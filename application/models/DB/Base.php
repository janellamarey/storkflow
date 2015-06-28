<?php

class DB_Base extends Zend_Db_Table_Abstract
{

    public function insertData( array $data )
    {
        if ( empty( $data[ 'date_created' ] ) )
        {
            $data[ 'date_created' ] = date( "Y-m-d" );
        }
        $userdata = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' )->getCurrentUser()->toArray();
        if ( empty( $data[ 'user_created' ] ) )
        {
            $data[ 'user_created' ] = $userdata[ 'username' ];
        }
        return parent::insert( $data );
    }

    public function updateData( array $data , $id )
    {
        if ( empty( $data[ 'date_last_modified' ] ) )
        {
            $data[ 'date_last_modified' ] = date( "Y-m-d" );
        }
        $userdata = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' )->getCurrentUser()->toArray();
        if ( empty( $data[ 'user_last_modified' ] ) )
        {
            $data[ 'user_last_modified' ] = $userdata[ 'username' ];
        }
        return parent::update( $data , "id=" . $id );
    }

    public function updateColumn( array $data , $column , $value )
    {
        if ( empty( $data[ 'date_last_modified' ] ) )
        {
            $data[ 'date_last_modified' ] = date( "Y-m-d" );
        }
        $userdata = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' )->getCurrentUser()->toArray();
        if ( empty( $data[ 'user_last_modified' ] ) )
        {
            $data[ 'user_last_modified' ] = $userdata[ 'username' ];
        }
        parent::update( $data , $column . "='" . $value . "'" );
    }

    public function updateColumns( array $data , $columnValuesMap )
    {
        if ( empty( $data[ 'date_last_modified' ] ) )
        {
            $data[ 'date_last_modified' ] = date( "Y-m-d" );
        }
        $userdata = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' )->getCurrentUser()->toArray();
        if ( empty( $data[ 'user_last_modified' ] ) )
        {
            $data[ 'user_last_modified' ] = $userdata[ 'username' ];
        }
        if ( is_null( $columnValuesMap ) && empty( $columnValuesMap ) )
        {
            return;
        }

        $whereTemp = array();
        foreach ( $columnValuesMap as $key => $value )
        {
            if ( is_int( $value ) )
            {
                $whereTemp[] = $key . "=" . $value;
            }
            else
            {
                $whereTemp[] = $key . "='" . $value . "'";
            }
        }
        $where = implode( " AND " , $whereTemp );
        parent::update( $data , $where );
    }

    public function getRow( $id )
    {
        $row = $this->fetchRow( 'deleted=0 AND id=' . $id );
        if ( is_null( $row ) )
        {
            return null;
        }
        return $this->fetchRow( 'deleted=0 AND id=' . $id )->toArray();
    }

    public function getRowObject( $id )
    {
        $sSql = $this->_db->quoteInto( 'deleted=0 AND id=?' , $id );
        return $this->fetchRow( $sSql );
    }

    public function toArray()
    {
        return $this->fetchAll()->toArray();
    }

}
