<?php 
    header("Access-Control-Allow-Origin: http://localhost:5173"); // your exact frontend origin, not *
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST, OPTIONS");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0); 
    }
?>