<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <!--ESTILOS CSS-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_Reporte_Exhibidores.css')?>">
  <!--JAVASCRIPTS JS-->
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/js_reporte_v2.js') ?>"></script>
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
        <span id="usuario_log" style="color:#343A40;">#</span>
      </li>
    </ul>
  </nav>
  <center>
    <form id="form_lsClteCensados"><br>
      <div class="content_titulo">
          <span class="titulos">REPORTE DE COLOCACIÓN DE EXHIBIDORES</span>
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
              <select class="form-control" id="filtrodivision" name="filtrodivision" disabled>
                  <option value="">TODAS LAS DIVISIONES</option>
                  <option value="">.....</option>
                  <option value="">.....</option>
              </select>
            </div>
          </div>
          <div class="col-md">
            <label class="titulo_busqueda">CANAL:</label>
            <div id="S_filtroCanales">
              <select class="form-control" id="filtrocanales" name="filtrocanales" disabled>
                  <option value="">TODOS LOS CANALES</option>
                  <option value="">.....</option>
                  <option value="">.....</option>
              </select>
            </div>
          </div>
          <div class="col-md">
            <label class="titulo_busqueda">DISTRITOS:</label>
            <div id="S_filtroDistritos">
              <select class="form-control" id="filtrodistritos" name="filtrodistritos" disabled>
                  <option value="">TODOS LOS DISTRITOS</option>
                  <option value="">.....</option>
                  <option value="">.....</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid" style="margin-top: 20px;">
        <div class="row">
          <div class="col-md">
            <label class="titulo_busqueda">CÓDIGO:</label>
            <div id="S_filtroCodigo" class="input-group">
              <input class="form-control" type="text" name="filtrocodigos" id="filtrocodigos">
              <button id="btn_buscarCod" title="Buscar" type="button" class="btn btn-secondary" style="border-radius:0px"><span class="fas fa-search fa-lg"></span></button>
              <button id="btn_eliminarBus" title="Eliminar Búsqueda" type="button" class="btn btn-danger" style="border-radius:0px"><span class="fas fa-brush fa-lg"></span></button>
            </div>
          </div>
          <div class="col-md">
            <label class="titulo_busqueda">RUTAS:</label>
            <div id="S_filtroRuta">
              <select class="form-control" id="filtrorutas" name="filtrorutas" disabled>
                  <option value="">TODAS LAS RUTAS</option>
                  <option value="">.....</option>
                  <option value="">.....</option>
              </select>
            </div>
          </div>
          <div class="col-md">
            <label class="titulo_busqueda">TIPO DE EXHIBIDOR:</label>
            <div id="S_filtroTipoExhibidor">
              <select class="form-control" id="filtrotipoexhibidor" name="filtrotipoexhibidor">
                  <option value="">TODOS LOS TIPOS</option>
                  <option value="">.....</option>
                  <option value="">.....</option>
              </select>
            </div>
          </div>
          <div class="col-md">
            <label class="titulo_busqueda">EXHIBIDOR:</label>
            <div id="S_filtrExhibidor">
              <select class="form-control" id="filtroexhibidores" name="filtroexhibidores" disabled>
                  <option value="">TODOS LOS EXHIBIDORES</option>
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
  <div class="modal fade-scale show" id="ModalAbrirExpendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" style="100%">
      <div class="modal-content">
        <div class="modal-header d_arriba">
            <span class="modal-title" style="margin-top:-14px;"><span class="vya fas fa-folder-open fa-2x"></span> Información del cliente</span>
          <span id="X" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col col-md content-infog" id="content-infog" style="background-color:;"></div>
              <div class="col col-md content-infoe" id="content-infoe" style="background-color:;"></div>
            </div>
          </div>
          <div class="container-fluid" style="background-color:;">
            <div class="row">
              <div class="col col-md content-infof" id="content-infof">FOTO DEL EXHIBIDOR SELECCIONADO</div>
              <div class="col col-md content-infocpe" id="content-infocpe">
                <table id="mjs_div_exh" class="tabla_foto">
                    <tr>
                        <th class="titulo-foto">CAPACIDAD DEL EXHIBIDOR</th>
                    </tr>
                    <tr>
                        <td>
                          <img src="../dependencias/imagenes/icon_256.png" id="foto_exhibidor">
                        </td>
                    </tr>
                </table>
              </div>
            </div>
          </div>
          <h6 style="height: 44px;line-height: 40px;border: 1px solid #fff;color:#fff;margin:0 auto;text-align: center;margin-top: 7px;background-color: #474745;width: 98%;">UBICACIÓN DEL CLIENTE Y EXHIBIDOR</h6>
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