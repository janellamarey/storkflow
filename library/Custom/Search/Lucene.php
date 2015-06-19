<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Custom_Search_Lucene extends Zend_Search_Lucene
{

    public static function create( $directory )
    {
        return new Zend_Search_Lucene_Proxy( new Custom_Search_Lucene( $directory , true ) );
    }

    public static function open( $directory )
    {
        return new Zend_Search_Lucene_Proxy( new Custom_Search_Lucene( $directory , false ) );
    }

    public function addDocument( Zend_Search_Lucene_Document $document )
    {
        $docRef = $document->docRef;
        $term = new Zend_Search_Lucene_Index_Term( $docRef , 'docRef' );
        $query = new Zend_Search_Lucene_Search_Query_Term( $term );
        $results = $this->find( $query );

        if ( count( $results ) > 0 )
        {
            foreach ( $results as $result )
            {
                $this->delete( $result->id );
            }
        }
        return parent::addDocument( $document );
    }

}
