window.onbeforeunload = function() {
    if(warn_on_unload != ''){
        return warn_on_unload;
    }
}
var table_catalogo = null;
var select_um = [];
var select_um_html = '';
var data_rec_nuevo = '';
var str_codigo_token_ = [];
var fotoFechaLote_ = '';
var fotoProducto_ = '';

$( document ).ready(function() {  

    $(document).on('click','#btn_regresar', function (){  
        $('#panel_info_reclamos').toggle("fast");
        $('#panel_ingreso_reclamos').toggle("fast");
        $('#btn_ingresar_rec').show()
        $(this).remove();
    });    

    $("#slc_tipo_reclamos").select2({
        placeholder: "Seleccione una opción",
        allowClear: true
    });

    $('#txtFechaVencimiento_').datepicker({format: 'yyyy-mm-dd' });

    $('#catalogoDtable tbody').on( 'click', 'tr', function () {   
        select_um_html = '';
        $('.select2_m').select2();
        if(table_catalogo.row( this ).data().Um_nombre != 'UN'){
            select_um[0] = table_catalogo.row( this ).data().Um_nombre;
            select_um[1] = 'UNIDAD';
        }else{
            select_um[0] = table_catalogo.row( this ).data().Um_nombre;
        }
        select_um_html += '<select class="custom-select" id="select_um" name="select_um" data-width="100%">' +
        '<option value="" hidden style="height:38px;">Seleccione una opción</option>';           

        select_um.forEach( function(valor, indice, array) {
            select_um_html+='<option value="'+ valor + '">' + valor + '</option>';
        });

        select_um_html+='</select>';        
        $("#fil_um_").html(select_um_html);
        $("#select_um").val(table_catalogo.row( this ).data().Cat_unidad_medida);
        $("#txtProducto_").val(table_catalogo.row( this ).data().Cat_descripcion);
        $("#txtCodigoP_").val(table_catalogo.row( this ).data().Cat_Id);
        // llenar_filtros_admin('select_UM', 'slc_UM_cat',  'Um_Id', 'Um_nombre','filtro_um', '')

        $("#modal_ingreso_rec").modal("toggle");
    }); 

    $('#modal_ingreso_rec').on('hidden.bs.modal', function (e) {       

        $("#fil_um_").empty();
        $('#select_um').val(0); 
        $("#canvas_").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        $("#canvas1_").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        document.getElementById("frm_ingreso_rec").reset();   
        $("#slc_tipo_reclamos").val('').trigger('change')  
    }); 

    $(document).on('change','#slc_pais_', function () { 
        codigo_pais = $(this).select2('val');
        llenar_filtros_admin('fil_division_', 'slc_division_',  'Di_Id', 'Di_nombre','data_filtro_division', codigo_pais);
        $('#fil_distribuidora_').html(html_slc);
        
        $('.select2_m').select2();
        codigo_division = '';
        codigo_dis = '';
       
    });

    $(document).on('change','#slc_division_', function () { 
        codigo_division = $(this).select2('val');
        llenar_filtros_admin('fil_distribuidora_', 'slc_distribuidora_', 'Dis_Id', 'Dis_nombre', 'data_filtro_dist', codigo_division);
        
        $('.select2_m').select2();
        codigo_dis = '';

    });

    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
          var re = new RegExp(regexp);
          return this.optional(element) || re.test(value);
        },
        "Por favor introducir formato correcto"
    );

    var validator = $('form[id="frm_ingreso_rec"]').validate({
        ignore: ".ignore" ,
        rules: {            
            file_fecha_lote_: 'required',
            file_producto_: 'required',
            txtNumLote_: 'required',
            txtFechaVencimiento_: {
                required: true
            },
            txtCantidad_: {
                required: true,
                digits: true,
                min: 1,
                regex: '^[0-9]{1,3}$'
            } ,           
            slc_tipo_reclamos: {
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
            txtNumLote_: 'Indique el numero de lote',
            file_fecha_lote_: 'Por favor adjuntar una fotografía',
            file_producto_: 'Por favor adjuntar una fotografía',
            txtNumeroLote: 'Por favor indique el número de lote',
            txtFechaVencimiento_: 'Por favor seleccionar una fecha de vencimiento',
            txtCantidad_: {
                required: 'Por favor indique la cantidad',
                digits: 'Ingrese solo numeros enteros por favor',
                min: 'El valor mínimo es 1',
            },
            slc_tipo_reclamos: {
                required: 'Por favor seleccione un tipo de reclamo'
            }  
        },
        submitHandler: function(form) {
          // alert('*Se manda*');
          procesar_reclamo_bodega();
        }
    });


    $('#familas-p').on( 'change', function () {
        table_catalogo.search( $(this).select2('val') ).draw();
    } );

    $('#panel_ingreso_reclamos').on('shown.bs.collapse', function (e) {
        cargar_panel_ingreso_rec();
    })

    $('#panel_ingreso_reclamos').on('hidden.bs.collapse', function (e) {
        cerrar_panel_ingreso_rec();
    })
    // shown.bs.collapse

}); // FIN DOCUMENT READY ////////////////////

function llenar_catalogo_productos(){
    $('#catalogoDtable').DataTable().destroy();
    table_catalogo = $('#catalogoDtable').DataTable( {
        "processing": true,
        "serverSide": true,
        "filter": true,
        "order": [[0, "asc" ]],
        "ajax": {
            "url": 'catalogo_bodega',
            "type": 'POST',            
            "dataType": "JSON",
            "data"  : {tipo_catalogo: 1},
            "timeout"  : 27777,
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
            { "data" : "Catx_estado" }
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


    // 
}


// function enviar_reclamo(){
//     if(_empty(Id_Cliente)){
//         Swal.fire({
//             title: '<strong>Aviso!</strong>',
//             type: 'info',
//             html:'<strong>Selecciona un cliente por favor</strong>',
//             confirmButtonText:'Ok'
//         });
//     }else{        
//         procesar_ingreso_reclamo();
//         //console.log('0-guardaddo');  
//     }
// }

function procesar_reclamo_bodega(){
     
    // usuarioCodigo = arrg_Credls['us_cod'];
    data_rec_nuevo = $("#frm_ingreso_rec").serializeArray();
    data_rec_nuevo.push({name: 'codigo_reclamo', value: str_codigo_token_[0]});
    data_rec_nuevo.push({name: 'token_reclamo', value: str_codigo_token_[1]});
    data_rec_nuevo.push({name: 'fileFechaLote', value: fotoFechaLote_});
    data_rec_nuevo.push({name: 'fileProducto', value: fotoProducto_});

    //console.log(data_rec_nuevo);
    $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'reclamo_nuevo1',
                type     : 'POST',
                dataType : 'JSON',
                data     : data_rec_nuevo,
                timeout  : 10777,
                beforeSend: function(){
                }
            }).done(function(_resp){
                if(_resp.rs == true){
                    Swal.fire({
                        type: 'success',
                        title: 'Registro enviado exitosamente!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result) => {
                        
                    });
                }else{
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<h6>'+_resp.errores+'</h6>',
                        confirmButtonText:'Ok'
                    });
                }
            }).always(function(_resp, textStatus, errorThrown) {
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                        if(textStatus == "success") {
                            if(_resp.rs == true){
                                Swal.fire({
                                    type: 'success',
                                    title: 'Registro enviado exitosamente!',
                                    showConfirmButton: true
                                }).then((result) => {
                                    // $('#modal_ingreso_rec').toggle();
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
                        }else{
                            _ajax_error_Reclamo(_resp.status,_resp.readyState,_resp.statusText);
                        }
                    });
                });
            });
        });
    });
}

function enviar_registro_reclamo() {
    data_rec_nuevo = $("#frm_ingreso_rec").serializeArray();
    // data_rec_nuevo.push({name: 'codigo_reclamo', value: str_codigo_token_[0]});
    // data_rec_nuevo.push({name: 'token_reclamo', value: str_codigo_token_[1]});

    $.ajax({
        url:'img_reclamo',
        type:"POST",  
        data: data_rec_nuevo,
        dataType: "JSON",
        timeout  : 27777
    }).done(function(resp_data) {     
        if(resp_data){
            table = table_lista; 
                console.log(table);
                var indexes = table.rows().indexes().filter( function ( value, index ) {
                 console.log(value);
                return codigo_rec === table.row(value).data().Rec_Id;
            } );
            table.rows(indexes).remove().draw();

            Swal.fire({
                type: 'success',
                title: 'Imagen guardada!',
                showConfirmButton: false,
                timer: 1500
            });
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'error',
                html:'<h6>'+resp_data.errores+'</h6>',
                confirmButtonText:'Ok'
            });
        }       
    }).fail(function() {
        console.log('Error al enviar los datos');
    });
}


function cerrar_modal(){
    // document.getElementById("frm_ingreso_rec").reset();   
    $("#slc_tipo_reclamos").val('').trigger('change')  
    $("#modal_ingreso_rec").modal("toggle");
}

// function cargar_panel_ingreso_rec(){
//     $.when( $("#panel_info_reclamos").stop(true,true).hide(200) ).done(function( x ) {
//         $.when( $("#panel_ingreso_reclamos").stop(true,true).show(200) ).done(function( x ) {
//             Promise.all([
//                 llenar_catalogo_productos(),
//                 llenar_filtros_admin('fil_familias', 'familas-p',  'Fa_nombre', 'Fa_nombre','filtro_familias', ''),
//                 llenar_filtros_admin('fil_tipo_reclamo', 'slc_tipo_reclamos','Tipd_Id',  'Tipd_descripcion', 'data_tipo_reclamos', ''),
//                 llenar_filtros_admin('fil_pais_', 'slc_pais_',  'P_Id', 'P_nombre','data_filtro_pais', ''),
//                 $('#btn_ingresar_rec').hide(),
//                 $('#nav_ingreso_rec').append('<button type="button" id="btn_regresar" class="btn btn-outline-light" style="font-size:16px;font-weight: bold;"><i class="fas fa-arrow-circle-left fa-lg"></i> Regresar</button>')
//             ])
//             .then(respuestas =>{
//                 console.log('bien');
//             })
//             .catch(error =>{
//                 console.log(error);
//             });
//         });
//     });
// }

function cargar_panel_ingreso_rec(){
    $.when( $("#panel_info_reclamos").stop(true,true).hide(200) ).done(function( x ) {
        $.when( $("#panel_mantinimiento").stop(true,true).hide(200) ).done(function( x ) {
                Promise.all([
                    llenar_catalogo_productos(),
                    llenar_filtros_admin('fil_familias', 'familas-p', 'Fa_nombre', 'Fa_nombre','filtro_familias', ''),
                    llenar_filtros_admin('fil_tipo_reclamo', 'slc_tipo_reclamos','Tipd_Id',  'Tipd_descripcion', 'data_tipo_reclamos', ''),
                    llenar_filtros_admin('fil_pais_', 'slc_pais_',  'P_Id', 'P_nombre','data_filtro_pais', '')
                ])
                .then(respuestas =>{
                    if($('#fil_distribuidora_pt').length){
                        llenar_filtros_admin('fil_distribuidora_pt', 'slc_distribuidora_pt','Cli_Ru_Id', 'Cli_nombre', 'C_reclamos/Ctr_ingreso_reclamos/filtro_distribuidora_pt')
                    }
                    $('#btn_ingresar_rec').html('<i class="fas fa-arrow-circle-left fa-lg"></i> Regresar');
                    $('#panel_mantinimiento_').hide();
                })
                .catch(error =>{
                    console.log(error);
                });            
        });
    });
}

function cerrar_panel_ingreso_rec(){
    $.when( $("#panel_mantinimiento").stop(true,true).hide(200) ).done(function( x ) {
        $.when( $("#panel_info_reclamos").stop(true,true).show(200) ).done(function( x ) {
            $('#btn_ingresar_rec').html('<i class="fas fa-folder-plus fa-lg"></i> Ingresar reclamo');
            $('#panel_mantinimiento_').show();
        });
    });
}


