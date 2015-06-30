<?php

class Form_LogoutInHeader extends Zend_Form
{

    public function init()
    {
        $linkDecorators = array( array( 'ViewHelper' ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'form-div' ) )
        );

        $buttonDecorators = array(
                array( 'ViewHelper' )
        );

        $this->addElement( new Zend_Form_Element_Note( 'welcome' ) );
        $this->getElement( 'welcome' )->setDecorators( $linkDecorators );


        $this->addElement( 'hidden' , 'sendlogout' , '1' );
        $this->getElement( 'sendlogout' )->setDecorators( $linkDecorators );

        $this->addDisplayGroup( array( 'welcome' , 'sendlogout' ) , 'firstColumn' , array( 'disableLoadDefaultDecorators' => true ) );
        $columnDecorator = array(
                array( 'FormElements' ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'form-block' ) )
        );
        $this->getDisplayGroup( 'firstColumn' )->setDecorators( $columnDecorator );
        
        $this->addElement( 'submit' , 'logout' );
        $this->getElement( 'logout' )
                ->setLabel( 'LOG OUT' )
                ->setAttrib( "class" , "btn btn-default btn-sm" )
                ->setDecorators( $buttonDecorators );



        $this->addDisplayGroup( array( 'logout' ) , 'secondColumn' , array( 'disableLoadDefaultDecorators' => true ) );
        $secondColumnDecorator = array(
                array( 'FormElements' ) ,
                array( array( 'div' => 'HtmlTag' ) , array( 'tag' => 'div' , 'class' => 'clear' ) )
        );
        $this->getDisplayGroup( 'secondColumn' )->setDecorators( $secondColumnDecorator );

        $this->setDecorators( array( 'FormElements' , 'Form' ) );
    }

}
