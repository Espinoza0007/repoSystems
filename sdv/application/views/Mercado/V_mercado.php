<!--ESTILOS CSS-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_actuClientes.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/css/CSS_modalExhbidor.css') ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css'); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css'); ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('dependencias/gijgo-combined-1.9.11/css/gijgo.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/select2-4.0.7/css/select2.css') ?>">
<link rel="stylesheet" type="text/css"
    href="<?php echo base_url('dependencias/select2-4.0.7/css/select2-bootstrap.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('dependencias/leaflet/leaflet.css') ?>">
<!--JAVASCRIPTS JS-->
<script type="text/javascript" src="<?php echo base_url('dependencias/jquery-validation/dist/jquery.validate.js'); ?>">
</script>
<script type="text/javascript"
    src="<?php echo base_url('dependencias/jquery-validation/dist/additional-methods.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/gijgo-combined-1.9.11/js/gijgo.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/select2-4.0.7/js/select2.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url('dependencias/js/JS_mercado.js') ?>"></script>
<script type="text/javascript"
    src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript"
    src="<?php echo base_url('dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js'); ?>">
</script>
<script type="text/javascript"
    src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js'); ?>"></script>
<script type="text/javascript"
    src="<?php echo base_url('dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/leaflet/leaflet.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/FileSaver.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/SheetJs/js/Blob.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('dependencias/js/ImageTools.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<style>
    .image-preview-container {
    width: 200px; /* O el ancho que desees para la vista previa */
    height: 200px; /* O el alto que desees para la vista previa */
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden; /* Esto asegura que la imagen no se salga del contenedor */
    border: 1px solid #ccc; /* Solo para visualizar el contenedor, puedes eliminarlo si lo deseas */
    margin: 0 auto; /* Esto centrará el contenedor en el eje horizontal si tiene un contenedor padre de ancho mayor */
}

.image-preview {
    max-width: 100%;
    max-height: 100%;
}
.card_cti_ {
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

.btn_editar_cti {
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

.div_comentario {
    margin-top: 10px;
    background-color: #FFFB71;
    color: #2F2E0F;
    /* height:77px; */
    /* overflow: auto; */
    border-radius: 7px;
    font-size: 17px;
    font-weight: 600;
    padding: 10px;
    border: 1px solid #DAD64C;
}

/* .row div textarea{
        width: 100%;
        height: 107px;
    } */

.vya {
    color: #3F3F3F;
    margin-right: 3px;
    text-shadow: -1px -1px 1px rgba(255, 255, 255, .1), 1px 1px 1px rgba(0, 0, 0, .6);
}

.separador {
    background-color: #727070;
    width: 100%;
}

.lbl_fultima {}

label.error {
    color: red;
    font-size: 1rem;
    display: block;
    margin-top: 5px;
}

.error {
    font-size: 14px;
    color: red;
}

.border-blue {
    border: 2px solid #007bff;
    margin-right: 20px;
    box-sizing: border-box;
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

#reload {
    font-weight: bold;
}

#titulo_inv {
    margin-top: 20px;
}

.btn_invC {
    width: 100px;
    border-radius: 3px;
    font-weight: bold;
    font-size: 20px;
}

#cliboraddd {
    margin-top: 20px;
    width: 200px;
}

.mi-contenedor {
    margin-left: 20px;
    /* Ajusta a tu gusto */
    margin-right: 20px;
    /* Ajusta a tu gusto */
}

.card-border-green {
    border: 2px solid green;
    border-radius: 5px;
}

.card-border-red {
    border: 2px solid red;
    border-radius: 5px;
}

.competencia-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.competencia-group .left-labels {
    display: flex;
    align-items: center;
}

.competencia-group .right-labels {
    display: flex;
    align-items: center;
}

.competencia-group .right-labels label {
    margin-left: 10px;
}

.radio-container {
    display: flex;
    flex-direction: column;
}

.radio-group {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.radio-group label {
    margin-right: 10px;
}

.radio-group input[type="radio"] {
    margin-right: 5px;
}


#tbl_clientes_nuevos.dataTable tbody tr:hover {
    cursor: pointer;
}

#tbl_clientes_actualizacion.dataTable tbody tr:hover {
    cursor: pointer;
}

#tbl_clientes_nuevos_a.dataTable tbody tr:hover {
    cursor: pointer;
}

#tbl_clientes_actualizacion_a.dataTable tbody tr:hover {
    cursor: pointer;
}

.info_mapd {
    color: #0379B8;
    font-weight: 600;
}

.estado-activo,
.estado-finalizado,
.estado-no-asignado {
    border-radius: 25px;
    padding: 2% 5%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    width: 100px;  /* Fija un ancho */
    height: 40px;  /* Fija un alto */
    text-align: center;
}

.estado-activo {
    background-color: orange;
    /* Cambia el color de fondo a verde */
    border: 2px solid orange;
    
}

.estado-finalizado {
    background-color: green;
    /* Cambia el color de fondo a rojo */
    border: 2px solid green;
    
}

.estado-no-asignado {
   
    background-color: red;
    /* Cambia el color de fondo a naranja */
    border: 2px solid red;
}

.page-heading h3 {
    font-size: 1.5em;
    /* Tamaño de fuente base */
}

.table-responsive {
    overflow-x: auto;
    /* Asegura que la tabla pueda desplazarse si es más ancha que su contenedor */
}
.btn-fixed-width {
    width: 54px;  /* Ajusta el valor según tus necesidades */
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.error-input:hover {
    border: 2px solid red;
    /* Si también deseas cambiar el fondo al hacer hover: */
    /* background-color: rgba(255, 0, 0, 0.1); */
}

.error-input {
    border: 2px solid red;
    border-radius: 5px; /* Esto crea el borde redondeado */
    padding: 5px;
}




</style>

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
            <span id="uslogin"><?php echo  $user; ?></span>
        </div>
        <div style="margin:0 auto;" id="btn-pendinetes" onclick="enviar_cola_cti()" class="icons_posicion">
            <span class="fa fa-cloud-upload-alt fa-2x" style="color:#fff;"></span> <span style="font-size: 14px;"
                class="badge badge-pill badge-dark" id="RegisCola">0</span>
        </div>
        <div style="" class="icons_posicion" id="btn-menu-back">
            <span class="fa fa-bars fa-2x" style=""></span>
        </div>
    </nav>
    <!-- FIN BARRA MENU -->
    <!-- CONTENEDOR -->
    <!-- CONTENEDOR -->
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
            <!-- PHP code... -->

            <!-- SELECCION CLIENTE -->
            <div class="col-md-12 celda" id="frm_info_cti">
                <h5 class="card-header card-header-form">
                    Evaluación de Mercado
                </h5>
                <br>

                <!-- Cards for Crear Encuesta and Tareas -->
                <div class="d-flex flex-column align-items-start">

                    <!-- Card for Crear Encuesta -->
                    <div class="card mb-3" style="width: 18rem; border: 0px solid #007bff; margin-right: 20px;">

                        <div class="card border-blue text-center" style="border: 2px solid #007bff;">
                            <h5 class="card-title">Crear Encuesta</h5>
                            <a href="#" id="nuevaEncuesta" class="btn btn-primary btn-rounded">Ir a Crear Encuesta</a>
                        </div>
                    </div>

                   <!-- Card for Tareas -->
                    <div class="card mb-3" style="width: 18rem; border: 0px solid #007bff; margin-right: 20px;">
                        <div class="card border-blue text-center" style="border: 2px solid #007bff;"> 
                            <h5 class="card-title">Asignacion de Tareas</h5>
                            <a href="#" id="btnAsignar" class="btn btn-primary btn-rounded">Ir a Asignar</a>
                        </div>
                    </div>


                    <!-- Card for Tareas -->
                    <div class="card mb-3" style="width: 18rem; border: 0px solid #007bff; margin-right: 20px;">
                        <div class="card border-blue text-center" style="border: 2px solid #007bff;">
                            <h5 class="card-title">Tareas</h5>
                            <a href="#" id="btnTareas" class="btn btn-primary btn-rounded">Ir a Tareas</a>
                        </div>
                    </div>

                </div>
                <!-- End of the cards -->

                <br>
                <!-- En esta parte va la informacion -->
                <div id="contenedorDeTareas" class="mi-contenedor"></div>
            </div>
            <!-- FIN SELECCION CLIENTE -->
        </div>
    </div>
    <!-- FIN CONTENEDOR -->
    <!-- Modal de crear nueva encuesta -->
    <!-- Modal de crear nueva encuesta  -->
    <div class="modal fade" id="encuestaModal" tabindex="-1" aria-labelledby="encuestaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="encuestaModalLabel">Encuesta de Mercado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myForm" method="post" action="/Ctr_mercado/guardar_formulario"
                        enctype="multipart/form-data">
                        <!-- Formulario -->
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Información General
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <fieldset class="two-columns">
                                            <!-- Se trae de la base de datos  -->

                                            <div class="form-group">
                                                <label text-align="left" for="nombreuser">Nombre:</label>
                                                <input type="text" class="form-control two-columns" id="nombreuser"
                                                    name="nombreuser" readonly required>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control two-columns" id="ruta" name="ruta" readonly required style="display: none;">
                                            </div>


                                            <div class="form-group">
                                                <label for="countries">Pais:</label>
                                                <input type="text" class="form-control" id="countries" name="countries"
                                                    readonly required>
                                            </div>

                                            <div class="form-group">
                                                <label for="divisions">Division:</label>
                                                <input type="text" class="form-control" id="divisions" name="divisions"
                                                    readonly required>
                                            </div>

                                            <div class="form-group">
                                                <label for="distribuidora">Distribuidora:</label>
                                                <input type="text" class="form-control" id="distribuidora"
                                                    name="distribuidora" readonly required>
                                            </div>
                                            <!-- fin de se trae de la base de datos -->
                                            <div class="form-group">
                                                <label for="sector" style="display: none;">SECTOR:</label>
                                                <input type="text" class="form-control" id="sector" name="sector" required style="display: none;" value="n/a">
                                            </div>


                                            <div class="form-group">
                                                <label for="search-box6">CODIGO DE CLIENTE: Si es nuevo o no se tiene el
                                                    codigo colocar 0:</label>
                                                <input type="text" class="form-control" id="search-box6"
                                                    name="search-box6" autocomplete="off" required />
                                                <div id="suggesstion-box6"></div>
                                            </div>

                                            <div class="form-group">
                                                <label for="nombreEstablecimiento">NOMBRE DEL ESTABLECIMIENTO:</label>
                                                <input type="text" class="form-control" id="nombreEstablecimiento"
                                                    name="nombreEstablecimiento" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="direccion">DIRECCION:</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label for="tipoNegocio">TIPO DE NEGOCIO:</label>
                                                <input class="form-control" id="tipoNegocio" name="tipoNegocio"
                                                    required></input>
                                            </div>

                                        </fieldset>
                                        <!-- con formato  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Formulario -->

                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            Competencias
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                                    data-parent="#accordionExample">
                                    <div class="card-body">

                                        <fieldset class="two-columns">

                                            <div class="contenedor">

                                                <div class="competencia-group">
                                                    <div class="left-labels">
                                                        <label for="yummies">YUMMIES</label>
                                                    </div>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="yummiesSi" name="yummies"
                                                                value="1">Si</label>
                                                        <label><input type="radio" id="yummiesNo" name="yummies"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="fritolays">FRITO LAYS</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="fritolaysSi" name="fritolays"
                                                                value="2">Si</label>
                                                        <label><input type="radio" id="fritolaysNo" name="fritolays"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="diana">DIANA</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="dianaSi" name="diana"
                                                                value="3">Si</label>
                                                        <label><input type="radio" id="dianaNo" name="diana"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="barcel">BARCEL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="barcelSi" name="barcel"
                                                                value="4">Si</label>
                                                        <label><input type="radio" id="barcelNo" name="barcel"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="senorial">SEÑORIAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="senorialSi" name="senorial"
                                                                value="5">Si</label>
                                                        <label><input type="radio" id="senorialNo" name="senorial"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group" id="ricaSulaGroup" >
                                                    <label for="ricaSula">RICA SULA</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="ricaSulaSi" name="ricaSula"
                                                                value="6">Si</label>
                                                        <label><input type="radio" id="ricaSulaNo" name="ricaSula"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group" id="tropicalGroup">
                                                    <label for="tropical">TROPICAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="tropicalSi" name="tropical"
                                                                value="7">Si</label>
                                                        <label><input type="radio" id="tropicalNo" name="tropical"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group" id="yaEstaGroup">
                                                    <label for="esta">YA ESTA</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="yaestaSi" name="esta"
                                                                value="8">Si</label>
                                                        <label><input type="radio" id="yaestaNo" name="esta"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group" id="botanisGroup">
                                                    <label for="botanis">BOTANIS</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="botanisSi" name="botanis"
                                                                value="9">Si</label>
                                                        <label><input type="radio" id="botanisNo" name="botanis"
                                                                value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>OTRO</label><br>
                                                    <input type="text" class="form-control" id="otro" name="otro" value="n/a">
                                                </div>

                                            </div>
                                            <!-- fin de contenedor  -->

                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseThree" aria-expanded="true"
                                            aria-controls="collapseThree">
                                            Presencias
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseThree" class="collapse show" aria-labelledby="headingThree"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <fieldset class="two-columns">
                                            <fieldset class="two-columns">
                                                <div class="contenedor">
                                                    <div>
                                                    </div>

                                                    <div class="competencia-group">
                                                        <label for="bocadeli">BOCADELI (Snacks)</label>
                                                        <div class="right-labels">
                                                            <label><input type="radio" id="bocadeliSi" name="bocadeli"
                                                                    value="9">Si</label>
                                                            <label><input type="radio" id="bocadeliNo" name="bocadeli"
                                                                    value="0">No</label>
                                                        </div>
                                                    </div>

                                                    <div class="competencia-group">
                                                        <label for="nutriva">NUTRIVA (Cereales)</label>
                                                        <div class="right-labels">
                                                            <label><input type="radio" id="nutrivaSi" name="nutriva"
                                                                    value="10">Si</label>
                                                            <label><input type="radio" id="nutrivaNo" name="nutriva"
                                                                    value="0">No</label>
                                                        </div>
                                                    </div>

                                                    <div class="competencia-group">
                                                        <label for="pindi">GALLETA (Planeta Dulce)</label>
                                                        <div class="right-labels">
                                                            <label><input type="radio" id="pindiSi" name="pindi"
                                                                    value="11">Si</label>
                                                            <label><input type="radio" id="pindiNo" name="pindi"
                                                                    value="0">No</label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </fieldset>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario -->
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingFour">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseFour" aria-expanded="true"
                                            aria-controls="collapseFour">
                                            Ejecucion
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseFour" class="collapse show" aria-labelledby="headingFour"
                                    data-parent="#accordionExample">
                                    <div class="card-body">

                                        <fieldset class="two-columns">
                                            <h6>EJECUCION BOCADELI (Snacks)</h6>
                                            <div class="contenedor">
                                                <div class="competencia-group">
                                                    <label for="bocadeliExhibidorPrincipal">EXHIBIDOR PRINCIPAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="bocadeliExhibidorPrincipalSi"
                                                                name="bocadeliExhibidorPrincipal" value="12">Si</label>
                                                        <label><input type="radio" id="bocadeliExhibidorPrincipalNo"
                                                                name="bocadeliExhibidorPrincipal" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="bocadeliExhibidorAdicional">EXHIBIDOR ADICIONAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="bocadeliExhibidorAdicionalSi"
                                                                name="bocadeliExhibidorAdicional" value="13">Si</label>
                                                        <label><input type="radio" id="bocadeliExhibidorAdicionalNo"
                                                                name="bocadeliExhibidorAdicional" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="bocadeliExhibicionAdecuada">EXHIBICION ADECUADA</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="bocadeliExhibicionAdecuadaSi"
                                                                name="bocadeliExhibicionAdecuada" value="14">Si</label>
                                                        <label><input type="radio" id="bocadeliExhibicionAdecuadaNo"
                                                                name="bocadeliExhibicionAdecuada" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="bocadeliPosicionDominante">POSICION DOMINANTE</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="bocadeliPosicionDominanteSi"
                                                                name="bocadeliPosicionDominante" value="15">Si</label>
                                                        <label><input type="radio" id="bocadeliPosicionDominanteNo"
                                                                name="bocadeliPosicionDominante" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="bocadeliPop">POP</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="bocadeliPopSi" name="bocadeliPop"
                                                                value="19">Si</label>
                                                        <label><input type="radio" id="bocadeliPopNo" name="bocadeliPop"
                                                                value="0">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <h6>EJECUCION NUTRIVA</h6>

                                            <div class="contenedor">
                                                <div class="competencia-group">
                                                    <label for="nutrivaExhibidorPrincipal">EXHIBIDOR PRINCIPAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="nutrivaExhibidorPrincipalSi"
                                                                name="nutrivaExhibidorPrincipal" value="16">Si</label>
                                                        <label><input type="radio" id="nutrivaExhibidorPrincipalNo"
                                                                name="nutrivaExhibidorPrincipal" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="nutrivaExhibidorAdicional">EXHIBIDOR ADICIONAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="nutrivaExhibidorAdicionalSi"
                                                                name="nutrivaExhibidorAdicional" value="17">Si</label>
                                                        <label><input type="radio" id="nutrivaExhibidorAdicionalNo"
                                                                name="nutrivaExhibidorAdicional" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="nutrivaExhibicionAdecuada">EXHIBICION ADECUADA</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="nutrivaExhibicionAdecuadaSi"
                                                                name="nutrivaExhibicionAdecuada" value="18">Si</label>
                                                        <label><input type="radio" id="nutrivaExhibicionAdecuadaNo"
                                                                name="nutrivaExhibicionAdecuada" value="0">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <h6>GALLETA (Planeta Dulce)</h6>

                                            <div class="contenedor">
                                                <div class="competencia-group">
                                                    <label for="galletaExhibidorPrincipal">EXHIBIDOR PRINCIPAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="galletaExhibidorPrincipalSi"
                                                                name="galletaExhibidorPrincipal" value="20">Si</label>
                                                        <label><input type="radio" id="galletaExhibidorPrincipalNo"
                                                                name="galletaExhibidorPrincipal" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="galletaExhibidorAdicional">EXHIBIDOR ADICIONAL</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="galletaExhibidorAdicionalSi"
                                                                name="galletaExhibidorAdicional" value="21">Si</label>
                                                        <label><input type="radio" id="galletaExhibidorAdicionalNo"
                                                                name="galletaExhibidorAdicional" value="0">No</label>
                                                    </div>
                                                </div>

                                                <div class="competencia-group">
                                                    <label for="galletaExhibicionAdecuada">EXHIBICION ADECUADA</label>
                                                    <div class="right-labels">
                                                        <label><input type="radio" id="galletaExhibicionAdecuadaSi"
                                                                name="galletaExhibicionAdecuada" value="22">Si</label>
                                                        <label><input type="radio" id="galletaExhibicionAdecuadaNo"
                                                                name="galletaExhibicionAdecuada" value="0">No</label>
                                                    </div>
                                                </div>
                                            </div>


                                        </fieldset>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario -->
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingFive">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseFive" aria-expanded="true"
                                            aria-controls="collapseFive">
                                            Frecuencia de visita
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseFive" class="collapse show" aria-labelledby="headingFive"
                                    data-parent="#accordionExample">
                                    <div class="card-body">

                                        <fieldset class="one-columns">

                                            <fieldset style="display: flex;flex-direction:column;">
                                                
                                                    <label>
                                                        <input type="radio" class="radiobox" name="frecuenciaVisita"
                                                            value="1">
                                                        <span>Semanal</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" class="radiobox" name="frecuenciaVisita"
                                                            value="2">
                                                        <span>Quincenal</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" class="radiobox" name="frecuenciaVisita"
                                                            value="3">
                                                        <span>Mensual</span>
                                                    </label>

                                            </fieldset>



                                            <fieldset>
                                                <label for="compraSemanal">Compra semanal $:</label>
                                                <input type="number" class="form-control" id="compraSemanal"
                                                    name="compraSemanal">
                                            </fieldset>
                                        </fieldset>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario -->
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingSix">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                            Tipo de compra de la competencia
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseSix" class="collapse show" aria-labelledby="headingSix"
                                    data-parent="#accordionExample">
                                    <div class="card-body">

                                        <fieldset class="two-columns">
                                            <div class="contenedor">

                                                <label>DIANA: </label><br>
                                                <div class="radio-group">
                                                    <input type="radio" id="dianaCredito" name="dianaCompra" value="1">
                                                    <label for="dianaCredito">Credito</label>
                                                    <input type="radio" id="dianaContado" name="dianaCompra" value="2">
                                                    <label for="dianaContado">Contado</label>
                                                    <input type="radio" id="dianaNA" name="dianaCompra" value="3">
                                                    <label for="dianaNA">N/A</label>
                                                </div>

                                                <label>FRITO LAY: </label><br>
                                                <div class="radio-group">
                                                    <input type="radio" id="fritoLayCredito" name="fritoLayCompra"
                                                        value="1">
                                                    <label for="fritoLayCredito">Credito</label>
                                                    <input type="radio" id="fritoLayContado" name="fritoLayCompra"
                                                        value="2">
                                                    <label for="fritoLayContado">Contado</label>
                                                    <input type="radio" id="fritoLayNA" name="fritoLayCompra" value="3">
                                                    <label for="fritoLayNA">N/A</label>
                                                </div>

                                                <label>YUMMIES: </label><br>
                                                <div class="radio-group">
                                                    <input type="radio" id="yummiesCredito" name="yummiesCompra"
                                                        value="1">
                                                    <label for="yummiesCredito">Credito</label>
                                                    <input type="radio" id="yummiesContado" name="yummiesCompra"
                                                        value="2">
                                                    <label for="yummiesContado">Contado</label>
                                                    <input type="radio" id="yummiesNA" name="yummiesCompra" value="3">
                                                    <label for="yummiesNA">N/A</label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="listaOportunidades">OPORTUNIDADES
                                                        ENCONTRADAS/SEGUIMIENTO-ACCIONES REALIZADAS:</label>
                                                    <select class="form-control" name="listaOportunidades"
                                                        id="listaOportunidades">
                                                        <option value="">Seleccione</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="oportunidades">Descripcion</label><br>
                                                    <textarea id="oportunidades" class="form-control"
                                                        name="oportunidades" rows="4" cols="50"></textarea><br>
                                                </div>

                                                <div class="form-group">
                                                    <label for="fechaSeguimiento">FECHA DE SEGUIMIENTO</label><br>
                                                    <input type="date" class="form-control" id="fechaSeguimiento"
                                                        name="fechaSeguimiento"><br>
                                                </div>
                                            </div>
                                        </fieldset>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Formulario -->
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseSeven" aria-expanded="true"
                                            aria-controls="collapseSeven">
                                            Información Adicional
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapseSeven" class="collapse show" aria-labelledby="headingSeven"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <fieldset class="two-columns">
                                            <fieldset>
                                                <h6>Coordenadas GPS</h6>
                                                <label for="latitud">Latitud (x.y °)</label><br>
                                                <input type="text" class="form-control" id="latitud" name="latitud"
                                                    readonly><br>

                                                <label for="longitud">Longitud (x.y °)</label><br>
                                                <input type="text" class="form-control" id="longitud" name="longitud"
                                                    readonly><br>

                                                <br><input class="btn btn-secondary" style="position: relative center;"
                                                    type="button" id="actualizar" value="Actualizar Coordenadas"><br>

                                                <br>
                                                <!-- <div id="map" style="height: 400px;"></div> -->


                                                <h6>Fotografías</h6>
                                                <!-- Foto 1 -->
                                                <div class="mb-3">
                                                    <label for="photo1" class="form-label">Foto 1</label>
                                                    <input class="form-control" type="file" id="file_foto_u" name="file_foto_u" accept="image/*" capture="camera" onchange="previewImage('file_foto_u', 'preview_photo1')">
                                                    <img id="preview_photo1" style="display: none; width: 100%; margin-top: 10px;" />
                                                </div>

                                                <!-- Foto 2 -->
                                                <div class="mb-3">
                                                    <label for="photo2" class="form-label">Foto 2</label>
                                                    <input class="form-control" type="file" id="file_foto_d" name="file_foto_d" accept="image/*" capture="camera" onchange="previewImage('file_foto_d', 'preview_photo2')">
                                                    <img id="preview_photo2" style="display: none; width: 100%; margin-top: 10px;" />
                                                </div>

                                                <!-- Foto 3 -->
                                                <div class="mb-3">
                                                    <label for="photo3" class="form-label">Foto 3</label>
                                                    <input class="form-control" type="file" id="file_foto_t" name="file_foto_t" accept="image/*" capture="camera" onchange="previewImage('file_foto_t', 'preview_photo3')">
                                                    <img id="preview_photo3" style="display: none; width: 100%; margin-top: 10px;" />
                                                </div>



                                            </fieldset>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Formulario -->

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnGuardarEncuesta" class="btn btn-primary">Guardar</button>
                    <button type="button" id="btnCerrarModal" class="btn btn-primary">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN Modal de crear nueva encuesta -->
    <!-- Modal de asignacion  de tareas  -->
    <!-- Modal de asignacion  de tareas -->
    <div class="modal fade" id="completarTareas" tabindex="-1" role="dialog" aria-labelledby="assignTaskModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTaskModalLabel">Asignacion de Tareas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- inicio del modal body -->
                    <div id="main" class="my-div">
                        <div class="page-heading my-div">
                            <div class="row">
                                <button class="btn btn-block btn-sm btn-default btn-flat border-primary" id="new_task"
                                    data-toggle="modal" data-target="#assignTaskModal" style="display: none;"><i
                                        class="fa fa-plus"></i> Agregar Nueva Tarea</button>
                            </div>
                        </div>

                        <div class="row my-div">
                            <!-- Inicio de la tabla   -->
                            <table id="list" class="display table" style="width:100%">
                                <thead>
                                    <tr role="row">
                                        <th>Tipo de solicitud</th>
                                        <th>Cliente</th>
                                        <th>Tarea</th>
                                        <th>Fecha</th>
                                        <th>Asignado a</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                                <tfoot>
                                    <tr role="row">
                                        <th>Tipo de solicitud</th>
                                        <th>Cliente</th>
                                        <th>Tarea</th>
                                        <th>Fecha</th>
                                        <th>Asignado a</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- Tabla para los datos  -->
                        </div>
                    </div>

              <!-- Modal de asignar tareas  -->
                    <div id="assignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Asignar tarea</h5>
                                    <button  type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <form id="assignForm">
                                            <div class="row">
                                                <input type="hidden" id="filaId" name="filaId">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="search-box5">Ruta:</label>
                                                        <input type="text" class="form-control" id="search-box5"
                                                            name="search-box5" autocomplete="off" required />
                                                        <div id="suggesstion-box5"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="comentario">Comentario:</label>
                                                        <textarea class="form-control" id="comentario" name="comentario"
                                                            rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="cerrarAsignar" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary btn-asignarRuta">Asignar tarea</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Fin del Modal -->

                    <!-- Modal ver detalles   -->

                <!-- Fin del Modal -->

                <!-- modal nueva tarea -->
                <div class="modal fade" id="assignTaskModal" tabindex="-1" role="dialog"
                    aria-labelledby="assignTaskModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignTaskModalLabel">Crear tareas</h5>
                                <button type="button" class="close_modal" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="taskForm" action="Tareas_ctr/create" method="POST">
                                    <!-- Aquí puedes incluir los campos necesarios para la asignación de tarea a ruta -->
                                    <div class="form-group">
                                        <label for="search-box6">CODIGO DE CLIENTE: </label>
                                        <input type="text" class="form-control" id="search-box6" name="search-box6"
                                            autocomplete="off" required />
                                        <div id="suggesstion-box6"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombreEstablecimiento">NOMBRE DEL ESTABLECIMIENTO:</label>
                                        <input type="text" class="form-control" id="nombreEstablecimiento_tarea"
                                            name="nombreEstablecimiento" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion_tarea">DIRECCION:</label>
                                        <input type="text" class="form-control" id="direccion_tarea" name="direccion_tarea"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha">Fecha:</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha">
                                    </div>
                                    <div class="form-group">
                                        <label for="tarea">Tarea:</label>
                                        <textarea class="form-control" id="tarea" name="tarea" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="search-box">Ruta a asignar:</label>
                                        <input type="text" class="form-control" id="search-box" name="search-box"
                                            autocomplete="off" required />
                                        <div id="suggesstion-box"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombreVendedor_modal">Nombre del vendedor:</label>
                                        <input type="text" class="form-control" id="nombreVendedor_modal"
                                            name="nombreVendedor_modal" readonly required>
                                    </div>
                                    <!-- Agrega aquí los demás campos necesarios para la asignación de tarea a ruta -->

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary-crear crear"
                                            style="border: 1px solid blue;">Crear</button>
                                        <button type="button" class="btn  border btn-secondary-close"
                                            data-dismiss="modal">Cerrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- fin del modal body -->
                </div>
            </div>
        </div>
        </div>
        </div>
    <!-- Fin del Modal -->
    <!-- Fin del Modal -->
    <!-- Fin del Modal -->

    <!-- modal nueva tarea -->
    <!-- modal nueva tarea -->
    <!-- modal nueva tarea -->
    <div class="modal fade" id="assignTaskModal" tabindex="-1" role="dialog" aria-labelledby="assignTaskModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTaskModalLabel">Crear tareas</h5>
                    <button type="button" class="close_modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="taskForm" action="Tareas_ctr/create" method="POST">
                        <!-- Aquí puedes incluir los campos necesarios para la asignación de tarea a ruta -->
                        <div class="form-group">
                            <label for="search-box6">CODIGO DE CLIENTE: </label>
                            <input type="text" class="form-control" id="search-box6" name="search-box6" autocomplete="off"
                                required />
                            <div id="suggesstion-box6"></div>
                        </div>
                        <div class="form-group">
                            <label for="nombreEstablecimiento">NOMBRE DEL ESTABLECIMIENTO:</label>
                            <input type="text" class="form-control" id="nombreEstablecimiento_tarea"
                                name="nombreEstablecimiento" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion_tarea">DIRECCION:</label>
                            <input type="text" class="form-control" id="direccion_tarea" name="direccion_tarea" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha">
                        </div>
                        <div class="form-group">
                            <label for="tarea">Tarea:</label>
                            <textarea class="form-control" id="tarea" name="tarea" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="search-box">Ruta a asignar:</label>
                            <input type="text" class="form-control" id="search-box" name="search-box" autocomplete="off"
                                required />
                            <div id="suggesstion-box"></div>
                        </div>
                        <div class="form-group">
                            <label for="nombreVendedor_modal">Nombre del vendedor:</label>
                            <input type="text" class="form-control" id="nombreVendedor_modal" name="nombreVendedor_modal"
                                readonly required>
                        </div>
                        <!-- Agrega aquí los demás campos necesarios para la asignación de tarea a ruta -->

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary-crear crear"
                                style="border: 1px solid blue;">Crear</button>
                            <button type="button" class="btn  border btn-secondary-close"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </body>
    <!-- FIN Modal de asignacion  de tareas -->

    <!-- Modal de asignar tareas  -->
    <div id="assignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Asignar tarea</h5>
                            <button id="cerrarAsig" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <form id="assignForm" action="<?php echo base_url('index.php/Tareas_ctr/asignar'); ?>">
                                    <div class="row">
                                    <input type="hidden" id="filaId" name="filaId">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="search-box5">Ruta:</label>
                                                <input type="text" class="form-control" id="search-box5" name="search-box5" autocomplete="off" required />
                                                <div id="suggesstion-box5"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="nombreVendedor">Nombre del vendedor:</label>
                                                <input type="text" class="form-control" id="nombreVendedor" name="nombreVendedor" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="comentario">Comentario:</label>
                                                <textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-asignar">Asignar tarea</button>
                        <!-- <button type="button" class="btn btn-success" onclick="shareInfoOnWhatsApp()">Compartir por WhatsApp</button> Botón de compartir -->
                    
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin del Modal asignar tareas -->
                <!-- Modal ver detalles   -->
                <div id="viewModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">Detalles de la tarea</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                <input type="hidden" id="filaId" name="filaId">
                                <div class="form-group">
                                    <label for="oportunidad"><strong>Oportunidad:</strong></label>
                                    <p id="oportunidad" name="oportunidad"></p>
                                </div>

                                <div class="form-group">
                                    <label for="estado"><strong>Estado:</strong></label>
                                    <p id="estado" name="estado"></p>
                                </div>

                                <div class="form-group">
                                    <label for="nombreE"><strong>Nombre del Establecimiento:</strong></label>
                                    <p id="nombreE" name="nombreE"></p>
                                </div>

                                <div class="form-group">
                                    <label for="dir"><strong>Dirección:</strong></label>
                                    <p id="dir" name="dir"></p>
                                </div>

                                <div class="form-group">
                                    <label for="fecha"><strong>Fecha:</strong></label>
                                    <p id="fec" name="fec"></p>
                                </div>

                                <div class="form-group">
                                    <label for="ruta_asig"><strong>Ruta Asignada:</strong></label>
                                    <p id="ruta_asig" name="ruta_asig"></p>
                                </div>      

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="asignado_a"><strong>Asignado a:</strong></label>
                                        <p id="asignado_a" name="asignado_a"></p>
                                    </div>

                                    <div class="form-group">
                                        <label for="comen"><strong>Comentario:</strong></label>
                                        <p id="comen" name="comen"></p>
                                    </div>

                                </div>


                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Fotos Antes</h6>
                                    <div class="col-md-6">
                                        <label for="foto_unoModal">Foto Uno:</label>
                                        <img id="foto_unoModal" name="foto_unoModal" src="" alt="Foto Uno" style="max-width: 100%;">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="foto_dosModal">Foto Dos:</label>
                                        <img id="foto_dosModal" name="foto_dosModal" src="" alt="Foto Dos" style="max-width: 100%;">
                                    </div>
                            

                                    <div class="col-md-6">
                                        <label for="foto_tresModal">Foto Tres:</label>
                                        <img id="foto_tresModal" name="foto_tresModal" src="" alt="Foto Tres" style="max-width: 100%;">
                                    </div>

                                </div>
                                </div>
                                <div class="col-md-6">
                                <h6>Fotos Después</h6>
                                    <div class="form-group">
                                        <label for="foto_cua">Foto Uno:</label>
                                        <img id="foto_cua" class="img-fluid" src="" alt="Foto Cuatro">
                                    </div>
                                    <div class="form-group">
                                        <label for="foto_cin">Foto Dos:</label>
                                        <img id="foto_cin" class="img-fluid" src="" alt="Foto Cinco">
                                    </div>
                                    <div class="form-group">
                                        <label for="foto_sei">Foto Tres:</label>
                                        <img id="foto_sei" class="img-fluid" src="" alt="Foto Seis">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="longit"><strong>Longitud:</strong></label>
                                <input type="text" id="longit" name="longit" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="latit"><strong>Latitud:</strong></label>
                                <input type="text" id="latit" name="latit" class="form-control" readonly>
                            </div>

                            <div id="mapaVer" style="height: 400px;"></div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tareasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl"> <!-- modal-xl para un modal más grande -->
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tareas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="contenedorDeTareasC">
                        <!-- Aquí es donde insertaremos las tarjetas -->
                    </div>
                    <div class="modal-footer">
                        <button id="CerrarTarea" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Modal de completar tarea -->
            <!-- Modal de completar tarea -->
            <!-- Modal de completar tarea -->
    <div id="completeTaskModal" class="modal fade" tabindex="-1" aria-labelledby="completeTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeTaskModalLabel">Completar Tarea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="completeTaskForm">
                        <div class="mb-3 border border-success rounded">
                            <label for="tareaDes" class="form-label">Tarea:</label>
                            <textarea class="form-control" id="tareaDes" readonly style="resize: none; overflow: auto;"></textarea>
                        </div>
                        
                        <!-- id -->
                        <div class="mb-3 d-none">
                            <textarea class="form-control" id="id_tarea_c" readonly style="resize: none; overflow: auto;"></textarea>
                        </div>
                        <!-- fotos -->
                        <div class="mb-3">
                            <label for="photo1" class="form-label">Foto 1</label>
                            <input class="form-control" type="file" id="photo1" name="file_photo1" accept="image/*" capture="camera">
                            <img id="preview_photo1" style="display: none; width: 100%; margin-top: 10px;" />
                        </div>
                        <div class="mb-3">
                            <label for="photo2" class="form-label">Foto 2</label>
                            <input class="form-control" type="file" id="photo2" name="file_photo2" accept="image/*" capture="camera">
                            <img id="preview_photo2" style="display: none; width: 100%; margin-top: 10px;" />
                        </div>
                        <div class="mb-3">
                            <label for="photo3" class="form-label">Foto 3</label>
                            <input class="form-control" type="file" id="photo3" name="file_photo3" accept="image/*" capture="camera">
                            <img id="preview_photo3" style="display: none; width: 100%; margin-top: 10px;" />
                        </div>
                        <!-- cometario  -->
                        <div class="mb-3 border border-success rounded">
                            <label for="tareaDes" class="form-label">Comentario:</label>
                            <textarea class="form-control" id="tareaDes"  style="resize: none; overflow: auto;"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnCerrarCe" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="completarTarea" type="button" class="btn btn-primary">Completar Tarea</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FIN Modal de completar tarea -->
</body>

<script>
$("#fotosAnexos").on('change', '.custom-file-input', function() {
    console.log('Agregando foto');
    var inputFile = this;
    var imgContainer = $(this).closest('.custom-file');
    var imgPreview = imgContainer.find('.preview-image');

    ImageTools.resize(this.files[0], {
        width: 923,
        height: 503
    }, function(blob, didItResize) {
        imgPreview.attr('src', window.URL.createObjectURL(blob));
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
            if (!blob) {
                imgPreview.attr('src', '../dependencias/imagenes/file_3_icon-icons.com_68952.png');
                Swal.fire({
                    title: '<strong>Atención!</strong>',
                    type: 'warning',
                    html: 'Por favor vuelve a tomar foto',
                    confirmButtonText: 'Ok'
                });
            } else {
                var base64data = reader.result;
                var inputName = inputFile.name;
                $('#' + inputName).val(base64data);
            }
            URL.revokeObjectURL(imgPreview.attr('src'));
        }
    });
});
</script>