var DataJSON_Cli = [];
var DataJsonClientes = [];
var rec_ingresados = '';
var arrg_Credls = [];
var opcion_catalogo = '';
function DB_CargarFiltrosReclamos(opcion) {
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            Promise.all([
                DB_CargarCatalogoP()                            
            ])
            .then(respuestas =>{
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        opcion_catalogo = opcion;
                        $("#modalCatalogo").modal("toggle");
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
// ----------------------------------------------------------------------------------------

function DB_CargarCatalogoP(){
    var data_catalogo_ = [];
    DataTBClientes = "";
    var p_dias = '';
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        let data = active.transaction(['tbl_productos'], "readonly");
        var object = data.objectStore('tbl_productos');
        var indiced = object.index('by_estado_p');
        var cursord = indiced.openCursor("1");

        
        var elements = [];
        cursord.onsuccess = function (e) {
            var result = e.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        };
        data.oncomplete = function () {
            // elements.forEach(function(valor, index, array) {
            //     if (valor.Fa_nombre != 'EXHIBIDOR') {
            //         data_catalogo_.push(valor);
            //     }
            // });
            DataJSON_Cli = elements.filter(datos => datos.Subf_nombre !="COMPETENCIA");
            DataJSON_Cli = DataJSON_Cli.filter(datos => datos.Subf_nombre !="CLIENTE");
            DataJSON_Cli = DataJSON_Cli.filter(datos => datos.Subf_nombre !="IMAGEN ANTIGUA");
            // DataJSON_Cli = elements;
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}

function DB_CargarListaClientes(opcion){
    $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
        return new Promise(function(resolve, reject){
            var active = dataBaseAppSDV.result;
            let data = active.transaction(['tbl_clientes'], "readonly");
            var object = data.objectStore('tbl_clientes');
            var indiced = object.index('by_estado_w');
            var cursord = indiced.openCursor("1");

            
            var elements = [];
            cursord.onsuccess = function (e) {
                var result = e.target.result;
                if (result === null) {
                    return;
                }
                elements.push(result.value);
                result.continue();
            };
            data.oncomplete = function () {
                DataJsonClientes = elements;
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        opcion_catalogo = opcion;
                        $("#modalClientes").modal("toggle");
                    });
                resolve(1);
            };
            data.onerror = function () {
                reject(0);
            };
        });
    });
}
// ----- CARGAR LISTA DE TIPO DE DAÑOS ----------------------------------------------------
function DB_CargarSelectTipoDanos(tabla,namcb,idcb,Trec_Id){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var atributos_dropdown = {
            class_input:'form-control custom-select'
        };

        var active = dataBaseAppSDV.result;
        var data = active.transaction([tabla], "readonly"),
        object = data.objectStore(tabla),
        indice = object.index('by_Trec_Id'),
        cursor = indice.openCursor(Trec_Id)
        var elements = [];
        cursor.onsuccess = function (e) {
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
                    codbx:elements[key].Tipd_Id,
                    valor: elements[key].Tipd_descripcion
                };                
            }
            elements = [];
            var selectes = '';
            selectes = _form_dropdown(namcb,arr_dat,'',atributos_dropdown);
            $("#"+idcb+"").html(selectes);
            //console.log(arr_dat);

            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
// ----------------------------------------------------------------------------------------

// ----- CARGAR SELECTOR ------------------------------------------------------------------
function DB_CargarSelect(tabla,namcb,idcb,valorBuscar){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var atributos_dropdown = {
            class_input:'form-control'
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
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
// ----------------------------------------------------------------------------------------

// ----- CARGAR LISTA DE FAMILIAS ---------------------------------------------------------
function DB_filtro_familias_p(tabla){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var atributos_dropdown = {
            class_input:'form-control custom-select'
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
            // console.log(elements);
            for (var key in elements[3]['FiltroFamiliasP']) {
                arr_dat.push({
                    codbx: elements[3]['FiltroFamiliasP'][key].Fa_nombre,
                    valor: elements[3]['FiltroFamiliasP'][key].Fa_nombre
                });
            }
        $('#filtro_fam').html(_form_dropdown('familas-p',arr_dat,'',atributos_dropdown));
        $('#familas-p').append('<option value="">TODAS</option>');
        //console.log(arr_dat);
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
// ----------------------------------------------------------------------------------------

function DB_Guardar_reclamo_temporal(tabla,condicion,arrgdata){
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
                DB_CantidadEnCola('tbl_reclamosTemp');
                Swal.fire({
                    type: 'info',
                    title: 'Registro guardado temporalmente!',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    //---------------- AGREGAR OTRO PRODUCTO AL RECLAMO --------------------
                    Swal.fire({
                        title: '¿Desea agregar otro producto al reclamo?',
                        text: "",
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No, finalizar reclamo',
                        allowOutsideClick: false
                    }).then((result) => {
                        if(result.value){
                            agregar_producto_reclamo();
                        }else{
                            limpiar_formulario_reclamo();
                        }
                    });
                    //-------------- FIN AGREGAR OTRO PRODUCTO AL RECLAMO ------------------
                });
                $('#btn-enviar').hide();
            }else{
                
            }
            console.log('Registro guardado permanente en DB!');
            resolve(1);
        };
    });
}

function DB_FiltroRutasMayoreo(){
    var arr_dat = [];
    return new Promise(function(resolve, reject){
        var datosObtenidos = [];
        var active = dataBaseAppImpulso.result;
        var data = active.transaction(['tbl_rutas_mayoreo'], "readonly");
        var object = data.objectStore('tbl_rutas_mayoreo');
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
            var filtro_htmlRutas = ``;
            filtro_htmlRutas += `<select class="custom-select" id="txtrutaapoyo" name="txtrutaapoyo">
            <option value="">SELECCIONA UNA RUTA</option>`;
            datosObtenidos.forEach(function(filall,index, arrgfilall){
                filtro_htmlRutas+=`<option value="${filall.Id_Ruta}">${filall.Nombre_Ruta}</option>`;
            });
            filtro_htmlRutas+=`</select>`;
            $("#S_filtroRutas").empty().html(filtro_htmlRutas);
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}

/// -- OBTENER REGISTROS DE RECLAMOS CON EL MISMO CODIGO ---------------------------------------------------
function DB_get_registro_reclamo(tabla,condicion,arrgdata){
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
                DB_CantidadEnCola('tbl_reclamosTemp');
                Swal.fire({
                    type: 'info',
                    title: 'Registro guardado temporalmente!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#btn-enviar').hide();
            }else{
                
            }
            console.log('Registro guardado permanente en DB!');
            resolve(1);
        };
    });
}

function DB_iniciar_reclamos(opcion){
    Promise.all([
        DB_IniciarCPSesion(1)
    ])
    .then(respuestas => {
        DB_filtro_familias_p('tbl_filtros');
        if(opcion == 1){
            consultar_cola_reclamos();
            $('#select_tipo_reclamo').select2();
            DB_CargarSelect('tbl_proveedores','select_proveedores','slc_proveedor','');
            mostrar_lista();
        }else{
            consultar_cola_cti();
        }
    })
    .catch(error => {
        // console.log(error);
    });
}

