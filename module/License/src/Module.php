<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 11/08/2018
 * Time: 23:33
 */

namespace License;

/**
 * Class Module
 * @package License
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