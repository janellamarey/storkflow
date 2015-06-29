<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Houses' );

class HousesController extends Zend_Controller_Action
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
        
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$HOUSES_MENUITEM );

        $this->houses = new Houses();
    }

    public function indexAction()
    {
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $isDeleteAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'houses' , 'delete' );
        $isEditAllowed = $this->_helper->_aclHelper->isAllowed( SiteConstants::$ROLE . $roleId , 'houses' , 'edit' );

        $this->view->houses = $this->houses->toArray( $this->houses->getHouses() , $isEditAllowed , $isDeleteAllowed , false, false );
        
        $this->view->is_logged = $this->user->sysRoleId === SiteConstants::$GUEST_ID ? false : true;
    }

    public function addAction()
    {
        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );
        
        $roleId = ( int ) $this->_helper->_aclHelper->getCurrentUserRole();
        $message = "";
        $form = new Form_HouseAdd( '/houses/add/' );

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $houseId = $this->houses->addNewHouse( $this->getRequest()->getPost() );
                if ( $houseId )
                {
                    $message = "House has been added successfully.";
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
        $houseId = ( int ) $this->getRequest()->getParam( 'id' );

        $message = "";
        $form = new Form_HouseEdit( '/houses/edit/' );
        $form->setData( array( 'house_id' => ( int ) $houseId ) );
        $form->init();

        if ( $this->getRequest()->isPost() )
        {
            $this->getRequest()->setPost( 'id' , ( int ) $houseId );
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $this->houses->updateHouseData( $houseId , $this->getRequest()->getPost() );
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
            $data = $this->houses->getData( $houseId )->toArray();
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
            $houseId = ( int ) $this->getRequest()->getParam( 'id' );
            $house = $this->houses->getData( $houseId );

            $this->view->house = $house;
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $houseId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "houses";
        if ( $this->houses->delete( $houseId ) )
        {
            $this->view->result = true;
            $this->view->id = $houseId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $houseId;
            $this->view->message = "Cannot delete data.";
        }
    }
    
}
