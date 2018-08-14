<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 13/08/2018
 * Time: 22:09
 */

namespace License\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="license_license")
 *
 * Class License
 * @package License\Entity
 */
class License
{
    /**
     * License constructor.
     */
    public function __construct()
    {
        $this->isBlocked = false;
        $this->installLimit = 2;
        $this->timestamp  = new \DateTime('now');
        $this->lastUpdate = new \DateTime('now');
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="license_code", type="string", length=250, unique=true)
     */
    private $licenseCode;

    /**
     * @ORM\Column(name="valid_for_envato_username", type="string", length=250, nullable=true)
     */
    private $validForEnvatoUsername;

    /**
     * @ORM\Column(name="licensed_ip", type="text", nullable=true)
     */
    private $licensedIp;

    /**
     * @ORM\Column(name="licensed_domain", type="text", nullable=true)
     */
    private $licensedDomain;

    /**
     * @ORM\Column(name="install_limit", type="integer", nullable=true)
     */
    private $installLimit;

    /**
     * @ORM\Column(name="license_expiration_date", type="datetime", nullable=true)
     */
    private $licenseExpirationDate;

    /**
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(name="is_blocked", type="boolean", options={"default": 0})
     */
    private $isBlocked;

    /**
     * @ORM\Column(name="last_update", type="datetime", columnDefinition="DATETIME on UPDATE CURRENT_TIMESTAMP")
     */
    private $lastUpdate;

    /**
     * @ORM\Column(name="timestamp", type="datetime", columnDefinition="DATETIME DEFAULT CURRENT_TIMESTAMP")
     */
    private $timestamp;

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return array
     */
    public function getArrayCopy(): array
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
    public function getLicenseCode()
    {
        return $this->licenseCode;
    }

    /**
     * @param mixed $licenseCode
     * @return $this
     */
    public function setLicenseCode($licenseCode)
    {
        $this->licenseCode = $licenseCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidForEnvatoUsername()
    {
        return $this->validForEnvatoUsername;
    }

    /**
     * @param mixed $validForEnvatoUsername
     * @return $this
     */
    public function setValidForEnvatoUsername($validForEnvatoUsername)
    {
        $this->validForEnvatoUsername = $validForEnvatoUsername;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLicensedIp()
    {
        return $this->licensedIp;
    }

    /**
     * @param mixed $licensedIp
     * @return $this
     */
    public function setLicensedIp($licensedIp)
    {
        $this->licensedIp = $licensedIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLicensedDomain()
    {
        return $this->licensedDomain;
    }

    /**
     * @param mixed $licensedDomain
     * @return $this
     */
    public function setLicensedDomain($licensedDomain)
    {
        $this->licensedDomain = $licensedDomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallLimit()
    {
        return $this->installLimit;
    }

    /**
     * @param mixed $installLimit
     * @return $this
     */
    public function setInstallLimit($installLimit)
    {
        $this->installLimit = $installLimit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLicenseExpirationDate()
    {
        return $this->licenseExpirationDate;
    }

    /**
     * @param mixed $licenseExpirationDate
     * @return $this
     */
    public function setLicenseExpirationDate($licenseExpirationDate)
    {
        $this->licenseExpirationDate = $licenseExpirationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * @param mixed $isBlocked
     * @return $this
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param mixed $lastUpdate
     * @return $this
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}