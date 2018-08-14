<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 07:55
 */

namespace User\Controller\Auth;

use Zend\Uri\Uri;
use Zend\Http\Request;
use Zend\Http\Response;
use User\Service\UserService;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use User\Service\Auth\AuthService;
use User\Service\TwoFactorService;
use User\Service\Auth\AuthAdapterService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class AuthController
 * @package User\Controller\Auth
 */
class AuthController extends AbstractActionController
{
    /** @var AuthService $authService */
    private $authService;

    /** @var UserService $userService */
    private $userService;

    /** @var TwoFactorService $twoFactorService */
    private $twoFactorService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     * @param UserService $userService
     * @param TwoFactorService $twoFactorService
     */
    public function __construct(
        AuthService $authService,
        UserService $userService,
        TwoFactorService $twoFactorService
    )
    {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * @return Response|ViewModel
     * @throws \Exception
     */
    public function loginAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        // Get the redirectUrl and prepare the login form
        $redirectUrl = $this->params()->fromQuery('redirectUrl', '');
        $form = $this->authService->prepareLoginForm($redirectUrl);

        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                // Get the redirectUrl if it exists
                $redirectUrl = $this->params()->fromPost('redirect_url');

                if (!is_null($redirectUrl) && !empty($redirectUrl)) {
                    /** @var Uri $uri */
                    $uri = new Uri($redirectUrl);

                    if (!$uri->isValid() || !is_null($uri->getHost())) {
                        $this->flashMessenger()->addErrorMessage('Invalid redirect url');
                        return $this->redirect()->toRoute('user.login');
                    }
                }

                /** @var array $data */
                $email = $form->get('email')->getValue();
                $password = $form->get('password')->getValue();

                /** @var Result $result */
                $result = $this->authService->login(
                    $email,
                    $password,
                    false
                );

                if (!is_bool($result) && $result->getCode() === Result::SUCCESS) {
                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('application.home');
                    }

                    return $this->redirect()->toUrl($redirectUrl);
                }

                if ($result->getCode() === AuthAdapterService::SUCCESS_BUT_REQUIRES_2FA) {
                    $options = [];

                    if (!empty($redirectUrl)) {
                        $options['query'] = ['redirectUrl' => $redirectUrl];
                    }

                    return $this->redirect()->toRoute('user.login/2fa', [], $options);
                }

                $this->flashMessenger()->addErrorMessage('Invalid login detail');
            }
        }

        return new ViewModel([
            'form'        => $form,
            'redirectUrl' => $redirectUrl
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function twoFactorLoginAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        /** @var string|null $redirectUrl */
        $redirectUrl = $this->params()->fromQuery('redirectUrl');

        // Check that we should run this route
        if (!isset($_SESSION['twoFactorIdentity'])) {
            return $this->redirect()->toRoute('user.login');
        }

        // Prepare the 2FA form
        $form = $this->twoFactorService->prepareLoginTwoFactorForm();

        if ($request->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $result = $this->twoFactorService->verifyLoginTwoFactorCode($form);

                if ($result) {
                    $email = $_SESSION['twoFactorIdentity']['email'];
                    $password = $_SESSION['twoFactorIdentity']['password'];

                    /** @var Result $result */
                    $result = $this->authService->login(
                        $email,
                        $password,
                        false,
                        true
                    );

                    // If the auth is OK, let's redirect!
                    if (!is_bool($result) && $result->getCode() === Result::SUCCESS) {
                        if (!is_null($redirectUrl)) {
                            return $this->redirect()->toUrl($redirectUrl);
                        }

                        return $this->redirect()->toRoute('application.home');
                    }
                }

                return $this->redirect()->toRoute('user.login');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function logoutAction()
    {
        $this->authService->logout();
        return $this->redirect()->toRoute('user.login');
    }
}