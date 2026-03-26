<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <title>Game Settings</title>
</head>

<body>

    <div class="menu-container">

        <div class="panel">
            <h3>Host a game - game Settings</h3>

            <label>Goal number:</label>
            <input type="text" id="goalInput" value="10"><br>

            <label>Paddle speed P1:</label>
            <input type="text" id="paddleSpeedInput1" value="250"><br>

            <label>Paddle speed P2:</label>
            <input type="text" id="paddleSpeedInput2" value="250"><br>

            <label>Starting Score P1:</label>
            <input type="text" id="startgoalscore1" value="0"><br>

            <label>Starting Score P2:</label>
            <input type="text" id="startgoalscore2" value="0"><br>

            <button onclick="createGame()">Create Game</button>

            <div id="gameid"></div>
        </div>

        <br>

        <div class="panel">
            <h3>Join a game</h3>
            <input type="text" id="gameIdInput" placeholder="Game ID">
            <button onclick="joinGame()">Join Game</button>
        </div>

    </div>
    <script>
        function createGame() {

            let data = {
                goal: document.getElementById("goalInput").value,
                paddleSpeed1: document.getElementById("paddleSpeedInput1").value,
                paddleSpeed2: document.getElementById("paddleSpeedInput2").value,
                startScore1: document.getElementById("startgoalscore1").value,
                startScore2: document.getElementById("startgoalscore2").value
            };

            fetch("create_game.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    window.location.href =
                        "game.php?gameId=" + data.gameId + "&player=1";
                });

        }

        function joinGame() {

            let id = document.getElementById("gameIdInput").value;

            fetch("join_game.php?gameId=" + id)
                .then(res => res.json())
                .then(data => {

                    if (data.status == "ok") {
                        window.location.href =
                            "game.php?gameId=" + id + "&player=2";
                    } else {
                        alert("Unable to join.");
                    }

                });
        }
    </script>
</body>

</html>