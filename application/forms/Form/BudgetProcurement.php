<?php

class Form_BudgetProcurement extends Zend_Form
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

        $this->addElement( 'hidden' , 'id' );
        
        $this->addElement( 'text' , 'name' );
        $this->getElement( 'name' )->setRequired( true )
                ->addFilter( 'StringTrim' )
                ->setDecorators( $aFieldDecorators );
        
        $oTypes = new Zend_Form_Element_Select( 'type' );
        $oTypes->setLabel( "Type:" )->addMultiOptions( array( 'BUDGET' => 'Budgets' ,
                'PROCUREMENT' => 'Procurements' ) );
        $oTypes->setDecorators( $aFieldDecorators );
        $this->addElement( $oTypes );
        
        $this->addElement( 'submit' , 'Save' );
        $this->getElement( 'Save' )->setDecorators( $aButtonDecorators );

        $this->setDecorators( array( 'FormElements' , array( 'HtmlTag' , array( 'tag' => 'ul' ) ) ,
                array( array( 'DivTag' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'loginDiv' ) ) ,
                'Form' ) );
    }

}
