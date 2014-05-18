<?php
namespace CMS\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'user'));

        // create the menu according to the route
        switch($request->get('_route')){
            //article menu
            case 'article':
                $menu->addChild('Article')->setCurrent(true);
            break;
            case 'article_new':
                $menu->addChild('Article', array('route' => 'article'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'article_create':
                $menu->addChild('Article', array('route' => 'article'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'article_edit':
                $menu->addChild('Article', array('route' => 'article'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'article_update':
                $menu->addChild('Article', array('route' => 'article'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'article_show':
                $menu->addChild('Article', array('route' => 'article'));
                $menu->addChild('Detail')->setCurrent(true);
            break;
            //Group Article menu
            case 'grouparticle':
                $menu->addChild('Article Group')->setCurrent(true);
            break;
            case 'grouparticle_new':
                $menu->addChild('Article Group', array('route' => 'grouparticle'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'grouparticle_create':
                $menu->addChild('Article Group', array('route' => 'grouparticle'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'grouparticle_update':
                $menu->addChild('Article Group', array('route' => 'grouparticle'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'grouparticle_edit':
                $menu->addChild('Article Group', array('route' => 'grouparticle'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'grouparticle_show':
                $menu->addChild('Article Group', array('route' => 'grouparticle'));
                $menu->addChild('Detail')->setCurrent(true);
            break;
            //role menu
            case 'role':
                $menu->addChild('Role')->setCurrent(true);
            break;
            case 'role_new':
                $menu->addChild('Role', array('route' => 'role'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'role_create':
                $menu->addChild('Role', array('route' => 'role'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'role_update':
                $menu->addChild('Role', array('route' => 'role'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'role_edit':
                $menu->addChild('Role', array('route' => 'role'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'role_show':
                $menu->addChild('Role', array('route' => 'role'));
                $menu->addChild('Detail')->setCurrent(true);
            break;
            //user menu
            case 'user':
                $menu->addChild('User')->setCurrent(true);
            break;
            case 'user_new':
                $menu->addChild('User', array('route' => 'user'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'user_create':
                $menu->addChild('User', array('route' => 'user'));
                $menu->addChild('New')->setCurrent(true);
            break;
            case 'user_update':
                $menu->addChild('User', array('route' => 'user'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'user_edit':
                $menu->addChild('User', array('route' => 'user'));
                $menu->addChild('Edit')->setCurrent(true);
            break;
            case 'user_show':
                $menu->addChild('User', array('route' => 'user'));
                $menu->addChild('Detail')->setCurrent(true);
            break;

            //tag menu
            case 'cmspage':
                $menu->addChild('Tag')->setCurrent(true);
                break;
            case 'cmspage_new':
                $menu->addChild('CMS Page', array('route' => 'cmspage'));
                $menu->addChild('New')->setCurrent(true);
                break;
            case 'cmspage_create':
                $menu->addChild('CMS Page', array('route' => 'cmspage'));
                $menu->addChild('New')->setCurrent(true);
                break;
            case 'cmspage_update':
                $menu->addChild('CMS Page', array('route' => 'cmspage'));
                $menu->addChild('Edit')->setCurrent(true);
                break;
            case 'cmspage_edit':
                $menu->addChild('CMS Page', array('route' => 'cmspage'));
                $menu->addChild('Edit')->setCurrent(true);
                break;
            case 'cmspage_show':
                $menu->addChild('CMS Page', array('route' => 'cmspage'));
                $menu->addChild('Detail')->setCurrent(true);
                break;

            //tag menu
            case 'tag':
                $menu->addChild('Tag')->setCurrent(true);
                break;
            case 'tag_new':
                $menu->addChild('Tag', array('route' => 'tag'));
                $menu->addChild('New')->setCurrent(true);
                break;
            case 'tag_create':
                $menu->addChild('Tag', array('route' => 'tag'));
                $menu->addChild('New')->setCurrent(true);
                break;
            case 'tag_update':
                $menu->addChild('Tag', array('route' => 'tag'));
                $menu->addChild('Edit')->setCurrent(true);
                break;
            case 'tag_edit':
                $menu->addChild('Tag', array('route' => 'tag'));
                $menu->addChild('Edit')->setCurrent(true);
                break;
            case 'tag_show':
                $menu->addChild('Tag', array('route' => 'tag'));
                $menu->addChild('Detail')->setCurrent(true);
                break;

            //special group menu
            case 'specgrp':
                $menu->addChild('Special Group')->setCurrent(true);
                break;
            case 'specgrp_new':
                $menu->addChild('Special Group', array('route' => 'specgrp'));
                $menu->addChild('New')->setCurrent(true);
                break;
            case 'specgrp_create':
                $menu->addChild('Special Group', array('route' => 'specgrp'));
                $menu->addChild('New')->setCurrent(true);
                break;
            case 'specgrp_update':
                $menu->addChild('Special Group', array('route' => 'specgrp'));
                $menu->addChild('Edit')->setCurrent(true);
                break;
            case 'specgrp_edit':
                $menu->addChild('Special Group', array('route' => 'specgrp'));
                $menu->addChild('Edit')->setCurrent(true);
                break;
            case 'specgrp_show':
                $menu->addChild('Special Group', array('route' => 'specgrp'));
                $menu->addChild('Detail')->setCurrent(true);
                break;
        }

        return $menu;
    }
}