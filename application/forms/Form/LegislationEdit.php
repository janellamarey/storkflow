<?php

class Form_LegislationEdit extends Zend_Form
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
        $this->setAction( $this->_actionUrl )->setMethod( 'post' )
                ->setAttrib( 'class' , 'simpleForm' )
                ->setAttrib( 'id' , 'saveEdit' );
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

        $this->addElement( 'hidden' , 'id' )->setDecorators( $aFieldDecorators );

        $this->addElement( 'text' , 'name' );
        $this->getElement( 'name' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $this->addElement( 'textarea' , 'summary' );
        $this->getElement( 'summary' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $oTypes = new Zend_Form_Element_Select( 'type' );
        $oTypes->setLabel( "Type:" )->addMultiOptions( array( 'ORDINANCE' => 'Ordinance' ,
                'RESOLUTION' => 'Resolutions' ) );
        $oTypes->setDecorators( $aFieldDecorators );
        $this->addElement( $oTypes );

        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
