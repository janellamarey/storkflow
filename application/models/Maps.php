<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Users
 *
 * @author Josh
 */
Zend_Loader::loadClass( 'DB_Maps' );
Zend_Loader::loadClass( 'Uploader' );

define( "MAP_HOME" , ROOT_DIR . '/public/img/map/' );

class Maps
{

    public function __construct()
    {
        $this->oMaps = new DB_Maps();
        $this->oUploader = new Uploader();
                
        $oAppConfig = Zend_Registry::get( 'config' );
        $this->pathToImages = $oAppConfig->upload->root->maps;
    }

    public function addMap( array $aFiles )
    {
        if ( !empty( $aFiles[ 'map' ] ) )
        {
            $sPath = $aFiles[ 'map' ][ 'name' ];
            $sExt = pathinfo( $sPath , PATHINFO_EXTENSION );
            $sFilename = "hazard." . $sExt;
            $sDestPath = MAP_HOME . '/' . $sFilename;

            $this->createMapDirectory();

            $aErrorMessages = $this->oUploader->uploadMap( $sDestPath , $aFiles[ 'map' ] );

            $this->insertMapToDB( $aErrorMessages , $sDestPath );
        }
        return $aErrorMessages;
    }
    
    public function editMap( array $aFiles )
    {
        if ( !empty( $aFiles[ 'map' ] ) )
        {
            $sPath = $aFiles[ 'map' ][ 'name' ];
            $sExt = pathinfo( $sPath , PATHINFO_EXTENSION );
            $sFilename = "hazard." . $sExt;
            $sDestPath = MAP_HOME . '/' . $sFilename;

            $aErrorMessages = $this->oUploader->uploadMap( $sDestPath , $aFiles[ 'map' ] );

            $this->updateMapToDB( $aErrorMessages , $sDestPath );
           
        }
        return $aErrorMessages;
    }

    private function createMapDirectory()
    {
        if ( !file_exists( MAP_HOME ) )
        {
            mkdir( MAP_HOME );
        }
    }

    private function insertMapToDB( $aErrorMessages , $sDestPath )
    {
        if ( empty( $aErrorMessages ) )
        {
            $aMapData = array(
                                    'name' => SiteConstants::$HAZARD_MAP_NAME ,
                                    'url' => $sDestPath
            );
            $this->oMaps->insertData( $aMapData );
        }
    }

    private function updateMapToDB( $aErrorMessages , $sDestPath )
    {
        if ( empty( $aErrorMessages ) )
        {
            $aMapData = array( 'url' => $sDestPath );
            $this->oMaps->updateColumn( $aMapData, 'name', SiteConstants::$HAZARD_MAP_NAME );
        }
    }

    public function getHazardMap()
    {
        return $this->oMaps->getRowObjectFromName( SiteConstants::$HAZARD_MAP_NAME );
    }
    
    public function deletefile( $iFileIndex, $iPostId , $iFileName )
    {
        $path = $this->pathToImages . DIRECTORY_SEPARATOR
                . $iPostId . DIRECTORY_SEPARATOR . $iFileName;
        if ( is_readable( $path ) )
        {
            unlink( $path );
            return true;
        }
        return false;
    }


}
