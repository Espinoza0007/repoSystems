<?php
ini_set('memory_limit', '-1');
// set_time_limit(999);
set_time_limit(0);
date_default_timezone_set('America/El_Salvador');
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
require 'phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Ctr_migrar extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->model('M_usuarios/Mdl_login','lg');
        $this->load->model('M_exhibidores/Mdl_exhibidores','exh');
        $this->load->model('M_clientes/Mdl_pruebas','k');
        $this->load->model('M_api_xamarin/Mdl_api_xamarin','xa');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }

	function index(){
        // $data['lista_ocupacion'] = $this->us->ocupaciones();
        // $data['lista_privilegio'] = $this->us->privilegios();
        $this->global['pageTitle'] = 'Generar Plantilla';
        $this->loadViews_nada('Reportes/V_migrar',$this->global);
  	}


    public function Lista_clientes_XA(){
        // if($this->input->is_ajax_request()){
            $list = $this->xa->List_Clientes();
            echo json_encode($list);
            var_dump($list);
        // }
    }



//editar_clientes
    function actualizar_clientes(){

        /*--------------------------------------------------------------------*/
        /*--------------------------------------------------------------------*/
        /*INICIO DE FUNCION PARA VALIDAR LOS REGISTROS DUPLICADOS EN LA BDD NUEVA*/
        /*--------------------------------------------------------------------*/


        /*DATA UPDATA CORREGIR IDS EN TABLAS */
            $arrg_Ste_id  = [];
            $arrg_Mar_id  = [];
            $arrg_Cti_id  = [];
            $arrg_Soa_id  = [];
            $arrg_Rec_id  = [];
            $arrg_Iti_id  = [];
            $arrg_Actc_Id = [];
            $arrg_Ac_Id   = [];
        /*DATA IDS A BORRAR EN LA TABLA CLIENTES */
            $arrg_clean_Cli_Ids = [];
            $arrg_desinfectados = [];
        /*-----------------------------------------------------*/
            $DISTRIBUIDORA = 'SAN MIGUEL';
            $CANAL         = 'MAYOREO';
        /*CONSULTANDO CLIENTES DUPLICADOS POR DISTRIBUIDORA Y CANAL*/
        $list_full_re = $this->cl->get_ls_repetidos_x_Ca_x_Dis($DISTRIBUIDORA,$CANAL);
        $C_full = count($list_full_re) - 1;
        for ($indiceHoja = 0; $indiceHoja <= $C_full; $indiceHoja++) {
            $list_rep = $this->cl->get_cli_repetidos($DISTRIBUIDORA,$list_full_re[$indiceHoja]->Cli_codigo);
            $ct_repe = count($list_rep);
            echo "Codigo Cliente ".$list_full_re[$indiceHoja]->Cli_codigo;
            echo ' <br>---------------------------------------------------<br>';
            $tbl_dpdt = '';$b_infectado = 0;
            if($ct_repe > 1){
                $ct_repe = $ct_repe - 1;
                for( $v = 1; $v <= $ct_repe; $v++ ){
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_status_exhibidores',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en  > 0 ){
                        $tbl_dpdt .= 'tbl_status_exhibidores,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Ste_id[] = [
                                'Ste_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Ste_token'  => $C_token->Ste_token
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_marcacion_impulso',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en > 0 ){
                        $tbl_dpdt .= 'tbl_marcacion_impulso,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Mar_id[] = [
                                'Mar_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Mar_token'  => $C_token->Mar_token
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_control_inventario',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en > 0 ){
                        $tbl_dpdt .= 'tbl_control_inventario,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Cti_id[] = [
                                'Cti_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Cti_token'  => $C_token->Cti_token
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_solicitud_apoyo',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en > 0 ){
                        $tbl_dpdt .= 'tbl_solicitud_apoyo,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Soa_id[] = [
                                'Soa_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Soa_token'  => $C_token->Soa_token
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_reclamo_pfn',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en > 0 ){
                        $tbl_dpdt .= 'tbl_reclamo_pfn,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Rec_id[] = [
                                'Rec_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Rec_token'  => $C_token->Rec_token
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_itinerario_impulso',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en > 0 ){
                        $tbl_dpdt .= 'tbl_itinerario_impulso,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Iti_id[] = [
                                'Iti_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Iti_Id'  => $C_token->Iti_Id
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_actualizacion_cliente',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en > 0 ){
                        $tbl_dpdt .= 'tbl_actualizacion_cliente,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Actc_Id[] = [
                                'Actc_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Actc_Id'  => $C_token->Actc_Id
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    $C_en = 0;$C_ls  = '';
                    $C_ls = $this->cl->count_ids_repetidos('tbl_acciones_competencia',$list_rep[$v]->Cli_Id);
                    // $C_en = $C_en->total;
                    $C_en = count($C_ls);
                    if( $C_en > 0 ){
                        $tbl_dpdt .= 'tbl_acciones_competencia,';
                        $b_infectado += 1;
                        foreach($C_ls as $C_token) {
                            $arrg_Ac_Id[] = [
                                'Ac_Cli_Id' => $list_rep[0]->Cli_Id,
                                'Ac_token'  => $C_token->Ac_token
                            ];
                        }
                    }
                    /*----------------------------------------------------------------------------------*/
                    if($b_infectado == 0){
                        $arrg_clean_Cli_Ids[] = [
                            'Cli_Id'          => $list_rep[$v]->Cli_Id,
                            'Cli_comentario'  => 'DEPURACION_MORTAL'
                        ];
                    }
                    if($b_infectado > 0){
                        $arrg_desinfectados[] = [
                            'Cli_Id'          => $list_rep[$v]->Cli_Id,
                            'Cli_comentario'  => 'DEPURACION_MORTAL'
                        ];
                    }
                    echo '<br>';
                    echo "Cliente Id => ".$list_rep[$v]->Cli_Id;
                    echo '<br>';
                    echo "Cantidad Infecciones => ".$b_infectado;
                    echo '<br>';
                    echo "Tablas infectadas => ".$tbl_dpdt;
                    echo '<br>';
                    echo '------------------------------------------------';
                    echo '<br>';
                    // $c_t_iftd+=$b_infectado;
                    $b_infectado = 0; $tbl_dpdt = '';
                }
            }
            echo ' ---------------------------------------------------<br>';
        }
        echo '<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        // echo "TOTAL REGISTROS INFECTADOS ".$c_t_iftd;
        $t_Ste = 0;$t_Mar = 0;$t_Cti = 0;$t_Soa = 0;$t_Rec = 0;$t_Iti = 0;$t_Actc = 0;$t_Ac = 0;
        $t_Ste = count($arrg_Ste_id);$t_Mar = count($arrg_Mar_id);
        $t_Cti = count($arrg_Cti_id);$t_Soa = count($arrg_Soa_id);
        $t_Rec = count($arrg_Rec_id);$t_Iti = count($arrg_Iti_id);
        $t_Actc = count($arrg_Actc_Id);$t_Ac = count($arrg_Ac_Id);
        echo '<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_status_exhibidores => ".count($arrg_Ste_id)."<br>";
        echo('<pre>');
        print_r($arrg_Ste_id);
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_marcacion_impulso => ".count($arrg_Mar_id )."<br>";
        echo('<pre>');
        print_r($arrg_Mar_id );
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_control_inventario => ".count($arrg_Cti_id)."<br>";
        echo('<pre>');
        print_r($arrg_Cti_id);
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_solicitud_apoyo => ".count($arrg_Soa_id)."<br>";
        echo('<pre>');
        print_r($arrg_Soa_id);
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_reclamo_pfn => ".count($arrg_Rec_id)."<br>";
        echo('<pre>');
        print_r($arrg_Rec_id);
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_itinerario_impulso => ".count($arrg_Iti_id)."<br>";
        echo('<pre>');
        print_r($arrg_Iti_id);
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_actualizacion_cliente => ".count($arrg_Actc_Id)."<br>";
        echo('<pre>');
        print_r($arrg_Actc_Id);
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo "<br><br>Correcciones Tabla tbl_acciones_competencia => ".count($arrg_Ac_Id)."<br>";
        echo('<pre>');
        print_r($arrg_Ac_Id);
        echo('</pre>');


        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo 'CLIENTES LIMPIOS (SIN RASTRO)';
        echo "<br><br>Cantidad => ".count($arrg_clean_Cli_Ids)."<br>";
        echo('<pre>');
        print_r($arrg_clean_Cli_Ids);
        echo('</pre>');
        echo '<br><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><br>';
        echo 'CLIENTES DESINFECTADOS';
        echo "<br><br>Cantidad => ".count($arrg_desinfectados)."<br>";
        echo('<pre>');
        print_r($arrg_desinfectados);
        echo('</pre>');
        /*-----------------------------------------------------------------------------*/
        if( $t_Ste > 0 ){
            echo "<br>Correcciones tbl_status_exhibidores -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_status_exhibidores',$arrg_Ste_id);
        }else{
            echo "<br>Sin correcciones en tbl_status_exhibidores --- OK";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_Mar > 0 ){
            echo "<br>Correcciones tbl_marcacion_impulso -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_marcacion_impulso',$arrg_Mar_id);
        }else{
            echo "<br>Sin correcciones en tbl_marcacion_impulso --- OK";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_Cti > 0 ){
            echo "<br>Correcciones tbl_control_inventario -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_control_inventario',$arrg_Cti_id);
        }else{
            echo "<br>Sin correcciones en tbl_control_inventario --- OK";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_Soa > 0 ){
            echo "<br>Correcciones tbl_solicitud_apoyo -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_solicitud_apoyo',$arrg_Soa_id);
        }else{
            echo "<br>Sin correcciones en tbl_solicitud_apoyo --- OK";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_Rec > 0 ){
            echo "<br>Correcciones tbl_reclamo_pfn -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_reclamo_pfn',$arrg_Rec_id);
        }else{
            echo "<br>Sin correcciones en tbl_reclamo_pfn --- OK";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_Iti > 0 ){
            echo "<br>Correcciones tbl_itinerario_impulso -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_itinerario_impulso',$arrg_Iti_id);
        }else{
            echo "<br>Sin correcciones en tbl_itinerario_impulso --- OK";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_Actc > 0 ){
            echo "<br>Correcciones tbl_actualizacion_cliente -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_actualizacion_cliente',$arrg_Actc_Id);
        }else{
            echo "<br>Sin correcciones en tbl_actualizacion_cliente --- OK";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_Ac > 0 ){
            echo "<br>Correcciones tbl_acciones_competencia -- OK<br>";
            echo $this->cl->Update_correccionCliIds('tbl_acciones_competencia',$arrg_Ac_Id);
        }else{
            echo "<br>Sin correcciones en tbl_acciones_competencia --- OK";
        }
        $t_cleans = 0;$t_desinfectados = 0;
        $t_cleans = count($arrg_clean_Cli_Ids);$t_desinfectados = count($arrg_desinfectados);
        if( $t_cleans > 0){
            echo "<br><br>Registros limpios, LISTOS PARA ELIMINAR-- OK<br>";
            echo $this->cl->Update_SicroCodClientes_DBB_N($arrg_clean_Cli_Ids);
        }else{
            echo "<br><br>Sin Datos para corregir (Registros Limpios).<br>";
        }
        /*-----------------------------------------------------------------------------*/
        if( $t_desinfectados > 0){
            echo "<br><br>Registros desinfectados, LISTOS PARA ELIMINAR-- OK<br>";
            echo $this->cl->Update_SicroCodClientes_DBB_N($arrg_desinfectados);
        }else{
            echo "<br><br>Sin Datos para corregir (Registros Limpios).<br>";
        }
        echo "<br><br>TOTAL DE CLIENTES VERIFICADOS ".count($list_full_re);

    }



///PARA LOS CLIENTES RECUPERADOS
//ingresar_clientes
    function ingresar_clientes(){
        $rutaArchivo = 'sincronizacion/CLIENTES_RECUPERADOS_7.2.23_15-08-2020.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $highestRow = $hojaActual->getHighestRow();
        $total_registros = $highestRow -1;
        echo "CANTIDAD DE REGISTROS => ".$total_registros;
        $SUCCESS_OK = 0;
        $ERRORES = 0;echo "<br>";
        $arrgaupdate = array();
        $contarrg = 0;


        $k=2;
        for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {

                    $cadena_dias = "L_0,M_0,I_0,J_0,V_0,S_0,D_0";
                    $cadenasinestado = "";
                    $input_diavisita = trim(strval($hojaActual->getCell("W".$indiceHoja)->getCalculatedValue()));
                    // echo $input_diavisita."<br>";
                    if(isset($input_diavisita)){
                        $seleccionado = "";
                        // foreach($input_diavisita as $seleccionado){
                            $seleccionado = $input_diavisita;
                            $cadenasinestado = substr($seleccionado,0,2);
                            $cadenasinestado = $cadenasinestado."0";
                            $cadena_dias = str_replace($cadenasinestado,$seleccionado,$cadena_dias);
                            // echo "awui ".$cadena_dias ."<br>";
                        // }
                    }else{
                        $cadena_dias = 'L_0,M_0,I_0,J_0,V_0,S_0,D_0';
                    }


                    $k++;

                    // echo $cadena_dias."<br>";
            $arrginsert[] = [
            'Id_cliente' => 0,
            'Codigo' => trim(strval($hojaActual->getCell("B".$indiceHoja)->getCalculatedValue())),
            'Nombre' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("C".$indiceHoja)->getCalculatedValue()))))),
            'Direccion' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("D".$indiceHoja)->getCalculatedValue()))))),
            'Id_Municipio' => desencriptar_cadena(trim(strval($hojaActual->getCell("E".$indiceHoja)->getCalculatedValue()))),
            'Telefono' => trim(strval($hojaActual->getCell("F".$indiceHoja)->getCalculatedValue())),
            'Contacto' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("G".$indiceHoja)->getCalculatedValue()))))),
            'Propietario' => mb_strtoupper(utf8_decode(quitar_acentos(trim(strval($hojaActual->getCell("G".$indiceHoja)->getCalculatedValue()))))),
            'Id_Tfacturacion' => trim(intval($hojaActual->getCell("I".$indiceHoja)->getCalculatedValue())),
            'Dui' => trim(strval($hojaActual->getCell("J".$indiceHoja)->getCalculatedValue())),
            'Numero_Registro' => trim(strval($hojaActual->getCell("K".$indiceHoja)->getCalculatedValue())),
            'Nit' => trim(strval($hojaActual->getCell("L".$indiceHoja)->getCalculatedValue())),
            'Id_Condicionc' => trim(intval($hojaActual->getCell("M".$indiceHoja)->getCalculatedValue())),
            'Dia_Cobro' => trim(strval($hojaActual->getCell("N".$indiceHoja)->getCalculatedValue())),
            'Monto_Credito' => trim(floatval($hojaActual->getCell("O".$indiceHoja)->getCalculatedValue())),
            'Id_Tcompra' => 1,
            'Id_Tcontribuyente' => 1,
            'Cantidad_Exhibidor' => 0,
            'Exhibiror_Uno' => '',
            'Exhibiror_Dos' => '',
            'Exhibiror_Tres' => '',
            'Orden_Visita' => trim(intval($hojaActual->getCell("V".$indiceHoja)->getCalculatedValue())),
            'Dias' => $cadena_dias,
            'RefUno' => 'NA',
            'Latitud' => trim(strval($hojaActual->getCell("Y".$indiceHoja)->getCalculatedValue())),
            'Longitud' => trim(strval($hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue())),
            'Id_Usuarios' => trim(intval($hojaActual->getCell("AA".$indiceHoja)->getCalculatedValue())),
            'Id_ref' => 0000001,
            'Id_Gironegocio' => desencriptar_cadena(trim(strval($hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue()))),
            'Foto_Negocio' => NULL,
            'Foto_Exhibidor' => NULL,
            'CompraS_B' => trim(floatval($hojaActual->getCell("AF".$indiceHoja)->getCalculatedValue())),
            'CompraS_D' => trim(floatval($hojaActual->getCell("AG".$indiceHoja)->getCalculatedValue())),
            'CompraS_Y' => trim(floatval($hojaActual->getCell("AH".$indiceHoja)->getCalculatedValue())),
            'CompraS_F' => trim(floatval($hojaActual->getCell("AI".$indiceHoja)->getCalculatedValue())),
            'Fecha_Ingreso' => '2020-08-15 10:58:00',
            'Estado' => 'N',
            'EstadoDescarga' => trim(intval($hojaActual->getCell("AL".$indiceHoja)->getCalculatedValue()))
            // 'quienresolucion' => 'SDV',
            // 'ActuExhibidor' => 'NO'
            ];
        }

        // $nombre_archivo = 'PLANTILLA_CLIENTES.xlsx';
        // $writer = new Xlsx($spreadsheet);
        // $writer->save($nombre_archivo);
        // $spreadsheet->disconnectWorksheets();
        // unset($spreadsheet);

        // var_dump($arrginsert);
        // echo $this->cl->ingresar_clientes_muchos($arrginsert);
    }



//prueba_piloto
function info_prueba_piloto(){

    $resultcanti = 0;
    $fecha_actual = date('Y_m_d_h_i_s');
    $fecha_actual_excel = date('Y-m-d h:i:s');
    $obtener_clientes = $this->k->plantilla_fullAC();
    // obtener_tabla_procesados
    $resultcanti = count($obtener_clientes);
    $i = 2;

    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< */
    /*-------- REPORTE DE CON O SIN EXHIBIDORES -------*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> */
    $spreadsheet = new Spreadsheet();
    $spreadsheet->setActiveSheetIndex(0);
    $sheet = $spreadsheet->getActiveSheet();


                /*000000000000---ENCABEZADO---0000000000000000000*/
                $sheet->getStyle('A1:AB1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                $sheet->getStyle('A1:AB1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
                $sheet->getStyle('A1:AB1')->getFont()->setBold( true );
                //$sheet->getStyle("A1:F1")->getFont()->setSize(11);
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
                //$sheet->getRowDimension('1')->setRowHeight(100);
                // $sheet->getDefaultColumnDimension()->setWidth(13);
                // $sheet->getColumnDimension('C')->setWidth(35);
                // $sheet->getColumnDimension('D')->setWidth(55);
                // $sheet->getColumnDimension('G')->setWidth(30);

                $sheet->setCellValue('A1','Nombre_Distribuidora');
                $sheet->setCellValue('B1','Nombre_Ruta');
                $sheet->setCellValue('C1','CodigoAC');
                $sheet->setCellValue('D1','NombreAC');
                $sheet->setCellValue('E1','DireccionAC');
                $sheet->setCellValue('F1','TelefonoAC');
                $sheet->setCellValue('G1','ContactoAC');
                $sheet->setCellValue('H1','EstadoAC');
                $sheet->setCellValue('I1','OrdenVistaAC');
                $sheet->setCellValue('J1','LUNES');
                $sheet->setCellValue('K1','MARTES');
                $sheet->setCellValue('L1','MIERCOLES');
                $sheet->setCellValue('M1','JUEVES');
                $sheet->setCellValue('N1','VIERNES');
                $sheet->setCellValue('O1','SABADO');
                $sheet->setCellValue('P1','DOMINGO');
                $sheet->setCellValue('Q1','FrecuencVisitaAC');
                $sheet->setCellValue('R1','LATITUD');
                $sheet->setCellValue('S1','LONGITUD');
                $sheet->setCellValue('T1','DEPARTAMENTO');
                $sheet->setCellValue('U1','MUNICIPIO');
                $sheet->setCellValue('V1','DuiAC');
                $sheet->setCellValue('W1','NitAC');
                $sheet->setCellValue('X1','Numero_RegistroAC');
                $sheet->setCellValue('Y1','Fecha_Ingreso');
                $sheet->setCellValue('Z1','FechaSupervisor');
                $sheet->setCellValue('AA1','FechaDescarga');
                $sheet->setCellValue('AB1','Motivo');
                $sheet->setCellValue('AC1','OrdenSemana');
                $separ_dias = '';

                /*0000000000000000000000000000000000000000000000*/
                foreach ($obtener_clientes as $cli )
                {
                    $telefono = '';
                    $sheet->setCellValue('A'.$i,$cli->Nombre_Distribuidora);
                    $sheet->setCellValue('B'.$i,$cli->Nombre_Ruta);
                    $sheet->setCellValue('C'.$i,$cli->CodigoAC);
                    $sheet->setCellValue('D'.$i,$cli->NombreAC);
                    $sheet->setCellValue('E'.$i,$cli->DireccionAC);
                    if($cli->TelefonoAC == '0000-0000'){
                        $telefono = 0;
                    }else{
                        $telefono = $cli->TelefonoAC;
                    }
                    $sheet->setCellValue('F'.$i,$telefono);
                    $sheet->setCellValue('G'.$i,$cli->ContactoAC);
                    $sheet->setCellValue('H'.$i,$cli->EstadoAC);



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
                            if(empty($orden_separados[0]))
                            $orden_separados[0] = $cli->OrdenVistaAC;
                            if(empty($orden_separados[1]))
                            $orden_separados[1] = $cli->OrdenVistaAC;
                            if(empty($orden_separados[2]))
                            $orden_separados[2] = $cli->OrdenVistaAC;
                            if(empty($orden_separados[3]))
                            $orden_separados[3]= $cli->OrdenVistaAC;
                            if(empty($orden_separados[4]))
                            $orden_separados[4] = $cli->OrdenVistaAC;
                            if(empty($orden_separados[5]))
                            $orden_separados[5] = $cli->OrdenVistaAC;
                            if(empty($orden_separados[6]))
                            $orden_separados[6] = $cli->OrdenVistaAC;
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

                    $separ_dias = explode(",", $cli->DiasAC);
                    $sheet->setCellValue('J'.$i,substr($separ_dias[0],-1));
                    $sheet->setCellValue('K'.$i,substr($separ_dias[1],-1));
                    $sheet->setCellValue('L'.$i,substr($separ_dias[2],-1));
                    $sheet->setCellValue('M'.$i,substr($separ_dias[3],-1));
                    $sheet->setCellValue('N'.$i,substr($separ_dias[4],-1));
                    $sheet->setCellValue('O'.$i,substr($separ_dias[5],-1));
                    $sheet->setCellValue('P'.$i,substr($separ_dias[6],-1));
                    $sheet->setCellValue('Q'.$i,$cli->FrecuencVisitaAC);
                    $sheet->setCellValue('R'.$i,$cli->LatitudAC);
                    $sheet->setCellValue('S'.$i,$cli->LongitudAC);
                    $sheet->setCellValue('T'.$i,$cli->NombreDepartamento);
                    $sheet->setCellValue('U'.$i,$cli->NombreMunicipio);
                    $sheet->setCellValue('V'.$i,$cli->DuiAC);
                    $sheet->setCellValue('W'.$i,$cli->NitAC);
                    $sheet->setCellValue('X'.$i,$cli->Numero_RegistroAC);
                    $sheet->setCellValue('Y'.$i,$cli->Fecha_Ingreso);
                    $sheet->setCellValue('Z'.$i,$cli->FechaSupervisor);
                    $sheet->setCellValue('AA'.$i,$cli->FechaDescarga);
                    $sheet->setCellValue('AB'.$i,$cli->Motivos);
                    $sheet->setCellValue('AC'.$i,$cli->Ord_VisitaSema);
                    $i++;
                }
                // $cod_aleatorio = numero_aleatorio(7);
                // $nombre_archivo = 'reporte-clientes/ClientesActualizados_'.$fecha_actual.'_'.$cod_aleatorio.'.xlsx';
                // $writer = new Xlsx($spreadsheet);
                // $writer->save($nombre_archivo);
                // $spreadsheet->disconnectWorksheets();
                // unset($spreadsheet);


    // $fecha_actual = date('d-m-Y');
    $nombre_archivo = 'ClientesActualizados_'.$fecha_actual.'.xlsx';
    $writer = new Xlsx($spreadsheet);
    // $writer->save($nombre_archivo);

    // Redirect output to a client’s web browser (Ods)
    header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
    header('Content-Disposition: attachment;filename="'.$nombre_archivo.'"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $writer->save('php://output');
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);


    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< */
    /*-------- REPORTE DE CON O SIN EXHIBIDORES -------*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> */






    // $sheet->setCellValue('A1','Ruta');
    // $sheet->setCellValue('B1','CodigoCliente');
    // $sheet->setCellValue('C1','NombreCliente');
    // $sheet->setCellValue('D1','ContactoCliente');
    // $sheet->setCellValue('E1','DireccionCliente');
    // $sheet->setCellValue('F1','Exhibidores');
    // $sheet->setCellValue('G1','Canal');
    // $sheet->setCellValue('H1','Grupo');
    // $sheet->setCellValue('I1','Distribuidora');
    // $sheet->setCellValue('J1','Division');
    // $sheet->setCellValue('K1','Pais');

    // $obt_reporte = $this->exh->ReporteExhibidor();
    // $k = 2;
    // foreach ($obt_reporte as $val){
   
    //     $sheet->setCellValue('A'.$k,$val->Nombre_Ruta);
    //     $sheet->setCellValue('B'.$k,$val->CodigoCliente);
    //     $sheet->setCellValue('C'.$k,$val->NombreCliente);
    //     $sheet->setCellValue('D'.$k,$val->ContactoCliente);
    //     $sheet->setCellValue('E'.$k,$val->DireccionCliente);
    //     $sheet->setCellValue('F'.$k,$val->Con);
    //     $sheet->setCellValue('G'.$k,$val->Canal);
    //     $sheet->setCellValue('H'.$k,$val->Grupo);
    //     $sheet->setCellValue('I'.$k,$val->Nombre_Distribuidora);
    //     $sheet->setCellValue('J'.$k,$val->Division);
    //     $sheet->setCellValue('K'.$k,$val->Nombre_Pais);

    //     $k++;
    // }
    // $fecha_actual = date('d-m-Y');
    // $nombre_archivo = 'CLIENTES_CON_SIN_EXHIBIDORES_'.$fecha_actual.'.xlsx';
    // $writer = new Xlsx($spreadsheet);
    // // $writer->save($nombre_archivo);

    // // Redirect output to a client’s web browser (Ods)
    // header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
    // header('Content-Disposition: attachment;filename="'.$nombre_archivo.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    // // If you're serving to IE over SSL, then the following may be needed
    // header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    // header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    // header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    // header('Pragma: public'); // HTTP/1.0

    // $writer->save('php://output');
    // $spreadsheet->disconnectWorksheets();
    // unset($spreadsheet);



}


//ingresar_exhibidores
//TAMBINE COMODIN AUXILIO DUPLICADOS
    function ingresar_exhibidores(){

    
    $Obt_Rutas = $this->exh->AuxilioRutas();
    // $TotalInconsistencias = count($Obt_Ayuda);
    // echo "TOTAL INCONSISTENCIAS => ".$TotalInconsistencias."<br>";
    
    $ContadorDuplicados = 0;
    // var_dump($Obt_Ayuda);

    foreach ($Obt_Rutas as $val){

        // echo $val->Id_Usuarios."<br>";
        $Obt_Ayuda = $this->exh->AuxilioDuplicados($val->Id_Usuarios);
        if(!empty($Obt_Ayuda)){
            
            foreach ($Obt_Ayuda as $vall){
                $ContadorDuplicados++;
                echo $vall->Id_Usuarios." | ".$vall->Codigo."<br>";
                $Atrapar_Pokemon = $this->exh->AtraparRepetido($vall->Codigo,$vall->Id_Usuarios);
                $Axilio = 0;
                foreach ($Atrapar_Pokemon as $valll){
                    $Axilio++;
                    if($Axilio > 1){
                        echo $valll->Id_Cliente." | ".$valll->Codigo." | ".$valll->Nombre." | ".$valll->Id_Usuarios." TE ATRAPE";
                        echo "<br>";
                        $this->exh->MetePresoAtrapado($valll->Id_Cliente);
                    }else{
                        echo $valll->Id_Cliente." | ".$valll->Codigo." | ".$valll->Nombre." | ".$valll->Id_Usuarios;
                        echo "<br>";
                    }
                }
                $Axilio = 0;
            }
        }else{
            echo $val->Id_Usuarios."No Se encontraron Duplicados en "."<br>";
        }


    }

     echo "<h1>TOTAL DE DUPLICADOS ENCONTRADOS ".$ContadorDuplicados."</h1>";
        // $rutaArchivo = 'sincronizacion/EXHIBDORES_7.1.08_Y_7.1.24.xlsx';
        // $documento = IOFactory::load($rutaArchivo);
        // $hojaActual = $documento->getSheet(0);
        // $highestRow = $hojaActual->getHighestRow();
        // $total_registros = $highestRow -1;
        // echo "CANTIDAD DE REGISTROS => ".$total_registros;
        // $SUCCESS_OK = 0;
        // $ERRORES = 0;echo "<br>";
        // $arrgaupdate = array();
        // $contarrg = 0;
        // for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {
        //     $arrginsert[] = [
        //     'Id_Cliente' => trim(intval($hojaActual->getCell("G".$indiceHoja)->getCalculatedValue())),
        //     'Id_Exhibidores' => trim(intval($hojaActual->getCell("F".$indiceHoja)->getCalculatedValue())),
        //     'Cantidad' => trim(intval($hojaActual->getCell("D".$indiceHoja)->getCalculatedValue()))
        //     ];
        // }

        // // var_dump($arrginsert);
        // echo $this->cl->ingresar_clientes_exhibidor($arrginsert);
    }


    function generacodigo(){
        $rutaArchivo = 'PLANTILLA_CLIENTES_SDVKIKITO.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        # Recuerda que un documento puede tener múltiples hojas
        # obtener conteo e iterar
        $totalDeHojas = $documento->getSheetCount();
        # Iterar hoja por hoja
        //echo "total de filas ".$totalDeHojas."<br>";
        $highestRow = $documento->getActiveSheet()->getHighestRow();
        echo "CANTIDAD DE REGISTROS => ".$highestRow;
    }

    /*
        GENERADOR DE ITINERARIO DE CLIENTES -------- 08/03/2023
    */


    function getNumsOfWeeksByDay($day, $month, $year) {
        $weekDays = 0;
        $date = new DateTime(sprintf('last day of %d-%d', $year, $month));
        while ($date->format('m') == $month) {
            if ($date->format('2') == $day) {
                $weekDays++;
            }
            $date->sub(new DateInterval('P1D'));
        }
        return $weekDays;
    }

    function SemanasMes($fecha){
        // $dt->format("d-m-Y");
        $date = $fecha;
        $firstDay = date('Y-m-01', strtotime($date)); // Tomamos el primer día del mes
        $lastDay = date('Y-m-t', strtotime($date)); // Y tomamos el ultimo día del mes
        $weeks = array();
        // Iteramos sobre todos los días del mes, y agregamos al arreglo las semanas solo si no existen previamente
        while ($firstDay < $lastDay) {
            $week = strftime('%V', strtotime($firstDay));
            if (!in_array($week, $weeks)) {
                $weeks[] = intval(strftime('%V', strtotime($firstDay)));
            }
            $firstDay = date ('Y-m-d', strtotime($firstDay . ' +1 day'));
        }
        return $weeks;
    }

    function verMeses($fechau,$fechad){
        $f1 = new DateTime($fechau);
        $f2 = new DateTime($fechad);
        $cant_meses = $f2->diff($f1);
        $cant_meses = $cant_meses->format('%m'); //devuelve el numero de meses entre ambas fechas.
        // echo 'Cantidad Meses'.$cant_meses.'<br>';
        $listaMeses = array($f1->format('Y-m-d'));
        for ($i = 1; $i <= $cant_meses; $i++) {
            $ultimaFecha = end($listaMeses);
            $ultimaFecha = new DateTime($ultimaFecha);
            $nuevaFecha = $ultimaFecha->add(new DateInterval("P1M"));
            $nuevaFecha = $nuevaFecha->format('Y-m-d');
            // $nuevaFecha = date('n', strtotime($nuevaFecha->format('Y-m-d')));
            array_push($listaMeses, $nuevaFecha) ;
        }
        return $listaMeses;
     }


    function Gen_Iti_PlusUltra(){

    // var_dump($this->verMeses( "2023-01-01",  "2023-09-01" ));

    //   echo $this->verMeses( "2023-02-01",  "2023-09-05" );
    // $fechaEntera = '2023-03-01';
    // $fechaEntera = strtotime($fechaEntera);
    // echo $mes = date("m", $fechaEntera);;


        // $fecha = '10-03-2023';	//Fecha de la cual obtendremos la semana
        // $fechaSegundos = strtotime($fecha);
        // $semana = intval(date('W', $fechaSegundos));
        // echo $semana;

        // var_dump($this->SemanasMes('10-03-2023'));
    

        // echo $this->getNumsOfWeeksByDay(1, 3, 2023);


        $rutaArchivo = 'RECODIFICACION/PLANTILLA_ITINERARIO.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $highestRow = $hojaActual->getHighestRow();
        $Total_Registros = $highestRow -1;
        // echo "CANTIDAD DE REGISTROS => ".$Total_Registros."<br>";
        $arrg_cli = array();$arrg_cli_especial = array();

        /*
            arrg_cli[x][y][z]
            ----------------------
            posicion x
            ----------------------
            0 --> LUNES
            1 --> MARTES
            2 --> MIERCOLES
            3 --> JUEVES
            4 --> VIERNES
            5 --> SABADO
            6 --> DOMINGO
            ----------------------
            posicion y
            ---------------------- 
            numero de registro (codigo)
            ---------------------- 
            posicion z
            ----------------------
            Frecuencia de registro
            ----------------------
        */
        $c_l = 0;$c_m = 0;$c_mi = 0;$c_j = 0;$c_v = 0;$c_s = 0;$c_d = 0;
        $c_m_l = 0;$c_m_m = 0;$c_m_mi = 0;$c_m_j = 0;$c_m_v = 0;$c_m_s = 0;$c_m_d = 0;
        # INICIALIZANDO ARRG
        // $arrg_cli[0][0][0] = 0;$arrg_cli[2][0][0] = 0;$arrg_cli[4][0][0] = 0;$arrg_cli[6][0][0] = 0;
        // $arrg_cli[1][0][0] = 0;$arrg_cli[3][0][0] = 0;$arrg_cli[5][0][0] = 0;
        
        for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {

            $dia_l  = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
            $dia_m  = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
            $dia_mi = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
            $dia_j  = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
            $dia_v  = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
            $dia_s  = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
            $dia_d  = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
            $Frecuencia = strval($hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue());

            if($dia_l == 1 && $Frecuencia !='1' && $Frecuencia !='2' && $Frecuencia !='3' && $Frecuencia !='4'){
                $arrg_cli[0][$c_l][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli[0][$c_l][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_l++;
            }
            if($dia_m == 1 && $Frecuencia !='1' && $Frecuencia !='2' && $Frecuencia !='3' && $Frecuencia !='4'){
                $arrg_cli[1][$c_m][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli[1][$c_m][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m++;
            }
            if($dia_mi == 1 && $Frecuencia !='1' && $Frecuencia !='2' && $Frecuencia !='3' && $Frecuencia !='4'){
                $arrg_cli[2][$c_mi][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli[2][$c_mi][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_mi++;
            }
            if($dia_j == 1 && $Frecuencia !='1' && $Frecuencia !='2' && $Frecuencia !='3' && $Frecuencia !='4'){
                $arrg_cli[3][$c_j][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli[3][$c_j][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_j++;
            }
            if($dia_v == 1 && $Frecuencia !='1' && $Frecuencia !='2' && $Frecuencia !='3' && $Frecuencia !='4'){
                $arrg_cli[4][$c_v][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli[4][$c_v][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_v++;
            }
            if($dia_s == 1 && $Frecuencia !='1' && $Frecuencia !='2' && $Frecuencia !='3' && $Frecuencia !='4'){
                $arrg_cli[5][$c_s][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli[5][$c_s][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_s++;
            }
            if($dia_d == 1 && $Frecuencia !='1' && $Frecuencia !='2' && $Frecuencia !='3' && $Frecuencia !='4'){
                $arrg_cli[6][$c_d][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli[6][$c_d][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_d++;
            }
            ###################################################################################################

            if($dia_l == 1 && ($Frecuencia == '1' || $Frecuencia == '2' || $Frecuencia == '3' || $Frecuencia == '4')){
                $arrg_cli_especial[0][$c_m_l][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[0][$c_m_l][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m_l++;
            }
           
            if($dia_m == 1 && ($Frecuencia == '1' || $Frecuencia == '2' || $Frecuencia == '3' || $Frecuencia == '4')){
                $arrg_cli_especial[1][$c_m_m][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[1][$c_m_m][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m_m++;
            }

            if( $dia_mi == 1 && ($Frecuencia == '1' || $Frecuencia == '2' || $Frecuencia == '3' || $Frecuencia == '4')){
                $arrg_cli_especial[2][$c_m_mi][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[2][$c_m_mi][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m_mi++;
            }

            if($dia_j == 1 && ($Frecuencia == '1' || $Frecuencia == '2' || $Frecuencia == '3' || $Frecuencia == '4')){
                $arrg_cli_especial[3][$c_m_j][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[3][$c_m_j][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m_j++;
            }

            if($dia_v == 1 && ($Frecuencia == '1' || $Frecuencia == '2' || $Frecuencia == '3' || $Frecuencia == '4')){
                $arrg_cli_especial[4][$c_m_v][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[4][$c_m_v][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m_v++;
            }

            if($dia_s == 1 && ($Frecuencia == '1' || $Frecuencia == '2' || $Frecuencia == '3' || $Frecuencia == '4')){
                $arrg_cli_especial[5][$c_m_s][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[5][$c_m_s][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m_s++;
            }

            if($dia_d == 1 && ($Frecuencia == '1' || $Frecuencia == '2' || $Frecuencia == '3' || $Frecuencia == '4')){
                $arrg_cli_especial[6][$c_m_d][0] = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][1] = $hojaActual->getCell("Z".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][2] = $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][3] = $hojaActual->getCell("N".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][4] = $hojaActual->getCell("O".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][5] = $hojaActual->getCell("P".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][6] = $hojaActual->getCell("Q".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][7] = $hojaActual->getCell("R".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][8] = $hojaActual->getCell("S".$indiceHoja)->getCalculatedValue();
                $arrg_cli_especial[6][$c_m_d][9] = $hojaActual->getCell("T".$indiceHoja)->getCalculatedValue();
                $c_m_d++;
            }

        }

        // var_dump($arrg_cli_especial);
        // echo "<br>";
        // echo "<br>";

        $arrg_fechas = array();
        $arrg_fecha_X_Mes = array();
        $l = 0;$m = 0;$mi = 0;$j = 0;$v = 0;$s = 0;$d = 0;$ct_dia = 0;
        /*
            arrg_fechas[x][y]
            ----------------------
            posicion x
            ----------------------
            0 --> LUNES
            1 --> MARTES
            2 --> MIERCOLES
            3 --> JUEVES
            4 --> VIERNES
            5 --> SABADO
            6 --> DOMINGO
            -----------------------
            posicion y
            -----------------------
            Fechas
            -----------------------
            xxxxxxxxxxxxxxxxxxxxxxx
            -----------------------
            arrg_fecha_X_Mes[x][y][z][a]
            -----------------------
            posicion x
            -----------------------
            Mes
            -----------------------
            posicion y
            -----------------------
            semana
            -----------------------
            posicion z
            -----------------------
            dia
            -----------------------
            posicion a
            -----------------------
            fecha
        */
        $fecha_inicial = new DateTime('01-03-2023');
        $fecha_final = new DateTime('31-03-2023');
        // Necesitamos modificar la fecha final en 1 día para que aparezca en el bucle
        $fecha_final = $fecha_final ->modify('+1 day');
        $intervalo = DateInterval::createFromDateString('1 day');
        $periodo = new DatePeriod($fecha_inicial , $intervalo, $fecha_final);
        foreach ($periodo as $dt) {

            $dia_semana = date('N', strtotime($dt->format("Y-m-d")));
            $mes = date('n', strtotime($dt->format("Y-m-d")));
            $semana= intval(date('W', strtotime($dt->format("Y-m-d"))));
        

            if( $dia_semana == 1 ){ //LUNES
                $arrg_fechas[0][$l]  = $dt->format("d-m-Y");
                // $arrg_fecha_X_Mes[$mes][$semana][$l] = $dt->format("d-m-Y");
                $arrg_fecha_X_Mes[$mes][$semana][0][$l] = $dt->format("d-m-Y");
                $l++;
            } 
               
            if( $dia_semana == 2 ){ //MARTES
                $arrg_fechas[1][$m] = $dt->format("d-m-Y");
                // $arrg_fecha_X_Mes[$mes][$semana][$m] = $dt->format("d-m-Y");
                $arrg_fecha_X_Mes[$mes][$semana][1][$m] = $dt->format("d-m-Y");
                $m++;
            } 
               
            if( $dia_semana == 3 ){ //MIERCOLES
                $arrg_fechas[2][$mi] = $dt->format("d-m-Y");
                // $arrg_fecha_X_Mes[$mes][$semana][$mi] = $dt->format("d-m-Y");
                $arrg_fecha_X_Mes[$mes][$semana][2][$mi] = $dt->format("d-m-Y");
                $mi++;
            } 
                
            if( $dia_semana == 4 ){ //JUEVES
                $arrg_fechas[3][$j] = $dt->format("d-m-Y");
                // $arrg_fecha_X_Mes[$mes][$semana][$j] = $dt->format("d-m-Y");
                $arrg_fecha_X_Mes[$mes][$semana][3][$j] = $dt->format("d-m-Y");
                $j++;
            } 
                
            if( $dia_semana == 5 ){ //VIERNES
                $arrg_fechas[4][$v] = $dt->format("d-m-Y");
                // $arrg_fecha_X_Mes[$mes][$semana][$v] = $dt->format("d-m-Y");
                $arrg_fecha_X_Mes[$mes][$semana][4][$v] = $dt->format("d-m-Y");
                $v++;
            } 
                
            if( $dia_semana == 6 ){ //SABADO
                $arrg_fechas[5][$s] = $dt->format("d-m-Y");
                // $arrg_fecha_X_Mes[$mes][$semana][$s] = $dt->format("d-m-Y");
                $arrg_fecha_X_Mes[$mes][$semana][5][$s] = $dt->format("d-m-Y");
                $s++;
            } 
               
            if( $dia_semana == 7 ){ //DOMINGO
                $arrg_fechas[6][$d] = $dt->format("d-m-Y");
                // $arrg_fecha_X_Mes[$mes][$semana][$d] = $dt->format("d-m-Y");
                $arrg_fecha_X_Mes[$mes][$semana][6][$d] = $dt->format("d-m-Y");
                $d++;
            } 
               
            //SACAR ARRAY POR DIAS DE VISITA -  DIA DE SEMANA
            //SACAR ARRAY MULTIDIMENCONAL CON CODIGO, FRECUENCI, DIA VISITA
            //EJECUTAR COMPARACION
            $ct_dia++;
        }

        // var_dump($arrg_fechas);
        // var_dump($arrg_fecha_X_Mes);

        #    CREAR ITINERARIO
        #    RECORRIDO DE LUNES A DOMINGO ----- O AL 6

        $arrg_data = array();
        $Tfechas = count($arrg_fechas);
        // var_dump($arrg_fechas);
        // echo $Tfechas;
        // $Tpru = count($arrg_cli[1]);
        // echo "<br> tota Tfechas ".$Tfechas;
        // echo "<br> tota Tpru ".$Tpru;
        $r = 0;
        for ($a=0; $a <$Tfechas; $a++) { //DIAS DE LA SEMANA DE LUNES A DOMINGO
            $Tfech_x_dia = count($arrg_fechas[$a]);
            // var_dump($arrg_fechas[$a]);
            // echo "PRIMER BLOQUE ".$Tfech_x_dia." <br>";
           for ($k=0; $k < $Tfech_x_dia; $k++) { #RECORREMOS LAS FECHAS POR DIA DE SEMANA
                
                
                if(isset($arrg_cli[$a])){
                    
                    // var_dump($arrg_cli[$a]);

                    $TCli = count($arrg_cli[$a]);
                    $fecha = $arrg_fechas[$a][$k];	//Fecha de la cual obtendremos la semana
                    $fechaSegundos = strtotime($fecha);
                    $semana = intval(date('W', $fechaSegundos));
                   
                    for ($i=0; $i < $TCli; $i++) { 
                        
                        $Frecuencia = strval($arrg_cli[$a][$i][1]);
                        // echo $Frecuencia;
                        // echo "<br>".$arrg_cli[$a][$i][0]." ".$arrg_fechas[$a][$k]."<br>";
            

                        if( $Frecuencia == 'NA'){
                            $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                            $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                            $arrg_data[$r][2] = $Frecuencia;
                            $arrg_data[$r][3] = 'SEMANAL';
                            $arrg_data[$r][4] = $semana;
                            $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                            $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                            $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                            $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                            $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                            $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                            $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                            $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                        }else if( $Frecuencia == '1,3'){
                            if (($semana % 2) != 0) {
                                //Es semana impar
                                $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                                $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                                $arrg_data[$r][2] = $Frecuencia;
                                $arrg_data[$r][3] = 'QUINCENAL 1,3';
                                $arrg_data[$r][4] = $semana;
                                $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                                $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                                $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                                $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                                $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                                $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                                $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                                $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                            }
                        }else if( $Frecuencia == '2,4'){
                            if (($semana % 2) == 0) {
                                //Es un semana par
                                $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                                $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                                $arrg_data[$r][2] = $Frecuencia;
                                $arrg_data[$r][3] = 'QUINCENAL 2,4';
                                $arrg_data[$r][4] = $semana;
                                $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                                $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                                $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                                $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                                $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                                $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                                $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                                $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                            }

                        }else if($Frecuencia == '1'){
                            echo "semana 1";
                            $tsem = 0;$arrSemes = $this->SemanasMes($arrg_fechas[$a][$k]);
                            $tsem = count($arrSemes);
                            $semanaInt  = intval($semana);
                            $conta_semana = 0;
                            for ($e=0; $e < $tsem ; $e++) { 
                                $conta_semana++;
                                // if($arrSemes[$e] == $semanaInt){
                                    if($conta_semana == 1){
                                        $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                                        $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                                        $arrg_data[$r][2] = $Frecuencia;
                                        $arrg_data[$r][3] = 'MENSUAL S1';
                                        $arrg_data[$r][4] = $semana;
                                        $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                                        $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                                        $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                                        $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                                        $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                                        $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                                        $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                                        $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                                    }

                                // }
                            }

                         
                        }else if($Frecuencia == '2'){
                            $tsem = 0;$arrSemes = $this->SemanasMes($arrg_fechas[$a][$k]);
                            $tsem = count($arrSemes);
                            // $conta_semana = 0;
                            $semanaInt  = intval($semana);
                            $conta_semana = 0;
                            for ($e=0; $e < $tsem ; $e++) { 
                                $conta_semana++;
                                // if($arrSemes[$e] == $semanaInt){
                                    if($conta_semana == 2){
                                        $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                                        $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                                        $arrg_data[$r][2] = $Frecuencia;
                                        $arrg_data[$r][3] = 'MENSUAL S2';
                                        $arrg_data[$r][4] = $semana;
                                        $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                                        $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                                        $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                                        $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                                        $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                                        $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                                        $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                                        $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                                    }

                                // }
                            }
                         
                        }else if($Frecuencia == '3'){

                            $tsem = 0;$arrSemes = $this->SemanasMes($arrg_fechas[$a][$k]);
                            $tsem = count($arrSemes);
                            // $conta_semana = 0;
                            $conta_semana = 0;
                            $semanaInt  = intval($semana);
                            for ($e=0; $e < $tsem ; $e++) { 
                                $conta_semana++;
                                // if($arrSemes[$e] == $semanaInt){
                                    if($conta_semana == 3){
                                        $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                                        $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                                        $arrg_data[$r][2] = $Frecuencia;
                                        $arrg_data[$r][3] = 'MENSUAL S3';
                                        $arrg_data[$r][4] = $semana;
                                        $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                                        $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                                        $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                                        $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                                        $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                                        $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                                        $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                                        $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                                    }

                                // }
                            }
                        }else if($Frecuencia == '4'){
                            $tsem = 0;$arrSemes = $this->SemanasMes($arrg_fechas[$a][$k]);
                            $tsem = count($arrSemes);
                            // $conta_semana = 0;
                            $semanaInt  = intval($semana);
                            $conta_semana = 0;
                            for ($e=0; $e < $tsem ; $e++) { 
                                $conta_semana++;
                                // if($arrSemes[$e] == $semanaInt){
                                    if($conta_semana == 4){
                                        $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                                        $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                                        $arrg_data[$r][2] = $Frecuencia;
                                        $arrg_data[$r][3] = 'MENSUAL S4';
                                        $arrg_data[$r][4] = $semana;
                                        $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                                        $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                                        $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                                        $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                                        $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                                        $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                                        $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                                        $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                                    }

                                // }
                            }
                         
                        }else{
                            $parimpar = '';
                            if (($semana % 2) != 0) {
                                $parimpar = 'IMPAR';
                            }else{
                                $parimpar = 'PAR';
                            }
                            $arrg_data[$r][0] = $arrg_cli[$a][$i][0];
                            $arrg_data[$r][1] = $arrg_fechas[$a][$k];
                            $arrg_data[$r][2] = $Frecuencia;
                            $arrg_data[$r][3] = 'NINGUNA';
                            $arrg_data[$r][4] = $semana;
                            $arrg_data[$r][5] = $arrg_cli[$a][$i][2];
                            $arrg_data[$r][6] = $arrg_cli[$a][$i][3];
                            $arrg_data[$r][7] = $arrg_cli[$a][$i][4];
                            $arrg_data[$r][8] = $arrg_cli[$a][$i][5];
                            $arrg_data[$r][9] = $arrg_cli[$a][$i][6];
                            $arrg_data[$r][10] = $arrg_cli[$a][$i][7];
                            $arrg_data[$r][11] = $arrg_cli[$a][$i][8];
                            $arrg_data[$r][12] = $arrg_cli[$a][$i][9];
                        }
                        $r++;
                    }

                }


           }
        }


        // var_dump($arrg_data);

        /*
            FRECUENCIA DE VISITA MENSUAL
            EDITAR EL FORMATO DE LAS FECHAS ------     
        */
        // var_dump($this->verMeses( "2023-01-01",  "2023-02-28" ));
        $arrg_meses = $this->verMeses( "2023-03-01",  "2023-03-31" );


        $Total_meses = count($arrg_meses);
        $Tfechas = count($arrg_fechas);

        $arrg_data_especial = array();
        $c_re_esp = 0;
        for ($a=0; $a <$Tfechas; $a++) { //DIAS DE LA SEMANA DE LUNES A DOMINGO
        
            if(isset($arrg_cli_especial[$a])){

                $Total_CliEspeciales = count($arrg_cli_especial[$a]);

                for ($cli=0; $cli < $Total_CliEspeciales; $cli++) { 
            
                    // echo $arrg_cli_especial[$a][$cli][1]."<br>";
                    $Frecuencia = $arrg_cli_especial[$a][$cli][1];

                    // if($Frecuencia == '1'){
                        
                        for ($m=0; $m < $Total_meses ; $m++) { 
                            
                            $mes = date('n', strtotime($arrg_meses[$m]));


                            $semanas_xmes = $this->SemanasMes($arrg_meses[$m]);
                            $total_Semas_X_mes = count($semanas_xmes);
                            $conta = 0;
                            $c_semana = 0;
                            for ($s=0; $s < $total_Semas_X_mes; $s++) { 

                                // echo $s."<br>";
                                // var_dump($semanas_xmes[$s]);

                                if(isset($arrg_fecha_X_Mes[$mes][$semanas_xmes[$s]][$a])){
                                   


                                    $t_s_dia_fecha = count($arrg_fecha_X_Mes[$mes][$semanas_xmes[$s]]);
                                    // echo 'TOTAL FECHAS '.$t_s_dia_fecha;
                                    // var_dump($arrg_fecha_X_Mes[$mes][$semanas_xmes[$s]][$a]);
                                    $arrg_fexmes = $arrg_fecha_X_Mes[$mes][$semanas_xmes[$s]][$a];

                                    
                                    foreach ($arrg_fexmes as $key => $value) {
                                        $c_semana++;
                                        // echo $value;

                                        if($c_semana == 1 && $Frecuencia == '1'){
                                            $arrg_data_especial[$c_re_esp][0] = $arrg_cli_especial[$a][$cli][0];
                                            $arrg_data_especial[$c_re_esp][1] = $value;
                                            $arrg_data_especial[$c_re_esp][2] = $Frecuencia;
                                            $arrg_data_especial[$c_re_esp][3] = 'MENSUAL S1';
                                            $arrg_data_especial[$c_re_esp][4] = $semanas_xmes[$s];
                                            $arrg_data_especial[$c_re_esp][5] = $arrg_cli_especial[$a][$cli][2];
                                            $arrg_data_especial[$c_re_esp][6] = $arrg_cli_especial[$a][$cli][3];
                                            $arrg_data_especial[$c_re_esp][7] = $arrg_cli_especial[$a][$cli][4];
                                            $arrg_data_especial[$c_re_esp][8] = $arrg_cli_especial[$a][$cli][5];
                                            $arrg_data_especial[$c_re_esp][9] = $arrg_cli_especial[$a][$cli][6];
                                            $arrg_data_especial[$c_re_esp][10] = $arrg_cli_especial[$a][$cli][7];
                                            $arrg_data_especial[$c_re_esp][11] = $arrg_cli_especial[$a][$cli][8];
                                            $arrg_data_especial[$c_re_esp][12] = $arrg_cli_especial[$a][$cli][9];
                                            $c_re_esp++;
                                        }
                                        if($c_semana == 2 && $Frecuencia == '2'){
                                            $arrg_data_especial[$c_re_esp][0] = $arrg_cli_especial[$a][$cli][0];
                                            $arrg_data_especial[$c_re_esp][1] = $value;
                                            $arrg_data_especial[$c_re_esp][2] = $Frecuencia;
                                            $arrg_data_especial[$c_re_esp][3] = 'MENSUAL S2';
                                            $arrg_data_especial[$c_re_esp][4] = $semanas_xmes[$s];
                                            $arrg_data_especial[$c_re_esp][5] = $arrg_cli_especial[$a][$cli][2];
                                            $arrg_data_especial[$c_re_esp][6] = $arrg_cli_especial[$a][$cli][3];
                                            $arrg_data_especial[$c_re_esp][7] = $arrg_cli_especial[$a][$cli][4];
                                            $arrg_data_especial[$c_re_esp][8] = $arrg_cli_especial[$a][$cli][5];
                                            $arrg_data_especial[$c_re_esp][9] = $arrg_cli_especial[$a][$cli][6];
                                            $arrg_data_especial[$c_re_esp][10] = $arrg_cli_especial[$a][$cli][7];
                                            $arrg_data_especial[$c_re_esp][11] = $arrg_cli_especial[$a][$cli][8];
                                            $arrg_data_especial[$c_re_esp][12] = $arrg_cli_especial[$a][$cli][9];
                                            $c_re_esp++;
                                        }
                                        if($c_semana == 3 && $Frecuencia == '3'){
                                            $arrg_data_especial[$c_re_esp][0] = $arrg_cli_especial[$a][$cli][0];
                                            $arrg_data_especial[$c_re_esp][1] = $value;
                                            $arrg_data_especial[$c_re_esp][2] = $Frecuencia;
                                            $arrg_data_especial[$c_re_esp][3] = 'MENSUAL S3';
                                            $arrg_data_especial[$c_re_esp][4] = $semanas_xmes[$s];
                                            $arrg_data_especial[$c_re_esp][5] = $arrg_cli_especial[$a][$cli][2];
                                            $arrg_data_especial[$c_re_esp][6] = $arrg_cli_especial[$a][$cli][3];
                                            $arrg_data_especial[$c_re_esp][7] = $arrg_cli_especial[$a][$cli][4];
                                            $arrg_data_especial[$c_re_esp][8] = $arrg_cli_especial[$a][$cli][5];
                                            $arrg_data_especial[$c_re_esp][9] = $arrg_cli_especial[$a][$cli][6];
                                            $arrg_data_especial[$c_re_esp][10] = $arrg_cli_especial[$a][$cli][7];
                                            $arrg_data_especial[$c_re_esp][11] = $arrg_cli_especial[$a][$cli][8];
                                            $arrg_data_especial[$c_re_esp][12] = $arrg_cli_especial[$a][$cli][9];
                                            $c_re_esp++;
                                        }
                                        if($c_semana == 4 && $Frecuencia == '4'){
                                            $arrg_data_especial[$c_re_esp][0] = $arrg_cli_especial[$a][$cli][0];
                                            $arrg_data_especial[$c_re_esp][1] = $value;
                                            $arrg_data_especial[$c_re_esp][2] = $Frecuencia;
                                            $arrg_data_especial[$c_re_esp][3] = 'MENSUAL S4';
                                            $arrg_data_especial[$c_re_esp][4] = $semanas_xmes[$s];
                                            $arrg_data_especial[$c_re_esp][5] = $arrg_cli_especial[$a][$cli][2];
                                            $arrg_data_especial[$c_re_esp][6] = $arrg_cli_especial[$a][$cli][3];
                                            $arrg_data_especial[$c_re_esp][7] = $arrg_cli_especial[$a][$cli][4];
                                            $arrg_data_especial[$c_re_esp][8] = $arrg_cli_especial[$a][$cli][5];
                                            $arrg_data_especial[$c_re_esp][9] = $arrg_cli_especial[$a][$cli][6];
                                            $arrg_data_especial[$c_re_esp][10] = $arrg_cli_especial[$a][$cli][7];
                                            $arrg_data_especial[$c_re_esp][11] = $arrg_cli_especial[$a][$cli][8];
                                            $arrg_data_especial[$c_re_esp][12] = $arrg_cli_especial[$a][$cli][9];
                                            $c_re_esp++;
                                        }
                                    }

                                    // echo 

                                    // var_dump($arrg_cli_especial[$cli]);

                                    // for ($f=0; $f < $t_s_dia_fecha ; $f++) { 
                                        // var_dump($arrg_fecha_X_Mes[$mes][$semanas_xmes[$s]][$a][$f]);
                                    // }

                                    



                                }

                                
                                
                                // $conta++;

                                // if($conta == 1){
                                //     $arrg_fecha_X_Mes[$mes][1][$a]
                                // }

                            }

                            // var_dump($this->SemanasMes('2023-03-10'));

                            // echo $mes = date('n', strtotime($arrg_meses[$m]));
                            
                            // $t_x_mes_x_sem = count($arrg_fecha_X_Mes[$mes][1][$a]);

                            
                            // for ($se=0; $se < ; $se++) { 
                            //     $arrg_fecha_X_Mes[$mes][]
                            // }

                            // echo 'TOTAL POR MES '.$t_x_mes_x_sem."<BR>";

                            // var_dump($arrg_fecha_X_Mes[$mes]);
                            // echo 'mes '.$arrg_fecha_X_Mes[$mes]."<br>";
                            // echo "mes ".$arrg_fecha_X_Mes[$m][1][$a][$m];
                            // var_dump($arrg_fecha_X_Mes[$mes]);
                            
                        }


                    // }
                
                
                }
            }


            
        }


        // var_dump($arrg_data_especial);

        # FUNCIONAR ARREGLOS
        $arrg_FUSION = array();


        $arrg_FUSION = array_merge($arrg_data, $arrg_data_especial);
        // var_dump($arrg_FUSION);
       

        // echo "<br>Resultado Imprimir excel <br>";
        // var_dump($arrg_data);
        // echo "<br>";

        /*
              PREPARAR EL REPORTE
        */
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        /*000000000000---ENCABEZADO---0000000000000000000*/
        $sheet->getStyle('A1:L1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
        $sheet->getStyle('A1:L1')->getFont()->setBold( true );
        $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
        $sheet->setCellValue('A1','Ruta');
        $sheet->setCellValue('B1','Codigo');
        $sheet->setCellValue('C1','Fecha');
        $sheet->setCellValue('D1','Semana');

        $sheet->setCellValue('E1','LU');
        $sheet->setCellValue('F1','MA');
        $sheet->setCellValue('G1','MI');
        $sheet->setCellValue('H1','JU');
        $sheet->setCellValue('I1','VI');
        $sheet->setCellValue('J1','SA');
        $sheet->setCellValue('K1','DO');
        $sheet->setCellValue('L1','FRECUENCIA_VISITA');

        $ctt = 2;
        foreach ($arrg_FUSION as $val){

            // echo $val[1]."<br>";
            $sheet->setCellValue('A'.$ctt,$val[5]);
            $sheet->setCellValue('B'.$ctt,$val[0]);
            $sheet->setCellValue('C'.$ctt,$val[1]);
            $sheet->setCellValue('D'.$ctt,$val[4]);

            $sheet->setCellValue('E'.$ctt,$val[6]);
            $sheet->setCellValue('F'.$ctt,$val[7]);
            $sheet->setCellValue('G'.$ctt,$val[8]);
            $sheet->setCellValue('H'.$ctt,$val[9]);
            $sheet->setCellValue('I'.$ctt,$val[10]);
            $sheet->setCellValue('J'.$ctt,$val[11]);
            $sheet->setCellValue('K'.$ctt,$val[12]);
            $sheet->setCellValue('L'.$ctt,$val[2]);
            $ctt++;
        }

        $fecha_actual = date('d-m-Y');
        $nombre_archivo = 'ITINERARIO_CLIENTES_'.$fecha_actual.'.xlsx';
        $writer = new Xlsx($spreadsheet);
        // $writer->save($nombre_archivo);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'-reporte-excel.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2014 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');


        // var_dump($arrg_data);


        /*---------------------------------------------------------------------*/


        // $nombre_archivo = 'PLANTILLA_CLIENTES.xlsx';
        // $writer = new Xlsx($spreadsheet);
        // $writer->save($nombre_archivo);
        // $spreadsheet->disconnectWorksheets();
        // unset($spreadsheet);

        // var_dump($arrginsert);
        // echo $this->cl->ingresar_clientes_muchos($arrginsert);
    }


    function distanciaCodigos($lat1, $lon1, $lat2, $lon2, $unit,$codigo) {
 
        // echo "<br>"." ".$codigo." | ".$lon1." | ".$lon2;

        $theta = $lon1 - $lon2;
        
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        // echo "<br>".$dist."<br>";
        // $dist = acos($dist);
        $dist = acos(min(max($dist,-1.0),1.0)); 
        // echo "<br>".$dist."<br>"; 
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
       
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    function DistaciaEntrePuntosGPS(){
        $rutaArchivo = 'RECODIFICACION/DISTANCIACLIENTES.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $highestRow = $hojaActual->getHighestRow();
        $Total_Registros = $highestRow -1;

        $arrgCliRu = array();
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        /*000000000000---ENCABEZADO---0000000000000000000*/
        $sheet->getStyle('A1:K1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
        $sheet->getStyle('A1:K1')->getFont()->setBold( true );
        // $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
        $sheet->setCellValue('A1','Ruta');
        $sheet->setCellValue('B1','Codigo');
        $sheet->setCellValue('C1','Nombre');
        $sheet->setCellValue('D1','Direccion_RT');
        $sheet->setCellValue('E1','Direccion_SDV');
        $sheet->setCellValue('F1','Latitud_RT');
        $sheet->setCellValue('G1','Longitud_RT');
        $sheet->setCellValue('H1','Latitud_SDV');
        $sheet->setCellValue('I1','Longitud_SDV');
        $sheet->setCellValue('J1','Diferencia (m)');
        $sheet->setCellValue('K1','Estado');

        for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {

            $codigo    = $hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
            $ruta      = str_replace(".", "", $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue());
            $latUnoRT  = $hojaActual->getCell("AA".$indiceHoja)->getCalculatedValue();
            $lotUnoSRT = $hojaActual->getCell("AB".$indiceHoja)->getCalculatedValue();
            $direccionRT = $hojaActual->getCell("E".$indiceHoja)->getCalculatedValue();
            $NombreRT = $hojaActual->getCell("D".$indiceHoja)->getCalculatedValue();
            $latDosSDV = '';
            $lotDosSDV = '';
            $direccionSDV = '';
            $distancia = 0;
            $estado = '';

            $r_lat = strval(substr($latUnoRT, 0,1));

            if( $r_lat == '-' ){
                $latUnoRT  = $hojaActual->getCell("AB".$indiceHoja)->getCalculatedValue();
                $lotUnoSRT =$hojaActual->getCell("AA".$indiceHoja)->getCalculatedValue();
            }

            $param = array(
                'Ruta'   => $ruta,
                'Codigo' => $codigo
            );

            $infoC = $this->cl->ListaClientexRutas($param);
            // echo str_replace(".", "", $hojaActual->getCell("AC".$indiceHoja)->getCalculatedValue())." ".$hojaActual->getCell("C".$indiceHoja)->getCalculatedValue();
            
            if( count($infoC) > 0 ){
               
                $latDosSDV = $infoC[0]->Cli_latitud;
                $lotDosSDV = $infoC[0]->Cli_longitud;
                $direccionSDV = $infoC[0]->Cli_direccion;
                $r_latsdv = strval(substr($latDosSDV, 0,1));
                if( $r_latsdv == '-' ){
                    $latDosSDV  = $infoC[0]->Cli_longitud;
                    $lotDosSDV = $infoC[0]->Cli_latitud;
                }

                $estado = 'CORRECTO';
                $distancia = $this->distanciaCodigos($latDosSDV,$lotDosSDV,$latUnoRT,$lotUnoSRT,"K",$codigo);
                // echo "<br>".$distancia."<br>";
                $distancia = $distancia * 1000;
                // echo "<br>Distancia: ".$distancia;

                if($distancia>10){
                    $estado = 'REVISAR';
                }

        
                $sheet->setCellValue('A'.$indiceHoja,$ruta);
                $sheet->setCellValue('B'.$indiceHoja,$codigo);
                $sheet->setCellValue('C'.$indiceHoja,$NombreRT);
                $sheet->setCellValue('D'.$indiceHoja,$direccionRT);
                $sheet->setCellValue('E'.$indiceHoja,$direccionSDV);
                $sheet->setCellValue('F'.$indiceHoja,$latUnoRT);
                $sheet->setCellValue('G'.$indiceHoja,$lotUnoSRT);
                $sheet->setCellValue('H'.$indiceHoja,$latDosSDV);
                $sheet->setCellValue('I'.$indiceHoja,$lotDosSDV);
                $sheet->setCellValue('J'.$indiceHoja,$distancia);
                $sheet->setCellValue('K'.$indiceHoja,$estado);


            }else{
                // echo "<br>Cliente no existe en SDV";
                        
                $sheet->setCellValue('A'.$indiceHoja,$ruta);
                $sheet->setCellValue('B'.$indiceHoja,$codigo);
                $sheet->setCellValue('C'.$indiceHoja,$NombreRT);
                $sheet->setCellValue('D'.$indiceHoja,$direccionRT);
                $sheet->setCellValue('E'.$indiceHoja,$direccionSDV);
                $sheet->setCellValue('F'.$indiceHoja,$latUnoRT);
                $sheet->setCellValue('G'.$indiceHoja,$lotUnoSRT);
                $sheet->setCellValue('H'.$indiceHoja,$latDosSDV);
                $sheet->setCellValue('I'.$indiceHoja,$lotDosSDV);
                $sheet->setCellValue('J'.$indiceHoja,$distancia);
                $sheet->setCellValue('K'.$indiceHoja,'NO EXISTE EN SDV');
            }
            // echo "<br>-------------------------------------------------------------------------------<br>";
            



        }

        $fecha_actual = date('d-m-Y');
        $nombre_archivo = 'DISTANCIA_CLIENTES'.$fecha_actual.'.xlsx';;
        $writer = new Xlsx($spreadsheet);





        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2014 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');


            /**
             * LISTA DE CLINTES SDV
             */

            // $arrg_busquedaRutas = array(1136,1144,1135);
            // $param = array(
            //     'Ruta' => 
            // )
            // $listClientes = $this->cl->ListaClientexRutas($arrg_busquedaRutas);
    
            // var_dump($listClientes);

    }

    function DistanciaEntrePuntosGPS_Kobo(){


        $res_credits = json_decode( file_get_contents( "https://kf.kobotoolbox.org/assets/a7BCaGa8LPjA2LvbkgCKNw/submissions/?format=json"), true);


        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        /*000000000000---ENCABEZADO---0000000000000000000*/
        $sheet->getStyle('A1:L1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
        $sheet->getStyle('A1:L1')->getFont()->setBold( true );
        // $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
        $sheet->setCellValue('A1','ID');
        $sheet->setCellValue('B1','Codigo_Kobo');
        $sheet->setCellValue('C1','NombreKobo');
        $sheet->setCellValue('D1','Direccion_Kobo');
        $sheet->setCellValue('E1','Codigo_SDV');
        $sheet->setCellValue('F1','Nombre_SDV');
        $sheet->setCellValue('G1','Direccion_SDV');
        $sheet->setCellValue('H1','Latitud_Kobo');
        $sheet->setCellValue('I1','Longitud_Kobo');
        $sheet->setCellValue('J1','Latitud_SDV');
        $sheet->setCellValue('K1','Longitud_SDV');
        $sheet->setCellValue('L1','Distancia (m)');
        // var_dump($res_credits);
        // echo count($res_credits);
        $c = 0;
        $referencia_kobo = '';
        $nombre_kobo = '';
        $distancia = 0.05;
        $IdRegistro = 1;
        $Cregistro = 2;
        $Cencontrado = 0;
        $arrgData =  array();
        foreach ($res_credits as $key) {
            $ContaArrg = 0;
            $latiKobo = '';
            $longKobo = '';
            if(isset($key['_geolocation'])){
                // $infoC = $this->xa->List_Clientes();
                // $coordenadas = explode(",",$key['_geolocation']);
                // echo $coordenadas[0]"<br>";
                // var_dump($key['_geolocation']);
                $latiKobo = $key['_geolocation'][0];
                $longKobo = $key['_geolocation'][1];

                $ClientesCerca = $this->xa->List_Clientes($latiKobo,$longKobo,$distancia);

                
                // $r_latsdv = strval(substr($latDosSDV, 0,1));
                // if( $r_latsdv == '-' ){
                //     $latDosSDV  = $infoC[0]->Cli_longitud;
                //     $lotDosSDV = $infoC[0]->Cli_latitud;
                // }
               
                if(!isset($key['_1_Nombre_del_negocio'])){
                    $NombreKobo = '';
                }else{
                    $NombreKobo = $key['_1_Nombre_del_negocio'];
                }
                if(!isset($key['_21_C_digo_de_client_vendedor_de_Bocadeli'])){
                    $codigoCliKobo = '0';
                }else{
                    $codigoCliKobo = $key['_21_C_digo_de_client_vendedor_de_Bocadeli'];
                }
                if(!isset($key['_5_Barrio_Colonia_Ca_Caser_o_Lotificaci_n'])){
                    $barrioKobo = '';
                }else{
                    $barrioKobo = $key['_5_Barrio_Colonia_Ca_Caser_o_Lotificaci_n'];
                }
                if(!isset($key['_6_Calle_Avenida_Sector'])){
                    $avenidaKobo = '';
                }else{
                    $avenidaKobo = $key['_6_Calle_Avenida_Sector'];
                }
                if(!isset($key['_7_Pasaje_Block_Pol_gono_Senda'])){
                    $pasajeKobo = '';
                }else{
                    $pasajeKobo = $key['_7_Pasaje_Block_Pol_gono_Senda'];
                }
                if(!isset($key['_8_N_de_casa_local'])){
                    $n_casaKobo = '';
                }else{
                    $n_casaKobo = $key['_8_N_de_casa_local'];
                }
                if(!isset($key['_9_N_Apartamento'])){
                    $apartamentoKobo = '';
                }else{
                    $apartamentoKobo = $key['_9_N_Apartamento'];
                }
                $direccionKobo = $barrioKobo." ".$avenidaKobo." ".$pasajeKobo." ".$n_casaKobo." ".$apartamentoKobo;
                foreach ($ClientesCerca as $keySDV) {
                    // echo $keySDV->Cli_codigo;
                    $sheet->setCellValue('A'.$Cregistro,$IdRegistro);
                    $sheet->setCellValue('B'.$Cregistro,$codigoCliKobo);
                    $sheet->setCellValue('C'.$Cregistro,$NombreKobo);
                    $sheet->setCellValue('D'.$Cregistro,$direccionKobo);
                    $sheet->setCellValue('E'.$Cregistro,$keySDV->Cli_codigo);
                    $sheet->setCellValue('F'.$Cregistro,$keySDV->Cli_nombre);
                    $sheet->setCellValue('G'.$Cregistro,$keySDV->Cli_direccion);
                    $sheet->setCellValue('H'.$Cregistro,$latiKobo);
                    $sheet->setCellValue('I'.$Cregistro,$longKobo);
                    $sheet->setCellValue('J'.$Cregistro,$keySDV->Cli_latitud);
                    $sheet->setCellValue('K'.$Cregistro,$keySDV->Cli_longitud);
                    $sheet->setCellValue('L'.$Cregistro,$keySDV->distance*1000);
                    $Cregistro++;
                    $IdRegistro++;
                }

            }else{
                $sheet->setCellValue('A'.$Cregistro,$IdRegistro);
                $sheet->setCellValue('B'.$Cregistro,$codigoCliKobo);
                $sheet->setCellValue('C'.$Cregistro,$NombreKobo);
                $sheet->setCellValue('D'.$Cregistro,$direccionKobo);
                $sheet->setCellValue('E'.$Cregistro,0);
                $sheet->setCellValue('F'.$Cregistro,0);
                $sheet->setCellValue('G'.$Cregistro,0);
                $sheet->setCellValue('H'.$Cregistro,0);
                $sheet->setCellValue('I'.$Cregistro,0);
                $sheet->setCellValue('J'.$Cregistro,0);
                $sheet->setCellValue('K'.$Cregistro,0);
                $sheet->setCellValue('L'.$Cregistro,'SIN COORDENADAS EN KOBO');
                $Cregistro++;
                $IdRegistro++;
                // echo "No hay coordenadas en el registro ".$key['_id']."<br>";
            }
            // $Cregistro++;
        }
        // echo $c;

        // $param = array(
        //     'Ruta'   => $ruta,
        //     'Codigo' => $codigo
        // );

        $fecha_actual = date('d-m-Y');
        $nombre_archivo = 'DISTANCIA_CLIENTES_KOBO_'.$fecha_actual.'.xlsx';
        $writer = new Xlsx($spreadsheet);
        // $writer->save($nombre_archivo);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'-reporte-excel.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2014 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');






    }



    function EncontrarClientes_Kobo(){


        // $res_credits = json_decode( file_get_contents( "https://kf.kobotoolbox.org/assets/a7BCaGa8LPjA2LvbkgCKNw/submissions/?format=json"), true);


        $rutaArchivo = 'RECODIFICACION/KOBO_31-03-2023.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $highestRow = $hojaActual->getHighestRow();
        $Total_Registros = $highestRow -1;
        $arrgKobo = array();$ckb = 0;

        for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {

            $arrgKobo[$ckb]['Codigo_Kobo'] = $hojaActual->getCell("AI".$indiceHoja)->getCalculatedValue();
            $arrgKobo[$ckb]['Nombre_Kobo'] = $hojaActual->getCell("AJ".$indiceHoja)->getCalculatedValue();
            $arrgKobo[$ckb]['Direccion_Kobo'] = $hojaActual->getCell("AK".$indiceHoja)->getCalculatedValue();
            $arrgKobo[$ckb]['Oportunidades_Kobo'] = $hojaActual->getCell("BO".$indiceHoja)->getCalculatedValue();  
            $arrgKobo[$ckb]['Latitud_Kobo'] = $hojaActual->getCell("BR".$indiceHoja)->getCalculatedValue();  
            $arrgKobo[$ckb]['Longitud_Kobo'] = $hojaActual->getCell("BS".$indiceHoja)->getCalculatedValue();   
            $ckb++;

        }

        // var_dump($arrgKobo);


        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        /*000000000000---ENCABEZADO---0000000000000000000*/
        $sheet->getStyle('A1:M1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
        $sheet->getStyle('A1:M1')->getFont()->setBold( true );
        // $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
        $sheet->setCellValue('A1','ID');
        $sheet->setCellValue('B1','Codigo_Kobo');
        $sheet->setCellValue('C1','Nombre_Kobo');
        $sheet->setCellValue('D1','Direccion_Kobo');
        $sheet->setCellValue('E1','Codigo_SDV');
        $sheet->setCellValue('F1','Nombre_SDV');
        $sheet->setCellValue('G1','Direccion_SDV');
        $sheet->setCellValue('H1','Latitud_Kobo');
        $sheet->setCellValue('I1','Longitud_Kobo');
        $sheet->setCellValue('J1','Latitud_SDV');
        $sheet->setCellValue('K1','Longitud_SDV');
        $sheet->setCellValue('L1','Distancia (m)');
        $sheet->setCellValue('M1','OPORTUNIDADES ENCONTRADAS/SEGUIMIENTO-ACCIONES REALIZADAS');
        $c = 0;
        $referencia_kobo = '';
        $nombre_kobo = '';
        $distancia = 0.05;
        $IdRegistro = 1;
        $Cregistro = 2;
        $Cencontrado = 0;
        $arrgData =  array();
        foreach ($arrgKobo as $key) {
            $ContaArrg = 0;
            $latiKobo = '';
            $longKobo = '';

                $latiKobo = $key['Latitud_Kobo'];
                $longKobo = $key['Longitud_Kobo'];
                $oportuni = $key['Oportunidades_Kobo'];

                $ClientesCerca = $this->xa->List_Clientes($latiKobo,$longKobo,$distancia);

                if(!isset($key['Nombre_Kobo'])){
                    $NombreKobo = '';
                }else{
                    $NombreKobo = $key['Nombre_Kobo'];
                }
                if(!isset($key['Codigo_Kobo'])){
                    $codigoCliKobo = '0';
                }else{
                    $codigoCliKobo = $key['Codigo_Kobo'];
                }

                $direccionKobo = $key['Direccion_Kobo'];
                foreach ($ClientesCerca as $keySDV) {
                    // echo $keySDV->Cli_codigo;
                    $sheet->setCellValue('A'.$Cregistro,$IdRegistro);
                    $sheet->setCellValue('B'.$Cregistro,$codigoCliKobo);
                    $sheet->setCellValue('C'.$Cregistro,$NombreKobo);
                    $sheet->setCellValue('D'.$Cregistro,$direccionKobo);
                    $sheet->setCellValue('E'.$Cregistro,$keySDV->Cli_codigo);
                    $sheet->setCellValue('F'.$Cregistro,$keySDV->Cli_nombre);
                    $sheet->setCellValue('G'.$Cregistro,$keySDV->Cli_direccion);
                    $sheet->setCellValue('H'.$Cregistro,$latiKobo);
                    $sheet->setCellValue('I'.$Cregistro,$longKobo);
                    $sheet->setCellValue('J'.$Cregistro,$keySDV->Cli_latitud);
                    $sheet->setCellValue('K'.$Cregistro,$keySDV->Cli_longitud);
                    $sheet->setCellValue('L'.$Cregistro,round($keySDV->distance*1000,2));
                    $sheet->setCellValue('M'.$Cregistro,$oportuni);
                    $Cregistro++;
                    $IdRegistro++;
                }

        }


        $fecha_actual = date('d-m-Y');
        $nombre_archivo = 'DISTANCIA_CLIENTES_KOBO_'.$fecha_actual.'.xlsx';
        $writer = new Xlsx($spreadsheet);


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombre_archivo.'-reporte-excel.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Fri, 11 Nov 2014 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');






    }


    function EncontrarClientes_KoboTres(){
      
        
        $res_credits = json_decode( file_get_contents( "https://kf.kobotoolbox.org/assets/a7BCaGa8LPjA2LvbkgCKNw/submissions/?format=json"), true);
        $data_insert = array();$ct = 0;

        foreach ($res_credits as $key) {

            // $latiKobo = $key['_geolocation'][0];
            // $longKobo = $key['_geolocation'][1];

            if(!isset($key['_geolocation'][0])){
                $latiKobo = '';
            }else{
                $latiKobo = $key['_geolocation'][0];
            }

            if(!isset($key['_geolocation'][1])){
                $longKobo = '';
            }else{
                $longKobo = $key['_geolocation'][1];
            }

            if(!isset($key['_1_Nombre_del_negocio'])){
                $NombreKobo = '';
            }else{
                $NombreKobo = $key['_1_Nombre_del_negocio'];
            }
            if(!isset($key['_21_C_digo_de_client_vendedor_de_Bocadeli'])){
                $codigoCliKobo = '0';
            }else{
                $codigoCliKobo = $key['_21_C_digo_de_client_vendedor_de_Bocadeli'];
            }
            if(!isset($key['_5_Barrio_Colonia_Ca_Caser_o_Lotificaci_n'])){
                $barrioKobo = '';
            }else{
                $barrioKobo = $key['_5_Barrio_Colonia_Ca_Caser_o_Lotificaci_n'];
            }
            if(!isset($key['_6_Calle_Avenida_Sector'])){
                $avenidaKobo = '';
            }else{
                $avenidaKobo = $key['_6_Calle_Avenida_Sector'];
            }
            if(!isset($key['_7_Pasaje_Block_Pol_gono_Senda'])){
                $pasajeKobo = '';
            }else{
                $pasajeKobo = $key['_7_Pasaje_Block_Pol_gono_Senda'];
            }
            if(!isset($key['_8_N_de_casa_local'])){
                $n_casaKobo = '';
            }else{
                $n_casaKobo = $key['_8_N_de_casa_local'];
            }
            if(!isset($key['_9_N_Apartamento'])){
                $apartamentoKobo = '';
            }else{
                $apartamentoKobo = $key['_9_N_Apartamento'];
            }
            $direccionKobo = $barrioKobo." ".$avenidaKobo." ".$pasajeKobo." ".$n_casaKobo." ".$apartamentoKobo;

            $data_insert[$ct]['Cli_codigo'] = $codigoCliKobo;
            $data_insert[$ct]['Cli_nombre'] = $NombreKobo;
            $data_insert[$ct]['Cli_direccion'] = $direccionKobo;
            $data_insert[$ct]['Cli_latitud'] = $latiKobo;
            $data_insert[$ct]['Cli_longitud'] = $longKobo;
            $ct++;
        }


        // var_dump($data_insert);


        $ClientesCerca = $this->cl->guardarTempoClientes($data_insert);

        // if( count($ClientesCerca) > 0){
            

            $rutaArchivo = 'RECODIFICACION/KOBO_31-03-2023.xlsx';
            $documento = IOFactory::load($rutaArchivo);
            $hojaActual = $documento->getSheet(0);
            $highestRow = $hojaActual->getHighestRow();
            $Total_Registros = $highestRow -1;
            $arrgKobo = array();$ckb = 0;
    
            for ($indiceHoja = 2; $indiceHoja <= $highestRow; $indiceHoja++) {
    
                $arrgKobo[$ckb]['Codigo_Kobo'] = $hojaActual->getCell("AI".$indiceHoja)->getCalculatedValue();
                $arrgKobo[$ckb]['Nombre_Kobo'] = $hojaActual->getCell("AJ".$indiceHoja)->getCalculatedValue();
                $arrgKobo[$ckb]['Direccion_Kobo'] = $hojaActual->getCell("AK".$indiceHoja)->getCalculatedValue();
                $arrgKobo[$ckb]['Oportunidades_Kobo'] = $hojaActual->getCell("BO".$indiceHoja)->getCalculatedValue();  
                $arrgKobo[$ckb]['Latitud_Kobo'] = $hojaActual->getCell("BR".$indiceHoja)->getCalculatedValue();  
                $arrgKobo[$ckb]['Longitud_Kobo'] = $hojaActual->getCell("BS".$indiceHoja)->getCalculatedValue();   
                $ckb++;
    
            }

            // var_dump($arrgKobo);
    

            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            /*000000000000---ENCABEZADO---0000000000000000000*/
            $sheet->getStyle('A1:M1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
            $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B4C6E7');
            $sheet->getStyle('A1:M1')->getFont()->setBold( true );
            // $sheet->getColumnDimension('L')->setWidth(14);
            $sheet->getParent()->getDefaultStyle()->getFont()->setName('Microsoft JhengHei Light')->setSize(11);
            $sheet->setCellValue('A1','ID');
            $sheet->setCellValue('B1','Codigo_Kobo');
            $sheet->setCellValue('C1','Nombre_Kobo');
            $sheet->setCellValue('D1','Direccion_Kobo');
            $sheet->setCellValue('E1','Codigo_SDV');
            $sheet->setCellValue('F1','Nombre_SDV');
            $sheet->setCellValue('G1','Direccion_SDV');
            $sheet->setCellValue('H1','Latitud_Kobo');
            $sheet->setCellValue('I1','Longitud_Kobo');
            $sheet->setCellValue('J1','Latitud_SDV');
            $sheet->setCellValue('K1','Longitud_SDV');
            $sheet->setCellValue('L1','Distancia (m)');
            $sheet->setCellValue('M1','OPORTUNIDADES ENCONTRADAS/SEGUIMIENTO-ACCIONES REALIZADAS');
            $c = 0;
            $referencia_kobo = '';
            $nombre_kobo = '';
            $distancia = 0.05;
            $IdRegistro = 1;
            $Cregistro = 2;
            $Cencontrado = 0;
            $arrgData =  array();
            foreach ($arrgKobo as $keyy) {
                $ContaArrg = 0;
                $latiKobo = '';
                $longKobo = '';
    
                    $latiKobo = $keyy['Latitud_Kobo'];
                    $longKobo = $keyy['Longitud_Kobo'];
                    $oportuni = $keyy['Oportunidades_Kobo'];
    
                    $ClientesCerca = $this->cl->List_ClientesGPS($latiKobo,$longKobo,$distancia);
    
                    if(!isset($keyy['Nombre_Kobo'])){
                        $NombreKobo = '';
                    }else{
                        $NombreKobo = $keyy['Nombre_Kobo'];
                    }
                    if(!isset($keyy['Codigo_Kobo'])){
                        $codigoCliKobo = '0';
                    }else{
                        $codigoCliKobo = $keyy['Codigo_Kobo'];
                    }
    
                    $direccionKobo = $keyy['Direccion_Kobo'];
                    // var_dump($ClientesCerca);
                    foreach ($ClientesCerca as $keySDV) {
                        // echo $keySDV->Cli_codigo;
                        $sheet->setCellValue('A'.$Cregistro,$IdRegistro);
                        $sheet->setCellValue('B'.$Cregistro,$codigoCliKobo);
                        $sheet->setCellValue('C'.$Cregistro,$NombreKobo);
                        $sheet->setCellValue('D'.$Cregistro,$direccionKobo);
                        $sheet->setCellValue('E'.$Cregistro,$keySDV->Cli_codigo);
                        $sheet->setCellValue('F'.$Cregistro,$keySDV->Cli_nombre);
                        $sheet->setCellValue('G'.$Cregistro,$keySDV->Cli_direccion);
                        $sheet->setCellValue('H'.$Cregistro,$latiKobo);
                        $sheet->setCellValue('I'.$Cregistro,$longKobo);
                        $sheet->setCellValue('J'.$Cregistro,$keySDV->Cli_latitud);
                        $sheet->setCellValue('K'.$Cregistro,$keySDV->Cli_longitud);
                        $sheet->setCellValue('L'.$Cregistro,round($keySDV->distance*1000,2));
                        $sheet->setCellValue('M'.$Cregistro,$oportuni);
                        $Cregistro++;
                        $IdRegistro++;
                    }
    
            }
    

            $fecha_actual = date('d-m-Y');
            $nombre_archivo = 'DISTANCIA_CLIENTES_KOBO_'.$fecha_actual.'.xlsx';
            $writer = new Xlsx($spreadsheet);
    
    
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$nombre_archivo.'-reporte-excel.xlsx"');
            header('Cache-Control: max-age=0');
            header('Expires: Fri, 11 Nov 2014 11:11:11 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            $writer->save('php://output');
    

    

            // $fecha_actual = date('d-m-Y');
            // $nombre_archivo = 'DISTANCIA_CLIENTES_KOBO_'.$fecha_actual;
            // $writer = new Xlsx($spreadsheet);
    
    
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
            // header('Cache-Control: max-age=0');
            // header('Expires: Fri, 11 Nov 2014 11:11:11 GMT');
            // header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            // header('Cache-Control: cache, must-revalidate');
            // header('Pragma: public');
            // $writer->save('php://output');
    

            // $fecha_actual = date('d-m-Y');
            // $nombre_archivo = 'DISTANCIA_CLIENTES_KOBO_'.$fecha_actual.'.xlsx';
            // $writer = new Xlsx($spreadsheet);
            // // $writer->save($nombre_archivo);
    
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="'.$nombre_archivo.'-reporte-excel.xlsx"');
            // header('Cache-Control: max-age=0');
            // header('Expires: Fri, 11 Nov 2014 11:11:11 GMT');
            // header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            // header('Cache-Control: cache, must-revalidate');
            // header('Pragma: public');
            // $writer->save('php://output');
    
    




        // }

    }



}



?>