$(document).ready(function () {
    Iniciar_Todo();
    $(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
        $.when( $("#o-cliente-nuevo").stop(true,true).hide() ).done(function( x ) {
            $.when( $("#carga_clienteN").stop(true,true).show() ).done(function( x ) {
            });
        });
    });
    /* Filtro de familia */
    $(document).on('change','#cb_familia',function(){
        var num_cols, display, input, filter, table_body, tr, td, i, txtValue;
        num_cols = 3;
        input = document.getElementById("cb_familia");
        filter = input.value.toUpperCase();    
        table_body = document.getElementById("DgrTableProductos");
        // console.log('hola');
        tr = table_body.getElementsByTagName("tr");
        for(i=0; i< tr.length - 4; i++){				
            display = "none";
            for(j=0; j < num_cols; j++){
                td = tr[i].getElementsByClassName("Fam_class")[j];
                if(td){
                    txtValue = td.textContent || td.innerText;
                    if(txtValue.toUpperCase().indexOf(filter) > -1){
                        display = "";
                    }
                }
            }
            tr[i].style.display = display;
        }
    });
    $(document).on('change','.motivosPed',function(){
        var Skuid = this.id.replace('cb_motivo','');
        if($(this).val() == 1){
            if($('#desk_'+Skuid).hasClass("select_cl_n") == true){
                $('#desk_'+Skuid).removeClass('select_cl_n').addClass('select_cl_wa');
                $('#cant_'+Skuid).removeClass('select_desk_n').addClass('select_desk_wa');
            }
            if($('#cb_motivo'+Skuid).hasClass("motivo_n") == true){
                $('#cb_motivo'+Skuid).removeClass('motivo_n').addClass('motivo_wa');
            }
        }else{
            if($('#desk_'+Skuid).hasClass("select_cl_wa") == true){
                $('#desk_'+Skuid).removeClass('select_cl_wa').addClass('select_cl_n');
                $('#cant_'+Skuid).removeClass('select_desk_wa').addClass('select_desk_n');
            }
            if($('#cb_motivo'+Skuid).hasClass("motivo_wa") == true){
                $('#cb_motivo'+Skuid).removeClass('motivo_wa').addClass('motivo_n');
            }
        }
    });
    $(document).on('keyup', '.gretro_input', delayInput(function (e) {
        var cSolicitadaSku = 0,cSolicitadaExh = 0;
        var valorSolicitadoSku = 0,valorSolicitadoExh = 0;
        var totalSkuIni = 0,valorTotalIni = 0;var totalSkuSolicitado = 0,valorTotalSolicitado = 0;
        var Sku_Val = Number(this.value);
        var band_par = 0,band_menorq = 0,band_mayordo = 0,band_decimal = 0,band_negativo = 0, band_cero = 0;
        var Sku = this.id.replace('sol_','');
        const found = arrg_ls_Pedido.find(element => element.Producto === Sku);
        var Doble_ValSku = 0;Doble_ValSku = Number(found.CantidadSugerida) * 2;
        var arrg_input_s = [];
        $(".gretro_input").each(function(index, element){
            arrg_input_s[element.id] = element.value;
        });
        var TotalPedido = 0;var Cantida_SKU = 0;var cantidadSol = 0;
        arrg_ls_Pedido.forEach(function(filall, index, arrgfilall) {
            cantidadSol = 0;
            // console.log(Number(arrg_input_s['sol_'+filall.Producto]) + ' ' +filall.DescripcionProd);
            if( Number(arrg_input_s['sol_'+filall.Producto]) == 0 || Number(arrg_input_s['sol_'+filall.Producto]) == ""  ){
                cantidadSol = Number(arrg_input_s['opt_'+filall.Producto]);
            }else{
                cantidadSol = Number(arrg_input_s['sol_'+filall.Producto]);
            }
            /* -.-.-.-.-.-.-.-.- CANTIDADES SOLICITADA -.-.-.-.-.-.-.-.-.- */
            if( filall.Fa_nombre == "EXHIBIDOR" ){
                cSolicitadaExh += cantidadSol;
                valorSolicitadoExh += cantidadSol * Number(filall.PrecioUnitario);
            }else{
                cSolicitadaSku += cantidadSol;
                valorSolicitadoSku += cantidadSol * Number(filall.PrecioUnitario);
            }
        });
        totalSkuSolicitado = Number(cSolicitadaExh) + Number(cSolicitadaSku);
        valorTotalSolicitado = Number(valorSolicitadoExh) + Number(valorSolicitadoSku);
        var totalSkuIni = 0,valorTotalIni = 0, totalGeneralSku = 0, totalGeneralDinero = 0;
        totalSkuIni = $("#totalSkuIni").val();
        valorTotalIni = $("#valorTotalIni").val();
        totalGeneralSku = Number(totalSkuIni) + Number(totalSkuSolicitado);
        totalGeneralDinero = Number(valorTotalIni) + Number(valorTotalSolicitado);
        totalGeneralDinero = roundToTwo(totalGeneralDinero);
        $("#cSolicitadaSku").val(cSolicitadaSku);
        $("#cSolicitadaExh").val(cSolicitadaExh);
        $("#valorSolicitadoSku").val(valorSolicitadoSku);
        $("#valorSolicitadoExh").val(valorSolicitadoExh);
        $("#totalSkuSolicitado").val(totalSkuSolicitado);
        $("#valorTotalSolicitado").val(valorTotalSolicitado);
        $("#totalGSku").val(totalGeneralSku);
        $("#totalGDinero").val(totalGeneralDinero);
        if (this.value.trim() != '') {
            CantidaSolGlo = Cantida_SKU;
            CantidaPedidoGlo = TotalPedido;
            if($('#error_'+found.Producto).hasClass("error_sku_warning") == true){
                $('#error_'+found.Producto).removeClass('error_sku_warning').addClass('error_sku');
            }
            if(Number.isInteger(Sku_Val)){
                if(ComparaParBin(Sku_Val) == false && found.CodigoUnidadVenta == 'RT' && found.Fa_nombre == 'SNACK'){
                    band_par = 1;
                    $('#desk_'+found.Producto).removeClass('select_cl_n').addClass('select_cl_wa');
                    $('#cant_'+found.Producto).removeClass('select_desk_n').addClass('select_desk_wa');
                    $('#help_component_'+found.Producto).show();
                    $('#error_'+found.Producto).html('LA CANTIDAD DEBE SER UN NÚMERO PAR');
                    $("#cb_motivo"+found.Producto).val("1");
                    $("#S_motivo"+found.Producto).hide();
                }else if(Sku_Val > 0){
                    if( Sku_Val < Number(found.CantidadSugerida)){
                        band_menorq = 1;
                        $('#desk_'+found.Producto).removeClass('select_cl_n').addClass('select_cl_wa');
                        $('#cant_'+found.Producto).removeClass('select_desk_n').addClass('select_desk_wa');
                        $('#help_component_'+found.Producto).show();
                        $('#error_'+found.Producto).html('LA CANTIDAD NO PUEDE SER MENOR QUE LO SUGERIDO');
                        $("#cb_motivo"+found.Producto).val("1");
                        $("#S_motivo"+found.Producto).hide();
                    }else if(Sku_Val > Doble_ValSku){
                        band_mayordo = 1;
                        if($('#desk_'+found.Producto).hasClass("select_cl_wa") == true){
                            $('#desk_'+found.Producto).removeClass('select_cl_wa').addClass('select_cl_n');
                            $('#cant_'+found.Producto).removeClass('select_desk_wa').addClass('select_desk_n');
                        }else{
                            if($('#desk_'+found.Producto).hasClass("select_cl_n") == false){
                                $('#desk_'+found.Producto).toggleClass('select_cl_n');
                                $('#cant_'+found.Producto).toggleClass('select_desk_n');
                            }
                        }
                        $('#help_component_'+found.Producto).show();
                        var ClasificacionRuta = '';
                        var FamiliaProducto   = '';
                        var DiaInventario = 0;
                        var CalculoDiaInventario = 0;
                        var ClaseError = 'error_sku';
                        var Msj = '';
                        CalculoDiaInventario = Sku_Val - found.CantidadSugerida;
                        CalculoDiaInventario = CalculoDiaInventario/found.CantidadSugerida;
                        // CalculoDiaInventario = CalculoDiaInventario * 100;
                        CalculoDiaInventario = roundToTwo(CalculoDiaInventario);
                        CalculoDiaInventario = Math.round(CalculoDiaInventario);
                        CalculoDiaInventario = Number(CalculoDiaInventario);
                        if( isNaN(CalculoDiaInventario) == true ){
                            Msj = `ESTA SOLICITANDO PRODUCTO NO SUGERIDO`;
                        }else{
                            Msj = `LA CANTIDAD DE PRODUCTO SOLICITADA EXCEDE ${CalculoDiaInventario} DÍA MÁS DE LO SUGERIDO`;
                        }
                        if(CalculoDiaInventario > 1){
                            ClaseError = 'error_sku_warning';
                            if($('#error_'+found.Producto).hasClass("error_sku_warning") == true){
                                $('#error_'+found.Producto).removeClass('error_sku_warning').addClass('error_sku');
                            }else{
                                $('#error_'+found.Producto).addClass('error_sku');
                            }
                        }else{
                            // ClaseError = 'error_sku';
                            if($('#error_'+found.Producto).hasClass("error_sku") == true){
                                $('#error_'+found.Producto).removeClass('error_sku').addClass('error_sku_warning');
                            }else{
                                $('#error_'+found.Producto).addClass('error_sku_warning');
                            }
                        }
                        // $('#error_'+found.Producto).html(`LA CANTIDAD DE PRODUCTO SOLICITADA EXCEDE UN ${CalculoDiaInventario}% MÁS DE LO SUGERIDOO`);
                        $('#error_'+found.Producto).html(Msj);
                        $("#S_motivo"+found.Producto).show();
                    }else{
                        $("#cb_motivo"+found.Producto).val("1");
                        $("#S_motivo"+found.Producto).hide();
                    }
                }else{
                    if( Sku_Val == 0 ){
                        if(found.HistoricoVenta == 1){
                            if(found.CantidadSugerida > 0){
                                $('#help_component_'+found.Producto).show();
                                $('#error_'+found.Producto).html('LA CANTIDAD NO PUEDE SER CERO, HAY CANTIDAD SUGERIDA');
                                $("#cb_motivo"+found.Producto).val("1");
                                $("#S_motivo"+found.Producto).hide();
                                var div = $('#help_component_'+found.Producto);
                                $.when(div.fadeOut(1500)).done(function() {
                                    $('#sol_'+found.Producto).val(found.CantidadSugerida);
                                });
                                band_cero = 1;
                            }
                        }
                    }else if(Sku_Val < 0){
                        band_negativo = 1;
                        $('#desk_'+found.Producto).removeClass('select_cl_n').addClass('select_cl_wa');
                        $('#cant_'+found.Producto).removeClass('select_desk_n').addClass('select_desk_wa');
                        $('#help_component_'+found.Producto).show();
                        $('#error_'+found.Producto).html('LA CANTIDAD DEBE SER POSITIVA');
                        $("#cb_motivo"+found.Producto).val("1");
                        $("#S_motivo"+found.Producto).hide();
                    }
                }
            }else{
                $('#help_component_'+found.Producto).show();
                band_decimal = 1;
                $('#desk_'+found.Producto).removeClass('select_cl_n').addClass('select_cl_wa');
                $('#cant_'+found.Producto).removeClass('select_desk_n').addClass('select_desk_wa');
                $('#error_'+found.Producto).html(`LA CANTIDAD SOLO PUEDE SER UN ENTERO`);
            }
            var AcumuError = 0;
            AcumuError = band_par + band_menorq + band_mayordo + band_decimal + band_negativo + band_cero;
            if(AcumuError == 0){
                $('#error_'+found.Producto).empty();
                $('#help_component_'+found.Producto).hide();
                if($('#desk_'+found.Producto).hasClass("select_cl_wa") == true){
                    $('#desk_'+found.Producto).removeClass('select_cl_wa').addClass('select_cl_n');
                    $('#cant_'+found.Producto).removeClass('select_desk_wa').addClass('select_desk_n');
                }else{
                    if($('#desk_'+found.Producto).hasClass("select_cl_n") == false){
                        $('#desk_'+found.Producto).toggleClass('select_cl_n');
                        $('#cant_'+found.Producto).toggleClass('select_desk_n');
                    }
                }
                $('#chek_'+found.Producto).prop('checked', true);
                arrg_check_s['chek_'+found.Producto] = true;
                $("#cb_motivo"+found.Producto).val("1");
                $("#S_motivo"+found.Producto).hide();
            }else{
                if(band_mayordo == 1){
                    $('#chek_'+found.Producto).prop('checked', true);
                    arrg_check_s['chek_'+found.Producto] = true;
                }
            }
        }else{
            $('#help_component_'+found.Producto).hide();
            $('#error_'+found.Producto).empty();
            $('#subf_'+found.Producto).click();
            $("#cb_motivo"+found.Producto).val("1");
            $("#S_motivo"+found.Producto).hide();
        }
    }, 777));
    $(document).on('click', '#btn_pedido', function () {
        $.when($(".carga-class").stop(true, true).show()).done(function(x) {
            var arrg_validaciones = [];var cv = 0;var CodEstadoPedido = 3;
            var arrg_solicitudes  = [];var arrg_input_s = [];arrg_pedido_env = [];
            var band_check = 0,band_par = 0,band_menorq = 0,band_mayordo = 0,band_decimal = 0,band_motivo = 0,band_negativo = 0;
            var c=0,p=0,me=0,ma=0,ent=0,ne=0;
            error_Sku[0] = new Array(2);error_Sku[1] = new Array(2);
            error_Sku[2] = new Array(2);error_Sku[3] = new Array(2);
            error_Sku[4] = new Array(2);error_Sku[5] = new Array(2);
            error_Sku_Descr[0] = new Array(2);error_Sku_Descr[1] = new Array(2);
            error_Sku_Descr[2] = new Array(2);error_Sku_Descr[3] = new Array(2);
            error_Sku_Descr[4] = new Array(2);error_Sku_Descr[5] = new Array(2);
            $(".gretro_input").each(function(index, element){
                arrg_input_s[element.id] = element.value;
            });
            var Sku_Val = 0;
            var oooo = 1;
            arrg_ls_Pedido.forEach(function(filall, index, arrgfilall) {
                Sku_Val            = Number(arrg_input_s['sol_'+filall.Producto]);
                var status_chk     = arrg_check_s['chek_'+filall.Producto];
                $('.gretro_div1 .error_sku').empty();
                if(Number.isInteger(Sku_Val)){
                    if(status_chk == false){
                        band_check = 1;
                        error_Sku_Descr[0][c] = '<span class="item_SKU">'+filall.Producto+'</span> <br><span class="item_DESP">'+filall.DescripcionProd+'</span>';
                        error_Sku[0][c] = filall.Producto;
                        c++;
                    }else{
                        $('#desk_'+filall.Producto).removeClass('select_cl_wa').addClass('select_cl_n');
                        $('#cant_'+filall.Producto).removeClass('select_desk_wa').addClass('select_desk_n');
                    }
                    if(band_check == 0){
                        if(ComparaParBin(Sku_Val) == false && filall.CodigoUnidadVenta == 'RT' && filall.Fa_nombre == 'SNACK'){
                            band_par = 1;
                            error_Sku_Descr[1][p] = '<span class="item_SKU">'+filall.Producto+'</span> <br><span class="item_DESP">'+filall.DescripcionProd+'</span>';
                            error_Sku[1][p] = filall.Producto;
                            p++;
                        }
                    }
                    if(band_par == 0 && band_check == 0){
                        if(Sku_Val > 0){
                            if( Sku_Val < Number(filall.CantidadSugerida)){
                                band_menorq = 1;
                                error_Sku_Descr[2][me] = '<span class="item_SKU">'+filall.Producto+'</span> <br><span class="item_DESP">'+filall.DescripcionProd+'</span>';
                                error_Sku[2][me] = filall.Producto;
                                me++;
                            }
                        }
                    }
                    if(band_par == 0 && band_check == 0 && band_menorq == 0){
                        var Doble_ValSku = 0;Doble_ValSku = Number(filall.CantidadSugerida) * 2;
                        if(Sku_Val > Doble_ValSku){
                            band_mayordo = 1;
                            CalculoDiaInventario = Sku_Val - filall.CantidadSugerida;
                            CalculoDiaInventario = CalculoDiaInventario/filall.CantidadSugerida;
                            CalculoDiaInventario = CalculoDiaInventario * 100;
                            CalculoDiaInventario = roundToTwo(CalculoDiaInventario);
                            if($("#cb_motivo"+filall.Producto).val() == 1){
                                error_Sku_Descr[3][ma] = '<span class="item_SKU">'+filall.Producto+'</span> <br><span class="item_DESP">'+filall.DescripcionProd+' '+CalculoDiaInventario+' MÁS</span>';
                                error_Sku[3][ma] = filall.Producto;
                                band_motivo = 1;
                            }
                            ma++;
                        }
                    }
                    if(band_par == 0 && band_check == 0 && band_menorq == 0 && band_motivo == 0){
                        if( Sku_Val < 0 ){
                            band_negativo = 1;
                            error_Sku_Descr[5][ne] = '<span class="item_SKU">'+filall.Producto+'</span> <br><span class="item_DESP">'+filall.DescripcionProd+'</span>';
                            error_Sku[5][ne] = filall.Producto;
                            ne++;
                        }
                    }
                    if( Sku_Val == '' || Sku_Val == 0 ){
                        Sku_Val = filall.CantidadSugerida;
                    }
                }else{
                    band_decimal = 1;
                    error_Sku_Descr[4][ent] = '<span class="item_SKU">'+filall.Producto+'</span> <br><span class="item_DESP">'+filall.DescripcionProd+'</span>';
                    error_Sku[4][ent] = filall.Producto;
                    ent++;
                }
                arrg_pedido_env.push({
                    "Correlativo"    : filall.Correlativo,
                    "IdPedidoEnc"    : filall.Id,
                    "Producto"       : filall.Producto,
                    "DescripcionProd": filall.DescripcionProd,
                    "CantidadPedida" : Sku_Val,
                    "MotivoVendedor" : $("#cb_motivo"+filall.Producto).val()
                });
            });
            var html_error = ``;var arrg_errores_info = '';
            var arrg_errores_skus = ''; var txt_error  = '';
            if(band_decimal ==1){
                html_error = `<h5>LA CANTIDAD SOLO PUEDE SER UN ENTERO.</h5><br>`;
                txt_error  = `LA CANTIDAD SOLO PUEDE SER UN ENTERO`;
                arrg_errores_info = error_Sku_Descr[4];
                arrg_errores_skus = error_Sku[4];
            }else{
                if(band_check == 1){
                    html_error = `<h5>CONFIRMAR LA CANTIDAD DEL PRODUCTO</h5><br>`;
                    arrg_errores_info = error_Sku_Descr[0];
                    arrg_errores_skus = error_Sku[0];
                }else if (band_par == 1){
                    html_error = `<h5>LAS CANTIDADES EN EL PRODUCTO DE RISTRA DEBEN SER PARES</h5><br>`;
                    txt_error  = 'LA CANTIDAD DEBE SER UN NÚMERO PAR';
                    arrg_errores_info = error_Sku_Descr[1];
                    arrg_errores_skus = error_Sku[1];
                }else if (band_menorq == 1){
                    html_error = `<h5>LA CANTIDAD DE PRODUCTO SOLICITADO NO PUEDE SER MENOR QUE LO SUGERIDO</h5><br>`;
                    txt_error  = 'LA CANTIDAD NO PUEDE SER MENOR QUE LO SUGERIDO';
                    arrg_errores_info = error_Sku_Descr[2];
                    arrg_errores_skus = error_Sku[2];
                }else if (band_motivo == 1){
                    html_error = `<h5>ELEGIR MOTIVO DE INCREMENTO AL PEDIDO SUGERIDO</h5><br>`;
                    txt_error  = `ELEGIR MOTIVO DE INCREMENTO AL PEDIDO SUGERIDO`;
                    arrg_errores_info = error_Sku_Descr[3];
                    arrg_errores_skus = error_Sku[3];
                }else if (band_negativo == 1){
                    html_error = `<h5>LA CANTIDAD NO PUEDE SER MENOR QUE CERO</h5><br>`;
                    txt_error  = `LA CANTIDAD NO PUEDE SER MENOR QUE CERO`;
                    arrg_errores_info = error_Sku_Descr[5]; 
                    arrg_errores_skus = error_Sku[5];
                }else{
                    html_error = ``;
                }
            }
            var AcumuError = 0;
            AcumuError = band_check + band_par + band_menorq + band_decimal + band_motivo + band_negativo;
            if( AcumuError > 0 ){
                arrg_errores_skus = arrg_errores_skus.filter(Boolean);
                arrg_errores_info = arrg_errores_info.filter(Boolean);
                arrg_errores_skus.forEach(function(sku) {
                    $('#desk_'+sku).removeClass('select_cl_n').addClass('select_cl_wa');
                    $('#cant_'+sku).removeClass('select_desk_n').addClass('select_desk_wa');
                    if( band_motivo == 0 ){
                        $('#error_'+sku).html(txt_error);
                    }
                });
                arrg_errores_info.forEach(function(info) {
                    html_error += `<p>${info}</p>`;
                });
                $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                    Swal.fire({
                        type: 'warning',
                        title: 'Aviso',
                        html: html_error,
                        showConfirmButton: true
                    }).then((result) => {});
                });
            }else{
                $.ajax({
                    url: 'pedido_sugerido/solicitado',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {pedido_sugerido:arrg_pedido_env,CodEstadoPedido:CodEstadoPedido},
                    timeout: 17777
                }).done(function(_resp) {
                    if (_resp.rs == true) {
                    }
                }).always(function(_resp, textStatus, errorThrown) {
                    $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                        if (textStatus == "success") {
                            if (_resp.rs == true) {
                                Actualizar_PedidOptimo(arrg_pedido_env,'NO',_resp.alert,_resp.tipo+_resp.info,CodEstadoPedido);
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: _resp.tipo,
                                    html: _resp.info,
                                    showConfirmButton: true
                                });
                            }
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: 'Error Desconocido...',
                                html: _resp.responseText,
                                showConfirmButton: true,
                            });
                            _ajax_error_statusexh(_resp.status, _resp.readyState, _resp.statusText,CodEstadoPedido);
                        }
                    });
                });
            }
        });
    });
    $('#DgrTableProductos').on('click', '.cantidades_inv .CantidadPedida', function () {
        var Sku = this.id.replace('sol_','');
        var status_chk = $('#chek_'+Sku).prop('checked');
        if( status_chk == false ){
            $('#chek_'+Sku).prop('checked', true);
            arrg_check_s['chek_'+Sku] = true;
        }
        if($('#desk_'+Sku).hasClass("select_cl_wa") == true){
            $('#desk_'+Sku).removeClass('select_cl_wa').addClass('select_cl_n');
            $('#cant_'+Sku).removeClass('select_desk_wa').addClass('select_desk_n');
        }else{
            $('#desk_'+Sku).addClass('select_cl_n');
            $('#cant_'+Sku).addClass('select_desk_n');
        }
    });
    $('#DgrTableProductos').on('click', '.content_click', function () {
        var Sku = this.id.replace('subf_','');
        var status_chk = $('#chek_'+Sku).prop('checked');
        if( status_chk == false ){
            $('#chek_'+Sku).prop('checked', true);
            arrg_check_s['chek_'+Sku] = true;
        }
        if($('#desk_'+Sku).hasClass("select_cl_wa") == true){
            $('#desk_'+Sku).removeClass('select_cl_wa').addClass('select_cl_n');
            $('#cant_'+Sku).removeClass('select_desk_wa').addClass('select_desk_n');
        }else{
            $('#desk_'+Sku).addClass('select_cl_n');
            $('#cant_'+Sku).addClass('select_desk_n');
        }
    });
});
function Cargar_Pedido(Json_carga_optima,Cantidad_Final,TotalPedido){
    CantidaSolGlo = Cantidad_Final;
    CantidaPedidoGlo = TotalPedido;
    var EspiaPedido = 0;
    var html_body = ``;
    var CodEstadoPedido  = 2;
    var html_VConHistorico = ``,html_VSinHistorico = ``;
    sortJSON(Json_carga_optima, 'Fa_orden', 'asc');
    var cSolicitadaSku = 0,cSolicitadaExh = 0,cInventarioSku = 0,cInventarioExh = 0;
    var valorSolicitadoSku = 0,valorSolicitadoExh = 0,valorInvSku = 0,valorInvExh = 0;
    var totalSkuIni = 0,valorTotalIni = 0;var totalSkuSolicitado = 0,valorTotalSolicitado = 0;
    var totalGSku = 0,totalGDinero = 0;
    Json_carga_optima.forEach(function(row, index, arrgfilall) {
        var CantidadPedidaRoly = '';var CantidadUltima = 0, CantidadInventario = 0, InputReadonly = '';
        CantidadInventario = Number(row.CantidadInventario);
        var cantidadSol = 0;
        var WarningPedP = '',WarningPedC = '',SelectedW = '';
        CodEstadoPedido = row.CodEstadoPedido;
        if( row.CodEstadoPedido == 2 ){
            CantidadUltima = row.CantidadSugerida;
            InputReadonly = 'input_optyma';
            cantidadSol = Number(row.CantidadSugerida);
        }else{
            CantidadPedidaRoly = 'readonly';
            EspiaPedido  = 1;
            CantidadUltima = row.CantidadPedida;
            InputReadonly = 'input_readonly';
            cantidadSol = Number(row.CantidadPedida);
        }
        if( (row.HistoricoVenta == 0 ) || (row.HistoricoVenta == 1 && row.Fa_nombre == "EXHIBIDOR") ){
            WarningPedP = 'select_cl_w';
            WarningPedC = 'select_desk_w';
            SelectedW = 'checked="checked"';
        }else{
            SelectedW = '';
        }
        /* -.-.-.-.-.-.-.-.- CANTIDADES SOLICITADA -.-.-.-.-.-.-.-.-.- */
        if( row.Fa_nombre == "EXHIBIDOR" ){
            cSolicitadaExh += cantidadSol;
            valorSolicitadoExh += cantidadSol * Number(row.PrecioUnitario);
        }else{
            cSolicitadaSku += cantidadSol;
            valorSolicitadoSku += cantidadSol * Number(row.PrecioUnitario);
        }
        /* -.-.-.-.-.-.-.-.- CANTIDADES EN INVENTARIO -.-.-.-.-.-.-.-.-.-*/
        if( row.Fa_nombre == "EXHIBIDOR" ){
            cInventarioExh += Number(row.CantidadInventario);
            valorInvExh += Number(row.CantidadInventario) * Number(row.PrecioUnitario);
        }else{
            cInventarioSku += Number(row.CantidadInventario);
            valorInvSku += Number(row.CantidadInventario) * Number(row.PrecioUnitario);
        }
        valorInvSku = roundToTwo(valorInvSku);/* Valor total de sku en inventario */
        valorSolicitadoSku = roundToTwo(valorSolicitadoSku);/* Valor total de sku en cantidad solicitada */
        totalSkuIni = cInventarioExh + cInventarioSku;/* Cantidad de sku y exhibidores en inventario  */
        valorTotalIni = valorInvExh + valorInvSku;/* Valor total de sku y exhibidores en inventario */
        valorTotalIni = roundToTwo(valorTotalIni);
        totalSkuSolicitado = cSolicitadaExh + cSolicitadaSku;/* Cantidad de SKU y Exhibidores en cantidada solicitada */
        valorTotalSolicitado = valorSolicitadoExh + valorSolicitadoSku;/* Valor total de sku y exhibidores en cantidad solicitada */
        valorTotalSolicitado = roundToTwo(valorTotalSolicitado);
        totalGSku = totalSkuSolicitado + totalSkuIni;/* Cantidad general por sku de inventario mas solicitado */
        totalGDinero = valorTotalIni + valorTotalSolicitado;/* Valor total de inventario mas lo solicitado */
        totalGDinero = roundToTwo(totalGDinero);
        html_body = ``;
        html_body = `
        <tr class="fila_principal" id="${row.DT_RowId}">
            <td class="subfila_principal yoshi">
                <div class="content_content">
                    <div class="content_click" id="subf_${row.Producto}">
                        <div class="sku_head">
                            <b>${row.Producto}</b>
                        </div>
                        <div class="desc_body ${WarningPedP}" id="desk_${row.Producto}">
                            ${row.DescripcionProd}
                        </div>
                    </div>
                    <div class="cantidades_inv ${WarningPedC}" id="cant_${row.Producto}">
                        <div class="row">
                            <div class="col">
                                <label>INVENTARIO</label><br>
                                <input type="text" class="form-control input_pd input_readonly" placeholder="0" value="${CantidadInventario}" readonly>
                            </div>
                            <div class="col">
                                <label>ÓPTIMA</label><br>
                                <input type="tel" class="form-control gretro_input input_pd input_readonly" id="opt_${row.Producto}"  name="opt_${row.Producto}" placeholder="0" value= "${row.CantidadSugerida}" readonly>
                            </div>
                            <div class="col">
                                <label>SOLICITADO</label><br>
                                <input type="tel" maxlength="3" class="form-control gretro_input input_pd CantidadPedida ${InputReadonly}" id="sol_${row.Producto}" name="sol_${row.Producto}" placeholder="0" value= "${CantidadUltima}" ${CantidadPedidaRoly}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="help_component" id="help_component_${row.Producto}">
                    <input type='checkbox' id='chek_${row.Producto}' name='chek_${row.Producto}' value='1' class='chequegrande' ${SelectedW}>
                    <span class='error_sku' id='error_${row.Producto}'></span>
                </div>
                <div class="col" id="S_motivo${row.Producto}" align="center" style="display:none;">
                    <div class="gretro-select">
                        <select id='cb_motivo${row.Producto}' class="form-control motivosPed motivo_wa">
                            <option value='1' selected>ELEGIR MOTIVO DEL INCREMENTO</option>`;
                            arrg_ls_Motivos.forEach(function(filall, index, arrgfilall) {
                                html_body += `<option value="${filall.Id}">${filall.DescripcionMotivo}</option>`;
                            });
          html_body += `</select>
                    </div>		
                </div>
                <span class="Fam_class" style="display:;color:transparent;">${row.Fa_nombre}</span>
            </td>
        </tr>`;
        if( row.HistoricoVenta == 1 && row.Fa_nombre!="EXHIBIDOR"){
            html_VConHistorico+=html_body;
        }else if( row.HistoricoVenta == 0 ){
            html_VSinHistorico+=html_body;
        }else if ( row.Fa_nombre == "EXHIBIDOR" ){
            html_VSinHistorico+=html_body;
        }else{
            html_VConHistorico+=html_body;
        }
    });
    $("#showDataPSug").html(html_VConHistorico+html_VSinHistorico);
    var msj_estadopedido = ``;
    if( CodEstadoPedido == 4 ){
        msj_estadopedido = `AUTORIZADO`;
    }else if( CodEstadoPedido == 5 ){
        msj_estadopedido = `DESCARGADO`;
    }else if( CodEstadoPedido == 3 ){
        msj_estadopedido = `PENDIENTE AUTORIZAR`;
    }
    var tr_et_finales = `
    <tr class="fila_principal">
        <td class="subfila_principal yoshi">
            <div class="content_content">
                <div>
                    <div class="sku_headDe">
                        <b>CANTIDAD INVENTARIO INI</b>
                    </div>
                </div>
                <div class="cantidades_inv">
                    <div class="row">
                        <div class="col">
                            <label class="labelCant">CANT. SKU</label><br>
                            <input id="cInventarioSku" type="text" class="form-control input_pd input_readonly input_cantidadDet" placeholder="0" value="${cInventarioSku}" readonly>
                        </div>
                        <div class="col">
                            <label class="labelCant">CANT. EXH</label><br>
                            <input id="cInventarioExh" type="tel" class="form-control gretro_input input_pd input_readonly input_cantidadDet" placeholder="0" value= "${cInventarioExh}" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label class="labelCant">TOTAL <span class="fas fa-dollar-sign fa-lg" style="padding: 4px;"></span></label><br>
                            <input id="valorInvSku" type="text" class="form-control input_pd input_readonly input_cantidadDet" placeholder="0" value="${valorInvSku}" readonly>
                        </div>
                        <div class="col">
                            <label class="labelCant">TOTAL <span class="fas fa-dollar-sign fa-lg" style="padding: 4px;"></span></label><br>
                            <input id="valorInvExh" type="tel" class="form-control gretro_input input_pd input_readonly input_cantidadDet" placeholder="0" value= "${valorInvExh}" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label class="labelCant">TOTAL SKU</label><br>
                        </div>
                        <div class="col">
                            <label class="labelCant">TOTAL <span class="fas fa-dollar-sign fa-lg" style="padding: 4px;"></span></label><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input id="totalSkuIni" type="text" class="form-control input_pd input_readonly input_cantidadDetT" placeholder="0" value="${totalSkuIni}" readonly>
                        </div>
                        <div class="col">
                            <input id="valorTotalIni" type="tel" class="form-control gretro_input input_pd input_readonly input_cantidadDetT" placeholder="0" value= "${valorTotalIni}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr class="fila_principal">
        <td class="subfila_principal yoshi">
            <div class="content_content">
                <div>
                    <div class="sku_headDe">
                        <b>CANTIDAD SOLICITADA</b>
                    </div>
                </div>
                <div class="cantidades_inv">
                    <div class="row">
                        <div class="col">
                            <label class="labelCant">CANT. SKU</label><br>
                            <input id="cSolicitadaSku" type="text" class="form-control input_pd input_readonly input_cantidadDet" placeholder="0" value="${cSolicitadaSku}" readonly>
                        </div>
                        <div class="col">
                            <label class="labelCant">CANT. EXH</label><br>
                            <input id="cSolicitadaExh" type="tel" class="form-control gretro_input input_pd input_readonly input_cantidadDet" placeholder="0" value= "${cSolicitadaExh}" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label class="labelCant">TOTAL <span class="fas fa-dollar-sign fa-lg" style="padding: 4px;"></span></label><br>
                            <input id="valorSolicitadoSku" type="text" class="form-control input_pd input_readonly input_cantidadDet" placeholder="0" value="${valorSolicitadoSku}" readonly>
                        </div>
                        <div class="col">
                            <label class="labelCant">TOTAL <span class="fas fa-dollar-sign fa-lg" style="padding: 4px;"></span></label><br>
                            <input id="valorSolicitadoExh" type="tel" class="form-control gretro_input input_pd input_readonly input_cantidadDet" placeholder="0" value= "${valorSolicitadoExh}" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label class="labelCant">TOTAL SKU</label><br>
                        </div>
                        <div class="col">
                            <label class="labelCant">TOTAL <span class="fas fa-dollar-sign fa-lg" style="padding: 4px;"></span></label><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input id="totalSkuSolicitado" type="text" class="form-control input_pd input_readonly input_cantidadDetT" placeholder="0" value="${totalSkuSolicitado}" readonly>
                        </div>
                        <div class="col">
                            <input id="valorTotalSolicitado" type="tel" class="form-control gretro_input input_pd input_readonly input_cantidadDetT" placeholder="0" value= "${valorTotalSolicitado}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <div class="sku_headDe">
                    <b>TOTAL GENERAL</b>
                </div>
            </div>

            <div class="cantidades_inv">
                <div class="row">
                    <div class="col">
                        <label class="labelCant">SKU</label><br>
                        <input id="totalGSku" type="text" class="form-control input_pd input_readonly input_cantidadDetT" placeholder="0" value="${totalGSku}" readonly>
                    </div>
                    <div class="col">
                        <label class="labelCant"><span class="fas fa-dollar-sign fa-lg" style="padding: 4px;"></span></label><br>
                        <input id="totalGDinero" type="tel" class="form-control gretro_input input_pd input_readonly input_cantidadDetT" placeholder="0" value= "${totalGDinero}" readonly>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding: 0px;">
            <span id="EstadoPedido">${msj_estadopedido}</span>
        </td>
    </tr>`;
    $('#DgrTableProductos tr:last').after(tr_et_finales);
    if( EspiaPedido == 1 )
        $("#btn_pedido").hide();
}
function ComparaParBin(numero) {
  return numero%2==0; 
} 
function delayInput(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
}
function Actualizar_PedidOptimo(arrgData, pendienteX,estadoalert,msjalert,CodEstadoPedido) {
    var Ct = 0, conta = 0;
    Ct = Object.entries(arrgData).length;
    if (Ct > 0) {
        arrgData.forEach(function (dato, index, arrayinsertar) {
            var actived = dataBaseAppSDV.result;
            var objectStore = actived.transaction(["tbl_PedSug_PedidosDet"], "readwrite").objectStore("tbl_PedSug_PedidosDet");
            var request = objectStore.get(dato.Correlativo);
            request.onerror = function (event) {
            };
            request.onsuccess = function (event) {
                var data = request.result;
                data.CantidadPedida = dato.CantidadPedida;
                data.CodEstadoPedido = CodEstadoPedido;
                data.PedSug_cola = pendienteX;
                data.MotivoVendedor = dato.MotivoVendedor;
                var requestUpdate = objectStore.put(data);
                requestUpdate.onerror = function (event) {
                };
                requestUpdate.onsuccess = function (event) {
                };
            };
            conta++;
        });
    }
    // console.log('CANTIDAD TRABAJADOS AC => ' + conta);
    if (Ct == conta) {
        if (pendienteX == 'SI') {
            Swal.fire({
                type: 'success',
                title: 'Pedido Guardado Con Éxito En Cola!',
                showConfirmButton: false,
                timer: 1500
            }).then((result) => {
                // location. reload();
                $('#DgrTableProductos').DataTable().destroy();
                $("#showDataPSug").empty();
                Iniciar_Todo();
            });
        } else {
            if(estadoalert){
                Swal.fire({
                    type: 'success',
                    title: 'Pedido Enviado Con Éxito!',
                    html:msjalert,
                    showConfirmButton: true
                }).then((result) => {
                    // location. reload();
                    $('#DgrTableProductos').DataTable().destroy();
                    $("#showDataPSug").empty();
                    Iniciar_Todo();
                });
            }else{
                Swal.fire({
                    type: 'success',
                    title: 'Pedido Enviado Con Éxito!',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    // location. reload();
                    $('#DgrTableProductos').DataTable().destroy();
                    $("#showDataPSug").empty();
                    Iniciar_Todo();
                });
            }
        }
    } else {
        Swal.fire({
            type: 'error',
            title:'Error En El Ingreso De Exhibidores BDDTel',
            title: '<br>Cantidad Registros ='+Ct+' <br>Cantidad Procesado = '+conta,
            showConfirmButton: true
        });
    }
}
function ConsultarColaPedSug() {
    return new Promise(function (resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_PedSug_PedidosDet', 'readonly'),
            store = transaccion.objectStore('tbl_PedSug_PedidosDet'),
            indice = store.index('by_PedSug_cola'),
            cursor = indice.openCursor('SI')
        cursor.onsuccess = function (event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                arrg_pedido_envCola = dataResult;
                let nuevoObjeto = {}
                dataResult.forEach( x => {
                if( !nuevoObjeto.hasOwnProperty(x.Id)){
                    nuevoObjeto[x.Id] = {
                    Pedidos: []
                    }
                }
                nuevoObjeto[x.Id].Pedidos.push({
                    nombre: x.Producto,
                    descripcion: x.DescripcionProd
                    })
                });
                CantCola = parseInt(Object.keys(nuevoObjeto).length);
                $("#RegisCola").text(CantCola);
                resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
function _ajax_error_statusexh(jqXHR, textStatus, errorThrown,CodEstadoPedido) {
    if (textStatus === 'timeout') {
        Actualizar_PedidOptimo(arrg_pedido_env, 'SI',false,'',CodEstadoPedido);
        return;
    } else if (jqXHR === 0) {
        Actualizar_PedidOptimo(arrg_pedido_env, 'SI',false,'',CodEstadoPedido);
        return;
    } else if (jqXHR === 200) {
        Actualizar_PedidOptimo(arrg_pedido_env, 'SI',false,'',CodEstadoPedido);
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
function EnvioCola() {
    var cantidiadCola = $("#RegisCola").text();
    if (cantidiadCola > 0) {
        Swal.fire({
            title: 'Enviar Registros En Cola ?',
            text: "",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, enviar!',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false
        }).then((result) => {
            $.when($(".carga-class").stop(true, true).show()).done(function(x) {
                if (result.value) {
                    // ColaExhibidores = arrgColaE;
                    // var C_Exhibidores = parseInt(ColaExhibidores.length);
                    envio_ColaPsug(0, arrg_pedido_envCola);
                } else {
                    $.when($(".carga-class").stop(true, true).hide()).done(function(x) {});
                }
            });
        });
    } else {
        Swal.fire({
            type: 'info',
            title: 'No tienes registros en cola!',
            showConfirmButton: false,
            timer: 1500
        });
    }
}
function envio_ColaPsug(indice, elementos) {
    var arrg_pedido_env = [];
    elementos.forEach(function(filall, index, arrgfilall) {
        arrg_pedido_env.push({
            "Correlativo"    : filall.Correlativo,
            "IdPedidoEnc"    : filall.Id,
            "Producto"       : filall.Producto,
            "DescripcionProd": filall.DescripcionProd,
            "CantidadPedida" : filall.CantidadPedida,
            "MotivoVendedor" : filall.MotivoVendedor
        });
    });
    $.ajax({
        url: 'pedido_sugerido/solicitado',
        type: 'POST',
        dataType: 'JSON',
        data: {pedido_sugerido:arrg_pedido_env,CodEstadoPedido:elementos[0].CodEstadoPedido},
        timeout: 17777
    }).done(function (_resp) {
    }).always(function (_resp, textStatus, errorThrown) {
        if (textStatus == "success") {
            if (_resp.rs == true) {
                var conta = 0,total_registros = 0;
                total_registros = parseInt(Object.keys(arrg_pedido_env).length);
                elementos.forEach(function (dato, index, arrayinsertar) {
                    var actived = dataBaseAppSDV.result;
                    var objectStore = actived.transaction(["tbl_PedSug_PedidosDet"], "readwrite").objectStore("tbl_PedSug_PedidosDet");
                    var request = objectStore.get(dato.Correlativo);
                    request.onerror = function (event) {
                    };
                    request.onsuccess = function (event) {
                        var data = request.result;
                        data.PedSug_cola = 'NO';
                        var requestUpdate = objectStore.put(data);
                        requestUpdate.onerror = function (event) {
                        };
                        requestUpdate.onsuccess = function (event) {
                        };
                    };
                    conta++;
                });
                if (total_registros == conta) {
                    $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                        if(_resp.alert){
                            Swal.fire({
                                type: 'success',
                                title: 'Pedido Enviado Con Éxito!',
                                html:_resp.tipo+_resp.info,
                                showConfirmButton: true
                            }).then((result) => {
                                ConsultarColaPedSug();
                            });
                        }else{
                            Swal.fire({
                                type: 'success',
                                title: 'Pedido Enviado Con Exito!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                ConsultarColaPedSug();
                            });
                        }
                    });
                } else {
                    $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                        Swal.fire({
                            type: 'error',
                            title:'Error En BDDTel',
                            title: '<br>Cantidad Registros ='+total_registros+' <br>Cantidad Procesado = '+conta,
                            showConfirmButton: true
                        });
                    });
                }
            } else {
                $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                    Swal.fire({
                        type: 'error',
                        title: _resp.tipo,
                        html: _resp.info,
                        showConfirmButton: true
                    });
                });
            }
        } else {
            $.when($(".carga-class").stop(true, true).hide()).done(function (x) {
                var errorhtml = `
                <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Detalle Error (Pedido)</a>
                <div class="row">
                    <div class="col">
                        <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            ${_resp.responseText}
                        </div>
                        </div>
                    </div>
                </div>`;
                Swal.fire({
                    title: 'Aviso!',
                    type: 'error',
                    html: errorhtml,
                    confirmButtonText: 'Ok'
                });
            });
        }
    });
}
function Iniciar_Todo(){
    arrg_ls_Pedido = [];
    Promise.all([
        DB_IniciarCPSesionPS()
    ]).then(respuestas =>{
        Promise.all([
            DB_Pedido_Sugerido(),
            DB_PedidoMotivos("2"),
            ConsultarColaPedSug()
        ]).then(respuestas =>{
            arrg_familias = [];var k = 0;var Cantidad_Final = 0;
            var TotalPedido = 0;var Cantida_SKU = 0;arrg_check_s = [];
            arrg_ls_Pedido.forEach(function(filall, index, arrgfilall) {
                if( filall.CodEstadoPedido == 3 || filall.CodEstadoPedido == 4){
                    Cantidad_Final = Number(filall.CantidadPedida);
                    TotalPedido += Cantidad_Final * filall.PrecioUnitario;
                }else{
                    Cantidad_Final = Number(filall.CantidadSugerida);
                    TotalPedido = filall.TotalPedido;
                }
                Cantida_SKU += Cantidad_Final; 
                arrg_familias[index] = filall.Fa_nombre;
                if( (filall.HistoricoVenta == 0) || (filall.HistoricoVenta == 1 && filall.Fa_nombre == "EXHIBIDOR") ){
                    arrg_check_s['chek_'+filall.Producto] = true;
                }else{
                    arrg_check_s['chek_'+filall.Producto] = false;
                }
            });
            let arrg_ls_familias = arrg_familias.filter((item,index)=>{
                return arrg_familias.indexOf(item) === index;
            });
            Cargar_Pedido(arrg_ls_Pedido,Cantida_SKU,TotalPedido);
            DB_FiltroFamilia(arrg_ls_familias);
        });
    });
}
function roundToTwo(num) {
    return +(Math.round(num + "e+2")  + "e-2");
}