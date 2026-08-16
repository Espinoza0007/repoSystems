var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
var warn_on_unload = '';
var map;
var marker;
var blockFCli=0;
var blockF=0;
var coordenadas_Tempo = new Array();
var arrg_vali_result = new Array();
var datas = '';
var Id_Cliente = '';
var Id_Catalogo_Producto = '';
var nombre_ruta = ''; 
var nombre_division = '';
var EstadoCoordenadas = 0;
var usuarioCodigo = '';
var tipoUsuario = '';
var canal = '';
var str_codigo_token = [];
var tipo_reclamo = '';
var arrgColaReclamos = [];
var CantCola = 0;
var data_ingre = [];
var ls_rec_enproceso = [];
var bandTipoReclamo = '';
window.onbeforeunload = function() {
    if(warn_on_unload != ''){
        return warn_on_unload;
    }
}
var numeroLote = '';
var arrg_fotos = [];
arrg_fotos[1] = null;
arrg_fotos[2] = null;
arrg_fotos[3] = null;
arrg_fotos[4] = null;
$( document ).ready(function() {
    $('#bitacora_reclamos').show();
	DB_iniciar_reclamos(1);
    
    $('#txtFechaVencimiento').datepicker({
        format: 'yyyy-mm-dd'
    });

    $('#txtHora').timepicker({
        format: 'HH:MM'
    });

    $(document).on('keyup','#txtNumMaquina', function(e) {
        var $this = $(this);
        if ( /^[a-zA-Z0-9-.]{1,6}$/.test(this.value)) {
            $this.removeClass('is-invalid'); $this.addClass('is-valid');
        } else {
            $this.removeClass('is-valid');
            $this.addClass('is-invalid');
            e.preventDefault();
        }
    });
    
    $(document).on('keyup','#txtUS', function(e) {
        var $this = $(this);
        if($(this).val() != ''){
            if ( /^[0-9]{2}$/i.test(this.value) && this.value <= 52) {
                $this.removeClass('is-invalid'); $this.addClass('is-valid');
            } else {
                $this.removeClass('is-valid');
                $this.addClass('is-invalid');
                e.preventDefault();
            }
        }
    });

    $(document).on('keyup','#txtR', function(e) {
        var $this = $(this);
        if($(this).val() != ''){
            if ( /^[0-9]{2}$/i.test(this.value) && this.value <= 52) {
                $this.removeClass('is-invalid'); $this.addClass('is-valid');
            } else {
                $this.removeClass('is-valid');
                $this.addClass('is-invalid');
                e.preventDefault();
            }
        }
    });

    $(document).on('change','#txtHora', function(e) {
        var $this = $(this);
        if ( /^[0-9]{1,2}[:][0-9]{2}$/i.test(this.value)) {
            $this.removeClass('is-invalid'); $this.addClass('is-valid');
        } else {
            $this.removeClass('is-valid');
            $this.addClass('is-invalid');
            e.preventDefault();
        }
    });

    $(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";
        $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
                $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            });
        });
    });

    $('#txtNombre').on('change',function(){
        $('#txtNombre').removeClass('error');
        $('#txtNombre-error').html('');
    });

    $('#clientesDtable tbody').on( 'click', 'tr', function () {    
        if(opcion_catalogo == 'rec'){
            $("#bitacora_reclamos").hide(); 
            $("#accordion").hide(); 
            if(blockF==0){
                blockF = 1000;
                $("#txtNombre").val(table.row( this ).data().Cli_nombre);
                $("#txtCodigo").val(table.row( this ).data().Cli_codigo);
                $("#txtIdCliente").val(table.row( this ).data().Cli_Id);
                $("#txtRuta").val(table.row( this ).data().Ru_nombre); 
                Id_Cliente = table.row( this ).data().Cli_Id;     
                nombre_ruta = table.row( this ).data().Ru_nombre;   
                nombre_division = table.row( this ).data().Di_nombre; 
                str_codigo_token = codigo_token_reclamo();
            }
        }else{
            id_cliente_cti = table.row( this ).data().Cli_Id;
            nombre_cliente_cti = table.row( this ).data().Cli_Id +' - ' +table.row( this ).data().Cli_nombre;
            $('#txtclientesinventario').val(table.row( this ).data().Cli_Id +' - ' +table.row( this ).data().Cli_nombre);
            $('#txtIdClienteCti').val(id_cliente_cti);
        }    
            getInfoCli();
    });
    
    $('#catalogoDtable tbody').on( 'click', 'tr', function () {      
        $("#bitacora_reclamos").hide(); 
        $("#accordion").hide(); 
        if(blockFCli==0){
            id_reclamo = '';
            var estado_P = '';
            blockFCli=1000;    
            var select_um = [];
            var select_um1 = [];
            var select_um_html = '';
            var tipoReclamo = '1';
            bandTipoReclamo = '';
            $(this).addClass("SeletedTRSN");
            $("#txtCodigoProducto").val(table.row( this ).data().Cat_codigo_SKU);
            $("#txtProducto").val(table.row( this ).data().Cat_descripcion);
            $("#txtFamilia").val(table.row( this ).data().Fa_nombre);
            $("#txtSubFamiliaP").val(table.row( this ).data().Subf_nombre);
            $("#txtEsdatoP").val(table.row( this ).data().Cat_descripcion);
            descripcion_producto = table.row( this ).data().Cat_descripcion;
            Id_Catalogo_Producto = table.row( this ).data().Cat_Id;
            estado_P = table.row( this ).data().Cat_estado;
            // ---------------------------------------------------------------------------------------------------------
                /*if(table.row( this ).data().Um_nombre != 'UN'){
                    select_um[0] = table.row( this ).data().Um_nombre;
                    select_um[1] = 'UN';
                }else{
                    select_um[0] = table.row( this ).data().Um_nombre;
                }
                select_um_html += '<select class="custom-select" id="select_unidad_medida" name="select_unidad_medida">' +
                '<option value="">SELECCIONE</option>';           

                select_um.forEach( function(valor, indice, array) {
                    select_um_html+='<option value="'+ valor + '">' + valor + '</option>';
                });

                select_um_html+='</select>';*/
            // ---------------------------------------------------------------------------------------------------------
            if(table.row( this ).data().Fa_nombre == 'EXHIBIDOR'){
                tipoReclamo = '2';
                $("#div_bocadeli").hide();
                $("#div_exhibidores").show();
                bandTipoReclamo = 'EXHIBIDOR';
            }else{
                tipoReclamo = '1';
                $("#div_exhibidores").hide();
                $("#div_bocadeli").show();
                bandTipoReclamo = 'BOCADELI';
            }
            DB_CargarSelectTipoDanos('tbl_tipo_danos','select_tipo_reclamo','slc_tipo_rec',tipoReclamo);
            $("#sunidad_medida").empty().html(select_um_html);
            $("#select_unidad_medida").val(table.row( this ).data().Cat_unidad_medida);
            getInfoSN();
        }
    });
    
    $(document).on("change", "#select_tipo_reclamo", function() {    
        tipo_reclamo = $(this).val();
        if (tipo_reclamo == 13) {
            $('#txtFechaVencimiento').addClass('ignore');
            $('#txtNumeroLote').addClass('ignore');
            $('#div_fecha_ven').hide();
            $('#title_N_lote').hide();
            validator.resetForm();
        }else{
            $('#txtFechaVencimiento').removeClass('ignore');
            $('#txtNumeroLote').removeClass('ignore');
            $('#div_fecha_ven').show();
            $('#title_N_lote').show();
            validator.resetForm();
        }
    });
    
    $(document).on("click", "#btn_enviar", function() {    
        $("#btn_enviar").prop('disabled', true);
        // $("input").prop('disabled', false);
    });

    // ------- VALIDACION FORMULARIO ---------------------------------------------------------------------
    $('#txtFechaVencimiento').mask('0000-00-00', {placeholder: 'yyyy-mm-dd'});
    
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Por favor introducir formato correcto"
    );
    var validator = $('form[id="form_reclamo"]').validate({
        ignore: ".ignore" ,
        rules: {
            txtNombre: 'required'  ,
            txtFamilia: 'required',
            txtProducto: 'required',
            fileProducto: {
                required: function(element) {
                    return bandTipoReclamo === 'BOCADELI';
                }
            },
            fileFechaLote: {
                required: function(element) {
                    return bandTipoReclamo === 'BOCADELI';
                }
            },
            txtFechaVencimiento: {
                required: function(element) {
                    return bandTipoReclamo === 'BOCADELI';
                }
            },
            txtNumeroLote: {
                required: function(element) {
                    return bandTipoReclamo === 'BOCADELI';
                },
                regex: '^[a-zA-Z0-9-:. ]{1,25}$',
                maxlength: 25,
                minlength: 10
            },
            txtCantidad: {
                required: function(element) {
                    return bandTipoReclamo === 'BOCADELI';
                },
                digits: true,
                min: 1,
                regex: '^[0-9]{1,3}$'
            },
            txtUnidadesDanadas: {
                required: function(element) {
                    return bandTipoReclamo === 'BOCADELI';
                },
                digits: true,
                min: 1,
                regex: '^[0-9]{1,5}$'
            },
            select_unidad_medida: {
                required: function(element) {
                    return bandTipoReclamo === 'BOCADELI';
                }
            },  
            select_tipo_reclamo: {
                required: true
            },
            select_proveedores: {
                required: function(element) {
                    return bandTipoReclamo === 'EXHIBIDOR';
                }
            },
            fileSticker: {
                required: function(element) {
                    return bandTipoReclamo === 'EXHIBIDOR';
                }
            },
            fileDano: {
                required: function(element) {
                    return bandTipoReclamo === 'EXHIBIDOR';
                }
            },
        },
        onkeyup: function(element, event) {
            $(element).valid();
        },
        onChange: function(element, event) {
            $(element).valid();
        },
        messages: {
            txtNombre: 'El nombre del cliente es requerido',
            txtFamilia: 'La famila de producto es requerida',
            txtProducto: 'Seleccione un producto de la tabla',
            fileProducto: 'Por favor tomar una fotografía',
            fileFechaLote: 'Por favor tomar una fotografía',
            txtFechaVencimiento: 'Por favor seleccionar una fecha de vencimiento',
            txtNumeroLote: {
                required: 'Por favor ingrese el número de lote del producto',
                maxlength: 'Número demasiado largo',
                regex: 'Formato no admitido',
                minlength: 'Número demasiado corto'
            },
            txtCantidad: {
                required: 'Por favor indique la cantidad',
                digits: 'Ingrese solo numeros enteros por favor',
                min: 'El valor mínimo es 1',
            },
            txtUnidadesDanadas: {
                required: 'Por favor indique la cantidad',
                digits: 'Ingrese solo numeros enteros por favor',
                min: 'El valor mínimo es 1',
            },
            select_unidad_medida: {
                required: 'Por favor seleccione una unidad de medida'
            },  
            select_tipo_reclamo: {
                required: "Por favor seleccione un tipo de reclamo"
            },
            select_proveedores: {
                required: "Por favor seleccione un proveedor"
            },
            fileSticker: 'Por favor tomar una fotografía',
            fileDano: 'Por favor tomar una fotografía'
        },
        submitHandler: function(form) {
            //alert('*Se manda*');
            enviar_registro_reclamo();
        }
    });
    // ---------------------------------------------------------------------------------------------------

});
// ------- FINAL DOCUMENT.READY --------------------------------------------------------------------------

function getInfoSN(){
    $.when( $('#InfoCuadro').stop(true,true).hide() ).done(function( x ) {
        $.when( $('#form_actuinfo').stop(true,true).show() ).done(function( x ) {
            $("#modalCatalogo").modal("toggle");
            $("#showDataSN").empty();
            $('#catalogoDtable').DataTable().destroy();
            $('#btn-enviar').show();
            blockFCli=0;
        });
    });
}

function enviar_registro_reclamo(){
    if(_empty(Id_Cliente)){
        Swal.fire({
            title: '<strong>Aviso!</strong>',
            type: 'info',
            html:'<strong>Selecciona un cliente por favor</strong>',
            confirmButtonText:'Ok'
        });
    }else{  
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {      
            guardar_registro_reclamo();
        });
    }
}

function agregar_us_offline(conect){
    var TotalRegisCola = 0;
    TotalRegisCola = $("#RegisCola").text();
    arrgColaReclamos = [];
    if(TotalRegisCola>0){
        Swal.fire({
            title: 'Deseas enviar los registros en cola?',
            text: "",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, enviar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            Promise.all([   
                consultar_cola_reclamos()
            ])
            .then(respuestas => {
                $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        envio_cola_reclamos(0, arrgColaReclamos);
                    });
                });  
            })
            .catch(error => {
                $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                    console.log('Ocurrio un error');
                });
            });
        });
    }else{
        Swal.fire({
            type: 'info',
            title: 'No tienes registros en cola!',
            showConfirmButton: false,
            timer: 1500
        });
    }
}

///-------GENERAR CODIGO Y TOKEN PARA EL RECLAMO DE PRODUCTOS ---------------------------------------------
function codigo_token_reclamo(){
    
    var hoy = new Date();
    var mes = (hoy.getMonth() + 1);
    var dia = hoy.getDate();
    var hora = hoy.getHours();
    var minutos = hoy.getMinutes();
    var segundos = hoy.getSeconds();
    if((hora >=0) && (hora<10)){
        hora = '0' + String(hora);
    }
    if ((minutos >= 0) && (minutos < 10)) {
        minutos = '0' + String(minutos);
    }
    if ((segundos >= 0) && (segundos < 10)) {
        segundos = '0' + String(segundos);
    }
    var fecha = String(hoy.getFullYear()).substr(-2) + String(mes) + String(dia);
    var hora = String(hora) + String(minutos) + String(segundos);

    str_codigo_token[0] =  nombre_division + nombre_ruta.split('.').join('') + 
                            String(fecha) + String(hora) + Id_Cliente;
    
    str_codigo_token[1] =  nombre_division + nombre_ruta.split('.').join('') + 
                            String(fecha) + String(hora) + Id_Cliente + String(arrg_Credls['usuario']);
    return str_codigo_token;
}

function V_Selec(data,campo,ordencampo,etiqueta){
    var v = 0;
    if(_empty(data)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo de selecci&oacute;n <strong>'+etiqueta+'</strong> es obligatorio.';
    }else{
        v = 1;
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';
    }
    return v;
}
///----------AGREGAR MAS PRODUCTOS AL RECLAMO -------------------------------------------------------------

function limpiar_formulario_reclamo(){
    $.when( $('#form_actuinfo').stop(true,true).hide() ).done(function( x ) {
        $.when( $('#InfoCuadro').stop(true,true).show() ).done(function( x ) {
            document.getElementById("form_reclamo").reset();
            Id_Cliente = '';
            Id_Catalogo_Producto = '';
            arrg_fotos[1] = null;
            arrg_fotos[2] = null;
            arrg_fotos[3] = null;
            arrg_fotos[4] = null;
            $('#btn-enviar').hide();
            $('#txtNumMaquina').removeClass('is-valid');
            $('#txtUS').removeClass('is-valid');
            $('#txtR').removeClass('is-valid');
            $('#txtHora').removeClass('is-valid');
            $('#btn-formopciones1').attr("disabled", false); 
            $(".imagen").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
            str_codigo_token = [];
            // get_reclamos_enviados();
            mostrar_lista();
            $('#accordion').show();
        });
    });
}

function agregar_producto_reclamo(){
    arrg_fotos[1] = null;
    arrg_fotos[2] = null;
    arrg_fotos[3] = null;
    arrg_fotos[4] = null;
    $('#txtCantidad').val('');
    $('#txtCodigoProducto').val('');
    $('#txtProducto').val('');
    $('#txtFamilia').val('');
    $('#select_tipo_reclamo').val('');
    $('#select_proveedores').val('');
    $('#txtFechaVencimiento').val('');
    $('#select_unidad_medida').val(0);
    $('#txtNumeroLote').val('');
    $('#txtUnidadesDanadas').val('');
    $('#txtNumeroLote').removeClass('is-valid');
    $('#btn-formopciones1').attr("disabled", true);    
    $('#fileFechaLote').val('');
    $('#fileProducto').val('');
    $(".imagen").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
    $('#btn-enviar').show();
    $('html, body').animate({
        scrollTop: 0
    }, 2000);
    $('#txtFechaVencimiento').removeClass('ignore');
    $('#txtNumeroLote').removeClass('ignore');
}

// ---- PROCESAR EL RECLAMO --------------------------------------------------------------------------

function guardar_registro_reclamo() {   
    data_ingre = [];
    str_codigo_token = codigo_token_reclamo();
    var fecha_telefono = fechaDispositivo();
    usuarioCodigo = arrg_Credls['us_cod'];
    tipoUsuario = arrg_Credls['privilegio'];
    numeroLote = $('#txtNumeroLote').val();
    var fecha_vencimiento1 = $('#txtFechaVencimiento').val();
    var cantidad = bandTipoReclamo == 'EXHIBIDOR' ? 1: $('#txtCantidad').val();
    var UDanadas = bandTipoReclamo == 'EXHIBIDOR' ? 1: $('#txtUnidadesDanadas').val();
    data_ingre.push({
        Id_Catalogo_Producto: Id_Catalogo_Producto,
        Id_Cliente: Id_Cliente,
        codigo_cliente: $('#txtCodigo').val(),
        cantidad: cantidad,
        codigo_reclamo: str_codigo_token[0],
        descripcion_producto: descripcion_producto,
        estado: "1",
        fecha_telefono: fecha_telefono,
        fecha_vencimiento: fecha_vencimiento1,
        fileFechaLote: arrg_fotos[1],
        fileProducto: arrg_fotos[2],
        nombre_cliente: $('#txtNombre').val(),
        nombre_ruta:  nombre_ruta,
        numeroLote: numeroLote, 
        pendiente: '',
        tipo_reclamo: $('#select_tipo_reclamo').val(),
        tipo_reclamo_descripcion: $('select[id="select_tipo_reclamo"] option:selected').text(),
        tipo_usuario: tipoUsuario,
        token_reclamo: str_codigo_token[1],
        unidades_danadas: UDanadas,
        usuario: usuarioCodigo,
        proveedor: $('#select_proveedores').val(),
        fileSticker: arrg_fotos[3],
        fileDano: arrg_fotos[4],
    });

    // $.when($("#content_carga").stop(true, true).show()).done(function(x) {
        $.ajax({
            url: 'C_reclamos/Ctr_ingreso_reclamos/guardar_reclamo_nuevo',
            type: 'POST',
            dataType: 'JSON',
            data: {datas: data_ingre[0]},
            timeout: 7777
        }).done(function(_resp) {
            if (_resp.rs == true) {
            }
        }).always(function(_resp, textStatus, errorThrown) {
            if (textStatus == "success") {
                if (_resp.rs == true) {       
                    // $.when($(".carga-class").stop(true, true).hide()).done(function(x) {             
                        guardar_reclamo_local('NO',data_ingre);   
                    // });
                } else {
                    $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                    // $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                        console.log('ERROR CAMPOS');
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html: _resp.errores,
                            confirmButtonText: 'Ok'
                        });

                    });
                }
            } else {
                $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                // $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                    _ajax_error_modulos_menu(_resp.status, _resp.readyState, _resp.statusText,'reclamo', data_ingre);
                });
            }
        });
    // });
}
// ---------------------------------------------------------------------------------------------------

// ----- GUARDAR REGISTROS EN LOCAL ------------------------------------------------------------------
function guardar_reclamo_local(pendienteX, data_ingre) {
    var mensaje = '';
    var request = '';
    var active = dataBaseAppSDV.result;
    var data = active.transaction(["tbl_reclamosingre"], "readwrite");
    var object = data.objectStore("tbl_reclamosingre");
    data_ingre[0].pendiente = pendienteX;
    request = object.put(data_ingre[0]);
    request.onerror = function(e) {
    };
    data.oncomplete = function(e) {
        Promise.all([                
            consultar_cola_reclamos()
        ])
        .then(respuestas => {
            $.when($(".carga-class").stop(true, true).hide()).done(function(x) {
                if(pendienteX == 'SI')
                    mensaje = 'Registro guardado temporalmente!';
                else
                    mensaje = 'Reclamo guardado correctamente';
                Swal.fire({
                    type: 'success',
                    title: mensaje,
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
                            mostrar_lista();
                        }
                    });
                    //-------------- FIN AGREGAR OTRO PRODUCTO AL RECLAMO ------------------
                });
            });
        })
        .catch(error => {
            $.when($("#formularioinicio").stop(true, true).hide(20)).done(function(x) {
                $.when($("#formulariofin").stop(true, true).show(20)).done(function(x) {
                    console.log(error);
                });
            });
        });
    };
}
// ---------------------------------------------------------------------------------------------------

// ----- OBTENER RECLAMOS EN COLA --------------------------------------------------------------------
function consultar_cola_reclamos(){
    arrgColaReclamos = [];
    CantCola = 0;
    var conta_cola_soli = {};
    return new Promise(function(resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_reclamosingre', 'readonly'),
        store = transaccion.objectStore('tbl_reclamosingre'),
        indice = store.index('by_cola'),
        cursor = indice.openCursor('SI')
        cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                arrgColaReclamos = dataResult;
                CantCola = parseInt(Object.keys(dataResult).length);
                $("#RegisCola").html('');
                $("#RegisCola").html(CantCola);
                // console.log(arrgColaReclamos);
                resolve(1);
            };
        }
        transaccion.onerror = function() {
            reject(0);
        };
    });
}
// ---------------------------------------------------------------------------------------------------

// ----- OBTENER RECLAMOS EN PROCESO -----------------------------------------------------------------
function get_reclamos_enviados(){
    ls_rec_enproceso = [];
    var ls_rec_conta = {}
    return new Promise(function(resolve, reject) {
        var dataResult = [];
        var active = dataBaseAppSDV.result;
        let transaccion = active.transaction('tbl_reclamosingre', 'readonly'),
        store = transaccion.objectStore('tbl_reclamosingre'),
        indice = store.index('by_estado'),
        cursor = indice.openCursor('1')
        cursor.onsuccess = function(event) {
            let dat = event.target.result;
            if (dat) {
                dataResult.push(dat.value);
                dat.continue();
            } else {
                ls_rec_enproceso = dataResult;
                resolve(1);
            };
        }
        transaccion.oncomplete = function(event) {
        }

        transaccion.onerror = function() {
            reject(0);
        };
    });
}

function mostrar_lista(){
    Promise.all([
        get_reclamos_enviados()
    ])
    .then(respuestas =>{
        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
            $.when( $("#InfoCuadroRec").stop(true,true).hide() ).done(function( x ) {
                var html_str = `<div class="row justify-content-center"><h6><i class="far fa-list-alt fa-lg"></i> RECLAMOS EN PROCESO</h6></div>`;

                ls_rec_enproceso.forEach(function(filall, index, arrgfilall) {
                html_str += `   
                    <div class="card" style="margin-top: 0px; width: 100%;">
                    <button class="btn btn-dark" data-toggle="collapse" data-target="#prb_${filall.codigo_reclamo}" aria-controls="prb_${filall.codigo_reclamo}">
                        <i class="far fa-eye fa-lg"></i> ${filall.codigo_reclamo}
                    </button>
                    <div id="prb_${filall.codigo_reclamo}" class="collapse" data-parent="#accordion_ls_rec">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="titulo"><span class=""></span> Fecha reclamo:</div>
                                    <input type="text" class="form-control form-control-sm" value="${filall.fecha_telefono}" readonly>
                                </div>
                            </div>                                   
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="titulo"><span class=""></span>Daño:</div>
                                    <textarea name="" id="" cols="30" class="form-control form-control-sm" readonly>${filall.tipo_reclamo_descripcion}</textarea>
                                </div>
                            </div>                                    
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="titulo"><span class=""></span>Producto:</div>
                                    <textarea name="" id="" cols="30" class="form-control form-control-sm" readonly>${filall.Id_Catalogo_Producto} - ${filall.descripcion_producto}</textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="titulo"><span class=""></span>Cliente:</div>
                                    <!-- <input type="text" class="form-control" value="${filall.nombre_cliente}" readonly> -->
                                    <textarea name="" id="" cols="30" class="form-control form-control-sm" readonly value="${filall.nombre_cliente}">${filall.codigo_cliente} - ${filall.nombre_cliente}</textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <div class="titulo">UN dañadas:</div>
                                    <input type="text" class="form-control form-control-sm" value="${filall.unidades_danadas}" readonly>
                                </div>
                                <div class="form-group col-6">
                                    <div class="titulo">Cantidad:</div>
                                    <input type="text" class="form-control form-control-sm" value="${filall.cantidad}" readonly>
                                </div>
                            </div>
                        </div>
                    </div></div>`;
                });
                $("#accordion_ls_rec").empty().html(html_str)
                $('#bitacora_reclamos').show();
            });
        });
    }).catch(error =>{
        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
            console.log(error);
        });
    });
}

// ---------------------------------------------------------------------------------------------------
function envio_cola_reclamos(indice, elements) {
    if (indice < elements.length) {
        $.ajax({
            url: 'C_reclamos/Ctr_ingreso_reclamos/guardar_reclamo_nuevo',
            type: "POST",
            data: {datas: elements[indice]},
            dataType: "JSON",
            timeout: 14777
        }).done(function(_resp) {
        }).always(function(_resp, textStatus, errorThrown) {
            if (textStatus == "success") {
                if (_resp.rs == true) {
                    var actived = dataBaseAppSDV.result;
                    var objectStore = actived.transaction(["tbl_reclamosingre"], "readwrite").objectStore("tbl_reclamosingre");
                    var request = objectStore.get(elements[indice].codigo_reclamo);
                    request.onerror = function(event) {
                    };
                    request.onsuccess = function(event) {
                        var data = request.result;
                        if (elements[indice].pendiente === 'SI') {
                            data.pendiente = 'NO';
                        }  
                        var requestUpdate = objectStore.put(data);
                            requestUpdate.onerror = function(event) {
                        };
                        requestUpdate.onsuccess = function(event) {
                            alertify.success('Registro enviado exitosamente!');
                            envio_cola_reclamos(indice + 1, elements);
                            consultar_cola_reclamos()
                        };
                    };
                } else {
                    $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                        Swal.fire({
                            title: 'Aviso!',
                            type: 'error',
                            html: _resp.errores,
                            confirmButtonText: 'Ok'
                        });
                    });
                }
            } else {
                $.when($("#content_carga").stop(true, true).hide()).done(function(x) {
                    Swal.fire({
                        title: 'Aviso!',
                        type: 'error',
                        html:'<strong>Error de envio de cola de registros...</strong>',
                        confirmButtonText: 'Ok'
                    });
                });
            }
        });
    }
}