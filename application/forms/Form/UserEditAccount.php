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
                ->setAttrib( 'class' , 'form' )
                ->setAttrib( 'id' , 'edit-account' );
        $this->clearDecorators();
        $fieldDecorators = array(
                array( 'ViewHelper' ) ,
                array( 'Errors' ) ,
                array(
                        'HtmlTag' ,
                        array( 'tag' => 'li' )
                )
        );

        $buttonDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => 'submit'
                        )
                )
        );

        $defaultDecorators = array(
                array( 'ViewHelper' ) ,
                array(
                        'HtmlTag' ,
                        array(
                                'tag' => 'li' ,
                                'class' => ''
                        )
                )
        );

        $noUsernameDuplicateValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'username' ,
                'adapter' => Zend_Registry::get( 'db' ) ,
                'exclude' => array(
                        'field' => 'id' ,
                        'value' => $this->userRoleId
                )
                ) );

        $this->addElement( 'hidden' , 'id' )->setDecorators( $fieldDecorators );

        //account information
        $accountNote = new Zend_Form_Element_Note( 'account_info' );
        $accountNote->setValue( '<h3>Account Information</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $accountNote );

        //username
        $usernameNote = new Zend_Form_Element_Note( 'username_label' );
        $usernameNote->setValue( 'Username' )->setDecorators( $defaultDecorators );
        $this->addElement( $usernameNote );

        //username field
        $this->addElement( 'text' , 'username' );
        $this->getElement( 'username' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $noUsernameDuplicateValidator )
                ->setDecorators( $fieldDecorators );

        //login button
        $this->addElement( 'submit' , 'save' );
        $this->getElement( 'save' )->setLabel( 'Save' )->setDecorators( $buttonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }
}
