<?php
namespace CMS\AdminBundle\Api;

class GetRoleApi
{
    public static function checkACL($roles, $acl, $module) {

        foreach($roles as $role){
            $permission = (array) json_decode($role->getResource());
            if($permission[$module] & $acl){
                return true;
            }

        }

        return false;
    }
}