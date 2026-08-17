<?php

    try{
        $db = new PDO('mysql:host=localhost;dbname=jobboard;charset=utf8;', 'root', '3012');
        return $db;
    }catch (Exception $e) {
        echo "An error has occured with DB!";
    }

?>