<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_control_inventario extends CI_Model
{
    private $DB2;

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }
      
// --- 06/09/2021 -------------------------------------------------------------------------------------------------------
    function ls_cti_ingresados($ruta){ // obtener todos los items ingresados por el usuario loguedo
        $this->DB2->select("
            Cti_Cat_Id AS id_producto,
            CAST(Cti_fecha_telefono as date) AS fecha,
            Cat_descripcion AS nombre_producto,
            Cti_Cli_Id AS id_cliente,
            CONCAT(Cli_codigo, ' - ', Cli_nombre) AS nombre_cliente,
            Cti_Usu_Id AS id_usuario,
            Cti_cantidad AS cantidad,
            CAST(Cti_fecha_vencimiento as date) AS fecha_vencimiento,
            Cti_fecha_telefono AS fecha_telefono,
            Cti_token AS token_cti,
            Cti_latitud AS latitud_ini,
            Cti_longitud AS longitud_ini,
            'NO' AS pendiente,
            'SI' AS enviado,
            'agregado' AS opcion,
            CONCAT(Cti_token, Cti_Cat_Id) As token  
        ");
        $this->DB2->from('tbl_control_inventario cti');
        $this->DB2->join('tbl_cliente','Cti_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_catalogo_productos','Cti_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_usuario','Usu_Id = Cti_Usu_Id');
        $this->DB2->where('Cti_estado', 1);        
        $this->DB2->where('Cli_Ru_Id', $ruta);         
        $this->DB2->where("CAST(cti.Cti_fecha_telefono as date) in ( CAST(NOW() as date), (select CAST(Cti_fecha_telefono as date) from tbl_control_inventario inner join tbl_cliente on Cti_Cli_Id = Cli_Id where Cli_Ru_Id = ".$ruta." order by Cti_fecha_telefono desc limit 1) )", NULL, false);
        $this->DB2->group_by('Cti_Id');
        $this->DB2->order_by('Cti_fecha_telefono','DESC');
        
        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
// ----------------------------------------------------------------------------------------------------------------------
    
// ------------------------------------------------------------------------------------------------------------
    public function verificar_regitro($data, $tabla)
    {
        $this->DB2->select('*');
        $this->DB2->from($tabla);
        $this->DB2->where($data);
        $this->DB2->where('CAST(Cti_fecha_telefono as date) = CAST(NOW() as date)');
        return $this->DB2->count_all_results() > 0 ? true : false;
    }
// ------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------
    public function editar_registro($data_set, $data_where, $tabla)
    {
        $this->DB2->set($data_set);
        $this->DB2->where($data_where);
        $this->DB2->where('CAST(Cti_fecha_telefono as date) = CAST(NOW() as date)');
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
}
?>


