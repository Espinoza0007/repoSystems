<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_historial extends CI_Model
{
    private $DB2;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }   
   // Datos de la tabla Historial 
     public function obtenerDatosTabla() {
            $this->DB2->select('tbl_recepcion_vehiculo.id_recepcion ,tbl_recepcion_vehiculo.Rvehi_Vehi_Id,tbl_vehiculo.Vehi_placas, tbl_recepcion_vehiculo.Revehi_Ru_Id, tbl_recepcion_vehiculo.Rvehi_fecha_recepcion');
            $this->DB2->from('tbl_vehiculo');
            $this->DB2->join('tbl_recepcion_vehiculo', 'tbl_vehiculo.Vehi_Id = tbl_recepcion_vehiculo.Rvehi_Vehi_Id');
            $query = $this->DB2->get();
            return $query->result_array();        
    }
    // Fin Datos de la tabla Historial 
    //traer datos del encargado del vehiculo

    public function datosReporte($id)
    {
            $this->DB2->select('rv.Rvehi_Vehi_Id,rv.Rvehi_KM_actual,rv.Rvehi_observaciones, rv.Rvehi_check_list_recepcion, rv.Nombre_Recibe, rv.Id_Recibe, rv.Rvehi_nombre_empleado, rv.Rvehi_carnet, e.Emp_Numero_licencia, tl.TLic_nombre, v.Vehi_Equipo, v.Vehi_placas, v.Vehi_marca, v.Vehi_tipo, v.Vehi_anio, v.Vehi_numero_motor, v.Vehi_numero_chasis, v.Vehi_tipo_combustible, rv.Rvehi_fecha_recepcion');
            $this->DB2->from('tbl_vehiculo AS v');
            $this->DB2->join('tbl_recepcion_vehiculo AS rv', 'v.Vehi_Id = rv.Rvehi_Vehi_Id', 'inner');
            $this->DB2->join('tbl_empleados AS e', 'v.Vehi_Ru_Id = e.Emp_Ru_Id', 'inner');
            $this->DB2->join('tbl_tipo_licencia AS tl', 'e.Emp_TLic_Id = tl.TLic_Id', 'inner');
            $this->DB2->join('tbl_rutas AS r', 'e.Emp_Ru_Id = r.Ru_Id AND v.Vehi_Ru_Id = r.Ru_Id', 'inner');
            $this->DB2->join('tbl_usuario AS u', 'u.Usu_Ru_Id = r.Ru_Id', 'inner');
            $this->DB2->where('rv.id_recepcion', $id);
            $query = $this->DB2->get();
            $resultado = $query->result();
            if(!empty($resultado)){
                return $resultado;
                echo json_encode($resultado);
            }else{
                return array();
            }
    }
    public function get_parte_trasera() {
        $this->DB2->select('Irv_Id,Irv_nombre_item');
        $this->DB2->from('tbl_items_recepcion_vehiculo');
        $this->DB2->where('Irv_seccion_descripcion', 'PARTE TRASERA');
        $query = $this->DB2->get();
        return $query->result();
    }

    public function get_parte_delantera() {
        $this->DB2->select('Irv_Id,Irv_nombre_item');
        $this->DB2->from('tbl_items_recepcion_vehiculo');
        $this->DB2->where('Irv_seccion_descripcion', 'PARTE DELANTERA');
        $query = $this->DB2->get();
        return $query->result();
    }

    public function get_parte_interior() {
        $this->DB2->select('Irv_Id,Irv_nombre_item');
        $this->DB2->from('tbl_items_recepcion_vehiculo');
        $this->DB2->where('Irv_seccion_descripcion', 'INTERIOR DEL VEHICULO');
        $query = $this->DB2->get();
        return $query->result();
    }
}
?>


