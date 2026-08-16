<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_login extends CI_Model
{

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }

    public function login($usuario,$contra){
        $this->db->select('u.Id_Usuarios,u.Usuario,u.Contrasena,u.Nombre_Completo,r.Nombre_Ruta,tu.Id_Tipo_Usuario,tu.Clave,u.Estado_U,tu.Tipo_Usuario,p.Nombre_Pais');
        $this->db->from('usuarios as u');
        $this->db->join("rutas as r","u.Id_Ruta = r.Id_Ruta");
        $this->db->join("distribuidora as d","r.Id_Distribuidora = d.Id_Distribuidora");
        $this->db->join("pais as p","d.Id_Pais = p.Id_Pais");
        $this->db->join('tipo_usuario as tu','u.Id_Tipo_Usuario = tu.Id_Tipo_Usuario');
        // $this->db->join("distribuidora as d","r.Id_Distribuidora = d.Id_Distribuidora");
        $this->db->where('u.Usuario', $usuario);
         $query = $this->db->get();
         $usuario = $query->result();
        if(!empty($usuario)){
            if(verifyHashedPassword($contra, $usuario[0]->Contrasena)){
                return $usuario;
            } else {
                return array();
            }    
        }else{
            return array();
        }
    }

    function modificar_usuario($datas,$usuario){
        $this->db->where('Usuario',$usuario);
        $this->db->update('usuarios', $datas);
        if($this->db->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }


    function obtener_distribuidoras_us($idusuario){
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
    public function comprobar_estado_us($id){
        $this->db->select('Estado_U');
        $this->db->from('usuarios');
        $this->db->where('Id_Usuarios', $id);

         $query = $this->db->get();
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

}
?>