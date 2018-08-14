<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/06/2018
 * Time: 19:21
 */

namespace User\Service;

use User\Entity\User;
use User\Entity\BackupCode;
use User\Form\LoginTwoFactorForm;
use PragmaRX\Google2FA\Google2FA;
use User\Form\AccountTwoFactorForm;
use User\Form\AccountTwoFactorCodeForm;
use Doctrine\ORM\EntityManagerInterface;
use User\Form\AccountTwoFactorDisableForm;
use Zend\Authentication\AuthenticationService;

/**
 * Class TwoFactorService
 * @package User\Service
 */
class TwoFactorService
{
    /** @var Google2FA $google2FA */
    private $google2FA;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var AuthenticationService $authenticationService */
    private $authenticationService;

    /**
     * TwoFactorService constructor.
     * @param EntityManagerInterface $entityManager
     * @param Google2FA $google2FA
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Google2FA $google2FA,
        AuthenticationService $authenticationService
    )
    {
        $this->google2FA = $google2FA;
        $this->entityManager = $entityManager;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return string
     */
    public function generateSecretKey(): string
    {
        return $this->google2FA->generateSecretKey();
    }

    /**
     * @param LoginTwoFactorForm $loginTwoFactorForm
     * @return bool
     */
    public function verifyLoginTwoFactorCode(LoginTwoFactorForm $loginTwoFactorForm): bool
    {
        // We need to get the user identity from the 2fa key in the session
        $twoFAIdentity = $_SESSION['twoFactorIdentity'];

        /** @var string $verificationSecret */
        $verificationSecret = $loginTwoFactorForm->get('verificationSecret')->getValue();

        // First let's check if this is a backup code
        if ($this->backupCodeExists($twoFAIdentity['email'], $verificationSecret)) {
            return true;
        }

        // Verify the code
        $verified = $this->google2FA->verifyKey(
            $twoFAIdentity['googleTwoFactorSecret'],
            $verificationSecret
        );

        return $verified;
    }

    /**
     * @param AccountTwoFactorForm $accountTwoFactorForm
     * @return bool
     * @throws \Exception
     */
    public function verifyTwoFactorCode(AccountTwoFactorForm $accountTwoFactorForm): bool
    {
        /** @var User $user */
        $user = $this->entityManager->merge($this->authenticationService->getIdentity());

        /** @var string $verificationSecret */
        $verificationSecret = $accountTwoFactorForm->get('verificationSecret')->getValue();

        // Verify the code
        $verified = $this->google2FA->verifyKey(
            $user->getGoogleTwoFactorSecret(),
            $verificationSecret
        );

        $user->setGoogleTwoFactorVerified((bool)$verified);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Let's create 10 backup codes
        // We're not passing in the merged identity, as this method merges the $user param
        $this->generateBackupCodes($user);

        return true;
    }

    /**
     * @param AccountTwoFactorDisableForm $accountTwoFactorDisableForm
     * @return bool
     */
    public function disableTwoFactor(AccountTwoFactorDisableForm $accountTwoFactorDisableForm): bool
    {
        /** @var User $user */
        $user = $this->entityManager->merge($this->authenticationService->getIdentity());
        $user->setGoogleTwoFactorVerified(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param User $user
     * @return string
     */
    public function generateQrCode(User $user): string
    {
        $appName = 'MedusaSoftware SERVER';
        $email = $user->getEmail();

        return $this->google2FA->getQRCodeInline(
            $appName,
            $email,
            $user->getGoogleTwoFactorSecret()
        );
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    public function generateBackupCodes(User $user)
    {
        // Remove any existing backup codes
        $backupCodes = $user->getBackupCodes();

        if (!empty($backupCodes)) {
            foreach ($backupCodes as $backupCode) {
                $this->entityManager->remove($backupCode);
            }
        }

        // Generate 10 backup codes
        $i = 0;

        while ($i < 10) {
            $i++;

            /** @var BackupCode $backupCode */
            $backupCode = new BackupCode();
            $backupCode->setUser($user);
            $backupCode->setCode(hash('crc32', random_bytes(8)));

            $this->entityManager->persist($backupCode);
        }

        $this->entityManager->flush();
    }

    /**
     * @param string $email
     * @param string $code
     * @return bool
     */
    private function backupCodeExists(string $email, string $code): bool
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $email,
            ]);

        if (is_null($user)) {
            return false;
        }

        /** @var BackupCode $backupCode */
        $backupCode = $this->entityManager
            ->getRepository(BackupCode::class)
            ->findOneBy([
                'user' => $user,
                'code' => $code,
            ]);

        if (is_null($backupCode)) {
            return false;
        }

        // Now that we know the backup code exists, let's remove it so it can't be reused
        $this->entityManager->remove($backupCode);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return AccountTwoFactorForm
     */
    public function prepareAccountTwoFactorForm(): AccountTwoFactorForm
    {
        return new AccountTwoFactorForm();
    }

    /**
     * @return AccountTwoFactorDisableForm
     */
    public function prepareAccountTwoFactorDisableForm(): AccountTwoFactorDisableForm
    {
        return new AccountTwoFactorDisableForm();
    }

    /**
     * @return AccountTwoFactorCodeForm
     */
    public function prepareAccountTwoFactorCodeForm(): AccountTwoFactorCodeForm
    {
        return new AccountTwoFactorCodeForm();
    }

    /**
     * @return LoginTwoFactorForm
     */
    public function prepareLoginTwoFactorForm(): LoginTwoFactorForm
    {
        return new LoginTwoFactorForm();
    }
}