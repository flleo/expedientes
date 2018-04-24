<?php

$conn = new mysqli("localhost", "root", "", "bdexpedientes");

if ($conn->connect_error)
    die("Database connection established Failed..");
$res = array('error' => false);

if (isset($_GET['action']))
    $action = $_GET['action'];

//Calificaciones//////////////////////////////////////
if ($action == 'mostrarCalificaciones') {
    $result = $conn->query("SELECT * FROM `calificaciones`");
    $calificaciones = array();

    while ($row = $result->fetch_assoc()) {
        array_push($calificaciones, $row);
    }
    $res['calificaciones'] = $calificaciones;
}

if ($action == 'grabarCalificacion') {
    $id = $_POST['id'];
    $idExpediente = $_POST['idExpediente'];
    $idTecnico = $_POST['idTecnico'];
    $idCalificaciones = $_POST['idCalificaciones'];
    $fecha = $_POST['fecha'];

    $result = $conn->query("INSERT INTO `calificacion`(`id`, `idExpediente`, `idTecnico`, `idCalificaciones`, `fecha`) VALUES (null,'$idExpediente',$idTecnico,$idCalificaciones,'$idFecha')");
    if ($result) {
        $res['message'] = "Calificacion añadida con éxito";
        $res['id'] = $conn->insert_id;
    } else {
        $res['error'] = true;
        $res['message'] = "La inserción Calificacion ha fallado";
    }
}


//Expedientes/////////////////////////////////////////////////////////////////////////////////////////////
//Actualizaciones//////////////////////////////////////////////////
if ($action == 'actualizaExpedienteCalificacion') {
    $id = $_POST['id'];
    $idExpediente = $_POST['idExpediente'];

    $result = $conn->query("UPDATE `expediente` SET `idCalificacion`=$id WHERE numero='$idExpediente'");
    if ($result) {
        $res['message'] = "Calificacion Actualizada correctamente";
    } else {
        $res['error'] = true;
        $res['message'] = "La actualización del Calificacion ha fallado";
    }
}
if ($action == 'actualizarExpedienteEstado') {
    $id = $_POST['id'];

    $result = $conn->query("UPDATE `expediente` SET `idEstado`=1 WHERE `id`=$id");
    if ($result) {
        $res['message'] = "Estado Actualizado correctamente";
    } else {
        $res['error'] = true;
        $res['message'] = "La actualización del Estado ha fallado";
    }
}

//Expedientes////////////////////////////////////////
if ($action == 'mostrarExpedientesTecnico') {
    $idTecnico = $_POST['id'];
    $result = $conn->query("
SELECT   expediente.id, urgente.urgente, tipoExpediente.tipo, expediente.fecha, expediente.numero, titular.nombre AS titular, direccion.direccion, proyectista.nombre AS proyectista, calificaciones.calificacion, IAE.denominacion AS iae , estado.estado,expediente.descripcion  
FROM `expediente` INNER JOIN urgente ON urgente.id=expediente.idUrgente INNER JOIN tipoExpediente ON tipoExpediente.id=expediente.idTipoExpediente
INNER JOIN persona AS titular ON titular.id=expediente.idTitular INNER JOIN direccion ON direccion.id=expediente.idDireccion
INNER JOIN persona AS proyectista ON proyectista.id=expediente.idProyectista 
INNER JOIN calificacion ON calificacion.id=expediente.idCalificacion 
INNER JOIN calificaciones ON calificaciones.id=calificacion.idCalificaciones
INNER JOIN IAE ON IAE.id=expediente.idIAE INNER JOIN estado ON estado.id=expediente.idEstado
ORDER BY expediente.id WHERE calificacion.idTecnico=$idTecnico");
    $expedientes = array();

    while ($row = $result->fetch_assoc()) {
        array_push($expedientes, $row);
    }
    $res['expedientes'] = $expedientes;
}
if ($action == 'mostrarExpedientes') {
    $result = $conn->query("
SELECT   expediente.id, urgente.urgente, tipoExpediente.tipo, expediente.fecha, expediente.numero, titular.nombre AS titular, direccion.direccion, proyectista.nombre AS proyectista, calificaciones.calificacion, IAE.denominacion AS iae , estado.estado,expediente.descripcion  
FROM `expediente` INNER JOIN urgente ON urgente.id=expediente.idUrgente INNER JOIN tipoExpediente ON tipoExpediente.id=expediente.idTipoExpediente
INNER JOIN persona AS titular ON titular.id=expediente.idTitular INNER JOIN direccion ON direccion.id=expediente.idDireccion
INNER JOIN persona AS proyectista ON proyectista.id=expediente.idProyectista 
INNER JOIN calificacion ON calificacion.id=expediente.idCalificacion 
INNER JOIN calificaciones ON calificaciones.id=calificacion.idCalificaciones
INNER JOIN IAE ON IAE.id=expediente.idIAE INNER JOIN estado ON estado.id=expediente.idEstado
ORDER BY expediente.id
");
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
        $res['id'] = $conn->insert_id;
    } else {
        $res['error'] = true;
        $res['message'] = "La inserción ha fallado";
    }
}

if ($action == 'generaNumeroExpediente') {
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $tipo = $_POST['tipo'];

    $result = $conn->query("UPDATE expediente SET numero = CONCAT($tipo,'-',id,'/',año) WHERE expediente.id = id");
    if ($result) {
        $res['message'] = "Expediente añadido con éxito";
        $res['id'] = $conn->insert_id;
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
//Login/////////////////////////////////////////////////////
if ($action == 'comprobarLogin') {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    $result = $conn->query("SELECT * FROM `usuario` WHERE usuario like '$usuario' AND contraseña like '$contraseña'");
    $res['usuario'] = $result->fetch_assoc();
    if ($res['usuario'] != null) {
        $res['message'] = "Usuario logueado con éxito";
    } else {
        $res['error'] = true;
        $res['message'] = "Login fallido";
    }
}
//NewExpedientes//////////////////////////////////////////////////////
////Direccio/////////////////////////////
if ($action == 'mostrarDirecciones') {
    $result = $conn->query("SELECT * FROM `direccion`");
    
    $resultados = array();
    while ($row = $result->fetch_assoc()) {
        array_push($resultados, $row);
    }
    $res['direcciones'] = $resultados;
}
////Estado/////////////////////////////
if ($action == 'mostrarEstados') {
    $result = $conn->query("SELECT * FROM `estado`");
    
    $resultados = array();
    while ($row = $result->fetch_assoc()) {
        array_push($resultados, $row);
    }
    $res['estados'] = $resultados;
}

////IAE/////////////////////////////
if ($action == 'mostrarIAEs') {
    $result = $conn->query("SELECT * FROM `IAE`");
    
    $resultados = array();
    while ($row = $result->fetch_assoc()) {
        array_push($resultados, $row);
    }
    $res['IAEs'] = $resultados;
}
////TipoExpedientes///////////
if ($action == 'mostrarTipoExpedientes') {
    $result = $conn->query("SELECT * FROM `tipoExpediente`");
    
    $resultados = array();
    while ($row = $result->fetch_assoc()) {
        array_push($resultados, $row);
    }
    $res['tipoExpedientes'] = $resultados;
}
//Titulares//////////////////
if ($action == 'mostrarTitulares') {
    $result = $conn->query("SELECT DISTINCT persona.id,persona.dni,persona.nombre,persona.idDireccion,persona.email,persona.telefono FROM persona INNER JOIN expediente ON expediente.idTitular=persona.id");
    
    $resultados = array();
    while ($row = $result->fetch_assoc()) {
        array_push($resultados, $row);
    }
    $res['titulares'] = $resultados;
}

//Proyectistas /////////////////////////////////////
if ($action == 'mostrarProyectistas') {
    $result = $conn->query("SELECT DISTINCT persona.id,persona.dni,persona.nombre,persona.idDireccion,persona.email,persona.telefono FROM persona INNER JOIN expediente ON expediente.idProyectista=persona.id");
    
    $resultados = array();
    while ($row = $result->fetch_assoc()) {
        array_push($resultados, $row);
    }
    $res['proyectistas'] = $resultados;
}
//Urgentes/////////////
if ($action == 'mostrarUrgentes') {
    $result = $conn->query("SELECT * FROM `urgente`");
    
    $urgentes = array();
    while ($row = $result->fetch_assoc()) {
        array_push($urgentes, $row);
    }
    $res['urgentes'] = $urgentes;
}

//fin NewExpediientes///////////////////////////////////////////


//Registrarse//////////////////////////////////////////////
if ($action == 'registrarse') {
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
if ($action == 'mostrarPaises') {
    $result = $conn->query("SELECT * FROM `pais`");
    $paises = array();

    while ($row = $result->fetch_assoc()) {
        array_push($paises, $row);
    }
    $res['paises'] = $paises;
}
if ($action == 'mostrarProvincias') {
    $id = $_POST['id'];
    $result = $conn->query("SELECT * FROM `provincia` WHERE idPais = $id");
    $provincias = array();

    while ($row = $result->fetch_assoc()) {
        array_push($provincias, $row);
    }
    $res['provincias'] = $provincias;
}
if ($action == 'mostrarMunicipios') {
    $id = $_POST['id'];
    $result = $conn->query("SELECT * FROM `municipio` WHERE idProvincia =  $id");
    $municipios = array();

    while ($row = $result->fetch_assoc()) {
        array_push($municipios, $row);
    }
    $res['municipios'] = $municipios;
}
if ($action == 'mostrarLocalidades') {
    $id = $_POST['id'];
    $result = $conn->query("SELECT * FROM `localidad` WHERE idProvincia =  $id");
    $localidades = array();

    while ($row = $result->fetch_assoc()) {
        array_push($localidades, $row);
    }
    $res['localidades'] = $localidades;
}
if ($action == 'añadirProvincia') {
    $id = $_POST['id'];
    $idPais = $_POST['idPais'];
    $provincia = $_POST['provincia'];

    $result = $conn->query("INSERT INTO `provincia`(`id`, `idPais`, `provincia`) VALUES (null,$idPais, '$provincia')");
    if ($result) {
        $res['message'] = "Provincia añadida con éxito";
        $res['id'] = $conn->insert_id;
    } else {
        $res['error'] = true;
        $res['message'] = "La inserción Provincia ha fallado";
    }
}

if ($action == 'añadirMunicipio') {
    $id = $_POST['id'];
    $idProvincia = $_POST['idProvincia'];
    $municipio = $_POST['municipio'];

    $result = $conn->query("INSERT INTO `municipio`(`id`, `idProvincia`, `municipio`) VALUES (null,$idProvincia, '$municipio')");
    if ($result) {
        $res['message'] = "Municipio añadido con éxito";
        $res['id'] = $conn->insert_id;
    } else {
        $res['error'] = true;
        $res['message'] = "La inserción Municipio ha fallado";
    }
}

if ($action == 'añadirLocalidad') {
    $id = $_POST['id'];
    $idProvincia = $_POST['idProvincia'];
    $localidad = $_POST['localidad'];

    $result = $conn->query("INSERT INTO `localidad`(`id`, `idProvincia`, `localidad`) VALUES (null,$idProvincia, '$localidad')");
    if ($result) {
        $res['message'] = "Localidad añadido con éxito";
        $res['id'] = $conn->insert_id;
    } else {
        $res['error'] = true;
        $res['message'] = "La inserción Localidad ha fallado";
    }
}

if ($action == 'añadirDireccion') {
    $idPais = $_POST['idPais'];
    $idProvincia = $_POST['idProvincia'];
    $idMunicipio = $_POST['idMunicipio'];
    $idLocalidad = $_POST['idLocalidad'];
    $codPostal = $_POST['codPostal'];
    $direccion = $_POST['direccion'];

    $result = $conn->query("INSERT INTO `direccion`(`id`, `idPais`, `idProvincia`, `idMunicipio`, `idLocalidad`, `codPostal`, `direccion`) VALUES (null,$idPais, $idProvincia, $idMunicipio, $idLocalidad, '$codPostal','$direccion')");
    if ($result) {
        $res['message'] = "Direccion añadida con éxito";
        $res['insert_id'] = $conn->insert_id;
    } else {
        $res['error'] = true;
        $res['message'] = "La inserción Direccion ha fallado";
    }
}

if ($action == 'añadirPersona') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $idDireccion = $_POST['idDireccion'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $ok = comprobarDni($dni);

    if ($ok[0] == 0 && $ok[1] == 0) {
        $res['error'] = true;
        $res['message'] = "Formato dni incorrecto" . $ok[0] . " " . $ok[1];
    } else if ($ok[0] == 8) {
        $res['error'] = true;
        $res['message'] = "La parte numérica dni incorrecto";
    } else if ($ok[1] == 8) {
        $res['error'] = true;
        $res['message'] = "La letra del dni está incorrecta";
    } else {
        $result = $conn->query("INSERT INTO `persona`(`id`, `dni`, `nombre`, `idDireccion`, `email`, `telefono`) VALUES  (null,'$dni','$nombre',$idDireccion, '$email', '$telefono')");
        if ($result) {
            $res['message'] = "Persona añadida con éxito" . $ok[0] . " " . $ok[1];
            $res['id'] = $conn->insert_id;
        } else {
            $res['error'] = true;
            $res['message'] = "La inserción Persona ha fallado: El dni o email ya existen";
        }
    }
}

//Tipo Usuario//////////////////////////////////////
if ($action == 'mostrarTipoUsuarios') {
    $result = $conn->query("SELECT * FROM `tipoUsuario`");
    $tipoUsuarios = array();

    while ($row = $result->fetch_assoc()) {
        array_push($tipoUsuarios, $row);
    }
    $res['tipoUsuarios'] = $tipoUsuarios;
}


//Usuario//////////////////////////////////////////////
if ($action == 'grabarUsuario') {
    $id = $_POST['id'];
    $idPersona = $_POST['idPersona'];
    $idTipoUsuario = $_POST['idTipoUsuario'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    $result = $conn->query("INSERT INTO `usuario`(`id`, `idPersona`, `idTipoUsuario`, `usuario`, `contraseña`) VALUES (null,$idPersona, $idTipoUsuario, '$usuario','$contraseña')");
    if ($result) {
        $res['message'] = "Usuario añadido con éxito";
        $res['id'] = $conn->insert_id;
    } else {
        $res['error'] = true;
        $res['message'] = "La inserción Usuario ha fallado";
    }
}

function comprobarDni($nif) {

    $ok = array($num, $let);
    $partes = explode('-', $nif);

    $numerosP = $partes[0];

    $numeros = substr($numerosP, -10, 2) . substr($numerosP, -7, 3) . substr($numerosP, -3, 3);
    if (is_numeric($numeros)) {

        $letra = strtoupper($partes[1]);

        if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1) == $letra) {
            if (substr($numerosP, -8, 1) === '.' && substr($numerosP, -4, 1) === '.') {
                $ok[0] = 1;
                $ok[1] = 1;
            } else {
                $ok[0] = 0;
            }
        } else {
            $ok[1] = 8;
        }
    } else {
        $ok[0] = 8;
        $ok[1] = 0;
    }
    return $ok;
}






$conn->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>
