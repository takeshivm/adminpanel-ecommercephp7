<?php

require_once "../../controladores/compras.controlador.php";
require_once "../../modelos/compras.modelo.php";
require_once "../../controladores/proveedores.controlador.php";
require_once "../../modelos/proveedores.modelo.php";
require_once "../../controladores/administradores.controlador.php";
require_once "../../modelos/administradores.modelo.php";
require_once "../../controladores/productos.controlador.php";
require_once "../../modelos/productos.modelo.php";

$reporte = new ControladorCompras();
$reporte -> ctrDescargarReporteCompras();