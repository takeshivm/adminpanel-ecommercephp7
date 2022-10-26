<?php

require_once "../controladores/proveedores.controlador.php";
require_once "../modelos/proveedores.modelo.php";

class AjaxProveedores{

	/*=============================================
	EDITAR USUARIO
	=============================================*/	

	public $idProveedor;

	public function ajaxEditarProveedor(){

		$item = "id_proveedor";
		$valor = $this->idProveedor;

		$respuesta = ControladorProveedores::ctrMostrarProveedores($item, $valor);

		echo json_encode($respuesta);

	}

	/*=============================================
	ACTIVAR USUARIO
	=============================================*/	

	public $activarProveedor;
	public $activarId;


	public function ajaxActivarProveedor(){

		$tabla = "proveedor";

		$item1 = "estado";
		$valor1 = $this->activarProveedor;

		$item2 = "id_proveedor";
		$valor2 = $this->activarId;

		$respuesta = ModeloProveedores::mdlActualizarProveedor($tabla, $item1, $valor1, $valor2);

		echo $respuesta;

	}

	/*=============================================
	VALIDAR NO REPETIR USUARIO
	=============================================*/	

	public $validarProveedor;

	public function ajaxValidarProveedor(){

		$item = "documento";
		$valor = $this->validarProveedor;

		$respuesta = ControladorProveedores::ctrMostrarProveedores($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR USUARIO
=============================================*/
if(isset($_POST["idProveedor"])){

	$editar = new AjaxProveedores();
	$editar -> idProveedor = $_POST["idProveedor"];
	$editar -> ajaxEditarProveedor();

}

/*=============================================
ACTIVAR USUARIO
=============================================*/	

if(isset($_POST["activarProveedor"])){

	$activarProveedor = new AjaxProveedores();
	$activarProveedor -> activarProveedor = $_POST["activarProveedor"];
	$activarProveedor -> activarId = $_POST["activarId"];
	$activarProveedor -> ajaxActivarProveedor();

}

/*=============================================
VALIDAR NO REPETIR USUARIO
=============================================*/

if(isset( $_POST["validarProveedor"])){

	$valProveedor = new AjaxProveedores();
	$valProveedor -> validarProveedor = $_POST["validarProveedor"];
	$valProveedor -> ajaxValidarProveedor();

}
