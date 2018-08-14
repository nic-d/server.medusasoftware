<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 30/03/2018
 * Time: 09:52
 */

namespace User\Service;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Class AclService
 * @package User\Service
 */
class AclService extends Acl
{
    /** @var array $accessFilterRules */
    private $accessFilterRules = [];

    /**
     * AclService constructor.
     * @param array $accessFilterRules
     */
    public function __construct(array $accessFilterRules = [])
    {
        $this->accessFilterRules = $accessFilterRules;
        $this->configureAcl();
    }

    private function configureAcl()
    {
        // Define our ACL roles
        $this->defineRoles();

        // Iterate through the access filter rules and add the resources & rules
        foreach ($this->accessFilterRules['resources'] as $controller => $rules) {
            $this->addResource(new GenericResource($controller));

            foreach ($rules as $rule) {
                $this->allow($rule['roles'], $controller, $rule['actions']);
            }
        }
    }

    private function defineRoles()
    {
        $guestRole = new GenericRole('Guest');
        $userRole  = new GenericRole('User');
        $adminRole = new GenericRole('Admin');

        $this->addRole($guestRole);
        $this->addRole($userRole, $guestRole);
        $this->addRole($adminRole, $userRole);
    }
}