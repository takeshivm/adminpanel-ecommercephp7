<?php

if($_SESSION["perfil"] == "Especial"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Crear Compra
    
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

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
            
                <div class="form-group">
                
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">

                  </div>

                </div> 

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                    <input type="text" class="form-control" id="nuevaCompra" name="nuevaCompra" placeholder="Ingrese el codigo de la compra" maxlength="20" required>

                    <?php
                    /*
                    $item = null;
                    $valor = null;

                    $compras = ControladorCompras::ctrMostrarCompras($item, $valor);

                    if(!$compras){

                      echo '<input type="text" class="form-control" id="nuevaCompra" name="nuevaCompra" value="10001" readonly>';
                  

                    }else{

                      foreach ($compras as $key => $value) {
                        
                        
                      
                      }

                      $codigo = $value["cod_com"] + 1;



                      echo '<input type="text" class="form-control" id="nuevaCompra" name="nuevaCompra" value="'.$codigo.'" readonly>';
                  

                    }
                    */
                    ?>
                    
                    
                  </div>
                
                </div>
                

                <!--=====================================
                BUSQUEDA DEL PROVEEDOR
                ======================================-->
            
                <div class="form-group">

                  <div class="input-group col-xs-12">
                      
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 
                    
                    <select class="form-control col-xs-5" id="tipoDocumento" name="tipoDocumento" required>
                      <option value="">Tipo Documento</option>
                      <option value="B">Boleta</option>
                      <option value="F">Factura</option>
                    </select>

                    <input type="text" class="form-control buscarProveedor col-xs-7" id="buscarProveedor"  placeholder="Ingresar documento del proveedor" name="buscarProveedor" required>

                  </div>

                </div> 


                <!--=====================================
                ENTRADA DEL PROVEEDOR
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    
                    <select class="form-control" id="seleccionarProveedor" name="seleccionarProveedor" required>

                    <!-- <option value="">Seleccionar Proveedor</option> 

                    <?php/*

                      $item = null;
                      $valor = null;

                      $categorias = ControladorProveedores::ctrMostrarProveedores($item, $valor);
                       foreach ($categorias as $key => $value) {

                         //echo '<option value="'.$value["id_proveedor"].'">'.$value["razon_social"].'</option>';

                       }*/

                    ?>
                    -->

                    </select>
                    
                    <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAgregarProveedor" data-dismiss="modal">Agregar proveedor</button></span>
                  
                  </div>
                
                </div>

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================--> 

                <div class="form-group row nuevoProducto">

                

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
                           
                              <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoCompra" name="nuevoImpuestoCompra" placeholder="0" required>

                               <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" required>

                               <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" required>

                              <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        
                            </div>

                          </td>

                           <td style="width: 50%">
                            
                            <div class="input-group">
                           
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="nuevoTotalCompra" name="nuevoTotalCompra" total="" placeholder="00000" readonly required>

                              <input type="hidden" name="totalCompra" id="totalCompra">
                              
                        
                            </div>

                          </td>

                        </tr>

                        <tr>
                          
                          <td style="width: 50%">
                            IGV
                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="number" class="form-control input-lg" min="0" id="nuevoPrecioImpuesto2" name="nuevoPrecioImpuesto2" placeholder="0" readonly>

                              <input type="hidden" name="totalIGV" id="totalIGV">


                            </div>

                          </td>

                           <td style="width: 50%">
                            
                            SubTotal
                            
                            <div class="input-group">
                           
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="nuevoPrecioNeto2" name="nuevoPrecioNeto2" total="" placeholder="00000" readonly required>

                              <input type="hidden" name="totalSubTotal" id="totalSubTotal">

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
                        <option value="">Seleccione método de pago</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="TC">Tarjeta Crédito</option>
                        <option value="TD">Tarjeta Débito</option>                  
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

            <button type="submit" class="btn btn-primary pull-right">Guardar compra</button>

          </div>

        </form>

        <?php

          $guardarVenta = new ControladorCompras();
          $guardarVenta -> ctrCrearCompra();
          
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

            <input type="hidden" name="estadoRUC" id="estadoRUC" required>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->

            
            
            <div class="form-group">
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" min="0" maxlength="11" class="form-control input-lg col-lg-4" name="nuevoDocumentoId" placeholder="Ingresar documento"  onKeyPress="if(this.value.length==11) return false;" required>

                <span class="input-group-addon" style="padding: 1px 1px;"><button style="padding: 10px 0px;" type="button" class="btn btn-success btn-md" onclick="consulta_sunat()">SUNAT</button></span>

              </div>

              <!--
              ///Boton para ingresar numero de hasta 4 digitos <input type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" />
              <button style="width: 100%;float: right;margin-left: 1%; padding: 12px 0px" type="button" id="doc_proveedor" class="btn btn-primary col-xs-12 col-sm-2 col-md-4 col-lg-4" onclick="consulta_sunat()">SUNAT</button>
              -->
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
