<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_vehiculo extends CI_Model
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
    // ------------------------------------------------------------------------------------------------------------// ------------------------------------------------------------------------------------------------------------
    public function editar_registros($tabla , $datos , $where)
    {
        $this->DB2->update_batch($tabla, $datos, $where);
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

    function get_items_checklist_vehiculo(){
        $this->DB2->select('Irv_Id, Irv_nombre_item, Irv_seccion_descripcion, Irv_estado,
            "item" as Irv_tipo');
        $this->DB2->from('tbl_items_recepcion_vehiculo');        
        $this->DB2->where('Irv_estado','1');  
        
        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            $this->DB2->select('Irv_seccion_descripcion,
            "seccion" as Irv_tipo');
            $this->DB2->from('tbl_items_recepcion_vehiculo');        
            $this->DB2->where('Irv_estado','1'); 
            $this->DB2->group_by('Irv_seccion_descripcion');
            $query = $this->DB2->get();
            $resultado_seccion = $query->result(); 
            $data = array_merge($resultado, $resultado_seccion);
        }

        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
       
    }

    //traer datos del empleado que recive el vehiculo
    
    public function obtener_datos_empleado($id_usuario)
    {
        $this->DB2->select('
            Usu_Id, Usu_nombre_usuario, Usu_Ru_Id, Usu_Priv_Id,
            Emp_Id, Emp_carnet, Emp_cod_rutero, Emp_nombre, Emp_Plaz_Id,
            Emp_Numero_licencia, Emp_fecha_vencimiento_licencia, Emp_TLic_Id, TLic_Id, TLic_nombre,
            Plaz_nombre_plaza, "DATOS_US" as tipo_parametro');
        $this->DB2->from('tbl_usuario');  
        $this->DB2->join('tbl_empleados','Emp_Ru_Id = Usu_Ru_Id','left');      
        $this->DB2->join('tbl_tipo_licencia','Emp_TLic_Id = TLic_Id', 'left');      
        $this->DB2->join('tbl_plazas_trabajo','Emp_Plaz_Id = Plaz_Id','left');      
        $this->DB2->where('Usu_Id', $id_usuario);
        $this->DB2->where('Emp_estado', 1);
        $this->DB2->where('Usu_Priv_Id', 2);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    // datos de la persona que entrega el vehiculo 
    public function obtener_datos_vendedor($id_usuario)
    {
        $this->DB2->select('
            Usu_Id, Usu_nombre_usuario, Usu_Ru_Id, Usu_Priv_Id,
            Emp_Id, Emp_carnet, Emp_cod_rutero, Emp_nombre, Emp_Plaz_Id,
            Emp_Numero_licencia, Emp_fecha_vencimiento_licencia, Emp_TLic_Id, TLic_Id, TLic_nombre,
            Plaz_nombre_plaza, "DATOS_US" as tipo_parametro');
        $this->DB2->from('tbl_usuario');  
        $this->DB2->join('tbl_empleados','Emp_Ru_Id = Usu_Ru_Id','left');      
        $this->DB2->join('tbl_tipo_licencia','Emp_TLic_Id = TLic_Id', 'left');      
        $this->DB2->join('tbl_plazas_trabajo','Emp_Plaz_Id = Plaz_Id','left');  
        $this->DB2->where('Usu_Ru_Id', $id_usuario);
        $this->DB2->where('Emp_estado', 1);
        $this->DB2->where('Usu_Priv_Id', 2);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    public function obtener_datos_vehiculo($id_ruta)
    {
        $this->DB2->select('
            Vehi_Id, Vehi_Equipo, Vehi_placas, Vehi_marca, Vehi_tipo, Vehi_anio, Vehi_numero_motor, Vehi_numero_chasis, Vehi_tipo_combustible, Vehi_Ru_Id, Vehi_fecha_recibido, Vehi_estado');
        $this->DB2->from('tbl_vehiculo');              
        $this->DB2->where('Vehi_Ru_Id', $id_ruta);
        // $this->DB2->where('Vehi_estado', 1);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
            echo json_encode($resultado);
        }else{
            return array();
        }
    }
  

     public function buscar_por_id($id) {
            $this->DB2->select('Vehi_Id, Vehi_Equipo, Vehi_placas, Vehi_marca, Vehi_tipo, Vehi_anio, Vehi_numero_motor, Vehi_numero_chasis, Vehi_tipo_combustible, Vehi_Ru_Id, Vehi_fecha_recibido, Vehi_estado');
            $this->DB2->from('tbl_vehiculo');              
            //$this->DB2->where('Vehi_Ru_Id', $id_ruta);
            $this->DB2->where('Vehi_estado', 1);
            $this->DB2->where('Vehi_Ru_Id', $id);
            $query = $this->DB2->get();
            return $query->row();
    }


    public function obtener_tipo_licencias()
    {
        $this->DB2->select('
            TLic_Id, TLic_nombre');
        $this->DB2->from('tbl_tipo_licencia');              
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    //traer datos del encargado del vehiculo
    function getRows3($params = array())
    {
        if(!empty($params['searchTerm'])){

            $this->DB2->select('
            Usu_Id, Usu_nombre_usuario, Usu_Ru_Id, Usu_Priv_Id,
            Emp_Id, Emp_carnet, Emp_cod_rutero, Emp_nombre, Emp_Plaz_Id,
            Emp_Numero_licencia, Emp_fecha_vencimiento_licencia, Emp_TLic_Id, TLic_Id, TLic_nombre,
            Plaz_nombre_plaza, "DATOS_US" as tipo_parametro');
            $this->DB2->from('tbl_usuario');  
            $this->DB2->join('tbl_empleados','Emp_Ru_Id = Usu_Ru_Id','left');      
            $this->DB2->join('tbl_tipo_licencia','Emp_TLic_Id = TLic_Id', 'left');      
            $this->DB2->join('tbl_plazas_trabajo','Emp_Plaz_Id = Plaz_Id','left'); 

            $this->DB2->like('Usu_Ru_Id', $params['searchTerm']);

            $this->DB2->where('Emp_estado', 1);
            $this->DB2->where('Usu_Priv_Id', 2);

            $this->DB2->limit(10);
            // $this->DB2->order_by('i.direccion', 'asc');

            $query = $this->DB2->get();

            $result = ($query->num_rows() > 0)?$query->result_array():FALSE;


            return $result;
        }
    }


}
?>


