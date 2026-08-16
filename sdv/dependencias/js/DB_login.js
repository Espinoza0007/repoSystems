function DB_GuardarCredenciales(arrg_data){
    var itemsProcesado = 1;
    var active = dataBaseAppSDV.result;
    var transaction = active.transaction('tbl_usuarios', "readwrite");
    var objectStore = transaction.objectStore('tbl_usuarios');
    arrg_data.forEach(function(insertar,index, arrayinsertar){
        var request = objectStore.put(insertar);
        request.onerror = function (e) {
            console.log('Llave repetida.');
        };
        if(itemsProcesado == arrayinsertar.length){
            location.href = arrg_data[0]['ruta_app'];
        }
        itemsProcesado++;
    });
}
/*Tabla Guardar Credenciales,Actualizar Credenciales*/
function DB_limpiarTablaGC(tabla, arrg_data) {
    if (!dataBaseAppSDV || !dataBaseAppSDV.result) {
        console.error("IndexedDB aún no está listo. Reintentando...");
        setTimeout(() => DB_limpiarTablaGC(tabla, arrg_data), 500); // Reintentar después de 500ms
        return;
    }

    try {
        var active = dataBaseAppSDV.result;
        var transaction = active.transaction(tabla, "readwrite");
        var objectStore = transaction.objectStore(tabla);

        var objectStoreRequest = objectStore.clear();

        objectStoreRequest.onsuccess = function (event) {
            console.log("Tabla " + tabla + " limpiada correctamente.");
        };

        objectStoreRequest.onerror = function (event) {
            console.error("Error al limpiar la tabla " + tabla + ": ", event.target.error);
        };

        transaction.oncomplete = function (event) {
            console.log("Transacción completada.");
            DB_GuardarCredenciales(arrg_data); 

            // Esperar 1 segundo y luego cargar credenciales
            setTimeout(function () {
                cargar_credenciales_sdv().then(() => {
                    console.log("Credenciales cargadas correctamente en DB_limpiarTablaGC.");
                }).catch((error) => {
                    console.error("Error al cargar credenciales después de limpiar la tabla:", error);
                });
            }, 1000);
        };

        transaction.onerror = function (event) {
            console.error("Error en la transacción: ", event.target.error);
        };

    } catch (error) {
        console.error("Error inesperado en DB_limpiarTablaGC: ", error);
    }
}

