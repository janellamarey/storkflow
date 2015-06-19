<?php

Zend_Loader::loadClass( 'DB_Posts' );
Zend_Loader::loadClass( 'DB_PostImages' );
Zend_Loader::loadClass( 'Images' );
Zend_Loader::loadClass( 'SiteConstants' );

class CommonPost
{

    public function __construct()
    {
        $this->oDb = Zend_Registry::get( 'db' );

        $this->oArticles = new DB_Posts();
        $this->oPostImages = new DB_PostImages();
        $this->oImage = new Images();

        $this->sPostId = $this->getPostId();
    }

    public function updateData( array $data )
    {
        if ( !empty( $this->sPostId ) )
        {
            $this->oArticles->updateData( $data , $this->sPostId );
        }
    }

    public function getContent()
    {
        $content = '';
        $aPostRow = array();
        if ( !empty( $this->sPostId ) )
        {
            $aPostRow = $this->oArticles->getRow( $this->sPostId );
        }
        if ( !empty( $aPostRow ) && !empty( $aPostRow[ 'body' ] ) )
        {
            $content = $aPostRow[ 'body' ];
        }
        return $content;
    }

    protected function getPostId()
    {
        return '';
    }

    public function deleteImage( $aData )
    {
        $deleteData = array( 'deleted' => 1 );
        $deleteWhere = array(
                "post_id" => $aData[ "post_id" ] ,
                "name" => $aData[ "name" ]
        );
        $this->oPostImages->updateColumns( $deleteData , $deleteWhere );
    }

    public function saveImages( $aData )
    {
        $this->deleteImage( $aData );

        return $this->oPostImages->insertData( $aData );
    }

    public function getImages( $postId , $showCaption = true , $limit = 10 )
    {
        $homedir = $this->pathToImages . DIRECTORY_SEPARATOR . $postId;
        if ( !file_exists( $homedir ) )
        {
            mkdir( $homedir );
        }
        $images = $this->oPostImages->getImagesByPostId( $postId );
        $result = array();
        $selectedImages = array_slice( $images , 0 , $limit );
        foreach ( $selectedImages as $image )
        {
            $temp[ 'id' ] = $postId;
            $path = $homedir . DIRECTORY_SEPARATOR . $image[ 'name' ];
            $temp[ 'path' ] = SiteConstants::convertToSrc( $path );
            if ( $showCaption )
            {
                $temp[ 'caption' ] = $image[ 'caption' ];
            }
            $temp[ 'basename' ] = $image[ 'name' ];
            $dimension = $this->oImage->getScaledSize( $path , 150 , 150 );
            $temp[ 'width' ] = $dimension[ 0 ];
            $temp[ 'height' ] = $dimension[ 1 ];
            $result[] = $temp;
        }
        return $result;
    }

}
