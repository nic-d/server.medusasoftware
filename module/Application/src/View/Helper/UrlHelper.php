<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 29/03/2018
 * Time: 09:25
 */

namespace Application\View\Helper;

use Zend\Uri\Http;
use Zend\View\Helper\AbstractHelper;

/**
 * Class UrlHelper
 * @package Application\View\Helper
 */
class UrlHelper extends AbstractHelper
{
    /** @var Http $request */
    private $request;

    /**
     * Can be called like so:
     *
     * urlHelper()->isCurrentRoute('something')
     *
     * @param $matchesPath
     * @return bool
     */
    private function isCurrentRoute($matchesPath)
    {
        if (is_array($matchesPath)) {
            $matchesPath = $matchesPath[0];
        }

        if ($this->getRequest()->getPath() !== $matchesPath) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @param mixed $args
     * @return self
     */
    public function __call($name, $args)
    {
        return $this->$name($args);
    }

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return Http
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param $request
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }
}