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
    <form method="POST" action="game.php">
        <input type="text" name="username" placeholder="Enter your username" required><br>
        <input type="password" name="password" placeholder="Enter your password" required><br>
        <input type="submit" value="Login & Play">
        <p>Not yet registered? Create an account <a href="register.php">here</a></p>
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

            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                header("Location: game.php");
                exit();
            } else {
                echo "<p style='color:red;'>Invalid username or password. Please try again.</p>";
            }
        }
    ?>
</body>
</html>