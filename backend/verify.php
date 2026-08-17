<?php 

    include "connect.php";



    function verify($db){

        $table = "users";
        $token = $_GET['token'];

        $sql_if_verify = "SELECT is_verified FROM $table WHERE verification_token= :token";
        $stmt = $db->prepare($sql_if_verify);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

        if($fetch['is_verified'] == 1){
            echo '<h3 class="text-center">Account is already verified</h3>';
            return true;
        }else{
            $sql_check = "SELECT token_expires_at FROM $table WHERE verification_token= :token AND token_expires_at > NOW() ";
    
            $stmt = $db->prepare($sql_check);
            $stmt->bindValue(':token', $token);
            $stmt->execute();
            $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if(!empty($fetch)){
                $sql_verify = "UPDATE $table SET is_verified=1 WHERE verification_token = :token";
                $stmt = $db->prepare($sql_verify);
                $stmt->bindValue(':token', $token);
                $stmt->execute();

                $sql_get_info= "SELECT id, username , role FROM $table WHERE verification_token = :token";
                $stmt = $db->prepare($sql_get_info);
                $stmt->bindValue(':token', $token);
                $stmt->execute();
                $fetch_session = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['user_id'] = $fetch_session['id'];
                $_SESSION['username'] = $fetch_session['username'];
                $_SESSION['role'] = $fetch_session['role'];

                header("Location: /profile");
                echo '<h3 class="text-center">Account has been verified</h3>';
                return true;
    
            }else{
                echo '<h3 class="text-center">Token has been expired</h3>';
                return false;
            }

        }
    }


    function resend_token($db){
        try{
            $table = "users";
            $token = $_GET['token'];
            $newtoken = bin2hex(random_bytes(32));
            date_default_timezone_set('Europe/Madrid');
            $token_date = date('Y-m-d H:i:s', strtotime("+10 minutes") );

            $sql = "UPDATE $table SET verification_token = :newtoken, token_expires_at = :token_date WHERE verification_token = :token";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':newtoken', $newtoken);
            $stmt->bindValue(':token_date', $token_date);
            $stmt->bindValue(':token', $token);
            $stmt->execute();

            echo "New email has been sent";
            return true;


        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }




?>