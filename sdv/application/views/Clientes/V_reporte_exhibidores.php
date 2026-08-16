<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <!--ESTILOS CSS-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_Reporte_Exhibidores.css')?>">
  <!--JAVASCRIPTS JS-->
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_Reporte_Exhibidores.js') ?>"></script>



</head>
<body background="<?php echo base_url('dependencias/imagenes/papyrus2.png'); ?>">
  <!--000000---DIV CARGANDO---000000-->
  <div id="content-carga" style="display:none;" class="carga-class">
    <div class="d-flex justify-content-center">
      <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
        <span class="sr-only">Cargando...</span>
       </div>
    </div>
  </div>
  <nav class="navbar navbar-dark bg-dark">
    <!-- Navbar content -->
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <span id="usuario_log">ADMINISTRADORES</span>
          </li>
    </ul>
  </nav>
<center>
  <form id="form_lsClteCensados">
 
 <br>
 <div class="content_titulo">
    <span class="titulos">REPORTE DE ACTUALIZACIÓN DE EXHIBIDORES</span>
 </div>

    <div class="container-fluid" id="FiltrosBusqueda">
      <div class="row">
        <div class="col-md">
          <label class="titulo_busqueda">PAÍS:</label>
          <div id="S_filtroPais">
            <select class="form-control" id="filtropais" name="filtropais">
                <option value="">TODOS LOS PAISES</option>
                <option value="">.....</option>
                <option value="">.....</option>
            </select>
          </div>
        </div>
        <div class="col-md">
          <label class="titulo_busqueda">DIVISIÓN:</label>
          <div id="S_filtroDivision">
            <select class="form-control" id="filtrodivision" name="filtrodivision">
                <option value="">TODAS LAS DIVISIONES</option>
                <option value="">.....</option>
                <option value="">.....</option>
            </select>
          </div>
        </div>
        <div class="col-md">
          <label class="titulo_busqueda">CANAL:</label>
          <div id="S_filtroCanales">
            <select class="form-control" id="filtrocanales" name="filtrocanales">
                <option value="">TODOS LOS CANALES</option>
                <option value="">.....</option>
                <option value="">.....</option>
            </select>
          </div>
        </div>
      </div>
    </div>

  <!--CLIENTES GENERAL UNO-->
<br>



    <br>
    <span class="titulos">Clientes Censados</span>
    <div id="Lista_GenralUnohtml"></div><br>
    <span class="titulos">Clientes Con ó Sin Exhibidor</span>
    <div id="Tabla_SinConExh"></div><br>
    <span class="titulos">Clientes Por Tipo de Actualización</span>
    <div id="Tabla_TipoActulizacion"></div><br>
    <span class="titulos">Clientes Por Tipo de Observación</span>
    <div id="Tabla_TipoObservacion"></div><br>
    <span class="titulos">Clientes Que Nó se Censaron</span>
    <div id="Tabla_noSePudoENTRAR"></div><br>
    <span class="titulos">Clientes Actualizados Por Día</span>
    <div id="TablaCliXdias"></div><br>
    <!-- <div class="content_titulo"> -->
      <span class="titulos">Lista de Clientes Censados</span>
    <!-- </div> -->
<!-- <div class="col-md-6" style="width: 50%;"> -->
    <div class="container-fluid" style="margin-top: 20px;">
      <div class="row">
          
      </div>
      <div class="row">


        <div class="col-md">
          <label class="titulo_busqueda">CÓDIGO:</label>
          <div id="S_filtroCodigo" class="input-group">
            <input class="form-control" type="text" name="filtrocodigos" id="filtrocodigos">
            <button id="btn_buscarCod" title="Buscar" type="button" class="btn btn-secondary" style="border-radius:0px"><span class="fas fa-search fa-lg"></span></button>
            <button id="btn_eliminarBus" title="Eliminar Búsqueda" type="button" class="btn btn-danger" style="border-radius:0px"><span class="fas fa-times fa-lg"></span></button>
          </div>
        </div>

        <div class="col-md">
          <label class="titulo_busqueda">DISTRITOS:</label>
          <div id="S_filtroDistritos">
            <select class="form-control" id="filtrodistritos" name="filtrodistritos">
                <option value="">TODOS LOS DISTRITOS</option>
                <option value="">.....</option>
                <option value="">.....</option>
            </select>
          </div>
        </div>
        <div class="col-md">
          <label class="titulo_busqueda">RUTAS:</label>
          <div id="S_filtroRuta" style="display: none;">
            <select class="form-control" id="filtrorutas" name="filtrorutas">
                <option value="">TODAS LAS RUTAS</option>
                <option value="">.....</option>
                <option value="">.....</option>
            </select>
          </div>
        </div>

      </div>
    </div>

    <div id="Content_tabla_lsClteCensados"></div>
    <div id="pag_tabla_LSCtleC"></div>
  </form>
</center>

    <!-- MODAL VER ESTATUS DE ENCUESTA-->
    <div class="modal fullscreen-modal" id="ModalAbrirExpendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document" >
        <div class="modal-content">
          <div class="modal-header d_arriba">
             <span class="modal-title" style="margin-top:-14px;"><span class="vya fas fa-folder-open fa-2x"></span> Información de encuesta</span>
            <span id="X" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col col-md" id="content-infog"></div>
                <div class="col col-md" id="content-infof"></div>
              </div>
            </div>
            <div class="container-fluid overflow-auto container_dos">
              <div class="row">
                  <div class="col col-md" id="content-infoe"></div>
              </div>
            </div>
            <h6 style="height: 44px;line-height: 40px;border: 1px solid #fff;color:#fff;margin:0 auto;text-align: center;margin-top: 7px;background-color: #474745;width: 98%;">UBICACIÓN DEL CLIENTE</h6>
            <div class="container-fluid">
              <div class="row" id="content-mapa">
              </div>
            </div>
          </div>
          <div class="modal-footer d_abajo">
          </div>
        </div>
      </div>
    </div>


<!--   <table class="table table-hover">
<thead>
  <tr>
    <th></th>
    <th></th>
    <th></th>
  </tr>
</thead>

<tbody>
    <tr data-toggle="collapse" data-target="#accordion" class="clickable">
        <td>Some Stuff</td>
        <td>Some more stuff</td>
        <td>And some more</td>
    </tr>
    <tr>
        <td colspan="3">
            <div id="accordion" class="collapse">Hidden by default</div>
        </td>
    </tr>
</tbody>
</table>
   -->

