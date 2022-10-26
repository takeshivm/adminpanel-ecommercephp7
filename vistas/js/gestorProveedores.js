/*=============================================
CARGAR LA TABLA DINÁMICA DE PROVEEDORES
=============================================*/

// $.ajax({

// 	url:"ajax/tablaProveedores.ajax.php",
// 	success:function(respuesta){
		
// 		console.log("respuesta", respuesta);

// 	}

// })
//console.log('se ejecuto ventas.js');

$(".tablaProveedores").DataTable({
	 "ajax": "ajax/tablaProveedores.ajax.php",
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
EDITAR PROVEEDOR
=============================================*/
$(".tablas").on("click", ".btnEditarProveedor", function(){

	var idProveedor = $(this).attr("idProveedor");
	$("#id_proveedor").val(idProveedor);
	
	var datos = new FormData();
	datos.append("idProveedor", idProveedor);

	$.ajax({

		url:"ajax/proveedores.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			console.log(respuesta["telefono"]);
			$("#editarDocumento").val(respuesta["documento"]);
			$("#editarProveedor").val(respuesta["razon_social"]);
			$("#editarTelefono").html(respuesta["telefono"]);
			$("#editarTelefono").val(respuesta["telefono"]);
			$("#editarDireccion").val(respuesta["direccion"]);
			$("#editarEmail").val(respuesta["email"]);

		}

	});

})

/*=============================================
ACTIVAR PROVEEDOR
=============================================*/
$(".tablaProveedores").on("click", ".btnActivar", function(){

	var idUsuario = $(this).attr("idProveedor");
	var estadoProveedor = $(this).attr("estadoProveedor");
	console.log(idUsuario+ ", " + estadoProveedor);
	var datos = new FormData();
 	datos.append("activarId", idUsuario);
  	datos.append("activarProveedor", estadoProveedor);

  	$.ajax({

	  url:"ajax/proveedores.ajax.php",
	  method: "POST",
	  data: datos,
	  cache: false,
      contentType: false,
      processData: false,
      success: function(respuesta){
      		console.log("Proveedor activado");
      		console.log(respuesta);

      		if(respuesta == "ok"){

	      		 swal({
			      title: "El proveedor ha sido actualizado",
			      type: "success",
			      confirmButtonText: "¡Cerrar!"
			    }).then(function(result) {
			        if (result.value) {

			        	window.location = "proveedores";

			        }


				});

	      	}

      }

  	})

  	if(estadoProveedor == 0){

  		$(this).removeClass('btn-success');
  		$(this).addClass('btn-danger');
  		$(this).html('Desactivado');
  		$(this).attr('estadoProveedor',1);

  	}else{

  		$(this).addClass('btn-success');
  		$(this).removeClass('btn-danger');
  		$(this).html('Activado');
  		$(this).attr('estadoProveedor',0);

  	}

})

/*=============================================
REVISAR SI EL USUARIO YA ESTÁ REGISTRADO
=============================================*/

$("input[name='nuevoDocumentoId']").on('change',function(){
	
	console.log("validar proveedore");

	$(".alert").remove();

	var proveedor = $(this).val();

	var datos = new FormData();
	datos.append("validarProveedor", proveedor);

	 $.ajax({
	    url:"ajax/proveedores.ajax.php",
	    method:"POST",
	    data: datos,
	    cache: false,
	    contentType: false,
	    processData: false,
	    dataType: "json",
	    success:function(respuesta){
	    	console.log(respuesta);
	    	//var validar = JSON.parse(respuesta);

	    	if(respuesta != ""){

	    		$("input[name='nuevoDocumentoId']").parent().after('<div class="alert alert-warning">Este proveedor ya existe en la base de datos</div>');

	    		$("input[name='nuevoDocumentoId']").val("");

	    	}

	    }

	})
})

/*=============================================
ELIMINAR USUARIO
=============================================*/
$(".tablas").on("click", ".btnEliminarProveedor", function(){

  var idUsuario = $(this).attr("idProveedor");
  var usuario = $(this).attr("proveedor");

  swal({
    title: '¿Está seguro de borrar el proveedor?',
    text: "¡Si no lo está puede cancelar la accíón!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, borrar proveedor!'
  }).then(function(result){

    if(result.value){

      window.location = "index.php?ruta=proveedores&idProveedor="+idUsuario+"&proveedor="+usuario;

    }

  })

})



function verificarRuc(){

	if ($("#estadoRUC").attr('value')) {
		console.log($("#estadoRUC").attr('value'));
		return true;
	}else{
		console.log($("#estadoRUC").attr('value'));
		return false;
	}

}

