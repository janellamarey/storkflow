<?php

class Form_LoginInHeader extends Zend_Form
{

    protected $_actionUrl;

    public function __construct( $actionUrl = null , $options = null )
    {
        parent::__construct( $options );
        $this->_actionUrl = $actionUrl;
        $this->init();
    }

    public function init()
    {
        $this->setAction( $this->_actionUrl )->setMethod( 'post' );
        $this->clearDecorators();

        $linkDecorators = array( array( 'ViewHelper' ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'form-div' ) )
        );

        $inputDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'Errors' ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' ) )
        );

        $buttonDecorators = array(
                array( 'ViewHelper' ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'form-block' ) )
        );
        
        $this->addElement( 'hidden' , 'sendlogin' );
        $this->getElement( 'sendlogin' )->setDecorators( $linkDecorators );
        
        $this->addElement( 'text' , 'username' );
        $this->getElement( 'username' )
                ->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addPrefixPath( 'Custom_Validate' , 'Custom/Validate/' , 'validate' )
                ->addValidator( 'Authorise' )
                ->setDecorators( $inputDecorators );

        $registerLink = new Zend_Form_Element_Note( 'register_user' );
        $registerLink->setValue( '<a href="' . $this->getView()->serverUrl( $this->getView()
                                ->url( array( 'controller' => 'users' , 'action' => 'registeruser' ) , null , true ) ) . '">Not Yet Registered?</a>' )
                ->setDecorators( $linkDecorators );
        $this->addElement( $registerLink );

        $this->addDisplayGroup( array( 'username' , 'register_user' ) , 'firstColumn' , array( 'disableLoadDefaultDecorators' => true ) );
        $columnDecorator = array(
                array( 'FormElements' ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'login-form-block' ) )
        );
        $this->getDisplayGroup( 'firstColumn' )->setDecorators( $columnDecorator );

        $this->addElement( 'password' , 'password' );
        $this->getElement( 'password' )
                ->setRequired( true )->addFilter( 'StringTrim' )
                ->setDecorators( $inputDecorators );

        $forgotPasswordLink = new Zend_Form_Element_Note( 'forgot_password' );
        $forgotPasswordLink->setValue( '<a href="' . $this->getView()->serverUrl( $this->getView()
                                ->url( array( 'controller' => 'users' , 'action' => 'forgotpass' ) , null , true ) ) . '">Forget Password?</a>' )
                ->setDecorators( $linkDecorators );
        $this->addElement( $forgotPasswordLink );

        $this->addDisplayGroup( array( 'password' , 'forgot_password' ) , 'secondColumn' , array( 'disableLoadDefaultDecorators' => true ) );
        $this->getDisplayGroup( 'secondColumn' )->setDecorators( $columnDecorator );

        $this->addElement( 'submit' , 'login' );
        $this->getElement( 'login' )
                ->setLabel( 'SIGN IN' )
                ->setAttrib( "class" , "search_btn" )
                ->setDecorators( $buttonDecorators );

        $this->setDecorators( array( 'FormElements' , 'Form' ) );
    }

}
