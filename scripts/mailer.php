<?php

define( "CRON_JOB" , true );
if ( getenv( 'APPLICATION_ENV' ) == "prod" )
{
    $index = realpath( dirname( __FILE__ ) . '/../' ) . '/public_html/index.php';
}
else if ( getenv( 'APPLICATION_ENV' ) == "staging" )
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
$oEmails = new Emails();
$oEmails->sendMails();
