<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 06:17
 */

namespace User;

use Zend\Uri\Uri;
use User\Entity\User;
use Zend\Mvc\MvcEvent;
use User\Service\AclService;
use User\Service\UserService;
use Zend\EventManager\EventManager;
use User\Controller\Auth\AuthController;
use Doctrine\ORM\EntityManagerInterface;
use Zend\EventManager\SharedEventManager;
use User\Entity\Subscriber\UserSubscriber;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class Module
 * @package User
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
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        /** @var EventManager $eventManager */
        $eventManager = $event->getApplication()->getEventManager();

        /** @var SharedEventManager $sharedEventManager */
        $sharedEventManager = $eventManager->getSharedManager();

        // Register our onDispatch event
        $sharedEventManager->attach(
            AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH,
            [$this, 'onDispatch'],
            100
        );
    }

    /**
     * @param MvcEvent $event
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function onDispatch(MvcEvent $event)
    {
        if (php_sapi_name() === 'cli' || php_sapi_name() === 'cli-server') {
            return;
        }

        // Generate default user
        $userService = $event->getApplication()->getServiceManager()->get(UserService::class);
        $userService->generateUser();

        /** @var EntityManagerInterface $doctrine */
        $doctrine = $event->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
        $userSubscriber = $event->getApplication()->getServiceManager()->get(UserSubscriber::class);

        // Add the subscriber(s) to Doctrine
        $doctrine->getEventManager()->addEventSubscriber($userSubscriber);

        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        /** @var AclService $aclService */
        $aclService = $event->getApplication()->getServiceManager()->get(AclService::class);

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $event->getApplication()->getServiceManager()->get(AuthenticationService::class);

        /** @var User $identity */
        $identity = $authenticationService->getIdentity();

        if (is_null($identity)) {
            $role = 'Guest';
        } else {
            $role = $identity->getRole();
        }

        if ($controllerName !== AuthController::class &&
            !$aclService->isAllowed($role, $controllerName, $actionName)
        ) {
            // Store the uri that the user tried to access so we can redirect after successful login
            /** @var Uri $uri */
            $uri = $event->getApplication()->getRequest()->getUri();

            // Make the URI relative
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);

            /** @var string $redirectUrl */
            $redirectUrl = $uri->toString();

            return $controller->redirect()->toRoute('user.login', [], ['query' => ['redirectUrl' => $redirectUrl]]);
        }
    }
}