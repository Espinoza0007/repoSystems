<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <!--ESTILOS CSS-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_pedido_sugerido.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">
  <!--JAVASCRIPTS JS-->
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/DB_pedido_sugerido.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_pedido_sugerido.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>
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
            <div style="margin:0 auto;" id="btn-pendinetes" onclick="EnvioCola()" class="icons_posicion">
                <span class="fa fa-cloud-upload-alt fa-2x" style="color:#fff;"></span> <span style="font-size: 14px;" class="badge badge-pill badge-dark" id="RegisCola">0</span>
            </div>
            <div style="" class="icons_posicion" id="btn-menu-back">
                <span class="fa fa-bars fa-2x" style=""></span>
            </div>
        </div>
    </nav>
    <div>
        <h5 id="titulo_pd">
            Carga Óptima
        </h5>
    </div>
    <div class="col" id="S_filtroFamilias" align="center" style="position:fixed;background-color:#fff;height:82px;z-index:1;margin:0 auto;">
        <div class="gretro-select">
            <select id='cb_familia' class="form-control">
                <option value='1'>TODAS LAS FAMILIAS</option>
            </select>
        </div>		
    </div>
    <div class="container">
        <div class="row">
            <div style="margin-top:70px;">
                <form id="form_pedidos">
                    <table id="DgrTableProductos" style="width:100%">
                        <thead class="thead-dark" style='display:none;'>
                            <th scope="col">Producto</th>
                            <!-- <th scope="col">Fam.</th> -->
                        </thead>
                        <tbody id="showDataPSug">
                        </tbody>
                    </table>
                    <!-- </div id="detalle_pedido" style="background-color:red;"></div> -->

                </form>
            </div>
            <button type="button" aria-pressed="false" class="btngr_azul_pd" id="btn_pedido">
                <span class="fas fa-paper-plane fa-lg" style="padding: 10px;"></span>
                Enviar Pedido
            </button>
        </div>
    </div>
</div>

