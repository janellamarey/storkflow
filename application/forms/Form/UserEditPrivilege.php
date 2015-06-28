<?php

class Form_UserEditPrivilege extends Zend_Form
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
                ->setAttrib( 'id' , 'edit-privilege' );
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

        $this->addElement( 'hidden' , 'id' )->setDecorators( $fieldDecorators );
        //privilege information
        $accountNote = new Zend_Form_Element_Note( 'account_info' );
        $accountNote->setValue( '<h3>Privilege Information</h3>' )->setDecorators( $defaultDecorators );
        $this->addElement( $accountNote );

        //role field
        $roles = new Zend_Form_Element_Select( 'sys_role_id' );
        $roles->setLabel( "Roles" )->addMultiOptions( array( '1' => 'Administrator' , '2' => 'Scrum Master', '3' => 'Member' ) );
        $roles->setDecorators( $fieldDecorators );
        $this->addElement( $roles );

        //login button
        $this->addElement( 'submit' , 'save' );
        $this->getElement( 'save' )->setDecorators( $buttonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
