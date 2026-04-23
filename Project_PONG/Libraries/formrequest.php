<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username= ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $user['id'];
            echo "success"; // <-- IMPORTANT
        } else {
            echo "Invalid username or password. Please try again.";
        }
    } else {
        echo "Invalid username or password. Please try again.";
        exit();
    }
}
