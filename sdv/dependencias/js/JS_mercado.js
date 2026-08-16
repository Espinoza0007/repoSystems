var table = null;
// Variable para almacenar el temporizador
var timer;
var arrg_Credls = [];
var us;
var todasLasRutas = []; // Almacenará todas las rutas una vez extraídas
var rutasList = [];
var rut ;

$(document).ready(function () {
    window.onload = initMap;
    cargar_localizacion();
    extraerPrimerRutas();
    ruta();
    cola();
    ocultarElementosSegunPrivilegio()
    // initMap();
    Promise.all([DB_IniciarCPSesion(1)]).then(function (respuestas) {
        cargar_credenciales_sdv().then(function (result) { // Realiza las operaciones necesarias con arrg_Credls dentro de este bloque
            $.ajax({
                url: 'C_mercado/Ctr_mercado/get_tareas',
                method: 'GET',
                dataType: 'json',
                data: {
                    usuario: arrg_Credls['nombre_us']
                },
                success: function (data) {
                    data.forEach(function (tarea) {
                        var estado;
                        var estadoClass;
                        var estadoColor;
                        if (tarea.estado == 0) {
                            estado = 'Pendiente';
                            estadoClass = 'estado-no-asignado';
                            estadoColor = 'text-warning';
                        } else if (tarea.estado == 1) {
                            estado = 'Activo';
                            estadoClass = 'estado-finalizado card-border-red';
                            estadoColor = 'text-danger';
                        } else if (tarea.estado == 2) {
                            estado = 'Finalizado';
                            estadoClass = 'estado-activo card-border-green';
                            estadoColor = 'text-success';
                        }
                        // Crea el div principal (tarjeta)
                        var $card = $('<div/>', {
                            class: "card " + estadoClass + " mb-3 mx-auto",
                            style: "width: 18rem;"
                        });
                        // Crea el cuerpo de la tarjeta
                        var $cardBody = $('<div/>', {class: "card-body"});
                        // Añade los elementos al cuerpo de la tarjeta
                        $cardBody.append($('<h5/>', {class: 'card-title'}).text(tarea.nombreEstablecimiento + ": " + tarea.codigoCliente));
                        $cardBody.append($('<h6/>', {class: 'card-subtitle mb-2 text-muted'}).text(tarea.fecha));
                        $cardBody.append($('<p/>', {
                            class: 'card-text ' + estadoColor
                        }).html('<strong>' + estado + '</strong>'));
                        $cardBody.append($('<p/>', {class: 'card-text'}).html('<strong>' + tarea.nombre_oportunidad + '</strong>' + " - " + tarea.oportunidad));
                        // Crea el div de opciones (acciones)
                        var $actions = $('<div/>', {class: 'actions d-flex justify-content-center align-items-center'});
                        // Agrega la clase 'align-items-center' para alinear verticalmente los ítems
                        // Verifica si el estado es 'Activo' antes de agregar el botón de completar tarea
                        if (estado === 'Activo') {
                            $actions.append($('<button/>', {
                                class: 'btn btn-success btn-completar-tarea',
                                'data-id': tarea.tarea_id, // Asegúrate de reemplazar 'id' con el campo correcto de tu objeto tarea
                                'data-tarea': tarea.nombreEstablecimiento + ": " + tarea.nombre_oportunidad
                            }).html('<i class="fas fa-check-circle"></i> Completar Tarea'));
                        }

                        // Añade las acciones al cuerpo de la tarjeta
                        $cardBody.append($actions);

                        // Añade el cuerpo de la tarjeta a la tarjeta principal
                        $card.append($cardBody);

                        // Añade la tarjeta a la lista (o a cualquier otro contenedor donde quieras que aparezcan estas tarjetas)
                        $('#contenedorDeTareas').append($card);
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        }).catch(function (error) {
            console.log(error);
        });
    }).catch(function (error) {
        console.log(error);
    });

    // Completar la tarea guardar
    // Completar la tarea guardar
    // Completar la tarea guardar
    $('#completarTarea').on('click', async function () {
        var taskId = $('#id_tarea_c').val();

        // Validaciones
        if (! validarCompleteTaskFormulario()) {
            return false;
        }

        // Convertir las imágenes a Base64
        // ... (tu código existente)

        var photo1 = document.getElementById('photo1').files[0];
        var photo2 = document.getElementById('photo2').files[0];
        var photo3 = document.getElementById('photo3').files[0];

        // Verificar que al menos una foto esté seleccionada
        if (! photo1 && ! photo2 && ! photo3) {
            Swal.fire({icon: 'error', title: 'Oops...', text: 'Por favor, selecciona al menos una foto.'});
            return;
        }

        let fotosBase64 = [];

        if (photo1) {
            const base64_1 = await reduceResolution(photo1); // Reemplazamos getBase64 por reduceResolution
            fotosBase64.push({name: "foto_u", value: base64_1});
        }

        if (photo2) {
            const base64_2 = await reduceResolution(photo2); // Reemplazamos getBase64 por reduceResolution
            fotosBase64.push({name: "foto_d", value: base64_2});
        }

        if (photo3) {
            const base64_3 = await reduceResolution(photo3); // Reemplazamos getBase64 por reduceResolution
            fotosBase64.push({name: "foto_t", value: base64_3});
        }

        // Construye el objeto para actualizar
        let dataToUpdate = {
            tarea_id: taskId,
            estado: 2
        };

        fotosBase64.forEach(foto => {
            dataToUpdate[foto.name] = foto.value;
        });


        // Intentar enviar al servidor
        // Muestra una notificación de "cargando" antes de enviar la solicitud AJAX
        Swal.fire({
            title: 'Cargando',
            text: 'Por favor, espere...',
            allowOutsideClick: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Intenta enviar la solicitud AJAX
        $.ajax({
            url: "C_mercado/Ctr_mercado/completeTask",
            type: "POST",
            data: dataToUpdate,
            success: function (response) { // Cierra la notificación de "cargando"
                Swal.close();

                if (response.success) {
                    Swal.fire({icon: 'success', title: '¡Tarea completada!', text: 'La tarea se ha actualizado correctamente en el servidor.', confirmButtonText: 'Aceptar'});
                    cola();
                } else {
                    Swal.fire({icon: 'warning', title: 'Oops...', text: 'La tarea se actualizó localmente, pero hubo un problema al actualizarla en el servidor.', confirmButtonText: 'Aceptar'});
                    cola();
                }
            },
            error: function () { // Cierra la notificación de "cargando"
                Swal.close();

                Swal.fire({icon: 'warning', title: 'Oops...', text: 'La tarea se actualizó localmente, pero hubo un problema al comunicarse con el servidor.', confirmButtonText: 'Aceptar'});
                cola();
            },
            complete: function () { // Actualiza la tarea en IndexedDB siempre
                actualizarTarea(dataToUpdate);
                $('#completeTaskModal').modal("toggle"); // Cierra el modal
            }
        });

    });
});
$(document).on('hidden.bs.modal', function (event) {
    if ($('.modal:visible').length) {
        $('body').addClass('modal-open');
    }
});
// buscar la ruta
// buscar la ruta
// buscar la ruta

$(document).ready(function () {
     rutasList = [];

    extraerPrimerRutas(function (data) {
        if (data) {
            rutasList = data;
            //console.log("Esta en el js de mercado linea 199: ",rutasList);
        }
    });

    $('#search-box5').on('input', function () {
        var inputValue = $(this).val().toLowerCase();
        var filteredRutas = rutasList.filter(function (ruta) {
            var regex = new RegExp(inputValue, 'i'); // 'i' indica que la búsqueda sea insensible a mayúsculas y minúsculas
            return regex.test(ruta.Ru_Id.toString()); // Asegúrate de convertir Ru_Id a una cadena antes de buscar
        });
    
        // Limitar el número de sugerencias a un máximo de 10
        filteredRutas = filteredRutas.slice(0, 10);
    
        var suggestionsHtml = '';
        for (var i = 0; i < filteredRutas.length; i++) {
            suggestionsHtml += '<div class="suggestion-item larger-font" style="font-size: 16px;">' + filteredRutas[i].Ru_Id + '</div>';
        }
    
        $('#suggesstion-box5').html(suggestionsHtml);
    });
    

    $('body').on('click', '.suggestion-item', function () {
        var selectedValue = $(this).text();
        $('#search-box5').val(selectedValue);
        $('#suggesstion-box5').html('');
    });
});


// fin de bucar la ruta


$(document).ready(function () {
    $("#nuevaEncuesta").on("click", function () { // Open the modal
        $("#encuestaModal").modal("show");
        cargar_datos();
    });

    $("#btnCerrarModal").on("click", function () { // Close the modal
        $("#encuestaModal").modal("hide");
    });

    $("#btnCerrar").on("click", function () { // Close the modal
        $("#encuestaModal").modal("hide");
    });

    // hide modal completar la tarea
    $("#btnCerrarC").on("click", function () {
        $("#completeTaskModal").modal("hide");
    });

    $("#btnCerrarCe").on("click", function () {
        $("#completeTaskModal").modal("hide");
    });

    // hide modal crear encuesta
    // hide modal de asignar
    $("#cerrarAsignar").on("click", function () {
        $("#assignModal").modal("hide");
    });
    // fin de hide asignar modal
    // Modal de las tareas
    $("#cerrarMTarea").on("click", function () {
        $("#tareasModal").modal("hide");
    });

    $("#CerrarTarea").on("click", function () {
        $("#tareasModal").modal("hide");
    });
    // fin

    // Modal de asignar tareas
    $("#btnAsignar").on("click", function () {
        $("#completarTareas").modal("show");
        cargar_tareas();
    });

    $("#btnTareas").on("click", function () {
        mostrarTareasEnModal()
    });
    // Guardar la encuesta//
    // Guardar la encuesta//
    // Guardar la encuesta//
    $("#btnGuardarEncuesta").on("click", async function () {
        try { // Validar el formulario primero
            if (! validarFormulario()) {
                return; // Si la validación falla, sale de la función de click.
            }

            // Procesa las imágenes
            var file_foto_u = document.getElementById('file_foto_u').files[0];
            var file_foto_d = document.getElementById('file_foto_d').files[0];
            var file_foto_t = document.getElementById('file_foto_t').files[0];

            // Verificar que al menos una foto esté seleccionada
            if (! file_foto_u && ! file_foto_d && ! file_foto_t) {
                Swal.fire({icon: 'error', title: 'Oops...', text: 'Por favor, selecciona al menos una foto.'});
                return;
            }

            let fotosBase64 = [];

            if (file_foto_u) {
                const base64_u = await reduceResolution(file_foto_u);
                fotosBase64.push({name: "foto_uno", value: base64_u});
            }

            if (file_foto_d) {
                const base64_d = await reduceResolution(file_foto_d);
                fotosBase64.push({name: "foto_dos", value: base64_d});
            }

            if (file_foto_t) {
                const base64_t = await reduceResolution(file_foto_t);
                fotosBase64.push({name: "foto_tres", value: base64_t});
            }

            const form = document.getElementById('myForm');
            const formData = new FormData(form);
            formData.append("hash", generateRandomId());
            formData.append("n_oportunidad", formData.get("listaOportunidades"));

            // Obtener datos de los usuarios
            let usuarios = await obtenerDatosUsuarios();
            const primerUsuario = usuarios[0];
            formData.append("id_creado", primerUsuario.us_cod);
            formData.append("asignado_a", rut);
            formData.append("codigoCliente", document.getElementById("search-box6").value);
            fotosBase64.forEach(foto => {
                formData.append(foto.name, foto.value);
            });

            const serializedData = {};
            for (const [key, value] of formData.entries()) {
                serializedData[key] = value;
            }

            // Muestra una notificación de "cargando" antes de enviar la solicitud AJAX
            Swal.fire({
                title: 'Cargando',
                text: 'Por favor, espere...',
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            // Intenta enviar el formulario al servidor
            $.ajax({
                url: 'C_mercado/Ctr_mercado/guardar_formulario',
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                timeout: 15000,
                dataType: 'JSON'
            }).done(function (response) { // Cierra la notificación de "cargando"
                Swal.close();

                if (response.success) {
                    Swal.fire('¡Éxito!', 'La encuesta se ha guardado exitosamente.', 'success');
                    serializedData['enviado'] = 'SI';
                } else {
                    Swal.fire('¡Atención!', 'La encuesta se ha guardado temporalmente debido a un problema en el servidor.', 'warning');
                    serializedData['enviado'] = 'NO';
                } guardarEnIndexedDB(serializedData);
                crearTarea(serializedData);
            }).always(function (response, textStatus, errorThrown) {
                if (textStatus == 'success') { // Aquí podrías manejar respuestas exitosas si lo necesitas
                } else {
                    Swal.fire('¡Atención!', 'Hubo un problema al conectarse con el servidor. La encuesta se ha guardado temporalmente.', 'warning');
                    serializedData['enviado'] = 'NO';
                    guardarEnIndexedDB(serializedData);
                    cola();
                    crearTarea(serializedData);
                }
            });


        } catch (error) {
            console.error("Ocurrió un error:", error);
            Swal.fire('¡Error!', 'Ocurrió un error al procesar el formulario. Por favor, inténtalo de nuevo.', 'error');
        }
    });

    // fin de guadar la encuesta
    // fin de guadar la encuesta
});
// -------- FINAL DOCUMENT.READY --------------------------------------------------------------------------
// inicio de compeltar la tarea
$(document).on('click', '.btn-completar-tarea', function () { // Extrae la información de la tarea de los atributos de datos
    var taskId = $(this).data('id');
    var taskName = $(this).data('tarea');

    // Establece los valores en los campos del modal
    $('#id_tarea_c').val(taskId);
    $('#tareaDes').val(taskName);
    // Abre el modal
    $('#completeTaskModal').modal("toggle");
});
// fin de completar las tarea
$(document).on("click", "#btn-menu-back", function () {
    location.href = "menu";
    $.when($(".carga-esconder").stop(true, true).hide()).done(function (x) {
        $.when($(".carga-class").stop(true, true).show()).done(function (x) {});
    });
});
$(document).on('click', '.btn-asignar', function () {
    var taskId = $(this).data('taskid'); // Obtiene el ID de la tarea desde el botón
    $('#filaId').val(taskId); // Asigna el taskId al campo oculto del modal
    $('#assignModal').modal("toggle"); // Muestra el modal
});
$(document).on('click', '.btn-ver', function () {
    var taskId = $(this).data('taskid'); // Obtiene el ID de la tarea desde el botón
    $('#filaId').val(taskId); // Asigna el taskId al campo oculto del modal
    llenarModal(taskId);
    // $('#viewModal').modal('show'); // Muestra el modal

});
// Función para guardar en IndexedDB
// Función para guardar en IndexedDB
function generateRandomId() {
    return Date.now() + '-' + Math.floor(Math.random() * 1000000);
}
function guardarEnIndexedDB(data) { // Asignar un id_evaluacion aleatorio si no existe
    if (! data.id_evaluacion) {
        data.id_evaluacion = data.hash;
    }
    // Abrir la base de datos o crearla si no existe
    var request = indexedDB.open('DBAppSDV', 1);

    // Evento que se dispara cuando se necesita actualizar la estructura de la base de datos
    request.onupgradeneeded = function (event) {
        var db = event.target.result;
        if (! db.objectStoreNames.contains('tbl_mercado')) {
            var objectStore = db.createObjectStore('tbl_mercado', {keyPath: 'id_evaluacion'});
        }
    };

    // Evento que se dispara cuando se completa la operación
    request.onsuccess = function (event) {
        var db = event.target.result;

        // Crear una transacción para el object store 'tbl_mercado'
        var transaction = db.transaction(['tbl_mercado'], 'readwrite');

        // Obtener el object store
        var objectStore = transaction.objectStore('tbl_mercado');

        // Intentar añadir los datos
        var addRequest = objectStore.put(data);

        addRequest.onsuccess = function () {
            console.log('Datos guardados correctamente');
        };

        addRequest.onerror = function () {
            console.log('Error al guardar datos en IndexedDB');
        };
    };

    request.onerror = function () {
        console.log('Error al abrir la base de datos');
    };
}
// END hide modal tarea
// Convertir la imagen a Base64
function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result.split(',')[1]);
        reader.onerror = error => reject(error);
    });
}
// Enviar los datos al servidor
function sendDataToServer(base64, photoNumber, taskId) {
    var fechaSeguimiento = new Date().toISOString().split('T')[0]; // Obtener la fecha actual
    $.ajax({
        url: 'C_mercado/Ctr_mercado/completeTask',
        method: 'POST',
        data: {
            [photoNumber]: base64,
            'fechaSeguimiento': fechaSeguimiento,
            'taskId': taskId
        },
        success: function (response) {
            Swal.fire({icon: 'info', title: 'Éxito', text: 'La tarea se ha completado con éxito.'}).then((result) => { // Cierra el modal si el usuario hace clic en "Aceptar"
                if (result) {
                    $('#completeTaskModal').modal("toggle");
                    location.reload();
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
            Swal.fire({icon: 'error', title: 'Error', text: 'Ocurrió un error al completar la tarea.'})
        }
    });
}

function DB_IniciarCPSesion(validateUs) {
    return new Promise(function (resolve, reject) {
        dataBaseAppSDV = indexedDB.open('DBAppSDV', 1);
        dataBaseAppSDV.onsuccess = function (e) {
            cantidad_idx_us = 0;
            var activedos = dataBaseAppSDV.result;
            var transaction = activedos.transaction(['tbl_usuarios'], 'readonly');
            var objectStore = transaction.objectStore('tbl_usuarios');
            var countRequest = objectStore.count();
            countRequest.onsuccess = function () {
                if (countRequest.result > 0) {
                    DB_UsuarioLogueado();
                    Promise.all([cargar_credenciales_sdv(), DB_CantidadUsuarios()]).then(respuestas => {
                        if (arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155) {
                            $('#slc_ruta_').show();
                            if (arrg_Credls['ruta_desarrollador'] != '' && arrg_Credls['ruta_desarrollador'] != null) {
                                $("#slc_ruta_desarrollador").val(arrg_Credls['ruta_desarrollador']).trigger('change');
                            }
                        } else {
                            $('#slc_ruta_').hide();
                        }
                    }).catch(error => {
                        console.log(error);
                    });
                } else {
                    if (validateUs == 1) 
                        location.href = '/sdv/';
                    


                }
            };
            countRequest.onerror = function (event) {
                location.href = '/sdv/';
            };
            resolve(1);
        };
        dataBaseAppSDV.onupgradeneeded = function (e) {
            var active = dataBaseAppSDV.result;
            var OBJ_tblusuarios = active.createObjectStore("tbl_usuarios", {
                keyPath: 'us_cod',
                autoIncrement: true
            });
            var OBJ_tbldepartamento = active.createObjectStore("tbl_departamento", {keyPath: 'idepart'});
            var OBJ_tblmunicipio = active.createObjectStore("tbl_municipio", {keyPath: 'idmun'});
            OBJ_tblmunicipio.createIndex('by_depat', 'depat', {unique: false});
            var OBJ_tbltpuntoventa = active.createObjectStore("tbl_tpuntoventa", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tblgironegocio = active.createObjectStore("tbl_gironegocio", {keyPath: 'idgiro'});
            OBJ_tblgironegocio.createIndex('by_tpventa', 'tpv', {unique: false});
            var OBJ_tbltfacturacion = active.createObjectStore("tbl_tfacturacion", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tblcondicioncli = active.createObjectStore("tbl_condicioncli", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tblclingresados = active.createObjectStore("tbl_clingresados", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tblclitemporales = active.createObjectStore("tbl_clitemporales", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tblexhibidores = active.createObjectStore("tbl_exhibidores", {keyPath: 'idexh'});
            var OBJ_clientes = active.createObjectStore("tbl_clientes", {keyPath: 'Cli_Id'});
            OBJ_clientes.createIndex('by_estado_w', 'Cli_estado', {unique: false});
            var OBJ_observacionexh = active.createObjectStore("tbl_observacionexh", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_obexhingresados = active.createObjectStore("tbl_obexhingre", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_exhifacturados = active.createObjectStore("tbl_exhifacturados", {keyPath: 'idexhf'});
            OBJ_exhifacturados.createIndex('by_exhfact', 'exhfact', {unique: false});
            var OBJ_clientesactuingre = active.createObjectStore("tbl_clientesactuingre", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_cliactutempo = active.createObjectStore("tbl_cliactutempo", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_parametros = active.createObjectStore("tbl_parametros", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tbl_exh_censados = active.createObjectStore("tbl_exh_censados", {
                keyPath: 'idexh',
                autoIncrement: true
            });
            OBJ_tbl_exh_censados.createIndex('by_codigocli', 'CodigoCliente', {unique: false});
            var OBJ_tblmotivoelim = active.createObjectStore("tbl_motivoelim", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tblfiltros = active.createObjectStore("tbl_filtros", {
                keyPath: 'id',
                autoIncrement: true
            });
            var OBJ_tblproductos = active.createObjectStore("tbl_productos", {keyPath: 'Cat_Id'});
            OBJ_tblproductos.createIndex('by_estado_p', 'Catx_estado', {unique: false});
            OBJ_tblproductos.createIndex('by_familia_p', 'Cat_familia', {unique: false});
            OBJ_tblproductos.createIndex('by_Subf_Fa_Id', 'Subf_Fa_Id', {unique: false});
            // -- 04/10/2021 ------------------------------------------------------------------------------------------------
            // var OBJ_tbl_reclamosingre = active.createObjectStore("tbl_reclamosingre", {keyPath: 'Id', autoIncrement: true});
            var OBJ_tbl_reclamosingre = active.createObjectStore("tbl_reclamosingre", {
                keyPath: 'codigo_reclamo',
                autoIncrement: true
            });
            OBJ_tbl_reclamosingre.createIndex('by_cola', 'pendiente', {unique: false});
            OBJ_tbl_reclamosingre.createIndex('by_cliente', 'Id_Cliente', {unique: false});
            OBJ_tbl_reclamosingre.createIndex('by_estado', 'estado', {unique: false});
            // --------------------------------------------------------------------------------------------------------
            var OBJ_tbl_reclamosTemp = active.createObjectStore("tbl_reclamosTemp", {
                keyPath: 'Id',
                autoIncrement: true
            });
            var OBJ_tbl_tipo_danos = active.createObjectStore("tbl_tipo_danos", {keyPath: 'Tipd_Id'});
            // DBA CAMBIOS 07/07/2021/
            var OBJ_tblstatusex = active.createObjectStore("tbl_status_exhibidores", {
                keyPath: 'Ste_token',
                autoIncrement: false
            });
            OBJ_tblstatusex.createIndex('by_Ste_Cli_Id', 'Ste_Cli_Id', {unique: false});
            OBJ_tblstatusex.createIndex('by_Ste_cola', 'Ste_cola', {unique: false});
            var OBJ_tbltipoexh = active.createObjectStore("tbl_tipo_exhibidores", {
                keyPath: 'idx',
                autoIncrement: true
            });
            // -- 06/09/2021 ------------------------------------------------------------------------------------------
            var OBJ_tbl_control_inventario = active.createObjectStore("tbl_control_inventario", {
                keyPath: 'token',
                unique: true
            });
            OBJ_tbl_control_inventario.createIndex('by_Cola', 'pendiente', {unique: false});
            OBJ_tbl_control_inventario.createIndex('by_enviado', 'enviado', {unique: false});
            OBJ_tbl_control_inventario.createIndex('by_cliente', 'id_cliente', {unique: false});
            OBJ_tbl_control_inventario.createIndex('by_opcion', 'opcion', {unique: false}); // --- 17/08/21 ---
            OBJ_tbl_control_inventario.createIndex('by_token', 'token', {unique: true});
            // --- 20/08/21 ---
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
            OBJ_tblPedidoSugerido.createIndex('by_PedSug_cola', 'PedSug_cola', {unique: false});
            OBJ_tblPedidoSugerido.createIndex('by_IdPedidoEnc', 'IdPedidoEnc', {unique: false});
            // --------------------------------------------------------------------------------------------------------
            // ----- 17/11/2022 -----------------------------------------------------------------------------------------
            var tbl_PedSug_Motivo = active.createObjectStore("tbl_PedSug_Motivo", {keyPath: 'Id'});
            tbl_PedSug_Motivo.createIndex('by_Tipo', 'Tipo', {unique: false});
            // --------------------------------------------------------------------------------------------------------
            // ----- 02/03/2022 ---------------------------------------------------------------------------------------
            var OBJ_tbl_vehiculo = active.createObjectStore("tbl_vehiculo", {
                keyPath: 'idx',
                autoIncrement: true
            });
            OBJ_tbl_vehiculo.createIndex('by_recepcion', 'Vehi_fecha_recibido', {unique: false});
            OBJ_tbl_vehiculo.createIndex('by_estado', 'Vehi_estado', {unique: false});
            OBJ_tbl_vehiculo.createIndex('by_placas', 'Vehi_placas', {unique: false});
            OBJ_tbl_vehiculo.createIndex('by_es_sincronizado', 'es_sincronizado', {unique: false});
            var OBJ_tbl_items_check_list_vehiculo = active.createObjectStore("tbl_items_check_list_vehiculo", {
                keyPath: 'idx',
                autoIncrement: true
            });
            OBJ_tbl_items_check_list_vehiculo.createIndex('by_estado', 'Irv_estado', {unique: false});
            OBJ_tbl_items_check_list_vehiculo.createIndex('by_id', 'Irv_Id', {unique: false});
            OBJ_tbl_items_check_list_vehiculo.createIndex('by_seccion', 'Irv_seccion_descripcion', {unique: false});
            OBJ_tbl_items_check_list_vehiculo.createIndex('by_tipo', 'Irv_tipo', {unique: false});
            var OBJ_tbl_vehiculo_recepcion = active.createObjectStore("tbl_vehiculo_recepcion", {
                keyPath: 'idx',
                autoIncrement: true
            });
            OBJ_tbl_vehiculo_recepcion.createIndex('by_fecha_recepcion', 'fecha_recepcion', {unique: false});
            OBJ_tbl_vehiculo_recepcion.createIndex('by_estado', 'estado_recepcion', {unique: false});
            OBJ_tbl_vehiculo_recepcion.createIndex('by_es_sincronizado', 'es_sincronizado', {unique: false});
            // --------------------------------------------------------------------------------------------------------
            // ----- 03/07/2022 ---------------------------------------------------------------------------------------
            var OBJ_tbl_tipo_licencia = active.createObjectStore("tbl_tipo_licencia", {keyPath: 'TLic_Id'});
            OBJ_tbl_tipo_licencia.createIndex('by_tipo', 'TLic_nombre', {unique: false});
            // --------------------------------------------------------------------------------------------------------
            // ----- 24/01/2022 ---------------------------------------------------------------------------------------
            var OBJ_tbl_parametros_vnt = active.createObjectStore("tbl_parametros_vnt", {
                keyPath: 'idx',
                autoIncrement: true
            });
            OBJ_tbl_parametros_vnt.createIndex('by_tipo', 'tipo_parametro', {unique: false});
            // ----- 09/05/2022 ---------------------------------------------------------------------------------------
            var OBJ_tbl_parametros_vnt = active.createObjectStore("tbl_referencia", {
                keyPath: 'idx',
                autoIncrement: true
            });
        }
        dataBaseAppSDV.onerror = function (e) {
            Swal.fire({title: 'Aviso Importante!', type: 'error', html: '<h5>Error inesperado, por favor comunicarlo a Sistemas de Venta</h5>', confirmButtonText: 'Ok'});
            reject(0);
        };
    });
}

function cargar_credenciales_sdv() {
    arrg_Credls = [];
    return new Promise(function (resolve, reject) {
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
            arrg_Credls['NombreRuta'] = elements[0].NombreRuta;
            arrg_Credls['usuario'] = elements[0].usuario;
            arrg_Credls['idusuario'] = elements[0].idusuario;
            arrg_Credls['clave'] = elements[0].clave;
            arrg_Credls['privilegio'] = elements[0].privilegio;
            arrg_Credls['ruta_app'] = elements[0].ruta_app;
            arrg_Credls['us_cod'] = elements[0].us_cod;
            arrg_Credls['us_ID_Ruta'] = elements[0].us_ID_Ruta;
            arrg_Credls['nombre_us'] = elements[0].nombre_us;
            arrg_Credls['idsupervisor'] = elements[0].idsupervisor;
            arrg_Credls['pais'] = elements[0].pais;
            arrg_Credls['canal_usu'] = elements[0].canal_usu;
            arrg_Credls['ltdistr'] = elements[0].ltdistr;
            arrg_Credls['ls_rutas'] = elements[0].ls_rutas;
            arrg_Credls['id_division'] = elements[0].id_division;
            arrg_Credls['id_distribuidora'] = elements[0].id_distribuidora;
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
            $("#docidentidad").html('<span class="fa fa-id-card fa-lg"></span> ' + elements[0].NombreDocumentoDUI + ':');
            $("#idtributaria").html('<span class="fa fa-id-card-alt fa-lg"></span> ' + elements[0].NombreDocumentoNIT + ':');
            if (elements[0].pais == 'EL SALVADOR') {
                arrg_Credls['CantidadGuionDUI'] = 1;
                arrg_Credls['CantidadGuionNIT'] = 3;
            } else if (elements[0].pais == 'GUATEMALA') {
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
            } else if (elements[0].pais == 'HONDURAS') {
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
            } else if (elements[0].pais == 'REPUBLICA DOMINICANA') {
                arrg_Credls['CantidadGuionDUI'] = 2;
                arrg_Credls['CantidadGuionNIT'] = 0;
            } else {
                arrg_Credls['CantidadGuionDUI'] = 1;
                arrg_Credls['CantidadGuionNIT'] = 3;
            }
            if (arrg_Credls['privilegio'] == 15 || arrg_Credls['privilegio'] == 116 || arrg_Credls['privilegio'] == 155) {
                Promise.all([cargar_ruta_desarrollo()]).then(respuestas => {
                    resolve(1);
                }).catch(error => {
                    console.log(error);
                });
            } else {
                resolve(1);
            }
            if (elements[0].passwor_status == '1') {
                $("#m-cambio-contrasena").modal("toggle");
            }
        };

        data.onerror = function () {
            reject(0);
        };
    });
}

function DB_CantidadUsuarios() {
    cantidad_idx_us = 0;
    var active = dataBaseAppSDV.result;
    var transaction = active.transaction(['tbl_usuarios'], 'readonly');
    var objectStore = transaction.objectStore('tbl_usuarios');
    var countRequest = objectStore.count();
    countRequest.onsuccess = function () {
        cantidad_idx_us = (countRequest.result > 0) ? 1 : 0;
    };
    countRequest.oncomplete = function () {};
    countRequest.onerror = function (event) {
        cantidad_idx_us = 0;
    };
}

function DB_UsuarioLogueado() {
    var rutas = [];
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
        us_cod = elements[0].us_cod;
        us_ID_Ruta = elements[0].us_ID_Ruta;
        pais = elements[0].pais;
        canal_usu = elements[0].canal_usu;
        usuario = elements[0].usuario;
        rutas = elements[0].ls_rutas;
        us_cod = elements[0].us_cod;
        // CONFIRMAR USUARIO
        var url_indicada = '',
            url_indicada = elements[0].ruta_app;
        url_indicada = url_indicada.replace("index.php/", "");
        if (elements[0].privilegio == '#supe1.$0') {
            $("#uslogin").empty().html('-------');
            location.href = url_indicada;
        } else if (elements[0].privilegio == 'admin01') {
            $("#uslogin").empty().html('-------');
            location.href = url_indicada;
        } else {
            $("#uslogin").empty().html(elements[0].nombre_us);
            if (elements[0].privilegio == '3' || elements[0].privilegio == 155) {
                $("#t_taskforce").show();
            } else {}
        }
        if (elements[0].privilegio == 15 || elements[0].privilegio == 116 || elements[0].privilegio == 155) {
            if (rutas.length != 0) {
                var arr_dat = [];
                var atributos_dropdown = {
                    class_input: 'form-control custom-select'
                };
                rutas.forEach(function (valor, index) {
                    arr_dat.push({codbx: valor.Ru_Id, valor: valor.Ru_nombre});
                    $('#select_ruta_desarrollador').html(_form_dropdown('slc_ruta_desarrollador', arr_dat, '', atributos_dropdown));
                });
            }
        }
    };
    data.onerror = function (e) {
        $("#uslogin").empty().html("");
    }
}
function cargar_localizacion() { // Verifica si la geolocalización está soportada
    if ("geolocation" in navigator) { // Solicita la ubicación actual
        navigator.geolocation.getCurrentPosition(function (position) { // Asigna las coordenadas a los campos de entrada
            document.getElementById('latitud').value = position.coords.latitude;
            document.getElementById('longitud').value = position.coords.longitude;
        }, function (error) { // Si ocurre un error, lo muestra en la consola
            console.log('Error al obtener la ubicación: ' + error.message);
        });
    } else { // Si la geolocalización no está soportada, muestra un mensaje de error
        alert('La geolocalización no está soportada en este navegador.');
    }
}
// esto es lo nuevo
function cargar_datos() {
    cargarOportunidades();

    // Arreglo con las divisiones y sus ID
    let divisiones = [
        {
            id: 1,
            nombre: "SV_CENTRO"
        },
        {
            id: 2,
            nombre: "SV_OCCIDENTE"
        },
        {
            id: 3,
            nombre: "SV_ORIENTE"
        },
        {
            id: 4,
            nombre: "GT_CENTRO"
        }, {
            id: 5,
            nombre: "GT_NORTE"
        }, {
            id: 6,
            nombre: "GT_SUR"
        }, {
            id: 7,
            nombre: "HN_CENTRO"
        }, {
            id: 8,
            nombre: "HN_NORTE"
        }, {
            id: 9,
            nombre: "RDO"
        }, {
            id: 11,
            nombre: "NICARAGUA"
        }, {
            id: 12,
            nombre: "COSTA RICA"
        }, {
            id: 13,
            nombre: "BELICE"
        }, {
            id: 14,
            nombre: "CR_MFN"
        }, {
            id: 15,
            nombre: "CR_PNL"
        }, {
            id: 16,
            nombre: "CR_JACKS"
        }
    ];

    let distribuidoras = [
        {
            id: 1,
            nombre: "CHALATENANGO"
        },
        {
            id: 2,
            nombre: "SAN SALVADOR"
        },
        {
            id: 3,
            nombre: "SANTA ANA"
        },
        {
            id: 4,
            nombre: "SONSONATE"
        }, {
            id: 5,
            nombre: "SAN MIGUEL"
        }, {
            id: 6,
            nombre: "BARBERENA"
        }, {
            id: 7,
            nombre: "CHIMALTENANGO"
        }, {
            id: 8,
            nombre: "ESCUINTLA"
        }, {
            id: 9,
            nombre: "JUTIAPA"
        }, {
            id: 10,
            nombre: "KARANTE"
        }, {
            id: 11,
            nombre: "CHIQUIMULA"
        }, {
            id: 12,
            nombre: "COBAN"
        }, {
            id: 13,
            nombre: "PETEN"
        }, {
            id: 14,
            nombre: "COATEPEQUE"
        }, {
            id: 15,
            nombre: "HUEHUETENANGO"
        }, {
            id: 16,
            nombre: "QUETZALTENANGO"
        }, {
            id: 17,
            nombre: "CHOLUTECA"
        }, {
            id: 18,
            nombre: "COMAYAGUA"
        }, {
            id: 19,
            nombre: "DANLI"
        }, {
            id: 20,
            nombre: "OLANCHO"
        }, {
            id: 21,
            nombre: "TEGUCIGALPA"
        }, {
            id: 22,
            nombre: "LA CEIBA"
        }, {
            id: 23,
            nombre: "SAN PEDRO SULA"
        }, {
            id: 24,
            nombre: "SANTA ROSA DE COPAN"
        }, {
            id: 25,
            nombre: "TECULUTAN"
        }, {
            id: 26,
            nombre: "LAGO MANCILLA"
        }, {
            id: 28,
            nombre: "REPUBLICA DOMINICANA"
        }, {
            id: 29,
            nombre: "NICARAGUA"
        }, {
            id: 30,
            nombre: "COSTA RICA"
        }, {
            id: 31,
            nombre: "BELICE"
        }, {
            id: 32,
            nombre: "MAFIONI"
        }, {
            id: 33,
            nombre: "PANAL"
        }, {
            id: 34,
            nombre: "CR_JACKS"
        }
    ];

    function obtenerNombreDistribuidora(id) {
        id = parseInt(id);
        let distribuidoraEncontrada = distribuidoras.find(distribuidora => distribuidora.id === id);
        return distribuidoraEncontrada ? distribuidoraEncontrada.nombre : '';
    }
    // Función para obtener el nombre de la división basado en el ID
    function obtenerNombreDivision(id) {
        id = parseInt(id);
        let divisionEncontrada = divisiones.find(division => division.id === id);
        return divisionEncontrada ? divisionEncontrada.nombre : '';
    }

    return new Promise(function (resolve, reject) {
        var request = indexedDB.open('DBAppSDV', 1);

        request.onsuccess = function (event) {
            var db = event.target.result;
            var transaction = db.transaction(['tbl_usuarios'], 'readonly');
            var objectStore = transaction.objectStore('tbl_usuarios');
            var request = objectStore.openCursor();

            request.onsuccess = function (event) {
                var cursor = event.target.result;
                if (cursor) {
                    var userData = cursor.value;
                    document.querySelector('#nombreuser').value = userData.nombre_us;
                    document.querySelector('#ruta').value = userData.us_ID_Ruta;
                    document.querySelector('#countries').value = userData.pais;
                    document.querySelector('#divisions').value = obtenerNombreDivision(userData.id_division); // Modificado para mostrar el nombre
                    document.querySelector('#distribuidora').value = obtenerNombreDistribuidora(userData.id_distribuidora);
                    cursor.continue();
                } else {
                    resolve(); // Resolvemos la promesa cuando se cargan los datos del usuario
                }
            };

            request.onerror = function (event) {
                console.error('Error al acceder a los datos del usuario:', event.target.error);
                reject(event.target.error);
            };
        };

        request.onerror = function (event) {
            console.error('Error al abrir la base de datos:', event.target.error);
            reject(event.target.error);
        };
    });
}

cargar_datos().then(function () {
    var searchBox = document.getElementById('search-box6');
    var suggestionBox = document.getElementById('suggesstion-box6');

    searchBox.addEventListener('input', function () {
        var inputValue = searchBox.value;
        suggestionBox.innerHTML = '';
        var request = indexedDB.open('DBAppSDV', 1);
        var suggestionCount = 0; // Contador para limitar las sugerencias a 8

        request.onsuccess = function (event) {
            var db = event.target.result;
            var transaction = db.transaction(['tbl_clientes'], 'readonly');
            var objectStore = transaction.objectStore('tbl_clientes');
            var request = objectStore.openCursor();

            request.onsuccess = function (event) {
                var cursor = event.target.result;
               // console.log("Este es el cursor: ",cursor);
                // Convertir inputValue a mayúsculas
                var inputValueUpper = inputValue.toUpperCase();
            
                if (cursor && suggestionCount < 8) {
                    var clienteCodigo = cursor.value.Cli_codigo;
                    var clienteNombre = cursor.value.Cli_nombre;
                    var clienteDirecciion = cursor.value.Cli_direccion;
                    var clienteTipo = cursor.value.Gir_descripcion;
            
                    // Convertir código y nombre a mayúsculas para que la búsqueda no sea sensible a mayúsculas/minúsculas
                    var clienteCodigoUpper = clienteCodigo.toUpperCase();
                    var clienteNombreUpper = clienteNombre.toUpperCase();
            
                    // Comprobar si el código o el nombre contienen la cadena inputValueUpper
                    if (clienteCodigoUpper.includes(inputValueUpper) || clienteNombreUpper.includes(inputValueUpper)) {
                        var suggestionItem = document.createElement('div');
                        suggestionItem.textContent = clienteCodigo + " - " + clienteNombre;
                        suggestionItem.addEventListener('click', function () {
                            searchBox.value = clienteCodigo;
                            document.querySelector('#nombreEstablecimiento').value = clienteNombre;
                            document.querySelector('#direccion').value = clienteDirecciion;
                            document.querySelector('#tipoNegocio').value = clienteTipo;
                            suggestionBox.innerHTML = '';
                        });
                        suggestionBox.appendChild(suggestionItem);
                        suggestionCount++;
                    }
                    cursor.continue();
                }
            };
            

            request.onerror = function (event) {
                console.error('Error al acceder a los datos de clientes:', event.target.error);
            };
        };

        request.onerror = function (event) {
            console.error('Error al abrir la base de datos:', event.target.error);
        };
    });



    obtenerDatosUsuarios().then(usuarios => {
        if (usuarios.length > 0) {
            const pais = usuarios[0].pais;
            // Asumiendo que quieres el país del primer usuario. Ajusta esto según tus necesidades.

            // Seleccionamos los elementos por su id
            const ricaSulaGroup = document.getElementById('ricaSulaGroup');
            const tropicalGroup = document.getElementById('tropicalGroup');
            const yaEstaGroup = document.getElementById('yaEstaGroup');
            const botanisGroup = document.getElementById('botanisGroup');

            // De forma predeterminada, ocultamos todos
            ricaSulaGroup.style.display = "none";
            tropicalGroup.style.display = "none";
            yaEstaGroup.style.display = "none";
            botanisGroup.style.display = "none";

            switch (pais) {
                case "HONDURAS": ricaSulaGroup.style.display = "";
                    tropicalGroup.style.display = "";
                    break;
                case "GUATEMALA": yaEstaGroup.style.display = "";
                    botanisGroup.style.display = "";
                    break;
                case "EL SALVADOR":
                    // En este caso, todos están ocultos ya que configuramos la ocultación por defecto arriba
                    break;
                default:
                    // Aquí puedes manejar cualquier otro caso o simplemente dejarlo vacío
                    break;
            }
        }
    });


}).catch(function (error) {
    console.error('Error al cargar los datos:', error);
});

////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
function ruta() {
    return new Promise((resolve, reject) => {

        var request = indexedDB.open('DBAppSDV', 1);

        request.onsuccess = function (event) {
            var db = event.target.result;
            var transaction = db.transaction(['tbl_clientes'], 'readonly');
            var objectStore = transaction.objectStore('tbl_clientes');
            var cursorRequest = objectStore.openCursor();

            cursorRequest.onsuccess = function (event) {
                var cursor = event.target.result;
                if (cursor) {
                    var clienteRuId = cursor.value.Cli_Ru_Id; // Obtener el campo Cli_Ru_Id del primer elemento
                    rut = clienteRuId; // Asignar el valor a la variable global 'rut'
                    resolve(clienteRuId); // Retornar el valor de Cli_Ru_Id
                } else {
                    reject('No hay registros en la tienda de objetos.');
                }
            };

            cursorRequest.onerror = function (event) {
                console.error('Error al acceder a los datos de clientes:', event.target.error);
                reject(event.target.error);
            };
        };

        request.onerror = function (event) {
            console.error('Error al abrir la base de datos:', event.target.error);
            reject(event.target.error);
        };
    });
}


ruta().then(clienteRuId => {
    console.log("El Cli_Ru_Id obtenido y asignado a 'rut' es:", rut);
}).catch(error => {
    console.error("Error:", error);
});


////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////



function cargarOportunidades() {
    var selectElement = document.getElementById('listaOportunidades');

    if (selectElement) {
        var request = indexedDB.open('DBAppSDV', 1);

        request.onsuccess = function (event) {
            var db = event.target.result;
            var transaction = db.transaction(['tbl_oportunidades'], 'readonly');
            var objectStore = transaction.objectStore('tbl_oportunidades');
            var request = objectStore.openCursor();

            request.onsuccess = function (event) {
                var cursor = event.target.result;
                if (cursor) {
                    var oportunidadData = cursor.value;
                    // Crea un nuevo elemento <option> para el select y asigna valores
                    var option = document.createElement('option');
                    // option.value = oportunidadData.id; // Asigna el valor del campo id de la oportunidad
                    option.textContent = oportunidadData.nombre_oportunidad;
                    // Asigna el valor del campo nombre de la oportunidad

                    // Agrega el <option> al select
                    selectElement.appendChild(option);

                    cursor.continue();
                } else { // console.log('Carga de oportunidades completa.');
                }
            };

            request.onerror = function (event) {
                console.error('Error al acceder a los datos de oportunidades:', event.target.error);
            };
        };

        request.onerror = function (event) {
            console.error('Error al abrir la base de datos:', event.target.error);
        };
    } else {
        console.error('Elemento con id="listaOportunidades" no encontrado en el DOM.');
    }
}
// crear tarea
async function crearTarea(formData) {
    const result = await Swal.fire({
        title: '¡Éxito!',
        text: '¿Deseas crear una tarea a partir del registro anterior?',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        type: 'question'
    });
    if (result.value) {
        const taskTypeResult = await Swal.fire({
            title: '¿Qué tipo de tarea quieres crear?',
            text: 'Elige el tipo de solicitud',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Trabajo',
            cancelButtonText: 'Servicio'
        });

        if (taskTypeResult.value) {
            await sendTask(formData, 1); // 1 for 'Trabajo'
        } else {
            await sendTask(formData, 2); // 2 for 'Servicio'
        }
    } else { // / resetForm();
        return;
    }
}
function sendTask(originalFormData, type) {

    console.log(originalFormData);
    let formData = new FormData();
    // Agregar los datos al nuevo FormData
    formData.append('tarea_id', originalFormData.hash);
    formData.append('estado', 0);
    formData.append('comentario', originalFormData.oportunidades);
    formData.append('fecha', originalFormData.fechaSeguimiento);
    formData.append('creado_por', originalFormData.nombreuser);
    formData.append('tipo_tarea', type);
    formData.append('asignado_a', originalFormData.asignado_a);
    formData.append('hash', originalFormData.hash);

    const serializedData = {};
    for (const [key, value] of formData.entries()) {
        serializedData[key] = value;
    }

    // Muestra una notificación de "cargando" antes de enviar la solicitud AJAX
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor, espere...',
        allowOutsideClick: false,
        showConfirmButton: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        }
    });

    // Intenta enviar la solicitud AJAX
    $.ajax({
        url: 'C_mercado/Ctr_mercado/crear_tarea',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        timeout: 15000,
        dataType: 'JSON'
    }).done(function (response) { // Cierra la notificación de "cargando"
        Swal.close();

        Swal.fire({
            html: 'La tarea se ha guardado exitosamente y asignada a la ruta ' + rut,
            title: '¡Tarea creada!',
            showConfirmButton: true,
            allowOutsideClick: false,
            type: 'success'
        }).then((result) => {
            if (result.value) {
                serializedData['enviado'] = 'SI';
                guardarTarea(serializedData);
                resetForm();
            }
        });
    }).always(function (response, textStatus, errorThrown) {
        if (textStatus !== 'success') {
            Swal.fire('Tarea creada!', 'La tarea se ha guardado temporalmente..', 'success');
            serializedData['enviado'] = 'NO';
            guardarTarea(serializedData);
            cola();
            resetForm();
        }
    });


}
function guardarTarea(data) {
    // Guardando tareas en IndexedDB
    // Asignar un id_evaluacion aleatorio si no existe
    if (! data.tarea_id) {
        data.tarea_id = data.hash;
    }
    // Abrir la base de datos o crearla si no existe
    var request = indexedDB.open('DBAppSDV', 1);

    // Evento que se dispara cuando se necesita actualizar la estructura de la base de datos
    request.onupgradeneeded = function (event) {
        var db = event.target.result;
        if (! db.objectStoreNames.contains('tbl_tareas')) {
            var objectStore = db.createObjectStore('tbl_tareas', {keyPath: 'tarea_id'});
        }
    };

    // Evento que se dispara cuando se completa la operación
    request.onsuccess = function (event) {
        var db = event.target.result;

        // Crear una transacción para el object store 'tbl_mercado'
        var transaction = db.transaction(['tbl_tareas'], 'readwrite');

        // Obtener el object store
        var objectStore = transaction.objectStore('tbl_tareas');

        // Intentar añadir los datos
        var addRequest = objectStore.put(data);

        addRequest.onsuccess = function () { // console.log('Datos guardados correctamente');
        };

        addRequest.onerror = function () { // console.log('Error al guardar datos en IndexedDB');
        };
    };

    request.onerror = function () { // console.log('Error al abrir la base de datos');
    };

}

// Cargar tareas en tabla
function cargar_tareas() {
    var dbName = 'DBAppSDV';
    var dbVersion = 1;
    var request = indexedDB.open(dbName, dbVersion);

    request.onsuccess = function (event) {
        var db = event.target.result;
        var transaction = db.transaction([
            'tbl_tareas', 'tbl_mercado'
        ], 'readonly');
        var tareasStore = transaction.objectStore('tbl_tareas');
        var mercadoStore = transaction.objectStore('tbl_mercado');
        var tareasRequest = tareasStore.getAll();
        var mercadoRequest = mercadoStore.getAll();

        tareasRequest.onsuccess = function () {
            var tareasData = tareasRequest.result;
            mercadoRequest.onsuccess = function () {
                var mercadoData = mercadoRequest.result;

                // Llama a obtenerDatosUsuarios() y obtén el valor de us_cod
                obtenerDatosUsuarios().then(function (usuarios) {
                    var us_cod = usuarios[0].us_cod.toString();
                    var user = usuarios[0].usuario.toString();

                    var dataSet = tareasData.map(function (tarea) { // Encuentra el mercado relacionado para esta tarea
                        var mercadoRelacionado = mercadoData.find(m => m.hash === tarea.hash);

                        if (mercadoRelacionado) {
                            var creado = mercadoRelacionado.id_creado.toString();
                            // Realiza la validación para cada mercadoRelacionado
                            var estado;
                            var estadoClass;
                            var btnAsignar = '<button class="btn btn-secondary btn-asignar btn-fixed-width" data-taskid="' + tarea.tarea_id + '">Asignar</button>';
                            if (tarea.estado == 0) {
                                estado = 'Pendiente';
                                estadoClass = 'estado-no-asignado';
                            } else if (tarea.estado == 1) {
                                estado = 'Activo';
                                estadoClass = 'estado-activo';
                                btnAsignar = '<button class="btn btn-secondary btn-asignar btn-fixed-width" data-taskid="' + tarea.tarea_id + '" disabled>Asignar</button>';
                            } else if (tarea.estado == 2) {
                                estado = 'Finalizado';
                                estadoClass = 'estado-finalizado';
                                btnAsignar = '<button class="btn btn-secondary btn-asignar btn-fixed-width" data-taskid="' + tarea.tarea_id + '" disabled>Asignar</button>';
                            }
                            var tipo = (tarea.tipo_tarea == 1) ? "Trabajo" : "Servicio";
                            return {
                                "DT_RowId": "row_" + tarea.tarea_id,
                                "0": tipo ? tipo : "N/A",
                                "1": '<p><b>' + (
                                mercadoRelacionado.nombreEstablecimiento ? mercadoRelacionado.nombreEstablecimiento : "N/A"
                            ) + ": " + (
                                mercadoRelacionado.codigoCliente ? mercadoRelacionado.codigoCliente : "N/A"
                            ) + '</b></p><p class="truncate">' + (
                                mercadoRelacionado.direccion ? mercadoRelacionado.direccion : "N/A"
                            ) + '</p>',
                                "2": '<p><b>' + (
                                mercadoRelacionado.n_oportunidad ? mercadoRelacionado.n_oportunidad : "N/A"
                            ) + '</b></p><p class="truncate">' + (
                                tarea.comentario ? tarea.comentario : "N/A"
                            ) + '</p>',
                                "3": tarea.fecha ? tarea.fecha : "N/A",
                                "4": tarea.asignado_a ? tarea.asignado_a : "N/A",
                                "5": '<span class="' + estadoClass + '">' + (
                                estado ? estado : "N/A"
                            ) + '</span>',
                                "6": '<button class="btn btn-primary btn-ver btn-fixed-width" data-taskid="' + tarea.tarea_id + '">Ver</button> ' + (
                                btnAsignar ? btnAsignar : "N/A"
                            )
                            };
                        }
                        return null;
                    }).filter(item => item !== null);

                    inicializarDataTable(dataSet);

                    // Añadido: Evento para los botones "Asignar"
                    $(".btn-asignar:not([disabled])").on("click", function () {
                        var taskId = $(this).data('taskid');
                        var task = tareasData.find(t => t.tarea_id === taskId);
                        if (task && (task.estado == 1 || task.estado == 2)) {
                            hasBeenAssigned(taskId);
                        }
                    });
                }).catch(function (error) {
                    console.error('Error al obtener datos de usuarios:', error);
                });
            };
        };

        transaction.onerror = function (event) {
            console.error('Error leyendo de IndexedDB:', event.target.errorCode);
        };
    };

    request.onerror = function (event) {
        console.error('Error abriendo base de datos:', event.target.errorCode);
    };
}


function hasBeenAssigned(taskId) {
    Swal.fire({
        icon: 'warning',
        title: 'Precaución',
        text: 'Esta tarea ya ha sido asignada.',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Aceptar'
    });
}
function inicializarDataTable(dataSet) {
    if ($.fn.dataTable.isDataTable('#list')) {
        $('#list').DataTable().destroy();
    }

    $('#list').DataTable({
        data: dataSet,
        columns: [
            {
                title: "Tipo Solicitud"
            },
            {
                title: "Cliente"
            },
            {
                title: "Tarea"
            },
            {
                title: "Fecha"
            }, {
                title: "Asignado a"
            }, {
                title: "Estado"
            }, {
                title: "Acción"
            }
        ],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ elementos",
            info: "Mostrando de _START_ a _END_ de _TOTAL_ elementos",
            infoEmpty: "Mostrando 0 elementos",
            infoFiltered: "(filtrado de _MAX_ elementos en total)",
            infoPostFix: "",
            loadingRecords: "Cargando registros...",
            zeroRecords: "No se encontraron registros",
            emptyTable: "No hay datos disponibles en la tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Último"
            },
            aria: {
                sortAscending: ": activar para ordenar la columna en orden ascendente",
                sortDescending: ": activar para ordenar la columna en orden descendente"
            }
        }
    });
}
// Función para llenar el modal con los detalles de la tarea
function llenarModal(taskId) {

    var db; // Declarar la variable para la base de datos.
    var dbName = 'DBAppSDV'; // Nombre de tu base de datos
    var version = 1;
    // Versión de tu base de datos

    // 1. Abrir la base de datos
    var openRequest = indexedDB.open(dbName, version);

    openRequest.onsuccess = function (e) {
        db = e.target.result;

        // 2. Obtener datos de Tareas
        var tareasTransaction = db.transaction(['tbl_tareas'], 'readonly');
        var tareasStore = tareasTransaction.objectStore('tbl_tareas');
        var taskIdAsString = String(taskId);
        var tareaRequest = tareasStore.get(taskIdAsString);

        tareaRequest.onsuccess = function () {
            var tareaData = tareaRequest.result;


            if (! tareaData) {
                console.error('Tarea no encontrada con el ID:', taskId);
                return;
            }

            // 3. Obtener datos de Mercado
            var mercadoTransaction = db.transaction(['tbl_mercado'], 'readonly');
            var mercadoStore = mercadoTransaction.objectStore('tbl_mercado');
            var mercadoRequest = mercadoStore.getAll();

            mercadoRequest.onsuccess = function () {
                var allMercados = mercadoRequest.result;

                var mercadoMatch = allMercados.find(mercado => mercado.hash === tareaData.hash);
                if (mercadoMatch) { // Datos del mercado
                    $("#nombreE").text(mercadoMatch.nombreEstablecimiento);
                    $("#dir").text(mercadoMatch.direccion);
                    $("#oportunidad").text(mercadoMatch.n_oportunidad);

                    // Datos de la tarea
                    $("#fec").text(mercadoMatch.fecha);
                    $("#asignado_a").text(tareaData.asignado_a);
                    $("#comen").text(tareaData.comentario);

                    var estado;
                    let estadoString = tareaData.estado.toString();

                    switch (estadoString) {
                        case "0": estado = 'Pendiente';
                            break;
                        case "1": estado = 'Activo';
                            break;
                        case "2": estado = 'Finalizado';
                            break;
                        default: estado = 'Desconocido';
                    }
                    $("#estado").text(estado);
                    $("#ruta_asig").text(tareaData.ruta_asignada);

                    // fotos

                    // Foto Uno
                    setImageSource("#foto_unoModal", mercadoMatch.foto_uno);

                    // Foto Dos
                    setImageSource("#foto_dosModal", mercadoMatch.foto_dos);

                    // Foto Tres
                    setImageSource("#foto_tresModal", mercadoMatch.foto_tres);

                    // Foto Cuatro
                    setImageSource("#foto_cua", tareaData.foto_u);

                    // Foto Cinco
                    setImageSource("#foto_cin", tareaData.foto_d);

                    // Foto Seis
                    setImageSource("#foto_sei", tareaData.foto_t);

                    // fin de fotos
                    function setImageSource(imgElement, source) {
                        if (source) {
                            let imageContent;

                            if (source.startsWith('/9j/')) { // Es una imagen JPEG en base64
                                imageContent = 'data:image/jpeg;base64,' + source;
                            } else if (source.startsWith('iVBORw0KGg')) { // Es una imagen PNG en base64
                                imageContent = 'data:image/png;base64,' + source;
                            } else if (source.startsWith('R0lGODlh')) { // Es una imagen GIF en base64
                                imageContent = 'data:image/gif;base64,' + source;
                            } else if (source.startsWith('Qk0')) { // Es una imagen BMP en base64
                                imageContent = 'data:image/bmp;base64,' + source;
                            } else { // Asumimos que es una URL
                                imageContent = source;
                            }
                            // Asignar el contenido o URL a la imagen
                            $(imgElement).attr("src", imageContent).show();
                        } else {
                            $(imgElement).hide();
                        }
                    }


                    // fin de fotos

                    $("#id_evaluacion").val(mercadoMatch.id_evaluacion);
                    $("#longit").val(mercadoMatch.longitud);
                    $("#latit").val(mercadoMatch.latitud);

                    // Mostrar el mapa con el punto en el modal
                    var mapContainer = $('#mapaVer');
                    if (! mapContainer.children().length) {

                        var map = L.map('mapaVer').setView([
                            mercadoMatch.latitud, mercadoMatch.longitud
                        ], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);
                        L.marker([mercadoMatch.latitud, mercadoMatch.longitud]).addTo(map);
                        // Centrar el mapa en las coordenadas del marcador nuevamente
                        map.setView([
                            mercadoMatch.latitud, mercadoMatch.longitud
                        ], 13);
                        // map.dragging.disable(); // Deshabilitar arrastrar el mapa
                        map.scrollWheelZoom.disable(); // Deshabilitar el control de zoom
                    }


                    $('#viewModal').modal("toggle");
                } else {
                    console.error('No se encontró un mercado relacionado para la tarea con el ID:', taskId);
                }
            };

            mercadoRequest.onerror = function () {
                console.error('Error al obtener el mercado relacionado');
            };
        };

        tareaRequest.onerror = function () {
            console.error('Error al obtener la tarea');
        };
    };

    openRequest.onerror = function (e) {
        console.error('Error abriendo la base de datos:', e);
    }
}
// /////////////////////////////////////////////////////////////
// ver si ya fue asignado
// tareas de la BaseIndex
// Asignar tarea
$(document).ready(function () {
    $(".btn-asignarRuta").click(function (e) {
        e.preventDefault();

        // Validaciones
        if (! validarAsignarFormulario()) {
            return false;
        }

        var tarea_id = document.getElementById("filaId").value;
        var ruta = document.getElementById("search-box5").value;
        var comentario = document.getElementById("comentario").value;

        // Preparar los datos para IndexedDB
        var dataForIndexedDB = {
            tarea_id: tarea_id,
            estado: "1",
            ruta: ruta,
            comentario: comentario,
            asignado_a: ruta,
            ruta_asignada: ruta,
            ac_enviado: "NO" // Por defecto, asumimos que no ha sido enviado.
        };
        // Enviar al servidor
        // Muestra una notificación de "cargando" antes de enviar la solicitud AJAX
        Swal.fire({
            title: 'Cargando',
            text: 'Por favor, espere...',
            allowOutsideClick: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Intenta enviar la solicitud AJAX
        $.ajax({
            url: 'C_mercado/Ctr_mercado/asignar',
            type: 'POST',
            data: $("#assignForm").serialize(),
            dataType: 'JSON',
            timeout: 15000
        }).done(function (response) { // Cierra la notificación de "cargando"
            Swal.close();

            if (response.error) {
                Swal.fire({title: 'Error!', text: response.error, icon: 'error', confirmButtonText: 'OK'});
            } else if (response.success) {
                dataForIndexedDB['ac_enviado'] = 'SI';
                actualizarTarea(dataForIndexedDB);

                // Puedes agregar aquí cualquier otro código relacionado con el éxito de la solicitud.

                // Por ejemplo, si deseas recargar la página después del éxito:
                // location.reload();

                // O si deseas cerrar un modal:
                // $('#assignModal').modal('toggle');
            }
        }).always(function (response, textStatus, errorThrown) {
            if (textStatus != 'success') {
                dataForIndexedDB['ac_enviado'] = 'NO';
                actualizarTarea(dataForIndexedDB);

                Swal.fire({title: 'Error!', text: 'Hubo un problema al intentar enviar los datos al servidor. Por favor, intenta nuevamente.', icon: 'error', confirmButtonText: 'OK'});
            }
        });

    });

});

function actualizarTarea(data) {
    var request = indexedDB.open('DBAppSDV', 1);

    request.onsuccess = function (event) {
        var db = event.target.result;
        var transaction = db.transaction(['tbl_tareas'], 'readwrite');
        var objectStore = transaction.objectStore('tbl_tareas');

        var getRequest = objectStore.get(data.tarea_id);
        getRequest.onsuccess = function (event) {
            var existingData = event.target.result;
            if (existingData) {
                for (var prop in data) {
                    existingData[prop] = data[prop];
                }

                var updateRequest = objectStore.put(existingData);

                updateRequest.onsuccess = function () {
                    cola();
                    Swal.fire({type: 'success', title: 'La tarea se ha actualizado correctamente.', showConfirmButton: true, allowOutsideClick: false}).then((result) => {
                        if (result) {
                            resetForm();
                            // $('#assignModal').modal("toggle");
                        }
                    });


                    // Cerrar el modal:
                    // $('#completeTaskModal').modal("toggle");
                    // resetForm();
                };

                updateRequest.onerror = function () {
                    console.log('Error al actualizar datos en IndexedDB');
                };
            } else {
                console.log('Registro no encontrado en IndexedDB');
            }
        };

        getRequest.onerror = function () {
            console.log('Error al obtener datos de IndexedDB');
        };
    };

    request.onerror = function () {
        console.log('Error al abrir la base de datos');
    };
}
// fin de asignar tarea=
// // Función para inicializar el mapa
function initMap() {
    var map = L.map('map').setView([
        0, 0
    ], 2);
    // Establece la vista inicial del mapa
    //     // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);
    //     // Evento de clic en el mapa para obtener las coordenadas
    map.on('click', function (e) {
        document.getElementById('latitud').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitud').value = e.latlng.lng.toFixed(6);
    });
    //     // Función para actualizar las coordenadas a la ubicación actual del usuario
    document.getElementById('actualizar').addEventListener('click', function () {
        map.locate({setView: true, maxZoom: 16});
    });
    //     // Cuando encontramos la ubicación del usuario, actualizamos las coordenadas
    map.on('locationfound', function (e) {
        document.getElementById('latitud').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitud').value = e.latlng.lng.toFixed(6);
    });
}
// Asignar las tareas
function extraerPrimerRutas(callback) {
    var dbName = 'DBAppSDV';
    var request = indexedDB.open(dbName);

    request.onsuccess = function (event) {
        var db = event.target.result;
        var transaction = db.transaction(['tbl_usuarios'], 'readonly');
        var usuariosStore = transaction.objectStore('tbl_usuarios');

        // Abre un cursor para recorrer los registros.
        var cursorRequest = usuariosStore.openCursor();

        cursorRequest.onsuccess = function (event) {
            var cursor = event.target.result;
            if (cursor) {
                if (cursor.value && cursor.value.ls_rutas) {
                    if (typeof callback === 'function') {
                        callback(cursor.value.ls_rutas);
                        
                    } else { // console.log("El callback proporcionado no es una función");
                    }
                } else {
                    if (typeof callback === 'function') {
                        callback(null);
                    }
                }
            }
        };

        cursorRequest.onerror = function (event) {
            console.error("Error al acceder al cursor:", event.target.error);
        };
    };

    request.onerror = function (event) {
        console.error('Error al abrir la base de datos:', event.target.errorCode);
    };
}

// Mostrar las tareas al usuario
function mostrarTareasEnModal() {
    obtenerDatosUsuarios().then(function (usuarios) {
        var usuarioActual = usuarios[0]; // Suponiendo que solo hay un usuario en la tabla

        var dbName = 'DBAppSDV';
        var dbVersion = 1;
        var request = indexedDB.open(dbName, dbVersion);

        request.onsuccess = function (event) {
            var db = event.target.result;

            // Paso 1: Obtener datos de Mercado
            var mercadoTransaction = db.transaction(['tbl_mercado'], 'readonly');
            var mercadoStore = mercadoTransaction.objectStore('tbl_mercado');
            var mercadoRequest = mercadoStore.getAll();

            mercadoRequest.onsuccess = function () {
                var mercadoData = mercadoRequest.result;

                // Paso 2: Obtener datos de Tareas
                var tareasTransaction = db.transaction(['tbl_tareas'], 'readonly');
                var tareasStore = tareasTransaction.objectStore('tbl_tareas');
                var tareasRequest = tareasStore.getAll();

                tareasRequest.onsuccess = function () {
                    var tareasData = tareasRequest.result;

                    // Filtrar tareas con estado de 1
                    var tareasFiltradas = tareasData.filter(tarea => tarea.estado === '1');

                    // Si el usuario tiene privilegio 2, filtrar por us_ID_Ruta
                    if (usuarioActual.privilegio === '2') {
                        tareasFiltradas = tareasFiltradas.filter(tarea => tarea.ruta_asignada === usuarioActual.us_ID_Ruta);
                    }

                    var combinedData = tareasFiltradas.map(function (tarea) {
                        var mercadoRelacionado = mercadoData.find(m => m.hash === tarea.hash);
                        if (mercadoRelacionado) {
                            return {tarea: tarea, mercado: mercadoRelacionado};
                        } else {
                            return null;
                        }
                    }).filter(item => item !== null);
                    // Paso 3: Mostrar los datos combinados en el modal
                    $('#contenedorDeTareasC').empty();
                    combinedData.forEach(function (data) {
                        var estado;
                        var estadoClass;
                        var $card = $('<div/>', {
                            class: "card " + estadoClass + " mb-3 mx-auto",
                            style: "width: 18rem;"
                        });

                        var $cardBody = $('<div/>', {class: "card-body"});
                        $cardBody.append($('<h5/>', {class: 'card-title'}).text(data.mercado.nombreEstablecimiento + ": " + data.mercado.codigoCliente));
                        // data.mercado.nombreEstablecimiento + ": " + data.mercado.search-box6));

                        $cardBody.append($('<h6/>', {class: 'card-subtitle mb-2 text-muted'}).text(data.tarea.fecha));
                        $cardBody.append($('<p/>', {class: 'card-text'}).html('<strong>' + data.mercado.n_oportunidad + '</strong>' + " - " + data.tarea.comentario));

                        var $actions = $('<div/>', {class: 'actions d-flex justify-content-center align-items-center'});
                        $actions.append($('<button/>', {
                            class: 'btn btn-success btn-completar-tarea',
                            'data-id': data.tarea.tarea_id,
                            'data-tarea': data.mercado.nombreEstablecimiento + ": " + data.mercado.n_oportunidad + ", " + data.tarea.comentario
                        }).html('<i class="fas fa-check-circle"></i> Completar Tarea'));

                        $cardBody.append($actions);
                        $card.append($cardBody);

                        $('#contenedorDeTareasC').append($card);
                    });

                    $("#tareasModal").modal('show');

                };

                tareasTransaction.onerror = function (event) {
                    console.error('Error leyendo de tbl_tareas:', event.target.errorCode);
                };
            };

            mercadoTransaction.onerror = function (event) {
                console.error('Error leyendo de tbl_mercado:', event.target.errorCode);
            };
        };

        request.onerror = function (event) {
            console.error('Error abriendo base de datos:', event.target.errorCode);
        };
    }).catch(function (error) {
        console.error('Error obteniendo datos del usuario:', error);
    });
}

// complatar las tareas
function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(',')[1]); // Retorna solo la parte de Base64.
        reader.onerror = error => reject(error);
        reader.readAsDataURL(file);
    });
}
// usuario
function obtenerDatosUsuarios() {
    return new Promise(function (resolve, reject) {
        var usuarios = []; // Array para acumular los datos de los usuarios

        var request = indexedDB.open('DBAppSDV', 1);

        request.onsuccess = function (event) {
            var db = event.target.result;
            var transaction = db.transaction(['tbl_usuarios'], 'readonly');
            var objectStore = transaction.objectStore('tbl_usuarios');
            var request = objectStore.openCursor();

            request.onsuccess = function (event) {
                var cursor = event.target.result;
                if (cursor) {
                    var userData = cursor.value;
                    // Aquí puedes modificar los datos si lo necesitas
                    usuarios.push(userData); // Añadimos los datos al array
                    cursor.continue();
                } else {
                    resolve(usuarios); // Resolvemos la promesa devolviendo el array de usuarios
                }
            };

            request.onerror = function (event) {
                console.error('Error al acceder a los datos del usuario:', event.target.error);
                reject(event.target.error);
            };
        };

        request.onerror = function (event) {
            console.error('Error al abrir la base de datos:', event.target.error);
            reject(event.target.error);
        };
    });
}
// ocultar según privilegio
function ocultarElementosSegunPrivilegio() {
    obtenerDatosUsuarios().then(function (usuarios) {
        var usuarioActual = usuarios[0];
        // Suponiendo que solo hay un usuario en la tabla
        if (usuarioActual.privilegio === '2') { // Ocultar "Crear Encuesta" y "Asignación de Tareas" para usuarios con privilegio 2
            console.log('Usuario con permiso');
            $('#nuevaEncuesta').closest('.card').hide();
            $('#btnAsignar').closest('.card').hide();
            contarTareas(usuarioActual);
        }
    }).catch(function (error) {
        console.error('Error obteniendo datos del usuario:', error);
    });
}

// Ejecuta la función al cargar la página
$(document).ready(function () {
    ocultarElementosSegunPrivilegio();
});

// Validación de formulario
function validarFormulario() {
    const form = document.getElementById('myForm');
    const inputs = form.querySelectorAll('input[type="text"], textarea');

    // Limpia clases de error previamente añadidas
    for (let input of form.querySelectorAll('.error-input')) {
        input.classList.remove('error-input');
    }

     // Función para enfocar un elemento con un pequeño retraso
     function enfocarElementoConRetraso(elemento) {
        setTimeout(() => {
            elemento.focus();
        }, 200); // Aumenta el retraso para asegurarte de que otros scripts hayan terminado
    }

    // Validación de campos de texto
    for (let input of inputs) {
        if (input.value.trim() === "") {
            input.classList.add('error-input');
            Swal.fire('Error', 'Por favor, rellene todos los campos antes de enviar.', 'error')
                .then(() => {
                    enfocarElementoConRetraso(input); // Aumenta el retraso aquí
                });
            return false;
        }
    }

    // Validación para fotos
    const photo1Input = document.getElementById("file_foto_u");
    const photo2Input = document.getElementById("file_foto_d");
    const photo3Input = document.getElementById("file_foto_t");

    if (!photo1Input.files.length && !photo2Input.files.length && !photo3Input.files.length) {
        photo1Input.classList.add('error-input');
        photo2Input.classList.add('error-input');
        photo3Input.classList.add('error-input');
        Swal.fire('Error', 'Por favor, seleccione al menos una foto.', 'error')
            .then(() => enfocarElementoConRetraso(photo1Input));
        return false;
    }

    // Validación de radio buttons
    const radioGroups = [
        "yummies",
        "fritolays",
        "diana",
        "barcel",
        "senorial",
        "ricaSula",
        "tropical",
        "bocadeli",
        "nutriva",
        "pindi",
        "esta",
        "botanis",
        "bocadeliExhibidorPrincipal",
        "bocadeliExhibidorAdicional",
        "bocadeliExhibicionAdecuada",
        "bocadeliPosicionDominante",
        "bocadeliPop",
        "nutrivaExhibidorPrincipal",
        "nutrivaExhibidorAdicional",
        "nutrivaExhibicionAdecuada",
        "galletaExhibidorPrincipal",
        "galletaExhibidorAdicional",
        "galletaExhibicionAdecuada",
        "dianaCompra",
        "fritoLayCompra",
        "yummiesCompra"
        // y cualquier otro grupo de radio buttons que necesites validar
    ];

    for (let groupName of radioGroups) {
        const radioGroup = form.querySelectorAll(`input[name="${groupName}"]`);
        const isVisible = radioGroup[0].offsetParent !== null;
        if (isVisible) {
            const selectedRadio = form.querySelector(`input[name="${groupName}"]:checked`);
            if (!selectedRadio) {
                for (let radio of radioGroup) {
                    radio.classList.add('error-input'); 
                }
                Swal.fire('Error', `Por favor, seleccione una opción para:  ${groupName.toUpperCase()}.`, 'error')
                    .then(() => {
                        enfocarElementoConRetraso(radioGroup[0]);
                    });
                return false;
            }
        }
    }

     // Validación de menú desplegable
     const selectElement = document.getElementById('listaOportunidades');
     if (selectElement.value === "") {
         selectElement.classList.add('error-input');
         Swal.fire('Error', 'Por favor, seleccione una opción de la lista de oportunidades.', 'error')
             .then(() => enfocarElementoConRetraso(selectElement));
         return false;
     }

    return true; // Si todas las validaciones son correctas
}





// validar asignar tarea
function validarAsignarFormulario() {
    const form = document.getElementById('assignForm');

    // Limpiar clases de error previamente añadidas
    for (let input of form.querySelectorAll('.error-input')) {
        input.classList.remove('error-input');
    }

    // Validación del campo de ruta
    const rutaInput = document.getElementById("search-box5");
    const valorRuta = rutaInput.value.trim();

    if (valorRuta === "") {
        rutaInput.classList.add('error-input'); // Añadir clase de error
        Swal.fire('Error', 'Por favor, ingrese la ruta.', 'error');
        rutaInput.focus();
        return false;
    }
    // Validación: Verificar si el valor de ruta no está en la lista rutasList
    var rutaEnLista = rutasList.some(ruta => ruta.Ru_Id.toString() === valorRuta);

    if (!rutaEnLista) {
        rutaInput.classList.add('error-input'); // Añadir clase de error
        Swal.fire('Error', 'La ruta ingresada no es válida.', 'error');
        rutaInput.focus();
        return false;
    }

    return true;
}


// Validar Completar la tarea
function validarCompleteTaskFormulario() {
    const form = document.getElementById('completeTaskForm');

    // Limpiar clases de error previamente añadidas
    for (let input of form.querySelectorAll('.error-input')) {
        input.classList.remove('error-input');
    }

    // Validación para fotos: al menos una foto debe ser seleccionada
    const photo1Input = document.getElementById("photo1");
    const photo2Input = document.getElementById("photo2");
    const photo3Input = document.getElementById("photo3");

    if (! photo1Input.files.length && ! photo2Input.files.length && ! photo3Input.files.length) {
        photo1Input.classList.add('error-input'); // Añadir clase de error
        photo2Input.classList.add('error-input');
        photo3Input.classList.add('error-input');
        Swal.fire('Error', 'Por favor, seleccione al menos una foto.', 'error');
        return false;
    }

    return true;
}
async function reduceResolution(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = function (event) {
            const img = new Image();
            img.onload = function () {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                let width = 800;
                let height = Math.round((img.height * width) / img.width);
                canvas.width = width;
                canvas.height = height;

                ctx.drawImage(img, 0, 0, width, height);

                // Usar el tipo MIME del archivo original
                let base64String = canvas.toDataURL(file.type, 0.8).split(',')[1];
                resolve(base64String);
            };

            img.onerror = function () {
                reject(new Error("Error loading image."));
            };

            img.src = event.target.result;
        };

        reader.onerror = function (error) {
            reject(error);
        };

        reader.readAsDataURL(file);
    });
}
// Enviar la cola de las tareas
// Enviar la cola de las tareas
// Enviar la cola de las tareas
function objetoAFormData(obj) {
    var formData = new FormData();
    for (var key in obj) {
        formData.append(key, obj[key]);
    }
    return formData;
}
function enviar_cola_cti() {
    var request = indexedDB.open('DBAppSDV', 1);
    request.onsuccess = function (event) {
        var db = event.target.result;
        var contador = 0;
        var getAllRequest = db.transaction(['tbl_mercado'], 'readonly').objectStore('tbl_mercado').getAll();
        getAllRequest.onsuccess = function () {
            var registros = getAllRequest.result;

            // contador
            registros.forEach(function (registro) {
                if (registro.enviado !== "NO") {
                    return;
                } else {
                    contador = contador + 1;
                }
            });
            // fin de contar

            if (contador == 0) {
                actualizar_cola_tareas();
                enviar_cola_tareas();
                Swal.fire('Aviso!!', 'No Existen Datos en cola', 'question')
                return;
            }
            registros.forEach(function (registro) { // Si el registro ya fue enviado (es decir, `enviado` no es "NO"), saltar al siguiente
                if (registro.enviado !== "NO") {
                    return;
                } else {
                    var registroFormData = objetoAFormData(registro);
                    $.ajax({
                        type: 'post',
                        url: 'C_mercado/Ctr_mercado/guardar_formulario',
                        data: registroFormData,
                        processData: false,
                        contentType: false,
                        dataType: "JSON" // Si esperas una respuesta JSON del servidor, sería útil especificar el tipo aquí.
                    }).done(function (response) { // Actualiza el campo enviado a "SI"
                        registro.enviado = "SI";

                        // Aquí abrimos una nueva transacción para la actualización
                        var updateTransaction = db.transaction(['tbl_mercado'], 'readwrite');
                        var updateRequest = updateTransaction.objectStore('tbl_mercado').put(registro);

                        updateRequest.onsuccess = function () {
                            var countTransaction = db.transaction(['tbl_mercado'], 'readonly');
                            var countRequest = countTransaction.objectStore('tbl_mercado').count();
                            enviar_cola_tareas();
                            actualizar_cola_tareas();
                            cola();

                            countRequest.onsuccess = function () { // contarRegistrosNoEnviados();
                            };
                        };

                        updateRequest.onerror = function () {
                            console.log('Error al actualizar el campo enviado en IndexedDB');
                        };
                    }).always(function (response, textStatus, errorThrown) {
                        if (textStatus != "success") {
                            Swal.fire({icon: 'error', title: '¡Error!', text: 'Hubo un problema al sincronizar los registros.'});
                        }
                    });
                }

            });

            Swal.fire('¡Exito!', 'Registros sincronizados.', 'success');

        };
    };

    request.onerror = function () {
        console.log('Error al abrir la base de datos');
    };
}
// envia las tareas creadas que no existen en el servidor
function enviar_cola_tareas() {
    var request = indexedDB.open('DBAppSDV', 1);
    var contador = 0;
    request.onsuccess = function (event) {
        var db = event.target.result;
        var getAllRequest = db.transaction(['tbl_tareas'], 'readonly').objectStore('tbl_tareas').getAll();

        getAllRequest.onsuccess = function () {
            var registros = getAllRequest.result;

            registros.forEach(function (registro) {
                if (registro.enviado !== "NO") {
                    return;
                } else {
                    contador = contador + 1;
                }
            });
            if (contador == 0) {
                Swal.fire('Aviso!!', 'No Existen Datos en cola para tareas', 'question')
                return;
            }
            registros.forEach(function (registro) { // Si el registro ya fue enviado (es decir, `enviado` no es "NO"), saltar al siguiente
                if (registro.enviado !== "NO") {
                    return;
                } else {
                    var registroFormData = objetoAFormData(registro);
                    $.ajax({
                        type: 'post',
                        url: 'C_mercado/Ctr_mercado/crear_tarea_s',
                        data: registroFormData,
                        processData: false,
                        contentType: false,
                        success: function () { // Actualiza el campo enviado a "SI"
                            registro.enviado = "SI";

                            // Aquí abrimos una nueva transacción para la actualización
                            var updateTransaction = db.transaction(['tbl_tareas'], 'readwrite');
                            var updateRequest = updateTransaction.objectStore('tbl_tareas').put(registro);
                            updateRequest.onsuccess = function () {};
                            updateRequest.onerror = function () { // console.log('Error al actualizar el campo enviado en IndexedDB para tareas');
                            };
                        },
                        error: function () { //    console.log('Error al enviar tarea al servidor');
                        }
                    });
                }
            });

            Swal.fire('¡Exito!', 'Tareas sincronizadas.', 'success');
        };
    };

    request.onerror = function () {
        console.log('Error al abrir la base de datos');
    };
}
// actualizaciones de las tareas existentes en el servidor
function actualizar_cola_tareas() {
    var request = indexedDB.open('DBAppSDV', 1);
    var contador = 0;
    request.onsuccess = function (event) {
        var db = event.target.result;
        var getAllRequest = db.transaction(['tbl_tareas'], 'readonly').objectStore('tbl_tareas').getAll();

        getAllRequest.onsuccess = function () {
            var registros = getAllRequest.result;

            registros.forEach(function (registro) {
                if (registro.ac_enviado !== "NO") {
                    return;
                } else {
                    contador = contador + 1;
                }
            });

            if (contador == 0) {
                Swal.fire('Aviso!!', 'No Existen Datos en cola para tareas', 'question')
                return;
            }

            registros.forEach(function (registro) { // Si el registro ya fue enviado (es decir, `enviado` no es "NO"), saltar al siguiente
                if (registro.ac_enviado !== "NO") {
                    return;
                } else {
                    var registroFormData = objetoAFormData(registro);
                    for (var pair of registroFormData.entries()) {}
                    $.ajax({
                        type: 'post',
                        url: 'C_mercado/Ctr_mercado/sincronizarTareas',
                        data: registroFormData,
                        processData: false,
                        contentType: false,
                        success: function () { // Actualiza el campo enviado a "SI"
                            registro.ac_enviado = "SI";
                            // Aquí abrimos una nueva transacción para la actualización
                            var updateTransaction = db.transaction(['tbl_tareas'], 'readwrite');
                            var updateRequest = updateTransaction.objectStore('tbl_tareas').put(registro);

                            updateRequest.onsuccess = function () {
                                console.log('Campo enviado actualizado en IndexedDB para tareas');
                            };

                            updateRequest.onerror = function () {
                                console.log('Error al actualizar el campo enviado en IndexedDB para tareas');
                            };
                        },
                        error: function () {
                            console.log('Error al enviar tarea al servidor');
                        }
                    });
                }
            });

            Swal.fire('¡Exito!', 'Tareas sincronizadas.', 'success');
        };
    };

    request.onerror = function () {
        console.log('Error al abrir la base de datos');
    };
}
// fin de enviar la cola de las tareas
// fin de enviar la cola de las tareas
// fin de enviar la cola de las tareas
function resetForm() { // Limpia el formulario
    $('form')[0].reset();

    // Limpia la vista previa de la imagen
    $('.image-preview').empty();

    // Recarga la página y vuelve al inicio
    window.location.reload();

    // Fuerza el desplazamiento al inicio de la página
    window.scrollTo(0, 0);
}
// Contar los registros no enviados
// Contar los registros no enviados
function cola() {
    var request = indexedDB.open('DBAppSDV', 1);
    var contador = 0;
    request.onsuccess = function (event) {
        var db = event.target.result;
        var getAllRequest = db.transaction(['tbl_mercado'], 'readonly').objectStore('tbl_mercado').getAll();
        getAllRequest.onsuccess = function () {
            var registros = getAllRequest.result;
            registros.forEach(function (registro) {
                if (registro.enviado !== "NO") {
                    return;
                } else {
                    contador = contador + 1;
                }
            });
            if (contador == 0) {
                contarTareas();

            } else { // ¡Aquí es donde debes actualizar el contenido del elemento!
                document.getElementById('RegisCola').textContent = contador;
            }
        }
    }
}
// fin de contar los registros no enviados
function contarTareas(usuarioActual) {
    var request = indexedDB.open('DBAppSDV', 1);
    var contadorTareas = 0;

    request.onsuccess = function (event) {
        var db = event.target.result;
        var getAllRequest = db.transaction(['tbl_tareas'], 'readonly').objectStore('tbl_tareas').getAll();

        getAllRequest.onsuccess = function () {
            var registros = getAllRequest.result;
            registros.forEach(function (registro) {
                if (registro.enviado !== "NO") {
                    return;
                } else {
                    contadorTareas = contadorTareas + 1;
                }
            });
            if (contadorTareas == 0) {
                contarTareasAc();
            } else {
                document.getElementById('RegisCola').textContent = contadorTareas;
            }

        }
    }
}

function contarTareasAc(usuarioActual) {
    var request = indexedDB.open('DBAppSDV', 1);
    var contadorTareas = 0;

    request.onsuccess = function (event) {
        var db = event.target.result;
        var getAllRequest = db.transaction(['tbl_tareas'], 'readonly').objectStore('tbl_tareas').getAll();

        getAllRequest.onsuccess = function () {
            var registros = getAllRequest.result;
            registros.forEach(function (registro) {
                if (registro.enviado !== "NO") {
                    return;
                } else {
                    contadorTareas = contadorTareas + 1;
                }
            });
            document.getElementById('RegisCola').textContent = contadorTareas;
        }
    }
}


function previewImage(inputId, imageId) {
    const input = document.getElementById(inputId);
    const image = document.getElementById(imageId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            image.src = e.target.result;
            image.style.display = 'block'; // Mostrar la imagen previa
            image.style.width = '100px'; // Establecer un ancho fijo
            image.style.height = '100px'; // Establecer una altura fija
            image.style.margin = 'auto'; // Centrar horizontalmente
            image.style.verticalAlign = 'middle'; // Centrar verticalmente
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        image.src = '';
        image.style.display = 'none'; // Ocultar la imagen previa si no se selecciona ningún archivo
    }
}
