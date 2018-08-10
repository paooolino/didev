<?php
namespace WebEngine\Plugin;

class DiscosForms {
  private $_machine;
  
  private $IAM_USER;
  private $SMTP_USER;
  private $SMTP_PASS;
  
  public function __construct($machine) {
    $this->_machine = $machine;
    
    $this->_IAM_USER = getenv("AWS_IAM_USER");
    $this->_SMTP_USER = getenv("AWS_SMTP_USER");
    $this->_SMTP_PASS = getenv("AWS_SMTP_PASSWORD");
  }
  
  private function dataToHtml($data) {
    $html = '';
    foreach ($data as $k => $v) {
      $html .= '<p><i>' . $k . '</i> <b>' . $v . '</b></p>';
    }
    return $html;
  }
  
  public function send($title, $data) {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
      //Server settings
      
      //$mail->SMTPDebug = 2;                                 
      $mail->isSMTP();                                      
      $mail->setFrom('info@discotecheitalia.it', 'Discoteche Italia');
      $mail->addAddress('paooolino@gmail.com');       
      $mail->Username = $this->_SMTP_USER;                 // SMTP username
      $mail->Password = $this->_SMTP_PASS;                         // SMTP password
      $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
      
      // The subject line of the email
      $mail->Subject = '[richiesta inviata dal sito] ' . $title;

      // The HTML-formatted body of the email
      $html = '';
      $html .= '<h1>' . $title . '</h1>';
      foreach ($data as $k => $v) {
        $html .= '<p><i>' . $k . '</i> <b> ' . $v . '</b></p>';
      }
      $mail->Body = $html;
      
      $mail->SMTPAuth = true;     

      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to
      
      $mail->isHTML(true);     
      
      $mail->AltBody = strip_tags($html);
      //Recipients
      
      //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
                    // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      //Content

      $mail->send();
      echo 'Message has been sent';
    } catch (Exception $e) {
      echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
  }
}