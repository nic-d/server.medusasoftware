<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 13:55
 */

namespace Server\Controller;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Server\Service\InstallService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class InstallController
 * @package Server\Controller
 */
class InstallController extends AbstractActionController
{
    /** @var InstallService $installService */
    private $installService;

    /**
     * InstallController constructor.
     * @param InstallService $installService
     */
    public function __construct(InstallService $installService)
    {
        $this->installService = $installService;
    }

    public function indexAction()
    {
        // TODO: Change this to use the form data!
        $this->installService->run([
            'DB_VM_HOST' => '10.0.2.2',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => 'mysql',
            'DB_NAME' => 'installer_test',
            'DB_PORT' => '3306',
            'license' => 'b1a13c6b-a604-4c1e-ae3d-bab9e4ccf7d2',
        ]);
        // TODO: Get the requirements
    }
}