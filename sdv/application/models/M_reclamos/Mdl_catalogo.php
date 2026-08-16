<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_catalogo extends CI_Model
{
    private $DB2;

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }
      
    function listadoProductosReclamos($canal){
        $this->DB2->select('tbl_catalogo_productos.*, Um_nombre, Fa_nombre, Subf_nombre, Catx_estado,Subf_Fa_Id');
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos','Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->where('Catx_estado', 1);

        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    

    function listadoTipoDanosReclamos($area_tipo){
        $this->DB2->select('Tipd_Id, Tipd_descripcion');
        $this->DB2->from('tbl_tipo_danos');
        $this->DB2->where('Tipd_area', $area_tipo);

        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    public function guardarReclamoNuevo($data_insertar){
       
        $this->DB2->insert('tbl_reclamo_pfn', $data_insertar);
            if($this->DB2->affected_rows() > 0 ){
                return true;
            }else{
                return false;
            }
    }

    function verificar_producto_reclamo($data){
        $this->DB2->select('Rec_Id, Rec_token, Rec_Cli_Id, Cat_unidad_medida');
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_catalogo_productos', 'Cat_Id = Rec_Cat_Id');
        $this->DB2->where('Rec_Id', $data[0]);
        $this->DB2->where('Rec_Cat_Id_SKU', $data[1]);
        $this->DB2->where('Cat_unidad_medida', $data[3]);

        $query = $this->DB2->get();
        $resultado = $query->num_rows();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

    }

    function filtro_familias($canal){
        $this->DB2->select('DISTINCT(Fa_nombre)');
        $this->DB2->from('tbl_catalogo_productos');
        $this->DB2->join('tbl_catalogopro_x_canal','Catx_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id','right');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->group_by('Fa_nombre');

        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

function filtro_familias1()
{
    $this->DB2->select('DISTINCT(Fa_nombre), Fa_Id');
    $this->DB2->from('tbl_catalogo_productos');
    $this->DB2->join('tbl_subfamilia', 'Cat_Subf_Id = Subf_Id', 'inner'); // JOIN normal
    $this->DB2->join('tbl_familia', 'Subf_Fa_Id = Fa_Id', 'right');       // RIGHT JOIN  
    $this->DB2->group_by('Fa_nombre');

    $query = $this->DB2->get();
    $resultado = $query->result_array();

    return !empty($resultado) ? $resultado : array();
}


/* $parametros: parametros para obtener datos.
 *  $query: indica la query que se construye para obtener los datos
 */

    function make_datatables_cta($parametros, $query_){  
        if($query_ == 'catalogo'){
            $this->query_catalogo_bodega($parametros);
        }

        if($_POST["length"] != -1)  
        {  
            $this->DB2->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->DB2->get();  

        return $query->result();  
    }  
 
    function get_filtered_data($parametros, $query_){  
        if($query_ == 'catalogo'){
            $this->query_catalogo_bodega($parametros);
        }
        $query = $this->DB2->get();  
        return $query->num_rows();  
    }     

    function get_all_data($parametros, $query_)  
    {  
        if($query_ == 'catalogo'){
            $this->query_catalogo_bodega($parametros);
        } 

        return $this->DB2->count_all_results();  
    }   

    // query para llenar catalogo de productos para usuario bodega/mercado ---------------------------------------------
    function query_catalogo_bodega($canal){
        $this->DB2->select('tbl_catalogo_productos.*, Um_nombre, Fa_nombre, Subf_nombre, Catx_estado');
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos','Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->where('Catx_estado', 1);
        $this->DB2->where('Fa_nombre !=', 'EXHIBIDOR');
        if(!empty($_POST['search']['value'])){
            $bus_ = $_POST['search']['value'];
            $this->DB2->where("(Cat_Id like '%".$bus_."%' or Cat_descripcion like '%".$bus_."%'
            or Um_nombre like '%".$bus_."%' or Fa_nombre like '%".$bus_."%'
            or Subf_nombre like '%".$bus_."%')", null, false);            
        }
       
    }

    function get_all_catalogo_xlsx($parametros){
        
        $this->DB2->select("Rec_Id AS NUMERO_RECLAMO, 
                            Tipd_descripcion AS TIPO_RECLAMO,
                            Rec_Cat_Id AS CODIGO_PRODUCTO, 
                            Cat_descripcion AS PRODUCTO,
                            Rec_cantidad AS CANTIDAD_A_ENTREGAR,
                            Fa_nombre AS FAMILIA_PRODUCTO,
                            Subf_nombre AS SUBFAMILIA_PRODUCTO,
                            Rec_fecha_vencimiento AS FECHA_VENCIMIENTO, 
                            Rec_numero_lote AS NUMERO_LOTE, 
                            Rec_unidad_medida AS UNIDAD_MEDIDA,
                            Cli_codigo as CODIGO_CLIENTE,
                            Cli_nombre as NOMBRE_CLIENTE,     
                            CASE
                                WHEN Tipd_area = 1 THEN 'VENTAS / MERCADEO'
                                ELSE 'BODEGA / CALIDAD'
                            END AS AREA, 
                            Rec_fecha_servidor AS FECHA_RECLAMO, 
                            Rec_token AS TOKEN, 
                            Rec_observacion AS OBSERVACION, 
                            Rec_observacion_ventas AS OBSERVACION_VENTAS, 
                            Rec_estado AS ESTADO,
                            P_nombre as PAIS, Di_nombre as DIVISION, Dis_nombre as DISTRIBUIDORA");
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_usuario','Usu_Id = Rec_Usu_Id');
        $this->DB2->join('tbl_rutas','Ru_Id = Usu_Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_cliente','Rec_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');

        // $this->DB2->where('Rec_estado', 1);
        $this->DB2->where('P_Id',$parametros['codigo_pais']);
        $this->DB2->where('Di_Id',$parametros['codigo_division']);
        
        if(!empty($parametros['codigo_dis']) || $parametros['codigo_dis'] != 0)
            $this->DB2->where('Dis_Id',$parametros['codigo_dis']);

        if(!empty($parametros['codigo_ca']) || $parametros['codigo_ca'] != 0)
            $this->DB2->where('Ca_Id',$parametros['codigo_ca']);

        if(!empty($parametros['codigo_grupo']) || $parametros['codigo_grupo'] != 0)
            $this->DB2->where('Dist_Id',$parametros['codigo_grupo']);
        
        if(!empty($parametros['codigo_ruta']) || $parametros['codigo_ruta'] != 0)
            $this->DB2->where('Ru_Id', $parametros['codigo_ruta']);

        if(!empty($parametros['fecha_inicial']) && empty($parametros['fecha_limite'])){
            $this->DB2->where('CAST(Rec_fecha_servidor as date) >=', $parametros['fecha_inicial']);
            $this->DB2->where('CAST(Rec_fecha_servidor as date) <=', 'current_date()');
        }
        else if(!empty($parametros['fecha_inicial']) && !empty($parametros['fecha_limite'])){
            $this->DB2->where('CAST(Rec_fecha_servidor as date) >=', $parametros['fecha_inicial']);
            $this->DB2->where('CAST(Rec_fecha_servidor as date) <=', $parametros['fecha_limite']);
        }

        $this->DB2->group_by('Rec_Id');
        $this->DB2->order_by('Rec_fecha_servidor','DESC');
    }

}
?>


