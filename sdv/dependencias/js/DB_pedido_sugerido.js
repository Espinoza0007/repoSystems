var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;var CantCola = 0;
var arrg_ls_Pedido = [];var arrg_pedido_env = [];var arrg_pedido_envCola = [];var warn_on_unload = '';var arrg_familias = [];var Datetable_Pedidos = null;var arrg_check_s = [];
var error_Sku_Descr = new Array(2);var error_Sku = new Array(2);
var CantidaSolGlo = 0,CantidaPedidoGlo = 0;var arrg_ls_Motivos = [];
error_Sku[0] = new Array(2);error_Sku[1] = new Array(2);
error_Sku[2] = new Array(2);error_Sku[3] = new Array(2);
error_Sku[4] = new Array(2);
error_Sku_Descr[0] = new Array(2);error_Sku_Descr[1] = new Array(2);
error_Sku_Descr[2] = new Array(2);error_Sku_Descr[3] = new Array(2);
error_Sku_Descr[4] = new Array(2);
function sortJSON(data, key, orden) {
    return data.sort(function (a, b) {
        var x = a[key],
        y = b[key];
        if (orden === 'asc') {
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        }
        if (orden === 'desc') {
            return ((x > y) ? -1 : ((x < y) ? 1 : 0));
        }
    });
}
function DB_Pedido_Sugerido() {
    return new Promise(function(resolve, reject) {
        var active = dataBaseAppSDV.result;
        var data = active.transaction(['tbl_PedSug_PedidosDet'], "readonly");
        var object = data.objectStore('tbl_PedSug_PedidosDet');
        var elements = [];
        object.openCursor().onsuccess = function(e) {
            var result = e.target.result;
            if (result) {
                arrg_ls_Pedido.push(result.value);
                result.continue();
            } else {
            }
        };
        data.oncomplete = function() {
            resolve(1);
            
        };
        data.onerror = function() {
            reject(0);
        };
    });
}
function DB_IniciarCPSesionPS() {
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
                    // Consultar_Colas();
                    DB_CargaCredenciales();
                    resolve(1);
                    // console.log('ejecutar funciones');
                };
                datado.onerror = function () {
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h5>Error inesperado [Encuesta Exhibidores, por favor comunicarlo a Sistemas de Venta</h5>',
                        confirmButtonText:'Ok'
                    });
                    // reject(0);
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
            var OBJ_clientes = active.createObjectStore("matchappcenso", {keyPath: 'id'});
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
});
}
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
            // console.log('antes ' +elements[0].usuario);
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
function DB_FiltroFamilia(ls_familias) {
    var filtro_html = ``;
    filtro_html += `
    <div class="gretro-select">
        <select class="custom-select" id="cb_familia" name="cb_familia">
            <option value="">TODAS LAS FAMILIAS</option>`;
            ls_familias.forEach(function(filall, index, arrgfilall) {
                filtro_html += `<option value="${filall}">${filall}</option>`;
            });
            filtro_html += `
        </select>
    </div>`;
    $("#S_filtroFamilias").empty().html(filtro_html);
}
function DB_PedidoMotivos(Tipo) {
        return new Promise(function(resolve, reject) {
            var active = dataBaseAppSDV.result;
            let transaccion = active.transaction('tbl_PedSug_Motivo', 'readonly'),
                store = transaccion.objectStore('tbl_PedSug_Motivo'),
                indice = store.index('by_Tipo'),
                cursor = indice.openCursor(Tipo)
            cursor.onsuccess = function(event) {
                let dat = event.target.result;
                if (dat) {
                    // console.log('VACIO -> FILTRO ITINERARIO');
                    arrg_ls_Motivos.push(dat.value);
                    dat.continue();
                } else {
                    // console.log(datosObtenidos);
                    // filtro_htmlClientesBo +=`<option value="">SELECCIONA UN CLIENTE</option>`;
                    // datosObtenidos.forEach(function(filall, index, arrgfilall) {
                    //     filtro_htmlClientesBo += `<option value="${filall.Iti_Cli_Id}">${filall.Cli_codigo} - ${filall.Cli_nombre}</option>`;
                    // });
                    // filtro_htmlClientesBo += `</select>`;
                    resolve(1);
                }
            };
            transaccion.onerror = function() {
                reject(0);
            };
        });
  
}