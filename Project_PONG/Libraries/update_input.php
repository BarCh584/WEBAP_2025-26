<?php
include "dbconn.php";
$gameId = $_POST["gameId"];
$player = $_POST["player"];
$direction = $_POST["direction"];

$file = "../games/$gameId.json";
if(!file_exists($file)) exit;

$fp = fopen($file, "c+"); // c+ means read/write, create if not exists, and do not truncate meaning don't shrink it
flock($fp, LOCK_EX);

rewind($fp); // move pointer to the beginning before reading/writing
$state = json_decode(stream_get_contents($fp), true); // stream_get_contents reads from the current pointer position to the end of the file, so we need to rewind first to get the whole content.
if(!$state) $state = [];

$moveSpeed = 20;

if($player == 1){
    if($direction == "up") $state["p1Y"] -= $moveSpeed;
    if($direction == "down") $state["p1Y"] += $moveSpeed;

    if($state["p1Y"] < 0) $state["p1Y"] = 0;
    if($state["p1Y"] > 500) $state["p1Y"] = 500;
}

if($player == 2){
    if($direction == "up") $state["p2Y"] -= $moveSpeed;
    if($direction == "down") $state["p2Y"] += $moveSpeed;

    if($state["p2Y"] < 0) $state["p2Y"] = 0;
    if($state["p2Y"] > 500) $state["p2Y"] = 500;
}

// write updated state
ftruncate($fp, 0); // clear file before writing updated state
rewind($fp); // pointer to the beginning of the file
fwrite($fp, json_encode($state)); // write updated state back to file
fflush($fp); // really store data to file (not just in memory)
flock($fp, LOCK_UN); // release lock
fclose($fp);