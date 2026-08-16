<?php
header("Access-Control-Allow-Origin: *");
if(!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/El_Salvador');
ini_set ('gd.jpeg_ignore_warning', 1);
require APPPATH . '/libraries/ControladorBase.php';
class Ctr_pedido_sugerido extends ControladorBase{
    function __construct(){
        parent::__construct();
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','security'));
        $this->load->model('M_pedidos/Mdl_pedidos','pds');
    }
    function index(){
        $this->global['pageTitle'] = 'Pedidos Sugeridos';
        $this->loadViews('Pedidos/V_pedido_sugerido',$this->global);
    }
    public function GuardarPedidOptimo(){
        $fecha_actual    = date('Y-m-d H:i:s');
        $pedido_sugerido = $this->input->post("pedido_sugerido");
        $CodEstadoPedido = $this->input->post("CodEstadoPedido");
        $C_Process       = 0;$C_OK = 0;
        $Error_Sku       = '';$Pedido_Solicitado = array();
        $pedidoIdPedidoEnc = array();
        $skuSugeridos = array();$estadoPedido = 0;
        $estadoPedido = $this->pds->val_PedidoSugEstado($pedido_sugerido[0]['IdPedidoEnc']);
        if($estadoPedido > 0){
            $pedidoIdPedidoEnc = $this->pds->get_CantidadPedidoSugId($pedido_sugerido[0]['IdPedidoEnc']);
            foreach ($pedidoIdPedidoEnc as $key ) {$skuSugeridos[$key->Producto] = $key->CantidadSugerida;}
            foreach ($pedido_sugerido as $pro){
                $Pedido_Solicitado = array();
                if(intval($CodEstadoPedido) == 3){
                    if( $skuSugeridos[$pro['Producto']] == $pro['CantidadPedida'] ){
                        $Pedido_Solicitado = array('CantidadPedida' => $pro['CantidadPedida'],'CantidadAutorizada' => $pro['CantidadPedida'],'MotivoVendedor' => $pro['MotivoVendedor'],'MotivoSupervisor' => $pro['MotivoVendedor']);
                    }else{
                        $Pedido_Solicitado = array('CantidadPedida' => $pro['CantidadPedida'],'MotivoVendedor' => $pro['MotivoVendedor']);
                    }
                }elseif(intval($CodEstadoPedido) == 4){
                    $Pedido_Solicitado = array('CantidadPedida' => $pro['CantidadPedida'],'CantidadAutorizada' => $pro['CantidadPedida'],'MotivoVendedor' => $pro['MotivoVendedor'],'MotivoSupervisor' => $pro['MotivoVendedor']);
                }else{
                    $Pedido_Solicitado = array('CantidadPedida' => $pro['CantidadPedida'],'MotivoVendedor' => $pro['MotivoVendedor']);
                    $CodEstadoPedido = 3;
                }
                $Procesar_Solicitado = $this->pds->Enviar_PedidoV($pro['IdPedidoEnc'],$pro['Producto'],$Pedido_Solicitado);
                if($Procesar_Solicitado == TRUE){
                    $C_OK++;
                }else{
                    $Error_Sku.= $pro['Producto'].'<br>'.$pro['DescripcionProd'];
                }
                $C_Process++;
            }
            if( $C_Process == $C_OK ){
                if(intval($CodEstadoPedido) == 3){
                    $Procesar_EnConsola = $this->pds->Modificar_PedidoEnc($pedido_sugerido[0]['IdPedidoEnc'],array('CodEstadoPedido' => $CodEstadoPedido,'FechaVendedor' => $fecha_actual));
                }elseif(intval($CodEstadoPedido) == 4){
                    $Procesar_EnConsola = $this->pds->Modificar_PedidoEnc($pedido_sugerido[0]['IdPedidoEnc'],array('CodEstadoPedido' => $CodEstadoPedido,'FechaVendedor' => $fecha_actual,'FechaSupervisor' => $fecha_actual));
                }else{
                    $Procesar_EnConsola = $this->pds->Modificar_PedidoEnc($pedido_sugerido[0]['IdPedidoEnc'],array('CodEstadoPedido' => $CodEstadoPedido,'FechaVendedor' => $fecha_actual));
                }
                if( $Procesar_EnConsola ){
                    echo json_encode(array(
                        'rs'   => TRUE,
                        'info' => 'Registros Guardados Exitosamente.',
                        'cla'  => 'success grSuccess',
                        'alert'=> FALSE
                        )
                    );
                }else{
                    echo json_encode(array(
                        'rs'   => FALSE,
                        'tipo' => 'Error Pedido #'.$pedido_sugerido[0]['IdPedidoEnc'],
                        'info' => 'EL PEDIDO NO CAMBIO DE ESTADO...',
                        'cla'  => 'success grSuccess',
                        )
                    );
                }
            }else{
                if(intval($CodEstadoPedido) == 3){
                    $Procesar_EnConsola = $this->pds->Modificar_PedidoEnc($pedido_sugerido[0]['IdPedidoEnc'],array('CodEstadoPedido' => $CodEstadoPedido,'FechaVendedor' => $fecha_actual));
                }elseif(intval($CodEstadoPedido) == 4){
                    $Procesar_EnConsola = $this->pds->Modificar_PedidoEnc($pedido_sugerido[0]['IdPedidoEnc'],array('CodEstadoPedido' => $CodEstadoPedido,'FechaVendedor' => $fecha_actual,'FechaSupervisor' => $fecha_actual));
                }else{
                    $Procesar_EnConsola = $this->pds->Modificar_PedidoEnc($pedido_sugerido[0]['IdPedidoEnc'],array('CodEstadoPedido' => $CodEstadoPedido,'FechaVendedor' => $fecha_actual));
                }
                if( $Procesar_EnConsola ){
                    echo json_encode(array(
                        'rs'   => TRUE,
                        'tipo' => '<h4>PRODUCTOS SOLICITADOS CANTIDAD "CERO"</h4>',
                        'info' => $Error_Sku,
                        'cla'  => 'success grSuccess',
                        'alert'=> TRUE
                        )
                    );
                }else{
                    echo json_encode(array(
                        'rs'   => FALSE,
                        'tipo' => 'Error Pedido #'.$pedido_sugerido[0]['IdPedidoEnc'],
                        'info' => 'EL PEDIDO NO CAMBIO DE ESTADO...',
                        'cla'  => 'success grSuccess',
                        )
                    );
                }
            }
        }else{
            echo json_encode(array(
                'rs'   => TRUE,
                'info' => 'Registros Guardados Exitosamente.',
                'cla'  => 'success grSuccess',
                'alert'=> FALSE
                )
            );
        }
    }
}
?>