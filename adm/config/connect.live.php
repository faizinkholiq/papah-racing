<?php
define('N',$_SERVER['SERVER_NAME']);
define('A',$_SERVER['SERVER_ADDR']);
define('REQURI', $_SERVER['REQUEST_URI']);
define('ENV', 'Live');
define('ROOT',dirname(FILE));
define('PROJECT', 'papah-racing');
define('SITEURL','https://'.N);
define('PHOTO',$_SERVER['DOCUMENT_ROOT'].'/p');
define('TEMA',SITEURL.'/tema');

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