<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <!--JAVASCRIPTS JS-->
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_geo_marcaciones.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/tokml.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/Leafletdraw-develop/src/Leaflet.draw.js') ?>"></script>
</head>

<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
<h1>Geocerca Marcaciones Impulso</h1>


<input type="button" id="export" value="Exportar KML" onclick="Exportar_KML()">


Convertir KML <input type="file" class="form-control-file" id="import_kml">

<div id='map' style='width: 100%; height: 100%;'></div>