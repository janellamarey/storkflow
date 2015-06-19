<?php

Zend_Loader::loadClass( 'SiteConstants' );

class Custom_Controller_Action_Helper_LinksHelper extends Zend_Controller_Action_Helper_Abstract
{

    public function getLinks( $sRole , $oView )
    {

        $aLinks = array();
        $sRole = 'role' . $sRole;
        if ( $sRole == SiteConstants::$SUPERADMIN )
        {
            $aLinks = array(
                    array( 'title' => 'View registered users' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'registered' ) , null , true ) ) ,
                    array( 'title' => 'Change password' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
                    array( 'title' => 'Change password change email recurrence' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'pwcemails' ) , null , true ) )
            );
        }
        else if ( $sRole == SiteConstants::$SUPERUSER )
        {
            $aLinks = array(
                    array( 'title' => 'View registered users' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'registered' ) , null , true ) ) ,
                    array( 'title' => 'Change password' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
                    array( 'title' => 'Change password change email recurrence' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'pwcemails' ) , null , true ) )
            );
        }
        else if ( $sRole == SiteConstants::$ADMIN )
        {
            $aLinks = array(
                    array( 'title' => 'Add user' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'View registered users' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'registered' ) , null , true ) ) ,
                    array( 'title' => 'View pending requests' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'new' ) , null , true ) ) ,
                    array( 'title' => 'Add polls' , 'url' => $oView->url( array( 'controller' => 'polls' , 'action' => 'add' ) , null , true ) ) ,
                    array( 'title' => 'View unpublished polls' , 'url' => $oView->url( array( 'controller' => 'polls' , 'action' => 'unpublished' ) , null , true ) ) ,
                    array( 'title' => 'Change password' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
            );
        }
        else if ( $sRole == SiteConstants::$VOTER )
        {
            $aLinks = array(
                    array( 'title' => 'Change password' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) )
            );
        }
        else if ( $sRole == SiteConstants::$COUNCILOR )
        {
            $aLinks = array(
                    array( 'title' => 'Change password' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'changepass' ) , null , true ) ) ,
                    array( 'title' => 'Change password change email recurrence' , 'url' => $oView->url( array( 'controller' => 'users' , 'action' => 'pwcemails' ) , null , true ) )
            );
        }

        return $aLinks;
    }

}
