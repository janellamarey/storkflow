<?php

class Form_NewsAdd extends Zend_Form
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
                ->setAttrib( 'id' , 'saveAdd' );
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

        $this->addElement( 'text' , 'title' );
        $this->getElement( 'title' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $this->addElement( 'textarea' , 'body' );
        $this->getElement( 'body' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );

        $type = new Zend_Form_Element_Select( 'type' );
        $type->setLabel( "Type:" )->addMultiOptions( array( '2' => 'News' ,
                '4' => 'Events' ) );
        $type->setDecorators( $aFieldDecorators );
        $this->addElement( $type );

        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
