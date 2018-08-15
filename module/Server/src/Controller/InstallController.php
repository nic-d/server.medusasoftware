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

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        // Prepare the install form
        $form = $this->installService->prepareInstallForm();

        if ($request->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                try {
                    $this->installService->processForm($form);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    return $this->redirect()->toRoute('server.install');
                }
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }
}