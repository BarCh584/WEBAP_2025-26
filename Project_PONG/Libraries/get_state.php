<?php
include "dbconn.php";
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
    $state["velY"] *= -1.04; // increase speed by 2% on each hit to make it more exciting over time
}

// paddle 1 collision
if (
    $state["ballX"] <= 20 &&
    $state["ballY"] >= $state["p1Y"] &&
    $state["ballY"] <= $state["p1Y"] + 100
) {
    $state["velX"] *= -1.08; // increase speed by 4% on each hit to make it more exciting over time
}

// paddle 2 collision
if (
    $state["ballX"] >= 1170 &&
    $state["ballY"] >= $state["p2Y"] &&
    $state["ballY"] <= $state["p2Y"] + 100
) {
    $state["velX"] *= -1.08; // increase speed by 4% on each hit to make it more exciting over time
}

// scoring
if ($state["ballX"] < 0) {
    $state["score2"]++;
    $score = $state["score1"] . " : " . $state["score2"];
    $insertgoalscore2 = $conn->prepare("UPDATE games SET score=? WHERE id=?");
    $insertgoalscore2->bind_param("si", $score, $gameId);
    $insertgoalscore2->execute();
    if ($state["score2"] >= $state["goal"]) {
        // Player 2 wins
        $insertendtimestmt = $conn->prepare("UPDATE games SET gameend=NOW() WHERE id=?");
        $insertendtimestmt->bind_param("i", $gameId);
        $insertendtimestmt->execute();
        $statusstmt = $conn->prepare("UPDATE games SET status='finished' WHERE id=?");
        $statusstmt->bind_param("i", $gameId);
        $statusstmt->execute();
        echo json_encode($state);
        exit;
    }
    $state["ballX"] = 600;
    $state["ballY"] = 300;
    $state["velX"] = (round(rand(1, 5)) < 3) ? -4 : 4; // randomize initial direction of the ball
    $state["velY"] = (round(rand(1, 5)) < 3) ? -3 : 3; // randomize initial direction of the ball
}

if ($state["ballX"] > 1200) {
    $state["score1"]++;
    $score = $state["score1"] . " : " . $state["score2"];
    $insertgoalscore1 = $conn->prepare("UPDATE games SET score=? WHERE id=?");
    $insertgoalscore1->bind_param("si", $score, $gameId);
    $insertgoalscore1->execute();
    if ($state["score1"] >= $state["goal"]) {
        $insertendtimestmt = $conn->prepare("UPDATE games SET gameend=NOW() WHERE id=?");
        $insertendtimestmt->bind_param("i", $gameId);
        $insertendtimestmt->execute();
        $statusstmt = $conn->prepare("UPDATE games SET status='finished' WHERE id=?");
        $statusstmt->bind_param("i", $gameId);
        $statusstmt->execute();
        echo json_encode($state);
        exit;
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
