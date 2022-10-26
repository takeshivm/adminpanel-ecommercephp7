<?php

require_once "conexion.php";

class ModeloProductos{

	/*=============================================
	MOSTRAR EL TOTAL DE VENTAS
	=============================================*/	

	static public function mdlMostrarTotalProductos($tabla, $orden){
	
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden DESC");

		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt-> close();

		$stmt = null;

	}


	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	

	static public function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR SUMA COMPRAS
	=============================================*/	

	static public function mdlMostrarSumaCompras($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(cantidad) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTOS
	=============================================*/

	static public function mdlActualizarProductos($tabla, $item1, $valor1, $item2, $valor2){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;
		
	}

	/*=============================================
	ACTUALIZAR OFERTA PRODUCTOS
	=============================================*/
	static public function mdlActualizarOfertaProductos($tabla, $datos, $ofertadoPor, $precioOfertaActualizado, $descuentoOfertaActualizado, $idOferta){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $ofertadoPor = :$ofertadoPor, oferta = :oferta, precioOferta = :precioOferta, descuentoOferta = :descuentoOferta, imgOferta = :imgOferta, finOferta = :finOferta WHERE id = :id");

		$stmt->bindParam(":".$ofertadoPor, $datos["oferta"], PDO::PARAM_STR);
		$stmt->bindParam(":oferta", $datos["oferta"], PDO::PARAM_STR);
		$stmt->bindParam(":precioOferta", $precioOfertaActualizado, PDO::PARAM_STR);
		$stmt->bindParam(":descuentoOferta", $descuentoOfertaActualizado, PDO::PARAM_STR);
		$stmt->bindParam(":imgOferta", $datos["imgOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":finOferta", $datos["finOferta"], PDO::PARAM_STR);
		$stmt -> bindParam(":id", $idOferta, PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;


	}

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id DESC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;


	}


	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductosOrden($tabla, $item, $valor, $orden){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla p JOIN detalle_compra d on p.id = d.id_producto GROUP by id_producto ORDER BY $orden DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla p JOIN detalle_compra d on p.id = d.id_producto GROUP by id_producto ORDER BY $orden DESC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	CREAR PRODUCTO
	=============================================*/

	static public function mdlIngresarProducto($tabla, $datos){
		
		$idOferta = null;

		if ($datos["oferta"] == 1) {
			
			$db = Conexion::conectar();

			$stmtO = $db->prepare("INSERT INTO oferta(descripcion, estado, precio, descuento, fin_oferta) VALUES (:descripcion, 1, :precio, :descuento, :fin_oferta)");

			$stmtO->bindParam(":descripcion", $datos["titulo"], PDO::PARAM_STR);
			//$stmtO->bindParam(":estado", 1, PDO::PARAM_STR);
			$stmtO->bindParam(":precio", $datos["precioOferta"], PDO::PARAM_STR);
			$stmtO->bindParam(":descuento", $datos["descuentoOferta"], PDO::PARAM_STR);
			$stmtO->bindParam(":fin_oferta", $datos["finOferta"], PDO::PARAM_STR);
			
			if($stmtO->execute()){
				
				$stmte = $db->query("SELECT LAST_INSERT_ID()");
				$idOferta = $stmte->fetchColumn();

			}

		}


		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, id_subcategoria, tipo, ruta, estado, titulo, titular, descripcion, multimedia, detalles, precio, portada, oferta, precioOferta, descuentoOferta, imgOferta, finOferta, peso, entrega, id_oferta, stock, codigo) VALUES (:id_categoria, :id_subcategoria, :tipo, :ruta, :estado, :titulo, :titular, :descripcion, :multimedia, :detalles, :precio, :portada, :oferta, :precioOferta, :descuentoOferta, :imgOferta, :finOferta,  :peso, :entrega, :id_oferta, :stock, :codigo)");

		$stmt->bindParam(":id_categoria", $datos["idCategoria"], PDO::PARAM_STR);
		$stmt->bindParam(":id_subcategoria", $datos["idSubCategoria"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);
		$stmt->bindParam(":ruta", $datos["ruta"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
		$stmt->bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR);
		$stmt->bindParam(":titular", $datos["titular"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":multimedia", $datos["multimedia"], PDO::PARAM_STR);
		$stmt->bindParam(":detalles", $datos["detalles"], PDO::PARAM_STR);
		$stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
		$stmt->bindParam(":portada", $datos["imgFotoPrincipal"], PDO::PARAM_STR);
		$stmt->bindParam(":oferta", $datos["oferta"], PDO::PARAM_STR);
		$stmt->bindParam(":precioOferta", $datos["precioOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":descuentoOferta", $datos["descuentoOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":imgOferta", $datos["imgOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":finOferta", $datos["finOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":peso", $datos["peso"], PDO::PARAM_STR);
		$stmt->bindParam(":entrega", $datos["entrega"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":id_oferta", $idOferta, PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmtO->close();
		$stmte->close();
		$stmt = null;
		$stmtO = null;
		$stmte = null;

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/

	static public function mdlEditarProducto($tabla, $datos){

		$dbP = Conexion::conectar();

		$stmt = $dbP->prepare("UPDATE $tabla SET id_categoria = :id_categoria, id_subcategoria = :id_subcategoria, tipo = :tipo, ruta = :ruta, estado = :estado, titulo = :titulo, titular = :titular, descripcion = :descripcion, multimedia = :multimedia, detalles = :detalles, precio = :precio, portada = :portada, oferta = :oferta, precioOferta = :precioOferta, descuentoOferta = :descuentoOferta, imgOferta = :imgOferta, finOferta = :finOferta, peso = :peso, entrega = :entrega, stock = :stock, codigo = :codigo WHERE id = :id");

		$stmt->bindParam(":id_categoria", $datos["idCategoria"], PDO::PARAM_STR);
		$stmt->bindParam(":id_subcategoria", $datos["idSubCategoria"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);
		$stmt->bindParam(":ruta", $datos["ruta"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
		$stmt->bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR);
		$stmt->bindParam(":titular", $datos["titular"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":multimedia", $datos["multimedia"], PDO::PARAM_STR);
		$stmt->bindParam(":detalles", $datos["detalles"], PDO::PARAM_STR);
		$stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
		$stmt->bindParam(":portada", $datos["imgFotoPrincipal"], PDO::PARAM_STR);
		$stmt->bindParam(":oferta", $datos["oferta"], PDO::PARAM_STR);
		$stmt->bindParam(":precioOferta", $datos["precioOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":descuentoOferta", $datos["descuentoOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":imgOferta", $datos["imgOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":finOferta", $datos["finOferta"], PDO::PARAM_STR);
		$stmt->bindParam(":peso", $datos["peso"], PDO::PARAM_STR);
		$stmt->bindParam(":entrega", $datos["entrega"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);
		
		/*	DESPUES DE EDITAR EL PRODUCTO, VERIFICAMOS SI SE CAMBIO LA OFERTA, EN 
			CASO SE HAYA MODIFICADO LA OFERTA, INSERTAMOS UNA NUEVA OFERTA CON LOS 
			VALORES MODIFICADOS Y DESACTIVAMOS LAS OFERTAS ANTERIORES DE DICHO PRODUCTO.
		*/
		
		if($stmt->execute()){
				
			if ($datos["oferta"] == 1) {
				
				$db = Conexion::conectar();

				$stmtO = $db->prepare("INSERT INTO oferta(descripcion, estado, precio, descuento, fin_oferta, id_producto) VALUES (:descripcion, 1, :precio, :descuento, :fin_oferta, :id_producto)");

				$stmtO->bindParam(":descripcion", $datos["titulo"], PDO::PARAM_STR);
				//$stmtO->bindParam(":estado", 1, PDO::PARAM_STR);
				$stmtO->bindParam(":precio", $datos["precioOferta"], PDO::PARAM_STR);
				$stmtO->bindParam(":descuento", $datos["descuentoOferta"], PDO::PARAM_STR);
				$stmtO->bindParam(":fin_oferta", $datos["finOferta"], PDO::PARAM_STR);
				$stmtO->bindParam(":id_producto", $datos["id"], PDO::PARAM_STR);
				
				if($stmtO->execute()){
					
					$stmteO = $db->query("SELECT LAST_INSERT_ID()");

					$idOferta = $stmteO->fetchColumn();

					ModeloProductos::mdlCambiarEstadoOferta($idOferta, $datos["id"]);
				}
			}

			return "ok";

		}else{

			return "error";

		}


		$stmt->close();
		$stmtO->close();
		$stmt = null;
		$stmtO = null;

	}

	/*=============================================
	ELIMINAR PRODUCTO
	=============================================*/

	static public function mdlEliminarProducto($tabla, $datos){

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
	CAMBIAR ESTADOS DE OFERTAS ANTERIORES
	=============================================*/

	static public function mdlCambiarEstadoOferta($id, $idProducto){

		$stmt = Conexion::conectar()->prepare("UPDATE oferta SET estado = 0 WHERE id != :id AND id_producto = :id_producto");

		$stmt -> bindParam(":id", $id, PDO::PARAM_INT);
		$stmt -> bindParam(":id_producto", $idProducto, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

}