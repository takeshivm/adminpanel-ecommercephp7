/*=============================================
CARGAR LA TABLA DINÁMICA DE VENTAS
=============================================*/

// $.ajax({

//  url: "ajax/datatable-ventas.ajax.php",
//  success:function(respuesta){
    
//    console.log("respuesta", respuesta);

//  }

// })// 

$("#buscarProveedor").on("keydown", function(e){
  
  removerSelectProveedores();

  //if (e.which == 13) {
  
    //console.log('Tecla enter presionada');
    $("#buscarProveedor").focus();
    buscarProveedor();

  //}

});


$("#tipoDocumento").change(function(){

  var tipo = $(this).val();

  if(tipo == "B"){

    $("#nuevoImpuestoCompra").val(0); 
    agregarImpuesto();

  }else if(tipo == "F"){

    $("#nuevoImpuestoCompra").val(18); 
    agregarImpuesto();

  }

});


$('.tablaCompras').DataTable( {
    "ajax": "ajax/datatable-compras.ajax.php",
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

} );


/*=============================================
AGREGANDO PRODUCTOS A LA VENTA DESDE LA TABLA
=============================================*/

$(".tablaCompras tbody").on("click", "button.agregarProducto", function(){

  var idProducto = $(this).attr("idProducto");

  $(this).removeClass("btn-primary agregarProducto");

  $(this).addClass("btn-default");

  var datos = new FormData();
    datos.append("idProducto", idProducto);

     $.ajax({

      url:"ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){
            //console.log(respuesta);
            //$res = json_decode(respuesta);
            var descripcion = respuesta[0]["ruta"];
            var stock = respuesta[0]["stock"];
            var precio = respuesta[0]["precio"];

            /*=============================================
            EVITAR AGREGAR PRODUTO CUANDO EL STOCK ESTÁ EN CERO
            =============================================*/
            /*
            if(stock == 0){

              swal({
                title: "No hay stock disponible",
                type: "error",
                confirmButtonText: "¡Cerrar!"
              });

              $("button[idProducto='"+idProducto+"']").addClass("btn-primary agregarProducto");

              return; 

              }

              */

              $(".nuevoProducto").append(

              '<div class="row" style="padding:5px 15px">'+

              '<!-- Descripción del producto -->'+
                  
                  '<div class="col-xs-6" style="padding-right:0px">'+
                  
                    '<div class="input-group">'+
                      
                      '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="'+idProducto+'"><i class="fa fa-times"></i></button></span>'+

                      '<input type="text" class="form-control nuevaDescripcionProducto" idProducto="'+idProducto+'" name="agregarProducto" value="'+descripcion+'" readonly required>'+

                    '</div>'+

                  '</div>'+

                  '<!-- Cantidad del producto -->'+

                  '<div class="col-xs-3">'+
                    
                     '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="1" stock="'+stock+'" nuevoStock="'+(Number(stock) + 1)+'" required>'+

                  '</div>' +

                  '<!-- Precio del producto -->'+

                  '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">'+

                    '<div class="input-group">'+

                      '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+
                         
                      '<input type="text" class="form-control nuevoPrecioProducto" precioReal="'+precio+'" name="nuevoPrecioProducto" value="'+precio+'" readonly required>'+
         
                    '</div>'+
                     
                  '</div>'+

                '</div>') 

            // SUMAR TOTAL DE PRECIOS

            sumarTotalPrecios()

            // AGREGAR IMPUESTO

            agregarImpuesto()

            // AGRUPAR PRODUCTOS EN FORMATO JSON

            listarProductos()

            // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

            $(".nuevoPrecioProducto").number(true, 2);


        localStorage.removeItem("quitarProducto");

        }

     })

});


/*=============================================
CUANDO CARGUE LA TABLA CADA VEZ QUE NAVEGUE EN ELLA
=============================================*/

$(".tablaCompras").on("draw.dt", function(){

  if(localStorage.getItem("quitarProducto") != null){

    var listaIdProductos = JSON.parse(localStorage.getItem("quitarProducto"));

    for(var i = 0; i < listaIdProductos.length; i++){

      $("button.recuperarBoton[idProducto='"+listaIdProductos[i]["idProducto"]+"']").removeClass('btn-default');
      $("button.recuperarBoton[idProducto='"+listaIdProductos[i]["idProducto"]+"']").addClass('btn-primary agregarProducto');

    }


  }


})


/*=============================================
QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];

localStorage.removeItem("quitarProducto");

$(".formularioCompra").on("click", "button.quitarProducto", function(){

  $(this).parent().parent().parent().parent().remove();

  var idProducto = $(this).attr("idProducto");

  /*=============================================
  ALMACENAR EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR
  =============================================*/

  if(localStorage.getItem("quitarProducto") == null){

    idQuitarProducto = [];
  
  }else{

    idQuitarProducto.concat(localStorage.getItem("quitarProducto"))

  }

  idQuitarProducto.push({"idProducto":idProducto});

  localStorage.setItem("quitarProducto", JSON.stringify(idQuitarProducto));

  $("button.recuperarBoton[idProducto='"+idProducto+"']").removeClass('btn-default');

  $("button.recuperarBoton[idProducto='"+idProducto+"']").addClass('btn-primary agregarProducto');

  if($(".nuevoProducto").children().length == 0){

    $("#nuevoImpuestoCompra").val(0);
    $("#nuevoTotalCompra").val(0);
    $("#totalCompra").val(0);
    $("#nuevoTotalCompra").attr("total",0);

  }else{

      // SUMAR TOTAL DE PRECIOS

        sumarTotalPrecios()

      // AGREGAR IMPUESTO
          
        agregarImpuesto()

        // AGRUPAR PRODUCTOS EN FORMATO JSON

        listarProductos()

  }

})


/*=============================================
AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numProducto = 0;

$(".btnAgregarProducto").click(function(){
  //console.log('trayendo productos');
  numProducto ++;

  var datos = new FormData();
  datos.append("traerProductos", "ok");

  $.ajax({

    url:"ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){
            
              $(".nuevoProducto").append(

            '<div class="row" style="padding:5px 15px">'+

            '<!-- Descripción del producto -->'+
                
                '<div class="col-xs-6" style="padding-right:0px">'+
                
                  '<div class="input-group">'+
                    
                    '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto><i class="fa fa-times"></i></button></span>'+

                    '<select class="form-control nuevaDescripcionProducto" id="producto'+numProducto+'" idProducto name="nuevaDescripcionProducto" required>'+

                    '<option>Seleccione el producto</option>'+

                    '</select>'+  

                  '</div>'+

                '</div>'+

                '<!-- Cantidad del producto -->'+

                '<div class="col-xs-3 ingresoCantidad">'+
                  
                   '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="0" stock nuevoStock required>'+

                '</div>' +

                '<!-- Precio del producto -->'+

                '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">'+

                  '<div class="input-group">'+

                    '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+
                       
                    '<input type="text" class="form-control nuevoPrecioProducto" precioReal="" name="nuevoPrecioProducto" readonly required>'+
       
                  '</div>'+
                   
                '</div>'+

              '</div>');


          // AGREGAR LOS PRODUCTOS AL SELECT 

           respuesta.forEach(funcionForEach);

           function funcionForEach(item, index){

            if(item.stock != 0){

              $("#producto"+numProducto).append(

            '<option idProducto="'+item.id+'" value="'+item.titulo+'">'+item.titulo+'</option>'
              )

             
             }           

           }

           // SUMAR TOTAL DE PRECIOS

          sumarTotalPrecios()

          // AGREGAR IMPUESTO
            
          agregarImpuesto()

          // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

          $(".nuevoPrecioProducto").number(true, 2);


        }

  })

})


/*=============================================
SELECCIONAR PRODUCTO
=============================================*/

$(".formularioCompra").on("change", "select.nuevaDescripcionProducto", function(){

  var nombreProducto = $(this).val();

  var nuevaDescripcionProducto = $(this).parent().parent().parent().children().children().children(".nuevaDescripcionProducto");

  var nuevoPrecioProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

  var nuevaCantidadProducto = $(this).parent().parent().parent().children(".ingresoCantidad").children(".nuevaCantidadProducto");

  var datos = new FormData();
    datos.append("nombreProducto", nombreProducto);


    $.ajax({

      url:"ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){
            
             $(nuevaDescripcionProducto).attr("idProducto", respuesta[0]["id"]);
            $(nuevaCantidadProducto).attr("stock", respuesta[0]["stock"]);
            $(nuevaCantidadProducto).attr("nuevoStock", Number(respuesta[0]["stock"])+1);
            $(nuevaCantidadProducto).val(1);
            $(nuevoPrecioProducto).val(respuesta[0]["precio"]);
            $(nuevoPrecioProducto).attr("precioReal", respuesta[0]["precio"]);
            /*
            $(nuevoPrecioProducto).val(respuesta["precio_venta"]);
            $(nuevoPrecioProducto).attr("precioReal", respuesta["precio_venta"]);
            */

          // AGRUPAR PRODUCTOS EN FORMATO JSON

          listarProductos();
          sumarTotalPrecios();
          agregarImpuesto()
        }

      })
})


/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/

$(".formularioCompra").on("change", "input.nuevaCantidadProducto", function(){

  var precio = $(this).parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

  var precioFinal = $(this).val() * precio.attr("precioReal");
  
  precio.val(precioFinal);

  var nuevoStock = Number($(this).attr("stock")) + Number($(this).val());

  $(this).attr("nuevoStock", nuevoStock);
/*
  if(Number($(this).val()) > Number($(this).attr("stock"))){

    /*=============================================
    SI LA CANTIDAD ES SUPERIOR AL STOCK REGRESAR VALORES INICIALES
    =============================================*/
/*
    $(this).val(0);

    $(this).attr("nuevoStock", $(this).attr("stock"));

    var precioFinal = $(this).val() * precio.attr("precioReal");

    precio.val(precioFinal);

    sumarTotalPrecios();

    swal({
        title: "La cantidad supera el Stock",
        text: "¡Sólo hay "+$(this).attr("stock")+" unidades!",
        type: "error",
        confirmButtonText: "¡Cerrar!"
      });

      return;

  }
*/
  // SUMAR TOTAL DE PRECIOS

  sumarTotalPrecios()

  // AGREGAR IMPUESTO
          
    agregarImpuesto()

    // AGRUPAR PRODUCTOS EN FORMATO JSON

    listarProductos()

})


/*=============================================
BORRAR COMPRA
=============================================*/
$(".tablas").on("click", ".btnEliminarCompra", function(){

  var idCompra = $(this).attr("idCompra");

  swal({
        title: '¿Está seguro de anular la compra?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, anular compra!'
      }).then(function(result){
        if (result.value) {
          
            window.location = "index.php?ruta=compras&idCompra="+idCompra;
        }

  })

})



/*=============================================
SUMAR TODOS LOS PRECIOS
=============================================*/

function sumarTotalPrecios(){

  var precioItem = $(".nuevoPrecioProducto");
  
  var arraySumaPrecio = [];  

  for(var i = 0; i < precioItem.length; i++){

     arraySumaPrecio.push(Number($(precioItem[i]).val()));
    
  }

  function sumaArrayPrecios(total, numero){

    return total + numero;

  }

  var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);
  
  $("#nuevoTotalCompra").val(sumaTotalPrecio);
  $("#totalCompra").val(sumaTotalPrecio);
  $("#nuevoTotalCompra").attr("total",sumaTotalPrecio);


}


/*=============================================
FUNCIÓN AGREGAR IMPUESTO
=============================================*/

function agregarImpuesto(){

  var impuesto = $("#nuevoImpuestoCompra").val();
  var precioTotal = $("#nuevoTotalCompra").attr("total");

  var precioImpuesto = Number(precioTotal * impuesto/100);

  // VENTA CON IMPUESTO ES EL 18% DEL PRECIO TOTAL
  //var totalConImpuesto = Number(precioImpuesto) + Number(precioTotal);
  
  // VENTA CON IMPUESTO ES EL 18% DEL PRECIO TOTAL
  var subTotalCompra = Number(precioTotal) - Number(precioImpuesto);
  
  $("#nuevoTotalCompra").val(precioTotal);

  //POR DEFECTO
  //$("#nuevoTotalCompra").val(totalConImpuesto);

  //TOTALCOMPRA POR DEFECTO
  //$("#totalCompra").val(totalConImpuesto);

  //TOTALCOMPRA ES EL PRECIO TOTAL o PRECIO NETO
  $("#totalCompra").val(precioTotal);

  $("#nuevoPrecioImpuesto").val(precioImpuesto);
  $("#nuevoPrecioImpuesto2").val(precioImpuesto);

  $("#nuevoPrecioNeto").val(precioTotal);
  $("#totalIGV").val(precioImpuesto);
  $("#nuevoPrecioNeto2").val(subTotalCompra);
  $("#totalSubTotal").val(subTotalCompra);

}

/*=============================================
CUANDO CAMBIA EL IMPUESTO
=============================================*/

$("#nuevoImpuestoCompra").change(function(){

  agregarImpuesto();

});

/*=============================================
FORMATO AL PRECIO FINAL
=============================================*/

$("#nuevoTotalCompra").number(true, 2);


/*=============================================
SELECCIONAR MÉTODO DE PAGO
=============================================*/

$("#nuevoMetodoPago").change(function(){

  var metodo = $(this).val();

  if(metodo == "Efectivo"){

  /*

    $(this).parent().parent().removeClass("col-xs-6");

    $(this).parent().parent().addClass("col-xs-4");

    $(this).parent().parent().parent().children(".cajasMetodoPago").html(

       '<div class="col-xs-4">'+ 

        '<div class="input-group">'+ 

          '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+ 

          '<input type="text" class="form-control" id="nuevoValorEfectivo" placeholder="000000" required>'+

        '</div>'+

       '</div>'+

       '<div class="col-xs-4" id="capturarCambioEfectivo" style="padding-left:0px">'+

        '<div class="input-group">'+

          '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

          '<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000" readonly required>'+

        '</div>'+

       '</div>'

     )

        // Agregar formato al precio

        $('#nuevoValorEfectivo').number( true, 2);
        $('#nuevoCambioEfectivo').number( true, 2);


        // Listar método en la entrada
        listarMetodos()


  */
        $(this).parent().parent().removeClass("col-xs-6");
        
        $(this).parent().parent().addClass("col-xs-4");

        $(this).parent().parent().parent().children('.cajasMetodoPago').html('')


        listarMetodos()

  }else{

    $(this).parent().parent().removeClass('col-xs-4');

    $(this).parent().parent().addClass('col-xs-6');

     $(this).parent().parent().parent().children('.cajasMetodoPago').html(

      '<div class="col-xs-6" style="padding-left:0px">'+
                        
                '<div class="input-group">'+
                     
                  '<input type="number" min="0" class="form-control" id="nuevoCodigoTransaccion" name="nuevoCodigoTransaccion" placeholder="Código transacción"  required>'+
                       
                  '<span class="input-group-addon"><i class="fa fa-lock"></i></span>'+
                  
                '</div>'+

              '</div>')

  }

  

})

/*=============================================
CAMBIO EN EFECTIVO
=============================================*/
$(".formularioCompra").on("change", "input#nuevoValorEfectivo", function(){

  var efectivo = $(this).val();

  var cambio =  Number(efectivo) - Number($('#nuevoTotalCompra').val());

  var nuevoCambioEfectivo = $(this).parent().parent().parent().children('#capturarCambioEfectivo').children().children('#nuevoCambioEfectivo');

  nuevoCambioEfectivo.val(cambio);

})


/*=============================================
CAMBIO TRANSACCIÓN
=============================================*/
$(".formularioCompra").on("change", "input#nuevoCodigoTransaccion", function(){

  // Listar método en la entrada
     listarMetodos()

})


/*=============================================
LISTAR TODOS LOS PRODUCTOS
=============================================*/

function listarProductos(){

  var listaProductos = [];

  var descripcion = $(".nuevaDescripcionProducto");

  var cantidad = $(".nuevaCantidadProducto");

  var precio = $(".nuevoPrecioProducto");

  for(var i = 0; i < descripcion.length; i++){

    listaProductos.push({ "id" : $(descripcion[i]).attr("idProducto"), 
                "descripcion" : $(descripcion[i]).val(),
                "cantidad" : $(cantidad[i]).val(),
                "stock" : $(cantidad[i]).attr("nuevoStock"),
                "precio" : $(precio[i]).attr("precioReal"),
                "total" : $(precio[i]).val()});

  }

  $("#listaProductos").val(JSON.stringify(listaProductos)); 

  $strJson = JSON.stringify(listaProductos);
  ////////console.log("<br>");
  ////////console.log(json_decode);
}

/*=============================================
LISTAR MÉTODO DE PAGO
=============================================*/

function listarMetodos(){

  var listaMetodos = "";

  if($("#nuevoMetodoPago").val() == "Efectivo"){

    $("#listaMetodoPago").val("Efectivo");

  }else{

    $("#listaMetodoPago").val($("#nuevoMetodoPago").val()+"-"+$("#nuevoCodigoTransaccion").val());

  }

}


/*=============================================
BOTON EDITAR COMPRA
=============================================*/
$(".tablas").on("click", ".btnEditarCompra", function(){

  var idCompra = $(this).attr("idCompra");

  window.location = "index.php?ruta=editar-compra&idCompra="+idCompra;


})



/*=============================================
FUNCIÓN PARA DESACTIVAR LOS BOTONES AGREGAR CUANDO EL PRODUCTO YA HABÍA SIDO SELECCIONADO EN LA CARPETA
=============================================*/

function quitarAgregarProducto(){

  //Capturamos todos los id de productos que fueron elegidos en la compra
  var idProductos = $(".quitarProducto");

  //Capturamos todos los botones de agregar que aparecen en la tabla
  var botonesTabla = $(".tablaCompras tbody button.agregarProducto");

  //Recorremos en un ciclo para obtener los diferentes idProductos que fueron agregados a la compra
  for(var i = 0; i < idProductos.length; i++){

    //Capturamos los Id de los productos agregados a la compra
    var boton = $(idProductos[i]).attr("idProducto");
    
    //Hacemos un recorrido por la tabla que aparece para desactivar los botones de agregar
    for(var j = 0; j < botonesTabla.length; j ++){

      if($(botonesTabla[j]).attr("idProducto") == boton){

        $(botonesTabla[j]).removeClass("btn-primary agregarProducto");
        $(botonesTabla[j]).addClass("btn-default");

      }
    }

  }
  
}


/*=============================================
CADA VEZ QUE CARGUE LA TABLA CUANDO NAVEGAMOS EN ELLA EJECUTAR LA FUNCIÓN:
=============================================*/

$('.tablaCompras').on( 'draw.dt', function(){

  quitarAgregarProducto();

})


/*=============================================
RANGO DE FECHAS
=============================================*/

$('#daterange-btn-compras').daterangepicker(
  {
    ranges   : {
      'Hoy'       : [moment(), moment()],
      'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
      'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
      'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
      'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment(),
    endDate  : moment()
  },
  function (start, end) {
    $('#daterange-btn-compras span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    var fechaInicial = start.format('YYYY-MM-DD');

    var fechaFinal = end.format('YYYY-MM-DD');

    var capturarRango = $("#daterange-btn-compras span").html();
   
    localStorage.setItem("capturarRango", capturarRango);

    window.location = "index.php?ruta=compras&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;
    //////console.log('se ejecuto el daterangepicker');
  }

)

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function(){

  localStorage.removeItem("capturarRango");
  localStorage.clear();
  window.location = "compras";
})

/*=============================================
CAPTURAR HOY
=============================================*/

$(".daterangepicker.opensleft .ranges li").on("click", function(){

  var textoHoy = $(this).attr("data-range-key");

  if(textoHoy == "Hoy"){

    var d = new Date();
    
    var dia = d.getDate();
    var mes = d.getMonth()+1;
    var año = d.getFullYear();

    // if(mes < 10){

    //  var fechaInicial = año+"-0"+mes+"-"+dia;
    //  var fechaFinal = año+"-0"+mes+"-"+dia;

    // }else if(dia < 10){

    //  var fechaInicial = año+"-"+mes+"-0"+dia;
    //  var fechaFinal = año+"-"+mes+"-0"+dia;

    // }else if(mes < 10 && dia < 10){

    //  var fechaInicial = año+"-0"+mes+"-0"+dia;
    //  var fechaFinal = año+"-0"+mes+"-0"+dia;

    // }else{

    //  var fechaInicial = año+"-"+mes+"-"+dia;
   //     var fechaFinal = año+"-"+mes+"-"+dia;

    // }

    dia = ("0"+dia).slice(-2);
    mes = ("0"+mes).slice(-2);

    var fechaInicial = año+"-"+mes+"-"+dia;
    var fechaFinal = año+"-"+mes+"-"+dia; 

      localStorage.setItem("capturarRango", "Hoy");

      window.location = "index.php?ruta=compras&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

  }

})


/*=============================================
BUSCAR AL PROVEEDOR CON EL DNI
=============================================*/
function buscarProveedor(){
  ////console.log('buscando');
  removerSelectProveedores();
  // OBETENEMOS EL DOC INGRESADO
  var doc = $("#buscarProveedor").val();
  
  var datos = new FormData();
  datos.append("doc", doc);

    $.ajax({

    url:"ajax/compras.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
        //console.log('Cantidad de la respuesta', respuesta.length);
        //console.log(respuesta);

        //ENVIAMOS LA RESPUESTA AL METODO PARA QUE IMPRIMA
        // EL RESULTADO EN EL SELECT
        enviarRespuestaProveedores(respuesta);
        
      }
  })    
}


/*=============================================
FUNCION PARA EL ARRAY DE LA RESPUESTA. (BUSCAR PROVEEDORES)
=============================================*/
function enviarRespuestaProveedores(respuesta){

  //  LIMPIAMOS EL BOMBO BOX (SELECT)
  //removerSelectProveedores();

  // RECORREMOS EL ARRAY DE LA RESPUESTA Y 
  for (var i = 0; i < respuesta.length; i++) {
   $("#seleccionarProveedor").append(
    '<option value="'+respuesta[i]["id_proveedor"]+'">'+respuesta[i]["razon_social"]+'</option>') 
  }

  /*
  for (var i = respuesta.length - 1; i < JSON(respuesta).length; i++) {
   $("#seleccionarProveedor").append(
    '<option value="'+respuesta[i]["id_proveedor"]+'">'+respuesta[i]["razon_social"]+'</option>') 
  }
  $("#seleccionarProveedor").append(
    '<option value="'+respuesta[0]["id_proveedor"]+'">'+respuesta[0]["razon_social"]+'</option>'
  )*/
}


function removerSelectProveedores() {

  var x = document.getElementById("seleccionarProveedor");
  
  if (x.length >= 0) {
    for (var i = 0; i <= x.length; i++) {
      x.remove(i);
    }
  }

}

/*=============================================
  INCIALIZAMOS EL ESTADO DEL RUC EN FALSE
  PARA QUE NO PUEDO REGISTRARLO
=============================================*/
var estadoRUC = false;
$("#estadoRUC").attr({
  value: estadoRUC
});
$('input[name="editarEstadoRUC"]').attr({
  value: estadoRUC
});

/*=============================================
  CONSULTAR EL RUC DEL PROVEEDOR EN SUNAT.
=============================================*/
function consulta_sunat(){

  var num = $('input[name="nuevoDocumentoId"]').val();
  if (num == "") {num = $('#editarDocumento').val()}
  if(num!=''){

    //$.getJSON("https://apis.sitefact.pe/api/ConsultaRuc",{ruc:num})
    $.ajax({
        type: "GET", //GET, POST, PUT
        url: 'https://apiperu.dev/api/ruc/'+num,  //the url to call
        contentType: "application/json",           
        beforeSend: function (xhr) {   //Set token here
            xhr.setRequestHeader("Authorization", 'Bearer '+ '8b616a6e9997310fcf5b727d479a30ec3bd33e094041ab4c37fe11460a2aed1b');
        }
    })
     .done(function(json){
          
          //console.log(json);
         //if(json.result.RUC.length != undefined || json.success != false){
         if(json.success != false){
            
            $('input[name="nuevoDocumentoId"]').val(json.data.ruc);
            $('input[name="nuevaDireccion"]').val(json.data.direccion);
            $('input[name="nuevaRazon"]').val(json.data.nombre_o_razon_social);

            $('input[name="editarDocumento"]').val(json.data.ruc);
            $('input[name="editarDireccion"]').val(json.data.direccion);
            $('input[name="editarProveedor"]').val(json.data.nombre_o_razon_social);
            
            swal({
              title: "Datos encontrados con exito",
              type: "success",
              confirmButtonText: "¡Ok!"
            });
            
            estadoRUC = true;
            $("#estadoRUC").attr({
              value: estadoRUC
            });
            $('input[name="editarEstadoRUC"]').attr({
              value: estadoRUC
            });

         }else{

            var mensajeSunat = "Documento no existe en SUNAT";
            mensajeErrorSwal(mensajeSunat);

         }
     });

  }else{

        var mensajeSunat = "";
        mensajeErrorSwal(mensajeSunat); 

  }
    
}

// MENSAJE PERSONZALIDO DE ERROR EN EL ENVIO DEL RUC A SUNAT
function mensajeErrorSwal($mensaje){

  if ($mensaje != "") {

    swal({
      title: $mensaje,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      type: "warning",
      confirmButtonText: "¡Ok!"
    });

  }else{

    swal({
      title: "Ingrese un número de documento",
      type: "error",
      confirmButtonText: "¡Cerrar!"
    });

  }

  estadoRUC = false;
  $("#estadoRUC").attr({
    value: estadoRUC
  });
  $('input[name="editarEstadoRUC"]').attr({
    value: estadoRUC
  });

  $('input[name="nuevaDireccion"]').val("");
  $('input[name="nuevaRazon"]').val("");

}


/*=============================================
  BOTON PARA IMPRIMIR FACTURA O BOLETA
=============================================*/


$(".tablas").on("click", ".btnImprimirFactura", function(){

  var codigoVenta = $(this).attr("codigoCompra");

  window.open("extensiones/tcpdf/pdf/factura.php?codigo="+codigoVenta, "_blank");

})
