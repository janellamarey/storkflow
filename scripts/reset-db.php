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
Zend_Loader::loadClass( 'Sql' );
$sql = new Sql();
$sql->reset();