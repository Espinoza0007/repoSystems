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
    <link rel="shortcut icon" type="image/ico" href="<?php echo base_url('dependencias/imagenes/bocadeli_logo.png')?>">
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
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/alertify/css/alertify.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/leaflet/leaflet.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">  
  <link href="<?php echo base_url('dependencias/fontawesome-free-5.13.0/css/all.css') ?>" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url('dependencias/jquery3.3.1/jquery-3.3.1.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-mask-plugin-master/src/jquery.mask.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/bootstrap4.3/js/bootstrap.js') ?>"></script>

  <script type="text/javascript" src="<?php echo base_url('dependencias/js/js_formulario.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/alertify/js/alertify.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/sweetalert/sweetalert2.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/select2-4.0.7/js/select2.js') ?>"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2-bootstrap.min.css') ?>">
  <script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>
  
<script type="text/javascript" src="<?php echo base_url('dependencias/moment/moment.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/moment/locale/es.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/js/HP_JS.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/lightbox2-2.11.3/dist/css/lightbox.min.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('dependencias/lightbox2-2.11.3/dist/js/lightbox.js'); ?>"></script>

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

#menu_sup{
  background: rgba(76,76,76,1);
background: -moz-linear-gradient(-45deg, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, rgba(102,102,102,1) 25%, rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);
background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(76,76,76,1)), color-stop(12%, rgba(89,89,89,1)), color-stop(25%, rgba(102,102,102,1)), color-stop(39%, rgba(71,71,71,1)), color-stop(50%, rgba(44,44,44,1)), color-stop(51%, rgba(0,0,0,1)), color-stop(60%, rgba(17,17,17,1)), color-stop(76%, rgba(43,43,43,1)), color-stop(91%, rgba(28,28,28,1)), color-stop(100%, rgba(19,19,19,1)));
background: -webkit-linear-gradient(-45deg, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, rgba(102,102,102,1) 25%, rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);
background: -o-linear-gradient(-45deg, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, rgba(102,102,102,1) 25%, rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);
background: -ms-linear-gradient(-45deg, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, rgba(102,102,102,1) 25%, rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);
background: linear-gradient(135deg, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, rgba(102,102,102,1) 25%, rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, rgba(43,43,43,1) 76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#131313', GradientType=1 );
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

    // var warn_on_unload='';

    // $('input:text,input:checkbox,input:file,select').one('change', function(){
    //   alertify.success('me presionaste');
    //   warn_on_unload = 'no salir';
    //   window.onbeforeunload = function() {
    //     if(warn_on_unload != ''){
    //       return warn_on_unload;
    //     }
    //   }

    // });


  });
  // window.onbeforeunload = eventTermination;
  //  function eventTermination(args) {
  //  args.returnValue = "alabama";
  // }

  function salir(){
    location.href = "../../sdv/";
  }


  </script>



</head>
<body  background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">

<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark" id="menu_sup">
    <!-- <a class="navbar-brand" href="#">Bocadeli System</a> -->
      <form class="form-inline">
        <h5 style="color:#fff;margin-right: 10px;font-size: 15px;">
          <span class="fa fa-user-check" style="font-size: 16px;"></span>
        <?php
        $nombre_completo=$this->session->userdata('nombrecompleto');
        if(!empty($nombre_completo)){
          echo $nombre_completo;
        }
        ?>
        </h5>
        <!-- <input class="form-control mr-sm-2" type="search" placeholder=" ........... " aria-label="Search">
        <button class="btn btn-outline-info my-2 my-sm-0" type="submit">B&uacute;squeda</button> -->
        <div id="img-carga" style="display: none;position: absolute;margin-left: 45%;">
            <div class="spinner-border text-light" id="spinner-load" style="width: 2rem; height: 2rem;"  role="status">
              <span class="sr-only">Loading...</span>
            </div>
        </div>

      <!-- <h6>&nbsp;Cargando...</h6> -->
      </form>
    <span id="usuariosesion" class="nav-link" style="font-size:15px;font-weight: bold;"></span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">

        </li>
<!--         <li class="nav-item active">
          <a class="nav-link" href="clientes">Inicio <span class="sr-only">(current)</span></a>
        </li> -->

        <?php
           if (strcmp ($this->session->userdata('role_pks'), 'admin01' ) == 0 ) {
        ?>
        <li class="nav-item">
          <!-- <a class="nav-link" href="#">Bitacora de procesados</a> -->
        </li>
      <li class="nav-item active" style="">
        <h5 style="color:#fff;margin-right: 10px;font-size: 15px;cursor: pointer;" onclick="pagina_bitacora(1)"><span class="fa fa-list-alt" style="font-size: 16px;"></span> Bit&aacute;cora de procesados</h5>
      </li>

        <li class="nav-item">
          <!-- <a class="nav-link" href="#">Bitacora de procesados</a> -->
        </li>
        <?php
          }
        ?>
        <li class="nav-item active">      
          
        </li>
        <li class="nav-item">

        </li>
      </ul>


      <a href="#" onclick="salir()"><button type="button" id="btn-salir" class="btn btn-danger" style="font-size:16px;font-weight: bold;"><span class="fa fa-times-circle" style="font-size: 16px;"></span> Cerrar sesi&oacute;n</button>
      </a>

      <form class="form-inline">
        <h5 style="color:#fff;margin-right: 10px;"><?php
        /*$nombre_completo=$this->session->userdata('nombrecompleto');
        if(!empty($nombre_completo)){
          echo $nombre_completo;
        }*/
        ?>
        </h5>
        <!-- <input class="form-control mr-sm-2" type="search" placeholder=" ........... " aria-label="Search">
        <button class="btn btn-outline-info my-2 my-sm-0" type="submit">B&uacute;squeda</button> -->

      </form>
    </div>
 </nav>

<div class="container-fluid" style="height: 100%;">

