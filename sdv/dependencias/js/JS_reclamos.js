window.onbeforeunload = function() {
    if(warn_on_unload != ''){
        return warn_on_unload;
    }
}
var fotoFechaLote = '';
var fotoProducto = '';
var codigo_rec = '';
var id_cliente = '';
var datas = [];
var arr_mun = [];
var atributos_dropdown = {
    class_input:'form-control custom-select'
};
var html_slc = '<select class="form-control custom-select select2_" data-width="100%"><option>Seleccione una opción</option></select>';
var codigo_dist = '';
var codigo_ca = '';
var codigo_grupo = '';
var codigo_ruta = '';
var fecha_inicial = '';
var fecha_limite = '';
var reclamo_arr = [];
var codigo_pais = '';
var codigo_division = '';
var foto_reclamo = '';
var table = null;
var table_lista = null;
var table_detalle = null;
var table_reclamos = null;
var tipoUsuarioLogueado = false;
var url_descarga = '';
$( document ).ready(function() {    
    iniciar_admin_reclamos();
    //---- llenar campos iniciales de pagina -----------------------------------------------------------------------
    llenar_filtros_admin('fil_pais', 'slc_pais',  'P_Id', 'P_nombre','data_filtro_pais', '');
   // location.href = "reclamos";
    $('.select2_').select2();
    $('#txtFechaInicial').datepicker({format: 'yyyy-mm-dd' });
    $('#txtFechaLim').datepicker({format: 'yyyy-mm-dd' });
    $('#lista_reclamos_table').DataTable({
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Filtre para ver registros...",
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
            "infoPostFix": "",
            "lengthChange": false,
            "iDisplayLength": 10,
            "scrollCollapse": true
        }        
    });
    lightbox.option({
        'resizeDuration': 400,
        'wrapAround': true
    })
    // --------- CARGA DE TABLA -----------------------------------------------------------------------------------
    $(document).on('click','#btn_buscar', function () {
        if(arrg_Credls['TipoUsuario'] != 5){
            if(codigo_pais != '' && codigo_division != ''){
                llenar_tabla_reclamos();
            }else{
                Swal.fire({
                    type: 'info',
                    title: 'Filtre para ver registros',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }else{
            llenar_tabla_reclamos();
        }
    });
    // --------- OBTENER INFO DE UN REGISTRO ---------------------------------------------------------------------
    $("#lista_reclamos_table tbody").on('click', '.btn_detalle', function(){
        codigo_rec = $(this).val();
        console.log(codigo_rec);
        tipoUsuarioLogueado = arrg_Credls['TipoUsuario'] != 5 ? true : false;
        datas.push({name: 'rec_codigo', value: codigo_rec});     
        $('#modal_detalle_rec').modal("toggle");
        $("#txtCodigoReclamo").val(codigo_rec);
        $.ajax({
            url:'data_reclamo_reg',
            type:"POST",  
            data: datas,      
            dataType: "JSON",
            timeout  : 27777
        }).done(function(resp_data) {  
            reclamo_arr = resp_data;
            // console.log(resp_data);
            $("#txtDireccion").val(resp_data[0].Cli_direccion);    
            var empleado =  resp_data[0].Emp_nombre != '' && resp_data[0].Emp_nombre != null ? resp_data[0].Emp_carnet + ' - ' + resp_data[0].Emp_nombre : ' -- '; 
            $("#txtEmpleado").val(empleado);
            $("#txtCliente").val(resp_data[0].Cliente);
            $("#txtRuta").val(resp_data[0].Ru_nombre);        
            $("#txtPais").val(resp_data[0].P_nombre);
            $("#txtUsuario").val(resp_data[0].Usu_nombre_usuario);
            $("#txtCanal").val(resp_data[0].Ca_nombre);
            $("#txtDistribuidora").val(resp_data[0].Dis_nombre);
            $("#txtDivision").val(resp_data[0].Di_nombre);
            $("#txtFechaReclamo").val(resp_data[0].Rec_fecha_servidor);
            $("#txtIdRuta").val(resp_data[0].Ru_Id);
            $("#txtEstado").val(resp_data[0].Rec_estado);
            url_descarga = "https://bocadeli.info/"+resp_data[0].Rec_img_aprobado;
            if(resp_data[0].Rec_img_aprobado != null && resp_data[0].Rec_img_aprobado != ''){
                if(resp_data[0].Rec_img_aprobado.indexOf(".jpg") != -1){
                    $("#canvas").attr("src","https://bocadeli.info/"+resp_data[0].Rec_img_aprobado);
                    $("#a_canvas").attr("href","https://bocadeli.info/"+resp_data[0].Rec_img_aprobado);
                }else{
                    $("#canvas").attr("src","../dependencias/imagenes/pdf-icon-1.png");
                    $("#a_canvas").attr("href","../dependencias/imagenes/pdf-icon-1.png");
                }
            }

            if(resp_data[0].Rec_estado == "ACTIVO"){ // ------------ Activo (Se puede marcar como 2, 3, 4)
                $('#btn_revisado').show();
                $('#btn_rechazar_rec').show();
                $('#btn_finalizado').hide(); // ------------- En bodega puede finalizar si esta activo
                $('#btn_procesar_reclamo').show();
                $('#btn_descargar_rec').hide();
            }else if(resp_data[0].Rec_estado == "RECHAZADO"){ // ------ Rechazado (Se puede marcar 3)
                $('#btn_rechazar_rec').hide();
                $('#btn_revisado').show();
                $('#btn_finalizado').hide();
                $('#btn_procesar_reclamo').hide();
                $('#btn_descargar_rec').hide();
            }else if(resp_data[0].Rec_estado == "REVISADO"){ // ------ Revisado (Se pude marcar como 2 ó 4)
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').show();
                $('#btn_finalizado').hide();
                $('#btn_procesar_reclamo').show();
                $('#btn_descargar_rec').hide();
            }else if(resp_data[0].Rec_estado == "FINALIZADO"){ // ------ Finalizado (No se puden cambiar estados)
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').hide();
                $('#btn_finalizado').hide();
                $('#btn_procesar_reclamo').hide();
                $('#btn_descargar_rec').show();
            }else{ // --------------------------------------- Procesado (Se pude marcar como 2 ó 4)
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').show();
                $('#btn_finalizado').show();
                $('#btn_procesar_reclamo').hide();
                $('#btn_descargar_rec').show();
            }

            /*if(resp_data[0].Rec_estado == 1){ // ------------ Activo (Se puede marcar como 2, 3, 4)
                $('#btn_revisado').show();
                $('#btn_rechazar_rec').show();
                $('#btn_finalizado').show(); // ------------- En bodega puede finalizar si esta activo
                $('#btn_procesar_reclamo').show();
            }else if(resp_data[0].Rec_estado == 2){ // ------ Rechazado (Se puede marcar 3)
                $('#btn_rechazar_rec').hide();
                $('#btn_revisado').show();
                $('#btn_finalizado').hide();
                $('#btn_procesar_reclamo').hide();
            }else if(resp_data[0].Rec_estado == 3){ // ------ Revisado (Se pude marcar como 2 ó 4)
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').show();
                $('#btn_finalizado').show();
                $('#btn_procesar_reclamo').show();
            }else if(resp_data[0].Rec_estado == 4){ // ------ Finalizado (No se puden cambiar estados)
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').hide();
                $('#btn_finalizado').hide();
                $('#btn_procesar_reclamo').hide();
            }else{ // --------------------------------------- Procesado (Se pude marcar como 2 ó 4)
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').show();
                $('#btn_finalizado').show();
                $('#btn_procesar_reclamo').hide();
            }*/


            /*if ($('#btns_aprobado').length && resp_data[0].Rec_estado == 2) {
                $('#btn_rechazar_rec').hide();
                $('#btn_revisado').show();
            } else if ($('#btns_aprobado').length && resp_data[0].Rec_estado == 3){
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').show();
            }else if ($('#btns_aprobado').length && resp_data[0].Rec_estado == 1){
                $('#btn_revisado').show();
                $('#btn_rechazar_rec').show();
            }else if ($('#btns_aprobado').length && resp_data[0].Rec_estado == 4){
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').hide();
            }else{
                $('#btn_revisado').hide();
                $('#btn_rechazar_rec').hide();
            }*/

            table_detalle = $('#detalle_rec_table').DataTable({
                "stateSave": true,
                "stateDuration": 60 * 60 * 24,
                "data" : resp_data,
                "columns" : [
                    { "data" : "Rec_observacion_ventas" },
                    { "data" : "Cat_Id" },
                    { "data" : "Cat_descripcion" },
                    { "data" : "Tipd_descripcion" },
                    { "data" : "Rec_unidades_danadas" },
                    { "data" : "Fa_nombre" },
                    { "data" : "Rec_cantidad" },
                    { "data" : "Rec_numero_lote" },
                    { "data" : "Rec_fecha_vencimiento" },
                    { "data" : "Cat_img" },
                    { "data" : "Rec_foto_fecha_lote" },
                    { "data" : "Rec_foto_producto" }
                ],
                "columnDefs":[
                    {
                        "targets":[0],
                        "data": "Rec_observacion_ventas",
                        "visible": true,
                        "render": function(data, type, row){           
                            var str_html_ = '<button type="button" class="btn btn-info center-block btn_observacion_ventas"><i class="fas fa-comment-dots fa-w-16 fa-2x"></i></button>';

                            return str_html_;
                        }
                    },               
                    {
                        "targets":[3],
                        "data": "Tipd_descripcion",
                        "render": function(data, type, row){                            
                            
                            str_html = '<b>' +data+ '</b>'
                            
                            return str_html;
                        }
                    }, 
                    {
                        "targets":[7],
                        "data": "Rec_numero_lote",
                        "render": function(data, type, row){                            
                            if(row.Rec_numero_lote != '' && row.Rec_numero_lote != null){
                                str_html = '<p>' +data+ '</p>'
                            }else{
                                str_html = '<p> -- </p>'
                            }
                            return str_html;
                        }
                    },
                    {
                        "targets":[8],
                        "data": "Rec_fecha_vencimiento",
                        "render": function(data, type, row){                            
                            if(row.Rec_fecha_vencimiento != '' && row.Rec_fecha_vencimiento != null){
                                str_html = '<p>' +data+ '</p>'
                            }else{
                                str_html = '<p> -- </p>'
                            }
                            return str_html;
                        }
                    },
                    {
                        "targets":[9],
                        "data": "Cat_img",
                        "render": function(data, type, row){                            
                            if(row.Cat_img != '' && row.Cat_img != null){
                                // img_html = '<img src="https://bocadeli.info/' +data+ '" class="img_datatable zoom" style="border: 1px solid black;max-height:65px;width:auto;">'
                                img_html = '<a href="https://bocadeli.info/' +data+ '" data-lightbox="reclamos_img" data-title="Imagen del producto"><img class="example-image" src="https://bocadeli.info/' +data+ '" alt="" style="max-height:65px;width:auto;" /></a>'
                            }else{
                                img_html = '<img src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;max-height:65px;width:auto;">'
                            }
                            return img_html;
                        }
                    },
                    {
                        "targets":[10],
                        "data": "Rec_foto_fecha_lote",
                        "render": function(data, type, row){                            
                            if(row.Rec_foto_fecha_lote != '' && row.Rec_foto_fecha_lote != null){
                                // img_html = '<img src="../' +data+ '" class="img_datatable zoom" style="border: 1px solid black;max-height:65px;width:auto;">'
                                img_html = '<a href="../' +data+ '" data-lightbox="reclamos_img" data-title="Fecha y numero de lote"><img class="example-image" src="../' +data+ '" alt="" style="max-height:65px;width:auto;" /></a>'
                            }else{
                                img_html = '<img src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;max-height:65px;width:auto;">'
                            }
                            return img_html;
                        }
                    },
                    {
                        "targets":[11],
                        "data": "Rec_foto_producto",
                        "render": function(data, type, row){                            
                            if(row.Rec_foto_producto != '' && row.Rec_foto_producto != null){
                                // img_html = '<img src="../' +data+ '" class="img_datatable zoom" style="border: 1px solid black;max-height:65px;width:auto;">'
                                img_html = '<a href="../' +data+ '" data-lightbox="reclamos_img" data-title="Producto dañado"><img class="example-image" src="../' +data+ '" alt="" style="max-height:65px;width:auto;" /></a>'
                            }else{
                                img_html = '<img src="../dependencias/imagenes/file_3_icon-icons.com_68952.png" style="border: 1px solid black;max-height:65px;width:auto;">'
                            }
                            return img_html;
                        }
                    }
                ],
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
                }
            });
            
        }).fail(function() {
            Swal.fire({
                type: 'error',
                title: 'Ha ocurrido un error al cargar los datos...',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });
    $(document).on('click','#btn_imprimir', function () {
        console.log(reclamo_arr);
        console.log(codigo_rec);
        window.open('imprimir_reclamo?codigo_reclamo='+codigo_rec,'_blank');
    });
    $(document).on('click','#btn_exportar', function () {
        if(codigo_pais != '' && codigo_division != ''){
            exportar_ls_reclamos()
        }else{
            Swal.fire({
                title: '¿Está seguro de exportar todos los reclamos?',
                text: "",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No, cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if(result.value){
                    exportar_ls_reclamos();
                }
            });
        }
    });
    $(document).on('click','#btn_descargar_rec', function (){ 
        console.log($('#canvas').attr('src'));
        var $a = $("<a>");
        $a.attr("href", url_descarga);
        $("body").append($a);
        if(url_descarga.indexOf(".jpg") != -1){
            $a.attr("download",'autorizacion_'+get_fecha_hora()+'.jpg');
        }else{
            $a.attr("download",'autorizacion_'+get_fecha_hora());
        }
        $a[0].click();
        $a.remove();
    });
    $('#modal_detalle_rec').on('hidden.bs.modal', function (e) {
        $('#detalle_rec_table').DataTable().destroy();
        table_detalle = '';
        $("#show_rec").empty();
        $("#txtIdRuta").val("");
        document.getElementById('frm_info_reclamo').reset();
        foto_reclamo = '';
        $("#file_img_rec").val("");
        reclamo_arr = [];
        $("#canvas").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        $("#a_canvas").attr("href","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
    });   
    $(document).on('change','#slc_pais', function () { 
        codigo_pais = $(this).select2('val');
        llenar_filtros_admin('fil_division', 'slc_division',  'Di_Id', 'Di_nombre','data_filtro_division', codigo_pais);
        $('#fil_distribuidora').html(html_slc);
        $('#fil_canal').html(html_slc);
        $('#fil_grupo').html(html_slc);
        $('#fil_ruta').html(html_slc);
        $('.select2_').select2();
        codigo_division = '';
        codigo_dis = '';
        codigo_grupo = '';
        codigo_ca = '';
        codigo_ruta = '';
    });
    $(document).on('change','#slc_division', function () { 
        codigo_division = $(this).select2('val');
        llenar_filtros_admin('fil_distribuidora', 'slc_distribuidora', 'Dis_Id', 'Dis_nombre', 'data_filtro_dist', codigo_division);
        $('#fil_canal').html(html_slc);
        $('#fil_grupo').html(html_slc);
        $('#fil_ruta').html(html_slc);
        $('.select2_').select2();
        codigo_dis = '';
        codigo_grupo = '';
        codigo_ca = '';
        codigo_ruta = '';
    });
    $(document).on('change','#slc_distribuidora', function () {   
        codigo_dist = $(this).val();
        llenar_filtros_admin('fil_canal', 'slc_canal', 'Ca_Id', 'Ca_nombre', 'data_filtro_canal', codigo_dist);
        $('#fil_grupo').html(html_slc);
        $('#fil_ruta').html(html_slc);
        $('.select2_').select2();
        codigo_ca = '';
        codigo_grupo = '';
        codigo_ruta = '';
    });
    $(document).on('change','#slc_canal', function () {   
        codigo_ca = $(this).select2('val');       
        llenar_filtros_admin('fil_grupo', 'slc_grupo', 'Dist_Id', 'Dist_nombre', 'data_filtro_grupo', codigo_ca);
        $('#fil_ruta').html(html_slc);
        $('.select2_').select2();
        codigo_grupo = '';
        codigo_ruta = '';
        console.log(codigo_ca);
    });
    $(document).on('change','#slc_grupo', function () {   
        codigo_grupo = $(this).select2('val');       
        llenar_filtros_admin('fil_ruta', 'slc_ruta', 'Ru_Id', 'Ru_nombre', 'data_filtro_ruta', codigo_grupo);
    });
    $(document).on('click','#btn_procesar_reclamo', function (){        
        if (foto_reclamo != '') {
            var ruta = $("#txtIdRuta").val();
            console.log(ruta);
            $.ajax({
                url:'img_reclamo',
                type:"POST",  
                data: { codigo_reclamo: codigo_rec,
                        foto_reclamo: foto_reclamo,
                        ruta: ruta
                    },
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
                        title: 'Registro enviado correctamente!',
                        showConfirmButton: true,
                        allowOutsideClick: false
                    }).then((result) => {
                        document.getElementById("frm_detalle_rec").reset();
                        $('#modal_detalle_rec').modal("toggle");
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
        }else{           
            Swal.fire({
                title: 'Aviso!',
                type: 'info',
                html:`<h6>La imagen es requerida</h6>`,
                confirmButtonText:'Ok'
            });            
        }
    });
    $(document).on('click','#btn_rechazar_rec', function (){ 
        cambiar_estado(2);
    });
    $(document).on('click','#btn_revisado', function (){ 
        cambiar_estado(3);
    });
    $(document).on('click','#btn_finalizado', function (){ 
        cambiar_estado(4);
    });
    $("#detalle_rec_table tbody").on('click', '.btn_observacion_ventas',async function(){
        var rec_id = table_detalle.row( $(this).parents("tr") ).data().Rec_Id;
        var txtComentario = table_detalle.row( $(this).parents("tr") ).data().Rec_observacion_ventas != null ? table_detalle.row( $(this).parents("tr") ).data().Rec_observacion_ventas : '';
        var cat_id = table_detalle.row( $(this).parents("tr") ).data().Cat_Id;
        var cat_nombre = table_detalle.row( $(this).parents("tr") ).data().Cat_descripcion;
        var data = '';
        Swal.fire({
            title: "Agregue una observación",
            html: `<p style="font-size: 12px;text-align: center;"> Informacion adicional para <b>${cat_id}</b> - <b>${cat_nombre}</b>
            <br>Numero de reclamo: <b>${rec_id}</b></p>`,
            input: "textarea",
            inputValue: txtComentario,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",           
            inputValidator: nombre => {
                if (!nombre) {
                    return "Por favor digite un texto";
                } else {
                    return undefined;
                }
            },
            preConfirm: (inputtxt) => {
                $.ajax({
                    url: 'C_reclamos/Ctr_ingreso_reclamos/guardar_comentario_rec',
                    type:"POST",  
                    data: { rec_id: rec_id,
                            txtComentario: inputtxt,
                            cat_id: cat_id
                        },
                    dataType: "JSON",
                    timeout  : 27777,
                    success: function(resp){
                        // if(!resp.rs){
                        //     throw new Error(resp.info)
                        // }                 
                        return resp.rs
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        Swal.showValidationMessage(
                            `Request failed: ${resp.info}`
                        )
                    }
                })
            },
        })
        .then(resultado => {
            if (resultado.value) {
                Swal.fire({
                    title: `Listo!`,
                    text: "Comentario agregado correctamente"
                })
            }
        });
    });
    $(document).on('click','#btn_exportar_rec', function(){
        exportar_detalle_reclamo()
    });
});

//-- Llena la lista de reclamos en la pantalla inicial -------------------------------------------------------------------------
function llenar_tabla_reclamos(){
    fecha_inicial = $('#txtFechaInicial').val();
    fecha_limite = $('#txtFechaLim').val();
    var estado = $('#slc_estado').select2('val');
    $('#lista_reclamos_table').DataTable().destroy();
    table = $('#lista_reclamos_table').DataTable( {
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "order": [[ 0, "desc" ]], 
        "ajax": {
            "url": 'data_reclamos_ls1',
            "type": 'POST',
            "data": {
                codigo_pais: codigo_pais,
                codigo_division: codigo_division,
                codigo_dist: codigo_dist,
                codigo_ca: codigo_ca,
                codigo_grupo: codigo_grupo,
                codigo_ruta: codigo_ruta,
                fecha_inicial: fecha_inicial,
                fecha_limite: fecha_limite,
                estado: estado
            },  
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
        "columns": [
            { "data": "Rec_Id" },
            { "data": "Tipd_area" },
            { "data": "Cli_codigo" },
            { "data": "Tipd_descripcion" },
            { "data": "Cat_Id" },
            { "data": "P_nombre" },
            { "data": "Di_nombre" },
            { "data": "Dis_nombre" },
            { "data": "Rec_fecha_servidor" },
            { "data": "Rec_estado" },
            { "data": "Rec_Id" }
        ],
        "columnDefs":[
            {
                "targets":[0],
                "data": "Rec_Id",
                // "visible": false,
                "render": function(data, type, row){
                    data = data.split(/(\d{1,30})/);
                    /* ********************************
                        1 - activo      : primary,
                        2 - rechazado   : danger,
                        3 - revisado    : success,
                        4 - entregado   : secondary,
                        0 - procesado   : dark
                     **********************************/
                    if(row.Rec_estado == '1'){
                        span_estadow = '<span class="badge badge-primary" style="font-size:12px;">'+data[0]+'<br>'+data[1]+'</span>';
                    }else if (row.Rec_estado == '2'){
                        span_estadow = '<span class="badge badge-danger" style="font-size:12px;">'+data[0]+'<br>'+data[1]+'</span>';
                    }else if(row.Rec_estado == '3'){
                        span_estadow = '<span class="badge badge-success" style="font-size:12px;">'+data[0]+'<br>'+data[1]+'</span>';
                    }else if(row.Rec_estado == '4'){
                        span_estadow = '<span class="badge badge-secondary" style="font-size:12px;">'+data[0]+'<br>'+data[1]+'</span>';
                    }else{
                        span_estadow = '<span class="badge badge-dark" style="font-size:12px;">'+data[0]+'<br>'+data[1]+'</span>';
                    }
                    return span_estadow;
                }
            },
            {
                "targets":[1],
                "data": "Tipd_area",
                "render": function(data, type, row){   
                    if(row.Tipd_area == 1){
                        data = 'Ventas / mercadeo'
                    }else if(row.Tipd_area == 2){
                        data = 'Bodega'
                    }
                        span_estadow = '<span style="font-size:12px;">'+data+'</span>';
                    return span_estadow;
                }
            },
            {
                "targets":[3],
                "data": "Tipd_descripcion",
                "render": function(data, type, row){
                    html_btn = '<b>'+data+'</b>';
                    return html_btn;
                }
            },
            {
                "targets":[9],
                "data": "Rec_estado",
                "render": function(data, type, row){        
                    /* ********************************
                        1 - activo      : primary,
                        2 - rechazado   : danger,
                        3 - revisado    : success,
                        4 - entregado   : secondary,
                        0 - procesado   : dark
                     **********************************/     

                    if(row.Rec_estado == '1'){
                        span_estadow = '<span class="badge badge-primary" style="font-size:12px;">ACTIVO</span>';
                    }else if (row.Rec_estado == '2'){
                        span_estadow = '<span class="badge badge-danger" style="font-size:12px;">RECHAZADO</span>';
                    }else if(row.Rec_estado == '3'){
                        span_estadow = '<span class="badge badge-success" style="font-size:12px;">REVISADO</span>';
                    }else if(row.Rec_estado == '4'){
                        span_estadow = '<span class="badge badge-secondary" style="font-size:12px;">FINALIZADO</span>';
                    }else{
                        span_estadow = '<span class="badge badge-dark" style="font-size:12px;">AUTORIZADO</span>';
                    }
                    return span_estadow;
                }
            },
            {
                "targets":[10],
                "data": "Rec_Id",
                "render": function(data, type, row){                        
                    html_btn = '<button type="button" class="btn btn-info btn_detalle" id="btn_detalle" value="'+data+'"><span class="fas fa-eye fa-lg"></span></button>';
                            
                    return html_btn;
                }
            }
        ],
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
            "infoPostFix": "",
            
        },"pageLength": 1
    } );
    table_lista = table;
}
//------------------------------------------------------------------------------------------------------------------------------
function llenar_filtros_admin(nombre_div, nombre_select, nombre_codigo, nombre_valor, ruta, codigo_filtro){
    arr_mun = [];
    $.ajax({
        url: ruta,
        type:"POST",  
        data: {codigo: codigo_filtro, 
            pais: arrg_Credls['pais'],
            privilegio: arrg_Credls['privilegio'],
            division: arrg_Credls['id_division'],
            distribuidora: arrg_Credls['id_distribuidora'],
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
        arr_mun = [];
    }).fail(function() {
        console.log('Error al cargar los datos');
    });
}
function V_Selec(data,campo,ordencampo,etiqueta){
    var v = 0;
    if(_empty(data)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        $("#"+ordencampo).html('El campo de selecci&oacute;n <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        v = 1;
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        $("#"+ordencampo).html();
    }
    return v;
}
function get_fecha_hora(){
    var fecha_hora = '';
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
    hora = String(hora) + String(minutos) + String(segundos);
    fecha_hora = String(hoy.getFullYear()) +'_'+ String(mes) +'_'+ String(dia)+'_'+String(hora);   
    
    return fecha_hora;
}
function DB_iniciar_reclamos_admin() {
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
                    DB_CargaCredenciales(); 
                    // llenar_filtros_admin('fil_pais', 'slc_pais',  'P_Id', 'P_nombre','data_filtro_pais', '');     
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
        var OBJ_tblusuarios = active.createObjectStore("tbl_usuarios", {keyPath: 'idusuario', autoIncrement: true});
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
        var OBJ_tbl_reclamosingre = active.createObjectStore("tbl_reclamosingre", {keyPath: 'Id', autoIncrement: true});
        var OBJ_tbl_reclamosTemp = active.createObjectStore("tbl_reclamosTemp", {keyPath: 'Id', autoIncrement: true});
        var OBJ_tbl_tipo_danos = active.createObjectStore("tbl_tipo_danos", {keyPath: 'Tipd_Id'});
        OBJ_tbl_tipo_danos.createIndex('by_Trec_Id', 'Trec_Id', { unique: false });
        /*DBA CAMBIOS 26/06/2021*/
        var OBJ_clientes_DBA = active.createObjectStore("tbl_cliente_DBA", {keyPath: 'Id_Cliente'});
        OBJ_clientes_DBA.createIndex('by_estado_w', 'estado_w', {unique: false});
        /*DBA CAMBIOS 07/07/2021*/
        var OBJ_tblstatusex = active.createObjectStore("tbl_status_exhibidores", { keyPath: 'Ste_token', autoIncrement: false });
        OBJ_tblstatusex.createIndex('by_Ste_Cli_Id', 'Ste_Cli_Id', { unique: false });
        OBJ_tblstatusex.createIndex('by_Ste_cola', 'Ste_cola', { unique: false });
        var OBJ_tbltipoexh = active.createObjectStore("tbl_tipo_exhibidores", { keyPath: 'idx', autoIncrement: true });
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
function iniciar_admin_reclamos(arrg_datos, arrg_items) {
    Promise.all([
        DB_iniciar_reclamos_admin()
    ])
    .then(respuestas => {
        llenar_filtros_admin('fil_pais', 'slc_pais',  'P_Id', 'P_nombre','data_filtro_pais', ''); 
    })
    .catch(error => {
        console.log(error);
    });
}
function exportar_ls_reclamos(){
    fecha_inicial = $('#txtFechaInicial').val();
    fecha_limite = $('#txtFechaLim').val();
    var estado = $('#slc_estado').select2('val');
    $.ajax({
        url:'C_reclamos/Ctr_ingreso_reclamos/get_all_reclamos_xlsx_1',
        type:"POST",  
        data: { 
            codigo_pais: codigo_pais,
            codigo_division: codigo_division,
            codigo_dist: codigo_dist,
            codigo_ca: codigo_ca,
            codigo_grupo: codigo_grupo,
            codigo_ruta: codigo_ruta,
            fecha_inicial: fecha_inicial,
            fecha_limite: fecha_limite,
            estado: estado
        },
        dataType: "JSON",
        timeout  : 27777
    }).done(function(resp_data) {     
        if(resp_data.rs){
            if(resp_data.cla != 'vacio'){
                var url_archivo = '../'+resp_data.archivo;
                var $a = $("<a>");
                var nombre_archivo = url_archivo.replace("../../Uploads/Plantilla_Excel/", "");
                $a.attr("href", url_archivo);
                $("body").append($a);
                $a.attr("download", nombre_archivo);
                $a[0].click();
                $a.remove();
            }else{
                // console.log(resp_data);   
                Swal.fire({
                    title: 'Info!',
                    type: 'info',
                    html:'<h6>'+resp_data.info+'...</h6>',
                    confirmButtonText:'Ok'
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
        console.log('ERROR');
    });
}
function DB_limpiar_tablas_() {
    var active = dataBaseAppSDV.result;
    var data = active.transaction('tbl_usuarios', "readwrite");
    var objectStore = data.objectStore('tbl_usuarios');
    var objectStoreRequest = objectStore.clear();
    objectStoreRequest.onsuccess = function(event) {
        console.log('Datos reiniciados');
    };
}
function exportar_detalle_reclamo(){
    fecha_inicial = $('#txtFechaInicial').val();
    fecha_limite = $('#txtFechaLim').val();
    var estado = $('#slc_estado').select2('val');
    $.ajax({
        url:'C_reclamos/Ctr_ingreso_reclamos/exportar_registro_reclamo',
        type:"POST",  
        data: { 
            codigo_rec: codigo_rec
        },
        dataType: "JSON",
        timeout  : 27777
    }).done(function(resp_data) {     
        if(resp_data){
            var url_archivo = '../'+resp_data.archivo;

            var $a = $("<a>");
            var nombre_archivo = url_archivo.replace("../../Uploads/Plantilla_Excel/", "");
            $a.attr("href", url_archivo);
            $("body").append($a);
            $a.attr("download", nombre_archivo);
            $a[0].click();
            $a.remove();
        }else{
            Swal.fire({
                title: 'Aviso!',
                type: 'error',
                html:'<h6>'+resp_data.info+'</h6>',
                confirmButtonText:'Ok'
            });
        }       
    }).fail(function() {
        console.log('ERROR');
    });
}
function cambiar_estado(estado) {
    if(estado == 2){
        mensaje = '¿Está seguro de rechazar este reclamo de calidad?';
        mensaje_respuesta = 'El reclamo se rechazó con éxito!';
    }else if(estado == 3){
        mensaje = '¿Desea marcar este reclamo como REVISADO?';
        mensaje_respuesta = 'Reclamo marcado con éxito';
    }else if(estado == 4){
        mensaje = '¿Desea marcar este reclamo como FINALIZADO?';
        mensaje_respuesta = 'Reclamo finalizado con éxito';
    }

    Swal.fire({
        title: mensaje,
        text: "",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No, cancelar',
        allowOutsideClick: false
    }).then((result) => {
        if(result.value){
            $.ajax({
                url:'C_reclamos/Ctr_ingreso_reclamos/rechazar_reclamo',
                type:"POST",  
                data: { codigo_reclamo: codigo_rec, estado : estado },
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
                        title: mensaje_respuesta,
                        showConfirmButton: true,
                        allowOutsideClick: false
                    }).then((result) => {
                        document.getElementById("frm_detalle_rec").reset();
                        $('#modal_detalle_rec').modal("toggle");
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
            arrg_Credls['id_division'] = elements[0].id_division;
            arrg_Credls['ltdistr'] = elements[0].ltdistr;
            arrg_Credls['RegexTelefono'] = elements[0].RegexTelefono;
            arrg_Credls['CantidTelefono'] = elements[0].CantidTelefono;
            arrg_Credls['FormatoTelefono'] = elements[0].FormatoTelefono;
            $('#txtnumtelefono').mask(elements[0].FormatoTelefono, {placeholder: elements[0].FormatoTelefono});
            arrg_Credls['RegexNumIP'] = elements[0].RegexNumIP;
            arrg_Credls['CantidNumIP'] = elements[0].CantidNumIP;
            arrg_Credls['FormatoNumIP'] = elements[0].FormatoNumIP;
            $('#txtdui').mask(elements[0].FormatoNumIP, {placeholder: elements[0].FormatoNumIP});
            arrg_Credls['NombreDocumentoDUI'] = elements[0].NombreDocumentoDUI;
            arrg_Credls['RegexNumNIT'] = elements[0].RegexNumNIT;
            arrg_Credls['CantidNumNIT'] = elements[0].CantidNumNIT;
            arrg_Credls['FormatoNumNIT'] = elements[0].FormatoNumNIT;
            $('#txtnit').mask(elements[0].FormatoNumNIT, {placeholder: elements[0].FormatoNumNIT});
            arrg_Credls['NombreDocumentoNIT'] = elements[0].NombreDocumentoNIT;
            $("#if-tfactura #docidentidad").html('<span class="fa fa-id-card fa-lg"></span> '+elements[0].NombreDocumentoDUI+':');
            $("#if-tfactura #idtributaria").html('<span class="fa fa-id-card-alt fa-lg"></span> '+elements[0].NombreDocumentoNIT+':');
            arrg_Credls['TipoUsuario'] = elements[0].TipoUsuario;
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
            }
            console.log();
            console.log();
            resolve(1);
        };
        data.onerror = function () {
            reject(0);
        };
    });
}


