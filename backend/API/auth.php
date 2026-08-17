<?php 
    header("Access-Control-Allow-Origin: http://localhost:5173"); // your exact frontend origin, not *
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST, OPTIONS");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0); 
    }

    date_default_timezone_set('Europe/Paris');
    header("Content-Type: application/json");
    include_once 'connect.php';

    $data = json_decode(file_get_contents("php://input"), true);
    $signup_username = $data['username'] ?? '';
    $signup_email = filter_var($data['email']  ?? '' , FILTER_VALIDATE_EMAIL) ;
    $signup_password = $data['password'] ?? '';

    if($signup_username && $signup_email && $signup_password){
        session_start();
        if(signup($db, $signup_username, $signup_email, $signup_password)){
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Account Created Successfully !"]);
        }else{
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Singup Failed"]);

        }
    }


    function signup($db, $username, $email, $password){
        try{

            $password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(32));
            $token_expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            
            $sql = "INSERT INTO users 
            (username, email, password, role, verification_token, token_expires_at)
            VALUES 
            (:username, :email, :password, :role, :verify_token, :token_time )";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':role', "user");
            $stmt->bindValue(':verify_token', $token);
            $stmt->bindValue(':token_time', $token_expires);
            $stmt->execute();

            return true;

        }catch(PDOException $e){
            return false;

        }
    }
?>