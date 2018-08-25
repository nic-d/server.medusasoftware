<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:48
 */

namespace Install\Controller;

use Zend\Http\Response;
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

    /**
     * @return ViewModel
     */
    public function searchAction()
    {
        /** @var string|null $query */
        $query = $this->params()->fromQuery('q', null);

        if (is_null($query)) {
            return $this->notFoundAction();
        }

        try {
            $matchingResults = $this->installService->search($query);
        } catch (\Exception $e) {
            $matchingResults = [];
        }

        return new ViewModel([
            'matchingResults' => $matchingResults,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function viewAction()
    {
        /** @var string|null $hash */
        $hash = $this->params()->fromRoute('hash');

        if (is_null($hash)) {
            return $this->notFoundAction();
        }

        try {
            $installation = $this->installService->getInstallation($hash);
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        // Prepare the edit form - to allow us to nullify the license
        $form = $this->installService->prepareEditForm($installation);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->installService->editInstall($form);
            }

            return $this->redirect()->toRoute('install.index');
        }

        return new ViewModel([
            'form' => $form,
            'installation' => $installation,
        ]);
    }
}