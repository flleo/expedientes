<?php

$conn = new mysqli("localhost", "root", "", "bdexpedientes");
if ($conn->connect_error) {
	die("Database connection established Failed..");
} else {
    $res['message'] = "User Added successfully";
}
$res = array('error' => false);

///////////////////////////////////////////////////

$action = 'checkLogin';

if (isset($_GET['checkLogin'])) {
	$action = $_GET['checkLogin'];
}

if ($action == 'checkLogin') {
    
	/*$email = $_POST['email'];
	$password = $_POST['password'];
   
       
	$result = $conn->query("SELECT * FROM `usuario` WHERE email = 'popo@popo' AND password = 'popo';");
        $users = array();

        if($row!=null) {
            $res['message'] = $email;
        } else {
            $res['error'] = true;
            $res['message'] = "No existe usuario";
        }
	while ($row = $result->fetch_assoc()){
		array_push($users, $row);
                
                $res['message'] = "Cogio email";
	}
	$res['users'] = $users;
      */  
}
///////////////////////////////////////////////////////////

$action = 'read';

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

if ($action == 'read') {
	$result = $conn->query("SELECT * FROM `users`");
	$users = array();

	while ($row = $result->fetch_assoc()){
		array_push($users, $row);
	}
	$res['users'] = $users;
}
////////////////////////////////////////////////////////////
if ($action == 'create') {

	$username = $_POST['username'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];


	$result = $conn->query("INSERT INTO `users` (`username`, `email`, `mobile`) VALUES ('$username', '$email', '$mobile') ");
	if ($result) {
		$res['message'] = "User Added successfully";
	} else{
		$res['error'] = true;
		$res['message'] = "Insert User fail";
	}
}


if ($action == 'update') {
	$id = $_POST['id'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];


	$result = $conn->query("UPDATE `users` SET `username` = '$username', `email` = '$email', `mobile` = '$mobile'WHERE `id` = '$id'");
	if ($result) {
		$res['message'] = "User Updated successfully";
	} else{
		$res['error'] = true;
		$res['message'] = "User Update failed";
	}
}





if ($action == 'delete') {
	$id = $_POST['id'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];


	$result = $conn->query("DELETE FROM `users` WHERE `id` = '$id'");
	if ($result) {
		$res['message'] = "User deleted successfully";
	} else{
		$res['error'] = true;
		$res['message'] = "User delete failed";
	}
}









$conn -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

 ?>
