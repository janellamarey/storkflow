<?php

Zend_Loader::loadClass( 'DB_Base' );

class DB_Team extends Custom_Db_Table_Row_Observable
{    
    public function getSearchIndexField()
    {
        $result = array();
        $result[ 'class' ] = 'teams';
        $result[ 'key' ] = $this->id;
        $result[ 'title' ] = $this->name;
        $result[ 'contents' ] = "";
        $result[ 'summary' ] = $this->description;
        $result[ 'createdBy' ] = $this->user_created;
        $result[ 'dateCreated' ] = $this->date_created;
        return $result;
    }

}
