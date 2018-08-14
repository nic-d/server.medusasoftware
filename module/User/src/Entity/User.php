<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 06:16
 */

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 *
 * Class User
 * @package User\Entity
 */
class User
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->role = 'Admin';
        $this->googleTwoFactorVerified = false;
        $this->backupCodes = new ArrayCollection();
        $this->accountCreationDate = new \DateTime('now');
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=250, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="email", type="string", length=250, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=250, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(name="role", type="string", length=20)
     */
    private $role;

    /**
     * @ORM\Column(name="account_creation_date", type="datetime", nullable=true)
     */
    private $accountCreationDate;

    /**
     * @ORM\Column(name="google_two_factor_secret", type="string", length=255, nullable=true)
     */
    private $googleTwoFactorSecret;

    /**
     * @ORM\Column(name="google_two_factor_verified", type="boolean", options={"default": 0})
     */
    private $googleTwoFactorVerified;

    /**
     * @ORM\OneToMany(targetEntity="User\Entity\BackupCode", mappedBy="user", cascade={"persist", "remove"})
     */
    private $backupCodes;

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountCreationDate()
    {
        return $this->accountCreationDate;
    }

    /**
     * @param mixed $accountCreationDate
     * @return $this
     */
    public function setAccountCreationDate($accountCreationDate)
    {
        $this->accountCreationDate = $accountCreationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleTwoFactorSecret()
    {
        return $this->googleTwoFactorSecret;
    }

    /**
     * @param mixed $googleTwoFactorSecret
     * @return $this
     */
    public function setGoogleTwoFactorSecret($googleTwoFactorSecret)
    {
        $this->googleTwoFactorSecret = $googleTwoFactorSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleTwoFactorVerified()
    {
        return $this->googleTwoFactorVerified;
    }

    /**
     * @param mixed $googleTwoFactorVerified
     * @return $this
     */
    public function setGoogleTwoFactorVerified($googleTwoFactorVerified)
    {
        $this->googleTwoFactorVerified = $googleTwoFactorVerified;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackupCodes()
    {
        return $this->backupCodes;
    }

    /**
     * @param mixed $backupCodes
     * @return $this
     */
    public function setBackupCodes($backupCodes)
    {
        $this->backupCodes = $backupCodes;
        return $this;
    }
}