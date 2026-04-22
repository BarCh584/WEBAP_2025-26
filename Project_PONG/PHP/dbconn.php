<?php
$username = "root";
$password = "";
$servername = "localhost";
$dbname = "ponggame";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}