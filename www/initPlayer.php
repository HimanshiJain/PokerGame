<?php

//include './cardTable.php';
include './suit.php';
include './class.database.php';
//$ct=new cardTable();
$s=new suit();

$response = array();
$response["error"] = false;

if (isset($_POST['user_id'])&&$_POST['user_id']!='') {
	$result=$database->query("Select cardtable_id,no_of_players from cardtable where no_of_players<4  ");
    $user_id = $_POST['user_id'];
	$fetched=mysqli_fetch_array($result);
	//print_r($fetched);
	$ids=array();
	//echo $fetched[0];
	//echo $fetched[1];
	//echo mysqli_num_rows($result);
	if(mysqli_num_rows($result) > 0){
		$player_id=$database->add_player($user_id,$fetched[0],$fetched[1]);
		$ids["player_id"]=$player_id;
		$ids["cardtable_id"]=$fetched[0];
		//echo $fetched['no_of_players']['0'];
		//echo $fetched['cardtable_id']['0'];
		mysqli_free_result($result);
	}else{
	$ids=$database->create_player($user_id);
    }
    if ($ids!=null) {
        $response["message"] = "User created";
        $response["ids"] = $ids;
		$response["error"]=false;
		
		
	}
    else
		{
        $response["message"] = "Failed to create user";
		$response["error"]=true;
		
    }
    
    
} else {
    $response["error"] = true;
    $response["message"] = "invalid parameters";
}


echo json_encode($response);
?>
