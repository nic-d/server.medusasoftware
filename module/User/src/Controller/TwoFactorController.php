<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/06/2018
 * Time: 19:41
 */

namespace User\Controller;

use Zend\Http\Request;
use Zend\Http\Response;
use User\Service\TwoFactorService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class TwoFactorController
 * @package User\Controller
 */
class TwoFactorController extends AbstractActionController
{
    /** @var TwoFactorService $twoFactorService */
    private $twoFactorService;

    /**
     * TwoFactorController constructor.
     * @param TwoFactorService $twoFactorService
     */
    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function updateTwoFactorAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $form = $this->twoFactorService->prepareAccountTwoFactorForm();

        if ($request->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->twoFactorService->verifyTwoFactorCode($form);
            }
        }

        return $this->redirect()->toRoute('user.account');
    }

    /**
     * @return Response
     */
    public function disableTwoFactorAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $form = $this->twoFactorService->prepareAccountTwoFactorDisableForm();

        if ($request->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->twoFactorService->disableTwoFactor($form);
            }
        }

        return $this->redirect()->toRoute('user.account');
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function generateBackupCodesAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $form = $this->twoFactorService->prepareAccountTwoFactorCodeForm();

        if ($request->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->twoFactorService->generateBackupCodes($this->identity());
            }
        }

        return $this->redirect()->toRoute('user.account');
    }
}