<?php
$servername = "maglev.proxy.rlwy.net";
$username = "root";
$password = "nQQHxQIyMWGARWkzZCZxrRypBRryeSkG";
$dbname = "railway";
$port = 31497;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
