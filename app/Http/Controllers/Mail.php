<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
use App\User;


class Mail
{
    public static function send($recipient, $subject, $body)
    {

        $mail = new PHPMailer();
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'Mailer Error' => "Invalid recipient's email format"
            ]);
          }
        try {
            // Settings
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';

            $mail->Host       =  env("MAIL_HOST"); // SMTP server example
            $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
            $mail->SMTPAuth   = true;          // enable SMTP authentication
            $mail->Port       = env("MAIL_PORT");                    // set the SMTP port for the  server
            $mail->Username   = env("MAIL_USERNAME"); // SMTP account username example
            $mail->Password   = env("MAIL_PASSWORD");        // SMTP account password example

            $mail->setFrom('updates@app-ksm.legnica.pl', 'Aplikacja KSM DL');

            $mail->addAddress($recipient);
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body.'<br><br>Pozdrawiamy,<br>Zespół KSM DL<br><a href="app-ksm.legnica.pl">app-ksm.legnica.pl</a>';

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

    public function sendMail(Request $request)
    {
        return Mail::send($request->get('recipient'), $request->get('subject'), $request->get('body'));
    }

    public static function sendById($id, $subject, $body){
        $user = User::find($id);
        return Mail::send($user->email, $subject, $body);
    }
    // public static function sendById(Request $request){
    //     $user = User::find($request->get('id'));
    //     return Mail::send($user->email, $request->get('subject'), $request->get('body'));
    // }
}
?>