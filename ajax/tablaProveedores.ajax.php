<?php

require_once "../controladores/proveedores.controlador.php";
require_once "../modelos/proveedores.modelo.php";

class TablaProveedores{

 	/*=============================================
  	MOSTRAR LA TABLA DE USUARIOS
  	=============================================*/ 

	public function mostrarTabla(){	

		$item = null;
 		$valor = null;

 		$proveedores = ControladorProveedores::ctrMostrarProveedores($item, $valor);

 		$datosJson = '{
		 
	 	"data": [ ';

	 	for($i = 0; $i < count($proveedores); $i++){

			/*=============================================
  			REVISAR ESTADO
  			=============================================*/

  			if( $proveedores[$i]["estado"] == 0){

  				$colorEstado = "btn-danger";
  				$textoEstado = "Desactivado";
  				$estadoProveedor = 1;

  			}else{

  				$colorEstado = "btn-success";
  				$textoEstado = "Activado";
  				$estadoProveedor = 0;

  			}

  			//$estado = '<td><button class="btn btn-success btn-xs btnActivar '.$colorEstado.'" idProveedor="'.$proveedores[$i]["id_proveedor"].'" estadoProveedor="'.$estadoProveedor.'">'.$textoEstado.'</button></td>';
  			$estado = "<button class='btn ".$colorEstado." btn-xs btnActivar' estadoProveedor='".$estadoProveedor."' idProveedor='".$proveedores[$i]["id_proveedor"]."'>".$textoEstado."</button>";
  			/*=============================================
  			CREAR LAS ACCIONES
  			=============================================*/
	    
		    //$acciones = '<div class="btn-group"><button class="btn btn-warning btnEditarProveedor" data-toggle="modal" data-target="#modalEditarProveedor" idProveedor="'.$proveedores[$i]["id_proveedor"].'"><i class="fa fa-pencil"></i></button></div>';
		    $acciones = "<div class='btn-group'><button class='btn btn-warning btnEditarProveedor' idProveedor='".$proveedores[$i]["id_proveedor"]."' data-toggle='modal' data-target='#modalEditarProveedor'><i class='fa fa-pencil'></i></button></div>";

	 		/*=============================================
			DEVOLVER DATOS JSON
			=============================================*/

			$datosJson	 .= '[
				      "'.($i+1).'",
				      "'.$proveedores[$i]["documento"].'",
				      "'.$proveedores[$i]["razon_social"].'",
				      "'.$proveedores[$i]["telefono"].'",
				      "'.$proveedores[$i]["direccion"].'",
				      "'.$proveedores[$i]["email"].'",
				      "'.$proveedores[$i]["transacciones"].'",
				      "'.$proveedores[$i]["ultima_compra"].'",
				      "'.$estado.'",
				      "'.$acciones.'"    
				    ],';

	 	}

	 	$datosJson = substr($datosJson, 0, -1);

		$datosJson.=  ']
			  
		}'; 

		echo $datosJson;

 	}

}

/*=============================================
ACTIVAR TABLA DE VENTAS
=============================================*/ 
$activar = new TablaProveedores();
$activar -> mostrarTabla();



