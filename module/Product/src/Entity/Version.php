<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:29
 */

namespace Product\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="product_version")
 *
 * Class Version
 * @package Product\Entity
 */
class Version
{
    /**
     * Version constructor.
     * @throws \Exception
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
     * @ORM\ManyToOne(targetEntity="Product\Entity\Product", inversedBy="versions")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\Column(name="version_number", type="string", length=250)
     */
    private $versionNumber;

    /**
     * @ORM\Column(name="packaged_app", type="string", length=250)
     */
    private $packagedApp;

    /**
     * @ORM\Column(name="packaged_app_url", type="string", length=250)
     */
    private $packagedAppUrl;

    /**
     * @ORM\Column(name="packaged_hash", type="string", length=250, nullable=true)
     */
    private $packagedHash;

    /**
     * @ORM\Column(name="changelog", type="text", nullable=true)
     */
    private $changelog;

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
    public function getVersionNumber()
    {
        return $this->versionNumber;
    }

    /**
     * @param mixed $versionNumber
     * @return $this
     */
    public function setVersionNumber($versionNumber)
    {
        $this->versionNumber = $versionNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPackagedApp()
    {
        return $this->packagedApp;
    }

    /**
     * @param mixed $packagedApp
     * @return $this
     */
    public function setPackagedApp($packagedApp)
    {
        $this->packagedApp = $packagedApp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChangelog()
    {
        return $this->changelog;
    }

    /**
     * @param mixed $changelog
     * @return $this
     */
    public function setChangelog($changelog)
    {
        $this->changelog = $changelog;
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
    public function getPackagedHash()
    {
        return $this->packagedHash;
    }

    /**
     * @param mixed $packagedHash
     * @return $this
     */
    public function setPackagedHash($packagedHash)
    {
        $this->packagedHash = $packagedHash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPackagedAppUrl()
    {
        return $this->packagedAppUrl;
    }

    /**
     * @param mixed $packagedAppUrl
     * @return $this
     */
    public function setPackagedAppUrl($packagedAppUrl)
    {
        $this->packagedAppUrl = $packagedAppUrl;
        return $this;
    }
}