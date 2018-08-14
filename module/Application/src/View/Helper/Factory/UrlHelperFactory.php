<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 29/03/2018
 * Time: 09:24
 */

namespace Application\View\Helper\Factory;

use Zend\Uri\Http;
use Application\View\Helper\UrlHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UrlHelperFactory
 * @package Application\View\Helper\Factory
 */
class UrlHelperFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UrlHelper|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Http $requestUri */
        $requestUri = $container->get('router')->getRequestUri();

        $viewHelper = new UrlHelper();
        $viewHelper->setRequest($requestUri);

        return $viewHelper;
    }
}