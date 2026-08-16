<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
class Ctr_login extends ControladorBase{
	function __construct(){
        parent::__construct();
        $this->load->model('M_login/Mdl_login','lg');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }
	public function index(){
        $this->global['pageTitle'] = 'Iniciar sesi&oacute;n';
        $this->loadViews_login('Usuarios/V_login',$this->global);
  	}
  	public function iniciar_sesion(){
	  	if($this->input->is_ajax_request()){
            $usuario             = strip_tags($this->input->post('usuario'));
            $contrasena          = $this->input->post('contrasena');
            $regex_usuario       = "/^[a-zA-Z0-9]{1,15}+$/";
            $regex_contrasena    = "/^[a-zA-Z0-9]{1,15}+$/";
            $arrg_validacion     = array();
            $arrg_distri_us      = array();
            $arrg_r_des          = array();
            $fecha_hoy           = date('d-m-Y');
            $cuenta_true         = 0;
            $Nombre_TipoUS       = '';
            $idsupervisor        = '';
            $id_us_DBA           = '';
            $id_division_        = '';
            $id_distribuidora_   = '';
            $estado_pt           = false;
            $pais                = '';
            $activo              = '';
            $passwor_status      = '0';
            /*----------------------------------------EL SALVADOR------------------------------------------*/
            $regex_numTelefonoSV = "/^[0-9]{4}-[0-9]{4}+$/";
            $regex_numIP_SV      = "/^[0-9]{8}-[0-9]{1}+$/";
            $regex_NIT_SV        = "/^[0-9]{4}-[0-9]{6}-[0-9]{3}-[0-9]{1}+$/";
            $FormatNumTelSV      = "0000-0000";
            $FormatNumIP_SV      = "00000000-0";
            $FormatNumNIT_SV     = "0000-000000-000-0";
            $CantidadNumSV       = 8;
            $CantiNumIP_SV       = 9;
            $CantiNum_NIT_SV     = 14;
            /*-----------------------------------------GUATEMALA-------------------------------------------*/
            $regex_numTelefonoGT = "/^[0-9]{4}-[0-9]{4}+$/";
            $regex_numIP_GT      = "/^[0-9]{4}-[0-9]{5}-[0-9]{4}+$/";
            $FormatNumTelGT      = "0000-0000";
            $FormatNumIP_GT      = "0000-00000-0000";
            $regex_NIT_GT        = "";
            $FormatNumNIT_GT     = "00000000";
            $CantidadNumGT       = 8;
            $CantiNumIP_GT       = 13;
            $CantiNum_NIT_GT     = 8;
            /*------------------------------------------HONDURAS-------------------------------------------*/
            $regex_numTelefonoHN = "/^[0-9]{4}-[0-9]{4}+$/";
            $regex_numIP_HN      = "/^[0-9]{4}-[0-9]{4}-[0-9]{5}+$/";
            $FormatNumTelHN      = "0000-0000";
            $FormatNumIP_HN      = "0000-0000-00000";
            $regex_NIT_HN        = "";
            $FormatNumNIT_HN     = "00000000000000";
            $CantidadNumHN       = 8;
            $CantiNumIP_HN       = 13;
            $CantiNum_NIT_HN     = 14;
            /*------------------------------------REPUBLICA DOMINICANA-------------------------------------*/
            $regex_numTelefonoRP = "/^[0-9]{3}-[0-9]{4}+$/";
            $regex_numIP_RP      = "/^[0-9]{3}-[0-9]{7}-[0-9]{1}+$/";
            $FormatNumTelRP      = "000-0000";
            $FormatNumIP_RP      = "000-0000000-0";
            $regex_NIT_RP        = "";
            $FormatNumNIT_RP     = "00000000000";
            $CantidadNumRP       = 7;
            $CantiNumIP_RP       = 11;
            $CantiNum_NIT_RP     = 11;
            if(!empty($usuario)){
                if(strlen($usuario)>15){
                    $cuenta_true        = $cuenta_true + 0;
                    $arrg_validacion[0] = 'El <strong>usuario</strong> no puede exceder los 15 caracteres';
                }else{
                    if(preg_match($regex_usuario, $usuario)){
                        $cuenta_true        = $cuenta_true + 1;
                        $arrg_validacion[0] = '';
                    } else {
                        $cuenta_true        = $cuenta_true + 0;
                        $arrg_validacion[0] = 'El <strong>usuario</strong> solo puede ser números o letras.';
                    }
                }
            }else{
                $cuenta_true        = $cuenta_true + 0;
                $arrg_validacion[0] = 'El <strong>usuario</strong> es obligatorio';
            }
            if(!empty($contrasena)){
                if(strlen($contrasena)>15){
                    $cuenta_true        = $cuenta_true + 0;
                    $arrg_validacion[1] = 'La <strong>contraseña</strong> no puede exceder los 15 caracteres';
                }else{
                    if(preg_match($regex_contrasena, $contrasena)){
                        $cuenta_true        = $cuenta_true + 1;
                        $arrg_validacion[1] = '';
                    } else {
                        $cuenta_true        = $cuenta_true + 0;
                        $arrg_validacion[1] = 'La <strong>contraseña</strong> solo puede ser números o letras.';
                    }
                }
            }else{
                $cuenta_true        = $cuenta_true + 0;
                $arrg_validacion[1] = 'La <strong>contraseña</strong> es obligatoria';
            }
            $result_k = "";
            foreach ($arrg_validacion as $key => $value) {
                $result_k.="<p>".$value."</p>";
            }
            if($cuenta_true < 2 ){
                $resp = array('rs' => FALSE,'errores' => $result_k,'cla' => 'danger grDanguer');
                echo json_encode($resp);
                return;
            }else{
                $inicio_sesion = $this->lg->login($usuario,$contrasena);
                foreach ($inicio_sesion as $row){
                    $codusu             = $row->Usu_Id;
                    $nombrecompleto     = $row->Usu_nombre_usuario;
                    $privi              = $row->Priv_Id;
                    $nom_distribuidora  = $row->Dis_nombre;
                    $nomruta            = $row->Ru_nombre;
                    $codruta            = $row->Usu_Ru_Id;
                    $activo             = $row->Usu_estado;
                    $tipous             = $row->Priv_descripcion;
                    $pais               = $row->P_nombre;
                    $pais_id            = $row->P_Id;
                    $tipo_cliente       = 0;
                    $canal_usu          = $row->Ca_Id;
                    $canal              = $row->Ca_nombre;
                    $tipo_usuario_log   = $row->Priv_Id;
                    $passwor_status     = $row->Usu_act_contrasena;
                    if (strcmp($pais, "EL SALVADOR") == 0) 
                        if (strcmp($row->Ca_nombre, "DETALLE") == 0)
                            $tipo_cliente = 1;
                        elseif(strcmp($row->Ca_nombre, "PREFERENCIAL") == 0) 
                            $tipo_cliente = 1;
                        else
                            $tipo_cliente = 1;
                    elseif(strcmp($pais, "GUATEMALA") == 0) 
                        if (strcmp($row->Ca_nombre, "DETALLE") == 0) 
                            $tipo_cliente = 1;
                        elseif(strcmp($row->Ca_nombre, "PREFERENCIAL") == 0) 
                            $tipo_cliente = 1;
                        else
                            $tipo_cliente = 1;
                    elseif(strcmp($pais, "HONDURAS") == 0) 
                        if (strcmp($row->Ca_nombre, "DETALLE") == 0) 
                            $tipo_cliente = 3;
                        elseif(strcmp($row->Ca_nombre, "PREFERENCIAL") == 0) 
                            $tipo_cliente = 3;
                        else
                            $tipo_cliente = 3;
                    else
                        if (strcmp($row->Ca_nombre, "DETALLE") == 0) 
                            $tipo_cliente = 1;
                        elseif(strcmp($row->Ca_nombre, "PREFERENCIAL") == 0) 
                            $tipo_cliente = 1;
                        else
                            $tipo_cliente = 1;
                }

                if(count($inicio_sesion) == 0){
                    $resp = array(
                        'rs'        => FALSE,
                        'errores'   => '<br>Usuario ó contraseña incorrectos...',
                        'cla'       => 'danger grDanguer',
                        'cantidad'  => count($inicio_sesion)
                    );
                    echo json_encode($resp);
                    return;
                }else{


                    if (strcmp($pais, "EL SALVADOR") == 0) {
                        $V_regexTel     = $regex_numTelefonoSV;$CantidadTel = $CantidadNumSV;$FormatoTel       = $FormatNumTelSV;
                        $V_regex_numIP  = $regex_numIP_SV;$V_cantidadNumIP  = $CantiNumIP_SV;$V_FormatNumIP    = $FormatNumIP_SV;
                        $V_regex_numNIT = $regex_NIT_SV;$V_CantidadNumNIT   = $CantiNum_NIT_SV;$V_FormatNumNIT = $FormatNumNIT_SV;$NombreDUI = "DUI";$NombreNIT = "NIT (Opcional)";
                    }elseif (strcmp($pais, "GUATEMALA") == 0) {
                        $V_regexTel     = $regex_numTelefonoGT;$CantidadTel = $CantidadNumGT;$FormatoTel       = $FormatNumTelGT;
                        $V_regex_numIP  = $regex_numIP_GT;$V_cantidadNumIP  = $CantiNumIP_GT;$V_FormatNumIP    = $FormatNumIP_GT;
                        $V_regex_numNIT = $regex_NIT_GT;$V_CantidadNumNIT   = $CantiNum_NIT_GT;$V_FormatNumNIT = $FormatNumNIT_GT;$NombreDUI = "DPI";$NombreNIT = "NIT";
                    }elseif (strcmp($pais, "HONDURAS") == 0) {
                        $V_regexTel     = $regex_numTelefonoHN;$CantidadTel = $CantidadNumHN;$FormatoTel       = $FormatNumTelHN;
                        $V_regex_numIP  = $regex_numIP_HN;$V_cantidadNumIP  = $CantiNumIP_HN;$V_FormatNumIP    = $FormatNumIP_HN;
                        $V_regex_numNIT = $regex_NIT_HN;$V_CantidadNumNIT   = $CantiNum_NIT_HN;$V_FormatNumNIT = $FormatNumNIT_HN;$NombreDUI = "RNP";$NombreNIT = "RTN";
                    }elseif (strcmp($pais, "REPUBLICA DOMINICANA") == 0) {
                        $V_regexTel     = $regex_numTelefonoRP;$CantidadTel = $CantidadNumRP;$FormatoTel       = $FormatNumTelRP;
                        $V_regex_numIP  = $regex_numIP_RP;$V_cantidadNumIP  = $CantiNumIP_RP;$V_FormatNumIP    = $FormatNumIP_RP;
                        $V_regex_numNIT = $regex_NIT_RP;$V_CantidadNumNIT   = $CantiNum_NIT_RP;$V_FormatNumNIT = $FormatNumNIT_RP;$NombreDUI = "CIE";$NombreNIT = "RNC";
                    }else{
                        $V_regexTel     = $regex_numTelefonoSV;$CantidadTel = $CantidadNumSV;$FormatoTel       = $FormatNumTelSV;
                        $V_regex_numIP  = $regex_numIP_SV;$V_cantidadNumIP  = $CantiNumIP_SV;$V_FormatNumIP    = $FormatNumIP_SV;
                        $V_regex_numNIT = $regex_NIT_SV;$V_CantidadNumNIT   = $CantiNum_NIT_SV;$V_FormatNumNIT = $FormatNumNIT_SV;$NombreDUI = "DUI";$NombreNIT = "NIT";
                    }
                    if (strcmp ($activo, '1') == 0 ) {
                        if(strcmp ($tipous, 'VENDEDOR') == 0){
                            $rutaapp = "index.php/menu";
                        }

                        elseif(strcmp ($tipous, 'CENSADOR') == 0 ){
                            $rutaapp = "index.php/menu";
                            $parametros = array(
                                'Di_Id' => $inicio_sesion[0]->Di_Id,
                                'Dis_Id' => $inicio_sesion[0]->Dis_Id,
                                'P_nombre' => $inicio_sesion[0]->P_nombre
                            );
                            $arrg_r_des = $this->lg->ls_rutas_desarrollador($parametros);
                            $preguntar_distribuidora = "";
                            $preguntar_distribuidora = $this->lg->obtener_division_us($codusu);
                            $id_distribuidora_ = $preguntar_distribuidora[0]->Dis_Id;
                            $id_division_ = $preguntar_distribuidora[0]->Di_Id;
                        }



                        elseif(strcmp($tipous, 'DESARROLLADOR') == 0) {
                            $rutaapp = "index.php/menu";
                            $parametros = array(
                                'Di_Id' => $inicio_sesion[0]->Di_Id ?? null,
                                'Dis_Id' => $inicio_sesion[0]->Dis_Id ?? null,
                                'P_nombre' => $inicio_sesion[0]->P_nombre ?? null
                            );
                        
                            // Verificar rutas
                            $arrg_r_des = $this->lg->ls_rutas_desarrollador($parametros);
                            if (empty($arrg_r_des)) {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Sin resultados',
                                        text: 'No se encontraron rutas para el desarrollador.',
                                        confirmButtonText: 'Entendido'
                                    });
                                </script>";
                                return;
                            }
                        
                            // Verificar distribuidora
                            $preguntar_distribuidora = $this->lg->obtener_division_us($codusu);
                            if (empty($preguntar_distribuidora)) {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Sin resultados',
                                        text: 'No se encontró información de la distribuidora asociada al usuario.',
                                        confirmButtonText: 'Entendido'
                                    });
                                </script>";
                                return;
                            }
                        
                            // Asignar valores si todo está bien
                            $id_distribuidora_ = $preguntar_distribuidora[0]->Dis_Id;
                            $id_division_ = $preguntar_distribuidora[0]->Di_Id;
                        }
                        
                        
                        elseif(strcmp ($tipous, 'ADMINISTRADORES') == 0 ) {
                            $rutaapp = "index.php/reportes";
                            $preguntar_distribuidora = "";
                            $preguntar_distribuidora = $this->lg->obtener_distribuidoras_us($codusu);
                            $dus = 0;
                            foreach ($preguntar_distribuidora as $dtu){
                                $arrg_distri_us[$dus] = $dtu->Id_Distribuidora;
                                $dus++;
                            }
                        }elseif(strcmp ($tipous, 'SUPERVISOR_SDV') == 0 ){
                            $rutaapp = "index.php/menu";
                            $parametros = array(
                                'Di_Id' => $inicio_sesion[0]->Di_Id,
                                'Dis_Id' => $inicio_sesion[0]->Dis_Id,
                                'P_nombre' => $inicio_sesion[0]->P_nombre
                            );
                            $arrg_r_des = $this->lg->ls_rutas_desarrollador($parametros);
                            $preguntar_distribuidora = "";
                            $preguntar_distribuidora = $this->lg->obtener_division_us($codusu);
                            $id_distribuidora_ = $preguntar_distribuidora[0]->Dis_Id;
                            $id_division_ = $preguntar_distribuidora[0]->Di_Id;
                        }elseif(strcmp ($tipous, 'ADMIN VENTAS') == 0 || strcmp ($tipous, 'BODEGA') == 0 || strcmp ($tipous, 'CALIDAD') == 0) {                            
                            $rutaapp = 'index.php/admin_reclamo';
                            $estado_pt = $this->lg->usuario_pais_tercero($codusu);
                            $preguntar_distribuidora = "";
                            $preguntar_distribuidora = $this->lg->obtener_division_us($codusu);
                            $id_distribuidora_ = $preguntar_distribuidora[0]->Dis_Id;
                            $id_division_ = $preguntar_distribuidora[0]->Di_Id;
                        }else{
                            $rutaapp ="/#";
                        }
                        $arrg_result = array();
                        $arrg_result[0]['usuario']              = $usuario;
                        $arrg_result[0]['clave']                = $contrasena;
                        $arrg_result[0]['privilegio']           = $privi;
                        $arrg_result[0]['ruta_app']             = $rutaapp;
                        $arrg_result[0]['us_ID_Ruta']           = $codruta;
                        $arrg_result[0]['us_cod']               = $codusu;
                        $arrg_result[0]['nombre_us']            = $nombrecompleto;
                        $arrg_result[0]['idsupervisor']         = $idsupervisor;
                        $arrg_result[0]['TipoCliente']          = $tipo_cliente;
                        $arrg_result[0]['canal_usu']            = $canal_usu;
                        $arrg_result[0]['ruta_desarrollador']   = '';
                        $arrg_result[0]['Ruta_UsuariosLog']     = $codruta;
                        $arrg_result[0]['pais']                 = $pais;
                        $arrg_result[0]['ltdistr']              = $arrg_distri_us;
                        $arrg_result[0]['ls_rutas']             = $arrg_r_des;
                        $arrg_result[0]['id_division']          = $id_division_;
                        $arrg_result[0]['id_distribuidora']     = $id_distribuidora_;
                        $arrg_result[0]['RegexTelefono']        = $V_regexTel;
                        $arrg_result[0]['CantidTelefono']       = $CantidadTel;
                        $arrg_result[0]['FormatoTelefono']      = $FormatoTel;
                        $arrg_result[0]['RegexNumIP']           = $V_regex_numIP;
                        $arrg_result[0]['CantidNumIP']          = $V_cantidadNumIP;
                        $arrg_result[0]['FormatoNumIP']         = $V_FormatNumIP;
                        $arrg_result[0]['NombreDocumentoDUI']   = $NombreDUI;
                        $arrg_result[0]['RegexNumNIT']          = $V_regex_numNIT;
                        $arrg_result[0]['CantidNumNIT']         = $V_CantidadNumNIT;
                        $arrg_result[0]['FormatoNumNIT']        = $V_FormatNumNIT;
                        $arrg_result[0]['NombreDocumentoNIT']   = $NombreNIT;
                        $arrg_result[0]['NombreRuta']           = $nomruta;
                        $arrg_result[0]['passwor_status']       = $passwor_status;
                        $arrg_result[0]['canal']                = $canal;
                        
                        $codusu = encriptar_cadena($codusu);
                        $data = array(
                            'codusuario'        => $codusu,
                            'nombrecompleto'    => $nombrecompleto,
                            'nomruta'           => $nomruta,
                            'codRuta'           => $codruta,
                            'fechahoy'          => $fecha_hoy,
                            'idsupervisor'      => $idsupervisor,    
                            'pais'              => $pais,
                            'listdistribuidora' => $arrg_distri_us,
                            'usuario'           => $usuario,
                            'tipousuario'       => $tipous,
                            'tipocuentaus'      => $Nombre_TipoUS,
                            'id_canal'          => $canal_usu,
                            'id_privilegio'     => $privi,
                            'id_distribuidora'  => $id_distribuidora_,
                            'id_division'       => $id_division_,
                            'id_pais'           => $pais_id,
                            'usu_pais_tercero'  => $estado_pt
                        );
                        $this->session->set_userdata($data);
                        $resp = array(
                            'rs'            => TRUE,
                            'ls_resultado'  => $arrg_result
                        );
                        echo json_encode($resp);
                        return;
                    }else{
                        $resp = array(
                            'rs'        => FALSE,
                            'errores'   => '<br>Usuario inhabilitado temporalmente...',
                            'cla'       => 'danger grDanguer',
                            'cantidad'  => count($inicio_sesion)
                        );
                        echo json_encode($resp);
                        return;
                    }

                }


            }
	    }else{
	      	show_404();
	      	return;
	    }
  	}
}