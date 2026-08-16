<!--ESTILOS CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_actuClientes.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/gijgo-combined-1.9.11/css/gijgo.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2-bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/leaflet/leaflet.css') ?>">

<!--JAVASCRIPTS JS-->
    <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-validation/dist/jquery.validate.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/jquery-validation/dist/additional-methods.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/gijgo-combined-1.9.11/js/gijgo.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/select2-4.0.7/js/select2.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_reclamos.js') ?>"></script>
    <!-- <script type="text/javascript" src="<?php // echo base_url('dependencias/js/js_reclamoNuevo.js') ?>"></script> -->
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_catalogo.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_Control_Inventario.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>
    
<style>    
    .card_cti_{
        border-top: 10px solid #2ca444;
        /* border-top: 10px solid #04A0F4; */
        width: 90%;
        margin: 0 auto;
        margin-top: 25px;
        margin-bottom: 25px;
        border-radius: 5px;
        background-color: #fff;
        /* padding-top: 10px; */
        box-shadow: 0 3px 5px -1px rgb(0 0 0 / 20%), 0 5px 8px 0 rgb(0 0 0 / 14%), 0 1px 14px 0 rgb(0 0 0 / 12%);
    }
    .btn_editar_cti{
        border: none;
        background-color: #fff;
    }
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

    /* .row div textarea{
        width: 100%;
        height: 107px;
    } */

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

    /* img:hover{
        height:100px;
        width: auto;
        -webkit-transition: width 2s;
    } */

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

    #titulo_inv{
        margin-top:20px;
    }
    .btn_invC{
        width:100px;
        border-radius:3px;
        font-weight:bold;
        font-size:20px;
    }
    #cliboraddd{
        margin-top:20px;
        width:200px;
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

<!-- DIV CARGA -->
    <div id="content-carga" style="display:none;" class="carga-class">
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
        <div style="margin:0 auto;" id="btn-pendinetes" onclick="enviar_cola_cti()" class="icons_posicion">
            <span class="fa fa-cloud-upload-alt fa-2x" style="color:#fff;"></span> <span style="font-size: 14px;" class="badge badge-pill badge-dark" id="RegisCola">0</span>
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
            <!-- SELECCION CLIENTE -->
                <div class="row contenedor">
                <!-- <form id="frm_info_cti" name="frm_info_cti" > -->
                    <div class="col-md-12 celda" id="frm_info_cti">
                        <h5 class="card-header card-header-form">
                            Control de inventario
                        </h5>
                        <br>

                        <div class="row" style="margin-top: 5%;">
                            <div class="col-12" id="btn-clientes">
                                <button type="button" class="btn btn-dark" id="btn-formopciones" onclick="DB_CargarListaClientes('cti');"><span class="fas fa-user" style="font-size: 25px;"></span><span style="font-size: 16px;"><br>Seleccionar cliente</span></button>

                                <button type="button" class="btn btn-dark" id="btn-formopciones-hide" style="display: none;">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                <span style="font-size: 12px;"><br>Catálogo</span></button>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5%;">
                            <div class="col-12">
                                <div class="titulo"><span class=""></span>Nombre cliente:</div>
                                <input type="text" name="txtclientesinventario" id="txtclientesinventario" class="form-control">
                                <input type="hidden" name="txtIdClienteCti" id="txtIdClienteCti">
                            </div>
                        </div>
                        <!-- CUADRO DE AVISO -->
                        <div id="InfoCuadro" style="margin-top:30px;">
                            <div class="alert alert-info" role="alert" style="text-align: center;margin-top: 10px;">
                                <h4 class="alert-heading">Aviso!</h4>
                                <p>Por favor seleccione un CLIENTE del <b>catálogo</b>.</p>                                
                            </div>
                        </div>
                        <!-- FIN CUADRO DE AVISO -->
                        <br>
                       <div class="row" style="margin-top: 5%;">
                            <!-- <div class="col-6">
                                <button type="button" class="btn btn-primary btn-block" onclick="" id="btn_cti_anterior" style="font-size:14px;"><span class="fas fa-history fa-lg" style="font-size: 25px;"></span><br>Inventario anterior</button>
                            </div>                             -->
                            <div class="col-12">
                                 <button type="button" id="btn_consulta_cti" class="btn btn-primary" style="font-size:14px;"><i class="fas fa-search-plus fa-lg" style="font-size: 25px;"></i><br>Tomar inventario
                                </button>
                            </div>
                        </div>

                    </div>
                <!-- </form> -->
           </div>
            <!-- FIN SELECCION CLIENTE -->
        </div>
    </div>
<!-- FIN CONTENEDOR -->

<!-- MODAL LISTA DE CLIENTES CTI-->
    <div class="modal fullscreen-modal" id="modalClientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header d_arriba">
                <span class="modal-title" style="margin-top:-7px;">LISTA DE CLIENTES</span>
                <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
                </div>
                <div class="modal-body">
                <div class="row" style="margin-top: 7px;">            
                    
                    <div class="col-8" style="background-color:;">     
                        <select id='dias_busqueda' class='form-control' style="">
                            <option value=''>TODOS LOS DIAS</option>
                            <option value='LUNES'>LUNES</option>
                            <option value='MARTES'>MARTES</option>
                            <option value='MIERCOLES'>MIERCOLES</option>
                            <option value='JUEVES'>JUEVES</option>
                            <option value='VIERNES'>VIERNES</option>
                            <option value='SABADO'>SABADO</option>
                            <option value='DOMINGO'>DOMINGO</option>
                        </select>
                    </div>

                </div>
                    <div class="table-responsive">
                        <table id="clientesDtable" class="table table-bordered" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Ruta</th>
                                    <th scope="col">Días visita</th>
                                    <th scope="col">Division</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="showDataCli">                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Ruta</th>
                                    <th scope="col">Días visita</th>
                                    <th scope="col">Division</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer d_abajo">
                </div>
            </div>
        </div>
    </div>
<!-- FIN LISTA DE CLIENTES CTI -->
<!-- MODAL CONTROL DE INVENTARIO -->
    <div class="modal fade-scale" id="m-control-inventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d_arriba">
                    <span class="modal-title" style="margin-top:-7px;">CONTROL DE INVENTARIO</span>
                    <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
                </div>
                <div class="modal-body">
                    <div id="C_pregInvAnt">
                        <h4 id="titulo_inv">QUIERES CARGAR EL INVENTARIO ANTERIOR ?</h4>
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn_invC" id="Si_InvAntes">SI</button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-danger btn_invC" id="No_InvAntes">NO</button>
                                </div>
                            </div>
                        </div>
                        <img id="cliboraddd" src="<?php echo base_url('dependencias/imagenes/cliborad.png')?>">
                    </div>
                    <div id="Content_Inv" style = "display:none;">
                        <form id="form-control-inventario">
                            <input type="hidden" id="txtCodigoCti">
                            <input type="hidden" id="txtProductoCti">
                            <input type="hidden" id="txtLatitudCti" name="txtLatitudCti">
                            <input type="hidden" id="txtLongitudCti" name="txtLongitudCti">
                            <div class="col-md-12 celda">
                                <div class="form-group">
                                    <button type="button" class="btn btn-warning btn-block" id="btn-forproductos" onclick="DB_CargarFiltrosReclamos('cti');" style="margin-top:3%;"><span class="fas fa-search-plus fa-lg" style="font-size: 25px;"></span><span style="font-size: 16px;"><br> <b>SELECCIONAR PRODUCTO</b></span></button>
                                </div>  
                            </div> 
                        </form>
                        <div id="items_control_inventario">
                            <form id="frm_cti_items">
                                <div id="accordion_cti">
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-info" id="btn_guardar_cti" onclick="validar_frm_cti_items()" >Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
<!-- FIN MODAL CONTROL DE INVENTARIO -->