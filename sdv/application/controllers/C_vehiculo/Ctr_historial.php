<?php
header("Access-Control-Allow-Origin: *");
if(!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/El_Salvador');
ini_set ('gd.jpeg_ignore_warning', 1);
require APPPATH . '/libraries/ControladorBase.php';
require 'phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
require APPPATH . '/libraries/dompdf/autoload.inc.php';
class Ctr_historial extends ControladorBase
{
    function __construct(){
        parent::__construct();
        $this->load->model('M_vehiculo/Mdl_historial','his');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }
    function index(){
        $this->global['pageTitle'] = 'Historial vehiculo';
        $this->loadViews('Vehiculo/V_historial',$this->global);
    }
    
    public function consulta()
    {
        $query = $this->his->obtenerDatosTabla();
        // Añade la propiedad 'reporte_url' a cada fila de datos
        foreach ($query as $index => $row) {
            $query[$index]['reporte_url'] = site_url('reporte');
        }
        echo json_encode($query);
    }
    public function datos_reporte_id(){
        $id = $this->input->get('term');
        $reporte = $this->his->datosReporte($id);
        echo json_encode($reporte);
    }

    //lista de partes delantera
    public function obtener_datos_trasera() {
            $datos = $this->his->get_parte_trasera();
            echo json_encode($datos);
    }
  
    //lista de partes trasera
    public function obtener_datos_delantera() {
        $datos = $this->his->get_parte_delantera();
        echo json_encode($datos);
    }

    //lista de partes trasera
    public function obtener_datos_interior() {
        $datos = $this->his->get_parte_interior();
        echo json_encode($datos);
    }

}
?>