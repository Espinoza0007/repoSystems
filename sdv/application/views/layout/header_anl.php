<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
	<title>SDV Bocadeli</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta name="MobileOptimized" content="width">
  <meta name="HandheldFriendly" content="true">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="shortcut icon" type="image/ico" href="<?php echo base_url('dependencias/imagenes/bocalogo192.png')?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/jquery3.3.1/jqueryCustomScrollbar.css') ?>">
  <link rel="apple-touch-icon" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>" media="(min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>" media="(min-device-width: 834px) and (max-device-width: 834px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/sdvlog192.png')?>" media="(min-device-width: 1024px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="manifest" href="<?php echo base_url('manifest.json');?>"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/bootstrap4.3/css/bootstrap.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/bootstrap4.3/css/bootstrap-grid.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_cargando.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('dependencias/css/menu.css'); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/alertify/css/alertify.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/alertify/css/themes/default.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/leaflet/leaflet.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/reportes.css');?>">
  <link href="<?php echo base_url('dependencias/fontawesome-free-5.13.0/css/all.css') ?>" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url('dependencias/jquery3.3.1/jquery-3.3.1.js');?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-mask-plugin-master/src/jquery.mask.js') ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/bootstrap4.3/js/bootstrap.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/bootstrap4.3/js/bootstrap.bundle.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/js_formulario.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/alertify/js/alertify.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/sweetalert/sweetalert2.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/select2-4.0.7/js/select2.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/moment/moment.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/moment/locale/es.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/reportes.js') ?>"></script>
  <style type="text/css">
		body{
			margin: 0;
			padding: 0;
			background-color:#f1f1f1;
		}
    #usuariosesion{
      color: #fff;
      font-size: 12px;
    }
    #piedepagina{
      position: fixed;
      bottom: 0;
      width:100%;
      z-index: 10;
      background-color:#2F91B0;
      font-weight: 700;
      color: #fff;
    }
    .zoom{
        /* Aumentamos la anchura y altura durante 2 segundos */
        transition: width 1s, height 1s, transform 1s;
        -moz-transition: width 1s, height 1s, -moz-transform 1s;
        -webkit-transition: width 1s, height 1s, -webkit-transform 1s;
        -o-transition: width 1s, height 1s,-o-transform 1s;
    }
    .zoom:hover{
        /* tranformamos el elemento al pasar el mouse por encima al doble de
           su tamaño con scale(2). */
        transform : scale(2);
        -moz-transform : scale(2);      /* Firefox */
        -webkit-transform : scale(2);   /* Chrome - Safari */
        -o-transform : scale(2);        /* Opera */
    }
    .components span{
      font-weight: 700;
      text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.2);
    }
    .letra{
      font-weight: 700;
      text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.1); 
    }
	</style>

  <script>
  $(document).ready(function() {
     var warn_on_unload = '';
     window.onbeforeunload = function() {
        if(warn_on_unload != ''){
          return warn_on_unload;
        }
      }
  });
  function salir(){
    location.href = "../../sdv/";
  }


  </script>
</head>
<body  background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">


  <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
      <div id="dismiss">
        <i class="fas fa-arrow-left"></i>
      </div>
      <div class="sidebar-header">
        <h3> <!-- <span class="fa fa-bars fa-2x" ></span> --><span class="letra"> Men&uacute;</span>

         <div style="display:none;float: right;margin-right: 50px;" id="img-carga-do">
            <div class="spinner-border text-light" id="spinner-load" style="width: 2rem; height: 2rem;"  role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
        </h3>
      </div>
      <ul class="list-unstyled components">

        <!-- DIV CONTROLADOR EVENTO --->
          <!-- <div id="div_controlador" style="background-color: blue;height: 100%;position: absolute;">s</div> -->
        <!-- DIV CONTROLADOR EVENTO --->

        <p> <span class="fa fa-wrench fa-2x"></span> <span class="letra">Opciones</span></p>
        <li>
          <a href="#matto" data-toggle="collapse" aria-expanded="false">

           <span class="fa fa-user-check fa-lg"></span> <span class="letra">Validar Clientes</span> <span id="total-todos" style="font-size: 14px;margin-left:5px;background-color: #02110F;" class="badge badge-pill badge-dark">0</span></a>
          <ul class="collapse list-unstyled" id="matto">
            <li>
              <a href="#" onclick="cambiar_aaprobados()" id="clientea"><span class="fa fa-check-double fa-lg"></span> <span class="letra">Aprobados</span> <span id="total-aprobados" style="font-size: 14px;margin-left:5px;background-color: #02110F;" class="badge badge-pill badge-dark">0</span></a>
            </li>
            <li>
              <a href="#" onclick="cambiar_aeditados()" id="clientee"><span class="fa fa-pencil-alt fa-lg"></span> <span class="letra">Editados</span> <span id="total-editados" style="font-size: 14px;margin-left:5px;background-color: #02110F;" class="badge badge-pill badge-dark">0</span></a>
            </li>
          </ul>
        </li>
        <li>
          <a href="#" onclick="paginar_aprobados(1)" id="clienteapro"> <span class="fa fa-cloud-download-alt fa-lg"></span> <span class="letra">Descargar Clte.</span> <span id="total-descargas" style="font-size: 14px;margin-left:5px;background-color: #02110F;" class="badge badge-pill badge-dark">0</span></a>
        </li>
        <li>
          <a href="#" id="importar_codigos"> <span class="fa fa-file-upload fa-lg"></span> <span class="letra">Actualizar Clte.</span> </a>
        </li>
        <li>
          <a href="#" onclick="pagina_bitacora(1)" id="clientebit"> <span class="fa fa-clipboard-list fa-lg"></span> <span class="letra">Bitacora de Descargas</span> </a>
        </li>
        <li>
          <a href="#reportconfig" data-toggle="collapse" aria-expanded="false"> <span class="fa fa-file-excel fa-lg"></span> <span class="letra">Reportes</span></a>

          <ul class="collapse list-unstyled" id="reportconfig">
            <li>
              <a href="#" id="reporte-config"><span class="fa fa-file-alt fa-lg"></span> <span class="letra">Reporte Completo</span></a>
            </li>
            <li>
              <!--report-config-actulizacion-->
              <a href="#" id="nada"><span class="fa fa-file-alt fa-lg"></span> <span class="letra">Reporte Actualizaci&oacute;n</span></a>
            </li>
          </ul>

        </li>
        <li>
          <a href="#menu-config" data-toggle="collapse" aria-expanded="false"> <span class="fa fa-tools fa-lg"></span> <span class="letra">Configuraci&oacute;n</span></a>

          <ul class="collapse list-unstyled" id="menu-config">
            <li>
              <a href="#" onclick="cambiar_contrasena()"><span class="fa fa-key fa-lg"></span><span class="letra"> Cambiar contrase&ntilde;a</span></a>
            </li>
          </ul>

        </li>
      </ul>
      <ul class="list-unstyled CTAs" style="margin-top:-16px;">
        <form id="form-filtro" style="width:250px;margin:0 auto;margin-left: -20px;">
        <div id="filtros">
          <div class="container" style="margin:0 auto;text-align: center;">
            <div class="row" style="margin:0 auto;text-align: center;">
              <div class="col-sm">
                <div class="form-group" id="filtro-distribuidora"><label>Distribuidoras:</label>
                </div>
              </div>
              <div class="col-sm">
                <div class="form-group" id="filtro-rutas"><label>Rutas</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        </form>
      </ul>
    </nav>
    <!-- Page Content  -->

    <!--INICIO DE CONTENIDO-->
    <div id="content">
    <div class="overlay">

      <div class="justify-content-center img-carga" style="color:white;top:50%;left:50%;position: absolute;display:none;">
        <div class="spinner-border" role="status" style="width: 5rem; height: 5rem;">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      
    </div> 
      <nav class="navbar navbar-expand-lg maraco">
        <div class="container-fluid">
          <span class="fas fa-align-left zoom" style="cursor: pointer;font-size: 26px;margin-left: -2px;color: #fff;font-weight: 700;" id="sidebarCollapse"></span> 
          <span class="fa fa-user-check" style="font-size: 20px;color: #fff;margin-left: 15px;"></span>  <span style="color: #fff;margin-left: 5px;">
            <?php
              $nombre_completo=$this->session->userdata('nombrecompleto');
              if(!empty($nombre_completo)){
                echo "<span style='text-transform: uppercase;font-weight: 700;'>".$nombre_completo."</span>";
              }
            ?>
          </span>
          <div style="display:none;margin-left:5px;" class="img-carga">
            <div class="spinner-border text-light" id="spinner-load" style="width: 2rem; height: 2rem;"  role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
          <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-align-justify"></i>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
              <li class="nav-item active">
                <a href="#" onclick="salir()"><button type="button" id="btn-salir" class="btn btn-danger" style="font-size:16px;font-weight: bold;"><span class="fa fa-times-circle" style="font-size: 16px;"></span> Cerrar sesi&oacute;n</button>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- <span style="margin-top:-35px;float:right;font-size: 20px;font-weight: bold;color: #fff;background-color: #193C47;" id="titulo-page">Cliente Nuevos Aprobados</span> -->
      <!-- <div class="line" style="background-color: #0F718E;"></div> -->
   