var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
var cantidad_idx_us = 0;
var arrg_dataSincro = [];
var warn_on_unload = '';
var blockF=0;
var B1=0;
var B2=0;
var B3=0;
var map;var TokenInsert = '';
var arreg_offline = [];
window.onbeforeunload = function() {
    if(warn_on_unload != ''){
        return warn_on_unload;
    }
}
var datas = '';var data_siempre = '';var datasdos = '';var data_siempredos = '';
var FotoFachada = '';
$( document ).ready(function() {
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Promise.all([
                DB_IniciarCPSesion(1)
            ])
            .then(respuestas =>{
                DB_CantidadEnCola('tbl_clitemporales');
                initControls();
                DB_CargarFiltrosTodos();
            })
            .catch(error =>{
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        console.log(error);
                    });
                });
            });
        });
    });
    $(document).on("click", "#btn-menu-back", function() {
        if(warn_on_unload != ''){
            Swal.fire({
                title: '¿Éstas seguro de ir al menu principal? el formulario aun no esta completo ',
                text: "",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!',
                cancelButtonText: 'No!'
            }).then((result) => {
                if(result.value){
                    warn_on_unload = '';
                    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                        });
                    });
                    location.href = "menu";
                }else{}
            });
        }else{
            $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                });
            });
            location.href = "menu";
        }
    });
    $('#c-departamento').on('change','#cbdepartamento',function(){
        warn_on_unload = 'no salir';
        var arr_mun = [];
        var txtdepartamento = '';
        $("#c-municipio").empty();
        V_Selec($("#cbdepartamento").val(),'cbdepartamento',2,'Departamento');
        txtdepartamento = $('select[name="cbdepartamento"] option:selected').text();
        if(!_empty(txtdepartamento)){
            $('#if-departamento').show();
            var atributos_dropdown = {
                class_input:'form-control custom-select'
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
                selectes = _form_dropdown('cbmunicipio',arr_mun,'',atributos_dropdown);
                $("#c-municipio").html(selectes);
                // V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
            };
        }else{
            $('#if-departamento').hide();
        }
    });
    $('#c-tpuntoventa').on('change','#cbtpuntoventa',function(){
        warn_on_unload = 'no salir';
        $('#if-tnegocio').show();
        var txttpuntoventa = '';
        $("#c-gironegocio").empty();
        V_Selec($("#cbtpuntoventa").val(),'cbtpuntoventa',8,'Tipo punto de venta');
        txttpuntoventa = $('select[name="cbtpuntoventa"] option:selected').text();
        if(!_empty(txttpuntoventa)){
            var arr_giro =[];
            var atributos_dropdown = {
                class_input:'form-control custom-select'
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
                    arr_giro[key] = {
                        codbx:elements[key].codbx,
                        valor:elements[key].valor
                    };
                }
                elements = [];
                var selectes = '';
                selectes = _form_dropdown('cbgironegocio',arr_giro,'',atributos_dropdown);
                $("#c-gironegocio").html(selectes);
                // V_Selec($("#cbgironegocio").val(),'cbgironegocio',9,'Giro de negocio');
            };
        }else{
            $('#if-tnegocio').hide();
        }
    });
    $('#c-tfacturacion').on('change','#cbtfacturacion',function(){
        warn_on_unload = 'no salir';
        var txtcbfacturacion = '';
        txtcbfacturacion = $('select[name="cbtfacturacion"] option:selected').text();
        V_Selec($("#cbtfacturacion").val(),'cbtfacturacion',10,'Tipo de facturaci&oacute;n');
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
    $('#c-condicioncli').on('change','#cbcondicioncli',function(){
        warn_on_unload = 'no salir';
        var txtcondicioncli = '';
        V_Selec($("#cbcondicioncli").val(),'cbcondicioncli',14,'Condici&oacute;n de cliente');
        txtcondicioncli = $('select[name="cbcondicioncli"] option:selected').text();
        if(txtcondicioncli === 'CREDITO'){
            $('#if-condcliente').show();
        }else{
            $('#if-condcliente').hide();
        }
    });
    /*-----------------VALIDACIONES TIEMPO REAL-------------------*/
    $("#txtnomcliente").keyup(function() {
        V_Text_LetraNumero($("#txtnomcliente").val(),'txtnomcliente',0,'Nombre del cliente');
        warn_on_unload = 'no salir';
    });
    $("#txtdireccion").keyup(function() {
        V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
        warn_on_unload = 'no salir';
    });
    $('#c-municipio').on('change','#cbmunicipio',function(){
        V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
        warn_on_unload = 'no salir';
    });
    $("#txtnumtelefono").keyup(function() {
        warn_on_unload = 'no salir';
        var CantidTelefonor = 0;var CantidTelefonot = 0;
        CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
        V_numeconMaskguion($("#txtnumtelefono").val(),'txtnumtelefono',4,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot);
    });
    $("#txtnomcontacto").keyup(function() {
        warn_on_unload = 'no salir';
        V_Text_ConEspacio($("#txtnomcontacto").val(),'txtnomcontacto',5,'Nombre de contacto');
    });
    $("#txtpropietario").keyup(function() {
        warn_on_unload = 'no salir';
        V_Text_ConEspacio($("#txtpropietario").val(),'txtpropietario',6,'Nombre de propietario');
    });
    $('#filefnegocio').on('change',function(){
        inputfilevalidacion('filefnegocio',7,'foto de la fachada del negocio');
        warn_on_unload = 'no salir'; 
    });
    $('#c-gironegocio').on('change','#cbgironegocio',function(){
        warn_on_unload = 'no salir';
        V_Selec($("#cbgironegocio").val(),'cbgironegocio',9,'Giro de negocio');
    });
    $("#txtdui").keyup(function() {
        warn_on_unload = 'no salir';
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        V_numeconMaskguion($("#txtdui").val(),'txtdui',11,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
    });
    $("#txtnumcontribuyente").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero($("#txtnumcontribuyente").val(),'txtnumcontribuyente',12,'N&uacute;mero de contribuyente');
    });
    $("#txtnit").keyup(function() {
        if(arrg_Credls['pais'] == 'GUATEMALA'){
            warn_on_unload = 'no salir';
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            V_numeconMaskguionGT($("#txtnit").val(),'txtnit',13,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }else{
            warn_on_unload = 'no salir';
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            V_numeconMaskguion($("#txtnit").val(),'txtnit',13,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }
    });
    $('#cbdiascobro').on('change',function(){
        warn_on_unload = 'no salir';
        V_Selec($("#cbdiascobro").val(),'cbdiascobro',15,'D&iacute;a de cobro');
    });
    $("#txtmontocredito").keyup(function() {
        warn_on_unload = 'no salir'; 
        V_NumeroEnteroDecimalpo($("#txtmontocredito").val(),'txtmontocredito',16,'Monto de credito');    
    });
    $('#cbfrecuenciavisita').on('change',function(){
        warn_on_unload = 'no salir';
        V_Selec($("#cbfrecuenciavisita").val(),'cbfrecuenciavisita',17,'Frecuencia de visita');
    });
    $('.GR_Check').click(function(){
        warn_on_unload = 'no salir';
        V_checks(18,'D&iacute;a de visita'); 
        var dia_se ='',estado_se=null;
        dia_se = this.value,estado_se=this.checked;
        if(dia_se == 'L_1' && estado_se == true){
            $("#ord_l").show();
            $("#txtordenvisital").val("");
            V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',31,'Orden de visita Lunes');
        } else if (dia_se == 'L_1' && estado_se == false) {
            $("#ord_l").hide();
        }

        if(dia_se == 'M_1' && estado_se == true){
            $("#ord_m").show();
            $("#txtordenvisitam").val("");
            V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',32,'Orden de visita Martes');
        } else if (dia_se == 'M_1' && estado_se == false) {
            $("#ord_m").hide();
        }

        if(dia_se == 'I_1' && estado_se == true){
            $("#ord_i").show();
            $("#txtordenvisitai").val("");
            V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',33,'Orden de visita Miércoles');
        } else if (dia_se == 'I_1' && estado_se == false) {
            $("#ord_i").hide();
        }

        if(dia_se == 'J_1' && estado_se == true){
            $("#ord_j").show();
            $("#txtordenvisitaj").val("");
            V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',34,'Orden de visita Jueves');
        } else if (dia_se == 'J_1' && estado_se == false) {
            $("#ord_j").hide();
        }

        if(dia_se == 'V_1' && estado_se == true){
            $("#ord_v").show();
            $("#txtordenvisitav").val("");
            V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',35,'Orden de visita Viernes');
        } else if (dia_se == 'V_1' && estado_se == false) {
            $("#ord_v").hide();
        }

        if(dia_se == 'S_1' && estado_se == true){
            $("#ord_s").show();
            $("#txtordenvisitas").val("");
            V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',36,'Orden de visita Sábado');
        } else if (dia_se == 'S_1' && estado_se == false) {
            $("#ord_s").hide();
        }

        if(dia_se == 'D_1' && estado_se == true){
            $("#ord_d").show();
            $("#txtordenvisitad").val("");
            V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',37,'Orden de visita Domingo');
        } else if (dia_se == 'D_1' && estado_se == false) {
            $("#ord_d").hide();
        }

    });
    $("#txtordenvisital").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',31,'Orden de visita Lunes');
    });
    $("#txtordenvisitam").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',32,'Orden de visita Martes');
    });
    $("#txtordenvisitai").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',33,'Orden de visita Miércoles');
    });
    $("#txtordenvisitaj").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',34,'Orden de visita Jueves');
    });
    $("#txtordenvisitav").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',35,'Orden de visita Viernes');
    });
    $("#txtordenvisitas").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',36,'Orden de visita Sábado');
    });
    $("#txtordenvisitad").keyup(function() {
        warn_on_unload = 'no salir';
        V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',37,'Orden de visita Domingo');
    });
    $('#cbrefrigerantes').on('change',function(){
        warn_on_unload = 'no salir';
        V_Selec($("#cbrefrigerantes").val(),'cbrefrigerantes',38,'Capacidad del negocio');
    });
    $('#cbreferencia').on('change',function(){
        warn_on_unload = 'no salir';
        V_Selec($("#cbreferencia").val(),'cbreferencia',39,'Referencia de cliente');
    });
});
/*------------------VALIDACIONES FROMULARIO-------------------*/
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
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-. ]+$/g
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
function V_Selec_Exhibidor(data,campo,ordencampo,etiqueta){
    var v = 0;
    if(_empty(data)){
        v = 0;
        $("#error-mjs-"+ordencampo).empty().html('<strong>Seleccionar un exhibidor</strong>');
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
    }else{
        v = 1;
        $("#error-mjs-"+ordencampo).empty();
        arrg_vali_result[ordencampo] = '';
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
function inputfilevalidacion(campo,ordencampo,etiqueta){
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
function V_NumeroEnteroDecimalpo(data,campo,ordencampo,etiqueta){
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
function validacion_form(){
    var contarok = 0;
    contarok +=V_Text_LetraNumero($("#txtnomcliente").val(),'txtnomcliente',0,'Nombre del establecimiento');
    contarok +=V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
    contarok +=V_Selec($("#cbdepartamento").val(),'cbdepartamento',2,'Departamento');
    contarok +=V_Selec($("#cbmunicipio").val(),'cbmunicipio',3,'Municipio');
    var CantidTelefonor = 0;var CantidTelefonot = 0;
    CantidTelefonor = arrg_Credls['CantidTelefono'] ;CantidTelefonot = arrg_Credls['CantidTelefono'] + 1;
    contarok +=V_numeconMaskguion($("#txtnumtelefono").val(),'txtnumtelefono',4,'N&uacute;mero de tel&eacute;fono',CantidTelefonor,CantidTelefonot);
    contarok +=V_Text_ConEspacio($("#txtnomcontacto").val(),'txtnomcontacto',5,'Nombre del contacto');
    contarok +=V_Text_ConEspacio($("#txtpropietario").val(),'txtpropietario',6,'Nombre del propietario');
    contarok +=inputfilevalidacion('filefnegocio',7,'Foto de la fachada del negocio');
    contarok +=V_Selec($("#cbtpuntoventa").val(),'cbtpuntoventa',8,'Tipo punto de venta');
    contarok +=V_Selec($("#cbgironegocio").val(),'cbgironegocio',9,'Giro de negocio');
    contarok +=V_Selec($("#cbtfacturacion").val(),'cbtfacturacion',10,'Tipo de facturaci&oacute;n');
    var txtcbfacturacion = '';
    txtcbfacturacion = $('select[name="cbtfacturacion"] option:selected').text();
    if(txtcbfacturacion === 'CREDITO FISCAL'){
        var CantidNumIPr = 0;var CantidNumIPt = 0;
        CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
        var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
        contarok +=V_numeconMaskguion($("#txtdui").val(),'txtdui',11,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
        contarok +=V_NumeroEntero($("#txtnumcontribuyente").val(),'txtnumcontribuyente',12,'N&uacute;mero de contribuyente');
        
        if(arrg_Credls['pais'] == 'GUATEMALA'){
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguionGT($("#txtnit").val(),'txtnit',13,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }else{
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguion($("#txtnit").val(),'txtnit',13,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
        }

    }else{
        if(arrg_Credls['pais'] == 'EL SALVADOR'){
            arrg_vali_result[11] = '';
            arrg_vali_result[12] = '';
            arrg_vali_result[13] = '';
            contarok +=3;
        }else if(arrg_Credls['pais'] == 'GUATEMALA'){
            arrg_vali_result[11] = '';
            arrg_vali_result[12] = '';
            arrg_vali_result[13] = '';
            contarok +=3;
        }else if(arrg_Credls['pais'] == 'HONDURAS'){
            var CantidNumIPr = 0;var CantidNumIPt = 0;
            CantidNumIPr = arrg_Credls['CantidNumIP'];CantidNumIPt = arrg_Credls['CantidNumIP'] + arrg_Credls['CantidadGuionDUI'];
            var NombreDocumentoDUI = "";NombreDocumentoDUI = arrg_Credls['NombreDocumentoDUI'];
            contarok +=V_numeconMaskguion($("#txtdui").val(),'txtdui',11,'N&uacute;mero de '+NombreDocumentoDUI,CantidNumIPr,CantidNumIPt);
            var CantidNumNITr = 0;var CantidNumNITt = 0;
            CantidNumNITr = arrg_Credls['CantidNumNIT'];CantidNumNITt = arrg_Credls['CantidNumNIT'] + arrg_Credls['CantidadGuionNIT'];
            var NombreDocumentoNIT = "";NombreDocumentoNIT = arrg_Credls['NombreDocumentoNIT'];
            contarok +=V_numeconMaskguion($("#txtnit").val(),'txtnit',13,'N&uacute;mero de '+NombreDocumentoNIT,CantidNumNITr,CantidNumNITt);
            contarok +=1;
        } 
    }
    contarok +=V_Selec($("#cbcondicioncli").val(),'cbcondicioncli',14,'Condici&oacute;n de cliente');
    var txtcondicioncli = '';
    txtcondicioncli = $('select[name="cbcondicioncli"] option:selected').text();
    if(txtcondicioncli === 'CREDITO'){
        contarok +=V_Selec($("#cbdiascobro").val(),'cbdiascobro',15,'D&iacute;a de cobro');
        contarok +=V_NumeroEnteroDecimalpo($("#txtmontocredito").val(),'txtmontocredito',16,'Monto de credito');
    }else{
        arrg_vali_result[15] = '';
        arrg_vali_result[16] = '';
        contarok +=2;
    }
    contarok +=V_Selec($("#cbfrecuenciavisita").val(),'cbfrecuenciavisita',17,'Frecuencia de visita');
    contarok +=V_checks(18,'D&iacute;a de visita');
    contarok +=V_NumeroEnteroDecimalpo($("#txtlatitud").val(),'txtlatitudm',19,'Latitud');
    contarok +=V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',20,'Longitud');

    // alert(document.getElementById('checklunes').checked);
    if(document.getElementById('checklunes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisital").val(),'txtordenvisital',31,'Orden de visita Lunes');
    }else{
        contarok +=1;
    }

    if(document.getElementById('checkmartes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitam").val(),'txtordenvisitam',32,'Orden de visita Martes');
    }else{
        contarok +=1;
    }

    if(document.getElementById('checkmiercoles').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitai").val(),'txtordenvisitai',33,'Orden de visita Miércoles');
    }else{
        contarok +=1;
    }


    if(document.getElementById('checkjueves').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitaj").val(),'txtordenvisitaj',34,'Orden de visita Jueves');
    }else{
        contarok +=1;
    }


    if(document.getElementById('checkviernes').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitav").val(),'txtordenvisitav',35,'Orden de visita Viernes');
    }else{
        contarok +=1;
    }


    if(document.getElementById('checksabado').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitas").val(),'txtordenvisitas',36,'Orden de visita Sábado');
    }else{
        contarok +=1;
    }

    if(document.getElementById('checkdomingo').checked){
        contarok +=V_NumeroEntero2digitos($("#txtordenvisitad").val(),'txtordenvisitad',37,'Orden de visita Domingo');
    }else{
        contarok +=1;
    }

    contarok +=V_Selec($("#cbrefrigerantes").val(),'cbrefrigerantes',38,'Capacidad del negocio');
    // alert(contarok)
    contarok +=V_Selec($("#cbreferencia").val(),'cbreferencia',39,'Referencia de cliente');
    return contarok;
}
/* ---------------------- AGREGAR USUARIO ----------------------- */
function agregar_usuario(){
    datas = '';
    data_siempre = '';
    datasdos = '';
    data_siempredos = '';
    TokenInsert = '';
    TokenInsert = TokenCliNuevo();
    datas = $("#form-clientes").serializeArray();
    var us_ID_Ruta = '';
    if(arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155 ){
        us_ID_Ruta = arrg_Credls['ruta_desarrollador'];
    }else{
        us_ID_Ruta = arrg_Credls['us_ID_Ruta'];
    }
    datas.push({name: 'us_cod', value: us_cod});
    datas.push({name: 'us_ID_Ruta', value: us_ID_Ruta});
    datas.push({name: 'pais', value: arrg_Credls['pais']});
    datas.push({name: 'imagenuno', value: FotoFachada});
    datas.push({name: 'TokenInsert', value: TokenInsert});
    datas.push({name: 'TipoUsuario', value: arrg_Credls['privilegio']});
    /* -------------------------------------------------------------- */
    data_siempre = $("#form-clientes").serializeArray();
    data_siempre.push({name: 'us_cod', value: us_cod});
    data_siempre.push({name: 'us_ID_Ruta', value: us_ID_Ruta});
    data_siempre.push({name: 'pais', value: arrg_Credls['pais']});
    data_siempre.push({name: 'imagenuno', value: FotoFachada});
    data_siempre.push({name: 'TokenInsert', value: TokenInsert});
    data_siempre.push({name: 'TipoUsuario', value: arrg_Credls['privilegio']});
    var detalle_validacion = '';
    if(validacion_form() < 30){
        arrg_vali_result.forEach( function(valor, indice, array) {
            if(!_empty(valor)){
                detalle_validacion += `<p>${valor}</p>`;
            }else{}
        });        
        Swal.fire({
            title: '<strong>Hay campos que requieren de su atención!</strong>',
            type: 'info',
            html:detalle_validacion,
            confirmButtonText:'Ok'
        });
        return;
    }else{
        $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                    url      : 'clientes/agregar-ok',
                    type     : 'POST',
                    dataType : 'JSON',
                    data     : datas,
                    timeout  : 7777
                }).done(function(_resp){
                    DB_GuardarPermanenteIDX(data_siempre);
                }).always(function(_resp, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                            if (textStatus == "success") {
                                if(_resp.rs == true){
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Registro enviado exitosamente!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then((result) => {
                                        $("#if-tfactura").hide(100,function(){
                                            $("#if-condcliente").hide(100,function(){
                                                $("#if-tnegocio").hide(100,function(){
                                                    $("#if-tfactura").hide(100,function(){
                                                        $('#if-departamento').hide(100,function(){
                                                            $("#ord_l").hide();
                                                            $("#ord_m").hide();
                                                            $("#ord_i").hide();
                                                            $("#ord_j").hide();
                                                            $("#ord_v").hide();
                                                            $("#ord_s").hide();
                                                            $("#ord_d").hide();
                                                            $(":input").removeClass("is-valid");
                                                            $(":checkbox").removeClass("is-valid");
                                                            $(":file").removeClass("is-valid");
                                                            document.getElementById("form-clientes").reset();
                                                            $('html').animate({scrollTop : 0}, 500);
                                                            FotoFachada = '';
                                                            $("#canvas").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                                            $("#filefnegocio").val("");
                                                        });
                                                    });
                                                });
                                            });
                                        });
                                    });
                                }else{
                                    console.log('ERROR CAMPOS');
                                    arrg_dataC = [];
                                    Swal.fire({
                                        title: 'Aviso Importante!',
                                        type: 'info',
                                        html:'<h6>'+_resp.errores+'</h6>',
                                        confirmButtonText:'Ok'
                                    });
                                }
                            }else{
                                _ajax_error_vClientesN(_resp.status,_resp.readyState,_resp.statusText);
                            }
                        });
                    });
                });
            });
        });
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
                var data = active.transaction('tbl_clitemporales', "readonly");
                var object = data.objectStore('tbl_clitemporales');
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
            title: 'No tiene registros en cola!',
            showConfirmButton: false,
            timer: 1500
        });
    }
}
function enviar_regis_offline(indice,elements){
    if(indice < elements.length){
        $.ajax({
            url:'clientes/agregar-ok',
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
                        delete_tempo_especifico(elements[indice].id);
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                                descargar_registros_cola('tbl_clitemporales');
                                Swal.fire({
                                    title: 'Ha ocurrido un error inesperado, por favor descargar y enviar archivo a sistemas de ventas',
                                    type: _resp.info,
                                    html:'<button class="btn btn-success" onclick="exportTableToExcel(\'tabla-registros-con-cola\')" type="button">Descargar Clientes</button> <br>Nombre : clientes_recuperados.xls',
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

function delete_tempo_especifico(eliminar) {
  var active = dataBaseAppSDV.result;
  var transaction = active.transaction(["tbl_clitemporales"], "readwrite");
  transaction.oncomplete = function(event) {
  };
  transaction.onerror = function(event) {
  };
  var objectStore = transaction.objectStore("tbl_clitemporales");
  var objectStoreRequest = objectStore.delete(eliminar);
  objectStoreRequest.onsuccess = function(event) {
    DB_CantidadEnCola('tbl_clitemporales');
  };
};

function descargar_registros_cola(tabla){
    arr_dat = [];
    var active = dataBaseAppSDV.result;
    var data = active.transaction([tabla], "readonly");
    var object = data.objectStore(tabla);
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
        //alert(elements.length);
        for (var key in elements) {
            arr_dat[key] = {
                txtsus:elements[key].txtsus,
                rutaimg:elements[key].rutaimg,
                txtnomcliente: elements[key].txtnomcliente,
                txtdireccion: elements[key].txtdireccion,
                cbmunicipio: elements[key].cbmunicipio,
                txtnumtelefono: elements[key].txtnumtelefono,
                txtnomcontacto: elements[key].txtnomcontacto,
                txtpropietario: elements[key].txtpropietario,
                cbtfacturacion: elements[key].cbtfacturacion,
                txtdui: elements[key].txtdui,
                txtnumcontribuyente: elements[key].txtnumcontribuyente,
                txtnit: elements[key].txtnit,
                cbcondicioncli: elements[key].cbcondicioncli,
                cbdiascobro: elements[key].cbdiascobro,
                txtmontocredito: elements[key].txtmontocredito,
                txttipocompra: 1,
                txttipocontribu: 1,
                cbcantidadex: elements[key].cbcantidadex,
                cbexhibidoru: elements[key].cbexhibidoru,
                cbexhibidord: elements[key].cbexhibidord,
                cbexhibidord: elements[key].cbexhibidord,
                txtordenvisita: elements[key].txtordenvisita,
                checkdiavisita: elements[key].checkdiavisita,
                cbfrecuenciavisita: elements[key].cbfrecuenciavisita,
                txtlatitud: elements[key].txtlatitud,
                txtlongitud: elements[key].txtlongitud,
                cbgironegocio: elements[key].cbgironegocio,
                imagenuno: elements[key].imagenuno,
                imagendos: elements[key].imagendos,
                txtmcomprab: elements[key].txtmcomprab,
                txtmcomprad: elements[key].txtmcomprad,
                txtmcompray: elements[key].txtmcompray,
                txtmcompraf: elements[key].txtmcompraf
            };
        }
    var htmltable = ``;
    htmltable += `
    <table id="tabla-registros-con-cola">
    <thead>
    <th>ID_CLIENTE</th>
    <th>CODIGO</th>
    <th>NOMBRE</th>
    <th>DIRECCION</th>
    <th>ID_MUNICIPIO</th>
    <th>TELEFONO</th>
    <th>CONTACTO</th>
    <th>PROPIETARIO</th>
    <th>ID_TIPOFACTURACION</th>
    <th>DUI</th>
    <th>NUMERO_REGISTRO</th>
    <th>NIT</th>
    <th>CONDICION_CLIENTE</th>
    <th>DIA_COBRO</th>
    <th>MONTO_CREDITO</th>
    <th>TIPO_COMPRA</th>
    <th>TIPO_CONTRIBUYENTE</th>
    <th>CANTIDAD_EXHIBIDOR</th>
    <th>EXHIBIDOR_UNO</th>
    <th>EXHIBIDOR_DOS</th>
    <th>EXHIBIDOR_TRES</th>
    <th>ORDEN_VISITA</th>
    <th>DIAS</th>
    <th>REFUNO</th>
    <th>LATITUD</th>
    <th>LONGITUD</th>
    <th>ID_USUARIOS</th>
    <th>ID_REF</th>
    <th>ID_GIRONEGOCIO</th>
    <th>FOTO_NEGOCIO</th>
    <th>FOTO_EXHIBIDOR</th>
    <th>COMPRAS_B</th>
    <th>COMPRAS_D</th>
    <th>COMPRAS_Y</th>
    <th>COMPRAS_F</th></thead>`;
        for(var i=0;i<parseInt(arr_dat.length);i++){

            htmltable +=`
            <tr>
                <td>${i}</td>
                <td>0000000</td>
                <td>${arr_dat[i].txtnomcliente}</td>
                <td>${arr_dat[i].txtdireccion}</td>
                <td>${arr_dat[i].cbmunicipio}</td>
                <td>${arr_dat[i].txtnumtelefono}</td>
                <td>${arr_dat[i].txtnomcontacto}</td>
                <td>${arr_dat[i].txtpropietario}</td>
                <td>${arr_dat[i].cbtfacturacion}</td>
                <td>${arr_dat[i].txtdui}</td>
                <td>${arr_dat[i].txtnumcontribuyente}</td>
                <td>${arr_dat[i].txtnit}</td>
                <td>${arr_dat[i].cbcondicioncli}</td>
                <td>${arr_dat[i].cbdiascobro}</td>
                <td>${arr_dat[i].txtmontocredito}</td>
                <td>${arr_dat[i].txttipocompra}</td>
                <td>${arr_dat[i].txttipocontribu}</td>
                <td>${arr_dat[i].cbcantidadex}</td>
                <td>${arr_dat[i].cbexhibidoru}</td>
                <td>${arr_dat[i].cbexhibidord}</td>
                <td>${arr_dat[i].cbexhibidord}</td>
                <td>${arr_dat[i].txtordenvisita}</td>
                <td>${arr_dat[i].checkdiavisita}</td>
                <td>${arr_dat[i].cbfrecuenciavisita}</td>
                <td>${arr_dat[i].txtlatitud}</td>
                <td>${arr_dat[i].txtlongitud}</td>
                <td></td>
                <td>1</td>
                <td>${arr_dat[i].cbgironegocio}</td>
                <td>NULL</td>
                <td>NULL</td>
                <td>${arr_dat[i].txtmcomprab}</td>
                <td>${arr_dat[i].txtmcomprad}</td>
                <td>${arr_dat[i].txtmcompray}</td>
                <td>${arr_dat[i].txtmcompraf}</td>  
            </td>`;
        }
    htmltable +=`</table>`;
    $("#registros-cola").html(htmltable);
    };
}
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'clientes_recuperados.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
        DB_LimpiarCola();
    }
}
/*------------------------------------------------------------*/
/*-------------------CONSULTAR COORDENADAS--------------------*/
/*------------------------------------------------------------*/
function consultar_coordenadas(){
    $("body").attr('style',  'overflow-y:hidden;');
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'comprobar/conexion',
                type     : 'POST',
                dataType : 'JSON',
                data     : {"pin":'conexions'},
                timeout  : 7777
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
                                    title: 'Aviso Importante!',
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
/*--------------------------------------------------------------*/
/*---------------------GEOLOCALIZACION--------------------------*/
/*--------------------------------------------------------------*/
function iniciar_mapa() {
    map = new L.Map('map');
    map.setView([13.685147,-89.147116], 15)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contribuyentes',
        maxZoom: 20
    }).addTo(map);
    map.attributionControl.setPrefix('SDV Bocadeli');
    L.marker([13.685147,-89.147116]).addTo(map);
}
function onLocationFound(e) {
    Swal.fire({
        type: 'success',
        title: 'Coordenda obtenida exitosamente!',
        showConfirmButton: false,
        timer: 1100
    }).then((result) => {
        map.remove();
        map = new L.Map('map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contribuyentes',
            maxZoom: 20
        }).addTo(map);
        map.attributionControl.setPrefix('SDV Bocadeli');
        map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng),15);
        L.marker([e.latlng.lat,e.latlng.lng]).addTo(map);
        $("#txtlatitud").val(e.latlng.lat);
        $("#txtlatitudm").val(e.latlng.lat);
        $("#txtlongitud").val(e.latlng.lng);
        $("#txtlongitudm").val(e.latlng.lng);
        V_NumeroEnteroDecimalpo($("#txtlatitud").val(),'txtlatitudm',19,'Latitud');
        V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',20,'Longitud');
    });
}
function onLocationError(e) {
    $("#txtlatitud").val(0);
    $("#txtlatitudm").val(0);
    $("#txtlongitud").val(0);
    $("#txtlongitudm").val(0);
    V_NumeroEnteroDecimalpo($("#txtlatitud").val(),'txtlatitudm',19,'Latitud');
    V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',20,'Longitud');
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
    map.locate({setView: true, maxZoom: 20});
}
/*-------------------------------------------------------------*/
/*---------------------TOKEN CLIENTE NUEVO---------------------*/
/*-------------------------------------------------------------*/
function TokenCliNuevo(){
    var hoy = new Date();
    var mes = (hoy.getMonth() + 1);
    var dia = hoy.getDate();
    var hora = hoy.getHours();
    var minutos = hoy.getMinutes();
    var segundos = hoy.getSeconds();
    if((mes >=0) && (mes<10)){
        mes = '0' + String(mes);
    }
    if((dia >=0) && (dia<10)){
        dia = '0' + String(dia);
    }
    if((hora >=0) && (hora<10)){
        hora = '0' + String(hora);
    }
    if ((minutos >= 0) && (minutos < 10)) {
        minutos = '0' + String(minutos);
    }
    if ((segundos >= 0) && (segundos < 10)) {
        segundos = '0' + String(segundos);
    }
    var fecha = String(hoy.getFullYear()) + String(mes) + String(dia);
    var hora = String(hora) + String(minutos) + String(segundos);
    var TokenMarcacion = String(fecha) + String(hora) + String(arrg_Credls['usuario']);
    return TokenMarcacion;
}