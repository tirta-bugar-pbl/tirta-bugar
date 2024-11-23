<?php 
    $host = 'localhost';
    $name = 'nurwahid';
    $pwd = '123';
    $db = 'tb_db';
    $port = '5432';
    
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db;user=$name;password=$pwd");

    // if (!$conn) {
    //     echo 'Could not connect: '. pg_last_error();
    // } else {
    //     echo 'Connected';
    // }
?>