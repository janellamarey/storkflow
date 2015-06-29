<?php

Zend_Loader::loadClass( 'DB_Tasks' );
Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'SQLConstants' );

class Tasks
{

    public function __construct()
    {
        $this->db = Zend_Registry::get( 'db' );
        $this->tasks = new DB_Tasks();

        $this->appConfig = Zend_Registry::get( 'config' );

        $aclHelper = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' );
        $user = $aclHelper->getCurrentUser();
        $this->currentRoleId = $user->sysRoleId;
        $this->currentUserId = $user->sysUserId;
    }

    public function getTasks()
    {
        return $this->db->fetchAll( SQLConstants::$QUERY_ALL_TASKS );
    }

    public function getData( $id )
    {
        return $this->tasks->getRowObject( $id );
    }

    public function addNewTask( array $post )
    {
        $task = $this->tasks->createRow();
        $task->name = $post[ 'name' ];
        $task->description = $post[ 'description' ];
        return $task->save();
    }

    public function toArray( $teams , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalTasks = $this->getStructuredArray( $teams , $isEditAllowed , $isDeleteAllowed , $isApproveAllowed , $isDenyAllowed );
        if ( count( $finalTasks ) > 0 )
        {
            return $finalTasks;
        }
        return array();
    }

    private function getStructuredArray( $tasks , $isEditAllowed = false , $isDeleteAllowed = false , $isApproveAllowed = false , $isDenyAllowed = false )
    {
        $finalTasks = array();
        for ( $i = 0 , $count = count( $tasks ); $i < $count; $i++ )
        {
            $item = array();
            $item[ 'id' ] = $tasks[ $i ][ 'id' ];
            $item[ 'name' ] = $tasks[ $i ][ 'name' ];
            $item[ 'description' ] = $tasks[ $i ][ 'description' ];
            $item[ 'date_created' ] = $tasks[ $i ][ 'date_created' ];
            $item[ 'user_created' ] = $tasks[ $i ][ 'user_created' ];
            $item[ 'date_modified' ] = $tasks[ $i ][ 'date_last_modified' ];
            $item[ 'user_modified' ] = $tasks[ $i ][ 'user_last_modified' ];
            $item[ 'edit' ] = $isEditAllowed;
            $item[ 'delete' ] = $isDeleteAllowed;
            $item[ 'approve' ] = $isApproveAllowed;
            $item[ 'deny' ] = $isDenyAllowed;
            $finalTasks[ $tasks[ $i ][ 'id' ] ] = $item;
        }
        return $finalTasks;
    }

    public function updateTaskData( $teamId , $post )
    {

        $name = $post[ 'name' ];
        $description = $post[ 'description' ];

        $data = array(
                'name' => $name ,
                'description' => $description
        );

        return $this->tasks->updateData( $data , $teamId );
    }

    public function delete( $taskId )
    {
        $data = array(
                'deleted' => 1
        );

        $row = $this->tasks->getRow( $taskId );
        if ( !is_null( $row ) )
        {
            $this->tasks->updateData( $data , $taskId );
        }
        return true;
    }
}
