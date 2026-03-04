<?php

$gameId = $_POST["gameId"];
$player = $_POST["player"];
$direction = $_POST["direction"];

$file = "../games/$gameId.json";
if(!file_exists($file)) exit;

$state = json_decode(file_get_contents($file), true);

$moveSpeed=20;

if($player==1){
    if($direction=="up") $state["p1Y"] -= $moveSpeed;
    if($direction=="down") $state["p1Y"] += $moveSpeed;

    if($state["p1Y"]<0) $state["p1Y"]=0;
    if($state["p1Y"]>500) $state["p1Y"]=500;
}

if($player==2){
    if($direction=="up") $state["p2Y"] -= $moveSpeed;
    if($direction=="down") $state["p2Y"] += $moveSpeed;

    if($state["p2Y"]<0) $state["p2Y"]=0;
    if($state["p2Y"]>500) $state["p2Y"]=500;
}

file_put_contents($file,json_encode($state));