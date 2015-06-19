<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'News' );
Zend_Loader::loadClass( 'Polls' );
Zend_Loader::loadClass( 'Ordinances' );
Zend_Loader::loadClass( 'Images' );

class NewsController extends Zend_Controller_Action
{

    function init()
    {
        $this->_helper->_aclHelper->allow( SiteConstants::$ADMIN , array( 'index' , 'add' , 'view' , 'edit' , 'delete' , 'deletefile' ) );
        $this->_helper->_aclHelper->allow( SiteConstants::$GUESTROLE , array( 'index' , 'view' ) );

        $this->_helper->_aclHelper->addResource( 'polls' );
        $this->_helper->_aclHelper->allowAll( 'polls' , SiteConstants::$COUNCILOR , 'vote' );

        $this->oNews = new News();
        $this->oPolls = new Polls();
        $this->oImages = new Images();
        $this->oOrdinances = new Ordinances();

        $this->_helper->_menuHelper->setMenuItemName( SiteConstants::$NEWS_MENUITEM );

        $ajaxContext = $this->_helper->getHelper( 'AjaxContext' );
        $ajaxContext->addActionContext( 'delete' , 'json' );
        $ajaxContext->addActionContext( 'deletefile' , 'json' );
        $ajaxContext->initContext();
    }

    public function indexAction()
    {
        $userData = $this->_helper->_aclHelper->getCurrentUserData();
        $roleId = $userData[ 'sys_role_id' ];
        $userId = $userData[ 'sys_user_id' ];
        $this->view->is_logged = $roleId === SiteConstants::$GUESTROLE_ID ? false : true;

        $isAddingAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'news' , 'add' );
        $isEditingAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'news' , 'edit' );
        $isDeletingAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'news' , 'delete' );

        $news = $this->oNews->getBacoorNews();
        $events = $this->oNews->getBacoorEvents();

        $this->view->news = $this->oNews->toArray( $news , $isEditingAllowed , $isDeletingAllowed );
        $this->view->events = $this->oNews->toArray( $events , $isEditingAllowed , $isDeletingAllowed );

        $this->view->add = $isAddingAllowed;

        $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
        $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
        $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'polls' , 'vote' );
        $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $userId );
        $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
        $this->view->poll = $aFeaturedPoll;
    }

    public function addAction()
    {
        $message = "";
        $form = new Form_NewsAdd( '/news/add/' );

        if ( $this->_request->isPost() )
        {
            if ( $form->isValid( $this->_request->getPost() ) )
            {
                $title = $this->_request->getPost( 'title' );
                $body = $this->_request->getPost( 'body' );
                $type = ( int ) $this->_request->getPost( 'type' );
                $data = array( 'title' => $title , 'body' => $body , 'type' => $type );
                $newsId = $this->oNews->add( $data );

                if ( $newsId )
                {
                    $message = "Submission success.";
                }
            }
            else
            {
                $form->populate( $this->_request->getPost() );
                $message = 'Submission Failed. See errors below.';
            }
        }
        $this->view->form = $form;
        $this->view->message = $message;
    }

    public function editAction()
    {
        $sMessage = "";
        $oForm = new Form_NewsEdit( '/news/edit/' );
        if ( $this->_request->isPost() )
        {
            $iNewsId = $this->_request->getPost( 'id' );
            $sTitle = $this->_request->getPost( 'title' );
            $sBody = $this->_request->getPost( 'body' );

            if ( $oForm->isValid( $this->_request->getPost() ) )
            {
                $this->handlePostFiles( $iNewsId , $oForm );

                $aData = array( 'title' => $sTitle , 'body' => $sBody );
                $this->oNews->update( $aData , $iNewsId );
                $sMessage = "News content has been successfully updated.";
            }
            else
            {
                $oForm->populate( $this->_request->getPost() );
                $sMessage = 'Submission Failed. See errors below.';
            }
            $oFinalNews = $this->oNews->getNews( $iNewsId );
            $this->view->post_type_name = $oFinalNews[ 'post_type_name' ];
            $this->view->images = $this->oNews->getImages( $iNewsId );
        }

        if ( $this->_request->isGet() )
        {
            $iNewsId = $this->_request->getParam( 'id' );
            if ( $iNewsId )
            {
                $oFinalNews = $this->oNews->getNews( $iNewsId );
                $oForm->populate( array( 'id' => $iNewsId , 'title' => $oFinalNews[ 'title' ] , 'body' => $oFinalNews[ 'body' ] ) );
                $this->view->post_type_name = $oFinalNews[ 'post_type_name' ];
            }

            $this->view->images = $this->oNews->getImages( $iNewsId );
        }

        $this->view->form = $oForm;
        $this->view->message = $sMessage;
    }

    private function handlePostFiles( $iNewsId , $oForm , $bInsertToDB = true )
    {
        $oForm->getValues();

        $oAppConfig = Zend_Registry::get( 'config' );
        $sDestinationFolder = $oAppConfig->upload->root->posts
                . DIRECTORY_SEPARATOR . $iNewsId;
        if ( !file_exists( $sDestinationFolder ) )
        {
            mkdir( $sDestinationFolder );
        }

        $aFiles = $oForm->images->getFileName();
        if ( !is_null( $aFiles ) && !empty( $aFiles ) )
        {
            $basename = basename( $aFiles );
            rename( $aFiles , $sDestinationFolder . DIRECTORY_SEPARATOR . $basename );

            if ( $bInsertToDB )
            {
                $sCaption = $this->_request->getPost( 'caption' );
                $aData = array(
                        "post_id" => $iNewsId ,
                        "name" => $basename ,
                        "caption" => $sCaption
                );
                $this->oNews->saveImages( $aData );
            }
        }
    }

    public function viewAction()
    {
        if ( $this->getRequest()->isGet() )
        {
            $newsId = ( int ) $this->getRequest()->getParam( 'id' );

            $userData = $this->_helper->_aclHelper->getCurrentUserData();
            $roleId = $userData[ 'sys_role_id' ];
            $userId = $userData[ 'sys_user_id' ];
            $this->view->is_logged = $roleId === SiteConstants::$GUESTROLE_ID ? false : true;

            $this->view->edit = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'news' , 'edit' );

            $news = $this->oNews->getNews( $newsId );
            if ( !is_null( $news ) && $news[ 'title' ] && $news[ 'body' ] )
            {
                $this->view->title = $news[ 'title' ];
                $this->view->body = $news[ 'body' ];
                $this->view->post_type_name = $news[ 'post_type_name' ];
                $this->view->date_created = $news[ 'date_created' ];
                $this->view->user_created = $news[ 'user_created' ];
            }
            else
            {
                $this->view->title = "There seems to be a problem in rendering the news.";
                $this->view->body = "";
            }

            $config = Zend_Registry::get( 'config' );
            $path = $config->upload->root->posts
                    . DIRECTORY_SEPARATOR . $newsId;
            if ( !file_exists( $path ) )
            {
                mkdir( $path );
            }
            $this->view->images = $this->oNews->getImages( $newsId , true , 3 );

            $this->view->registerURL = $this->view->url( array( 'controller' => 'users' , 'action' => 'register' ) );
            $this->view->ordinances = $this->oOrdinances->getOrdinances( 5 , 200 , 'PUBLISHED' );
            $bVoteAllowed = $this->_helper->_aclHelper->isAllowed( 'role' . $roleId , 'polls' , 'vote' );
            $aFeaturedPoll = $this->oPolls->getFeaturedPoll( $bVoteAllowed , $userId );
            $this->view->vote = $bVoteAllowed && !empty( $aFeaturedPoll );
            $this->view->poll = $aFeaturedPoll;
        }
        $this->view->id = $newsId;
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iNewsId = ( int ) $this->getRequest()->getPost( 'id' );
        $this->view->list = "news";
        if ( $this->oNews->delete( $iNewsId ) )
        {
            $this->view->result = true;
            $this->view->id = $iNewsId;
            $this->view->message = "Success";
        }
        else
        {
            $this->view->result = false;
            $this->view->id = $iNewsId;
            $this->view->message = "Cannot delete data.";
        }
    }

    public function deletefileAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        $iFileName = $this->getRequest()->getPost( 'filename' );
        $iPostId = $this->getRequest()->getPost( 'postid' );

        if ( $this->oNews->deletefile( $iPostId , $iFileName ) )
        {
            $this->view->result = true;
        }
        else
        {
            $this->view->result = false;
        }
    }

}
