var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
var dataBaseAppSDV = null;
var cantidad_idx_us = 0;
var arrg_dataC = [];
$( document ).ready(function() {
    DB_IniciarCPSesion(0);
    $("#usuario").keyup(function() {
        V_Usuario($("#usuario").val(),'usuario',1,'Usuario');
    });
    $("#contrasena").keyup(function() {
        V_Contrasena($("#contrasena").val(),'contrasena',2,'Contraseña');
    });
    $('#tipousuario').on('change',function(){
        V_Selec($("#tipousuario").val(),'tipousuario',0,'Tipo de Usuario');
    });
});
function Iniciar_Sesion(){
    var detalle_validacion = '';
    var contarok = 0;
    contarok    += V_Usuario($("#usuario").val(),'usuario',1,'Usuario');
    contarok    += V_Contrasena($("#contrasena").val(),'contrasena',2,'Contraseña');
    if(contarok < 2){
        arrg_vali_result.forEach( function(valor, indice, array) {
            if(!_empty(valor)){
                detalle_validacion += `<p>${valor}</p>`;
            }else{}
        });
        Swal.fire({
            title: '<strong>Aviso!</strong>',
            type: 'info',
            html:detalle_validacion,
            confirmButtonText:'Ok'
        });
    }else{
        ntipoUs = '';
        var usuario = $("#usuario").val();
        var datas = $("#login_form").serializeArray();
        $.when( $(".carga-esconder").stop(true,true).hide() ).done(function( x ) {
            $.when( $(".carga-class").stop(true,true).show() ).done(function( x ) {
                $.ajax({
                    url      : 'index.php/login/consultar',
                    type     : 'POST',
                    dataType : 'JSON',
                    data     : datas,
                    timeout  : 9777
                }).done(function(_resp){
                    if(_resp.rs == true){
                        $ok = 'ok';
                    }else{
                        arrg_dataC = [];
                    }
                }).always(function(_resp, textStatus, errorThrown) {
                    $.when( $(".carga-class").stop(true,true).hide() ).done(function( x ) {
                        $.when( $(".carga-esconder").stop(true,true).show() ).done(function( x ) {
                            if (textStatus == "success") {
                                if(_resp.rs == true){
                                    DB_limpiarTablaGC('tbl_usuarios',_resp.ls_resultado)
                                }else{
                                    console.log('ERROR CAMPOS');
                                    arrg_dataC = [];
                                    Swal.fire({
                                        title: 'Aviso Importante!',
                                        type: 'info',
                                        html:'<h5>'+_resp.errores+'</h5>',
                                        confirmButtonText:'Ok'
                                    });
                                }
                            }else{
                                _ajax_error_validacion(_resp.status,_resp.readyState,_resp.statusText);
                            }
                        });
                    });
                });
            });
        });
    }
}