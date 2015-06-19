<?php

class Form_UserEditAccount extends Zend_Form
{

    protected $_actionUrl;
    protected $userRoleId;

    public function __construct( $actionUrl = null , $options = null )
    {
        parent::__construct( $options );
        $this->_actionUrl = $actionUrl;
    }

    public function setData( array $options = array() )
    {
        if ( $options[ 'user_role_id' ] )
        {
            $this->userRoleId = $options[ 'user_role_id' ];
        }
    }

    public function init()
    {
        $this->setAction( $this->_actionUrl )->setMethod( 'post' )
                ->setAttrib( 'class' , 'simpleForm' )
                ->setAttrib( 'id' , 'saveEdit' );
        $this->clearDecorators();
        $aFieldDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'Errors' ) ,
                array(
                        'HtmlTag' ,
                        array( 'tag' => 'li' )
                )
        );

        $aButtonDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => 'submit'
                        )
                )
        );

        $aDefaultDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => ''
                        )
                )
        );

        $oUsernameDBValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'username' ,
                'adapter' => Zend_Registry::get( 'db' ) ,
                'exclude' => array(
                        'field' => 'id' ,
                        'value' => $this->userRoleId
                )
                ) );

        $this->addElement( 'hidden' , 'id' )->setDecorators( $aFieldDecorators );
        //account information
        $oAccountNote = new Zend_Form_Element_Note( 'account_info' );
        $oAccountNote->setValue( '<h3>Account Information</h3>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oAccountNote );

        //username
        $oUsernameNote = new Zend_Form_Element_Note( 'username_label' );
        $oUsernameNote->setValue( 'Username' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oUsernameNote );

        //username field
        $this->addElement( 'text' , 'username' );
        $this->getElement( 'username' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oUsernameDBValidator )
                ->setDecorators( $aFieldDecorators );

        //password
        $oPasswordNote = new Zend_Form_Element_Note( 'password_label' );
        $oPasswordNote->setValue( 'Password' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oPasswordNote );

        //password field
        $this->addElement( 'text' , 'password' );
        $this->getElement( 'password' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        //login button
        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
