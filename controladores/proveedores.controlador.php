<?php

class ControladorProveedores{

	/*=============================================
	MOSTRAR TOTAL PROVEEDORES
	=============================================*/

	static public function ctrMostrarTotalProveedores($orden){

		$tabla = "proveedor";

		$respuesta = ModeloProveedores::mdlMostrarTotalProveedores($tabla, $orden);

		return $respuesta;

	}

	/*=============================================
	CREAR PROVEEDORES
	=============================================*/


	static public function ctrCrearProveedor(){

		if(isset($_POST["nuevaRazon"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaRazon"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"]) &&
			   preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) && 
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) && 
			   preg_match('/^[a-zA-Z ]+$/', $_POST["estadoRUC"]) && 
			    $_POST["estadoRUC"] == 'true' && 
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["nuevaDireccion"])){

			   	$tabla = "proveedor";

			   	$datos = array(//"nombre"=>$_POST["nuevoCliente"],
					           "documento"=>$_POST["nuevoDocumentoId"],
					           "razon"=>$_POST["nuevaRazon"],
					           "email"=>$_POST["nuevoEmail"],
					           "telefono"=>$_POST["nuevoTelefono"],
					           "direccion"=>$_POST["nuevaDireccion"]);
					           //"fecha_nacimiento"=>$_POST["nuevaFechaNacimiento"]);

			   	$validar = ModeloProveedores::mdlMostrarProveedores("proveedor","documento",$_POST["nuevoDocumentoId"]);

			   	if (isset($validar)) {

			   		$respuesta = ModeloProveedores::mdlIngresarProveedor($tabla, $datos);

				   	if($respuesta == "ok"){

						echo'<script>

						swal({
							  type: "success",
							  title: "El proveedor ha sido guardado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										
										}
									})

						</script>';

					}

				}else{

					echo'<script>

						swal({
							  type: "warning",
							  title: "¡El proveedor ya existe en la base de datos!",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										
										}
									})

						</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El proveedor no puede ir vacío o llevar caracteres especiales!",
						  text: "¡Ingrese un numero de documento correcto!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							//window.location = "clientes";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	EDITAR PROVEEDORES
	=============================================*/


	static public function ctrEditarProveedor(){

		if(isset($_POST["editarProveedor"])){

			if(preg_match('/^[.\a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarProveedor"]) &&
			   preg_match('/^[0-9]+$/', $_POST["editarDocumento"]) &&
			   preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editarEmail"]) && 
			   preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"]) && 
			   preg_match('/^[a-zA-Z ]+$/', $_POST["editarEstadoRUC"]) && 
			    $_POST["editarEstadoRUC"] == 'true' && 
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"])){

			   	$tabla = "proveedor";

			   	$datos = array(//"nombre"=>$_POST["nuevoCliente"],
					           "id_proveedor"=>$_POST["id_proveedor"],
					           "documento"=>$_POST["editarDocumento"],
					           "razon"=>$_POST["editarProveedor"],
					           "email"=>$_POST["editarEmail"],
					           "telefono"=>$_POST["editarTelefono"],
					           "direccion"=>$_POST["editarDireccion"]);
					           //"fecha_nacimiento"=>$_POST["nuevaFechaNacimiento"]);

			   	$respuesta = ModeloProveedores::mdlEditarProveedor($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El proveedor ha sido actualizado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "proveedores";
									
									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El proveedor no puede ir vacío o llevar caracteres especiales!",
						  text: "¡Ingrese un número de documento correcto!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							//window.location = "proveedores";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	MOSTRAR PROVEEDORES
	=============================================*/

	static public function ctrMostrarProveedores($item, $valor){

		$tabla = "proveedor";

		$respuesta = ModeloProveedores::mdlMostrarProveedores($tabla, $item, $valor);

		return $respuesta;

	}
	
	/*=============================================
	ELIMINAR PROVEEDOR
	=============================================*/

	static public function ctrEliminarProveedor(){

		if(isset($_GET["idProveedor"])){

			$tabla ="proveedores";
			$datos = $_GET["idProveedor"];

			$respuesta = ModeloProveedores::mdlEliminarProveedor($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El proveedor ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result){
								if (result.value) {

								window.location = "proveedores";

								}
							})

				</script>';

			}		

		}

	}

////  TERMINA CLASE CONTROLADOR
}
