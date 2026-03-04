<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pong game</title>
    <style>
        canvas {
            background-color: black;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter your username" required><br>
        <input type="password" name="password" placeholder="Enter your password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm your password" required><br>
        <input type="submit" value="Register">
        <p>Already have an account? Login <a href="index.php">here</a></p>
    </form>
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Ponggame";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];
            if($password !== $confirm_password) {
                echo "<p style='color:red;'>Passwords do not match. Please try again.</p>";
                exit();
            }
            if(strlen($password) < 6) {
                echo "<p style='color:red;'>Password must be at least 6 characters long. Please try again.</p>";
                exit();
            }
            if(!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
                echo "<p style='color:red;'>Username can only contain letters, numbers, and underscores. Please try again.</p>";
                exit();
            }
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?,?)");
            $stmt->bind_param("ss", $username, $hashedpassword);
            if($stmt->execute()) {
                echo "<p style='color:green;'>Registration successful! You can now <a href='index.php'>login</a>.</p>";
            } else {
                echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
    ?>
</body>
</html>