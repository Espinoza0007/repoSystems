<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_exhibidores extends CI_Model
{
    private $DB2;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }


    function guardarDatos_Generales($datas){
        $this->db->insert('fotos_actualizacion_exh', $datas);
        if($this->db->affected_rows() > 0 ){
          return true;
        }else{
          return false;
        }
    }

    function guardarActualizacionExh($datas){
        return $this->db->insert_batch('actualizacion_exhibidores', $datas);
        // if($this->db->affected_rows() > 0 ){
        //   return true;
        // }else{
        //   return false;
        // }
    }
    
    function ok_actualizadoExh($tabla,$datas,$idcliente){
        $this->db->where('Id_Cliente',$idcliente);
        $this->db->update('clientes', $datas);
        if($this->db->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }

    function list_exhibidor(){
        $query = $this->db->get('exhibidores');
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    function ExhibidoresFacturados($idusuario){
        $this->db->select('cex.Id_Exhibidores,c.Codigo as exhfact,exh.SKU_Exhibidor,exh.NombreExhibidor,cex.Cantidad');
        $this->db->from('clientes_exhibidor cex');
        $this->db->join("exhibidores as exh","cex.Id_Exhibidores = exh.Id_Exhibidores");    
        $this->db->join("clientes as c","cex.Id_Cliente = c.Id_Cliente");      
        $this->db->where('c.Id_Usuarios',$idusuario);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function ClientesExhibiEncuesta($idusuario,$estadoactualizado){

        /*FILTRO PARA EVITAR LOS DUPLICADOS*/
        $this->db->select("MIN(cc.Id_Cliente)")->from("clientes as cc");
        $this->db->join("usuarios as uu","cc.Id_Usuarios = uu.Id_Usuarios");
        $this->db->join("rutas as rr","uu.Id_Ruta = rr.Id_Ruta");
        $this->db->join("distribuidora as dd","rr.Id_Distribuidora = dd.Id_Distribuidora");
        $this->db->join("pais as pp","dd.Id_Pais = pp.Id_Pais");
        $this->db->where('cc.estado_w',1);
        $this->db->where('cc.Estado_Analista','A');
        $this->db->where('cc.quienresolucion!=','CB');
        // $this->db->where('cc.ActuClientes',$estadoactualizado);
        $this->db->group_by(array("cc.Id_Usuarios", "cc.Codigo"));
        $subconsulta =  $this->db->get_compiled_select();

        $this->db->select('c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Contacto,c.estado_w,c.Dias,ru.Nombre_Ruta,c.ActuExhibidor');
        $this->db->from('clientes as c');
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");   
        $this->db->where("c.Id_Cliente IN ($subconsulta)", NULL, FALSE);     
        $this->db->where('c.Id_Usuarios',$idusuario);
        $this->db->where('c.estado_w',1);
        $this->db->where('c.Estado_Analista','A');
        $this->db->where('c.ActuExhibidor!=','SI');
        $this->db->where('c.quienresolucion!=','CB');
        // $this->db->where('quienresolucion!=','SDV');
        // $this->db->where('c.ActuExhibidor',$estadoactualizado);
        $this->db->order_by('c.Codigo', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }



    }
    /*-----------------------------------------------------*/
    /*----------------CLIENTES ACTUALIZADOS----------------*/
    /*-----------------------------------------------------*/
    function ClientesActualizadosExhibidores($idusuario){
        $this->db->select('f.CodigoCliente,f.NombreCliente,f.DireccionCliente,f.ContactoCliente');
        $this->db->from('fotos_actualizacion_exh as f');
        $this->db->join("clientes as c","c.Id_Cliente = f.Id_Cliente");
        $this->db->where('c.estado_w','1');
        // $this->db->join("actualizacion_exhibidores a ","f.Id_Cliente = a.Id_Cliente");
        $this->db->where('f.Id_Usuarios',$idusuario);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function ContarClientesNoActualizadoExh($idusuario){
        $this->db->select('COUNT(*) as resultotal');
        $this->db->from('clientes');
        $this->db->where('Id_Usuarios',$idusuario);
        $this->db->where('estado_w','1');
        $this->db->where('quienresolucion!=','CB');
        $this->db->where('Estado','P');
        $this->db->where('ActuExhibidor','NO');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }
    }

    function ReporteExhibidor(){
        $this->db->select('ru.Nombre_Ruta,f.CodigoCliente,f.NombreCliente,f.ContactoCliente,f.DireccionCliente,f.FechaObservacionTel,f.FechaObservacionSer,f.FotoObservacion,f.LatitudObservacion,f.LongitudObservacion,p.Nombre_Pais,d.Nombre_Distribuidora,d.Division,ru.Canal,ru.Grupo,f.Con');
        $this->db->from('fotos_actualizacion_exh as f');
        $this->db->join("usuarios as u","f.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("distribuidora as d","ru.Id_Distribuidora = d.Id_Distribuidora");
        $this->db->join("pais as p","p.Id_Pais = d.Id_Pais");
        $this->db->where('p.Nombre_Pais','REPUBLICA DOMINICANA');
        // $this->db->group_by('f.CodigoCliente,e.SKU_Exhibidor,a.TipoActualizacion');
        $this->db->order_by('f.Nombre_Ruta');
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }


    /*CORREGIR REGISTROS REPETIDOS*/

    function AuxilioRutas(){

        $query_upprocesados = "SELECT * FROM usuarios";
        $query_Proccess = $this->db->query($query_upprocesados);

        $resultado = $query_Proccess->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

      }


    function AtraparRepetido($codigorepetido,$idus){

        $query_upprocesados = "SELECT * FROM clientes WHERE Codigo='".$codigorepetido."' AND Id_Usuarios = ".$idus." ORDER BY Id_Cliente ASC";
        $query_Proccess = $this->db->query($query_upprocesados);

        $resultado = $query_Proccess->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

      }


    function MetePresoAtrapado($idcli){

        $query_upprocesados = "UPDATE clientes SET estado_w = 0, Comentario_E = 'REPETIDO' WHERE Id_Cliente=".$idcli."";
        $this->db->query($query_upprocesados);

        if($this->db->affected_rows() > 0 ){
          return true;
        }else{
          return false;
        }
      }

    function AuxilioDuplicados($id_usurioRuta){

        $query_upprocesados = "
        SELECT Id_Cliente,Codigo,Nombre,Id_Usuarios,estado_w,Comentario_E, COUNT(*) Total
        FROM clientes
        WHERE Id_Usuarios = ".$id_usurioRuta." AND Estado = 'P'
        GROUP BY Codigo
        HAVING COUNT(*) > 1";
        $query_Proccess = $this->db->query($query_upprocesados);

        $resultado = $query_Proccess->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

      }
      
      /*CAMBIOS 06/02/2021*/

    function ExhibidoresCensados($idusuario){
        $this->db->select('f.CodigoCliente,a.Id_Exhibidores,a.TipoActualizacion,exh.SKU_Exhibidor,exh.NombreExhibidor,a.Cantidad,a.RespuestaObservacion,a.Comentario,f.FechaObservacionTel');
        $this->db->from('actualizacion_exhibidores as a');
        $this->db->join("fotos_actualizacion_exh as f","a.Id_Cliente = f.Id_Cliente");
        $this->db->join("clientes as c","a.Id_Cliente = c.Id_Cliente");
        $this->db->join("exhibidores as exh","a.Id_Exhibidores = exh.Id_Exhibidores");  
        $this->db->where('f.Id_Usuarios',$idusuario);

        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    /* CAMBIOS 07/07/2021*/
    public function detalle_exhibidores($param){

        $this->DB2->select('e.*,Cat_descripcion as Ste_Cat_Id_Descripcion,CURDATE() Fecha_Sincronizacion');
        $this->DB2->from("tbl_status_exhibidores as e");
        $this->DB2->join("tbl_cliente","Ste_Cli_Id = Cli_Id");
        $this->DB2->join("tbl_rutas","Cli_Ru_Id = Ru_Id");
        $this->DB2->join("tbl_catalogo_productos as cat","Ste_Cat_Id = Cat_Id");
        $this->DB2->where('Ru_Id',$param['ruta']);
        $this->DB2->where('Ste_eliminado',0);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }
    public function tipo_exhibidores(){
        $this->DB2->select('Subf_nombre');
        $this->DB2->from('tbl_subfamilia');
        $this->DB2->where('Subf_Fa_Id',4);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }
    function ContarTokenExh($TokenBuscar){
        $this->DB2->select('COUNT(*) as totaltoken');
        $this->DB2->from('tbl_status_exhibidores');
        $this->DB2->where('Ste_token',$TokenBuscar);
        $this->DB2->where('Ste_Cat_Id!=','7777777');
        $query = $this->DB2->get();
        if ($query->num_rows() > 0){
          return $query->row();
        }else{
          return 0;
        }
    }
    function ContarTokenExhSin($cliid,$cat_id){
        $this->DB2->select('COUNT(*) as totaltokensin');
        $this->DB2->from('tbl_status_exhibidores');
        $this->DB2->where('Ste_Cli_Id',$cliid);
        $this->DB2->where('Ste_Cat_Id',$cat_id);
        $query = $this->DB2->get();
        if ($query->num_rows() > 0){
          return $query->row();
        }else{
          return 0;
        }
    }
    function Insertar_StatusExhibidores($datas){
        return $this->DB2->insert_batch('tbl_status_exhibidores', $datas);
    }
    function Modificar_StatusExhibidores($datas){
        return $this->DB2->update_batch('tbl_status_exhibidores', $datas,'Ste_token');
    }
    function bloqCliActuExh($data,$idCli){
        $this->DB2->where('Cli_Id',$idCli);
        $this->DB2->update('tbl_cliente', $data);
        if($this->DB2->affected_rows() > 0 ){
          return true;
        }else{
          return false;
        }
    }
    /*CAMBIOS 20/08/2021*/
    function quitar_sin_exhibidor($idCli){
        $this->DB2->where('Ste_Cli_Id',$idCli);
        $this->DB2->where('Ste_Cat_Id','7777777');
        $this->DB2->delete('tbl_status_exhibidores');
        if($this->DB2->affected_rows() > 0 ){
          return true;
        }else{
          return false;
        }
    }
    /*CAMBIOS 29/10/2021*/
    function ls_ste_tipo_motivos(){
        $query = $this->DB2->get('tbl_ste_tipo_motivos');
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }
    function ls_ste_motivos(){
        $query = $this->DB2->get('tbl_ste_motivos');
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }
    /*MODIFICACION 25/11/2021*/
    public function error_desconocido($datas){
      $this->db->insert('tbl_errores', $datas);
      if($this->db->affected_rows() > 0 ){
        return true;
      }else{
        return false;
      }
    }
    function Insertar_StatusExhibidores_BK_i($datas){
      return $this->DB2->insert_batch('tbl_status_exhibidores_bk_i', $datas);
    }
    function Insertar_StatusExhibidores_BK_a($datas){
      return $this->DB2->insert_batch('tbl_status_exhibidores_bk_a', $datas);
    }
}
?>