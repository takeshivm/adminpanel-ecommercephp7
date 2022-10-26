<?php

class ControladorVentas{

	/*=============================================
	MOSTRAR TOTAL VENTAS
	=============================================*/

	public function ctrMostrarTotalVentas(){

		$tabla = "compras";

		$respuesta = ModeloVentas::mdlMostrarTotalVentas($tabla);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	public function ctrMostrarVentas($item, $valor){

		$tabla = "compras";

		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

		return $respuesta;

	}


	/*=============================================
	MOSTRAR VENTA
	=============================================*/

	public function ctrTraerVenta($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlTraerVenta($tabla, $item, $valor);

		return $respuesta;

	}

}