<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 15:48
 */

namespace Product\Form\Factory;

use Product\Form\VersionAddForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VersionAddFormFactory
 * @package Product\Form\Factory
 */
class VersionAddFormFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|VersionAddForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new VersionAddForm($entityManager);
    }
}