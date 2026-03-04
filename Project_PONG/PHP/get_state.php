<?php

$gameId = $_GET["gameId"];
$file = "../games/$gameId.json";
if(!file_exists($file)) exit;
$state = json_decode(file_get_contents($file), true);
if(!$state["player2Joined"]){
    echo json_encode($state);
    exit;
}
// BALL PHYSICS
$state["ballX"] += $state["velX"];
$state["ballY"] += $state["velY"];
// top/bottom wall
if($state["ballY"]<=0 || $state["ballY"]>=590){
    $state["velY"] *= -1; // reverse vertical direction to bounce
}
// paddle 1
if($state["ballX"]<=20 &&
   $state["ballY"]>=$state["p1Y"] &&
   $state["ballY"]<=$state["p1Y"]+100){
    $state["velX"] *= -1; // reverse horizontal direction to bounce
}
// paddle 2
if($state["ballX"]>=1170 &&
   $state["ballY"]>=$state["p2Y"] &&
   $state["ballY"]<=$state["p2Y"]+100){
    $state["velX"] *= -1; // reverse horizontal direction to bounce
}

// scoring
if($state["ballX"]<0){
    $state["score2"]++;
    $state["ballX"]=600;
    $state["ballY"]=300;
}

if($state["ballX"]>1200){
    $state["score1"]++;
    $state["ballX"]=600;
    $state["ballY"]=300;
}

file_put_contents($file,json_encode($state));

echo json_encode($state);