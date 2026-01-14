<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "messages";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed to db");
}
$db = $_POST["db"];
if ($db == "users") {
    $stmt = $conn->prepare("SELECT * FROM users");

    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
}
if($db == "insertuser") {
    $stmt = $conn->prepare("INSERT INTO users(userName) VALUES (?)");
    $stmt->bind_param("s",$_POST["username"]);
    $stmt->execute();
}
if($db == "insertmessage") {
    $stmt = $conn->prepare("INSERT INTO messages (messageText, userId) VALUES (?)");
    $stmt->bind_param("s", $_POST["message"],$_POST["user"]);
    $stmt->execute();
}