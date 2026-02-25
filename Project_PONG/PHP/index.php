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
    <label>Goal number</label>
    <input type="number" id="goalnumber">
    <button id="goalnumberbutton">Start</button>
    <br><br>
    <canvas id="canvas" width="1200" height="600"></canvas>
    <script>

        // canvas
        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d"); // access canvas drawing function with reference
        // ball functions
        let ballposX = canvas.width / 2;
        let ballposY = canvas.height / 2;
        let ballvelocityX = 3;
        let ballvelocityY = 1.75;
        let balllength = 10;

        // paddles
        const paddleheight = 100;
        const paddlewidth = 10;
        // player 1 is left
        var player1paddleX = 10; // Move it away a bit from the edge
        var player1paddleY = (canvas.height - paddleheight) / 2; // Middle of Y
        // player 2 is right
        var player2paddleX = (canvas.width - paddlewidth - 10)// Move it away a bit from the edge 
        var player2paddleY = (canvas.height - paddleheight) / 2; // Middle of Y

        // audio
        var touchsound = new Audio("touchsound.wav"); // touch sound
        var goalsound = new Audio("goalsound.wav"); // goal sound
        // scores
        let player1score = 0;
        let player2score = 0;
        let winningscore = 5;
        let gameover = false;
        let player1won = false;
        let player2won = false;
        var keysPressed = {}; // Object to store pressed keys
        let keyRepeatInterval = null; // Variable to store the interval ID for key repeat
        let moveSpeed = 20; // Adjust this value to change the movement speed

        document.addEventListener("keydown", function (event) {
            keysPressed[event.key] = true;
            startKeyRepeat();
        });

        document.addEventListener("keyup", function (event) {
            delete keysPressed[event.key];
            stopKeyRepeat();
        });

        // Function to start key repeat action
        function startKeyRepeat() {
            if (!keyRepeatInterval) {
                // Start executing the action repeatedly (adjust interval as needed)
                keyRepeatInterval = setInterval(movePaddles, 50);
            }
        }
        // Function to stop key repeat action
        function stopKeyRepeat() {
            if (Object.keys(keysPressed).length === 0) {
                // Stop executing the action repeatedly
                clearInterval(keyRepeatInterval);
                keyRepeatInterval = null;
            }
        }

        function movePaddles() {
            if (keysPressed["w"] && player1paddleY > 0) { // left paddle cannot exceed the upper edge
                player1paddleY -= moveSpeed;
            } else if (keysPressed["s"] && player1paddleY < canvas.height - paddleheight) { // left paddle cannot exceed the lower edge
                player1paddleY += moveSpeed;
            }
            if (keysPressed["ArrowUp"] && player2paddleY > 0) { // right paddle cannot exceed the lower edge
                player2paddleY -= moveSpeed;
            } else if (keysPressed["ArrowDown"] && player2paddleY < canvas.height - paddleheight) { // right paddle cannot exceed the lower edge
                player2paddleY += moveSpeed;
            }
        }
        function drawscoresincanvas() {
            ctx.beginPath(); // start drawing path
            ctx.font = "20px Arial";
            ctx.fillStyle = "white";
            ctx.textAlign = "center";
            ctx.fillText("Player1: " + player1score, canvas.width / 4, 20);
            ctx.fillText("Player2: " + player2score, canvas.width / 4 * 3, 20);
            ctx.closePath(); // stop drawing path
        }
        function drawpaddle(x, y) {
            ctx.beginPath(); // start drawing path
            ctx.rect(x, y, paddlewidth, paddleheight);
            ctx.fillStyle = "white";
            ctx.fill();
            ctx.closePath(); // stop drawing path
        }
        function drawball(x) {
            ctx.beginPath();
            ctx.rect(ballposX - x / 2, ballposY - x / 2, x, x);
            ctx.fillStyle = "white";
            ctx.fill();
            ctx.closePath();
        }
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height); // clear everything and start drawing from 0
            drawpaddle(player1paddleX, player1paddleY);
            drawpaddle(player2paddleX, player2paddleY);
            drawball(balllength);
            drawscoresincanvas();
            ballphysics();
            console.log(ballvelocityX + "" + ballvelocityY);
        }
        function ballphysics() {
            // Update ball position
            ballposX += ballvelocityX;
            ballposY += ballvelocityY;

            // Check for collision with top and bottom walls
            if (ballposY + balllength > canvas.height || ballposY < 0) {
                ballvelocityY = -ballvelocityY;
                touchsound.play();
                if (ballvelocityX < 0) {
                    ballvelocityX -= 0.1; // acceleration of ball
                }
                else if (ballvelocityX > 0) {
                    ballvelocityX += 0.1; // acceleration of ball
                }
                if (ballvelocityY < 0) {
                    ballvelocityY -= 0.05; // acceleration of ball
                }
                else if (ballvelocityY > 0) {
                    ballvelocityY += 0.05; // acceleration of ball
                } // make ball move faster after every collision with paddle
            }

            // Check for collision with paddles
            if (
                ballposX <= player1paddleX + paddlewidth &&
                ballposY + balllength >= player1paddleY &&
                ballposY <= player1paddleY + paddleheight
            ) {
                ballvelocityX = -ballvelocityX;
                ballvelocityX -= 0.1;
                if (ballvelocityY < 0) {
                    ballvelocityY -= 0.05;
                }
                else if (ballvelocityY > 0) {
                    ballvelocityY += 0.05;
                } // make ball move faster after every collision with paddle
                touchsound.play();
            } else if (
                ballposX + balllength >= player2paddleX &&
                ballposY + balllength >= player2paddleY &&
                ballposY <= player2paddleY + paddleheight
            ) {
                ballvelocityX = -ballvelocityX;
                ballvelocityX -= 0.1;
                if (ballvelocityY < 0) {
                    ballvelocityY -= 0.05;
                } // make ball move faster after every collision with paddle
                else if (ballvelocityY > 0) {
                    ballvelocityY += 0.05;
                } // make ball move faster after every collision with paddle
                touchsound.play();
            }

            // Check for scoring
            if (ballposX + balllength > canvas.width) {
                player1score++;
                ctx.clearRect(0, 0, canvas.width, canvas.height - 40); // clear playerresult
                resetball();
                goalsound.play();
            } else if (ballposX < 0) {
                player2score++;
                ctx.clearRect(0, 0, canvas.width, canvas.height - 40); // clear playerresult
                
                resetball();
                goalsound.play();
            }
        }

        function resetball() {
            if (player1score >= winningscore) {
                gameover = true;
                player1won = true;
                ctx.beginPath(); // start drawing path
                ctx.font = "20px Arial";
                ctx.fillStyle = "white";
                ctx.textAlign = "center";
                ctx.fillText("Player1 won: " + player1score + ":" + player2score, canvas.width / 2, canvas.height / 2);
                ctx.closePath(); // stop drawing path

                drawscoresincanvas();
            }
            else if (player2score >= winningscore) {
                gameover = true;
                player2won = true;
                ctx.beginPath(); // start drawing path
                ctx.font = "20px Arial";
                ctx.fillStyle = "white";
                ctx.textAlign = "center";
                ctx.fillText("Player2 won: " + player2score + ":" + player1score, canvas.width / 2, canvas.height / 2);
                ctx.closePath(); // stop drawing path
                drawscoresincanvas();
            }
            else {
                ballposX = canvas.width / 2;
                ballposY = canvas.height / 2;
                player1paddleY = (canvas.height - paddleheight) / 2;
                player2paddleY = (canvas.height - paddleheight) / 2;
                ballvelocityX = 3;
                ballvelocityY = 1.75;
                ballvelocityX = Math.random() > 0.5 ? ballvelocityX : -ballvelocityX; // Randomize X direction (If statement)
                ballvelocityY = Math.random() > 0.5 ? ballvelocityY : -ballvelocityY; // Randomize Y direction (If statement)
                draw();
            }
        }
        function gameLoop() {
            if (gameover != true) {
                draw();
                requestAnimationFrame(gameLoop); // efficient animation method to recall a function and make the "animation" in this case reutilize the gameloop method   
            }

        }
        var startbutton = document.getElementById("goalnumberbutton");
        startbutton.addEventListener("click", function start() {
            var goalnumbervalue = parseInt(document.getElementById("goalnumber").value);
            if (!isNaN(goalnumbervalue) && goalnumbervalue <= -0) {
                alert("Goal number cannot be null, 0 or negative");
            }
            else {
                winningscore = goalnumbervalue;
                player1score = 0;
                player2score = 0;
                gameLoop();
            }
        });
    </script>
</body>
</html>