<?php
session_start();
$gameId = $_GET["gameId"];
$player2name = $_SESSION["username"] ?? "Player 2";
$file = "../games/$gameId.json";
if(!file_exists($file)){
    echo json_encode(["status"=>"error"]);
    exit;
}
$state = json_decode(file_get_contents($file), true);
if($state["player2Joined"]==true){
    echo json_encode(["status"=>"error"]);
    exit;
}
$state["player2Joined"]=true;
$state["p2N"] = $player2name;
file_put_contents($file,json_encode($state));
echo json_encode(["status"=>"ok"]);