<?php
$servername = "shortline.proxy.rlwy.net";
$username = "root";
$password = "SrsirYBpcnNIzJuRMbeBtrkufPyXwUHD";
$dbname = "railway";
$port = 19158; // Ne pas oublier le port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
