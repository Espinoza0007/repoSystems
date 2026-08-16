var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var DataTB = '';
var blockF=0;
var B1=0;
var arrg_vali_result = [];
var dataBaseCola = null;
var dataBase = null;
var map;
var marker;
var warn_on_unload='';
var arreg_offline = [];

function cerrar_cargas(errormsj,tipo){

    $("#btn-enviar-coord").hide(100,function(){
        $("#btn-enviar").show(100,function(){
            $("#btn-enviar-coord").text('Enviando, por favor espere....');
        });
    });

    $("#btn-formopciones").show(150,function(){
        $("#btn-formodetalles").show(150,function(){
            $("#img-carga").hide(150,function(){

            });//CIERRE IMG-CARGA
        });//CIERRE BTN-FORMDETALLES
    });//CIERRE BTN-FORM-OPCIONES
}

function cerrar_cargas_mensaje(errormsj,tipo){

    $("#btn-enviar-coord").hide(100,function(){
        $("#btn-enviar").show(100,function(){
            $("#btn-enviar-coord").text('Enviando, por favor espere....');
        });
    });

    $("#btn-formopciones").show(150,function(){
        $("#btn-formodetalles").show(150,function(){
            $("#img-carga").hide(150,function(){
                if(tipo == 1){
                    Swal.fire({
                        title: '<strong>'+errormsj+'</strong>',
                        type: 'error',
                        html:'',
                        confirmButtonText:'Ok'
                    });
                }else{

                }
            });//CIERRE IMG-CARGA
        });//CIERRE BTN-FORMDETALLES
    });//CIERRE BTN-FORM-OPCIONES
}


function cerrar_cargas_INFO(errormsj,tipo){

    $("#btn-enviar-coord").hide(100,function(){
        $("#btn-enviar").show(100,function(){
            $("#btn-enviar-coord").text('Enviando, por favor espere....');
        });
    });

    $("#btn-formopciones").show(150,function(){
        $("#btn-formodetalles").show(150,function(){
            $("#img-carga").hide(150,function(){
                if(tipo == 1){
                    Swal.fire({
                        title: '<strong>'+errormsj+'</strong>',
                        type: 'info',
                        html:'',
                        confirmButtonText:'Ok'
                    });
                }else{

                }
            });//CIERRE IMG-CARGA
        });//CIERRE BTN-FORMDETALLES
    });//CIERRE BTN-FORM-OPCIONES
}


function cerrar_cargas_ok(errormsj,tipo){

    $("#btn-enviar-coord").hide(100,function(){
        $("#btn-enviar").show(100,function(){
            $("#btn-enviar-coord").text('Enviando, por favor espere....');
        });
    });

    $("#btn-formopciones").show(150,function(){
        $("#btn-formodetalles").show(150,function(){
            $("#img-carga").hide(150,function(){
                if(tipo == 1){
                    Swal.fire({
                        title: '<strong>'+errormsj+'</strong>',
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1100
                    });
                }else{

                }
            });//CIERRE IMG-CARGA
        });//CIERRE BTN-FORMDETALLES
    });//CIERRE BTN-FORM-OPCIONES
}

function check_conn_lat_log() {
    // alert('comprobarconexion');
    $("#btn-enviar-coord").text('Cargando por favor espere...');
    $("#btn-enviar").hide(100,function(){
        $("#btn-enviar-coord").show(100);
    });
    $("#btn-formopciones").hide(150,function(){
        $("#btn-formodetalles").hide(150,function(){
            $("#img-carga").show(150,function(){
                $.ajax({
                    url:'comprobarconexion/resultconexion',
                    type:"POST",
                    data:{"pin":'conexions'},
                    dataType: "JSON",
                    timeout:7777
                }).done(function(resul) {
                    conn = true;
                    cerrar_cargas('',0);
                    // console.log('TRUE CONEXION FORMULARO');
                    borrar_DB_DBAC();
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // alert('pagina error');
                    conn = false;
                    if ( textStatus === 'timeout'){
                        // console.log('Error de tiempo de espera...');
                        Levantar_startDB_AC_CLI_CON_CONN();
                        cerrar_cargas('Error de tiempo de espera.',1);
                        // startDBsin();
                    }else if (jqXHR.status === 0) {
                        // console.log('Sin conexion a internet...');
                        Levantar_startDB_AC_CLI_CON_CONN();
                        cerrar_cargas('Sin conexion a internet...',1);
                        // startDBsin();
                    } else if (jqXHR.status == 404) {
                        // console.log('Página solicitada no encontrada[404]');
                        cerrar_cargas_mensaje('Página solicitada no encontrada[404]',1);
                        // retorna_inicio();
                    } else if (jqXHR.status == 500) {
                        // console.log('Error de servidor interno [500].');
                        cerrar_cargas_mensaje('Error de servidor interno [500].',1);
                        // retorna_inicio();
                    } else if (textStatus === 'parsererror') {
                        //BLOQUE DEL ACL
                        //RESTABLECER SESION
                        // console.log('El análisis JSON solicitado falló POSIBLE SESION EXPIRADA [FALLO SINCRONIZACION].');
                        actualizar_sesion_login();
                        cerrar_cargas('...',1);
                        // retorna_inicio();
                    } else if (textStatus === 'abort') {
                        // console.log('Solicitud de Ajax abortada.');
                        cerrar_cargas_mensaje('Solicitud de Ajax abortada.',1);
                        // retorna_inicio();
                    } else {
                        // console.log('Error no detectado: ' + jqXHR.responseText);
                        cerrar_cargas_mensaje('Error no detectado: ' + jqXHR.responseText,1);
                        // retorna_inicio();
                    }
                });
            });

        });
    });
}


function actualizar_sesion_login(){
    // alert($("#usc").val());
    $.ajax({
        url:"login/iniciosesion",
        type:"POST",
        data:{usuario:$("#usc").val(),contrasena:$("#usc").val()},
        dataType: "JSON",
        timeout:7777
    }).done(function(respuesta) {
        if(respuesta.rs==false){
            // alert('tiandome para atras');
            location.href = "../";
            // alert(respuesta);
        }else{
            // console.log('<<SESION ACTUALIZADA CON EXITO>>');
            $("#btn-enviar-coord").hide(100,function(){
                $("#btn-enviar").show(100,function(){
                    $("#btn-enviar-coord").text('Enviando, por favor espere...');
                });
            });
            $("#btn-formopciones").show(150,function(){
                $("#btn-formodetalles").show(150,function(){
                    $("#img-carga").hide(150,function(){
                        borrar_DB_DBAC();
                    });
                });
            });
        }
    }).fail(function() {
        $("#spinner-load").hide(150,function(){
            // console.log('<<[WARNING]>> SE PERDIO CONEXION CON EL SERVIDOR')
            Swal.fire({
                title: '<strong></strong>',
                type: 'error',
                html:'',
                confirmButtonText:'Ok'
            });
        });
    });
}

function actualizar_sesion_login_carg_cli(){
    // alert($("#usc").val());
    $.ajax({
        url:"login/iniciosesion",
        type:"POST",
        data:{usuario:$("#usc").val(),contrasena:$("#usc").val()},
        dataType: "JSON",
        timeout:7777
    }).done(function(respuesta) {
        if(respuesta.rs==false){
            // alert('tiandome para atras');
            location.href = "../";
            // alert(respuesta);
        }else{
            // console.log('<< SESION ACTUALIZADA CARGAR DE CLIENTES >>');
            limpiar_clientes();
        }
    }).fail(function() {
        $("#spinner-load").hide(150,function(){
            // console.log('<<[WARNING]>> SE PERDIO CONEXION CON EL SERVIDOR');
            Swal.fire({
                title: '<strong></strong>',
                type: 'error',
                html:'',
                confirmButtonText:'Ok'
            });
        });
    });
}

function actualizar_sesion_login_carg_cli_AC(){
    // alert($("#usc").val());
    $.ajax({
        url:"login/iniciosesion",
        type:"POST",
        data:{usuario:$("#usc").val(),contrasena:$("#usc").val()},
        dataType: "JSON",
        timeout:7777
    }).done(function(respuesta) {
        if(respuesta.rs==false){
            // alert('tiandome para atras');
            location.href = "../";
            // alert(respuesta);
        }else{
            // console.log('<< SESION ACTUALIZADA CARGAR DE CLIENTES >>');
            cargar_clientes_ruta_AC(1);
        }
    }).fail(function() {
        $("#spinner-load").hide(150,function(){
            // console.log('<<[WARNING]>> SE PERDIO CONEXION CON EL SERVIDOR');
            Swal.fire({
                title: '<strong></strong>',
                type: 'error',
                html:'',
                confirmButtonText:'Ok'
            });
        });
    });
}

function check_conn_todo_cli() {


    $("#btn-enviar-coord").text('Cargando por favor espere...');
    $("#btn-enviar").hide(100,function(){
        $("#btn-enviar-coord").show(100);
    });
    $("#btn-formopciones").hide(150,function(){
        $("#btn-formodetalles").hide(150,function(){
            $("#img-carga").show(150,function(){

                $.ajax({
                    url:'comprobarconexion/resultconexion',
                    type:"POST",
                    data:{"pin":'conexions'},
                    dataType: "JSON",
                    timeout:7777
                }).done(function(resul) {
                    limpiar_clientes();
                    // startDB_AC_CLI_CON_CONN();
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // alert('pagina error');
                    conn = false;
                    if ( textStatus === 'timeout'){
                        // console.log('Error de tiempo de espera.');
                        // cerrar_cargas('Error de tiempo de espera.',1);
                        startDB_AC_CLI_SIN_CONN();
                    }else if (jqXHR.status === 0) {
                        // console.log('Sin conexion a internet...');
                        // cerrar_cargas('Sin conexión a internet...',1);
                        startDB_AC_CLI_SIN_CONN();
                    } else if (jqXHR.status == 404) {
                        // console.log('Página solicitada no encontrada[404]');
                        cerrar_cargas('Página solicitada no encontrada[404]',1);
                    } else if (jqXHR.status == 500) {
                        // console.log('Error de servidor interno [500].');
                        cerrar_cargas('Error de servidor interno [500].',1);
                    } else if (textStatus === 'parsererror') {
                        //BLOQUE DEL ACL
                        //RESTABLECER SESION
                        // console.log('El análisis JSON solicitado falló POSIBLE SESION EXPIRADA [FALLO SINCRONIZACION].');
                        actualizar_sesion_login_carg_cli();
                        // retorna_inicio();
                    } else if (textStatus === 'abort') {
                        // console.log('Solicitud de Ajax abortada.');
                        cerrar_cargas('Solicitud de Ajax abortada.',1);
                    } else {
                        // console.log('Error no detectado: ' + jqXHR.responseText);
                        cerrar_cargas('Error no detectado: ' + jqXHR.responseText,1);
                    }
                });
            });//CIERRE IMG-CARGA
        });///CIERRE FORMDETALLE
    });//CIRRE FORM-OPCIONES
}


function check_conn_todo_cli_AC() {


    $("#btn-enviar-coord").text('Cargando por favor espere...');
    $("#btn-enviar").hide(100,function(){
        $("#btn-enviar-coord").show(100);
    });
    $("#btn-formopciones").hide(150,function(){
        $("#btn-formodetalles").hide(150,function(){
            $("#img-carga").show(150,function(){

                $.ajax({
                    url:'comprobarconexion/resultconexion',
                    type:"POST",
                    data:{"pin":'conexions'},
                    dataType: "JSON",
                    timeout:7777
                }).done(function(resul) {
                    cargar_clientes_ruta_AC(1);
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // alert('pagina error');
                    conn = false;
                    if ( textStatus === 'timeout'){
                        // console.log('Error de tiempo de espera.');
                        cerrar_cargas_mensaje('Error de tiempo de espera<br>Conexón Lenta.',1);
                    }else if (jqXHR.status === 0) {
                        // console.log('Sin conexion a internet...<br>Esta opción solo funciona con internet.');
                        cerrar_cargas_mensaje('Sin conexión a internet...<br>Esta opción solo funciona con internet.',1);
                    } else if (jqXHR.status == 404) {
                        // console.log('Página solicitada no encontrada[404]');
                        cerrar_cargas_mensaje('Página solicitada no encontrada[404]',1);
                    } else if (jqXHR.status == 500) {
                        // console.log('Error de servidor interno [500].');
                        cerrar_cargas_mensaje('Error de servidor interno [500].',1);
                    } else if (textStatus === 'parsererror') {
                        //BLOQUE DEL ACL
                        //RESTABLECER SESION
                        // console.log('El análisis JSON solicitado falló POSIBLE SESION EXPIRADA [FALLO SINCRONIZACION].');
                        actualizar_sesion_login_carg_cli_AC();
                        // retorna_inicio();
                    } else if (textStatus === 'abort') {
                        // console.log('Solicitud de Ajax abortada.');
                        cerrar_cargas_mensaje('Solicitud de Ajax abortada.',1);
                    } else {
                        // console.log('Error no detectado: ' + jqXHR.responseText);
                        cerrar_cargas_mensaje('Error no detectado: ' + jqXHR.responseText,1);
                    }
                });

            });//CIERRE IMG-CARGA
        });///CIERRE FORMDETALLE
    });//CIRRE FORM-OPCIONES

}


      function init(latitud,longitud,direccion) {

        map = new L.Map('map');
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 20
        }).addTo(map);
        map.attributionControl.setPrefix('SDV Bocadeli');
        map.setView(new L.LatLng(latitud, longitud),15);
        L.marker([latitud,longitud]).addTo(map)
        .bindPopup('<strong>'+direccion+'</strong>')
        .openPopup();

        console.log('inicializando el mapa pofavo alfedo');
      }

      function onLocationFound(e) {
        var radius = e.accuracy / 2;
        var location = e.latlng;

        var greenIcon = new L.Icon({
          iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });


        if (marker != undefined) {
            map.removeLayer(marker);
        }
        marker = new L.Marker(e.latlng, {draggable:false,icon: greenIcon});
        map.addLayer(marker);
        // document.getElementById('latitud').value = e.latlng.lat;
        // document.getElementById('longitud').value = e.latlng.lng;


        $("#txtlatitud").val(e.latlng.lat);
        $("#txtlatitudm").val(e.latlng.lat);
        $("#txtlongitud").val(e.latlng.lng);
        $("#txtlongitudm").val( e.latlng.lng);



        V_NumeroEnteroDecimalpogps($("#txtlatitud").val(),'txtlatitudm',4,'Latitud');
        V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',5,'Longitud');

      }

      function onLocationError(e) {
        // alert(e.message);
        $("#txtlatitud").val(0);
        $("#txtlatitudm").val(0);
        $("#txtlongitud").val(0);
        $("#txtlongitudm").val(0);
        V_NumeroEnteroDecimalpogps($("#txtlatitud").val(),'txtlatitudm',4,'Latitud');
        V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',5,'Longitud');

        Swal.fire({
            type: 'info',
            title: 'GPS apagado o geolocalizacion bloqueada',
            showConfirmButton: false,
            timer: 2100
        });

      }

      function getLocationLeaflet() {
         map.on('locationfound', onLocationFound);
         map.on('locationerror', onLocationError);
         map.locate({setView: true, maxZoom: 15});
      }


function carga_suss(){
    var active = dataBaseCola.result;
    var data = active.transaction('tempologin', "readonly");
    var object = data.objectStore('tempologin');
    var elements = [];
    object.openCursor().onsuccess = function (e) {
        var result = e.target.result;
        if (result === null) {
            return;
        }
        elements.push(result.value);
        result.continue();
        // check_conn_lat_log();
    };
    data.oncomplete = function () {
        var outerHTML = '';
            $("#usc").val(elements[0].clave);
            $("#usuarioruta").val(elements[0].sus);
            $("#usuariosesion").text(elements[0].nameruta);
            active.close();           
        // alert($("#usuarioruta").val());
    };
    
}

function crear_tempologinAC(){
    dataBaseCola = indexedDB.open('RegistroCola',1);
    dataBaseCola.onsuccess = function (e) {
        console.log('DB Registros pendientes [onsuccess RegistroCola]');
        cantidad_pendientes_act();
        // carga_tempo_credenciales();
    };
    dataBaseCola.onupgradeneeded = function (e) {
        dbExists = false;
        var active = dataBaseCola.result;
        var objtemporalregistros = active.createObjectStore("temporegistros", {keyPath: 'id', autoIncrement: true});
        var objtemporalogin = active.createObjectStore("tempologin", {keyPath: 'id'});
        var objtemporalregistros_act = active.createObjectStore("temporegisactu", {keyPath: 'id', autoIncrement: true});
        // console.log('INICIALIZACION DE BASE DE DATOS [onupgradeneeded RegistroCola]');
        // active.close();
        // dataBaseCola.close();
    };
    dataBaseCola.oncomplete = function () {
        // console.log('INICIALIZACION DE BASE DE DATOS [oncomplete RegistroCola]');
    };
    /*dataBaseCola.onerror = function (e) {
        console.log('DB Registros pendientes error!');
    };*/
}


function grBusqueda(){
    const TABLAD = document.getElementById('DgrTable');
    const S_Text = document.getElementById('txtBusqueda').value.toLowerCase();
    let total = 0;
    for (let i = 1; i < TABLAD.rows.length; i++) {
        if (TABLAD.rows[i].classList.contains("noSearch")) {
            continue;
        }
        let encontrado = false;
        const coR = TABLAD.rows[i].getElementsByTagName('td');
        for (let j = 0; j < coR.length && !encontrado; j++) {
            const compara = coR[j].innerHTML.toLowerCase();
            if (S_Text.length == 0 || compara.indexOf(S_Text) > -1) {
                encontrado = true;
                total++;
            }
        }
        if (encontrado) {
            TABLAD.rows[i].style.display = '';
        } else {
            TABLAD.rows[i].style.display = 'none';
        }
    }
    // const ultimaTR=TABLAD.rows[TABLAD.rows.length-1];
    // const td=ultimaTR.querySelector("td");
    // ultimaTR.classList.remove("hide", "red");
    if (S_Text == "") {
        // ultimaTR.classList.add("hide");
    } else if (total) {
        // td.innerHTML="Se ha encontrado "+total+" coincidencia"+((total>1)?"s":"");
    } else {
        // ultimaTR.classList.add("red");
        // td.innerHTML="No se han encontrado coincidencias";
    }
}
    $("#X").click(function(){
        if(blockF==0){
            $(".CTable_Gr").fadeOut("slow",function(){
                $("#showData").empty();
            });
        }
    });

function mostrar_clientes_ruta(){
   // Levantar_startDB_AC_CLI_CON_CONN();
    var arr_dat = [];
    var active = dataBase.result;
    var data = active.transaction(['TempoClientes'], "readonly");
    var object = data.objectStore('TempoClientes');
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
            codigo: elements[key].codigo,
            nombre: elements[key].nombre,
            direccion: elements[key].contacto,
            contacto: elements[key].direccion,
            telefono: elements[key].telefono,
            latitud: elements[key].latitud,
            longitud: elements[key].longitud
            };
        }
        elements = [];
        var paginado = "";
        DataTB = "";
                    
                    for(var i=0;i<parseInt(arr_dat.length);i++){
                    // $.each(resul.listaclientes, function(i, val){
                        DataTB+="<tr id='ROW_"+(i+1)+"' class='TrSelect NormalTR'>";
                        DataTB+="   <td  style='vertical-align:middle;' class='Cme'>"+arr_dat[i].codigo+"<input type='hidden' class='Latitude' value='"+arr_dat[i].latitud+"'><input type='hidden' class='Longitude' value='"+arr_dat[i].longitud+"'></td>";
                        DataTB+="   <td  style='vertical-align:middle;' class='Nme'>"+arr_dat[i].nombre+"</td>";
                        DataTB+="   <td  style='vertical-align:middle;' class='Dme'>"+arr_dat[i].direccion+"</td>";
                        DataTB+="   <td  style='vertical-align:middle;' class='COme'>"+arr_dat[i].contacto+"</td>";
                        DataTB+="   <td  style='vertical-align:middle;' class='Tme'>"+arr_dat[i].telefono+"</td>";
                        DataTB+="</tr>";
                    // });
                }
        $("#showData").empty().html(DataTB);
   

                    // paginado +=resul.pagina_insertar;
                    // $("#showData").empty().append(DataTB);
                    // $("#paginado").empty().append(paginado);
                    
                    // $("#paginado").empty().html(paginado);
                    $("#totalregistros").html("Cantidad de Registros: <span class='badge badge-primary'>"+arr_dat.length+"</span>");
                    B1=1000;
                    //BOTON PARA ENVIAR LOS REGISTROS DE ACTUALIZACION DE CLIENTES
                    $("#btn-enviar-coord").hide(100,function(){
                        $("#btn-enviar").show(100,function(){
                            $("#btn-enviar-coord").text('Enviando, por favor espere....');
                        });
                    });
                    $("#btn-formopciones").show(150,function(){
                        $("#btn-formodetalles").show(150,function(){
                            $("#img-carga").hide(150,function(){
                                $('#DgrTable').DataTable( {
                                    "language": {
                                        "lengthMenu": "Mostrar _MENU_ registros por página",
                                        "zeroRecords": "Nada encontrado - lo siento",
                                        "info": "Mostrando la página _PAGE_ de _PAGES_",
                                        "infoEmpty": "No hay registros disponibles.",
                                        "infoFiltered": "(filtrado de _MAX_ registros totales)",
                                        "search": "Buscar:",
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
                                        // "paging":   false,
                                        "ordering": false,
                                        "info": false,
                                        "lengthChange": false,
                                        // "aLengthMenu": [ [2, 4, 8, -1], [2, 4, 8, "All"] ],
                                        "iDisplayLength": 10, 
                                        // "responsive": true,
                                        // "pageLength": 3,
                                        "pagingType": "simple"
                                } );
                                
                                $(".CTable_Gr").fadeIn("slow");
                                $("#Modalopciones").modal("show");
                                // $('#btn-formodetalles').attr("disabled", false);                        
                            });//CIERRE IMG-CARGA
                        });//CIERRE BTN-FORMDETALLES
                    });//CIERRE BTN-FORM-OPCIONES
                    // alert('btn modal presionado');
    };
// active.close();
}

function cargar_clientes_ruta(page){

          var promise =  $.ajax({
                url:'lista-clientes-ruta/leer_clientes',
                type:"POST",
                data:{page:page},
                dataType: "JSON",
                timeout:17777
            }).done(function(resul) {
                if(resul.rs == true){
                    guardar_db_clientes(resul.listaclientes);
                }else{
                    Swal.fire({
                        title: '<strong><<[ ERROR CARGA CLIENTES SN ]>><BR> POR FAVOR CONSULTE CON SISTEMAS DE VENTA</strong>',
                        type: 'error',
                        html:'',
                        confirmButtonText:'Ok'
                    });  
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $("#btn-enviar-coord").hide(100,function(){
                    $("#btn-enviar").show(100,function(){
                        $("#btn-enviar-coord").text('Enviando, por favor espere....');
                    });
                });                
                $("#btn-formopciones").show(150,function(){
                    $("#btn-formodetalles").show(150,function(){
                        $("#img-carga").hide(150,function(){

                        });//CIERRE IMG-CARGA
                    });//CIERRE BTN-FORMDETALLES
                });//CIERRE BTN-FORM-OPCIONES   
                Swal.fire({
                    title: '<strong><<[ ERROR ]>><BR> SE PERDIO CONEXION CON EL SERVIDOR</strong>',
                    type: 'error',
                    html:'',
                    confirmButtonText:'Ok'
                });
            });//ajax cierre

            promise.then(function(){
                mostrar_clientes_ruta();
            });
}

function cargar_clientes_ruta_AC(page){

    $("#btn-enviar-coord").hide(100,function(){
        $("#btn-enviar").show(100,function(){
            $("#btn-enviar-coord").text('Enviando, por favor espere....');
        });
    });
    $("#btn-formopciones").show(150,function(){
        $("#btn-formodetalles").show(150,function(){
            $("#img-carga").hide(150,function(){
                $.ajax({
                    url:'lista-clientes-ruta_ac/leer_clientes_AC',
                    type:"POST",
                    data:{page:page},
                    dataType: "JSON",
                    timeout:17777
                }).done(function(resul) {
                    if(resul.rs == true){
                        var paginado = "";
                        DataTB = "";
                        $.each(resul.listaclientes, function(i, val){
                            DataTB+="<tr>";
                            DataTB+="   <td style='vertical-align:middle;'>"+val.codigo+"</td>";
                            DataTB+="   <td style='vertical-align:middle;'>"+val.nombre+"</td>";
                            DataTB+="   <td style='vertical-align:middle;'>"+val.direccion+"</td>";
                            DataTB+="   <td style='vertical-align:middle;'>"+val.contacto+"</td>";
                            DataTB+="   <td style='vertical-align:middle;'>"+val.telefono+"</td>";
                            DataTB+="</tr>";
                        });
                        paginado +=resul.pagina_insertar;
                        // $("#showData").empty().append(DataTB);
                        // $("#paginado").empty().append(paginado);
                        $("#listaclientescon").empty().html(DataTB);
                        // $("#paginado").empty().html(paginado);
                        $("#totalregistroscon").html("Cantidad de Registros: <span class='badge badge-primary'>"+resul.resultcanti+"</span>");
                        // $('#DgrTable').DataTable( {
                        //     responsive: true
                        // } );
                        cargar_totales_cuadro(1);
                    }else{
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {

                });
            });//CIERRE IMG-CARGA
        });//CIERRE BTN-FORMDETALLES
    });//CIERRE BTN-FORM-OPCIONES
}

function cargar_totales_cuadro(page){

    $.ajax({
        url:'totales_actu_cuadro/cuadro_cantidades_cli',
        type:"POST",
        data:{page:page},
        dataType: "JSON",
        timeout:17777
    }).done(function(resul) {
        if(resul.rs == true){
            $("#tgeneral").text(resul.total_general);
            $("#tactualizados").text(resul.total_c_actualizar);
            $("#tsinactualizar").text(resul.total_s_actualizar);
            
            $("#btn-formodetalles-hide").hide(150,function(){
                $("#btn-formodetalles").show(150,function(){
                           

                    $('#btn-formopciones').attr("disabled", false);
                    $("#ModalopcionesDET").modal("show");
                });
            });
        }else{
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {

    });

}


function getInfo(){
    // $(".CTable_Gr").fadeOut("slow",function(){
        $("#showData").empty();
        $("#txtBusqueda").val("");
        blockF=0;
        B1=0;
        $("#Modalopciones").modal('hide');
    // });
}

    // $('#btncoordenadas').on('click',function() {
    //     //Click al boton para pedir permisos
    //     //Si el navegador soporta geolocalizacion
    //     // if (!!navigator.geolocation) {
    //         //Pedimos los datos de geolocalizacion al navegador
    //         navigator.geolocation.getCurrentPosition(
    //             //Si el navegador entrega los datos de geolocalizacion los imprimimos
    //             function (position) {
    //                 //window.alert("nav permitido");
    //                 var latitud = '';
    //                 var longitud = '';
    //                 $("#txtlatitud").val(position.coords.latitude);
    //                 $("#txtlatitudm").val(position.coords.latitude);
    //                 $("#txtlongitud").val(position.coords.longitude);
    //                 $("#txtlongitudm").val(position.coords.longitude);
    //                 V_NumeroEnteroDecimalpogps($("#txtlatitud").val(),'txtlatitudm',4,'Latitud');
    //                 V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',5,'Longitud');
    //             },
    //             //Si no los entrega manda un alerta de error
    //             function () {
    //                 $("#txtlatitud").val(0);
    //                 $("#txtlatitudm").val(0)
    //                 $("#txtlongitud").val(0);
    //                 $("#txtlongitudm").val(0);
    //                 Swal.fire({
    //                     type: 'info',
    //                     title: 'GPS apagado o geolocalizacion bloqueada',
    //                     showConfirmButton: false,
    //                     timer: 2100
    //                 });
    //                 // alertify.warning("geolocalizacion no permitida!");
    //                 V_NumeroEnteroDecimalpogps($("#txtlatitud").val(),'txtlatitudm',4,'Latitud');
    //                 V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',5,'Longitud');
    //             }
    //         );
    //     // }else{
    //     //     $("#txtlatitud").val(0);
    //     //     $("#txtlongitud").val(0);
    //     // }


    // });

function V_Text_LetraNumero(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\- ]+$/g
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length<7){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es muy corto.';
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es muy corto.');
        }else{        
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#error-mjs-"+ordencampo).html('En el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros,"ñ", "#" y "-"');
                arrg_vali_result[ordencampo] = 'En el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros,"ñ" y "#" y "-"';
            }
        }
    }
    return v;
}

function V_Text_LetraNumero_Direccion(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-. ]+$/g
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es obligatoria.';
        $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> es obligatoria.');
    }else{
        if(data_C.length<26){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es muy corta.';
            $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> es muy corta.');
        }else{        
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#error-mjs-"+ordencampo).html('En la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros,"ñ", "#" y "-"');
                arrg_vali_result[ordencampo] = 'En la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros,"ñ" y "#" y "-"';
            }
        }
    }
    return v;
}

function V_numeconMaskguion(data,campo,ordencampo,etiqueta,valcanti){
    var  v = 0;
    var data_C=data.trim();
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data.length == valcanti){
            v = 1;
            $("#"+campo).removeClass("is-invalid").addClass("is-valid");
            arrg_vali_result[ordencampo] = '';
        }else{
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> tiene que tener '+valcanti+' digitos.';
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> tiene que tener '+valcanti+' digitos.');
        }
    }
    return v;
}

function V_Text_ConEspacio(data,campo,ordencampo,etiqueta){

    var data_C=data.trim();
    
    var v = 0;
    var data_E=/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/g
    if(empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length<6){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es muy corto.');
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es muy corto.';
        }else{        
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#error-mjs-"+ordencampo).html('En el nombre de  <strong>'+etiqueta+'</strong>, se necesita minimo un nombre y un apellido (solo letras).');
                arrg_vali_result[ordencampo] = 'En el nombre de <strong>'+etiqueta+'</strong>, se necesita minimo un nombre y un apellido (solo letras).';
            }
        }
    }
    return v;
}

function V_NumeroEnteroDecimalpo(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        if(data_C==0){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = '<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.';
            $("#error-mjs-"+ordencampo).html('<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.');
        }else{
            var data_E=/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/gm
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
                $("#error-mjs-"+ordencampo).html('');
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En el campo <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
            }
        }

    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';     
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}

function V_NumeroEnteroDecimalne(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;

    if(data_C!=""){
        if(data_C==0){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = '<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.';
            $("#error-mjs-"+ordencampo).html('<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.');
        }else{
            var data_E=/^[-]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/gm
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
                $("#error-mjs-"+ordencampo).html('');
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
            }
        }
    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}

function V_NumeroEnteroDecimalpogps(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    if(data_C!=""){
        if(data_C==0){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = '<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.';
            $("#error-mjs-"+ordencampo).html('<strong>'+etiqueta+'</strong> El gps esta inactivo o se ha bloqueado la localización.');
        }else{
            var data_E=/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/gm
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
                $("#error-mjs-"+ordencampo).html('');
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                arrg_vali_result[ordencampo] = 'En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.';
                $("#error-mjs-"+ordencampo).html('En la <strong>'+etiqueta+'</strong> solo se permiten n&uacute;meros.');
            }
        }

    }else{
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El campo <strong>'+etiqueta+'</strong> es obligatorio.';     
        $("#error-mjs-"+ordencampo).html('El campo <strong>'+etiqueta+'</strong> es obligatorio.');
    }
    return v;
}


function validacion_form_actu(){
    var contarok = 0;
    contarok +=V_Text_LetraNumero($("#txtnombre").val(),'txtnombre',0,'Nombre del establecimiento');
    contarok +=V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
    contarok +=V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',2,'Nombre de contacto');
    contarok +=V_numeconMaskguion($("#txttelefono").val(),'txttelefono',3,'N&uacute;mero de tel&eacute;fono',9);
    contarok +=V_NumeroEnteroDecimalpogps($("#txtlatitud").val(),'txtlatitudm',4,'Latitud');
    contarok +=V_NumeroEnteroDecimalne($("#txtlongitud").val(),'txtlongitudm',5,'Longitud');
    return contarok;
}

/*FUNCION ENVIO DE ACTUALIZACION*/
function check_conn_enviar_info() {
    // $("#btn-enviar-coord").text('Cargando por favor espere...');
    Swal.fire({
        title: 'Estás seguro de enviar los cambios',
        text: "si aceptas no podras cambiar lo enviado...",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Enviar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.value){
            $("#btn-enviar").hide(150,function(){
                $("#btn-enviar-coord").show(150,function(){
                    $("#btn-formopciones").hide(150,function(){
                        $("#btn-formodetalles").hide(150,function(){
                            $("#img-carga").show(150,function(){
                                $.ajax({
                                    url:'comprobarconexion/resultconexion',
                                    type:"POST",
                                    data:{"pin":'conexions'},
                                    dataType: "JSON",
                                    timeout:7777
                                }).done(function(resul) {
                                    entregar_a_sdv();
                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                    // alert('pagina error');
                                    conn = false;
                                    if ( textStatus === 'timeout'){
                                        // console.log('Error de tiempo de espera.');
                                        // cerrar_cargas('Error de tiempo de espera.',1);
                                        salvar_temporal_actu();
                                    }else if (jqXHR.status === 0) {
                                        // console.log('Sin conexion a internet...');
                                        // cerrar_cargas('Sin conexión a internet...',1);
                                        salvar_temporal_actu();
                                    } else if (jqXHR.status == 404) {
                                        // console.log('Página solicitada no encontrada[404]');
                                        cerrar_cargas('Página solicitada no encontrada[404]',1);
                                    } else if (jqXHR.status == 500) {
                                        // console.log('Error de servidor interno [500].');
                                        cerrar_cargas('Error de servidor interno [500].',1);
                                    } else if (textStatus === 'parsererror') {
                                        //BLOQUE DEL ACL
                                        //RESTABLECER SESION
                                        // console.log('El análisis JSON solicitado falló POSIBLE SESION EXPIRADA [FALLO SINCRONIZACION].');
                                        actualizar_sesion_enviar_info();
                                        // retorna_inicio();
                                    } else if (textStatus === 'abort') {
                                        // console.log('Solicitud de Ajax abortada.');
                                        cerrar_cargas('Solicitud de Ajax abortada.',1);
                                    } else {
                                        // console.log('Error no detectado: ' + jqXHR.responseText);
                                        cerrar_cargas('Error no detectado: ' + jqXHR.responseText,1);
                                    }
                                });
                            });//CIERRE IMG-CARGA
                        });///CIERRE FORMDETALLE
                    });//CIRRE FORM-OPCIONES         
                });//CIERRE BTN-ENVIAR-COORD
            });//CIERRE BTN-ENVIAR
        }else{

        }
    });
}

function actualizar_sesion_enviar_info(){
    // alert($("#usc").val());
    $.ajax({
        url:"login/iniciosesion",
        type:"POST",
        data:{usuario:$("#usc").val(),contrasena:$("#usc").val()},
        dataType: "JSON",
        timeout:7777
    }).done(function(respuesta) {
        if(respuesta.rs==false){
            // alert('tiandome para atras');
            location.href = "../";
            // alert(respuesta);
        }else{
            // console.log('<< SESION ACTUALIZADA ENVIO DE REGISTROS >>');
            entregar_a_sdv();
        }
    }).fail(function() {
        $("#spinner-load").hide(150,function(){
            // console.log('<<[WARNING]>> SE PERDIO CONEXION CON EL SERVIDOR');
            Swal.fire({
                title: '<strong></strong>',
                type: 'error',
                html:'',
                confirmButtonText:'Ok'
            });
        });
    });
}



function entregar_a_sdv(){
    var datas = $("#form-actuinfo").serializeArray();
    datas.push({name: 'txtsus', value:$("#usuarioruta").val()});
    datas.push({name: 'txtcodigocliente', value:$("#lblcodcli").text()});
    // datas.push({name: 'rutaimg', value:$("#rutaimg").val()});
    var detalle_validacion = ``;
    // console.log(6);
    // alert('CAMBIO => '+$("#lblcodcli").text());
    if($("#lblcodcli").text() !='0000000'){
        if(validacion_form_actu() == 6){
            // alert(datas);
                $.ajax({
                    url:'actu-cliente/actualizacion_cli',
                    type:"POST",
                    data:datas,
                    dataType: "JSON",
                    timeout:17777
                }).done(function(resul) {
                    $("#btn-enviar-coord").hide(150,function(){
                        $("#btn-enviar").show(150,function(){
                            $("#btn-formopciones").show(150,function(){
                                $("#btn-formodetalles").show(150,function(){
                                    $("#img-carga").hide(150,function(){
                                        $(":input").removeClass("is-valid");
                                        document.getElementById("form-actuinfo").reset();
                                        $("#txtlatitud").val('');
                                        $("#txtlongitud").val('');
                                        $("#lblcodcli").text('0000000');
                                        $('html').animate({scrollTop : 0}, 500);
                                        Swal.fire({
                                            type: 'success',
                                            title: 'Registro enviado exitosamente!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    });//CIERRE IMG-CARGA
                                });//CIERRE BTN-FORMDETALLES
                            });//CIERRE BTN-FORM-OPCIONES
                        });//CIERRE BTN-ENVIAR-COORD
                    });//CIERRE BTN-ENVIAR
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    $("#btn-enviar-coord").hide(150,function(){
                        $("#btn-enviar").show(150,function(){
                            $("#btn-formopciones").show(150,function(){
                                $("#btn-formodetalles").show(150,function(){
                                    $("#img-carga").hide(150,function(){
                                        Swal.fire({
                                            type: 'info',
                                            title: 'Algo salio mal :( por favor vuelve a intentarlo, si el error persiste comunicate con Sistemas de venta',
                                            showConfirmButton: false,
                                            timer: 1900
                                        });
                                    });//CIERRE IMG-CARGA
                                });//CIERRE BTN-FORMDETALLES
                            });//CIERRE BTN-FORM-OPCIONES
                        });//CIERRE BTN-ENVIAR-COORD
                    });//CIERRE BTN-ENVIAR
                });
        }else{
            $("#btn-enviar-coord").hide(150,function(){
                $("#btn-enviar").show(150,function(){
                    $("#btn-formopciones").show(150,function(){
                        $("#btn-formodetalles").show(150,function(){
                            $("#img-carga").hide(150,function(){
                                arrg_vali_result.forEach( function(valor, indice, array) {
                                    if(!empty(valor)){
                                        detalle_validacion += `<p>${valor}</p>`;
                                    }else{}
                                });
                                Swal.fire({
                                    title: '<strong>Hay campos que requieren de su atención!</strong>',
                                    type: 'info',
                                    html:detalle_validacion,
                                    confirmButtonText:'Ok'
                                });
                            });//CIERRE IMG-CARGA
                        });//CIERRE BTN-FORMDETALLES
                    });//CIERRE BTN-FORM-OPCIONES
                });//CIERRE BTN-ENVIAR-COORD
            });//CIERRE BTN-ENVIAR
        }
    }else{
        cerrar_cargas('Error Actualización Sin Código de Cliente.',1);
            Swal.fire({
            title: '<strong>Por Favor Selecciona un Cliente!</strong>',
            type: 'info',
            html:'',
            confirmButtonText:'Ok'
        });
    }//CIERRE CONDICION CODIGO EN CERO
}

//FUNCIONALIDAD SIN CONEXION CON INDEXEDDB
function startDB_AC_CLI_SIN_CONN() {
    mostrar_clientes_ruta();
}


function startDB_AC_CLI_CON_CONN() {
    // var request = indexedDB.deleteDatabase('DBAC',1);
    // request.onerror = function(event) {
    //     console.log("Error al intentar borrar BD", event);
    //     alertify.error('Ocurrio un error inesperado,Por favor comuniquese con Sistemas de Ventas',5);
    // };
    // request.onsuccess = function(event) {
        // console.log("DB TemporaSDV borrada", event);
        // dataBase = indexedDB.open('DBAC',1);
        // dataBase.onsuccess = function (e) {
        //     cargar_clientes_ruta(1);
        // };
        // dataBase.onupgradeneeded = function (e) {
        //     var active = dataBase.result;
        //     var TempoClientes = active.createObjectStore("TempoClientes", {keyPath: 'id', autoIncrement: true});
        //     var TempoColaAC = active.createObjectStore("TempoColaAC", {keyPath: 'id', autoIncrement: true});
        // };
        // dataBase.onerror = function (e) {
        //     alertify.error('Ocurrio un error inesperado,Por favor comuniquese con Sistemas de Ventas [DB NULL]',10);
        // };
    // };

//     if(performance.navigation.type  == 0 ) {
//                         // The db already exists so delete it and re-create it so we don't have stale records.
//                         // alert('ya existe base de datos'); 

// var DBDeleteRequest = window.indexedDB.deleteDatabase("DBAC");

// DBDeleteRequest.onerror = function(event) {
//   console.log("Error deleting database.");
// };
 
// DBDeleteRequest.onsuccess = function(event) {
//   console.log("Database deleted successfully");
    
//   console.log(event.result); // should be undefined
// };


//                     } else {
//                         window.refreshing = true;
//                         alert('no existe dbb'); 
//                     }


// var DBDeleteRequest = window.indexedDB.deleteDatabase("DBAC");

// DBDeleteRequest.onerror = function(event) {
//   console.log("Error deleting database.");
// };
 
// DBDeleteRequest.onsuccess = function(event) {
//   console.log("Database deleted successfully");
    
//   console.log(event.result); // should be undefined
// };

}

function borrar_DB_DBAC(){

    var DBDeleteRequest = window.indexedDB.deleteDatabase("DBAC",1);

    DBDeleteRequest.onerror = function(event) {
      // console.log("ERROR NO SE PUDO BORRAR BASE DE DATOS");
    };
     
    DBDeleteRequest.onsuccess = function(event) {
      // console.log("BASE DE DATOS BORRADA EXITOSAMENTE DBAC");
      // console.log(event.result); // should be undefined
      Levantar_startDB_AC_CLI_CON_CONN();
    };


}


function Levantar_startDB_AC_CLI_CON_CONN() {
    dataBase = indexedDB.open('DBAC',1);
    dataBase.onsuccess = function (e) {
        // console.log('DB inicializada DBAC');
        // alertify.success('DB inicializada Correctamente [DBAC]',10);
    };
    dataBase.onupgradeneeded = function (e) {
        var active = dataBase.result;
        var TempoClientes = active.createObjectStore("TempoClientes", {keyPath: 'id', autoIncrement: true});
        var TempoColaAC = active.createObjectStore("TempoColaAC", {keyPath: 'id', autoIncrement: true});
        // active.close();
    };
    dataBase.onerror = function (e) {
        // alertify.error('Ocurrio un error inesperado,Por favor comuniquese con Sistemas de Ventas [DB NULL]',10);
    };
}


function guardar_db_clientes(arrg_datos) {
    //arrg_datos,tabla,descripcion
    var active = dataBase.result;
    /*----------------CARGANDO DEPARTAMENTOS----------------------*/
    var data = active.transaction(['TempoClientes'], "readwrite");
    var object = data.objectStore('TempoClientes');
    var ii=1;
    $.each(arrg_datos, function(i, val){
        var request = object.put({
            id:ii,
            codigo: val.codigo,
            nombre: val.nombre,
            direccion: val.contacto,
            contacto: val.direccion,
            telefono: val.telefono,
            latitud: val.Latitud,
            longitud: val.Longitud
        });
        ii++;
        request.onerror = function (e) {
                //console.log(request.error.name + '\n\n' + request.error.message);
                // console.log('Llave repetida '+descripcion+'...');
                return 0;
        };
    });
    data.oncomplete = function (e) {
        // console.log('Sincronizacion exitosa '+descripcion+'! var => '+conteo_sincro);
        // alertify.success('Sincronizacion exitosa '+descripcion+'!',2);
        // console.log('TERMINO DE CARGAR '+ii);
        // if(ii == 395){
        //     console.log('abrir modal de clientes');
        //     mostrar_clientes_ruta();
        // }
        
        return 1;
    };
    // active.close();
}

function carga_complementos_exh(tabla){
    arr_dat = [];
    var active = dataBase.result;
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
                codbx:elements[key].codbx,
                 SKU:elements[key].SKU,
                valor: elements[key].valor
            };
        }
        elements = [];
        for(var i=0;i<parseInt(arr_dat.length);i++){
            // DataTB+="<input type='hidden' class='Cme' value='"+arr_dat[i].valor+"'>";
            DataTB+="<tr id='ROW_"+(i+1)+"' class='TrSelect NormalTR'>";
            // DataTB+="<input type='text' class='Cme' value='"+arr_dat[i].codbx+"'>";
            // DataTB+="   <th scope='row'>"+(i+1)+"</th>";
            DataTB+="   <td>"+arr_dat[i].SKU+"</td>";
            DataTB+="   <td style='display:none;' class='Cme'>"+arr_dat[i].codbx+"</td>";
            DataTB+="   <td class='Nme'>"+arr_dat[i].valor+"</td>";
            // if(String(arr_dat[i].IMAGEN_E)=="NULL"){
            //     DataTB+="   <td><div class='NulleIMG'>Sin imagen</div></td>";   
            // }else{
            //     // DataTB+="   <td><img src='"+arr_dat[i].IMAGEN_E+"'></td>";
            // }
            // DataTB+="<<Sin imagen>>";
            DataTB+="</tr>";
        }


    };
    active.close();
}


function limpiar_clientes(){
/*LIMPIAR BASE DE DATOS ULTIMA OPCION*/
 var active = dataBase.result;
  // open a read/write db transaction, ready for clearing the data
  var transaction = active.transaction(["TempoClientes"], "readwrite");

  // report on the success of the transaction completing, when everything is done
  transaction.oncomplete = function(event) {
    // console.log('TRANSACCION COMPLETA');
    // note.innerHTML += '<li>Transaction completed.</li>';
  };

  transaction.onerror = function(event) {
    // console.log('Transaction not opened due to error: ' + transaction.error);
    // note.innerHTML += '<li>Transaction not opened due to error: ' + transaction.error + '</li>';
  };

  // create an object store on the transaction
  var objectStore = transaction.objectStore("TempoClientes");

  // Make a request to clear all the data out of the object store
  var objectStoreRequest = objectStore.clear();

  objectStoreRequest.onsuccess = function(event) {
    // report the success of our request
    // console.log('TABLA CLIENTES LIMPIA');
    cargar_clientes_ruta(1);
    // note.innerHTML += '<li>Request successful.</li>';
  };
}

function salvar_temporal_actu(){
    var detalle_validacion = ``;
    if($("#lblcodcli").text() !='0000000'){
        if(validacion_form_actu() == 6){
                // $("#btn-enviar-coord").hide(150,function(){
                //     $("#btn-enviar").show(150,function(){
                //         $("#btn-formopciones").show(150,function(){
                //             $("#btn-formodetalles").show(150,function(){
                //                 $("#img-carga").hide(150,function(){
                                    var active = dataBaseCola.result;
                                    /*----------------SALVANDO REGISTROS CLIENTES TEMPORALES----------------------*/
                                    var data = active.transaction(['temporegisactu'], "readwrite");
                                    var object = data.objectStore('temporegisactu');
                                    var request = object.put({
                                        txtnombre: $("#txtnombre").val(),
                                        txtdireccion: $("#txtdireccion").val(),
                                        txtcontacto: $("#txtcontacto").val(),
                                        txttelefono: $("#txttelefono").val(),
                                        txtlatitud: $("#txtlatitud").val(),
                                        txtlongitud: $("#txtlongitud").val(),
                                        txtcodigocliente: $("#lblcodcli").text(),
                                        txtsus: $("#usuarioruta").val()
                                    });
                                    request.onerror = function (e) {
                                        // console.log('Llave repetida.');
                                    };
                                    data.oncomplete = function (e) {
                                        console.log('Registro guardado en cola exitosamente!');
                                        $(":input").removeClass("is-valid");
                                        document.getElementById("form-actuinfo").reset();
                                        $("#txtlatitud").val('');
                                        $("#txtlongitud").val('');
                                        $("#lblcodcli").text('0000000');
                                        cantidad_pendientes_act();
                                        $('html').animate({scrollTop : 0}, 500);
                                        warn_on_unload = '';
                                        window.onbeforeunload = function() {
                                            if(warn_on_unload != ''){
                                                return warn_on_unload;
                                            }
                                        }
                                        Swal.fire({
                                            type: 'success',
                                            title: '[ Sin conexión ] salvando registro temporalmente...',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    };
                //                 });//CIERRE IMG-CARGA
                //             });//CIERRE BTN-FORMDETALLES
                //          });//CIERRE BTN-FORM-OPCIONES
                //     });//CIERRE BTN-ENVIAR-COORD
                // });//CIERRE BTN-ENVIAR
        }else{
            $("#btn-enviar-coord").hide(150,function(){
                $("#btn-enviar").show(150,function(){
                    $("#btn-formopciones").show(150,function(){
                        $("#btn-formodetalles").show(150,function(){
                            $("#img-carga").hide(150,function(){
                                arrg_vali_result.forEach( function(valor, indice, array) {
                                    if(!empty(valor)){
                                        detalle_validacion += `<p>${valor}</p>`;
                                    }else{}
                                });
                                Swal.fire({
                                    title: '<strong>Hay campos que requieren de su atención!</strong>',
                                    type: 'info',
                                    html:detalle_validacion,
                                    confirmButtonText:'Ok'
                                });
                            });//CIERRE IMG-CARGA
                        });//CIERRE BTN-FORMDETALLES
                    });//CIERRE BTN-FORM-OPCIONES
                });//CIERRE BTN-ENVIAR-COORD
            });//CIERRE BTN-ENVIAR
        }
    }else{
        cerrar_cargas('Error Actualización Sin Código de Cliente.',1);
        Swal.fire({
            title: '<strong>Por Favor Selecciona Un Cliente!</strong>',
            type: 'info',
            html:'',
            confirmButtonText:'Ok'
        });
    }//CIERRE CONDICION CODIGO EN CERO
}


function checkconexion_offlinecola() {

    $("#btn-enviar-coord").text('Cargando por favor espere...');
    $("#btn-enviar").hide(100,function(){
        $("#btn-enviar-coord").show(100);
    });

    $("#btn-formopciones").hide(150,function(){
        $("#btn-formodetalles").hide(150,function(){
            $("#img-carga").show(150,function(){
                var conect = false;
                var totalpendientes = 0;
                totalpendientes = $("#pendientes-act").text();

                if(totalpendientes>0){
                    $.ajax({
                        url:'comprobarconexion/resultconexion',
                        type:"POST",
                        data:{"pin":'conexions'},
                        dataType: "JSON",
                        timeout:12000
                    }).done(function(resul) {
                        conect = true;
                        agregar_us_offline_act(conect);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        conect = false;
                        if ( textStatus === 'timeout'){
                            // console.log('Error de tiempo de espera.');
                            agregar_us_offline_act(conect);
                        }else if (jqXHR.status === 0) {
                            // console.log('Sin conexion a internet...');
                            agregar_us_offline_act(conect);
                        } else if (jqXHR.status == 404) {
                            // console.log('Página solicitada no encontrada[404]');
                            retorna_inicio();
                        } else if (jqXHR.status == 500) {
                            // console.log('Error de servidor interno [500].');
                            retorna_inicio();
                        } else if (textStatus === 'parsererror') {
                            // console.log('El análisis JSON solicitado falló POSIBLE SESION EXPIRADA [ REENVIAR COLA ].');
                            actualizar_sesion_cola_act();
                        } else if (textStatus === 'abort') {
                            // console.log('Solicitud de Ajax abortada.');
                            retorna_inicio();
                        } else {
                            // console.log('Error no detectado: ' + jqXHR.responseText);
                            retorna_inicio();
                        }
                    });
                }else{
                    Swal.fire({
                        type: 'info',
                        title: 'No tienes registros pendientes!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            });
        });
    });

}

function agregar_us_offline_act(conect){
    if(conect) {
        Swal.fire({
            title: 'Deseas enviar los registros pendientes?',
            text: "",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, enviar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                var active = dataBaseCola.result;
                var data = active.transaction('temporegisactu', "readonly");
                var object = data.objectStore('temporegisactu');
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
                    arreg_offline = [];
                    arreg_offline = elements;
                    $('#pendientes-act').attr("disabled", true);
                    enviar_regis_offline_act(0,arreg_offline);
                };
            }else{
                cerrar_cargas_mensaje('Error Inesperado [ Registros Pendientes ]',1);
            }
         });
    }else{
        cerrar_cargas_mensaje('Tiempo de espera excedido, por favor busque un lugar con buena cobertura a internet....',1);
    }
}

function enviar_regis_offline_act(indice,elements){
    if(indice < elements.length){
        $.ajax({
            url:'actu-cliente/actualizacion_cli',
            type:"POST",
            data:elements[indice],
            dataType: "JSON",
            timeout:34777
            }).done(function(respuesta) {
                if(respuesta.rs == false){
                    // actualizar_sesion_cola_envio();
                    // descargar_registros_cola('temporegistros');
                    // Swal.fire({
                    //     title: 'Ha ocurrido un error inesperado, por favor descargar y enviar archivo a sistemas de ventas',
                    //     type: respuesta.info,
                    //     html:'<button class="btn btn-success" onclick="exportTableToExcel(\'tabla-registros-con-cola\')" type="button">Descargar Clientes</button> <br>Nombre : clientes_recuperados.xls',
                    //     confirmButtonText:'Ok'
                    // });
                    cerrar_cargas_mensaje('Ocurrio un Error Inesperado',1);
                    // break;
                }else{
                    alertify.success('Registro enviado exitosamente!');
                    enviar_regis_offline_act(indice + 1,arreg_offline);
                    delete_tempo_especifico_act(elements[indice].id);
                    // $('#pendientes').attr("disabled", false);
                }
            }).fail(function() {
                // console.log('ocurrio un error en registros offline');
                // alertify.error('El registro no pudo ser enviado...');
                actualizar_sesion_cola_envio_act();
            });
    }else{
        cerrar_cargas('',1);
        arreg_offline = [];
    }   
}

function delete_tempo_especifico_act(eliminar) {
  var active = dataBaseCola.result;
  var transaction = active.transaction(["temporegisactu"], "readwrite");
  transaction.oncomplete = function(event) {
  };
  transaction.onerror = function(event) {
  };
  var objectStore = transaction.objectStore("temporegisactu");
  var objectStoreRequest = objectStore.delete(eliminar);
  objectStoreRequest.onsuccess = function(event) {
    console.log('Registro eliminado de DB RegistroCola')
    cantidad_pendientes_act_estado();
  };
};

function actualizar_sesion_cola_envio_act(){
    $.ajax({
        url:"login/iniciosesion",
        type:"POST",
        data:{usuario:$("#usc").val(),contrasena:$("#usc").val()},
        dataType: "JSON",
        timeout:7777
    }).done(function(respuesta) {
        if(respuesta.rs==false){
            // $("#mjs_result").show();             
            // $("#mjs_result").html("<div class='alert alert-"+respuesta.cla+" alert-dismissible fade show' role='alert'><strong><h4>¡Informacion Importante!</h4></strong> Debes verificar los campos a continuaci&oacute;n.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+respuesta.errores+"</div>");
            // $('body,html').animate({scrollTop : 0}, 500);
            // $("#spinner-load").hide();
            location.href = "../";
        }else{
            // alert(respuesta.tipou);
            // alert(respuesta.idsupervisor);
            // cleardatatempo(respuesta.usuario,respuesta.clave,respuesta.rutaapp,respuesta.us,respuesta.nameruta,respuesta.rutaapp,respuesta.tipou);
            // location.href = "clientes";
            // console.log('<<SESION ACTUALIZADA CON EXITO>>');
            cerrar_cargas('',1);
            Swal.fire({
                title: '<strong>POR FAVOR VUELVA A ENVIAR LOS REGISTROS PENDIENTES</strong>',
                type: 'info',
                html:'',
                confirmButtonText:'Ok'
            });   
        }
    }).fail(function() {
        $("#spinner-load").hide(150,function(){
            // console.log('<<[WARNING]>> SE PERDIO CONEXION CON EL SERVIDOR')
            Swal.fire({
                title: '<strong></strong>',
                type: 'error',
                html:'',
                confirmButtonText:'Ok'
            });
        });
    });
}


function actualizar_sesion_cola_act(){
    $.ajax({
        url:"login/iniciosesion",
        type:"POST",
        data:{usuario:$("#usc").val(),contrasena:$("#usc").val()},
        dataType: "JSON",
        timeout:7777
    }).done(function(respuesta) {
        if(respuesta.rs==false){
            location.href = "../";
        }else{
            // console.log('<<SESION ACTUALIZADA CON EXITO>>');
            checkconexion_offlinecola();
        }
    }).fail(function() {
        $("#spinner-load").hide(150,function(){
            // console.log('<<[WARNING]>> SE PERDIO CONEXION CON EL SERVIDOR')
            Swal.fire({
                title: '<strong></strong>',
                type: 'error',
                html:'',
                confirmButtonText:'Ok'
            });
        });
    });
}


function cantidad_pendientes_act(){
    var active = dataBaseCola.result;
    var transaction = active.transaction(['temporegisactu'], 'readonly');
    var objectStore = transaction.objectStore('temporegisactu');
    var countRequest = objectStore.count();
    countRequest.onsuccess = function() {
        //console.log(countRequest.result);
        // alert(countRequest.result);
        $("#pendientes-act").html(countRequest.result);

        var activet = dataBaseCola.result;
        var data = activet.transaction('tempologin', "readonly");
        var object = data.objectStore('tempologin');
        var countRequest_tempologin = object.count();
        var elements = [];
        object.openCursor().onsuccess = function (e) {
            var result = e.target.result;
            if (result === null) {
                    return;
            }
            elements.push(result.value);
            result.continue();
            // check_conn_lat_log();
        };
        data.oncomplete = function () {
            var outerHTML = '';
            // alert(countRequest_tempologin.result);
            if(countRequest_tempologin.result>0){
                $("#usc").val(elements[0].clave);
                $("#usuarioruta").val(elements[0].sus);
                $("#usuariosesion").text(elements[0].nameruta);
                check_conn_lat_log();
            }else{
                location.href = "../";
            }
            // active.close();           
            // alert($("#usuarioruta").val());
        };
    }
}


function cantidad_pendientes_act_estado(){
    var active = dataBaseCola.result;
    var transaction = active.transaction(['temporegisactu'], 'readonly');
    var objectStore = transaction.objectStore('temporegisactu');
    var countRequest = objectStore.count();
    countRequest.onsuccess = function() {
    //console.log(countRequest.result);
    //alert(countRequest.result);
    $("#pendientes-act").html(countRequest.result);
    }
}

function cantidad_login_regis(){
    var active = dataBaseCola.result;
    var transaction = active.transaction(['tempologin'], 'readonly');
    var objectStore = transaction.objectStore('tempologin');
    var countRequest = objectStore.count();
    countRequest.onsuccess = function() {
        return countRequest.result;
    }
}



/*VALIDANDO OBTENCION DE COORDENADAS*/


function check_conn_coordenadas() {
    // alert('comprobarconexion');
    $("#btn-enviar-coord").text('Cargando por favor espere...');
    $("#btn-enviar").hide(100,function(){
        $("#btn-enviar-coord").show(100);
    });
    $("#btncoordenadas").hide(200,function(){
       $("#btncoordenadas-hide").show(150); 
    });
    $("#btn-formopciones").hide(150,function(){
        $("#btn-formodetalles").hide(150,function(){
            $("#img-carga").show(150,function(){
                $.ajax({
                    url:'comprobarconexion/resultconexion',
                    type:"POST",
                    data:{"pin":'conexions'},
                    dataType: "JSON",
                    timeout:7777
                }).done(function(resul) {
                    conn = true;
                    cerrar_cargas_ok('Coordenda obtenida sin problemas',1);
                    // console.log('TRUE COORDENADAS');
                    getLocationLeaflet();
                    $("#btncoordenadas-hide").hide(200,function(){
                       $("#btncoordenadas").show(150);
                       // $("#btn-enviar-coord").text('Enviando, por favor espere....');
                    });
                    
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // alert('pagina error');
                    conn = false;
                    if ( textStatus === 'timeout'){
                        // console.log('Error de tiempo de espera...');
                        getLocationLeaflet();
                        cerrar_cargas_INFO('Coordenada Obtenida, no podemos mostrar ubicación en el mapa, sin conexión a internet',1);
                        // startDBsin();
                        $("#btncoordenadas-hide").hide(200,function(){
                            $("#btncoordenadas").show(150); 
                        });                       
                    }else if (jqXHR.status === 0) {
                        // console.log('Sin conexion a internet...');
                        getLocationLeaflet();
                        cerrar_cargas_INFO('Coordenada Obtenida, no podemos mostrar ubicación en el mapa, sin conexión a internet',1);
                        // startDBsin();
                        $("#btncoordenadas-hide").hide(200,function(){
                            $("#btncoordenadas").show(150); 
                        });  
                    } else if (jqXHR.status == 404) {
                        // console.log('Página solicitada no encontrada[404]');
                        cerrar_cargas_mensaje('Página solicitada no encontrada[404]',1);
                        // retorna_inicio();
                        $("#btncoordenadas-hide").hide(200,function(){
                            $("#btncoordenadas").show(150); 
                        });  
                    } else if (jqXHR.status == 500) {
                        // console.log('Error de servidor interno [500].');
                        cerrar_cargas_mensaje('Error de servidor interno [500].',1);
                        // retorna_inicio();
                        $("#btncoordenadas-hide").hide(200,function(){
                            $("#btncoordenadas").show(150); 
                        });  
                    } else if (textStatus === 'parsererror') {
                        //BLOQUE DEL ACL
                        //RESTABLECER SESION
                        // console.log('El análisis JSON solicitado falló POSIBLE SESION EXPIRADA [FALLO SINCRONIZACION].');
                        actualizar_sesion_coordendas();
                        cerrar_cargas('...',1);

                        // retorna_inicio();
                    } else if (textStatus === 'abort') {
                        // console.log('Solicitud de Ajax abortada.');
                        cerrar_cargas_mensaje('Solicitud de Ajax abortada.',1);
                        // retorna_inicio();
                        $("#btncoordenadas-hide").hide(200,function(){
                            $("#btncoordenadas").show(150); 
                        });  
                    } else {
                        // console.log('Error no detectado: ' + jqXHR.responseText);
                        cerrar_cargas_mensaje('Error no detectado: ' + jqXHR.responseText,1);
                        // retorna_inicio();
                        $("#btncoordenadas-hide").hide(200,function(){
                            $("#btncoordenadas").show(150); 
                        });  
                    }
                });
            });

        });
    });
}

function actualizar_sesion_coordendas(){
    // alert($("#usc").val());
    $.ajax({
        url:"login/iniciosesion",
        type:"POST",
        data:{usuario:$("#usc").val(),contrasena:$("#usc").val()},
        dataType: "JSON",
        timeout:7777
    }).done(function(respuesta) {
        if(respuesta.rs==false){
            // alert('tiandome para atras');
            location.href = "../";
            // alert(respuesta);
        }else{
            // console.log('<<SESION ACTUALIZADA CON EXITO>>');
            // $("#btn-enviar-coord").hide(100,function(){
            //     $("#btn-enviar").show(100,function(){
            //         $("#btn-enviar-coord").text('Enviando, por favor espere...');
            //     });
            // });
            $("#btn-formopciones").show(150,function(){
                $("#btn-formodetalles").show(150,function(){
                    $("#img-carga").hide(150,function(){
                        check_conn_coordenadas();
                    });
                });
            });
        }
    }).fail(function() {
        $("#spinner-load").hide(150,function(){
            // console.log('<<[WARNING]>> SE PERDIO CONEXION CON EL SERVIDOR')
            Swal.fire({
                title: '<strong></strong>',
                type: 'error',
                html:'',
                confirmButtonText:'Ok'
            });
        });
    });
}

function cleardatatempos() {
    var active = dataBaseCola.result;
    var data = active.transaction('tempologin', "readwrite");
    data.oncomplete = function(event) {
        // console.log('Limpiando sesion tempo');
    };
    data.onerror = function(event) {
        // console.log('Ocurrio un error: ' + transaction.error + '');
    };
    var objectStore = data.objectStore('tempologin');
    var objectStoreRequest = objectStore.clear();
    objectStoreRequest.onsuccess = function(event) {
        // console.log('Preparando la sesion offline');
    };
}

/*FIN DE PROCESO DE OBTENCION DE COORDENADAS*/


$(document).ready(function(e){

    init('13.685147','-89.147116','BOCADELI');
    /*INICIALIZAR LAS BASE DE DATOS*/
    // crear_tempologinAC();
    /*0000000000000000000000000000000000000*/
    $("#btn-pendinetes").hide();
    $('#Modalopciones').on('hidden.bs.modal', function (e) {
        validacion_form_actu();
        $('#DgrTable').DataTable().destroy();
        $('#showData').empty();
    });

    $(document).on("click", "#btn-menu-back", function() {
        location.href = "menu";
        console.log('presiono el menu');
    });

    $("#btn-pendinetes").hide();

    $('#ModalopcionesDET').on('hidden.bs.modal', function (e) {
       
        $('#DgrTableCon').DataTable().destroy();
        $('#DgrTableSin').DataTable().destroy();
        $('#listaclientessin').empty();
        $('#listaclientescon').empty();
        // $("#DgrTableCon").hide(150);
        // alert('se esconde modal');
    });



    $('#ModalopcionesDET').on('shown.bs.modal', function (e) {
        // alert('se abre modal');
        
                    $('#DgrTableCon').DataTable( {
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros por página",
                            "zeroRecords": "Nada encontrado - lo siento",
                            "info": "Mostrando la página _PAGE_ de _PAGES_",
                            "infoEmpty": "No hay registros disponibles.",
                            "infoFiltered": "(filtrado de _MAX_ registros totales)",
                            "search": "Buscar:",
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
                        // "paging":   false,
                        "ordering": false,
                        "info": false,
                        "lengthChange": false,
                        // "aLengthMenu": [ [2, 4, 8, -1], [2, 4, 8, "All"] ],
                        "iDisplayLength": 10, 
                        "responsive": true,
                        // "pageLength": 3,
                        "pagingType": "simple"
                    });
        // $("#DgrTableCon").show(150);
    });

$(document).on("click", "#borrardb", function() {

/*LIMPIAR BASE DE DATOS ULTIMA OPCION*/
 var active = dataBase.result;
  // open a read/write db transaction, ready for clearing the data
  var transaction = active.transaction(["TempoClientes"], "readwrite");

  // report on the success of the transaction completing, when everything is done
  transaction.oncomplete = function(event) {
    // console.log('Transaction completed');
    // note.innerHTML += '<li>Transaction completed.</li>';
  };

  transaction.onerror = function(event) {
    // console.log('Transaction not opened due to error: ' + transaction.error);
    // note.innerHTML += '<li>Transaction not opened due to error: ' + transaction.error + '</li>';
  };

  // create an object store on the transaction
  var objectStore = transaction.objectStore("TempoClientes");

  // Make a request to clear all the data out of the object store
  var objectStoreRequest = objectStore.clear();

  objectStoreRequest.onsuccess = function(event) {
    // report the success of our request
    // console.log('Request successful');
    // note.innerHTML += '<li>Request successful.</li>';
  };

});
   
    $(document).on("click", ".TrSelect", function() {
        warn_on_unload = 'no salir';
        $("#content-map").empty();
        $("#content-map").html('<div id="map" style="height: 277px;width: 100%;"></div>');
        // event.preventDefault();
        if(blockF==0){
            blockF=1000;
            $(this).addClass("SeletedTR");

            if(B1==1000){
                // alert("CME "+$("#"+$(this).attr("id")+" .Cme").text());
                // alert("NME "+$("#"+$(this).attr("id")+" .Latitude").val());
                $("#lblcodcli").text($("#"+$(this).attr("id")+" .Cme").text());
                // $("#txtcodigocliente").val($("#"+$(this).attr("id")+" .Cme").text());
                $("#txtnombre").val($("#"+$(this).attr("id")+" .Nme").text());
                $("#txtdireccion").val($("#"+$(this).attr("id")+" .Dme").text());
                $("#txtcontacto").val($("#"+$(this).attr("id")+" .COme").text());
                $("#txttelefono").val($("#"+$(this).attr("id")+" .Tme").text());
                $("#txtlatitudm").val($("#"+$(this).attr("id")+" .Latitude").val());
                $("#txtlongitudm").val($("#"+$(this).attr("id")+" .Longitude").val());
                $("#txtlatitud").val($("#"+$(this).attr("id")+" .Latitude").val());
                $("#txtlongitud").val($("#"+$(this).attr("id")+" .Longitude").val());


                init($("#txtlatitud").val(),$("#txtlongitud").val(),$("#txtdireccion").val());
                // map.remove();
            }
            // setTimeout(getInfo, 500);
            getInfo();   
        }
    });

    $('#txttelefono').mask("0000-0000", {placeholder: "0000-0000"});

    $("#txtnombre").keyup(function() {
        V_Text_LetraNumero($("#txtnombre").val(),'txtnombre',0,'Nombre del cliente');
        warn_on_unload = 'no salir';
    });

    $("#txtdireccion").keyup(function() {
        V_Text_LetraNumero_Direccion($("#txtdireccion").val(),'txtdireccion',1,'Direcci&oacute;n');
        warn_on_unload = 'no salir';  
    });

    $("#txttelefono").keyup(function() {
        V_numeconMaskguion($("#txttelefono").val(),'txttelefono',3,'N&uacute;mero de tel&eacute;fono',9);
        warn_on_unload = 'no salir';
    });

    $("#txtcontacto").keyup(function() {
        V_Text_ConEspacio($("#txtcontacto").val(),'txtcontacto',2,'Nombre de contacto');
        warn_on_unload = 'no salir'; 
    });

});