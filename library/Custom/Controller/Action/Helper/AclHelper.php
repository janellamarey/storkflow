<?php

class Custom_Controller_Action_Helper_AclHelper extends Zend_Controller_Action_Helper_Abstract
{

    protected $_action;
    protected $_auth;
    protected $_acl;
    protected $_controllerName;

    public function __construct( Zend_View_Interface $view = null , array $options = array() )
    {
        Zend_Loader::loadClass( 'SiteConstants' );
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = $options[ 'acl' ];
    }

    public function isAllowed( $sUser , $sController , $sPrivileges )
    {
        return $this->_acl->isAllowed( $sUser , $sController , $sPrivileges );
    }

    /**
     * Hook into action controller initialization
     * @return void
     */
    public function init()
    {
        $this->_action = $this->getActionController();

        $this->_controllerName = $this->_action->getRequest()->getControllerName();

        if ( !$this->_acl->has( $this->_controllerName ) )
        {
            $this->_acl->add( new Zend_Acl_Resource( $this->_controllerName ) );
        }
    }

    public function allow( $roles = null , $actions = null )
    {
        $resource = $this->_controllerName;
        $this->_acl->allow( $roles , $resource , $actions );
        return $this;
    }

    public function allowAll( $resource = null , $roles = null , $actions = null )
    {
        $this->_acl->allow( $roles , $resource , $actions );
        return $this;
    }

    public function deny( $roles = null , $actions = null )
    {
        $resource = $this->_controllerName;
        $this->_acl->deny( $roles , $resource , $actions );
        return $this;
    }
    
    public function denyAll( $resource = null , $roles = null , $actions = null )
    {
        $this->_acl->deny( $roles , $resource , $actions );
        return $this;
    }

    public function getUserData()
    {
        if ( $this->_auth->hasIdentity() )
        {
            $user = $this->_auth->getIdentity();
            if ( is_object( $user ) )
            {
                return $user;
            }
        }

        return 6; //default to guest
    }

    /**
     * Get the current user information in array form
     * @return <type>
     */
    public function getCurrentUserData()
    {
        //null object
        $arrayResult = array(
                'id' => 0 ,
                'sys_user_id' => 0 ,
                'sys_role_id' => 6 ,
                'role_id' => 6 ,
                'username' => '' ,
                'deleted' => 0 ,
        );
        if ( $this->_auth->hasIdentity() )
        {
            $user = $this->_auth->getIdentity();
            if ( is_object( $user ) )
            {
                $arrayResult[ 'id' ] = $user->id;
                $arrayResult[ 'sys_user_id' ] = $user->sys_user_id;
                $arrayResult[ 'sys_role_id' ] = $user->sys_role_id;
                $arrayResult[ 'role_id' ] = ( int ) $user->sys_role_id;
                $arrayResult[ 'username' ] = $user->username;
                $arrayResult[ 'deleted' ] = $user->deleted;

                return $arrayResult;
            }
        }

        return $arrayResult;
    }

    public function preDispatch()
    {
        $role = SiteConstants::$GUESTROLE;
        //checks if the user is logged in
        if ( $this->_auth->hasIdentity() )
        {
            $user = $this->_auth->getIdentity();
            if ( is_object( $user ) )
            {
                $role = 'role' . $this->getUserData()->sys_role_id;
            }
        }
        $request = $this->_action->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $this->_controllerName = $controller;

        $resource = $controller;
        $privilege = $action;

        if ( !$this->_acl->has( $resource ) )
        {
            $resource = null;
        }

        //if a resource is accessed but the user has no access to it, redirect to login
        if ( !$this->_acl->isAllowed( $role , $resource , $privilege ) )
        {
            $request->setModuleName( 'default' );
            $request->setControllerName( 'index' );
            $request->setActionName( '' );
            $request->setDispatched( false );
        }
    }

    public function addResource( $controllerName )
    {
        if ( !$this->_acl->has( $controllerName ) )
        {
            $this->_acl->add( new Zend_Acl_Resource( $controllerName ) );
        }
    }

}
