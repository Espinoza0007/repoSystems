<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
date_default_timezone_set('America/El_Salvador');
class Ctr_lat_log extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','security'));
    }

	function index(){
        $this->global['pageTitle'] = 'Actualizacion de coordenadas';
        $this->loadViews('Clientes/V_actualiza_lat_log',$this->global);
  	}
    function resultconexion(){
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
    function actualizacion_cli(){
        if($this->input->is_ajax_request()){
            $EstadoAC = intval($this->input->post('EstadoAC'));
            if($EstadoAC == 1){
                $this->form_validation->set_rules(
                    'txtnombre','<strong>Nombre del establecimiento</strong>',
                    'trim|required|min_length[4]|max_length[77]|regex_match[/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-. ]+$/]',
                    array(
                        'required' => 'El campo %s es obligatorio.',
                        'min_length' => 'El campo %s es muy corto.',
                        'max_length' => 'El campo %s no puede exceder los 45 caracteres.',
                        'regex_match' => 'El <strong>Nombre del Establecimiento</strong> esta mal escrito, por favor verifique (Solo se permiten letras y n&uacute;meros).'
                    )
                );
                $this->form_validation->set_rules(
                    'txtdireccion','<strong>Direccion</strong>',
                    'trim|required|min_length[25]|max_length[250]|regex_match[/^[A-Za-zÁÉÍÓÚñáéíóúÑ0-9#°\-.,\/ ]+$/]',
                    array(
                        'required' => 'El campo %s es obligatorio.',
                        'min_length' => 'El campo %s es muy corto proporcione Direccion.',
                        'max_length' => 'El campo %s no puede exceder los 25 caracteres de longitud.',
                        'regex_match' => 'La <strong>Direcci&oacute;n</strong> esta mal escrita, por favor verifique (Solo se permiten letras y n&uacute;meros).'
                    )
                );
                $this->form_validation->set_rules(
                    'txtcontacto','<strong>Nombre de Contacto</strong>',
                    'trim|required|min_length[6]|max_length[200]|regex_match[/^[A-Za-zÁÉÍÓÚñáéíóúÑ ]+$/]',
                    array(
                        'required' => 'El campo %s es obligatorio.',
                        'min_length' => 'El campo %s es muy corto proporcione Direccion.',
                        'max_length' => 'El campo %s no puede exceder los 25 caracteres de longitud.',
                        'regex_match' => 'En el campo %s solo se permiten letras.'
                    )
                );
                if($this->form_validation->run() == FALSE){
                    $resp = array(
                        'rs' => FALSE,
                        'errores' => validation_errors('<li>','</li>'),
                        'cla' => 'danger grDanguer'
                    );
                    echo json_encode($resp);
                    return;
                }else{
                    $Id_Cliente = $this->input->post('Id_Cliente');
                    $Codigo = mb_strtoupper(quitar_acentos($this->input->post('Codigo_Cli')));
                    $input_nombre = mb_strtoupper(quitar_acentos($this->input->post('txtnombre')));
                    $input_direccion = mb_strtoupper(quitar_acentos($this->input->post('txtdireccion')));
                    $input_contacto = mb_strtoupper(quitar_acentos($this->input->post('txtcontacto')));
                    $input_municipio = desencriptar_cadena($this->input->post('cbmunicipio'));
                    $input_telefono = mb_strtoupper(quitar_acentos($this->input->post('txtnumtelefono')));
                    $input_diavisita = $this->input->post('checkdiavisita');
                    $input_tfacturacion = desencriptar_cadena($this->input->post('cbtfacturacion'));
                    $input_frecuvisita = $this->input->post('cbfrecuenciavisita');
                    $input_ordenvisita = $this->input->post('txtordevisita');
                    $input_latitud = $this->input->post('txtlatitudAC');
                    $input_longitud = $this->input->post('txtlongitudAC');
                    $TipoUsuario = $this->input->post('TipoUsuario');
                    $txtUsModifica      = $this->input->post('us_modifica') == null || $this->input->post('us_modifica') == '' ? 0 : $this->input->post('us_modifica');
                    $input_cbrefrigerantes = $this->input->post('cbrefrigerantes');
                    if(empty($this->input->post('cbrefrigerantes'))){
                        $input_cbrefrigerantes = 0;
                    }
                    $fecha_actual = date('Y-m-d H:i:s');
                    $fechaEnDispositivo = $this->input->post('fechaEnDispositivo');
                    $Ruta_Nombre = $this->input->post('Ruta_Nombre');
                    $Ruta_NombreCP = $this->input->post('Ruta_NombreCP');
                    $Id_Usuario = desencriptar_cadena($this->input->post('Id_Usuario'));
                    $cadena_dias = "L_0,M_0,I_0,J_0,V_0,S_0,D_0";
                    $cadenasinestado = "";
                    $errores ='';
                    $input_gironegocio = desencriptar_cadena($this->input->post('cbgironegocio'));
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
                    $dataclienteEncuestado = array(
                        'Cli_ac_cliente' => 1
                    );
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
                    //CAMBIAR ESTADO CLIENTE CENSADO
                    $Id_Cliente = intval($Id_Cliente);
                    $clienteEncuestado = $this->cl->modificar_clientes($dataclienteEncuestado,$Id_Cliente);
                    $insertar = array(
                        'Actc_Cli_Id' => $Id_Cliente,
                        'Actc_Ruta' => $Ruta_NombreCP,
                        'Actc_codigo' => strval($Codigo),
                        'Actc_nombre' => strval($input_nombre),
                        'Actc_direccion' => strval($input_direccion),
                        'Actc_Mun_Id' => intval($input_municipio),
                        'Actc_Gir_Id' => $input_gironegocio,
                        'Actc_telefono' => strval($input_telefono),
                        'Actc_contacto' => strval($input_contacto),
                        'Actc_dui' => strval($input_dui),
                        'Actc_num_registro' => strval($input_numcontribuyente),
                        'Actc_nit' => strval($input_nit),
                        'Actc_l' => substr($cadena_dias[0], -1),
                        'Actc_m' => substr($cadena_dias[1], -1),
                        'Actc_mi' => substr($cadena_dias[2], -1),
                        'Actc_j' => substr($cadena_dias[3], -1),
                        'Actc_v' => substr($cadena_dias[4], -1),
                        'Actc_s' => substr($cadena_dias[5], -1),
                        'Actc_d' => substr($cadena_dias[6], -1),
                        'Actc_orden_l' => $ord_l,
                        'Actc_orden_m' => $ord_m,
                        'Actc_orden_mi' => $ord_i,
                        'Actc_orden_j' => $ord_j,
                        'Actc_orden_v' => $ord_v,
                        'Actc_orden_s' => $ord_s,
                        'Actc_orden_d' => $ord_d,
                        'Actc_frecuencia_visita' => strval($input_frecuvisita),
                        'Actc_latitud' => strval($input_latitud),
                        'Actc_longitud' => strval($input_longitud),
                        'Actc_fecha_telefono' => $fechaEnDispositivo,
                        'Actc_fecha_servidor' => $fecha_actual,
                        'Actc_estado_registro' => intval($EstadoAC),
                        'Actc_estado_analista' => 'N',
                        'Actc_estado_supervisor' => 'N',
                        'Actc_estado_descarga' => 0,
                        'Actc_fecha_supervisor' => NULL,
                        'Actc_fecha_analista' => NULL,
                        'Actc_fecha_descarga' => NULL,
                        'Actc_fecha_ult_modi' => $fecha_actual,
                        'Actc_quien_modifico' => $Ruta_Nombre,
                        'Actc_motivo' => NULL,
                        'Actc_usuario' => intval($txtUsModifica),
                        'Actc_cantidad_CMR' => $input_cbrefrigerantes
                    );
                    $inserdata=$this->cl->guardar_actualizacionCLI($insertar);
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
                            'info' => 'Ocurrio un error durante el proceso.',
                            'cla' => 'success grDanguer'
                            )
                        );
                    }
                }

            }else{
                /*CLIENTE INACTIVOS*/
                $Id_Cliente = $this->input->post('Id_Cliente');
                $Codigo = mb_strtoupper(quitar_acentos($this->input->post('Codigo_Cli')));
                $input_nombre = mb_strtoupper(quitar_acentos($this->input->post('txtnombre')));
                $input_direccion = mb_strtoupper(quitar_acentos($this->input->post('txtdireccion')));
                $input_contacto = mb_strtoupper(quitar_acentos($this->input->post('txtcontacto')));
                $input_municipio = '';
                $input_telefono = mb_strtoupper(quitar_acentos($this->input->post('txttelefono')));
                $input_diavisita = $this->input->post('checkdiavisita');
                $input_tfacturacion = desencriptar_cadena($this->input->post('cbtfacturacion'));
                $input_frecuvisita = $this->input->post('cbfrecuenciavisita');
                $input_ordenvisita = $this->input->post('txtordevisita');
                $input_latitud = $this->input->post('txtlatitudAC');
                $input_longitud = $this->input->post('txtlongitudAC');
                $TipoUsuario = $this->input->post('TipoUsuario');
                $txtUsModifica      = $this->input->post('us_modifica') == null || $this->input->post('us_modifica') == '' ? 0 : $this->input->post('us_modifica');
                $input_cbrefrigerantes = $this->input->post('cbrefrigerantes');
                $input_gironegocio = desencriptar_cadena($this->input->post('cbgironegocio'));
                if(empty($this->input->post('cbrefrigerantes'))){
                    $input_cbrefrigerantes = 0;
                }
                if(empty($input_telefono)){
                    $input_telefono  = '0000-0000';
                }
                if(empty($this->input->post('cbmunicipio'))){
                    $input_municipio  = 263;
                }else{
                    $input_municipio = desencriptar_cadena($this->input->post('cbmunicipio'));
                }
                $fecha_actual = date('Y-m-d H:i:s');
                $fechaEnDispositivo = $this->input->post('fechaEnDispositivo');
                $Ruta_Nombre = $this->input->post('Ruta_Nombre');
                $Ruta_NombreCP = $this->input->post('Ruta_NombreCP');
                $Id_Usuario = $this->input->post('Id_Usuario');
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
                if (strcmp($input_tfacturacion, 2) == 0) {
                    $input_dui = strval($this->input->post('txtdui'));
                    $input_numcontribuyente = strval($this->input->post('txtnumcontribuyente'));
                    $input_nit = intval($this->input->post('txtnit'));
                    if(empty($input_nit)){
                        $input_nit = '';
                    }
                }else{
                    $input_dui = strval($this->input->post('txtdui'));
                    $input_numcontribuyente = '';
                    $input_nit = strval($this->input->post('txtnit'));
                }
                $dataclienteEncuestado = array(
                    'Cli_ac_cliente' => 1
                );
                $ord_l = 1;$ord_m = 1;$ord_i = 1;$ord_j = 1;$ord_v = 1;$ord_s = 1;$ord_d = 1;
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
                //CAMBIAR ESTADO CLIENTE CENSADO
                $Id_Cliente = intval($Id_Cliente);
                $clienteEncuestado = $this->cl->modificar_clientes($dataclienteEncuestado,$Id_Cliente);
                $ls_Mun_IdDefault = $this->cl->get_Mun_Default($Id_Cliente);
                if(count($ls_Mun_IdDefault)>0)
                    foreach ($ls_Mun_IdDefault as $ct){$Mun_IdDefault = $ct->Mun_Id;}
                else
                    $Mun_IdDefault = 263;
                $cadena_dias = explode(",", $cadena_dias);
                $insertar = array(
                    'Actc_Cli_Id' => $Id_Cliente,
                    'Actc_Ruta' => $Ruta_NombreCP,
                    'Actc_codigo' => strval($Codigo),
                    'Actc_nombre' => strval($input_nombre),
                    'Actc_direccion' => strval($input_direccion),
                    'Actc_Mun_Id' => intval($Mun_IdDefault),
                    'Actc_Gir_Id' => $input_gironegocio,
                    'Actc_telefono' => strval($input_telefono),
                    'Actc_contacto' => strval($input_contacto),
                    'Actc_dui' => strval($input_dui),
                    'Actc_num_registro' => strval($input_numcontribuyente),
                    'Actc_nit' => strval($input_nit),
                    'Actc_l' => substr($cadena_dias[0], -1),
                    'Actc_m' => substr($cadena_dias[1], -1),
                    'Actc_mi' => substr($cadena_dias[2], -1),
                    'Actc_j' => substr($cadena_dias[3], -1),
                    'Actc_v' => substr($cadena_dias[4], -1),
                    'Actc_s' => substr($cadena_dias[5], -1),
                    'Actc_d' => substr($cadena_dias[6], -1),
                    'Actc_orden_l' => $ord_l,
                    'Actc_orden_m' => $ord_m,
                    'Actc_orden_mi' => $ord_i,
                    'Actc_orden_j' => $ord_j,
                    'Actc_orden_v' => $ord_v,
                    'Actc_orden_s' => $ord_s,
                    'Actc_orden_d' => $ord_d,
                    'Actc_frecuencia_visita' => strval($input_frecuvisita),
                    'Actc_latitud' => strval($input_latitud),
                    'Actc_longitud' => strval($input_longitud),
                    'Actc_fecha_telefono' => $fechaEnDispositivo,
                    'Actc_fecha_servidor' => $fecha_actual,
                    'Actc_estado_registro' => intval($EstadoAC),
                    'Actc_estado_analista' => 'N',
                    'Actc_estado_supervisor' => 'N',
                    'Actc_estado_descarga' => 0,
                    'Actc_fecha_supervisor' => NULL,
                    'Actc_fecha_analista' => NULL,
                    'Actc_fecha_descarga' => NULL,
                    'Actc_fecha_ult_modi' => $fecha_actual,
                    'Actc_quien_modifico' => $Ruta_Nombre,
                    'Actc_motivo' => strval($this->input->post('MotivoAC')),
                    'Actc_usuario' => intval($txtUsModifica),
                    'Actc_cantidad_CMR' => $input_cbrefrigerantes
                );
                $inserdata=$this->cl->guardar_actualizacionCLI($insertar);
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
                        'info' => 'Ocurrio un error durante el proceso.',
                        'cla' => 'success grDanguer'
                        )
                    );
                }
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
    }

    function leer_clientes(){
        if($this->input->is_ajax_request()){
            $limit = 5;
            $adjacent = 1;
            $page = $this->input->post('page');
            if($page==1){
                $start = 0;
            }else{
                $start = ($page-1)*$limit;
            }
            $usuarioactual = $this->session->userdata("codusuario");
            $usuarioactual = desencriptar_cadena($usuarioactual);
            $arrlistas = array();
            $lista_clientes_obt = $this->ls->lista_clientes_ruta($limit,$start,$usuarioactual);
            $arreglo_cli = array();
            $cod_encriptado_cli = '';
            $i=0;
            foreach ($lista_clientes_obt as $ct){
                $cod_encriptado_cli = encriptar_cadena($ct->Id_Cliente);
                $arreglo_cli[$i]['codiclient'] = $cod_encriptado_cli;
                $arreglo_cli[$i]['codigo'] = $ct->Codigo;
                $arreglo_cli[$i]['nombre'] = $ct->Nombre;
                $arreglo_cli[$i]['contacto'] = $ct->Contacto;
                $arreglo_cli[$i]['direccion'] = $ct->Direccion;
                $arreglo_cli[$i]['telefono'] = $ct->Telefono;
                $arreglo_cli[$i]['Latitud'] = $ct->Latitud;
                $arreglo_cli[$i]['Longitud'] = $ct->Longitud;
                $i++;
            }
            $resultcanti=0;
            $c_clientes = $this->ls->contar_clientes_ruta($usuarioactual);
            $resultcanti = $c_clientes->totolus;

            $pagina_insertar = $this->paginationcon($limit,$adjacent,$resultcanti,$page);
            $resp = array(
                'rs' => TRUE,
                'listaclientes' => $arreglo_cli,
                'resultcanti' => $resultcanti,
                'pagina_insertar' =>  $pagina_insertar
            );
            echo json_encode($resp);
            return;
        }else{
            $resp = array(
                'rs' => FALSE
            );
            echo json_encode($resp);
            return;
        }
    }

    function leer_clientes_AC(){
        if($this->input->is_ajax_request()){
            $limit = 5;
            $adjacent = 1;
            $page = $this->input->post('page');
            if($page==1){
                $start = 0;
            }else{
                $start = ($page-1)*$limit;
            }
            $usuarioactual = $this->session->userdata("codusuario");
            $usuarioactual = desencriptar_cadena($usuarioactual);
            $arrlistas = array();
            $lista_clientes_obt = $this->ls->lista_clientes_ruta_AC($limit,$start,$usuarioactual);
            $arreglo_cli = array();
            $cod_encriptado_cli = '';
            $i=0;
            foreach ($lista_clientes_obt as $ct){
                $cod_encriptado_cli = encriptar_cadena($ct->Id_Actu_Info_Cli);
                $arreglo_cli[$i]['codiclient'] = $cod_encriptado_cli;
                $arreglo_cli[$i]['codigo'] = $ct->Codigo_Cliente;
                $arreglo_cli[$i]['nombre'] = $ct->Nombre;
                $arreglo_cli[$i]['contacto'] = $ct->Contacto;
                $arreglo_cli[$i]['direccion'] = $ct->Direccion;
                $arreglo_cli[$i]['telefono'] = $ct->Telefono;
                $arreglo_cli[$i]['Latitud'] = $ct->Latitud;
                $arreglo_cli[$i]['Longitud'] = $ct->Longitud;
                $i++;
            }
            $resultcanti=0;
            $c_clientes = $this->ls->contar_clientes_ruta_AC($usuarioactual);
            $resultcanti = $c_clientes->totolus;

            $pagina_insertar = $this->paginationcon($limit,$adjacent,$resultcanti,$page);
            $resp = array(
                'rs' => TRUE,
                'listaclientes' => $arreglo_cli,
                'resultcanti' => $resultcanti,
                'pagina_insertar' =>  $pagina_insertar
            );
            echo json_encode($resp);
            return;
        }else{
            $resp = array(
                'rs' => FALSE
            );
            echo json_encode($resp);
            return;
        }
    }

    function cuadro_cantidades_cli(){
        if($this->input->is_ajax_request()){

            $usuarioactual = $this->session->userdata("codusuario");
            $usuarioactual = desencriptar_cadena($usuarioactual);

            /*TOTAL DE CLIENTES POR RUTA*/
            $total_general=0;
            $cli_t_general = $this->ls->total_clientes_ruta($usuarioactual);
            $total_general = $cli_t_general->totolus;
            /*TOTAL DE CLIENTES ACTUALIZADOS*/
            $total_c_actualizar=0;
            $cli_c_actualizar = $this->ls->contar_clientes_ruta_AC($usuarioactual);
            $total_c_actualizar = $cli_c_actualizar->totolus;
            /*TOTAL DE CLIENTES SIN ACTUALIZAR*/
            $total_s_actualizar=0;
            $cli_s_actualizar = $this->ls->contar_clientes_ruta($usuarioactual);
            $total_s_actualizar = $cli_s_actualizar->totolus;

            $resp = array(
                'rs' => TRUE,
                'total_general' => $total_general,
                'total_c_actualizar' => $total_c_actualizar,
                'total_s_actualizar' => $total_s_actualizar
            );
            echo json_encode($resp);
            return;
        }else{
            $resp = array(
                'rs' => FALSE
            );
            echo json_encode($resp);
            return;
        }
    }

    function paginationcon($limit,$adjacents,$t,$page){
        $pagination='';
        if ($page == 0) $page = 1;              
        $prev = $page - 1;                          
        $next = $page + 1;                          
        $prev_='';
        $first='';
        $lastpage = ceil($t/$limit);    
        $next_='';
        $last='';
        if($lastpage > 1)
        {   
            if ($page > 1) 
                $prev_.= "<a class='page-numbersdo' href=\"?page=$prev\">Anterior</a>";
            else{ }
            
            if ($lastpage < 5 + ($adjacents * 2))
            {   
            $first='';
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-numbersdo' href=\"?page=$counter\">$counter</a>";                   
                }
                $last='';
            }
            elseif($lastpage > 3 + ($adjacents * 2))
            {
                $first='';
                if($page < 1 + ($adjacents * 2))        
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-numbersdo' href=\"?page=$counter\">$counter</a>";                   
                    }
                $last.= "<a class='page-numbersdo' href=\"?page=$lastpage\">Final</a>";            
                }
                
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $first.= "<a class='page-numbersdo' href=\"?page=1\">Principio</a>";  
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-numbersdo' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last.= "<a class='page-numbersdo' href=\"?page=$lastpage\">Final</a>";            
                }    
                else
                {
                    $first.= "<a class='page-numbersdo' href=\"?page=1\">Principio</a>";  
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-numbersdo' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last='';
                }   
            }
            if ($page < $counter - 1) 
                $next_.= "<a class='page-numbersdo' href=\"?page=$next\">Siguiente</a>";
            else{ }
            $pagination = "<div class=\"pagination\">".$first.$prev_.$pagination.$next_.$last;
            
            $pagination.= "</div>\n";       
        }
        return $pagination;   
    }



}

?>