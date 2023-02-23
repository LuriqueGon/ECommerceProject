<?php

namespace App\Models;
use MF\Model\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    Class Mailer extends Model
    {
        
        public static function sendMail($toAddress, $toName ,$subject, $msg, $mailer = "suport")
        {
            

            $email = ($mailer == "suport" ? "suporteecommercelurique@gmail.com" : '');
            $senha = ($mailer == "suport" ? "wlkgfnaurwhpyzkx" : '');

            var_dump($email, $senha);


            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = false;
                $mail->isSMTP();   
                $mail->Debugoutput =  'html';
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );                                         
                $mail->Host       = "smtp.gmail.com";
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = $email;                     
                $mail->Password   = $senha;                               
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->CharSet = "UTF-8";

                $mail->setFrom($email, 'Suporte Ecommerce');
                if (!$mail->addReplyTo($email)) {
                    echo 'Invalid email address';
                    exit;
                }
                $mail->addAddress($toAddress, $toName. " - ". $subject);
            
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $msg;
            
                $mail->send();
                
                echo "Enviado";
                
            } catch (Exception $e) {
                echo $e->getMessage();
            }



        }
        
    }
