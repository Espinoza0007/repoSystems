<!--ESTILOS CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_actualizacion_clte.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">
<!--JAVASCRIPTS JS-->
    <script src="<?php echo base_url('dependencias/js/DB_recuperacionDatos.js') ?>"></script>
    <script src="<?php echo base_url('dependencias/js/JS_recuperacionDatos.js') ?>"></script>
    <script src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script> 
    <script src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>

        <script src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script> 
    <script src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>

<style>
  #ord_l,#ord_m,#ord_i,#ord_j,#ord_v,#ord_s,#ord_d { display:none; }

/* usa el mismo estilo para ambos nombres de clase */
.switch_estilo { /* alias de .switch_estilod */
  position: relative;
  width: 80px; height: 40px;
  -webkit-appearance:none; -moz-appearance:none; outline: none;
  background-color: #D0CFCF; border-radius: 20px;
  box-shadow: inset 0 0 5px rgba(0,0,0,.2); transition: .5s;
}
.switch_estilo:checked{ background-color:#28A745; }
.switch_estilo:before{
  content:''; position:absolute; width:40px; height:40px; border-radius:29px;
  top:0; left:0; background:#fff; transform:scale(1.1);
  box-shadow:0 2px 5px rgba(0,0,0,.2); transition:.5s;
}
.switch_estilo:checked:before{ left:40px; }


 

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
        border-radius:7px;
        font-size:17px;
        font-weight:600;padding: 10px;
        border:1px solid #DAD64C;
    }
    .row div textarea{
		width: 100%;
		height: auto;
	}

    .row div .texareas{
		width: 100%;
		height: 120px;
	}

    .vya{
        color:#3F3F3F;
        margin-right:3px;
        text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
    }
    .separador {background-color:#727070;width: 100%;}
    /*SWITCH*/
    .switch_estilod{
        position: relative;
        width: 80px;
        height: 40px;
        -webkit-appearance:none;
        -moz-appearance:none;
        outline: none;
        background-color: #D0CFCF;
        border-radius: 20px;
        box-shadow: inset 0 0 5px rgba(0,0,0,.2);
        transition: .5s;
    }
    .switch_estilod:checked{
        background-color: #28A745;
    }
    .switch_estilod:before{
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        border-radius: 29px;
        top:0;
        left: 0;
        background-color: #fff;
        transform: scale(1.1);
        box-shadow: 0 2px 5px rgba(0,0,0,.2);
        transition: .5s;
    }
    .switch_estilod:checked:before{
        left: 40px;
    }
    #snackbar {
        visibility: hidden;
        min-width: 250px;
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 16px;
        position: fixed;
        z-index: 9999;
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
<div id="snackbar">Hay disponible una nueva versión. <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a></div>
<!--000000---DIV CARGANDO---000000-->
<div id="content-carga" style="display:none;" class="carga-class">
    <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
        <span class="sr-only">Cargando...</span>
        </div>
    </div>
</div>
<div id="notificacion_ac" style="margin-top:70px;position: absolute;width: 100%;height: auto;display: none;">
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
    <div style="" class="icons_posicion" id="btn-menu-back">
        <span class="fa fa-bars fa-2x" style=""></span>
    </div>
</nav>
<div class="container" id="contenedor-principal" style="margin-top:50px;">
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
        <form id="form_actualizacion">
            <div class="row contenedor">
                <div class="col-md-12 celda">
                    <h5 class="card-header card-header-form">
                        Actualizaci&oacute;n de Clientes
                    </h5>
                    <br>
                    <div class="row">
                        <div class="col-12" id="btn-clientes">
                            <button type="button" class="btn btn-dark" id="btn-formopciones" onclick="DB_CargarFiltrosTodos_VER();"><span class="fa fa-database" style="font-size: 25px;"></span><span style="font-size: 12px;"><br>Clientes</span></button>
                            <button type="button" class="btn btn-dark" id="btn-formopciones-hide" style="display: none;">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            <span style="font-size: 12px;"><br>Clientes</span></button>
                        </div>
                    </div>
                    <!--******************************************************************************-->
                    <!--*************************INFORMACION DEL CLIENTE******************************-->
                    <!--******************************************************************************-->
                    <div id="InfoCuadro" style="margin-top:30px;">
                        <div class="alert alert-info" role="alert" style="text-align: center;margin-top: 10px;">
                            <h4 class="alert-heading">Aviso!</h4>
                            <p>Por favor selecciona un Cliente.</p>
                            <hr>
                            <p class="mb-0">
                                <span class="fas fa-database fa-3x"></span> <br>Clientes
                            </p>
                        </div>
                    </div>
                    <div id="form_actuinfo" style="display: none;">
                        <div class="row">
                            <div class="col-md-12 divrow" style="text-align: center;">
                                <strong>CODIGO: <span class="badge badge-dark" id="lblcodcli">0000000</span></strong>
                            </div>
                        </div>
                        <div id="conten_Si_No" class="nada">
                            <fieldset class="col-md-12">
                                <legend>Información General</legend>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-signature fa-lg"></span> Nombre:</div>
                                        <textarea class="form-control outlinenone" name="txtnombre" id="txtnombre"></textarea>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-directions fa-lg"></span> Direcci&oacute;n Comercial <span style="color:red;font-weight:bold;">* </span>:</div>
                                        <textarea class="form-control outlinenone" name="txtdireccion" id="txtdireccion" readonly="readonly"></textarea>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-1"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-id-badge fa-lg"></span> Contacto:</div>
                                        <textarea class="form-control outlinenone" name="txtcontacto" id="txtcontacto" readonly="readonly"></textarea>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-2"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-phone-square-alt fa-lg"></span> Tel&eacute;fono <span style="color:red;font-weight:bold;">* </span>:</div>
                                        <input type="tel" class="form-control outlinenone" name="txtnumtelefono" id="txtnumtelefono" value="" readonly="readonly">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-3"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-envelope fa-lg"></span> Correo electrónico <span style="color:red;font-weight:bold;">* </span>:
                                            <div class="alert alert-info" role="alert">
                                                para recibir factura electrónica:
                                            </div>
                                        </div>
                                        <input type="email" class="form-control outlinenone" name="txtcorreo" id="txtcorreo" value="" readonly="readonly">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-4"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-user-tie fa-lg"></span> Personería <span style="color:red;font-weight:bold;">* </span>:</div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input GR_CheckRadio" type="radio" name="rdpersoneria" id="rdpersoneria1" value="1">
                                            <label class="custom-control-label" for="rdpersoneria1">
                                            Persona Natural
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input GR_CheckRadio" type="radio" name="rdpersoneria" id="rdpersoneria2" value="2">
                                            <label class="custom-control-label" for="rdpersoneria2">
                                            Persona Jurídica
                                            </label>
                                        </div>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-5"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="if_cinva" style="display:none;">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-money-bill-wave fa-lg"></span> Contribuyente de iva <span style="color:red;font-weight:bold;">* </span>:</div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input GR_CheckRadioC" type="radio" name="rdcontribuyente" id="rdcontribuyente1" value="1">
                                            <label class="custom-control-label" for="rdcontribuyente1">
                                            si
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input GR_CheckRadioC" type="radio" name="rdcontribuyente" id="rdcontribuyente2" value="0">
                                            <label class="custom-control-label" for="rdcontribuyente2">
                                            no
                                            </label>
                                        </div>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-6"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="div_dui" style="display: none;">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-id-card fa-lg"></span> DUI <span style="color:red;font-weight:bold;">* </span>:</div>
                                        <input type="tel" id="txtdui" maxlength="15" name="txtdui" class="form-control outlinenone duis" readonly="readonly">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-7"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="div_nit">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-id-card-alt fa-lg"></span> NIT (Opcional):</div>
                                        <input type="tel" id="txtnit" maxlength="17" name="txtnit" class="form-control outlinenone" readonly="readonly">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-20"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="div_numregistro" style="display: none;">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo">N&uacute;mero de registro de contribuyente <span style="color:red;font-weight:bold;">* </span>:</div>
                                        <input type="text" class="form-control outlinenone" name="txtnumcontribuyente" id="txtnumcontribuyente" value="0" readonly="readonly">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-8"> 
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="col-md-12" id="fotosAnexos">
                                <legend>Documentos (DUI,NIT,NRC)</legend>
                                <div id="foto_nrc" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="titulo"><span class="fa fa-camera fa-lg"></span> Foto Frontal NRC <span style="color:red;font-weight:bold;">* </span>:</div>
                                            <div class="custom-file">
                                                <input id="file1" name="filefotos" class="custom-file-input" lang="es" type="file" accept="image/*" disabled="disabled">
                                                <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Foto NRC</label>
                                                <div class="valid-feedback">
                                                    <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjs-9">
                                                    <strong>Por favor toma una foto!</strong>
                                                </div>
                                            </div>
                                            <div class="contenedorFotos">
                                                <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="titulo"><span class="fa fa-camera fa-lg"></span> Foto Trasera NRC <span style="color:red;font-weight:bold;">* </span>:</div>
                                            <div class="custom-file">
                                                <input id="file5" name="filefotos" class="custom-file-input" lang="es" type="file" accept="image/*" disabled="disabled">
                                                <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Foto NRC</label>
                                                <div class="valid-feedback">
                                                    <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjs-21">
                                                    <strong>Por favor toma una foto!</strong>
                                                </div>
                                            </div>
                                            <div class="contenedorFotos">
                                                <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="foto_dui" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-camera fa-lg"></span> Foto Frontal DUI <span style="color:red;font-weight:bold;">* </span>:</div>
                                            <div class="custom-file">
                                                <input id="file2" name="filefotos" class="custom-file-input" lang="es" type="file" accept="image/*" disabled="disabled">
                                                <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Foto DUI</label>
                                            <div class="valid-feedback">
                                                <strong></strong>
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-10">
                                                <strong>Por favor toma una foto!</strong>
                                            </div>
                                            </div>
                                            <div class="contenedorFotos">
                                                <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen2"> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-camera fa-lg"></span> Foto Trasera DUI <span style="color:red;font-weight:bold;">* </span>:</div>
                                            <div class="custom-file">
                                                <input id="file3" name="filefotos" class="custom-file-input" lang="es" type="file" accept="image/*" disabled="disabled">
                                                <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Foto DUI</label>
                                            <div class="valid-feedback">
                                                <strong></strong>
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-18">
                                                <strong>Por favor toma una foto!</strong>
                                            </div>
                                            </div>
                                            <div class="contenedorFotos">
                                                <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen3"> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-camera fa-lg"></span> Foto Frontal NIT (Opcional):</div>
                                        <div class="custom-file">
                                            <input id="file4" name="filefotos" class="custom-file-input" lang="es" type="file" accept="image/*" disabled="disabled">
                                            <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Foto NIT</label>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-19">
                                            <strong>Por favor toma una foto!</strong>
                                        </div>
                                        </div>
                                        <div class="contenedorFotos">
                                            <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="imagen4"> 
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="col-md-12">
                                <legend>Información de persona que recibira documentación</legend>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-question fa-lg"></span> El contacto es el mismo <span style="color:red;font-weight:bold;">* </span>:</div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input GR_CheckRadioEC" type="radio" name="rdpreguntacontacto" id="rdpreguntacontacto1" value="1">
                                            <label class="custom-control-label" for="rdpreguntacontacto1">
                                            SI
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input GR_CheckRadioEC" type="radio" name="rdpreguntacontacto" id="rdpreguntacontacto2" value="0">
                                            <label class="custom-control-label" for="rdpreguntacontacto2">
                                            NO
                                            </label>
                                        </div>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-22"> 
                                        </div>
                                    </div>
                                </div>
                                <div id="info_contacto" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-signature fa-lg"></span> Nombre <span style="color:red;font-weight:bold;">* </span>:</div>
                                            <input type="text" class="form-control outlinenone" name="txtnombreC" id="txtnombreC" value="">
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-12"> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-phone-square-alt fa-lg"></span> Tel&eacute;fono <span style="color:red;font-weight:bold;">* </span>:</div>
                                            <input type="tel" class="form-control outlinenone telefonos" name="txtnumtelefonoC" id="txtnumtelefonoC" value="">
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-13">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="div_dui">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-id-card fa-lg"></span> DUI <span style="color:red;font-weight:bold;">* </span>:</div>
                                            <input type="tel" id="txtduiC" maxlength="15" name="txtduiC" class="form-control outlinenone duis" >
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-14"> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div id="if-representante" style="display:none;">
                                <fieldset class="col-md-12">
                                    <legend>Representante Legal</legend>
                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-signature fa-lg"></span> Nombre:</div>
                                            <input type="text" class="form-control outlinenone" name="txtnombreR" id="txtnombreR" value="">
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-15"> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo"><span class="fa fa-phone-square-alt fa-lg"></span> Tel&eacute;fono:</div>
                                            <input type="tel" class="form-control outlinenone telefonos" name="txtnumtelefonoR" id="txtnumtelefonoR" value="">
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-16">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="div_dui">
                                        <div class="col-md-12 divrow">
                                            <div class="titulo docidentidad"></div>
                                            <input type="tel" id="txtduiR" maxlength="15" name="txtduiR" class="form-control outlinenone duis">
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback" id="error-mjs-17"> 
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                               <!-- ===================== FRECUENCIA + DÍA DE VISITA ===================== -->
<div id="bloque-frecuencia-dias" class="form-group" style="margin-top:15px;">

  <!-- Frecuencia de visita -->
  <div class="form-group">
    <label for="cbfrecuenciavisita">
      <span class="fa fa-question fa-lg"></span> Frecuencia De Visita:
    </label>
    <select class="form-control custom-select" id="cbfrecuenciavisita" name="cbfrecuenciavisita" required>
      <option value="">SELECCIONE UNA OPCIÓN</option>
      <option value="1,2,3,4,5">SEMANAL</option>
      <option value="1,3,5">QUINCENAL 1,3,5</option>
      <option value="2,4">QUINCENAL 2,4</option>
      <option value="1">MENSUAL S1</option>
      <option value="2">MENSUAL S2</option>
      <option value="3">MENSUAL S3</option>
      <option value="4">MENSUAL S4</option>
    </select>
    <div class="valid-feedback"><strong></strong></div>
    <div class="invalid-feedback" id="error-mjs-17">
      <strong>Por favor selecciona una opción de la lista!</strong>
    </div>
  </div>

  <!-- Días de visita -->
  <div class="form-group">
    <label>
      <span class="fa fa-calendar-day fa-lg"></span> DÍA DE VISITA:
    </label>

    <!-- Encabezado general: aparece si hay al menos un día seleccionado -->
    <div id="orden_visita_header" style="display:none; font-weight:600; margin:10px 0 6px;">
      ORDEN DE VISITA
    </div>

    <!-- Lunes -->
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input GR_Check" id="checklunes" name="checkdiavisita[]" value="L_1">
      <label class="custom-control-label" for="checklunes">LUNES</label>
    </div>
    <div id="ord_l" style="margin-top:7px;display:none;">
      <label><strong>ORDEN DE VISITA LUNES:</strong></label>
      <input type="tel" name="txtordenvisital" id="txtordenvisital" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
      <div class="invalid-feedback" id="error-mjs-31"></div>
      <hr class="separador">
    </div>

    <!-- Martes -->
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input GR_Check" id="checkmartes" name="checkdiavisita[]" value="M_1">
      <label class="custom-control-label" for="checkmartes">MARTES</label>
    </div>
    <div id="ord_m" style="margin-top:7px;display:none;">
      <label><strong>ORDEN DE VISITA MARTES:</strong></label>
      <input type="tel" name="txtordenvisitam" id="txtordenvisitam" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
      <div class="invalid-feedback" id="error-mjs-32"></div>
      <hr class="separador">
    </div>

    <!-- Miércoles -->
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input GR_Check" id="checkmiercoles" name="checkdiavisita[]" value="I_1">
      <label class="custom-control-label" for="checkmiercoles">MIÉRCOLES</label>
    </div>
    <div id="ord_i" style="margin-top:7px;display:none;">
      <label><strong>ORDEN DE VISITA MIÉRCOLES:</strong></label>
      <input type="tel" name="txtordenvisitai" id="txtordenvisitai" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
      <div class="invalid-feedback" id="error-mjs-33"></div>
      <hr class="separador">
    </div>

    <!-- Jueves -->
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input GR_Check" id="checkjueves" name="checkdiavisita[]" value="J_1">
      <label class="custom-control-label" for="checkjueves">JUEVES</label>
    </div>
    <div id="ord_j" style="margin-top:7px;display:none;">
      <label><strong>ORDEN DE VISITA JUEVES:</strong></label>
      <input type="tel" name="txtordenvisitaj" id="txtordenvisitaj" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
      <div class="invalid-feedback" id="error-mjs-34"></div>
      <hr class="separador">
    </div>

    <!-- Viernes -->
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input GR_Check" id="checkviernes" name="checkdiavisita[]" value="V_1">
      <label class="custom-control-label" for="checkviernes">VIERNES</label>
    </div>
    <div id="ord_v" style="margin-top:7px;display:none;">
      <label><strong>ORDEN DE VISITA VIERNES:</strong></label>
      <input type="tel" name="txtordenvisitav" id="txtordenvisitav" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
      <div class="invalid-feedback" id="error-mjs-35"></div>
      <hr class="separador">
    </div>

    <!-- Sábado -->
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input GR_Check" id="checksabado" name="checkdiavisita[]" value="S_1">
      <label class="custom-control-label" for="checksabado">SÁBADO</label>
    </div>
    <div id="ord_s" style="margin-top:7px;display:none;">
      <label><strong>ORDEN DE VISITA SÁBADO:</strong></label>
      <input type="tel" name="txtordenvisitas" id="txtordenvisitas" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
      <div class="invalid-feedback" id="error-mjs-36"></div>
      <hr class="separador">
    </div>

    <!-- Domingo -->
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input GR_Check" id="checkdomingo" name="checkdiavisita[]" value="D_1">
      <label class="custom-control-label" for="checkdomingo">DOMINGO</label>
    </div>
    <div id="ord_d" style="margin-top:7px;display:none;">
      <label><strong>ORDEN DE VISITA DOMINGO:</strong></label>
      <input type="tel" name="txtordenvisitad" id="txtordenvisitad" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
      <div class="invalid-feedback" id="error-mjs-37"></div>
      <hr class="separador">
    </div>

    <!-- checkbox oculto opcional de compatibilidad -->
    <input type="checkbox" style="display:none;" class="custom-control-input GR_Check" id="checkvalidate" value="">
  </div>
</div>
<!-- =================== /FRECUENCIA + DÍA DE VISITA =================== -->



                            
                            <fieldset class="col-md-12">
                                <legend><span class="fa fa-comment-dots fa-lg"></span> Observaciones :</legend>
                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <textarea class="form-control outlinenone texareas" name="txtobservacion" id="txtobservacion" maxlength="200"></textarea>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-23"> 
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>


                                <input type="checkbox" id="switch_estado" name="switch_estado" checked style="display:none;">

                                                        <!-- ================= WIDGET COORDENADAS REUTILIZABLE ================= -->

                                <a href="#anclacoord" id="anclacoord"></a>
                                <div class="row_especial">
                                <div class="col-md-12 divrow">
                                    <div class="titulo_espcial">¿ QUIERES ACTUALIZAR LA UBICACIÓN ?</div>
                                    <input class="switch_estilo" id="switch-two" type="checkbox">
                                </div>
                                </div>

                                <div id="Div_Coordendads" style="display: none;">
                                <div class="row" id="btn_obt_coordendas">
                                    <div class="col-md-12 divrow">
                                    <center>
                                        <button type="button" class="btn btn-info carga-esconder" style="font-size: 15px;width: 100%;" id="btncoordenadas" name="btncoordenadas" onclick="consultar_coordenadas();">
                                        <strong><span class="fa fa-map-marker-alt"></span> OBTENER COORDENADAS</strong>
                                        </button>
                                        <button type="button" class="btn btn-info carga-class" style="display:none;font-size: 15px;width: 100%;" id="btncoordenadas-hide" name="btncoordenadas-hide">
                                        <strong><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> OBTENIENDO COORDENADAS</strong>
                                        </button>
                                    </center>
                                    </div>
                                </div>

                                <div class="row" id="content-map">
                                    <div class="col-md-12 divrow">
                                    <div id="map" style="height: 277px;width: 100%;"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                    <div class="titulo"><span class="fa fa-compass fa-lg"></span> Latitud:</div>
                                    <input type="tel" id="txtlatitudm" class="form-control outlinenone" placeholder="Latitud..." readonly="readonly" style="background-color: #fff;">
                                    <input type="hidden" id="txtlatitud" class="form-control" name="txtlatitudAC" placeholder="Latitud...">
                                    <div class="valid-feedback"><strong></strong></div>
                                    <div class="invalid-feedback" id="error-mjs-12"></div>
                                    </div>
                                    <div class="col-6">
                                    <div class="titulo"><span class="fa fa-compass fa-lg"></span> Longitud:</div>
                                    <input type="tel" id="txtlongitudm" class="form-control outlinenone" placeholder="Longitud..." readonly="readonly" style="background-color: #fff;">
                                    <input type="hidden" id="txtlongitud" class="form-control" name="txtlongitudAC" placeholder="Longitud...">
                                    <div class="valid-feedback"><strong></strong></div>
                                    <div class="invalid-feedback" id="error-mjs-13"></div>
                                    </div>
                                </div>
                                </div>



<!-- =============== /WIDGET COORDENADAS REUTILIZABLE ================== -->




                        <div class="row">
                            <div class="col-md-12 divrow">
                                <div class="textoSeleccion"><br>
                                    <button type="button" id="btn-enviar" class="btn btn-primary carga-esconder" onclick="enviar_actualizacion()"><span class="fas fa-paper-plane fa-lg" ></span> Enviar Actualización
                                    </button>
                                    <button type="button" class="btn btn-primary carga-class" style="display: none;"><span class="fas fa-paper-plane fa-spin fa-lg"></span> Enviando Actualización
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div><!--FINAL INFO CLENTES-->
                </div>
            </div>
        </form>
    </div>
</div>
<!-- MODAL VER CLIENTES SIN ACTUALIZAR-->
<div class="modal fullscreen-modal" id="ModalCliSN" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header d_arriba">
                <span class="modal-title" style="margin-top:-7px;">LISTA DE CLIENTES</span>
                <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-top: 7px;">
                    <div class="col-6" style="background-color:;">     
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
                    <div class="col-6" style="background-color:;"> 
                        <select id='switch_ESAC' class='form-control' style="">
                            <option value='VERDES'>ACTIVOS</option>
                            <option value='ROJOS'>INACTIVOS</option>
                            <option value='TDOS'>TODOS</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="DgrTableSN" class="table table-bordered" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">C&oacute;digo</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Direccion</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Tel&eacute;fono</th>
                                <th scope="col">Día</th>
                                <th scope="col">Ultima Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="showDataSN">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col">C&oacute;digo</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Direccion</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Tel&eacute;fono</th>
                                <th scope="col">Día</th>
                                <th scope="col">Ultima Fecha</th>
                            </tr>
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
<script>
    $("#fotosAnexos").on('change', '.custom-file-input', function() {
        var img_id = $(this).attr("id"),id_Org = 0;
        img_id = img_id.substring(4,img_id.length);id_Org = parseInt(img_id);
        $("#file"+img_id).removeClass("is-invalid").addClass("is-valid");
        $("#error-mjsf-" + img_id).html('');
        img_id= "imagen"+img_id;
        var FotoExhibidor = '';
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


<script>
/* === Requiere Leaflet y jQuery === */

// FLAGS/estado como antes
window.EstadoCoordenadas = window.EstadoCoordenadas || 0;   // 0 = no hay nuevas, 1 = hay nuevas
window.coordenadas_Tempo = window.coordenadas_Tempo || [ '', '' ]; // [lat, lng] previas

(function () {
  let map = null;
  let marker = null;
  let mapInited = false;

  // Mostrar/Ocultar contenedor al cambiar el switch (mismo id)
  $(document).on('change', '#switch-two', function(){
    if (this.checked) {
      $('#Div_Coordendads').slideDown(150, function(){
        if (map) setTimeout(() => map.invalidateSize(), 120);
      });
    } else {
      $('#Div_Coordendads').slideUp(150);
    }
  });

  // Botón principal (mismo id y misma función que llamabas)
  window.consultar_coordenadas = function consultar_coordenadas() {
    // UI loading ON
    $('#btncoordenadas').hide();
    $('#btncoordenadas-hide').show();

    // Asegurar que el contenedor esté visible
    if (!$('#switch-two').is(':checked')) {
      $('#switch-two').prop('checked', true).trigger('change');
    }

    // Init mapa si no existe
    if (!mapInited) {
      map = L.map('map').setView([13.6929, -89.2182], 15); // San Salvador aprox
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      // Click en mapa para fijar punto
      map.on('click', function(e){
        setPoint(e.latlng.lat, e.latlng.lng, true);
      });

      mapInited = true;
    } else {
      setTimeout(() => map.invalidateSize(), 120);
    }

    // Intentar geolocalizar primero (como antes hacía el botón)
    if (!navigator.geolocation) {
      // Si no hay geolocalización, solo dejamos el mapa listo para click
      finishUI();
      return;
    }
    if (location.protocol !== 'https:') {
      // En HTTP los navegadores pueden negar/ser imprecisos; no bloqueamos
      // Solo avisamos si tenés SweetAlert
      if (window.Swal) Swal.fire({icon:'info', title:'Aviso', text:'Para mejor precisión, usá HTTPS.'});
    }

    navigator.geolocation.getCurrentPosition(
      function(pos){
        const { latitude, longitude } = pos.coords;
        setPoint(latitude, longitude, true);
        finishUI();
      },
      function(err){
        // Si falla, igual dejamos el mapa listo para click
        finishUI();
      },
      { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
  };

  function setPoint(lat, lng, centerMap) {
    if (isNaN(lat) || isNaN(lng)) return;

    // Normalizar a 6 decimales
    const latF = Number(lat).toFixed(6);
    const lngF = Number(lng).toFixed(6);

    // Inputs visibles/ocultos con los MISMOS IDs que usabas
    $('#txtlatitudm').val(latF);
    $('#txtlongitudm').val(lngF);
    $('#txtlatitud').val(latF);
    $('#txtlongitud').val(lngF);

    // Actualizar banderas/temporales exactamente como tu flujo espera
    window.EstadoCoordenadas = 1;
    window.coordenadas_Tempo = [ latF, lngF ];

    // Dibujar/actualizar marcador
    if (map) {
      if (!marker) {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on('dragend', function(){
          const p = marker.getLatLng();
          setPoint(p.lat, p.lng, false);
        });
      } else {
        marker.setLatLng([lat, lng]);
      }
      if (centerMap) map.setView([lat, lng], 16);
    }
  }

  function finishUI() {
    // UI loading OFF
    $('#btncoordenadas-hide').hide();
    $('#btncoordenadas').show();
  }
})();
</script>

<script>
/* ================== FRECUENCIA (independiente) + DÍAS DE VISITA ================== */
/* Requiere jQuery. IDs esperados:
   #cbfrecuenciavisita,
   checkboxes de días .GR_Check con ids:
     #checklunes/#checkmartes/#checkmiercoles/#checkjueves/#checkviernes/#checksabado/#checkdomingo
   contenedores de orden:
     #ord_l #ord_m #ord_i #ord_j #ord_v #ord_s #ord_d
   inputs de orden:
     #txtordenvisital/#txtordenvisitam/#txtordenvisitai/#txtordenvisitaj/#txtordenvisitav/#txtordenvisitas/#txtordenvisitad
*/

(function(){
  // --- Config por día
  const DIA_CFG = {
    checklunes     : { div: '#ord_l', input: '#txtordenvisital', label: 'Lunes'     },
    checkmartes    : { div: '#ord_m', input: '#txtordenvisitam', label: 'Martes'    },
    checkmiercoles : { div: '#ord_i', input: '#txtordenvisitai', label: 'Miércoles' },
    checkjueves    : { div: '#ord_j', input: '#txtordenvisitaj', label: 'Jueves'    },
    checkviernes   : { div: '#ord_v', input: '#txtordenvisitav', label: 'Viernes'   },
    checksabado    : { div: '#ord_s', input: '#txtordenvisitas', label: 'Sábado'    },
    checkdomingo   : { div: '#ord_d', input: '#txtordenvisitad', label: 'Domingo'   }
  };

  // --- Header "ORDEN DE VISITA" (visible solo si hay al menos un día marcado)
  function ensureOrdenHeader() {
    if (!$('#orden_visita_header').length) {
      const firstDiv = DIA_CFG.checklunes?.div || '#ord_l';
      if ($(firstDiv).length) {
        $(firstDiv).before(
          '<div id="orden_visita_header" style="display:none;font-weight:600;margin:10px 0 6px;">ORDEN DE VISITA</div>'
        );
      }
    }
  }
  function toggleOrdenHeader() {
    const anyChecked = Object.keys(DIA_CFG).some(id => $('#'+id).is(':checked'));
    $('#orden_visita_header').toggle(anyChecked);
  }

  // --- Mostrar/ocultar orden al marcar un día


  function toggleOrdenDia(id) {
  const cfg = DIA_CFG[id];
  if (!cfg) return;
  const checked = $('#'+id).is(':checked');

  if (checked) {
    const $label = $(cfg.div).find('label').first();
    if ($label.length) $label.text('Orden De Visita ' + (cfg.label || '').toUpperCase() + ':');
    $(cfg.div).slideDown(120);
    $(cfg.input).attr('required', true);
    if (!$(cfg.input).val()) $(cfg.input).val('0');
  } else {
    $(cfg.div).slideUp(120);
    $(cfg.input).removeAttr('required').val('');
  }
}


  // ====== EVENTOS ======

  // 1) La FRECUENCIA ahora es independiente: NO toca los días.
  $(document).on('change', '#cbfrecuenciavisita', function(){
    // Si querés limpiar los días al cambiar frecuencia, descomenta:
    // Object.keys(DIA_CFG).forEach(id => { $('#'+id).prop('checked', false); toggleOrdenDia(id); });
    // toggleOrdenHeader();
    // Por ahora: no hace nada sobre los días.
  });

  // 2) Al marcar/desmarcar un día
  $(document).on('change', '.GR_Check', function(){
    const id = this.id;
    if (DIA_CFG[id]) {
      toggleOrdenDia(id);
      toggleOrdenHeader();
    }
  });

  // 3) Validación simple 0..90 y solo dígitos en órdenes
  const ORDER_INPUTS = [
    '#txtordenvisital','#txtordenvisitam','#txtordenvisitai',
    '#txtordenvisitaj','#txtordenvisitav','#txtordenvisitas','#txtordenvisitad'
  ];
  $(document).on('input', ORDER_INPUTS.join(','), function(){
    let v = this.value.replace(/[^\d]/g, '');
    if (v === '') { this.value = ''; return; }
    let n = parseInt(v, 10);
    if (isNaN(n)) n = 0;
    if (n > 90) n = 90;
    this.value = String(n);
  });

  // Utilidad por si necesitás leer lo elegido al enviar
  window.getDiasVisitaSeleccionados = function(){
    const dias = {};
    Object.keys(DIA_CFG).forEach(id=>{
      if ($('#'+id).is(':checked')) {
        const cfg = DIA_CFG[id];
        dias[id] = { orden: $(cfg.input).val() || '0' };
      }
    });
    return dias;
  };

  // Init
  $(function(){
    ensureOrdenHeader();
    Object.keys(DIA_CFG).forEach(toggleOrdenDia); // Aplica estado actual (modo edición)
    toggleOrdenHeader();
  });
})();
</script>

<script>
/* =============================================================
   AJUSTE SOLICITADO - ACTUALIZACIÓN DE CLIENTES
   - No exigir txtnumcontribuyente ni archivos file1-file5.
   - Bloquear edición de contacto, teléfono, correo, DUI y NRC.
   ============================================================= */
(function(){
    function limpiarEstadoCampo(selector, errorSelector){
        var $campo = $(selector);
        $campo.removeClass('is-invalid').addClass('is-valid');
        if(errorSelector){
            $(errorSelector).html('');
        }
    }

    window.prepararFormularioActualizacionCliente = function(){
        // Bloquear campos que NO deben editarse
        $('#txtcontacto').prop('readonly', true);
        $('#txtnumtelefono').prop('readonly', true);
        $('#txtcorreo').prop('readonly', true);
        $('#txtdui').prop('readonly', true);
        $('#txtnumcontribuyente').prop('readonly', true);

        // Asegurar valor por defecto en NRC para evitar validaciones vacías
        if($.trim($('#txtnumcontribuyente').val()) === ''){
            $('#txtnumcontribuyente').val('0');
        }

        // Quitar obligatoriedad de TODOS los archivos del formulario
        $('#file1, #file2, #file3, #file4, #file5')
            .prop('required', false)
            .prop('disabled', true)
            .removeClass('is-invalid')
            .addClass('is-valid');

        $('input[type="file"][name="filefotos"]')
            .prop('required', false)
            .prop('disabled', true)
            .removeClass('is-invalid')
            .addClass('is-valid');

        // Limpiar mensajes de validación relacionados a NRC/DUI/NIT/FOTOS
        limpiarEstadoCampo('#txtdui', '#error-mjs-7');
        limpiarEstadoCampo('#txtnumcontribuyente', '#error-mjs-8');
        limpiarEstadoCampo('#file1', '#error-mjs-9');
        limpiarEstadoCampo('#file2', '#error-mjs-10');
        limpiarEstadoCampo('#file3', '#error-mjs-18');
        limpiarEstadoCampo('#file4', '#error-mjs-19');
        limpiarEstadoCampo('#file5', '#error-mjs-21');

        $('#error-mjs-7, #error-mjs-8, #error-mjs-9, #error-mjs-10, #error-mjs-18, #error-mjs-19, #error-mjs-21').html('');

        // Compatibilidad por si JS_recuperacionDatos usa arreglos globales de fotos
        if(typeof window.arrg_fotos !== 'undefined'){
            window.arrg_fotos[1] = window.arrg_fotos[1] || '';
            window.arrg_fotos[2] = window.arrg_fotos[2] || '';
            window.arrg_fotos[3] = window.arrg_fotos[3] || '';
            window.arrg_fotos[4] = window.arrg_fotos[4] || '';
            window.arrg_fotos[5] = window.arrg_fotos[5] || '';
        }
    };

    $(document).ready(function(){
        prepararFormularioActualizacionCliente();
    });

    // Antes de enviar, volvemos a aplicar el ajuste por si otro script cambió estados/required.
    $(document).on('click', '#btn-enviar', function(){
        prepararFormularioActualizacionCliente();
    });

    // Envolvemos enviar_actualizacion sin cambiar su lógica interna.
    var intentosWrap = 0;
    var timerWrap = setInterval(function(){
        intentosWrap++;
        if(typeof window.enviar_actualizacion === 'function' && !window.enviar_actualizacion.__ajuste_sin_files){
            var enviarOriginal = window.enviar_actualizacion;
            window.enviar_actualizacion = function(){
                prepararFormularioActualizacionCliente();
                return enviarOriginal.apply(this, arguments);
            };
            window.enviar_actualizacion.__ajuste_sin_files = true;
            clearInterval(timerWrap);
        }
        if(intentosWrap > 20){
            clearInterval(timerWrap);
        }
    }, 250);
})();
</script>

