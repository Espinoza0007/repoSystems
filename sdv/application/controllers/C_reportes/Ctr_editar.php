<?php
ini_set('memory_limit', '-1');
set_time_limit(999);
date_default_timezone_set('America/El_Salvador');
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
require 'phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Ctr_editar extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->model('M_usuarios/Mdl_login','lg');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }

	function index(){
        // $data['lista_ocupacion'] = $this->us->ocupaciones();
        // $data['lista_privilegio'] = $this->us->privilegios();
        $this->global['pageTitle'] = 'Generar Plantilla';
        $this->loadViews_nada('Reportes/V_actualizar',$this->global);
  	}

    function actualizaplantilla(){

        echo "<h1>ACTUALIZACION DE CLIENTES POR PLANTILLA</h1>";
        echo "<br><br>";
    }


    function totalcli(){

        $arrg_data_update = array();
        $rutaArchivo = 'INFO_REAL/COMPRAS/SS_COMPRAS_RECUPERAR_PARTE_II.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        $totalDeHojas = $documento->getSheetCount();
        $highestRow = $documento->getActiveSheet()->getHighestRow();
        // echo "CANTIDAD DE REGISTROS => ".$highestRow."<br>";
        $createuncodigounico = '';
        $hojaActual = $documento->getSheet(0);
        $coonta_update = 0;
        $totalcorrecto = 0;
        $totalcorrecto =  $highestRow - 1;

        for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {

            // echo $hojaActual->getCell("A".$indiceHoja)->getCalculatedValue();
            // echo "<br>";
            $arrg_data_update[$coonta_update]['Codigo'] = $hojaActual->getCell("A".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["Municipio"] = $hojaActual->getCell("B".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["CantidadExhibidor"] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["ExhibidorUno"] = $hojaActual->getCell("D".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["ExhibidorDos"] = $hojaActual->getCell("E".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["ExhibidorTres"] = $hojaActual->getCell("F".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["GiroNegocio"] = $hojaActual->getCell("G".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["FotoNegocio"] = $hojaActual->getCell("H".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["FotoExhibidor"] = $hojaActual->getCell("I".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["CompraB"] = $hojaActual->getCell("J".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["CompraD"] = $hojaActual->getCell("K".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["CompraY"] = $hojaActual->getCell("L".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["CompraF"] = $hojaActual->getCell("M".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["FechaIngreso"] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["DUI"] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["NIT"] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["NUMERO_REGISTRO"] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["CONDICION_CLIENTE"] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["TIPO_FACTURACION"] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["DIA_COBRO"] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["MONTO_CREDITO"] = $hojaActual->getCell("U".$indiceHoja)->getCalculatedValue();
            $arrg_data_update[$coonta_update]["FECHA_RESOLUCION"] = $hojaActual->getCell("V".$indiceHoja)->getCalculatedValue();
            $coonta_update++;
        }
        echo json_encode(array(
            'rs' => TRUE,
            'info' => 'Exito',
            'cla' => 'success grSuccess',
            'total'=> $totalcorrecto,
            'insertar' => $arrg_data_update
            )
        );
    }


    function cargarregistro(){
	    $cantidadexh = 0;
        $cantidadexh = $this->input->post("arrgdata")['CantidadExhibidor'];
        $tipofacturacion = $this->input->post("arrgdata")['TIPO_FACTURACION'];
        $insertar = array(
            // 'Codigo' => $this->input->post("arrgdata")['Codigo'],
            // 'Id_Municipio' => $this->input->post("arrgdata")['Municipio'],
            // if( $cantidadexh > 0 ){
                // 'Cantidad_Exhibidor' => $this->input->post("arrgdata")['CantidadExhibidor'],
                // 'Exhibiror_Uno' => $this->input->post("arrgdata")['ExhibidorUno'],
                // 'Exhibiror_Dos' => $this->input->post("arrgdata")['ExhibidorDos'],
                // 'Exhibiror_Tres' =>$this->input->post("arrgdata")['ExhibidorTres'],
                // 'Foto_Exhibidor' => $this->input->post("arrgdata")['FotoExhibidor'],
            // }
            // 'Id_Gironegocio' => $this->input->post("arrgdata")['GiroNegocio'],
            // 'Foto_Negocio' => $this->input->post("arrgdata")['FotoNegocio'],
            'CompraS_B' => $this->input->post("arrgdata")['CompraB'],
            'CompraS_D' => $this->input->post("arrgdata")['CompraD'],
            'CompraS_Y' => $this->input->post("arrgdata")['CompraY'],
            'CompraS_F' => $this->input->post("arrgdata")['CompraF'],
            // 'Fecha_Ingreso' => $this->input->post("arrgdata")['FechaIngreso'],
            // if( $tipofacturacion == 2 ){
                // 'Dui' => $this->input->post("arrgdata")['DUI'],
                // 'Nit' => $this->input->post("arrgdata")['NIT'],
                // 'Numero_Registro' => $this->input->post("arrgdata")['NUMERO_REGISTRO'],
            // }
            // 'Id_Condicionc' => $this->input->post("arrgdata")['CONDICION_CLIENTE'],
            // 'Id_Tfacturacion' => $tipofacturacion,
            // 'Dia_Cobro' => $this->input->post("arrgdata")['DIA_COBRO'],
            // 'Monto_Credito' => $this->input->post("arrgdata")['MONTO_CREDITO'],
            // 'Fecha_Resolucion' => $this->input->post("arrgdata")['FECHA_RESOLUCION'],
        );
        $inserdata = $this->cl->modificar_clientes_x_cod($insertar,$this->input->post("arrgdata")['Codigo']);
        if($inserdata){
        	$totalahora = $this->input->post("totalahora");
        	$totalimport = $this->input->post("totalimport");
        	$percentage = round(($totalahora * 100) / $totalimport, 2);
            // var "El registro se realiz&oacute; correctamente. [ " .$hojaActual->getCell("B".$indiceHoja)." ]";
            // $SUCCESS_OK++;
	        echo json_encode(array(
	            'rs' => TRUE,
	            'info' => 'Exito',
	            'cla' => 'success grSuccess',
	            'percentage' => round($percentage, 0)
	            )
	        );
        }else{
            // $ERRORES++;
	        echo json_encode(array(
	            'rs' => TRUE,
	            'info' => 'Exito',
	            'cla' => 'success grSuccess',
	            'erromjs' => "Ocurrio un error en el proceso."
	            )
	        );
        }
    }


}

?>