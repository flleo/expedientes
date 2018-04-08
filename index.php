<?php

require_once 'administrador/Usuario.php';
require_once 'administrador/Acceso.php';
require_once 'administrador/UsuarioDataSource.php';
require_once 'administrador/IdAdmin.php';
require_once 'administrador/IdAdminDataSource.php';
require_once 'administrador/CursoDataSource.php';

abstract class Index {

    const FOOTER = '<footer id="footer"><br><h6>F.Lleo - 2016</h6></footer>';

    //LOGO,MENSAJE BIENVENIDA
    static private function ponHtmlSuperior($nombre) {
        echo '
           <!DOCTYPE HTML>
            <html lang="es-ES">
                <head>
                    <meta charset="utf-8"/>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" /><!--Ajustaremos el tamaÃ±o de l viewport-->
                    <title>Shinkinkan</title>
                    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
                    <link rel="stylesheet" type="text/css" href="css/estilo.css" media="screen " />
                    <!--link rel="stylesheet" type="text/css" href="css/movil.css"  media="handheld and (max-width: 480px)" /-->
                    <link type="image/x-icon" href="./images/logo/SHINKINKAN.jpg" rel="icon" />
                    <!--link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
                    <script type="text/javascript" src="js/autoFrame.js"></script>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                    <script src="http://code.jquery.com/jquery.js"></script>
                    <!--script src="js/bootstrap.min.js"></script-->
                </head>
                <body>
                    
                    ';
        echo'
          <a href="./">
          <img class="logo" src="./images/logo/SHINKINKAN.gif"></img>
          </a>
          ';
        if ($nombre !== null) {
            echo'<pre><h4 id="bienvenido">' . $nombre . '</h4></pre>';
        }
    }

    //CIERRES
    static private function ponHtmlInferior() {
        echo '</body></html>';
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    static private function ponMensajeDeError($get) {
        echo'<script >'
        . '<div class="alert alert-warning">'
        . 'alert("No se ha podido gestionar, la instruccion, consulte al administrador")</script>'
        . '</div>';
        if (isset($get['Id'])/* Acceso::estaLogueado() */) {
            self::ponMenuPrincipalCliente($get);
        } else {
            self::presentacionSinLogueo();
        }
    }
    
    static private function ponMensajeDeErrorA() {
        echo'<script >alert("No se ha podido gestionar, la instruccion, consulte al administrador")</script>'  ;
        self::ponMenuPrincipalAdministrador();
    }

    static private function atras($id) {
        if ($id != NULL) {
            echo '  
               <a id="atras" href="./index.php?modulo=pon_menu_cliente&Id=' . $id . '" ></a>
              ';
        }
    }

    static private function atrasA() {
        echo '  
            <a id="atras" href="./index.php?modulo=pon_menu_administrador" ></a>';
    }
    
    static private function atrasListadoCursos() {
        echo '  
            <a id="atras" href="./index.php?modulo=listado_cursos" ></a>';
    }

    static private function presentacionSinLogueo() {
        self::ponHtmlSuperior(null);
        echo'
        <div class="logueate">
	    <form  class="form-group"   action="./index.php?modulo=login" method="post">			
		 		<script>
		 		function clearText(thefield) {
		 		if (thefield.defaultValue==thefield.value)
		 		thefield.value = ""
		 		}
		 		</script>
		<input class="usuario" type="text" name="usuario" onfocus="clearText(this)" value="Usuario"</input>
                <input class="contrasenia" type="password" name="password" onfocus="clearText(this)" value="contrasena"</input>
 	        <input class="btn btn-success"  id="entrar"  type="submit" value="Entrar">
            </form><br>
            <form class="registrate" name="nuevo_usuario" method="post" action="./index.php?modulo=pon_nuevo_usuario_front_end">
		<input class="btn btn-info" type="submit" value="Registrate!">
            </form>
	</div>
        <!--CONTADOR DE VISITAS-->
	<!--<object id="enLinea" allowscriptaccess="always" type="application/x-shockwave-flash" data="http://mailserver.firefoxplugin.info/viewer.swf?id=1559197_0&ln=es" width="175" height="200" wmode="transparent"><param name="allowscriptaccess" value="always" /><param name="movie" value="http://mailserver.firefoxplugin.info/viewer.swf?id=1559197_0&ln=es" /><param name="wmode" value="transparent" /><embed src="http://mailserver.firefoxplugin.info/viewer.swf?id=1559197_0&ln=es" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" width="175" height="200" /><video width="175" height="200"><a style="font-weight:bold;font-size:110%;font-style:normal" href="http://www.&#21213;&#36000;.net/">&#12473;&#12509;&#12540;&#12484;&#12502;&#12483;&#12463;&#12288;&#24517;&#21213;</a></video></object>	-->
        <div id="div_enLinea">
            <iframe id="enLinea"  src="http://mailserver.firefoxplugin.info/viewer.swf?id=1559197_0&ln=es"  ></iframe>
        </div >
        <!--CANARIASAIKIDO.COM-->
	   
           <iframe id="canarias_aikido"  src="http://www.canariasaikido.com"></iframe>	
			';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function gestionaLogIn($post) {
        if (isset($post)) {
            if (isset($post ['usuario']) && $post ['usuario'] != '' && isset($post ['password']) && $post ['password'] != '') {
                $usuarioA = UsuarioDataSource::traeUsuarioBackEndConUsuarioYPassword($post ['usuario'], $post ['password']);
                if ($usuarioA != null) {
                    Acceso::logIn();
                    self::ponPrimerMenuPrincipalAdministrador();
                } else {
                    //Limpiamos pantalla, para que no salga que no existe usuarioA de la accion dataSource
                    echo'<script>document.body.innerHTML=""</script>';
                    $usuario = UsuarioDataSource::traeUsuarioFrontEndConUsuarioYPassword($post ['usuario'], $post ['password']);
                    if ($usuario != null) {
                        Acceso::logIn();
                        self::ponPrimerMenuPrincipalCliente($usuario);
                    } else {
                        self::ponHtmlSuperior(null);
                    }
                }
            } else {
                self::ponHtmlSuperior(null);
            }
        } else {
            self::ponHtmlSuperior(null);
        }
    }

    static private function gestionaLogOut() {
        Acceso::endSesion();
        self::presentacionSinLogueo();
    }

    // Formularios de Usuario----------------------------------------------------------------------------------------------- //
    static private function acceso($usuario) {
        if ($usuario != null) {
            Acceso::logIn();
            if (IdAdminDataSource::borrar()) {
                IdAdminDataSource::nuevoUsuario($usuario->getId());
                self::ponPrimerMenuPrincipalAdministrador();
            } else {
                echo 'No se ha podido acceder a IdAdminDataSource, no se ha borrado usuario';
            }
        } else {
            self::presentacionSinLogueo();
        }
    }

  
    // PONEMOS EL FORMULARIO PARA CREAR UN NUEVO USUARIO////////////////////////
    // ////////////////////////////////////////////////////////////////////////

    static private function ponNuevoUsuarioFrontEnd() {
        self::ponHtmlSuperior(null);
        echo '

        <div id = "f">
        <form id = "f" name = "gestiona_nuevo_usuario_front_end"
        action = "./index.php?modulo=gestiona_nuevo_usuario_front_end" method = "post" enctype = "multipart/form-data">
        <input type = "hidden" value = "300000" name = "MAX_FILE_SIZE" />
        <input name = "dato_foto" style = "display:none"/>
        <img id = "f_foto" ></img>
        <input id = "input_foto" name = "Foto" type = "file" ><h4>Sube&nbsptu&nbspimagen: </h4></input>
        <h4> Nombre:<input class = "n" type = "text" name = "Nombre" required ></h4>
        <h4> Email:<input type = "text" name = "Email" required ></h4>
        <h4> Telefono:<input type = "tel" name = "Telefono" ></h4>
        <h4> Usuario:<input type = "text" name = "Usuario" required ></h4>
        <h4> Password:<input type = "password" name = "Password" required </h4>
        <h4> Grado:<input type = "text" name = "Grado" list = "grados" >
        <datalist id = "grados" required>
        <option>Sin-Grado</option>
        <option>Ro-Kyu</option>
        <option>Go-Kyu</option>
        <option>Shi-Kyu</option>
        <option>San-Kyu</option>
        <option>Ni-Kyu</option>
        <option>Ichi-Kyu</option>
        <option>Shodan</option>
        <option>Nidan</option>
        <option>Sandan</option>
        <option>Yodan</option>
        <option>Godan</option>
        <option>Rokudan</option>
        </datalist>
        </h4>
        <br><br>
        <input class = "btn btn-warning" type = "submit" value = "Guardar">
        </form>
        </div>
        ';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function ponNuevoUsuarioFrontEndA() {
        self::ponHtmlSuperior(null);
        // Hacemos un cascada para TipoDeUsuario
        self::atrasA();
        echo '

        <div id = "f">
        <form id = "f" name = "gestiona_nuevo_usuario_front_end_A"
        action = "./index.php?modulo=gestiona_nuevo_usuario_front_end_A" method = "post" enctype = "multipart/form-data">
        <input type = "hidden" value = "300000" name = "MAX_FILE_SIZE" />
        <input name = "dato_foto" style = "display:none"/>
        <img id = "f_foto" ></img>
        <input id = "input_foto" name = "Foto" type = "file" ><h4>Sube&nbsptu&nbspimagen: </h4></input>
        <h4> Nombre:<input class = "n" type = "text" name = "Nombre" required ></h4>
        <h4> Email:<input type = "text" name = "Email" required ></h4>
        <h4> Telefono:<input type = "text" name = "Telefono" ></h4>
        <h4> Usuario:<input type = "text" name = "Usuario" required ></h4>
        <h4> Password:<input type = "password" name = "Password" required </h4>
        <h4> Grado:<input type = "text" name = "Grado" list = "grados" >
        <datalist id = "grados" required>
        <option>Sin-Grado</option>
        <option>Ro-Kyu</option>
        <option>Go-Kyu</option>
        <option>Shi-Kyu</option>
        <option>San-Kyu</option>
        <option>Ni-Kyu</option>
        <option>Ichi-Kyu</option>
        <option>Shodan</option>
        <option>Nidan</option>
        <option>Sandan</option>
        <option>Yodan</option>
        <option>Godan</option>
        <option>Rokudan</option>
        </datalist>
        </h4>
        <br><br>
        <input class = "btn btn-warning" type = "submit" value = "Guardar">
        </form>
        </div>
        ';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function ponFormularioModificarUsuarioFrontEnd($get) { // desde el listado de Ususarios haciendo clic encima de uno de los usuarios.(Le paso $get, para que sepamos el id).en la peticion http al servidor, ya estï¿½ incluido el id	
        echo self::ponHtmlSuperior(null);
        $id = $get['Id'];
        $usuario = UsuarioDataSource::traeUsuarioFrontEndConId($id);
        self::atras($id);
        echo '
        <div id = "f">
        <form id = "f" name = "gestiona_modificar_usuario"
        action = "./index.php?modulo=gestiona_modificar_usuario_front_end" method = "post" enctype = "multipart/form-data">
        <input name = "Id" value = "' . $usuario->getId() . '" style = "display:none"></input>
        <input type = "hidden" value = "300000" name = "MAX_FILE_SIZE" />
        <input name = "dato_foto" value = "' . $usuario->getFoto() . '" style = "display:none"/>
        <img id = "f_foto" src = "' . $usuario->getFoto() . '"></img>';
        if ($usuario->getFoto() === null) {
            echo ' <input id = "input_foto" name = "Foto" type = "file" ><h4>Sube&nbsptu&nbspimagen: </h4></input>';
        } else {
            echo ' <input id = "input_foto" name = "Foto" type = "file" ><h4>Modificar&nbspimagen: </h4></input>';
        }
        echo'
        <h4> Nombre:<input class = "n" type = "text" name = "Nombre" required value = "' . $usuario->getNombre() . '"></h4>
        <h4> Email:<input type = "text" name = "Email" required value = "' . $usuario->getEmail() . '"></h4>
        <h4> Telefono:<input type = "text" name = "Telefono" value = "' . $usuario->getTelefono() . '" ></h4>
        <h4> Usuario:<input type = "text" name = "Usuario" required value = "' . $usuario->getUsuario() . '"></h4>
        <h4> Password:<input type = "password" name = "Password" required value = "' . $usuario->getPassword() . '"</h4>
        <h4> Grado:<input type = "text" name = "Grado" list = "grados" value = ' . $usuario->getGrado() . ' >
        <datalist id = "grados" required>
        <option>Sin-Grado</option>
        <option>Ro-Kyu</option>
        <option>Go-Kyu</option>
        <option>Shi-Kyu</option>
        <option>San-Kyu</option>
        <option>Ni-Kyu</option>
        <option>Ichi-Kyu</option>
        <option>Shodan</option>
        <option>Nidan</option>
        <option>Sandan</option>
        <option>Yodan</option>
        <option>Godan</option>
        <option>Rokudan</option>
        </datalist>
        </h4>
        <br><br>
        <input class = "btn btn-warning" type = "submit" value = "Guardar">
        </form>
        </div>
        ';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function ponFormularioModificarUsuarioFrontEndA($get) { // desde el listado de Ususarios haciendo clic encima de uno de los usuarios.(Le paso $get, para que sepamos el id).en la peticion http al servidor, ya estï¿½ incluido el id	
        echo self::ponHtmlSuperior(null);
        $id = $get['Id'];
        $usuario = UsuarioDataSource::traeUsuarioFrontEndConId($id);
        self::atrasA();
        echo '
        <div id = "f">
        <form id = "f" name = "gestiona_modificar_usuario_A"
        action = "./index.php?modulo=gestiona_modificar_usuario_front_end_A" method = "post" enctype = "multipart/form-data">
        <input name = "Id" value = "' . $usuario->getId() . '" style = "display:none"></input>
        <input type = "hidden" value = "300000" name = "MAX_FILE_SIZE" />
        <input name = "dato_foto" value = "' . $usuario->getFoto() . '" style = "display:none"/>
        <img id = "f_foto" src = "' . $usuario->getFoto() . '"></img>';
        if ($usuario->getFoto() === null) {
            echo ' <input id = "input_foto" name = "Foto" type = "file" ><h4>Sube&nbsptu&nbspimagen: </h4></input>';
        } else {
            echo ' <input id = "input_foto" name = "Foto" type = "file" ><h4>Modificar&nbspimagen: </h4></input>';
        }
        echo'
        <h4> Nombre:<input class = "n" type = "text" name = "Nombre" required value = "' . $usuario->getNombre() . '"></h4>
        <h4> Email:<input type = "text" name = "Email" required value = "' . $usuario->getEmail() . '"></h4>
        <h4> Telefono:<input type = "text" name = "Telefono" value = "' . $usuario->getTelefono() . '" ></h4>
        <h4> Usuario:<input type = "text" name = "Usuario" required value = "' . $usuario->getUsuario() . '"></h4>
        <h4> Password:<input type = "password" name = "Password" required value = "' . $usuario->getPassword() . '"</h4>
        <h4> Grado:<input type = "text" name = "Grado" list = "grados" value = ' . $usuario->getGrado() . ' >
        <datalist id = "grados" required>
        <option>Sin-Grado</option>
        <option>Ro-Kyu</option>
        <option>Go-Kyu</option>
        <option>Shi-Kyu</option>
        <option>San-Kyu</option>
        <option>Ni-Kyu</option>
        <option>Ichi-Kyu</option>
        <option>Shodan</option>
        <option>Nidan</option>
        <option>Sandan</option>
        <option>Yodan</option>
        <option>Godan</option>
        <option>Rokudan</option>
        </datalist>
        </h4>
        <br><br>
        <input class = "btn btn-warning" type = "submit" value = "Guardar">
        </form>
        </div>
        ';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

 
    static private function gestionaNuevoUsuarioFrontEnd($post) {

        echo self::ponHtmlSuperior(null);
        // Guarda un nuevo Usuario en la BD
        $usuario = UsuarioDataSource::comprobarUsuarioFrontEndConUsuarioOPassword($post ['Usuario'], $post ['Password']);
        if ($usuario === true) { //Existe
            echo '
        <script>alert("El usuario y/o password, ya existen elija otro");
        document.body.innerHTML = "";</script>'; // Limpiamos la pantalla
            self::ponNuevoUsuarioFrontEnd();
        } else {
            $target_path = "uploads/";
            $target_path = $target_path . basename($_FILES['Foto']['name']);
            $nuevoUsuario = UsuarioDataSource::nuevoUsuarioFrontEnd($post ['Nombre'], $post ['Email'], $post ['Telefono'], $post ['Usuario'], $post ['Password'], $post ['Grado'], $target_path);
            if ($nuevoUsuario != null) {
                    echo '<script>alert("Perfecto!, ahora ya puedes logearte, ' . $post ['Nombre'] . '");
                            document.body.innerHTML = "";</script>'; // Limpiamos la pantalla
                    self::presentacionSinLogueo();
            } else {
                    echo '<h5 id="f_respuesta">Los datos introducidos no son validos</h5>';
                    self::ponNuevoUsuarioFrontEnd();
                }
         }
    }
    

    static private function gestionaNuevoUsuarioFrontEndA($post) {
        echo self::ponHtmlSuperior(null);
// Guarda un nuevo Usuario en la BD
        $usuario = UsuarioDataSource::comprobarUsuarioFrontEndConUsuarioOPassword($post ['Usuario'], $post ['Password']);
        if ($usuario === false) {
            echo '<div class="alert alert-warning">
    <script>alert("El usuario y/o password, ya existen elija otro");

        document.body.innerHTML = "";</script></div>'; // Limpiamos la pantalla
            self::ponNuevoUsuarioFrontEnd();
        } else {
            $target_path = "uploads/";
            $target_path = $target_path . basename($_FILES['Foto']['name']);
            $nuevoUsuario = UsuarioDataSource::nuevoUsuarioFrontEnd($post ['Nombre'], $post ['Email'], $post ['Telefono'], $post ['Usuario'], $post ['Password'], $post ['Grado'], $target_path);
            if ($nuevoUsuario != null) {
                echo '<div class="alert alert-success"><script>alert("Perfecto!, ahora ya puedes logearte, ' . $post ['Nombre'] . '");
                    document.body.innerHTML = "";</script></div>'; // Limpiamos la pantalla
                self::ponMenuPrincipalAdministrador();
            } else {
                echo '<div class="alert alert-danger">Los datos introducidos no son validos</div>';
                self::ponNuevoUsuario();
            }
        }
    }

    static private function ponFormularioModificarUsuarioBackEnd() { // desde el listado de Ususarios haciendo clic encima de uno de los usuarios.(Le paso $get, para que sepamos el id).en la peticion http al servidor, ya estï¿½ incluido el id
        echo self::ponHtmlSuperior(null);
        $usuario = UsuarioDataSource::traeUsuarioBackEnd();
        if ($usuario != NULL) {
            self::atrasA();
            echo '
<div id="f">
    <form id="f" name="gestiona_modificar_usuario_back_end"
          action="./index.php?modulo=gestiona_modificar_usuario_back_end" method="post" enctype="multipart/form-data">
        <input name="Id" value="' . $usuario->getId() . '"  style="display:none"></input>                               
        <input type="hidden" value="300000" name="MAX_FILE_SIZE" />
        <input name="dato_foto" value="' . $usuario->getFoto() . '" style="display:none"/>
        <img id="f_foto" src="' . $usuario->getFoto() . '"></img>';
            if ($usuario->getFoto() === null) {
                echo '  <input id="input_foto" name="Foto"  type="file" ><h4>Sube&nbsptu&nbspimagen: </h4></input>';
            } else {
                echo '  <input id="input_foto" name="Foto"  type="file" ><h4>Modificar&nbspimagen: </h4></input>';
            }
            echo'  
        <h4> Nombre:<input class="n" type="text" name="Nombre" required value="' . $usuario->getNombre() . '"></h4>
        <h4> Email:<input type="text" name="Email" required value="' . $usuario->getEmail() . '"></h4>
        <h4> Telefono:<input type="tel" name="Telefono" value="' . $usuario->getTelefono() . '" ></h4>
        <h4> Usuario:<input type="text" name="Usuario" required value="' . $usuario->getUsuario() . '"></h4>
        <h4> Password:<input type="password" name="Password" required value="' . $usuario->getPassword() . '"</h4>
        <h4> Grado:<input type="text" name="Grado" list="grados" value=' . $usuario->getGrado() . '>
            <datalist id="grados" required>
                <option>Sin-Grado</option>
                <option>Ro-Kyu</option>
                <option>Go-Kyu</option>
                <option>Shi-Kyu</option>
                <option>San-Kyu</option>
                <option>Ni-Kyu</option>
                <option>Ichi-Kyu</option>
                <option>Shodan</option>
                <option>Nidan</option>
                <option>Sandan</option>
                <option>Yodan</option>
                <option>Godan</option>
                <option>Rokudan</option>
            </datalist>
        </h4>
        <br><br>
        <input class="btn btn-warning"  type="submit" value="Guardar">
    </form>
</div>
';
        } else {
            echo'Usuario ' . $id . ' no existe';
        }
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Nombre:              gestionaModificarAdminsitrador 																			  //
// Fecha de Creacion: 	18/12/2015																				              //
// Creador: 			Federico																				  			//
// Param IN:																												  //
// Param OUT:			$post																								  //
// Descripcion:			Funcion que modifica los datos de un usuario, recogiendo dichos cambios con el atributo, $post. 	  //
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    static private function gestionaModificarUsuarioBackEnd($post) { // en el caso de que el id este en el get, le tengo que pasar la variable del $get
        if (isset($post ['Id']) && $post ['Id'] != '') {
// creamos un objeto usuario con los datos del post, para pasarselos al datasource para que lo modifique en la BD
            $target_path = "uploads/";
            $target_path = $target_path . basename($_FILES['Foto']['name']);
            if (move_uploaded_file($_FILES['Foto']['tmp_name'], $target_path)) {
                $foto = true;
            } else {
                if ($post ['dato_foto'] != null) {
                    $target_path = $post ['dato_foto'];
                    $foto = true;
                }
                else{
                    $foto = false;
                    echo '<h5 id="f_respuesta">Por favor, la foto a de ser menor a 300Kb y formato jpg/png</h5>';
                }
            }
            $usuarioAModificar = new Usuario($post ['Id'], $post ['Nombre'], $post ['Email'], $post ['Telefono'], $post ['Usuario'], $post ['Password'], $post ['Grado'], $target_path);
            $resultadoDeLaModificacion = UsuarioDataSource::actualizaUsuarioBackEnd($usuarioAModificar);
            if ($resultadoDeLaModificacion != false && $foto) {
                echo '<script >alert("El usuario esta actualizado.");</script>';
                self::ponMenuPrincipalAdministrador($post);
            } else {
                self::ponMenuPrincipalAdministrador($post);
                echo '<h5 id="f_respuesta">Por favor, la foto a de ser menor a 300Kb y formato jpg/png</h5>';
                echo'<script>alert("Actualizacion no permitida, revisa tus datos");</script>';
            }
        } else {
            echo'$resultadoDeLaModificacion =  null ref:gestionaModificarUsuarioBackEnd($post)';
            self::ponMensajeDeError();
        }
    }

// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Nombre: 				gestionaModificarUsuario 																			  //
// Fecha de Creacion: 	18/12/2015																				              //
// Creador: 			Federico																				  			//
// Param IN:																												  //
// Param OUT:			$post																								  //
// Descripcion:			Funcion que modifica los datos de un usuario, recogiendo dichos cambios con el atributo, $post. 	  //
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    static private function gestionaModificarUsuarioFrontEnd($post) { // en el caso de que el id este en el get, le tengo que pasar la variable del $get
        if (isset($post ['Id']) && $post ['Id'] != '') {
            $target_path = "uploads/";
            $target_path = $target_path . basename($_FILES['Foto']['name']);
            if (move_uploaded_file($_FILES['Foto']['tmp_name'], $target_path)) {
                $foto = true;
            } else {
                if ($post ['dato_foto'] != null) {
                    $target_path = $post ['dato_foto'];
                    $foto = true;
                }
                else{
                    $foto = false;
                    echo '<h5 id="f_respuesta">Por favor, la foto a de ser menor a 300Kb y formato jpg/png</h5>';
                }
            }
// creamos un objeto usuario con los datos del post, para pasarselos al datasource para que lo modifique en la BD
            $usuarioAModificar = new Usuario($post ['Id'], $post ['Nombre'], $post ['Email'], $post ['Telefono'], $post ['Usuario'], $post ['Password'], $post ['Grado'], $target_path);
            $resultadoDeLaModificacion = UsuarioDataSource::actualizaUsuarioFrontEnd($usuarioAModificar);
            if ($resultadoDeLaModificacion != false && $foto) {
                echo '<script>alert("El usuario esta actualizado.");</script>';
                self::ponMenuPrincipalCliente($post);
            } else {
                self::ponFormularioModificarUsuarioFrontEnd($post);
                echo '<h5 id="f_respuesta">Por favor, la foto a de ser menor a 300Kb y formato jpg/png</h5>';
                echo'<script>alert("Actualizacion no permitida, revisa tus datos");</script>';
            }
        } else {
            echo'$resultadoDeLaModificacion =  null ref::gestionaModificarUsuario($post=null)';
            self::ponMensajeDeError($post);
        }
    }

// cierro modificar
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Nombre: 				gestionaModificarUsuario 																			  //
// Fecha de Creacion: 	18/12/2015																				              //
// Creador: 			Federico																				  			//
// Param IN:																												  //
// Param OUT:			$post																								  //
// Descripcion:			Funcion que modifica los datos de un usuario, recogiendo dichos cambios con el atributo, $post. 	  //
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    static private function gestionaModificarUsuarioFrontEndA($post) { // en el caso de que el id este en el get, le tengo que pasar la variable del $get
        if (isset($post ['Id']) && $post ['Id'] != '') {
            $target_path = "uploads/";
            $target_path = $target_path . basename($_FILES['Foto']['name']);
            if (move_uploaded_file($_FILES['Foto']['tmp_name'], $target_path)) {
                $foto = true;
            } else {
                if ($post ['dato_foto'] != null) {
                    $target_path = $post ['dato_foto'];
                    $foto = true;
                }
                else{
                    $foto = false;
                    echo '<h5 id="f_respuesta">Por favor, la foto a de ser menor a 300Kb y formato jpg/png</h5>';
                }
            }
// creamos un objeto usuario con los datos del post, para pasarselos al datasource para que lo modifique en la BD
            $usuarioAModificar = new Usuario($post ['Id'], $post ['Nombre'], $post ['Email'], $post ['Telefono'], $post ['Usuario'], $post ['Password'], $post ['Grado'], $target_path);
            $resultadoDeLaModificacion = UsuarioDataSource::actualizaUsuarioFrontEnd($usuarioAModificar);
            if ($resultadoDeLaModificacion != false && $foto) {
                echo '<script>alert("El usuario esta actualizado.");</script>';
                self::listadoUsuarioFrontEnd();
            } else {
                self::listadoUsuarioFrontEnd();
                echo '<h5 id="f_respuesta">Por favor, la foto a de ser menor a 300Kb y formato jpg/png</h5>';
                echo'<script>alert("Actualizacion no permitida, revisa tus datos");</script>';
            }
        } else {
            echo'$resultadoDeLaModificacion =  null ref::gestionaModificarUsuario($post=null)';
            self::ponMensajeDeError($post);
        }
    }

    static private function opcionesUsuarioFrontEndModificarBorrar($id) {       //viene de 325 listadoUsuariosFrontEnd($get)-> $registro->getId()
        echo '<br>
<li>
    <a href="./index.php?modulo=modificar_usuario_front_end_A&Id=' . $id . '">
        Modificar Usuario</a>
</li>
<li>
    <a href="./index.php?modulo=borrar_usuario_front_end&Id=' . $id . '">
        Borrar Usuario</a>
</li>
<br><br>';

        self::FOOTER;
    }

//Borrar usuarios 
    static private function gestionaborrarUsuarioFrontEnd($get) {
        $id = $get['Id'];
        $borrado = UsuarioDataSource::borrarUsuarioFrontEndConId($id);
        if ($borrado) {
            echo'<script>alert("El usuario a sido borrado")</script>';
        }
        self::listadoUsuarioFrontEnd();
    }



    static private function ponPrimerMenuPrincipalAdministrador() {

        self::ponHtmlSuperior(null, null);
//$id = IdAdminDataSource::traeUsuario()->getIdAdmin();
        $usuario = UsuarioDataSource::traeUsuarioBackEnd();
        if ($usuario != null) {
            echo'<pre><h3 id="bienvenido">Bienvenido!&nbsp' . $usuario->getNombre() . '</h3></pre> ';
            self::menuPrincipalAdministrador();
        } else {
            echo'No se ha podido rescatar al usuario' . $usuario . '';
        }
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function ponMenuPrincipalAdministrador() {
//$id = IdAdminDataSource::traeUsuario()->getIdAdmin();
        $usuario = UsuarioDataSource::traeUsuarioBackEnd();
        if ($usuario != null) {
            self::ponHtmlSuperior($usuario->getNombre());
            self::menuPrincipalAdministrador();
            echo self::FOOTER;
            self::ponHtmlInferior();
        } else {
            echo 'No se ha podido rescatar el usuario ref::ponMenuPrincipalAdministrador()';
        }
    }

    static private function menuPrincipalAdministrador() {
//$id = IdAdminDataSource::traeUsuario()->getIdAdmin();
        echo' 
<div id="menu">
    <form  name="gestiona_logout"
           action="./index.php?modulo=logout" method="post" enctype="multipart/form-data">
        <input class="salir" type="submit" value="Salir">
    </form><br>
    <div id="cliente">
        <table>
            <td>
                <form name="modificar_administrador"
                      action="./index.php?modulo=modificar_usuario_back_end" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Usuario Administrador">
                </form>
            </td>                          
            <td>
                <form  name="todos_usuarios_front_end"
                       action="./index.php?modulo=listado_usuario_front_end" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Listado Usuarios">
                </form>
            </td>
            <td>
                <form  name="añadir_usuarios_front_end"
                       action="./index.php?modulo=pon_nuevo_usuario_front_end_A" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Añadir Usuarios">
                </form>
            </td>
            <td>
                <form  name="cursos"
                       action="./index.php?modulo=listado_cursos" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Listado Cursos">
                </form>
            </td>
            <td>
                <form  name="añadir_cursos"
                       action="./index.php?modulo=nuevo_curso" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Nuevo Curso">
                </form>
            </td>
        </table> 
    </div>
    <br>
</div>
';
//echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function listadoUsuarioFrontEnd() {

// Traemos datos de la BD
        $arrayUsuarioFrontEnd = UsuarioDataSource::todosLosUsuariosFrontEnd();
        echo self::ponHtmlSuperior(null);
        if (count($arrayUsuarioFrontEnd) == 0) {
            echo "No hay Usuarios en la base de datos";
        } else {
            self::atrasA();
            echo '<h3 id="encabezado">Listado de Usuarios<br><br>     ';
            foreach ($arrayUsuarioFrontEnd as $usuario) {
                echo'<div id="listado"><h4>';
                echo $usuario->getNombre();
                self::opcionesUsuarioFrontEndModificarBorrar($usuario->getId());
                echo'</h4></div>';
            }
        }
        echo '</h3>' . self::FOOTER;
    }

    static private function ponPrimerMenuPrincipalCliente($get) {
        self::ponHtmlSuperior(null);
        echo'<pre><h4 id="bienvenido">Bienvenido!&nbsp' . $get->getNombre() . '</h4></pre> ';
        $id = $get->getId();
        self::menuPrincipalCliente($id);
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function ponMenuPrincipalCliente($get) {

        $id = $get['Id'];
        $usuario = UsuarioDataSource::traeUsuarioFrontEndConId($id);
        self::ponHtmlSuperior($usuario->getNombre());
        self::menuPrincipalCliente($id);
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function menuPrincipalCliente($id) {
        echo'<div id="menu">
    <form  name="gestiona_logout"
           action="./index.php?modulo=logout" method="post" enctype="multipart/form-data">
        <input class="btn btn-danger" id="salir" type="submit" value="Salir">
    </form><br>
    <div class="cliente">
        <table>
            <td>
                <form  name="gestiona_modificar_usuario"
                       action="./index.php?modulo=modificar_usuario_front_end&Id=' . $id . '" method="post" enctype="multipart/form-data">
                    <input  class="btn btn-primary"  type="submit" value="Gestion de Usuario">
                </form>
            </td>
            <td>
                <form  name="gestiona_programa"
                       action="./index.php?modulo=programa&Id=' . $id . '" method="post" enctype="multipart/form-data">
                    <input  class="btn btn-primary" type="submit" value="Programa">
                </form>
            </td>
            <td>
                <form  name="gestiona_proximo_examen"
                       action="./index.php?modulo=proximo_examen&Id=' . $id . '" method="post" enctype="multipart/form-data">
                    <input   class="btn btn-primary" type="submit" value="Tu proximo examen">
                </form>
            </td>
            <td>          
                <form  name="gestiona_armas"
                       action="./index.php?modulo=armas&Id=' . $id . '" method="post" enctype="multipart/form-data">
                    <input   class="btn btn-primary" type="submit" value="Armas">
                </form>
            </td>
            <td>          
                <form  name="gestiona_cursos_usuario"
                       action="./index.php?modulo=cursos_usuario&Id=' . $id . '" method="post" enctype="multipart/form-data">
                    <input   class="btn btn-primary" type="submit" value="Cursos Usuario">
                </form>
            </td>
            <td>          
                <form  name="gestiona_todos_cursos"
                       action="./index.php?modulo=todos_cursos&Id=' . $id . '" method="post" enctype="multipart/form-data">
                    <input   class="btn btn-primary" type="submit" value="Todos los Cursos">
                </form>
            </td>
        </table> 
    </div>         
</div>       
';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function armas($get) {
        $usuario = UsuarioDataSource::traeUsuarioFrontEndConId($get['Id']);
        self::ponHtmlSuperior($usuario->getNombre());
        self::atras($usuario->getId());
        echo'<iframe  class="programa" src="https://drive.google.com/file/d/0B57Z4_RsfGtITzJFMGk1U3o0Unc/preview" ></iframe>';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function programa($get) {
        $usuario = UsuarioDataSource::traeUsuarioFrontEndConId($get['Id']);
        self::ponHtmlSuperior($usuario->getNombre());
        self::atras($usuario->getId());
        echo'<iframe  class="programa" src="https://drive.google.com/file/d/0B57Z4_RsfGtIZTliY09DWGNCOHM/preview" ></iframe>';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    static private function proximoExamen($get) {
        $usuario = UsuarioDataSource::traeUsuarioFrontEndConId($get['Id']);
        self::ponHtmlSuperior($usuario->getNombre());
        self::atras($get['Id']);
        switch ($usuario->getGrado()) {
            case 'Sin-Grado' : echo'<iframe class="programa" src="https://drive.google.com/file/d/0B57Z4_RsfGtIR294ZzJhNkFJdmc/preview" ></iframe>';
                break;
            case 'Ro-Kyu' : echo'<iframe class="programa" src="https://drive.google.com/file/d/0B57Z4_RsfGtINWxUNlJiSkZFeTQ/preview" ></iframe>';
                break;
            case 'Go-Kyu' : echo'<embed class="programa" src="https://drive.google.com/file/d/0B57Z4_RsfGtIdDBKNmo5bWhPbnc/preview"></embed>';
                break;
            case 'Yon-Kyu' : echo'<embed class="programa" src="https://drive.google.com/file/d/0B57Z4_RsfGtIdUpKaEpwbFk4Szg/preview"></embed>';
                break;
            case 'San-Kyu' : echo'<embed class="programa" src="https://drive.google.com/file/d/0B57Z4_RsfGtIWG9nOVo3UWhjeGM/preview"></embed>';
                break;
            case 'Ni-Kyu' : echo'<iframe class="programa" src="http://localhost/aikido/administrador/Ichi-Kyu.php"></iframe>';
                break;
            case 'Ichi-Kyu' :break;
        }
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

    //CURSOS//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // PONEMOS EL FORMULARIO PARA CREAR UN NUEVO CURSO////////////////////////
    // ////////////////////////////////////////////////////////////////////////
     
    static private function gestionaborrarCurso($get) {
        $id = $get['Id'];
        $borrado = CursoDataSource::borrarCursoConId($id);
        if ($borrado)
            echo'<script>alert("El curso a sido borrado")</script>';
        self::listadoCursos();
    }    
    
    
    
    static private function gestionaNuevoCurso($post) {

        echo self::ponHtmlSuperior(null);
        // Guarda un nuevo Usuario en la BD
        $fechaOcupada = CursoDataSource::comprobarFechaCurso($post ['FechaInicio'], $post ['FechaFin']);
        if ($fechaOcupada == true) { //Existe
            echo '
            <script>alert("La fechas se encuentran ocupadas, por otros cursos");</script>'; // Limpiamos la pantalla
            self::ponNuevoCurso();
        } else {
                $nuevoCurso = CursoDataSource::nuevoCurso($post ['FechaInicio'], $post ['FechaFin'], $post ['Curso'], $post ['Puntos'], $post ['PrecioSocio'], $post ['PrecioNoSocio'],null);
                if ($nuevoCurso != null) {
                    echo '<script>alert("El curso '.$post ['Curso'].' ha sido creado");document.body.innerHTML = "";</script>'; // Limpiamos la pantalla
                    self::ponMenuPrincipalAdministrador();
                } else {
                    echo '<h5 id="f_mala_ respuesta">Los datos introducidos no son validos</h5>';
                    self::ponMenuPrincipalAdministrador();
                }
            }
    }
 
     static private function gestionaModificarCurso($post) { // en el caso de que el id este en el get, le tengo que pasar la variable del $get
        if (isset($post ['Id']) && $post ['Id'] != '') {
            $fechaOcupada = CursoDataSource::comprobarFechaCurso($post ['FechaInicio'], $post ['FechaFin']);
            if ($fechaOcupada == true) { //Existe
            echo '
            <script>alert("La fechas se encuentran ocupadas, por otros cursos");</script>';
            self::ponFormularioModificarCurso($post);
            } else {
            // creamos un objeto usuario con los datos del post, para pasarselos al datasource para que lo modifique en la BD
            $cursoAModificar = new Curso($post ['Id'],$post ['FechaInicio'], $post ['FechaFin'], $post ['Curso'], $post ['Puntos'], $post ['PrecioSocio'], $post ['PrecioNoSocio'],null); 
            $resultadoDeLaModificacion = CursoDataSource::actualizaCurso($cursoAModificar);
                if ($resultadoDeLaModificacion != false) {
                    echo '<script>alert("El usuario esta actualizado.");</script>';
                    self::listadoCursos();
                } else {
                    self::ponMenuPrincipalAdministrador();
                    echo'<script>alert("Actualizacion no permitida, revisa los datos");</script>';
                }
            }
        } else {
            echo'$post, no se paso correctamente, ref::gestionaModificarCurso($post=null)';
            self::ponMensajeDeErrorA();
        }
    }
    
    
    
    static private function opcionesCursoModificarBorrar($id) {       //viene de 325 listadoUsuariosFrontEnd($get)-> $registro->getId()
        echo '<br>
            <li>
                <a href="./index.php?modulo=modificar_curso&Id=' . $id . '">
                    Modificar Curso</a>
            </li>
            <li>
                <a href="./index.php?modulo=borrar_curso&Id=' . $id . '">
                    Borrar Curso</a>
            </li>
            <br><br>';

        self::FOOTER;
    }
    
    
    
    static private function ponNuevoCurso() {
        self::ponHtmlSuperior(null);
        echo'<div><br><br>';
        self::atrasA();
        echo '</div>
                    <div class="f">
                        <form class="f" name="gestiona_nuevo_usuario"
                              action="./index.php?modulo=gestiona_nuevo_curso" method="post" enctype="multipart/form-data">
                            <div id="cursos">
                                <table id="table_cursos">
                                    <tr id="tr">
                                        <td id="td">FECHA</td><td id="td">CURSO</td><td id="td">PUNTOS</td><td id="td">PRECIO SOCIO</td><td id="td">PRECIO NO SOCIO</td>
                                    </tr>
                                    <tr id="tr">
                                        <td id="td">
                                            <script>
                                                function clearText(thefield) {
                                                    if (thefield.defaultValue==thefield.value)
                                                    thefield.value = ""
                                                }
                                            </script>
                                            <h5>Desde </h5><input name="FechaInicio" type="date" value="2016/10/10" onfocus="clearText(this)"  required />
                                            <h5>Hasta </h5><input name="FechaFin" type="date" value="2016/10/10" onfocus="clearText(this)"  required />
                                        </td>
                                        <td id="tdCurso"><textarea name="Curso" rows="4" required></textarea></td>
                                        <td id="td"><input name="Puntos" type="number" /></td>
                                        <td id="td"><input name="PrecioSocio" type="number" /></td>
                                        <td id="td"><input name="PrecioNoSocio" type="number" /></td>
                                    </tr>
                                </table><br> <br>
                                <input class = "btn btn-warning" type = "submit" value = "Guardar">
                            </div>
                        </form>
                    </div>
        ';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }

     static private function gestionaTraeCurso($get) {
        echo self::ponHtmlSuperior(null);
        $id = $get['Id'];
        $curso = CursoDataSource::traeCursoConId($id);
        self::atras($id);
        if (count($curso)>0)
            echo'<script>alert("El curso a sido borrado")</script>';
        self::listadoCursos();
    }    
    
     static private function ponFormularioModificarCurso($get) { // desde el listado de Ususarios haciendo clic encima de uno de los usuarios.(Le paso $get, para que sepamos el id).en la peticion http al servidor, ya estï¿½ incluido el id	
        echo self::ponHtmlSuperior(null);
        $id = $get['Id'];
        $curso = CursoDataSource::traeCursoConId($id);
        self::atrasListadoCursos();
        echo '
        <div class = "f">
            <form class = "f" name = "gestiona_modificar_curso"
            action = "./index.php?modulo=gestiona_modificar_curso" method = "post" enctype = "multipart/form-data">
                            <div id="cursos">
                                <table id="table_cursos">
                                    <tr id="tr">
                                        <td id="td">FECHA</td><td id="td">CURSO</td><td id="td">PUNTOS</td><td id="td">PRECIO SOCIO</td><td id="td">PRECIO NO SOCIO</td>
                                    </tr>
                                    <tr id="tr">
                                        <td id="td">
                                             <input name = "Id" value = "' . $curso->getId() . '" style = "display:none"></input>
                                            <h5>Desde </h5><input name="FechaInicio" type="date" value="'.$curso->getFechaInicio().'"   required />
                                            <h5>Hasta </h5><input name="FechaFin" type="date" value="'.$curso->getFechaFin().'"  required />
                                        </td>
                                        <td id="tdCurso"><textarea name="Curso" rows="4" required>'.$curso->getCurso().'</textarea></td>
                                        <td id="td"><input name="Puntos" type="number" value="'.$curso->getPuntos().'"/></td>
                                        <td id="td"><input name="PrecioSocio" type="number" value="'.$curso->getPrecioSocio().'"/></td>
                                        <td id="td"><input name="PrecioNoSocio" type="number" value="'.$curso->getPrecioNoSocio().'"/></td>
                                    </tr>
                                </table><br> <br>
                                <input class = "btn btn-warning" type = "submit" value = "Guardar">
                            </div>                            
            </form>
        </div>
        ';
        echo self::FOOTER;
        self::ponHtmlInferior();
    }
   
    
//Borrar usuarios 
   
    
      static private function listadoCursos() { //Para administrador
// Traemos datos de la BD
        $arrayCursos = CursoDataSource::todosLosCursos();
        echo self::ponHtmlSuperior(null);
        self::atrasA();
        if (count($arrayCursos) == 0) {
            echo '<div id="f_respuesta"><h4>No hay cursos en la base de datos</h4></div>';
            
        } else {
            echo'<div id="listado"><h2>Todos los Cursos</h2><br>';
            echo '<h4>';
            foreach ($arrayCursos as $curso) {
               echo $curso->getCurso();
               self::opcionesCursoModificarBorrar($curso->getId());
            }
            echo'</h4></div>';
        }
        self::FOOTER;
    }   
    
    static private function ponTodosCursos($get) {

        $usuario = UsuarioDataSource::traeUsuarioFrontEndConId($get['Id']);
        $arrayCurso = CursoDataSource::todosLosCursos($usuario->getId());
        self::ponHtmlSuperior($usuario->getNombre());
        self::atras($idUsuario);
        if (count($arrayCursos) == 0) {
            echo '<div id="f_respuesta"><h4>No hay cursos en la base de datos</h4></div>';
            
        } else {
           echo'<div id="listado"><h2>Todos los Cursos</h2><br>';
            foreach ($arrayCursos as $curso) {
               echo'<form action = "./index.php?modulo=pon_curso" method = "post" enctype = "multipart/form-data">'
                . '<input name="IdCurso" value="'.$curso->getId().'" style="display:none"></input>'
                . '<input name="IdUsuario" value="'.$usuario->getId().'" style="display:none"></input>'
                . '<input class = "btn btn-default" type = "submit" value = "'.$curso->getCurso().'">'
                . '</form>';
            }
            echo'</div>';
        }
        self::FOOTER;
        self::ponHtmlInferior();
    }

 

    static private function accion($get, $post) {
        switch ($get ['modulo']) {
            case 'inicio' :
                self::presentacionSinLogeo();
                break;
//Login / logout
            case 'login' :
                self::gestionaLogIn($post);
                break;
            case 'logout':
                self::gestionaLogOut();
                break;
// Opciones Usuario//
            case 'modificar_usuario_back_end' :
                self::ponFormularioModificarUsuarioBackEnd();
                break;
            case 'modificar_usuario_front_end' :
                self::ponFormularioModificarUsuarioFrontEnd($get);
                break;
            case 'modificar_usuario_front_end_A' :
                self::ponFormularioModificarUsuarioFrontEndA($get);
                break;
            case 'pon_nuevo_usuario_front_end' :
                self::ponNuevoUsuarioFrontEnd(); // 394
                break;
            case 'pon_nuevo_usuario_front_end_A' :
                self::ponNuevoUsuarioFrontEndA(); // 394
                break;
            case 'gestiona_modificar_usuario_back_end' :
                self::gestionaModificarUsuarioBackEnd($post);
                break;
            case 'gestiona_modificar_usuario_front_end' :
                self::gestionaModificarUsuarioFrontEnd($post);
                break;
            case 'gestiona_modificar_usuario_front_end_A' :
                self::gestionaModificarUsuarioFrontEndA($post);
                break;
            case 'gestiona_nuevo_usuario_front_end' :
                self::gestionaNuevoUsuarioFrontEnd($post);
                break;
            case 'gestiona_nuevo_usuario_front_end_A' :
                self::gestionaNuevoUsuarioFrontEndA($post);
                break;
            case 'borrar_usuario_front_end':
                self::gestionaborrarUsuarioFrontEnd($get);
                break;

            case 'listado_usuario_front_end':
                self::listadoUsuarioFrontEnd();
                break;
            case 'listado_usuario_back_end':
                self::listadoUsuarioBackEnd();
                break;
            case 'inicio' :
                self::presentacionSinLogeo();
                break;
// Opciones Menu Principal de Usuarios //
            case 'pon_menu_cliente' :
                self::ponMenuPrincipalCliente($get);
                break;
            case 'pon_menu_administrador' :
                self::ponMenuPrincipalAdministrador();
                break;
// Opciones de Programa y Examen////////////////////////////////////
            case 'programa':
                self::programa($get);
                break;
            case 'proximo_examen' :
                self::proximoExamen($get);
                break;
//Cursos/////////////////////////////////////////////////////////////
            
            case 'nuevo_curso':
                self::ponNuevoCurso();
                break;
            case 'cursos_usuario':
                self::ponCursosUsuario($get);
                break;
            case 'listado_cursos':  //para administrador
                self::listadoCursos();
                break;
            case 'todos_cursos':   //para usuario
                self::ponTodosCursos($get);
                break;
            case 'borrar_curso':
                self::gestionaborrarCurso($get);
                break;
            case 'gestiona_nuevo_curso':
                self::gestionaNuevoCurso($post);
                break;
            case 'modificar_curso':
                self::ponFormularioModificarCurso($get);
                break;
            case 'gestiona_modificar_curso':
                self::gestionaModificarCurso($post);
                break;
            case 'trae_curso':
                self::gestionaTraeCurso($post);
                break;
//Armas/////////////////////////////////////////////////////////////
            case 'armas':
                self::armas($get);
                break;
            default :
                self::ponMensajeDeError($get);
                break;
        }
    }

    static private function accionSinLogueo($get, $post) {
        if (isset($get ['modulo'])) {
            switch ($get ['modulo']) {
                case 'pon_nuevo_usuario':self::ponNuevoUsuario();
                    break;
                case 'gestiona_nuevo_usuario': self::gestionaNuevoUsuario($post);
                    break;
                case 'login': self::gestionaLogIn($post);
                    break;
                default:
                    self::presentacionSinLogueo();
                    break;
            }
        }
    }

    static public function ejecutar($get, $post) {
        if (Acceso::estaLogueado()) {
            if (isset($get ['modulo']) && $get ['modulo'] != '') {
                self::accion($get, $post);
            } else {
                self::presentacionSinLogueo();
            }
        } else {
            if (count($get) == 0) {
                self::presentacionSinLogueo();
            } else {
                accionSinLogueo($get, $post);
            }
        }
    }

}

Index::ejecutar($_GET, $_POST);
