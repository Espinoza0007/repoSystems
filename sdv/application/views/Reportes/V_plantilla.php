<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
.content_titulo{
background: rgba(255,255,255,1);
background: -moz-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(255,255,255,1)), color-stop(47%, rgba(246,246,246,1)), color-stop(100%, rgba(237,237,237,1)));
background: -webkit-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: -o-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: -ms-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed', GradientType=1 );
  border-right: 5px solid #575353;
  border-left: 5px solid #575353;
  width: 98%;
  /*height: 50px;*/
  text-align: center;
  align-items: center;display: flex;
  margin: 0 auto;
  margin-bottom: 20px;
}
.titulos_no{
  padding: 20px;
  font-family: Tahoma; 
  /*text-transform: uppercase;*/
  font-weight: 700;
  font-style: normal;
  font-size: 25px;
  color: #444444;
  /*text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);*/
  margin:0 auto;
}

/*TABLA DE ACTUALIZACION DE CLIENTES*/
#tabla_clientesAC{
  margin-top:20px;
  width: 100%;
  background-color: white;
  /*text-align: center;*/
  font-weight: 500 !important;
  color:#032A40;
  border:1px inset #505050;
  font-size: 14px;
  margin: 0 auto;
  margin-bottom: 15px;
}
#tabla_clientesAC th{
  background-color: #505050;
  padding: 7px;
  color: white;
  font-size: 12px;
  text-align: center;
  /*border:1px solid #000;*/
}
#tabla_clientesAC td{
  border:1px inset #505050;
  padding: 7px;
}

/*EXHIBIDORES DEL CLIENTE*/


.vya{
  color:#2089EC;
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}

.malub{
  color:#F5801F;
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}
.invad{
  color:#D7DA2E;
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}
.necer{
  color:#171716;
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}
.deseg{
  color:#E63414;
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}
.retig{
  color:#E63414;
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}
.defaultcolor{
  /*color:;*/
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}
.nuevo{
  color:#0CC370;
  margin-right:3px;
  text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);
}
.seg{
      width: 100%;
      /*background-color: red;*/
      background-color: #2ba6cb;
      text-transform: uppercase;
      padding: 1px;
      margin-bottom: 3px;max-width: 100vw;
    }
    .segdos{
      width: 100%;
      /*background-color: red;*/
      background-color: #F13154;
      text-transform: uppercase;
      padding: 1px;
      margin-bottom: 3px;max-width: 100vw;
    }
    .segtres{
      width: 100%;
      /*background-color: red;*/
      background-color: #0CC370;
      text-transform: uppercase;
      padding: 1px;
      margin-bottom: 3px;max-width: 100vw;
    }
    .segtres .seg_i{
      height: 25px;
      vertical-align:top;
      max-width: 100vw;
      background-color: #0CC370;
      color: #FFF;
      padding: 1px;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }
    .segtres .seg_d{
      width: 100%;
      background-color: #fff;
      color: #746F6F;
      padding: 15px;
      font-weight: bold;
      font-size: 14px;
    }
    .segdos .seg_i{
      height: 25px;
      vertical-align:top;
      max-width: 100vw;
      background-color: #F13154;
      color: #FFF;
      padding: 1px;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }
    .segdos .seg_d{
      width: 100%;
      background-color: #fff;
      color: #746F6F;
      padding: 15px;
      font-weight: bold;
      font-size: 14px;
    }

    .seg .seg_i{
      height: 25px;
      vertical-align:top;
      max-width: 100vw;
      background-color: #2ba6cb;
      color: #FFF;
      padding: 1px;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }
    .seg .seg_d{
      width: 100%;
      background-color: #fff;
      color: #746F6F;
      padding: 15px;
      font-weight: bold;
      font-size: 14px;
    }

    #ModalAbrirExpendiente .modal-dialog {
  max-width: 100%;
/*  margin: 0;
  padding: 0;*/
 /* overflow: hidden;*/
}
#ModalAbrirExpendiente .modal-body{
  /*background-color: silver;*/
  /*background-image: url("../../dependencias/imagenes/papyrus2.png");*/
}
#content-infog{
  /*background-color: yellow;*/
  min-width:  500px;
  height: auto;
}
#content-infof{
  /*background-color: green;*/
  min-width:  500px;
  height: auto;
}
#content-infoe{
  /*background-color: green;*/
  min-width:  500px;
  height: auto;
}
.container-fluid{
  height: auto;
  /*background-color: skyblue;*/
}
.container_dos{
  margin-top: 20px;
}

    #tabla-infop{
      width: 100%;
      border-collapse: separate;
      text-transform: uppercase;
      font-weight: 600;
      text-align: justify;
      margin: 0 auto;
      -webkit-box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
      -moz-box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
      box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
      height: 100%;
    }
    .titulo-foto{
      background-color: #2A2A2A;
      color: white;
      /*padding: 10px;*/
      text-transform: uppercase;
      font-weight: 600;
      height: 44px;
    }
    #tabla_foto{
      width: 100%;
      text-align: center;
      margin: 0 auto;
      border-collapse: separate;
      /*background-color: #FCFFEA;*/
      -webkit-box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
      -moz-box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
      box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
      height: 100%;
      /*height: 500px;*/
    }

    #tabla_foto tr td img{
      max-width: 100%;
      max-height: 100%;
    }

    .titulo-principal{
      background-color: #528BCB;
      color: white;
    }
    .tabla-tituloCol{
      background-color: #DCE6F1;
      color: #000;
      width: 30px;
    }
    #tabla-infop td, #tabla-infop th{
      padding: 10px;
    }
    .tabla-infop{
      background-color: #FAFAFA;
      color: #32383C;
      /*border-top:1px dotted #000;*/
      /*border-bottom:1px dotted #000;*/
    }

.titulo_Exhibidores{
  color: #000;
  font-weight: 600;
}
.sin_exhibidor{
  text-transform: uppercase;
  color: #32383C;
  background-color: #FFFB71;
  font-weight: 600;
  padding: 10px;
  border:1px solid #EBE75B;
  /*width: 100px;*/
}

.titulo-exh{
  background-color: #474745;
  color: white;
  padding: 10px;
  text-transform: uppercase;
  font-weight: 600;
}
#tabla_exhibidor{
  width: 100%;
  text-align: center;
  margin: 0 auto;
  border-collapse: separate;
  background-color: #F9F9F3;
  margin-bottom: 5px;
  -webkit-box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
  -moz-box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
  box-shadow: 1px 3px 7px -2px rgba(0,0,0,0.75);
}

.Observ{
  width: 100px;
  background-color: #FAFAFA;
}
.contenedor_principal{
  /*background-color: red;*/
  height: 960px;
}

/* Tamaño del scroll */
.contenedor_principal::-webkit-scrollbar {
  width: 8px;
}

 /* Estilos barra (thumb) de scroll */
.contenedor_principal::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 4px;
}

.contenedor_principal::-webkit-scrollbar-thumb:active {
  background-color: #999999;
}

.contenedor_principal::-webkit-scrollbar-thumb:hover {
  background: #b3b3b3;
  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.2);
}

 /* Estilos track de scroll */
.contenedor_principal::-webkit-scrollbar-track {
  background: #e1e1e1;
  border-radius: 4px;
}

.contenedor_principal::-webkit-scrollbar-track:hover, 
.contenedor_principal::-webkit-scrollbar-track:active {
  background: #d4d4d4;
}

@media all and (max-width: 499px) {
  #content-infog{
    /*background-color: red;*/
    min-width:  250px;
  }
  #content-infoe{
    /*background-color: green;*/
    min-width:  250px;
  }
  #content-infof{
    /*background-color: green;*/
    min-width:  250px;
  }


}


.switch_estilo{
    position: relative;
    width: 80px;
    height: 40px;
    -webkit-appearance:none;
    -moz-appearance:none;
    outline: none;
    background-color: #DC3545;
    border-radius: 20px;
    box-shadow: inset 0 0 5px rgba(0,0,0,.2);
    transition: .5s;
}

.switch_estilo:checked{
    background-color: #28A745;

}


.switch_estilo:before{
    content: '';
    position: absolute;
    width: 40px;
    height: 40px;
    border-radius: 29px;
    top:0;
    left: 0;
    background-color: #fff;
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0,0,0,.2);
    transition: .5s;

}
.switch_estilo:checked:before{
    left: 40px;
}

.content_titulo{
background: rgba(255,255,255,1);
background: -moz-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(255,255,255,1)), color-stop(47%, rgba(246,246,246,1)), color-stop(100%, rgba(237,237,237,1)));
background: -webkit-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: -o-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: -ms-linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
background: linear-gradient(45deg, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed', GradientType=1 );
  border-right: 5px solid #575353;
  border-left: 5px solid #575353;
  width: 98%;
  /*height: 50px;*/
  text-align: center;
  align-items: center;display: flex;
  margin: 0 auto;
  margin-bottom: 20px;
}
.titulos_no{
  padding: 20px;
  font-family: Tahoma; 
  /*text-transform: uppercase;*/
  font-weight: 700;
  font-style: normal;
  font-size: 25px;
  color: #444444;
  /*text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);*/
  margin:0 auto;
}
/*.swal2-icon.swal2-info {
 border-color: #f27474 !important;

}*/

.swal2-popup.swal2-toast.swal2-show {
border: 2px solid #000 !important;
}

.swal2-modal {

}

/*.swal2-icon.swal2-error {
  border-color: #f27474 !important;
}*/
#content_cliAc{
  margin-top:-47px;
}

.info_clie{
  text-align:center;
  font-weight:500;
  font-size:16px;
  color:#000;
}

.aprob{
  color:#28A745;
  margin-left:5px;
}
.recha{
  color:#DC3545;
  margin-left:5px;
} 
.btn_carpeta span{
 color: #FFD764;
 text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.3);
}

.estilo_alert_nohay{
  text-align:center;
  border-top:2px dashed #242424;
  border-bottom:2px dashed #242424;
  border-right:0px;
  border-left:0px;
  color:#242424;
  border-radius:0px !important;
}

.span_descargar{
  font-weight:600;
  font-style:italic;
}

.titulo_page {
  /*background-color: #B4E1F6;*/
  /*text-shadow: 3px 3px 3px #929292;*/
  text-align: center;
  margin-top: 30px;
  color: #2d535d;
  vertical-align: middle;
  text-transform: uppercase;
  font-weight: 700;
  /* font-size:29px; */
}

  .page-TablaClteCensados{
    background-color: #4FABC3;
    font-weight: bold;
    color: #fff;
    padding: 10px;
    font-size: 17px;
    
  }
  
  .page-numbers{
    font-size: 18px;
  }

.tablabitacora{
  margin:0 auto;
}

  .page-aprobadoac{
    background-color: #4FABC3;
    font-weight: bold;
    color: #fff;
    padding: 10px;
    font-size: 17px;
    
  }

/* .page-numbers {
  background-color: #4fabc3;
  font-weight: bold;
  color: #fff;
  padding: 7px;
  font-size: 10px;
} */

.div_comentario{
  margin-top:10px;
  background-color:#FFFB71;
  color:#2F2E0F;
  /* height:77px; */
  /* overflow: auto; */
  border-radius:7px;
  font-size:17px;
  font-weight:600;padding: 10px;
  border:1px solid #DAD64C;
}

input.chequegrande {
  transform : scale(3.5);

}

.tb_dovisita td {
  border:1px solid #DEE2E6 !important;
}
</style>

  <!--000000---DIV CARGANDO---000000-->
  <div id="content-carga" style="display:none;" class="carga-class">
    <div class="d-flex justify-content-center">
      <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
        <span class="sr-only">Cargando...</span>
       </div>
    </div>
  </div>

<!-- <div class="row justify-content-md identify-content" style="display: none;">
  <div style="width: 100%" id="contenedor-formulario"> -->
    <div style="width:100%;margin-top:80px;position: static;" id="mjs_result"></div>
    <div id="mjs_extra"></div>
    <?php
      $datta = $this->session->flashdata('datta');
      if($datta){
    ?>
    <div style="width:100%;margin-top:2px;">
      <div class="alert alert-<?php echo $datta['cla'];?>" role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
        <strong><h3><?php echo $datta['ttmjs'];?></h3></strong>
        <?php echo $datta['info'];?>
      </div>
    </div>
    <?php
      }else{}
      $atributos = array(
        'class' => 'form-estilo',
        'id' =>'form-reporte',
        'enctype' => 'multipart/form-data',
        'method' => 'POST'
      );
      echo form_open(base_url('generar-plantilla/plantillaok'), $atributos);
    ?>
<!--     <center>
      <button type="button" class="btn btn-success" id="btn-configuracion" style="display: none;"> 
        <span class="fa fa-bars"></span> Configuraci&oacute;n
      </button>
    </center> -->
<!--     <center>
      <button type="button" id="sidebarCollapse" class="btn btn-configu" style="display:none;background-color: #126D89;color: #fff;">
        <i class="fas fa-align-left"></i>
        <span>Men&uacute;</span>
      </button>
    </center> -->


    <div class="col-md-6 offset-md-3" id="content-configuracion" style="display:none;margin-top: 7px;">
      <div class="card card-body alabama">
        <h3 class="card-header" style="text-align:center;background-color:#2D535D;color:#fff;">Configuraci&oacute;n de Reportes</h3><br>
        <label><h6><span class="">Fecha Inicio:</span></h6></label>
        <div class="form-group">
          <div class="input-group date" id="datepicker" data-target-input="nearest">
            <input type="text" id="datepickervalue" name="datepickervalue" class="form-control datetimepicker-input" data-target="#datepicker" style="border:1px solid #9DD0D4;" />
            <div class="input-group-append" data-target="#datepicker" data-toggle="datetimepicker" style="border:1px solid #9DD0D4;">
              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
          </div>
        </div>
        <label><h6><span class="">Fecha Final:</span></h6></label>
        <div class="form-group">
          <div class="input-group date" id="datepickerdos" data-target-input="nearest">
            <input type="text" id="datepickerdosvalue" name="datepickerdosvalue" class="form-control datetimepicker-input" data-target="#datepickerdos" style="border:1px solid #9DD0D4;"/>
            <div class="input-group-append" data-target="#datepickerdos" data-toggle="datetimepicker" style="border:1px solid #9DD0D4;">
              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
          </div>
        </div>
        <div id="list-distribuidora-report" style="text-align: center;">
         <div>
            <div class="spinner-border text-info" id="spinner-load" style="width: 3rem; height: 3rem;"  role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
          <span style="font-weight: 700;">Cargando Distribuidoras...</span>
        </div>
<!--         <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="chechpruebas" style="height: 100px;">
          <label class="custom-control-label" for="chechpruebas">Incluir clientes de pruebas</label>
        </div> -->
        <div class="final" style="margin: 0 auto;margin-top: 7px;">
          <table style="width: 100%;">
            <tr>
              <td>
                <div class="form-group">
                  <center>
                    <button type="button" style="width: 100%;" class="btn btn-info" onclick="resultacantidadplantillaco()"> <span class="fa fa-file-excel"></span> Reporte Completo
                    </button>
                  </center>
                </div>
              </td>
<!--               <td>
                <div class="form-group">
                  <button type="button" style="width: 100%;" class="btn btn-success" onclick="resultacantidadplantilla()"> <span class="fa fa-file-excel"></span> Descargar Plantilla
                  </button>
                </div>
              </td> -->
<!--               <td>
                <div class="form-group">
                  <button type="button" style="width: 100%;" class="btn btn-dark" onclick="resultacantidadplantillaco()">  <span class="fa fa-file-excel"></span> Reporte Completo 
                  </button>
                </div>
              </td> -->
            </tr>
          </table>
        </div>
      </div> 
    </div>
  <!-- </div> -->




<!--DESCARGA DE ACTUALIZACION DE CLIENTES-->

    <div class="col-md-6 offset-md-3" id="content-configuracion-actu" style="display:none;margin-top: 7px;">
      <div class="card card-body alabama">
        <h3 class="card-header" style="text-align:center;background-color:#2D535D;color:#fff;">Descargar Actualizaciones de Clientes</h3><br>
        <label><h6><span class="">Fecha Inicio:</span></h6></label>
        <div class="form-group">
          <div class="input-group date" id="datepickeractu" data-target-input="nearest">
            <input type="text" id="datepickervalueactu" name="datepickervalueactu" class="form-control datetimepicker-input" data-target="#datepickeractu" style="border:1px solid #9DD0D4;" />
            <div class="input-group-append" data-target="#datepickeractu" data-toggle="datetimepicker" style="border:1px solid #9DD0D4;">
              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
          </div>
        </div>
        <label><h6><span class="">Fecha Final:</span></h6></label>
        <div class="form-group">
          <div class="input-group date" id="datepickerdosactu" data-target-input="nearest">
            <input type="text" id="datepickerdosvalueactu" name="datepickerdosvalueactu" class="form-control datetimepicker-input" data-target="#datepickerdosactu" style="border:1px solid #9DD0D4;"/>
            <div class="input-group-append" data-target="#datepickerdosactu" data-toggle="datetimepicker" style="border:1px solid #9DD0D4;">
              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
          </div>
        </div>
        <div id="list-distribuidora-report-actu" style="text-align: center;">
         <div>
            <div class="spinner-border text-info" id="spinner-load" style="width: 3rem; height: 3rem;"  role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
          <span style="font-weight: 700;">Cargando Distribuidoras...</span>
        </div>
<!--         <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="chechpruebas" style="height: 100px;">
          <label class="custom-control-label" for="chechpruebas">Incluir clientes de pruebas</label>
        </div> -->
        <div class="final" style="margin: 0 auto;margin-top: 7px;">
          <table style="width: 100%;">
            <tr>
              <td>
                <div class="form-group">
                  <center>
                    <button type="button" style="width: 100%;" class="btn btn-info" onclick="resultacantidadplantillaco_ACTU()"> <span class="fa fa-file-excel"></span> Consultar
                    </button>
                  </center>
                </div>
              </td>
<!--               <td>
                <div class="form-group">
                  <button type="button" style="width: 100%;" class="btn btn-success" onclick="resultacantidadplantilla()"> <span class="fa fa-file-excel"></span> Descargar Plantilla
                  </button>
                </div>
              </td> -->
<!--               <td>
                <div class="form-group">
                  <button type="button" style="width: 100%;" class="btn btn-dark" onclick="resultacantidadplantillaco()">  <span class="fa fa-file-excel"></span> Reporte Completo 
                  </button>
                </div>
              </td> -->
            </tr>
          </table>
        </div>
      </div> 
    </div>



  <!--FORMULARIO PARA CAMBIO DE CONTRASEÑA-->
    <div class="col-md-5" id="content-pass" style="display:none;margin-top: 7px;margin: 0 auto;">
      <div class="card card-body alabama">
        <h3 class="card-header" style="text-align:center;background-color:#2D535D;color:#fff;">Cambio de Contrase&ntilde;a</h3><br>
        <label><h6><span class="">Contrase&ntilde;a Anterior:</span></h6></label>
        <div class="form-group">
            <input type="text" id="txtpassantes" name="txtpassantes" class="form-control" style="-webkit-text-security: square;border:1px solid #9DD0D4;"/>
        </div>
        <label><h6><span class="">Contrase&ntilde;a Nueva:</span></h6></label>
        <div class="form-group">
            <input type="text" id="txtpassnueva" name="txtpassnueva" class="form-control" style="-webkit-text-security: square;border:1px solid #9DD0D4;"/>
        </div>
        <label><h6><span class="">Confirmar Contrase&ntilde;a Nueva:</span></h6></label>
        <div class="form-group">
            <input type="text" id="txtpassnuevarepe" name="txtpassnuevarepe" class="form-control" style="-webkit-text-security: square;border:1px solid #9DD0D4;"/>
        </div>
        <div class="final" style="margin: 0 auto;margin-top: 7px;">
          <table style="width: 100%;">
            <tr>
              <td>
                <div class="form-group">
                  <center>
                    <button type="button" style="width: 100%;" class="btn btn-info" onclick="confirmar_cambio_pass()"> <span class="fa fa-check-circle"></span> Aceptar
                    </button>
                  </center>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div> 
    </div>


    <div class="col-md-5" id="content_importacodigos" style="display:none;margin-top: 7px;margin: 0 auto;">
      <div class="card card-body alabama">
        <h3 class="card-header" style="text-align:center;background-color:#2D535D;color:#fff;">Actualización de Clientes</h3><br>
        <label><h6><span class="">Adjuntar Plantilla:</span></h6></label>
        <div class="custom-file mb-3" style="text-align: left;">
          <!--application/vnd.openxmlformats-officedocument.spreadsheetml.sheet-->
          <input type="file" class="custom-file-input" id="fileimportcodes" name="fileimportcodes" lang="es" accept="xls,.xlsx">
          <label class="custom-file-label" for="filefotodos" data-browse="Adjuntar">Plantilla Descargada Con Codigo</label>
        </div>
        <div class="final" style="margin: 0 auto;margin-top: 7px;">
          <table style="width: 100%;">
            <tr>
              <td>
                <div class="form-group">
                  <center>
                    <button type="button" style="width: 100%;" class="btn btn-info" onclick="enviar_plantila_CodNew()"> <span class="fa fa-check-circle"></span> Aceptar
                    </button>
                  </center>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div> 
    </div>





<!---AQUI FORMULARIO DE EDICION DE CLIENTES ACTUALIZADOS-->





    <div class="row" id="content_cliAc" style="display: none;margin-top:20px;">
    <!-- <div> -->
        <div class="col-md-8 offset-md-2">
            <div class="card card-body">
                <h3 class="card-header" style="text-align:center;background-color:#2D535D;color:#fff;">Editar cliente</h3><br>
                        <!--******************************************************************************-->
                        <!--*************************INFORMACION DEL CLIENTE******************************-->
                        <!--******************************************************************************-->
                            <div class="row">
                                <div class="col-md-12 divrow" style="text-align: center;">
                                    <strong>CODIGO: <span class="badge badge-dark" id="lblcodcli">0000000</span></strong>
                                </div>
                            </div>

                            <br>

                            <div class="row_especial">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-user fa-lg"></span> Estado del cliente:</div>
                                    <input class="switch_estilo" id="switch_estado" type="checkbox">
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-signature fa-lg"></span> Nombre:</div>
                                    <input type="text" class="form-control outlinenone" name="txtnombre" id="txtnombre" value="">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjsd-0"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-directions fa-lg"></span> Direcci&oacute;n:</div>
                                    <textarea class="form-control outlinenone" name="txtdireccion" id="txtdireccion" style="width: 100%;height: 90px;"></textarea>
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjsd-1"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Departamento:</div>
                                    <div id="c-departamento">
                                        <select name="cbdepartamentod" id="cbdepartamentod">
                                            <option value="" selected>0</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="if-departamento">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Municipio:</div>
                                    <div id="c-municipio" class="especial-info">
                                      <select name="cbmunicipiod" id="cbmunicipiod">
                                        <option value="" selected>0</option>
                                      </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-id-badge fa-lg"></span> Contacto:</div>
                                    <input type="text" class="form-control outlinenone" name="txtcontacto" id="txtcontacto" value="">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjsd-4"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-phone-square-alt fa-lg"></span> Tel&eacute;fono:</div>
                                    <input type="text" class="form-control outlinenone" name="txttelefono" id="txttelefono" value="">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjsd-5"> 
                                    </div>
                                </div>
                            </div>

                            <div class="titulo"><span class="fa fa-calendar-day fa-lg"></span> D&iacute;a De Visita:</div>
                            <div class="form-group" id="div_diasVisita">
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Tipo de facturaci&oacute;n:</div>
                                    <div id="c-tfacturaciond">
                                        <select name="cbtfacturaciond" id="cbtfacturaciond">
                                            <option value="" selected>0</option>
                                        </select>
                                    </div>
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjsd-14"> 
                                    </div>
                                </div>
                            </div>
                            <div id="if-tfacturad" style="display: none;">
                                <div class="row" style="display: none;" id="div_dui">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo" id="docidentidadd"></div>
                                        <input type="tel" id="txtduid" maxlength="15" name="txtduid" class="form-control outlinenone">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjsd-7"> 
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="display: none;" id="div_numregistro">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo">N&uacute;mero de registro de contribuyente:</div>
                                        <input type="text" class="form-control outlinenone" name="txtnumcontribuyented" id="txtnumcontribuyented" value="0">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjsd-8"> 
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="display: none;" id="div_nit">
                                    <div class="col-md-12 divrow">
                                        <div class="titulo" id="idtributariad"></div>
                                        <input type="tel" id="txtnitd" name="txtnitd" maxlength="17" class="form-control outlinenone">
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback" id="error-mjsd-9"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--FINAL DE TIPO DE FACTURACION DEL CLEINTE-->
                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-question fa-lg"></span> Frecuencia de visita:</div>
                                        <select class="form-control custom-select outlinenone" id="cbfrecuenciavisitad" name="cbfrecuenciavisitad">
                                            <option value="">SELECCIONE UNA OPCION</option>
                                            <option value="1,2,3,4,5">SEMANAL</option>
                                            <option value="1,3,5">QUINCENAL 1,3,5</option>
                                            <option value="2,4">QUINCENAL 2,4</option>
                                            <option value="1">MENSUAL S1</option>
                                            <option value="2">MENSUAL S2</option>
                                            <option value="3">MENSUAL S3</option>
                                            <option value="4">MENSUAL S4</option>
                                        </select>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjsd-10">
                                            <strong>Por favor selecciona una opción de la lista!</strong>
                                        </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="titulo"><span class="fa fa-sort-numeric-up fa-lg"></span> Orden de visita</div>
                                    <input type="number" min="1" max="5" class="form-control outlinenone" name="txtordevisita" id="txtordevisita" value="0">
                                    <div class="valid-feedback">
                                    </div>
                                    <div class="invalid-feedback" id="error-mjsd-11"> 
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 divrow">
                                    <div class="textoSeleccion"><br>
                                        <center>
                                            <button type="button" id="btn-enviar" class="btn btn-primary carga-esconder" onclick="ModificarClienteAC()"><span class="fas fa-paper-plane fa-lg" ></span> Aceptar
                                            </button>
                                            <button type="button" class="btn btn-primary carga-class" style="display: none;"><span class="fas fa-paper-plane fa-spin fa-lg"></span> Aceptar
                                            </button>
                                            <button type="button" name="cancelar-agregar" class="btn btn-danger" style="font-size:16px;font-weight: bold;" onclick="cancelar_actividad()"><span class="fa fa-times-circle" style="font-size: 16px;"></span> Cancelar</button>
                                        </center>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

        <!-- </div> -->
    </div>






  <?php
    echo form_close();
  ?>
<!-- </div> -->
<!-- </div> -->
      <div id="map" style="width: 100%;background-color: red; height: 400px;display:none;margin-left:auto;margin-right:auto;left:0;right:0;margin-top:100px;"></div>
      <div id="content-tabla" style="width: 100%;margin: 0 auto;margin-top: -20px;"></div>
      <form id="form-validacion">
        <div id="formularios" style="display: none;margin-top: 40px;"></div>

        
        <div id="content_actualizados" style=""></div>

      </form>
      <!-- <div style="background-color: red;width: 100%; height: 400px;">s</div> -->
      <br><br>

    </div>
    <!--FIN DEL CONTENIDO-->
  </div>

<div class="modal fade" id="Modaldetalles" tabindex="-1" role="dialog" aria-labelledby="eliminar" aria-hidden="true">
  <div class="modal-dialog modal-full" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminar">Seleccione exhibidor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contemodelformdetalle" style="background-color: #fff;">


<div class="CTable_Gr">

  <div id="X" class="CerrarGr" style="display: none;">X</div>
  <div class="table-responsive">
    <!-- <div class="textS">Seleccione exhibidor a continuaci&oacute;n:</div> -->
     B&uacute;scar: <input id="txtBusqueda" type="text" onkeyup="grBusqueda()" />
    <table id="DgrTable" class="table table-borderless table-sm GrTable">
      <thead>
        <tr>
          <th scope="col">SKU</th>
          <th scope="col">Exhibidor</th>
          <!-- <th scope="col">Imagen</th> -->
        </tr>
      </thead>
      <tbody id="showData">
      
      </tbody>
    </table>
  </div>
</div>




      </div>
      <div class="modal-footer" id="pieModalformdetalle">
      </div>
    </div>
  </div>
</div>

    <!-- MODAL VER ESTATUS DE ENCUESTA-->
    <div class="modal fullscreen-modal" id="ModalAbrirExpendiente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document" >
        <div class="modal-content">
          <div class="modal-header d_arriba">
             <span class="modal-title" style="margin-top:-14px;"><span class="vya fas fa-folder-open fa-2x"></span> Información de encuesta</span>
            <span id="X" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col col-md" id="content-infog"></div>
                <div class="col col-md" id="content-infof"></div>
              </div>
            </div>
            <div class="container-fluid overflow-auto container_dos">
              <div class="row">
                  <div class="col col-md" id="content-infoe"></div>
              </div>
            </div>
<!--             <h6 style="height: 44px;line-height: 40px;border: 1px solid #fff;color:#fff;margin:0 auto;text-align: center;margin-top: 7px;background-color: #474745;width: 98%;">UBICACIÓN DEL CLIENTE</h6>
            <div class="container-fluid">
              <div class="row" id="content-mapa">
              </div>
            </div> -->
          </div>
          <div class="modal-footer d_abajo">
          </div>
        </div>
      </div>
    </div>
<script type="text/javascript">
  
  $(function () {
    $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, { icons: {time: 'fas fa-clock', date: 'fas fa-calendar', up: 'fas fa-arrow-up', down: 'fas fa-arrow-down', previous: 'fas fa-chevron-left', next: 'fas fa-chevron-right', today: 'fas fa-calendar-check-o', clear: 'fas fa-trash', close: 'fas fa-times' } });
    
    $('#datepicker').datetimepicker({
      format: 'YYYY-MM-DD HH:mm:ssa'
    });
    $('#datepickerdos').datetimepicker({
      format: 'YYYY-MM-DD HH:mm:ssa',
      useCurrent: false
    });
    $("#datepicker").on("change.datetimepicker", function (e) {
      $('#datepickerdos').datetimepicker('minDate', e.date);
    });
    $("#datepickerdos").on("change.datetimepicker", function (e) {
      $('#datepicker').datetimepicker('maxDate', e.date);
    });

    $('#datepickeractu').datetimepicker({
      format: 'YYYY-MM-DD HH:mm:ssa'
    });
    $('#datepickerdosactu').datetimepicker({
      format: 'YYYY-MM-DD HH:mm:ssa',
      useCurrent: false
    });
    $("#datepickeractu").on("change.datetimepicker", function (e) {
      $('#datepickerdos').datetimepicker('minDate', e.date);
    });
    $("#datepickerdosactu").on("change.datetimepicker", function (e) {
      $('#datepicker').datetimepicker('maxDate', e.date);
    });



  });
  
  $(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
      theme: "minimal"
    });
    // $("#responsivo-table").mCustomScrollbar({
    //   theme: "minimal"
    // });
    // $("#content").mCustomScrollbar({
    //   theme: "dark-3"
    // });
    $(document).on("click", "#dismiss, .overlay", function() {
    // $('#dismiss, .overlay').on('click', function () {
      $('#sidebar').removeClass('active');
      $('.overlay').removeClass('active');
    });
    $(document).on("click", "#sidebarCollapse", function() {
    // $('#content #sidebarCollapse').on('click', function () {
      $('#sidebar').addClass('active');
      $('.overlay').addClass('active');
      $('.collapse.in').toggleClass('in');
      $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

  });
</script>