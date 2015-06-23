<?php

Zend_Loader::loadClass( "DB_RoleMappings" );

class Custom_Acl extends Zend_Acl
{

    private $_noAuth;
    private $_noAcl;

    public function __construct()
    {
        $roleMappings = new DB_RoleMappings();
        $roles = $roleMappings->getMappings();
        $this->_addRoles( $roles );
        $this->_loadRedirectionActions();
    }

    public function setNoAuthAction( $noAuth )
    {
        $this->_noAuth = $noAuth;
    }

    public function setNoAclAction( $noAcl )
    {
        $this->_noAcl = $noAcl;
    }

    public function getNoAuthAction()
    {
        return $this->_noAuth;
    }

    public function getNoAclAction()
    {
        return $this->_noAcl;
    }

    protected function _addRoles( $aRoles )
    {

        $prefix = 'role';
        $rolesParsed = array();
        foreach ( $aRoles as $role )
        {
            if ( ( int ) $role[ 'child_id' ] === 0 )
            {
                $rolesParsed[ $prefix . $role[ 'parent_id' ] ] = null;
            }
            else
            {
                $rolesParsed[ $prefix . $role[ 'parent_id' ] ][] = $prefix . $role[ 'child_id' ];
            }
        }

        foreach ( $rolesParsed as $name => $children )
        {
            if ( !$this->hasRole( $name ) )
            {
                $this->addRole( new Zend_Acl_Role( $name ) , $children );
            }
        }
    }

    protected function _loadRedirectionActions( $config = null )
    {
        $this->_noAuth = array( 'module' => 'default' ,
                'controller' => 'auth' ,
                'action' => 'login' );

        $this->_noAcl = array( 'module' => 'default' ,
                'controller' => 'auth' ,
                'action' => 'login' );
    }

}
