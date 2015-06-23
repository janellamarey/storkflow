<?php

Zend_Loader::loadClass( 'SiteConstants' );

class Custom_Controller_Action_Helper_MenuHelper extends Zend_Controller_Action_Helper_Abstract
{

    public function getMenu( $role , $view )
    {
        $menu = array();

        $extraMenu = array();
        
        if ( $role == SiteConstants::$ADMIN_ID )
        {
            $extraMenu = array( 'title' => 'ACCOUNT' ,
                    'url' => $view->url( array( 'controller' => 'accounts' , 'action' => '' ) , null , true )
            );
        }
        else if ( $role == SiteConstants::$SM_ID )
        {
            $extraMenu = array( 'title' => 'ACCOUNT' ,
                    'url' => $view->url( array( 'controller' => 'accounts' , 'action' => '' ) , null , true )
            );
        }
        else if ( $role == SiteConstants::$MEMBER_ID )
        {
            $extraMenu = array( 'title' => 'ACCOUNT' ,
                    'url' => $view->url( array( 'controller' => 'accounts' , 'action' => '' ) , null , true )
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
        return $menu;
    }

    public function setMenuItemName( $name )
    {
        $this->menuItemName = $name;
    }

    public function setMenuItemAction( $action )
    {
        $this->menuItemAction = $action;
    }

}
