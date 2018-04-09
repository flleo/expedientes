<?php

$conn = new mysqli("localhost", "root", "", "bdexpedientes");
if ($conn->connect_error) {
	die("Database connection established Failed..");
}
$res = array('error' => false);



$action = 'read';

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

if ($action == 'read') {
	$result = $conn->query("SELECT * FROM `expediente`");
	$expedientes = array();

	while ($row = $result->fetch_assoc()){
		array_push($expedientes, $row);
	}
	$res['expedientes'] = $expedientes;
}

if ($action == 'creaExpediente') {

	$idUrgente = $_POST['idUrgente'];
	$idTipoExpediente = $_POST['idTipoExpediente'];
	$fecha = $_POST['fecha'];
	$idTitular = $_POST['idTitular'];
	$idDireccion = $_POST['idDireccion'];
	$idProyectista = $_POST['idProyectista'];
	$idCalificacion = $_POST['idCalificacion'];
	$idIAE = $_POST['idIAE'];
	$idEstado = $_POST['idEstado'];
	$descripcion = $_POST['descripcion'];


	$result = $conn->query("INSERT INTO `expediente` (`id`, `idUrgente`, `idTipoExpediente`, `fecha`, `numero`, `idTitular`, `idDireccion`, `idProyectista`, `idCalificacion`, `idIAE`, `idEstado`, `descripcion`)
													VALUES (null,$idUrgente, $idTipoExpediente, '$fecha', null, $idTitular,$idDireccion, $idProyectista,$idCalificacion,$idIAE,$idEstado,'$descripcion')");
	if ($result) {
		$res['message'] = "Expediente añadido con éxito";
	} else{
		$res['error'] = true;
		$res['message'] = "La inserción ha fallado";
	}
}


if ($action == 'updateExpediente') {
	$id = $_POST['id'];
	$idUrgente = $_POST['idUrgente'];
	$idTipoExpediente = $_POST['idTipoExpediente'];
	$fecha = $_POST['fecha'];
	$numero = $_POST['numero'];
	$idTitular = $_POST['idTitular'];
	$idDireccion = $_POST['idDireccion'];
	$idProyectista = $_POST['idProyectista'];
	$idCalificacion = $_POST['idCalificacion'];
	$idIAE = $_POST['idIAE'];
	$idEstado = $_POST['idEstado'];
	$descripcion = $_POST['descripcion'];


	$result = $conn->query("UPDATE `expediente` SET `idUrgente`=$idUrgente,`idTipoExpediente`=$idTipoExpediente,`fecha`='$fecha',`numero`='$numero',`idTitular`=$idTitular,`idDireccion`=$idDireccion,`idProyectista`=$idProyectista,`idCalificacion`=$idCalificacion,`idIAE`=$idIAE,`idEstado`=$idEstado,`descripcion`='$descripcion' WHERE `id`=$id");
	if ($result) {
		$res['message'] = "Expediente Actualizado correctamente";
	} else{
		$res['error'] = true;
		$res['message'] = "La actualización del expediente ha fallado";
	}
}


if ($action == 'deleteExpediente') {
	$id = $_POST['id'];


	$result = $conn->query("DELETE FROM `expediente` WHERE `id` = $id");
	if ($result) {
		$res['message'] = "Expediente eliminado";
	} else{
		$res['error'] = true;
		$res['message'] = "La eliminación del expediente ha fallado";
	}
}









$conn -> close();
header("Content-type: application/json");
echo json_encode($res);
die();

 ?>
