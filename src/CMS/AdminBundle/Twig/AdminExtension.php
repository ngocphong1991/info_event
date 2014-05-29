<?php

namespace CMS\AdminBundle\Twig;

class AdminExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('cut', array($this, 'cutSting')),
            new \Twig_SimpleFilter('stripslashes', array($this, 'stripSlashesString')),
            new \Twig_SimpleFilter('checkACL', array($this, 'checkPermission')),
            new \Twig_SimpleFilter('checkAdvanceACL', array($this, 'checkAdvancePermission')),
        );
    }

    function checkPermission($resource, $acl){
        if($resource & $acl)
            return true;
        else
            return false;
    }

    function checkAdvancePermission($roles, $acl, $module){

        foreach($roles as $role){
            $permission = (array) json_decode($role->getResource());
            if($permission[$module] & $acl){
                return true;
            }

        }

        return false;
    }

    function  stripSlashesString($str){
        return stripslashes($str);
    }

    function cutSting($str, $length = 3, $minword = 3)
    {
        $sub = '';
        $len = 0;
        foreach (explode(' ', $str) as $word)
        {
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen($part);
            if (strlen($word) > $minword && strlen($sub) >= $length)
            {
                break;
            }
        }
        return $sub . (($len < strlen($str)) ? ' ...' : '');
    }

    public function getName()
    {
        return 'admin_extension';
    }
}

?>