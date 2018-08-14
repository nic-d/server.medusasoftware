<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 07:53
 */

namespace User\Service;

use User\Entity\User;
use User\Form\AccountForm;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Authentication\AuthenticationService;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

/**
 * Class UserService
 * @package User\Service
 */
class UserService
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var int $resultsPerPage */
    private $resultsPerPage = 15;

    /** @var AuthenticationService $authenticationService */
    private $authenticationService;

    /** @var TwoFactorService $twoFactorService */
    private $twoFactorService;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $entityManager
     * @param AuthenticationService $authenticationService
     * @param TwoFactorService $twoFactorService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AuthenticationService $authenticationService,
        TwoFactorService $twoFactorService
    )
    {
        $this->entityManager = $entityManager;
        $this->authenticationService = $authenticationService;
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * @param int|string $id
     * @param string $getBy
     * @return User
     * @throws \Exception
     */
    public function getUser($id, string $getBy = 'id'): User
    {
        switch ($getBy) {
            case 'id':
                return $this->getUserById($id);
                break;

            case 'username':
                return $this->getUserByUsername($id);
                break;

            case 'email':
                return $this->getUserByEmail($id);
                break;

            default:
                throw new \Exception('Unknown getBy: ' . $getBy);
        }
    }
    
    /**
     * @param $id
     * @return User
     * @throws \Exception
     */
    private function getUserById($id): User
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);

        if (is_null($user)) {
            throw new \Exception('User not found');
        }

        return $user;
    }

    /**
     * @param string $username
     * @return User
     * @throws \Exception
     */
    private function getUserByUsername(string $username): User
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy([
                'username' => $username,
            ]);

        if (is_null($user)) {
            throw new \Exception('User not found');
        }

        return $user;
    }

    /**
     * @param string $email
     * @return User
     * @throws \Exception
     */
    private function getUserByEmail(string $email): User
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $email,
            ]);

        if (is_null($user)) {
            throw new \Exception('User not found');
        }

        return $user;
    }

    /**
     * @return bool
     */
    public function usersAlreadyExist(): bool
    {
        /** @var array $users */
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        if (empty($users)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function generateUser(): bool
    {
        // Only generate a user if one already exists
        if ($this->usersAlreadyExist()) {
            return true;
        }

        /** @var User $user */
        $user = new User();
        $user->setRole('Admin');
        $user->setUsername('nic');
        $user->setEmail('medusasoftware.io@gmail.com');
        $user->setPassword(password_hash('DqwR7jBaTBFx9dJx', PASSWORD_DEFAULT));

        $secretKey = $this->twoFactorService->generateSecretKey();
        $user->setGoogleTwoFactorSecret($secretKey);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param AccountForm $accountForm
     * @return bool
     */
    public function editAccount(AccountForm $accountForm): bool
    {
        /** @var User $user */
        $user = $accountForm->getObject();

        // We need to merge the user back into the entity manager
        /** User $user */
        $user = $this->entityManager->merge($user);

        // The password updating is done in the user subscriber class
        // Entity\Subscriber\UserSubscriber

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param User $user
     * @return AccountForm
     */
    public function prepareAccountForm(User $user): AccountForm
    {
        /** @var AccountForm $form */
        $form = new AccountForm();
        $form->setHydrator(new DoctrineObject($this->entityManager, User::class));
        $form->bind($user);

        return $form;
    }

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return int
     */
    public function getResultsPerPage()
    {
        return $this->resultsPerPage;
    }

    /**
     * @param int $resultsPerPage
     * @return $this
     */
    public function setResultsPerPage($resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
        return $this;
    }
}