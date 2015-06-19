<?php

class DB_Legislation extends Custom_Db_Table_Row_Observable
{

    public function getSearchIndexField()
    {
        $result = array();
        $result[ 'class' ] = 'legislations';
        $result[ 'key' ] = $this->id;
        $result[ 'title' ] = $this->name;
        $result[ 'contents' ] = "";
        $result[ 'summary' ] = strlen( $this->summary ) > 100 ? substr( $this->summary , 0, 100 ) : $this->summary;
        $result[ 'createdBy' ] = $this->user_created;
        $result[ 'dateCreated' ] = $this->date_created;
        return $result;
    }

}
