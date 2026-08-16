var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
var warn_on_unload = '';
var map;
var marker;
var blockFCli=0;
var blockF=0;
var coordenadas_Tempo = new Array();
var arrg_vali_result = new Array();
var arreg_offline = [];
var Id_Cliente = '';
var arrg_fotos = [];
arrg_fotos[1] = null;
arrg_fotos[2] = null;
arrg_fotos[3] = null;
arrg_fotos[4] = null;
arrg_fotos[5] = null;
window.onbeforeunload = function() {
    if(warn_on_unload != ''){
        return warn_on_unload;
    }
}
$( document ).ready(function() {
    $("#txtnombre").prop('readonly', true);
    Promise.all([
        DB_IniciarCPSesion(1)
    ])
    .then(respuestas => {
        ConsultarCola();
    })
    .catch(error => {
        console.log('ERROR EN CONSUSLTAR COLAS');
    });
    $(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";
        $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            });
        });
    });
    $("input[name='rdpersoneria']").change(function() {
        var value = $("input[name='rdpersoneria']:checked").val();
        if( value == 2 ){//Persona Juridica
            $("#if-representante").show();
            $("#div_dui").hide();
            $("#foto_dui").hide();
            $("#rdcontribuyente1").click();
            $("input[name='rdcontribuyente']").prop("disabled", true);
            $("#txtnombreR").addClass("is-valid");
            $("#txtnumtelefonoR").addClass("is-valid");
            $("#txtduiR").addClass("is-valid");
            arrg_fotos[2] = null;
            arrg_fotos[3] = null;
            $("#imagen2").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            $("#imagen3").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        }else{//Persona Natural
            $("#foto_nrc").hide();
            $("#div_numregistro").hide();
            $("#div_dui").show();
            $("#foto_dui").show();
            $("#if-representante").hide();
            $("input[name='rdcontribuyente']").prop("disabled", false);
            // $("#rdcontribuyente1").click();
            $("input[name='rdcontribuyente']").prop("checked", false);
            arrg_fotos[1] = null;
            arrg_fotos[5] = null;
            $("#imagen1").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            $("#imagen5").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        }
        V_checksRadio(5,'Personería','GR_CheckRadio');
        $("#if_cinva").show();
    });
    $("input[name='rdcontribuyente']").change(function() {
        var value = $("input[name='rdcontribuyente']:checked").val();
        if( value == 1 ){
            $("#foto_nrc").show();
            $("#div_numregistro").show();
        }else{
            $("#foto_nrc").hide();
            $("#div_numregistro").hide();
            
        }
        V_checksRadio(6,'Contribuyente de IVA','GR_CheckRadioC');
    });
    $("input[name='rdpreguntacontacto']").change(function() {
        var value = $("input[name='rdpreguntacontacto']:checked").val();
        $("#info_contacto").show();
        if( value == 1 ){
            $("#txtnombreC").val($("#txtcontacto").val());
            $("#txtnumtelefonoC").unmask().val($("#txtnumtelefono").val()).mask(arrg_Credls['FormatoTelefono']);
            $("#txtduiC").unmask().val($("#txtdui").val()).mask(arrg_Credls['FormatoNumIP'],{placeholder: arrg_Credls['FormatoNumIP']});
            /* ---  Validacion de nombre contacto ---*/
            V_Text_LetraNumero($("#txtnombreC").val(),'txtnombreC',12,'Nombre del contacto');
            /* ---  Validacion de nombre telefono ---*/
            var CantidTelefonorC = 0;var CantidTelefonotC = 0;
            CantidTelefonorC = arrg_Credls['CantidTelefono'] ;CantidTelefonotC = arrg_Credls['CantidTelefono'] + 1;
            V_numeconMaskguion($("#txtnumtelefonoC").val(),'txtnumtelefonoC',13,'N&uacute;mero de tel&eacute;fono',CantidTelefonorC,CantidTelefonotC);
            /* ---  Validacion de nombre dui ---*/
            var CantidNumIPrC = 0;var CantidNumIPtC = 0;
            CantidNumIPrC = arrg_Credls['CantidNumIP'];CantidNumIPtC = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
            var NombreDocumentoDUIC = "";NombreDocumentoDUIC = arrg_Credls['NombreDocumentoDUI'];
            V_numeconMaskguion($("#txtduiC").val(),'txtduiC',14,'N&uacute;mero de '+NombreDocumentoDUIC,CantidNumIPrC,CantidNumIPtC);
        }else{
            $("#txtnombreC").val("");
            $("#txtnumtelefonoC").val("");
            $("#txtduiC").val("");
            /* ---  Validacion de nombre contacto ---*/
            V_Text_LetraNumero($("#txtnombreC").val(),'txtnombreC',12,'Nombre del contacto');
            /* ---  Validacion de nombre telefono ---*/
            var CantidTelefonorC = 0;var CantidTelefonotC = 0;
            CantidTelefonorC = arrg_Credls['CantidTelefono'] ;CantidTelefonotC = arrg_Credls['CantidTelefono'] + 1;
            V_numeconMaskguion($("#txtnumtelefonoC").val(),'txtnumtelefonoC',13,'N&uacute;mero de tel&eacute;fono',CantidTelefonorC,CantidTelefonotC);
            /* ---  Validacion de nombre dui ---*/
            var CantidNumIPrC = 0;var CantidNumIPtC = 0;
            CantidNumIPrC = arrg_Credls['CantidNumIP'];CantidNumIPtC = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
            var NombreDocumentoDUIC = "";NombreDocumentoDUIC = arrg_Credls['NombreDocumentoDUI'];
            V_numeconMaskguion($("#txtduiC").val(),'txtduiC',14,'N&uacute;mero de '+NombreDocumentoDUIC,CantidNumIPrC,CantidNumIPtC);
        }
        V_checksRadio(22,'El contacto es el mismo ?','GR_CheckRadioEC');
    });

    $('#DgrTableSN tbody').on( 'click', 'tr', function () {
        if(blockFCli==0){
            blockFCli=1000;
            arrg_fotos[1] = null;
            arrg_fotos[2] = null;
            arrg_fotos[3] = null;
            arrg_fotos[4] = null;
            arrg_fotos[5] = null;
            $("#info_contacto").hide();
            $("#imagen1").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            $("#imagen2").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            $("#imagen3").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            $("#imagen4").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            $("#imagen5").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            document.getElementById("form_actualizacion").reset();
            $(".is-valid").removeClass("is-valid");
            $(".is-invalid").removeClass("is-invalid");
            var dui_val = '', numregistro_val = '', estado_w_val = '', telefono_val = '', iva_percibido = '', personeria = '';
            Id_Cliente = '';
            arrg_vali_result = [];
            Id_Cliente = table.row( this ).data().Cli_Id;
            telefono_val = table.row( this ).data().Cli_telefono;
            dui_val = table.row( this ).data().Cli_dui;
            numregistro_val = table.row( this ).data().Cli_num_registro;
            nit_val = table.row( this ).data().Cli_nit;
            dui_val = String(dui_val);
            nit_val = String(nit_val);
            numregistro_val = String(numregistro_val);
            dui_val = dui_val.replace(/-/g, "");
            numregistro_val = numregistro_val.replace("-", "");
            estado_w_val = table.row( this ).data().Cli_estado;
            iva_percibido = table.row( this ).data().Cli_ctr_iva;
            personeria = table.row( this ).data().Cli_Pers_Id;
            $(this).addClass("SeletedTRSN");
            $("#lblcodcli").text(table.row( this ).data().Cli_codigo);
            $("#txtnombre").val(table.row( this ).data().Cli_nombre);
            $("#txtdireccion").val(table.row( this ).data().Cli_direccion);
            $("#txtcontacto").val(table.row( this ).data().Cli_contacto);
            $("#txtcorreo").val(table.row( this ).data().Cli_correo);
            $("#txtnumtelefono").unmask().val(telefono_val).mask(arrg_Credls['FormatoTelefono']);
            $("#txtdui").unmask().val(dui_val).mask(arrg_Credls['FormatoNumIP']);
            $("#txtnit").unmask().val(nit_val).mask(arrg_Credls['FormatoNumNIT']);
            // $("#txtnit").val(nit_val);
            $("#txtnumcontribuyente").val(numregistro_val);
            $('#lblcodcli').removeClass();
            $(".is-valid").removeClass("is-valid");
            $(".telefonos").unmask().mask(arrg_Credls['FormatoTelefono'],{placeholder: arrg_Credls['FormatoTelefono']});
            $(".duis").unmask().mask(arrg_Credls['FormatoNumIP'],{placeholder: arrg_Credls['FormatoNumIP']});
            if(personeria == 1){
                $('#rdpersoneria1').click();
                $("#div_dui").show();
                $("#foto_dui").show();
            }else if(personeria == 2){
                $('#rdpersoneria2').click();
                $("#div_dui").hide();
                $("#foto_dui").hide();
            }
            if(iva_percibido == null){
                $("input[name='rdcontribuyente']").prop("disabled", false);
            }else{
                if(iva_percibido == 1 ){
                    $("#div_numregistro").show();
                    $('#rdcontribuyente1').click();
                    $("#rdcontribuyente1").prop("checked", true);
                }else{
                    $("#div_numregistro").hide();
                    $('#rdcontribuyente2').click();
                }
            }
            if(estado_w_val == 1)
                $('#lblcodcli').addClass('badge badge-success');
            else
                $('#lblcodcli').addClass('badge badge-danger');
            if(isNaN(dui_val))
                dui_val = '';
            if(isNaN(numregistro_val))
                numregistro_val = '';
            getInfoSN();
        }
    });
    $('#ModalCliSN').on('shown.bs.modal', function (e) {
        $('#dias_busqueda option').prop('selected', function() {return this.defaultSelected;});
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_filtros', "readonly");
        var object = data.objectStore('tbl_filtros');
        var elements = [];
        object.openCursor().onsuccess = function (e) {
            var result = e.target.result;
            if(result === null){
                return;
            }
            elements.push(result.value);
            result.continue();
        };
        data.oncomplete = function () {
            $("#dias_busqueda").val(elements[0].ValueAC);
        };
        data.onerror = function (e) {
            
        };
        table =  $('#DgrTableSN').DataTable({
            "data" : DataJSON_Cli,
            "columns" : [
                { "data" : "Cli_codigo" },
                { "data" : "Cli_nombre" },
                { "data" : "Cli_direccion" },
                { "data" : "Cli_contacto" },
                { "data" : "Cli_telefono" },
                { "data" : "Cli_dia" },
                { "data" : "Cli_ul_fecha_ac_cliente" }
            ],
            "columnDefs":[
                {
                    "targets":[0],
                    "data": "Cli_codigo",
                    "render": function(data, type, row){
                        var EstadoCensado = '';var span_estadow = '';var span_categoria = '';
                        if(row.Cli_ac_cliente == '1'){
                            EstadoCensado = '<span class="vya fas fa-check fa-2x"></span><br>';
                        }else{
                            EstadoCensado = '';
                        }
                        if(row.Cli_estado == '1'){
                            span_estadow = '<span class="badge badge-success">'+data+' <span style="display:none;">VERDES TDOS</span></span>';
                        }else{
                            span_estadow = '<span class="badge badge-danger">'+data+' <span style="display:none;">TDOS ROJOS</span></span>';
                        }
                        if(row.Cli_categoria == 'S'){
                            span_categoria = '<br><p class="text-secondary" style="font-size:14px;font-weight:500;">SIN CATEGORIA</p>';
                        }else{
                            span_categoria = '<br><p class="text-dark" style="font-size:29px;font-weight:500;">'+row.Cli_categoria+'</p>';
                        }
                        return EstadoCensado+span_estadow+span_categoria;
                    }
                },
                {
                    "targets":[5],
                    "data": "Cli_dia",
                    "render": function(data, type, row){
                        var badge_dias = ``;
                        if(row.Cli_l == 1)
                            badge_dias += `<span class="badge badge-info">LUNES</span>`;
                        if(row.Cli_m == 1)
                            badge_dias += `<span class="badge badge-info">MARTES</span>`;
                        if(row.Cli_mi == 1)
                            badge_dias += `<span class="badge badge-info">MIERCOLES</span>`;
                        if(row.Cli_j == 1)
                            badge_dias += `<span class="badge badge-info">JUEVES</span>`;
                        if(row.Cli_v == 1)
                            badge_dias += `<span class="badge badge-info">VIERNES</span>`;
                        if(row.Cli_s == 1)
                            badge_dias += `<span class="badge badge-info">SABADO</span>`;
                        if(row.Cli_d == 1)
                            badge_dias += `<span class="badge badge-info">DOMINGO</span>`;
                        return badge_dias;
                    }//FIN RENDER
                },
                {
                    "targets":[6],
                    "data": "Cli_ul_fecha_ac_cliente",
                    "render": function(data, type, row){
                        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        var fecha_formateada = '';
                        if(!_empty(data)){
                            var fecha = new Date(data);
                            fecha_formateada = fecha.toLocaleDateString("es-ES", options);
                        }else{
                            fecha_formateada = 'NA';
                        }
                        return fecha_formateada;
                    }
                }
            ],
            initComplete: function () {
                this.api().columns([5]).every(function(i){
                var column = this;
                    if(arrg_Credls['FiltroDiaVisitaAC'] == 'LUNES'){
                        column.search('LUNES').draw();
                        $('#dias_busqueda').val('LUNES');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'MARTES') {
                        column.search('MARTES').draw();
                        $('#dias_busqueda').val('MARTES');
                    } else if (arrg_Credls['FiltroDiaVisitaAC'] == 'MIERCOLES') {
                        column.search('MIERCOLES').draw();
                        $('#dias_busqueda').val('MIERCOLES');
                    }else if (arrg_Credls['FiltroDiaVisitaAC'] == 'JUEVES') {
                        column.search('JUEVES').draw();
                        $('#dias_busqueda').val('JUEVES');
                    }else if (arrg_Credls['FiltroDiaVisitaAC'] == 'VIERNES') {
                        column.search('VIERNES').draw();
                        $('#dias_busqueda').val('VIERNES');
                    }else if (arrg_Credls['FiltroDiaVisitaAC'] == 'SABADO') {
                        column.search('SABADO').draw();
                        $('#dias_busqueda').val('SABADO');
                    }else if (arrg_Credls['FiltroDiaVisitaAC'] == 'DOMINGO') {
                        column.search('DOMINGO').draw();
                        $('#dias_busqueda').val('DOMINGO');
                    }
                    var switchs_d = $('#dias_busqueda').on( 'change', function(e){
                        var vall = $.fn.dataTable.util.escapeRegex($(this).val());
                        guardar_filtro(vall,1);
                        if(vall == 'LUNES'){
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
                        }else if (vall == 'VIERNES') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'VIERNES';
                            column.search(vall).draw();
                        }else if (vall == 'SABADO') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'SABADO';
                            column.search(vall).draw();
                        }else if (vall == 'DOMINGO') {
                            arrg_Credls['FiltroDiaVisitaAC'] = 'DOMINGO';
                            column.search(vall).draw();
                        }
                    });
                });
                this.api().columns([0]).every(function(i){
                    var column = this;
                    if(arrg_Credls['FiltroEstadoAC'] == 'TDOS'){
                        column.search('TDOS').draw();
                        $('#switch_ESAC').val('TDOS');
                    } else if (arrg_Credls['FiltroEstadoAC'] == 'VERDES') {
                        column.search('VERDES').draw();
                        $('#switch_ESAC').val('VERDES');
                    } else if (arrg_Credls['FiltroEstadoAC'] == 'ROJOS') {
                        column.search('ROJOS').draw();
                        $('#switch_ESAC').val('ROJOS');
                    }
                    var switchs = $('#switch_ESAC').on( 'change', function(e){
                        var vall = $.fn.dataTable.util.escapeRegex($(this).val());
                        guardar_filtroEstado(vall);
                        if(vall == 'TDOS'){
                            arrg_Credls['FiltroEstadoAC'] = 'TDOS';
                            column.search(vall).draw();
                        } else if (vall == 'VERDES') {
                            arrg_Credls['FiltroEstadoAC'] = 'VERDES';
                            column.search(vall).draw();
                        } else if (vall == 'ROJOS') {
                            arrg_Credls['FiltroEstadoAC'] = 'ROJOS';
                            column.search(vall).draw();
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
            drawCallback: function() {
                this.api().state.clear();
            },
            "dom": '<"top"i>frt<"bottom"lp><"clear">',
            "ordering": true,
            "info": false,
            "lengthChange": false,
            "iDisplayLength": 10, 
            "pagingType": "numbers",
            "scrollY":"50vh",
            "scrollX":"50vh",
            "scrollCollapse": true
        });
    });
    $('#ModalCliSN').on('hidden.bs.modal', function (e) {
        $('#DgrTableSN').DataTable().destroy();
        $("#showDataSN").empty();
    });
    $("#XX").click(function() {
        $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
            $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                document.getElementById("form_actualizacion").reset();
                $('input.checkbox').prop('checked',false);
                Id_Cliente = '';
            });
        });
    });
    $("#txtnombre").keyup(function() {
        V_Text_LetraNumero($("#txtnombre").val(),'txtnombre',0,'Nombre del establecimiento');
        warn_on_unload = 'no salir';
    });
    $("#txtcorreo").keyup(function() {
        V_email($("#txtcorreo").val(),'txtcorreo',4,'Correo');
        warn_on_unload = 'no salir';
    });
    $("#txtdireccion").keyup(function() {
        V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
        warn_on_unload = 'no salir';  
    });
    $("#txtnumtelefono").keyup(function() {
        var CantidTelefonor = 0;var CantidTelefonot = 0;
        CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguion($("#txtnumtelefono").val(),'txtnumtelefono',5,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot);
        warn_on_unload = 'no salir';
        $("#txtnumtelefonoC").val($(this).val());
        var CantidTelefonorC = 0;var CantidTelefonotC = 0;
        CantidTelefonorC = arrg_Credls['CantidTelefono'] ;CantidTelefonotC = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguion($("#txtnumtelefonoC").val(),'txtnumtelefonoC',13,'N&uacute;mero de tel&eacute;fono',CantidTelefonorC,CantidTelefonotC);
    });
    $("#txtcontacto").keyup(function() {
        V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',2,'Nombre de contacto');
        warn_on_unload = 'no salir';
        $("#txtnombreC").val($(this).val());
        V_Text_LetraNumero($("#txtnombreC").val(),'txtnombreC',12,'Nombre del contacto');
    });
    $("#txtdui").keyup(function() {
        warn_on_unload = 'no salir';
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguion($("#txtdui").val(),'txtdui',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
        $("#txtduiC").val($(this).val());
        var CantidNumIPrC = 0;var CantidNumIPtC = 0;
        CantidNumIPrC = arrg_Credls['CantidNumIP'];CantidNumIPtC = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUIC = "";NombreDocumentoDUIC = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguion($("#txtduiC").val(),'txtduiC',14,'N&uacute;mero de '+NombreDocumentoDUIC,CantidNumIPrC,CantidNumIPtC);
    });
    $("#txtnumcontribuyente").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero($("#txtnumcontribuyente").val(),'txtnumcontribuyente',8,'N&uacute;mero de contribuyente');
    });
    $("#txtnumtelefonoC").keyup(function() {
        var CantidTelefonorC = 0;var CantidTelefonotC = 0;
        CantidTelefonorC = arrg_Credls['CantidTelefono'] ;CantidTelefonotC = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguion($("#txtnumtelefonoC").val(),'txtnumtelefonoC',13,'N&uacute;mero de tel&eacute;fono',CantidTelefonorC,CantidTelefonotC);
        warn_on_unload = 'no salir';
    });
    $("#txtnombreC").keyup(function() {
        V_Text_LetraNumero($("#txtnombreC").val(),'txtnombreC',12,'Nombre del contacto');
        warn_on_unload = 'no salir';
    });
    $("#txtduiC").keyup(function() {
        warn_on_unload = 'no salir';
        var CantidNumIPrC = 0;var CantidNumIPtC = 0;
        CantidNumIPrC = arrg_Credls['CantidNumIP'];CantidNumIPtC = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUIC = "";NombreDocumentoDUIC = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguion($("#txtduiC").val(),'txtduiC',14,'N&uacute;mero de '+NombreDocumentoDUIC,CantidNumIPrC,CantidNumIPtC);
    });
    $("#txtnumtelefonoR").keyup(function() {
        var CantidTelefonorR = 0;var CantidTelefonotR = 0;
        CantidTelefonorR = arrg_Credls['CantidTelefono'] ;CantidTelefonotR = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguionOff($("#txtnumtelefonoR").val(),'txtnumtelefonoR',16,'N&uacute;mero de tel&eacute;fono',CantidTelefonorR,CantidTelefonotR);
        warn_on_unload = 'no salir';
    });
    $("#txtnombreR").keyup(function() {
        V_Text_LetraNumerOff($("#txtnombreR").val(),'txtnombreR',15,'Nombre del representante legal');
        warn_on_unload = 'no salir'; 
    });
    $("#txtduiR").keyup(function() {
        warn_on_unload = 'no salir';
        var CantidNumIPrR = 0;var CantidNumIPtR = 0;
        CantidNumIPrR = arrg_Credls['CantidNumIP'];CantidNumIPtR = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUIR = "";NombreDocumentoDUIR = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguionOff($("#txtduiR").val(),'txtduiR',17,'N&uacute;mero de '+NombreDocumentoDUIR,CantidNumIPrR,CantidNumIPtR);
    });
    $("#txtnit").keyup(function() {
        warn_on_unload = 'no salir';
        var CantidNumNITr = 0;var CantidNumNITt = 0;
        CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
        var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
        V_numeconMaskguionOff($("#txtnit").val(),'txtnit',20,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
    });
});
//FINAL DOCUMENT.READY
function obtener_municipios(txtdepartamento,valorSmunicipio){
    var arr_mun = [];
    if(!_empty(txtdepartamento)){
        $('#if-departamento').show();
        var atributos_dropdown = {
            class_input:'form-control custom-select outlinenone'
        };
        var active = dataBaseAppSDV.result;
        var data = active.transaction(["tbl_municipio"], "readonly");
        var object = data.objectStore("tbl_municipio");
        var myIndex = object.index('by_depat'); 
        var elements = [];
        var request = myIndex.openCursor(IDBKeyRange.only(txtdepartamento));
        request.onsuccess = function() {
            var cursor = request.result;
            var i=0;
            if (cursor) {
                elements.push(cursor.value);
                cursor.continue();
            } else {
                //NUll
            }
        };
        data.oncomplete = function () {
            for (var key in elements) {
                arr_mun[key] = {
                    codbx:elements[key].codbx,
                    valor:elements[key].valor
                };
            }
            elements = [];
            var selectes = '';
            selectes = _form_dropdown('cbmunicipio',arr_mun,valorSmunicipio,atributos_dropdown);
            $("#c-municipio").html(selectes);
            V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
        };   
    }else{
        $('#if-departamento').hide();
    }
}
function obtener_giros_negocios(txttpuntoventa,valorStpuntoventa){
    var arr_mun = [];
    if(!_empty(txttpuntoventa)){
        // $('#if-departamento').show();
        var atributos_dropdown = {
            class_input:'form-control custom-select outlinenone'
        };
        var active = dataBaseAppSDV.result;
        var data = active.transaction(["tbl_gironegocio"], "readonly");
        var object = data.objectStore("tbl_gironegocio");
        var myIndex = object.index('by_tpventa'); 
        var elements = [];
        var request = myIndex.openCursor(IDBKeyRange.only(txttpuntoventa));
        request.onsuccess = function() {
            var cursor = request.result;
            var i=0;
            if (cursor) {
                elements.push(cursor.value);
                cursor.continue();
            } else {
                //NUll
            }
        };
        data.oncomplete = function () {
            for (var key in elements) {
                arr_mun[key] = {
                    codbx:elements[key].codbx,
                    valor:elements[key].valor
                };
            }
            elements = [];
            var selectes = '';
            selectes = _form_dropdown('cbgironegocio',arr_mun,valorStpuntoventa,atributos_dropdown);
            $("#c-gironegocio").html(selectes);
            V_Selec($("#cbgironegocio").val(),'cbgironegocio',25,'Giro de negocio');
        };   
    }else{
        $('#if-departamento').hide();
    }
}
function getInfoSN(){
    $.when( $('#InfoCuadro').stop(true,true).hide() ).done(function( x ) {
        $.when( $('#form_actuinfo').stop(true,true).show() ).done(function( x ) {
            $("#ModalCliSN").modal("toggle");
            $("#showDataSN").empty();
            $('#DgrTableSN').DataTable().destroy();
            blockFCli=0;
            validacion_form_actu();
        });
    });
}
/*------------------------------------------------------------*/
/*-------------------CONSULTAR COORDENADAS--------------------*/
/*------------------------------------------------------------*/
function consultar_coordenadas(){
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'comprobar/conexion',
                type     : 'POST',
                dataType : 'JSON',
                data     : {"pin":'conexions'},
                timeout  : 13777
            }).always(function(_resp, textStatus, errorThrown) {
                $("body").attr('style',  'overflow-y:;');
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        if (textStatus == "success") {
                            if(_resp.rs == true){
                                getLocationLeaflet();
                            }else{
                                Swal.fire({
                                    title: 'Aviso!',
                                    type: 'error',
                                    html:'<h5>'+_resp.errores+'</h5>',
                                    confirmButtonText:'Ok'
                                });
                            }
                        }else{
                            _ajax_error_validacion_loglat(_resp.status,_resp.readyState,_resp.statusText,datas);
                        }
                    });
                });
            }); 
        });
    });
}
/*------------------------------------------------------------*/
/*------------------VALIDACIONES FROMULARIO-------------------*/
/*------------------------------------------------------------*/
function V_CoordenadasLL(data_C){
    var data_E=/^-?[0-9]\d*(\.\d+)?$/gm
    if(data_E.test(String(data_C))){
        // console.log('SON VALIDAS');
        return true;
    }else{
        // console.log('NO SON VALIDAS');
        return false;
    }
}
function V_NumeroEnteroDecimalpogps(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        if(data_C==0){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = '<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.';
            $("#error-mjs-"+ordencampo).html('<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.');
        }else{
            var data_E=/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/gm
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
                $("#error-mjs-"+ordencampo).html('');
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
            }
        }

    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';     
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}
function V_NumeroEnteroDecimalne(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;

    if(data_C!=""){
        if(data_C==0){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = '<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.';
            $("#error-mjs-"+ordencampo).html('<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.');
        }else{
            var data_E=/^[-]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/gm
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
                $("#error-mjs-"+ordencampo).html('');
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
            }
        }
    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}
function V_Text_LetraNumero(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-. ]+$/g
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length<7){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es muy corto.';
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es muy corto.');
        }else{
            if(data_C.length>77){
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> no puede exceder los 77 caracteres';
                $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> no puede exceder los 77 caracteres');
            }else{
                if(data_E.test(String(data_C))){
                    v = 1;
                    $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                    arrg_vali_result[ordencampo] = '';
                }else{
                    v = 0;
                    $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                    $("#error-mjs-"+ordencampo).html('Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>');
                    arrg_vali_result[ordencampo] = 'Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>';
                }
            }
        }
    }
    return v;
}
function V_Text_LetraNumerOff(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-. ]+$/g
    if(_empty(data_C)){
        v = 1;
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';
    }else{
        if(data_C.length<7){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es muy corto.';
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es muy corto.');
        }else{
            if(data_C.length>77){
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> no puede exceder los 77 caracteres';
                $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> no puede exceder los 77 caracteres');
            }else{
                if(data_E.test(String(data_C))){
                    v = 1;
                    $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                    arrg_vali_result[ordencampo] = '';
                }else{
                    v = 0;
                    $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                    $("#error-mjs-"+ordencampo).html('Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>');
                    arrg_vali_result[ordencampo] = 'Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>';
                }
            }
        }
    }
    return v;
}
function V_Text_LetraNumero_Direccion(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-./, ]+$/g
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es obligatoria.';
        $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> es obligatoria.');
    }else{
        if(data_C.length<25){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es muy corta.';
            $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> es muy corta.');
        }else{
            if(data_C.length>250){
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> no puede exceder los <strong>250</strong> caracteres';
                $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> no puede exceder los <strong>250</strong> caracteres');
            }else{
                if(data_E.test(String(data_C))){
                    v = 1;
                    $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                    arrg_vali_result[ordencampo] = '';
                }else{
                    v = 0;
                    $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                    $("#error-mjs-"+ordencampo).html('Por favor verifique la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>');
                    arrg_vali_result[ordencampo] = 'Por favor verifique la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>';
                }
            }
        }
    }
    return v;
}
function V_Text_LetraNumero_Motivo(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-./, ]+$/g
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length<10){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es muy corto.';
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es muy corto.');
        }else{
            if(data_C.length>250){
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> no puede exceder los <strong>250</strong> caracteres';
                $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> no puede exceder los <strong>250</strong> caracteres');
            }else{
                if(data_E.test(String(data_C))){
                    v = 1;
                    $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                    arrg_vali_result[ordencampo] = '';
                }else{
                    v = 0;
                    $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                    $("#error-mjs-"+ordencampo).html('Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>');
                    arrg_vali_result[ordencampo] = 'Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>';
                }
            }
        }
    }
    return v;
}
function V_Text_ConEspacio(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/g
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length<6){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es muy corto.');
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es muy corto.';
        }else{
            if(data_C.length>77){
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> no puede exceder los 77 caracteres');
                arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> no puede exceder los 77 caracteres';
            }else{
                if(data_E.test(String(data_C))){
                    v = 1;
                    $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                    arrg_vali_result[ordencampo] = '';
                }else{
                    v = 0;
                    $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                    $("#error-mjs-"+ordencampo).html('Por favor verifique el <strong>'+etiqueta+'</strong>, se necesita minimo un nombre y un apellido (solo letras).');
                    arrg_vali_result[ordencampo] = 'Por favor verifique el <strong>'+etiqueta+'</strong>, se necesita minimo un nombre y un apellido (solo letras).';
                }
            }
        }
    }
    return v;
}
function V_numeconMaskguion(data,campo,ordencampo,etiqueta,valcantir,valcantit){
    var  v = 0;
    var data_C=data.trim();
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data.length == valcantit){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El valor del campo <strong>'+etiqueta+'</strong> tiene que tener '+valcantir+' digitos.';
            $("#error-mjs-"+ordencampo).html('El valor del campo <strong>'+etiqueta+'</strong> tiene que tener '+valcantir+' digitos.');
        }
    }
    return v;
}
function V_numeconMaskguionOff(data,campo,ordencampo,etiqueta,valcantir,valcantit){
    var  v = 0;
    var data_C=data.trim();
    if(_empty(data_C)){
        v = 1;
        arrg_vali_result[ordencampo] = '';
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
    }else{
        if(data.length == valcantit){
            v = 1;
            arrg_vali_result[ordencampo] = '';
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        }else{
            v = 0;
            arrg_vali_result[ordencampo] = 'El valor del campo <strong>'+etiqueta+'</strong> tiene que tener '+valcantir+' digitos.';
            $("#error-mjs-"+ordencampo).html('El valor del campo <strong>'+etiqueta+'</strong> tiene que tener '+valcantir+' digitos.');
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        }
    }
    return v;
}
function V_numeconMaskguionGT(data,campo,ordencampo,etiqueta,valcantir,valcantit){
    var  v = 0;
    var data_C=data.trim();
    var data_E=/^[A-Za-z0-9]+$/g
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_E.test(String(data_C))){
            if(data.length >= 7){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'El valor de <strong>'+etiqueta+'</strong> tiene que tener al menos 7 digitos.';
                $("#error-mjs-"+ordencampo).html('El valor de <strong>'+etiqueta+'</strong> tiene que tener al menos 7 digitos.');
            }

        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            $("#error-mjs-"+ordencampo).html('Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros</strong>');
            arrg_vali_result[ordencampo] = 'Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros</strong>';
        }
    }
    return v;
}
function V_email(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        var data_E=/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if(data_E.test(String(data_C))){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = '<strong>'+etiqueta+' invalido</strong> .';
            $("#error-mjs-"+ordencampo).html('En el campo <strong>'+etiqueta+'</strong> invalido');
        }
    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}
function V_Selec(data,campo,ordencampo,etiqueta){
    var v = 0;
    if(_empty(data)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo de selecci&oacute;n <strong>'+etiqueta+'</strong> es obligatorio.';
    }else{
        v = 1;
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';
    }
    return v;
}
function V_checks(ordencampo,etiqueta){
    var  v = 0;
    var checks = document.getElementsByClassName("GR_Check");
    var val_checks=false
    for (var i = 0; i < checks.length; i++) {
        if(checks[i].checked==true){
            val_checks=true;
        }
    }
    if(val_checks==true){
        v = 1;
        $(".GR_Check").removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';        
    }else{
        v = 0;
        $(".GR_Check").removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
    }
    return v;
}
function V_NumeroEntero2digitos(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        if(parseInt(data) > 0){
            var data_E=/^[0-9]{1,3}$/gm
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros enteros positivos de maximo 3 digitos.';
                $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros enteros positivos de maximo 3 digitos.');
            }
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> no puede ser cero.';
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> no puede ser cero.');
        }
    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}
function V_CoordenadasLL_ContarOK(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C == 0){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es obligatoria.';
        $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> es obligatoria.');
    }else{
        if(data_C!=""){
            var data_E=/^-?[0-9]\d*(\.\d+)?$/gm
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
            }
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
            $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
        }
    }

    return v;
}
function V_NumeroEntero(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        var data_E=/^[0-9]*$/gm
        if(data_E.test(String(data_C))){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros sin puntos y guiones.';
            $("#error-mjs-"+ordencampo).html('En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros sin puntos y guiones.');
        }
    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}
function V_checksRadio(ordencampo,etiqueta,className){
    var  v = 0;
    var checks = document.getElementsByClassName(className);
    var val_checks=false
    for (var i = 0; i < checks.length; i++) {
        if(checks[i].checked==true){
            val_checks=true;
        }
    }
    if(val_checks==true){
        v = 1;
        $("."+className).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';        
    }else{
        v = 0;
        $("."+className).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}



function validacion_form_actu(){
    var contarok = 0;
    var rdpersoneria = '', rdcontribuyente = '';

    rdpersoneria = $('input:radio[name=rdpersoneria]:checked').val();
    rdcontribuyente = $('input:radio[name=rdcontribuyente]:checked').val();

    arrg_vali_result = [];

    // OBLIGATORIOS QUE SE MANTIENEN
    contarok += V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
    contarok += V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',2,'Nombre de contacto');

    // TELÉFONO PRINCIPAL NO OBLIGATORIO
    var CantidTelefonor = arrg_Credls['CantidTelefono'];
    var CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
    V_numeconMaskguionOff($("#txtnumtelefono").val(),'txtnumtelefono',3,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot);
    arrg_vali_result[3] = '';
    contarok += 1;

    // CORREO NO OBLIGATORIO
    $("#txtcorreo").removeClass("is-invalid").addClass("is-valid");
    $("#error-mjs-4").html('');
    arrg_vali_result[4] = '';
    contarok += 1;

    // PERSONERÍA NO OBLIGATORIA
    $(".GR_CheckRadio").removeClass("is-invalid").addClass("is-valid");
    $("#error-mjs-5").html('');
    arrg_vali_result[5] = '';
    contarok += 1;

    // CONTRIBUYENTE IVA NO BLOQUEA
    $(".GR_CheckRadioC").removeClass("is-invalid").addClass("is-valid");
    $("#error-mjs-6").html('');
    arrg_vali_result[6] = '';
    contarok += 1;

    // DUI PRINCIPAL NO OBLIGATORIO
    var CantidNumIPr = arrg_Credls['CantidNumIP'];
    var CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
    var NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
    V_numeconMaskguionOff($("#txtdui").val(),'txtdui',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
    arrg_vali_result[7] = '';
    contarok += 1;

    // NRC / NÚMERO CONTRIBUYENTE NO OBLIGATORIO
    $("#txtnumcontribuyente").removeClass("is-invalid").addClass("is-valid");
    $("#error-mjs-8").html('');
    arrg_vali_result[8] = '';
    contarok += 1;

    // FOTOS NO OBLIGATORIAS
    $("#file1, #file2, #file3, #file4, #file5").removeClass("is-invalid").addClass("is-valid");
    $("#error-mjs-9").html('');
    $("#error-mjs-10").html('');
    $("#error-mjs-18").html('');
    $("#error-mjs-19").html('');
    $("#error-mjs-21").html('');

    arrg_vali_result[9]  = '';
    arrg_vali_result[10] = '';
    arrg_vali_result[18] = '';
    arrg_vali_result[19] = '';
    arrg_vali_result[21] = '';

    contarok += 5;

    // CONTACTO ES EL MISMO NO OBLIGATORIO
    $(".GR_CheckRadioEC").removeClass("is-invalid").addClass("is-valid");
    $("#error-mjs-22").html('');
    arrg_vali_result[22] = '';
    contarok += 1;

    // CONTACTO DOCUMENTACIÓN NO OBLIGATORIO
    V_Text_LetraNumerOff($("#txtnombreC").val(),'txtnombreC',12,'Nombre del contacto');
    arrg_vali_result[12] = '';
    contarok += 1;

    var CantidTelefonorC = arrg_Credls['CantidTelefono'];
    var CantidTelefonotC = arrg_Credls['CantidTelefono'] + 1;
    V_numeconMaskguionOff($("#txtnumtelefonoC").val(),'txtnumtelefonoC',13,'N&uacute;mero de tel&eacute;fono',CantidTelefonorC,CantidTelefonotC);
    arrg_vali_result[13] = '';
    contarok += 1;

    var CantidNumIPrC = arrg_Credls['CantidNumIP'];
    var CantidNumIPtC = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
    var NombreDocumentoDUIC = arrg_Credls['NombreDocumentoDUI'];
    V_numeconMaskguionOff($("#txtduiC").val(),'txtduiC',14,'N&uacute;mero de '+NombreDocumentoDUIC,CantidNumIPrC,CantidNumIPtC);
    arrg_vali_result[14] = '';
    contarok += 1;

    // REPRESENTANTE LEGAL OPCIONAL
    V_Text_LetraNumerOff($("#txtnombreR").val(),'txtnombreR',15,'Nombre del representante legal');
    arrg_vali_result[15] = '';

    var CantidTelefonorR = arrg_Credls['CantidTelefono'];
    var CantidTelefonotR = arrg_Credls['CantidTelefono'] + 1;
    V_numeconMaskguionOff($("#txtnumtelefonoR").val(),'txtnumtelefonoR',16,'N&uacute;mero de tel&eacute;fono',CantidTelefonorR,CantidTelefonotR);
    arrg_vali_result[16] = '';

    var CantidNumIPrR = arrg_Credls['CantidNumIP'];
    var CantidNumIPtR = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
    var NombreDocumentoDUIR = arrg_Credls['NombreDocumentoDUI'];
    V_numeconMaskguionOff($("#txtduiR").val(),'txtduiR',17,'N&uacute;mero de '+NombreDocumentoDUIR,CantidNumIPrR,CantidNumIPtR);
    arrg_vali_result[17] = '';

    // NIT OPCIONAL
    var CantidNumNITr = arrg_Credls['CantidNumNIT'];
    var CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
    var NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
    V_numeconMaskguionOff($("#txtnit").val(),'txtnit',20,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
    arrg_vali_result[20] = '';

    // OBSERVACIONES OPCIONAL
    V_Text_LetraNumerOff($("#txtobservacion").val(),'txtobservacion',23,'Observaciones');
    arrg_vali_result[23] = '';

    return 16;
}

function inputfilevalidacion(campo,ordencampo,etiqueta){
    // Para actualización de clientes, ningún archivo es obligatorio
    if(campo == 'file1' || campo == 'file2' || campo == 'file3' || campo == 'file4' || campo == 'file5'){
        $("#" + campo).removeClass("is-invalid").addClass("is-valid");
        $("#error-mjs-" + ordencampo).html('');
        arrg_vali_result[ordencampo] = '';
        return 1;
    }

    var v = 0;
    var fileInput = document.getElementById(campo);
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg)$/i;

    if(!allowedExtensions.exec(filePath)){
        v = 0;
        fileInput.value = '';
        $("#" + campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>' + etiqueta + '</strong> es obligatoria.';
    }else{
        v = 1;
        $("#" + campo).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';
    }

    return v;
}

/*function procesar_actualizacion(){
    var fechaEnDispositivo = '0000-00-00 00:00:00';
    fechaEnDispositivo = fechaDispositivo();
    var datos = [];
    datos = $("#form_actualizacion").serializeArray();
    datos.push({name: 'Id_Cliente', value: Id_Cliente});
    datos.push({name: 'Codigo_Cli', value: $("#lblcodcli").text()});
    datos.push({name: 'fechaEnDispositivo', value: fechaEnDispositivo});
    datos.push({name: 'Usu_Id', value: arrg_Credls['us_cod']});
    datos.push({name: 'fotodui', value: arrg_fotos[2]});
    datos.push({name: 'fotoduitrasera', value: arrg_fotos[3]});
    datos.push({name: 'fotonrc', value: arrg_fotos[1]});
    datos.push({name: 'fotonit', value: arrg_fotos[4]});
    datos.push({name: 'fotonrctrasera', value: arrg_fotos[5]});
    datos.push({name: 'Actc_cola', value: "NO"});
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'actualizar_datos/ok',
                type     : 'POST',
                dataType : 'JSON',
                data     : datos,
                timeout  : 10777
            }).done(function(_resp){
                if(_resp.rs == true){
                    // DB_GuardarPermanenteCLIAC('tbl_clientesactuingre',0,datas);
                }
            }).always(function(_resp, textStatus, errorThrown) {
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
                            $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                                if(textStatus == "success") {
                                    if(_resp.rs == true){
                                        Swal.fire({
                                            type: 'success',
                                            title: 'Registro enviado exitosamente!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then((result) => {
                                            Id_Cliente = '';
                                            arrg_fotos[1] = null;
                                            arrg_fotos[2] = null;
                                            arrg_fotos[3] = null;
                                            arrg_fotos[4] = null;
                                            arrg_fotos[5] = null;
                                            document.getElementById("form_actualizacion").reset();
                                            $("#info_contacto").hide();
                                            $(".is-valid").removeClass("is-valid");
                                            $("input[name='rdpersoneria']").prop("checked", false);
                                            $("input[name='rdcontribuyente']").prop("checked", false);
                                            $("#imagen1").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                            $("#imagen2").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                            $("#imagen3").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                            $("#imagen4").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                            $("#imagen5").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                        });
                                    }else{
                                        Swal.fire({
                                            title: 'Aviso!',
                                            type: 'error',
                                            html:'<h6>'+_resp.errores+'</h6>',
                                            confirmButtonText:'Ok'
                                        });
                                    }
                                }else{
                                    var jsonData = {};
                                    $.each(datos, function(index, field) {
                                        if(field.name == 'Actc_cola'){
                                            jsonData[field.name] = "SI";
                                        }else{
                                            jsonData[field.name] = field.value;
                                        }
                                    });
                                    _ajax_error_(_resp.status,_resp.readyState,_resp.statusText,jsonData);
                                }
                            });
                        });
                    });
                });
            });
        });
    });
}*/


function procesar_actualizacion(){
  try {
    var fechaEnDispositivo = fechaDispositivo();

    // Base: todos los campos del form
    var datos = $("#form_actualizacion").serializeArray();

    // ===== COORDENADAS =====
    var isSwitchTwo = !!document.getElementById('switch-two') && document.getElementById('switch-two').checked;
    var lat = ($('#txtlatitud').val() || '').toString().trim();
    var lng = ($('#txtlongitud').val() || '').toString().trim();

    // Si el switch está activo, obligamos a tener lat/lng
    if (isSwitchTwo && (!lat || !lng)) {
      Swal.fire({
        type: 'info',
        title: 'Atención',
        html: '<strong>Se te ha olvidado tomar las nuevas coordenadas.</strong>'
      }).then(function () {
        if ($("#anclacoord").length){
          $('body,html').animate({ scrollTop: $("#anclacoord").offset().top }, 500);
        }
      });
      return;
    }

    // Fallback: coordenadas temporales si inputs están vacíos
    if ((!lat || !lng) && Array.isArray(window.coordenadas_Tempo)) {
      lat = lat || (window.coordenadas_Tempo[0] || '');
      lng = lng || (window.coordenadas_Tempo[1] || '');
    }

    // Normaliza a 6 decimales si son numéricos
    if (!isNaN(parseFloat(lat))) lat = Number(lat).toFixed(6);
    if (!isNaN(parseFloat(lng))) lng = Number(lng).toFixed(6);

    // ===== FRECUENCIA / DÍAS / ÓRDENES =====
    // helper: checkbox -> "0"/"1"
    function toFlag(sel) { return $(sel).is(':checked') ? '1' : '0'; }
    // helper: orden -> entero >=0 (o "0" si vacío/no numérico)
    function toOrder(sel) {
      var v = ($(sel).val() || '').trim();
      var n = parseInt(v, 10);
      return (Number.isFinite(n) && n >= 0) ? String(n) : '0';
    }

    // Frecuencia (string tipo "1,2,3,4,5" o "")
    var Actc_frecuencia_visita = $('#cbfrecuenciavisita').val() || '';

    // === OBLIGATORIO: Frecuencia de Visita ===
    if (!Actc_frecuencia_visita) {
      $('#cbfrecuenciavisita').addClass('is-invalid').focus();
      Swal.fire({
        icon: 'warning',
        title: 'Falta la Frecuencia de Visita',
        html: '<strong>Por favor selecciona la Frecuencia de Visita.</strong>'
      });
      return; // Detenemos el flujo
    } else {
      $('#cbfrecuenciavisita').removeClass('is-invalid').addClass('is-valid');
    }

    // Flags de días
    var Actc_l  = toFlag('#checklunes');
    var Actc_m  = toFlag('#checkmartes');
    var Actc_mi = toFlag('#checkmiercoles');
    var Actc_j  = toFlag('#checkjueves');
    var Actc_v  = toFlag('#checkviernes');
    var Actc_s  = toFlag('#checksabado');
    var Actc_d  = toFlag('#checkdomingo');

    // Órdenes por día (si NO está marcado, siempre "0")
    var Actc_orden_l  = (Actc_l  === '1') ? toOrder('#txtordenvisital')  : '0';
    var Actc_orden_m  = (Actc_m  === '1') ? toOrder('#txtordenvisitam')  : '0';
    var Actc_orden_mi = (Actc_mi === '1') ? toOrder('#txtordenvisitai')  : '0';
    var Actc_orden_j  = (Actc_j  === '1') ? toOrder('#txtordenvisitaj')  : '0';
    var Actc_orden_v  = (Actc_v  === '1') ? toOrder('#txtordenvisitav')  : '0';
    var Actc_orden_s  = (Actc_s  === '1') ? toOrder('#txtordenvisitas')  : '0';
    var Actc_orden_d  = (Actc_d  === '1') ? toOrder('#txtordenvisitad')  : '0';

    // Limpia del serialize lo que no quieres enviar "crudo"
    datos = datos.filter(function (f) {
      return f.name !== 'checkdiavisita[]' &&
             f.name !== 'txtordenvisital' &&
             f.name !== 'txtordenvisitam' &&
             f.name !== 'txtordenvisitai' &&
             f.name !== 'txtordenvisitaj' &&
             f.name !== 'txtordenvisitav' &&
             f.name !== 'txtordenvisitas' &&
             f.name !== 'txtordenvisitad';
    });

    // ===== CAMPOS EXTRA (no vienen del serialize) =====
    if (typeof arrg_Credls !== 'undefined' && arrg_Credls && arrg_Credls['NombreRuta']) {
      datos.push({ name: 'Actc_Ruta', value: arrg_Credls['NombreRuta'] });
    }

    // Coordenadas con los nombres esperados en el backend
    datos.push({ name: 'txtlatitudAC',  value: lat });
    datos.push({ name: 'txtlongitudAC', value: lng });
    // (defensivo) también crudos
    datos.push({ name: 'txtlatitud',  value: lat });
    datos.push({ name: 'txtlongitud', value: lng });

    // ===== FRECUENCIA / DÍAS / ÓRDENES NORMALIZADOS (SIEMPRE) =====
    datos.push({ name: 'Actc_frecuencia_visita', value: Actc_frecuencia_visita });

    datos.push({ name: 'Actc_l',  value: Actc_l  });
    datos.push({ name: 'Actc_m',  value: Actc_m  });
    datos.push({ name: 'Actc_mi', value: Actc_mi });
    datos.push({ name: 'Actc_j',  value: Actc_j  });
    datos.push({ name: 'Actc_v',  value: Actc_v  });
    datos.push({ name: 'Actc_s',  value: Actc_s  });
    datos.push({ name: 'Actc_d',  value: Actc_d  });

    datos.push({ name: 'Actc_orden_l',  value: Actc_orden_l  });
    datos.push({ name: 'Actc_orden_m',  value: Actc_orden_m  });
    datos.push({ name: 'Actc_orden_mi', value: Actc_orden_mi });
    datos.push({ name: 'Actc_orden_j',  value: Actc_orden_j  });
    datos.push({ name: 'Actc_orden_v',  value: Actc_orden_v  });
    datos.push({ name: 'Actc_orden_s',  value: Actc_orden_s  });
    datos.push({ name: 'Actc_orden_d',  value: Actc_orden_d  });

    // ===== Campos originales =====
    datos.push({ name: 'Id_Cliente',           value: Id_Cliente });
    datos.push({ name: 'Codigo_Cli',           value: $("#lblcodcli").text() });
    datos.push({ name: 'fechaEnDispositivo',   value: fechaEnDispositivo });
    datos.push({ name: 'Usu_Id',               value: (arrg_Credls && arrg_Credls['us_cod']) ? arrg_Credls['us_cod'] : 0 });

    // Fotos (base64 o null)
    datos.push({ name: 'fotodui',         value: (typeof arrg_fotos !== 'undefined') ? arrg_fotos[2] : null });
    datos.push({ name: 'fotoduitrasera',  value: (typeof arrg_fotos !== 'undefined') ? arrg_fotos[3] : null });
    datos.push({ name: 'fotonrc',         value: (typeof arrg_fotos !== 'undefined') ? arrg_fotos[1] : null });
    datos.push({ name: 'fotonit',         value: (typeof arrg_fotos !== 'undefined') ? arrg_fotos[4] : null });
    datos.push({ name: 'fotonrctrasera',  value: (typeof arrg_fotos !== 'undefined') ? arrg_fotos[5] : null });

    // Estado de cola
    datos.push({ name: 'Actc_cola', value: "NO" });

    // DEBUG útil
    console.log('[procesar_actualizacion] preview ->', {
      ruta: (arrg_Credls && arrg_Credls['NombreRuta']),
      freq: Actc_frecuencia_visita,
      dias: { l:Actc_l, m:Actc_m, mi:Actc_mi, j:Actc_j, v:Actc_v, s:Actc_s, d:Actc_d },
      orden: { l:Actc_orden_l, m:Actc_orden_m, mi:Actc_orden_mi, j:Actc_orden_j, v:Actc_orden_v, s:Actc_orden_s, d:Actc_orden_d },
      lat: lat, lng: lng
    });

    // ===== AJAX =====
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function() {
      $.when( $(".carga-class").stop(true,true).show() ).done(function() {
        $.ajax({
          url      : 'actualizar_datos/ok',
          type     : 'POST',
          dataType : 'JSON',
          data     : datos,
          timeout  : 15000
        })
        .done(function(_resp){
          // opcional: lógica extra
        })
        .always(function(_resp, textStatus) {
          $.when( $(".carga-class").stop(true,true).hide() ).done(function() {
            $.when( $(".carga-esconder").stop(true,true).show() ).done(function() {
              $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function() {
                $.when( $('#InfoCuadro').stop(true,true).show() ).done(function() {

                  if (textStatus === "success") {
                    if (_resp && _resp.rs === true) {
                      Swal.fire({
                        type: 'success',
                        title: 'Registro enviado exitosamente!',
                        showConfirmButton: false,
                        timer: 1500
                      }).then(function() {
                        // limpiar estados
                        Id_Cliente = '';
                        if (typeof arrg_fotos !== 'undefined') {
                          arrg_fotos[1] = arrg_fotos[2] = arrg_fotos[3] = arrg_fotos[4] = arrg_fotos[5] = null;
                        }
                        document.getElementById("form_actualizacion").reset();
                        $("#info_contacto").hide();
                        $(".is-valid").removeClass("is-valid");
                        $("input[name='rdpersoneria']").prop("checked", false);
                        $("input[name='rdcontribuyente']").prop("checked", false);
                        $("#imagen1,#imagen2,#imagen3,#imagen4,#imagen5")
                          .attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");

                        // limpia estados de coordenadas
                        window.EstadoCoordenadas = 0;
                        window.coordenadas_Tempo = ['', ''];

                        // Colapsar/limpiar UI de visita si lo tienes implementado
                        if (typeof collapseAndResetVisitaUI === 'function') {
                          collapseAndResetVisitaUI();
                        }
                      });
                    } else {
                      Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h6>'+((_resp && (_resp.errores||_resp.info)) || 'No se pudo procesar la respuesta')+'</h6>',
                        confirmButtonText:'Ok'
                      });
                    }
                  } else {
                    // Armamos payload simple para cola offline
                    var jsonData = {};
                    $.each(datos, function(index, field) {
                      jsonData[field.name] = (field.name == 'Actc_cola') ? "SI" : field.value;
                    });
                    _ajax_error_(_resp && _resp.status, _resp && _resp.readyState, _resp && _resp.statusText, jsonData);
                  }

                });
              });
            });
          });
        });
      });
    });

  } catch (err) {
    console.error('[procesar_actualizacion] Excepción:', err);
    if (window.Swal) Swal.fire({type:'error', title:'Error inesperado', text:String(err)});
  }
}






// Cierra acordeones y limpia Frecuencia/Días/Órdenes + coords
function collapseAndResetVisitaUI() {
  // Frecuencia
  $('#cbfrecuenciavisita').val('');

  // Días
  const checks = [
    '#checklunes','#checkmartes','#checkmiercoles',
    '#checkjueves','#checkviernes','#checksabado','#checkdomingo'
  ];
  checks.forEach(sel => $(sel).prop('checked', false));

  // Órdenes (ocultar + limpiar + quitar required)
  ['#ord_l','#ord_m','#ord_i','#ord_j','#ord_v','#ord_s','#ord_d'].forEach(sel => $(sel).hide());
  [
    '#txtordenvisital','#txtordenvisitam','#txtordenvisitai',
    '#txtordenvisitaj','#txtordenvisitav','#txtordenvisitas','#txtordenvisitad'
  ].forEach(sel => $(sel).removeAttr('required').val(''));

  // Encabezado
  $('#orden_visita_header').hide();

  // Cierra cualquier collapse abierto (Bootstrap)
  $('.collapse.show').collapse('hide');

  // Oculta widget coordenadas
  $('#switch-two').prop('checked', false);
  $('#Div_Coordendads').hide();

  // Subir al inicio
  $('html, body').animate({ scrollTop: 0 }, 300);
}

// Al volver con botón “Atrás”, forzar estado colapsado
window.addEventListener('pageshow', function () {
  $('.collapse.show').removeClass('show');
  ['#ord_l','#ord_m','#ord_i','#ord_j','#ord_v','#ord_s','#ord_d'].forEach(sel => $(sel).hide());
  $('#orden_visita_header').hide();
});




function gurdarEnCola(dataJson){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_act_cliente', "readwrite");
    var object = data.objectStore('tbl_act_cliente');
    var request = object.put(dataJson);
    request.onerror = function (e) {
        Swal.fire({
            type: 'error',
            title: 'Error al guardar temporalmente!',
            showConfirmButton: false,
            timer: 1500
        });
    };
    data.oncomplete = function (e) {
        // DB_CantidadEnCola('tbl_clitemporales');
        Swal.fire({
            type: 'info',
            title: 'Registro guardado temporalmente!',
            showConfirmButton: false,
            timer: 1500
        }).then((result) => {
            Promise.all([
                ConsultarCola()
            ])
            .then(respuestas => {
                Id_Cliente = '';
                arrg_fotos[1] = null;
                arrg_fotos[2] = null;
                arrg_fotos[3] = null;
                arrg_fotos[4] = null;
                arrg_fotos[5] = null;
                document.getElementById("form_actualizacion").reset();
                $('input.checkbox').prop('checked',false);
                $("#info_contacto").hide();
                $("#imagen1").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                $("#imagen2").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                $("#imagen3").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                $("#imagen4").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                $("#imagen5").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            })
            .catch(error => {
            });
        });
    };
}
function ConsultarCola() {
    var cant = 0;
    return new Promise(function (resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_act_cliente', 'readonly'),
            store = transaccion.objectStore('tbl_act_cliente'),
            indice = store.index('by_Actc_cola'),
            cursor = indice.openCursor('SI')
        cursor.onsuccess = function (event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                arrgColaE = dataResult;
                cant += parseInt(Object.keys(dataResult).length);
                $("#RegisCola").text(cant);
                resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
function _ajax_error_(jqXHR, textStatus, errorThrown, data) {
    if (textStatus === 'timeout') {
        gurdarEnCola(data);
        return;
    } else if (jqXHR === 0) {
        gurdarEnCola(data);
        return;
    } else if (jqXHR === 200) {
        gurdarEnCola(data);
        return;
    } else if (jqXHR == 404) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>Página solicitada no encontrada[404]</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    } else if (jqXHR == 500) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>Error de servidor interno [500].</h3>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            gurdarEnCola(data);
        });
        return;
    } else if (textStatus === 'parsererror') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    } else if (textStatus === 'abort') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    } else {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>Error desconocido, por favor contactar con Sistemas de Venta...</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    }
}

function enviar_actualizacion(){
    if(_empty(Id_Cliente)){
        Swal.fire({
            title: '<strong>Aviso!</strong>',
            type: 'info',
            html:'<strong>Selecciona un cliente por favor</strong>',
            confirmButtonText:'Ok'
        });
    }else{
        arrg_vali_result = [];

        $("#error-mjs-9").html('');
        $("#error-mjs-21").html('');
        $("#error-mjs-22").html('');

        $("#file1").removeClass("is-invalid").addClass("is-valid");
        $("#file5").removeClass("is-invalid").addClass("is-valid");
        $(".GR_CheckRadioEC").removeClass("is-invalid").addClass("is-valid");

        procesar_actualizacion();
    }
}

/*
function enviar_actualizacion(){
    var detalle_validacion = ``;
    if(_empty(Id_Cliente)){
        Swal.fire({
            title: '<strong>Aviso!</strong>',
            type: 'info',
            html:'<strong>Selecciona un cliente por favor</strong>',
            confirmButtonText:'Ok'
        });
    }else{
        if(validacion_form_actu() == 16){
            procesar_actualizacion();
        }else{
            arrg_vali_result.forEach( function(valor, indice, array) {
                if(!_empty(valor)){
                    detalle_validacion += `<p>${valor}</p>`;
                }else{}
            });
            Swal.fire({
                title: '<strong>Atención!</strong>',
                type: 'warning',
                html:detalle_validacion,
                confirmButtonText:'Ok'
            });
        }
    }
}

*/
function agregar_us_offline(conect){
    var TotalRegisCola = 0;
    TotalRegisCola = $("#RegisCola").text();
    if(TotalRegisCola>0){
        Swal.fire({
            title: 'Deseas enviar los registros en cola?',
            text: "",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, enviar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                var active = dataBaseAppSDV.result;
                var dataResult = [];
                let transaccion = active.transaction('tbl_act_cliente', 'readonly'),
                    store = transaccion.objectStore('tbl_act_cliente'),
                    indice = store.index('by_Actc_cola'),
                    cursor = indice.openCursor('SI')
                cursor.onsuccess = function (event) {
                    let dat = event.target.result;
                    if (dat) {
                        dataResult.push(dat.value);
                        dat.continue();
                    } else {
                        arreg_offline = dataResult;
                    };
                };
                transaccion.oncomplete = function () {
                    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                            enviar_regis_offline(0,arreg_offline);
                        });
                    });  
                };
                transaccion.onerror = function () {
                    reject(0);
                };
            }
        });
    }else{
        Swal.fire({
            type: 'info',
            title: 'No tienes registros en cola!',
            showConfirmButton: false,
            timer: 1500
        });
    }
}
function enviar_regis_offline(indice,elements){
    if(indice < elements.length){
        $.ajax({
            url:'actualizar_datos/ok',
            type:"POST",
            data:elements[indice],
            dataType: "JSON",
            timeout:34777
            }).done(function(_resp) {
            }).always(function(_resp, textStatus, errorThrown) {
                if (textStatus == "success") {
                    if(_resp.rs == true){
                        Promise.all([
                            CambiaEstadoCola(arreg_offline,indice)
                        ])
                        .then(respuestas => {
                            enviar_regis_offline(indice + 1,arreg_offline);
                        })
                        .catch(error => {
                            console.log('ERROR EN ENVIAR CONSUSLTAR COLAS');
                        });
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                                Swal.fire({
                                    title: 'Aviso!',
                                    type: 'error',
                                    html:'Error inesperado, por favor informar a Sistema de Venta [ Error envio cola ]',
                                    confirmButtonText:'Ok'
                                });
                            });
                        });
                    }
                }else{
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                            _ajax_error_envioOffline(_resp.status,_resp.readyState,_resp.statusText);
                        });
                    });
                } 
            });
    }else{
        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
            $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                ConsultarCola();
            });
        });
    }
}
function CambiaEstadoCola(arrgdata,indice){
    var actived = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_act_cliente"], "readwrite").objectStore("tbl_act_cliente");
    var request = objectStore.get(arrgdata[indice].idx);
    request.onerror = function (event) {
    };
    request.onsuccess = function (event) {
        var data = request.result;
        data.Actc_cola = 'NO';
        var requestUpdate = objectStore.put(data);
        requestUpdate.onerror = function (event) {
        };
        requestUpdate.onsuccess = function (event) {
            alertify.success('Registro enviado exitosamente!');
            ConsultarCola();
        };
    };
}