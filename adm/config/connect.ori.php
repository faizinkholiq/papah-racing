<?php
$dbhost = 'localhost';
$dbuser = 'u1039423_andre';
$dbpass = 'Papahracing2000';
$dbname = 'u1039423_papahracing';
$db_fc = "mysql:dbname=$dbname;host=$dbhost";
try {
    $db = new PDO($db_fc, $dbuser, $dbpass);
} catch (PDOException $e) {
    echo $e->getMessage(), ' in your website. <strong>Connection Failed</strong>';
    die();
}
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
