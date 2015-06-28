<?php

Zend_Loader::loadClass( 'SiteConstants' );
Zend_Loader::loadClass( 'Users' );
Zend_Loader::loadClass( 'DB_EmailAudit' );
Zend_Loader::loadClass( 'DB_EmailQueue' );

class Emails
{

    const QUERY_EMAILS_FROM_QUEUE = <<<EOT
            SELECT
                `id`, `from`, `subject`, `message`, `recipients`
            FROM
                sys_email_queue
EOT;

    public function __construct()
    {
        $this->initObjects();
        $this->initDBObjects();
        $this->initMail();
    }

    private function initObjects()
    {
        $this->users = new Users();
        $this->adminEmails = $this->users->getActiveEmailAddresses( SiteConstants::$ADMIN_USER );
        $this->smEmails = $this->users->getActiveEmailAddresses( SiteConstants::$SM_USER );
        $this->memberEmails = $this->users->getActiveEmailAddresses( SiteConstants::$MEMBER_USER );
    }

    private function initDBObjects()
    {
        $this->db = Zend_Registry::get( 'db' );
        $this->emailAudit = new DB_EmailAudit();
        $this->emailQueue = new DB_EmailQueue();
    }

    private function initMail()
    {
        $config = Zend_Registry::get( 'config' );
        $this->oEmailConfig = array(
                'auth' => $config->email->params->auth ,
                'username' => $config->email->params->username ,
                'password' => $config->email->params->password ,
                'ssl' => $config->email->params->ssl ,
                'port' => $config->email->params->port ,
                'domain' => $config->email->params->domain
        );
        try
        {
            $mailTransport = new Zend_Mail_Transport_Smtp( $config->email->params->smtpserver , $this->oEmailConfig );
            Zend_Mail::setDefaultTransport( $mailTransport );
        }
        catch ( Zend_Exception $e )
        {
            var_dump( $e );
        }
    }

    public function mailReset( $emailAddress , $username , $password )
    {
        $data = array(
                'from' => "Administrator" ,
                'subject' => "Your new password for www.storkflow.xxx.com" ,
                'message' => sprintf( SiteConstants::$MAIL_RESET , $username , $password ) ,
                'recipients' => $emailAddress
        );
        return $this->emailQueue->insertData( $data );
    }


    private function addRecipients( $zendMail , $emails )
    {
        $emailsArray = explode( ',' , $emails );
        $this->addTo( $zendMail , $emailsArray );
    }

    private function addTo( $zendMail , $emailsArray )
    {
        foreach ( $emailsArray as $email )
        {
            $zendMail->addTo( $email );
        }
    }

    private function addCC( $zendMail , $emailsArray )
    {
        foreach ( $emailsArray as $email )
        {
            $zendMail->addCC( $email );
        }
    }

    public function sendMails()
    {
        $emailsToSend = $this->db->fetchAll( self::QUERY_EMAILS_FROM_QUEUE );
        foreach ( $emailsToSend as $email )
        {
            if ( $this->sendMail( $email ) )
            {
                $email[ 'sent_datetime' ] = date( 'Y/m/d H:i:s' );
                $this->emailQueue->delete( array( 'id=?' => $email[ 'id' ] ) );
                $filtered = $this->filter( $email );
                $this->emailAudit->insertData( $filtered );
            }
        }
    }

    private function filter( $email )
    {
        $filterOutKeys = array( 'id' );
        return array_diff_key( $email , array_flip( $filterOutKeys ) );
    }

    private function sendMail( $data )
    {
        $zendMail = new Zend_Mail();
        $zendMail->setSubject( $data[ 'subject' ] );

        $zendMail->setBodyHtml( nl2br( $data[ 'message' ] ) );

        $from = $this->oEmailConfig[ 'username' ];
        if ( $this->oEmailConfig[ 'domain' ] )
        {
            $from = $this->oEmailConfig[ 'username' ] . '@' . $this->oEmailConfig[ 'domain' ];
        }
        $zendMail->setFrom( $from , $data[ 'from' ] );

        $this->addRecipients( $zendMail , $data[ 'recipients' ] );

        $this->addCC( $zendMail , $this->adminEmails );
        try
        {
            $zendMail->send();
        }
        catch ( Exception $e )
        {
            var_dump( $e );
            return false;
        }

        return true;
    }

}
