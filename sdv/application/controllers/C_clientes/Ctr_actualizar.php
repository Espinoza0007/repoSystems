<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
date_default_timezone_set('America/El_Salvador');
class Ctr_actualizar extends ControladorBase{
	function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','security'));
    }
	function index(){
        $this->global['pageTitle'] = 'Actualización de datos';
        $this->loadViews('Clientes/V_actualizar',$this->global);
    }
   /* function actualizar(){
        if($this->input->is_ajax_request()){
            $Id_Cliente = $this->input->post('Id_Cliente');
            $Codigo = mb_strtoupper(quitar_acentos($this->input->post('Codigo_Cli')));
            $input_nombre = mb_strtoupper(quitar_acentos($this->input->post('txtnombre')));
            $input_direccion = mb_strtoupper(quitar_acentos($this->input->post('txtdireccion')));
            $input_contacto = mb_strtoupper(quitar_acentos($this->input->post('txtcontacto')));
            $input_telefono = mb_strtoupper(quitar_acentos($this->input->post('txtnumtelefono')));
            $input_observaciones = mb_strtoupper(quitar_acentos($this->input->post('txtobservacion')));
            $input_dui = $this->input->post('txtdui');
            $input_nrc = $this->input->post('txtnumcontribuyente');
            $input_nit = $this->input->post('txtnit');
            $input_correo = $this->input->post('txtcorreo');
            $rd_personeria = $this->input->post('rdpersoneria');
            $rd_contribuyente = $this->input->post('rdcontribuyente');
            $foto_dui = $this->generarimagen($this->input->post('fotodui'),$Id_Cliente);
            $foto_duitrasera = $this->generarimagen($this->input->post('fotoduitrasera'),$Id_Cliente);
            $foto_nrc = $this->generarimagen($this->input->post('fotonrc'),$Id_Cliente);
            $foto_nrctrasera = $this->generarimagen($this->input->post('fotonrctrasera'),$Id_Cliente);
            $foto_nit = $this->generarimagen($this->input->post('fotonit'),$Id_Cliente);
            $input_nombreC = mb_strtoupper(quitar_acentos($this->input->post('txtnombreC')));
            $input_telefonoC = $this->input->post('txtnumtelefonoC');
            $input_duiC = $this->input->post('txtduiC');
            $input_nombreR = mb_strtoupper(quitar_acentos($this->input->post('txtnombreR')));
            $input_telefonoR = $this->input->post('txtnumtelefonoR');
            $input_duiR = $this->input->post('txtduiR');
            $input_latitud = 0;
            $input_longitud = 0;
            $Usu_Id      = $this->input->post('Usu_Id') == null || $this->input->post('Usu_Id') == '' ? 0 : $this->input->post('Usu_Id');
            $fecha_actual = date('Y-m-d H:i:s');
            $fechaEnDispositivo = $this->input->post('fechaEnDispositivo');
            $Id_Cliente = intval($Id_Cliente);
            $insertar = array(
                'Actc_Cli_Id' => $Id_Cliente,
                'Actc_codigo' => strval($Codigo),
                'Actc_nombre' => strval($input_nombre),
                'Actc_direccion' => strval($input_direccion),
                'Actc_Mun_Id' => 263,
                'Actc_Gir_Id' => 22,
                'Actc_telefono' => strval($input_telefono),
                'Actc_contacto' => strval($input_contacto),
                'Actc_dui' => strval($input_dui),
                'Actc_num_registro' => strval($input_nrc),
                'Actc_nit' => strval($input_nit),
                'Actc_l' => 0,
                'Actc_m' => 0,
                'Actc_mi' => 0,
                'Actc_j' => 0,
                'Actc_v' => 0,
                'Actc_s' => 0,
                'Actc_d' => 0,
                'Actc_orden_l' => 0,
                'Actc_orden_m' => 0,
                'Actc_orden_mi' => 0,
                'Actc_orden_j' => 0,
                'Actc_orden_v' => 0,
                'Actc_orden_s' => 0,
                'Actc_orden_d' => 0,
                'Actc_frecuencia_visita' => 'NA',
                'Actc_correo' => strval($input_correo),
                'Actc_Pers_Id' => intval($rd_personeria),
                'Actc_ctr_iva' => intval($rd_contribuyente),
                'Actc_foto_dui_frontal' => strval($foto_dui),
                'Actc_foto_dui_trasera' => strval($foto_duitrasera),
                'Actc_foto_nit_frontal' => strval($foto_nit),
                'Actc_foto_nrc_frontal' => strval($foto_nrc),
                'Actc_foto_nrc_trasera' => strval($foto_nrctrasera),
                'Actc_nombre_contacto' => strval($input_nombreC),
                'Actc_telefono_contacto' => strval($input_telefonoC),
                'Actc_dui_contacto' => strval($input_duiC),
                'Actc_nombre_representante' => strval($input_nombreR),
                'Actc_telefono_representante' => strval($input_telefonoR),
                'Actc_dui_representante' => strval($input_duiR),
                'Actc_latitud' => strval($input_latitud),
                'Actc_longitud' => strval($input_longitud),
                'Actc_fecha_telefono' => $fechaEnDispositivo,
                'Actc_fecha_servidor' => $fecha_actual,
                'Actc_estado_supervisor' => 'N',
                'Actc_estado_analista' => 'N',
                'Actc_estado_descarga' => 0,
                'Actc_usuario' => intval($Usu_Id),
                'Actc_observaciones' => strval($input_observaciones),
                'Actc_estado_registro' => 1
            );
            $inserdata = $this->cl->guardar_actualizacionCLI($insertar);
            if($inserdata){
                echo json_encode(array(
                    'rs' => TRUE,
                    'info' => ' El registro se realiz&oacute; correctamente.',
                    'cla' => 'success grSuccess'
                    )
                );
            }else{
                echo json_encode(array(
                    'rs' => FALSE,
                    'info' => 'Ocurrio un error durante el proceso.'.json_encode($insertar),
                    'cla' => 'success grDanguer'
                    )
                );
            }            
        }else{
            $resp = array(
                'rs' => FALSE,
                'errores' => validation_errors('<li>','</li>'),
                'cla' => 'danger grDanguer'
            );
            echo json_encode($resp);
            return;
        }
    }*/
    
    
    
public function actualizar()
{
    if (!$this->input->is_ajax_request()) {
        echo json_encode([
            'rs'  => FALSE,
            'errores' => validation_errors('<li>','</li>'),
            'cla' => 'danger grDanguer'
        ]);
        return;
    }

    // ====== CAMPOS BASE ======
    $Id_Cliente      = $this->input->post('Id_Cliente');
    $Codigo          = mb_strtoupper(quitar_acentos($this->input->post('Codigo_Cli')));
    $actc_ruta       = $this->input->post('Actc_Ruta');

    $input_nombre        = mb_strtoupper(quitar_acentos($this->input->post('txtnombre')));
    $input_direccion     = mb_strtoupper(quitar_acentos($this->input->post('txtdireccion')));
    $input_contacto      = mb_strtoupper(quitar_acentos($this->input->post('txtcontacto')));
    $input_telefono      = mb_strtoupper(quitar_acentos($this->input->post('txtnumtelefono')));
    $input_observaciones = mb_strtoupper(quitar_acentos($this->input->post('txtobservacion')));
    $input_dui           = $this->input->post('txtdui');
    $input_nrc           = $this->input->post('txtnumcontribuyente');
    $input_nit           = $this->input->post('txtnit');
    $input_correo        = $this->input->post('txtcorreo');
    $rd_personeria       = $this->input->post('rdpersoneria');
    $rd_contribuyente    = $this->input->post('rdcontribuyente');

    // Fotos (base64 -> archivo, según tu helper)
    $foto_dui        = $this->generarimagen($this->input->post('fotodui'),        $Id_Cliente);
    $foto_duitrasera = $this->generarimagen($this->input->post('fotoduitrasera'), $Id_Cliente);
    $foto_nrc        = $this->generarimagen($this->input->post('fotonrc'),        $Id_Cliente);
    $foto_nrctrasera = $this->generarimagen($this->input->post('fotonrctrasera'), $Id_Cliente);
    $foto_nit        = $this->generarimagen($this->input->post('fotonit'),        $Id_Cliente);

    // Contacto receptor
    $input_nombreC   = mb_strtoupper(quitar_acentos($this->input->post('txtnombreC')));
    $input_telefonoC = $this->input->post('txtnumtelefonoC');
    $input_duiC      = $this->input->post('txtduiC');

    // Representante
    $input_nombreR   = mb_strtoupper(quitar_acentos($this->input->post('txtnombreR')));
    $input_telefonoR = $this->input->post('txtnumtelefonoR');
    $input_duiR      = $this->input->post('txtduiR');

    // ====== COORDENADAS ======
    $latPost = $this->input->post('txtlatitudAC');
    $lngPost = $this->input->post('txtlongitudAC');
    if ($latPost === null || $latPost === '') { $latPost = $this->input->post('txtlatitud'); }
    if ($lngPost === null || $lngPost === '') { $lngPost = $this->input->post('txtlongitud'); }

    $latPost = str_replace(',', '.', trim((string)$latPost));
    $lngPost = str_replace(',', '.', trim((string)$lngPost));
    $input_latitud  = (is_numeric($latPost)) ? number_format((float)$latPost, 6, '.', '') : 0;
    $input_longitud = (is_numeric($lngPost)) ? number_format((float)$lngPost, 6, '.', '') : 0;

    log_message('debug', 'ACTUALIZAR_COORD -> lat: '.$input_latitud.' lng: '.$input_longitud.' (raw: '.$latPost.', '.$lngPost.') Cliente: '.$Id_Cliente);

    $Usu_Id             = ($this->input->post('Usu_Id') == null || $this->input->post('Usu_Id') == '') ? 0 : $this->input->post('Usu_Id');
    $fecha_actual       = date('Y-m-d H:i:s');
    $fechaEnDispositivo = $this->input->post('fechaEnDispositivo');
    $Id_Cliente         = intval($Id_Cliente);

    // ====== FRECUENCIA / DÍAS / ÓRDENES (recuperados del push del front) ======
    $actc_frec = (string)$this->input->post('Actc_frecuencia_visita'); // "", "1,2,3,4,5", "1,3,5", etc.

    // Helpers locales
    $getFlag = function($key){
        $v = $this->input->post($key);
        return ($v === '1' || $v === 1) ? 1 : 0;
    };
    $getOrder = function($key){
        $v = trim((string)$this->input->post($key));
        if ($v === '' || !is_numeric($v)) return 0;
        $n = (int)$v;
        return ($n < 0) ? 0 : $n;
    };

    // Flags 0/1 enviados por el front
    $actc_l  = $getFlag('Actc_l');
    $actc_m  = $getFlag('Actc_m');
    $actc_mi = $getFlag('Actc_mi');
    $actc_j  = $getFlag('Actc_j');
    $actc_v  = $getFlag('Actc_v');
    $actc_s  = $getFlag('Actc_s');
    $actc_d  = $getFlag('Actc_d');

    // Órdenes (si el día no está marcado, forzamos 0)
    $actc_orden_l  = $actc_l  ? $getOrder('Actc_orden_l')  : 0;
    $actc_orden_m  = $actc_m  ? $getOrder('Actc_orden_m')  : 0;
    $actc_orden_mi = $actc_mi ? $getOrder('Actc_orden_mi') : 0;
    $actc_orden_j  = $actc_j  ? $getOrder('Actc_orden_j')  : 0;
    $actc_orden_v  = $actc_v  ? $getOrder('Actc_orden_v')  : 0;
    $actc_orden_s  = $actc_s  ? $getOrder('Actc_orden_s')  : 0;
    $actc_orden_d  = $actc_d  ? $getOrder('Actc_orden_d')  : 0;

    // ====== ARRAY PARA GUARDAR ======
    $insertar = array(
        'Actc_Cli_Id'            => $Id_Cliente,
        'Actc_codigo'            => (string)$Codigo,
        'Actc_Ruta'              => (string)$actc_ruta,

        'Actc_nombre'            => (string)$input_nombre,
        'Actc_direccion'         => (string)$input_direccion,
        'Actc_Mun_Id'            => 263, // cambia si lo tomas del form
        'Actc_Gir_Id'            => 22,  // cambia si lo tomas del form
        'Actc_telefono'          => (string)$input_telefono,
        'Actc_contacto'          => (string)$input_contacto,
        'Actc_dui'               => (string)$input_dui,
        'Actc_num_registro'      => (string)$input_nrc,
        'Actc_nit'               => (string)$input_nit,

        // Días y órdenes (ya no fijos)
        'Actc_l'                 => $actc_l,
        'Actc_m'                 => $actc_m,
        'Actc_mi'                => $actc_mi,
        'Actc_j'                 => $actc_j,
        'Actc_v'                 => $actc_v,
        'Actc_s'                 => $actc_s,
        'Actc_d'                 => $actc_d,

        'Actc_orden_l'           => $actc_orden_l,
        'Actc_orden_m'           => $actc_orden_m,
        'Actc_orden_mi'          => $actc_orden_mi,
        'Actc_orden_j'           => $actc_orden_j,
        'Actc_orden_v'           => $actc_orden_v,
        'Actc_orden_s'           => $actc_orden_s,
        'Actc_orden_d'           => $actc_orden_d,

        // Frecuencia seleccionada en el front
        'Actc_frecuencia_visita' => (string)$actc_frec,

        'Actc_correo'            => (string)$input_correo,
        'Actc_Pers_Id'           => (int)$rd_personeria,
        'Actc_ctr_iva'           => (int)$rd_contribuyente,

        'Actc_foto_dui_frontal'  => (string)$foto_dui,
        'Actc_foto_dui_trasera'  => (string)$foto_duitrasera,
        'Actc_foto_nit_frontal'  => (string)$foto_nit,
        'Actc_foto_nrc_frontal'  => (string)$foto_nrc,
        'Actc_foto_nrc_trasera'  => (string)$foto_nrctrasera,

        'Actc_nombre_contacto'   => (string)$input_nombreC,
        'Actc_telefono_contacto' => (string)$input_telefonoC,
        'Actc_dui_contacto'      => (string)$input_duiC,

        'Actc_nombre_representante'   => (string)$input_nombreR,
        'Actc_telefono_representante' => (string)$input_telefonoR,
        'Actc_dui_representante'      => (string)$input_duiR,

        'Actc_latitud'           => (string)$input_latitud,
        'Actc_longitud'          => (string)$input_longitud,
        'Actc_fecha_telefono'    => $fechaEnDispositivo,
        'Actc_fecha_servidor'    => $fecha_actual,

        'Actc_estado_supervisor' => 'N',
        'Actc_estado_analista'   => 'N',
        'Actc_estado_descarga'   => 0,
        'Actc_usuario'           => (int)$Usu_Id,
        'Actc_observaciones'     => (string)$input_observaciones,
        'Actc_estado_registro'   => 1
    );

    // Guardar
    $inserdata = $this->cl->guardar_actualizacionCLI($insertar);

    if ($inserdata) {
        echo json_encode([
            'rs'   => TRUE,
            'info' => 'El registro se realizó correctamente.',
            'cla'  => 'success grSuccess',
            'lat'  => $input_latitud,
            'lng'  => $input_longitud
        ]);
    } else {
        echo json_encode([
            'rs'   => FALSE,
            'info' => 'Ocurrió un error durante el proceso.',
            'cla'  => 'danger grDanguer'
        ]);
    }
    return;
}

    
    
        public function generarimagen($img,$ruta){
        //generateImage($_POST['image']);
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
            $carpeta = "../Uploads/img_server/DocumentosLegales/".$runanombre;
            // $carpeta = "/var/www/html/Uploads/img_server/clte_n/".$runanombre;
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
            // $file = str_replace("/var/www/html/Uploads/img_server/","",$file);
            return $file;
        }
    }
}
?>