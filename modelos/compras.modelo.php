<?php

require_once "conexion.php";

class ModeloCompras{

	/*=============================================
	REGISTRO DE COMPRA
	=============================================*/

	static public function mdlIngresarCompra($tabla, $datos){
		$db = Conexion::conectar();
		$stmt = $db->prepare("INSERT INTO $tabla(tipo_doc, num_doc, id_proveedor, subTotal, igv, total, cod_com, id_vendedor, metodo_pago, codigo_trans, anular) VALUES (:tipo_doc, :num_doc, :id_proveedor, :subTotal, :igv, :total, :cod_com, :id_vendedor, :metodo_pago, :codigo_trans, 0)");


		$stmt->bindParam(":tipo_doc", $datos["tipo_doc"], PDO::PARAM_STR);
		$stmt->bindParam(":num_doc", $datos["num_doc"], PDO::PARAM_STR);
		$stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
		$stmt->bindParam(":subTotal", $datos["subTotal"], PDO::PARAM_STR);
		$stmt->bindParam(":igv", $datos["igv"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":cod_com", $datos["cod_com"], PDO::PARAM_STR);
		$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
		$stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo_trans", $datos["codigo_trans"], PDO::PARAM_STR);
		//$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$arrayJson = json_decode($_POST["listaProductos"], true);

		
		if($stmt->execute()){
			

			$stmte = $db->query("SELECT LAST_INSERT_ID()");
			$idC = $stmte->fetchColumn();			

			foreach ($arrayJson as $key => $value) {

				$id = $value["id"];
				$cantidad = $value["cantidad"];

				$stmt1 = Conexion::conectar()->prepare("INSERT INTO detalle_compra(id_compra, id_producto, cantidad) VALUES ($idC, $id, $cantidad)");

				$stmt1->execute();

			}

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt1->close();
		$db->close();

		$stmt = null;
		$stmt1 = null;
		$db = null;
		//$stmt1 = null;

	}

	/*=============================================
	EDITAR COMPRA
	=============================================*/

	static public function mdlEditarCompra($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fecha_mod = :fecha_mod, tipo_doc = :tipo_doc, num_doc = :num_doc, id_proveedor = :id_proveedor, subTotal = :subTotal, igv = :igv, total= :total, cod_com = :cod_com, id_vendedor = :id_vendedor, metodo_pago = :metodo_pago, codigo_trans = :codigo_trans WHERE id = :id");

		$stmt->bindParam(":fecha_mod", $datos["fecha_mod"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_doc", $datos["tipo_doc"], PDO::PARAM_STR);
		$stmt->bindParam(":num_doc", $datos["num_doc"], PDO::PARAM_STR);
		$stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
		$stmt->bindParam(":subTotal", $datos["subTotal"], PDO::PARAM_STR);
		$stmt->bindParam(":igv", $datos["igv"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":cod_com", $datos["cod_com"], PDO::PARAM_STR);
		$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
		$stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo_trans", $datos["codigo_trans"], PDO::PARAM_STR);
		$stmt->bindParam(":id", $datos["id_compra"], PDO::PARAM_INT);


		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}


	/*=============================================
	INSERTAR DETALLE COMPRA
	=============================================*/

	static public function mdlInsertarDetallesCompra($valorCompra, $valorProducto, $cantidad){

		$stmt = Conexion::conectar()->prepare("INSERT INTO detalle_compra(id_compra, id_producto, cantidad) VALUES (:idCompra, :idProducto, :cantidad)");
		//var_dump($valorCompra, $valorProducto, $cantidad);

		$stmt->bindParam(":idCompra", $valorCompra, PDO::PARAM_INT);
		$stmt->bindParam(":idProducto", $valorProducto, PDO::PARAM_INT);
		$stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	MOSTRAR COMPRAS
	=============================================*/

	static public function mdlMostrarCompras($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		
		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR TOTAL COMPRA
	=============================================*/

	static public function mdlMostrarTotalCompras($tabla){


		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		
		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR DETALLES DE COMPRA
	=============================================*/

	static public function mdlMostrarDetalles($tabla, $item, $valor){


		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetchAll();

		
		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasCompras($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_reg like '%$fechaFinal%'");

			$stmt -> bindParam(":fecha_reg", $fechaFinal, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_reg BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_reg BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}


	/*=============================================
	ACTUALIZAR COMPRA
	=============================================*/

	static public function mdlActualizarCompra($tabla, $item1, $valor1, $valor){


		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		//$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		//$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	ELIMINAR COMPRA -> SOLO EN CASOS ESPECIALES
	=============================================*/

	static public function mdlEliminarCompra($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	ELIMINAR DETALLES DE COMPRA
	=============================================*/

	static public function mdlEliminarDetalles($tabla, $item, $valor){


		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $item = :$item");

		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

		$stmt -> execute();
		
		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}


//////  TERMINA LA CLASE MODELO COMPRAS
}

