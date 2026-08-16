<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/El_Salvador');
class Mdl_pedidos extends CI_Model
{
    private $DB2;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    } 
    public function val_Pedido_Sugerido($param){
        $Fecha_Servidor = strtotime(date('Y-m-d'));
        $Fecha_Telefono = strtotime($param['Fecha_Telefono']);
        if($Fecha_Servidor == $Fecha_Telefono){
            $this->DB2->select("*");
            $this->DB2->from('tbl_PedSug_PedidosEnc');
            $this->DB2->where('RutaId', $param['Ru_nombre']);
            $this->DB2->where('CodEstadoPedido', 2);
            $this->DB2->where('Date(FechaPedido)', $param['Fecha_Telefono']);
            return $this->DB2->count_all_results();
        }else{
            return 'SINCRONIZAR';
        }
    }
    public function val_Pedido_Sugerido_Enviado($param){
        $Fecha_Servidor = strtotime(date('Y-m-d'));
        $Fecha_Telefono = strtotime($param['Fecha_Telefono']);
        if($Fecha_Servidor == $Fecha_Telefono){
            $this->DB2->select("*");
            $this->DB2->from('tbl_PedSug_PedidosEnc');
            $this->DB2->where('RutaId', $param['Ru_nombre']);
            $this->DB2->where('Date(FechaPedido)', $param['Fecha_Telefono']);
            return $this->DB2->count_all_results();
        }else{
            return 'SINCRONIZAR';
        }
    }
    public function get_ValDupliPedido_Sugerido($param){
        $Fecha_Servidor = strtotime(date('Y-m-d'));
        $Fecha_Telefono = strtotime($param['Fecha_Telefono']);
        $Fecha_Consulta = $param['Fecha_Telefono'];
        if($Fecha_Servidor == $Fecha_Telefono){
            $query_select = "
            select
            Id,
            NumPedido,
            NoLiquidacion,
            FechaPedido,
            Correlativo,
            Producto,
            DescripcionProd,
            CodigoUnidadVenta,
            CantidadSugerida,
            Cat_img,
            Fa_Id,
            Fa_nombre,
            Subf_Id,
            Subf_nombre,
            TotalPedido,
            PrecioUnitario,
            SubTotal,
            CONCAT('Fila_',Producto) as 'DT_RowId',
            'NO' as PedSug_cola,
            CodEstadoPedido,
            CantidadPedida,
            RutaId,
            CantidadInventario
            from tbl_PedSug_PedidosEnc
            inner join tbl_PedSug_PedidosDet on Id = IdPedidoEnc
            left join tbl_catalogo_productos on Producto = Cat_Id
            left join tbl_subfamilia on Cat_Subf_Id = Subf_Id
            left join tbl_familia on Subf_Fa_Id = Fa_Id
            where RutaId = '".$param['Ru_nombre']."' and Date(FechaPedido) = '".$Fecha_Consulta."'
            group by Producto
            having count(*)>1";
            $resultado = $this->DB2->query($query_select);
            $data = $resultado->result();
            // $this->DB2->where('RutaId', $param['Ru_nombre']);
            // $this->DB2->where('CodEstadoPedido', 2);
            // $this->DB2->where('Date(FechaPedido)', $Fecha_Consulta);
            // $query = $this->DB2->get();
            // $data  = $query->result();
            if(!empty($data)){
                return $data;
            }else{
                return array();
            }
        }else{
            return 'SINCRONIZAR';
        }
    }
    public function get_Pedido_Sugerido($param){
        $Fecha_Servidor = strtotime(date('Y-m-d'));
        $Fecha_Telefono = strtotime($param['Fecha_Telefono']);
        $Fecha_Consulta = $param['Fecha_Telefono'];
        if($Fecha_Servidor == $Fecha_Telefono){
            $this->DB2->select("
                Id,
                NumPedido,
                NoLiquidacion,
                FechaPedido,
                Correlativo,
                Producto,
                DescripcionProd,
                CodigoUnidadVenta,
                CantidadSugerida,
                Cat_img,
                Fa_Id,
                Fa_nombre,
                Subf_Id,
                Subf_nombre,
                TotalPedido,
                PrecioUnitario,
                SubTotal,
                CONCAT('Fila_',Producto) as 'DT_RowId',
                'NO' as PedSug_cola,
                CodEstadoPedido,
                CantidadPedida,
                RutaId,
                CantidadInventario,
                MotivoVendedor,
                MotivoSupervisor,
                HistoricoVenta,
                Fa_orden
            ");
            $this->DB2->from('tbl_PedSug_PedidosEnc');
            $this->DB2->join('tbl_PedSug_PedidosDet','Id = IdPedidoEnc');
            $this->DB2->join('tbl_catalogo_productos','Producto = Cat_Id','left');
            $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id','left');
            $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id','left');
            $this->DB2->where('RutaId', $param['Ru_nombre']);
            // $this->DB2->where('CodEstadoPedido', 2);
            $this->DB2->where('Date(FechaPedido)', $Fecha_Consulta);
            $this->DB2->order_by('DescripcionProd', 'ASC');
            $query = $this->DB2->get();
            $data  = $query->result();
            if(!empty($data)){
                return $data;
            }else{
                return array();
            }
        }else{
            return 'SINCRONIZAR';
        }
    }
    public function Enviar_PedidoV($IdPedido,$Sku,$data){
        $this->DB2->where('IdPedidoEnc',$IdPedido);
        $this->DB2->where('Producto',$Sku);
        $this->DB2->update('tbl_PedSug_PedidosDet', $data);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    public function Modificar_PedidoEnc($IdPedido,$data){
        $this->DB2->where('Id',$IdPedido);
        $this->DB2->update('tbl_PedSug_PedidosEnc', $data);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    public function Val_ClasificacionRuta($param){
        $this->DB2->select('Cla_descripcion');
        $this->DB2->from('tbl_rutas');
        $this->DB2->join('tbl_clasificacion','Ru_Cla_Id = Cla_Id');
        $this->DB2->where('Ru_nombre', $param['Ru_nombre']);
        $query = $this->DB2->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }
    public function get_MotivosPedidos(){
        $this->DB2->select(
            'Id,
            DescripcionMotivo,
            IdMotivoTipo Tipo'
        );
        $this->DB2->from('tbl_PedSug_Motivo');
        $this->DB2->where('IdMotivoTipo',2);
        $query = $this->DB2->get();
        $data  = $query->result();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }
    public function get_CantidadPedidoSugId($Id){
        $this->DB2->select("
            Producto,
            CantidadSugerida
        ");
        $this->DB2->from('tbl_PedSug_PedidosEnc');
        $this->DB2->join('tbl_PedSug_PedidosDet','Id = IdPedidoEnc');
        $this->DB2->join('tbl_catalogo_productos','Producto = Cat_Id','left');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id','left');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id','left');
        $this->DB2->where('IdPedidoEnc', $Id);
        $this->DB2->order_by('DescripcionProd', 'ASC');
        $query = $this->DB2->get();
        $data  = $query->result();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }
    public function val_PedidoSugEstado($Id){
        $this->DB2->select("*");
        $this->DB2->from('tbl_PedSug_PedidosEnc');
        $this->DB2->where('Id', $Id);
        $this->DB2->where('CodEstadoPedido', 2);
        return $this->DB2->count_all_results();
    }

}
?>