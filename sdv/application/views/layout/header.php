<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SDV Bocadeli</title>
  <meta name="theme-color" content="#1B7CC3">
  <meta name="MobileOptimized" content="width">
  <meta name="HandheldFriendly" content="true">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="shortcut icon" type="image/png" href="<?php echo base_url('dependencias/imagenes/bocadeli_logo.png')?>">
  <link rel="apple-touch-icon" href="<?php echo base_url('dependencias/imagenes/bocadeli_logo.png')?>">
  <link rel="apple-touch-startup-image" href="<?php echo base_url('dependencias/imagenes/bocadeli_logo.png');?>">
  <link rel="manifest" href="<?php echo base_url('manifest.json');?>"/>
  <!--ESTILOS CSS-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/bootstrap4.3/css/bootstrap.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/bootstrap4.3/css/bootstrap-grid.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_cargando.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/leaflet/leaflet.css') ?>">
  <link href="<?php echo base_url('dependencias/fontawesome-free-5.13.0/css/all.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('dependencias/css/CSS_main.css') ?>" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/alertify/css/alertify.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/alertify/css/themes/default.css') ?>">
  <!--JAVASCRIPTS JS-->
  <script type="text/javascript" src="<?php echo base_url('dependencias/jquery3.3.1/jquery-3.3.1.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-mask-plugin-master/src/jquery.mask.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/bootstrap4.3/js/bootstrap.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/moment/moment.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/HP_JS.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/sweetalert/sweetalert2.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/alertify/js/alertify.js') ?>"></script>
  <script>
  $(document).ready(function() {
     window.onbeforeunload = function() {
        if(warn_on_unload != ''){
          return warn_on_unload;
        }
      }
  });
  function salir(){
    if(navigator.onLine) {
      cleardatatempos();
      location.href = "salir";
    }else{
      cleardatatempos();
      location.href = "/sdv/";
    }
  }
  </script>

