<?php
namespace Mpm\Contrib\PhpMailer;
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Mpm\Utils\Utils;
//Load Composer's autoloader
require_once "PHPMailer.php";
require_once "SMTP.php";
require_once "Exception.php";

//Create an instance; passing `true` enables exceptions
class Mailer {
  public static function sendmail($metadata){
    global $DEFAULT_FROM_EMAIL;
    global $EMAIL_HOST;
    global $EMAIL_PORT;
    global $EMAIL_HOST_USER;
    global $EMAIL_HOST_PASSWORD;
    global $EMAIL_SECURE_PROTOCOL;
    
    $mail = new PHPMailer(true);
    try {
        //Server settings
       // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                 //Send using SMTP
        $mail->Host       = $EMAIL_HOST;        //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                      //Enable SMTP authentication
        $mail->Username   = $EMAIL_HOST_USER;                 //SMTP username
        $mail->Password   = $EMAIL_HOST_PASSWORD; //SMTP password
        $mail->SMTPSecure = $EMAIL_SECURE_PROTOCOL;//PHPMailer::ENCRYPTION_SMTPS;;     PHPMailer::ENCRYPTION_STARTTLS       //Enable implicit TLS encryption
        $mail->Port       = $EMAIL_PORT;//465;                                   //TCP port to connect to; use 465 if you have set ``
    
        //Recipients
        $mail->setFrom($DEFAULT_FROM_EMAIL);
        
        $mail->addAddress($metadata['to']['email'], Utils::get_safe($metadata['to']['name']));     //Add a recipient  &    Name is optional
        $mail->addReplyTo(Utils::get_safe($metadata['reply']['email']),Utils::get_safe($metadata['reply']['info']) );
        $mail->addCC(Utils::get_safe($metadata['cc']));
        $mail->addBCC(Utils::get_safe($metadata['bcc']));
    
        foreach(Utils::get_safe($metadata['attachments'],[]) as $attachment){
          $mail->addAttachment($attachment);         //Add attachments
        }

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = Utils::get_safe($metadata['subject']);
        $mail->Body    = Utils::get_safe($metadata['body']);
        $mail->AltBody = Utils::get_safe($metadata['altbody']);
    
        $mail->send();
        return 'Message has been sent';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
}