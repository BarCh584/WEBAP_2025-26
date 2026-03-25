<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
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
    <form method="POST" class="card">
        <h3>Login</h3>

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <input type="submit" value="Login & Play">

        <p>Not registered? <a href="register.php">Create account</a></p>
    </form>
    <?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Ponggame";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
                $_SESSION['username'] = $username; // Store username in session
                $_SESSION['id'] = $user['id']; // Store user ID in session
                header("Location: game.php");
            } else {
                echo "<p style='color:red;'>Invalid username or password. Please try again.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid username or password. Please try again.</p>";
            exit();
        }
    }
    ?>
</body>

</html>