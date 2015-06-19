<?php

define( "CRON_JOB" , true );
$index = realpath( dirname( __FILE__ ) . '/../' ) . '/public_html/index.php';
if ( getenv( 'APPLICATION_ENV' ) == "staging" )
{
    $index = realpath( dirname( __FILE__ ) . '/../' ) . '/public_html/bacoorcitycouncil/index.php';
}
else if ( getenv( 'APPLICATION_ENV' ) == "dev" )
{
    $index = realpath( dirname( __FILE__ ) . '/../' ) . '/public/index.php';
}
include $index;
Zend_Session::$_unitTestEnabled = true;
Zend_Loader::loadClass( 'Emails' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'SiteConstants' );

$oUsers = new Users();
$oUserRoles = $oUsers->getActiveUsers();
$oEmails = new Emails();
$aRecipients = array();
$dToday = date( "Y-m-d" );
$iCurrentDate = strtotime( $dToday );

foreach ( $oUserRoles as $account )
{
    if ( $account[ 'date_created' ] !== "0000-00-00" )
    {
        $iDateInt = strtotime( $account[ 'date_created' ] );
        $iPwc = ( int ) $account[ 'pwc' ];
        $iComputeDiff = $iCurrentDate - $iDateInt;

        if ( $iPwc !== 0 && $iComputeDiff !== 0 )
        {
            //there 604800 secs in a week
            //there 86400 secs in a day
            $iNewPWC = ($iPwc * SiteConstants::$BY_WEEK);
            print_r( "Name: " . $account[ 'name' ] . "\r\n" );
            print_r( "Email: " . $account[ 'email' ] . "\r\n" );
            print_r( "Date created: " . $account[ 'date_created' ] . "\r\n" );
            print_r( "Current date: " . $dToday . "\r\n" );
            print_r( "Send email every: " . $iPwc . " days. \r\n" );
            print_r( "-----------\r\n" );

            $iShouldEmail = $iComputeDiff % $iNewPWC;
            if ( $iShouldEmail === 0 )
            {
                $aRecipients[] = $account[ 'email' ];
            }
        }
    }
}
if ( !is_null( $aRecipients ) && !empty( $aRecipients ) )
{
    $oEmails->passwordChange( $aRecipients );
}