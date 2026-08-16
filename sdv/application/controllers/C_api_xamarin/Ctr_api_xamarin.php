<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
class Ctr_api_xamarin extends ControladorBase{
    function __construct(){
        parent::__construct();
        $this->load->model('M_api_xamarin/Mdl_api_xamarin','xa');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','security'));
    }
    public function index(){
        $this->global['pageTitle'] = 'Api';
    }
    public function Lista_clientes_XA(){
        $lati = '13.820672815364738';
        $long = '-89.1792306029424';
        $distancia = 0.5/1000;
        $lati = $this->input->get('lati');
        $long = $this->input->get('long');
        $distancia = $this->input->get('dist');
        $distancia = $distancia / 1000;
        $list = $this->xa->List_Clientes($lati,$long,$distancia);
        $arrg_List = array();
        $i = 0;$ii = 1;
        foreach ($list as $key ) {
            $ii = $i + 1;
            $distancia =  round($key->distance * 1000,2);
            $arrg_List[$i]['Cli_Id'] = $key->Cli_Id;
            $arrg_List[$i]['Cli_codigo'] = $key->Cli_codigo;
            $arrg_List[$i]['Cli_nombre'] = $ii.". ".$key->Cli_nombre." ( ".$distancia." m )";
            $arrg_List[$i]['Cli_direccion'] = $key->Cli_direccion;
            $arrg_List[$i]['Cli_latitud'] = $key->Cli_latitud;
            $arrg_List[$i]['Cli_longitud'] = $key->Cli_longitud;
            $arrg_List[$i]['distance'] = $distancia;
            $i++;

        }
        echo json_encode($arrg_List);
    }

    public function Lista_clientes_X_cli(){
        $Cli_Id = 1;
        $lati = $this->input->get('lati');
        $long = $this->input->get('long');
        $distancia = $this->input->get('dist');
        $Cli_Id = $this->input->get('IdCliente');
        $list = $this->xa->List_Clientes_X_Cli($lati,$long,$distancia,$Cli_Id);
        echo json_encode($list);
    }

    public function LoginUsApk(){

        $Usuario    = $this->input->get('usu');
        $Contrasena = $this->input->get('pass');
        $inicio_sesion = $this->xa->loginApk($Usuario,$Contrasena);
        $data = array();$d = 0;
        $mjs = '';
        if(count($inicio_sesion) == 0){
            echo json_encode([array(
                'Auntenticacion' => FALSE,
                'Mensaje' => 'Usuario y Contraseña incorrectos.',
                'usuEstado' => '',
                'UsuActContrasena' => '',
                'user' => '',
                // 'Data' => $data
            )]);

        }else{
            foreach ($inicio_sesion as $l) {
                // $data =  array(
                //     'user' => $l->user,
                //     'usuEstado' => $l->usuEstado,
                //     'UsuActContrasena' => $l->UsuActContrasena
                // );
                // $data[$d]['user'] = $l->user;
                // $data[$d]['usuEstado'] = $l->usuEstado;
                // $data[$d]['UsuActContrasena'] = $l->UsuActContrasena;
                if($l->UsuActContrasena == 0){
                    $mjs = 'Por favor cambiar contraseña...';
                }else{
                    $mjs = 'Usuario validado';
                }
                $d++;
            }
            $objeto = new stdClass();
            echo json_encode([ array(
                    'Auntenticacion' => TRUE,
                    'Mensaje' => $mjs,
                    'usuEstado' => $inicio_sesion[0]->usuEstado,
                    'UsuActContrasena' => $inicio_sesion[0]->UsuActContrasena,
                    'user' => $inicio_sesion[0]->user
                    // 'Data' => $data
                )
            ]);
        }
    }

    public function ModificarUsuario(){
        $usu = $this->input->get('usu');
        // $contrasenaActual = $this->input->get('pass');
        $contrasenaNueva  = $this->input->get('passn');
        $val = 0;$pass_nuevo = '';
        $data_resul = array();
        if(empty($usu))
            $val = 1;
        // if(empty($contrasenaActual))
        //     $val = 1;
        if(empty($contrasenaNueva))
            $val = 1;
        if($val == 0){
            $info = $this->xa->InfoUsuario($usu);
            if(isset($info[0]->usuEstado)){
                if($info[0]->usuEstado == 1){
                    if($info[0]->UsuActContrasena == 0){
                        // $inicio_sesion = $this->xa->loginApk($usu,$contrasenaActual);
                        // if(count($inicio_sesion) > 0){
                            $password_hash = password_hash($contrasenaNueva,PASSWORD_DEFAULT);
                            $updata = $this->xa->modificarUsuario(array('password' => $password_hash,'UsuActContrasena' => 1),$usu);
                            if($updata){
                                $data_resul = array(
                                    'Resultado' => TRUE,
                                    'Mensaje' => 'Contraseña modificada correctamente'
                                );
                            }else{
                                $data_resul = array(
                                    'Resultado' => FALSE,
                                    'Mensaje' => 'Error, sin cambios...'
                                );
                            }
                        // }else{
                        //     $data_resul = array(
                        //         'Resultado' => FALSE,
                        //         'Mensaje' => 'Contraseña actual incorrecta...'
                        //     );
                        // }
                    }else{
                        $data_resul = array(
                            'Resultado' => FALSE,
                            'Mensaje' => 'Usuario ya cambio su contraseña, petición bloqueada...'
                        );
                    }
                }else{
                    $data_resul = array(
                        'Resultado' => FALSE,
                        'Mensaje' => 'Usuario inactivo'
                    );
                }
            }else{
                $data_resul = array(
                    'Resultado' => FALSE,
                    'Mensaje' => 'Usuario no valido...'
                );
            }
        }else{
            $data_resul = array(
                'Resultado' => FALSE,
                'Mensaje' => 'Parametros incompletos...'
            );
        }
        echo json_encode([$data_resul]);
    }

    public function Lista_clientes_X_Zona(){
        $Zona = $this->input->get('zona');
        $list = $this->xa->ListaClienteXZona($Zona);
        echo json_encode($list);
    }
    
    public function Lista_usuariosOffline(){
        $list = $this->xa->loginApkFull();
        echo json_encode($list);
    }

    public function Modificar_usuariosOffline(){
        $user = $this->input->get('user');
        $password = $this->input->get('password');
        // $password = hash ( "sha256", $password );
        $updata = $this->xa->modificarUsuariOffline(array('password' => $password,'UsuActContrasena' => 1),$user);
        if($updata){
            $data_resul = array(
                'Resultado' => TRUE,
                'Mensaje' => 'Contraseña modificada correctamente'
            );
        }else{
            $data_resul = array(
                'Resultado' => FALSE,
                'Mensaje' => 'Error, sin cambios...'
            );
        }
        echo json_encode([$data_resul]);
    }



    public function Lista_censoPorUbicacion(){
        $lati = $this->input->get('lati');
        $long = $this->input->get('long');
        $distancia = $this->input->get('dist');
        $list = $this->xa->List_CensoPorDistancia($lati,$long,$distancia);
        echo json_encode($list);
    }


    public function Insertar_MatchCenso() {
        // Recuperar datos de la solicitud
        $ruta = $this->input->get('ruta');
        $CodigoCliente = $this->input->get('CodigoCliente');
        $Cc_nombre_negocio = $this->input->get('Cc_nombre_negocio');
        $latitud = $this->input->get('latitud');
        $longitud = $this->input->get('longitud');
        $Cc_id = $this->input->get('Cc_id');
        $fecha = $this->input->get('fecha');
        $Pais = $this->input->get('Pais'); // Recuperar la nueva variable
    
        // Validar que los parámetros no están vacíos
        if (empty($ruta) || empty($CodigoCliente) || empty($Cc_nombre_negocio) || 
            empty($latitud) || empty($longitud) || empty($Cc_id) || empty($fecha)) {
            $respuesta = array('status' => 'error', 'message' => 'Parámetros incompletos.');
            echo json_encode($respuesta);
            return;
        }
    
        // Validar que la fecha sea válida
        if (!strtotime($fecha)) {
            $respuesta = array('status' => 'error', 'message' => 'Fecha inválida.');
            echo json_encode($respuesta);
            return;
        }
    
        // Convertir la fecha a formato compatible con la base de datos (si es necesario)
        $fecha = date('Y-m-d H:i:s', strtotime($fecha));
    
        // Insertar los datos en la base de datos
        $exito = $this->xa->insertarMatchAppCenso($ruta, $CodigoCliente, $Cc_nombre_negocio, $latitud, $longitud, $Cc_id, $fecha,$Pais);
        
        // Preparar la respuesta
        if ($exito) {
            $respuesta = array('status' => 'success', 'message' => 'Registro insertado con éxito');
        } else {
            $respuesta = array('status' => 'error', 'message' => 'Error al insertar el registro');
        }
        
        // Devolver la respuesta como JSON
        echo json_encode($respuesta);
    }
    
    


    public function ActualizarCensoMaestroClientes() {
        // Recuperar datos de la solicitud
        $CodigoCliente = $this->input->get('CodigoCliente', true); // Escapar automáticamente
        $Cc_id = $this->input->get('Cc_id', true);
    
        // Validar que los parámetros no están vacíos
        if (empty($CodigoCliente) || $Cc_id === null) {
            $respuesta = array('status' => 'error', 'message' => 'Parámetros incompletos.');
            echo json_encode($respuesta);
            return;
        }
    
        // Actualizar los datos en la base de datos
        $exito = $this->xa->actualizarCliente($CodigoCliente, $Cc_id);
    
        // Preparar la respuesta
        if ($exito) {
            $respuesta = array('status' => 'success', 'message' => 'Registro actualizado con éxito');
        } else {
            $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro o no se realizaron cambios');
        }
    
        // Devolver la respuesta como JSON
        echo json_encode($respuesta);
    }
    
    

    public function ValidacionClienteXamarin() {
        // Recupera los parámetros 'id' y 'Ru_nombre' desde la solicitud GET
        $codigo = $this->input->get('id', true); 
        $Ru_nombre = $this->input->get('Ru_nombre', true); 
    
        // Verifica que ambos parámetros no estén vacíos
        if (empty($codigo) || empty($Ru_nombre)) {
            echo json_encode(array());
            return;
        }
    
        // Llama al método del modelo con ambos parámetros
        $list = $this->xa->ValidacionClienteXamarinController($codigo, $Ru_nombre);
    
        // Retorna el resultado en formato JSON
        echo json_encode($list);
    }
    

    public function ConsultaClienteVinculoCensoview() {
        // Recupera los parámetros 'id' y 'Ru_nombre' desde la solicitud GET
        $codigo = $this->input->get('id', true); 
    
    
        // Verifica que ambos parámetros no estén vacíos
        if (empty($codigo)) {
            echo json_encode(array());
            return;
        }
    
        // Llama al método del modelo con ambos parámetros
        $list = $this->xa->ConsultaClienteVinculoCenso($codigo);
    
        // Retorna el resultado en formato JSON
        echo json_encode($list);
    }

    
    public function ActualizaErrorCensoMaestroClientes() {
        // Recuperar datos de la solicitud
        $CodigoCliente = $this->input->get('id', true); // Escapar automáticamente
    
        // Validar que el parámetro no está vacío
        if (empty($CodigoCliente)) {
            $respuesta = array('status' => 'error', 'message' => 'Parámetros incompletos.');
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($respuesta));
            return;
        }
    
        // Validar el formato del parámetro (opcional, si el código tiene un formato esperado)
  
    
        // Llamar al método del modelo para actualizar el registro
        try { 
            $exito = $this->xa->actualizarClienteErrorVinculacion($CodigoCliente);
    
            // Preparar la respuesta según el resultado
            if ($exito) {
                $respuesta = array('status' => 'success', 'message' => 'Registro actualizado con éxito.');
            } else {
                $respuesta = array('status' => 'error', 'message' => 'Error al actualizar el registro o no se realizaron cambios.');
            }
        } catch (Exception $e) {
            // Manejar excepciones y errores del modelo
            $respuesta = array('status' => 'error', 'message' => 'Ocurrió un error: ' . $e->getMessage());
        }
    
        // Devolver la respuesta como JSON
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($respuesta));
    }
    

    public function ConsultaClientesPorRutaOfflineCenso() {
        // Recuperar el parámetro de la solicitud
        $Ruta = $this->input->get('Ruta', true); // Se asegura de recibir la variable de forma segura
    
        // Validar que el parámetro no está vacío
        if (empty($Ruta)) {
            $respuesta = array('status' => 'error', 'message' => 'Parámetro Ruta vacío o inválido.');
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($respuesta));
            return;
        }
            
        try { 
            // Ejecuta la consulta en el modelo
            $datos = $this->xa->ConsultaClientesPorRutaOfflineCenso($Ruta);
    
            // Si hay datos, enviarlos en JSON
            if (!empty($datos)) {
                $respuesta = array('status' => 'success', 'data' => $datos);
            } else {
                $respuesta = array('status' => 'error', 'message' => 'No se encontraron clientes para la ruta especificada.');
            }
        } catch (Exception $e) {
            // Capturar cualquier error y devolver un mensaje
            $respuesta = array('status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage());
        }
    
        // Enviar la respuesta en formato JSON
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($respuesta));
    }


    public function ConsultaBaseCensoPorPais() {
        // Recuperar el parámetro de la solicitud
        $Pais = $this->input->get('Pais', true); // Se asegura de recibir la variable de forma segura
    
        // Validar que el parámetro no está vacío
        if (empty($Pais)) {
            $respuesta = array('status' => 'error', 'message' => 'Parámetro Ruta vacío o inválido.');
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($respuesta));
            return;
        }
            
        try { 
            // Ejecuta la consulta en el modelo
            $datos = $this->xa->ConsultaCensoOFFLineBaseCensoPor_Pais($Pais);
    
            // Si hay datos, enviarlos en JSON
            if (!empty($datos)) {
                $respuesta = array('status' => 'success', 'data' => $datos);
            } else {
                $respuesta = array('status' => 'error', 'message' => 'No se encontraron clientes para la ruta especificada.');
            }
        } catch (Exception $e) {
            // Capturar cualquier error y devolver un mensaje
            $respuesta = array('status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage());
        }
    
        // Enviar la respuesta en formato JSON
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($respuesta));
    }
    

    public function ConsultaUsuarioLoginCenso() {
        // Recuperar el parámetro de la solicitud de forma segura (XSS filtering activado)
        $Usuario = $this->input->get('Usuario', true); 
    
        // Validar que el parámetro no esté vacío ni mal formado
        if (empty($Usuario) || !is_string($Usuario)) {
            $respuesta = array('status' => 'error', 'message' => 'Parámetro Usuario vacío o inválido.');
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($respuesta));
            return;
        }
    
        try { 
            // Ejecutar la consulta en el modelo
            $datos = $this->xa->ConsultaUsuarioLoginCensoController($Usuario);
    
            // Si hay datos, enviarlos en JSON
            if (!empty($datos)) {
                $respuesta = array('status' => 'success', 'data' => $datos);
            } else {
                $respuesta = array('status' => 'error', 'message' => 'No se encontraron datos del usuario.');
            }
        } catch (Exception $e) {
            // Capturar cualquier error y devolver un mensaje
            $respuesta = array('status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage());
        }
    
        // Enviar la respuesta en formato JSON
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($respuesta));
    }
    

}
?>