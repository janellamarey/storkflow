<?php

Zend_Loader::loadClass( 'Polls' );
Zend_Loader::loadClass( 'Ordinances' );

class PollsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'unpublished' , 'add' , 'edit' , 'delete' , 'feature' , 'publish' ) );

        $this->_helper->_aclHelper->allow( SiteConstants::$COUNCILOR , array( 'vote' ) );

        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'getvotes' , 'voteoptions' , 'published' , 'view' ) );

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$ACCOUNT_MENUITEM );

        $this->oPolls = new Polls();
        $this->oOrdinances = new Ordinances();

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );
        $ajaxContext->addActionContext( 'vote' , 'json' );
        $ajaxContext->addActionContext( 'getvotes' , 'json' );
        $ajaxContext->addActionContext( 'voteoptions' , 'json' );
        $ajaxContext->addActionContext( 'publish' , 'json' );
        $ajaxContext->addActionContext( 'feature' , 'json' );
        $ajaxContext->initContext();
    }

    public function publishedAction()
    {
        $this->view->title = "Ano sa tingin mo?";

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PEOPLES_MENUITEM );

        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sRoleId = $aUserData[ 'sys_role_id' ];
        $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $bDeleteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'delete' );
        $bFeatureAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'feature' );

        $aQuestions = $this->oPolls->listPolls( 'PUBLISHED' );
        $aOptions = $this->oPolls->listOptions( 'PUBLISHED' );
        $aFinalPolls = $this->oPolls->toArray( $aQuestions , $aOptions , $bDeleteAllowed , $bFeatureAllowed );
        $this->view->polls = $aFinalPolls;

        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
    }

    public function publishAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iPollId = ( int ) $this->getRequest()->getPost( 'id' );

        if ( $iPollId )
        {
            $iReturnedPollId = $this->oPolls->publish( $iPollId );
            if ( $iReturnedPollId )
            {
                $this->view->result = true;
                $this->view->id = $iPollId;
            }
            else
            {
                $this->view->result = false;
            }
        }
    }

    public function featureAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iPollId = ( int ) $this->getRequest()->getPost( 'id' );

        if ( $iPollId )
        {
            $iCurrentFeaturedId = $this->oPolls->getFeaturedQuestion();
            if ( $iCurrentFeaturedId )
            {
                $this->oPolls->feature( $iCurrentFeaturedId , 'NOT_FEATURED' );
                $this->view->oldid = $iCurrentFeaturedId;
            }

            $iReturnedPollId = $this->oPolls->feature( $iPollId , 'FEATURED' );
            if ( $iReturnedPollId )
            {
                $this->view->result = true;
                $this->view->id = $iPollId;
            }
            else
            {
                $this->view->result = false;
            }
        }
    }

    public function unpublishedAction()
    {
        $aQuestions = $this->oPolls->listPolls();
        $aOptions = $this->oPolls->listOptions();
        $aFinalPolls = $this->oPolls->toArray( $aQuestions , $aOptions );
        $this->view->title = "Unpublished polls";
        $this->view->polls = $aFinalPolls;
    }

    public function addAction()
    {
        $this->view->title = "Add survey";
        $aSubmissionMessage = '';
        $aErrorMessages = array();

        if ( $this->getRequest()->isPost() )
        {
            $sQuestion = $this->getRequest()->getPost( 'poll-question' );

            $aErrorMessages = array_merge( $aErrorMessages , $this->_helper->_validator->errorMessages( 'notempty' , $sQuestion , "Question cannot be empty." ) );

            $bIsPresent = $this->checkIfAtLeastTwoOptionsIsPresent();
            if ( !$bIsPresent )
            {
                $aErrorMessages[] = "At least two options must be present.";
            }
            if ( empty( $aErrorMessages ) )
            {
                $iQuestionId = $this->oPolls->addPollQuestion( array( 'question' => $sQuestion ) );
                for ( $i = 0; $i < 10; $i++ )
                {
                    $sAnswer = $this->getRequest()->getPost( 'poll-option' . $i );
                    if ( !is_null( $sAnswer ) && !empty( $sAnswer ) )
                    {
                        $iAnswerId = $this->oPolls->addPollAnswer( array( 'answer_text' => $sAnswer ) );
                        $this->oPolls->addPoll( array( 'b_survey_question_id' => $iQuestionId , 'b_survey_option_id' => $iAnswerId ) );
                    }
                }
                $aSubmissionMessage = "Poll has been successfully added to the database.";
            }
            else
            {
                $aSubmissionMessage = "Submission Failed. See errors below.";
                $this->view->post = $this->getRequest()->getPost();
            }
        }

        $this->view->formmessage = $aSubmissionMessage;
        $this->view->formresponse = $aErrorMessages;
        $this->view->url = $this->view->url( array( 'controller' => 'polls' , 'action' => 'add' ) );
    }

    public function voteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sUserId = $aUserData[ 'sys_user_id' ];

        if ( $this->getRequest()->isPost() )
        {
            $sQuestionId = ( int ) $this->getRequest()->getPost( 'qid' );
            $sAnswerid = ( int ) $this->getRequest()->getPost( 'oid' );
            if ( !is_null( $sQuestionId ) && !empty( $sQuestionId ) && !is_null( $sAnswerid ) && !empty( $sAnswerid ) )
            {
                $subtotalVotes = $this->oPolls->addVote( $sQuestionId , $sAnswerid , $sUserId );
                if ( $subtotalVotes )
                {
                    $aHome = $this->oPolls->getPublishedOptions( $sQuestionId );
                    $this->view->optionresults = $aHome;
                    $this->view->total = $this->oPolls->getPublishedOptions( $sQuestionId );
                    $this->view->subtotal = $subtotalVotes;
                    $this->view->result = true;
                    $this->view->qid = $sQuestionId;
                    $this->view->oid = $sAnswerid;
                    $this->view->message = "Success";
                }
                else
                {
                    $this->view->optionresults = array();
                    $this->view->total = 0;
                    $this->view->subtotal = 0;
                    $this->view->result = false;
                    $this->view->qid = $sQuestionId;
                    $this->view->oid = $sAnswerid;
                    $this->view->message = "Cannot delete data.";
                }
            }
        }
    }

    public function getvotesAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        if ( $this->getRequest()->isPost() )
        {
            $sQuestionId = ( int ) $this->getRequest()->getPost( 'qid' );
            if ( !is_null( $sQuestionId ) && !empty( $sQuestionId ) )
            {
                $this->view->result = true;
                $this->view->qid = $sQuestionId;
                $this->view->votes = $this->oPolls->getVotes( $sQuestionId );
                $this->view->message = "Success";
            }
        }
    }

    public function voteoptionsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
        $sUserId = $aUserData[ 'sys_user_id' ];

        if ( $this->getRequest()->isPost() )
        {
            $sQuestionId = $this->oPolls->getRandomPublishedNonfeatureQuestion( $sUserId );
            if ( $sQuestionId )
            {
                $this->view->qid = $sQuestionId;
                $this->view->options = $this->oPolls->getOptions( $sQuestionId );
                $this->view->hasVotedAll = false;
            }
            else
            {
                $this->view->qid = 0;
                $this->view->options = array();
                $this->view->hasVotedAll = true;
            }
            $this->view->result = true;
            $this->view->message = "Success";
        }
    }

    public function viewAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$PEOPLES_MENUITEM );

            $aUserData = $this->_helper->_aclHelper->getCurrentUserData();
            $sRoleId = $aUserData[ 'sys_role_id' ];
            $sUserId = $aUserData[ 'sys_user_id' ];
            $this->view->is_logged = $sRoleId === SiteConstants::$GUESTROLE_ID ? false : true;

            $iQuestionId = ( int ) $this->getRequest()->getParam( 'id' );
            $aQuestions = $this->oPolls->getPublishedQuestion( $iQuestionId );
            $aOptions = $this->oPolls->getPublishedOptions( $iQuestionId );
            $aFinalPolls = $this->oPolls->toSingleArray( $aQuestions , $aOptions );

            $this->view->vote = $this->_helper->_aclHelper->isAllowed( 'role' . $sRoleId , 'polls' , 'vote' );
            $this->view->alreadyvoted = $this->oPolls->checkIfAlreadyVoted( $iQuestionId , $sUserId );
            $this->view->poll = $aFinalPolls;

            $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
            $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iPollId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "polls";
        if ( $this->oPolls->delete( $iPollId ) )
        {
            $this->view->result = true;
            $this->view->id = $iPollId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $iPollId;
            $this->view->message = "Cannot delete data.";
        }
    }

    private function checkIfAtLeastTwoOptionsIsPresent()
    {
        $bPresentCount = 0;
        for ( $i = 0; $i < 10; $i++ )
        {
            $sOption = $this->getRequest()->getPost( 'poll-option' . $i );
            if ( !empty( $sOption ) )
            {
                $bPresentCount++;
            }
        }

        if ( $bPresentCount >= 2 )
        {
            return true;
        }
        return false;
    }

}
