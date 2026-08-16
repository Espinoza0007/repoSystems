var DataTB = "";
var arrg_Credls = [];
///DB_CargarFiltrosTodos
function DB_CargarFiltrosTodos(arrg_datos,arrg_items) {
    Promise.all([
        DB_CargarFiltro('tbl_departamento','cbdepartamento','c-departamento'),
        DB_CargarFiltro('tbl_tpuntoventa','cbtpuntoventa','c-tpuntoventa'),
        DB_CargarFiltro('tbl_tfacturacion','cbtfacturacion','c-tfacturacion'),
        DB_CargarFiltro('tbl_condicioncli','cbcondicioncli','c-condicioncli'),
        DB_CargarFiltro('tbl_referencia','cbreferencia','c-referencia'),
        DB_CargarFiltroExhibidor()
    ])
    .then(respuestas =>{
        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
            $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
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
}
function DB_CargarFiltro(tabla,namcb,idcb){
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
            for (var key in elements) {
                arr_dat[key] = {
                    codbx:elements[key].codbx,
                    valor: elements[key].valor
                };
            }
            elements = [];
            var selectes = '';
            selectes = _form_dropdown(namcb,arr_dat,'',atributos_dropdown);
            $("#"+idcb+"").html(selectes);
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_CargarFiltroExhibidor(){
    var arr_dat = [];
    DataTB = "";
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction(['tbl_exhibidores'], "readonly");
        var object = data.objectStore('tbl_exhibidores');
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
                    SKU:elements[key].SKU,
                    valor: elements[key].valor
                };
            }
            elements = [];
            for(var i=0;i<parseInt(arr_dat.length);i++){
                DataTB+"<div id='DgrTable'>";
                DataTB+="<div class='seg TrSelect item' id='ROW_"+(i+1)+"'>";
                    DataTB+="<span style='display:none;' class='Cme'>"+arr_dat[i].codbx+"</span>";
                    DataTB+="<div class='seg_i Csku'> SKU : "+arr_dat[i].SKU+"</div>";
                    DataTB+="<div class='seg_d Nme'>"+arr_dat[i].valor+"</div>";
                DataTB+="</div>";
                DataTB+="</div>";
            }
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}
function DB_LimpiarCola() {
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_clitemporales', "readwrite");
    data.oncomplete = function(event) {
        console.log('Limpiando sesion tempo');
    };
    data.onerror = function(event) {
        console.log('Ocurrio un error: ' + transaction.error + '');
    };
    var objectStore = data.objectStore('tbl_clitemporales');
    var objectStoreRequest = objectStore.clear();
    objectStoreRequest.onsuccess = function(event) {
        $("#RegisCola").html(0);
    };
}
/*----------------------------------------------------------------------------*/
/*----------------SALVANDO REGISTROS CLIENTES TEMPORALES----------------------*/
/*----------------------------------------------------------------------------*/
function DB_GuardarTemporal(arrgdata){
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_clitemporales', "readwrite");
        var object = data.objectStore('tbl_clitemporales');
        var request = object.put(arrgdata);
        request.onerror = function (e) {
            console.log('Llave repetida.');
            reject(0);
        };
        data.oncomplete = function (e) {
            DB_CantidadEnCola('tbl_clitemporales');
            Swal.fire({
                type: 'info',
                title: 'Registro guardado temporalmente!',
                showConfirmButton: false,
                timer: 1500
            });
            resolve(1);
        };
    });
}
function DB_GuardarPermanenteIDX(arrgdata){
    return new Promise(function(resolve, reject){
        var active = dataBaseAppSDV.result;
        var data = active.transaction('tbl_clingresados', "readwrite");
        var object = data.objectStore('tbl_clingresados');
        var request = object.put(arrgdata);
        request.onerror = function (e) {
            reject(0);
        };
        data.oncomplete = function (e) {
            resolve(1);
        };
    });
}