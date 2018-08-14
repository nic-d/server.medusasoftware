<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/07/2018
 * Time: 23:31
 */

namespace Application\Service\Twig\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\Twig\MarkdownExtension;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class MarkdownExtensionFactory
 * @package Application\Service\Twig\Factory
 */
class MarkdownExtensionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return MarkdownExtension|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MarkdownExtension($container, $container->get('ZendTwig\Renderer\TwigRenderer'));
    }
}