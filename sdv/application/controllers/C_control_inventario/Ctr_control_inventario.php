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

class Ctr_control_inventario extends ControladorBase
{
    function __construct(){
        parent::__construct();
        $this->load->model('M_control_inventario/Mdl_control_inventario','cti');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');

    }

    function index(){
        $this->global['pageTitle'] = 'Control de inventario';
        $this->loadViews('Control_inventario/V_control_inventario',$this->global);
    }

    /// cti --> control de inventario
    /// cti --> control de inventario ---- 13 /09 / 2021
    public function guardar_items_cti()
    {
        if ($this->input->is_ajax_request()) {
            $con = 0;
            $data = array();
            $fecha_actual   = date('Y-m-d H:i:s');
            $info = array();
            $verif = [];
            $Cti_estado = 1;
            $arr_cti_id = $this->input->post('txtCtiId'); // ids productos
            $arr_vencimiento = $this->input->post('txtFechaVencimientoCti');
            $arr_cantidad = $this->input->post('txtCantidadCti');
            $id_cliente = $this->input->post('id_cliente');
            $id_usuario = $this->input->post('id_usuario');
            $fecha_tel = $this->input->post('fecha_tel');
            $latitud_ini = $this->input->post('latitud_ini');
            $longitud_ini = $this->input->post('longitud_ini');
            $eliminados = $this->input->post('eliminados');
            $token = $this->input->post('token_cti');
            $token_eliminar = $this->input->post('token_item_eliminar');
            $codigos = array();

            if($eliminados != ""){
                $codigos = explode(',', $eliminados);
            }

            if(!empty($arr_cti_id)){
                foreach($arr_cti_id as $val){
                    if(!empty($codigos)){
                        $Cti_estado = in_array($arr_cti_id[$con], $codigos) ? 0 : 1;
                    }
                    $data = array(
                        'Cti_Cat_Id'            => intval($arr_cti_id[$con]),
                        'Cti_Cli_Id'            => intval($id_cliente),
                        'Cti_Usu_Id'            => intval($id_usuario),
                        'Cti_cantidad'          => intval($arr_cantidad[$con]),
                        'Cti_fecha_vencimiento' => strval($arr_vencimiento[$con]),
                        'Cti_fecha_servidor'    => strval($fecha_actual),
                        'Cti_fecha_telefono'    => strval($fecha_tel),
                        'Cti_token'             => strval($token),
                        'Cti_latitud'           => strval($latitud_ini),
                        'Cti_longitud'          => strval($longitud_ini),
                        'Cti_estado'            => intval($Cti_estado)
                    );
                    if(!$this->cti->verificar_regitro(array('Cti_token' => $token, 'Cti_Cat_Id' => $arr_cti_id[$con]), 'tbl_control_inventario')){
                        $dat = $this->cti->agregar_registro('tbl_control_inventario',$data);
                        $info[] = 'ITEM '.$arr_cti_id[$con].' CTI GUARDADO ✓';
                    }else{
                        if($arr_vencimiento[$con] != '' && $arr_vencimiento[$con] != null && $arr_cantidad[$con] != '' && $arr_cantidad[$con] != null){
                            $dat = $this->cti->editar_registro($data, array('Cti_token' => $token, 'Cti_Cat_Id' => $arr_cti_id[$con]), 'tbl_control_inventario');
                            $info[] = 'ITEM '.$arr_cti_id[$con].' CTI YA EXISTE (editado)';
                        }else{
                            $info[] = 'ITEM '.$arr_cti_id[$con].' CTI YA EXISTE (sin cambios)';
                        }
                    }
                    $con++;
                }

                if(!empty($codigos)){
                    foreach($codigos as $val){
                        if($this->cti->verificar_regitro(array('Cti_token' => $token_eliminar, 'Cti_Cat_Id' => $val), 'tbl_control_inventario')){
                            $data_where = array(
                                'Cti_token' => $token_eliminar,
                                'Cti_Cat_Id' => $val
                            );
                            $data_set = array(
                                'Cti_estado' => 0
                           );
                        $dat = $this->cti->editar_registro($data_set, $data_where, 'tbl_control_inventario');
                            $info[] = 'ITEM '.$val.' CTI YA EXISTE (eliminado)';
                        }else{
                            $info[] = 'ITEM '.$val.' CTI no encontrado 0.o';
                        }
                    }
                }
                
                echo json_encode(array(
                    'rs'    => TRUE,
                    'info'  => $info,
                    'DATA' => $data,
                    'arr_cti_id' => $arr_cti_id,
                    'eliminados' => $token_eliminar,
                    'codigos' => $codigos
                ));
            }else{
                echo json_encode(array(
                    'rs'        => FALSE,
                    'errores'   => 'ERROR DESCONOCIDO'
                ));
            }

        }       
    }

    public function procesar_cola_cti()
    {
        if ($this->input->is_ajax_request()) {
            $fecha_actual = date('Y-m-d H:i:s');
            $estado_cola = $this->input->post('Pendiente');
            
            if($estado_cola = "SI"){
                $id_producto        = $this->input->post('id_producto');
                $id_cliente         = $this->input->post('id_cliente');
                $id_usuario         = $this->input->post('id_usuario');
                $cantidad           = $this->input->post('cantidad');
                $fecha_vencimiento  = $this->input->post('fecha_vencimiento');
                $fecha_telefono     = $this->input->post('fecha_telefono');
                $latitud_ini  = $this->input->post('latitud_ini') != '' ? $this->input->post('latitud_ini') : '0';
                $longitud_ini = $this->input->post('longitud_ini') != '' ? $this->input->post('longitud_ini') : '0';
                $token        = $this->input->post('token_cti');
                $estado = $this->input->post('opcion') == 'eliminado' ? 0 : 1;

                if( $this->cti->verificar_regitro(array('Cti_token' => strval($token),'Cti_Cat_Id' => strval($id_producto)), 'tbl_control_inventario') ){
                    $data_set = array(
                        'Cti_Cli_Id'            => intval($id_cliente),
                        'Cti_Usu_Id'            => intval($id_usuario),
                        'Cti_cantidad'          => intval($cantidad),
                        'Cti_fecha_vencimiento' => strval($fecha_vencimiento),
                        'Cti_fecha_servidor'    => strval($fecha_actual),
                        'Cti_fecha_telefono'    => strval($fecha_telefono),
                        'Cti_token'             => strval($token),
                        'Cti_latitud'           => strval($latitud_ini),
                        'Cti_longitud'          => strval($longitud_ini),
                        'Cti_estado'            => intval($estado),
                    );
                    $dat = $this->cti->editar_registro($data_set, array('Cti_token' => strval($token),'Cti_Cat_Id' => strval($id_producto)),'tbl_control_inventario');
                    if ($dat) {
                        echo json_encode(array(
                            'rs'    => TRUE,
                            'info'  => 'ITEM CTI EN COLA ENVIADA ✓ (editado)',
                            'array' => $dat
                        ));
                    } else {
                        echo json_encode(array(
                            'rs'        => FALSE,
                            'errores'   => 'NO'
                        ));
                    }                    
                    return;
                }else {
                    $data_set = array(
                        'Cti_Cat_Id'            => intval($id_producto),
                        'Cti_Cli_Id'            => intval($id_cliente),
                        'Cti_Usu_Id'            => intval($id_usuario),
                        'Cti_cantidad'          => intval($cantidad),
                        'Cti_fecha_vencimiento' => strval($fecha_vencimiento),
                        'Cti_fecha_servidor'    => strval($fecha_actual),
                        'Cti_fecha_telefono'    => strval($fecha_telefono),
                        'Cti_token'             => strval($token),                         
                        'Cti_latitud'           => strval($latitud_ini),
                        'Cti_longitud'          => strval($longitud_ini),
                        'Cti_estado'            => intval($estado)

                    );
                    $dat = $this->cti->agregar_registro('tbl_control_inventario',$data_set);
                    if ($dat) {
                        echo json_encode(array(
                            'rs'    => TRUE,
                            'info'  => 'ITEM CTI EN COLA ENVIADA ✓ (agregado)'
                        ));
                    }else{
                        echo json_encode(array(
                            'rs'        => FALSE,
                            'errores'   => 'NO'
                        ));
                    }     
                    return;
                }

            }
        }else{
            echo json_encode(array(
                'rs'    => TRUE,
                'ERROR' => 'ALGO MALIO SAL'
            ));
            return;
        }
    }
}


?>