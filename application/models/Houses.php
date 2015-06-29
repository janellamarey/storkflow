<?php

Zend_Loader::loadClass( 'DB_Houses' );
Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'SQLConstants' );

class Houses
{

    public function __construct()
    {
        $this->db = Zend_Registry::get( 'db' );
        $this->houses = new DB_Houses();

        $this->appConfig = Zend_Registry::get( 'config' );

        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' );
        $user = $aclHelper->getCurrentUser();
        $this->currentRoleId = $user->sysRoleId;
        $this->currentUserId = $user->sysUserId;
    }

    public function getHouses()
    {
        return $this->db->fetchAll( SQLConstants::$QUERY_ALL_HOUSES );
    }

    public function getData( $id )
    {
        return $this->houses->getRowObject( $id );
    }

    public function addNewHouse( array $post )
    {
        $house = $this->houses->createRow();
        $house->name = $post[ 'name' ];
        $house->description = $post[ 'description' ];
        return $house->save();
    }

    public function toArray( $teams , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalHouses = $this->getStructuredArray( $teams , $isEditAllowed , $isDeleteAllowed , $isApproveAllowed , $isDenyAllowed );
        if ( count( $finalHouses ) > 0 )
        {
            return $finalHouses;
        }
        return array();
    }

    private function getStructuredArray( $houses , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalHouses = array();
        for ( $i = 0 , $count = count( $houses ); $i < $count; $i++ )
        {
            $item = array();
            $item[ 'id' ] = $houses[ $i ][ 'id' ];
            $item[ 'name' ] = $houses[ $i ][ 'name' ];
            $item[ 'description' ] = $houses[ $i ][ 'description' ];
            $item[ 'date_created' ] = $houses[ $i ][ 'date_created' ];
            $item[ 'user_created' ] = $houses[ $i ][ 'user_created' ];
            $item[ 'date_modified' ] = $houses[ $i ][ 'date_last_modified' ];
            $item[ 'user_modified' ] = $houses[ $i ][ 'user_last_modified' ];
            $item[ 'edit' ] = $isEditAllowed;
            $item[ 'delete' ] = $isDeleteAllowed;
            $item[ 'approve' ] = $isApproveAllowed;
            $item[ 'deny' ] = $isDenyAllowed;
            $finalHouses[ $houses[ $i ][ 'id' ] ] = $item;
        }
        return $finalHouses;
    }

    public function updateHouseData( $teamId , $post )
    {

        $name = $post[ 'name' ];
        $description = $post[ 'description' ];

        $data = array(
                'name' => $name ,
                'description' => $description
        );

        return $this->houses->updateData( $data , $teamId );
    }

    public function delete( $teamId )
    {
        $data = array(
                'deleted' => 1
        );

        $row = $this->houses->getRow( $teamId );
        if ( !is_null( $row ) )
        {
            $this->houses->updateData( $data , $teamId );
        }
        return true;
    }
}
