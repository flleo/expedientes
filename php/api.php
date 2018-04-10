<?php

$conn = new mysqli("localhost", "root", "", "bdexpedientes");

if ($conn->connect_error)
    die("Database connection established Failed..");
$res = array('error' => false);

if (isset($_GET['action']))    $action = $_GET['action'];

//Registrarse//////////////////////////////////////////////
if ($action == 'registrarse') {
   /* $id = $_POST['id'];
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
    } else {
        $res['error'] = true;
        $res['message'] = "La actualización del expediente ha fallado";
    }
    * 
    */
}
if ($action == 'mostrarPaises') {
    $result = $conn->query("SELECT * FROM `pais`");
    $paises = array();

    while ($row = $result->fetch_assoc()) {
        array_push($paises, $row);
    }
    $res['paises'] = $paises;
}
if ($action == 'mostrarProvincias') {
    $id=$_POST['id'];
    $result = $conn->query("SELECT * FROM `provincia` WHERE idPais = $id");
    $provincias = array();

    while ($row = $result->fetch_assoc()) {
        array_push($provincias, $row);
    }
    $res['provincias'] = $provincias;
}

//Login/////////////////////////////////////////////////////
if ($action == 'comprobarLogin') {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    $result = $conn->query("SELECT * FROM `usuario` WHERE email like '$email' AND contraseña like '$contraseña'");

    $res['usuario'] = $result->fetch_assoc();
    if ($res['usuario']!=null) {
        $res['message'] = "Usuario logueado con éxito";
    } else {
        $res['error'] = true;
        $res['message'] = "Login fallido";
    }
}



//Expedientes//////////////////////////////////////////////////
if ($action == 'mostrarExpedientes') {
    $result = $conn->query("SELECT * FROM `expediente`");
    $expedientes = array();

    while ($row = $result->fetch_assoc()) {
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
    } else {
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
    } else {
        $res['error'] = true;
        $res['message'] = "La actualización del expediente ha fallado";
    }
}


if ($action == 'deleteExpediente') {
    $id = $_POST['id'];


    $result = $conn->query("DELETE FROM `expediente` WHERE `id` = $id");
    if ($result) {
        $res['message'] = "Expediente eliminado";
    } else {
        $res['error'] = true;
        $res['message'] = "La eliminación del expediente ha fallado";
    }
}









$conn->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>
