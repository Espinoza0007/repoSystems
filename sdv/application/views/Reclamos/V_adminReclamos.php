ESTILOS CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/datatables.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/gijgo-combined-1.9.11/css/gijgo.css'); ?>">
    

<!--FIN ESTILOS CSS-->


<!--JAVASCRIPTS JS-->
    <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-validation/dist/jquery.validate.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-validation/dist/additional-methods.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/gijgo-combined-1.9.11/js/gijgo.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_reclamos.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_reclamos.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/js_reclamosBodega.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/js_catalogo_bodega.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>    
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>


<!--FIN JAVASCRIPTS JS-->

<style type="text/css">
    .custom-file {
        position: relative;
        display: inline-block;
        width: 100%;
        height: 31px;
        margin-bottom: 0;
    }
    .custom-file-input{
        width: 100%;
        height: 31px;
    }
    .custom-file-label {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1;
        height: 31px;
        padding: 0.375rem 0.75rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .custom-file-label::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 3;
        display: block;
        height: 31px;
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        color: #495057;
        content: "Browse";
        background-color: #e9ecef;
        border-left: inherit;
        border-radius: 0 0.25rem 0.25rem 0;
    }

    table.dataTable.dataTable_width_auto {
        width: auto;
    }

    table.dataTable td {
      font-size: 12px;
    }
    table.dataTable th {
      font-size: 13px;
    }
	body{
		margin: 0;
		padding: 0;
		background-color:#f1f1f1;
	}
    #usuariosesion{
      color: #fff;
      font-size: 12px;
    }
    #piedepagina{
      position: fixed;
      bottom: 0;
      width:100%;
      z-index: 10;
      background-color:#2F91B0;
      font-weight: 700;
      color: #fff;

    }
    .zoom{
        /* Aumentamos la anchura y altura durante 2 segundos */
        transition: width 1s, height 1s, transform 1s;
        -moz-transition: width 1s, height 1s, -moz-transform 1s;
        -webkit-transition: width 1s, height 1s, -webkit-transform 1s;
        -o-transition: width 1s, height 1s,-o-transform 1s;
    }
    .zoom:hover{
        /* tranformamos el elemento al pasar el mouse por encima al doble de
           su tamaño con scale(2). */
        transform : scale(2);
        -moz-transform : scale(2);      /* Firefox */
        -webkit-transform : scale(2);   /* Chrome - Safari */
        -o-transform : scale(2);        /* Opera */
        z-index: 1000;
    }
    .components span{
      font-weight: 700;
      text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.2);
    }
    .letra{
      font-weight: 700;
      text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.1); 
    }

    label.error {
        color: red;
        font-size: 1rem;
        display: block;
        margin-top: 5px;
    }
    .error{
        font-size:14px;
        color:red;    
    }
    .modal {
        padding: 0 !important;
        /*override inline padding-right added from js*/
    }
    .modal .modal-dialog {
        width: 100%;
        max-width: none;
        /*height: 100%;*/
        min-height: 100vh;

        margin: 0;
    }
    .modal .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0;
    }

    .modal .modal-body {
        height: 100%;
    }
</style>

<script>
    $(document).ready(function() {
        var warn_on_unload = '';
        window.onbeforeunload = function() {
            if(warn_on_unload != ''){
              return warn_on_unload;
            }
        }
    });
    /*function salir(){
        location.href = "../../sdv/";
    }*/

    // -- 27_07_2021 ---------------------------------------------
        function salir(){
            Promise.all([
                DB_limpiar_tablas_()
            ])
            .then(respuestas => {
                $.ajax({
                    url: 'Ctr_cerrarsesion/cerrar_session_admin_pfn',
                    type:"POST",          
                    dataType: "JSON",
                    timeout  : 27777
                }).done(function(resp_data) {
                    if(resp_data.rs){
                        location.href = "../../sdv/";
                    }else{
                      
                    }
                }).fail(function() {
                    console.log('Error al cargar los datos');
                });
            })
            .catch(error => {
                console.log(error);
            });
          
    // -----------------------------------------------------------
  }
</script>

</head>

<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
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
    <div id="content-carga" style="display:none;" class="carga-class">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
    </div>

<div class="container-fluid" id="contenedor" style="margin-top:50px;">    

<!-- PANEL INFORMACION DE RECLAMOS -->
    <div class="collapse show" id="panel_info_reclamos">
        <div class="card card-body">
            <h5 class="card-title">Lista reclamos</h5>
            <form id="frm_info_reclamo">
                <div class="form-row">
                    <div class="form-group col-md-3 divrow">
                        <div class="titulo"><span class=""></span> País</div>
                        <div id="fil_pais">
                        </div>
                        <div class="valid-feedback">
                            <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-17">
                            <strong>Por favor selecciona una opción de la lista!</strong>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="titulo"><span class=""></span> División</div>
                        <div id="fil_division">
                            <select class="form-control custom-select select2_" data-width="100%">
                                <option>Seleccione una opción</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group col-md-3 divrow">
                        <div class="titulo"><span class=""></span> Distribuidora</div>
                        <div id="fil_distribuidora">
                            <select class="form-control custom-select select2_" data-width="100%">
                                <option>Seleccione una opción</option>
                            </select>
                        </div>                                
                    </div>
                </div>
                <?php if(!$this->session->userdata('usu_pais_tercero')){ ?>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <div class="titulo"><span class=""></span> Canal</div>
                            <div id="fil_canal">
                                <select class="form-control custom-select select2_" data-width="100%">
                                    <option>Seleccione una opción</option>
                                </select>
                            </div>
                            <div class="valid-feedback">
                                <strong></strong>
                            </div>
                            <div class="invalid-feedback" id="error-mjs-17">
                                <strong>Por favor selecciona una opción de la lista!</strong>
                            </div>
                        </div> 
                        <div class="form-group col-md-3">
                            <div class="titulo"><span class=""></span> Grupo</div>
                            <div id="fil_grupo">
                                <select class="form-control custom-select select2_" data-width="100%">
                                    <option>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="titulo"><span class=""></span> Ruta</div>
                            <div id="fil_ruta">
                                <select class="form-control custom-select select2_" data-width="100%">
                                    <option>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php }  ?>
                
                <div class="form-row d-flex">
                    <div class="p-1 form-group col-md-3">
                        <div class="titulo"> Fecha inicial</div>
                        <input type="text" class="form-control" name="txtFechaInicial" id="txtFechaInicial" value="">
                    </div>
                    <div class="p-1 form-group col-md-3">
                        <div class="titulo">Fecha limite</div>
                        <input type="text" class="form-control" name="txtFechaLim" id="txtFechaLim" value="">
                    </div>
                    <div class="p-1 form-group col-md-2">
                        <div class="titulo"><span class=""></span> Estado</div>
                        <select class="form-control custom-select select2_" id="slc_estado" data-width="100%">
                            <option value="">Seleccione una opción</option>
                            <option value="1">ACTIVO</option>
                            <option value="0">AUTORIZADO</option>
                            <option value="2">RECHAZADO</option>
                            <option value="3">REVISADO</option>
                            <option value="4">FINALIZADO</option>
                        </select>
                    </div>
                    <div class="p-1 align-self-center col-md-1">
                        <div class="form-group">
                            <button type="button" class="btn btn-success btn-block" id="btn_buscar">
                                <span class="fas fa-search"> Buscar</span> 
                            </button>
                        </div> 
                    </div>
                </div>                        
            </form>
            
            <!-- <div class="align-self-center">Aligned flex item</div> -->

            <hr>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success" id="btn_exportar" name="btn_exportar"><i class="far fa-file-excel"></i> Exportar</button>
                        <!-- <button type="button" class="btn btn-danger"><i class="far fa-file-pdf"></i> PDF</button> -->
                    </div>                
                </div>
            </div>
            <div class="table-responsive">
                <table id="lista_reclamos_table" class="table table-bordered display" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">No. Reclamo</th>
                            <th scope="col">Area</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Tipo de reclamo</th>
                            <th scope="col">Producto</th>
                            <th scope="col">País</th>
                            <th scope="col">División</th>
                            <th scope="col">Distribuidora</th>
                            <th scope="col">Fecha reclamo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Detalle</th>
                            
                        </tr>
                    </thead>
                    <tbody id="show_data_rec">                    
                    </tbody>
                    <tfoot class="thead-dark">
                        <tr>
                            <th scope="col">No. Reclamo</th>
                            <th scope="col">Area</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Tipo de reclamo</th>
                            <th scope="col">Producto</th>
                            <th scope="col">País</th>
                            <th scope="col">División</th>
                            <th scope="col">Distribuidora</th>
                            <th scope="col">Fecha reclamo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Detalle</th>
                        </tr>
                    </tfoot>
                </table>
            </div> 
            <!-- <div>
                <a class="example-image-link" href="../dependencias/imagenes/file_3_icon-icons.com_68952.png" data-lightbox="example-set" data-title="Click the right half of the image to move forward."><img class="example-image" src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" alt="" style="max-height:65px;width:auto;" /></a> 
                <a class="example-image-link" href="../dependencias/imagenes/bocadeli_logo.png" data-lightbox="example-set" data-title="Click the right half of the image to move forward."><img class="example-image" src="../dependencias/imagenes/bocadeli_logo.png" alt="" style="max-height:65px;width:auto;" /></a> 
            </div> -->
            <!-- <a href="../dependencias/imagenes/file_3_icon-icons.com_68952.png" data-lightbox="ejemplo1XD"><img class="example-image" src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" alt="" style="max-height:65px;width:auto;" /></a> -->
            <!-- <a href="../dependencias/imagenes/bocadeli_logo.png" data-lightbox="ejemplo1XD"><img class="example-image" src="../dependencias/imagenes/bocadeli_logo.png" alt="" style="max-height:65px;width:auto;" /></a> -->
        </div>  
    </div>
<!-- FIN PANEL INFORMACION DE RECLAMOS -->

<!-- PANEL INGRESO DE RECLAMOS -->
    <div class="collapse" id="panel_ingreso_reclamos">
        <div class="card card-body">
            <div class="form-group col-9" style="background-color:;" id="filtro_fam">     
                <!-- <div class="titulo"><span class=""></span> Familias</div>
                <div id="fil_familias">
                    
                </div> -->
               
            </div>
            <div class="table-responsive">
            <table id="catalogoDtable" class="table table-bordered" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">C&oacute;digo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Unidad de medida</th>
                        <th scope="col">Familia</th>
                        <th scope="col">Sub Familia</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody id="showDataSN">                    
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col">C&oacute;digo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Unidad de medida</th>
                        <th scope="col">Familia</th>
                        <th scope="col">Sub Familia</th>
                        <th scope="col">Estado</th>
                    </tr>
                    </tr>
                </tfoot>
            </table>
        </div>
        </div> 
    </div>
<!-- FIN PANEL INGRESO DE RECLAMOS -->

<!-- MODAL DETALLE RECLAMOS -->
<div class="modal fade-scale" id="modal_detalle_rec" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">DETALLE DE RECLAMO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding:4%;">
                <input type="hidden" name="txtIdRuta" id="txtIdRuta" value="">
                <form id="frm_detalle_rec">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="modal-title" id="exampleModalLongTitle">Información Reclamo</h6>            
                        </div>                  
                    </div>
                    <br>
                    <div class="form-row g-1">
                        <div class="form-group col-md-8">
                            <div class="titulo"><span class=""></span>Código Reclamo</div>
                            <input type="text" class="form-control form-control-sm" name="txtCodigoReclamo" id="txtCodigoReclamo" value="" readonly disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Fecha reclamo</div>
                            <input type="text" class="form-control form-control-sm" name="txtFechaReclamo" id="txtFechaReclamo" value="" readonly disabled>
                        </div>
                    </div>
                    <div class="form-row g-1">
                        <div class="form-group col-md-8">
                            <div class="titulo"><span class=""></span>País</div>
                            <input type="text" class="form-control form-control-sm" name="txtPais" id="txtPais" value="" readonly disabled>
                        </div>                    
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>División</div>
                            <input type="text" class="form-control form-control-sm" name="txtDivision" id="txtDivision" value="" readonly disabled>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Distribuidora</div>
                            <input type="text" class="form-control form-control-sm" name="txtDistribuidora" id="txtDistribuidora" value="" readonly disabled>

                        </div>                    
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Canal</div>
                            <input type="text" class="form-control form-control-sm" name="txtCanal" id="txtCanal" value="" readonly disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Ruta</div>
                            <input type="text" class="form-control form-control-sm" name="txtRuta" id="txtRuta" value="" readonly disabled>
                        </div>
                    </div>                   
                    <div class="form-row justify-content-center">
                        <div class="col-md-7">
                            <div class="form-group">
                                <div class="titulo"><span class=""></span>Cliente</div>
                                <input type="text" class="form-control form-control-sm" name="txtCliente" id="txtCliente" value="" readonly disabled>
                            </div>                    
                            <div class="form-group">
                                <div class="titulo"><span class=""></span>Dirección</div>
                                <input type="text" class="form-control form-control-sm" name="txtDireccion" id="txtDireccion" value="" readonly disabled>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="titulo"><span class=""></span>Empleado</div>
                                    <input type="text" class="form-control form-control-sm" name="txtEmpleado" id="txtEmpleado" value="" readonly disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="titulo"><span class=""></span>Usuario</div>
                                <input type="text" class="form-control form-control-sm" name="txtUsuario" id="txtUsuario" value="" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="titulo"><span class="fa fa-camera"></span> Adjuntar hoja de reclamo aprobado</div>
                                <div class="custom-file input-group input-group-sm mb-3">
                                    <!-- <input id="file_img_rec" name="file_img_rec" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera" > -->
                                    <input id="file_img_rec" name="file_img_rec" class="custom-file-input" lang="es" type="file" accept="image/*, .pdf">
                                    <label class="custom-file-label col-form-label-sm" data-browse="Tomar foto" for="customFileLang">Hoja de reclamo (imagen o PDF)</label>
                                    <div class="valid-feedback">
                                        <strong></strong>
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-7">
                                        <strong>Por favor tomar una foto!</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="display: flex; justify-content: center; max-height: 151px; min-height: 151px;">
                                <a href="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" data-lightbox="aprobado_rec" data-title="Hoja de reclamo aprobada" id="a_canvas">
                                <img id="canvas" class="example-image" src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" alt="" style="border: 1px solid black;width:100%;max-width:151px;height:auto;max-height:151px;"/>
                                </a>
                            </div>
                        </div>
                    </div> 
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Estado del reclamo</div>
                            <input type="text" class="form-control form-control-sm" name="txtEstado" id="txtEstado" value="" readonly disabled>
                        </div>
                        <?php if( strcmp ($this->session->userdata('tipousuario'), 'ADMIN VENTAS' ) == 0 || 
                            strcmp ($this->session->userdata('tipousuario'), 'BODEGA' ) == 0 ){ ?>
                            <div class="form-group col-md-3">
                                <button type="button" class="btn btn-danger btn-block btn_estado" id="btn_descargar_rec" name="btn_descargar_rec"><span class="fas fa-download fa-lg"></span> Descargar archivo</button>
                                <button type="button" class="btn btn-primary btn-block btn_estado" id="btn_procesar_reclamo" name="btn_procesar_reclamo"><span class="fas fa-file-upload fa-lg"></span> Autorizar y procesar</button>
                                <button type="button" class="btn btn-outline-primary btn-block btn_estado" id="btn_finalizado" name="btn_finalizado"><i class="fas fa-check"></i> Finalizado</button>
                            </div>
                        <?php } ?>

                        <?php if(strcmp ($this->session->userdata('tipousuario'), 'CALIDAD' ) == 0 ){ ?>
                            <div class="btn-group col-md-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-success btn_estado" id="btn_revisado" name="btn_revisado"><i class="fas fa-check"></i> Revisado</button>
                                <button type="button" class="btn btn-danger" id="btn_rechazar_rec" name="btn_rechazar_rec"><i class="fas fa-times fa-lg"></i> Rechazar</button>
                            </div>
                        <?php } ?>
                    </div>
                </form>                   
                <hr>
                <div class="form-row d-flex">
                    <div class="mr-auto p-2 form-group">
                        <h5 class="modal-title" id="exampleModalLongTitle">Información productos dañados</h5>
                    </div>
                    <div class="p-2">
                        <div class="form-group">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-success" id="btn_exportar_rec" name="btn_exportar_rec"><i class="far fa-file-excel"></i> Exportar</button>
                                <button type="button" class="btn btn-danger" id="btn_imprimir" name="btn_imprimir"><i class="far fa-file-pdf"></i> PDF</button>
                            </div>   
                        </div> 
                    </div>
                </div>
                <br>
                <div id="tabla_detalle">
                    <div class="table-responsive">
                        <table id="detalle_rec_table" class="table table-bordered display" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <?php //if($this->session->userdata('id_privilegio') != 5) { ?>
                                    <?php //} ?>
                                    <th scope="col">Comentario</th>
                                    <th scope="col">C&oacute;digo producto</th>
                                    <th scope="col">Descripci&oacute;n</th>
                                    <th scope="col">Tipo de daño</th>
                                    <th scope="col">Unidades dañadas</th>
                                    <th scope="col">Familia</th>
                                    <th scope="col">Cantidad a entregar</th>
                                    <th scope="col">Numero Lote</th>
                                    <th scope="col">Fecha vencimiento</th>
                                    <th scope="col">Foto Producto</th>
                                    <th scope="col">Foto fecha y No.Lote</th>
                                    <th scope="col">Producto dañado</th>                                   
                                </tr>
                            </thead>
                            <tbody id="show_rec">                    
                            </tbody>
                            <tfoot class="thead-dark">
                                <tr>
                                    <?php //if($this->session->userdata('id_privilegio') != 5) { ?>
                                    <?php //} ?>
                                    <th scope="col">Comentario</th>
                                    <th scope="col">C&oacute;digo producto</th>
                                    <th scope="col">Descripci&oacute;n</th>
                                    <th scope="col">Tipo de daño</th>
                                    <th scope="col">Unidades dañadas</th>
                                    <th scope="col">Familia</th>
                                    <th scope="col">Cantidad a entregar</th>
                                    <th scope="col">Numero Lote</th>
                                    <th scope="col">Fecha vencimiento</th>
                                    <th scope="col">Foto Producto</th>
                                    <th scope="col">Foto fecha y No.Lote</th>
                                    <th scope="col">Producto dañado</th>
                                    
                                </tr>
                            </tfoot>
                        </table>
                    </div>  
                </div>           
            

            </div>            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL DETALLE RECLAMOS -->

<!-- MODAL INGRESO RECLAMOS BODEGA / MERCADEO -->
<div class="modal fade-scale" id="modal_ingreso_rec" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Ingreso de reclamo calidad nuevo</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 4% 4% 0% 4%;">
                <input type="hidden" name="txtIdRuta" id="txtIdRuta" value="">
                <form id="frm_ingreso_rec">
                    <div id="">   
                    <?php if(!$this->session->userdata('usu_pais_tercero')){ ?>     
                        <div class="form-row">
                            <div class="form-group col-md-12 divrow">
                                <div class="titulo"><span class=""></span> Tipo de reclamo</div>
                                <div id="fil_tipo_reclamo">
                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>
                        </div>

                    <?php } else {?>      
                        <div class="form-row">
                            <div class="form-group col-md-6 divrow">
                                <div class="titulo"><span class=""></span> Tipo de reclamo</div>
                                <div id="fil_tipo_reclamo">
                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>
                            <div class="form-group col-md-6 divrow">
                                <div class="titulo"><span class=""></span> Distribuidora</div>
                                <div id="fil_distribuidora_pt">
                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>
                        </div>
                    <?php } ?>                 
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <div class="titulo"><span class=""></span>Descripción del producto:</div>
                                <input type="text" class="form-control" name="txtProducto_" id="txtProducto_" value="" readonly disabled>
                            </div>                    
                            <div class="form-group col-md-4">
                                <div class="titulo"><span class=""></span>Código producto</div>
                                <input type="text" class="form-control" name="txtCodigoP_" id="txtCodigoP_" value="" readonly >
                            </div>
                        </div>
                        <div class="form-row">                        
                            <div class="form-group col-md-6">
                                <div class="titulo"><span class=""></span>No. Lote</div>
                                <input type="text" class="form-control" name="txtNumLote_" id="txtNumLote_" value="">
                            </div>      
                            <div class="form-group col-md-6">
                                <div class="titulo"><span class=""></span>Fecha de vencimiento</div>
                                <input type="text" class="form-control" name="txtFechaVencimiento_" id="txtFechaVencimiento_" value="" readonly>
                            </div>
                        </div>
                        <!-- <div class="form-row">                        
                            <div class="form-group col-md-6">
                                <div class="titulo"><span class=""></span>Cantidad:</div>
                                <input type="number" class="form-control" name="txtCantidad_" id="txtCantidad_" value="">
                            </div>
                            <div class="form-group col-md-6 divrow">
                                <div class="titulo"><span class=""></span> Unidad de medida</div>
                                <div id="fil_um_">

                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>                  
                        </div> -->
                        <div class="form-row">                        
                            <div class="form-group col-md-4">
                                <div class="titulo"><span class=""></span>Unidades dañadas (UN)</div>
                                <input type="number" class="form-control" name="txtUnidadesDanadas_bo" id="txtUnidadesDanadas_bo" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <div class="titulo"><span class=""></span>Cantidad a entregar:</div>
                                <input type="number" class="form-control" name="txtCantidad_" id="txtCantidad_" value="">
                            </div>
                            <div class="form-group col-md-4 divrow">
                                <div class="titulo"><span class=""></span> Unidad de medida</div>
                                <div id="fil_um_">

                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>                  
                        </div>
                        <div class="form-row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="titulo"><span class="fa fa-camera"></span> Foto de fecha y lote</div>
                                    <div class="custom-file">
                                        <input id="file_fecha_lote_" name="file_fecha_lote_" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                        <label class="custom-file-label" data-browse="Tomar foto" for="file_fecha_lote_">Fotografía de fecha y lote</label>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-7">
                                            <strong>Por favor tomar una foto!</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="display: flex; justify-content: center; max-height: 164px; min-height: 164px;">                        
                                    <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="canvas_" style="border: 1px solid black;width:auto;max-width:224px;height:164px;max-height:164px;" class="align-content-center zoom">
                                </div>                            
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="titulo"><span class="fa fa-camera"></span>Foto de producto dañado</div>
                                    <div class="custom-file">
                                        <input id="file_producto_" name="file_producto_" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                        <label class="custom-file-label" data-browse="Tomar foto" for="file_producto_">Foto de producto dañado</label>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-7">
                                            <strong>Por favor tomar una foto!</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="display: flex; justify-content: center; max-height: 164px; min-height: 164px;">                        
                                    <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="canvas1_" style="border: 1px solid black;width:auto;max-width:224px;height:164px;max-height:164px;" class="align-content-center zoom">
                                </div>                           
                            </div>
                        </div>  
                        <div class="form-row"> 
                            <div class="form-group col-md-12">
                                <div class="titulo"><span class=""></span>Observación (opcional):</div>
                                <textarea class="form-control" id="txtObservacion_" name="txtObservacion_" rows="2"></textarea>
                            </div>
                        </div>                    
                    </div> 
            </div>            
            <div class="modal-footer">
                <button id="btn_enviar" class="btn btn-primary" ><span class="fas fa-paper-plane fa-lg" ></span> Enviar Reclamo</button>
                <!-- <input type="submit" id="btn_enviar" class="btn btn-primary" value="Enviar Reclamo" >
                </input> -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
                </form>                 
        </div>
    </div>
</div>
<!-- FIN MODAL INGRESO RECLAMOS BODEGA / MERCADEO -->
<?php 
// -- 29_07_2021 ------------------------------------
    require 'V_catalogo_bodega.php'; 
// --------------------------------------------------
?>
<script type="text/javascript">
    document.getElementById('file_img_rec').onchange = function(evt) {
        // console.log($(this).val());
        var fakep = '';
        foto_reclamo = '';
        if($(this).val() != ''){
            fakep = $(this).val();
            ImageTools.resize(this.files[0], {
                width: 823,
                height: 403
            }, function(blob, didItResize) {
                var src_ = window.URL.createObjectURL(blob);
                // document.getElementById('canvas').src = window.URL.createObjectURL(blob);
                if(fakep.indexOf(".pdf") != -1){
                    $("#canvas").attr("src","../dependencias/imagenes/pdf-icon-1.png");
                    $("#a_canvas").attr("href","../dependencias/imagenes/pdf-icon-1.png");
                }else{
                    document.getElementById('canvas').src = src_;
                    $("#a_canvas").attr("href",src_);
                }
                var reader = new FileReader();
                reader.readAsDataURL(blob); 
                reader.onloadend = function(){
                    if(blob === null || blob === "" || blob === undefined){
                        $("#file_img_rec").val("");
                        $("#error_fotouno").empty().html('* LA FOTO ES OBLIGATORIA');
                        $("#canvas").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                        Swal.fire({
                            title: '<strong>Atención!</strong>',
                            type: 'warning',
                            html:`<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
                            confirmButtonText:'Ok'
                        });
                    }else{
                        var base64data = reader.result;
                        foto_reclamo = base64data;
                    }
                    URL.revokeObjectURL(this.src); 
                }
            });
        }else{
            $("#canvas").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            $("#a_canvas").attr("href","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        }
    };

    document.getElementById('file_fecha_lote_').onchange = function(evt) {
        fotoFechaLote_ = '';
        if($(this).val() != ''){
            ImageTools.resize(this.files[0], {
                width: 823,
                height: 403
            }, function(blob, didItResize) {
                document.getElementById('canvas_').src = window.URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob); 
                reader.onloadend = function() {

                    if(blob === null || blob === "" || blob === undefined){
                        $("#file_fecha_lote_").val("");
                        $("#error_fotouno").empty().html('* LA FOTO ES OBLIGATORIA');
                        $("#canvas_").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                        Swal.fire({
                            title: '<strong>Atención!</strong>',
                            type: 'warning',
                            html:`<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
                            confirmButtonText:'Ok'
                        });
                    }else{
                        var base64data = reader.result;
                        fotoFechaLote_ = base64data;
                    }
                      URL.revokeObjectURL(this.src); 
                }
            });
        }else{
            $("#canvas_").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        }
    };

    document.getElementById('file_producto_').onchange = function(evt) {
        fotoProducto_ = '';
        if($(this).val() != ''){
            ImageTools.resize(this.files[0], {
                width: 823,
                height: 403
            }, function(blob, didItResize) {
                document.getElementById('canvas1_').src = window.URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob); 
                reader.onloadend = function() {

                    if(blob === null || blob === "" || blob === undefined){
                        $("#file_producto_").val("");
                        $("#error_fotouno").empty().html('* LA FOTO ES OBLIGATORIA');
                        $("#canvas1_").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                        Swal.fire({
                            title: '<strong>Atención!</strong>',
                            type: 'warning',
                            html:`<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
                            confirmButtonText:'Ok'
                        });
                    }else{
                        var base64data = reader.result;
                        fotoProducto_ = base64data;
                    }
                      URL.revokeObjectURL(this.src); 
                }
            });
        }else{
            $("#canvas1_").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        }
    };

/*$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
      theme: "minimal"
    });
    // $("#responsivo-table").mCustomScrollbar({
    //   theme: "minimal"
    // });
    // $("#content").mCustomScrollbar({
    //   theme: "dark-3"
    // });
    $(document).on("click", "#dismiss, .overlay", function() {
    // $('#dismiss, .overlay').on('click', function () {
      $('#sidebar').removeClass('active');
      $('.overlay').removeClass('active');
    });
    $(document).on("click", "#sidebarCollapse", function() {
    // $('#content #sidebarCollapse').on('click', function () {
      $('#sidebar').addClass('active');
      $('.overlay').addClass('active');
      $('.collapse.in').toggleClass('in');
      $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

  });*/
</script>

