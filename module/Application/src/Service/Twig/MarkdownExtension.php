<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/07/2018
 * Time: 23:31
 */

namespace Application\Service\Twig;

use ZendTwig\Renderer\TwigRenderer;
use Interop\Container\ContainerInterface;
use ZendTwig\Extension\AbstractExtension;

/**
 * Class MarkdownExtension
 * @package Application\Service\Twig
 */
class MarkdownExtension extends AbstractExtension
{
    /**
     * MarkdownExtension constructor.
     * @param ContainerInterface $serviceManager
     * @param TwigRenderer|null $renderer
     */
    public function __construct(
        ContainerInterface $serviceManager,
        TwigRenderer $renderer = null
    )
    {
        parent::__construct($serviceManager, $renderer);
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdown', function ($string) {
                $parsedown = new \Parsedown();
                return $parsedown->text($string);
            })
        ];
    }

    /**
     * @return TwigRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}