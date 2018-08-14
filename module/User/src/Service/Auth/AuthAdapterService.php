<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 07:57
 */

namespace User\Service\Auth;

use User\Entity\User;
use Zend\Authentication\Result;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Authentication\Adapter\AdapterInterface;

/**
 * Class AuthAdapterService
 * @package User\Service\Auth
 */
class AuthAdapterService implements AdapterInterface
{
    /** @var string $email */
    private $email;

    /** @var string $password */
    private $password;

    /** @var bool $passed2FA */
    private $passed2FA;

    /** @var User $user */
    private $user;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    // We'll use this for 2fa enabled accounts
    const SUCCESS_BUT_REQUIRES_2FA = -5;

    /**
     * AuthAdapterService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Result
     */
    public function authenticate()
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $this->email,
            ]);

        if (is_null($user)) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                [
                    'Invalid Credentials',
                ]
            );
        }

        // Check if the password is correct
        if (password_verify($this->password, $user->getPassword())) {
            // If this account has 2FA enabled and it hasn't been passed:
            if ($user->getGoogleTwoFactorVerified() && !$this->isPassed2FA()) {
                return new Result(
                    self::SUCCESS_BUT_REQUIRES_2FA,
                    $user,
                    [
                        'Requires 2FA code',
                    ]
                );
            }

            return new Result(
                Result::SUCCESS,
                $user,
                [
                    'Authenticated successfully',
                ]
            );
        }

        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            [
                'Invalid Credentials',
            ]
        );
    }

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPassed2FA(): bool
    {
        return $this->passed2FA;
    }

    /**
     * @param bool $passed2FA
     * @return $this
     */
    public function setPassed2FA(bool $passed2FA)
    {
        $this->passed2FA = $passed2FA;
        return $this;
    }
}