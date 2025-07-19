<?php
$servername = "hopper.proxy.rlwy.net";
$username = "root";
$password = "wYXxwvIzwxUsGqNRlsTmKfUXlacdfsQb";
$dbname = "railway";
$port = 22983; // Ne pas oublier le port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
