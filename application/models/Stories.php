<?php

Zend_Loader::loadClass( 'DB_Stories' );
Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'SQLConstants' );

class Stories
{

    public function __construct()
    {
        $this->db = Zend_Registry::get( 'db' );
        $this->stories = new DB_Stories();

        $this->appConfig = Zend_Registry::get( 'config' );

        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' );
        $user = $aclHelper->getCurrentUser();
        $this->currentRoleId = $user->sysRoleId;
        $this->currentUserId = $user->sysUserId;
    }

    public function getStories()
    {
        return $this->db->fetchAll( SQLConstants::$QUERY_ALL_STORIES );
    }

    public function getData( $id )
    {
        return $this->stories->getRowObject( $id );
    }

    public function addNewStory( array $post )
    {
        $story = $this->stories->createRow();
        $story->name = $post[ 'name' ];
        $story->description = $post[ 'description' ];
        return $story->save();
    }

    public function toArray( $stories  , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalStories = $this->getStructuredArray( $stories , $isEditAllowed , $isDeleteAllowed , $isApproveAllowed , $isDenyAllowed );
        if ( count( $finalStories ) > 0 )
        {
            return $finalStories;
        }
        return array();
    }

    private function getStructuredArray( $stories , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalStories = array();
        for ( $i = 0 , $count = count( $stories ); $i < $count; $i++ )
        { 
            $item = array();
            $item[ 'id' ] = $stories[ $i ][ 'id' ];
            $item[ 'name' ] = $stories[ $i ][ 'name' ];
            $item[ 'description' ] = $stories[ $i ][ 'description' ];
            $item[ 'date_created' ] = $stories[ $i ][ 'date_created' ];
            $item[ 'user_created' ] = $stories[ $i ][ 'user_created' ];
            $item[ 'date_modified' ] = $stories[ $i ][ 'date_last_modified' ];
            $item[ 'user_modified' ] = $stories[ $i ][ 'user_last_modified' ];
            $item[ 'edit' ] = $isEditAllowed;
            $item[ 'delete' ] = $isDeleteAllowed;
            $item[ 'approve' ] = $isApproveAllowed;
            $item[ 'deny' ] = $isDenyAllowed;
            $finalStories[ $stories[ $i ][ 'id' ] ] = $item;
        }
        return $finalStories;
    }

    public function updateStoriesData( $teamId , $post )
    {

        $name = $post[ 'name' ];
        $description = $post[ 'description' ];

        $data = array(
                'name' => $name ,
                'description' => $description
        );

        return $this->stories->updateData( $data , $teamId );
    }

    public function delete( $storyId )
    {
        $data = array(
                'deleted' => 1
        );

        $row = $this->stories->getRow( $storyId );
        if ( !is_null( $row ) )
        {
            $this->stories->updateData( $data , $storyId );
        }
        return true;
    }
}
