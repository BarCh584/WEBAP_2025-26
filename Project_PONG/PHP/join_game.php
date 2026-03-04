<?php
$gameId = $_GET["gameId"];
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
file_put_contents($file,json_encode($state));
echo json_encode(["status"=>"ok"]);