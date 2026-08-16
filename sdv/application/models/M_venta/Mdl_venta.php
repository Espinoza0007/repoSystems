<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_venta extends CI_Model
{
    private $DB2;

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }      
    
    
    // ------------------------------------------------------------------------------------------------------------
    public function verificar_regitro($data, $tabla)
    {
        $this->DB2->select('*');
        $this->DB2->from($tabla);
        $this->DB2->where($data);
        return $this->DB2->count_all_results() > 0 ? true : false;

    }
    // ------------------------------------------------------------------------------------------------------------
    // ------------------------------------------------------------------------------------------------------------
    public function editar_registro($data_set, $data_where, $tabla)
    {
        $this->DB2->set($data_set);
        $this->DB2->where($data_where);
        $this->DB2->update($tabla);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    // ------------------------------------------------------------------------------------------------------------
    // ------------------------------------------------------------------------------------------------------------
    public function agregar_registro($tabla, $data )
    {
        $this->DB2->insert($tabla, $data);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    // ------------------------------------------------------------------------------------------------------------
    // ------------------------------------------------------------------------------------------------------------
    public function agregar_registros($tabla, $data )
    {
        $this->DB2->insert_batch($tabla, $data);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    // ------------------------------------------------------------------------------------------------------------
    function obtener_total_registros($parametros, $query_)  
    {  
        if($query_ == 'catalogo_venta'){
            $this->query_catalogo_venta($parametros);
        }
        return $this->DB2->count_all_results();  
    }  

    function obtener_data_info($parametros, $query_){  
        if($query_ == 'catalogo_venta'){
            $this->query_catalogo_venta($parametros);
        }        
        if($_POST["length"] != -1)  
        {  
            $this->DB2->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->DB2->get();  
        return $query->result();  
    }  

    // VER CATALOGO PARA MODULO DE VENTA -----------------------------------------------------------------
    function query_catalogo_venta($parametros){
        $this->DB2->select("tbl_catalogopro_x_canal.*,
            Cat_descripcion, Cat_img, Um_Id, Um_nombre, Subf_Id, Subf_nombre, Fa_Id, Fa_nombre,
            tbl_precio.*, Nvp_nombre, Impt_valor, Impt_descripcion");
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos', 'Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_precio', 'Catx_Id = Prc_Catx_Id','left');
        $this->DB2->join('tbl_nivel_precio', 'Prc_Nvp_Id = Nvp_Id','left');
        $this->DB2->join('tbl_impuesto', 'Prc_Impt_Id = Impt_Id','left');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id','left');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id','left');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id','left');
        $this->DB2->where('Catx_Ca_Id', $parametros);  
        $this->DB2->where('Catx_estado','1');  
        $this->DB2->order_by('Fa_Id', 'ASC');  
        $this->DB2->order_by('Cat_Id', 'ASC');
        $this->DB2->order_by('Catx_estado', 'DESC');

        if(!empty($_POST['search']['value'])){
            $bus_ = $_POST['search']['value'];
            $this->DB2->where("(Cat_Id like '%".$bus_."%' or Cat_descripcion like '%".$bus_."%'
            or Um_nombre like '%".$bus_."%' or Fa_nombre like '%".$bus_."%'
            or Subf_nombre like '%".$bus_."%')", null, false);
        } 
       
    }

    /*function ls_catalogo_venta($parametros){
        $this->DB2->select("
            tbl_catalogopro_x_canal.*,
            Cat_descripcion, Cat_img, Um_Id, 
            Um_nombre, Subf_Id, Subf_nombre, 
            Fa_Id, Fa_nombre,
            tbl_precio.*, 
            Nvp_nombre, Impt_valor, Impt_nombre");
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos', 'Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_precio', 'Catx_Id = Prc_Catx_Id','left');
        $this->DB2->join('tbl_nivel_precio', 'Prc_Nvp_Id = Nvp_Id','left');
        $this->DB2->join('tbl_impuesto', 'Prc_Impt_Id = Impt_Id','left');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id','left');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id','left');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id','left');
        $this->DB2->where('Catx_Ca_Id', $parametros);  
        $this->DB2->where('Catx_estado','1');  
        $this->DB2->order_by('Fa_Id', 'ASC');  
        $this->DB2->order_by('Cat_Id', 'ASC');
        $this->DB2->order_by('Catx_estado', 'DESC');

        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        } 
    }*/

    function ls_catalogo_venta($parametros){
        $this->DB2->select("
            tbl_catalogopro_x_canal_01.*, Catx_precio, Cat_img,
            Cat_descripcion, Subf_Id, Subf_nombre, Fa_Id, Fa_nombre,
            Nvp_nombre,Impt_nombre, Impt_valor, Impt_descripcion");
        $this->DB2->from('tbl_catalogopro_x_canal_01');
        $this->DB2->join('tbl_catalogo_productos', 'Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->join('tbl_nivel_precio', 'Nvp_Id = Catx_Nvp_Id');
        $this->DB2->join('tbl_impuesto', 'Impt_Id = Catx_Impt_Id');
        $this->DB2->where('Catx_Ca_Id', $parametros);  
        $this->DB2->where('Catx_estado','1');  
        $this->DB2->order_by('Fa_Id', 'ASC');  
        $this->DB2->order_by('Cat_Id', 'ASC');
        $this->DB2->order_by('Catx_estado', 'DESC');

        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        } 
    }
// ------------------------------------------------------------------------------------------------------

// --- Funcion para extraer pedidos realizados por el vendedor ------------------------------------------
    public function obtener_pedidos_realizados($id_usuario)
    {
        $this->DB2->select("
            Cli_codigo          as cliente_codigo, 
            Cli_Id              as cliente_id,
            Cli_nombre          as cliente_nombre, 
            'SINCRONIZADO'      as es_sincronizado,
            Fact_fecha_emision  as fecha_emision,
            Fact_importe_total  as importe_total,
            Fact_numero         as numero,
            Usu_Ru_Id           as pedido_ruta,
            Fact_total_unidades as total_unidades,
            Usu_Id              as usuario_id,            
            Fact_Id,
            CASE WHEN Fact_estado = 1 THEN 'VIGENTE' ELSE 'ANULADO' END AS estado");
        $this->DB2->from('tbl_factura');
        $this->DB2->join('tbl_cliente','Fact_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_usuario','Fact_Usu_Id = Usu_Id');
        $this->DB2->where('Fact_Usu_Id', $id_usuario);
        $this->DB2->where('date(Fact_fecha_emision) between DATE_SUB(current_date(), 
            INTERVAL 1 DAY) and CURRENT_DATE()', null, false);
        $query      = $this->DB2->get();
        $resultado  = $query->result();

        foreach ($resultado as $key) {
            $this->DB2->select('
                Pvnt_cantidad       as item_cantidad,
                Cat_Id              as item_codigo,
                Cat_descripcion     AS item_descripcion,
                Pvnt_estado         as item_estado,
                Pvnt_fecha_telefono as item_fecha,
                Catx_Id             as item_id,
                Pvnt_impuesto       as item_impuesto,
                Pvnt_precio         as item_precio,
                Pvnt_subtotal       as item_subtotal,
                Pvnt_total          as item_total');
            $this->DB2->from('tbl_pedido_vnt');
            $this->DB2->join('tbl_catalogopro_x_canal_01','Pvnt_Catx_Id = Catx_Id');
            $this->DB2->join('tbl_catalogo_productos','Catx_Cat_Id = Cat_Id');
            $this->DB2->where('Pvnt_Fact_Id', $key->Fact_Id);
            $query                  = $this->DB2->get();
            $data                   = $query->result();
            $key->pedido_detalle    = ($data);
        }   
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
// ------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------
    public function obtener_ultimo_correlativo($ruta, $id_usuario)
    {
        $this->DB2->select('
            MAX(Fact_numero) as ultimo_correlativo,
            Fact_serie,
            "CORRELATIVO" as tipo_parametro,
            "SINCRONIZADO" as es_sincronizado',
        );
        $this->DB2->from('tbl_factura');  
        $this->DB2->join('tbl_cliente','Cli_Id = Fact_Cli_Id');      
        $this->DB2->where('Cli_Ru_Id', $ruta);
        $this->DB2->where('Fact_Usu_Id', $id_usuario);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
// ------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------
    public function obtener_numero_factura($numero)
    {
        $this->DB2->select('Fact_Id, Fact_numero');
        $this->DB2->from('tbl_factura');
        $this->DB2->where('Fact_numero', $numero);

        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

    }
// ------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------
    public function obtener_pedidos_sugeridos($id_usuario)
    {
        $this->DB2->select("
            Cli_Id as cliente, 
            Pvnt_Catx_Id as item, 
            Cat_Id as item_codigo,
            Cat_descripcion as item_descripcion,
            SUM(Pvnt_cantidad) as suma_cantidad,
            count(distinct Pvnt_Fact_Id) as NUM_VENTAS,
            floor(SUM(Pvnt_cantidad) / count(distinct Pvnt_Fact_Id)) as cantidad");
        $this->DB2->from('tbl_pedido_vnt');
        $this->DB2->join('tbl_factura','Pvnt_Fact_Id = Fact_Id');
        $this->DB2->join('tbl_cliente','Fact_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_catalogopro_x_canal_01','Pvnt_Catx_Id = Catx_Id');
        $this->DB2->join('tbl_catalogo_productos','Catx_Cat_Id = Cat_Id');
        $this->DB2->where('Fact_Usu_Id', $id_usuario);
        $this->DB2->group_by('Pvnt_Catx_Id, Cli_Id');
        $query = $this->DB2->get();
        $data  = $query->result();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }

    }
// ------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------
    public function carga_pedido_sugerido($id_usuario)
    {
        $this->DB2->select("
            Cli_Id as cliente, 
            Pvnt_Catx_Id as item, 
            Cat_Id as item_codigo,
            Cat_descripcion as item_descripcion,
            SUM(Pvnt_cantidad) as suma_cantidad,
            count(distinct Pvnt_Fact_Id) as NUM_VENTAS,
            floor(SUM(Pvnt_cantidad) / count(distinct Pvnt_Fact_Id)) as cantidad");
        $this->DB2->from('tbl_pedido_vnt');
        $this->DB2->join('tbl_factura','Pvnt_Fact_Id = Fact_Id');
        $this->DB2->join('tbl_cliente','Fact_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_catalogopro_x_canal_01','Pvnt_Catx_Id = Catx_Id');
        $this->DB2->join('tbl_catalogo_productos','Catx_Cat_Id = Cat_Id');
        $this->DB2->where('Fact_Usu_Id', $id_usuario);
        $this->DB2->group_by('Pvnt_Catx_Id, Cli_Id');
        $query = $this->DB2->get();
        $data  = $query->result();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }

    }
// ------------------------------------------------------------------------------------------------------

}
?>


