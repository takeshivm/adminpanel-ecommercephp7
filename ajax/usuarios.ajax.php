<?php

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

class AjaxUsuarios{

  /*=============================================
  ACTIVAR USUARIOS
  =============================================*/	

  public $activarUsuario;
  public $activarId;

  public function ajaxActivarUsuario(){

  	$respuesta = ModeloUsuarios::mdlActualizarUsuario("usuarios", "verificacion", $this->activarUsuario, "id", $this->activarId);

  	echo $respuesta;

  }

  public $id;
  public $item;

  public function ajaxMostrarUsuario(){

    $respuesta = ModeloUsuarios::mdlMostrarUsuarios("usuarios", $this->item, $this->id);

    echo json_encode($respuesta);

  }

}

/*=============================================
ACTIVAR CATEGORIA
=============================================*/

if(isset($_POST["activarUsuario"])){

	$activarUsuario = new AjaxUsuarios();
	$activarUsuario -> activarUsuario = $_POST["activarUsuario"];
	$activarUsuario -> activarId = $_POST["activarId"];
	$activarUsuario -> ajaxActivarUsuario();

}


/*=============================================
TRAER USUARIO
=============================================*/

if(isset($_POST["id"])){

  $traerUsuario = new AjaxUsuarios();
  $traerUsuario -> id = $_POST["id"];
  $traerUsuario -> item = $_POST["item"];
  $traerUsuario -> ajaxMostrarUsuario();

}