<?php

Zend_Loader::loadClass( 'DB_Posts' );
Zend_Loader::loadClass( 'DB_PostTypes' );
Zend_Loader::loadClass( 'DB_PostsPostTypes' );
Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'RSSNews' );
Zend_Loader::loadClass( 'CommonPost' );

class News extends CommonPost
{

    const QUERY_ALL = "
                    SELECT 
                            b_posts_post_types.id,
                            b_posts.id AS post_id,
                            b_posts.title,                            
                            IF(LENGTH(b_posts.body) > 1000, CONCAT(SUBSTRING(TRIM(b_posts.body), 1, 1000 ), '...'), TRIM(b_posts.body)) AS body,
                            b_posts.date_created,
                            b_posts.user_created,
                            b_posts.date_last_modified,
                            b_posts.user_last_modified
                    FROM 
                    b_posts_post_types
                            INNER JOIN b_posts
                            ON b_posts_post_types.post_id = b_posts.id
                            INNER JOIN b_post_types
                            ON b_posts_post_types.post_type_id = b_post_types.id
                    WHERE
                            b_posts.deleted = 0
                            AND b_posts_post_types.deleted = 0
                            AND b_post_types.deleted = 0
                            AND b_post_types.id = ?";
    const ORDER_BY_DATE_LAST_MODIFIED = "ORDER BY b_posts.date_last_modified ASC LIMIT 0, 1";

    public function __construct()
    {
        parent::__construct();

        $this->oDb = Zend_Registry::get( 'db' );
        $this->oDBPosts = new DB_Posts();
        $this->oDBPostTypes = new DB_PostTypes();
        $this->oDBPostsPostTypes = new DB_PostsPostTypes();

        $oAppConfig = Zend_Registry::get( 'config' );
        $this->pathToImages = $oAppConfig->upload->root->posts;

        $aPostRow = $this->getPostTypeId();
        $this->sPostTypeId = $aPostRow[ 'id' ];
    }

    public function add( array $data )
    {
        $insertId = $this->oDBPosts->insertData( array( 'title' => $data[ 'title' ] , 'body' => $data[ 'body' ] ) );

        $postTypeData = array(
                'post_type_id' => $data[ 'type' ] ,
                'post_id' => $insertId
        );
        return $this->oDBPostsPostTypes->insertData( $postTypeData );
    }

    public function update( array $aData , $iNewsId )
    {
        $aExistingData = $this->get( $iNewsId );

        if ( !is_null( $aExistingData ) && $aExistingData->id )
        {
            return $this->oDBPosts->updateData( $aData , $aExistingData->id );
        }
        return 0;
    }

    public function getPostTypeId()
    {
        return $this->oDBPostTypes->getIdByName( SiteConstants::$NEWS );
    }

    public function get( $iNewsId )
    {
        $aRow = $this->oDBPostsPostTypes->getRow( $iNewsId );
        if ( !is_null( $aRow ) && !empty( $aRow ) )
        {
            return $this->oDBPosts->getRowObject( $aRow[ 'post_id' ] );
        }
        return null;
    }

    public function getNews( $newsId )
    {
        $fetch = $this->oDb->fetchRow( Sql::$SELECT_NEWS_BY_ID , $newsId );
        return $fetch;
    }

    public function getBacoorNews()
    {
        return $this->oDb->fetchAll( self::QUERY_ALL , array( SiteConstants::$NEWS_ID ) );
    }

    public function getBacoorEvents()
    {
        return $this->oDb->fetchAll( self::QUERY_ALL , array( SiteConstants::$EVENTS_ID ) );
    }

    public function getLatestBacoorNews()
    {
        return $this->oDb->fetchAll( self::QUERY_ALL . " " . self::ORDER_BY_DATE_LAST_MODIFIED , array( $this->sPostTypeId ) );
    }

    public function getGMANews( $iLimit = 5 )
    {
        $oFeed = Zend_Feed::import( SiteConstants::$GMA_FEED );
        return $this->getRSSItems( $oFeed , $iLimit );
    }

    public function getStarNews( $iLimit = 5 )
    {
        $oFeed = Zend_Feed::import( SiteConstants::$STAR_FEED );
        return $this->getRSSItems( $oFeed , $iLimit );
    }

    public function getCNNNews( $iLimit = 5 )
    {
        $oFeed = Zend_Feed::import( SiteConstants::$CNN_FEED );
        return $this->getRSSItems( $oFeed , $iLimit );
    }

    public function getGuardianNews( $iLimit = 5 )
    {
        $oFeed = Zend_Feed::import( SiteConstants::$GUARDIAN_FEED );
        return $this->getRSSItems( $oFeed , $iLimit );
    }

    private function getRSSItems( $oFeed , $iLimit )
    {
        $oResultItems = array();
        $iCount = 1;
        foreach ( $oFeed as $oItem )
        {
            $oTempItem = new RSSNews();
            $oTempItem->title = $oItem->title();
            $oTempItem->link = $oItem->link;
            $oTempItem->description = $oItem->description();
            $oResultItems[] = $oTempItem;
            if ( $iCount == $iLimit )
            {
                break;
            }
            $iCount++;
        }
        return $oResultItems;
    }

    public function delete( $iNewsId )
    {
        $aData = array(
                'deleted' => 1
        );

        $aRow = $this->oDBPostsPostTypes->getRow( $iNewsId );

        $this->oDBPostsPostTypes->updateData( $aData , $iNewsId );
        $this->oDBPosts->updateData( $aData , $aRow[ 'post_id' ] );

        return true;
    }

    public function toArray( $news , $isEditingAllowed = false , $isDeletingAllowed = false )
    {
        $finalNews = $this->getStructuredArray( $news , $isEditingAllowed , $isDeletingAllowed );
        if ( count( $finalNews ) > 0 )
        {
            return $finalNews;
        }
        return array();
    }

    private function getStructuredArray( $aNews , $bEditAllowed = false , $bDeleteAllowed = false )
    {
        $aFinalNews = array();
        for ( $i = 0 , $count = count( $aNews ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'id' ] = $aNews[ $i ][ 'id' ];
            $aItem[ 'title' ] = $aNews[ $i ][ 'title' ];
            $aItem[ 'body' ] = $aNews[ $i ][ 'body' ];
            $aItem[ 'date_created' ] = $aNews[ $i ][ 'date_created' ];
            $aItem[ 'user_created' ] = $aNews[ $i ][ 'user_created' ];
            $aItem[ 'date_modified' ] = $aNews[ $i ][ 'date_last_modified' ];
            $aItem[ 'user_modified' ] = $aNews[ $i ][ 'user_last_modified' ];
            $aItem[ 'edit' ] = $bEditAllowed;
            $aItem[ 'delete' ] = $bDeleteAllowed;
            $aFinalNews[ $aNews[ $i ][ 'id' ] ] = $aItem;
        }
        return $aFinalNews;
    }

    public function deletefile( $iNewsId , $iFileName )
    {
        $path = $this->pathToImages . DIRECTORY_SEPARATOR
                . $iNewsId . DIRECTORY_SEPARATOR . $iFileName;
        if ( is_readable( $path ) )
        {
            unlink( $path );

            $aData = array( "name" => $iFileName , "post_id" => $iNewsId );
            $this->deleteImage( $aData );
            return true;
        }
        return false;
    }

}
