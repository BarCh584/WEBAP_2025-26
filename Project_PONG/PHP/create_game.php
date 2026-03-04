<?php

$gameId = uniqid();

$state = [
    "ballX"=>600,
    "ballY"=>300,
    "velX"=>4,
    "velY"=>3,
    "p1Y"=>250,
    "p2Y"=>250,
    "score1"=>0,
    "score2"=>0,
    "player2Joined"=>false
];

file_put_contents("../games/$gameId.json", json_encode($state));

echo json_encode(["gameId"=>$gameId]);