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
                ->setAttrib( 'class' , 'simpleForm' )
                ->setAttrib( 'id' , 'saveChange' );
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

        $oCurrentPasswordValidator = new Zend_Validate_Db_RecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'password' ,
                'adapter' => Zend_Registry::get( 'db' ) ,
                'exclude' => "id = " . $this->userRoleId
                ) );
        
        $oNewPasswordValidator = new Zend_Validate_Db_NoRecordExists(
                array(
                'table' => 'sys_user_roles' ,
                'field' => 'password' ,
                'adapter' => Zend_Registry::get( 'db' ) ,
                'exclude' => "id = " . $this->userRoleId
                ) );

        $oIdenticalValidator = new Zend_Validate_Identical();
        $oIdenticalValidator->setStrict( false )->setToken( 'password' );

        $oPasswordLengthValidator = new Zend_Validate_StringLength( array( 'min' => 1 , 'max' => 10 ) );

        //current password
        $oChangePasswordNote = new Zend_Form_Element_Note( 'changepass_info' );
        $oChangePasswordNote->setValue( '<h3>Current password</h3>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oChangePasswordNote );

        //current password field
        $this->addElement( 'password' , 'current_password' );
        $this->getElement( 'current_password' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oCurrentPasswordValidator )
                ->setDecorators( $aFieldDecorators );

        //password
        $oPasswordNote = new Zend_Form_Element_Note( 'password_label' );
        $oPasswordNote->setValue( '<h3>New Password</h3>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oPasswordNote );

        //password field
        $this->addElement( 'password' , 'password' );
        $this->getElement( 'password' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oPasswordLengthValidator )
                ->addValidator( $oNewPasswordValidator)
                ->setDecorators( $aFieldDecorators );

        //duplicate password
        $oDuplicatePasswordNote = new Zend_Form_Element_Note( 'repassword_label' );
        $oDuplicatePasswordNote->setValue( '<h3>Type new password again</h3>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oDuplicatePasswordNote );

        //duplicate password field
        $this->addElement( 'password' , 'repassword' );
        $this->getElement( 'repassword' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->addValidator( $oPasswordLengthValidator )
                ->addValidator( 'identical' , false , array( 'token' => 'password' ) )
                ->setDecorators( $aFieldDecorators );


        //login button
        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
