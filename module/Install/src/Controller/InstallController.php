<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:48
 */

namespace Install\Controller;

use Zend\View\Model\ViewModel;
use Install\Service\InstallService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class InstallController
 * @package Install\Controller
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

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var int $page */
        $page = $this->params()->fromQuery('page', 1);

        // Get all installations (paginated)
        $installations = $this->installService->getInstallations($page);

        return new ViewModel([
            'page' => $page,
            'installations' => $installations,
        ]);
    }
}