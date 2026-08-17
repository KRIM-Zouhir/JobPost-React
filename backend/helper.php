<?php 
    require __DIR__ . '/../vendor/autoload.php';


    use PHPMailer\PHPMailer\PHPMailer;

    function calcul($db , $table , $rows_per_page){
        try{
            $sql = "SELECT * FROM  $table";
            $records = $db->query($sql);
            $nr_rows = $records->rowCount();

            $pages = ceil($nr_rows/$rows_per_page);

            return $pages;

        } catch(PDOException $e){
            echo $e;
        }
    }

    function calcul_offers($db , $userid, $rows_per_page){
        try{
            $sql = "SELECT * FROM applications WHERE user_id =:userid";
            $records = $db->prepare($sql);
            $records->bindValue(':userid',$userid);
            $records->execute();
            $nr_rows = $records->rowCount();

            $pages = ceil($nr_rows/$rows_per_page);

            return $pages;

        } catch(PDOException $e){
            echo $e;
        }
    }

    function send_verify_mail($email_user , $token){
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'live.smtp.mailtrap.io';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'smtp@mailtrap.io';                     //SMTP username
            $mail->Password   = 'f0aacf576569802455dfe146c101f61f';                               //SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('hello@demomailtrap.com', 'JobPost');
            $mail->addAddress($email_user);     //Add a recipient   

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Verify your email - JobPost';

            $mail->Body = "
                <h2>Welcome to JobPost!</h2>
                <p>Thanks for signing up. Please verify your email address to activate your account.</p>
                <p><a href='http://localhost:8000/verified?token={$token}'>Click here to verify your email</a></p>
                <p>This link will expire in 10 minutes.</p>
                <p>If you didn't sign up for JobPost, you can safely ignore this email.</p>
            ";

            $mail->AltBody = "Welcome to JobPost!\n\n"
                . "Thanks for signing up. Please verify your email address to activate your account.\n\n"
                . "Verify your email using this link: https://yoursite.com/verify.php?token={$token}\n\n"
                . "This link will expire in 30 minutes.\n\n"
                . "If you didn't sign up for JobPost, you can safely ignore this email.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

    }


    function send_resend_pwd($email_user , $token){
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'live.smtp.mailtrap.io';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'smtp@mailtrap.io';                     //SMTP username
            $mail->Password   = 'f0aacf576569802455dfe146c101f61f';                               //SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('hello@demomailtrap.com', 'JobPost');
            $mail->addAddress($email_user);     //Add a recipient   

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Reset your password - JobPost';


            $mail->Body = "
                <h2>Welcome to JobPost!</h2>
                <p>Thanks for signing up. Please verify your email address to activate your account.</p>
                <p><a href='http://localhost:8000/reset-token?token={$token}'>Click here to verify your email</a></p>
                <p>This link will expire in 10 minutes.</p>
                <p>If you didn't sign up for JobPost, you can safely ignore this email.</p>
            ";

            $mail->AltBody = "Welcome to JobPost!\n\n"
                . "Thanks for signing up. Please verify your email address to activate your account.\n\n"
                . "Verify your email using this link: https://yoursite.com/verify.php?token={$token}\n\n"
                . "This link will expire in 30 minutes.\n\n"
                . "If you didn't sign up for JobPost, you can safely ignore this email.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

    }
?>