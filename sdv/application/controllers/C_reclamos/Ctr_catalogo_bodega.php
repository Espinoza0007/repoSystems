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

class Ctr_catalogo_bodega extends ControladorBase
{
    function __construct(){
        parent::__construct();
        $this->load->model('M_reclamos/Mdl_catalogo','cat');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');

    }
    function index(){
        $this->global['pageTitle'] = 'Catalago de productos';
        $this->loadViews_bodega('Reclamos/V_catalogo_bodega',$this->global);
    }

    public function ls_catalogo_productos(){
        if($this->input->is_ajax_request()){
                $canal = $this->session->userdata('id_canal');      

                $data_catalogo = $this->rec->make_datatables($canal,'catalogo');
                $output = array(  
                    "draw"            => intval($_POST["draw"]),  
                    "recordsTotal"    => $this->rec->get_all_data($canal,'catalogo'),  
                    "recordsFiltered" => $this->rec->get_filtered_data($canal,'catalogo'),  
                    "data"            => $data_catalogo, /*,
                    "valor"           => $_POST['search']['value']*/
                    "canal"           => $canal
                );  
                header("Content-type: application/json");
                echo json_encode($output);
                return;
            }
    }

    function ingresoNuevoReclamo(){
        if($this->input->is_ajax_request()){
            $token_reclamo = $this->input->post('token_reclamo');            
            
            if( !$this->rec->verificar_reclamo($token_reclamo) ){

                $ruta_img               = $this->input->post('nombre_ruta');
                $id_cliente             = $this->input->post('Id_Cliente');
                $id_producto            = $this->input->post('Id_Catalogo_Producto');
                $nombre_ruta            = str_replace('.', '', $this->input->post('nombre_ruta'));
                $nombre_division        = $this->input->post('nombre_division');
                $codigo_reclamo         = $this->input->post('codigo_reclamo');  
                $input_fecha_actual     = date('y-m-d H:i:s');
                $fecha_telefono         = $this->input->post('fecha_telefono');
                $input_id_tipo_reclamo  = $this->input->post('select_tipo_reclamo');
                $input_cantidad         = mb_strtoupper(quitar_acentos($this->input->post('txtCantidad')));            

                $input_fecha_vencimiento    = $this->input->post('txtFechaVencimiento') != '' ? strval(mb_strtoupper(quitar_acentos($this->input->post('txtFechaVencimiento')))) : null;

                $input_foto_fecha_lote      = $this->generarimagen_rec($this->input->post('fileFechaLote'),$ruta_img);
                $input_foto_producto        = $this->generarimagen_rec($this->input->post('fileProducto'),$ruta_img);
                $input_numero_lote          = $this->input->post('numeroLote') != '' ? strval(mb_strtoupper(quitar_acentos($this->input->post('numeroLote')))) : null;         
                $input_unidad_medida        = mb_strtoupper(quitar_acentos($this->input->post('select_unidad_medida')));

                $data_insertar = array(
                    'Rec_Id'                => strval($codigo_reclamo),
                    'Rec_Cli_Id'            => intval($id_cliente),
                    'Rec_Cat_Id'            => strval($id_producto),
                    'Rec_Tipd_Id'           => intval($input_id_tipo_reclamo),
                    'Rec_cantidad'          => intval($input_cantidad),
                    'Rec_unidad_medida'     => strval($this->input->post('select_unidad_medida')),
                    'Rec_fecha_vencimiento' => $input_fecha_vencimiento,
                    'Rec_numero_lote'       => $input_numero_lote,
                    'Rec_foto_fecha_lote'   => strval($input_foto_fecha_lote),
                    'Rec_foto_producto'     => strval($input_foto_producto),
                    'Rec_fecha_telefono'    => $fecha_telefono,
                    'Rec_fecha_servidor'    => $input_fecha_actual,
                    'Rec_Usu_Id'            => intval(desencriptar_cadena($this->input->post('usuario'))),
                    'Rec_tipo_usuario'      => intval($this->input->post('tipoUsuario')),
                    'Rec_token'             => strval($token_reclamo),
                    'Rec_estado'            => intval(1)
                );

                $respuesta=$this->rec->guardarReclamoNuevo($data_insertar);
                if($respuesta){
                    echo json_encode(array(
                        'rs' => TRUE,
                        'info' => ' El registro se guard&oacute; correctamente.',
                        'cla' => 'success grSuccess'
                        )
                    );
                }else{
                    echo json_encode(array(
                        'rs' => FALSE,
                        'info' => 'Ocurri&oacute; un error durante el proceso.',
                        'cla' => 'success grDanguer'
                        )
                    );
                }
            }else {
                echo json_encode(array(
                    'rs'    => TRUE,
                    'info'  => 'ITEM RECLAMO YA EXISTE (No agregado)'
                ));
            }
        }

    }

    public function generarimagen($img,$ruta){
        if(empty($img)){
            return NULL;
        }else{
            $nombre_imgdb = '';
            $fecha_img = date('d-m-Y_His');
            if(empty($ruta)){
                $runanombre = 'snomruta';
            }else{
                $runanombre = str_replace(".", "",$ruta);
            } 
            $carpeta = "../Uploads/img_server/Img_CatalagoProductos/".$runanombre;

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $folderPath = $carpeta;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $nombrenuevo = $runanombre."_".numero_aleatorio(17)."_".$fecha_img;
            $file = $folderPath."/".$nombrenuevo.'.jpg';
            file_put_contents($file,$image_base64);
            $file = str_replace("../../Img_CatalagoProductos/","",$file);
            return $file;
        }
    }
    

 
}


?>