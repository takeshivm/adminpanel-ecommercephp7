<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";


class AjaxVentas{

	/*=============================================
	ACTUALIZAR PROCESO DE ENVÍO
	=============================================*/
	

  	public $idVenta;
  	public $etapa;

  	public function ajaxEnvioVenta(){

  		$respuesta = ModeloVentas::mdlActualizarVenta("compras", "envio", $this->etapa, "id", $this->idVenta);

  		echo $respuesta;

	}



	/*=============================================
	TRAER VENTA
	=============================================*/
	public $item_venta;

	public function ajaxTraerVenta(){

		$respuesta = ControladorVentas::ctrTraerVenta($this->item_venta, $this->idVenta); 

		echo json_encode($respuesta);

	}


	/*=============================================
	TRAER DETALLE DE VENTA
	=============================================*/

	public function ajaxTraerDetalleVenta(){

		//TRAEMOS LAS COMPRAS (DETALLES DE VENTA), RELACIONADAS A LA VENTA.
		$respuesta = ControladorVentas::ctrMostrarVentas($this->item_venta, $this->idVenta); 

		echo json_encode($respuesta);

	}


}
/*=============================================
ACTUALIZAR PROCESO DE ENVÍO
=============================================*/


if(isset($_POST["idVenta"])){

	$envioVenta = new AjaxVentas();
	$envioVenta -> idVenta = $_POST["idVenta"];
	$envioVenta -> etapa = $_POST["etapa"];
	$envioVenta -> ajaxEnvioVenta();

}


/*=============================================
VERIFICAR LA VENTA
=============================================*/	

if(isset($_POST["id_venta"])){

	$traerVenta = new AjaxVentas();
	$traerVenta -> idVenta = $_POST["id_venta"];
	$traerVenta -> item_venta = $_POST["item_venta"];
	$traerVenta ->ajaxTraerVenta();
}


/*=============================================
MOSTRAR LOS DETALLES DE LA VENTA (COMPRAS)
=============================================*/	

if(isset($_POST["idVenta2"])){

	$traerDetalle = new AjaxVentas();
	$traerDetalle -> idVenta = $_POST["idVenta2"];
	$traerDetalle -> item_venta = $_POST["item_venta"];
	$traerDetalle ->ajaxTraerDetalleVenta();
}