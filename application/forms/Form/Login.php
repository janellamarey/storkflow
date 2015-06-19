<?php

class Form_Login extends Zend_Form
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
        $this->setAction( $this->_actionUrl )->setMethod( 'post' )->setAttrib( 'class' , 'simpleForm' );
        $this->clearDecorators();
        $aFieldDecorators = array(
            array( 'ViewHelper' ) ,
            array( 'Errors' ) ,
            array( 'HtmlTag' , array( 'tag' => 'li' ) ) ,
        );

        $aButtonDecorators = array(
            array( 'ViewHelper' ) ,
            array( 'HtmlTag' , array(
                    'tag' => 'li' ,
                    'class' => 'submit' ) ) );
        
        $aDefaultDecorators = array(
            array( 'ViewHelper' ) ,
            array( 'HtmlTag' , array(
                    'tag' => 'li' ,
                    'class' => '' ) ) );
        
        $oTitleNote = new Zend_Form_Element_Note( 'title_label' );
        $oTitleNote->setValue( '<h1>Login</h1>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oTitleNote );
        
        $oUsernameNote = new Zend_Form_Element_Note( 'username_label' );
        $oUsernameNote->setValue( 'Username *' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oUsernameNote );
        
        $this->addElement( 'text' , 'username' );
        $this->getElement( 'username' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addPrefixPath( 'Custom_Validate' , 'Custom/Validate/' , 'validate' )
                ->addValidator( 'Authorise' )
                ->setDecorators( $aFieldDecorators );
        
        $oPassNote = new Zend_Form_Element_Note( 'password_label' );
        $oPassNote->setValue( 'Password *' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oPassNote );
        
        $this->addElement( 'password' , 'password' );
        $this->getElement( 'password' )->setRequired( true )->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $oForgotPassNote = new Zend_Form_Element_Note( 'forgot_password' );
        $oForgotPassNote->setValue( '<a href="' . $this->getView()->serverUrl( $this->getView()
                                ->url( array( 'action' => 'forgotpass' ) ) ) . '">Forgotten Username/Password?</a>' )
                                ->setDecorators( $aDefaultDecorators );
        $this->addElement( $oForgotPassNote );
                
        $this->addElement( 'submit' , 'Login' );
        $this->getElement( 'Login' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
            array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
            'Form' ) );
    }

}
