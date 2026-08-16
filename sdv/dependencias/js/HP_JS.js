var arrg_vali_result = [];
var arrg_Credls      = [];
function _form_abrir(input_name,input_value,atributos_param){
    var atributos=``;
    $.each(atributos_param, function(i, val){
        atributos+=i+`="${val}" `;
        atributos = atributos.replace('_form', '');
    });
    var form = `<form ${atributos}><br>
    <input type="hidden" name="${input_name}" value="${input_value}">`;
    return form;
}
function _form_cerrar(){
    return `</form>`;
}
function _form_input(parametros){
    var atributos=``;
    $.each(parametros, function(i, val){
        atributos+=i+`="${val}" `;
        atributos = atributos.replace('_input', '');
    });
    var input =`<input ${atributos}>`;
    return input;
}
function _form_dropdown(nombre,arrglista,seleccionar,atributos_extra){
    var extra=``;
    $.each(atributos_extra, function(i, val){
        extra+=i+`="${val}" `;
        extra = extra.replace('_input', '');
    });    
    var dropdown = `<select id="${nombre}" name="${nombre}" ${extra} data-width="100%"> required`;
    dropdown += `<option value="" hidden>Seleccione una opci&oacute;n</option>`;
    $.each(arrglista, function(i, val){
        if(seleccionar == val.valor){
            dropdown += `<option value="${val.codbx}" selected>${val.valor}</option>`;
        }else{
            dropdown += `<option value="${val.codbx}">${val.valor}</option>`;
        }   
    });
    dropdown+=`</select>
    <div class="valid-feedback">
        <strong></strong>
    </div>
    <div class="invalid-feedback">
        <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
    </div>`;
    return dropdown;
}
function _form_dropdown_do(nombre,arrglista,atributos_extra){
    var extra=``;
    $.each(atributos_extra, function(i, val){
        extra+=i+`="${val}" `;
        extra = extra.replace('_input', '');
    });    
    var dropdown = `<select id="${nombre}" name="${nombre}" ${extra} data-width="100%">`;
    dropdown += `<option value="" hidden>Elige la cantidad ...</option>`;
    $.each(arrglista, function(i, val){
        dropdown += `<option value="${val.codbx}">${val.valor}</option>`;
    });
    dropdown+=`</select><div id="error_cantiExh" style="color:#F13154;text-align: left;"></div>`;
    return dropdown;
}
function _mensaje_alerta(parametros,extra_datos){
    var divhtml=``;
    divhtml=`
    <div id='content-mjs' class='alert alert-${parametros.cla} alert-dismissible fade show' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
        ${parametros.info}
    </div>
    ${extra_datos}`;
    return divhtml;
}
function _empty(e) {
  switch (e) {
    case "":
    case null:
    case false:
    case typeof this == "undefined":
      return true;
    default:
      return false;
  }
}
function _obtener_param_url(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function fecha_actual(){
    var hoy = new Date();
    var fecha = hoy.getDate() + '-' + ( hoy.getMonth() + 1 ) + '-' + hoy.getFullYear();
    var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
    var fechaYHora = fecha + ' ' + hora;
    return fechaYHora;
}
function espera(ms){
var d = new Date();
var d2 = null;
do { d2 = new Date(); }
while(d2-d < ms);
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
    }else if ( (jqXHR === 0) || (jqXHR === 200) ) {
        if(cantidad_idx_us>0){
            var active = dataBaseAppSDV.result;
            var data = active.transaction(['tbl_usuarios'], "readonly");
            var object = data.objectStore('tbl_usuarios');
            var idx_usuario_text = '';
            var idx_contrasena_text = '';
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
                idx_usuario_text = elements[0].usuario;
                idx_contrasena_text = elements[0].contrasena;
                if( ( idx_usuario_text == $("#usuario").val() ) && ( idx_usuario_text == $("#contrasena").val() )){
                    location.href = elements[0].ruta_app;
                }else{
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h5>El <strong>usuario &oacute; contrase&ntilde;a</strong> ingresados no son validos</h5>',
                        confirmButtonText:'Ok'
                    });                
                }
            };
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'error',
                html:'<h3>Por favor verifica tu conexión a internet e inicia sesión para guardar tus credenciales...</h3>',
                confirmButtonText:'Ok'
            });
        }
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
function _ajax_error_vClientesN(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Promise.all([
            DB_GuardarPermanenteIDX(data_siempre),
            DB_GuardarTemporal(datas)
        ])
        .then(respuestas =>{
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
                                    FotoExhibidor = '';
                                    FotoFachada = '';
                                    $("#txtEName").empty().html("");
                                    $("#txtENamedos").empty().html("");
                                    $("#txtENametres").empty().html("");
                                    $("#cbexhibidoru").val("");
                                    $("#cbexhibidord").val("");
                                    $("#cbexhibidort").val("");
                                    $("#canvas").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                    $("#canvasd").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                    $("#filefnegocio").val("");
                                    $("#fileexhibidor").val("");
                                });
                            });
                        });
                    });
                });
            
        })
        .catch(error =>{
            console.log(error);
        });
        return;
    }else if (jqXHR === 200) {
        Promise.all([
            DB_GuardarPermanenteIDX(data_siempre),
            DB_GuardarTemporal(datas)
        ])
        .then(respuestas =>{
  
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
                                    FotoExhibidor = '';
                                    FotoFachada = '';
                                    $("#txtEName").empty().html("");
                                    $("#txtENamedos").empty().html("");
                                    $("#txtENametres").empty().html("");
                                    $("#cbexhibidoru").val("");
                                    $("#cbexhibidord").val("");
                                    $("#cbexhibidort").val("");
                                    $("#canvas").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                    $("#canvasd").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                    $("#filefnegocio").val("");
                                    $("#fileexhibidor").val("");
                                });
                            });
                        });
                    });
                });
            
        })
        .catch(error =>{
            console.log(error);
        });
        return;
    }else if (jqXHR == 404) {
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
function _ajax_error_Exhibidores(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Promise.all([
            DB_GuardarPermanenteEXH('tbl_obexhingre',0,data_siempre),
            DB_GuardarPermanenteEXH('tbl_observacionexh',1,datas)
        ])
        .then(respuestas =>{
            $.when( $('#InfoObservacion').stop(true,true).hide() ).done(function( x ) {
               $.when( $('#No_InfoCorrecta').stop(true,true).hide() ).done(function( x ) {
                    $.when( $('#InfoCliente').stop(true,true).hide() ).done(function( x ) {
                        $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                            $.when( $('#Colocacion_exhibidores').stop(true,true).hide() ).done(function( x ) {
                                $("#CodCLi").text("00000");
                                delete_tempo_especifico(IdClienteSelect);
                                $("#fotounovalidacion").val("");
                                $("#imagen").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                document.getElementById("form-exhibidores").reset();
                                $('html').animate({scrollTop : 0}, 500);
                                $("#filefotodos").val("");
                                IdClienteSelect = '';
                                InicializarExhNuevos();
                                $("#Motivo_NO").hide();
                                FotoExhibidor = '';
                            });
                        });
                    });
                });
            });
        })
        .catch(error =>{
            console.log(error);
        });
        return;
    }else if (jqXHR === 200) {
        Promise.all([
            DB_GuardarPermanenteEXH('tbl_obexhingre',0,data_siempre),
            DB_GuardarPermanenteEXH('tbl_observacionexh',1,datas)
        ])
        .then(respuestas =>{
            $.when( $('#InfoObservacion').stop(true,true).hide() ).done(function( x ) {
               $.when( $('#No_InfoCorrecta').stop(true,true).hide() ).done(function( x ) {
                    $.when( $('#InfoCliente').stop(true,true).hide() ).done(function( x ) {
                        $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                            $.when( $('#Colocacion_exhibidores').stop(true,true).hide() ).done(function( x ) {
                                $("#CodCLi").text("00000");
                                delete_tempo_especifico(IdClienteSelect);
                                $("#fotounovalidacion").val("");
                                $("#imagen").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                                document.getElementById("form-exhibidores").reset();
                                $('html').animate({scrollTop : 0}, 500);
                                $("#filefotodos").val("");
                                IdClienteSelect = '';
                                InicializarExhNuevos();
                                $("#Motivo_NO").hide();
                                FotoExhibidor = '';
                            });
                        });
                    });
                });
            });
        })
        .catch(error =>{
            alert(error);
            console.log(error);
        });
        return;
    }else if (jqXHR == 404) {
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
function _error_validacion_sincroexh(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Promise.all([
            DB_CargarFiltroExhibidor(),
            DB_CargaCredenciales()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                 $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log('Filtros iniciados correctamente!');
                });
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log(error);
                });
            });
        });
    }else if (jqXHR === 200) {
        Promise.all([
            DB_CargarFiltroExhibidor(),
            DB_CargaCredenciales()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log('Filtros iniciados correctamente!');
                });
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log(error);
                });
            });
        });
    }else if (jqXHR == 404) {
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
function _error_validacion_sincroexhVER(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Promise.all([
            DB_CargarFiltroExhibidor(),
            DB_CargaCredenciales(),
            DB_CargarFiltroClientes()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                 $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log('Carga de filtros completada! [ClientesSN]');
                    $("#ModalCliSN").modal("toggle");
                });
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log(error);
                });
            });
        });
    }else if (jqXHR === 200) {
        Promise.all([
            DB_CargarFiltroExhibidor(),
            DB_CargaCredenciales(),
            DB_CargarFiltroClientes()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log('Carga de filtros completada! [ClientesSN]');
                    $("#ModalCliSN").modal("toggle");
                });
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log(error);
                });
            });
        });
    }else if (jqXHR == 404) {
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
function _ajax_error_validacion_sincro(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Promise.all([
            DB_CargarFiltro('tbl_departamento','cbdepartamento','c-departamento'),
            DB_CargarFiltro('tbl_tpuntoventa','cbtpuntoventa','c-tpuntoventa'),
            DB_CargarFiltro('tbl_tfacturacion','cbtfacturacion','c-tfacturacion'),
            DB_CargarFiltro('tbl_condicioncli','cbcondicioncli','c-condicioncli'),
            DB_CargarFiltroExhibidor(),
            DB_CargaCredenciales()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                 $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log('Filtros iniciados correctamente!');
                    iniciar_mapa();
                });
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log(error);
                });
            });
        });
    }else if (jqXHR === 200) {
        Promise.all([
            DB_CargarFiltro('tbl_departamento','cbdepartamento','c-departamento'),
            DB_CargarFiltro('tbl_tpuntoventa','cbtpuntoventa','c-tpuntoventa'),
            DB_CargarFiltro('tbl_tfacturacion','cbtfacturacion','c-tfacturacion'),
            DB_CargarFiltro('tbl_condicioncli','cbcondicioncli','c-condicioncli'),
            DB_CargarFiltroExhibidor(),
            DB_CargaCredenciales()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log('Filtros iniciados correctamente!');
                    iniciar_mapa();
                });
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    console.log(error);
                });
            });
        });
    }else if (jqXHR == 404) {
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
function _ajax_error_validacion_loglat(jqXHR, textStatus, errorThrown){
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
            type: 'info',
            html:'<h3>Coordenada obtenida pero no podemos mostrar ubicación en el mapa, sin conexión a internet</h3>',
            confirmButtonText:'Ok'
        }).then((result) => {
            getLocationLeaflet();
        });
        return;
    } else if (jqXHR === 200) {
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'info',
            html:'<h3>Coordenada obtenida pero no podemos mostrar ubicación en el mapa, sin conexión a internet</h3>',
            confirmButtonText:'Ok'
        }).then((result) => {
            getLocationLeaflet();
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
function _ajax_error_envioOffline(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Se perdio la conexión a internet...</h3>',
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
function _ajax_error_VerCliAC(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Se perdio la conexión a internet... esta opcion solo funciona con internet...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (jqXHR === 200) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Se perdio la conexión a internet... esta opcion solo funciona con internet...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (jqXHR == 404) {
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
function _ajax_error_CliAC(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Promise.all([
            DB_GuardarPermanenteCLIAC('tbl_clientesactuingre',0,datas),
            DB_GuardarPermanenteCLIAC('tbl_cliactutempo',1,datas)
        ])
        .then(respuestas =>{
            $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
                $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                    document.getElementById("form_actualizacion").reset();
                    Id_Cliente = '';
                });
            });
        })
        .catch(error =>{
            console.log(error);
        });
        return;
    }else if (jqXHR === 200) {
        Promise.all([
            DB_GuardarPermanenteCLIAC('tbl_clientesactuingre',0,datas),
            DB_GuardarPermanenteCLIAC('tbl_cliactutempo',1,datas)
        ])
        .then(respuestas =>{
            $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
                $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
                    document.getElementById("form_actualizacion").reset();
                    Id_Cliente = '';
                });
            });
        })
        .catch(error =>{
            // alert(error);
            console.log(error);
        });
        return;
    }else if (jqXHR == 404) {
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
function _ajax_error_Reclamo(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, por favor comunicarse con Sistemas de Venta.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Promise.all([
            DB_Guardar_reclamo_temporal('tbl_reclamosingre',0,datas),
            DB_Guardar_reclamo_temporal('tbl_reclamosTemp',1,datas)
        ])
        .then(respuestas =>{
            // $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
            //     $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
            //         document.getElementById("form_reclamo").reset();
            //         Id_Cliente = '';
            //     });
            // });           
        })
        .catch(error =>{
            console.log(error);
        });
        return;
    }else if (jqXHR === 200) {
        Promise.all([
            DB_Guardar_reclamo_temporal('tbl_reclamosingre',0,datas),
            DB_Guardar_reclamo_temporal('tbl_reclamosTemp',1,datas)
        ])
        .then(respuestas =>{
            // $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
            //     $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
            //         document.getElementById("form_reclamo").reset();
            //         Id_Cliente = '';
            //     });
            // });            
        })
        .catch(error =>{
            // alert(error);
            console.log(error);
        });
        return;
    }else if (jqXHR == 404) {
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
function DB_IniciarCPSesion(validateUs) {
    return new Promise(function(resolve, reject) {
        dataBaseAppSDV = indexedDB.open('DBAppSDV',1);
        dataBaseAppSDV.onsuccess = function (e) {
            cantidad_idx_us = 0;
            var activedos = dataBaseAppSDV.result;
            var transaction = activedos.transaction(['tbl_usuarios'], 'readonly');
            var objectStore = transaction.objectStore('tbl_usuarios');
            var countRequest = objectStore.count();
            countRequest.onsuccess = function() {
                if( countRequest.result > 0  ){
                    DB_UsuarioLogueado();
                    Promise.all([
                        cargar_credenciales_sdv(),
                        DB_CantidadUsuarios()
                    ])
                    .then(respuestas => {
                        if(arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155){
                            $('#slc_ruta_').show();
                            if(arrg_Credls['ruta_desarrollador'] != '' && arrg_Credls['ruta_desarrollador'] != null){
                                $("#slc_ruta_desarrollador").val(arrg_Credls['ruta_desarrollador']).trigger('change');
                            }
                        }else{
                            $('#slc_ruta_').hide();
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }else{
                    if(validateUs == 1)
                        location.href = '/sdv/';
                }
            };
            countRequest.onerror = function(event) {
                location.href = '/sdv/';
            };
            resolve(1);
        };
        dataBaseAppSDV.onupgradeneeded = function (e) {
            var active = dataBaseAppSDV.result;
            var OBJ_tblusuarios = active.createObjectStore("tbl_usuarios", {keyPath: 'us_cod', autoIncrement: true});
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
            var OBJ_clientes = active.createObjectStore("tbl_clientes", {keyPath: 'Cli_Id'});
            OBJ_clientes.createIndex('by_estado_w', 'Cli_estado', {unique: false});  
            var OBJ_observacionexh = active.createObjectStore("tbl_observacionexh", {keyPath: 'id', autoIncrement: true});
            var OBJ_obexhingresados = active.createObjectStore("tbl_obexhingre", {keyPath: 'id', autoIncrement: true});
            var OBJ_exhifacturados = active.createObjectStore("tbl_exhifacturados", {keyPath: 'idexhf'});
            OBJ_exhifacturados.createIndex('by_exhfact', 'exhfact', {unique: false});
            var OBJ_clientesactuingre = active.createObjectStore("tbl_clientesactuingre", {keyPath: 'id', autoIncrement: true});
            var OBJ_cliactutempo = active.createObjectStore("tbl_cliactutempo", {keyPath: 'id', autoIncrement: true});
            var OBJ_parametros = active.createObjectStore("tbl_parametros", {keyPath: 'id', autoIncrement: true});
            var OBJ_tbl_exh_censados = active.createObjectStore("tbl_exh_censados", {keyPath: 'idexh', autoIncrement: true});
            OBJ_tbl_exh_censados.createIndex('by_codigocli', 'CodigoCliente', {unique: false});
            var OBJ_tblmotivoelim = active.createObjectStore("tbl_motivoelim", {keyPath: 'id', autoIncrement: true});
            var OBJ_tblfiltros = active.createObjectStore("tbl_filtros", {keyPath: 'id', autoIncrement: true});
            var OBJ_tblproductos = active.createObjectStore("tbl_productos", {keyPath: 'Cat_Id'});
            OBJ_tblproductos.createIndex('by_estado_p', 'Catx_estado', {unique: false});
            OBJ_tblproductos.createIndex('by_familia_p', 'Cat_familia', {unique: false});
            OBJ_tblproductos.createIndex('by_Subf_Fa_Id', 'Subf_Fa_Id', {unique: false});
        // -- 04/10/2021 ------------------------------------------------------------------------------------------------
            // var OBJ_tbl_reclamosingre = active.createObjectStore("tbl_reclamosingre", {keyPath: 'Id', autoIncrement: true});
            var OBJ_tbl_reclamosingre = active.createObjectStore("tbl_reclamosingre", {keyPath: 'codigo_reclamo', autoIncrement: true});
            OBJ_tbl_reclamosingre.createIndex('by_cola', 'pendiente', { unique: false });
            OBJ_tbl_reclamosingre.createIndex('by_cliente', 'Id_Cliente', { unique: false });
            OBJ_tbl_reclamosingre.createIndex('by_estado', 'estado', { unique: false });
        // --------------------------------------------------------------------------------------------------------
            var OBJ_tbl_reclamosTemp = active.createObjectStore("tbl_reclamosTemp", {keyPath: 'Id', autoIncrement: true});
            var OBJ_tbl_tipo_danos = active.createObjectStore("tbl_tipo_danos", {keyPath: 'Tipd_Id'});
            OBJ_tbl_tipo_danos.createIndex('by_Trec_Id', 'Trec_Id', { unique: false });
            /*DBA CAMBIOS 07/07/2021*/
            var OBJ_tblstatusex = active.createObjectStore("tbl_status_exhibidores", { keyPath: 'Ste_token', autoIncrement: false });
            OBJ_tblstatusex.createIndex('by_Ste_Cli_Id', 'Ste_Cli_Id', { unique: false });
            OBJ_tblstatusex.createIndex('by_Ste_cola', 'Ste_cola', { unique: false });
            var OBJ_tbltipoexh = active.createObjectStore("tbl_tipo_exhibidores", { keyPath: 'idx', autoIncrement: true });
        // -- 06/09/2021 ------------------------------------------------------------------------------------------
            var OBJ_tbl_control_inventario = active.createObjectStore("tbl_control_inventario", { keyPath: 'token', unique: true });
            OBJ_tbl_control_inventario.createIndex('by_Cola', 'pendiente', { unique: false });
            OBJ_tbl_control_inventario.createIndex('by_enviado', 'enviado', { unique: false });
            OBJ_tbl_control_inventario.createIndex('by_cliente', 'id_cliente', { unique: false });
            OBJ_tbl_control_inventario.createIndex('by_opcion', 'opcion', { unique: false }); // --- 17/08/21 ---
            OBJ_tbl_control_inventario.createIndex('by_token', 'token', { unique: true }); // --- 20/08/21 ---
        // --------------------------------------------------------------------------------------------------------
        // ----- 20/10/2021 ---------------------------------------------------------------------------------------
            var OBJ_tbl_ruta_desarrollo = active.createObjectStore("tbl_ruta_desarrollo", {keyPath: 'Ru_Id'});
        // --------------------------------------------------------------------------------------------------------
        // ----- 05/01/2021 -----------------------------------------------------------------------------------------
            var OBJ_tbl_ste_tmotivo = active.createObjectStore("tbl_ste_tipo_motivos", {keyPath: 'Tmot_Id'});
            var OBJ_tbl_ste_motivo = active.createObjectStore("tbl_ste_motivo", {keyPath: 'Mot_Id'});
            OBJ_tbl_ste_motivo.createIndex('by_Tmot_Id', 'Mot_Tmot_Id', {unique: false});
        // ----------------------------------------------------------------------------------------------------------
        // ----- 10/09/2022
            var OBJ_tblPedidoSugerido = active.createObjectStore("tbl_PedSug_PedidosDet", {keyPath: 'Correlativo'});
            OBJ_tblPedidoSugerido.createIndex('by_sufamilia', 'Subf_nombre', {unique: false});
            OBJ_tblPedidoSugerido.createIndex('by_PedSug_cola', 'PedSug_cola', { unique: false });
            OBJ_tblPedidoSugerido.createIndex('by_IdPedidoEnc', 'IdPedidoEnc', { unique: false });
        // --------------------------------------------------------------------------------------------------------
        // ----- 17/11/2022 -----------------------------------------------------------------------------------------
        var tbl_PedSug_Motivo = active.createObjectStore("tbl_PedSug_Motivo", {keyPath: 'Id'});
        tbl_PedSug_Motivo.createIndex('by_Tipo', 'Tipo', {unique: false});
        // --------------------------------------------------------------------------------------------------------
        // ----- 02/03/2022 ---------------------------------------------------------------------------------------
        var OBJ_tbl_vehiculo = active.createObjectStore("tbl_vehiculo", {keyPath: 'idx', autoIncrement: true });
        OBJ_tbl_vehiculo.createIndex('by_recepcion', 'Vehi_fecha_recibido', {unique: false});
        OBJ_tbl_vehiculo.createIndex('by_estado', 'Vehi_estado', {unique: false});
        OBJ_tbl_vehiculo.createIndex('by_placas', 'Vehi_placas', {unique: false});
        OBJ_tbl_vehiculo.createIndex('by_es_sincronizado', 'es_sincronizado', {unique: false});
        var OBJ_tbl_items_check_list_vehiculo = active.createObjectStore("tbl_items_check_list_vehiculo", {keyPath: 'idx', autoIncrement: true });
        OBJ_tbl_items_check_list_vehiculo.createIndex('by_estado', 'Irv_estado', {unique: false});
        OBJ_tbl_items_check_list_vehiculo.createIndex('by_id', 'Irv_Id', {unique: false});
        OBJ_tbl_items_check_list_vehiculo.createIndex('by_seccion', 'Irv_seccion_descripcion', {unique: false});
        OBJ_tbl_items_check_list_vehiculo.createIndex('by_tipo', 'Irv_tipo', {unique: false});
        var OBJ_tbl_vehiculo_recepcion = active.createObjectStore("tbl_vehiculo_recepcion", {keyPath: 'idx', autoIncrement: true });
        OBJ_tbl_vehiculo_recepcion.createIndex('by_fecha_recepcion', 'fecha_recepcion', {unique: false});
        OBJ_tbl_vehiculo_recepcion.createIndex('by_estado', 'estado_recepcion', {unique: false});
        OBJ_tbl_vehiculo_recepcion.createIndex('by_es_sincronizado', 'es_sincronizado', {unique: false});
        // -------------------------------------------------------------------------------------------------------- 
        // ----- 03/07/2022 ---------------------------------------------------------------------------------------
        var OBJ_tbl_tipo_licencia = active.createObjectStore("tbl_tipo_licencia", {keyPath: 'TLic_Id'});
        OBJ_tbl_tipo_licencia.createIndex('by_tipo', 'TLic_nombre', {unique: false});
        // --------------------------------------------------------------------------------------------------------  
        // ----- 24/01/2022 ---------------------------------------------------------------------------------------
        var OBJ_tbl_parametros_vnt = active.createObjectStore("tbl_parametros_vnt", {keyPath: 'idx', autoIncrement: true });
        OBJ_tbl_parametros_vnt.createIndex('by_tipo', 'tipo_parametro', {unique: false});
        // ----- 09/05/2022 ---------------------------------------------------------------------------------------
        var OBJ_tbl_parametros_vnt = active.createObjectStore("tbl_referencia", {keyPath: 'idx', autoIncrement: true });
        // ----- 23/08/2023 ---------------------------------------------------------------------------------------
        var OBJ_tbl_act_cliente = active.createObjectStore("tbl_act_cliente", { keyPath: 'idx', autoIncrement: true });
        OBJ_tbl_act_cliente.createIndex('by_Actc_cola', 'Actc_cola', { unique: false });
        var OBJ_ttbl_proveedores = active.createObjectStore("tbl_proveedores", { keyPath: 'idx', autoIncrement: true });

        // Crea el almacén de objetos tbl_mercado
        var objectStore = active.createObjectStore('tbl_mercado', { keyPath: 'id_evaluacion' });
        // Crea el almacén de objetos tbl_tareas
        var OBJ_tbl_tareas = active.createObjectStore("tbl_tareas", {keyPath: 'tarea_id'});


        // OBJ_tbl_tareas.createIndex('by_id_evaluacion', 'id_evaluacion', {unique: true});
        var OBJ_tbl_oportunidades = active.createObjectStore("tbl_oportunidades", {keyPath: 'id'});


        }
        dataBaseAppSDV.onerror = function (e) {
            Swal.fire({
                title: 'Aviso Importante!',
                type: 'error',
                html:'<h5>Error inesperado, por favor comunicarlo a Sistemas de Venta</h5>',
                confirmButtonText:'Ok'
            });
            reject(0);
        };
    });
}
function DB_UsuarioLogueado(){
    var rutas       = [];
    var active      = dataBaseAppSDV.result;
    var data        = active.transaction('tbl_usuarios', "readonly");
    var object      = data.objectStore('tbl_usuarios');
    var elements    = [];
    object.openCursor().onsuccess = function (e) {
        var result = e.target.result;
        if(result === null){
            return;
        }
        elements.push(result.value);
        result.continue();
    };
    data.oncomplete = function () {
        us_cod      = elements[0].us_cod;
        us_ID_Ruta  = elements[0].us_ID_Ruta;
        pais        = elements[0].pais;
        canal_usu   = elements[0].canal_usu;
        usuario     = elements[0].usuario;
        rutas       = elements[0].ls_rutas;
        //CONFIRMAR USUARIO
        var url_indicada    = '',
        url_indicada        = elements[0].ruta_app;
        url_indicada        = url_indicada.replace("index.php/", "");
        if(elements[0].privilegio == '#supe1.$0'){
            $("#uslogin").empty().html('-------');
            location.href   = url_indicada;
        }else if(elements[0].privilegio == 'admin01'){
            $("#uslogin").empty().html('-------');
            location.href   = url_indicada;
        }else{
            $("#uslogin").empty().html(elements[0].nombre_us);
            if(elements[0].privilegio == '3' || elements[0].privilegio == '155'){
                $("#t_taskforce").show();
            }else{}
        }
        if(elements[0].privilegio == 15 || elements[0].privilegio == 116 || elements[0].privilegio == 155){
            if(rutas.length != 0){
                var arr_dat = []; 
                var atributos_dropdown = {
                    class_input:'form-control custom-select'
                };  
                rutas.forEach(function(valor, index){
                    arr_dat.push({
                        codbx: valor.Ru_Id,
                        valor: valor.Ru_nombre
                    });
                    $('#select_ruta_desarrollador').html(_form_dropdown('slc_ruta_desarrollador',arr_dat,'',atributos_dropdown));
                });
            }
        }
    };
    data.onerror = function (e) {
        $("#uslogin").empty().html("");
    }
}
function DB_CantidadEnCola(tabla){
    var active = dataBaseAppSDV.result;
    var transaction = active.transaction([tabla], 'readonly');
    var objectStore = transaction.objectStore(tabla);
    var countRequest = objectStore.count();
    countRequest.onsuccess = function() {
        $("#RegisCola").html(countRequest.result);
    }
}
function fechaDispositivo(){
    var hoy = new Date();
    var mes = (hoy.getMonth() + 1);
    var dia = hoy.getDate();
    var hora = hoy.getHours();
    var minutos = hoy.getMinutes();
    var segundos = hoy.getSeconds();
    if((mes >=0) && (mes<10)){
        mes = '0' + mes;
    }
    if((dia >=0) && (dia<10)){
        dia = '0' + dia;
    }
    if((hora >=0) && (hora<10)){
        hora = '0' + hora;
    }
    if ((minutos >= 0) && (minutos < 10)) {
        minutos = '0' + minutos;
    }
    if ((segundos >= 0) && (segundos < 10)) {
        segundos = '0' + segundos;
    }
    var fecha = hoy.getFullYear() +'-'+ mes + '-' + dia;
    var hora = hora + ':' + minutos + ':' + segundos;
    var fechaDispositivo = fecha + ' ' + hora;
    return fechaDispositivo;
}
/*CAMBIOS 11/08/2021*/
function guardar_filtro(val_filtro,tipofil){
    var actived     = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_filtros"], "readwrite").objectStore("tbl_filtros");
    var request     = objectStore.get(tipofil);
    request.onerror = function(event) {
    };
    request.onsuccess = function(event) {
        var data = request.result;
        if(tipofil == 1){
            data.ValueAC  = val_filtro;
            data.ValorAC  = val_filtro;
            data.EstadoAC = 1;
        }else{
            data.ValueEX  = val_filtro;
            data.ValorEX  = val_filtro;
            data.EstadoEX = 1;
        }
        var requestUpdate = objectStore.put(data);
        requestUpdate.onerror = function(event) {
        };
        requestUpdate.onsuccess = function(event) {
        };
    }
}
function guardar_filtroEstado(val_filtro){
    var actived = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_filtros"], "readwrite").objectStore("tbl_filtros");
    var request = objectStore.get(3);
    request.onerror = function(event) {
    };
    request.onsuccess = function(event) {
        var data = request.result;
        data.FiltroEstadoAC = val_filtro;
        var requestUpdate = objectStore.put(data);
        requestUpdate.onerror = function(event) {
        };
        requestUpdate.onsuccess = function(event) {
        };
    }
}
function cargar_credenciales_sdv(){
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
            arrg_Credls['NombreRuta']           = elements[0].NombreRuta;
            arrg_Credls['usuario']              = elements[0].usuario;
            arrg_Credls['idusuario']            = elements[0].idusuario;
            arrg_Credls['clave']                = elements[0].clave;
            arrg_Credls['privilegio']           = elements[0].privilegio;
            arrg_Credls['ruta_app']             = elements[0].ruta_app;
            arrg_Credls['us_cod']               = elements[0].us_cod;
            arrg_Credls['us_ID_Ruta']           = elements[0].us_ID_Ruta;
            arrg_Credls['nombre_us']            = elements[0].nombre_us;
            arrg_Credls['idsupervisor']         = elements[0].idsupervisor;
            arrg_Credls['pais']                 = elements[0].pais;
            arrg_Credls['canal']                = elements[0].canal;
            arrg_Credls['canal_usu']            = elements[0].canal_usu;
            arrg_Credls['ltdistr']              = elements[0].ltdistr;
            arrg_Credls['ls_rutas']             = elements[0].ls_rutas;
            arrg_Credls['id_division']          = elements[0].id_division;
            arrg_Credls['id_distribuidora']     = elements[0].id_distribuidora;
            arrg_Credls['RegexTelefono']        = elements[0].RegexTelefono;
            arrg_Credls['CantidTelefono']       = elements[0].CantidTelefono;
            arrg_Credls['FormatoTelefono']      = elements[0].FormatoTelefono;
            $('#txtnumtelefono').mask(elements[0].FormatoTelefono, {placeholder: elements[0].FormatoTelefono});
            arrg_Credls['RegexNumIP']           = elements[0].RegexNumIP;
            arrg_Credls['CantidNumIP']          = elements[0].CantidNumIP;
            arrg_Credls['FormatoNumIP']         = elements[0].FormatoNumIP;
            $('#txtdui').mask(elements[0].FormatoNumIP, {placeholder: elements[0].FormatoNumIP});
            arrg_Credls['NombreDocumentoDUI']   = elements[0].NombreDocumentoDUI;
            arrg_Credls['RegexNumNIT']          = elements[0].RegexNumNIT;
            arrg_Credls['CantidNumNIT']         = elements[0].CantidNumNIT;
            arrg_Credls['FormatoNumNIT']        = elements[0].FormatoNumNIT;
            $('#txtnit').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            arrg_Credls['NombreDocumentoNIT']   = elements[0].NombreDocumentoNIT;
            $(".docidentidad").html('<span class="fa fa-id-card fa-lg"></span> '+elements[0].NombreDocumentoDUI+':');
            $(".idtributaria").html('<span class="fa fa-id-card-alt fa-lg"></span> '+elements[0].NombreDocumentoNIT+':');
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
            if(arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155){
                if(arrg_Credls['privilegio']  == 155)
                    {
    
                        $('#encuesta-de-exhibidores').hide();                       
                        $('#btnNuevoReclamo').hide();
                        $('#btn_control_inventario').hide();
                        $('#btn_pedido_sugerido').hide();
    
                    }
                
                Promise.all([
                    cargar_ruta_desarrollo()
                ])
                .then(respuestas => {
                    console.log(arrg_Credls['ruta_desarrollador']);
                    resolve(1);
                })
                .catch(error => {
                    console.log(error);
                });
            }else{
                resolve(1);
            }
            if( elements[0].passwor_status == '1'){
                $("#m-cambio-contrasena").modal("toggle");
            }
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function cargar_ruta_desarrollo(){
    return new Promise(function function_name(resolve, reject) {
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_ruta_desarrollo', "readonly");
        var object = data.objectStore('tbl_ruta_desarrollo');
        var elements = [];
        object.openCursor().onsuccess = function (e) {
            var result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
        }
        data.oncomplete = function () {
            if(parseInt(Object.entries(elements).length) != 0){
                arrg_Credls['ruta_desarrollador'] = elements[0].Ru_Id;
               // console.log('ru_id es '+arrg_Credls['ruta_desarrollador']);
                resolve(1);
            }
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
// ----------------------------------------------------------------------------------------
function initControls(){
    window.location.hash="red";
    window.location.hash="Red" //chrome
    window.onhashchange=function(){window.location.hash="red";}
}
function DB_CantidadUsuarios(){
    cantidad_idx_us = 0;
    var active = dataBaseAppSDV.result;
    var transaction = active.transaction(['tbl_usuarios'], 'readonly');
    var objectStore = transaction.objectStore('tbl_usuarios');
    var countRequest = objectStore.count();
    countRequest.onsuccess = function() {
        cantidad_idx_us = ( countRequest.result > 0 ) ? 1 : 0;
    };
    countRequest.oncomplete = function() {
    };
    countRequest.onerror = function(event) {
        cantidad_idx_us = 0;
    };
}