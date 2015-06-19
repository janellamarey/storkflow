<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Emails' );
Zend_Loader::loadClass( 'Errors' );
Zend_Loader::loadClass( 'ContactUs' );
Zend_Loader::loadClass( 'Polls' );
Zend_Loader::loadClass( 'Ordinances' );

class ContactController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'index' , 'edit' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' ) );

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->oContactUs = new ContactUs();
        $this->oEmails = new Emails();
        $this->oPolls = new Polls();
        $this->oOrdinances = new Ordinances();

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$CONTACTUS_MENUITEM );
    }

    public function getErrorMessagesBeforeAdding( $oPost )
    {
        $sName = $oPost[ 'contact-name' ];
        $sEmail = $oPost[ 'contact-email' ];
        $sSubject = $oPost[ 'contact-subject' ];
        $sDescription = $oPost[ 'contact-description' ];

        $oErrors = new Errors();
        $oErrors->setValidator( $this->_helper->_validator );
        $oErrors->checkError( 'notempty' , $sName , "'Name' cannot be empty." );
        $oErrors->checkError( 'notempty' , $sEmail , "'Email Address' cannot be empty." );
        $oErrors->checkError( 'notempty' , $sSubject , "'Subject' cannot be empty." );
        $oErrors->checkError( 'notempty' , $sDescription , "'Description' cannot be empty." );
        return $oErrors->getErrors();
    }

    public function indexAction()
    {
        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $sUserId = $aUserData[ 'sys_user_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $sMessage = "";
        $oForm = new Form_ContactForm( '/contact/index/' );

        if ( $this->_request->isPost() )
        {
            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $recipientSelection = $this->getRequest()->getPost( 'to' );
                $message = nl2br( $this->getRequest()->getPost( 'message' ) );
                $fullname = $this->getRequest()->getPost( 'fullname' );
                $address = $this->getRequest()->getPost( 'address' );
                $contact = $this->getRequest()->getPost( 'contact' );
                $emailadd = $this->getRequest()->getPost( 'emailadd' );
                $company = $this->getRequest()->getPost( 'company' );

                $iEmailId = $this->oEmails->mailContact( $recipientSelection , $message , $fullname , $address , $contact , $emailadd , $company );
                if ( $iEmailId )
                {
                    $sMessage = "Message sent successfully!";
                }
                else
                {
                    $oForm->populate( $this->_request->getPost() );
                    $sMessage = 'Submission Failed. See errors below.';
                }
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;

        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $sUserId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function editAction()
    {
        $this->view->title = "Update 'Contact Us' Information";
        $this->view->post = array( 'contactus-text' => $this->oContactUs->getContent() );

        $aSubmissionMessage = '';
        $aErrorMessages = array();
        if ( $this->getRequest()->isPost() )
        {
            $sInfo = $this->getRequest()->getPost( 'contactus-text' );

            $aErrorMessages = array_merge( $aErrorMessages , $this->_helper->_validator->errorMessages( 'notempty' , $sInfo , "'Contact Us' Information cannot be empty." ) );

            if ( empty( $aErrorMessages ) )
            {
                $this->oContactUs->updateData( array( 'body' => $sInfo ) );
                $aSubmissionMessage = "New 'About Us' information has been successfully updated!";
                $this->view->post = array( 'contactus-text' => $this->oContactUs->getContent() );
            }
            else
            {
                $aSubmissionMessage = "Submission Failed. See errors below.";
                $this->view->post = $this->getRequest()->getPost();
            }
        }
        else
        {
            $this->view->post = array( 'contactus-text' => $this->oContactUs->getContent() );
        }
        $this->view->url = $this->view->url( array( 'controller' => 'contact' , 'action' => 'edit' ) );
        $this->view->formmessage = $aSubmissionMessage;
        $this->view->formresponse = $aErrorMessages;
    }

}
