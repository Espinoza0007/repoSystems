var mis_codigos = [];var mis_codigosK = [];
var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
// var mis_codigos_duplicadosC = [];var mis_codigos_duplicadosT = [];var mis_codigos_duplicadosN = [];
var arrg_checqueados = [];
var scrollx = 0;var scrolly = 0;
var warn_on_unload = '';
var arrg_vali_result = [];
var canvassu = '';
var canvasex = '';
var blockF=0;
var DataTB="";
var busqueda_param = [];var busqueda_param_s = [];
var arrg_plus_minus = [];
var mis_rutas = [];
var toggle_aco = 7777777;
var arrg_plus_minus_sub = [];var toggle_aco_sub_tr = 7777777;var toggle_aco_sub_subaco = 7777777;
var map;
var editar_count = 0;var cantidad_subaco = 0;
var Recordar_Rutas = "";
var indexes_clientes = new Array();
var arrg_CredlsAC = new Array();
var arrg_Credls = [];var codigActualAC = 0;
var esDUI = '';var esNIT = '';
var formatoTel = '';var formatoNumIP = '';var fotmatoNumNit = '';
function DB_IniciarCPSesionCliAct_Admin() {
    dataBaseAppSDV = indexedDB.open('DBAppSDV',1);
    dataBaseAppSDV.onsuccess = function (e) {
        cantidad_idx_us = 0;
        var activedos = dataBaseAppSDV.result;
        var transaction = activedos.transaction(['tbl_usuarios'], 'readonly');
        var objectStore = transaction.objectStore('tbl_usuarios');
        var countRequest = objectStore.count();
        countRequest.onsuccess = function() {
            if( countRequest.result > 0  ){
                var activedo = dataBaseAppSDV.result;
                var datado = activedo.transaction('tbl_usuarios', "readonly");
                var object = datado.objectStore('tbl_usuarios');
                var elements = [];
                object.openCursor().onsuccess = function (e) {
                    var result = e.target.result;
                    if (result === null) {
                        return;
                    }
                    elements.push(result.value);
                    result.continue();
                };
                datado.oncomplete = function () {
                    DB_CargaCredenciales_Admin();
                };
                datado.onerror = function () {
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h5>Error inesperado [Encuesta Exhibidores, por favor comunicarlo a Sistemas de Venta</h5>',
                        confirmButtonText:'Ok'
                    });
                };
            }else{
                location.href = '/sdv/';
            }
        };
        countRequest.onerror = function(event) {
            location.href = '/sdv/';
        };
    };
    dataBaseAppSDV.onupgradeneeded = function (e) {
        var active = dataBaseAppSDV.result;
        var OBJ_tblusuarios = active.createObjectStore("tbl_usuarios", {keyPath: 'idusuario', autoIncrement: true});
        var OBJ_tbldepartamento = active.createObjectStore("tbl_departamento", {keyPath: 'idepart'});
        var OBJ_tblmunicipio = active.createObjectStore("tbl_municipio", {keyPath: 'idmun'});
        OBJ_tblmunicipio.createIndex('by_depat', 'depat', {unique: false});
        var OBJ_tbltpuntoventa = active.createObjectStore("tbl_tpuntoventa", {keyPath: 'id', autoIncrement: true});
        var OBJ_tblgironegocio = active.createObjectStore("tbl_gironegocio", {keyPath: 'idgiro'});
        OBJ_tblgironegocio.createIndex('by_tpventa', 'tpv', {unique: false});
        var OBJ_tbltfacturacion = active.createObjectStore("tbl_tfacturacion", {keyPath: 'id', autoIncrement: true});
        var OBJ_tblcondicioncli = active.createObjectStore("tbl_condicioncli", {keyPath: 'id', autoIncrement: true});
        var OBJ_tblclingresados = active.createObjectStore("tbl_clingresados", {keyPath: 'id', autoIncrement: true});
        var OBJ_tblclitemporales = active.createObjectStore("tbl_clitemporales", {keyPath: 'id', autoIncrement: true});
        var OBJ_tblexhibidores = active.createObjectStore("tbl_exhibidores", {keyPath: 'idexh'});
        var OBJ_clientesexh = active.createObjectStore("tbl_clientesexh", {keyPath: 'Id_Cliente'});
        var OBJ_observacionexh = active.createObjectStore("tbl_observacionexh", {keyPath: 'id', autoIncrement: true});
        var OBJ_obexhingresados = active.createObjectStore("tbl_obexhingre", {keyPath: 'id', autoIncrement: true});
        var OBJ_exhifacturados = active.createObjectStore("tbl_exhifacturados", {keyPath: 'idexhf'});
        OBJ_exhifacturados.createIndex('by_exhfact', 'exhfact', {unique: false});
        var OBJ_clientesactu = active.createObjectStore("tbl_clientesactu", {keyPath: 'Id_Cliente'});
        var OBJ_clientesactuingre = active.createObjectStore("tbl_clientesactuingre", {keyPath: 'id', autoIncrement: true});
        var OBJ_cliactutempo = active.createObjectStore("tbl_cliactutempo", {keyPath: 'id', autoIncrement: true});
        var OBJ_parametros = active.createObjectStore("tbl_parametros", {keyPath: 'id', autoIncrement: true});
    }
    dataBaseAppSDV.onerror = function (e) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h5>Error inesperado, por favor comunicarlo a Sistemas de Venta</h5>',
            confirmButtonText:'Ok'
        });
    };
}

function DB_CargaCredenciales_Admin(){
    arrg_Credls = [];
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_usuarios', "readonly");
        var object = data.objectStore('tbl_usuarios');
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
            arrg_Credls['usuario'] = elements[0].usuario;
            arrg_Credls['clave'] = elements[0].clave;
            arrg_Credls['privilegio'] = elements[0].privilegio;
            arrg_Credls['ruta_app'] = elements[0].ruta_app;
            arrg_Credls['us_cod'] = elements[0].us_cod;
            arrg_Credls['nombre_us'] = elements[0].nombre_us;
            arrg_Credls['idsupervisor'] = elements[0].idsupervisor;
            arrg_Credls['pais'] = elements[0].pais;
            arrg_Credls['ltdistr'] = elements[0].ltdistr;
            arrg_Credls['RegexTelefono'] = elements[0].RegexTelefono;
            arrg_Credls['CantidTelefono'] = elements[0].CantidTelefono;
            arrg_Credls['FormatoTelefono'] = elements[0].FormatoTelefono;
            $('#txttelefono').mask(elements[0].FormatoTelefono, {placeholder: elements[0].FormatoTelefono});
            formatoTel = elements[0].FormatoTelefono;
            arrg_Credls['RegexNumIP'] = elements[0].RegexNumIP;
            arrg_Credls['CantidNumIP'] = elements[0].CantidNumIP;
            arrg_Credls['FormatoNumIP'] = elements[0].FormatoNumIP;
            $('#txtduid').mask(elements[0].FormatoNumIP, {placeholder: elements[0].FormatoNumIP});
            formatoNumIP = elements[0].FormatoNumIP;
            arrg_Credls['NombreDocumentoDUI'] = elements[0].NombreDocumentoDUI;
            arrg_Credls['RegexNumNIT'] = elements[0].RegexNumNIT;
            arrg_Credls['CantidNumNIT'] = elements[0].CantidNumNIT;
            arrg_Credls['FormatoNumNIT'] = elements[0].FormatoNumNIT;
            $('#txtnitd').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            fotmatoNumNit = elements[0].FormatoNumNIT;
            arrg_Credls['NombreDocumentoNIT'] = elements[0].NombreDocumentoNIT;
            $("#if-tfacturad #docidentidadd").html('<span class="fa fa-id-card fa-lg"></span> '+elements[0].NombreDocumentoDUI+':');
            $("#if-tfacturad #idtributariad").html('<span class="fa fa-id-card-alt fa-lg"></span> '+elements[0].NombreDocumentoNIT+':');

            // alert(elements[0].NombreDocumentoDUI);
            esDUI = elements[0].NombreDocumentoDUI;
            esNIT = elements[0].NombreDocumentoNIT;
            // $("#if-tfactura #docidentidad").html('<span class="fa fa-id-card fa-lg"></span> '+elements[0].NombreDocumentoDUI+':');
            // $("#if-tfactura #idtributaria").html('<span class="fa fa-id-card-alt fa-lg"></span> '+elements[0].NombreDocumentoNIT+':');
            if(elements[0].pais == 'EL SALVADOR'){
                arrg_Credls['CantidadGuionDUI'] = 1;
                arrg_Credls['CantidadGuionNIT'] = 3;
            }else if(elements[0].pais == 'GUATEMALA'){
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
            }else if(elements[0].pais == 'HONDURAS'){
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
            }else if(elements[0].pais == 'REPUBLICA DOMINICANA'){
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
            }else{
                arrg_Credls['CantidadGuionDUI'] = 1;
                arrg_Credls['CantidadGuionNIT'] = 3;
            }
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function validacion_form_actu(){
    var contarok = 0;
    var txtcbfacturacion = '';
    arrg_vali_result = [];
    txtcbfacturacion = $('select[name="cbtfacturaciond"] option:selected').text();
    contarok +=V_Text_LetraNumero($("#txtnombre").val(),'txtnombre',0,'Nombre del establecimiento',2);
    contarok +=V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n',2);
    contarok +=V_Selec($("#cbdepartamentod").val(),'cbdepartamentod',2,'Departamento');
    contarok +=V_Selec($("#cbmunicipiod").val(),'cbmunicipiod',3,'Municipio');
    contarok +=V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',4,'Nombre de contacto');
    var CantidTelefonor = 0;var CantidTelefonot = 0;
    CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
    contarok +=V_numeconMaskguion($("#txttelefono").val(),'txttelefono',5,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot,2);
    contarok +=V_checksd(6,'D&iacute;a de visita');
    if(txtcbfacturacion === 'CREDITO FISCAL'){
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        contarok +=V_numeconMaskguion($("#txtduid").val(),'txtduid',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt,2);
        contarok +=V_NumeroEntero($("#txtnumcontribuyented").val(),'txtnumcontribuyented',8,'N&uacute;mero de contribuyente',2);
        var CantidNumNITr = 0;var CantidNumNITt = 0;
        CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
        var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
        contarok +=V_numeconMaskguion($("#txtnitd").val(),'txtnitd',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt,2);
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
            contarok +=V_numeconMaskguion($("#txtduid").val(),'txtduid',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt,2);
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguion($("#txtnitd").val(),'txtnitd',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt,2);
            contarok +=1;
        }
    }
    contarok +=V_Selec($("#cbfrecuenciavisitad").val(),'cbfrecuenciavisitad',10,'Frecuencia de visita');
    // contarok +=V_NumeroEntero2digitos($("#txtordevisita").val(),'txtordevisita',11,'Orden de visita');
    // var Is_Cheked = document.getElementById('switch-two').checked;
    // if (Is_Cheked){
    //     // console.log('esta activo toma coordendas');
    //     contarok +=V_CoordenadasLL_ContarOK($("#txtlatitud").val(),'txtlatitudm',12,'Latitud');
    //     contarok +=V_CoordenadasLL_ContarOK($("#txtlongitud").val(),'txtlongitudm',13,'Longitud');
    // }else{
    //     contarok+=2;
    //     // console.log('toma de coordendas desactivada');
    // }
    contarok +=V_Selec($("#cbtfacturaciond").val(),'cbtfacturaciond',14,'Tipo de facturaci&oacute;n');

    if(document.getElementById('checklunesd').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',15,'Orden de visita Lunes',2);
    }else{
        contarok +=1;
        arrg_vali_result[15] = '';
    }

    if(document.getElementById('checkmartesd').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',16,'Orden de visita Martes',2);
    }else{
        contarok +=1;
        arrg_vali_result[16] = '';
    }

    if(document.getElementById('checkmiercolesd').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',17,'Orden de visita Miércoles',2);
    }else{
        contarok +=1;
        arrg_vali_result[17] = '';
    }


    if(document.getElementById('checkjuevesd').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',18,'Orden de visita Jueves',2);
    }else{
        contarok +=1;
        arrg_vali_result[18] = '';
    }


    if(document.getElementById('checkviernesd').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',19,'Orden de visita Viernes',2);
    }else{
        contarok +=1;
        arrg_vali_result[19] = '';
    }


    if(document.getElementById('checksabadod').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',21,'Orden de visita Sábado',2);
    }else{
        contarok +=1;
        arrg_vali_result[21] = '';
    }


    if(document.getElementById('checkdomingod').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',22,'Orden de visita Domingo',2);
    }else{
        contarok +=1;
        arrg_vali_result[22] = '';
    }



    console.log(contarok);
    return contarok;
}

function ModificarClienteAC(){
    var detalle_validacion = '';
    if(validacion_form_actu() < 19){
        arrg_vali_result.forEach( function(valor, indice, array) {
        if(!empty(valor)){
            detalle_validacion += `<p>${valor}</p>`;
        }else{}
        });
        Swal.fire({
            title: '<strong>Atención!</strong>',
            type: 'info',
            html:detalle_validacion,
            confirmButtonText:'Ok'
        });
        $("#ok-editar").attr("disabled", false);
        return;
    }else{
        var datas_info = '';
        var Is_Cheked = document.getElementById('switch_estado').checked;
        var estadoBoolean = 0;
        if(Is_Cheked){
            estadoBoolean = 1;
        }else{
            estadoBoolean = 0;
        }
        datas_info = $("#form-validacion").serializeArray();
        datas_info.push({name: 'nombreus', value: arrg_Credls['nombre_us']});
        datas_info.push({name: 'codecli', value: codigActualAC});
        datas_info.push({name: 'estadoCli', value: estadoBoolean});
        $.ajax({
            url      : 'ok/clientesac',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas_info,
            timeout  : 30777
        }).done(function(_resp){
            if(_resp.rs == true){
                Swal.fire({
                    type: 'success',
                    title: 'Cliente modificado exitosamente!',
                    showConfirmButton: false,
                    timer: 1200
                }).then((result) => {
                    cancelar_actividad();
                    var numero_pagina = $(".pagination span.currentd").text();
                    paginar_clientesAC(numero_pagina,1);
                });
            }else{
                Swal.fire({
                    type: 'error',
                    title: 'Error inesperado!<br>'+_resp.info,
                    showConfirmButton: false,
                    timer: 1200
                }).then((result) => {
                    cancelar_actividad();
                    var numero_pagina = $(".pagination span.currentd").text();
                    paginar_clientesAC(numero_pagina,1);
                    $('html').animate({scrollTop : scrolly}, 500);
                });
            }
        }).fail(function(status, textStatus, errorThrown) {
            _ajax_error_validacion(status,textStatus,errorThrown);
        });
    }
}

function CargarComplementos(){
    $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
        Promise.all([
            paginar_supervisores(1),
            paginar_clientesAC(1),
            DB_IniciarCPSesionCliAct_Admin()
            ])
        .then(respuestas =>{
            FiltrosDeBusqueda();
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                var errorString = error;
                errorString = errorString.toString();
                Swal.fire({
                    title: 'Aviso Importante!',
                    type: 'error',
                    html:errorString,
                    confirmButtonText:'Ok'
                });
            });
        });
    });
}

function FiltrosDeBusqueda(){

    $.ajax({
        url      : 'filtros/supervisores',
        type     : 'POST',
        dataType : 'JSON',
        data     : {},
        timeout  : 30777
    }).done(function(_resp){
        var filtro_htmlRuta = ``;
        filtro_htmlRuta += `<select class="form-control" id="filtrorutas" name="filtrorutas">
        <option value="">TODAS LAS RUTAS</option>`;
        _resp.ls_filtroRuta.forEach(function(filall,index, arrgfilall){
            if(Recordar_Rutas == filall.Id_Ruta){
                filtro_htmlRuta+=`<option value="${filall.Id_Ruta}" selected>${filall.Nombre_Ruta}</option>`;
            }else{
                filtro_htmlRuta+=`<option value="${filall.Id_Ruta}">${filall.Nombre_Ruta}</option>`;
            }  
        });
        filtro_htmlRuta+=`</select>`;
        $("#S_filtroRuta").empty().html(filtro_htmlRuta);
        // $.fn.select2.defaults.set("theme", "bootstrap");
        // $( "#filtrorutas" ).select2({
        //     theme: "bootstrap"
        // });
    }).fail(function(status, textStatus, errorThrown) {
        _ajax_error_validacion(status,textStatus,errorThrown);
    });
}

function iniciar_mapa(latitud,longitud,info) {
    // $("#map").empty();
    $("#map").attr("style","height: 88%;width: 90%;position: absolute;display: ;margin-left:auto;margin-right:auto;left:0;right:0;")
    // console.log('INICIALIZANDO MAPA');
    map = new L.Map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(map);
    map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.
    // map view before we get the location
    map.setView(new L.LatLng(latitud, longitud),15);
    // map.on('locationfound', ubicacionencontrada);
    // map.on('locationerror', onLocationError);
    L.marker([latitud,longitud]).addTo(map)
    .bindPopup(info)
    .openPopup();
    // map.locate({setView: true, maxZoom: 290});

    L.Control.Watermark = L.Control.extend({
       onAdd: function(map) {
       // var img = L.DomUtil.create('img');
       // img.src = '../../docs/images/logo.png';
       // img.style.width = '200px';
       var container = L.DomUtil.create('input');
       container.type="button";
       container.title="Atras";
       container.value = "Atras";
       // container.class = "btn btn-dark";
       container.id = "btn-salir-mapa";
          return container;
       },

       onRemove: function(map) {
          // Nothing to do here
       }
    });

    L.control.watermark = function(opts) {
       return new L.Control.Watermark(opts);
    }
    L.control.watermark({ position: 'topleft' }).addTo(map);
    $('html').animate({scrollTop : scrolly}, 500, function(){
    });
}

function cleardatatempos(){
    return 0;
}

function cancelar_actividad(){
    $.when( $("#formularios").stop(true,true).hide(20) ).done(function( x ) {
        $.when( $("#div_filtrorutas").stop(true,true).show(20) ).done(function( x ) {
            $('#formularios').empty();
            $.when( $("#content-tabla").stop(true,true).show(20) ).done(function( x ) {
                $.when( $("#content_cliAc").stop(true,true).hide(20) ).done(function( x ) {
                    $.when( $("#content_actualizados").stop(true,true).show(20) ).done(function( x ) {
                        $('html').animate({scrollTop : scrolly}, 500, function(){
                        });
                    });
                });
            });
        });
    });
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
function V_Text_LetraNumero(data,campo,ordencampo,etiqueta,option){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-. ]+$/g
    var div_error = "";
    if(option == 1){
        div_error = "error-mjs-";
    }else{
        div_error = "error-mjsd-";
    }
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#"+div_error+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length<7){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El valor ingresado en <strong>'+etiqueta+'</strong> es muy corto.';
            $("#"+div_error+ordencampo).html('El valor ingresado en <strong>'+etiqueta+'</strong> es muy corto.');
        }else{        
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#"+div_error+ordencampo).html('Por favor verifique el campo <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros,"ñ", "#" , "-" , "."');
                arrg_vali_result[ordencampo] = 'Por favor verifique el campo <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros,"ñ" y "#" , "-" , "."';
            }
        }
    }
    return v;
}

function V_Text_LetraNumero_Direccion(data,campo,ordencampo,etiqueta,option){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-./, ]+$/g
    var div_error = "";
    if(option == 1){
        div_error = "error-mjs-";
    }else{
        div_error = "error-mjsd-";
    }
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es obligatoria.';
        $("#"+div_error+ordencampo).html('La <strong>'+etiqueta+'</strong> es obligatoria.');
    }else{
        if(data_C.length<25){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es muy corta.';
            $("#"+div_error+ordencampo).html('La <strong>'+etiqueta+'</strong> es muy corta.');
        }else{
            if(data_C.length>250){
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> no puede exceder los <strong>250</strong> caracteres';
                $("#"+div_error+ordencampo).html('La <strong>'+etiqueta+'</strong> no puede exceder los <strong>250</strong> caracteres');
            }else{
                if(data_E.test(String(data_C))){
                    v = 1;
                    $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                    arrg_vali_result[ordencampo] = '';
                }else{
                    v = 0;
                    $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                    $("#"+div_error+ordencampo).html('Por favor verifique la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>');
                    arrg_vali_result[ordencampo] = 'Por favor verifique la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>';
                }
            }
        }
    }
    return v;
}

function V_numeconMaskguion(data,campo,ordencampo,etiqueta,valcantir,valcantit,option){
    var  v = 0;
    var div_error = "";
    if(option == 1){
        div_error = "error-mjs-";
    }else{
        div_error = "error-mjsd-";
    }
    var data_C=data.trim();
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#"+div_error+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data.length == valcantit){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El valor del campo <strong>'+etiqueta+'</strong> tiene que tener '+valcantir+' digitos.';
            $("#"+div_error+ordencampo).html('El valor del campo <strong>'+etiqueta+'</strong> tiene que tener '+valcantir+' digitos.');
        }
    }
    return v;
}

function V_Text_ConEspacio(data,campo,ordencampo,etiqueta,option){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/g
    var div_error = "";
    if(option == 1){
        div_error = "error-mjs-";
    }else{
        div_error = "error-mjsd-";
    }
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#"+div_error+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length<6){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            $("#"+div_error+ordencampo).html('El valor ingresado en <strong>'+etiqueta+'</strong> es muy corto.');
            arrg_vali_result[ordencampo] = 'El valor ingresado en <strong>'+etiqueta+'</strong> es muy corto.';
        }else{        
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#"+div_error+ordencampo).html('Por favor verifique el campo <strong>'+etiqueta+'</strong>, se necesita minimo un nombre y un apellido (solo letras).');
                arrg_vali_result[ordencampo] = 'Por favor verifique el campo <strong>'+etiqueta+'</strong>, se necesita minimo un nombre y un apellido (solo letras).';
            }
        }
    }
    return v;
}

function V_Selec(data,campo,ordencampo,etiqueta){
    var v = 0;
    if(empty(data)){
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
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatoria.';
    }
    return v;
}
function V_checksd(ordencampo,etiqueta){
    var  v = 0;
    var checks = document.getElementsByClassName("GR_Checkd");
    var val_checks=false
    for (var i = 0; i < checks.length; i++) {
        if(checks[i].checked==true){
            val_checks=true;
        }
    }
    if(val_checks==true){
        v = 1;
        $("#div_diasVisita .GR_Checkd").removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';        
    }else{
        v = 0;
        $("#div_diasVisita .GR_Checkd").removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatoria.';
    }
    return v;
}
function V_NumeroEntero(data,campo,ordencampo,etiqueta,option){
    var data_C=data.trim();
    var v = 0;
    var div_error = "";
    if(option == 1){
        div_error = "error-mjs-";
    }else{
        div_error = "error-mjsd-";
    }
    if(data_C!=""){
        var data_E=/^[0-9]*$/gm
        if(data_E.test(String(data_C))){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
            $("#"+div_error+ordencampo).html('En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
        }
    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#"+div_error+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}

function inputfilevalidacion(campo,ordencampo,etiqueta,campoimg){
    var  v = 0;
    var fileInput = document.getElementById(campo);
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg)$/i;
    if(!allowedExtensions.exec(filePath)){
        if($("#"+campoimg).val()!=""){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            fileInput.value = '';
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es obligatoria.';
        }
    }else{
        v = 1;
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';
    }
    return v;
}

function inputfilevaliok(campo,ordencampo,etiqueta){
    var  v = 0;
    var fileInput = document.getElementById(campo);
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg)$/i;
    if(!allowedExtensions.exec(filePath)){
        v = 0;
        fileInput.value = '';
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es obligatoria.';
    }else{
        v = 1;
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';
    }
    return v;
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
                arrg_vali_result[ordencampo] = 'En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
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

function V_NumeroEnteroDecimalpo(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        if(data_C==0){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = '<strong>'+etiqueta+'</strong> El Monto de crédito no puede cer cero.';
            $("#error-mjs-"+ordencampo).html('<strong>'+etiqueta+'</strong> El Monto de crédito no puede cer cero.');
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
                arrg_vali_result[ordencampo] = 'En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
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
function V_NumeroEnteroDecimalpoinput(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        var data_E=/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/gm
        if(data_E.test(String(data_C))){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
            $("#error-mjs-"+ordencampo).html('En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
        }
    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';     
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}
function V_NumeroEntero2digitos(data,campo,ordencampo,etiqueta,option){
    var data_C=data.trim();
    var v = 0;var tipomjsod = '';

    (option == 1) ? tipomjsod = 'mjs'  : tipomjsod = 'mjsd';

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
                $("#error-"+tipomjsod+"-"+ordencampo).html('El <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros enteros positivos de maximo 3 digitos.');
            }
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> no puede ser cero.';
            $("#error-"+tipomjsod+"-"+ordencampo).html('El <strong>'+etiqueta+'</strong> no puede ser cero.');
        }


    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-"+tipomjsod+"-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}
/*0000000000000000000000000000000000000000000000000000000000000000000000000*/
/*------------------CONFIGURAR IMAGEN CANVAS PARA ENVIAR-------------------*/
/*0000000000000000000000000000000000000000000000000000000000000000000000000*/
function handleImagesu(e) {
    var reader = new FileReader();
    reader.onload = function(event) {
        onReaderLoadsu(event);
    }
    reader.readAsDataURL(e.target.files[0]);
}
var onReaderLoadsu = function(event) {
    var image = new Image();
    image.onload = function() {
        onImageLoadsu(image);
    }
    image.src = event.target.result;
}
var onImageLoadsu = function(img) {
    var MAX_WIDTH = 900;
    var MAX_HEIGHT = 700;
    var width = img.width;
    var height = img.height;
    if (width > height) {
        if (width > MAX_WIDTH) {
            height *= MAX_WIDTH / width;
            width = MAX_WIDTH;
        }
    } else {
        if (height > MAX_HEIGHT) {
            width *= MAX_HEIGHT / height;
            height = MAX_HEIGHT;
        }
    }
    canvassu.width = width;
    canvassu.height = height;
    var ctx = canvassu.getContext("2d");
    ctx.drawImage(img, 0, 0, width, height);
    var dataUrl = '';
    // var dataUrl = canvas.toDataURL('image/jpeg', 1); //CALIDAD ALTA
    dataUrl = canvassu.toDataURL('image/jpeg', 0.8); //CALIDAD MEDIA
    //var dataUrl = canvas.toDataURL('image/jpeg', 0.1); //CALIDAD BAJA
    $("#imagecli").val(dataUrl);
    console.log('IMAGEN '+canvassu);
}

function handleImagefa(e) {
    var reader = new FileReader();
    reader.onload = function(event) {
        onReaderLoadfa(event);
    }
    reader.readAsDataURL(e.target.files[0]);
}
var onReaderLoadfa = function(event) {
    var image = new Image();
    image.onload = function() {
        onImageLoadfa(image);
    }
    image.src = event.target.result;
}
var onImageLoadfa = function(img) {
    var MAX_WIDTH = 800;
    var MAX_HEIGHT = 600;
    var width = img.width;
    var height = img.height;
    if (width > height) {
        if (width > MAX_WIDTH) {
            height *= MAX_WIDTH / width;
            width = MAX_WIDTH;
        }
    } else {
        if (height > MAX_HEIGHT) {
            width *= MAX_HEIGHT / height;
            height = MAX_HEIGHT;
        }
    }
    canvasex.width = width;
    canvasex.height = height;
    var ctx = canvasex.getContext("2d");
    ctx.drawImage(img, 0, 0, width, height);
    var dataUrl = '';
    // var dataUrl = canvas.toDataURL('image/jpeg', 1); //CALIDAD ALTA
    dataUrl = canvasex.toDataURL('image/jpeg', 0.5); //CALIDAD MEDIA
    //var dataUrl = canvas.toDataURL('image/jpeg', 0.1); //CALIDAD BAJA
    $("#imagendos").val(dataUrl);
}
/*000000000000000000000000000000000000000000000000000000000000000000*/
/*----------------LISTADO DE CLIENTES POR SUPERVISOR----------------*/
/*000000000000000000000000000000000000000000000000000000000000000000*/

function paginar_clientesAC(numero_pagina,rutas){

    return new Promise(function(resolve, reject){
    var datas = '';
    var rutasid = '';
    datas = $("#form-reporte").serializeArray();
    if(empty(numero_pagina)){
        numero_pagina = 1;
    }else{}
    datas.push({name: 'page', value: numero_pagina});
    datas.push({name: 'cbrutas_s', value: Recordar_Rutas});
    $.ajax({
        url:'ls_clientes_actualizados/mostrar',
        type:"POST",
        data:datas,
        dataType: "JSON",
        timeout  : 27777
        }).done(function(respuesta) {
            // alert(respuesta.total);
            var detalles_html=``;
            if(respuesta.rs == false){
                var divhtml = mensaje_alerta({cla:respuesta.cla,info:respuesta.errores},detalles_html);
                reject(0);
            }else{
                var html_tabla = ``;
                var contts = 0;
                var p_dias = '';
                var estados = '';
                var badge_dias = ``;
                var cadena_dias_true = 'L_1,M_1,I_1,J_1,V_1,S_1,D_1';
                cadena_dias_true = cadena_dias_true.split(',');
                if(respuesta.total > 0){
                    html_tabla+=`
                    <span class="titulos"><span class="fa fa-marker fa-lg" style="color:#269FE4;"></span> Clientes Actualizados</span>
                    <br><span class="titulos_sub">Total de clientes <span class="badge badge-info" id="totalcli_Ac">${respuesta.total}</span>
                    <div class="table-responsive">
                        <table id="tabla_clientesAC">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle;" scope="col">
                                        <div id="anclar-capitan77777777"></div>
                                    </th>
                                    <th style="vertical-align:middle;" scope="col">RUTA</th>
                                    <th style="vertical-align:middle;" scope="col">NOMBRE</th>
                                    <th style="vertical-align:middle;" scope="col" width="27%">DIRECCION</th>
                                    <th style="vertical-align:middle;" scope="col">TELEFONO</th>
                                    <th style="vertical-align:middle;" scope="col">CONTACTO</th>
                                    <th style="vertical-align:middle;" scope="col">DIA Y ORDEN DE VISITA</th>
                                    <th style="vertical-align:middle;" scope="col">FRECUENCIA DE VISITA</th>
                                    <!--<th style="vertical-align:middle;" scope="col">DEPARTAMENTO</th>-->
                                    <!--<th style="vertical-align:middle;" scope="col">MUNICIPIO</th>-->
                                    <th style="vertical-align:middle;" scope="col" id="td-ubicacion-p">UBICACION</th>
                                    <th style="vertical-align:middle;" id="td-opciones">OPCIONES</th>
                                    <th style="vertical-align:middle;" id="td-resolucion" width="20%">RESOLUCION</th>
                                </tr>
                            </thead>`;
                            paginasin = respuesta.paginacionsin;
                                    $.each(respuesta.ltclientesAC, function(i, val){
                                        indexes_clientes[i] = val.id_cliente;
                                        contts++;
                                        var estadoCliente = '';
                                        if(val.Estado == 1){
                                            estadoCliente = '<span class="fa fa-arrow-circle-up fa-3x" style="color:#28A745;"></span>';
                                        }else{
                                            estadoCliente = '<span class="fa fa-arrow-circle-down fa-3x" style="color:#DC3545;"></span>';
                                        }
                                        mis_codigosK[i] =  val.IdcliRk;
                                        // if (contts<totals){
                                        // mis_codigos[i] = val.codcli;
                                        // mis_rutas[i] = val.codruta;

                                        p_dias = val.dias.split(',');
                                        var OrdenVDia = '';
                                        OrdenVDia = val.Ord_VisitaSema;

                                        if(OrdenVDia === null){
                                            OrdenVDia = 0;
                                        }else{
                                            OrdenVDia = OrdenVDia.split(',');
                                        }
                                        
                                        if(Object.entries(OrdenVDia).length < 7){
                                            OrdenVDia = [0,0,0,0,0,0,0];
                                        }
                                        
                                        badge_dias = '';
                                        badge_dias +=`<table class="tb_dovisita" style="border:hidden;">`;
                                        for(d=0;d<=6;d++){
                                           
                                            if(p_dias[d] == cadena_dias_true[d]){
                                                 badge_dias +=`<tr>`;
                                                if(p_dias[d] == 'L_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">LUNES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[0]}
                                                    </td>`;
                                                }else if(p_dias[d] == 'M_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">MARTEES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[1]}
                                                    </td>`;
                                                }else if(p_dias[d] == 'I_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">MIERCOLES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[2]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'J_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">JUEVES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[3]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'V_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">VIERNES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[4]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'S_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">SABADO</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[5]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'D_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">DOMINGO</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[6]}
                                                    </td>`;
                                                }else{
                                                   badge_dias = 'NA';
                                                }
                                            badge_dias +=`</tr>`;
                                            }else{
                                                // badge_dias = 'NA';
                                            }
                                            
                                        }
                                        badge_dias +=`</table>`;
                                        var departamento = ``;
                                        var municipio = ``;
                                        var iddepartamento = ``;
                                        var idmunicipio = ``;
                                        var titulo_dep_mun = ``;


                                        /*-------------------------------------------*/
                                        /*------EVALUAR DEPARTAMENTO Y MUNICIPIO-----*/
                                        /*-------------------------------------------*/
                                        departamento = val.departamento;
                                        municipio = val.municipio;
                                        iddepartamento = val.iddepartamento;
                                        idmunicipio = val.idmunicipio;
                                        titulo_dep_mun = `<span class="badge badge-secondary">Departamento:</span> ${departamento},<br> <span class="badge badge-secondary">Municipio:</span> ${municipio}<br>`;
                                        
                                        html_tabla+=`
                                        <tbody>

                                        <tr id="fila_p${i}">
                                            <td style="vertical-align:middle;">
                                                <button type="button" class="btn btn_carpeta abrirmodal" id="despliege_${i}"><span class='fa fa-folder-plus fa-3x'></span></button>
                                            </td>
                                            <td style="vertical-align:middle;text-align:center;" id="tk-ruta${i}"><span class="badge badge-secondary">${val.ruta}</span>
                                                <span class="badge badge-secondary">${val.codcli}</span>
                                                <input type="hidden" value="${val.ruta}" id="rktanom${i}">
                                                ${estadoCliente}
                                            </td>
                                            <td style="vertical-align:middle;" id="tk-n${i}">${val.nombrecliente}</td>
                                            <td style="vertical-align:middle;" id="tk-d${i}">
                                                ${titulo_dep_mun}
                                                <span class="badge badge-secondary" id="tk-dd${i}">Direccion: </span>${val.direccion}
                                                <input type="hidden" id="dkpak${i}" value="${iddepartamento}">
                                                <input type="hidden" id="mknik${i}" value="${idmunicipio}">
                                            </td>
                                            <td style="vertical-align:middle;" id="tk-t${i}">${val.telefono}</td>
                                            <td style="vertical-align:middle;" id="tk-c${i}">${val.contacto}</td>
                                            <td style="vertical-align:middle;"><center>${badge_dias}</center>
                                                <input type="hidden" id="tk-dias${i}" value="${val.dias}">
                                            </td>
            
                                            <td style="vertical-align:middle;" id="tk-f${i}">${val.frecuencia}</td>
                                            <td style="vertical-align:middle;" id="tk-ubicacion${i}">
                                                <button type="button" class="btn btn-secondary btn-form ubicacion" id="ubicaciok-${i}" onclick="mostrar_mapak(this.id,0,'k')"><span class="fa fa-map-marked-alt"></span> MAPA</button>
                                                <input type="hidden" id="tk-long${i}" value="${val.long}">
                                                <input type="hidden" id="tk-lati${i}" value="${val.lati}">
                                                <input type="hidden" id="tk-dire${i}" value="${val.direccion}">
                                            </td>
                                            <td style="vertical-align:middle;"><button type="button" class="btn btn-success btn-form editak" id="editak-${i}"><span class="fa fa-pencil-alt"></span> MODIFICAR</button></td>
                                            <td style="vertical-align:middle;" id="tk-resolucion-${i}">
                                                  <select class="form-control custom-select" id="tipo_resolk${i}" onchange="confirmar_resoluciok(this.value,'${val.IdcliRk}',0,${i},0)">
                                                      <option value="">Seleccione una opcion</option>
                                                      <option value="1">APROBADO</option>
                                                      <option value="2">RECHAZADO</option>
                                                  </select>
                                            </td>
                                        </tr>`;
                                        // }else{}
                                        badge_dias = ``;
                                    });
                                    html_tabla+=`
                                        </tbody>
                                    </table></div><div class="paginacion">${paginasin}</div>`;
                }else{
                    html_tabla+=`
                    <span class="titulos"><span class="fa fa-marker fa-lg" style="color:#269FE4;"></span> Clientes Actualizados</span>
                    <div class="alert alert-dark estilo_alert_nohay" role="alert">
                        <h4><br>
                            NO SE ENCONTRARON REGISTROS DE ACTUALIZACIÓN DE CLIENTES<br>
                            <span class="fa fa-clipboard-check fa-3x" style="margin-top:2px;"></span><br>
                        </h4>
                    </div>`;
                }
                $('#content_actualizados').empty().html(html_tabla);
                resolve(1);
            }

        }).fail(function() {

            Swal.fire({
                type: 'error',
                title: 'Error en cargar datos de clientes actualizados...',
                showConfirmButton: false,
                timer: 1500
            });
            reject(0);
        });
    });
}

function paginar_supervisores(numero_pagina,rutas){

    return new Promise(function(resolve, reject){
        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            var datas = '';
            var rutasid = '';
            datas = $("#form-reporte").serializeArray();
            if(empty(numero_pagina)){
                numero_pagina = 1;
            }else{}

            datas.push({name: 'page', value: numero_pagina});
            datas.push({name: 'cbrutas_s', value: Recordar_Rutas});
            $.ajax({
                url:'lista-clientes-s/verificacion_tab_clientes',
                type:"POST",
                data:datas,
                dataType: "JSON",
                timeout  : 7777
                }).done(function(respuesta) {
                    // alert(respuesta.total);
                    var detalles_html=``;
                    if(respuesta.rs == false){
                        $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                            $.when( $(".carga-esconder").stop(true,true).show(20) ).done(function( x ) {
                                var divhtml = mensaje_alerta({cla:respuesta.cla,info:respuesta.errores},detalles_html);
                                $("#mjs_result").empty();
                                $("#mjs_result").html(divhtml);
                                $('body,html').animate({scrollTop : 0}, 500);
                                reject(0);
                            });
                        });
                    }else{

                        $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {


                                var atributos_dropdown = {
                                    class_input:'form-control custom-select'
                                };
                                var paginasin = '';
                                var htmlinsertarsin = ``;
                                htmlinsertarsin =`
                                `;
                                if(respuesta.total > 0){
                                    paginasin = respuesta.paginacionsin;
                                    var htmlinsertarcon = ``;
                                    var contts = 0;
                                    var p_dias = '';
                                    var estados = '';
                                    var badge_dias = ``;
                                    var cadena_dias_true = 'L_1,M_1,I_1,J_1,V_1,S_1,D_1';
                                    cadena_dias_true = cadena_dias_true.split(',');
                                    htmlinsertarsin+=`
                                    <span class="titulos"><span class="fa fa-plus-square fa-lg" style="color:#28A745;"></span> Clientes Nuevos</span><br>
                                    <span class="titulos_sub">Total de clientes <span class="badge badge-info" id="totalcli">0</span>
                                    <div class="table-responsive">
                                    <table id="tabla-clientes">
                                        <thead>
                                        <tr>
                                            <th style="vertical-align:middle;" scope="col">
                                                <div id="anclar-capitan7777777"></div>
                                            </th>
                                            <th style="vertical-align:middle;" scope="col">RUTA</th>
                                            <th style="vertical-align:middle;" scope="col">NOMBRE</th>
                                            <th style="vertical-align:middle;" scope="col" width="27%">DIRECCION</th>
                                            <th style="vertical-align:middle;" scope="col">TELEFONO</th>
                                            <th style="vertical-align:middle;" scope="col">CONTACTO</th>
                                            <th style="vertical-align:middle;" scope="col">DIA Y ORDEN DE VISITA</th>
                                            <th style="vertical-align:middle;" scope="col">FRECUENCIA DE VISITA</th>
                                            <!--<th style="vertical-align:middle;" scope="col">DEPARTAMENTO</th>-->
                                            <!--<th style="vertical-align:middle;" scope="col">MUNICIPIO</th>-->
                                            <th style="vertical-align:middle;" scope="col" id="td-ubicacion-p">UBICACION</th>
                                            <th style="vertical-align:middle;" id="td-opciones">OPCIONES</th>
                                            <th style="vertical-align:middle;" id="td-resolucion" width="20%">RESOLUCION</th>
                                        </tr>
                                        </thead>`;
                                    $.each(respuesta.ltclientes, function(i, val){
                                        contts++;
                                        // if (contts<totals){
                                        mis_codigos[i] = val.codcli;
                                        mis_rutas[i] = val.codruta;
                                        arrg_plus_minus[i] = 0;
                                        arrg_plus_minus_sub[i] = {
                                            0:0,
                                            1:0,
                                            2:0
                                        }
                                        p_dias = val.dias.split(',');

                                        var OrdenVDia = '';
                                        OrdenVDia = val.Ord_VisitaSema;

                                        if(OrdenVDia === null){
                                            OrdenVDia = 0;
                                        }else{
                                            OrdenVDia = OrdenVDia.split(',');
                                        }
                                        
                                        if(Object.entries(OrdenVDia).length < 7){
                                            OrdenVDia = [0,0,0,0,0,0,0];
                                        }
                                        
                                        badge_dias = '';
                                        badge_dias +=`<table class="tb_dovisita" style="border:hidden;">`;
                                        for(d=0;d<=6;d++){
                                           
                                            if(p_dias[d] == cadena_dias_true[d]){
                                                 badge_dias +=`<tr>`;
                                                if(p_dias[d] == 'L_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">LUNES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[0]}
                                                    </td>`;
                                                }else if(p_dias[d] == 'M_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">MARTEES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[1]}
                                                    </td>`;
                                                }else if(p_dias[d] == 'I_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">MIERCOLES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[2]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'J_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">JUEVES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[3]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'V_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">VIERNES</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[4]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'S_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">SABADO</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[5]}
                                                    </td>`;
                                                }else if (p_dias[d] == 'D_1'){
                                                    badge_dias += `
                                                    <td>
                                                        <span class="badge badge-info">DOMINGO</span> 
                                                    </td>
                                                    <td>
                                                        ${OrdenVDia[6]}
                                                    </td>`;
                                                }else{
                                                   badge_dias = 'NA';
                                                }
                                            badge_dias +=`</tr>`;
                                            }else{
                                                // badge_dias = 'NA';
                                            }
                                            
                                        }
                                        badge_dias +=`</table>`;

                                        var departamento = ``;
                                        var municipio = ``;
                                        var titulo_dep_mun = ``;

                                        /*-------------------------------------------*/
                                        /*------EVALUAR DEPARTAMENTO Y MUNICIPIO-----*/
                                        /*-------------------------------------------*/

                                        departamento = val.departamento;
                                        municipio = val.municipio;
                                        titulo_dep_mun = `<span class="badge badge-secondary">Departamento:</span> ${departamento},<br> <span class="badge badge-secondary">Municipio:</span> ${municipio}<br>`;
                                        
                                        var tooltipeditar = ``;
                                        if(val.Editado == "S"){
                                            tooltipeditar = `<span data-toggle="tooltip" data-placement="top" title="${val.comentarioe}" class="fa fa-comment-dots" style="color:#FFFFFF;font-size:37px;border-radius:50%;text-shadow: 2px 0px 14px rgba(138, 150, 150, 1);"></span>`;
                                        }
                                        htmlinsertarsin+=`
                                        <tbody id="accordionPrincipal">

                                        <tr id="contenedor${i}" class="tr_principalTBNuevos">

                                        
                                            <td style="vertical-align:middle;">
                                                <div id="anclar-capitan${i}"></div>
                                                <button type="button" class="btn btn-primary clickable" id="btn-duplicado${i}">
                                                    <span id="span-duplicado${i}" class="fa fa-plus-square span-dupli"></span>
                                                </button>
                                                <button type="button" style="display:none;" class="btn btn-primary" id="btn-mduplicado${i}" data-toggle="collapse" data-target="#accordion${i}"></button>
                                            </td>
                                            <td style="vertical-align:middle;" id="td-ruta${i}">
                                                ${tooltipeditar}
                                                <span class="badge badge-success">${val.ruta}</span>
                                                <input type="hidden" value="${val.ruta}" id="rutanom${i}">
                                            </td>
                                            <td style="vertical-align:middle;" id="td-n${i}">${val.nombrecliente}</td>
                                            <td style="vertical-align:middle;" id="td-d${i}">
                                                ${titulo_dep_mun}
                                                <span class="badge badge-secondary">Direccion: </span>${val.direccion}
                                                <input type="hidden" id="depak${i}" value="${departamento}">
                                                <input type="hidden" id="munik${i}" value="${municipio}">
                                            </td>
                                            <td style="vertical-align:middle;" id="td-t${i}">${val.telefono}</td>
                                            <td style="vertical-align:middle;" id="td-c${i}">${val.contacto}</td>
                                            <td style="vertical-align:middle;"><center>${badge_dias}</center>
                                                <input type="hidden" id="td-dias${i}" value="${val.dias}">
                                            </td>
                                            <td style="vertical-align:middle;" id="td-f${i}">${val.frecuencia}</td>
                                            <!--<td style="vertical-align:middle;" id="td-dep${i}">${val.departamento}</td>-->
                                            <!--<td style="vertical-align:middle;" id="td-mun${i}">${val.municipio}</td>-->
                                            <td style="vertical-align:middle;" id="td-ubicacion${i}">
                                                <button type="button" class="btn btn-secondary btn-form ubicacion" id="ubicacioo-${i}" onclick="mostrar_mapa(this.id,0,'k')"><span class="fa fa-map-marked-alt"></span> MAPA</button>
                                                <input type="hidden" id="td-long${i}" value="${val.long}">
                                                <input type="hidden" id="td-lati${i}" value="${val.lati}">
                                                <input type="hidden" id="td-dire${i}" value="${val.direccion}">
                                            </td>
                                            <td style="vertical-align:middle;"><button type="button" class="btn btn-success btn-form editar" id="editar-${i}" onclick="ctr_form_editar(this.id,0,0,${i})"><span class="fa fa-pencil-alt"></span> MODIFICAR</button></td>
                                            <td style="vertical-align:middle;" id="td-resolucion-${i}">
                                                  <select class="form-control custom-select resolucion" id="cbresolucion-${i}"" onchange="confirmar_resolucion(this.value,'${val.codcli}',0,this.id,0)">
                                                      <option value="">Seleccione una opcion</option>
                                                      <option value="1">APROBADO</option>
                                                      <option value="2">RECHAZADO</option>
                                                  </select>
                                            </td>
                                        </tr>
                                        <tr id="tr-acordeon${i}">
                                            <td colspan="13" style="padding: 0px;border-bottom: 0px solid #000;">
                                                <div id="accordion${i}" class="collapse" style="padding: 8px;">
                                                    <div class="accordion" id="accordionExample${i}" style="width: 100%;margin:0 auto;">
                                                      <div class="card" id="estado-cardC${i}" style="">
                                                        <div class="card-header head-acordeon" id="headingOne${i}">
                                                          <h2 class="mb-0">
                                                            <button type="button" class="btn btn-primary sub-aco" id="0btn-duplicadosC${i}" data-toggle="collapse" data-target="#collapseOne${i}">
                                                                <span id="0span-duplicados${i}" class="fa fa-plus-square subaco-span"></span>
                                                            </button>
                                                            <!--<button class="btn btn-link" type="button" aria-expanded="true" aria-controls="collapseOne${i}">
                                                              Coincidencia por contacto
                                                            </button>-->
                                                            <label style="font-size:16px;">Coincidencia por contacto</label>
                                                            <span class="badge badge-dark badgeaco" id="totaldC${i}"></span>
                                                          </h2>
                                                        </div>

                                                        <div id="collapseOne${i}" class="collapse" aria-labelledby="headingOne${i}" data-parent="#accordionExample${i}">
                                                          <div class="card-body" id="aco-contacto${i}" style="border:1px solid #485F7E;">
                                                            
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="card" id="estado-cardT${i}">
                                                        <div class="card-header head-acordeon" id="headingTwo${i}">
                                                          <h2 class="mb-0">
                                                            <button type="button" class="btn btn-primary sub-aco" id="1btn-duplicadosC${i}" data-toggle="collapse" data-target="#collapseTwo${i}">
                                                                <span id="1span-duplicados${i}" class="fa fa-plus-square subaco-span"></span>
                                                            </button>
                                                            <!--<button class="btn btn-link collapsed" type="button" aria-expanded="false" aria-controls="collapseTwo${i}">
                                                              Coincidencia por telefono
                                                            </button>-->
                                                            <label style="font-size:16px;">Coincidencia por telefono</label>
                                                            <span class="badge badge-dark badgeaco" id="totaldT${i}"></span>
                                                          </h2>
                                                        </div>
                                                        <div id="collapseTwo${i}" class="collapse" aria-labelledby="headingTwo${i}" data-parent="#accordionExample${i}">
                                                          <div class="card-body" id="aco-telefono${i}">
                                                            
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="card" id="estado-cardN${i}">
                                                        <div class="card-header head-acordeon" id="headingThree${i}">
                                                          <h2 class="mb-0">
                                                            <button type="button" class="btn btn-primary sub-aco" id="2btn-duplicadosC${i}" data-toggle="collapse" data-target="#collapseThree${i}">
                                                                <span id="2span-duplicados${i}" class="fa fa-plus-square subaco-span"></span>
                                                            </button>
                                                            <!--<button class="btn btn-link collapsed" type="button" aria-expanded="false" aria-controls="collapseThree${i}">
                                                              Coincidencia por nombre
                                                            </button>-->
                                                            <label style="font-size:16px;">Coincidencia por nombre</label>
                                                            <span class="badge badge-dark badgeaco" id="totaldN${i}"></span>
                                                          </h2>
                                                        </div>
                                                        <div id="collapseThree${i}" class="collapse" aria-labelledby="headingThree${i}" data-parent="#accordionExample${i}">
                                                          <div class="card-body" id="aco-nombre${i}">

                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>`;
                                        // }else{}
                                        badge_dias = ``;
                                    });
                                     htmlinsertarsin+=`
                                        </tbody>
                                    </table></div><div class="paginacion">${paginasin}</div>`;

                                }else{
                                    htmlinsertarsin+=`
                                    <span class="titulos"><span class="fa fa-plus-square fa-lg" style="color:#28A745;"></span> Clientes Nuevos</span><br>
                                    <div class="alert alert-dark estilo_alert_nohay" role="alert">
                                        <h4><br>
                                            NO SE ENCONTRARON REGISTROS DE CLIENTES NUEVOS<br>
                                            <span class="fa fa-clipboard-check fa-3x" style="margin-top:2px;"></span><br>
                                        </h4>
                                    </div>`;
                                }
                                $('#content-tabla').empty().html(htmlinsertarsin);
                                $("#totalcli").html(respuesta.total);
                                resolve(1);
                            });    
                    }///TRUE RESULTADO LISTA CLIENTES
                }).fail(function() {
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {

                            Swal.fire({
                                type: 'error',
                                title: 'Error en carga de clientes nuevos...',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            reject(0);
                    }); 
                    // retorna_inicio();
                });
        });
    })

}

function convertImageToCanvas(imgfoto) {
    // var canvas = document.createElement("contenedor-img");
    // canvas.width = image.width;
    // canvas.height = image.height;
    // canvas.getContext("2d").drawImage(image, 0, 0);

  var c = document.getElementById("contenedor-img");
  var ctx = c.getContext("2d");
  var img = imgfoto;
  ctx.drawImage(img, 10, 10);

}

/*0000000000000000000000000000000000000000000000000000000000000000000000000*/
/*-------------FUNCION CREAR FORMULARIO HTML PARA INSCRUSTAR---------------*/
/*0000000000000000000000000000000000000000000000000000000000000000000000000*/
function crea_form_cli(arrg_input_val,tipo,codigotr){
    
    var input_nombre = {
        type_input:'text',
        name_input:'nomestablecimiento',
        id_input:'nomestablecimiento',
        class_input:'form-control',
        placeholder_input:'Nombre del establecimiento...',
        value_input:arrg_input_val.nombre
    };

    // var input_direccion = {
    //     type_input:'textarea',
    //     name_input:'direccion',
    //     id_input:'direccion',
    //     class_input:'form-control',
    //     placeholder_input:'Direccion...',
    //     value_input:arrg_input_val.direccion
    // };

    var input_telefono = {
        type_input:'tel',
        name_input:'telefono',
        id_input:'telefono',
        class_input:'form-control',
        placeholder_input:'Telefono...',
        value_input:arrg_input_val.telefono
    };

    var input_contacto = {
        type_input:'text',
        name_input:'contacto',
        id_input:'contacto',
        class_input:'form-control',
        placeholder_input:'Contacto...',
        value_input:arrg_input_val.contacto
    };
    var input_ordenvisita = {
        type_input:'tel',
        name_input:'ordenvisita',
        id_input:'ordenvisita',
        class_input:'form-control',
        placeholder_input:'Orde de visita...',
        value_input:arrg_input_val.ordenvisita
    };

    // alert(arrg_input_val.dias);
    var p_dias = arrg_input_val.dias.split(',');


    var style_l='',style_m='',style_i='',style_j='',style_v='',style_s='',style_d='';
    if(p_dias[0] === 'L_1'){
        arrg_checqueados['lcheck'] = `checked="checked"`;
        style_l = 'margin-top:7px;display:;';
    }else{
        arrg_checqueados['lcheck'] = '';
        style_l = 'margin-top:7px;display:none;';
    }
    if(p_dias[1] === 'M_1'){
        arrg_checqueados['mcheck'] = `checked="checked"`;
        style_m = 'margin-top:7px;display:;';
    }else{
        arrg_checqueados['mcheck'] = '';
        style_m = 'margin-top:7px;display:none;';
    }
    if(p_dias[2] === 'I_1'){
        arrg_checqueados['icheck'] = `checked="checked"`;
        style_i = 'margin-top:7px;display:;';
    }else{
        arrg_checqueados['icheck'] = '';
        style_i = 'margin-top:7px;display:none;';
    }
    if(p_dias[3] === 'J_1'){
        arrg_checqueados['jcheck'] = `checked="checked"`;
        style_j = 'margin-top:7px;display:;';
    }else{
        arrg_checqueados['jcheck'] = '';
        style_j = 'margin-top:7px;display:none;';
    }
    if(p_dias[4] === 'V_1'){
        arrg_checqueados['vcheck'] = `checked="checked"`;
        style_v = 'margin-top:7px;display:;';
    }else{
        arrg_checqueados['vcheck'] = '';
        style_v = 'margin-top:7px;display:none;';
    }
    if(p_dias[5] === 'S_1'){
        arrg_checqueados['scheck'] = `checked="checked"`;
        style_s = 'margin-top:7px;display:;';
    }else{
        arrg_checqueados['scheck'] = '';
        style_s = 'margin-top:7px;display:none;';
    }
    if(p_dias[6] === 'D_1'){
        arrg_checqueados['dcheck'] = `checked="checked"`;
        style_d = 'margin-top:7px;display:;';
    }else{
        arrg_checqueados['dcheck'] = '';
        style_d = 'margin-top:7px;display:none;';
    }
    var l=0,m=0,i=0,j=0,v=0,s=0,d=0;
    var OrdenVDia = '';
    OrdenVDia = arrg_input_val.Ord_VisitaSema;

    if(OrdenVDia === null){
        OrdenVDia = 0;
    }else{
        OrdenVDia = OrdenVDia.split(',');
    }

    if(Object.entries(OrdenVDia).length < 7){
        OrdenVDia = [0,0,0,0,0,0,0];
    }

    ( empty(OrdenVDia[0] )) ?  l ='' : l = OrdenVDia[0];
    ( empty(OrdenVDia[1] )) ?  m ='' : m = OrdenVDia[1];
    ( empty(OrdenVDia[2] )) ?  i ='' : i = OrdenVDia[2];
    ( empty(OrdenVDia[3] )) ?  j ='' : j = OrdenVDia[3];
    ( empty(OrdenVDia[4] )) ?  v ='' : v = OrdenVDia[4];
    ( empty(OrdenVDia[5] )) ?  s ='' : s = OrdenVDia[5];
    ( empty(OrdenVDia[6] )) ?  d ='' : d = OrdenVDia[6];

    var checkbox_dias = ``;
    arrg_checqueados = [];
    if(p_dias[0] === 'L_1'){
        arrg_checqueados['lcheck'] = `checked="checked"`;
    }else{
        arrg_checqueados['lcheck'] = '';
    }
    if(p_dias[1] === 'M_1'){
        arrg_checqueados['mcheck'] = `checked="checked"`;
    }else{
        arrg_checqueados['mcheck'] = '';
    }
    if(p_dias[2] === 'I_1'){
        arrg_checqueados['icheck'] = `checked="checked"`;
    }else{
        arrg_checqueados['icheck'] = '';
    }
    if(p_dias[3] === 'J_1'){
        arrg_checqueados['jcheck'] = `checked="checked"`;
    }else{
        arrg_checqueados['jcheck'] = '';
    }
    if(p_dias[4] === 'V_1'){
        arrg_checqueados['vcheck'] = `checked="checked"`;
    }else{
        arrg_checqueados['vcheck'] = '';
    }
    if(p_dias[5] === 'S_1'){
        arrg_checqueados['scheck'] = `checked="checked"`;
    }else{
        arrg_checqueados['scheck'] = '';
    }
    if(p_dias[6] === 'D_1'){
        arrg_checqueados['dcheck'] = `checked="checked"`;
    }else{
        arrg_checqueados['dcheck'] = '';
    }
    checkbox_dias = `
    <div class="form-group">
        <label for="Diasvisita">D&iacute;a De Visita</label>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input GR_Check" id="checklunes" name="checkdiavisita[]" value='L_1' ${arrg_checqueados['lcheck']}>
            <label class="custom-control-label" for="checklunes">LUNES</label>
        </div>


        <div style="${style_l}" id="ord_ln">
            <label>Orden De Visita Lunes:</label>
            <input type="number" name="txtordenvisitaln" id="txtordenvisitaln" class="form-control" placeholder="Orden de visita..." value="${l}" min="0" max="90" step="1">
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback" id="error-mjs-27">
            </div>
            <!-- <hr style="wid:100%;"> -->
            <hr class="separador">
        </div>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input GR_Check id="checkmartes" name="checkdiavisita[]" value='M_1' ${arrg_checqueados['mcheck']}>
            <label class="custom-control-label" for="checkmartes">MARTES</label>
        </div>

        <div style="${style_m}" id="ord_mn">
            <label>Orden De Visita Martes:</label>
            <input type="number" name="txtordenvisitamn" id="txtordenvisitamn" class="form-control" placeholder="Orden de visita..." value="${m}" min="0" max="90" step="1">
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback" id="error-mjs-28">
            </div>
            <hr class="separador">
        </div>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input GR_Check" id="checkmiercoles" name="checkdiavisita[]" value='I_1' ${arrg_checqueados['icheck']}>
            <label class="custom-control-label" for="checkmiercoles">MI&Eacute;RCOLES</label>
        </div>

        <div style="${style_i}" id="ord_in">
            <label>Orden De Visita Miércoles:</label>
            <input type="number" name="txtordenvisitain" id="txtordenvisitain" class="form-control" placeholder="Orden de visita..." value="${i}" min="0" max="90" step="1">
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback" id="error-mjs-29">
            </div>
            <hr class="separador">
        </div>


        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input GR_Check" id="checkjueves" name="checkdiavisita[]" value='J_1' ${arrg_checqueados['jcheck']}>
            <label class="custom-control-label" for="checkjueves">JUEVES</label>
        </div>

        <div style="${style_j}" id="ord_jn">
            <label>Orden De Visita Jueves:</label>
            <input type="number" name="txtordenvisitajn" id="txtordenvisitajn" class="form-control" placeholder="Orden de visita..." value="${j}" min="0" max="90" step="1">
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback" id="error-mjs-30">
            </div>
            <hr class="separador">
        </div>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input GR_Check" id="checkviernes" name="checkdiavisita[]" value='V_1' ${arrg_checqueados['vcheck']}>
            <label class="custom-control-label" for="checkviernes">VIERNES</label>
        </div>


        <div style="${style_v}" id="ord_vn">
            <label>Orden De Visita Viernes:</label>
            <input type="number" name="txtordenvisitavn" id="txtordenvisitavn" class="form-control" placeholder="Orden de visita..." value="${v}" min="0" max="90" step="1">
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback" id="error-mjs-31">
            </div>
            <hr class="separador">
        </div>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input GR_Check" id="checksabado" name="checkdiavisita[]" value='S_1' ${arrg_checqueados['scheck']}>
            <label class="custom-control-label" for="checksabado">SABADO</label>
        </div>

        <div style="${style_s}" id="ord_sn">
            <label>Orden De Visita Sabado:</label>
            <input type="number" name="txtordenvisitasn" id="txtordenvisitasn" class="form-control" placeholder="Orden de visita..." value="${s}" min="0" max="90" step="1">
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback" id="error-mjs-32">
            </div>
            <hr class="separador">
        </div>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input GR_Check" id="checkdomingo" name="checkdiavisita[]" value='D_1' ${arrg_checqueados['dcheck']}>
            <label class="custom-control-label" for="checkdomingo">DOMINGO</label>
        </div>

        <div style="${style_d}" id="ord_dn">
            <label>Orden De Visita Domingo:</label>
            <input type="number" name="txtordenvisitadn" id="txtordenvisitadn" class="form-control" placeholder="Orden de visita..." value="${d}" min="0" max="90" step="1">
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback" id="error-mjs-33">
            </div>
            <hr class="separador">
        </div>

        <input type="checkbox" style="display: none;" class="custom-control-input GR_Check" id="checkvalidate" value=''>
        <div class="valid-feedback">
            <strong></strong>
        </div>
        <div class="invalid-feedback">
            <strong>Por favor selecciona una opción de la lista!</strong>
        </div>
    </div>`;


    var input_btn_cancelar = {
        type_input:'button',
        name_input:'cancelar-agregar',
        class_input:'btn btn-danger',
        onclick_input:'cancelar_actividad()',
        value_input:'Cancelar'
    };


    var atributos_dropdown = {
        class_input:'form-control custom-select'
    };
    var imgfachada = '';
    /*-----------------------------*/
    /*COMPROBAR IMG FACHADA NEGOCIO*/
    /*-----------------------------*/
    if(arrg_input_val.fotofachada == 'NULL'){    
        imgfachada = '../dependencias/imagenes/file_3_icon-icons.com_68952.png'
    }else{
        imgfachada = '../../Uploads/img_server/'+arrg_input_val.fotofachada;
    }
    /*-----------------------------*/
    /*---COMPROBAR IMG EXHIBIDOR---*/
    /*-----------------------------*/
    var imgexhibidor = '';
    /*COMPROBAR IMG FACHADA NEGOCIO*/
    if(arrg_input_val.fotoexhibidor == 'NULL'){    
        imgexhibidor = '../dependencias/imagenes/file_3_icon-icons.com_68952.png'
    }else{
        imgexhibidor = '../../Uploads/img_server/'+arrg_input_val.fotoexhibidor;
    }
    // convertImageToCanvas(imgfachada);
    var comentarioee = '';
    if(arrg_input_val.editados == "S"){
        comentarioee = `
        <div class="form-group div_comentario">
            <span>
                <h5>Registro modificado! </h5>
                ${arrg_input_val.comentarioe}
            </span>
        </div>`;
    }

    var formu_html = ``;
    formu_html = `
    <div class="row" style="margin-top:-47px;">
        <div class="col-md-8 offset-md-2">
            <div class="card card-body">
                <h3 class="card-header" style="text-align:center;background-color:#2D535D;color:#fff;">Modificar cliente</h3>

                ${comentarioee}

                <div class="form-group">
                    <label>Nombre del establecimiento:</label>
                    ${form_input(input_nombre)}
                  <div class="valid-feedback">
                    <strong></strong>
                  </div>
                  <div class="invalid-feedback" id="error-mjs-0">
                  </div>
                </div>
                <div class="form-group">
                    <label>Direcci&oacute;n:</label>
                    <textarea class="form-control" name="direccion" id="direccion" style="width: 100%;height: 90px;" placeholder="Direccion...">${arrg_input_val.direccion}</textarea>
                  <div class="valid-feedback">
                    <strong></strong>
                  </div>
                  <div class="invalid-feedback" id="error-mjs-1">
                  </div>
                </div>

                <div class="form-group">
                  <label>Departamento:</label>
                    ${form_dropdown('cbdepartamento',arrg_input_val.deptlist,arrg_input_val.deptselect,atributos_dropdown)}
                </div>

                <div class="form-group" id="carga-municipio">
                  <label>Municipio:</label>
                    ${form_dropdown('cbmunicipio',arrg_input_val.munilist,arrg_input_val.muniselect,atributos_dropdown)}
                </div>

                <div class="form-group">
                    <label>Tel&eacute;fono:</label>
                    ${form_input(input_telefono)}
                  <div class="valid-feedback">
                    <strong></strong>
                  </div>
                  <div class="invalid-feedback" id="error-mjs-4">
                  </div>
                </div>
                <div class="form-group">
                    <label>Contacto:</label>
                    ${form_input(input_contacto)}
                    <div class="valid-feedback">
                    <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-5">
                    </div>
                </div>

                <div class="form-group">
                    
                  <label>Foto de fachada del negocio:</label>
                  <div class="custom-file">
                    <label for="filefnegocio" class="form-label">Foto de fachada del negocio</label>
                    <input id="filefnegocio" name="filefnegocio" type="file" class="form-control" accept="image/*" capture="camera">
                
                  <div class="valid-feedback">
                    <strong></strong>
                  </div>
                  <div class="invalid-feedback" id="error-mjs-6">
                    <strong>Por favor toma una foto!</strong>
                  </div>                    
                  </div>
                  <br><br>
                  
                  <div id="contenedor-img">
                    <img src="${imgfachada}" style="border: 1px solid black;width:200px;height:200px;"/>
                  </div>
                  <canvas id="canvas-fachada" style="border: 1px solid black;width:200px;height:200px;display:none;">
                  </canvas>

                </div>

                <div class="form-group">
                  <label>Tipo punto de venta:</label>
                    ${form_dropdown('cbtpuntoventa',arrg_input_val.tpvlist,arrg_input_val.tpvselect,atributos_dropdown)}
                </div>

                <div class="form-group" id="carga-gironegocio">
                  <label>Giro de Negocio:</label>
                    ${form_dropdown('cbgironegocio',arrg_input_val.gironegociolist,arrg_input_val.gironegocioselect,atributos_dropdown)}
                </div>

                <div class="form-group">
                  <label>Tipo de facturaci&oacute;n:</label>
                    ${form_dropdown('cbtfacturacion',arrg_input_val.tipofacturacion,arrg_input_val.tipofactuselect,atributos_dropdown)}
                </div>

                <div id="if-tfactura" style="display: none;" class="especial-info">

                    <div class="form-group" style="display: none;" id="div_duiu">
                        <label id="docidentidad">${esDUI}</label>
                        <input type="tel" id="txtdui" maxlength="15" name="txtdui" class="form-control" value="${arrg_input_val.dui}">
                        <div class="valid-feedback">
                            <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-10">
                        </div>
                    </div>

                    <div class="form-group" style="display: none;" id="div_numregistrou">
                        <label>N&uacute;mero de registro de contribuyente:</label>
                        <input type="tel" id="txtnumcontribuyente" name="txtnumcontribuyente" maxlength="10" class="form-control" placeholder="Número de registro de contribuyente..." value="${arrg_input_val.numcontriy}">
                        <div class="valid-feedback">
                            <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-11">
                        </div>
                    </div>

                    <div class="form-group" style="display: none;" id="div_nitu">
                        <label id="idtributaria">${esNIT}</label>
                        <input type="tel" id="txtnit" name="txtnit" maxlength="17" class="form-control" value="${arrg_input_val.nit}">
                        <div class="valid-feedback">
                            <strong></strong>
                        </div>
                        <div class="invalid-feedback" id="error-mjs-12">
                        </div>
                    </div>

                </div>

                <div class="form-group">
                  <label>Condici&oacute;n de cliente:</label>
                    ${form_dropdown('cbcondicioncli',arrg_input_val.condiciocliente,arrg_input_val.condicioncliselct,atributos_dropdown)}
                </div>

                <div id="if-condcliente" style="display: none;" class="especial-info">             
                  <div class="form-group">
                    <label >D&iacute;a de cobro:</label>
                    <select class="form-control js-example-responsive narrow custom-select" id="cbdiascobro" name="cbdiascobro">
                      <option value="">SELECCIONE UNA OPCI&Oacute;N</option>
                      <option value="1">LUNES</option>
                      <option value="2">MARTES</option>
                      <option value="3">MI&Eacute;RCOlES</option>
                      <option value="4">JUEVES</option>
                      <option value="5">VIERNES</option>
                      <option value="6">SABADO</option>
                      <option value="7">DOMINGO</option>
                    </select>
                    <div class="valid-feedback">
                      <strong></strong>
                    </div>
                    <div class="invalid-feedback">
                      <strong>Por favor selecciona una opción de la lista!</strong>
                    </div>                                                    
                  </div>
                  <div class="form-group">
                    <label>Monto de cr&eacute;dito:</label>
                    <input type="tel" name="txtmontocredito" id="txtmontocredito" class="form-control" placeholder="Monto Crédito..." value="${arrg_input_val.montocredito}">
                    <div class="valid-feedback">
                      <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-15">
                    </div>   
                  </div>
                </div>

                <div class="form-group">
                  <label for="Frecuenciavisita">Frecuencia De Visita</label>
                  <select class="form-control custom-select" id="cbfrecuenciavisita" name="cbfrecuenciavisita">
                    <option value="">SELECCIONE UNA OPCION</option>
                    <option value="1,2,3,4,5">SEMANAL</option>
                    <option value="1,3,5">QUINCENAL 1,3,5</option>
                    <option value="2,4">QUINCENAL 2,4</option>
                    <option value="1">MENSUAL S1</option>
                    <option value="2">MENSUAL S2</option>
                    <option value="3">MENSUAL S3</option>
                    <option value="4">MENSUAL S4</option>
                  </select>
                  <div class="valid-feedback">
                    <strong></strong>
                  </div>
                  <div class="invalid-feedback" id="error-mjs-16">
                    <strong>Por favor selecciona una opción de la lista!</strong>
                  </div>   
                </div>

                <div class="form-group">
                    ${checkbox_dias}
                </div>

                  <div class="form-group" id="content-exu">
                    <label>Exhibidor 1:</label>
                    <div id="c-exhibidoru">
                      <!--<select name="cbexhibidoru" id="cbexhibidoru">
                        <option value="" selected>0</option>
                      </select>-->
                      <button type="button" id="AddFiles" class="btn btn-light btnNormal">Seleccione exhibidor 1 &nbsp;
                        <img src="../dependencias/imagenes/mlc.png">
                      </button>
                      <input type="text" value="${arrg_input_val.exu}" class="form-control" id="txtEName" name="txtEName" placeholder="Exhibidor seleccionado ..." readonly="readonly" style="margin-top: 5px;background-color: #fff;">
                      <div class="valid-feedback">
                            <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-19">
                      </div>     
                       
                      <input type="hidden" id="cbexhibidoru" name="cbexhibidoru" value="">
  
                    </div>
                  </div>


                  <div class="form-group" id="content-exd">

                    <label>Exhibidor 2:</label>

                    <div id="c-exhibidord">


                      <button type="button" id="AddFilesdos" class="btn btn-light btnNormal">Seleccione exhibidor 2 &nbsp;
                        <img src="../dependencias/imagenes/mlc.png">
                      </button>
                      <input type="text" value="${arrg_input_val.exd}" class="form-control" id="txtENamedos" name="txtENamedos" placeholder="Exhibidor seleccionado ..." readonly="readonly" style="margin-top: 5px;background-color: #fff;">
                      <div class="valid-feedback">
                            <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-20">
                      </div>     
                      <input type="hidden" id="cbexhibidord" name="cbexhibidord" value="">
                    </div>



                  </div>
                  <div class="form-group" id="content-ext">

                    <label>Exhibidor 3:</label>

                    <div id="c-exhibidort">

                      <button type="button" id="AddFilestres" class="btn btn-light btnNormal">Seleccione exhibidor 3 &nbsp;
                        <img src="../dependencias/imagenes/mlc.png">
                      </button>
                      <input type="text" value="${arrg_input_val.ext}" class="form-control" id="txtENametres" name="txtENametres" placeholder="Exhibidor seleccionado ..." readonly="readonly" style="margin-top: 5px;background-color: #fff;">
                      <div class="valid-feedback">
                            <strong></strong>
                      </div>
                      <div class="invalid-feedback" id="error-mjs-21">
                      </div>     
                       
                      <input type="hidden" id="cbexhibidort" name="cbexhibidort" value="">

                    </div>

                  </div>

                <div class="form-group" id="content-fotoexhibidor" style="display: none;">
                  <div>
                    <label>Foto exhibidor principal:</label>
                    <div class="custom-file">
                      <input id="fileexhibidor" name="fileexhibidor" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                      <label class="custom-file-label" data-browse="Tomar foto" for="customFileLang">Foto exhibidor principal</label>
                      <div class="valid-feedback">
                        <strong></strong>
                      </div>
                      <div class="invalid-feedback">
                        <strong>Por favor toma una foto!</strong>
                      </div>
                    </div><br><br>

                    <div id="contenedor-foto-exh">
                    <img src="${imgexhibidor}" style="border: 1px solid black;width:200px;height:200px;"/>
                    </div>

                    <canvas id="canvasd" style="border: 1px solid black;width:200px;height:200px;display:none;" width="200px" height="200px">
                    </canvas>
                  </div>
                </div>

                  <div class="form-group">
                    <label>Bocadeli:</label>
                    <input type="tel" name="txtmcomprab" id="txtmcomprab" class="form-control" placeholder="Compra Bocadeli" value="${arrg_input_val.comprab}">
                    <div class="valid-feedback">
                      <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-23">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Diana:</label>
                    <input type="tel" name="txtmcomprad" id="txtmcomprad" class="form-control" placeholder="Compra Diana" value="${arrg_input_val.comprad}">
                    <div class="valid-feedback">
                      <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-24">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Yummies:</label>
                    <input type="tel" name="txtmcompray" id="txtmcompray" class="form-control" placeholder="Compra Yummies" value="${arrg_input_val.compray}">
                    <div class="valid-feedback">
                      <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-25">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Frito lay:</label>
                    <input type="tel" name="txtmcompraf" id="txtmcompraf" class="form-control" placeholder="Compra Frito lay" value="${arrg_input_val.compraf}">
                    <div class="valid-feedback">
                      <strong></strong>
                    </div>
                    <div class="invalid-feedback" id="error-mjs-26">
                    </div>
                  </div>

                <div class="form-group">
                   <button type="button" id="ok-editar" name="confirmar-editar" class="btn btn-info" style="font-size:16px;font-weight: bold;" onclick="editar_cliente('${arrg_input_val.editarcodigo}','${arrg_input_val.rutanombre}',${arrg_input_val.codigotr})"><span class="fa fa-check-circle" style="font-size: 16px;"></span> Aceptar</button>
                   <button type="button" name="cancelar-agregar" class="btn btn-danger" style="font-size:16px;font-weight: bold;" onclick="cancelar_actividad()"><span class="fa fa-times-circle" style="font-size: 16px;"></span> Cancelar</button>
                </div>
            </div>
        </div>
    </div>`;
    return formu_html;
}
/*00000000000000000000000000000000000000000000000000000000000000000000000000000*/
/*---------------------MODIFICACION DE CLIENTE---------------------------------*/
/*00000000000000000000000000000000000000000000000000000000000000000000000000000*/

async function editar_cliente(codeditar,nombreruta,codetr){
    $("#ok-editar").attr("disabled", true);
    var detalle_validacion = '';
    // console.log('VALIDACION FORM => '+validacion_form());
    if(validacion_form() < 33){
            arrg_vali_result.forEach( function(valor, indice, array) {
            if(!empty(valor)){
                detalle_validacion += `<p>${valor}</p>`;
            }else{}
            });
            Swal.fire({
                title: '<strong>Atención!</strong>',
                type: 'info',
                html:detalle_validacion,
                confirmButtonText:'Ok'
            });
            $("#ok-editar").attr("disabled", false);
            return;
    }else{
        var cod = codeditar;
        var coderuta = nombreruta;
        // console.log('MI RUTA ES '+coderuta);
        var datas_info = '';
        datas_info = $("#form-validacion").serializeArray();
        datas_info.push({name: 'coderuta', value: coderuta});
        // alert(datas_info.nomestablecimiento);
        // alert(cod);
        datas_info.push({name: 'codecliente', value: cod});
        // console.log(datas_info);
        const { value: text } = await Swal.fire({
          input: 'textarea',
          inputPlaceholder: 'Deja tu comentario...',
          inputAttributes: {
            'aria-label': 'Deja tu comentario'
          },
          showCancelButton: false,
          allowOutsideClick: false
        });

        if (text) {
            $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
                datas_info.push({name: 'comentarioedit', value: text});
                $.ajax({
                    url:'modificar-clientes/ok_modificar_cliente',
                    type:"POST",
                    data:datas_info,
                    dataType: "JSON"
                }).done(function(resul) {
                    if(resul.rs == true){

                    var numero_pagina = $(".pagination span.current").text();
                    // alert('numero de pagina '+numero_pagina);
                    $.when( $("#div_filtrorutas").stop(true,true).show(20) ).done(function( x ) {
                        $.when( $("#formularios").stop(true,true).hide(20) ).done(function( x ) {
                            $.when( $("#content-tabla").stop(true,true).show(20) ).done(function( x ) {
                                $.when( $("#content_actualizados").stop(true,true).show(20) ).done(function( x ) {
                                paginar_supervisores(numero_pagina,1);
                                Swal.fire({
                                    type: 'success',
                                    title: 'Cliente modificado exitosamente!',
                                    showConfirmButton: false,
                                    timer: 1200
                                }).then((result) => {
                                    var u = 1;
                                    editar_count++;
                                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                                        // $("#btn-duplicado"+codetr).click();
                                            // var bandera_dekikito = 0;
                                            // $("#contenedor"+codetr).addClass("sombra");
                                            // $('#contenedor'+codetr).fadeOut("fast").fadeIn("fast",function(){
                                            //     $("#contenedor"+codetr).removeClass("sombra");
                                            // });
                                    });
                                });
                                });
                            });
                        });
                    });
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                            Swal.fire({
                                type: 'info',
                                title: 'No se ha realizado ningún cambio.',
                                showConfirmButton: false,
                                timer: 2900
                            });
                            $("#ok-editar").attr("disabled", false);                    
                        });
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                        Swal.fire({
                            type: 'error',
                            title: 'Ha pasado algo  malo :(<br> por favor recarga la p&aacute;gina e intenta de nuevo',
                            showConfirmButton: false,
                             timer: 2900
                        });
                        $("#ok-editar").attr("disabled", false);
                    });
                    // retorna_inicio();
                });               
            });


        }else{
            $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                $("#ok-editar").attr("disabled", false);
                Swal.fire({
                    title: '<strong>Por favor deja un comentario del por qué se modifico el cliente!</strong>',
                    type: 'info',
                    html:'',
                    confirmButtonText:'Ok',
                    // showCancelButton: true,
                    // cancelButtonText: 'Cancelar'
                }).then((result) => {
                    // alert('presionar el boton');
                    // alert(result.value);
                    if(result.value){
                        // $("#ok-editar").click();
                    }else{

                    }
                });
            });

        }

    }
}


function mostrar_mapak(idubicacion,tipo,tpc){
    // alert('maga de actualizados');
        scrolly = $(window).scrollTop();
        scrollx = $(window).scrollLeft();
        var ubicacioncod = idubicacion;
        ubicacioncod = ubicacioncod.substring(10,ubicacioncod.length);
        var txtlongitud = 0;
        var txtlatitud = 0;
        var txtdireccion = '';
        var txtruta = '';
        var depa = '';
        var muni = '';
        var nomcliente = '';
        var infocliente = '';
        if(tipo == 1){
            txtlongitud = $("#tk-long"+ubicacioncod).val();
            txtlatitud = $("#tk-lati"+ubicacioncod).val();
            txtdireccion = $("#tk-dire"+ubicacioncod).val();
            txtruta = $("#rktanom"+ubicacioncod).val();
            depa = $("#dkpa"+tpc+ubicacioncod).val();
            muni = $("#mkni"+tpc+ubicacioncod).val();
            nomcliente = $("#tk-nn"+tpc+ubicacioncod).text();
        }else{
            txtlongitud = $("#tk-long"+ubicacioncod).val();
            txtlatitud = $("#tk-lati"+ubicacioncod).val();
            txtdireccion = $("#tk-dire"+ubicacioncod).val();
            txtruta = $("#rktanom"+ubicacioncod).val();
            depa = $("#dkpa"+tpc+ubicacioncod).val();
            muni = $("#mkni"+tpc+ubicacioncod).val(); 
            nomcliente = $("#tk-n"+ubicacioncod).text();         
        }

        if(V_CoordenadasLL(txtlatitud)){
            txtlatitud = txtlatitud;
        }else{
            txtlatitud = 0;
        }

        if(V_CoordenadasLL(txtlongitud)){
            txtlongitud = txtlongitud;
        }else{
            txtlongitud = 0;
        }

        info = `<strong><span style="color:#26869F;">RUTA:</span> <span style="color:#657073;">${txtruta}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">CLIENTE:</span> <span style="color:#657073;">${nomcliente}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">DEPARTAMENTO:</span> <span style="color:#657073;">${depa}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">MUNICIPIO:</span> <span style="color:#657073;">${muni}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">DIRECCION:</span> <span style="color:#657073;">${txtdireccion}</span></strong>`;

        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            $.when( $("#div_filtrorutas").stop(true,true).hide(20) ).done(function( x ) {
                $.when( $("#content-tabla").stop(true,true).hide(20) ).done(function( x ) {
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                        $.when( $("#content_actualizados").stop(true,true).hide(20) ).done(function( x ) {
                            // $.when( $(".titulos").stop(true,true).hide() ).done(function( x ) {});
                            iniciar_mapa(txtlatitud,txtlongitud,info);
                        });
                    });
                });
            });
        });

}

function mostrar_mapa(idubicacion,tipo,tpc){
        scrolly = $(window).scrollTop();
        scrollx = $(window).scrollLeft();
        var ubicacioncod = idubicacion;
        ubicacioncod = ubicacioncod.substring(10,ubicacioncod.length);
        var txtlongitud = 0;
        var txtlatitud = 0;
        var txtdireccion = '';
        var txtruta = '';
        var depa = '';
        var muni = '';
        var nomcliente = '';
        var infocliente = '';
        if(tipo == 1){
            txtlongitud = $("#tu-long"+ubicacioncod).val();
            txtlatitud = $("#tu-lati"+ubicacioncod).val();
            txtdireccion = $("#tu-dire"+ubicacioncod).val();
            txtruta = $("#ritanom"+ubicacioncod).val();
            depa = $("#depa"+tpc+ubicacioncod).val();
            muni = $("#muni"+tpc+ubicacioncod).val();
            nomcliente = $("#td-nn"+tpc+ubicacioncod).text();
        }else{
            txtlongitud = $("#td-long"+ubicacioncod).val();
            txtlatitud = $("#td-lati"+ubicacioncod).val();
            txtdireccion = $("#td-dire"+ubicacioncod).val();
            txtruta = $("#rutanom"+ubicacioncod).val();
            depa = $("#depa"+tpc+ubicacioncod).val();
            muni = $("#muni"+tpc+ubicacioncod).val(); 
            nomcliente = $("#td-n"+ubicacioncod).text();         
        }

        if(V_CoordenadasLL(txtlatitud)){
            txtlatitud = txtlatitud;
        }else{
            txtlatitud = 0;
        }

        if(V_CoordenadasLL(txtlongitud)){
            txtlongitud = txtlongitud;
        }else{
            txtlongitud = 0;
        }

        info = `<strong><span style="color:#26869F;">RUTA:</span> <span style="color:#657073;">${txtruta}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">CLIENTE:</span> <span style="color:#657073;">${nomcliente}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">DEPARTAMENTO:</span> <span style="color:#657073;">${depa}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">MUNICIPIO:</span> <span style="color:#657073;">${muni}</span></strong><br>`;
        info += `<strong><span style="color:#26869F;">DIRECCION:</span> <span style="color:#657073;">${txtdireccion}</span></strong>`;

        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            $.when( $("#div_filtrorutas").stop(true,true).hide(20) ).done(function( x ) {
                $.when( $("#content-tabla").stop(true,true).hide(20) ).done(function( x ) {
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                        $.when( $("#content_actualizados").stop(true,true).hide(20) ).done(function( x ) {
                            // $.when( $(".titulos").stop(true,true).hide() ).done(function( x ) {});
                            iniciar_mapa(txtlatitud,txtlongitud,info);
                        });
                    }); 
                });
            });
        });

}
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*--------CREACION DE FORMULARIO EDITAR CLIENTES--------------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
function ctr_form_editar(ideditar,opt,tipoaco,codigotr){

    // console.log(ideditar);
    // console.log(opt);
    // console.log(tipoaco);
    // console.log(codigotr);
    // console.log('MODIFICANDO CLIENTE NORMAL');
    $("#imagendos").val("");
    $("#imagecli").val("");
    $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
        $.when( $("#div_filtrorutas").stop(true,true).hide(20) ).done(function( x ) {

            var editarcod = ideditar;
            editarcod = editarcod.substring(7,editarcod.length);
            if(opt == 0){
                // console.log('Codigo normal');
                var cod = mis_codigos[editarcod];
            }else{
                var cod = tipoaco;
            }
            scrolly = $(window).scrollTop();
            scrollx = $(window).scrollLeft();
            // console.log(cod);
            $.ajax({
                url:'editar-cliente-show/mostrar_cliente',
                type:"POST",
                data:{codecli:cod},
                dataType: "JSON"
            }).done(function(resul) {
                // alert(resul.parametros);
                var strnumerocontry = resul.numerocontry;
                var valornumcontry = strnumerocontry.replace("-", "");
                var textfsemanal = '';
                var arrg_inputs_val = [];
                var arrg_inputs_val = {
                    nombre:resul.nombrecli,
                    direccion:resul.direccioncli,
                    telefono:resul.telefonocli,
                    contacto:resul.contactocli,
                    dias:resul.diascli,
                    ordenvisita:resul.ordenvisitacli,
                    deptselect:resul.selectdept,
                    muniselect:resul.selectmuni,
                    deptlist:resul.ldepartamento,
                    munilist:resul.lmunicipio,
                    fotofachada:resul.fotofachada,
                    tpvlist: resul.ltpuntoventa,
                    tpvselect:resul.stpuntoventa,
                    gironegociolist:resul.lgironegocio,
                    gironegocioselect:resul.sgironegocio,
                    tipofacturacion:resul.ltfacturacion,
                    tipofactuselect:resul.stfacturacion,
                    condiciocliente:resul.lcondicioncli,
                    condicioncliselct:resul.scondicionc,
                    dui:resul.dui,
                    numcontriy:valornumcontry,
                    nit:resul.nit,
                    diacobro:resul.diacobro,
                    montocredito:resul.montocredito,
                    exu:resul.exu,
                    exd:resul.exd,
                    ext:resul.ext,
                    exhibidorlis:resul.lexhibidor,
                    comprab:resul.comprab,
                    comprad:resul.comprad,
                    compray:resul.compray,
                    compraf:resul.compraf,
                    cbcantidadex:resul.cantidadex,
                    fotoexhibidor:resul.img_exhibidor,
                    rutanombre:resul.rutanombre,
                    codigotr:codigotr,
                    editarcodigo:cod,
                    editados:resul.editados,
                    comentarioe:resul.comentarioe,
                    Ord_VisitaSema:resul.Ord_VisitaSema
                };
                $("#img-cliente").val(resul.fotofachada);
                $("#img_exhibid").val(resul.img_exhibidor);
                $("#cbcantidadex").val(resul.cantidadex);
                if($("#td-f"+editarcod).text() == 'NA'){
                    textfsemanal = 'SEMANAL';
                }else if($("#td-f"+editarcod).text() == '1,3'){
                    textfsemanal = 'QUINCENAL 1,3';
                }else if($("#td-f"+editarcod).text() == '2,3'){
                    textfsemanal = 'QUINCENAL 2,3';
                }else if($("#td-f"+editarcod).text() == '1'){
                    textfsemanal = 'MENSUAL S1';
                }else if($("#td-f"+editarcod).text() == '2'){
                    textfsemanal = 'MENSUAL S2';
                }else if($("#td-f"+editarcod).text() == '3'){
                    textfsemanal = 'MENSUAL S3';
                }else if($("#td-f"+editarcod).text() == '4'){
                    textfsemanal = 'MENSUAL S4';
                }else{
                    textfsemanal = 'SEMANAL';
                }
                DataTB = "";
                $.each(resul.lexhibidor, function(i, val){
     
                    // DataTB+="<input type='hidden' class='Cme' value='"+arr_dat[i].valor+"'>";
                    DataTB+="<tr id='ROW_"+(i+1)+"' class='TrSelect NormalTR'>";
                    // DataTB+="<input type='text' class='Cme' value='"+arr_dat[i].codbx+"'>";
                    // DataTB+="   <th scope='row'>"+(i+1)+"</th>";
                    DataTB+="   <td>"+val.SKU+"</td>";
                    DataTB+="   <td style='display:none;' class='Cme'>"+val.codbx+"</td>";
                    DataTB+="   <td class='Nme'>"+val.valor+"</td>";
                    // if(String(arr_dat[i].IMAGEN_E)=="NULL"){
                    //     DataTB+="   <td><div class='NulleIMG'>Sin imagen</div></td>";   
                    // }else{
                    //     // DataTB+="   <td><img src='"+arr_dat[i].IMAGEN_E+"'></td>";
                    // }
                    // DataTB+="<<Sin imagen>>";
                    DataTB+="</tr>";
                });

                // alert(textfsemanal);
                // alert($("#td-f"+editarcod).text());

                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                    var form_html = ``;
                    /*--------------------------------------------------*/
                    /*0000000FUNCION PARA CREAR FORMULARIO EDITAR00000000*/
                    /*--------------------------------------------------*/
                    form_html = crea_form_cli(arrg_inputs_val,0,codigotr);
                    $("#formularios").empty().html(form_html);


                    $.when( $("#content-tabla").stop(true,true).hide(20) ).done(function( x ) {
                    $.when( $("#content_actualizados").stop(true,true).hide(20) ).done(function( x ) {
                        $("#formularios").show("fast");
                        $('#formularios #telefono').mask(formatoTel, {placeholder: formatoTel});
                        $('#txtdui').mask(formatoNumIP, {placeholder: formatoNumIP});
                        $('#txtnit').mask(fotmatoNumNit, {placeholder: fotmatoNumNit});
                        // $("#cbfrecuenciavisita option[value="+ $("#td-f"+editarcod).text() +"]").attr("selected",true);
                        $("#cbfrecuenciavisita option:contains("+textfsemanal+")").attr('selected', true);

                        var txtcbfacturacion = '';
                        txtcbfacturacion = $('select[name="cbtfacturacion"] option:selected').text();
                        if(txtcbfacturacion === 'CREDITO FISCAL'){
                            $('#if-tfactura').show();
                            $('#div_duiu').show();
                            $('#div_numregistrou').show();
                            $('#div_nitu').show();
                        }else{
                            if(arrg_Credls['pais'] == 'EL SALVADOR'){
                                $('#if-tfactura').hide();
                                $('#div_duiu').hide();
                                $('#div_numregistrou').hide();
                                $('#div_nitu').hide();
                            }else if(arrg_Credls['pais'] == 'GUATEMALA'){
                                $('#if-tfactura').hide();
                                $('#div_duiu').hide();
                                $('#div_numregistrou').hide();
                                $('#div_nitu').hide();
                            }else if(arrg_Credls['pais'] == 'HONDURAS'){
                                $('#if-tfactura').show();
                                $('#div_duiu').show();
                                $('#div_numregistrou').hide();
                                $('#div_nitu').show();
                            }
                        }
                        var txtcondicioncli = '';
                        txtcondicioncli = $('select[name="cbcondicioncli"] option:selected').text();
                        if(txtcondicioncli === 'CREDITO'){
                            $('#if-condcliente').show();
                            $("#cbdiascobro option[value="+ resul.diacobro +"]").attr("selected",true);
                        }else{
                            $('#if-condcliente').hide();
                        }

                        if(resul.cantidadex == 1){
                            $("#content-exu").show();
                            $("#content-exd").hide();
                            $("#content-ext").hide();
                        }else if(resul.cantidadex == 2){
                            $("#content-exu").show();
                            $("#content-exd").show();
                            $("#content-ext").hide();
                        }else if(resul.cantidadex == 3){
                            $("#content-exu").show();
                            $("#content-exd").show();
                            $("#content-ext").show();
                        }else{
                            $("#content-exu").hide();
                            $("#content-exd").hide();
                            $("#content-ext").hide();
                        }
                        if(resul.cantidadex > 0){
                            $("#content-fotoexhibidor").show();
                        }else{
                            $("#content-fotoexhibidor").hide();
                        }
                        /*----------*/
                        /*IMAGEN UNO*/
                        /*----------*/
                        canvassu = document.getElementById('canvas-fachada');
                        var imageLoadersu = document.getElementById('filefnegocio');
                        imageLoadersu.addEventListener('change', handleImagesu, false);
                        /*----------*/
                        /*IMAGEN DOS*/
                        /*----------*/
                        canvasex = document.getElementById('canvasd');
                        var imageLoaderex = document.getElementById('fileexhibidor');
                        imageLoaderex.addEventListener('change', handleImagefa, false);
                        /*------------------------------------------------------*/
                        /*------------VALOR ACTUAL DE LOS EXHIBIDORES-----------*/
                        /*------------------------------------------------------*/
                        $("#cbexhibidoru").val(resul.codeexhu);
                        $("#cbexhibidord").val(resul.codeexhd);
                        $("#cbexhibidort").val(resul.codeexht);
                        /*------------------------------------------------------*/
                        validacion_form();
                    });
                    });
                });

            }).fail(function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    type: 'error',
                    title: 'Ha pasado algo  malo :(<br> por favor recarga la p&aacute;gina e intenta de nuevo',
                    showConfirmButton: false,
                    timer: 2900
                });
            });
        });
    });
        // alert(scrolly); 
}
/*----------------------------ArrayList------total----pagiancion*/
function construir_duplicados(lista_clientes,totalcli,paginadocli,acordeonum,codigotr,tpc){
    var paginasin = '';
    var htmlinsertarsin = ``;
    if(totalcli > 0){
    paginasin = paginadocli;
    var htmlinsertarcon = ``;
    var contts = 0;
    var p_dias = '';
    var estados = '';
    var badge_dias = ``;
    var cadena_dias_true = 'L_1,M_1,I_1,J_1,V_1,S_1,D_1';
    cadena_dias_true = cadena_dias_true.split(',');
    var html_content_aco = ``;
    var count_nuevos = 0;
    $.each(lista_clientes, function(e, valu){
        if(valu.estado == 'N' ){
            count_nuevos++;
        }else{

        }
    });
    htmlinsertarsin+=`
    <!--<div class="table-responsive">-->
        <table class="table table-bordered" id="tabla-clientes_subaco${tpc}">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">RUTA</th>
                    <th scope="col">NOMBRE</th>
                    <th scope="col" width="26%">DIRECCION</th>
                    <th scope="col">TELEFONO</th>
                    <th scope="col">CONTACTO</th>
                    <th scope="col">DIA</th>
                    <th scope="col">ORDEN VISITA</th>
                    <th scope="col">FRECUENCIA DE VISITA</th>
                    <!--<th scope="col">DEPARTAMENTO</th>-->
                    <!--<th scope="col">MUNICIPIO</th>-->
                    <th scope="col">UBICACION</th>
                    <th scope="col">OPCIONES</th>
                    <th scope="col" width="20%;">RESOLUCION</th>
                </tr>
            </thead>`;
    $.each(lista_clientes, function(i, val){
        var titulo_dep_mun = ``;
        contts++;
        p_dias = val.dias.split(',');
        for(d=0;d<=6;d++){
            if(p_dias[d] == cadena_dias_true[d]){
                if(p_dias[d] == 'L_1'){
                    badge_dias += `<span class="badge badge-info">LUNES</span> `;
                }else if(p_dias[d] == 'M_1'){
                    badge_dias += `<span class="badge badge-info">MARTES</span> `;
                }else if(p_dias[d] == 'I_1'){
                    badge_dias += `<span class="badge badge-info">MIERCOLES</span> `;
                }else if (p_dias[d] == 'J_1'){
                    badge_dias += `<span class="badge badge-info">JUEVES</span> `;
                }else if (p_dias[d] == 'V_1'){
                    badge_dias += `<span class="badge badge-info">VIERNES</span> `;
                }else if (p_dias[d] == 'S_1'){
                    badge_dias += `<span class="badge badge-info">SABADO</span> `;
                }else if (p_dias[d] == 'D_1'){
                    badge_dias += `<span class="badge badge-info">DOMINGO</span> `;
                }else{
                    badge_dias = 'NA';
                }
            }else{
                 // badge_dias = 'NA';
            }
        }
        //binoculars
        var claseactivo = ``;
        var estadowes = ``;
        var editar_btn_new = ``;
        var ruta_badge = ``;
        var departamento = ``;
        var municipio = ``;
        departamento = val.departamento;
        municipio = val.municipio;
        if(val.estadow == 1 && val.estado == 'P'){
            claseactivo = 'activo';
            estadowes = `<span class="badge badge-primary">YA EXISTE</span>`;
            ruta_badge = `<span class="badge badge-primary">`;
        }else if(val.estadow == 0 && val.estado == 'P'){
            claseactivo = 'inactivo';
            estadowes = `<span class="badge badge-danger">YA EXISTE</span>`;
            ruta_badge = `<span class="badge badge-danger">`;
        }else if(val.estado == 'R'){
            claseactivo = 'inactivo';
            estadowes = `<span class="badge badge-danger">RECHAZADO</span>`;
            ruta_badge = `<span class="badge badge-danger">`;
        }else{
            if(val.estado == 'A' && val.Estado_Analista == 'A'){
                claseactivo = 'activo';
                estadowes = `<span class="badge badge-primary">APROBADO</span>`;
                ruta_badge = `<span class="badge badge-primary">`;
            }else if(val.estado == 'A' && String(val.Estado_Analista).toLowerCase() == 'null'){
                claseactivo = 'activo';
                estadowes = `<span class="badge badge-primary">APROBADO</span>`;
                ruta_badge = `<span class="badge badge-primary">`;
            }else if(val.estado == 'A' && val.Estado_Analista == 'R'){
                claseactivo = 'inactivo';
                estadowes = `<span class="badge badge-danger">RECHAZADO</span>`;
                ruta_badge = `<span class="badge badge-danger">`;
            }else if(val.estado == 'N' ){
                editar_btn_new = `<button type="button" class="btn btn-success btn-form" id="dditar-${i}" onclick="ctr_form_editar(this.id,1,'${val.codcli}',${codigotr})"><span class="fa fa-pencil-alt"></span> EDITAR</button>`;
                estadowes = `<span class="badge badge-success">NUEVO</span>`;
                claseactivo = 'nuevo_duplicado';
                ruta_badge = `<span class="badge badge-success">`;
            }else{
                estadowes = `<span class="badge badge-warning">NO IDENTIFICADO</span>`;
                claseactivo = 'inactivo';
                ruta_badge = `<span class="badge badge-warning">`;
            }
        }
        titulo_dep_mun = `<span class="badge badge-secondary">Departamento:</span> ${departamento},<br> <span class="badge badge-secondary">Municipio:</span> ${municipio}<br>`;
        htmlinsertarsin+=`
        <tbody>
            <tr id="${tpc}contenedor_subaco${i}" href="#contenedor_subaco${i}" class="${claseactivo}">
                <td style="vertical-align:middle;text-align:center;">
                    ${ruta_badge}${val.ruta}</span><br>${estadowes}<br>${val.codigo}
                    <input type=hidden value="${val.ruta}" id="ritanom${i}">
                    <input type="hidden" value="${val.idruta}" id="rutaidd-${i}">
                </td>
                <td style="vertical-align:middle;" id="td-nn${tpc}${i}">${val.nombrecliente}</td>
                <td style="vertical-align:middle;" id="td-dd${i}">
                    ${titulo_dep_mun}
                    <span class="badge badge-secondary">Direccion: </span>${val.direccion}
                    <input type="hidden" id="depa${tpc}${i}" value="${departamento}">
                    <input type="hidden" id="muni${tpc}${i}" value="${municipio}">  
                </td>
                <td style="vertical-align:middle;" id="td-tt${i}">${val.telefono}</td>
                <td style="vertical-align:middle;" id="td-cc${i}">${val.contacto}</td>
                <td style="vertical-align:middle;"><center>${badge_dias}</center>
                    <input type="hidden" id="td-dias${i}" value="${val.dias}">
                </td>
                <td style="vertical-align:middle;" id="td-oo${i}">${val.ordenvisita}</td>
                <td style="vertical-align:middle;" id="td-ff${i}">${val.frecuenciavisita}</td>
                <!--<td style="vertical-align:middle;" id="td-depp${i}">${departamento}</td>-->
                <!--<td style="vertical-align:middle;" id="td-munn${i}">${municipio}</td>-->
                <td style="vertical-align:middle;">
                    <button type="button" class="btn btn-secondary btn-form ubicacion" id="ubicacion-${i}" onclick="mostrar_mapa(this.id,1,'${tpc}')"><span class="fa fa-map-marked-alt"></span> MAPA</button>
                    <input type="hidden" id="tu-long${i}" value="${val.long}">
                    <input type="hidden" id="tu-lati${i}" value="${val.lati}">
                    <input type="hidden" id="tu-dire${i}" value="${val.direccion}">
                </td>                                
                <td style="vertical-align:middle;text-align:center;">${editar_btn_new}</td>
                <td style="vertical-align:middle;">`;
                    if(val.igual == 'SI'){
                    htmlinsertarsin+=`
                    <select class="form-control custom-select resolucion" id="${tpc}cbresolucioo-${i}" onchange="confirmar_resolucion(this.value,'${val.codcli}',1,this.id,${count_nuevos})">
                        <option value="">Seleccione una opcion</option>
                        <option value="1">APROBADO</option>
                        <option value="2">RECHAZADO</option>
                    </select>`;
                    }else{}
                    htmlinsertarsin+=`
                </td>
            </tr>`;
            // }else{}
        badge_dias = ``;
    });
    htmlinsertarsin+=`
    </tbody>
    </table>
    <!--</div>-->
    <div class="paginacion">${paginasin}</div>`;
    }else{
        htmlinsertarsin+=`
        <div class="alert alert-dark" role="alert">
            <h4><br>NO SE ENCONTRARON REGISTROS DUPLICADOS</h4>
        </div>`;
    }

    return htmlinsertarsin;
}

function confirmar_resolucion(valorresolucion,iddicliente,tipo,idcbresolucion,countnue){
    var nFilas = 0;
    // $("#tabla-clientes tr").each(function() {
    $("#tabla-clientes .tr_principalTBNuevos").each(function() {
        nFilas++;
    });
    // FILA QUITADA POR FILA POR DEFECTO TITULO DE TABLA
    // nFilas = nFilas - 1;
    // alert(nFilas);
    var contenedor_t = '';
    if(tipo == 0){
        var idcbresolucion = idcbresolucion;
        idcbresolucion = idcbresolucion.substring(13,idcbresolucion.length);
        contenedor_t = 'contenedor'+idcbresolucion;
    }else{
        var tpcigual = idcbresolucion;
        tpcigual = tpcigual.substring(1,0);
        var idcbresolucion = idcbresolucion;
        idcbresolucion = idcbresolucion.substring(14,idcbresolucion.length);
        contenedor_t = tpcigual+'contenedor_subaco'+idcbresolucion;
        /*00000000000000000000FIN00000000000000000000000000000000000000000000000000000000*/
    }
    scrolly = $(window).scrollTop();
    scrollx = $(window).scrollLeft();
    $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
        $.ajax({
            url:'resolucion-cliente/ok_resolucion',
            type:"POST",
            data:{resolucion:valorresolucion,codecliente:iddicliente},
            dataType: "JSON"
        }).done(function(resul) {
            if(resul.rs == true){
                var tipoR = '';
                if(valorresolucion == 1){
                    tipoR = 'APROBADO <span class="fa fa-user-plus aprob"></span>';
                }else{
                    tipoR = 'RECHAZADO <span class="fa fa-user-slash recha"></span>';
                }
                nFilas = nFilas - 1;
                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                    var numero_pagina = $(".pagination span.current").text();
                    // alert('numero de pagina '+numero_pagina);
                    $.when( $("#formularios").stop(true,true).hide(20) ).done(function( x ) {
                        // paginar_supervisores(numero_pagina);
                        $.when( $("#content-tabla").stop(true,true).show(20) ).done(function( x ) {
                            Swal.fire({
                                type: 'success',
                                title: tipoR,
                                html:'<p class="info_clie">Ruta: '+$("#td-ruta"+idcbresolucion).text()+'<br>Nombre: '+$("#td-n"+idcbresolucion).text()+'<br>Contacto: '+$("#td-c"+idcbresolucion).text()+'<br>Teléfono: '+$("#td-t"+idcbresolucion).text()+'</p>',
                                showConfirmButton: false,
                                timer: 1300
                            }).then((result) => {
                                if(nFilas == 0){
                                    if(numero_pagina <= 1){
                                        // numero_pagina = 1;
                                        if(empty(numero_pagina)){
                                            numero_pagina = 1;
                                        }
                                        // alert('filas [' +nFilas+ ' ] numero de pagina <= 1 [ '+numero_pagina+' ]');
                                    }else{
                                        // alert('numero de pagina actual [ ' + numero_pagina+' ]');
                                        numero_pagina = numero_pagina - 1;
                                        // alert('numero de pagina despues [ ' + numero_pagina+' ]');
                                    }
                                }else{
                                    // numero_pagina = 1;
                                    if(empty(numero_pagina)){
                                        numero_pagina = 1;
                                    }
                                    // alert('Sin ajustes de vista, pagina actual [ '+numero_pagina+' ]');
                                }
                                $("#"+contenedor_t).addClass("sombra");
                                // $('#'+contenedor_t).fadeOut(150).fadeIn(150).fadeOut(150).fadeIn(150).fadeOut(150,function(){
                                    // $("#"+contenedor_t).removeClass("sombra");
                                    $.when($("#"+contenedor_t).stop(true,true).hide(20) ).done(function( x ) {
                                        paginar_supervisores(numero_pagina,1);
                                        $('html').animate({scrollTop : scrolly}, 500);
                                    });
                                // });
                            });
                        });
                    });
                });
            }else{
                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                    Swal.fire({
                        type: 'info',
                        title: 'No se ha realizado ningún cambio.',
                        showConfirmButton: false,
                        timer: 2900
                    });
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                Swal.fire({
                    type: 'error',
                    title: 'Ha pasado algo  malo :(<br> por favor recarga la p&aacute;gina e intenta de nuevo',
                    showConfirmButton: false,
                    timer: 2900
                });
            });
        });
    });
}

function confirmar_resoluciok(valorresolucion,iddicliente,tipo,idre,countnue){
    var nFilas = 0;
    $("#tabla_clientesAC tr").each(function() {
        nFilas++;
    });
    // FILA QUITADA POR FILA POR DEFECTO TITULO DE TABLA
    nFilas = nFilas - 1;
    //console.log(iddicliente);
    scrolly = $(window).scrollTop();
    scrollx = $(window).scrollLeft();
    $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
        $.ajax({
            url:'actualizacion/resolucionac',
            type:"POST",
            data:{resolucion:valorresolucion,codecliente:iddicliente},
            dataType: "JSON"
        }).done(function(resul) {
            if(resul.rs == true){
                //FILA QUITADA POR ENVIO DE RESOLUCION
                var tipoR = '';
                if(valorresolucion == 1){
                    tipoR = 'APROBADO <span class="fa fa-user-plus aprob"></span>';
                }else{
                    tipoR = 'RECHAZADO <span class="fa fa-user-slash recha"></span>';
                }
                nFilas = nFilas - 1;
                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                    var numero_pagina = $(".pagination span.currentd").text();
                    $.when( $("#formularios").stop(true,true).hide(20) ).done(function( x ) {
                         $.when( $("#content_actualizados").stop(true,true).show(20) ).done(function( x ) {
                            Swal.fire({
                                type: 'success',
                                title: tipoR,
                                html:'<p class="info_clie">Ruta: '+$("#tk-ruta"+idre).text()+'<br>Nombre: '+$("#tk-n"+idre).text()+'<br>Contacto: '+$("#tk-c"+idre).text()+'<br>Teléfono: '+$("#tk-t"+idre).text()+'</p>',
                                showConfirmButton: false,
                                timer: 1300
                            }).then((result) => {
                                if(nFilas == 0){
                                    if(numero_pagina <= 1){
                                        if(empty(numero_pagina)){
                                            numero_pagina = 1;
                                        }
                                        // alert('filas [' +nFilas+ ' ] numero de pagina <= 1 [ '+numero_pagina+' ]');
                                    }else{
                                        // alert('numero de pagina actual [ ' + numero_pagina+' ]');
                                        numero_pagina = numero_pagina - 1;
                                        // alert('numero de pagina despues [ ' + numero_pagina+' ]');
                                    }
                                }else{
                                    if(empty(numero_pagina)){
                                        numero_pagina = 1;
                                    }
                                    // alert('Sin ajustes de vista, pagina actual [ '+numero_pagina+' ]');
                                }
                                paginar_clientesAC(numero_pagina,1);
                                $('html').animate({scrollTop : scrolly}, 500);
                            });
                        });
                    });
                });
            }else{
                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                    Swal.fire({
                        type: 'info',
                        title: 'No se ha realizado ningún cambio.',
                        showConfirmButton: false,
                        timer: 2900
                    });
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                 Swal.fire({
                    type: 'error',
                    title: 'Ha pasado algo  malo :(<br> por favor recarga la p&aacute;gina e intenta de nuevo',
                    showConfirmButton: false,
                    timer: 2900
                });
            });
        });
    });
}

/*_____________________________________________________*/
/*<<<<<<<<<<<<<<<<<<EVENTO READY JQUERY>>>>>>>>>>>>>>>>*/
/*_____________________________________________________*/
$(document).ready(function(e){
    CargarComplementos();
    // paginar_supervisores(1);
    // paginar_clientesAC(1);
    // editak" id="editak-${i}"
    /*FORMULARIO PARA VER EL CLIENTE A ACTUALIZAR*/
    $('#content_actualizados').on('click','.editak',function(){
        scrolly = $(window).scrollTop();
        scrollx = $(window).scrollLeft();
        var IdSustrato = $(this).attr("id");
        IdSustrato = IdSustrato.substring(7,IdSustrato.length);
        var IdMiCodigo =  mis_codigosK[IdSustrato];
        codigActualAC = IdMiCodigo;
        var municipioVAL = '';
        var departamentoVAL = '';
        municipioVAL = $("#mknik"+IdSustrato).val();
        departamentoVAL = $("#dkpak"+IdSustrato).val();
        var frecuenciavisitaVAL = '';
        frecuenciavisitaVAL = $("#tk-f"+IdSustrato).text();
        // alert('actualizar '+IdSustrato);
        // alert(municipioVAL+' ---- '+ departamentoVAL);
        //  alert(arrg_Credls['ruta_app']);
        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            $.when( $("#div_filtrorutas").stop(true,true).hide(20) ).done(function( x ) {
                $.ajax({
                    url      : 'ver/clienteac',
                    type     : 'POST',
                    dataType : 'JSON',
                    data     : {'idx_cliente': IdMiCodigo,'idx_municipio':municipioVAL,'idx_departamento':departamentoVAL},
                    timeout  : 30777
                }).done(function(_resp){
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                        
                            $.when( $("#content-tabla").stop(true,true).hide(20) ).done(function( x ) {
                                $.when( $("#content_actualizados").stop(true,true).hide(20) ).done(function( x ) {
                                    $.when( $("#content_cliAc").stop(true,true).show(20) ).done(function( x ) {
                                        var filtro_htmlDepartamento = ``;
                                        var filtro_htmlMunicipio = ``;

                                        /* FILTRO DEPARTAMENTO */
                                        filtro_htmlDepartamento += `<select class="form-control custom-select" id="cbdepartamentod" name="cbdepartamentod">`;
                                        _resp.DepartamentosAC.forEach(function(filall,index, arrgfilall){

                                            if(departamentoVAL == filall.Id_Departamento){
                                                filtro_htmlDepartamento+=`<option value="${filall.Id_Departamento}" selected>${filall.NombreDepartamento}</option>`;
                                            }else{
                                                filtro_htmlDepartamento+=`<option value="${filall.Id_Departamento}">${filall.NombreDepartamento}</option>`;
                                            }
                                        });
                                        filtro_htmlDepartamento+=`</select>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback">
                                            <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
                                        </div>`;
                                        $("#c-departamento").empty().html(filtro_htmlDepartamento);
                                        /*-----------------0000000000000000000000000000000000000000000-----------------------------------*/
                                        /* FILTRO MUNICIPIO */
                                        filtro_htmlMunicipio += `<select class="form-control custom-select" id="cbmunicipiod" name="cbmunicipiod">`;
                                        _resp.MunicipiosAC.forEach(function(filall,index, arrgfilall){

                                            if(municipioVAL == filall.Id_Municipio){
                                                filtro_htmlMunicipio+=`<option value="${filall.Id_Municipio}" selected>${filall.NombreMunicipio}</option>`;
                                            }else{
                                                filtro_htmlMunicipio+=`<option value="${filall.Id_Municipio}">${filall.NombreMunicipio}</option>`;
                                            }
                                        });
                                        filtro_htmlMunicipio+=`</select>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback">
                                            <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
                                        </div>`;
                                        $("#c-municipio").empty().html(filtro_htmlMunicipio);
                                        /*-----------------0000000000000000000000000000000000000000000-----------------------------------*/
                                        var p_dias = '';
                                        var html_dias = ``;
                                        p_dias = _resp.clienteAC[0].DiasAC.split(',');

                                        var style_l='',style_m='',style_i='',style_j='',style_v='',style_s='',style_d='';
                                        if(p_dias[0] === 'L_1'){
                                            arrg_checqueados['lcheck'] = `checked="checked"`;
                                            style_l = 'margin-top:7px;display:;';
                                        }else{
                                            arrg_checqueados['lcheck'] = '';
                                            style_l = 'margin-top:7px;display:none;';
                                        }
                                        if(p_dias[1] === 'M_1'){
                                            arrg_checqueados['mcheck'] = `checked="checked"`;
                                            style_m = 'margin-top:7px;display:;';
                                        }else{
                                            arrg_checqueados['mcheck'] = '';
                                            style_m = 'margin-top:7px;display:none;';
                                        }
                                        if(p_dias[2] === 'I_1'){
                                            arrg_checqueados['icheck'] = `checked="checked"`;
                                            style_i = 'margin-top:7px;display:;';
                                        }else{
                                            arrg_checqueados['icheck'] = '';
                                            style_i = 'margin-top:7px;display:none;';
                                        }
                                        if(p_dias[3] === 'J_1'){
                                            arrg_checqueados['jcheck'] = `checked="checked"`;
                                            style_j = 'margin-top:7px;display:;';
                                        }else{
                                            arrg_checqueados['jcheck'] = '';
                                            style_j = 'margin-top:7px;display:none;';
                                        }
                                        if(p_dias[4] === 'V_1'){
                                            arrg_checqueados['vcheck'] = `checked="checked"`;
                                            style_v = 'margin-top:7px;display:;';
                                        }else{
                                            arrg_checqueados['vcheck'] = '';
                                            style_v = 'margin-top:7px;display:none;';
                                        }
                                        if(p_dias[5] === 'S_1'){
                                            arrg_checqueados['scheck'] = `checked="checked"`;
                                            style_s = 'margin-top:7px;display:;';
                                        }else{
                                            arrg_checqueados['scheck'] = '';
                                            style_s = 'margin-top:7px;display:none;';
                                        }
                                        if(p_dias[6] === 'D_1'){
                                            arrg_checqueados['dcheck'] = `checked="checked"`;
                                            style_d = 'margin-top:7px;display:;';
                                        }else{
                                            arrg_checqueados['dcheck'] = '';
                                            style_d = 'margin-top:7px;display:none;';
                                        }
                                        var l=0,m=0,i=0,j=0,v=0,s=0,d=0;

                                        var OrdenVDia = '';
                                        OrdenVDia = _resp.clienteAC[0].Ord_VisitaSema;

                                        if(OrdenVDia === null){
                                            OrdenVDia = 0;
                                        }else{
                                            OrdenVDia = OrdenVDia.split(',');
                                        }
                                        
                                        if(Object.entries(OrdenVDia).length < 7){
                                            OrdenVDia = [0,0,0,0,0,0,0];
                                        }

                                        ( empty(OrdenVDia[0] )) ?  l ='' : l = OrdenVDia[0];
                                        ( empty(OrdenVDia[1] )) ?  m ='' : m = OrdenVDia[1];
                                        ( empty(OrdenVDia[2] )) ?  i ='' : i = OrdenVDia[2];
                                        ( empty(OrdenVDia[3] )) ?  j ='' : j = OrdenVDia[3];
                                        ( empty(OrdenVDia[4] )) ?  v ='' : v = OrdenVDia[4];
                                        ( empty(OrdenVDia[5] )) ?  s ='' : s = OrdenVDia[5];
                                        ( empty(OrdenVDia[6] )) ?  d ='' : d = OrdenVDia[6];


                                        arrg_checqueados = [];
                                        if(p_dias[0] === 'L_1'){
                                            arrg_checqueados['lcheck'] = `checked="checked"`;
                                        }else{
                                            arrg_checqueados['lcheck'] = '';
                                        }
                                        if(p_dias[1] === 'M_1'){
                                            arrg_checqueados['mcheck'] = `checked="checked"`;
                                        }else{
                                            arrg_checqueados['mcheck'] = '';
                                        }
                                        if(p_dias[2] === 'I_1'){
                                            arrg_checqueados['icheck'] = `checked="checked"`;
                                        }else{
                                            arrg_checqueados['icheck'] = '';
                                        }
                                        if(p_dias[3] === 'J_1'){
                                            arrg_checqueados['jcheck'] = `checked="checked"`;
                                        }else{
                                            arrg_checqueados['jcheck'] = '';
                                        }
                                        if(p_dias[4] === 'V_1'){
                                            arrg_checqueados['vcheck'] = `checked="checked"`;
                                        }else{
                                            arrg_checqueados['vcheck'] = '';
                                        }
                                        if(p_dias[5] === 'S_1'){
                                            arrg_checqueados['scheck'] = `checked="checked"`;
                                        }else{
                                            arrg_checqueados['scheck'] = '';
                                        }
                                        if(p_dias[6] === 'D_1'){
                                            arrg_checqueados['dcheck'] = `checked="checked"`;
                                        }else{
                                            arrg_checqueados['dcheck'] = '';
                                        }              
                                        html_dias+=`
                                        

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input GR_Checkd" id="checklunesd" name="checkdiavisitad[]" value='L_1' ${arrg_checqueados['lcheck']}>
                                                <label class="custom-control-label" for="checklunesd">LUNES</label>
                                            </div>

                                            <div style="${style_l}" id="ord_l">
                                                <label>Orden De Visita Lunes:</label>
                                                <input type="number" name="txtordenvisital" id="txtordenvisital" class="form-control" placeholder="Orden de visita..." value="${l}" min="0" max="90" step="1">
                                                <div class="valid-feedback">
                                                <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjsd-15">
                                                </div>
                                                <!-- <hr style="wid:100%;"> -->
                                                <hr class="separador">
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input GR_Checkd" id="checkmartesd" name="checkdiavisitad[]" value='M_1' ${arrg_checqueados['mcheck']}>
                                                <label class="custom-control-label" for="checkmartesd">MARTES</label>
                                            </div>

                                            <div style="${style_m}" id="ord_m">
                                                <label>Orden De Visita Martes:</label>
                                                <input type="number" name="txtordenvisitam" id="txtordenvisitam" class="form-control" placeholder="Orden de visita..." value="${m}" min="0" max="90" step="1">
                                                <div class="valid-feedback">
                                                <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjsd-16">
                                                </div>
                                                <hr class="separador">
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input GR_Checkd" id="checkmiercolesd" name="checkdiavisitad[]" value='I_1' ${arrg_checqueados['icheck']}>
                                                <label class="custom-control-label" for="checkmiercolesd">MI&Eacute;RCOLES</label>
                                            </div>

                                            <div style="${style_i}" id="ord_i">
                                                <label>Orden De Visita Miércoles:</label>
                                                <input type="number" name="txtordenvisitai" id="txtordenvisitai" class="form-control" placeholder="Orden de visita..." value="${i}" min="0" max="90" step="1">
                                                <div class="valid-feedback">
                                                <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjsd-17">
                                                </div>
                                                <hr class="separador">
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input GR_Checkd" id="checkjuevesd" name="checkdiavisitad[]" value='J_1' ${arrg_checqueados['jcheck']}>
                                                <label class="custom-control-label" for="checkjuevesd">JUEVES</label>
                                            </div>
                                       
                                            <div style="${style_j}" id="ord_j">
                                                <label>Orden De Visita Jueves:</label>
                                                <input type="number" name="txtordenvisitaj" id="txtordenvisitaj" class="form-control" placeholder="Orden de visita..." value="${j}" min="0" max="90" step="1">
                                                <div class="valid-feedback">
                                                <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjsd-18">
                                                </div>
                                                <hr class="separador">
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input GR_Checkd" id="checkviernesd" name="checkdiavisitad[]" value='V_1' ${arrg_checqueados['vcheck']}>
                                                <label class="custom-control-label" for="checkviernesd">VIERNES</label>
                                            </div>

                                            <div style="${style_v}" id="ord_v">
                                                <label>Orden De Visita Viernes:</label>
                                                <input type="number" name="txtordenvisitav" id="txtordenvisitav" class="form-control" placeholder="Orden de visita..." value="${v}" min="0" max="90" step="1">
                                                <div class="valid-feedback">
                                                <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjsd-19">
                                                </div>
                                                <hr class="separador">
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input GR_Checkd" id="checksabadod" name="checkdiavisitad[]" value='S_1' ${arrg_checqueados['scheck']}>
                                                <label class="custom-control-label" for="checksabadod">SABADO</label>
                                            </div>

                                            <div style="${style_s}" id="ord_s">
                                                <label>Orden De Visita Sabado:</label>
                                                <input type="number" name="txtordenvisitas" id="txtordenvisitas" class="form-control" placeholder="Orden de visita..." value="${s}" min="0" max="90" step="1">
                                                <div class="valid-feedback">
                                                <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjsd-20">
                                                </div>
                                                <hr class="separador">
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input GR_Checkd" id="checkdomingod" name="checkdiavisitad[]" value='D_1' ${arrg_checqueados['dcheck']}>
                                                <label class="custom-control-label" for="checkdomingod">DOMINGO</label>
                                            </div>

                                            <div style="${style_d}" id="ord_d">
                                                <label>Orden De Visita Domingo:</label>
                                                <input type="number" name="txtordenvisitad" id="txtordenvisitad" class="form-control" placeholder="Orden de visita..." value="${d}" min="0" max="90" step="1">
                                                <div class="valid-feedback">
                                                <strong></strong>
                                                </div>
                                                <div class="invalid-feedback" id="error-mjsd-21">
                                                </div>
                                                <hr class="separador">
                                            </div>

                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback">
                                            <strong>Por favor selecciona una opción de la lista!</strong>
                                        </div>`;
                                        $("#div_diasVisita").empty().html(html_dias);
                                        /*-----------------0000000000000000000000000000000000000000000-----------------------------------*/
                                        /* FILTRO TIPO FACTURACION */
                                        var filtro_htmlTipoFacturacion = ``;
                                        var txtcbfacturacion = '';
                                        // txtcbfacturacion = $('select[name="cbtfacturaciond"] option:selected').text();
                                        filtro_htmlTipoFacturacion += `<select class="form-control custom-select" id="cbtfacturaciond" name="cbtfacturaciond">`;
                                        _resp.TipoFacAC.forEach(function(filall,index, arrgfilall){
                                            if(_resp.clienteAC[0].Id_Tfacturacion == filall.Id_Tfacturacion){
                                                filtro_htmlTipoFacturacion+=`<option value="${filall.Id_Tfacturacion}" selected>${filall.Nombre_Tfacturacion}</option>`;
                                                txtcbfacturacion = filall.Nombre_Tfacturacion;
                                            }else{
                                                filtro_htmlTipoFacturacion+=`<option value="${filall.Id_Tfacturacion}">${filall.Nombre_Tfacturacion}</option>`;
                                            }
                                        });
                                        filtro_htmlTipoFacturacion+=`</select>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback">
                                            <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
                                        </div>`;
                                        $("#c-tfacturaciond").empty().html(filtro_htmlTipoFacturacion);
                                        if(txtcbfacturacion === 'CREDITO FISCAL'){
                                            $('#if-tfacturad').show();
                                            $('#div_dui').show();
                                            $('#div_numregistro').show();
                                            $('#div_nit').show();
                                        }else{
                                            if(arrg_Credls['pais'] == 'EL SALVADOR'){
                                                $('#if-tfacturad').hide();
                                                $('#div_dui').hide();
                                                $('#div_numregistro').hide();
                                                $('#div_nit').hide();
                                            }else if(arrg_Credls['pais'] == 'GUATEMALA'){
                                                $('#if-tfacturad').hide();
                                                $('#div_dui').hide();
                                                $('#div_numregistro').hide();
                                                $('#div_nit').hide();
                                            }else if(arrg_Credls['pais'] == 'HONDURAS'){
                                                $('#if-tfacturad').show();
                                                $('#div_dui').show();
                                                $('#div_numregistro').hide();
                                                $('#div_nit').show();
                                            }
                                        }
                                        /*-----------------0000000000000000000000000000000000000000000-----------------------------------*/
                                        /* FILTRO FRECUENCIA DE VISITA */
                                        $("#cbfrecuenciavisitad").val(frecuenciavisitaVAL);
                                        /*-----------------0000000000000000000000000000000000000000000-----------------------------------*/
                                        /* SWITCH - ESTADO DEL CLIENTE ACTUALIZADO */
                                        var Is_Cheked = document.getElementById('switch_estado').checked;
                                        if(_resp.clienteAC[0].EstadoAC == 1){
                                            if(Is_Cheked){
                                            }else{
                                                $("#switch_estado").click();
                                            }
                                        }else{
                                            if (Is_Cheked){
                                                $("#switch_estado").click();
                                            }
                                        }
                                        /*-----------------0000000000000000000000000000000000000000000-----------------------------------*/
                                        /* DEMAS ENTRADAS DE DATOS */
                                        $("#lblcodcli").text(_resp.clienteAC[0].CodigoAC);
                                        $("#txtnombre").val(_resp.clienteAC[0].NombreAC);
                                        $("#txtdireccion").val(_resp.clienteAC[0].DireccionAC);
                                        $("#txtcontacto").val(_resp.clienteAC[0].ContactoAC);
                                        $("#txttelefono").val(_resp.clienteAC[0].TelefonoAC);
                                        // $("#txtordevisita").val(_resp.clienteAC[0].OrdenVistaAC);
                                        $("#txtduid").val(_resp.clienteAC[0].DuiAC);
                                        $("#txtnumcontribuyented").val(_resp.clienteAC[0].Numero_RegistroAC);
                                        $("#txtnitd").val(_resp.clienteAC[0].NitAC);
                                        validacion_form_actu();
                                    });
                                });
                            });
                      
                    });
                }).fail(function(status, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                        _ajax_error_validacion(status,textStatus,errorThrown);
                    });
                });
            });
        });
    });

    $('#content_actualizados').on('click','.abrirmodal',function(){
        // body.addClass('block-scroll');
        $("#content-mapa").empty().html("<div id='map' style='width: 100%; height: 100%;'></div>");
        // alert(indexes_clientes[$(this).attr('id').substring(10)]);
        var idx_cliente = indexes_clientes[$(this).attr('id').substring(10)];
        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            $.ajax({
                url      : 'expediente/xcliente',
                type     : 'POST',
                dataType : 'JSON',
                data     : {'idx_cliente': idx_cliente},
                timeout  : 30777
            }).done(function(_resp){
                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                    if(_resp.rs == true){

                        var divUno_html = ``;
                        var fotoexhibidor = '';
                        
                        // if(Object.entries(map).length === 0){

                              // setTimeout(function() {
                              //   iniciar_mapa(_resp.xcliente.LatitudObservacion,_resp.xcliente.LongitudObservacion,_resp.xcliente.Latitud,_resp.xcliente.Longitud);
                              //   // map.invalidateSize();
                              //   // alert('mapa cargado correctament');
                              // }, 1000);

                            
                        // }else{
                        //     map.remove();
                        //     map = new Array();
                        // }

                        if(Object.entries(_resp.xcliente).length === 0){
                            Swal.fire({
                                title: 'Aviso!',
                                type: 'info',
                                html:'<h3>CLIENTE NO POSEE EXHIBIDORES.</h3>',
                                confirmButtonText:'Ok'
                            });
                        }else{


                            if(_resp.xcliente.FotoObservacion === 0){    
                                fotoexhibidor = '../dependencias/imagenes/icon_256.png'
                            }else{
                                fotoexhibidor = '../../Uploads/img_server/'+_resp.xcliente.FotoObservacion;
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


                          


                        }//SI NO TIENE EXHIBIDORES



                        // toggleFullScreen();
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
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
                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                    _ajax_error_validacion(status,textStatus,errorThrown);
                });
            });
        });
    });


    $('#S_filtroRuta').on('change','#filtrorutas',function(){
        Recordar_Rutas = $("#filtrorutas").val();
        CargarComplementos();
    });

    $(document).on("click",".page-TablaClteCensados",function(){
        // $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
        $page = $(this).attr('href');
        $pageind = $page.indexOf('page=');
        $page = $page.substring(($pageind+5));
        paginar_clientesAC($page);
        return false;
    });

    /*00000000000000000000000000000000000000000000000000000*/
    /*---------EVENTO CHANGE DE SELECT DEPARTAMENTO--------*/
    /*00000000000000000000000000000000000000000000000000000*/
    $(document).on("change","#cbdepartamento",function(){
        $.ajax({
            url:'mostrar-municipios/m_municipio',
            type:"POST",
            data:{codedepa:$("#cbdepartamento").val()},
            dataType: "JSON"
        }).done(function(resul) {
            var municipio_html = ``;
            var atributos_dropdown = {
                class_input:'form-control custom-select'
            };
            municipio_html += `
            <label>Municipio:</label>
            ${form_dropdown('cbmunicipio',resul.lmunicipio,'',atributos_dropdown)}`;
            $("#carga-municipio").html(municipio_html);
            V_Selec($("#cbdepartamento").val(),'cbdepartamento',2,'Departamento');
            V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');

        }).fail(function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                type: 'error',
                title: 'Ha pasado algo  malo :(<br> por favor recarga la p&aacute;gina e intenta de nuevo',
                showConfirmButton: false,
                 timer: 2900
            });
        });
    });
    $(document).on("change","#cbdepartamentod",function(){
        $.ajax({
            url:'m/municipios',
            type:"POST",
            data:{codedepa:$("#cbdepartamentod").val()},
            dataType: "JSON"
        }).done(function(resul) {
            var municipio_html = ``;
            var atributos_dropdown = {
                class_input:'form-control custom-select'
            };
            municipio_html += `${form_dropdown('cbmunicipiod',resul.lmunicipio,'',atributos_dropdown)}`;
            $("#c-municipio").html(municipio_html);
            V_Selec($("#cbdepartamentod").val(),'cbdepartamentod',2,'Departamento');
            V_Selec($("#cbmunicipiod").val(),'cbmunicipiod',3,'Municipio');

        }).fail(function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                type: 'error',
                title: 'Ha pasado algo  malo :(<br> por favor recarga la p&aacute;gina e intenta de nuevo',
                showConfirmButton: false,
                 timer: 2900
            });
        });
    });
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*---------EVENTO CHANGE DE SELECT TIPO PUNTO DE VENTA--------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    $(document).on("change","#cbtpuntoventa",function(){
        $.ajax({
            url:'mostrar-gironegocio/m_gironegocio',
            type:"POST",
            data:{codetpv:$("#cbtpuntoventa").val()},
            dataType: "JSON"
        }).done(function(resul) {
            var gironegocio_html = ``;
            var atributos_dropdown = {
                class_input:'form-control custom-select'
            };
            gironegocio_html += `
            <label>Giro de Negocio:</label>
            ${form_dropdown('cbgironegocio',resul.lgironegocio,'',atributos_dropdown)}`;
            $("#carga-gironegocio").html(gironegocio_html);
            V_Selec($("#cbtpuntoventa").val(),'cbtpuntoventa',7,'Departamento');
            V_Selec($("#cbgironegocio").val(),'cbgironegocio',8,'Municipio');

        }).fail(function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                type: 'error',
                title: 'Ha pasado algo  malo :(<br> por favor recarga la p&aacute;gina e intenta de nuevo',
                showConfirmButton: false,
                 timer: 2900
            });
        });
    });
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*---------EVENTO CHANGE DE SELECT TIPO DE FACTURACION--------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    $(document).on("change","#cbtfacturacion",function(){
        var txtcbfacturacion = '';
        txtcbfacturacion = $('select[name="cbtfacturacion"] option:selected').text();
        if(txtcbfacturacion === 'CREDITO FISCAL'){
            $('#if-tfactura').show();
            $('#div_duiu').show();
            $('#div_numregistrou').show();
            $('#div_nitu').show();
        }else{
            if(arrg_Credls['pais'] == 'EL SALVADOR'){
                $('#if-tfactura').hide();
                $('#div_duiu').hide();
                $('#div_numregistrou').hide();
                $('#div_nitu').hide();
            }else if(arrg_Credls['pais'] == 'GUATEMALA'){
                $('#if-tfactura').hide();
                $('#div_duiu').hide();
                $('#div_numregistrou').hide();
                $('#div_nitu').hide();
            }else if(arrg_Credls['pais'] == 'HONDURAS'){
                $('#if-tfactura').show();
                $('#div_duiu').show();
                $('#div_numregistrou').hide();
                $('#div_nitu').show();
            }
        }
        V_Selec($("#cbtfacturacion").val(),'cbtfacturacion',9,'Tipo de facturaci&oacute;n');
    });
    $('#c-tfacturaciond').on('change','#cbtfacturaciond',function(){
        // alert('holaaa');
        warn_on_unload = 'no salir';
        var txtcbfacturacion = '';
        txtcbfacturacion = $('select[name="cbtfacturaciond"] option:selected').text();
        V_Selec($("#cbtfacturaciond").val(),'cbtfacturaciond',14,'Tipo de facturaci&oacute;n');
        if(txtcbfacturacion === 'CREDITO FISCAL'){
            $('#if-tfacturad').show();
            $('#div_dui').show();
            $('#div_numregistro').show();
            $('#div_nit').show();
        }else{
            if(arrg_Credls['pais'] == 'EL SALVADOR'){
                $('#if-tfacturad').hide();
                $('#div_dui').hide();
                $('#div_numregistro').hide();
                $('#div_nit').hide();
            }else if(arrg_Credls['pais'] == 'GUATEMALA'){
                $('#if-tfacturad').hide();
                $('#div_dui').hide();
                $('#div_numregistro').hide();
                $('#div_nit').hide();
            }else if(arrg_Credls['pais'] == 'HONDURAS'){
                $('#if-tfacturad').show();
                $('#div_dui').show();
                $('#div_numregistro').hide();
                $('#div_nit').show();
            }
        }
    });
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*--------EVENTO CHANGE DE SELECT CONDICION DE CLIENTE--------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    $(document).on("change","#cbcondicioncli",function(){
        V_Selec($("#cbcondicioncli").val(),'cbcondicioncli',13,'Condici&oacute;n de cliente');
        V_Selec($("#cbdiascobro").val(),'cbdiascobro',14,'D&iacute;a de cobro');
        V_NumeroEnteroDecimalpo($("#txtmontocredito").val(),'txtmontocredito',15,'Monto de credito');
        var txtcondicioncli = '';
        txtcondicioncli = $('select[name="cbcondicioncli"] option:selected').text();
        if(txtcondicioncli === 'CREDITO'){
            $('#if-condcliente').show();
            // $("#cbdiascobro option[value="+ resul.diacobro +"]").attr("selected",true);
        }else{
            $('#if-condcliente').hide();
        }
    });
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*--------EVENTO CHANGE DE SELECT RUTAS DE SUPERVISOR---------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*-------------EVENTO CLICK PARA CAMBIAR DE PAGINA------------*/
    /*000000000000000000000000000000000000000000000000000000000000*/   
    $(document).on("click",".page-numbers",function(){
        $page = $(this).attr('href');
        $pageind = $page.indexOf('page=');
        $page = $page.substring(($pageind+5));
        busqueda_param_s['rutatext'] = $('select[name="cbrutas_s"] option:selected').text();
        paginar_supervisores($page,1);
        return false;
    });
    $(document).on("click",".page-numbersdo",function(){
        $page = $(this).attr('href');
        $pageind = $page.indexOf('page=');
        $page = $page.substring(($pageind+5));
        busqueda_param['rutatext'] = $('select[name="cbrutas"] option:selected').text();
        // console.log(busqueda_param);
        paginar_duplicados($page,1);
        return false;
    });
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*---------------ZOOM DUPLICADOS CLIENTES---------------------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    $("#content-tabla").on('click','.zoomd',function(){
        var zoomcod = $(this).attr("id");
        zoomcod = zoomcod.substring(6,zoomcod.length);
        var pbusqueda = '';
        var valorbusqueda = '';
        var rutacod = '';

        pbusqueda = $("#cbopcionbq").val();
        rutacod = $("#rutaid-"+zoomcod).val();

        if(pbusqueda ==  1){
            valorbusqueda = $("#td-c"+zoomcod).text();
        }else if(pbusqueda == 2){
            valorbusqueda = $("#td-t"+zoomcod).text();
        }else{
            valorbusqueda = $("#td-n"+zoomcod).text();
        }
        // console.log('VALOR BUSQUEDA => '+valorbusqueda);
        // console.log('RUTA BUSQUEDA => '+rutacod);
        paginar_zoom_duplicados(1,pbusqueda,rutacod,valorbusqueda);
    });
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*----------------ESTADO ACORDEON DUPLICADOS------------------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    $("#content-tabla").on('click','.sub-aco',function(){
        var duplicod = $(this).attr("id");
        duplicod = duplicod.substring(16,duplicod.length);
        var subco = $(this).attr("id");
        subco = subco.substring(0,1);
        
     // $("#img-carga").show("fast",function(){
        // $("#"+subco+"btn-duplicadosC"+duplicod).attr("disabled", true);
        $('.collapse-sub').collapse('hide');
        // console.log('------------------------------------------------');
        // console.log('CODIGO DE TR => '+ duplicod);
        // console.log('CODIGO DE ACORDEON => '+ subco);
        // console.log('------------------------------------------------');


        if(toggle_aco_sub_tr !=7777777 && toggle_aco_sub_subaco !=7777777){
            $("#"+toggle_aco_sub_subaco+"span-duplicados"+toggle_aco_sub_tr).attr("class", "fa fa-plus-square");
            $("#"+toggle_aco_sub_subaco+"btn-duplicadosC"+toggle_aco_sub_tr).attr("class", "btn btn-primary sub-aco");
            if(toggle_aco_sub_tr == duplicod && toggle_aco_sub_subaco == subco){
                // console.log('Esto es igual');
                // $("#img-carga").hide("fast",function(){
                //     $("#btn-duplicado"+duplicod).attr("disabled", false);
                // });
            }else{
                arrg_plus_minus_sub[toggle_aco_sub_tr][toggle_aco_sub_subaco] = 0;
            }

        }else{
            // console.log('Presionaste Eco de la SUERTE');
        }

        if(arrg_plus_minus_sub[duplicod][subco] == 0){
            // console.log('ACORDEON ESTA EN CRUZ');
            arrg_plus_minus_sub[duplicod][subco] = 1;
            // 0btn-duplicadosC${i}
            $("#"+subco+"span-duplicados"+duplicod).attr("class", "fa fa-minus-square");
            $("#"+subco+"btn-duplicadosC"+duplicod).attr("class", "btn btn-danger sub-aco");
            toggle_aco_sub_tr = duplicod;
            toggle_aco_sub_subaco = subco; 
        }else{
            // console.log('ACORDEON ESTA EN GUION');
            arrg_plus_minus_sub[duplicod][subco] = 0;
            $("#"+subco+"span-duplicados"+duplicod).attr("class", "fa fa-plus-square");
            $("#"+subco+"btn-duplicadosC"+duplicod).attr("class", "btn btn-primary sub-aco");
        }
        // $("#img-carga").hide("fast",function(){
        //     // $("#"+subco+"btn-duplicadosC"+duplicod).attr("disabled", false);
        // });
     // });

    });

    $("#content-tabla").on('click','.clickable',function(){
        
        var duplicod = $(this).attr("id");
        duplicod = duplicod.substring(13,duplicod.length);
        $("#btn-duplicado"+duplicod).attr("disabled", true);
        var countacod = 0;
        countacod = duplicod -1;
        if(countacod < 0){
            countacod = 7777777;
        }else{

        }
        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            $('html').stop(true,true).animate({
                scrollTop: $('#anclar-capitan'+countacod).offset().top
            },377,function(){
            // console.log('termino de anclar');


            $('.collapse').collapse('hide');
            $("#0span-duplicados"+duplicod).attr("class", "fa fa-plus-square");
            $("#0btn-duplicadosC"+duplicod).attr("class", "btn btn-primary sub-aco");
            $("#1span-duplicados"+duplicod).attr("class", "fa fa-plus-square");
            $("#1btn-duplicadosC"+duplicod).attr("class", "btn btn-primary sub-aco");
            $("#2span-duplicados"+duplicod).attr("class", "fa fa-plus-square");
            $("#2btn-duplicadosC"+duplicod).attr("class", "btn btn-primary sub-aco");
            arrg_plus_minus_sub[duplicod][0] = 0;
            arrg_plus_minus_sub[duplicod][1] = 0;
            arrg_plus_minus_sub[duplicod][2] = 0;

            if(toggle_aco !=7777777){
                $("#span-duplicado"+toggle_aco).attr("class", "fa fa-plus-square");
                $("#btn-duplicado"+toggle_aco).attr("class", "btn btn-primary clickable");
                $("#ubicacioo-"+toggle_aco).attr("style", "display:;");
                $("#cbresolucion-"+toggle_aco).attr("style", "display:;");
                $("#editar-"+toggle_aco).attr("style", "display:;");
                $("#aco-contacto"+toggle_aco).empty();
                $("#aco-telefono"+toggle_aco).empty();
                $("#aco-nombre"+toggle_aco).empty();
                if(duplicod == toggle_aco){
                    // console.log('EL MISMO TR');
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                        $("#btn-duplicado"+duplicod).attr("disabled", false);
                    });
                }else{
                    arrg_plus_minus[toggle_aco] = 0;
                }
                // console.log('Cierre de ACO TR'+ toggle_aco+ 'Estado Actual'+arrg_plus_minus[toggle_aco]);
                // console.log('APLICAR CRUZ A TR '+toggle_aco);
            }else{
                // console.log('Presionaste Eco de la SUERTE');

            }

            if(arrg_plus_minus[duplicod] == 0){

                var datas = '';
                datas = $("#form-reporte").serializeArray();
                datas.push({name: 'page', value: 1});
                datas.push({name: 'codecli', value: mis_codigos[duplicod]});
                datas.push({name: 'cbrutas', value: mis_rutas[duplicod]});
                datas.push({name: 'paramc', value: $("#td-c"+duplicod).text()});
                datas.push({name: 'paramt', value: $("#td-t"+duplicod).text()});
                datas.push({name: 'paramn', value: $("#td-n"+duplicod).text()});

                $.ajax({
                    url:'lista-duplicados-s/verificacion_duplicados',
                    type:"POST",
                    data:datas,
                    dataType: "JSON",
                    }).done(function(respuesta) {
                        var detalles_html=``;
                        if(respuesta.rs == false){
                            $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                                var divhtml = mensaje_alerta({cla:respuesta.cla,info:respuesta.errores},detalles_html);
                                $("#mjs_result").empty();
                                $("#mjs_result").html(divhtml);
                            });
                        }else{
                            var xcontacto  = ``;
                            var xtelefono = ``;
                            var xnombre = ``;
                            var conta_aco = 0;
                            /*-----------------------ArrayList------total----pagiancion*/
                            if(respuesta.totalC>0){
                                xcontacto =  construir_duplicados(respuesta.ltclientesC,respuesta.totalC,'',1,duplicod,'c');
                                $("#aco-contacto"+duplicod).html(xcontacto);
                                $("#totaldC"+duplicod).html(respuesta.totalC);
                                conta_aco++;
                            }else{$("#estado-cardC"+duplicod).attr("style","display:none;");}
                            if(respuesta.totalT>0){
                                xtelefono =  construir_duplicados(respuesta.ltclientesT,respuesta.totalT,'',2,duplicod,'t');
                                $("#aco-telefono"+duplicod).html(xtelefono);
                                $("#totaldT"+duplicod).html(respuesta.totalT);
                                conta_aco++;
                            }else{$("#estado-cardT"+duplicod).attr("style","display:none;");}
                            if(respuesta.totalN>0){
                                xnombre =  construir_duplicados(respuesta.ltclientesN,respuesta.totalN,'',3,duplicod,'n');
                                $("#aco-nombre"+duplicod).html(xnombre);
                                $("#totaldN"+duplicod).html(respuesta.totalN);
                                conta_aco++;
                            }else{$("#estado-cardN"+duplicod).attr("style","display:none;");}

                            if(conta_aco > 0){
                        
                                // console.log('Esto es igual');
                                
                                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                                    $("#btn-mduplicado"+duplicod).click();
                                    $("#btn-duplicado"+duplicod).attr("disabled", false);
                                    $("#ubicacioo-"+duplicod).attr("style", "display:none;");
                                    $("#cbresolucion-"+duplicod).attr("style", "display:none;");
                                    $("#editar-"+duplicod).attr("style", "display:none;");
                                    $("#span-duplicado"+duplicod).attr("class", "fa fa-minus-square");
                                    $("#btn-duplicado"+duplicod).attr("class", "btn btn-danger clickable");
                                    arrg_plus_minus[duplicod] = 1;
                                    toggle_aco = duplicod;
                                });
                                // arrg_plus_minus[toggle_aco] = 0;
                            }else{

                                arrg_plus_minus[duplicod] = 0;
                                $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {
                                    $("#btn-duplicado"+duplicod).attr("disabled", false);

                                        $("#span-duplicado"+duplicod).attr("class", "fa fa-plus-square");
                                        $("#btn-duplicado"+duplicod).attr("class", "btn btn-primary clickable");
                                        const Toast = Swal.mixin({
                                          toast: true,
                                          position: 'bottom-end',
                                          showConfirmButton: false,
                                          // background: '#B2CCE9',
                                          timer: 1500,
                                          onOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                          }
                                        })

                                        Toast.fire({
                                          type: 'info',
                                          title: 'No se encontraron coincidencias'
                                        });
                                    $("#ubicacioo-"+toggle_aco).attr("style", "display:;");
                                    $("#cbresolucion-"+toggle_aco).attr("style", "display:;");
                                    $("#editar-"+toggle_aco).attr("style", "display:;");  
                                });  
                            }
                        }
                }).fail(function() {
                    $.when( $(".carga-class").stop(true,true).hide(20) ).done(function( x ) {});
                     // retorna_inicio();
                });
            }else{
                // console.log('CIERRA [ACO ACTUAL TR'+duplicod+'] '+ arrg_plus_minus[duplicod]);
                $("#span-duplicado"+duplicod).attr("class", "fa fa-plus-square");
                $("#btn-duplicado"+duplicod).attr("class", "btn btn-primary clickable");
                arrg_plus_minus[duplicod] = 0;
                // arrg_plus_minus[toggle_aco] = 0;
                $("#btn-mduplicado"+duplicod).click();
                toggle_aco = 7777777;
                arrg_plus_minus[toggle_aco] = 0;
            }
            });

        });
        // console.log($("#contenedor"+duplicod).scrollTop());
    
        // console.log("-----------------------------------------");
    });
    /*-----------------------------------------------------------*/
    /*--------FUNCION PARA OBTENER LA UBICACION VISTA MAPA-------*/
    /*-----------------------------------------------------------*/
    $(document).on("click","#btn-salir-mapa",function(){
        // $("#map").hide("fast",function(){

        $.when( $("#div_filtrorutas").stop(true,true).show(20) ).done(function( x ) {
            $("#map").attr("style","height: 90%;width: 90%;position: absolute;display: none;margin-left:auto;margin-right:auto;left:0;right:0;");
            $.when( $("#content-tabla").stop(true,true).show(20) ).done(function( x ) {
                $.when( $("#content_actualizados").stop(true,true).show(20) ).done(function( x ) {
                    map.remove();
                    $('html').animate({scrollTop : scrolly}, 500);
                });
            });
        });

        // });
    });
    
    /* 0000000000000_____--- VALIDACIONES KEYUP ---_________000000000000000000000000*/
    /* VALIDACION NOMBRE DEL CLIENTE*/
    $('#formularios').on('keyup','#nomestablecimiento',function(){
        V_Text_LetraNumero($("#nomestablecimiento").val(),'nomestablecimiento',0,'Nombre del cliente',1);
        warn_on_unload = 'no salir';
    });
    $('#content_cliAc').on('keyup','#txtnombre',function(){
        V_Text_LetraNumero($("#txtnombre").val(),'txtnombre',0,'Nombre del cliente',2);
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION DIRECCION DEL CLIENTE*/
    $('#formularios').on('keyup','#direccion',function(){
        V_Text_LetraNumero_Direccion($("#direccion").val(),'direccion',1,'Direccion',1);
        warn_on_unload = 'no salir';
    });
    $('#content_cliAc').on('keyup','#txtdireccion',function(){
        V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direccion',2);
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION TELEFONO*/
    $('#formularios').on('keyup','#telefono',function(){
        var CantidTelefonor = 0;var CantidTelefonot = 0;
        CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguion($("#telefono").val(),'telefono',4,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot,1);
        warn_on_unload = 'no salir';
    });
    $('#content_cliAc').on('keyup','#txttelefono',function(){
        var CantidTelefonor = 0;var CantidTelefonot = 0;
        CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguion($("#txttelefono").val(),'txttelefono',5,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot,2);
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION CONTACTO*/
    $('#formularios').on('keyup','#contacto',function(){
        V_Text_ConEspacio($("#contacto").val(),'contacto',5,'Contacto',1);
        warn_on_unload = 'no salir';
    });
    $('#content_cliAc').on('keyup','#txtcontacto',function(){
        V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',4,'Contacto',2);
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION DEL DIA DE VISITA*/
    $('#formularios').on('click','.GR_Check',function(){
        V_checks(17,'D&iacute;a de visita');
        warn_on_unload = 'no salir';

        var dia_se ='',estado_se=null;
        dia_se = this.value,estado_se=this.checked;

        if(dia_se == 'L_1' && estado_se == true){
            $("#ord_ln").show();
            // $("#txtordenvisital").val("");
            V_NumeroEntero2digitos($("#txtordenvisitaln").val(),'txtordenvisitaln',27,'Orden de visita Lunes',1);
        } else if (dia_se == 'L_1' && estado_se == false) {
            $("#ord_ln").hide();
        }

        if(dia_se == 'M_1' && estado_se == true){
            $("#ord_mn").show();
            // $("#txtordenvisitam").val("");
            V_NumeroEntero2digitos($("#txtordenvisitamn").val(),'txtordenvisitamn',28,'Orden de visita Martes',1);
        } else if (dia_se == 'M_1' && estado_se == false) {
            $("#ord_mn").hide();
        }

        if(dia_se == 'I_1' && estado_se == true){
            $("#ord_in").show();
            // $("#txtordenvisitai").val("");
            V_NumeroEntero2digitos($("#txtordenvisitain").val(),'txtordenvisitain',29,'Orden de visita Miércoles',1);
        } else if (dia_se == 'I_1' && estado_se == false) {
            $("#ord_in").hide();
        }

        if(dia_se == 'J_1' && estado_se == true){
            $("#ord_jn").show();
            // $("#txtordenvisitaj").val("");
            V_NumeroEntero2digitos($("#txtordenvisitajn").val(),'txtordenvisitajn',30,'Orden de visita Jueves',1);
        } else if (dia_se == 'J_1' && estado_se == false) {
            $("#ord_jn").hide();
        }

        if(dia_se == 'V_1' && estado_se == true){
            $("#ord_vn").show();
            // $("#txtordenvisitav").val("");
            V_NumeroEntero2digitos($("#txtordenvisitavn").val(),'txtordenvisitavn',31,'Orden de visita Viernes',1);
        } else if (dia_se == 'V_1' && estado_se == false) {
            $("#ord_vn").hide();
        }

        if(dia_se == 'S_1' && estado_se == true){
            $("#ord_sn").show();
            // $("#txtordenvisitas").val("");
            V_NumeroEntero2digitos($("#txtordenvisitasn").val(),'txtordenvisitasn',32,'Orden de visita Sábado',1);
        } else if (dia_se == 'S_1' && estado_se == false) {
            $("#ord_sn").hide();
        }

        if(dia_se == 'D_1' && estado_se == true){
            $("#ord_dn").show();
            // $("#txtordenvisitad").val("");
            V_NumeroEntero2digitos($("#txtordenvisitadn").val(),'txtordenvisitadn',33,'Orden de visita Domingo',1);
        } else if (dia_se == 'D_1' && estado_se == false) {
            $("#ord_dn").hide();
        }

    });
    $('#div_diasVisita').on('click','.GR_Checkd',function(){
        V_checksd(6,'D&iacute;a de visita');
        warn_on_unload = 'no salir';

        var dia_se ='',estado_se=null;
        dia_se = this.value,estado_se=this.checked;

        if(dia_se == 'L_1' && estado_se == true){
            $("#ord_l").show();
            // $("#txtordenvisital").val("");
            V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',15,'Orden de visita Lunes',2);
        } else if (dia_se == 'L_1' && estado_se == false) {
            $("#ord_l").hide();
        }

        if(dia_se == 'M_1' && estado_se == true){
            $("#ord_m").show();
            // $("#txtordenvisitam").val("");
            V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',16,'Orden de visita Martes',2);
        } else if (dia_se == 'M_1' && estado_se == false) {
            $("#ord_m").hide();
        }

        if(dia_se == 'I_1' && estado_se == true){
            $("#ord_i").show();
            // $("#txtordenvisitai").val("");
            V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',17,'Orden de visita Miércoles',2);
        } else if (dia_se == 'I_1' && estado_se == false) {
            $("#ord_i").hide();
        }

        if(dia_se == 'J_1' && estado_se == true){
            $("#ord_j").show();
            // $("#txtordenvisitaj").val("");
            V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',18,'Orden de visita Jueves',2);
        } else if (dia_se == 'J_1' && estado_se == false) {
            $("#ord_j").hide();
        }

        if(dia_se == 'V_1' && estado_se == true){
            $("#ord_v").show();
            // $("#txtordenvisitav").val("");
            V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',19,'Orden de visita Viernes',2);
        } else if (dia_se == 'V_1' && estado_se == false) {
            $("#ord_v").hide();
        }

        if(dia_se == 'S_1' && estado_se == true){
            $("#ord_s").show();
            // $("#txtordenvisitas").val("");
            V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',20,'Orden de visita Sábado',2);
        } else if (dia_se == 'S_1' && estado_se == false) {
            $("#ord_s").hide();
        }

        if(dia_se == 'D_1' && estado_se == true){
            $("#ord_d").show();
            // $("#txtordenvisitad").val("");
            V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',21,'Orden de visita Domingo',2);
        } else if (dia_se == 'D_1' && estado_se == false) {
            $("#ord_d").hide();
        }
    });
    $('#div_diasVisita').on('keyup','#txtordenvisital',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',15,'Orden de visita Lunes',2);
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitam',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',16,'Orden de visita Martes',2);
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitai',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',17,'Orden de visita Miércoles',2);
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitaj',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',18,'Orden de visita Jueves',2);
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitav',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',19,'Orden de visita Viernes',2);
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitas',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',20,'Orden de visita Sábado',2);
    });
    $('#div_diasVisita').on('keyup','#txtordenvisitad',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',21,'Orden de visita Domingo',2);
    });


    $('#formularios').on('keyup','#txtordenvisitaln',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitaln").val(),'txtordenvisitaln',27,'Orden de visita Lunes',1);
    });
    $('#formularios').on('keyup','#txtordenvisitamn',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitamn").val(),'txtordenvisitamn',28,'Orden de visita Martes',1);
    });
    $('#formularios').on('keyup','#txtordenvisitain',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitain").val(),'txtordenvisitain',29,'Orden de visita Miércoles',1);
    });
    $('#formularios').on('keyup','#txtordenvisitajn',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitajn").val(),'txtordenvisitajn',30,'Orden de visita Jueves',1);
    });
    $('#formularios').on('keyup','#txtordenvisitavn',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitavn").val(),'txtordenvisitavn',31,'Orden de visita Viernes',1);
    });
    $('#formularios').on('keyup','#txtordenvisitasn',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitasn").val(),'txtordenvisitasn',32,'Orden de visita Sábado',1);
    });
    $('#formularios').on('keyup','#txtordenvisitadn',function(){
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitadn").val(),'txtordenvisitadn',33,'Orden de visita Domingo',1);
    });

    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION MUNICIPIOS*/
    $(document).on("change","#cbmunicipio",function(){
        V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
        warn_on_unload = 'no salir';
    });
    $(document).on("change","#cbmunicipiod",function(){
        V_Selec($("#cbmunicipiod").val(),'cbmunicipiod',3,'Municipio');
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION FRECUENCIA DE VISITA*/
    $(document).on("change","#cbfrecuenciavisita",function(){
        V_Selec($("#cbfrecuenciavisita").val(),'cbfrecuenciavisita',16,'Frecuencia de visita');
        warn_on_unload = 'no salir';
    });
    $(document).on("change","#cbfrecuenciavisitad",function(){
        V_Selec($("#cbfrecuenciavisitad").val(),'cbfrecuenciavisitad',10,'Frecuencia de visita');
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION ORDEN DE VISITA*/
    // $('#formularios').on('keyup','#ordenvisita',function(){
    //     V_NumeroEntero2digitos($("#ordenvisita").val(),'ordenvisita',18,'Orden de visita',1);
    //     warn_on_unload = 'no salir';
    // });
    // $('#content_cliAc').on('keyup','#txtordevisita',function(){
    //     V_NumeroEntero2digitos($("#txtordevisita").val(),'txtordevisita',11,'Orden de visita',2);
    //       warn_on_unload = 'no salir';
    // });
    $(document).on("change","#cbgironegocio",function(){
        V_Selec($("#cbgironegocio").val(),'cbgironegocio',8,'Giro de negocio');
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION NUMERO DE DUI*/
    $('#formularios').on('keyup','#txtdui',function(){
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguion($("#txtdui").val(),'txtdui',10,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt,1);
        warn_on_unload = 'no salir';
    });
    $('#content_cliAc').on('keyup','#txtduid',function(){
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguion($("#txtduid").val(),'txtduid',7,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt,2);
        warn_on_unload = 'no salir';
    });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION NUMERO DE CONTRIBUYENTE*/
    $('#formularios').on('keyup','#txtnumcontribuyente',function(){
      V_NumeroEntero($("#txtnumcontribuyente").val(),'txtnumcontribuyente',11,'N&uacute;mero de contribuyente',1);
        warn_on_unload = 'no salir';
    });
    $('#content_cliAc').on('keyup','#txtnumcontribuyented',function(){
        V_NumeroEntero($("#txtnumcontribuyented").val(),'txtnumcontribuyented',8,'N&uacute;mero de contribuyente',2);
        warn_on_unload = 'no salir';
      });
    /*--------__________---------------__________-----_________----------____________-------------------*/
    /* VALIDACION NUMERO DE NIT*/
    $('#formularios').on('keyup','#txtnit',function(){
        var CantidNumNITr = 0;var CantidNumNITt = 0;
        CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
        var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
        V_numeconMaskguion($("#txtnit").val(),'txtnit',12,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt,1);
        warn_on_unload = 'no salir';
    });
    $('#content_cliAc').on('keyup','#txtnitd',function(){
        var CantidNumNITr = 0;var CantidNumNITt = 0;
        CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
        var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
        V_numeconMaskguion($("#txtnitd").val(),'txtnitd',9,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt,2);
        warn_on_unload = 'no salir';
    });
    $('#formularios').on('keyup','#txtmontocredito',function(){
        V_NumeroEnteroDecimalpo($("#txtmontocredito").val(),'txtmontocredito',17,'Monto de credito');
        warn_on_unload = 'no salir';
    });
    $(document).on("change","#cbdiascobro",function(){
        V_Selec($("#cbdiascobro").val(),'cbdiascobro',16,'D&iacute;a de cobro');
        warn_on_unload = 'no salir';
    });
    $('#formularios').on('keyup','#txtmcomprab',function(){
        V_NumeroEnteroDecimalpoinput($("#txtmcomprab").val(),'txtmcomprab',23,'Bocadeli');
        warn_on_unload = 'no salir';
    });
    $('#formularios').on('keyup','#txtmcomprad',function(){
        V_NumeroEnteroDecimalpoinput($("#txtmcomprad").val(),'txtmcomprad',24,'Diana');
        warn_on_unload = 'no salir';
    });
    $('#formularios').on('keyup','#txtmcompray',function(){
        V_NumeroEnteroDecimalpoinput($("#txtmcompray").val(),'txtmcompray',25,'Yummies');
        warn_on_unload = 'no salir';
    });
    $('#formularios').on('keyup','#txtmcompraf',function(){
        V_NumeroEnteroDecimalpoinput($("#txtmcompraf").val(),'txtmcompraf',26,'Frito lay');
        warn_on_unload = 'no salir';
    });
    /**************************************************/
    /*--------------EVENTO CHANGE FOTOS---------------*/
    /**************************************************/
    $(document).on('change','#filefnegocio',function(){
        // console.log('SELECCIONO IMAGEN');
        var cuentaok = 0;
        cuentaok = inputfilevaliok('filefnegocio',6,'foto de la fachada del negocio');
        if(cuentaok>0){
        }else{
            // alert('imagen no deseada');
            $("#img-cliente").val("");
            var canvas = document.getElementById('canvas-fachada');
            var ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        warn_on_unload = 'no salir';
        $("#contenedor-img").attr("style","display:none;");
        $("#canvas-fachada").attr("style","border: 1px solid black;width:200px;height:200px;display:;");
    });
    $(document).on('change','#fileexhibidor',function(){
        // console.log('SELECCIONO IMAGEN');
        var cuentaok = 0;
        cuentaok = inputfilevaliok('fileexhibidor',22,'foto del exhibidor principal');
        if(cuentaok>0){
        }else{
            var canvasex = document.getElementById('canvasd');
            var ctxx = canvasex.getContext("2d");
            ctxx.clearRect(0, 0, canvasex.width, canvasex.height);
            $("#img_exhibid").val("");
        }
        warn_on_unload = 'no salir';
        $("#contenedor-foto-exh").attr("style","display:none;");
        $("#canvasd").attr("style","border: 1px solid black;width:200px;height:200px;display:;");
    });
    /********************************************************/
    /*-----------------RESOLUCION DE CLIENTE----------------*/
    /********************************************************/
    $('#content-tabla').on('change','.resolucion',function(){

    });

    $(document).on("click", "#AddFiles", function() {
        if(blockF==0){
            B1=1000;
            B2=0;
            B3=0;
            $(".CTable_Gr").fadeIn("slow");
            $("#showData").empty().append(DataTB);
            $("#Modaldetalles").modal();
        }
    });
    $(document).on("click", "#AddFilesdos", function() {
        if(blockF==0){
            B2=1000;
            B1=0;
            B3=0;
            $(".CTable_Gr").fadeIn("slow");
            $("#showData").empty().append(DataTB);
            $("#Modaldetalles").modal();
        }
    });
    $(document).on("click", "#AddFilestres", function() {
        if(blockF==0){
            B3=1000;
            B1=0;
            B2=0;
            $(".CTable_Gr").fadeIn("slow");
            $("#showData").empty().append(DataTB);
            $("#Modaldetalles").modal();
        }
    });
    $(document).on("click", "#X", function() {
        if(blockF==0){
            $(".CTable_Gr").fadeOut("slow",function(){
                $("#showData").empty();
            });
        }
    });

    $(document).on("click", ".TrSelect", function() {
        if(blockF==0){
            blockF=1000;    
            $(this).addClass("SeletedTR");

            if(B1==1000){
                $("#cbexhibidoru").val($("#"+$(this).attr("id")+" .Cme").text());
                $("#txtEName").val($("#"+$(this).attr("id")+" .Nme").text());
                V_Selec($("#txtEName").val(),'txtEName',19,'Exhibidor uno');
            }else if(B2==1000){
                $("#cbexhibidord").val($("#"+$(this).attr("id")+" .Cme").text());
                $("#txtENamedos").val($("#"+$(this).attr("id")+" .Nme").text());
                V_Selec($("#txtENamedos").val(),'txtENamedos',20,'Exhibidor dos');
            }else if(B3==1000){
                $("#cbexhibidort").val($("#"+$(this).attr("id")+" .Cme").text());
                $("#txtENametres").val($("#"+$(this).attr("id")+" .Nme").text());
                V_Selec($("#txtENametres").val(),'txtENametres',21,'Exhibidor tres');
            }
            // setTimeout(getInfo, 500);
            getInfo();
        }
    });
});
/*_______________________________________________*/
/*<<<<<<<<<<<<FIN EVENTO READY JQUERY>>>>>>>>>>>>*/
/*_______________________________________________*/
/*0000000000000000000000000000000000000000000000000000000000*/
/*------------VALIDACION DE FORMULARIO MODIFICAR------------*/
/*0000000000000000000000000000000000000000000000000000000000*/
function validacion_form(){
    var contarok = 0;
    arrg_vali_result = [];
    contarok += V_Text_LetraNumero($("#nomestablecimiento").val(),'nomestablecimiento',0,'Nombre del cliente',1);
    contarok += V_Text_LetraNumero_Direccion($("#direccion").val(),'direccion',1,'Direcci&oacute;n',1);
    contarok +=V_Selec($("#cbdepartamento").val(),'cbdepartamento',2,'Departamento');
    contarok +=V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
    var CantidTelefonor = 0;var CantidTelefonot = 0;
    CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
    contarok +=V_numeconMaskguion($("#telefono").val(),'telefono',4,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot,1);
    contarok += V_Text_ConEspacio($("#contacto").val(),'contacto',5,'Contacto');
    contarok +=inputfilevalidacion('filefnegocio',6,'foto de la fachada del negocio','img-cliente');
    contarok +=V_Selec($("#cbtpuntoventa").val(),'cbtpuntoventa',7,'Tipo punto de venta');
    contarok +=V_Selec($("#cbgironegocio").val(),'cbgironegocio',8,'Giro de negocio');
    contarok +=V_Selec($("#cbtfacturacion").val(),'cbtfacturacion',9,'Tipo de facturaci&oacute;n');
    var txtcbfacturacion = '';
    txtcbfacturacion = $('select[name="cbtfacturacion"] option:selected').text();
    if(txtcbfacturacion === 'CREDITO FISCAL'){

        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        contarok +=V_numeconMaskguion($("#txtdui").val(),'txtdui',10,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt,1);
        contarok +=V_NumeroEntero($("#txtnumcontribuyente").val(),'txtnumcontribuyente',11,'N&uacute;mero de contribuyente',1);
        var CantidNumNITr = 0;var CantidNumNITt = 0;
        CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
        var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
        contarok +=V_numeconMaskguion($("#txtnit").val(),'txtnit',12,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt,1);

    }else{

        if(arrg_Credls['pais'] == 'EL SALVADOR'){
            arrg_vali_result[10] = '';
            arrg_vali_result[11] = '';
            arrg_vali_result[12] = '';
            contarok +=3;
        }else if(arrg_Credls['pais'] == 'GUATEMALA'){
            arrg_vali_result[10] = '';
            arrg_vali_result[11] = '';
            arrg_vali_result[12] = '';
            contarok +=3;
        }else if(arrg_Credls['pais'] == 'HONDURAS'){
            arrg_vali_result[11] = '';
            var CantidNumIPr = 0;var CantidNumIPt = 0;
            CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
            var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
            contarok +=V_numeconMaskguion($("#txtdui").val(),'txtdui',10,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt,1);
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguion($("#txtnit").val(),'txtnit',12,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt,1);
            contarok +=1;
        }

    }
    contarok +=V_Selec($("#cbcondicioncli").val(),'cbcondicioncli',13,'Condici&oacute;n de cliente');
    var txtcondicioncli = '';
    txtcondicioncli = $('select[name="cbcondicioncli"] option:selected').text();
    if(txtcondicioncli === 'CREDITO'){
        contarok +=V_Selec($("#cbdiascobro").val(),'cbdiascobro',14,'D&iacute;a de cobro');
        contarok +=V_NumeroEnteroDecimalpo($("#txtmontocredito").val(),'txtmontocredito',15,'Monto de credito');
    }else{
        arrg_vali_result[14] = '';
        arrg_vali_result[15] = '';
        contarok +=2;
    }
    contarok +=V_Selec($("#cbfrecuenciavisita").val(),'cbfrecuenciavisita',16,'Frecuencia de visita');
    contarok +=V_checks(17,'D&iacute;a de visita');
    // contarok +=V_NumeroEntero2digitos($("#ordenvisita").val(),'ordenvisita',18,'Orden de visita',1);
    var txtcantidadex = '';
    txtcantidadex = $('#cbcantidadex').val();
    // console.log('CANTIDAD EXHIBIDORES '+$('#cbcantidadex').val());
    if(txtcantidadex > 0 ){
        if(txtcantidadex == 1 ){
            contarok +=V_Selec($("#txtEName").val(),'txtEName',19,'Exhibidor uno');
            contarok +=2;
            arrg_vali_result[20] = '';
            arrg_vali_result[21] = '';
        }else if(txtcantidadex == 2 ){
            contarok +=V_Selec($("#txtEName").val(),'txtEName',19,'Exhibidor uno');
            contarok +=V_Selec($("#txtENamedos").val(),'txtENamedos',20,'Exhibidor dos');
            contarok +=1;
            arrg_vali_result[21] = '';
        }else if(txtcantidadex == 3){
            contarok +=V_Selec($("#txtEName").val(),'txtEName',19,'Exhibidor uno');
            contarok +=V_Selec($("#txtENamedos").val(),'txtENamedos',20,'Exhibidor dos');
            contarok +=V_Selec($("#txtENametres").val(),'txtENametres',21,'Exhibidor tres');
        }
        contarok +=inputfilevalidacion('fileexhibidor',22,'Foto exhibidor principal','img_exhibid');
    }else{
        arrg_vali_result[19] = '';
        arrg_vali_result[20] = '';
        arrg_vali_result[21] = '';
        arrg_vali_result[22] = '';
        contarok +=4;
    }
    contarok +=V_NumeroEnteroDecimalpoinput($("#txtmcomprab").val(),'txtmcomprab',23,'Bocadeli');
    contarok +=V_NumeroEnteroDecimalpoinput($("#txtmcomprad").val(),'txtmcomprad',24,'Diana');
    contarok +=V_NumeroEnteroDecimalpoinput($("#txtmcompray").val(),'txtmcompray',25,'Yummies');
    contarok +=V_NumeroEnteroDecimalpoinput($("#txtmcompraf").val(),'txtmcompraf',26,'Frito lay');


    if(document.getElementById('checklunes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitaln").val(),'txtordenvisitaln',27,'Orden de visita Lunes',1);
    }else{
        contarok +=1;
        arrg_vali_result[27] = '';
    }

    if(document.getElementById('checkmartes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitamn").val(),'txtordenvisitamn',28,'Orden de visita Martes',1);
    }else{
        contarok +=1;
        arrg_vali_result[28] = '';
    }

    if(document.getElementById('checkmiercoles').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitain").val(),'txtordenvisitain',29,'Orden de visita Miércoles',1);
    }else{
        contarok +=1;
        arrg_vali_result[29] = '';
    }


    if(document.getElementById('checkjueves').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitajn").val(),'txtordenvisitajn',30,'Orden de visita Jueves',1);
    }else{
        contarok +=1;
        arrg_vali_result[30] = '';
    }


    if(document.getElementById('checkviernes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitavn").val(),'txtordenvisitavn',31,'Orden de visita Viernes',1);
    }else{
        contarok +=1;
        arrg_vali_result[31] = '';
    }


    if(document.getElementById('checksabado').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitasn").val(),'txtordenvisitasn',32,'Orden de visita Sábado',1);
    }else{
        contarok +=1;
        arrg_vali_result[32] = '';
    }


    if(document.getElementById('checkdomingo').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitadn").val(),'txtordenvisitadn',33,'Orden de visita Domingo',1);
    }else{
        contarok +=1;
        arrg_vali_result[33] = '';
    }



    return contarok;
}
/*00000000000000000000000000000000000000000000000000000000000*/
/*------------FUNCIONES PARA BUSCADOR EXHIBIDORES------------*/
/*00000000000000000000000000000000000000000000000000000000000*/
function getInfo(){
    $(".CTable_Gr").fadeOut("slow",function(){
        $("#showData").empty();
        $("#txtBusqueda").val("");
        blockF=0;
        B1=0;
        B2=0;
        B3=0;
        $("#Modaldetalles").modal('hide');
    });
}
function grBusqueda(){
    const TABLAD = document.getElementById('DgrTable');
    const S_Text = document.getElementById('txtBusqueda').value.toLowerCase();
    let total = 0;

    for (let i = 1; i < TABLAD.rows.length; i++) {
        if (TABLAD.rows[i].classList.contains("noSearch")) {
            continue;
        }
        let encontrado = false;
        const coR = TABLAD.rows[i].getElementsByTagName('td');
        for (let j = 0; j < coR.length && !encontrado; j++) {
            const compara = coR[j].innerHTML.toLowerCase();
            if (S_Text.length == 0 || compara.indexOf(S_Text) > -1) {
                encontrado = true;
                total++;
            }
        }
        if (encontrado) {
            TABLAD.rows[i].style.display = '';
        } else {
            TABLAD.rows[i].style.display = 'none';
        }
    }
    const ultimaTR=TABLAD.rows[TABLAD.rows.length-1];
    const td=ultimaTR.querySelector("td");
    ultimaTR.classList.remove("hide", "red");
    if (S_Text == "") {
        ultimaTR.classList.add("hide");
    } else if (total) {
        td.innerHTML="Se ha encontrado "+total+" coincidencia"+((total>1)?"s":"");
    } else {
        ultimaTR.classList.add("red");
        td.innerHTML="No se han encontrado coincidencias";
    }
}

function _ajax_error_validacion(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Sin conexión a internet.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (jqXHR === 200) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Se perdio la conexión a internet...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (jqXHR == 404) {
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Página solicitada no encontrada[404]</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (jqXHR == 500) {
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de servidor interno [500].</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (textStatus === 'parsererror') {
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (textStatus === 'abort') {
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else {
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error desconocido, por favor contactar con Sistemas de Venta...</h3>',
            confirmButtonText:'Ok'
        });
        return;
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