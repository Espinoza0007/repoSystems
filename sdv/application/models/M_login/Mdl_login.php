<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_login extends CI_Model{
    private $DB2;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }
    public function login($usuario,$contra){
        $this->DB2->select('
            Usu_nombre_usuario, Usu_usuario, 
            Usu_contrasena, Priv_Id, Usu_Id,
            Priv_descripcion, Usu_Ru_Id, 
            Ru_nombre, Usu_estado, Ca_Id,
            Ca_nombre, Dist_nombre, 
            Dist_Id, Dis_Id, Dis_nombre,
            Di_Id, P_Id, P_nombre,Usu_act_contrasena');
        $this->DB2->from('tbl_usuario');       
        $this->DB2->join('tbl_privilegio', 'Usu_Priv_Id = Priv_Id');
        $this->DB2->join('tbl_rutas', 'Usu_Ru_Id = Ru_Id');
        $this->DB2->join('tbl_distrito', 'Dist_Id = Ru_Dist_Id');       
        $this->DB2->join('tbl_canal', 'Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora', 'Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division', 'Dis_Di_Id = Di_Id');       
        $this->DB2->join('tbl_pais', 'Di_P_Id = P_Id');       
        $this->DB2->where('Usu_usuario', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->where('Usu_Priv_Id', 2);
        $this->DB2->or_where('Usu_Priv_Id', 15);
        $this->DB2->where('Usu_usuario', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->or_where('Usu_Priv_Id', 4);
        $this->DB2->where('Usu_usuario', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->or_where('Usu_Priv_Id', 6);
        $this->DB2->where('Usu_usuario', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->or_where('Usu_Priv_Id', 116);
        $this->DB2->where('Usu_usuario', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->or_where('Usu_Priv_Id', 155);
        $this->DB2->where('Usu_usuario', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $query = $this->DB2->get();
        $usuario = $query->result();
        if(!empty($usuario)){
            if(verifyHashedPassword($contra, $usuario[0]->Usu_contrasena)){
                return $usuario;
            } else {
                return array();
            }    
        }else{
            return array();
        }
    }
    public function VerificarUsu($usuario){
        $this->DB2->select('
            Usu_nombre_usuario, Usu_usuario,Usu_estado,Usu_act_contrasena');
        $this->DB2->from('tbl_usuario');             
        $this->DB2->where('Usu_usuario', $usuario);
        $query = $this->DB2->get();
        $usuario = $query->result();
        if(!empty($usuario)){
            return $usuario;
        }else{
            return array();
        }
    }
    public function DBA_obtener_distribuidoras_us($idusuario){
        $this->db->select('dist.Id_Distribuidora,dist.Nombre_Distribuidora');
        $this->db->from("asignacion_distribuidora as ad");
        $this->db->join("distribuidora as dist","ad.Id_Distribuidora = dist.Id_Distribuidora");
        $this->db->where('ad.Id_Usuarios', $idusuario);   
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    public function obtener_distribuidoras_us($idusuario){
        $this->DB2->select('Dis_Id, Dis_nombre, Usu_Ru_Id');
        $this->DB2->from("tbl_usuario");
        $this->DB2->join("tbl_rutas","Usu_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id = Dis_Id");
        $this->DB2->where('Usu_Id', $idusuario);   
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    public function comprobar_estado_us($id){
        $this->DB2->select('Usu_estado');
        $this->DB2->from('tbl_usuario');
        $this->DB2->where('Usu_Id', $id);

         $query = $this->DB2->get();
         $usuario = $query->result();

        if(!empty($usuario)){
            return $usuario;  
        }else{
            return array();
        }
    }

    public function verificar_supervisor($id){
        $this->db->select('Id_Supervisor');
        $this->db->from('usuarios_supervisores');
        $this->db->where('Id_Usuarios', $id);
        $query = $this->db->get();
        $usuario = $query->result();

        if(!empty($usuario)){
            return $usuario;  
        }else{
            return array();
        }
    }

    public function obtener_division_us($idusuario){
        $this->DB2->select('Di_Id, Dis_Id, Dis_nombre, Usu_Ru_Id');
        $this->DB2->from("tbl_usuario");
        $this->DB2->join("tbl_rutas","Usu_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id = Dis_Id");
        $this->DB2->join("tbl_division","Di_Id = Dis_Di_Id");
        $this->DB2->where('Usu_Id', $idusuario);   
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    public function usuario_pais_tercero($idusuario)
    {
        $this->DB2->select('*');
        $this->DB2->from("tbl_usuario_pais_tercero");
        $this->DB2->where('Upt_Usu_Id', $idusuario);   
        return $this->DB2->count_all_results() > 0 ? true : false;

    }

    // ----- LISTA RUTAS POR DISTRIBUIDOR PARA DESAROLLO --------------------------------
    public function ls_rutas_desarrollador($parametros){
        $this->DB2->select('tbl_rutas.*');
        $this->DB2->from("tbl_rutas");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        // $this->DB2->where('Dis_Id', $id_distribuidora);
        
        if($parametros['P_nombre'] == 'EL SALVADOR'){
            $this->DB2->where('Di_Id', $parametros['Di_Id']);
        }else{
            $this->DB2->where('Dis_id', $parametros['Dis_Id']);
        }
        $this->DB2->where('Ru_nombre != Ca_nombre',null);
        $this->DB2->where('Ru_Id!=',34);
        $this->DB2->where('Ru_Id!=',35);
        $this->DB2->where('Ru_Id!=',36);
        $this->DB2->where('Ru_estado',1);

        $this->DB2->order_by('Ru_Id', "ASC");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    // ----------------------------------------------------------------------------------
    // -------- 15/02/2023
    public function param_usu($usuario){
        $this->DB2->select('
            Usu_nombre_usuario, Usu_usuario, 
            Usu_contrasena, Priv_Id, Usu_Id,
            Priv_descripcion, Usu_Ru_Id, 
            Ru_nombre, Usu_estado, Ca_Id,
            Ca_nombre, Dist_nombre, 
            Dist_Id, Dis_Id, Dis_nombre,
            Di_Id, P_Id, P_nombre,Usu_act_contrasena');
        $this->DB2->from('tbl_usuario');       
        $this->DB2->join('tbl_privilegio', 'Usu_Priv_Id = Priv_Id');
        $this->DB2->join('tbl_rutas', 'Usu_Ru_Id = Ru_Id');
        $this->DB2->join('tbl_distrito', 'Dist_Id = Ru_Dist_Id');       
        $this->DB2->join('tbl_canal', 'Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora', 'Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division', 'Dis_Di_Id = Di_Id');       
        $this->DB2->join('tbl_pais', 'Di_P_Id = P_Id');       
        $this->DB2->where('Usu_Id', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->where('Usu_Priv_Id', 2);
        $this->DB2->or_where('Usu_Priv_Id', 15);
        $this->DB2->where('Usu_Id', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->or_where('Usu_Priv_Id', 4);
        $this->DB2->where('Usu_Id', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->or_where('Usu_Priv_Id', 6);
        $this->DB2->where('Usu_Id', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->where('Usu_Id', $usuario);
        $this->DB2->where('Usu_estado', 1);
        $this->DB2->or_where('Usu_Priv_Id', 155);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    public function modificarUsuario($datas,$userid){
        $this->DB2->where('Usu_Id',$userid);
        $this->DB2->update('tbl_usuario', $datas);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
}
?>