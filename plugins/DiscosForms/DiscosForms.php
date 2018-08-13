<?php
namespace WebEngine\Plugin;

class DiscosForms {
  private $_machine;
  
  private $IAM_USER;
  private $SMTP_USER;
  private $SMTP_PASS;
  
  private $fieldmapping;
  
  public function __construct($machine) {
    $this->_machine = $machine;
    
    $this->_IAM_USER = getenv("AWS_IAM_USER");
    $this->_SMTP_USER = getenv("AWS_SMTP_USER");
    $this->_SMTP_PASS = getenv("AWS_SMTP_PASSWORD");
    $this->_MAIL_TO = getenv("MAIL_TO");
    $this->_MAIL_BCC = getenv("MAIL_BCC");
    
    $this->fieldmapping = [
      "name" => "Nome",
      "surname" => "Cognome",
      "email" => "E-mail",
      "info" => "Testo richiesta",
      "newsletter" => "Iscrizione newsletter",
      "site_title" => "Sito",
      "location" => "Locale",
      "day" => "Giorno",
      "reservation" => "Tipo prenotazione",
      "recontact" => "Tipo contatto",
      "phone" => "Telefono",
      "typo" => "Categoria locale",
      "denomination" => "Denominazione",
      "address_way" => "Indirizzo",
      "address_number" => "Civico",
      "address_zip" => "CAP",
      "address_city" => "Città",
      "address_province" => "Provincia",
      "openings" => "Giorni di apertura",
      "type" => "Tipologia"
    ];
  }
  
  private function dataToHtml($data) {
    $html = '';
    foreach ($data as $k => $v) {
      if (isset($this->fieldmapping[$k])) {
        if (is_array($v)) {
          $v = "<br>" . implode("<br>", $v);
        } else {
          $v = nl2br($v);
          if ($v == "0" && $k == "newsletter") { $v = "No"; }
          if ($v == "1" && $k == "newsletter") { $v = "Sì"; }
        }
        $html .= '<p><i>' . $this->fieldmapping[$k] . '</i> <b>' . $v . '</b></p>';
      }
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
      
      $addresses = explode(',', $this->_MAIL_TO);
      foreach ($addresses as $address) {
        $email->AddAddress($address);
      }

      $addresses = explode(',', $this->_MAIL_BCC);
      foreach ($addresses as $address) {
        $email->AddBCC($address);
      }

      $mail->Username = $this->_SMTP_USER;                 // SMTP username
      $mail->Password = $this->_SMTP_PASS;                         // SMTP password
      $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
      
      // The subject line of the email
      $site = isset($data["site_title"]) ? " " . $data["site_title"] : "";
      $mail->Subject = '[richiesta inviata dal sito' . $site . '] ' . $title;

      // The HTML-formatted body of the email
      $html = '';
      $html .= '<h1>' . $title . '</h1>';
      $html .= $this->dataToHtml($data);

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