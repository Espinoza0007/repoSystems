<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
date_default_timezone_set('America/El_Salvador');
ini_set('max_execution_time', 0);
class Ctr_reporte_exh extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_exhibidores/Mdl_Reporte_Exh','Rexh');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }
	function index(){
        $this->global['pageTitle'] = 'Reporte Exhibidores';
        $this->loadViews_gerencia('Reportes/V_reporte_exh',$this->global);
  	}
    function Lista_TablaClteAfiches(){
        if($this->input->is_ajax_request()){
            if(!empty($this->input->post('page'))){
                $limit     = 20;
                $adjacent  = 1;
                $page      = $this->input->post('page');
                $va        = '';
                $x         = 0;
                if($page==1){
                    $start = 0;
                }else{
                    $start = ($page-1)*$limit;
                }
                $param_pais            = '';
                $param_pais            = $this->input->post('filtropais');
                $param_division        = $this->input->post('filtrodivision');
                $param_canal           = $this->input->post('filtrocanales');
                $param_distritro       = $this->input->post('filtrodistritos');
                $param_ruta            = $this->input->post('filtrorutas');
                $param_codigo          = $this->input->post('filtrocodigos');
                $param_rutas           = $this->input->post('filtrorutas');
                $param_tipoexh         = $this->input->post('filtrotipoexhibidor');
                $param_exhibidores     = $this->input->post('filtroexhibidores');
                if($this->input->post('clean') == 1){
                    $param_pais        = '';
                    $param_division    = '';
                    $param_canal       = '';
                    $param_distritro   = '';
                    $param_rutas       = '';
                    $param_tipoexh     = '';
                    $param_exhibidores = '';
                    $param_codigo      = '';
                }
                $param_busqueda = array(
                    'pais'             => $param_pais,
                    'division'         => $param_division,
                    'canal'            => $param_canal,
                    'grupo'            => $param_distritro,
                    'codigo'           => $param_codigo,
                    'rutas'            => $param_rutas,
                    'tipoexhibidores'  => $param_tipoexh,
                    'exhibidores'      => $param_exhibidores
                );
                $Total_clientes        = $this->Rexh->Total_ClteAfiches($param_busqueda);
                $Toto                  = $Total_clientes->totalresultados;
                $ls_clte_censados      = $this->Rexh->list_cltesConAfiche($start,$limit,$param_busqueda);$arrg_lista_tabla = array();
                $paginado_ls_clte      = $this->pagination_tblClteAfiche($limit,$adjacent,$Toto,$page);
                foreach ($ls_clte_censados as $val){
                    $arrg_lista_tabla[$x]['Id_Cliente']       = $val->Ste_Cli_Id;
                    $arrg_lista_tabla[$x]['Nombre_Ruta']      = $val->Ru_nombre;
                    $arrg_lista_tabla[$x]['CodigoCliente']    = $val->Cli_codigo;
                    $arrg_lista_tabla[$x]['NombreCliente']    = $val->Cli_nombre;
                    $arrg_lista_tabla[$x]['ContactoCliente']  = $val->Cli_contacto;
                    $arrg_lista_tabla[$x]['DireccionCliente'] = $val->Cli_Direccion;
                    $arrg_lista_tabla[$x]['Nombre_Pais']      = $val->P_nombre;
                    $arrg_lista_tabla[$x]['Division']         = $val->Di_nombre;
                    $arrg_lista_tabla[$x]['Canal']            = $val->Ca_nombre;
                    $arrg_lista_tabla[$x]['Grupo']            = $val->Dist_nombre;
                    //EXISTE FOTO ???
                    $fotourl = '';
                    $fotourl = strval($val->Cli_foto);
                    $fotourl = str_replace("../","",$fotourl);
                    if ( strcmp($fotourl, "NULL") == 0 || empty($fotourl) ){
                        $arrg_lista_tabla[$x]['FotoObservacion']     = 0;
                    }else{
                        if (!file_exists("../".$fotourl)) {
                            $arrg_lista_tabla[$x]['FotoObservacion'] = 0;
                        } else {
                            $arrg_lista_tabla[$x]['FotoObservacion'] = "../".$fotourl;
                        }
                    }
                    $x++;
                    $fotourl = '';
                }
                echo json_encode(array(
                    'rs'          => TRUE,
                    'ls_clientes' => $arrg_lista_tabla,
                    'paginado'    => $paginado_ls_clte,
                    'pedorra' => $Toto
                ));
            }else{
                $resp = array(
                    'rs'   => FALSE,
                    'info' => 'Por favor salir y volver a ingresar a la plataforma...'
                );
            }
        }else{
            $resp = array(
                'rs'   => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }
    function Expediente_ExhibidoresXcli(){
        if($this->input->is_ajax_request()){
            if(!empty($this->input->post('idx_cliente'))){
                $idx_cliente              = $this->input->post('idx_cliente');
                $Select_clte              = $this->Rexh->InfoCliExhibidores($idx_cliente);$arrg_xCliSeleccionado = array();$x = 0;
                $Select_ExhXcli_tiene     = $this->Rexh->ListExhibidoresBq($idx_cliente,1);$arrg_QTiene          = array();$eq = 0;
                $Select_ExhXcli_devueltos = $this->Rexh->ListExhibidoresBq($idx_cliente,2);$arrg_devu            = array();$de = 0;
                $Select_ExhXcli_nuevos    = $this->Rexh->ListExhibidoresBq($idx_cliente,3);
                foreach ($Select_clte as $val){
                    $arrg_xCliSeleccionado['Nombre_Ruta']      = $val->Ru_nombre;
                    $arrg_xCliSeleccionado['CodigoCliente']    = $val->Cli_codigo;
                    $arrg_xCliSeleccionado['NombreCliente']    = $val->Cli_nombre;
                    $arrg_xCliSeleccionado['DireccionCliente'] = $val->Cli_Direccion;
                    $arrg_xCliSeleccionado['ContactoCliente']  = $val->Cli_contacto;
                    $arrg_xCliSeleccionado['Telefono']         = $val->Ste_telefono_cli;
                    $arrg_xCliSeleccionado['Nombre_Pais']      = $val->P_nombre;
                    $arrg_xCliSeleccionado['Canal']            = $val->Ca_nombre;
                    $arrg_xCliSeleccionado['Division']         = $val->Di_nombre;
                    $arrg_xCliSeleccionado['Cli_latitud']      = $val->Cli_latitud;
                    $arrg_xCliSeleccionado['Cli_longitud']     = $val->Cli_longitud;
                    $arrg_xCliSeleccionado['Ste_latitud_obs']  = $val->Ste_latitud_obs;
                    $arrg_xCliSeleccionado['Ste_longitud_obs'] = $val->Ste_longitud_obs;
                }
                $Select_ExhXcli_tiene = array_merge($Select_ExhXcli_tiene, $Select_ExhXcli_nuevos);
                echo json_encode(array(
                    'rs'           => TRUE,
                    'xcliente'     => $arrg_xCliSeleccionado,
                    'xexhibidorqt' => $Select_ExhXcli_tiene,
                    'xexhibidorde' => $Select_ExhXcli_devueltos
                ));
            }else{
                $resp = array(
                    'rs'   => FALSE,
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
    function InfoExhSeleccionado(){
        if($this->input->is_ajax_request()){
            if(!empty($this->input->post('idx_exh'))){
                $idx_exh     = $this->input->post('idx_exh');
                $Select_clte = $this->Rexh->InfoExhibidorSlc($idx_exh);$arrg_info_exhibidor = array();$x = 0;
                $total       = count($Select_clte);
                foreach ($Select_clte as $val){
                    $arrg_info_exhibidor[$x]['Ste_latitud_obs']     = $val->Ste_latitud_obs;
                    $arrg_info_exhibidor[$x]['Ste_longitud_obs']    = $val->Ste_longitud_obs;
                    $arrg_info_exhibidor[$x]['Ste_Cat_Id']          = $val->Ste_Cat_Id;
                    $arrg_info_exhibidor[$x]['Ste_status']          = $val->Ste_status;
                    $arrg_info_exhibidor[$x]['Ste_estado']          = $val->Ste_estado;
                    $arrg_info_exhibidor[$x]['Ste_comentario']      = $val->Ste_comentario;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_RT']     = $val->Ste_cantidad_RT;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_PQ']     = $val->Ste_cantidad_PQ;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_PINES']  = $val->Ste_cantidad_PINES;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_UN']     = $val->Ste_cantidad_UN;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_BOLSAS'] = $val->Ste_cantidad_BOLSAS;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_CARAS']  = $val->Ste_cantidad_CARAS;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_PINES']  = $val->Ste_cantidad_PINES;
                    $arrg_info_exhibidor[$x]['Ste_cantidad_BOTES']  = $val->Ste_cantidad_BOTES;
                    $arrg_info_exhibidor[$x]['Ste_tipo_exhibidor']  = $val->Ste_tipo_exhibidor;
                    $arrg_info_exhibidor[$x]['Ste_fecha_tel']       = $val->Ste_fecha_tel;
                    $arrg_info_exhibidor[$x]['Ste_fecha_serv']      = $val->Ste_fecha_serv;
                    $arrg_info_exhibidor[$x]['Ste_ultima_fecha']    = $val->Ste_ultima_fecha;
                    $arrg_info_exhibidor[$x]['Ste_fecha_serv']      = $val->Ste_fecha_serv;
                    $arrg_info_exhibidor[$x]['Ste_fecha_serv']      = $val->Ste_fecha_serv;
                    $arrg_info_exhibidor[$x]['Ste_fecha_serv']      = $val->Ste_fecha_serv;
                    $arrg_info_exhibidor[$x]['Cli_latitud']         = $val->Cli_latitud;
                    $arrg_info_exhibidor[$x]['Cli_longitud']        = $val->Cli_longitud;
                    //EXISTE FOTO ???
                    $fotourl = '';
                    $fotourl = strval($val->Ste_foto);
                    $fotourl = str_replace("../","",$fotourl);
                    if ( strcmp($fotourl, "NULL") == 0 || empty($fotourl) ){
                        $arrg_info_exhibidor[$x]['Ste_foto']     = '../dependencias/imagenes/icon_256.png';
                    }else{
                        if (!file_exists("../".$fotourl)) {
                            $arrg_info_exhibidor[$x]['Ste_foto'] = '../dependencias/imagenes/icon_256.png';
                        } else {
                            $arrg_info_exhibidor[$x]['Ste_foto'] = "../../".$fotourl;
                        }
                    }
                    $x++;
                    $fotourl = '';
                }
                if( $total > 0 ){
                    echo json_encode(array(
                        'rs'      => TRUE,
                        'InfoExh' => $arrg_info_exhibidor
                    ));
                }else{
                    echo json_encode(array(
                        'rs'   => FALSE,
                        'info' => 'Error no se encontro el exhibidor seleccionado...'
                    ));
                }
            }else{
                echo json_encode(array(
                    'rs'   => FALSE,
                    'info' => 'Por favor salir y volver a ingresar a la plataforma...'
                ));
            }
        }else{
            echo json_encode(array(
                'rs'   => FALSE,
                'info' => 'Error desconocido...'
            ));
        }
        return;
    }
    function FiltrosBusqueda(){
        $param_pais            = '';
        $param_pais            = $this->input->post('filtropais');
        $param_division        = $this->input->post('filtrodivision');
        $param_canal           = $this->input->post('filtrocanales');
        $param_distritro       = $this->input->post('filtrodistritos');
        $param_rutas           = $this->input->post('filtrorutas');
        $param_tipoexh         = $this->input->post('filtrotipoexhibidor');
        $param_exhibidores     = $this->input->post('filtroexhibidores');
        $param_codigo          = $this->input->post('filtrocodigos');
        $param_opt_select      = $this->input->post('opt_select');
        if($this->input->post('clean') == 1){
            $param_pais        = '';
            $param_division    = '';
            $param_canal       = '';
            $param_distritro   = '';
            $param_rutas       = '';
            $param_tipoexh     = '';
            $param_exhibidores = '';
            $param_codigo      = '';
        }
        $param_busqueda = array(
            'pais'             => $param_pais,
            'division'         => $param_division,
            'canal'            => $param_canal,
            'grupo'            => $param_distritro,
            'rutas'            => $param_rutas,
            'tipoexhibidores'  => $param_tipoexh,
            'exhibidores'      => $param_exhibidores,
            'codigo'           => $param_codigo
        );
        $ls_arrg_select = array();
        if(      $param_opt_select == 1      ){
            $ls_arrg_select    = $this->Rexh->FiltroPais($param_busqueda);
        }elseif ($param_opt_select == 2){
            $ls_arrg_select    = $this->Rexh->FiltroDivision($param_busqueda);
        }elseif ($param_opt_select == 3){
            $ls_arrg_select    = $this->Rexh->FiltroCanal($param_busqueda);
        }elseif ($param_opt_select == 4){
            $ls_arrg_select    = $this->Rexh->FiltroDistrito($param_busqueda);
        }elseif ($param_opt_select == 5){
            $ls_arrg_select    = $this->Rexh->FiltroRuta($param_busqueda);
        }elseif ($param_opt_select == 6){
            $ls_arrg_select    = $this->Rexh->FiltroTipoExhibidores($param_busqueda);
        }elseif ($param_opt_select == 7){
            $ls_arrg_select    =  $this->Rexh->FiltroExhibidores($param_busqueda);
        }
        echo json_encode(array(
            'rs'               => TRUE,
            'ls_arrg_select'   => $ls_arrg_select
        ));
    }
    function pagination_tblClteAfiche($limit,$adjacents,$t,$page){
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