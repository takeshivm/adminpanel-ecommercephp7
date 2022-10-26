<?php

$item = null;
$valor = null;

$compras = ControladorCompras::ctrMostrarCompras($item, $valor);
$Proveedores = ControladorProveedores::ctrMostrarProveedores($item, $valor);

$arrayProveedores = array();
$arraylistaProveedores = array();

foreach ($compras as $key => $valueCompras) {
  
  foreach ($Proveedores as $key => $valueProveedores) {
    
      if($valueProveedores["id_proveedor"] == $valueCompras["id_proveedor"]){

        #Capturamos los Proveedores en un array
        array_push($arrayProveedores, $valueProveedores["razon_social"]);

        #Capturamos las nombres y los valores netos en un mismo array
        $arraylistaProveedores = array($valueProveedores["razon_social"] => $valueCompras["total"]);

        #Sumamos los netos de cada cliente
        foreach ($arraylistaProveedores as $key => $value) {
          
          $sumaTotalProveedores[$key] += $value;
        
        }

      }   
  }

}

#Evitamos repetir nombre
$noRepetirNombres = array_unique($arrayProveedores);

?>

<!--=====================================
VENDEDORES
======================================-->

<div class="box box-primary">
	
	<div class="box-header with-border">
    
    	<h3 class="box-title">Proveedores</h3>
  
  	</div>

  	<div class="box-body">
  		
		<div class="chart-responsive">
			
			<div class="chart" id="bar-chart3" style="height: 300px;"></div>

		</div>

  	</div>

</div>

<script>
	
//BAR CHART
var bar = new Morris.Bar({
  element: 'bar-chart3',
  resize: true,
  data: [
     <?php
    
    foreach($noRepetirNombres as $value){

      echo "{y: '".$value."', a: '".$sumaTotalProveedores[$value]."'},";

    }

  ?>
  ],
  barColors: ['#a6a'],
  xkey: 'y',
  ykeys: ['a'],
  labels: ['compras'],
  preUnits: '$',
  hideHover: 'auto'
});


</script>