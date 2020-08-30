<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class Mail {
    public function sendMail(){
   
// $name = "Undefined name";

// if(isset($_POST['name'])){
//     $name = $_POST['name'];
// }

// $message = "<p>Hi!</p>";
// $message .= "<p>Wazaaaaa $name</p>";

// $to_email = 'moniusiar@gmail.com';
// $subject = 'Mail subject';
// $headers[] = 'MIME-Version: 1.0';
// $headers[] = 'Content-type: text/html; charset=UTF-8';
// $headers[] = 'From: <app@ksm.legnica.pl>';

// mail($to_email, $subject, $message, implode("\r\n", $headers));


$mail = new PHPMailer();
try {
// Settings
    $mail->isSMTP();
    // $mail->CharSet = 'UTF-8';

    $mail->Host       = "marsz.home.pl"; // SMTP server example
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;          // enable SMTP authentication
    $mail->Port       = 587;                    // set the SMTP port for the  server
    $mail->Username   = "app@ksm.legnica.pl"; // SMTP account username example
    $mail->Password   = "kostka13";        // SMTP account password example

    $mail->setFrom('app@ksm.legnica.pl', 'Mailer');
    $mail->addAddress('moniusiar@gmail.com');    
// Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
}
?>