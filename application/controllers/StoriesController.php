<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Stories' );

class StoriesController extends Zend_Controller_Action
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
        
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$STORIES_MENUITEM );

        $this->stories = new Stories();
    }

    public function indexAction()
    {
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $isDeleteAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'stories' , 'delete' );
        $isEditAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'stories' , 'edit' );

        $this->view->stories = $this->stories->toArray( $this->stories->getStories() , $isEditAllowed , $isDeleteAllowed , false, false );
        
        $this->view->is_logged = $this->user->sysRoleId === SiteConstants::$GUEST_ID ? false : true;
    }

    public function addAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );
        
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $message = "";
        $form = new Form_StoryAdd( '/stories/add/' );

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $storyId = $this->stories->addNewStory( $this->getRequest()->getPost() );
                if ( $storyId )
                {
                    $message = "Story has been added successfully.";
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
        $storyId = ( int ) $this->getRequest()->getParam( 'id' );

        $message = "";
        $form = new Form_StoryEdit( '/stories/edit/' );
        $form->setData( array( 'story_id' => ( int ) $storyId ) );
        $form->init();

        if ( $this->getRequest()->isPost() )
        {
            $this->getRequest()->setPost( 'id' , ( int ) $storyId );
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $this->stories->updateStoriesData( $storyId , $this->getRequest()->getPost() );
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
            $data = $this->stories->getData( $storyId )->toArray();
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
            $storyId = ( int ) $this->getRequest()->getParam( 'id' );
            $story = $this->stories->getData( $storyId );

            $this->view->story = $story;
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $storyId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "stories";
        if ( $this->stories->delete( $storyId ) )
        {
            $this->view->result = true;
            $this->view->id = $storyId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $storyId;
            $this->view->message = "Cannot delete data.";
        }
    }
    
}
