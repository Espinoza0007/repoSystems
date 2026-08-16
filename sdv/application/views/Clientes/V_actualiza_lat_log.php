<!--ESTILOS CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_actuClientes.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">



<!--JAVASCRIPTS JS-->
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_actualizacionCli.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_actualizacionClientes.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>
  

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
		/* box-shadow: none !important; */
		/* outline: none !important; */
		/* border: 1px solid #BDBDBD !important; */
		/* background-color: #fff !important; */
		/* text-transform: uppercase; */
		/* border-radius: 10px; */
		/* resize: none; */
		/* font-weight: bold; */
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

</style>

</head>
<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">

  <div id="snackbar">Hay disponible una nueva versión. <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a></div>
    
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

<!--                             <div class="col-6" id="btn-historial">
                                <button type="button" class="btn btn-dark" id="btn-formodetalles" onclick="check_conn_todo_cli_AC();"><span class="fa fa-tasks" style="font-size: 25px;"></span><span style="font-size: 12px;text-align: center;"><br>Actualizados</span>
                                </button>

                                <button type="button" class="btn btn-dark" id="btn-formodetalles-hide" style="display: none;">
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    <span style="font-size: 12px;"><br>Historial</span>
                                </button>
                            </div> -->
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


                            <div class="row">
                                <div class="col-md-12 divrow" style="text-align: center;">
                                    <strong>Última Fecha de Actualización: <br><i><span id="lbl_fultima" style="color:#0B6371;"></span></i></strong>
                                </div>
                            </div>


                            
                            <div class="row_especial">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-user fa-lg"></span> Estado del cliente:</div>
                                    <input class="switch_estilo" id="switch_estado" type="checkbox">
                                </div>
                            </div>

                            <div id="cli_inactivado" style="display:none;">

                                <!-- <div class="titulo">Se esta inactivando el registro del cliente, por favor escribe el motivo</div> -->
                                <div class="form-group div_comentario">
                                    <span>
                                        Se esta inactivando el registro del cliente, por favor elige el motivo
                                    </span>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"> <span class="fas fa-file-alt fa-lg"></span> Motivo:</div>
<!--                                         <textarea class="form-control textarea_k" id="motivo_inactivo" name="motivo_inactivo" maxlength="255"></textarea>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-77"> 
                                        </div> -->

                                        <div id="S_filtroMotivosR">
                                            <select class="custom-select" id="motivo_inactivo" name="motivo_inactivo">
                                              <option value="" hidden>Elige una opción...</option>
                                              <option value="1">CERRO OPERACIONES</option>
                                              <option value="2">TRASLADO DE DOMICILIO</option>
                                              <option value='3'>ZONA DE ALTO RIESGO</option>
                                              <option value="4">CODIGO DUPLICADO</option>
                                              <option value="5">NO COMPRA</option>
                                              <option value="6">ES DE OTRO RUTA</option>
                                              <option value="7">NO EXISTE</option>
                                              <option value="8">CAMBIO DE PROPIETARIO</option>
                                              <option value="9">CAMBIO DE PROPIETARIO</option>
                                              <option value="10">CAMBIO DE RAZON SOCIAL</option>
                                            </select>

                                            <div class="valid-feedback">
                                                <strong></strong>
                                            </div>
                                            <div class="invalid-feedback">
                                                <strong> POR FAVOR SELECCIONA UN MOTIVO! </strong>
                                            </div>

                                        </div>


                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-signature fa-lg"></span> Nombre:</div>
                                        <!-- <span id="span_nombre"></span> -->
                                        <input type="text" class="form-control outlinenone" name="span_nombre" id="span_nombre" value="" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-directions fa-lg"></span> Direcci&oacute;n:</div>
                                        <!-- <span id="span_direccion"></span> -->
                                        <textarea class="form-control outlinenone" name="span_direccion" id="span_direccion" value="" readonly></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-id-badge fa-lg"></span> Contacto:</div>
                                        <!-- <span id="span_contacto"></span> -->
                                        <input type="text" class="form-control outlinenone" name="span_contacto" id="span_contacto" value="" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo"><span class="fa fa-phone-square-alt fa-lg"></span> Tel&eacute;fono:</div>
                                        <!-- <span id="span_telefono"></span> -->
                                        <input type="text" class="form-control outlinenone" name="span_telefono" id="span_telefono" value="" readonly>
                                    </div>
                                </div>

                            </div>

                        <div id="conten_Si_No" style="display:none;" class="nada">


                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-signature fa-lg"></span> Nombre:</div>
                                    <input type="text" class="form-control outlinenone" name="txtnombre" id="txtnombre" value="">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-0"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-directions fa-lg"></span> Direcci&oacute;n:</div>
                                    <textarea class="form-control outlinenone" name="txtdireccion" id="txtdireccion" style="width: 100%;height: 90px;"></textarea>
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-1"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Departamento:</div>
                                    <div id="c-departamento">
                                        <select name="cbdepartamento" id="cbdepartamento">
                                            <option value="" selected>0</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="if-departamento">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Municipio:</div>
                                    <div id="c-municipio" class="especial-info">
                                      <select name="cbmunicipio" id="cbmunicipio">
                                        <option value="" selected>0</option>
                                      </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-id-badge fa-lg"></span> Contacto:</div>
                                    <input type="text" class="form-control outlinenone" name="txtcontacto" id="txtcontacto" value="">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-4"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-phone-square-alt fa-lg"></span> Tel&eacute;fono:</div>
                                    <input type="tel" class="form-control outlinenone" name="txtnumtelefono" id="txtnumtelefono" value="">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-5"> 
                                    </div>
                                </div>
                            </div>

                            <div class="titulo"><span class="fa fa-calendar-day fa-lg"></span> D&iacute;a De Visita:</div>
                            <div class="form-group" id="div_diasVisita">
                            <!-- <div class="row" id="div_diasVisita"> -->
                                <!-- <div class="col-md-6" style="width: 50%;"> -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check" id="checklunes" name="checkdiavisita[]" value='L_1'>
                                        <label class="custom-control-label" for="checklunes">LUNES</label>
                                    </div>

                                    <div style="margin-top:7px;display:none;" id="ord_l">
                                        <label>Orden De Visita Lunes:</label>
                                        <input type="number" name="txtordenvisital" id="txtordenvisital" class="form-control" placeholder="Orden de visita..." value="0" min="0" max="90" step="1">
                                        <div class="valid-feedback">
                                        <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-16">
                                        </div>
                                        <!-- <hr style="wid:100%;"> -->
                                        <hr class="separador">
                                    </div>


                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check" id="checkmartes" name="checkdiavisita[]" value='M_1'>
                                        <label class="custom-control-label" for="checkmartes">MARTES</label>
                                    </div>

                                    <div style="margin-top:7px;display:none;" id="ord_m">
                                        <label>Orden De Visita Martes:</label>
                                        <input type="number" name="txtordenvisitam" id="txtordenvisitam" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                                        <div class="valid-feedback">
                                        <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-17">
                                        </div>
                                        <hr class="separador">
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check" id="checkmiercoles" name="checkdiavisita[]" value='I_1'>
                                        <label class="custom-control-label" for="checkmiercoles">MI&Eacute;RCOLES</label>
                                    </div>

                                    <div style="margin-top:7px;display:none;" id="ord_i">
                                        <label>Orden De Visita Miércoles:</label>
                                        <input type="number" name="txtordenvisitai" id="txtordenvisitai" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                                        <div class="valid-feedback">
                                        <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-18">
                                        </div>
                                        <hr class="separador">
                                    </div>


                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check" id="checkjueves" name="checkdiavisita[]" value='J_1'>
                                        <label class="custom-control-label" for="checkjueves">JUEVES</label>
                                    </div>

                                    <div style="margin-top:7px;display:none;" id="ord_j">
                                        <label>Orden De Visita Jueves:</label>
                                        <input type="number" name="txtordenvisitaj" id="txtordenvisitaj" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                                        <div class="valid-feedback">
                                        <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-19">
                                        </div>
                                        <hr class="separador">
                                    </div>

                                <!-- </div> -->
                                <!-- <div class="col-md-6" style="width: 50%;"> -->
                                    <!-- <div class="titulo"></div> -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check" id="checkviernes" name="checkdiavisita[]" value='V_1'>
                                        <label class="custom-control-label" for="checkviernes">VIERNES</label>
                                    </div>

                                    <div style="margin-top:7px;display:none;" id="ord_v">
                                        <label>Orden De Visita Viernes:</label>
                                        <input type="number" name="txtordenvisitav" id="txtordenvisitav" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                                        <div class="valid-feedback">
                                        <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-20">
                                        </div>
                                        <hr class="separador">
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check" id="checksabado" name="checkdiavisita[]" value='S_1'>
                                        <label class="custom-control-label" for="checksabado">SABADO</label>
                                    </div>

                                    <div style="margin-top:7px;display:none;" id="ord_s">
                                        <label>Orden De Visita Sabado:</label>
                                        <input type="number" name="txtordenvisitas" id="txtordenvisitas" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                                        <div class="valid-feedback">
                                        <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-21">
                                        </div>
                                        <hr class="separador">
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check" id="checkdomingo" name="checkdiavisita[]" value='D_1'>
                                        <label class="custom-control-label" for="checkdomingo">DOMINGO</label>
                                    </div>

                                    <div style="margin-top:7px;display:none;" id="ord_d">
                                        <label>Orden De Visita Domingo:</label>
                                        <input type="number" name="txtordenvisitad" id="txtordenvisitad" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                                        <div class="valid-feedback">
                                        <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-22">
                                        </div>
                                        <hr class="separador">
                                    </div>

                                <!-- </div> -->

                                <div class="valid-feedback">
                                  <strong></strong>
                                </div>
                                <div class="invalid-feedback">
                                  <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Tipo de facturaci&oacute;n:</div>
                                    <div id="c-tfacturacion">
                                        <select name="cbtfacturacion" id="cbtfacturacion">
                                            <option value="" selected>0</option>
                                        </select>
                                    </div>
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-14"> 
                                    </div>
                                </div>
                            </div>
                            
                            <div id="if-tfactura" style="display: none;">
                                <div class="row" style="display: none;" id="div_dui">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo" id="docidentidad"></div>
                                        <input type="tel" id="txtdui" maxlength="15" name="txtdui" class="form-control outlinenone">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-7"> 
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="display: none;" id="div_numregistro">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo">N&uacute;mero de registro de contribuyente:</div>
                                        <input type="text" class="form-control outlinenone" name="txtnumcontribuyente" id="txtnumcontribuyente" value="0">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-8"> 
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="display: none;" id="div_nit">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo" id="idtributaria"></div>
                                        <input type="tel" id="txtnit" name="txtnit" maxlength="17" class="form-control outlinenone">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-9"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--FINAL DE TIPO DE FACTURACION DEL CLEINTE-->
                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Frecuencia de visita:</div>
                                        <select class="form-control custom-select outlinenone" id="cbfrecuenciavisita" name="cbfrecuenciavisita">
                                            <option value="">SELECCIONE UNA OPCION</option>
                                            <option value="1,2,3,4,5">SEMANAL</option>
                                            <option value="1,3,5">QUINCENAL 1,3,5</option>
                                            <option value="2,4">QUINCENAL 2,4</option>
                                            <option value="1">MENSUAL S1</option>
                                            <option value="2">MENSUAL S2</option>
                                            <option value="3">MENSUAL S3</option>
                                            <option value="4">MENSUAL S4</option>
                                        </select>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-10">
                                            <strong>Por favor selecciona una opción de la lista!</strong>
                                        </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Capacidad del negocio (C&aacute;maras refrigerantes):</div>
                                    <select class="form-control custom-select outlinenone" name="cbrefrigerantes" id="cbrefrigerantes">
                                    <option value="">SELECCIONE UNA OPCION</option>
                                    <option value="0">SIN CAMARAS REFRIGERANTES</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    </select>
                                    <div class="valid-feedback">
                                    <strong></strong>
                                    </div>
                                    <div class="invalid-feedback">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Tipo punto de venta:</div>
                                    <div id="c-tpuntoventa">
                                        <select name="cbtpuntoventa" id="cbtpuntoventa">
                                            <option value="" selected>0</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Giro de negocio:</div>
                                    <div id="c-gironegocio">
                                        <select name="cbgironegocio" id="cbgironegocio">
                                            <option value="" selected>0</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-sort-numeric-up fa-lg"></span> Orden de visita</div>
                                    <input type="number" min="1" max="5" class="form-control outlinenone" name="txtordevisita" id="txtordevisita" value="0">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-11"> 
                                    </div>
                                </div>
                            </div> -->
                            <a href="#anclacoord" id="anclacoord"></a>
                            <div class="row_especial">
                                <div class="col-md-12 divrow">
                                    <div class="titulo_espcial">¿ QUIERES ACTUALIZAR LA UBICACIÓN ?</div>
                                    <!-- <input id="switch-two" type="checkbox" data-onstyle="success" data-offstyle="danger"> -->
                                    <input class="switch_estilo" id="switch-two" type="checkbox">

                                </div>
                            </div>
                        
                        <div id="Div_Coordendads" style="display: none;">
                            <div class="row" id="btn_obt_coordendas">
                                <div class="col-md-12 divrow">
                                    <center>
                                        <button type="button" class="btn btn-info carga-esconder" style="font-size: 15px;width: 100%;" id="btncoordenadas" name="btncoordenadas" onclick="consultar_coordenadas();"><strong> <span class="fa fa-map-marker-alt">
                                            </span> OBTENER COORDENADAS</strong>
                                        </button>
                                        
                                        <button type="button" class="btn btn-info carga-class" style="display:none;font-size: 15px;width: 100%;" id="btncoordenadas-hide" name="btncoordenadas-hide"><strong>
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            OBTENIENDO COORDENADAS</strong>
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
                                    <input type="hidden" id="txtlatitud" class="form-control" placeholder="Latitud...">
                                    <div class="valid-feedback">
                                      <strong></strong>
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-12">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="titulo"><span class="fa fa-compass fa-lg"></span> Longitud:</div>
                                    <input type="tel" id="txtlongitudm" class="form-control outlinenone" placeholder="Longitud..." readonly="readonly" style="background-color: #fff;">
                                    <input type="hidden" id="txtlongitud" class="form-control" placeholder="Longitud...">
                                    <div class="valid-feedback">
                                      <strong></strong>
                                    </div>
                                    <div class="invalid-feedback" id="error-mjs-13">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        </div><!--FINAL INFO CLENTES-->
                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="textoSeleccion"><br>
                                        <center>
                                            <button type="button" id="btn-enviar" class="btn btn-primary carga-esconder" onclick="enviar_actualizacion()"><span class="fas fa-paper-plane fa-lg" ></span> Enviar Actualización
                                            </button>
                                            <button type="button" class="btn btn-primary carga-class" style="display: none;"><span class="fas fa-paper-plane fa-spin fa-lg"></span> Enviando Actualización
                                            </button>
                                        </center>
                                    </div>
                                </div>
                            </div>
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
                <!-- <input class="switch_estilod" id="switch_ESAC" type="checkbox"> -->
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


    <!-- Modal CLIENTES NO ACTUALIZADOS-->
    <div class="modal fade" id="Modalopciones" tabindex="-1" role="dialog" aria-labelledby="eliminar" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminar">Lista de Clientes</h5>
<!--                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                     <button type="button" id="X" class="btn btn-info close" data-dismiss="modal" aria-label="Close">Regresar</button>
                </div>
                <div class="modal-body" id="" style="background-color: #fff;">
                    <!-- <div class="CTable_Gr"> -->
                       
                        <!-- <div id="X" class="CerrarGr" style="display: none;">X</div> -->
                               <!--  B&uacute;scar: <input id="txtBusqueda" type="text" onkeyup="grBusqueda()" /> -->
                                <div class="table-responsive">
                                    <div id="totalregistros" style="text-align: center;"></div><br>
                                    <table id="DgrTable" class="table" style="width:100%">
                                    <!-- <table id="DgrTable" class="table table-borderless table-sm GrTable" style="z-index: -1;"> -->
                                       <thead>
                                            <tr>
                                                <th scope="col">C&oacute;digo</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Direccion</th>
                                                <th scope="col">Contacto</th>
                                                <th scope="col">Tel&eacute;fono</th>
                                                <!-- <th scope="col">Imagen</th> -->
                                            </tr>
                                        </thead>
                                        <tbody id="showData">
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th scope="col">C&oacute;digo</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Direccion</th>
                                                <th scope="col">Contacto</th>
                                                <th scope="col">Tel&eacute;fono</th>
                                                <!-- <th scope="col">Imagen</th> -->
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <!-- <div id="paginado"></div> -->
                    <!-- </div> -->
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
               <!-- <center>SDV</center> -->
            </div>
        </div>
        </div>

    <!-- Modal DETALLES-->
    <div class="modal fade" id="ModalopcionesDET" tabindex="-1" role="dialog" aria-labelledby="eliminar" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminar">Detalles</h5>
  <!--                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                <button type="button" id="X" class="btn btn-info close" data-dismiss="modal" aria-label="Close">Regresar</button>
                </div>
                <div class="modal-body" id="" style="background-color: #fff;">
                    <!-- <div class="CTable_Gr"> -->
                        <div id="X" class="CerrarGr" style="display: none;">X</div>
                               <!--  B&uacute;scar: <input id="txtBusqueda" type="text" onkeyup="grBusqueda()" /> -->

        <table id="table-totales">
          <tr>
            <td colspan="2">
                <div class="encabezado-tabla">Total Clientes</div>
                <span class="" id="tgeneral">0</span>
            </td>
            </tr>

            <tr>
            <td>
                <div class="encabezado-tabla">Actualizados</div>
                <span class="" id="tactualizados">0</span>
            </td>
            <td>
                <div class="encabezado-tabla">Sin Actualizar</div>
                <span class="" id="tsinactualizar">0</span>
            </td>
          </tr>
        </table>

                <!-- <div class="card card-body" style="width: 100%;background-color: red;"> -->
<br>
<center><h5 style="margin: 0 auto;">CLIENTES ACTUALIZADOS</h5></center>

                                <div class="table-responsive">
                                        <div id="totalregistroscon" style="margin-top:5px;text-align: center;display: none;"></div>
                                        <table id="DgrTableCon" class="table table-striped table-bordered GrTable" style="display:;width:100%">
                                           <thead>
                                                <tr>
                                                    <th scope="col">C&oacute;digo</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Direccion</th>
                                                    <th scope="col">Contacto</th>
                                                    <th scope="col">Tel&eacute;fono</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="listaclientescon">
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th scope="col">C&oacute;digo</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Direccion</th>
                                                    <th scope="col">Contacto</th>
                                                    <th scope="col">Tel&eacute;fono</th>
                                                    
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                <!-- </div> -->
                <!-- </div> -->
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
               <!-- <center>SDV</center> -->
            </div>
        </div>
    </div>

  <div class="modal fullscreen-modal" id="ModalTutorial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
      <div class="modal-content">
        <div class="modal-header d_arriba">
          <span class="modal-title" style="margin-top:-7px;">Activar Geolocalización</span>
          <span id="X" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
        </div>
        <div class="modal-body">


          <img id="imgtutorial" src="#" height="100%">


  
        </div>
        <div class="modal-footer d_abajo">
        </div>
      </div>
    </div>
  </div>

