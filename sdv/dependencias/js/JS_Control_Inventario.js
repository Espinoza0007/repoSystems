var arrgColaCti = [];
var ids_productos = [];
var data_control_inventario = [];
var array_cti = [];
var data_catalogo_cti = [];
var data_cti_cliente = [];
var id_cliente = '';
var cola_solo_cti = '';
var data_insertar_cti = [];
var veri_registro = '';
var token_editar_ctis = [];
var token_eliminar_ctis = [];
var id_cliente_cti = '';
var nombre_cliente_cti = '';
var cant_cola_cti_ = '';
var elemento_cti = '';
var fecha_tel = '';
var token_cti = '';
var fecha_actual = '';
var token_elim = '';
var marker;
var idCat = '';
var BandAdEd = 0;/* 1 = Agregar, 0 = Editar */
var ContAd = 0;/* Contador Agregar Temporal */

$(document).ready(function() { 
    /********************
     * 1 - reclamos
     * 2 - control
    ********************/
    DB_iniciar_reclamos(2); 
    init();  

    $("#txtLongitud").val(0);
    $("#txtLongitud").val(0);


    $(document).on('click', '#btn-forproductos', function() {
        BandAdEd = 1;
    });


    $('#catalogoDtable tbody').on( 'click', 'tr', function () {
        var txtProduId = $("input[name='txtCtiId[]']").serializeArray();
        var fechaActual = fecha_actual_cti();
        let ProdEstan = txtProduId.filter(val => val.value == table.row( this ).data().Cat_Id);
        if( parseInt(Object.keys(ProdEstan).length) > 0){
            $("#modalCatalogo").modal("toggle");
            $("#showDataSN").empty();
            $('#catalogoDtable').DataTable().destroy();
            blockFCli=0;
            Swal.fire({
                title: 'Aviso!',
                type: 'warning',
                html: '<h3>El item ya existe</h3>',
                confirmButtonText: 'Ok'
            });
        }else{
            if( BandAdEd == 1 ){
                $("#txtCodigoCti").val(table.row( this ).data().Cat_Id);
                $("#txtProductoCti").val(table.row( this ).data().Cat_descripcion);
                $("#txtProductoNombreCti").val(table.row( this ).data().Cat_descripcion);
                mostarInfoCti();
            }else{
                $("#nombreCti"+idCat).val(table.row( this ).data().Cat_descripcion);
                $("#CtiId"+idCat).val(table.row( this ).data().Cat_Id);
                $("#fechat"+idCat).text(fechaActual);
                $("#prodnombre"+idCat).text(table.row( this ).data().Cat_descripcion);
                $("#skunombre"+idCat).text(table.row( this ).data().Cat_Id);
                $("#modalCatalogo").modal("toggle");
                $("#showDataSN").empty();
                $('#catalogoDtable').DataTable().destroy();
                blockFCli=0;
            }
        }
    });
    
    $('#clientesDtable tbody').on( 'click', 'tr', function () {
        id_cliente_cti = table.row( this ).data().Cli_Id;
        nombre_cliente_cti = table.row( this ).data().Cli_Id +' - ' +table.row( this ).data().Cli_nombre;
        $('#txtclientesinventario').val(table.row( this ).data().Cli_Id +' - ' +table.row( this ).data().Cli_nombre);
        $('#txtIdClienteCti').val(id_cliente_cti);
        getInfoCli();
    });

    $('#m-control-inventario').on('hidden.bs.modal', function (e) {
        document.getElementById("form-control-inventario").reset();
        document.getElementById("frm_cti_items").reset();
        $('#accordion_cti').html('');
        $('#txtclientesinventario').val(''); 
        $('#txtIdClienteCti').val(''); 
        token_eliminar_ctis = [];
    });

    $('#modalCatalogo').on('hidden.bs.modal', function (e) {
        BandAdEd = 0;
    });

    $(document).on('click', '#Si_InvAntes', function() 
    {
        var id_cliente = $('#txtIdClienteCti').val();
        if (id_cliente != '' && id_cliente != null) {
            consultar_cti_cliente(id_cliente, false);
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html: '<h3>Por favor, seleccione un cliente</h3>',
                confirmButtonText: 'Ok'
            });
        }
    });

    $(document).on('click', '#No_InvAntes', function() 
    {
        var id_cliente = $('#txtIdClienteCti').val();
        if (id_cliente != '' && id_cliente != null) {
            consultar_cti_cliente(id_cliente, true);
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html: '<h3>Por favor, seleccione un cliente</h3>',
                confirmButtonText: 'Ok'
            });
        }
    });

    $(document).on('click', '#btn_consulta_cti', function() 
    {

        var id_cliente = $('#txtIdClienteCti').val();
        if (id_cliente != '' && id_cliente != null) {
            var BandRegHoy = 0;
            data_cti_cliente = [];
            var dataResult = [];
            var active = dataBaseAppSDV.result;
            let transaccion = active.transaction('tbl_control_inventario', 'readonly'),
            store = transaccion.objectStore('tbl_control_inventario'),
            indice = store.index('by_cliente'),
            cursor = indice.openCursor(id_cliente)
            cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue(); 
            } else {
            
                dataResult.forEach( x => {
                    //LOS PRODUCTOS DE LA FECHA ACTUAL
                    if( !dataResult.hasOwnProperty(x.opcion) && !dataResult.hasOwnProperty(x.opcion)){
                        if(x.opcion != 'eliminado' && x.fecha == fecha_actual_cti())
                        {
                            data_cti_cliente.push(x)
                            BandRegHoy = 1;
                        }
                    }
                })
                // resolve(1);
            };
            }
            transaccion.oncomplete = function() {
            Promise.all([
                // BD_lista_catalogo_productos()
                DB_CargarCatalogoP()
            ])
            .then(respuestas =>{
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $('#m-control-inventario').modal('toggle');
                    if( BandRegHoy == 1 ){
                        cargar_items_cti_html(data_cti_cliente, true);
                        $("#C_pregInvAnt").hide();
                        $("#Content_Inv").show();
                    }else{
                        $("#C_pregInvAnt").show();
                        $("#Content_Inv").hide();
                    }
                });
            })
            .catch(error => {
                console.log('ERROR :c '+error);
            });
            };
            transaccion.onerror = function() {
             reject(0);
            };
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html: '<h3>Por favor, seleccione un cliente</h3>',
                confirmButtonText: 'Ok'
            });
        }
    });

    $(document).on('click', '.btn_editar_cti', function(event) {
        idCat = ''; 
        idCat     = $(this).attr("id");
        idCat     = idCat.substring(6, idCat.length);
        opcion_catalogo = 'cti'
        $("#modalCatalogo").modal("toggle");
    });

    $(document).on('click', '.btn_eliminar_cti', function(event) {
        Swal.fire({
            title: 'Aviso!',
            text: "¿Desea eliminar este elemento?",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false
        }).then((result) => {
            if(result.value){
                if(!$(this).hasClass('nv')){
                    token_elim = $(this).parent('.p-2').parent('.d-flex').parent('.card').find("input[name='txtTokenCti[]']").val();
                    token_eliminar_ctis.push($(this).parent('.p-2').parent('.d-flex').parent('.card').find("input[name='txtCtiId[]']").val());
                }
                $(this).parent('.p-2').parent('.d-flex').parent('.card').remove();
            }else{

            }
        });
    });

    $(document).on('change', '.cti', function(event) {
        if ($(this).val() == ""){
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');
        }else{
            $(this).addClass('is-valid');
            $(this).removeClass('is-invalid');
        }
    });

    $(document).on('keyup', '.cti1', function(event) {
        if ($(this).val() == ""){
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');
        }else{
            $(this).addClass('is-valid');
            $(this).removeClass('is-invalid');
        }
    });
});

function validar_frm_cti_items() {
    var ban_f = 0, ban_c = 0, ban_r = 0;
    var CantidR = parseInt(Object.keys($("input[name='txtCtiId[]']").serializeArray()).length);
    if( CantidR == 0 ){
        ban_r = 1;
    }
    $(document).find("input[name='txtFechaVencimientoCti[]']").each(function(index, val) {
        if ($(val).val() == ""){
            $(val).addClass('is-invalid');
            ban_f = 1;
        }else{
            $(val).addClass('is-valid');
        }
    });
    $(document).find("input[name='txtCantidadCti[]']").each(function(index, val) {
        if ($(val).val() == ""){
            $(val).addClass('is-invalid');
            ban_c = 1;
        }else{
            $(val).addClass('is-valid');
        }
    });

    if( ban_r === 1 ){
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html: '<h3>Por favor agregue un item (Producto) al inventario</h3>',
            confirmButtonText: 'Ok'
        });
    }else if( ban_f === 0 && ban_c === 0 ){
        get_coordenadas_cti();
    }else{
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html: '<h3>Hay campos sin llenar</h3>',
            confirmButtonText: 'Ok'
        });
    }
}

function get_coordenadas_cti() {
    if (!_empty(data_control_inventario)){
        $("#txtlat_solicitud").val(0);
        $("#txtlong_solicitud").val(0);
        map.once('locationfound', guardar_cti_db);
        map.on('locationerror', onLocationError_cti);
        map.locate({ setView: true, maxZoom: 15 });
    }else{                        
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html: '<h3>No hay datos seleccionados</h3>',
            confirmButtonText: 'Ok'
        });
    }
}

function guardar_cti_db(e){ 
    $('#btn_guardar_cti').prop('disabled', true);
    nombre_cliente = $('txtclientesinventario').val();
    id_cliente_cti = $('#txtIdClienteCti').val();
    fecha_tel = fechaDispositivo();
    fecha_actual = fecha_actual_cti();
    token_cti = token_inventario(id_cliente_cti);
    $("#txtLatitudCti").val(e.latlng.lat);
    $("#txtLongitudCti").val(e.latlng.lng);
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
    marker = new L.Marker(e.latlng, { draggable: false });
    map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng), 18);
    map.addLayer(marker); 
    var data_insertar = $('#frm_cti_items').serializeArray();
    var latitudCti = $("#txtLatitudCti").val();
    var longitudCti = $("#txtLongitudCti").val();
    data_insertar.push({name: 'latitud_ini', value: latitudCti});
    data_insertar.push({name: 'longitud_ini', value: longitudCti});
    data_insertar.push({name: 'nombre_cliente', value: nombre_cliente});
    data_insertar.push({name: 'id_cliente', value: id_cliente_cti});
    data_insertar.push({name: 'fecha_tel', value: fecha_tel});
    data_insertar.push({name: 'id_usuario', value: arrg_Credls['us_cod']});
    data_insertar.push({name: 'token_cti', value: token_cti});
    data_insertar.push({name: 'eliminados', value: token_eliminar_ctis});
    data_insertar.push({name: 'token_item_eliminar', value: token_elim});
    $.when($("#content_carga").stop(true, true).show()).done(function(x) {
        $.ajax({
            url: 'C_control_inventario/Ctr_control_inventario/guardar_items_cti',
            type: 'POST',
            dataType: 'JSON',           
            data: data_insertar,
            timeout: 7777
        }).done(function(_resp) {
            if (_resp.rs == true) {

            } else {
                arrg_dataSincro = [];
            }
        }).always(function(_resp, textStatus, errorThrown) {
            if (textStatus == "success") {
                if (_resp.rs == true) {       
                    guardar_cti_local('NO','SI');  
                    data_insertar = [];
                } else {
                    $.when($("#content_carga").stop(true, true).hide()).done(function(x) {                        
                        arrg_dataSincro = [];
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html: _resp.errores,
                            confirmButtonText: 'Ok'
                        });
                    });
                }
            } else {
                $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                    _ajax_error_modulos_menu(_resp.status, _resp.readyState, _resp.statusText,'controlInventario','');
                });
            }
        });
    });
}

function guardar_cti_local(pendienteX, enviadoX) { 
    var returnArray = {id_producto: [], cantidad: [], fecha_vencimiento: [], token: [], nombre_producto: [] };
    var formArray = $('#frm_cti_items').serializeArray();
    var array_guaradar_local = [];
    var array_editar_local = [];
    var array_eliminar_local = [];
    
    for (var i = 0; i < formArray.length; i++){
        if(formArray[i]['name'] == "txtCtiId[]"){            
            returnArray.id_producto.push(formArray[i]['value']);
        }else if(formArray[i]['name'] == "txtCantidadCti[]"){
            returnArray.cantidad.push(formArray[i]['value']);
        }else if(formArray[i]['name'] == "txtFechaVencimientoCti[]"){
            returnArray.fecha_vencimiento.push(formArray[i]['value']);
        }else if(formArray[i]['name'] == "txtnombreCti[]"){
            returnArray.nombre_producto.push(formArray[i]['value']);
        }
    }
    var con = 0;
    datos_ingre = {};   
    $.each(returnArray.id_producto, function(index, val) {
        datos_ingre = {
            id_producto: val,
            nombre_producto:  returnArray.nombre_producto[index],
            cantidad: returnArray.cantidad[index],
            fecha_vencimiento: returnArray.fecha_vencimiento[index],
            id_cliente: $('#txtIdClienteCti').val(),
            nombre_cliente: $('#txtclientesinventario').val(),
            id_usuario: arrg_Credls['us_cod'],
            fecha_telefono: fecha_tel,
            latitud_ini: $("#txtLatitudCti").val(),
            longitud_ini: $("#txtLongitudCti").val(),
            fecha: fecha_actual,
            token_cti: token_cti,
            token: token_cti + val,
            enviado: enviadoX,
            pendiente: pendienteX,
            opcion: ''


            
        }; 
        // array_guaradar_local.push(datos_ingre);
        if($.inArray(val, token_editar_ctis) === -1 && $.inArray(val, token_eliminar_ctis) === -1){
            datos_ingre.opcion = 'agregado'
            array_guaradar_local.push(datos_ingre);
        } else{
            datos_ingre.opcion = 'editado';
            array_editar_local.push(datos_ingre);
        }
    });    
        if(token_eliminar_ctis.length > 0){
            token_eliminar_ctis.forEach( function(val, index) {
                array_eliminar_local.push(token_elim + val);
            });
        }
    Promise.all([   
        agregar_registro_local(array_guaradar_local),
        editar_registro_local(array_editar_local),
        eliminar_registro_local(array_eliminar_local, pendienteX, enviadoX),
        consultar_cola_cti()
    ])
    .then(respuestas => {
        $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
            if(pendienteX == 'SI')
                mensaje = 'Inventario guardado temporalmente!';
            else
                mensaje = 'Inventario guardado correctamente';
            Swal.fire({
                type: 'success',
                title: mensaje,
                showConfirmButton: true,
            }).then((result) => {
                $('#m-control-inventario').modal('toggle');
                $('#btn_guardar_cti').prop('disabled', false);
            });
        });
    })
    .catch(error => {
        $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
            console.log('Ocurrio un error');
        });
    });
    
}

function agregar_item_html() { // usando actualmente ------------------------------------------------
    var datos_ingre = {};
    // var token_item = TokenMarcacion();
    var fecha_tel = fechaDispositivo();
    let producto = $('#txtCodigoCti').val();
    let fecha_vencimiento = $("#txt_fecha_vencimiento").val();
    let cantidad = $("#txt_cantidad_producto").val();
    var nombre_producto = $('#txtProductoCti').val();
    var id_cliente = $('#txtIdClienteCti').val();
    var nombre_cliente = $('#txtclientesinventario').val();
    var html = '';
    html += `<div class="card card_cti_ nv">
                <div class="d-flex">
                    <div class="mr-auto align-self-center p-2"><i class="far fa-calendar-alt fa-lg"></i> <span id="fechat${ContAd}">${fecha_tel}</span></div>
                    <div class="p-2"><i class="fas fa-trash-alt fa-2x btn_eliminar_cti nv"></i></div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <h5 id="prodnombre${ContAd}">${nombre_producto}</h5>
                        </div>
                    </div> 
                </div>
                <button type="button" class="btn btn-light" data-toggle="collapse" data-target="#CAT${ContAd}" aria-controls="CAT${ContAd}">
                    <h5 style="margin-bottom: 0px; margin-top:3px;"><i class="far fa-eye fa-lg"></i> SKU: <span id="skunombre${ContAd}">${producto}</span></h5>
                </button>
                <div id="CAT${ContAd}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion_cti">
                    <div class="card-body">
                        <input type="hidden" id="CtiId${ContAd}" name="txtCtiId[]" value="${producto}">
                        <input type="hidden" id="nombreCti${ContAd}" name="txtnombreCti[]" value="${nombre_producto}">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="titulo"><span class=""></span> Vencimiento:</div>
                                <input type="date" name="txtFechaVencimientoCti[]" class="form-control form-control-sm nv cti">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="titulo">Cantidad</div>
                                <input type="number" name="txtCantidadCti[]" class="form-control form-control-sm nv cti1" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="titulo"><span class=""></span>Cliente:</div>
                                <textarea cols="30" class="form-control form-control-sm" rows="2" disabled>${nombre_cliente}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    $("#accordion_cti").prepend(html);
    producto = '';
    fecha_vencimiento = '';
    cantidad = '';
    nombre_producto = '';
    document.getElementById("form-control-inventario").reset();
    $( '#form-control-inventario' ).find('.select2-container--default').removeClass("is-invalid");
    $( '#form-control-inventario' ).find('.select2-container--default').removeClass("is-valid");
    $( '#txt_cantidad_producto' ).removeClass("is-valid");
    ContAd++;
}

function Agregar_tr_cti() {
    var html = '';
    if($('#txtIdClienteCti').val() != '' && $('#txtCodigoCti').val() != '')
    {
       agregar_item_html();

    }else{
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html: '<h3>Hay campos sin llenar</h3>',
            confirmButtonText: 'Ok'
        });
    }
}

//---------------------------------- ENVIO COLA CTI -----------------------------------------------------
function envio_cola_cti(indice, elements) {
  
    var v_ban = 0;
    if (indice < elements.length) {
        $.ajax({
            url: 'C_control_inventario/Ctr_control_inventario/procesar_cola_cti',
            type: "POST",
            data: elements[indice],
            dataType: "JSON",
            timeout: 14777
        }).done(function(_resp) {
        }).always(function(_resp, textStatus, errorThrown) {
            if (textStatus == "success") {
                if (_resp.rs == true) {
                    var actived = dataBaseAppSDV.result;
                    var objectStore = actived.transaction(["tbl_control_inventario"], "readwrite").objectStore("tbl_control_inventario");
                    var request = objectStore.get(elements[indice].token);
                    request.onerror = function(event) {
                    };
                    request.onsuccess = function(event) {
                        var data = request.result;
                        if (elements[indice].pendiente === 'SI') {
                            data.pendiente = 'NO';
                            data.enviado = 'SI';
                        }  
                        var requestUpdate = objectStore.put(data);
                            requestUpdate.onerror = function(event) {
                        };
                        requestUpdate.onsuccess = function(event) {
                            alertify.success('Registro enviado exitosamente!');
                            envio_cola_cti(indice + 1, elements);
                            consultar_cola_cti()
                        };
                    };
                } else {
                    $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html: _resp.errores,
                            confirmButtonText: 'Ok'
                        });
                    });
                }
            } else {
                $.when($("#content_carga").stop(true, true).show()).done(function(x) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<strong>Conectese a una red...</strong>',
                            // html:'<strong>Error de envio de cola de registros...</strong>',
                            confirmButtonText: 'Ok'
                        });
                    });
                });
            }
        });
    }
}

function onLocationError_cti(e) {
    $("#txtLatitud").val(0);
    $("#txtLongitud").val(0);
    console.log('error');
    Swal.fire({
        type: 'info',
        title: 'GPS apagado o geolocalización bloqueada',
        html: '<p>Por favor ver el tutorial para desbloquear la geolocalización</p>',
        showConfirmButton: true,
        confirmButtonText: 'Ok'
    }).then((result) => {
        if (result.value) {
            $.when($("#ModalTutorial").modal("show")).done(function(x) {
                $("#imgtutorial").attr("src", "../Public/Img/Permitir_GPS.gif");
                // console.log(':)');
            });
        } else {            
            location.reload();
        }
    });
}

function consultar_cola_cti(){
    arrgColaCti         = [];
    var conta_cola_cti  = {};
    cant_cola_cti_      = 0;
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
                dataResult.forEach( x => {
                    if( !dataResult.hasOwnProperty(x.id_cliente)){
                        conta_cola_cti[x.id_cliente] = {
                          toke_val: []
                        }
                    }
                    conta_cola_cti[x.id_cliente].toke_val.push({
                        valor: x.id_cliente
                    })                    
                })
                arrgColaCti = dataResult;
                cant_cola_cti_ += parseInt(Object.entries(conta_cola_cti).length);
                $("#RegisCola").html('');
                $("#RegisCola").html(cant_cola_cti_);
                resolve(1);
            };
        }
        transaccion.onerror = function() {
             reject(0);
        };
    });
}

//Prepara el modal de catalogo de productos 
function cargar_m_catalogo_cti(arrg_datos,arrg_items) {
    $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
        Promise.all([
            cargar_ls_catalogo_reclamos()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                console.log('Carga de productos completada!');
                opcion_catalogo = 'cti';
                $('#m-catalogo-reclamos').modal('toggle');
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                console.log(error);
            });
        });
    });
}

function validate_select2(data, campo) {
    var v = 0;    
    if (_empty(data)) 
    {
        v = 0;
        $( '#'+ campo ).find('.select2-container--default').removeClass("is-valid").addClass("is-invalid");
    } else {
        v = 1;
        $( '#'+ campo ).find('.select2-container--default').removeClass("is-invalid").addClass("is-valid");
    }
    return v;
}

function v_cantidades (data, campo){
    var v = 0;        
    if(data != ''){
        if ( /^[0-9]{1,3}$/i.test(data) ) {
          $( '#'+ campo ).removeClass('is-invalid'); $( '#'+ campo ).addClass('is-valid');
          v = 1;
        } else {
            $( '#'+ campo ).removeClass('is-valid'); $( '#'+ campo ).addClass('is-invalid');
            v = 0;
        }
    }else {
        v = 0
    }
    return v;
}

function validate_txt_cti() {
}

function consultar_cti_cliente(id_cliente, attrdis){
    var fechaActual = fecha_actual_cti();
    data_cti_cliente = [];
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_control_inventario', 'readonly'),
        store = transaccion.objectStore('tbl_control_inventario'),
        indice = store.index('by_cliente'),
        cursor = indice.openCursor(id_cliente)
        cursor.onsuccess = function(event) {
        let dat = event.target.result;
        if (dat) {
            dataResult.push(dat.value);
            dat.continue(); 
        } else {

            const ctrlInv = dataResult.filter(ctrlinv => ctrlinv.fecha != fechaActual);
            dataResult.forEach( x => {
                var FechaInventario
                //LOS PRODUCTOS DE LA FECHA ACTUAL
                if(attrdis){
                    if( !dataResult.hasOwnProperty(x.opcion) && !dataResult.hasOwnProperty(x.opcion)){
                        if(x.opcion != 'eliminado' && x.fecha == fecha_actual_cti())
                        {
                          data_cti_cliente.push(x)
                        }
                    }
                //LOS PRODUCTOS DE AYER
                }else{
                    if(!dataResult.hasOwnProperty(x.opcion) && !dataResult.hasOwnProperty(x.opcion)){
                        if(x.opcion != 'eliminado' && x.fecha != fecha_actual_cti())
                        {
                            data_cti_cliente.push(x)
                        }   
                    }
                }
            })
        };
    }
    transaccion.oncomplete = function() {
        Promise.all([
            DB_CargarCatalogoP()
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                cargar_items_cti_html(data_cti_cliente, attrdis);
                if ($('#m-control-inventario').is(':hidden')){
                    $('#m-control-inventario').modal('toggle');
                }else{
                    $("#C_pregInvAnt").hide();
                    $("#Content_Inv").show();
                }
                // BD_lista_catalogo_productos()
            });
        })
        .catch(error => {
            console.log('ERROR :c '+error);
        });
    };
    transaccion.onerror = function() {
         reject(0);
    };
}

function cargar_items_cti_html(arreglo, disabledattr) {
    var html = ``;
    arreglo.forEach(function(valor, index) {
        var tk   = token_registro()+index;
        html += `
            <div class="card card_cti_ nv">
                <div class="d-flex">
                    <div class="mr-auto align-self-center p-2"><i class="far fa-calendar-alt fa-lg"></i><span id="fechat${tk}">${valor.fecha_telefono}</span></div>
                    <div class="p-2"><i class="fas fa-trash-alt fa-2x btn_eliminar_cti"></i></div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <h5 id="prodnombre${tk}">${valor.nombre_producto}</h5>
                        </div>
                    </div> 
                </div>
                <button type="button" class="btn btn-light" data-toggle="collapse" data-target="#CAT${tk}" aria-controls="CAT${tk}">
                    <h5 style="margin-bottom: 0px;"><i class="far fa-eye fa-lg"></i> SKU: <span id="skunombre${tk}">${valor.id_producto}</span></h5>
                </button>
                <div id="CAT${tk}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion_cti">
                    <div class="card-body">
                        <input type="hidden" name="txtTokenCti[]" value="${valor.token_cti}" readonly>
                        <input type="hidden" id="CtiId${tk}" name="txtCtiId[]" value="${valor.id_producto}">       
                        <input type="hidden" id="nombreCti${tk}" name="txtnombreCti[]" value="${valor.nombre_producto}">  
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="titulo"><span class=""></span> Vencimiento:</div>
                                <input type="date" class="form-control form-controdate cti" name="txtFechaVencimientoCti[]" value="${valor.fecha_vencimiento}">
                            </div>
                        </div>                                   
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="titulo">Cantidad</div>
                                <input type="number" class="form-control form-control-sm cti1" name="txtCantidadCti[]" value="${valor.cantidad}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="titulo"><span class=""></span>Cliente:</div>
                                <textarea cols="30" class="form-control form-control-sm" rows="2" disabled>${valor.nombre_cliente}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div> `;
    });
    $("#accordion_cti").append(html);
}

function mostarInfoCti(){
    $("#modalCatalogo").modal("toggle");
    $("#showDataSN").empty();
    $('#catalogoDtable').DataTable().destroy();
    Agregar_tr_cti();
    blockFCli = 0;
    BandAdEd = 0;
}

function verificar_cola_cti(){
    return new Promise(function(resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_control_inventario', 'readonly'),
        store = transaccion.objectStore('tbl_control_inventario'),
        indice = store.index('by_Cola'),
        cursor = indice.openCursor('SI')
        cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {

                cola_solo_cti = parseInt(Object.keys(dataResult).length);
                resolve(1);
            };
        }
        transaccion.onerror = function() {
             reject(0);
        };
    });
}

function cerrar_modal_cti(){
    document.getElementById("form-control-inventario").reset();
    document.getElementById("frm_cti_items").reset();
    $('#txtclientesinventario').val(''); 
    $('#txtIdClienteCti').val(''); 
    token_eliminar_ctis = [];
}

function editar_registro_local(data_editar) {
    if(data_editar.length != 0){
        var actived = dataBaseAppSDV.result;
        var objectStore = actived.transaction(["tbl_control_inventario"], "readwrite").objectStore("tbl_control_inventario");
        var request = {};
        var requestUpdate = {};

        data_editar.forEach(function(valor, index) {
            request = objectStore.get(valor.token);
            request.onerror = function(event) {
            };
            request.onsuccess = function(event) {
                requestUpdate = objectStore.put(valor);
                requestUpdate.onerror = function(event) {
                };
                requestUpdate.onsuccess = function(event) {
                };
            };            
        }); 
    }
}

function eliminar_registro_local(data_eliminar, pendiente, enviado) {
    if(data_eliminar.length != 0){
        data_eliminar.forEach(function(valor, index) {
            var actived = dataBaseAppSDV.result;
            var objectStore = actived.transaction(["tbl_control_inventario"], "readwrite").objectStore("tbl_control_inventario");
            var request = objectStore.get(valor);
            request.onerror = function(event) {
            };
            request.onsuccess = function(event) {
                var data = request.result;
                    data.opcion = 'eliminado';
                    data.pendiente = pendiente;
                    data.enviado = enviado;
                var requestUpdate = objectStore.put(data);
                    requestUpdate.onerror = function(event) {
                };
                requestUpdate.onsuccess = function(event) {
                    
                };
            };
        }); 
    }
}

function agregar_registro_local(data_insertar) {
    var request = '';
    var active = dataBaseAppSDV.result;
    var data = active.transaction(["tbl_control_inventario"], "readwrite");
    var object = data.objectStore("tbl_control_inventario");
    data_insertar.forEach(function(valor, index) {
        request = object.put(valor);
    }); 
    request.onerror = function(e) {
        console.log(request.error.name + '\n\n' + request.error.message);
    };
    data.oncomplete = function(e) {   
    }; 
}

function TokenMarcacion() {
    var hoy = new Date();
    var mes = (hoy.getMonth() + 1);
    var dia = hoy.getDate();
    var hora = hoy.getHours();
    var minutos = hoy.getMinutes();
    var segundos = hoy.getSeconds();
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
    var fecha = String(hoy.getFullYear()) + String(mes) + String(dia);
    var hora = String(hora) + String(minutos) + String(segundos);
    var TokenMarcacion = String(fecha) + String(hora) + String(arrg_Credls['usuario']);
    return TokenMarcacion;
}

function fecha_actual_cti() {
    var hoy = new Date();
    var mes = (hoy.getMonth() + 1);
    var dia = hoy.getDate();
    var hora = hoy.getHours();
    var minutos = hoy.getMinutes();
    var segundos = hoy.getSeconds();
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
    var fecha = String(hoy.getFullYear()) +'-'+ String(mes) +'-'+ String(dia);
    return fecha;
}

function enviar_cola_cti(conect){
    // consultar_cola_cti();
    var TotalRegisCola = 0;
    TotalRegisCola = $("#RegisCola").text();
    arrgColaCti = [];
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
            Promise.all([   
                consultar_cola_cti()
            ])
            .then(respuestas => {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        envio_cola_cti(0, arrgColaCti);
                    });
                });  
            })
            .catch(error => {
                $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                    console.log('Ocurrio un error');
                });
            });
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

function token_inventario(id_cliente) {
    // idproducto -----------------
    // fecha
    // idcliente
    // idusuario
    var hoy = new Date();
    var mes = (hoy.getMonth() + 1);
    var dia = hoy.getDate();
    var hora = hoy.getHours();
    var minutos = hoy.getMinutes();
    var segundos = hoy.getSeconds();
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
    var fecha = String(hoy.getFullYear()) + String(mes) + String(dia);
    var TokenMarcacion = arrg_Credls['privilegio'] == 15 ? 
    fecha + String(arrg_Credls['ruta_desarrollador']) + id_cliente : 
    fecha + String(arrg_Credls['us_cod']) + id_cliente;
    return TokenMarcacion;
}

function token_registro(id_cliente) {
    // idproducto -----------------
    // fecha
    // idcliente
    // idusuario
    var hoy = new Date();
    var mes = (hoy.getMonth() + 1);
    var dia = hoy.getDate();
    var hora = hoy.getHours();
    var minutos = hoy.getMinutes();
    var segundos = hoy.getSeconds();
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
    return String(hoy.getFullYear()) + String(mes) + String(dia);
}