<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    function sendEmail($recipientEmail, $recipientName, $subject, $body){
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;
            // $mail->SMTPAutoTLS = false;                                       
            $mail->Username   = 'l3782960@gmail.com';                     
            $mail->Password   = 'favxmitncpqpqyfc';                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = 587;                                   

            //Recipients
            $mail->setFrom('tirtabugar@example.com', 'Tirta Bugar');
            $mail->addAddress($recipientEmail, $recipientName); 
            $mail->addReplyTo('info-tirtabugar@example.com', 'Information');

            //Content
            $mail->isHTML(true);                               
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();

            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
?>