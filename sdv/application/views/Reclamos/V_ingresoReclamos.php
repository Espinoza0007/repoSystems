<!--ESTILOS CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_actuClientes.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/gijgo-combined-1.9.11/css/gijgo.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2-bootstrap.min.css') ?>">

<!--JAVASCRIPTS JS-->
    <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-validation/dist/jquery.validate.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-validation/dist/additional-methods.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/gijgo-combined-1.9.11/js/gijgo.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/select2-4.0.7/js/select2.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_reclamos.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/js_reclamoNuevo.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_catalogo.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/xlsx.full.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>
    
    <style>    
        .cubrir_campos {
            background-color: rgba(0, 0, 0, .5);
            height: 2em;
            width: 150px;
            position: absolute;
            top: 10px;
            color: #fff;
            text-align: center;
            padding-top: 1em;
        }

        .div_comentario{
            margin-top:10px;
            background-color:#FFFB71;
            color:#2F2E0F;
            /* height:77px; */
            /* overflow: auto; */
            border-radius:7px;
            font-size:17px;
            font-weight:600;padding: 10px;
            border:1px solid #DAD64C;
        }

        .row div textarea{
            width: 100%;
            height: 107px;
        }

        .vya{
            color:#3F3F3F;
            margin-right:3px;
            text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
        }

        .separador {background-color:#727070;width: 100%;}

        .lbl_fultima{

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

        img:hover{
            height:100px;
            width: auto;
            -webkit-transition: width 2s;
        }

        #snackbar {
            /*top:0;*/
            visibility: hidden;
            min-width: 250px;
            /* margin-left: -125px; */
            background-color: #333;
            color: #fff;
            text-align: center;
            /* border-radius: 2px; */
            padding: 16px;
            position: fixed;
            z-index: 9999;
            /* left: 50%; */
            /* bottom: 30px; */
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
        }


        #snackbar.show {
            visibility: visible;
            /* -webkit-animation: fadein 0.5s;
            animation: fadein 0.5s; */
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

        .badge-estado-reclamo{
            font-size:12px;
            padding:6px 8px;
            border-radius:12px;
        }

        #div_listado_reclamos .card{
            margin-bottom:15px;
        }

        #tbl_reclamos{
            font-size:12px;
        }

        #tbl_reclamos thead th{
            white-space:nowrap;
        }

        .btn-nuevo-reclamo{
            font-weight:bold;
        }

    </style>

</head>
<?php require_once(APPPATH.'views/Reclamos/V_catalogo.php'); ?>
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
    <!--000000---DIV CARGANDO---000000-->
    <div id="content-carga" style="display:none;" class="carga-class">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
    </div>

    <div id="notificacion_ac" style="position: absolute;width: 100%;height: auto;display: none;">
        <div id="snackbar">Hay disponible una versión nueva de la aplicación. 
            <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a>
        </div>
    </div>

    <nav class="navbar fixed-top navbar-expand-lg" id="menu_bar">
        <div style="float:left;" class="icons_posicion">
            <span class="fa fa-user fa-2x"></span>
            <span id="uslogin"></span>
        </div>
        <div style="margin:0 auto;" id="btn-pendinetes" onclick="agregar_us_offline()" class="icons_posicion">
            <span class="fa fa-cloud-upload-alt fa-2x" style="color:#fff;"></span> <span style="font-size: 14px;" class="badge badge-pill badge-dark" id="RegisCola">0</span>
        </div>

        <!-- <div style="margin:0 auto;" id="btn-exp" onclick="get_data_info()" class="icons_posicion"> -->
        <!-- <div style="margin:0 auto;" id="btn-exp" onclick="get_registros_reclamos()" class="icons_posicion">
            <i class="fas fa-file-export fa-2x" style="color:#fff;"></i>
        </div> -->

        <div style="" class="icons_posicion" id="btn-menu-back">
            <span class="fa fa-bars fa-2x" style=""></span>
        </div>
    </nav>
    <div class="container-fluid" id="contenedor-principal" style="margin-top:50px;">

        <!-- LISTADO INICIAL DE RECLAMOS -->
        <div id="div_listado_reclamos" style="width:100%;">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-7">
                            <h5 class="mb-0">
                                <i class="fas fa-list"></i> Listado de Reclamos
                            </h5>
                        </div>
                        <div class="col-5 text-right">
                            <button type="button" class="btn btn-primary btn-sm btn-nuevo-reclamo" onclick="mostrarNuevoReclamo();">
                                <i class="fas fa-plus"></i> Nuevo Reclamo
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
            

                    <div class="table-responsive">
                        <table id="tbl_reclamos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No Reclamo</th>
                                    <th>Tipo Reclamo</th>
                                    <th>Código Producto</th>
                                    <th>Producto</th>
                                    <th>Cantidad Entregar</th>
                                    <th>Cliente</th>
                                    <th>Ruta</th>
                      
                                    <th>Fecha Reclamo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_reclamos">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN LISTADO INICIAL DE RECLAMOS -->

        <div style="width: 100%; display:none;" id="contenedor-formulario">
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
            <!-- INFORMACION DE RECLAMO -->
                <form id="form_reclamo" name="form_reclamo" >
                    <div class="row contenedor">
                        <div class="col-md-12 celda">
                            <h5 class="card-header card-header-form">
                                Reclamo de productos
                            </h5>
                            <br>
                            <input type="hidden" id="imagenuno_no" name="imagenuno_no" value="">          
                            <input type="hidden" id="imagendos_no" name="imagendos_no" value="">
                            <div class="row" style="margin-top: 5%;">
                                <div class="col-6" id="btn-clientes">
                                    <button type="button" class="btn btn-dark" id="btn-formopciones" onclick="DB_CargarFiltrosReclamos('rec');"><span class="fa fa-database" style="font-size: 25px;"></span><span style="font-size: 12px;"><br>Catálogo</span></button>

                                    <button type="button" class="btn btn-dark" id="btn-formopciones-hide" style="display: none;">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    <span style="font-size: 12px;"><br>Catálogo</span></button>
                                </div>
                                
                                <div class="col-6" id="btn-listaClientes">
                                    <button type="button" class="btn btn-dark" id="btn-formopciones1" onclick="DB_CargarListaClientes('rec');"><span class="fa fa-users" style="font-size: 25px;"></span><span style="font-size: 12px;"><br>Clientes</span></button>

                                    <button type="button" class="btn btn-dark" id="btn-formopciones1-hide" style="display: none;">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    <span style="font-size: 12px;"><br>Clientes</span></button>
                                </div>
                            </div>

                            <!-- CUADRO DE AVISO -->
                            <div id="InfoCuadro" style="margin-top:30px;">
                                <div class="alert alert-info" role="alert" style="text-align: center;margin-top: 10px;">
                                    <h4 class="alert-heading">Aviso!</h4>
                                    <p>Por favor seleccione un producto del <b>catálogo</b> y un <b>cliente</b> de la lista.</p>                                
                                </div>
                            </div>
                            <!-- FIN CUADRO DE AVISO -->
                            <!-- FORMULARIO DE RECLAMO -->
                            <div id="form_actuinfo" style="display:none;">
                                <div id="conten_Si_No" class="nada">                                                   
                                    <input type="hidden" name="txtCodigo" id="txtCodigo" value="">
                                    <input type="hidden" name="txtCodigoProducto" id="txtCodigoProducto" value="">
                                    <input type="hidden" name="txtIdCliente" id="txtIdCliente" value="">

                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-users fa-lg"></span> Cliente:</div>
                                            <input type="text" class="form-control" name="txtNombre" id="txtNombre" value="" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-question fa-lg"></span> Familia de producto:</div>
                                            <input type="text" class="form-control" name="txtFamilia" id="txtFamilia" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fas fa-cookie-bite fa-lg"></span> Producto:</div>
                                            <input type="text" class="form-control" name="txtProducto" id="txtProducto" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-signature fa-lg"></span> Tipo de reclamo:</div>
                                            <div id="slc_tipo_rec">

                                            </div>                                        
                                        </div>
                                    </div>


                                    <div id="div_bocadeli" style="display:none;">
                                        <div class="row">
                                            <div class="col-md-12 divrow">
                                                <div class="titulo"><span class="fa fa-signature fa-lg"></span> Unidades dañadas (UN): </div>
                                                <input type="number" class="form-control" name="txtUnidadesDanadas" id="txtUnidadesDanadas" value="" autocomplete="off">
                                            </div>
                                        </div>                               
                                        <div class="form-row">
                                            <div class=" col-12">
                                                <div class="form-group">
                                                    <div class="titulo"><span class="fa fa-signature fa-lg"></span> Cantidad a entregar (UN): </div>
                                                    <input type="number" class="form-control" name="txtCantidad" id="txtCantidad" value="" autocomplete="off">
                                                </div>
                                            </div>
                                            <!-- <div class=" col-4">
                                                <div class="form-group">
                                                    <div class="titulo"><span class="fas fa-weight fa-lg"></span> um:</div>
                                                    <div id="sunidad_medida">
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 divrow" id="div_fecha_ven">
                                                <div class="titulo"><span class="fas fa-calendar-day fa-lg"></span> Fecha de vencimiento:</div>
                                                <input type="text" class="form-control .datepicker" name="txtFechaVencimiento" id="txtFechaVencimiento" value="" autocomplete="off" onkeydown="return false" readonly>
                                            </div>
                                        </div>   
                                        <div class="row" id="title_N_lote">
                                            <div class="col-md-12 divrow">
                                                <div class="titulo"><span class="fas fa-signature fa-lg"></span> NUMERO LOTE:</div>
                                                <label style="color:#5F5F5F;">Ejemplo: LTL17:26US37 R41</label>
                                                <input type="text" class="form-control" name="txtNumeroLote" id="txtNumeroLote" value="" autocomplete="off" placeholder="Ej: LTL17:26US37 R41">
                                            </div>
                                        </div>  
                                        <div class="card d-none" id="div_N_lote">
                                            <div class="card-body" >

                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <div class="titulo"><!-- <span class="fa fa-signature fa-lg"></span> --> No. Maquina: </div>
                                                        <input type="text" class="form-control" name="txtNumMaquina" id="txtNumMaquina" value="" autocomplete="off" placeholder="Ej: LTL, LTQ...">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <div class="titulo"><!-- <span class="fa fa-signature fa-lg"></span> --> US: </div>
                                                        <input type="number" class="form-control" name="txtUS" id="txtUS" value="" autocomplete="off" placeholder="Ej: 39">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <div class="titulo"><!-- <span class="fa fa-signature fa-lg"></span> --> R: </div>
                                                        <input type="number" class="form-control" name="txtR" id="txtR" value="" autocomplete="off" placeholder="Ej: 39">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <div class="titulo"><!-- <span class="fa fa-signature fa-lg"></span> --> Hora: </div>
                                                        <input type="text" class="form-control" name="txtHora" id="txtHora" value="" autocomplete="off" placeholder="Ej: 09:13" onkeydown="return false" readonly="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 divrow">
                                                <div class="titulo"><span class="fa fa-camera fa-lg"></span> Fotografía de fecha y lote:</div>
                                                <div class="custom-file">
                                                    <input id="file1" name="fileFechaLote" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                                    <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Fotografía de fecha y lote</label>
                                                </div>
                                                <div class="contenedorFotos">
                                                    <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen1" class="imagen">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 divrow">
                                                <div class="titulo"><span class="fa fa-camera fa-lg"></span> Fotografía de producto:</div>
                                                <div class="custom-file">
                                                    <input id="file2" name="fileProducto" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                                    <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Fotografía de fecha y lote</label>
                                                </div>
                                                <div class="contenedorFotos">
                                                    <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen2" class="imagen">
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    
                                    <div id="div_exhibidores" style="display:none;">
                                        
                                        <div class="row">
                                            <div class="col-md-12 divrow">
                                                <div class="titulo"><span class="fa fa-signature fa-lg"></span> Proveedor:</div>
                                                <div id="slc_proveedor">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 divrow">
                                                <div class="titulo"><span class="fa fa-camera fa-lg"></span> Fotografía del sticker:</div>
                                                <div class="custom-file">
                                                    <input id="file3" name="fileSticker" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                                    <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Fotografía del sticker</label>
                                                </div>
                                                <div class="contenedorFotos">
                                                    <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen3" class="imagen">
                                                </div>
                                            </div>
                                        </div>    
                                        
                                        
                                        <div class="row">
                                            <div class="col-md-12 divrow">
                                                <div class="titulo"><span class="fa fa-camera fa-lg"></span> Fotografía del inconveniente:</div>
                                                <div class="custom-file">
                                                    <input id="file4" name="fileDano" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                                    <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Fotografía del daño</label>
                                                </div>
                                                <div class="contenedorFotos">
                                                    <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen4" class="imagen">
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <!-- FIN FORMULARIO DE RECLAMO -->
                        
                        </div>
                    </div>
                    
                    <!--FINAL INFO RECLAMO-->
                    <div class="row">
                        <div class="col-md-12 divrow">
                            <div class="textoSeleccion">                            
                                <center>
                                <?php //$texto = 'L_0,M_0,I_0,J_0,V_0,S_0,D_0'; var_dump(str_replace('0','1',$texto)); ?>
                                    <button id="btn-enviar" class="btn btn-primary" style="display: none;" ><span class="fas fa-paper-plane fa-lg" ></span> Enviar Reclamo
                                    </button>
                                    <!-- <input type="submit" id="btn-enviar" class="btn btn-primary carga-esconder" style="display: none;" onclick="enviar_registro_reclamo();" value="Enviar Reclamo"> -->
                                </center>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FIN DE INFORMACION DE RECLAMO -->
        
            <div id="bitacora_reclamos" >
                <div id="accordion_ls_rec">            
                </div>                        
            </div>
        </div>
    
    </div>

    
<script type="text/javascript">
    $("#form_actuinfo").on('change', '.custom-file-input', function() {
        var img_id = $(this).attr("id"),id_Org = 0;
        img_id = img_id.substring(4,img_id.length);id_Org = parseInt(img_id);
        img_id= "imagen"+img_id;
        ImageTools.resize(this.files[0], {
            width: 923,
            height: 503
        }, function(blob, didItResize) {
            document.getElementById( img_id ).src = window.URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob); 
            reader.onloadend = function() {
                if(blob === null || blob === "" || blob === undefined){
                    $("#"+$(this).attr("id")).attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                    Swal.fire({
                        title: '<strong>Atención!</strong>',
                        type: 'warning',
                        html:`Por favor vuelve a tomar foto`,
                        confirmButtonText:'Ok'
                    });
                }else{
                    var base64data = reader.result;
                    arrg_fotos[id_Org] = base64data;
                }
                URL.revokeObjectURL(this.src);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        cargarListadoReclamosInicial();
    });

    function mostrarNuevoReclamo(){
        $('#div_listado_reclamos').hide();
        $('#contenedor-formulario').show();
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    }

    function mostrarListadoReclamos(){
        $('#contenedor-formulario').hide();
        $('#div_listado_reclamos').show();
        $('html, body').animate({ scrollTop: 0 }, 'fast');
        cargarListadoReclamosInicial();
    }

    function limpiarTextoTabla(valor){
        if(valor === null || valor === undefined || valor === ''){
            return '';
        }
        return String(valor)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function claseEstadoReclamo(estado){
        estado = limpiarTextoTabla(estado).toUpperCase();

        if(estado === 'ACTIVO'){
            return 'badge badge-primary badge-estado-reclamo';
        }

        if(estado === 'RECHAZADO'){
            return 'badge badge-danger badge-estado-reclamo';
        }

        if(estado === 'REVISADO'){
            return 'badge badge-warning badge-estado-reclamo';
        }

        if(estado === 'FINALIZADO'){
            return 'badge badge-success badge-estado-reclamo';
        }

        return 'badge badge-secondary badge-estado-reclamo';
    }



function cargarListadoReclamosInicial(){

    $('#content-carga').show();

    setTimeout(function(){

        var credencialesEnviar = {};

        if (typeof arrg_Credls !== 'undefined' && arrg_Credls !== null) {
            credencialesEnviar = Object.assign({}, arrg_Credls);
        }

        $.ajax({
            url: '<?php echo base_url("index.php/rec_Listadoini"); ?>',
            type:'POST',
            data:{
                arrg_Credls: JSON.stringify(credencialesEnviar)
            },
            dataType:'JSON',
            timeout:30000
        }).done(function(resp){

            $('#content-carga').hide();

            if($.fn.DataTable.isDataTable('#tbl_reclamos')){
                $('#tbl_reclamos').DataTable().clear().destroy();
            }

            $('#tbody_reclamos').html('');

            if(resp.rs){

                if(resp.data && resp.data.length > 0){

                    $.each(resp.data, function(i, row){

                        var estado = limpiarTextoTabla(row.ESTADO);
                        var claseEstado = claseEstadoReclamo(estado);

                        $('#tbody_reclamos').append(`
                            <tr>
                                <td>${limpiarTextoTabla(row.NUMERO_RECLAMO)}</td>
                                <td>${limpiarTextoTabla(row.TIPO_RECLAMO)}</td>
                                <td>${limpiarTextoTabla(row.CODIGO_PRODUCTO)}</td>
                                <td>${limpiarTextoTabla(row.PRODUCTO)}</td>
                                <td>${limpiarTextoTabla(row.CANTIDAD_A_ENTREGAR)}</td>
                                <td>${limpiarTextoTabla(row.NOMBRE_CLIENTE)}</td>
                                <td>${limpiarTextoTabla(row.RUTA)}</td>
                                <td>${limpiarTextoTabla(row.FECHA_RECLAMO)}</td>
                                <td><span class="${claseEstado}">${estado}</span></td>
                            </tr>
                        `);
                    });
                }

                $('#tbl_reclamos').DataTable({
                    responsive:true,
                    pageLength:10,
                    lengthMenu:[[10,25,50,100,-1],[10,25,50,100,'Todos']],
                    order:[[0,'desc']],
                    language:{
                        decimal:'',
                        emptyTable:'No hay reclamos disponibles',
                        info:'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty:'Mostrando 0 a 0 de 0 registros',
                        infoFiltered:'(filtrado de _MAX_ registros totales)',
                        infoPostFix:'',
                        thousands:',',
                        lengthMenu:'Mostrar _MENU_ registros',
                        loadingRecords:'Cargando...',
                        processing:'Procesando...',
                        search:'Buscar:',
                        zeroRecords:'No se encontraron registros',
                        paginate:{
                            first:'Primero',
                            last:'Último',
                            next:'Siguiente',
                            previous:'Anterior'
                        },
                        aria:{
                            sortAscending:': activar para ordenar ascendente',
                            sortDescending:': activar para ordenar descendente'
                        }
                    }
                });

            }else{

                $('#tbl_reclamos').DataTable({
                    responsive:true,
                    pageLength:25,
                    language:{
                        emptyTable: resp.info ? resp.info : 'No hay información disponible',
                        search:'Buscar:',
                        lengthMenu:'Mostrar _MENU_ registros',
                        info:'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty:'Mostrando 0 a 0 de 0 registros',
                        zeroRecords:'No se encontraron registros',
                        paginate:{
                            first:'Primero',
                            last:'Último',
                            next:'Siguiente',
                            previous:'Anterior'
                        }
                    }
                });

                Swal.fire({
                    title:'Aviso!',
                    type:'info',
                    html:'<h6>'+(resp.info ? resp.info : 'No se encontraron reclamos')+'</h6>',
                    confirmButtonText:'Ok'
                });
            }

        }).fail(function(){

            $('#content-carga').hide();

            Swal.fire({
                title:'Error!',
                type:'error',
                html:'<h6>Error al cargar el listado inicial de reclamos.</h6>',
                confirmButtonText:'Ok'
            });
        });

    }, 1000);
}


</script>
