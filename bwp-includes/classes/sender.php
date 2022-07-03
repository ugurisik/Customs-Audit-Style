<?php
class sender
{
    private $host;
    private $port;
    private $user;
    private $pass;
    private $encryption;

    function __construct($host,$port,$user,$pass,$ssl) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
        $this->encryption = $ssl;
    }

    function mail($title,$reciverMail,$reciverName,$senderMail,$senderTitle,$mailBody){
        $transport = (new Swift_SmtpTransport("$this->host", $this->port, $this->encryption))
            ->setUsername($this->user)
            ->setPassword($this->pass)->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false)));
            
        $mailer = new Swift_Mailer($transport);

      
        

        $message = (new Swift_Message($title))
            ->setFrom([ $senderMail => $senderTitle])
            ->setTo([$reciverMail, $reciverMail => $reciverName])
            ->setBody($mailBody,"text/html");
        $result = $mailer->send($message);
    }
}
