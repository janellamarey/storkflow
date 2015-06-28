<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'User' );

class Custom_Controller_Action_Helper_AclHelper extends Zend_Controller_Action_Helper_Abstract
{

    protected $auth;
    protected $acl;
    protected $controllerName;
    protected $action;

    public function __construct( Zend_View_Interface $view = null , array $options = array() )
    {
        $this->acl = $options[ 'acl' ];
        $this->user = new User( $this->getUserIdentity() );
    }

    public function getCurrentUser()
    {
        return $this->user;
    }
    
    public function getCurrentUserRole()
    {
        return $this->user->sysRoleId;
    }

    public function isLogged()
    {
        return $this->user->sysRoleId !== SiteConstants::$GUEST_USER;
    }

    public function getCurrentUserArray()
    {
        return $this->user->toArray();
    }

    public function isAllowed( $user , $controller , $privileges )
    {
        return $this->acl->isAllowed( $user , $controller , $privileges );
    }

    public function allow( $roles = null , $actions = null )
    {
        $resource = $this->controllerName;
        $this->acl->allow( $roles , $resource , $actions );
        return $this;
    }

    public function allowAll( $resource = null , $roles = null , $actions = null )
    {
        $this->acl->allow( $roles , $resource , $actions );
        return $this;
    }

    public function permit( $resource = null , $roles = null , $actions = null )
    {
        $this->acl->allow( $roles , $resource , $actions );
        return $this;
    }

    public function deny( $roles = null , $actions = null )
    {
        $resource = $this->controllerName;
        $this->acl->deny( $roles , $resource , $actions );
        return $this;
    }

    public function restrict( $resource = null , $roles = null , $actions = null )
    {
        $this->acl->deny( $roles , $resource , $actions );
        return $this;
    }

    public function addResource( $controllerName )
    {
        if ( !$this->acl->has( $controllerName ) )
        {
            $this->acl->add( new Zend_Acl_Resource( $controllerName ) );
        }
    }

    public function init()
    {
        $this->action = $this->getActionController();
        $this->request = $this->action->getRequest();
        $this->actionName = $this->request->getActionName();
        $this->controllerName = $this->request->getControllerName();
        $this->addResource( $this->controllerName );
    }

    public function preDispatch()
    {
        $role = SiteConstants::$ROLE . $this->user->sysRoleId;
        $resource = $this->controllerName;
        $privilege = $this->actionName;

        if ( $this->shouldRedirect( $role , $resource , $privilege ) )
        {
            $this->redirectToHome();
        }
    }

    public function shouldRedirect( $role , $resource , $privilege )
    {
        return (!$this->acl->has( $resource ) && !$this->acl->isAllowed( $role , null , $privilege ) )
                || ( $this->acl->has( $resource ) && !$this->acl->isAllowed( $role , $resource , $privilege ) );
    }

    public function redirectToHome()
    {
        $this->request->setModuleName( 'default' );
        $this->request->setControllerName( 'index' );
        $this->request->setActionName( 'index' );
        $this->request->setDispatched( true );
    }

    public function update( $event )
    {
        if ( $event === 'post-write' || $event === 'post-clear' )
        {
            $this->user = new User( $this->getUserIdentity() );
        }
    }

    private function getUserIdentity()
    {
        $auth = Zend_Auth::getInstance();
        $user = null;
        if ( $auth->hasIdentity() )
        {
            $identity = $auth->getIdentity();
            if ( is_object( $identity ) )
            {
                $user = $identity;
            }
        }
        return $user;
    }

    public function getPrivileges( $resource , $role )
    {
        return $this->buildPrivileges( $role , $resource , $this->getResourceActions( $resource ) );
    }

    private function buildPrivileges( $role , $resource , $actions )
    {
        $final = array();
        foreach ( $actions as $action )
        {
            $final[ $action ] = $this->isAllowed( $role , $resource , $action );
        }
        return $final;
    }

    public function getRoles()
    {
        return $this->acl->getRoles();
    }

    public function getResources()
    {
        return $this->acl->getResources();
    }

    public function getResourceActions( $resource )
    {
        $actions = $this->getAllActions();
        return $actions[ 'default' ][ $resource ];
    }

    //borrowed code: http://stackoverflow.com/questions/887947/get-all-modules-controllers-and-actions-from-a-zend-framework-application
    public function getAllActions()
    {
        $front = Zend_Controller_Front::getInstance();

        $final = array();
        $controllers = array();

        foreach ( $front->getControllerDirectory() as $module => $path )
        {
            foreach ( scandir( $path ) as $file )
            {
                if ( strstr( $file , "Controller.php" ) !== false )
                {
                    include_once $path . DIRECTORY_SEPARATOR . $file;
                    foreach ( get_declared_classes() as $class )
                    {
                        if ( $this->isClassNew( $class , $controllers ) )
                        {
                            $controllers[] = $class;
                            $final[ $module ][ $this->extractController( $class ) ] = $this->extractActions( $class );
                        }
                    }
                }
            }
        }
        return $final;
    }

    private function isClassNew( $class , $controllers )
    {
        return is_subclass_of( $class , 'Zend_Controller_Action' ) && !in_array( $class , $controllers );
    }

    private function extractController( $class )
    {
        return strtolower( substr( $class , 0 , strpos( $class , "Controller" ) ) );
    }

    private function extractActions( $class )
    {
        $actions = array();
        foreach ( get_class_methods( $class ) as $action )
        {
            if ( strstr( $action , "Action" ) !== false )
            {
                $actions[] = str_replace( "Action" , "" , $action );
            }
        }
        return $actions;
    }

}
