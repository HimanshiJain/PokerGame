<?php

include './cardTable.php';
include './suit.php';
include './class.database.php';
$ct=new cardTableDatabase();
$s=new suit();

$response = array();
$response["error"] = false;

if (isset($_POST['username'])&&$_POST['username']!='') {
    $username = $_POST['username'];
	$id=$database->insert_user($username);
    
    if ($id!=null) {
        $response["message"] = "User created";
        $response["id"] = $id;
		$response["error"]=false;
		
    } else {
        $response["message"] = "Failed to create user";
		$response["error"]=true;
		
    }
    
    
} else {
    $response["error"] = true;
    $response["message"] = "invalid parameters";
}


echo json_encode($response);
?>
