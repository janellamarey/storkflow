<?php

Zend_Loader::loadClass( 'CommonPost' );
Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Images' );
Zend_Loader::loadClass( 'DB_PostImages' );

class AboutUs extends CommonPost
{

    public function __construct()
    {
        parent::__construct();

        $this->oImage = new Images();
        $this->oPostImages = new DB_PostImages();
        $oAppConfig = Zend_Registry::get( 'config' );
        $this->pathToImages = $oAppConfig->upload->root->posts;
        $this->pathToRoot = $oAppConfig->upload->root->home;
    }

    protected function getPostId()
    {
        return SiteConstants::$ABOUT_POST_ID;
    }

    public function deletefile( $iFileName )
    {
        $path = $this->pathToImages . DIRECTORY_SEPARATOR
                . SiteConstants::$ABOUT_POST_ID . DIRECTORY_SEPARATOR . $iFileName;
        if ( is_readable( $path ) )
        {
            unlink( $path );

            $aData = array( "name" => $iFileName , "post_id" => SiteConstants::$ABOUT_POST_ID );
            $this->deleteImage( $aData );
            return true;
        }
        return false;
    }
}
