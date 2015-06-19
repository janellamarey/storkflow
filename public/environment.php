<?php

if ( getenv( 'APPLICATION_ENV' ) === "prod" )
{
    include realpath( './../' ) . '/bacoor/application/Bootstrap.php';
    set_include_path( implode( PATH_SEPARATOR , array(
            realpath( dirname( __FILE__ ) . '/../' ) . '/bacoor/application' ,
            realpath( dirname( __FILE__ ) . '/../' ) . '/bacoor/library' ,
            get_include_path() ,
    ) ) );

    defined( 'APPLICATION_ENV' ) || define( 'APPLICATION_ENV' , 'prod' );
}
else if ( getenv( 'APPLICATION_ENV' ) === "staging" )
{
    include realpath( dirname( __FILE__ ) . '/../../' ) . '/bacoor/application/Bootstrap.php';
    set_include_path( implode( PATH_SEPARATOR , array(
            realpath( dirname( __FILE__ ) . '/../../' ) . '/bacoor/application' ,
            realpath( dirname( __FILE__ ) . '/../../' ) . '/bacoor/library' ,
            get_include_path() ,
    ) ) );
    defined( 'APPLICATION_ENV' ) || define( 'APPLICATION_ENV' , 'staging' );
}
else if ( getenv( 'APPLICATION_ENV' ) === "dev" )
{
    include '../application/Bootstrap.php';
    set_include_path( implode( PATH_SEPARATOR , array(
            realpath( dirname( __FILE__ ) . '/../application' ) ,
            realpath( dirname( __FILE__ ) . '/../library' ) ,
            get_include_path() ,
    ) ) );
    defined( 'APPLICATION_ENV' ) || define( 'APPLICATION_ENV' , 'dev' );
}
