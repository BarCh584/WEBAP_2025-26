<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ponggame";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$gameId = $_GET["gameId"];
$file = "../games/$gameId.json";

if (!file_exists($file)) exit;

$fp = fopen($file, "c+");
flock($fp, LOCK_EX);

rewind($fp);
$state = json_decode(stream_get_contents($fp), true);
if (!$state) $state = [];

// If player 2 hasn't joined yet, just return state
if (!($state["player2Joined"] ?? false)) {
    flock($fp, LOCK_UN);
    fclose($fp);
    echo json_encode($state);
    exit;
}

// BALL PHYSICS
$state["ballX"] += $state["velX"];
$state["ballY"] += $state["velY"];

// top/bottom wall
if ($state["ballY"] <= 0 || $state["ballY"] >= 590) {
    $state["velY"] *= -1.02; // increase speed by 2% on each hit to make it more exciting over time
}

// paddle 1 collision
if (
    $state["ballX"] <= 20 &&
    $state["ballY"] >= $state["p1Y"] &&
    $state["ballY"] <= $state["p1Y"] + 100
) {
    $state["velX"] *= -1.04; // increase speed by 4% on each hit to make it more exciting over time
}

// paddle 2 collision
if (
    $state["ballX"] >= 1170 &&
    $state["ballY"] >= $state["p2Y"] &&
    $state["ballY"] <= $state["p2Y"] + 100
) {
    $state["velX"] *= -1.04; // increase speed by 4% on each hit to make it more exciting over time
}

// scoring
if ($state["ballX"] < 0) {
    $state["score2"]++;
    if ($state["score2"] >= $state["goal"]) {
        echo "<script>alert('" . $state["p2N"] . " wins " . $state["score2"] . ":" . $state["score1"] . "');</script>";
        return;
    }
    $state["ballX"] = 600;
    $state["ballY"] = 300;
    $state["velX"] = (round(rand(1, 5)) < 3) ? -4 : 4; // randomize initial direction of the ball
    $state["velY"] = (round(rand(1, 5)) < 3) ? -3 : 3; // randomize initial direction of the ball
}

if ($state["ballX"] > 1200) {
    $state["score1"]++;
    if ($state["score1"] >= $state["goal"]) {
        echo "<script>alert('" . $state["p1N"] . " wins " . $state["score1"] . ":" . $state["score2"] . "');</script>";
        return;
    }
    $state["ballX"] = 600;
    $state["ballY"] = 300;
    $state["velX"] = (round(rand(1, 5)) < 3) ? -4 : 4; // randomize initial direction of the ball
    $state["velY"] = (round(rand(1, 5)) < 3) ? -3 : 3; // randomize initial direction of the ball
}

// write updated state
ftruncate($fp, 0);
rewind($fp);
fwrite($fp, json_encode($state));
fflush($fp);

flock($fp, LOCK_UN);
fclose($fp);

echo json_encode($state);
