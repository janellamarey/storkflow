<?php

class Custom_Acl extends Zend_Acl
{

    private $_noAuth;
    private $_noAcl;
    
    public function __construct()
    {        
        Zend_Loader::loadClass("SysRoleMappings");
        $oSysRoleMappings = new SysRoleMappings();
        $aRoles = $oSysRoleMappings->getMappings();
        $this->_addRoles($aRoles);
        $this->_loadRedirectionActions();
    }

    public function setNoAuthAction($noAuth)
    {
        $this->_noAuth = $noAuth;
    }

    public function setNoAclAction($noAcl)
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

    protected function _addRoles($aRoles)
    {

        $sPrefix= 'role';
        $aRolesParsed = array();
        foreach ($aRoles as $role)
        {
            if((int)$role['child_id'] === 0)
            {
                $aRolesParsed[$sPrefix.$role['parent_id']] = null;
            }
            else
            {
                $aRolesParsed[$sPrefix.$role['parent_id']][] = $sPrefix.$role['child_id'];
            }            
        }

        foreach($aRolesParsed as $name=>$children)
        {            
            if (!$this->hasRole($name))
            {
                $this->addRole(new Zend_Acl_Role($name), $children);
            }
            
        }
        
    }

    protected function _loadRedirectionActions($aclConfig=null)
    {
        $this->_noAuth = array('module' => 'default',
            'controller' => 'auth',
            'action' => 'login');

        $this->_noAcl = array('module' => 'default',
            'controller' => 'auth',
            'action' => 'login');
    }
}