<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/08/2018
 * Time: 21:31
 */

namespace Envato;

/**
 * Class Module
 * @package Envato
 */
class Module
{
    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}