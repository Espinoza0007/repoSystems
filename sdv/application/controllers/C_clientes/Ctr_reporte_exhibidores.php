<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
date_default_timezone_set('America/El_Salvador');
ini_set('max_execution_time', 0);
class Ctr_reporte_exhibidores extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->model('M_usuarios/Mdl_login','lg');
        $this->load->model('M_exhibidores/Mdl_Reporte_Exh','Rexh');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }

	function index(){
        $this->global['pageTitle'] = 'Reporte Exhibidores';
        $this->loadViews_gerencia('Clientes/V_reporte_exhibidores',$this->global);
  	}


    function Lista_TablaGenralUno(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTabla = $this->Rexh->ReporteGeneralUno($param_busqueda);$arrg_ls_GenralUnoTabla = array();
            $porcentajeAV = 0;$cUno = 0;
            $tpv_SV = 0;$tpv_GT = 0;$tpv_HN = 0;$tpv_RDO = 0;
            $tpvActu_SV = 0;$tpvActu_GT = 0;$tpvActu_HN = 0;$tpvActu_RDO = 0;
            $tpvActuNO_SV = 0;$tpvActuNO_GT = 0;$tpvActuNO_HN = 0;$tpvActuNO_RDO = 0;
            $tporcenta_SV = 0;$tporcenta_GT = 0;$tporcenta_HN = 0;$tporcenta_RDO = 0;
            $full_tpv = 0;$full_tpvActu = 0;$full_tpvActuNo = 0;$full_tporcentaje =0;
            foreach ($ls_GenralUnoTabla as $k) {

                if($k->totalpdv == 0){
                    $porcentajeAV = 0;
                }else{
                    $porcentajeAV = ($k->pdvactualizados/$k->totalpdv) * 100;
                }
                
                if (strcmp($k->Nombre_Pais, "EL SALVADOR") == 0) {
                    $tpv_SV += $k->totalpdv;
                    $tpvActu_SV += $k->pdvactualizados;
                    $tpvActuNO_SV += $k->NOpdvactualizados;
                }elseif(strcmp($k->Nombre_Pais, "GUATEMALA") == 0){
                    $tpv_GT += $k->totalpdv;
                    $tpvActu_GT += $k->pdvactualizados;
                    $tpvActuNO_GT += $k->NOpdvactualizados;
                }elseif(strcmp($k->Nombre_Pais, "HONDURAS") == 0){
                    $tpv_HN += $k->totalpdv;
                    $tpvActu_HN += $k->pdvactualizados;
                    $tpvActuNO_HN += $k->NOpdvactualizados;
                }elseif(strcmp($k->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                    $tpv_RDO += $k->totalpdv;
                    $tpvActu_RDO += $k->pdvactualizados;
                    $tpvActuNO_RDO += $k->NOpdvactualizados;
                }

                $arrg_ls_GenralUnoTabla[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTabla[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTabla[$cUno]['totalpdv'] = $k->totalpdv;
                $arrg_ls_GenralUnoTabla[$cUno]['pdvactualizados'] = $k->pdvactualizados;
                $arrg_ls_GenralUnoTabla[$cUno]['NOpdvactualizados'] = $k->NOpdvactualizados;
                $arrg_ls_GenralUnoTabla[$cUno]['avance'] = floor($porcentajeAV);
                $porcentajeAV = 0;
                $cUno++;
            }

            if($tpv_SV == 0){
                $tporcenta_SV = 0;
            }else{
                $tporcenta_SV = ($tpvActu_SV/$tpv_SV) * 100;
            }

            if($tpv_GT == 0){
                $tporcenta_GT = 0;
            }else{
                $tporcenta_GT = ($tpvActu_GT/$tpv_GT) * 100;
            }
           
            if($tpv_HN == 0){
                $tporcenta_HN = 0;
            }else{
                $tporcenta_HN = ($tpvActu_HN/$tpv_HN) * 100;
            }

            if($tpv_RDO == 0){
                $tporcenta_RDO = 0;
            }else{
                $tporcenta_RDO = ($tpvActu_RDO/$tpv_RDO) * 100;
            }

            $arrg_totaPDV = array();
            $arrg_totaPDV[0] = $tpv_SV;
            $arrg_totaPDV[1] = $tpv_GT;
            $arrg_totaPDV[2] = $tpv_HN;
            $arrg_totaPDV[3] = $tpv_RDO;
            $arrg_totaPDVaCTU = array();
            $arrg_totaPDVaCTU[0] = $tpvActu_SV;
            $arrg_totaPDVaCTU[1] = $tpvActu_GT;
            $arrg_totaPDVaCTU[2] = $tpvActu_HN;
            $arrg_totaPDVaCTU[3] = $tpvActu_RDO;
            $arrg_totaPDVaCTUnO = array();
            $arrg_totaPDVaCTUnO[0] = $tpvActuNO_SV;
            $arrg_totaPDVaCTUnO[1] = $tpvActuNO_GT;
            $arrg_totaPDVaCTUnO[2] = $tpvActuNO_HN;
            $arrg_totaPDVaCTUnO[3] = $tpvActuNO_RDO;
            $arrg_TotalPorcen= array();
            $arrg_TotalPorcen[0] = floor($tporcenta_SV);
            $arrg_TotalPorcen[1] = floor($tporcenta_GT);
            $arrg_TotalPorcen[2] = floor($tporcenta_HN);
            $arrg_TotalPorcen[3] = floor($tporcenta_RDO);
            $arrg_FullTotales= array();
            $full_tpv = $tpv_SV + $tpv_GT + $tpv_HN + $tpv_RDO;
            $full_tpvActu = $tpvActu_SV + $tpvActu_GT + $tpvActu_HN + $tpvActu_RDO;
            $full_tpvActuNo = $tpvActuNO_SV + $tpvActuNO_GT + $tpvActuNO_HN + $tpvActu_RDO;

            if($full_tpv == 0){
                $full_tporcentaje = 0;
            }else{
                $full_tporcentaje = ($full_tpvActu/$full_tpv) * 100;
            }
            
            $arrg_FullTotales[0] = $full_tpv;
            $arrg_FullTotales[1] = $full_tpvActu;
            $arrg_FullTotales[2] = $full_tpvActuNo;
            $arrg_FullTotales[3] = floor($full_tporcentaje);
            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralUno' => $arrg_ls_GenralUnoTabla,
                'tpv' => $arrg_totaPDV,
                'tpvAC' => $arrg_totaPDVaCTU,
                'tpvACNO' => $arrg_totaPDVaCTUnO,
                'tpvPOR' => $arrg_TotalPorcen,
                'FullTotales' => $arrg_FullTotales
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }

    function Lista_TablaGenralUnoZoomUno(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->ReporteGeneralUnoZoomUNO($param_busqueda);$arrg_ls_GenralUnoTablaZUNO = array();

            $porcentajeAV = 0;
            $cUno = 0;
            $tpv = 0;$tpvActu = 0;$tpvActuNO = 0;$full_tporcentaje = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {

                if($k->totalpdv == 0){
                    $porcentajeAV = 0;
                }else{
                    $porcentajeAV = ($k->pdvactualizados/$k->totalpdv) * 100;
                }


    
                $tpv += $k->totalpdv;
                $tpvActu += $k->pdvactualizados;
                $tpvActuNO += $k->NOpdvactualizados;


                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['totalpdv'] = $k->totalpdv;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['pdvactualizados'] = $k->pdvactualizados;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['NOpdvactualizados'] = $k->NOpdvactualizados;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['avance'] = floor($porcentajeAV);
                $porcentajeAV = 0;
                $cUno++;
            }

            if($tpv == 0){
                $full_tporcentaje = 0;
            }else{
                $full_tporcentaje = ($tpvActu/$tpv) * 100;
            }

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralUnoZommUno' => $arrg_ls_GenralUnoTablaZUNO,
                'tpv' => $tpv,
                'tpvAC' => $tpvActu,
                'tpvACNO' => $tpvActuNO,
                'tpvPOR' => floor($full_tporcentaje)
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    function Lista_TablaGenralUnoZoomDos(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_grupo = $this->input->post('grupo');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal,
                'grupo' => $param_grupo
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->ReporteGeneralUnoZoomDOS($param_busqueda);$arrg_ls_GenralUnoTablaZDOS = array();

            $porcentajeAV = 0;
            $cUno = 0;
            $tpv = 0;$tpvActu = 0;$tpvActuNO = 0;$full_tporcentaje = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {

                if($k->totalpdv == 0){
                    $porcentajeAV = 0;
                }else{
                    $porcentajeAV = ($k->pdvactualizados/$k->totalpdv) * 100;
                }


    
                $tpv += $k->totalpdv;
                $tpvActu += $k->pdvactualizados;
                $tpvActuNO += $k->NOpdvactualizados;

                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Ruta'] = $k->Nombre_Ruta;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['totalpdv'] = $k->totalpdv;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['pdvactualizados'] = $k->pdvactualizados;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['NOpdvactualizados'] = $k->NOpdvactualizados;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['avance'] = floor($porcentajeAV);
                $porcentajeAV = 0;
                $cUno++;
            }


            if($tpv == 0){
                $full_tporcentaje = 0;
            }else{
                $full_tporcentaje = ($tpvActu/$tpv) * 100;
            }
            $arrg_FullTotales= array();
            $arrg_FullTotales[0] = $tpv;
            $arrg_FullTotales[1] = $tpvActu;
            $arrg_FullTotales[2] = $tpvActuNO;
            $arrg_FullTotales[3] = floor($full_tporcentaje);   
            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralUnoZommDos' => $arrg_ls_GenralUnoTablaZDOS,
                'tpv' => $tpv,
                'tpvAC' => $tpvActu,
                'tpvACNO' => $tpvActuNO,
                'tpvPOR' => floor($full_tporcentaje)
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    /*TABLA DE CON Y SIN EXIBIDOR TABLA  DOS*/


    function Lista_TablaConSinExh(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTabla = $this->Rexh->RConSinExhActualizados($param_busqueda);$arrg_ls_GenralUnoTabla = array();
            $porcentajeACon = 0;$porcentajeASin = 0;$cUno = 0;//PORCENTAJES
            $tpvActu_SV = 0;$tpvActu_GT = 0;$tpvActu_HN = 0;$tpvActu_RDO = 0;//ACTUALIZADOS
            $SinExh_SV = 0;$SinExh_GT = 0;$SinExh_HN = 0;$SinExh_RDO = 0;//SIN EXHIBIDOR
            $ConExh_SV = 0;$ConExh_GT = 0;$ConExh_HN = 0;$ConExh_RDO = 0;//CON EXHIBIDOR

            $tporcentaSIN_SV = 0;$tporcentaSIN_GT = 0;$tporcentaSIN_HN = 0;$tporcentaSIN_RDO = 0;//TOTAL PORCENTAJES SIN
            $tporcentaCON_SV = 0;$tporcentaCON_GT = 0;$tporcentaCON_HN = 0;$tporcentaCON_RDO = 0;//TOTAL PORCENTAJES CON
            $full_tpvActu = 0;$full_tpvSIN = 0;$full_tpvCON = 0;$full_tporcentajeSIN =0;$full_tporcentajeCON =0;
            foreach ($ls_GenralUnoTabla as $k) {

                /* AVANCE SIN EXHIBIDOR*/
                if($k->totalactualizados == 0){
                    $porcentajeASin = 0;
                }else{
                    $porcentajeASin = ($k->sinexhibidor/$k->totalactualizados) * 100;
                }

                /* AVANCE CON EXHIBIDOR*/
                if($k->totalactualizados == 0){
                    $porcentajeACon = 0;
                }else{
                    $porcentajeACon = ($k->conexhibidor/$k->totalactualizados) * 100;
                }
                

                if (strcmp($k->Nombre_Pais, "EL SALVADOR") == 0) {
                    $tpvActu_SV += $k->totalactualizados;
                    $SinExh_SV += $k->sinexhibidor;
                    $ConExh_SV += $k->conexhibidor;
                }elseif(strcmp($k->Nombre_Pais, "GUATEMALA") == 0){
                    $tpvActu_GT += $k->totalactualizados;
                    $SinExh_GT += $k->sinexhibidor;
                    $ConExh_GT += $k->conexhibidor;
                }elseif(strcmp($k->Nombre_Pais, "HONDURAS") == 0){
                    $tpvActu_HN += $k->totalactualizados;
                    $SinExh_HN += $k->sinexhibidor;
                    $ConExh_HN += $k->conexhibidor;
                }elseif(strcmp($k->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                    $tpvActu_RDO += $k->totalactualizados;
                    $SinExh_RDO += $k->sinexhibidor;
                    $ConExh_RDO += $k->conexhibidor;
                }

                $arrg_ls_GenralUnoTabla[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTabla[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTabla[$cUno]['pdvactualizados'] = $k->totalactualizados;
                $arrg_ls_GenralUnoTabla[$cUno]['sinexhibidores'] = $k->sinexhibidor;
                $arrg_ls_GenralUnoTabla[$cUno]['conexhibidores'] = $k->conexhibidor;
                $arrg_ls_GenralUnoTabla[$cUno]['avanceSin'] = round($porcentajeASin,0, PHP_ROUND_HALF_UP);
                $arrg_ls_GenralUnoTabla[$cUno]['avanceCon'] = round($porcentajeACon,0, PHP_ROUND_HALF_UP);
                $porcentajeACon = 0;
                $porcentajeASin = 0;
                $cUno++;
            }

            /*TOTAL PORCENTAJE AVANCE SIN EXHIBIDOR*/
            if($tpvActu_SV == 0){
                $tporcentaSIN_SV = 0;
            }else{
                $tporcentaSIN_SV = ($SinExh_SV/$tpvActu_SV) * 100;
            }

            if($tpvActu_GT == 0){
                $tporcentaSIN_GT = 0;
            }else{
                $tporcentaSIN_GT = ($SinExh_GT/$tpvActu_GT) * 100;
            }

            if($tpvActu_HN == 0){
                $tporcentaSIN_HN = 0;
            }else{
                $tporcentaSIN_HN = ($SinExh_HN/$tpvActu_HN) * 100;
            }

            if($tpvActu_RDO == 0){
                $tporcentaSIN_RDO = 0;
            }else{
                $tporcentaSIN_RDO = ($SinExh_RDO/$tpvActu_RDO) * 100;
            }

            /*TOTAL PORCENTAJE AVANCE SIN EXHIBIDOR*/

            if($tpvActu_SV == 0){
                $tporcentaCON_SV = 0;
            }else{
                $tporcentaCON_SV = ($ConExh_SV/$tpvActu_SV) * 100;
            }

            if($tpvActu_GT == 0){
                $tporcentaCON_GT = 0;
            }else{
                $tporcentaCON_GT = ($ConExh_GT/$tpvActu_GT) * 100;
            }

            if($tpvActu_HN == 0){
                $tporcentaCON_HN = 0;
            }else{
                $tporcentaCON_HN = ($ConExh_HN/$tpvActu_HN) * 100;
            }

            if($tpvActu_RDO == 0){
                $tporcentaCON_RDO = 0;
            }else{
                $tporcentaCON_RDO = ($ConExh_RDO/$tpvActu_RDO) * 100;
            }


            /*----------------------------------------------------------------------------*/
            $arrg_totaPDVaCTU = array();
            $arrg_totaPDVaCTU[0] = $tpvActu_SV;
            $arrg_totaPDVaCTU[1] = $tpvActu_GT;
            $arrg_totaPDVaCTU[2] = $tpvActu_HN;
            $arrg_totaPDVaCTU[3] = $tpvActu_RDO;
            /*----------------------------------------------------------------------------*/
            $arrg_totaPDVSINEXH = array();
            $arrg_totaPDVSINEXH[0] = $SinExh_SV;
            $arrg_totaPDVSINEXH[1] = $SinExh_GT;
            $arrg_totaPDVSINEXH[2] = $SinExh_HN;
            $arrg_totaPDVSINEXH[3] = $SinExh_RDO;
            /*----------------------------------------------------------------------------*/
            $arrg_totaPDVCONEXH = array();
            $arrg_totaPDVCONEXH[0] = $ConExh_SV;
            $arrg_totaPDVCONEXH[1] = $ConExh_SV;
            $arrg_totaPDVCONEXH[2] = $ConExh_SV;
            $arrg_totaPDVCONEXH[3] = $ConExh_SV;
            /*----------------------------------------------------------------------------*/
            $arrg_TotalPorcenSIN = array();
            $arrg_TotalPorcenSIN[0] = round($tporcentaSIN_SV, 0, PHP_ROUND_HALF_UP);
            $arrg_TotalPorcenSIN[1] = round($tporcentaSIN_GT, 0, PHP_ROUND_HALF_UP);
            $arrg_TotalPorcenSIN[2] = round($tporcentaSIN_HN, 0, PHP_ROUND_HALF_UP);
            $arrg_TotalPorcenSIN[3] = round($tporcentaSIN_RDO, 0, PHP_ROUND_HALF_UP);
            /*----------------------------------------------------------------------------*/
            $arrg_TotalPorcenCON = array();
            $arrg_TotalPorcenCON[0] = round($tporcentaCON_SV, 0, PHP_ROUND_HALF_UP);
            $arrg_TotalPorcenCON[1] = round($tporcentaCON_GT, 0, PHP_ROUND_HALF_UP);
            $arrg_TotalPorcenCON[2] = round($tporcentaCON_HN, 0, PHP_ROUND_HALF_UP);
            $arrg_TotalPorcenCON[3] = round($tporcentaCON_RDO, 0, PHP_ROUND_HALF_UP);
            /*----------------------------------------------------------------------------*/
            $arrg_FullTotales = array();
            $full_tpvActu = $tpvActu_SV + $tpvActu_GT + $tpvActu_HN + $tpvActu_RDO;
            $full_tpvSIN = $SinExh_SV + $SinExh_GT + $SinExh_HN + $SinExh_RDO;
            $full_tpvCON = $ConExh_SV + $ConExh_GT + $ConExh_HN + $ConExh_RDO;

            if($full_tpvActu == 0){
                $full_tporcentajeSIN = 0;
            }else{
                $full_tporcentajeSIN = ($full_tpvSIN/$full_tpvActu) * 100;
            }
            if($full_tpvActu == 0){
                $full_tporcentajeCON = 0;
            }else{
                $full_tporcentajeCON = ($full_tpvCON/$full_tpvActu) * 100;
            }    
            $arrg_FullTotales[0] = $full_tpvActu;
            $arrg_FullTotales[1] = $full_tpvSIN;
            $arrg_FullTotales[2] = $full_tpvCON;
            $arrg_FullTotales[3] = round($full_tporcentajeSIN, 0, PHP_ROUND_HALF_UP);
            $arrg_FullTotales[4] = round($full_tporcentajeCON, 0, PHP_ROUND_HALF_UP);
            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralDos' => $arrg_ls_GenralUnoTabla,
                'tpvAC' => $arrg_totaPDVaCTU,
                'tpvSIN' => $arrg_totaPDVSINEXH,
                'tpvCON' => $arrg_totaPDVCONEXH,
                'tpvPORSIN' => $arrg_TotalPorcenSIN,
                'tpvPORCON' => $arrg_TotalPorcenCON,
                'FullTotales' => $arrg_FullTotales
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }


    function Lista_TablaConSinExhZoomUno(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RConSinExhActualizadosZOOMUNO($param_busqueda);
            $arrg_ls_GenralUnoTablaZUNO = array();
            $porcentajeASin = 0;$porcentajeACon = 0;$cUno = 0;
            $tpvActu = 0;$tpvSIN = 0;$tpvCON = 0;
            $full_tporcentajeSIN = 0;$full_tporcentajeCON = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {

                /* AVANCE SIN EXHIBIDOR*/
                if($k->totalactualizados == 0){
                    $porcentajeASin = 0;
                }else{
                    $porcentajeASin = ($k->sinexhibidor/$k->totalactualizados) * 100;
                }

                /* AVANCE CON EXHIBIDOR*/
                if($k->totalactualizados == 0){
                    $porcentajeACon = 0;
                }else{
                    $porcentajeACon = ($k->conexhibidor/$k->totalactualizados) * 100;
                }


                $tpvActu += $k->totalactualizados;
                $tpvSIN += $k->sinexhibidor;
                $tpvCON += $k->conexhibidor;


                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['pdvactualizados'] = $k->totalactualizados;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['sinexhibidores'] = $k->sinexhibidor;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['conexhibidores'] = $k->conexhibidor;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['avanceSin'] = round($porcentajeASin, 0, PHP_ROUND_HALF_UP);
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['avanceCon'] = round($porcentajeACon, 0, PHP_ROUND_HALF_UP);
                $porcentajeASin = 0;$porcentajeACon = 0;
                $cUno++;
            }


            if($tpvActu == 0){
                $full_tporcentajeSIN = 0;
            }else{
                $full_tporcentajeSIN = ($tpvSIN/$tpvActu) * 100;
            }

            if($tpvActu == 0){
                $full_tporcentajeCON = 0;
            }else{
                $full_tporcentajeCON = ($tpvCON/$tpvActu) * 100;
            }

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralDosZoomUno' => $arrg_ls_GenralUnoTablaZUNO,
                'totalpdvA' => $tpvActu,
                'tpvSIN' => $tpvSIN,
                'tpvCON' => $tpvCON,
                'tpvPORSIN' => round($full_tporcentajeSIN, 0, PHP_ROUND_HALF_UP),
                'tpvPORCON' => round($full_tporcentajeCON, 0, PHP_ROUND_HALF_UP)
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }



    function Lista_TablaConSinExhZoomDos(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_grupo = $this->input->post('grupo');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal,
                'grupo' => $param_grupo
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RConSinExhActualizadosZOOMDOS($param_busqueda);
            $arrg_ls_GenralUnoTablaZDOS = array();

            $porcentajeASin = 0;$porcentajeASin = 0;
            $cUno = 0;
            $tpvActu = 0;
            $tpvSIN = 0;$tpvCON = 0;
            $full_tporcentajeSIN = 0;$full_tporcentajeCON = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {

                /* AVANCE SIN EXHIBIDOR*/
                if($k->totalactualizados == 0){
                    $porcentajeASin = 0;
                }else{
                    $porcentajeASin = ($k->sinexhibidor/$k->totalactualizados) * 100;
                }

                /* AVANCE CON EXHIBIDOR*/
                if($k->totalactualizados == 0){
                    $porcentajeACon = 0;
                }else{
                    $porcentajeACon = ($k->conexhibidor/$k->totalactualizados) * 100;
                }
    
                $tpvActu += $k->totalactualizados;
                $tpvSIN += $k->sinexhibidor;
                $tpvCON += $k->conexhibidor;


                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Ruta'] = $k->Nombre_Ruta;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['pdvactualizados'] = $k->totalactualizados;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['sinexhibidores'] = $k->sinexhibidor;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['conexhibidores'] = $k->conexhibidor;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['avanceSin'] = round($porcentajeASin, 0, PHP_ROUND_HALF_DOWN);
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['avanceCon'] = round($porcentajeACon, 0, PHP_ROUND_HALF_DOWN);
                $porcentajeASin = 0;$porcentajeACon = 0;
                $cUno++;
            }


            if($tpvActu == 0){
                $full_tporcentajeSIN = 0;
            }else{
                $full_tporcentajeSIN = ($tpvSIN/$tpvActu) * 100;
            }

            if($tpvActu == 0){
                $full_tporcentajeCON = 0;
            }else{
                $full_tporcentajeCON = ($tpvCON/$tpvActu) * 100;
            }

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralDosZoomDos' => $arrg_ls_GenralUnoTablaZDOS,
                'totalpdvA' => $tpvActu,
                'tpvSIN' => $tpvSIN,
                'tpvCON' => $tpvCON,
                'tpvPORSIN' => round($full_tporcentajeSIN, 0, PHP_ROUND_HALF_DOWN),
                'tpvPORCON' => round($full_tporcentajeCON, 0, PHP_ROUND_HALF_DOWN)
            ));


        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    /*------------------------------------------------------------------------*/
    function Lista_TablaPorTipoAct(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTabla = $this->Rexh->RporTipoActualizacion($param_busqueda);
            $arrg_ls_GenralUnoTabla = array();

            $cUno = 0;
            $tpvCantidExh_SV = 0;$tpvCantidExh_GT = 0;$tpvCantidExh_HN = 0;$tpvCantidExh_RDO = 0;
            $tpvExhqtiene_SV = 0;$tpvExhqtiene_GT = 0;$tpvExhqtiene_HN = 0;$tpvExhqtiene_RDO = 0;
            $tpvExhDevuel_SV = 0;$tpvExhDevuel_GT = 0;$tpvExhDevuel_HN = 0;$tpvExhDevuel_RDO = 0;
            $tpvExhNuevos_SV = 0;$tpvExhNuevos_GT = 0;$tpvExhNuevos_HN = 0;$tpvExhNuevos_RDO = 0;
            $full_tpvCantidExh = 0;$full_tpvExhqtiene = 0;$full_tpvExhDevuel = 0;$full_tpvExhNuevos = 0;

            foreach ($ls_GenralUnoTabla as $k) {

                if (strcmp($k->Nombre_Pais, "EL SALVADOR") == 0) {
                    $tpvCantidExh_SV += $k->totalexhibidores;
                    $tpvExhqtiene_SV += $k->exhquetiene;
                    $tpvExhDevuel_SV += $k->exhdevueltos;
                    $tpvExhNuevos_SV += $k->exhnuevos;
                }elseif(strcmp($k->Nombre_Pais, "GUATEMALA") == 0){
                    $tpvCantidExh_GT += $k->totalexhibidores;
                    $tpvExhqtiene_GT += $k->exhquetiene;
                    $tpvExhDevuel_GT += $k->exhdevueltos;
                    $tpvExhNuevos_GT += $k->exhnuevos;
                }elseif(strcmp($k->Nombre_Pais, "HONDURAS") == 0){
                    $tpvCantidExh_HN += $k->totalexhibidores;
                    $tpvExhqtiene_HN += $k->exhquetiene;
                    $tpvExhDevuel_HN += $k->exhdevueltos;
                    $tpvExhNuevos_HN += $k->exhnuevos;
                }elseif(strcmp($k->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                    $tpvCantidExh_RDO += $k->totalexhibidores;
                    $tpvExhqtiene_RDO += $k->exhquetiene;
                    $tpvExhDevuel_RDO += $k->exhdevueltos;
                    $tpvExhNuevos_RDO += $k->exhnuevos;
                }

                $arrg_ls_GenralUnoTabla[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTabla[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTabla[$cUno]['totalpdvexh'] = $k->totalexhibidores;
                $arrg_ls_GenralUnoTabla[$cUno]['exhquetiene'] = $k->exhquetiene;
                $arrg_ls_GenralUnoTabla[$cUno]['exhdevueltos'] = $k->exhdevueltos;
                $arrg_ls_GenralUnoTabla[$cUno]['exnuevos'] = $k->exhnuevos;
                $cUno++;
            }

            $arrg_totaPDVCexh = array();
            $arrg_totaPDVCexh[0] = $tpvCantidExh_SV;
            $arrg_totaPDVCexh[1] = $tpvCantidExh_GT;
            $arrg_totaPDVCexh[2] = $tpvCantidExh_HN;
            $arrg_totaPDVCexh[3] = $tpvCantidExh_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhqtiene = array();
            $arrg_tpvExhqtiene[0] = $tpvExhqtiene_SV;
            $arrg_tpvExhqtiene[1] = $tpvExhqtiene_GT;
            $arrg_tpvExhqtiene[2] = $tpvExhqtiene_HN;
            $arrg_tpvExhqtiene[3] = $tpvExhqtiene_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhDevuel = array();
            $arrg_tpvExhDevuel[0] = $tpvExhDevuel_SV;
            $arrg_tpvExhDevuel[1] = $tpvExhDevuel_GT;
            $arrg_tpvExhDevuel[2] = $tpvExhDevuel_HN;
            $arrg_tpvExhDevuel[3] = $tpvExhDevuel_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhNuevos= array();
            $arrg_tpvExhNuevos[0] = $tpvExhNuevos_SV;
            $arrg_tpvExhNuevos[1] = $tpvExhNuevos_GT;
            $arrg_tpvExhNuevos[2] = $tpvExhNuevos_HN;
            $arrg_tpvExhNuevos[3] = $tpvExhNuevos_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_FullTotales= array();
            $full_tpvCantidExh = $tpvCantidExh_SV + $tpvCantidExh_GT + $tpvCantidExh_HN + $tpvCantidExh_RDO;
            $full_tpvExhqtiene = $tpvExhqtiene_SV + $tpvExhqtiene_GT + $tpvExhqtiene_HN + $tpvExhqtiene_RDO;
            $full_tpvExhDevuel = $tpvExhDevuel_SV + $tpvExhDevuel_GT + $tpvExhDevuel_HN + $tpvExhDevuel_RDO;
            $full_tpvExhNuevos = $tpvExhNuevos_SV + $tpvExhNuevos_GT + $tpvExhNuevos_HN + $tpvExhNuevos_RDO;
            $arrg_FullTotales[0] = $full_tpvCantidExh;
            $arrg_FullTotales[1] = $full_tpvExhqtiene;
            $arrg_FullTotales[2] = $full_tpvExhDevuel;
            $arrg_FullTotales[3] = $full_tpvExhNuevos;

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralUnoTAct' => $arrg_ls_GenralUnoTabla,
                'tpvCantidadExh' => $arrg_totaPDVCexh,
                'tpvExhquetiene' => $arrg_tpvExhqtiene,
                'tpvExhdevueltos' => $arrg_tpvExhDevuel,
                'tpvExhnuevos' => $arrg_tpvExhNuevos,
                'FullTotales' => $arrg_FullTotales
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }


    function Lista_TablaPorTipoActZoomUno(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RporTipoActualizacionZOOMUNO($param_busqueda);
            $arrg_ls_GenralUnoTablaZUNO = array();
            $cUno = 0;
            $tpvCantidExh = 0;$tpvExhqtiene = 0;$tpvExhDevuel = 0;$tpvExhNuevos = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {

                $tpvCantidExh += $k->totalexhibidores;
                $tpvExhqtiene += $k->exhquetiene;
                $tpvExhDevuel += $k->exhdevueltos;
                $tpvExhNuevos += $k->exhnuevos;

                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['totalpdvexh'] = $k->totalexhibidores;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhquetiene'] = $k->exhquetiene;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhdevueltos'] = $k->exhdevueltos;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exnuevos'] = $k->exhnuevos;
                $cUno++;
            }

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralTresZoomUno' => $arrg_ls_GenralUnoTablaZUNO,
                'tpvCantidadExh' => $tpvCantidExh,
                'tpvExhquetiene' => $tpvExhqtiene,
                'tpvExhdevueltos' => $tpvExhDevuel,
                'tpvExhnuevos' => $tpvExhNuevos
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    function Lista_TablaPorTipoActZoomDos(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_grupo = $this->input->post('grupo');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal,
                'grupo' => $param_grupo
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RporTipoActualizacionZOOMUDOS($param_busqueda);
            $arrg_ls_GenralUnoTablaZDOS = array();


            $cUno = 0;

            $tpvCantidExh = 0;$tpvExhqtiene = 0;$tpvExhDevuel = 0;$tpvExhNuevos = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {
    
                $tpvCantidExh += $k->totalexhibidores;
                $tpvExhqtiene += $k->exhquetiene;
                $tpvExhDevuel += $k->exhdevueltos;
                $tpvExhNuevos += $k->exhnuevos;

                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Ruta'] = $k->Nombre_Ruta;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['totalpdvexh'] = $k->totalexhibidores;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhquetiene'] = $k->exhquetiene;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhdevueltos'] = $k->exhdevueltos;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exnuevos'] = $k->exhnuevos;
                $cUno++;
            }


            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralTresZoomDos' => $arrg_ls_GenralUnoTablaZDOS,
                'tpvCantidadExh' => $tpvCantidExh,
                'tpvExhquetiene' => $tpvExhqtiene,
                'tpvExhdevueltos' => $tpvExhDevuel,
                'tpvExhnuevos' => $tpvExhNuevos
            ));


        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    /*----------------------------------------------------------------------*/


    function Lista_TablaPorTipoObse(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTabla = $this->Rexh->RporTipoAObservacion($param_busqueda);
            $arrg_ls_GenralUnoTabla = array();

            $cUno = 0;
            $tpvExhqtiene_SV = 0;$tpvExhqtiene_GT = 0;$tpvExhqtiene_HN = 0;$tpvExhqtiene_RDO = 0;
            $tpvExhDesech_SV = 0;$tpvExhDesech_GT = 0;$tpvExhDesech_HN = 0;$tpvExhDesech_RDO = 0;
            $tpvExhInvadi_SV = 0;$tpvExhInvadi_GT = 0;$tpvExhInvadi_HN = 0;$tpvExhInvadi_RDO = 0;
            $tpvExhMalubi_SV = 0;$tpvExhMalubi_GT = 0;$tpvExhMalubi_HN = 0;$tpvExhMalubi_RDO = 0;
            $tpvExhRetira_SV = 0;$tpvExhRetira_GT = 0;$tpvExhRetira_HN = 0;$tpvExhRetira_RDO = 0;
            $tpvExhVisyAc_SV = 0;$tpvExhVisyAc_GT = 0;$tpvExhVisyAc_HN = 0;$tpvExhVisyAc_RDO = 0;
            $tpvExhNecesr_SV = 0;$tpvExhNecesr_GT = 0;$tpvExhNecesr_HN = 0;$tpvExhNecesr_RDO = 0;
            $full_tpvExhqtiene = 0;$full_tpvExhDesech = 0;$full_tpvExhInvadi = 0;$full_tpvExhMalubi = 0;
            $full_tpvExhRetira = 0;$full_tpvExhVisyAc = 0;$full_tpvExhNecesr = 0;
            foreach ($ls_GenralUnoTabla as $k) {

                if (strcmp($k->Nombre_Pais, "EL SALVADOR") == 0) {
                    $tpvExhqtiene_SV += $k->exhquetiene;
                    $tpvExhDesech_SV += $k->exhdesechadooguardado;
                    $tpvExhInvadi_SV += $k->exhinvadido;
                    $tpvExhMalubi_SV += $k->exhmalubicado;
                    $tpvExhRetira_SV += $k->exhretirados;
                    $tpvExhVisyAc_SV += $k->exhvisibleyaccesible;
                    $tpvExhNecesr_SV += $k->exhnecesitareparacion;
                }elseif(strcmp($k->Nombre_Pais, "GUATEMALA") == 0){
                    $tpvExhqtiene_GT += $k->exhquetiene;
                    $tpvExhDesech_GT += $k->exhdesechadooguardado;
                    $tpvExhInvadi_GT += $k->exhinvadido;
                    $tpvExhMalubi_GT += $k->exhmalubicado;
                    $tpvExhRetira_GT += $k->exhretirados;
                    $tpvExhVisyAc_GT += $k->exhvisibleyaccesible;
                    $tpvExhNecesr_GT += $k->exhnecesitareparacion;
                }elseif(strcmp($k->Nombre_Pais, "HONDURAS") == 0){
                    $tpvExhqtiene_HN += $k->exhquetiene;
                    $tpvExhDesech_HN += $k->exhdesechadooguardado;
                    $tpvExhInvadi_HN += $k->exhinvadido;
                    $tpvExhMalubi_HN += $k->exhmalubicado;
                    $tpvExhRetira_HN += $k->exhretirados;
                    $tpvExhVisyAc_HN += $k->exhvisibleyaccesible;
                    $tpvExhNecesr_HN += $k->exhnecesitareparacion;
                }elseif(strcmp($k->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                    $tpvExhqtiene_RDO += $k->exhquetiene;
                    $tpvExhDesech_RDO += $k->exhdesechadooguardado;
                    $tpvExhInvadi_RDO += $k->exhinvadido;
                    $tpvExhMalubi_RDO += $k->exhmalubicado;
                    $tpvExhRetira_RDO += $k->exhretirados;
                    $tpvExhVisyAc_RDO += $k->exhvisibleyaccesible;
                    $tpvExhNecesr_RDO += $k->exhnecesitareparacion;
                }

                $arrg_ls_GenralUnoTabla[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTabla[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTabla[$cUno]['exhquetiene'] = $k->exhquetiene;
                $arrg_ls_GenralUnoTabla[$cUno]['exhdesechados'] = $k->exhdesechadooguardado;
                $arrg_ls_GenralUnoTabla[$cUno]['exhinvadido'] = $k->exhinvadido;
                $arrg_ls_GenralUnoTabla[$cUno]['exhmalubicado'] = $k->exhmalubicado;
                $arrg_ls_GenralUnoTabla[$cUno]['exhretirado'] = $k->exhretirados;
                $arrg_ls_GenralUnoTabla[$cUno]['exhvisibles'] = $k->exhvisibleyaccesible;
                $arrg_ls_GenralUnoTabla[$cUno]['exhnecesitare'] = $k->exhnecesitareparacion;
                
                $cUno++;
            }

            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhqtiene = array();
            $arrg_tpvExhqtiene[0] = $tpvExhqtiene_SV;
            $arrg_tpvExhqtiene[1] = $tpvExhqtiene_GT;
            $arrg_tpvExhqtiene[2] = $tpvExhqtiene_HN;
            $arrg_tpvExhqtiene[3] = $tpvExhqtiene_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhDesech = array();
            $arrg_tpvExhDesech[0] = $tpvExhDesech_SV;
            $arrg_tpvExhDesech[1] = $tpvExhDesech_GT;
            $arrg_tpvExhDesech[2] = $tpvExhDesech_HN;
            $arrg_tpvExhDesech[3] = $tpvExhDesech_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhInvadi= array();
            $arrg_tpvExhInvadi[0] = $tpvExhInvadi_SV;
            $arrg_tpvExhInvadi[1] = $tpvExhInvadi_GT;
            $arrg_tpvExhInvadi[2] = $tpvExhInvadi_HN;
            $arrg_tpvExhInvadi[3] = $tpvExhInvadi_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhMalubi= array();
            $arrg_tpvExhMalubi[0] = $tpvExhMalubi_SV;
            $arrg_tpvExhMalubi[1] = $tpvExhMalubi_GT;
            $arrg_tpvExhMalubi[2] = $tpvExhMalubi_HN;
            $arrg_tpvExhMalubi[3] = $tpvExhMalubi_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhRetira = array();
            $arrg_tpvExhRetira[0] = $tpvExhRetira_SV;
            $arrg_tpvExhRetira[1] = $tpvExhRetira_GT;
            $arrg_tpvExhRetira[2] = $tpvExhRetira_HN;
            $arrg_tpvExhRetira[3] = $tpvExhRetira_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhVisyAc = array();
            $arrg_tpvExhVisyAc[0] = $tpvExhVisyAc_SV;
            $arrg_tpvExhVisyAc[1] = $tpvExhVisyAc_GT;
            $arrg_tpvExhVisyAc[2] = $tpvExhVisyAc_HN;
            $arrg_tpvExhVisyAc[3] = $tpvExhVisyAc_RDO;
            /*------------------------------------------------------------------------------*/
            $arrg_tpvExhNecesr = array();
            $arrg_tpvExhNecesr[0] = $tpvExhNecesr_SV;
            $arrg_tpvExhNecesr[1] = $tpvExhNecesr_GT;
            $arrg_tpvExhNecesr[2] = $tpvExhNecesr_HN;
            $arrg_tpvExhNecesr[3] = $tpvExhNecesr_RDO;
            /*------------------------------------------------------------------------------*/

            $arrg_FullTotales= array();

            $full_tpvExhqtiene = $tpvExhqtiene_SV + $tpvExhqtiene_GT + $tpvExhqtiene_HN + $tpvExhqtiene_RDO;
            $full_tpvExhDesech = $tpvExhDesech_SV + $tpvExhDesech_GT + $tpvExhDesech_HN + $tpvExhDesech_RDO;
            $full_tpvExhInvadi = $tpvExhInvadi_SV + $tpvExhInvadi_GT + $tpvExhInvadi_HN + $tpvExhInvadi_RDO;
            $full_tpvExhMalubi = $tpvExhMalubi_SV + $tpvExhMalubi_GT + $tpvExhMalubi_HN + $tpvExhMalubi_RDO;
            $full_tpvExhRetira = $tpvExhRetira_SV + $tpvExhRetira_GT + $tpvExhRetira_HN + $tpvExhRetira_RDO;
            $full_tpvExhVisyAc = $tpvExhVisyAc_SV + $tpvExhVisyAc_GT + $tpvExhVisyAc_HN + $tpvExhVisyAc_RDO;
            $full_tpvExhNecesr = $tpvExhNecesr_SV + $tpvExhNecesr_GT + $tpvExhNecesr_HN + $tpvExhNecesr_RDO;

            $arrg_FullTotales[0] = $full_tpvExhqtiene;
            $arrg_FullTotales[1] = $full_tpvExhDesech;
            $arrg_FullTotales[2] = $full_tpvExhInvadi;
            $arrg_FullTotales[3] = $full_tpvExhMalubi;
            $arrg_FullTotales[4] = $full_tpvExhRetira;
            $arrg_FullTotales[5] = $full_tpvExhVisyAc;
            $arrg_FullTotales[6] = $full_tpvExhNecesr;

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralUnoTObserv' => $arrg_ls_GenralUnoTabla,
                'tpvExhquetiene' => $arrg_tpvExhqtiene,
                'tpvExhdesechados' => $arrg_tpvExhDesech,
                'tpvExhinvadidos' => $arrg_tpvExhInvadi,
                'tpvExhmalubicados' => $arrg_tpvExhMalubi,
                'tpvExhretirados' => $arrg_tpvExhRetira,
                'tpvExhvisibleyacce' => $arrg_tpvExhVisyAc,
                'tpvExhnecesitar' => $arrg_tpvExhNecesr,
                'FullTotales' => $arrg_FullTotales
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }


    function Lista_TablaPorTipoObseZoomUno(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RporTipoAObservacionZOOMUNO($param_busqueda);
            $arrg_ls_GenralUnoTablaZUNO = array();
            $cUno = 0;
            $tpvExhqtiene = 0;
            $tpvExhDesech = 0;
            $tpvExhInvadi = 0;
            $tpvExhMalubi = 0;
            $tpvExhRetira = 0;
            $tpvExhVisyAc = 0;
            $tpvExhNecesr = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {

                $tpvExhqtiene += $k->exhquetiene;
                $tpvExhDesech += $k->exhdesechadooguardado;
                $tpvExhInvadi += $k->exhinvadido;
                $tpvExhMalubi += $k->exhmalubicado;
                $tpvExhRetira += $k->exhretirados;
                $tpvExhVisyAc += $k->exhvisibleyaccesible;
                $tpvExhNecesr += $k->exhnecesitareparacion;

                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhquetiene'] = $k->exhquetiene;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhdesechados'] = $k->exhdesechadooguardado;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhinvadido'] = $k->exhinvadido;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhmalubicado'] = $k->exhmalubicado;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhretirado'] = $k->exhretirados;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhvisibles'] = $k->exhvisibleyaccesible;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['exhnecesitare'] = $k->exhnecesitareparacion;
                $cUno++;
            }

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralCuatroZoomUno' => $arrg_ls_GenralUnoTablaZUNO,
                'tpvExhquetiene' => $tpvExhqtiene,
                'tpvExhdesechados' => $tpvExhDesech,
                'tpvExhinvadidos' => $tpvExhInvadi,
                'tpvExhmalubicados' => $tpvExhMalubi,
                'tpvExhretirados' => $tpvExhRetira,
                'tpvExhvisibleyacce' => $tpvExhVisyAc,
                'tpvExhnecesitar' => $tpvExhNecesr
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    function Lista_TablaPorTipoObseZoomDos(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_grupo = $this->input->post('grupo');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal,
                'grupo' => $param_grupo
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RporTipoAObservacionZOOMUDOS($param_busqueda);
            $arrg_ls_GenralUnoTablaZDOS = array();


            $cUno = 0;
            $tpvExhqtiene = 0;
            $tpvExhDesech = 0;
            $tpvExhInvadi = 0;
            $tpvExhMalubi = 0;
            $tpvExhRetira = 0;
            $tpvExhVisyAc = 0;
            $tpvExhNecesr = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {
    
                $tpvExhqtiene += $k->exhquetiene;
                $tpvExhDesech += $k->exhdesechadooguardado;
                $tpvExhInvadi += $k->exhinvadido;
                $tpvExhMalubi += $k->exhmalubicado;
                $tpvExhRetira += $k->exhretirados;
                $tpvExhVisyAc += $k->exhvisibleyaccesible;
                $tpvExhNecesr += $k->exhnecesitareparacion;

                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Ruta'] = $k->Nombre_Ruta;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhquetiene'] = $k->exhquetiene;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhdesechados'] = $k->exhdesechadooguardado;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhinvadido'] = $k->exhinvadido;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhmalubicado'] = $k->exhmalubicado;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhretirado'] = $k->exhretirados;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhvisibles'] = $k->exhvisibleyaccesible;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['exhnecesitare'] = $k->exhnecesitareparacion;
                $cUno++;
            }


            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralCuatroZoomDos' => $arrg_ls_GenralUnoTablaZDOS,
                'tpvExhquetiene' => $tpvExhqtiene,
                'tpvExhdesechados' => $tpvExhDesech,
                'tpvExhinvadidos' => $tpvExhInvadi,
                'tpvExhmalubicados' => $tpvExhMalubi,
                'tpvExhretirados' => $tpvExhRetira,
                'tpvExhvisibleyacce' => $tpvExhVisyAc,
                'tpvExhnecesitar' => $tpvExhNecesr
            ));


        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    /*--------------------------------------------------------------------------*/


    function Lista_TablaXNosePudoEntrar(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTabla = $this->Rexh->RporNosepudoEntrarExh($param_busqueda);
            $arrg_ls_GenralUnoTabla = array();

            $cUno = 0;
            $tCliNodejoEntr_SV = 0;$tCliNodejoEntr_GT = 0;$tCliNodejoEntr_HN = 0;$tCliNodejoEntr_RDO = 0;
            $tEstabaCerrado_SV = 0;$tEstabaCerrado_GT = 0;$tEstabaCerrado_HN = 0;$tEstabaCerrado_RDO = 0;
            $tNoseEncontroT_SV = 0;$tNoseEncontroT_GT = 0;$tNoseEncontroT_HN = 0;$tNoseEncontroT_RDO = 0;
            $full_tCliNodejoEntr = 0;$full_tEstabaCerrado = 0;$full_tNoseEncontroT = 0;
            foreach ($ls_GenralUnoTabla as $k) {

                if (strcmp($k->Nombre_Pais, "EL SALVADOR") == 0) {
                    $tCliNodejoEntr_SV += $k->clientenodejoentrar;
                    $tEstabaCerrado_SV += $k->estabacerrado;
                    $tNoseEncontroT_SV += $k->noseencontrotienda;
                }elseif(strcmp($k->Nombre_Pais, "GUATEMALA") == 0){
                    $tCliNodejoEntr_GT += $k->clientenodejoentrar;
                    $tEstabaCerrado_GT += $k->estabacerrado;
                    $tNoseEncontroT_GT += $k->noseencontrotienda;
                }elseif(strcmp($k->Nombre_Pais, "HONDURAS") == 0){
                    $tCliNodejoEntr_HN += $k->clientenodejoentrar;
                    $tEstabaCerrado_HN += $k->estabacerrado;
                    $tNoseEncontroT_HN += $k->noseencontrotienda;
                }elseif(strcmp($k->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                    $tCliNodejoEntr_RDO += $k->clientenodejoentrar;
                    $tEstabaCerrado_RDO += $k->estabacerrado;
                    $tNoseEncontroT_RDO += $k->noseencontrotienda;
                }

                $arrg_ls_GenralUnoTabla[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTabla[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTabla[$cUno]['CliNoEntrar'] = $k->clientenodejoentrar;
                $arrg_ls_GenralUnoTabla[$cUno]['CerradoTienda'] = $k->estabacerrado;
                $arrg_ls_GenralUnoTabla[$cUno]['NoseEncontroT'] = $k->noseencontrotienda;
                
                $cUno++;
            }

            /*------------------------------------------------------------------------------*/
            $tCliNodejoEntr = array();
            $tCliNodejoEntr[0] = $tCliNodejoEntr_SV;
            $tCliNodejoEntr[1] = $tCliNodejoEntr_GT;
            $tCliNodejoEntr[2] = $tCliNodejoEntr_HN;
            $tCliNodejoEntr[3] = $tCliNodejoEntr_RDO;
            /*------------------------------------------------------------------------------*/
            $tEstabaCerrado = array();
            $tEstabaCerrado[0] = $tEstabaCerrado_SV;
            $tEstabaCerrado[1] = $tEstabaCerrado_GT;
            $tEstabaCerrado[2] = $tEstabaCerrado_HN;
            $tEstabaCerrado[3] = $tEstabaCerrado_RDO;
            /*------------------------------------------------------------------------------*/
            $tNoseEncontroT= array();
            $tNoseEncontroT[0] = $tNoseEncontroT_SV;
            $tNoseEncontroT[1] = $tNoseEncontroT_GT;
            $tNoseEncontroT[2] = $tNoseEncontroT_HN;
            $tNoseEncontroT[3] = $tNoseEncontroT_RDO;
            /*------------------------------------------------------------------------------*/

            $arrg_FullTotales= array();

            $full_tCliNodejoEntr = $tCliNodejoEntr_SV + $tCliNodejoEntr_GT + $tCliNodejoEntr_HN + $tCliNodejoEntr_RDO;
            $full_tEstabaCerrado = $tEstabaCerrado_SV + $tEstabaCerrado_GT + $tEstabaCerrado_HN + $tEstabaCerrado_RDO;
            $full_tNoseEncontroT = $tNoseEncontroT_SV + $tNoseEncontroT_GT + $tNoseEncontroT_HN + $tNoseEncontroT_RDO;

            $arrg_FullTotales[0] = $full_tCliNodejoEntr;
            $arrg_FullTotales[1] = $full_tEstabaCerrado;
            $arrg_FullTotales[2] = $full_tNoseEncontroT;

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralUnoNoseEntrar' => $arrg_ls_GenralUnoTabla,
                'tCliNoEntrar' => $tCliNodejoEntr,
                'tEstabaCerrado' => $tEstabaCerrado,
                'tNoseEncontroCli' => $tNoseEncontroT,
                'FullTotales' => $arrg_FullTotales
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }



    function Lista_TablaXNosePudoEntrarZoomUno(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RporNosepudoEntrarExhZOOMUNO($param_busqueda);
            $arrg_ls_GenralUnoTablaZUNO = array();
            $cUno = 0;
            $tCliNodejoEntr = 0;$tEstabaCerrado = 0;$tNoseEncontroT = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {

                $tCliNodejoEntr += $k->clientenodejoentrar;
                $tEstabaCerrado += $k->estabacerrado;
                $tNoseEncontroT += $k->noseencontrotienda;

                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['CliNoEntrar'] = $k->clientenodejoentrar;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['CerradoTienda'] = $k->estabacerrado;
                $arrg_ls_GenralUnoTablaZUNO[$cUno]['NoseEncontroT'] = $k->noseencontrotienda;
                $cUno++;
            }

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralCincoZoomUno' => $arrg_ls_GenralUnoTablaZUNO,
                'tCliNoEntrar' => $tCliNodejoEntr,
                'tEstabaCerrado' => $tEstabaCerrado,
                'tNoseEncontroCli' => $tNoseEncontroT
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }

    function Lista_TablaXNosePudoEntrarZoomDos(){
        if($this->input->is_ajax_request()){

            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_grupo = $this->input->post('grupo');
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal,
                'grupo' => $param_grupo
            );

            $ls_GenralUnoTablazoomUNO = $this->Rexh->RporNosepudoEntrarExhZOOMUDOS($param_busqueda);
            $arrg_ls_GenralUnoTablaZDOS = array();
            $cUno = 0;
            $tCliNodejoEntr = 0;$tEstabaCerrado = 0;$tNoseEncontroT = 0;
            foreach ($ls_GenralUnoTablazoomUNO as $k) {
    
                $tCliNodejoEntr += $k->clientenodejoentrar;
                $tEstabaCerrado += $k->estabacerrado;
                $tNoseEncontroT += $k->noseencontrotienda;

                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Ruta'] = $k->Nombre_Ruta;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Nombre_Pais'] = $k->Nombre_Pais;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Division'] = $k->Division;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['Grupo'] = $k->Grupo;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['CliNoEntrar'] = $k->clientenodejoentrar;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['CerradoTienda'] = $k->estabacerrado;
                $arrg_ls_GenralUnoTablaZDOS[$cUno]['NoseEncontroT'] = $k->noseencontrotienda;
                $cUno++;
            }


            echo json_encode(array(
                'rs'=> TRUE,
                'ls_GeneralCincoZoomDos' => $arrg_ls_GenralUnoTablaZDOS,
                'tCliNoEntrar' => $tCliNodejoEntr,
                'tEstabaCerrado' => $tEstabaCerrado,
                'tNoseEncontroCli' => $tNoseEncontroT
            ));


        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;    
        }
    }


    /*--------------------------------------------------------------------------*/
    function Lista_TablaCounRegistroXDia(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');

            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $fechaInicio=strtotime("2020-08-17");
            $fechaFin = strtotime("now");
            // $fechaFin = strtotime("2020-08-20");

            $fecha_arrg = '';
            $fecha_consulta = '';$contadorSI = 0;
            $arrg_original = array();
            $contadorN = 0;$cumplio = '';
            $arrg_fechaTabla = array();
            $arrg_ACUMUsumaTotal_SV = array();
            $arrg_ACUMUsumaTotal_GT = array();
            $arrg_ACUMUsumaTotal_HN = array();
            $arrg_ACUMUsumaTotal_RDO = array();
            $ee = 0;
            for($e=$fechaInicio; $e<=$fechaFin; $e+=86400){
                $arrg_fechaTabla[$ee] = date("d-m-Y", $e);
                $arrg_ACUMUsumaTotal_SV[$ee] = 0;
                $arrg_ACUMUsumaTotal_GT[$ee] = 0;
                $arrg_ACUMUsumaTotal_HN[$ee] = 0;
                $arrg_ACUMUsumaTotal_RDO[$ee] = 0;
                $ee++;
            }

            $Paso_UNO = $this->Rexh->RporEncuestadosXDias_QUEpd($param_busqueda);
            $Paso_TOTAL = $this->Rexh->RporEncuestadosXDias_QUEpdTotal($param_busqueda);
            $Paso_TOTAL_PAIS = $this->Rexh->RporEncuestadosXDias_QUEpdTotalPais($param_busqueda);


            $arrg_SOLO_Pais = array();
            $arrg_sumaTotal_SV = array();
            $arrg_sumaTotal_GT = array();
            $arrg_sumaTotal_HN = array();
            $arrg_sumaTotal_RDO = array();
            $contaDivision = 0;
            foreach ($Paso_UNO as $key) {
                $Paso_DOS = $this->Rexh->RporEncuestadosXDias($key->Division,$param_busqueda);
                $cantidad_array = count($Paso_DOS);
                $cantidad_array = $cantidad_array - 1;
                // foreach ($Paso_DOS as $keyd) {

                $contadorSI = 0;$contarDiviSI = 0;
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                        $fecha_arrg = strtotime(date("d-m-Y", $i));
                        
                        if($contadorSI > $cantidad_array){
                            $fecha_consulta = 0;
                        }else{
                            $fecha_consulta = strtotime($Paso_DOS[$contadorSI]->fecha);
                        }

                        if($fecha_arrg === $fecha_consulta){
                            $arrg_original[$contadorN]['Nombre_Pais'] = $key->Nombre_Pais;
                            $arrg_original[$contadorN]['Division'] = $key->Division;
                            $arrg_original[$contadorN]['fecha'] =  date("d-m-Y",$fecha_consulta);
                            $arrg_original[$contadorN]['totalpdv'] = $Paso_DOS[$contadorSI]->totalactualizados;

                            if (strcmp($key->Nombre_Pais, "EL SALVADOR") == 0) {
                                $arrg_sumaTotal_SV[$contaDivision][$contarDiviSI] = $Paso_DOS[$contadorSI]->totalactualizados;
                            }elseif(strcmp($key->Nombre_Pais, "GUATEMALA") == 0){
                                $arrg_sumaTotal_GT[$contaDivision][$contarDiviSI] = $Paso_DOS[$contadorSI]->totalactualizados;
                            }elseif(strcmp($key->Nombre_Pais, "HONDURAS") == 0){
                                $arrg_sumaTotal_HN[$contaDivision][$contarDiviSI] = $Paso_DOS[$contadorSI]->totalactualizados;
                            }elseif(strcmp($key->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                                $arrg_sumaTotal_RDO[$contaDivision][$contarDiviSI] = $Paso_DOS[$contadorSI]->totalactualizados;
                            }
                            
                            $contadorSI++;
                        }else{
                            $arrg_original[$contadorN]['Nombre_Pais'] = $key->Nombre_Pais;
                            $arrg_original[$contadorN]['Division'] = $key->Division;
                            $arrg_original[$contadorN]['fecha'] =  date("d-m-Y", $i);
                            $arrg_original[$contadorN]['totalpdv'] = 0;

                            if (strcmp($key->Nombre_Pais, "EL SALVADOR") == 0) {
                                $arrg_sumaTotal_SV[$contaDivision][$contarDiviSI] = 0;
                            }elseif(strcmp($key->Nombre_Pais, "GUATEMALA") == 0){
                                $arrg_sumaTotal_GT[$contaDivision][$contarDiviSI] = 0;
                            }elseif(strcmp($key->Nombre_Pais, "HONDURAS") == 0){
                                $arrg_sumaTotal_HN[$contaDivision][$contarDiviSI] = 0;
                            }elseif(strcmp($key->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                                $arrg_sumaTotal_RDO[$contaDivision][$contarDiviSI] = 0;
                            }

                        }
                        $contadorN++;$contarDiviSI++;
                    }//FINA FOR
                    $contaDivision++;


                // }
            }

            /*TOTALIZANDO TOTALES POR FECHA Y PAIS Y DIVISION*/
            $index_divison = 0;$count_fechas = count($arrg_fechaTabla);
            $arrg_Total_General = array();
            foreach ($Paso_UNO as $kk) {

                if (strcmp($kk->Nombre_Pais, "EL SALVADOR") == 0) {
                    $count_Pais = count($arrg_sumaTotal_SV);
                    for ($u=0; $u < $count_fechas; $u++) { 
                       $arrg_ACUMUsumaTotal_SV[$u] = $arrg_ACUMUsumaTotal_SV[$u] + $arrg_sumaTotal_SV[$index_divison][$u];
                    }
                }elseif(strcmp($kk->Nombre_Pais, "GUATEMALA") == 0){
                    $count_Pais = count($arrg_sumaTotal_SV);
                    for ($u=0; $u < $count_fechas; $u++) { 
                       $arrg_ACUMUsumaTotal_GT[$u] = $arrg_ACUMUsumaTotal_GT[$u] + $arrg_sumaTotal_GT[$index_divison][$u];
                    }
                }elseif(strcmp($kk->Nombre_Pais, "HONDURAS") == 0){
                    $count_Pais = count($arrg_sumaTotal_SV);
                    for ($u=0; $u < $count_fechas; $u++) { 
                       $arrg_ACUMUsumaTotal_HN[$u] = $arrg_ACUMUsumaTotal_HN[$u] + $arrg_sumaTotal_HN[$index_divison][$u];
                    }
                }elseif(strcmp($kk->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                    $count_Pais = count($arrg_sumaTotal_SV);
                    for ($u=0; $u < $count_fechas; $u++) { 
                       $arrg_ACUMUsumaTotal_RDO[$u] = $arrg_ACUMUsumaTotal_RDO[$u] + $arrg_sumaTotal_RDO[$index_divison][$u];
                    }
                }

                $index_divison++;
            }

            $TotalCAM = 0; $arrg_totalPais = array();$tpp = 0;
            foreach ($Paso_TOTAL_PAIS as $tp) {
                $TotalCAM+=$tp->totalactualizados;
                if (strcmp($tp->Nombre_Pais, "EL SALVADOR") == 0) {
                    $arrg_totalPais['ELSALVADOR'] = $tp->totalactualizados;
                }elseif(strcmp($tp->Nombre_Pais, "GUATEMALA") == 0){
                    $arrg_totalPais['GUATEMALA'] = $tp->totalactualizados;
                }elseif(strcmp($tp->Nombre_Pais, "HONDURAS") == 0){
                    $arrg_totalPais['HONDURAS'] = $tp->totalactualizados;
                }elseif(strcmp($tp->Nombre_Pais, "REPUBLICA DOMINICANA") == 0){
                    $arrg_totalPais['REPUBLICADOMINICANA'] = $tp->totalactualizados;
                }
            }

            $Paso_TRES = $this->Rexh->RporEncuestadosXDiasTotalGeneral($param_busqueda);
            $cantidad_arrayT = count($Paso_TRES);
            $cantidad_arrayT = $cantidad_arrayT - 1;
            // foreach ($Paso_DOS as $keyd) {
            $fecha_arrg = '';
            $contadorSIT = 0;$contarDiviSIT = 0;$contadorNT = 0;
            for($iT=$fechaInicio; $iT<=$fechaFin; $iT+=86400){
                $fecha_arrg = strtotime(date("d-m-Y", $iT));
                if($contadorSIT > $cantidad_arrayT){
                    $fecha_consultaT = 0;
                }else{
                    $fecha_consultaT = strtotime($Paso_TRES[$contadorSIT]->fecha);
                }
                if($fecha_arrg === $fecha_consultaT){
                    $arrg_originalTOTAL[$contadorNT]['fecha'] =  date("d-m-Y",$fecha_consultaT);
                    $arrg_originalTOTAL[$contadorNT]['totalpdv'] = $Paso_TRES[$contadorSIT]->totalactualizados;
                    $contadorSIT++;
                }else{
                    $arrg_originalTOTAL[$contadorNT]['fecha'] =  date("d-m-Y", $iT);
                    $arrg_originalTOTAL[$contadorNT]['totalpdv'] = 0;
                }
                $contadorNT++;
            }//FINA FOR


            echo json_encode(array(
                'rs'=> TRUE,
                'ls_CuenXdia' => $arrg_original,
                'ls_Fechas' => $arrg_fechaTabla,
                'ls_Divisiones' => $Paso_UNO,
                'ls_TotalSV' => $arrg_ACUMUsumaTotal_SV,
                'ls_TotalGT' => $arrg_ACUMUsumaTotal_GT,
                'ls_TotalHN' => $arrg_ACUMUsumaTotal_HN,
                'ls_TotalRDO' => $arrg_ACUMUsumaTotal_RDO,
                'ls_TotalPorDivison' => $Paso_TOTAL,
                'ls_TotalPorPais' => $arrg_totalPais,
                'ls_TotalPorDia' => $arrg_originalTOTAL,
                'TotalCAM' => $TotalCAM
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }

    function Lista_TablaCounRegistroXDiaZoomUno(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');

            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $fechaInicio=strtotime("2020-08-17");
            $fechaFin = strtotime("now");
            // $fechaFin = strtotime("2020-08-20");

            $fecha_arrg = '';
            $fecha_consulta = '';$contadorSI = 0;
            $arrg_original = array();
            $contadorN = 0;$cumplio = '';
            $arrg_fechaTabla = array();
            $arrg_ACUMUsumaTotal_SV = array();
            $arrg_ACUMUsumaTotal_GT = array();
            $arrg_ACUMUsumaTotal_HN = array();
            $arrg_ACUMUsumaTotal_RDO = array();
            $ee = 0;
            for($e=$fechaInicio; $e<=$fechaFin; $e+=86400){
                $arrg_fechaTabla[$ee] = date("d-m-Y", $e);
                $arrg_ACUMUsumaTotal_SV[$ee] = 0;
                $arrg_ACUMUsumaTotal_GT[$ee] = 0;
                $arrg_ACUMUsumaTotal_HN[$ee] = 0;
                $arrg_ACUMUsumaTotal_RDO[$ee] = 0;
                $ee++;
            }

            $Paso_UNO = $this->Rexh->RporEncuestadosXDias_QUEpd_ZOOUNO($param_busqueda);
            $Paso_CUATRO = $this->Rexh->RporEncuestadosXDiasTotalxGrupo_ZOOMUNO($param_busqueda);
            $Paso_TOTAL_Division = $this->Rexh->RporEncuestadosXDiasTotalDivision_ZOOMUNO($param_busqueda);
            
            $arrg_SOLO_Pais = array();
            $arrg_sumaTotal_SV = array();
            $arrg_sumaTotal_GT = array();
            $arrg_sumaTotal_HN = array();
            $arrg_sumaTotal_RDO = array();
            $contaDivision = 0;
            foreach ($Paso_UNO as $key) {
                $Paso_DOS = $this->Rexh->RporEncuestadosXDias_ZOOMUNO($key->Grupo,$key->Division,$param_busqueda);
                $cantidad_array = count($Paso_DOS);
                $cantidad_array = $cantidad_array - 1;
                // foreach ($Paso_DOS as $keyd) {

                $contadorSI = 0;$contarDiviSI = 0;
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                        $fecha_arrg = strtotime(date("d-m-Y", $i));
                        
                        if($contadorSI > $cantidad_array){
                            $fecha_consulta = 0;
                        }else{
                            $fecha_consulta = strtotime($Paso_DOS[$contadorSI]->fecha);
                        }

                        if($fecha_arrg === $fecha_consulta){
                            $arrg_original[$contadorN]['Nombre_Pais'] = $key->Nombre_Pais;
                            $arrg_original[$contadorN]['Division'] = $key->Division;
                            $arrg_original[$contadorN]['Grupo'] = $key->Grupo;
                            $arrg_original[$contadorN]['fecha'] =  date("d-m-Y",$fecha_consulta);
                            $arrg_original[$contadorN]['totalpdv'] = $Paso_DOS[$contadorSI]->totalactualizados;
                            $arrg_sumaTotal_SV[$contaDivision][$contarDiviSI] = $Paso_DOS[$contadorSI]->totalactualizados;
                            $contadorSI++;
                        }else{
                            $arrg_original[$contadorN]['Nombre_Pais'] = $key->Nombre_Pais;
                            $arrg_original[$contadorN]['Division'] = $key->Division;
                            $arrg_original[$contadorN]['Grupo'] = $key->Grupo;
                            $arrg_original[$contadorN]['fecha'] =  date("d-m-Y", $i);
                            $arrg_original[$contadorN]['totalpdv'] = 0;
                            $arrg_sumaTotal_SV[$contaDivision][$contarDiviSI] = 0;
                        }
                        $contadorN++;$contarDiviSI++;
                    }//FINA FOR
                    $contaDivision++;


                // }
            }

            /*TOTALIZANDO TOTALES POR FECHA Y PAIS Y DIVISION*/
            $index_divison = 0;$count_fechas = count($arrg_fechaTabla);
            foreach ($Paso_UNO as $kk) {
                $count_Pais = count($arrg_sumaTotal_SV);
                for ($u=0; $u < $count_fechas; $u++) { 
                    $arrg_ACUMUsumaTotal_SV[$u] = $arrg_ACUMUsumaTotal_SV[$u] + $arrg_sumaTotal_SV[$index_divison][$u];
                }
                $index_divison++;

            }  

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_CuenXdia' => $arrg_original,
                'ls_Fechas' => $arrg_fechaTabla,
                'ls_Divisiones' => $Paso_UNO,
                'ls_TotalSV' => $arrg_ACUMUsumaTotal_SV,
                'ls_TotalGT' => $arrg_ACUMUsumaTotal_GT,
                'ls_TotalHN' => $arrg_ACUMUsumaTotal_HN,
                'ls_TotalRDO' => $arrg_ACUMUsumaTotal_RDO,
                'ls_TotalGrupo' => $Paso_CUATRO,
                'ls_TotalDivision' => $Paso_TOTAL_Division
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }


    function Lista_TablaCounRegistroXDiaZoomDos(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('division');
            $param_canal = $this->input->post('filtrocanales');
            $param_grupo = $this->input->post('grupo');
            
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal
            );

            $fechaInicio=strtotime("2020-08-17");
            $fechaFin = strtotime("now");
            // $fechaFin = strtotime("2020-08-20");

            $fecha_arrg = '';
            $fecha_consulta = '';$contadorSI = 0;
            $arrg_original = array();
            $contadorN = 0;$cumplio = '';
            $arrg_fechaTabla = array();
            $arrg_ACUMUsumaTotal_SV = array();
            $arrg_ACUMUsumaTotal_GT = array();
            $arrg_ACUMUsumaTotal_HN = array();
            $arrg_ACUMUsumaTotal_RDO = array();
            $ee = 0;
            for($e=$fechaInicio; $e<=$fechaFin; $e+=86400){
                $arrg_fechaTabla[$ee] = date("d-m-Y", $e);
                $arrg_ACUMUsumaTotal_SV[$ee] = 0;
                $arrg_ACUMUsumaTotal_GT[$ee] = 0;
                $arrg_ACUMUsumaTotal_HN[$ee] = 0;
                $arrg_ACUMUsumaTotal_RDO[$ee] = 0;
                $ee++;
            }

            $Paso_UNO = $this->Rexh->RporEncuestadosXDias_QUEpd_ZOODOS($param_grupo,$param_division,$param_busqueda);
            $Paso_CUATRO = $this->Rexh->RporEncuestadosXDiasTOTALgene_ZOOMDOS($param_grupo,$param_division,$param_busqueda);
            $Paso_TOTAL_Division = $this->Rexh->RporEncuestadosXDiasTotalDivision_ZOOMDOS($param_grupo,$param_division,$param_busqueda);
            
            $arrg_SOLO_Pais = array();
            $arrg_sumaTotal_SV = array();
            $arrg_sumaTotal_GT = array();
            $arrg_sumaTotal_HN = array();
            $arrg_sumaTotal_RDO = array();
            $contaDivision = 0;
            foreach ($Paso_UNO as $key) {
                $Paso_DOS = $this->Rexh->RporEncuestadosXDias_ZOOMDOS($key->Grupo,$key->Division,$key->Nombre_Ruta,$param_busqueda);
                $cantidad_array = count($Paso_DOS);
                $cantidad_array = $cantidad_array - 1;
                // foreach ($Paso_DOS as $keyd) {

                $contadorSI = 0;$contarDiviSI = 0;
                    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                        $fecha_arrg = strtotime(date("d-m-Y", $i));
                        
                        if($contadorSI > $cantidad_array){
                            $fecha_consulta = 0;
                        }else{
                            $fecha_consulta = strtotime($Paso_DOS[$contadorSI]->fecha);
                        }

                        if($fecha_arrg === $fecha_consulta){
                            $arrg_original[$contadorN]['Nombre_Pais'] = $key->Nombre_Pais;
                            $arrg_original[$contadorN]['Division'] = $key->Division;
                            $arrg_original[$contadorN]['Grupo'] = $key->Grupo;
                            $arrg_original[$contadorN]['Nombre_Ruta'] = $key->Nombre_Ruta;
                            $arrg_original[$contadorN]['fecha'] =  date("d-m-Y",$fecha_consulta);
                            $arrg_original[$contadorN]['totalpdv'] = $Paso_DOS[$contadorSI]->totalactualizados;
                            $arrg_sumaTotal_SV[$contaDivision][$contarDiviSI] = $Paso_DOS[$contadorSI]->totalactualizados;
                            $contadorSI++;
                        }else{
                            $arrg_original[$contadorN]['Nombre_Pais'] = $key->Nombre_Pais;
                            $arrg_original[$contadorN]['Division'] = $key->Division;
                            $arrg_original[$contadorN]['Grupo'] = $key->Grupo;
                            $arrg_original[$contadorN]['Nombre_Ruta'] = $key->Nombre_Ruta;
                            $arrg_original[$contadorN]['fecha'] =  date("d-m-Y", $i);
                            $arrg_original[$contadorN]['totalpdv'] = 0;
                            $arrg_sumaTotal_SV[$contaDivision][$contarDiviSI] = 0;
                        }
                        $contadorN++;$contarDiviSI++;
                    }//FINA FOR
                    $contaDivision++;


                // }
            }

            /*TOTALIZANDO TOTALES POR FECHA Y PAIS Y DIVISION*/
            $index_divison = 0;$count_fechas = count($arrg_fechaTabla);
            foreach ($Paso_UNO as $kk) {
                $count_Pais = count($arrg_sumaTotal_SV);
                for ($u=0; $u < $count_fechas; $u++) { 
                    $arrg_ACUMUsumaTotal_SV[$u] = $arrg_ACUMUsumaTotal_SV[$u] + $arrg_sumaTotal_SV[$index_divison][$u];
                }
                $index_divison++;

            }

            echo json_encode(array(
                'rs'=> TRUE,
                'ls_CuenXdia' => $arrg_original,
                'ls_Fechas' => $arrg_fechaTabla,
                'ls_Divisiones' => $Paso_UNO,
                'ls_TotalSV' => $arrg_ACUMUsumaTotal_SV,
                'ls_TotalGT' => $arrg_ACUMUsumaTotal_GT,
                'ls_TotalHN' => $arrg_ACUMUsumaTotal_HN,
                'ls_TotalRDO' => $arrg_ACUMUsumaTotal_RDO,
                'ls_TotalGrupo' => $Paso_CUATRO,
                'ls_TotalDivision' => $Paso_TOTAL_Division
            ));

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }



    /*--------------------------------------------------------------------------*/

    function Filtros_Busquedas(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');
            $param_distritro = $this->input->post('filtrodistritos');

            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal,
                'grupo' => $param_distritro
            );
            $pais_seleccionxdivision = '';
            if(!empty($param_division)){
                $dintermedio = $this->Rexh->DB_divisionInterme($param_busqueda);
                $pais_seleccionxdivision = '';
                foreach ($dintermedio as $key) {
                    $pais_seleccionxdivision = $key->Id_Pais;
                }
            }else{
                $pais_seleccionxdivision = '';
            }

            $ls_FiltroPais = $this->Rexh->DB_FiltroPais($param_busqueda);
            $ls_FiltroDivisiones = $this->Rexh->DB_FiltroDivisiones($param_busqueda,$pais_seleccionxdivision);
            $ls_FiltroCanal = $this->Rexh->DB_FiltroCanal($param_busqueda);
            $ls_FiltroDistrito = $this->Rexh->DB_FiltroDistrito($param_busqueda);
            $ls_FiltroRuta = $this->Rexh->DB_FiltroRuta($param_busqueda);


            echo json_encode(array(
                'rs'=> TRUE,
                'ls_filtropais' => $ls_FiltroPais,
                'ls_filtrodivisiones' => $ls_FiltroDivisiones,
                'ls_filtrocanal' => $ls_FiltroCanal,
                'ls_filtroDistrito' => $ls_FiltroDistrito,
                'ls_filtroRuta' => $ls_FiltroRuta
            ));



        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }

    function Filtros_Busquedas_RUTA(){
        if($this->input->is_ajax_request()){

            $param_pais = '';
            $param_pais = $this->input->post('filtropais');
            $param_division = $this->input->post('filtrodivision');
            $param_canal = $this->input->post('filtrocanales');
            $param_distritro = $this->input->post('filtrodistritos');
            
            $param_busqueda = array(
                'idpais' => $param_pais,
                'division' => $param_division,
                'canal'=> $param_canal,
                'grupo' => $param_distritro
            );


            $ls_FiltroRuta = $this->Rexh->DB_FiltroRuta($param_busqueda);


            echo json_encode(array(
                'rs'=> TRUE,
                'ls_filtroRuta' => $ls_FiltroRuta
            ));


        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }





    function Lista_TablaClteCensados(){
        if($this->input->is_ajax_request()){

            if(!empty($this->input->post('page'))){
                $limit = 10;
                $adjacent = 1;
                $page = $this->input->post('page');
                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }

                $param_pais = '';
                $param_pais = $this->input->post('filtropais');
                $param_division = $this->input->post('filtrodivision');
                $param_canal = $this->input->post('filtrocanales');
                $param_distritro = $this->input->post('filtrodistritos');
                $param_ruta = $this->input->post('filtrorutas');
                $param_codigo = $this->input->post('filtrocodigos');
                $param_busqueda = array(
                    'idpais' => $param_pais,
                    'division' => $param_division,
                    'canal'=> $param_canal,
                    'grupo' => $param_distritro,
                    'ruta' => $param_ruta,
                    'codigo' => $param_codigo
                );




                $Total_clientes = $this->Rexh->list_cltesC_Total($param_busqueda);
                $Toto = $Total_clientes->totalresultados;
                $ls_clte_censados = $this->Rexh->list_cltesC($start,$limit,$param_busqueda);$arrg_lista_tabla = array();
                $paginado_ls_clteCensados = $this->pagination_TablaClteCensados($limit,$adjacent,$Toto,$page);
                $va = '';$x = 0;


                foreach ($ls_clte_censados as $val){
                    $arrg_lista_tabla[$x]['Id_Cliente'] = $val->Id_Cliente;
                    $arrg_lista_tabla[$x]['Nombre_Ruta'] = $val->Nombre_Ruta;
                    $arrg_lista_tabla[$x]['CodigoCliente'] = $val->Codigo;
                    $arrg_lista_tabla[$x]['NombreCliente'] = $val->NombreCliente;
                    $arrg_lista_tabla[$x]['DireccionCliente'] = $val->DireccionCliente;
                    $arrg_lista_tabla[$x]['ContactoCliente'] = $val->ContactoCliente;
                    $arrg_lista_tabla[$x]['Nombre_Pais'] = $val->Nombre_Pais;
                    $arrg_lista_tabla[$x]['Division'] = $val->Division;
                    $arrg_lista_tabla[$x]['Canal'] = $val->Canal;
                    $arrg_lista_tabla[$x]['Grupo'] = $val->Grupo;
                

                    //EXISTE FOTO ???
                    $fotourl = '';
                    $fotourl = strval($val->FotoObservacion);


                    if ( strcmp($fotourl, "NULL") == 0 || empty($fotourl) ){
                        $arrg_lista_tabla[$x]['FotoObservacion'] = 0;
                    }else{
                        if (!file_exists("../img_server/".$fotourl)) {
                            $arrg_lista_tabla[$x]['FotoObservacion'] = 0;
                        } else {
                            $arrg_lista_tabla[$x]['FotoObservacion'] = $fotourl;
                            
                        }
                    }
                    $x++;
                    $fotourl = '';
                }


                echo json_encode(array(
                    'rs'=> TRUE,
                    'ls_listaCtlCensados' => $arrg_lista_tabla,
                    'paginado' => $paginado_ls_clteCensados,
                    'totalCli' => $Toto
                ));

            }else{
                $resp = array(
                    'rs' => FALSE,
                    'info' => 'Por favor salir y volver a ingresar a la plataforma...'
                );
            }

        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }

    function Expediente_EncuestaXclte(){
        if($this->input->is_ajax_request()){

            if(!empty($this->input->post('idx_cliente'))){

                $idx_cliente = $this->input->post('idx_cliente');
                $Select_clte = $this->Rexh->CLietenSeleccionado($idx_cliente);$arrg_xCliSeleccionado = array();$x = 0;

                $Select_ExhXcli_tiene = $this->Rexh->ExhibidorXCliSeleccionado($idx_cliente,1);$arrg_QTiene = array();$eq = 0;
                $Select_ExhXcli_devueltos = $this->Rexh->ExhibidorXCliSeleccionado($idx_cliente,2);$arrg_devu = array();$de = 0;
                $Select_ExhXcli_nuevos = $this->Rexh->ExhibidorXCliSeleccionado($idx_cliente,3);
                // $Select_ExhXcli_notiene = $this->Rexh->ExhibidorXCliSeleccionado($idx_cliente,4);

                foreach ($Select_clte as $val){
                    $arrg_xCliSeleccionado['Nombre_Ruta'] = $val->Nombre_Ruta;
                    $arrg_xCliSeleccionado['CodigoCliente'] = $val->CodigoCliente;
                    $arrg_xCliSeleccionado['NombreCliente'] = $val->NombreCliente;
                    $arrg_xCliSeleccionado['DireccionCliente'] = $val->DireccionCliente;
                    $arrg_xCliSeleccionado['ContactoCliente'] = $val->ContactoCliente;
                    $arrg_xCliSeleccionado['Telefono'] = $val->Telefono;
                    $arrg_xCliSeleccionado['Nombre_Pais'] = $val->Nombre_Pais;
                    $arrg_xCliSeleccionado['Canal'] = $val->Canal;
                    $arrg_xCliSeleccionado['Division'] = $val->Division;
                    $arrg_xCliSeleccionado['LatitudObservacion'] = $val->LatitudObservacion;
                    $arrg_xCliSeleccionado['LongitudObservacion'] = $val->LongitudObservacion;
                    $arrg_xCliSeleccionado['Latitud'] = $val->Latitud;
                    $arrg_xCliSeleccionado['Longitud'] = $val->Longitud;


                    //EXISTE FOTO ???
                
                    $fotourl = '';
                    $fotourl = strval($val->FotoObservacion);

                    if ( strcmp($fotourl, "NULL") == 0 || empty($fotourl) ){
                        $arrg_xCliSeleccionado['FotoObservacion'] = 0;
                    }else{
                        if (!file_exists("../img_server/".$fotourl)) {
                            $arrg_xCliSeleccionado['FotoObservacion'] = 0;
                        } else {
                            $arrg_xCliSeleccionado['FotoObservacion'] = $fotourl;
                        }
                    }

                    $x++;
                    $fotourl = '';
                }
                /*<<<<<<<<<<<<<<<<<<OBTENER OBSERVACION EXHIBIDOR DEVUELTO>>>>>>>>>>>>>>>>>*/
                foreach ($Select_ExhXcli_tiene as $vqt){
                    $arrg_QTiene[$eq]['SKU_Exhibidor'] = $vqt->SKU_Exhibidor;
                    $arrg_QTiene[$eq]['RespuestaObservacion'] = $vqt->RespuestaObservacion;
                    $eq++;
                }
                $arrg_QTiene = (object) $arrg_QTiene;
                foreach ($Select_ExhXcli_devueltos as $vde){
                    foreach ($arrg_QTiene as $vqtd) {
                        if($vde->SKU_Exhibidor == $vqtd["SKU_Exhibidor"]){
                            $arrg_devu[$de]['RespuestaObservacion'] = $vqtd["RespuestaObservacion"];
                        }
                    }
                    $arrg_devu[$de]['SKU_Exhibidor'] = $vde->SKU_Exhibidor;
                    $arrg_devu[$de]['NombreExhibidor'] = $vde->NombreExhibidor;
                    $de++;
                }
                echo json_encode(array(
                    'rs'=> TRUE,
                    'xcliente' => $arrg_xCliSeleccionado,
                    'xexhibidorqt' => $Select_ExhXcli_tiene,
                    'xexhibidorde' => $arrg_devu,
                    'xexhibidornu' => $Select_ExhXcli_nuevos
                ));

            }else{
                $resp = array(
                    'rs' => FALSE,
                    'info' => 'Por favor salir y volver a ingresar a la plataforma...'
                );
            }
        }else{
            $resp = array(
                'rs' => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
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
                        $pagination.= "<span class=\"current\">$counter</span>";
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
                            $pagination.= "<span class=\"current\">$counter</span>";
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
                            $pagination.= "<span class=\"current\">$counter</span>";
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
                            $pagination.= "<span class=\"current\">$counter</span>";
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