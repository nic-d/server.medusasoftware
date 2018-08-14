<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 14:20
 */

namespace License\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="license_import_meta")
 *
 * Class ImportMeta
 * @package License\Entity
 */
class ImportMeta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="last_run", type="datetime")
     */
    private $lastRun;

    /**
     * @ORM\Column(name="import_result", type="string", length=8)
     */
    private $importResult;
}