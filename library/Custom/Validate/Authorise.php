<?php

class Custom_Validate_Authorise extends Zend_Validate_Abstract
{

    const NOT_AUTHORISED = 'notAuthorised';

    protected $_authAdapter;
    protected $_messageTemplates = array( 'notAuthorised' => 'No user with those credentials exists.' );

    public function getAuthAdapter()
    {
        return $this->_authAdapter;
    }

    public function isValid( $value , $context = null )
    {
        //string from form e.g. username
        $value = ( string ) $value;
        $this->_setValue( $value );

        //array of strings from other form values
        if ( is_array( $context ) )
        {
            if ( !isset( $context[ 'password' ] ) )
            {
                return false;
            }
        }

        //get Db Adapter
        $oDbAdapter = Zend_Registry::get( 'db' );
        //get Auth Adapter
        $this->_authAdapter = new Zend_Auth_Adapter_DbTable( $oDbAdapter );
        $this->_authAdapter->setTableName( 'sys_user_roles' )
                ->setIdentityColumn( 'username' )
                ->setCredentialColumn( 'password' );
        //set identity column
        $this->_authAdapter->setIdentity( $context[ 'username' ] );
        //set credential column
        $this->_authAdapter->setCredential( $context[ 'password' ] );

        //do the authentication
        $oAuth = Zend_Auth::getInstance();
        $result = $oAuth->authenticate( $this->_authAdapter );
        
        if ( !$result->isValid() )
        {
            $this->_error( 'notAuthorised' );
            return false;
        }

        $oData = $this->_authAdapter->getResultRowObject( null , 'password' );

        if ( $oData->status !== SiteConstants::$REG )
        {
            $this->_error( 'notAuthorised' );
            return false;
        }
        
        Zend_Loader::loadClass( 'SysUsers' );
        $oSysUsers = new SysUsers();
        
        $sUserId = $oSysUsers->fetchRow( 'id=' . $oData->sys_user_id . ' AND deleted=0' );

        if ( empty( $sUserId ) && is_null( $sUserId ) )
        {
            $this->_error( 'notAuthorised' );
            return false;
        }
        return true;
    }

}
