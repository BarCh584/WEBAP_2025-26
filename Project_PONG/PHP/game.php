<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
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
    <div class="card" id="hostCard">
        <h3>Host a Game</h3>
        <label>Goal number</label>
        <input type="text" id="goalInput" value="10">
        <input type="hidden" id="paddleSpeedInput1" value="250">
        <input type="hidden" id="paddleSpeedInput2" value="250">
        <label>Starting Score P1</label>
        <input type="text" id="startgoalscore1" value="0">
        <label>Starting Score P2</label>
        <input type="text" id="startgoalscore2" value="0">
        <button onclick="createGame()">Create Game</button>
    </div><br>
    <p id="gameid">Game ID: </p>
    <div class="card" id="joinCard">
        <h3>Join a Game</h3>
        <input type="text" id="gameIdInput" placeholder="Enter Game ID">
        <button onclick="joinGame()">Join Game</button>
    </div>
    <canvas id="canvas" width="1200" height="600"></canvas>
    <script>
        let canvas = document.getElementById("canvas");
        canvas.style.display = "none"; // hide canvas until game starts
        let ctx = canvas.getContext("2d");
        let gameId = null;
        let playerNumber = null;
        const paddleHeight = 100;
        const paddleWidth = 10;
        const ballSize = 10;
        let upInterval = null;
        let downInterval = null;

        function hideForms() {
            document.querySelectorAll(".card").forEach(card => {
                card.style.display = "none";
            });
        }

        function createGame() {
            let data = {
                goal: document.getElementById("goalInput").value ?? 10,
                ballSpeedX: 600,
                ballSpeedY: 300,
                paddleSpeed1: document.getElementById("paddleSpeedInput1").value ?? 250,
                paddleSpeed2: document.getElementById("paddleSpeedInput2").value ?? 250,
                startScore1: document.getElementById("startgoalscore1").value ?? 0,
                startScore2: document.getElementById("startgoalscore2").value ?? 0
            };
            fetch("../Libraries/create_game.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    gameId = data.gameId;
                    playerNumber = 1;
                    document.getElementById("gameid").innerText = "Game Created. ID: " + gameId;
                    canvas.style.display = "block"; // show canvas when game starts
                    hideForms();
                    update();
                });
        }

        function joinGame() {
            let id = document.getElementById("gameIdInput").value;

            fetch("../Libraries/join_game.php?gameId=" + id)
                .then(res => res.json())
                .then(data => {
                    if (data.status == "ok") {
                        gameId = id;
                        playerNumber = 2;
                        document.getElementById("gameid").innerText = "Joined Game. ID: " + gameId;
                        canvas.style.display = "block"; // show canvas when game starts
                        hideForms();
                        update();
                    } else {
                        alert("Unable to join.");
                    }
                });
        }
        document.addEventListener("keydown", function(e) {
            if (!gameId) return; // prevent game input before joining/creating
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
            fetch("../Libraries/update_input.php", {
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
            setInterval(fetchState, 30); // ~33 FPS
        }

        function fetchState() {
            fetch("../Libraries/get_state.php?gameId=" + gameId)
                .then(res => res.json())
                .then(data => {
                    ballX = data.ballX;
                    ballY = data.ballY;
                    p1Y = data.p1Y;
                    p2Y = data.p2Y;
                    p1N = data.p1N;
                    p2N = data.p2N;
                    score1 = data.score1;
                    score2 = data.score2;
                    goal = data.goal;
                    draw();
                });
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "white";

            // paddles
            ctx.fillRect(10, p1Y, paddleWidth, paddleHeight);
            ctx.fillRect(canvas.width - 20, p2Y, paddleWidth, paddleHeight);

            // ball
            ctx.fillRect(ballX, ballY, ballSize, ballSize);

            // scores
            ctx.font = "20px Arial";
            ctx.fillText(p1N + ": " + score1, 200, 30);
            ctx.fillText(p2N + ": " + score2, 900, 30);

            // middle dashed line
            for (let i = 0; i < canvas.height; i += 30) {
                ctx.fillRect(canvas.width / 2, i, 2, 20);
            }
            if (score1 >= goal) {
                ctx.fillText(p1N + " wins!", 550, 300);
            } else if (score2 >= goal) {
                ctx.fillText(p2N + " wins!", 550, 300);
            }
        }
    </script>
</body>

</html>