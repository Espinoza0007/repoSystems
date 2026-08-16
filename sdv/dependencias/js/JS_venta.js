var warn_on_unload          = '';
var precio                  = 0;
var impuesto                = 0;
var nombre_impuesto         = '';
var data_item               = []; // item seleccionado de la tabla
var data_catalogo           = []; // data del catalogo de productos
var data_ids_item           = new Array();
var data_pedido_realizado   = {}; //data de resumen de pedido
var table                   = null;
var data_clientes           = [];
var monto                   = 0;
var valor_impuesto          = 0;
var valor_total             = 0;

$( document ).ready(function() { 
    // carga la informacion del modulo ------------------------   
    iniciar_modulo_venta();

    // cargar tabla clientes ----------------------------------
    $('#m-clientes-venta').on('shown.bs.modal', function (e) {  
        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            ver_tbl_clientes_vnt(data_clientes);
        });
    });

    // cargar tabla catalogo ----------------------------------
    $('#m-catalogo-venta').on('shown.bs.modal', function (e) {  
        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            ver_tbl_catalogo_vnt(data_catalogo);  
        });

    });

    // seleccionar cliente para pedido ------------------------
    $('#tbl-clientes-vnt tbody').on( 'click', 'tr', function () 
    {    
        $('.label-nombre-cliente').html(
            `<h5>${table.row( this ).data().Cli_codigo} - ${table.row( this ).data().Cli_nombre}</h5>`
        );
        $('#txt_nombre_cliente_vnt').val(table.row( this ).data().Cli_nombre);
        $('#txt_id_cliente_vnt').val(table.row( this ).data().Cli_Id);
        $('#txt-codigo-cliente-vnt').val(table.row( this ).data().Cli_codigo);
        $('#m-clientes-venta').modal('toggle');
    });

    // seleccionar un producto del catalogo -------------------
    $('#tbl-catalogo-vnt tbody').on( 'click', 'tr', function () 
    {   
        warn_on_unload = 'cambios sin guardar';
        let id = '';
        id = table_catalogo_vnt.row( this ).data().Catx_Id;
        obtener_item_vnt(id, 0, 'item');
    });

    // validacion nuevo pedido ----------------------------
    $(document).on( 'click', '#btn_nuevo_vnt', function ()
    {
        if($('#txt_id_cliente_vnt').val() == '' || $('#txt_id_cliente_vnt').val() == null){
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html: '<h5>Por favor seleccione un cliente</h5>',
                confirmButtonText: 'Ok'
            });
        }else{
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) { 
                if( Object.entries(data_pedido_realizado).length > 0 ){
                    obtener_resumen_pedido(data_ids_item).done(function (respuesta) {
                        data_pedido_realizado = respuesta;
                    });
                }else{
                    limpiar_pedido('consulta');
                }           
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $('.panel-btn-vnt').html(`
                        <div class="form-group btn-01">
                            <button type="button" class="btn btn-vnt-01 btn-block" data-toggle="modal" data-target="#m-catalogo-venta" style="margin-top:3%;">
                                <span class="fas fa-search-plus fa-lg" style="font-size: 25px;"></span><span style="font-size: 16px;"><br> <b>SELECCIONAR PRODUCTO</b></span>
                            </button>
                        </div>
                    `);
                    $('.footer-pedido-vnt').append(`
                        <button type="button" class="btn btn-vnt-01" id="btn-guardar-cambios"><i class="fas fa-check"></i> Guardar cambios</button>
                    `);
                    $('#form-venta-pedido').show();
                    $('#m-pedido-vnt').modal('toggle');

                    $.when($("#m-pedido-vnt").stop(true, true).modal('show')).done(function(x) {
                        // if( data_ids_item.length == 0){
                            Swal.fire({
                              html: `<h5>¿Desea tomar el pedido sugerido?</h5><br>
                              <p style="font-size: 14px; text-align: center;">Se cargará el pedido sugerido para este cliente</p>`,
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Sí, cargar',
                              cancelButtonText: 'No',
                              target: document.getElementById('m-pedido-vnt')                            
                            }).then((result) => {
                                if (result.value) {
                                    $('.carga-class').show();
                                    obtener_pedido_sugerido($('#txt_id_cliente_vnt').val());
                                }
                            })
                        // }
                    });
                });
            });
        }
    });

    // validacion consultar pedidos ---------------------------
    $(document).on( 'click', '#btn_consultar_vnt', function (){
        $.when( $(".carga-class").stop(true,true).show(20) ).done(function( x ) {
            obtener_pedidos_realizados();
            $('#m-consulta-pedidos-vnt').modal('toggle');
        });
    });

    // calcular monto de producto ------------------
    $( "#txt-cantidad-item-vnt" ).keyup(function() {
        monto           = precio * $(this).val();
        valor_impuesto  = monto * impuesto;
        valor_total     = monto + valor_impuesto;
        $('#label-impuesto-item').html(`<b>${nombre_impuesto} - ${valor_impuesto.toFixed(2)}</b>`);
        $('#label-monto-item-vnt').html(`<b>${monto.toFixed(2)}</b>`);
        $('#label-total-item-vnt').html(`<b>${valor_total.toFixed(2)}</b>`);
    });

    // limipiar resumen de pedido --------------------------------
    $('#m-producto-item-vnt').on('hidden.bs.modal', function (e) {      
        precio          = 0;
        impuesto        = 0;
        nombre_impuesto = 0;
        monto           = 0;
        valor_impuesto  = 0;
        valor_total     = 0;
        $('#label-impuesto-item').html(`<b> 0 </b>`);
        $('#label-monto-item-vnt').html(`<b> 0 </b>`);
        $('#label-total-item-vnt').html(`<b> 0 </b>`);
        $('#txt-cantidad-item-vnt').val('');
    });
    
    // validacion cantidad de producto a agregar ------------------
    $(document).on( 'click', '#btn-agregar-productos', function (){
        let res = validar_campos($('#frm-item-pedido').serializeArray());
        if(res > 0){            
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html: '<h6>Por favor ingrese una cantidad</h6>',
                confirmButtonText: 'Ok',
                width: '70%',
                target: document.getElementById('frm-item-pedido')
            });
        }else{
            if($('#txt-cantidad-item-vnt').val() == 0)
            {
                Swal.fire({
                    title: 'Aviso!',
                    type: 'info',
                    html: '<h6>La cantidad no puede ser 0 <h6>',
                    confirmButtonText: 'Ok',
                    width: '70%',
                    target: document.getElementById('frm-item-pedido')
                });
            }else{
                let resultado = data_ids_item.findIndex( x => x.item == $('#txt-item-id').val());

                if(resultado != -1){
                    data_ids_item[resultado] = {
                        item            : $('#txt-item-id').val(),
                        cantidad        : $('#txt-cantidad-item-vnt').val(),
                        precio          : precio,
                        subtotal        : monto,
                        valor_impuesto  : valor_impuesto,
                        valor_total     : valor_total
                    }
                    $('#m-producto-item-vnt').modal('toggle');                
                    obtener_resumen_pedido(data_ids_item).done(function (respuesta) {
                        data_pedido_realizado = respuesta;
                    });
                }else{
                    data_ids_item.push({
                        item            : $('#txt-item-id').val(),
                        cantidad        : $('#txt-cantidad-item-vnt').val(),
                        precio          : precio,
                        subtotal        : monto,
                        valor_impuesto  : valor_impuesto,
                        valor_total     : valor_total
                    });                    
                    $('#m-producto-item-vnt').modal('toggle');                
                }
            }
        }
    });

    $(document).on( 'click', '#btn-ir-resumen', function (){
        $('#m-catalogo-venta').modal('toggle');
    });

    // cargar el resumen de pedido ----------------------------
    $('#m-catalogo-venta').on('hidden.bs.modal', function (e) {        
        obtener_resumen_pedido(data_ids_item).done(function (respuesta) {
            data_pedido_realizado = respuesta;
        });
    });
    
    $(document).on( 'dblclick', '#tbl-pedido-detalle tr', function (){
        console.log($(this).data('catxid'));
        obtener_item_vnt($(this).data('catxid'), $(this).data('catxcantidad'), 'item'); 
    });

    $(document).on( 'click', '.btn-eliminar-item', function (){
        let item                = $(this).val();
        let new_data_ids_item   = [];

        data_ids_item.forEach(function(val, index) {
            if(val.item != item ){
                new_data_ids_item.push(val);
            }
        });
        data_ids_item = new_data_ids_item;

        obtener_resumen_pedido(data_ids_item).done(function(respuesta){
            data_pedido_realizado = respuesta;
        });
    });   

    $(document).on('click', '#btn-guardar-cambios', function(){
        preparar_pedido_envio(data_pedido_realizado, 'nuevo_pedido');
    });

    $(document).on('click', '.btn-info-item', function(){
        let numero = $(this).val();
        numero = numero.toString();
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {            
            obtener_info_pedido(numero, 'consulta').done(function (respuesta) {
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $('.panel-btn-vnt').html(`
                        <div class="form-group btn-02" style="display:flex;">
                            <button type="button" class="btn btn-vnt btn-anular" style="margin:1% 1% 1% 0%;" value="${numero}">
                                <span class="fas fa-ban fa-lg" style="font-size: 25px;"></span>
                                <span style="font-size: 16px;"><b> ANULAR</b></span>
                            </button>
                            <button type="button" class="btn btn-vnt-01 d-none" style="margin:1% 1% 1% 0%;">
                                <span class="fas fa-pen-square fa-lg" style="font-size: 25px;"></span>
                                <span style="font-size: 16px;"><b> EDITAR</b></span>
                            </button>
                            <!-- <button type="button" class="btn btn-vnt-01" style="margin:1% 1% 1% 0%;" onclick="dibujar_ticket('${numero}').done(function() {
                                    $('#m-impresion-ticket').modal('toggle');
                                });">
                                <span class="fas fa-print fa-lg" style="font-size: 25px;"></span>
                                <span style="font-size: 16px;"><b> IMPRIMIR</b></span>
                            </button> -->

                            <button type="button" class="btn btn-vnt-01" style="margin:1% 1% 1% 0%;" onclick="dibujar_ticket('${numero}')">
                                <span class="fas fa-print fa-lg" style="font-size: 25px;"></span>
                                <span style="font-size: 16px;"><b> IMPRIMIR</b></span>
                            </button>
                        </div> 
                    `);  
                    $('#btn-guardar-cambios').remove();
                    $('#m-pedido-vnt').modal('toggle');
                });
            })
        });
    });

    $(document).on('click', '.btn-anular', function(){
        let numero  = $(this).val();
        numero      = numero.toString();

        $.when($(".carga-class").stop(true, true).show()).done(function(x) {
            Promise.all([
                console.log(numero),
                actualizar_pedidos_local(numero, 'estado', 'ANULADO') 
            ])
            .then(respuestas => {
                // guardar_pedido_bd(0, 'anular', numero);
                preparar_pedido_envio(0, 'anular', numero);
            })
            .catch(error => {
                console.log(error);
            });            
        });
    });

    $('#m-pedido-vnt').on('hidden.bs.modal', function (e) {                
        $('#btn-guardar-cambios').remove();
    });
    
    $(document).on('change', '#slc_estado_pedidos', function(){
        console.log($(this).val());
        $('.carga-class').show();
        if($(this).val() == 'TODOS'){
            obtener_pedidos_realizados();
        }else{
            obtener_pedidos_realizados_estado($(this).val());
        }
    });
    $(document).on('click', '#btn_pedido_sugerido', function(){
        $.when($('.carga-class').stop(true, true).show(20)).done(function(x) {
             $('.panel-btn-vnt').html(`
                <div class="form-group btn-01">
                    <button type="button" class="btn btn-vnt-01 btn-block" data-toggle="modal" data-target="#m-catalogo-venta" style="margin-top:3%;">
                        <span class="fas fa-search-plus fa-lg" style="font-size: 25px;"></span><span style="font-size: 16px;"><br> <b>SELECCIONAR PRODUCTO</b></span>
                    </button>
                </div>
            `);
            $.when($("#m-pedido-vnt").stop(true, true).modal('show')).done(function(x) {
                $('.carga-class').hide();
                Swal.fire({
                  html: `<h5>¿Desea tomar el pedido sugerido?</h5><br>
                  <p style="font-size: 14px; text-align: center;">Se cargará el pedido sugerido de reabastecimiento</p>`,
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Sí, cargar',
                  cancelButtonText: 'No',
                  target: document.getElementById('m-pedido-vnt')                            
                }).then((result) => {
                    if (result.value) {
                        // $('.carga-class').show();
                        // obtener_pedido_sugerido($('#txt_id_cliente_vnt').val());
                        obtener_existencias_ruta();
                    }
                });
            });
        });
    });
});

// carga la informacion de clientes y catalogo ----------------------------------------------------
function iniciar_modulo_venta(){
    Promise.all([
        DB_IniciarCPSesion(),     
    ])
    .then(respuestas => {
        cargar_lista_clientes();    // ---------------- llena el catalogo de clientes    ----------
        cargar_catalogo_venta();    // ---------------- llena el catalogo de productsos  ----------
        // -------------------------------------------- llena el filtro de familias de productos --
        cargar_filtro_familias('tbl_filtros','filtro_familias_vnt','familas-vnt');
        consultar_cola_venta();
    })
    .catch(error => {
        console.log(error);
    });
}

function cargar_catalogo_venta(){
    return new Promise(function(resolve, reject)
    {
        let active      = dataBaseAppSDV.result;
        let data        = active.transaction('tbl_catalogo_venta', "readonly");
        let object      = data.objectStore('tbl_catalogo_venta');
        let elements    = [];

        object.openCursor().onsuccess = function (e) 
        {
            let result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        };
        data.oncomplete = function () 
        {
            data_catalogo = elements;
            if(Object.entries(data_catalogo).length > 0){
                resolve(1);
            }else{
                reject(0);
            }
        };
        data.onerror = function () 
        {
            reject(0);
        };
    });
}

function cargar_lista_clientes(){
    return new Promise(function(resolve, reject)
    {
        let active      = dataBaseAppSDV.result;
        let data        = active.transaction(['tbl_clientes'], "readonly");
        let object      = data.objectStore('tbl_clientes');
        let indiced     = object.index('by_estado_w');
        let cursord     = indiced.openCursor("1");            
        let elements    = [];

        cursord.onsuccess = function (e) 
        {
            let result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        };
        data.oncomplete = function () 
        {
            data_clientes = elements;
            if(Object.entries(data_clientes).length > 0){
                resolve(1);
            }else{
                reject(0);
            }
        };
        data.onerror = function () 
        {
            reject(0);
        };
    });
}

function ver_tbl_clientes_vnt(data_)
{    
    $('#tbl-clientes-vnt').DataTable().destroy();
    table =  $('#tbl-clientes-vnt').DataTable({
            "stateSave": true,
            "stateDuration": 60 * 60 * 24,
            "data" : data_,
            "columns" : [
                { "data" : "Cli_codigo" },
                { "data" : "Cli_nombre" },
                { "data" : "Cli_direccion" },
                { "data" : "Cli_telefono" },
                { "data" : "Cli_contacto" },
                { "data" : "Ru_nombre" },
                { "data" : "Cli_orden_visita" },
                { "data" : "Di_nombre" },
                { "data" : "Cli_estado" }
            ],           
            "columnDefs":[
                {
                    "targets":[0],
                    "data": "Cli_codigo",
                    "render": function(data, type, row){
                        
                        if(row.Cli_estado == '1'){
                            span_estadow = '<span class="badge badge-success" style="font-size:15px;">'+data+' <span style="display:none;">VERDES TDOS</span></span>';
                        }else{
                            span_estadow = '<span class="badge badge-danger" style="font-size:15px;">'+data+' <span style="display:none;">TDOS ROJOS</span></span>';
                        }
                        return span_estadow;
                    }
                },                   
                {
                    "targets":[8],
                    "data": "Cli_Estado",
                    "render": function(data, type, row){
                        
                        if(row.Cli_estado == '1'){
                            span_estadow = '<span class="badge badge-success">Activo<span style="display:none;">VERDES TDOS</span></span>';
                        }else{
                            span_estadow = '<span class="badge badge-danger">Inactivo<span style="display:none;">TDOS ROJOS</span></span>';
                        }
                        return span_estadow;
                    }
                },                   
                {
                    "targets":[6],
                    "data": "Cli_orden_visita",
                    "searchable": true,
                    "render": function(data, type, row){
                        
                        var dias_visita = ``;
                        if(row.Cli_l == '1'){
                            dias_visita += `<span class="badge badge-info">LUNES</span>`;
                        }
                        if(row.Cli_m == '1'){
                            dias_visita += `<span class="badge badge-info">MARTES</span>`;
                        }
                        if(row.Cli_mi == '1'){
                            dias_visita += `<span class="badge badge-info">MIERCOLES</span>`;
                        }
                        if(row.Cli_j == '1'){
                            dias_visita += `<span class="badge badge-info">JUEVES</span>`;
                        }
                        if(row.Cli_v == '1'){
                            dias_visita += `<span class="badge badge-info">VIERNES</span>`;
                        }
                        if(row.Cli_s == '1'){
                            dias_visita += `<span class="badge badge-info">SABADO</span>`;
                        }
                        if(row.Cli_d == '1'){
                            dias_visita += `<span class="badge badge-info">DOMINGO</span>`;
                        }
                        return dias_visita;
                    }
                }                  
            ],    
            initComplete: function () {
                $('.carga-class').hide();
                this.api().columns([6]).every(function(i){
                    var column = this,
                    select = $('#m-clientes-venta #dias_busqueda')
                        .on( 'change', function(){
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
            "scrollY":"50vh",
            "scrollX":"50vh",
            "scrollCollapse": true
        });
}

function ver_tbl_catalogo_vnt(data_) { 
    $('#familas-vnt').val('').trigger('change');
    $('#tbl-catalogo-vnt').DataTable().destroy();
    table_catalogo_vnt = $('#tbl-catalogo-vnt').DataTable({
        "data" : data_, 
        "columns" : [
            {data: 'Catx_Cat_Id' },
            {data: 'Catx_precio' }
        ],       
        "columnDefs":[
            {
                "targets":[0],
                "data": "Catx_Id",
                "searchable":true,
                "width": "80%",
                "render": function(data, type, row){
                    let row_data_html = '';
                    let precio = parseFloat(row.Catx_precio);
                    precio = precio.toFixed(4);
                    row_data_html = `
                    <div style="font-size:14px; margin:0px;">
                        <b>${row.Catx_Cat_Id}</b> - ${row.Cat_descripcion}
                        <label class="d-none">${row.Fa_nombre}</label>
                        <div class="d-flex align-items-center">
                            <!-- <div class="p-2">Precio: <b>${precio}</b></div> -->
                        </div>              
                    </div>
                    `;                    
                    return row_data_html;
                }
            },
            {
                "targets":[1],
                "data": "Catx_precio",
                "searchable":true,
                "width": "20%",
                "render": function(data){
                    let precio = parseFloat(data);
                    precio = precio.toFixed(4);
                    return precio;
                }
            }
        ],   
        initComplete: function () {
            $('.carga-class').hide();
            this.api().columns([0]).every(function(i){
                var column = this,
                select = $('#familas-vnt')
                    .on( 'change', function(){
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
        // "scrollY":"45vh",
        "scrollCollapse": true
    });
}

/**
 * opcion -> item    = producto seleccionado para ingresar al pedido
 * opcion -> resumen = Obtener informacion del item para el detalle
 **/
function obtener_item_vnt(id, cantidad, opcion){
    return $.Deferred(function(dfd) 
    {
        let active      = dataBaseAppSDV.result;
        let transaction = active.transaction(["tbl_catalogo_venta"]);
        let objectStore = transaction.objectStore("tbl_catalogo_venta");
        let request     = objectStore.get(id.toString());

        request.onerror = function(event) {
            console.log('error')
            dfd.reject('error');
        };
        request.onsuccess = function(event) {
            if(opcion == 'item'){
                data_item = request.result;            
                precio          = data_item.Catx_precio;
                impuesto        = data_item.Impt_valor;
                nombre_impuesto = data_item.Impt_nombre;
                $('#txt-item-id').val(data_item.Catx_Id);
                $('#label-nombre-producto').html(`<h5>${data_item.Catx_Cat_Id} - ${data_item.Cat_descripcion}</h5>`);
                $('#label-img-producto').html(`<img src="https://bocadeli.info/${data_item.Cat_img}" class="img-responsive" style="height: 65px; width: auto;">`);
                $('#label-precio-item-vnt').html(`<b>${data_item.Catx_precio}</b>`);
                $('#label-impuesto-item').html(`<b>${data_item.Impt_nombre}</b>`);
                $('#txt-cantidad-item-vnt').val(`${cantidad}`).trigger('keyup');
                $('#m-producto-item-vnt').modal('toggle');
                dfd.resolve(1);
            }else{
                dfd.resolve(request.result);
            }
        };
    }).promise();
}

function obtener_resumen_pedido(array_items) {
    return $.Deferred(function(dfd) 
    {
        let item                = []; 
        let data_item           = []; 
        let html                = ``;
        let html_tr             = ``;
        let data_length         = Object.entries(array_items).length
        let total               = 0;
        let subtotal            = 0;
        let iva                 = 0;
        let total_unidades      = 0;
        let data_respuesta      = {};
        let data_pedido         = [];

        if(data_length > 0){
            array_items.forEach( function(val, index) {
                obtener_item_vnt(val.item, 0, 'resumen').done(function(respuesta){
                    obtener_ultimo_correlativo().done(function(nuevo_correlativo) {
                        data_item           = respuesta;
                        let cantidad        = parseInt(val.cantidad);
                        let impuesto_nombre = data_item.Impt_nombre;

                        let item_subtotal   = cantidad * data_item.Catx_precio;
                        let item_iva        = item_subtotal * data_item.Impt_valor;
                        let item_total      = item_subtotal + item_iva;

                        subtotal        += item_subtotal;
                        iva             += item_iva;
                        total           += item_total;
                        total_unidades  += cantidad;
                        
                        html_tr = `
                        <tr data-catxid="${data_item.Catx_Id}" data-catxcantidad="${cantidad}">
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-eliminar-item" value="${data_item.Catx_Id}">
                                    <i class="far fa-trash-alt"></i>
                                </button>                        
                            </td>
                            <td width="">
                                ${data_item.Catx_Cat_Id} - ${data_item.Cat_descripcion}<br>
                                Precio: ${data_item.Catx_precio}<br>
                            </td>
                            <th scope="row">${cantidad}</th>
                            <th scope="row">${item_subtotal.toFixed(2)}</th>
                        </tr>`;

                        html += html_tr;
                        html_tr = ``;
                    // --- ingresa item en array del detalle del pedido ---
                        data_pedido.push({
                            item_id             : data_item.Catx_Id,// id del item -> indica sku del producto y canal correspondiente
                            item_codigo         : data_item.Catx_Cat_Id, // sku del producto (item)
                            item_descripcion    : data_item.Cat_descripcion,
                            item_cantidad       : cantidad,
                            item_precio         : data_item.Catx_precio,
                            item_subtotal       : item_subtotal,
                            item_impuesto       : item_iva,
                            item_total          : item_total,
                            item_fecha          : fecha_actual_vnt(),
                            item_estado         : 1
                        });
                    // --- ------------------------------------------------
                        if((index + 1) == data_length){    
                            data_respuesta['factura'] = {
                                importe_total   : total,
                                impuesto        : iva,
                                impuesto_nombre : impuesto_nombre,
                                subtotal        : subtotal,
                                total_unidades  : total_unidades,
                                numero          : nuevo_correlativo
                            }
                            data_respuesta['detalle'] = data_pedido;

                            $('#lbl-iva').html(`<b> ${iva.toFixed(2)} </b>`);
                            $('#lbl-subtotal').html(`<b> ${subtotal.toFixed(2)} </b>`);
                            $('#lbl-total').html(`<b> ${total.toFixed(2)} </b>`);
                            $('#lbl-fecha').html(`<b> ${fecha_actual_vnt()} </b>`);
                            $('#lbl-numero').html(`<b> ${nuevo_correlativo} </b>`);
                            $('#tbl-pedido-detalle').html(html);    
                            $('.carga-class').hide(); 
                            dfd.resolve(data_respuesta);
                        }                    
                    });
                });                
            });            
        }else{
            $('#lbl-iva').html(`0.0000`);
            $('#lbl-subtotal').html(`0.0000`);
            $('#lbl-total').html(`0.0000`);
            $('#tbl-pedido-detalle').html(``);
            dfd.resolve(1);
        }
    }).promise();
}

function validar_campos(obj) {
    let count = 0;
    //recorremos el arreglo
    $.each(obj, function (i, item) {
        if (
            $("#" + obj[i].name + "").val() == undefined ||
            $("#" + obj[i].name + "").val() == "" ||
            $("#" + obj[i].name + "").val().length <= 0)
        {
            count++;
        }
    });
    return count;
}

function round(num) {
    var m = Number((Math.abs(num) * 100).toPrecision(10));
    return Math.round(m) / 100 * Math.sign(num);
}

function obtener_ultimo_correlativo(){   
    return $.Deferred(function(dfd) 
    {
        let active      = dataBaseAppSDV.result;
        let data        = active.transaction(['tbl_parametros_vnt'], "readonly");
        let object      = data.objectStore('tbl_parametros_vnt');
        let indiced     = object.index('by_tipo');
        let cursord     = indiced.openCursor("CORRELATIVO");            
        let elements    = [];
        let correlativo = [];

        cursord.onsuccess = function (e) 
        {
            let result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        };
        data.oncomplete = function () 
        {
            // extraemos el valor del correlativo guardado localmente
            dato = elements[0].ultimo_correlativo;

            // le damos el formato requerido - ruta-correlativo
            if(dato == null){
                correlativo = "1";
            }else{
                correlativo = dato.split('-')[1];
                correlativo = (parseInt(correlativo) + 1).toString();
            }
            nuevo_correlativo = arrg_Credls['usuario'] + '-' + correlativo.padStart(5, "0");            
            // "devolvemos" el nuevo correlativo
            dfd.resolve(nuevo_correlativo);
        };
        data.onerror = function () 
        {
            dfd.reject(0)       
        };
     
   }).promise();
}

function actualizar_ultimo_correlativo(nuevo_correlativo, es_sincronizado, opcion)
{
    return $.Deferred(function(dfd) 
    {
        let active      = dataBaseAppSDV.result;
        let data        = active.transaction(['tbl_parametros_vnt'], "readonly");
        let object      = data.objectStore('tbl_parametros_vnt');
        let indiced     = object.index('by_tipo');
        let cursord     = indiced.openCursor("CORRELATIVO");            
        let elements    = [];
        let correlativo = [];

        cursord.onsuccess = function (e) 
        {
            let result = e.target.result;
            
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue(); 
        };
        data.oncomplete = function () 
        {
            let actived     = dataBaseAppSDV.result;
            let objectStore = actived.transaction(["tbl_parametros_vnt"], "readwrite").objectStore("tbl_parametros_vnt");
            let request     = objectStore.get(elements[0].idx);
            request.onerror = function(event) {
                dfd.reject(0)       
            };
            request.onsuccess = function(event) {
                let data = request.result;
                if(opcion == 'envio'){ // envio de registros nuevos
                    data.ultimo_correlativo = nuevo_correlativo;                
                    data.es_sincronizado    = es_sincronizado;                
                }else if(opcion == 'sincronizacion'){ // envio de registros en cola
                    data.es_sincronizado    = es_sincronizado;               
                }
                let requestUpdate = objectStore.put(data);
                requestUpdate.onerror = function(event) {
                    dfd.reject(request.error.name + '\n\n' + request.error.message);
                };
                requestUpdate.onsuccess = function(event) {
                    dfd.resolve(nuevo_correlativo);
                };
            };
        };        
        data.onerror = function () 
        {
            dfd.reject(0)       
        };

    }).promise();
}

function guardar_pedido_local(data_insertar, es_sincronizado) {   
    return $.Deferred(function(dfd) 
    {
        var request = '';
        var active = dataBaseAppSDV.result;
        var data = active.transaction(["tbl_factura_vnt"], "readwrite");
        var object = data.objectStore("tbl_factura_vnt");
        data_insertar.es_sincronizado = es_sincronizado;
        request = object.put(data_insertar);
        request.onerror = function(e) {
            console.log(request.error.name + '\n\n' + request.error.message);
            dfd.reject(request.error.name + '\n\n' + request.error.message);
        };
        data.oncomplete = function(e) {   
            dfd.resolve(1);
        }; 
    }).promise();
}

function preparar_pedido_envio(data_pedido_realizado, opcion, numero = null) {
    let correlativo         = 0;
    let nuevo_correlativo   = '';
    let data_insertar       = {};
    
    if(opcion == 'anular')
    {
        obtener_info_pedido(numero, 'anular').done(function (data_info) {
            guardar_pedido_bd(data_info, 'anular', numero);         
        });

    }else{
        $('#btn-guardar-cambios').prop('disabled', true);
        if(Object.entries(data_pedido_realizado).length > 0){

            nuevo_correlativo = data_pedido_realizado.factura['numero'];
            data_insertar = {
                fecha_emision   : fecha_actual_vnt(),
                numero          : nuevo_correlativo,
                importe_total   : data_pedido_realizado.factura['importe_total'],
                nombre_impuesto : data_pedido_realizado.factura['impuesto_nombre'],
                cliente_id      : $('#txt_id_cliente_vnt').val(),
                cliente_codigo  : $('#txt-codigo-cliente-vnt').val(),
                cliente_nombre  : $('#txt_nombre_cliente_vnt').val(),
                total_unidades  : data_pedido_realizado.factura['total_unidades'],
                pedido_ruta     : arrg_Credls['usuario'],
                usuario_id      : arrg_Credls['us_cod_N'],
                nombre_vendedor : '',
                pedido_detalle  : data_pedido_realizado.detalle,
                es_sincronizado : '',
                estado          : "VIGENTE"
            };
            guardar_pedido_bd(data_insertar, 'nuevo_pedido', nuevo_correlativo); 
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html: '<h5>No hay pedidos para guardar</h5>',
                confirmButtonText: 'Ok',
                target: document.getElementById('m-pedido-vnt')
            });
            $('#btn-guardar-cambios').prop('disabled', false);
            return;
        } 
    }            
}

function guardar_pedido_bd(data_insertar, opcion, numero) {
    if(Object.entries(data_insertar).length > 0){
        $.ajax({
            url: 'C_venta/Ctr_venta/guardar_pedido_vnt',
            type: 'POST',
            dataType: 'JSON',
            data: {data_insertar: data_insertar},
            timeout: 7777,
            beforeSend: function() {
                $(".carga-class").show();
            }
        }).done(function(_resp) {
            if (_resp.rs == true) { 
                if(_resp.opcion != 'anular'){
                    Promise.all([
                        console.log(numero),
                        actualizar_ultimo_correlativo(numero, "SINCRONIZADO", 'envio'),
                        guardar_pedido_local(data_insertar, "SINCRONIZADO")
                    ])
                    .then(respuestas =>{
                        $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                            Swal.fire({
                                type: 'success',
                                title: 'Pedido guardado con exito',
                                confirmButtonText: 'Ok',
                                target: document.getElementById('m-pedido-vnt')
                            }).then((result) => {
                                dibujar_ticket(numero);
                                limpiar_pedido('pedido');
                            });              
                        });
                    })
                    .catch(error =>{
                        console.log(error);
                    }); 
                }else{
                    $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                        Swal.fire({
                            type: 'success',
                            title: 'Pedido anulado con exito',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            consultar_cola_venta();
                            limpiar_pedido('consulta');
                        });              
                    });                        
                }
            }else{
                $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                    Swal.fire({
                        type: 'success',
                        title: 'Pedido anulado con exito',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        consultar_cola_venta();
                        limpiar_pedido('consulta');
                    });              
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if(opcion != 'anular')
            {
                $.when($(".carga-class").stop(true, true).hide()).done(function(x) {                    
                    console.log(textStatus);
                    let data_insertar_local = {};
                    data_insertar_local['pedido']               = data_insertar;
                    data_insertar_local['ultimo_correlativo']   = nuevo_correlativo
                    _ajax_error_modulos_menu(jqXHR.status, jqXHR.readyState, jqXHR.statusText, 'venta', data_insertar_local);
                });     
            }else{
                Promise.all([
                    actualizar_pedidos_local(data_insertar.numero, 'es_sincronizado', 'PENDIENTE')
                ])
                .then(respuestas =>{
                    $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                        Swal.fire({
                            type: 'success',
                            title: 'Pedido anulado con exito',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            consultar_cola_venta().done(function (argument) {
                                limpiar_pedido('consulta');
                            });
                        });              
                    });
                })
                .catch(error =>{
                    console.log(error);
                });
            }
        });                      
    }  
}

function anular_pedido_bd(numero) {
    if(numero != '' && numero != null){           
        $.ajax({
            url: 'C_venta/Ctr_venta/anular_pedido_vnt',
            type: 'POST',
            dataType: 'JSON',
            data: {numero: numero},
            timeout: 7777,
            beforeSend: function() {
                $(".carga-class").show();
            }
        })
        .always(function(_resp, textStatus, errorThrown) {
            if (textStatus == "success") {
                if (_resp.rs == true) {       
                    Promise.all([
                        actualizar_pedidos_local(numero, 'estado', 'ANULADO')
                    ])
                    .then(respuestas =>{
                        $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                            Swal.fire({
                                type: 'success',
                                title: 'Pedido anulado con exito',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                limpiar_pedido('consulta');
                            });              
                        });
                    })
                    .catch(error =>{
                        console.log(error);
                    });  
                }else {
                    $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                        console.log('ERROR CAMPOS');
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html: _resp.errores,
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            limpiar_pedido('consulta');
                        }); 
                    });
                }
            } else {
                $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                    console.log('ERROR ERROR ERROR');

                    Promise.all([
                        actualizar_pedidos_local(numero, 'es_sincronizado', 'PENDIENTE')
                    ])
                    .then(respuestas =>{
                        $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                            Swal.fire({
                                type: 'success',
                                title: 'Pedido anulado con exito',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                consultar_cola_venta();
                                limpiar_pedido('consulta');
                            });              
                        });
                    })
                    .catch(error =>{
                        console.log(error);
                    });
                });
            }
        });                    

    }else{

        Swal.fire({
          title: '!Aviso',
          text: 'No hay pedidos para guardar',
          confirmButtonText: 'Ok',
          target: document.getElementById('m-pedido-vnt')                            
        });
        /* Swal.fire({
            title: 'Aviso!',
            type: 'info',
            html: '<h5>No hay pedidos para guardar</h5>',
            confirmButtonText: 'Ok',
            target: document.getElementById('m-pedido-vnt')
        });*/
    }        
}

function fecha_actual_vnt() 
{
    let fecha_actual_vnt = [];
    let hoy              = new Date();
    let mes              = (hoy.getMonth() + 1);
    let dia              = hoy.getDate();
    let hora             = hoy.getHours();
    let minutos          = hoy.getMinutes();
    let segundos         = hoy.getSeconds();

    if ((mes >= 0) && (mes < 10)) {
        mes = '0' + String(mes);
    }
    if ((dia >= 0) && (dia < 10)) {
        dia = '0' + String(dia);
    }
    if ((hora >= 0) && (hora < 10)) {
        hora = '0' + String(hora);
    }
    if ((minutos >= 0) && (minutos < 10)) {
        minutos = '0' + String(minutos);
    }
    if ((segundos >= 0) && (segundos < 10)) {
        segundos = '0' + String(segundos);
    }
    let hora_format = String(hora) + ':' + String(minutos) + ':' + String(segundos);
    let fecha       = String(hoy.getFullYear()) +'-'+ String(mes) +'-'+ String(dia);
    let fecha_hora  = fecha + ' ' + hora_format;
    return fecha_hora;
}

// --- borrar contenido y valores del pedido recien guardado --------------
function limpiar_pedido(opcion) {
    if(opcion == 'pedido'){
        warn_on_unload = '';
        data_pedido_realizado = {};
        data_ids_item = [];
        $('#txt_id_cliente_vnt').val('');
        $('#txt-codigo-cliente-vnt').val('');
        $('#txt_nombre_cliente_vnt').val('');
        $('.label-nombre-cliente').html('');
    }else{
        if($('#txt-codigo-cliente-vnt').val() != '' && $('#txt-codigo-cliente-vnt').val() != null){
            $('.label-nombre-cliente').html('<h5>' 
                + $('#txt-codigo-cliente-vnt').val() + ' - ' 
                + $('#txt_nombre_cliente_vnt').val() + '</h5>');
        }
    }
    $('#btn-guardar-cambios').remove();
    $('#lbl-iva').html(`0.0000`);
    $('#lbl-subtotal').html(`0.0000`);
    $('#lbl-total').html(`0.0000`);
    $('#lbl-fecha').html(` -- `);
    $('#lbl-numero').html(` -- `);
    $('#tbl-pedido-detalle').html(``);
    $('#btn-guardar-cambios').prop('disabled', false);
    $('.panel-btn-vnt').html('');
    $('#m-pedido-vnt').modal('hide');
    // $('#m-impresion-ticket').modal('toggle');//Impresion Ticket
}

function consultar_cola_venta(){
    return $.Deferred(function(dfd) 
    {
        let data_cola_pedidos   = [];
        let data_               = [];
        let elements            = [];
        let active              = dataBaseAppSDV.result;
        let transaccion         = active.transaction('tbl_factura_vnt', 'readonly'),
        store   = transaccion.objectStore('tbl_factura_vnt'),
        indice  = store.index('by_es_sincronizado'),
        cursor  = indice.openCursor('PENDIENTE')
        cursor.onsuccess = function(event) {            
            let result = event.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        }
        transaccion.oncomplete = function() {
            let anulados            = [];
            let no_sincronizados    = [];
            data_cola_pedidos['no_sincronizado']    =   elements;
            data_cola_pedidos['cuenta_cola']        = parseInt(Object.keys(elements).length);
            $("#RegisCola").html('');
            $("#RegisCola").html(data_cola_pedidos['cuenta_cola']);
            dfd.resolve(data_cola_pedidos);
        };
        transaccion.onerror = function() {
            dfd.reject(0);
        };
    }).promise();    
}

function sincronizar_pedidos_vnt() {    
    consultar_cola_venta().done(function (data) {
        let cuenta_no_sincronizado  = Object.entries(data['no_sincronizado']).length;
        let index_count         = 0;
        let errores_proceso     = '';
        let success_proceso     = false;       

        if(cuenta_no_sincronizado > 0 ){
            data['no_sincronizado'].forEach(function(val, index) {
                console.log(val);
                $.ajax({
                    url: 'C_venta/Ctr_venta/guardar_pedido_vnt',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {data_insertar: val},
                    timeout: 7777,
                    beforeSend: function(){
                        $('.carga-class').show();
                    }
                }).done(function(_resp, textStatus, jqXHR) {
                    Promise.all([
                        actualizar_pedidos_local(val.numero, 'es_sincronizado', 'SINCRONIZADO'),
                        actualizar_ultimo_correlativo(0, "SINCRONIZADO", 'sincronizacion')
                    ])
                    .then(respuestas => {
                        success_proceso = true;
                        alertify.success('Registro enviado exitosamente!');
                        
                        if((index + 1) == cuenta_no_sincronizado){                    
                            $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                                consultar_cola_venta().done(function () {
                                    limpiar_pedido('pedido');
                                });
                            });                        
                        }                        
                    })
                    .catch(error => {
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            alertify.error('El registro no se pudo enviar!');
                            consultar_cola_venta().done(function () {
                                limpiar_pedido('pedido');
                            });
                            return false;
                        });
                    });
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<strong>Conectese a una red...</strong>',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            consultar_cola_venta().done(function () {
                                limpiar_pedido('pedido');
                            });
                        });
                        return false;
                    });
                });            
            });
        }
    });
}

function actualizar_pedidos_local(numero, propiedad, valor_propiedad) {
    return new Promise(function(resolve, reject)
    {
        let actived = dataBaseAppSDV.result;
        let objectStore = actived.transaction(["tbl_factura_vnt"], "readwrite").objectStore("tbl_factura_vnt");
        let request = objectStore.get(numero);
        request.onsuccess = function(event) {
            let data = request.result;
                data[propiedad]  = valor_propiedad;                
            let requestUpdate = objectStore.put(data);
            requestUpdate.onsuccess = function(event) {
                resolve(1);
            };
            requestUpdate.onerror = function(event) {
                reject(requestUpdate.error.name + '\n\n' + requestUpdate.error.message);
            };
        };
        request.onerror = function(event) {
            reject(request.error.name + '\n\n' + request.error.message)       
        };
    
    });
}

function obtener_pedidos_realizados() {
    let active      = dataBaseAppSDV.result;
    let data        = active.transaction('tbl_factura_vnt', "readonly");
    let object      = data.objectStore('tbl_factura_vnt');
    let elements    = [];
    let html_tr     = ``;
    object.openCursor().onsuccess = function (e) 
    {
        let result = e.target.result;
        if (result === null) {
            return;
        }
        elements.push(result.value);
        result.continue();
    };
    data.oncomplete = function () 
    {
        elements.forEach(function(val, index) {
            let estado = ``;
            if(val.estado == "VIGENTE"){
                estado = `<span class="badge badge-success">VIGENTE</span>`
            }else{
                estado = `<span class="badge badge-danger">ANULADO</span>`                
            }
            importe = parseFloat(val.importe_total);
            
            html_tr += `            
            <tr>
                <td style="width: 85%; text-align:left; vertical-align: middle;">                    
                    <div style="display:flex;">
                        <div style=" margin-right:2%;">
                            Estado <br>
                            Fecha <br>
                            N. Doc <br>
                            Cliente                            
                        </div>
                        <div>
                            | ${estado}<br>
                            | ${val.fecha_emision}  <br>
                            | <b>${val.numero}</b> <br>
                            | <b>${val.cliente_codigo} - ${val.cliente_nombre}</b><br>                            
                        </div>                         
                    </div>
                </td>
                <td style="width: 10%; vertical-align: middle;">
                    <b>${importe.toFixed(2)}</b>
                </td>
                <td scope="col" style="width: 5%; vertical-align: middle;">
                    <button type="button" class="btn btn-info btn-sm btn-info-item" value="${val.numero}">
                        <i class="fas fa-eye 1x"></i>
                    </button>
                </td>
            </tr> `
            ;
        });
        $('#tbl-consulta-pedidos-vnt tbody').html(html_tr);
        $('.carga-class').hide();
    };
    data.onerror = function () 
    {
        console.log('error');
    };    
}

function obtener_pedidos_realizados_estado(estado_opcion) {
    
    let html_tr     = ``;
    let data_       = [];
    let elements    = [];
    let active      = dataBaseAppSDV.result;
    let transaccion = active.transaction('tbl_factura_vnt', 'readonly'),
    store   = transaccion.objectStore('tbl_factura_vnt'),
    indice  = store.index('by_estado'),
    cursor  = indice.openCursor(estado_opcion)
    cursor.onsuccess = function(event) {            
        let result = event.target.result;
        if (result === null) {
            return;
        }
        elements.push(result.value);
        result.continue();
    }
    transaccion.oncomplete = function() {
        
        elements.forEach(function(val, index) {
            let estado = ``;

            if(val.estado == "VIGENTE"){
                estado = `<span class="badge badge-success">VIGENTE</span>`
            }else{
                estado = `<span class="badge badge-danger">ANULADO</span>`                
            }
            importe = parseFloat(val.importe_total);
            
            html_tr += `            
            <tr>
                <td style="width: 85%; text-align:left; vertical-align: middle;">                    
                    <div style="display:flex;">
                        <div style=" margin-right:2%;">
                            Estado <br>
                            Fecha <br>
                            N. Doc <br>
                            Cliente                            
                        </div>
                        <div>
                            | ${estado}<br>
                            | ${val.fecha_emision}  <br>
                            | <b>${val.numero}</b> <br>
                            | <b>${val.cliente_codigo} - ${val.cliente_nombre}</b><br>                            
                        </div>                         
                    </div>
                </td>
                <td style="width: 10%; vertical-align: middle;">
                    <b>${importe.toFixed(2)}</b>
                </td>
                <td scope="col" style="width: 5%; vertical-align: middle;">
                    <button type="button" class="btn btn-info btn-sm btn-info-item" value="${val.numero}">
                        <i class="fas fa-eye 1x"></i>
                    </button>
                </td>
            </tr> `
            ;

        });
        $('#tbl-consulta-pedidos-vnt tbody').html(html_tr);
        $('.carga-class').hide();
    };
    transaccion.onerror = function() {
        console.log('error');
    };
}

function obtener_info_pedido(numero, opcion) {
    return $.Deferred(function(dfd){
        let active      = dataBaseAppSDV.result;
        let data        = active.transaction(['tbl_factura_vnt'], "readonly");
        let object      = data.objectStore('tbl_factura_vnt');
        let indiced     = object.index('by_numero');
        let cursord     = indiced.openCursor(numero);            
        let elements    = [];
        let html_tr     = ``;

        cursord.onsuccess = function (e) 
        {
            let result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();

        };        
        data.oncomplete = function () 
        {
            let data_detalle_pedido = elements[0].pedido_detalle;
            if(opcion == "consulta"){
                let iva                 = 0;
                let subtotal            = 0;
                let total               = parseFloat(elements[0].importe_total);
                let item_subtotal       = 0;
                let item_impuesto       = 0;
                data_detalle_pedido.forEach( function(val, index) {
                    item_subtotal = parseFloat(val.item_subtotal);
                    item_impuesto = parseFloat(val.item_impuesto);
                    html_tr += `
                        <tr data-catxid="${val.item_id}" data-catxcantidad="${val.item_cantidad}">
                            <!-- <td>
                                <button type="button" class="btn btn-danger btn-sm btn-eliminar-item" value="${val.item_id}" disabled>
                                    <i class="far fa-trash-alt"></i>
                                </button>                        
                            </td> -->
                            <td width="" colspan="2" style="text-align: left;">
                                <b>${val.item_codigo}</b> - ${val.item_descripcion}<br>
                                <b>Precio: ${val.item_precio}</b><br>
                            </td>
                            <th scope="row">${val.item_cantidad}</th>
                            <th scope="row">${item_subtotal.toFixed(2)}</th>
                        </tr>`;
                        iva         += item_impuesto;
                        subtotal    += item_subtotal;
                });
                $('#lbl-iva').html(`<b> ${iva.toFixed(2)} </b>`);
                $('#lbl-subtotal').html(`<b> ${subtotal.toFixed(2)} </b>`);
                $('#lbl-total').html(`<b> ${total.toFixed(2)} </b>`);
                $('#lbl-fecha').html(`<b> ${elements[0].fecha_emision} </b>`);
                $('#lbl-numero').html(`<b> ${elements[0].numero} </b>`);
                $('#tbl-pedido-detalle').html(html_tr);
                $('.label-nombre-cliente').html(`<h5>${elements[0].cliente_codigo} - ${elements[0].cliente_nombre}</h5>`);
                // $('#m-consulta-pedidos-vnt').modal('toggle');
            }

            dfd.resolve(elements[0]);          
        };
        data.onerror = function () 
        {
            dfd.reject(0)       
        };
    }).promise();
}

function dibujar_ticket(numero) {
    // return $.Deferred(function(dfd) 
    // {
    this.get = function() { 
        return $.Deferred(function(dfd) {
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                obtener_info_pedido(numero, 'imprimir').done(function (data) {
                    let html_header     = ``;
                    let html_body       = ``;
                    let pedido_detalle  = data.pedido_detalle;
                    let total           = data.importe_total;
                    let total_item      = 0;
                    total               = parseFloat(total);
                    html_header = `
                        <p class="centered">
                            <br>PRODUCTOS ALIMENTICIOS BOCADELI S.A DE C.V
                            <br>Ruta: ${arrg_Credls['usuario']}
                            <br>Fecha Solicitud:
                            <br>${data.fecha_emision}
                            <br>Fecha Impresion:
                            <br>${fecha_actual_vnt()}
                        </p>
                        <p>
                            Estado Pedido: <u>${data.estado}</u>
                            <br>N. pedido: ${data.numero}
                            <br>Cliente: ${data.cliente_codigo} - ${data.cliente_nombre}
                        </p>
                        <p class="centered">
                            -----------------------------------------------------
                            <br>PEDIDO ACTUAL
                            <br>-----------------------------------------------------
                        </p>
                    `;
                    html_body += `
                        <tr style="height: 28px;">
                            <th class="quantity">Cant.</th>
                            <th class="description">Descripcion</th>
                            <th class="price">Total</th>
                        </tr>
                    `;
                    pedido_detalle.forEach(function(val, index) {
                        total_item = parseFloat(val.item_total);
                        html_body += `
                            <tr>
                                <td class="quantity">${val.item_cantidad}</td>
                                <td class="description">${val.item_codigo}<br>${val.item_descripcion}</td>
                                <td class="price">${total_item.toFixed(4)}</td>
                            </tr>
                        `;
                    });
                    html_body += `
                        <tr>
                            <td colspan="3" class="centered">
                                TOTAL UNIDADES: ${data.total_unidades} <br>
                                TOTAL PEDIDO  : ${total.toFixed(2)}
                            </td>
                        </tr>
                    `;

                    $('.header-ticket').html(html_header);
                    $('.tbl_tk').html(html_body);
                    dfd.resolve(1);
            }).fail(function () {
                dfd.resolve(0);
            });
        });
         
       }).promise();
    }
    
    this.get().done(function(respuesta) {
        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
            $('#m-impresion-ticket').modal('toggle');
        });
    }).fail(function (respuesta) {
        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
            console.log(respuesta);
        });
    });
}

function imprSelec(nombre) {    

    var ficha = document.getElementById(nombre);
    var ventimp = '';
    var ventimp = window.open(' ', '_blank');
    ventimp.document.write('<link rel="stylesheet" href="../dependencias/css/style_ticket.css">');
    ventimp.document.write('</head><body>');
    ventimp.document.write( ficha.innerHTML );
    ventimp.document.write('</body></html>');
    ventimp.document.close();
    ventimp.focus();
    ventimp.print();
    
    ventimp.onfocus = function () { setTimeout(function () { ventimp.close(); }, 500); }
}

function obtener_pedido_sugerido(cliente, opcion = null) {
    warn_on_unload = 'pedido_sugerido';
    data_ids_item           = [];
    let data_cola_pedidos   = [];
    let elements            = [];
    let active              = dataBaseAppSDV.result;
    let transaccion         = active.transaction('tbl_items_sugeridos', 'readonly'),
    store   = transaccion.objectStore('tbl_items_sugeridos'),
    indice  = store.index('by_cliente'),
    cursor  = indice.openCursor(cliente)
    cursor.onsuccess = function(event) {            
        let result = event.target.result;
        if (result === null) {
            return;
        }
        elements.push(result.value);
        result.continue();
    }
    transaccion.oncomplete = function() {
        if(opcion == null){
            if(Object.entries(elements).length > 0){
                data_ids_item = elements;
                obtener_resumen_pedido(data_ids_item).done(function (respuesta) {
                    data_pedido_realizado = respuesta;
                });
            }else{
                $('.carga-class').hide();
                Swal.fire({
                    title: 'Aviso!',
                    type: 'info',
                    html: '<h5>No hay pedidos realizados para este cliente</h5>',
                    confirmButtonText: 'Ok',
                    target: document.getElementById('m-pedido-vnt')
                });
            }            
        }else{
            
        }

    };
    transaccion.onerror = function() {
        console.log('error');
    };
}

function obtener_items_cantidades(cliente) {
    let data_items      = [];
    let elements        = [];
    let active          = dataBaseAppSDV.result;
    let data_promedio   = [];
    let transaccion = active.transaction('tbl_factura_vnt', 'readonly'),
    store   = transaccion.objectStore('tbl_factura_vnt'),
    indice  = store.index('by_cliente'),
    cursor  = indice.openCursor(cliente)
    cursor.onsuccess = function(event) {            
        let result = event.target.result;
        if (result === null) {
            return;
        }
        elements.push(result.value);
        result.continue();
    };
    transaccion.oncomplete = function() {
        elements.forEach(function(val, index) {
            data_item.push(val.pedido_detalle);
        });

        if(Object.entries(data_item).length > 0){
            const personas = [
              { nombre: 'Edu', edad: 35 },
              { nombre: 'Manuel', edad: 37 },
              { nombre: 'Marta', edad: 42 },
              { nombre: 'Edu', edad: 25 },
            ];

            const busqueda = personas.reduce((acc, persona) => {
              acc[persona.nombre] = ++acc[persona.nombre] || 0;
              return acc;
            }, {});

            const duplicados = personas.filter( (persona) => {
                return busqueda[persona.nombre];
            });

            console.log(duplicados);
        }
    };
}

function obtener_existencias_ruta() {
    $.ajax({
        type: "POST",
        url: "C_venta/Ctr_venta/get_invenario_ro",
        dataType: "JSON",
        // data: Datos,
        data: {
            ruta        : arrg_Credls['usuario'],
            usuario     : arrg_Credls['us_cod_N'],
            nomnre_ruta : arrg_Credls['ruta_nombre']
        },
        beforeSend: function () {
            $(".carga-class").show();
        }       
    }).done(function(msg) {
        $(".carga-class").hide();
        console.log(msg);
    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
}