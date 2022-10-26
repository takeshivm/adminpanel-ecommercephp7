<?php

require_once "../controladores/proveedores.controlador.php";
require_once "../modelos/proveedores.modelo.php";


class AjaxCompras{

	/*=============================================
	ACTUALIZAR PROCESO DE ENVÍO
	=============================================*/
	

  	public $doc;
  	//public $etapa;

  	public function ajaxConsultarProveedor(){

  		$respuesta = ModeloProveedores::mdlMostrarProveedoresDoc("proveedor", "documento", $this->doc);

  		echo json_encode($respuesta);

	}

}

/*=============================================
ACTUALIZAR PROCESO DE ENVÍO
=============================================*/


if(isset($_POST["doc"])){

	$doc = new AjaxCompras();
	$doc -> doc = $_POST["doc"];
	//$doc -> etapa = $_POST["etapa"];
	$doc -> ajaxConsultarProveedor();

}