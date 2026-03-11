<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Multiplayer Pong</title>
    <style>
        canvas {
            background: black;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <div>
        <h3>Host a game - game Settings</h3>
        <label for="goalInput">Goal number (Default=10):</label>
        <input type="text" id="goalInput" placeholder="Goal number" value="10"><br>
        <label for="paddleSpeedInput1">Paddle speed P1 (Default=250):</label>
        <input type="text" id="paddleSpeedInput1" placeholder="Paddle speed P1" value="250"><br>
        <label for="paddleSpeedInput2">Paddle speed P2 (Default=250):</label>
        <input type="text" id="paddleSpeedInput2" placeholder="Paddle speed P2" value="250"><br>
        <label for="startgoalscore1">Starting Score P1 (Default=0):</label>
        <input type="text" id="startgoalscore1" placeholder="Starting Score P1" value="0"><br>
        <label for="startgoalscore2">Starting Score P2 (Default=0):</label>
        <input type="text" id="startgoalscore2" placeholder="Starting Score P2" value="0"><br>
        <button onclick="createGame()">Create Game</button>
        <div id="gameid">Game ID: </div>
    </div><br><br>
    <div>
        <h3>Join a game</h3>
        <input type="text" id="gameIdInput" placeholder="Enter Game ID to Join">
        <button onclick="joinGame()">Join Game</button>
    </div>
    <br><br>
    <canvas id="canvas" width="1200" height="600"></canvas>
    <script>
        let canvas = document.getElementById("canvas");
        let ctx = canvas.getContext("2d");
        let gameId = null;
        let playerNumber = null;
        const paddleHeight = 100;
        const paddleWidth = 10;
        const ballSize = 10;
        let upInterval = null;
        let downInterval = null;

        function createGame() {
            let data = {
                goal: document.getElementById("goalInput").value ?? 10,
                ballSpeedX: 600,
                ballSpeedY: 300,
                paddleSpeed1: document.getElementById("paddleSpeedInput1").value ?? 250,
                paddleSpeed2: document.getElementById("paddleSpeedInput2").value ?? 250,
                startScore1: document.getElementById("startgoalscore1").value ?? 0,
                startScore2: document.getElementById("startgoalscore2").value ?? 0
            }
            fetch("create_game.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json()).then(data => {
                    gameId = data.gameId;
                    playerNumber = 1;
                    document.getElementById("gameid").innerText = "Game Created. ID: " + gameId;
                    update();
                });
        }

        function joinGame() {
            let id = document.getElementById("gameIdInput").value;
            fetch("join_game.php?gameId=" + id).then(res => res.json()).then(data => {
                if (data.status == "ok") {
                    gameId = id;
                    playerNumber = 2;
                    update();
                } else {
                    alert("Unable to join.");
                }
            });
        }
        document.addEventListener("keydown", function(e) {
            if (!gameId) return;

            if (e.key === "w" || e.key === "ArrowUp") {
                if (!upInterval) {
                    sendInput("up");
                    upInterval = setInterval(() => sendInput("up"), 50);
                }
            }

            if (e.key === "s" || e.key === "ArrowDown") {
                if (!downInterval) {
                    sendInput("down");
                    downInterval = setInterval(() => sendInput("down"), 50);
                }
            }
        });

        document.addEventListener("keyup", function(e) {
            if (e.key === "w" || e.key === "ArrowUp") {
                clearInterval(upInterval);
                upInterval = null;
            }

            if (e.key === "s" || e.key === "ArrowDown") {
                clearInterval(downInterval);
                downInterval = null;
            }
        });

        function sendInput(direction) {
            fetch("update_input.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "gameId=" + gameId +
                    "&player=" + playerNumber +
                    "&direction=" + direction
            });
        }

        function update() {
            setInterval(fetchState, 30); // 30ms around 33 FPS
        }

        function fetchState() {
            fetch("get_state.php?gameId=" + gameId).then(res => res.json()).then(data => {
                ballX = data.ballX;
                ballY = data.ballY;
                p1Y = data.p1Y;
                p2Y = data.p2Y;
                p1N = data.p1N;
                p2N = data.p2N;
                score1 = data.score1;
                score2 = data.score2;
                draw();
            });
        }

        function draw() {
            // Draw basic game state: paddles, ball, scores
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "white";
            ctx.fillRect(10, p1Y, paddleWidth, paddleHeight);
            ctx.fillRect(canvas.width - 20, p2Y, paddleWidth, paddleHeight);
            ctx.fillRect(ballX, ballY, ballSize, ballSize);
            ctx.font = "20px Arial";
            ctx.fillText(p1N + ": " + score1, 200, 30);
            ctx.fillText(p2N + ": " + score2, 900, 30);
            // create the middle dashed line like 1972 atari pong style
            for (let i = 0; i < canvas.height; i += 30) {
                ctx.fillRect(canvas.width / 2, i, 2, 20);
            }
        }
    </script>
</body>

</html>