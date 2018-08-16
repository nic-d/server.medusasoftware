<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 00:42
 */

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
$error = null;

// Randomly generated - for extra security, change these.
$username = '2B5CEC5A';
$password = '0c1d9b54';

if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
    header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
    header("HTTP/1.0 401 Unauthorized");
    exit;
} else {
    if ($_SERVER['PHP_AUTH_USER'] == $username && $_SERVER['PHP_AUTH_PW'] == $password) {
    } else {
        header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Oops! It require login to proceed further. Please enter your login detail\n";
        exit;
    }
}

// Start processing the install
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /** @var InstallService $installService */
    $installService = new InstallService();

    try {
        $installService->run($_POST);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

/**
 * Class InstallService
 */
class InstallService
{
    /** @var \mysqli $mysqliInstance */
    protected $mysqliInstance;

    /** @var array $config */
    protected $config = [];

    /** @var array $parsedConfig */
    protected $parsedConfig = [];

    /** @var string $parsedAppVersion */
    protected $parsedAppVersion;

    /** @var string $parsedInstallerVersion */
    protected $parsedInstallerVersion;

    /** @var string $parsedUpdaterVersion */
    protected $parsedUpdaterVersion;

    /** @var string $parsedProductId */
    protected $parsedProductId;

    /** @var array $parsedNotifyUrls */
    protected $parsedNotifyUrls = [];

    /** @var array $parsedRequirements */
    protected $parsedRequirements = [];

    /** @var array $parsedSchemaFiles */
    protected $parsedSchemaFiles = [];

    /** @var $curlInstance */
    protected $curlInstance;

    /**
     * @param array $config
     * @return bool
     * @throws \Exception
     */
    public function run(array $config = [])
    {
        $this->parseApplicationJson();

        $this->convertInputData($config);
        $this->checkRequirements();
        $this->testDatabaseConnection($this->config);
        $this->importSchema();

        return $this->finalise();
    }

    /**
     * @param array $config
     */
    public function convertInputData(array $config = [])
    {
        $appUrl = $_SERVER['HTTP_ORIGIN'] . '/';

        $this->config = [
            'DB_VM_HOST' => $config['host'],
            'DB_USERNAME' => $config['user'],
            'DB_PASSWORD' => $config['password'],
            'DB_NAME' => $config['name'],
            'DB_PORT' => $config['port'],
            'APP_URL' => $appUrl,
            'LICENSE' => $config['license'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function checkRequirements()
    {
        // First notify the licensing server
        $this->notifyLicense($this->config);

        // Get the PHP Version required
        $systemPhpVersion = phpversion();
        $requiredPhpVersion = $this->getParsedRequirements()['php'];

        if (!version_compare($systemPhpVersion, $requiredPhpVersion, '>=')) {
            throw new \Exception('PHP Version incompatible. Requires: ' . $requiredPhpVersion . ' You have: ' . $systemPhpVersion);
        }

        // Check the PHP extensions
        if (isset($this->getParsedRequirements()['php-extensions'])) {
            foreach ($this->getParsedRequirements()['php-extensions'] as $extension) {
                if (!extension_loaded($extension)) {
                    throw new \Exception('PHP Extension: ' . $extension . ' required, but not installed');
                }
            }
        }
    }

    /**
     * @param array $dbConfig
     * @throws \Exception
     */
    public function testDatabaseConnection(array $dbConfig = [])
    {
        /** @var \mysqli $mysqli */
        @$mysqli = new \mysqli(
            $dbConfig['DB_VM_HOST'],
            $dbConfig['DB_USERNAME'],
            $dbConfig['DB_PASSWORD'],
            $dbConfig['DB_NAME'],
            $dbConfig['DB_PORT']
        );

        if ($mysqli->connect_errno) {
            throw new \Exception('Failed to connect to database: ' . $mysqli->connect_error);
        }

        $this->mysqliInstance = $mysqli;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function importSchema()
    {
        /** @var array $files */
        $files = $this->getParsedSchemaFiles();

        if (empty($files)) {
            return true;
        }

        foreach ($files as $file) {
            $fileContents = file_get_contents(getcwd() . '/' . $file);
            $this->mysqliInstance->multi_query($fileContents);

            if ($this->mysqliInstance->errno) {
                throw new \Exception('Error importing: ' . $file . '. MySQL Message: ' . $this->mysqliInstance->error);
            }
        }
    }

    /**
     * @return bool
     */
    public function finalise(): bool
    {
        $this->writeEnvFile($this->config);
        $this->mysqliInstance->close();
        $this->notifyInstall($this->config);

        return true;
    }

    /**
     * @throws \Exception
     */
    public function parseApplicationJson()
    {
        if (!$this->doesApplicationJsonExist()) {
            throw new \Exception('The application.json file is missing');
        }

        // Decode the json file contents
        $parsedConfig = json_decode(base64_decode(file_get_contents(getcwd() . '/application.json')), true);

        if (is_null($parsedConfig)) {
            throw new Exception('Couldn\'t parse the application.json file');
        }

        $this->setParsedConfig($parsedConfig)
            ->setParsedProductId($parsedConfig['product-id'])
            ->setParsedAppVersion($parsedConfig['app-version'])
            ->setParsedInstallerVersion($parsedConfig['installer-version'])
            ->setParsedUpdaterVersion($parsedConfig['updater-version'])
            ->setParsedNotifyUrls($parsedConfig['notify-urls'])
            ->setParsedRequirements($parsedConfig['requirements'])
            ->setParsedSchemaFiles($parsedConfig['schema-files']);
    }

    /**
     * @return bool
     */
    public function doesApplicationJsonExist(): bool
    {
        return file_exists(getcwd() . '/application.json');
    }

    /**
     * @param array $config
     * @return bool
     */
    public function writeEnvFile(array $config = []): bool
    {
        $this->createEnvFile();

        $fileBuffer = '';
        foreach ($config as $key => $value) {
            $fileBuffer .= "$key = \"$value\"\n";
        }

        return file_put_contents(getcwd() . '/.env.test', $fileBuffer);
    }

    /**
     * @return bool
     */
    public function createEnvFile(): bool
    {
        $envFilePath = getcwd() . '/.env.test';
        $envDistFilePath = getcwd() . '/.env.dist';

        if (!file_exists($envFilePath)) {
            return copy($envDistFilePath, $envFilePath);
        }

        return true;
    }

    /**
     * @param array $params = ['licenseCode', 'ip', 'domain']
     * @return bool
     * @throws \Exception
     */
    public function notifyLicense(array $params = []): bool
    {
        $this->prepareCurl();

        $ch = $this->curlInstance;
        $installUrl = $this->getParsedNotifyUrls()['license'];

        $postParams = [
            'licenseCode' => $params['LICENSE'],
            'ip' => $_SERVER['SERVER_ADDR'],
            'domain' => $_SERVER['HTTP_HOST'],
        ];

        curl_setopt_array($ch, [
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $installUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postParams,
        ]);

        // Execute this request
        $result = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpStatusCode !== 200) {
            throw new \Exception('Failed to verify your license code.');
        }

        $this->closeCurl();
        return true;
    }

    /**
     * @param array $params = ['license', 'product', 'ipAddress', 'domain', 'fullUrl', 'serverName', 'phpVersion']
     * @return bool
     */
    public function notifyInstall(array $params = []): bool
    {
        $this->prepareCurl();

        $ch = $this->curlInstance;
        $installUrl = $this->getParsedNotifyUrls()['install'];

        // Build the fullUrl
        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $postParams = [
            'ipAddress' => $_SERVER['SERVER_ADDR'],
            'domain' => $_SERVER['HTTP_HOST'],
            'fullUrl' => $fullUrl,
            'serverName' => $_SERVER['SERVER_NAME'],
            'phpVersion' => phpversion(),
            'license' => $params['LICENSE'],
            'product' => $this->getParsedProductId(),
        ];

        curl_setopt_array($ch, [
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $installUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postParams,
        ]);

        // Execute this request
        $result = curl_exec($ch);
        $this->closeCurl();
        return true;
    }

    /**
     * Prepares an instance of cUrl.
     */
    public function prepareCurl()
    {
        $curlInstance = curl_init();
        $this->curlInstance = $curlInstance;
    }

    /**
     * Destroys the cUrl instance.
     */
    public function closeCurl()
    {
        curl_close($this->curlInstance);
    }

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return array
     */
    protected function getParsedConfig(): array
    {
        return $this->parsedConfig;
    }

    /**
     * @param array $parsedConfig
     * @return $this
     */
    protected function setParsedConfig(array $parsedConfig)
    {
        $this->parsedConfig = $parsedConfig;
        return $this;
    }

    /**
     * @return array
     */
    protected function getParsedNotifyUrls(): array
    {
        return $this->parsedNotifyUrls;
    }

    /**
     * @param array $parsedNotifyUrls
     * @return $this
     */
    protected function setParsedNotifyUrls(array $parsedNotifyUrls)
    {
        $this->parsedNotifyUrls = $parsedNotifyUrls;
        return $this;
    }

    /**
     * @return array
     */
    protected function getParsedRequirements(): array
    {
        return $this->parsedRequirements;
    }

    /**
     * @param array $parsedRequirements
     * @return $this
     */
    protected function setParsedRequirements(array $parsedRequirements)
    {
        $this->parsedRequirements = $parsedRequirements;
        return $this;
    }

    /**
     * @return array
     */
    protected function getParsedSchemaFiles(): array
    {
        return $this->parsedSchemaFiles;
    }

    /**
     * @param array $parsedSchemaFiles
     * @return $this
     */
    protected function setParsedSchemaFiles(array $parsedSchemaFiles)
    {
        $this->parsedSchemaFiles = $parsedSchemaFiles;
        return $this;
    }

    /**
     * @return string
     */
    protected function getParsedProductId(): string
    {
        return $this->parsedProductId;
    }

    /**
     * @param string $parsedProductId
     * @return $this
     */
    protected function setParsedProductId(string $parsedProductId)
    {
        $this->parsedProductId = $parsedProductId;
        return $this;
    }

    /**
     * @return string
     */
    protected function getParsedAppVersion(): string
    {
        return $this->parsedAppVersion;
    }

    /**
     * @param string $parsedAppVersion
     * @return $this
     */
    protected function setParsedAppVersion(string $parsedAppVersion)
    {
        $this->parsedAppVersion = $parsedAppVersion;
        return $this;
    }

    /**
     * @return string
     */
    protected function getParsedInstallerVersion(): string
    {
        return $this->parsedInstallerVersion;
    }

    /**
     * @param string $parsedInstallerVersion
     * @return $this
     */
    protected function setParsedInstallerVersion(string $parsedInstallerVersion)
    {
        $this->parsedInstallerVersion = $parsedInstallerVersion;
        return $this;
    }

    /**
     * @return string
     */
    protected function getParsedUpdaterVersion(): string
    {
        return $this->parsedUpdaterVersion;
    }

    /**
     * @param string $parsedUpdaterVersion
     * @return $this
     */
    protected function setParsedUpdaterVersion(string $parsedUpdaterVersion)
    {
        $this->parsedUpdaterVersion = $parsedUpdaterVersion;
        return $this;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>INSTALLER - MedusaSoftware</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5 mb-2">Install</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-6">
            <?php if (!is_null($error)) { ?>
                <div class="alert alert-danger">
                    <p><?php echo htmlentities($error) ?></p>
                </div>
            <?php } ?>

            <form method="post">
                <div class="form-group">
                    <label for="license">License Key</label><input type="text" name="license" id="license"
                                                                   class="form-control" autocomplete="off" value="">
                </div>

                <div class="form-group">
                    <label for="host">Database Host</label><input type="text" name="host" id="host" class="form-control"
                                                                  autocomplete="off" value="localhost">
                </div>

                <div class="form-group">
                    <label for="port">Database Port</label><input type="number" name="port" id="port"
                                                                  class="form-control" autocomplete="off" value="3306">
                </div>

                <div class="form-group">
                    <label for="user">Database User</label><input type="text" name="user" id="user" class="form-control"
                                                                  autocomplete="off" value="">
                </div>

                <div class="form-group">
                    <label for="password">Database Password</label><input type="password" name="password" id="password"
                                                                          class="form-control" autocomplete="off"
                                                                          value="">
                </div>

                <div class="form-group">
                    <label for="name">Database Name</label><input type="text" name="name" id="name" class="form-control"
                                                                  autocomplete="off" value="">
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" class="btn&#x20;btn-primary" value="Install">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>