<?php

class Form_PasswordChange extends Zend_Form
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
                ->setAttrib( 'id' , 'change-password' );
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

        $currentPasswordValidator = new Zend_Validate_Db_RecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'password' ,
                'adapter' => Zend_Registry::get( 'db' ) ,
                'exclude' => "id = " . $this->userRoleId
                ) );

        $newPasswordValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'password' ,
                'adapter' => Zend_Registry::get( 'db' ) ,
                'exclude' => "id = " . $this->userRoleId
                ) );

        $identicalValidator = new Zend_Validate_Identical();
        $identicalValidator->setStrict( false )->setToken( 'password' );

        $passwordLengthValidator = new Zend_Validate_StringLength( array( 'min' => 1 , 'max' => 10 ) );

        //current password
        $changePasswordNote = new Zend_Form_Element_Note( 'changepass_info' );
        $changePasswordNote->setValue( '<h3>Current password</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $changePasswordNote );

        //current password field
        $this->addElement( 'password' , 'current_password' );
        $this->getElement( 'current_password' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $currentPasswordValidator )
                ->setDecorators( $fieldDecorators );

        //password
        $passwordNote = new Zend_Form_Element_Note( 'password_label' );
        $passwordNote->setValue( '<h3>New Password</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $passwordNote );

        //password field
        $this->addElement( 'password' , 'password' );
        $this->getElement( 'password' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $passwordLengthValidator )
                ->addValidator( $newPasswordValidator )
                ->setDecorators( $fieldDecorators );

        //duplicate password
        $duplicatePasswordNote = new Zend_Form_Element_Note( 'repassword_label' );
        $duplicatePasswordNote->setValue( '<h3>Type new password again</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $duplicatePasswordNote );

        //duplicate password field
        $this->addElement( 'password' , 'repassword' );
        $this->getElement( 'repassword' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $passwordLengthValidator )
                ->addValidator( 'identical' , false , array( 'token' => 'password' ) )
                ->setDecorators( $fieldDecorators );


        //login button
        $this->addElement( 'submit' , 'save' );
        $this->getElement( 'save' )->setLabel( 'Save' )->setDecorators( $buttonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
