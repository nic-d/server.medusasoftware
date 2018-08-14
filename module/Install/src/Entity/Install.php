<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:50
 */

namespace Install\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Install\Repository\InstallRepository")
 * @ORM\Table(name="install_install")
 *
 * Class Install
 * @package Install\Entity
 */
class Install
{
    /**
     * Install constructor.
     */
    public function __construct()
    {
        $this->hash = hash('crc32', random_bytes(8));
        $this->timestamp  = new \DateTime('now');
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;

    /**
     * @ORM\ManyToOne(targetEntity="Product\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="License\Entity\License")
     * @ORM\JoinColumn(name="license_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $license;

    /**
     * @ORM\Column(name="ip_address", type="string", length=250)
     */
    private $ipAddress;

    /**
     * @ORM\Column(name="domain", type="string", length=250)
     */
    private $domain;

    /**
     * @ORM\Column(name="full_url", type="string", length=250)
     */
    private $fullUrl;

    /**
     * @ORM\Column(name="server_name", type="string", length=250, nullable=true)
     */
    private $serverName;

    /**
     * @ORM\Column(name="php_version", type="string", length=250, nullable=true)
     */
    private $phpVersion;

    /**
     * @ORM\Column(name="database_platform", type="string", length=250, nullable=true)
     */
    private $databasePlatform;

    /**
     * @ORM\Column(name="database_platform_version", type="string", length=250, nullable=true)
     */
    private $databasePlatformVersion;

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
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param mixed $license
     * @return $this
     */
    public function setLicense($license)
    {
        $this->license = $license;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param mixed $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * @param mixed $fullUrl
     * @return $this
     */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * @param mixed $serverName
     * @return $this
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhpVersion()
    {
        return $this->phpVersion;
    }

    /**
     * @param mixed $phpVersion
     * @return $this
     */
    public function setPhpVersion($phpVersion)
    {
        $this->phpVersion = $phpVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatabasePlatform()
    {
        return $this->databasePlatform;
    }

    /**
     * @param mixed $databasePlatform
     * @return $this
     */
    public function setDatabasePlatform($databasePlatform)
    {
        $this->databasePlatform = $databasePlatform;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatabasePlatformVersion()
    {
        return $this->databasePlatformVersion;
    }

    /**
     * @param mixed $databasePlatformVersion
     * @return $this
     */
    public function setDatabasePlatformVersion($databasePlatformVersion)
    {
        $this->databasePlatformVersion = $databasePlatformVersion;
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

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }
}