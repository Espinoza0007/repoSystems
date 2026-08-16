<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.fullscreen-modal .modal-dialog {
  margin: 0;
  margin-right: auto;
  margin-left: auto;
  width: 100%;
  /*background-color: red;*/
}

  @media (min-width: 768px) {
  
  .fullscreen-modal .modal-dialog {
    width: 750px;
  }



  }
  @media (min-width: 992px) {
    .fullscreen-modal .modal-dialog {
    width: 970px;
  }

  }
  @media (min-width: 1200px) {
        #principal{
      width: 70%;
    }
     .fullscreen-modal .modal-dialog {
     width: 1170px;
    } 
  }


.modal-full .modal-content {
    min-height: 100vh;
}
    .modal-header,.modal-footer{
        background-color: #4D8D86;
        color: #fff;
        border-radius: 1px;
    }
	#contenedor-formulario{
		margin-top: 1445px;
		margin: 0 auto;
	}
.paginacion{
font-size:15pt;
}
.page-numbers{
   background-color: #4FABC3;
   font-weight: bold;
   color: #fff;
   padding: 7px;
   font-size: 10px;

}

.page-numbersdo{
   background-color: #4FABC3;
   font-weight: bold;
   color: #fff;
   padding: 7px;
   font-size: 10px;

}
    .current{
        font-size:20px;
        /*border:1px solid dimgray;*/
        border-radius:3px;
        padding: 5px;
        background-color: #fff;
        font-weight: bold;
    }

    #contenedor-formulario{
    margin-top: 1445px;
    margin: 0 auto;
  }
    

  .page-numbers{
    font-size: 18px;
  }

  .sombra{
    box-shadow: 0 0 10px #229FF5;
    border: 1px solid #229FF5;
    font-weight: bold;
    color: #000;
  }

#fachadaimg{
  width:500px;
  height:500px;
}
.btn-circle.btn-lg {
  width: 50px;
  height: 50px;
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.33;
  border-radius: 25px;
}
#btn-salir-mapa{
  background-color: #000;
  font-weight: bold;
  color: #fff;
  width: 75;
  padding: 5px;
  border:0px;
}

.activo{
  color: #2B4581;
  font-weight: bold;
  /*background-color: #B7CCFE;*/
  border: 1px solid #2B4581;
}

.inactivo{
  /*background-color: #FEB7CE;*/
  color: #FC4580;
  font-weight: bold;
  border: 1px solid #FC4580;
}

.nuevo_duplicado{
  /*background-color: #B7FEC6;*/
  color: #2C8A40;
  font-weight: bold;
  border: 1px solid #2C8A40;
}
.page-numbersdo{
  font-size: 18px;
}

.clickable{
  cursor: pointer;
}
.badgeaco{
  font-size: 15px;
}

.head-acordeon{
/*background: rgba(183,222,237,1);
background: -moz-linear-gradient(45deg, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 99%, rgba(183,222,237,1) 100%);
background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(183,222,237,1)), color-stop(99%, rgba(113,206,239,1)), color-stop(100%, rgba(183,222,237,1)));
background: -webkit-linear-gradient(45deg, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 99%, rgba(183,222,237,1) 100%);
background: -o-linear-gradient(45deg, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 99%, rgba(183,222,237,1) 100%);
background: -ms-linear-gradient(45deg, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 99%, rgba(183,222,237,1) 100%);
background: linear-gradient(45deg, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 99%, rgba(183,222,237,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b7deed', endColorstr='#b7deed', GradientType=1 );*/

background: rgba(242,246,248,1);
background: -moz-linear-gradient(45deg, rgba(242,246,248,1) 0%, rgba(216,225,231,1) 0%, rgba(224,239,249,1) 100%);
background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(242,246,248,1)), color-stop(0%, rgba(216,225,231,1)), color-stop(100%, rgba(224,239,249,1)));
background: -webkit-linear-gradient(45deg, rgba(242,246,248,1) 0%, rgba(216,225,231,1) 0%, rgba(224,239,249,1) 100%);
background: -o-linear-gradient(45deg, rgba(242,246,248,1) 0%, rgba(216,225,231,1) 0%, rgba(224,239,249,1) 100%);
background: -ms-linear-gradient(45deg, rgba(242,246,248,1) 0%, rgba(216,225,231,1) 0%, rgba(224,239,249,1) 100%);
background: linear-gradient(45deg, rgba(242,246,248,1) 0%, rgba(216,225,231,1) 0%, rgba(224,239,249,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f6f8', endColorstr='#e0eff9', GradientType=1 );
  border:1px solid #485F7E;
}



.btn_carpeta span{
 color: #FFD764;
 text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.3);
}


.page-TablaClteCensados{
  background-color: #4FABC3;
  font-weight: bold;
  color: #fff;
  padding: 10px;
  font-size: 15px;  
}


/*TABLA DE CLIENTES NUEVOS*/

#tabla-clientes{
  margin-top:20px;
  width: 98%;
  background-color: white;
  /*text-align: center;*/
  font-weight: 500 !important;
  color:#032A40;
  border:1px inset #505050;
  font-size: 14px;
  margin: 0 auto;
}
#tabla-clientes th{
  background-color: #505050;
  padding: 7px;
  color: white;
  font-size: 12px;
  text-align: center;
  /*border:1px solid #000;*/
}
#tabla-clientes td{
  border:1px inset #505050;
  padding: 7px;
}

/*TABLA DE ACTUALIZACION DE CLIENTES*/
#tabla_clientesAC{
  margin-top:20px;
  width: 98%;
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

.titulos{
  /*padding: 20px;*/
  font-family: Tahoma; 
  /*text-transform: uppercase;*/
  font-weight: 700;
  font-style: normal;
  font-size: 25px;
  color: #444444;
  width: 100%;
  /*padding: 0px 0px 0px 20px;*/
  margin-left: 14px;
  /*background-color: red;*/
  /*text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);*/

}
.titulos_sub{
  padding: 20px;
  font-family: Tahoma; 
  /*text-transform: uppercase;*/
  font-weight: 700;
  font-style: normal;
  font-size: 18px;
  color: #444444;
  /*text-shadow: -1px -1px 1px rgba(255,255,255,.1), 1px 1px 1px rgba(0,0,0,.6);*/

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
}

.aprob{
  color:#28A745;
  margin-left:5px;
}
.recha{
  color:#DC3545;
  margin-left:5px;
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
.estilo_alert_nohay{
  text-align:center;
  border-top:2px dashed #242424;
  border-bottom:2px dashed #242424;
  border-right:0px;
  border-left:0px;
  color:#242424;
  border-radius:0px !important;
}

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

.tb_dovisita td {
  border:1px solid #DEE2E6 !important;
}

</style>


<br><br><br>
<script type="text/javascript" src="<?php echo base_url('dependencias/js/js_supervisores.js') ?>"></script>

  <div id="content-carga" style="display:none;" class="carga-class">
    <div class="d-flex justify-content-center">
      <div class="spinner-border" role="status" style="width: 6rem; height: 6rem;color: #239BEA;font-size: 40px;">
        <span class="sr-only">Cargando...</span>
       </div>
    </div>
  </div>


  <div style="width: 99%" id="contenedor-formulario">
	    <!-- <h4>Filtros de busqueda</h4><br> -->
	</div>
  <form id="form-validacion">
    <div class="container-fluid" id="FiltrosBusqueda" style="width: 280px;margin-bottom: 15px;">
      <div class="row">
          <div class="col-md" id="div_filtrorutas">
            <label class="titulo_busqueda">RUTAS:</label>
            <div id="S_filtroRuta">
              <select class="form-control" id="filtrorutas" name="filtrorutas">
                  <option value="">TODAS LAS RUTAS</option>
                  <option value="">.....</option>
                  <option value="">.....</option>
              </select>
            </div>
          </div>
      </div>
    </div>
    <!-- <div id="filtros-tabla"></div> -->
    
    
    <div id="content-tabla"></div>
    <div id="content-tabla-zoom"></div>

    <br>
    
    <div id="content_actualizados" style="margin-top:4px;"></div>


    <input type="hidden" id="imagecli" name="imagecli" value="">
    <input type="hidden" id="imagendos" name="imagendos" value="">
    <input type="hidden" id="img-cliente" name="cliente" value="">
    <input type="hidden" id="img_exhibid" name="img_exhibid" value="">
    <input type="hidden" id="cbcantidadex" name="cbcantidadex" value="">
    <div id="formularios" style="display: none;"></div>



<!---AQUI FORMULARIO DE EDICION DE CLIENTES ACTUALIZADOS-->



    <div class="container" id="content_cliAc" style="display: none;">
        <div>
                <div class="row contenedor">
                    <div class="card card-body">
                        <h5 class="card-header card-header-form">
                            Modificar cliente
                        </h5>
                        <br>
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

        </div>
    </div>





  </form>






<center>
  <div id="map" style="height: 88%;width: 90%;position: absolute;display:none;margin-left:auto;margin-right:auto;left:0;right:0;"></div>
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






<div class="modal fade" id="Modalmapa" tabindex="-1" role="dialog" aria-labelledby="mapa" aria-hidden="true">
  <div class="modal-dialog modal-full" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mapa">UBICACION DEL CLIENTE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contentmapa" style="background-color: #fff;">

        

      </div>
      <div class="modal-footer" id="piemodalmapa">
      </div>
    </div>
  </div>
</div>


</center>

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
