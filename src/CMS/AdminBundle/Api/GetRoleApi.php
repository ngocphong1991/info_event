<?php
namespace CMS\AdminBundle\Api;

use Symfony\Component\Yaml\Yaml;

class GetRoleApi
{
    public $resources;
    public $acl;

    public function __construct(){
        $file   = __DIR__."/../Resources/config/resources.yml";
        $resources = Yaml::parse(file_get_contents($file));
        $this->resources = $resources['resources'];
        $this->acl = $resources['acl'];
    }

    public function checkACL($roles, $acl, $module) {

        foreach($roles as $role){
            $permission = (array) json_decode($role->getResource());
            if($permission[$module] & $acl){
                return true;
            }

        }

        return false;
    }
}