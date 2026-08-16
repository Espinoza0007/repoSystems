var colores_rutas = [];var colores_poligonos = [];
var json_point = new Array();
var a_c = 0;var json = '';
var collection = {
    "type": "FeatureCollection",
    "features": []
};
var Json_KML_X_Ruta = new Array();var Lis_Rutas = '';
var Fusion_KML = ``;
$(document).ready(function(e){
    Cargar_CoordenadasReg();
    // VRF_Puntos_x_Poligonos();
});
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
function Cargar_CoordenadasReg(){
    $.ajax({
        url      : 'ls_marcaciones/coordenadas',
        type     : 'POST',
        dataType : 'JSON',
        data     : {},
        timeout  : 60777
    }).done(function(_resp){
        while (colores_rutas.length < 100) {
            do {
                var color = Math.floor((Math.random()*1000000)+1);
            } while (colores_rutas.indexOf(color) >= 0);
            colores_rutas.push("ff" + ("000000" + color.toString(16)).slice(-6));
        }
        while (colores_poligonos.length < 100) {
            do {
                var color = Math.floor((Math.random()*1000000)+1);
            } while (colores_poligonos.indexOf(color) >= 0);
            colores_poligonos.push("#" + ("000000" + color.toString(16)).slice(-6));
        }
        Lis_Rutas = _resp.ls_rutas;
        Cargar_Mapa(_resp.ls_rutas,_resp.ls_coordenadas,_resp.ls_poligonos);
        // console.log(_resp.ls_poligonos);
    }).fail(function(status, textStatus, errorThrown) {
        _ajax_error_(status,textStatus,errorThrown);
    });
}
function Exportar_KML(){
    $.ajax({
        url      : 'exportar_kml/coordenadas',
        type     : 'POST',
        dataType : 'JSON',
        data     : {Json_KML:JSON.stringify(Json_KML_X_Ruta)},
        timeout  : 60777
    }).done(function(_resp){
        var linkreports = $("<a>");
        var url_zelda = '';var fecha_rep = FechaReporte();
        url_zelda = _resp.Doc;
        linkreports.attr("href",'../TemporalZip/'+url_zelda);
        $("body").append(linkreports);
        linkreports.attr("download",'Coordenadas_'+fecha_rep);
        linkreports[0].click();
        linkreports.remove();   
    }).fail(function(status, textStatus, errorThrown) {
        _ajax_error_(status,textStatus,errorThrown);
    });
}
function VRF_Puntos_x_Poligonos(){
    $.ajax({
        url      : 'validar/coordenadas',
        type     : 'POST',
        dataType : 'JSON',
        data     : {validar:ok},
        timeout  : 60777
    }).done(function(_resp){
        console.log(_resp);
    }).fail(function(status, textStatus, errorThrown) {
        _ajax_error_(status,textStatus,errorThrown);
    });
}
function Cargar_Mapa(arrg_rutas,arrg_lat_long,arrg_poligonos){
    $("#map").attr("style","height: 88%;width: 90%;position: absolute;display: ;margin-left:auto;margin-right:auto;left:0;right:0;")
    var map = new L.Map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18,
        drawControl: true
    }).addTo(map);
    map.attributionControl.setPrefix('');
    map.setView(new L.LatLng(13.8073822, -88.768435),9);

    var c_c = 0;var c_p = 0;

    arrg_rutas.forEach(function(filall,index, arrgfilall){
        // console.log(filall.Ru_Id);
        var Ru_Id_S = filall.Ru_Id;
        Ru_Id_S = Ru_Id_S.toString();
        /*----------------------------------------
         * CONSTRUCCION DE POLIGONOS *************
         */
        var geojsonFeaturePolygon = [
            {
                "type": "Feature",
                "properties": {
                    "tipo": Ru_Id_S,
                    "name":Ru_Id_S
                },
                "geometry": {
                    "type": "Polygon",
                    "coordinates": [
                        arrg_poligonos[Ru_Id_S]
                    ]
                }
            }
        ];
        var polygon = new L.geoJson(geojsonFeaturePolygon, {
            onEachFeature: popUpInfo
        }).addTo(map);



        polygon.setStyle({fillColor: colores_poligonos[c_c],color:colores_poligonos[c_c]});
        /*-----------------------------------------------
         * CONSTRUCCION DE PUNTOS MARCACION *************
         */
        arrg_lat_long[filall.Ru_Id].forEach(function(fd,index, arrgfilall){
            var lon = fd.Longitud;
            var lat = fd.Latitud;
            var popupText = fd.Usu_usuario +' '+ fd.Ruta;
            var Lon_Lat_S = lon+','+lat;
            Lon_Lat_S = Lon_Lat_S.toString();
            // console.log(Lon_Lat_S);
            Fusion_KML += `<Placemark><name>${fd.Ruta}</name><Style><IconStyle><color>${colores_rutas[c_c]}</color><scale>1</scale><Icon><href>https://www.gstatic.com/mapspro/images/stock/503-wht-blank_maps.png</href></Icon><hotSpot x="32" xunits="pixels" y="64" yunits="insetPixels"/></IconStyle></Style><ExtendedData><Data name="tipo"><value>${popupText}</value></Data><Data name="name"><value>${fd.Ruta}</value></Data></ExtendedData><Point><coordinates>${Lon_Lat_S}</coordinates></Point></Placemark>`;
            var geojsonFeaturePoint = {
                "type": "Feature",
                "properties": {
                    "tipo": popupText,
                    "name":fd.Ruta
                },
                "style": {
                    "__comment": "all SVG styles allowed",
                    "fill":colores_rutas[c_c],
                    "stroke-width":"3",
                    "fill-opacity":0.6
                },
                "geometry": {
                    "type": "Point",
                    "coordinates": [lon, lat ]
                }
            };
            var MarkerOptions = {
                radius: 8,
                fillColor: colores_rutas[c_c],
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            };
            var point = new L.geoJson(geojsonFeaturePoint, {
                pointToLayer: function (feature, latlng) {
                    return L.circleMarker(latlng, MarkerOptions);
                },
                onEachFeature: popUpInfo
            }).addTo(map);
            collection.features.push(geojsonFeaturePoint);
            // collection.features.push(geojson);
            c_p++;
        });
        Json_KML_X_Ruta[Ru_Id_S] = Fusion_KML;
        Fusion_KML = ``;
        c_p = 0;
        c_c++;
    });
}
function popUpInfo(feature, layer) {
    // does this feature have a property named popupContent?
    if (feature.properties && feature.properties.tipo) {
        layer.bindPopup(feature.properties.tipo);
    }
}
function _ajax_error_(jqXHR, textStatus, errorThrown){
    if ( textStatus === 'timeout'){
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error de tiempo de espera, volver a cargar la pagina por favor.</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 0) {
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html:'<h3>Sin conexión a intenet....</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR === 200) {
        Swal.fire({
            title: 'Aviso!',
            type: 'warning',
            html:'<h3>Sin conexión a intenet....</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }else if (jqXHR == 404) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Página solicitada no encontrada[404]</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (jqXHR == 500) {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error de servidor interno [500].</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (textStatus === 'parsererror') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else if (textStatus === 'abort') {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>No pudimos establecer conexión con el servidor, por favor intente de nuevo...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    } else {
        Swal.fire({
            title: 'Aviso!',
            type: 'error',
            html:'<h3>Error desconocido, por favor contactar con Sistemas de Venta...</h3>',
            confirmButtonText:'Ok'
        });
        return;
    }
}