<!--ESTILOS CSS-->
	<link href="<?php echo base_url('dependencias/css/CSS_actuexhibidor.css') ?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/leaflet/leaflet.css') ?>">



<!--JAVASCRIPTS JS-->
	<script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_exhibidores.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_exhibidores.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>

	<script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>

	<script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/Configuracion_Modal.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_Validacion_Exh.js') ?>"></script>
<style>
.modal-content{
    background-color: #eeeeee;
}
.modal-header{
    background-color: #eeeeee;
    border-bottom: none !important;
    box-shadown: 0 7px 8px -4px rgb(0 0 0 / 20%), 0 12px 17px 2px rgb(0 0 0 / 14%), 0 5px 22px 4px rgb(0 0 0 / 12%);
}
.gr_pre___x .nav{
    background-color: #eeeeee;
    padding-top: 10px;
    border-color: #646464;
}
.gr_pre___x .nav a:first-child{
    border-radius: 5px 5px 0px 0px;
    border-color: #646464;
    margin-left: 15px;
    border-bottom: none;
}
.gr_pre___x .nav a{
    border-color: #646464;
    margin-left: 3px;
    border-radius: 5px 5px 0px 0px;
    border-bottom: none;
}
.gr_pre___x .nav a:hover{
    border-color: #646464;
    background: #ECECEC;
    color: #FFF;
}
.gr_pre___x .nav a.x.active{
    background: #C8E9FF;
    border-color: #646464;
    border-bottom: none;
}
.gr_pre___x .nav a.y.active{
    background: #C9FFC8;
    border-color: #646464;
    border-bottom: none;
}
.gr_pre___x .nav a.z.active{
    background: #FFD7D7;
    border-color: #646464;
    border-bottom: none;
}

.gr_pre___y{
    background-color:#eeeeee;
}
.gr_pre___y div form #list_qtiene .fichz__{
    border-top: 10px solid #04A0F4;
    width: 90%;
    margin: 0 auto;
    margin-top: 25px;
    margin-bottom: 25px;
    border-radius: 5px;
    background-color: #fff;
    padding-top: 10px;
    box-shadow: 0 3px 5px -1px rgb(0 0 0 / 20%), 0 5px 8px 0 rgb(0 0 0 / 14%), 0 1px 14px 0 rgb(0 0 0 / 12%);
}
.gr_pre___y div form #list_qtiene .fichz__ .box01{
    
}
.gr_pre___y div form #list_qtiene .fichz__ .box01 .box03{
    
}
.gr_pre___y div form #list_qtiene .fichz__ .box01 .box03 span{
    margin-right: 5px;
    
}
.gr_pre___y div form #list_qtiene .fichz__ .box01 .box03 span:first-child{
    color: #828282;
}
.gr_pre___y div form #list_qtiene .fichz__ .box01 .box03 span:last-child{
    color: #ff0000;
}
.gr_pre___y div form #list_qtiene .fichz__ .seg{
    padding: 10px 0px 10px 0px;
}
.gr_pre___y div form #list_qtiene .fichz__ .toggle_observacion hr{
    width: 90%;
    margin-bottom: 0px;
    margin-top: 0px;
}
.gr_pre___y div form #list_qtiene .fichz__ .toggle_observacion hr.sig__{
    width: 80%;
    margin-bottom: 0px;
    margin-top: 0px;
    display: none !important;
}
.gr_pre___y div form #list_qtiene .fichz__ .toggle_observacion button{
    border-radius: 0px 0px 5px 5px;
    color: #808080 !important;
    padding: 15px 0px 15px 0px;
}
.gr_pre___y div form #list_qtiene .fichz__ .toggle_observacion button:hover{
    color: #808080;
}
.gr_pre___y div form #list_qtiene .fichz__ .toggle_observacion .gretro__ .form-group .fa-camera{
    font-size: 1rem;
}
.gr_pre___y div form #list_qtiene .fichz__ .toggle_observacion .gretro__ .form-group label{
    text-transform: uppercase;
}

/*sub-nav-qtiene*/


.gr_pre___y div form #sub-nav-qtiene .fichz__{
    border-top: 10px solid #04A0F4;
    width: 90%;
    margin: 0 auto;
    margin-top: 25px;
    margin-bottom: 25px;
    border-radius: 5px;
    background-color: #fff;
    padding-top: 10px;
    box-shadow: 0 3px 5px -1px rgb(0 0 0 / 20%), 0 5px 8px 0 rgb(0 0 0 / 14%), 0 1px 14px 0 rgb(0 0 0 / 12%);
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .box01{
    
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .box01 .box03{
    
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .box01 .box03 span{
    margin-right: 5px;
    
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .box01 .box03 span:first-child{
    color: #828282;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .box01 .box03 span:last-child{
    color: #ff0000;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .seg{
    padding: 10px 0px 10px 0px;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .toggle_observacion hr{
    width: 90%;
    margin-bottom: 0px;
    margin-top: 0px;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .toggle_observacion hr.sig__{
    width: 80%;
    margin-bottom: 0px;
    margin-top: 0px;
    display: none !important;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .toggle_observacion button{
    border-radius: 0px 0px 5px 5px;
    color: #808080 !important;
    padding: 15px 0px 15px 0px;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .toggle_observacion button:hover{
    color: #808080;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .toggle_observacion .gretro__ .form-group .fa-camera{
    font-size: 1rem;
}
.gr_pre___y div form #sub-nav-qtiene .fichz__ .toggle_observacion .gretro__ .form-group label{
    text-transform: uppercase;
}


/*sub-nav-nuevos*/
.gr_pre___y div form #sub-nav-nuevos .fichz__{
    border-top: 10px solid #0cc370;
    width: 90%;
    margin: 0 auto;
    margin-top: 25px;
    margin-bottom: 25px;
    border-radius: 5px;
    background-color: #fff;
    padding-top: 10px;
    box-shadow: 0 3px 5px -1px rgb(0 0 0 / 20%), 0 5px 8px 0 rgb(0 0 0 / 14%), 0 1px 14px 0 rgb(0 0 0 / 12%);
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .box01{
    
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .box01 .box03{
    
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .box01 .box03 span{
    margin-right: 5px;
    
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .box01 .box03 span:first-child{
    color: #828282;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .box01 .box03 span:last-child{
    color: #ff0000;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .segtres{
    padding: 10px 0px 10px 0px;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .toggle_observacion hr{
    width: 90%;
    margin-bottom: 0px;
    margin-top: 0px;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .toggle_observacion hr.sig__{
    width: 80%;
    margin-bottom: 0px;
    margin-top: 0px;
    display: none !important;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .toggle_observacion button{
    border-radius: 0px 0px 5px 5px;
    color: #808080 !important;
    padding: 15px 0px 15px 0px;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .toggle_observacion button:hover{
    color: #808080;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .toggle_observacion .gretro__ .form-group .fa-camera{
    font-size: 1rem;
}
.gr_pre___y div form #sub-nav-nuevos .fichz__ .toggle_observacion .gretro__ .form-group label{
    text-transform: uppercase;
}

/*-----------------------------------------*/


/*list_devul*/

.gr_pre___y div form #list_devul .fichz__{
    border-top: 10px solid #f13154;
    width: 90%;
    margin: 0 auto;
    margin-top: 25px;
    margin-bottom: 25px;
    border-radius: 5px;
    background-color: #fff;
    padding-top: 10px;
    box-shadow: 0 3px 5px -1px rgb(0 0 0 / 20%), 0 5px 8px 0 rgb(0 0 0 / 14%), 0 1px 14px 0 rgb(0 0 0 / 12%);
}
.gr_pre___y div form #list_devul .fichz__ .box01{
    
}
.gr_pre___y div form #list_devul .fichz__ .box01 .box03{
    
}
.gr_pre___y div form #list_devul .fichz__ .box01 .box03 span{
    margin-right: 5px;
    
}
.gr_pre___y div form #list_devul .fichz__ .box01 .box03 span:first-child{
    color: #FF0000;
}
.gr_pre___y div form #list_devul .fichz__ .box01 .box03 span:last-child{
    color: #2BA6CB;
}
.gr_pre___y div form #list_devul .fichz__ .seg{
    padding: 10px 0px 10px 0px;
}
.gr_pre___y div form #list_devul .fichz__ .toggle_observacion hr{
    width: 90%;
    margin-bottom: 0px;
    margin-top: 0px;
}
.gr_pre___y div form #list_devul .fichz__ .toggle_observacion hr.sig__{
    width: 80%;
    margin-bottom: 0px;
    margin-top: 0px;
    display: none !important;
}
.gr_pre___y div form #list_devul .fichz__ .toggle_observacion button{
    border-radius: 0px 0px 5px 5px;
    color: #808080 !important;
    padding: 15px 0px 15px 0px;
}
.gr_pre___y div form #list_devul .fichz__ .toggle_observacion button:hover{
    color: #808080;
}
.gr_pre___y div form #list_devul .fichz__ .toggle_observacion .gretro__ .form-group .fa-camera{
    font-size: 1rem;
}
.gr_pre___y div form #list_devul .fichz__ .toggle_observacion .gretro__ .form-group label{
    text-transform: uppercase;
}

.gr_pre___y div form #det_devul .fichz__{
    border-top: 10px solid #f13154;
    width: 90%;
    margin: 0 auto;
    margin-top: 25px;
    margin-bottom: 25px;
    border-radius: 5px;
    background-color: #fff;
    padding-top: 10px;
    box-shadow: 0 3px 5px -1px rgb(0 0 0 / 20%), 0 5px 8px 0 rgb(0 0 0 / 14%), 0 1px 14px 0 rgb(0 0 0 / 12%);
}
.gr_pre___y div form #det_devul .fichz__ .box01{
    
}
.gr_pre___y div form #det_devul .fichz__ .box01 .box03{
    
}
.gr_pre___y div form #det_devul .fichz__ .box01 .box03 span{
    margin-right: 5px;
    
}
.gr_pre___y div form #det_devul .fichz__ .box01 .box03 span:first-child{
    color: #FF0000;
}
.gr_pre___y div form #det_devul .fichz__ .box01 .box03 span:last-child{
    color: #2BA6CB;
}


.fichz__ .box01 .box03 span:last-child{
    color: #2BA6CB;
}


.gr_pre___y div form #det_devul .fichz__ .seg{
    padding: 10px 0px 10px 0px;
}
.gr_pre___y div form #det_devul .fichz__ .toggle_observacion hr{
    width: 90%;
    margin-bottom: 0px;
    margin-top: 0px;
}
.gr_pre___y div form #det_devul .fichz__ .toggle_observacion hr.sig__{
    width: 80%;
    margin-bottom: 0px;
    margin-top: 0px;
    display: none !important;
}
.gr_pre___y div form #det_devul .fichz__ .toggle_observacion button{
    border-radius: 0px 0px 5px 5px;
    color: #808080 !important;
    padding: 15px 0px 15px 0px;
}
.gr_pre___y div form #det_devul .fichz__ .toggle_observacion button:hover{
    color: #808080;
}
.gr_pre___y div form #det_devul .fichz__ .toggle_observacion .gretro__ .form-group .fa-camera{
    font-size: 1rem;
}
.gr_pre___y div form #det_devul .fichz__ .toggle_observacion .gretro__ .form-group label{
    text-transform: uppercase;
}

/*-----------------------------------------*/


/*-------------------------------*/
.grtritu___{
    font-weight: 500;
}
.fa-asterisk{
    font-size: 1rem;
}
.__p_form .row{
    text-align: left;
    margin-bottom: 20px;
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

    #txt_nom_cli_cod{
      font-size: 12px;
      color:#0B6371;
      font-style: italic;
      font-weight: 600;
      text-align: center;
    }
    .btn_css{
      font-size: 15px;
    }
</style>

</head>
<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">

  <div id="snackbar">Hay disponible una nueva versión. <a id="reload">Actualizar ahora <i class="fas fa-download fa-lg"></i></a></div>
  
    <div class="row" id="content-map" style="margin-top:15px;display:none;">
      <div class="col-md-12 divrow">
        <div id="map" style="height: 277px;width: 100%;"></div>
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
      <div style="margin:0 auto;" id="btn-pendinetes" onclick="EnvioCola()" class="icons_posicion">
          <span class="fa fa-cloud-upload-alt fa-2x" style="color:#fff;"></span> <span style="font-size: 14px;" class="badge badge-pill badge-dark" id="RegisCola">0</span>
      </div>
      <div style="" class="icons_posicion" id="btn-menu-back">
       <span class="fa fa-bars fa-2x" style=""></span>
      </div>
  </nav>


  <div class="container" id="conten_prin">

    <h5 class="card-header card-header-form" style="margin-top: 60px;">
        Actualizaci&oacute;n de exhibidores
    </h5>
    
    <div class="row" style="margin-top: 20px;">
      <div class="col-md-12" style="">
        <button type="button" class="btn btn-dark" onclick="DB_ListarClientes()" style="width: 100%;">
          <span class="fas fa-database fa-2x"></span> <p>Clientes</p>
        </button>
      </div>
    </div>

    <div id="InfoCuadro">
      <div class="alert alert-info" role="alert" style="text-align: center;margin-top: 10px;">
          <h4 class="alert-heading">Aviso!</h4>
          <p>Por favor selecciona un Cliente.</p>
          <hr>
          <p class="mb-0">
            <span class="fas fa-database fa-3x"></span> <br>Clientes
          </p>
      </div>
    </div>

    <div id="InfoCliente" style="display: none;margin-top: 7px;">

<!--       <div class="row _ter">
        <div class="col-md"><span class="fas fa-user fa-lg"></span> Última Fecha de Actualización:</div>
        <div class="col-md"><input type="text" class="form-control" name="codigoCli" id="codigoCli" readonly></div>
      </div>
 -->
      <input type="hidden" class="form-control" name="direccionCli" id="direccionCli">
      <input type="hidden" class="form-control" name="telefonoCli" id="telefonoCli">
      <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12 divrow" style="text-align: center;">
          <strong>Última Fecha de Actualización: <br><i>
            <span id="lbl_fultima" style="color:#0B6371;"></span></i>
          </strong>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 divrow" style="text-align: center;">
          <strong>Estado: <br><i>
            <span id="lbl_estadoExh" style="color:#0B6371;">SIN EXHIBIDORES</span></i>
          </strong>
        </div>
      </div>


      <div class="row _ter">
        <div class="col-md"><span class="fas fa-user fa-lg"></span> C&oacute;digo</div>
        <div class="col-md"><input type="text" class="form-control" name="codigoCli" id="codigoCli" readonly></div>
      </div>
      <div class="row _ter">
        <div class="col-md"><span class="fas fa-signature fa-lg"></span> Nombre cliente</div>
        <div class="col-md"><input type="text" class="form-control" name="nombreCli" id="nombreCli" readonly></div>
      </div>
<!--       <div class="row _ter">
        <div class="col-md"><span class="fas fa-location-arrow fa-lg"></span> Direcci&oacute;n</div>
        <div class="col-md">
          <textarea class="form-control" name="direccionCli" id="direccionCli" readonly></textarea>
        </div>
      </div> -->
      <div class="row _ter">
        <div class="col-md"><span class="fas fa-users fa-lg"></span> Contactos</div>
        <div class="col-md"><input type="text" class="form-control" name="contactoCli" id="contactoCli" readonly></div>
      </div>
<!--       <div class="row _ter" style="background-color: red;width: 100%;">
        
      </div>    -->   

      <div class="row justify-content-md-center" style="margin-top: -12px;">

          <div class="col">
          <button type="button" class="btn btn-danger" id="btn_conExh" style="width: 100%;"><i class="fas fa-search-plus fa-lg"></i> <br><span class="btn_css">Consultar</span></button>
          </div>
          <div class="col">
          <button type="button" class="btn btn-dark" id="btn_sinExh" style="width: 100%;"><i class="fas fa-ban fa-lg"></i> <br><span class="btn_css">Sin Exhibidores</span></button>
          </div>
       
<!--         <div class="col-md-6">

        </div> -->
      </div>


    </div> 

  </div>


<!--- MODAL EXHIBIDORES --->
  <div class="modal fullscreen-modal" id="ModalExh" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
      <div class="modal-content">
        <div class="modal-header d_arriba">
          <span class="modal-title" style="margin-top:-7px;">LISTA DE EXHIBIDORES</span>
          <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
        </div>
        <div class="modal-body">
          <div class="row" style="margin-top: 7px;">
              <div class="col" id="S_filtroSubFamilia"> 
                  <select id='txtipoexh' class='form-control' >
                      <option value=''>TODOS LOS TIPOS</option>
                  </select>
              </div>
          </div>
          <div class="table-responsive">
            <table id="DgrTableExh" class="table table-bordered" style="width:100%">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">C&oacute;digo</th>
                  <th scope="col">Foto</th>
                  <th scope="col">Descripcion</th>
                  <th scope="col">Tipo</th>
                </tr>
              </thead>
              <tbody id="showDataExh">
              </tbody>
              <tfoot>
                <tr>
                  <th scope="col">C&oacute;digo</th>
                  <th scope="col">Foto</th>
                  <th scope="col">Descripcion</th>
                  <th scope="col">Tipo</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer d_abajo">
          </div>
        </div>
      </div>
    </div>
  </div>

<!--- MODAL CLIENTES --->

  <div class="modal fullscreen-modal" id="ModalCli" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" >
      <div class="modal-content">
        <div class="modal-header d_arriba">
          <span class="modal-title" style="margin-top:-7px;">LISTA DE CLIENTES</span>
          <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
        </div>
        <div class="modal-body">
<!--           <div class="row" style="margin-top: 7px;">
              <div class="col" id="S_filtroSubFamilia"> 
                  <select id='txtipoexh' class='form-control' style="">
                      <option value=''>TODOS LOS TIPOS</option>
                  </select>
              </div>
          </div> -->

        <div class="row" style="margin-top: 7px;">
      
            <div class="col-12">     
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
            <table id="DgrTableCli" class="table table-bordered" style="width:100%">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">C&oacute;digo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Direccion</th>
                  <th scope="col">Contacto</th>
                  <th scope="col">Telefono</th>
                  <th scope="col">Día</th>
                  <th scope="col">Fecha</th>
                  <!-- <th scope="col">Día Visita</th> -->
                </tr>
              </thead>
              <tbody id="showDataCli">
              </tbody>
              <tfoot>
                <tr>
                  <th scope="col">C&oacute;digo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Direccion</th>
                  <th scope="col">Contacto</th>
                  <th scope="col">Telefono</th>
                  <th scope="col">Día</th>
                  <th scope="col">Fecha</th>
                  <!-- <th scope="col">Día Visita</th> -->
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer d_abajo">
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- CONTROL DE EXHIBIDORES VISTA -->
<!-- Modal -->


<div class="modal fade-scale" id="m_control_exhibidores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#000;text-align: center;">CONTROL DE EXHIBIDORES<br>
          <span id="txt_nom_cli_cod">-------</span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- PRB-1 TAB EXHIBIDORES-->
        <div id="div_detExh" style="" class="gr_pre___x">
          <input id="txtclientesexh" type="hidden" value="0">
          <input id="textlatexh" type="hidden" value="0">
          <input id="textlotexh" type="hidden" value="0">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a class="nav-link x active" id="nav-qtiene-tab" data-toggle="tab" href="#nav-qtiene" role="tab" aria-controls="nav-qtiene" aria-selected="true">
                <i class="fas fa-check fa-lg c_qtiene" id="c_qtiene"></i>
              </a>
              <a class="nav-link y" id="nav-nuevos-tab" data-toggle="tab" href="#nav-nuevos" role="tab" aria-controls="nav-nuevos" aria-selected="false">
                <i class="fas fa-plus fa-lg c_nuevos" id="c_nuevos"></i>
              </a>
              <a class="nav-link z" id="nav-devueltos-tab" data-toggle="tab" href="#nav-devueltos" role="tab" aria-controls="nav-devueltos" aria-selected="false">
                <i class="fas fa-minus-circle fa-lg c_devueltos" id="c_devueltos"></i>
              </a>
            </div>
          </nav>
          <div class="tab-content gr_pre___y" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-qtiene" role="tabpanel" aria-labelledby="nav-qtiene-tab">
              <form id="form_exqtiene">
                <div id="list_qtiene"></div>
                <div id="det_qtiene"></div>
                <div id="sub-nav-qtiene"></div>
                <div id="add_btQtiene" style="display:none;">
                  <div class="div_add">
                    <button type="button" class="btn btn-success" onclick="DB_ListarExhibidores(1,1)"><i class="fas fa-plus fa-3x"></i></button>
                  </div>
                </div>
              </form>
            </div>
            <div class="tab-pane fade" id="nav-nuevos" role="tabpanel" aria-labelledby="nav-nuevos-tab">
              <form id="form_nuevos">
                <div id="det_nuevos">
                  <div class="alert alert-info" role="alert" style="text-align: center;margin-top: 10px;">
                    <h4 class="alert-heading">Aviso!</h4>
                    <p>NO HAY REGISTROS PARA MOSTRAR</p>
                    <hr>
                    <i class="fas fa-folder-open fa-3x"></i>
                  </div>
                </div>
                <div id="sub-nav-nuevos"></div>
              </form>
              <div id="add_nuevos" class="div_add">
                <button type="button" class="btn btn-success" onclick="DB_ListarExhibidores(3,1)"><i class="fas fa-plus fa-3x"></i></button>
              </div>
            </div>
            <div class="tab-pane fade" id="nav-devueltos" role="tabpanel" aria-labelledby="nav-devueltos-tab">
              <form id="form_devueltos">
                <div id="list_devul"></div>
                <div id="det_devul"></div>
                <div id="sub-nav-devueltos"></div>
              </form>
            </div>
          </div>
        </div>
        <div id="div_btnPlus" style="display: none;"><span class="fas fa-plus fa-5x" id="plus" style="color:;"></span></div>
        <div id="errores_exhibidores"></div>



      </div>
      <div class="modal-footer" id="modal_foo_exh">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-info" id="btn_conExh">Consultar</button> -->
        <button type="button" class="btn btn-info" id="btn_GuaExh" style="display: ;" onclick="Enviar_CambiosExhibidores()">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal MOTIVO ELIMINACION REGISTRO-->
<div class="modal fade" id="modalMotivoElim" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">ELIMINAR REGISTRO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row" id="det_exheliminar" style="width: 95%;margin:0 auto;font-weight: bold;"></div>

      <div class="col-md">
        <label>Motivo para eliminar:</label>
        <div id="S_filtroMotivoE">
          <select class="form-control" id="filtromotivosE" name="filtromotivosE">
              <option value="">.....</option>
              <option value="">.....</option>
          </select>
          <div class="valid-feedback">
            <strong></strong>
          </div>
          <div class="invalid-feedback d-block">
            <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
          </div>
        </div>
      </div>

      <input type="hidden" id="registroElim" value="">
      <!-- <img src="../../Uploads/img_server/Img_CatalagoProductos/icon_default.png" id="img_eliminar" style="width: 90%;"> -->
      <div class="row" style="width: 99%;margin:0 auto;margin-top: 7px;">
          <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Deja un comentario:</span></div>
          <div class="col">
              <textarea class="form-control" id="txtelimR" name="txtelimR[]" maxlength="255"></textarea>
              <div class="valid-feedback"></div>
              <div class="invalid-feedback" id="error_elimR"></div>
          </div>
      </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmar_eliminarR">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal MOTIVO DEVOLUCION DE EXHIBIDORES-->
<div class="modal fade" id="modalMotivoDevol" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">DEVOLUCIÓN DE EXHIBIDOR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row" id="det_exhdevolu" style="width: 95%;margin:0 auto;font-weight: bold;"></div>

      <div class="col-md">
        <label>Motivo de devolución:</label>
        <div id="S_filtroMotivoD">
          <select class="form-control" id="filtromotivosD" name="filtromotivosD">
              <option value="">.....</option>
              <option value="">.....</option>
          </select>
          <div class="valid-feedback">
            <strong></strong>
          </div>
          <!-- d-block -->
          <div class="invalid-feedback">
            <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
          </div>
        </div>
      </div>

      <input type="hidden" id="registroDevol" value="">
      <!-- <img src="../../Uploads/img_server/Img_CatalagoProductos/icon_default.png" id="img_eliminar" style="width: 90%;"> -->
      <div class="row" style="width: 99%;margin:0 auto;margin-top: 7px;">
          <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Deja un comentario:</span></div>
          <div class="col">
              <textarea class="form-control" id="txtdevol" name="txtdevol[]" maxlength="255"></textarea>
              <div class="valid-feedback"></div>
              <div class="invalid-feedback" id="error_devolu"></div>
          </div>
      </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmar_devolucion">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    /* Fotos de los exhibidores */
    /* Exhibidor Antes */
    $('#div_detExh').on('change', '.file_u', function() {
        var img_id = $(this).attr("id"),id_Org = 0;
        img_id = img_id.substring(10,img_id.length);id_Org = img_id;
        $("#filefotosu"+img_id).removeClass("is-invalid").addClass("is-valid");
        $("#error-mjsf-" + img_id).html('');
        img_id= "imagenu"+img_id;
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
                  html:`<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
                  confirmButtonText:'Ok'
                });
              }else{
                var base64data = reader.result;
                arrg_fotosExh[id_Org] = base64data;
              }
              URL.revokeObjectURL(this.src);
            }
        });
    });

    $('#div_detExh').on('change', '.file_d', function() {
        var img_id = $(this).attr("id"),id_Org = 0;
        img_id = img_id.substring(10,img_id.length);id_Org = img_id;
        $("#filefotosd"+img_id).removeClass("is-invalid").addClass("is-valid");
        $("#error-mjsf-" + img_id).html('');
        img_id= "imagend"+img_id;
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
                  html:`<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
                  confirmButtonText:'Ok'
                });
              }else{
                var base64data = reader.result;
                arrg_fotosExhD[id_Org] = base64data;
              }
              URL.revokeObjectURL(this.src);
            }
        });
    });

    $('#div_detExh').on('change', '.file_t', function() {
        var img_id = $(this).attr("id"),id_Org = 0;
        img_id = img_id.substring(10,img_id.length);id_Org = img_id;
        $("#filefotost"+img_id).removeClass("is-invalid").addClass("is-valid");
        $("#error-mjsf-" + img_id).html('');
        img_id= "imagent"+img_id;
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
                  html:`<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
                  confirmButtonText:'Ok'
                });
              }else{
                var base64data = reader.result;
                arrg_fotosExhT[id_Org] = base64data;
              }
              URL.revokeObjectURL(this.src);
            }
        });
    });
    
    /* ---------------------------------------------------------------------------------- */
    /* ---------------------------------------------------------------------------------- */
    /* Fotos Consulta Exhibidores */
   

    $('#div_detExh').on('change', '.file_ud', function() {
    var img_id = $(this).attr("id"),
        id_Org = 0;
    img_id = img_id.substring(11, img_id.length);
    id_Org = img_id;

    $("#filefotosud" + img_id).removeClass("is-invalid").addClass("is-valid");
    $("#error-mjsf-" + img_id).html('');
    arrg_cambioFoto[img_id] = 'SI';
    img_id = "imagenud" + img_id;

    if (this.files && this.files[0]) {
        ImageTools.resize(this.files[0], {
            width: 923,
            height: 503
        }, function(blob, didItResize) {
            if (!blob) {
                handleInvalidImage(img_id);
                return;
            }

            var tempUrl = window.URL.createObjectURL(blob);
            // Eliminar la palabra "sdv" de la URL temporal si existe
            var cleanedUrl = tempUrl.replace("sdv", "");

            var imgElement = document.getElementById(img_id);
            imgElement.src = cleanedUrl;

            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                if (!base64data) {
                    handleInvalidImage(img_id);
                } else {
                    arrg_fotosExhdu[id_Org] = base64data;
                }
                URL.revokeObjectURL(cleanedUrl);
            };
        });
    }

    function handleInvalidImage(img_id) {
        $("#" + img_id).attr("src", "../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        Swal.fire({
            title: '<strong>Atención!</strong>',
            icon: 'warning',
            html: `<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
            confirmButtonText: 'Ok'
        });
    }
});
    /*------------------------------------------------------------------------------------- */
    /* Exhibidor panoramica */
 
    $('#div_detExh').on('change', '.file_dd', function() {
    var img_id = $(this).attr("id"),
        id_Org = 0;
    img_id = img_id.substring(11, img_id.length);
    id_Org = img_id;

    $("#filefotosdd" + img_id).removeClass("is-invalid").addClass("is-valid");
    $("#d_error-mjsf-" + img_id).html('');
    arrg_cambioFotoD[img_id] = 'SI';
    img_id = "imagendd" + img_id;

    if (this.files && this.files[0]) {
        ImageTools.resize(this.files[0], {
            width: 923,
            height: 503
        }, function(blob, didItResize) {
            if (!blob) {
                handleInvalidImage(img_id);
                return;
            }

            var tempUrl = window.URL.createObjectURL(blob);
            // Eliminar la palabra "sdv" de la URL temporal si existe
            var cleanedUrl = tempUrl.replace("sdv", "");

            var imgElement = document.getElementById(img_id);
            imgElement.src = cleanedUrl;

            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                if (!base64data) {
                    handleInvalidImage(img_id);
                } else {
                    arrg_fotosExhdd[id_Org] = base64data;
                }
                URL.revokeObjectURL(cleanedUrl);
            };
        });
    }

    function handleInvalidImage(img_id) {
        $("#" + img_id).attr("src", "../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        Swal.fire({
            title: '<strong>Atención!</strong>',
            icon: 'warning',
            html: `<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
            confirmButtonText: 'Ok'
        });
    }
});


    /* Exhibidor Despues */
  

    $('#div_detExh').on('change', '.file_td', function() {
    var img_id = $(this).attr("id"),
        id_Org = 0;
    img_id = img_id.substring(11, img_id.length);
    id_Org = img_id;

    $("#filefotostd" + img_id).removeClass("is-invalid").addClass("is-valid");
    $("#d_error-mjsf-" + img_id).html('');
    arrg_cambioFotoT[img_id] = 'SI';
    img_id = "imagentd" + img_id;

    if (this.files && this.files[0]) {
        ImageTools.resize(this.files[0], {
            width: 923,
            height: 503
        }, function(blob, didItResize) {
            if (!blob) {
                handleInvalidImage(img_id);
                return;
            }

            var tempUrl = window.URL.createObjectURL(blob);
            // Eliminar la palabra "sdv" de la URL temporal si existe
            var cleanedUrl = tempUrl.replace("sdv", "");

            var imgElement = document.getElementById(img_id);
            imgElement.src = cleanedUrl;

            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                if (!base64data) {
                    handleInvalidImage(img_id);
                } else {
                    arrg_fotosExhdt[id_Org] = base64data;
                }
                URL.revokeObjectURL(cleanedUrl);
            };
        });
    }

    function handleInvalidImage(img_id) {
        $("#" + img_id).attr("src", "../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        Swal.fire({
            title: '<strong>Atención!</strong>',
            icon: 'warning',
            html: `<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
            confirmButtonText: 'Ok'
        });
    }
});



  });
</script>