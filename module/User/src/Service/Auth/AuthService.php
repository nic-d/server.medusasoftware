<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 07:54
 */

namespace User\Service\Auth;

use User\Form\LoginForm;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;
use User\Service\TwoFactorService;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class AuthService
 * @package User\Service\Auth
 */
class AuthService
{
    /** @var AuthenticationService $authenticationService */
    private $authenticationService;

    /** @var SessionManager $sessionManager */
    private $sessionManager;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var TwoFactorService $twoFactorService */
    private $twoFactorService;

    /**
     * AuthService constructor.
     * @param AuthenticationService $authenticationService
     * @param SessionManager $sessionManager
     * @param EntityManagerInterface $entityManager
     * @param TwoFactorService $twoFactorService
     */
    public function __construct(
        AuthenticationService $authenticationService,
        SessionManager $sessionManager,
        EntityManagerInterface $entityManager,
        TwoFactorService $twoFactorService
    )
    {
        $this->authenticationService = $authenticationService;
        $this->sessionManager = $sessionManager;
        $this->entityManager = $entityManager;
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $rememberMe
     * @param bool $passed2FA
     * @return Result|bool
     */
    public function login($email, $password, $rememberMe = false, bool $passed2FA = false)
    {
        // Prevent multiple logins
        if (!is_null($this->authenticationService->getIdentity())) {
            $this->authenticationService->clearIdentity();
        }

        /** @var AuthAdapterService $authAdapter */
        $authAdapter = $this->authenticationService->getAdapter();

        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);
        $authAdapter->setPassed2FA($passed2FA);
        $result = $this->authenticationService->authenticate();

        // If the result is -5 (2fa error) then add the identity to the session
        // This allows us to use it on the 2FA verification page.
        if ($result->getCode() === AuthAdapterService::SUCCESS_BUT_REQUIRES_2FA) {
            $this->sessionManager->getStorage()->twoFactorIdentity = [
                'email' => $email,
                'password' => $password,
                'googleTwoFactorSecret' => $result->getIdentity()->getGoogleTwoFactorSecret(),
            ];
        }

        // If it's a success result, let's remove the twoFactorIdentity key from the session
        if ($result->getCode() === Result::SUCCESS) {
            $this->sessionManager->getStorage()->clear('twoFactorIdentity');
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    public function logout()
    {
        // Remove the identity from the session even if there isn't one
        $this->authenticationService->clearIdentity();
    }

    /**
     * @param string $redirectUrl
     * @return LoginForm
     */
    public function prepareLoginForm(string $redirectUrl = ''): LoginForm
    {
        /** @var LoginForm $form */
        $form = new LoginForm();

        if (!empty($redirectUrl)) {
            $form->get('redirect_url')->setValue($redirectUrl);
        }

        return $form;
    }

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @param AuthenticationService $authenticationService
     * @return $this
     */
    protected function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     * @return SessionManager
     */
    protected function getSessionManager()
    {
        return $this->sessionManager;
    }

    /**
     * @param SessionManager $sessionManager
     * @return $this
     */
    protected function setSessionManager($sessionManager)
    {
        $this->sessionManager = $sessionManager;
        return $this;
    }
}