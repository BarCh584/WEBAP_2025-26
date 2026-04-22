<?php
session_start();
include "dbconn.php";
$maxgames = 0;
$data = json_decode(file_get_contents("php://input"), true);

$goal = $data["goal"] ?? 10;
$ballSpeedX = $data["ballSpeedX"] ?? 600;
$ballSpeedY = $data["ballSpeedY"] ?? 300;
$paddleSpeed1 = $data["paddleSpeed1"] ?? 250;
$paddleSpeed2 = $data["paddleSpeed2"] ?? 250;
$player1name = $_SESSION["username"] ?? "Player 1";
$player2name = null; // player 2 name cannot be set at game creation, it will be set when player 2 joins
$startScore1 = $data["startScore1"] ?? 0;
$startScore2 = $data["startScore2"] ?? 0;

$gameId = random_int(100000, 999999);

$state = [
    "goal"=>$goal,
    "ballX"=>$ballSpeedX,
    "ballY"=>$ballSpeedY,
    "velX"=> (round(rand(1, 5)) < 3) ? -4 : 4, // randomize initial direction of the ball
    "velY"=> (round(rand(1, 5)) < 3) ? -3 : 3, // randomize initial direction of the ball
    "p1Y"=>$paddleSpeed1,
    "p2Y"=>$paddleSpeed2,
    "p1N"=>$player1name,
    "p2N"=>$player2name,
    "score1"=>$startScore1,
    "score2"=>$startScore2,
    "player2Joined"=>false
];

// check if directory exists, if not create it
if(!is_dir("../games")){
    mkdir("../games", 0777, true);
}
// check if file already exists, if so generate a new ID
while(file_exists("../games/$gameId.json")){
    $gameId = $maxgames;
    $maxgames++;
    if($maxgames>899999) {
        echo json_encode(["status"=>"error", "message"=>"Too many games. Try again later."]);
        exit;
    }
}

// create db entry for the game
$insertgamestmt = $conn->prepare("INSERT INTO games (id, user1_id, user2_id, score, status) VALUES (?, ?, ?, ?, ?)");
$user1_id = $_SESSION["id"] ?? null; // store player 1's user ID in the database, not the name
$user2_id = null; // player 2 ID cannot be set at game creation, it will be set when player 2 joins
$score = $startScore1 . " : " . $startScore2;
$status = "waiting";
$insertgamestmt->bind_param("issis", $gameId, $user1_id, $user2_id, $score, $status);
$insertgamestmt->execute();
// create the file
file_put_contents("../games/$gameId.json", json_encode($state));

echo json_encode(["gameId"=>$gameId]);