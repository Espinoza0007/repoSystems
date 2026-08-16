var indexes_clientes = new Array();
var Recordar_Fpais = "";
var Recordar_Fdivision = "";
var Recordar_Fcanal = "";

var estadoZoomGrupo = new Array();var estadoZoomGrupoB = new Array();var estadoZoomGrupoC = new Array();var estadoZoomGrupoD = new Array();
var estadoZoomGrupoE = new Array();var estadoZoomGrupoF = new Array();
var fila_temporal = new Array();var fila_temporalB = new Array();var fila_temporalC = new Array();var fila_temporalD = new Array();
var fila_temporalE = new Array();var fila_temporalF = new Array();
var fila_temporaldos = new Array();var fila_temporaldosB = new Array();var fila_temporaldosC = new Array();var fila_temporaldosD = new Array();
var fila_temporaldosE = new Array();var fila_temporaldosF = new Array();
var nuevoArray = new Array(2);var nuevoArrayB = new Array(2);var nuevoArrayC = new Array(2);var nuevoArrayD = new Array(2);
var nuevoArrayE = new Array(2);var nuevoArrayF = new Array(2);
var cargaSolo = 0;
$(document).ready(function(e){
    
    document.fullscreenEnabled = document.fullscreenEnabled || document.mozFullScreenEnabled || document.documentElement.webkitRequestFullScreen;
    var body = $('html,body');
    CargarFULLCOMPLEMENTOS();


    $(document).on("click", ".zoomUNO", function() {

        var id_zoomUNO = $(this).attr("id");
        id_zoomUNO = id_zoomUNO.substring(13,id_zoomUNO.length);
        var pais = '';
        var division = '';
        pais = $("#np"+id_zoomUNO).html();  
        division = $("#nd"+id_zoomUNO).html();


        if(estadoZoomGrupo[id_zoomUNO] == 1){
            var estadoCerrar = 0;
            estadoZoomGrupo[id_zoomUNO] = 0;
            console.log('------------------------------------------------------');
            var Grupo = 1;
            var CuntosAbiertos = 0;
            for(k=0;k<nuevoArray[id_zoomUNO].length;k++){
                console.log('Fila => '+id_zoomUNO+' Grupo# '+ Grupo +' Estado => ' + nuevoArray[id_zoomUNO][k]);
                if(nuevoArray[id_zoomUNO][k] === 1){
                    CuntosAbiertos++;
                    nuevoArray[id_zoomUNO][k] = 0;
                }else{
                    CuntosAbiertos = CuntosAbiertos + 0;
                }
                Grupo++;
            }

            if(CuntosAbiertos > 0){
                $("#filaG_"+id_zoomUNO+"0").closest('tr').before(fila_temporal[id_zoomUNO]);
                $('.fila_tempoGru'+id_zoomUNO).remove();
                $('.fila_ru'+id_zoomUNO).remove();
            }else{
                $("#filaG_"+id_zoomUNO+"0").closest('tr').before(fila_temporal[id_zoomUNO]);
                $('.fila_tempoGru'+id_zoomUNO).remove();
            }
        }else{
            // console.log('desplegando por grupo');
            estadoZoomGrupo[id_zoomUNO] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportunozu/zoom_uno',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    var filaG ='';
                    fila_temporal[id_zoomUNO] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralUnoZommUno.forEach(function(filall,index, arrgfilall){
                            nuevoArray[id_zoomUNO][index] = 0;
                            conta_MAX++;
                            if(index === 0){
                                tabla_html += `
                                <tr id="filaG_${id_zoomUNO}${index}" class="fila_tempoGru${id_zoomUNO}">
                                    <td><button type="button" class="btn btn_abierto zoomUNO" id="despliegeUNO_${id_zoomUNO}"><span id='span_zoomGru${id_zoomUNO}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorU" id="np${id_zoomUNO}">${filall.Nombre_Pais}</td>
                                    <td class="borde_de" id="nd${id_zoomUNO}">${filall.Division}</td>
                                    <td class="borde_de css_grupo"><button type="button" class="btn btn-light btn_cerrado zoomDOS" id="${id_zoomUNO}_despliegeDOS_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ng${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_de"></td>
                                    <td class="borde_de">${filall.totalpdv}</td>
                                    <td class="borde_de">${filall.pdvactualizados}</td>
                                    <td>${filall.NOpdvactualizados}</td>
                                    <td class="fila_colorAU">${filall.avance} %</td>
                                </tr>`;
                            }else{
                                tabla_html += `
                                <tr id="filaG_${id_zoomUNO}${index}" class="fila_tempoGru${id_zoomUNO}">
                                    <td></td>
                                    <td class="fila_colorU"></td>
                                    <td class="borde_de"></td>
                                    <td class="borde_de css_grupo"><button type="button" class="btn btn-light btn_cerrado zoomDOS" id="${id_zoomUNO}_despliegeDOS_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ng${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_de"></td>
                                    <td class="borde_de">${filall.totalpdv}</td>
                                    <td class="borde_de">${filall.pdvactualizados}</td>
                                    <td>${filall.NOpdvactualizados}</td>
                                    <td class="fila_colorAU">${filall.avance} %</td>
                                </tr>`;
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo fila_tempoGru${id_zoomUNO}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorU"></td>
                            <td class="fila_colorUT">TOTAL ${division}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tpv}</td>
                            <td class="fila_colorUT">${_resp.tpvAC}</td>
                            <td class="fila_colorUT">${_resp.tpvACNO}</td>
                            <td class="fila_colorAU">${_resp.tpvPOR} %</td>
                        </tr>`;
                        fila_temporal[id_zoomUNO]= `
                        <tr id="fila_${id_zoomUNO}">
                            <td><button type="button" class="btn btn_cerrado zoomUNO" id="despliegeUNO_${id_zoomUNO}"><span id='span_zoomGru${id_zoomUNO}' class='fa fa-search-plus fa-lg'></span></button></td>
                            <td class="fila_colorU" id="np${id_zoomUNO}">${pais}</td>
                            <td class="borde_de" id="nd${id_zoomUNO}">${division}</td>
                            <td class="borde_de"></td>
                            <td class="borde_de"></td>
                            <td class="borde_de">${_resp.tpv}</td>
                            <td class="borde_de">${_resp.tpvAC}</td>
                            <td>${_resp.tpvACNO}</td>
                            <td class="fila_colorAU">${_resp.tpvPOR} %</td>
                        </tr>`;
                        $("#fila_"+id_zoomUNO).closest('tr').after(tabla_html);
                        $('#fila_'+id_zoomUNO).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });

    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /*ZOMM DOS PARA RUTAS*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    $(document).on("click", ".zoomDOS", function() {

        var id_zoomUNO = $(this).attr("id");

        var result = id_zoomUNO.split("_");
        var fila_Tab = result[0];
        var filaSub_Tab = result[2];
        var unidofilas = fila_Tab+filaSub_Tab;

        fila_Tab = parseInt(fila_Tab);
        filaSub_Tab = parseInt(filaSub_Tab);

        var pais = '';
        var division = '';
        var grupo = '';

        division = $("#nd"+fila_Tab).html();
        grupo = $("#ng"+unidofilas).html();//Grupo Fila + FilaSUB
        pais = $("#np"+fila_Tab).html();
        

        if(nuevoArray[fila_Tab][filaSub_Tab] == 1){
            nuevoArray[fila_Tab][filaSub_Tab] = 0;
            $("#filaG_"+unidofilas).closest('tr').before(fila_temporaldos[unidofilas]);
            $('.fila_tempoGru'+unidofilas).remove();

        }else{
            nuevoArray[fila_Tab][filaSub_Tab] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            datas.push({name: 'grupo',value: grupo});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportdoszu/zoom_dos',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    fila_temporaldos[unidofilas] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralUnoZommDos.forEach(function(filall,index, arrgfilall){
            
                            conta_MAX++;
                            if(filaSub_Tab === 0 && index === 0){
                                // console.log('se cumpli primera fila');
                                tabla_html += `
                                <tr id="filaG_${unidofilas}" class="fila_tempoGru${unidofilas} fila_ru${fila_Tab}">
                                    <td><button type="button" class="btn btn_abierto zoomUNO" id="despliegeUNO_${fila_Tab}"><span id='span_zoomGru${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorU" id="np${fila_Tab}">${filall.Nombre_Pais}</td>
                                    <td class="borde_de" id="nd${fila_Tab}">${filall.Division}</td>
                                    <td class="borde_de css_grupo_sin_borde"><button type="button" class="btn btn-light btn_abierto zoomDOS" id="${fila_Tab}_despliegeDOS_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ng${unidofilas}">${filall.Grupo}</span></td>
                                    <td class="borde_de">${filall.Nombre_Ruta}</td>
                                    <td class="borde_de">${filall.totalpdv}</td>
                                    <td class="borde_de">${filall.pdvactualizados}</td>
                                    <td>${filall.NOpdvactualizados}</td>
                                    <td class="fila_colorAU">${filall.avance} %</td>
                                </tr>`;

                            }else{

                                if(index === 0){
                                    tabla_html += `
                                    <tr id="filaG_${unidofilas}" class="fila_tempoGru${unidofilas} fila_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorU"></td>
                                        <td class="borde_de"></td>
                                        <td class="borde_de css_grupo_sin_borde"><button type="button" class="btn btn-light btn_abierto zoomDOS" id="${fila_Tab}_despliegeDOS_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ng${unidofilas}">${filall.Grupo}</span></td>
                                        <td class="borde_de">${filall.Nombre_Ruta}</td>
                                        <td class="borde_de">${filall.totalpdv}</td>
                                        <td class="borde_de">${filall.pdvactualizados}</td>
                                        <td>${filall.NOpdvactualizados}</td>
                                        <td class="fila_colorAU">${filall.avance} %</td>
                                    </tr>`;
                                }else{
                                    tabla_html += `
                                    <tr id="filaR_${unidofilas}" class="fila_tempoGru${unidofilas} fila_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorU"></td>
                                        <td class="borde_de"></td>
                                        <td class="borde_de css_grupo_sin_borde"></td>
                                        <td class="borde_de">${filall.Nombre_Ruta}</td>
                                        <td class="borde_de">${filall.totalpdv}</td>
                                        <td class="borde_de">${filall.pdvactualizados}</td>
                                        <td>${filall.NOpdvactualizados}</td>
                                        <td class="fila_colorAU">${filall.avance} %</td>
                                    </tr>`;
                                }

                            }


                        });

                        tabla_html += `
                        <tr class="filazoom_grupo fila_tempoGru${unidofilas} fila_ru${fila_Tab}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorU"></td>
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUT">TOTAL ${grupo}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tpv}</td>
                            <td class="fila_colorUT">${_resp.tpvAC}</td>
                            <td class="fila_colorUT">${_resp.tpvACNO}</td>
                            <td class="fila_colorAU">${_resp.tpvPOR} %</td>
                        </tr>`;

                        if(filaSub_Tab === 0){
                            fila_temporaldos[unidofilas] = `
                            <tr id="filaG_${unidofilas}" class="fila_tempoGru${fila_Tab}">
                                <td><button type="button" class="btn btn_abierto zoomUNO" id="despliegeUNO_${fila_Tab}"><span id='span_zoomGru${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                <td class="fila_colorU" id="np${fila_Tab}">${pais}</td>
                                <td class="borde_de" id="nd${fila_Tab}">${division}</td>
                                <td class="borde_de css_grupo"><button type="button" class="btn btn-light btn_cerrado zoomDOS" id="${fila_Tab}_despliegeDOS_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ng${unidofilas}">${grupo}</span></td>
                                <td class="borde_de"></td>
                                <td class="borde_de">${_resp.tpv}</td>
                                <td class="borde_de">${_resp.tpvAC}</td>
                                <td>${_resp.tpvACNO}</td>
                                <td class="fila_colorAU">${_resp.tpvPOR} %</td>
                            </tr>`;
                        }else{
                            fila_temporaldos[unidofilas] = `
                            <tr id="filaG_${unidofilas}" class="fila_tempoGru${fila_Tab}">
                                <td></td>
                                <td class="fila_colorU"></td>
                                <td class="borde_de"></td>
                                <td class="borde_de css_grupo"><button type="button" class="btn btn-light btn_cerrado zoomDOS" id="${fila_Tab}_despliegeDOS_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ng${unidofilas}">${grupo}</span></td>
                                <td class="borde_de"></td>
                                <td class="borde_de">${_resp.tpv}</td>
                                <td class="borde_de">${_resp.tpvAC}</td>
                                <td>${_resp.tpvACNO}</td>
                                <td class="fila_colorAU">${_resp.tpvPOR} %</td>
                            </tr>`;
                        }

                        $("#filaG_"+unidofilas).closest('tr').after(tabla_html);
                        $('#filaG_'+unidofilas).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });
    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /*FINAL TABLA UNO GENERAL ENCUESTA*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /*INICIO TABLA CON O SIN EXHIBIDOR*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    $(document).on("click", ".zoomUNOB", function() {

        var id_zoomUNO = $(this).attr("id");
        id_zoomUNO = id_zoomUNO.substring(14,id_zoomUNO.length);
        var pais = '';
        var division = '';
        pais = $("#npB"+id_zoomUNO).html();  
        division = $("#ndB"+id_zoomUNO).html();

        if(estadoZoomGrupoB[id_zoomUNO] == 1){
            var estadoCerrar = 0;
            estadoZoomGrupoB[id_zoomUNO] = 0;
            console.log('------------------------------------------------------');
            var Grupo = 1;
            var CuntosAbiertos = 0;
            for(k=0;k<nuevoArrayB[id_zoomUNO].length;k++){
                console.log('Fila => '+id_zoomUNO+' Grupo# '+ Grupo +' Estado => ' + nuevoArrayB[id_zoomUNO][k]);
                if(nuevoArrayB[id_zoomUNO][k] === 1){
                    CuntosAbiertos++;
                    nuevoArrayB[id_zoomUNO][k] = 0;
                }else{
                    CuntosAbiertos = CuntosAbiertos + 0;
                }
                Grupo++;
            }



            if(CuntosAbiertos > 0){
                $("#filaGB_"+id_zoomUNO+"0").closest('tr').before(fila_temporalB[id_zoomUNO]);
                $('.filaB_tempoGru'+id_zoomUNO).remove();
                $('.filaB_ru'+id_zoomUNO).remove();
            }else{
                $("#filaGB_"+id_zoomUNO+"0").closest('tr').before(fila_temporalB[id_zoomUNO]);
                $('.filaB_tempoGru'+id_zoomUNO).remove();
            }
        }else{
            // console.log('desplegando por grupo');
            estadoZoomGrupoB[id_zoomUNO] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportdoszudos/zoomb_uno',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    var filaG ='';
                    fila_temporalB[id_zoomUNO] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralDosZoomUno.forEach(function(filall,index, arrgfilall){
                            nuevoArrayB[id_zoomUNO][index] = 0;
                            conta_MAX++;
                            if(index === 0){
                                tabla_html += `
                                <tr id="filaGB_${id_zoomUNO}${index}" class="filaB_tempoGru${id_zoomUNO}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOB" id="despliegeUNOB_${id_zoomUNO}"><span id='span_zoomGruB${id_zoomUNO}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUB" id="npB${id_zoomUNO}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deB" id="ndB${id_zoomUNO}">${filall.Division}</td>
                                    <td class="borde_deB css_grupoB"><button type="button" class="btn btn-light btn_cerrado zoomDOSB" id="${id_zoomUNO}_despliegeDOSB_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngB${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deB"></td>
                                    <td class="borde_deB">${filall.pdvactualizados}</td>
                                    <td class="borde_deB">${filall.sinexhibidores}</td>
                                    <td>${filall.conexhibidores}</td>
                                    <td class="fila_colorAUB">${filall.avanceSin} %</td>
                                    <td class="fila_colorAUB">${filall.avanceCon} %</td>
                                </tr>`;
                            }else{
                                tabla_html += `
                                <tr id="filaGB_${id_zoomUNO}${index}" class="filaB_tempoGru${id_zoomUNO}">
                                    <td></td>
                                    <td class="fila_colorUB"></td>
                                    <td class="borde_deB"></td>
                                    <td class="borde_deB css_grupoB"><button type="button" class="btn btn-light btn_cerrado zoomDOSB" id="${id_zoomUNO}_despliegeDOSB_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngB${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deB"></td>
                                    <td class="borde_deB">${filall.pdvactualizados}</td>
                                    <td class="borde_deB">${filall.sinexhibidores}</td>
                                    <td>${filall.conexhibidores}</td>
                                    <td class="fila_colorAUB">${filall.avanceSin} %</td>
                                    <td class="fila_colorAUB">${filall.avanceCon} %</td>
                                </tr>`;
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaB_tempoGru${id_zoomUNO}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUB"></td>
                            <td class="fila_colorUT">TOTAL ${division}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.totalpdvA}</td>
                            <td class="fila_colorUT">${_resp.tpvSIN}</td>
                            <td class="fila_colorUT">${_resp.tpvCON}</td>
                            <td class="fila_colorAUB">${_resp.tpvPORSIN}</td>
                            <td class="fila_colorAUB">${_resp.tpvPORCON} %</td>
                        </tr>`;
                        fila_temporalB[id_zoomUNO]= `
                        <tr id="filaB_${id_zoomUNO}">
                            <td><button type="button" class="btn btn_cerrado zoomUNOB" id="despliegeUNOB_${id_zoomUNO}"><span id='span_zoomGruB${id_zoomUNO}' class='fa fa-search-plus fa-lg'></span></button></td>
                            <td class="fila_colorUB" id="npB${id_zoomUNO}">${pais}</td>
                            <td class="borde_deB" id="ndB${id_zoomUNO}">${division}</td>
                            <td class="borde_deB"></td>
                            <td class="borde_deB"></td>
                            <td class="borde_deB">${_resp.totalpdvA}</td>
                            <td class="borde_deB">${_resp.tpvSIN}</td>
                            <td>${_resp.tpvCON}</td>
                            <td class="fila_colorAUB">${_resp.tpvPORSIN} %</td>
                            <td class="fila_colorAUB">${_resp.tpvPORCON} %</td>
                        </tr>`;
                        $("#filaB_"+id_zoomUNO).closest('tr').after(tabla_html);
                        $('#filaB_'+id_zoomUNO).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });

    $(document).on("click", ".zoomDOSB", function() {

        var id_zoomUNO = $(this).attr("id");
        // console.log('id ' + id_zoomUNO)
        var result = id_zoomUNO.split("_");
        var fila_Tab = result[0];
        var filaSub_Tab = result[2];
        var unidofilas = fila_Tab+filaSub_Tab;

        fila_Tab = parseInt(fila_Tab);
        filaSub_Tab = parseInt(filaSub_Tab);

        // console.log(fila_Tab);
        // console.log(filaSub_Tab);

        var pais = '';
        var division = '';
        var grupo = '';

        division = $("#ndB"+fila_Tab).html();
        grupo = $("#ngB"+unidofilas).html();//Grupo Fila + FilaSUB
        pais = $("#npB"+fila_Tab).html();
        

        if(nuevoArrayB[fila_Tab][filaSub_Tab] == 1){
            nuevoArrayB[fila_Tab][filaSub_Tab] = 0;
            $("#filaGB_"+unidofilas).closest('tr').before(fila_temporaldosB[unidofilas]);
            $('.filaB_tempoGru'+unidofilas).remove();

        }else{
            nuevoArrayB[fila_Tab][filaSub_Tab] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            datas.push({name: 'grupo',value: grupo});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportconsindos/zoomb_dos',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    fila_temporaldosB[unidofilas] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralDosZoomDos.forEach(function(filall,index, arrgfilall){
            
                            conta_MAX++;
                            if(filaSub_Tab === 0 && index === 0){
                                // console.log('se cumpli primera fila');
                                tabla_html += `
                                <tr id="filaGB_${unidofilas}" class="filaB_tempoGru${unidofilas} filaB_ru${fila_Tab}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOB" id="despliegeUNOB_${fila_Tab}"><span id='span_zoomGruB${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUB" id="npB${fila_Tab}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deB" id="ndB${fila_Tab}">${filall.Division}</td>
                                    <td class="borde_deB css_grupo_sin_bordeB"><button type="button" class="btn btn-light btn_abierto zoomDOSB" id="${fila_Tab}_despliegeDOSB_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngB${unidofilas}">${filall.Grupo}</span></td>
                                    <td class="borde_deB">${filall.Nombre_Ruta}</td>
                                    <td class="borde_deB">${filall.pdvactualizados}</td>
                                    <td class="borde_deB">${filall.sinexhibidores}</td>
                                    <td>${filall.conexhibidores}</td>
                                    <td class="fila_colorAUB">${filall.avanceSin} %</td>
                                    <td class="fila_colorAUB">${filall.avanceCon} %</td>
                                </tr>`;

                            }else{

                                if(index === 0){
                                    tabla_html += `
                                    <tr id="filaGB_${unidofilas}" class="filaB_tempoGru${unidofilas} filaB_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUB"></td>
                                        <td class="borde_deB"></td>
                                        <td class="borde_deB css_grupo_sin_bordeB"><button type="button" class="btn btn-light btn_abierto zoomDOSB" id="${fila_Tab}_despliegeDOSB_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngB${unidofilas}">${filall.Grupo}</span></td>
                                        <td class="borde_deB">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deB">${filall.pdvactualizados}</td>
                                        <td class="borde_deB">${filall.sinexhibidores}</td>
                                        <td>${filall.conexhibidores}</td>
                                        <td class="fila_colorAUB">${filall.avanceSin} %</td>
                                        <td class="fila_colorAUB">${filall.avanceCon} %</td>
                                    </tr>`;
                                }else{
                                    tabla_html += `
                                    <tr id="filaRB_${unidofilas}" class="filaB_tempoGru${unidofilas} filaB_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUB"></td>
                                        <td class="borde_deB"></td>
                                        <td class="borde_deB css_grupo_sin_bordeB"></td>
                                        <td class="borde_deB">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deB">${filall.pdvactualizados}</td>
                                        <td class="borde_deB">${filall.sinexhibidores}</td>
                                        <td>${filall.conexhibidores}</td>
                                        <td class="fila_colorAUB">${filall.avanceSin} %</td>
                                        <td class="fila_colorAUB">${filall.avanceCon} %</td>
                                    </tr>`;
                                }

                            }


                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaB_tempoGru${unidofilas} filaB_ru${fila_Tab}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUB"></td>
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUT">TOTAL ${grupo}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.totalpdvA}</td>
                            <td class="fila_colorUT">${_resp.tpvSIN}</td>
                            <td class="fila_colorUT">${_resp.tpvCON}</td>
                            <td class="fila_colorAUB">${_resp.tpvPORSIN} %</td>
                            <td class="fila_colorAUB">${_resp.tpvPORCON} %</td>
                        </tr>`;

                        if(filaSub_Tab === 0){
                            fila_temporaldosB[unidofilas] = `
                            <tr id="filaGB_${unidofilas}" class="filaB_tempoGru${fila_Tab}">
                                <td><button type="button" class="btn btn_abierto zoomUNOB" id="despliegeUNOB_${fila_Tab}"><span id='span_zoomGruB${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                <td class="fila_colorUB" id="npB${fila_Tab}">${pais}</td>
                                <td class="borde_deB" id="ndB${fila_Tab}">${division}</td>
                                <td class="borde_de css_grupoB"><button type="button" class="btn btn-light btn_cerrado zoomDOSB" id="${fila_Tab}_despliegeDOSB_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngB${unidofilas}">${grupo}</span></td>
                                <td class="borde_deB"></td>
                                <td class="borde_deB">${_resp.totalpdvA}</td>
                                <td class="borde_deB">${_resp.tpvSIN}</td>
                                <td>${_resp.tpvCON}</td>
                                <td class="fila_colorAUB">${_resp.tpvPORSIN} %</td>
                                <td class="fila_colorAUB">${_resp.tpvPORCON} %</td>
                            </tr>`;
                        }else{
                            fila_temporaldosB[unidofilas] = `
                            <tr id="filaGB_${unidofilas}" class="filaB_tempoGru${fila_Tab}">
                                <td></td>
                                <td class="fila_colorUB"></td>
                                <td class="borde_deB"></td>
                                <td class="borde_deB css_grupoB"><button type="button" class="btn btn-light btn_cerrado zoomDOSB" id="${fila_Tab}_despliegeDOSB_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngB${unidofilas}">${grupo}</span></td>
                                <td class="borde_deB"></td>
                                <td class="borde_deB">${_resp.totalpdvA}</td>
                                <td class="borde_deB">${_resp.tpvSIN}</td>
                                <td>${_resp.tpvCON}</td>
                                <td class="fila_colorAUB">${_resp.tpvPORSIN} %</td>
                                <td class="fila_colorAUB">${_resp.tpvPORCON} %</td>
                            </tr>`;
                        }

                        $("#filaGB_"+unidofilas).closest('tr').after(tabla_html);
                        $('#filaGB_'+unidofilas).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });

    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /*INICIO TABLA POR TIPO DE ACTUALIZACION*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    $(document).on("click", ".zoomUNOC", function() {

        var id_zoomUNO = $(this).attr("id");
        id_zoomUNO = id_zoomUNO.substring(14,id_zoomUNO.length);
        var pais = '';
        var division = '';
        pais = $("#npC"+id_zoomUNO).html();  
        division = $("#ndC"+id_zoomUNO).html();

        if(estadoZoomGrupoC[id_zoomUNO] == 1){
            var estadoCerrar = 0;
            estadoZoomGrupoC[id_zoomUNO] = 0;
            console.log('------------------------------------------------------');
            var Grupo = 1;
            var CuntosAbiertos = 0;
            for(k=0;k<nuevoArrayC[id_zoomUNO].length;k++){
                console.log('Fila => '+id_zoomUNO+' Grupo# '+ Grupo +' Estado => ' + nuevoArrayC[id_zoomUNO][k]);
                if(nuevoArrayC[id_zoomUNO][k] === 1){
                    CuntosAbiertos++;
                    nuevoArrayC[id_zoomUNO][k] = 0;
                }else{
                    CuntosAbiertos = CuntosAbiertos + 0;
                }
                Grupo++;
            }



            if(CuntosAbiertos > 0){
                $("#filaGC_"+id_zoomUNO+"0").closest('tr').before(fila_temporalC[id_zoomUNO]);
                $('.filaC_tempoGru'+id_zoomUNO).remove();
                $('.filaC_ru'+id_zoomUNO).remove();
            }else{
                $("#filaGC_"+id_zoomUNO+"0").closest('tr').before(fila_temporalC[id_zoomUNO]);
                $('.filaC_tempoGru'+id_zoomUNO).remove();
            }
        }else{
            // console.log('desplegando por grupo');
            estadoZoomGrupoC[id_zoomUNO] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportdoszutres/zoomc_uno',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    var filaG ='';
                    fila_temporalC[id_zoomUNO] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralTresZoomUno.forEach(function(filall,index, arrgfilall){
                            nuevoArrayC[id_zoomUNO][index] = 0;
                            conta_MAX++;
                            if(index === 0){
                                tabla_html += `
                                <tr id="filaGC_${id_zoomUNO}${index}" class="filaC_tempoGru${id_zoomUNO}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOC" id="despliegeUNOC_${id_zoomUNO}"><span id='span_zoomGruC${id_zoomUNO}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUC" id="npC${id_zoomUNO}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deC" id="ndC${id_zoomUNO}">${filall.Division}</td>
                                    <td class="borde_deC css_grupoC"><button type="button" class="btn btn-light btn_cerrado zoomDOSC" id="${id_zoomUNO}_despliegeDOSC_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngC${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deC"></td>
                                    <td class="borde_deC">${filall.totalpdvexh}</td>
                                    <td class="borde_deC">${filall.exhquetiene}</td>
                                    <td class="borde_deC">${filall.exhdevueltos}</td>
                                    <td class="borde_deC">${filall.exnuevos}</td>
                                </tr>`;
                            }else{
                                tabla_html += `
                                <tr id="filaGC_${id_zoomUNO}${index}" class="filaC_tempoGru${id_zoomUNO}">
                                    <td></td>
                                    <td class="fila_colorUC"></td>
                                    <td class="borde_deC"></td>
                                    <td class="borde_deC css_grupoC"><button type="button" class="btn btn-light btn_cerrado zoomDOSC" id="${id_zoomUNO}_despliegeDOSC_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngC${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deC"></td>
                                    <td class="borde_deC">${filall.totalpdvexh}</td>
                                    <td class="borde_deC">${filall.exhquetiene}</td>
                                    <td class="borde_deC">${filall.exhdevueltos}</td>
                                    <td class="borde_deC">${filall.exnuevos}</td>
                                </tr>`;
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaC_tempoGru${id_zoomUNO}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUC"></td>
                            <td class="fila_colorUT">TOTAL ${division}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tpvCantidadExh}</td>
                            <td class="fila_colorUT">${_resp.tpvExhquetiene}</td>
                            <td class="fila_colorUT">${_resp.tpvExhdevueltos}</td>
                            <td class="fila_colorUT">${_resp.tpvExhnuevos}</td>
                        </tr>`;
                        fila_temporalC[id_zoomUNO]= `
                        <tr id="filaC_${id_zoomUNO}">
                            <td><button type="button" class="btn btn_cerrado zoomUNOC" id="despliegeUNOC_${id_zoomUNO}"><span id='span_zoomGruC${id_zoomUNO}' class='fa fa-search-plus fa-lg'></span></button></td>
                            <td class="fila_colorUC" id="npC${id_zoomUNO}">${pais}</td>
                            <td class="borde_deC" id="ndC${id_zoomUNO}">${division}</td>
                            <td class="borde_deC"></td>
                            <td class="borde_deC"></td>
                            <td class="borde_deC">${_resp.tpvCantidadExh}</td>
                            <td class="borde_deC">${_resp.tpvExhquetiene}</td>
                            <td class="borde_deC">${_resp.tpvExhdevueltos}</td>
                            <td class="borde_deC">${_resp.tpvExhnuevos}</td>
                        </tr>`;
                        $("#filaC_"+id_zoomUNO).closest('tr').after(tabla_html);
                        $('#filaC_'+id_zoomUNO).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });


    $(document).on("click", ".zoomDOSC", function() {

        var id_zoomUNO = $(this).attr("id");
        // console.log('id ' + id_zoomUNO)
        var result = id_zoomUNO.split("_");
        var fila_Tab = result[0];
        var filaSub_Tab = result[2];
        var unidofilas = fila_Tab+filaSub_Tab;

        fila_Tab = parseInt(fila_Tab);
        filaSub_Tab = parseInt(filaSub_Tab);

        // console.log(fila_Tab);
        // console.log(filaSub_Tab);

        var pais = '';
        var division = '';
        var grupo = '';

        division = $("#ndC"+fila_Tab).html();
        grupo = $("#ngC"+unidofilas).html();//Grupo Fila + FilaSUB
        pais = $("#npC"+fila_Tab).html();
        

        if(nuevoArrayC[fila_Tab][filaSub_Tab] == 1){
            nuevoArrayC[fila_Tab][filaSub_Tab] = 0;
            $("#filaGC_"+unidofilas).closest('tr').before(fila_temporaldosC[unidofilas]);
            $('.filaC_tempoGru'+unidofilas).remove();

        }else{
            nuevoArrayC[fila_Tab][filaSub_Tab] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            datas.push({name: 'grupo',value: grupo});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reporttipotres/zoomc_dos',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    fila_temporaldosC[unidofilas] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralTresZoomDos.forEach(function(filall,index, arrgfilall){
            
                            conta_MAX++;
                            if(filaSub_Tab === 0 && index === 0){
                                // console.log('se cumpli primera fila');
                                tabla_html += `
                                <tr id="filaGC_${unidofilas}" class="filaC_tempoGru${unidofilas} filaC_ru${fila_Tab}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOC" id="despliegeUNOC_${fila_Tab}"><span id='span_zoomGruC${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUC" id="npC${fila_Tab}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deC" id="ndC${fila_Tab}">${filall.Division}</td>
                                    <td class="borde_deC css_grupo_sin_bordeC"><button type="button" class="btn btn-light btn_abierto zoomDOSC" id="${fila_Tab}_despliegeDOSC_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngC${unidofilas}">${filall.Grupo}</span></td>
                                    <td class="borde_deC">${filall.Nombre_Ruta}</td>
                                    <td class="borde_deC">${filall.totalpdvexh}</td>
                                    <td class="borde_deC">${filall.exhquetiene}</td>
                                    <td class="borde_deC">${filall.exhdevueltos}</td>
                                    <td class="borde_deC">${filall.exnuevos}</td>
                                </tr>`;

                            }else{

                                if(index === 0){
                                    tabla_html += `
                                    <tr id="filaGC_${unidofilas}" class="filaC_tempoGru${unidofilas} filaC_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUC"></td>
                                        <td class="borde_deC"></td>
                                        <td class="borde_deC css_grupo_sin_bordeC"><button type="button" class="btn btn-light btn_abierto zoomDOSC" id="${fila_Tab}_despliegeDOSC_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngC${unidofilas}">${filall.Grupo}</span></td>
                                        <td class="borde_deC">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deC">${filall.totalpdvexh}</td>
                                        <td class="borde_deC">${filall.exhquetiene}</td>
                                        <td class="borde_deC">${filall.exhdevueltos}</td>
                                        <td class="borde_deC">${filall.exnuevos}</td>
                                    </tr>`;
                                }else{
                                    tabla_html += `
                                    <tr id="filaRC_${unidofilas}" class="filaC_tempoGru${unidofilas} filaC_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUC"></td>
                                        <td class="borde_deC"></td>
                                        <td class="borde_deC css_grupo_sin_bordeC"></td>
                                        <td class="borde_deC">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deC">${filall.totalpdvexh}</td>
                                        <td class="borde_deC">${filall.exhquetiene}</td>
                                        <td class="borde_deC">${filall.exhdevueltos}</td>
                                        <td class="borde_deC">${filall.exnuevos}</td>
                                    </tr>`;
                                }
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaC_tempoGru${unidofilas} filaC_ru${fila_Tab}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUC"></td>
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUT">TOTAL ${grupo}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tpvCantidadExh}</td>
                            <td class="fila_colorUT">${_resp.tpvExhquetiene}</td>
                            <td class="fila_colorUT">${_resp.tpvExhdevueltos}</td>
                            <td class="fila_colorUT">${_resp.tpvExhnuevos}</td>
                        </tr>`;

                        if(filaSub_Tab === 0){
                            fila_temporaldosC[unidofilas] = `
                            <tr id="filaGC_${unidofilas}" class="filaC_tempoGru${fila_Tab}">
                                <td><button type="button" class="btn btn_abierto zoomUNOC" id="despliegeUNOC_${fila_Tab}"><span id='span_zoomGruC${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                <td class="fila_colorUC" id="npC${fila_Tab}">${pais}</td>
                                <td class="borde_deC" id="ndC${fila_Tab}">${division}</td>
                                <td class="borde_de css_grupoC"><button type="button" class="btn btn-light btn_cerrado zoomDOSC" id="${fila_Tab}_despliegeDOSC_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngC${unidofilas}">${grupo}</span></td>
                                <td class="borde_deC"></td>
                                <td class="borde_deC">${_resp.tpvCantidadExh}</td>
                                <td class="borde_deC">${_resp.tpvExhquetiene}</td>
                                <td class="borde_deC">${_resp.tpvExhdevueltos}</td>
                                <td class="borde_deC">${_resp.tpvExhnuevos}</td>
                            </tr>`;
                        }else{
                            fila_temporaldosC[unidofilas] = `
                            <tr id="filaGC_${unidofilas}" class="filaC_tempoGru${fila_Tab}">
                                <td></td>
                                <td class="fila_colorUC"></td>
                                <td class="borde_deC"></td>
                                <td class="borde_deC css_grupoC"><button type="button" class="btn btn-light btn_cerrado zoomDOSC" id="${fila_Tab}_despliegeDOSC_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngC${unidofilas}">${grupo}</span></td>
                                <td class="borde_deC"></td>
                                <td class="borde_deC">${_resp.tpvCantidadExh}</td>
                                <td class="borde_deC">${_resp.tpvExhquetiene}</td>
                                <td class="borde_deC">${_resp.tpvExhdevueltos}</td>
                                <td class="borde_deC">${_resp.tpvExhnuevos}</td>
                            </tr>`;
                        }

                        $("#filaGC_"+unidofilas).closest('tr').after(tabla_html);
                        $('#filaGC_'+unidofilas).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });

    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /*INICIO TABLA POR TIPO DE OBSERVACION POR EXHIBIDORES*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    $(document).on("click", ".zoomUNOD", function() {

        var id_zoomUNO = $(this).attr("id");
        id_zoomUNO = id_zoomUNO.substring(14,id_zoomUNO.length);
        var pais = '';
        var division = '';
        pais = $("#npD"+id_zoomUNO).html();  
        division = $("#ndD"+id_zoomUNO).html();

        if(estadoZoomGrupoD[id_zoomUNO] == 1){
            var estadoCerrar = 0;
            estadoZoomGrupoD[id_zoomUNO] = 0;
            console.log('------------------------------------------------------');
            var Grupo = 1;
            var CuntosAbiertos = 0;
            for(k=0;k<nuevoArrayD[id_zoomUNO].length;k++){
                console.log('Fila => '+id_zoomUNO+' Grupo# '+ Grupo +' Estado => ' + nuevoArrayD[id_zoomUNO][k]);
                if(nuevoArrayD[id_zoomUNO][k] === 1){
                    CuntosAbiertos++;
                    nuevoArrayD[id_zoomUNO][k] = 0;
                }else{
                    CuntosAbiertos = CuntosAbiertos + 0;
                }
                Grupo++;
            }



            if(CuntosAbiertos > 0){
                $("#filaGD_"+id_zoomUNO+"0").closest('tr').before(fila_temporalD[id_zoomUNO]);
                $('.filaD_tempoGru'+id_zoomUNO).remove();
                $('.filaD_ru'+id_zoomUNO).remove();
            }else{
                $("#filaGD_"+id_zoomUNO+"0").closest('tr').before(fila_temporalD[id_zoomUNO]);
                $('.filaD_tempoGru'+id_zoomUNO).remove();
            }
        }else{
            // console.log('desplegando por grupo');
            estadoZoomGrupoD[id_zoomUNO] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportdoszucuatro/zoomd_uno',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    var filaG ='';
                    fila_temporalD[id_zoomUNO] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralCuatroZoomUno.forEach(function(filall,index, arrgfilall){
                            nuevoArrayD[id_zoomUNO][index] = 0;
                            conta_MAX++;
                            if(index === 0){
                                tabla_html += `
                                <tr id="filaGD_${id_zoomUNO}${index}" class="filaD_tempoGru${id_zoomUNO}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOD" id="despliegeUNOD_${id_zoomUNO}"><span id='span_zoomGruD${id_zoomUNO}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUD" id="npD${id_zoomUNO}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deD" id="ndD${id_zoomUNO}">${filall.Division}</td>
                                    <td class="borde_deD css_grupoD"><button type="button" class="btn btn-light btn_cerrado zoomDOSD" id="${id_zoomUNO}_despliegeDOSD_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngD${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deD"></td>
                                    <td class="borde_deD">${filall.exhquetiene}</td>
                                    <td class="borde_deD">${filall.exhdesechados}</td>
                                    <td class="borde_deD">${filall.exhinvadido}</td>
                                    <td class="borde_deD">${filall.exhmalubicado}</td>
                                    <td class="borde_deD">${filall.exhretirado}</td>
                                    <td class="borde_deD">${filall.exhvisibles}</td>
                                    <td class="borde_deD">${filall.exhnecesitare}</td>
                                </tr>`;
                            }else{
                                tabla_html += `
                                <tr id="filaGD_${id_zoomUNO}${index}" class="filaD_tempoGru${id_zoomUNO}">
                                    <td></td>
                                    <td class="fila_colorUD"></td>
                                    <td class="borde_deD"></td>
                                    <td class="borde_deD css_grupoD"><button type="button" class="btn btn-light btn_cerrado zoomDOSD" id="${id_zoomUNO}_despliegeDOSD_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngD${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deD"></td>
                                    <td class="borde_deD">${filall.exhquetiene}</td>
                                    <td class="borde_deD">${filall.exhdesechados}</td>
                                    <td class="borde_deD">${filall.exhinvadido}</td>
                                    <td class="borde_deD">${filall.exhmalubicado}</td>
                                    <td class="borde_deD">${filall.exhretirado}</td>
                                    <td class="borde_deD">${filall.exhvisibles}</td>
                                    <td class="borde_deD">${filall.exhnecesitare}</td>
                                </tr>`;
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaD_tempoGru${id_zoomUNO}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUD"></td>
                            <td class="fila_colorUT">TOTAL ${division}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tpvExhquetiene}</td>
                            <td class="fila_colorUT">${_resp.tpvExhdesechados}</td>
                            <td class="fila_colorUT">${_resp.tpvExhinvadidos}</td>
                            <td class="fila_colorUT">${_resp.tpvExhmalubicados}</td>
                            <td class="fila_colorUT">${_resp.tpvExhretirados}</td>
                            <td class="fila_colorUT">${_resp.tpvExhvisibleyacce}</td>
                            <td class="fila_colorUT">${_resp.tpvExhnecesitar}</td>
                        </tr>`;
                        fila_temporalD[id_zoomUNO]= `
                        <tr id="filaD_${id_zoomUNO}">
                            <td><button type="button" class="btn btn_cerrado zoomUNOD" id="despliegeUNOD_${id_zoomUNO}"><span id='span_zoomGruD${id_zoomUNO}' class='fa fa-search-plus fa-lg'></span></button></td>
                            <td class="fila_colorUD" id="npD${id_zoomUNO}">${pais}</td>
                            <td class="borde_deD" id="ndD${id_zoomUNO}">${division}</td>
                            <td class="borde_deD"></td>
                            <td class="borde_deD"></td>
                            <td class="borde_deD">${_resp.tpvExhquetiene}</td>
                            <td class="borde_deD">${_resp.tpvExhdesechados}</td>
                            <td class="borde_deD">${_resp.tpvExhinvadidos}</td>
                            <td class="borde_deD">${_resp.tpvExhmalubicados}</td>
                            <td class="borde_deD">${_resp.tpvExhretirados}</td>
                            <td class="borde_deD">${_resp.tpvExhvisibleyacce}</td>
                            <td class="borde_deD">${_resp.tpvExhnecesitar}</td>
                        </tr>`;
                        $("#filaD_"+id_zoomUNO).closest('tr').after(tabla_html);
                        $('#filaD_'+id_zoomUNO).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });

    $(document).on("click", ".zoomDOSD", function() {

        var id_zoomUNO = $(this).attr("id");
        // console.log('id ' + id_zoomUNO)
        var result = id_zoomUNO.split("_");
        var fila_Tab = result[0];
        var filaSub_Tab = result[2];
        var unidofilas = fila_Tab+filaSub_Tab;

        fila_Tab = parseInt(fila_Tab);
        filaSub_Tab = parseInt(filaSub_Tab);

        // console.log(fila_Tab);
        // console.log(filaSub_Tab);

        var pais = '';
        var division = '';
        var grupo = '';

        division = $("#ndD"+fila_Tab).html();
        grupo = $("#ngD"+unidofilas).html();//Grupo Fila + FilaSUB
        pais = $("#npD"+fila_Tab).html();
        

        if(nuevoArrayD[fila_Tab][filaSub_Tab] == 1){
            nuevoArrayD[fila_Tab][filaSub_Tab] = 0;
            $("#filaGD_"+unidofilas).closest('tr').before(fila_temporaldosD[unidofilas]);
            $('.filaD_tempoGru'+unidofilas).remove();

        }else{
            nuevoArrayD[fila_Tab][filaSub_Tab] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            datas.push({name: 'grupo',value: grupo});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reporttipocuatro/zoomd_dos',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    fila_temporaldosD[unidofilas] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralCuatroZoomDos.forEach(function(filall,index, arrgfilall){
            
                            conta_MAX++;
                            if(filaSub_Tab === 0 && index === 0){
                                // console.log('se cumpli primera fila');
                                tabla_html += `
                                <tr id="filaGD_${unidofilas}" class="filaD_tempoGru${unidofilas} filaD_ru${fila_Tab}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOD" id="despliegeUNOD_${fila_Tab}"><span id='span_zoomGruD${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUD" id="npD${fila_Tab}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deD" id="ndD${fila_Tab}">${filall.Division}</td>
                                    <td class="borde_deD css_grupo_sin_bordeD"><button type="button" class="btn btn-light btn_abierto zoomDOSD" id="${fila_Tab}_despliegeDOSD_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngD${unidofilas}">${filall.Grupo}</span></td>
                                    <td class="borde_deD">${filall.Nombre_Ruta}</td>
                                    <td class="borde_deD">${filall.exhquetiene}</td>
                                    <td class="borde_deD">${filall.exhdesechados}</td>
                                    <td class="borde_deD">${filall.exhinvadido}</td>
                                    <td class="borde_deD">${filall.exhmalubicado}</td>
                                    <td class="borde_deD">${filall.exhretirado}</td>
                                    <td class="borde_deD">${filall.exhvisibles}</td>
                                    <td class="borde_deD">${filall.exhnecesitare}</td>
                                </tr>`;

                            }else{

                                if(index === 0){
                                    tabla_html += `
                                    <tr id="filaGD_${unidofilas}" class="filaD_tempoGru${unidofilas} filaD_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUD"></td>
                                        <td class="borde_deD"></td>
                                        <td class="borde_deD css_grupo_sin_bordeD"><button type="button" class="btn btn-light btn_abierto zoomDOSD" id="${fila_Tab}_despliegeDOSD_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngD${unidofilas}">${filall.Grupo}</span></td>
                                        <td class="borde_deD">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deD">${filall.exhquetiene}</td>
                                        <td class="borde_deD">${filall.exhdesechados}</td>
                                        <td class="borde_deD">${filall.exhinvadido}</td>
                                        <td class="borde_deD">${filall.exhmalubicado}</td>
                                        <td class="borde_deD">${filall.exhretirado}</td>
                                        <td class="borde_deD">${filall.exhvisibles}</td>
                                        <td class="borde_deD">${filall.exhnecesitare}</td>
                                    </tr>`;
                                }else{
                                    tabla_html += `
                                    <tr id="filaRD_${unidofilas}" class="filaD_tempoGru${unidofilas} filaD_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUD"></td>
                                        <td class="borde_deD"></td>
                                        <td class="borde_deD css_grupo_sin_bordeD"></td>
                                        <td class="borde_deD">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deD">${filall.exhquetiene}</td>
                                        <td class="borde_deD">${filall.exhdesechados}</td>
                                        <td class="borde_deD">${filall.exhinvadido}</td>
                                        <td class="borde_deD">${filall.exhmalubicado}</td>
                                        <td class="borde_deD">${filall.exhretirado}</td>
                                        <td class="borde_deD">${filall.exhvisibles}</td>
                                        <td class="borde_deD">${filall.exhnecesitare}</td>
                                    </tr>`;
                                }
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaD_tempoGru${unidofilas} filaD_ru${fila_Tab}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUD"></td>
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUT">TOTAL ${grupo}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tpvExhquetiene}</td>
                            <td class="fila_colorUT">${_resp.tpvExhdesechados}</td>
                            <td class="fila_colorUT">${_resp.tpvExhinvadidos}</td>
                            <td class="fila_colorUT">${_resp.tpvExhmalubicados}</td>
                            <td class="fila_colorUT">${_resp.tpvExhretirados}</td>
                            <td class="fila_colorUT">${_resp.tpvExhvisibleyacce}</td>
                            <td class="fila_colorUT">${_resp.tpvExhnecesitar}</td>
                        </tr>`;

                        if(filaSub_Tab === 0){
                            fila_temporaldosD[unidofilas] = `
                            <tr id="filaGD_${unidofilas}" class="filaD_tempoGru${fila_Tab}">
                                <td><button type="button" class="btn btn_abierto zoomUNOD" id="despliegeUNOD_${fila_Tab}"><span id='span_zoomGruD${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                <td class="fila_colorUD" id="npD${fila_Tab}">${pais}</td>
                                <td class="borde_deD" id="ndD${fila_Tab}">${division}</td>
                                <td class="borde_deD css_grupoD"><button type="button" class="btn btn-light btn_cerrado zoomDOSD" id="${fila_Tab}_despliegeDOSD_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngD${unidofilas}">${grupo}</span></td>
                                <td class="borde_deD"></td>
                                <td class="borde_deD">${_resp.tpvExhquetiene}</td>
                                <td class="borde_deD">${_resp.tpvExhdesechados}</td>
                                <td class="borde_deD">${_resp.tpvExhinvadidos}</td>
                                <td class="borde_deD">${_resp.tpvExhmalubicados}</td>
                                <td class="borde_deD">${_resp.tpvExhretirados}</td>
                                <td class="borde_deD">${_resp.tpvExhvisibleyacce}</td>
                                <td class="borde_deD">${_resp.tpvExhnecesitar}</td>
                            </tr>`;
                        }else{
                            fila_temporaldosD[unidofilas] = `
                            <tr id="filaGD_${unidofilas}" class="filaD_tempoGru${fila_Tab}">
                                <td></td>
                                <td class="fila_colorUD"></td>
                                <td class="borde_deD"></td>
                                <td class="borde_deD css_grupoD"><button type="button" class="btn btn-light btn_cerrado zoomDOSD" id="${fila_Tab}_despliegeDOSD_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngD${unidofilas}">${grupo}</span></td>
                                <td class="borde_deD"></td>
                                <td class="borde_deD">${_resp.tpvExhquetiene}</td>
                                <td class="borde_deD">${_resp.tpvExhdesechados}</td>
                                <td class="borde_deD">${_resp.tpvExhinvadidos}</td>
                                <td class="borde_deD">${_resp.tpvExhmalubicados}</td>
                                <td class="borde_deD">${_resp.tpvExhretirados}</td>
                                <td class="borde_deD">${_resp.tpvExhvisibleyacce}</td>
                                <td class="borde_deD">${_resp.tpvExhnecesitar}</td>
                            </tr>`;
                        }

                        $("#filaGD_"+unidofilas).closest('tr').after(tabla_html);
                        $('#filaGD_'+unidofilas).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });


    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /*INICIO TABLA POR NO SE PUDO ENTRAR A LA TIENDA*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
    $(document).on("click", ".zoomUNOE", function() {

        var id_zoomUNO = $(this).attr("id");
        id_zoomUNO = id_zoomUNO.substring(14,id_zoomUNO.length);
        var pais = '';
        var division = '';
        pais = $("#npE"+id_zoomUNO).html();  
        division = $("#ndE"+id_zoomUNO).html();

        if(estadoZoomGrupoE[id_zoomUNO] == 1){
            var estadoCerrar = 0;
            estadoZoomGrupoE[id_zoomUNO] = 0;
            console.log('------------------------------------------------------');
            var Grupo = 1;
            var CuntosAbiertos = 0;
            for(k=0;k<nuevoArrayE[id_zoomUNO].length;k++){
                console.log('Fila => '+id_zoomUNO+' Grupo# '+ Grupo +' Estado => ' + nuevoArrayE[id_zoomUNO][k]);
                if(nuevoArrayE[id_zoomUNO][k] === 1){
                    CuntosAbiertos++;
                    nuevoArrayE[id_zoomUNO][k] = 0;
                }else{
                    CuntosAbiertos = CuntosAbiertos + 0;
                }
                Grupo++;
            }



            if(CuntosAbiertos > 0){
                $("#filaGE_"+id_zoomUNO+"0").closest('tr').before(fila_temporalE[id_zoomUNO]);
                $('.filaE_tempoGru'+id_zoomUNO).remove();
                $('.filaE_ru'+id_zoomUNO).remove();
            }else{
                $("#filaGE_"+id_zoomUNO+"0").closest('tr').before(fila_temporalE[id_zoomUNO]);
                $('.filaE_tempoGru'+id_zoomUNO).remove();
            }
        }else{
            // console.log('desplegando por grupo');
            estadoZoomGrupoE[id_zoomUNO] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportdoszucinco/zoome_uno',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    var filaG ='';
                    fila_temporalE[id_zoomUNO] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralCincoZoomUno.forEach(function(filall,index, arrgfilall){
                            nuevoArrayE[id_zoomUNO][index] = 0;
                            conta_MAX++;
                            if(index === 0){
                                tabla_html += `
                                <tr id="filaGE_${id_zoomUNO}${index}" class="filaE_tempoGru${id_zoomUNO}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOE" id="despliegeUNOE_${id_zoomUNO}"><span id='span_zoomGruE${id_zoomUNO}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUE" id="npE${id_zoomUNO}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deE" id="ndE${id_zoomUNO}">${filall.Division}</td>
                                    <td class="borde_deE css_grupoE"><button type="button" class="btn btn-light btn_cerrado zoomDOSE" id="${id_zoomUNO}_despliegeDOSE_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngE${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deE"></td>
                                    <td class="borde_deE">${filall.CliNoEntrar}</td>
                                    <td class="borde_deE">${filall.CerradoTienda}</td>
                                    <td class="borde_deE">${filall.NoseEncontroT}</td>
                                </tr>`;
                            }else{
                                tabla_html += `
                                <tr id="filaGE_${id_zoomUNO}${index}" class="filaE_tempoGru${id_zoomUNO}">
                                    <td></td>
                                    <td class="fila_colorUE"></td>
                                    <td class="borde_deE"></td>
                                    <td class="borde_deE css_grupoE"><button type="button" class="btn btn-light btn_cerrado zoomDOSE" id="${id_zoomUNO}_despliegeDOSE_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngE${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                    <td class="borde_deE"></td>
                                    <td class="borde_deE">${filall.CliNoEntrar}</td>
                                    <td class="borde_deE">${filall.CerradoTienda}</td>
                                    <td class="borde_deE">${filall.NoseEncontroT}</td>
                                </tr>`;
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaE_tempoGru${id_zoomUNO}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUE"></td>
                            <td class="fila_colorUT">TOTAL ${division}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tCliNoEntrar}</td>
                            <td class="fila_colorUT">${_resp.tEstabaCerrado}</td>
                            <td class="fila_colorUT">${_resp.tNoseEncontroCli}</td>
                        </tr>`;
                        fila_temporalE[id_zoomUNO]= `
                        <tr id="filaE_${id_zoomUNO}">
                            <td><button type="button" class="btn btn_cerrado zoomUNOE" id="despliegeUNOE_${id_zoomUNO}"><span id='span_zoomGruE${id_zoomUNO}' class='fa fa-search-plus fa-lg'></span></button></td>
                            <td class="fila_colorUE" id="npE${id_zoomUNO}">${pais}</td>
                            <td class="borde_deE" id="ndE${id_zoomUNO}">${division}</td>
                            <td class="borde_deE"></td>
                            <td class="borde_deE"></td>
                            <td class="borde_deE">${_resp.tCliNoEntrar}</td>
                            <td class="borde_deE">${_resp.tEstabaCerrado}</td>
                            <td class="borde_deE">${_resp.tNoseEncontroCli}</td>
                        </tr>`;
                        $("#filaE_"+id_zoomUNO).closest('tr').after(tabla_html);
                        $('#filaE_'+id_zoomUNO).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });


    $(document).on("click", ".zoomDOSE", function() {

        var id_zoomUNO = $(this).attr("id");
        // console.log('id ' + id_zoomUNO)
        var result = id_zoomUNO.split("_");
        var fila_Tab = result[0];
        var filaSub_Tab = result[2];
        var unidofilas = fila_Tab+filaSub_Tab;

        fila_Tab = parseInt(fila_Tab);
        filaSub_Tab = parseInt(filaSub_Tab);

        // console.log(fila_Tab);
        // console.log(filaSub_Tab);

        var pais = '';
        var division = '';
        var grupo = '';

        division = $("#ndE"+fila_Tab).html();
        grupo = $("#ngE"+unidofilas).html();//Grupo Fila + FilaSUB
        pais = $("#npE"+fila_Tab).html();
        

        if(nuevoArrayE[fila_Tab][filaSub_Tab] == 1){
            nuevoArrayE[fila_Tab][filaSub_Tab] = 0;
            $("#filaGE_"+unidofilas).closest('tr').before(fila_temporaldosE[unidofilas]);
            $('.filaE_tempoGru'+unidofilas).remove();

        }else{
            nuevoArrayE[fila_Tab][filaSub_Tab] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            datas.push({name: 'grupo',value: grupo});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reporttipocinco/zoome_dos',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    fila_temporaldosE[unidofilas] = '';
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_GeneralCincoZoomDos.forEach(function(filall,index, arrgfilall){
            
                            conta_MAX++;
                            if(filaSub_Tab === 0 && index === 0){
                                // console.log('se cumpli primera fila');
                                tabla_html += `
                                <tr id="filaGE_${unidofilas}" class="filaE_tempoGru${unidofilas} filaE_ru${fila_Tab}">
                                    <td><button type="button" class="btn btn_abierto zoomUNOE" id="despliegeUNOE_${fila_Tab}"><span id='span_zoomGruE${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="fila_colorUE" id="npE${fila_Tab}">${filall.Nombre_Pais}</td>
                                    <td class="borde_deE" id="ndE${fila_Tab}">${filall.Division}</td>
                                    <td class="borde_deE css_grupo_sin_bordeE"><button type="button" class="btn btn-light btn_abierto zoomDOSE" id="${fila_Tab}_despliegeDOSE_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngE${unidofilas}">${filall.Grupo}</span></td>
                                    <td class="borde_deE">${filall.Nombre_Ruta}</td>
                                    <td class="borde_deE">${filall.CliNoEntrar}</td>
                                    <td class="borde_deE">${filall.CerradoTienda}</td>
                                    <td class="borde_deE">${filall.NoseEncontroT}</td>
                                </tr>`;

                            }else{

                                if(index === 0){
                                    tabla_html += `
                                    <tr id="filaGE_${unidofilas}" class="filaE_tempoGru${unidofilas} filaE_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUE"></td>
                                        <td class="borde_deE"></td>
                                        <td class="borde_deE css_grupo_sin_bordeE"><button type="button" class="btn btn-light btn_abierto zoomDOSE" id="${fila_Tab}_despliegeDOSE_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngE${unidofilas}">${filall.Grupo}</span></td>
                                        <td class="borde_deE">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deE">${filall.CliNoEntrar}</td>
                                        <td class="borde_deE">${filall.CerradoTienda}</td>
                                        <td class="borde_deE">${filall.NoseEncontroT}</td>
                                    </tr>`;
                                }else{
                                    tabla_html += `
                                    <tr id="filaRE_${unidofilas}" class="filaE_tempoGru${unidofilas} filaE_ru${fila_Tab}">
                                        <td></td>
                                        <td class="fila_colorUE"></td>
                                        <td class="borde_deE"></td>
                                        <td class="borde_deE css_grupo_sin_bordeE"></td>
                                        <td class="borde_deE">${filall.Nombre_Ruta}</td>
                                        <td class="borde_deE">${filall.CliNoEntrar}</td>
                                        <td class="borde_deE">${filall.CerradoTienda}</td>
                                        <td class="borde_deE">${filall.NoseEncontroT}</td>
                                    </tr>`;
                                }
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaE_tempoGru${unidofilas} filaE_ru${fila_Tab}">
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUE"></td>
                            <td class="fila_colorUTzu"></td>
                            <td class="fila_colorUT">TOTAL ${grupo}</td>
                            <td class="fila_colorUT"></td>
                            <td class="fila_colorUT">${_resp.tCliNoEntrar}</td>
                            <td class="fila_colorUT">${_resp.tEstabaCerrado}</td>
                            <td class="fila_colorUT">${_resp.tNoseEncontroCli}</td>
                        </tr>`;

                        if(filaSub_Tab === 0){
                            fila_temporaldosE[unidofilas] = `
                            <tr id="filaGE_${unidofilas}" class="filaE_tempoGru${fila_Tab}">
                                <td><button type="button" class="btn btn_abierto zoomUNOE" id="despliegeUNOE_${fila_Tab}"><span id='span_zoomGruE${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                <td class="fila_colorUE" id="npE${fila_Tab}">${pais}</td>
                                <td class="borde_deE" id="ndE${fila_Tab}">${division}</td>
                                <td class="borde_deE css_grupoE"><button type="button" class="btn btn-light btn_cerrado zoomDOSE" id="${fila_Tab}_despliegeDOSE_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngE${unidofilas}">${grupo}</span></td>
                                <td class="borde_deE"></td>
                                <td class="borde_deE">${_resp.tCliNoEntrar}</td>
                                <td class="borde_deE">${_resp.tEstabaCerrado}</td>
                                <td class="borde_deE">${_resp.tNoseEncontroCli}</td>
                            </tr>`;
                        }else{
                            fila_temporaldosE[unidofilas] = `
                            <tr id="filaGE_${unidofilas}" class="filaE_tempoGru${fila_Tab}">
                                <td></td>
                                <td class="fila_colorUE"></td>
                                <td class="borde_deE"></td>
                                <td class="borde_deE css_grupoE"><button type="button" class="btn btn-light btn_cerrado zoomDOSE" id="${fila_Tab}_despliegeDOSE_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngE${unidofilas}">${grupo}</span></td>
                                <td class="borde_deE"></td>
                                <td class="borde_deE">${_resp.tCliNoEntrar}</td>
                                <td class="borde_deE">${_resp.tEstabaCerrado}</td>
                                <td class="borde_deE">${_resp.tNoseEncontroCli}</td>
                            </tr>`;
                        }

                        $("#filaGE_"+unidofilas).closest('tr').after(tabla_html);
                        $('#filaGE_'+unidofilas).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });


    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /*INICIO TABLA CONTEO DE CENSADOS POR DIA*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

    $(document).on("click", ".zoomUNOF", function() {
        // alert('zoomUNOF');
        var id_zoomUNO = $(this).attr("id");
        id_zoomUNO = id_zoomUNO.substring(14,id_zoomUNO.length);
        var pais = '';
        var division = '';
        pais = $("#npF"+id_zoomUNO).html();  
        division = $("#ndF"+id_zoomUNO).html();

        // alert(division);


        if(estadoZoomGrupoF[id_zoomUNO] == 1){
            var estadoCerrar = 0;
            estadoZoomGrupoF[id_zoomUNO] = 0;
            console.log('------------------------------------------------------');
            var Grupo = 1;
            var CuntosAbiertos = 0;
            for(k=0;k<nuevoArrayF[id_zoomUNO].length;k++){
                console.log('Fila => '+id_zoomUNO+' Grupo# '+ Grupo +' Estado => ' + nuevoArrayF[id_zoomUNO][k]);
                if(nuevoArrayF[id_zoomUNO][k] === 1){
                    CuntosAbiertos++;
                    nuevoArrayF[id_zoomUNO][k] = 0;
                }else{
                    CuntosAbiertos = CuntosAbiertos + 0;
                }
                Grupo++;
            }


            console.log(id_zoomUNO);
            if(CuntosAbiertos > 0){
                $("#filaGF_"+id_zoomUNO+"0").closest('tr').before(fila_temporalF[id_zoomUNO]);
                $('.filaF_tempoGru'+id_zoomUNO).remove();
                $('.filaF_ru'+id_zoomUNO).remove();
            }else{
                $("#filaGF_"+id_zoomUNO+"0").closest('tr').before(fila_temporalF[id_zoomUNO]);
                $('.filaF_tempoGru'+id_zoomUNO).remove();
                console.log(fila_temporalF);
            }
        }else{
            // console.log('desplegando por grupo');
            estadoZoomGrupoF[id_zoomUNO] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportsesisuno/tabla_seis_zoom_uno',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    var filaG ='';
                    fila_temporalF[id_zoomUNO] = '';
                    var totalarrgfe = _resp.ls_Fechas.length;
                    var conta = 0;var contas = 0;var ConRegreso = 0;
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_Divisiones.forEach(function(filall,index, arrgfilall){
                            nuevoArrayF[id_zoomUNO][index] = 0;
                            conta_MAX++;

                            if(index === 0){

                                tabla_html+=`
                                <tr id="filaGF_${id_zoomUNO}${index}" class="filaF_tempoGru${id_zoomUNO}">
                                <td class="sticky Borde_AUF"><button type="button" class="btn btn_abierto zoomUNOF" id="despliegeUNOF_${id_zoomUNO}"><span id='span_zoomGruF${id_zoomUNO}' class='fa fa-search-minus fa-lg'></span></button></td>
                                <td class="sticky_2 fila_colorUF" id="npF${id_zoomUNO}">${filall.Nombre_Pais}</td>
                                <td class="sticky_3 borde_deF" id="ndF${id_zoomUNO}">${filall.Division}</td>
                                <td class="sticky_4 borde_deF css_grupoF"><button type="button" class="btn btn-light btn_cerrado zoomDOSF" id="${id_zoomUNO}_despliegeDOSF_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngF${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                <td class="sticky_5 borde_deF"></td>`;
                                    
                                for(o=0;o<totalarrgfe;o++){
                                    tabla_html+=`<td class="borde_deF">${_resp.ls_CuenXdia[conta].totalpdv}</td>`;
                                    conta++;
                                }
                                tabla_html+=`<td class="borde_deF">${_resp.ls_TotalGrupo[index].totalactualizados}</td>`;
                                tabla_html+=`</tr>`;

                            }else{

                                tabla_html+=`
                                <tr id="filaGF_${id_zoomUNO}${index}" class="filaF_tempoGru${id_zoomUNO}">
                                <td class="sticky"></td>
                                <td class="sticky_2 fila_colorUF"></td>
                                <td class="sticky_3 borde_deF"></td>
                                <td class="sticky_4 borde_deF css_grupoF"><button type="button" class="btn btn-light btn_cerrado zoomDOSF" id="${id_zoomUNO}_despliegeDOSF_${index}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngF${id_zoomUNO}${index}">${filall.Grupo}</span></td>
                                <td class="sticky_5 borde_deF"></td>`;
                                    
                                for(o=0;o<totalarrgfe;o++){
                                    tabla_html+=`<td class="borde_deF">${_resp.ls_CuenXdia[conta].totalpdv}</td>`;
                                    conta++;
                                }
                                tabla_html+=`<td class="borde_deF">${_resp.ls_TotalGrupo[index].totalactualizados}</td>`;
                                tabla_html+=`</tr>`;
                            }
                        });

                        tabla_html += `
                        <tr class="filazoom_grupo filaF_tempoGru${id_zoomUNO}">
                            <td class="sticky"></td>
                            <td class="sticky_2 fila_colorUF"></td>
                            <td class="sticky_3">TOTAL ${division}</td>
                            <td class="sticky_4"></td>
                            <td class="sticky_5"></td>`;
                            for(s=0;s<totalarrgfe;s++){
                                tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalSV[contas]}</td>`;
                                contas++;
                            }
                            tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalDivision[0].totalactualizados}</td>`;
                        tabla_html += `</tr>`;

                        fila_temporalF[id_zoomUNO] = `<tr id="filaF_${id_zoomUNO}">
                            <td class="sticky"><button type="button" class="btn btn_cerrado zoomUNOF" id="despliegeUNOF_${id_zoomUNO}"><span id='span_zoomGruF${id_zoomUNO}' class='fa fa-search-plus fa-lg'></span></button></td>
                            <td class="sticky_2 fila_colorUF" id="npF${id_zoomUNO}">${pais}</td>
                            <td class="sticky_3 borde_deF" id="ndF${id_zoomUNO}">${division}</td>
                            <td class="sticky_4 borde_deF"></td>
                            <td class="sticky_5 borde_deF"></td>`;
                        for(s=0;s<totalarrgfe;s++){
                                fila_temporalF[id_zoomUNO]+=`<td>${_resp.ls_TotalSV[ConRegreso]}</td>`;
                                ConRegreso++;
                            }
                        fila_temporalF[id_zoomUNO]+=`<td>${_resp.ls_TotalDivision[0].totalactualizados}</td>`;
                        fila_temporalF[id_zoomUNO] += `</tr>`;


                        $("#filaF_"+id_zoomUNO).closest('tr').after(tabla_html);
                        $('#filaF_'+id_zoomUNO).remove();
                    });
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });

    $(document).on("click", ".zoomDOSF", function() {
        // alert('zoomDOSF');
        var id_zoomUNO = $(this).attr("id");
        // console.log('id ' + id_zoomUNO)
        var result = id_zoomUNO.split("_");
        var fila_Tab = result[0];
        var filaSub_Tab = result[2];
        var unidofilas = fila_Tab+filaSub_Tab;

        fila_Tab = parseInt(fila_Tab);
        filaSub_Tab = parseInt(filaSub_Tab);

        // console.log(fila_Tab);
        // console.log(filaSub_Tab);

        var pais = '';
        var division = '';
        var grupo = '';

        division = $("#ndF"+fila_Tab).html();
        grupo = $("#ngF"+unidofilas).html();//Grupo Fila + FilaSUB
        pais = $("#npF"+fila_Tab).html();
        

        // alert('division '+division + 'id '+fila_Tab);
        // alert('grupo '+grupo+ 'id '+unidofilas);
        // alert('pais '+pais+ 'id '+fila_Tab);
        if(nuevoArrayF[fila_Tab][filaSub_Tab] == 1){
            nuevoArrayF[fila_Tab][filaSub_Tab] = 0;
            $("#filaGF_"+unidofilas).closest('tr').before(fila_temporaldosF[unidofilas]);
            $('.filaF_tempoGru'+unidofilas).remove();

        }else{
            nuevoArrayF[fila_Tab][filaSub_Tab] = 1;
            datas = $("#form_lsClteCensados").serializeArray();
            datas.push({name: 'page',value: 1});
            datas.push({name: 'division',value: division});
            datas.push({name: 'grupo',value: grupo});
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                        url      : 'ls_reportsesisdos/tabla_seis_zoom_dos',
                        type     : 'POST',
                        dataType : 'JSON',
                        data     : datas,
                        timeout  : 60777
                }).done(function(_resp){
                    var conta_MAX = 0;
                    var tabla_html = ``;
                    fila_temporaldosF[unidofilas] = '';
                    var totalarrgfe = _resp.ls_Fechas.length;
                    var conta = 0;var contas = 0;var ConRegreso = 0;
                     $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        _resp.ls_Divisiones.forEach(function(filall,index, arrgfilall){
            
                            conta_MAX++;
                            if(filaSub_Tab === 0 && index === 0){
                                // console.log('se cumpli primera fila');

                                tabla_html += `
                                <tr id="filaGF_${unidofilas}" class="filaF_tempoGru${unidofilas} filaF_ru${fila_Tab}">
                                    <td class="sticky"><button type="button" class="btn btn_abierto zoomUNOF" id="despliegeUNOF_${fila_Tab}"><span id='span_zoomGruF${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                    <td class="sticky_2 fila_colorUF" id="npF${fila_Tab}">${filall.Nombre_Pais}</td>
                                    <td class="sticky_3 borde_deF" id="ndF${fila_Tab}">${filall.Division}</td>
                                    <td class="sticky_4 borde_deF css_grupo_sin_bordeF"><button type="button" class="btn btn-light btn_abierto zoomDOSF" id="${fila_Tab}_despliegeDOSF_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngF${unidofilas}">${filall.Grupo}</span></td>
                                    <td class="sticky_5 borde_deF">${filall.Nombre_Ruta}</td>`;

                                for(o=0;o<totalarrgfe;o++){
                                    tabla_html+=`<td>${_resp.ls_CuenXdia[conta].totalpdv}</td>`;
                                    conta++;
                                }
                                tabla_html+=`<td>${_resp.ls_TotalGrupo[index].totalactualizados}</td>`;
                                tabla_html+=`</tr>`;

                            }else{

                                if(index === 0){
                                    tabla_html += `
                                    <tr id="filaGF_${unidofilas}" class="filaF_tempoGru${unidofilas} filaF_ru${fila_Tab}">
                                        <td class"sticky"></td>
                                        <td class="sticky_2 fila_colorUF"></td>
                                        <td class="sticky_3 borde_deF"></td>
                                        <td class="sticky_4 borde_deF css_grupo_sin_bordeF"><button type="button" class="btn btn-light btn_abierto zoomDOSF" id="${fila_Tab}_despliegeDOSF_${filaSub_Tab}"><span class='fa fa-search-minus fa-lg'></span></button><span id="ngF${unidofilas}">${filall.Grupo}</span></td>
                                        <td class="sticky_5 borde_deF">${filall.Nombre_Ruta}</td>`;

                                        for(o=0;o<totalarrgfe;o++){
                                            tabla_html+=`<td>${_resp.ls_CuenXdia[conta].totalpdv}</td>`;
                                            conta++;
                                        }
                                    tabla_html+=`<td>${_resp.ls_TotalGrupo[index].totalactualizados}</td>`;
                                    tabla_html+=`</tr>`;
                                }else{
                                    tabla_html += `
                                    <tr id="filaRF_${unidofilas}" class="filaF_tempoGru${unidofilas} filaF_ru${fila_Tab}">
                                        <td class="sticky"></td>
                                        <td class="sticky_2 fila_colorUF"></td>
                                        <td class="sticky_3 borde_deF"></td>
                                        <td class="sticky_4 borde_deF css_grupo_sin_bordeF"></td>
                                        <td class="sticky_5 borde_deF">${filall.Nombre_Ruta}</td>`;
                                        for(o=0;o<totalarrgfe;o++){
                                            tabla_html+=`<td>${_resp.ls_CuenXdia[conta].totalpdv}</td>`;
                                            conta++;
                                        }
                                    tabla_html+=`<td>${_resp.ls_TotalGrupo[index].totalactualizados}</td>`;
                                    tabla_html+=`</tr>`;
                                }
                            }
                        });


                        tabla_html += `
                        <tr class="filazoom_grupo filaF_tempoGru${unidofilas} filaF_ru${fila_Tab}">
                            <td class="sticky"></td>
                            <td class="sticky_2 fila_colorUF"></td>
                            <td class="sticky_3"></td>
                            <td class="sticky_4">TOTAL ${grupo}</td>
                            <td class="sticky_5"></td>`;
                            for(s=0;s<totalarrgfe;s++){
                                tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalSV[contas]}</td>`;
                                contas++;
                            }
                            tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalDivision[0].totalactualizados}</td>`;
                        tabla_html += `</tr>`;

                        if(filaSub_Tab === 0){
                            fila_temporaldosF[unidofilas] = `
                            <tr id="filaGF_${unidofilas}" class="filaF_tempoGru${fila_Tab}">
                                <td class="sticky"><button type="button" class="btn btn_abierto zoomUNOF" id="despliegeUNOF_${fila_Tab}"><span id='span_zoomGruF${unidofilas}' class='fa fa-search-minus fa-lg'></span></button></td>
                                <td class="sticky_2 fila_colorUF" id="npF${fila_Tab}">${pais}</td>
                                <td class="sticky_3 borde_deF" id="ndF${fila_Tab}">${division}</td>
                                <td class="sticky_4 borde_deF css_grupoF"><button type="button" class="btn btn-light btn_cerrado zoomDOSF" id="${fila_Tab}_despliegeDOSF_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngF${unidofilas}">${grupo}</span></td>
                                <td class="sticky_5 borde_deF"></td>`;

                            for(s=0;s<totalarrgfe;s++){
                                fila_temporaldosF[unidofilas]+=`<td>${_resp.ls_TotalSV[ConRegreso]}</td>`;
                                ConRegreso++;
                            }
                            fila_temporaldosF[unidofilas]+=`<td>${_resp.ls_TotalDivision[0].totalactualizados}</td>`;

                            fila_temporaldosF[unidofilas]+=`</tr>`;
                        }else{
                            fila_temporaldosF[unidofilas] = `
                            <tr id="filaGF_${unidofilas}" class="filaF_tempoGru${fila_Tab}">
                                <td class="sticky"></td>
                                <td class="sticky_2 fila_colorUF"></td>
                                <td class="sticky_3 borde_deF"></td>
                                <td class="sticky_4 borde_deF css_grupoF"><button type="button" class="btn btn-light btn_cerrado zoomDOSF" id="${fila_Tab}_despliegeDOSF_${filaSub_Tab}"><span class='fa fa-search-plus fa-lg'></span></button><span id="ngF${unidofilas}">${grupo}</span></td>
                                <td class="sticky_5 borde_deF"></td>`;
                            for(s=0;s<totalarrgfe;s++){
                                fila_temporaldosF[unidofilas]+=`<td>${_resp.ls_TotalSV[ConRegreso]}</td>`;
                                ConRegreso++;
                            }
                            fila_temporaldosF[unidofilas]+=`<td>${_resp.ls_TotalDivision[0].totalactualizados}</td>`;
                            fila_temporaldosF[unidofilas]+=`</tr>`;
                        }

                        $("#filaGF_"+unidofilas).closest('tr').after(tabla_html);
                        $('#filaGF_'+unidofilas).remove();
                    });
                    // console.log(fila_temporaldosF);
                    // console.log(CopyPasteTotales);
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                         _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    });
                });
            });
        }

    });



    $('#S_filtroPais').on('change','#filtropais',function(){

        $("#filtrocodigos").val("");
        $("#filtrodistritos").val("");
        $("#filtrorutas").val("");

        cargaSolo = 0;
        Recordar_Fpais = this.value;
        Recordar_Fdivision = $("#filtrodivision").val();
        Recordar_Fcanal = $("#filtrocanales").val();
        if(Recordar_Fpais !=""){
            Recordar_Fpais = this.value;
            Recordar_Fdivision = $("#filtrodivision").val();
        }else{
            Recordar_Fpais = this.value;
            Recordar_Fdivision = "";
            $("#filtropais").val("");
            $("#filtrodivision").val("");
            $("#filtrodistritos").val("");
            $("#filtrorutas").val("");
        }
        CargarFULLCOMPLEMENTOS();
    });

    $('#S_filtroDivision').on('change','#filtrodivision',function(){
        $("#filtrocodigos").val("");
        $("#filtrodistritos").val("");
        $("#filtrorutas").val("");
        cargaSolo = 0;
        Recordar_Fpais = $("#filtropais").val();
        Recordar_Fdivision = this.value;
        Recordar_Fcanal = $("#filtrocanales").val();
        if(Recordar_Fdivision !=""){
            Recordar_Fpais = $("#filtropais").val();
            Recordar_Fdivision = this.value;
        }else{
            Recordar_Fpais = "";
            Recordar_Fdivision = "";
            $("#filtropais").val("");
            $("#filtrodivision").val("");
            $("#filtrodistritos").val("");
            $("#filtrorutas").val("");
        }
        CargarFULLCOMPLEMENTOS();
    });

    $('#S_filtroCanales').on('change','#filtrocanales',function(){
        $("#filtrocodigos").val("");
        $("#filtrodistritos").val("");
        $("#filtrorutas").val("");
        cargaSolo = 0;
        Recordar_Fpais = $("#filtropais").val();
        Recordar_Fdivision = $("#filtrodivision").val();
        $("#filtrodistritos").val("");
        $("#filtrorutas").val("");
        Recordar_Fcanal = this.value;;
        CargarFULLCOMPLEMENTOS();
    });


    $('#Content_tabla_lsClteCensados').on('click','.abrirmodal',function(){
        // body.addClass('block-scroll');
        $("#content-mapa").empty().html("<div id='map' style='width: 100%; height: 100%;'></div>");
        var idx_cliente = indexes_clientes[$(this).attr('id').substring(10)];
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'expediente/xcliente',
                type     : 'POST',
                dataType : 'JSON',
                data     : {'idx_cliente': idx_cliente},
                timeout  : 60777
            }).done(function(_resp){
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    if(_resp.rs == true){

                        var divUno_html = ``;
                        var fotoexhibidor = '';
                        
                        // if(Object.entries(map).length === 0){

                              setTimeout(function() {
                                iniciar_mapa(_resp.xcliente.LatitudObservacion,_resp.xcliente.LongitudObservacion,_resp.xcliente.Latitud,_resp.xcliente.Longitud);
                                // map.invalidateSize();
                                // alert('mapa cargado correctament');
                              }, 1000);

                            
                        // }else{
                        //     map.remove();
                        //     map = new Array();
                        // }
                        if(_resp.xcliente.FotoObservacion === 0){    
                            fotoexhibidor = '../dependencias/imagenes/icon_256.png'
                        }else{
                            fotoexhibidor = '../../img_server/'+_resp.xcliente.FotoObservacion;
                        }

                        divUno_html += `
                        <table id="tabla-infop">
                            <thead>
                                <tr>
                                    <th colspan="2" class="titulo-principal">Información General</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="tabla-tituloCol">Ruta:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Nombre_Ruta}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Código:</td>
                                    <td class="tabla-infop">${_resp.xcliente.CodigoCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Nombre:</td>
                                    <td class="tabla-infop">${_resp.xcliente.NombreCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Dirección:</td>
                                    <td class="tabla-infop">${_resp.xcliente.DireccionCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Contacto:</td>
                                    <td class="tabla-infop">${_resp.xcliente.ContactoCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Teléfono:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Telefono}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">País:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Nombre_Pais}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Canal:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Canal}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">División:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Division}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>`;
                        var DivFotohtml = ``;
                        DivFotohtml = `
                        <table id="tabla_foto">
                            <tr>
                                <th class="titulo-foto">Foto del exhibidor principal</th>
                            </tr>
                            <tr>
                                <td>
                                    <img src="${fotoexhibidor}" />
                                </td>
                            </tr>
                        </table>`;

                        $("#content-infog").empty().html(divUno_html);
                        $("#content-infof").empty().html(DivFotohtml);
 
                        var divDos_html = ``;
                        divDos_html = `
                        <table id="tabla_exhibidor">
                            <tr>
                                <th class="titulo-exh" colspan="2">EXHIBIDORES QUE TIENE FACTURADOS</th>
                            </tr>`;
                        if(Object.entries(_resp.xexhibidorqt).length === 0){
                            divDos_html +=`
                            <tr>
                                <td class="Observ"><span class="vya fas fa-info fa-3x"></span></td>
                                <td class='sin_exhibidor'>
                                    <span>SIN EXHIBIDORES</span>
                                </td>
                            </tr>`;
                        }else{
                            _resp.xexhibidorqt.forEach(function(filall,index, arrgfilall){
                                divDos_html +=`
                                 <tr>
                                    <td class="Observ">`;
                                    if(filall.RespuestaObservacion == 1){
                                        divDos_html +=`<span class="vya fas fa-check-double fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 2){
                                        divDos_html +=`<span class="malub fas fa-arrows-alt fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 3){
                                        divDos_html +=`<span class="invad fas fa-exclamation-triangle fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 4){
                                        divDos_html +=`<span class="necer fas fa-tools fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 5){
                                        divDos_html +=`<span class="deseg fas fa-trash-alt fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 6){
                                        divDos_html +=`<span class="retig fas fa-ban fa-3x"></span>`;
                                    }else{
                                        divDos_html +=`<span class="defaultcolor fas fa-question-circle fa-3x"></span>`;
                                    }
                                divDos_html +=`
                                    </td>
                                    <td>
                                        <div class='seg' style="width:100%;margin:0 auto;">
                                            <div class='seg_i'>${filall.SKU_Exhibidor}</div>
                                            <div class='seg_d'><span class=''></span> ${filall.NombreExhibidor}</div>
                                        </div>
                                    </td>
                                </tr>
                                `;
                            });
                        }
                        divDos_html +=`</table>`;

                        var divTres_html = ``;
                        divTres_html = `
                        <table id="tabla_exhibidor">
                            <tr>
                                <th class="titulo-exh" colspan="2">EXHIBIDORES NUEVOS</th>
                            </tr>`;
                        if(Object.entries(_resp.xexhibidornu).length === 0){
                            divTres_html +=`
                            <tr>
                                <td class="Observ"><span class="vya fas fa-info fa-3x"></span></td>
                                <td class='sin_exhibidor'>
                                    <span>SIN EXHIBIDORES</span>
                                </td>
                            </tr>`;
                        }else{
                            _resp.xexhibidornu.forEach(function(filall,index, arrgfilall){
                                divTres_html +=`
                                 <tr>
                                    <td class="Observ">
                                        <span class='nuevo fas fa-plus fa-3x'></span>
                                    </td>
                                    <td>
                                        <div class='segtres' style="width:100%;margin:0 auto;">
                                            <div class='seg_i'>${filall.SKU_Exhibidor}</div>
                                            <div class='seg_d'><span class=''></span> ${filall.NombreExhibidor}</div>
                                        </div>
                                    </td>
                                </tr>
                                `;
                            });
                        }
                        divTres_html +=`</table>`;

                        var divCuatro_html = ``;
                        divCuatro_html = `
                        <table id="tabla_exhibidor">
                            <tr>
                                <th class="titulo-exh" colspan="2">EXHIBIDORES DEVUELTOS</th>
                            </tr>`;
                        if(Object.entries(_resp.xexhibidorde).length === 0){
                            divCuatro_html +=`
                            <tr>
                                <td class="Observ"><span class="vya fas fa-info fa-3x"></span></td>
                                <td class='sin_exhibidor'>
                                    <span>SIN EXHIBIDORES</span>
                                </td>
                            </tr>`;
                        }else{
                            _resp.xexhibidorde.forEach(function(filall,index, arrgfilall){
                                divCuatro_html +=`
                                 <tr>
                                    <td class="Observ">`;
                                    if(filall.RespuestaObservacion == 1){
                                        divCuatro_html +=`<span class="vya fas fa-check-double fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 2){
                                        divCuatro_html +=`<span class="malub fas fa-arrows-alt fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 3){
                                        divCuatro_html +=`<span class="invad fas fa-exclamation-triangle fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 4){
                                        divCuatro_html +=`<span class="necer fas fa-tools fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 5){
                                        divCuatro_html +=`<span class="deseg fas fa-trash-alt fa-3x"></span>`;
                                    }else if(filall.RespuestaObservacion == 6){
                                        divCuatro_html +=`<span class="retig fas fa-ban fa-3x"></span>`;
                                    }else{
                                        divCuatro_html +=`<span class="defaultcolor fas fa-question-circle fa-3x"></span>`;
                                    }
                                divCuatro_html +=`
                                    </td>
                                    <td>
                                        <div class='segdos' style="width:100%;margin:0 auto;">
                                            <div class='seg_i'>${filall.SKU_Exhibidor}</div>
                                            <div class='seg_d'><span class=''></span> ${filall.NombreExhibidor}</div>
                                        </div>
                                    </td>
                                </tr>
                                `;
                            });
                        }
                        divCuatro_html +=`</table>`;


                        $("#content-infoe").empty().html(divDos_html+divCuatro_html+divTres_html);
                        $("#ModalAbrirExpendiente").modal("toggle");
                        // toggleFullScreen();
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            Swal.fire({
                                title: 'Aviso!',
                                type: 'error',
                                html:'<h6>'+_resp.info+'</h6>',
                                confirmButtonText:'Ok'
                            });
                        });
                    }
                });
            }).fail(function(status, textStatus, errorThrown) {
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    _ajax_error_Exhibidores(status,textStatus,errorThrown);
                });
            });
        });
    });
    $('#ModalAbrirExpendiente').on('hidden.bs.modal', function () {
        if (document.fullscreenElement || 
            document.webkitFullscreenElement || 
            document.mozFullScreenElement) {
            screenfull.exit();
            map.remove();
        }
        body.removeClass('block-scroll');
    });

    /*----------------------------------------------------------------------------------*/
    $(document).on("click",".page-TablaClteCensados",function(){
        cargaSolo = 1;
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
        $page = $(this).attr('href');
        $pageind = $page.indexOf('page=');
        $page = $page.substring(($pageind+5));
        Tabla_ClteConCenso($page);
        return false;
    });

    $('#S_filtroDistritos').on('change','#filtrodistritos',function(){
        cargaSolo = 1;
        var datas = '';
        datas = $("#form_lsClteCensados").serializeArray();
        // console.log(datas);
        $.ajax({
            url      : 'filtro/rexhibidores_ruta',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){

            if( $("#filtrodistritos").val() !="" ){
                $("#S_filtroRuta").show();
                var filtro_htmlRuta = ``;
                filtro_htmlRuta += `<select class="form-control" id="filtrorutas" name="filtrorutas">
                <option value="">TODAS LAS RUTAS</option>`;
                _resp.ls_filtroRuta.forEach(function(filall,index, arrgfilall){
                    filtro_htmlRuta+=`<option value="${filall.Id_Ruta}">${filall.Nombre_Ruta}</option>`;
                });
                $("#S_filtroRuta").empty().html(filtro_htmlRuta);
                $.fn.select2.defaults.set("theme", "bootstrap");
                $( "#filtrorutas" ).select2({
                    theme: "bootstrap"
                });
            }else{
                $("#S_filtroRuta").hide();
            }
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                Tabla_ClteConCenso(1);
            });
        }).fail(function(status, textStatus, errorThrown) {
            _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });
    });

    $('#S_filtroRuta').on('change','#filtrorutas',function(){
        cargaSolo = 1;
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Tabla_ClteConCenso(1);
        });
    });

    $('#S_filtroCodigo').on('click','#btn_buscarCod',function(){
        cargaSolo = 1;
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Tabla_ClteConCenso(1);
        });
    });

    $('#S_filtroCodigo').on('click','#btn_eliminarBus',function(){
        cargaSolo = 1;
        $("#filtrocodigos").val("");
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Tabla_ClteConCenso(1);
        });
    });


});

function CargarFULLCOMPLEMENTOS(){
    $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
        Promise.all([
            Tabla_ClteConCenso(1),
            Tabla_ClteGenralUno(),
            Tabla_CONSIN_Exh(),
            Tabla_TipoActualizacionExh(),
            Tabla_TipoObservacionExh(),
            Tabla_NosePudoEntrar(),
            Tabla_Seis()
            ])
        .then(respuestas =>{
            FiltrosDeBusqueda();
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                Swal.fire({
                    type: 'error',
                    title: error,
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });
    });
}

/*--------------------TABLA GENERAL UNO------------------------------*/
function Tabla_ClteGenralUno(){

    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: 1});
    // cnsole.log(datas);
    return new Promise(function(resolve, reject){


        $.ajax({
            url      : 'ls_reportuno/tabla_uno',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){

                if(_resp.rs == true){
                    var tabla_html = ``;
                    tabla_html += `
                    <table id="tabla_CtleUno">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="15%">PAIS</th>
                                <th width="15%">DIVISION</th>
                                <th width="15%">DISTRITO</th>
                                <th width="15%">RUTA</th>
                                <th width="10%">TOTAL PDV</th>
                                <th width="10%">T. PDV ACTUALIZADOS</th>
                                <th width="10%">T. PDV NO ACTUALIZADOS</th>
                                <th width="5%">% AVANCE</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        
                        var cont_SV = 0; var cont_GT = 0; var cont_HN = 0; var cont_RDO = 0;
                        var cont_TODO = 0;
                        var totalarrg = _resp.ls_GeneralUno.length;
                        _resp.ls_GeneralUno.forEach(function(filall,index, arrgfilall){
                            
                            if(filall.Nombre_Pais === 'EL SALVADOR'){
                                cont_SV++;

                            }

                            if(filall.Nombre_Pais === 'GUATEMALA'){
                                cont_GT++;
                            }

                            if(filall.Nombre_Pais === 'HONDURAS'){
                                cont_HN++;
                            }

                            if(filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                cont_RDO++;
                            }
                            cont_TODO++;
                            if(cont_TODO == totalarrg){

                                var conta_MAX = 0;
                                var fila = 1;
                                _resp.ls_GeneralUno.forEach(function(filall,index, arrgfilall){
                                

                                    nuevoArray[index] = new Array(2);

                                    fila++;
                                    conta_MAX++;
                                    estadoZoomGrupo[index] = 0;

                                    tabla_html += `
                                    <tr id="fila_${index}">
                                        <td><button type="button" class="btn btn_cerrado zoomUNO" id="despliegeUNO_${index}"><span id='span_zoomGru${index}' class='fa fa-search-plus fa-lg'></span></button></td>
                                        <td class="fila_colorU" id="np${index}">${filall.Nombre_Pais}</td>
                                        <td class="borde_de" id="nd${index}">${filall.Division}</td>
                                        <td class="borde_de"></td>
                                        <td class="borde_de"></td>
                                        <td class="borde_de">${filall.totalpdv}</td>
                                        <td class="borde_de">${filall.pdvactualizados}</td>
                                        <td>${filall.NOpdvactualizados}</td>
                                        <td class="fila_colorAU">${filall.avance} %</td>
                                    </tr>`;

                                    if(conta_MAX == cont_SV && filall.Nombre_Pais === 'EL SALVADOR'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpv[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvAC[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvACNO[0]}</td>
                                            <td class="fila_colorAUT">${_resp.tpvPOR[0]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if(conta_MAX == cont_GT && filall.Nombre_Pais === 'GUATEMALA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpv[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvAC[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvACNO[1]}</td>
                                            <td class="fila_colorAUT">${_resp.tpvPOR[1]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_HN && filall.Nombre_Pais === 'HONDURAS'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"</td>
                                            <td class="fila_colorUT">${_resp.tpv[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvAC[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvACNO[2]}</td>
                                            <td class="fila_colorAUT">${_resp.tpvPOR[2]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_RDO && filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpv[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvAC[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvACNO[3]}</td>
                                            <td class="fila_colorAUT">${_resp.tpvPOR[3]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }

                                    // indexes_clientes[index] = filall.Id_Cliente;
                                });


                            }
                        });
                            tabla_html += `
                            <tr>
                                <td class="fila_colorU"></td>
                                <td class="fila_colorU">TOTAL CAM</td>
                                <td class="fila_colorU"></td>
                                <td class="fila_colorU"></td>
                                <td class="fila_colorU"></td>
                                <td class="fila_colorU">${_resp.FullTotales[0]}</td>
                                <td class="fila_colorU">${_resp.FullTotales[1]}</td>
                                <td class="fila_colorU">${_resp.FullTotales[2]}</td>
                                <td class="fila_colorU">${_resp.FullTotales[3]} %</td>
                            </tr>
                        </tbody>
                    </table>`;

                    $("#Lista_GenralUnohtml").empty().html(tabla_html);
                    resolve(1);
                }else{

                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<h6>'+_resp.info+'</h6>',
                            confirmButtonText:'Ok'
                        });
                    reject(0);

                }

        }).fail(function(status, textStatus, errorThrown) {

                _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });
    });
}
/*-------FINAL TABLA GENERAL UNO*/


/*--------------------TABLA GENERAL DOS------------------------------*/

function Tabla_CONSIN_Exh(){

    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: 1});
    // cnsole.log(datas);
    return new Promise(function(resolve, reject){


        $.ajax({
            url      : 'ls_reportdos/tabla_dos',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){

                if(_resp.rs == true){
                    var tabla_html = ``;
                    tabla_html += `
                    <table id="tabla_CtleDos">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="15%">PAIS</th>
                                <th width="15%">DIVISION</th>
                                <th width="13%">DISTRITO</th>
                                <th width="8%">RUTA</th>
                                <th width="8%">PDV ACT.</th>
                                <th width="10%">PDV SIN EXH</th>
                                <th width="10%">PDV CON EXH</th>
                                <th width="8%">% SIN EXH</th>
                                <th width="8%">% CON EXH</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        
                        var cont_SV = 0; var cont_GT = 0; var cont_HN = 0; var cont_RDO = 0;
                        var cont_TODO = 0;
                        var totalarrg = _resp.ls_GeneralDos.length;
                        _resp.ls_GeneralDos.forEach(function(filall,index, arrgfilall){

                            if(filall.Nombre_Pais === 'EL SALVADOR'){
                                cont_SV++;
                            }

                            if(filall.Nombre_Pais === 'GUATEMALA'){
                                cont_GT++;
                            }

                            if(filall.Nombre_Pais === 'HONDURAS'){
                                cont_HN++;
                            }

                            if(filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                cont_RDO++;
                            }
                            cont_TODO++;
                            if(cont_TODO == totalarrg){

                                var conta_MAX = 0;
                                var fila = 1;
                                _resp.ls_GeneralDos.forEach(function(filall,index, arrgfilall){
                                

                                    nuevoArrayB[index] = new Array(2);

                                    fila++;
                                    conta_MAX++;
                                    estadoZoomGrupoB[index] = 0;
                                    tabla_html += `
                                    <tr id="filaB_${index}">
                                        <td class="Borde_AUB"><button type="button" class="btn btn_cerrado zoomUNOB" id="despliegeUNOB_${index}"><span id='span_zoomGruB${index}' class='fa fa-search-plus fa-lg'></span></button></td>
                                        <td class="fila_colorUB" id="npB${index}">${filall.Nombre_Pais}</td>
                                        <td class="borde_deB" id="ndB${index}">${filall.Division}</td>
                                        <td class="borde_deB"></td>
                                        <td class="borde_deB"></td>
                                        <td class="borde_deB">${filall.pdvactualizados}</td>
                                        <td class="borde_deB">${filall.sinexhibidores}</td>
                                        <td>${filall.conexhibidores}</td>
                                        <td class="fila_colorAUB">${filall.avanceSin} %</td>
                                        <td class="fila_colorAUB">${filall.avanceCon} %</td>
                                    </tr>`;
                                    if(conta_MAX == cont_SV && filall.Nombre_Pais === 'EL SALVADOR'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvAC[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvSIN[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvCON[0]}</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORSIN[0]} %</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORCON[0]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if(conta_MAX == cont_GT && filall.Nombre_Pais === 'GUATEMALA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvAC[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvSIN[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvCON[1]}</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORSIN[1]} %</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORCON[1]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_HN && filall.Nombre_Pais === 'HONDURAS'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvAC[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvSIN[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvCON[2]}</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORSIN[2]} %</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORCON[2]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_RDO && filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvAC[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvSIN[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvCON[3]}</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORSIN[3]} %</td>
                                            <td class="fila_colorAUTB">${_resp.tpvPORCON[3]} %</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }

                                    // indexes_clientes[index] = filall.Id_Cliente;
                                });


                            }
                        });
                            tabla_html += `
                            <tr>
                                <td class="fila_colorUB"></td>
                                <td class="fila_colorUB">TOTAL CAM</td>
                                <td class="fila_colorUB"></td>
                                <td class="fila_colorUB"></td>
                                <td class="fila_colorUB"></td>
                                <td class="fila_colorUB">${_resp.FullTotales[0]}</td>
                                <td class="fila_colorUB">${_resp.FullTotales[1]}</td>
                                <td class="fila_colorUB">${_resp.FullTotales[2]}</td>
                                <td class="fila_colorUB">${_resp.FullTotales[3]} %</td>
                                <td class="fila_colorUB">${_resp.FullTotales[4]} %</td>
                            </tr>
                        </tbody>
                    </table>`;
                    $("#Tabla_SinConExh").empty().html(tabla_html);
                    resolve(1);
                }else{

                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<h6>'+_resp.info+'</h6>',
                            confirmButtonText:'Ok'
                        });
                    reject(0);

                }

        }).fail(function(status, textStatus, errorThrown) {

                _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });


    });

}
/*--------------------FIN TABLA GENERAL DOS------------------------------*/

/*--------------------INICIO TABLA GENERAL TRES------------------------------*/


function Tabla_TipoActualizacionExh(){

    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: 1});
    // cnsole.log(datas);
    return new Promise(function(resolve, reject){


        $.ajax({
            url      : 'ls_reporttres/tabla_tres',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){

                if(_resp.rs == true){
                    var tabla_html = ``;
                    tabla_html += `
                    <table id="tabla_CtleTres">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="15%">PAIS</th>
                                <th width="15%">DIVISION</th>
                                <th width="15%">DISTRITO</th>
                                <th width="10%">RUTA</th>
                                <th width="10%">CANT. EXHIBIDORES</th>
                                <th width="10%">EXH. QUE POSEE</th>
                                <th width="10%">EXH. DEVUELTOS</th>
                                <th width="10%">EXH. NUEVOS</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        
                        var cont_SV = 0; var cont_GT = 0; var cont_HN = 0; var cont_RDO = 0;
                        var cont_TODO = 0;
                        var totalarrg = _resp.ls_GeneralUnoTAct.length;
                        _resp.ls_GeneralUnoTAct.forEach(function(filall,index, arrgfilall){

                            if(filall.Nombre_Pais === 'EL SALVADOR'){
                                cont_SV++;
                            }

                            if(filall.Nombre_Pais === 'GUATEMALA'){
                                cont_GT++;
                            }

                            if(filall.Nombre_Pais === 'HONDURAS'){
                                cont_HN++;
                            }

                            if(filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                cont_RDO++;
                            }
                            cont_TODO++;
                            if(cont_TODO == totalarrg){

                                var conta_MAX = 0;
                                var fila = 1;
                                _resp.ls_GeneralUnoTAct.forEach(function(filall,index, arrgfilall){
                                

                                    nuevoArrayC[index] = new Array(2);
                                    estadoZoomGrupoC[index] = 0;
                                    fila++;
                                    conta_MAX++;
                                    tabla_html += `
                                    <tr id="filaC_${index}">
                                        <td class="Borde_AUC"><button type="button" class="btn btn_cerrado zoomUNOC" id="despliegeUNOC_${index}"><span id='span_zoomGruC${index}' class='fa fa-search-plus fa-lg'></span></button></td>
                                        <td class="fila_colorUC" id="npC${index}">${filall.Nombre_Pais}</td>
                                        <td class="borde_deC" id="ndC${index}">${filall.Division}</td>
                                        <td class="borde_deC"></td>
                                        <td class="borde_deC"></td>
                                        <td class="borde_deC">${filall.totalpdvexh}</td>
                                        <td class="borde_deC">${filall.exhquetiene}</td>
                                        <td class="borde_deC">${filall.exhdevueltos}</td>
                                        <td class="borde_deC">${filall.exnuevos}</td>
                                    </tr>`;
                                    if(conta_MAX == cont_SV && filall.Nombre_Pais === 'EL SALVADOR'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvCantidadExh[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdevueltos[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnuevos[0]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if(conta_MAX == cont_GT && filall.Nombre_Pais === 'GUATEMALA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvCantidadExh[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdevueltos[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnuevos[1]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_HN && filall.Nombre_Pais === 'HONDURAS'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvCantidadExh[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdevueltos[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnuevos[2]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_RDO && filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvCantidadExh[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdevueltos[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnuevos[3]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }

                                    // indexes_clientes[index] = filall.Id_Cliente;
                                });


                            }
                        });
                            tabla_html += `
                            <tr>
                                <td class="fila_colorUC"></td>
                                <td class="fila_colorUC">TOTAL CAM</td>
                                <td class="fila_colorUC"></td>
                                <td class="fila_colorUC"></td>
                                <td class="fila_colorUC"></td>
                                <td class="fila_colorUC">${_resp.FullTotales[0]}</td>
                                <td class="fila_colorUC">${_resp.FullTotales[1]}</td>
                                <td class="fila_colorUC">${_resp.FullTotales[2]}</td>
                                <td class="fila_colorUC">${_resp.FullTotales[3]}</td>
                            </tr>
                        </tbody>
                    </table>`;
                    $("#Tabla_TipoActulizacion").empty().html(tabla_html);
                    resolve(1);
                }else{

                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<h6>'+_resp.info+'</h6>',
                            confirmButtonText:'Ok'
                        });
                    reject(0);

                }

        }).fail(function(status, textStatus, errorThrown) {

                _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });


    });

}

/*--------------------FINAL TABLA GENERAL TRES------------------------------*/


/*--------------------INICIO TABLA GENERAL CUATRO------------------------------*/

function Tabla_TipoObservacionExh(){

    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: 1});
    // cnsole.log(datas);
    return new Promise(function(resolve, reject){


        $.ajax({
            url      : 'ls_reportcuatro/tabla_cuatro',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){

                if(_resp.rs == true){
                    var tabla_html = ``;
                    tabla_html += `
                    <table id="tabla_CtleCuatro">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="12%">PAIS</th>
                                <th>DIVISION</th>
                                <th>DISTRITO</th>
                                <th width="5%">RUTA</th>
                                <th>EXH. QUE POSEE</th>
                                <th>EXH. DESECHADOS</th>
                                <th>EXH. INVADIDOS</th>
                                <th>EXH. MAL UBICADOS</th>
                                <th>EXH. RETIRADOS</th>
                                <th>EXH. VISIBLE Y ACCESIBLE</th>
                                <th>EXH. NECESITA REPARACION</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        
                        var cont_SV = 0; var cont_GT = 0; var cont_HN = 0; var cont_RDO = 0;
                        var cont_TODO = 0;
                        var totalarrg = _resp.ls_GeneralUnoTObserv.length;
                        _resp.ls_GeneralUnoTObserv.forEach(function(filall,index, arrgfilall){

                            if(filall.Nombre_Pais === 'EL SALVADOR'){
                                cont_SV++;
                            }

                            if(filall.Nombre_Pais === 'GUATEMALA'){
                                cont_GT++;
                            }

                            if(filall.Nombre_Pais === 'HONDURAS'){
                                cont_HN++;
                            }

                            if(filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                cont_RDO++;
                            }
                            cont_TODO++;
                            if(cont_TODO == totalarrg){

                                var conta_MAX = 0;
                                var fila = 1;
                                _resp.ls_GeneralUnoTObserv.forEach(function(filall,index, arrgfilall){
                                

                                    nuevoArrayD[index] = new Array(2);
                                    estadoZoomGrupoD[index] = 0;
                                    fila++;
                                    conta_MAX++;
                                    tabla_html += `
                                    <tr id="filaD_${index}">
                                        <td class="Borde_AUD"><button type="button" class="btn btn_cerrado zoomUNOD" id="despliegeUNOD_${index}"><span id='span_zoomGruD${index}' class='fa fa-search-plus fa-lg'></span></button></td>
                                        <td class="fila_colorUD" id="npD${index}">${filall.Nombre_Pais}</td>
                                        <td class="borde_deD" id="ndD${index}">${filall.Division}</td>
                                        <td class="borde_deD"></td>
                                        <td class="borde_deD"></td>
                                        <td class="borde_deD">${filall.exhquetiene}</td>
                                        <td class="borde_deD">${filall.exhdesechados}</td>
                                        <td class="borde_deD">${filall.exhinvadido}</td>
                                        <td class="borde_deD">${filall.exhmalubicado}</td>
                                        <td class="borde_deD">${filall.exhretirado}</td>
                                        <td class="borde_deD">${filall.exhvisibles}</td>
                                        <td class="borde_deD">${filall.exhnecesitare}</td>
                                    </tr>`;
                                    if(conta_MAX == cont_SV && filall.Nombre_Pais === 'EL SALVADOR'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdesechados[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhinvadidos[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhmalubicados[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhretirados[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhvisibleyacce[0]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnecesitar[0]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if(conta_MAX == cont_GT && filall.Nombre_Pais === 'GUATEMALA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdesechados[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhinvadidos[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhmalubicados[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhretirados[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhvisibleyacce[1]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnecesitar[1]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_HN && filall.Nombre_Pais === 'HONDURAS'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdesechados[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhinvadidos[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhmalubicados[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhretirados[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhvisibleyacce[2]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnecesitar[2]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_RDO && filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tpvExhquetiene[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhdesechados[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhinvadidos[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhmalubicados[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhretirados[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhvisibleyacce[3]}</td>
                                            <td class="fila_colorUT">${_resp.tpvExhnecesitar[3]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }

                                    // indexes_clientes[index] = filall.Id_Cliente;
                                });


                            }
                        });
                            tabla_html += `
                            <tr>
                                <td class="fila_colorUD"></td>
                                <td class="fila_colorUD">TOTAL CAM</td>
                                <td class="fila_colorUD"></td>
                                <td class="fila_colorUD"></td>
                                <td class="fila_colorUD"></td>
                                <td class="fila_colorUD">${_resp.FullTotales[0]}</td>
                                <td class="fila_colorUD">${_resp.FullTotales[1]}</td>
                                <td class="fila_colorUD">${_resp.FullTotales[2]}</td>
                                <td class="fila_colorUD">${_resp.FullTotales[3]}</td>
                                <td class="fila_colorUD">${_resp.FullTotales[4]}</td>
                                <td class="fila_colorUD">${_resp.FullTotales[5]}</td>
                                <td class="fila_colorUD">${_resp.FullTotales[6]}</td>
                            </tr>
                        </tbody>
                    </table>`;
                    $("#Tabla_TipoObservacion").empty().html(tabla_html);
                    resolve(1);
                }else{

                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<h6>'+_resp.info+'</h6>',
                            confirmButtonText:'Ok'
                        });
                    reject(0);

                }

        }).fail(function(status, textStatus, errorThrown) {

                _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });


    });

}

/*--------------------FINAL TABLA GENERAL CUATRO-------------------------------*/


/*--------------------INICIO TABLA GENERAL CINCO-------------------------------*/


function Tabla_NosePudoEntrar(){

    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: 1});
    // cnsole.log(datas);
    return new Promise(function(resolve, reject){


        $.ajax({
            url      : 'ls_reportcinco/tabla_cinco',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){

                if(_resp.rs == true){
                    var tabla_html = ``;
                    tabla_html += `
                    <table id="tabla_CtleCinco">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="15%">PAIS</th>
                                <th>DIVISION</th>
                                <th>DISTRITO</th>
                                <th width="10%">RUTA</th>
                                <th>NO SE PUDO ENTRAR AL PDV</th>
                                <th>PDV CERRADO</th>
                                <th>PDV NO ENCONTRADO</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        
                        var cont_SV = 0; var cont_GT = 0; var cont_HN = 0; var cont_RDO = 0;
                        var cont_TODO = 0;
                        var totalarrg = _resp.ls_GeneralUnoNoseEntrar.length;
                        _resp.ls_GeneralUnoNoseEntrar.forEach(function(filall,index, arrgfilall){

                            if(filall.Nombre_Pais === 'EL SALVADOR'){
                                cont_SV++;
                            }

                            if(filall.Nombre_Pais === 'GUATEMALA'){
                                cont_GT++;
                            }

                            if(filall.Nombre_Pais === 'HONDURAS'){
                                cont_HN++;
                            }

                            if(filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                cont_RDO++;
                            }
                            cont_TODO++;
                            if(cont_TODO == totalarrg){

                                var conta_MAX = 0;
                                var fila = 1;
                                _resp.ls_GeneralUnoNoseEntrar.forEach(function(filall,index, arrgfilall){
                                

                                    nuevoArrayE[index] = new Array(2);
                                    estadoZoomGrupoE[index] = 0;
                                    fila++;
                                    conta_MAX++;
                                    tabla_html += `
                                    <tr id="filaE_${index}">
                                        <td class="Borde_AUE"><button type="button" class="btn btn_cerrado zoomUNOE" id="despliegeUNOE_${index}"><span id='span_zoomGruE${index}' class='fa fa-search-plus fa-lg'></span></button></td>
                                        <td class="fila_colorUE" id="npE${index}">${filall.Nombre_Pais}</td>
                                        <td class="borde_deE" id="ndE${index}">${filall.Division}</td>
                                        <td class="borde_deE"></td>
                                        <td class="borde_deE"></td>
                                        <td class="borde_deE">${filall.CliNoEntrar}</td>
                                        <td class="borde_deE">${filall.CerradoTienda}</td>
                                        <td>${filall.NoseEncontroT}</td>
                                    </tr>`;
                                    if(conta_MAX == cont_SV && filall.Nombre_Pais === 'EL SALVADOR'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tCliNoEntrar[0]}</td>
                                            <td class="fila_colorUT">${_resp.tEstabaCerrado[0]}</td>
                                            <td class="fila_colorUT borde_deE">${_resp.tNoseEncontroCli[0]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if(conta_MAX == cont_GT && filall.Nombre_Pais === 'GUATEMALA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tCliNoEntrar[1]}</td>
                                            <td class="fila_colorUT">${_resp.tEstabaCerrado[1]}</td>
                                            <td class="fila_colorUT borde_deE">${_resp.tNoseEncontroCli[1]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_HN && filall.Nombre_Pais === 'HONDURAS'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tCliNoEntrar[2]}</td>
                                            <td class="fila_colorUT">${_resp.tEstabaCerrado[2]}</td>
                                            <td class="fila_colorUT borde_deE">${_resp.tNoseEncontroCli[2]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }else if (conta_MAX == cont_RDO && filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                        tabla_html += `
                                        <tr>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT"></td>
                                            <td class="fila_colorUT">${_resp.tCliNoEntrar[3]}</td>
                                            <td class="fila_colorUT">${_resp.tEstabaCerrado[3]}</td>
                                            <td class="fila_colorUT borde_deE">${_resp.tNoseEncontroCli[3]}</td>
                                        </tr>`;
                                        conta_MAX = 0;
                                    }

                                    // indexes_clientes[index] = filall.Id_Cliente;
                                });

                            }
                        });
                            tabla_html += `
                            <tr>
                                <td class="fila_colorUE"></td>
                                <td class="fila_colorUE">TOTAL CAM</td>
                                <td class="fila_colorUE"></td>
                                <td class="fila_colorUE"></td>
                                <td class="fila_colorUE"></td>
                                <td class="fila_colorUE">${_resp.FullTotales[0]}</td>
                                <td class="fila_colorUE">${_resp.FullTotales[1]}</td>
                                <td class="fila_colorUE borde_deE">${_resp.FullTotales[2]}</td>
                            </tr>
                        </tbody>
                    </table>`;
                    $("#Tabla_noSePudoENTRAR").empty().html(tabla_html);
                    resolve(1);
                }else{

                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<h6>'+_resp.info+'</h6>',
                            confirmButtonText:'Ok'
                        });
                    reject(0);

                }

        }).fail(function(status, textStatus, errorThrown) {

                _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });


    });

}



/*---------------------------------------------------------------------------------*/
/*_______.-.-.-.-.-.-.-.-.-. FINAL TABLA 6*/


function Tabla_Seis(){

    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: 1});
    // cnsole.log(datas);
    return new Promise(function(resolve, reject){


        $.ajax({
            url      : 'ls_reportsesis/tabla_seis',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){

                if(_resp.rs == true){
                    var tabla_html = ``;
                    tabla_html += `
                 
                    <table id="tabla_CtleSeis">
                        <thead>
                            <tr>
                                <th width="50px" class="sticky"></th>
                                <th width="150px" class="sticky_2">PAIS</th>
                                <th width="150px" class="sticky_3">DIVISION</th>
                                <th width="150px" class="sticky_4">DISTRITO</th>
                                <th width="110px" class="sticky_5">RUTA</th>`;
                                _resp.ls_Fechas.forEach(function(filall,index, arrgfilall){
                                    tabla_html += `<th class="columna_fecha">${filall}</th>`;
                                });

                    tabla_html += `<th width="140px">TOTAL GENERAL</th></tr>
                        </thead>
                        <tbody>`
                        var arrgprue = new Array();
                        var arrgpruedoprueb = new Array();
                        var totalarrgfe = _resp.ls_Fechas.length;
                        // totalarrg = totalarrg - 1;
                        var cont_SV = 0; var cont_GT = 0; var cont_HN = 0; var cont_RDO = 0;
                        var cont_TODO = 0;var totalarrg = _resp.ls_Divisiones.length;

                        _resp.ls_Divisiones.forEach(function(filall,index, arrgfilall){
                            if(filall.Nombre_Pais === 'EL SALVADOR'){
                                cont_SV++;
                            }

                            if(filall.Nombre_Pais === 'GUATEMALA'){
                                cont_GT++;
                            }

                            if(filall.Nombre_Pais === 'HONDURAS'){
                                cont_HN++;
                            }

                            if(filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                cont_RDO++;
                            }

                            cont_TODO++;
                            if(cont_TODO == totalarrg){
                                var conta_MAX = 0;var conta = 0;

                                _resp.ls_Divisiones.forEach(function(filall,index, arrgfilall){

                                    nuevoArrayF[index] = new Array(2);
                                    estadoZoomGrupoF[index] = 0;
                                    var contaVecez = 0;
                                    conta_MAX++;
                                    tabla_html+=`<tr id="filaF_${index}">
                                        <td class="sticky Borde_AUF"><button type="button" class="btn btn_cerrado zoomUNOF" id="despliegeUNOF_${index}"><span id='span_zoomGruF${index}' class='fa fa-search-plus fa-lg'></span></button></td>
                                        <td class="sticky_2 fila_colorUF" id="npF${index}">${filall.Nombre_Pais}</td>
                                        <td class="sticky_3 borde_deF" id="ndF${index}">${filall.Division}</td>
                                        <td class="sticky_4 borde_deF"></td>
                                        <td class="sticky_5 borde_deF"></td>`;
                                    
                                    for(o=0;o<totalarrgfe;o++){
                                        tabla_html+=`<td>${_resp.ls_CuenXdia[conta].totalpdv}</td>`;
                                        conta++;contaVecez++;
                                        if(contaVecez == totalarrgfe){
                                            tabla_html+=`<td>${_resp.ls_TotalPorDivison[index].totalactualizados}</td>`;
                                        }else{

                                        }
                                    }

                                    tabla_html+=`</tr>`;
                                    if(conta_MAX == cont_SV && filall.Nombre_Pais === 'EL SALVADOR'){
                                        var contas = 0;
                                        tabla_html+=`<tr>
                                        <td class="sticky fila_colorUT"></td>
                                        <td class="sticky_2 fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                        <td class="sticky_3 fila_colorUT"></td>
                                        <td class="sticky_4 fila_colorUT"></td>
                                        <td class="sticky_5 fila_colorUT"></td>`;
                                        for(s=0;s<totalarrgfe;s++){
                                            tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalSV[contas]}</td>`;
                                            contas++;
                                        }
                                        tabla_html+=`<td class="fila_colorTF">${_resp.ls_TotalPorPais['ELSALVADOR']}</td>`;
                                        tabla_html+=`</tr>`;
                                        conta_MAX = 0;
                                    }

                                    if(conta_MAX == cont_GT && filall.Nombre_Pais === 'GUATEMALA'){
                                        var contag = 0;
                                        tabla_html+=`<tr>
                                        <td class="sticky fila_colorUT"></td>
                                        <td class="sticky_2 fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                        <td class="sticky_3 fila_colorUT"></td>
                                        <td class="sticky_4 fila_colorUT"></td>
                                        <td class="sticky_5 fila_colorUT"></td>`;
                                        for(g=0;g<totalarrgfe;g++){
                                            tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalGT[contag]}</td>`;
                                            contag++;
                                        }
                                         tabla_html+=`<td class="fila_colorTF">${_resp.ls_TotalPorPais['GUATEMALA']}</td>`;
                                        tabla_html+=`</tr>`;
                                        conta_MAX = 0;
                                    }

                                    if(conta_MAX == cont_HN && filall.Nombre_Pais === 'HONDURAS'){
                                        var contah = 0;
                                        tabla_html+=`<tr>
                                        <td class="sticky fila_colorUT"></td>
                                        <td class="sticky_2 fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                        <td class="sticky_3 fila_colorUT"></td>
                                        <td class="sticky_4 fila_colorUT"></td>
                                        <td class="sticky_5 fila_colorUT"></td>`;
                                        for(h=0;h<totalarrgfe;h++){
                                            tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalHN[contah]}</td>`;
                                            contah++;
                                        }
                                         tabla_html+=`<td class="fila_colorTF">${_resp.ls_TotalPorPais['HONDURAS']}</td>`;
                                        tabla_html+=`</tr>`;
                                        conta_MAX = 0;
                                    }

                                    if(conta_MAX == cont_RDO && filall.Nombre_Pais === 'REPUBLICA DOMINICANA'){
                                        var contar = 0;
                                        tabla_html+=`<tr>
                                        <td class="sticky fila_colorUT"></td>
                                        <td class="sticky_2 fila_colorUT">TOTAL ${filall.Nombre_Pais}</td>
                                        <td class="sticky_3 fila_colorUT"></td>
                                        <td class="sticky_4 fila_colorUT"></td>
                                        <td class="sticky_5 fila_colorUT"></td>`;
                                        for(r=0;r<totalarrgfe;r++){
                                            tabla_html+=`<td class="fila_colorUT">${_resp.ls_TotalRDO[contar]}</td>`;
                                            contar++;
                                        }
                                         tabla_html+=`<td class="fila_colorTF">${_resp.ls_TotalPorPais['REPUBLICADOMINICANA']}</td>`;
                                        tabla_html+=`</tr>`;
                                        conta_MAX = 0;
                                    }


                                });    
                            }

                        });
                    var contarrr = 0;
                    tabla_html += `
                    <tr>
                        <td class="sticky fila_colorUF"></td>
                        <td class="sticky_2 fila_colorUF">TOTAL CAM</td>
                        <td class="sticky_3 fila_colorUF"></td>
                        <td class="sticky_4 fila_colorUF"></td>
                        <td class="sticky_5 fila_colorUF"></td>`;
                        for(r=0;r<totalarrgfe;r++){
                            tabla_html+=`<td class="fila_colorUF">${_resp.ls_TotalPorDia[contarrr].totalpdv}</td>`;
                            contarrr++;
                        }

                    tabla_html += `
                    <td class="fila_colorUF">${_resp.TotalCAM}</td>
                    </tr>
                    </tbody>
                    </table>`;
                    $("#TablaCliXdias").empty().html(tabla_html);
                    resolve(1);
                }else{

                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<h6>'+_resp.info+'</h6>',
                            confirmButtonText:'Ok'
                        });
                    reject(0);

                }

        }).fail(function(status, textStatus, errorThrown) {

                _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });


    });

}


/*---------------------FINAL TABLA GENERAL CINCO-------------------------------*/
function Tabla_ClteConCenso(page){

    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: page});




    // console.log(datas);
    return new Promise(function(resolve, reject){

        $.ajax({
            url      : 'ls_cltecensados/mostrar',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){
                if(_resp.rs == true){
                    var tabla_html = ``;
                    tabla_html += `
                    <table id="tabla_CtleCensados">
                        <thead>
                            <tr>
                                <th width="7%"></th>
                                <th>RUTA</th>
                                <th>CÓDIGO</th>
                                <th width="177px">FOTO</th>
                                <th>NOMBRE</th>
                                <th>CONTACTO</th>
                                <th>DIRECCIÓN</th>
                                <th>PAIS</th>
                                <th>DIVISION</th>
                                <th>CANAL</th>
                                <th>DISTRITO</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    _resp.ls_listaCtlCensados.forEach(function(filall,index, arrgfilall){

                        if(filall.FotoObservacion === 0){    
                            fotoexhibidor = '../dependencias/imagenes/icon_256.png'
                        }else{
                            fotoexhibidor = '../../img_server/'+filall.FotoObservacion;
                        }


                        tabla_html += `
                        <tr>
                            <td height="50px"><button type="button" class="btn btn_carpeta abrirmodal" id="despliege_${index}"><span class='fa fa-folder-plus fa-3x'></span></button></td>
                            <td>${filall.Nombre_Ruta}</td>
                            <td>${filall.CodigoCliente}</td>
                            <td><img src="${fotoexhibidor}" class="foto_collage"></td>
                            <td>${filall.NombreCliente}</td>
                            <td>${filall.ContactoCliente}</td>
                            <td>${filall.DireccionCliente}</td>
                            <td>${filall.Nombre_Pais}</td>
                            <td>${filall.Division}</td>
                            <td>${filall.Canal}</td>
                            <td>${filall.Grupo}</td>
                        </tr>`;
                        indexes_clientes[index] = filall.Id_Cliente;
                    });
                    tabla_html += `
                        </tbody>
                    </table>`;
                    
                        $("#Content_tabla_lsClteCensados").empty().html(tabla_html);
                        $("#pag_tabla_LSCtleC").empty().html(_resp.paginado);
                        resolve(1);
                    if(cargaSolo === 1){
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        });            
                    }
                }else{
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h6>'+_resp.info+'</h6>',
                        confirmButtonText:'Ok'
                    });
                    reject(0);
                }

        }).fail(function(status, textStatus, errorThrown) {
            _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });


    });

    

}

function FiltrosDeBusqueda(){


    // if( $("#filtropais").val() !="" && $("#filtrodivision").val() =="" && $("#filtrocanales").val() =="" ){

        
    // }else{
        datas = $("#form_lsClteCensados").serializeArray();
        datas.push({name: 'page',value: 1});
        // console.log('loque se envia');
        // console.log(datas);
        $.ajax({
            url      : 'filtros/rexhibidores',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 60777
        }).done(function(_resp){
                if(_resp.rs == true){
                    var filtro_htmlPais = ``;
                    var filtro_htmlDivisiones = ``;
                    var filtro_htmlCanal = ``;

                    if(Object.entries(_resp.ls_filtropais).length == 1){
                        filtro_htmlPais += `<select class="form-control" id="filtropais" name="filtropais">`;
                    }else{
                        filtro_htmlPais += `<select class="form-control" id="filtropais" name="filtropais">
                        <option value="">TODOS LOS PAISES</option>`;           
                    }
                    _resp.ls_filtropais.forEach(function(filall,index, arrgfilall){
                        if(Object.entries(_resp.ls_filtropais).length == 1){
                            filtro_htmlPais+=`<option value="${filall.Id_Pais}" selected>${filall.Nombre_Pais}</option>`;
                        }else{
                            if(Recordar_Fpais == filall.Id_Pais){
                                filtro_htmlPais+=`<option value="${filall.Id_Pais}" selected>${filall.Nombre_Pais}</option>`;
                            }else{
                                filtro_htmlPais+=`<option value="${filall.Id_Pais}">${filall.Nombre_Pais}</option>`;
                            }
                        }
                    });
                    filtro_htmlPais+=`</select>`;
                    $("#S_filtroPais").empty().html(filtro_htmlPais);
                    /*-------------------------------------------------------------------------------------*/
                    filtro_htmlDivisiones += `<select class="form-control" id="filtrodivision" name="filtrodivision">
                    <option value="">TODAS LAS DIVISIONES</option>`;
                    _resp.ls_filtrodivisiones.forEach(function(filall,index, arrgfilall){
                        // if(Object.entries(_resp.ls_filtrodivisiones).length == 1){
                        //     filtro_htmlDivisiones+=`<option value="${filall.Division}" selected>${filall.Division}</option>`;
                        // }else{
                            if(Recordar_Fdivision == filall.Division){
                                filtro_htmlDivisiones+=`<option value="${filall.Division}" selected>${filall.Division}</option>`;
                            }else{
                                filtro_htmlDivisiones+=`<option value="${filall.Division}">${filall.Division}</option>`;
                            }             
                        // }
                    });
                    filtro_htmlDivisiones+=`</select>`;
                    $("#S_filtroDivision").empty().html(filtro_htmlDivisiones);
                    /*-------------------------------------------------------------------------------------*/
                    filtro_htmlCanal += `<select class="form-control" id="filtrocanales" name="filtrocanales">
                    <option value="">TODOS LOS CANALES</option>`;
                    _resp.ls_filtrocanal.forEach(function(filall,index, arrgfilall){
                        if(Object.entries(_resp.ls_filtrocanal).length == 1){
                            filtro_htmlCanal+=`<option value="${filall.Canal}" selected>${filall.Canal}</option>`;
                        }else{
                            if(Recordar_Fcanal == filall.Canal){
                                filtro_htmlCanal+=`<option value="${filall.Canal}" selected>${filall.Canal}</option>`;
                            }else{
                                filtro_htmlCanal+=`<option value="${filall.Canal}">${filall.Canal}</option>`;
                            }                            
                        }
                    });
                    filtro_htmlCanal+=`</select>`;
                    $("#S_filtroCanales").empty().html(filtro_htmlCanal);
                    /*-------------------------------------------------------------------------------------*/
                    var filtro_htmlDistrito = ``;
                    filtro_htmlDistrito += `<select class="form-control" id="filtrodistritos" name="filtrodistritos">
                    <option value="">TODOS LOS DISTRITOS</option>`;
                    _resp.ls_filtroDistrito.forEach(function(filall,index, arrgfilall){
                        filtro_htmlDistrito+=`<option value="${filall.Grupo}">${filall.Grupo}</option>`;
                    });
                    filtro_htmlDistrito+=`</select>`;
                    $("#S_filtroDistritos").empty().html(filtro_htmlDistrito);
                    /*-------------------------------------------------------------------------------------*/


                    if( $("#filtrodistritos").val() !="" ){
                        $("#S_filtroRuta").show();
                        var filtro_htmlRuta = ``;
                        filtro_htmlRuta += `<select class="form-control" id="filtrorutas" name="filtrorutas">
                        <option value="">TODAS LAS RUTAS</option>`;
                        _resp.ls_filtroRuta.forEach(function(filall,index, arrgfilall){
                            filtro_htmlRuta+=`<option value="${filall.Id_Ruta}">${filall.Nombre_Ruta}</option>`;
                        });
                        filtro_htmlRuta+=`</select>`;
                        $("#S_filtroRuta").empty().html(filtro_htmlRuta);
                        $('#filtrorutas').select2();
                    }else{
                        $("#S_filtroRuta").hide();
                        var filtro_htmlRuta = ``;
                        filtro_htmlRuta += `<select class="form-control" id="filtrorutas" name="filtrorutas">
                        <option value="">TODAS LAS RUTAS</option>`;
                        filtro_htmlRuta+=`</select>`;
                        $("#S_filtroRuta").empty().html(filtro_htmlRuta);
                    }

                    $.fn.select2.defaults.set("theme", "bootstrap");
                    $( "#filtrodistritos" ).select2({
                        theme: "bootstrap"
                    });
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        Swal.fire({
                            type: 'success',
                            title: 'REPORTE CARGADO EXITOSAMENTE!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            console.log('pais '+Recordar_Fpais);
                            console.log('division '+Recordar_Fdivision);
                            console.log('canal '+Recordar_Fcanal);
                        });

                    });
                }else{
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h6>'+_resp.info+'</h6>',
                        confirmButtonText:'Ok'
                    });
                }
        }).fail(function(status, textStatus, errorThrown) {
            _ajax_error_Exhibidores(status,textStatus,errorThrown);

        });
    // }
}

/*<<<< FUNCIONE EXTRAS >>>>>>>>>>*/
/*<<<< VALIDACION FAIL AJAX >>>>>*/
function _ajax_error_Exhibidores(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, volver a cargar la pagina por favor.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html:'<h3>Sin conexión a intenet....</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 200) {
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html:'<h3>Sin conexión a intenet....</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR == 404) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Página solicitada no encontrada[404]</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (jqXHR == 500) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error de servidor interno [500].</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (textStatus === 'parsererror') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (textStatus === 'abort') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error desconocido, por favor contactar con Sistemas de Venta...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }
}
function requestFullscreen(element) {
  if(element.requestFullscreen){
    element.requestFullscreen();
  }else if(element.mozRequestFullScreen){
    element.mozRequestFullScreen();
  }else if(element.webkitRequestFullScreen){
    element.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
  }
}
function toggleFullScreen() {
  if(document.fullscreenEnabled){
    requestFullscreen(document.documentElement);
    $("#ModalAbrirExpendiente").modal("toggle");
  }
}


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
function iniciar_mapa(latiOB,longiOB,latiC,longiC) {
   
        $("#map").attr("style","height: 400px;width: 98%;margin:0 auto;");

        var map = new L.Map('map');
        // map = new L.Map('map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 18
        }).addTo(map);

        if(V_CoordenadasLL(latiOB)){
            latiOB = latiOB;
        }else{latiOB = 0}

        if(V_CoordenadasLL(longiOB)){
            longiOB = longiOB;
        }else{longiOB = 0}

        if(V_CoordenadasLL(latiC)){
            latiC = latiC;
        }else{latiC = 0}

        if(V_CoordenadasLL(longiC)){
            longiC = longiC;
        }else{longiC = 0}

        map.setView(new L.LatLng(latiC, longiC),18);
        

        var circle = L.circle([latiC, longiC], {
            color: '#3ACA31',
            fillColor: '#51DF48',
            fillOpacity: 0.5,
            radius: 50
        }).addTo(map);
        var marker = L.marker([latiOB, longiOB]).addTo(map);

}