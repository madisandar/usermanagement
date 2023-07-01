<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "sampledbthree";

try{
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo "Connected Successfully ";
}catch(PDOException $e){
   echo "Failed to connect ".$e->getMessage();
}



?>