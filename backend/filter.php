<?php 

include "connect.php"; 


$table = "users" ; 
$username = "hello" ;

$stmt = $db->prepare("SELECT * FROM " . $table . " WHERE username = ? " );
$stmt->bindValue(1, $username);
$stmt->execute();

$fetch = $stmt->fetch(PDO::FETCH_ASSOC);
if ($fetch == false ){
    echo "No data found !";
}
while($row = $fetch){
    $id = htmlentities($row['id']);
    $username= htmlentities($row['username']);
    $email= htmlentities($row['email']);
    $pwd= htmlentities($row['password']);
    $role= htmlentities($row['role']);
    $date= htmlentities($row['created_at']);

    echo "> ID : " . $id . "<br>> Username :" . $username . "<br>> Email : " . $email ."<br>> Pwd : " . $pwd ."<br>> Role : " . $role ."<br>> Date : " . $date ;

    break;
}



?>