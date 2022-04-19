<?php
define('N',$_SERVER['SERVER_NAME']);
define('A',$_SERVER['SERVER_ADDR']);
define('REQURI', $_SERVER['REQUEST_URI']);
define('ENV', 'Development');
define('ROOT',dirname(__FILE__));
define('PHOTO',ROOT.'/p');
define('SITEURL','http://'.N.'/papah-racing');
define('TEMA',SITEURL.'/tema');

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'papah_racing';
$db_fc = "mysql:dbname=$dbname;host=$dbhost";
try {
    $db = new PDO($db_fc, $dbuser, $dbpass);
} catch (PDOException $e) {
    echo $e->getMessage(), ' in your website. <strong>Connection Failed</strong>';
    die();
}
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
