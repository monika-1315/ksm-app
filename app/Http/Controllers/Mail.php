<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
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

            $mail->Host       =  env("MAIL_HOST"); // SMTP server
            $mail->SMTPDebug  = 0;                     
            $mail->SMTPAuth   = true;          // enable SMTP authentication
            $mail->Port       = env("MAIL_PORT");                    // set the SMTP port for the  server
            $mail->Username   = env("MAIL_USERNAME"); // SMTP account username 
            $mail->Password   = env("MAIL_PASSWORD");        // SMTP account password 

            $mail->setFrom('app@ksm.legnica.pl', 'Aplikacja KSM DL');

            $mail->addAddress($recipient);
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body.'<br><br>Pozdrawiamy,<br>Zespół KSM DL<br><a href="app.ksm.legnica.pl">app.ksm.legnica.pl</a>';

            $success=$mail->send();
            return response()->json([
                'success' => $success
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'Mailer Error' => $mail->ErrorInfo
            ]);
        }
    }

    public function sendRegisterMail(Request $request)
    {
        $code=$request->get('code')-9999;
        return Mail::send($request->get('recipient'), "Potwierdź adres email - aplikacja KSM DL", 
        "Witaj ".$request->get('name')."!<br> Próbujesz się właśnie zarejestrować do aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej. "
        ."Twój unikalny kod aktywacyjny to: <br><b>".$code."</b>");
    }

    public static function sendById($id, $subject, $body){
        $user = User::find($id);
        return Mail::send($user->email, $subject, $body);
    }
    
}
?>