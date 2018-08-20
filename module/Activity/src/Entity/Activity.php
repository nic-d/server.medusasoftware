<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:52
 */

namespace Activity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Activity\Repository\ActivityRepository")
 * @ORM\Table(name="activity_activity")
 *
 * Class Activity
 * @package Activity\Entity
 */
class Activity
{
    /**
     * Activity constructor.
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
     * @ORM\Column(name="log_message", type="string", length=250)
     */
    private $logMessage;

    /**
     * @ORM\Column(name="ip_address", type="string", length=250, nullable=true)
     */
    public $ipAddress;

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
    public function getLogMessage()
    {
        return $this->logMessage;
    }

    /**
     * @param mixed $logMessage
     * @return $this
     */
    public function setLogMessage($logMessage)
    {
        $this->logMessage = $logMessage;
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
}