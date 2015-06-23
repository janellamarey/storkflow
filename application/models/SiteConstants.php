<?php

class SiteConstants
{

    public static $SITE = "http://storkflow.jauzio.com";
    
    public static $ADMIN_USER = 'role1';
    public static $SM_USER = 'role2';
    public static $MEMBER_USER = 'role3';
    public static $GUEST_USER = 'role4';
    public static $ADMIN_ID = 1;
    public static $SM_ID = 2;
    public static $MEMBER_ID = 3;
    public static $GUEST_ID = 4;
    
    public static $HOME_MENUITEM = 'HOME';
    public static $ACCOUNT_MENUITEM = 'ACCOUNT';
    
    public static $BY_DAY = 86400;
    public static $BY_WEEK = 604800;
    public static $NEW = "NEW";
    public static $REG = "REG";

    public static function createName( $firstname , $lastname , $mi , $designation )
    {
        return trim( $firstname . ' ' . $mi . ' ' . $lastname . ' ' . $designation );
    }

    public static function convertToSrc( $imagePath )
    {
        $appConfig = Zend_Registry::get( 'config' );
        return str_replace( '\\' , '/' , str_replace( $appConfig->folder->publicdir , "" , $imagePath ) );
    }
}
