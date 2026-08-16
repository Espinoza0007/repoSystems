<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_geocerca extends CI_Model
{
    private $DB2;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2',TRUE);
    }
    function ls_paises(){
        $query = $this->DB2->get('tbl_pais');
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function arrg_coordenadas_rutas($v_p_id){
        $this->DB2->select('Ru_nombre,Ru_Id');
        $this->DB2->from('tbl_marcacion_impulso');
        // $this->DB2->join("tbl_cliente","Mar_Cli_Id = Cli_Id");
        $this->DB2->join("tbl_usuario","Mar_Usu_Id = Usu_Id");
        $this->DB2->join("tbl_rutas","Usu_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id  = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id  = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->where('P_Id',1);
        $this->DB2->where('Usu_estado',1);
        $this->DB2->where('Di_Id',1);
        $this->DB2->where('Ca_nombre','MAYOREO');
        $this->DB2->where('Usu_Id!=',596);
        $this->DB2->where('DATE(Mar_fecha_inicio) >=','2021-09-01');
        $this->DB2->where('DATE(Mar_fecha_inicio) <=', '2021-09-30');
        $this->DB2->group_by('Ru_Id');
        // $this->DB2->where('Ru_nombre=','1.3.02');
        // $this->DB2->where("DATE(Mar_fecha_inicio) BETWEEN '2021-08-01' AND '2021-09-31'");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function arrg_coordenadas($v_r_id){
        $this->DB2->select('Ru_nombre,Mar_latitud_ini,Mar_longitud_ini,Usu_nombre_usuario,Mar_token,Usu_usuario');
        $this->DB2->from('tbl_marcacion_impulso');
        // $this->DB2->join("tbl_cliente","Mar_Cli_Id = Cli_Id");
        $this->DB2->join("tbl_usuario","Mar_Usu_Id = Usu_Id");
        $this->DB2->join("tbl_rutas","Usu_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id  = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id  = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->where('P_Id',1);
        $this->DB2->where('Usu_estado',1);
        $this->DB2->where('Di_Id',1);
        $this->DB2->where('Ca_nombre','MAYOREO');
        $this->DB2->where('Usu_Id!=',596);
        // $this->DB2->where('Ru_Id',$v_r_id);
        $this->DB2->where('DATE(Mar_fecha_inicio) >=','2021-09-01');
        $this->DB2->where('DATE(Mar_fecha_inicio) <=', '2021-09-30');
        
        // $this->DB2->where('Ru_nombre=','1.3.02');
        // $this->DB2->where("DATE(Mar_fecha_inicio) BETWEEN '2021-08-01' AND '2021-09-31'");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function arrg_poligonos($v_r_id){
        $this->DB2->select('Po_Latitud,Po_Longitud,Po_Ru_Id');
        $this->DB2->from('tbl_poligonos_lat_log');
        $this->DB2->join("tbl_rutas","Po_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id  = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id  = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->where('P_Id',1);
        $this->DB2->where('Di_Id',1);
        $this->DB2->where('Ca_nombre','MAYOREO');
        $this->DB2->where('Po_Ru_Id',$v_r_id);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
}