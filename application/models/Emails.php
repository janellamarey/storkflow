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
                b_email_queue
EOT;

    public function __construct()
    {
        $this->initObjects();
        $this->initDBObjects();
        $this->initMail();
    }

    private function initObjects()
    {
        $this->oUsers = new Users();
        $this->councilorsEmails = $this->oUsers->getActiveEmailAddresses( SiteConstants::$COUNCILOR );
        $this->superusersEmails = $this->oUsers->getActiveEmailAddresses( SiteConstants::$SUPERUSER );
        $this->superadminEmails = $this->oUsers->getActiveEmailAddresses( SiteConstants::$SUPERADMIN );
        $this->adminEmails = $this->oUsers->getActiveEmailAddresses( SiteConstants::$ADMIN );
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

    public function mailContact( $recipientSelection , $message , $fullname , $address , $contact , $emailadd , $company )
    {
        $recipients = $this->getContactRecipients( $recipientSelection );
        $designation = $this->getContactRecipientDesignation( $recipientSelection );

        $data = array(
                'from' => "Administrator" ,
                'subject' => "Bacoor City Council Notice: New message from Bacoor City Council website users." ,
                'message' => sprintf( SiteConstants::$MAIL_CONTACT , $designation , $message , $fullname , $address , $contact , $emailadd , $company ) ,
                'recipients' => implode( ',' , $recipients )
        );
        return $this->emailQueue->insertData( $data );
    }

    public function passwordChange( $recipients )
    {
        $data = array(
                'from' => "Administrator" ,
                'subject' => "Bacoor City Council Notice: Please change your password for security purposes." ,
                'message' => sprintf( SiteConstants::$PASSWORD_CHANGE ) ,
                'recipients' => implode( ',' , $recipients )
        );
        return $this->emailQueue->insertData( $data );
    }

    public function mailReset( $emailAddress , $username , $password )
    {
        $data = array(
                'from' => "Administrator" ,
                'subject' => "Bacoor City Council Notice: Your new password for www.bacoorcitycouncil.com" ,
                'message' => sprintf( SiteConstants::$MAIL_RESET , $username , $password ) ,
                'recipients' => $emailAddress
        );
        return $this->emailQueue->insertData( $data );
    }

    public function ordinanceStaged( $personalData , $roleId , $ordinance , $link )
    {
        $designation = $this->getDesignationText( $roleId );
        $shortDesignation = $this->getShortDesignationText( $roleId );
        $name = SiteConstants::createName( $personalData[ 'firstname' ] , $personalData[ 'lastname' ] , "" , "" );
        if ( $shortDesignation !== '' )
        {
            $name = $shortDesignation . ' ' . $name;
        }

        $subjectPattern = 'Bacoor City Council Notice: All Councilors have signed %s no. %s.';
        $subject = sprintf( $subjectPattern , strtoupper( $ordinance->type ) , $ordinance->id );
        $message = sprintf( SiteConstants::$ORDINANCE_STAGED , strtoupper( $ordinance->type ) , $ordinance->id , $ordinance->name , $link , $link , $designation , $name );
        $recipients = array_merge( $this->councilorsEmails , $this->superusersEmails , $this->superadminEmails );

        $data = array(
                'from' => $name ,
                'subject' => $subject ,
                'message' => $message ,
                'recipients' => implode( ',' , $recipients )
        );

        return $this->emailQueue->insertData( $data );
    }

    public function ordinanceApproved( $personalData , $roleId , $ordinance , $link )
    {
        $designation = $this->getDesignationText( $roleId );
        $shortDesignation = $this->getShortDesignationText( $roleId );
        $name = SiteConstants::createName( $personalData[ 'firstname' ] , $personalData[ 'lastname' ] , "" , "" );
        if ( $shortDesignation !== '' )
        {
            $name = $shortDesignation . ' ' . $name;
        }

        $subjectPattern = 'Bacoor City Council Notice: %s has signed %s no. %s.';
        $subject = sprintf( $subjectPattern , $name , strtoupper( $ordinance->type ) , $ordinance->id );

        $messagePattern = SiteConstants::$ORDINANCE_APPROVED;
        $message = sprintf( $messagePattern , $name , strtoupper( $ordinance->type ) , $ordinance->id , $ordinance->name , $link , $link , $designation , $name );

        $recipients = array_merge( $this->councilorsEmails , $this->superusersEmails , $this->superadminEmails );

        $data = array(
                'from' => $name ,
                'subject' => $subject ,
                'message' => $message ,
                'recipients' => implode( ',' , $recipients )
        );
        if ( !$this->emailQueue->insertData( $data ) )
        {
            return false;
        }
        return true;
    }

    public function userApproved( $sender , $recipient )
    {
        $senderName = SiteConstants::createName( $sender[ 'firstname' ] , $sender[ 'lastname' ] , "" , "" );
        $recipientName = SiteConstants::createName( $recipient[ 'firstname' ] , $recipient[ 'lastname' ] , "" , "" );

        $subjectPattern = 'Bacoor City Council Notice: %s has approved your registration.';
        $subject = sprintf( $subjectPattern , $senderName );

        $messagePattern = SiteConstants::$USER_APPROVED;
        $message = sprintf( $messagePattern , $recipientName , $senderName , $senderName );

        $recipients = array( $recipient[ 'email_add' ] );

        $data = array(
                'from' => $senderName ,
                'subject' => $subject ,
                'message' => $message ,
                'recipients' => implode( ',' , $recipients )
        );

        if ( !$this->emailQueue->insertData( $data ) )
        {
            return false;
        }
        return true;
    }
    
    public function userDenied( $sender , $recipient )
    {
        $senderName = SiteConstants::createName( $sender[ 'firstname' ] , $sender[ 'lastname' ] , "" , "" );
        $recipientName = SiteConstants::createName( $recipient[ 'firstname' ] , $recipient[ 'lastname' ] , "" , "" );

        $subjectPattern = 'Bacoor City Council Notice: %s has denied your registration.';
        $subject = sprintf( $subjectPattern , $senderName );

        $messagePattern = SiteConstants::$USER_DENIED;
        $message = sprintf( $messagePattern , $recipientName , $senderName , $senderName );

        $recipients = array( $recipient[ 'email_add' ] );

        $data = array(
                'from' => $senderName ,
                'subject' => $subject ,
                'message' => $message ,
                'recipients' => implode( ',' , $recipients )
        );

        if ( !$this->emailQueue->insertData( $data ) )
        {
            return false;
        }
        return true;
    }

    public function ordinanceChanged( $personalData , $accountData , $ordinance , $link )
    {
        $shortDesignation = $this->getShortDesignationText( ( int ) $accountData[ 'sys_role_id' ] );
        $name = SiteConstants::createName( $personalData[ 'firstname' ] , $personalData[ 'lastname' ] , "" , "" );
        if ( $shortDesignation !== '' )
        {
            $name = $shortDesignation . ' ' . $name;
        }

        $subjectPattern = 'Bacoor City Council Notice: %s has edited %s no. %s';
        $subject = sprintf( $subjectPattern , $name , strtoupper( $ordinance->type ) , $ordinance->id );

        $messagePattern = SiteConstants::$ORDINANCE_CHANGED;
        $message = sprintf( $messagePattern , $name , strtoupper( $ordinance->type ) , $ordinance->id , $ordinance->name , $link , $link , $name );

        $recipients = array_merge( $this->councilorsEmails , $this->superusersEmails , $this->superadminEmails );

        $data = array(
                'from' => $name ,
                'subject' => $subject ,
                'message' => $message ,
                'recipients' => implode( ',' , $recipients )
        );

        if ( !$this->emailQueue->insertData( $data ) )
        {
            return false;
        }
        return true;
    }

    public function ordinanceCreated( $personal_data , $account_data , $ordinance , $link )
    {
        $shortDesignation = $this->getShortDesignationText( ( int ) $account_data[ 'sys_role_id' ] );
        $name = SiteConstants::createName( $personal_data[ 'firstname' ] , $personal_data[ 'lastname' ] , "" , "" );
        if ( $shortDesignation !== '' )
        {
            $name = $shortDesignation . ' ' . $name;
        }
        
        $subjectPattern = 'Bacoor City Council Notice: %s has created %s no. %s';
        $subject = sprintf( $subjectPattern , $name , strtoupper( $ordinance->type ) , $ordinance->id );

        $messagePattern = SiteConstants::$ORDINANCE_CREATED;
        $message = sprintf( $messagePattern , $name , strtoupper( $ordinance->type ) , $ordinance->id , $ordinance->name , $link , $link , $name );
        $recipients = array_merge( $this->councilorsEmails , $this->superusersEmails , $this->superadminEmails );
        $data = array(
                'from' => $name ,
                'subject' => $subject ,
                'message' => $message ,
                'recipients' => implode( ',' , $recipients )
        );
        if ( !$this->emailQueue->insertData( $data ) )
        {
            return false;
        }
        return true;
    }

    private function getDesignationText( $roleId )
    {
        $designation = "";
        if ( SiteConstants::$ADMIN_ID === $roleId )
        {
            $designation = "Admin";
        }
        else if ( SiteConstants::$COUNCILOR_ID === $roleId )
        {
            $designation = "Councilor";
        }
        else if ( SiteConstants::$SUPERUSER_ID === $roleId )
        {
            $designation = "Attorney";
        }
        else if ( SiteConstants::$SUPERADMIN_ID === $roleId )
        {
            $designation = "Vice Mayor";
        }
        return $designation;
    }

    private function getShortDesignationText( $roleId )
    {
        $designation = "";
        if ( SiteConstants::$SUPERUSER_ID === $roleId )
        {
            $designation = "Atty.";
        }
        return $designation;
    }

    private function getContactRecipients( $recipientSelection )
    {
        $recipients = array();
        if ( $recipientSelection === '1' )
        {
            $recipients = $this->superadminEmails;
        }
        else if ( $recipientSelection === '2' )
        {
            $recipients = $this->superusersEmails;
        }
        else if ( $recipientSelection === '3' )
        {
            $recipients = $this->councilorsEmails;
        }
        return $recipients;
    }

    private function getContactRecipientDesignation( $recipientSelection )
    {
        $designation = "User";
        if ( $recipientSelection === '1' )
        {
            $designation = "Vice Mayor";
        }
        else if ( $recipientSelection === '2' )
        {
            $designation = "Attorney";
        }
        else if ( $recipientSelection === '3' )
        {
            $designation = "All Councilors";
        }
        return $designation;
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
