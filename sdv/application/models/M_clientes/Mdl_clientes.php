<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_clientes extends CI_Model
{
    private $DB2;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2',TRUE);
    }

    function TipoCLiente($usuario){
      $this->DB2->select("Ca_nombre");
      $this->DB2->from("tbl_usuario");
      $this->DB2->join("tbl_rutas","Usu_Ru_Id = Ru_Id");
      $this->DB2->join("tbl_distrito","Ru_Dist_Id = Dist_Id");
      $this->DB2->join("tbl_canal","Dist_Ca_Id = Ca_Id");
      $this->DB2->where("Usu_Id",$usuario);
      $query = $this->DB2->get();
      if ($query->num_rows() > 0){
        return $query->row();
      }else{
        return array();
      }
    }



    /*-----------------------------------------------------------*/
    /*------------FORMULARIO ACTUALIZACION CLIENTES--------------*/
    /*-----------------------------------------------------------*/

    /*EDITAR FORMMULARIO DE ACTUALIZACION DE CLIENTES PARTE DE SUPERVISOR */
    function cliente_seleccionadoAC($idseleccionado){
      $this->db->where('Id_AC',$idseleccionado);
      $query = $this->db->get('actualizacion_clientes');
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }

  function departamentoAC($pais){

    $this->db->select("d.Id_Departamento,d.NombreDepartamento,p.Nombre_Pais");
    $this->db->from('departamento as d');
    $this->db->join("pais as p","d.Id_Pais = p.Id_Pais");
    $this->db->where('p.Nombre_Pais',$pais);
    $this->db->where('d.NombreDepartamento!=','DESCONOCIDO');
    $this->db->order_by("d.NombreDepartamento DESC");
    $query = $this->db->get();
    $resultado = $query->result();
    if(!empty($resultado)){
      return $resultado;
    }else{
      return array();
    }
}


function municipioAC($pais,$iddepa){

  $this->db->select("m.Id_Municipio,m.NombreMunicipio,d.NombreDepartamento,p.Nombre_Pais");
  $this->db->from('municipio as m');
  $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
  $this->db->join("pais as p","d.Id_Pais = p.Id_Pais");
  $this->db->where('p.Nombre_Pais',$pais);
  $this->db->where('m.NombreMunicipio!=','DESCONOCIDO');
  $this->db->where('d.Id_Departamento',$iddepa);
  $this->db->order_by("m.NombreMunicipio DESC");
  $query = $this->db->get();
  $resultado = $query->result();
  if(!empty($resultado)){
    return $resultado;
  }else{
    return array();
  }
}

function tipofacturacionAC(){
  $query = $this->db->get('tipo_facturacion');
  $resultado = $query->result();
  if(!empty($resultado)){
    return $resultado;
  }else{
    return array();
  }
}


    function ClientesSINActualizar($idusuario){
      $query_select = "SELECT r.Nombre_Ruta,c.Dui,c.Numero_Registro,c.RefUno,c.Orden_Visita,c.Nit,de.NombreDepartamento,m.NombreMunicipio,c.Id_Cliente,c.Telefono,c.Codigo,c.Nombre,c.Direccion,c.Contacto,c.estado_w,c.Latitud,c.Longitud,c.Dias,tpf.Id_Tfacturacion,tpf.Nombre_Tfacturacion,c.ActuExhibidor,c.ActuClientes,c.Cantidad_CMR,
      SUBSTRING_INDEX(c.Dias,',',1) AS Lunes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Dias,',',2),',',-1) AS Martes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Dias,',',3),',',-1) AS Miercoles,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Dias,',',4),',',-1) AS Jueves,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Dias,',',5),',',-1) AS Viernes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Dias,',',6),',',-1) AS Sabado,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Dias,',',7),',',-1) AS Domingo,
      c.UlFechaActuCli,c.UlFechaActuExh,c.Ord_VisitaSema,
      SUBSTRING_INDEX(c.Ord_VisitaSema,',',1) AS OrdLunes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Ord_VisitaSema,',',2),',',-1) AS OrdMartes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Ord_VisitaSema,',',3),',',-1) AS OrdMiercoles,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Ord_VisitaSema,',',4),',',-1) AS OrdJueves,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Ord_VisitaSema,',',5),',',-1) AS OrdViernes,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Ord_VisitaSema,',',6),',',-1) AS OrdSabado,
      SUBSTRING_INDEX(SUBSTRING_INDEX(c.Ord_VisitaSema,',',7),',',-1) AS OrdDomingo
      FROM clientes as c
      INNER JOIN usuarios as u ON c.Id_Usuarios = u.Id_Usuarios
      INNER JOIN rutas as r ON u.Id_Ruta = r.Id_Ruta
      INNER JOIN distribuidora as d ON r.Id_Distribuidora = d.Id_Distribuidora
      INNER JOIN pais as p ON d.Id_Pais = p.Id_Pais
      INNER JOIN municipio as m ON c.Id_Municipio = m.Id_Municipio
      INNER JOIN departamento as de ON m.Id_Departamento = de.Id_Departamento
      INNER JOIN tipo_facturacion as tpf ON c.Id_Tfacturacion = tpf.Id_Tfacturacion
      WHERE c.Id_Cliente IN (
        SELECT MIN(cc.Id_Cliente) FROM clientes as cc
        INNER JOIN usuarios as uu ON cc.Id_Usuarios = uu.Id_Usuarios
        INNER JOIN rutas as rr ON uu.Id_Ruta = rr.Id_Ruta
        INNER JOIN distribuidora as dd ON rr.Id_Distribuidora = dd.Id_Distribuidora
        INNER JOIN pais as pp ON dd.Id_Pais = pp.Id_Pais
        WHERE cc.Id_Usuarios = ".$idusuario." 
        AND cc.Estado = 'P'
        AND cc.quienresolucion!='CB'
        GROUP BY cc.Id_Usuarios,cc.Codigo
      )
      AND c.Id_Usuarios = ".$idusuario."
      AND c.Estado = 'P'
      AND c.quienresolucion!='CB'
      AND c.Codigo!='0'
      ORDER BY c.Codigo ASC";
      $resultado = $this->db->query($query_select);
      $resultado = $resultado->result();
      if(!empty($resultado)){
          return $resultado;
      }else{
          return array();
      }

    }

    function Lista_de_clientes($idusuario,$pais,$canal){
      $this->DB2->select('tbl_cliente.*,CURDATE() Fecha_Sincronizacion,Usu_Ru_Id, Ru_nombre, Di_nombre,Dep_descripcion,Mun_descripcion,Gir_Id,Gir_descripcion,Tpv_Id,Tpv_descripcion');
      $this->DB2->from('tbl_cliente');
      $this->DB2->join('tbl_municipio', 'Cli_Mun_Id = Mun_Id','left');
      $this->DB2->join('tbl_departamento', 'Mun_Dep_Id = Dep_Id','left');
      $this->DB2->join('tbl_usuario', 'Usu_Ru_Id = Cli_Ru_Id','left');
      $this->DB2->join('tbl_rutas', 'Usu_Ru_Id = Ru_Id','left');
      $this->DB2->join('tbl_distrito', 'Ru_Dist_Id = Dist_Id','left');
      $this->DB2->join('tbl_canal', 'Dist_Ca_Id = Ca_Id','left');
      $this->DB2->join('tbl_distribuidora', 'Ca_Dis_Id = Dis_Id','left');
      $this->DB2->join('tbl_division', 'Dis_Di_Id = Di_Id','left');
      $this->DB2->join('tbl_giro_negocio', 'Cli_Gir_Id = Gir_Id','left');
      $this->DB2->join('tbl_tipo_punto_venta', 'Tpv_Id = Gir_Tpv_Id','left');
      $this->DB2->where('Usu_Id', $idusuario);
      $this->DB2->where('Cli_codigo!=', '0');
      $this->DB2->where('Cli_codigo!=', '0000000');
      $this->DB2->where('Cli_estado_sys', 'P');
      $this->DB2->where('Cli_estado_descarga', 1);
      //validacion de filtro 
      if(( $canal == 'PREFERENCIAL') && ($pais == 'GUATEMALA' ) ||
         ( $canal == 'PREFERENCIAL') && ($pais == 'HONDURAS'  )){
           $this->DB2->where("SUBSTRING(Cli_codigo, 1,1) = 'P' ");
      }
      $query = $this->DB2->get();
      $resultado = $query->result();
      if(!empty($resultado)){
          return $resultado;
      }else{
          return array();
      }
    }

    function guardar_actualizacionCLI($datas){
      $this->DB2->insert('tbl_actualizacion_cliente', $datas);
        if($this->DB2->affected_rows() > 0 ){
          return true;
        }else{
          return false;
      }
    }

    function guardar_act($datas){
      $this->DB2->insert('tbl_act_cliente', $datas);
        if($this->DB2->affected_rows() > 0 ){
          return true;
        }else{
          return false;
      }
    }

    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/
    /*LISTA CLIENTES ACTUALIZADOS SUPERVISOR*/
    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/
    function contar_clientesSu_AC($datosb){

      $where_ruta = "";
      $query_select = "SELECT COUNT(distinct ac.Id_Cliente) as totolu
      FROM actualizacion_clientes as ac
      INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios
      INNER JOIN rutas as ru ON u.Id_Ruta = ru.Id_Ruta
      INNER JOIN supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor
      INNER JOIN municipio as m ON ac.Id_Municipio = m.Id_Municipio
      INNER JOIN departamento as d ON m.Id_Departamento = d.Id_Departamento
      INNER JOIN distribuidora as dd ON ru.Id_Distribuidora = dd.Id_Distribuidora
      INNER JOIN pais as p ON dd.Id_Pais = p.Id_Pais
      INNER JOIN ( SELECT Id_Cliente,MAX(FechaACSer) as fecha_max
        FROM actualizacion_clientes GROUP BY Id_Cliente
      ) as b ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaACSer = fecha_max WHERE s.Id_Supervisor = ".$datosb['idsupervisor']."
      AND ac.EstadoASupervisor = 'N'";

      if(!empty($datosb['rutas'])){
        $where_ruta = " AND ru.Id_Ruta = ".$datosb['rutas'];
      }else{}
      $query_select.=$where_ruta;
      $resultado = $this->db->query($query_select);
      if ($resultado->num_rows() > 0){
        return $resultado->row();
      }else{
        return array();
      }

      
    }


    function lista_ClientesAC($datosb,$start,$limit){

      $where_ruta = "";$where_distribuidora = "";
      $query_select = "SELECT m.Id_Municipio,d.Id_Departamento,ac.Id_AC,ac.Id_Cliente,ac.CodigoAC,ac.NombreAC,ac.DireccionAC,ac.TelefonoAC,ac.ContactoAC,ac.OrdenVistaAC,ac.DiasAC,ac.LatitudAC,ac.LongitudAC,ru.Id_Ruta,ru.Nombre_Ruta,ac.FechaACSer,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,ac.EstadoAC,ac.FrecuencVisitaAC,ac.Ord_VisitaSema
      FROM actualizacion_clientes as ac
      INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios
      INNER JOIN rutas as ru ON u.Id_Ruta = ru.Id_Ruta
      INNER JOIN supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor
      INNER JOIN municipio as m ON ac.Id_Municipio = m.Id_Municipio
      INNER JOIN departamento as d ON m.Id_Departamento = d.Id_Departamento
      INNER JOIN distribuidora as dd ON ru.Id_Distribuidora = dd.Id_Distribuidora
      INNER JOIN pais as p ON dd.Id_Pais = p.Id_Pais
      INNER JOIN ( SELECT Id_Cliente,MAX(FechaACSer) as fecha_max
        FROM actualizacion_clientes GROUP BY Id_Cliente
      ) as b ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaACSer = fecha_max WHERE ac.EstadoASupervisor = 'N' AND s.Id_Supervisor = ".$datosb['idsupervisor'];

      if(!empty($datosb['rutas'])){
        $where_ruta = " AND ru.Id_Ruta = ".$datosb['rutas'];
      }else{}

      $query_select.=$where_ruta." ORDER BY ru.Nombre_Ruta,ac.CodigoAC ASC LIMIT ".$start.",".$limit."";
      $resultado = $this->db->query($query_select);
      $resultado = $resultado->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }


      // You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'SELECT `Id_Cliente`, `Id_Municipio`, MAX(FechaACSer)as fecha_max FROM `actualiza' at line 8
      // $query_upprocesados = "SELECT m.Id_Municipio,d.Id_Departamento,ac.Id_AC,ac.Id_Cliente,ac.CodigoAC,ac.NombreAC,ac.DireccionAC,ac.TelefonoAC,ac.ContactoAC,ac.OrdenVistaAC,ac.DiasAC,ac.LatitudAC,ac.LongitudAC,ru.Id_Ruta,ru.Nombre_Ruta,ac.FechaACSer,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,ac.EstadoAC,ac.FrecuencVisitaAC 
      // FROM actualizacion_clientes ac 
      // INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios 
      // INNER JOIN rutas as ru ON u.Id_Ruta = ru.Id_Ruta 
      // INNER JOIN supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor 
      // INNER JOIN municipio as m ON ac.Id_Municipio = m.Id_Municipio 
      // INNER JOIN departamento as d ON m.Id_Departamento = d.Id_Departamento 
      // INNER JOIN(SELECT Id_Cliente,Id_Municipio,MAX(FechaACSer)as fecha_max 
      // FROM actualizacion_clientes 
      // GROUP BY Id_Cliente) b 
      // ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaACSer = fecha_max 
      // WHERE s.Id_Supervisor =".$datosb['idsupervisor']." AND ac.EstadoASupervisor = 'N' LIMIT ".$limit.",".$start;

      // $query = $this->db->query($query_upprocesados);




      
    }


    public function modificar_actualizacionCLi($datas,$idclienteAC){

        $this->db->where('Id_AC',$idclienteAC);
        $this->db->update('actualizacion_clientes', $datas);
               
            if($this->db->affected_rows() > 0 ){
                return true;
            }else{
                return false;
            }
    }

    public function modificar_actualizacionCLiAD($datas,$idclienteAC){

      $this->db->where('Id_AC',$idclienteAC);
      $this->db->update('actualizacion_clientes', $datas);
             
          if($this->db->affected_rows() > 0 ){
              return true;
          }else{
              return false;
          }
  }

    function lista_ClientesACC($datosb,$start,$limit){

        $this->db->select("MAX(cc.Id_Cliente)")->from("actualizacion_clientes as cc");
        $this->db->join("usuarios as uu","cc.Id_Usuarios = uu.Id_Usuarios");
        $this->db->join("rutas as ruu","uu.Id_Ruta = ruu.Id_Ruta");
        $this->db->join("supervisores as ss","ruu.Id_Supervisor = ss.Id_Supervisor");
        $this->db->where('ss.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where('cc.EstadoASupervisor', 'N');
        $this->db->group_by(array("cc.Id_Cliente"));
        $subconsulta =  $this->db->get_compiled_select();

        $this->db->select("ac.Id_AC,ac.Id_Cliente,ac.CodigoAC,ac.NombreAC,ac.DireccionAC,ac.TelefonoAC,ac.ContactoAC,ac.OrdenVistaAC,ac.DiasAC,ac.LatitudAC,ac.LongitudAC,ru.Id_Ruta,ru.Nombre_Ruta,ac.FechaACSer,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,ac.EstadoAC,ac.FrecuencVisitaAC");
        $this->db->from("actualizacion_clientes as ac");
        $this->db->join("usuarios as u","ac.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","ac.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where('ac.EstadoASupervisor', 'N');
        $this->db->where("ac.Id_Cliente IN ($subconsulta)", NULL, FALSE);

        $this->db->group_by(array("ac.Id_Cliente","DESC"));
        $this->db->order_by('ac.Id_AC', 'DESC');

        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }

    }
    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/
    /*LISTA CLIENTES ACTUALIZADOS SUPERVISOR*/
    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/


    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/
    /*LISTA CLIENTES ACTUALIZADOS ADMINISTRADOR*/
    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/

    function contar_clientesSu_ACAD($datosb){

      $where_ruta = ""; $where_distribuidora = "";
      $query_select = "SELECT COUNT(distinct ac.Id_Cliente) as totolu
      FROM actualizacion_clientes as ac
      INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios
      INNER JOIN rutas as ru ON u.Id_Ruta = ru.Id_Ruta
      INNER JOIN supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor
      INNER JOIN municipio as m ON ac.Id_Municipio = m.Id_Municipio
      INNER JOIN departamento as d ON m.Id_Departamento = d.Id_Departamento
      INNER JOIN distribuidora as dd ON ru.Id_Distribuidora = dd.Id_Distribuidora
      INNER JOIN pais as p ON dd.Id_Pais = p.Id_Pais
      INNER JOIN ( SELECT Id_Cliente,MAX(FechaASupervisor) as fecha_max
        FROM actualizacion_clientes GROUP BY Id_Cliente
      ) as b ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaASupervisor = fecha_max WHERE ac.EstadoASupervisor = 'A' AND ac.EstadoAAnalista='N' AND p.Nombre_Pais='".$this->session->userdata('pais')."'";

      if(!empty($datosb['rutas'])){
        $where_ruta = " AND ru.Id_Ruta = ".$datosb['rutas'];
      }else{}

      if(!empty($datosb['distribuidoras'])){
        $distrib = join(",",$datosb['distribuidoras']);   
        $where_distribuidora = " AND dd.Id_Distribuidora IN ( ".$distrib." )";
      }else{}

      $query_select.=$where_ruta.$where_distribuidora;
      $resultado = $this->db->query($query_select);
      if ($resultado->num_rows() > 0){
        return $resultado->row();
      }else{
        return array();
      }


    }

    function lista_ClientesACAD($datosb,$start,$limit){

      $where_ruta = "";$where_distribuidora = "";
      $query_select = "SELECT m.Id_Municipio,d.Id_Departamento,ac.Id_AC,ac.Id_Cliente,ac.CodigoAC,ac.NombreAC,ac.DireccionAC,ac.TelefonoAC,ac.ContactoAC,ac.OrdenVistaAC,ac.DiasAC,ac.LatitudAC,ac.LongitudAC,ru.Id_Ruta,ru.Nombre_Ruta,ac.FechaACSer,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,ac.EstadoAC,ac.FrecuencVisitaAC,ac.Id_Usuarios,ac.Ord_VisitaSema
      FROM actualizacion_clientes as ac
      INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios
      INNER JOIN rutas as ru ON u.Id_Ruta = ru.Id_Ruta
      INNER JOIN supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor
      INNER JOIN municipio as m ON ac.Id_Municipio = m.Id_Municipio
      INNER JOIN departamento as d ON m.Id_Departamento = d.Id_Departamento
      INNER JOIN distribuidora as dd ON ru.Id_Distribuidora = dd.Id_Distribuidora
      INNER JOIN pais as p ON dd.Id_Pais = p.Id_Pais
      INNER JOIN ( SELECT Id_Cliente,MAX(FechaASupervisor) as fecha_max
        FROM actualizacion_clientes GROUP BY Id_Cliente
      ) as b ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaASupervisor = fecha_max WHERE ac.EstadoASupervisor = 'A' AND ac.EstadoAAnalista='N' AND p.Nombre_Pais='".$this->session->userdata('pais')."'";

      if(!empty($datosb['rutas'])){
        $where_ruta = " AND ru.Id_Ruta = ".$datosb['rutas'];
      }else{}

      if(!empty($datosb['distribuidoras'])){
        $distrib = join(",",$datosb['distribuidoras']);   
        $where_distribuidora = " AND ru.Id_Distribuidora IN ( ".$distrib." )";
      }else{}

      $query_select.=$where_ruta.$where_distribuidora." ORDER BY ru.Nombre_Ruta,ac.CodigoAC ASC LIMIT ".$start.",".$limit."";
      $resultado = $this->db->query($query_select);
      $resultado = $resultado->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }


    }

    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/
    /*LISTA CLIENTES ACTUALIZADOS ADMINISTRADOR*/
    /*-----_____-----_____-----_____-----_____-----______------______------______------____------__--*/
    /* 00000000000000000000000000000000000000000000000000000000000*/

    function lista_clientes_ruta(){
        $query = $this->db->get('clientes');
        
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    function list_condicioncli(){
      $query = $this->DB2->get('tbl_condicion_cliente');
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }

    function list_gironegocio($datosb){
        $this->db->distinct();
        $this->db->select('g.Id_Gironegocio,g.Nombre_Gnegocio');
        $this->db->from('giro_negocio as g');
        $this->db->join('tipo_punto_venta as tpv','g.Id_Tpuntoventa = tpv.Id_Tpuntoventa');
        if(!empty($datosb['b_tpuntoventa'])){
            $this->db->where('g.Id_Tpuntoventa', $datosb['b_tpuntoventa']);
        }   
        $this->db->order_by('g.Nombre_Gnegocio', 'ASC');

        $query = $this->db->get();
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    function list_exhibidor(){
        $this->db->where('Id_Exhibidores!=', 777777777);
        $query = $this->db->get('exhibidores');
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    function exhibidor_select($idexhibidor){
        $this->db->where('Id_Exhibidores', $idexhibidor);
        $query = $this->db->get('exhibidores');

        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }


    function list_tfacturacion(){
      $this->DB2->where('Tfc_Id', 1);
      $query = $this->DB2->get('tbl_tipo_facturacion');
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }

    function list_tpuntoventa(){
      $query = $this->DB2->get('tbl_tipo_punto_venta');
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }

  function list_gironegocio_n(){
    $this->DB2->distinct();
    $this->DB2->select('Gir_Id,Gir_descripcion,Tpv_descripcion');
    $this->DB2->from('tbl_giro_negocio');
    $this->DB2->join('tbl_tipo_punto_venta','Gir_Tpv_Id = Tpv_Id');
    $this->DB2->order_by('Gir_descripcion', 'ASC');
    $query = $this->DB2->get();
    $resultado = $query->result();
    if(!empty($resultado)){
      return $resultado;
    }else{
      return array();
    }
  }
    function list_tcompra(){
        $query = $this->db->get('tipo_compra');
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    function list_tcontribuyente(){
        $query = $this->db->get('tipo_contribuyente');
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    function list_departamento($pais){
      $this->DB2->select('Dep_Id,Dep_descripcion');
      $this->DB2->from('tbl_departamento');
      $this->DB2->join('tbl_pais','Dep_P_Id = P_Id');
      $this->DB2->where('Dep_Id!=', 55);
      $this->DB2->where('P_nombre=', $pais);
      $query = $this->DB2->get();
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }

    function list_municipio_x_PAIS($pais){
      $this->DB2->select('Mun_Id,Mun_descripcion,Dep_descripcion');
      $this->DB2->from('tbl_municipio');
      $this->DB2->join('tbl_departamento','Mun_Dep_Id = Dep_Id');
      $this->DB2->join('tbl_pais','Dep_P_Id = P_Id');
      $this->DB2->where('P_nombre =', $pais);
      $this->DB2->where('Dep_Id!=', 55);
      $this->DB2->order_by('Mun_descripcion', 'ASC');
      $query = $this->DB2->get();
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }

    function list_municipio_n(){
        $this->db->distinct();
        $this->db->select('m.Id_Municipio,m.NombreMunicipio,d.NombreDepartamento');
        $this->db->from('municipio as m');
        $this->db->join('departamento as d','m.Id_Departamento = d.Id_Departamento'); 
        $this->db->order_by('m.NombreMunicipio', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    public function rutas(){
        //$this->db->like('Cod_Ocupacion', $parametro, 'both');
        $query = $this->db->get('rutas');
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

  public function guardar_cliente($datas){
    $this->DB2->insert('tbl_cliente', $datas);
    if($this->DB2->affected_rows() > 0 ){
      return true;
    }else{
      return false;
    }

  }

  public function guardar_cliente_backup($datas){
    $this->DB2->insert('ClientesN_Recuperados', $datas);
    if($this->DB2->affected_rows() > 0 ){
      return true;
    }else{
      return false;
    }

  }

  public function guardar_clienteAC_backup($datas){
    $this->db->insert('actualizacion_clientesbck', $datas);
    if($this->db->affected_rows() > 0 ){
      return true;
    }else{
      return false;
    }

  }

  public function ContarTokenExiste($TokenBuscar){
    $this->DB2->select('COUNT(*) as totaltoken');
    $this->DB2->from('tbl_cliente');
    $this->DB2->where('Cli_token',$TokenBuscar);
    $query = $this->DB2->get();
    if ($query->num_rows() > 0){
        return $query->row();
    }else{
        return 0;
    }
  }

  function guardarExhibidores($datas){
    return $this->db->insert_batch('clientes_exhibidor', $datas);
  }

    public function privilegios(){
        //$this->db->like('Cod_Privilegio', $parametro, 'both');
        $query = $this->db->get('privilegio');

        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }

    public function guardar_usuario($datas){
       
        $this->db->insert('usuario', $datas);
            if($this->db->affected_rows() > 0 ){
                return true;
            }else{
                return false;
            }
    }

    public function modificar_clientes($datas,$idcliente){
      $this->DB2->where('Cli_Id',$idcliente);
      $this->DB2->update('tbl_cliente', $datas);
      if($this->DB2->affected_rows() > 0 ){
        return true;
      }else{
        return false;
      }
    }
    public function modificar_clientesAC($datas,$idcliente){

      $this->db->where('Id_AC',$idcliente);
      $this->db->update('actualizacion_clientes', $datas);
               
      if($this->db->affected_rows() > 0 ){
        return true;
      }else{
        return false;
      }

    }

    function modificar_clientes_x_cod($datas,$codicli){

        $this->db->where('Codigo',$codicli);
        $this->db->update('clientes', $datas);
               
            if($this->db->affected_rows() > 0 ){
                return true;
            }else{
                return false;
            }
    }
    /*--------------------------------------------------------------*/
    /*---MODIFICAR CLIENTES POR RANGO DE FECHAS ESTADO PROCESADOS---*/
    /*--------------------------------------------------------------*/

    function m_clientes_procesados($datas,$fechaini,$fechafin,$arrgdistri,$fecha_actual_p,$rutas){

      $total_bueno = 0;
      $totalpartes =  count($arrgdistri);
      $estado_procesado = array();
      $where_ruta = "";
      if(!empty($rutas)){
        $where_ruta = " AND r.Id_Ruta = ".$rutas;
      }else{}

      for ($i=0; $i < $totalpartes; $i++) {

        $query_upprocesados = "UPDATE clientes as c INNER JOIN usuarios as u ON c.Id_Usuarios = u.Id_Usuarios INNER JOIN rutas as r ON u.Id_Ruta = r.Id_Ruta INNER JOIN distribuidora as d ON r.Id_Distribuidora = d.Id_Distribuidora SET c.Estado='P',c.Fecha_AprobacionA='".$fecha_actual_p."',c.EstadoDescarga= '1' WHERE c.Estado_Analista='A' AND c.EstadoDescarga= '0' AND d.Nombre_Distribuidora = '".$arrgdistri[$i]."'".$where_ruta."";
        $this->db->query($query_upprocesados);
        if($this->db->affected_rows() > 0 ){
          $total_bueno +=1;
        }else{
          $total_bueno +=1; 
        }

      }

      if($total_bueno == $totalpartes){
        return true;
      }else{
        return false;
      }

    }


    function m_clientes_procesadosAC($datas,$fechaini,$fechafin,$arrgdistri,$fecha_actual_p,$rutas){

      $total_bueno = 0;
      $totalpartes =  count($arrgdistri);
      $estado_procesado = array();

      $where_ruta = "";
      if(!empty($rutas)){
        $where_ruta = " AND r.Id_Ruta = ".$rutas;
      }else{}


      for ($i=0; $i < $totalpartes; $i++) {

        $query_upprocesados = "UPDATE actualizacion_clientes as ac 
        INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios 
        INNER JOIN rutas as r ON u.Id_Ruta = r.Id_Ruta 
        INNER JOIN distribuidora as d ON r.Id_Distribuidora = d.Id_Distribuidora 
        SET ac.EstadoDescarga=1,ac.FechaDescarga='".$fecha_actual_p."' WHERE ac.EstadoASupervisor='A' AND ac.EstadoAAnalista='A' AND ac.EstadoDescarga= 0 AND d.Nombre_Distribuidora = '".$arrgdistri[$i]."'".$where_ruta."";


        $this->db->query($query_upprocesados);
        if($this->db->affected_rows() > 0 ){
          $total_bueno +=1;
        }else{
          $total_bueno +=1; 
        }

      }

      if($total_bueno == $totalpartes){
        return true;
      }else{
        return false;
      }

    }

    public function count_repetidos($datosb){
        $this->db->select('COUNT(*) as crepetidos');
        $this->db->from('clientes');
    
        $this->db->where('Nombre', $datosb['nombre']);
        $this->db->where('Direccion', $datosb['direccion']);
        $this->db->where('Id_Municipio', $datosb['direccion']);
        $this->db->where('Telefono', $datosb['direccion']);
        $this->db->where('Contacto', $datosb['direccion']);
        $this->db->where('Propietario', $datosb['direccion']);
        $this->db->where('Dia_Cobro', $datosb['direccion']);
        

        $query = $this->db->get();

        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }

    }

    function obt_fotos_cli($codecli){
        $this->db->where('Id_Cliente', $codecli);
        $query = $this->db->get('clientes');
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }


  function salvar_exhibidores($datas){
    $this->db->insert('clientes_exhibidor', $datas);
    if($this->db->affected_rows() > 0 ){
      return true;
    }else{
      return false;
    }

  }

  // function modificar_info_clientes($datas,$codecli){
  //   $this->db->update('clientes', $datas);
  //   $this->db->where('Codigo', $codecli);
  //   if($this->db->affected_rows() > 0 ){
  //     return true;
  //   }else{
  //     return false;
  //   }

  // }

    function agregar_codigoClientes($datas){
       return $this->db->update_batch('clientes', $datas,'Id_Cliente');
    }  

    function modificar_info_clientes($datas){
       return $this->db->update_batch('clientes', $datas,'Codigo');
    }  
    function ProcesoResolAc($datas){
      return $this->db->update_batch('actualizacion_clientes', $datas,'Id_AC');
   }  
    function modificar_info_clientes_SOLO($datas){
       return $this->db->update_batch('clientes', $datas,'Id_Cliente');
    }
    
    function modificar_DB_nueva($datas){
      return $this->DB2->update_batch('tbl_cliente', $datas,'Cli_Id');
   }

    function ingresar_clientes_muchos($datas){
       return $this->db->insert_batch('clientes', $datas);
    }
    function ingresar_exhibidores_muchos($datas){
       return $this->db->insert_batch('clientes_exhibidor', $datas);
    }
    function ingresar_clientes_exhibidor($datas){
       return $this->db->insert_batch('clientes_exhibidor', $datas);
    }  
    function Parametros_Actualizacion(){
      $query = $this->db->get('parametros_actualizaciones');
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }

    function BackupClientesAC($datas){
      return $this->db->insert_batch('Clientesac_recuperados', $datas);
    }
    function BackupClientesN($datas){
      return $this->db->insert_batch('Clientesn_recuperados', $datas);
    }


    /* SINCRONIZACION DE CLIENTES APROBADOS DB NEW 02/07/2021 */
    function Add_SincroClientes($datas){
       return $this->DB2->insert_batch('tbl_cliente', $datas);
    }

    function Update_SicroCodClientes($datas){
       return $this->DB2->update_batch('tbl_cliente', $datas,'Cli_token');
    }  

    // ----- 29/10/2021 OBTENER USUARIO DE RUTA POR DESARROLLADOR -------------------------
    function get_usuario_ruta($ruta)
    {
        $this->DB2->select('*');
        $this->DB2->from('tbl_usuario');
        $this->DB2->join('tbl_rutas','Usu_Ru_Id = Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        $this->DB2->where('Ru_Id', $ruta);
        $this->DB2->where('Usu_Priv_Id', 2);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    public function get_usuario_ruta_DBA($usuario){
        $this->db->select('
            u.Id_Usuarios,u.Usuario,u.Contrasena,
            u.Nombre_Completo,r.Nombre_Ruta,
            tu.Id_Tipo_Usuario,tu.Clave,
            u.Estado_U,tu.Tipo_Usuario,
            p.Nombre_Pais,r.Canal
        ');
        $this->db->from('usuarios as u');
        $this->db->join("rutas as r","u.Id_Ruta = r.Id_Ruta");
        $this->db->join("distribuidora as d","r.Id_Distribuidora = d.Id_Distribuidora");
        $this->db->join("pais as p","d.Id_Pais = p.Id_Pais");
        $this->db->join('tipo_usuario as tu','u.Id_Tipo_Usuario = tu.Id_Tipo_Usuario');
        $this->db->where('u.Usuario', $usuario);

        $query = $this->db->get();
        $usuario = $query->result();

        if(!empty($usuario)){
            return $usuario;
        }else{
            return array();
        }
    }
    // ------------------------------------------------------------------------------------
    function get_canal_x_ruta($ruta){
      $this->DB2->select('Ca_Id,Ca_nombre');
      $this->DB2->from('tbl_rutas');
      $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
      $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
      $this->DB2->where('Ru_Id', $ruta);
      $query = $this->DB2->get();
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }









    function get_ls_repetidos_x_Ca_x_Dis($dis,$ca){
      $query_select = "select Cli_codigo,count(*) as Cantidad from tbl_cliente
      inner join tbl_rutas on Cli_Ru_Id = Ru_Id
      inner join tbl_distrito on Ru_Dist_Id = Dist_Id
      inner join tbl_canal on Dist_Ca_Id = Ca_Id
      inner join tbl_distribuidora on Ca_Dis_Id = Dis_Id
      inner join tbl_division on Dis_Di_Id = Di_Id
      inner join tbl_pais on Di_P_Id = P_Id
      where Dis_nombre = '".$dis."' and Ca_nombre = '".$ca."' and Cli_codigo!='0' group by Cli_codigo
      having count(*) > 1";
      $resultado = $this->DB2->query($query_select);
      $resultado = $resultado->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }
    function get_cli_repetidos($D,$C){
      $this->DB2->select('Cli_Id,Cli_codigo');
      $this->DB2->from('tbl_cliente');
      $this->DB2->join('tbl_rutas','Cli_Ru_Id = Ru_Id');
      $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
      $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
      $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
      $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
      $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
      $this->DB2->where('Dis_nombre ', $D);
      $this->DB2->where('Cli_codigo ', $C);
      $this->DB2->order_by("Cli_Id", "ASC");
      $query = $this->DB2->get();
      $resultado = $query->result();
      if(!empty($resultado)){
        return $resultado;
      }else{
        return array();
      }
    }
    function count_ids_repetidos($tbl,$id){
      $c_cli_id = '';$token = '';$k = 1;
      if( $tbl      == 'tbl_status_exhibidores' ){
        $c_cli_id   = 'Ste_Cli_Id';$token = 'Ste_token';
      }elseif( $tbl == 'tbl_marcacion_impulso' ){
        $c_cli_id   = 'Mar_Cli_Id';$token = 'Mar_token';
      }elseif( $tbl == 'tbl_control_inventario' ){
        $c_cli_id   = 'Cti_Cli_Id';$token = 'Cti_token';
      }elseif( $tbl == 'tbl_solicitud_apoyo' ){
        $c_cli_id   = 'Soa_Cli_Id';$token = 'Soa_token';
      }elseif( $tbl == 'tbl_reclamo_pfn' ){
        $c_cli_id   = 'Rec_Cli_Id';$token = 'Rec_token';
      }elseif( $tbl == 'tbl_itinerario_impulso' ){
        $c_cli_id   = 'Iti_Cli_Id';$token = 'Iti_Id';
      }elseif( $tbl == 'tbl_actualizacion_cliente' ){
        $c_cli_id   = 'Actc_Cli_Id';$token = 'Actc_Id';
      }elseif( $tbl == 'tbl_acciones_competencia' ){
        $c_cli_id   = 'Ac_Cli_Id';$token = 'Ac_token';
      }else{
        $k = 0;
      }
      if( $k == 1 ){
        $this->DB2->select($c_cli_id.','.$token);
        $this->DB2->from($tbl);
        $this->DB2->where($c_cli_id,$id);
        // $query = $this->DB2->get();
        // if ($query->num_rows() > 0){
        //   return $query->row();
        // }else{
        //   return 0;
        // }
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
      }else{
        return array();
      }
    }
    function Update_correccionCliIds($tbl,$data){
      $token = '';$k = 1;
      if( $tbl      == 'tbl_status_exhibidores' ){
        $token = 'Ste_token';
      }elseif( $tbl == 'tbl_marcacion_impulso' ){
       $token  = 'Mar_token';
      }elseif( $tbl == 'tbl_control_inventario' ){
        $token = 'Cti_token';
      }elseif( $tbl == 'tbl_solicitud_apoyo' ){
       $token  = 'Soa_token';
      }elseif( $tbl == 'tbl_reclamo_pfn' ){
        $token = 'Rec_token';
      }elseif( $tbl == 'tbl_itinerario_impulso' ){
        $token = 'Iti_Id';
      }elseif( $tbl == 'tbl_actualizacion_cliente' ){
        $token = 'Actc_Id';
      }elseif( $tbl == 'tbl_acciones_competencia' ){
        $token  = 'Ac_token';
      }else{
        $k = 0;
      }
      if( $k == 1 ){
        return $this->DB2->update_batch($tbl,$data,$token);
      }else{
        return 0;
      }
    }

    function Update_SicroCodClientes_DBB_N($datas){
      return $this->DB2->update_batch('tbl_cliente', $datas,'Cli_Id');
    }

    /*Parche Actualizacion clientes Inactivacion 27/09/2022*/
    function get_Mun_Default($Cli_Id){
        $this->DB2->select('Mun_Id');
        $this->DB2->from('tbl_cliente');
        $this->DB2->join('tbl_municipio','Cli_Mun_Id = Mun_Id');
        $this->DB2->where('Cli_Id', $Cli_Id);
        $this->DB2->order_by("Mun_Id ASC");
        $this->DB2->limit(1);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }
    }
  ###############################

  function ListaClientexRutas($param){
    $this->DB2->select('tbl_cliente.*,CURDATE() Fecha_Sincronizacion,Usu_Ru_Id, Ru_nombre, Di_nombre,Dep_descripcion,Mun_descripcion,Tfc_descripcion,Gir_Id,Gir_descripcion,Tpv_Id,Tpv_descripcion');
    $this->DB2->from('tbl_cliente');
    $this->DB2->join('tbl_municipio', 'Cli_Mun_Id = Mun_Id','left');
    $this->DB2->join('tbl_departamento', 'Mun_Dep_Id = Dep_Id','left');
    $this->DB2->join('tbl_usuario', 'Usu_Ru_Id = Cli_Ru_Id','left');
    $this->DB2->join('tbl_rutas', 'Usu_Ru_Id = Ru_Id','left');
    $this->DB2->join('tbl_distrito', 'Ru_Dist_Id = Dist_Id','left');
    $this->DB2->join('tbl_canal', 'Dist_Ca_Id = Ca_Id','left');
    $this->DB2->join('tbl_distribuidora', 'Ca_Dis_Id = Dis_Id','left');
    $this->DB2->join('tbl_division', 'Dis_Di_Id = Di_Id','left');
    $this->DB2->join('tbl_tipo_facturacion', 'Cli_Tfc_Id = Tfc_Id','left');
    $this->DB2->join('tbl_giro_negocio', 'Cli_Gir_Id = Gir_Id','left');
    $this->DB2->join('tbl_tipo_punto_venta', 'Tpv_Id = Gir_Tpv_Id','left');
    // $this->DB2->where_in('Usu_Ru_Id', $arrgrutas);
    $this->DB2->where('Cli_codigo!=', '0');
    $this->DB2->where('Cli_codigo!=', '0000000');
    $this->DB2->where('Cli_estado_sys', 'P');
    $this->DB2->where('Cli_estado_descarga', 1);

    $this->DB2->where('Cli_codigo', $param['Codigo']);
    $this->DB2->where('Cli_Ru_Id', $param['Ruta']);


    $query = $this->DB2->get();
    $resultado = $query->result();
    if(!empty($resultado)){
        return $resultado;
    }else{
        return array();
    }
  }

  function guardarTempoClientes($datas){
    return $this->DB2->insert_batch('tempoCliente', $datas);
  }

  public function List_ClientesGPS($lat,$long,$distancia){
    $query_select = "SELECT Cli_Id,Cli_codigo,Cli_nombre , Cli_direccion, Cli_latitud, Cli_longitud, ( 6371 * acos(cos(radians(".$lat.")) * cos(radians(Cli_latitud)) * cos(radians(Cli_longitud) - radians(".$long.")) + sin(radians(".$lat.")) * sin(radians(Cli_latitud)))) AS distance FROM tempoCliente HAVING distance < ".$distancia."  ORDER BY distance ";
    $resultado = $this->DB2->query($query_select);
    $resultado = $resultado->result();
    if(!empty($resultado)){
        return $resultado;
    }else{
        return array();
    }
  }

  public function clientes_referencias(){
    $this->DB2->select('Refc_Id codbx,Refc_descripcion valor');
    $this->DB2->from('tbl_referencia');
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