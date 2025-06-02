<?php
$servername = "localhost";
$username = "queri";
$password = "BSm6/DRdZPaqoik-";
$dbname = "supermarcher";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
