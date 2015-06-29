<?php

Zend_Loader::loadClass( 'DB_Teams' );
Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'SQLConstants' );

class Teams
{

    public function __construct()
    {
        $this->db = Zend_Registry::get( 'db' );
        $this->teams = new DB_Teams();

        $this->appConfig = Zend_Registry::get( 'config' );

        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' );
        $user = $aclHelper->getCurrentUser();
        $this->currentRoleId = $user->sysRoleId;
        $this->currentUserId = $user->sysUserId;
    }

    public function getTeams()
    {
        return $this->db->fetchAll( SQLConstants::$QUERY_ALL_TEAMS );
    }

    public function getData( $id )
    {
        return $this->teams->getRowObject( $id );
    }

    public function addNewTeam( array $post )
    {
        $team = $this->teams->createRow();
        $team->name = $post[ 'name' ];
        $team->description = $post[ 'description' ];
        return $team->save();
    }

    public function toArray( $teams , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalTeams = $this->getStructuredArray( $teams , $isEditAllowed , $isDeleteAllowed , $isApproveAllowed , $isDenyAllowed );
        if ( count( $finalTeams ) > 0 )
        {
            return $finalTeams;
        }
        return array();
    }

    private function getStructuredArray( $teams , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalTeams = array();
        for ( $i = 0 , $count = count( $teams ); $i < $count; $i++ )
        {
            $item = array();
            $item[ 'id' ] = $teams[ $i ][ 'id' ];
            $item[ 'name' ] = $teams[ $i ][ 'name' ];
            $item[ 'description' ] = $teams[ $i ][ 'description' ];
            $item[ 'date_created' ] = $teams[ $i ][ 'date_created' ];
            $item[ 'user_created' ] = $teams[ $i ][ 'user_created' ];
            $item[ 'date_modified' ] = $teams[ $i ][ 'date_last_modified' ];
            $item[ 'user_modified' ] = $teams[ $i ][ 'user_last_modified' ];
            $item[ 'edit' ] = $isEditAllowed;
            $item[ 'delete' ] = $isDeleteAllowed;
            $item[ 'approve' ] = $isApproveAllowed;
            $item[ 'deny' ] = $isDenyAllowed;
            $finalTeams[ $teams[ $i ][ 'id' ] ] = $item;
        }
        return $finalTeams;
    }

    public function updateTeamData( $teamId , $post )
    {

        $name = $post[ 'name' ];
        $description = $post[ 'description' ];

        $data = array(
                'name' => $name ,
                'description' => $description
        );

        return $this->teams->updateData( $data , $teamId );
    }

    public function delete( $teamId )
    {
        $data = array(
                'deleted' => 1
        );

        $row = $this->teams->getRow( $teamId );
        if ( !is_null( $row ) )
        {
            $this->teams->updateData( $data , $teamId );
        }
        return true;
    }
}
