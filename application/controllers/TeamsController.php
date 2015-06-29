<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Teams' );

class TeamsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->user = $this->_helper->_aclHelper->getCurrentUser();

        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN_USER , array( 'add', 'delete' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$SM_USER , array( 'edit' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$MEMBER_USER , array( 'index' , 'view' ) );
        
        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );
        $ajaxContext->initContext();        
        
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$TEAMS_MENUITEM );

        $this->teams = new Teams();
    }

    public function indexAction()
    {
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $isDeleteAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'teams' , 'delete' );
        $isEditAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'teams' , 'edit' );

        $this->view->teams = $this->teams->toArray( $this->teams->getTeams() , $isEditAllowed , $isDeleteAllowed , false, false );
        
        $this->view->is_logged = $this->user->sysRoleId === SiteConstants::$GUEST_ID ? false : true;
    }

    public function addAction()
    {
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $message = "";
        $form = new Form_TeamAdd( '/teams/add/' );

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $teamId = $this->teams->addNewTeam( $this->getRequest()->getPost() );
                if ( $teamId )
                {
                    $message = "Team has been added successfully.";
                }
                else
                {
                    $form->populate( $this->getRequest()->getPost() );
                    $message = 'Submission Failed. See errors below.';
                }
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }


        $this->view->links = $this->_helper->_linksHelper->getLinks( $roleId , $this->view );

        $this->view->form = $form;
        $this->view->message = $message;
    }
    
    public function editAction()
    {
        $teamId = ( int ) $this->getRequest()->getParam( 'id' );

        $message = "";
        $form = new Form_TeamEdit( '/teams/edit/' );
        $form->setData( array( 'team_id' => ( int ) $teamId ) );
        $form->init();

        if ( $this->getRequest()->isPost() )
        {
            $this->getRequest()->setPost( 'id' , ( int ) $teamId );
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $this->teams->updateTeamData( $teamId , $this->getRequest()->getPost() );
                $message = "New information has been successfully updated.";
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }

        if ( $this->getRequest()->isGet() )
        {
            $data = $this->teams->getData( $teamId )->toArray();
            $this->view->title = $data[ 'name' ];
            $form->populate( $data );
        }

        $this->view->form = $form;
        $this->view->message = $message;
    }
    
    public function viewAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $teamId = ( int ) $this->getRequest()->getParam( 'id' );
            $team = $this->teams->getData( $teamId );

            $this->view->team = $team;
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $teamId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "teams";
        if ( $this->teams->delete( $teamId ) )
        {
            $this->view->result = true;
            $this->view->id = $teamId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $teamId;
            $this->view->message = "Cannot delete data.";
        }
    }
    
}
