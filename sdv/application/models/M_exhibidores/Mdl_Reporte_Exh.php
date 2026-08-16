
<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*--------------------------------------------------------------------------*/
/*---------------- REPORTE EXHIBIDORES NEW 10/09/2021 ---------------------*/
/*--------------------------------------------------------------------------*/
class Mdl_Reporte_Exh extends CI_Model
{
    private $DB2;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2',TRUE);
    }
    function Total_ClteAfiches($datab){
        $this->DB2->select("COUNT(DISTINCT(Ste_Cli_Id)) as totalresultados");
        $this->DB2->from("tbl_status_exhibidores");
        $this->DB2->join("tbl_cliente","Ste_Cli_Id = Cli_Id");
        $this->DB2->join("tbl_rutas","Cli_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id  = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id  = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->join("tbl_catalogo_productos","Ste_Cat_Id = Cat_Id");
        $this->DB2->join("tbl_subfamilia","Cat_Subf_Id = Subf_Id");
        if(!empty($datab['pais']))
            $this->DB2->where('P_Id',$datab['pais']);
        if(!empty($datab['division']))
            $this->DB2->where('Di_Id',$datab['division']);
        if(!empty($datab['canal']))
            $this->DB2->where('Ca_nombre',$datab['canal']);
        if(!empty($datab['grupo']))
            $this->DB2->where('Dist_nombre',$datab['grupo']);
        if(!empty($datab['rutas']))
            $this->DB2->where('Ru_Id',$datab['rutas']);
        if(!empty($datab['tipoexhibidores']))
            $this->DB2->where('Subf_nombre',$datab['tipoexhibidores']);
        if(!empty($datab['exhibidores']))
            $this->DB2->where('Cat_descripcion',$datab['exhibidores']);
        if(!empty($datab['codigo']))
            $this->DB2->like('Cli_codigo', $datab['codigo'], 'both');
        $this->DB2->where('Ste_Cat_Id!=','7777777');
        $this->DB2->where('Ste_eliminado',0);
        $query = $this->DB2->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }
    }
    function list_cltesConAfiche($start,$limit,$datab){
        $this->DB2->select('Ste_Cli_Id,Ru_nombre,Cli_codigo,Ste_foto,Cli_foto,Cli_nombre,Cli_contacto,Cli_Direccion,P_nombre,Di_nombre,Ca_nombre,Dist_nombre');
        $this->DB2->from('tbl_status_exhibidores');
        $this->DB2->join("tbl_cliente","Ste_Cli_Id = Cli_Id");
        $this->DB2->join("tbl_rutas","Cli_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id  = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id  = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->join("tbl_catalogo_productos","Ste_Cat_Id = Cat_Id");
        $this->DB2->join("tbl_subfamilia","Cat_Subf_Id = Subf_Id");
        if(!empty($datab['pais']))
            $this->DB2->where('P_Id',$datab['pais']);
        if(!empty($datab['division']))
            $this->DB2->where('Di_Id',$datab['division']);
        if(!empty($datab['canal']))
            $this->DB2->where('Ca_nombre',$datab['canal']);
        if(!empty($datab['grupo']))
            $this->DB2->where('Dist_nombre',$datab['grupo']);
        if(!empty($datab['rutas']))
            $this->DB2->where('Ru_Id',$datab['rutas']);
        if(!empty($datab['tipoexhibidores']))
            $this->DB2->where('Subf_nombre',$datab['tipoexhibidores']);
        if(!empty($datab['exhibidores']))
            $this->DB2->where('Cat_descripcion',$datab['exhibidores']);
        if(!empty($datab['codigo']))
            $this->DB2->like('Cli_codigo', $datab['codigo'], 'both');
        $this->DB2->where('Ste_Cat_Id!=','7777777');
        $this->DB2->where('Ste_eliminado',0);
        $this->DB2->group_by("Ste_Cli_Id");
        $this->DB2->order_by("Ru_nombre ASC");
        $this->DB2->limit($limit,$start);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function InfoCliExhibidores($idx_cliente){
        $this->DB2->select('Ste_latitud_obs,Ste_longitud_obs,Cli_latitud,Cli_longitud,Ste_Cli_Id,Ru_nombre,Cli_codigo,Ste_foto,Cli_nombre,Cli_contacto,Ste_telefono_cli,Cli_Direccion,P_nombre,Di_nombre,Ca_nombre,Dist_nombre');
        $this->DB2->from('tbl_status_exhibidores');
        $this->DB2->join("tbl_cliente","Ste_Cli_Id = Cli_Id");
        $this->DB2->join("tbl_rutas","Cli_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id  = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id  = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->where('Ste_Cli_Id',$idx_cliente);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function ListExhibidoresBq($idx_cliente,$status){
        $this->DB2->join("tbl_catalogo_productos","Ste_Cat_Id = Cat_Id");
        $this->DB2->where('Ste_Cli_Id',$idx_cliente);
        $this->DB2->where('Ste_status',$status);
        $this->DB2->where('Ste_Cat_Id!=','7777777');
        $this->DB2->where('Ste_eliminado',0);
        $query = $this->DB2->get('tbl_status_exhibidores');
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function InfoExhibidorSlc($idx_exh){
        $this->DB2->where('Ste_token',$idx_exh);
        $this->DB2->join("tbl_cliente","Ste_Cli_Id = Cli_Id");
        $query = $this->DB2->get('tbl_status_exhibidores');
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function FiltroPais($datab){
        $this->DB2->select("DISTINCT(P_nombre) as Descripcion,P_Id as Id");
        $this->DB2->join("tbl_division","Di_P_Id = P_Id");
        $this->DB2->join("tbl_distribuidora","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_canal","Ca_Dis_Id = Dis_Id");
        $this->DB2->join("tbl_distrito","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_rutas","Dist_Id = Ru_Dist_Id");
        $this->DB2->from("tbl_pais");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function FiltroDivision($datab){
        $this->DB2->select("DISTINCT(Di_nombre) as Descripcion,Di_Id As Id");
        $this->DB2->from("tbl_division");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->join("tbl_distribuidora","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_canal","Ca_Dis_Id = Dis_Id");
        $this->DB2->join("tbl_distrito","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_rutas","Dist_Id = Ru_Dist_Id");
        if(!empty($datab['pais']))
            $this->DB2->where('P_Id',$datab['pais']);
        $this->DB2->order_by("Di_nombre DESC");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function FiltroCanal($datab){
        $this->DB2->select("Ca_nombre as Descripcion,Ca_Id as Id");
        $this->DB2->from("tbl_canal");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->join("tbl_distrito","Dist_Ca_Id = Ca_Id");
        if(!empty($datab['pais']))
            $this->DB2->where('P_Id',$datab['pais']);
        if(!empty($datab['division']))
            $this->DB2->where('Di_Id',$datab['division']);
        $this->DB2->group_by("Ca_nombre");
        $this->DB2->order_by("Ca_nombre ASC");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function FiltroDistrito($datab){
        $this->DB2->select("Dist_nombre as Descripcion,Dis_Id as Id");
        $this->DB2->from("tbl_distrito");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        $this->DB2->join("tbl_rutas","Dist_Id = Ru_Dist_Id");
        if(!empty($datab['pais']))
            $this->DB2->where('P_Id',$datab['pais']);
        if(!empty($datab['division']))
            $this->DB2->where('Di_Id',$datab['division']);
        if(!empty($datab['canal']))
            $this->DB2->where('Ca_nombre',$datab['canal']);
        $this->DB2->group_by("Dist_nombre");
        $this->DB2->order_by("Dist_nombre ASC");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function FiltroRuta($datab){
        $this->DB2->select("Ru_nombre as Descripcion,Ru_Id as Id");
        $this->DB2->from("tbl_rutas");
        $this->DB2->join("tbl_distrito","Ru_Dist_Id = Dist_Id");
        $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
        $this->DB2->join("tbl_distribuidora","Ca_Dis_Id = Dis_Id");
        $this->DB2->join("tbl_division","Dis_Di_Id = Di_Id");
        $this->DB2->join("tbl_pais","Di_P_Id = P_Id");
        if(!empty($datab['pais']))
            $this->DB2->where('P_Id',$datab['pais']);
        if(!empty($datab['division']))
            $this->DB2->where('Di_Id',$datab['division']);
        if(!empty($datab['canal']))
            $this->DB2->where('Ca_nombre',$datab['canal']);
        if(!empty($datab['grupo']))
            $this->DB2->where('Dist_nombre',$datab['grupo']);
        $this->DB2->order_by("Ru_nombre ASC");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function FiltroTipoExhibidores($datab){
        $this->DB2->select('Subf_nombre as Descripcion,Subf_Id as Id');
        $this->DB2->from('tbl_subfamilia');
        $this->DB2->join("tbl_catalogo_productos","Subf_Id = Cat_Subf_Id");
        $this->DB2->join("tbl_catalogopro_x_canal","Catx_Cat_Id = Cat_Id");
        $this->DB2->where('Subf_Fa_Id',4);
        if(!empty($datab['exhibidores']))
            $this->DB2->where('Cat_descripcion',$datab['exhibidores']);
        $this->DB2->group_by("Subf_nombre");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }
    function FiltroExhibidores($datab){
        $this->DB2->select('Cat_descripcion as Descripcion,Cat_Id as Id');
        $this->DB2->from('tbl_catalogo_productos');
        $this->DB2->join("tbl_subfamilia","Cat_Subf_Id = Subf_Id");
        $this->DB2->join("tbl_catalogopro_x_canal","Catx_Cat_Id = Cat_Id");
        $this->DB2->where('Subf_Fa_Id',4);
        if(!empty($datab['tipoexhibidores']))
            $this->DB2->where('Subf_nombre',$datab['tipoexhibidores']);
        $this->DB2->group_by("Cat_descripcion");
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }
}
?>
