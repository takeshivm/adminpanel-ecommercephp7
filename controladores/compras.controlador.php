<?php
/*
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
*/
class ControladorCompras{

	/*=============================================
	MOSTRAR COMPRAS
	=============================================*/

	static public function ctrMostrarCompras($item, $valor){

		$tabla = "comprasPro";

		$respuesta = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR TOTAL COMPRAS
	=============================================*/

	static public function ctrMostrarTotalCompras(){

		$tabla = "comprasPro";

		$respuesta = ModeloCompras::mdlMostrarTotalCompras($tabla);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR DETALLE DE COMPRA
	=============================================*/

	static public function ctrMostrarDetalles($item, $valor){

		$tabla = "detalle_compra";

		$respuesta = ModeloCompras::mdlMostrarDetalles($tabla, $item, $valor);

		return $respuesta;

	}


	/*=============================================
	CREAR COMPRA
	=============================================*/

	static public function ctrCrearCompra(){

		if(isset($_POST["nuevaCompra"])){

			/*=============================================
			ACTUALIZAR LAS COMPRAS DEL CLIENTE, REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
			=============================================*/

			if($_POST["listaProductos"] == ""){

					echo'<script>

				swal({
					  type: "error",
					  title: "La compra no se ejecuta si no hay productos",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "crear-compra"

								}
							})

				</script>';

				return;
			}else{


				$listaProductos = json_decode($_POST["listaProductos"], true);

				$totalProductosComprados = array();

				foreach ($listaProductos as $key => $value) {

				   array_push($totalProductosComprados, $value["cantidad"]);
					
				   $tablaProductos = "productos";

				    $item = "id";
				    $valor = $value["id"];
				    //$orden = "id";

				    $traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor/*, $orden*/);
				    /*
					$item1a = "ventas";
					$valor1a = $value["cantidad"] + $traerProducto["ventas"];

				    $nuevasVentas = ModeloProductos::mdlActualizarProductos($tablaProductos, $item1a, $valor1a, $valor);
					*/
					$item1b = "stock";
					$valor1b = $value["stock"];

					$nuevoStock = ModeloProductos::mdlActualizarProductos($tablaProductos, $item1b, $valor1b, $item, $valor);

				}

				$tablaProveedores = "proveedor";

				$item = "id_proveedor";
				$valor = $_POST["seleccionarProveedor"];

				$traerProveedor = ModeloProveedores::mdlMostrarProveedores($tablaProveedores, $item, $valor);

				$item1a = "transacciones";
					
				$valor1a = array_sum($totalProductosComprados) + $traerProveedor["transacciones"];

				$comprasCliente = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item1a, $valor1a, $valor);

				$item1b = "ultima_compra";

				date_default_timezone_set('America/Bogota');

				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b = $fecha.' '.$hora;

				$fechaCliente = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item1b, $valor1b, $valor);

				/*=============================================
				GUARDAR LA COMPRA
				=============================================*/	

				if (isset($_POST["nuevoCodigoTransaccion"])) {
					$codTrans = $_POST["nuevoCodigoTransaccion"];
				}else{
					$codTrans = 0;
				}

				$tabla = "comprasPro";

				$datos = array("id_vendedor"=>$_POST["idVendedor"],
							   "id_proveedor"=>$_POST["seleccionarProveedor"],
							   "tipo_doc"=>$_POST["tipoDocumento"],
							   "num_doc"=>$_POST["buscarProveedor"],
							   "cod_com"=>$_POST["nuevaCompra"],
							   "productos"=>$_POST["listaProductos"],
							   //"productos"=>$_POST["listaProductos"],
							   "igv"=>$_POST["totalIGV"],
							   "subTotal"=>$_POST["totalSubTotal"],
							   "total"=>$_POST["totalCompra"],
							   "codigo_trans"=>$codTrans,
							   "metodo_pago"=>$_POST["nuevoMetodoPago"]);

				$respuesta = ModeloCompras::mdlIngresarCompra($tabla, $datos);

				

				if($respuesta == "ok"){

					// $impresora = "epson20";

					// $conector = new WindowsPrintConnector($impresora);

					// $imprimir = new Printer($conector);

					// $imprimir -> text("Hola Mundo"."\n");

					// $imprimir -> cut();

					// $imprimir -> close();


					//MODIFICAR DEACUERDO AL TIPO DE IMPRESORA

					/*
					$impresora = "epson20";

					$conector = new WindowsPrintConnector($impresora);

					$printer = new Printer($conector);

					$printer -> setJustification(Printer::JUSTIFY_CENTER);

					$printer -> text(date("Y-m-d H:i:s")."\n");//Fecha de la factura

					$printer -> feed(1); //Alimentamos el papel 1 vez -------

					$printer -> text("Inventory System"."\n");//Nombre de la empresa

					$printer -> text("NIT: 71.759.963-9"."\n");//Nit de la empresa

					$printer -> text("Dirección: Calle 44B 92-11"."\n");//Dirección de la empresa

					$printer -> text("Teléfono: 300 786 52 49"."\n");//Teléfono de la empresa

					$printer -> text("FACTURA N.".$_POST["nuevaVenta"]."\n");//Número de factura

					$printer -> feed(1); //Alimentamos el papel 1 vez -------

					$printer -> text("Cliente: ".$traerCliente["nombre"]."\n");//Nombre del cliente

					$tablaVendedor = "usuarios";
					$item = "id";
					$valor = $_POST["idVendedor"];

					$traerVendedor = ModeloUsuarios::mdlMostrarUsuarios($tablaVendedor, $item, $valor);

					$printer -> text("Vendedor: ".$traerVendedor["nombre"]."\n");//Nombre del vendedor

					$printer -> feed(1); //Alimentamos el papel 1 vez -------

					foreach ($listaProductos as $key => $value) {

						$printer->setJustification(Printer::JUSTIFY_LEFT);

						$printer->text($value["descripcion"]."\n");//Nombre del producto

						$printer->setJustification(Printer::JUSTIFY_RIGHT);

						$printer->text("$ ".number_format($value["precio"],2)." Und x ".$value["cantidad"]." = $ ".number_format($value["total"],2)."\n");

					}

					$printer -> feed(1); //Alimentamos el papel 1 vez -------		
					
					$printer->text("NETO: $ ".number_format($_POST["nuevoPrecioNeto"],2)."\n"); //ahora va el neto

					$printer->text("IMPUESTO: $ ".number_format($_POST["nuevoPrecioImpuesto"],2)."\n"); //ahora va el impuesto

					$printer->text("--------\n");

					$printer->text("TOTAL: $ ".number_format($_POST["totalVenta"],2)."\n"); //ahora va el total

					$printer -> feed(1); //Alimentamos el papel 1 vez -------

					$printer->text("Muchas gracias por su compra"); //Podemos poner también un pie de página

					$printer -> feed(3); //Alimentamos el papel 3 veces -------

					$printer -> cut(); //Cortamos el papel, si la impresora tiene la opción

					$printer -> pulse(); //Por medio de la impresora mandamos un pulso, es útil cuando hay cajón moneder

					$printer -> close();
					*/
		
					echo'<script>

					localStorage.removeItem("rango");

					swal({
						  type: "success",
						  title: "La compra ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "crear-compra";

									}
								})

					</script>';

				}
			}

		}

	}

	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function ctrEditarCompra(){

		if(isset($_POST["editarCompra"])){

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/
			$tabla = "comprasPro";

			$item = "id";
			$valor = $_POST["id_compra"];

			$traerCompra = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);

			/*=============================================
			REVISAR SI VIENE PRODUCTOS EDITADOS
			=============================================*/

			if($_POST["listaProductos"] == ""){

				$listaProductos = ModeloCompras::mdlMostrarDetalles("detalle_compra", "id_compra", $valor);
				//$listaProductos = $traerCompra["productos"];
				$cambioProducto = false;


			}else{

				$listaProductos = $_POST["listaProductos"];
				$cambioProducto = true;
			}

			if($cambioProducto){

				//$productos =  json_decode($traerCompra["productos"], true);

				$listaProductosComparar = ModeloCompras::mdlMostrarDetalles("detalle_compra", "id_compra", $valor);

				//	TRAEMOS LOS PRODUCTOS PARA RESTAR LA CANTIDAD AL STOCK ACTUAL Y CONVERTIRLO A
				//	COMO ESTABA ANTERIORMENTE

				$totalProductosCompradosAnterior = array();

				foreach ($listaProductosComparar as $key => $value1) {
					# code...
					array_push($totalProductosCompradosAnterior, $value1["cantidad"]);

					$tablaProductos = "productos";

					$itemPro = "id";
					$valorPro = $value1["id_producto"];
					//$orden = "id";

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $itemPro, $valorPro/*, $orden*/);

					$item1b = "stock";
					$valor1b = $traerProducto[0]["stock"] - $value1["cantidad"];

					$nuevoStock = ModeloProductos::mdlActualizarProductos($tablaProductos, $item1b, $valor1b, $itemPro, $valorPro);

				}

				// ELIMINAMOS LOS DETALLES RECIONADOS CON LA COMPRA EDITADA (EN BASE AL ID)

				$eliminarDetallesCompra = ModeloCompras::mdlEliminarDetalles("detalle_compra", "id_compra", $valor);

				// CREAMOS NUEVAMENTE TODOS LOS DETALLES Y ACTUALIZAMOS EL STOCK 

				//$listaProductos_2 = json_decode($listaProductos, true);
				
				$productos =  json_decode($listaProductos, true);
				$totalProductosComprados = array();

				foreach ($productos as $key => $value) {

					var_dump($value["cantidad"]);
					array_push($totalProductosComprados, $value["cantidad"]);

					$tablaProductos = "productos";

					$item = "id";
					$valor = $value["id"];
					//$orden = "id";

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor/*, $orden*/);

					/*
					$item1a = "comprasPro";
					$valor1a = $traerProducto["ventas"] - $value["cantidad"];

					$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);*/


					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto[0]["stock"];

					$nuevoStock = ModeloProductos::mdlActualizarProductos($tablaProductos, $item1b, $valor1b, $item, $valor);

					//INSERTAMOS LOS DETALLES EDITADOS
					$insertarDetalles = ModeloCompras::mdlInsertarDetallesCompra($_POST["id_compra"], $valor, $value["cantidad"]);

				}


				$tablaProveedores = "proveedor";

				$item = "id_proveedor";
				$valor = $_POST["seleccionarProveedor"];

				$traerProveedor = ModeloProveedores::mdlMostrarProveedores($tablaProveedores, $item, $valor);

				$item1a = "transacciones";
					
				$valor1anterior = $traerProveedor["transacciones"] - array_sum($totalProductosCompradosAnterior);

				//DEVOLVEMOS AL VALOR ANTERIOR DE LAS TRANSACCIONES
				$comprasProveedor = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item1a, $valor1anterior, $valor);

				//ACTUALIZAMOS LAS TRANSACCIONES CON LA NUEVA CANTIDAD DE PRODUCTOS
				$valor1a = array_sum($totalProductosComprados) + $traerProveedor["transacciones"];

				$comprasProveedor2 = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item1a, $valor1a, $valor);

				$item1b = "ultima_compra";

				date_default_timezone_set('America/Bogota');

				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b = $fecha.' '.$hora;

				$fechaCliente = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item1b, $valor1b, $valor);




			}

			/*=============================================
			GUARDAR CAMBIOS DE LA COMPRA
			=============================================*/	

			date_default_timezone_set('America/Bogota');

			$fechaMod = date('Y-m-d');
			$horaMod = date('H:i:s');
			$valorMod = $fecha.' '.$hora;

			$tabla = "comprasPro";

			$datos = array("id_compra"=>$_POST["id_compra"],
							"id_vendedor"=>$_POST["idVendedor"],
						   "id_proveedor"=>$_POST["seleccionarProveedor"],
						   "tipo_doc"=>$_POST["tipoDocumento"],
						   "num_doc"=>$_POST["buscarProveedor"],
						   "cod_com"=>$_POST["editarCompra"],
						   //"productos"=>$_POST["listaProductos"],
						   "igv"=>$_POST["totalIGV"],
						   "subTotal"=>$_POST["totalSubTotal"],
						   "total"=>$_POST["totalCompra"],
						   "codigo_trans"=>$codTrans,
						   "fecha_mod"=>$valorMod,
						   "metodo_pago"=>$_POST["nuevoMetodoPago"]);

			// $datos = array("id_vendedor"=>$_POST["idVendedor"],
			// 			   "id_cliente"=>$_POST["seleccionarCliente"],
			// 			   "codigo"=>$_POST["editarCompra"],
			// 			   "productos"=>$listaProductos,
			// 			   "impuesto"=>$_POST["nuevoPrecioImpuesto"],
			// 			   "neto"=>$_POST["nuevoPrecioNeto"],
			// 			   "total"=>$_POST["totalVenta"],
			// 			   "metodo_pago"=>$_POST["listaMetodoPago"]);


			$respuesta = ModeloCompras::mdlEditarCompra($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La Compra ha sido editada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {

								window.location = "compras";

								}
							})

				</script>';

			}

		}

	}


	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasCompras($fechaInicial, $fechaFinal){

		$tabla = "comprasPro";

		$respuesta = ModeloCompras::mdlRangoFechasCompras($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}


	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporteCompras(){

		if(isset($_GET["reporte"])){

			$tabla = "comprasPro";

			if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				$compras = ModeloCompras::mdlRangoFechasCompras($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);

			}else{

				$item = null;
				$valor = null;

				$compras = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);

			}


			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/

			$Name = $_GET["reporte"].'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate"); 
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public"); 
			header('Content-Disposition:; filename="'.$Name.'"');
			header("Content-Transfer-Encoding: binary");
		
			echo utf8_decode("<table border='0'> 

					<tr> 
					<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
					<td style='font-weight:bold; border:1px solid #eee;'>PROVEEDOR</td>
					<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
					<td style='font-weight:bold; border:1px solid #eee;'>IMPUESTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>SUBTOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td	
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");

			foreach ($compras as $row => $item){

				$proveedor = ControladorProveedores::ctrMostrarProveedores("id_proveedor", $item["id_proveedor"]);
				$vendedor = ControladorAdministradores::ctrMostrarAdministradores("id", $item["id_vendedor"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["cod_com"]."</td> 
			 			<td style='border:1px solid #eee;'>".$proveedor["razon_social"]."</td>
			 			<td style='border:1px solid #eee;'>".$vendedor["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>");

			 	$detalles = ModeloCompras::mdlMostrarDetalles("detalle_compra","id_compra",$item["id"]);

			 	//$productos =  json_decode($item["productos"], true);

			 	foreach ($detalles as $key => $valueProductos) {
			 			
			 		echo utf8_decode($valueProductos["cantidad"]."<br>");
			 	}

			 	echo utf8_decode("</td><td style='border:1px solid #eee;'>");	

		 		foreach ($detalles as $key => $valueProductos) {

					$traerProducto = ControladorProductos::ctrMostrarProductos("id", $valueProductos["id_producto"] /*, $orden*/);
			 		$unProducto = $traerProducto[0]["titulo"];	
		 			echo utf8_decode($unProducto."<br>");
		 		
		 		}

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["igv"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["subTotal"],2)."</td>	
					<td style='border:1px solid #eee;'>$ ".number_format($item["total"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["metodo_pago"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["fecha_reg"],0,10)."</td>		
		 			</tr>");


			}


			echo "</table>";

		}

	}




	/*=============================================
	ELIMINAR COMPRA
	=============================================*/

	static public function ctrEliminarCompra(){

		if(isset($_GET["idCompra"])){

			$tabla = "comprasPro";

			$item = "id";
			$valor = $_GET["idCompra"];

			$traerCompra = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);
			//var_dump($traerCompra);
			/*=============================================
			ACTUALIZAR FECHA ÚLTIMA COMPRA
			=============================================*/

			$tablaProveedores = "proveedor";

			$itemCompras = null;
			$valorCompras = null;

			$traerCompras = ModeloCompras::mdlMostrarCompras($tabla, $itemCompras, $valorCompras);

			$guardarFechas = array();

			foreach ($traerCompras as $key => $value) {
				
				if($value["id_proveedor"] == $traerCompra["id_proveedor"]){

					array_push($guardarFechas, $value["fecha_reg"]);

				}

			}

			if(count($guardarFechas) > 1){

				if($traerCompra["fecha_reg"] > $guardarFechas[count($guardarFechas)-2]){

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-2];
					$valorIdProveedor = $traerCompra["id_proveedor"];

					$comprasCliente = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item, $valor, $valorIdProveedor);

				}else{

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-1];
					$valorIdProveedor = $traerCompra["id_proveedor"];

					$comprasCliente = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item, $valor, $valorIdProveedor);

				}


			}else{

				$item = "ultima_compra";
				$valor = "0000-00-00 00:00:00";
				$valorIdProveedor = $traerCompra["id_proveedor"];

				$comprasCliente = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item, $valor, $valorIdProveedor);

			}

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/

			$productos = ModeloCompras::mdlMostrarDetalles("detalle_compra", "id_compra", $traerCompra["id"]);
			//var_dump($traerCompra["id"]);
			//$productos =  json_decode($traerVenta["productos"], true);

			$totalProductosComprados = array();

			foreach ($productos as $key => $value) {

				array_push($totalProductosComprados, $value["cantidad"]);
				
				$tablaProductos = "productos";

				$item = "id";
				$valor = $value["id_producto"];
				//$orden = "id";

				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor/*, $orden*/);

				// 	EN ESTE CASO, NO SE APLICARA LA MODIFICACION, YA QUE EL PRODUICTO
				// NO CUENTA CON CAMPO DE COMPRA
				/*
				$item1a = "ventas";
				$valor1a = $traerProducto["ventas"] - $value["cantidad"];

				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);
				*/

				//MODIFICAMOS EL STOCK DEL PRODUCTO
				$item1b = "stock";
				$valor1b = $traerProducto[0]["stock"] - $value["cantidad"];

				$item2b = "id";

				$nuevoStock = ModeloProductos::mdlActualizarProductos($tablaProductos, $item1b, $valor1b, $item2b, $valor);

			}

			$tablaProveedores = "proveedor";

			$itemProveedor = "id_proveedor";
			$valorProveedor = $traerCompra["id_proveedor"];

			$traerProveedor = ModeloProveedores::mdlMostrarProveedores($tablaProveedores, $itemProveedor, $valorProveedor);

			$item1a = "transacciones";
			$valor1a = $traerProveedor["transacciones"] - array_sum($totalProductosComprados);

			$comprasCliente = ModeloProveedores::mdlActualizarProveedor($tablaProveedores, $item1a, $valor1a, $valorProveedor);

			/*=============================================
			ELIMINAR COMPRA
			=============================================*/
			$itemCom = "anular";
			$valCom = "1";

			$respuesta = ModeloCompras::mdlActualizarCompra($tabla, $itemCom, $valCom, $_GET["idCompra"]);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "La compra ha sido anulada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "compras";

								}
							})

				</script>';

			}		
		}

	}


/////	TERMINA LA CLASE CONTROLADOR
}
