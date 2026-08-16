var cargaSolo = 0;
var map;
var Recordar_Fpais = "";
var Recordar_Fdivision = "";
var Recordar_Fcanal = "";
var Recordar_Fdistrito = "";
var Recordar_Ftipoexhibidor = "";
var Recordar_Fexhibidor = "";
var Recordar_Fruta = "";
var V_Clean = 0;
$(document).ready(function(e){
    Cargar_Funciones(1);
    FiltrosDeBusqueda(6);
    $(document).on("click",".page-TablaClteCensados",function(){
        cargaSolo = 1;
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {});
        $page = $(this).attr('href');
        $pageind = $page.indexOf('page=');
        $page = $page.substring(($pageind+5));
        Tabla_ClteConCenso($page);
        return false;
    });
    $(document).on("click", ".SelecExhibidor", function() {
        var idx_exh = $(this).attr("id");
        idx_exh = idx_exh.substring(12,idx_exh.length);
        map.remove();
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'ls_clteafiches/infoexhibidor',
                type     : 'POST',
                dataType : 'JSON',
                data     : {'idx_exh': idx_exh},
                timeout  : 10777
            }).done(function(_resp){
                var exhibidor_espec = 0;
                if (_resp.InfoExh[0].Ste_Cat_Id != '85000000001' && 
                    _resp.InfoExh[0].Ste_Cat_Id != '99999999999' && 
                    _resp.InfoExh[0].Ste_Cat_Id != '11111111111' && 
                    _resp.InfoExh[0].Ste_Cat_Id != '22222222222' &&
                    _resp.InfoExh[0].Ste_Cat_Id != '33333333333' &&
                    _resp.InfoExh[0].Ste_Cat_Id != '44444444444' &&
                    _resp.InfoExh[0].Ste_Cat_Id != '55555555555' &&
                    _resp.InfoExh[0].Ste_Cat_Id != '88888888888' &&
                    _resp.InfoExh[0].Ste_Cat_Id != '12121212121' &&
                    _resp.InfoExh[0].Ste_Cat_Id != '13131313131'
                ){
                    exhibidor_espec = 0;
                }else{
                    exhibidor_espec = 1;
                }
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    $("#foto_exhibidor").attr("src",_resp.InfoExh[0].Ste_foto);
                    if( exhibidor_espec == 1 ){
                        var tipoExhibidor = _resp.InfoExh[0].Ste_tipo_exhibidor.split(','); var cheched_te = [];
                        (tipoExhibidor[0] == '1') ? $("#d_checktipoexhg").prop("checked", true) : $("#d_checktipoexhg").prop("checked", false);
                        (tipoExhibidor[1] == '1') ? $("#d_checktipoexhs").prop("checked", true) : $("#d_checktipoexhs").prop("checked", false);
                        (tipoExhibidor[2] == '1') ? $("#d_checktipoexhc").prop("checked", true) : $("#d_checktipoexhc").prop("checked", false);
                        (tipoExhibidor[3] == '1') ? $("#d_checktipoexho").prop("checked", true) : $("#d_checktipoexho").prop("checked", false);
                        $("#d_txtrt").val(_resp.InfoExh[0].Ste_cantidad_RT);
                        $("#d_txtpq").val(_resp.InfoExh[0].Ste_cantidad_PQ);
                        $("#d_txtpines").val(_resp.InfoExh[0].Ste_cantidad_PINES);
                        $("#d_txtun").val(_resp.InfoExh[0].Ste_cantidad_UN);
                        $("#d_txtbolsas").val(_resp.InfoExh[0].Ste_cantidad_BOLSAS);
                        $("#d_txtbotes").val(_resp.InfoExh[0].Ste_cantidad_BOTES);
                        $("#d_txtcaras").val(_resp.InfoExh[0].Ste_cantidad_CARAS);
                        $("#d_observacion_exh").val(_resp.InfoExh[0].Ste_estado);
                        $.when( $("#mjs_div_exh").stop(true,true).hide() ).done(function( x ) {
                            $.when( $("#content_infoexh").stop(true,true).show() ).done(function( x ) {
                            });
                        });
                    }else{
                        $.when( $("#content_infoexh").stop(true,true).hide() ).done(function( x ) {
                            $.when( $("#mjs_div_exh").stop(true,true).show() ).done(function( x ) {
                            });
                        });
                    }
                    setTimeout(function() {
                        iniciar_mapa(_resp.InfoExh[0].Ste_latitud_obs,_resp.InfoExh[0].Ste_longitud_obs,_resp.InfoExh[0].Cli_latitud,_resp.InfoExh[0].Cli_longitud);
                    }, 1000);
                });
            }).fail(function(status, textStatus, errorThrown) {
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    console.log(errorThrown);
                });
            });
        });
    });
    $('#Content_tabla_lsClteCensados').on('click','.abrirmodal',function(){
        $("#content-mapa").empty().html("<div id='map' style='width: 100%; height: 100%;'></div>");
        var idx_cliente = $(this).attr("id");
        idx_cliente = idx_cliente.substring(10,idx_cliente.length);
        $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
            $.ajax({
                url      : 'ls_clteafiches/xclienteAfiche',
                type     : 'POST',
                dataType : 'JSON',
                data     : {'idx_cliente': idx_cliente},
                timeout  : 60777
            }).done(function(_resp){
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    if(_resp.rs == true){
                        var divUno_html = ``;
                        setTimeout(function() {
                        iniciar_mapa(_resp.xcliente.Ste_latitud_obs,_resp.xcliente.Ste_longitud_obs,_resp.xcliente.Cli_latitud,_resp.xcliente.Cli_longitud);
                        }, 1000);
                        divUno_html += `
                        <table id="tabla-infop">
                            <thead>
                                <tr>
                                    <th colspan="2" class="titulo-principal">Información General</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="tabla-tituloCol">Ruta:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Nombre_Ruta}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Código:</td>
                                    <td class="tabla-infop">${_resp.xcliente.CodigoCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Nombre:</td>
                                    <td class="tabla-infop">${_resp.xcliente.NombreCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Dirección:</td>
                                    <td class="tabla-infop">${_resp.xcliente.DireccionCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Contacto:</td>
                                    <td class="tabla-infop">${_resp.xcliente.ContactoCliente}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Teléfono:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Telefono}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">País:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Nombre_Pais}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">Canal:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Canal}</td>
                                </tr>
                                <tr>
                                    <td class="tabla-tituloCol">División:</td>
                                    <td class="tabla-infop">${_resp.xcliente.Division}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>`;
                        var DivFotohtml = ``;
                        DivFotohtml = `
                        <table id="tabla_foto" class="tabla_foto">
                            <tr>
                                <th class="titulo-foto">Foto del exhibidor</th>
                            </tr>
                            <tr>
                                <td>
                                   <img src="../dependencias/imagenes/icon_256.png" id="foto_exhibidor">
                                </td>
                            </tr>
                        </table>`;
                        $("#content-infog").empty().html(divUno_html);
                        $("#content-infof").empty().html(DivFotohtml);
                        var divDos_html = ``;
                        divDos_html = `
                        <table id="tabla_exhibidor">
                            <tr>
                                <th class="titulo-exh" colspan="2">EXHIBIDORES QUE TIENE</th>
                            </tr>`;
                        if(Object.entries(_resp.xexhibidorqt).length === 0){
                            divDos_html +=`
                            <tr>
                                <td class="Observ"><span class="vya fas fa-info fa-3x"></span></td>
                                <td class='sin_exhibidor'>
                                    <span>SIN EXHIBIDORES</span>
                                </td>
                            </tr>`;
                        }else{
                            _resp.xexhibidorqt.forEach(function(filall,index, arrgfilall){
                                divDos_html +=`
                                 <tr id="SelOptExhQt_${filall.Ste_token}" class="SelecExhibidor">
                                    <td class="Observ">`;
                                    if(filall.Ste_estado == 1){
                                        divDos_html +=`<span class="vya fas fa-check-double fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 2){
                                        divDos_html +=`<span class="malub fas fa-arrows-alt fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 3){
                                        divDos_html +=`<span class="invad fas fa-exclamation-triangle fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 4){
                                        divDos_html +=`<span class="necer fas fa-tools fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 5){
                                        divDos_html +=`<span class="deseg fas fa-trash-alt fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 6){
                                        divDos_html +=`<span class="retig fas fa-ban fa-3x"></span>`;
                                    }else{
                                        divDos_html +=`<span class="defaultcolor fas fa-question-circle fa-3x"></span>`;
                                    }
                                divDos_html +=`
                                    </td>
                                    <td>
                                        <div class='seg' style="width:100%;margin:0 auto;">
                                            <div class='seg_i'>${filall.Ste_Cat_Id}</div>
                                            <div class='seg_d'><span class=''></span> ${filall.Cat_descripcion}</div>
                                        </div>
                                    </td>
                                </tr>`;
                            });
                        }
                        divDos_html +=`</table>`;
                        var divCuatro_html = ``;
                        divCuatro_html = `
                        <table id="tabla_exhibidor">
                            <tr>
                                <th class="titulo-exh" colspan="2">EXHIBIDORES DEVUELTOS</th>
                            </tr>`;
                        if(Object.entries(_resp.xexhibidorde).length === 0){
                            divCuatro_html +=`
                            <tr>
                                <td class="Observ"><span class="vya fas fa-info fa-3x"></span></td>
                                <td class='sin_exhibidor'>
                                    <span>SIN EXHIBIDORES</span>
                                </td>
                            </tr>`;
                        }else{
                            _resp.xexhibidorde.forEach(function(filall,index, arrgfilall){
                                divCuatro_html +=`
                                 <tr id="SelOptExhDv_${filall.Ste_token}" class="SelecExhibidor">
                                    <td class="Observ">`;
                                    if(filall.Ste_estado == 1){
                                        divCuatro_html +=`<span class="vya fas fa-check-double fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 2){
                                        divCuatro_html +=`<span class="malub fas fa-arrows-alt fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 3){
                                        divCuatro_html +=`<span class="invad fas fa-exclamation-triangle fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 4){
                                        divCuatro_html +=`<span class="necer fas fa-tools fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 5){
                                        divCuatro_html +=`<span class="deseg fas fa-trash-alt fa-3x"></span>`;
                                    }else if(filall.Ste_estado == 6){
                                        divCuatro_html +=`<span class="retig fas fa-ban fa-3x"></span>`;
                                    }else{
                                        divCuatro_html +=`<span class="defaultcolor fas fa-question-circle fa-3x"></span>`;
                                    }
                                divCuatro_html +=`
                                    </td>
                                    <td>
                                        <div class='segdos' style="width:100%;margin:0 auto;">
                                            <div class='seg_i'>${filall.Ste_Cat_Id}</div>
                                            <div class='seg_d'><span class=''></span> ${filall.Cat_descripcion}</div>
                                        </div>
                                    </td>
                                </tr>`;
                            });
                        }
                        divCuatro_html +=`</table>`;
                        var htmlCapacidadExh = ``;
                        htmlCapacidadExh = `
                        <div id="content_infoexh" style="display:none;">
                        <div style="border-collapse: separate;font-weight:600;height: 48px;line-height: 40px;border: 2px solid #fff;color:#fff;margin:0 auto;text-align: center;background-color: #2A2A2A;">CAPACIDAD DEL EXHIBIDOR</div>
                        <div class="form-check">
                            <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Tipo de Exhibidor</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input GR_Check chka chksegu" id="d_checktipoexhg" name="d_checktipoexh[]" value='1'>
                                <label class="custom-control-label" for="d_checktipoexhg">GALLETA&nbsp;&nbsp;</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input GR_Check chka chksegu" id="d_checktipoexhs" name="d_checktipoexh[]" value='1'>
                                <label class="custom-control-label" for="d_checktipoexhs">SNACK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input GR_Check chka chksegu" id="d_checktipoexhc" name="d_checktipoexh[]" value='1'>
                                <label class="custom-control-label" for="d_checktipoexhc">CEREALES</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input GR_Check chka chksegu" id="d_checktipoexho" name="d_checktipoexh[]" value='1'>
                                <label class="custom-control-label" for="d_checktipoexho">CONFITERIA</label>
                            </div>
                        </div>         
                        <div id="d_bcapacidadexh"><br>
                        <div class="container __p_form">
                            <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Capacidad del Exhibidor</label>
                                <div class="row">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Ristra (RT):</span></div>
                                    <div class="col">
                                        <input type="tel" id="d_txtrt" name="d_txtrt[]" class="form-control" placeholder="0" value="" autocomplete="off">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjsr-"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Paquete (PQ):</span></div>
                                    <div class="col">
                                        <input type="tel" id="d_txtpq" name="d_txtpq[]"class="form-control" placeholder="0" value="" autocomplete="off">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjsp-"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Pines:</span></div>
                                    <div class="col">
                                        <input type="tel" id="d_txtpines" name="d_txtpines[]" class="form-control" placeholder="0" value="" autocomplete="off">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjspn-"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Unidad (PRODUCTO FAMILIAR):</span></div>
                                    <div class="col">
                                        <input type="tel" id="d_txtun" name="d_txtun[]" class="form-control" placeholder="0" value="" autocomplete="off">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjsun-"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Bolsas:</span></div>
                                    <div class="col">
                                        <input type="tel" id="d_txtbolsas" name="d_txtbolsas[]" class="form-control" placeholder="0" value="" autocomplete="off">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjsbl-"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Botes:</span></div>
                                    <div class="col">
                                        <input type="tel" id="d_txtbotes" name="d_txtbotes[]" class="form-control" placeholder="0" value="" autocomplete="off">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjsbt-"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md"><span class="fa fa-asterisk fa-lg"></span> <span class="grtritu___">Caras de Exhibición:</span></div>
                                    <div class="col">
                                        <input type="tel" id="d_txtcaras" name="d_txtcaras[]" class="form-control" placeholder="0" value="" autocomplete="off">
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback" id="d_error-mjscara-"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="form-group">
                        <label style="font-size:20px;margin-top:5px;text-transform: uppercase;text-decoration:underline;">Estado del Exhibidor</label>
                        <select class="custom-select" id="d_observacion_exh" name="d_observacion_exh[]">
                            <option value="" hidden>Elige una opción...</option>
                            <option value="1">VISIBLE Y ACCESIBLE</option>
                            <option value="2">MAL UBICADO</option>
                            <option value="3">INVADIDO</option>
                            <option value="4">NECESITA REPARACION</option>
                            <option value="5">DESECHADO O GUARDADO POR EL CLIENTE</option>
                            <option value="6">RETIRADO DEL NEGOCIO</option>
                            <option value="7">EN BODEGA</option>
                        </select>
                    </div>
                    </div>
                    <table id="mjs_div_exh" class="tabla_foto">
                        <tr>
                            <th class="titulo-foto">CAPACIDAD DEL EXHIBIDOR</th>
                        </tr>
                        <tr>
                            <td>
                            <img src="../dependencias/imagenes/icon_256.png" id="foto_exhibidor">
                            </td>
                        </tr>
                    </table>`;
                        $("#content-infoe").empty().html(divDos_html+divCuatro_html);
                        $("#content-infocpe").empty().html(htmlCapacidadExh);
                        $("#ModalAbrirExpendiente").modal("toggle");
                    }else{
                        $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                            Swal.fire({
                                title: 'Aviso!',
                                type: 'error',
                                html:'<h6>'+_resp.info+'</h6>',
                                confirmButtonText:'Ok'
                            });
                        });
                    }
                });
            }).fail(function(status, textStatus, errorThrown) {
                $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    _ajax_error_Exhibidores(status,textStatus,errorThrown);
                    console.log(errorThrown);
                });
            });
        });
    });
    $('#S_filtroPais').on('change','#filtropais',function(){
        V_Clean = 0;
        $('#filtrodivision').val('');
        $('#filtrocanales').val('');
        $('#filtrodistritos').val('');
        $('#filtrorutas').val('');
        $('#filtrodivision').prop('disabled', 'disabled');
        $('#filtrocanales').prop('disabled', 'disabled');
        $('#filtrodistritos').prop('disabled', 'disabled');
        $('#filtrorutas').prop('disabled', 'disabled');
        Cargar_Funciones(2);
    });
    $('#S_filtroDivision').on('change','#filtrodivision',function(){
        V_Clean = 0;
        $('#filtrocanales').val('');
        $('#filtrodistritos').val('');
        $('#filtrorutas').val('');
        $('#filtrocanales').prop('disabled', 'disabled');
        $('#filtrodistritos').prop('disabled', 'disabled');
        $('#filtrorutas').prop('disabled', 'disabled');
        Cargar_Funciones(3);
    });
    $('#S_filtroCanales').on('change','#filtrocanales',function(){
        V_Clean = 0;
        $('#filtrodistritos').val('');
        $('#filtrorutas').val('');
        $('#filtrodistritos').prop('disabled', 'disabled');
        $('#filtrorutas').prop('disabled', 'disabled');
        Cargar_Funciones(4);
    });
    $('#S_filtroDistritos').on('change','#filtrodistritos',function(){
        V_Clean = 0;
        $('#filtrorutas').val('');
        $('#filtrorutas').prop('disabled', 'disabled');
        Cargar_Funciones(5);
    });
    $('#S_filtroRuta').on('change','#filtrorutas',function(){
        V_Clean = 0;
        Cargar_Funciones(0);
    });
    $('#S_filtroTipoExhibidor').on('change','#filtrotipoexhibidor',function(){
        V_Clean = 0;
        $('#filtroexhibidores').val('');
        $('#filtroexhibidores').prop('disabled', 'disabled');
        Cargar_Funciones(7);
    });
    $('#S_filtrExhibidor').on('change','#filtroexhibidores',function(){
        V_Clean = 0;
        Cargar_Funciones(0);
    });
    $('#S_filtroCodigo').on('click','#btn_buscarCod',function(){
        V_Clean = 0;
        cargaSolo = 1;
        Cargar_Funciones(0);
    });
    $('#S_filtroCodigo').on('click','#btn_eliminarBus',function(){
        cargaSolo = 1;
        V_Clean =1;
        $("#filtrocodigos").val("");
        $("#filtropais").val("");
        $("#filtrodivision").val("");
        $("#filtrocanales").val("");
        $("#filtrodistritos").val("");
        $("#filtrotipoexhibidor").val("");
        $("#filtroexhibidores").val("");
        $("#filtrorutas").val("");
        Cargar_Funciones(0);
    });
});
function Cargar_Funciones(opt_select){
    $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
        Promise.all([
            Tabla_ClteConCenso(1)
        ])
        .then(respuestas =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                Swal.fire({
                    type: 'success',
                    title: 'REPORTE CARGADO EXITOSAMENTE!',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    FiltrosDeBusqueda(opt_select);
                });
            });
        })
        .catch(error =>{
            $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                Swal.fire({
                    type: 'error',
                    title: error,
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });
    });
}
function Tabla_ClteConCenso(page){
    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'clean', value: V_Clean});
    datas.push({name: 'page',value: page});
    return new Promise(function(resolve, reject){
        $.ajax({
            url      : 'ls_clteafiches/mostrar',
            type     : 'POST',
            dataType : 'JSON',
            data     : datas,
            timeout  : 20777
        }).done(function(_resp){
            if(_resp.rs == true){
                var tabla_html = ``;
                if(_resp.ls_clientes.length > 0){
                    tabla_html += `
                    <table id="tabla_CtleCensados" class="resp">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">RUTA</th>
                                <th scope="col">CÓDIGO</th>
                                <th scope="col">NOMBRE</th>
                                <th scope="col">CONTACTO</th>
                                <th scope="col">DIRECCIÓN</th>
                                <th scope="col">PAIS</th>
                                <th scope="col">DIVISION</th>
                                <th scope="col">CANAL</th>
                                <th scope="col">DISTRITO</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    _resp.ls_clientes.forEach(function(filall,index, arrgfilall){
                        tabla_html += `
                        <tr>
                            <td height="50px">
                                <button type="button" class="btn btn_carpeta abrirmodal" id="despliege_${filall.Id_Cliente}">
                                    <span class='fa fa-folder-plus fa-3x'></span>
                                </button>
                            </td>
                            <td>${filall.Nombre_Ruta}</td>
                            <td>${filall.CodigoCliente}</td>
                            <td>${filall.NombreCliente}</td>
                            <td>${filall.ContactoCliente}</td>
                            <td>${filall.DireccionCliente}</td>
                            <td>${filall.Nombre_Pais}</td>
                            <td>${filall.Division}</td>
                            <td>${filall.Canal}</td>
                            <td>${filall.Grupo}</td>
                        </tr>`;
                    });
                    tabla_html += `
                        </tbody>
                    </table>`;
                }else{
                    tabla_html +=`
                    <div class="alert alert-info" role="alert" style="margin-top:57px;">
                        <h4 class="alert-heading">Sin registros para mostrar!</h4>
                        <hr>
                        <span class="fas fa-folder-open fa-3x"></span>
                    </div>`;
                }
                $("#Content_tabla_lsClteCensados").empty().html(tabla_html);
                $("#pag_tabla_LSCtleC").empty().html(_resp.paginado);
                if(cargaSolo === 1){
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                    });
                }
                resolve(1);
            }else{
                Swal.fire({
                    title: 'Aviso!',
                    type: 'error',
                    html:'<h6>'+_resp.info+'</h6>',
                    confirmButtonText:'Ok'
                });
                reject(0);
            }
        }).fail(function(status, textStatus, errorThrown) {
            _ajax_error_Exhibidores(status,textStatus,errorThrown);
            reject(0);
        });
    });
}
function FiltrosDeBusqueda(opt_select){
    var SelectControl = [];
    if (opt_select == 1) {
        SelectControl[0] = 'S_filtroPais';
        SelectControl[1] = 'filtropais';
        SelectControl[2] = 'TODOS LOS PAISES';
    }else if (opt_select == 2) {
        SelectControl[0] = 'S_filtroDivision';
        SelectControl[1] = 'filtrodivision';
        SelectControl[2] = 'TODAS LAS DIVISIONES';
    }else if (opt_select == 3) {
        SelectControl[0] = 'S_filtroCanales';
        SelectControl[1] = 'filtrocanales';
        SelectControl[2] = 'TODOS LOS CANALES';
    }else if (opt_select == 4) {
        SelectControl[0] = 'S_filtroDistritos';
        SelectControl[1] = 'filtrodistritos';
        SelectControl[2] = 'TODOS LOS DISTRITOS';
    }else if (opt_select == 5) {
        SelectControl[0] = 'S_filtroRuta';
        SelectControl[1] = 'filtrorutas';
        SelectControl[2] = 'TODAS LAS RUTAS';
    }else if (opt_select == 6) {
        SelectControl[0] = 'S_filtroTipoExhibidor';
        SelectControl[1] = 'filtrotipoexhibidor';
        SelectControl[2] = 'TODOS LOS TIPOS';
    }else if (opt_select == 7) {
        SelectControl[0] = 'S_filtrExhibidor';
        SelectControl[1] = 'filtroexhibidores';
        SelectControl[2] = 'TODOS LOS EXHIBIDORES';
    }
    datas = $("#form_lsClteCensados").serializeArray();
    datas.push({name: 'page',value: 1});
    datas.push({name: 'opt_select',value: opt_select});
    $.ajax({
        url      : 'ls_clteafiches/filtrosReportExh',
        type     : 'POST',
        dataType : 'JSON',
        data     : datas,
        timeout  : 60777
    }).done(function(_resp){
        if(opt_select!=0){
            if(_resp.rs == true){
                var filtro_htmlSelect = ``; 
                filtro_htmlSelect += `<select class="form-control" id="${SelectControl[1]}" name="${SelectControl[1]}">
                <option value="">${SelectControl[2]}</option>`;
                _resp.ls_arrg_select.forEach(function(filall,index, arrgfilall){
                    if(opt_select == 3 || opt_select == 4 || opt_select == 6 || opt_select == 7){
                        filtro_htmlSelect+=`<option value="${filall.Descripcion}">${filall.Descripcion}</option>`;
                    }else{
                        filtro_htmlSelect+=`<option value="${filall.Id}">${filall.Descripcion}</option>`;
                    }
                });
                filtro_htmlSelect+=`</select>`;
                $("#"+SelectControl[0]).empty().html(filtro_htmlSelect);
                /*CONFIGURACION DE SELECT*/
                $.fn.select2.defaults.set("theme", "bootstrap");
                $( "#filtrodistritos" ).select2({
                    theme: "bootstrap"
                });
                $( "#filtrorutas" ).select2({
                    theme: "bootstrap"
                });
                $( "#filtrotipoexhibidor" ).select2({
                    theme: "bootstrap"
                });
                $( "#filtroexhibidores" ).select2({
                    theme: "bootstrap"
                });
            }else{
                Swal.fire({
                    title: 'Aviso!',
                    type: 'error',
                    html:'<h6>'+_resp.info+'</h6>',
                    confirmButtonText:'Ok'
                });
            }
        }
    }).fail(function(status, textStatus, errorThrown) {
        _ajax_error_Exhibidores(status,textStatus,errorThrown);
    });
}
function V_CoordenadasLL(data_C){
    var data_E=/^-?[0-9]\d*(\.\d+)?$/gm
    if(data_E.test(String(data_C))){
        return true;
    }else{
        return false;
    }
}
function iniciar_mapa(latiOB,longiOB,latiC,longiC) {
    $("#map").attr("style","height: 400px;width: 98%;margin:0 auto;");
    map = new L.Map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(map);
    if(V_CoordenadasLL(latiOB)){
        latiOB = latiOB;
    }else{latiOB = 0}
    if(V_CoordenadasLL(longiOB)){
        longiOB = longiOB;
    }else{longiOB = 0}
    if(V_CoordenadasLL(latiC)){
        latiC = latiC;
    }else{latiC = 0}
    if(V_CoordenadasLL(longiC)){
        longiC = longiC;
    }else{longiC = 0}
    map.setView(new L.LatLng(latiC, longiC),18);
    var circle = L.circle([latiC, longiC], {
        color: '#3ACA31',
        fillColor: '#51DF48',
        fillOpacity: 0.5,
        radius: 50
    }).addTo(map);
    var marker = L.marker([latiOB, longiOB]).addTo(map);
}
function _ajax_error_Exhibidores(jqXHR, textStatus, errorThrown){
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