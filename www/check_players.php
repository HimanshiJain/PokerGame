<?php

include_once './cardTable.php';
include_once './suit.php';
$ct=new cardTableDatabase();
$s=new suit();

$response = array();
$response["error"] = false;

if (isset($_POST['player_id'])&&$_POST['player_id']!=''&& isset($_POST['cardtable_id'])&& $_POST['cardtable_id']!='') {
    $player_id = $_POST['player_id'];
	$cardtable_id=$_POST['cardtable_id'];
	$ct->set_cardtable_id($cardtable_id);
	$play=false;
	$players=0;
    if($ct->istable_complete()){
		$ct->set_engaged();
			$play=true;
			$players=4;
	}else{
		$players=$ct->getPlayers($player_id);
	}

    if (!$play) {
        $response["message"] = "Wait";
        $response["play"] = false;
		$response["players"]=$players;
    } else {
        $response["play"] = true;
        $response["message"] = "Game ready to start";
		$plays=$ct->getPlayers($player_id);
		$response["position"] = $plays;
		$turn=$ct->getTurn();
		$response["turn"]=$turn;
		$position=$ct->getPosition($player_id);
		$response["position"]=$position;
		$cardarr=$s->getCards();
		$card1=$cardarr[($position-1)*2];
		$card2=$cardarr[($position-1)*2+1];
		$s->savePlayerCards($player_id,$card1,$card2);
		$response["c1"]=$card1;
		$response["c2"]=$card2;
		$s->saveCommunityCards($cardtable_id,$cardarr[12],$cardarr[11],$cardarr[10],$cardarr[9],$cardarr[7]);
		$response["all_cards"]=$cardarr;
		$big_blind=$ct->getBigBlind();
		$response["big_blind"]=$big_blind;
		
    }
    
    
} else {
    $response["error"] = true;
    $response["message"] = "invalid parameters";
}


echo json_encode($response);
?>
