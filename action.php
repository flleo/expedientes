<?php

$conn = new mysqli("localhost", "root", "", "bdexpedientes");
if ($conn->connect_error) {
	die("Database connection established Failed..");
}
$res = array('error' => false);



if ($action == 'login') {

	$email = $_POST['email'];
	$password = $_POST['password'];


	$result = $conn->query("SELECT * FROM `usuario` WHERE email = '$email' AND password = '$password'");
	if ($result) {
		$res['message'] = "User Added successfully";
	} else{
		$res['error'] = true;
		$res['message'] = "El usuario no existe.";
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
