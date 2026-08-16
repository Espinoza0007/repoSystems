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
  <link rel="stylesheet" href="<?php echo base_url('dependencias/css/menu.css'); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/alertify/css/alertify.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/alertify/css/themes/default.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/cbexhibidor.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/leaflet/leaflet.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/reportes.css');?>">
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
  <script type="text/javascript" src="<?php echo base_url('dependencias/fontawesome-free-5.11.2/js/all.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/reportes.js') ?>"></script>
  <style type="text/css">
		body{
			margin: 0;
			padding: 0;
			background-color:#f1f1f1;
		}
  </style>
</head>
<body  background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
