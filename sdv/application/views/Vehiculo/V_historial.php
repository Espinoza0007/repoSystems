<!-- ESTILOS CSS-->
<link href="<?php echo base_url('dependencias/css/CSS_vehiculo.css') ?>" rel="stylesheet">
<!--ESTILOS CSS-->
<!-- Herramientas -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js"></script>

<!-- Fin de Herramientas -->
<!--JAVASCRIPTS JS-->
    <!-- <script type="text/javascript" src="<?php //echo base_url('dependencias/js/HP.js') ?>"></script> -->
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_historial.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
<!--JAVASCRIPTS JS-->   

<style> 
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #B2BABB;
      /*background-color: #ccc;*/
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 22px;
      width: 22px;
      left: 2px;
      bottom: 1px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(25px);
      -ms-transform: translateX(25px);
      transform: translateX(25px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 25px;
    }

    .slider.round:before {
      border-radius: 50%;
    }   
        
</style>
</head>
<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
    <?php
        defined('BASEPATH') OR exit('No direct script access allowed');
    ?>

<!-- Inicio del  Modal  -->
<!-- Inicio del  Modal  -->
<!-- Inicio del  Modal  -->
<!-- Inicio del  Modal  -->
<div class="modal fade-scale" id="m-cambio-contrasena" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header d_arriba">
      <span class="modal-title"  style="margin-top:-7px;">Recepcion  de Vehiculo</span>
          <!-- <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span> -->
      </div>
      <div class="modal-body">
        <form id="form_actualizar_pass" autocomplete="nope">
          <div class="col-md-12 celda">
 <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        h3 {
            text-align: center;
            border-bottom: 1px solid #000;
            padding: 10px 0;
        }
    </style>
    <!-- Incio de los Datos  -->
    <!-- Incio de los Datos  -->
    <!-- Incio de los Datos  -->
    <!-- Incio de los Datos  -->
        
<h3 style="text-align:center;">Datos del receptor</h3>
<table>
    <tr>
        <th>Nombre</th>
        <td id="repo-nombre-recive">Juan Pérez</td>
    </tr>
    <tr>
        <th>Código</th>
        <td id="repo-codigo-recive">123456</td>
    </tr>
</table>

<h3 style="text-align:center;">Datos del responsable</h3>
<table>

    <tr>
        <th>Nombre</th>
        <td id="resp-nombre-responsable">Juan Perez</td>
    </tr>
    <tr>
        <th>Código</th>
        <td id="resp-codigo-responsable">789012</td>
    </tr>

    <tr>
        <th>Ruta</th>
        <td id="resp-ruta-responsable">Ruta 5</td>
    </tr>
    <tr>
        <th>Número de licencia</th>
        <td id="resp-licencia-responsable">ABC123</td>
    </tr>
    <tr>
        <th>Clase de licencia</th>
        <td id="resp-claselicencia-responsable">Clase B</td>
    </tr>
</table>

<h3 style="text-align:center;">Datos del vehículo</h3>
<table>
    <tr>
        <th>Equipo</th>
        <td id="vehi-equipo">Equipo 1</td>
    </tr>
    <tr>
        <th>Placas</th>
        <td id="vehi-placas">XYZ789</td>
    </tr>
    <tr>
        <th>Marca</th>
        <td id="vehi-marca">Ford</td>
    </tr>
    <tr>
        <th>Tipo</th>
        <td id="vehi-tipo">SUV</td>
    </tr>
    <tr>
        <th>Año</th>
        <td id="vehi-anio">2020</td>
    </tr>
    <tr>
        <th>Número de motor</th>
        <td id="vehi-motor">1234567890</td>
    </tr>
    <tr>
        <th>Número de chasis</th>
        <td id="vehi-chasis">0987654321</td>
    </tr>
    <tr>
        <th>Combustible</th>
        <td id="resp-combustible">Gasolina</td>
    </tr>
    <tr>
        <th>Km actual</th>
        <td id="vehi-kilometraje">50000</td>
    </tr>
</table>

<h3 style="text-align:center;">Estado del Vehiculo</h3>

<style>
    body {
      font-family: Arial, sans-serif;
    }
    h1 {
      text-align: center;
    }
    ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
    }
    li {
      margin-bottom: 10px;
    }
    label {
      display: inline-block;
      width: 200px;
    }
    .radio-label {
      display: inline-block;
      width: 100px;
    }
    details {
      background-color: #eee;
      border: 1px solid #aaa;
      border-radius: 4px;
      margin-bottom: 8px;
      padding: 0.5em;
    }
    summary {
      font-weight: bold;
      margin: -0.5em;
      padding: 0.5em;
    }
    details[open] {
      padding: 0.5em;
    }
    details[open] summary {
      border-bottom: 1px solid #aaa;
      margin-bottom: 0.5em;
    }
  </style>
</head>
<body>
  <details>
  <summary>Parte Trasera</summary>
  <ul id="listaParteTrasera">
    <!-- Aquí colocas el contenido de tu lista -->
  </ul>
  </details>
  <details>
    <summary>Parte delantera</summary>
    <ul id="listaParteDelantera">
      <!-- Aquí colocas el contenido de tu lista -->
    </ul>
  </details>
  <details>
    <summary>Interior del Vehiculo</summary>
    <ul id="listaParteInterior">
      <!-- Aquí colocas el contenido de tu lista -->
    </ul>
  </details>
  <details>
    <summary>observación</summary>
      <!-- Observacion  -->
      <textarea id="observacion" disabled style="width: 100%; height: 100%;">
      </textarea>
     <!-- Observacion  -->
  </details>
          </div>
        </form>
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button> -->
          <button type="button" class="btn btn-info" id="btn_cerrar_modal"  >Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!--Fin del  Modal -->
<!-- Inicio del  Modal  -->
<!-- Inicio del  Modal  -->
<!-- Inicio del  Modal  -->

<!-- DIV CARGA -->
    <div id="content-carga" style="display:none;z-index: 30000;" class="carga-class">
        <div class="d-flex justify-content-center">
          <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
            <span class="sr-only">Cargando...</span>
           </div>
        </div>
    </div>
<!-- FIN DIV CARGA -->

<!-- NOTIFICACION ACTUALIZACION -->
   <div id="notificacion_ac" style="position: absolute;width: 100%;height: auto;display: none;">
        <div id="snackbar">Hay disponible una versión nueva de la aplicación. 
          <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a>
        </div>
    </div>
<!-- FIN NOTIFICACION ACTUALIZACION -->

<!-- BARRA MENU -->
    <nav class="navbar fixed-top navbar-expand-lg" id="menu_bar">
        <div style="float:left;" class="icons_posicion">
            <span class="fa fa-user fa-2x"></span>
            <span id="uslogin"></span>
        </div>
        <div style="margin:0 auto;" id="btn-pendinetes" onclick="" class="icons_posicion">
        <!-- <div style="margin:0 auto;" id="btn-pendinetes" onclick="sincronizar_pedidos_vnt()" class="icons_posicion"> -->
            <!-- <span class="fa fa-cloud-upload-alt fa-2x" style="color:#fff;"></span> -->
            <!-- Ver el historial de los vehiculos -->
            <div style="" class="icons_posicion" id="btn-recepcion">
            <span id="historial"  style="font-size: 14px;" class="badge badge-pill badge-dark" id="RegisCola">Recepcion de vehiculos</span> 
        </div>
            <!-- Fin de mostrar los vehiculos -->
        </div>
        <div style="" class="icons_posicion" id="btn-menu-back">
            <span class="fa fa-bars fa-2x" style=""></span>
        </div>
    </nav>
<!-- FIN BARRA MENU -->

<!-- CONTENEDOR -->
    <div class="row" id="content-map" style="margin-top:15px;display:none;">
      <div class="col-md-12 divrow">
        <div id="map" style="height: 277px;width: 100%;"></div>
      </div>
    </div>
    <div class="container" id="contenedor-principal" style="margin-top:50px;">
        <input type="hidden" value="" id="lat" name="lat">
        <input type="hidden" value="" id="lon" name="lon">
        <div style="width: 100%;" id="contenedor-formulario">
            <!-- <?php
                $datta = $this->session->flashdata('datta');
               if($datta){
            ?>
            <div style="width:100%;margin-top:2px;">
                <div class="alert alert-<?php echo $datta['cla'];?>" role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong><h3><?php echo $datta['ttmjs'];?></h3></strong>
                    <?php echo $datta['info']; ?>
                </div>
            </div>
            <?php
                }else{}
            ?> -->
            
        </div>
    </div>
<!-- FIN CONTENEDOR -->
    <div class="container-fluid" style="height: 100%;width:100%;">        
        <div class="row">
            <h5 class="card-header card-header-form" style="">
                Historial de Recepcion
            </h5>
        </div>

        <!-- Inicio de la tabla   -->
        <div class="table-responsive">
          <table id="example" class="display table" style="width:100%">
              <thead>
                  <tr>
                      <th>Placa</th>
                      <th>Ruta</th>
                      <th>Fecha</th>
                      <th>Reporte</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                  <tr>
                      <th>Placa</th>
                      <th>Ruta</th>
                      <th>Fecha</th>
                      <th>Reporte</th>
                  </tr>
              </tfoot>
          </table>
      </div>
        <!-- Tabla para los datos  -->


        <div style="background-color: #F7F9F9;width:100%;">
                      
        </div>

    </div>

<script>

</script>