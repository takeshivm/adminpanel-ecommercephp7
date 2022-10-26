<?php

if($_SESSION["perfil"] != "administrador"){

echo '<script>

  window.location = "inicio";

</script>';

return;

}

?>

<div class="content-wrapper">
  
   <section class="content-header">
      
    <h1>
      Gestor ventas
    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Gestor ventas</li>
      
    </ol>

  </section>


  <section class="content">

    <div class="box"> 

      <div class="box-header with-border">
        
        <?php

        include "inicio/grafico-ventas.php";

        ?>

      </div>

      <div class="box-body">

        <div class="box-tools">

          <a href="vistas/modulos/reportes.php?reporte=compras">
            
              <button class="btn btn-success">Descargar reporte en Excel</button>

          </a>

        </div>

        <br>
        
        <table class="table table-bordered table-striped dt-responsive tablaVentas" width="100%">
        
          <thead>
            
            <tr>
              
              <th style="width:10px">#</th>
              <th>Producto</th>
              <th>Imagen Producto</th>
              <th>Cliente</th>
              <th>Foto Cliente</th>
              <th>Venta</th>
              <th>Tipo</th>  
              <th>Proceso de envío</th>         
              <th>Metodo</th>
              <th>Email</th>
              <th>Dirección</th>
              <th>País</th>
              <th>Fecha</th>
              <th>Acción</th>

            </tr>

          </thead> 


        </table>


      </div>

    </div>

  </section>

</div>


<!--=====================================
VENTANA MODAL PARA MOSTRAR VENTA, SELECCIONADA
PREVIAMENTE EN EL DETALLE DE LAS COMPRAS.
BOTON -> MOSTRAR DETALLE VENTA
======================================-->

<div  class="modal fade modalDetalleVenta" id="modalDetalleVenta" role="dialog">
  
  <div class="modal-content modal-dialog modal-lg">
    
    <div class="modal-body">
      
      <div id="inventory-invoice">

          <div class="toolbar hidden-print">
              <div class="text-right">
                  <button id="printInvoiceeditado" class="btn btn-info" onclick="PrintThisDiv()"><i class="fa fa-print"></i> Imprimir</button>
                  <a href="javascript:generarPDF()" class="button">
                    <button class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Exportar como PDF</button>
                  </a>
              </div>
              <hr>
          </div>
          <div class="invoice imprimirDocumento overflow-auto" id="imprimirDocumento">
              <div style="min-width: 600px">
                  <header>
                      <div class="row">
                          <div class="col">
                              <a target="_blank" href="#">
                                  <img src="<?php echo $servidor.$social["logo"]; ?>" class="img-responsive">
                                  </a>
                          </div>
                          <div class="col company-details">
                              <h2 class="name">
                                  <a target="_blank" href="#">
                                  E-Commerce Cix
                                  </a>
                              </h2>
                              <div>San Jose #542, Chiclayo - 14011, PERÚ</div>
                              <div>(074) 589023</div>
                              <div>info@ecommercecix.com</div>
                          </div>
                      </div>
                  </header>
                  <main>
                      <div class="row contacts">
                          <div class="col invoice-to">
                              <div class="text-gray-light">Boleta para:</div>
                              <h2 class="nombreCliente"></h2>
                              <div class="direccionCliente"></div>
                              <div class="emailCliente"><a href="mailto:<?php echo $_SESSION["email"]?>"></a></div>
                              <div class="telefonoCliente"></div>
                              <div class="documentoCliente"></div>
                          </div>
                          <div class="col invoice-details">
                              <h1 class="numeroVenta">INVOICE 121</h1>
                              <div class="fechaVenta">Date of Invoice: 28/11/2018</div>
                              <!--<div class="fechaVentaVencimiento">Due Date: 28/11/2018</div>-->
                          </div>
                      </div>
                      <table id="tablaDetalleVenta" border="0" cellspacing="0" cellpadding="0">
                          <thead>
                              <tr>
                                  <th>N°.</th>
                                  <th class="text-left">DESCRIPCIÓN</th>
                                  <th class="text-right">PRECIO</th>
                                  <th class="text-right">IMPUESTO (18%)</th>
                                  <th class="text-right">TOTAL</th>
                              </tr>
                          </thead>
                          <tbody id="bodyDetalleVenta">
                            <!--
                              <tr>
                                  <td class="no">01</td>
                                  <td class="text-left"><h3>Description 1</h3>Testing Description 1</td>
                                  <td class="unit">₹ 0.00</td>
                                  <td class="tax">10%</td>
                                  <td class="total">₹ 0.00</td>
                              </tr>
                              <tr>
                                  <td class="no">02</td>
                                  <td class="text-left"><h3>Description 2</h3>Testing Description 2</td>
                                  <td class="unit">₹ 40.00</td>
                                  <td class="tax">13%</td>
                                  <td class="total">₹ 1,200.00</td>
                              </tr>
                              -->
                          </tbody>
                          <tfoot id="footDetalleVenta">
                              <tr>
                                  <td colspan="2"></td>
                                  <td colspan="2">SUBTOTAL</td>
                                  <td>S/. 5,200.00</td>
                              </tr>
                              <tr>
                                  <td colspan="2"></td>
                                  <td colspan="2">TAX 25%</td>
                                  <td>S/. 1,300.00</td>
                              </tr>
                              <tr>
                                  <td colspan="2"></td>
                                  <td colspan="2">GRAND TOTAL</td>
                                  <td>S/. 6,500.00</td>
                              </tr>
                          </tfoot>
                      </table>
                      <div class="thanks">Gracias por su preferencia!</div>
                      <div class="notices">
                          <div>AVISO:</div>
                          <div class="notice">Documento generado automaticamente por el sistema, Gracias.</div>
                      </div>
                  </main>
                  <footer>
                      Representación real del documento de venta emitido por la empresa, por lo tanto la validez es la misma.
                  </footer>
                  <div class="pageBreak"></div>
              </div>
              <div></div>
          </div>
      </div>

    </div>

    <div class="modal-footer">
        
        </div>

  </div>

</div>