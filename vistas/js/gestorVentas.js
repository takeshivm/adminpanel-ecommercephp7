/*=============================================
CARGAR LA TABLA DINÁMICA DE VENTAS
=============================================*/

// $.ajax({

// 	url:"ajax/tablaVentas.ajax.php",
// 	success:function(respuesta){
		
// 		console.log("respuesta", respuesta);

// 	}

// })
//console.log('se ejecuto ventas.js');

$(".tablaVentas").DataTable({
	 "ajax": "ajax/tablaVentas.ajax.php",
	 "deferRender": true,
	 "retrieve": true,
	 "processing": true,
	 "language": {

	 	"sProcessing":     "Procesando...",
		"sLengthMenu":     "Mostrar _MENU_ registros",
		"sZeroRecords":    "No se encontraron resultados",
		"sEmptyTable":     "Ningún dato disponible en esta tabla",
		"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
		"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
		"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		"sInfoPostFix":    "",
		"sSearch":         "Buscar:",
		"sUrl":            "",
		"sInfoThousands":  ",",
		"sLoadingRecords": "Cargando...",
		"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
		},
		"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
		}

	 }


});

/*=============================================
PROCESO DE ENVÍO
=============================================*/


$(".tablaVentas tbody").on("click", ".btnEnvio", function(){


	var idVenta = $(this).attr("idVenta");
	var etapa = $(this).attr("etapa");

	var datos = new FormData();
 	datos.append("idVenta", idVenta);
  	datos.append("etapa", etapa);

  		$.ajax({

  		 url:"ajax/ventas.ajax.php",
  		 method: "POST",
	  	data: datos,
	  	cache: false,
      	contentType: false,
      	processData: false,
      	success: function(respuesta){ 
      	    
      	  console.log("respuesta", respuesta);

      	} 	 

  	});

  	if(etapa == 1){
	
  		$(this).addClass('btn-warning');
  		$(this).removeClass('btn-danger');
  		$(this).html('Enviando el producto');
  		$(this).attr('etapa', 2);

  	}

	if(etapa == 2){
	
  		$(this).addClass('btn-success');
  		$(this).removeClass('btn-warning');
  		$(this).html('Producto entregado');
	
  	}
  	

})


/*=============================================
	FUNCION PARA MOSTRAR EL MODAL CON LOS VALORES
	DEL DETALLE DE VENTA SOLICITADO.
=============================================*/
var jsonRes = "";

function limpiarModalVenta(){

	$("#footDetalleVenta tr").remove();
	$('.numeroVenta').html("");
	$('.fechaVenta').html("");
	$("#bodyDetalleVenta tr").remove();

}

function cargarDatosCliente($idCliente){

	var item = "id";

	var datos = new FormData();
	datos.append('id', $idCliente);
	datos.append('item', item);

	$.ajax({

		url:"ajax/usuarios.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		success:function(respuesta){

			var usuario = JSON.parse(respuesta);

			$(".nombreCliente").html(usuario["nombre"]);
			$(".direccionCliente").html("Dirección: " + usuario["domicilio"]);
			$(".emailCliente").html("Email: " + '<a href="mailto:'+usuario["email"]+'">'+usuario["email"]+'</a>');
			$(".telefonoCliente").html("Telefono: " + usuario["telefono"]);
			$(".documentoCliente").html("Documento: " + usuario["num_documento"]);

		}

	});

}

function verDetalleVenta($id_venta){
	
	limpiarModalVenta();
	agregarDetalleVenta($id_venta);

	var item = "id";

	var datos = new FormData();
	datos.append('id_venta', $id_venta);
	datos.append('item_venta', item);

	$.ajax({

		url:"ajax/ventas.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		success:function(respuesta){
			
			var jsonVenta = JSON.parse(respuesta);
			console.log(jsonVenta["id_cliente"]);
			cargarDatosCliente(jsonVenta["id_cliente"]);

			if(respuesta){
				
				if (jsonVenta["serie"] != null && jsonVenta["numero"]) {
					var tipoDoc = jsonVenta["tipo_documento"]=="B"?"Boleta":"Factura";
					
					$('.numeroVenta').append(tipoDoc +
						" " + jsonVenta["serie"] +' ' + jsonVenta["numero"]);

					$('.fechaVenta').append("Fecha de emisión: " + jsonVenta["fecha_emision"].substring(0,10));					

				}else{

					$('.numeroVenta').append("");
					$('.fechaVenta').append("");

				}

				if (jsonVenta["sub_total"] != null) {

					$('#footDetalleVenta').append('<tr><td colspan="2"></td><td colspan="2">SUBTOTAL</td>'+
						"<td>S/. " + jsonVenta["sub_total"] +'</td></tr>');

				}else{

					$('#footDetalleVenta').append('<tr><td colspan="2"></td><td colspan="2">SUBTOTAL</td>'+
						'<td>S/. 0.00</td></tr>');

				}

				if (jsonVenta["igv"] != null) {

					$('#footDetalleVenta').append('<tr><td colspan="2"></td><td colspan="2">IGV</td>'+
						"<td>S/. " + jsonVenta["igv"] +'</td></tr>');

				}else{

					$('#footDetalleVenta').append('<tr><td colspan="2"></td><td colspan="2">IGV</td>'+
						'<td>S/. 0.00</td></tr>');

				}

				if (jsonVenta["total"] != null) {

					$('#footDetalleVenta').append('<tr><td colspan="2"></td><td colspan="2">TOTAL</td>'+
						"<td>S/. " + jsonVenta["total"] +'</td></tr>');

				}else{

					$('#footDetalleVenta').append('<tr><td colspan="2"></td><td colspan="2">TOTAL</td>'+
						'<td>S/. 0.00</td></tr>');

				}
				
				//console.log(jsonVenta["total"]);
				//console.log("Cliente id: " + jsonVenta["id_cliente"]);

			}else{

				//var modo = JSON.parse(respuesta).modo;

			}

		}

	});


	$("#modalDetalleVenta").modal("show");



}

/*=============================================
	TRAEMOS LOS DETALLES REALACIONES A LA VENTA,
	LUEGO CON UN BUCLE, CAPTURAMOS LOS VALORES Y ENVIAMOS
	AL METODO: TRAERPRODUCTO(); UNO A LA VEZ.
=============================================*/
function agregarDetalleVenta($id_venta){
	var res = "";
	var item = "id_venta";

	var datos = new FormData();
	datos.append('idVenta2', $id_venta);
	datos.append('item_venta', item);
	
	$.ajax({

		url:"ajax/ventas.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		success:function(respuesta){

			var jsonDetalle = JSON.parse(respuesta);

			for (var i = 0; i < jsonDetalle.length; i++) {
				var id = jsonDetalle[i]["id_producto"];
				var cantidad = jsonDetalle[i]["cantidad"];
				var subTotal = (Math.round((jsonDetalle[i]["pago"]/1.18) * 100) / 100).toFixed(2);
				var igv = (Math.round((jsonDetalle[i]["pago"]*0.18) * 100) / 100).toFixed(2);
				var total = jsonDetalle[i]["pago"];
				var envio = jsonDetalle[i]["envio"];
				traerProducto(i, id, cantidad, subTotal, igv, total, envio);

			}

		}

	});

}
//var res = "";

/*=============================================
	TRAEMOS LOS PRODUCTOS SOLICITADOS EN LOS DETALLES,
	DE ESTA MANERA OBTENEMO EL NOMBRE DEL PRODUCTO CORRESPONDIENTE.
=============================================*/
function traerProducto($iterador, $id_producto, $cantidad, $subTotal, $igv, $total, $envio){

	var datos = new FormData();
	datos.append('idProducto', $id_producto);
	$.ajax({

		url:"ajax/productos.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		success:function(respuesta){
			var jsonproducto = JSON.parse(respuesta);
			var tituloProducto = jsonproducto[0]["titulo"];

			agregarItemDetalle(tituloProducto, $iterador+1, $cantidad, $subTotal, $igv, $total, $envio)
			
		}

	});

}

/*=============================================
	CAPTURAMOS TODOS LOS VALORES PARA MOSTRAR LOS DETALLES
	EN EL MODAL DE LA VENTA.
=============================================*/
function agregarItemDetalle($tituloProducto, $iterador, $cantidad, $subTotal, $igv, $total, $envio){
	
	if($envio == 0){

		$validarEnvio ="<button class='btn btn-danger btnEnvio' etapa='1' disabled>Despachando el producto</button>";

	}else if($envio == 1){

		$validarEnvio = "<button class='btn btn-warning btnEnvio' etapa='2' disabled>Enviando el producto</button>";

	}else{

		$validarEnvio = "<button class='btn btn-success'>Producto entregado</button>";

	}

	$("#bodyDetalleVenta").append(`
		<tr>
			<td class="no">`+ $iterador +`</td>
            <td class="text-left">
            	<h3>Description `+ $iterador +`</h3>
            	`+ $tituloProducto +`
            	<br>
            	<h3>CANTIDAD: `+ $cantidad +`</h3>
            	<br>
            	<h3>Estado del envio: `+ $validarEnvio +`</h3>
            	</td>
            <td class="unit">S/. `+ $subTotal +`</td>
            <td class="tax">S/. `+ $igv +`</td>
            <td class="total">S/. `+ $total +`</td>
		</tr>
	`);

}

