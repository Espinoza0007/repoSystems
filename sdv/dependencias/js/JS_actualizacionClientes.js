var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
var warn_on_unload = '';
var map;
var marker;
var blockFCli=0;
var blockF=0;
var coordenadas_Tempo = new Array();
var arrg_vali_result = new Array();
var datas = '';
var Id_Cliente = '';var EstadoCoordenadas = 0;
window.onbeforeunload = function() {
    if(warn_on_unload != ''){
        return warn_on_unload;
    }
}


$( document ).ready(function() {
    DB_IniciarCPSesion(1)
    $(document).on("change", "#switch_estado", function() {
        var Is_Cheked = document.getElementById('switch_estado').checked;
        if (Is_Cheked){
            $("#conten_Si_No").show();
            $("#cli_inactivado").hide();
            $('#motivo_inactivo').removeClass();
            $('#motivo_inactivo').addClass('custom-select');
        }else{
            $("#conten_Si_No").hide();
            $("#cli_inactivado").show();
            $('#motivo_inactivo').removeClass();
            $('#motivo_inactivo').addClass('custom-select');
        }
    });
    init('13.685147','-89.147116','BOCADELI');
    $(document).on("change", "#switch-two", function() {
        var Is_Cheked = document.getElementById('switch-two').checked;
        var v_log_lag = 0;
        var latitud = 0,longitud = 0;
        latitud = coordenadas_Tempo[0];
        longitud = coordenadas_Tempo[1];
        if(V_CoordenadasLL(latitud)){
            latitud = latitud;
        }else{
            latitud = 0;
            v_log_lag += 1;
        }
        if(V_CoordenadasLL(longitud)){
            longitud = longitud;
        }else{
            longitud = 0;
            v_log_lag += 1;
        }
        if( v_log_lag > 0 ){
            latitud = 0,longitud = 0;
        }
        $("#content-map").empty();
        $("#content-map").html('<div class="col-md-12 divrow"><div id="map" style="height: 277px;width: 100%;"></div></div>');
        if (Is_Cheked){
            $.when( $("#Div_Coordendads").stop(true,true).show() ).done(function( x ) {
                init(latitud,longitud,coordenadas_Tempo[2]);
            });
        }else{
            $.when( $("#Div_Coordendads").stop(true,true).hide() ).done(function( x ) {
                $("#txtlatitudm").val(latitud);
                $("#txtlongitudm").val(longitud);
                $("#txtlatitud").val(latitud);
                $("#txtlongitud").val(longitud);
            });
        }
    });
    $(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";
        $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
             $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            });
         });
    });
    $('#DgrTableSN tbody').on( 'click', 'tr', function () {    
        $('#motivo_inactivo').val("");
        $('#motivo_inactivo').removeClass();
        $('#motivo_inactivo').addClass('custom-select');
        $("#cbmunicipio").val("");
        $("#content-map").empty();
        $("#content-map").html('<div class="col-md-12 divrow"><div id="map" style="height: 277px;width: 100%;"></div></div>');
        $.when( $("#Div_Coordendads").stop(true,true).hide() ).done(function( x ) {
        });
        if(blockFCli==0){
            EstadoCoordenadas = 0;
            var html_dias = ``;
            var departamento_val = '';
            var municipio_val = '';
            var dui_val = '';
            var numregistro_val = '';
            var nit_val = '';
            var frecuencia_val = '';
            var estado_w_val = '';
            var tipofacturacion_val = '';
            var telefono_val = '';
            var Formato_Tel_Val = '';
            var Formato_Dui_Val = '';
            var Formato_NumeRe_Val = '';
            var Formato_Nit_Val = '';
            var Cantidad_CMR = '';
            var tipopventa_val = '';
            var gironegocio_val = '';
            Id_Cliente = '';
            blockFCli=1000;
            coordenadas_Tempo = new Array();
            telefono_val = table.row( this ).data().Cli_telefono;
            $(this).addClass("SeletedTRSN");
            $("#lblcodcli").text(table.row( this ).data().Cli_codigo);
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            var fecha_formateada = '';
            if(!_empty(table.row( this ).data().Cli_ul_fecha_ac_cliente)){
                var fecha = new Date(table.row( this ).data().Cli_ul_fecha_ac_cliente);
                fecha_formateada = fecha.toLocaleDateString("es-ES", options);
            }else{
                fecha_formateada = 'SIN ACTUALIZAR';
            }
            var v_log_lag = 0;
            var latitud = 0,longitud = 0;
            latitud = table.row( this ).data().Cli_latitud;
            longitud = table.row( this ).data().Cli_longitud;
            if(V_CoordenadasLL(latitud)){
                latitud = latitud;
            }else{
                latitud = 0;
                v_log_lag += 1;
            }
            if(V_CoordenadasLL(longitud)){
                longitud = longitud;
            }else{
                longitud = 0;
                v_log_lag += 1;
            }
            if( v_log_lag > 0 ){
                latitud = 0,longitud = 0;
            }
            $("#lbl_fultima").text(fecha_formateada);
      
            $("#txtnombre").val(table.row(this).data().Cli_nombre);
           // bloquearSiTieneValor("#txtnombre");

            $("#txtdireccion").val(table.row( this ).data().Cli_direccion);
            $("#txtcontacto").val(table.row( this ).data().Cli_contacto);
            $("#txtnumtelefono").val(telefono_val).mask(arrg_Credls['FormatoTelefono']);
            $("#txtlatitudm").val(latitud);
            $("#txtlongitudm").val(longitud);
            $("#txtlatitud").val(latitud);
            $("#txtlongitud").val(longitud);
            $("#span_nombre").val(table.row( this ).data().Cli_nombre);
            $("#span_contacto").val(table.row( this ).data().Cli_contacto);
            $("#span_direccion").val(table.row( this ).data().Cli_direccion);
            $("#span_telefono").val(telefono_val);
            $("#cbrefrigerantes").val(table.row( this ).data().Cli_cantidad_CMR);
            Id_Cliente = table.row( this ).data().Cli_Id;
            departamento_val = table.row( this ).data().Dep_descripcion;
            municipio_val = table.row( this ).data().Mun_descripcion;
            departamento_val = String(departamento_val);
            municipio_val = String(municipio_val);
            frecuencia_val = table.row( this ).data().Cli_frecuencia_visita;
            frecuencia_val = String(frecuencia_val);
            dui_val = table.row( this ).data().Cli_dui;
            numregistro_val = table.row( this ).data().Cli_num_registro;
            nit_val = table.row( this ).data().Cli_nit;
            dui_val = String(dui_val);
            numregistro_val = String(numregistro_val);
            nit_val = String(nit_val);
            dui_val = dui_val.replace(/-/g, "");
            numregistro_val = numregistro_val.replace("-", "");
            nit_val = nit_val.replace(/-/g, "");
            estado_w_val = table.row( this ).data().Cli_estado;
            tipofacturacion_val = table.row( this ).data().Tfc_descripcion;
            tipofacturacion_val = String(tipofacturacion_val);
            tipopventa_val = table.row( this ).data().Tpv_descripcion;
            tipopventa_val = String(tipopventa_val);
            gironegocio_val = table.row( this ).data().Gir_descripcion;
            gironegocio_val = String(gironegocio_val);
            $('#lblcodcli').removeClass();
            if(estado_w_val == 1){
                $('#lblcodcli').addClass('badge badge-success');
                $("#switch_estado").click();
                $("#conten_Si_No").show();
                $("#cli_inactivado").hide();
            }else{
                $('#lblcodcli').addClass('badge badge-danger');
                $("#conten_Si_No").hide();
                $("#cli_inactivado").show();
                $("#motivo_inactivo").val(table.row( this ).data().Motivos);
            }
            if(isNaN(dui_val)){
                dui_val = '';
            }
            if(isNaN(numregistro_val)){
                numregistro_val = '';
            }
            if(isNaN(nit_val)){
                nit_val = '';
            }
            if(departamento_val === 'NA'){
                departamento_val = '';
                municipio_val = '';
            }
            if((tipopventa_val === 'NA') || (tipopventa_val === null)){
                tipopventa_val = '';
                gironegocio_val = '';
            }
            DB_CargarFiltro('tbl_departamento','cbdepartamento','c-departamento',departamento_val,'Departamento',4);
            DB_CargarFiltro('tbl_tfacturacion','cbtfacturacion','c-tfacturacion',tipofacturacion_val,'Tipo de facturaci&oacute;n',14);
            DB_CargarFiltro('tbl_tpuntoventa','cbtpuntoventa','c-tpuntoventa',tipopventa_val,'Tipo punto de venta',24);
            if(tipofacturacion_val === 'CREDITO FISCAL'){
                $('#if-tfactura').show();
                $('#div_dui').show();
                $('#div_numregistro').show();
                $('#div_nit').show();
            }else{
                if(arrg_Credls['pais'] == 'EL SALVADOR'){
                    $('#if-tfactura').hide();
                    $('#div_dui').hide();
                    $('#div_numregistro').hide();
                    $('#div_nit').hide();
                }else if(arrg_Credls['pais'] == 'GUATEMALA'){
                    $('#if-tfactura').hide();
                    $('#div_dui').hide();
                    $('#div_numregistro').hide();
                    $('#div_nit').hide();
                }else if(arrg_Credls['pais'] == 'HONDURAS'){
                    $('#if-tfactura').show();
                    $('#div_dui').show();
                    $('#div_numregistro').hide();
                    $('#div_nit').show();
                }
            }
            obtener_municipios(departamento_val,municipio_val);
            obtener_giros_negocios(tipopventa_val,gironegocio_val);
            $("#txtdui").val(dui_val).mask(arrg_Credls['FormatoNumIP']);
            $("#txtnumcontribuyente").val(numregistro_val);
            if(arrg_Credls['pais'] == 'GUATEMALA'){
            }else{
                $("#txtnit").val(nit_val).mask(arrg_Credls['FormatoNumNIT']);
            }
            $("#cbfrecuenciavisita").val(frecuencia_val);
            coordenadas_Tempo[0] = latitud;
            coordenadas_Tempo[1] = longitud;
            coordenadas_Tempo[2] = table.row( this ).data().Cli_direccion;
            arrg_checqueados = [];
            var style_l='',style_m='',style_i='',style_j='',style_v='',style_s='',style_d='';
            if(table.row( this ).data().Cli_l == 1){
                arrg_checqueados['lcheck'] = `checked="checked"`;
                style_l = 'margin-top:7px;display:;';
            }else{
                arrg_checqueados['lcheck'] = '';
                style_l = 'margin-top:7px;display:none;';
            }
            if(table.row( this ).data().Cli_m == 1){
                arrg_checqueados['mcheck'] = `checked="checked"`;
                style_m = 'margin-top:7px;display:;';
            }else{
                arrg_checqueados['mcheck'] = '';
                style_m = 'margin-top:7px;display:none;';
            }
            if(table.row( this ).data().Cli_mi == 1){
                arrg_checqueados['icheck'] = `checked="checked"`;
                style_i = 'margin-top:7px;display:;';
            }else{
                arrg_checqueados['icheck'] = '';
                style_i = 'margin-top:7px;display:none;';
            }
            if(table.row( this ).data().Cli_j == 1){
                arrg_checqueados['jcheck'] = `checked="checked"`;
                style_j = 'margin-top:7px;display:;';
            }else{
                arrg_checqueados['jcheck'] = '';
                style_j = 'margin-top:7px;display:none;';
            }
            if(table.row( this ).data().Cli_v == 1){
                arrg_checqueados['vcheck'] = `checked="checked"`;
                style_v = 'margin-top:7px;display:;';
            }else{
                arrg_checqueados['vcheck'] = '';
                style_v = 'margin-top:7px;display:none;';
            }
            if(table.row( this ).data().Cli_s == 1){
                arrg_checqueados['scheck'] = `checked="checked"`;
                style_s = 'margin-top:7px;display:;';
            }else{
                arrg_checqueados['scheck'] = '';
                style_s = 'margin-top:7px;display:none;';
            }
            if(table.row( this ).data().Cli_d == 1){
                arrg_checqueados['dcheck'] = `checked="checked"`;
                style_d = 'margin-top:7px;display:;';
            }else{
                arrg_checqueados['dcheck'] = '';
                style_d = 'margin-top:7px;display:none;';
            }
            var l=0,m=0,i=0,j=0,v=0,s=0,d=0;
            if(_empty(table.row( this ).data().Cli_orden_l)){
                l='';
            }else{
                l = table.row( this ).data().Cli_orden_l;
            }
            if(_empty(table.row( this ).data().Cli_orden_m)){
                m='';
            }else{
                m = table.row( this ).data().Cli_orden_m;
            }
            if(_empty(table.row( this ).data().Cli_orden_mi)){
                i='';
            }else{
                i = table.row( this ).data().Cli_orden_mi;
            }
            if(_empty(table.row( this ).data().Cli_orden_j)){
                j='';
            }else{
                j = table.row( this ).data().Cli_orden_j;
            }
            if(_empty(table.row( this ).data().Cli_orden_v)){
                v='';
            }else{
                v = table.row( this ).data().Cli_orden_v;
            }
            if(_empty(table.row( this ).data().Cli_orden_s)){
                s='';
            }else{
                s = table.row( this ).data().Cli_orden_s;
            }
            if(_empty(table.row( this ).data().Cli_orden_d)){
                d='';
            }else{
                d = table.row( this ).data().Cli_orden_d;
            }
            html_dias+=`
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input GR_Check" id="checklunes" name="checkdiavisita[]" value='L_1' ${arrg_checqueados['lcheck']}>
                    <label class="custom-control-label" for="checklunes">LUNES</label>
                </div>
                <div style="${style_l}" id="ord_l">
                    <label>Orden De Visita Lunes:</label>
                    <input type="number" name="txtordenvisital" id="txtordenvisital" class="form-control outlinenone" placeholder="Orden de visita..." value="${l}" min="0" max="90" step="1">
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-16">
                    </div>
                    <!-- <hr style="wid:100%;"> -->
                    <hr class="separador">
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input GR_Check" id="checkmartes" name="checkdiavisita[]" value='M_1' ${arrg_checqueados['mcheck']}>
                    <label class="custom-control-label" for="checkmartes">MARTES</label>
                </div>
                <div style="${style_m}" id="ord_m">
                    <label>Orden De Visita Martes:</label>
                    <input type="number" name="txtordenvisitam" id="txtordenvisitam" class="form-control outlinenone" placeholder="Orden de visita..." value="${m}" min="0" max="90" step="1">
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-17">
                    </div>
                    <hr class="separador">
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input GR_Check" id="checkmiercoles" name="checkdiavisita[]" value='I_1' ${arrg_checqueados['icheck']}>
                    <label class="custom-control-label" for="checkmiercoles">MI&Eacute;RCOLES</label>
                </div>
                <div style="${style_i}" id="ord_i">
                    <label>Orden De Visita Miércoles:</label>
                    <input type="number" name="txtordenvisitai" id="txtordenvisitai" class="form-control outlinenone" placeholder="Orden de visita..." value="${i}" min="0" max="90" step="1">
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-18">
                    </div>
                    <hr class="separador">
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input GR_Check" id="checkjueves" name="checkdiavisita[]" value='J_1' ${arrg_checqueados['jcheck']}>
                    <label class="custom-control-label" for="checkjueves">JUEVES</label>
                </div>
                <div style="${style_j}" id="ord_j">
                    <label>Orden De Visita Jueves:</label>
                    <input type="number" name="txtordenvisitaj" id="txtordenvisitaj" class="form-control outlinenone" placeholder="Orden de visita..." value="${j}" min="0" max="90" step="1">
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-19">
                    </div>
                    <hr class="separador">
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input GR_Check" id="checkviernes" name="checkdiavisita[]" value='V_1' ${arrg_checqueados['vcheck']}>
                    <label class="custom-control-label" for="checkviernes">VIERNES</label>
                </div>
                <div style="${style_v}" id="ord_v">
                    <label>Orden De Visita Viernes:</label>
                    <input type="number" name="txtordenvisitav" id="txtordenvisitav" class="form-control outlinenone" placeholder="Orden de visita..." value="${v}" min="0" max="90" step="1">
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-20">
                    </div>
                    <hr class="separador">
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input GR_Check" id="checksabado" name="checkdiavisita[]" value='S_1' ${arrg_checqueados['scheck']}>
                    <label class="custom-control-label" for="checksabado">SABADO</label>
                </div>
                <div style="${style_s}" id="ord_s">
                    <label>Orden De Visita Sabado:</label>
                    <input type="number" name="txtordenvisitas" id="txtordenvisitas" class="form-control outlinenone" placeholder="Orden de visita..." value="${s}" min="0" max="90" step="1">
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-21">
                    </div>
                    <hr class="separador">
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input GR_Check" id="checkdomingo" name="checkdiavisita[]" value='D_1' ${arrg_checqueados['dcheck']}>
                    <label class="custom-control-label" for="checkdomingo">DOMINGO</label>
                </div>
                <div style="${style_d}" id="ord_d">
                    <label>Orden De Visita Domingo:</label>
                    <input type="number" name="txtordenvisitad" id="txtordenvisitad" class="form-control outlinenone" placeholder="Orden de visita..." value="${d}" min="0" max="90" step="1">
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-22">
                    </div>
                    <hr class="separador">
                </div>`;
            $("#div_diasVisita").empty().html(html_dias);
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
                        console.log('estado => '+ vall);
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
    $('#c-departamento').on('change','#cbdepartamento',function(){
        warn_on_unload = 'no salir';
        var txtdepartamento = '';
        $("#c-municipio").empty();
        V_Selec($("#cbdepartamento").val(),'cbdepartamento',2,'Departamento');
        txtdepartamento = $('select[name="cbdepartamento"] option:selected').text();
        console.log(txtdepartamento);
        if(!_empty(txtdepartamento)){
            obtener_municipios(txtdepartamento,'');
        }else{
            $('#if-departamento').hide();
        }
    });
    $('#c-tpuntoventa').on('change','#cbtpuntoventa',function(){
        warn_on_unload = 'no salir';
        var txttipopuntoventa = '';
        $("#c-gironegocio").empty();
        V_Selec($("#cbtpuntoventa").val(),'cbtpuntoventa',24,'Tipo punto de venta');
        txttipopuntoventa = $('select[name="cbtpuntoventa"] option:selected').text();
        console.log(txttipopuntoventa);
        if(!_empty(txttipopuntoventa)){
            obtener_giros_negocios(txttipopuntoventa,'');
        }else{
            // $('#if-departamento').hide();
        }
    });
    $('#c-gironegocio').on('change','#cbgironegocio',function(){
        V_Selec($("#cbgironegocio").val(),'cbgironegocio',25,'Giro de negocio');
        warn_on_unload = 'no salir';
    });
    $('#c-tfacturacion').on('change','#cbtfacturacion',function(){
        warn_on_unload = 'no salir';
        var txtcbfacturacion = '';
        txtcbfacturacion = $('select[name="cbtfacturacion"] option:selected').text();
        V_Selec($("#cbtfacturacion").val(),'cbtfacturacion',14,'Tipo de facturaci&oacute;n');
        if(txtcbfacturacion === 'CREDITO FISCAL'){
            $('#if-tfactura').show();
            $('#div_dui').show();
            $('#div_numregistro').show();
            $('#div_nit').show();
        }else{
            if(arrg_Credls['pais'] == 'EL SALVADOR'){
                $('#if-tfactura').hide();
                $('#div_dui').hide();
                $('#div_numregistro').hide();
                $('#div_nit').hide();
            }else if(arrg_Credls['pais'] == 'GUATEMALA'){
                $('#if-tfactura').hide();
                $('#div_dui').hide();
                $('#div_numregistro').hide();
                $('#div_nit').hide();
            }else if(arrg_Credls['pais'] == 'HONDURAS'){
                $('#if-tfactura').show();
                $('#div_dui').show();
                $('#div_numregistro').hide();
                $('#div_nit').show();
            }
        }
    });
    $('#c-municipio').on('change','#cbmunicipio',function(){
        V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
        warn_on_unload = 'no salir';
    });
    $("#txtnombre").keyup(function() {
        V_Text_LetraNumero($("#txtnombre").val(),'txtnombre',0,'Nombre del establecimiento');
        warn_on_unload = 'no salir';
    });
    $("#txtdireccion").keyup(function() {
        V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
        warn_on_unload = 'no salir';  
    });
    $('#S_filtroMotivosR').on('change','#motivo_inactivo',function(){
        V_Selec($("#motivo_inactivo").val(),'motivo_inactivo',77,'Motivo');
        warn_on_unload = 'no salir';  
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
    $("#txtnumtelefono").keyup(function() {
        var CantidTelefonor = 0;var CantidTelefonot = 0;
        CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguion($("#txtnumtelefono").val(),'txtnumtelefono',5,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot);
        warn_on_unload = 'no salir';
    });
    $("#txtcontacto").keyup(function() {
        V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',4,'Nombre de contacto');
        warn_on_unload = 'no salir'; 
    });
    $("#txtdui").keyup(function() {
        warn_on_unload = 'no salir';
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguion($("#txtdui").val(),'txtdui',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
    });
    $("#txtnumcontribuyente").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero($("#txtnumcontribuyente").val(),'txtnumcontribuyente',8,'N&uacute;mero de contribuyente');
    });
    $("#txtnit").keyup(function() {
        warn_on_unload = 'no salir';
        if(arrg_Credls['pais'] == 'GUATEMALA'){
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            V_numeconMaskguionGT($("#txtnit").val(),'txtnit',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }else{
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            V_numeconMaskguion($("#txtnit").val(),'txtnit',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }
    });
    $('#cbfrecuenciavisita').on('change',function(){
        warn_on_unload = 'no salir';
        V_Selec($("#cbfrecuenciavisita").val(),'cbfrecuenciavisita',10,'Frecuencia de visita');
    });
    $('#div_diasVisita').on('change','.GR_Check',function(){
        warn_on_unload = 'no salir';
        V_checks(6,'D&iacute;a de visita');
        var dia_se ='',estado_se=null;
        dia_se = this.value,estado_se=this.checked;
        if(dia_se == 'L_1' && estado_se == true){
            $("#ord_l").show();
            V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',16,'Orden de visita Lunes');
        } else if (dia_se == 'L_1' && estado_se == false) {
            $("#ord_l").hide();
        }
        if(dia_se == 'M_1' && estado_se == true){
            $("#ord_m").show();
            V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',17,'Orden de visita Martes');
        } else if (dia_se == 'M_1' && estado_se == false) {
            $("#ord_m").hide();
        }
        if(dia_se == 'I_1' && estado_se == true){
            $("#ord_i").show();
            V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',18,'Orden de visita Miércoles');
        } else if (dia_se == 'I_1' && estado_se == false) {
            $("#ord_i").hide();
        }
        if(dia_se == 'J_1' && estado_se == true){
            $("#ord_j").show();
            V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',19,'Orden de visita Jueves');
        } else if (dia_se == 'J_1' && estado_se == false) {
            $("#ord_j").hide();
        }
        if(dia_se == 'V_1' && estado_se == true){
            $("#ord_v").show();
            V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',20,'Orden de visita Viernes');
        } else if (dia_se == 'V_1' && estado_se == false) {
            $("#ord_v").hide();
        }
        if(dia_se == 'S_1' && estado_se == true){
            $("#ord_s").show();
            V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',21,'Orden de visita Sábado');
        } else if (dia_se == 'S_1' && estado_se == false) {
            $("#ord_s").hide();
        }
        if(dia_se == 'D_1' && estado_se == true){
            $("#ord_d").show();
            V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',22,'Orden de visita Domingo');
        } else if (dia_se == 'D_1' && estado_se == false) {
            $("#ord_d").hide();
        }
    });
    $('#div_diasVisita').on('keyup','#txtordenvisital',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',16,'Orden de visita Lunes');
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitam',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',17,'Orden de visita Martes');
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitai',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',18,'Orden de visita Miércoles');
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitaj',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',19,'Orden de visita Jueves');
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitav',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',20,'Orden de visita Viernes');
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitas',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',21,'Orden de visita Sábado');
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitad',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',22,'Orden de visita Domingo');
    });
    $('#cbrefrigerantes').on('change',function(){
        warn_on_unload = 'no salir';
        V_Selec($("#cbrefrigerantes").val(),'cbrefrigerantes',23,'Capacidad del negocio');
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
            init($("#txtlatitud").val(),$("#txtlongitud").val(),$("#txtdireccion").val());
            validacion_form_actu();
        });
    });
}
function init(latitud,longitud,direccion) {
    var v_log_lag = 0;
    if(V_CoordenadasLL(latitud)){
        latitud = latitud;
    }else{
        latitud = 0;
        v_log_lag += 1;
    }
    if(V_CoordenadasLL(longitud)){
        longitud = longitud;
    }else{
        longitud = 0;
        v_log_lag += 1;
    }
    if( v_log_lag > 0 ){
        latitud = 0,longitud = 0;
    }
    // $("#txtlatitud").val();
    // $("#txtlongitud").val();
    map = new L.Map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(map);
    map.attributionControl.setPrefix('SDV Bocadeli');
    map.setView(new L.LatLng(latitud, longitud),18);
    var circle = L.circle([latitud, longitud], {
        color: '#3ACA31',
        fillColor: '#51DF48',
        fillOpacity: 0.5,
        radius: 50
    }).addTo(map).bindPopup('<strong>'+direccion+'</strong>').openPopup();

    V_CoordenadasLL_ContarOK($("#txtlatitud").val(),'txtlatitudm',12,'Latitud');
    V_CoordenadasLL_ContarOK($("#txtlongitud").val(),'txtlongitudm',13,'Longitud');
}
function onLocationFound(e) {
    var radius = e.accuracy / 2;
    var location = e.latlng;
    var greenIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    if (marker != undefined) {
        map.removeLayer(marker);
    }
    marker = new L.Marker(e.latlng, {draggable:false});
    map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng),18);
    map.addLayer(marker);
    
    // document.getElementById('latitud').value = e.latlng.lat;
    // document.getElementById('longitud').value = e.latlng.lng;
    $("#txtlatitud").val(e.latlng.lat);
    $("#txtlatitudm").val(e.latlng.lat);
    $("#txtlongitud").val(e.latlng.lng);
    $("#txtlongitudm").val( e.latlng.lng);

    V_CoordenadasLL_ContarOK($("#txtlatitud").val(),'txtlatitudm',12,'Latitud');
    V_CoordenadasLL_ContarOK($("#txtlongitud").val(),'txtlongitudm',13,'Longitud');
    EstadoCoordenadas = 1;
}
function onLocationError(e) {
    // alert(e.message);
    $("#txtlatitud").val(0);
    $("#txtlatitudm").val(0);
    $("#txtlongitud").val(0);
    $("#txtlongitudm").val(0);
    V_CoordenadasLL_ContarOK($("#txtlatitud").val(),'txtlatitudm',12,'Latitud');
    V_CoordenadasLL_ContarOK($("#txtlongitud").val(),'txtlongitudm',13,'Longitud');
    Swal.fire({
        type: 'info',
        title: 'GPS apagado o geolocalización bloqueada',
        html:'<p>Por favor ver el tutorial para desbloquear la geolocalización</p>',
        showConfirmButton: true,
        confirmButtonText:'Ok'
    }).then((result) => {
        if(result.value){
            $("#ModalTutorial").modal("show");
            $("#imgtutorial").attr("src","../dependencias/imagenes/Geo_BloqueadaV2.gif");
        }else{

        }
    });
}
function getLocationLeaflet() {
    map.on('locationfound', onLocationFound);
    map.on('locationerror', onLocationError);
    map.locate({setView: true, maxZoom: 15});
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
                                console.log('ERROR CAMPOS');
                                Swal.fire({
                                    title: 'Aviso!',
                                    type: 'error',
                                    html:'<h5>'+_resp.errores+'</h5>',
                                    confirmButtonText:'Ok'
                                });
                            }
                        }else{
                            _ajax_error_validacion_loglat(_resp.status,_resp.readyState,_resp.statusText);
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
                console.log(valcantit);
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
    // console.log(campo+' '+data);
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
function validacion_form_actu(){
    var contarok = 0;
    var txtcbfacturacion = '';
    txtcbfacturacion = $('select[name="cbtfacturacion"] option:selected').text();
    contarok +=V_Text_LetraNumero($("#txtnombre").val(),'txtnombre',0,'Nombre del establecimiento');
    contarok +=V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
    contarok +=V_Selec($("#cbdepartamento").val(),'cbdepartamento',2,'Departamento');
    contarok +=V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
    contarok +=V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',4,'Nombre de contacto');
    var CantidTelefonor = 0;var CantidTelefonot = 0;
    CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
    contarok +=V_numeconMaskguion($("#txtnumtelefono").val(),'txtnumtelefono',5,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot);
    contarok +=V_checks(6,'D&iacute;a de visita');
    if(txtcbfacturacion === 'CREDITO FISCAL'){
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        contarok +=V_numeconMaskguion($("#txtdui").val(),'txtdui',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
        contarok +=V_NumeroEntero($("#txtnumcontribuyente").val(),'txtnumcontribuyente',8,'N&uacute;mero de contribuyente');
        if(arrg_Credls['pais'] == 'GUATEMALA'){
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguionGT($("#txtnit").val(),'txtnit',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }else{
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguion($("#txtnit").val(),'txtnit',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }
    }else{
        if(arrg_Credls['pais'] == 'EL SALVADOR'){
            arrg_vali_result[7] = '';
            arrg_vali_result[8] = '';
            arrg_vali_result[9] = '';
            contarok +=3;
        }else if(arrg_Credls['pais'] == 'GUATEMALA'){
            arrg_vali_result[7] = '';
            arrg_vali_result[8] = '';
            arrg_vali_result[9] = '';
            contarok +=3;
        }else if(arrg_Credls['pais'] == 'HONDURAS'){
            arrg_vali_result[8] = '';
            var CantidNumIPr = 0;var CantidNumIPt = 0;
            CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
            var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
            contarok +=V_numeconMaskguion($("#txtdui").val(),'txtdui',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguion($("#txtnit").val(),'txtnit',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
            contarok +=1;
        }
    }
    contarok +=V_Selec($("#cbfrecuenciavisita").val(),'cbfrecuenciavisita',10,'Frecuencia de visita');
    var Is_Cheked = document.getElementById('switch-two').checked;
    if (Is_Cheked){
        contarok +=V_CoordenadasLL_ContarOK($("#txtlatitud").val(),'txtlatitudm',12,'Latitud');
        contarok +=V_CoordenadasLL_ContarOK($("#txtlongitud").val(),'txtlongitudm',13,'Longitud');
        contarok++;
        arrg_vali_result[15] = '';
    }else{
        arrg_vali_result[12] = '';
        arrg_vali_result[13] = '';
        var contalatilongi = 0;
        if($("#txtlatitud").val() == 0){
            contalatilongi = 0;
        }else{
            contalatilongi++;
        }
        if($("#txtlongitud").val() == 0){
            contalatilongi = 0;
        }else{
            contalatilongi++;
        }
        if(contalatilongi == 2){
            contarok+=1;
            arrg_vali_result[15] = '';
        }else{
            arrg_vali_result[15] = '<strong>Por favor obtener coordenadas (Cliente Sin Coordenadas)</strong>';
        }
        contarok+=2;
    }
    contarok +=V_Selec($("#cbtfacturacion").val(),'cbtfacturacion',14,'Tipo de facturaci&oacute;n');
    if(document.getElementById('checklunes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',16,'Orden de visita Lunes');
    }else{
        contarok +=1;
        arrg_vali_result[16] = '';
    }
    if(document.getElementById('checkmartes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',17,'Orden de visita Martes');
    }else{
        contarok +=1;
        arrg_vali_result[17] = '';
    }
    if(document.getElementById('checkmiercoles').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',18,'Orden de visita Miércoles');
    }else{
        contarok +=1;
        arrg_vali_result[18] = '';
    }
    if(document.getElementById('checkjueves').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',19,'Orden de visita Jueves');
    }else{
        contarok +=1;
        arrg_vali_result[19] = '';
    }
    if(document.getElementById('checkviernes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',20,'Orden de visita Viernes');
    }else{
        contarok +=1;
        arrg_vali_result[20] = '';
    }
    if(document.getElementById('checksabado').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',21,'Orden de visita Sábado');
    }else{
        contarok +=1;
        arrg_vali_result[21] = '';
    }
    if(document.getElementById('checkdomingo').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',22,'Orden de visita Domingo');
    }else{
        contarok +=1;
        arrg_vali_result[22] = '';
    }
    contarok +=V_Selec($("#cbrefrigerantes").val(),'cbrefrigerantes',23,'Capacidad del negocio');
    contarok +=V_Selec($("#cbtpuntoventa").val(),'cbtpuntoventa',24,'Tipo punto de venta');
    contarok +=V_Selec($("#cbgironegocio").val(),'cbgironegocio',25,'Giro de negocio');
    return contarok;
}
function validar_EnvioInactivo(){
    var contarok = 0;
    contarok +=V_Selec($("#motivo_inactivo").val(),'motivo_inactivo',77,'Motivo');
    return contarok;
}



function procesar_actualizacion(){
    var fechaEnDispositivo = '0000-00-00 00:00:00';
    fechaEnDispositivo = fechaDispositivo();
    var nombre_ruta = '';
    if(arrg_Credls['privilegio'] === 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155){
        nombre_ruta = arrg_Credls['ruta_desarrollador'];
    }else{
        nombre_ruta = arrg_Credls['nombre_us'];
    }
    datas = $("#form_actualizacion").serializeArray();
    
    datas.push({name: 'Id_Cliente', value: Id_Cliente});
    datas.push({name: 'Ruta_Nombre', value: nombre_ruta});
    datas.push({name: 'Id_Usuario', value: arrg_Credls['DBA_us_cod']});
    datas.push({name: 'Codigo_Cli', value: $("#lblcodcli").text()});
    datas.push({name: 'fechaEnDispositivo', value: fechaEnDispositivo});
    datas.push({name: 'EstadoAC', value: 1});
    datas.push({name: 'MotivoAC', value: ''});
    datas.push({name: 'TipoUsuario', value: arrg_Credls['privilegio']});
    datas.push({name: 'us_modifica', value: arrg_Credls['us_cod']});
    datas.push({name: 'Ruta_NombreCP', value: arrg_Credls['NombreRuta']});
    var Is_Cheked = document.getElementById('switch-two').checked;
    if (Is_Cheked){
        datas.push({name: 'txtlatitudAC', value: $("#txtlatitud").val()});
        datas.push({name: 'txtlongitudAC', value: $("#txtlongitud").val()});
    }else{
        datas.push({name: 'txtlatitudAC', value: coordenadas_Tempo[0]});
        datas.push({name: 'txtlongitudAC', value: coordenadas_Tempo[1]});
    }
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'actualizar_cleitnes/ok',
                type     : 'POST',
                dataType : 'JSON',
                data     : datas,
                timeout  : 10777
            }).done(function(_resp){
                if(_resp.rs == true){
                    DB_GuardarPermanenteCLIAC('tbl_clientesactuingre',0,datas);
                }
            }).always(function(_resp, textStatus, errorThrown) {
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        if(textStatus == "success") {
                            if(_resp.rs == true){
                                Swal.fire({
                                    type: 'success',
                                    title: 'Registro enviado exitosamente!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
                                        $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                                            DB_EstadoExhibidor_Change(Id_Cliente);
                                            // alert('Id_Cliente => '+Id_Cliente);
                                            document.getElementById("form_actualizacion").reset();
                                            $('input.checkbox').prop('checked',false);
                                            Id_Cliente = '';
                                        });
                                    });
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
                            _ajax_error_CliAC(_resp.status,_resp.readyState,_resp.statusText);
                        }
                    });
                });
            });
        });
    });
}




function enviar_actualizacion(){
    var detalle_validacion = ``;
    datas = '';
    var Is_Chekedtwo = document.getElementById('switch-two').checked;
    if(_empty(Id_Cliente)){
        Swal.fire({
            title: '<strong>Aviso!</strong>',
            type: 'info',
            html:'<strong>Selecciona un cliente por favor</strong>',
            confirmButtonText:'Ok'
        });
    }else{
        var Is_Cheked = document.getElementById('switch_estado').checked;
        if(Is_Cheked){
            if(validacion_form_actu() == 25){
                if(Is_Chekedtwo){
                    if(EstadoCoordenadas == 0){
                        Swal.fire({
                            title: '<strong>Atención!</strong>',
                            type: 'info',
                            html:'<strong>Se te ha olvidado tomar las nuevas coordenadas!</strong>',
                            confirmButtonText:'Ok'
                        }).then((result) => {
                            $('body,html').stop(true,true).animate({				
                                scrollTop: $("#anclacoord").offset().top
                            },500);
                        });
                    }else{
                        procesar_actualizacion();
                    }
                }else{
                    procesar_actualizacion()
                }
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
        }else{
            if(validar_EnvioInactivo() == 1){
                var fechaEnDispositivo = '0000-00-00 00:00:00';
                var nombre_ruta = '';
                if(arrg_Credls['privilegio'] === 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155 ){
                    nombre_ruta = arrg_Credls['ruta_desarrollador'];
                }else{
                    nombre_ruta = arrg_Credls['nombre_us'];
                }
                fechaEnDispositivo = fechaDispositivo();
                datas = $("#form_actualizacion").serializeArray();
                datas.push({name: 'Id_Cliente', value: Id_Cliente});
                datas.push({name: 'Ruta_Nombre', value: nombre_ruta});
                // datas.push({name: 'Ruta_Nombre', value: arrg_Credls['nombre_us']});
                datas.push({name: 'Id_Usuario', value: arrg_Credls['us_cod']});
                datas.push({name: 'Codigo_Cli', value: $("#lblcodcli").text()});
                datas.push({name: 'fechaEnDispositivo', value: fechaEnDispositivo});
                datas.push({name: 'EstadoAC', value: 0});
                datas.push({name: 'MotivoAC', value: $("#motivo_inactivo").val()});
                datas.push({name: 'TipoUsuario', value: arrg_Credls['privilegio']});
                // datas.push({name: 'us_modifica', value: arrg_Credls['nombre_us']});
                datas.push({name: 'us_modifica', value: arrg_Credls['us_cod']});
                datas.push({name: 'Ruta_NombreCP', value: arrg_Credls['NombreRuta']});
                var Is_Cheked = document.getElementById('switch-two').checked;
                if (Is_Cheked){
                    datas.push({name: 'txtlatitudAC', value: $("#txtlatitud").val()});
                    datas.push({name: 'txtlongitudAC', value: $("#txtlongitud").val()});
                }else{
                    datas.push({name: 'txtlatitudAC', value: coordenadas_Tempo[0]});
                    datas.push({name: 'txtlongitudAC', value: coordenadas_Tempo[1]});
                }
                $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                        $.ajax({
                            url      : 'actualizar_cleitnes/ok',
                            type     : 'POST',
                            dataType : 'JSON',
                            data     : datas,
                            timeout  : 10777
                        }).done(function(_resp){
                            if(_resp.rs == true){
                                DB_GuardarPermanenteCLIAC('tbl_clientesactuingre',0,datas);
                            }
                        }).always(function(_resp, textStatus, errorThrown) {
                            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                                    if(textStatus == "success") {
                                        if(_resp.rs == true){
                                            Swal.fire({
                                                type: 'success',
                                                title: 'Registro enviado exitosamente!',
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then((result) => {
                                                $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
                                                    $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                                                        DB_EstadoExhibidor_Change(Id_Cliente);
                                                        document.getElementById("form_actualizacion").reset();
                                                        $('input.checkbox').prop('checked',false);
                                                        Id_Cliente = '';
                                                    });
                                                });
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
                                        _ajax_error_CliAC(_resp.status,_resp.readyState,_resp.statusText);
                                    }
                                });
                            });
                        });
                    });
                });    
            }else{
                Swal.fire({
                    title: '<strong>Por favor selecciona el motivo de por qué se inactivara el cliente!</strong>',
                    type: 'warning',
                    html:'',
                    confirmButtonText:'Ok'
                });
            }

        }



    }
}
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
                var data = active.transaction('tbl_cliactutempo', "readonly");
                var object = data.objectStore('tbl_cliactutempo');
                var elements = [];
                object.openCursor().onsuccess = function (e) {
                var result = e.target.result;
                    if (result === null) {
                            return;
                    }
                    elements.push(result.value);
                    result.continue();
                };
                data.oncomplete = function () {
                    var outerHTML = '';
                    arreg_offline = [];
                    arreg_offline = elements;
                    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                            enviar_regis_offline(0,arreg_offline);
                        });
                    });  
                };
            }else{
                // Swal.fire({
                //     type: 'error',
                //     title: 'Error inesperado...',
                //     showConfirmButton: false,
                //     timer: 1500
                // });
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
            url:'actualizar_cleitnes/ok',
            type:"POST",
            data:elements[indice],
            dataType: "JSON",
            timeout:34777
            }).done(function(_resp) {
            }).always(function(_resp, textStatus, errorThrown) {
                if (textStatus == "success") {
                    if(_resp.rs == true){
                        alertify.success('Registro enviado exitosamente!');
                        enviar_regis_offline(indice + 1,arreg_offline);
                        delete_tempo_especificodos(elements[indice].id);
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                                // descargar_registros_cola('tbl_clitemporales');
                                // Swal.fire({
                                //     title: 'Ha ocurrido un error inesperado, por favor descargar y enviar archivo a sistemas de ventas',
                                //     type: _resp.info,
                                //     html:'<button class="btn btn-success" onclick="exportTableToExcel(\'tabla-registros-con-cola\')" type="button">Descargar Clientes</button> <br>Nombre : clientes_recuperados.xls',
                                //     confirmButtonText:'Ok'
                                // });
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
                arreg_offline = [];
            });
        });
    }
}
function delete_tempo_especificodos(eliminar) {
  var active = dataBaseAppSDV.result;
  var transaction = active.transaction(["tbl_cliactutempo"], "readwrite");
  transaction.oncomplete = function(event) {
  };
  transaction.onerror = function(event) {
  };
  var objectStore = transaction.objectStore("tbl_cliactutempo");
  var objectStoreRequest = objectStore.delete(eliminar);
  objectStoreRequest.onsuccess = function(event) {
    DB_CantidadEnCola('tbl_cliactutempo');
  };
}

function bloquearSiTieneValor(selector) {
    if ($(selector).val().trim() !== "") {
        $(selector).prop("readonly", true);
    } else {
        $(selector).prop("readonly", false);
    }
}