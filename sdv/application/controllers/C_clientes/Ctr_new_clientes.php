<?php
header("Access-Control-Allow-Origin: *");
if(!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/El_Salvador');
ini_set ('gd.jpeg_ignore_warning', 1);
require APPPATH . '/libraries/ControladorBase.php';
require 'phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Ctr_new_clientes extends ControladorBase
{

    function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_login/Mdl_login','lg');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }

    function index(){
        $this->global['pageTitle'] = 'Registro de clientes';
        $this->loadViews('Clientes/V_ingresoclientes',$this->global);
    }
    public function resultconexion(){
        if($this->input->is_ajax_request()){
            $conexiones = $this->input->post('pin');
            if(!empty($conexiones)){
                echo json_encode(array('rs' => TRUE));
            }else{
                echo json_encode(array('rs' => FALSE));
            }
        }else{
            echo json_encode(array('rs' => FALSE));
            return;
        }
    }

    public function nuevoclienteok(){
        if($this->input->is_ajax_request()){
            $estado_us     = '';
            $txtusuario    = $this->input->post('us_cod');
            $txtUsModifica = $this->input->post('us_modifica') == null || $this->input->post('us_modifica') == '' ? 0 : $this->input->post('us_modifica');
            $txtruta       = $this->input->post('us_ID_Ruta');
            $V_pais        = $this->input->post('pais');
            $info_logueo=$this->lg->comprobar_estado_us($txtusuario);
            foreach ($info_logueo as $rowe){$estado_us=$rowe->Usu_estado;}
            if (strcmp ($estado_us, "1") == 0 ) {
                /*--------EXPRESIONES REGULARES--------*/
                $regex_textoEspecial = "/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-. ]+$/";
                $regex_contacto      = "/^[A-Za-zÁÉÍÓÚñáéíóúÑ ]+$/";
                $regex_SoloNumero    = "/^[A-Za-z0-9]+$/";
                $regex_NunNYPD       = "/^([-]{0,1})[0-9]{1,6}([.][0-9]{0,18})?$/";
                $regex_NumDecimalesP = "/^[0-9]{1,9}([.][0-9]{0,2})?$/";
                /*----------------------------------------EL SALVADOR------------------------------------------*/
                $regex_numTelefonoSV = "/^[0-9]{4}-[0-9]{4}+$/";$CantidadNumSV = 8;$FormatNumTelSV = "0000-0000";
                $regex_numIP_SV      = "/^[0-9]{8}-[0-9]{1}+$/";
                $CantiNumIP_SV       = 9;
                $FormatNumIP_SV      = "00000000-0";
                $regex_NIT_SV        = "/^[0-9]{4}-[0-9]{6}-[0-9]{3}-[0-9]{1}+$/";
                $CantiNum_NIT_SV     = 14;
                $FormatNumNIT_SV     = "0000-000000-000-0";
                /*-----------------------------------------GUATEMALA-------------------------------------------*/
                $regex_numTelefonoGT = "/^[0-9]{4}-[0-9]{4}+$/";$CantidadNumGT = 8;$FormatNumTelGT = "0000-0000";
                $regex_numIP_GT      = "/^[0-9]{4}-[0-9]{5}-[0-9]{4}+$/";
                $CantiNumIP_GT       = 13;
                $FormatNumIP_GT      = "0000-00000-0000";
                $regex_NIT_GT        = "";
                $CantiNum_NIT_GT     = 14;
                $FormatNumNIT_GT     = "";
                /*------------------------------------------HONDURAS-------------------------------------------*/
                $regex_numTelefonoHN = "/^[0-9]{4}-[0-9]{4}+$/";$CantidadNumHN = 8;$FormatNumTelHN = "0000-0000";
                $regex_numIP_HN      = "/^[0-9]{4}-[0-9]{4}-[0-9]{5}+$/";
                $CantiNumIP_HN       = 13;
                $FormatNumIP_HN      = "0000-0000-00000";
                $regex_NIT_HN        = "";
                $CantiNum_NIT_HN     = 14;
                $FormatNumNIT_HN     = "";
                /*------------------------------------REPUBLICA DOMINICANA-------------------------------------*/
                $regex_numTelefonoRP = "/^[0-9]{3}-[0-9]{4}+$/";$CantidadNumRP = 7;$FormatNumTelRP = "000-0000";
                $regex_numIP_RP      = "/^[0-9]{3}-[0-9]{7}-[0-9]{1}+$/";
                $CantiNumIP_RP       = 11;
                $FormatNumIP_RP      = "000-0000000-0";
                $regex_NIT_RP        = "";
                $CantiNum_NIT_RP     = 11;
                $FormatNumNIT_RP     = "";
                /*---------------------------------------------------------------------------------------------*/
                $input_nombre        = strval(mb_strtoupper(quitar_acentos($this->input->post('txtnomcliente'))));
                $input_direccion     = strval(mb_strtoupper(quitar_acentos($this->input->post('txtdireccion'))));
                $input_telefono      = strval($this->input->post('txtnumtelefono'));
                $input_contacto      = strval(mb_strtoupper(quitar_acentos($this->input->post('txtnomcontacto'))));
                $input_propietario   = strval(mb_strtoupper(quitar_acentos($this->input->post('txtpropietario'))));
                $input_departamento  = desencriptar_cadena($this->input->post('cbdepartamento'));
                $input_municipio     = desencriptar_cadena($this->input->post('cbmunicipio'));
                $input_tpuntoventa   = desencriptar_cadena($this->input->post('cbtpuntoventa'));
                $input_gironegocio   = desencriptar_cadena($this->input->post('cbgironegocio'));
                $input_img_fachada   = $this->input->post('imagenuno');
                $input_tfacturacion  = desencriptar_cadena($this->input->post('cbtfacturacion'));
                $input_ordenvisita   = $this->input->post('txtordenvisita');
                $input_frecuvisita   = $this->input->post('cbfrecuenciavisita');
                $input_diavisita     = $this->input->post('checkdiavisita');
                $input_latitud       = strval($this->input->post('txtlatitud'));
                $input_longitud      = strval($this->input->post('txtlongitud'));
                $input_codicioncli   = desencriptar_cadena($this->input->post('cbcondicioncli'));
                $input_cbrefrigerantes = $this->input->post('cbrefrigerantes');
                $input_cbreferencia = $this->input->post('cbreferencia');
                $input_exhibidoru      = '';
                $input_exhibidord      = '';
                $input_exhibidort      = '';
                $input_diacobro        = 0;
                $input_montocredito    = '';
                $input_dui             = '';
                $input_numcontribuyente = '';
                $input_nit              = '';
                $input_img_exhibidor    = '';
                $fecha_actual           = date('Y-m-d H:i:s');
                $TipoUsuario            = $this->input->post('TipoUsuario');
                $arrg_validacion        = array();
                $cuenta_true            = 0;
                if (strcmp($input_tfacturacion, 2) == 0) {
                    $input_dui = strval($this->input->post('txtdui'));
                    $input_numcontribuyente = strval($this->input->post('txtnumcontribuyente'));
                    $input_nit = intval($this->input->post('txtnit'));
                    if(empty($input_nit)){
                        $input_nit = '';
                    }
                    $input_nit = strtoupper($input_nit);
                }else{
                    $input_dui = strval($this->input->post('txtdui'));
                    $input_numcontribuyente = '';
                    $input_nit = strval($this->input->post('txtnit'));
                    $input_nit = strtoupper($input_nit);
                }
                if (strcmp($input_codicioncli, 2) == 0) {
                    $input_diacobro = $this->input->post('cbdiascobro');
                    $input_montocredito = strval($this->input->post('txtmontocredito'));
                }else{$input_diacobro = 0;$input_montocredito = 0;}
                //Nombre del establecimiento
                if(!empty($input_nombre)){
                    if(strlen($input_nombre)<7){
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[0] = 'El <strong>Nombre del Establecimiento</strong> es muy corto';
                    }else{
                        if(strlen($input_nombre)>77){
                            $cuenta_true = $cuenta_true + 0;
                            $arrg_validacion[0] = 'El <strong>Nombre del Establecimiento</strong> no puede exceder los 77 caracteres';
                        }else{
                            if(preg_match($regex_textoEspecial, $input_nombre)){
                                $cuenta_true = $cuenta_true + 1;
                                $arrg_validacion[0] = '';
                            } else {
                                $cuenta_true = $cuenta_true + 0;
                                $arrg_validacion[0] = 'El <strong>Nombre del Establecimiento</strong> esta mal escrito, por favor verifique, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>';
                            }
                        }
                    }
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[0] = 'El <strong>Nombre del Establecimiento</strong> es obligatorio';
                }
                //Direccion
                if(!empty($input_direccion)){
                    if(strlen($input_direccion)<25){
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[1] = 'La <strong>Direcci&oacute;n</strong> es muy corta';
                    }else{
                        if(strlen($input_direccion)>250){
                            $cuenta_true = $cuenta_true + 0;
                            $arrg_validacion[1] = 'La <strong>Direcci&oacute;n</strong> no puede exceder los 250 caracteres';
                        }else{
                            if(preg_match($regex_textoEspecial, $input_direccion)){
                                $cuenta_true = $cuenta_true + 1;
                                $arrg_validacion[1] = '';
                            } else {
                                $cuenta_true = $cuenta_true + 0;
                                $arrg_validacion[1] = 'La <strong>Direcci&oacute;n</strong> esta mal escrita, por favor verifique, solo se permiten letras, n&uacute;meros y estos signos <strong>(#),(°),(-),(.)</strong>';
                            }
                        }
                    }
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[1] = 'La <strong>Direccion</strong> es obligatoria';
                }
                //Departamento
                if(!empty($input_departamento)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[2] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[2] = 'El <strong>Departamento</strong> es obligatorio';
                }
                //Municipio
                if(!empty($input_municipio)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[3] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[3] = 'El <strong>Municipio</strong> es obligatorio';
                }
                //Telefono
                if (strcmp($V_pais, "EL SALVADOR") == 0) {
                    $V_regex_numTel = $regex_numTelefonoSV;$V_cantidadNumTel = $CantidadNumSV;$V_FormatNumTel = $FormatNumTelSV;
                }elseif (strcmp($V_pais, "GUATEMALA") == 0) {
                    $V_regex_numTel = $regex_numTelefonoGT;$V_cantidadNumTel = $CantidadNumGT;$V_FormatNumTel = $FormatNumTelGT;
                }elseif (strcmp($V_pais, "HONDURAS") == 0) {
                    $V_regex_numTel = $regex_numTelefonoHN;$V_cantidadNumTel = $CantidadNumHN;$V_FormatNumTel = $FormatNumTelHN;
                }elseif (strcmp($V_pais, "REPUBLICA DOMINICANA") == 0) {
                    $V_regex_numTel = $regex_numTelefonoRP;$V_cantidadNumTel = $CantidadNumRP;$V_FormatNumTel = $FormatNumTelRP;
                }else{
                    $V_regex_numTel = $regex_numTelefonoSV;$V_cantidadNumTel = $CantidadNumSV;$V_FormatNumTel = $FormatNumTelSV;
                }
                //Telefono
                if(!empty($input_telefono)){
                    if(preg_match($V_regex_numTel, $input_telefono)){
                        $cuenta_true = $cuenta_true + 1;
                        $arrg_validacion[4] = '';
                    } else {
                        $cuenta_true = $cuenta_true + 0;

                        $arrg_validacion[4] = 'El valor ingresado en el <strong>Tel&eacute;fono</strong> es incorrecto, tiene que tener <strong>'.$V_cantidadNumTel.'</strong> n&uacute;meros en el siguiente formato Ejemplo: <strong>'.$V_FormatNumTel.'</strong>';
                    }   
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[4] = 'El <strong>Tel&eacute;fono</strong> es obligatorio';
                }
                //Contacto
                if(!empty($input_contacto)){
                    if(strlen($input_contacto)<6){
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[5] = 'El nombre de <strong>Contacto</strong> es muy corto, por favor proporcione un nombre y un apellido';
                    }else{
                        if(strlen($input_contacto)>77){
                            $cuenta_true = $cuenta_true + 0;
                            $arrg_validacion[5] = 'El nombre de <strong>Contacto</strong> no puede exceder los 77 caracteres';
                        }else{
                            if(preg_match($regex_contacto, $input_contacto)){
                                $cuenta_true = $cuenta_true + 1;
                                $arrg_validacion[5] = '';
                            } else {
                                $cuenta_true = $cuenta_true + 0;
                                $arrg_validacion[5] = 'El nombre de  <strong>Contacto</strong> esta mal escrito, por favor verifique, solo se permiten letras</strong>';
                            }
                        }
                    }
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[5] = 'El nombre de <strong>Contacto</strong> es obligatorio';
                }
                //Propietario
                if(!empty($input_propietario)){
                    if(strlen($input_propietario)<6){
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[6] = 'El nombre de <strong>Propietario</strong> es muy corto, por favor proporcione un nombre y un apellido';
                    }else{
                        if(strlen($input_propietario)>77){
                            $cuenta_true = $cuenta_true + 0;
                            $arrg_validacion[6] = 'El nombre de <strong>Propietario</strong> no puede exceder los 77 caracteres';
                        }else{
                            if(preg_match($regex_contacto, $input_propietario)){
                                $cuenta_true = $cuenta_true + 1;
                                $arrg_validacion[6] = '';
                            } else {
                                $cuenta_true = $cuenta_true + 0;
                                $arrg_validacion[6] = 'El nombre de  <strong>Propietario</strong> esta mal escrito, por favor verifique, solo se permiten letras</strong>';
                            }
                        }
                    }
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[6] = 'El nombre de <strong>Propietario</strong> es obligatorio';
                }
                //Tipo Punto de Venta
                if(!empty($input_tpuntoventa)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[7] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[7] = 'El <strong>Tipo de punto de venta</strong> es obligatorio';
                }
                //Giro de Negocio
                if(!empty($input_gironegocio)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[8] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[8] = 'El <strong>Giro de Negocio</strong> es obligatorio';
                }
                //Tipo de Facturacion
                if(!empty($input_tfacturacion)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[9] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[9] = 'El <strong>Tipo de Facturaci&oacute;n</strong> es obligatorio';
                }
                //CREDITO FISCAL
                if (strcmp($input_tfacturacion, 2) == 0) {
                    if (strcmp($V_pais, "EL SALVADOR") == 0) {
                        $V_regex_numIP  = $regex_numIP_SV;$V_cantidadNumIP = $CantiNumIP_SV;$V_FormatNumIP    = $FormatNumIP_SV;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_SV;$V_CantidadNumNIT  = $CantiNum_NIT_SV;$V_FormatNumNIT = $FormatNumNIT_SV;$NombreDUI = "DUI";$NombreNIT = "NIT";
                    }elseif (strcmp($V_pais, "GUATEMALA") == 0) {
                        $V_regex_numIP  = $regex_numIP_GT;$V_cantidadNumIP = $CantiNumIP_GT;$V_FormatNumIP    = $FormatNumIP_GT;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_GT;$V_CantidadNumNIT  = $CantiNum_NIT_GT;$V_FormatNumNIT = $FormatNumNIT_GT;$NombreDUI = "DPI";$NombreNIT = "NIT";
                    }elseif (strcmp($V_pais, "HONDURAS") == 0) {
                        $V_regex_numIP  = $regex_numIP_HN;$V_cantidadNumIP = $CantiNumIP_HN;$V_FormatNumIP    = $FormatNumIP_HN;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_HN;$V_CantidadNumNIT  = $CantiNum_NIT_HN;$V_FormatNumNIT = $FormatNumNIT_HN;$NombreDUI = "RNP";$NombreNIT = "RTN";
                    }elseif (strcmp($V_pais, "REPUBLICA DOMINICANA") == 0) {
                        $V_regex_numIP  = $regex_numIP_RP;$V_cantidadNumIP = $CantiNumIP_RP;$V_FormatNumIP    = $FormatNumIP_RP;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_RP;$V_CantidadNumNIT  = $CantiNum_NIT_RP;$V_FormatNumNIT = $FormatNumNIT_RP;$NombreDUI = "CIE";$NombreNIT = "RNC";
                    }else{
                        $V_regex_numIP  = $regex_numIP_SV;$V_cantidadNumIP = $CantiNumIP_SV;$V_FormatNumIP    = $FormatNumIP_SV;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_SV;$V_CantidadNumNIT  = $CantiNum_NIT_SV;$V_FormatNumNIT = $FormatNumNIT_SV;$NombreDUI = "DUI";$NombreNIT = "NIT";$MAS_DUI = 1;$MAS_NIT =3;
                    }
                    if(!empty($input_dui)){
                        if(preg_match($V_regex_numIP, $input_dui)){
                            $cuenta_true = $cuenta_true + 1;
                            $arrg_validacion[10] = '';
                        } else {
                            $cuenta_true = $cuenta_true + 0;
                            $arrg_validacion[10] = 'El valor ingresado en el <strong>'.$NombreDUI.'</strong> es incorrecto, tiene que tener <strong>'.$V_cantidadNumIP.'</strong> n&uacute;meros en el siguiente formato Ejemplo: <strong>'.$V_FormatNumIP.'</strong>';
                        }
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[10] = 'El n&uacute;mero de <strong>'.$NombreDUI.'</strong> es obligatorio';
                    }
                    if(preg_match($regex_SoloNumero, $input_numcontribuyente)){
                        if(strlen($input_numcontribuyente)>15){
                            $arrg_validacion[11] = 'El <strong>n&uacute;mero de registro de contribuyente</strong> no puede exceder los 15 digitos';
                        }else{
                            $cuenta_true = $cuenta_true + 1;
                            $arrg_validacion[11] = '';
                        }
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[11] = 'El <strong>n&uacute;mero de registro de contribuyente</strong> es obligatorio, solo se permiten n&uacute;meros enteros, si el cliente no posee introducir cero';
                    }
                    if(!empty($input_nit)){
                        if(!empty($V_regex_numNIT)){
                            if(preg_match($V_regex_numNIT, $input_nit)){
                                $cuenta_true = $cuenta_true + 1;
                                $arrg_validacion[12] = '';
                            } else {
                                $cuenta_true = $cuenta_true + 0;
                                $arrg_validacion[12] = 'El valor ingresado en el <strong>'.$NombreNIT.'</strong> es incorrecto, tiene que tener <strong>'.$V_CantidadNumNIT.'</strong> n&uacute;meros en el siguiente formato Ejemplo: <strong>'.$V_FormatNumNIT.'</strong>';
                            }
                        }else{
                            if (strcmp($V_pais, "GUATEMALA") == 0) {
                                if(strlen($input_nit)>$V_CantidadNumNIT){
                                    $cuenta_true = $cuenta_true + 0;
                                    $arrg_validacion[12] = 'El <strong>'.$NombreNIT.'</strong> no puede exceder los '.$V_CantidadNumNIT.' n&uacute;meros';
                                }else{
                                    if(preg_match($regex_SoloNumero, $input_nit)){
                                        $cuenta_true = $cuenta_true + 1;
                                        $arrg_validacion[12] = '';
                                    }else{
                                        $cuenta_true = $cuenta_true + 0;
                                        $arrg_validacion[12] = 'En el<strong>'.$NombreNIT.'</strong> solo se permiten n&uacute;meros';
                                    }
                                }
                            }else{
                                if(strlen($input_nit)<$V_CantidadNumNIT){
                                    $cuenta_true = $cuenta_true + 0;
                                    $arrg_validacion[12] = 'El <strong>'.$NombreNIT.'</strong> tiene que tener '.$V_CantidadNumNIT.' n&uacute;meros';
                                }else{
                                    if(strlen($input_nit)>$V_CantidadNumNIT){
                                        $cuenta_true = $cuenta_true + 0;
                                        $arrg_validacion[12] = 'El <strong>'.$NombreNIT.'</strong> no puede exceder los '.$V_CantidadNumNIT.' n&uacute;meros';
                                    }else{
                                        if(preg_match($regex_SoloNumero, $input_nit)){
                                            $cuenta_true = $cuenta_true + 1;
                                            $arrg_validacion[12] = '';
                                        }else{
                                            $cuenta_true = $cuenta_true + 0;
                                            $arrg_validacion[12] = 'En el<strong>'.$NombreNIT.'</strong> solo se permiten n&uacute;meros';
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[12] = 'El n&uacute;mero de <strong>'.$NombreNIT.'</strong> es obligatorio';
                    }
                }else{///CONSUMIDOR FINAL
                    if (strcmp($V_pais, "EL SALVADOR") == 0) {
                        $V_regex_numIP  = $regex_numIP_SV;$V_cantidadNumIP = $CantiNumIP_SV;$V_FormatNumIP    = $FormatNumIP_SV;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_SV;$V_CantidadNumNIT  = $CantiNum_NIT_SV;$V_FormatNumNIT = $FormatNumNIT_SV;$NombreDUI = "DUI";$NombreNIT = "NIT";
                    }elseif (strcmp($V_pais, "GUATEMALA") == 0) {
                        $V_regex_numIP  = $regex_numIP_GT;$V_cantidadNumIP = $CantiNumIP_GT;$V_FormatNumIP    = $FormatNumIP_GT;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_GT;$V_CantidadNumNIT  = $CantiNum_NIT_GT;$V_FormatNumNIT = $FormatNumNIT_GT;$NombreDUI = "DPI";$NombreNIT = "NIT";
                    }elseif (strcmp($V_pais, "HONDURAS") == 0) {
                        $V_regex_numIP  = $regex_numIP_HN;$V_cantidadNumIP = $CantiNumIP_HN;$V_FormatNumIP    = $FormatNumIP_HN;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_HN;$V_CantidadNumNIT  = $CantiNum_NIT_HN;$V_FormatNumNIT = $FormatNumNIT_HN;$NombreDUI = "RNP";$NombreNIT = "RTN";
                    }elseif (strcmp($V_pais, "REPUBLICA DOMINICANA") == 0) {
                        $V_regex_numIP  = $regex_numIP_RP;$V_cantidadNumIP = $CantiNumIP_RP;$V_FormatNumIP    = $FormatNumIP_RP;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_RP;$V_CantidadNumNIT  = $CantiNum_NIT_RP;$V_FormatNumNIT = $FormatNumNIT_RP;$NombreDUI = "CIE";$NombreNIT = "RNC";
                    }else{
                        $V_regex_numIP  = $regex_numIP_SV;$V_cantidadNumIP = $CantiNumIP_SV;$V_FormatNumIP    = $FormatNumIP_SV;
                        /*-------------------------------------------------------------------------------------------------*/
                        $V_regex_numNIT = $regex_NIT_SV;$V_CantidadNumNIT  = $CantiNum_NIT_SV;$V_FormatNumNIT = $FormatNumNIT_SV;$NombreDUI = "DUI";$NombreNIT = "NIT";$MAS_DUI = 1;$MAS_NIT =3;
                    }
                    if (strcmp($V_pais, "HONDURAS") == 0) {
                        if(!empty($input_dui)){
                            if(preg_match($V_regex_numIP, $input_dui)){
                                $cuenta_true = $cuenta_true + 1;
                                $arrg_validacion[10] = '';
                            } else {
                                $cuenta_true = $cuenta_true + 0;
                                $arrg_validacion[10] = 'El valor ingresado en el <strong>'.$NombreDUI.'</strong> es incorrecto, tiene que tener <strong>'.$V_cantidadNumIP.'</strong> n&uacute;meros en el siguiente formato Ejemplo: <strong>'.$V_FormatNumIP.'</strong>';
                            }
                        }else{
                            $cuenta_true = $cuenta_true + 0;
                            $arrg_validacion[10] = 'El n&uacute;mero de <strong>'.$NombreDUI.'</strong> es obligatorio'.$input_dui;
                        }
                        if(!empty($input_nit)){
                            if(!empty($V_regex_numNIT)){
                                if(preg_match($V_regex_numNIT, $input_nit)){
                                    $cuenta_true = $cuenta_true + 1;
                                    $arrg_validacion[12] = '';
                                } else {
                                    $cuenta_true = $cuenta_true + 0;
                                    $arrg_validacion[12] = 'El valor ingresado en el <strong>'.$NombreNIT.'</strong> es incorrecto, tiene que tener <strong>'.$V_CantidadNumNIT.'</strong> n&uacute;meros en el siguiente formato Ejemplo: <strong>'.$V_FormatNumNIT.'</strong>';
                                }
                            }else{
                                if(strlen($input_nit)<$V_CantidadNumNIT){
                                    $cuenta_true = $cuenta_true + 0;
                                    $arrg_validacion[12] = 'El <strong>'.$NombreNIT.'</strong> tiene que tener '.$V_CantidadNumNIT.' n&uacute;meros';
                                }else{
                                    if(strlen($input_nit)>$V_CantidadNumNIT){
                                        $cuenta_true = $cuenta_true + 0;
                                        $arrg_validacion[12] = 'El <strong>'.$NombreNIT.'</strong> no puede exceder los '.$V_CantidadNumNIT.' n&uacute;meros';
                                    }else{
                                        if(preg_match($regex_SoloNumero, $input_nit)){
                                            $cuenta_true = $cuenta_true + 1;
                                            $arrg_validacion[12] = '';
                                        }else{
                                            $cuenta_true = $cuenta_true + 0;
                                            $arrg_validacion[12] = 'En el<strong>'.$NombreNIT.'</strong> solo se permiten n&uacute;meros';
                                        }
                                    }
                                }
                            }
                        }else{
                            $cuenta_true = $cuenta_true + 0;
                            $arrg_validacion[12] = 'El n&uacute;mero de <strong>'.$NombreNIT.'</strong> es obligatorio';
                        }
                        $input_numcontribuyente = "";
                        $cuenta_true = $cuenta_true + 1;
                        $arrg_validacion[11] = '';
                    }else{
                        $input_numcontribuyente = "";$input_nit = "";$input_dui = "";
                        $cuenta_true = $cuenta_true + 3;
                        $arrg_validacion[10] = '';
                        $arrg_validacion[11] = '';
                        $arrg_validacion[12] = '';
                    }
                }//FINAL CONDICION DE TIPO DE FACTURACION
                //Condicion de Cliente
                if(!empty($input_codicioncli)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[13] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[13] = 'La <strong>Condicion del Cliente</strong> es obligatoria';
                    $input_diacobro = 0;
                }
                //CREDITO
                if (strcmp($input_codicioncli, 2) == 0) {
                    //DIA COBRO
                    if(!empty($input_diacobro)){
                        $cuenta_true = $cuenta_true + 1;
                        $arrg_validacion[14] = '';
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[14] = 'El <strong>D&iacute;a de cobro</strong> es obligatorio';
                    }
                    if(!empty($input_montocredito)){
                        $cuenta_true = $cuenta_true + 1;
                        $arrg_validacion[15] = '';

                        // if(preg_match($regex_NumDecimalesP, $input_montocredito)){
                        //     $cuenta_true = $cuenta_true + 1;
                        //     $arrg_validacion[15] = '';
                        // }else{
                        //     $cuenta_true = $cuenta_true + 0;
                        //     $arrg_validacion[15] = 'En el <strong>Monto de cr&eacute;dito</strong> solo se permiten n&uacute;meros positivos y m&aacute;ximo 2 decimales';
                        // }
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[15] = 'El <strong>Monto de cr&eacute;dito</strong> es obligatorio';
                    }
                }else{
                    $input_diacobro = 0;$input_montocredito = 0;
                    $cuenta_true = $cuenta_true + 2;
                }
                //Frecuencia de visita
                if(!empty($input_frecuvisita)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[16] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[16] = 'La <strong>Frecuencia de visita</strong> es obligatoria';
                }
                //Dia de visita
                if(!empty($input_diavisita)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[17] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[17] = 'El <strong>D&iacute;a de visita</strong> es obligatorio';
                }
                //Latitud
                if(!empty($input_latitud)){
                    if(preg_match($regex_NunNYPD, $input_latitud)){
                        $cuenta_true = $cuenta_true + 1;
                        $arrg_validacion[18] = '';
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[18] = 'En la <strong>Latitud</strong> solo se permiten n&uacute;meros';
                    }
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[18] = 'La <strong>Latitud</strong> es obligatoria';
                }
                //Longitud
                if(!empty($input_longitud)){
                    if(preg_match($regex_NunNYPD, $input_longitud)){
                        $cuenta_true = $cuenta_true + 1;
                        $arrg_validacion[19] = '';
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[19] = 'En la <strong>Longitud</strong> solo se permiten n&uacute;meros';
                    }
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[19] = 'La <strong>Longitud</strong> es obligatoria';
                }
                //Foto de la fachada del negocio
                if(!empty($input_img_fachada)){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[25] = '';
                }else{
                    $cuenta_true = $cuenta_true + 0;
                    $arrg_validacion[25] = 'Por favor tome una <strong> Foto de fachada del negocio</strong>';
                }
                if($input_cbrefrigerantes == 0){
                    $cuenta_true = $cuenta_true + 1;
                    $arrg_validacion[26] = '';
                }else{
                    if(!empty($input_cbrefrigerantes)){
                        $cuenta_true = $cuenta_true + 1;
                        $arrg_validacion[26] = '';
                    }else{
                        $cuenta_true = $cuenta_true + 0;
                        $arrg_validacion[26] = 'El Campo de Capacidad del negocio es obligatorio';
                    }
                }
                if($input_ordenvisita == 0) {
                    $input_ordenvisita = 1;
                }else{}
                $result_k = "";
                $arrg_limpiar = array_filter($arrg_validacion);
                foreach ($arrg_limpiar as $key => $value) {
                    $result_k.="<p>".$value."</p>";
                }
                if($cuenta_true < 21 ){
                    $resp = array('rs' => FALSE,'errores' => $result_k,'cla' => 'danger grDanguer');
                    echo json_encode($resp);
                    return;
                }else{
                    $input_img_fachada = $this->generarimagen($this->input->post('imagenuno'),$txtruta);
                    $cadena_dias = "L_0,M_0,I_0,J_0,V_0,S_0,D_0";
                    $cadenasinestado = "";
                    $errores ='';
                    if(isset($input_diavisita)){
                        $seleccionado = "";
                        foreach($input_diavisita as $seleccionado){
                            $seleccionado = $seleccionado;
                            $cadenasinestado = substr($seleccionado,0,2);
                            $cadenasinestado = $cadenasinestado."0";
                            $cadena_dias = str_replace($cadenasinestado,$seleccionado,$cadena_dias);
                        }
                    }else{
                        $cadena_dias = 'L_0,M_0,I_0,J_0,V_0,S_0,D_0';
                    }
                    $CanalRuta = 0;
                    $clienteTipo = $this->cl->TipoCLiente(intval($txtusuario));
                    $CanalRuta = $clienteTipo->Ca_nombre;
                    $tipo_cliente = 0;
                    if (strcmp($V_pais, "EL SALVADOR") == 0) {
                        if (strcmp($CanalRuta, "DETALLE") == 0) {
                            $tipo_cliente = 1;
                        }elseif(strcmp($CanalRuta, "PREFERENCIAL") == 0) {
                            $tipo_cliente = 1;
                        }else{
                            $tipo_cliente = 1;
                        }
                    }elseif(strcmp($V_pais, "GUATEMALA") == 0) {
                        if (strcmp($CanalRuta, "DETALLE") == 0) {
                            $tipo_cliente = 1;
                        }elseif(strcmp($CanalRuta, "PREFERENCIAL") == 0) {
                            $tipo_cliente = 1;
                        }else{
                            $tipo_cliente = 1;
                        }
                    }elseif(strcmp($V_pais, "HONDURAS") == 0) {
                        if (strcmp($CanalRuta, "DETALLE") == 0) {
                            $tipo_cliente = 3;
                        }elseif(strcmp($CanalRuta, "PREFERENCIAL") == 0) {
                            $tipo_cliente = 3;
                        }else{
                            $tipo_cliente = 3;
                        }
                    }else{
                        if (strcmp($CanalRuta, "DETALLE") == 0) {
                            $tipo_cliente = 1;
                        }elseif(strcmp($CanalRuta, "PREFERENCIAL") == 0) {
                            $tipo_cliente = 1;
                        }else{
                            $tipo_cliente = 1;
                        } 
                    }
                
                    $input_nombre = str_replace("TDA","TIENDA", $input_nombre);
                    $input_nombre = str_replace("TD","TIENDA", $input_nombre);
                    $input_nombre = str_replace("TODA","TIENDA", $input_nombre);

                    $input_direccion = str_replace("LOT","LOTIFICACION", $input_direccion);
                    $input_direccion = str_replace("COM","COMUNIDAD", $input_direccion);
                    $input_direccion = str_replace("CLL","CALLE", $input_direccion);
                    $input_direccion = str_replace("BARIO","BARRIO", $input_direccion);
                    $input_direccion = str_replace("LOTIFICASION","LOTIFICACION", $input_direccion);
                    $input_direccion = str_replace("PSJ","PJE", $input_direccion);
                    $input_direccion = str_replace("CLLE","CALLE", $input_direccion);
                    $input_direccion = str_replace("CSR","CASERIO", $input_direccion);
                    $input_direccion = str_replace("CNTO","CANTON", $input_direccion);
                    $input_direccion = str_replace("CIRCUNBALACION","CIRCUNVALACION", $input_direccion);
                    $input_direccion = str_replace("CTON","CANTON", $input_direccion);
                    $input_direccion = str_replace("PRREGRINOS","PEREGRINOS", $input_direccion);
                    $input_direccion = str_replace("CONSECCION","CONCEPCION", $input_direccion);
                    $input_direccion = str_replace("ASIENDA","HACIENDA", $input_direccion);
                    $input_direccion = str_replace("HASIENDA","HACIENDA", $input_direccion);
                    $input_direccion = str_replace("PRINSIPAL","PRINCIPAL", $input_direccion);
                    $input_direccion = str_replace("MUNISIPAL","MUNICIPAL", $input_direccion);
                    $input_direccion = str_replace("INGLECIA","IGLESIA", $input_direccion);

                    $ConsultaCuanto = 0;
                    $cuantotoken = 0;
                    $ConsultaCuanto = $this->cl->ContarTokenExiste($this->input->post('TokenInsert'));
                    $cuantotoken = $ConsultaCuanto->totaltoken;

                    $ord_l = 1;$ord_m = 1;$ord_i = 1;$ord_j = 1;$ord_v = 1;$ord_s = 1;$ord_d = 1;
                    $ord_visitaSema = '';
        
                    $ord_l = intval($this->input->post('txtordenvisital'));
                    $ord_m = intval($this->input->post('txtordenvisitam'));
                    $ord_i = intval($this->input->post('txtordenvisitai'));
                    $ord_j = intval($this->input->post('txtordenvisitaj'));
                    $ord_v = intval($this->input->post('txtordenvisitav'));
                    $ord_s = intval($this->input->post('txtordenvisitas'));
                    $ord_d = intval($this->input->post('txtordenvisitad'));
                    if($ord_l == 0)
                        $ord_l=1;
                    if($ord_m == 0)
                        $ord_m=1;
                    if($ord_i == 0)
                        $ord_i=1;
                    if($ord_j == 0)
                        $ord_j=1;
                    if($ord_v == 0)
                        $ord_v=1;
                    if($ord_s == 0)
                        $ord_s=1;
                    if($ord_d == 0)
                        $ord_d=1;
                    $ord_visitaSema = $ord_l.','.$ord_m.','.$ord_i.','.$ord_j.','.$ord_v.','.$ord_s.','.$ord_d;
                    $cadena_dias = explode(",", $cadena_dias);
                    if($cuantotoken > 0){
                        $inserdata = TRUE;
                    }else{
                        $insertar = array(
                            'Cli_Id' => 0,
                            'Cli_codigo' => '0',
                            'Cli_nombre' => $input_nombre,
                            'Cli_direccion' => $input_direccion,
                            'Cli_Mun_Id' => intval($input_municipio),
                            'Cli_telefono' => $input_telefono,
                            'Cli_contacto' => $input_contacto,
                            'Cli_dui' => $input_dui,
                            'Cli_num_registro' => $input_numcontribuyente,
                            'Cli_nit' => $input_nit,
                            'Cli_Pers_Id' => 1,
                            'Cli_ctr_iva' => 0,
                            'Cli_foto_dui_frontal' => NULL,
                            'Cli_foto_dui_trasera' => NULL,
                            'Cli_foto_nit_frontal' => NULL,
                            'Cli_foto_nrc_frontal' => NULL,
                            'Cli_l' => substr($cadena_dias[0], -1),
                            'Cli_m' => substr($cadena_dias[1], -1),
                            'Cli_mi' => substr($cadena_dias[2], -1),
                            'Cli_j' => substr($cadena_dias[3], -1),
                            'Cli_v' => substr($cadena_dias[4], -1),
                            'Cli_s' => substr($cadena_dias[5], -1),
                            'Cli_d' => substr($cadena_dias[6], -1),
                            'Cli_orden_l' => $ord_l,
                            'Cli_orden_m' => $ord_m,
                            'Cli_orden_mi' => $ord_i,
                            'Cli_orden_j' => $ord_j,
                            'Cli_orden_v' => $ord_v,
                            'Cli_orden_s' => $ord_s,
                            'Cli_orden_d' => $ord_d,
                            'Cli_frecuencia_visita' => $input_frecuvisita,
                            'Cli_latitud' => $input_latitud,
                            'Cli_longitud' => $input_longitud,
                            'Cli_Ru_Id' => intval($txtruta),
                            'Cli_Gir_Id' => intval($input_gironegocio),
                            'Cli_foto' => $input_img_fachada,
                            'Cli_estado' => 1,
                            'Cli_estado_sys' => 'N',
                            'Cli_estado_analista' => 0,
                            'Cli_estado_descarga' => 0,
                            'Cli_editado' => 0,
                            'Cli_comentario' => NULL,
                            'Cli_tipo_cliente' => $tipo_cliente,
                            'Cli_ac_exhibidor' => 0,
                            'Cli_ac_cliente' => 1,
                            'Cli_us_resolucion' => 'PENDIENTE',
                            'Cli_fecha_ingreso' => $fecha_actual,
                            'Cli_fecha_r_supervisor' => NULL,
                            'Cli_fecha_r_analista' => NULL,
                            'Cli_ul_fecha_ac_cliente' => $fecha_actual,
                            'Cli_ul_fecha_ac_exhibidor' => NULL,
                            'Cli_token' => $this->input->post('TokenInsert'),
                            'Cli_bloq_exh' => 0,
                            'Cli_tipo_us' => $TipoUsuario,
                            'Cli_estado_csexh' => 0,
                            'Cli_cantidad_CMR' => $input_cbrefrigerantes,
                            'Cli_usuario_modifica' => $txtusuario,
                            'Cli_valid_cod' => 1,
                            'Cli_Refc_Id' => $input_cbreferencia
                        );
                        $inserdata=$this->cl->guardar_cliente($insertar);
                    }
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
                            'errores' => ' Ocurrio un error en el proceso.',
                            'cla' => 'success grDanguer'
                            )
                        );
                    }
                    return;
                }
            }else{
                $resp = array('rs' => FALSE,'errores' =>$estado_us. '<br>Usuario inhabilitado temporalmente...'.$txtusuario,'cla' => 'danger grDanguer');
                echo json_encode($resp);
                return;
            }
        }else{
            $resp = array(
                'rs' => FALSE,
                'errores' => 'ERROR DESCONOCIDO',
                'cla' => 'danger grDanguer'
            );
            echo json_encode($resp);
            return;
        }        
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
            $carpeta = "../Uploads/img_server/clte_n/".$runanombre;
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