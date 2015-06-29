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
                    array( 'title' => 'Add story' , 'url' => $view->url( array( 'controller' => 'stories' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'Add task' , 'url' => $view->url( array( 'controller' => 'tasks' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'Add team' , 'url' => $view->url( array( 'controller' => 'teams' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'Add house' , 'url' => $view->url( array( 'controller' => 'houses' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'Add user' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'View registered users' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'registered' ) , null , true ) ) ,
                    array( 'title' => 'Change password' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
            );
        }
        else if ( $role == SiteConstants::$SM_ID )
        {
            $links = array(
                    array( 'title' => 'Change password' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) )
            );
        }
        else if ( $role == SiteConstants::$MEMBER_ID )
        {
            $links = array(
                    array( 'title' => 'Change password' , 'url' => $view->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) )
            );
        }
        return $links;
    }

}
