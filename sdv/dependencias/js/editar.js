var total_import = 0;
var arrgocargas = "";
function insertar_registros(indice,arrgocargas){
    var contanu = 0;
    // alert(arrgocargas.length);
    if(indice < arrgocargas.length){
        $.ajax({
            url:'cargarmigrareditar/cargarregistro',
            type:"POST",
            data:{arrgdata:arrgocargas[indice],totalahora:indice + 1,totalimport:total_import},
            dataType: "JSON",
            timeout:34777
            }).done(function(respuesta) {
                contanu = indice + 1;
                if(respuesta.rs == false){
                    alertify.error('El registro no editado...');
                    indice++;
                }else{
                    alertify.success('Registro editado exitosamente!');
                    insertar_registros(indice + 1,arrgocargas);                  
                }
                $('.progress-bar').css('width', respuesta.percentage+'%');
                $('.progress-bar').text(respuesta.percentage+'%');
                $('.progress-bar').attr('data-progress', respuesta.percentage);


                $("#tocargados").text(contanu);
            }).fail(function() {
               
            });
    }else{

        var html_mjs = `<br>
            <div class="alert alert-success" role="alert">
              <h4>Clientes Editados Correctamente.</h4>
            </div>`;
        $("#mjsinfo").html(html_mjs);

    }   
}

function totalimportar(){
    $("#spinner-load").show("fast");
    $.ajax({
    url:'migrartotaleditar/totalcli',
    type:"POST",
    data:{hola:'hola'},
    dataType: "JSON",
    timeout:34777
    }).done(function(r) {
        $("#spinner-load").hide("fast",function(){
            total_import = r.total;
            $("#toacargar").text(r.total);
            arrgocargas = r.insertar;
            // console.log(arrgocargas[0]);
            insertar_registros(0,arrgocargas);
        });
    }).fail(function() {

    });
}
