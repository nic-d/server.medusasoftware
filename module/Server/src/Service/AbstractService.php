<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 19:19
 */

namespace Server\Service;

/**
 * Class AbstractService
 * @package Server\Service
 */
abstract class AbstractService
{
    /** @var array $parsedConfig */
    private $parsedConfig = [];

    /** @var int|null $parsedVersion */
    private $parsedVersion;

    /** @var string $parsedProductId */
    private $parsedProductId;

    /** @var array $parsedNotifyUrls */
    private $parsedNotifyUrls = [];

    /** @var array $parsedRequirements */
    private $parsedRequirements = [];

    /** @var array $parsedPermissions */
    private $parsedPermissions = [];

    /** @var array $parsedSchemaFiles */
    private $parsedSchemaFiles = [];

    /** @var $curlInstance */
    private $curlInstance;

    /**
     * AbstractService constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->parseApplicationJson();
    }

    /**
     * @throws \Exception
     */
    private function parseApplicationJson()
    {
        if (!$this->doesApplicationJsonExist()) {
            throw new \Exception('The application.json file is missing');
        }

        // Decode the json file contents
        $this->parsedConfig = json_decode(file_get_contents(getcwd() . '/application.json'), true);

        // Set the individual keys
        $this->parsedProductId = $this->parsedConfig['product-id'] ?? '';
        $this->parsedVersion = $this->parsedConfig['current-version'] ?? 0;
        $this->parsedNotifyUrls = $this->parsedConfig['notify-urls'] ?? [];
        $this->parsedRequirements = $this->parsedConfig['requirements'] ?? [];
        $this->parsedPermissions = $this->parsedConfig['permissions'] ?? [];
        $this->parsedSchemaFiles = $this->parsedConfig['schema-files'] ?? [];
    }

    /**
     * @return bool
     */
    private function doesApplicationJsonExist(): bool
    {
        return file_exists(getcwd() . '/application.json');
    }

    /**
     * @param array $config
     * @return bool
     */
    protected function writeEnvFile(array $config = []): bool
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
    private function createEnvFile(): bool
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
    protected function notifyLicense(array $params = []): bool
    {
        $this->prepareCurl();

        $ch = $this->curlInstance;
        $installUrl = $this->getParsedNotifyUrls()['license'];

        $postParams = [
            'licenseCode' => $params['license'],
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
    protected function notifyInstall(array $params = []): bool
    {
        $this->prepareCurl();

        $ch = $this->curlInstance;
        $installUrl = $this->getParsedNotifyUrls()['install'];

        // Build the fullUrl
        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $postParams = [
            'ipAddress' => $_SERVER['SERVER_ADDR'],
            'domain'    => $_SERVER['HTTP_HOST'],
            'fullUrl'   => $fullUrl,
            'serverName' => $_SERVER['SERVER_NAME'],
            'phpVersion' => phpversion(),
            'license' => $params['license'],
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
    private function prepareCurl()
    {
        $curlInstance = curl_init();
        $this->curlInstance = $curlInstance;
    }

    /**
     * Destroys the cUrl instance.
     */
    private function closeCurl()
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
     * @return int|null
     */
    protected function getParsedVersion(): int
    {
        return $this->parsedVersion;
    }

    /**
     * @param int|null $parsedVersion
     * @return $this
     */
    protected function setParsedVersion(int $parsedVersion)
    {
        $this->parsedVersion = $parsedVersion;
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
    protected function getParsedPermissions(): array
    {
        return $this->parsedPermissions;
    }

    /**
     * @param array $parsedPermissions
     * @return $this
     */
    protected function setParsedPermissions(array $parsedPermissions)
    {
        $this->parsedPermissions = $parsedPermissions;
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
}