var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
var TipoAc_Exh = 1,
    ContaExh = 0,
    IdCliente = '',
    token_ExhEspec = '',
    bandera_tipoAC = 0,
    id_Editando = 0,
    tipo_edicion = 0;
var arrg_datCli = [],
    arrg_fotosExh = [],
    arrg_fotosExhD = [],
    arrg_fotosExhT = [],
    arrg_fotosExhdu = [],
    arrg_fotosExhdd = [],
    arrg_fotosExhdt = [],
    arrg_datExhDevu = [],
    arrg_cambioFoto = [],
    arrg_cambioFotoD = [],
    arrg_cambioFotoT = [];
var cantidadDe = 0;
var Agregar_Exh = [],
    Actualizar_Exh = [],
    arrg_tipofoto = [];
var CantCola = 0;
var arrgColaM = [],
    arrgColaE = [];
var Arrg_Mantenimiento = [];
var Ok_Eliminado = 0;
var Ok_devolucion = 0;
var ColaMarcacion = [],
    ColaExhibidores = [];
var C_V_mjs = ``;
var arrg_capacidadExh = [];
var ContaCambios = 0;
var CuantoExhCola = 0;
var warn_on_unload = '';
var map;
var marker;
var key_Cli_Id = 0;
var dataCli = [];
var ValiConSinExh = 0;
var Opcion_Dev = 1; // 1 = PRIMERA, 2 = SEGUNDA
window.onbeforeunload = function () {
    if (warn_on_unload != '') {
        return warn_on_unload;
    }
}
$(document).ready(function () {
    init();
    DB_IniciarCPSesionExh();

    $(document).on("click", "#btn-menu-back", function () {
        location.href = "menu";
        $.when($(".carga-esconder").stop(true, true).hide()).done(function (x) {
            $.when($(".carga-class").stop(true, true).show()).done(function (x) {});
        });
    });
    $('#div_detExh').on('click', '.bloc_cheq', function () {
        return false;
    });
    $(document).on('click', '#mostrar-modal', function () {
        $("#modal").attr('style', 'top:0');
        $("#equis").attr('style', 'display: block;');
    });
    $(document).on('click', '#cerrar-modal', function () {
        $("#modal").attr('style', 'top: -100vh');
    });
    /*----000---- VALIDACIONES PRIMERA TOMA DE DATOS ----000-------*/
    $(document).on('click', '.chkpri', function () {
        var id_checkpri = $(this).attr("id");
        id_checkpri = id_checkpri.substring(13, id_checkpri.length);
        var Is_Cheked_G = false;
        Is_Cheked_G = document.getElementById('checktipoexhg' + id_checkpri).checked;
        var Is_Cheked_S = false;
        Is_Cheked_S = document.getElementById('checktipoexhs' + id_checkpri).checked;
        var Is_Cheked_C = false;
        Is_Cheked_C = document.getElementById('checktipoexhc' + id_checkpri).checked;
        var Is_Cheked_O = false;
        Is_Cheked_O = document.getElementById('checktipoexho' + id_checkpri).checked;
        var contaCheck = 0;
        (Is_Cheked_G) ? contaCheck += 1 : contaCheck += 0;
        (Is_Cheked_S) ? contaCheck += 1 : contaCheck += 0;
        (Is_Cheked_C) ? contaCheck += 1 : contaCheck += 0;
        (Is_Cheked_O) ? contaCheck += 1 : contaCheck += 0;
        if (contaCheck > 0) {
            $("#checktipoexhg" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
            $("#checktipoexhs" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
            $("#checktipoexhc" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
            $("#checktipoexho" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
        } else {
            $("#checktipoexhg" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
            $("#checktipoexhs" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
            $("#checktipoexhc" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
            $("#checktipoexho" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
        }
    });
    $(document).on("keyup", "input[name='txtrt[]']", function () {
        var id_rt = $(this).attr("id");
        id_rt = id_rt.substring(5, id_rt.length);
        V_input_Entero(id_rt, $("#txtrt" + id_rt).val(), 'txtrt', 'error-mjsr-', 'RISTRA', $("#txtidcat_desc" + id_rt).val());
    });
    $(document).on("keyup", "input[name='txtpq[]']", function () {
        var id_pq = $(this).attr("id");
        id_pq = id_pq.substring(5, id_pq.length);
        V_input_Entero(id_pq, $("#txtpq" + id_pq).val(), 'txtpq', 'error-mjsp-', 'PAQUETE', $("#txtidcat_desc" + id_pq).val());
    });
    $(document).on("keyup", "input[name='txtpines[]']", function () {
        var id_pn = $(this).attr("id");
        id_pn = id_pn.substring(8, id_pn.length);
        V_input_Entero(id_pn, $("#txtpines" + id_pn).val(), 'txtpines', 'error-mjspn-', 'PINES', $("#txtidcat_desc" + id_pn).val());
    });
    $(document).on("keyup", "input[name='txtun[]']", function () {
        var id_un = $(this).attr("id");
        id_un = id_un.substring(5, id_un.length);
        V_input_Entero(id_un, $("#txtun" + id_un).val(), 'txtun', 'error-mjsun-', 'UNIDADES', $("#txtidcat_desc" + id_un).val());
    });
    $(document).on("keyup", "input[name='txtbolsas[]']", function () {
        var id_bl = $(this).attr("id");
        id_bl = id_bl.substring(9, id_bl.length);
        V_input_Entero(id_bl, $("#txtbolsas" + id_bl).val(), 'txtbolsas', 'error-mjsbl-', 'BOLSAS', $("#txtidcat_desc" + id_bl).val());
    });
    $(document).on("keyup", "input[name='txtcaras[]']", function () {
        var id_cr = $(this).attr("id");
        id_cr = id_cr.substring(8, id_cr.length);
        V_input_EnteroMYQc(id_cr, $("#txtcaras" + id_cr).val(), 'txtcaras', 'error-mjscara-', 'CARAS DE EXHIBICION', $("#txtidcat_desc" + id_cr).val());
    });
    $(document).on("keyup", "input[name='txtbotes[]']", function () {
        var id_cr = $(this).attr("id");
        id_cr = id_cr.substring(8, id_cr.length);
        V_input_Entero(id_cr, $("#txtbotes" + id_cr).val(), 'txtbotes', 'error-mjsbt-', 'BOTES', $("#txtidcat_desc" + id_cr).val());
    });
    $(document).on("change", "select[name='observacion_exh[]']", function () {
        var id_obser = $(this).attr("id");
        id_obser = id_obser.substring(15, id_obser.length);
        if (!_empty($('#observacion_exh' + id_obser).val())) {
            $('#observacion_exh' + id_obser).removeClass("is-invalid").addClass("is-valid");
        } else {
            $('#observacion_exh' + id_obser).removeClass("is-valid").addClass("is-invalid");
        }
    });
    /*-------000000-------000000-------0000000------000000-------000000------*/
    /*----000---- VALIDACIONES SEGUNDA TOMA DE DATOS ----000-------*/
    $(document).on('click', '.chksegu', function () {
        var id_checkpri = $(this).attr("id");
        id_checkpri = id_checkpri.substring(15, id_checkpri.length);
        var Is_Cheked_G = false;
        Is_Cheked_G = document.getElementById('d_checktipoexhg' + id_checkpri).checked;
        var Is_Cheked_S = false;
        Is_Cheked_S = document.getElementById('d_checktipoexhs' + id_checkpri).checked;
        var Is_Cheked_C = false;
        Is_Cheked_C = document.getElementById('d_checktipoexhc' + id_checkpri).checked;
        var Is_Cheked_O = false;
        Is_Cheked_O = document.getElementById('d_checktipoexho' + id_checkpri).checked;
        var contaCheck = 0;
        (Is_Cheked_G) ? contaCheck += 1 : contaCheck += 0;
        (Is_Cheked_S) ? contaCheck += 1 : contaCheck += 0;
        (Is_Cheked_C) ? contaCheck += 1 : contaCheck += 0;
        (Is_Cheked_O) ? contaCheck += 1 : contaCheck += 0;
        if (contaCheck > 0) {
            $("#d_checktipoexhg" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
            $("#d_checktipoexhs" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
            $("#d_checktipoexhc" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
            $("#d_checktipoexho" + id_checkpri).removeClass("is-invalid").addClass("is-valid");
        } else {
            $("#d_checktipoexhg" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
            $("#d_checktipoexhs" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
            $("#d_checktipoexhc" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
            $("#d_checktipoexho" + id_checkpri).removeClass("is-valid").addClass("is-invalid");
        }
    });
    $(document).on("keyup", "input[name='d_txtrt[]']", function () {
        var id_rt = $(this).attr("id");
        id_rt = id_rt.substring(7, id_rt.length);
        V_input_Entero(id_rt, $("#d_txtrt" + id_rt).val(), 'd_txtrt', 'd_error-mjsr-', 'RISTRA', $("#d_txtidcat_desc" + id_rt).val());
    });
    $(document).on("keyup", "input[name='d_txtpq[]']", function () {
        var id_pq = $(this).attr("id");
        id_pq = id_pq.substring(7, id_pq.length);
        V_input_Entero(id_pq, $("#d_txtpq" + id_pq).val(), 'd_txtpq', 'd_error-mjsp-', 'PAQUETE', $("#d_txtidcat_desc" + id_pq).val());
    });
    $(document).on("keyup", "input[name='d_txtpines[]']", function () {
        var id_pn = $(this).attr("id");
        id_pn = id_pn.substring(10, id_pn.length);
        V_input_Entero(id_pn, $("#d_txtpines" + id_pn).val(), 'd_txtpines', 'd_error-mjspn-', 'PINES', $("#d_txtidcat_desc" + id_pn).val());
    });
    $(document).on("keyup", "input[name='d_txtun[]']", function () {
        var id_un = $(this).attr("id");
        id_un = id_un.substring(7, id_un.length);
        V_input_Entero(id_un, $("#d_txtun" + id_un).val(), 'd_txtun', 'd_error-mjsun-', 'UNIDADES', $("#d_txtidcat_desc" + id_un).val());
    });
    $(document).on("keyup", "input[name='d_txtbolsas[]']", function () {
        var id_bl = $(this).attr("id");
        id_bl = id_bl.substring(11, id_bl.length);
        V_input_Entero(id_bl, $("#d_txtbolsas" + id_bl).val(), 'd_txtbolsas', 'd_error-mjsbl-', 'BOLSAS', $("#d_txtidcat_desc" + id_bl).val());
    });
    $(document).on("keyup", "input[name='d_txtcaras[]']", function () {
        var id_cr = $(this).attr("id");
        id_cr = id_cr.substring(10, id_cr.length);
        V_input_EnteroMYQc(id_cr, $("#d_txtcaras" + id_cr).val(), 'd_txtcaras', 'd_error-mjscara-', 'CARAS DE EXHIBICION', $("#d_txtidcat_desc" + id_cr).val());
    });
    $(document).on("keyup", "input[name='d_txtbotes[]']", function () {
        var id_bl = $(this).attr("id");
        id_bl = id_bl.substring(10, id_bl.length);
        V_input_Entero(id_bl, $("#d_txtbotes" + id_bl).val(), 'd_txtbotes', 'd_error-mjsbt-', 'BOTES', $("#d_txtidcat_desc" + id_bl).val());
    });
    $(document).on("change", "select[name='d_observacion_exh[]']", function () {
        var id_obser = $(this).attr("id");
        id_obser = id_obser.substring(17, id_obser.length);
        if (!_empty($('#d_observacion_exh' + id_obser).val())) {
            $('#d_observacion_exh' + id_obser).removeClass("is-invalid").addClass("is-valid");
        } else {
            $('#d_observacion_exh' + id_obser).removeClass("is-valid").addClass("is-invalid");
        }
    });
    /*-------000000-------000000-------0000000------000000-------000000------*/
    $('#conten_prin').on('click', '#btn_conExh', function () {
        // $.when($("#conten_prin").stop(true, true).hide()).done(function (x) {
        //     $.when($("#div_contentDetExh").stop(true, true).show()).done(function (x) {
        map.once('locationfound', geoUbicacinCliente);
        map.on('locationerror', onLocationError_Exh);
        map.locate({setView: true, maxZoom: 15});
        //     });
        // });
        $("#m_control_exhibidores").modal("toggle");
    });
    $('#conten_prin').on('click', '#btn_sinExh', function () {
        map.once('locationfound', geoUbicacinClienteSinExh);
        map.on('locationerror', onLocationError_Exh);
        map.locate({setView: true, maxZoom: 15});
    });
    $('#Menu_principal').on('click', '#btn_CtrExhibidores', function () {
        $("#txtclientesexh").val("");
        $.when($("#div_detExh").stop(true, true).hide()).done(function (x) {
            $.when($("#div_prinBody").stop(true, true).show()).done(function (x) {
                $("#btn_GuaExh").hide();
                $("#btn_conExh").show();
                $("#sub-nav-qtiene").empty();
            });
        });
    });
    $('#ModalExh').on('shown.bs.modal', function (e) {
        table = null;
        table = $('#DgrTableExh').DataTable({
            "data": DataJSON_Exh,
            "columns": [
                {
                    "data": "Cat_Id"
                }, {
                    "data": "Cat_img"
                }, {
                    "data": "Cat_descripcion"
                }, {
                    "data": "Subf_nombre"
                }
            ],
            "columnDefs": [
                {
                    "targets": [1],
                    "data": "Cat_img",
                    "render": function (data, type, row) {
                        var img_sku = `Uploads/img_server/Img_CatalagoProductos/icon_default.png`;
                        img_sku = `../../${data}`;
                        img_sku = `<img src="${img_sku}" class="img_sku">`;
                        return img_sku;
                    }
                }
            ],
            initComplete: function () {
                this.api().columns([3]).every(function (i) {
                    var column = this,
                        select = $('#txtipoexh').on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val).draw();
                        });
                });
            },
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Nada encontrado - lo siento",
                "info": "Mostrando la página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles.",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "<span class='fa fa-search-plus fa-2x' style='margin-top: 5px;color:#536162;'></span>",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "processing": "Procesando...",
                "decimal": "",
                "loadingRecords": "Cargando...",
                "thousands": ",",
                "infoPostFix": ""
            },
            "dom": '<"top"i>frt<"bottom"lp><"clear">',
            "ordering": true,
            "info": false,
            "lengthChange": false,
            "iDisplayLength": 10,
            "pagingType": "numbers",
            "scrollY": "50vh",
            "scrollX": "50vh",
            "scrollCollapse": true
        });
    });
    $('#ModalCli').on('shown.bs.modal', function (e) {
        table_Cli = null;
        table_Cli = $('#DgrTableCli').DataTable({
            "data": DataJSON_Cli,
            "columns": [
                {
                    "data": "Cli_codigo"
                },
                {
                    "data": "Cli_nombre"
                },
                {
                    "data": "Cli_direccion"
                },
                {
                    "data": "Cli_contacto"
                }, {
                    "data": "Cli_telefono"
                }, {
                    "data": "Cli_dia"
                }, {
                    "data": "Cli_ul_fecha_ac_exhibidor"
                }
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "data": "Cli_codigo",
                    "render": function (data, type, row) {
                        var EstadoCensado = '';
                        var span_estadow = '';
                        var span_categoria = '';
                        if (row.Cli_ac_exhibidor == 1) {
                            EstadoCensado = '<span class="vya fas fa-check fa-2x"></span><br>';
                        } else {
                            EstadoCensado = '';
                        }
                        if (row.Cli_categoria == 'S') {
                            span_categoria = '<br><p class="text-secondary" style="font-size:14px;font-weight:500;">SIN CATEGORIA</p>';
                        } else {
                            span_categoria = '<br><p class="text-dark" style="font-size:29px;font-weight:500;">' + row.Cli_categoria + '</p>';
                        }
                        return EstadoCensado + data + span_categoria;
                    }
                }, {
                    "targets": [5],
                    "data": "Cli_dia",
                    "render": function (data, type, row) {

                        var badge_dias = ``;

                        if (row.Cli_l == 1) {
                            badge_dias += `<span class="badge badge-info">LUNES</span>`;
                        }
                        if (row.Cli_m == 1) {
                            badge_dias += `<span class="badge badge-info">MARTES</span>`;
                        }
                        if (row.Cli_mi == 1) {
                            badge_dias += `<span class="badge badge-info">MIERCOLES</span>`;
                        }
                        if (row.Cli_j == 1) {
                            badge_dias += `<span class="badge badge-info">JUEVES</span>`;
                        }
                        if (row.Cli_v == 1) {
                            badge_dias += `<span class="badge badge-info">VIERNES</span>`;
                        }
                        if (row.Cli_s == 1) {
                            badge_dias += `<span class="badge badge-info">SABADO</span>`;
                        }
                        if (row.Cli_d == 1) {
                            badge_dias += `<span class="badge badge-info">DOMINGO</span>`;
                        }

                        return badge_dias;

                    } // FIN RENDER
                }, {
                    "targets": [6],
                    "data": "Cli_ul_fecha_ac_exhibidor",
                    "render": function (data, type, row) {
                        var options = {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        var fecha_formateada = '';
                        if (!_empty(data)) {
                            var fecha = new Date(data);
                            fecha_formateada = fecha.toLocaleDateString("es-ES", options);
                        } else {
                            fecha_formateada = 'NA';
                        }
                        return fecha_formateada;
                    }
                }
            ],
            initComplete: function () {
                this.api().columns([5]).every(function (i) {
                    var column = this;
                    if (arrg_Credls['FiltroDiaVisitaAC'] == 'LUNES') {
                        column.search('LUNES').draw();
                        $('#dias_busqueda').val('LUNES');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'MARTES') {
                        column.search('MARTES').draw();
                        $('#dias_busqueda').val('MARTES');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'MIERCOLES') {
                        column.search('MIERCOLES').draw();
                        $('#dias_busqueda').val('MIERCOLES');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'JUEVES') {
                        column.search('JUEVES').draw();
                        $('#dias_busqueda').val('JUEVES');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'VIERNES') {
                        column.search('VIERNES').draw();
                        $('#dias_busqueda').val('VIERNES');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'SABADO') {
                        column.search('SABADO').draw();
                        $('#dias_busqueda').val('SABADO');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'DOMINGO') {
                        column.search('DOMINGO').draw();
                        $('#dias_busqueda').val('DOMINGO');
                    }
                    var switchs_d = $('#dias_busqueda').on('change', function (e) {
                        var vall = $.fn.dataTable.util.escapeRegex($(this).val());
                        guardar_filtro(vall, 2);
                        if (vall == 'LUNES') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'LUNES';
                            column.search(vall).draw();
                        } else if (vall == 'MARTES') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'MARTES';
                            column.search(vall).draw();
                        } else if (vall == 'MIERCOLES') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'MIERCOLES';
                            column.search(vall).draw();
                        } else if (vall == 'JUEVES') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'JUEVES';
                            column.search(vall).draw();
                        } else if (vall == 'VIERNES') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'VIERNES';
                            column.search(vall).draw();
                        } else if (vall == 'SABADO') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'SABADO';
                            column.search(vall).draw();
                        } else if (vall == 'DOMINGO') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'DOMINGO';
                            column.search(vall).draw();
                        } else if (vall == '') {
                            column.search('').draw();
                        }
                    });
                });
            },
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Nada encontrado - lo siento",
                "info": "Mostrando la página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles.",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "<span class='fa fa-search-plus fa-2x' style='margin-top: 5px;color:#536162;'></span>",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "processing": "Procesando...",
                "decimal": "",
                "loadingRecords": "Cargando...",
                "thousands": ",",
                "infoPostFix": ""
            },
            "dom": '<"top"i>frt<"bottom"lp><"clear">',
            "ordering": true,
            "info": false,
            "lengthChange": false,
            "iDisplayLength": 10,
            "pagingType": "numbers",
            "scrollY": "50vh",
            "scrollX": "50vh",
            "scrollCollapse": true
        });
    });
    $('#ModalExh').on('hidden.bs.modal', function (e) {
        $('#DgrTableExh').DataTable().destroy();
        $("#showDataExh").empty();
    });
    $('#ModalCli').on('hidden.bs.modal', function (e) {
        $('#DgrTableCli').DataTable().destroy();
        $("#showDataCli").empty();
    });
    /* Seleccion de exhibidores */
    $('#DgrTableCli tbody').on('click', 'tr', function () {
        arrg_datCli = [];
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        var fecha_formateada = '';
        if (!_empty(table_Cli.row(this).data().Cli_ul_fecha_ac_exhibidor)) {
            var fecha = new Date(table_Cli.row(this).data().Cli_ul_fecha_ac_exhibidor);
            fecha_formateada = fecha.toLocaleDateString("es-ES", options);
        } else {
            fecha_formateada = 'SIN ACTUALIZAR';
        }
        $("#lbl_fultima").text(fecha_formateada);
        $("#codigoCli").val(table_Cli.row(this).data().Cli_codigo);
        $("#nombreCli").val(table_Cli.row(this).data().Cli_nombre);
        $("#direccionCli").val(table_Cli.row(this).data().Cli_direccion);
        $("#telefonoCli").val(table_Cli.row(this).data().Cli_telefono);
        $("#contactoCli").val(table_Cli.row(this).data().Cli_contacto);
        $("#txtclientesexh").val(table_Cli.row(this).data().Cli_Id);
        arrg_datCli = [
            table_Cli.row(this).data().Cli_Id,
            table_Cli.row(this).data().Cli_nombre,
            table_Cli.row(this).data().Cli_codigo,
            table_Cli.row(this).data().Cli_direccion,
            table_Cli.row(this).data().Cli_telefono,
            table_Cli.row(this).data().Cli_contacto,
            table_Cli.row(this).data().Cli_actu_exh,
            table_Cli.row(this).data().Cli_Id,
            table_Cli.row(this).data().Cli_bloq_exh
        ];
        /*CABMIOS 21/08/2021*/
        if (table_Cli.row(this).data().Cli_estado_csexh == 1) {
            $("#lbl_estadoExh").text('CON EXHIBIDORES');
        } else {
            $("#lbl_estadoExh").text('SIN EXHIBIDORES');
        }
        $.when($("#InfoCuadro").stop(true, true).hide()).done(function (x) {
            $.when($("#InfoCliente").stop(true, true).show()).done(function (x) {
                $("#ModalCli").modal("toggle");
            });
        });
    });
    /* Seleccion de exhibidores */
    $('#DgrTableExh tbody').on('click', 'tr', function () {
        warn_on_unload = 'no salir';
        /*---------------------BLOQUEOS POR TIPO AC-----------------------------*/
        var bloquearObs = ``;
        var SelectedOb_D = [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            bloquearInp = '',
            bloqdivInp = '';
        var bloq_tipoexh = ``;
        /*----------------------------------------------------------------------*/
        if (bandera_tipoAC == 1) {
            var token_Exh = TokenAC_Exh();
            var class_Exh = ``,
                class_status = ``,
                class_color = ``,
                contentDiv = ``;
            var FechaTelefono = fechaDispositivo();
            if (TipoAc_Exh == 1) {
                class_Exh = `seg`;
                class_status = `defaultcolor fas fa-question-circle fa-lg`;
                class_color = `c_qtiene`;
                let limpiarDqtiene = ($('#det_qtiene').html()) ? $('#det_qtiene').empty() : 'Nada';
                let VisibleDqtiene = ($('#add_btQtiene').is(':visible')) ? 'Nada' : $("#add_btQtiene").show();
                contentDiv = 'sub-nav-qtiene';
                bloquearObs = ``;
                SelectedOb_D[0] = ``;
            } else if (TipoAc_Exh == 2) {
                class_Exh = `segdos`;
                class_status = ``;
                bloquearObs = ``;
                SelectedOb_D[0] = ``;
            } else if (TipoAc_Exh == 3) {
                class_Exh = `segtres`;
                class_status = ``;
                class_color = `c_nuevos`;
                bloquearObs = ``;
                SelectedOb_D[0] = ``;
                let limpiarDqtiene = ($('#det_nuevos').html()) ? $('#det_nuevos').empty() : 'Nada';
                let VisibleDqtiene = ($('#add_nuevos').is(':visible')) ? 'Nada' : $("#add_nuevos").show();
                contentDiv = 'sub-nav-nuevos';
            } else {
                class_Exh = `seg`;
                class_status = ``;
                bloquearObs = ``;
            }
            if (table.row(this).data().Cat_Id != '85000000001' && table.row(this).data().Cat_Id != '99999999999' && table.row(this).data().Cat_Id != '11111111111' && table.row(this).data().Cat_Id != '22222222222' && table.row(this).data().Cat_Id != '33333333333' && table.row(this).data().Cat_Id != '44444444444' && table.row(this).data().Cat_Id != '55555555555' && table.row(this).data().Cat_Id != '88888888888' && table.row(this).data().Cat_Id != '12121212121' && table.row(this).data().Cat_Id != '13131313131') {
                bloquearInp = 'readonly';
                bloqdivInp = `style="display:none;"`;
                bloq_tipoexh = `style="text-align:left;display:none;"`;
            } else {
                bloq_tipoexh = `style="text-align:left;"`;
            }
            var options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            var fecha_formateada = '';
            var fecha = new Date(FechaTelefono);
            fecha_formateada = fecha.toLocaleDateString("es-ES", options);
            var html_add = `
            <div id="content_exh${ContaExh}" class="fichz__">
                <div class='box01'>
                    <div class='box02'>
                        <label style='font-weight: 700;font-size:13px;color:#3E5154;text-transform: uppercase;'><span class='fas fa-calendar-alt fa-lg'></span> <i>${fecha_formateada}</i></label>
                    </div>
                    <div class='box03' id="span_op${ContaExh}">
                    <span id="edit_exh${ContaExh}" class='fas fa-pencil-alt fa-2x qt_bt_edit'></span>`;
            if (TipoAc_Exh == 1) {
                html_add += `
                        <span id="quit_exh${ContaExh}" class='fas fa-exchange-alt fa-2x qt_bt_devu'></span>`;
            }
            html_add += ` 
                    <span id="quit_qui${ContaExh}" class='fas fa-trash-alt fa-2x qt_bt_quitar'></span>
                    </div>
                </div>
                <div class='${class_Exh}'>
                    <div class='seg_i' id="sku_id${ContaExh}">SKU: ${
                table.row(this).data().Cat_Id
            }<span id="error_incompleto${ContaExh}" class="fas fa-exclamation-triangle fa-lg" style="color:#EEEB38;float:right;display:none;"></span></div>
                    <div class='seg_d' id="sku_desc${ContaExh}">${
                table.row(this).data().Cat_descripcion
            }</div>
                </div>
                <div class='toggle_observacion'>
                <hr>
                    <button type='button' class='btn btn_observacion' data-toggle='collapse' data-target='#collapse_observacionno${ContaExh}' aria-expanded='true'><span class='fa fa-eye fa-lg'></span> DETALLES DEL EXHIBIDOR</button>
                    <div id='collapse_observacionno${ContaExh}' class='col-md collapse show gretro__'>
                        <div class='row divcollapse'>
                        <hr class="sig__">
                            <div class='col-md' style='padding-bottom:5px;'>




                                <div class="form-group">
                                    <div>
                                        <span class="fas fa-camera fa-2x"></span>
                                        <label style="font-weight: 700;">Foto Exhibidor (Antes)</label>
                                        <div class="custom-file mb-3" style="text-align: left;">
                                            <input type="file" class="custom-file-input file_u" id="filefotosu${ContaExh}" name="filefotosu[]" lang="es" accept="image/*">
                                            <label class="custom-file-label" data-browse="Tomar foto">Im&aacute;gen</label>
                                        </div>
                                        <img id="imagenu${ContaExh}" src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;width:200px;height:200px;float: left;" width="200px" height="200px">
                                    </div>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback" id="error-mjsf-${ContaExh}"> </div>
                                </div>
                   


                                <div class="form-group">
                                    <div>
                                        
                                        <label style="font-weight: 700;">Foto Exhibidor (Después)</label>
                                        <div class="custom-file mb-3" style="text-align: left;">
                                            <input type="file" class="custom-file-input file_t" id="filefotost${ContaExh}" name="filefotost[]" lang="es" accept="image/*">
                                            <label class="custom-file-label" data-browse="Tomar foto">Im&aacute;gen</label>
                                        </div>
                                        <img id="imagent${ContaExh}" src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;width:200px;height:200px;float: left;" width="200px" height="200px">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjsf-despues-${ContaExh}"></div>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div>
                                       
                                        <label style="font-weight: 700;">Foto Exhibidor (Panorámica)</label>
                                        <div class="custom-file mb-3" style="text-align: left;">
                                            <input type="file" class="custom-file-input file_d" id="filefotosd${ContaExh}" name="filefotosd[]" lang="es" accept="image/*">
                                            <label class="custom-file-label" data-browse="Tomar foto">Im&aacute;gen</label>
                                        </div>
                                        <img id="imagend${ContaExh}" src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;width:200px;height:200px;float: left;" width="200px" height="200px">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjsf-panoramica-${ContaExh}"></div>
                                    </div>
                                </div>
                            


                                <div ${bloq_tipoexh} id="btipo_exh${ContaExh}">
                                    <label style="font-size:20px;margin-top:5px;text-transform: uppercase;">Tipo de Exhibidor</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check chk${ContaExh} chkpri" id="checktipoexhg${ContaExh}" name="checktipoexh${ContaExh}[]" value='1'>
                                        <label class="custom-control-label" for="checktipoexhg${ContaExh}">GALLETA</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check chk${ContaExh} chkpri" id="checktipoexhs${ContaExh}" name="checktipoexh${ContaExh}[]" value='1'>
                                        <label class="custom-control-label" for="checktipoexhs${ContaExh}">SNACK</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check chk${ContaExh} chkpri" id="checktipoexhc${ContaExh}" name="checktipoexh${ContaExh}[]" value='1'>
                                        <label class="custom-control-label" for="checktipoexhc${ContaExh}">CEREALES</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input GR_Check chk${ContaExh} chkpri" id="checktipoexho${ContaExh}" name="checktipoexh${ContaExh}[]" value='1'>
                                        <label class="custom-control-label" for="checktipoexho${ContaExh}">CONFITERIA</label>
                                    </div>
                                </div>
                                <div ${bloqdivInp} id="bcapacidadexh${ContaExh}">
                                    <label style="font-size:20px;margin-top:5px;text-transform: uppercase;">Capacidad del Exhibidor</label>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Ristra (RT):</span></div>
                                            <div class="col">
                                                <input type="tel" id="txtrt${ContaExh}" name="txtrt[]" class="form-control" placeholder="0" ${bloquearInp} autocomplete="off">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback" id="error-mjsr-${ContaExh}"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Paquete (PQ):</span></div>
                                            <div class="col">
                                                <input type="tel" id="txtpq${ContaExh}" name="txtpq[]"class="form-control" placeholder="0" ${bloquearInp} autocomplete="off">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback" id="error-mjsp-${ContaExh}"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Pines:</span></div>
                                            <div class="col">
                                                <input type="tel" id="txtpines${ContaExh}" name="txtpines[]" class="form-control" placeholder="0" ${bloquearInp} autocomplete="off">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback" id="error-mjspn-${ContaExh}"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Unidad (PRODUCTO FAMILIAR):</span></div>
                                            <div class="col">
                                                <input type="tel" id="txtun${ContaExh}" name="txtun[]" class="form-control" placeholder="0" ${bloquearInp} autocomplete="off">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback" id="error-mjsun-${ContaExh}"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Bolsas:</span></div>
                                            <div class="col">
                                                <input type="tel" id="txtbolsas${ContaExh}" name="txtbolsas[]" class="form-control" placeholder="0" ${bloquearInp} autocomplete="off">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback" id="error-mjsbl-${ContaExh}"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Botes:</span></div>
                                            <div class="col">
                                                <input type="tel" id="txtbotes${ContaExh}" name="txtbotes[]" class="form-control" placeholder="0" ${bloquearInp} autocomplete="off">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback" id="error-mjsbt-${ContaExh}"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Caras de Exhibición:</span></div>
                                            <div class="col">
                                                <input type="tel" id="txtcaras${ContaExh}" name="txtcaras[]" class="form-control" placeholder="0" ${bloquearInp} autocomplete="off">
                                                <div class="valid-feedback"></div>
                                                <div class="invalid-feedback" id="error-mjscara-${ContaExh}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label style="font-size:20px;margin-top:5px;text-transform: uppercase; text-decoration: underline;">Estado del Exhibidor</label>
                                    <select class="custom-select" id="observacion_exh${ContaExh}" name="observacion_exh[]" ${bloquearObs}>
                                        <option value="" hidden>Elige una opción...</option>
                                        <option value="1" ${SelectedOb_D[0]}>VISIBLE Y ACCESIBLE</option>
                                        <option value="2">MAL UBICADO</option>
                                        <option value="3">INVADIDO</option>
                                        <option value="4">NECESITA REPARACION</option>
                                        <option value="5">DESECHADO O GUARDADO POR EL CLIENTE</option>
                                        <option value="6">RETIRADO DEL NEGOCIO</option>
                                        <option value="7">EN BODEGA</option>
                                    </select>
                                    <div class="valid-feedback">
                                        <strong></strong>
                                    </div>
                                    <div class="invalid-feedback">
                                        <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
                                    </div>
                                </div>
                                <div class="row" style="padding: 10px 0px 10px 0px;">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Comentario:</span></div>
                                    <div class="col">
                                        <textarea class="form-control" id="comentarioCliexh${ContaExh}" name="comentarioCliexh[]" maxlength="255"></textarea>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="error-mjsdi-${ContaExh}"></div>
                                    </div>
                                </div>
                                <input type="hidden" id="txtidcat${ContaExh}" name="txtidcat[]" value="${
                table.row(this).data().Cat_Id
            }">
                                <input type="hidden" id="txtidcat_desc${ContaExh}" name="txtidcat_desc[]" value="${
                table.row(this).data().Cat_descripcion
            }">
                                <input type="hidden" id="txttoken${ContaExh}" name="txttoken[]" value="${token_Exh}">
                                <input type="hidden" id="txtfechatel${ContaExh}" name="txtfechatel[]" value="${FechaTelefono}">
                                <input type="hidden" id="txttipoac${ContaExh}" name="txttipoac[]" value="${TipoAc_Exh}">
                                <input type="hidden" id="Ste_Mot_Id${ContaExh}" name="Ste_Mot_Id[]" value="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            if (TipoAc_Exh == 1) {
                if (arrg_datCli[6] == 0 && arrg_datCli[8] == 0) {
                    $("#list_qtiene").empty();
                } else if (arrg_datCli[8] == 1) {
                    if (TipoAc_Exh == 1) {
                        $("#list_qtiene").empty();
                    }
                } else {}
            }
            $('#' + contentDiv).append(html_add);
            $("#ModalExh").modal("toggle");
            $("#showDataExh").empty();
            $('#DgrTableExh').DataTable().destroy();
            arrg_fotosExh[ContaExh] = 0;
            ContaExh++;
        } else { // console.log('TIPO DE ACTUALIZACION => '+TipoAc_Exh);
            if (tipo_edicion == 1) {
                if (table.row(this).data().Cat_Id != '85000000001' && table.row(this).data().Cat_Id != '99999999999' && table.row(this).data().Cat_Id != '11111111111' && table.row(this).data().Cat_Id != '22222222222' && table.row(this).data().Cat_Id != '33333333333' && table.row(this).data().Cat_Id != '44444444444' && table.row(this).data().Cat_Id != '55555555555' && table.row(this).data().Cat_Id != '88888888888' && table.row(this).data().Cat_Id != '12121212121' && table.row(this).data().Cat_Id != '13131313131') {
                    $("#btipo_exh" + id_Editando).hide();
                    $("#bcapacidadexh" + id_Editando).hide();
                    $('#txtrt' + id_Editando).prop("readOnly", true);
                    $('#txtpq' + id_Editando).prop("readOnly", true);
                    $('#txtpines' + id_Editando).prop("readOnly", true);
                    $('#txtun' + id_Editando).prop("readOnly", true);
                    $('#txtbolsas' + id_Editando).prop("readOnly", true);
                    $("#txtrt" + id_Editando).val('');
                    $("#txtpq" + id_Editando).val('');
                    $("#txtpines" + id_Editando).val('');
                    $("#txtun" + id_Editando).val('');
                    $("#txtbolsas" + id_Editando).val('');
                    $("#txtcaras" + id_Editando).val('');
                    $("#txtbotes" + id_Editando).val('');
                } else {
                    $("#btipo_exh" + id_Editando).show();
                    $("#bcapacidadexh" + id_Editando).show();
                    $('#txtrt' + id_Editando).prop("readOnly", false);
                    $('#txtpq' + id_Editando).prop("readOnly", false);
                    $('#txtpines' + id_Editando).prop("readOnly", false);
                    $('#txtun' + id_Editando).prop("readOnly", false);
                    $('#txtbolsas' + id_Editando).prop("readOnly", false);
                    $('#txtcaras' + id_Editando).prop("readOnly", false);
                    $('#txtbotes' + id_Editando).prop("readOnly", false);
                    if (TipoAc_Exh != 3) {
                        if (arrg_capacidadExh[0] == '0') {
                            $("#txtrt" + id_Editando).val('');
                        } else {
                            $("#txtrt" + id_Editando).val(arrg_capacidadExh[0]);
                        }
                        if (arrg_capacidadExh[1] == '0') {
                            $("#txtpq" + id_Editando).val('');
                        } else {
                            $("#txtpq" + id_Editando).val(arrg_capacidadExh[1]);
                        }
                        if (arrg_capacidadExh[2] == '0') {
                            $("#txtpines" + id_Editando).val('');
                        } else {
                            $("#txtpines" + id_Editando).val(arrg_capacidadExh[2]);
                        }
                        if (arrg_capacidadExh[3] == '0') {
                            $("#txtun" + id_Editando).val('');
                        } else {
                            $("#txtun" + id_Editando).val(arrg_capacidadExh[3]);
                        }
                        if (arrg_capacidadExh[4] == '0') {
                            $("#txtbolsas" + id_Editando).val('');
                        } else {
                            $("#txtbolsas" + id_Editando).val(arrg_capacidadExh[4]);
                        }
                        if (arrg_capacidadExh[5] == '0') {
                            $("#txtcaras" + id_Editando).val('');
                        } else {
                            $("#txtcaras" + id_Editando).val(arrg_capacidadExh[5]);
                        }
                        if (arrg_capacidadExh[6] == '0') {
                            $("#txtbotes" + id_Editando).val('');
                        } else {
                            $("#txtbotes" + id_Editando).val(arrg_capacidadExh[6]);
                        }
                    } else {
                        $("#txtrt" + id_Editando).val('');
                        $("#txtpq" + id_Editando).val('');
                        $("#txtpines" + id_Editando).val('');
                        $("#txtun" + id_Editando).val('');
                        $("#txtbolsas" + id_Editando).val('');
                        $("#txtcaras" + id_Editando).val('');
                        $("#txtbotes" + id_Editando).val('');
                    }
                }
                $("#sku_id" + id_Editando).html('SKU: ' + table.row(this).data().Cat_Id + `<span id="d_error_incompleto${id_Editando}" class="fas fa-exclamation-triangle fa-lg" style="color:#EEEB38;float:right;display:none;"></span>`);
                $("#sku_desc" + id_Editando).text(table.row(this).data().Cat_descripcion);
                $("#txtidcat" + id_Editando).val(table.row(this).data().Cat_Id);
                $("#txtidcat_desc" + id_Editando).val(table.row(this).data().Cat_descripcion);
            } else {
                if (table.row(this).data().Cat_Id != '85000000001' && table.row(this).data().Cat_Id != '99999999999' && table.row(this).data().Cat_Id != '11111111111' && table.row(this).data().Cat_Id != '22222222222' && table.row(this).data().Cat_Id != '33333333333' && table.row(this).data().Cat_Id != '44444444444' && table.row(this).data().Cat_Id != '55555555555' && table.row(this).data().Cat_Id != '88888888888' && table.row(this).data().Cat_Id != '12121212121' && table.row(this).data().Cat_Id != '13131313131') {
                    $("#d_btipo_exh" + id_Editando).hide();
                    $("#d_bcapacidadexh" + id_Editando).hide();
                    $('#d_txtrt' + id_Editando).prop("readOnly", true);
                    $('#d_txtpq' + id_Editando).prop("readOnly", true);
                    $('#d_txtpines' + id_Editando).prop("readOnly", true);
                    $('#d_txtun' + id_Editando).prop("readOnly", true);
                    $('#d_txtbolsas' + id_Editando).prop("readOnly", true);
                    $("#d_txtrt" + id_Editando).val('');
                    $("#d_txtpq" + id_Editando).val('');
                    $("#d_txtpines" + id_Editando).val('');
                    $("#d_txtun" + id_Editando).val('');
                    $("#d_txtbolsas" + id_Editando).val('');
                    $("#d_txtcaras" + id_Editando).val('');
                    $("#d_txtbotes" + id_Editando).val('');
                } else { // console.log('habilitar otras campos');
                    $("#d_btipo_exh" + id_Editando).show();
                    $("#d_bcapacidadexh" + id_Editando).show();
                    $('#d_txtrt' + id_Editando).prop("readOnly", false);
                    $('#d_txtpq' + id_Editando).prop("readOnly", false);
                    $('#d_txtpines' + id_Editando).prop("readOnly", false);
                    $('#d_txtun' + id_Editando).prop("readOnly", false);
                    $('#d_txtbolsas' + id_Editando).prop("readOnly", false);
                    $('#d_txtcaras' + id_Editando).prop("readOnly", false);
                    $('#d_txtbotes' + id_Editando).prop("readOnly", false);
                    if (TipoAc_Exh != 3) {
                        if (arrg_capacidadExh[0] == '0') {
                            $("#d_txtrt" + id_Editando).val('');
                        } else {
                            $("#d_txtrt" + id_Editando).val(arrg_capacidadExh[0]);
                        }
                        if (arrg_capacidadExh[1] == '0') {
                            $("#d_txtpq" + id_Editando).val('');
                        } else {
                            $("#d_txtpq" + id_Editando).val(arrg_capacidadExh[1]);
                        }
                        if (arrg_capacidadExh[2] == '0') {
                            $("#d_txtpines" + id_Editando).val('');
                        } else {
                            $("#d_txtpines" + id_Editando).val(arrg_capacidadExh[2]);
                        }
                        if (arrg_capacidadExh[3] == '0') {
                            $("#d_txtun" + id_Editando).val('');
                        } else {
                            $("#d_txtun" + id_Editando).val(arrg_capacidadExh[3]);
                        }
                        if (arrg_capacidadExh[4] == '0') {
                            $("#d_txtbolsas" + id_Editando).val('');
                        } else {
                            $("#d_txtbolsas" + id_Editando).val(arrg_capacidadExh[4]);
                        }
                        if (arrg_capacidadExh[5] == '0') {
                            $("#d_txtcaras" + id_Editando).val('');
                        } else {
                            $("#d_txtcaras" + id_Editando).val(arrg_capacidadExh[5]);
                        }
                        if (arrg_capacidadExh[6] == '0') {
                            $("#d_txtbotes" + id_Editando).val('');
                        } else {
                            $("#d_txtbotes" + id_Editando).val(arrg_capacidadExh[6]);
                        }
                    } else {
                        $("#d_txtrt" + id_Editando).val('');
                        $("#d_txtpq" + id_Editando).val('');
                        $("#d_txtpines" + id_Editando).val('');
                        $("#d_txtun" + id_Editando).val('');
                        $("#d_txtbolsas" + id_Editando).val('');
                        $("#d_txtcaras" + id_Editando).val('');
                        $("#d_txtbotes" + id_Editando).val('');
                    }
                }
                $("#d_sku_id" + id_Editando).html('SKU: ' + table.row(this).data().Cat_Id + `<span id="d_error_incompleto${id_Editando}" class="fas fa-exclamation-triangle fa-lg" style="color:#EEEB38;float:right;display:none;"></span>`);
                $("#d_sku_desc" + id_Editando).text(table.row(this).data().Cat_descripcion);
                $("#d_txtidcat" + id_Editando).val(table.row(this).data().Cat_Id);
                $("#d_txtidcat_desc" + id_Editando).val(table.row(this).data().Cat_descripcion);
            }
            $("#ModalExh").modal("toggle");
        }
    });
    $('#div_detExh').on('click', '.qt_bt_edit', function () { // console.log(' PRIMERO X EDICION');
        warn_on_unload = 'no salir';
        arrg_capacidadExh = [];
        tipo_edicion = 1;
        bandera_tipoAC = 2;
        id_Editando = 0;
        id_Editando = $(this).attr("id");
        id_Editando = id_Editando.substring(8, id_Editando.length);
        arrg_capacidadExh = [
            $("#d_txtrt" + id_Editando).val(),
            $("#d_txtpq" + id_Editando).val(),
            $("#d_txtpines" + id_Editando).val(),
            $("#d_txtun" + id_Editando).val(),
            $("#d_txtbolsas" + id_Editando).val(),
            $("#d_txtcaras" + id_Editando).val(),
            $("#d_txtbotes" + id_Editando).val()
        ];
        DB_ListarExhibidores($("#txttipoac" + id_Editando).val(), 2);
    });
    $('#div_detExh').on('click', '.d_qt_bt_edit', function () { // console.log(' EDICION X EDICION');
        warn_on_unload = 'no salir';
        arrg_capacidadExh = [];
        id_Editando = 0;
        id_Editando = $(this).attr("id");
        id_Editando = id_Editando.substring(10, id_Editando.length);
        arrg_capacidadExh = [
            $("#d_txtrt" + id_Editando).val(),
            $("#d_txtpq" + id_Editando).val(),
            $("#d_txtpines" + id_Editando).val(),
            $("#d_txtun" + id_Editando).val(),
            $("#d_txtbolsas" + id_Editando).val(),
            $("#d_txtcaras" + id_Editando).val(),
            $("#d_txtbotes" + id_Editando).val()
        ];
        tipo_edicion = 2;
        bandera_tipoAC = 2;
        $("#d_modificado" + id_Editando).val(1);
        DB_ListarExhibidores($("#d_txttipoac" + id_Editando).val(), 2);
    });
    $('#div_detExh').on('click', '.qt_bt_devu', function () {
        var ContaCorrecto = 0;
        warn_on_unload = 'no salir';
        var id_Devuelto = $(this).attr("id");
        var info_dev = "";
        Opcion_Dev = 1;
        id_Devuelto = id_Devuelto.substring(8, id_Devuelto.length);
        var det_exhibidor = `
        <div class="fichz__">
            <div class='box01'>
                <div class='box02'>
                </div>
                <div class='box03'>
                    <span style="color: #2BA6CB"></span>
                </div>
            </div>
            <div class='segdos'>
                <div class='seg_i'>SKU: ${
            $("#txtidcat" + id_Devuelto).val()
        }</div>
                <div class='seg_d'>${
            $("#txtidcat_desc" + id_Devuelto).val()
        }</div>
            </div>
        </div>`;
        $("#registroDevol").val(id_Devuelto);
        $("#det_exhdevolu").empty().html(det_exhibidor);

        if (arrg_fotosExh[id_Devuelto] == 0) {
            ContaCorrecto += 0;
            $("#filefotosu" + id_Devuelto).removeClass("is-valid").addClass("is-invalid");
            $("#error-mjsf-" + id_Devuelto).html('Por favor tome una foto del exhibidor');
        } else {
            ContaCorrecto += 1;
            $("#filefotosu" + id_Devuelto).removeClass("is-invalid").addClass("is-valid");
            $("#error-mjsf-" + id_Devuelto).html('');
        }
        if ($("#txtidcat" + id_Devuelto).val() != '85000000001' && $("#txtidcat" + id_Devuelto).val() != '99999999999' && $("#txtidcat" + id_Devuelto).val() != '11111111111' && $("#txtidcat" + id_Devuelto).val() != '22222222222' && $("#txtidcat" + id_Devuelto).val() != '33333333333' && $("#txtidcat" + id_Devuelto).val() != '44444444444' && $("#txtidcat" + id_Devuelto).val() != '55555555555' && $("#txtidcat" + id_Devuelto).val() != '88888888888' && $("#txtidcat" + id_Devuelto).val() != '12121212121' && $("#txtidcat" + id_Devuelto).val() != '13131313131') {
            ContaCorrecto += 1; // TIPO DE EXHIBIDOR
            ContaCorrecto += 7; // CAPACIDAD DE EXHIBIDOR
        } else {
            var checks = document.getElementsByClassName("chk" + id_Devuelto);
            var val_checks = false
            for (var i = 0; i < checks.length; i++) {
                if (checks[i].checked == true) {
                    val_checks = true;
                }
            }
            if (val_checks == true) {
                $(".chk" + id_Devuelto).removeClass("is-invalid").addClass("is-valid");
                ContaCorrecto += 1;
            } else {
                $(".chk" + id_Devuelto).removeClass("is-valid").addClass("is-invalid");
                ContaCorrecto += 0;
            }
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#txtrt" + id_Devuelto).val(), 'txtrt', 'error-mjsr-', 'RISTRA', $("#txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#txtpq" + id_Devuelto).val(), 'txtpq', 'error-mjsp-', 'PAQUETE', $("#txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#txtpines" + id_Devuelto).val(), 'txtpines', 'error-mjspn-', 'PINES', $("#txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#txtun" + id_Devuelto).val(), 'txtun', 'error-mjsun-', 'UNIDADES', $("#txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#txtbolsas" + id_Devuelto).val(), 'txtbolsas', 'error-mjsbl-', 'BOLSAS', $("#txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_EnteroMYQc(id_Devuelto, $("#txtcaras" + id_Devuelto).val(), 'txtcaras', 'error-mjscara-', 'CARAS DE EXHIBICION', $("#txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#txtbotes" + id_Devuelto).val(), 'txtbotes', 'error-mjsbt-', 'BOTES', $("#txtidcat_desc" + id_Devuelto).val());
        }
        if (!_empty($('#observacion_exh' + id_Devuelto).val())) {
            $('#observacion_exh' + id_Devuelto).removeClass("is-invalid").addClass("is-valid");
            ContaCorrecto += 1;
        } else {
            $('#observacion_exh' + id_Devuelto).removeClass("is-valid").addClass("is-invalid");
            ContaCorrecto += 0;
        }
        if (ContaCorrecto < 10) {
            Swal.fire({
                title: '<strong>Atención!</strong>',
                type: 'info',
                html: '<p>El ' + $("#txtidcat_desc" + id_Devuelto).val() + ' no se pueden agregar a exhibidores devueltos, hay campos incompletos.</p><p><span class="fas fa-exclamation-triangle fa-3x" style="color:#EEEB38;"></span></p>',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#error_incompleto" + id_Devuelto).show();
            });
        } else {
            $("#modalMotivoDevol").modal("toggle");
        }
    });
    $('#div_detExh').on('click', '.d_qt_bt_devu', function () {
        Opcion_Dev = 2;
        var ContaCorrecto = 0;
        warn_on_unload = 'no salir';
        var id_Devuelto = $(this).attr("id");
        var info_dev = "";
        id_Devuelto = id_Devuelto.substring(10, id_Devuelto.length);
        var det_exhibidor = `
        <div class="fichz__">
            <div class='box01'>
                <div class='box02'>
                </div>
                <div class='box03'>
                    <span style="color: #2BA6CB"></span>
                </div>
            </div>
            <div class='segdos'>
                <div class='seg_i'>SKU: ${
            $("#d_txtidcat" + id_Devuelto).val()
        }</div>
                <div class='seg_d'>${
            $("#d_txtidcat_desc" + id_Devuelto).val()
        }</div>
            </div>
        </div>`;
        $("#registroDevol").val(id_Devuelto);
        $("#det_exhdevolu").empty().html(det_exhibidor);
        // VERIFICAR DESPUES, ACTUALIZACION FOTOS INECESARIO
        if ($("#d_txtidcat" + id_Devuelto).val() != '85000000001' && $("#d_txtidcat" + id_Devuelto).val() != '99999999999' && $("#d_txtidcat" + id_Devuelto).val() != '11111111111' && $("#d_txtidcat" + id_Devuelto).val() != '22222222222' && $("#d_txtidcat" + id_Devuelto).val() != '33333333333' && $("#d_txtidcat" + id_Devuelto).val() != '44444444444' && $("#d_txtidcat" + id_Devuelto).val() != '55555555555' && $("#d_txtidcat" + id_Devuelto).val() != '88888888888' && $("#d_txtidcat" + id_Devuelto).val() != '12121212121' && $("#d_txtidcat" + id_Devuelto).val() != '13131313131') {
            ContaCorrecto += 1; // TIPO DE EXHIBIDOR
            ContaCorrecto += 7; // CAPACIDAD DE EXHIBIDOR
        } else {
            var checks = document.getElementsByClassName("chka" + id_Devuelto);
            var val_checks = false
            for (var i = 0; i < checks.length; i++) {
                if (checks[i].checked == true) {
                    val_checks = true;
                }
            }
            if (val_checks == true) {
                $(".chka" + id_Devuelto).removeClass("is-invalid").addClass("is-valid");
                ContaCorrecto += 1;
            } else {
                $(".chka" + id_Devuelto).removeClass("is-valid").addClass("is-invalid");
                ContaCorrecto += 0;
            } ContaCorrecto += V_input_Entero(id_Devuelto, $("#d_txtrt" + id_Devuelto).val(), 'd_txtrt', 'd_error-mjsr-', 'RISTRA', $("#d_txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#d_txtpq" + id_Devuelto).val(), 'd_txtpq', 'd_error-mjsp-', 'PAQUETE', $("#d_txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#d_txtpines" + id_Devuelto).val(), 'd_txtpines', 'd_error-mjspn-', 'PINES', $("#d_txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#d_txtun" + id_Devuelto).val(), 'd_txtun', 'd_error-mjsun-', 'UNIDADES', $("#d_txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#d_txtbolsas" + id_Devuelto).val(), 'd_txtbolsas', 'd_error-mjsbl-', 'BOLSAS', $("#d_txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_EnteroMYQc(id_Devuelto, $("#d_txtcaras" + id_Devuelto).val(), 'd_txtcaras', 'd_error-mjscara-', 'CARA DE EXHIBICION', $("#d_txtidcat_desc" + id_Devuelto).val());
            ContaCorrecto += V_input_Entero(id_Devuelto, $("#d_txtbotes" + id_Devuelto).val(), 'd_txtbotes', 'd_error-mjsbt-', 'BOTES', $("#d_txtidcat_desc" + id_Devuelto).val());
        }
        if (!_empty($('#d_observacion_exh' + id_Devuelto).val())) {
            $('#d_observacion_exh' + id_Devuelto).removeClass("is-invalid").addClass("is-valid");
            ContaCorrecto += 1;
        } else {
            $('#d_observacion_exh' + id_Devuelto).removeClass("is-valid").addClass("is-invalid");
            ContaCorrecto += 0;
        }
        if (ContaCorrecto < 9) {
            Swal.fire({
                title: '<strong>Atención!</strong>',
                type: 'info',
                html: '<p>El ' + $("#d_txtidcat_desc" + id_Devuelto).val() + ' no se pueden agregar a exhibidores devueltos, hay campos incompletos.</p><p><span class="fas fa-exclamation-triangle fa-3x" style="color:#EEEB38;text-align:center;"></span></p>',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#d_error_incompleto" + id_Devuelto).show();
            });
        } else {
            $("#modalMotivoDevol").modal("toggle");
        }
    });
    $('#div_detExh').on('click', '.d_qt_bt_eliminar', async function () {
        var id_eliminar = $(this).attr("id");
        id_eliminar = id_eliminar.substring(10, id_eliminar.length);
        var det_exhibidor = ``;
        det_exhibidor = `
        <div class="fichz__">
            <div class='box01'>
                <div class='box02'>
                </div>
                <div class='box03'>
                    <span style="color: #2BA6CB"></span>
                </div>
            </div>
            <div class='segdos'>
                <div class='seg_i'>SKU: ${
            $("#d_txtidcat" + id_eliminar).val()
        }</div>
                <div class='seg_d'>${
            $("#d_txtidcat_desc" + id_eliminar).val()
        }</div>
            </div>
        </div>`;
        $("#registroElim").val(id_eliminar);
        $("#det_exheliminar").empty().html(det_exhibidor);
        $("#modalMotivoElim").modal("toggle");
    });
    $('#modalMotivoElim').on('hidden.bs.modal', function (e) {
        var id_elim = $("#registroElim").val();
        if (Ok_Eliminado == 2) {
            $('#d_content_exh' + id_elim).fadeOut(250).fadeIn(250, function () {
                $("#txtelimR").val('')
                $("#error_elimR").html('');
                $("#d_content_exh" + id_elim).hide();
                $("#txtelimR").removeClass("is-valid");
                Ok_Eliminado = 0;
                $("#d_modificado" + id_elim).val(1);
            });
        } else {
            $("#txtelimR").val('')
            $("#error_elimR").html('');
            $("#txtelimR").removeClass("is-valid");
            Ok_Eliminado = 0;
        }
    });
    $('#modalMotivoDevol').on('hidden.bs.modal', function (e) {
        var id_Devuelto = $("#registroDevol").val();
        if (Ok_devolucion == 2) {
            if (Opcion_Dev == 1) {
                var txtidcat = '',
                    txtidcat_desc = '',
                    observacion_exh = '',
                    txtrt = '',
                    txtpq = '',
                    txtpines = '',
                    txtun = '',
                    comentarioCliexh = '';
                var txtidbols = '';
                var txtcaras = '';
                var txtbotes = '';
                var tvtSte_Mot_Id = '';
                txtidcat = $("#txtidcat" + id_Devuelto).val();
                txtidcat_desc = $("#txtidcat_desc" + id_Devuelto).val();
                txtrt = $("#txtrt" + id_Devuelto).val();
                txtpq = $("#txtpq" + id_Devuelto).val();
                txtpines = $("#txtpines" + id_Devuelto).val();
                txtun = $("#txtun" + id_Devuelto).val();
                txtidbols = $("#txtbolsas" + id_Devuelto).val();
                txtcaras = $("#txtcaras" + id_Devuelto).val();
                txtbotes = $("#txtbotes" + id_Devuelto).val();
                observacion_exh = $("#observacion_exh" + id_Devuelto).val();
                comentarioCliexh = $("#txtdevol").val();
                tvtSte_Mot_Id = $("#Ste_Mot_Id" + id_Devuelto).val();
               // console.log('MOTIVO DEVOLUCION ' + tvtSte_Mot_Id);
                var Is_Cheked_G = false;
                Is_Cheked_G = document.getElementById('checktipoexhg' + id_Devuelto).checked;
                var Is_Cheked_S = false;
                Is_Cheked_S = document.getElementById('checktipoexhs' + id_Devuelto).checked;
                var Is_Cheked_C = false;
                Is_Cheked_C = document.getElementById('checktipoexhc' + id_Devuelto).checked;
                var Is_Cheked_O = false;
                Is_Cheked_O = document.getElementById('checktipoexho' + id_Devuelto).checked;
                $("#edit_exh" + id_Devuelto).remove();
                $("#quit_exh" + id_Devuelto).remove();
                // $("#quit_qui" + id_Devuelto).remove();
                var copy_divExh = '<div id="content_exh' + id_Devuelto + '" class="fichz__">' + $('#content_exh' + id_Devuelto).html() + '</div>';
                copy_divExh = copy_divExh.replace("seg", "segdos");
                if (cantidadDe <= 0) {
                    $("#list_devul").empty();
                }
                $('#det_devul').append(copy_divExh);
                $('#content_exh' + id_Devuelto).remove();
                $("#txtidcat" + id_Devuelto).val(txtidcat);
                $("#txtidcat_desc" + id_Devuelto).val(txtidcat_desc);
                $("#txtrt" + id_Devuelto).val(txtrt);
                $("#txtpq" + id_Devuelto).val(txtpq);
                $("#txtpines" + id_Devuelto).val(txtpines);
                $("#txtun" + id_Devuelto).val(txtun);
                $("#txtbolsas" + id_Devuelto).val(txtidbols);
                $("#txtcaras" + id_Devuelto).val(txtcaras);
                $("#txtbotes" + id_Devuelto).val(txtbotes);
                $("#observacion_exh" + id_Devuelto).val(observacion_exh);
                $("#comentarioCliexh" + id_Devuelto).val(comentarioCliexh);
                $("#txttipoac" + id_Devuelto).val(2);
                $("#Ste_Mot_Id" + id_Devuelto).val(tvtSte_Mot_Id);
                if (Is_Cheked_G) {
                    $("#checktipoexhg" + id_Devuelto).prop("checked", true);
                }
                if (Is_Cheked_S) {
                    $("#checktipoexhs" + id_Devuelto).prop("checked", true);
                }
                if (Is_Cheked_C) {
                    $("#checktipoexhc" + id_Devuelto).prop("checked", true);
                }
                if (Is_Cheked_O) {
                    $("#checktipoexho" + id_Devuelto).prop("checked", true);
                }
                // $('#span_op'+id_Devuelto).append(`<span id="quit_qui${id_Devuelto}" class='fas fa-trash-alt fa-2x qt_bt_quitar'></span>`);
                $('#span_op' + id_Devuelto).append(`<span id="undo${id_Devuelto}" class='fas fa-undo-alt fa-2x qt_bt_undo'></span>`);

                $("#filtromotivosD").val('');
                $("#txtdevol").val('');
                $("#filtromotivosD").removeClass("is-valid");
                $("#txtdevol").removeClass("is-valid");
            } else {
                var txtidcat = '',
                    txtidcat_desc = '',
                    observacion_exh = '',
                    txtrt = '',
                    txtpq = '',
                    txtpines = '',
                    txtun = '',
                    comentarioCliexh = '';
                var txtidbols = '';
                var txtcaras = '';
                var txtbotes = '';
                var tvtSte_Mot_Id = '';
                txtidcat = $("#d_txtidcat" + id_Devuelto).val();
                txtidcat_desc = $("#d_txtidcat_desc" + id_Devuelto).val();
                txtrt = $("#d_txtrt" + id_Devuelto).val();
                txtpq = $("#d_txtpq" + id_Devuelto).val();
                txtpines = $("#d_txtpines" + id_Devuelto).val();
                txtun = $("#d_txtun" + id_Devuelto).val();
                txtidbols = $("#d_txtbolsas" + id_Devuelto).val();
                txtcaras = $("#d_txtcaras" + id_Devuelto).val();
                txtbotes = $("#d_txtbotes" + id_Devuelto).val();
                observacion_exh = $("#d_observacion_exh" + id_Devuelto).val();
                comentarioCliexh = $("#d_comentarioCliexh" + id_Devuelto).val();
                tvtSte_Mot_Id = $("#d_Ste_Mot_Id" + id_Devuelto).val();
                $("#d_edit_exh" + id_Devuelto).remove();
                $("#d_quit_exh" + id_Devuelto).remove();
                var copy_divExh = '<div id="d_content_exh' + id_Devuelto + '" class="fichz__">' + $('#d_content_exh' + id_Devuelto).html() + '</div>';
                copy_divExh = copy_divExh.replace("seg", "segdos");
                if (cantidadDe <= 0) {
                    $("#list_devul").empty();
                }
                $('#det_devul').append(copy_divExh);
                $('#d_content_exh' + id_Devuelto).remove();
                $("#d_txtidcat" + id_Devuelto).val(txtidcat);
                $("#d_txtidcat_desc" + id_Devuelto).val(txtidcat_desc);
                $("#d_txtrt" + id_Devuelto).val(txtrt);
                $("#d_txtpq" + id_Devuelto).val(txtpq);
                $("#d_txtpines" + id_Devuelto).val(txtpines);
                $("#d_txtun" + id_Devuelto).val(txtun);
                $("#d_txtbolsas" + id_Devuelto).val(txtidbols);
                $("#d_txtcaras" + id_Devuelto).val(txtcaras);
                $("#d_txtbotes" + id_Devuelto).val(txtbotes);
                $("#d_observacion_exh" + id_Devuelto).val(observacion_exh);
                $("#d_comentarioCliexh" + id_Devuelto).val(comentarioCliexh);
                $("#d_txttipoac" + id_Devuelto).val(2);
                $("#d_modificado" + id_Devuelto).val(1);
                $("#d_Ste_Mot_Id" + id_Devuelto).val(tvtSte_Mot_Id);
                // $('#d_span_op'+id_Devuelto).append(`<span id="d_quit_qui${id_Devuelto}" class='fas fa-trash-alt fa-2x qt_bt_quitar'></span>`);
                $('#d_span_op' + id_Devuelto).append(`<span id="d_undo${id_Devuelto}" class='fas fa-undo-alt fa-2x d_qt_bt_undo'></span>`);
                $("#filtromotivosD").val('');
                $("#txtdevol").val('');
                $("#filtromotivosD").removeClass("is-valid");
                $("#txtdevol").removeClass("is-valid");
            }
        } else {
            $("#filtromotivosD").val('');
            $("#txtdevol").val('');
            $("#filtromotivosD").removeClass("is-valid");
            $("#txtdevol").removeClass("is-valid");
            Ok_devolucion = 0;
        }
    });

    /*$('#modalMotivoElim').on('click', '#confirmar_eliminarR', function () {
        Ok_Eliminado = 0;
        var id_elim = $("#registroElim").val();
        var comentarioDevuelto = $("#txtelimR").val();
        comentarioDevuelto = comentarioDevuelto.length;
        var Sel_eliminar = $("#filtromotivosE").val();
        if (_empty($("#txtelimR").val())) {
            $("#txtelimR").removeClass("is-valid").addClass("is-invalid");
            $("#error_elimR").html('Escribe el motivo para eliminar el registro');
            Ok_Eliminado += 0;
        } else {
            if (comentarioDevuelto < 10) {
                $("#txtelimR").removeClass("is-valid").addClass("is-invalid");
                $("#error_elimR").html('Motivo muy corto');
                Ok_Eliminado += 0;
            } else {
                $("#txtelimR").removeClass("is-invalid").addClass("is-valid");
                $("#error_elimR").html('');
                $("#d_txteliminado" + id_elim).val(1);
                $("#d_Ste_Mot_Id" + id_elim).val(Sel_eliminar);
                $("#d_comentarioCliexh" + id_elim).val($("#txtelimR").val());
                Ok_Eliminado += 1;
            }
        }
        if (_empty(Sel_eliminar)) {
            $("#filtromotivosE").removeClass("is-valid").addClass("is-invalid");
            Ok_Eliminado += 0;
        } else {
            $("#filtromotivosE").removeClass("is-invalid").addClass("is-valid");
            Ok_Eliminado += 1;
        }
        if (Ok_Eliminado == 2) {
            $("#modalMotivoElim").modal("toggle");
        }
    });*/



    $(document).off('click', '#confirmar_eliminarR').on('click', '#confirmar_eliminarR', function (e) {
    e.preventDefault();
    e.stopPropagation();

    Ok_Eliminado = 0;

    var id_elim = $("#registroElim").val();
    var comentarioTexto = $("#txtelimR").val();
    var comentarioDevuelto = comentarioTexto.length;
    var Sel_eliminar = $("#filtromotivosE").val();
    var privilegio = parseInt(arrg_Credls['privilegio'] || 0);

    console.log('Privilegio detectado:', privilegio);

    // =========================
    // VALIDAR PRIVILEGIOS NUEVOS
    // =========================
    const privilegiosPermitidos = [1, 8, 10, 11, 12, 13, 14,116,29,28,30,184];

    if (!privilegiosPermitidos.includes(privilegio)) {

        // cerrar modal primero
        $("#modalMotivoElim").modal("hide");

        // luego mostrar aviso
        setTimeout(function () {
            Swal.fire({
                title: 'Acceso denegado',
                icon: 'warning',
                html: '<h5>No tiene privilegios para eliminar exhibidores</h5>',
                confirmButtonText: 'Ok'
            });
        }, 300);

        return;
    }

    // =========================
    // VALIDAR COMENTARIO
    // =========================
    if (_empty(comentarioTexto)) {
        $("#txtelimR").removeClass("is-valid").addClass("is-invalid");
        $("#error_elimR").html('Escribe el motivo para eliminar el registro');
        Ok_Eliminado += 0;
    } else {
        if (comentarioDevuelto < 10) {
            $("#txtelimR").removeClass("is-valid").addClass("is-invalid");
            $("#error_elimR").html('Motivo muy corto');
            Ok_Eliminado += 0;
        } else {
            $("#txtelimR").removeClass("is-invalid").addClass("is-valid");
            $("#error_elimR").html('');
            Ok_Eliminado += 1;
        }
    }

    // =========================
    // VALIDAR MOTIVO
    // =========================
    if (_empty(Sel_eliminar)) {
        $("#filtromotivosE").removeClass("is-valid").addClass("is-invalid");
        Ok_Eliminado += 0;
    } else {
        $("#filtromotivosE").removeClass("is-invalid").addClass("is-valid");
        Ok_Eliminado += 1;
    }

    // =========================
    // PROCESO FINAL
    // =========================
    if (Ok_Eliminado == 2) {
        $("#d_txteliminado" + id_elim).val(1);
        $("#d_Ste_Mot_Id" + id_elim).val(Sel_eliminar);
        $("#d_comentarioCliexh" + id_elim).val(comentarioTexto);

        $("#modalMotivoElim").modal("toggle");
    }
});


    
    $('#modalMotivoDevol').on('click', '#confirmar_devolucion', function () {
        Ok_devolucion = 0;
        var id_devu = $("#registroDevol").val();
        var comentarioDevuelto = $("#txtdevol").val();
        comentarioDevuelto = comentarioDevuelto.length;
        var Sel_devolucion = $("#filtromotivosD").val();
        if (_empty($("#txtdevol").val())) {
            $("#txtdevol").removeClass("is-valid").addClass("is-invalid");
            $("#error_devolu").html('Escribe el motivo para devolver el exhibidor');
            Ok_devolucion += 0;
        } else {
            if (comentarioDevuelto < 10) {
                $("#txtdevol").removeClass("is-valid").addClass("is-invalid");
                $("#error_devolu").html('Motivo muy corto');
                Ok_devolucion += 0;
            } else {
                $("#txtdevol").removeClass("is-invalid").addClass("is-valid");
                $("#error_devolu").html('');
                // $("#d_txteliminado"+id_devu).val(1);
                if (Opcion_Dev == 1) {
                    $("#comentarioCliexh" + id_devu).val($("#txtdevol").val());
                    $("#Ste_Mot_Id" + id_devu).val(Sel_devolucion);
                } else {
                    $("#d_comentarioCliexh" + id_devu).val($("#txtdevol").val());
                    $("#d_Ste_Mot_Id" + id_devu).val(Sel_devolucion);
                } Ok_devolucion += 1;
            }
        }
        if (_empty(Sel_devolucion)) {
            $("#filtromotivosD").removeClass("is-valid").addClass("is-invalid");
            Ok_devolucion += 0;
        } else {
            $("#filtromotivosD").removeClass("is-invalid").addClass("is-valid");
            Ok_devolucion += 1;
        }
        if (Ok_devolucion == 2) {
            $("#modalMotivoDevol").modal("toggle");
            $("#filtromotivosD").removeClass("is-valid");
            $("#txtdevol").removeClass("is-valid");
        }
    });



    $('#div_detExh').on('click', '.qt_bt_quitar', function () {
        var id_quitar = $(this).attr("id");
        id_quitar = id_quitar.substring(8, id_quitar.length);
        $('#content_exh' + id_quitar).remove();
    });
    $('#div_detExh').on('click', '.qt_bt_undo', function () {
        var id_undo = $(this).attr("id");
        id_undo = id_undo.substring(4, id_undo.length);
        // console.log('el ide del regresando => '+id_undo);
        var txtidcat = '',
            txtidcat_desc = '',
            observacion_exh = '',
            txtrt = '',
            txtpq = '',
            txtpines = '',
            txtun = '',
            comentarioCliexh = '';
        var txtidbols = '';
        var txtcaras = '';
        var txtbotes = '';
        txtidcat = $("#txtidcat" + id_undo).val();
        txtidcat_desc = $("#txtidcat_desc" + id_undo).val();
        txtrt = $("#txtrt" + id_undo).val();
        txtpq = $("#txtpq" + id_undo).val();
        txtpines = $("#txtpines" + id_undo).val();
        txtun = $("#txtun" + id_undo).val();
        txtidbols = $("#txtbolsas" + id_undo).val();
        txtcaras = $("#txtcaras" + id_undo).val();
        txtbotes = $("#txtbotes" + id_undo).val();
        observacion_exh = $("#observacion_exh" + id_undo).val();
        comentarioCliexh = $("#comentarioCliexh" + id_undo).val();
        var Is_Cheked_G = false;
        Is_Cheked_G = document.getElementById('checktipoexhg' + id_undo).checked;
        var Is_Cheked_S = false;
        Is_Cheked_S = document.getElementById('checktipoexhs' + id_undo).checked;
        var Is_Cheked_C = false;
        Is_Cheked_C = document.getElementById('checktipoexhc' + id_undo).checked;
        var Is_Cheked_O = false;
        Is_Cheked_O = document.getElementById('checktipoexho' + id_undo).checked;
        $("#undo" + id_undo).remove();
        // $("#quit_exh" + id_undo).remove();
        $("#quit_qui" + id_undo).remove();
        var copy_divExh = '<div id="content_exh' + id_undo + '" class="fichz__">' + $('#content_exh' + id_undo).html() + '</div>';
        copy_divExh = copy_divExh.replace("segdos", "seg");
        $('#content_exh' + id_undo).remove();
        $('#list_qtiene').append(copy_divExh);
        $("#txtidcat" + id_undo).val(txtidcat);
        $("#txtidcat_desc" + id_undo).val(txtidcat_desc);
        $("#txtrt" + id_undo).val(txtrt);
        $("#txtpq" + id_undo).val(txtpq);
        $("#txtpines" + id_undo).val(txtpines);
        $("#txtun" + id_undo).val(txtun);
        $("#txtbolsas" + id_undo).val(txtidbols);
        $("#txtcaras" + id_undo).val(txtcaras);
        $("#txtbotes" + id_undo).val(txtbotes);
        $("#observacion_exh" + id_undo).val(observacion_exh);
        $("#comentarioCliexh" + id_undo).val('');
        $("#txttipoac" + id_undo).val(1);
        if (Is_Cheked_G) {
            $("#checktipoexhg" + id_undo).prop("checked", true);
        }
        if (Is_Cheked_S) {
            $("#checktipoexhs" + id_undo).prop("checked", true);
        }
        if (Is_Cheked_C) {
            $("#checktipoexhc" + id_undo).prop("checked", true);
        }
        if (Is_Cheked_O) {
            $("#checktipoexho" + id_undo).prop("checked", true);
        }
        $('#span_op' + id_undo).append(`<span id="edit_exh${id_undo}" class='fas fa-pencil-alt fa-2x qt_bt_edit'></span>`);
        $('#span_op' + id_undo).append(`<span id="quit_exh${id_undo}" class='fas fa-exchange-alt fa-2x qt_bt_devu'></span>`);
        $('#span_op' + id_undo).append(`<span id="quit_qui${id_undo}" class='fas fa-trash-alt fa-2x qt_bt_quitar'></span>`);
    });
    $('#div_detExh').on('click', '.d_qt_bt_undo', function () {
        var id_undo = $(this).attr("id");
        id_undo = id_undo.substring(6, id_undo.length);
        // console.log('el ide del regresando => '+id_undo);
        var txtidcat = '',
            txtidcat_desc = '',
            observacion_exh = '',
            txtrt = '',
            txtpq = '',
            txtpines = '',
            txtun = '',
            comentarioCliexh = '';
        var txtidbols = '';
        var txtcaras = '';
        var d_txttipoac_S = '';
        var txtbotes = '';
        txtidcat = $("#d_txtidcat" + id_undo).val();
        txtidcat_desc = $("#d_txtidcat_desc" + id_undo).val();
        txtrt = $("#d_txtrt" + id_undo).val();
        txtpq = $("#d_txtpq" + id_undo).val();
        txtpines = $("#d_txtpines" + id_undo).val();
        txtun = $("#d_txtun" + id_undo).val();
        txtidbols = $("#d_txtbolsas" + id_undo).val();
        txtcaras = $("#d_txtcaras" + id_undo).val();
        txtbotes = $("#d_txtbotes" + id_undo).val();
        d_txttipoac_S = $("#d_txttipoac_S" + id_undo).val();
        observacion_exh = $("#d_observacion_exh" + id_undo).val();
        comentarioCliexh = $("#d_comentarioCliexh" + id_undo).val();
        var Is_Cheked_G = false;
        Is_Cheked_G = document.getElementById('d_checktipoexhg' + id_undo).checked;
        var Is_Cheked_S = false;
        Is_Cheked_S = document.getElementById('d_checktipoexhs' + id_undo).checked;
        var Is_Cheked_C = false;
        Is_Cheked_C = document.getElementById('d_checktipoexhc' + id_undo).checked;
        var Is_Cheked_O = false;
        Is_Cheked_O = document.getElementById('d_checktipoexho' + id_undo).checked;
        $("#d_undo" + id_undo).remove();
        $("#d_quit_qui" + id_undo).remove();
        var copy_divExh = '<div id="d_content_exh' + id_undo + '" class="fichz__">' + $('#d_content_exh' + id_undo).html() + '</div>';
        copy_divExh = copy_divExh.replace("segdos", "seg");
        $('#d_content_exh' + id_undo).remove();
        $('#list_qtiene').append(copy_divExh);
        $("#d_txtidcat" + id_undo).val(txtidcat);
        $("#d_txtidcat_desc" + id_undo).val(txtidcat_desc);
        $("#d_txtrt" + id_undo).val(txtrt);
        $("#d_txtpq" + id_undo).val(txtpq);
        $("#d_txtpines" + id_undo).val(txtpines);
        $("#d_txtun" + id_undo).val(txtun);
        $("#d_txtbolsas" + id_undo).val(txtidbols);
        $("#d_txtcaras" + id_undo).val(txtcaras);
        $("#d_txtbotes" + id_undo).val(txtbotes);
        $("#d_observacion_exh" + id_undo).val(observacion_exh);
        $("#d_comentarioCliexh" + id_undo).val('');
        $("#d_txttipoac" + id_undo).val(d_txttipoac_S);
        $("#d_modificado" + id_undo).val(1);
        if (Is_Cheked_G) {
            $("#d_checktipoexhg" + id_undo).prop("checked", true);
        }
        if (Is_Cheked_S) {
            $("#d_checktipoexhs" + id_undo).prop("checked", true);
        }
        if (Is_Cheked_C) {
            $("#d_checktipoexhc" + id_undo).prop("checked", true);
        }
        if (Is_Cheked_O) {
            $("#d_checktipoexho" + id_undo).prop("checked", true);
        }
        $('#d_span_op' + id_undo).append(`<span id="d_edit_exh${id_undo}" class='fas fa-pencil-alt fa-2x d_qt_bt_edit'></span>`);
        $('#d_span_op' + id_undo).append(`<span id="d_quit_exh${id_undo}" class='fas fa-exchange-alt fa-2x d_qt_bt_devu'></span>`);
        $('#d_span_op' + id_undo).append(`<span id="d_quit_qui${id_undo}" class='fas fa-trash-alt fa-2x d_qt_bt_eliminar'></span>`);
    });
    // Manejador de eventos para los botones
    $('#div_detExh').on('click', '.btn_ac_exh', function () {
        var id_modf = $(this).attr("id");
        id_modf = id_modf.substring(11, id_modf.length);
        $("#d_modificado" + id_modf).val(1);
      //  console.log('boton detalles exhibidor:', id_modf);
    });

    // Manejador de eventos para los checkboxes
$('#div_detExh').on('click', '.btn_ac_rev', function () {
    var id_modf = $(this).attr("id");
    id_modf = id_modf.substring(10, id_modf.length);
    $("#d_modificado" + id_modf).val(1);

    // Desactivar el botón
    $(this).attr('disabled', 'disabled');
    
    // Cambiar el estilo del botón para que parezca desactivado
    $(this).css({
        'background-color': '#0cc370',  // Puedes cambiar el color a uno más claro
        'color': '#888'  // Cambia el color del texto a uno más claro si lo deseas
    });

    // Puedes quitar el icono de marca de verificación si lo deseas
    // $(this).find('i').remove();

   // console.log('boton detalles exhibidor:', id_modf);
});


});
function Consultar_Exhibidores(dataCli) {
    return new Promise(function (resolve, reject) {
        var datosObtenidosd = [];
        var actived = dataBaseAppSDV.result;
        let transacciond = actived.transaction('tbl_status_exhibidores', 'readonly'),
            stored = transacciond.objectStore('tbl_status_exhibidores'),
            indiced = stored.index('by_Ste_Cli_Id'),
            cursord = indiced.openCursor(dataCli[0])
        cursord.onsuccess = function (event) {
            let datd = event.target.result;
            if (datd) {
                datosObtenidosd.push(datd.value);
                datd.continue();
            } else {
                IdCliente = dataCli[0];
                let Exh_QTiene = datosObtenidosd.filter(tipo_actu => tipo_actu.Ste_status == "1" || tipo_actu.Ste_status == "3");
                Exh_QTiene = Exh_QTiene.filter(tipo_actu => tipo_actu.Ste_eliminado == "0");
                let Exh_Devueltos = datosObtenidosd.filter(tipo_actu => tipo_actu.Ste_status == "2" && tipo_actu.Ste_eliminado == "0");
                $("#txt_nom_cli_cod").html($("#codigoCli").val() + ' - ' + $("#nombreCli").val());
                $.when($("#div_prinBody").stop(true, true).hide()).done(function (x) {
                    $.when($("#div_detExh").stop(true, true).show()).done(function (x) {
                        $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                            var CantiExqtiene = (Object.keys(Exh_QTiene).length);
                            var CantiExDevuel = (Object.keys(Exh_Devueltos).length);
                            cantidadDe = CantiExDevuel;
                            var FechaTelefono = fechaDispositivo();
                            var ct = 0;
                            if (CantiExqtiene <= 0) {
                                var mjs_detalle = ``;
                                mjs_detalle = `
                                <div>
                                    <div class="alert alert-info" role="alert" style="text-align: center;margin-top: 10px;">
                                        <h4 class="alert-heading">Aviso!</h4>
                                        <p>NO HAY REGISTROS PARA MOSTRAR</p>
                                        <hr>
                                        <i class="fas fa-folder-open fa-3x"></i>
                                    </div>
                                </div>`;
                                $("#list_qtiene").empty().html(mjs_detalle);
                                if (arrg_datCli[8] == 1) {
                                    $("#add_btQtiene").hide();
                                } else {
                                    $("#add_btQtiene").show();
                                }
                            } else {
                                var html_detalle = ``;
                                /*---------------------BLOQUEOS POR TIPO AC-----------------------------*/
                                var bloquearObs = ``;
                                var bloquearInp = '',
                                    bloqdivInp = '';
                                var bloq_tipoexh = ``;
                                /*----------------------------------------------------------------------*/
                                for (var key in Exh_QTiene) {
                                    bloqdivInp = '',
                                    bloquearInp = '';
                                    if (Exh_QTiene[key].Ste_Cat_Id != '85000000001' && Exh_QTiene[key].Ste_Cat_Id != '99999999999' && Exh_QTiene[key].Ste_Cat_Id != '11111111111' && Exh_QTiene[key].Ste_Cat_Id != '22222222222' && Exh_QTiene[key].Ste_Cat_Id != '33333333333' && Exh_QTiene[key].Ste_Cat_Id != '44444444444' && Exh_QTiene[key].Ste_Cat_Id != '55555555555' && Exh_QTiene[key].Ste_Cat_Id != '88888888888' && Exh_QTiene[key].Ste_Cat_Id != '12121212121' && Exh_QTiene[key].Ste_Cat_Id != '13131313131') {
                                        bloquearInp = 'readonly';
                                        bloqdivInp = `style="display:none;"`;
                                        bloq_tipoexh = `style="text-align:left;display:none;"`;
                                    } else {
                                        bloq_tipoexh = `style="text-align:left;"`;
                                    }
                                    var SelectedOb = [];
                                    if (Exh_QTiene[key].Ste_estado == 1) {
                                        SelectedOb[0] = 'selected';
                                    } else if (Exh_QTiene[key].Ste_estado == 2) {
                                        SelectedOb[1] = 'selected';
                                    } else if (Exh_QTiene[key].Ste_estado == 3) {
                                        SelectedOb[2] = 'selected';
                                    } else if (Exh_QTiene[key].Ste_estado == 4) {
                                        SelectedOb[3] = 'selected';
                                    } else if (Exh_QTiene[key].Ste_estado == 5) {
                                        SelectedOb[4] = 'selected';
                                    } else if (Exh_QTiene[key].Ste_estado == 6) {
                                        SelectedOb[5] = 'selected';
                                    } else if (Exh_QTiene[key].Ste_estado == 7) {
                                        SelectedOb[6] = 'selected';
                                    } else {
                                        SelectedOb[0] = '';
                                        SelectedOb[1] = '';
                                        SelectedOb[2] = '';
                                        SelectedOb[3] = '';
                                        SelectedOb[4] = '';
                                        SelectedOb[5] = '';
                                        SelectedOb[6] = '';
                                    }

                                    arrg_fotosExhdu[ct] = Exh_QTiene[key].Ste_foto;
                                    arrg_fotosExhdd[ct] = Exh_QTiene[key].Ste_pano;
                                    arrg_fotosExhdt[ct] = Exh_QTiene[key].Ste_despues;


                                    arrg_cambioFoto[ct] = 'NO';
                                    arrg_cambioFotoD[ct] = 'NO';
                                    arrg_cambioFotoT[ct] = 'NO';
                                    var options = {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric'
                                    };
                                    var fecha_formateada = '';
                                    var fecha = new Date(Exh_QTiene[key].Ste_ultima_fecha);
                                    fecha_formateada = fecha.toLocaleDateString("es-ES", options);
                                    var tipoExhibidor = Exh_QTiene[key].Ste_tipo_exhibidor.split(',');
                                    var cheched_te = [];
                                    (tipoExhibidor[0] == '1') ? cheched_te[0] = 'checked' : cheched_te[0] = '';
                                    (tipoExhibidor[1] == '1') ? cheched_te[1] = 'checked' : cheched_te[1] = '';
                                    (tipoExhibidor[2] == '1') ? cheched_te[2] = 'checked' : cheched_te[2] = '';
                                    (tipoExhibidor[3] == '1') ? cheched_te[3] = 'checked' : cheched_te[3] = '';

                                    var textAlerta = "";

                                  //  console.log("panoramica: ", Exh_QTiene[key].Ste_pano);
                                  //  console.log("Despues", Exh_QTiene[key].Ste_despues);
                                    if (Exh_QTiene[key].Ste_pano == "" || Exh_QTiene[key].Ste_despues == "" || Exh_QTiene[key].Ste_pano == null || Exh_QTiene[key].Ste_despues == null) {
                                        textAlerta = "¡Actualizar Fotos!"
                                    }
                                  //  console.log(textAlerta);

                                    html_detalle += `
                                    <div id="d_content_exh${ct}" class="fichz__">
                                    <div class='box001' id='d_span_op${ct}' style='text-align: center;'>
                                    
                                    <button type='button' id='d_checkbox${ct}' class='btn_ac_rev' aria-expanded='true' style='height: 2em;'>
                                    <span><i class="fas fa-check" style="color: black;"></i></span>
                                   </button>
                                        <span id="d_edit_exh${ct}" class='fas fa-pencil-alt fa-2x d_qt_bt_edit' style="font-size: 2em; margin-right: 10px; height: 2em;"></span>
                                        <span id="d_quit_exh${ct}" class='fas fa-exchange-alt fa-2x d_qt_bt_devu' style="font-size: 2em; margin-right: 10px; height: 2em;"></span>
                                        <span id="d_quit_qui${ct}" class='fas fa-trash-alt fa-2x d_qt_bt_eliminar' style="font-size: 2em; margin-right: 10px; height: 2em;"></span>
                                   </div>
                                        <div class='box01' style='text-align: center;'>
                                            <div class='box02'>
                                                <label style='font-weight: 700; font-size:13px; color:#3E5154; text-transform: uppercase;'>
                                                    <span class='fas fa-calendar-alt fa-lg'></span>
                                                    <i>${fecha_formateada}</i>
                                                    <br> 
                                                    <span style='color: #ff0000;'>${textAlerta}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class='seg'>
                                            <div class='seg_i' id="d_sku_id${ct}">SKU: ${
                                        Exh_QTiene[key].Ste_Cat_Id
                                    }<span id="d_error_incompleto${ct}" class="fas fa-exclamation-triangle fa-lg" style="color:#EEEB38;float:right;display:none;"></span></div>
                                            <div class='seg_d' id="d_sku_desc${ct}">${
                                        Exh_QTiene[key].Ste_Cat_Id_Descripcion
                                    }</div>
                                        </div>
                                        <div class='toggle_observacion'>
                                        <hr>
                                            <button type='button' id='btn_det_exh${ct}' class='btn btn_observacion btn_ac_exh' data-toggle='collapse' data-target='#d_collapse_observacionno${ct}' aria-expanded='true'><span class='fa fa-eye fa-lg'></span> DETALLES EXHIBIDOR</button>
                                            <div id='d_collapse_observacionno${ct}' class='col-md collapse gretro__'>
                                                <div class='row divcollapse'>
                                                <hr class="sig__">
                                                    <div class='col-md' style='padding-bottom:5px;'>
                                                        <div class="form-group">
                                                        <div style="text-align: center;">
                                                        <div>
                                                            <span class="fas fa-camera fa-2x"></span>
                                                            <label style="font-weight: 700;">Foto Antes</label>
                                                            <div class="custom-file mb-3" style="text-align: left;">
                                                                <input type="file" class="custom-file-input file_ud" id="filefotosud${ct}" name="filefotosud[]" lang="es" accept="image/*">
                                                                <label class="custom-file-label" data-browse="Tomar foto">Im&aacute;gen</label>
                                                            </div>
                                                            <img id="imagenud${ct}" src="${Exh_QTiene[key].Ste_foto}" style="border: 1px solid black; margin-bottom: 10px; width:200px; height:200px; display: block; margin: 0 auto;" width="200px" height="200px">
                                                            <div class="valid-feedback"></div>
                                                            <div class="invalid-feedback" id="d_error-mjsf-antes-${ct}"></div>
                                                        </div>
                                                        <hr style="border-top: 1px solid black; margin-bottom: 20px;">
                                                    </div>
                                                    

                                                    <div style="text-align: center;">
                                                        <div>
                                                            <span class="fas fa-camera fa-2x"></span>
                                                            <label style="font-weight: 700;">Foto Después</label>
                                                            <div class="custom-file mb-3" style="text-align: left;">
                                                                <input type="file" class="custom-file-input file_td" id="filefotostd${ct}" name="filefotostd[]" lang="es" accept="image/*">
                                                                <label class="custom-file-label" data-browse="Tomar foto">Im&aacute;gen</label>
                                                            </div>
                                                            <img id="imagentd${ct}" src="${Exh_QTiene[key].Ste_despues}" style="border: 1px solid black; margin-bottom: 10px; width:200px; height:200px; display: block; margin: 0 auto;" width="200px" height="200px">
                                                            <div class="valid-feedback"></div>
                                                            <div class="invalid-feedback" id="d_error-mjsf-despues-${ct}"></div>
                                                        </div>
                                                    </div>


                                                    <div style="text-align: center;">
                                                        <div>
                                                            <span class="fas fa-camera fa-2x"></span>
                                                            <label style="font-weight: 700;">Foto Panorámica</label>
                                                            <div class="custom-file mb-3" style="text-align: left;">
                                                                <input type="file" class="custom-file-input file_dd" id="filefotosdd${ct}" name="filefotosdd[]" lang="es" accept="image/*">
                                                                <label class="custom-file-label" data-browse="Tomar foto">Im&aacute;gen</label>
                                                            </div>
                                                            <img id="imagendd${ct}" src="${Exh_QTiene[key].Ste_pano}" style="border: 1px solid black; margin-bottom: 10px; width:200px; height:200px; display: block; margin: 0 auto;" width="200px" height="200px">
                                                            <div class="valid-feedback"></div>
                                                            <div class="invalid-feedback" id="d_error-mjsf-panoramica-${ct}"></div>
                                                        </div>
                                                        <hr style="border-top: 1px solid black; margin-bottom: 20px;">
                                                    </div>
                                                    
                                                    
                                                        <div ${bloq_tipoexh} id="d_btipo_exh${ct}">
                                                            <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Tipo de Exhibidor</label>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check chka${ct} chksegu" id="d_checktipoexhg${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_te[0]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexhg${ct}">GALLETA&nbsp;&nbsp;</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check chka${ct} chksegu" id="d_checktipoexhs${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_te[1]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexhs${ct}">SNACK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check chka${ct} chksegu" id="d_checktipoexhc${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_te[2]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexhc${ct}">CEREALES</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check chka${ct} chksegu" id="d_checktipoexho${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_te[3]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexho${ct}">CONFITERIA</label>
                                                            </div>
                                                        </div>
                                                        <div ${bloqdivInp} id="d_bcapacidadexh${ct}"><br>
                                                            <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Capacidad del Exhibidor</label>
                                                            <div class="container __p_form">
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Ristra (RT):</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtrt${ct}" name="d_txtrt[]" class="form-control" placeholder="0" value="${
                                        Exh_QTiene[key].Ste_cantidad_RT
                                    }" ${bloquearInp} autocomplete="off">
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsr-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Paquete (PQ):</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtpq${ct}" name="d_txtpq[]"class="form-control" placeholder="0" value="${
                                        Exh_QTiene[key].Ste_cantidad_PQ
                                    }" ${bloquearInp} autocomplete="off">
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsp-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Pines:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtpines${ct}" name="d_txtpines[]" class="form-control" placeholder="0" value="${
                                        Exh_QTiene[key].Ste_cantidad_PINES
                                    }" ${bloquearInp} autocomplete="off">
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjspn-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Unidad (PRODUCTO FAMILIAR):</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtun${ct}" name="d_txtun[]" class="form-control" placeholder="0" value="${
                                        Exh_QTiene[key].Ste_cantidad_UN
                                    }" ${bloquearInp} autocomplete="off">
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsun-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Bolsas:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtbolsas${ct}" name="d_txtbolsas[]" class="form-control" placeholder="0" value="${
                                        Exh_QTiene[key].Ste_cantidad_BOLSAS
                                    }" ${bloquearInp} autocomplete="off">
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsbl-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Botes:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtbotes${ct}" name="d_txtbotes[]" class="form-control" placeholder="0" value="${
                                        Exh_QTiene[key].Ste_cantidad_BOTES
                                    }" ${bloquearInp} autocomplete="off">
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsbt-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Caras de Exhibición:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtcaras${ct}" name="d_txtcaras[]" class="form-control" placeholder="0" value="${
                                        Exh_QTiene[key].Ste_cantidad_CARAS
                                    }" ${bloquearInp} autocomplete="off">
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjscara-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Estado del Exhibidor</label>
                                                        <select class="form-control" id="d_observacion_exh${ct}" name="d_observacion_exh[]">
                                                            <option value="" hidden>Elige una opción...</option>
                                                            <option value="1" ${
                                        SelectedOb[0]
                                    }>VISIBLE Y ACCESIBLE</option>
                                                            <option value="2" ${
                                        SelectedOb[1]
                                    }>MAL UBICADO</option>
                                                            <option value="3" ${
                                        SelectedOb[2]
                                    }>INVADIDO</option>
                                                            <option value="4" ${
                                        SelectedOb[3]
                                    }>NECESITA REPARACION</option>
                                                            <option value="5" ${
                                        SelectedOb[4]
                                    }>DESECHADO O GUARDADO POR EL CLIENTE</option>
                                                            <option value="6" ${
                                        SelectedOb[5]
                                    }>RETIRADO DEL NEGOCIO</option>
                                                            <option value="7" ${
                                        SelectedOb[6]
                                    }>EN BODEGA</option>
                                                        </select>
                                                        <div class="valid-feedback">
                                                            <strong></strong>
                                                        </div>
                                                        <div class="invalid-feedback">
                                                            <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
                                                        </div>
                                                        <div class="row" style="padding: 10px 0px 10px 0px;">
                                                            <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Comentario:</span></div>
                                                            <div class="col">
                                                                <textarea class="form-control" id="d_comentarioCliexh${ct}" name="d_comentarioCliexh[]" maxlength="255">${
                                        Exh_QTiene[key].Ste_comentario
                                    }</textarea>
                                                                <div class="valid-feedback"></div>
                                                                <div class="invalid-feedback" id="d_error-mjsdi-${ct}"></div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="d_txtidcat${ct}" name="d_txtidcat[]" value="${
                                        Exh_QTiene[key].Ste_Cat_Id
                                    }">
                                                        <input type="hidden" id="d_txtidcat_desc${ct}" name="d_txtidcat_desc[]" value="${
                                        Exh_QTiene[key].Ste_Cat_Id_Descripcion
                                    }">
                                                        <input type="hidden" id="d_txttoken${ct}" name="d_txttoken[]" value="${
                                        Exh_QTiene[key].Ste_token
                                    }">
                                                        <input type="hidden" id="d_txtfechatel${ct}" name="d_txtfechatel[]" value="${
                                        Exh_QTiene[key].Ste_fecha_tel
                                    }">
                                                        <input type="hidden" id="d_txtfechatelultima${ct}" name="d_txtfechatelultima[]" value="${FechaTelefono}">
                                                        <input type="hidden" id="d_txttipoac${ct}" name="d_txttipoac[]" value="${
                                        Exh_QTiene[key].Ste_status
                                    }">
                                                        <input type="hidden" id="d_txttipoac_S${ct}" name="d_txttipoac_S[]" value="${
                                        Exh_QTiene[key].Ste_status
                                    }">
                                                        <input type="hidden" id="d_txteliminado${ct}" name="d_txteliminado[]" value="0">
                                                        <input type="hidden" id="d_modificado${ct}" name="d_modificado[]" value="0">
                                                        <input type="hidden" id="d_Ste_Mot_Id${ct}" name="Ste_Mot_Id[]" value="${
                                        Exh_QTiene[key].Ste_Mot_Id
                                    }">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div></div>`;
                                    ct++;
                                }
                                $("#list_qtiene").empty().html(html_detalle);
                                if (arrg_datCli[8] == 1) {
                                    $("#add_btQtiene").hide();
                                } else {
                                    $("#add_btQtiene").show();
                                }
                            }
                            if (CantiExDevuel <= 0) {
                                mjs_detalle = ``;
                                mjs_detalle = `
                                <div>
                                    <div class="alert alert-info" role="alert" style="text-align: center;margin-top: 10px;">
                                        <h4 class="alert-heading">Aviso!</h4>
                                        <p>NO HAY REGISTROS PARA MOSTRAR</p>
                                        <hr>
                                        <i class="fas fa-folder-open fa-3x"></i>
                                    </div>
                                </div>`;
                                $("#list_devul").empty().html(mjs_detalle);
                            } else {
                                var html_detalle = ``;
                                for (var key in Exh_Devueltos) {
                                    bloqdivInp = '',
                                    bloquearInp = '';
                                    bloq_tipoexh = ``;
                                    if (Exh_Devueltos[key].Ste_Cat_Id != '85000000001' && Exh_Devueltos[key].Ste_Cat_Id != '99999999999' && Exh_Devueltos[key].Ste_Cat_Id != '11111111111' && Exh_Devueltos[key].Ste_Cat_Id != '22222222222' && Exh_Devueltos[key].Ste_Cat_Id != '33333333333' && Exh_Devueltos[key].Ste_Cat_Id != '44444444444' && Exh_Devueltos[key].Ste_Cat_Id != '55555555555' && Exh_Devueltos[key].Ste_Cat_Id != '88888888888' && Exh_Devueltos[key].Ste_Cat_Id != '12121212121' && Exh_Devueltos[key].Ste_Cat_Id != '13131313131') {
                                        bloquearInp = 'readonly';
                                        bloqdivInp = `style="display:none;"`;
                                        bloq_tipoexh = `style="text-align:left;display:none;"`;
                                    } else {
                                        bloq_tipoexh = `style="text-align:left;"`;
                                    }
                                    var SelectedOb = [];
                                    if (Exh_Devueltos[key].Ste_estado == 1) {
                                        SelectedOb[0] = 'selected';
                                    } else if (Exh_Devueltos[key].Ste_estado == 2) {
                                        SelectedOb[1] = 'selected';
                                    } else if (Exh_Devueltos[key].Ste_estado == 3) {
                                        SelectedOb[2] = 'selected';
                                    } else if (Exh_Devueltos[key].Ste_estado == 4) {
                                        SelectedOb[3] = 'selected';
                                    } else if (Exh_Devueltos[key].Ste_estado == 5) {
                                        SelectedOb[4] = 'selected';
                                    } else if (Exh_Devueltos[key].Ste_estado == 6) {
                                        SelectedOb[5] = 'selected';
                                    } else if (Exh_Devueltos[key].Ste_estado == 7) {
                                        SelectedOb[6] = 'selected';
                                    } else {
                                        SelectedOb[0] = '';
                                        SelectedOb[1] = '';
                                        SelectedOb[2] = '';
                                        SelectedOb[3] = '';
                                        SelectedOb[4] = '';
                                        SelectedOb[5] = '';
                                        SelectedOb[6] = '';
                                    } 
                                    arrg_fotosExhdu[ct] = Exh_Devueltos[key].Ste_foto;
                                    arrg_cambioFoto[ct] = 'NO';
                                    var options = {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric'
                                    };
                                    var fecha_formateada = '';
                                    var fecha = new Date(Exh_Devueltos[key].Ste_ultima_fecha);
                                    fecha_formateada = fecha.toLocaleDateString("es-ES", options);
                                    var tipoExhibidorD = Exh_Devueltos[key].Ste_tipo_exhibidor.split(',');
                                    var cheched_teD = [];
                                    (tipoExhibidorD[0] == '1') ? cheched_teD[0] = 'checked' : cheched_teD[0] = '';
                                    (tipoExhibidorD[1] == '1') ? cheched_teD[1] = 'checked' : cheched_teD[1] = '';
                                    (tipoExhibidorD[2] == '1') ? cheched_teD[2] = 'checked' : cheched_teD[2] = '';
                                    (tipoExhibidorD[3] == '1') ? cheched_teD[3] = 'checked' : cheched_teD[3] = '';
                                    html_detalle += `
                                    <div id="d_content_exh${ct}" class="fichz__">
                                        <div class='box01'>
                                            <div class='box02'>
                                            <label style='font-weight: 700;font-size:13px;color:#3E5154;text-transform: uppercase;'><span class='fas fa-calendar-alt fa-lg'></span> <i>${fecha_formateada}</i></label>
                                            </div>
                                            <div class='box03'>
                                                <span id="d_quit_qui${ct}" class='fas fa-trash-alt fa-2x d_qt_bt_eliminar'></span>
                                                <span></span>
                                            </div>
                                        </div>
                                        <div class='segdos'>
                                            <div class='seg_i' id="d_sku_id${ct}">SKU: ${
                                        Exh_Devueltos[key].Ste_Cat_Id
                                    }</div>
                                            <div class='seg_d' id="d_sku_desc${ct}">${
                                        Exh_Devueltos[key].Ste_Cat_Id_Descripcion
                                    }</div>
                                        </div>
                                        <div class='toggle_observacion'>
                                        <hr>
                                            <button type='button' id='btn_det_exh${ct}' class='btn btn_observacion btn_ac_exh' data-toggle='collapse' data-target='#d_collapse_observacionno${ct}' aria-expanded='true'><span class='fa fa-eye fa-lg'></span> DETALLES EXHIBIDOR</button>
                                            <div id='d_collapse_observacionno${ct}' class='col-md collapse gretro__'>
                                                <div class='row divcollapse' style='margin-top:10px;'>
                                                <hr class="sig__">
                                                    <div class='col-md' style='padding-bottom:5px;'>
                                                        <div class="form-group">
                                                            <div style="margin-top: 15px;">
                                                                <img id="imagenud${ct}" src="${
                                        Exh_Devueltos[key].Ste_foto
                                    }" style="border: 1px solid black;width:200px;height:200px;float: left;" width="200px" height="200px">
                                                            </div>
                                                            <div class="valid-feedback">
                                                            </div>
                                                            <div class="invalid-feedback" id="d_error-mjsf-${ct}"> 
                                                            </div>
                                                        </div>
                                                        <div ${bloq_tipoexh}>
                                                            <label style="font-size:20px;margin-top:5px;text-transform: uppercase;">Tipo de Exhibidor</label>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check bloc_cheq chka${ct}" id="d_checktipoexhg${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_teD[0]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexhg${ct}">GALLETA</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check bloc_cheq chka${ct}" id="d_checktipoexhs${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_teD[1]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexhs${ct}">SNACK</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check bloc_cheq chka${ct}" id="d_checktipoexhc${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_teD[2]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexhc${ct}">CEREALES</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input GR_Check bloc_cheq chka${ct}" id="d_checktipoexho${ct}" name="d_checktipoexh${ct}[]" value='1' ${
                                        cheched_teD[3]
                                    }>
                                                                <label class="custom-control-label" for="d_checktipoexho${ct}">CONFITERIA</label>
                                                            </div>
                                                        </div>
                                                        <div ${bloqdivInp}><br>
                                                            <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Capacidad del Exhibidor</label>
                                                            <div class="container __p_form">
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Ristra (RT):</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtrt${ct}" name="d_txtrt[]" class="form-control bloq_input" placeholder="0" value="${
                                        Exh_Devueltos[key].Ste_cantidad_RT
                                    }" readonly>
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsr-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Paquete (PQ):</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtpq${ct}" name="d_txtpq[]"class="form-control bloq_input" placeholder="0" value="${
                                        Exh_Devueltos[key].Ste_cantidad_PQ
                                    }" readonly>
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsp-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Pines:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtpines${ct}" name="d_txtpines[]" class="form-control bloq_input" placeholder="0" value="${
                                        Exh_Devueltos[key].Ste_cantidad_PINES
                                    }" readonly>
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjspn-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Unidad (PRODUCTO FAMILIAR):</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtun${ct}" name="d_txtun[]" class="form-control bloq_input" placeholder="0" value="${
                                        Exh_Devueltos[key].Ste_cantidad_UN
                                    }" readonly>
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsun-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Bolsas:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtbolsas${ct}" name="d_txtbolsas[]" class="form-control bloq_input" placeholder="0" value="${
                                        Exh_Devueltos[key].Ste_cantidad_BOLSAS
                                    }" readonly>
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsbl-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Botes:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtbotes${ct}" name="d_txtbotes[]" class="form-control bloq_input" placeholder="0" value="${
                                        Exh_Devueltos[key].Ste_cantidad_BOTES
                                    }" readonly>
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjsbt-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Caras de Exhibición:</span></div>
                                                                    <div class="col">
                                                                        <input type="tel" id="d_txtcaras${ct}" name="d_txtcaras[]" class="form-control bloq_input" placeholder="0" value="${
                                        Exh_Devueltos[key].Ste_cantidad_CARAS
                                    }" readonly>
                                                                        <div class="valid-feedback"></div>
                                                                        <div class="invalid-feedback" id="d_error-mjscara-${ct}"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Estado del Exhibidor</label>
                                                        <select class="form-control bloq_select" id="d_observacion_exh${ct}" name="d_observacion_exh[]">
                                                            <option value="" hidden>Elige una opción...</option>
                                                            <option value="1" ${
                                        SelectedOb[0]
                                    }>VISIBLE Y ACCESIBLE</option>
                                                            <option value="2" ${
                                        SelectedOb[1]
                                    }>MAL UBICADO</option>
                                                            <option value="3" ${
                                        SelectedOb[2]
                                    }>INVADIDO</option>
                                                            <option value="4" ${
                                        SelectedOb[3]
                                    }>NECESITA REPARACION</option>
                                                            <option value="5" ${
                                        SelectedOb[4]
                                    }>DESECHADO O GUARDADO POR EL CLIENTE</option>
                                                            <option value="6" ${
                                        SelectedOb[5]
                                    }>RETIRADO DEL NEGOCIO</option>
                                                            <option value="7" ${
                                        SelectedOb[6]
                                    }>EN BODEGA</option>
                                                        </select>
                                                        <div class="valid-feedback">
                                                            <strong></strong>
                                                        </div>
                                                        <div class="invalid-feedback">
                                                            <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
                                                        </div>
                                                        <div style="padding: 10px 0px 10px 0px;">
                                                            <label style="font-weight: 700;margin-left: 0px;float: left;color:#fff;"> <span class="fas fa-file-alt fa-lg"></span> Comentario</label>
                                                            <textarea class="form-control bloq_textarea" id="d_comentarioCliexh${ct}" name="d_comentarioCliexh[]" maxlength="255" readonly>${
                                        Exh_Devueltos[key].Ste_comentario
                                    }</textarea>
                                                            <div class="valid-feedback">
                                                            </div>
                                                            <div class="invalid-feedback" id="d_error-mjsdi-${ct}">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="d_txtidcat${ct}" name="d_txtidcat[]" value="${
                                        Exh_Devueltos[key].Ste_Cat_Id
                                    }">
                                                        <input type="hidden" id="d_txtidcat_desc${ct}" name="d_txtidcat_desc[]" value="${
                                        Exh_Devueltos[key].Ste_Cat_Id_Descripcion
                                    }">
                                                        <input type="hidden" id="d_txttoken${ct}" name="d_txttoken[]" value="${
                                        Exh_Devueltos[key].Ste_token
                                    }">
                                                        <input type="hidden" id="d_txtfechatel${ct}" name="d_txtfechatel[]" value="${
                                        Exh_Devueltos[key].Ste_fecha_tel
                                    }">
                                                        <input type="hidden" id="d_txtfechatelultima${ct}" name="d_txtfechatelultima[]" value="${FechaTelefono}">
                                                        <input type="hidden" id="d_txttipoac${ct}" name="d_txttipoac[]" value="${
                                        Exh_Devueltos[key].Ste_status
                                    }">
                                                        <input type="hidden" id="d_txteliminado${ct}" name="d_txteliminado[]" value="0">
                                                        <input type="hidden" id="d_modificado${ct}" name="d_modificado[]" value="0">
                                                        <input type="hidden" id="d_Ste_Mot_Id${ct}" name="d_Ste_Mot_Id[]" value="${
                                        Exh_Devueltos[key].Ste_Mot_Id
                                    }">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                    ct++;
                                }
                                $("#list_devul").empty().html(html_detalle);
                                $('.bloq_select,.bloq_textarea').prop("disabled", true);
                            }
                        });
                    });
                });
                // $("#btn_conExh").hide();
                $("#btn_GuaExh").show();
                resolve(1);
            };
        }
        transacciond.onerror = function () { // console.log('ERROR INESPERADO -> LISTA EXHIBIDORES CLIENTE ' + IdCli);
            reject(0);
        };
    });
}
function Consultar_InfoCli(dataCli) {
    arrg_datCli = [];
    bandera_Bloq = 0;
    return new Promise(function (resolve, reject) {
        var datosObtenidosd = [];
        var actived = dataBaseAppSDV.result;
        let transacciond = actived.transaction('tbl_clientes', 'readonly'),
            stored = transacciond.objectStore('tbl_clientes'),
            indiced = stored.index('by_Iti_Cli_Id'),
            cursord = indiced.openCursor(dataCli[0])
        cursord.onsuccess = function (event) {
            let datd = event.target.result;
            if (datd) {
                datosObtenidosd.push(datd.value);
                datd.continue();
            } else {
                arrg_datCli = [
                    dataCli[0],
                    datosObtenidosd[0].Cli_nombre,
                    datosObtenidosd[0].Cli_codigo,
                    datosObtenidosd[0].Cli_direccion,
                    datosObtenidosd[0].Cli_telefono,
                    datosObtenidosd[0].Cli_contacto,
                    datosObtenidosd[0].Cli_actu_exh,
                    datosObtenidosd[0].idx,
                    datosObtenidosd[0].Cli_bloq_exh
                ];
                resolve(1);
            };
        }
        transacciond.onerror = function () {
            console.log('ERROR INESPERADO -> INFO CLIENTES');
            reject(0);
        };
    });
}
/* Envio de cambios en los registros de los exhibidores */
function Enviar_CambiosExhibidores() {
    token_ExhEspec = '';
    Agregar_Exh = [],
    Actualizar_Exh = [];
    Arrg_Mantenimiento = [];
    token_ExhEspec = TokenAC_Exh();
    var arrg_vali_result = new Array(2);
    var ContaCorrecto = 0,V_Ok = 0;
    var CantOk = 0;
    /*____0000000_____ AGREGANDO EXHIBIDORES ______000000______*/
    /*INICIALIZANDO ARRG VALIDACION*/
    C_V_mjs = `<div style="text-align:left;">`;
    $("select[name='observacion_exh[]']").each(function (indice, elemento) {
        var IdOriginal = 0;
        ContaCorrecto = 0;
        IdOriginal = $(this).attr("id");
        IdOriginal = IdOriginal.substring(15, IdOriginal.length);
        var Is_Cheked_G = false;
        Is_Cheked_G = document.getElementById('checktipoexhg' + IdOriginal).checked;
        var Is_Cheked_S = false;
        Is_Cheked_S = document.getElementById('checktipoexhs' + IdOriginal).checked;
        var Is_Cheked_C = false;
        Is_Cheked_C = document.getElementById('checktipoexhc' + IdOriginal).checked;
        var Is_Cheked_O = false;
        Is_Cheked_O = document.getElementById('checktipoexho' + IdOriginal).checked;
        var tipo_exhibidor = '';
        (Is_Cheked_G) ? tipo_exhibidor += '1,' : tipo_exhibidor += '0,';
        (Is_Cheked_S) ? tipo_exhibidor += '1,' : tipo_exhibidor += '0,';
        (Is_Cheked_C) ? tipo_exhibidor += '1,' : tipo_exhibidor += '0,';
        (Is_Cheked_O) ? tipo_exhibidor += '1' : tipo_exhibidor += '0';


        if (arrg_fotosExh[IdOriginal] == 0) {
            ContaCorrecto += 0;
            $("#filefotosu" + IdOriginal).removeClass("is-valid").addClass("is-invalid");
            $("#error-mjsf-" + IdOriginal).html('Por favor tome una foto del exhibidor');
        } else {
            ContaCorrecto += 1;
            $("#filefotosu" + IdOriginal).removeClass("is-invalid").addClass("is-valid");
            $("#error-mjsf-" + IdOriginal).html('');
        }
        if ($("#txtidcat" + IdOriginal).val() != '85000000001' && $("#txtidcat" + IdOriginal).val() != '99999999999' && $("#txtidcat" + IdOriginal).val() != '11111111111' && $("#txtidcat" + IdOriginal).val() != '22222222222' && $("#txtidcat" + IdOriginal).val() != '33333333333' && $("#txtidcat" + IdOriginal).val() != '44444444444' && $("#txtidcat" + IdOriginal).val() != '55555555555' && $("#txtidcat" + IdOriginal).val() != '88888888888' && $("#txtidcat" + IdOriginal).val() != '12121212121' && $("#txtidcat" + IdOriginal).val() != '13131313131') {
            ContaCorrecto += 1; // TIPO DE EXHIBIDOR
            ContaCorrecto += 7;
            // CAPACIDAD DE EXHIBIDOR
            // alert('exhibidor Normal N => '+$("#txtidcat" + IdOriginal).val());
        } else {
            var checks = document.getElementsByClassName("chk" + IdOriginal);
            var val_checks = false
            for (var i = 0; i < checks.length; i++) {
                if (checks[i].checked == true) {
                    val_checks = true;
                }
            }
            if (val_checks == true) {
                $(".chk" + IdOriginal).removeClass("is-invalid").addClass("is-valid");
                ContaCorrecto += 1;
            } else {
                $(".chk" + IdOriginal).removeClass("is-valid").addClass("is-invalid");
                ContaCorrecto += 0;
            }
            // alert('exhibidor Especial N => '+$("#txtidcat" + IdOriginal).val());
            ContaCorrecto += V_input_Entero(IdOriginal, $("#txtrt" + IdOriginal).val(), 'txtrt', 'error-mjsr-', 'RISTRA', $("#txtidcat_desc" + IdOriginal).val());
            ContaCorrecto += V_input_Entero(IdOriginal, $("#txtpq" + IdOriginal).val(), 'txtpq', 'error-mjsp-', 'PAQUETE', $("#txtidcat_desc" + IdOriginal).val());
            ContaCorrecto += V_input_Entero(IdOriginal, $("#txtpines" + IdOriginal).val(), 'txtpines', 'error-mjspn-', 'PINES', $("#txtidcat_desc" + IdOriginal).val());
            ContaCorrecto += V_input_Entero(IdOriginal, $("#txtun" + IdOriginal).val(), 'txtun', 'error-mjsun-', 'UNIDADES', $("#txtidcat_desc" + IdOriginal).val());
            ContaCorrecto += V_input_Entero(IdOriginal, $("#txtbolsas" + IdOriginal).val(), 'txtbolsas', 'error-mjsbl-', 'BOLSAS', $("#txtidcat_desc" + IdOriginal).val());
            ContaCorrecto += V_input_Entero(IdOriginal, $("#txtbotes" + IdOriginal).val(), 'txtbotes', 'error-mjsbt-', 'BOTES', $("#txtidcat_desc" + IdOriginal).val());
            ContaCorrecto += V_input_EnteroMYQc(IdOriginal, $("#txtcaras" + IdOriginal).val(), 'txtcaras', 'error-mjscara-', 'CARAS DE EXHIBICION', $("#txtidcat_desc" + IdOriginal).val());
        }
        if (!_empty($(elemento).val())) {
            $('#observacion_exh' + IdOriginal).removeClass("is-invalid").addClass("is-valid");
            ContaCorrecto += 1;
        } else {
            $('#observacion_exh' + IdOriginal).removeClass("is-valid").addClass("is-invalid");
            ContaCorrecto += 0;
        }
        var Producto_D = $("#txtidcat_desc" + IdOriginal).val();
        Producto_D = Producto_D.replace("EXHIBIDOR ", "");
        var Cod_EmpresaCompe = 1;
        if (Producto_D == 'DIANA') {
            Cod_EmpresaCompe = 2;
        } else if (Producto_D == 'YUMMIES') {
            Cod_EmpresaCompe = 3;
        } else if (Producto_D == 'FRITO LAY') {
            Cod_EmpresaCompe = 4;
        } else if (Producto_D == 'SENORIAL') {
            Cod_EmpresaCompe = 5;
        } else if (Producto_D == 'IDEAL') {
            Cod_EmpresaCompe = 6;
        } else if (Producto_D == 'BARCEL') {
            Cod_EmpresaCompe = 7;
        } else {
            Cod_EmpresaCompe = 1;
        } ValiConSinExh = 0;
        if ($("#txtidcat" + IdOriginal).val() == '7777777') {
            ValiConSinExh = 0;
        } else {
            ValiConSinExh = 1;
        }
       // console.log("Este es fotos: ", arrg_fotosExh);
        Agregar_Exh.push({
            "Ste_Cli_Id": arrg_datCli[0],
            "Ste_codigo_cli": arrg_datCli[2],
            "Ste_nombre_cli": arrg_datCli[1],
            "Ste_direccion_cli": arrg_datCli[3],
            "Ste_telefono_cli": arrg_datCli[4],
            "Ste_contacto_cli": arrg_datCli[5],
            "Ste_foto": arrg_fotosExh[IdOriginal],
            "Ste_pano": arrg_fotosExhD[IdOriginal],
            "Ste_despues": arrg_fotosExhT[IdOriginal],
            "Ste_latitud_obs": $("#textlatexh").val(),
            "Ste_longitud_obs": $("#textlotexh").val(),
            "Ste_Emprc_Id": Cod_EmpresaCompe,
            "Ste_Cat_Id": $("#txtidcat" + IdOriginal).val(),
            "Ste_Cat_Id_Descripcion": $("#txtidcat_desc" + IdOriginal).val(),
            "Ste_status": $("#txttipoac" + IdOriginal).val(),
            "Ste_cantidad": 1,
            "Ste_estado": $(elemento).val(),
            "Ste_comentario": $("#comentarioCliexh" + IdOriginal).val(),
            "Ste_cantidad_RT": (String($("#txtrt" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#txtrt" + IdOriginal).val()),
            "Ste_cantidad_PQ": (String($("#txtpq" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#txtpq" + IdOriginal).val()),
            "Ste_cantidad_PINES": (String($("#txtpines" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#txtpines" + IdOriginal).val()),
            "Ste_cantidad_UN": (String($("#txtun" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#txtun" + IdOriginal).val()),
            "Ste_cantidad_BOLSAS": (String($("#txtbolsas" + IdOriginal).val()) == '') ? 0 : parseInt($("#txtbolsas" + IdOriginal).val()),
            "Ste_cantidad_CARAS": (String($("#txtcaras" + IdOriginal).val()) == '') ? 0 : parseInt($("#txtcaras" + IdOriginal).val()),
            "Ste_cantidad_BOTES": (String($("#txtbotes" + IdOriginal).val()) == '') ? 0 : parseInt($("#txtbotes" + IdOriginal).val()),
            "Ste_tipo_exhibidor": tipo_exhibidor,
            "Ste_fecha_tel": $("#txtfechatel" + IdOriginal).val(),
            "Ste_fecha_serv": '1999-01-01 00:00:00',
            "Ste_ultima_fecha": $("#txtfechatel" + IdOriginal).val(),
            "Ste_nombre_ruta": arrg_Credls['usuario'],
            "Ste_Usu_Id": arrg_Credls['us_cod'],
            "Ste_token": $("#txttoken" + IdOriginal).val(),
            "Ste_token_espec": token_ExhEspec,
            "Ste_eliminado": 0,
            "Ste_cola": 'NO',
            "Ste_CambioAntes": 'SI',
            "Ste_CambioPano": 'SI',
            "Ste_CambioDespues": 'SI',
            "Ste_Accion": 'Agregar',
            "Ste_guardado": 'NO',
            "Ste_tipo_us": arrg_Credls['privilegio'],
            "Ste_Mot_Id": $("#Ste_Mot_Id" + IdOriginal).val()
        });
        if (ContaCorrecto < 10) {
            $("#error_incompleto" + IdOriginal).show();
        } else {
            $("#error_incompleto" + IdOriginal).hide();
        } CantOk += ContaCorrecto;
        V_Ok += 10;
        ContaCambios++;
    });
    // /*____0000000_____ ACTUALIZANDO EXHIBIDORES ______000000____*/
    $("select[name='d_observacion_exh[]']").each(function (indice, elemento) {
        // console.log('Array de fotos: ', arrg_fotosExh);
        // console.log('Array de fotos arrg_fotosExhd: ', arrg_fotosExhd);
        var IdOriginal = 0;
        ContaCorrecto = 0;
        IdOriginal = $(this).attr("id");
        IdOriginal = IdOriginal.substring(17, IdOriginal.length);
        if ($("#d_modificado" + IdOriginal).val() == 0) {} else {
            var Is_Cheked_G = false;
            Is_Cheked_G = document.getElementById('d_checktipoexhg' + IdOriginal).checked;
            var Is_Cheked_S = false;
            Is_Cheked_S = document.getElementById('d_checktipoexhs' + IdOriginal).checked;
            var Is_Cheked_C = false;
            Is_Cheked_C = document.getElementById('d_checktipoexhc' + IdOriginal).checked;
            var Is_Cheked_O = false;
            Is_Cheked_O = document.getElementById('d_checktipoexho' + IdOriginal).checked;
            var tipo_exhibidor = '';
            (Is_Cheked_G) ? tipo_exhibidor += '1,' : tipo_exhibidor += '0,';
            (Is_Cheked_S) ? tipo_exhibidor += '1,' : tipo_exhibidor += '0,';
            (Is_Cheked_C) ? tipo_exhibidor += '1,' : tipo_exhibidor += '0,';
            (Is_Cheked_O) ? tipo_exhibidor += '1' : tipo_exhibidor += '0';
            /*VERIFICAR DESPUES FOTO PARA LAS ACTUALIZACIONES NO CESARIO VALIDACION*/
            if ($("#d_txtidcat" + IdOriginal).val() != '85000000001' && $("#d_txtidcat" + IdOriginal).val() != '99999999999' && $("#d_txtidcat" + IdOriginal).val() != '11111111111' && $("#d_txtidcat" + IdOriginal).val() != '22222222222' && $("#d_txtidcat" + IdOriginal).val() != '33333333333' && $("#d_txtidcat" + IdOriginal).val() != '44444444444' && $("#d_txtidcat" + IdOriginal).val() != '55555555555' && $("#d_txtidcat" + IdOriginal).val() != '88888888888' && $("#d_txtidcat" + IdOriginal).val() != '12121212121' && $("#d_txtidcat" + IdOriginal).val() != '13131313131') {
                ContaCorrecto += 1; // TIPO DE EXHIBIDOR
                ContaCorrecto += 7;
                // CAPACIDAD DE EXHIBIDOR
            } else {
                var checks = document.getElementsByClassName("chka" + IdOriginal);
                var val_checks = false
                for (var i = 0; i < checks.length; i++) {
                    if (checks[i].checked == true) {
                        val_checks = true;
                    }
                }
                if (val_checks == true) {
                    $(".chka" + IdOriginal).removeClass("is-invalid").addClass("is-valid");
                    ContaCorrecto += 1;
                } else {
                    $(".chka" + IdOriginal).removeClass("is-valid").addClass("is-invalid");
                    ContaCorrecto += 0;
                }
                ContaCorrecto += V_input_Entero(IdOriginal, $("#d_txtrt" + IdOriginal).val(), 'd_txtrt', 'd_error-mjsr-', 'RISTRA', $("#d_txtidcat_desc" + IdOriginal).val());
                ContaCorrecto += V_input_Entero(IdOriginal, $("#d_txtpq" + IdOriginal).val(), 'd_txtpq', 'd_error-mjsp-', 'PAQUETE', $("#d_txtidcat_desc" + IdOriginal).val());
                ContaCorrecto += V_input_Entero(IdOriginal, $("#d_txtpines" + IdOriginal).val(), 'd_txtpines', 'd_error-mjspn-', 'PINES', $("#d_txtidcat_desc" + IdOriginal).val());
                ContaCorrecto += V_input_Entero(IdOriginal, $("#d_txtun" + IdOriginal).val(), 'd_txtun', 'd_error-mjsun-', 'UNIDADES', $("#d_txtidcat_desc" + IdOriginal).val());
                ContaCorrecto += V_input_Entero(IdOriginal, $("#d_txtbolsas" + IdOriginal).val(), 'd_txtbolsas', 'd_error-mjsbl-', 'BOLSAS', $("#d_txtidcat_desc" + IdOriginal).val());
                ContaCorrecto += V_input_Entero(IdOriginal, $("#d_txtbotes" + IdOriginal).val(), 'd_txtbotes', 'd_error-mjsbt-', 'BOTES', $("#d_txtidcat_desc" + IdOriginal).val());
                ContaCorrecto += V_input_EnteroMYQc(IdOriginal, $("#d_txtcaras" + IdOriginal).val(), 'd_txtcaras', 'd_error-mjscara-', 'CARAS DE EXHIBICION', $("#d_txtidcat_desc" + IdOriginal).val());
            }
            if (!_empty($(elemento).val())) {
                $('#d_observacion_exh' + IdOriginal).removeClass("is-invalid").addClass("is-valid");
                ContaCorrecto += 1;
            } else {
                $('#d_observacion_exh' + IdOriginal).removeClass("is-valid").addClass("is-invalid");
                ContaCorrecto += 0;
            }
            var Producto_D = $("#d_txtidcat_desc" + IdOriginal).val();
            Producto_D = Producto_D.replace("EXHIBIDOR ", "");
            var Cod_EmpresaCompe = 1;
            if (Producto_D == 'DIANA') {
                Cod_EmpresaCompe = 2;
            } else if (Producto_D == 'YUMMIES') {
                Cod_EmpresaCompe = 3;
            } else if (Producto_D == 'FRITO LAY') {
                Cod_EmpresaCompe = 4;
            } else if (Producto_D == 'SENORIAL') {
                Cod_EmpresaCompe = 5;
            } else if (Producto_D == 'IDEAL') {
                Cod_EmpresaCompe = 6;
            } else if (Producto_D == 'BARCEL') {
                Cod_EmpresaCompe = 7;
            } else {
                Cod_EmpresaCompe = 1;
            } ValiConSinExh = 0;
            if ($("#d_txtidcat" + IdOriginal).val() == '7777777') {
                ValiConSinExh = 0;
            } else {
                ValiConSinExh = 1;
            } 
            Actualizar_Exh.push({
                "Ste_Cli_Id": arrg_datCli[0],
                "Ste_codigo_cli": arrg_datCli[2],
                "Ste_nombre_cli": arrg_datCli[1],
                "Ste_direccion_cli": arrg_datCli[3],
                "Ste_telefono_cli": arrg_datCli[4],
                "Ste_contacto_cli": arrg_datCli[5],
                "Ste_foto": arrg_fotosExhdu[IdOriginal],
                "Ste_pano": arrg_fotosExhdd[IdOriginal],
                "Ste_despues": arrg_fotosExhdt[IdOriginal],
                "Ste_latitud_obs": $("#textlatexh").val(),
                "Ste_longitud_obs": $("#textlotexh").val(),
                "Ste_Emprc_Id": Cod_EmpresaCompe,
                "Ste_Cat_Id": $("#d_txtidcat" + IdOriginal).val(),
                "Ste_Cat_Id_Descripcion": $("#d_txtidcat_desc" + IdOriginal).val(),
                "Ste_status": $("#d_txttipoac" + IdOriginal).val(),
                "Ste_cantidad": 1,
                "Ste_estado": $(elemento).val(),
                "Ste_comentario": $("#d_comentarioCliexh" + IdOriginal).val(),
                "Ste_cantidad_RT": (String($("#d_txtrt" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#d_txtrt" + IdOriginal).val()),
                "Ste_cantidad_PQ": (String($("#d_txtpq" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#d_txtpq" + IdOriginal).val()),
                "Ste_cantidad_PINES": (String($("#d_txtpines" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#d_txtpines" + IdOriginal).val()),
                "Ste_cantidad_UN": (String($("#d_txtun" + IdOriginal).val()).trim() == '') ? 0 : parseInt($("#d_txtun" + IdOriginal).val()),
                "Ste_cantidad_BOLSAS": (String($("#d_txtbolsas" + IdOriginal).val()) == '') ? 0 : parseInt($("#d_txtbolsas" + IdOriginal).val()),
                "Ste_cantidad_CARAS": (String($("#d_txtcaras" + IdOriginal).val()) == '') ? 0 : parseInt($("#d_txtcaras" + IdOriginal).val()),
                "Ste_cantidad_BOTES": (String($("#d_txtbotes" + IdOriginal).val()) == '') ? 0 : parseInt($("#d_txtbotes" + IdOriginal).val()),
                "Ste_tipo_exhibidor": tipo_exhibidor,
                "Ste_fecha_tel": $("#d_txtfechatel" + IdOriginal).val(),
                "Ste_fecha_serv": '1999-01-01 00:00:00',
                "Ste_ultima_fecha": $("#d_txtfechatelultima" + IdOriginal).val(),
                "Ste_nombre_ruta": arrg_Credls['usuario'],
                "Ste_Usu_Id": arrg_Credls['us_cod'],
                "Ste_token": $("#d_txttoken" + IdOriginal).val(),
                "Ste_token_espec": token_ExhEspec,
                "Ste_eliminado": $("#d_txteliminado" + IdOriginal).val(),
                "Ste_cola": 'NO',
                "Ste_CambioAntes": arrg_cambioFoto[IdOriginal],
                "Ste_CambioPano": arrg_cambioFotoD[IdOriginal],
                "Ste_CambioDespues": arrg_cambioFotoT[IdOriginal],
                "Ste_Accion": 'Actualizar',
                "Ste_guardado": 'SI',
                "Ste_Mot_Id": $("#d_Ste_Mot_Id" + IdOriginal).val()
            });

            if (ContaCorrecto < 9) {
                $("#d_error_incompleto" + IdOriginal).show();
            } else {
                $("#d_error_incompleto" + IdOriginal).hide();
            } CantOk += ContaCorrecto;
            V_Ok += 9;
            ContaCambios++;
        }
    });

    C_V_mjs = `<ul>${C_V_mjs}</ul></div>`;
    // console.log(C_V_mjs);
    if (CantOk == V_Ok) { // console.log('CUANTOS CAMBIOS HUBIERON => '+ContaCambios);
        if (ContaCambios > 0) { // console.log('TODO CORRECTO');
            Arrg_Mantenimiento = Agregar_Exh.concat(Actualizar_Exh);

            //console.log(Arrg_Mantenimiento);
            $.when($(".carga-class").stop(true, true).show()).done(function (x) {
                $.ajax({
                    url: 'exhibidores/guardar_cambios',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        status_exhibidores: Arrg_Mantenimiento,
                        IdCliente: IdCliente
                    },
                    timeout: 17777
                }).done(function (_resp) {
                    if (_resp.rs == true) {} else {
                        arrg_dataSincro = [];
                    }
                }).always(function (_resp, textStatus, errorThrown) {
                    ContaCambios = 0;
                    arrg_fotosExh = [];
                    DB_BloqAddExhQtiene(arrg_datCli[7]);
                    DB_EstadoExhibidor_Change(dataCli[0]);
                    $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                        $.when($("#div_detExh").stop(true, true).hide()).done(function (x) {
                            $.when($("#div_prinBody").stop(true, true).show()).done(function (x) {
                                if (textStatus == "success") {
                                    if (_resp.rs == true) {
                                       // console.log('Esto va para el llocal: ', Actualizar_Exh);
                                        Guardar_Cambios_StatusExh(Agregar_Exh, Actualizar_Exh, 'NO', 'SI');
                                        Swal.fire({type: 'success', title: 'Cambios guardados con éxito!', showConfirmButton: false, timer: 1500});
                                    } else { // console.log('ERROR CAMPOS');
                                        arrg_dataSincro = [];
                                        var mensajeError = '';
                                        mensajeError =  JSON.parse(_resp.errores);
                                        Swal.fire({title: 'Aviso!', type: 'error', html:_resp.titulo+'<pre>'+ JSON.stringify(mensajeError,null, 2)+'</pre>', confirmButtonText: 'Ok'});
                                    }
                                } else { // console.log('ERROR ERROR ERROR');
                                    _ajax_error_statusexh(_resp.status, _resp.readyState, _resp.statusText);
                                }
                                $("#list_qtiene").empty();
                                $("#list_devul").empty();
                                $("#sub-nav-qtiene").empty();
                                $("#sub-nav-nuevos").empty();
                                $("#det_devul").empty();
                                $("#txtclientesexh").val("");
                                $("#btn_GuaExh").hide();
                                $("#btn_conExh").show();
                            });
                        });
                    });
                    $("#m_control_exhibidores").modal("toggle");
                    $.when($("#InfoCliente").stop(true, true).hide()).done(function (x) {
                        $.when($("#InfoCuadro").stop(true, true).show()).done(function (x) {});
                    });
                });
            });
        } else {
            Swal.fire({type: 'info', title: 'No se hicieron cambios!', showConfirmButton: false, timer: 1500});
        }
    } else {
        Swal.fire({title: '<strong>Atención!</strong>', type: 'info', html: '<p style="text-align:center;">Por favor verificar los campos de los exhibidores que tiene la imagen siguiente.</p><p style="text-align:center;"><span class="fas fa-exclamation-triangle fa-3x" style="color:#EEEB38;"></span></p>', confirmButtonText: 'Ok'}).then((result) => {});
    }
}
/*<<<< ---- STATUS EXHIBIDORES DB INDEXEDDB ---- >>>>>*/
/*
    NO => NO REGISTRO PENDIENTE
    SI => REGISTROS PENDIENTES
*/
function Guardar_Cambios_StatusExh(arrgDataGD, arrgDataAC, pendienteX, estadoSaved) {


    var Ct = 0,
        conta = 0;
    Ct = Object.entries(arrgDataGD).length;
    if (Ct > 0) {
        var active = dataBaseAppSDV.result;
        var transaction = active.transaction(['tbl_status_exhibidores'], "readwrite");
        var objectStoreG = transaction.objectStore('tbl_status_exhibidores');
        arrgDataGD.map(function (data) {
            data.Ste_cola = pendienteX;
            data.Ste_guardado = estadoSaved;
             console.log('EL CAMBIO DE FOTO => ' + data.Ste_CambioFoto);
            var request = objectStoreG.put(data);
            request.onerror = function (e) { // console.log('Llave repetida.');
            };
            conta++;
        });
    }

    if (Ct == conta) {
        Actualizar_StatusExh(arrgDataAC, pendienteX, 'SI');
    } else {
        Swal.fire({type: 'error', title: 'Error en guardar los cambios...', showConfirmButton: false, timer: 1500});
    }
}
function Actualizar_StatusExh(arrgData, pendienteX) {

    var Ct = 0,
        conta = 0;
    Ct = Object.entries(arrgData).length;

    if (Ct > 0) {
        arrgData.forEach(function (dato, index, arrayinsertar) {
            var actived = dataBaseAppSDV.result;
            var objectStore = actived.transaction(["tbl_status_exhibidores"], "readwrite").objectStore("tbl_status_exhibidores");
            var request = objectStore.get(dato.Ste_token);
            request.onerror = function (event) {};
            request.onsuccess = function (event) {
                var data = request.result;

                // Validar y asignar propiedades solo si existen en dato
                // if (dato.Ste_foto !== undefined) {
                //     data.Ste_foto = dato.Ste_foto;
                // }

                // if (dato.Ste_antes !== undefined) {
                //     data.Ste_antes = dato.Ste_antes;
                // }

                // if (dato.Ste_pano !== undefined) {
                //     data.Ste_pano = dato.Ste_pano;
                // }
                data.Ste_CambioAntes = dato.Ste_CambioAntes;
                data.Ste_CambioPano = dato.Ste_CambioPano;
                data.Ste_CambioDespues = dato.Ste_CambioDespues;
                data.Ste_foto = dato.Ste_foto;
                data.Ste_despues = dato.Ste_despues;
                data.Ste_pano = dato.Ste_pano;
                data.Ste_Cat_Id = dato.Ste_Cat_Id;
                data.Ste_Cat_Id_Descripcion = dato.Ste_Cat_Id_Descripcion;
                data.Ste_status = dato.Ste_status;
                data.Ste_estado = dato.Ste_estado;
                data.Ste_comentario = dato.Ste_comentario;
                data.Ste_cantidad_RT = dato.Ste_cantidad_RT;
                data.Ste_cantidad_PQ = dato.Ste_cantidad_PQ;
                data.Ste_cantidad_PINES = dato.Ste_cantidad_PINES;
                data.Ste_cantidad_UN = dato.Ste_cantidad_UN;
                data.Ste_cantidad_BOLSAS = dato.Ste_cantidad_BOLSAS;
                data.Ste_cantidad_CARAS = dato.Ste_cantidad_CARAS;
                data.Ste_cantidad_BOTES = dato.Ste_cantidad_BOTES;
                data.Ste_tipo_exhibidor = dato.Ste_tipo_exhibidor;
                data.Ste_cola = pendienteX;

                if (!_empty(data.Ste_Accion)) {/* Agregar o Actualizar */
                    if (pendienteX == 'SI' && data.Ste_Accion == 'Agregar' && data.Ste_guardado == 'NO') {
                        data.Ste_Accion = 'Agregar';
                        data.Ste_CambioAntes = "SI";
                    } else {
                        data.Ste_Accion = 'Actualizar';
                        // data.Ste_CambioFoto = dato.Ste_CambioFoto;
                    }
                } else {
                    data.Ste_Accion = 'Actualizar';
                    // data.Ste_CambioFoto = dato.Ste_CambioFoto;
                }
                data.Ste_ultima_fecha = dato.Ste_ultima_fecha;
                data.Ste_eliminado = dato.Ste_eliminado;
                data.Ste_Mot_Id = dato.Ste_Mot_Id;
               // console.log("Data: ", data);
                var requestUpdate = objectStore.put(data);
                requestUpdate.onerror = function (event) {};
                requestUpdate.onsuccess = function (event) {};
            };
            conta++;
        });



    }


    // console.log('CANTIDAD TRABAJADOS AC => ' + conta);
    if (Ct == conta) {
        if (pendienteX == 'SI') {
            Swal.fire({type: 'success', title: 'Cambios guardados con éxito en cola!', showConfirmButton: false, timer: 1500});
            Consultar_Colas();
        } else {
            Swal.fire({type: 'success', title: 'Cambios guardados con éxito!', showConfirmButton: false, timer: 1500});
        }
    } else {
        Swal.fire({type: 'error', title: 'Error en la actualizacion de exhibidores...', showConfirmButton: false, timer: 1500});
    }
}
/*_____0000000______ PUEDE SERVIR DESPUES - ACTU FULL REGISTROS POR TOKEN _____0000000______*/
function ActuFUllxToken() {
    var actived = dataBaseAppSDV.result;
    const transaction = actived.transaction(['tbl_status_exhibidores'], 'readwrite');
    const objectStore = transaction.objectStore('tbl_status_exhibidores');
    objectStore.openCursor().onsuccess = function (event) {
        const cursor = event.target.result;
        if (cursor) {
            if (cursor.value.Ste_token_espec === token) {
                const updateData = cursor.value;
                updateData.Ste_token_espec = pendienteX;
                const request = cursor.update(updateData);
                request.onsuccess = function () {};
            };
            cursor.continue();
        } else {}
    };
}
function Consultar_Colas() {
    CantCola = 0;
    Promise.all([ConsultarColaStatusExh()]).then(respuestas => {
        $("#RegisCola").text(CantCola);
        // console.log('Canntidad En Cola => ' + CantCola);
    }).catch(error => {
        console.log('ERROR EN CONSUSLTAR COLAS');
    });
}
function ConsultarColaMarcaciones() {
    arrgColaM = [];
    return new Promise(function (resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_marcaciones', 'readonly'),
            store = transaccion.objectStore('tbl_marcaciones'),
            indice = store.index('by_Proceso'),
            cursor = indice.openCursor('INCOMPLETO')
        cursor.onsuccess = function (event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                arrgColaM = dataResult;
                CantCola += parseInt(Object.keys(dataResult).length);
                resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
function ConsultarColaStatusExh() {
    arrgColaE = [];
    return new Promise(function (resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_status_exhibidores', 'readonly'),
            store = transaccion.objectStore('tbl_status_exhibidores'),
            indice = store.index('by_Ste_cola'),
            cursor = indice.openCursor('SI')
        cursor.onsuccess = function (event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                arrgColaE = dataResult;
                CantCola += parseInt(Object.keys(dataResult).length);
                resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
function EnvioCola() {
    var cantidiadCola = $("#RegisCola").text();
    if (cantidiadCola > 0) {
        Swal.fire({
            title: 'Deseas enviar los registros en cola?',
            text: "",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, enviar!',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false
        }).then((result) => {
            $.when($(".carga-class").stop(true, true).show()).done(function (x) {
                if (result.value) {
                    ColaExhibidores = arrgColaE;
                    var C_Exhibidores = parseInt(ColaExhibidores.length);
                    envio_RecuRegistrosColaExh(0, ColaExhibidores);
                } else {
                    $.when($(".carga-class").stop(true, true).hide()).done(function (x) {});
                }
            });

        });
    } else {
        Swal.fire({type: 'info', title: 'No tienes registros en cola!', showConfirmButton: false, timer: 1500});
    }
}
function envio_RecuRegistrosColaExh(indice, elementos) { // console.log('----------------'); console.log('indice => ' + indice);
    var arr_objeto = [];
    if (indice < elementos.length) {
        arr_objeto.push(elementos[indice]);
        $.ajax({
            url: 'exhibidores/guardar_cambios',
            type: 'POST',
            dataType: 'JSON',
            data: {
                status_exhibidores: arr_objeto,
                IdCliente: IdCliente
            },
            timeout: 17777
        }).done(function (_resp) {}).always(function (_resp, textStatus, errorThrown) {
            if (textStatus == "success") {
                if (_resp.rs == true) {
                    var actived = dataBaseAppSDV.result;
                    var objectStore = actived.transaction(["tbl_status_exhibidores"], "readwrite").objectStore("tbl_status_exhibidores");
                    var request = objectStore.get(elementos[indice].Ste_token);
                    request.onerror = function (event) {};
                    request.onsuccess = function (event) {
                        var data = request.result;
                        data.Ste_cola = 'NO';
                        data.Ste_guardado = 'SI';
                        var requestUpdate = objectStore.put(data);
                        requestUpdate.onerror = function (event) {};
                        requestUpdate.onsuccess = function (event) {
                            alertify.success('Registro enviado exitosamente!');
                            envio_RecuRegistrosColaExh(indice + 1, ColaExhibidores);
                            Consultar_Colas();
                        };
                    };
                } else {
                    $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                        var mensajeError = '';
                        mensajeError =  JSON.parse(_resp.errores);
                        Swal.fire({title: 'Aviso!', type: 'error', html:_resp.titulo+'<pre>'+ JSON.stringify(mensajeError,null, 2)+'</pre>', confirmButtonText: 'Ok'});
                    });
                }
            } else {
                $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                    var errorhtml = ``;
                    errorhtml = `No se pueden enviar los registros en cola, por favor verificar su conexión a internet`;
                    Swal.fire({title: 'Aviso!', type: 'error', html: errorhtml, confirmButtonText: 'Ok'});
                });
            }
        });
    } else {
        $.when($(".carga-class").stop(true, true).hide()).done(function (x) { // console.log('exhibidores enviados correctamente');
        });
    }
}
/*<<<< ---- CONSULTAR CLIENTE EXHIBIDORES ---- >>>>>*/
function geoUbicacinCliente(e) {

    $("#textlatexh").val(e.latlng.lat);
    $("#textlotexh").val(e.latlng.lng);
    var radius = e.accuracy / 2;
    var location = e.latlng;
    var greenIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [
            25, 41
        ],
        iconAnchor: [
            12, 41
        ],
        popupAnchor: [
            1, -34
        ],
        shadowSize: [41, 41]
    });
    if (marker != undefined) {
        map.removeLayer(marker);
    }
    marker = new L.Marker(e.latlng, {draggable: false});
    map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng), 18);
    map.addLayer(marker);

    if ($("#txtclientesexh").val() === "") {
        $("#errores_exhibidores").html('<div class="alert alert-danger"><ul><li>POR FAVOR SELECCIONA UN CLIENTE</li></ul></div>');
    } else {
        dataCli = [];
        dataCli = [
            $("#txtclientesexh").val(),
            // $("#txtclientesexh option:selected").text(),
        ];
        $.when($(".carga-class").stop(true, true).show()).done(function (x) {
            Consultar_Exhibidores(dataCli);
        });
    }
}
function geoUbicacinClienteSinExh(e) {

    $("#textlatexh").val(e.latlng.lat);
    $("#textlotexh").val(e.latlng.lng);
    var radius = e.accuracy / 2;
    var location = e.latlng;
    var greenIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [
            25, 41
        ],
        iconAnchor: [
            12, 41
        ],
        popupAnchor: [
            1, -34
        ],
        shadowSize: [41, 41]
    });
    if (marker != undefined) {
        map.removeLayer(marker);
    }
    marker = new L.Marker(e.latlng, {draggable: false});
    map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng), 18);
    map.addLayer(marker);
    dataCli = [];
    dataCli = [
        $("#txtclientesexh").val(),
        // $("#txtclientesexh option:selected").text(),
    ];
    IdCliente = $("#txtclientesexh").val();
    if ($("#txtclientesexh").val() === "") {
        $("#errores_exhibidores").html('<div class="alert alert-danger"><ul><li>POR FAVOR SELECCIONA UN CLIENTE</li></ul></div>');
    } else {
        ConsultarCa_ExhXcli();
    }
}

function onLocationError_Exh(e) {
    $("#textlatexh").val(0);
    $("#textlotexh").val(0);

    Swal.fire({
        type: 'info',
        title: 'GPS apagado o geolocalización bloqueada',
        html: '<p>Por favor ver el tutorial para desbloquear la geolocalización</p>',
        showConfirmButton: true,
        confirmButtonText: 'Ok'
    }).then((result) => {
        if (result.value) {
            $.when($("#ModalTutorial").modal("show")).done(function (x) {
                $("#imgtutorial").attr("src", "../Public/Img/Permitir_GPS.gif");
            });
        } else {
            location.reload();
        }
    });
}
function _ajax_error_statusexh(jqXHR, textStatus, errorThrown) {
    if (textStatus === 'timeout') {
        Guardar_Cambios_StatusExh(Agregar_Exh, Actualizar_Exh, 'SI', 'NO');
        return;
    } else if (jqXHR === 0) {
        Guardar_Cambios_StatusExh(Agregar_Exh, Actualizar_Exh, 'SI', 'NO');
        return;
    } else if (jqXHR === 200) {
        Guardar_Cambios_StatusExh(Agregar_Exh, Actualizar_Exh, 'SI', 'NO');
        return;
    } else if (jqXHR == 404) {
        Swal.fire({title: 'Aviso!', type: 'error', html: '<h3>Página solicitada no encontrada[404]</h3>', confirmButtonText: 'Ok'});
        return;
    } else if (jqXHR == 500) {
        Swal.fire({title: 'Aviso!', type: 'error', html: '<h3>Error de servidor interno [500].</h3>', confirmButtonText: 'Ok'});
        return;
    } else if (textStatus === 'parsererror') {
        Swal.fire({title: 'Aviso!', type: 'error', html: '<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>', confirmButtonText: 'Ok'});
        return;
    } else if (textStatus === 'abort') {
        Swal.fire({title: 'Aviso!', type: 'error', html: '<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>', confirmButtonText: 'Ok'});
        return;
    } else {
        Swal.fire({title: 'Aviso!', type: 'error', html: '<h3>Error desconocido, por favor contactar con Sistemas de Venta...</h3>', confirmButtonText: 'Ok'});
        return;
    }
}
/*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
/*<<<<<<<<<<<<<<<<<<<<< OBTENIENDO COORDENADAS >>>>>>>>>>>>>>>>>>>>>>>*/
/*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
function init() {
    map = new L.Map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(map);
    map.attributionControl.setPrefix('SDV Bocadeli');
    map.setView(new L.LatLng(13.685147, -89.147116), 18);
    var circle = L.circle([
        13.685147, -89.147116
    ], {
        color: '#3ACA31',
        fillColor: '#51DF48',
        fillOpacity: 0.5,
        radius: 50
    });

    V_CoordenadasLL_ContarOK($("#lat").val(), 'lat', 2, 'Latitud');
    V_CoordenadasLL_ContarOK($("#lon").val(), 'lon', 3, 'Longitud');
}
/*CAMBIOS 18/08/2021*/
function ConsultarCa_ExhXcli() {
    var FechaTelefono = fechaDispositivo();
    var token_Exh = TokenAC_Exh();
    return new Promise(function (resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_status_exhibidores', 'readonly'),
            store = transaccion.objectStore('tbl_status_exhibidores'),
            indice = store.index('by_Ste_Cli_Id'),
            cursor = indice.openCursor($("#txtclientesexh").val())
        cursor.onsuccess = function (event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {

                let FiltroCantiExh = dataResult.filter(tipo_actu => tipo_actu.Ste_status == "1" || tipo_actu.Ste_status == "3");
                FiltroCantiExh = FiltroCantiExh.filter(tipo_actu => tipo_actu.Ste_eliminado == "0");
                var CantidadExh = parseInt(Object.keys(FiltroCantiExh).length);
                ValiConSinExh = 0;
                if (CantidadExh == 0) {
                    Agregar_Exh = [];
                    Actualizar_Exh = [];
                    Agregar_Exh.push({
                        "Ste_Cli_Id": $("#txtclientesexh").val(),
                        "Ste_codigo_cli": $("#codigoCli").val(),
                        "Ste_nombre_cli": $("#nombreCli").val(),
                        "Ste_direccion_cli": $("#direccionCli").val(),
                        "Ste_telefono_cli": $("#telefonoCli").val(),
                        "Ste_contacto_cli": $("#contactoCli").val(),
                        "Ste_foto": 'NA',
                        "Ste_latitud_obs": $("#textlatexh").val(),
                        "Ste_longitud_obs": $("#textlotexh").val(),
                        "Ste_Emprc_Id": 1,
                        "Ste_Cat_Id": 7777777,
                        "Ste_Cat_Id_Descripcion": 'SIN EXHIBIDOR',
                        "Ste_status": 0,
                        "Ste_cantidad": 1,
                        "Ste_estado": 0,
                        "Ste_comentario": '-------',
                        "Ste_cantidad_RT": 0,
                        "Ste_cantidad_PQ": 0,
                        "Ste_cantidad_PINES": 0,
                        "Ste_cantidad_UN": 0,
                        "Ste_cantidad_BOLSAS": 0,
                        "Ste_cantidad_CARAS": 0,
                        "Ste_cantidad_BOTES": 0,
                        "Ste_tipo_exhibidor": '0,0,0,0',
                        "Ste_fecha_tel": FechaTelefono,
                        "Ste_fecha_serv": '1999-01-01 00:00:00',
                        "Ste_ultima_fecha": FechaTelefono,
                        "Ste_nombre_ruta": arrg_Credls['usuario'],
                        "Ste_Usu_Id": arrg_Credls['us_cod'],
                        "Ste_token": token_Exh,
                        "Ste_token_espec": token_ExhEspec,
                        "Ste_eliminado": 0,
                        "Ste_cola": 'NO',
                        "Ste_CambioAntes": 'NO',
                        "Ste_CambioPano": 'NO',
                        "Ste_CambioDespues": 'NO',
                        "Ste_Accion": 'Agregar',
                        "Ste_guardado": 'NO',
                        "Ste_tipo_us": arrg_Credls['privilegio']
                    });
                    $.when($(".carga-class").stop(true, true).show()).done(function (x) {
                        $.ajax({
                            url: 'exhibidores/guardar_cambios',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                status_exhibidores: Agregar_Exh,
                                IdCliente: IdCliente
                            },
                            timeout: 17777
                        }).done(function (_resp) {
                            if (_resp.rs == true) {} else {
                                arrg_dataSincro = [];
                            }
                        }).always(function (_resp, textStatus, errorThrown) {
                            DB_BloqAddExhQtiene(arrg_datCli[7]);
                            DB_EstadoExhibidor_Change(dataCli[0]);
                            $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                                $.when($("#div_detExh").stop(true, true).hide()).done(function (x) {
                                    $.when($("#div_prinBody").stop(true, true).show()).done(function (x) {
                                        if (textStatus == "success") {
                                            if (_resp.rs == true) {
                                                Guardar_Cambios_StatusExh(Agregar_Exh, Actualizar_Exh, 'NO', 'SI');
                                                Swal.fire({type: 'success', title: 'Cambios guardados con éxito!', showConfirmButton: false, timer: 1500});
                                            } else { // console.log('ERROR CAMPOS');
                                                arrg_dataSincro = [];
                                                var mensajeError = '';
                                                mensajeError =  JSON.parse(_resp.errores);
                                                Swal.fire({title: 'Aviso!', type: 'error', html:_resp.titulo+'<pre>'+ JSON.stringify(mensajeError,null, 2)+'</pre>', confirmButtonText: 'Ok'});
                                            }
                                        } else { // console.log('ERROR ERROR ERROR');
                                            _ajax_error_statusexh(_resp.status, _resp.readyState, _resp.statusText);
                                        }
                                    });
                                });
                            });
                            $.when($("#InfoCliente").stop(true, true).hide()).done(function (x) {
                                $.when($("#InfoCuadro").stop(true, true).show()).done(function (x) {});
                            });
                        });
                    });
                } else {
                    Swal.fire({title: 'Aviso!', type: 'error', html: '<h3>El cliente tiene exhibidores.<br> Para cambiar el estado de cliente a sin exhibidores, tiene que eliminar los exhibidores que tiene ó cambiar el estado a devolución. </h3>', confirmButtonText: 'Ok'});
                } resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
/*CAMBIOS 04/11/2021*/
function DB_FiltroMotivosEliminar() {
    return new Promise(function (resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_ste_motivo', 'readonly'),
            store = transaccion.objectStore('tbl_ste_motivo'),
            indice = store.index('by_Tmot_Id'),
            cursor = indice.openCursor('2')
        cursor.onsuccess = function (event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                var filtro_html = ``;
                filtro_html += `<select class="custom-select" id="filtromotivosE" name="filtromotivosE">
                <option value="">Seleccionar una opción</option>`;
                dataResult.forEach(function (filall, index, arrgfilall) {
                    filtro_html += `<option value="${
                        filall.Mot_Id
                    }">${
                        filall.Mot_descripcion
                    }</option>`;
                });
                filtro_html += `</select>`;
                $("#S_filtroMotivoE").empty().html(filtro_html);
                resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
function DB_FiltroMotivosDevolucion() {
    return new Promise(function (resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_ste_motivo', 'readonly'),
            store = transaccion.objectStore('tbl_ste_motivo'),
            indice = store.index('by_Tmot_Id'),
            cursor = indice.openCursor('3')
        cursor.onsuccess = function (event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                var filtro_html = ``;
                filtro_html += `<select class="custom-select" id="filtromotivosD" name="filtromotivosD">
                <option value="">Seleccionar una opción</option>`;
                dataResult.forEach(function (filall, index, arrgfilall) {
                    filtro_html += `<option value="${
                        filall.Mot_Id
                    }">${
                        filall.Mot_descripcion
                    }</option>`;
                });
                filtro_html += `</select>`;
                $("#S_filtroMotivoD").empty().html(filtro_html);
                resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
