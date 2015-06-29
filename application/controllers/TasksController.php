<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Tasks' );

class TasksController extends Zend_Controller_Action
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
        
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$TASKS_MENUITEM );

        $this->tasks = new Tasks();
    }

    public function indexAction()
    {
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $isDeleteAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'tasks' , 'delete' );
        $isEditAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'tasks' , 'edit' );

        $this->view->tasks = $this->tasks->toArray( $this->tasks->getTasks() , $isEditAllowed , $isDeleteAllowed , false, false );
        
        $this->view->is_logged = $this->user->sysRoleId === SiteConstants::$GUEST_ID ? false : true;
    }

    public function addAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );
        
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $message = "";
        $form = new Form_TaskAdd( '/tasks/add/' );

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $taskId = $this->tasks->addNewTask( $this->getRequest()->getPost() );
                if ( $taskId )
                {
                    $message = "Task has been added successfully.";
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
        $taskId = ( int ) $this->getRequest()->getParam( 'id' );

        $message = "";
        $form = new Form_TaskEdit( '/tasks/edit/' );
        $form->setData( array( 'task_id' => ( int ) $taskId ) );
        $form->init();

        if ( $this->getRequest()->isPost() )
        {
            $this->getRequest()->setPost( 'id' , ( int ) $taskId );
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $this->tasks->updateTaskData( $taskId , $this->getRequest()->getPost() );
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
            $data = $this->tasks->getData( $taskId )->toArray();
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
            $taskId = ( int ) $this->getRequest()->getParam( 'id' );
            $task = $this->tasks->getData( $taskId );

            $this->view->task = $task;
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $taskId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "tasks";
        if ( $this->tasks->delete( $taskId ) )
        {
            $this->view->result = true;
            $this->view->id = $taskId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $taskId;
            $this->view->message = "Cannot delete data.";
        }
    }
    
}
