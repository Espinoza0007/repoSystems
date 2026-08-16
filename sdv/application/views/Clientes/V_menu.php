<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <!--ESTILOS CSS-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_menu.css')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
  <!--JAVASCRIPTS JS-->
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_menu.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/xlsx.full.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
  <!-- Cargar jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Cargar jQuery Mask Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>


<style>
.btngr_backup{
    background-color: #465966;
    color:#fff;
    font-weight: bold;
    text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.5);
    width: 96%;
    height: 105px;
    font-size: 18px;
    border:1px solid #323639;
    border-bottom: 4px solid #323639;
    border-right: 4px solid #323639;
    padding-bottom: 5px;
    margin-bottom: 7px;
    border-radius: 3px;
    outline: none !important;
    box-shadow: none !important;
}

#snackbar {
    top:0;
    visibility: hidden;
    min-width: 250px;
    /* margin-left: -125px; */
    background-color: #333;
    color: #fff;
    text-align: center;
    /* border-radius: 2px; */
    padding: 16px;
    /*position: fixed;*/
    /*position: absolute;*/
    z-index: 9999;
    /* left: 50%; */
    /* bottom: 30px; */
    margin-bottom: 7px;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: bold;
}

#snackbar.show {
    visibility: visible;
    /* -webkit-animation: fadein 0.5s;
    animation: fadein 0.5s; */
}

@-webkit-keyframes fadein {
  from {
    bottom: 0;
    opacity: 0;
  }
  to {
    bottom: 30px;
    opacity: 1;
  }
}

@keyframes fadein {
  from {
    bottom: 0;
    opacity: 0;
  }
  to {
    bottom: 30px;
    opacity: 1;
  }
}

#reload{
  font-weight:bold;
}
#seguridad{
  width:83px;
  margin-top:7px;
}
#content_pass{
 /* padding:27px; */
 text-align:left;
 font-weight:600;
}
#menjs_pass{
  color:#F71313;
  font-weight:600;
  /* margin-top:7px; */
  text-align:left;
  margin-top:3px;
}
.eye_btn{
  border-radius:0px;
}
.passwordClas{
  -webkit-text-security: square;
  /* font-size:10px; */
}

</style>
</head>
<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
  <nav class="navbar fixed-top navbar-expand-lg" id="menu_bar">
    <div style="float:left;" class="icons_posicion">
        <span class="fa fa-user fa-2x"></span>
        <span id="uslogin"></span>
    </div>
    <div style="margin:0 auto;" id="btn-pendinetes" class="icons_posicion">
    </div>
    <div style="" class="icons_posicion" id="btn-menu-back">
        <span class="fa fa-bars fa-2x" style=""></span>
    </div>
  </nav>
  <!--000000---DIV CARGANDO---000000-->
  <div id="content-carga" style="display:none;" class="carga-class">
    <div class="d-flex justify-content-center">
      <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
        <span class="sr-only">Cargando...</span>
       </div>
    </div>
  </div>

  <div id="notificacion_ac" style="margin-top: 50px;position: fixed;top: 0;width: 100%;height: auto;display: none;">
    <div id="snackbar">Hay disponible una nueva versión. <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a></div>
  </div>

  <div id="principal_menu" style="margin-top: 58px;justify-content: center;align-items: center;text-align: center;" >
  <!-- <span id="titulo_menu">Men&uacute;</span> -->
    <h3 style="color:red;display: none;" id="t_taskforce"></h3>
    <div id="menu_boton">
      <div id="slc_ruta_">
          <div class="slc_ruta d-flex align-items-center">
              <div class="col-12">
                  <span class="" style="padding: 10px;">SELECCIONAR RUTA</span><br>
                  <div id="select_ruta_desarrollador">
                      <select id="slc_ruta_desarrollador" name="slc_ruta_desarrollador" class="form-control" style="margin: auto;">
                          <option value="1990">1.9.90</option>
                      </select>
                  </div>
              </div>
          </div>
        </div>
      <!-- <button class="btngr_venta" id="btn_recepcion_vehiculo">
          <span class="fas fa-truck fa-2x" style="padding: 10px;"></span><br>
          Recepción de vehículo
      </button> -->
      <button class="btngr_verde" id="o-cliente-nuevo">
          <span class="fas fa-user-plus fa-2x" style="padding: 10px;"></span><br>
          Clientes nuevos
      </button>
      <button class="btngr_verde" id="carga_clienteN" style="display: none;">
          <span class="fas fa-user-plus fa-2x" style="padding: 10px;"></span><br>
          Clientes nuevos
      </button>

      <button class="btngr_azul_m" id="o-actualizar-coordenada">
          <span class="fas fa-edit fa-2x" style="padding: 10px;"></span><br>
          Actualizaci&oacute;n de clientes
      </button>
      <button class="btngr_azul_m" id="carga_actualizarco" style="display: none;">
          <span class="fas fa-edit fa-2x" style="padding: 10px;"></span><br>
          Actualizaci&oacute;n de clientes
      </button>

      <button class="btngr_nayib" id="encuesta-de-exhibidores">
          <span class="fas fa-book-open fa-2x" style="padding: 10px;"></span><br>
          Actualizaci&oacute;n de Exhibidores
      </button>
      <button class="btngr_nayib" id="carga_encuestaexhi" style="display: none;">
          <span class="fas fa-book-open fa-2x" style="padding: 10px;"></span><br>
          Actualizaci&oacute;n de Exhibidores
      </button>
      <button class="btngr_celeste" id="btnNuevoReclamo">
          <span class="fas fa-folder-plus fa-2x" style="padding: 10px;"></span><br>
          Reclamos de productos
      </button> 
      <button class="btngr_azul_m" id="btn_control_inventario">
          <span class="fas fa-clipboard-list fa-2x" style="padding: 10px;"></span><br>
          Control de inventario
      </button> 
      <button class="btngr_anaranjado" id="carga_pedidosugerido" style="display: none;">
          <span class="fas fa-clipboard-list fa-2x" style="padding: 10px;"></span><br>
          Carga Optima
      </button> 

      <!-- <button class="btngr_venta" id="btn_recepcion_vehiculo">
          <span class="fas fa-truck fa-2x" style="padding: 10px;"></span><br>
          Recepción de vehículo
      </button> -->

        <!--   -->
        <button class="btngr_mercado" id="btn_control_mercado">
          <span class="fas fa-clipboard-list fa-2x" style="padding: 10px;"></span><br>
          Evaluacion de Mercado
      </button> 
      <!--   -->
      <button class="btngr_anaranjado" id="btn_pedido_sugerido">
          <span class="fas fa-clipboard-list fa-2x" style="padding: 10px;"></span><br>
          Carga Optima
      </button> 
      <button class="btngr_amarillopatito" id="sincronizar">
          <span class="fas fa-cloud-download-alt fa-2x" style="padding: 10px;"></span><br>
          Sincronizar
      </button>
      <!-- <button class="btngr_anaranjado_m" id="configuracion">
          <span class="fas fa-wrench fa-2x" style="padding: 10px;"></span><br>
          Configuraci&oacute;n
      </button> -->

       <!-- <button class="btngr_anaranjado_m" id="encuesta-de-exhibidores_no" onclick="registros_colaClientesN()">
          <span class="fas fa-upload fa-2x" style="padding: 10px;"></span><br>
          Enviar Base de Datos
      </button> -->

      <!-- <button class="btngr_anaranjado_m" id="encuesta-de-exhibidores_no" onclick="registros_colaClientesAC()">
          <span class="fas fa-upload fa-2x" style="padding: 10px;"></span><br>
          Enviar Base de Datos AC
      </button>
  -->
      <button class="btngr_rojo" id="salir-sdv">
          <span class="fas fa-door-closed fa-2x" style="padding: 10px;"></span><br>
          Salir
      </button>
    </div>
    <div id="sincronizando_loading" style="display: none;">
        <span id="nube" class="fas fa-cloud-download-alt fa-5x" style="padding: 10px;"></span>
        <h4 id="titulo_sincro">SINCRONIZANDO POR FAVOR ESPERE...</h4>
        <span id="tuerca" class="fas fa-cog fa-spin fa-5x" style="padding: 10px;"></span>
        <span id="db" class="fas fa-database fa-5x" style="padding: 10px;"></span>
    </div>




</div>
<!-- <div id="backup_clientesNuevos" style="display: none;">
</div><br> -->
<!-- <button class="btn btn-success" onclick="registros_colaClientesN()" type="button">Descargar Clientes</button> -->
<!-- MODAL CAMBIO DE CONTRASEÑA -->
<div class="modal fade-scale" id="m-cambio-contrasena" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header d_arriba">
          <span class="modal-title" style="margin-top:-7px;">CAMBIO DE CONTRASEÑA</span>
          <!-- <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span> -->
      </div>
      <div class="modal-body">
        <img id="seguridad" src="<?php echo base_url('dependencias/imagenes/seguridad.png')?>">
        <form id="form_actualizar_pass" autocomplete="nope">
          <div class="col-md-12 celda">
            <div id="menjs_pass">
              <span id="C_ocho" class="">* Mayor o igual 8 caracteres.</span><br>
              <span id="C_mayu" class="">* Al menos 1 letra mayúscula</span><br>
              <span id="C_minu" class="">* Al menos 1 letra minúscula</span><br>
              <span id="C_nume" class="">* Al menos 1 número</span><br>
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top:3px;" id="content_pass">
                  <div class="titulo">Contraseña actual: </div>
                  <div class="input-group">
                    <input type="text" class="form-control passwordClas" name="txtPassactual" id="txtPassactual" value="" autocomplete="nope">
                    <button id="Passactual" class="btn btn-secondary eye_btn" type="button" onclick="mostrarPassword(this.id)"> <span id="iconPassactual" class="fa fa-eye-slash icon"></span> </button>
                  </div>
                  <div class="valid-feedback">
                  </div>
                  <div class="invalid-feedback" id="error-mjs-0">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top:3px;" id="content_pass">
                  <div class="titulo">Contraseña nueva: </div>
                  <div class="input-group">
                    <input type="text" class="form-control passwordClas" name="txtPassNuevo" id="txtPassNuevo" value="" autocomplete="new-password">
                    <button id="PassNuevo" class="btn btn-secondary eye_btn" type="button" onclick="mostrarPassword(this.id)"> <span id="iconPassNuevo" class="fa fa-eye-slash icon"></span> </button>
                  </div>
                  <div class="valid-feedback">
                  </div>
                  <div class="invalid-feedback" id="error-mjs-0">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top:3px;" id="content_pass">
                  <div class="titulo">Repetir contraseña nueva: </div>
                  <div class="input-group">
                    <input type="text" class="form-control passwordClas" name="txtPassNuevoR" id="txtPassNuevoR" value="" autocomplete="new-password">
                    <button id="PassNuevoR" class="btn btn-secondary eye_btn" type="button" onclick="mostrarPassword(this.id)"> <span id="iconPassNuevoR" class="fa fa-eye-slash icon"></span> </button>
                  </div>
                  <div class="valid-feedback">
                  </div>
                  <div class="invalid-feedback" id="error-mjs-0">
                  </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button> -->
          <button type="button" class="btn btn-info" id="btn_guardar_cti" onclick="GuardarPassNuevos()" >Guardar cambios</button>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODAL CAMBIO DE CONTRASEÑA -->