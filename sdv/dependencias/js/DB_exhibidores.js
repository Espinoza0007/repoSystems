var DataJSON_Exh = [];var table = null;
var DataJSON_Cli = [];var table_Cli = null;
var arrg_Credls = [];
function DB_ListarExhibidores(tipoac,tipobande) {
    bandera_tipoAC = tipobande;
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Promise.all([
                DB_CatalogoExhibidores(),
                DB_CargaCredenciales(),
                DB_FiltroExhibidorTipo(),
                DB_FiltroMotivosEliminar(),
                DB_FiltroMotivosDevolucion()
                ])
            .then(respuestas =>{
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        TipoAc_Exh = tipoac;
                        $("#ModalExh").modal("toggle");
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
        });
    });
}

function DB_ListarClientes(tipoac,tipobande) {
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Promise.all([
                DB_CargaFiltrosAvanced(),
                DB_CargarFiltroClientes()
                ])
            .then(respuestas =>{
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        $("#ModalCli").modal("toggle");
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
        });
    });
}

/*------- FILTRO CLIENTES 06/07/2021 -----------*/

function DB_CargarFiltroClientes(){
    DataTBClientes = "";
    var p_dias = '';
    var EstadoCensado = '';
    DataJSON_Cli = "";
    return new Promise(function(resolve, reject){
        var datosObtenidos = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_clientes', 'readonly'),
            store = transaccion.objectStore('tbl_clientes'),
            indice = store.index('by_estado_w'),
            cursor = indice.openCursor("1")
        cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                datosObtenidos.push(dat.value);
                dat.continue();
            } else {
                DataJSON_Cli = datosObtenidos
                resolve(1);
            };
        }
        transaccion.onerror = function() {
            console.log('ERROR INESPERADO -> BUSQUEDA PRODUCTO');
            reject(0);
        };
    });


    
}

/*00000000000000000000000000000000000000000000000000000000000000*/
function DB_BusquedaProducto(sku) {
    var datosObtenidos = [];
    var active = dataBaseAppSDV.result;
    let transaccion = active.transaction('tbl_catalogo_productos', 'readonly'),
        store = transaccion.objectStore('tbl_catalogo_productos'),
        indice = store.index('by_Cat_Id'),
        cursor = indice.openCursor(sku)
    cursor.onsuccess = function(event) {
        let dat = event.target.result;
        if (dat) {
            datosObtenidos.push(dat.value);
            dat.continue();
        } else {
            alert(datosObtenidos);
            return datosObtenidos;
        };
    }
    transaccion.onerror = function() {
        console.log('ERROR INESPERADO -> BUSQUEDA PRODUCTO');
    };
}
/*MODIFICANDO*/





function DB_CatalogoExhibidores() {
    return new Promise(function(resolve, reject) {
        let datosObtenidos = [];
        let db = dataBaseAppSDV.result;

        let transaccion = db.transaction('tbl_productos', 'readonly');
        let store = transaccion.objectStore('tbl_productos');
        let indice = store.index('by_Subf_Fa_Id');

        let categorias = ["4", "8","9","10"];
        let pendientes = categorias.length;

        categorias.forEach(categoria => {
            let cursor = indice.openCursor(IDBKeyRange.only(categoria));

            cursor.onsuccess = function(event) {
                let cursorResult = event.target.result;

                if (cursorResult) {
                    datosObtenidos.push(cursorResult.value);
                    cursorResult.continue();
                } else {
                    pendientes--;
                    if (pendientes === 0) {
                        DataJSON_Exh = datosObtenidos;
                        resolve(1);
                    }
                }
            };

            cursor.onerror = function() {
                console.error(`Error en categoría ${categoria}`);
                reject(0);
            };
        });
    });
}



function DELETE_DB_CatalogoExhibidores() {
    DataJSON_Exh = [];
    return new Promise(function(resolve, reject) {


        var datosObtenidos = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_productos', 'readonly'),
            store = transaccion.objectStore('tbl_productos'),
            indice = store.index('by_Subf_Fa_Id'),
            cursor = indice.openCursor(4)
        cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                datosObtenidos.push(dat.value);
                dat.continue();
                resolve(1);
            } else {
                // alert(datosObtenidos);
                return datosObtenidos;
                resolve(1);
            };
        }
        transaccion.onerror = function() {
            console.log('ERROR INESPERADO -> BUSQUEDA PRODUCTO');
        };


        // var active = dataBaseAppSDV.result;
        // var data = active.transaction('tbl_productos', "readonly");
        // var object = data.objectStore('tbl_productos');
        // var elements = [];
        // object.openCursor().onsuccess = function(e) {
        //     var result = e.target.result;
        //     if (result === null) {
        //         return;
        //     }
        //     elements.push(result.value);
        //     result.continue();
        // };
        // data.oncomplete = function() {
        //     DataJSON_Exh = elements;
        //     resolve(1);
        // };
        // data.onerror = function() {
        //     console.log('ERROR INESPERADO -> FILTRO EXHIBIDORES');
        //     reject(0);
        // };



    });
}
function DB_FiltroExhibidorTipo() {
    var arr_dat = [];
    return new Promise(function(resolve, reject) {
        var datosObtenidos = [];
        var active = dataBaseAppSDV.result;
        var data = active.transaction(['tbl_tipo_exhibidores'], "readonly");
        var object = data.objectStore('tbl_tipo_exhibidores');
        var elements = [];
        object.openCursor().onsuccess = function(e) {
            var result = e.target.result;
            if (result === null) {
                return;
            }
            datosObtenidos.push(result.value);
            result.continue();
        };
        data.oncomplete = function() {
            var filtro_html = ``;
            filtro_html += `<select class="custom-select" id="txtipoexh" name="txtipoexh">
            <option value="">TODOS LOS TIPOS</option>`;
            datosObtenidos.forEach(function(filall, index, arrgfilall) {
                filtro_html += `<option value="${filall.Subf_nombre}">${filall.Subf_nombre}</option>`;
            });
            filtro_html += `</select>`;
            $("#S_filtroSubFamilia").empty().html(filtro_html);
            resolve(1);
        };
        data.onerror = function() {
            reject(0);
        };
    });
}
function DB_BloqAddExhQtiene(idx_cli) {
    var actived = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_clientes"], "readwrite").objectStore("tbl_clientes");
    var request = objectStore.get(idx_cli);
    request.onerror = function(event) {
        // Handle errors!
    };
    request.onsuccess = function(event) {
        // Get the old value that we want to update
        var data = request.result;

        // update the value(s) in the object that you want to change
        data.Cli_actu_exh = 1;
        data.Cli_bloq_exh = 1;
        // Put this updated object back into the database.
        var requestUpdate = objectStore.put(data);
        requestUpdate.onerror = function(event) {
            // Do something with the error
        };
        requestUpdate.onsuccess = function(event) {
            // Success - the data is updated!
        };
    };
}
function DB_BusquedaProducto(sku) {
    var datosObtenidos = [];
    var active = dataBaseAppSDV.result;
    let transaccion = active.transaction('tbl_catalogo_productos', 'readonly'),
        store = transaccion.objectStore('tbl_catalogo_productos'),
        indice = store.index('by_Cat_Id'),
        cursor = indice.openCursor(sku)
    cursor.onsuccess = function(event) {
        let dat = event.target.result;
        if (dat) {
            datosObtenidos.push(dat.value);
            dat.continue();
        } else {
            alert(datosObtenidos);
            return datosObtenidos;
        };
    }
    transaccion.onerror = function() {
        console.log('ERROR INESPERADO -> BUSQUEDA PRODUCTO');
    };
}
function DB_ValidarEstadoEnvioCola(agenteX, estadoX, EstadoMa, TokenK, k) {
    var actived = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_marcaciones"], "readwrite").objectStore("tbl_marcaciones");
    var request = objectStore.get(TokenK);
    request.onerror = function(event) {

    };
    request.onsuccess = function(event) {
        var data = request.result;
        data.Pendiente = 'NO';
        var requestUpdate = objectStore.put(data);
        requestUpdate.onerror = function(event) {
        };
        requestUpdate.onsuccess = function(event) {
            Promise.all([
                    Consultar_Colas()
                ])
                .then(respuestas => {
                    alertify.success('Registro enviado exitosamente!');
                    envio_RecuRegistrosCola(k + 1, arreg_offline);
                })
                .catch(error => {
                    $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                        // $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        console.log(error);
                        // });
                    });
                });
        };
    };
}
/*-------------------------------------------------------------*/
/*-----------------------TOKEN EXHIBIDOR-----------------------*/
/*-------------------------------------------------------------*/
function TokenAC_Exh(){
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
    var TokenMarcacion = String(fecha) + String(hora) + String(arrg_Credls['usuario'])+Cod_aleatorio(4);
    return TokenMarcacion;
}
function Cod_aleatorio(lon){
    code = "";
    var chars = '0123456789abcdef'
    for (x=0; x < lon; x++)
    {
    rand = Math.floor(Math.random()*chars.length);
    code += chars.substr(rand, 1);
    }
    return String(code);
}
function ConvertirBase64Img(src, callback, outputFormat,idimage) {
    const img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = () => {
        const canvas = document.createElement(idimage);
        const ctx = canvas.getContext('2d');
        let dataURL;
        canvas.height = img.naturalHeight;
        canvas.width = img.naturalWidth;
        ctx.drawImage(img, 0, 0);
        dataURL = canvas.toDataURL(outputFormat);
        callback(dataURL);
    };
    img.src = src;
    if (img.complete || img.complete === undefined) {
        img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
        img.src = src;
    }
}
function tomar_captura(idexhibidor) {
  let region = document.querySelector("body"); // whole screen
  html2canvas(region, {
    onrendered: function(canvas) {
      let pngUrl = canvas.toDataURL(); // png in dataURL format
      let img = document.querySelector(".screen");
      img.src = pngUrl; 

      // here you can allow user to set bug-region
      // and send it with 'pngUrl' to server
    },
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

function DB_IniciarCPSesionExh() {
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
                    DB_UsuarioLogueado();
                    // DB_CantidadEnCola('tbl_observacionexh');
                    Consultar_Colas();
                    DB_CargaCredenciales();
                    DB_FiltroMotivosEliminar();
                    DB_FiltroMotivosDevolucion();



                    map.once('locationfound', geoUbicacinCliente);
                    map.on('locationerror', onLocationError_Exh);
                    map.locate({setView: true, maxZoom: 15});


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
            title: 'Aviso!',
            type: 'error',
            html:'<h5>Error inesperado, por favor comunicarlo a Sistemas de Venta</h5>',
            confirmButtonText:'Ok'
        });
    };
}
/*-------------------------------------------------------------*/
/*-------------------CARGAR LAS CREDENCIALES-------------------*/
/*-------------------------------------------------------------*/
function DB_CargaCredenciales(arrg_items) {
    arrg_Credls = [];
    return new Promise(function(resolve, reject) {
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_usuarios', "readonly");
        var object = data.objectStore('tbl_usuarios');
        var elements = [];
        object.openCursor().onsuccess = function(e) {
            var result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        };
        data.oncomplete = function() {
            arrg_Credls['usuario'] = elements[0].usuario;
            arrg_Credls['clave'] = elements[0].clave;
            arrg_Credls['privilegio'] = elements[0].privilegio;
            arrg_Credls['ruta_app'] = elements[0].ruta_app;
            arrg_Credls['us_cod'] = elements[0].us_cod;
            arrg_Credls['nombre_us'] = elements[0].nombre_us;
            arrg_Credls['pais'] = elements[0].pais;
            arrg_Credls['Id_Distribuidora'] = elements[0].Id_Distribuidora;
            arrg_Credls['TipoUsuario'] = elements[0].TipoUsuario;
            resolve(1);
        };
        data.onerror = function() {
            reject(0);
        };
    });
}

/*VALIDACIONES*/
function V_CoordenadasLL_ContarOK(data, campo, ordencampo, etiqueta) {
var data_C = data == undefined ? '' : data.trim();
    // var data_C = data.trim();
    var v = 0;
    // console.log(campo+' '+data);
    if (data_C == 0) {
        v = 0;
        $("#" + campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>' + etiqueta + '</strong> es obligatoria.';
        $("#error-mjs-" + ordencampo).html('La <strong>' + etiqueta + '</strong> es obligatoria.');
    } else {
        if (data_C != "") {
            var data_E = /^-?[0-9]\d*(\.\d+)?$/gm
            if (data_E.test(String(data_C))) {
                v = 1;
                $("#" + campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            } else {
                v = 0;
                $("#" + campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'En la <strong>' + etiqueta + '</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-" + ordencampo).html('En la <strong>' + etiqueta + '</strong> solo se permiten n&uacute;meros.');
            }
        } else {
            v = 0;
            $("#" + campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El campo <strong>' + etiqueta + '</strong> es obligatorio.';
            $("#error-mjs-" + ordencampo).html('El campo <strong>' + etiqueta + '</strong> es obligatorio.');
        }
    }

    return v;
}
/*CAMBIOS 04/08/2021*/
/*===>>>>> ACTUALIZAR EL ESTADO DEL CLIENTE CENSADO*/
function DB_EstadoExhibidor_Change(IdClienteSelect){
    var FechaTelefono = fechaDispositivo();
    var actived = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_clientes"], "readwrite").objectStore("tbl_clientes");
    // console.log(tokenactual);
    var request = objectStore.get(IdClienteSelect);
    request.onerror = function(event) {
      // Handle errors!
    };
    request.onsuccess = function(event) {
      // Get the old value that we want to update
      var data = request.result;
    
      // update the value(s) in the object that you want to change
      data.Cli_ac_exhibidor = 1;
      data.Cli_ul_fecha_ac_exhibidor = FechaTelefono;
      data.Cli_estado_csexh = ValiConSinExh;
      // Put this updated object back into the database.
      var requestUpdate = objectStore.put(data);
       requestUpdate.onerror = function(event) {
         // Do something with the error
       };
       requestUpdate.onsuccess = function(event) {
         // Success - the data is updated!
        //  console.log('Id_Cliente => '+IdClienteSelect);
       };
    };
}
/*CAMBIOS 11/08/2021*/
function DB_CargaFiltrosAvanced(){
    // arrg_Credls = [];
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_filtros', "readonly");
        var object = data.objectStore('tbl_filtros');
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
            // console.log('DB_CargaFiltrosAvanced => '+elements[1].ValueEX);
            arrg_Credls['FiltroDiaVisitaAC'] = elements[1].ValueEX;
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}