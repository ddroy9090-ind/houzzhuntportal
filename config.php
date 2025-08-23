<?php
$host = "localhost";  // check in hPanel > Databases; usually 'localhost'
$user = "u431421769_channel_portal";
$pass = "K#ng@9090";
$db   = "u431421769_portal_cms";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4'); // good practice
?>
