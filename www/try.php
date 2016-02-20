<?php

$hands=array();
$hands["p1"]=40;
$hands["p2"]=67;
$hands["p3"]=33;
asort($hands);
		
		
		
/*$hands->sortByDesc(function($hand)
        {
            return $hand->getValue();
        });*/
		arsort($hands);
		reset($hands);
		$first_key = key($hands);
		$response["winner"] = $first_key;
		$response["winnerScore"]=$hands[$first_key];
$response["scoreArray"]=$hands;
		print_r($hands);
echo json_encode($response);
?>