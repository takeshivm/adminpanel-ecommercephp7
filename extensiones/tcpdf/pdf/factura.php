<?php

require_once "../../../controladores/compras.controlador.php";
require_once "../../../modelos/compras.modelo.php";

require_once "../../../controladores/proveedores.controlador.php";
require_once "../../../modelos/proveedores.modelo.php";

require_once "../../../controladores/administradores.controlador.php";
require_once "../../../modelos/administradores.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFactura{

public $codigo;

public function traerImpresionFactura(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$itemCompra = "id";
$valorCompra = $this->codigo;

$respuestaCompra = ControladorCompras::ctrMostrarCompras($itemCompra, $valorCompra);

$fecha = substr($respuestaCompra["fecha_reg"],0,-8);
$neto = number_format($respuestaCompra["subTotal"],2);
$impuesto = number_format($respuestaCompra["igv"],2);
$total = number_format($respuestaCompra["total"],2);

$itemDetalle = "id_compra";
$respuestaDetalleProductos = ControladorCompras::ctrMostrarDetalles($itemDetalle, $valorCompra);

//$productos = json_decode($respuestaCompra["productos"], true);

//TRAEMOS LA INFORMACIÓN DEL PROVEEDOR

$itemProveedor = "id_proveedor";
$valorProveedor = $respuestaCompra["id_proveedor"];

$respuestaProveedor = ControladorProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);

//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

$itemVendedor = "id";
$valorVendedor = $respuestaCompra["id_vendedor"];

$respuestaVendedor = ControladorAdministradores::ctrMostrarAdministradores($itemVendedor, $valorVendedor);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage('P', 'A7');

//---------------------------------------------------------

$bloque1 = <<<EOF

<table style="font-size:9px; text-align:center">

	<tr>
		
		<td style="width:160px;">
	
			<div>
			
				Fecha: $fecha

				<br><br>
				$respuestaProveedor[razon_social]
				
				<br>
				RUC: $respuestaProveedor[documento]

				<br>
				Dirección: $respuestaProveedor[direccion]

				<br>
				Teléfono: $respuestaProveedor[telefono]

				<br>
				FACTURA N.$respuestaCompra[cod_com]

				<br><br>					
				Cliente: E-Commerce Net System

				<br>
				Vendedor: $respuestaProveedor[razon_social]

				<br>

			</div>

		</td>

	</tr>


</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

// ---------------------------------------------------------


foreach ($respuestaDetalleProductos as $key => $item) {

// TRAEMOS UN PRODUCTO DE ACUERDO AL REGISTRADO EN EL DETALLE DE COMPRA
$producto = ControladorProductos::ctrMostrarProductos("id",$item["id_producto"]);

$valorUnitario = number_format($producto[0]["precio"], 2);
$totalDetalle = $producto[0]["precio"] * $item["cantidad"];
$precioTotal = number_format($totalDetalle, 2);
$titulo = $producto[0]["titulo"];

$bloque2 = <<<EOF

<table style="font-size:9px;">

	<tr>
	
		<td style="width:160px; text-align:left">
		$titulo 
		</td>

	</tr>

	<tr>
	
		<td style="width:160px; text-align:right">
		$ $valorUnitario Und * $item[cantidad]  = $ $precioTotal
		<br>
		</td>

	</tr>

</table>

EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');

}

// ---------------------------------------------------------

$bloque3 = <<<EOF

<table style="font-size:9px; text-align:right">

	<tr>
	
		<td style="width:80px;">
			 NETO: 
		</td>

		<td style="width:80px;">
			$ $neto
		</td>

	</tr>

	<tr>
	
		<td style="width:80px;">
			 IMPUESTO: 
		</td>

		<td style="width:80px;">
			$ $impuesto
		</td>

	</tr>

	<tr>
	
		<td style="width:160px;">
			 --------------------------
		</td>

	</tr>

	<tr>
	
		<td style="width:80px;">
			 TOTAL: 
		</td>

		<td style="width:80px;">
			$ $total
		</td>

	</tr>

	<tr>
	
		<td style="width:160px;">
			<br>
			<br>
			Muchas gracias por su compra
		</td>

	</tr>

</table>



EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');

// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 

//$pdf->Output('factura.pdf', 'D');
$pdf->Output('factura.pdf');

}

}

$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();

?>