<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\EventManager\SharedEventManager;
use Zend\Http\Request;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Listener\ConfigListener;

/**
 * Class Module
 * @package Application
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

    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        /** @var EventManager $events */
        $events = $moduleManager->getEventManager();

        $events->attach(
            ModuleEvent::EVENT_MERGE_CONFIG,
            [$this, 'onMergeConfig'],
            100
        );
    }

    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        /** @var EventManager $eventManager */
        $eventManager = $event->getApplication()->getEventManager();

        /** @var SharedEventManager $sharedEventManager */
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(
            AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH,
            [$this, 'onDispatch'],
            100
        );

        // We'll attach error events so we can log them
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            [$this, 'handleError']
        );

        $eventManager->attach(
            MvcEvent::EVENT_RENDER_ERROR,
            [$this, 'handleError']
        );
    }

    /**
     * @param MvcEvent $event
     */
    public function onDispatch(MvcEvent $event)
    {
        /** @var Request $request */
        $request = $event->getRequest();

        if ($request->isGet()) {
            /** @var ViewModel $viewModel */
            $viewModel = $event->getViewModel();

            // If this is an AJAX request, set the template var to ajax else standard layout
            if ($request->isXmlHttpRequest()) {
                $viewModel->setVariable('template', 'layout/ajax.twig');
            } else {
                $viewModel->setVariable('template', 'layout/layout.twig');
            }
        }
    }

    /**
     * @param ModuleEvent $event
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        /** @var ConfigListener $configListener */
        $configListener = $event->getConfigListener();
        $config = $configListener->getMergedConfig(false);

        // If both TwigStrategy and ViewJsonStrategy registered, prioritise ViewJsonStrategy, this fixes an issue
        // with the zfctwig module where it was trying to load layouts for JsonModel responses.
        if (isset($config['view_manager']['strategies'])
            && false !== array_search('ViewJsonStrategy', $config['view_manager']['strategies'])
            && false !== ($zfcKey = array_search('ZendTwig\View\TwigStrategy', $config['view_manager']['strategies']))
        ) {
            unset($config['view_manager']['strategies'][$zfcKey]);
            array_push($config['view_manager']['strategies'], 'ZendTwig\View\TwigStrategy');
            $configListener->setMergedConfig($config);
        }
    }

    /**
     * @param MvcEvent $event
     */
    public function handleError(MvcEvent $event)
    {
        /** @var \Exception $exception */
        $exception = $event->getParam('exception');

        /** @var Logger $logger */
        $logger = $event->getApplication()->getServiceManager()->get('App');
        $logger->err($exception);
    }
}