<?php
date_default_timezone_set('UTC');
$host = '[your MySQL Host]';
$username = '[MySQL Username]';
$password = '[MySQL Password]';
$db_name = '[Database]';

//Establishes the connection, note that I'm using SSL with a specific certificate, you'll probably have to modify this to match your environment
$conn = mysqli_init();
mysqli_ssl_set($conn,NULL,NULL, "/var/www/html/BaltimoreCyberTrustRoot.crt.pem", NULL, NULL) ; 
mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
if (mysqli_connect_errno($conn)) {
die('Failed to connect to MySQL: '.mysqli_connect_error());
}

?>