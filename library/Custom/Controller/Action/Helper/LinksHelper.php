<?php

Zend_Loader::loadClass( 'SiteConstants' );

class Custom_Controller_Action_Helper_LinksHelper extends Zend_Controller_Action_Helper_Abstract
{

    public function getLinks( $role , $view )
    {
        $links = array();
        if ( $role == SiteConstants::$ADMIN_ID )
        {
            $links = array(
                    array( 'title' => 'View registered users' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'registered' ) , null , true ) ) ,
                    array( 'title' => 'Change password' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
                    array( 'title' => 'Change password change email recurrence' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'pwcemails' ) , null , true ) )
            );
        }
        else if ( $role == SiteConstants::$SM_ID )
        {
            $links = array(
                    array( 'title' => 'View registered users' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'registered' ) , null , true ) ) ,
                    array( 'title' => 'Change password' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
                    array( 'title' => 'Change password change email recurrence' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'pwcemails' ) , null , true ) )
            );
        }
        else if ( $role == SiteConstants::$MEMBER_ID )
        {
            $links = array(
                    array( 'title' => 'Add user' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'View registered users' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'registered' ) , null , true ) ) ,
                    array( 'title' => 'View pending requests' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'new' ) , null , true ) ) ,
                    array( 'title' => 'Add polls' , 'url' => $view->url( array( 'controller' => 'polls' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'View unpublished polls' , 'url' => $view->url( array( 'controller' => 'polls' , 'action' => 'unpublished' ) , null , true ) ) ,
                    array( 'title' => 'Change password' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
            );
        }
        return $links;
    }

}
