<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Editar Compra
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Crear Compra</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="row">

      <!--=====================================
      EL FORMULARIO
      ======================================-->
      
      <div class="col-lg-5 col-xs-12">
        
        <div class="box box-success">
          
          <div class="box-header with-border"></div>

          <form role="form" method="post" class="formularioCompra">

            <div class="box-body">
  
              <div class="box">

                <?php

                    $item = "id";
                    $valor = $_GET["idCompra"];

                    $compra = ControladorCompras::ctrMostrarCompras($item, $valor);

                    $itemVendedor = "id";
                    $valorVendedor = $compra["id_vendedor"];

                    $vendedor = ControladorAdministradores::ctrMostrarAdministradores($itemVendedor, $valorVendedor);

                    $itemProveedor = "id_proveedor";
                    $valorProveedor = $compra["id_proveedor"];

                    $proveedor = ControladorProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);

                    $porcentajeImpuesto = $compra["igv"] * 100 / $compra["total"];


                ?>

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
            
                <div class="form-group">
                
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $vendedor["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $vendedor["id"]; ?>">

                  </div>

                </div> 

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                   <input type="text" class="form-control" id="editarCompra" name="editarCompra" value="<?php echo $compra["cod_com"]; ?>" required>

                   <input type="text" class="form-control" id="id_compra" name="id_compra" value="<?php echo $valor; ?>" readonly required>
               
                  </div>
                
                </div>

                <!--=====================================
                BUSQUEDA DEL PROVEEDOR
                ======================================-->
            
                <div class="form-group">

                  <div class="input-group col-xs-12">
                      
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 
                    
                    <select class="form-control col-xs-5" id="tipoDocumento" name="tipoDocumento" required>
                      <option value="" <?php  echo ($compra["tipo_doc"]=="")?"selected":""; ?>>Tipo Documento</option>
                      <option value="B" <?php  echo ($compra["tipo_doc"]=="B")?"selected":""; ?>>Boleta</option>
                      <option value="F" <?php  echo ($compra["tipo_doc"]=="F")?"selected":""; ?>>Factura</option>
                    </select>

                    <input type="text" class="form-control buscarProveedor col-xs-7" id="buscarProveedor"  placeholder="Ingresar documento del proveedor" name="buscarProveedor" value="<?php echo $compra["num_doc"]; ?>" required>

                  </div>

                </div> 
                <!--=====================================
                ENTRADA DEL PROVEEDOR
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    
                    <select class="form-control" id="seleccionarProveedor" name="seleccionarProveedor" required>

                    <option value="<?php echo $proveedor["id_proveedor"]; ?>"><?php echo $proveedor["razon_social"]; ?></option>

                    <?php
                      /*
                      $item = null;
                      $valor = null;

                      $categorias = ControladorClientes::ctrMostrarClientes($item, $valor);

                       foreach ($categorias as $key => $value) {

                         echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';

                       }
                       */

                    ?>

                    </select>
                    
                    <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAgregarProveedor" data-dismiss="modal">Agregar provedor</button></span>
                  
                  </div>
                
                </div>

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================--> 

                <div class="form-group row nuevoProducto">

                <?php

                $item_DCompra = "id_compra";

                $valor_DCompra = $compra["id"];

                $detalles = ControladorCompras::ctrMostrarDetalles($item_DCompra, $valor_DCompra/*, $orden*/);

                //$listaProducto = json_decode($venta["productos"], true);

                foreach ($detalles as $key => $value) {

                  $item = "id";
                  $valor = $value["id_producto"];
                  //$orden = "id";

                  $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor);

                  $stockAntiguo = $respuesta[0]["stock"] - $value["cantidad"];
                  
                  
                  echo '<div class="row" style="padding:5px 15px">
            
                        <div class="col-xs-6" style="padding-right:0px">
            
                          <div class="input-group">
                
                            <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto="'.$value["id_producto"].'"><i class="fa fa-times"></i></button></span>

                            <input type="text" class="form-control nuevaDescripcionProducto" idProducto="'.$value["id_producto"].'" name="agregarProducto" value="'.$respuesta[0]["titulo"].'" readonly required>

                          </div>

                        </div>

                        <div class="col-xs-3">
              
                          <input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="'.$value["cantidad"].'" stock="'.$stockAntiguo.'" nuevoStock="'.$respuesta[0]["stock"].'" required>

                        </div>

                        <div class="col-xs-3 ingresoPrecio" style="padding-left:0px">

                          <div class="input-group">

                            <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                   
                            <input type="text" class="form-control nuevoPrecioProducto" precioReal="'.$respuesta[0]["precio"].'" name="nuevoPrecioProducto" value="'.$respuesta[0]["precio"]*$value["cantidad"].'" readonly required>
   
                          </div>
               
                        </div>

                      </div>

                      <script>

                        $(function(){
                          sumarTotalPrecios();
                        });
  
                      </script>
                  



                      ';
                }


                ?>

                </div>

                <input type="hidden" id="listaProductos" name="listaProductos">

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>

                <hr>

                <div class="row">

                  <!--=====================================
                  ENTRADA IMPUESTOS Y TOTAL
                  ======================================-->
                  
                  <div class="col-xs-8 pull-right">
                    
                    <table class="table">

                      <thead>

                        <tr>
                          <th>Impuesto</th>
                          <th>Total</th>      
                        </tr>

                      </thead>

                      <tbody>
                      
                        <tr>
                          
                          <td style="width: 50%">
                            
                            <div class="input-group">
                           
                              <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoCompra" name="nuevoImpuestoCompra" value="<?php echo $porcentajeImpuesto; ?>" required>

                               <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" value="<?php echo $compra["igv"]; ?>" required>

                               <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" value="<?php echo $compra["subTotal"]; ?>" required>

                              <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        
                            </div>

                          </td>

                           <td style="width: 50%">
                            
                            <div class="input-group">
                           
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="nuevoTotalCompra" name="nuevoTotalCompra" total="<?php echo $compra["total"]; ?>"  value="<?php echo $compra["total"]; ?>" readonly required>

                              <input type="hidden" name="totalCompra" value="<?php echo $compra["total"]; ?>" id="totalCompra">
                              
                        
                            </div>

                          </td>

                        </tr>

                        <tr>
                          
                          <td style="width: 50%">
                            IGV
                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="number" class="form-control input-lg" min="0" id="nuevoPrecioImpuesto2" name="nuevoPrecioImpuesto2" value="<?php echo $compra["igv"]; ?>" placeholder="0" readonly>

                              <input type="hidden" name="totalIGV" id="totalIGV" value="<?php echo $compra["igv"]; ?>">


                            </div>

                          </td>

                           <td style="width: 50%">
                            
                            SubTotal
                            
                            <div class="input-group">
                           
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="nuevoPrecioNeto2" name="nuevoPrecioNeto2" total="" placeholder="00000" value="<?php echo $compra["subTotal"]; ?>" readonly required>

                              <input type="hidden" name="totalSubTotal" value="<?php echo $compra["subTotal"]; ?>" id="totalSubTotal">

                            </div>

                          </td>

                        </tr>

                      </tbody>

                    </table>

                  </div>

                </div>

                <hr>

                <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->

                <div class="form-group row">
                  
                  <div class="col-xs-6" style="padding-right:0px">
                    
                     <div class="input-group">
                  
                      <select class="form-control" id="nuevoMetodoPago" name="nuevoMetodoPago" required>
                        <option value="" <?php  echo ($compra["metodo_pago"]=="")?"selected":""; ?>>Seleccione método de pago</option>
                        <option value="Efectivo" <?php  echo ($compra["metodo_pago"]=="Efectivo")?"selected":""; ?>>Efectivo</option>
                        <option value="TC" <?php  echo ($compra["metodo_pago"]=="TC")?"selected":""; ?>>Tarjeta Crédito</option>
                        <option value="TD" <?php  echo ($compra["metodo_pago"]=="TD")?"selected":""; ?>>Tarjeta Débito</option>                  
                      </select>    

                    </div>

                  </div>

                  <div class="cajasMetodoPago"></div>

                  <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">

                </div>

                <br>
      
              </div>

          </div>

          <div class="box-footer">

            <button type="submit" class="btn btn-primary pull-right">Guardar cambios</button>

          </div>

        </form>

        <?php

          $editarCompra = new ControladorCompras();
          $editarCompra -> ctrEditarCompra();
          
        ?>

        </div>
            
      </div>

      <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->

      <div class="col-lg-7 hidden-md hidden-sm hidden-xs">
        
        <div class="box box-warning">

          <div class="box-header with-border"></div>

          <div class="box-body">
            
            <table class="table table-bordered table-striped dt-responsive tablaCompras">
              
               <thead>

                 <tr>
                  <th style="width: 10px">#</th>
                  <th>Imagen</th>
                  <th>Código</th>
                  <th>Descripcion</th>
                  <th>Stock</th>
                  <th>Acciones</th>
                </tr>

              </thead>

            </table>

          </div>

        </div>


      </div>

    </div>
   
  </section>

</div>

<!--=====================================
MODAL AGREGAR PROVEEDOR
======================================-->

<div id="modalAgregarProveedor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar proveedor</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" min="0" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar documento" required>

              </div>

            </div>
            
            <!-- ENTRADA PARA LA RAZON SOCIAL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaRazon" placeholder="Ingresar Razon social" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>
             -->
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar proveedor</button>

        </div>

      </form>

      <?php

        $crearCliente = new ControladorProveedores();
        $crearCliente -> ctrCrearProveedor();

      ?>

    </div>

  </div>

</div>
