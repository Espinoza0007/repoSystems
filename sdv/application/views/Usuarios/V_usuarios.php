<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/***************************************
	****************INPUTS******************
	***************************************/
	$input_nombre = array(
	    'type' => 'text',
	    'id' => 'b_nombrecompleto',
	    'name' => 'b_nombrecompleto',
	    'class' => 'form-control',
	    'placeholder' => 'Nombres'
	);
	$input_apellidos = array(
	    'type' => 'text',
	    'id' => 'b_apellidos',
	    'name' => 'b_apellidos',
	    'class' => 'form-control',
	    'placeholder' => 'Apellidos'
	);
?>
<style type="text/css">
	.alinear{
		float: left;
		border: 1px solid #000;
		border-radius: 6px;
		padding:8px;
		margin-left: 5px;
		margin-top: 5px;
		width: 255px;
		height: 335px;
	}
	.sombra{
        box-shadow: 0 0 7px #3EC9FE;
      	border: 1px solid #54D0FF;
	}
	.pagination{
		float:left;
		padding:0px 0px 0px 0px;
		margin:10px 0px 10px 10px;
		width:95%;
		text-align:left;
		border-radius:3px;
	}
	.page-numbers{
		font-size:13pt;
		border:1px solid dimgray;
		border-radius:3px;
	}
	.current{
		font-size:13pt;
		border:1px solid dimgray;
		border-radius:3px;
	}
	.buscador{
		float: left;
		width: 210px;
	}
	#fotousu{
		width: 100px;
		height: 100px;
		margin:0 auto;
	}
	.cancelfile{
		float: left;
	}
	#contenedor-formulario{
		margin: 0 auto;
	}
	#tabla-usuarios tr{
		background-color: #fff;
	}
	#contenedor-principal{
		margin-top: 30px;
	}
	.modal-header,.modal-footer{
		background-color: #4D8D86;
		color: #fff;
		border-radius: 1px;
	}
	#tabla-usuarios thead tr th{
		background-color: #575D5D;
	}
</style>
<script type="text/javascript" src="<?php echo base_url('dependencias/js/js_usuarios.js') ?>"></script>
<!-- <script src="<?php //echo base_url('dependencias/datepicker/js/core.js') ?>" type="text/javascript"></script> -->
<!-- <link href="<?php //echo base_url('dependencias/datepicker/css/core.css') ?>" rel="stylesheet" type="text/css"> -->
<!-- <link href="<?php //echo base_url('dependencias/datepicker/css/datepicker.css') ?>" rel="stylesheet" type="text/css"> -->
<!-- <script src="<?php //echo base_url('dependencias/datepicker/js/datepicker.js') ?>"></script> -->

	<div class="row justify-content-md identify-content" id="contenedor-principal">
		<div style="width: 90%" id="contenedor-formulario">
		<div id="cargando_gif"></div>
		<div style="width:100%;margin-top:2px;" id="mjs_result"></div>
		<div id="mjs_extra"></div>
		<?php
		    $datta = $this->session->flashdata('datta');
		   if($datta){
		?>
		<div style="width:100%;margin-top:2px;">
		<div class="alert alert-<?php echo $datta['cla'];?>" role='alert'>
		    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong><h3><?php echo $datta['ttmjs'];?></h3></strong>
		    <?php echo $datta['info']; ?>
		</div>
		</div>
		<?php
		    }else{}
		?>
		<button type="button" class="btn btn-success" id="agregar-usuario">Agregar Usuario</button>
		<button type="button" class="btn btn-primary" id="buscar-usuario" data-toggle="modal" data-target="#Modabusqueda">Filtros de busqueda</button>
		<?php
			$atributos = array(
				 'class' => 'form-estilo',
				 'id' =>'form-lista',
				 'enctype' => 'multipart/form-data',
				 'method' => 'POST'
			);
			echo form_open(base_url('lista-usuarios/listado'), $atributos);
		?>
		<div id='buscador-principal' style="width: 100%;">

			<div class="modal fade" id="Modabusqueda" tabindex="-1" role="dialog" aria-labelledby="eliminar" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="eliminar">Filtros de busqueda</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
					<div class="form-group">
						<label>Nombre completo:</label>
						<?php
							echo form_input($input_nombre);
						?>
					</div>
					<div class="form-group">
						<label>Apellidos:</label>
						<?php
							echo form_input($input_apellidos);
						?>
					</div>			      	
			      	<div id="buscador-listado"></div>
			      </div>
			      <div class="modal-footer" id="pieModalEliminar">
			      </div>
			    </div>
			  </div>
			</div>


		</div>
		<div id="lista-us"></div>
		<div id="formularios"></div>
		<?php
			echo form_close();
		?>
		</div>
	</div>
</div>


<!-- Modal ELIMINAR-->
<div class="modal fade" id="Modaleliminar" tabindex="-1" role="dialog" aria-labelledby="eliminar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminar">¿Eliminar usuario?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="conteModalEliminar">
      </div>
      <div class="modal-footer" id="pieModalEliminar">
      </div>
    </div>
  </div>
</div>
<!-- <script type="text/javascript" src="<?php //echo base_url('dependencias/bootstrap_4.1.3/js/bootstrap-filestyle.min.js') ?>"></script> -->