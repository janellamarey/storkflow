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
    public static $BY_DAY = 86400;
    public static $BY_WEEK = 604800;
    public static $NEW = "NEW";
    public static $REG = "REG";
    public static $ACCOUNT_MENUITEM = 'ACCOUNT';
    public static $ROLE = 'role';

    public static function createName( $firstname , $lastname , $mi , $designation )
    {
        return trim( $firstname . ' ' . $mi . ' ' . $lastname . ' ' . $designation );
    }

    public static function convertToSrc( $imagePath )
    {
        $appConfig = Zend_Registry::get( 'config' );
        return str_replace( '\\' , '/' , str_replace( $appConfig->folder->publicdir , "" , $imagePath ) );
    }

    public static $MAIL_RESET = <<<EOT
                Dear User,

                As you requested, your password has now been reset. Your new details are as follows:
                
                Username: %s
                Password: %s
                    
                To change your password, login to http://storkflow.xxx.com/ and in your dashboard click 'Change Password'.
                
                All the best,
                Administrator
                www.lambsmarketing.com
EOT;
}
