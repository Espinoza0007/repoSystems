var tipo_licencia = 0;
$( document ).ready(function() {

    iniciar_modulo_vehiculo();

	$(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";    
    });

    $(document).on( 'click', '#btn-crear-vehiculo', function (){
        $('.vehi').prop('disabled', false);
        $('.vehi').val('');
        $('#slc-historial-vehiculo').hide();
        $('#btn-guardar-vehiculo').show();
    });

    $(document).on( 'click', '#btn-guardar-vehiculo', function (){
        crear_vehiculo();
    });

    $(document).on( 'click', '#btn-cambiar-vehiculo', function (){
        $('#slc-historial-vehiculo').show();
    });
    $(document).on( 'click', '#btn-actualizar-vendedor', function (){
        $('.vnd').prop('disabled', false);
        $('#btn-guardar-datos').show();        
    });

    $(document).on( 'click', '#btn-guardar-datos', function (){
        actualizar_info_vendedor();        
    });

    $(document).on( 'change', '#slc-vehiculo-id', function (){
        let parametros = {
            cursor  : $(this).val(),
            indice  : 'by_placas',
            tabla   : 'tbl_vehiculo'
        }
        let id = $(this).val();
        if(id != null){
            get_info_local(parametros).done(function (datos) {
                llenar_campos_vehiculo(datos);
            });        

        }
    });

    $(document).on( 'click', '#btn-enviar-info', function (){
        guardar_checklist();        
    });
});

function iniciar_modulo_vehiculo(){
    Promise.all([
        DB_IniciarCPSesion(),     
    ])
    .then(respuestas => {
        get_item_checklist();
        
        
        let param_vehiculo = {
            cursor  : '1',
            indice  : 'by_estado',
            tabla   : 'tbl_vehiculo'
        };

        let param_vendedor = {
            cursor  : 'DATOS_US',
            indice  : 'by_tipo',
            tabla   : 'tbl_parametros_vnt'
        };
        get_info_local(param_vehiculo).done(function (datos) {//AGREGAR VALIDACION CUANDO NO HAY VEHICULOS ASIGNADOS A LA RUTA
            llenar_campos_vehiculo(datos);
        });
        get_info_local(param_vendedor).done(function (datos) {
            llenar_campos_vendedor(datos);
        });
        // get_vehiculos_ruta();
        get_info_all_local('tbl_vehiculo').done(function(datos){
            llenar_select(datos, 'Vehi_placas', 'Vehi_placas', 'slc-historial-vehiculo', 'slc-vehiculo-id', '')
        });

        get_info_all_local('tbl_tipo_licencia').done(function(datos){
            llenar_select(datos, 'TLic_nombre', 'TLic_Id', 'slc-div-licencia', 'slc-licencias', tipo_licencia);
            console.log(tipo_licencia);
        });
    })
    .catch(error => {
        console.log(error);
    });
}

function get_item_checklist() {
    let html_header = ``;
    let html_body = ``;
    let html = ``;
    let html_item = ``;
    let param_secciones = {
        indice : "by_tipo",
        cursor : "seccion",
        tabla  : "tbl_items_check_list_vehiculo"
    }
    get_info_local(param_secciones).done(function (datos) {
        console.log(datos);
        datos.forEach(function (val, index) {
            html_header = 
            `<div class="panel panel-default">
                <div class="panel-heading" id="header_${val.idx}">                        
                    <div class="panel-title">
                        <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_${val.idx}" aria-expanded="false" aria-controls="collapse_${val.idx}">${val.Irv_seccion_descripcion}</a>
                    </div>
                </div>
            </div>`;
            html_body =
            `<div id="collapse_${val.idx}" class="collapse" aria-labelledby="header_${val.idx}" data-parent="#accordion">
                <div class="panel-body" id = "panel_${val.idx}">
                </div>
            </div>
            `;
            let param_item = {
                indice : "by_seccion",
                cursor : val.Irv_seccion_descripcion,
                tabla  : "tbl_items_check_list_vehiculo"
            }
            html = html_header + html_body;
            $('#frm-checklist').append(html);

            get_info_local(param_item).done(function (datos_) {
                console.log();
                datos_.forEach(function (val, index) {
                    if(val.Irv_nombre_item != undefined){
                       /* html_item += 
                        `<div class="custom-control custom-switch">
                           <input type="checkbox" class="custom-control-input switch_estilo" id="item_${val.Irv_Id}>
                           <label class="custom-control-label" for="item_${val.Irv_Id}">${val.Irv_nombre_item}</label>
                        </div>                            
                        `; */

                        html_item += 
                        `<div class="row" style="display: flex;align-items: center;">
                            <div class="col-9">
                                <label for="item_${val.Irv_Id}">${val.Irv_nombre_item}</label>
                            </div>                                
                            <div class="col-3">
                                <label class="switch">                                
                                    <input type="checkbox" id="item_list" name="item_list[]" value = ${val.Irv_Id}>
                                    <span class="slider round"></span>     
                                </label>                        
                            </div>
                        </div><hr>`; 
                    }
                });                   
                $('#panel_'+val.idx).append(html_item);
                html_item = ``;
            });

        });
    });
    
}

function llenar_campos_vehiculo(datos) {
    console.log(datos);
    $('.vehi').prop('disabled', true);
    $('#txt-id-vehiculo').val(datos[0].Vehi_Id);
    $('#txt-equipo-vehiculo').val(datos[0].Vehi_Equipo);
    $('#txt-placas-vehiculo').val(datos[0].Vehi_placas);
    $('#txt-marca-vehiculo').val(datos[0].Vehi_marca);
    $('#txt-tipo-vehiculo').val(datos[0].Vehi_tipo);
    $('#txt-anio-vehiculo').val(datos[0].Vehi_anio);
    $('#txt-motor-vehiculo').val(datos[0].Vehi_numero_motor);
    $('#txt-chasis-vehiculo').val(datos[0].Vehi_numero_chasis);
    $('#txt-combustible-vehiculo').val(datos[0].Vehi_tipo_combustible);
    $('#txt-km-vehiculo').val('');
}

function llenar_campos_vendedor(datos) {
    // console.log(datos);
    $('#lbl-nombre-vendedor').html(`<b>${datos[0].Emp_nombre}</b>`);
    $('#lbl-carnet-vendedor').html(`<b>${datos[0].Emp_carnet}</b>`);
    $('#lbl-nombre-ruta').html(`<b>${datos[0].Usu_Ru_Id}</b>`);
    $('#txt-numero-licencia').val(datos[0].Emp_Numero_licencia);
    $('#txt-vencimiento-licencia').val(datos[0].Emp_fecha_vencimiento_licencia);
    $('#txt-id-ruta').val(datos[0].Usu_Ru_Id);
    $('#txt-id_empleado').val(datos[0].Emp_Id);
    tipo_licencia = datos[0].TLic_nombre;
}

function get_info_local(param) {
    // console.log(param);
    return $.Deferred(function(dfd) 
    {
        let elements            = [];
        let active              = dataBaseAppSDV.result;
        let transaccion         = active.transaction(param.tabla, 'readonly'),
        store   = transaccion.objectStore(param.tabla),
        indice  = store.index([param.indice]),
        cursor  = indice.openCursor(param.cursor)
        cursor.onsuccess = function(event) {            
            let result = event.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        }
        transaccion.oncomplete = function() {
            dfd.resolve(elements);
        };
        transaccion.onerror = function() {
            dfd.reject(0);
        };
    }).promise();
}

function get_info_all_local(tabla) {   
    return $.Deferred(function(dfd) 
    {
        let active      = dataBaseAppSDV.result;
        let data        = active.transaction(tabla, "readonly");
        let object      = data.objectStore(tabla);
        let elements    = [];
        object.openCursor().onsuccess = function (e) 
        {            
            let result = event.target.result;
            if (result === null) {
                return;
            }
            elements.push(result.value);
            result.continue();
        }
        data.oncomplete = function() {
            dfd.resolve(elements);
        };
        data.onerror = function() {
            dfd.reject(0);
        };
    }).promise();
}

function actualizar_vehiculos_ruta(placas, es_sincronizado) {
    return $.Deferred(function(dfd) 
    {
        get_info_all_local('tbl_vehiculo').done(function (datos) {
            let cuenta = Object.entries(datos).length;
            if(cuenta > 0){
                data_actualizar = {
                    Vehi_estado : 0
                }
                datos.forEach(function (index, val) {
                    if(val['Vehi_estado'] != 1 && val['Vehi_placas'] != placas){
                        actualizar_info_local('tbl_vehiculo', val['Vehi_placas'], data_actualizar, es_sincronizado);
                    }
                });                
            }
            dfd.resolve(datos);
        }).fail(function () {
            dfd.reject(0);
        });
    }).promise();
}

function llenar_select(elements, valor, id, nombre_div, nombre_select, seleccionar) {
    if(Object.entries(elements).length > 0){
        let arr_mun = [];
        elements.forEach(function (val, index) {
            arr_mun.push({
                codbx : val[id],
                valor : val[valor]
            });
            
        });
        let atributos_dropdown = {
            class_input:'form-control custom-select outlinenone',
        };
        $('div[id="'+nombre_div+'"]').html(_form_dropdown(nombre_select,arr_mun, seleccionar, atributos_dropdown));
    }
}

function crear_vehiculo() {
    let datos = $("#frm-datos-vehiculo").serializeArray();
    let data_insertar = {
        Vehi_equipo             : $('#txt-equipo-vehiculo').val(),
        Vehi_placas             : $('#txt-placas-vehiculo').val(),
        Vehi_marca              : $('#txt-marca-vehiculo').val(),
        Vehi_tipo               : $('#txt-tipo-vehiculo').val(),
        Vehi_anio               : $('#txt-anio-vehiculo').val(),
        Vehi_motor              : $('#txt-motor-vehiculo').val(),
        Vehi_chasis             : $('#txt-chasis-vehiculo').val(),
        Vehi_Ru_Id              : $('#txt-id-ruta').val(),
        Vehi_estado             : "1",
        Vehi_tipo_combustible   : $('#txt-combustible-vehiculo').val()
    };
    $.ajax({
        url: 'C_vehiculo/Ctr_vehiculo/guardar_vehiculo',
        type: 'POST',
        dataType: 'JSON',
        data: datos,
        timeout: 7777,
        beforeSend: function() {
            $(".carga-class").show();
        }
    }).done(function(_resp) {
        guardar_info_local("tbl_vehiculo", data_insertar, "SINCRONIZADO").done(function (respuesta) {
            $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                Swal.fire({
                    type: 'success',
                    title: 'El vehiculo se registró con exito',
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    $('.vehi').prop('disabled', true);
                    $('#btn-guardar-vehiculo').hide();
                    actualizar_vehiculos_ruta(data_insertar['Vehi_placas'], "SINCRONIZADO");
                });              
            });
        });
    }).fail(function(jqXHR, textStatus, errorThrown) {
        guardar_info_local("tbl_vehiculo", data_insertar, "PENDIENTE").done(function (respuesta) {
            $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                Swal.fire({
                    type: 'success',
                    title: 'El vehiculo se registró temporalmente',
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    $('.vehi').prop('disabled', true);
                    $('#btn-guardar-vehiculo').hide();
                    actualizar_vehiculos_ruta(data_insertar['Vehi_placas'], "PENDIENTE");
                });              
            });
        });
    });
}

function actualizar_info_vendedor() {
    let datos   = $("#frm-datos-vendedor").serializeArray();
    let data_insertar = {
        Emp_Numero_licencia             : $('#txt-numero-licencia').val(),
        Emp_fecha_vencimiento_licencia  : $('#txt-vencimiento-licencia').val(),
        Emp_TLic_Id                     : $('#slc-licencias').val(),
        TLic_Id                         : $('#slc-licencias').val(),      
        TLic_nombre                     : $("#slc-licencias option:selected" ).text(),    
        Emp_Id                          : $('#txt-id_empleado').val() ,      
    };
    $.ajax({
        url: 'C_vehiculo/Ctr_vehiculo/actualizar_datos_vendedor',
        type: 'POST',
        dataType: 'JSON',
        data: datos,
        timeout: 7777,
        beforeSend: function() {
            $(".carga-class").show();
        }
    }).done(function(_resp) {

        actualizar_vendedor_local(data_insertar, 'SINCRONIZADO').done(function (respuesta) {
            $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                Swal.fire({
                    type: 'success',
                    title: 'Datos actualizados con exito',
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    $('.vnd').prop('disabled', true);
                    $('#btn-guardar-datos').hide();
                });              
            });                      

        });
        
        
    }).fail(function(jqXHR, textStatus, errorThrown) {
        actualizar_vendedor_local(data_insertar, 'PENDIENTE').done(function (respuesta) {
            $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                Swal.fire({
                    type: 'success',
                    title: 'Datos actualizados temporalmente',
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    $('.vnd').prop('disabled', true);
                    $('#btn-guardar-datos').hide();
                });              
            }); 
        });        
    });
}

function guardar_checklist() {
    let checks = [];
    let datos = $("#frm-checklist").serializeArray();
    datos.push({name: 'txt-id-vehiculo', value : $('#txt-id-vehiculo').val() });
    datos.push({name: 'txt-observaciones-vehiculo', value : $('#txt-observaciones-vehiculo').val() });
    datos.push({name: 'txt-km-vehiculo', value : $('#txt-km-vehiculo').val() });
    datos.push({name: 'txt-id-ruta', value : $('#txt-id-ruta').val() });
    datos.push({name: 'txt-id-usu', value : arrg_Credls['us_cod_N'] });
    datos.push({name: 'txt-fecha-recepcion', value : fecha_actual_vnt() });
    $("input:checkbox:checked").each(function() {
        checks.push($(this).val());
    });

    let check = check_Campos(datos);
    if(check == 0){
        if(checks.length > 0){
            let data_insertar = {
                Rvehi_Vehi_Id               : $('#txt-id-vehiculo').val(),
                Rvehi_check_list_recepcion  : checks,
                Rvehi_KM_actual             : $('#txt-km-vehiculo').val(),
                Rvehi_observaciones         : $('#txt-observaciones-vehiculo').val(),
                Revehi_Ru_Id                : $('#txt-id-ruta').val(),
                Rvehi_nombre_empleado       : '', 
                Rvehi_carnet                : '', 
                Rvehi_Usu_Id                : arrg_Credls['us_cod_N'], 
                Rvehi_fecha_recepcion       : fecha_actual_vnt(),
                es_sincronizado             : ''

            };
            $.ajax({
                url: 'C_vehiculo/Ctr_vehiculo/guardar_checklist',
                type: 'POST',
                dataType: 'JSON',
                data: datos,
                timeout: 7777,
                beforeSend: function() {
                    $(".carga-class").show();
                }
            }).done(function(_resp) {
                guardar_info_local("tbl_vehiculo_recepcion", data_insertar, "SINCRONIZADO").done(function (respuesta) {
                    $.when($(".carga-class").stop(true, true).hide()).done(function() {
                        Swal.fire({
                            type: 'success',
                            title: 'El registro se guardó con exito',
                            confirmButtonText: 'Ok',
                        }).then((result) => {
                            $('input:checkbox').prop('checked', false);
                        });              
                    });
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                guardar_info_local("tbl_vehiculo_recepcion", data_insertar, "PENDIENTE").done(function (respuesta) {
                    $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                        Swal.fire({
                            type: 'success',
                            title: 'El Registro temporalmente',
                            confirmButtonText: 'Ok',
                        }).then((result) => {
                            $('input:checkbox').prop('checked', false);
                        });              
                    });
                });
            });
        }else{
            Swal.fire({
                type: 'info',
                title: 'Aun hay campos sin completar',
                text: 'Favor completar el chequeo del vehiculo',
                confirmButtonText: 'Ok',
            })
        }        
    }else{
        Swal.fire({
            type: 'info',
            title: 'Aun hay campos sin completar',
            text: 'Favor completar los campos de la seccion DATOS DEL VEHICULO',
            confirmButtonText: 'Ok',
        })
    }

    
}

function guardar_info_local(tabla_nombre, data_insertar, es_sincronizado) {   
    return $.Deferred(function(dfd) 
    {
        var request = '';
        var active = dataBaseAppSDV.result;
        var data = active.transaction([tabla_nombre], "readwrite");
        var object = data.objectStore(tabla_nombre);

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


function actualizar_info_local(tabla_nombre, item_obtener, propiedades, es_sincronizado) {
    return new Promise(function(resolve, reject)
    {        
        let actived = dataBaseAppSDV.result;
        let objectStore = actived.transaction([tabla_nombre], "readwrite").objectStore(tabla_nombre);
        let request = objectStore.get(item_obtener);

        request.onsuccess = function(event) {
            let data = request.result;
                Object.entries(propiedades).forEach(([key, value]) => {
                    data[key] = value;
                });
                data[es_sincronizado] = es_sincronizado              
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

function actualizar_vendedor_local(propiedades, es_sincronizado) {
    return $.Deferred(function(dfd) 
    {
        let active      = dataBaseAppSDV.result;
        let data        = active.transaction(['tbl_parametros_vnt'], "readonly");
        let object      = data.objectStore('tbl_parametros_vnt');
        let indiced     = object.index('by_tipo');
        let cursord     = indiced.openCursor("DATOS_US");            
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
                console.log(data);
                console.log(propiedades);
                Object.entries(propiedades).forEach(([key, value]) => {
                    data[key] = value;
                });
                data.es_sincronizado = es_sincronizado  

            let requestUpdate = objectStore.put(data);
                requestUpdate.onerror = function(event) {
                    dfd.reject(request.error.name + '\n\n' + request.error.message);
                };
                requestUpdate.onsuccess = function(event) {
                    dfd.resolve(data);
                };
            };
        };        
        data.onerror = function () 
        {
            dfd.reject(0)       
        };

    }).promise();
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

function check_Campos(obj) {
    let count = 0;
    console.log(obj);
    //recorremos el arreglo
    if(Object.entries(obj).length > 0){        
        Object.entries(obj).forEach(([key, value]) => {
            if (
                value.value == undefined ||
                value.value == "" ||
                value.value.length <= 0
            ) {
                count++;
            }            
        });
    }else{
        count++;        
    }
    return count;
}