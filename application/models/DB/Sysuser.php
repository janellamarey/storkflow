<?php

class DB_Sysuser extends Custom_Db_Table_Row_Observable
{    
    public function getSearchIndexField()
    {
        $result = array();
        $result[ 'class' ] = 'users';
        $result[ 'key' ] = $this->id;
        $result[ 'title' ] = SiteConstants::createName( $this->firstname , $this->lastname , $this->mi , $this->designation );
        $result[ 'contents' ] = "";
        $result[ 'summary' ] = "Email address: " . $this->email_add . " Contact number: " . $this->contacts;
        $result[ 'createdBy' ] = $this->user_created;
        $result[ 'dateCreated' ] = $this->date_created;
        return $result;
    }

}
