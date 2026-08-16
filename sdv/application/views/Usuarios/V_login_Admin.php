<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/***************************************
****************INPUTS******************
***************************************/
$input_usuario = array(
    'type'  => 'text',
    'name'  => 'usuario',
    'id' => 'usuario',
    'class' => 'form-control',
    'placeholder' => 'Nombre de usuario',
    'value' => ''

);
$input_contrasena = array(
    'type'  => 'password',
    'name'  => 'contrasena',
    'id'  => 'contrasena',
    'class' => 'form-control',
    'placeholder' => 'Contrase&ntilde;a',
    'value' => ''
);
?>
<style type="text/css">
	.card{
		/*border:1px solid #677495;*/
		border-radius: 7px;
		-webkit-box-shadow: 1px 2px 16px -5px rgba(0,0,0,0.75);
		-moz-box-shadow: 1px 2px 16px -5px rgba(0,0,0,0.75);
		box-shadow: 1px 2px 16px -5px rgba(0,0,0,0.75);
		border: 4px inset #75C6DE;
	}
	.card-header{
		background-color: #0493BE;
		color: #fff;
		font-weight: bold;
		text-align: center;

	}
	body{
		background-color: #eee;
		font-weight: bold;
	}
	#logo-sdv{
		width: 65%;
		margin: 0 auto;
		text-align: center;
		
	}
	#spinner-load{
		display: none;
	}
</style>

<div class="row justify-content-md identify-content">
	<div id="cargando_gif"></div>
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
	<div class="container-fluid" style="margin-top:20px;">

		<div class="row justify-content-md-center">
			            <div id="img-carga">
<input type="hidden" value="0" id="c-login" name="">
<input type="hidden" value="" id="ustempo" name="">
<input type="hidden" value="" id="clatempo" name="">
<input type="hidden" value="" id="loginurl" name="">
            </div>		
			<div class="col-md-4">
				<div class="card card-body">
					<h4 class="card-header">Inicio de sesi&oacute;n</h4><br>
					<div style="position: relative;text-align: center;max-height:140px;">
						<img style="" id="logo-sdv" src="<?php echo base_url('dependencias/imagenes/sdvlogo.png')?>">
						<div class="spinner-grow text-info" id="spinner-load" style="position: relative;margin:0 auto;width: 15rem; height: 15rem;"  role="status">
						    <span class="sr-only">Loading...</span>
						</div>
					</div>
					<row>
				  		<?php
				  			$atributos = array('class' => 'form-estilo', 'id' => 'login_form','autocomplete' => 'off');
							echo form_open(base_url('index.php/login-admin/sesionadmin'), $atributos);
				  		?>
						<div class="form-group">
							<label for="exampleInputEmail1">Usuario:</label>
							<?php echo form_input($input_usuario);?>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Contrase&ntilde;a:</label>
							<?php echo form_input($input_contrasena);?>
						</div>
			            <div class="form-group" style="margin-top: 10px;">
			                <div class="input-group" id="recaptchapt">
			                    
			                </div>
			            </div>
				  		<div class="final">
				  			<div class="form-group">
				  				<center><button type="button" class="btn btn-success" onclick="logueo()">Iniciar Sesi&oacute;n</button></center>
				  			</div>

				  		</div>
					</row>
				</div>
			</div>
			<?php form_close();?>
		</div>
	</div>
</div>
</div>
