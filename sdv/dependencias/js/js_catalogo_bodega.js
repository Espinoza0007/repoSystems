var codigo_cat = '';
var codigo_familia = '';
var foto_catalogo_producto = '';
var opcion = '';
var subfamilia_selected = '';
var canales_asignados_ = [];
var distribuidoras_ls = [];
$(document).ready(function() {

    $('#panel_mantinimiento').on('shown.bs.collapse', function (e) {
        cargar_panel_mantenimiento();
    });

    $('#panel_mantinimiento').on('hidden.bs.collapse', function (e) {
        cerrar_panel_mantenimiento();
    });

    $('#modal_catalogo_bodega').on('hidden.bs.modal', function (e) {
        // $('#frm_catalogo_bodega').reset();
        document.getElementById("frm_catalogo_bodega").reset();
        $("#slc_familia_cat").val('').trigger('change');
        $("#slc_UM_cat").val('').trigger('change');
        $("#slc_subfamilia_cat").val('').trigger('change');
        $("#canvas_cat").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        subfamilia_selected = '';
        foto_catalogo_producto = '';
        $('#div_ls_distribuidoras').html('');
        $('#div_ls_distribuidoras').hide('');
    });

    $('#modal_catalogo_bodega').on('shown.bs.modal', function (e) {
        if(subfamilia_selected!=''){
            $("#slc_subfamilia_cat").val(subfamilia_selected).trigger('change');
        }
        
    });

    $(document).on('focus', 'input[type=number]', function (e) {
        $(this).on('wheel.disableScroll', function (e) {
            e.preventDefault()
        })
    })

    $(document).on('blur', 'input[type=number]', function (e) {
        $(this).off('wheel.disableScroll')
    })

    // --------- EDITAR UN PRODUCTO DEL CATALOGO ---------------------------------------------------------------------
    $("#catalogoDbodega tbody").on('click', '.btn_editar', function(){
        opcion = 'editar';
        var img = table_catalogo_bo.row( $(this).parents("tr") ).data().Cat_img;
        subfamilia_selected = table_catalogo_bo.row( $(this).parents("tr") ).data().Subf_Id
        codigo_cat = table_catalogo_bo.row( $(this).parents("tr") ).data().Cat_Id;
        $("#txtDescripcionCat").val(table_catalogo_bo.row( $(this).parents("tr") ).data().Cat_descripcion);
        $("#slc_familia_cat").val(table_catalogo_bo.row( $(this).parents("tr") ).data().Fa_Id).trigger('change');
        $("#slc_UM_cat").val(table_catalogo_bo.row( $(this).parents("tr") ).data().Um_Id).trigger('change');
        $("#slc_estado_cat").val(table_catalogo_bo.row( $(this).parents("tr") ).data().Catx_estado).trigger('change');
        $("#slc_estado_cat option[value="+table_catalogo_bo.row( $(this).parents("tr") ).data().Cat_estado+"]").attr("selected",true);
        $('#modal_catalogo_bodega').modal("toggle");
        $("#txtCodigoCat").val(codigo_cat);
        $('#txtCodigoCat').prop( "readonly", true );
        $('#canvas_cat').attr('src','https://bocadeli.info/'+img);
        $('#div_ls_distribuidoras').show();
        $('#file_cat_bo').addClass('ignore');
        canales_asignados(codigo_cat);
    });

    $(document).on('click', '#btn_agregar_cat', function(){
        opcion = 'Agregar';
        $('#modal_catalogo_bodega').modal("toggle");
        $('#txtCodigoCat').prop( "readonly", false );
        $('#file_cat_bo').removeClass('ignore');
        lista_distribuidoras_();
        $('#div_ls_distribuidoras').show()
    });

    $(document).on('change','#slc_fil_familia', function () {
        codigo_familia = $(this).select2('val');
        llenar_filtros_admin('fil_subfamilia', 'slc_fil_subfamilia',  'Subf_Id', 'Subf_nombre','filtro_subfamilias', codigo_familia);      
    });


    $(document).on('change','#slc_familia_cat', function () {
        codigo_familia = $(this).select2('val');
        if (subfamilia_selected != '') {
            llenar_filtro_subfamilia('select_subfamilia', 'slc_subfamilia_cat',  'Subf_Id', 'Subf_nombre','filtro_subfamilias', codigo_familia,subfamilia_selected);
        }else{
            llenar_filtros_admin('select_subfamilia', 'slc_subfamilia_cat',  'Subf_Id', 'Subf_nombre','filtro_subfamilias', codigo_familia);        
        }
    });

    /*$(document).on('change','.check_dis', function (){
        var val = $(this).val();
        if ($(this).is(':checked')) {
            lista_canales_($(this).val(), $(this).data('nombre'));
        } else {
            $('#dis_'+val).html('');
        }
    });*/

    /*$(document).on('change','.check_dis', function (){
        var val_ = $(this).val();
        if (!$(this).is(':checked')) {
            $('#dis_'+val_).find(".form-check-input").each(function(index, val) {
                $(val).removeAttr('checked');
            });
        } else {
            $('#dis_'+val_).find(".form-check-input").each(function(index, val) {
                $(val).attr('checked','checked');
            });
        }
    });*/

    $(document).on('change','.check_dis', function (){
        var val_ = $(this).val();
        if (!$(this).is(':checked')) {
            $('#dis_'+val_).find(".check_canal").each(function(index, val) {
                $(val).removeAttr('checked');
            });
        } else {
            $('#dis_'+val_).find(".check_canal").each(function(index, val) {
                $(val).attr('checked','checked');
            });
        }
    });

    $(document).on('change','#slc_estado_cat', function () {
        if($(this).val() == 0){
            $('#div_ls_distribuidoras').hide()
        }else{
            $('#div_ls_distribuidoras').show();
            // canales_asignados(codigo_cat);
        }
    });

    var validator = $('form[id="frm_catalogo_bodega"]').validate({
        ignore: ".ignore" ,
        rules: {            
            txtCodigoCat: 'required',
            txtDescripcionCat: 'required',
            file_cat_bo: 'required',
            slc_fil_familia: {
                required: true
            },
            slc_subfamilia_cat: {
                required: true
            },
            slc_UM_cat: {
                required: true
            }
        },
        onkeyup: function(element, event) {
            $(element).valid();
        },
        onChange: function(element, event) {
            $(element).valid();
        },
        messages: {
            txtCodigoCat: 'Indique el numero de lote',
            txtDescripcionCat: 'Por favor adjuntar una fotografía',
            file_cat_bo: 'Por favor adjuntar una fotografía',            
            slc_fil_familia: {
                required: 'Por favor seleccione un tipo de reclamo'
            },
            slc_subfamilia_cat: {
                required: 'Por favor seleccione un tipo de reclamo'
            },
            slc_UM_cat: {
                required: 'Por favor seleccione un tipo de reclamo'
            }

        },
        submitHandler: function(form) {
            // alert('* Se manda *');
            guardar_cambios();
        }
    });

});

function procesar_cambios(){
    var data_cat = [];
    var canales_inactivos = [];
    $(document).find("input[name='id_canales_ls[]']").each(function(index, val) {
        if (!$(val).is(':checked')) {
            canales_inactivos.push($(val).val());
        }
    });
    data_cat = $("#frm_catalogo_bodega").serializeArray();
    data_cat.push({name: 'opcion', value: opcion});   
    data_cat.push({name: 'foto_catalogo_producto', value: foto_catalogo_producto});   
    data_cat.push({name: 'canales_inactivos', value: canales_inactivos});
    $.ajax({
        url:'C_reclamos/Ctr_ingreso_reclamos/procesar_producto_catalogo_bo',
        type:"POST",  
        data: data_cat,      
        dataType: "JSON",
        timeout  : 27777
    }).done(function(resp_data) {  
        console.log(resp_data);
            if(resp_data.rs){
                if(resp_data.cla == 'success'){
                    Swal.fire({
                        type: 'success',
                        title: 'Registro enviado exitosamente!',
                        showConfirmButton: true,
                    }).then((result) => {
                        $("#modal_catalogo_bodega").modal("toggle");
                        ls_catalogo_productos_bodega()
                    });
                }else if(resp_data.cla == 'successedit'){
                    Swal.fire({
                        title: 'Registro editado con exito',
                        type: 'success',
                        confirmButtonText:'Ok'
                    }).then((result) => {
                        $("#modal_catalogo_bodega").modal("toggle");
                        ls_catalogo_productos_bodega()
                    });
                }else{
                    Swal.fire({
                        title: 'No se realizó ningun cambio',
                        type: 'info',
                        // timer: 1500,
                        confirmButtonText:'Ok'
                    }).then((result) => {
                        $("#modal_catalogo_bodega").modal("toggle");
                    });
                }
            }else{
                Swal.fire({
                    title: 'Aviso!',
                    type: 'error',
                    html:'<h6>'+resp_data.info+'</h6>',
                    confirmButtonText:'Ok'
                });
            }
    }).fail(function() {
        Swal.fire({
            type: 'error',
            title: 'Ha ocurrido un error al cargar los datos...',
            showConfirmButton: false,
            timer: 1500
        });
    });  
}


function guardar_cambios() {
    if($('#txtDescripcionCat').val() != '' && $('#slc_familia_cat').select2('val') != ''
     && $('#slc_UM_cat').select2('val') != ''){
        if(opcion == 'Agregar' && $('#txtCodigoCat').val() != ''  && foto_catalogo_producto != ''){
            procesar_cambios();
        } else if (opcion == 'editar'){
            procesar_cambios();
        }else {
            Swal.fire({
                type: 'info',
                title: 'Hay campos sin llenar',
                text: 'La imagen es necesaria para guardar el registro',
                confirmButtonText:'Ok'
            });
        }
        
    }else{
        Swal.fire({
            type: 'info',
            title: 'Hay campos sin llenar',
            text: 'Todos los campos son necesarios para guardar el registro',
            confirmButtonText:'Ok'
        });
    }
}

function ls_catalogo_productos_bodega(){
    $('#catalogoDbodega').DataTable().destroy();
    table_catalogo_bo = $('#catalogoDbodega').DataTable( {
        "processing": true,
        "serverSide": true,
        "filter": true,
        "order": [[0, "asc" ]],
        "ajax": {
            "url": 'C_reclamos/Ctr_ingreso_reclamos/ls_productos_bodega',
            "type": 'POST',    
            "data": {tipo_catalogo: 2},        
            "dataType": "JSON",
            "timeout"  : 27777
        },        
        dom: 'Blfrtip',
        buttons: [
            'copyHtml5',
            {
                extend: 'excelHtml5',
                autoFilter: true,
                title: 'Reclamos_'+ get_fecha_hora(),
                sheetName: 'Registros'
            },
            'csvHtml5'
        ],        
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 filas', '25 filas', '50 filas', 'Ver todo' ]
        ],
        "columns" : [
            { "data" : "Cat_Id" },
            { "data" : "Cat_descripcion" },
            { "data" : "Cat_img" },
            { "data" : "Um_nombre" },
            { "data" : "Fa_nombre" },
            { "data" : "Subf_nombre" },
            { "data" : "Catx_estado" },
            { "data" : "Catx_estado" },
            { "data" : "Fa_Id" },
            { "data" : "Subf_Id" },
            { "data" : "Um_Id" }
        ],         
        "columnDefs":[
            {
                "targets":[2],
                "data": "Cat_img",
                "render": function(data, type, row){
                    
                    if(row.Cat_img != '' && row.Cat_img != null){
                        img_html = '<img src="https://bocadeli.info/'+data+'" class="img_datatable" style="border: 1px solid black;max-height:65px;width:auto;">'
                    }else{
                        img_html = '<img src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;max-height:65px;width:auto;">'
                    }
                    return img_html;
                }
            },
            {
                "targets":[0],
                "data": "Cat_Id",
                "searchable":true,
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
            },
            {
                "targets":[7],
                "data": "Catx_estado",
                "render": function(data, type, row){
                    html_btn = '<button type="button" class="btn btn-info btn_editar"><i class="far fa-edit fa-lg"></i></button> ';
                    // html_btn += '<button type="button" class="btn btn-info btn_inactivar"><i class="fas fa-power-off fa-lg"></i></button>';
                    return html_btn;
                }
            },
            {
                "targets": [ 8 ],
                "visible": false
            },
            {
                "targets": [ 9 ],
                "visible": false
            },
            {
                "targets": [ 10 ],
                "visible": false
            }
        ], 
        initComplete: function () {

            this.api().columns([4]).every(function(i){
                var column = this,
                // select = $('#familas-p')
                select = $('#familas-p').on( 'change', function(){
                    var val = $.fn.dataTable.util.escapeRegex($(this).select2('val'));
                        column.search(val).draw();
                        // guardar_filtro(val,1);
                        console.log($(this).select2('val'));
                });

            });
            
        },
        "language": {
            // "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json",
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
            "pageLength": 10
    });
}

function cargar_panel_mantenimiento(){
    $.when( $("#panel_info_reclamos").stop(true,true).hide(200) ).done(function( x ) {
        $.when( $("#panel_ingreso_reclamos").stop(true,true).hide(200) ).done(function( x ) {
            Promise.all([
                ls_catalogo_productos_bodega(),
                llenar_filtros_admin('fil_familia', 'slc_fil_familia',  'Fa_Id', 'Fa_nombre','filtro_familias', ''),
                llenar_filtros_admin('select_familia', 'slc_familia_cat',  'Fa_Id', 'Fa_nombre','filtro_familias', ''),
                llenar_filtros_admin('select_UM', 'slc_UM_cat',  'Um_Id', 'Um_nombre','filtro_um', ''),
                

            ])
            .then(respuestas =>{
                
                $('#panel_mantinimiento_').html('<i class="fas fa-arrow-circle-left fa-lg"></i> Regresar');
                $('#btn_ingresar_rec').hide();
            })
            .catch(error =>{
                console.log(error);
            });            
        });
    });
}

function cerrar_panel_mantenimiento(){
    $.when( $("#panel_mantinimiento").stop(true,true).hide(200) ).done(function( x ) {
        $.when( $("#panel_info_reclamos").stop(true,true).show(200) ).done(function( x ) {
            $('#catalogoDbodega').DataTable().destroy();
            table_catalogo_bo = '';
            $('#panel_mantinimiento_').html('<i class="fas fa-folder-plus fa-lg"></i> Catalogo de productos')
            $('#btn_ingresar_rec').show();
        });
    });
}

function FechaReporte(){
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
    var fecha = String(hoy.getFullYear()) +'-'+ String(mes) +'-'+ String(dia);
    var hora = String(hora) + String(minutos) + String(segundos);
    var fecha_rep = String(fecha) +'_'+ String(hora);
    return fecha_rep;
}

function procesar_catalogo_producto(){
    var data_catalogo = [];
    
    data_catalogo = $("#frm_catalogo_bodega").serializeArray();
    
    $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
        $.ajax({
            url      : 'reclamo_nuevo1',
            type     : 'POST',
            dataType : 'JSON',
            data     : data_catalogo,
            timeout  : 10777,
            beforeSend: function(){
            }
        }).done(function(_resp){

            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                if(_resp.rs == true){
                    Swal.fire({
                        type: 'success',
                        title: 'Registro enviado exitosamente!',
                        showConfirmButton: true,
                        timer: 1500
                    }).then((result) => {
                        cerrar_modal();
                    });
                }else{
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h6>'+_resp.errores+'</h6>',
                        confirmButtonText:'Ok'
                    });
                }
            });
        }).fail(function(_resp, textStatus, errorThrown) {
            console.log('Error al enviar los datos');
        });
    
    });
}

function llenar_filtro_subfamilia(nombre_div, nombre_select, nombre_codigo, 
    nombre_valor, ruta, codigo_filtro, seleccionar)
{
    arr_mun = [];
    $.ajax({
        url: ruta,
        type:"POST",  
        data: {codigo: codigo_filtro, 
            pais: arrg_Credls['pais'],
            privilegio: arrg_Credls['privilegio'],
            distribuidora: arrg_Credls['id_distribuidora']
            },
        dataType: "JSON",
        timeout  : 27777
    }).done(function(resp_data) {
        for (var key in resp_data) {
            arr_mun[key] = {
                codbx:resp_data[key][nombre_codigo],
                valor:resp_data[key][nombre_valor]
            };
        }
        $('div[id="'+nombre_div+'"]').html(_form_dropdown(nombre_select,arr_mun,'',''));
        $('select[id="'+nombre_select+'"]').select2();
        $("#slc_subfamilia_cat").val(seleccionar).trigger('change');
        arr_mun = [];
    }).fail(function() {
        console.log('Error al cargar los datos');
    });
}


// --- 29/09/2021 ---------------------------------------------------------------------------------------------------
function lista_distribuidoras_() {
     var html = ``;
    $.ajax({
        url: 'C_reclamos/Ctr_ingreso_reclamos/ls_distribuidoras_canales',
        // url: 'data_filtro_dist',
        type:"POST",  
        data: { codigo: arrg_Credls['id_division'] },
        dataType: "JSON",
        timeout  : 27777
    }).done(function(resp_data) {
        distribuidoras_ls = resp_data;
        distribuidoras = {};

        resp_data.forEach( x => {
            distribuidoras[x.Dis_Id] = {
                Dis_Id: x.Dis_Id,
                Dis_nombre: x.Dis_nombre
            }
        });
        for (var dis in distribuidoras) {
            html += `
            <tr>
                <td width = "50%">
                    <div class="form-check">
                        <input class="form-check-input check_dis" data-nombre="${distribuidoras[dis]['Dis_nombre']}" type="checkbox" value="${distribuidoras[dis]['Dis_Id']}" name="id_distribuidora_ls[]">
                        <label class="form-check-label">
                            ${distribuidoras[dis]['Dis_nombre']}
                        </label>
                    </div>
                </td>
                <td id="dis_${distribuidoras[dis]['Dis_Id']}" width = "50%">`;
                    for (var key in resp_data){
                        if(distribuidoras[dis]['Dis_Id'] == resp_data[key]['Dis_Id']){
                            if(resp_data[key]['Ca_nombre'] != distribuidoras[dis]['Dis_nombre']){
                                html += `
                                    <div class="form-check">
                                        <input class="form-check-input check_canal" type="checkbox" value="${resp_data[key]['Ca_Id']}" name="id_canales_ls[]">
                                        <label class="form-check-label">
                                            ${resp_data[key]['Ca_nombre']}
                                        </label>
                                    </div>`;
                            }else{
                                if(dis != arrg_Credls['id_division']){
                                    html += `
                                    <div class="form-check d-none">
                                        <input class="form-check-input check_canal" type="checkbox" value="${resp_data[key]['Ca_Id']}" name="id_canales_ls[]">
                                        <label class="form-check-label">
                                            ${resp_data[key]['Ca_nombre']}
                                        </label>
                                    </div>`;
                                }

                            }
                        }
                    }
                html += `</td>
            </tr>`;
        }
        
        $('#div_ls_distribuidoras').html(html);
        arr_mun = [];
    }).fail(function() {
        console.log('Error al cargar los datos');
    });
}

function canales_asignados(producto) {
    $.ajax({
        url: 'C_reclamos/Ctr_ingreso_reclamos/canales_asignados_producto',
        type:"POST",  
        data: { 
            division :arrg_Credls['id_division'],
            id_producto: producto },
        dataType: "JSON",
        timeout  : 27777
    }).done(function(resp_data) {
        var distribuidoras = {};
        var html = ``;
        var block = ``;
        var ca = ``;
        var dis = ``;
        var dis_id = 0;
        var dis_count = 0;
        resp_data.forEach( x => {
            distribuidoras[x.Dis_Id] = {
                Dis_Id: x.Dis_Id,
                Dis_nombre: x.Dis_nombre
            }
        });

        for (var dis in distribuidoras) {
            dis_count = 0;
            html = `<td id="dis_${distribuidoras[dis]['Dis_Id']}" width = "50%">`;
                for (var key in resp_data){
                    if(distribuidoras[dis]['Dis_Id'] == resp_data[key]['Dis_Id']){

                        if(resp_data[key]['Ca_nombre'] == distribuidoras[dis]['Dis_nombre']){
                            if(resp_data[key]['Estado'] != 0){
                                ca += `
                                    <input type="checkbox" class='d-none check_canal' value="${resp_data[key]['Ca_Id']}" name="id_canales_ls[]" checked>
                                `;
                            }else{
                                ca += `
                                    <input type="checkbox" class='d-none check_canal' value="${resp_data[key]['Ca_Id']}" name="id_canales_ls[]">
                                `;
                            }
                        }else{
                            if(resp_data[key]['Estado'] != 0){
                                dis_count++;
                                ca += `
                                <div class="form-check">
                                    <input class="form-check-input check_canal" type="checkbox" value="${resp_data[key]['Ca_Id']}" name="id_canales_ls[]" checked>
                                    <label class="form-check-label">
                                        ${resp_data[key]['Ca_nombre']}
                                    </label>
                                </div>`;
                            }else{
                                ca += `
                                <div class="form-check">
                                    <input class="form-check-input check_canal" type="checkbox" value="${resp_data[key]['Ca_Id']}" name="id_canales_ls[]">
                                    <label class="form-check-label">
                                        ${resp_data[key]['Ca_nombre']}
                                    </label>
                                </div>`;
                            }
                        }
                    }
                }
                if(dis_count > 0){
                    dis += `
                    <tr>
                        <td width = "50%">
                            <div class="form-check">
                                <input class="form-check-input check_dis" data-nombre="${distribuidoras[dis]['Dis_nombre']}" type="checkbox" 
                                value="${distribuidoras[dis]['Dis_Id']}" name="id_distribuidora_ls[]" checked>
                                <label class="form-check-label">
                                    ${distribuidoras[dis]['Dis_nombre']}
                                </label>
                            </div>
                        </td>`;  
                }else{
                    dis += `
                    <tr>
                        <td width = "50%">
                            <div class="form-check">
                                <input class="form-check-input check_dis" data-nombre="${distribuidoras[dis]['Dis_nombre']}" type="checkbox" 
                                value="${distribuidoras[dis]['Dis_Id']}" name="id_distribuidora_ls[]">
                                <label class="form-check-label">
                                    ${distribuidoras[dis]['Dis_nombre']}
                                </label>
                            </div>
                        </td>`;
                }
                block +=  dis + html + ca;
                ca = ``;

                block += `</td>`;
            block += `</tr>`;
        }

        $('#div_ls_distribuidoras').html(block);
    }).fail(function() {
        console.log('Error al cargar los datos');
    });
}
// --- FIN 29/09/2021 -----------------------------------------------------------------------------------------------


