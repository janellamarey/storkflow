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
Zend_Loader::loadClass( 'Custom_Search_Lucene' );
Zend_Loader::loadClass( 'SearchIndexer' );
Zend_Loader::loadClass( 'SiteConstants' );

$config = Zend_Registry::get( 'config' );
SearchIndexer::setIndexDirectory( $config->search->path );
$index = Custom_Search_Lucene::create( SearchIndexer::getIndexDirectory() );

$users = new DB_Sysusers();
$usersFetch = $users->fetchAll();
foreach ( $usersFetch as $user )
{
    if ( ( int ) $user->searchable === 1 )
    {
        $document = SearchIndexer::getDocument( $user );
        $index->addDocument( $document );
    }
}

$legislations = new DB_Legislations();
$legislationFetch = $legislations->fetchAll();
foreach ( $legislationFetch as $legislation )
{
    $document = SearchIndexer::getDocument( $legislation );
    $index->addDocument( $document );
}
