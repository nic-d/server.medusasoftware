<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 13:59
 */

namespace Server\Service;

use Server\Form\InstallForm;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;

/**
 * Class InstallService
 * @package Server\Service
 */
class InstallService extends AbstractService
{
    /** @var \mysqli $mysqliInstance */
    private $mysqliInstance;

    /** @var array $config */
    private $config = [];

    /** @var FormElementManager $formElementManager */
    private $formElementManager;

    /**
     * InstallService constructor.
     * @param FormElementManager $formElementManager
     * @throws \Exception
     */
    public function __construct(FormElementManager $formElementManager)
    {
        $this->formElementManager = $formElementManager;
        parent::__construct();
    }

    /**
     * @param InstallForm $installForm
     * @throws \Exception
     */
    public function processForm(InstallForm $installForm)
    {
        $config = [
            'DB_VM_HOST'  => $installForm->get('host')->getValue(),
            'DB_USERNAME' => $installForm->get('user')->getValue(),
            'DB_PASSWORD' => $installForm->get('password')->getValue(),
            'DB_NAME'     => $installForm->get('name')->getValue(),
            'DB_PORT'     => $installForm->get('port')->getValue(),
            'LICENSE'     => $installForm->get('license')->getValue(),
        ];

        $this->run($config);
    }

    /**
     * @param array $config
     * @return bool
     * @throws \Exception
     */
    public function run(array $config = [])
    {
        $this->config = $config;
        $this->checkRequirements();
        $this->checkFilesystemPermissions();
        $this->testDatabaseConnection($config);
        $this->importSchema();

        return $this->finalise();
    }

    /**
     * @throws \Exception
     */
    private function checkRequirements()
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
     * @throws \Exception
     */
    private function checkFilesystemPermissions()
    {
        // Get the permissions
        $permissions = $this->getParsedPermissions()['filesystem'];

        foreach ($permissions as $key => $value) {
            $filePermission = substr(sprintf('%o', fileperms(getcwd() . '/' . $key)), -4);

            if ((int)$filePermission !== (int)$value) {
                throw new \Exception('Incorrect permissions for ' . $key . ', requires: ' . $value);
            }
        }
    }

    /**
     * @param array $dbConfig
     * @throws \Exception
     */
    private function testDatabaseConnection(array $dbConfig = [])
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
    private function importSchema()
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
    private function finalise(): bool
    {
        $this->writeEnvFile($this->config);
        $this->mysqliInstance->close();
        $this->notifyInstall($this->config);

        return true;
    }

    /**
     * @return InstallForm
     */
    public function prepareInstallForm(): InstallForm
    {
        return $this->formElementManager->get(InstallForm::class);
    }
}