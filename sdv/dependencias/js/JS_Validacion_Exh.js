function V_input_Entero(indice_C, valor_C, Input_E, Div_E, Descrip_C, Descrip_Exh) {
    var ContaCorrecto = 0;
    if ($("#"+Input_E + indice_C).val() != "") {
        if (parseInt(valor_C) >= 0) {
            var data_E = /^[0-9]{1,3}$/gm
            if (data_E.test(String(valor_C))) {
                $("#" + Input_E + indice_C).removeClass("is-invalid").addClass("is-valid");
                $("#" + Div_E + indice_C).html('');
                ContaCorrecto += 1;
            } else {
                $("#" + Input_E + indice_C).removeClass("is-valid").addClass("is-invalid");
                $("#" + Div_E + indice_C).html('En la cantidad <strong>' + Descrip_C + '</strong> solo se permiten n&uacute;meros enteros positivos de maximo 3 digitos.');
                ContaCorrecto += 0;
            }
        } else {
            $("#" + Input_E + indice_C).removeClass("is-valid").addClass("is-invalid");
            $("#" + Div_E + indice_C).html('En la cantidad <strong>' + Descrip_C + '</strong> solo se permiten n&uacute;meros enteros positivos de maximo 3 digitos.');
            ContaCorrecto += 0;
        }
    } else {
        $("#" + Input_E + indice_C).removeClass("is-valid").addClass("is-invalid");
        $("#" + Div_E + indice_C).html('La cantidad <strong>' + Descrip_C + '</strong> es obligatorio.');
        ContaCorrecto += 0;
    }
    return ContaCorrecto;
}
function V_input_EnteroMYQc(indice_C, valor_C, Input_E, Div_E, Descrip_C, Descrip_Exh) {
    var ContaCorrecto = 0;
    if ($("#"+Input_E + indice_C).val() != "") {
        if (parseInt(valor_C) > 0) {
            var data_E = /^[0-9]{1,3}$/gm
            if (data_E.test(String(valor_C))) {
                $("#" + Input_E + indice_C).removeClass("is-invalid").addClass("is-valid");
                $("#" + Div_E + indice_C).html('');
                ContaCorrecto += 1;
            } else {
                $("#" + Input_E + indice_C).removeClass("is-valid").addClass("is-invalid");
                $("#" + Div_E + indice_C).html('En la cantidad <strong>' + Descrip_C + '</strong> solo se permiten n&uacute;meros enteros positivos de maximo 3 digitos.');
                ContaCorrecto += 0;
            }
        } else {
            $("#" + Input_E + indice_C).removeClass("is-valid").addClass("is-invalid");
            $("#" + Div_E + indice_C).html('En la cantidad <strong>' + Descrip_C + '</strong> solo se permiten n&uacute;meros enteros positivos de maximo 3 digitos mayor que cero.');
            ContaCorrecto += 0;
        }
    } else {
        $("#" + Input_E + indice_C).removeClass("is-valid").addClass("is-invalid");
        $("#" + Div_E + indice_C).html('La cantidad <strong>' + Descrip_C + '</strong> es obligatorio.');
        ContaCorrecto += 0;
    }
    return ContaCorrecto;
}