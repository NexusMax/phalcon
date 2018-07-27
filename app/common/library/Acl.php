<?php
namespace App\Library;

use Phalcon\Acl as AclMain;
use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;

/**
 * Vokuro\Acl\Acl
 */
class Acl extends Component
{

    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $acl;

    /**
     * The file path of the ACL cache file.
     *
     * @var string
     */
    private $filePath;

    /**
     * Define the resources that are considered "private". These controller => actions require authentication.
     *
     * @var array
     */
    private $privateResources = array();

    public $roles = [];
    /**
     * Checks if a controller is private or not
     *
     * @param string $controllerName
     * @return boolean
     */
    public function isPrivate($controllerName, $role = false)
    {
        $controllerName = strtolower($controllerName);

        if($role){
            return isset($this->privateResources[$role][$controllerName]);
        }else{
            foreach ($this->privateResources as $role => $resources) {
                if(isset($resources[$controllerName])){
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $profile
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed($profile, $controller, $action)
    {
        return $this->getAcl()->isAllowed($profile, $controller, $action);
    }


    /**
     * Returns all the resources and their actions available in the application
     *
     * @return array
     */
    public function getResources()
    {
        return $this->privateResources;
    }


    /**
     * Rebuilds the access list into a file
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        $acl = new AclMemory();

        $acl->setDefaultAction(AclMain::DENY);

        // Register roles

        foreach ($this->roles as $role) {
            $acl->addRole(new AclRole($role));
        }

        //Grant access to private area to role Users
        foreach ($this->privateResources as $role => $resources) {

            foreach ($resources as $resource => $actions) {

                foreach ($actions as $action) {
                    $acl->addResource(new AclResource($resource), $action);
                    $acl->allow($role, $resource, $action);
                }
            }
        }


        return $acl;
    }


    /**
     * Adds an array of private resources to the ACL object.
     *
     * @param array $resources
     */
    public function addPrivateResources(array $resources)
    {
        if (count($resources) > 0) {
            foreach ($resources as $key => $value) {
                $this->roles[] = $key;
            }

            $this->privateResources = array_merge($this->privateResources, $resources);
            if (is_object($this->acl)) {
                $this->acl = $this->getAcl();
            }
        }
    }
}
