<?php
$servername = "localhost";
$username = "queri";
$password = "N1U8@U5IbPFvLtgk";
$dbname = "supermarcher";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
