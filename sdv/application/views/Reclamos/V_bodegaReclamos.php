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
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/HP_JS.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_reclamos.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_reclamosBodega.js') ?>"></script>

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
    function salir(){
        location.href = "../../sdv/";
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
    <!--000000---DIV CARGANDO---000000
        <nav class="navbar fixed-top navbar-expand-lg" id="menu_bar">
            <div style="float:left;" class="icons_posicion">
                <span class="fa fa-user fa-2x"></span>
                <span id="uslogin"></span>
            </div>
            <div style="margin:0 auto;" id="btn-pendinetes" onclick="agregar_us_offline()" class="icons_posicion">
            </div>
            <div style="" class="icons_posicion" id="btn-menu-back">
                <span class="fa fa-bars fa-2x" style=""></span>
            </div>
        </nav> -->
    <!--000000---FIN DIV CARGANDO---000000-->

    <div class="container-fluid" id="contenedor" style="margin-top:50px;">     
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="padding:20px;">
                    <div class="card-body">
                        <h5 class="card-title">Lista reclamos</h5>
                        <form>
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
                            
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <div class="titulo"> Fecha inicial</div>
                                    <input type="text" class="form-control" name="txtFechaInicial" id="txtFechaInicial" value="">
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="titulo">Fecha limite</div>
                                    <input type="text" class="form-control" name="txtFechaLim" id="txtFechaLim" value="">
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="titulo"><span class="">Buscar</span> </div>
                                    <div>
                                        <button type="button" class="btn btn-success btn-block" id="btn_buscar">
                                            <span class="fas fa-search"></span> 
                                        </button>
                                    </div>
                                </div> 
                                <div class="form-group col-md-2">
                                    <div class="titulo"><span class="">Buscar</span> </div>
                                    <div>
                                        <button type="button" class="btn btn-success btn-block" id="btn_m">
                                            <span class="fas fa-search"></span> 
                                        </button>
                                    </div>
                                </div>         
                            </div>                            
                        </form>
                        <hr>
                        <div class="table-responsive">

                            <table id="lista_reclamos_table" class="table table-bordered display" style="width:100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">No. Reclamo</th>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="display: none;">
            <div class="col-md-12">
                <div class="card" style="padding:20px;">
                    <div class="card-body">
                        
                    </div> 
                </div>
            </div>
        </div>

    </div>

<!-- Modal -->
<div class="modal" id="modal_detalle_rec" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">DETALLE DE RECLAMO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding:4%;">
                <input type="hidden" name="txtIdRuta" id="txtIdRuta" value="">
                <div id="frm_detalle_rec">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="modal-title" id="exampleModalLongTitle">Información Reclamo</h6>            
                        </div>                  
                    </div>

                    <br>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <div class="titulo"><span class=""></span>Codigo Reclamo:</div>
                            <input type="text" class="form-control" name="txtCodigoReclamo" id="txtCodigoReclamo" value="" readonly disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Fecha reclamo</div>
                            <input type="text" class="form-control" name="txtFechaReclamo" id="txtFechaReclamo" value="" readonly disabled>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <div class="titulo"><span class=""></span>País:</div>
                            <input type="text" class="form-control" name="txtPais" id="txtPais" value="" readonly disabled>
                        </div>                    
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>División</div>
                            <input type="text" class="form-control" name="txtDivision" id="txtDivision" value="" readonly disabled>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Distribuidora:</div>
                            <input type="text" class="form-control" name="txtDistribuidora" id="txtDistribuidora" value="" readonly disabled>

                        </div>                    
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Canal</div>
                            <input type="text" class="form-control" name="txtCanal" id="txtCanal" value="" readonly disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>ruta</div>
                            <input type="text" class="form-control" name="txtRuta" id="txtRuta" value="" readonly disabled>
                        </div>
                    </div>                   
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <div class="titulo"><span class=""></span>Cliente:</div>
                            <input type="text" class="form-control" name="txtCliente" id="txtCliente" value="" readonly disabled>
                        </div>                    
                        <div class="form-group col-md-4">
                            <div class="titulo"><span class=""></span>Código cliente</div>
                            <input type="text" class="form-control" name="txtCodigoCliente" id="txtCodigoCliente" value="" readonly disabled>
                        </div>
                    </div>                             
                    <div class="form-row justify-content-center">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="titulo"><span class=""></span>Dirección</div>
                                <input type="text" class="form-control" name="txtDireccion" id="txtDireccion" value="" readonly disabled>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="titulo"><span class=""></span>Empleado</div>
                                    <input type="text" class="form-control" name="txtEmpleado" id="txtEmpleado" value="" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="titulo"><span class=""></span>Carnet</div>
                                    <input type="text" class="form-control" name="txtCarnet" id="txtCarnet" value="" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="titulo"><span class=""></span>Grupo</div>
                                <input type="text" class="form-control" name="txtGrupo" id="txtGrupo" value="" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="titulo"><span class="fa fa-camera"></span> Fotografia de reclamo aprovado:</div>
                                <div class="custom-file">
                                    <input id="file_img_rec" name="file_img_rec" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                    <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Fotografía de fecha y lote</label>
                                    <div class="valid-feedback">
                                        <strong></strong>
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-7">
                                        <strong>Por favor tomar una foto!</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="display: flex; justify-content: center; max-height: 164px; min-height: 164px;">                        
                                <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="canvas" style="border: 1px solid black;width:100%;max-width:164px;height:auto;max-height:164px;" class="align-content-center zoom">
                            </div>
                            <div class="form-group" style="display-flex: justify-content-center;">
                                <button type="button" class="btn btn-primary btn-block" id="btn_subir_foto" name="btn_subir_foto">
                                    <span class="fas fa-file-upload fa-lg"></span> Guardar foto
                                </button>
                            </div>
                        </div>
                    </div>             
                </div>                   
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-10">
                        <h5 class="modal-title" id="exampleModalLongTitle">Información productos dañados</h5>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-block" id="btn_imprimir" name="btn_imprimir"><i class="far fa-file-pdf fa-lg"></i> Imprimir</button>
                        <!-- <a href=""></a> -->
                    </div>
                    <div class="form-group col-md-2" style="display:none;">
                        <button type="button" class="btn btn-outline-success btn-block" id="btn_exportar" name="btn_exportar"><i class="far fa-file-excel fa-lg"></i> Exportar</button>
                        <!-- <a href=""></a> -->
                    </div>
                </div>
                <br>
                <div id="tabla_detalle">
                    <div class="table-responsive">
                        <table id="detalle_rec_table" class="table table-bordered display" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">C&oacute;digo producto</th>
                                    <th scope="col">Descripci&oacute;n</th>
                                    <th scope="col">Tipo de daño</th>
                                    <th scope="col">Unidad Medida</th>
                                    <th scope="col">Familia</th>
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
                                    <th scope="col">C&oacute;digo producto</th>
                                    <th scope="col">C&oacute;digo cliente</th>
                                    <th scope="col">Tipo de daño</th>
                                    <th scope="col">Unidad Medida</th>
                                    <th scope="col">Familia</th>
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

<script type="text/javascript">

        document.getElementById('file_img_rec').onchange = function(evt) {
            foto_reclamo = '';
          ImageTools.resize(this.files[0], {
            width: 823,
            height: 403
          }, function(blob, didItResize) {
            document.getElementById('canvas').src = window.URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob); 
            reader.onloadend = function() {

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
        };

</script>

