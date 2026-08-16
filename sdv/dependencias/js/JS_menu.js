var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
var arrg_error = [];
var warn_on_unload='';
var us_cod = '',usuario = '',us_ID_Ruta = '';
var pais = '';
var canal_usu = '';
var arrg_dataSincro = []; var table = null;
var CuantoExhCola = 0;
var arrgColaReclamos = [];
var CantCola = 0;
var cola_reclamos = 0;
var cola_cti = 0;
var ruta_desarrollador = ''; // -- Ruta seleccionada por el desarrollador antes de sincronizar ---
var Mensaje_Sincronizacion = 'Sincronización exitosa!';
var bandPedido = 0;
var arrg_listas = [
    "tbl_clientes",
    "tbl_productos",
    "tbl_filtros",
    "tbl_tipo_danos",
    "tbl_departamento",
    "tbl_municipio",
    "tbl_condicioncli",
    "tbl_gironegocio",
    "tbl_tfacturacion",
    "tbl_tpuntoventa",
    "tbl_exhifacturados",
    "tbl_parametros",
    "tbl_motivoelim",
    "tbl_exhibidores",
    "tbl_status_exhibidores",
    "tbl_tipo_exhibidores",
    "tbl_control_inventario",
    "tbl_reclamosingre",
    "tbl_ste_tipo_motivos",
    "tbl_ste_motivo",
    "tbl_PedSug_PedidosDet",
    "tbl_PedSug_Motivo",
    "tbl_items_check_list_vehiculo",
    "tbl_vehiculo",
    "tbl_tipo_licencia",
    "tbl_parametros_vnt",
    "tbl_referencia",
    "tbl_proveedores",
    "tbl_mercado",
    "tbl_tareas",
    "tbl_oportunidades"
];
var notificaciones = [];
var tipo_usuario;
function DB_validar_vehiculo(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_items_check_list_vehiculo', "readonly");
    var object = data.objectStore('tbl_items_check_list_vehiculo');
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

        if(Object.entries(elements).length == 0){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor sincronizar<br> Faltan datos que descargar...</h5>',
                confirmButtonText:'Ok'
            }); 
        }else{
            location.href = "vehiculo";         
        }

    };
    data.onerror = function (e) {
        Swal.fire({
            title: 'Error inesperado!',
            type: 'error',
            html:'<h5>Error en verificar sincronización [ Clientes Nuevos ]</h5>',
            confirmButtonText:'Ok'
        });
    }
}

//
function DB_validar_mercado(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_items_check_list_vehiculo', "readonly");
    var object = data.objectStore('tbl_items_check_list_vehiculo');
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
        if(Object.entries(elements).length == 0){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor sincronizar<br> Faltan datos que descargar...</h5>',
                confirmButtonText:'Ok'
            }); 
        }else{
            location.href = "mercado";         
        }
    };
    data.onerror = function (e) {
        Swal.fire({
            title: 'Error inesperado!',
            type: 'error',
            html:'<h5>Error en verificar sincronización [ Clientes Nuevos ]</h5>',
            confirmButtonText:'Ok'
        });
    }
}
//

//Codigo para las tareas 
//Codigo para las tareas 
//Codigo para las tareas 
//Codigo para las tareas 
function DB_ValidaSiYaSincronizo(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_departamento', "readonly");
    var object = data.objectStore('tbl_departamento');
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
        if(Object.entries(elements).length == 0){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor sincronizar<br> Faltan datos que descargar...</h5>',
                confirmButtonText:'Ok'
            }); 
        }else{
            location.href = "clientes";
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
            $.when( $("#o-cliente-nuevo").stop(true,true).hide() ).done(function( x ) {
                $.when( $("#carga_clienteN").stop(true,true).show() ).done(function( x ) {
                });
            });    
        }
    };
    data.onerror = function (e) {
        Swal.fire({
            title: 'Error inesperado!',
            type: 'error',
            html:'<h5>Error en verificar sincronización [ Clientes Nuevos ]</h5>',
            confirmButtonText:'Ok'
        });
    }
}
/* **************************************************************** */
function DB_validarSincronizacion(opcion){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_clientes', "readonly");
    var object = data.objectStore('tbl_clientes');
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
        if(Object.entries(elements).length == 0){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor sincronizar<br> Faltan datos que descargar...</h5>',
                confirmButtonText:'Ok'
            }); 
        }else{
            if(opcion == "reclamos"){
                location.href = "reclamos";
            }else {
                location.href = "control_inventario";
            }
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
            $.when( $("#o-cliente-nuevo").stop(true,true).hide() ).done(function( x ) {
            });    
        }
    };
    data.onerror = function (e) {
        Swal.fire({
            title: 'Error inesperado!',
            type: 'error',
            html:'<h5>Error en verificar sincronización [ Clientes Nuevos ]</h5>',
            confirmButtonText:'Ok'
        });
    }
}
function DB_ValidaUsuarioXClienteExh(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_clientes', "readonly");
    var object = data.objectStore('tbl_clientes');
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
        if(Object.entries(elements).length == 0){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor sincronizar<br> Itinerario de clientes vacío...<br>Puede que ya no tengas clientes por censar</h5>',
                confirmButtonText:'Ok'
            }); 
        }else{
            if(arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155){
                var ruta_logueada = arrg_Credls['ruta_desarrollador'];
                var nombre_RutaItinerario = arrg_Credls['ruta_desarrollador'];
            }else{
                var ruta_logueada = $("#uslogin").text();
                ruta_logueada = ruta_logueada.toString();
                var nombre_RutaItinerario = elements[0].Usu_Ru_Id;
            }
            nombre_RutaItinerario = nombre_RutaItinerario.toString();
            if(ruta_logueada == nombre_RutaItinerario){
                location.href = "encuesta-exhibidores";
                $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
                $.when( $("#encuesta-de-exhibidores").stop(true,true).hide() ).done(function( x ) {
                    $.when( $("#carga_encuestaexhi").stop(true,true).show() ).done(function( x ) {
                    });
                });
            }else{
                Swal.fire({
                    title: 'Aviso!',
                    type: 'warning',
                    html:'<h5>Por favor sincronizar, no se encontro itinerario de clientes...</h5>',
                    confirmButtonText:'Ok'
                });
            }
        }
    };
    data.onerror = function (e) {
        Swal.fire({
            title: 'Error!',
            type: 'error',
            html:'<h5>No se pudo verificar usuario...</h5>',
            confirmButtonText:'Ok'
        });
    }
}
function DB_ValidaUsuarioXClienteACTU(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_clientes', "readonly");
    var object = data.objectStore('tbl_clientes');
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
        if(Object.entries(elements).length == 0){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor sincronizar<br> Itinerario de clientes vacío...</h5>',
                confirmButtonText:'Ok'
            }); 
        }else{
            if(arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155){
                var ruta_logueada = arrg_Credls['ruta_desarrollador'];
                var nombre_RutaItinerario =  arrg_Credls['ruta_desarrollador'];
            }else{
                var ruta_logueada = $("#uslogin").text();
                ruta_logueada = ruta_logueada.toString();
                var nombre_RutaItinerario = elements[0].Usu_Ru_Id;
            }
            nombre_RutaItinerario = nombre_RutaItinerario.toString();
            nombre_RutaItinerario = nombre_RutaItinerario.replace(".", "");
            nombre_RutaItinerario = nombre_RutaItinerario.replace(".", "");
            if(ruta_logueada == nombre_RutaItinerario){
                var FechaA = fechaDispositivo();
                var FechaB = elements[0].Fecha_Sincronizacion+' 07:00:00';
                FechaA     = FechaA.substring(0,10)+' 07:00:00';
                FechaA     = new Date(FechaA);
                FechaB     = new Date(FechaB);
                if( FechaB.getTime() < FechaA.getTime() ){
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'warning',
                        html:'<h5>Por favor sincronizar, datos no actualizados...</h5>',
                        confirmButtonText:'Ok'
                    });
                }else{
                    if(arrg_Credls['pais'] == 'EL SALVADOR' && arrg_Credls['canal'] == 'MAYOREO'){
                        location.href = "actualizar_datos";
                    }else if(arrg_Credls['pais'] == 'EL SALVADOR' && arrg_Credls['canal'] == 'GUDAFF'){
                        location.href = "actualizar_datos";
                    }else{
                        location.href = "actualizacion-clientes";
                    }
                    $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
                    $.when( $("#o-actualizar-coordenada").stop(true,true).hide() ).done(function( x ) {
                        $.when( $("#carga_actualizarco").stop(true,true).show() ).done(function( x ) {
                        });
                    });
                }
            }else{
                Swal.fire({
                    title: 'Aviso!',
                    type: 'warning',
                    html:'<h5>Por favor sincronizar, no se encontro itinerario de clientes...</h5>',
                    confirmButtonText:'Ok'
                });
            }
        }
    };
    data.onerror = function (e) {
        Swal.fire({
            title: 'Error!',
            type: 'error',
            html:'<h5>No se pudo verificar usuario...</h5>',
            confirmButtonText:'Ok'
        });
    }
}
function DB_ValPedidoSugerido(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_PedSug_PedidosDet', "readonly");
    var object = data.objectStore('tbl_PedSug_PedidosDet');
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
        if(Object.entries(elements).length == 0){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor sincronizar para descargar el pedido óptimo</h5>',
                confirmButtonText:'Ok'
            }); 
        }else{
            var FechaTelefono    = fechaDispositivo()
            var FechaTelefonoCor = FechaTelefono.substring(10,0);
            FechaTelefonoCor     = FechaTelefonoCor.replace(/-/g, '/');
            var FechaDB    = elements[0].FechaPedido;
            var FechaDBCor = FechaDB.substring(10,0);
            FechaDBCor     = FechaDBCor.replace(/-/g, '/');
            FechaTelefonoCor = new Date(FechaTelefonoCor);
            FechaDBCor = new Date(FechaDBCor);
            if( arrg_Credls['NombreRuta'] != elements[0].RutaId ){
                Swal.fire({
                    title: 'Aviso!',
                    type: 'info',
                    html:'<h5>Por favor sincronizar para descargar el pedido óptimo</h5>',
                    confirmButtonText:'Ok'
                });
            }else if( elements[0].Id == 5555555 ){
                Swal.fire({
                    title: 'Aviso!',
                    type: 'info',
                    html:'<h5>No hay pedido de esta ruta para este día...</h5>',
                    confirmButtonText:'Ok'
                });
            }else if( elements[0].Id == 7777777 ){
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    Swal.fire({
                        type: 'info',
                        title: 'Aviso!',
                        html:'<span style="color:red;font-weight:500;">Por favor informar a sistemas de ventas, el pedido de ahora está duplicado...</span>',
                        showConfirmButton: true,
                    }).then((result) => {
                    });
                });
            }else if( elements[0].Id == 2222222 ){
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    Swal.fire({
                        type: 'info',
                        title: 'Aviso!',
                        html:'<span style="color:red;font-weight:500;">Por favor informar a sistemas de ventas, se encontro SKU duplicado en el pedido de ahora...</span><br><br><span>'+elements[0].DescripcionProd+'</span>',
                        showConfirmButton: true,
                    }).then((result) => {
                    });
                });
            }else if( FechaTelefonoCor.getTime() != FechaDBCor.getTime() ){
                Swal.fire({
                    title: 'Aviso!',
                    type: 'info',
                    html:'<h5>Por favor sincronizar para descargar el pedido óptimo<br>(Fechas no coinciden)</h5>',
                    confirmButtonText:'Ok'
                });
            }else{
                $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
                $.when( $("#btn_pedido_sugerido").stop(true,true).hide() ).done(function( x ) {
                    $.when( $("#carga_pedidosugerido").stop(true,true).show() ).done(function( x ) {
                        location.href = "pedido_sugerido";
                    });
                });
                
            }
        }
    };
    data.onerror = function (e) {
        Swal.fire({
            title: 'Error!',
            type: 'error',
            html:'<h5>No se pudo verificar usuario...</h5>',
            confirmButtonText:'Ok'
        });
    }
}
$(document).ready(function(e){
    DB_IniciarCPSesion(1);
    $(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
        $.when( $("#o-cliente-nuevo").stop(true,true).hide() ).done(function( x ) {
            $.when( $("#carga_clienteN").stop(true,true).show() ).done(function( x ) {
            });
        });
    });
    $('#o-cliente-nuevo').on('click',function() {
        DB_ValidaSiYaSincronizo();
    });
    $('#o-actualizar-coordenada').on('click',function() {
        DB_ValidaUsuarioXClienteACTU();
    });
    $('#encuesta-de-exhibidores').on('click',function() {
        DB_ValidaUsuarioXClienteExh();
    });
    $('#sincronizar').on('click',function() {
        Swal.fire({
            title: '¿Éstas seguro de sincronizar?',
            text: "",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si!',
            cancelButtonText: 'Cancelar!'
        }).then((result) => {
            if(result.value){
                sincronizar_completa();
            }else{
            }
        });
    });
    $('#configuracion').on('click',function() {
        registros_colaClientesN();
    });
    $('#salir-sdv').on('click',function() {
        location.href = "../../sdv/";
    });
    $('#btnNuevoReclamo').on('click',function(event) {
        DB_validarSincronizacion("reclamos");
    });
    $('#btnProductoReclamos').on('click',function(event) {
        location.href = "reclamos-productos";
    });
    $('#btn_control_inventario').on('click',function(event) {
        DB_validarSincronizacion("cti");
    });
    $('#btn_pedido_sugerido').on('click',function(event) {
        DB_ValPedidoSugerido();
    });
    $(document).on("keyup", "#txtPassNuevo", function() {
        Val_input_Pass_Nuevo();
    });
    $(document).on("keyup", "#txtPassNuevoR", function() {
        Val_input_Pass_Nuevo();
    });
    $('#btn_recepcion_vehiculo').on('click',function(event) {
        DB_validar_vehiculo();     
    });  
    $('#btn_control_mercado').on('click',function(event) {
        DB_validar_mercado();     
    }); 
});
function sincronizar_completa(){
    if(arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155){
        if(arrg_Credls['ls_rutas'].length != 0 && $('#slc_ruta_desarrollador').val() != '' && $('#slc_ruta_desarrollador').val() != null){
            procesar_sincronizacion($('#slc_ruta_desarrollador').val())
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'<h5>Por favor seleccione una ruta para sincronizar</h5>',
                confirmButtonText:'Ok'
            }); 
        }
    }else{
        procesar_sincronizacion('')
    }
}
function procesar_sincronizacion(ruta_desa) {
    var FechaTelefono = fechaDispositivo();
    ruta_desarrollador = $("#slc_ruta_desarrollador").val();
    $.when( $("#menu_boton").stop(true,true).hide() ).done(function( x ) {
        $.when( $("#sincronizando_loading").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'menu/sincronizar',
                type     : 'POST',
                dataType : 'JSON',
                data     : 
                {    
                    us_cod              : us_cod,
                    us_ID_Ruta          : us_ID_Ruta,
                    pais                : pais,
                    canal_usu           : canal_usu,
                    tipo_usuario        : arrg_Credls['privilegio'],
                    ruta_desarrollador  : ruta_desarrollador,
                    Fecha_Telefono      : FechaTelefono,
                    NombreRuta          : arrg_Credls['NombreRuta']
                },
                timeout  : 45777
            }).done(function(_resp){

                tipo_usuario = arrg_Credls['privilegio'];

             /*   if(tipo_usuario == 155)
                {

                    $('#encuesta-de-exhibidores').hide();
                    $('#o-cliente-nuevo').hide();
                    $('#btnNuevoReclamo').hide();
                    $('#btn_control_inventario').hide();
                    $('#btn_pedido_sugerido').hide();

                }
                */

                if(_resp.parametros.rs == true){
                    arrg_dataSincro['tbl_clientes']           = _resp.parametros.lsclientes;
                    arrg_dataSincro['tbl_productos']          = _resp.parametros.lsproductos;
                    arrg_dataSincro['tbl_filtros']            = _resp.parametros.lsfilstros;
                    arrg_dataSincro['tbl_tipo_danos']         = _resp.parametros.lstipoDanos;
                    arrg_dataSincro['tbl_departamento']       = _resp.parametros.ldepartamento;
                    arrg_dataSincro['tbl_municipio']          = _resp.parametros.lmunicipio;
                    arrg_dataSincro['tbl_condicioncli']       = _resp.parametros.lcondicioncli;
                    arrg_dataSincro['tbl_gironegocio']        = _resp.parametros.lgironegocio;
                    arrg_dataSincro['tbl_tfacturacion']       = _resp.parametros.ltfacturacion;
                    arrg_dataSincro['tbl_tpuntoventa']        = _resp.parametros.ltpuntoventa;
                    arrg_dataSincro['tbl_exhifacturados']     = _resp.parametros.lsexhfacturados;
                    arrg_dataSincro['tbl_parametros']         = _resp.parametros.lsparametros;
                    arrg_dataSincro['tbl_motivoelim']         = _resp.parametros.lsmotivoselim;
                    arrg_dataSincro['tbl_exhibidores']        = _resp.parametros.lexhibidor;
                    arrg_dataSincro['tbl_status_exhibidores'] = _resp.parametros.ls_det_exhibidores;
                    arrg_dataSincro['tbl_tipo_exhibidores']   = _resp.parametros.ls_tipo_exhibidores;
                    arrg_dataSincro['tbl_control_inventario'] = _resp.parametros.ls_cti_ingresados;
                    arrg_dataSincro['tbl_reclamosingre']      = _resp.parametros.ls_reclamos_enproceso;
                    arrg_dataSincro['tbl_ste_tipo_motivos']   = _resp.parametros.ls_ste_tipo_motivos;
                    arrg_dataSincro['tbl_ste_motivo']         = _resp.parametros.ls_ste_motivos;
                    arrg_dataSincro['tbl_PedSug_PedidosDet']  = _resp.parametros.ls_pedido_sugerido;
                    arrg_dataSincro['tbl_PedSug_Motivo']      = _resp.parametros.ls_pedidos_motivos;
                    arrg_dataSincro['tbl_items_check_list_vehiculo'] = _resp.parametros.ls_checklist_vehiculo;
                    arrg_dataSincro['tbl_vehiculo']             = _resp.parametros.ls_vehiculo;
                    arrg_dataSincro['tbl_tipo_licencia']        = _resp.parametros.ls_tipo_licencia;
                    arrg_dataSincro['tbl_parametros_vnt']       = _resp.parametros.ls_parametros_vnt;
                    arrg_dataSincro['tbl_referencia']            = _resp.parametros.l_referencias;
                    arrg_dataSincro['tbl_proveedores']            = _resp.parametros.l_proveedores;
                    arrg_dataSincro['tbl_referencia']           = _resp.parametros.l_referencias;
                    arrg_dataSincro['tbl_mercado']              = _resp.parametros.ls_mercado;
                    arrg_dataSincro['tbl_tareas']               = _resp.parametros.ls_tareas;
                    arrg_dataSincro['tbl_oportunidades']         = _resp.parametros.ls_oportunidades;
                    notificaciones = arrg_dataSincro['tbl_tareas'];
                    
                }else{
                    arrg_dataSincro = [];
                }
            }).always(function(_resp, textStatus, errorThrown) {
                if (textStatus == "success") {
                    
                    if(_resp.parametros.rs == true){
                        DB_limpiarTablasFiltros(arrg_dataSincro,arrg_listas);
                        if(ruta_desa != ''){
                            Promise.all([,
                                DB_limpiar_ruta_des(ruta_desa)
                            ]).then(respuestas => {
                               // console.log('Estas es la respuesta:',respuestas);
                                BD_guardar_ruta(ruta_desa);
                            }).catch(error =>{
                                console.log(error);
                            });
                        }
                        AContrasenaUs(_resp.parametros.pass_on_off);
                        arrg_dataSincro = [];
                    }else{
                        $.when( $("#sincronizando_loading").stop(true,true).hide() ).done(function( x ) {
                            $.when( $("#menu_boton").stop(true,true).show() ).done(function( x ) {
                                console.log('ERROR CAMPOS');
                                arrg_dataSincro = [];
                                Swal.fire({
                                    title: 'Aviso Importante!',
                                    type: 'info',
                                    html:'<h5>No se pudo sincronizar...</h5>',
                                    confirmButtonText:'Ok'
                                });
                            });
                        });
                    }
                }else{
                    $.when( $("#sincronizando_loading").stop(true,true).hide() ).done(function( x ) {
                        $.when( $("#menu_boton").stop(true,true).show() ).done(function( x ) {
                            _ajax_error_envioOffline(_resp.status,_resp.readyState,_resp.statusText);
                            
                        });
                    });
                }
            });
        });
    });
}

function notificacion(array) {
    let contador = 0;

    array.forEach(elemento => {
        if (elemento.asignado_a === us_ID_Ruta && elemento.estado ==1) {
            contador++;
        }
    });

    // Mostrar alerta con SweetAlert2 si hay coincidencias
    if (contador > 0 && tipo_usuario ==2) {
        Swal.fire({
            title: '¡Tienes ' + contador + ' tareas asignadas!',
            text: 'Ve a Evaluación de mercado/Tareas', // Texto adicional
            type: 'info', // Tipo de alerta cambiado a 'alert'
            showConfirmButton: true,
        });
    }
}

function AContrasenaUs(pass_on_off) {
    var actived = dataBaseAppSDV.result;
    const transaction = actived.transaction(['tbl_usuarios'], 'readwrite');
    const objectStore = transaction.objectStore('tbl_usuarios');
    objectStore.openCursor().onsuccess = function (event) {
        const cursor = event.target.result;
        if (cursor) {
            // if (cursor.value.Ste_token_espec === token) {
                const updateData = cursor.value;
                updateData.passwor_status = pass_on_off;
                const request = cursor.update(updateData);
                request.onsuccess = function () {
                };
            // };
            cursor.continue();
        } else {
        }
    };
}
function BD_guardar_ruta(ruta_des){
    return new Promise(function(resolve, reject){
        var ingresar = [];
        if(ruta_des != ''){
            arrg_Credls['ruta_desarrollador'] = ruta_des;
            ingresar.push({'Ru_Id': ruta_des});
            var active = dataBaseAppSDV.result;
            var transaction = active.transaction('tbl_ruta_desarrollo', "readwrite");
            var objectStore = transaction.objectStore('tbl_ruta_desarrollo');
            var request = objectStore.put(ingresar[0]);
            request.onerror = function (e) {
                resolve(0);
            };
            request.onsuccess = function(event) {
                resolve(1);
            };
        }
    });
}
function DB_limpiar_ruta_des(ruta_des) {
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_ruta_desarrollo', "readwrite");
        var objectStore = data.objectStore('tbl_ruta_desarrollo');
        var objectStoreRequest = objectStore.clear();
        objectStoreRequest.onsuccess = function(event) {
            resolve(1)
        };
        objectStoreRequest.onerror = function(event) {
            console.log('Ocurrio un error');
            resolve(0)
        };
    });
}
function DB_GuardarParamFiltros(arrg_datos,arrg_items,ia) {
    var TotalItems = 0;
    TotalItems = arrg_items.length;
    // console.log(TotalItems);
    // console.log(ia);
    if(ia < TotalItems){
        var InfoTablas = [];
        InfoTablas = arrg_datos[arrg_items[ia]];
        var active = dataBaseAppSDV.result;
        var transaction = active.transaction([arrg_items[ia]], "readwrite");
        var objectStoreG = transaction.objectStore(arrg_items[ia]);
        if( CantCola = parseInt(Object.keys(arrg_datos[arrg_items[ia]]).length) > 0 ){
            if( objectStoreG.name == 'tbl_PedSug_PedidosDet' ){
                if( InfoTablas[0].Id == 7777777 ){
                    bandPedido = 1;
                    console.log('error');
                }else{
                    bandPedido = 0;
                }
            }
        }
        return Promise.all(InfoTablas.map( InfoTablas => {
            // console.log(arrg_items[ia]);
            // console.log(InfoTablas);
            return objectStoreG.put(InfoTablas); 
        })).then( function () { 
            DB_GuardarParamFiltros(arrg_datos,arrg_items,ia + 1);
            return transaction.complete; 
        });
    }else{
        $.when( $("#sincronizando_loading").stop(true,true).hide() ).done(function( x ) {
            $.when( $("#menu_boton").stop(true,true).show(100) ).done(function( x ) {
                // console.log('Carga de datos completada!');
                if( bandPedido == 1 ){
                    Swal.fire({
                        type: 'info',
                        title: 'Sincronización exitosa!',
                        html:'<span style="color:red;font-weight:500;">Por favor informar a sistemas de ventas, el pedido de ahora está duplicado...</span>',
                        showConfirmButton: true,
                    }).then((result) => {
                        
                    });
                }else{
                    Swal.fire({
                    title: 'Sincronización exitosa!',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    notificacion(notificaciones);
                });

                }
            });
        });
    }
}
function DB_limpiarTablasFiltros(arrg_datos, arrg_items) {
    var arrg_listas_new = [];
    CantCola = 0;
    Promise.all([
        VerifyExhCola(),
        consultar_cola_reclamos(),
        consultar_cola_cti()
    ])
    .then(respuestas => {
        if( CuantoExhCola > 0){
            var arrg_listas_new = [
                "tbl_clientes",
                "tbl_productos",
                "tbl_filtros",
                "tbl_tipo_danos",
                "tbl_departamento",
                "tbl_municipio",
                "tbl_condicioncli",
                "tbl_gironegocio",
                "tbl_tfacturacion",
                "tbl_tpuntoventa",
                "tbl_exhifacturados",
                "tbl_parametros",
                "tbl_motivoelim",
                "tbl_exhibidores",
                "tbl_tipo_exhibidores",
                "tbl_control_inventario",
                "tbl_ste_tipo_motivos",
                "tbl_ste_motivo",
                "tbl_PedSug_Motivo",
                "tbl_items_check_list_vehiculo",
                "tbl_vehiculo",
                "tbl_tipo_licencia",
                "tbl_parametros_vnt",
                "tbl_referencia",
                "tbl_proveedores",
                "tbl_mercado",
                "tbl_tareas",
                "tbl_oportunidades"
            ];
            DB_LimpiarTablas(arrg_datos, arrg_listas_new,0);
        }else{
            arrg_listas_new = [
                "tbl_clientes",
                "tbl_productos",
                "tbl_filtros",
                "tbl_tipo_danos",
                "tbl_departamento",
                "tbl_municipio",
                "tbl_condicioncli",
                "tbl_gironegocio",
                "tbl_tfacturacion",
                "tbl_tpuntoventa",
                "tbl_exhifacturados",
                "tbl_parametros",
                "tbl_motivoelim",
                "tbl_exhibidores",
                "tbl_status_exhibidores",
                "tbl_tipo_exhibidores",
                "tbl_control_inventario",
                "tbl_reclamosingre",
                "tbl_ste_tipo_motivos",
                "tbl_ste_motivo",
                "tbl_PedSug_PedidosDet",
                "tbl_PedSug_Motivo",
                "tbl_items_check_list_vehiculo",
                "tbl_vehiculo",
                "tbl_tipo_licencia",
                "tbl_parametros_vnt",
                "tbl_referencia",
                "tbl_proveedores",
                "tbl_mercado",
                "tbl_tareas",
                "tbl_oportunidades"
            ];
            DB_LimpiarTablas(arrg_datos, arrg_listas_new,0);
        }
    })
    .catch(error => { 
        console.log('ERROR AL SINCRONIZAR');
    });
}
function VerifyExhCola() {
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
                CuantoExhCola += parseInt(Object.keys(dataResult).length);
                resolve(1);
            };
        }
        transaccion.onerror = function () {
            reject(0);
        };
    });
}
function DB_LimpiarTablas(arrg_datos,arrg_items,ik) {
    var TotalItems = 0;
    TotalItems = arrg_items.length;
    if(ik < TotalItems){
        var active = dataBaseAppSDV.result;
        var data = active.transaction(arrg_items[ik], "readwrite");
        var objectStore = data.objectStore(arrg_items[ik]);
        var objectStoreRequest = objectStore.clear();
        objectStoreRequest.onsuccess = function(event) {
            DB_LimpiarTablas(arrg_datos,arrg_items,ik + 1);
            //console.log(arrg_datos);
        };
        objectStoreRequest.onerror = function(event) {
            console.log('Error en el indice = '+ik);
        };
    }else{
        DB_GuardarParamFiltros(arrg_datos,arrg_items,0);
    }
}
function registros_colaClientesN(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_clingresados', "readonly");
    var object = data.objectStore('tbl_clingresados');
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
        if(arreg_offline.length > 0){
            $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                    enviar_regis_BACKUP(0,arreg_offline);
                });
            });  
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'Base de datos vacia...',
                confirmButtonText:'Ok'
            });
        }
    };
}
function registros_colaClientesAC(){
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_clientesactuingre', "readonly");
    var object = data.objectStore('tbl_clientesactuingre');
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

        if(arreg_offline.length > 0){
            $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                    enviar_regis_BACKUPAC(0,arreg_offline);
                });
            });  
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:'Base de datos vacia...',
                confirmButtonText:'Ok'
            });
        }
    };
}
function enviar_regis_BACKUP(indice,elements){
    if(indice < elements.length){
        $.ajax({
            url:'clientes/agregar-backup',
            type:"POST",
            data:elements[indice],
            dataType: "JSON",
            timeout:34777
            }).done(function(_resp) {
            }).always(function(_resp, textStatus, errorThrown) {
                var indic = 0;
                indic = indice + 1;
                if (textStatus == "success") {
                    if(_resp.rs == true){
                        alertify.success('Registro enviado exitosamente! [ '+indic+' ]');
                        enviar_regis_BACKUP(indice + 1,arreg_offline);
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                                Swal.fire({
                                    title: 'Aviso!',
                                    type: 'warning',
                                    html:_resp.errores,
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
function enviar_regis_BACKUPAC(indice,elements){
    if(indice < elements.length){
        $.ajax({
            url:'clientes/actualizar-backup',
            type:"POST",
            data:elements[indice],
            dataType: "JSON",
            timeout:34777
            }).done(function(_resp) {
            }).always(function(_resp, textStatus, errorThrown) {
                var indic = 0;
                indic = indice + 1;
                if (textStatus == "success") {
                    if(_resp.rs == true){
                        alertify.success('Registro enviado exitosamente! [ '+indic+' ]');
                        enviar_regis_BACKUPAC(indice + 1,arreg_offline);
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                                Swal.fire({
                                    title: 'Aviso!',
                                    type: 'warning',
                                    html:_resp.errores,
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
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    filename = filename?filename+'.xls':'clientes_recuperados.xls';
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
// ----- OBTENER RECLAMOS EN COLA --------------------------------------------------------------------
function consultar_cola_reclamos(){
    cola_reclamos = 0;
    var conta_cola_soli = {};
    return new Promise(function(resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_reclamosingre', 'readonly'),
        store = transaccion.objectStore('tbl_reclamosingre'),
        indice = store.index('by_cola'),
        cursor = indice.openCursor('SI')
        cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                cola_reclamos = parseInt(Object.keys(dataResult).length);
                resolve(1);
            };
        }
        transaccion.onerror = function() {
            reject(0);
        };
    });
}
// ----- OBTENER CTI EN COLA -------------------------------------------------------------------------
function consultar_cola_cti(){
    cola_cti = 0;
    return new Promise(function(resolve, reject) {
        var dataResult  = [];
        var active      = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_control_inventario', 'readonly'),
        store           = transaccion.objectStore('tbl_control_inventario'),
        indice          = store.index('by_Cola'),
        cursor          = indice.openCursor('SI')
        cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                cola_cti += parseInt(Object.entries(dataResult).length);
                resolve(1);
            };
        }
        transaccion.onerror = function() {
            reject(0);
        };
    });
}
// ---------------------------------------------------------------------------------------------------
function Val_input_Pass_Nuevo(){
    var PassActual  = '',PassNuevo = '', PassNuevoR = '';
    var B_longitud = 0,B_mayuscula = 0,B_minuscula = 0, B_numero = 0;
    PassActual = String($("#txtPassactual").val());
    PassNuevo  = String($("#txtPassNuevo").val());
    PassNuevoR = String($("#txtPassNuevoR").val());
    var strings = PassNuevo; var i=0; var character='';
    while ( i < strings.length ){
        character = strings.charAt(i);
        if (!isNaN(character * 1)){
            B_numero = 1;
        }else{
            if (character == character.toUpperCase()) {
                B_mayuscula = 1;
            }
            if (character == character.toLowerCase()){
                B_minuscula = 1;
            }
        } i++;
    }
//-----------------------------------------------------
        var Control_B = 0;
        if( PassNuevo.length >= 8 ){
            B_longitud = 1;
            $("#C_ocho").attr("style","color:#18B244;");
        }else{
            B_longitud = 0;
            $("#C_ocho").attr("style","color:#F71313;");
        }
        if( B_mayuscula == 1 )
            $("#C_mayu").attr("style","color:#18B244;");
        else
            $("#C_mayu").attr("style","color:#F71313;");
        if( B_minuscula == 1 )
            $("#C_minu").attr("style","color:#18B244;");
        else
            $("#C_minu").attr("style","color:#F71313;");
        if( B_numero == 1 )
            $("#C_nume").attr("style","color:#18B244;");
        else
            $("#C_nume").attr("style","color:#F71313;");
    // }
//-----------------------------------------------------
    return Control_B = ( B_longitud + B_numero + B_mayuscula + B_minuscula ) == 4 ? 0 : 1 ;
}
// CAMBIO DE CONTRASEÑAS 16/02/2023
function GuardarPassNuevos(){
    var FechaTelefono = fechaDispositivo();
    var dta = $("#form_actualizar_pass").serializeArray();
    dta.push({name: 'us_cod', value: us_cod});
    dta.push({name: 'usuario', value: arrg_Credls['usuario']});
    if( Val_input_Pass_Nuevo() == 0 ){
        var PassNuevo = '', PassNuevoR = '';
        PassNuevo  = String($("#txtPassNuevo").val());
        PassNuevoR = String($("#txtPassNuevoR").val());
        if(PassNuevo == PassNuevoR){
            $.when($(".carga-class").stop(true, true).show()).done(function(x) {
                $.ajax({
                    url      : 'menu/contrasena_nueva',
                    type     : 'POST',
                    dataType : 'JSON',
                    data     : dta,
                    timeout  : 45777
                }).always(function(_resp,textStatus, errorThrown){
                    if (textStatus == "success") {
                        if(_resp.Resultado == true){
                            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Cambios guardados con exito!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    $("#m-cambio-contrasena").modal("hide");
                                    editar_registro_local(us_cod);
                                });
                             });
                        }else{
                            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                                Swal.fire({
                                    title: 'Aviso Importante!',
                                    type: 'info',
                                    html:_resp.Mensaje,
                                    confirmButtonText:'Ok'
                                });
                            });
                        }
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            _ajax_error_envioOffline(_resp.status,_resp.readyState,_resp.statusText);
                        });
                    }
                });
            });
        }else{
            Swal.fire({
                title: 'Aviso Importante!',
                type: 'info',
                html:'Las contraseñas no coinciden',
                confirmButtonText:'Ok'
            });
        }
    }else{
        Swal.fire({
            title: 'Aviso Importante!',
            type: 'info',
            html:'La contraseña debe cumplir con lo sugerido.',
            confirmButtonText:'Ok'
        });
    }
}
function mostrarPassword(id){
    if(!$("#txt"+id).hasClass('passwordClas')){
        $("#txt"+id).removeClass('passwordNo');
        $("#txt"+id).addClass('passwordClas');
        $('#icon'+id).removeClass('fa fa-eye').addClass('fa fa-eye-slash');
    }else{
        $("#txt"+id).removeClass('passwordClas');
        $("#txt"+id).addClass('passwordNo');
        $('#icon'+id).removeClass('fa fa-eye-slash').addClass('fa fa-eye');
    }
}
function editar_registro_local(id_usuario) {
    var actived = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_usuarios"], "readwrite").objectStore("tbl_usuarios");
    var request = objectStore.get(id_usuario);
    request.onerror = function(event) {
        // Handle errors!
    };
    request.onsuccess = function(event) {
        // Get the old value that we want to update
        var data = request.result;
        // update the value(s) in the object that you want to change
        data.passwor_status = 0;
        var requestUpdate = objectStore.put(data);
        requestUpdate.onerror = function(event) {
            // Do something with the error
        };
        requestUpdate.onsuccess = function(event) {
            // Success - the data is updated!
        };
    };
}