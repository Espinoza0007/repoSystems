function form_abrir(input_name,input_value,atributos_param){
    var atributos=``;
    $.each(atributos_param, function(i, val){
        atributos+=i+`="${val}" `;
        atributos = atributos.replace('_form', '');
    });
    var form = `<form ${atributos}><br>
                <input type="hidden" name="${input_name}" value="${input_value}">`;
    return form;
}
function form_cerrar(){
    return `</form>`;
}
function form_input(parametros){
    var atributos=``;
    $.each(parametros, function(i, val){
        atributos+=i+`="${val}" `;
        atributos = atributos.replace('_input', '');
    });
    var input =`<input ${atributos}>`;
    return input;
}
function form_dropdown(nombre,arrglista,seleccionar,atributos_extra){
    var extra=``;
    $.each(atributos_extra, function(i, val){
        extra+=i+`="${val}" `;
        extra = extra.replace('_input', '');
    });    
    var dropdown = `<select id="${nombre}" name="${nombre}" ${extra} data-width="100%"> required`;
    dropdown += `<option value="" selected>Seleccione una opci&oacute;n</option>`;
    $.each(arrglista, function(i, val){
        //alert(val[0]);
        if(seleccionar == val.valor){
            dropdown += `<option value="${val.codbx}" selected>${val.valor}</option>`;
        }else{
            dropdown += `<option value="${val.codbx}">${val.valor}</option>`;
        }   
    });
    dropdown+=`</select>
    <div class="valid-feedback">
        <strong></strong>
    </div>
    <div class="invalid-feedback">
        <strong> Por favor selecciona una opci&oacute;n de la lista! </strong>
    </div>`;
    return dropdown;
}
function mensaje_alerta(parametros,extra_datos){
    var divhtml=``;
    divhtml=`
    <div id='content-mjs' class='alert alert-${parametros.cla} alert-dismissible fade show' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
        ${parametros.info}
    </div>
    ${extra_datos}`;
    return divhtml;
}

function empty(e) {
  switch (e) {
    case "":
    case null:
    case false:
    case typeof this == "undefined":
      return true;
    default:
      return false;
  }
}

function obtener_param_url(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}