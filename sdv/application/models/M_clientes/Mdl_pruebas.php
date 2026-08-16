<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_pruebas extends CI_Model
{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }


    function plantilla_fullAC(){

        // $this->db->select("MAX(cc.Id_AC)")->from("actualizacion_clientes as cc");
        // $this->db->join("usuarios as uu","cc.Id_Usuarios = uu.Id_Usuarios");
        // $this->db->join("rutas as rr","uu.Id_Ruta = rr.Id_Ruta");
        // $this->db->join("distribuidora as dd","rr.Id_Distribuidora = dd.Id_Distribuidora");
        // $this->db->join("pais as pp","dd.Id_Pais = pp.Id_Pais");
        // $this->db->where("pp.Nombre_Pais","EL SALVADOR");
        // $this->db->where("rr.Id_Distribuidora",2);
        // $this->db->where("rr.Canal",'MAYOREO');
        // $this->db->where("cc.EstadoDescarga",1);
        // $this->db->group_by("cc.CodigoAC");
        // $subconsulta = $this->db->get_compiled_select();
        // $this->db->select("r.Nombre_Ruta,
        // c.CodigoAC,
        // c.NombreAC,
        // c.DireccionAC,
        // c.TelefonoAC,
        // c.ContactoAC,
        // c.EstadoAC,
        // c.OrdenVistaAC,
        // c.DiasAC,
        // c.FrecuencVisitaAC,
        // c.LatitudAC,
        // c.LongitudAC,
        // de.NombreDepartamento,
        // m.NombreMunicipio,
        // DATE(c.FechaACSer) AS Fecha_Ingreso,DATE(c.FechaASupervisor) AS FechaSupervisor,DATE(c.FechaDescarga) AS FechaDescarga,c.Motivos, month(c.FechaDescarga) as mes");
        // $this->db->from('actualizacion_clientes as c');
        // $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        // $this->db->join("rutas as r","u.Id_Ruta = r.Id_Ruta");
        // $this->db->join("distribuidora as d","r.Id_Distribuidora = d.Id_Distribuidora");
        // $this->db->join("pais as p","d.Id_Pais = p.Id_Pais");
        // $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        // $this->db->join("departamento as de","m.Id_Departamento = de.Id_Departamento");
        // $this->db->where("c.Id_AC IN ($subconsulta)", NULL, FALSE);
        // $this->db->order_by("r.Nombre_Ruta ASC,c.CodigoAC ASC");

        $query_select = "SELECT 
        d.Nombre_Distribuidora,
        r.Nombre_Ruta,
        c.CodigoAC,
        c.NombreAC,
        c.DireccionAC,
        c.TelefonoAC,
        c.ContactoAC,
        c.EstadoAC,
        c.OrdenVistaAC,
        c.DiasAC,
        c.FrecuencVisitaAC,
        c.LatitudAC,
        c.LongitudAC,
        de.NombreDepartamento,
        m.NombreMunicipio,
        DATE(c.FechaACSer) AS Fecha_Ingreso,DATE(c.FechaASupervisor) AS FechaSupervisor,DATE(c.FechaDescarga) AS FechaDescarga,c.Motivos, month(c.FechaDescarga) as mes,
        c.Numero_RegistroAC,c.NitAC,c.DuiAC,c.Ord_VisitaSema
        FROM actualizacion_clientes as c
        INNER JOIN usuarios as u ON c.Id_Usuarios = u.Id_Usuarios
        INNER JOIN rutas as r ON u.Id_Ruta = r.Id_Ruta
        INNER JOIN distribuidora as d ON r.Id_Distribuidora = d.Id_Distribuidora
        INNER JOIN pais as p ON d.Id_Pais = p.Id_Pais
        INNER JOIN municipio as m ON c.Id_Municipio = m.Id_Municipio
        INNER JOIN departamento as de ON m.Id_Departamento = de.Id_Departamento
        WHERE c.Id_AC IN (

          SELECT MAX(cc.Id_AC) FROM actualizacion_clientes as cc
          INNER JOIN usuarios as uu ON cc.Id_Usuarios = uu.Id_Usuarios
          INNER JOIN rutas as rr ON uu.Id_Ruta = rr.Id_Ruta
          INNER JOIN distribuidora as dd ON rr.Id_Distribuidora = dd.Id_Distribuidora
          INNER JOIN pais as pp ON dd.Id_Pais = pp.Id_Pais
          WHERE pp.Nombre_Pais = 'EL SALVADOR'
          -- AND rr.Id_Distribuidora = 1
          -- AND rr.Canal = 'MAYOREO'
          AND DATE(FechaDescarga) BETWEEN '2021-04-08' AND '2021-04-23'
          AND cc.EstadoDescarga = 1
          GROUP BY cc.CodigoAC

        ) ORDER BY r.Nombre_Ruta ASC,c.CodigoAC ASC";



        $resultado = $this->db->query($query_select);
        $resultado = $resultado->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }



        // $query = $this->db->get();
        // $resultado = $query->result();
        // if(!empty($resultado)){
        //   return $resultado;
        // }else{
        //   return array();
        // }



      }
      
      /*CORRECCION DE ACTUALIZACION CLIENTE */

      function BusAcXusuario($id_usurioRuta){

        $query_upprocesados = "
        SELECT Id_Cliente,CodigoAC,NombreAC,Id_Usuarios,EstadoAC,EstadoVigente, COUNT(*) Total
        FROM actualizacion_clientes
        WHERE Id_Usuarios = ".$id_usurioRuta."
        GROUP BY CodigoAC
        HAVING COUNT(*) > 0";
        $query_Proccess = $this->db->query($query_upprocesados);

        $resultado = $query_Proccess->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

      }


      public function Cuentadescargar($codigoAC,$id_usuario){
        $this->db->select('COUNT(*) as cveces');
        $this->db->from('actualizacion_clientes');
        $this->db->where('CodigoAC', $codigoAC);
        $this->db->where('Id_Usuarios', $id_usuario);
        $this->db->where('EstadoDescarga', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }
    }


      function AtraparRepetidoAC($codigorepetido,$idus){

        $query_upprocesados = "SELECT * FROM actualizacion_clientes WHERE CodigoAC='".$codigorepetido."' AND Id_Usuarios = ".$idus." ORDER BY FechaACTel ASC";
        $query_Proccess = $this->db->query($query_upprocesados);

        $resultado = $query_Proccess->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

      }


      function CambEstadoVigente($estado,$idac){

        $query_upprocesados = "UPDATE actualizacion_clientes SET EstadoVigente = ".$estado." WHERE Id_AC=".$idac."";
        $this->db->query($query_upprocesados);

        if($this->db->affected_rows() > 0 ){
          return true;
        }else{
          return false;
        }
      }

  
    /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<*/
    /* SQL PARA EL ORDENAMIENTO POR DIAS 13/03/2021*/
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

    function dias_VisitaXCliente($id_Cliente){
      $this->db->select("Dias");
      $this->db->from('clientes');
      $this->db->where('Id_Cliente',$id_Cliente);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->row();
      }else{
        return array();
      }
  }


    function ls_clientesXdia($id_usuarios,$valDia){


      $filtroDia = '';$filtroOrdenDia = '';
      if ( strcmp($valDia, 'L_1') == 0 ){
        $filtroDia = "SUBSTRING_INDEX(Dias,',',1)";
        $filtroOrdenDia = "CONVERT(SUBSTRING_INDEX(Ord_VisitaSema,',',1),UNSIGNED INTEGER)";
      }
      if ( strcmp($valDia, 'M_1') == 0 ){
        $filtroDia = "SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',2),',',-1)";
        $filtroOrdenDia = "CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',2),',',-1),UNSIGNED INTEGER)";
      }
      if ( strcmp($valDia, 'I_1') == 0 ){
        $filtroDia = "SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',3),',',-1)";
        $filtroOrdenDia = "CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',3),',',-1),UNSIGNED INTEGER)";
      }
      if ( strcmp($valDia, 'J_1') == 0 ){
        $filtroDia = "SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',4),',',-1)";
        $filtroOrdenDia = "CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',4),',',-1),UNSIGNED INTEGER)";
      }
      if ( strcmp($valDia, 'V_1') == 0 ){
        $filtroDia = "SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',5),',',-1)";
        $filtroOrdenDia = "CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',5),',',-1),UNSIGNED INTEGER)";
      }
      if ( strcmp($valDia, 'S_1') == 0 ){
        $filtroDia = "SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',6),',',-1)";
        $filtroOrdenDia = "CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',6),',',-1),UNSIGNED INTEGER)";
      }
      if ( strcmp($valDia, 'D_1') == 0 ){
        $filtroDia = "SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',7),',',-1)";
        $filtroOrdenDia = "CONVERT(SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',7),',',-1),UNSIGNED INTEGER)";
      }

      $query_select = "SELECT Id_Cliente,Codigo,Ord_VisitaSema,SUBSTRING_INDEX(Dias,',',1) AS Lunes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',2),',',-1) AS Martes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',3),',',-1) AS Miercoles,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',4),',',-1) AS Jueves,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',5),',',-1) AS Viernes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',6),',',-1) AS Sabado,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Dias,',',7),',',-1) AS Domingo,
      SUBSTRING_INDEX(Ord_VisitaSema,',',1) AS OrdLunes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',2),',',-1) AS OrdMartes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',3),',',-1) AS OrdMiercoles,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',4),',',-1) AS OrdJueves,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',5),',',-1) AS OrdViernes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',6),',',-1) AS OrdSabado,
      SUBSTRING_INDEX(SUBSTRING_INDEX(Ord_VisitaSema,',',7),',',-1) AS OrdDomingo
      FROM clientes WHERE Id_Usuarios = ".$id_usuarios." AND ".$filtroDia."='".$valDia."' AND Estado='P' AND quienresolucion!='CB' AND estado_w ='1' ORDER BY ".$filtroOrdenDia." ASC";

      $resultado = $this->db->query($query_select);
      $resultado = $resultado->result();
      if(!empty($resultado)){
          return $resultado;
      }else{
          return array();
      }


    }

    function ordenamiento_completo($datas){
      return $this->db->update_batch('clientes', $datas,'Id_Cliente');
    }



}
?>