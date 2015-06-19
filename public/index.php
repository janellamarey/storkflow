<?php

require_once 'environment.php';
require_once 'Zend/Loader/Autoloader.php';
$bootstrap = new Bootstrap( getenv( 'APPLICATION_ENV' ) );
$bootstrap->configure();
if ( !defined( "CRON_JOB" ) || CRON_JOB === false )
{
    $bootstrap->runApp();
}

