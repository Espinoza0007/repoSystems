function V_Usuario(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-z0-9]+$/g
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
        $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> es obligatorio.');
    }else{
        if(data_C.length>15){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> no puede exceder los 15 caracteres';
            $("#error-mjs-"+ordencampo).html('El <strong>'+etiqueta+'</strong> no puede exceder los 15 caracteres');
        }else{        
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#error-mjs-"+ordencampo).html('Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros');
                arrg_vali_result[ordencampo] = 'Por favor verifique el <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros';
            }
        }
    }
    return v;
}
function V_Contrasena(data,campo,ordencampo,etiqueta){
    var data_C=data.trim();
    var v = 0;
    var data_E=/^[A-Za-z0-9]+$/g
    if(_empty(data_C)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> es obligatoria.';
        $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> es obligatoria.');
    }else{
        if(data_C.length>15){
            v = 0;
            $("#"+campo).removeClass("is-valid").addClass("is-invalid");
            arrg_vali_result[ordencampo] = 'La <strong>'+etiqueta+'</strong> no puede exceder los 15 caracteres';
            $("#error-mjs-"+ordencampo).html('La <strong>'+etiqueta+'</strong> no puede exceder los 15 caracteres');
        }else{        
            if(data_E.test(String(data_C))){
                v = 1;
                $("#"+campo).removeClass("is-invalid").addClass("is-valid");
                arrg_vali_result[ordencampo] = '';
            }else{
                v = 0;
                $("#"+campo).removeClass("is-valid").addClass("is-invalid");
                $("#error-mjs-"+ordencampo).html('Por favor verifique la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros');
                arrg_vali_result[ordencampo] = 'Por favor verifique la <strong>'+etiqueta+'</strong>, solo se permiten letras, n&uacute;meros';
            }
        }
    }
    return v;
}
function V_Selec(data,campo,ordencampo,etiqueta){
    var v = 0;
    if(_empty(data)){
        v = 0;
        $("#"+campo).removeClass("is-valid").addClass("is-invalid");
        arrg_vali_result[ordencampo] = 'El <strong>'+etiqueta+'</strong> es obligatorio.';
    }else{
        v = 1;
        $("#"+campo).removeClass("is-invalid").addClass("is-valid");
        arrg_vali_result[ordencampo] = '';
    }
    return v;
}