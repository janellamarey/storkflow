<?php

Zend_Loader::loadClass( 'Images' );
Zend_Loader::loadClass( 'Errors' );

class Uploader
{

    public static $HEADERS = array(
            'application/zip' ,
            'application/x-zip' ,
            'application/octet-stream' ,
            'application/x-zip-compressed' ,
            'application/msword' ,
            'application/pdf' ,
            'image/png' ,
            'image/jpeg'
    );
    public static $IMAGE_HEADERS = array(
            'image/png' ,
            'image/jpeg'
    );
    public static $PNG_ONLY = array(
            'image/png'
    );

    public function __construct()
    {
        $this->oAppConfig = Zend_Registry::get( 'config' );
        $this->oUploadsConfig = array(
                'home' => $this->oAppConfig->upload->root->home ,
                'posts' => $this->oAppConfig->upload->root->posts ,
                'maps' => $this->oAppConfig->upload->root->maps ,
                'users' => $this->oAppConfig->upload->root->users ,
        );

        foreach ( $this->oUploadsConfig as $folder )
        {
            if ( !file_exists( $folder ) )
            {
                mkdir( $folder );
            }
        }
    }

    public function upload( $sDestPath , $aFiles )
    {
        $aErrorMessages = array();

        //Сheck that we have a file
        if ( (!empty( $aFiles )) && ($aFiles[ 'error' ] == 0) )
        {
            $filename = basename( $aFiles[ 'name' ] );

            if ( in_array( $aFiles[ "type" ] , Uploader::$HEADERS ) )
            {
                if ( ($aFiles[ "size" ] < 1999999 ) )
                {
                    //Determine the path to which we want to save this file
                    $sPath = $sDestPath;
                    //Check if the file with the same name is already exists on the server
                    if ( !file_exists( $sPath ) )
                    {
                        //Attempt to move the uploaded file to it's new place
                        if ( !(move_uploaded_file( $aFiles[ 'tmp_name' ] , $sPath )) )
                        {
                            $aErrorMessages[] = "A problem occurred during file upload!";
                        }
                    }
                    else
                    {
                        $aErrorMessages[] = "File " . $aFiles[ "name" ] . " already exists";
                    }
                }
                else
                {
                    $aErrorMessages[] = "Only files under 100Mb are accepted for upload";
                }
            }
            else
            {
                $aErrorMessages[] = "The file uploaded is not a PNG, JPEG, ZIP, PDF or DOC file.";
            }
        }
        else
        {
            if ( $aFiles[ 'error' ] == 2 || $aFiles[ 'error' ] == 1 )
            {
                $aErrorMessages[] = "The uploaded file exceeded the allowable file size of 100MB.";
            }
            else if ( $aFiles[ 'error' ] == 3 )
            {
                $aErrorMessages[] = "The file was only partially uploaded. Please upload again.";
            }
            else if ( $aFiles[ 'error' ] == 4 )
            {
                $aErrorMessages[] = "No files uploaded.";
            }
            else if ( $aFiles[ 'error' ] == 7 )
            {
                $aErrorMessages[] = "Failed to write the uploaded file in the server.";
            }
        }

        return $aErrorMessages;
    }

    public function uploadSignature( $sDestPath , $aFiles )
    {
        $aErrorMessages = array();

        //Сheck that we have a file
        if ( (!empty( $aFiles )) && ($aFiles[ 'error' ] == 0) )
        {
            $filename = basename( $aFiles[ 'name' ] );
            if ( in_array( $aFiles[ "type" ] , Uploader::$PNG_ONLY ) )
            {
                if ( ($aFiles[ "size" ] < 104857600 ) )
                {
                    $this->createThumbnail( $aFiles , $sDestPath , 400 , 'small' );
                    $this->createThumbnail( $aFiles , $sDestPath , 100 , 'tiny' );
                    $this->createThumbnail( $aFiles , $sDestPath , 50 , 'verytiny' );

                    if ( !(move_uploaded_file( $aFiles[ 'tmp_name' ] , $sDestPath )) )
                    {
                        $aErrorMessages[] = "A problem occurred during file upload!";
                    }
                }
                else
                {
                    $aErrorMessages[] = "Only files under 100Mb are accepted for upload";
                }
            }
            else
            {
                $aErrorMessages[] = "The file uploaded is not a PNG type.";
            }
        }
        else
        {
            if ( $aFiles[ 'error' ] == 2 || $aFiles[ 'error' ] == 1 )
            {
                $aErrorMessages[] = "The uploaded file exceeded the allowable file size of 100MB.";
            }
            else if ( $aFiles[ 'error' ] == 3 )
            {
                $aErrorMessages[] = "The file was only partially uploaded. Please upload again.";
            }
            else if ( $aFiles[ 'error' ] == 4 )
            {
                $aErrorMessages[] = "No files uploaded.";
            }
            else if ( $aFiles[ 'error' ] == 7 )
            {
                $aErrorMessages[] = "Failed to write the uploaded file in the server.";
            }
        }

        return $aErrorMessages;
    }

    public function uploadMap( $sDestPath , $aFiles )
    {
        $aErrorMessages = array();

        //Сheck that we have a file
        if ( (!empty( $aFiles )) && ($aFiles[ 'error' ] == 0) )
        {
            if ( in_array( $aFiles[ "type" ] , array( 'image/png' ) ) )
            {
                if ( ($aFiles[ "size" ] < 104857600 ) )
                {
                    //Attempt to move the uploaded file to it's new place
                    if ( !move_uploaded_file( $aFiles[ 'tmp_name' ] , $sDestPath ) )
                    {
                        $aErrorMessages[] = "A problem occurred during file upload!";
                    }
                }
                else
                {
                    $aErrorMessages[] = "Only files under 100Mb are accepted for upload";
                }
            }
            else
            {
                $aErrorMessages[] = "The file uploaded is not a PNG type.";
            }
        }
        else
        {
            if ( $aFiles[ 'error' ] == 2 || $aFiles[ 'error' ] == 1 )
            {
                $aErrorMessages[] = "The uploaded file exceeded the allowable file size of 100MB.";
            }
            else if ( $aFiles[ 'error' ] == 3 )
            {
                $aErrorMessages[] = "The file was only partially uploaded. Please upload again.";
            }
            else if ( $aFiles[ 'error' ] == 4 )
            {
                $aErrorMessages[] = "No files uploaded.";
            }
            else if ( $aFiles[ 'error' ] == 7 )
            {
                $aErrorMessages[] = "Failed to write the uploaded file in the server.";
            }
        }

        return $aErrorMessages;
    }

    public function createThumbnail( $aOriginalFile , $sPath , $iSize , $sFolderName )
    {
        $aPathInfo = pathinfo( $sPath );
        $oImages = new Images();
        if ( !file_exists( $aPathInfo[ 'dirname' ] . '/' . $sFolderName ) )
        {
            mkdir( $aPathInfo[ 'dirname' ] . '/' . $sFolderName );
        }
        if ( $aPathInfo[ 'extension' ] == 'jpg' || $aPathInfo[ 'extension' ] == 'jpeg' )
        {
            $oImages->makeJPEGThumbnail( $aOriginalFile[ 'tmp_name' ] , $aPathInfo[ 'dirname' ] . '/' . $sFolderName . '/' . $aPathInfo[ 'basename' ] , $iSize , $iSize , 85 );
        }
        else if ( $aPathInfo[ 'extension' ] == 'png' )
        {
            $oImages->makePNGThumbnail( $aOriginalFile[ 'tmp_name' ] , $aPathInfo[ 'dirname' ] . '/' . $sFolderName . '/' . $aPathInfo[ 'basename' ] , $iSize , $iSize , 5 );
        }
    }

}
