<?php

if($_SESSION["administrador"] == "vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar proveedores
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar proveedores</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProveedor">
          
          Agregar proveedor

        </button>

      </div>

      <div class="box-body">
        <div class="box-tools"></div>
        
       <table class="table table-bordered table-striped dt-responsive tablas tablaProveedores" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Documento ID</th>
           <th>Razon Social</th>
           <th>Teléfono</th>
           <th>Dirección</th>
           <th>Email</th>
           <th>Transacciones</th> 
           <th>Ultima compra</th>
           <th>Estado</th>
           <th>Acciones</th>

         </tr> 

        </thead>


        <?php
/*
          $item = null;
          $valor = null;

          $clientes = ControladorProveedores::ctrMostrarProveedores($item, $valor);

          foreach ($clientes as $key => $value) {
            

            echo '<tr>

                    <td>'.($key+1).'</td>

                    <td>'.$value["documento"].'</td>
                    
                    <td>'.$value["razon_social"].'</td>

                    <td>'.$value["telefono"].'</td>

                    <td>'.$value["direccion"].'</td>

                    <td>'.$value["email"].'</td>

                    <td>'.$value["transacciones"].'</td>             

                    <td>'.$value["ultima_compra"].'</td>';

                    if($value["estado"] != 0){

                      echo '<td><button class="btn btn-success btn-xs btnActivar" idProveedor="'.$value["id_proveedor"].'" estadoProveedor="0">Activado</button></td>';

                    }else{

                      echo '<td><button class="btn btn-danger btn-xs btnActivar" idProveedor="'.$value["id_proveedor"].'" estadoProveedor="1">Desactivado</button></td>';

                    }

            echo '<td>

                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarProveedor" data-toggle="modal" data-target="#modalEditarProveedor" idProveedor="'.$value["id_proveedor"].'"><i class="fa fa-pencil"></i></button>';

                      if($_SESSION["perfil"] == "administrador"){
                        /*
                          echo '<button class="btn btn-danger btnEliminarProveedor" idProveedor="'.$value["id_proveedor"].' proveedor="'.$value["documento"].'"><i class="fa fa-times"></i></button>';
                          
                      }

                      echo '</div>  

                    </td>

                  </tr>';
          
            }
*/
        ?>
   

       </table>

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
        <input type="hidden" name="estadoRUC" id="estadoRUC" required>
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

            <!-- ENTRADA PARA EL DOCUMENTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" min="0" maxlength="11" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar documento" onKeyPress="if(this.value.length==11) return false;" required>
                <span class="input-group-addon" style="padding: 1px 1px;"><button style="padding: 10px 0px;" type="button" class="btn btn-success btn-md" onclick="consulta_sunat()">SUNAT</button></span>

              </div>

            </div>
            
            <!-- ENTRADA PARA LA RAZON SOCIAL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaRazon" placeholder="Ingresar RAZON SOCIAL" required>

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

            <!-- ENTRADA PARA EL EMAIL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>
  
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

        $crearProveedor = new ControladorProveedores();
        $crearProveedor -> ctrCrearProveedor();

      ?>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR PROVEEDOR
======================================-->

<div id="modalEditarProveedor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" onsubmit="return verificarRuc()">
        <input type="hidden" name="editarEstadoRUC" id="editarEstadoRUC" required>
        <input type="hidden" name="id_proveedor" id="id_proveedor" required>
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar proveedor</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL DOCUMENTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" min="0" maxlength="11" class="form-control input-lg" name="editarDocumento" id="editarDocumento" onKeyPress="if(this.value.length==11) return false;" required>
                <span class="input-group-addon" style="padding: 1px 1px;"><button style="padding: 10px 0px;" type="button" class="btn btn-success btn-md" onclick="consulta_sunat()">SUNAT</button></span>

              </div>

            </div>
            
            <!-- ENTRADA PARA LA RAZON SOCIAL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarProveedor" id="editarProveedor" required>
                
              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text" class="form-control input-lg" id="editarTelefono" name="editarTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask required>

              </div>

            </div>
            
            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" class="form-control input-lg" id="editarDireccion" name="editarDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="email" class="form-control input-lg" id="editarEmail" name="editarEmail" placeholder="Ingresar email" required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary" onclick="return verificarRuc()">Guardar cambios</button>

        </div>

      </form>

      <?php

        $editarProveedor = new ControladorProveedores();
        $editarProveedor -> ctrEditarProveedor();

      ?>

    

    </div>

  </div>

</div>

<?php

  $eliminarProveedor = new ControladorProveedores();
  $eliminarProveedor -> ctrEliminarProveedor();

?>


