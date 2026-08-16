<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
class Ctr_menu extends ControladorBase{
    function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->model('M_reclamos/Mdl_reclamos','lr');
        $this->load->model('M_exhibidores/Mdl_exhibidores','exh');
        $this->load->model('M_control_inventario/Mdl_control_inventario','cti');
        $this->load->model('M_pedidos/Mdl_pedidos','pds');
        $this->load->model('M_login/Mdl_login','lg');
        $this->load->model('M_vehiculo/Mdl_vehiculo','vhe');
        $this->load->model('M_mercado/Mdl_mercado','mer');
        $this->load->model('M_venta/Mdl_venta','vnt');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','security'));
    }
    public function index(){
        $this->global['pageTitle'] = 'Menu Principal';
        $this->loadViews('Clientes/V_menu',$this->global);
    }
    public function sincronizacion_completa(){
        if($this->input->is_ajax_request()){
            $tipo_usuario       = $this->input->post('tipo_usuario');
            $ruta_desarrollador = $this->input->post('ruta_desarrollador');
            $Fecha_Telefono     = strtotime($this->input->post('Fecha_Telefono'));
            $Fecha_Telefono     = date('Y-m-d',$Fecha_Telefono);
            $ca_nombre   = '';
            $Ru_nombre   = '';
            $pass_on_off = 0;
            $usuario_log = 0;
            $usuario_log = $this->input->post('us_cod');
            if($tipo_usuario        == "15" || $tipo_usuario        == "116" || $tipo_usuario        == "155"){
                $usu_solicitado     = $this->cl->get_usuario_ruta($ruta_desarrollador);
                $ruta               = $ruta_desarrollador;
                $idusuario_NEW      = $usu_solicitado[0]->Usu_Id;
                $pais               = $usu_solicitado[0]->P_nombre;
                $canal_usu          = $usu_solicitado[0]->Ca_Id;
                $Ru_nombre          = $usu_solicitado[0]->Ru_nombre;
            }else{
                $ruta               = $this->input->post('us_ID_Ruta');
                $idusuario_NEW      = $this->input->post('us_cod');
                $pais               = $this->input->post('pais');
                $canal_usu          = $this->input->post('canal_usu');
                $Ru_nombre          = $this->input->post('NombreRuta');
                // $id_usuario_sdv     = $this->input->post('id_usuario_sdv');
            }
            $get_ca_nombre = $this->cl->get_canal_x_ruta($ruta);
            $ca_nombre     = $get_ca_nombre[0]->Ca_nombre;
            $param_consultas = array(
                'idusuario' => $idusuario_NEW,
                'ruta'      => $ruta
            );
            $data_us = $this->lg->param_usu($this->input->post('us_cod'));
            foreach ($data_us as $row){$pass_on_off = $row->Usu_act_contrasena;}
            $arrg_condicioncli      = array();
            $arrg_gironegocio       = array();
            $arrg_exhibidor         = array();
            $arrg_tfacturacion      = array();
            $arrg_tpuntoventa       = array();
            $arrg_departamento      = array();
            $arrg_municipio         = array();
            $arrg_exhibidoresfac    = array();
            $l_departamento         = $this->cl->list_departamento($pais);
            $l_municipio            = $this->cl->list_municipio_x_PAIS($pais);
            $l_tpuntoventa          = $this->cl->list_tpuntoventa();
            $l_gironegocio          = $this->cl->list_gironegocio_n();
            $l_condicioncli         = $this->cl->list_condicioncli();
            $l_tfacturacion         = $this->cl->list_tfacturacion();
            $l_clientes_todos       = $this->cl->Lista_de_clientes($idusuario_NEW,$pais,$ca_nombre);
            $l_productos_pais       = $this->lr->listadoProductosReclamos($canal_usu);
            $l_tipo_danos           = $this->lr->listadoTipoDanosReclamos(1);
            $ls_cti_ingresados      = $this->cti->ls_cti_ingresados($ruta);
            $ls_reclamos_enproceso  = $this->lr->ls_reclamos_enproceso($ruta);
            $l_det_exhibidores      = $this->exh->detalle_exhibidores($param_consultas);
            $l_tipo_exhibidores     = $this->exh->tipo_exhibidores();
            $ls_ste_tipo_motivos    = $this->exh->ls_ste_tipo_motivos();
            $ls_ste_motivos         = $this->exh->ls_ste_motivos();
            #---------------------------------------------------------------------------------------
            $ls_checklist_vehiculo  = $this->vhe->get_items_checklist_vehiculo();
            $ls_info_vehiculo       = $this->vhe->obtener_datos_vehiculo($ruta);
            $ls_tipo_licencia       = $this->vhe->obtener_tipo_licencias();
            $parametros_vnt         = array();
            $l_referencias          = $this->cl->clientes_referencias();
            $l_proveedores          = $this->lr->ListaProveedores();
            // array_push($parametros_vnt, $this->vnt->obtener_ultimo_correlativo($ruta,$id_usuario_sdv)[0]);
            #Mercado---------------------------------------------------------------------------------
            $ls_mercado              = $this->mer->list_mercado(intval($usuario_log), $ruta);
            $ls_tareas               = $this->mer->list_tareas();
            $ls_oportunidades        = $this->mer->list_oportunidades();

            //array_push($parametros_vnt, $this->vnt->obtener_ultimo_correlativo($ruta,$id_usuario_sdv)[0]);
            array_push($parametros_vnt, $this->vhe->obtener_datos_vendedor($idusuario_NEW));
            array_push($parametros_vnt, $this->vhe->obtener_datos_empleado($idusuario_NEW));
            $dp=0;
            foreach ($l_departamento as $dpt){
                $cod_encriptado_dpt = '';
                $cod_encriptado_dpt = encriptar_cadena($dpt->Dep_Id);
                $arrg_departamento[$dp]['codbx']   = $cod_encriptado_dpt;
                $arrg_departamento[$dp]['valor']   = $dpt->Dep_descripcion;
                $arrg_departamento[$dp]['idepart'] = $dp;
                $dp++;
            }
            $mn=0;
            foreach ($l_municipio as $mni){
                $cod_encriptado_dpt = '';
                $cod_encriptado_dpt = encriptar_cadena($mni->Mun_Id);
                $arrg_municipio[$mn]['codbx'] = $cod_encriptado_dpt;
                $arrg_municipio[$mn]['valor'] = $mni->Mun_descripcion;
                $arrg_municipio[$mn]['depat'] = $mni->Dep_descripcion;
                $arrg_municipio[$mn]['idmun'] = $mn;
                $mn++;
            }
            $tppv=0;
            foreach ($l_tpuntoventa as $tpv){
                $cod_encriptado_tpv = '';
                $cod_encriptado_tpv = encriptar_cadena($tpv->Tpv_Id);
                $arrg_tpuntoventa[$tppv]['codbx'] = $cod_encriptado_tpv;
                $arrg_tpuntoventa[$tppv]['valor'] = $tpv->Tpv_descripcion;
                $tppv++;
            }
            $d=0;
            foreach ($l_gironegocio as $gn){
                $cod_encriptado_gn = '';
                $cod_encriptado_gn = encriptar_cadena($gn->Gir_Id);
                $arrg_gironegocio[$d]['codbx']  = $cod_encriptado_gn;
                $arrg_gironegocio[$d]['valor']  = $gn->Gir_descripcion;
                $arrg_gironegocio[$d]['tpv']    = $gn->Tpv_descripcion;
                $arrg_gironegocio[$d]['idgiro'] = $d;
                $d++;
            }
            $c=0;
            foreach ($l_condicioncli as $ccli ){
                $cod_encriptado_ccli = '';
                $cod_encriptado_ccli = encriptar_cadena($ccli->Coc_Id);
                $arrg_condicioncli[$c]['codbx'] = $cod_encriptado_ccli;
                $arrg_condicioncli[$c]['valor'] = $ccli->Coc_descripcion;
                $c++;
            }
            $tf=0;
            foreach ($l_tfacturacion as $tfc){
                $cod_encriptado_tfc = '';
                $cod_encriptado_tfc = encriptar_cadena($tfc->Tfc_Id);
                $arrg_tfacturacion[$tf]['codbx'] = $cod_encriptado_tfc;
                $arrg_tfacturacion[$tf]['valor'] = $tfc->Tfc_descripcion;
                $tf++;
            }            
            $arrg_motivoInact = array();
            $arrg_valfiltro   = array();
            $arrg_motivoInact[0]['Id_Motivo']  = '1';
            $arrg_motivoInact[0]['Valor']      = 'CERRO OPERACIONES';
            $arrg_motivoInact[1]['Id_Motivo']  = '2';
            $arrg_motivoInact[1]['Valor']      = 'TRASLADO DE DOMICILIO';
            $arrg_motivoInact[2]['Id_Motivo']  = '3';
            $arrg_motivoInact[2]['Valor']      = 'ZONA DE ALTO RIESGO';
            $arrg_motivoInact[3]['Id_Motivo']  = '4';
            $arrg_motivoInact[3]['Valor']      = 'CODIGO DUPLICADO';
            $arrg_motivoInact[4]['Id_Motivo']  = '5';
            $arrg_motivoInact[4]['Valor']      = 'NO COMPRA';
            $arrg_motivoInact[5]['Id_Motivo']  = '6';
            $arrg_motivoInact[5]['Valor']      = 'ES DE OTRO RUTA';
            $arrg_motivoInact[6]['Id_Motivo']  = '7';
            $arrg_motivoInact[6]['Valor']      = 'NO EXISTE';
            $arrg_motivoInact[7]['Id_Motivo']  = '8';
            $arrg_motivoInact[7]['Valor']      = 'CAMBIO DE PROPIETARIO';
            $arrg_motivoInact[8]['Id_Motivo']  = '9';
            $arrg_motivoInact[8]['Valor']      = 'CAMBIO DE GIRO DE NEGOCIO';
            $arrg_motivoInact[9]['Id_Motivo']  = '10';
            $arrg_motivoInact[9]['Valor']      = 'CAMBIO DE RAZON SOCIAL';
            $arrg_motivoInact[10]['Id_Motivo'] = '11';
            $arrg_motivoInact[10]['Valor']     = 'CAMBIO DE RUTA';
            $dia_actual = date("N");
            $dia_actual = intval($dia_actual);
            $dia_texto  = '';
            if($dia_actual     == 1)
                $dia_texto      = 'LUNES';
            elseif($dia_actual == 2)
                $dia_texto      = 'MARTES';
            elseif($dia_actual == 3)
                $dia_texto      = 'MIERCOLES';
            elseif($dia_actual == 4)
                $dia_texto      = 'JUEVES';
            elseif($dia_actual == 5)
                $dia_texto      = 'VIERNES';
            elseif($dia_actual == 6)
                $dia_texto      = 'SABADO';
            elseif($dia_actual == 7)
                $dia_texto      = 'DOMINGO';
            else
                $dia_texto      = 'LUNES';
            $arrg_valfiltro[0]['id']       = 1;
            $arrg_valfiltro[0]['ValueAC']  = '';
            $arrg_valfiltro[0]['ValorAC']  = 'TODOS LOS DIAS';
            $arrg_valfiltro[0]['EstadoAC'] = 0;
            $arrg_valfiltro[1]['id']       = 2;
            $arrg_valfiltro[1]['ValueEX']  = $dia_texto;
            $arrg_valfiltro[1]['ValorEX']  = $dia_texto;
            $arrg_valfiltro[1]['EstadoEX'] = 0;
            $arrg_valfiltro[2]['id']       = 3;
            $arrg_valfiltro[2]['FiltroEstadoAC']  = 'VERDES';            
            $arrg_valfiltro[3]['FiltroFamiliasP'] = $this->lr->filtro_familias($canal_usu);
            /*-----------------------------------------------------------*/
            /*--------------------- PEDIDO SUGERIDO ---------------------*/
            /*-----------------------------------------------------------*/
            $param_pds =  array(
                'Ru_nombre' => $Ru_nombre,
                'Fecha_Telefono' => $Fecha_Telefono
            );
            $clasificacionRuta = '';
            $rs_Clasificacion = $this->pds->Val_ClasificacionRuta($param_pds);
            if(empty($rs_Clasificacion)){
                $clasificacionRuta = 'SIN CLASIFICACION';
            }else{
                $clasificacionRuta = $rs_Clasificacion->Cla_descripcion;
            }
            $rs_psd = $this->pds->val_Pedido_Sugerido($param_pds);
            if( $rs_psd > 1 ){
                $ls_pedido_sugerido[0]['Id'] = 7777777;
                $ls_pedido_sugerido[0]['NumPedido'] = 0;
                $ls_pedido_sugerido[0]['NoLiquidacion'] = 0;
                $ls_pedido_sugerido[0]['FechaPedido'] = '0000-00-00 00:00:00';
                $ls_pedido_sugerido[0]['Correlativo'] = 0;
                $ls_pedido_sugerido[0]['Producto'] = 'NA';
                $ls_pedido_sugerido[0]['DescripcionProd'] = 'ERROR PEDIDOS DUPLICADOS';
                $ls_pedido_sugerido[0]['CodigoUnidadVenta'] = 'NA';
                $ls_pedido_sugerido[0]['CantidadSugerida'] = 0;
                $ls_pedido_sugerido[0]['Cat_img'] = 'Uploads/img_server/Img_CatalagoProductos/icon_default.png';
                $ls_pedido_sugerido[0]['Fa_Id'] = 0;
                $ls_pedido_sugerido[0]['Fa_nombre'] = 'NA';
                $ls_pedido_sugerido[0]['Subf_Id'] = 0;
                $ls_pedido_sugerido[0]['Subf_nombre'] = 'NA';
                $ls_pedido_sugerido[0]['TotalPedido'] = 0;
                $ls_pedido_sugerido[0]['PrecioUnitario'] = 0;
                $ls_pedido_sugerido[0]['SubTotal'] = 0;
                $ls_pedido_sugerido[0]['DT_RowId'] = 0;
                $ls_pedido_sugerido[0]['PedSug_cola'] = 'NA';
                $ls_pedido_sugerido[0]['CodEstadoPedido'] = 0;
                $ls_pedido_sugerido[0]['CantidadPedida'] = 0;
                $ls_pedido_sugerido[0]['RutaId'] = $Ru_nombre;
                $ls_pedido_sugerido[0]['CantidadInventario'] = 0;
                $ls_pedido_sugerido[0]['ClasificacionRuta'] = $clasificacionRuta;
                $ls_pedido_sugerido[0]['MotivoVendedor'] = 1;
                $ls_pedido_sugerido[0]['MotivoSupervisor'] = 1;
                $ls_pedido_sugerido[0]['HistoricoVenta'] = 0;
                $ls_pedido_sugerido[0]['Fa_orden'] = 0;
            }else{
                $rs_psdEnviado = $this->pds->val_Pedido_Sugerido_Enviado($param_pds);
                if(empty($rs_psdEnviado)){
                    $ls_pedido_sugerido[0]['Id'] = 5555555;
                    $ls_pedido_sugerido[0]['NumPedido'] = 0;
                    $ls_pedido_sugerido[0]['NoLiquidacion'] = 0;
                    $ls_pedido_sugerido[0]['FechaPedido'] = '0000-00-00 00:00:00';
                    $ls_pedido_sugerido[0]['Correlativo'] = 0;
                    $ls_pedido_sugerido[0]['Producto'] = 'NA';
                    $ls_pedido_sugerido[0]['DescripcionProd'] = 'NO HAY PEDIDOS PARAR ESTA RUTA';
                    $ls_pedido_sugerido[0]['CodigoUnidadVenta'] = 'NA';
                    $ls_pedido_sugerido[0]['CantidadSugerida'] = 0;
                    $ls_pedido_sugerido[0]['Cat_img'] = 'Uploads/img_server/Img_CatalagoProductos/icon_default.png';
                    $ls_pedido_sugerido[0]['Fa_Id'] = 0;
                    $ls_pedido_sugerido[0]['Fa_nombre'] = 'NA';
                    $ls_pedido_sugerido[0]['Subf_Id'] = 0;
                    $ls_pedido_sugerido[0]['Subf_nombre'] = 'NA';
                    $ls_pedido_sugerido[0]['TotalPedido'] = 0;
                    $ls_pedido_sugerido[0]['PrecioUnitario'] = 0;
                    $ls_pedido_sugerido[0]['SubTotal'] = 0;
                    $ls_pedido_sugerido[0]['DT_RowId'] = 0;
                    $ls_pedido_sugerido[0]['PedSug_cola'] = 'NA';
                    $ls_pedido_sugerido[0]['CodEstadoPedido'] = 0;
                    $ls_pedido_sugerido[0]['CantidadPedida'] = 0;
                    $ls_pedido_sugerido[0]['RutaId'] = $Ru_nombre;
                    $ls_pedido_sugerido[0]['CantidadInventario'] = 0;
                    $ls_pedido_sugerido[0]['ClasificacionRuta'] = $clasificacionRuta;
                    $ls_pedido_sugerido[0]['MotivoVendedor'] = 1;
                    $ls_pedido_sugerido[0]['MotivoSupervisor'] = 1;
                    $ls_pedido_sugerido[0]['HistoricoVenta'] = 0;
                    $ls_pedido_sugerido[0]['Fa_orden'] = 0;
                }else{
                    $Procesar_ValPedidoSugerido = $this->pds->get_ValDupliPedido_Sugerido($param_pds);
                    if(empty($Procesar_ValPedidoSugerido)){
                        $Procesar_PedidoSugerido = $this->pds->get_Pedido_Sugerido($param_pds);
                        $pd = 0;
                        $Cat_img = '';$Fa_Id = '';$Fa_nombre = '';$Subf_Id = '';$Subf_nombre = '';
                        $ls_pedido_sugerido = [];
                        foreach ($Procesar_PedidoSugerido as $psd){
                            $Cat_img     = $psd->Cat_img;
                            $Fa_Id       = $psd->Fa_Id;
                            $Fa_nombre   = $psd->Fa_nombre;
                            $Subf_Id     = $psd->Subf_Id;
                            $Subf_nombre = $psd->Subf_nombre;
                            if(empty($Cat_img)){
                                $Cat_img = 'Uploads/img_server/Img_CatalagoProductos/icon_default.png';
                            }else{
                                if (!file_exists($Cat_img)) {
                                    $Cat_img = "Uploads/img_server/Img_CatalagoProductos/icon_default.png";
                                }
                            }
                            if(empty($Fa_Id))
                                $Fa_Id = 'SIN FAMILIA';
                            if(empty($Fa_nombre))
                                $Fa_nombre = 'SIN FAMILIA';
                            if(empty($Subf_Id))
                                $Subf_Id = 'SIN FAMILIA';
                            if(empty($Subf_nombre))
                                $Subf_nombre = 'SIN FAMILIA';
                            $ls_pedido_sugerido[$pd]['Id'] = $psd->Id;
                            $ls_pedido_sugerido[$pd]['NumPedido'] = $psd->NumPedido;
                            $ls_pedido_sugerido[$pd]['NoLiquidacion'] = $psd->NoLiquidacion;
                            $ls_pedido_sugerido[$pd]['FechaPedido'] = $psd->FechaPedido;
                            $ls_pedido_sugerido[$pd]['Correlativo'] = $psd->Correlativo;
                            $ls_pedido_sugerido[$pd]['Producto'] = $psd->Producto;
                            $ls_pedido_sugerido[$pd]['DescripcionProd'] = $psd->DescripcionProd;
                            $ls_pedido_sugerido[$pd]['CodigoUnidadVenta'] = $psd->CodigoUnidadVenta;
                            $ls_pedido_sugerido[$pd]['CantidadSugerida'] = $psd->CantidadSugerida;
                            $ls_pedido_sugerido[$pd]['Cat_img'] = $Cat_img;
                            $ls_pedido_sugerido[$pd]['Fa_Id'] = $Fa_Id;
                            $ls_pedido_sugerido[$pd]['Fa_nombre'] = $Fa_nombre;
                            $ls_pedido_sugerido[$pd]['Subf_Id'] = $Subf_Id;
                            $ls_pedido_sugerido[$pd]['Subf_nombre'] = $Subf_nombre;
                            $ls_pedido_sugerido[$pd]['TotalPedido'] = $psd->TotalPedido;
                            $ls_pedido_sugerido[$pd]['PrecioUnitario'] = $psd->PrecioUnitario;
                            $ls_pedido_sugerido[$pd]['SubTotal'] = $psd->SubTotal;
                            $ls_pedido_sugerido[$pd]['DT_RowId'] = $psd->DT_RowId;
                            $ls_pedido_sugerido[$pd]['PedSug_cola'] = $psd->PedSug_cola;
                            $ls_pedido_sugerido[$pd]['CodEstadoPedido'] = $psd->CodEstadoPedido;
                            $ls_pedido_sugerido[$pd]['CantidadPedida'] = $psd->CantidadPedida;
                            $ls_pedido_sugerido[$pd]['RutaId'] = $Ru_nombre;
                            $ls_pedido_sugerido[$pd]['CantidadInventario'] = $psd->CantidadInventario;
                            $ls_pedido_sugerido[$pd]['ClasificacionRuta'] = $clasificacionRuta;
                            $ls_pedido_sugerido[$pd]['MotivoVendedor'] = $psd->MotivoVendedor;
                            $ls_pedido_sugerido[$pd]['MotivoSupervisor'] = $psd->MotivoSupervisor;
                            $ls_pedido_sugerido[$pd]['HistoricoVenta'] = $psd->HistoricoVenta;
                            $ls_pedido_sugerido[$pd]['Fa_orden'] = $psd->Fa_orden;
                            $pd++;
                        }
                    }else{
                        $pd = 0;
                        $Cat_img = '';$Fa_Id = '';$Fa_nombre = '';$Subf_Id = '';$Subf_nombre = '';
                        $ls_pedido_sugerido = [];
                        $SKU_duplicados = '';
                        foreach ($Procesar_ValPedidoSugerido as $psd){
                            $SKU_duplicados .=$psd->Producto.' '.$psd->DescripcionProd.'<br>';
                        }
                        $ls_pedido_sugerido[0]['Id'] = 2222222;
                        $ls_pedido_sugerido[0]['NumPedido'] = 0;
                        $ls_pedido_sugerido[0]['NoLiquidacion'] = 0;
                        $ls_pedido_sugerido[0]['FechaPedido'] = '0000-00-00 00:00:00';
                        $ls_pedido_sugerido[0]['Correlativo'] = 0;
                        $ls_pedido_sugerido[0]['Producto'] = 'NA';
                        $ls_pedido_sugerido[0]['DescripcionProd'] = $SKU_duplicados;
                        $ls_pedido_sugerido[0]['CodigoUnidadVenta'] = 'NA';
                        $ls_pedido_sugerido[0]['CantidadSugerida'] = 0;
                        $ls_pedido_sugerido[0]['Cat_img'] = 'Uploads/img_server/Img_CatalagoProductos/icon_default.png';
                        $ls_pedido_sugerido[0]['Fa_Id'] = 0;
                        $ls_pedido_sugerido[0]['Fa_nombre'] = 'NA';
                        $ls_pedido_sugerido[0]['Subf_Id'] = 0;
                        $ls_pedido_sugerido[0]['Subf_nombre'] = 'NA';
                        $ls_pedido_sugerido[0]['TotalPedido'] = 0;
                        $ls_pedido_sugerido[0]['PrecioUnitario'] = 0;
                        $ls_pedido_sugerido[0]['SubTotal'] = 0;
                        $ls_pedido_sugerido[0]['DT_RowId'] = 0;
                        $ls_pedido_sugerido[0]['PedSug_cola'] = 'NA';
                        $ls_pedido_sugerido[0]['CodEstadoPedido'] = 0;
                        $ls_pedido_sugerido[0]['CantidadPedida'] = 0;
                        $ls_pedido_sugerido[0]['RutaId'] = $Ru_nombre;
                        $ls_pedido_sugerido[0]['CantidadInventario'] = 0;
                        $ls_pedido_sugerido[0]['ClasificacionRuta'] = $clasificacionRuta;
                        $ls_pedido_sugerido[0]['MotivoVendedor'] = 1;
                        $ls_pedido_sugerido[0]['MotivoSupervisor'] = 1;
                        $ls_pedido_sugerido[0]['HistoricoVenta'] = 0;
                        $ls_pedido_sugerido[0]['Fa_orden'] = 0;
                    }
                }
            }
            $arrlistas['parametros']['rs'] = TRUE;
            $arrlistas['parametros']['lsclientes']            = $l_clientes_todos;
            $arrlistas['parametros']['lsfilstros']            = $arrg_valfiltro;
            $arrlistas['parametros']['lsproductos']           = $l_productos_pais;
            $arrlistas['parametros']['lstipoDanos']           = $l_tipo_danos;
            $arrlistas['parametros']['lsclientes_DBA']        = [];
            $arrlistas['parametros']['ldepartamento']         = $arrg_departamento;
            $arrlistas['parametros']['lmunicipio']            = $arrg_municipio;
            $arrlistas['parametros']['lcondicioncli']         = $arrg_condicioncli;
            $arrlistas['parametros']['lgironegocio']          = $arrg_gironegocio;
            $arrlistas['parametros']['ltfacturacion']         = $arrg_tfacturacion;
            $arrlistas['parametros']['ltpuntoventa']          = $arrg_tpuntoventa;
            $arrlistas['parametros']['lsexhfacturados']       = $arrg_exhibidoresfac;
            $arrlistas['parametros']['lsparametros']          = [];
            $arrlistas['parametros']['lsmotivoselim']         = $arrg_motivoInact;
            $arrlistas['parametros']['lexhibidor']            = $arrg_exhibidor;
            $arrlistas['parametros']['us_cod']                = encriptar_cadena($idusuario_NEW);
            $arrlistas['parametros']['ls_det_exhibidores']    = $l_det_exhibidores;
            $arrlistas['parametros']['ls_tipo_exhibidores']   = $l_tipo_exhibidores;
            $arrlistas['parametros']['ls_cti_ingresados']     = $ls_cti_ingresados;
            $arrlistas['parametros']['ls_reclamos_enproceso'] = $ls_reclamos_enproceso;
            $arrlistas['parametros']['ls_ste_tipo_motivos']   = $ls_ste_tipo_motivos;
            $arrlistas['parametros']['ls_ste_motivos']        = $ls_ste_motivos;
            $arrlistas['parametros']['ls_pedido_sugerido']    = $ls_pedido_sugerido;
            $arrlistas['parametros']['Clasificacion']         = $clasificacionRuta;
            $arrlistas['parametros']['ls_pedidos_motivos']    = $this->pds->get_MotivosPedidos();
            $arrlistas['parametros']['pass_on_off']           = $pass_on_off;
            $arrlistas['parametros']['ls_checklist_vehiculo']   = $ls_checklist_vehiculo;
            $arrlistas['parametros']['ls_vehiculo']             = $ls_info_vehiculo;
            $arrlistas['parametros']['ls_tipo_licencia']        = $ls_tipo_licencia;
            $arrlistas['parametros']['ls_parametros_vnt']       = $parametros_vnt;
            $arrlistas['parametros']['l_referencias']           = $l_referencias;
            $arrlistas['parametros']['l_proveedores']           = $l_proveedores;
            //Mercado 16/08/2024
            $arrlistas['parametros']['ls_mercado']              = $ls_mercado ;
            $arrlistas['parametros']['ls_tareas']               = $ls_tareas;
            $arrlistas['parametros']['ls_oportunidades']        = $ls_oportunidades;
            echo json_encode($arrlistas);
            return;
        }
    }

    public function Actualizar_Pass_Nuevo(){
        $usuId = $this->input->post('us_cod');
        $usu = $this->input->post('usuario');
        $contrasenaActual = $this->input->post('txtPassactual');
        $contraNueva = $this->input->post('txtPassNuevo');
        if($this->input->is_ajax_request()){
            $info = $this->lg->VerificarUsu($usu);
            $count_Data = count($info);
            if( $count_Data > 0 ){
                if($info[0]->Usu_estado == 1){
                    if($info[0]->Usu_act_contrasena == 1){
                        $inicio_sesion = $this->lg->login($usu,$contrasenaActual);
                        if(count($inicio_sesion) > 0){
                            $password_hash = password_hash($contraNueva,PASSWORD_DEFAULT);
                            $updata = $this->lg->modificarUsuario(array('Usu_contrasena' => $password_hash,'Usu_act_contrasena' => 0),$usuId);
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
                        }else{
                            $data_resul = array(
                                'Resultado' => FALSE,
                                'Mensaje' => 'Contraseña actual incorrecta...'
                            );                            
                        }
                    }else{
                        $data_resul = array(
                            'Resultado' => FALSE,
                            'Mensaje' => 'Peticion bloqueada...<br>Informar a Sistemas de venta'
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
                    'Resultado' => TRUE,
                    'Mensaje' => 'Usuario no encontrado...'
                );
            }
            echo json_encode($data_resul);
            return;
        }else{
            $data_resul = array(
                'Resultado' => FALSE,
                'Mensaje' => 'Peticion inalcanzable...'
            );
            echo json_encode($data_resul);
            return;
        }
    }
}
?>