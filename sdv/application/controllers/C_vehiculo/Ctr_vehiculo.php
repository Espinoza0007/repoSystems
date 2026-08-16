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
class Ctr_vehiculo extends ControladorBase
{
    function __construct(){
        parent::__construct();
        $this->load->model('M_vehiculo/Mdl_vehiculo','veh');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }
    function index(){
        $this->global['pageTitle'] = 'Recepcion de vehiculo';
        $this->loadViews('Vehiculo/V_vehiculo',$this->global);
    }
    
    public function guardar_vehiculo()
    {
        if($this->input->is_ajax_request()){
            $equipo         = strval(strtoupper($this->input->post('txt-equipo-vehiculo')));
            $placas         = strval(strtoupper($this->input->post('txt-placas-vehiculo')));
            $marca          = strval(strtoupper($this->input->post('txt-marca-vehiculo')));
            $tipo           = strval(strtoupper($this->input->post('txt-tipo-vehiculo')));
            $anio           = strval(strtoupper($this->input->post('txt-anio-vehiculo')));
            $motor          = strval(strtoupper($this->input->post('txt-motor-vehiculo')));
            $chasis         = strval(strtoupper($this->input->post('txt-chasis-vehiculo')));
            $id_ruta        = intval($this->input->post('txt-id-ruta'));
            $combustible    = strval(strtoupper($this->input->post('txt-combustible-vehiculo')));

            $existe = $this->veh->verificar_regitro(array('Vehi_placas' => $placas, 'Vehi_Ru_Id' => $id_ruta), 'tbl_vehiculo');
            
            // --- SI EXISTE EL DOCUMENTO Y ESTA COMO ANULADO SE EDITA EL ESTADO DE LA FACTURA -------------
            if(!$existe){

                $update_estado = $this->veh->editar_registro(array('Vehi_estado' => 0),array('Vehi_Ru_Id' => $id_ruta), 'tbl_vehiculo');                
                $data_set = array(
                    'Vehi_equipo'           => $equipo,
                    'Vehi_placas'           => $placas,
                    'Vehi_marca'            => $marca,
                    'Vehi_tipo'             => $tipo,
                    'Vehi_anio'             => $anio,
                    'Vehi_numero_motor'     => $motor,
                    'Vehi_numero_chasis'    => $chasis,
                    'Vehi_Ru_Id'            => $id_ruta,
                    'Vehi_tipo_combustible' => $combustible
                );

                $respuesta = $this->veh->agregar_registro('tbl_vehiculo', $data_set);
                if($respuesta){
                    echo json_encode(array(
                        'rs'        => true,
                        'existe'    => true,
                        'info'      => 'VEHICULO AGREGADO CON EXITO',
                        'opcion'    => 'anular'
                    ));
                }else{
                    echo json_encode(array(
                        'rs'        => false,
                        'existe'    => true,
                        'info'      => 'ERROR -> NO SE GUARDO EL VEHICULO',
                        'opcion'    => 'anular'
                    ));
                }

            }else{
                
            }
        }else{
            echo 'SIN ACCESO';
            return;
        }
    }

    public function actualizar_datos_vendedor()
    {
        if($this->input->is_ajax_request()){
            $num_licencia           = strval(strtoupper($this->input->post('txt-numero-licencia')));
            $fecha_vence_lincencia  = strval(strtoupper($this->input->post('txt-vencimiento-licencia')));
            $clase_licencia         = intval(strtoupper($this->input->post('slc-licencias')));
            $id_empleado            = intval($this->input->post('txt-id_empleado'));
            $data_where             = array('Emp_Id' => $id_empleado);
            
            $existe                 = $this->veh->verificar_regitro($data_where, 'tbl_empleados');
            // --- SI EXISTE EL DOCUMENTO Y ESTA COMO ANULADO SE EDITA EL ESTADO DE LA FACTURA -------------
            if($existe){

                $data_set = array(
                    'Emp_Numero_licencia'               => $num_licencia,
                    'Emp_fecha_vencimiento_licencia'    => $fecha_vence_lincencia,
                    'Emp_TLic_Id'                       => $clase_licencia                    
                );

                $respuesta = $this->veh->editar_registro($data_set,$data_where,'tbl_empleados');
                if($respuesta){
                    echo json_encode(array(
                        'rs'     => true,
                        'existe' => true,
                        'info'   => 'EMPLEADO EDITADO CON EXITO'
                    ));
                }else{
                    echo json_encode(array(
                        'rs'     => false,
                        'existe' => true,
                        'info'   => 'ERROR -> NO SE EDITADO EMPLEADO'
                       
                    ));
                  
                }

            }else{
                echo json_encode(array(
                    'rs'     => false,
                    'existe' => false,
                    'info'   => 'El EMPLEADO NO EXITO'
                ));
            }
        }else{
            echo 'SIN ACCESO';
            return;
        }
    }

    public function guardar_checklist()
    {
        if($this->input->is_ajax_request()){
            $Rvehi_Vehi_Id               = intval($this->input->post('txt-id-vehiculo'));
            $Rvehi_check_list_recepcion  = implode(",", $this->input->post('item_list')); 
            $Rvehi_KM_actual             = strval($this->input->post('txt-km-vehiculo')); 
            $Rvehi_observaciones         = strval($this->input->post('txt-observaciones-vehiculo')); 
            $Revehi_Ru_Id                = intval($this->input->post('txt-nombre-ruta')); 

            $Rvehi_nombre_empleado       = strval($this->input->post('txt-nombre-vendedor')); 
            $Rvehi_carnet                = strval($this->input->post('txt-carnet-vendedor')); 

            $Rvehi_Usu_Id                = intval($this->input->post('txt-id-usu'));  
            $Rvehi_fecha_recepcion       = strval($this->input->post('txt-fecha-recepcion'));


            $Id_Recibe                   = intval($this->input->post('txt-codigo-recive')); 
            $Nombre_Recibe               = strval($this->input->post('txt-nombre-recive'));

            $data_set = array(
                'Rvehi_Vehi_Id'                 => $Rvehi_Vehi_Id,
                'Rvehi_check_list_recepcion'    => $Rvehi_check_list_recepcion,
                'Rvehi_KM_actual'               => $Rvehi_KM_actual,
                'Rvehi_observaciones'           => $Rvehi_observaciones,
                'Revehi_Ru_Id'                  => $Revehi_Ru_Id,
                'Rvehi_nombre_empleado'         => $Rvehi_nombre_empleado,
                'Rvehi_carnet'                  => $Rvehi_carnet,
                'Rvehi_Usu_Id'                  => $Rvehi_Usu_Id,
                'Rvehi_fecha_recepcion'         => $Rvehi_fecha_recepcion,
                'Id_Recibe'                     => $Id_Recibe,
                'Nombre_Recibe'                 => $Nombre_Recibe
            );

            $respuesta = $this->veh->agregar_registro('tbl_recepcion_vehiculo', $data_set);
            if($respuesta){
                echo json_encode(array(
                    'rs'        => true,
                    'info'      => 'LISTA AGREGADA CON EXITO'
                ));
            }else{
                echo json_encode(array(
                    'rs'        => false,
                    'info'      => 'ERROR -> NO SE GUARDO LA LISTA'
                ));
            }
        }else{
            echo 'SIN ACCESO';
            return;
        }
    }
    //vehiculos
    function autocompleteData3(){
        $returnData = array();
        // Get skills data
        $conditions['searchTerm'] = $this->input->post('term');
         $skillData = $this->veh->getRows3($conditions);
        // echo json_encode($skillData);
         $datos='<ul class="list-group" id="country-list3">';
         // Generate array
         if(!empty($skillData)){
             foreach ($skillData as $row){
                 //parametro de busqueda
                 $Usu_Ru_Id = $row['Usu_Ru_Id'];
                 $Emp_cod_rutero=$row['Emp_carnet'];
                 $Usu_nombre_usuario = $row['Usu_nombre_usuario'];
                 $Emp_Numero_licencia=$row['Emp_Numero_licencia'];
                 $Emp_fecha_vencimiento_licencia=$row['Emp_fecha_vencimiento_licencia'];
                 $Emp_TLic_Id = $row['Emp_TLic_Id'];
                 //parametro de busqueda
                 $Emp_nombre = $row['Emp_nombre'];
                 $datos=$datos.'<li role="button" class="list-group-item d-flex justify-content-between align-items-center" onClick="selectCountry5('.$Usu_Ru_Id.',&apos;'.$Emp_nombre.'&apos;, '.$Emp_cod_rutero.',&apos;'.$Usu_nombre_usuario.'&apos;, &apos;'.$Emp_Numero_licencia.'&apos; ,&apos;'.$Emp_fecha_vencimiento_licencia.'&apos; ,&apos;'.$Emp_TLic_Id.'&apos;);">
                 <span class=" float-right"> <small >&nbsp;'.$Usu_Ru_Id.'&nbsp;</small>  &nbsp;'.$Emp_nombre.'&nbsp;</span> </li>';
             }
         }
         echo   $datos=$datos.'</ul>';
    }

    public function buscar_vehiculo_por_id() {
        $id = $this->input->get('term');
        $vehiculo = $this->veh->buscar_por_id($id);
        echo json_encode($vehiculo);
    }
}
?>