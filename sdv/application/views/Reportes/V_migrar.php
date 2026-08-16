<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript" src="<?php echo base_url('dependencias/js/importar.js') ?>"></script>

	<center><h1>CARGAR CLIENTES</h1></center>
	<center><button class="btn btn-success" onclick="totalimportar()">Cargar Clientes</button></center>

	<br>
	<center>
    <div class="spinner-border text-info" id="spinner-load" style="width: 3rem; height: 3rem;display: none;"  role="status">
        <span class="sr-only">Loading...</span>
    </div><br>
    </center>


<div style="width: 80%;margin:0 auto;">

<div class="progress">
  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div>
</div>


    <br>
	 <span>Total Clientes Cargados: <strong><span id="tocargados">0</span></strong> </span> / <span>Total Clientes a Cargar: <strong><span id="toacargar">0</span></strong></span>
	 <br>

	 <div id="mjsinfo"></div>
</div>

</div>