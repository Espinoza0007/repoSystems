var staticCacheName = 'AppSdv';
    const dynamicCacheName = 'dynamic';
    var version = 'v3::';
    // Archivos importantes para la instalacion
    function updateStaticCache() {
        return caches.open(version + staticCacheName)
            .then(function (cache) {
                return cache.addAll([
                './',
                './offline.html',
                './dependencias/imagenes/papyrus2.png',
                './dependencias/imagenes/bocadeli_logo.png',
                './dependencias/imagenes/sdvlogo_transparente.png',
                './dependencias/imagenes/icon_32.png',
                './dependencias/imagenes/icon_64.png',
                './dependencias/imagenes/icon_128.png',
                './dependencias/imagenes/icon_192.png',
                './dependencias/imagenes/icon_256.png',
                './dependencias/imagenes/icon_384.png',
                './dependencias/imagenes/icon_512.png',
                './dependencias/imagenes/file_3_icon-icons.com_68952.png',
                './dependencias/imagenes/Geo_BloqueadaV2.gif',
                './dependencias/bootstrap4.3/css/bootstrap.css',
                './dependencias/bootstrap4.3/css/bootstrap-grid.css',
                './dependencias/alertify/css/alertify.css',
                './dependencias/alertify/css/themes/default.css',
                './dependencias/leaflet/leaflet.css',
                './dependencias/fontawesome-free-5.13.0/css/all.css',
                './dependencias/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css',
                './dependencias/DataTables/Responsive-2.2.3/css/responsive.bootstrap4.min.css',
                './dependencias/gijgo-combined-1.9.11/css/gijgo.css',
                './dependencias/css/CSS_main.css',
                './dependencias/css/CSS_cargando.css',
                './dependencias/css/CSS_modalExhbidor.css',
                './dependencias/css/CSS_menu.css',
                './dependencias/css/CSS_login.css',
                './dependencias/css/CSS_clientesN.css',
                './dependencias/css/CSS_actuexhibidor.css',
                './dependencias/css/CSS_actuClientes.css',
                './dependencias/jquery3.3.1/jquery-3.3.1.js',
                './dependencias/jquery-mask-plugin-master/src/jquery.mask.js',
                './dependencias/bootstrap4.3/js/bootstrap.js',
                './dependencias/moment/moment.js',
                './dependencias/leaflet/leaflet.js',
                './dependencias/sweetalert/sweetalert2.js',
                './dependencias/alertify/js/alertify.js',
                './dependencias/SheetJs/js/xlsx.full.min.js',
                './dependencias/SheetJs/js/FileSaver.js',
                './dependencias/SheetJs/js/Blob.js',
                './dependencias/DataTables/DataTables-1.10.20/js/jquery.dataTables.min.js',
                './dependencias/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js',
                './dependencias/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js',
                './dependencias/DataTables/Responsive-2.2.3/js/responsive.bootstrap4.min.js',
                './dependencias/jquery-validation/dist/jquery.validate.js',
                './dependencias/jquery-validation/dist/additional-methods.js',
                './dependencias/gijgo-combined-1.9.11/js/gijgo.js',
                './dependencias/js/ImageTools.js',
                './dependencias/js/Configuracion_Modal.js',
                './dependencias/js/HP_JS.js',
                './dependencias/js/JS_menu.js',
                './dependencias/js/JS_login.js',
                './dependencias/js/JS_clientes.js',
                './dependencias/js/JS_exhibidores.js',
                './dependencias/js/JS_actualizacionClientes.js',
                './dependencias/js/JS_Validacion_Exh.js',
                './dependencias/js/js_reclamoNuevo.js',
                './dependencias/js/DB_login.js',
                './dependencias/js/DB_clientesN.js',
                './dependencias/js/DB_exhibidores.js',
                './dependencias/js/DB_actualizacionCli.js',
                './dependencias/js/DB_reclamos.js',
                './index.php/login',
                './index.php/menu',
                './index.php/clientes',
                './index.php/encuesta-exhibidores',
                './index.php/actualizacion-clientes',
                './index.php/reclamos',
                './manifest.json',
                './script.js',
                './script_v2.js',
                './cambios.html'
                ]);
            });
    };
    self.addEventListener('message', function (event) {
        if (event.data.action === 'skipWaiting') {
          self.skipWaiting();
        }
    });
    self.addEventListener('install', function (event) {
        // updateStaticCache();
        event.waitUntil(updateStaticCache());
    });
    self.addEventListener('activate', function (event) {
        event.waitUntil(

            caches.keys()
                .then(function (keys) {
                    // Eliminar caches cuyo nombre ya no es válido
                    return Promise.all(keys
                        .filter(function (key) {
                          return key.indexOf(version) !== 0;
                        })
                        .map(function (key) {
                          return caches.delete(key);
                        })
                    );
                })
        );
    });
    self.addEventListener('fetch', function (event) {
        var request = event.request;
        if (request.method !== 'GET') {
            event.respondWith(
                fetch(request)
                    .catch(function () {
                        return caches.match('./offline.html');
                    })
            );
            return;
        }
        if (request.headers.get('Accept').indexOf('text/html') !== -1) {
            // FIX Bug Google Chrome https://code.google.com/p/chromium/issues/detail?id=573937
            if (request.mode != 'navigate') {
                request = new Request(request.url, {
                    method: 'GET',
                    headers: request.headers,
                    mode: request.mode,
                    credentials: request.credentials,
                    redirect: request.redirect
                });
            }
            event.respondWith(async function() {
                const response = await caches.match(event.request);
                return response || fetch(event.request);
            }());
            return;
        }
        //Para solicitudes que no sean HTML, primero buscar en la memoria cache, recurrir a la red
        event.respondWith(
            caches.match(request)
                .then(function (response) {
                    return response || fetch(request)
                        .catch(function () {
                            // Si la solicitud es para una imagen, muestre un marcador de posicion sin conexion
                            if (request.headers.get('Accept').indexOf('image') !== -1) {
                                return new Response('<svg width="200" height="100" role="img" aria-labelledby="offline-title" viewBox="0 0 200 100" xmlns="http://www.w3.org/2000/svg"><title id="offline-title">Offline</title><g fill="none" fill-rule="evenodd"><path fill="#D8D8D8" d="M0 0h400v300H0z"/><text fill="#9B9B9B" font-family="Helvetica Neue,Arial,Helvetica,sans-serif" font-size="72" font-weight="bold"><tspan x="93" y="172">offline</tspan></text></g></svg>', { headers: { 'Content-Type': 'image/svg+xml' }});
                            }
                        });
                })
        );
    });
