<?php

class Custom_Db_Table_Row_Observable extends Zend_Db_Table_Row_Abstract
{

    protected static $_observers = array();

    public static function attachObserver( $class )
    {
        if ( !is_string( $class ) || !class_exists( $class ) || !is_callable( array( $class , 'observeTableRow' ) ) )
        {
            return false;
        }

        if ( !isset( self::$_observers[ $class ] ) )
        {
            self::$_observers[ $class ] = true;
        }
        return true;
    }

    public static function detachObserver( $class )
    {
        if ( !isset( self::$_observers[ $class ] ) )
        {
            return false;
        }

        unset( self::$_observers[ $class ] );
        return true;
    }

    protected function _notifyObservers( $event )
    {
        if ( !empty( self::$_observers ) )
        {
            foreach ( array_keys( self::$_observers ) as $observer )
            {
                call_user_func_array( array( $observer , 'observeTableRow' ) , array( $event , $this ) );
            }
        }
        parent::_postInsert();
    }

    protected function _insert()
    {
        $this->date_created = date( "Y-m-d" );
        $user = Zend_Controller_Action_HelperBroker::getStaticHelper( 'AclHelper' )->getCurrentUser();
        $this->user_created = $user->username;
    }

    protected function _postInsert()
    {
        $this->_notifyObservers( 'post-insert' );
    }

    protected function _update()
    {
    }

    protected function _postUpdate()
    {
        $this->_notifyObservers( 'post-update' );
    }

    protected function _delete()
    {
        $this->_notifyObservers( 'pre-delete' );
    }

    protected function _postDelete()
    {
        $this->_notifyObservers( 'post-delete' );
    }

}
