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

        $this->addElement( 'hidden' , 'id' )->setDecorators( $aFieldDecorators );
        //privilege information
        $oAccountNote = new Zend_Form_Element_Note( 'account_info' );
        $oAccountNote->setValue( '<h3>Privilege Information</h3>' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oAccountNote );

        //role
        $oRoleNote = new Zend_Form_Element_Note( 'role_label' );
        $oRoleNote->setValue( 'Privilege' )->setDecorators( $aDefaultDecorators );
        $this->addElement( $oRoleNote );

        //role field
        $oRoles = new Zend_Form_Element_Select( 'sys_role_id' );
        $oRoles->setLabel( "Roles" )->addMultiOptions( array( '0' => '' , '1' => 'Super Administrator' ,
                '2' => 'Super User' , '3' => 'Administrator' , '4' => 'Voter' , '5' => 'Councilor' ) );
        $oRoles->setDecorators( $aFieldDecorators );
        $this->addElement( $oRoles );

        //login button
        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
