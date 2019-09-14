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
  
  public function send_to_user($to, $name, $number="338.45.13.917", $body="") {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    
    if ($body != "") {
      $html = str_replace("{{NOME}}", $nome, $body);
    } else {
      $html = '
Ciao ' . $name . ',<br><br>
grazie per averci contattato.<br><br>
Abbiamo ricevuto la tua richiesta. A breve verrai ricontattato dal nostro team.<br><br>
Se la richiesta è urgente, contattaci telefonicamente o su WhatsApp al numero ' . $number . '<br><br>
      ';
    }
    
    $firma = '
<div style="display: table; margin: 25px 0; text-size-adjust: none !important; -ms-text-size-adjust: none !important; -webkit-text-size-adjust: none !important;">
	<div style="display: table-row;">
		<div style="display: table-cell; width: 110px; border-right: 1px solid #ebebec;vertical-align: middle;">
			<p style="font-family: Helvetica,Arial,sans-serif; font-size: 12px; line-height: 18px; margin-bottom: 10px;">
				<a style="text-decoration: none;" href="http://www.discotecheitalia.it" title="Vai al sito">
					<img src="http://www.albertoragnoli.it/img/db.png" height="54" width="82" alt="Discoteche Italia" border="0" style="margin-left: 7px">
				</a>
			</p>
		</div>
		<div style="display: table-cell;vertical-align: middle; padding: 0 0 0 20px">
			<p style="font-family: Helvetica,Arial,sans-serif; font-size: 14px; line-height: 15px; text-transform: uppercase;  margin:0 0  10px 0; ">
				DISCOTECHE ITALIA
				<br /> <span style="color:#818181; font-size: 11px; text-transform: none; display: inline;letter-spacing: 0.3px">La miglior vetrina per locali ed eventi</span>
			</p>
			<p style="font-family: Helvetica,Arial,sans-serif; font-size: 12px; line-height: 17px;  margin:0 0  0px 0; ">
				Via Padana Superiore 173/A, 25035 Ospitaletto - Brescia.<br />	
				<strong>Email</strong> <a href="mailto:info@discotecheitalia.it" style="color: #ffaf00 !important; text-decoration: none !important;  display: inline;">info@discotecheitalia.it</a><br />
				<strong>Website</strong> <a href="http://www.discotecheitalia.it" title="vai al sito" style="color: #ffaf00 !important; text-decoration: none !important;display: inline;">www.discotecheitalia.it</a>
			</p>
			
		</div>
	</div>
</div>
    ';
    try {
      $mail->isSMTP();                                      
      $mail->setFrom('info@discotecheitalia.it', 'Discoteche Italia');
      $mail->AddAddress($to);
      
      $mail->Username = $this->_SMTP_USER;
      $mail->Password = $this->_SMTP_PASS;
      $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
      
      $mail->Subject = "[discotecheitalia] Abbiamo ricevuto la tua richiesta.";
      $mail->Body = $html . $firma;
      
      $mail->SMTPAuth = true;     
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to
      $mail->isHTML(true);     
      
      $mail->AltBody = strip_tags($html);
      $mail->send();
    } catch (Exception $e) {
      //
    }
  }
  
  public function send($title, $data, $bodyresp="") {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
      //Server settings
      
      //$mail->SMTPDebug = 2;                                 
      $mail->isSMTP();                                      
      $mail->setFrom('info@discotecheitalia.it', 'Discoteche Italia');
      
      $addresses = explode(',', $this->_MAIL_TO);
      foreach ($addresses as $address) {
        if ($address != "") {
          $mail->AddAddress($address);
        }
      }

      $addresses = explode(',', $this->_MAIL_BCC);
      foreach ($addresses as $address) {
        if ($address != "") {
          $mail->AddBCC($address);
        }
      }

      $mail->Username = $this->_SMTP_USER;                 // SMTP username
      $mail->Password = $this->_SMTP_PASS;                         // SMTP password
      $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
      
      // The subject line of the email
      $site = isset($data["site_title"]) ? " " . $data["site_title"] : "discotecheitalia";
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
    
    if (isset($data["email"]) && isset($data["name"]))
      $this->send_to_user($data["email"], $data["name"], null, $bodyresp);
  }
}