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

class Ctr_mercado extends ControladorBase
{
    function __construct(){
        parent::__construct();
        $this->load->model('M_mercado/Mdl_mercado','mer');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }
    function index(){
        $this->global['pageTitle'] = 'Tareas';
        $this->global['user']  =  $this->session->userdata('us_ID_Ruta');
        $this->loadViews('Mercado/V_mercado', $this->global);
    }
    public function get_tareas() {
        $user = $this->input->get('usuario');
        // $id = $this->session->userdata('us_ID_Ruta');
        $tareas = $this->mer->get_tareas($user);
        echo json_encode($tareas);
        
    }

    public function list_oportunidades(){
        $oportunidades = $this->mer->get_oportunidades();
        echo json_encode($tareas);
    }

    public function completeTask() {
        $fechaSeguimiento = $this->input->post('fechaSeguimiento');
        $Ruta_upload_u = null;
        $Ruta_upload_d = null;
        $Ruta_upload_t = null;
        $data = array();
    
        // Manera segura de obtener los datos de la imagen, ya que puede que no estén presentes
        $foto_u = $this->input->post('foto_u') ?? null;
        $foto_d = $this->input->post('foto_d') ?? null;
        $foto_t = $this->input->post('foto_t') ?? null;
    
        // Función para procesar la imagen y devolver la ruta
        function procesarImagen($foto, $fechaSeguimiento) {
            $nombreAleatorio = uniqid('', true);
            $ruta = "../Uploads/Mercadeo/" . $fechaSeguimiento . "_" . str_replace(" ", "_", $nombreAleatorio) . ".png";
            $Img_base64 = base64_decode($foto);
            file_put_contents($ruta, $Img_base64);
            return "../" . $ruta;
        }
    
        if ($foto_u !== null) {
            $data['foto_u'] = procesarImagen($foto_u, $fechaSeguimiento);
        }
        if ($foto_d !== null) {
            $data['foto_d'] = procesarImagen($foto_d, $fechaSeguimiento);
        }
        if ($foto_t !== null) {
            $data['foto_t'] = procesarImagen($foto_t, $fechaSeguimiento);
        }
    
        $taskId = $this->input->post('tarea_id');  // Obtén el 'id' de la tarea de acuerdo al nuevo esquema de datos
        var_dump($data);
        try {
            $this->mer->guardarFotos($taskId, $data);
           
            echo json_encode(['success' => true, 'message' => 'La tarea se ha completado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Hubo un error al completar la tarea: ' . $e->getMessage()]);
        }
    }

    public function guardar_formulario() {
        //Variables
        $variables = ['yummies', 'fritolays', 'diana', 'barcel', 'senorial', 'ricaSula', 'tropical', 'musa', 
            'bocadeli', 'nutriva', 'pindi', 
            'bocadeliExhibidorPrincipal', 'bocadeliExhibidorAdicional', 'bocadeliExhibicionAdecuada', 
            'bocadeliPosicionDominante', 'bocadeliPop', 
            'nutrivaExhibidorPrincipal', 'nutrivaExhibidorAdicional', 'nutrivaExhibicionAdecuada', 
            'galletaExhibidorPrincipal', 'galletaExhibidorAdicional', 'galletaExhibicionAdecuada'];
        // Generate post data
        $postData = [];
        foreach($variables as $var) {
            $postData[$var] = $this->input->post($var);
        }
        // Concatenation
        $opciones = implode(",", array_filter($postData, function($value) { return $value != 0; }));
        // Validation
        $this->form_validation->set_rules('frecuenciaVisita', 'Frecuencia de Visita', 'required|in_list[1,2,3]');
        if ($this->form_validation->run() === FALSE) {
           // $this->load->view('/Mercado/Index');
          //  return;
        }
        // Images processing
        $imageNames = ['foto_uno', 'foto_dos', 'foto_tres'];
        $imagePaths = [];
        foreach($imageNames as $name) {
            $nombreAleatorio = uniqid('', true);
            $path = "../Uploads/Mercadeo/" . $this->input->post('fechaSeguimiento') . "_" . str_replace(" ", "_", $nombreAleatorio) . ".png";
            $Img_base64 = base64_decode($this->input->post($name));
            file_put_contents($path, $Img_base64);
            $imagePaths[$name] = $path;
        }
        // Data
        $data = array(    
            'pais' => $this->input->post('countries'),
            'division' => $this->input->post('divisions'),
            'ruta' => $this->input->post('ruta'),
            'distribuidora' => $this->input->post('distribuidora'),
            'nombreVendedor' => $this->input->post('nombreuser'),
            'sector' => $this->input->post('sector'),
            'codigoCliente' => $this->input->post('search-box6'),
            'nombreEstablecimiento' => $this->input->post('nombreEstablecimiento'),
            'direccion' => $this->input->post('direccion'),
            'tipoNegocio' => $this->input->post('tipoNegocio'),
            'lista_opcion' => $opciones,
            'frecuencia_visita' => $this->input->post('frecuenciaVisita'),
            'tipo_compra' => implode(",", array($this->input->post('dianaCompra'), $this->input->post('fritoLayCompra'), $this->input->post('yummiesCompra'))),
            'fecha' => $this->input->post('fechaSeguimiento'),
            'n_oportunidad'=> $this->input->post('listaOportunidades'),
            'oportunidad' => $this->input->post('oportunidades'),
            'compra_semanal' => (float) $this->input->post('compraSemanal'),
            'otra_competencia' => $this->input->post('otro'),
            'latitud' => $this->input->post('latitud'),
            'longitud' => $this->input->post('longitud'),
            'id_creado'=> $this->input->post('id_creado'),
            'foto_uno' => "../" . $imagePaths['foto_uno'] ,
            'foto_dos' => "../" . $imagePaths['foto_dos'] ,
            'foto_tres' => "../" . $imagePaths['foto_tres'] ,
            'hash'      => $this->input->post('hash')
            
        );
  
        // Insert
        $success = $this->mer->insertar_formulario($data);  
        if ($success) {
            // Envía un mensaje de éxito.
            $response = ['success' => 'La tarea ha sido asignada correctamente.'];
        } else {
            $response = ['error' => 'Hubo un problema al asignar la tarea.'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function	crear_tarea(){
    
        $data = array(	
            'id_evaluacion' => $this->input->post('id'),
            'estado' => 0,
            'comentario' => $this->input->post('comentario'),
            'fecha' => $this->input->post('fechaSeguimiento'),
            'creado_por'=> $this->session->userdata('Usu_id'),
            'tipo_tarea' => $this->input->post('tipo_tarea'),
            'asignado_a'      => $this->input->post('asignado_a'),
            'ruta_asignada'      => $this->input->post('asignado_a'),
            'estado'      => 1,
            'hash'      => $this->input->post('hash')

            
        );
        $this->mer->insertar_tareas($data);
        echo json_encode(array("status" => TRUE));   
    }

    public function	crear_tarea_s(){
    
        $data = array(	
            'id_evaluacion' => $this->input->post('id'),
            'estado' => $this->session->userdata('estado'),
            'fecha' => $this->input->post('fechaSeguimiento'),
            'creado_por'=> $this->session->userdata('Usu_id'),
            'tipo_tarea' => $this->input->post('tipo_tarea'),
            'hash'      => $this->input->post('hash'),
            'comentario' => $this->input->post('comentario'),
            'asignado_a' => $this->input->post('asignado_a'),
            'ruta_asignada' => $this->input->post('ruta_asignada'),
            
        );
        $this->mer->insertar_tareas($data);
        echo json_encode(array("status" => TRUE));   
    }

    public function getTareas()
    {
		$userId = $this->input->get('userId');
        $tareas = $this->mer->tareas($userId);
        echo json_encode($tareas);
    }
    //Aasignar tareas 
    public function asignar() {
        // Crear un array con todos los datos
        $data = [
            'id' => $this->input->post('filaId'),
            'ruta' => $this->input->post('search-box5'),
            'comentario' => $this->input->post('comentario'),
            'asignado_a' => $this->input->post('search-box5'),
            'estado' => "1",
        ];
    
        // Enviar el array como un solo parámetro
        $success = $this->mer->asignarTarea($data);
        
        if ($success) {
            // Envía un mensaje de éxito.
            echo json_encode(['success' => 'La tarea ha sido asignada correctamente.']);
        } else {
            echo json_encode(['error' => 'Hubo un problema al asignar la tarea.']);
        }
    }
    
    
    public function sincronizarTareas(){

        $fechaSeguimiento = $this->input->post('fechaSeguimiento');
        $Ruta_upload_u = null;
        $Ruta_upload_d = null;
        $Ruta_upload_t = null;
        $data = array();
        $data = array(	
            'id_evaluacion' => $this->input->post('id'),
            'estado' => $this->input->post('estado'),
            'fecha' => $this->input->post('fecha'),
            'creado_por' => $this->input->post('creado_por'),
            'ruta_asignada' => $this->input->post('ruta'),
            'asignado_a' => $this->input->post('asignado_a'),
            'comentario' => $this->input->post('comentario'),
            'tipo_tarea' => $this->input->post('tipo_tarea'),
            'hash'      => $this->input->post('hash')
        );
        // Manera segura de obtener los datos de la imagen, ya que puede que no estén presentes
        $foto_u = $this->input->post('foto_u') ?? null;
        $foto_d = $this->input->post('foto_d') ?? null;
        $foto_t = $this->input->post('foto_t') ?? null;
    
        // Función para procesar la imagen y devolver la ruta
        function procesarImagen($foto, $fechaSeguimiento) {
            $nombreAleatorio = uniqid('', true);
            $ruta = "../Uploads/Mercadeo/" . $fechaSeguimiento . "_" . str_replace(" ", "_", $nombreAleatorio) . ".png";
            $Img_base64 = base64_decode($foto);
            file_put_contents($ruta, $Img_base64);
            return "../" . $ruta;
        }
    
        if ($foto_u !== null) {
            $data['foto_u'] = procesarImagen($foto_u, $fechaSeguimiento);
        }
        if ($foto_d !== null) {
            $data['foto_d'] = procesarImagen($foto_d, $fechaSeguimiento);
        }
        if ($foto_t !== null) {
            $data['foto_t'] = procesarImagen($foto_t, $fechaSeguimiento);
        }
    
        $taskId = $this->input->post('tarea_id');  // Obtén el 'id' de la tarea de acuerdo al nuevo esquema de datos
        var_dump($data);
        try {
            $this->mer->actuTareas($taskId, $data);
           
            echo json_encode(['success' => true, 'message' => 'La tarea se ha completado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Hubo un error al completar la tarea: ' . $e->getMessage()]);
        }

    }

    // public function imagen() {

    //     $dataClientes = json_decode(file_get_contents('https://kf.kobotoolbox.org/assets/a7BCaGa8LPjA2LvbkgCKNw/submissions/?format=json'), true);
        
    //     $carpetaDestino = '../Uploads/img_server/sss/';
    //     $startDownloading = false;
    
    //     foreach ($dataClientes as $key) {
            
    //         // Obtén el nombre de archivo de la URL
    //         $nombreArchivo = $key['Fotograf_a_de_la_fachada_del_negocio'];
            
    //         // Comprueba si es la imagen a partir de la cual se deben descargar las siguientes
    //         if ($nombreArchivo === '1685217848672.jpg') {
    //             $startDownloading = true;
    //         }
            
    //         // Si no es el momento de comenzar a descargar, continúa con la siguiente iteración
    //         if (!$startDownloading) {
    //             continue;
    //         }
    
    //         $urlFoto = $key['_attachments'][0]['download_url'];
    //         echo $urlFoto . '<br>';
    
    //         // Ruta completa del archivo local
    //         $rutaCompleta = $carpetaDestino . $nombreArchivo;
    //         echo "$nombreArchivo <br>";
            
    //         // Descarga la imagen desde la URL
    //         $imagenDescargada = file_get_contents($urlFoto);
            
    //         if ($imagenDescargada !== false) {
    //             // Guarda la imagen en la carpeta de destino
    //             if (file_put_contents($rutaCompleta, $imagenDescargada) !== false) {
    //                 echo 'La imagen se ha descargado y guardado con éxito en: ' . $rutaCompleta . '<br>';
    //             } else {
    //                 echo 'Error al guardar la imagen en la carpeta de destino.<br>';
    //             }
    //         } else {
    //             echo 'Error al obtener el contenido de la imagen desde la URL.<br>';
    //         }
    //     }
    // }
            
}
?>