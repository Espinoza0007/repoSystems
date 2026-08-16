  <!--ESTILOS CSS-->
  <link href="<?php echo base_url('dependencias/css/CSS_clientesN.css') ?>" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
  <!--JAVASCRIPTS JS-->
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_clientesN.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_clientes.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/xlsx.full.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>
  <style>
  .separador {background-color:#727070;width: 100%;}
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




  <?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  ?>
  <!-- Modal -->
  <div class="modal fullscreen-modal" id="ModalExhibidores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
      <div class="modal-content">
        <div class="modal-header d_arriba">
          <span class="modal-title" style="margin-top:-7px;">LISTA DE EXHIBIDORES</span>
          <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
        </div>
        <div class="modal-body">
          <span class="fa fa-search-plus fa-2x" style="margin-top: 5px;color:#536162;"></span> 
          <input id="txtBusqueda" type="text"/>
          <div id="showData">
          </div>    
        </div>
        <div class="modal-footer d_abajo">
        </div>
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

  <!--000000---DIV CARGANDO---000000-->
  <div id="content-carga" style="display:none;" class="carga-class">
    <div class="d-flex justify-content-center">
      <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
        <span class="sr-only">Cargando...</span>
       </div>
    </div>
  </div>

  <div id="notificacion_ac" style="margin-top:50px;position: absolute;width: 100%;height: auto;display: none;">
    <div id="snackbar">Hay disponible una nueva versión. 
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
  <div class="container-fluid" style="height: 100%;width:100%;background-color: red;">

    <div class="row justify-content-md identify-content">
      <div style="width: 100%;position: absolute;" id="contenedor-formulario">
        <?php
            $atributos = array(
              'class' => 'form-estilo',
              'id' =>'form-clientes',
              'enctype' => 'multipart/form-data',
              'method' => 'POST'
          );
          echo form_open(base_url(''), $atributos);
        ?>
        <div class="" style="margin:0 auto;margin-top: 70px;background-color: blue;" id="principal">




        <input type="hidden" id="imagenuno_no" name="imagenuno_no" value="">          
        <input type="hidden" id="imagendos_no" name="imagendos_no" value="">
        <!-- <div id="imgbase64" style="max-width: 700px;height: auto;background-color:skyblue">hola</div> -->
        <!-- <textarea id="imgbase64"></textarea> -->
        <div id="registros-cola" style="display: none;"></div>
          <div class="card card-body" style="width: 100%;padding: 0px;border-radius: 0px;">
            <h4 class="card-header card-titulo" style="text-align:center;border:3px solid #fff;">Clientes Nuevos</h4><br>
            <div id="accordion">
              <div class="card">
                <div class="card-header header-acordeon" id="headingOne">
                  <h5 class="mb-0">
                    <button type="button" class="btn btn-link" style="text-decoration: none;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                      <span class="fa fa-bars fa-lg"></span> &nbsp;&nbsp;INF. DEL ESTABLECIMIENTO
                    </button>
                  </h5>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body">
                    <div class="form-group">
                      

                      <label><span class="fa fa-signature fa-lg"></span> Nombre establecimiento:</label>
                      <input type="text" name="txtnomcliente" id="txtnomcliente" class="form-control" placeholder="Nombre del establecimiento...">
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-0">
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-directions fa-lg"></span> Direcci&oacute;n:</label>
                      <textarea name="txtdireccion" id="txtdireccion" class="form-control" placeholder="Direccion del establecimiento..." height="207px"></textarea>
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-1">
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label><span class="fa fa-question fa-lg"></span> Departamento:</label>
                      <div id="c-departamento"></div>
                    </div>                
                    <div id="if-departamento" style="display: none;">
                      <div class="form-group">
                        <label><span class="fa fa-question fa-lg"></span> Municipio:</label>
                        <div id="c-municipio" class="especial-info">
                          <select name="cbmunicipio" id="cbmunicipio">
                            <option value="" selected>0</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-phone-square-alt fa-lg"></span> Tel&eacute;fono:</label>
                      <input type="tel" id="txtnumtelefono" name="txtnumtelefono" maxlength="9" class="form-control" placeholder="N&uacute;mero de tel&eacute;fono...">
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-4">
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-id-badge fa-lg"></span> Nombre de Contacto:</label>
                      <input type="text" name="txtnomcontacto" id="txtnomcontacto" class="form-control" placeholder="Nombre de contacto...">
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-5">
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-id-badge fa-lg"></span> Nombre de Propietario:</label>
                      <input type="text" name="txtpropietario" id="txtpropietario" class="form-control" placeholder="Nombre de propietario...">
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-6">
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-camera fa-lg"></span> Foto fachada del negocio:</label>
                      <div class="custom-file">
                        <input id="filefnegocio" name="filefnegocio" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                        <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Foto de fachada del negocio</label>
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-7">
                        <strong>Por favor toma una foto!</strong>
                      </div>
                      </div>
                      <br><br>
                      <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="canvas" style="border: 1px solid black;width:200px;height:200px;">
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-question fa-lg"></span> Referencia:</label>
                      <div id="c-referencia"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header header-acordeon" id="headingFour">
                  <h5 class="mb-0">
                    <button type="button" class="btn btn-link collapsed quitartexto" style="text-decoration: none;" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                      <span class="fa fa-bars fa-lg"></span> &nbsp;&nbsp;TIPO DE NEGOCIO
                    </button>
                  </h5>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">  
                  <div class="card-body">
                    <div class="form-group">
                      <label><span class="fa fa-question fa-lg"></span> Tipo de punto de venta:</label>
                      <div id="c-tpuntoventa"></div>
                    </div>    
                    <div id="if-tnegocio" style="display: none;" class="especial-info">
                      <div class="form-group">
                        <label><span class="fa fa-question fa-lg"></span> Giro de Negocio:</label>
                        <div id="c-gironegocio">
                          <select name="cbgironegocio" id="cbgironegocio">
                            <option value="" selected>0</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-question fa-lg"></span> Tipo de facturaci&oacute;n:</label>
                        <div id="c-tfacturacion"></div>
                    </div>
                    <div id="if-tfactura" style="display: none;" class="especial-info">
                      <div class="form-group" id="div_dui" style="display: none;">
                        <label id="docidentidad"></label>
                        <input type="tel" id="txtdui" maxlength="15" name="txtdui" class="form-control">
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-11">
                      </div>                    
                      </div>
                      <div class="form-group" id="div_numregistro" style="display: none;">
                        <label><span class="fa fa-registered fa-lg"></span> N&uacute;mero de registro de contribuyente:</label>
                        <input type="tel" id="txtnumcontribuyente" name="txtnumcontribuyente" maxlength="10" class="form-control" placeholder="Número de registro de contribuyente...">
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-12">
                      </div>                    
                      </div>
                      <div class="form-group" id="div_nit" style="display: none;">
                        <label id="idtributaria"></label>
                        <input type="tel" id="txtnit" name="txtnit" maxlength="14" class="form-control">
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-13">
                      </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-question fa-lg"></span> Condici&oacute;n de cliente:</label>
                      <div id="c-condicioncli">
                          <select name="cbcondicioncli" id="cbcondicioncli">
                            <option value="" selected>0</option>
                          </select>
                      </div>
                    </div>
                    <div id="if-condcliente" style="display: none;" class="especial-info">             
                      <div class="form-group">
                        <label><span class="fa fa-question fa-lg"></span> D&iacute;a de cobro:</label>
                        <select class="form-control js-example-responsive narrow custom-select" id="cbdiascobro" name="cbdiascobro">
                          <option value="">SELECCIONE UNA OPCI&Oacute;N</option>
                          <option value="1">LUNES</option>
                          <option value="2">MARTES</option>
                          <option value="3">MI&Eacute;RCOlES</option>
                          <option value="4">JUEVES</option>
                          <option value="5">VIERNES</option>
                          <option value="6">SABADO</option>
                          <option value="7">DOMINGO</option>
                        </select>
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback">
                          <strong>Por favor selecciona una opción de la lista!</strong>
                        </div>
                      </div>
                      <div class="form-group">
                        <label><span class="fa fa-dollar-sign fa-lg"></span> Monto de cr&eacute;dito:</label>
                        <input type="tel" name="txtmontocredito" id="txtmontocredito" class="form-control" placeholder="Monto Crédito...">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-16">
                        </div>   
                      </div>
                    </div>
                    <div class="form-group">
                      <label><span class="fa fa-question fa-lg"></span> Capacidad del negocio (C&aacute;maras refrigerantes):</label>
                        <select class="form-control js-example-responsive narrow custom-select" name="cbrefrigerantes" id="cbrefrigerantes">
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
                </div>
              </div>
              <div class="card">
                <div class="card-header header-acordeon" id="headingThree">
                  <h5 class="mb-0">
                    <button type="button" class="btn btn-link collapsed quitartexto" style="text-decoration: none;" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      <span class="fa fa-bars fa-lg"></span> &nbsp;&nbsp;INFO. DE VISITA
                    </button>
                  </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="Frecuenciavisita"><span class="fa fa-question fa-lg"></span> Frecuencia De Visita:</label>
                      <select class="form-control custom-select" id="cbfrecuenciavisita" name="cbfrecuenciavisita">
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
                      <div class="invalid-feedback" id="error-mjs-17">
                        <strong>Por favor selecciona una opción de la lista!</strong>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="Diasvisita"><span class="fa fa-calendar-day fa-lg"></span> D&iacute;as De Visita:</label>
                      
                      
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input GR_Check" id="checklunes" name="checkdiavisita[]" value='L_1'>
                        <label class="custom-control-label" for="checklunes">LUNES</label>
                      </div>

                      <div style="margin-top:7px;display:none;" id="ord_l">
                        <label>Orden De Visita Lunes:</label>
                        <input type="tel" name="txtordenvisital" id="txtordenvisital" class="form-control" placeholder="Orden de visita..." value="0" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-31">
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
                        <input type="tel" name="txtordenvisitam" id="txtordenvisitam" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-32">
                        </div>
                        <hr class="separador">
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input GR_Check" id="checkmiercoles" name="checkdiavisita[]" value='I_1'>
                        <label class="custom-control-label" for="checkmiercoles">MI&Eacute;RCOLES</label>
                      </div>


                      <div style="margin-top:7px;display:none;" id="ord_i">
                        <label>Orden De Visita Miércoles:</label>
                        <input type="tel" name="txtordenvisitai" id="txtordenvisitai" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-33">
                        </div>
                        <hr class="separador">
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input GR_Check" id="checkjueves" name="checkdiavisita[]" value='J_1'>
                        <label class="custom-control-label" for="checkjueves">JUEVES</label>
                      </div>


                      <div style="margin-top:7px;display:none;" id="ord_j">
                        <label>Orden De Visita Jueves:</label>
                        <input type="tel" name="txtordenvisitaj" id="txtordenvisitaj" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-34">
                        </div>
                        <hr class="separador">
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input GR_Check" id="checkviernes" name="checkdiavisita[]" value='V_1'>
                        <label class="custom-control-label" for="checkviernes">VIERNES</label>
                      </div>


                      <div style="margin-top:7px;display:none;" id="ord_v">
                        <label>Orden De Visita Viernes:</label>
                        <input type="tel" name="txtordenvisitav" id="txtordenvisitav" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-35">
                        </div>
                        <hr class="separador">
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input GR_Check" id="checksabado" name="checkdiavisita[]" value='S_1'>
                        <label class="custom-control-label" for="checksabado">SABADO</label>
                      </div>


                      <div style="margin-top:7px;display:none;" id="ord_s">
                        <label>Orden De Visita Sabado:</label>
                        <input type="tel" name="txtordenvisitas" id="txtordenvisitas" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-36">
                        </div>
                        <hr class="separador">
                      </div>


                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input GR_Check" id="checkdomingo" name="checkdiavisita[]" value='D_1'>
                        <label class="custom-control-label" for="checkdomingo">DOMINGO</label>
                      </div>


                      <div style="margin-top:7px;display:none;" id="ord_d">
                        <label>Orden De Visita Domingo:</label>
                        <input type="tel" name="txtordenvisitad" id="txtordenvisitad" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-37">
                        </div>
                        <hr class="separador">
                      </div>

                      <input type="checkbox" style="display: none;" class="custom-control-input GR_Check" id="checkvalidate" value=''>
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback">
                          <strong>Por favor selecciona una opción de la lista!</strong>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label><span class="fa fa-sort-numeric-up fa-lg"></span> Orden De Visita:</label>
                        <input type="tel" name="txtordenvisita" id="txtordenvisita" class="form-control" placeholder="Orden de visita..." value="" min="0" max="90" step="1">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-30">
                        </div>   
                    </div> -->
                    <div class="form-group">
                      <button type="button" class="btn btn-info" style="font-size: 13px;" id="btncoordenadas" name="btncoordenadas" onclick="consultar_coordenadas()"><strong> <span class="fa fa-map-marker-alt fa-lg"></span> OBTENER COORDENADAS</strong></button>
                    </div>
                    <!--SOY EL MAPA, SIII-->
                    <div id="map" style="height: 277px;width: 100%;"></div>
                    <div class="row">
                      <div class="col-6">
                        <label for="Latitud"><span class="fa fa-compass fa-lg"></span> Latitud</label>
                        <input type="tel" name="txtlatitudm" id="txtlatitudm" class="form-control" placeholder="Latitud..." readonly="readonly" style="background-color: #fff;">
                        <input type="hidden" name="txtlatitud" id="txtlatitud" class="form-control" placeholder="Latitud...">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-19">
                        </div>
                      </div>
                      <div class="col-6">
                        <label for="Longitud"><span class="fa fa-compass fa-lg"></span> Longitud</label>
                        <input type="tel" name="txtlongitudm" id="txtlongitudm" class="form-control" placeholder="Longitud..." readonly="readonly" style="background-color: #fff;">
                        <input type="hidden" name="txtlongitud" id="txtlongitud" class="form-control" placeholder="Longitud...">
                        <div class="valid-feedback">
                          <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-20">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bt-contenedor">
                <div class="">
                  <!-- <div class="col-12"> -->
                  <button type="button" class="btn-form btngr_azul carga-esconder" id="btn-enviar" onclick="agregar_usuario()"><span class="fas fa-paper-plane fa-lg"></span> Enviar</button>
                  <!-- </div> -->
                  <!--000000---BOTON CARGANDO---000000-->
                  <button type="button" aria-pressed="false" class="btn-form btngr_azul_presi carga-class" style="display:none;">
                    <span class="fas fa-paper-plane fa-spin fa-lg" style="padding: 3px;"></span>
                      Enviar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          echo form_close();
        ?>
      </div>
    </div>
</div>
<script type="text/javascript">

document.getElementById('filefnegocio').onchange = function(evt) {
  FotoFachada = '';
  ImageTools.resize(this.files[0], {
    width: 923,
    height: 503
  }, function(blob, didItResize) {
    document.getElementById('canvas').src = window.URL.createObjectURL(blob);
    $("#imagenuno_no").val('ya');
    var reader = new FileReader();
    reader.readAsDataURL(blob); 
    reader.onloadend = function() {
      var base64data = reader.result;
      FotoFachada = base64data;
    }
  });
};

</script>
