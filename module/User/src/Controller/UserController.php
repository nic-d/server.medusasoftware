<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 06:15
 */

namespace User\Controller;

use Zend\Http\Request;
use Zend\Http\Response;
use User\Service\UserService;
use Zend\View\Model\ViewModel;
use User\Service\TwoFactorService;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class UserController
 * @package User\Controller
 */
class UserController extends AbstractActionController
{
    /** @var UserService $userService */
    private $userService;

    /** @var TwoFactorService $twoFactorService */
    private $twoFactorService;

    /**
     * UserController constructor.
     * @param UserService $userService
     * @param TwoFactorService $twoFactorService
     */
    public function __construct(
        UserService $userService,
        TwoFactorService $twoFactorService
    )
    {
        $this->userService = $userService;
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * @return Response|ViewModel
     */
    public function accountAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        // We need to get the user from the DB to get the updated settings
        try {
            $user = $this->userService->getUser($this->identity()->getId(), 'id');
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('user.logout');
        }

        $accountForm = $this->userService->prepareAccountForm($user);
        $accountTwoFactorForm = $this->twoFactorService->prepareAccountTwoFactorForm();
        $accountTwoFactorDisableForm = $this->twoFactorService->prepareAccountTwoFactorDisableForm();
        $accountTwoFactorGenerateForm = $this->twoFactorService->prepareAccountTwoFactorCodeForm();

        if ($request->isPost()) {
            $accountForm->setData($this->params()->fromPost());

            if ($accountForm->isValid()) {
                $this->userService->editAccount($accountForm);
            }
        }

        // Check if the iconv extension is loaded
        if (extension_loaded('iconv')) {
            $qrCode = $this->twoFactorService->generateQrCode($user);
        } else {
            $qrCode = null;
        }

        return new ViewModel([
            'user'                         => $user,
            'accountForm'                  => $accountForm,
            'accountTwoFactorForm'         => $accountTwoFactorForm,
            'accountTwoFactorDisableForm'  => $accountTwoFactorDisableForm,
            'accountTwoFactorGenerateForm' => $accountTwoFactorGenerateForm,

            'twoFactorAuth' => [
                'qrCode' => $qrCode,
            ],
        ]);
    }
}