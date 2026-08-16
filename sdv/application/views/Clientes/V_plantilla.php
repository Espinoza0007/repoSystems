<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

  <script type="text/javascript" src="<?php echo base_url('dependencias/js/clientes.js') ?>"></script>

  <div class="row justify-content-md identify-content">
    <div style="width: 100%" id="contenedor-formulario">
    <div id="cargando_gif">
    <div style="width:100%;margin-top:65px;position: static;" id="mjs_result"></div>
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
      <button type="button" class="btn btn-success" id="agregar-clientes" style="margin-top: 15px;">Agregar clientes</button>
      <?php
        $atributos = array(
          'class' => 'form-estilo',
          'id' =>'form-lista',
          'enctype' => 'multipart/form-data',
          'method' => 'POST'
        );
        echo form_open(base_url('lista-clientes/listado'), $atributos);
      ?>
        <div id="lista-clientes"></div>
        <div id="formularios" style="margin-top: -70px;"></div>
      <?php
        echo form_close();
      ?>
    </div>
  </div>
