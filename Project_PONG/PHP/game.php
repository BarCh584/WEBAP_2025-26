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
        <input type="text" id="goalInput" placeholder="Goal number (Default=10)">
        <input type="text" id="ballSpeedInput" placeholder="Ball speed (Default=5)">
        <input type="text" id="paddleSpeedInput" placeholder="Paddle speed (Default=5)">
    <button onclick="createGame()">Create Game</button>
    <div id="gameidinf"></div>
    </div><br><br>
    <div>
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
        let ballX = 600;
        let ballY = 300;
        let p1Y = 250;
        let p2Y = 250;
        let score1 = 0;
        let score2 = 0;
        const paddleHeight = 100;
        const paddleWidth = 10;
        const ballSize = 10;

        function createGame() {
            fetch("create_game.php").then(res => res.json()).then(data => {
                    gameId = data.gameId;
                    playerNumber = 1;
                    document.getElementById("gameidinf").innerText = "Game Created. ID: " + gameId;
                    update();
                });
        }
        function joinGame() {
            let id = prompt("Enter Game ID:");
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
            if (e.key == "w" || e.key == "ArrowUp") sendInput("up");
            if (e.key == "s" || e.key == "ArrowDown") sendInput("down");
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
            ctx.fillText("P1: " + score1, 200, 30);
            ctx.fillText("P2: " + score2, 900, 30);
        }
    </script>
</body>

</html>