<?php

Zend_Loader::loadClass( 'DB_Polls' );
Zend_Loader::loadClass( 'DB_PollQuestions' );
Zend_Loader::loadClass( 'DB_PollAnswers' );
Zend_Loader::loadClass( 'DB_PollUsers' );

class Polls
{

    const QUERY_RANDOM_PUBLISHED_NON_FEATURE_POLL_NOT_VOTED = "
                    SELECT 
                            b_survey_questions.id                         
                            
                    FROM
                            b_survey_questions LEFT JOIN b_survey_users
                            ON b_survey_questions.id = b_survey_users.b_survey_question_id
                    WHERE 
                            (b_survey_users.sys_user_id <> ? OR b_survey_users.sys_user_id IS NULL)
                            AND b_survey_questions.status = 'PUBLISHED'
                            AND b_survey_questions.featured = 'NOT_FEATURED'
                            AND b_survey_questions.deleted = 0
                            AND (b_survey_users.deleted = 0 OR b_survey_users.deleted IS NULL)
                    ORDER BY RAND()
                    LIMIT 0,1";

    public function __construct()
    {
        $this->oDb = Zend_Registry::get( 'db' );
        $this->oPolls = new DB_Polls();
        $this->oPollQuestions = new DB_PollQuestions();
        $this->oPollAnswers = new DB_PollAnswers();
        $this->oPollUsers = new DB_PollUsers();
    }

    public function addPollQuestion( array $data )
    {
        return $this->oPollQuestions->insertData( $data );
    }

    public function addPollAnswer( array $data )
    {
        return $this->oPollAnswers->insertData( $data );
    }

    public function addVote( $sQuestionId , $sAnswerId , $sUserId )
    {
        $sQuery = "
                    SELECT
                            b_surveys.id,
                            b_surveys.b_survey_question_id AS question_id,
                            b_surveys.b_survey_option_id AS option_id,
                            b_surveys.votes
                    FROM 
                            b_surveys
                    WHERE
                            b_surveys.b_survey_question_id = ?
                            AND b_surveys.b_survey_option_id = ?";

        $oRow = $this->oDb->fetchRow( $sQuery , array( $sQuestionId , $sAnswerId ) );
        $iVotes = (( int ) $oRow[ 'votes' ]) + 1;

        $this->oPolls->updateData( array( 'votes' => $iVotes ) , $oRow[ 'id' ] );

        $this->oPollUsers->insertData( array( 'b_survey_question_id' => $oRow[ 'question_id' ] , 'sys_user_id' => $sUserId ) );
        return $iVotes;
    }

    public function listPolls( $publish = 'NOT_PUBLISHED' )
    {
        $sQuery = "
                    SELECT
                            b_survey_questions.id AS survey_question_id,
                            b_survey_questions.question AS survey_question,
                            b_survey_questions.featured AS featured
                    FROM 
                            b_survey_questions
                    WHERE
                            b_survey_questions.deleted = 0
                            AND b_survey_questions.status = '" . $publish . "'";

        return $this->oDb->fetchAll( $sQuery );
    }

    public function listOptions( $publish = 'NOT_PUBLISHED' )
    {
        $sQuery = "
                    SELECT 
                            b_surveys.id AS survey_id,
                            b_survey_questions.id AS survey_question_id,
                            b_survey_options.id AS survey_option_id,
                            b_survey_options.answer_text AS survey_option,
                            b_surveys.votes AS votes
                    FROM 
                            b_surveys
                            LEFT JOIN b_survey_questions
                            ON b_surveys.b_survey_question_id = b_survey_questions.id
                            LEFT JOIN b_survey_options
                            ON b_surveys.b_survey_option_id = b_survey_options.id
                    WHERE
                            b_surveys.deleted = 0
                            AND b_survey_questions.deleted = 0
                            AND b_survey_options.deleted = 0
                            AND b_survey_questions.status = '" . $publish . "'";

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getQuestion( $qid )
    {
        $sQuery = "
                    SELECT
                            b_survey_questions.id AS survey_question_id,
                            b_survey_questions.question AS survey_question,
                            b_survey_questions.featured AS featured
                    FROM 
                            b_survey_questions
                    WHERE
                            b_survey_questions.deleted = 0
                            AND b_survey_questions.id = " . $qid;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getOptions( $qid )
    {
        $sQuery = "
                    SELECT 
                            b_surveys.id AS survey_id,
                            b_survey_questions.id AS survey_question_id,
                            b_survey_options.id AS survey_option_id,
                            b_survey_options.answer_text AS survey_option
                    FROM 
                            b_surveys
                            LEFT JOIN b_survey_questions
                            ON b_surveys.b_survey_question_id = b_survey_questions.id
                            LEFT JOIN b_survey_options
                            ON b_surveys.b_survey_option_id = b_survey_options.id
                    WHERE
                            b_surveys.deleted = 0
                            AND b_survey_questions.deleted = 0
                            AND b_survey_options.deleted = 0
                            AND b_surveys.b_survey_question_id = " . $qid;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getPublishedQuestion( $qid )
    {
        $sQuery = "
                    SELECT
                            b_survey_questions.id AS survey_question_id,
                            b_survey_questions.question AS survey_question,
                            b_survey_questions.featured AS featured
                    FROM 
                            b_survey_questions
                    WHERE
                            b_survey_questions.deleted = 0
                            AND b_survey_questions.status = 'PUBLISHED'
                            AND b_survey_questions.id = " . $qid;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getPublishedOptions( $qid )
    {
        $sQuery = "
                    SELECT 
                            b_surveys.id AS survey_id,
                            b_survey_questions.id AS survey_question_id,
                            b_survey_options.id AS survey_option_id,
                            b_survey_options.answer_text AS survey_option,
                            b_surveys.votes AS votes
                    FROM 
                            b_surveys
                            LEFT JOIN b_survey_questions
                            ON b_surveys.b_survey_question_id = b_survey_questions.id
                            LEFT JOIN b_survey_options
                            ON b_surveys.b_survey_option_id = b_survey_options.id
                    WHERE
                            b_surveys.deleted = 0
                            AND b_survey_questions.deleted = 0
                            AND b_survey_options.deleted = 0
                            AND b_survey_questions.status = 'PUBLISHED'
                            AND b_surveys.b_survey_question_id = " . $qid;

        return $this->oDb->fetchAll( $sQuery );
    }

    public function getVotes( $qid )
    {
        $sQuery = "
                    SELECT
                            b_surveys.id,
                            b_surveys.b_survey_question_id AS survey_question_id,
                            b_surveys.b_survey_option_id AS survey_option_id,
                            b_surveys.votes AS votes,
                            b_survey_options.answer_text AS option_name
                    FROM 
                            b_surveys
                            LEFT JOIN b_survey_options
                            ON b_surveys.b_survey_option_id = b_survey_options.id
                    WHERE
                            b_surveys.deleted = 0
                            AND b_surveys.b_survey_question_id = " . $qid;

        return $this->oDb->fetchAll( $sQuery );
    }

    private function getSelectedQuestionId( $bVoteAllowed , $sUserId )
    {
        $iSelectedQuestionId = 0;
        $bAlreadyVotedInFeatureQuestion = $this->checkIfAlreadyVoted( $this->getFeaturedQuestion() , $sUserId );
        if ( $bVoteAllowed && $bAlreadyVotedInFeatureQuestion )
        {
            $iSelectedQuestionId = $this->getRandomPublishedNonfeatureQuestion( $sUserId );
        }
        else
        {
            $iSelectedQuestionId = $this->getFeaturedQuestion();
        }
        return $iSelectedQuestionId;
    }

    public function getFeaturedPoll( $bVoteAllowed , $sUserId )
    {
        $iSelectedQuestionId = $this->getSelectedQuestionId( $bVoteAllowed , $sUserId );
        $aQuestion = $this->getPublishedQuestion( $iSelectedQuestionId );
        $aOptions = $this->getPublishedOptions( $iSelectedQuestionId );
        if ( !is_null( $aQuestion ) && !empty( $aQuestion ) && !is_null( $aOptions ) && !empty( $aOptions ) )
        {
            return $this->toSingleArray( $aQuestion , $aOptions );
        }
        return array();
    }

    public function addPoll( array $data )
    {
        return $this->oPolls->insertData( $data );
    }

    public function edit( array $data )
    {
        return $this->oPolls->insertData( $data );
    }

    public function delete( $q_id )
    {
        $aData = array(
                'deleted' => 1
        );

        $options = $this->getOptions( $q_id );
        foreach ( $options as $option )
        {
            $this->oPolls->updateData( $aData , $option[ 'survey_id' ] );
            $this->oPollAnswers->updateData( $aData , $option[ 'survey_option_id' ] );
        }
        $this->oPollQuestions->updateData( $aData , $q_id );

        return true;
    }

    public function toArray( $aQuestions , $aOptions , $bDeleteAllowed = false , $bFeatureAllowed = false )
    {
        $aFinalPolls = $this->getStructuredArray( $aQuestions , $aOptions , $bDeleteAllowed , $bFeatureAllowed );
        if ( count( $aFinalPolls ) > 0 )
        {
            return $aFinalPolls;
        }
        return array();
    }

    public function toSingleArray( $aQuestions , $aOptions , $bDeleteAllowed = false , $bFeatureAllowed = false )
    {
        $aFinalPolls = $this->getStructuredArray( $aQuestions , $aOptions , $bDeleteAllowed , $bFeatureAllowed );
        if ( count( $aFinalPolls ) > 0 )
        {
            return current( $aFinalPolls );
        }
        return array();
    }

    private function getStructuredArray( $aQuestions , $aOptions , $bDeleteAllowed = false , $bFeatureAllowed = false )
    {
        $aFinalPolls = array();
        for ( $i = 0 , $count = count( $aQuestions ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'q_id' ] = $aQuestions[ $i ][ 'survey_question_id' ];
            $aItem[ 'question' ] = $aQuestions[ $i ][ 'survey_question' ];
            $aItem[ 'delete' ] = $bDeleteAllowed;
            $aItem[ 'feature' ] = $bFeatureAllowed;
            if ( $aQuestions[ $i ][ 'featured' ] === 'FEATURED' )
            {
                $aItem[ 'alreadyfeatured' ] = true;
            }
            else
            {
                $aItem[ 'alreadyfeatured' ] = false;
            }
            $aFinalPolls[ $aQuestions[ $i ][ 'survey_question_id' ] ] = $aItem;
        }

        $aFinalPollsFiles = array();
        for ( $i = 0 , $count = count( $aOptions ); $i < $count; $i++ )
        {
            $aItem = array();
            $aItem[ 'q_id' ] = $aOptions[ $i ][ 'survey_question_id' ];
            $aItem[ 'opt_id' ] = $aOptions[ $i ][ 'survey_option_id' ];
            $aItem[ 'option' ] = $aOptions[ $i ][ 'survey_option' ];
            $aItem[ 'votes' ] = $aOptions[ $i ][ 'votes' ];

            $aFinalPollsFiles[ $aOptions[ $i ][ 'survey_question_id' ] ][] = $aItem;
        }

        foreach ( $aFinalPollsFiles as $value )
        {
            $aFinalPolls[ $value[ 0 ][ 'q_id' ] ][ 'options' ] = $value;

            $iSum = 0;
            foreach ( $value as $option )
            {
                $iSum += $option[ 'votes' ];
            }
            $aFinalPolls[ $value[ 0 ][ 'q_id' ] ][ 'totalvotes' ] = $iSum;
        }

        return $aFinalPolls;
    }

    public function publish( $iPollId )
    {
        if ( $iPollId )
        {
            return $this->oPollQuestions->updateData( array( 'status' => 'PUBLISHED' ) , $iPollId );
        }
        return 0;
    }

    public function getFeaturedQuestion()
    {
        $iFeatured = $this->oPollQuestions->fetchRow( "featured='FEATURED'" );
        if ( !is_null( $iFeatured ) && !empty( $iFeatured ) && !empty( $iFeatured[ 'id' ] ) )
        {
            return ( int ) $iFeatured[ 'id' ];
        }
        return 0;
    }

    public function getRandomPublishedNonfeatureQuestion( $iUserId )
    {
        $aRandomFeatured = $this->oDb->fetchOne( self::QUERY_RANDOM_PUBLISHED_NON_FEATURE_POLL_NOT_VOTED , $iUserId );
        if ( $aRandomFeatured )
        {
            return ( int ) $aRandomFeatured;
        }
        return 0;
    }

    public function feature( $iPollId , $featured = 'NOT_FEATURED' )
    {
        if ( $iPollId )
        {
            return $this->oPollQuestions->updateData( array( 'featured' => $featured ) , $iPollId );
        }
        return 0;
    }

    public function checkIfAlreadyVoted( $iQuestionId , $iUserId )
    {
        $aHaveVoted = $this->oPollUsers->fetchRow( "b_survey_question_id=" . $iQuestionId . " AND sys_user_id=" . $iUserId );
        if ( !is_null( $aHaveVoted ) && !empty( $aHaveVoted ) && !empty( $aHaveVoted[ 'id' ] ) )
        {
            return true;
        }
        return false;
    }

}
