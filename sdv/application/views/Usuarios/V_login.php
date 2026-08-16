<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$input_usuario = array(
		'type'  	  => 'text',
		'name'  	  => 'usuario',
		'id' 		  => 'usuario',
		'class' 	  => 'form-control',
		'placeholder' => 'Nombre de usuario',
		'value' 	  => ''
	);
	$input_contrasena = array(
		'type'  	  => 'text',
		'name'  	  => 'contrasena',
		'id'  		  => 'contrasena',
		'class' 	  => 'form-control',
		'placeholder' => 'Contrase&ntilde;a',
		'style' 	  => '-webkit-text-security: square;',
		'value' 	  => ''
	);
	$options = [
		''   => 'Seleccionar opción',
        '2'  => 'VENDEDOR',
		'15' => 'DESARROLLADOR',
        '3'  => 'ADMINISTRADOR',
        '6'  => 'ADMIN VENTAS',
        '4'  => 'BODEGA',
        '5'  => 'CALIDAD',
		'150'  => 'CENSADOR'
	];
?>
	<!--ESTILOS CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_login.css')?>">
	<!--JAVASCRIPTS JS-->
	<script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_login.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_login.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_Validaciones.js') ?>"></script>
<style>
	#snackbar {
		top:0;
		visibility: hidden;
		min-width: 250px;
		background-color: #333;
		color: #fff;
		text-align: center;
		padding: 16px;
		position: fixed;
		z-index: 1;
		text-transform: uppercase;
		font-size: 12px;
		font-weight: bold;
    }
    #snackbar.show {
      	visibility: visible;
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
</style>
</head>
<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
<div id="snackbar">Hay disponible una nueva versión. <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a></div>
	<div id="content-carga" style="display:none;" class="carga-class">
		<div class="d-flex justify-content-center">
			<div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
				<span class="sr-only">Cargando...</span>
			 </div>
		</div>
	</div>
	<div style="width:100%;margin-top:2px;display:none;" id="mjs_result"></div>
	<?php
	    $cuentaok = $this->session->flashdata('cuentaok');
	    if($cuentaok){
	?>
	<div style="width:100%;margin-top:2px;">
		<div class="alert alert-<?php echo $cuentaok['cla'];?>" role='alert'>
		    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong><h3><?php echo $cuentaok['ttmjs'];?></h3></strong>
		    <?php echo $cuentaok['info']; ?>
		</div>
	</div>
	<?php
	    }else{}
	?>
	<div class="container-fluid" style="min-height: 100vh; margin-top:-11px;">
		<div class="row justify-content-center">
			<div id="img-carga"></div>
			<div class="col-md-4 w-100 p-3">
				<div class="card card-body">
					<h4 class="card-header">Inicio de Sesión</h4><br>
					<div style="position: relative;text-align: center;max-height:140px;">
						<img style="" id="logo_login" src="<?php echo base_url('dependencias/imagenes/sdvlogo_transparente.png')?>">
					</div>
					<row style="margin-top: 10px;">
				  		<?php
				  			$atributos = array('class' => 'form-estilo', 'id' => 'login_form','autocomplete' => 'off');
							echo form_open(base_url('index.php/login/iniciosesion'), $atributos);
				  		?>
						<div class="d-flex justify-content-center">
							<div class="spinner-border text-success" role="status" id="carga-span" style="display: none;">
							<span class="sr-only">Loading...</span>
							</div>
						</div>
						<hr>
				  		<label>Usuario:</label>
						<div class="inputConIcono inputIconBg" style="margin-top: -12px;">
							<?php echo form_input($input_usuario);?>
			                <div class="valid-feedback">
			                    <strong></strong>
			                </div>
			                <div class="invalid-feedback" id="error-mjs-1">
			                </div>
						</div>
						<hr>
						<label>Contrase&ntilde;a:</label>
						<div class="inputConIcono inputIconBg" style="margin-top: -12px;">
							<?php echo form_input($input_contrasena);?>
			                <div class="valid-feedback">
			                    <strong></strong>
			                </div>
			                <div class="invalid-feedback" id="error-mjs-2">
			                </div>
						</div>
				  		<div class="final" style="margin-top: 15px;">
							<button type="button" aria-pressed="false" class="btngr_azul carga-esconder" id="btn_login" onclick="Iniciar_Sesion()">
								<span class="fas fa-sign-in-alt fa-lg" style="padding: 10px;"></span>
								   	Iniciar Sesi&oacute;n
							</button>
							<button type="button" aria-pressed="false" class="btngr_azul_presi carga-class" style="display: none;">
								<span class="fas fa-sign-in-alt fa-spin fa-lg" style="padding: 10px;"></span>
								   	Iniciar Sesi&oacute;n
							</button>
				  		</div>
					</row>
					<span style="font-size: 13px;margin-top: 15px;float: right;font-style: italic;">Version 2.6.2</span>
				</div>
			</div>
			<?php form_close();?>
		</div>
	</div>