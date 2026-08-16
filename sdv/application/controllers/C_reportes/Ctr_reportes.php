<?php
ini_set('memory_limit', '-1');
set_time_limit(999);
date_default_timezone_set('America/El_Salvador');
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
require 'phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Ctr_reportes extends ControladorBase
{
    function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->model('M_usuarios/Mdl_login','lg');
        $this->load->model('M_clientes/Mdl_pruebas','k');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }

    function index(){
        if($this->session->userdata('tipousuario')){
            if(strcmp ($this->session->userdata('tipousuario'), 'ADMINISTRADORES') == 0 ){
                $this->global['pageTitle'] = 'Generar Plantilla';
                $this->loadViews_admin_anl('Reportes/V_plantilla',$this->global);
            }else{
                redirect('../../sdv/', 'refresh');
            }
        }else{
            redirect('../../sdv/', 'refresh');
        }
    }
    /*RESOLUCION DE CLIENTES ACTUALIZADOS CAMBIOS 30/03/2021*/
    function ok_resolucion_k(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'tiporesolucion','<strong>Resolucion</strong>',
            'trim|required',
                array(
                    'required' => 'El campo %s es obligatorio.'
                )
            );

            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';
                echo json_encode($resp);
                return;
            }else{
                // $resolucion = '';

                // $resolucion = $this->input->post('resolucion');
                // $codecliente = $this->input->post('codecliente');
                // $IdCliente = $this->input->post('idcli');
                
                // $codecliente = desencriptar_cadena($codecliente);
                $fecha_actual = date('Y-m-d H:i:s');
                $re = '';
                $arrg_UP_Ordenar = [];


                        /*REORDENANDO LOS DIAS DE VISITA POR CLIENTE*/

                        // //CLIENTE APROBADO POR EL ANALISTA
                        // $Id_Ruta = 0;$Id_Usuarios = 0;$IdCliente_DiasVis = 0;
                        // $IdCliente_DiasVis = $IdCliente;$Id_Ruta = 1;$Id_Usuarios = $this->input->post('idusuarios');$dias_separados = '';$conta_OK = 0;
                        // $conta_UNO = 0;
                        // $arrg_diasBuscar = array();$arrg_UP_Ordenar = array();
                        // /* CONSULTANDO DÍAS DE VISITA DE CLIENTE APROBADO*/

                        // // echo "Id_Cliente => ".$IdCliente_DiasVis."<br>";
                        // $ls_arrg_dias = $this->k->dias_VisitaXCliente($IdCliente_DiasVis);
                        
                        // if(!empty($ls_arrg_dias)){

                        //     $dias_separados = explode(',',$ls_arrg_dias->Dias);

                        //     // var_dump($dias_separados);
                        //     /*
                        //         Error Orden Visita [ 1 ] => Cantidad de Elementos de Array No Coincide Con el Formato Correcto L_0,M_1,I_0,J_0,V_1,S_0,D_0
                        //         ---------------------------------------------------------------------------------------------------------------------------
                        //         Error Orden Visita [ 2 ] => El Formato de los dias en Array es incorrecto Correcto L_0,M_1,I_0,J_0,V_1,S_0,D_0
                        //         ---------------------------------------------------------------------------------------------------------------------------
                        //         Error Orden Visita Dias [ 3 ] => El Formato de los orden dias en Array es incorrecto Correcto 1,2,9,1,1,34,1
                        //         ---------------------------------------------------------------------------------------------------------------------------
                        //     */
                        //     if(count($dias_separados) < 7){
                        //         echo json_encode(array(
                        //             'rs' => FALSE,
                        //             'errores' => 'Error Orden Visita [ 1 ], Formato de Días Incorrecto Para Este Cliente...<br>Informar a Sistemas de Venta',
                        //             'cla' => 'danger grDanguer'
                        //             )
                        //         );
                        //         return;
                        //     }else{
                        //         if ( strcmp($dias_separados[0], 'L_1') == 0 || strcmp($dias_separados[0], 'L_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[1], 'M_1') == 0 || strcmp($dias_separados[1], 'M_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[2], 'I_1') == 0 || strcmp($dias_separados[2], 'I_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[3], 'J_1') == 0 || strcmp($dias_separados[3], 'J_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[4], 'V_1') == 0 || strcmp($dias_separados[4], 'V_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[5], 'S_1') == 0 || strcmp($dias_separados[5], 'S_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[6], 'D_1') == 0 || strcmp($dias_separados[6], 'D_0') == 0)
                        //             $conta_OK++;
                        //     }
                        //     if( $conta_OK === 7 ) {
                        //         // echo "TODO CORRECTO";
                        //         /* EXTRAYENDO DIAS DE VISITA PARA CONSULTAR LOS CLIENTES DE ESE DIA DE LA RUTA SELECCIONADO*/
                        //         if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'L_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[1], 'M_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'M_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[2], 'I_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'I_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[3], 'J_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'J_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[4], 'V_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'V_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[5], 'S_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'S_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[6], 'D_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'D_1';
                        //             $conta_UNO++;
                        //         }

                        //         /*AQUI FALTA FOR PARA RECORRER DIAS EN ESTADO 1*/
                        //         // echo var_dump($arrg_diasBuscar);
                        //         // echo "<br>";
                        //         // echo $arrg_diasBuscar[0]."<br>";
                        //         $totalBus = 0;
                        //         $totalBus = count($arrg_diasBuscar);
                        //         $kik = 0;
                        //         foreach ($arrg_diasBuscar as $diaSelec){
                                    
                        //             // echo "ORDENANDO DIA => ".$diaSelec."<br>";
                        //             // echo "----------------------------------------------------------<br>";
                        //             $ls_client_dsordanado = $this->k->ls_clientesXdia($Id_Usuarios,$diaSelec);
                        //             $arrg_UP_Ordenar = [];

                        //             $cuenta_orden = 1;$cambio_OrdenDia = '';$nuevo_orden = '';
                        //             foreach ($ls_client_dsordanado as $d){
                                       
                        //                 $cambio_OrdenDia = $d->Ord_VisitaSema;
                        //                 $cambio_OrdenDia = explode(',',$cambio_OrdenDia);

                        //                 if( count($cambio_OrdenDia) < 7 ){
                        //                     echo json_encode(array(
                        //                         'rs' => FALSE,
                        //                         'errores' => 'Error Orden Visita Dias [ 3 ], Formato de Orden Días Incorrecto Para Este Cliente [ '.$d->Id_Cliente.' ]<br>Informar a Sistemas de Venta',
                        //                         'cla' => 'danger grDanguer'
                        //                         )
                        //                     );
                        //                     return;
                        //                 }else{
                                            
                        //                     if ( strcmp($diaSelec, 'L_1') == 0 )
                        //                         $nuevo_orden = $cuenta_orden.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'M_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$cuenta_orden.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'I_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$cuenta_orden.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'J_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$cuenta_orden.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'V_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$cuenta_orden.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'S_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$cuenta_orden.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'D_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$cuenta_orden;
                                            
                        //                     $cuenta_orden++;
                        //                     $arrg_UP_Ordenar[] = [
                        //                         'Id_Cliente' => $d->Id_Cliente,
                        //                         'Ord_VisitaSema' => $nuevo_orden
                        //                     ];
                        //                 }
                        //             }

                        //             // var_dump($arrg_UP_Ordenar);
                        //             $this->k->ordenamiento_completo($arrg_UP_Ordenar);
                        //             $kik++;
                        //         }


                        //     }else{
                        //         echo json_encode(array(
                        //             'rs' => FALSE,
                        //             'errores' => 'Error Orden Visita [ 2 ] , Formato de Días Incorrecto Para Este Cliente...<br>Informar a Sistemas de Venta',
                        //             'cla' => 'danger grDanguer'
                        //             )
                        //         );
                        //         return;
                        //     }
                        // }else{
                        //     echo json_encode(array(
                        //         'rs' => FALSE,
                        //         'errores' => 'ERROR Cliente no encontrado [ '.$IdCliente_DiasVis.' ]',
                        //         'cla' => 'danger grDanguer'
                        //         )
                        //     );
                        //     return;
                        // }

                        /*FINAL REORDENANDO LOS DIAS DE VISITA POR CLIENTE*/

                    // if($kik == $totalBus){

                        // $modificar_c = array(
                        //     'EstadoAAnalista' => $re,
                        //     'FechaAAnalista' => $fecha_actual,
                        //     'QuienAutorizo' => $this->session->userdata('nombrecompleto')
                        // );

                        // $modificardata = $this->cl->modificar_actualizacionCLi($modificar_c,$codecliente);
                        // $modificardata = true;

                        $resoluciones = $this->input->post("resoluciones");
                        $tipoR = $this->input->post("tiporesolucion");$resolucionAnalista = '';
                        if($tipoR == 1){
                            $resolucionAnalista = 'A';
                        }else{
                            $resolucionAnalista = 'R';
                        }
                        $tot_arrg = 0;
                        $tot_arrg = count($resoluciones);
                        $arrg_ResolucionAC = array();
                        if($tot_arrg > 0){

                            for ($k=0; $k < $tot_arrg; $k++) { 
                                $arrg_ResolucionAC[] = [
                                    'Id_AC' => $resoluciones[$k],
                                    'EstadoAAnalista' => $resolucionAnalista,
                                    'FechaAAnalista' => $fecha_actual,
                                    'QuienAutorizo' => $this->session->userdata('nombrecompleto')
                                ];
                            }

                        }else{
                            echo json_encode(array(
                                'rs' => FALSE,
                                'errores' => 'No se recibio ninguna resolucion de cliente actualizado',
                                'cla' => 'success grDanguer'
                                )
                            );
                        }
                        $totalAActualizar = 0;
                        $totalAActualizar = count($arrg_ResolucionAC);
                        $modificardata = $this->cl->ProcesoResolAc($arrg_ResolucionAC);
                        if($totalAActualizar == $modificardata){
                            echo json_encode(array(
                                'rs' => TRUE,
                                'info' => ' El registro se modifico correctamente.',
                                'cla' => 'success grSuccess'
                                )
                            );
                        }else{
                            echo json_encode(array(
                                'rs' => FALSE,
                                'errores' => ' Ocurrio un error en el proceso. BACK END',
                                'cla' => 'success grDanguer'
                                )
                            );
                        }
                        return;

                    // }else{

                    //     echo json_encode(array(
                    //         'rs' => FALSE,
                    //         'errores' => 'ERROR, PROCESO INCOMPLETO ORDEN DE VISITA',
                    //         'cla' => 'danger grDanguer'
                    //         )
                    //     );
                    //     return;

                    // }



            }//Form validation Codeigniter

        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso...FRONT END',
                'cla' => 'success grDanguer'
                )
            );
            return;
        }
    }

    function verificacion_actualizadosCLI(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'page','<strong>Error en numero de p&aacute;gina</strong>',
            'trim|required',
                array(
                    'required'  => 'Ocurrio un error inesperado...'
                )
            );

            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $toto = 0;
            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';

                echo json_encode($resp);
                return;
            }else{
                $mjs_resultados = '';
                $arrlistas = array();
                $limit = 10;
                $adjacent = 1;
                $page = $this->input->post('page');
    

                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                // $fechadesde = $this->input->post('datepicker');
                // $fechahasta = $this->input->post('datepickerdos');
                $idrutas = '';
                if(!empty($this->input->post('cbrutas_s'))){
                    $idrutas = desencriptar_cadena($this->input->post('cbrutas_s'));
                }else{
                    $idrutas = '';
                }
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                $arrgdistribuidoras = '';
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');

                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();

                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }

                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }
                    }

                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);

                }


                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    'rutas' => $idrutas,
                    'distribuidoras' => $arrgdistribuidoras
                );
                $tot_clientes = $this->cl->contar_clientesSu_ACAD($param_busqueda);
                $toto = $tot_clientes->totolu;

          
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                0000000-LLENANDO ARREGLOS PARA LAS LISTAS DE CLIENTES-00000000000000000
                00000000000000000000000000000000000000000000000000000000000000000000000
                */

                /*SIN ENCUESTAS*/
                $obt_clientes = $this->cl->lista_ClientesACAD($param_busqueda,$start,$limit);

                // $toto = count($obt_clientes);
                $tabla_clientes =  array();
                $s=0;
                foreach ($obt_clientes as $sin ){
                   
                    $tabla_clientes[$s]['ruta'] = $sin->Nombre_Ruta;
                    $tabla_clientes[$s]['nombrecliente'] = $sin->NombreAC;
                    $tabla_clientes[$s]['direccion'] = $sin->DireccionAC;
                    $tabla_clientes[$s]['telefono'] = $sin->TelefonoAC;
                    $tabla_clientes[$s]['contacto'] = $sin->ContactoAC;
                    $tabla_clientes[$s]['dias'] = $sin->DiasAC;
                    $tabla_clientes[$s]['ordenvisita'] = $sin->OrdenVistaAC;
                    $tabla_clientes[$s]['frecuencia'] = $sin->FrecuencVisitaAC;
                    $tabla_clientes[$s]['departamento'] = $sin->NombreDepartamento;
                    $tabla_clientes[$s]['municipio'] = $sin->NombreMunicipio;
                    $tabla_clientes[$s]['idmunicipio'] = $sin->Id_Municipio;
                    $tabla_clientes[$s]['iddepartamento'] = $sin->Id_Departamento;
                    $tabla_clientes[$s]['lati'] = $sin->LatitudAC;
                    $tabla_clientes[$s]['long'] = $sin->LongitudAC;
                    $tabla_clientes[$s]['Estado'] = $sin->EstadoAC;
                    $tabla_clientes[$s]['codruta'] = $sin->Id_Ruta;
                    $tabla_clientes[$s]['id_cliente'] = $sin->Id_Cliente;
                    $tabla_clientes[$s]['codcli'] = $sin->CodigoAC;
                    $tabla_clientes[$s]['IdcliRk'] = $sin->Id_AC;
                    $tabla_clientes[$s]['Id_Usuarios'] = $sin->Id_Usuarios;
                    $tabla_clientes[$s]['Ord_VisitaSema'] = $sin->Ord_VisitaSema;
                    $s++;
                }


                $pagina_insertar = $this->pagination_TablaClteCensados($limit,$adjacent,$toto,$page);

                /*---------------------------------------------------------------*/
                /*--------------------RUTAS SEGUN SUPERVISOR---------------------*/
                /*---------------------------------------------------------------*/
                $codsupervisor = '';
                $codsupervisor = $this->session->userdata('idsupervisor');
               


                $arrlistas['rs'] = TRUE;
                $arrlistas['paginacionsin'] = $pagina_insertar;
                $arrlistas['total'] = $toto;
                $arrlistas['ltclientesAC'] = $tabla_clientes;
                echo json_encode($arrlistas);
                return;
        
            }

        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
            return;
        }
    }


    function Ver_ClienteAC(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'idx_cliente','<strong>Código Cliente</strong>',
            'trim|required',
                array(
                    'required'  => 'Ocurrio un error inesperado...'
                )
            );

            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';
                echo json_encode($resp);
                return;
            }else{

                $idSeleccionado = '';
                $idmunicipioSele = '';
                $iddepartamentoSele = '';
                $paisSele = '';
                $idSeleccionado = $this->input->post("idx_cliente");
                $idmunicipioSele = $this->input->post("idx_municipio");
                $iddepartamentoSele = $this->input->post("idx_departamento");
                $paisSele = $this->session->userdata("pais");
                $obt_clientes = $this->cl->cliente_seleccionadoAC($idSeleccionado);
                $obt_departamentos = $this->cl->departamentoAC($paisSele);
                $obt_municipios = $this->cl->municipioAC($paisSele,$iddepartamentoSele);
                $obt_tipofacturacion = $this->cl->tipofacturacionAC();
                
                $arrlistas['rs'] = TRUE;
                $arrlistas['clienteAC'] = $obt_clientes;
                $arrlistas['DepartamentosAC'] = $obt_departamentos;
                $arrlistas['MunicipiosAC'] = $obt_municipios;
                $arrlistas['TipoFacAC'] = $obt_tipofacturacion;
                $arrlistas['PaisAC'] = $paisSele;

                echo json_encode($arrlistas);
                return;
        
            }

        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
            return;
        }
    }
    function procesar_codigos(){
        $result = 0;
        $fecha_actual = date('Y_m_d_h_i_s');
        $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if(in_array($_FILES["plantilla_xls"]["type"],$allowedFileType)){
            // $targetPath = '/var/www/html/Uploads/Plantilla_Excel/UpDataCli/'.'UPDATA_CODE_'.$fecha_actual.'_'.$_FILES['plantilla_xls']['name'];
            $targetPath = '../Uploads/Plantilla_Excel/UpDataCli/'.'UPDATA_CODE_'.$fecha_actual.'_'.$_FILES['plantilla_xls']['name'];
            if(move_uploaded_file($_FILES['plantilla_xls']['tmp_name'], $targetPath)){
                $documento = IOFactory::load($targetPath);
                $hojaActual = $documento->getSheet(0);
                $highestRow = $hojaActual->getHighestRow();

                $highestRow = $hojaActual->getHighestRow();
                // $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
                $total_registros = $highestRow -1;
                $arrgaupdate = array();
                $arrgaupdate_NB = array();
                if (strcmp($hojaActual->getHighestColumn(), "AZ") == 0) {

                    $arrgaupdate = array();
                    $contarrg = 0;
                    for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {
                        $arrgaupdate[] = [
                            'Id_Cliente' => trim(strval($hojaActual->getCell("AY".$indiceHoja)->getCalculatedValue())),
                            'Codigo' => trim(strval($hojaActual->getCell("B".$indiceHoja)->getCalculatedValue())),
                            'Nombre' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("C".$indiceHoja)->getCalculatedValue()))))),
                            'Direccion' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("D".$indiceHoja)->getCalculatedValue()))))),
                            'Telefono' => trim(strval($hojaActual->getCell("E".$indiceHoja)->getCalculatedValue())),
                            'Contacto' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("G".$indiceHoja)->getCalculatedValue()))))),
                            'Dui' => trim(strval($hojaActual->getCell("V".$indiceHoja)->getCalculatedValue())),
                            'Latitud' => trim(strval($hojaActual->getCell("I".$indiceHoja)->getCalculatedValue())),
                            'Longitud' => trim(strval($hojaActual->getCell("H".$indiceHoja)->getCalculatedValue()))
                        ];
                        $token_Sincro = '';
                        $token_Sincro = str_replace("K", "",trim(strval($hojaActual->getCell("AZ".$indiceHoja)->getCalculatedValue())));
                        $arrgaupdate_NB[] = [
                            'Cli_token' => $token_Sincro,
                            'Cli_codigo' => trim(strval($hojaActual->getCell("B".$indiceHoja)->getCalculatedValue())),
                            'Cli_nombre' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("C".$indiceHoja)->getCalculatedValue()))))),
                            'Cli_direccion' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("D".$indiceHoja)->getCalculatedValue()))))),
                            'Cli_telefono' => trim(strval($hojaActual->getCell("E".$indiceHoja)->getCalculatedValue())),
                            'Cli_contacto' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("G".$indiceHoja)->getCalculatedValue()))))),
                            'Cli_dui' => trim(strval($hojaActual->getCell("V".$indiceHoja)->getCalculatedValue())),
                            'Cli_num_registro' => trim(strval($hojaActual->getCell("I".$indiceHoja)->getCalculatedValue())),
                            'Cli_nit' => trim(strval($hojaActual->getCell("H".$indiceHoja)->getCalculatedValue()))
                        ];
                        $contarrg++;
                    }
                    $result = $this->cl->agregar_codigoClientes($arrgaupdate);
                    $this->cl->Update_SicroCodClientes($arrgaupdate_NB);
                    if($result == $contarrg){
                        echo json_encode(array("rs" => TRUE,"cantidadAct"=>$result,"info"=>"Actualización de Códigos Completada!",'columnas' => $hojaActual->getHighestColumn()));
                    }else{
                        echo json_encode(array("rs" => FALSE,"cantidadAct"=>$result,"info"=>"Puede que no se haya ejecutado el proceso completo o ya fueron modificados estos registros"));
                    }

                }else{
                    echo json_encode(array("rs" => FALSE,"cantidadAct"=>$result,"info"=>"Por favor adjuntar la plantilla de clientes nuevos proporcionada ...",'columnas' => $hojaActual->getHighestColumn()));
                }
            }else{
                echo json_encode(array("rs" => FALSE,"cantidadAct"=>$result,"info"=>"La plantilla no se pudo cargar..."));
            }
       }else{ 
            echo json_encode(array("rs" => FALSE,"cantidadAct"=>$result,"info"=>"El tipo de archivo tiene que ser xls ó xlsx"));
      }
    }
    function totales_iniciales(){
        // $selected_distribuidoras = '';
        // $selected_distribuidoras = $this->input->post('distriselect');
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                $nombrepais = '';
                $nombrepais = $this->session->userdata('pais');


                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $co = 0;
                    $arreglos_claves_borrar = array();
                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }
                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }
                    }
                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);
                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }

                // $param_busqueda_todos = array(
                //     // 'fechadesde' => $fechadesde,
                //     // 'fechahasta' => $fechahasta,
                //     // 'cp' => $clienteprueba,
                //     'distribuidoras' => $arraid_distribuidora,
                //     'rutas' => $rutas,
                //     'vista_elegida' => ''
                // );

                $param_busqueda_apobados = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas,
                    'vista_elegida' => 0
                );

                $param_busqueda_editados = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas,
                    'vista_elegida' => 1
                );
                $sumatotales = 0;
                // $tot_clientes_todos = $this->ls->contar_clientes($param_busqueda_todos,1);
                // $toto_todos = $tot_clientes_todos->totolu;

                $tot_clientes_aprobados = $this->ls->contar_clientes($param_busqueda_apobados,1);
                $toto_aprobados = $tot_clientes_aprobados->totolu;

                $tot_clientes_aprobadosAC = $this->cl->contar_clientesSu_ACAD($param_busqueda_apobados);
                $toto_aprobadosAC = $tot_clientes_aprobadosAC->totolu;


                $tot_clientes_editados = $this->ls->contar_clientes($param_busqueda_editados,1);
                $toto_editados = $tot_clientes_editados->totolu;


                $param_busqueda_descargar = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas
                );
                $toto_descargas = 0;$totaldes_uno = 0; $totaldes_dos = 0;
                $tot_clientes_descargas = $this->ls->contar_clientes_procesados($param_busqueda_descargar);
                $totaldes_uno = $tot_clientes_descargas->totolu;

                $tot_clientes_aprobadosAC = $this->ls->contar_clientes_procesadosAC($param_busqueda_apobados);
                $totaldes_dos = $tot_clientes_aprobadosAC->totolu;

                $toto_descargas = $totaldes_uno + $totaldes_dos;

                $sumatotales = $toto_aprobados + $toto_editados + $toto_aprobadosAC;

                $sumaaprobados = $toto_aprobados + $toto_aprobadosAC;




        echo json_encode(array(
            'rs' => TRUE,
            'info' => 'Exito',
            'cla' => 'success grSuccess',
            'totaltodos'=> $sumatotales,
            'totalapro'=> $sumaaprobados,
            'totaledit'=> $toto_editados,
            'totaldescargas'=> $toto_descargas,
            )
        );

    }

    function cambio_contrasena(){
        if($this->input->is_ajax_request()){

            $usuario = $this->session->userdata('usuario');
            $contrasena = $this->input->post('txtpassantes');
            $contranueva =  $this->input->post('txtpassnueva');

            $info_logueo=$this->lg->logueo($usuario,$contrasena);
            if(!empty($info_logueo)){

                $data_cambio =  array(
                    'Contrasena' =>  password_hash($contranueva, PASSWORD_DEFAULT)
                );
                $cambio_pass = $this->lg->modificar_usuario($data_cambio,$usuario);
                if($cambio_pass ==  TRUE){
                    echo json_encode(array(
                        'rs' => TRUE,
                        'info' => '[ Contrase&ntilde; actualizada con &eacute;xito ]',
                        'cla' => 'success grSuccess'
                        )
                    );
                }else{
                    echo json_encode(array(
                        'rs' => FALSE,
                        'info' => 'No se pudo realizar el cambio [Contrase&nitlde;a Anterior es Incorrecta]',
                        'cla' => 'success grDanguer'
                        )
                    );
                }

            }else{
                echo json_encode(array(
                    'rs' => FALSE,
                    'info' => 'La contrase&ntilde;a Anterior es Incorrecta.',
                    'cla' => 'success grDanguer'
                    )
                );
            }

        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }

    function totalcli(){
        $rutaArchivo = 'PLANTILLA_CLIENTES_SDV.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        # Recuerda que un documento puede tener múltiples hojas
        # obtener conteo e iterar
        $totalDeHojas = $documento->getSheetCount();
        # Iterar hoja por hoja
        //echo "total de filas ".$totalDeHojas."<br>";
        $highestRow = $documento->getActiveSheet()->getHighestRow();

        echo json_encode(array(
            'rs' => TRUE,
            'info' => 'Exito',
            'cla' => 'success grSuccess',
            'total'=> $highestRow
            )
        );

    }

function limpia_espacios($cadena){
  $cadena = str_replace(' ', '', $cadena);
  return $cadena;
}
    function generacodigo(){

        /*000000000000000000000000000000000000000000000000000000000000000000000000000000000*/
        /*--------------RECUPERAJION DE DATOS (TODOS LOS CLIENTES PLANTILLA)---------------*/
        /*000000000000000000000000000000000000000000000000000000000000000000000000000000000*/
        /*RECUPERACION DE CLIENTES NUEVOS*/
        $arrg_recuperacion = array();
        $arrg_clientes_nuevos = array();
        $rutaArchivo = 'recuperacion_datos/RECUPERAR_DATOS/PLANTILLA_CLIENTES_SDV_SANSALVADOR_PARTE_II.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        $totalDeHojas = $documento->getSheetCount();
        $highestRow = $documento->getActiveSheet()->getHighestRow();
        // echo "CANTIDAD DE REGISTROS => ".$highestRow;
        $createuncodigounico = '';
        $hojaActual = $documento->getSheet(0);
        $contaclinu = 0;

        for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {
            $telefono = "";
            $telefono = str_replace("0000-0000","0",$hojaActual->getCell("F".$indiceHoja)->getCalculatedValue());
            $createuncodigounico = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue().$hojaActual->getCell("Y".$indiceHoja)->getCalculatedValue().$hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue().$hojaActual->getCell("G".$indiceHoja)->getCalculatedValue().$telefono;
            $arrg_clientes_nuevos[$contaclinu]["NombreCliente"] = $this->limpia_espacios($createuncodigounico);
            $arrg_clientes_nuevos[$contaclinu]["CodigoCliente"] = $hojaActual->getCell("B".$indiceHoja)->getCalculatedValue();
            $contaclinu++;
            $createuncodigounico = '';
        }
        /*00000000000000000000000000000000000000000000000000000000000000000000*/
        /**000000000000000000--CLIENTES DE PROGRAMA SDV--0000000000000000000***/
        $arrg_clientes_enviadosRT = array();
        $rutaArchivoenviado = 'recuperacion_datos/RECUPERAR_DATOS/SS_Y_CH.xlsx';
        $documentoenviado = IOFactory::load($rutaArchivoenviado);
        $totalDeHojasenviado = $documentoenviado->getSheetCount();
        $highestRowenviado = $documentoenviado->getActiveSheet()->getHighestRow();
        echo "<br>CANTIDAD DE REGISTROS => ".$highestRowenviado."<br>";

        $codigounicoenviado = '';
        $hojaActualenviado = $documentoenviado->getSheet(0);
        $contaclinunue = 0;
        for ($indiceHojad = 2; $indiceHojad <= $highestRowenviado; $indiceHojad++) {
            $telefonod = "";
            $telefonod = str_replace("0000-0000","0",$hojaActualenviado->getCell("F".$indiceHojad)->getCalculatedValue());
            $codigounicoenviado = $hojaActualenviado->getCell("C".$indiceHojad)->getCalculatedValue().$hojaActualenviado->getCell("Y".$indiceHojad)->getCalculatedValue().$hojaActualenviado->getCell("Z".$indiceHojad)->getCalculatedValue().$hojaActualenviado->getCell("G".$indiceHojad)->getCalculatedValue().$telefonod;

            $arrg_clientes_enviadosRT[$contaclinunue]['NombreCliente'] = $this->limpia_espacios($codigounicoenviado);
            $arrg_clientes_enviadosRT[$contaclinunue]['CodigoCliente'] = $hojaActualenviado->getCell("B".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['Municipio'] = $hojaActualenviado->getCell("E".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['CantidadExhibidor'] = $hojaActualenviado->getCell("R".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['ExhibidorUno'] = $hojaActualenviado->getCell("S".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['ExhibidorDos'] = $hojaActualenviado->getCell("T".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['ExhibidorTres'] = $hojaActualenviado->getCell("U".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['GiroNegocio'] = $hojaActualenviado->getCell("AC".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['FotoNegocio'] = $hojaActualenviado->getCell("AD".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['FotoExhibidor'] = $hojaActualenviado->getCell("AE".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['CompraB'] = $hojaActualenviado->getCell("AF".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['CompraD'] = $hojaActualenviado->getCell("AG".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['CompraY'] = $hojaActualenviado->getCell("AH".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['CompraF'] = $hojaActualenviado->getCell("AI".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['FechaIngreso'] = $hojaActualenviado->getCell("AJ".$indiceHojad)->getCalculatedValue();

            $arrg_clientes_enviadosRT[$contaclinunue]['DUI'] = $hojaActualenviado->getCell("J".$indiceHojad)->getCalculatedValue();

            $arrg_clientes_enviadosRT[$contaclinunue]['NIT'] = $hojaActualenviado->getCell("L".$indiceHojad)->getCalculatedValue();

            $arrg_clientes_enviadosRT[$contaclinunue]['NUMERO_REGISTRO'] = $hojaActualenviado->getCell("K".$indiceHojad)->getCalculatedValue();

            $arrg_clientes_enviadosRT[$contaclinunue]['CONDICION_CLIENTE'] = $hojaActualenviado->getCell("M".$indiceHojad)->getCalculatedValue();

            $arrg_clientes_enviadosRT[$contaclinunue]['TIPO_FACTURACION'] = $hojaActualenviado->getCell("I".$indiceHojad)->getCalculatedValue();

            $arrg_clientes_enviadosRT[$contaclinunue]['DIA_COBRO'] = $hojaActualenviado->getCell("N".$indiceHojad)->getCalculatedValue();

            $arrg_clientes_enviadosRT[$contaclinunue]['MONTO_CREDITO'] = $hojaActualenviado->getCell("O".$indiceHojad)->getCalculatedValue();
            $arrg_clientes_enviadosRT[$contaclinunue]['FECHA_RESOLUCION'] = $hojaActualenviado->getCell("AL".$indiceHojad)->getCalculatedValue();



            $contaclinunue++;
            $codigounicoenviado = '';
        }
        // echo "<br><br>";
        // var_dump($arrg_clientes_nuevos);
        // echo "<br><br><h3>aqui clientes mandados a routemaps</h3>";
        // var_dump($arrg_clientes_enviadosRT);
        $recorridosdat = 1;
        $cantidad_b_principal = 0;
        // echo "<br>count => ".count($arrg_clientes_enviadosRT);
        $totalrealplantilla = $highestRow - 1;
        $totalrealRT = $highestRowenviado - 1;
    

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setCellValue('A1','CODIGO_CLIENTE');
        $sheet->setCellValue('B1','ID_MUNICIPIO');
        $sheet->setCellValue('C1','CANTIDAD_EXHIBIDORES');
        $sheet->setCellValue('D1','EXHIBIDORUNO');
        $sheet->setCellValue('E1','EXHIBIDORDOS');
        $sheet->setCellValue('F1','EXHIBIDORTRES');
        $sheet->setCellValue('G1','ID_GIRONEGOCIO');
        $sheet->setCellValue('H1','FOTO_NEGOCIO');
        $sheet->setCellValue('I1','FOTO_EXHIBIDOR');
        $sheet->setCellValue('J1','COMPRAB');
        $sheet->setCellValue('K1','COMPRAD');
        $sheet->setCellValue('L1','COMPRAY');
        $sheet->setCellValue('M1','COMPRAF');
        $sheet->setCellValue('N1','FECHA_INGRESO');
        $sheet->setCellValue('O1','DUI');
        $sheet->setCellValue('P1','NIT');
        $sheet->setCellValue('Q1','NUMERO_REGISTRO');
        $sheet->setCellValue('R1','ID_CODICIONCLI');
        $sheet->setCellValue('S1','ID_TFACTURACION');
        $sheet->setCellValue('T1','DIA_COBRO');
        $sheet->setCellValue('U1','MONTO_CREDITO');
        $sheet->setCellValue('V1','FECHA_RESOLUCION');
        $sheet->setCellValue('W1','PORCENTAJE_COICIDENCIA');
        

        $numcelda = 2;
        for ($i=0; $i < $totalrealplantilla ; $i++) { 
            // echo $arrg_clientes_nuevos[$i]["NombreCliente"]."<br>";
            for ($e=0; $e < $totalrealRT; $e++) { 
                $var_1 = strtoupper($arrg_clientes_nuevos[$i]["NombreCliente"]);
                $var_2 = strtoupper($arrg_clientes_enviadosRT[$e]["NombreCliente"]);
                $porcentajeparecidos = 0;
                $porcentajeparecidos = similar_text($var_1, $var_2, $percent);

                if($percent>=80){
                    
                    echo "<br>VALOR EN LA PLANTILLA  | ".$arrg_clientes_nuevos[$i]["NombreCliente"]."<br>";
                    echo "<br>VALOR ENVIADO A ROUTEMAPS | ".$arrg_clientes_enviadosRT[$e]["NombreCliente"]."<br>";
                    echo "<br>CODIGO CLIENTE | ".$arrg_clientes_nuevos[$i]["CodigoCliente"]."<br>";
                    echo "<br>CODIGO MUNICIPIO | ".$arrg_clientes_enviadosRT[$e]["Municipio"]."<br>";
                    echo "<br>CANTIDAD EXHIBIDORES | ".$arrg_clientes_enviadosRT[$e]["CantidadExhibidor"]."<br>";
                    echo "<br>EXHIBIDOR UNO | ".$arrg_clientes_enviadosRT[$e]["ExhibidorUno"]."<br>";
                    echo "<br>EXHIBIDOR DOS | ".$arrg_clientes_enviadosRT[$e]["ExhibidorDos"]."<br>";
                    echo "<br>EXHIBIDOR TRES | ".$arrg_clientes_enviadosRT[$e]["ExhibidorTres"]."<br>";
                    echo "<br>GIRO DE NEGOCIO | ".$arrg_clientes_enviadosRT[$e]["GiroNegocio"]."<br>";
                    echo "<br>FOTO DE NEGOCIO | ".$arrg_clientes_enviadosRT[$e]["FotoNegocio"]."<br>";
                    echo "<br>FOTO DE EXHIBIDOR | ".$arrg_clientes_enviadosRT[$e]["FotoExhibidor"]."<br>";
                    echo "<br>COMPRA BOCADELI | ".$arrg_clientes_enviadosRT[$e]["CompraB"]."<br>";
                    echo "<br>COMPRA DIANA | ".$arrg_clientes_enviadosRT[$e]["CompraD"]."<br>";
                    echo "<br>COMPRA YUMMIES | ".$arrg_clientes_enviadosRT[$e]["CompraY"]."<br>";
                    echo "<br>COMPRA FRITO LAY | ".$arrg_clientes_enviadosRT[$e]["CompraF"]."<br>";
                    echo "<br>FECHA DE INGRESO | ".$arrg_clientes_enviadosRT[$e]["FechaIngreso"]."<br>";
                    echo "<br>PORCENTAJE DE SIMILITUD ENCONTRADO => ".$percent."<br>";
                    echo "<br>CUANTAS VECES TUVIMOS QUE BUSCARLO => ".$recorridosdat."<br>";

                    $sheet->setCellValue('A'.$numcelda,$arrg_clientes_nuevos[$i]["CodigoCliente"]);
                    $sheet->setCellValue('B'.$numcelda,$arrg_clientes_enviadosRT[$e]["Municipio"]);
                    $sheet->setCellValue('C'.$numcelda,$arrg_clientes_enviadosRT[$e]["CantidadExhibidor"]);
                    $sheet->setCellValue('D'.$numcelda,$arrg_clientes_enviadosRT[$e]["ExhibidorUno"]);
                    $sheet->setCellValue('E'.$numcelda,$arrg_clientes_enviadosRT[$e]["ExhibidorDos"]);
                    $sheet->setCellValue('F'.$numcelda,$arrg_clientes_enviadosRT[$e]["ExhibidorTres"]);
                    $sheet->setCellValue('G'.$numcelda,$arrg_clientes_enviadosRT[$e]["GiroNegocio"]);
                    $sheet->setCellValue('H'.$numcelda,$arrg_clientes_enviadosRT[$e]["FotoNegocio"]);
                    $sheet->setCellValue('I'.$numcelda,$arrg_clientes_enviadosRT[$e]["FotoExhibidor"]);
                    $sheet->setCellValue('J'.$numcelda,$arrg_clientes_enviadosRT[$e]["CompraB"]);
                    $sheet->setCellValue('K'.$numcelda,$arrg_clientes_enviadosRT[$e]["CompraD"]);
                    $sheet->setCellValue('L'.$numcelda,$arrg_clientes_enviadosRT[$e]["CompraY"]);
                    $sheet->setCellValue('M'.$numcelda,$arrg_clientes_enviadosRT[$e]["CompraF"]);
                    $sheet->setCellValue('N'.$numcelda,$arrg_clientes_enviadosRT[$e]["FechaIngreso"]);
                    $sheet->setCellValue('O'.$numcelda,$arrg_clientes_enviadosRT[$e]["DUI"]);
                    $sheet->setCellValue('P'.$numcelda,$arrg_clientes_enviadosRT[$e]["NIT"]);
                    $sheet->setCellValue('Q'.$numcelda,$arrg_clientes_enviadosRT[$e]["NUMERO_REGISTRO"]);
                    $sheet->setCellValue('R'.$numcelda,$arrg_clientes_enviadosRT[$e]["CONDICION_CLIENTE"]);
                    $sheet->setCellValue('S'.$numcelda,$arrg_clientes_enviadosRT[$e]["TIPO_FACTURACION"]);
                    $sheet->setCellValue('T'.$numcelda,$arrg_clientes_enviadosRT[$e]["DIA_COBRO"]);
                    $sheet->setCellValue('U'.$numcelda,$arrg_clientes_enviadosRT[$e]["MONTO_CREDITO"]);
                    $sheet->setCellValue('V'.$numcelda,$arrg_clientes_enviadosRT[$e]["FECHA_RESOLUCION"]);
                    $sheet->setCellValue('W'.$numcelda,$percent);
                    $numcelda++;
                    echo "--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";
                    $recorridosdat = 1;
                    break;
                }else{
                    $recorridosdat++;
                    if($recorridosdat == $highestRowenviado){
                        echo "--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";
                        echo "<br>VALOR EN LA PLANTILLA  | ".$arrg_clientes_nuevos[$i]["NombreCliente"]."<br>";
                        echo "<br>CUANTAS VECES TUVIMOS QUE BUSCARLO => ".$recorridosdat."<br>";
                        echo "<h3>NO ENCONTRE SIMILITUD :(</h3>";
                        $recorridosdat = 1;
                        // echo "<h3>PORCENTAJE ENCONTRADO => ".$percent."</h3>";
                    }
                }
            }
        }
        $nombre_archivo = 'RECUPERACION_DATOS.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($nombre_archivo);
        $spreadsheet->disconnectWorksheets();

        // foreach ($arrg_clientes_nuevos as $key => $value) {
                
        //         // echo "<br>".$value;
        //         $cantidad_b_principal++;
        //         foreach ($arrg_clientes_enviadosRT as $keyy => $text) {


        //             $var_1 = strtoupper($value);
        //             $var_2 = strtoupper($text);
        //             $porcentajeparecidos = 0;
        //             $porcentajeparecidos = similar_text($var_1, $var_2, $percent);

        //             if($percent>80){
        //                 echo "<br>VALOR EN LA PLANTILLA  | ".$value."<br>";
        //                 echo "<br>VALOR ENVIADO A ROUTEMAPS | ".$text."<br>";
        //                 echo "<br>PORCENTAJE DE SIMILITUD ENCONTRADO => ".$percent."<br>";
        //                 echo "<br>CUANTAS VECES TUVIMOS QUE BUSCARLO => ".$recorridosdat."<br>";
        //                 echo "------------------------------------------------------------------";
        //                 $recorridosdat = 1;
        //                 break;
        //             }else{
        //                 $recorridosdat++;

        //                 if($recorridosdat == $highestRowenviado){
        //                     echo "<br>VALOR EN LA PLANTILLA  | ".$value."<br>";
        //                     echo "<br>CUANTAS VECES TUVIMOS QUE BUSCARLO => ".$recorridosdat."<br>";
        //                     echo "<h3>NO ENCONTRAMOS SIMILITUD :(</h3>";
        //                 }
        //             }
        //         }

        // }
            echo "<br>";echo "<br>";echo "<br>";
            echo "<strong>----------------------------------------------------------</strong>";
            echo "<br>";
            echo "Busqueda Plantilla Completa => ".$i;
            echo "<br>";
            echo "<strong>----------------------------------------------------------</strong>";

        //     $totalarrg_encontrados = count($arrg_recuperacion);

        //     echo $totalarrg_encontrados;
            // foreach ($variable as $key => $value) {
            //     # code...
            // }

        //     
        // echo "<h3>Similitudes</h3><br><br>";

        // $var_1 = strtoupper('JOSE BRIGIDO GARCIA BARAHONA-88.7593533333333413.682548333333333');
        // $var_2 = strtoupper('JOSE BRIGIDO GARCIA BARAHONA-88.7591982.682548333333313.6826583');

        // similar_text($var_1, $var_2, $percent);

        // echo "Porcentaje de similar =>". $percent;
    }


    function fechaCastellano ($fecha) {
      $fecha = substr($fecha, 0, 16);
      $numeroDia = date('d', strtotime($fecha));
      $dia = date('l', strtotime($fecha));
      $mes = date('F', strtotime($fecha));
      $anio = date('Y', strtotime($fecha));
      $hora = date('H', strtotime($fecha));
      $minuti = date('i', strtotime($fecha));
      $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
      $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
      $nombredia = str_replace($dias_EN, $dias_ES, $dia);
      $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
      $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
      $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
      return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio." Hora ".$hora.":".$minuti;
    }

    function verificacion_d_aprobados(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'page','<strong>Error en numero de p&aacute;gina</strong>',
            'trim|required',
                array(
                    'required'  => 'Ocurrio un error inesperado...'
                )
            );

            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $toto = 0;
            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['rs'] = FALSE;
                $resp['errores'] = validation_errors('<h3>','</h3>');
                $resp['cla'] = 'danger grDanguer';
                echo json_encode($resp);
                return;
            }else{
                $mjs_resultados = '';
                $arrlistas = array();
                $limit = 25;
                $adjacent = 1;
                $page = $this->input->post('page');
                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                // $fechadesde = $this->input->post('datepicker');
                // $fechahasta = $this->input->post('datepickerdos');
                $idrutas = '';
                $codecliente = '';
                $codecliente = desencriptar_cadena($this->input->post('codecli'));
                $opcionbusqueda = '';
                if(!empty($this->input->post('cbrutas'))){
                    $idrutas = desencriptar_cadena($this->input->post('cbrutas'));
                }else{
                    $idrutas = '';
                }
                $arrg_x_contacto = array();
                $arrg_x_telefono = array();
                $arrg_x_nombre = array();
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    LISTADO X CONTACTO
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $param_busquedaC = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'idsupervisor' => $this->session->userdata('idsupervisor'),
                    'rutas' => $idrutas,
                    'opcionbusqueda' => 1
                );
                $valorbusquedac = '';
                $valorbusquedac = $this->input->post('paramc');
                $tonewc = 0;
                $tot_clientes_newc = $this->ls->contar_cli_new_repetidos_a($param_busquedaC,$valorbusquedac);
                $tonewc = $tot_clientes_newc->totrepnew;
                $obt_clientesc = $this->ls->zoom_list_duplicados_cli_a($param_busquedaC,$start,$limit,$valorbusquedac);
                $c=0;
                foreach ($obt_clientesc as $sinc ){
                    $arrg_x_contacto[$c]['ruta'] = $sinc->Nombre_Ruta;
                    $arrg_x_contacto[$c]['codigo'] = $sinc->Codigo;
                    $arrg_x_contacto[$c]['nombrecliente'] = $sinc->Nombre;
                    $arrg_x_contacto[$c]['direccion'] = $sinc->Direccion;
                    $arrg_x_contacto[$c]['telefono'] = $sinc->Telefono;
                    $arrg_x_contacto[$c]['contacto'] = $sinc->Contacto;
                    $arrg_x_contacto[$c]['dias'] = $sinc->Dias;
                    $arrg_x_contacto[$c]['ordenvisita'] = $sinc->Orden_Visita;
                    $arrg_x_contacto[$c]['FechaI'] = $sinc->Fecha_Ingreso;
                    $arrg_x_contacto[$c]['FechaR'] = $sinc->Fecha_Resolucion;
                    $arrg_x_contacto[$c]['frecuenciavisita'] = $sinc->RefUno;
                    $arrg_x_contacto[$c]['departamento'] = $sinc->NombreDepartamento;
                    $arrg_x_contacto[$c]['municipio'] = $sinc->NombreMunicipio;
                    $arrg_x_contacto[$c]['long'] = $sinc->Longitud;
                    $arrg_x_contacto[$c]['lati'] = $sinc->Latitud;
                    $arrg_x_contacto[$c]['fechaingreso'] = $sinc->Fecha_Ingreso;
                    $arrg_x_contacto[$c]['estado'] = $sinc->Estado;
                    $arrg_x_contacto[$c]['estadow'] = $sinc->estado_w;
                    $arrg_x_contacto[$c]['estadoanalista'] = $sinc->Estado_Analista;
                    $arrg_x_contacto[$c]['Id_Usuarios'] = $sinc->Id_Usuarios;
                    $arrg_x_contacto[$c]['id_cliente'] = $sinc->Id_Cliente;
                    $arrg_x_contacto[$c]['Ord_VisitaSema'] = $sinc->Ord_VisitaSema;
                    
                    $cod_encriptado_clic = '';
                    if($codecliente == $sinc->Id_Cliente){
                        $arrg_x_contacto[$c]['igual'] = 'SI';
                    }else{
                        $arrg_x_contacto[$c]['igual'] = 'NO';
                    }
                    $cod_encriptado_clic = encriptar_cadena($sinc->Id_Cliente);
                    $arrg_x_contacto[$c]['codcli'] = $cod_encriptado_clic;
                    $codiruta_encriptadoc = '';
                    $codiruta_encriptadoc = encriptar_cadena($sinc->Id_Ruta);
                    $arrg_x_contacto[$c]['idruta'] = $codiruta_encriptadoc;
                    $c++;
                }
                // $pagina_insertar = $this->paginationcon($limit,$adjacent,$tonew,$page);

                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    LISTADO X TELEFONO
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $param_busquedaT = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'idsupervisor' => $this->session->userdata('idsupervisor'),
                    'rutas' => $idrutas,
                    'opcionbusqueda' => 2
                );
                $valorbusquedat = '';
                $valorbusquedat = $this->input->post('paramt');
                $tonewt = 0;
                $tot_clientes_newt = $this->ls->contar_cli_new_repetidos_a($param_busquedaT,$valorbusquedat);
                $tonewt = $tot_clientes_newt->totrepnew;
                $obt_clientest = $this->ls->zoom_list_duplicados_cli_a($param_busquedaT,$start,$limit,$valorbusquedat);
                $t=0;
                foreach ($obt_clientest as $sint ){
                    $arrg_x_telefono[$t]['ruta'] = $sint->Nombre_Ruta;
                    $arrg_x_telefono[$t]['codigo'] = $sint->Codigo;
                    $arrg_x_telefono[$t]['nombrecliente'] = $sint->Nombre;
                    $arrg_x_telefono[$t]['direccion'] = $sint->Direccion;
                    $arrg_x_telefono[$t]['telefono'] = $sint->Telefono;
                    $arrg_x_telefono[$t]['contacto'] = $sint->Contacto;
                    $arrg_x_telefono[$t]['dias'] = $sint->Dias;
                    $arrg_x_telefono[$t]['ordenvisita'] = $sint->Orden_Visita;
                    $arrg_x_telefono[$t]['frecuenciavisita'] = $sint->RefUno;
                    $arrg_x_telefono[$t]['FechaI'] = $sint->Fecha_Ingreso;
                    $arrg_x_telefono[$t]['FechaR'] = $sint->Fecha_Resolucion;
                    $arrg_x_telefono[$t]['departamento'] = $sint->NombreDepartamento;
                    $arrg_x_telefono[$t]['municipio'] = $sint->NombreMunicipio;
                    $arrg_x_telefono[$t]['long'] = $sint->Longitud;
                    $arrg_x_telefono[$t]['lati'] = $sint->Latitud;
                    $arrg_x_telefono[$t]['fechaingreso'] = $sint->Fecha_Ingreso;
                    $arrg_x_telefono[$t]['estado'] = $sint->Estado;
                    $arrg_x_telefono[$t]['estadow'] = $sint->estado_w;
                    $arrg_x_telefono[$t]['estadoanalista'] = $sint->Estado_Analista;
                    $arrg_x_telefono[$t]['Id_Usuarios'] = $sint->Id_Usuarios;
                    $arrg_x_telefono[$t]['id_cliente'] = $sint->Id_Cliente;
                    $arrg_x_telefono[$t]['Ord_VisitaSema'] = $sint->Ord_VisitaSema;
                    $cod_encriptado_clit = '';
                    if($codecliente == $sint->Id_Cliente){
                        $arrg_x_telefono[$t]['igual'] = 'SI';
                    }else{
                        $arrg_x_telefono[$t]['igual'] = 'NO';
                    }
                    $cod_encriptado_clit = encriptar_cadena($sint->Id_Cliente);
                    $arrg_x_telefono[$t]['codcli'] = $cod_encriptado_clit;
                    $codiruta_encriptadot = '';
                    $codiruta_encriptadot = encriptar_cadena($sint->Id_Ruta);
                    $arrg_x_telefono[$t]['idruta'] = $codiruta_encriptadot;
                    $t++;
                }

                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    LISTADO X NOMBRE
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $param_busquedaN = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'idsupervisor' => $this->session->userdata('idsupervisor'),
                    'rutas' => $idrutas,
                    'opcionbusqueda' => 3
                );
                $valorbusquedan = '';
                $valorbusquedan = $this->input->post('paramn');
                $tonewn = 0;
                $tot_clientes_newn = $this->ls->contar_cli_new_repetidos_a($param_busquedaN,$valorbusquedan);
                $tonewn = $tot_clientes_newn->totrepnew;
                $obt_clientestn = $this->ls->zoom_list_duplicados_cli_a($param_busquedaN,$start,$limit,$valorbusquedan);
                $n=0;
                foreach ($obt_clientestn as $sinn ){
                    $arrg_x_nombre[$n]['ruta'] = $sinn->Nombre_Ruta;
                    $arrg_x_nombre[$n]['codigo'] = $sinn->Codigo;
                    $arrg_x_nombre[$n]['nombrecliente'] = $sinn->Nombre;
                    $arrg_x_nombre[$n]['direccion'] = $sinn->Direccion;
                    $arrg_x_nombre[$n]['telefono'] = $sinn->Telefono;
                    $arrg_x_nombre[$n]['contacto'] = $sinn->Contacto;
                    $arrg_x_nombre[$n]['dias'] = $sinn->Dias;
                    $arrg_x_nombre[$n]['ordenvisita'] = $sinn->Orden_Visita;
                    $arrg_x_nombre[$n]['frecuenciavisita'] = $sinn->RefUno;
                    $arrg_x_nombre[$n]['FechaI'] = $sinn->Fecha_Ingreso;
                    $arrg_x_nombre[$n]['FechaR'] = $sinn->Fecha_Resolucion;
                    $arrg_x_nombre[$n]['departamento'] = $sinn->NombreDepartamento;
                    $arrg_x_nombre[$n]['municipio'] = $sinn->NombreMunicipio;
                    $arrg_x_nombre[$n]['long'] = $sinn->Longitud;
                    $arrg_x_nombre[$n]['lati'] = $sinn->Latitud;
                    $arrg_x_nombre[$n]['fechaingreso'] = $sinn->Fecha_Ingreso;
                    $arrg_x_nombre[$n]['estado'] = $sinn->Estado;
                    $arrg_x_nombre[$n]['estadow'] = $sinn->estado_w;
                    $arrg_x_nombre[$n]['estadoanalista'] = $sinn->Estado_Analista;
                    $arrg_x_nombre[$n]['Id_Usuarios'] = $sinn->Id_Usuarios;
                    $arrg_x_nombre[$n]['id_cliente'] = $sinn->Id_Cliente;
                    $arrg_x_nombre[$n]['Ord_VisitaSema'] = $sinn->Ord_VisitaSema;
                    
                    $cod_encriptado_clin = '';
                    if($codecliente == $sinn->Id_Cliente){
                        $arrg_x_nombre[$n]['igual'] = 'SI';
                    }else{
                        $arrg_x_nombre[$n]['igual'] = 'NO';
                    }
                    $cod_encriptado_clin = encriptar_cadena($sinn->Id_Cliente);
                    $arrg_x_nombre[$n]['codcli'] = $cod_encriptado_clin;
                    $codiruta_encriptadon = '';
                    $codiruta_encriptadon = encriptar_cadena($sinn->Id_Ruta);
                    $arrg_x_nombre[$n]['idruta'] = $codiruta_encriptadon;
                    $n++;
                }
                $arrlistas['rs'] = TRUE;
                // $arrlistas['paginacionsin'] = $pagina_insertar;
                $arrlistas['ltclientesC'] = $arrg_x_contacto;
                $arrlistas['ltclientesT'] = $arrg_x_telefono;
                $arrlistas['ltclientesN'] = $arrg_x_nombre;
                $arrlistas['totalC'] = $tonewc;
                $arrlistas['totalT'] = $tonewt;
                $arrlistas['totalN'] = $tonewn;
                // $arrlistas['rutacod'] = $idrutas;
                // $arrlistas['totalnew'] = $tonew;
                echo json_encode($arrlistas);
                return;
        
            }

        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
            return;
        }
    }
    /*000000000000000000000000000000000000000000000000000000000000*/
    /*--------------ENVIAR RESOLUCION DE ANALISTA-----------------*/
    /*000000000000000000000000000000000000000000000000000000000000*/
    function ok_resolucion_anl(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'resolucion','<strong>Resolucion</strong>',
            'trim|required',
                array(
                    'required' => 'El campo %s es obligatorio.'
                )
            );

            $this->form_validation->set_rules(
            'codecliente','<strong>Cliente no encontrado</strong>',
            'trim|required',
                array(
                    'required' => 'El campo %s es obligatorio.'
                )
            );

            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';
                echo json_encode($resp);
                return;
            }else{
                $resolucion = '';
                $resolucion = $this->input->post('resolucion');
                $codecliente = $this->input->post('codecliente');
                $Idcliente = $this->input->post('idcli');
                $codecliente = desencriptar_cadena($codecliente);
                $fecha_actual = date('Y-m-d H:i:s');
                $re = '';
                $evalcodigoruta = '';
                if(strcmp($this->session->userdata('pais'), 'HONDURAS') == 0 ){
                    $evalcodigoruta = '0';
                    $resto = 7777777;
                }elseif(strcmp($this->session->userdata('pais'), 'GUATEMALA') == 0 ){
                    $evalcodigoruta = '0';
                    $resto = 7777777;
                }else{
                    $codiruta = $this->input->post('codiruta');
                    $codiruta = desencriptar_cadena($codiruta);
                    $ultimocodiruta = '';
                    $ultimocodiruta = $this->ls->obt_ultimo_codigoxruta($codiruta);
                    if(empty($ultimocodiruta)){
                        $ultimocodiruta = 0;
                    }else{
                        $ultimocodiruta = $ultimocodiruta->Codigo;
                    }
                    $resto = substr($ultimocodiruta, 0, -3);
                    $rutanombre = $resto;
                    $resto = $resto.'999';
                    $evalcodigoruta = intval($ultimocodiruta) + 1;
                }
                if($resolucion == 1){
                    $re = 'A';
                    
                    if($evalcodigoruta < $resto){

                        /*REORDENANDO LOS DIAS DE VISITA POR CLIENTE*/
                        //CLIENTE APROBADO POR EL ANALISTA
                        // $Id_Ruta = 0;$Id_Usuarios = 0;$IdCliente_DiasVis = 0;
                        // $IdCliente_DiasVis = $Idcliente;$Id_Ruta = 1;$Id_Usuarios = $this->input->post('idusuarios');$dias_separados = '';$conta_OK = 0;
                        // $conta_UNO = 0;
                        // $arrg_diasBuscar = array();$arrg_UP_Ordenar = array();
                        // /* CONSULTANDO DÍAS DE VISITA DE CLIENTE APROBADO*/

                        // // echo "Id_Cliente => ".$IdCliente_DiasVis."<br>";
                        // $ls_arrg_dias = $this->k->dias_VisitaXCliente($IdCliente_DiasVis);
                        
                        // if(!empty($ls_arrg_dias)){

                        //     $dias_separados = explode(',',$ls_arrg_dias->Dias);

                        //     // var_dump($dias_separados);
                        //     /*
                        //         Error Orden Visita [ 1 ] => Cantidad de Elementos de Array No Coincide Con el Formato Correcto L_0,M_1,I_0,J_0,V_1,S_0,D_0
                        //         ---------------------------------------------------------------------------------------------------------------------------
                        //         Error Orden Visita [ 2 ] => El Formato de los dias en Array es incorrecto Correcto L_0,M_1,I_0,J_0,V_1,S_0,D_0
                        //         ---------------------------------------------------------------------------------------------------------------------------
                        //         Error Orden Visita Dias [ 3 ] => El Formato de los orden dias en Array es incorrecto Correcto 1,2,9,1,1,34,1
                        //         ---------------------------------------------------------------------------------------------------------------------------
                        //     */
                        //     if(count($dias_separados) < 7){
                        //         echo json_encode(array(
                        //             'rs' => FALSE,
                        //             'errores' => 'Error Orden Visita [ 1 ], Formato de Días Incorrecto Para Este Cliente...<br>Informar a Sistemas de Venta',
                        //             'cla' => 'danger grDanguer'
                        //             )
                        //         );
                        //         return;
                        //     }else{
                        //         if ( strcmp($dias_separados[0], 'L_1') == 0 || strcmp($dias_separados[0], 'L_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[1], 'M_1') == 0 || strcmp($dias_separados[1], 'M_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[2], 'I_1') == 0 || strcmp($dias_separados[2], 'I_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[3], 'J_1') == 0 || strcmp($dias_separados[3], 'J_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[4], 'V_1') == 0 || strcmp($dias_separados[4], 'V_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[5], 'S_1') == 0 || strcmp($dias_separados[5], 'S_0') == 0)
                        //             $conta_OK++;
                        //         if ( strcmp($dias_separados[6], 'D_1') == 0 || strcmp($dias_separados[6], 'D_0') == 0)
                        //             $conta_OK++;
                        //     }
                        //     if( $conta_OK === 7 ) {
                        //         // echo "TODO CORRECTO";
                        //         /* EXTRAYENDO DIAS DE VISITA PARA CONSULTAR LOS CLIENTES DE ESE DIA DE LA RUTA SELECCIONADO*/
                        //         if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'L_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[1], 'M_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'M_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[2], 'I_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'I_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[3], 'J_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'J_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[4], 'V_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'V_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[5], 'S_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'S_1';
                        //             $conta_UNO++;
                        //         }
                        //         if ( strcmp($dias_separados[6], 'D_1') == 0 ){
                        //             $arrg_diasBuscar[$conta_UNO] = 'D_1';
                        //             $conta_UNO++;
                        //         }

                        //         AQUI FALTA FOR PARA RECORRER DIAS EN ESTADO 1
                        //         // echo var_dump($arrg_diasBuscar);
                        //         // echo "<br>";
                        //         // echo $arrg_diasBuscar[0]."<br>";
                        //         $totalBus = 0;
                        //         $totalBus = count($arrg_diasBuscar);
                        //         $kik = 0;
                        //         foreach ($arrg_diasBuscar as $diaSelec){
                                    
                        //             // echo "ORDENANDO DIA => ".$diaSelec."<br>";
                        //             // echo "----------------------------------------------------------<br>";
                        //             $ls_client_dsordanado = $this->k->ls_clientesXdia($Id_Usuarios,$diaSelec);
                        //             $arrg_UP_Ordenar = [];

                        //             $cuenta_orden = 1;$cambio_OrdenDia = '';$nuevo_orden = '';
                        //             foreach ($ls_client_dsordanado as $d){
                                       
                        //                 $cambio_OrdenDia = $d->Ord_VisitaSema;
                        //                 $cambio_OrdenDia = explode(',',$cambio_OrdenDia);

                        //                 if( count($cambio_OrdenDia) < 7 ){
                        //                     echo json_encode(array(
                        //                         'rs' => FALSE,
                        //                         'errores' => 'Error Orden Visita Dias [ 3 ], Formato de Orden Días Incorrecto Para Este Cliente [ '.$d->Id_Cliente.' ]<br>Informar a Sistemas de Venta',
                        //                         'cla' => 'danger grDanguer'
                        //                         )
                        //                     );
                        //                     return;
                        //                 }else{
                                            
                        //                     if ( strcmp($diaSelec, 'L_1') == 0 )
                        //                         $nuevo_orden = $cuenta_orden.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'M_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$cuenta_orden.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'I_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$cuenta_orden.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'J_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$cuenta_orden.','.$d->OrdViernes.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'V_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$cuenta_orden.','.$d->OrdSabado.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'S_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$cuenta_orden.','.$d->OrdDomingo;
                        //                     if ( strcmp($diaSelec, 'D_1') == 0 )
                        //                         $nuevo_orden = $d->OrdLunes.','.$d->OrdMartes.','.$d->OrdMiercoles.','.$d->OrdJueves.','.$d->OrdViernes.','.$d->OrdSabado.','.$cuenta_orden;
                                            
                        //                     $cuenta_orden++;
                        //                     $arrg_UP_Ordenar[] = [
                        //                         'Id_Cliente' => intval($d->Id_Cliente),
                        //                         'Ord_VisitaSema' => $nuevo_orden
                        //                     ];
                        //                 }
                        //             }

                        //             // var_dump($arrg_UP_Ordenar);
                        //             $this->k->ordenamiento_completo($arrg_UP_Ordenar);
                        //             $kik++;
                        //         }


                        //     }else{
                        //         echo json_encode(array(
                        //             'rs' => FALSE,
                        //             'errores' => 'Error Orden Visita [ 2 ] , Formato de Días Incorrecto Para Este Cliente...<br>Informar a Sistemas de Venta',
                        //             'cla' => 'danger grDanguer'
                        //             )
                        //         );
                        //         return;
                        //     }
                        // }else{
                        //     echo json_encode(array(
                        //         'rs' => FALSE,
                        //         'errores' => 'ERROR Cliente no encontrado [ '.$IdCliente_DiasVis.' ]',
                        //         'cla' => 'danger grDanguer'
                        //         )
                        //     );
                        //     return;
                        // }

                        /*FINAL REORDENANDO LOS DIAS DE VISITA POR CLIENTE*/

                        // if($kik == $totalBus){
                            $modificar_c = array(
                                'Codigo' => $evalcodigoruta,
                                'Estado_Analista' => $re,
                                'estado_w' => '1',
                                'Fecha_Resolucion_R' => $fecha_actual,
                                'quienresolucion' => $this->session->userdata('nombrecompleto')
                            );
                            // $modificardata = TRUE;
                            $modificardata = $this->cl->modificar_clientes($modificar_c,$codecliente);
                            if($modificardata){
                                echo json_encode(array(
                                    'rs' => TRUE,
                                    'info' => ' El registro se modifico correctamente.',
                                    'cla' => 'success grSuccess'
                                    )
                                );
                            }else{
                                echo json_encode(array(
                                    'rs' => FALSE,
                                    'info' => ' Ocurrio un error en el proceso.',
                                    'cla' => 'success grDanguer'
                                    )
                                );
                            }
                        // }else{
                        //     echo json_encode(array(
                        //         'rs' => FALSE,
                        //         'errores' => 'ERROR, PROCESO INCOMPLETO ORDEN DE VISITA',
                        //         'cla' => 'danger grDanguer'
                        //         )
                        //     );
                        //     return;
                        // }
                    }else{
                        echo json_encode(array(
                            'rs' => FALSE,
                                'info' => 'La ruta '.$rutanombre.' ha alcanzado el maximo de correlativos permitidos en los registros de clientes',
                                'cla' => 'success grDanguer'
                            )
                        );
                    }
                }else{
                    $re = 'R';
                    $modificar_c = array(
                            'Estado_Analista' => $re,
                            'estado_w' => '0',
                            'Fecha_Resolucion_R' => $fecha_actual,
                            'quienresolucion' => $this->session->userdata('nombrecompleto')
                    );
                    // $modificardata = TRUE;
                    $modificardata = $this->cl->modificar_clientes($modificar_c,$codecliente);
                    if($modificardata){
                        echo json_encode(array(
                            'rs' => TRUE,
                            'info' => ' El registro se modifico correctamente.',
                            'cla' => 'success grSuccess'
                            )
                        );
                    }else{
                        echo json_encode(array(
                            'rs' => FALSE,
                            'info' => ' Ocurrio un error en el proceso.',
                            'cla' => 'success grDanguer'
                            )
                        );
                    }
                }
               return;
            }
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
            return;
        }
    }
    /*------------00000000000000000000000000--------------*/
    /*------------LISTA DE CLIENTES PROCESADOS------------*/
    /*------------00000000000000000000000000--------------*/
    function m_lista_procesados(){
        if($this->input->is_ajax_request()){

            $limit = 25;
            $adjacent = 1;
            $page = $this->input->post('page');
    

            if($page==1){
                $start = 0;
            }else{
                $start = ($page-1)*$limit;
            }

                $distribuidoras = $this->input->post('cbmuldistribuidora');
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');
                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();
                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }
                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }                   
                    }
                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);
                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);
                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }
                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arraid_distribuidora,
                    'rutas' => $rutas
                );


            $nombrepais = '';
            $nombrepais = $this->session->userdata('pais');
            $arrg_bitacora = array();
            $l_bitacora = $this->ls->list_bitacora_procesados($nombrepais,$start,$limit,$param_busqueda);
            $fechainicio = '';
            $fechafinal = '';
            $distribuidoralist = '';
            $nombrec = '';
            $fechadescarga = '';
            $nom_arch = '';
            $ba = 0;
            $idbitacora = '';

            $eval_archivo = '';

            foreach ($l_bitacora as $bt){
                $arrg_bitacora[$ba]['biticadoraid'] = $bt->Id_Bitacora_P;
                $arrg_bitacora[$ba]['fechainicio'] = $this->fechaCastellano($bt->Fecha_Inicio);
                $arrg_bitacora[$ba]['fechafinal'] = $this->fechaCastellano($bt->Fecha_Final);
                $arrg_bitacora[$ba]['distribuidoralist'] = $bt->Distribuidoras_B;
                $arrg_bitacora[$ba]['nombrec'] = $bt->Nombre_Completo;
                $arrg_bitacora[$ba]['fechadescarga'] = $this->fechaCastellano($bt->Fecha_Descarga);
                $arrg_bitacora[$ba]['tipodescarga'] = $bt->TipoDescarga;

                $eval_archivo = $bt->Nombre_Archivo;

                if (file_exists($eval_archivo)) {
                    $eval_archivo = $eval_archivo;
                } else {
                    $eval_archivo = '#';
                }
  

                $arrg_bitacora[$ba]['nom_arch'] = $eval_archivo;
                $ba++;
            }
            $resultcanti=0;
            $c_bitacora = $this->ls->contar_bitacora($nombrepais,$param_busqueda);
            $resultcanti = $c_bitacora->totolus;
        
            $pagina_insertar = $this->paginationcon($limit,$adjacent,$resultcanti,$page);

            $arrlistas['parametros']['rs'] = TRUE;
            $arrlistas['parametros']['paginacionsin'] = $pagina_insertar;
            $arrlistas['parametros']['total'] = $resultcanti;
            $arrlistas['parametros']['lbitacoras'] = $arrg_bitacora;
            $arrlistas['parametros']['titulo'] = 'Bit&aacute;cora de descargas';

            echo json_encode($arrlistas);
            return;

        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }
    /*------------00000000000000000000000000-------------*/
    /*--------MULTI SELECT DISTRIBUIDORA POR PAIS--------*/
    /*------------00000000000000000000000000-------------*/
    function m_distribuidora_pais(){
        if($this->input->is_ajax_request()){

                $nombrepais = '';
                $nombrepais = $this->session->userdata('pais');
                $arrg_distribuidora = array();
                $arrg_rutas = array();
                $count_d = 0;
                $count_d = count($this->session->userdata('listdistribuidora'));
                $distri_x_us = '';
                $distri_x_us = $this->session->userdata('listdistribuidora');
                $eval_x_dis = 0;                
                $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                $dist=0;
                foreach ($l_distribuidoras as $dtb){
                    $cod_encriptado_dtb= '';
                    $cod_encriptado_dtb= encriptar_cadena($dtb->Id_Distribuidora);
                    $arrg_distribuidora[$dist]['codbx'] = $cod_encriptado_dtb;
                    $arrg_distribuidora[$dist]['valor'] = $dtb->Nombre_Distribuidora;
                    for ($ds=0; $ds < $count_d; $ds++) { 
                        if($distri_x_us[$ds] == $dtb->Id_Distribuidora){
                            $eval_x_dis = 1;
                            break;
                        }else{
                            $eval_x_dis = 0;
                        }
                    }
                    $arrg_distribuidora[$dist]['seleccionado'] = $eval_x_dis;
                    $dist++;
                }

            $distribuidoras = $distri_x_us;
            $totaldist = 0;
            $where_distribuidora = "";
            $arrgdistribuidoras = array();
            if(!empty($distribuidoras)){
                $totaldist = count($distribuidoras);
                if($totaldist>0){
                    for ($i=0; $i < $totaldist ; $i++) {
                        $arrgdistribuidoras[$i] =  $distribuidoras[$i];
                    }
                }else{
                    $arrgdistribuidoras = array();
                }
            }else{
                $totaldist = 0;
                $arrgdistribuidoras = array();
            }
            $arrgdistribuidoras = array_values($arrgdistribuidoras);
            /*-------------------------------------------------------*/
            /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
            /*-------------------------------------------------------*/
            $l_distinto_distribuidora = array();
            $dist = 0;
            $a = 0;
            $co = 0;
            $valor = '';
            $arreglos_claves_borrar = array();
            $arraid_distribuidora = array();
            $lc = 0;
            foreach ($l_distribuidoras as $dtbd){
                $arraid_distribuidora[$lc] = $dtbd->Id_Distribuidora;
                $lc++;
            }
            for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                if(!empty($clave)){
                    $arreglos_claves_borrar[$co] = $clave;
                    $co++;
                }else{
                    if($clave == 0){
                        $arreglos_claves_borrar[$co] = $clave;
                        $co++;
                    }else{

                    }
                }                   
            }
            $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
            $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

            for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
            }
            $arraid_distribuidora = array_values($arraid_distribuidora);


                $l_rutas = $this->ls->list_rutas($nombrepais,$arraid_distribuidora);
                $ruts=0;
                foreach ($l_rutas as $r){
                    $cod_encriptado_rt= '';
                    $cod_encriptado_rt= encriptar_cadena($r->Id_Ruta);
                    $arrg_rutas[$ruts]['codbx'] = $cod_encriptado_rt;
                    $arrg_rutas[$ruts]['valor'] = $r->Nombre_Ruta;
                    $ruts++;
                }
                echo json_encode(array(
                    'rs' => TRUE,
                    'ldistribuidora' => $arrg_distribuidora,
                    'lrutas' => $arrg_rutas,
                    'cla' => 'success grSuccess'
                    )
                );
            
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }
    /*------------00000000000000000000000000-------------*/
    /*---------------SELECT RUTAS POR PAIS---------------*/
    /*------------00000000000000000000000000-------------*/
    function m_rutas_por_distribuidoras(){
        if($this->input->is_ajax_request()){
            $nombrepais = '';
            $nombrepais = $this->session->userdata('pais');
            $arrg_distribuidora = array();
            $arrg_rutas = array();
            $distribuidoras = $this->input->post('cbmuldistribuidora');
            /*---------------------------------------------------------------------*/
            /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
            /*---------------------------------------------------------------------*/
            $clienteprueba = $this->input->post('cp');
            $totaldist = 0;
            $where_distribuidora = "";
            $arrgdistribuidoras = array();
            if(!empty($distribuidoras)){
                $totaldist = count($distribuidoras);
                if($totaldist>0){
                    for ($i=0; $i < $totaldist ; $i++) {
                        $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                    }
                }else{
                    $arrgdistribuidoras = array();
                }
            }else{
                $totaldist = 0;
                $arrgdistribuidoras = array();
            }
            $arrgdistribuidoras = array_values($arrgdistribuidoras);
            /*-------------------------------------------------------*/
            /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
            /*-------------------------------------------------------*/
            $l_distinto_distribuidora = array();
            $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
            $dist = 0;
            $a = 0;
            $co = 0;
            $valor = '';
            $arreglos_claves_borrar = array();
            $arraid_distribuidora = array();
            $lc = 0;
            foreach ($l_distribuidoras as $dtb){
                $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                $lc++;
            }
            for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                if(!empty($clave)){
                    $arreglos_claves_borrar[$co] = $clave;
                    $co++;
                }else{
                    if($clave == 0){
                        $arreglos_claves_borrar[$co] = $clave;
                        $co++;
                    }else{

                    }
                }                   
            }
            $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
            $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

            for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
            }
            $arraid_distribuidora = array_values($arraid_distribuidora);
            $l_rutas = $this->ls->list_rutas_por_distri($nombrepais,$arraid_distribuidora);
            $ruts=0;
            foreach ($l_rutas as $r){
                $cod_encriptado_rt= '';
                $cod_encriptado_rt= encriptar_cadena($r->Id_Ruta);
                $arrg_rutas[$ruts]['codbx'] = $cod_encriptado_rt;
                $arrg_rutas[$ruts]['valor'] = $r->Nombre_Ruta;
                $ruts++;
            }
            echo json_encode(array(
                'rs' => TRUE,
                'lrutas' => $arrg_rutas,
                'cla' => 'success grSuccess',
                'd' => $distribuidoras
                )
            );
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }
    /*------------00000000000000000000000000-------------*/
    /*-----------TOTAL DE CLIENTES PLANTILLA OK----------*/
    /*------------00000000000000000000000000-------------*/
    function total_clientes_plantilla(){
        if($this->input->is_ajax_request()){
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                // $fechadesde = $this->input->post('datepickervalue');
                // $fechahasta = $this->input->post('datepickerdosvalue');
                
                $vista_elegida = $this->input->post('tipovista');
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');

                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();

                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }

                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }                   
                    }
                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);
                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }
                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas
                );

                $c_plantilla = $this->ls->contar_clientes_plantilla($param_busqueda);
                $resultcanti=0;
                $resultcanti = $c_plantilla->totolu;
                echo json_encode(array(
                    'rs' => TRUE,
                    'resultcanti' => $resultcanti,
                    'cla' => 'success grSuccess',
                    'total' => $resultcanti
                    )
                );
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }
    /*------------00000000000000000000000000-------------*/
    /*-------------TOTAL DE CLIENTES CONSULTA------------*/
    /*------------00000000000000000000000000-------------*/
    function total_clientes_consulta(){
        if($this->input->is_ajax_request()){

                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $fechadesde = $this->input->post('datepickervalue');
                $fechahasta = $this->input->post('datepickerdosvalue');
                $distribuidoras = '';
                $distribuidoras = $this->input->post('cbmultidistribuidora');

                $fechadesde = str_replace("am","",$fechadesde);
                $fechadesde = str_replace("pm","",$fechadesde);

                $fechahasta = str_replace("am","",$fechahasta);
                $fechahasta = str_replace("pm","",$fechahasta);
                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $clienteprueba = $this->input->post('cp');
                $totaldist = 0;
                $where_distribuidora = "";
                $arrgdistribuidoras = array();
                if(!empty($distribuidoras)){
                    $totaldist = count($distribuidoras);
                    if($totaldist>0){
                        for ($i=0; $i < $totaldist ; $i++) {
                            // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                            // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                            $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                        }
                    }else{
                        $arrgdistribuidoras = array();
                    }
                }else{
                    $totaldist = 0;
                    $arrgdistribuidoras = array();
                }
                $arrgdistribuidoras = array_values($arrgdistribuidoras);
                /*-------------------------------------------------------*/
                /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*-------------------------------------------------------*/
                $nombrepais = '';
                $nombrepais = $this->session->userdata('pais');
                $l_distinto_distribuidora = array();

                $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                $dist = 0;
                $a = 0;
                $co = 0;
                $valor = '';
                $arreglos_claves_borrar = array();

                $arraid_distribuidora = array();
                $lc = 0;
                foreach ($l_distribuidoras as $dtb){
                    $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                    $lc++;
                }

                for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                    $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                    if(!empty($clave)){
                        $arreglos_claves_borrar[$co] = $clave;
                        $co++;
                    }else{
                        if($clave == 0){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{

                        }
                    }                   
                }

                $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                    unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                }
                $arraid_distribuidora = array_values($arraid_distribuidora);

                $param_busqueda = array(
                    'fechadesde' => $fechadesde,
                    'fechahasta' => $fechahasta,
                    'cp' => $clienteprueba,
                    'distribuidoras' => $arraid_distribuidora
                );
                $c_plantilla = $this->ls->contar_clientes($param_busqueda);
                $resultcanti=0;
                foreach ($c_plantilla as $ct){$resultcanti = $ct->totolu;}
                echo json_encode(array(
                    'rs' => TRUE,
                    'resultcanti' => $resultcanti,
                    'cla' => 'success grSuccess'
                    )
                );
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }
    /*------------00000000000000000000000000-------------*/
    /*-------------TOTAL DE CLIENTES CONSULTA------------*/
    /*------------00000000000000000000000000-------------*/
    function total_clientes_plantillaco(){
        if($this->input->is_ajax_request()){
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $fechadesde = $this->input->post('datepickervalue');
                $fechahasta = $this->input->post('datepickerdosvalue');
                $distribuidoras = '';
                $distribuidoras = $this->input->post('cbmuldistribuidorare');

                $fechadesde = str_replace("am","",$fechadesde);
                $fechadesde = str_replace("pm","",$fechadesde);

                $fechahasta = str_replace("am","",$fechahasta);
                $fechahasta = str_replace("pm","",$fechahasta);
                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $clienteprueba = $this->input->post('cp');
                $totaldist = 0;
                $where_distribuidora = "";
                $arrgdistribuidoras = array();
                if(!empty($distribuidoras)){
                    $totaldist = count($distribuidoras);
                    if($totaldist>0){
                        for ($i=0; $i < $totaldist ; $i++) {
                            // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                            // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                            $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                        }
                    }else{
                        $arrgdistribuidoras = array();
                    }
                }else{
                    $totaldist = 0;
                    $arrgdistribuidoras = array();
                }
                $arrgdistribuidoras = array_values($arrgdistribuidoras);
                /*-------------------------------------------------------*/
                /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*-------------------------------------------------------*/
                $nombrepais = '';
                $nombrepais = $this->session->userdata('pais');
                $l_distinto_distribuidora = array();

                $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                $dist = 0;
                $a = 0;
                $co = 0;
                $valor = '';
                $arreglos_claves_borrar = array();

                $arraid_distribuidora = array();
                $lc = 0;
                foreach ($l_distribuidoras as $dtb){
                    $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                    $lc++;
                }

                for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                    $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                    if(!empty($clave)){
                        $arreglos_claves_borrar[$co] = $clave;
                        $co++;
                    }else{
                        if($clave == 0){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{

                        }
                    }                   
                }

                $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                    unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                }
                $arraid_distribuidora = array_values($arraid_distribuidora);

                $param_busqueda = array(
                    'fechadesde' => $fechadesde,
                    'fechahasta' => $fechahasta,
                    'cp' => $clienteprueba,
                    'distribuidoras' => $arraid_distribuidora
                );
                $c_plantilla = $this->ls->contar_clientes_platcompleta($param_busqueda);
                $resultcanti=0;
                foreach ($c_plantilla as $ct){$resultcanti = $ct->totolu;}
                echo json_encode(array(
                    'rs' => TRUE,
                    'resultcanti' => $resultcanti,
                    'cla' => 'success grSuccess'
                    )
                );
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }

    function total_clientes_plantillaco_actu(){
        if($this->input->is_ajax_request()){
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $fechadesde = $this->input->post('datepickervalueactu');
                $fechahasta = $this->input->post('datepickerdosvalueactu');
                $distribuidoras = '';
                $distribuidoras = $this->input->post('cbmuldistribuidorareactu');

                $fechadesde = str_replace("am","",$fechadesde);
                $fechadesde = str_replace("pm","",$fechadesde);

                $fechahasta = str_replace("am","",$fechahasta);
                $fechahasta = str_replace("pm","",$fechahasta);
                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $clienteprueba = $this->input->post('cp');
                $totaldist = 0;
                $where_distribuidora = "";
                $arrgdistribuidoras = array();
                if(!empty($distribuidoras)){
                    $totaldist = count($distribuidoras);
                    if($totaldist>0){
                        for ($i=0; $i < $totaldist ; $i++) {
                            // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                            // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                            $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                        }
                    }else{
                        $arrgdistribuidoras = array();
                    }
                }else{
                    $totaldist = 0;
                    $arrgdistribuidoras = array();
                }
                $arrgdistribuidoras = array_values($arrgdistribuidoras);
                /*-------------------------------------------------------*/
                /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*-------------------------------------------------------*/
                $nombrepais = '';
                $nombrepais = $this->session->userdata('pais');
                $l_distinto_distribuidora = array();

                $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                $dist = 0;
                $a = 0;
                $co = 0;
                $valor = '';
                $arreglos_claves_borrar = array();

                $arraid_distribuidora = array();
                $lc = 0;
                foreach ($l_distribuidoras as $dtb){
                    $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                    $lc++;
                }

                for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                    $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                    if(!empty($clave)){
                        $arreglos_claves_borrar[$co] = $clave;
                        $co++;
                    }else{
                        if($clave == 0){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{

                        }
                    }                   
                }

                $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                    unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                }
                $arraid_distribuidora = array_values($arraid_distribuidora);

                $param_busqueda = array(
                    'fechadesde' => $fechadesde,
                    'fechahasta' => $fechahasta,
                    'cp' => $clienteprueba,
                    'distribuidoras' => $arraid_distribuidora
                );
                $c_plantilla = $this->ls->contar_clientes_platcompleta_actu($param_busqueda);
                $resultcanti = 0;
                foreach ($c_plantilla as $ct){$resultcanti = $ct->totolu;}

                echo json_encode(array(
                    'rs' => TRUE,
                    'resultcanti' => $resultcanti,
                    'cla' => 'success grSuccess'
                    )
                );
        }else{
            echo json_encode(array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer'
                )
            );
        }
    }
    /*---------------------------------------------------------------------*/
    /*------------LISTADO DE CLIENTES ACTUALIZADOS POR FECHA---------------*/
    /*---------------------------------------------------------------------*/
    function tablaclientes_actu(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'page','<strong>Error en numero de p&aacute;gina</strong>',
            'trim|required',
                array(
                    'required'  => 'Ocurrio un error inesperado...'
                )
            );

            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $toto = 0;
            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';

                echo json_encode($resp);
                return;
            }else{
                $mjs_resultados = '';
                $arrlistas = array();
                $limit = 5;
                $adjacent = 1;
                $page = $this->input->post('page');
    

                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }
                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $fechadesde = $this->input->post('datepickervalueactu');
                $fechahasta = $this->input->post('datepickerdosvalueactu');
                $distribuidoras = $this->input->post('cbmuldistribuidorareactu');
                $titulo = '';

                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');

                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();

                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }

                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }
                    }

                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);

                }
                $rutas = '';
                $toto = 0;
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }
                $param_busqueda = array(
                    'fechadesde' => $fechadesde,
                    'fechahasta' => $fechahasta,
                    'distribuidoras' => $arraid_distribuidora,
                    'limit' => $limit,
                    'start' => $start
                );
                $tot_clientes = $this->ls->contar_clientes_platcompleta_actu($param_busqueda);
                foreach ($tot_clientes as $ctc){$toto = $ctc->totolu;}
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                0000000-LLENANDO ARREGLOS PARA LAS LISTAS DE CLIENTES-00000000000000000
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $obt_clientes = $this->ls->lista_clientes_actualizados($param_busqueda);
                $tabla_clientes =  array();
                $s=0;
                foreach ($obt_clientes as $sin ){
                    
                    $tabla_clientes[$s]['idcliente'] = $sin->Id_Actu_Info_Cli;
                    $tabla_clientes[$s]['ruta'] = $sin->Nombre_Ruta;
                    $tabla_clientes[$s]['nombrecliente'] = $sin->Nombre;
                    $tabla_clientes[$s]['direccion'] = $sin->Direccion;
                    $tabla_clientes[$s]['telefono'] = $sin->Telefono;
                    $tabla_clientes[$s]['contacto'] = $sin->Contacto;
                    $tabla_clientes[$s]['latitud'] = $sin->Latitud;
                    $tabla_clientes[$s]['longitud'] = $sin->Longitud;
                    $tabla_clientes[$s]['fechaactu'] = $sin->Fecha_Actualizacion;
                    $tabla_clientes[$s]['codigocliente'] = $sin->Codigo_Cliente;
                    $s++;
                }

                $pagina_insertar = $this->pagination_actu($limit,$adjacent,$toto,$page);
                $arrlistas['parametros']['rs'] = TRUE;
                $arrlistas['parametros']['paginacionsin'] = $pagina_insertar;
                $arrlistas['parametros']['total'] = $toto;
                $arrlistas['parametros']['ltclientes'] = $tabla_clientes;                
                echo json_encode($arrlistas);
                return;
            }
        }else{

        }
    }
    /*---------------------------------------------------------------------*/
    /*------------LISTADO DE CLIENTES POR LA OPCION CONSULTA---------------*/
    /*---------------------------------------------------------------------*/
    function tablaclientes(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'page','<strong>Error en numero de p&aacute;gina</strong>',
            'trim|required',
                array(
                    'required'  => 'Ocurrio un error inesperado...'
                )
            );

            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $toto = 0;
            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';

                echo json_encode($resp);
                return;
            }else{
                $mjs_resultados = '';
                $arrlistas = array();
                $limit = 10;
                $adjacent = 1;
                $page = $this->input->post('page');
    

                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                // $fechadesde = $this->input->post('datepickervalue');
                // $fechahasta = $this->input->post('datepickerdosvalue');
                // $distribuidoras = '';
                

                // $fechadesde = str_replace("am","",$fechadesde);
                // $fechadesde = str_replace("pm","",$fechadesde);

                // $fechahasta = str_replace("am","",$fechahasta);
                // $fechahasta = str_replace("pm","",$fechahasta);
                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $clienteprueba = $this->input->post('cp');
                $vista_elegida = $this->input->post('tipovista');
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                $titulo = '';
                if($vista_elegida == 0){
                    $titulo = 'Clientes Aprobados (NUEVOS)';
                }else{
                    $titulo = 'Clientes Aprobados (NUEVOS EDITADOS)';
                }
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');

                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();

                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }

                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }
                    }

                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);

                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }
                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas,
                    'vista_elegida' => $vista_elegida
                );
                $tot_clientes = $this->ls->contar_clientes($param_busqueda,1);
                $toto = $tot_clientes->totolu;
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                0000000-LLENANDO ARREGLOS PARA LAS LISTAS DE CLIENTES-00000000000000000
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                $obt_clientes = $this->ls->obtener_listado_tabla($param_busqueda,$start,$limit,1);
                $tabla_clientes =  array();
                $s=0;
                foreach ($obt_clientes as $sin ){
                    $tabla_clientes[$s]['ruta'] = $sin->Nombre_Ruta;
                    $tabla_clientes[$s]['nombrecliente'] = $sin->Nombre;
                    $tabla_clientes[$s]['direccion'] = $sin->Direccion;
                    $tabla_clientes[$s]['telefono'] = $sin->Telefono;
                    $tabla_clientes[$s]['contacto'] = $sin->Contacto;
                    $tabla_clientes[$s]['dias'] = $sin->Dias;
                    $tabla_clientes[$s]['ordenvisita'] = $sin->Orden_Visita;
                    $tabla_clientes[$s]['frecuencia'] = $sin->RefUno;
                    $tabla_clientes[$s]['departamento'] = $sin->NombreDepartamento;
                    $tabla_clientes[$s]['municipio'] = $sin->NombreMunicipio;
                    $tabla_clientes[$s]['long'] = $sin->Longitud;
                    $tabla_clientes[$s]['lati'] = $sin->Latitud;
                    $tabla_clientes[$s]['Estado'] = $sin->Estado;
                    $tabla_clientes[$s]['FechaI'] = $sin->Fecha_Ingreso;
                    $tabla_clientes[$s]['FechaR'] = $sin->Fecha_Resolucion;
                    $cod_encriptado_cli = '';
                    $cod_encriptado_cli = encriptar_cadena($sin->Id_Cliente);
                    $cod_encriptado_ruta = '';
                    $cod_encriptado_ruta = encriptar_cadena($sin->Id_Ruta);
                    $tabla_clientes[$s]['codruta'] = $cod_encriptado_ruta;
                    $tabla_clientes[$s]['codcli'] = $cod_encriptado_cli;
                    $tabla_clientes[$s]['comentarioe'] = $sin->Comentario_E;
                    $tabla_clientes[$s]['Id_Usuarios'] = $sin->Id_Usuarios;
                    $tabla_clientes[$s]['id_cliente'] = $sin->Id_Cliente;
                    $tabla_clientes[$s]['Ord_VisitaSema'] = $sin->Ord_VisitaSema;
                    $s++;
                }

                $pagina_insertar = $this->pagination($limit,$adjacent,$toto,$page);
                $arrlistas['parametros']['rs'] = TRUE;
                $arrlistas['parametros']['paginacionsin'] = $pagina_insertar;
                $arrlistas['parametros']['total'] = $toto;
                $arrlistas['parametros']['ltclientes'] = $tabla_clientes;
                $arrlistas['parametros']['distribuidoras'] = $distribuidoras;
                $arrlistas['parametros']['titulo'] = $titulo;
                
                
                echo json_encode($arrlistas);
                return;
        
            }

        }else{

        }
    }
    /*---------------------------------------------------------------------*/
    /*------------LISTADO DE CLIENTES POR LA OPCION CONSULTA APROBADOS ANALISTAA---------------*/
    /*---------------------------------------------------------------------*/
    function tablaclientes_aprobados(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'page','<strong>Error en numero de p&aacute;gina</strong>',
            'trim|required',
                array(
                    'required'  => 'Ocurrio un error inesperado...'
                )
            );

            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $toto = 0;
            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';
                echo json_encode($resp);
                return;
            }else{
                $mjs_resultados = '';
                $arrlistas = array();
                $limit = 5;
                $adjacent = 1;
                $page = $this->input->post('page');
                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                // $fechadesde = $this->input->post('datepickervalue');
                // $fechahasta = $this->input->post('datepickerdosvalue');
                // $distribuidoras = '';
                // $fechadesde = str_replace("am","",$fechadesde);
                // $fechadesde = str_replace("pm","",$fechadesde);

                // $fechahasta = str_replace("am","",$fechahasta);
                // $fechahasta = str_replace("pm","",$fechahasta);
                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $clienteprueba = $this->input->post('cp');
                $vista_elegida = $this->input->post('tipovista');
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');
                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();
                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }
                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }
                    }
                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);
                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);
                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }
                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas
                );
                $tot_clientes = $this->ls->contar_clientes_procesados($param_busqueda);
                $toto = $tot_clientes->totolu;
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                0000000-LLENANDO ARREGLOS PARA LAS LISTAS DE CLIENTES-00000000000000000
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                /*SIN ENCUESTAS*/
                $obt_clientes = $this->ls->obtener_tabla_procesados($param_busqueda,$start,$limit,1);
                $tabla_clientes =  array();
                $s=0;
                foreach ($obt_clientes as $sin ){
                    $tabla_clientes[$s]['ruta'] = $sin->Nombre_Ruta;
                    $tabla_clientes[$s]['nombrecliente'] = $sin->Nombre;
                    $tabla_clientes[$s]['direccion'] = $sin->Direccion;
                    $tabla_clientes[$s]['telefono'] = $sin->Telefono;
                    $tabla_clientes[$s]['contacto'] = $sin->Contacto;
                    $tabla_clientes[$s]['dias'] = $sin->Dias;
                    $tabla_clientes[$s]['fecharesolucion'] = $sin->Fecha_Resolucion_R;
                    $tabla_clientes[$s]['departamento'] = $sin->NombreDepartamento;
                    $tabla_clientes[$s]['municipio'] = $sin->NombreMunicipio;
                    $tabla_clientes[$s]['quien'] = $sin->quienresolucion;
                    $tabla_clientes[$s]['codigocli'] = $sin->Codigo;
                    $tabla_clientes[$s]['Ord_VisitaSema'] = $sin->Ord_VisitaSema;
                    $s++;
                }
                $pagina_insertar = $this->paginationaprobados($limit,$adjacent,$toto,$page);
                $arrlistas['parametros']['rs'] = TRUE;
                $arrlistas['parametros']['paginacionsin'] = $pagina_insertar;
                $arrlistas['parametros']['total'] = $toto;
                $arrlistas['parametros']['ltclientes'] = $tabla_clientes;
                $arrlistas['parametros']['titulo'] = 'DESCARGA DE CLIENTES NUEVOS';
                echo json_encode($arrlistas);
                return;
            }
        }else{
        }
    }
    function tablaclientes_aprobadosAC(){
        if($this->input->is_ajax_request()){

            $this->form_validation->set_rules(
            'page','<strong>Error en numero de p&aacute;gina</strong>',
            'trim|required',
                array(
                    'required'  => 'Ocurrio un error inesperado...'
                )
            );

            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $toto = 0;
            if($this->form_validation->run() == FALSE){
                $resp = array();
                $resp['parametros']['rs'] = FALSE;
                $resp['parametros']['errores'] = validation_errors('<h3>','</h3>');
                $resp['parametros']['cla'] = 'danger grDanguer';
                echo json_encode($resp);
                return;
            }else{
                $mjs_resultados = '';
                $arrlistas = array();
                $limit = 10;
                $adjacent = 1;
                $page = $this->input->post('page');
                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                    OBTENCION DE DATOS PARA LAS BUSQUEDAS
                00000000000000000000000000000000000000000000000000000000000000000000000
                */

                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $clienteprueba = $this->input->post('cp');
                $vista_elegida = $this->input->post('tipovista');
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');
                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();
                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }
                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }                   
                    }
                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);
                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);
                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }
                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas
                );
                $tot_clientes = $this->ls->contar_clientes_procesadosAC($param_busqueda);
                $toto = $tot_clientes->totolu;
                /*
                00000000000000000000000000000000000000000000000000000000000000000000000
                0000000-LLENANDO ARREGLOS PARA LAS LISTAS DE CLIENTES-00000000000000000
                00000000000000000000000000000000000000000000000000000000000000000000000
                */
                /*SIN ENCUESTAS*/
                $obt_clientes = $this->ls->obtener_tabla_procesadosAC($param_busqueda,$start,$limit,1);
                $tabla_clientes =  array();
                $s=0;
                foreach ($obt_clientes as $sin ){
                    $tabla_clientes[$s]['ruta'] = $sin->Nombre_Ruta;
                    $tabla_clientes[$s]['nombrecliente'] = $sin->NombreAC;
                    $tabla_clientes[$s]['direccion'] = $sin->DireccionAC;
                    $tabla_clientes[$s]['telefono'] = $sin->TelefonoAC;
                    $tabla_clientes[$s]['contacto'] = $sin->ContactoAC;
                    $tabla_clientes[$s]['dias'] = $sin->DiasAC;
                    $tabla_clientes[$s]['fecharesolucion'] = $sin->FechaAAnalista;
                    $tabla_clientes[$s]['departamento'] = $sin->NombreDepartamento;
                    $tabla_clientes[$s]['municipio'] = $sin->NombreMunicipio;
                    $tabla_clientes[$s]['quien'] = $sin->QuienAutorizo;
                    $tabla_clientes[$s]['codigocli'] = $sin->CodigoAC;
                    $s++;
                }
                $pagina_insertar = $this->paginationaprobadosAC($limit,$adjacent,$toto,$page);
                $arrlistas['parametros']['rs'] = TRUE;
                $arrlistas['parametros']['paginacionsin'] = $pagina_insertar;
                $arrlistas['parametros']['total'] = $toto;
                $arrlistas['parametros']['ltclientes'] = $tabla_clientes;
                $arrlistas['parametros']['titulo'] = 'DESCARGA DE CLIENTES ACTUALIZADOS';
                $arrlistas['parametros']['distribuidorasselec'] = $arrgdistribuidoras;
                $arrlistas['parametros']['rutaselc'] = $rutas;
                echo json_encode($arrlistas);
                return;
            }
        }else{
        }
    }
    function plantillaokactu(){

        if($this->input->is_ajax_request()){

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            $tiponumero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
            $tipoletras = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $fechadesde = $this->input->post('datepickervalueactu');
                $fechahasta = $this->input->post('datepickerdosvalueactu');
                $distribuidoras = $this->input->post('cbmuldistribuidorareactu');
                $titulo = '';

                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');

                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();

                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }

                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }
                    }

                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);

                }
                $rutas = '';
                $toto = 0;
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }
                $param_busqueda = array(
                    'fechadesde' => $fechadesde,
                    'fechahasta' => $fechahasta,
                    'distribuidoras' => $arraid_distribuidora
                );

                /*000000000000---ENCABEZADO---0000000000000000000*/
                $sheet->getStyle('A1:AH1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $sheet->getStyle('A1:AH1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
                $sheet->getStyle('A1:AH1')->getFont()->setBold( true );
                //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
                //$sheet->getRowDimension('1')->setRowHeight(100);
                $sheet->getDefaultColumnDimension()->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(55);
                $sheet->getColumnDimension('G')->setWidth(30);

                $sheet->getStyle('A1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('B1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('C1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('D1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('E1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('F1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('G1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('H1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('J1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('K1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('L1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('M1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('N1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('O1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('P1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Q1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('R1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('S1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('T1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('U1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('V1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('W1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('X1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Y1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Z1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AA1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AB1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AD1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AE1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AF1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AG1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AH1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AI1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AJ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // $sheet->getStyle('AK1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // $sheet->getStyle('AL1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


                $sheet->setCellValue('A1','ID_RUTA');
                $sheet->setCellValue('B1','CODIGO');
                $sheet->setCellValue('C1','NOMBRE');
                $sheet->setCellValue('D1','DIRECCION');
                $sheet->setCellValue('E1','TELEFONO');
                $sheet->setCellValue('F1','CORREO');
                $sheet->setCellValue('G1','CONTACTO');
                $sheet->setCellValue('H1','ESTADO');
                $sheet->setCellValue('I1','ORDEN_VISITA');
                $sheet->setCellValue('J1','LIMITE_CREDITO');
                $sheet->setCellValue('K1','SALDO');
                $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('M1','LUNES');
                $sheet->setCellValue('N1','MARTES');
                $sheet->setCellValue('O1','MIERCOLES');
                $sheet->setCellValue('P1','JUEVES');
                $sheet->setCellValue('Q1','VIERNES');
                $sheet->setCellValue('R1','SABADO');
                $sheet->setCellValue('S1','DOMINGO');
                $sheet->setCellValue('T1','NOMBRE_FISCAL');
                $sheet->setCellValue('U1','NIT_FISCAL');
                $sheet->setCellValue('V1','REF1');
                $sheet->setCellValue('W1','REF2');
                $sheet->setCellValue('X1','REF3');
                $sheet->setCellValue('Y1','REF4');
                $sheet->setCellValue('Z1','REF5');
                $sheet->setCellValue('AA1','LATITUD');
                $sheet->setCellValue('AB1','LONGITUD');
                $sheet->setCellValue('AC1','REF6');
                $sheet->setCellValue('AD1','REF7');
                $sheet->setCellValue('AE1','REF8');
                $sheet->setCellValue('AF1','REF9');
                $sheet->setCellValue('AG1','REF10');
                $sheet->setCellValue('AH1','NCR');
                // $sheet->setCellValue('AI1','DEPARTAMENTO');
                // $sheet->setCellValue('AJ1','MUNICIPIO');
                // $sheet->setCellValue('AK1','TIPO_PUNTO_VENTA');
                // $sheet->setCellValue('AL1','GIRO_NEGOCIO');
                $separ_dias = '';
                $obt_clientes = $this->ls->lista_clientes_actualizados_v2($param_busqueda);
                $tabla_clientes =  array();
                $s=0;    
                $telefono=0;
                $i = 2;
                foreach ($obt_clientes as $sin ){
                    // $tabla_clientes[$i]['idcliente'] = $sin->Id_Actu_Info_Cli;
                    // $tabla_clientes[$i]['ruta'] = $sin->Nombre_Ruta;

                    $telefono = '';
                    $sheet->setCellValue('A'.$i,$sin->Nombre_Ruta);
                    $sheet->setCellValue('B'.$i,$sin->Codigo_Cliente);
                    $sheet->setCellValue('C'.$i,$sin->Nombre);
                    $sheet->setCellValue('D'.$i,$sin->Direccion);
                    if($sin->Telefono == '0000-0000'){
                        $telefono = 0;
                    }else{
                        $telefono = $sin->Telefono;
                    }
                    $sheet->setCellValue('E'.$i,$telefono);
                    $sheet->setCellValue('F'.$i,'NA');
                    $sheet->setCellValue('G'.$i,$sin->Contacto);
                    $sheet->setCellValue('H'.$i,'1');
                    $ordenvisita = 1;
                    if($sin->Orden_Visita == 0){
                        $ordenvisita = 1;
                    }else{
                        $ordenvisita = $sin->Orden_Visita;
                    }
                    $sheet->setCellValue('I'.$i,$ordenvisita);
                    $sheet->setCellValue('J'.$i,'100');
                    $sheet->setCellValue('K'.$i,'0');
                    $sheet->setCellValue('L'.$i,'1');
                    $separ_dias = explode(",", $sin->Dias);
                    $sheet->setCellValue('M'.$i,substr($separ_dias[0],-1));
                    $sheet->setCellValue('N'.$i,substr($separ_dias[1],-1));
                    $sheet->setCellValue('O'.$i,substr($separ_dias[2],-1));
                    $sheet->setCellValue('P'.$i,substr($separ_dias[3],-1));
                    $sheet->setCellValue('Q'.$i,substr($separ_dias[4],-1));
                    $sheet->setCellValue('R'.$i,substr($separ_dias[5],-1));
                    $sheet->setCellValue('S'.$i,substr($separ_dias[6],-1));
                    $sheet->setCellValue('T'.$i,$sin->Nombre);
                    $sheet->setCellValue('U'.$i,'NA');
                    $sheet->setCellValue('V'.$i,$sin->RefUno);
                    $sheet->setCellValue('W'.$i,$sin->Nombre);
                    $sheet->setCellValue('X'.$i,'NA');
                    $sheet->setCellValue('Y'.$i,'0');
                    $sheet->setCellValue('Z'.$i,'0');
                    $sheet->setCellValueExplicit('AA'.$i,$sin->Latitud,$tiponumero);
                    $sheet->setCellValue('AB'.$i,$sin->Longitud);
                    $sheet->setCellValue('AC'.$i,'0');
                    $sheet->setCellValue('AD'.$i,'50');
                    $sheet->setCellValue('AE'.$i,'0');
                    $sheet->setCellValue('AF'.$i,'0');
                    $sheet->setCellValue('AG'.$i,'0');
                    $sheet->setCellValue('AH'.$i,'NA');
                    // $sheet->setCellValue('AI'.$i,$nombredepa);
                    // $sheet->setCellValue('AJ'.$i,$nombremuni);
                    // $sheet->setCellValue('AK'.$i,$nombretpunv);
                    // $sheet->setCellValue('AL'.$i,$nombregirone);
                    //$sheet->setCellValue('G'.$i,'=C'.$i);
                    $i++;
                }

                $fecha_actual = date('Y_m_d_h_i_s');
                $cod_aleatorio = numero_aleatorio(7);
                $nombre_archivo = 'reporte-clientes/Actualizacion_clientes_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

                $resp = array(
                    'rs' => TRUE,
                    'info' => 'La Plantilla de Clientes Actualizados Se Genero Satisfactoriamente',
                    // 'clientesactualizados' => $tabla_clientes,
                    'cla' => 'success grSuccess',
                    'archivo' => $nombre_archivo
                );
                echo json_encode($resp);
                return;

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso. ERROR DESCONICIDO',
                'cla' => 'success grDanguer'
            );
            echo json_encode($resp);
            return;
        }

    }

    function plantillaok(){
        if($this->input->is_ajax_request()){
            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            $tiponumero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
            $tipoletras = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
            $fechadesde = '';
            $fechahasta = '';


            // $fechadesde = $this->input->post('datepickervalue');
            // $fechahasta = $this->input->post('datepickerdosvalue');

            // $fechadesde = str_replace("am","",$fechadesde);
            // $fechadesde = str_replace("pm","",$fechadesde);

            // $fechahasta = str_replace("am","",$fechahasta);
            // $fechahasta = str_replace("pm","",$fechahasta);
                $clienteprueba = $this->input->post('cp');
                $vista_elegida = $this->input->post('tipovista');
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');

                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();

                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }

                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }                   
                    }

                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);

                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }

                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas
                );

            $resultcanti = 0;
            $fecha_actual = date('Y_m_d_h_i_s');
            $obtener_clientes = $this->ls->obtener_listado($param_busqueda);
            $data_Add_SincroCli = array();
            // obtener_tabla_procesados
            $resultcanti = count($obtener_clientes);
            $i = 2;
            $fecha_actual_p = date('Y-m-d H:i:s');

            if(strcmp($this->session->userdata('pais'), 'HONDURAS') == 0 ){
                /*000000000000---ENCABEZADO---0000000000000000000*/
                $sheet->getStyle('A1:AY1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $sheet->getStyle('A1:AY1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
                $sheet->getStyle('A1:AY1')->getFont()->setBold( true );
                //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
                //$sheet->getRowDimension('1')->setRowHeight(100);
                $sheet->getDefaultColumnDimension()->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(55);
                $sheet->getColumnDimension('G')->setWidth(30);
                $sheet->getStyle('A1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('B1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('C1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('D1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('E1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('F1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('G1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('H1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('J1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('K1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('L1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('M1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('N1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('O1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('P1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Q1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('R1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('S1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('T1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('U1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('V1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('W1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('X1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Y1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Z1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AA1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AB1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AD1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AE1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AF1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AG1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AH1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AI1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AJ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AK1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AL1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AM1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AN1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AO1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AP1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AQ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AR1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AS1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AT1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AU1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AV1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AW1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AX1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AY1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                /*CABEZERA DE LA TABLA EXCEL*/
                $sheet->setCellValue('A1','RUTA');
                $sheet->setCellValue('B1','CLIENTE');
                $sheet->setCellValue('C1','NOMBRE');
                $sheet->setCellValue('D1','DIRECCION');
                $sheet->setCellValue('E1','TELEFONO');
                $sheet->setCellValue('F1','CORREO');
                $sheet->setCellValue('G1','CONTACTO');
                $sheet->setCellValue('H1','LATITUD');
                $sheet->setCellValue('I1','LONGITUD');
                $sheet->setCellValue('J1','ESTADO');
                $sheet->setCellValue('K1','LIMITE_CREDITO');
                $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('M1','LUNES');
                $sheet->setCellValue('N1','MARTES');
                $sheet->setCellValue('O1','MIERCOLES');
                $sheet->setCellValue('P1','JUEVES');
                $sheet->setCellValue('Q1','VIERNES');
                $sheet->setCellValue('R1','SABADO');
                $sheet->setCellValue('S1','DOMINGO');
                $sheet->setCellValue('T1','ORDEN_VISITA');
                $sheet->setCellValue('U1','SALDO');
                $sheet->setCellValue('V1','NIT_FISCAL');
                $sheet->setCellValue('W1','RTN');
                $sheet->setCellValue('X1','NOMBRE_FISCAL');
                $sheet->setCellValue('Y1','REF2');
                $sheet->setCellValue('Z1','REF6');
                $sheet->setCellValue('AA1','FRECUENCIA');
                $sheet->setCellValue('AB1','PAIS');
                $sheet->setCellValue('AC1','DEPTO');
                $sheet->setCellValue('AD1','MUNICIPIO');
                $sheet->setCellValue('AE1','TIPO_PUNTO_VENTA');
                $sheet->setCellValue('AF1','GIRO_NEGOCIO');
                $sheet->setCellValue('AG1','TIPO_FACTURACION');
                $sheet->setCellValue('AH1','CONDICION_CLIENTE');
                $sheet->setCellValue('AI1','DIA_COBRO');
                $sheet->setCellValue('AJ1','MONTO_CREDITO');
                $sheet->setCellValue('AK1','CANTIDAD_EXHIBIDORES');
                $sheet->setCellValue('AL1','EXHIBIDOR_UNO');
                $sheet->setCellValue('AM1','EXHIBIDOR_DOS');
                $sheet->setCellValue('AN1','EXHIBIDOR_TRES');
                $sheet->setCellValue('AO1','COMPRA_S_B');
                $sheet->setCellValue('AP1','COMPRA_S_D');
                $sheet->setCellValue('AQ1','COMPRA_S_Y');
                $sheet->setCellValue('AR1','COMPRA_S_F');
                $sheet->setCellValue('AS1','FECHA_INGRESO');
                $sheet->setCellValue('AT1','FECHA_RESOLUCION');
                $sheet->setCellValue('AU1','EDITADO');
                $sheet->setCellValue('AV1','COMENTARIO_E');
                $sheet->setCellValue('AW1','FECHA_RESOLUCION_A');
                $sheet->setCellValue('AX1','FECHA_PROCESADO');
                $sheet->setCellValue('AY1','ID_CLIENTE');
                $sheet->setCellValue('AZ1','TOKEN');
                /*IMPRIMIENDO DATOS EN EXCEL*/
                $separ_dias = '';
                /*0000000000000000000000000000000000000000000000*/
                foreach ($obtener_clientes as $cli )
                {



                    $dia_nombre = '';
                    $telefono = '';
                    if($cli->Telefono == '0000-0000'){
                        $telefono = 0;
                    }else{
                        $telefono = $cli->Telefono;
                    }
                    if($cli->Dia_Cobro == 1){
                        $dia_nombre = 'LUNES';
                    }elseif($cli->Dia_Cobro == 2){
                        $dia_nombre = 'MARTES';
                    }elseif($cli->Dia_Cobro == 3){
                        $dia_nombre = 'MIERCOLES';
                    }elseif($cli->Dia_Cobro == 4){
                        $dia_nombre = 'JUEVES';
                    }elseif($cli->Dia_Cobro == 5){
                        $dia_nombre = 'VIERNES';
                    }elseif($cli->Dia_Cobro == 6){
                        $dia_nombre = 'SABADO';
                    }elseif($cli->Dia_Cobro == 7){
                        $dia_nombre = 'DOMINGO';
                    }else{
                        $dia_nombre = 'NA';
                    }
                    $nom_exhibidoru = '';
                    $nom_exhibidord = '';
                    $nom_exhibidort = '';
                    $valor_exu = '';
                    $valor_exd = '';
                    $valor_ext = '';
                    /*----------------------------------*/
                    /*EVALUAR LA CANTIDAD DE EXHIBIDORES*/
                    /*----------------------------------*/
                    if($cli->Cantidad_Exhibidor == 1){
                        $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                        foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                        $valor_exd = '';
                        $valor_ext = '';
                    }elseif($cli->Cantidad_Exhibidor == 2){
                        $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                        foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                        /*---------------------------------------------------------------------*/
                        $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                        foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->SKU_Exhibidor;}
                        $valor_ext = '';
                    }elseif($cli->Cantidad_Exhibidor == 3){
                        $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                        foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                        /*---------------------------------------------------------------------*/
                        $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                        foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->SKU_Exhibidor;}
                        /*---------------------------------------------------------------------*/
                        $nom_exhibidort = $this->cl->exhibidor_select($cli->Exhibiror_Tres);
                        foreach ($nom_exhibidort as $next){$valor_ext = $next->SKU_Exhibidor;}
                    }else{
                        $valor_exu = '';
                        $valor_exd = '';
                        $valor_ext = '';
                    }

                    //VALIDACR TIPO DE CLIENTE SEGUN CANAL
                    /*                    
                        TIPO DE CLIENTE
                        1: DETALLE
                        2: MAYOREO
                        3: PREFERENCIAL
                        4: GUDAFF

                    */

                    $tipo_cliente = 0;

                    if (strcmp($cli->Canal, "DETALLE") == 0) {
                        $tipo_cliente = 3;
                    }elseif(strcmp($cli->Canal, "PREFERENCIAL") == 0) {
                        $tipo_cliente = 3;
                    }else{
                        $tipo_cliente = 3;
                    }

                    $sheet->setCellValue('A'.$i,$cli->Nombre_Ruta);
                    $sheet->setCellValue('B'.$i,$cli->Codigo);
                    $sheet->setCellValue('C'.$i,$cli->Nombre);
                    $sheet->setCellValue('D'.$i,$cli->Direccion);
                    $sheet->setCellValue('E'.$i,$telefono);
                    $sheet->setCellValue('F'.$i,'NA');
                    $sheet->setCellValue('G'.$i,$cli->Contacto);
                    $sheet->setCellValue('H'.$i,$cli->Longitud);
                    $sheet->setCellValueExplicit('I'.$i,$cli->Latitud,$tiponumero);
                    $sheet->setCellValue('J'.$i,1);
                    $sheet->setCellValue('K'.$i,3000);
                    $sheet->setCellValue('L'.$i,$tipo_cliente);
                    $separ_dias = explode(",", $cli->Dias);


                    $dias_separados = explode(',',$cli->Dias);
                    $orden_separados = explode(',',$cli->Ord_VisitaSema);
                    $OrdeVDinamico = 0;

                    if(count($orden_separados) < 7){
                        $orden_separados[0] = $cli->Orden_Visita;
                        $orden_separados[1] = $cli->Orden_Visita;
                        $orden_separados[2] = $cli->Orden_Visita;
                        $orden_separados[3] = $cli->Orden_Visita;
                        $orden_separados[4] = $cli->Orden_Visita;
                        $orden_separados[5] = $cli->Orden_Visita;
                        $orden_separados[6] = $cli->Orden_Visita;
                    }else{
                        if(empty($orden_separados[0]))
                        $orden_separados[0] = $cli->Orden_Visita;
                        if(empty($orden_separados[1]))
                        $orden_separados[1] = $cli->Orden_Visita;
                        if(empty($orden_separados[2]))
                        $orden_separados[2] = $cli->Orden_Visita;
                        if(empty($orden_separados[3]))
                        $orden_separados[3]= $cli->Orden_Visita;
                        if(empty($orden_separados[4]))
                        $orden_separados[4] = $cli->Orden_Visita;
                        if(empty($orden_separados[5]))
                        $orden_separados[5] = $cli->Orden_Visita;
                        if(empty($orden_separados[6]))
                        $orden_separados[6] = $cli->Orden_Visita;
                    }

                    if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[0];
                    }elseif( strcmp($dias_separados[1], 'M_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[1];
                    }elseif( strcmp($dias_separados[2], 'I_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[2];
                    }elseif( strcmp($dias_separados[3], 'J_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[3];
                    }elseif( strcmp($dias_separados[4], 'V_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[4];
                    }elseif( strcmp($dias_separados[5], 'S_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[5];
                    }elseif( strcmp($dias_separados[6], 'D_1') == 0 ){
                        $OrdeVDinamico =$orden_separados[6];
                    }

                    $sheet->setCellValue('M'.$i,substr($separ_dias[0],-1));
                    $sheet->setCellValue('N'.$i,substr($separ_dias[1],-1));
                    $sheet->setCellValue('O'.$i,substr($separ_dias[2],-1));
                    $sheet->setCellValue('P'.$i,substr($separ_dias[3],-1));
                    $sheet->setCellValue('Q'.$i,substr($separ_dias[4],-1));
                    $sheet->setCellValue('R'.$i,substr($separ_dias[5],-1));
                    $sheet->setCellValue('S'.$i,substr($separ_dias[6],-1));
                    $sheet->setCellValue('T'.$i,$OrdeVDinamico);
                    $sheet->setCellValue('U'.$i,0);
                    $sheet->setCellValue('V'.$i,$cli->Dui);
                    $sheet->setCellValue('W'.$i,$cli->Nit);
                    $sheet->setCellValue('X'.$i,$cli->Nombre);
                    $sheet->setCellValue('Y'.$i,'N/A');
                    $sheet->setCellValue('Z'.$i,'0');
                    $sheet->setCellValue('AA'.$i,$cli->RefUno);
                    $sheet->setCellValue('AB'.$i,'HONDURAS');
                    $sheet->setCellValue('AC'.$i,mb_strtoupper(quitar_acentos($cli->NombreDepartamento)));
                    $sheet->setCellValue('AD'.$i,mb_strtoupper(quitar_acentos($cli->NombreMunicipio)));
                    $sheet->setCellValue('AE'.$i,$cli->Nombre_TpuntoV);
                    $sheet->setCellValue('AF'.$i,$cli->Nombre_Gnegocio);
                    $sheet->setCellValue('AG'.$i,$cli->Nombre_Tfacturacion);
                    $sheet->setCellValue('AH'.$i,$cli->Nombre_Condicionc);
                    $sheet->setCellValue('AI'.$i,$dia_nombre);
                    $sheet->setCellValue('AJ'.$i,$cli->Monto_Credito);
                    $sheet->setCellValue('AK'.$i,$cli->Cantidad_Exhibidor);
                    $sheet->setCellValue('AL'.$i,$valor_exu);
                    $sheet->setCellValue('AM'.$i,$valor_exd);
                    $sheet->setCellValue('AN'.$i,$valor_ext);
                    $sheet->setCellValue('AO'.$i,$cli->CompraS_B);
                    $sheet->setCellValue('AP'.$i,$cli->CompraS_D);
                    $sheet->setCellValue('AQ'.$i,$cli->CompraS_Y);
                    $sheet->setCellValue('AR'.$i,$cli->CompraS_F);
                    $sheet->setCellValue('AS'.$i,$cli->Fecha_Ingreso);
                    $sheet->setCellValue('AT'.$i,$cli->Fecha_Resolucion);
                    $sheet->setCellValue('AU'.$i,$cli->Editado);
                    $sheet->setCellValue('AV'.$i,$cli->Comentario_E);
                    $sheet->setCellValue('AW'.$i,$cli->Fecha_Resolucion_R);
                    $sheet->setCellValue('AX'.$i,$fecha_actual);
                    $sheet->setCellValue('AY'.$i,$cli->Id_Cliente);
                    $sheet->setCellValue('AZ'.$i,'K'.$cli->TokenCliNuevo);
                    $i++;

                    /* DATA - SINCRONIZACION - 02/07/2021 */
                    $IdRuta_SysNew = '';
                    $IdRuta_SysNew = $cli->Nombre_Ruta;
                    $IdRuta_SysNew = str_replace(".","",$IdRuta_SysNew);
                    $dia_cobro = 0;
                    if($cli->Dia_Cobro == 'LUNES'){
                        $dia_cobro = 1;
                    }elseif($cli->Dia_Cobro == 'MARTES'){
                        $dia_cobro = 2;
                    }elseif($cli->Dia_Cobro == 'MIERCOLES'){
                        $dia_cobro = 3;
                    }elseif($cli->Dia_Cobro == 'JUEVES'){
                        $dia_cobro = 4;
                    }elseif($cli->Dia_Cobro == 'VIERNES'){
                        $dia_cobro = 5;
                    }elseif($cli->Dia_Cobro == 'SABADO'){
                        $dia_cobro = 6;
                    }elseif($cli->Dia_Cobro == 'DOMINGO'){
                        $dia_cobro = 7;
                    }else{
                        $dia_cobro = 0;
                    }         
                    $data_Add_SincroCli[] = [
                        'Cli_Id' => 0,
                        'Cli_codigo' => $cli->Codigo,
                        'Cli_nombre' => $cli->Nombre,
                        'Cli_direccion' => $cli->Direccion,
                        'Cli_Mun_Id' => $cli->Mun_Id,
                        'Cli_telefono' => $cli->Telefono,
                        'Cli_contacto' => $cli->Contacto,
                        'Cli_Tfc_Id' => $cli->Tfc_Id,
                        'Cli_dui' => $cli->Dui,
                        'Cli_num_registro' => $cli->Numero_Registro,
                        'Cli_nit' => $cli->Nit,
                        'Cli_Coc_Id' => $cli->Cod_Id,
                        'Cli_dia_cobro' => $dia_cobro,
                        'Cli_monto_credito' => $cli->Monto_Credito,
                        'Cli_l' => substr($separ_dias[0],-1),
                        'Cli_m' => substr($separ_dias[1],-1),
                        'Cli_mi' => substr($separ_dias[2],-1),
                        'Cli_j' => substr($separ_dias[3],-1),
                        'Cli_v' => substr($separ_dias[4],-1),
                        'Cli_s' => substr($separ_dias[5],-1),
                        'Cli_d' => substr($separ_dias[6],-1),
                        'Cli_orden_l' => $orden_separados[0],
                        'Cli_orden_m' => $orden_separados[1],
                        'Cli_orden_mi' => $orden_separados[2],
                        'Cli_orden_j' => $orden_separados[3],
                        'Cli_orden_v' => $orden_separados[4],
                        'Cli_orden_s' => $orden_separados[5],
                        'Cli_orden_d' => $orden_separados[6],
                        'Cli_frecuencia_visita' => $cli->RefUno,
                        'Cli_latitud' => $cli->Latitud,
                        'Cli_longitud' => $cli->Longitud,
                        'Cli_Ru_Id' => $IdRuta_SysNew,
                        'Cli_Gir_Id' => $cli->Gir_Id,
                        'Cli_foto' => $cli->Foto_Negocio,
                        'Cli_estado' => 1,
                        'Cli_estado_sys' => 'P',
                        'Cli_estado_analista' => 'A',
                        'Cli_estado_descarga' => 1,
                        'Cli_editado' => 0,
                        'Cli_comentario' => $cli->Comentario_E,
                        'Cli_tipo_cliente' => $cli->TipoCliente,
                        'Cli_ac_exhibidor' => 0,
                        'Cli_ac_cliente' => 1,
                        'Cli_us_resolucion' => $cli->quienresolucion,
                        'Cli_fecha_ingreso' => $cli->Fecha_Ingreso,
                        'Cli_fecha_r_supervisor' => $cli->Fecha_Resolucion,
                        'Cli_fecha_r_analista' => $fecha_actual_p,
                        'Cli_ul_fecha_ac_cliente' => $cli->Fecha_Ingreso,
                        'Cli_ul_fecha_ac_exhibidor' => $cli->UlFechaActuExh,
                        'Cli_token' => $cli->TokenCliNuevo,
                        'Cli_actu_exh' => 0,
                        'Cli_bloq_exh' => 0,
                        'Cli_tipo_us' => $cli->tipo_us,
                        'Cli_cantidad_CMR' => $cli->Cantidad_CMR
                    ];


                }
                $cod_aleatorio = numero_aleatorio(7);
                $nombre_archivo = '/var/www/html/Uploads/Plantilla_Excel/Clientes_Nuevos/CLIENTES_NUEVOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                // $nombre_archivo = '../Uploads/Plantilla_Excel/Clientes_Nuevos/CLIENTES_NUEVOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);



            }elseif(strcmp($this->session->userdata('pais'), 'GUATEMALA') == 0 ){
            
                     /*000000000000---ENCABEZADO---0000000000000000000*/
                     $sheet->getStyle('A1:AY1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                     $sheet->getStyle('A1:AY1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
                     $sheet->getStyle('A1:AY1')->getFont()->setBold( true );
                     //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
                     $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
                     //$sheet->getRowDimension('1')->setRowHeight(100);
                     $sheet->getDefaultColumnDimension()->setWidth(13);
                     $sheet->getColumnDimension('C')->setWidth(35);
                     $sheet->getColumnDimension('D')->setWidth(55);
                     $sheet->getColumnDimension('G')->setWidth(30);
                     $sheet->getStyle('A1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('B1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('C1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('D1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('E1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('F1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('G1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('H1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('I1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('J1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('K1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('L1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('M1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('N1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('O1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('P1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('Q1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('R1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('S1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('T1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('U1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('V1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('W1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('X1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('Y1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('Z1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AA1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AB1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AD1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AE1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AF1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AG1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AH1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AI1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AJ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AK1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AL1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AM1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AN1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AO1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AP1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AQ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AR1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AS1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AT1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AU1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AV1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AW1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AX1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     $sheet->getStyle('AY1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                     /*CABEZERA DE LA TABLA EXCEL*/
                     $sheet->setCellValue('A1','RUTA');
                     $sheet->setCellValue('B1','CLIENTE');
                     $sheet->setCellValue('C1','NOMBRE');
                     $sheet->setCellValue('D1','DIRECCION');
                     $sheet->setCellValue('E1','TELEFONO');
                     $sheet->setCellValue('F1','CORREO');
                     $sheet->setCellValue('G1','CONTACTO');
                     $sheet->setCellValue('H1','LATITUD');
                     $sheet->setCellValue('I1','LONGITUD');
                     $sheet->setCellValue('J1','ESTADO');
                     $sheet->setCellValue('K1','LIMITE_CREDITO');
                     $sheet->setCellValue('L1','TIPO');
                     $sheet->setCellValue('M1','LUNES');
                     $sheet->setCellValue('N1','MARTES');
                     $sheet->setCellValue('O1','MIERCOLES');
                     $sheet->setCellValue('P1','JUEVES');
                     $sheet->setCellValue('Q1','VIERNES');
                     $sheet->setCellValue('R1','SABADO');
                     $sheet->setCellValue('S1','DOMINGO');
                     $sheet->setCellValue('T1','ORDEN_VISITA');
                     $sheet->setCellValue('U1','SALDO');
                     $sheet->setCellValue('V1','NIT_FISCAL');
                     $sheet->setCellValue('W1','RTN');
                     $sheet->setCellValue('X1','NOMBRE_FISCAL');
                     $sheet->setCellValue('Y1','REF2');
                     $sheet->setCellValue('Z1','REF6');
                     $sheet->setCellValue('AA1','FRECUENCIA');
                     $sheet->setCellValue('AB1','PAIS');
                     $sheet->setCellValue('AC1','DEPTO');
                     $sheet->setCellValue('AD1','MUNICIPIO');
                     $sheet->setCellValue('AE1','TIPO_PUNTO_VENTA');
                     $sheet->setCellValue('AF1','GIRO_NEGOCIO');
                     $sheet->setCellValue('AG1','TIPO_FACTURACION');
                     $sheet->setCellValue('AH1','CONDICION_CLIENTE');
                     $sheet->setCellValue('AI1','DIA_COBRO');
                     $sheet->setCellValue('AJ1','MONTO_CREDITO');
                     $sheet->setCellValue('AK1','CANTIDAD_EXHIBIDORES');
                     $sheet->setCellValue('AL1','EXHIBIDOR_UNO');
                     $sheet->setCellValue('AM1','EXHIBIDOR_DOS');
                     $sheet->setCellValue('AN1','EXHIBIDOR_TRES');
                     $sheet->setCellValue('AO1','COMPRA_S_B');
                     $sheet->setCellValue('AP1','COMPRA_S_D');
                     $sheet->setCellValue('AQ1','COMPRA_S_Y');
                     $sheet->setCellValue('AR1','COMPRA_S_F');
                     $sheet->setCellValue('AS1','FECHA_INGRESO');
                     $sheet->setCellValue('AT1','FECHA_RESOLUCION');
                     $sheet->setCellValue('AU1','EDITADO');
                     $sheet->setCellValue('AV1','COMENTARIO_E');
                     $sheet->setCellValue('AW1','FECHA_RESOLUCION_A');
                     $sheet->setCellValue('AX1','FECHA_PROCESADO');
                     $sheet->setCellValue('AY1','ID_CLIENTE');
                     $sheet->setCellValue('AZ1','TOKEN');
                     /*IMPRIMIENDO DATOS EN EXCEL*/
                     $separ_dias = '';
                     /*0000000000000000000000000000000000000000000000*/
                     foreach ($obtener_clientes as $cli )
                     {
                         $dia_nombre = '';
                         $telefono = '';
                         if($cli->Telefono == '0000-0000'){
                             $telefono = 0;
                         }else{
                             $telefono = $cli->Telefono;
                         }
                         if($cli->Dia_Cobro == 1){
                             $dia_nombre = 'LUNES';
                         }elseif($cli->Dia_Cobro == 2){
                             $dia_nombre = 'MARTES';
                         }elseif($cli->Dia_Cobro == 3){
                             $dia_nombre = 'MIERCOLES';
                         }elseif($cli->Dia_Cobro == 4){
                             $dia_nombre = 'JUEVES';
                         }elseif($cli->Dia_Cobro == 5){
                             $dia_nombre = 'VIERNES';
                         }elseif($cli->Dia_Cobro == 6){
                             $dia_nombre = 'SABADO';
                         }elseif($cli->Dia_Cobro == 7){
                             $dia_nombre = 'DOMINGO';
                         }else{
                             $dia_nombre = 'NA';
                         }
                         $nom_exhibidoru = '';
                         $nom_exhibidord = '';
                         $nom_exhibidort = '';
                         $valor_exu = '';
                         $valor_exd = '';
                         $valor_ext = '';
                         /*----------------------------------*/
                         /*EVALUAR LA CANTIDAD DE EXHIBIDORES*/
                         /*----------------------------------*/
                         if($cli->Cantidad_Exhibidor == 1){
                             $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                             foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                             $valor_exd = '';
                             $valor_ext = '';
                         }elseif($cli->Cantidad_Exhibidor == 2){
                             $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                             foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                             /*---------------------------------------------------------------------*/
                             $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                             foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->SKU_Exhibidor;}
                             $valor_ext = '';
                         }elseif($cli->Cantidad_Exhibidor == 3){
                             $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                             foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                             /*---------------------------------------------------------------------*/
                             $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                             foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->SKU_Exhibidor;}
                             /*---------------------------------------------------------------------*/
                             $nom_exhibidort = $this->cl->exhibidor_select($cli->Exhibiror_Tres);
                             foreach ($nom_exhibidort as $next){$valor_ext = $next->SKU_Exhibidor;}
                         }else{
                             $valor_exu = '';
                             $valor_exd = '';
                             $valor_ext = '';
                         }

                    //VALIDACR TIPO DE CLIENTE SEGUN CANAL
                    /*                    
                        TIPO DE CLIENTE
                        1: DETALLE
                        2: MAYOREO
                        3: PREFERENCIAL
                        4: GUDAFF

                    */

                    $tipo_cliente = 0;

                    if (strcmp($cli->Canal, "DETALLE") == 0) {
                        $tipo_cliente = 1;
                    }elseif(strcmp($cli->Canal, "PREFERENCIAL") == 0) {
                        $tipo_cliente = 1;
                    }else{
                        $tipo_cliente = 1;
                    }

                         $sheet->setCellValue('A'.$i,$cli->Nombre_Ruta);
                         $sheet->setCellValue('B'.$i,$cli->Codigo);
                         $sheet->setCellValue('C'.$i,$cli->Nombre);
                         $sheet->setCellValue('D'.$i,$cli->Direccion);
                         $sheet->setCellValue('E'.$i,$telefono);
                         $sheet->setCellValue('F'.$i,'NA');
                         $sheet->setCellValue('G'.$i,$cli->Contacto);
                         $sheet->setCellValue('H'.$i,$cli->Longitud);
                         $sheet->setCellValueExplicit('I'.$i,$cli->Latitud,$tiponumero);
                         $sheet->setCellValue('J'.$i,1);
                         $sheet->setCellValue('K'.$i,3000);
                         $sheet->setCellValue('L'.$i,$tipo_cliente);
                         $separ_dias = explode(",", $cli->Dias);


                        $dias_separados = explode(',',$cli->Dias);
                        $orden_separados = explode(',',$cli->Ord_VisitaSema);
                        $OrdeVDinamico = 0;

                        if(count($orden_separados) < 7){
                            $orden_separados[0] = $cli->Orden_Visita;
                            $orden_separados[1] = $cli->Orden_Visita;
                            $orden_separados[2] = $cli->Orden_Visita;
                            $orden_separados[3] = $cli->Orden_Visita;
                            $orden_separados[4] = $cli->Orden_Visita;
                            $orden_separados[5] = $cli->Orden_Visita;
                            $orden_separados[6] = $cli->Orden_Visita;
                        }else{
                            if(empty($orden_separados[0]))
                            $orden_separados[0] = $cli->Orden_Visita;
                            if(empty($orden_separados[1]))
                            $orden_separados[1] = $cli->Orden_Visita;
                            if(empty($orden_separados[2]))
                            $orden_separados[2] = $cli->Orden_Visita;
                            if(empty($orden_separados[3]))
                            $orden_separados[3]= $cli->Orden_Visita;
                            if(empty($orden_separados[4]))
                            $orden_separados[4] = $cli->Orden_Visita;
                            if(empty($orden_separados[5]))
                            $orden_separados[5] = $cli->Orden_Visita;
                            if(empty($orden_separados[6]))
                            $orden_separados[6] = $cli->Orden_Visita;
                        }

                        if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                            $OrdeVDinamico = $orden_separados[0];
                        }elseif( strcmp($dias_separados[1], 'M_1') == 0 ){
                            $OrdeVDinamico = $orden_separados[1];
                        }elseif( strcmp($dias_separados[2], 'I_1') == 0 ){
                            $OrdeVDinamico = $orden_separados[2];
                        }elseif( strcmp($dias_separados[3], 'J_1') == 0 ){
                            $OrdeVDinamico = $orden_separados[3];
                        }elseif( strcmp($dias_separados[4], 'V_1') == 0 ){
                            $OrdeVDinamico = $orden_separados[4];
                        }elseif( strcmp($dias_separados[5], 'S_1') == 0 ){
                            $OrdeVDinamico = $orden_separados[5];
                        }elseif( strcmp($dias_separados[6], 'D_1') == 0 ){
                            $OrdeVDinamico =$orden_separados[6];
                        }


                         $sheet->setCellValue('M'.$i,substr($separ_dias[0],-1));
                         $sheet->setCellValue('N'.$i,substr($separ_dias[1],-1));
                         $sheet->setCellValue('O'.$i,substr($separ_dias[2],-1));
                         $sheet->setCellValue('P'.$i,substr($separ_dias[3],-1));
                         $sheet->setCellValue('Q'.$i,substr($separ_dias[4],-1));
                         $sheet->setCellValue('R'.$i,substr($separ_dias[5],-1));
                         $sheet->setCellValue('S'.$i,substr($separ_dias[6],-1));
                         $sheet->setCellValue('T'.$i,$OrdeVDinamico);
                         $sheet->setCellValue('U'.$i,0);
                         $sheet->setCellValue('V'.$i,$cli->Dui);
                         $sheet->setCellValue('W'.$i,$cli->Dui);
                         $sheet->setCellValue('X'.$i,$cli->Nombre);
                         $sheet->setCellValue('Y'.$i,'N/A');
                         $sheet->setCellValue('Z'.$i,'0');
                         $sheet->setCellValue('AA'.$i,$cli->RefUno);
                         $sheet->setCellValue('AB'.$i,'GUATEMALA');
                         $sheet->setCellValue('AC'.$i,mb_strtoupper(quitar_acentos($cli->NombreDepartamento)));
                         $sheet->setCellValue('AD'.$i,mb_strtoupper(quitar_acentos($cli->NombreMunicipio)));
                         $sheet->setCellValue('AE'.$i,$cli->Nombre_TpuntoV);
                         $sheet->setCellValue('AF'.$i,$cli->Nombre_Gnegocio);
                         $sheet->setCellValue('AG'.$i,$cli->Nombre_Tfacturacion);
                         $sheet->setCellValue('AH'.$i,$cli->Nombre_Condicionc);
                         $sheet->setCellValue('AI'.$i,$dia_nombre);
                         $sheet->setCellValue('AJ'.$i,$cli->Monto_Credito);
                         $sheet->setCellValue('AK'.$i,$cli->Cantidad_Exhibidor);
                         $sheet->setCellValue('AL'.$i,$valor_exu);
                         $sheet->setCellValue('AM'.$i,$valor_exd);
                         $sheet->setCellValue('AN'.$i,$valor_ext);
                         $sheet->setCellValue('AO'.$i,$cli->CompraS_B);
                         $sheet->setCellValue('AP'.$i,$cli->CompraS_D);
                         $sheet->setCellValue('AQ'.$i,$cli->CompraS_Y);
                         $sheet->setCellValue('AR'.$i,$cli->CompraS_F);
                         $sheet->setCellValue('AS'.$i,$cli->Fecha_Ingreso);
                         $sheet->setCellValue('AT'.$i,$cli->Fecha_Resolucion);
                         $sheet->setCellValue('AU'.$i,$cli->Editado);
                         $sheet->setCellValue('AV'.$i,$cli->Comentario_E);
                         $sheet->setCellValue('AW'.$i,$cli->Fecha_Resolucion_R);
                         $sheet->setCellValue('AX'.$i,$fecha_actual);
                         $sheet->setCellValue('AY'.$i,$cli->Id_Cliente);
                         $sheet->setCellValue('AZ'.$i,strval('K'.$cli->TokenCliNuevo));
                         $i++;


                        /* DATA - SINCRONIZACION - 02/07/2021 */
                        $IdRuta_SysNew = '';
                        $IdRuta_SysNew = $cli->Nombre_Ruta;
                        $IdRuta_SysNew = str_replace(".","",$IdRuta_SysNew);
                        $dia_cobro = 0;
                        if($cli->Dia_Cobro == 'LUNES'){
                            $dia_cobro = 1;
                        }elseif($cli->Dia_Cobro == 'MARTES'){
                            $dia_cobro = 2;
                        }elseif($cli->Dia_Cobro == 'MIERCOLES'){
                            $dia_cobro = 3;
                        }elseif($cli->Dia_Cobro == 'JUEVES'){
                            $dia_cobro = 4;
                        }elseif($cli->Dia_Cobro == 'VIERNES'){
                            $dia_cobro = 5;
                        }elseif($cli->Dia_Cobro == 'SABADO'){
                            $dia_cobro = 6;
                        }elseif($cli->Dia_Cobro == 'DOMINGO'){
                            $dia_cobro = 7;
                        }else{
                            $dia_cobro = 0;
                        }             
                        $data_Add_SincroCli[] = [
                            'Cli_Id' => 0,
                            'Cli_codigo' => $cli->Codigo,
                            'Cli_nombre' => $cli->Nombre,
                            'Cli_direccion' => $cli->Direccion,
                            'Cli_Mun_Id' => $cli->Mun_Id,
                            'Cli_telefono' => $cli->Telefono,
                            'Cli_contacto' => $cli->Contacto,
                            'Cli_Tfc_Id' => $cli->Tfc_Id,
                            'Cli_dui' => $cli->Dui,
                            'Cli_num_registro' => $cli->Numero_Registro,
                            'Cli_nit' => $cli->Nit,
                            'Cli_Coc_Id' => $cli->Cod_Id,
                            'Cli_dia_cobro' => $dia_cobro,
                            'Cli_monto_credito' => $cli->Monto_Credito,
                            'Cli_l' => substr($separ_dias[0],-1),
                            'Cli_m' => substr($separ_dias[1],-1),
                            'Cli_mi' => substr($separ_dias[2],-1),
                            'Cli_j' => substr($separ_dias[3],-1),
                            'Cli_v' => substr($separ_dias[4],-1),
                            'Cli_s' => substr($separ_dias[5],-1),
                            'Cli_d' => substr($separ_dias[6],-1),
                            'Cli_orden_l' => $orden_separados[0],
                            'Cli_orden_m' => $orden_separados[1],
                            'Cli_orden_mi' => $orden_separados[2],
                            'Cli_orden_j' => $orden_separados[3],
                            'Cli_orden_v' => $orden_separados[4],
                            'Cli_orden_s' => $orden_separados[5],
                            'Cli_orden_d' => $orden_separados[6],
                            'Cli_frecuencia_visita' => $cli->RefUno,
                            'Cli_latitud' => $cli->Latitud,
                            'Cli_longitud' => $cli->Longitud,
                            'Cli_Ru_Id' => $IdRuta_SysNew,
                            'Cli_Gir_Id' => $cli->Gir_Id,
                            'Cli_foto' => $cli->Foto_Negocio,
                            'Cli_estado' => 1,
                            'Cli_estado_sys' => 'P',
                            'Cli_estado_analista' => 'A',
                            'Cli_estado_descarga' => 1,
                            'Cli_editado' => 0,
                            'Cli_comentario' => $cli->Comentario_E,
                            'Cli_tipo_cliente' => $cli->TipoCliente,
                            'Cli_ac_exhibidor' => 0,
                            'Cli_ac_cliente' => 1,
                            'Cli_us_resolucion' => $cli->quienresolucion,
                            'Cli_fecha_ingreso' => $cli->Fecha_Ingreso,
                            'Cli_fecha_r_supervisor' => $cli->Fecha_Resolucion,
                            'Cli_fecha_r_analista' => $fecha_actual_p,
                            'Cli_ul_fecha_ac_cliente' => $cli->Fecha_Ingreso,
                            'Cli_ul_fecha_ac_exhibidor' => $cli->UlFechaActuExh,
                            'Cli_token' => $cli->TokenCliNuevo,
                            'Cli_actu_exh' => 0,
                            'Cli_bloq_exh' => 0,
                            'Cli_tipo_us' => $cli->tipo_us,
                            'Cli_cantidad_CMR' => $cli->Cantidad_CMR
                        ];


                     }
                     $cod_aleatorio = numero_aleatorio(7);
                     $nombre_archivo = '/var/www/html/Uploads/Plantilla_Excel/Clientes_Nuevos/CLIENTES_NUEVOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                    // $nombre_archivo = '../Uploads/Plantilla_Excel/Clientes_Nuevos/CLIENTES_NUEVOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                     $writer = new Xlsx($spreadsheet);
                     $writer->save($nombre_archivo);
                     $spreadsheet->disconnectWorksheets();
                     unset($spreadsheet);       
        
            }else{
                /*000000000000---ENCABEZADO---0000000000000000000*/
                $sheet->getStyle('A1:AK1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                $sheet->getStyle('A1:AK1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
                $sheet->getStyle('A1:AK1')->getFont()->setBold( true );
                //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
                //$sheet->getRowDimension('1')->setRowHeight(100);
                $sheet->getDefaultColumnDimension()->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(55);
                $sheet->getColumnDimension('G')->setWidth(30);
                // $sheet->getStyle('A1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('B1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('C1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('D1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('E1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('F1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('G1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('H1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('I1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('J1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('K1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('L1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('M1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('N1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('O1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('P1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('Q1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('R1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('S1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('T1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('U1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('V1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('W1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('X1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('Y1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('Z1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AA1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AB1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AD1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AE1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AF1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AG1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AH1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AI1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AJ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // $sheet->getStyle('AK1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // $sheet->getStyle('AL1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


                // $sheet->getStyle('AM1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $sheet->getStyle('AN1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                //$sheet->getDefaultStyle()->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A1','RUTA');
                $sheet->setCellValue('B1','CODIGO');
                $sheet->setCellValue('C1','NOMBRE');
                $sheet->setCellValue('D1','DIRECCION');
                $sheet->setCellValue('E1','TELEFONO');
                $sheet->setCellValue('F1','CORREO');
                $sheet->setCellValue('G1','CONTACTO');
                $sheet->setCellValue('H1','ESTADO');
                $sheet->setCellValue('I1','ORDEN_VISITA');
                $sheet->setCellValue('J1','LIMITE_CREDITO');
                $sheet->setCellValue('K1','SALDO');
                $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('L1','R');
                $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('M1','LUNES');
                $sheet->setCellValue('N1','MARTES');
                $sheet->setCellValue('O1','MIERCOLES');
                $sheet->setCellValue('P1','JUEVES');
                $sheet->setCellValue('Q1','VIERNES');
                $sheet->setCellValue('R1','SABADO');
                $sheet->setCellValue('S1','DOMINGO');
                $sheet->setCellValue('T1','NOMBRE_FISCAL');
                $sheet->setCellValue('U1','NIT_FISCAL');
                $sheet->setCellValue('V1','REF1');
                $sheet->setCellValue('W1','REF2');
                $sheet->setCellValue('X1','REF3');
                $sheet->setCellValue('Y1','REF4');
                $sheet->setCellValue('Z1','REF5');
                $sheet->setCellValue('AA1','LATITUD');
                $sheet->setCellValue('AB1','LONGITUD');
                $sheet->setCellValue('AC1','REF6');
                $sheet->setCellValue('AD1','REF7');
                $sheet->setCellValue('AE1','REF8');
                $sheet->setCellValue('AF1','REF9');
                $sheet->setCellValue('AG1','REF10');
                $sheet->setCellValue('AH1','REF11');
                $sheet->setCellValue('AI1','DEPARTAMENTO');
                $sheet->setCellValue('AJ1','MUNICIPIO');
                $sheet->setCellValue('AK1','CONCATENADO');
                // $sheet->setCellValue('AK1','TIPO_PUNTO_VENTA');
                // $sheet->setCellValue('AL1','GIRO_NEGOCIO');
                // $sheet->setCellValue('AM1','FECHA_INGRESO');
                // $sheet->setCellValue('AN1','FECHA_RESOLUCION');
                $separ_dias = '';
                /*0000000000000000000000000000000000000000000000*/
                foreach ($obtener_clientes as $cli )
                {
                    $telefono = '';
                    $sheet->setCellValue('A'.$i,$cli->Nombre_Ruta);
                    $sheet->setCellValue('B'.$i,$cli->Codigo);
                    $sheet->setCellValue('C'.$i,$cli->Nombre);
                    $sheet->setCellValue('D'.$i,$cli->Direccion);
                    if($cli->Telefono == '0000-0000'){
                        $telefono = 0;
                    }else{
                        $telefono = $cli->Telefono;
                    }

                    //VALIDACR TIPO DE CLIENTE SEGUN CANAL
                    /*                    
                        TIPO DE CLIENTE
                        1: DETALLE
                        2: MAYOREO
                        3: PREFERENCIAL
                        4: GUDAFF

                    */

                    $tipo_cliente = 0;

                    if (strcmp($cli->Canal, "DETALLE") == 0) {
                        $tipo_cliente = 1;
                    }elseif(strcmp($cli->Canal, "PREFERENCIAL") == 0) {
                        $tipo_cliente = 1;
                    }else{
                        $tipo_cliente = 1;
                    }

                    $dias_separados = explode(',',$cli->Dias);
                    $orden_separados = explode(',',$cli->Ord_VisitaSema);

                    if(count($orden_separados) < 7){
                        $orden_separados[0] = $cli->Orden_Visita;
                        $orden_separados[1] = $cli->Orden_Visita;
                        $orden_separados[2] = $cli->Orden_Visita;
                        $orden_separados[3] = $cli->Orden_Visita;
                        $orden_separados[4] = $cli->Orden_Visita;
                        $orden_separados[5] = $cli->Orden_Visita;
                        $orden_separados[6] = $cli->Orden_Visita;
                    }else{
                        if(empty($orden_separados[0]))
                        $orden_separados[0] = $cli->Orden_Visita;
                        if(empty($orden_separados[1]))
                        $orden_separados[1] = $cli->Orden_Visita;
                        if(empty($orden_separados[2]))
                        $orden_separados[2] = $cli->Orden_Visita;
                        if(empty($orden_separados[3]))
                        $orden_separados[3]= $cli->Orden_Visita;
                        if(empty($orden_separados[4]))
                        $orden_separados[4] = $cli->Orden_Visita;
                        if(empty($orden_separados[5]))
                        $orden_separados[5] = $cli->Orden_Visita;
                        if(empty($orden_separados[6]))
                        $orden_separados[6] = $cli->Orden_Visita;
                    }

                    $OrdeVDinamico = 0;

                    if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[0];
                    }elseif( strcmp($dias_separados[1], 'M_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[1];
                    }elseif( strcmp($dias_separados[2], 'I_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[2];
                    }elseif( strcmp($dias_separados[3], 'J_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[3];
                    }elseif( strcmp($dias_separados[4], 'V_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[4];
                    }elseif( strcmp($dias_separados[5], 'S_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[5];
                    }elseif( strcmp($dias_separados[6], 'D_1') == 0 ){
                        $OrdeVDinamico =$orden_separados[6];
                    }

                    $sheet->setCellValue('E'.$i,$telefono);
                    $sheet->setCellValue('F'.$i,'NA');
                    $sheet->setCellValue('G'.$i,$cli->Contacto);
                    $sheet->setCellValue('H'.$i,'1');
                    $sheet->setCellValue('I'.$i,$OrdeVDinamico);
                    $sheet->setCellValue('J'.$i,'100');
                    $sheet->setCellValue('K'.$i,'0');
                    $sheet->setCellValue('L'.$i,$tipo_cliente);
                    $separ_dias = explode(",", $cli->Dias);




                    $sheet->setCellValue('M'.$i,substr($separ_dias[0],-1));
                    $sheet->setCellValue('N'.$i,substr($separ_dias[1],-1));
                    $sheet->setCellValue('O'.$i,substr($separ_dias[2],-1));
                    $sheet->setCellValue('P'.$i,substr($separ_dias[3],-1));
                    $sheet->setCellValue('Q'.$i,substr($separ_dias[4],-1));
                    $sheet->setCellValue('R'.$i,substr($separ_dias[5],-1));
                    $sheet->setCellValue('S'.$i,substr($separ_dias[6],-1));
                    $sheet->setCellValue('T'.$i,$cli->Nombre);
                    $sheet->setCellValue('U'.$i,'NA');
                    $sheet->setCellValue('V'.$i,$cli->RefUno);
                    $sheet->setCellValue('W'.$i,$cli->Nombre);
                    $sheet->setCellValue('X'.$i,'NA');
                    $sheet->setCellValue('Y'.$i,'0');
                    $sheet->setCellValue('Z'.$i,'0');
                    
                    $sheet->setCellValueExplicit('AA'.$i,$cli->Latitud,$tiponumero);

                    $sheet->setCellValue('AB'.$i,$cli->Longitud);
                    $sheet->setCellValue('AC'.$i,'0');
                    $sheet->setCellValue('AD'.$i,'0');
                    $sheet->setCellValue('AE'.$i,'0');
                    $sheet->setCellValue('AF'.$i,'0');
                    $sheet->setCellValue('AG'.$i,'0');
                    $sheet->setCellValue('AH'.$i,'NA');
                    $sheet->setCellValue('AI'.$i,$cli->NombreDepartamento);
                    $sheet->setCellValue('AJ'.$i,$cli->NombreMunicipio);
                    $sheet->setCellValue('AK'.$i,'=CONCATENATE(A'.$i.',"|",B'.$i.',"|",C'.$i.',"|",D'.$i.',"|",E'.$i.',"|",F'.$i.',"|",G'.$i.',"|",H'.$i.',"|",I'.$i.',"|",J'.$i.',"|",K'.$i.',"|",L'.$i.',"|",M'.$i.',"|",N'.$i.',"|",O'.$i.',"|",P'.$i.',"|",Q'.$i.',"|",R'.$i.',"|",S'.$i.',"|",T'.$i.',"|",U'.$i.',"|",V'.$i.',"|",W'.$i.',"|",X'.$i.',"|",Y'.$i.',"|",Z'.$i.',"|",AA'.$i.',"|",AB'.$i.',"|",AC'.$i.',"|",AD'.$i.',"|",AE'.$i.',"|",AF'.$i.',"|",AG'.$i.',"|",AH'.$i.',"|",AI'.$i.',"|",AJ'.$i.',"|")');

                    // $sheet->setCellValue('AK'.$i,$cli->Nombre_TpuntoV);
                    // $sheet->setCellValue('AL'.$i,$cli->Nombre_Gnegocio);
                    // $sheet->setCellValue('AM'.$i,$cli->Fecha_Ingreso);
                    // $sheet->setCellValue('AN'.$i,$cli->Fecha_Resolucion);
                    //$sheet->setCellValue('G'.$i,'=C'.$i);
                    $i++;


                    /* DATA - SINCRONIZACION - 02/07/2021 */

                    $IdRuta_SysNew = $cli->Nombre_Ruta;
                    $IdRuta_SysNew = str_replace(".","",$IdRuta_SysNew);
                    $dia_cobro = 0;
                    if($cli->Dia_Cobro == 'LUNES'){
                        $dia_cobro = 1;
                    }elseif($cli->Dia_Cobro == 'MARTES'){
                        $dia_cobro = 2;
                    }elseif($cli->Dia_Cobro == 'MIERCOLES'){
                        $dia_cobro = 3;
                    }elseif($cli->Dia_Cobro == 'JUEVES'){
                        $dia_cobro = 4;
                    }elseif($cli->Dia_Cobro == 'VIERNES'){
                        $dia_cobro = 5;
                    }elseif($cli->Dia_Cobro == 'SABADO'){
                        $dia_cobro = 6;
                    }elseif($cli->Dia_Cobro == 'DOMINGO'){
                        $dia_cobro = 7;
                    }else{
                        $dia_cobro = 0;
                    }
                    $data_Add_SincroCli[] = [
                        'Cli_Id' => 0,
                        'Cli_codigo' => $cli->Codigo,
                        'Cli_nombre' => $cli->Nombre,
                        'Cli_direccion' => $cli->Direccion,
                        'Cli_Mun_Id' => $cli->Mun_Id,
                        'Cli_telefono' => $cli->Telefono,
                        'Cli_contacto' => $cli->Contacto,
                        'Cli_Tfc_Id' => $cli->Tfc_Id,
                        'Cli_dui' => $cli->Dui,
                        'Cli_num_registro' => $cli->Numero_Registro,
                        'Cli_nit' => $cli->Nit,
                        'Cli_Coc_Id' => $cli->Cod_Id,
                        'Cli_dia_cobro' => $dia_cobro,
                        'Cli_monto_credito' => $cli->Monto_Credito,
                        'Cli_l' => substr($separ_dias[0],-1),
                        'Cli_m' => substr($separ_dias[1],-1),
                        'Cli_mi' => substr($separ_dias[2],-1),
                        'Cli_j' => substr($separ_dias[3],-1),
                        'Cli_v' => substr($separ_dias[4],-1),
                        'Cli_s' => substr($separ_dias[5],-1),
                        'Cli_d' => substr($separ_dias[6],-1),
                        'Cli_orden_l' => $orden_separados[0],
                        'Cli_orden_m' => $orden_separados[1],
                        'Cli_orden_mi' => $orden_separados[2],
                        'Cli_orden_j' => $orden_separados[3],
                        'Cli_orden_v' => $orden_separados[4],
                        'Cli_orden_s' => $orden_separados[5],
                        'Cli_orden_d' => $orden_separados[6],
                        'Cli_frecuencia_visita' => $cli->RefUno,
                        'Cli_latitud' => $cli->Latitud,
                        'Cli_longitud' => $cli->Longitud,
                        'Cli_Ru_Id' => $IdRuta_SysNew,
                        'Cli_Gir_Id' => $cli->Gir_Id,
                        'Cli_foto' => $cli->Foto_Negocio,
                        'Cli_estado' => 1,
                        'Cli_estado_sys' => 'P',
                        'Cli_estado_analista' => 'A',
                        'Cli_estado_descarga' => 1,
                        'Cli_editado' => 0,
                        'Cli_comentario' => $cli->Comentario_E,
                        'Cli_tipo_cliente' => $cli->TipoCliente,
                        'Cli_ac_exhibidor' => 0,
                        'Cli_ac_cliente' => 1,
                        'Cli_us_resolucion' => $cli->quienresolucion,
                        'Cli_fecha_ingreso' => $cli->Fecha_Ingreso,
                        'Cli_fecha_r_supervisor' => $cli->Fecha_Resolucion,
                        'Cli_fecha_r_analista' => $fecha_actual_p,
                        'Cli_ul_fecha_ac_cliente' => $cli->Fecha_Ingreso,
                        'Cli_ul_fecha_ac_exhibidor' => $cli->UlFechaActuExh,
                        'Cli_token' => $cli->TokenCliNuevo,
                        'Cli_actu_exh' => 0,
                        'Cli_bloq_exh' => 0,
                        'Cli_tipo_us' => $cli->tipo_us,
                        'Cli_cantidad_CMR' => $cli->Cantidad_CMR
                    ];

                }
                $cod_aleatorio = numero_aleatorio(7);
                $nombre_archivo = '/var/www/html/Uploads/Plantilla_Excel/Clientes_Nuevos/CLIENTES_NUEVOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                // $nombre_archivo = '../Uploads/Plantilla_Excel/Clientes_Nuevos/CLIENTES_NUEVOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';

                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            }


            $C_Registros = 0;
            $C_Registros = count($data_Add_SincroCli);
            $Agregar_Data = $this->cl->Add_SincroClientes($data_Add_SincroCli);
            if($C_Registros == $Agregar_Data){
                // echo "se guardo en otra base";
            }else{
                // echo "no se guardo en otra base";
            }
            /*--------------------------------------------------------------*/
            /*------------PROCESAR CLIENTES POR RANGO DE FECHAS-------------*/
            /*--------------------------------------------------------------*/
            
            $idusuario = '';
            $idusuario = desencriptar_cadena($this->session->userdata('codusuario'));
            $selected_distribuidoras = '';
            $selected_distribuidoras = $this->input->post('distriselect');
            $distriselect = '';
            // if(!empty($arraid_distribuidora)){
            //     $distriselect = $arraid_distribuidora;
            // }else{
            //     $distriselect = 'TODAS LAS DISTRIBUIDORAS';
            // }
            $datag_bitacora = array(
                'Fecha_Inicio' => $fecha_actual_p,
                'Fecha_Final' => $fecha_actual_p,
                'Distribuidoras_B' => $selected_distribuidoras,
                'Id_Usuarios' => $idusuario,
                'Fecha_Descarga' => $fecha_actual_p,
                'Nombre_Archivo' => $nombre_archivo,
                'TipoDescarga' => 'CTLE NUEVOS'
            );

            $modificar_c = array(
                'Fecha_AprobacionA' => $fecha_actual_p
            );
 
            $totalpartes = 0;
            $parte_distri = explode(",", $selected_distribuidoras);
            $guardarbitacora = $this->ls->guardar_bitacora($datag_bitacora);
            if($guardarbitacora){
                $modificardata = $this->cl->m_clientes_procesados($modificar_c,$fechadesde,$fechahasta,$parte_distri,$fecha_actual_p,$rutas);
                if($modificardata){
                    echo json_encode(array(
                        'rs' => TRUE,
                        'info' => '<h5>La Plantilla de Clientes Se Genero Satisfactoriamente.</h5>',
                        'cla' => 'success grSuccess',
                        'archivo' => $nombre_archivo,
                        'fecha' => $fechadesde,
                        'resultcanti' => $resultcanti,
                        'arraydistri' => $arraid_distribuidora
                        )
                    );
                    return;
                }else{
                    $resp = array(
                        'rs' => FALSE,
                        'info' => ' Ocurrio un error en el proceso. MOFIGICAR ESTADO CLIENTE',
                        'cla' => 'success grDanguer',
                        'RESULTERROR' => $modificardata,
                        'DISTRIBUIDORAS' => $parte_distri
                    );
                    echo json_encode($resp);
                    return;
                }
            }else{
                $resp = array(
                    'rs' => FALSE,
                    'info' => ' Ocurrio un error en el proceso GUARDAR BITACORA.',
                    'cla' => 'success grDanguer'
                );
                echo json_encode($resp);
                return;
            }
        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso. ERROR DESCONICIDO',
                'cla' => 'success grDanguer'
            );
            echo json_encode($resp);
            return;
        }
    }


    function plantillaokAC(){
        if($this->input->is_ajax_request()){

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            $tiponumero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
            $tipoletras = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
            $fechadesde = '';
            $fechahasta = '';
            $arrgaupdate = array();
                $clienteprueba = $this->input->post('cp');
                $vista_elegida = $this->input->post('tipovista');
                $distribuidoras = $this->input->post('cbmuldistribuidora');
                if(empty($distribuidoras)){
                    // $distribuidoras = '';
                    $arraid_distribuidora = '';
                }else{
                    $totaldist = 0;
                    $where_distribuidora = "";
                    $arrgdistribuidoras = array();
                    if(!empty($distribuidoras)){
                        $totaldist = count($distribuidoras);
                        if($totaldist>0){
                            for ($i=0; $i < $totaldist ; $i++) {
                                // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                                // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                                $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                            }
                        }else{
                            $arrgdistribuidoras = array();
                        }
                    }else{
                        $totaldist = 0;
                        $arrgdistribuidoras = array();
                    }
                    $arrgdistribuidoras = array_values($arrgdistribuidoras);
                    /*-------------------------------------------------------*/
                    /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                    /*-------------------------------------------------------*/
                    $nombrepais = '';
                    $nombrepais = $this->session->userdata('pais');

                    $l_distinto_distribuidora = array();
                    $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                    $dist = 0;
                    $a = 0;
                    $co = 0;
                    $valor = '';
                    $arreglos_claves_borrar = array();

                    $arraid_distribuidora = array();
                    $lc = 0;
                    foreach ($l_distribuidoras as $dtb){
                        $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                        $lc++;
                    }

                    for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                        $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                        if(!empty($clave)){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{
                            if($clave == 0){
                                $arreglos_claves_borrar[$co] = $clave;
                                $co++;
                            }else{

                            }
                        }                   
                    }

                    $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                    $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                    for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                        unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                    }
                    $arraid_distribuidora = array_values($arraid_distribuidora);

                }
                $rutas = '';
                $rutas = desencriptar_cadena($this->input->post("cbrutas_todas"));
                if($rutas == 7777777){
                    $rutas = '';
                }

                $param_busqueda = array(
                    // 'fechadesde' => $fechadesde,
                    // 'fechahasta' => $fechahasta,
                    // 'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras,
                    'rutas' => $rutas
                );

            $resultcanti = 0;
            $fecha_actual = date('Y_m_d_h_i_s');
            $fecha_actual_excel = date('Y-m-d h:i:s');
            $obtener_clientes = $this->ls->Plantilla_procesadosACPl($param_busqueda);
            // obtener_tabla_procesados
            $resultcanti = count($obtener_clientes);
            $i = 2;

            $arrgaupdate = array();
            $arrgaupdate_sincro = array();
            if(strcmp($this->session->userdata('pais'), 'HONDURAS') == 0 ){
                /*000000000000---ENCABEZADO---0000000000000000000*/
                $sheet->getStyle('A1:AY1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $sheet->getStyle('A1:AY1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
                $sheet->getStyle('A1:AY1')->getFont()->setBold( true );
                //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
                //$sheet->getRowDimension('1')->setRowHeight(100);
                $sheet->getDefaultColumnDimension()->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(55);
                $sheet->getColumnDimension('G')->setWidth(30);
                $sheet->getStyle('A1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('B1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('C1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('D1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('E1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('F1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('G1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('H1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('I1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('J1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('K1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('L1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('M1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('N1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('O1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('P1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Q1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('R1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('S1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('T1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('U1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('V1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('W1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('X1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Y1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('Z1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AA1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AB1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AD1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AE1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AF1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AG1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AH1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AI1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AJ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AK1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AL1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AM1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AN1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AO1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AP1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AQ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AR1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AS1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AT1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AU1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AV1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AW1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AX1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('AY1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                /*CABEZERA DE LA TABLA EXCEL*/
                $sheet->setCellValue('A1','RUTA');
                $sheet->setCellValue('B1','CLIENTE');
                $sheet->setCellValue('C1','NOMBRE');
                $sheet->setCellValue('D1','DIRECCION');
                $sheet->setCellValue('E1','TELEFONO');
                $sheet->setCellValue('F1','CORREO');
                $sheet->setCellValue('G1','CONTACTO');
                $sheet->setCellValue('H1','LATITUD');
                $sheet->setCellValue('I1','LONGITUD');
                $sheet->setCellValue('J1','ESTADO');
                $sheet->setCellValue('K1','LIMITE_CREDITO');
                $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('M1','LUNES');
                $sheet->setCellValue('N1','MARTES');
                $sheet->setCellValue('O1','MIERCOLES');
                $sheet->setCellValue('P1','JUEVES');
                $sheet->setCellValue('Q1','VIERNES');
                $sheet->setCellValue('R1','SABADO');
                $sheet->setCellValue('S1','DOMINGO');
                $sheet->setCellValue('T1','ORDEN_VISITA');
                $sheet->setCellValue('U1','SALDO');
                $sheet->setCellValue('V1','NIT_FISCAL');
                $sheet->setCellValue('W1','RTN');
                $sheet->setCellValue('X1','NOMBRE_FISCAL');
                $sheet->setCellValue('Y1','REF2');
                $sheet->setCellValue('Z1','REF6');
                $sheet->setCellValue('AA1','FRECUENCIA');
                $sheet->setCellValue('AB1','PAIS');
                $sheet->setCellValue('AC1','DEPTO');
                $sheet->setCellValue('AD1','MUNICIPIO');
                $sheet->setCellValue('AE1','TIPO_PUNTO_VENTA');
                $sheet->setCellValue('AF1','GIRO_NEGOCIO');
                $sheet->setCellValue('AG1','TIPO_FACTURACION');
                $sheet->setCellValue('AH1','CONDICION_CLIENTE');
                $sheet->setCellValue('AI1','DIA_COBRO');
                $sheet->setCellValue('AJ1','MONTO_CREDITO');
                $sheet->setCellValue('AK1','CANTIDAD_EXHIBIDORES');
                $sheet->setCellValue('AL1','EXHIBIDOR_UNO');
                $sheet->setCellValue('AM1','EXHIBIDOR_DOS');
                $sheet->setCellValue('AN1','EXHIBIDOR_TRES');
                $sheet->setCellValue('AO1','COMPRA_S_B');
                $sheet->setCellValue('AP1','COMPRA_S_D');
                $sheet->setCellValue('AQ1','COMPRA_S_Y');
                $sheet->setCellValue('AR1','COMPRA_S_F');
                $sheet->setCellValue('AS1','FECHA_INGRESO');
                $sheet->setCellValue('AT1','FECHA_RESOLUCION');
                $sheet->setCellValue('AU1','EDITADO');
                $sheet->setCellValue('AV1','COMENTARIO_E');
                $sheet->setCellValue('AW1','FECHA_RESOLUCION_A');
                $sheet->setCellValue('AX1','FECHA_PROCESADO');
                $sheet->setCellValue('AY1','ID_CLIENTE');
                /*IMPRIMIENDO DATOS EN EXCEL*/
                $separ_dias = '';
                /*0000000000000000000000000000000000000000000000*/
                
                foreach ($obtener_clientes as $cli )
                {
                    $dia_nombre = '';
                    $telefono = '';
                    if($cli->TelefonoAC == '0000-0000'){
                        $telefono = 0;
                    }else{
                        $telefono = $cli->TelefonoAC;
                    }


                    $nom_exhibidoru = '';
                    $nom_exhibidord = '';
                    $nom_exhibidort = '';
                    $valor_exu = '';
                    $valor_exd = '';
                    $valor_ext = '';
                    /*----------------------------------*/
                    /*EVALUAR LA CANTIDAD DE EXHIBIDORES*/
                    /*----------------------------------*/
                    if($cli->Cantidad_Exhibidor == 1){
                        $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                        foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                        $valor_exd = '';
                        $valor_ext = '';
                    }elseif($cli->Cantidad_Exhibidor == 2){
                        $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                        foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                        /*---------------------------------------------------------------------*/
                        $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                        foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->SKU_Exhibidor;}
                        $valor_ext = '';
                    }elseif($cli->Cantidad_Exhibidor == 3){
                        $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                        foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->SKU_Exhibidor;}
                        /*---------------------------------------------------------------------*/
                        $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                        foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->SKU_Exhibidor;}
                        /*---------------------------------------------------------------------*/
                        $nom_exhibidort = $this->cl->exhibidor_select($cli->Exhibiror_Tres);
                        foreach ($nom_exhibidort as $next){$valor_ext = $next->SKU_Exhibidor;}
                    }else{
                        $valor_exu = '';
                        $valor_exd = '';
                        $valor_ext = '';
                    }

                    $sheet->setCellValue('A'.$i,$cli->Nombre_Ruta);
                    $sheet->setCellValue('B'.$i,$cli->CodigoAC);
                    $sheet->setCellValue('C'.$i,$cli->NombreAC);
                    $sheet->setCellValue('D'.$i,$cli->DireccionAC);
                    $sheet->setCellValue('E'.$i,$telefono);
                    $sheet->setCellValue('F'.$i,'NA');
                    $sheet->setCellValue('G'.$i,$cli->ContactoAC);
                    $sheet->setCellValue('H'.$i,$cli->LongitudAC);
                    $sheet->setCellValueExplicit('I'.$i,$cli->LatitudAC,$tiponumero);
                    $sheet->setCellValue('J'.$i,$cli->EstadoAC);
                    $sheet->setCellValue('K'.$i,3000);
                    $sheet->setCellValue('L'.$i,'3');
                    $separ_dias = explode(",", $cli->DiasAC);
                    $sheet->setCellValue('M'.$i,substr($separ_dias[0],-1));
                    $sheet->setCellValue('N'.$i,substr($separ_dias[1],-1));
                    $sheet->setCellValue('O'.$i,substr($separ_dias[2],-1));
                    $sheet->setCellValue('P'.$i,substr($separ_dias[3],-1));
                    $sheet->setCellValue('Q'.$i,substr($separ_dias[4],-1));
                    $sheet->setCellValue('R'.$i,substr($separ_dias[5],-1));
                    $sheet->setCellValue('S'.$i,substr($separ_dias[6],-1));


                    $dias_separados = explode(',',$cli->DiasAC);
                    $orden_separados = explode(',',$cli->Ord_VisitaSema);
                    $OrdeVDinamico = 0;

                    if(count($orden_separados) < 7){
                        $orden_separados[0] = $cli->OrdenVistaAC;
                        $orden_separados[1] = $cli->OrdenVistaAC;
                        $orden_separados[2] = $cli->OrdenVistaAC;
                        $orden_separados[3] = $cli->OrdenVistaAC;
                        $orden_separados[4] = $cli->OrdenVistaAC;
                        $orden_separados[5] = $cli->OrdenVistaAC;
                        $orden_separados[6] = $cli->OrdenVistaAC;
                    }else{

                        $ordenvi = $cli->OrdenVistaAC;
                        if(empty($cli->OrdenVistaAC)){
                            $ordenvi = 1;
                        }

                        if(empty($orden_separados[0]))
                        $orden_separados[0] = $ordenvi;
                        if(empty($orden_separados[1]))
                        $orden_separados[1] = $ordenvi;
                        if(empty($orden_separados[2]))
                        $orden_separados[2] = $ordenvi;
                        if(empty($orden_separados[3]))
                        $orden_separados[3] = $ordenvi;
                        if(empty($orden_separados[4]))
                        $orden_separados[4] = $ordenvi;
                        if(empty($orden_separados[5]))
                        $orden_separados[5] = $ordenvi;
                        if(empty($orden_separados[6]))
                        $orden_separados[6] = $ordenvi;
                    }
                    
                    if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[0];
                    }elseif( strcmp($dias_separados[1], 'M_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[1];
                    }elseif( strcmp($dias_separados[2], 'I_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[2];
                    }elseif( strcmp($dias_separados[3], 'J_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[3];
                    }elseif( strcmp($dias_separados[4], 'V_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[4];
                    }elseif( strcmp($dias_separados[5], 'S_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[5];
                    }elseif( strcmp($dias_separados[6], 'D_1') == 0 ){
                        $OrdeVDinamico =$orden_separados[6];
                    }

                    $sheet->setCellValue('T'.$i,$OrdeVDinamico);
                    $sheet->setCellValue('U'.$i,0);
                    $sheet->setCellValue('V'.$i,$cli->DuiAC);
                    $sheet->setCellValue('W'.$i,$cli->NitAC);
                    $sheet->setCellValue('X'.$i,$cli->NombreAC);
                    $sheet->setCellValue('Y'.$i,'N/A');
                    $sheet->setCellValue('Z'.$i,'0');
                    $sheet->setCellValue('AA'.$i,$cli->FrecuencVisitaAC);
                    $sheet->setCellValue('AB'.$i,'HONDURAS');
                    $sheet->setCellValue('AC'.$i,mb_strtoupper(quitar_acentos($cli->NombreDepartamento)));
                    $sheet->setCellValue('AD'.$i,mb_strtoupper(quitar_acentos($cli->NombreMunicipio)));
                    $sheet->setCellValue('AE'.$i,$cli->Nombre_TpuntoV);
                    $sheet->setCellValue('AF'.$i,$cli->Nombre_Gnegocio);
                    $sheet->setCellValue('AG'.$i,$cli->Nombre_Tfacturacion);
                    $sheet->setCellValue('AH'.$i,$cli->Nombre_Condicionc);
                    $sheet->setCellValue('AI'.$i,$dia_nombre);
                    $sheet->setCellValue('AJ'.$i,$cli->Monto_Credito);
                    $sheet->setCellValue('AK'.$i,$cli->Cantidad_Exhibidor);
                    $sheet->setCellValue('AL'.$i,$valor_exu);
                    $sheet->setCellValue('AM'.$i,$valor_exd);
                    $sheet->setCellValue('AN'.$i,$valor_ext);
                    $sheet->setCellValue('AO'.$i,$cli->CompraS_B);
                    $sheet->setCellValue('AP'.$i,$cli->CompraS_D);
                    $sheet->setCellValue('AQ'.$i,$cli->CompraS_Y);
                    $sheet->setCellValue('AR'.$i,$cli->CompraS_F);
                    $sheet->setCellValue('AS'.$i,$cli->FechaACSer);
                    $sheet->setCellValue('AT'.$i,$cli->FechaASupervisor);
                    $sheet->setCellValue('AU'.$i,$cli->EstadoSICambio);
                    $sheet->setCellValue('AV'.$i,'NA');
                    $sheet->setCellValue('AW'.$i,$cli->FechaAAnalista);
                    $sheet->setCellValue('AX'.$i,$fecha_actual_excel);
                    $sheet->setCellValue('AY'.$i,$cli->Id_Cliente);
                    $i++;


                    $arrgaupdate[] = [
                        'Id_Cliente' => $cli->Id_Cliente,
                        'Nombre' => $cli->NombreAC,
                        'Direccion' => $cli->DireccionAC,
                        'Contacto' => $cli->ContactoAC,
                        'Id_Municipio' => $cli->Id_Municipio,
                        'Telefono' => $cli->TelefonoAC,
                        'Dias' => $cli->DiasAC,
                        'estado_w' => $cli->EstadoAC,
                        'Id_Tfacturacion' => $cli->Id_Tfacturacion,
                        'Dui' => $cli->DuiAC,
                        'Numero_Registro' => $cli->Numero_RegistroAC,
                        'Nit' => $cli->NitAC,
                        'RefUno' => $cli->FrecuencVisitaAC,
                        'Orden_Visita' => $OrdeVDinamico,
                        'Latitud' => $cli->LatitudAC,
                        'Longitud' => $cli->LongitudAC,
                        'UlFechaActuCli' => $fecha_actual,
                        'Ord_VisitaSema' => $cli->Ord_VisitaSema,
                        'Cantidad_CMR' => $cli->Cantidad_CMR,
                        'ActuClientes' => 'SI'
                    ];

                    $arrgaupdate_sincro[] = [
                        'Cli_token' => $cli->TokenCliNuevo,
                        'Cli_nombre' => $cli->NombreAC,
                        'Cli_direccion' => $cli->DireccionAC,
                        'Cli_contacto' => $cli->ContactoAC,
                        'Cli_Mun_Id' => $cli->Id_Municipio,
                        'Cli_telefono' => $cli->TelefonoAC,
                        'Cli_l' => substr($separ_dias[0],-1),
                        'Cli_m' => substr($separ_dias[1],-1),
                        'Cli_mi' => substr($separ_dias[2],-1),
                        'Cli_j' => substr($separ_dias[3],-1),
                        'Cli_v' => substr($separ_dias[4],-1),
                        'Cli_s' => substr($separ_dias[5],-1),
                        'Cli_d' => substr($separ_dias[6],-1),
                        'Cli_estado' => $cli->EstadoAC,
                        'Cli_Tfc_Id' => $cli->Tfc_Id,
                        'Cli_dui' => $cli->DuiAC,
                        'Cli_num_registro' => $cli->Numero_RegistroAC,
                        'Cli_nit' => $cli->NitAC,
                        'Cli_frecuencia_visita' => $cli->FrecuencVisitaAC,
                        'Cli_orden_l' => $orden_separados[0],
                        'Cli_orden_m' => $orden_separados[1],
                        'Cli_orden_mi' => $orden_separados[2],
                        'Cli_orden_j' => $orden_separados[3],
                        'Cli_orden_v' => $orden_separados[4],
                        'Cli_orden_s' => $orden_separados[5],
                        'Cli_orden_d' => $orden_separados[6],
                        'Cli_latitud' => $cli->LatitudAC,
                        'Cli_longitud' => $cli->LongitudAC,
                        'Cli_ul_fecha_ac_cliente' => $fecha_actual,
                        'Cli_cantidad_CMR' => $cli->Cantidad_CMR,
                        'Cli_ac_cliente' => 1
                    ];

                }
                $cod_aleatorio = numero_aleatorio(7);
                $nombre_archivo = '/var/www/html/Uploads/Plantilla_Excel/Actualizacion_Clientes/CLIENTES_ACTUALIZADOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                // $nombre_archivo = '../Uploads/Plantilla_Excel/Actualizacion_Clientes/CLIENTES_ACTUALIZADOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            }else{
                /*000000000000---ENCABEZADO---0000000000000000000*/
                $sheet->getStyle('A1:AK1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                $sheet->getStyle('A1:AK1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
                $sheet->getStyle('A1:AK1')->getFont()->setBold( true );
                //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
                //$sheet->getRowDimension('1')->setRowHeight(100);
                $sheet->getDefaultColumnDimension()->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(55);
                $sheet->getColumnDimension('G')->setWidth(30);

                $sheet->setCellValue('A1','RUTA');
                $sheet->setCellValue('B1','CODIGO');
                $sheet->setCellValue('C1','NOMBRE');
                $sheet->setCellValue('D1','DIRECCION');
                $sheet->setCellValue('E1','TELEFONO');
                $sheet->setCellValue('F1','CORREO');
                $sheet->setCellValue('G1','CONTACTO');
                $sheet->setCellValue('H1','ESTADO');
                $sheet->setCellValue('I1','ORDEN_VISITA');
                $sheet->setCellValue('J1','LIMITE_CREDITO');
                $sheet->setCellValue('K1','SALDO');
                // $sheet->setCellValue('L1','TIPO');
                // $sheet->setCellValue('L1','R');
                // $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('L1','TIPO');
                $sheet->setCellValue('M1','LUNES');
                $sheet->setCellValue('N1','MARTES');
                $sheet->setCellValue('O1','MIERCOLES');
                $sheet->setCellValue('P1','JUEVES');
                $sheet->setCellValue('Q1','VIERNES');
                $sheet->setCellValue('R1','SABADO');
                $sheet->setCellValue('S1','DOMINGO');
                $sheet->setCellValue('T1','NOMBRE_FISCAL');
                $sheet->setCellValue('U1','NIT_FISCAL');
                $sheet->setCellValue('V1','REF1');
                $sheet->setCellValue('W1','REF2');
                $sheet->setCellValue('X1','REF3');
                $sheet->setCellValue('Y1','REF4');
                $sheet->setCellValue('Z1','REF5');
                $sheet->setCellValue('AA1','LATITUD');
                $sheet->setCellValue('AB1','LONGITUD');
                $sheet->setCellValue('AC1','REF6');
                $sheet->setCellValue('AD1','REF7');
                $sheet->setCellValue('AE1','REF8');
                $sheet->setCellValue('AF1','REF9');
                $sheet->setCellValue('AG1','REF10');
                $sheet->setCellValue('AH1','REF11');
                $sheet->setCellValue('AI1','DEPARTAMENTO');
                $sheet->setCellValue('AJ1','MUNICIPIO');
                $sheet->setCellValue('AK1','CONCATENADO');
                $separ_dias = '';
                /*0000000000000000000000000000000000000000000000*/
                foreach ($obtener_clientes as $cli )
                {
                    $telefono = '';
                    $sheet->setCellValue('A'.$i,$cli->Nombre_Ruta);
                    $sheet->setCellValue('B'.$i,$cli->CodigoAC);
                    $sheet->setCellValue('C'.$i,$cli->NombreAC);
                    $sheet->setCellValue('D'.$i,$cli->DireccionAC);
                    if($cli->TelefonoAC == '0000-0000'){
                        $telefono = 0;
                    }else{
                        $telefono = $cli->TelefonoAC;
                    }
                    $sheet->setCellValue('E'.$i,$telefono);
                    $sheet->setCellValue('F'.$i,'NA');
                    $sheet->setCellValue('G'.$i,$cli->ContactoAC);
                    $sheet->setCellValue('H'.$i,$cli->EstadoAC);

                    // $orden_dinamico = 0;
                    // if(strcmp($cli->DiasAC, '1') == 0 )
                    $dias_separados = explode(',',$cli->DiasAC);
                    $orden_separados = explode(',',$cli->Ord_VisitaSema);
                    $OrdeVDinamico = 0;

                    if(count($orden_separados) < 7){
                        $orden_separados[0] = $cli->OrdenVistaAC;
                        $orden_separados[1] = $cli->OrdenVistaAC;
                        $orden_separados[2] = $cli->OrdenVistaAC;
                        $orden_separados[3] = $cli->OrdenVistaAC;
                        $orden_separados[4] = $cli->OrdenVistaAC;
                        $orden_separados[5] = $cli->OrdenVistaAC;
                        $orden_separados[6] = $cli->OrdenVistaAC;
                    }else{

                        $ordenvi = $cli->OrdenVistaAC;
                        if(empty($cli->OrdenVistaAC)){
                            $ordenvi = 1;
                        }

                        if(empty($orden_separados[0]))
                        $orden_separados[0] = $ordenvi;
                        if(empty($orden_separados[1]))
                        $orden_separados[1] = $ordenvi;
                        if(empty($orden_separados[2]))
                        $orden_separados[2] = $ordenvi;
                        if(empty($orden_separados[3]))
                        $orden_separados[3] = $ordenvi;
                        if(empty($orden_separados[4]))
                        $orden_separados[4] = $ordenvi;
                        if(empty($orden_separados[5]))
                        $orden_separados[5] = $ordenvi;
                        if(empty($orden_separados[6]))
                        $orden_separados[6] = $ordenvi;
                    }

                    if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[0];
                    }elseif( strcmp($dias_separados[1], 'M_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[1];
                    }elseif( strcmp($dias_separados[2], 'I_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[2];
                    }elseif( strcmp($dias_separados[3], 'J_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[3];
                    }elseif( strcmp($dias_separados[4], 'V_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[4];
                    }elseif( strcmp($dias_separados[5], 'S_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[5];
                    }elseif( strcmp($dias_separados[6], 'D_1') == 0 ){
                        $OrdeVDinamico =$orden_separados[6];
                    }


                    $sheet->setCellValue('I'.$i,$OrdeVDinamico);
                    $sheet->setCellValue('J'.$i,'100');
                    $sheet->setCellValue('K'.$i,'0');
                    $sheet->setCellValue('L'.$i,'1');
                    $separ_dias = explode(",", $cli->DiasAC);
                    $sheet->setCellValue('M'.$i,substr($separ_dias[0],-1));
                    $sheet->setCellValue('N'.$i,substr($separ_dias[1],-1));
                    $sheet->setCellValue('O'.$i,substr($separ_dias[2],-1));
                    $sheet->setCellValue('P'.$i,substr($separ_dias[3],-1));
                    $sheet->setCellValue('Q'.$i,substr($separ_dias[4],-1));
                    $sheet->setCellValue('R'.$i,substr($separ_dias[5],-1));
                    $sheet->setCellValue('S'.$i,substr($separ_dias[6],-1));
                    $sheet->setCellValue('T'.$i,$cli->NombreAC);
                    $sheet->setCellValue('U'.$i,'NA');
                    $sheet->setCellValue('V'.$i,$cli->FrecuencVisitaAC);
                    $sheet->setCellValue('W'.$i,$cli->NombreAC);
                    $sheet->setCellValue('X'.$i,'NA');
                    $sheet->setCellValue('Y'.$i,'0');
                    $sheet->setCellValue('Z'.$i,'0');
                    
                    $sheet->setCellValueExplicit('AA'.$i,$cli->LatitudAC,$tiponumero);

                    $sheet->setCellValue('AB'.$i,$cli->LongitudAC);
                    $sheet->setCellValue('AC'.$i,'0');
                    if(strcmp($this->session->userdata('pais'), 'EL SALVADOR') == 0 ){
                        $sheet->setCellValue('AD'.$i,'0');
                    }else{
                        $sheet->setCellValue('AD'.$i,'50');
                    }
                    $sheet->setCellValue('AE'.$i,'0');
                    $sheet->setCellValue('AF'.$i,'0');
                    $sheet->setCellValue('AG'.$i,'0');
                    $sheet->setCellValue('AH'.$i,'NA');
                    $sheet->setCellValue('AI'.$i,$cli->NombreDepartamento);
                    $sheet->setCellValue('AJ'.$i,$cli->NombreMunicipio);
                    $sheet->setCellValue('AK'.$i,'=CONCATENATE(A'.$i.',"|",B'.$i.',"|",C'.$i.',"|",D'.$i.',"|",E'.$i.',"|",F'.$i.',"|",G'.$i.',"|",H'.$i.',"|",I'.$i.',"|",J'.$i.',"|",K'.$i.',"|",L'.$i.',"|",M'.$i.',"|",N'.$i.',"|",O'.$i.',"|",P'.$i.',"|",Q'.$i.',"|",R'.$i.',"|",S'.$i.',"|",T'.$i.',"|",U'.$i.',"|",V'.$i.',"|",W'.$i.',"|",X'.$i.',"|",Y'.$i.',"|",Z'.$i.',"|",AA'.$i.',"|",AB'.$i.',"|",AC'.$i.',"|",AD'.$i.',"|",AE'.$i.',"|",AF'.$i.',"|",AG'.$i.',"|",AH'.$i.',"|",AI'.$i.',"|",AJ'.$i.',"|")');
                    $sheet->setCellValue('AL'.$i,$cli->NombreMunicipio);

                    $arrgaupdate[] = [
                        'Id_Cliente' => $cli->Id_Cliente,
                        'Nombre' => $cli->NombreAC,
                        'Direccion' => $cli->DireccionAC,
                        'Contacto' => $cli->ContactoAC,
                        'Id_Municipio' => $cli->Id_Municipio,
                        'Telefono' => $cli->TelefonoAC,
                        'Dias' => $cli->DiasAC,
                        'estado_w' => $cli->EstadoAC,
                        'Id_Tfacturacion' => $cli->Id_Tfacturacion,
                        'Dui' => $cli->DuiAC,
                        'Numero_Registro' => $cli->Numero_RegistroAC,
                        'Nit' => $cli->NitAC,
                        'RefUno' => $cli->FrecuencVisitaAC,
                        'Orden_Visita' => $OrdeVDinamico,
                        'Latitud' => $cli->LatitudAC,
                        'Longitud' => $cli->LongitudAC,
                        'UlFechaActuCli' => $fecha_actual,
                        'Ord_VisitaSema' => $cli->Ord_VisitaSema,
                        'Cantidad_CMR' => $cli->Cantidad_CMR,
                        'ActuClientes' => 'SI'
                    ];

                    $arrgaupdate_sincro[] = [
                        'Cli_token' => $cli->TokenCliNuevo,
                        'Cli_nombre' => $cli->NombreAC,
                        'Cli_direccion' => $cli->DireccionAC,
                        'Cli_contacto' => $cli->ContactoAC,
                        'Cli_Mun_Id' => $cli->Id_Municipio,
                        'Cli_telefono' => $cli->TelefonoAC,
                        'Cli_l' => substr($separ_dias[0],-1),
                        'Cli_m' => substr($separ_dias[1],-1),
                        'Cli_mi' => substr($separ_dias[2],-1),
                        'Cli_j' => substr($separ_dias[3],-1),
                        'Cli_v' => substr($separ_dias[4],-1),
                        'Cli_s' => substr($separ_dias[5],-1),
                        'Cli_d' => substr($separ_dias[6],-1),
                        'Cli_estado' => $cli->EstadoAC,
                        'Cli_Tfc_Id' => $cli->Tfc_Id,
                        'Cli_dui' => $cli->DuiAC,
                        'Cli_num_registro' => $cli->Numero_RegistroAC,
                        'Cli_nit' => $cli->NitAC,
                        'Cli_frecuencia_visita' => $cli->FrecuencVisitaAC,
                        'Cli_orden_l' => $orden_separados[0],
                        'Cli_orden_m' => $orden_separados[1],
                        'Cli_orden_mi' => $orden_separados[2],
                        'Cli_orden_j' => $orden_separados[3],
                        'Cli_orden_v' => $orden_separados[4],
                        'Cli_orden_s' => $orden_separados[5],
                        'Cli_orden_d' => $orden_separados[6],
                        'Cli_latitud' => $cli->LatitudAC,
                        'Cli_longitud' => $cli->LongitudAC,
                        'Cli_ul_fecha_ac_cliente' => $fecha_actual,
                        'Cli_cantidad_CMR' => $cli->Cantidad_CMR,
                        'Cli_ac_cliente' => 1
                    ];

                    $i++;
                }
                $cod_aleatorio = numero_aleatorio(7);
                $nombre_archivo = '/var/www/html/Uploads/Plantilla_Excel/Actualizacion_Clientes/CLIENTES_ACTUALIZADOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                // $nombre_archivo = '../Uploads/Plantilla_Excel/Actualizacion_Clientes/CLIENTES_ACTUALIZADOS_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save($nombre_archivo);
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            }



            /*--------------------------------------------------------------*/
            /*------------PROCESAR CLIENTES POR RANGO DE FECHAS-------------*/
            /*--------------------------------------------------------------*/
            $fecha_actual_p = date('Y-m-d H:i:s');
            $idusuario = '';
            $idusuario = desencriptar_cadena($this->session->userdata('codusuario'));
            $selected_distribuidoras = '';
            $selected_distribuidoras = $this->input->post('distriselect');
            $distriselect = '';
            // if(!empty($arraid_distribuidora)){
            //     $distriselect = $arraid_distribuidora;
            // }else{
            //     $distriselect = 'TODAS LAS DISTRIBUIDORAS';
            // }
            $this->cl->modificar_info_clientes_SOLO($arrgaupdate);
            $datag_bitacora = array(
                'Fecha_Inicio' => $fecha_actual_p,
                'Fecha_Final' => $fecha_actual_p,
                'Distribuidoras_B' => $selected_distribuidoras,
                'Id_Usuarios' => $idusuario,
                'Fecha_Descarga' => $fecha_actual_p,
                'Nombre_Archivo' => $nombre_archivo,
                'TipoDescarga' => 'CTLE ACTUAL'
            );

            $modificar_c = array(
                'Fecha_AprobacionA' => $fecha_actual_p
            );
 
            $totalpartes = 0;
            $parte_distri = explode(",", $selected_distribuidoras);
            $guardarbitacora = $this->ls->guardar_bitacora($datag_bitacora);
            if($guardarbitacora){
                $this->cl->Update_SicroCodClientes($arrgaupdate_sincro);
                $modificardata = $this->cl->m_clientes_procesadosAC($modificar_c,$fechadesde,$fechahasta,$parte_distri,$fecha_actual_p,$rutas);
                if($modificardata){
                    echo json_encode(array(
                        'rs' => TRUE,
                        'info' => '<h5>La Plantilla de Clientes Se Genero Satisfactoriamente.</h5>',
                        'cla' => 'success grSuccess',
                        'archivo' => $nombre_archivo,
                        'fecha' => $fechadesde,
                        'resultcanti' => $resultcanti,
                        'arraydistri' => $arraid_distribuidora
                        )
                    );
                    return;
                }else{
                    $resp = array(
                        'rs' => FALSE,
                        'info' => ' Ocurrio un error en el proceso. MOFIGICAR ESTADO CLIENTE',
                        'cla' => 'success grDanguer',
                        'RESULTERROR' => $modificardata,
                        'DISTRIBUIDORAS' => $parte_distri
                    );
                    echo json_encode($resp);
                    return;
                }
            }else{
                $resp = array(
                    'rs' => FALSE,
                    'info' => ' Ocurrio un error en el proceso GUARDAR BITACORA.',
                    'cla' => 'success grDanguer'
                );
                echo json_encode($resp);
                return;
            }
        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso. ERROR DESCONICIDO',
                'cla' => 'success grDanguer'
            );
            echo json_encode($resp);
            return;
        }
    }

    function plantillaok_completa(){
        if($this->input->is_ajax_request()){
            $csrftokename = $this->security->get_csrf_token_name();
            $csrfhash = $this->security->get_csrf_hash();
            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            $tiponumero = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
            $tipoletras = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
            $fechadesde = '';
            $fechahasta = '';

            $fechadesde = $this->input->post('datepickervalue');
            $fechahasta = $this->input->post('datepickerdosvalue');

            $fechadesde = str_replace("am","",$fechadesde);
            $fechadesde = str_replace("pm","",$fechadesde);

            $fechahasta = str_replace("am","",$fechahasta);
            $fechahasta = str_replace("pm","",$fechahasta);

            $clienteprueba = $this->input->post('cp');

                /*---------------------------------------------------------------------*/
                /*------DISTRIBUIDORAS SELECCIONADAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*---------------------------------------------------------------------*/
                $distribuidoras = $this->input->post('cbmuldistribuidorare');
                $totaldist = 0;
                $where_distribuidora = "";
                $arrgdistribuidoras = array();
                if(!empty($distribuidoras)){
                    $totaldist = count($distribuidoras);
                    if($totaldist>0){
                        for ($i=0; $i < $totaldist ; $i++) {
                            // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                            // $this->db->where('ru.Id_Distribuidora',$distribuidoras[$i]);
                            $arrgdistribuidoras[$i] =  desencriptar_cadena($distribuidoras[$i]);
                        }
                    }else{
                        $arrgdistribuidoras = array();
                    }
                }else{
                    $totaldist = 0;
                    $arrgdistribuidoras = array();
                }
                $arrgdistribuidoras = array_values($arrgdistribuidoras);
                /*-------------------------------------------------------*/
                /*------DISTRIBUIDORAS DEL PAIS DEL USUARIO LOGUEADO-----*/
                /*-------------------------------------------------------*/
                $nombrepais = '';
                $nombrepais = $this->session->userdata('pais');
                $l_distinto_distribuidora = array();

                $l_distribuidoras = $this->ls->list_distribuidora($nombrepais);
                $dist = 0;
                $a = 0;
                $co = 0;
                $valor = '';
                $arreglos_claves_borrar = array();

                $arraid_distribuidora = array();
                $lc = 0;
                foreach ($l_distribuidoras as $dtb){
                    $arraid_distribuidora[$lc] = $dtb->Id_Distribuidora;
                    $lc++;
                }

                for ($k=0; $k <count($arrgdistribuidoras); $k++) {
                    $clave = array_search($arrgdistribuidoras[$k], $arraid_distribuidora);
                    if(!empty($clave)){
                        $arreglos_claves_borrar[$co] = $clave;
                        $co++;
                    }else{
                        if($clave == 0){
                            $arreglos_claves_borrar[$co] = $clave;
                            $co++;
                        }else{

                        }
                    }                   
                }

                $arreglos_claves_borrar = array_filter($arreglos_claves_borrar, "is_numeric");
                $arreglos_claves_borrar = array_values($arreglos_claves_borrar);

                for ($o=0; $o < count($arreglos_claves_borrar); $o++) { 
                    unset($arraid_distribuidora[$arreglos_claves_borrar[$o]]);
                }
                $arraid_distribuidora = array_values($arraid_distribuidora);

                $param_busqueda = array(
                    'fechadesde' => $fechadesde,
                    'fechahasta' => $fechahasta,
                    'cp' => $clienteprueba,
                    'distribuidoras' => $arrgdistribuidoras
                );
            $fecha_actual = date('Y_m_d_h_i_s');
            $obtener_clientes = $this->ls->obtener_listado_completo($param_busqueda);
            $i = 2;
            /*000000000000---ENCABEZADO---0000000000000000000*/
            $sheet->getStyle('A1:BM1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            $sheet->getStyle('A1:BM1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
            $sheet->getStyle('A1:BM1')->getFont()->setBold( true );
            //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
            $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
            //$sheet->getRowDimension('1')->setRowHeight(100);
            $sheet->getDefaultColumnDimension()->setWidth(13);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(55);
            $sheet->getColumnDimension('G')->setWidth(30);
            $sheet->getStyle('A1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('B1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('C1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('D1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('E1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('F1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('G1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('H1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('I1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('J1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('K1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('L1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('M1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('N1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('O1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('P1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('Q1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('R1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('S1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('T1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('U1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('V1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('W1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('X1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('Y1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('Z1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AA1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AB1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AD1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AE1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AF1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AG1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AH1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AI1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AJ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('AK1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AL1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AM1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AN1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AO1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AP1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AQ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AR1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AS1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AT1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AU1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AV1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AW1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AX1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AY1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('AZ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('BA1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('BB1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('BC1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('BD1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('BE1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('BF1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('BG1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('BH1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('BI1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('BJ1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('BK1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('BL1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('BM1')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            //$sheet->getDefaultStyle()->getFont()->setName('Times New Roman');
            $sheet->setCellValue('A1','ID_RUTA');
            $sheet->setCellValue('B1','CODIGO');
            $sheet->setCellValue('C1','NOMBRE');
            $sheet->setCellValue('D1','DIRECCION');
            $sheet->setCellValue('E1','TELEFONO');
            $sheet->setCellValue('F1','CORREO');
            $sheet->setCellValue('G1','CONTACTO');
            $sheet->setCellValue('H1','ESTADO');
            $sheet->setCellValue('I1','ORDEN_VISITA');
            $sheet->setCellValue('J1','LIMITE_CREDITO');
            $sheet->setCellValue('K1','SALDO');
            $sheet->setCellValue('L1','TIPO');
            $sheet->setCellValue('M1','LUNES');
            $sheet->setCellValue('N1','MARTES');
            $sheet->setCellValue('O1','MIERCOLES');
            $sheet->setCellValue('P1','JUEVES');
            $sheet->setCellValue('Q1','VIERNES');
            $sheet->setCellValue('R1','SABADO');
            $sheet->setCellValue('S1','DOMINGO');
            $sheet->setCellValue('T1','NOMBRE_FISCAL');
            $sheet->setCellValue('U1','NIT_FISCAL');
            $sheet->setCellValue('V1','REF1');
            $sheet->setCellValue('W1','REF2');
            $sheet->setCellValue('X1','REF3');
            $sheet->setCellValue('Y1','REF4');
            $sheet->setCellValue('Z1','REF5');
            $sheet->setCellValue('AA1','LATITUD');
            $sheet->setCellValue('AB1','LONGITUD');
            $sheet->setCellValue('AC1','REF6');
            $sheet->setCellValue('AD1','REF7');
            $sheet->setCellValue('AE1','REF8');
            $sheet->setCellValue('AF1','REF9');
            $sheet->setCellValue('AG1','REF10');
            $sheet->setCellValue('AH1','NCR');
            $sheet->setCellValue('AI1','DEPARTAMENTO');
            $sheet->setCellValue('AJ1','MUNICIPIO');
            $sheet->setCellValue('AK1','TIPO_PUNTO_VENTA');
            $sheet->setCellValue('AL1','GIRO_NEGOCIO');
            $sheet->setCellValue('AM1','TIPO_FACTURACION');
            $sheet->setCellValue('AN1','DUI');
            $sheet->setCellValue('AO1','NUMERO_REGISTRO');
            $sheet->setCellValue('AP1','NIT');
            $sheet->setCellValue('AQ1','PROPIETARIO');
            $sheet->setCellValue('AR1','CONDICION_CLIENTE');
            $sheet->setCellValue('AS1','DIA_COBRO');
            $sheet->setCellValue('AT1','MONTO_CREDITO');
            $sheet->setCellValue('AU1','CANTIDAD_EXHIBIDORES');
            $sheet->setCellValue('AV1','EXHIBIDOR_UNO');
            $sheet->setCellValue('AW1','EXHIBIDOR_DOS');
            $sheet->setCellValue('AX1','EXHIBIDOR_TRES');
            $sheet->setCellValue('AY1','COMPRA_S_B');
            $sheet->setCellValue('AZ1','COMPRA_S_D');
            $sheet->setCellValue('BA1','COMPRA_S_Y');
            $sheet->setCellValue('BB1','COMPRA_S_F');
            $sheet->setCellValue('BC1','FECHA_REGISTRO');
            $sheet->setCellValue('BD1','FECHA_RESOLUCION');
            $sheet->setCellValue('BE1','ESTADO_SUPERVISOR');
            $sheet->setCellValue('BF1','ID');
            $sheet->setCellValue('BG1','EDITADO');
            $sheet->setCellValue('BH1','COMENTARIO_E');
            $sheet->setCellValue('BI1','ESTADO_ANALISTA');
            $sheet->setCellValue('BJ1','FECHA_RESOLUCION_A');
            $sheet->setCellValue('BK1','FECHA_PROCESADO');
            $sheet->setCellValue('BL1','USUARIO');
            $sheet->setCellValue('BM1','ESTADO_DESCARGA');
            // $sheet->setCellValue('AK1','FECHA_INGRESO');
            // $sheet->setCellValue('AL1','ESTADO');

            $separ_dias = '';
            /*0000000000000000000000000000000000000000000000*/
            foreach ($obtener_clientes as $cli )
            {

                    $dias_separados = explode(',',$cli->Dias);
                    $orden_separados = explode(',',$cli->Ord_VisitaSema);
                    $OrdeVDinamico = 0;

                    if(count($orden_separados) < 7){
                        $orden_separados[0] = $cli->Orden_Visita;
                        $orden_separados[1] = $cli->Orden_Visita;
                        $orden_separados[2] = $cli->Orden_Visita;
                        $orden_separados[3] = $cli->Orden_Visita;
                        $orden_separados[4] = $cli->Orden_Visita;
                        $orden_separados[5] = $cli->Orden_Visita;
                        $orden_separados[6] = $cli->Orden_Visita;
                    }else{

                        $ordenvi = $cli->Orden_Visita;
                        if(empty($cli->Orden_Visita)){
                            $ordenvi = 1;
                        }

                        if(empty($orden_separados[0]))
                        $orden_separados[0] = $ordenvi;
                        if(empty($orden_separados[1]))
                        $orden_separados[1] = $ordenvi;
                        if(empty($orden_separados[2]))
                        $orden_separados[2] = $ordenvi;
                        if(empty($orden_separados[3]))
                        $orden_separados[3] = $ordenvi;
                        if(empty($orden_separados[4]))
                        $orden_separados[4] = $ordenvi;
                        if(empty($orden_separados[5]))
                        $orden_separados[5] = $ordenvi;
                        if(empty($orden_separados[6]))
                        $orden_separados[6] = $ordenvi;
                    }

                    if ( strcmp($dias_separados[0], 'L_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[0];
                    }elseif( strcmp($dias_separados[1], 'M_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[1];
                    }elseif( strcmp($dias_separados[2], 'I_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[2];
                    }elseif( strcmp($dias_separados[3], 'J_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[3];
                    }elseif( strcmp($dias_separados[4], 'V_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[4];
                    }elseif( strcmp($dias_separados[5], 'S_1') == 0 ){
                        $OrdeVDinamico = $orden_separados[5];
                    }elseif( strcmp($dias_separados[6], 'D_1') == 0 ){
                        $OrdeVDinamico =$orden_separados[6];
                    }


                $telefono = '';
                $sheet->setCellValue('A'.$i,$cli->Nombre_Ruta);
                $sheet->setCellValue('B'.$i,$cli->Codigo);
                $sheet->setCellValue('C'.$i,$cli->Nombre);
                $sheet->setCellValue('D'.$i,$cli->Direccion);
                if($cli->Telefono == '0000-0000'){
                    $telefono = 0;
                }else{
                    $telefono = $cli->Telefono;
                }
                $sheet->setCellValue('E'.$i,$telefono);
                $sheet->setCellValue('F'.$i,'NA');
                $sheet->setCellValue('G'.$i,$cli->Contacto);
                $sheet->setCellValue('H'.$i,'1');
                $sheet->setCellValue('I'.$i,$OrdeVDinamico);
                $sheet->setCellValue('J'.$i,'100');
                $sheet->setCellValue('K'.$i,'0');
                $sheet->setCellValue('L'.$i,'1');
                $separ_dias = explode(",", $cli->Dias);

                // if($separ_dias == 7){

                // }else{

                // }


                $sheet->setCellValue('M'.$i,substr($separ_dias[0],-1));
                $sheet->setCellValue('N'.$i,substr($separ_dias[1],-1));
                $sheet->setCellValue('O'.$i,substr($separ_dias[2],-1));
                $sheet->setCellValue('P'.$i,substr($separ_dias[3],-1));
                $sheet->setCellValue('Q'.$i,substr($separ_dias[4],-1));
                $sheet->setCellValue('R'.$i,substr($separ_dias[5],-1));
                $sheet->setCellValue('S'.$i,'0');
                // $sheet->setCellValue('S'.$i,substr($separ_dias[6],-1));
                $sheet->setCellValue('T'.$i,$cli->Nombre);
                $sheet->setCellValue('U'.$i,'NA');
                $sheet->setCellValue('V'.$i,$cli->RefUno);
                $sheet->setCellValue('W'.$i,$cli->Nombre);
                $sheet->setCellValue('X'.$i,'NA');
                $sheet->setCellValue('Y'.$i,'0');
                $sheet->setCellValue('Z'.$i,'0');
                $sheet->setCellValueExplicit('AA'.$i,$cli->Latitud,$tiponumero);


                $sheet->setCellValue('AB'.$i,$cli->Longitud);
                $sheet->setCellValue('AC'.$i,'0');
                $sheet->setCellValue('AD'.$i,'50');
                $sheet->setCellValue('AE'.$i,'0');
                $sheet->setCellValue('AF'.$i,'0');
                $sheet->setCellValue('AG'.$i,'0');
                $sheet->setCellValue('AH'.$i,'NA');
                $sheet->setCellValue('AI'.$i,$cli->NombreDepartamento);
                $sheet->setCellValue('AJ'.$i,$cli->NombreMunicipio);
                $sheet->setCellValue('AK'.$i,$cli->Nombre_TpuntoV);
                $sheet->setCellValue('AL'.$i,$cli->Nombre_Gnegocio);
                $sheet->setCellValue('AM'.$i,$cli->Nombre_Tfacturacion);
                $sheet->setCellValue('AN'.$i,$cli->Dui);
                $sheet->setCellValue('AO'.$i,$cli->Numero_Registro);
                $sheet->setCellValue('AP'.$i,$cli->Nit);
                $sheet->setCellValue('AQ'.$i,$cli->Propietario);
                $sheet->setCellValue('AR'.$i,$cli->Nombre_Condicionc);

                $dia_nombre = '';
                if($cli->Dia_Cobro == 1){
                    $dia_nombre = 'LUNES';
                }elseif($cli->Dia_Cobro == 2){
                    $dia_nombre = 'MARTES';
                }elseif($cli->Dia_Cobro == 3){
                    $dia_nombre = 'MIERCOLES';
                }elseif($cli->Dia_Cobro == 4){
                    $dia_nombre = 'JUEVES';
                }elseif($cli->Dia_Cobro == 5){
                    $dia_nombre = 'VIERNES';
                }elseif($cli->Dia_Cobro == 6){
                    $dia_nombre = 'SABADO';
                }elseif($cli->Dia_Cobro == 7){
                    $dia_nombre = 'DOMINGO';
                }else{
                    $dia_nombre = 'NA';
                }

                $sheet->setCellValue('AS'.$i,$dia_nombre);
                $sheet->setCellValue('AT'.$i,$cli->Monto_Credito);
                $sheet->setCellValue('AU'.$i,$cli->Cantidad_Exhibidor);

                $nom_exhibidoru = '';
                $nom_exhibidord = '';
                $nom_exhibidort = '';
                $valor_exu = '';
                $valor_exd = '';
                $valor_ext = '';
                /*----------------------------------*/
                /*EVALUAR LA CANTIDAD DE EXHIBIDORES*/
                /*----------------------------------*/
                if($cli->Cantidad_Exhibidor == 1){
                    $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                    foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->NombreExhibidor;}
                    $valor_exd = '';
                    $valor_ext = '';
                }elseif($cli->Cantidad_Exhibidor == 2){
                    $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                    foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->NombreExhibidor;}
                    /*---------------------------------------------------------------------*/
                    $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                    foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->NombreExhibidor;}
                    $valor_ext = '';
                }elseif($cli->Cantidad_Exhibidor == 3){
                    $nom_exhibidoru = $this->cl->exhibidor_select($cli->Exhibiror_Uno);
                    foreach ($nom_exhibidoru as $nexu){$valor_exu = $nexu->NombreExhibidor;}
                    /*---------------------------------------------------------------------*/
                    $nom_exhibidord = $this->cl->exhibidor_select($cli->Exhibiror_Dos);
                    foreach ($nom_exhibidord as $nexd){$valor_exd = $nexd->NombreExhibidor;}
                    /*---------------------------------------------------------------------*/
                    $nom_exhibidort = $this->cl->exhibidor_select($cli->Exhibiror_Tres);
                    foreach ($nom_exhibidort as $next){$valor_ext = $next->NombreExhibidor;}
                }else{
                    $valor_exu = '';
                    $valor_exd = '';
                    $valor_ext = '';
                }

                $sheet->setCellValue('AV'.$i,$valor_exu);
                $sheet->setCellValue('AW'.$i,$valor_exd);
                $sheet->setCellValue('AX'.$i,$valor_ext);
                $sheet->setCellValue('AY'.$i,$cli->CompraS_B);
                $sheet->setCellValue('AZ'.$i,$cli->CompraS_D);
                $sheet->setCellValue('BA'.$i,$cli->CompraS_Y);
                $sheet->setCellValue('BB'.$i,$cli->CompraS_F);
                $sheet->setCellValue('BC'.$i,$cli->Fecha_Ingreso);
                $sheet->setCellValue('BD'.$i,$cli->Fecha_Resolucion);

                $Estado_Nombre = '';
                if($cli->Estado == "A"){
                    $Estado_Nombre = 'APROBADO';
                }elseif($cli->Estado == "N"){
                    $Estado_Nombre = 'NUEVO';
                }elseif($cli->Estado == "R"){
                    $Estado_Nombre = 'RECHAZADO';
                }elseif($cli->Estado == "W"){
                    $Estado_Nombre = 'WEBAPP';
                }elseif($cli->Estado == 'P'){
                    $Estado_Nombre = 'APROBADO';
                }

                $Estado_Nombre_A = '';
                if($cli->Estado_Analista == "A"){
                    $Estado_Nombre_A = 'APROBADO';
                }elseif($cli->Estado_Analista == "N"){
                    $Estado_Nombre_A = 'NUEVO';
                }elseif($cli->Estado_Analista == "R"){
                    $Estado_Nombre_A = 'RECHAZADO';
                }elseif($cli->Estado_Analista == "W"){
                    $Estado_Nombre_A = 'WEBAPP';
                }elseif($cli->Estado_Analista == 'P'){
                    $Estado_Nombre_A = 'APROBADO';
                }

                $sheet->setCellValue('BE'.$i,$Estado_Nombre);
                $sheet->setCellValue('BF'.$i,$cli->Id_Cliente);
                $sheet->setCellValue('BG'.$i,$cli->Editado);
                $sheet->setCellValue('BH'.$i,$cli->Comentario_E);
                $sheet->setCellValue('BI'.$i,$Estado_Nombre_A);
                $sheet->setCellValue('BJ'.$i,$cli->Fecha_Resolucion_R);
                $sheet->setCellValue('BK'.$i,$cli->Fecha_AprobacionA);
                $sheet->setCellValue('BL'.$i,$cli->quienresolucion);
                $sheet->setCellValue('BM'.$i,$cli->EstadoDescarga);
                //$sheet->setCellValue('G'.$i,'=C'.$i);
                $i++;
            }
            $cod_aleatorio = numero_aleatorio(7);
            $nombre_archivo = '/var/www/html/Uploads/Plantilla_Excel/Reporte_Clientes/Clientes_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($nombre_archivo);
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            echo json_encode(array(
                'rs' => TRUE,
                'info' => '<h5>El Reporte de Clientes Completo Se Genero Satisfactoriamente.</h5>',
                'cla' => 'success grSuccess',
                'archivo' => $nombre_archivo,
                'csrftokename' => $csrftokename,
                'csrfhash' => $csrfhash,
                'fecha' => $fechadesde,
                'distribuidoras' => $arrgdistribuidoras
                )
            );
            return;

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => ' Ocurrio un error en el proceso.',
                'cla' => 'success grDanguer',
                'csrftokename' => $csrftokename,
                'csrfhash' => $csrfhash,
                'fecha' => $fechadesde
            );
            echo json_encode($resp);
            return;
        }
    }
    function pagination_actu($limit,$adjacents,$t,$page){
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
                $prev_.= "<a class='page-numberactu' href=\"?page=$prev\">Anterior</a>";
            else{ }
            
            if ($lastpage < 5 + ($adjacents * 2))
            {   
            $first='';
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-numberactu' href=\"?page=$counter\">$counter</a>";                   
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
                            $pagination.= "<a class='page-numberactu' href=\"?page=$counter\">$counter</a>";                   
                    }
                $last.= "<a class='page-numberactu' href=\"?page=$lastpage\">Final</a>";            
                }
                
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $first.= "<a class='page-numberactu' href=\"?page=1\">Principio</a>";  
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-numberactu' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last.= "<a class='page-numberactu' href=\"?page=$lastpage\">Final</a>";            
                }    
                else
                {
                    $first.= "<a class='page-numberactu' href=\"?page=1\">Principio</a>";  
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-numberactu' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last='';
                }   
            }
            if ($page < $counter - 1) 
                $next_.= "<a class='page-numberactu' href=\"?page=$next\">Siguiente</a>";
            else{ }
            $pagination = "<div class=\"pagination\">".$first.$prev_.$pagination.$next_.$last;
            
            $pagination.= "</div>\n";       
        }
        return $pagination;   
    }
    
    function pagination($limit,$adjacents,$t,$page){
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
                $prev_.= "<a class='page-numbers' href=\"?page=$prev\">Anterior</a>";
            else{ }
            
            if ($lastpage < 5 + ($adjacents * 2))
            {   
            $first='';
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";                   
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
                            $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";                   
                    }
                $last.= "<a class='page-numbers' href=\"?page=$lastpage\">Final</a>";            
                }
                
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $first.= "<a class='page-numbers' href=\"?page=1\">Principio</a>";  
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last.= "<a class='page-numbers' href=\"?page=$lastpage\">Final</a>";            
                }    
                else
                {
                    $first.= "<a class='page-numbers' href=\"?page=1\">Principio</a>";  
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last='';
                }   
            }
            if ($page < $counter - 1) 
                $next_.= "<a class='page-numbers' href=\"?page=$next\">Siguiente</a>";
            else{ }
            $pagination = "<div class=\"pagination\">".$first.$prev_.$pagination.$next_.$last;
            
            $pagination.= "</div>\n";       
        }
        return $pagination;   
    }

    function crearMiniatura($filename){
        //'./documentos/imagenes_de_pacientes/'.$carpeta.'/';
        $config['image_library'] = 'gd2';
        $config['source_image'] = './documentos/imagenes_de_pacientes/'.$filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['new_image']='./documentos/imagenes_de_pacientes/thumbs/';
        $config['thumb_marker']='';//captura_thumb.png
        $config['width'] = 230;
        $config['height'] = 235;
        $this->load->library('image_lib', $config); 
        $this->image_lib->resize();
        if (!$this->image_lib->resize()) {
            $data['error']  = $this->image_lib->display_errors();
        }else{
            unlink('./documentos/imagenes_de_pacientes/'.$filename);
        }
        
    }
    
    function validar_imagen() {
        $check = TRUE;
        if ((!isset($_FILES['img_usuario'])) || $_FILES['img_usuario']['size'] == 0) {
            //$this->form_validation->set_message('validar_imagen', 'The {field} field is required ');
            $check = TRUE;
        }elseif (isset($_FILES['img_usuario']) && $_FILES['img_usuario']['size'] != 0) {
            $allowedExts = array("jpeg", "jpg", "png", "JPG", "JPEG", "PNG");
            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
            $extension = pathinfo($_FILES["img_usuario"]["name"], PATHINFO_EXTENSION);
            $detectedType = exif_imagetype($_FILES['img_usuario']['tmp_name']);
            $type = $_FILES['img_usuario']['type'];
            if (!in_array($detectedType, $allowedTypes)) {
                $this->form_validation->set_message('validar_imagen', 'El campo <strong>Foto</strong>: Contenido de imagen no válido!');
                $check = FALSE;
            }
            if(filesize($_FILES['img_usuario']['tmp_name']) > 2000000) {
                $this->form_validation->set_message('validar_imagen', 'El campo <strong>Foto</strong>: El tamaño del archivo de imagen no debe exceder 20MB!');
                $check = FALSE;
            }
            if(!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('validar_imagen', "El campo <strong>Foto</strong>: La Extensión de archivo ({$extension}) es inválida, por favor seleccione solo imagenes / jpg / png /.");
                $check = FALSE;
            }
        }
        return $check;
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

    function paginationaprobados($limit,$adjacents,$t,$page){
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
                $prev_.= "<a class='page-aprobado' href=\"?page=$prev\">Anterior</a>";
            else{ }
            
            if ($lastpage < 5 + ($adjacents * 2))
            {   
            $first='';
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-aprobado' href=\"?page=$counter\">$counter</a>";                   
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
                            $pagination.= "<a class='page-aprobado' href=\"?page=$counter\">$counter</a>";                   
                    }
                $last.= "<a class='page-aprobado' href=\"?page=$lastpage\">Final</a>";            
                }
                
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $first.= "<a class='page-aprobado' href=\"?page=1\">Principio</a>";  
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-aprobado' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last.= "<a class='page-aprobado' href=\"?page=$lastpage\">Final</a>";            
                }    
                else
                {
                    $first.= "<a class='page-aprobado' href=\"?page=1\">Principio</a>";  
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-aprobado' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last='';
                }   
            }
            if ($page < $counter - 1) 
                $next_.= "<a class='page-aprobado' href=\"?page=$next\">Siguiente</a>";
            else{ }
            $pagination = "<div class=\"pagination\">".$first.$prev_.$pagination.$next_.$last;
            
            $pagination.= "</div>\n";       
        }
        return $pagination;   
    }

  function paginationaprobadosAC($limit,$adjacents,$t,$page){
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
                $prev_.= "<a class='page-aprobadoac' href=\"?page=$prev\">Anterior</a>";
            else{ }
            
            if ($lastpage < 5 + ($adjacents * 2))
            {   
            $first='';
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='page-aprobadoac' href=\"?page=$counter\">$counter</a>";                   
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
                            $pagination.= "<a class='page-aprobadoac' href=\"?page=$counter\">$counter</a>";                   
                    }
                $last.= "<a class='page-aprobadoac' href=\"?page=$lastpage\">Final</a>";            
                }
                
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $first.= "<a class='page-aprobadoac' href=\"?page=1\">Principio</a>";  
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-aprobadoac' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last.= "<a class='page-aprobadoac' href=\"?page=$lastpage\">Final</a>";            
                }    
                else
                {
                    $first.= "<a class='page-aprobadoac' href=\"?page=1\">Principio</a>";  
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a class='page-aprobadoac' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last='';
                }   
            }
            if ($page < $counter - 1) 
                $next_.= "<a class='page-aprobadoac' href=\"?page=$next\">Siguiente</a>";
            else{ }
            $pagination = "<div class=\"pagination\">".$first.$prev_.$pagination.$next_.$last;
            
            $pagination.= "</div>\n";       
        }
        return $pagination;   
    }


    function pagination_TablaClteCensados($limit,$adjacents,$t,$page){
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
                $prev_.= "<a class='page-TablaClteCensados' href=\"?page=$prev\">Anterior</a>";
            else{ }
            
            if ($lastpage < 5 + ($adjacents * 2))
            {   
            $first='';
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"currentd\">$counter</span>";
                    else
                        $pagination.= "<a class='page-TablaClteCensados' href=\"?page=$counter\">$counter</a>";                   
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
                            $pagination.= "<span class=\"currentd\">$counter</span>";
                        else
                            $pagination.= "<a class='page-TablaClteCensados' href=\"?page=$counter\">$counter</a>";                   
                    }
                $last.= "<a class='page-TablaClteCensados' href=\"?page=$lastpage\">Final</a>";            
                }
                
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $first.= "<a class='page-TablaClteCensados' href=\"?page=1\">Principio</a>";  
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"currentd\">$counter</span>";
                        else
                            $pagination.= "<a class='page-TablaClteCensados' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last.= "<a class='page-TablaClteCensados' href=\"?page=$lastpage\">Final</a>";            
                }    
                else
                {
                    $first.= "<a class='page-TablaClteCensados' href=\"?page=1\">Principio</a>";  
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"currentd\">$counter</span>";
                        else
                            $pagination.= "<a class='page-TablaClteCensados' href=\"?page=$counter\">$counter</a>";                   
                    }
                    $last='';
                }   
            }
            if ($page < $counter - 1) 
                $next_.= "<a class='page-TablaClteCensados' href=\"?page=$next\">Siguiente</a>";
            else{ }
            $pagination = "<div class=\"pagination\">".$first.$prev_.$pagination.$next_.$last;
            
            $pagination.= "</div>\n";       
        }
        return $pagination;   
    }




}
?>