<!-- ESTILOS CSS-->
    <link href="<?php echo base_url('dependencias/css/CSS_vehiculo.css') ?>" rel="stylesheet">
<!--ESTILOS CSS-->

<!--JAVASCRIPTS JS-->
    <!-- <script type="text/javascript" src="<?php //echo base_url('dependencias/js/HP.js') ?>"></script> -->
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_vehiculo.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

<!--JAVASCRIPTS JS-->   

<style> 
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #B2BABB;
      /*background-color: #ccc;*/
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 22px;
      width: 22px;
      left: 2px;
      bottom: 1px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(25px);
      -ms-transform: translateX(25px);
      transform: translateX(25px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 25px;
    }

    .slider.round:before {
      border-radius: 50%;
    }   
        
</style>

</head>
<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
    <?php
        defined('BASEPATH') OR exit('No direct script access allowed');
    ?>

<!-- DIV CARGA -->
    <div id="content-carga" style="display:none;z-index: 30000;" class="carga-class">
        <div class="d-flex justify-content-center">
          <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
            <span class="sr-only">Cargando...</span>
           </div>
        </div>
    </div>
<!-- FIN DIV CARGA -->

<!-- NOTIFICACION ACTUALIZACION -->
   <div id="notificacion_ac" style="position: absolute;width: 100%;height: auto;display: none;">
        <div id="snackbar">Hay disponible una versión nueva de la aplicación. 
          <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a>
        </div>
    </div>
<!-- FIN NOTIFICACION ACTUALIZACION -->

<!-- BARRA MENU -->
    <nav class="navbar fixed-top navbar-expand-lg" id="menu_bar">
        <div style="float:left;" class="icons_posicion">
            <span class="fa fa-user fa-2x"></span>
            <span id="uslogin"></span>
        </div>
        <div style="margin:0 auto;" id="btn-pendinetes" onclick="" class="icons_posicion">
        <!-- <div style="margin:0 auto;" id="btn-pendinetes" onclick="sincronizar_pedidos_vnt()" class="icons_posicion"> -->
            <!-- <span class="fa fa-cloud-upload-alt fa-2x" style="color:#fff;"></span> -->
            <!-- Ver el historial de los vehiculos -->
            <div style="" class="icons_posicion" id="btn-historial">
            <span id="historial"  style="font-size: 14px;" class="badge badge-pill badge-dark" id="RegisCola">Ver Historial</span> 
        </div>
            <!-- Fin de mostrar los vehiculos -->
        </div>
        <div style="" class="icons_posicion" id="btn-menu-back">
            <span class="fa fa-bars fa-2x" style=""></span>
        </div>
    </nav>
<!-- FIN BARRA MENU -->

<!-- CONTENEDOR -->
    <div class="row" id="content-map" style="margin-top:15px;display:none;">
      <div class="col-md-12 divrow">
        <div id="map" style="height: 277px;width: 100%;"></div>
      </div>
    </div>
    <div class="container" id="contenedor-principal" style="margin-top:50px;">
        <input type="hidden" value="" id="lat" name="lat">
        <input type="hidden" value="" id="lon" name="lon">
        <div style="width: 100%;" id="contenedor-formulario">
            <!-- <?php
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
            ?> -->
            
        </div>
    </div>
<!-- FIN CONTENEDOR -->
    <div class="container-fluid" style="height: 100%;width:100%;">        
        <div class="row">
            <h5 class="card-header card-header-form" style="">
                Recepción de vehiculo
            </h5>
        </div>
        <!-- DATOS DEL RECEPTOR -->
        <h5 class="centered">Datos del Receptor</h5>
        <div class="row form-group">
               <div class="col-3">
                     Nombre:
             </div>
                <div class="col-9" id="lbl-nombre-recive">
                  pruebas
                 </div>                                
         </div>

          <div class="row form-group">
           <div class="col-3">
              Codigo:
            </div>
            <div class="col-9" id="lbl-codigo-recive">
             00000
              </div>                                
            </div>
        <!--FIN  DATOS DEL RECEPTOR -->
        <div class="row">          
            <div id="accordion" class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading" id="headingOne">                        
                        <div class="panel-title">
                            <a class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Datos de responsable</a>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" class="collapse show panel-collapse in" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="panel-body">
                        <div class="row form-group" style="justify-content: flex-end; padding: 0% 2%;">
                            <button class="btn btn-vehiculo-01" id="btn-actualizar-vendedor" style="font-size: 13px;"><i class="fas fa-edit"></i><b> ACTUALIZAR DATOS</b></button>
                        </div>
                        <!--Buscar Buscador de rutas  -->
                        <!-- <div class="input-group mb-3">
                                <input type="text" class="form-control" id="txtbusca" placeholder="Numero de Ruta" aria-label="Buscar" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="input-group-text" id="btn-selec-responsable">BUSCAR</button>
                                </div>
                        </div>   -->
                        <div class="frmSearch">
								<div class="input-group">
											<div class="input-group-prepend">
												<span id="carga" class="input-group-text"><i class="fas fa-search"></i></span>
												</div>
												<input type="text" id="search-box5" class="form-control" autocomplete="off" placeholder="Numero de ruta "   />
                                            </div>
								<div id="suggesstion-box5"></div>
                        </div>
                        <!--Fin de Buscar Buscador de rutas  -->
                        <form id="frm-datos-vendedor">                                
                            <input type="hidden" name="txt-id_empleado" id="txt-id_empleado">
                            <div class="row form-group">
                                <div class="col-3">
                                    Nombre:
                                </div>
                                <div class="col-9" >
                                   <label id="lbl-nombre-vendedor"> Vendedor ruta de pruebas</label>
                                </div>                                
                            </div>
                            <div class="row form-group">
                                <div class="col-3">
                                    Codigo:
                                </div>
                                <div class="col-3" id="lbl-carnet">
                                    <label id="lbl-carnet-vendedor">0000</label>
                                </div>                                
                                <div class="col-3">
                                    Ruta:
                                </div>
                                <div class="col-3" id="lbl-nombre">
                                <label  id="lbl-nombre-ruta">0.0.00</label>
                                </div>                                
                            </div>
                            
                            <div class="row form-group">
                                <div class="col-4">
                                    N. Licencia:
                                </div>
                                <div class="col-8" id="lbl-numero-licencia">
                                    <input type="text" name="txt-numero-licencia" id="txt-numero-licencia" class="form-control vnd" disabled>
                                </div>                                
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    Vence licencia:
                                </div>
                                <div class="col-6" id="lbl-vencimiento-licencia">
                                    <input type="date" name="txt-vencimiento-licencia" id="txt-vencimiento-licencia" class="form-control vnd" disabled>
                                </div>                                
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    Clase de licencia:
                                </div>
                                
                                <div class="col-6" id="slc-div-licencia">
                                    <select class="form-control" name="slc-licencias" id="slc-licencias" disabled>
                                        <option>-- seleccione --</option>
                                    </select>
                                </div>                            
                            </div>
                            <div class="row form-group" style="justify-content: flex-end; padding: 0% 2%;">
                                <button type="button" class="btn btn-success" style="flex: center;font-size: 13px;  display: none" id="btn-guardar-datos"><i class="far fa-save fa-lg"></i> <b>GUARDAR CAMBIOS</b></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" id="headingTwo">                        
                        <div class="panel-title">
                            <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Datos del vehiculo</a>
                        </div>
                    </div>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="panel-body">

                        <div class="row form-group" style="justify-content: flex-end; padding: 0% 2%;">
                            <button class="btn btn-success" id="btn-crear-vehiculo" style="font-size: 13px;margin-right: 5px;"><i class="fas fa-plus"></i><b> NUEVO</b></button>
                            <button class="btn btn-vehiculo-01" id="btn-cambiar-vehiculo" style="font-size: 13px;"><i class="fas fa-edit"></i><b> CAMBIAR VEHICULO</b></button>
                        </div>
                         
                        <!-- <div class="row form-group" style="padding: 2%">                                
                            <h6> -- Sin vehiculos, favor registrar uno nuevo -- </h6>
                        </div> -->

                        <div class="row form-group" style="display:none;" id="slc-historial-vehiculo">
                            <div class="col-4">
                                Historial vehiculos:
                            </div>
                            <div class="col-8" id="lbl-equipo-vehiculo">
                                <select class="form-control" name="slc-vehiculo-id" id="slc-vehiculo-id">
                                    <option>-- seleccione --</option>
                                </select>
                            </div>                                
                        </div>
                        <form id="frm-datos-vehiculo">
                            <input type="hidden" name="txt-id-ruta" id="txt-id-ruta">
                            <input type="hidden" name="txt-id-vehiculo" id="txt-id-vehiculo">
                            <div class="row form-group">
                                <div class="col-4">
                                    Equipo:
                                </div>
                                <div class="col-8" id="lbl-equipo-vehiculo">
                                    <input type="text" name="txt-equipo-vehiculo" id="txt-equipo-vehiculo" class="form-control vehi" disabled>
                                </div>                                
                            </div>
                            <div class="row form-group">
                                <div class="col-4">
                                    Placas:
                                </div>
                                <div class="col-8" id="lbl-placas-vehiculo">
                                    <input type="text" name="txt-placas-vehiculo" id="txt-placas-vehiculo" class="form-control vehi" disabled>
                                </div>                             
                            </div>
                            <div class="row form-group">
                                <div class="col-4">
                                    Marca:
                                </div>
                                <div class="col-8" id="lbl-marca-vehiculo">
                                    <input type="text" name="txt-marca-vehiculo" id="txt-marca-vehiculo" class="form-control vehi" disabled>
                                </div>                             
                            </div>
                            <div class="row form-group">
                                <div class="col-4">
                                    Tipo:
                                </div>
                                <div class="col-8" id="lbl-tipo-vehiculo">
                                    <input type="text" name="txt-tipo-vehiculo" id="txt-tipo-vehiculo" class="form-control vehi" disabled>
                                </div>                             
                            </div>

                            <div class="row form-group">
                                <div class="col-4">
                                    Año:
                                </div>
                                <div class="col-8" id="lbl-anio-vehiculo">
                                    <input type="text" name="txt-anio-vehiculo" id="txt-anio-vehiculo" class="form-control vehi" disabled>
                                </div>                             
                            </div>

                            <div class="row form-group">
                                <div class="col-4">
                                    N. Motor:
                                </div>
                                <div class="col-8" id="lbl-motor-vehiculo">
                                    <input type="text" name="txt-motor-vehiculo" id="txt-motor-vehiculo" class="form-control vehi" disabled>
                                </div>                             
                            </div>

                            <div class="row form-group">
                                <div class="col-4">
                                    N. Chasis:
                                </div>
                                <div class="col-8" id="lbl-chasis-vehiculo">
                                    <input type="text" name="txt-chasis-vehiculo" id="txt-chasis-vehiculo" class="form-control vehi" disabled>
                                </div>                             
                            </div>
                            <div class="row form-group">
                                <div class="col-4">
                                    Combustible:
                                </div>
                                <div class="col-8" id="lbl-combustible-vehiculo">
                                    <input type="text" name="txt-combustible-vehiculo" id="txt-combustible-vehiculo" class="form-control vehi" disabled>
                                </div>                             
                            </div>

                            <div class="row form-group">
                                <div class="col-4">
                                    KM. Actual:
                                </div>
                                <div class="col-8" id="lbl-km-vehiculo">
                                    <input type="text" name="txt-km-vehiculo" id="txt-km-vehiculo" class="form-control">
                                </div>                             
                            </div>
                            <div class="row form-group" style="justify-content: flex-end; padding: 0% 2%;">
                                <button type="button" class="btn btn-success" style="flex: center;font-size: 13px;  display: none" id="btn-guardar-vehiculo"><i class="far fa-save fa-lg"></i> <b>GUARDAR VEHICULO</b></button>
                            </div>
                        </form>
                    </div>
                </div>
                <form id="frm-checklist">
                </form>       
            </div>
        </div>

        <div style="background-color: #F7F9F9;width:100%;">
                
            <div class="row form-group" style="justify-content: flex-center; padding: 5% 2%; bottom: 0px;">
                <div class="col-12" id="lbl-observaciones">
                <h6>
                    ESPACIO PARA OBSERVACIONES:
                </h6>
                    <textarea name="txt-observaciones-vehiculo" id="txt-observaciones-vehiculo" class="form-control"></textarea>
                </div>                             
            </div>
            <div class="row form-group" style="justify-content: flex-end; padding: 0% 2%; bottom: 0px;">
                <button class="btn btn-success" id="btn-enviar-info" ><i class="fas fa-check"></i><b> ENVIAR RECEPCION</b></button>
            </div>
            
        </div>

    </div>

<script>

</script>