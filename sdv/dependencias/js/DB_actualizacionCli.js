var arrg_Credls = [];
var DataTBClientes = "";
var DataJSON_Cli = "";
function DB_CargarFiltrosTodos_VER(arrg_datos,arrg_items) {
    document.getElementById("form_actualizacion").reset();
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Promise.all([
                DB_CargaFiltrosAvanced(),
                DB_CargarFiltroClientes(),
                DB_CargarParametros(),
                DB_FiltroMotivoR()
            ])
            .then(respuestas =>{
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
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
        });
    });
}
function DB_GuardarPermanenteCLIAC(tabla,condicion,arrgdata){
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction([tabla], "readwrite");
        var object = data.objectStore(tabla);
        var request = object.put(arrgdata);
        request.onerror = function (e) {
            console.log('Llave repetida.');
            reject(0);
        };
        data.oncomplete = function (e) {
            if(condicion == 1){
                DB_CantidadEnCola('tbl_cliactutempo');
                Swal.fire({
                    type: 'info',
                    title: 'Registro guardado temporalmente!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }else{
                
            }
            resolve(1);
        };
    });
}
function DB_CargarParametros(){
    arrg_Paramtros = [];
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction(['tbl_parametros'], "readonly");
        var object = data.objectStore('tbl_parametros');
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
            for (var key in elements) {
                if(elements[key].Tipo_Actualizacion == "ACTCLIENTES"){
                    arrg_Paramtros['Fecha_Inicial'] = elements[key].Fecha_Inicial;
                    arrg_Paramtros['Fecha_Final'] = elements[key].Fecha_Final;
                    arrg_Paramtros['TokenK'] = elements[key].TokenActualizacion;
                    arrg_Paramtros['Estado'] = elements[key].Estado;
                }else{}
            }
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_CargarFiltroClientes(){
    DataTBClientes = "";
    var p_dias = '';
    var EstadoCensado = '';
    DataJSON_Cli = "";
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction(['tbl_clientes'], "readonly");
        var object = data.objectStore('tbl_clientes');
        var elements = [];
        object.openCursor().onsuccess = function (e) {
            var result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            // DataJSON_Cli = result.value;
            result.continue();
        };
        data.oncomplete = function () {
            // DataJSON_Cli = "";
            DataJSON_Cli = elements;
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_CargarFiltro(tabla,namcb,idcb,valorBuscar,titulo,idvalidacion){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var atributos_dropdown = {
            class_input:'form-control custom-select outlinenone'
        };
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
            for (var key in elements) {
                arr_dat[key] = {
                    codbx:elements[key].codbx,
                    valor: elements[key].valor
                };
            }
            elements = [];
            var selectes = '';
            selectes = _form_dropdown(namcb,arr_dat,valorBuscar,atributos_dropdown);
            $("#"+idcb+"").empty().html(selectes);
            V_Selec(valorBuscar,namcb,idvalidacion,titulo);
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_CargarFiltro_Depa(tabla,namcb,idcb,valorBuscar){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var atributos_dropdown = {
            class_input:'form-control custom-select outlinenone'
        };
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
            for (var key in elements) {
                arr_dat[key] = {
                    codbx:elements[key].codbx,
                    valor: elements[key].valor
                };
            }
            elements = [];
            var selectes = '';
            selectes = _form_dropdown(namcb,arr_dat,valorBuscar,atributos_dropdown);
            $("#"+idcb+"").empty().html(selectes);
            V_Selec(valorBuscar,'cbdepartamento',4,'Departamento');
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_CargarFiltro_TpFact(tabla,namcb,idcb,valorBuscar){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var atributos_dropdown = {
            class_input:'form-control custom-select outlinenone'
        };
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
            for (var key in elements) {
                arr_dat[key] = {
                    codbx:elements[key].codbx,
                    valor: elements[key].valor
                };
            }
            elements = [];
            var selectes = '';
            selectes = _form_dropdown(namcb,arr_dat,valorBuscar,atributos_dropdown);
            $("#"+idcb+"").empty().html(selectes);
            V_Selec(valorBuscar,'cbtfacturacion',14,'Tipo de facturaci&oacute;n');
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_IniciarCPSesionCliAct() {
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
                    DB_CantidadEnCola('tbl_cliactutempo');
                    DB_CargaCredenciales();
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
function DB_CargaCredenciales(arrg_items){
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
            arrg_Credls['DBA_us_cod'] = elements[0].us_cod_DBA;
            arrg_Credls['us_cod_N'] = elements[0].us_cod_N;
            arrg_Credls['nombre_us'] = elements[0].nombre_us;
            arrg_Credls['idsupervisor'] = elements[0].idsupervisor;
            arrg_Credls['pais'] = elements[0].pais;
            arrg_Credls['ltdistr'] = elements[0].ltdistr;
            arrg_Credls['RegexTelefono'] = elements[0].RegexTelefono;
            arrg_Credls['CantidTelefono'] = elements[0].CantidTelefono;
            arrg_Credls['FormatoTelefono'] = elements[0].FormatoTelefono;
            $('#txttelefono').mask(elements[0].FormatoTelefono, {placeholder: elements[0].FormatoTelefono});
            arrg_Credls['RegexNumIP'] = elements[0].RegexNumIP;
            arrg_Credls['CantidNumIP'] = elements[0].CantidNumIP;
            arrg_Credls['FormatoNumIP'] = elements[0].FormatoNumIP;
            $('#txtdui').mask(elements[0].FormatoNumIP, {placeholder: elements[0].FormatoNumIP});
            arrg_Credls['NombreDocumentoDUI'] = elements[0].NombreDocumentoDUI;
            arrg_Credls['RegexNumNIT'] = elements[0].RegexNumNIT;
            arrg_Credls['CantidNumNIT'] = elements[0].CantidNumNIT;
            arrg_Credls['FormatoNumNIT'] = elements[0].FormatoNumNIT;
            arrg_Credls['TipoUsuario'] = elements[0].TipoUsuario;
            arrg_Credls['ruta_desarrollador'] = elements[0].ruta_desarrollador;
            $('#txtnit').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            arrg_Credls['NombreDocumentoNIT'] = elements[0].NombreDocumentoNIT;
            $("#docidentidad").html('<span class="fa fa-id-card fa-lg"></span> '+elements[0].NombreDocumentoDUI+':');
            $("#idtributaria").html('<span class="fa fa-id-card-alt fa-lg"></span> '+elements[0].NombreDocumentoNIT+':');
            if(elements[0].pais == 'EL SALVADOR'){
                arrg_Credls['CantidadGuionDUI'] = 1;
                arrg_Credls['CantidadGuionNIT'] = 3;
                $('#txtnit').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            }else if(elements[0].pais == 'GUATEMALA'){
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
                var tiponit = document.getElementById("txtnit");
                tiponit.setAttribute("type","text");
            }else if(elements[0].pais == 'HONDURAS'){
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
                $('#txtnit').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            }else if(elements[0].pais == 'REPUBLICA DOMINICANA'){
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
                $('#txtnit').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            }else{
                arrg_Credls['CantidadGuionDUI'] = 1;
                arrg_Credls['CantidadGuionNIT'] = 3;
                $('#txtnit').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            }
            if(arrg_Credls['TipoUsuario'] == 15){
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
            // console.log(arrg_Credls['ruta_desarrollador']);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_CargaFiltrosAvanced(arrg_items){
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
            arrg_Credls['FiltroEstadoAC'] = elements[2].FiltroEstadoAC;
            arrg_Credls['FiltroDiaVisitaAC'] = elements[0].ValueAC;
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
/*===>>>>> ACTUALIZAR EL ESTADO DEL CLIENTE CENSADO*/
function DB_EstadoExhibidor_Change(IdClienteSelect){
    var actived = dataBaseAppSDV.result;
    var objectStore = actived.transaction(["tbl_clientes"], "readwrite").objectStore("tbl_clientes");
    var request = objectStore.get(IdClienteSelect);
    request.onerror = function(event) {
    };
    request.onsuccess = function(event) {
        var data = request.result;
        data.Cli_ac_cliente = '1';
        var requestUpdate = objectStore.put(data);
        requestUpdate.onerror = function(event) {
        };
        requestUpdate.onsuccess = function(event) {
           
        };
    };
}
/*-------------------------------------------------------------*/
/*---------------OBTENER MOTIVO RECHAZO-----------------*/
/*-------------------------------------------------------------*/
function DB_FiltroMotivoR(){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var datosObtenidos = [];
        var active = dataBaseAppSDV.result;
        var data = active.transaction(['tbl_motivoelim'], "readonly");
        var object = data.objectStore('tbl_motivoelim');
        var elements = [];
        object.openCursor().onsuccess = function (e) {
            var result = e.target.result;
            if (result === null) {
                return;
            }
            datosObtenidos.push(result.value);
            result.continue();
        };
        data.oncomplete = function () {
            var filtro_htmlMotivo = ``;
            filtro_htmlMotivo += `<select class="custom-select" id="motivo_inactivo" name="motivo_inactivo">
            <option value="">ELIGE UNA OPCIÓN</option>`;
            datosObtenidos.forEach(function(filall,index, arrgfilall){
                filtro_htmlMotivo+=`<option value="${filall.Valor}">${filall.Valor}</option>`;
            });
            filtro_htmlMotivo+=`</select>
            <div class="valid-feedback">
                <strong></strong>
            </div>
            <div class="invalid-feedback">
                <strong> POR FAVOR SELECCIONA UN MOTIVO! </strong>
            </div>`;
            $("#S_filtroMotivosR").empty().html(filtro_htmlMotivo);
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}