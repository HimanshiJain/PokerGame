<?php

include './cardTable.php';
include './Contracts/Player.php';

$ct=new cardTable();

$response = array();
$response["error"] = false;

if (isset($_POST['player_id'])&&$_POST['player_id']!=''&&$_POST['cardtable_id'])&&$_POST['cardtable_id']!='' &&
   isset($_POST['position'])) {
    $player_id = $_POST['player_id'];
	$cardtable_id=$_POST['cardtable_id'];
	$position=$_POST['position'];
	$otherplayersStatus=$ct->getOtherPlayersStatus($cardtable_id,$position);
    $turn=$ct->getTurn();
	$round=$ct->getRound();
    if (!$result) {
        $response["message"] = "Error . Move not maid";
    } else {
        $response["error"] = false;
		$response["otherPlayersStatus"]$otherPlayersStatus;
        $response["turn"]=$turn;
		$response["round"]=$round;
		
		
    }
    
    
} else {
    $response["error"] = true;
    $response["message"] = "invalid parameters";
}


echo json_encode($response);
?>
