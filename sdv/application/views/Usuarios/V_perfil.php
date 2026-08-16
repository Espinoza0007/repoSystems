<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<style type="text/css">
	.fotocontenedor{
		position: relative;
  		width: 430px;
  		height: 320px;
  		overflow: auto;
  		/*background-color: red;*/
	}
	.fotocontenedor .contenidofoto {
		width: auto;
  		/*background-color: green;*/
	}
	#contenidomenu{
		background-color: ;
		width: 100%;
		text-align: center;
		margin-top: 15px;
		padding: 5px;
		max-height: 100%;
	}
	.card{
		padding: 5px;
		margin-right: 5px;
	}
</style>
<script type="text/javascript">
	document.oncontextmenu = function() {return false;};
</script>
<div class="row justify-content-md h-100 identify-content"">
	<div class="col-md-12 row justify-content-md-center">
	<!-- <a>Cerrar Sesi&oacute;n</a><br> -->
			
      	<div style="text-align: center;margin-top: 40px;">
      		<img id="logo-sdv" src="<?php echo base_url('dependencias/imagenes/logo.png')?>">
      	</div>
	</div>
</div>
<script type="text/javascript">
const ps = new PerfectScrollbar('.fotocontenedor', {
  wheelSpeed: 2,
  wheelPropagation: true,
  minScrollbarLength: 20
});
</script>