<?php

class SearchIndexer
{

    protected static $_indexDirectory;

    public static function setIndexDirectory( $directory )
    {
        if ( !is_dir( $directory ) )
        {
            throw new Exception( 'Directory for Searchindexer is invalid (' . $directory . ') ' );
        }
        self::$_indexDirectory = $directory;
    }

    public static function getIndexDirectory()
    {
        return self::$_indexDirectory;
    }

    public static function observeTableRow( $event , $row )
    {
        switch ( $event )
        {
            case 'post-insert':
            case 'post-update':
                $doc = self::getDocument( $row );
                if ( $doc !== false && $row->searchable === 1 )
                {
                    self::_addToIndex( $doc );
                }
                break;
        }
    }

    public static function getDocument( Custom_Db_Table_Row_Observable $row )
    {
        if ( method_exists( $row , 'getSearchIndexField' ) )
        {
            $fields = $row->getSearchIndexField();
            $doc = new Custom_Search_Lucene_Document(
                    $fields[ 'class' ] , $fields[ 'key' ] , $fields[ 'title' ] , $fields[ 'contents' ] , $fields[ 'summary' ] , $fields[ 'createdBy' ] , $fields[ 'dateCreated' ]
            );
            return $doc;
        }
        return false;
    }

    protected static function _addToIndex( $doc )
    {
        try
        {
            $index = Custom_Search_Lucene::open( self::$_indexDirectory );
        }
        catch ( Exception $e )
        {
            $index = Custom_Search_Lucene::create( self::$_indexDirectory );
        }
        $index->addDocument( $doc );
        $index->commit();
    }

}
