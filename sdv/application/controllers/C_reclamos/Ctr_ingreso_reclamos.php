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

class Ctr_ingreso_reclamos extends ControladorBase
{
    function __construct(){
        parent::__construct();
        $this->load->model('M_reclamos/Mdl_reclamos','rec');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');

    }

    function index(){
        $this->global['pageTitle'] = 'Registro de reclamos';
        $this->loadViews('Reclamos/V_ingresoReclamos',$this->global);
    }

    function index_admin(){
        if($this->session->userdata('tipousuario')){

            if(strcmp($this->session->userdata('tipousuario'), 'BODEGA') == 0 || strcmp ($this->session->userdata('tipousuario'), 'CALIDAD') == 0){
                $this->global['pageTitle'] = 'Reclamos de calidad';
                $this->loadViews_bodega('Reclamos/V_adminReclamos',$this->global);
            }
            else if(strcmp($this->session->userdata('tipousuario'), 'ADMIN VENTAS') == 0){
                $this->global['pageTitle'] = 'Reclamos de calidad';
                $this->loadViews_admin('Reclamos/V_adminReclamos',$this->global);
            }
            else{
                redirect('../../sdv/', 'refresh');                
            }
        }else{
            redirect('../../sdv/', 'refresh');
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
                $input_cantidad         = $this->input->post('txtCantidad'); // cantidad a entregar para cambio             
                $unidades_danadas = $this->input->post('txtUnidadesDanadas'); // Cantidad unidades dañadas             

                $input_fecha_vencimiento    = $this->input->post('txtFechaVencimiento') != '' ? strval(mb_strtoupper(quitar_acentos($this->input->post('txtFechaVencimiento')))) : null;

                $input_foto_fecha_lote      = $this->generarimagen_rec($this->input->post('fileFechaLote'),$ruta_img);
                $input_foto_producto        = $this->generarimagen_rec($this->input->post('fileProducto'),$ruta_img);
                $input_numero_lote          = $this->input->post('numeroLote') != '' ? strval(mb_strtoupper(quitar_acentos($this->input->post('numeroLote')))) : null;         
                // $input_unidad_medida        = mb_strtoupper(quitar_acentos($this->input->post('select_unidad_medida')));
                $input_unidad_medida        = 'UN';

                $data_insertar = array(
                    'Rec_Id'                => strval($codigo_reclamo),
                    'Rec_Cli_Id'            => intval($id_cliente),
                    'Rec_Cat_Id'            => strval($id_producto),
                    'Rec_Tipd_Id'           => intval($input_id_tipo_reclamo),
                    'Rec_cantidad'          => intval($input_cantidad),
                    'Rec_unidades_danadas'  => intval($unidades_danadas),
                    'Rec_unidad_medida'     => strval($input_unidad_medida),
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

    public function obtener_lista_reclamos1(){
        if($this->input->is_ajax_request()){
            $parametros = array();
            $parametros['codigo_pais'] = $this->input->post('codigo_pais');
            $parametros['codigo_division'] = $this->input->post('codigo_division');
            $parametros['codigo_dis'] = $this->input->post('codigo_dist');
            $parametros['codigo_ca'] = $this->input->post('codigo_ca');
            $parametros['codigo_grupo'] = $this->input->post('codigo_grupo');
            $parametros['codigo_ruta'] = $this->input->post('codigo_ruta');
            $parametros['fecha_inicial'] = $this->input->post('fecha_inicial');
            $parametros['fecha_limite'] = $this->input->post('fecha_limite');
            $parametros['estado'] = $this->input->post('estado');
            $rows = $this->rec->get_all_data($parametros, 'lista');
            $data_lista_reclamos = $this->rec->make_datatables($parametros, 'lista');
            $output = array(  
                "draw"            => intval($_POST["draw"]),  
                "recordsTotal"    => $rows,  
                "recordsFiltered" => $rows,  
                "data"            => $data_lista_reclamos,
                "estado"          => $parametros['estado'] 
            );  

            echo json_encode($output);
            return;
        }

    }

    public function obtener_reclamo(){
        if($this->input->is_ajax_request()){
            $rec_Id = $this->input->post('rec_codigo');

            $datos_reclamo = $this->rec->obtener_registro_reclamo($rec_Id);

            echo json_encode($datos_reclamo);
            return;
        }
    }

    public function filtro_distribuidora(){
        if($this->input->is_ajax_request()){
            $codigo_division = $this->input->post('codigo');
            $distribuidora = $this->input->post('distribuidora');
            $privilegio = intval($this->session->userdata('id_privilegio'));
            // $data = $privilegio != 5 ? $this->rec->lista_distribuidoras($codigo_division,$distribuidora) : $this->rec->lista_distribuidoras($codigo_division,0);
            
            $data = $this->rec->lista_distribuidoras($codigo_division,0);
            echo json_encode($data);
            return;
        }
    }
    // lista de distribuidoras / clientes para usuarios de paises terceros ----------------------
    public function filtro_distribuidora_pt(){
        if($this->input->is_ajax_request()){
            $ruta = $this->session->userdata('id_pais');
            $data = $this->rec->lista_distribuidoras_pt($ruta);
            echo json_encode($data);
            return;
        }
    }
    // ------------------------------------------------------------------------------------------
    
    public function filtro_canal(){
        if($this->input->is_ajax_request()){
            $codigo_dist = $this->input->post('codigo');
            $data = $this->rec->lista_canal($codigo_dist);
            echo json_encode($data);
            return;
        }
    }
    
    public function filtro_grupo(){
        if($this->input->is_ajax_request()){
            $codigo_ca = $this->input->post('codigo');
            $data = $this->rec->lista_grupo($codigo_ca);

            echo json_encode($data);
            return;
        }
    }
    
    public function filtro_ruta(){
        if($this->input->is_ajax_request()){
            $codigo_grupo = $this->input->post('codigo');
            $data = $this->rec->lista_rutas($codigo_grupo);

            echo json_encode($data);
            return;
        }
    }

    public function guardar_imagen_reclamo(){
        if($this->input->is_ajax_request()){
            $info = "";
            $codigo_reclamo = $this->input->post('codigo_reclamo');
            $img_ruta = $this->input->post('ruta');
            $img_reclamo = $this->generarimagen_rec($this->input->post('foto_reclamo'),$img_ruta);
            if($img_reclamo != ""){
                $data = $this->rec->guardar_img_reclamo($codigo_reclamo, $img_reclamo);
                echo json_encode(array(
                    'rs'    => $data,
                    'info'  => 'El registro se guard&oacute; correctamente.',
                    'cla'   => 'success grSuccess'
                    )
                );
            }else{
                echo json_encode(array(
                    'rs'    => false,
                    'info'  => 'El formato del archivo no es soportado',
                    'cla'   => 'success grSuccess'
                    )
                );
            }
            return;
        }
    }

    public function generarimagen_rec($img,$ruta){
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
            $carpeta = "../Uploads/img_server/Img_reclamos/".$runanombre;

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $permitidos = array("pdf", "jpg", "jpeg", "png");
            $folderPath = $carpeta;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("/", $image_parts[0]);

            if(in_array($image_type_aux[1], $permitidos)){

                $nombrenuevo = $runanombre."_".numero_aleatorio(17)."_".$fecha_img;
                if(strcmp ($image_type_aux[0], 'data:application' ) == 0)
                {
                    $file = $folderPath."/".$nombrenuevo.'.pdf';        
                }else{
                    $file = $folderPath."/".$nombrenuevo.'.jpg';    
                }
                $image_base64 = base64_decode($image_parts[1]);
                file_put_contents($file,$image_base64);
                $file = str_replace("../Img_reclamos/","",$file);
                return $file;
            }else{
                return "";
            }
        }
    }

    public function generarReclamoPdf(){
        $fecha_actual   = date('Y_m_d_h_i_s');
        $nombre_pdf     = "Reclamo_".$fecha_actual;
        $dompdf         = new Dompdf\Dompdf(array ('enable_remote' => true));
        $html = '';
        if ($this->input->get('codigo_reclamo')) {
            $rec_Id         = $this->input->get('codigo_reclamo');
            $reclamo_arr    = $this->rec->obtener_registro_reclamo($rec_Id);
            $data['reclamo_arr'] = $reclamo_arr;
            $htmlP          = $this->load->view('Reclamos/V_reclamoPdf',$data,TRUE);
            $dompdf->loadHtml($htmlP);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();
            $pdf = $dompdf->output();
            $dompdf->stream($nombre_pdf, array("Attachment"=>0));
        }        
    }

    public function filtro_pais(){
        if($this->input->is_ajax_request()){
            $n_pais     = strval($this->session->userdata('pais'));
            $privilegio = intval($this->session->userdata('id_privilegio'));

            if($privilegio != 5){
                $data = $this->rec->lista_paises($n_pais);
            }else{
                $data = $this->rec->lista_paises(-1);
            }
            echo json_encode($data);
            return;
        }
    }

    public function filtro_division(){
        if($this->input->is_ajax_request()){
            $estado_pt_ = $this->session->userdata('usu_pais_tercero');
            $pais       = $this->input->post('codigo');
            $privilegio = $this->input->post('privilegio');
            $division   = $this->input->post('division');

            $data = $privilegio != 5 && !$estado_pt_ ? 
                $this->rec->lista_division($pais,$division) : 
                $this->rec->lista_division($pais,0);   
            echo json_encode($data);
            return;
        }
    }

    public function ls_productos_bodega(){
        if($this->input->is_ajax_request()){
            $parametros     = array();
            $tipo_catalogo  = $this->input->post('tipo_catalogo');
            $canal          = $this->session->userdata('id_canal');      
            $pais           = $this->session->userdata('id_pais');      
            $division       = $this->session->userdata('id_division');  

            $parametros['canal']    = $canal;      
            $parametros['division'] = $division;      
            $parametros['pais']     = $pais;   

            $parametros['codigo_producto']  = '';      
            $parametros['subfamilia']       = '';      
            $parametros['familia']          = '';      
            $parametros['estado']           = '';      
            $rowsCount = $this->rec->get_all_data($parametros,'catalogo_bo');
            $data_catalogo  = $this->rec->make_datatables($parametros,'catalogo_bo');
            $output = array(  
                "draw"              => intval($_POST["draw"]),  
                "recordsTotal"      => $rowsCount,  
                "recordsFiltered"   => $rowsCount,  
                "data"              => $data_catalogo,
                "canal"             => $canal,
                "pais"              => $parametros
            );  
            header("Content-type: application/json");
            echo json_encode($output);
            return;
        }
    }

    public function filtro_familas_catalago(){
        if($this->input->is_ajax_request()){
            $canal1 = $this->session->userdata('id_canal');

            $data = $this->rec->filtro_familias1($canal1);

            echo json_encode($data);
            return;
        }
    }

    public function filtro_subfamilas_catalago(){
        if($this->input->is_ajax_request()){
            $codigo_familia = $this->input->post('codigo');
            $canal1 = $this->session->userdata('id_canal');
            $data = $this->rec->filtro_subfamilias($canal1,$codigo_familia);
            echo json_encode($data);
            return;
        }
    }

    public function filtro_um(){
        if($this->input->is_ajax_request()){
            $data = $this->rec->filtro_unidad_medida();
            echo json_encode($data);
            return;
        }
    }

    public function ls_tipo_reclamos_bodega(){
        if($this->input->is_ajax_request()){

            $data = $this->rec->listadoTipoDanosReclamos(2);
            echo json_encode($data);
            return;
        }
    }

    function ingresoReclamo_bodega(){
        if($this->input->is_ajax_request()){

            $data_cliente       = array();
            // $idRutaCli          = $this->session->userdata('codRuta');
            $nombreRutaCli      = $this->session->userdata('nomruta');
            $tipoUsuario        = $this->session->userdata('id_privilegio');

            $idRutaCli          = $this->session->userdata('usu_pais_tercero') ?
                                $this->input->post('slc_distribuidora_pt') : 
                                $this->session->userdata('codRuta');

            $data_cliente       = $this->rec->get_cliente_bodega_mercadeo($idRutaCli);
            $id_cliente         = $data_cliente[0]->Cli_Id;    

            $id_producto        = $this->input->post('txtCodigoP_');

            $codigo_reclamo             = $data_cliente[0]->Di_nombre.$data_cliente[0]->Cli_Ru_Id.date('YmdHis').$data_cliente[0]->Cli_Id;
            $token_reclamo              = $data_cliente[0]->Di_nombre.$data_cliente[0]->Cli_Ru_Id.date('YmdHis');

            $input_fecha_actual         = date('Y-m-d H:i:s');

            $input_id_tipo_reclamo      = $this->input->post('slc_tipo_reclamos');
            $input_cantidad             = mb_strtoupper(quitar_acentos($this->input->post('txtCantidad_')));            

            $input_fecha_vencimiento    = $this->input->post('txtFechaVencimiento_') != '' ? strval(mb_strtoupper(quitar_acentos($this->input->post('txtFechaVencimiento_')))) : null;

            $input_foto_fecha_lote      = $this->generarimagen_rec($this->input->post('fileFechaLote'),$idRutaCli);
            $input_foto_producto        = $this->generarimagen_rec($this->input->post('fileProducto'),$idRutaCli);
            
            $input_numero_lote          = $this->input->post('txtNumLote_') != '' ? strval(mb_strtoupper(quitar_acentos($this->input->post('txtNumLote_')))) : null;                  
            
            $input_unidad_medida        = mb_strtoupper(quitar_acentos($this->input->post('select_um')));
            $input_unidades_danadas     = $this->input->post('txtUnidadesDanadas_bo');
            $input_observacion          = $this->input->post('txtObservacion_') != '' ? mb_strtoupper(quitar_acentos($this->input->post('txtObservacion_'))) : null;

            $data_insertar = array(
                'Rec_Id'                => strval($codigo_reclamo),
                'Rec_Cli_Id'            => intval($id_cliente),
                'Rec_Cat_Id'            => strval($id_producto),
                'Rec_Tipd_Id'           => intval($input_id_tipo_reclamo),
                'Rec_cantidad'          => intval($input_cantidad),
                'Rec_unidades_danadas'  => intval($input_unidades_danadas),
                'Rec_unidad_medida'     => strval($input_unidad_medida),
                'Rec_fecha_vencimiento' => $input_fecha_vencimiento,
                'Rec_numero_lote'       => $input_numero_lote,
                'Rec_foto_fecha_lote'   => strval($input_foto_fecha_lote),
                'Rec_foto_producto'     => strval($input_foto_producto),
                'Rec_fecha_telefono'    => $input_fecha_actual,
                'Rec_fecha_servidor'    => $input_fecha_actual,
                'Rec_observacion'       => $input_observacion,
                'Rec_Usu_Id'            => intval(desencriptar_cadena($this->session->userdata('codusuario'))),
                'Rec_token'             => strval($token_reclamo),
                'Rec_tipo_usuario'      => intval($tipoUsuario),
                'Rec_estado'            => intval(1)
            );

            $respuesta = $this->rec->guardarReclamoNuevo($data_insertar);
            if($respuesta){
                echo json_encode(array(
                    'rs'    => TRUE,
                    'info'  => 'El registro se guard&oacute; correctamente.',
                    'cla'   => 'success grSuccess'
                    )
                );
            }else{
                echo json_encode(array(
                    'rs'    => FALSE,
                    'info'  => 'Ocurri&oacute; un error durante el proceso.',
                    'cla'   => 'success grDanguer'
                    )
                );
            }
        }

    }
    function get_reclamos_eviados(){
        if($this->input->is_ajax_request()){
            $idRuto = $this->session->userdata('codRuta');
            $data   = $this->rec->get_reclamos_enviados($idRuto);
            echo json_encode($data);
            return;
        }
    }

    function guardar_comentario_rec(){
        if($this->input->is_ajax_request()){
            $parametros = array();
            $parametros['rec_id']           = $this->input->post('rec_id');
            $parametros['cat_id']           = $this->input->post('cat_id');
            $parametros['txtComentario']    = mb_strtoupper($this->input->post('txtComentario'));

            $data = $this->rec->update_comentario_rec($parametros);
            if($data){
                echo json_encode(array(
                        'rs'    => $data,
                        'info'  => 'Informacion guardada!',
                        )
                    );

            }else{
                echo json_encode(array(
                        'rs'    => $data,
                        'info'  => 'Error!',
                        )
                    );
            }
            // echo json_encode($data);
            return;
        }
    }

    function get_all_reclamos_xlsx_1(){
        if($this->input->is_ajax_request()){
            $parametros['codigo_pais']      = $this->input->post('codigo_pais');
            $parametros['codigo_division']  = $this->input->post('codigo_division');
            $parametros['codigo_dis']       = $this->input->post('codigo_dist');
            $parametros['codigo_ca']        = $this->input->post('codigo_ca');
            $parametros['codigo_grupo']     = $this->input->post('codigo_grupo');
            $parametros['codigo_ruta']      = $this->input->post('codigo_ruta');
            $parametros['fecha_inicial']    = $this->input->post('fecha_inicial');
            $parametros['fecha_limite']     = $this->input->post('fecha_limite');
            $parametros['estado']           = $this->input->post('estado');

            if($this->session->userdata('id_privilegio') != 5){
                $parametros['codigo_division'] = $this->session->userdata('id_division');
            }

            $data_ls_reclamos = $this->rec->get_all_reclamos_xlsx($parametros);
            if(!empty($data_ls_reclamos)){
                $fecha_actual = date('Y_m_d_his');
                $spreadsheet = new Spreadsheet();
                $spreadsheet->setActiveSheetIndex(0);
                $sheet = $spreadsheet->getActiveSheet();
                $tiponumero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
                $tipoletras = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
                $sheet->getStyle('A1:V1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('9ae5e5');
                $sheet->getStyle('A1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                  
                $sheet->getStyle('B1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('C1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('D1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('E1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('F1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('H1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('I1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('J1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('K1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('L1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('M1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('N1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('O1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('P1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('Q1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('R1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('S1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('T1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                $sheet->getStyle('U1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('V1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('W1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->getStyle('A1:V1')->getFont()->setBold( true );
                $sheet->getDefaultColumnDimension()->setWidth(25);
                // $sheet->getColumnDimension('P')->setWidth(40);

                $sheet->setCellValue('A1','NUMERO_RECLAMO');
                $sheet->setCellValue('B1','TIPO_RECLAMO');
                $sheet->setCellValue('C1','CODIGO_PRODUCTO');
                $sheet->setCellValue('D1','PRODUCTO');
                $sheet->setCellValue('E1','CANTIDAD_A_ENTREGAR');
                $sheet->setCellValue('F1','FAMILIA_PRODUCTO');
                $sheet->setCellValue('G1','SUBFAMILIA_PRODUCTO');
                $sheet->setCellValue('H1','FECHA_VENCIMIENTO');
                $sheet->setCellValue('I1','NUMERO_LOTE');
                $sheet->setCellValue('J1','UNIDADES_DANADAS');
                $sheet->setCellValue('K1','CODIGO_CLIENTE');
                $sheet->setCellValue('L1','NOMBRE_CLIENTE');
                $sheet->setCellValue('M1','AREA');
                $sheet->setCellValue('N1','FECHA_RECLAMO');
                $sheet->setCellValue('O1','TOKEN');
                $sheet->setCellValue('P1','OBSERVACION');
                $sheet->setCellValue('Q1','OBSERVACION_VENTAS');
                $sheet->setCellValue('R1','ESTADO');
                $sheet->setCellValue('S1','PAIS');
                $sheet->setCellValue('T1','DIVISION');
                $sheet->setCellValue('U1','DISTRIBUIDORA');
                $sheet->setCellValue('V1','RUTA');
                // $sheet->setCellValue('W1','COMENTARIO_CIERRE');
                $con = 2;
                foreach ($data_ls_reclamos as $v) {
                    $sheet->getStyle('A'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('B'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('C'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('D'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('E'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('F'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('G'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('H'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('I'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('J'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('K'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('L'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('M'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('N'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('O'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('P'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('Q'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('R'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('S'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('T'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('U'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    $sheet->getStyle('V'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                    // $sheet->getStyle('W'.$con)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);   

                    // $sheet->getStyle('A'.$con.':W'.$con)->getAlignment()->setWrapText(true);
                    $sheet->getStyle('A'.$con.':W'.$con)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);                      
                    $sheet->getStyle('A'.$con.':W'.$con)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);                 
                    $sheet->setCellValueExplicit('A'.$con,$v->NUMERO_RECLAMO,$tipoletras);
                    $sheet->setCellValue('B'.$con,mb_strtoupper($v->TIPO_RECLAMO));
                    $sheet->setCellValueExplicit('C'.$con,$v->CODIGO_PRODUCTO,$tiponumero);
                    $sheet->setCellValue('D'.$con,$v->PRODUCTO);
                    $sheet->setCellValue('E'.$con,$v->CANTIDAD_A_ENTREGAR);
                    $sheet->setCellValue('F'.$con,$v->FAMILIA_PRODUCTO);
                    $sheet->setCellValue('G'.$con,$v->SUBFAMILIA_PRODUCTO);
                    $sheet->setCellValue('H'.$con,$v->FECHA_VENCIMIENTO);
                    $sheet->setCellValue('I'.$con,$v->NUMERO_LOTE);
                    $sheet->setCellValue('J'.$con,$v->UNIDADES_DANADAS);
                    $sheet->setCellValue('K'.$con,$v->CODIGO_CLIENTE);
                    $sheet->setCellValue('L'.$con,$v->NOMBRE_CLIENTE);
                    $sheet->setCellValue('M'.$con,$v->AREA);
                    $sheet->setCellValue('N'.$con,$v->FECHA_RECLAMO);
                    $sheet->setCellValue('O'.$con,$v->TOKEN);
                    $sheet->setCellValue('P'.$con,$v->OBSERVACION);
                    $sheet->setCellValue('Q'.$con,$v->OBSERVACION_VENTAS);
                    $sheet->setCellValue('R'.$con,$v->ESTADO);
                    $sheet->setCellValue('S'.$con,$v->PAIS);
                    $sheet->setCellValue('T'.$con,$v->DIVISION);
                    $sheet->setCellValue('U'.$con,$v->DISTRIBUIDORA);
                    $sheet->setCellValue('V'.$con,$v->RUTA);
                    // $sheet->setCellValue('W'.$con,$v->COMENTARIO_CIERRE);
                    $con++;
                }
                $nombre_archivo = '../Uploads/Plantilla_Excel/Reclamos_'.$fecha_actual.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

                echo json_encode(array(
                        'rs'        => true,
                        'info'      => 'Reclamos generados correctamente',
                        'cla'       => 'success grSuccess',
                        'archivo'   => $nombre_archivo
                    ));
                
                return;
            }else{
                echo json_encode(array(
                    'rs'        => true,
                    'info'      => 'No se encontraron registros',
                    'cla'       => 'vacio',
                    'archivo'   => ''
                ));
                return;
            }
           
        }
    }

    function procesar_producto_catalogo_bo(){
        if($this->input->is_ajax_request()){
            $data_insertar      = array();
            $pais               = $this->session->userdata('pais');
            $canal              = $this->session->userdata('id_canal');
            $opcion             = $this->input->post('opcion');
            $codigo_cat         = $this->input->post('txtCodigoCat');
            $descripcion_cat    = $this->input->post('txtDescripcionCat');
            $familia            = $this->input->post('slc_familia_cat');
            $subfamilia         = $this->input->post('slc_subfamilia_cat');
            $unidad_medida      = $this->input->post('slc_UM_cat');
            $estado_cat         = 1;
            // $estado_cat         = $this->input->post('slc_estado_cat');
            $canales_           = $this->input->post('id_canales_ls');
            $canales_inactivos  = $this->input->post('canales_inactivos');
            
            if(strcmp($opcion, 'Agregar') == 0 ){

                if(!$this->rec->verificar_regitro(array('Cat_Id' => $codigo_cat),'tbl_catalogo_productos')){
                    $foto_producto_cat  = $this->generarimagen($this->input->post('foto_catalogo_producto'),$pais);
                    $data_insertar = array(
                        'Cat_Id'             => strval($codigo_cat),
                        'Cat_descripcion'    => strval(mb_strtoupper($descripcion_cat)),
                        'Cat_img'            => strval($foto_producto_cat),
                        'Cat_Um_Id'          => intval($unidad_medida),
                        'Cat_Subf_Id'        => intval($subfamilia)
                    );
                    $respuesta      = $this->rec->agregar_registro('tbl_catalogo_productos', $data_insertar);
                    $data_asignar   = array();
                    
                    if($respuesta){
                        if(!empty($canales_)){
                            foreach($canales_ as $v){
                                array_push($data_asignar, array(
                                    'Catx_Cat_Id'   => strval($codigo_cat),
                                    'Catx_Ca_Id'    => intval($v),
                                    'Catx_estado'   => intval($estado_cat)
                                ));
                            }
                        }
                        array_push($data_asignar, array(
                            'Catx_Cat_Id'   => strval($codigo_cat),
                            'Catx_Ca_Id'    => intval($canal),
                            'Catx_estado'   => intval($estado_cat)
                        ));

                        $respuesta_asignar = $this->rec->agregar_registros('tbl_catalogopro_x_canal', $data_asignar);
                        
                        if($respuesta_asignar){
                            echo json_encode(array(
                                'rs'    => TRUE,
                                'info'  => 'El registro se asignó correctamente.(Agregado)',
                                'cla'   => 'success'
                                )
                            );
                        }else{
                            echo json_encode(array(
                                'rs'    => false,
                                'info'  => 'Ocurrio un error, el registro no se arignó a un canal',
                                'cla'   => 'error'
                                )
                            );
                        }
                        return;
                    }else{
                        echo json_encode(array(
                            'rs'    => false,
                            'info'  => 'Ocurrio un error, el registro no se agregó',
                            'cla'   => 'error'
                            )
                        );
                        return;
                    }
                }else{
                    echo json_encode(array(
                        'rs'    => false,
                        'info'  => 'El registro ya existe',
                        'cla'   => 'repetido'
                        )
                    );
                }
            }else{
                $veditar = 0;
                $vestado = 0;
                $foto_catalogo_producto = $this->input->post('foto_catalogo_producto') != '' ? 
                    strval($this->generarimagen($this->input->post('foto_catalogo_producto'), $pais)) : '';
                if($foto_catalogo_producto == ''){
                    $data_insertar = array(
                        'Cat_descripcion'   => strval(mb_strtoupper($descripcion_cat)),
                        'Cat_Um_Id'         => intval($unidad_medida),
                        'Cat_Subf_Id'       => intval($subfamilia)
                    );
                }else{
                    $data_insertar = array(
                        'Cat_descripcion'   => strval(mb_strtoupper($descripcion_cat)),
                        'Cat_img'           => $foto_catalogo_producto,
                        'Cat_Um_Id'         => intval($unidad_medida),
                        'Cat_Subf_Id'       => intval($subfamilia)
                    );
                }   
                
                $veditar = $this->rec->editar_registro($data_insertar, array('Cat_Id' => strval($codigo_cat)), 
                    'tbl_catalogo_productos');

                if($estado_cat == 0){

                    $canales = implode(',', $canales_);
                    $rs = $this->rec->editar_registro( array('Catx_estado' => intval(0) ), array( 'Catx_Cat_Id' => strval($codigo_cat), 'Catx_Ca_Id in ('.$canales.')' => null), 'tbl_catalogopro_x_canal');
                    if( $rs ){
                        echo json_encode(array(
                                'rs'    => true,
                                'info'  => 'Se guardaron los cambios con exito',
                                'cla'   => 'successedit'
                            )
                        );
                    }else{
                        echo json_encode(array(
                                'rs'    => false,
                                'info'  => 'Ocurrió algo inesperado',
                                'cla'   => 'error'
                            )
                        );
                    }
                    return;

                }else{
                    // ------------------------------------------------------            
                    if(!empty($canales_)){
                        foreach($canales_ as $v){
                            $data_asignar = array(
                                'Catx_Cat_Id'   => strval($codigo_cat),
                                'Catx_Ca_Id'    => intval($v),
                                'Catx_estado'   => intval(1)
                            );

                            if(!$this->rec->verificar_regitro(array('Catx_Cat_Id' => $codigo_cat, 'Catx_Ca_Id' => $v),'tbl_catalogopro_x_canal'))
                            {
                                if($this->rec->agregar_registro('tbl_catalogopro_x_canal', $data_asignar))
                                    $vestado++;
                            }else{
                                if($this->rec->editar_registro(array('Catx_estado' => intval(1)), 
                                    array('Catx_Cat_Id' => strval($codigo_cat),'Catx_Ca_Id' => strval($v)), 
                                    'tbl_catalogopro_x_canal'))
                                    $vestado++;
                            }
                        }
                    }
                    // ------------------------------------------------------            
                    if(!empty($canales_inactivos)){
                        $data_asignar =  array(
                            'Catx_estado'   => 0
                        );
                        if($this->rec->editar_registro($data_asignar, array('Catx_Cat_Id' => strval($codigo_cat), 'Catx_Ca_Id in ('.$canales_inactivos.')' => null), 'tbl_catalogopro_x_canal')){
                            $vestado++;
                        }
                    }
                    // ------------------------------------------------------            
                }
                if($veditar == false && $vestado == 0){
                    echo json_encode(array(
                        'rs'            => true,
                        'info'          => 'No se realizaron cambios.',
                        'cla'           => 'sincambios',
                        'inactivos'     => $canales_inactivos,
                        'estado'        => $vestado
                        )
                    );
                }else if($veditar == true || $vestado > 0){
                    echo json_encode(array(
                        'rs'            => true,
                        'info'          => 'Se guardaron los cambios con exito',
                        'cla'           => 'successedit',
                        'inactivos'     => $canales_inactivos,
                        'estado'        => $vestado
                        )
                    );
                }
                return;
            }
        }
    }

    function exportar_registro_reclamo(){
        if($this->input->is_ajax_request()){

            $rec_Id = $this->input->post('codigo_rec');
            $data_ls_reclamos = $this->rec->obtener_registro_reclamo($rec_Id);

            if(!empty($data_ls_reclamos)){
                $fecha_actual = date('Y_m_d_his');
                $spreadsheet = new Spreadsheet();
                $spreadsheet->setActiveSheetIndex(0);
                $sheet = $spreadsheet->getActiveSheet();
                $tiponumero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
                $tipoletras = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

            // ---------- INFORMACION DEL RECLAMO ----------------------------
                $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('9ae5e5');
                $sheet->mergeCells('A1:D1');                      
                $sheet->getStyle('A1:D1')->getFont()->setBold( true );
                $sheet->getStyle('A2:A7')->getFont()->setBold( true );
                $sheet->getStyle('C2:C6')->getFont()->setBold( true );

                $sheet->getDefaultColumnDimension()->setWidth(30);
                $sheet->setCellValue('A1','DATOS GENERALES DEL RECLAMO');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('A2','CODIGO DE RECLAMO');
                $sheet->setCellValue('A3','PAIS');
                $sheet->setCellValue('A4','DISTRIBUIDORA');
                $sheet->setCellValue('A5','NOMBRE CLIENTE');
                $sheet->setCellValue('A6','RUTA');
                $sheet->setCellValueExplicit('B2',$data_ls_reclamos[0]->Rec_Id,$tipoletras);
                $sheet->setCellValue('B3',$data_ls_reclamos[0]->P_nombre);
                $sheet->setCellValue('B4',$data_ls_reclamos[0]->Dis_nombre);
                $sheet->setCellValue('B5',$data_ls_reclamos[0]->Cli_nombre);
                $sheet->setCellValue('B6',$data_ls_reclamos[0]->Ru_nombre);
                $sheet->setCellValue('C2','FECHA RECLAMO');
                $sheet->setCellValue('C3','DIVISION');
                $sheet->setCellValue('C4','CANAL');
                $sheet->setCellValue('C5','CODIGO CLIENTE');
                $sheet->setCellValue('C6','ESTADO RECLAMO');
                $sheet->setCellValue('D2',$data_ls_reclamos[0]->Rec_fecha_servidor);
                $sheet->setCellValue('D3',$data_ls_reclamos[0]->Di_nombre);
                $sheet->setCellValue('D4',$data_ls_reclamos[0]->Ca_nombre);
                $sheet->setCellValueExplicit('D5',$data_ls_reclamos[0]->Cli_Id,$tiponumero);
                $sheet->setCellValue('D6',$data_ls_reclamos[0]->Rec_estado);
                $sheet->setCellValue('A7', 'NOTA DE CIERRE');
                $sheet->mergeCells('B7:D7');
                $sheet->setCellValue('B7',$data_ls_reclamos[0]->Rec_comentario_cierre);
            // ---------------------------------------------------------------

            // ---------- DETALLES DEL PRODUCTO ------------------------------
                $sheet->getStyle('A9:J9')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('9ae5e5');
                $sheet->getStyle('A9:J9')->getFont()->setBold( true );
                $sheet->setCellValue('A9','CODIGO_PRODUCTO');
                $sheet->setCellValue('B9','PRODUCTO');
                $sheet->setCellValue('C9','TIPO_RECLAMO');
                $sheet->setCellValue('D9','UNIDADES_DANADAS');
                $sheet->setCellValue('E9','FAMILIA_PRODUCTO');
                $sheet->setCellValue('F9','SUBFAMILIA_PRODUCTO');
                $sheet->setCellValue('G9','CANTIDAD_A_ENTREGAR');
                $sheet->setCellValue('H9','FECHA_VENCIMIENTO');
                $sheet->setCellValue('I9','NUMERO_LOTE');
                $sheet->setCellValue('J9','OBSERVACION_VENTAS');               
            // ---------------------------------------------------------------

                $con = 10;
                    foreach ($data_ls_reclamos as $v) {  
                        $sheet->setCellValueExplicit('A'.$con,$v->Cat_Id,$tiponumero);
                        $sheet->setCellValue('B'.$con,$v->Cat_descripcion);
                        $sheet->setCellValue('C'.$con,mb_strtoupper($v->Tipd_descripcion));
                        $sheet->setCellValue('D'.$con,$v->Rec_unidades_danadas);
                        $sheet->setCellValue('E'.$con,$v->Fa_nombre);
                        $sheet->setCellValue('F'.$con,$v->Subf_nombre);
                        $sheet->setCellValue('G'.$con,$v->Rec_cantidad);
                        $sheet->setCellValue('H'.$con,$v->Rec_fecha_vencimiento);
                        $sheet->setCellValue('I'.$con,$v->Rec_numero_lote);
                        $sheet->setCellValue('J'.$con,$v->Rec_observacion_ventas);       
                        $con++;
                    }
               
                $nombre_archivo = '../Uploads/Plantilla_Excel/Detalle_reclamo_'.$fecha_actual.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

                echo json_encode(array(
                        'rs' => true,
                        'info' => 'Reclamo generados correctamente',
                        'cla' => 'success grSuccess',
                        'archivo' => $nombre_archivo
                    ));
                
                return;
                   
        }
            // $writer = new Xlsx($spreadsheet);
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="Reclamos_'.$fecha_actual.'.xlsx"');
            // ob_end_clean();
            // $writer->save('php://output');
        }
    }

    // -- 09/01/2021 ------------------------------------------------------------------------------------------
    public function rechazar_reclamo(){

        if($this->input->is_ajax_request()){
            $codigo_reclamo = $this->input->post('codigo_reclamo');
            $estado = $this->input->post('estado');
            $fecha = date('y-m-d H:i:s');
            $data = $this->rec->editar_registro(array('Rec_estado' => $estado, 'Rec_fecha_revision' => strval($fecha)), array('Rec_Id' => $codigo_reclamo), 'tbl_reclamo_pfn');

            echo json_encode($data);
            return; 

        }
    }
    // --------------------------------------------------------------------------------------------------------

    // -- 29/09/2021 ------------------------------------------------------------------------------------------
    public function canales_asignados_producto(){

        if($this->input->is_ajax_request()){
            $division = $this->input->post('division') != '' && $this->input->post('division') ?
                        $this->input->post('division') : $this->session->userdata('id_division');
            $producto = $this->input->post('id_producto');
            $canales_asignagos = $this->rec->lista_canal_asignar($division, $producto);
            echo json_encode($canales_asignagos);
            return; 
        }
    }
    // --------------------------------------------------------------------------------------------------------

    // -- 01/10/2021 ------------------------------------------------------------------------------------------
    public function ls_distribuidoras_canales(){
        if($this->input->is_ajax_request()){
            $codigo_division = $this->input->post('codigo');            
            $data = $this->rec->ls_distribuidoras_canales($codigo_division);
            echo json_encode($data);
            return;
        }
    }
    // --------------------------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------------------------

    public function guardar_reclamo_nuevo(){
        if($this->input->is_ajax_request()){
            $data = array();
            if (!empty($this->input->post('datas'))) {
                $data = $this->input->post('datas');
                $ruta_img           = $data['nombre_ruta'];
                $id_cliente         = $data['Id_Cliente'];
                $id_producto        = $data['Id_Catalogo_Producto'];
                $nombre_ruta        = str_replace('.', '', $data['nombre_ruta']);
                $nombre_division    = $data['nombre_ruta'];
                $token_reclamo      = $data['token_reclamo'];            
                $codigo_reclamo     = $data['codigo_reclamo'];  
                $input_fecha_actual = date('y-m-d H:i:s');
                $fecha_telefono     = $data['fecha_telefono'];
                $input_id_tipo_reclamo  = $data['tipo_reclamo'];
                $input_cantidad         = $data['cantidad'];            
                $input_un_danadas         = $data['unidades_danadas'];            
                $input_fecha_vencimiento = $data['fecha_vencimiento'] != '' ? strval(mb_strtoupper(quitar_acentos($data['fecha_vencimiento']))) : null;
                $input_foto_fecha_lote = $this->generarimagen($data['fileFechaLote'],$ruta_img);
                $input_foto_producto   = $this->generarimagen($data['fileProducto'],$ruta_img);
                $input_numero_lote = $data['numeroLote'] != '' ? strval(mb_strtoupper(quitar_acentos($data['numeroLote']))) : null;         
                $input_unidad_medida = 'UN';
                $proveedor  = empty($data['proveedor']) ? null : intval($data['proveedor']);
                $input_foto_sticker = $this->generarimagen($data['fileSticker'],$ruta_img);
                $input_foto_inconveniente   = $this->generarimagen($data['fileDano'],$ruta_img);
                if( !$this->rec->verificar_regitro(array('Rec_token' => $token_reclamo),'tbl_reclamo_pfn') ){
                    $data_insertar = array(
                        'Rec_Id'                 => strval($codigo_reclamo),
                        'Rec_Cli_Id'             => intval($id_cliente),
                        'Rec_Cat_Id'             => strval($id_producto),
                        'Rec_Tipd_Id'            => intval($input_id_tipo_reclamo),
                        'Rec_Pr_Id'              => $proveedor,
                        'Rec_cantidad'           => intval($input_cantidad),
                        'Rec_unidades_danadas'   => intval($input_un_danadas),
                        'Rec_unidad_medida'      => strval($input_unidad_medida),
                        'Rec_fecha_vencimiento'  => $input_fecha_vencimiento,
                        'Rec_numero_lote'        => $input_numero_lote,
                        'Rec_foto_fecha_lote'    => strval($input_foto_fecha_lote),
                        'Rec_foto_producto'      => strval($input_foto_producto),
                        'Rec_foto_sticker'       => strval($input_foto_sticker),
                        'Rec_foto_inconveniente' => strval($input_foto_inconveniente),
                        'Rec_fecha_telefono'     => $fecha_telefono,
                        'Rec_fecha_servidor'     => $input_fecha_actual,
                        // 'Rec_Usu_Id'             => intval(desencriptar_cadena($data['usuario'])),
                        'Rec_Usu_Id'             => intval($data['usuario']),
                        'Rec_token'              => strval($token_reclamo),
                        'Rec_tipo_usuario'       => intval($data['tipo_usuario']),
                        'Rec_estado'             => intval(1)
                    );
                    $respuesta = $this->rec->agregar_registro('tbl_reclamo_pfn', $data_insertar);
                    if($respuesta){
                        echo json_encode(array(
                            'rs' => TRUE,
                            'info' => ' El registro se guard&oacute; correctamente.',
                            'cla' => 'success grSuccess',
                            'data' => $this->input->post('datas')
                            )
                        );
                    }else{
                        echo json_encode(array(
                            'rs' => FALSE,
                            'info' => 'Ocurri&oacute; un error durante el proceso.',
                            'cla' => 'success grDanguer',
                            'errores' => 'Ocurri&oacute; un error durante el proceso.'
                            )
                        );
                    }
                }else {
                    echo json_encode(array(
                        'rs'    => TRUE,
                        'info'  => 'ITEM EN COLA YA EXISTE (No agregado)'
                    ));
                }
            }else{
                echo json_encode(array(
                    'rs'    => TRUE,
                    'ERROR' => 'data vacia',
                    'errores' => 'Ocurri&oacute; un error durante el proceso: datos incompletos.'
                ));
                return;
            }   
        }
    }
    // --------------------------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------------------------
    public function export_tabla_indexed_()
    {
        if($this->input->is_ajax_request()){
            $data = $this->input->post('data');
            $data_ = array();
            $data_v = array();
            $fecha_actual = date('Y_m_d_his');
            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('tbl_reclamos');

            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Another sheet');
            $tiponumero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
            $tipoletras = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

            // ----- ENCABEZADOS -----------------------------------
                $enc = 1;
                $indice = 0;
                $fila = 2;
                $sheet = $spreadsheet->getSheet(0);
                foreach ($data[0] as $key => $value) {  
                    $sheet->setCellValueByColumnAndRow($enc, 1, $key);                    
                    $enc ++;
                }
                
                $enc = 1; 
                foreach ($data as $key => $value) { 

                    $sheet->setCellValueExplicit('A'.$fila,$value['Id_Catalogo_Producto'],$tiponumero);
                    $sheet->setCellValue('B'.$fila,$value['Id_Cliente']);
                    $sheet->setCellValue('C'.$fila,$value['codigo_cliente']);
                    $sheet->setCellValue('D'.$fila,$value['cantidad']);
                    $sheet->setCellValue('E'.$fila,$value['codigo_reclamo']);
                    $sheet->setCellValue('F'.$fila,$value['descripcion_producto']);
                    $sheet->setCellValue('G'.$fila,$value['estado']);
                    $sheet->setCellValue('H'.$fila,$value['fecha_telefono']);
                    $sheet->setCellValue('I'.$fila,$value['fecha_vencimiento']);
                    $sheet->setCellValue('J'.$fila,$value['fileFechaLote']); 
                    $sheet->setCellValue('K'.$fila,$value['fileProducto']); 
                    $sheet->setCellValue('L'.$fila,$value['nombre_cliente']);
                    $sheet->setCellValue('M'.$fila,$value['nombre_ruta']);
                    $sheet->setCellValue('N'.$fila,$value['numeroLote']);
                    $sheet->setCellValue('O'.$fila,$value['pendiente']);
                    $sheet->setCellValue('P'.$fila,$value['tipo_reclamo']);
                    $sheet->setCellValue('Q'.$fila,$value['tipo_reclamo_descripcion']);
                    $sheet->setCellValue('R'.$fila,$value['tipo_usuario']);
                    $sheet->setCellValue('S'.$fila,$value['token_reclamo']);
                    $sheet->setCellValue('T'.$fila,$value['unidades_danadas']);
                    $sheet->setCellValue('U'.$fila,$value['usuario']);
                    /*foreach($value as $ind => $val){
                        $sheet->setCellValueByColumnAndRow($enc, $fila, $val);  
                        $enc++;                  
                    }
                    $enc = 1;*/
                    $fila++;
                }
                $col_ultima = $sheet->getHighestColumn();
                $sheet->getStyle('A1:'.$col_ultima.'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('9ae5e5');
                $sheet->getStyle('A1:'.$col_ultima.'1')->getFont()->setBold( true );
                $sheet->getDefaultColumnDimension()->setWidth(25);
            // -----------------------------------------------------
            
            $nombre_archivo = '../Uploads/Plantilla_Excel/tbl_reclamos_'.$fecha_actual.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

            echo json_encode(array(
                'rs' => true,
                'info' => 'Reclamos generados correctamente',
                'cla' => 'success grSuccess',
                'data' => $data,
                'archivo' => $nombre_archivo
            ));
            
            return;
        }
    }




public function get_reclamos_listado_inicial()
{
    header('Content-Type: application/json');

    $credenciales = json_decode(
        $this->input->post('arrg_Credls'),
        true
    );

    $data = $this->rec->get_reclamos_listado_inicial($credenciales);

    //$data = $credenciales;

    echo json_encode(array(
        'rs'    => true,
        'total' => count($data),
        'data'  => $data
    ));

    exit;
}


    // --------------------------------------------------------------------------------------------------------
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
}// --------------------------------------------------------------------------------------------------------


?>