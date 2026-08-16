<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This function is used to print the content of any data
 */
function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * This function used to get the CI instance
 */
if(!function_exists('get_instance'))
{
    function get_instance()
    {
        $CI = &get_instance();
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if(!function_exists('getHashedPassword'))
{
    function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}


if(!function_exists('__encrypt'))
{
    function _encrypt($data) {
        $key = 'KiLg/Z1f+zB29tge/Op2018VIw+W+aC+puPuSaS8orY=';
        // Eliminar la codificación base64 de nuestra clave
        $encryption_key = base64_decode($key);
        // Generar un vector de inicialización.
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Cifre los datos utilizando el cifrado AES 256 en modo CBC utilizando nuestra clave de cifrado y el vector de inicialización
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        // El $ iv es tan importante como la clave para descifrar, así que guárdelo con nuestros datos cifrados usando un separador único (: :)
        return base64_encode($encrypted . '::' . $iv);
    }    
}

if(!function_exists('__decrypt'))
{
    function _decrypt($data) {
        $key = 'KiLg/Z1f+zB29tge/Op2018VIw+W+aC+puPuSaS8orY=';
        // Eliminar la codificación base64 de nuestra clave
        $encryption_key = base64_decode($key);
        // Para descifrar, divida los datos cifrados de nuestra IV: nuestro único separador utilizado fue "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }    
}


     
if(!function_exists('ip_address'))
{

function ip_address()
    {
        $CI = &get_instance();
   
        return $CI->input->ip_address();
    
        
    }
}



if(!function_exists('fallo_psw_pr'))
{
    function fallo_psw_pr($id_us,$tipo){
        $CI = &get_instance();
        $CI->load->model('suspension_model','sp');
        $fecha_hoy=date("Y-m-d H:i:s");
        $datainsert = array(
        'fc_us_id' => $id_us,
        'fc_fecha' => $fecha_hoy,
        'fc_tipo' => $tipo);
        $datoin=$CI->sp->insert_evento_fallo($datainsert);
        if($datoin){
        }else{}
    }
}

if(!function_exists('random_string'))
{
    function random_string($clave,$longitud) {
        $characters = $clave;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $longitud; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if ( ! function_exists('add_foreign_key'))
{
    /**
     * @param string $table       Table name
     * @param string $foreign_key Collumn name having the Foreign Key
     * @param string $references  Table and column reference. Ex: users(id)
     * @param string $on_delete   RESTRICT, NO ACTION, CASCADE, SET NULL, SET DEFAULT
     * @param string $on_update   RESTRICT, NO ACTION, CASCADE, SET NULL, SET DEFAULT
     *
     * @return string SQL command
     */
    function add_foreign_key($table, $foreign_key, $references, $on_delete = 'RESTRICT', $on_update = 'RESTRICT')
    {
        $references = explode('(', str_replace(')', '', str_replace('`', '', $references)));
        return "ALTER TABLE `{$table}` ADD CONSTRAINT `{$table}_{$foreign_key}_fk` FOREIGN KEY (`{$foreign_key}`) REFERENCES `{$references[0]}`(`{$references[1]}`) ON DELETE {$on_delete} ON UPDATE {$on_update}";
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 * @param {string} $hashedPassword : This is hashed password
 */
if(!function_exists('verifyHashedPassword'))
{
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }
}

/**
 * This method used to get current browser agent
 */
if(!function_exists('getBrowserAgent'))
{
    function getBrowserAgent()
    {
        $CI = get_instance();
        $CI->load->library('user_agent');

        $agent = '';

        if ($CI->agent->is_browser())
        {
            $agent = $CI->agent->browser().' '.$CI->agent->version();
        }
        else if ($CI->agent->is_robot())
        {
            $agent = $CI->agent->robot();
        }
        else if ($CI->agent->is_mobile())
        {
            $agent = $CI->agent->mobile();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }

        return $agent;
    }
}

if(!function_exists('setProtocol'))
{
    function setProtocol()
    {
        $CI = &get_instance();
                    
        $CI->load->library('email');
        
        $config['protocol'] = PROTOCOL;
        $config['mailpath'] = MAIL_PATH;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['smtp_user'] = SMTP_USER;
        $config['smtp_pass'] = SMTP_PASS;
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        
        $CI->email->initialize($config);
        
        return $CI;
    }
}

if(!function_exists('emailConfig'))
{
    function emailConfig()
    {
        $CI->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['mailpath'] = MAIL_PATH;
        $config['charset'] = 'UTF-8';
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
    }
}

if(!function_exists('setFlashData'))
{
    function setFlashData($status, $flashMsg)
    {
        $CI = get_instance();
        $CI->session->set_flashdata($status, $flashMsg);
    }
}

if(!function_exists('get_cod_aleatorio'))
{
function get_cod_aleatorio(){

$a=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 7); 
$b=substr(str_shuffle("0123456789"), 0, 6); 

return $codigo=$a.date("ymdHis").$b;
}
}

if(!function_exists('new_hash_csrf_token'))
{
    function new_hash_csrf_token(){

        $CI = &get_instance();
        $CI->load->helper('security');
       $result_set = json_encode(array(
            'csrftokename' => $CI->security->get_csrf_token_name(), 
            'csrfhash' => $CI->security->get_csrf_hash()
        ));
        return $result_set;
    }
}

if(!function_exists('encriptar_cadena'))
{
    function encriptar_cadena($cadena){
        $salida = '';
        $metodo_de_encriptado = 'AES-256-CBC';
        $alfayomega=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNLOPQRSTUVWXYZ"), 0, 32);
        $llave_secreta = '777nsmhf57e1gjzbat0xo3p29yu6rqwl';
        $iv_secreto =$alfayomega;
        $llave =hash('sha256', $llave_secreta);
        $vector_inicializado = substr(hash('sha256', $iv_secreto),0,16);
        $salida = openssl_encrypt($cadena, $metodo_de_encriptado, $llave,0,$vector_inicializado);
        $salida = base64_encode($salida.'::'.$vector_inicializado);
        $a=substr(str_shuffle("zxyopqrstuvabcdefgiquantics"), 0, 1);
        $b=substr(str_shuffle("1234abcd56789efghijklquanticsyeah"), 0, 1);
        $totalcadena = strlen($salida);
        $restacadena = $totalcadena - 6;
        $principiocadena = substr($salida,0,-$restacadena);
        $finalcadena = substr($salida,6);
        $salida = $a.$principiocadena.$b.$finalcadena;
        $salida = str_replace("=","_1",$salida);
        return $salida;
    }

}

if(!function_exists('desencriptar_cadena'))
{
    function desencriptar_cadena($cadena){
        $salida = '';
        $cadena = str_replace("_1","=",$cadena);
        $cadenacompleta = substr($cadena, 1);
        $dtotalcadena = strlen($cadenacompleta);
        $drestacadena = $dtotalcadena - 6;
        $dprincipiocadena = substr($cadenacompleta,0,-$drestacadena);
        $dfinalcadena = substr($cadenacompleta,7);
        $dcadenacompleta = $dprincipiocadena.$dfinalcadena;

        $metodo_de_encriptado = 'AES-256-CBC';
        $llave_secreta = '777nsmhf57e1gjzbat0xo3p29yu6rqwl';
        $llave =hash('sha256', $llave_secreta);
        $partes =explode('::', base64_decode($dcadenacompleta), 2);
        if(count($partes) == 2) {
            list($info_encriptada, $vector_inicializado) =  $partes;
            $vector =strlen($vector_inicializado);
            if ($vector<16){
                $salida='7777777';
            }else{
                $salida = @openssl_decrypt($info_encriptada, $metodo_de_encriptado, $llave, 0, $vector_inicializado);
            }
        }else {
            $salida='7777777';
        }
        $valor_limpio = '';
        $parametros = array(
            '/[^a-zA-Z0-9]/' => ''
        );
        $valor_limpio = strip_tags($salida);
        $valor_limpio = preg_replace(array_keys($parametros), array_values($parametros), $valor_limpio );
        $longitud_v = strlen($salida);
        if(empty($valor_limpio)){
            $valor_limpio = '7777777';
        }
        /*if($longitud_v<25){
            $valor_limpio = '7777777';
        }*/
        return $valor_limpio;
    }
}

if(!function_exists('cadena_aleatorio'))
{
    function cadena_aleatoria($longitud){
    $a=substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNLOPQRSTUVWXYZ"), 0, $longitud); 
    return $a;
    }
}
if(!function_exists('numero_aleatorio'))
{
    function numero_aleatorio($longitud){
    $a=substr(str_shuffle("123456789104598921777"), 0, $longitud); 
    return $a;
    }
}

if(!function_exists('desinfectar_sololetras_numeros'))
{
    function desinfectar_sololetras_numeros($valor) {
        $valor_limpio = '';
        $parametros = array(
            '/[\x00-\x1F\x80-\xFF]/' => '',
            '/[^a-zA-Z0-9]/' => ''
        );
        $valor_limpio = strip_tags($valor); 
        $valor_limpio = preg_replace(array_keys($parametros), array_values($parametros), $valor_limpio );
        return $valor_limpio;
    }
}

if(!function_exists('solo_alfanumericos'))
{
    function solo_alfanumericos($valor_array) {
        $valor_limpio = '';
        $arrnuevo = array();
        foreach ($valor_array as $clave => $valor){

            $valor_limpio = '';
            $parametros = array(
                    //'/[\x00-\x1F\x80-\xFF]/' => '',
                    '/[^a-zA-Z0-9 ]/' => ''
            );
            $valor_limpio = strip_tags($valor); 
            $valor_limpio = preg_replace(array_keys($parametros), array_values($parametros), $valor_limpio );
            $arrnuevo[$clave] = $valor_limpio;
        }
        return $arrnuevo;
    }
}
if(!function_exists('return_alfanume'))
{
    function return_alfanume($valor) {
        $valor_limpio = '';
        $parametros = array(
            '/[^a-zA-Z0-9ÑñáéíóúÁÉÍÓÚ ]/' => ''
        );
        $valor_limpio = strip_tags($valor);
        $valor_limpio = preg_replace(array_keys($parametros), array_values($parametros), $valor_limpio);
    
        return $valor_limpio;
    }
}
if(!function_exists('return_letra'))
{
    function return_letra($valor) {
        $valor_limpio = '';
        $parametros = array(
            '/[^a-zA-ZÑñáéíóúÁÉÍÓÚ ]/' => ''
        );
        $valor_limpio = strip_tags($valor);
        $valor_limpio = preg_replace(array_keys($parametros), array_values($parametros), $valor_limpio);
    
        return $valor_limpio;
    }
}

if(!function_exists('quitar_acentos'))
{
    function quitar_acentos($cadena){
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = str_replace("ñ","n",$cadena);
        $cadena = str_replace("Ñ","N",$cadena);
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }
}

if(!function_exists('h_estado_us_imp')){

    function h_estado_us_imp($cod_us_imp){

        $CI = &get_instance();
        $CI->load->model('M_login/Mdl_login','login');
        $info_estado = $CI->login->estado_imp($cod_us_imp);
        $estado_result = '';

        foreach ($info_estado as $resp){$estado_result = $resp->Estado_imp;}

        if (strcmp ($estado_result, 'A') == 0 ) {
            return TRUE;
        }else{
            return FALSE;
        }

    }
}


?>