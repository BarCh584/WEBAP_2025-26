<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../style.css">
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
    <div class="menu-container">
        <form method="POST" class="panel">
            <input type="text" id="username" name="username" placeholder="Enter your username" required><br>
            <input type="password" id="password" name="password" placeholder="Enter your password" required><br>
            <input type="submit" id="submit" value="Login & Play">
            <p>Not yet registered? Create an account <a href="register.php">here</a></p>
            <p id="response"></p>
        </form>
    </div>
    <script src="../Libraries/jquery-4.0.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // check if form was submitted 
            $("#submit").click(function(event) {
                event.preventDefault();
                $.post("../Libraries/formrequest.php", 
                {
                    username: $("#username").val(),
                    password: $("#password").val()
                }, 
                function(response) {
                    if (response.trim() === "success") {
                        window.location.href = "game.php";
                    } else {
                        $("#response").text(response);
                    }
                });
            });
        });
    </script>
</body>

</html>