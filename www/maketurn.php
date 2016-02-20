<?php

include './cardTable.php';
include './HandStrength.php';
include './Player.php';
$ct=new cardTableDatabase();
$player=new Player();
$handstr=new HandStrength();
$response = array();
$response["error"] = false;

if (isset($_POST['player_id'])&&$_POST['player_id']!=''&&isset($_POST['cardtable_id'])&&$_POST['cardtable_id']!='' &&
   isset($_POST['move'])) {
	   if(isset($_POST['raise']))
		   $raise=$_POST['raise'];
	   else $raise=0;
    $player_id = $_POST['player_id'];
	$cardtable_id=$_POST['cardtable_id'];
	$move=$_POST['move'];
	
	$ct->set_cardtable_id($cardtable_id);
	$player->set_current_player($player_id,$cardtable_id);
	$result=$player->makeMove($move,$raise);
	$player->update_turn();
	$turn=$player->getTurn();
	$player_status=$player->getPlayerStatus();
	
    $round=$player->getRound();
	$hands=array();
	if($round==5){
		for($i=1;$i<5;++$i){
			$hands[$i]=$handstr->getHand();
		}
		arsort($hands);
		reset($hands);
		$first_key = key($hands);
		$response["winner"] = $first_key;
		$response["winnerScore"]=$hands[$first_key];
	}else{
    if (!$result) {
        $response["message"] = "Error . Move not maid";
    } else {
        $response["play"] = true;
        $response["message"] = "move successful";
		$response["turn"]=$turn;
		$response["round"]=$round;
		$response["player_status"]=$player_status;
		
    }
	}
    
    
} else {
    $response["error"] = true;
    $response["message"] = "invalid parameters";
}


echo json_encode($response);
?>
