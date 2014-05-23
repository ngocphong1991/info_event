<?php

namespace CMS\AdminBundle\Twig;

class AdminExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('cut', array($this, 'cutSting')),
        );
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