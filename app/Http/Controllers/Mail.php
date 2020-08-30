<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;


class Mail
{
    public function sendMail(Request $request)
    {

        $mail = new PHPMailer();
        if (!filter_var($request->get('recipient'), FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'Mailer Error' => "Invalid recipient's email format"
            ]);
          }
        try {
            // Settings
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';

            $mail->Host       = "marsz.home.pl"; // SMTP server example
            $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
            $mail->SMTPAuth   = true;          // enable SMTP authentication
            $mail->Port       = 587;                    // set the SMTP port for the  server
            $mail->Username   = "app@ksm.legnica.pl"; // SMTP account username example
            $mail->Password   = "kostka13";        // SMTP account password example

            $mail->setFrom('app@ksm.legnica.pl', 'Aplikacja KSM DL');

            $mail->addAddress($request->get('recipient'));
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $request->get('subject');
            $mail->Body    = $request->get('body');
            $mail->AltBody =  $request->get('body');

            $mail->send();
            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'Mailer Error' => $mail->ErrorInfo
            ]);
        }
    }
}
?>