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
                DB_CantidadEnCola('tbl_act_cliente');
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
            console.log(elements[0].NombreDocumentoDUI);
            $(".docidentidad").html('<span class="fa fa-id-card fa-lg"></span> '+elements[0].NombreDocumentoDUI+':');
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