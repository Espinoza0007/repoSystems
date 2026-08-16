<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/El_Salvador');
ini_set ('gd.jpeg_ignore_warning', 1);
require APPPATH . '/libraries/ControladorBase.php';
class Ctr_exhibidores extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_exhibidores/Mdl_exhibidores','exh');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }
	public function index(){
        $this->global['pageTitle'] = 'Actualizacion exhibidores';
        $this->loadViews('Clientes/V_exhibidores',$this->global);
    }

    
    public function GuardarStatusExh(){
        $fusiooonn_arrg     = array();
        $fecha_actual       = date('Y-m-d H:i:s');
        $status_exhibidores = $this->input->post("status_exhibidores");
        $ct_status_AC       = 0;
        $C_Token            = 0;
        $Cons_Token         = 0;
        $IdCliente          = 0;
        $Agregar_Exh        = array_filter( $status_exhibidores, function( $Agregar ) {
            return $Agregar['Ste_Accion']    == 'Agregar';
        });
        $Actuali_Exh        = array_filter( $status_exhibidores, function( $Actualizar ) {
            return $Actualizar['Ste_Accion'] == 'Actualizar';
        });
        $Agregar_Exh        = array_values($Agregar_Exh);
        $Actuali_Exh        = array_values($Actuali_Exh);
        if(!empty($Agregar_Exh)){
            $ct_status_AC   = count($Agregar_Exh);
        }
        if( $ct_status_AC > 0 ){
            for ($k=0; $k < $ct_status_AC; $k++) {
                $Cons_Token     = $this->exh->ContarTokenExh($Agregar_Exh[$k]['Ste_token']);
                $Cons_Token_s   = $this->exh->ContarTokenExhSin($Agregar_Exh[$k]['Ste_Cli_Id'],'7777777');
                $C_Token        = $Cons_Token->totaltoken;
                $C_Token_SinExh = $Cons_Token_s->totaltokensin;
                $IdCliente      = $Agregar_Exh[$k]['Ste_Cli_Id'];
                if( $C_Token > 0 )       {unset($Agregar_Exh[$k]);}
                if (isset($Agregar_Exh[$k])) {
                    if( $C_Token_SinExh > 0 && $Agregar_Exh[$k]['Ste_Cat_Id'] == '7777777' ){unset($Agregar_Exh[$k]);}
                }
            }
        }
        $Agregar_Exh = array_values($Agregar_Exh);
        // $IdCliente   = $this->input->post("IdCliente");
        if(!empty($Agregar_Exh)){
            $cnt_AG  = count($Agregar_Exh);
        }else{
            $cnt_AG  = 0;
        }
        if(!empty($Actuali_Exh)){
            $cnt_AC  = count($Actuali_Exh);
        }else{
            $cnt_AC  = 0;
        }
        $EstadoProceso = 0;$valiEstadoCli = 0;$contadorAgr = 0;
        /* Agregando los exhibidores nuevos */
        if( $cnt_AG > 0 ){
            $arrg_cambios = array();
            /*---- CONSTRUYENDO FOTOS EXHIBIDORES -----*/
            for ($f=0; $f < $cnt_AG; $f++) {
                $fotoBase64 = '';$fotoBase64Pano = '';$fotoBase64Despues = '';
                /* Foto antes */
                if (strcmp($Agregar_Exh[$f]['Ste_CambioAntes'], "SI") == 0) {
                    $fotoBase64 =  $Agregar_Exh[$f]['Ste_foto'];
                    $fotoBase64 = $this->generarimagen($fotoBase64, $Agregar_Exh[$f]['Ste_Usu_Id']);
                }
                /* Foto despues */
                if (strcmp($Agregar_Exh[$f]['Ste_CambioPano'], "SI") == 0) {
                    $fotoBase64Pano =  $Agregar_Exh[$f]['Ste_pano'];
                    $fotoBase64Pano = $this->generarimagen($fotoBase64Pano, $Agregar_Exh[$f]['Ste_Usu_Id']);
                }
                /* Foto panoramica */
                if (strcmp($Agregar_Exh[$f]['Ste_CambioDespues'], "SI") == 0) {
                    $fotoBase64Despues =  $Agregar_Exh[$f]['Ste_despues'];
                    $fotoBase64Despues = $this->generarimagen($fotoBase64Despues, $Agregar_Exh[$f]['Ste_Usu_Id']);
                }

                if(!empty($fotoBase64)){
                    $Agregar_Exh[$f]['Ste_foto'] = $fotoBase64;
                }else{
                    unset($Agregar_Exh[$f]['Ste_foto']);
                }

                if(!empty($fotoBase64Pano)){
                    $Agregar_Exh[$f]['Ste_pano'] = $fotoBase64Pano;
                }else{
                    unset($Agregar_Exh[$f]['Ste_pano']);
                }

                if(!empty($fotoBase64Despues)){
                    $Agregar_Exh[$f]['Ste_despues'] = $fotoBase64Despues;
                }else{
                    unset($Agregar_Exh[$f]['Ste_despues']);
                }


                if (isset($Agregar_Exh[$f]['Fecha_Sincronizacion'])) {
                    unset($Agregar_Exh[$f]['Fecha_Sincronizacion']);
                }

                
                $Agregar_Exh[$f]['Ste_fecha_serv'] = $fecha_actual;
                $Agregar_Exh[$f]['Ste_ultima_fecha'] = $fecha_actual;

                                
                unset($Agregar_Exh[$f]['Ste_token_espec'],$Agregar_Exh[$f]['Ste_cola'],$Agregar_Exh[$f]['Ste_Cat_Id_Descripcion'],$Agregar_Exh[$f]['Ste_CambioAntes'],$Agregar_Exh[$f]['Ste_CambioPano'],$Agregar_Exh[$f]['Ste_CambioDespues'],$Agregar_Exh[$f]['Ste_Accion'],$Agregar_Exh[$f]['Ste_guardado']);
                
                
                /*CAMBIOS 21/08/2021 - ESTADO CLIENTE*/
                if($Agregar_Exh[$f]['Ste_Cat_Id'] == '7777777'){
                    $valiEstadoCli = 0;
                }else{
                    $valiEstadoCli = 1;
                }
                $contadorAgr++;
            }
            /*---- AGREGANDO PARAMETROS EXTRA (EXH QUE TIENE) -----*/
            // for ($i=0; $i < $cnt_AG; $i++) { 
            //     $Agregar_Exh[$i] = array_replace($Agregar_Exh[$i],$arrg_cambios[$i]);
            // }
            // var_dump($Agregar_Exh);
            $Agregar_Data = $this->exh->Insertar_StatusExhibidores($Agregar_Exh);
            // $this->exh->Insertar_StatusExhibidores_BK_i($Agregar_Exh);
            if($cnt_AG == $contadorAgr){
                $EstadoProceso +=1;
            }else{
                $var_resuldata = json_encode($Agregar_Exh);
                echo json_encode(array(
                    'rs'      => FALSE,
                    'titulo' => '<h5>(Error - Ingreso de Exhibidores)</h5>',
                    'errores' => $var_resuldata,
                    'cla'     => 'success grDanguer'
                    )
                );
                // $this->exh->Insertar_StatusExhibidores_BK_i($Agregar_Exh);
                return;
            }
        }else{
            $EstadoProceso +=1;
        }
        
        // Actualizando exhibidores
        if( $cnt_AC > 0 ){

            $arrg_cambios = array();
            $fotoAntes64 = '';
            $fotoPano64 = '';
            $fotoDespues64 = '';

            /*---- CONSTRUYENDO FOTOS EXHIBIDORES FOTO ANTES  -----*/
            for ($f=0; $f < $cnt_AC; $f++) {
                $IdCliente      = $Actuali_Exh[$f]['Ste_Cli_Id'];

                $fotoBase64 = '';$fotoBase64Pano = '';$fotoBase64Despues = '';
                /* Foto antes */
                if (strcmp($Actuali_Exh[$f]['Ste_CambioAntes'], "SI") == 0) {
                    $fotoBase64 =  $Actuali_Exh[$f]['Ste_foto'];
                    $fotoBase64 = $this->generarimagen($fotoBase64, $Actuali_Exh[$f]['Ste_Usu_Id']);
                }
                /* Foto panoramica */
                if (strcmp($Actuali_Exh[$f]['Ste_CambioPano'], "SI") == 0) {
                    $fotoBase64Pano =  $Actuali_Exh[$f]['Ste_pano'];
                    $fotoBase64Pano = $this->generarimagen($fotoBase64Pano, $Actuali_Exh[$f]['Ste_Usu_Id']);
                }
                /* Foto despues */
                if (strcmp($Actuali_Exh[$f]['Ste_CambioDespues'], "SI") == 0) {
                    $fotoBase64Despues =  $Actuali_Exh[$f]['Ste_despues'];
                    $fotoBase64Despues = $this->generarimagen($fotoBase64Despues, $Actuali_Exh[$f]['Ste_Usu_Id']);
                }

                if(!empty($fotoBase64)){
                    $Actuali_Exh[$f]['Ste_foto'] = $fotoBase64;
                }else{
                    unset($Actuali_Exh[$f]['Ste_foto']);
                }

                if(!empty($fotoBase64Pano)){
                    $Actuali_Exh[$f]['Ste_pano'] = $fotoBase64Pano;
                }else{
                    unset($Actuali_Exh[$f]['Ste_pano']);
                }
                
                if(!empty($fotoBase64Despues)){
                    $Actuali_Exh[$f]['Ste_despues'] = $fotoBase64Despues;
                }else{
                    unset($Actuali_Exh[$f]['Ste_despues']);
                }

                if (isset($Actuali_Exh[$f]['Fecha_Sincronizacion'])) {
                    unset($Actuali_Exh[$f]['Fecha_Sincronizacion']);
                }
                unset($Actuali_Exh[$f]['Ste_token_espec'],$Actuali_Exh[$f]['Ste_cola'],$Actuali_Exh[$f]['Ste_Cat_Id_Descripcion'],$Actuali_Exh[$f]['Ste_CambioAntes'],$Actuali_Exh[$f]['Ste_CambioPano'],$Actuali_Exh[$f]['Ste_CambioDespues'],$Actuali_Exh[$f]['Ste_Accion'],$Actuali_Exh[$f]['Ste_guardado']);
                
                $Actuali_Exh[$f]['Ste_ultima_fecha'] = $fecha_actual;
                /*CAMBIOS 21/08/2021 - ESTADO CLIENTE*/
                if($Actuali_Exh[$f]['Ste_Cat_Id'] == '7777777'){
                    $valiEstadoCli = 0;
                }else{
                    $valiEstadoCli = 1;
                }

            }

            $Actualizar_Data = $this->exh->Modificar_StatusExhibidores($Actuali_Exh);
            // $this->exh->Insertar_StatusExhibidores_BK_a($Actuali_Exh);
            if($cnt_AC == $Actualizar_Data){
                $EstadoProceso +=1;
            }else{
                $var_resuldata = json_encode($Actuali_Exh);
                echo json_encode(array(
                    'rs'      => FALSE,
                    'titulo' => '<h5>(Error - Actualización de Exhibidores)</h5>',
                    'errores' => $var_resuldata,
                    'cla'     => 'success grDanguer'
                    )
                );
                // $this->exh->Insertar_StatusExhibidores_BK_a($Actuali_Exh);
                return;
            }


        } else{
            $EstadoProceso +=1;
        }
        if($EstadoProceso == 2){

            if($valiEstadoCli == 1){
                $this->exh->quitar_sin_exhibidor($IdCliente);
            }
            $this->exh->bloqCliActuExh(
                array(
                    'Cli_ac_exhibidor' => 1,
                    'Cli_bloq_exh' => 1,
                    'Cli_ul_fecha_ac_exhibidor' => $fecha_actual,
                    'Cli_estado_csexh' => $valiEstadoCli
                ),$IdCliente
            );
            echo json_encode(array(
                'rs' => TRUE,
                'info' => 'Registros Guardados Exitosamente.',
                'cla' => 'success grSuccess',
                // 'Agregar' => $Agregar_Exh,
                // 'Actualizar' => $Actuali_Exh,
                // 'fecha_actual' => $fecha_actual,
                'IdCliente' => $IdCliente
                )
            );
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'errores' => 'Error Proceso Incompleto...',
                'cla' => 'success grDanguer'
                )
            );
        }
        
        return;
    }
    /*<<<<<<<<<<<<<<------- GERNERAR FOTOS EXHIBIDORES ----------- >>>>>>>>>>>>>>>>>>>>*/


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
            $carpeta = "../Uploads/img_server/Fotos_Exhibidores/".$runanombre;

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
            $file = str_replace("../Fotos_Exhibidores/","",$file);
            return "../".$file;
        }
    }

}