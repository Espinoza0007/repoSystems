var table = null;
$( document ).ready(function() {

    $(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";
        $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            });
        });
    });

    $('#modalCatalogo').on('shown.bs.modal', function (e) {
        var hideCol = opcion_catalogo == 'venta' ? true : false;
        table =  $('#catalogoDtable').DataTable({
            "stateSave": true,
            "stateDuration": 60 * 60 * 24,
            "data" : DataJSON_Cli,        
            "columnDefs":[
                {
                    "targets":[0],
                    "data": "Cat_Id",
                    "render": function(data, type, row){
                        
                        if(row.Catx_estado == '1'){
                            span_estadow = '<span class="badge badge-success" style="font-size:15px;">'+data+' </span>';
                        }else{
                            span_estadow = '<span class="badge badge-danger" style="font-size:15px;">'+data+' </span>';
                        }
                        return span_estadow;
                    }
                },
                {
                    "targets":[1],
                    "data": "Cat_descripcion",
                    "visible" : true
                },
                {
                    "targets":[2],
                    "data": "Cat_img",
                    "render": function(data, type, row){
                        
                        if(row.Cat_img != '' && row.Cat_img != null){
                            //img_html = '<img src="http://localhost:84/img_server/'+data+'" class="img_datatable" style="border: 1px solid black;max-height:65px;width:auto;">'
                            img_html = '<img src="https://bocadeli.info/'+data+'" class="img_datatable" style="border: 1px solid black;max-height:65px;width:auto;">'
                        }else{
                            img_html = '<img src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;max-height:65px;width:auto;">'
                        }
                        return img_html;
                    }
                },
                {
                    "targets":[3],
                    "data": "Um_nombre",
                    "visible" : true
                },
                {
                    "targets":[4],
                    "data": "Fa_nombre",
                    "visible" : true
                },
                {
                    "targets":[5],
                    "data": "Subf_nombre",
                    "visible" : true
                },
                {
                    "targets":[6],
                    "data": "Catx_estado",
                    "render": function(data, type, row){
                        
                        if(row.Catx_estado == '1'){
                            span_estadow = '<span class="badge badge-success" style="font-size:15px;">ACTIVO</span>';
                        }else{
                            span_estadow = '<span class="badge badge-danger" style="font-size:15px;">INACTIVO</span>';
                        }
                        return span_estadow;
                    }
                }/*,
                {
                    "targets":[7],
                    "data": "Catx_precio",
                    "visible" : hideCol,
                    "searchable": hideCol
                }*/
            ],   
            initComplete: function () {

                this.api().columns([4]).every(function(i){
                    var column = this,
                    select = $('#familas-p')
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
    });
    
    $('#modalClientes').on('shown.bs.modal', function (e) {
        table =  $('#clientesDtable').DataTable({
            "stateSave": true,
            "stateDuration": 60 * 60 * 24,
            "data" : DataJsonClientes,
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
                        if(row.Cli_categoria == 'S'){
                            span_categoria = '<br><p class="text-secondary" style="font-size:14px;font-weight:500;">SIN CATEGORIA</p>';
                        }else{
                            span_categoria = '<br><p class="text-dark" style="font-size:29px;font-weight:500;">'+row.Cli_categoria+'</p>';
                        }
                        return span_estadow+span_categoria;
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

                this.api().columns([6]).every(function(i){
                    var column = this,
                    select = $('#dias_busqueda')
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
    });    

    $('#modalCatalogo').on('hidden.bs.modal', function (e) {
        $('#familas-p').val('').trigger('change');
        $('#catalogoDtable').DataTable().destroy();
        table = null;
        $("#showDataSN").empty();
    });
    
    $('#modalClientes').on('hidden.bs.modal', function (e) {
        $('#clientesDtable').DataTable().destroy();
        table = null;
        $("#showDataCli").empty();
    });   

});
//-------- FINAL DOCUMENT.READY --------------------------------------------------------------------------

function getInfoCli(){
    $.when( $('#InfoCuadro').stop(true,true).hide() ).done(function( x ) {
        $.when( $('#form_actuinfo').stop(true,true).show() ).done(function( x ) {
            $("#modalClientes").modal("toggle");
            $("#showDataCli").empty();
            $('#clientesDtable').DataTable().destroy();
            $('#btn-enviar').show();
            blockF=0;
        });
    });
}

function init() {
    map = new L.Map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(map);
    map.attributionControl.setPrefix('SDV Bocadeli');
    map.setView(new L.LatLng(13.685147, -89.147116), 18);
    var circle = L.circle([13.685147, -89.147116], {
        color: '#3ACA31',
        fillColor: '#51DF48',
        fillOpacity: 0.5,
        radius: 50
    });
}

function _ajax_error_modulos_menu(jqXHR, textStatus, errorThrown,opcion,array) {
    if (textStatus === 'timeout') {
        if (opcion == 'controlInventario'){
            guardar_cti_local('SI', 'NO');
        }else if(opcion == 'reclamo'){
            guardar_reclamo_local('SI', array);
        }else if(opcion == 'venta'){
            guardar_pedido_local('SI', array)
        }
        return;
    } else if (jqXHR === 0) {

        if (opcion == 'controlInventario'){
            guardar_cti_local('SI', 'NO');
        }else if(opcion == 'reclamo'){
            guardar_reclamo_local('SI', array)
        }else if(opcion == 'venta'){
            guardar_pedido_local('SI', array)
        }
        
        return;
    } else if (jqXHR === 200) {
        if (opcion == 'controlInventario'){
            guardar_cti_local('SI', 'NO');
        }else if(opcion == 'reclamo'){
            guardar_reclamo_local('SI', array)
        }else if(opcion == 'venta'){
            guardar_pedido_local('SI', array)
        }        
        return;
    } else if (jqXHR == 404) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>Página solicitada no encontrada[404]</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    } else if (jqXHR == 500) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>Error de servidor interno [500].</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    } else if (textStatus === 'parsererror') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    } else if (textStatus === 'abort') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    } else {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html: '<h3>Error desconocido, por favor contactar con Sistemas de Venta...</h3>',
            confirmButtonText: 'Ok'
        });
        return;
    }
}

function descargar_registros_cola(tabla){
    arr_dat = [];
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
        var outerHTML = '';
        //alert(elements.length);
        for (var key in elements) {
            arr_dat[key] = {
                txtsus:elements[key].txtsus,
                rutaimg:elements[key].rutaimg,
                txtnomcliente: elements[key].txtnomcliente,
                txtdireccion: elements[key].txtdireccion,
                cbmunicipio: elements[key].cbmunicipio,
                txtnumtelefono: elements[key].txtnumtelefono,
                txtnomcontacto: elements[key].txtnomcontacto,
                txtpropietario: elements[key].txtpropietario,
                cbtfacturacion: elements[key].cbtfacturacion,
                txtdui: elements[key].txtdui,
                txtnumcontribuyente: elements[key].txtnumcontribuyente,
                txtnit: elements[key].txtnit,
                cbcondicioncli: elements[key].cbcondicioncli,
                cbdiascobro: elements[key].cbdiascobro,
                txtmontocredito: elements[key].txtmontocredito,
                txttipocompra: 1,
                txttipocontribu: 1,
                cbcantidadex: elements[key].cbcantidadex,
                cbexhibidoru: elements[key].cbexhibidoru,
                cbexhibidord: elements[key].cbexhibidord,
                cbexhibidord: elements[key].cbexhibidord,
                txtordenvisita: elements[key].txtordenvisita,
                checkdiavisita: elements[key].checkdiavisita,
                cbfrecuenciavisita: elements[key].cbfrecuenciavisita,
                txtlatitud: elements[key].txtlatitud,
                txtlongitud: elements[key].txtlongitud,
                cbgironegocio: elements[key].cbgironegocio,
                imagenuno: elements[key].imagenuno,
                imagendos: elements[key].imagendos,
                txtmcomprab: elements[key].txtmcomprab,
                txtmcomprad: elements[key].txtmcomprad,
                txtmcompray: elements[key].txtmcompray,
                txtmcompraf: elements[key].txtmcompraf
            };
        }
    var htmltable = ``;
    htmltable += `
    <table id="tabla-registros-con-cola">
    <thead>
    <th>ID_CLIENTE</th>
    <th>CODIGO</th>
    <th>NOMBRE</th>
    <th>DIRECCION</th>
    <th>ID_MUNICIPIO</th>
    <th>TELEFONO</th>
    <th>CONTACTO</th>
    <th>PROPIETARIO</th>
    <th>ID_TIPOFACTURACION</th>
    <th>DUI</th>
    <th>NUMERO_REGISTRO</th>
    <th>NIT</th>
    <th>CONDICION_CLIENTE</th>
    <th>DIA_COBRO</th>
    <th>MONTO_CREDITO</th>
    <th>TIPO_COMPRA</th>
    <th>TIPO_CONTRIBUYENTE</th>
    <th>CANTIDAD_EXHIBIDOR</th>
    <th>EXHIBIDOR_UNO</th>
    <th>EXHIBIDOR_DOS</th>
    <th>EXHIBIDOR_TRES</th>
    <th>ORDEN_VISITA</th>
    <th>DIAS</th>
    <th>REFUNO</th>
    <th>LATITUD</th>
    <th>LONGITUD</th>
    <th>ID_USUARIOS</th>
    <th>ID_REF</th>
    <th>ID_GIRONEGOCIO</th>
    <th>FOTO_NEGOCIO</th>
    <th>FOTO_EXHIBIDOR</th>
    <th>COMPRAS_B</th>
    <th>COMPRAS_D</th>
    <th>COMPRAS_Y</th>
    <th>COMPRAS_F</th></thead>`;
        for(var i=0;i<parseInt(arr_dat.length);i++){

            htmltable +=`
            <tr>
                <td>${i}</td>
                <td>0000000</td>
                <td>${arr_dat[i].txtnomcliente}</td>
                <td>${arr_dat[i].txtdireccion}</td>
                <td>${arr_dat[i].cbmunicipio}</td>
                <td>${arr_dat[i].txtnumtelefono}</td>
                <td>${arr_dat[i].txtnomcontacto}</td>
                <td>${arr_dat[i].txtpropietario}</td>
                <td>${arr_dat[i].cbtfacturacion}</td>
                <td>${arr_dat[i].txtdui}</td>
                <td>${arr_dat[i].txtnumcontribuyente}</td>
                <td>${arr_dat[i].txtnit}</td>
                <td>${arr_dat[i].cbcondicioncli}</td>
                <td>${arr_dat[i].cbdiascobro}</td>
                <td>${arr_dat[i].txtmontocredito}</td>
                <td>${arr_dat[i].txttipocompra}</td>
                <td>${arr_dat[i].txttipocontribu}</td>
                <td>${arr_dat[i].cbcantidadex}</td>
                <td>${arr_dat[i].cbexhibidoru}</td>
                <td>${arr_dat[i].cbexhibidord}</td>
                <td>${arr_dat[i].cbexhibidord}</td>
                <td>${arr_dat[i].txtordenvisita}</td>
                <td>${arr_dat[i].checkdiavisita}</td>
                <td>${arr_dat[i].cbfrecuenciavisita}</td>
                <td>${arr_dat[i].txtlatitud}</td>
                <td>${arr_dat[i].txtlongitud}</td>
                <td></td>
                <td>1</td>
                <td>${arr_dat[i].cbgironegocio}</td>
                <td>NULL</td>
                <td>NULL</td>
                <td>${arr_dat[i].txtmcomprab}</td>
                <td>${arr_dat[i].txtmcomprad}</td>
                <td>${arr_dat[i].txtmcompray}</td>
                <td>${arr_dat[i].txtmcompraf}</td>  
            </td>`;
        }
    htmltable +=`</table>`;
    $("#registros-cola").html(htmltable);
    };
}
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'clientes_recuperados.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
        DB_LimpiarCola();
    }
}

function get_registros_reclamos() {
    Promise.all([   
        get_data_info()
    ])
    .then(respuestas => {
        $.ajax({
            url: 'C_reclamos/Ctr_ingreso_reclamos/export_tabla_indexed',
            type: "POST",
            data: {data_info: data_info},
            dataType: "JSON",
            timeout: 14777
        }).done(function(_resp) {
        }).always(function(_resp, textStatus, errorThrown) {
            if (textStatus == "success") {
                if (_resp.rs == true) {
                    var url_archivo = '../'+_resp.archivo;
                    var $a = $("<a>");
                    var nombre_archivo = url_archivo.replace("../../Uploads/Plantilla_Excel/", "");
                    $a.attr("href", url_archivo);
                    $("body").append($a);
                    $a.attr("download", nombre_archivo);
                    $a[0].click();
                    $a.remove();
                }else{
                }
            }else{
                $.when($("#content_carga").stop(true, true).show()).done(function(x) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html:'<strong>Conectese a una red...</strong>',
                            confirmButtonText: 'Ok'
                        });
                    });
                });
            }
        });
    })
    .catch(error => {
        console.log('error');
    });

       
    
}
/*var data_info = [];
function get_data_info(){
    return new Promise(function(resolve, reject) {
        var arrg_items = [
            "tbl_reclamosingre",
            "tbl_control_inventario"
        ];
        var active = dataBaseAppSDV.result;

        arrg_items.forEach(function(valor,index, array){
            var data = active.transaction([valor], "readonly");
            var object = data.objectStore([valor]);
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
                data_info.push({[valor]:elements});
                if (data_info.length == 2) {
                    resolve(1);
                }
            };
        });
    });
}*/

var data_info = {};
var data_header = [];
function get_data_info(){
    return new Promise(function(resolve, reject) {
        var arrg_items = [
            "tbl_reclamosingre",
            "tbl_control_inventario"
        ];
        var active = dataBaseAppSDV.result;
        var htmltable = ``;
        arrg_items.forEach(function(valor, index, array){
            var data = active.transaction([valor], "readonly");
            var object = data.objectStore([valor]);
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
                
                htmltable += ``;
                data_info[valor] = Object.values(elements);
                // data_header[valor] = Object.keys(elements[0]);
            
                if (Object.entries(data_info).length == 2) {
                    console.log(Object.entries(data_info).length);
                    resolve(1);
                }
            };
        });
    });
}