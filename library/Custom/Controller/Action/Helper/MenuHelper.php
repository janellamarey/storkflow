<?php

Zend_Loader::loadClass( 'SiteConstants' );

class Custom_Controller_Action_Helper_MenuHelper extends Zend_Controller_Action_Helper_Abstract
{

    public function getMenu( $role , $view )
    {
        $menu = array();

        if ( ( int ) $role != SiteConstants::$GUEST_ID )
        {
            $menu = array(
                    'STORIES' => array( 'title' => 'STORIES' , 'url' => $view->url( array( 'controller' => 'stories' , 'action' => 'index' ) , null , true ) ) ,
                    'TASKS' => array( 'title' => 'TASKS' , 'url' => $view->url( array( 'controller' => 'tasks' , 'action' => 'index' ) , null , true ) ) ,
                    'TEAMS' => array( 'title' => 'TEAMS' , 'url' => $view->url( array( 'controller' => 'teams' , 'action' => 'index' ) , null , true ) ) ,
                    'HOUSES' => array( 'title' => 'HOUSES' , 'url' => $view->url( array( 'controller' => 'houses' , 'action' => 'index' ) , null , true ) ) ,
                    'ACCOUNT' => array( 'title' => 'ACCOUNT' , 'url' => $view->url( array( 'controller' => 'accounts' , 'action' => '' ) , null , true ) )
            );
        }

        return $menu;
    }

    /**
     * Set the active menu item based name of menu item
     * @param boolean $aMenu
     * @param type $sUrlToSearch
     * @return boolean
     */
    public function setActiveMenuItem( $menu )
    {
        if ( !empty( $menu ) && !is_null( $menu[ $this->menuItemName ] ) && !empty( $menu[ $this->menuItemName ] ) )
        {
            $menu[ $this->menuItemName ][ 'active' ] = true;
        }
        else
        {
            $menu[ 'ACCOUNT' ][ 'active' ] = true;
        }
        return $menu;
    }

    public function setMenuItemName( $name )
    {
        $this->menuItemName = $name;
    }

    public function getMenuItemName()
    {
        return $this->menuItemName;
    }

    public function setMenuItemAction( $action )
    {
        $this->menuItemAction = $action;
    }

}
