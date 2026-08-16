<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_listado extends CI_Model
{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function total_clientes_aprobados($datosb,$start,$limit,$vacio){ 
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Id_Ruta,ru.Nombre_Ruta,r.Tipo,r.Nit_Fiscal,r.RefCuatro,r.RefCinco,r.RefSeis,r.RefSiete,r.RefOcho,RefNueve,RefDies,r.Ncr,c.Fecha_Ingreso,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,c.Estado,c.Fecha_Resolucion,c.Comentario_E");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        // $where_fechas = "CAST(c.Fecha_Ingreso AS DATETIME) between '".$datosb['fechadesde']."' AND '".$datosb['fechahasta']."'";
        // $this->db->where($where_fechas);

        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{
            $totaldist = 0;
            $where_distribuidora = "";
            if(!empty($datosb['distribuidoras'])){
                $totaldist = count($datosb['distribuidoras']);
                if($totaldist>0){
                    for ($i=0; $i < $totaldist ; $i++) {
                        $this->db->where('ru.Id_Distribuidora !=',$datosb["distribuidoras"][$i]);
                    }
                }else{

                }
            }else{
                $totaldist = 0;
            }          
        }


        $this->db->where('p.Nombre_Pais',$this->session->userdata('pais'));
        $this->db->where('c.Estado', 'A');
        if($datosb['vista_elegida'] == 0){
            $this->db->where('c.Editado', NULL);
        }else{
            $this->db->where('c.Editado!=', NULL);
        }
        $this->db->where('c.Estado_Analista', NULL);
        // if($datosb['cp'] == 1){
        // }else{
            $this->db->where('s.Id_Gruporutas !=', 12);
        // }
        if(!empty($datosb['rutas'])){
            $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        }else{
        }
        $this->db->limit($limit,$start);
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->order_by('c.Id_Cliente', 'ASC');

        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }



    /*------------------------------------------------------------------------------*/
    /*---------------------|CLIENTES QUE FALTAN POR ACTUALIZAR|---------------------*/
    /*_____________________|----------------------------------|_____________________*/
    function lista_clientes_ruta($limit,$start,$usuario){
        $this->db->select('Codigo_Cliente')->from('actu_info_clientes');
        $subconsulta =  $this->db->get_compiled_select();

        $this->db->select('c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Latitud,c.Longitud');
        $this->db->from('clientes as c');
        $this->db->where("c.Codigo NOT IN ($subconsulta)", NULL, FALSE);
        $this->db->where('c.Id_Usuarios', $usuario);
        $this->db->where('c.Estado_Analista ', 'A');
        $this->db->where('c.quienresolucion!=', 'CB');
        $this->db->where('c.estado_w', '1');
        $this->db->order_by('c.Codigo ', 'ASC');
        // $this->db->limit($limit,$start);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function contar_clientes_ruta($usuario){

        $this->db->select('Codigo_Cliente')->from('actu_info_clientes');
        $subconsulta =  $this->db->get_compiled_select();

        $this->db->select('COUNT(*) as totolus');
        $this->db->from('clientes');
        $this->db->where("Codigo NOT IN ($subconsulta)", NULL, FALSE);
        $this->db->where('Id_Usuarios', $usuario);
        $this->db->where('Estado_Analista ', 'A');
        $this->db->where('quienresolucion!=', 'CB');
        $this->db->where('estado_w', '1');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }
    /************************************************************************************/

    /*------------------------------------------------------------------------------*/
    /*---------------------|CLIENTES QUE YA ESTAN ACTUALIZADOS|---------------------*/
    /*_____________________|----------------------------------|_____________________*/
    function lista_clientes_ruta_AC($limit,$start,$usuario){
        $this->db->distinct();
        $this->db->select('Id_Actu_Info_Cli,Codigo_Cliente,Nombre,Direccion,Telefono,Contacto,Latitud,Longitud');
        $this->db->from('actu_info_clientes');
        $this->db->where('Id_Usuarios', $usuario);
        $this->db->group_by('Codigo_Cliente');
        $this->db->order_by('Codigo_Cliente', 'ASC');
        // $this->db->limit($limit,$start);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function contar_clientes_ruta_AC($usuario){
        $this->db->distinct();
        $this->db->select('COUNT(distinct Codigo_Cliente) as totolus');
        $this->db->from('actu_info_clientes');
        $this->db->where('Id_Usuarios', $usuario);

        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }
    /************************************************************************************/

    function total_clientes_ruta($usuario){
        $this->db->select('COUNT(*) as totolus');
        $this->db->from('clientes');
        $this->db->where('Id_Usuarios', $usuario);
        $this->db->where('Estado_Analista ', 'A');
        $this->db->where('quienresolucion!=', 'CB');
        $this->db->where('estado_w', '1');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }

    function obt_ultimo_codigoxruta($codiruta){

        $this->db->select('CONVERT(SUBSTRING_INDEX(c.Codigo,"-",-1),UNSIGNED INTEGER) AS Codigo');
        $this->db->from('clientes as c');
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->where('ru.Id_Ruta', $codiruta);
        $this->db->where('c.Estado_Analista','A');
        $this->db->where('c.quienresolucion!=','CB');
        $this->db->order_by('Codigo', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }
    }

    function obtener_listadorutas($datosb){

        $this->db->select("Nombre_Ruta");
        $this->db->from("rutas");
        $this->db->order_by('Nombre_Ruta', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }
    /*---------------------------------------------------------------------------------------*/
    /*----------------CONSULTA PARA GENERAR PLANTILLA DE CLIENTES APROBADOS------------------*/
    /*---------------------------------------------------------------------------------------*/
    function obtener_listado($datosb){ 

        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Propietario,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Nombre_Ruta,m.Id_Municipio,m.NombreMunicipio,d.Id_Departamento,d.NombreDepartamento,c.Foto_Negocio,g.Nombre_Gnegocio,g.Id_Gironegocio,tpv.Id_Tpuntoventa,tpv.Nombre_TpuntoV,tf.Nombre_Tfacturacion,tf.Id_Tfacturacion,c.Dui,c.Numero_Registro,c.Nit,con.Nombre_Condicionc,con.Id_Condicionc,c.Dia_Cobro,c.Monto_Credito,c.CompraS_B,c.CompraS_D,c.CompraS_Y,c.CompraS_F,c.Exhibiror_Uno,c.Exhibiror_Dos,c.Exhibiror_Tres,c.Cantidad_Exhibidor,c.Foto_Exhibidor,c.Fecha_Ingreso,c.Estado,c.Fecha_Resolucion,s.Id_Gruporutas,c.Editado,c.Comentario_E,c.Fecha_Resolucion_R,c.Fecha_AprobacionA,ru.Canal,c.Ord_VisitaSema,c.TipoCliente,c.quienresolucion,c.UlFechaActuCli,c.UlFechaActuExh,c.TokenCliNuevo,m.Mun_Id,tf.Tfc_Id,con.Cod_Id,g.Gir_Id,c.tipo_us,c.Cantidad_CMR");
        $this->db->from("clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");  
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->join("giro_negocio as g","c.Id_Gironegocio = g.Id_Gironegocio");
        $this->db->join("tipo_punto_venta as tpv","g.Id_Tpuntoventa = tpv.Id_Tpuntoventa");
        $this->db->join("tipo_facturacion as tf","c.Id_Tfacturacion = tf.Id_Tfacturacion");
        $this->db->join("condicion_cliente as con","c.Id_Condicionc = con.Id_Condicionc");
        $this->db->where('p.Nombre_Pais',$this->session->userdata('pais'));
        $this->db->where('c.Estado_Analista', 'A');
        $this->db->where('c.EstadoDescarga', '0');

        // $query_select = "SELECT c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Propietario,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Nombre_Ruta,m.Id_Municipio,m.NombreMunicipio,d.Id_Departamento,d.NombreDepartamento,c.Foto_Negocio,g.Nombre_Gnegocio,tpv.Id_Tpuntoventa,tpv.Nombre_TpuntoV,tf.Nombre_Tfacturacion,tf.Id_Tfacturacion,c.Dui,c.Numero_Registro,c.Nit,con.Nombre_Condicionc,c.Dia_Cobro,c.Monto_Credito,c.CompraS_B,c.CompraS_D,c.CompraS_Y,c.CompraS_F,c.Exhibiror_Uno,c.Exhibiror_Dos,c.Exhibiror_Tres,c.Cantidad_Exhibidor,c.Foto_Exhibidor,c.Fecha_Ingreso,c.Estado,c.Fecha_Resolucion,s.Id_Gruporutas,c.Editado,c.Comentario_E,c.Fecha_Resolucion_R,c.Fecha_AprobacionA,ru.Canal FROM clientes as c
        // INNER JOIN usuarios as u ON c.Id_Usuarios = u.Id_Usuarios
        // rutas as ru ON u.Id_Ruta = ru.Id_Ruta
        // supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor
        // distribuidora as dist ON ru.Id_Distribuidora = dist.Id_Distribuidora
        // municipio as m ON c.Id_Municipio = m.Id_Municipio
        // departamento as d ON m.Id_Departamento = d.Id_Departamento";


        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{}
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('ru.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->order_by('c.Codigo', 'ASC');
        $this->db->order_by('c.Fecha_Resolucion', 'ASC');
        // $this->db->limit($datosb['limit'],$datosb['start']);
        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }






    }

    function contar_clientes_plantilla($datosb){
        $this->db->select('COUNT(*) as totolu');
        $this->db->from('clientes as c');
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais',$this->session->userdata('pais'));
        $this->db->where('c.Estado_Analista', 'A');
        $this->db->where('c.EstadoDescarga', '0');
        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{}
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('ru.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }
    /*---------------------------------------------------------------------------------------*/
    /*---------------------PLANTILLA COMPLETA INFORMACION CLIENTES---------------------------*/
    /*---------------------------------------------------------------------------------------*/
    function obtener_listado_completo($datosb){ 
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Propietario,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Nombre_Ruta,m.Id_Municipio,m.NombreMunicipio,d.Id_Departamento,d.NombreDepartamento,c.Foto_Negocio,g.Nombre_Gnegocio,tpv.Id_Tpuntoventa,tpv.Nombre_TpuntoV,tf.Nombre_Tfacturacion,tf.Id_Tfacturacion,c.Dui,c.Numero_Registro,c.Nit,con.Nombre_Condicionc,c.Dia_Cobro,c.Monto_Credito,c.CompraS_B,c.CompraS_D,c.CompraS_Y,c.CompraS_F,c.Exhibiror_Uno,c.Exhibiror_Dos,c.Exhibiror_Tres,c.Cantidad_Exhibidor,c.Foto_Exhibidor,c.Fecha_Ingreso,c.Estado,c.Fecha_Resolucion,s.Id_Gruporutas,c.estado_w,c.Estado_Analista,c.Fecha_Resolucion_R,c.Fecha_AprobacionA,c.quienresolucion,c.Editado,c.Comentario_E,c.EstadoDescarga,c.Ord_VisitaSema");
        $this->db->from("clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");   
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join("giro_negocio as g","c.Id_Gironegocio = g.Id_Gironegocio");
        $this->db->join("tipo_punto_venta as tpv","g.Id_Tpuntoventa = tpv.Id_Tpuntoventa");
        $this->db->join("tipo_facturacion as tf","c.Id_Tfacturacion = tf.Id_Tfacturacion");
        $this->db->join("condicion_cliente as con","c.Id_Condicionc = con.Id_Condicionc");

        $where_fechas = "CAST(c.Fecha_Ingreso AS DATETIME) between '".$datosb['fechadesde']."' AND '".$datosb['fechahasta']."'";
        //$where_fechas = "CAST(c.Fecha_Ingreso AS DATE) between '2019-08-13' AND '2019-08-13'";
        $this->db->where($where_fechas);
        $this->db->where('c.quienresolucion !=', 'CB');
        $this->db->where('c.quienresolucion !=', 'SDV');
        $totaldist = 0;
        $where_distribuidora = "";
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('ru.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        if($datosb['cp'] == 1){
        }else{
            // $this->db->where('s.Id_Gruporutas !=', 12);
        }
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->order_by('c.Codigo', 'ASC');
        // $this->db->limit($datosb['limit'],$datosb['start']);
        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
                return $resultado;
        }else{
                return array();
        }
    }

    function contar_clientes_platcompleta($datosb){
        $this->db->select('COUNT(*) as totolu');
        $this->db->from("clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join("giro_negocio as g","c.Id_Gironegocio = g.Id_Gironegocio");
        $this->db->join("tipo_punto_venta as tpv","g.Id_Tpuntoventa = tpv.Id_Tpuntoventa");
        $this->db->join("tipo_facturacion as tf","c.Id_Tfacturacion = tf.Id_Tfacturacion");
        $this->db->join("condicion_cliente as con","c.Id_Condicionc = con.Id_Condicionc");

        $where_fechas = "CAST(c.Fecha_Ingreso AS DATETIME) between '".$datosb['fechadesde']."' AND '".$datosb['fechahasta']."'";
        //$where_fechas = "CAST(c.Fecha_Ingreso AS DATE) between '2019-08-13' AND '2019-08-13'";
        
        $this->db->where($where_fechas);
        $totaldist = 0;
        $where_distribuidora = "";
        if(!empty($datosb['distribuidoras'])){
            $totaldist = count($datosb['distribuidoras']);
            if($totaldist>0){
                for ($i=0; $i < $totaldist ; $i++) {
                    // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                    $this->db->where('ru.Id_Distribuidora !=',$datosb["distribuidoras"][$i]);
                    // $this->db->like('ru.Id_Distribuidora', $datosb["distribuidoras"][$i], 'both');
                }
            }else{

            }
        }else{
            $totaldist = 0;
        }
        if($datosb['cp'] == 1){
        }else{
            $this->db->where('s.Id_Gruporutas !=', 12);
        }

        $this->db->where('c.Estado !=', 'W');
      
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        // $this->db->limit($datosb['limit'],$datosb['start']);
        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
                return $resultado;
        }else{
                return array();
        }
    }
    /*
        contar_clientes_actualizados
    */
    function contar_clientes_platcompleta_actu($datosb){
        $this->db->select('COUNT(*) as totolu');
        $this->db->from("actu_info_clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");
        $where_fechas = "CAST(c.Fecha_Actualizacion AS DATETIME) between '".$datosb['fechadesde']."' AND '".$datosb['fechahasta']."'";
        //$where_fechas = "CAST(c.Fecha_Ingreso AS DATE) between '2019-08-13' AND '2019-08-13'";
        
        $this->db->where($where_fechas);
        $totaldist = 0;
        $where_distribuidora = "";
        if(!empty($datosb['distribuidoras'])){
            $totaldist = count($datosb['distribuidoras']);
            if($totaldist>0){
                for ($i=0; $i < $totaldist ; $i++) {
                    // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                    $this->db->where('ru.Id_Distribuidora !=',$datosb["distribuidoras"][$i]);
                    // $this->db->like('ru.Id_Distribuidora', $datosb["distribuidoras"][$i], 'both');
                }
            }else{

            }
        }else{
            $totaldist = 0;
        }
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        // $this->db->limit($datosb['limit'],$datosb['start']);
        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
                return $resultado;
        }else{
                return array();
        }
    }

    /*LISTADO CLIENTE ACTUALIZADOS POR FECHA*/
    function lista_clientes_actualizados($datosb){
        $this->db->select('ru.Nombre_Ruta,c.Id_Actu_Info_Cli,c.Nombre,c.Direccion,c.Contacto,c.Telefono,c.Latitud,c.Longitud,c.Fecha_Actualizacion,c.Codigo_Cliente');
        $this->db->from("actu_info_clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");
        $where_fechas = "CAST(c.Fecha_Actualizacion AS DATETIME) between '".$datosb['fechadesde']."' AND '".$datosb['fechahasta']."'";
        $this->db->where($where_fechas);
        $totaldist = 0;
        $where_distribuidora = "";
        if(!empty($datosb['distribuidoras'])){
            $totaldist = count($datosb['distribuidoras']);
            if($totaldist>0){
                for ($i=0; $i < $totaldist ; $i++) {
                    // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                    $this->db->where('ru.Id_Distribuidora !=',$datosb["distribuidoras"][$i]);
                    // $this->db->like('ru.Id_Distribuidora', $datosb["distribuidoras"][$i], 'both');
                }
            }else{

            }
        }else{
            $totaldist = 0;
        }
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->limit($datosb['limit'],$datosb['start']);
        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
                return $resultado;
        }else{
                return array();
        }
    }

    /*LISTADO CLIENTE ACTUALIZADOS POR FECHA SIN PAGINADO*/
    function lista_clientes_actualizados_v2($datosb){
        $this->db->select('ru.Nombre_Ruta,a.Id_Actu_Info_Cli,a.Nombre,a.Direccion,a.Contacto,a.Telefono,a.Latitud,a.Longitud,a.Fecha_Actualizacion,a.Codigo_Cliente,c.Orden_Visita,c.Dias,c.RefUno');
        $this->db->from("actu_info_clientes as a");
        $this->db->join("clientes as c","a.Id_Usuarios = c.Id_Usuarios");

        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");  

        $where_fechas = "CAST(a.Fecha_Actualizacion AS DATETIME) between '".$datosb['fechadesde']."' AND '".$datosb['fechahasta']."'";
        $totaldist = 0;
        $where_distribuidora = "";
        if(!empty($datosb['distribuidoras'])){
            $totaldist = count($datosb['distribuidoras']);
            if($totaldist>0){
                for ($i=0; $i < $totaldist ; $i++) {
                    // $where .= "ru.Id_Distribuidora = ".$datosb['distribuidoras'][$i];
                    $this->db->where('ru.Id_Distribuidora !=',$datosb["distribuidoras"][$i]);
                    // $this->db->like('ru.Id_Distribuidora', $datosb["distribuidoras"][$i], 'both');
                }
            }else{

            }
        }else{
            $totaldist = 0;
        }
        $this->db->where($where_fechas);
        $where_codigos = "c.Codigo = a.Codigo_Cliente";
        $this->db->where($where_codigos);
        // $this->db->where('c.Codigo','a.Codigo_Cliente');
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->order_by('a.Codigo_Cliente', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
                return $resultado;
        }else{
                return array();
        }
    }

    function cliente_x_codigo($codigo_x){
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Propietario,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Nombre_Ruta,m.Id_Municipio,m.NombreMunicipio,d.Id_Departamento,d.NombreDepartamento,c.Foto_Negocio,g.Nombre_Gnegocio,tpv.Id_Tpuntoventa,tpv.Nombre_TpuntoV,tf.Nombre_Tfacturacion,tf.Id_Tfacturacion,c.Dui,c.Numero_Registro,c.Nit,con.Nombre_Condicionc,c.Dia_Cobro,c.Monto_Credito,c.CompraS_B,c.CompraS_D,c.CompraS_Y,c.CompraS_F,c.Exhibiror_Uno,c.Exhibiror_Dos,c.Exhibiror_Tres,c.Cantidad_Exhibidor,c.Foto_Exhibidor,c.Fecha_Ingreso,c.Estado,c.Fecha_Resolucion,s.Id_Gruporutas");
        $this->db->from("clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");  
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->join("giro_negocio as g","c.Id_Gironegocio = g.Id_Gironegocio");
        $this->db->join("tipo_punto_venta as tpv","g.Id_Tpuntoventa = tpv.Id_Tpuntoventa");
        $this->db->join("tipo_facturacion as tf","c.Id_Tfacturacion = tf.Id_Tfacturacion");
        $this->db->join("condicion_cliente as con","c.Id_Condicionc = con.Id_Condicionc");
        $this->db->where('c.Codigo',$codigo_x);
        $query = $this->db->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }


    /*-------------------------------------------------------------------------------*/
    /*-----------TABLA CONSULTA CLIENTES APROBADOS EN VISTA ANALISTAS----------------*/
    /*-------------------------------------------------------------------------------*/
    function obtener_listado_tabla($datosb,$start,$limit,$vacio){ 
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Id_Ruta,ru.Nombre_Ruta,r.Tipo,r.Nit_Fiscal,r.RefCuatro,r.RefCinco,r.RefSeis,r.RefSiete,r.RefOcho,RefNueve,RefDies,r.Ncr,c.Fecha_Ingreso,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,c.Estado,c.Fecha_Resolucion,c.Comentario_E,c.Id_Usuarios,c.Ord_VisitaSema");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais',$this->session->userdata('pais'));
        $this->db->where('c.Estado', 'A');
        $this->db->where('c.EstadoDescarga', '0');
        $this->db->where('c.Estado_Analista IS NULL', null, false);
        if($datosb['vista_elegida'] == 0){
            $this->db->where('c.Editado', NULL);
        }else{
            $this->db->where('c.Editado!=', NULL);
        }
        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{        
        }
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('ru.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        $this->db->limit($limit,$start);
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->order_by('c.Id_Cliente', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }


    function obt_list_cli_supervisor($datosb,$start,$limit){
        $this->db->select("c.Id_Cliente,c.Codigo,c.Editado,c.Comentario_E,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Id_Ruta,ru.Nombre_Ruta,r.Tipo,r.Nit_Fiscal,r.RefCuatro,r.RefCinco,r.RefSeis,r.RefSiete,r.RefOcho,RefNueve,RefDies,r.Ncr,c.Fecha_Ingreso,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,c.Estado,c.Ord_VisitaSema");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        // $where_fechas = "CAST(c.Fecha_Ingreso AS DATE) between '".$datosb['fechadesde']."' AND '".$datosb['fechahasta']."'";
        //$where_fechas = "CAST(c.Fecha_Ingreso AS DATE) between '2019-08-13' AND '2019-08-13'";
        $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where('c.Estado !=', 'A');
        $this->db->where('c.Estado !=', 'R');
        $this->db->where('c.Estado !=', 'W');
        $this->db->where('c.Estado !=', 'P');

        $this->db->limit($limit,$start);
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->order_by('c.Id_Cliente', 'ASC');
        // $this->db->order_by('c.Nombre', 'ASC');
        // $this->db->order_by('c.Contacto', 'ASC');
        // $this->db->order_by('c.Telefono', 'ASC');
        
        // $this->db->limit($datosb['limit'],$datosb['start']);
        if(!empty($datosb['rutas'])){
            $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        }else{

        }
        $query = $this->db->get();
        $resultado = $query->result();

          if(!empty($resultado))
         {
                return $resultado;
         }else{
                return array();
         }
    }


    function obt_cliente_supervisor($codigocli){
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Nombre_Ruta,m.Id_Municipio,m.NombreMunicipio,d.Id_Departamento,d.NombreDepartamento,c.Foto_Negocio,g.Nombre_Gnegocio,tpv.Id_Tpuntoventa,tpv.Nombre_TpuntoV,tf.Nombre_Tfacturacion,tf.Id_Tfacturacion,c.Dui,c.Numero_Registro,c.Nit,con.Nombre_Condicionc,c.Dia_Cobro,c.Monto_Credito,c.CompraS_B,c.CompraS_D,c.CompraS_Y,c.CompraS_F,c.Exhibiror_Uno,c.Exhibiror_Dos,c.Exhibiror_Tres,c.Cantidad_Exhibidor,c.Foto_Exhibidor,c.Editado,c.Comentario_E,c.Ord_VisitaSema");
        $this->db->from("clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join("giro_negocio as g","c.Id_Gironegocio = g.Id_Gironegocio");
        $this->db->join("tipo_punto_venta as tpv","g.Id_Tpuntoventa = tpv.Id_Tpuntoventa");
        $this->db->join("tipo_facturacion as tf","c.Id_Tfacturacion = tf.Id_Tfacturacion");
        $this->db->join("condicion_cliente as con","c.Id_Condicionc = con.Id_Condicionc");
        $this->db->where('c.Id_Cliente', $codigocli);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    /*-----------------------------------------------------------------------*/
    /*------------------ENCONTRAR CLIENTES DUPLICADOS------------------------*/
    /*-----------------------------------------------------------------------*/
    function fetch_rutas($idsupervisor){
        $this->db->where('Id_Supervisor', $idsupervisor);
        $query = $this->db->get('rutas');
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    function lista_duplicados_cli($datosb,$start,$limit,$opt){

        $parambusqueda = '';
        if($datosb['opcionbusqueda'] == 1){
            $parambusqueda = 'Contacto';
        }elseif($datosb['opcionbusqueda'] == 2){
            $parambusqueda = 'Telefono';
        }elseif($datosb['opcionbusqueda'] == 3){
            $parambusqueda = 'Nombre';
        }else{
            $parambusqueda = 'Contacto';
        }

        $this->db->select('cli.'.$parambusqueda.'')->from('clientes as cli');
        $this->db->join("usuarios as us","cli.Id_Usuarios = us.Id_Usuarios");
        $this->db->join("rutas as ru","us.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as su","ru.Id_Supervisor = su.Id_Supervisor");
        if($opt == 1){
        }else{
            $this->db->where('su.Id_Supervisor', $datosb['idsupervisor']);
        }
        if(!empty($datosb['rutas'])){
            $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        }else{}
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('cli.Contacto!=', 'NA');
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('cli.Telefono!=', 'NA');
            $this->db->where('cli.Telefono!=', '0000-0000');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('cli.Nombre!=', 'NA');
        }else{
            /*CONTACTO*/
            $this->db->where('cli.Contacto!=', 'NA');
        }
        $this->db->group_by("cli.".$parambusqueda."");
        $this->db->having('count( cli.'.$parambusqueda.' ) >1');
        $subconsulta =  $this->db->get_compiled_select();
        $this->db->select("rut.Id_Ruta,c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,rut.Nombre_Ruta,r.Tipo,r.Nit_Fiscal,r.RefCuatro,r.RefCinco,r.RefSeis,r.RefSiete,r.RefOcho,RefNueve,RefDies,r.Ncr,c.Fecha_Ingreso,m.NombreMunicipio,d.NombreDepartamento,rut.Id_Supervisor,c.Estado,c.estado_w");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as rut","u.Id_Ruta = rut.Id_Ruta");
        $this->db->join("supervisores as s","rut.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where("c.".$parambusqueda." IN ($subconsulta)", NULL, FALSE);
        if($opt == 1){
        }else{
            $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        }
        if(!empty($datosb['rutas'])){
            $this->db->where("rut.Id_Ruta", $datosb['rutas']);
        }else{}
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('c.Contacto!=', 'NA');
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('c.Telefono!=', 'NA');
            $this->db->where('c.Telefono!=', '0000-0000');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('c.Nombre!=', 'NA');
        }else{
            /*CONTACTO*/
            $this->db->where('c.Contacto!=', 'NA');
        }
        $this->db->where("c.Estado", "N");

        $this->db->order_by('rut.Nombre_Ruta', 'ASC');
        $this->db->limit($limit,$start);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }


    function zoom_lista_duplicados_cli($datosb,$start,$limit,$valorbusq){

        $parambusqueda = '';
        if($datosb['opcionbusqueda'] == 1){
            $parambusqueda = 'Contacto';
        }elseif($datosb['opcionbusqueda'] == 2){
            $parambusqueda = 'Telefono';
        }elseif($datosb['opcionbusqueda'] == 3){
            $parambusqueda = 'Nombre';
        }else{
            $parambusqueda = 'Contacto';
        }

        $this->db->select('cli.'.$parambusqueda.'')->from('clientes as cli');
        $this->db->join("usuarios as us","cli.Id_Usuarios = us.Id_Usuarios");
        $this->db->join("rutas as ru","us.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as su","ru.Id_Supervisor = su.Id_Supervisor");
        $this->db->where('su.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where("ru.Id_Ruta", $datosb['rutas']);

        /*000000000000000000000000000000000000000000000000000000000*/
        /*-------------VERIFICACION DE TIPO DE BUSQUEDA------------*/
        /*000000000000000000000000000000000000000000000000000000000*/
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('cli.Telefono', $valorbusq);
            $this->db->where('cli.Telefono!=', 'NA');
            $this->db->where('cli.Telefono!=', '0000-0000');
            $this->db->where('cli.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('cli.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }
        $this->db->group_by("cli.".$parambusqueda."");
        $this->db->having('count( cli.'.$parambusqueda.' ) >1');
        $subconsulta =  $this->db->get_compiled_select();
        /*00000000000000000000000000000000000000000000000000000000000000*/
        /*--------------------FIN DE LA SUBCONSULTA---------------------*/
        /*00000000000000000000000000000000000000000000000000000000000000*/
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,rut.Id_Ruta,rut.Nombre_Ruta,r.Tipo,r.Nit_Fiscal,r.RefCuatro,r.RefCinco,r.RefSeis,r.RefSiete,r.RefOcho,RefNueve,RefDies,r.Ncr,c.Fecha_Ingreso,m.NombreMunicipio,d.NombreDepartamento,rut.Id_Supervisor,c.Estado,c.estado_w,c.Estado_Analista");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as rut","u.Id_Ruta = rut.Id_Ruta");
        $this->db->join("supervisores as s","rut.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where("c.".$parambusqueda." IN ($subconsulta)", NULL, FALSE);
        $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where("rut.Id_Ruta", $datosb['rutas']);
        /*000000000000000000000000000000000000000000000000000000000*/
        /*-------------VERIFICACION DE TIPO DE BUSQUEDA------------*/
        /*000000000000000000000000000000000000000000000000000000000*/
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('c.Telefono', $valorbusq);
            $this->db->where('c.Telefono!=', 'NA');
            $this->db->where('c.Telefono!=', '0000-0000');
            $this->db->where('c.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('c.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }
        $this->db->order_by('c.Estado', 'ASC');
        $this->db->limit($limit,$start);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }


    function zoom_contar_cli($datosb,$valorbusq){

        $parambusqueda = '';
        if($datosb['opcionbusqueda'] == 1){
            $parambusqueda = 'Contacto';
        }elseif($datosb['opcionbusqueda'] == 2){
            $parambusqueda = 'Telefono';
        }elseif($datosb['opcionbusqueda'] == 3){
            $parambusqueda = 'Nombre';
        }else{
            $parambusqueda = 'Contacto';
        }

        $this->db->select('cli.'.$parambusqueda.'')->from('clientes as cli');
        $this->db->join("usuarios as us","cli.Id_Usuarios = us.Id_Usuarios");
        $this->db->join("rutas as ru","us.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as su","ru.Id_Supervisor = su.Id_Supervisor");
        $this->db->where('su.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where("ru.Id_Ruta", $datosb['rutas']);

        /*000000000000000000000000000000000000000000000000000000000*/
        /*-------------VERIFICACION DE TIPO DE BUSQUEDA------------*/
        /*000000000000000000000000000000000000000000000000000000000*/
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('cli.Telefono', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('cli.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }
        $this->db->group_by("cli.".$parambusqueda."");
        $this->db->having('count( cli.'.$parambusqueda.' ) >1');
        $subconsulta =  $this->db->get_compiled_select();
        /*00000000000000000000000000000000000000000000000000000000000000*/
        /*--------------------FIN DE LA SUBCONSULTA---------------------*/
        /*00000000000000000000000000000000000000000000000000000000000000*/
        $this->db->select("COUNT(*) as totrepnew");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as rut","u.Id_Ruta = rut.Id_Ruta");
        $this->db->join("supervisores as s","rut.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where("c.".$parambusqueda." IN ($subconsulta)", NULL, FALSE);
        $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where("rut.Id_Ruta", $datosb['rutas']);
        /*000000000000000000000000000000000000000000000000000000000*/
        /*-------------VERIFICACION DE TIPO DE BUSQUEDA------------*/
        /*000000000000000000000000000000000000000000000000000000000*/
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('c.Telefono', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('c.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }


    function contar_clientes_duplicados($datosb,$opt){

        $parambusqueda = '';
        if($datosb['opcionbusqueda'] == 1){
            $parambusqueda = 'Contacto';
        }elseif($datosb['opcionbusqueda'] == 2){
            $parambusqueda = 'Telefono';
        }elseif($datosb['opcionbusqueda'] == 3){
            $parambusqueda = 'Nombre';
        }else{
            $parambusqueda = 'Contacto';
        }

        $this->db->select('cli.'.$parambusqueda.'')->from('clientes as cli');
        $this->db->join("usuarios as us","cli.Id_Usuarios = us.Id_Usuarios");
        $this->db->join("rutas as ru","us.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as su","ru.Id_Supervisor = su.Id_Supervisor");
        if($opt == 1){
        }else{
            $this->db->where('su.Id_Supervisor', $datosb['idsupervisor']);
        }
        if(!empty($datosb['rutas'])){
            $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        }else{}


        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('cli.Contacto!=', 'NA');
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('cli.Telefono!=', 'NA');
            $this->db->where('cli.Telefono!=', '0000-0000');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('cli.Nombre!=', 'NA');
        }else{
            /*CONTACTO*/
            $this->db->where('cli.Contacto!=', 'NA');
        }

        $this->db->group_by("cli.".$parambusqueda."");
        $this->db->having('count( cli.'.$parambusqueda.' ) >1');
        $subconsulta =  $this->db->get_compiled_select();
        $this->db->select("COUNT(*) as totolu");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as rut","u.Id_Ruta = rut.Id_Ruta");
        $this->db->join("supervisores as s","rut.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where("c.".$parambusqueda." IN ($subconsulta)", NULL, FALSE);
        if($opt == 1){
        }else{
            $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        }
        if(!empty($datosb['rutas'])){
            $this->db->where("rut.Id_Ruta", $datosb['rutas']);
        }else{}
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('c.Contacto!=', 'NA');
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('c.Telefono!=', 'NA');
            $this->db->where('c.Telefono!=', '0000-0000');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('c.Nombre!=', 'NA');
        }else{
            /*CONTACTO*/
            $this->db->where('c.Contacto!=', 'NA');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }
    /*ENCONTRAR CLIENTES NUEVOS REPETIDOS*/
    function contar_cli_nuevos_repetidos($datosb,$valorbusq){

        $parambusqueda = '';
        if($datosb['opcionbusqueda'] == 1){
            $parambusqueda = 'Contacto';
        }elseif($datosb['opcionbusqueda'] == 2){
            $parambusqueda = 'Telefono';
        }elseif($datosb['opcionbusqueda'] == 3){
            $parambusqueda = 'Nombre';
        }else{
            $parambusqueda = 'Contacto';
        }

        $this->db->select('cli.'.$parambusqueda.'')->from('clientes as cli');
        $this->db->join("usuarios as us","cli.Id_Usuarios = us.Id_Usuarios");
        $this->db->join("rutas as ru","us.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as su","ru.Id_Supervisor = su.Id_Supervisor");
        $this->db->where('su.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('cli.Telefono', $valorbusq);
            $this->db->where('cli.Telefono!=', 'NA');
            $this->db->where('cli.Telefono!=', '0000-0000');
            $this->db->where('cli.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('cli.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }
        $this->db->group_by("cli.".$parambusqueda."");
        $this->db->having('count( cli.'.$parambusqueda.' ) >1');
        $subconsulta =  $this->db->get_compiled_select();
        $this->db->select("COUNT(*) as totrepnew");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as rut","u.Id_Ruta = rut.Id_Ruta");
        $this->db->join("supervisores as s","rut.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where("c.".$parambusqueda." IN ($subconsulta)", NULL, FALSE);
        $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where("rut.Id_Ruta", $datosb['rutas']);
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('c.Telefono', $valorbusq);
            $this->db->where('c.Telefono!=', 'NA');
            $this->db->where('c.Telefono!=', '0000-0000');
            $this->db->where('c.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('c.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }
        // $this->db->where("c.Estado", "N");
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }
    /*-------------------------------------------------------------------------------*/
    /*------------TABLA DE CONSULTA CLIENTES APROBADOS USUARIO ANALISTAS-------------*/
    /*-------------------------------------------------------------------------------*/


    function contar_cli_new_repetidos_a($datosb,$valorbusq){

        $parambusqueda = '';
        if($datosb['opcionbusqueda'] == 1){
            $parambusqueda = 'Contacto';
        }elseif($datosb['opcionbusqueda'] == 2){
            $parambusqueda = 'Telefono';
        }elseif($datosb['opcionbusqueda'] == 3){
            $parambusqueda = 'Nombre';
        }else{
            $parambusqueda = 'Contacto';
        }

        $this->db->select('cli.'.$parambusqueda.'')->from('clientes as cli');
        $this->db->join("usuarios as us","cli.Id_Usuarios = us.Id_Usuarios");
        $this->db->join("rutas as ru","us.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as su","ru.Id_Supervisor = su.Id_Supervisor");
        $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        // $this->db->where('c.Estado', 'A');
        // $this->db->where('c.Editado', NULL);
        // $this->db->where('su.Id_Gruporutas !=', 12);
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('cli.Telefono', $valorbusq);
            $this->db->where('cli.Telefono!=', 'NA');
            $this->db->where('cli.Telefono!=', '0000-0000');
            $this->db->where('cli.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('cli.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }
        $this->db->group_by("cli.".$parambusqueda."");
        $this->db->having('count( cli.'.$parambusqueda.' ) >1');
        $subconsulta =  $this->db->get_compiled_select();
        $this->db->select("COUNT(*) as totrepnew");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as rut","u.Id_Ruta = rut.Id_Ruta");
        $this->db->join("supervisores as s","rut.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where("c.".$parambusqueda." IN ($subconsulta)", NULL, FALSE);
        $this->db->where("rut.Id_Ruta", $datosb['rutas']);
        // $this->db->where('c.Estado', 'A');
        // $this->db->where('c.Editado', NULL);
        // $this->db->where('s.Id_Gruporutas !=', 12);
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('c.Telefono', $valorbusq);
            $this->db->where('c.Telefono!=', 'NA');
            $this->db->where('c.Telefono!=', '0000-0000');
            $this->db->where('c.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('c.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }


    function zoom_list_duplicados_cli_a($datosb,$start,$limit,$valorbusq){

        $parambusqueda = '';
        if($datosb['opcionbusqueda'] == 1){
            $parambusqueda = 'Contacto';
        }elseif($datosb['opcionbusqueda'] == 2){
            $parambusqueda = 'Telefono';
        }elseif($datosb['opcionbusqueda'] == 3){
            $parambusqueda = 'Nombre';
        }else{
            $parambusqueda = 'Contacto';
        }

        $this->db->select('cli.'.$parambusqueda.'')->from('clientes as cli');
        $this->db->join("usuarios as us","cli.Id_Usuarios = us.Id_Usuarios");
        $this->db->join("rutas as ru","us.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as su","ru.Id_Supervisor = su.Id_Supervisor");
        $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        // $this->db->where('c.Estado', 'A');
        // $this->db->where('c.Editado', NULL);
        // $this->db->where('su.Id_Gruporutas !=', 12);
        /*000000000000000000000000000000000000000000000000000000000*/
        /*-------------VERIFICACION DE TIPO DE BUSQUEDA------------*/
        /*000000000000000000000000000000000000000000000000000000000*/
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('cli.Telefono', $valorbusq);
            $this->db->where('cli.Telefono!=', 'NA');
            $this->db->where('cli.Telefono!=', '0000-0000');
            $this->db->where('cli.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('cli.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('cli.Contacto', $valorbusq);
        }
        $this->db->group_by("cli.".$parambusqueda."");
        $this->db->having('count( cli.'.$parambusqueda.' ) >1');
        $subconsulta =  $this->db->get_compiled_select();
        /*00000000000000000000000000000000000000000000000000000000000000*/
        /*--------------------FIN DE LA SUBCONSULTA---------------------*/
        /*00000000000000000000000000000000000000000000000000000000000000*/
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,rut.Id_Ruta,rut.Nombre_Ruta,r.Tipo,r.Nit_Fiscal,r.RefCuatro,r.RefCinco,r.RefSeis,r.RefSiete,r.RefOcho,RefNueve,RefDies,r.Ncr,c.Fecha_Ingreso,c.Fecha_Resolucion,m.NombreMunicipio,d.NombreDepartamento,rut.Id_Supervisor,c.Estado,c.estado_w,c.Estado_Analista,c.Id_Usuarios,c.Ord_VisitaSema");
        $this->db->from("clientes as c");
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as rut","u.Id_Ruta = rut.Id_Ruta");
        $this->db->join("supervisores as s","rut.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where("c.".$parambusqueda." IN ($subconsulta)", NULL, FALSE);
        $this->db->where("rut.Id_Ruta", $datosb['rutas']);
        // $this->db->where('c.Estado', 'A');
        // $this->db->where('c.Editado', NULL);
        // $this->db->where('s.Id_Gruporutas !=', 12);
        /*000000000000000000000000000000000000000000000000000000000*/
        /*-------------VERIFICACION DE TIPO DE BUSQUEDA------------*/
        /*000000000000000000000000000000000000000000000000000000000*/
        if($datosb['opcionbusqueda'] == 1){
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }elseif($datosb['opcionbusqueda'] == 2){
            /*TELEFONO*/
            $this->db->where('c.Telefono', $valorbusq);
            $this->db->where('c.Telefono!=', 'NA');
            $this->db->where('c.Telefono!=', '0000-0000');
            $this->db->where('c.Telefono!=', '0');
        }elseif($datosb['opcionbusqueda'] == 3){
            /*NOMBRE*/
            $this->db->where('c.Nombre', $valorbusq);
        }else{
            /*CONTACTO*/
            $this->db->where('c.Contacto', $valorbusq);
        }
        $this->db->order_by('c.Fecha_Ingreso', 'DESC');
        $this->db->limit($limit,$start);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }



    function contar_clientes($datosb,$vacio){
        $this->db->select('COUNT(*) as totolu');
        $this->db->from('clientes as c');
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais',$this->session->userdata('pais'));
        $this->db->where('c.Estado', 'A');
        $this->db->where('c.EstadoDescarga', '0');
        $this->db->where('c.Estado_Analista IS NULL', null, false);
        if($datosb['vista_elegida'] == 0){
            $this->db->where('c.Editado', NULL);
        }elseif ($datosb['vista_elegida'] == 1){
            $this->db->where('c.Editado!=', NULL);
        }else{}

        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{}
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('dist.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }

    function contar_clientesAC($datosb){
        $this->db->select("Id_Cliente,Id_Municipio,MAX(FechaASupervisor) as fecha_max")->from("actualizacion_clientes");
        $this->db->group_by("Id_Cliente");
        $subconsulta = $this->db->get_compiled_select();
        $this->db->select('COUNT(*) as totolu');
        $this->db->from('actualizacion_clientes as ac');
        $this->db->join("usuarios as u","ac.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("($subconsulta) as b","ac.Id_Cliente = b.Id_Cliente AND ac.FechaASupervisor = fecha_max");
        $this->db->where("ac.EstadoASupervisor", "A");
        $this->db->where("ac.EstadoAAnalista", "A");
        $this->db->where("ac.EstadoDescarga", 0);

        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{}
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('ru.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }

    function contar_clientes_supervisor($datosb){
        $this->db->select('COUNT(*) as totolu');
        $this->db->from('clientes as c');
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->where('s.Id_Supervisor', $datosb['idsupervisor']);
        $this->db->where('c.Estado !=', 'A');
        $this->db->where('c.Estado !=', 'R');
        $this->db->where('c.Estado !=', 'W');
        $this->db->where('c.Estado !=', 'P');
        if(!empty($datosb['rutas'])){
            $this->db->where("ru.Id_Ruta", $datosb['rutas']);
        }else{

        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }

    function list_distribuidora($pais){
        $this->db->select('d.Id_Distribuidora,d.Nombre_Distribuidora,p.Nombre_Pais');
        $this->db->from('distribuidora as d');
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais', $pais);
        $this->db->order_by('d.Nombre_Distribuidora', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }


    function list_rutas($pais,$distribuidoras){
        $this->db->select('r.Id_Ruta,r.Nombre_Ruta');
        $this->db->from('rutas as r');
        $this->db->join('distribuidora as d','r.Id_Distribuidora = d.Id_Distribuidora');
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais', $pais);
        $this->db->where('r.Nombre_Ruta!=', 'SUPERVISOR');
        $this->db->where('r.Nombre_Ruta!=', 'NO ASIGNADO');
        $this->db->where('r.Nombre_Ruta!=', 'SAN MIGUEL');
        $this->db->where('r.Nombre_Ruta!=', 'SAN SALVADOR');
        $this->db->where('r.Nombre_Ruta!=', 'admin');
        $this->db->where('r.Nombre_Ruta!=', 'SANTA ANA');
        $this->db->where('r.Nombre_Ruta!=', 'SONSONATE');
        $this->db->where('r.Nombre_Ruta!=', 'adminsa');
        $this->db->where('r.Nombre_Ruta!=', 'VENDEDOR JUNIOR');
        $this->db->where('r.Nombre_Ruta!=', 'adminsm');
        $totaldist = 0;
        $where_distribuidora = "";
        if(!empty($distribuidoras)){
            $totaldist = count($distribuidoras);
            if($totaldist>0){
                for ($i=0; $i < $totaldist ; $i++) {
                    $this->db->where('r.Id_Distribuidora !=',$distribuidoras[$i]);
                }
            }else{

            }
        }else{
            $totaldist = 0;
        }
        $this->db->order_by('r.Nombre_Ruta', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function list_rutas_por_distri($pais,$distribuidoras){
        $this->db->select('r.Id_Ruta,r.Nombre_Ruta');
        $this->db->from('rutas as r');
        $this->db->join('distribuidora as d','r.Id_Distribuidora = d.Id_Distribuidora');
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais', $pais);
        $this->db->where('r.Nombre_Ruta!=', 'SUPERVISOR');
        $this->db->where('r.Nombre_Ruta!=', 'NO ASIGNADO');
        $this->db->where('r.Nombre_Ruta!=', 'SAN MIGUEL');
        $this->db->where('r.Nombre_Ruta!=', 'SAN SALVADOR');
        $this->db->where('r.Nombre_Ruta!=', 'admin');
        $this->db->where('r.Nombre_Ruta!=', 'SANTA ANA');
        $this->db->where('r.Nombre_Ruta!=', 'SONSONATE');
        $this->db->where('r.Nombre_Ruta!=', 'adminsa');
        $this->db->where('r.Nombre_Ruta!=', 'VENDEDOR JUNIOR');
        $this->db->where('r.Nombre_Ruta!=', 'adminsm');
        
        $totaldist = 0;
        $where_distribuidora = "";
        if(!empty($distribuidoras)){
            $totaldist = count($distribuidoras);
            if($totaldist>0){
                for ($i=0; $i < $totaldist ; $i++) {
                    $this->db->where('r.Id_Distribuidora !=',$distribuidoras[$i]);
                }
            }else{

            }
        }else{
            $totaldist = 0;
        }
        $this->db->order_by('r.Nombre_Ruta', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    /*----------------------------------------------------------------------*/
    /*-------------------BITACORA DE EXPORTADOS (PROCESADOS)----------------*/
    /*----------------------------------------------------------------------*/
    function guardar_bitacora($datas){
        $this->db->insert('bitacora_procesados', $datas);
        if($this->db->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    
    function list_bitacora_procesados($pais,$start,$limit){
        $this->db->select('b.Id_Bitacora_P,b.Fecha_Inicio,b.Fecha_Final,b.Distribuidoras_B,b.Id_Usuarios,b.Fecha_Descarga,b.Nombre_Archivo,u.Nombre_Completo,b.TipoDescarga');
        $this->db->from('bitacora_procesados as b');
        $this->db->join('usuarios as u','b.Id_Usuarios = u.Id_Usuarios');
        $this->db->join('rutas as r','u.Id_Ruta = r.Id_Ruta');
        $this->db->join('distribuidora as d','r.Id_Distribuidora = d.Id_Distribuidora');
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais', $pais);
        $this->db->order_by('b.Fecha_Descarga', 'DESC');
        $this->db->limit($limit,$start);
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function contar_bitacora($pais){
        $this->db->select('COUNT(*) as totolus');
        $this->db->from('bitacora_procesados as b');
        $this->db->join('usuarios as u','b.Id_Usuarios = u.Id_Usuarios');
        $this->db->join('rutas as r','u.Id_Ruta = r.Id_Ruta');
        $this->db->join('distribuidora as d','r.Id_Distribuidora = d.Id_Distribuidora');
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais',$pais);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }



    /*-------------------------------------------------------------------------------*/
    /*-----------TABLA CONSULTA CLIENTES APROBADOS EN VISTA ANALISTAS----------------*/
    /*-------------------------------------------------------------------------------*/
    function obtener_tabla_procesados($datosb,$start,$limit,$vacio){ 
        $this->db->select("c.Id_Cliente,c.Codigo,c.Nombre,c.Direccion,c.Telefono,c.Contacto,c.Propietario,c.Orden_Visita,c.Dias,c.RefUno,c.Latitud,c.Longitud,ru.Nombre_Ruta,m.Id_Municipio,m.NombreMunicipio,d.Id_Departamento,d.NombreDepartamento,c.Foto_Negocio,g.Nombre_Gnegocio,tpv.Id_Tpuntoventa,tpv.Nombre_TpuntoV,tf.Nombre_Tfacturacion,tf.Id_Tfacturacion,c.Dui,c.Numero_Registro,c.Nit,con.Nombre_Condicionc,c.Dia_Cobro,c.Monto_Credito,c.CompraS_B,c.CompraS_D,c.CompraS_Y,c.CompraS_F,c.Exhibiror_Uno,c.Exhibiror_Dos,c.Exhibiror_Tres,c.Cantidad_Exhibidor,c.Foto_Exhibidor,c.Fecha_Ingreso,c.Estado,c.Fecha_Resolucion,s.Id_Gruporutas,c.Fecha_Resolucion_R,c.quienresolucion,c.Ord_VisitaSema");
        $this->db->from("clientes as c");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");  
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->join("giro_negocio as g","c.Id_Gironegocio = g.Id_Gironegocio");
        $this->db->join("tipo_punto_venta as tpv","g.Id_Tpuntoventa = tpv.Id_Tpuntoventa");
        $this->db->join("tipo_facturacion as tf","c.Id_Tfacturacion = tf.Id_Tfacturacion");
        $this->db->join("condicion_cliente as con","c.Id_Condicionc = con.Id_Condicionc");
        $this->db->where('p.Nombre_Pais',$this->session->userdata('pais'));
        $this->db->where('c.Estado_Analista', 'A');
        $this->db->where('c.EstadoDescarga', '0');
        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{}
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('ru.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        $this->db->limit($limit,$start);
        $this->db->order_by('ru.Nombre_Ruta', 'ASC');
        $this->db->order_by('c.Codigo', 'ASC');
        $query = $this->db->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    /*-------------------------------------------------------------------------------*/
    /*------------TABLA DE CONSULTA CLIENTES APROBADOS USUARIO ANALISTAS-------------*/
    /*-------------------------------------------------------------------------------*/
    function contar_clientes_procesados($datosb){
        $this->db->select('COUNT(*) as totolu');
        $this->db->from('clientes as c');
        $this->db->join("ref as r","c.Id_ref = r.Id_ref");
        $this->db->join("usuarios as u","c.Id_Usuarios = u.Id_Usuarios");
        $this->db->join("rutas as ru","u.Id_Ruta = ru.Id_Ruta");
        // $this->db->join("supervisores as s","ru.Id_Supervisor = s.Id_Supervisor");
        $this->db->join("distribuidora as dist","ru.Id_Distribuidora = dist.Id_Distribuidora");
        $this->db->join("municipio as m","c.Id_Municipio = m.Id_Municipio");
        $this->db->join("departamento as d","m.Id_Departamento = d.Id_Departamento");
        $this->db->join('pais as p','d.Id_Pais = p.Id_Pais');
        $this->db->where('p.Nombre_Pais',$this->session->userdata('pais'));
        $this->db->where('c.Estado_Analista', 'A');
        $this->db->where('c.EstadoDescarga', '0');
        $this->db->order_by('c.Codigo', 'ASC');
        if(!empty($datosb["rutas"])){
            $this->db->where('ru.Id_Ruta', $datosb["rutas"]);
        }else{}
        if(!empty($datosb['distribuidoras'])){
            $this->db->where_in('dist.Id_Distribuidora',$datosb["distribuidoras"]);
        }else{}
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return array();
        }
    }


    /*______++++++++++++++_____++++++++______+++++++_____+++++++______++++++++_______+++++++_______*/
    /*            VISTA DE CLIENTES APROBADOR POR EL ANALISTA - ACTUALIZACION DE CLIENTES          */
    /*______++++++++++++++_____++++++++______+++++++_____+++++++______++++++++_______+++++++_______*/
    function contar_clientes_procesadosAC($datosb){

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
        INNER JOIN ( SELECT Id_Cliente,MAX(FechaAAnalista) as fecha_max
          FROM actualizacion_clientes GROUP BY Id_Cliente
        ) as b ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaAAnalista = fecha_max WHERE ac.EstadoASupervisor = 'A' AND ac.EstadoAAnalista='A' AND ac.EstadoDescarga=0 AND p.Nombre_Pais='".$this->session->userdata('pais')."'";
  
        if(!empty($datosb['rutas'])){
          $where_ruta = " AND ru.Id_Ruta = ".$datosb['rutas'];
        }else{}
  
        if(!empty($datosb['distribuidoras'])){
          $distrib = join(",",$datosb['distribuidoras']);   
          $where_distribuidora = " AND dd.Id_Distribuidora IN ( ".$distrib." )";
        }else{}
        $QueryCompleta = "";
        $QueryCompleta = $query_select.$where_ruta.$where_distribuidora;
        $resultado = $this->db->query($QueryCompleta);
        if ($resultado->num_rows() > 0){
          return $resultado->row();
        }else{
          return array();
        }



    }

    function obtener_tabla_procesadosAC($datosb,$start,$limit,$vacio){


        $where_ruta = "";$where_distribuidora = "";
        $query_select = "SELECT m.Id_Municipio,d.Id_Departamento,ac.Id_AC,ac.Id_Cliente,ac.CodigoAC,ac.NombreAC,ac.DireccionAC,ac.TelefonoAC,ac.ContactoAC,ac.OrdenVistaAC,ac.DiasAC,ac.LatitudAC,ac.LongitudAC,ru.Id_Ruta,ru.Nombre_Ruta,ac.FechaACSer,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,ac.EstadoAC,ac.FrecuencVisitaAC,ac.FechaAAnalista,ac.QuienAutorizo
        FROM actualizacion_clientes as ac
        INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios
        INNER JOIN rutas as ru ON u.Id_Ruta = ru.Id_Ruta
        INNER JOIN supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor
        INNER JOIN municipio as m ON ac.Id_Municipio = m.Id_Municipio
        INNER JOIN departamento as d ON m.Id_Departamento = d.Id_Departamento
        INNER JOIN distribuidora as dd ON ru.Id_Distribuidora = dd.Id_Distribuidora
        INNER JOIN pais as p ON dd.Id_Pais = p.Id_Pais
        INNER JOIN ( SELECT Id_Cliente,MAX(FechaAAnalista) as fecha_max
          FROM actualizacion_clientes GROUP BY Id_Cliente
        ) as b ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaAAnalista = fecha_max WHERE ac.EstadoASupervisor = 'A' AND ac.EstadoAAnalista='A' AND ac.EstadoDescarga=0 AND p.Nombre_Pais='".$this->session->userdata('pais')."'";
  
        if(!empty($datosb['rutas'])){
          $where_ruta = " AND ru.Id_Ruta = ".$datosb['rutas'];
        }else{}
  
        if(!empty($datosb['distribuidoras'])){
          $distrib = join(",",$datosb['distribuidoras']);   
          $where_distribuidora = " AND dd.Id_Distribuidora IN ( ".$distrib." )";
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

    function Plantilla_procesadosACPl($datosb){

        $where_ruta = "";$where_distribuidora = "";
        $query_select = "SELECT ac.Id_Municipio,d.Id_Departamento,ac.Id_AC,ac.Id_Cliente,ac.CodigoAC,ac.NombreAC,ac.DireccionAC,ac.TelefonoAC,ac.ContactoAC,ac.OrdenVistaAC,ac.DiasAC,ac.LatitudAC,ac.LongitudAC,ru.Id_Ruta,ru.Nombre_Ruta,ac.FechaACSer,m.NombreMunicipio,d.NombreDepartamento,ru.Id_Supervisor,ac.EstadoAC,ac.FrecuencVisitaAC,ac.FechaAAnalista,ac.QuienAutorizo,ac.DuiAC,tpv.Nombre_TpuntoV,g.Nombre_Gnegocio,tf.Nombre_Tfacturacion,con.Nombre_Condicionc,c.Monto_Credito,c.Cantidad_Exhibidor,c.Exhibiror_Uno,c.Exhibiror_Dos,c.Exhibiror_Tres,c.CompraS_B,c.CompraS_D,c.CompraS_Y,c.CompraS_F,c.Fecha_Ingreso,c.Fecha_Resolucion,ac.EstadoSICambio,ac.FechaASupervisor,ac.FechaAAnalista,ac.Id_Tfacturacion,ac.Numero_RegistroAC,ac.NitAC,ac.Cantidad_CMR,
              SUBSTRING_INDEX(ac.Ord_VisitaSema,',',1) AS OrdLunes,
              SUBSTRING_INDEX(SUBSTRING_INDEX(ac.Ord_VisitaSema,',',2),',',-1) AS OrdMartes,
              SUBSTRING_INDEX(SUBSTRING_INDEX(ac.Ord_VisitaSema,',',3),',',-1) AS OrdMiercoles,
              SUBSTRING_INDEX(SUBSTRING_INDEX(ac.Ord_VisitaSema,',',4),',',-1) AS OrdJueves,
              SUBSTRING_INDEX(SUBSTRING_INDEX(ac.Ord_VisitaSema,',',5),',',-1) AS OrdViernes,
              SUBSTRING_INDEX(SUBSTRING_INDEX(ac.Ord_VisitaSema,',',6),',',-1) AS OrdSabado,
              SUBSTRING_INDEX(SUBSTRING_INDEX(ac.Ord_VisitaSema,',',7),',',-1) AS OrdDomingo,
              ac.Ord_VisitaSema,
              tf.Tfc_Id,
              c.TokenCliNuevo
        FROM actualizacion_clientes as ac
        INNER JOIN clientes as c ON ac.Id_Cliente = c.Id_Cliente
        INNER JOIN usuarios as u ON ac.Id_Usuarios = u.Id_Usuarios
        INNER JOIN rutas as ru ON u.Id_Ruta = ru.Id_Ruta
        INNER JOIN supervisores as s ON ru.Id_Supervisor = s.Id_Supervisor
        INNER JOIN municipio as m ON ac.Id_Municipio = m.Id_Municipio
        INNER JOIN departamento as d ON m.Id_Departamento = d.Id_Departamento
        INNER JOIN distribuidora as dd ON ru.Id_Distribuidora = dd.Id_Distribuidora
        INNER JOIN pais as p ON dd.Id_Pais = p.Id_Pais
        INNER JOIN giro_negocio as g ON c.Id_Gironegocio = g.Id_Gironegocio
        INNER JOIN tipo_punto_venta as tpv ON g.Id_Tpuntoventa = tpv.Id_Tpuntoventa
        INNER JOIN tipo_facturacion as tf ON c.Id_Tfacturacion = tf.Id_Tfacturacion
        INNER JOIN condicion_cliente as con ON c.Id_Condicionc = con.Id_Condicionc
        INNER JOIN ( SELECT Id_Cliente,MAX(FechaAAnalista) as fecha_max
          FROM actualizacion_clientes GROUP BY Id_Cliente
        ) as b ON ac.Id_Cliente = b.Id_Cliente AND ac.FechaAAnalista = fecha_max WHERE ac.EstadoASupervisor = 'A' AND ac.EstadoAAnalista='A' AND ac.EstadoDescarga=0 AND p.Nombre_Pais='".$this->session->userdata('pais')."'";
  
        if(!empty($datosb['rutas'])){
          $where_ruta = " AND ru.Id_Ruta = ".$datosb['rutas'];
        }else{}
  
        if(!empty($datosb['distribuidoras'])){
          $distrib = join(",",$datosb['distribuidoras']);   
          $where_distribuidora = " AND ru.Id_Distribuidora IN ( ".$distrib." )";
        }else{}
  
        $query_select.=$where_ruta.$where_distribuidora." ORDER BY ru.Nombre_Ruta,ac.CodigoAC ASC";
        $resultado = $this->db->query($query_select);
        $resultado = $resultado->result();
        if(!empty($resultado)){
          return $resultado;
        }else{
          return array();
        }

    }

    /*______++++++++++++++_____++++++++______+++++++_____+++++++______++++++++_______+++++++_______*/
    /*______++++++++++++++_____++++++++______+++++++_____+++++++______++++++++_______+++++++_______*/

}