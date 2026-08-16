<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Mdl_api_xamarin extends CI_Model
{
    private $DB2;
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }

    public function List_Clientes($lat, $long, $distancia)
    {
        $query_select = "SELECT Cli_Id,Cli_codigo,Cli_nombre , Cli_direccion, Cli_latitud, Cli_longitud, ( 6371 * acos(cos(radians(" . $lat . ")) * cos(radians(Cli_latitud)) * cos(radians(Cli_longitud) - radians(" . $long . ")) + sin(radians(" . $lat . ")) * sin(radians(Cli_latitud)))) AS distance FROM tbl_cliente where Cli_estado = 1 and Cli_estado_sys = 'P' and Cli_valid_cod = 1 and Cli_estado_descarga = 1 HAVING distance < " . $distancia . "  ORDER BY distance ";
        $resultado = $this->DB2->query($query_select);
        $resultado = $resultado->result();
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return array();
        }
    }

    public function List_Clientes_X_Cli($lat, $long, $distancia, $IdCliente)
    {
        $query_select = "SELECT Cli_Id,Cli_codigo,Cli_nombre , Cli_direccion, Cli_latitud, Cli_longitud, ( 6371 * acos(cos(radians(" . $lat . ")) * cos(radians(Cli_latitud)) * cos(radians(Cli_longitud) - radians(" . $long . ")) + sin(radians(" . $lat . ")) * sin(radians(Cli_latitud)))) AS distance FROM tbl_cliente where Cli_estado = 1 and Cli_Id = " . $IdCliente . " and Cli_estado_sys = 'P' and Cli_valid_cod = 1 and Cli_estado_descarga = 1 HAVING distance < " . $distancia . "  ORDER BY distance ";
        $resultado = $this->DB2->query($query_select);
        $resultado = $resultado->result();
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return array();
        }
    }

    public function loginApk($usuario, $contra)
    {
        $this->DB2->select('
            user,
            password,
            usuEstado,
            UsuActContrasena
            ');
        $this->DB2->from('LoginApK');
        $this->DB2->where('user', $usuario);
        $query = $this->DB2->get();
        $usuario = $query->result();
        if (!empty($usuario)) {
            if (verifyHashedPassword($contra, $usuario[0]->password)) {
                return $usuario;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function modificarUsuario($datas, $userid)
    {
        $this->DB2->where('user', $userid);
        $this->DB2->update('LoginApK', $datas);
        if ($this->DB2->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function InfoUsuario($usuario)
    {
        $this->DB2->select('
            user,
            usuEstado,
            UsuActContrasena
            ');
        $this->DB2->from('LoginApK');
        $this->DB2->where('user', $usuario);
        $query = $this->DB2->get();
        $usuario = $query->result();
        if (!empty($usuario)) {
            return $usuario;
        } else {
            return array();
        }
    }

    function ListaClienteXZona($zona)
    {
        $this->DB2->select('Cli_Id,Cli_codigo,Cli_nombre , Cli_direccion, Cli_latitud, Cli_longitud');
        $this->DB2->from('tbl_cliente');
        $this->DB2->join('tbl_rutas', 'Cli_Ru_Id = Ru_Id', 'left');
        $this->DB2->join('tbl_distrito', 'Ru_Dist_Id = Dist_Id', 'left');
        $this->DB2->join('tbl_canal', 'Dist_Ca_Id = Ca_Id', 'left');
        $this->DB2->join('tbl_distribuidora', 'Ca_Dis_Id = Dis_Id', 'left');
        $this->DB2->join('tbl_division', 'Dis_Di_Id = Di_Id', 'left');
        $this->DB2->join('tbl_giro_negocio', 'Cli_Gir_Id = Gir_Id', 'left');
        $this->DB2->join('tbl_tipo_punto_venta', 'Tpv_Id = Gir_Tpv_Id', 'left');
        $this->DB2->where('Cli_codigo!=', '0');
        $this->DB2->where('Cli_estado', 1);
        // $this->DB2->where('Cli_codigo!=', '0000000');
        $this->DB2->where('Cli_estado_sys', 'P');
        $this->DB2->where('Cli_estado_descarga', 1);
        $this->DB2->where('Dis_nombre', $zona);
        $query = $this->DB2->get();
        $resultado = $query->result();
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return array();
        }
    }

    public function loginApkFull()
    {
        $this->DB2->select('
            user,
            password,
            usuEstado,
            UsuActContrasena
            ');
        $this->DB2->from('LoginApKOffLine');
        $query = $this->DB2->get();
        $resultado = $query->result();
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return array();
        }
    }

    public function modificarUsuariOffline($datas, $userid)
    {
        $this->DB2->where('user', $userid);
        $this->DB2->update('LoginApKOffLine', $datas);
        if ($this->DB2->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function List_CensoPorDistancia($lat, $long, $distancia) {
        // Validar que los parámetros sean números válidos para prevenir inyecciones SQL
        if (!is_numeric($distancia) || !is_numeric($lat) || !is_numeric($long)) {
            return array(); // O manejar el error de otra manera adecuada
        }
    
        // Convertir la distancia de metros a kilómetros
        $distancia = $distancia / 1000;
    
        // Construir la consulta SQL con la distancia interpolada y redondeada a 4 decimales
        $sql = "
            SELECT 
                Cc_Id,
                Cc_Nombre_negocio,
                Cc_direccion,
                ROUND(
                    (6371 * acos(
                        cos(radians($lat)) * 
                        cos(radians(Cc_latitud)) * 
                        cos(radians(Cc_longitud) - radians($long)) + 
                        sin(radians($lat)) * 
                        sin(radians(Cc_latitud))
                    )), 4
                ) AS distance
            FROM 
                tbl_clientes_censo
            HAVING 
                distance < $distancia
            ORDER BY 
                distance ASC
        ";
    
        // Ejecutar la consulta
        $resultado = $this->DB2->query($sql);
        $resultado = $resultado->result();
    
        return !empty($resultado) ? $resultado : array();
    }


    



    public function insertarMatchAppCenso($ruta, $CodigoCliente, $Cc_nombre_negocio, $latitud, $longitud, $Cc_id, $fecha,$paisMach) {
        // Validar que los parámetros son del tipo correcto para prevenir inyecciones SQL
        if (!is_string($ruta) || !is_string($CodigoCliente) || !is_string($Cc_nombre_negocio) || 
            !is_numeric($latitud) || !is_numeric($longitud) || !is_string($Cc_id) || !is_string($fecha)|| !is_string($paisMach)) {
            log_message('error', 'Parámetros inválidos.');
            return false; // O manejar el error de una manera apropiada
        }
    
        // Validar que la fecha sea válida
        if (!strtotime($fecha)) {
            log_message('error', 'Fecha inválida.');
            return false; // O manejar el error de una manera apropiada
        }
    
        // Convertir la fecha al formato compatible con la base de datos
        $fecha = date('Y-m-d H:i:s', strtotime($fecha));

        $FechaBase = new DateTime(); 

        $fechaFormateada = $FechaBase->format('Y-m-d H:i:s'); 

    
        // Sanitizar los parámetros de entrada (opcional pero recomendado)
        $ruta = $this->db->escape($ruta);
        $CodigoCliente = $this->db->escape($CodigoCliente);
        $Cc_nombre_negocio = $this->db->escape($Cc_nombre_negocio);
        $latitud = $this->db->escape($latitud);
        $longitud = $this->db->escape($longitud);
        $Cc_id = $this->db->escape($Cc_id);
        $fecha = $this->db->escape($fechaFormateada);
        $paisMach = $this->db->escape($paisMach);
    
        // Construir la consulta SQL
        $sql = "
            INSERT INTO MatchAppCenso (ruta, CodigoCliente, Cc_nombre_negocio, latitud, longitud, Cc_id, Fecha,Pais)
            VALUES ($ruta, $CodigoCliente, $Cc_nombre_negocio, $latitud, $longitud, $Cc_id, $fecha,$paisMach)
        ";
    
        // Ejecutar la consulta
        if ($this->db->query($sql)) {
            // Verificar si la inserción fue exitosa
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                log_message('error', 'No se afectaron filas en la inserción.');
                return false;
            }
        } else {
            // Registrar error de base de datos
            $error = $this->db->error();
            log_message('error', 'Error en la consulta SQL: ' . $error['message']);
            return false;
        }
    }
    

    
    public function actualizarCliente($CodigoCliente, $Cc_id) {
        // Validar que los parámetros son del tipo correcto para prevenir inyecciones SQL
        if (!is_string($CodigoCliente) || !is_string($Cc_id)) {
            log_message('error', 'Parámetros inválidos.');
            return false; // Manejar el error de manera apropiada
        }
    
        // Sanitizar los parámetros de entrada para prevenir inyecciones SQL
        $CodigoCliente = $this->db->escape($CodigoCliente); // Escapar los valores para la consulta
        $Cc_id = $this->db->escape($Cc_id);
    
        // Construir la consulta SQL para la actualización
        $sql = "
            UPDATE tbl_cliente
            SET Cc_id = $Cc_id
            WHERE Cli_codigo = $CodigoCliente
        ";
    
        // Ejecutar la consulta
        if ($this->db->query($sql)) {
            // Verificar si la actualización fue exitosa
            if ($this->db->affected_rows() > 0) {
                return true; // La actualización se realizó correctamente
            } else {
                log_message('error', 'No se afectaron filas en la actualización.');
                return false; // No se realizaron cambios
            }
        } else {
            // Registrar error de base de datos
            $error = $this->db->error();
            log_message('error', 'Error en la consulta SQL: ' . $error['message']);
            return false;
        }
    }
    
 

    public function ValidacionClienteXamarinController($codigo, $Ru_nombre) {
        // Verifica que los parámetros no sean vacíos o nulos
        if (empty($codigo) || empty($Ru_nombre)) {
            return array();  // Retorna un array vacío si los parámetros son inválidos
        }
    
        // Consulta SQL con un marcador de posición para el parámetro
        $sql = "
            SELECT cli.Cli_codigo, cli.Cli_nombre, cli.Cli_contacto, mun.Mun_descripcion, cli.Cli_direccion,Cc_id
            FROM tbl_cliente cli
            JOIN tbl_municipio mun ON cli.Cli_Mun_Id = mun.Mun_Id
            JOIN tbl_rutas ru ON cli.Cli_Ru_Id = ru.Ru_Id
            WHERE cli.Cli_codigo = ?
            AND cli.Cli_estado = 1
            AND ru.Ru_nombre = ?
        ";
    
        // Ejecuta la consulta con los parámetros de manera segura
        try {
            $query = $this->db->query($sql, array($codigo, $Ru_nombre));
            $resultado = $query->result();
    
            // Retorna el resultado o un array vacío si no se encuentran datos
            return !empty($resultado) ? $resultado : array();
        } catch (Exception $e) {
            // En caso de error, captura la excepción y maneja el error (opcional: loguear el error)
            log_message('error', 'Error al ejecutar la consulta: ' . $e->getMessage());
            return array();  // Retorna un array vacío en caso de error
        }
    }
    
    public function ConsultaClienteVinculoCenso($codigo) {
        // Verifica que los parámetros no sean vacíos o nulos
        if (empty($codigo)) {
            return array();  // Retorna un array vacío si los parámetros son inválidos
        }
    
        // Consulta SQL con un marcador de posición para el parámetro
        $sql = "
        SELECT 
            cli.Cli_codigo,
            cli.Cli_nombre,
            cli.Cli_direccion,
            censo.Cc_nombre_negocio,
            CONCAT(censo.Cc_nombres_propietario, ' ', IFNULL(censo.Cc_apellidos_propietario, '')) AS nombre,
            censo.Cc_direccion
        FROM 
            tbl_cliente AS cli
        INNER JOIN 
            tbl_clientes_censo AS censo
        ON 
            cli.Cc_id = censo.Cc_id
        WHERE 
            cli.Cli_codigo = ?        
        ";
        
    
        // Ejecuta la consulta con los parámetros de manera segura
        try {
            $query = $this->db->query($sql, array($codigo));
            $resultado = $query->result();
    
            // Retorna el resultado o un array vacío si no se encuentran datos
            return !empty($resultado) ? $resultado : array();
        } catch (Exception $e) {
            // En caso de error, captura la excepción y maneja el error (opcional: loguear el error)
            log_message('error', 'Error al ejecutar la consulta: ' . $e->getMessage());
            return array();  // Retorna un array vacío en caso de error
        }
    }



    public function ConsultaClientesPorRutaOfflineCenso($Ruta) {
        // Verifica que el parámetro no sea vacío o nulo
        if (empty($Ruta)) {
            return array();  // Retorna un array vacío si el parámetro es inválido
        }
    
        // Consulta SQL con marcador de posición para el parámetro
        $sql = "
			SELECT cli.Cli_Id,cli.Cli_codigo, cli.Cli_nombre, cli.Cli_direccion,cli.Cli_Telefono,cli.Cli_contacto,ru.Ru_Nombre,mun.Mun_descripcion,Cc_id
            FROM tbl_cliente cli
            JOIN tbl_municipio mun ON cli.Cli_Mun_Id = mun.Mun_Id
            JOIN tbl_rutas ru ON cli.Cli_Ru_Id = ru.Ru_Id
            WHERE  cli.Cli_estado = 1
            AND ru.Ru_nombre = ?
        ";
    
        // Ejecuta la consulta con los parámetros de manera segura
        try {
            $query = $this->db->query($sql, array($Ruta));
            $resultado = $query->result();
    
            // Retorna el resultado o un array vacío si no se encuentran datos
            return !empty($resultado) ? $resultado : array();
        } catch (Exception $e) {
            // En caso de error, captura la excepción y maneja el error
            log_message('error', 'Error al ejecutar la consulta: ' . $e->getMessage());
            return array();  // Retorna un array vacío en caso de error
        }
    }
    

    public function actualizarClienteErrorVinculacion($CodigoCliente) {
        // Validar que el parámetro no esté vacío
        if (empty($CodigoCliente) || !is_string($CodigoCliente)) {
            log_message('error', 'Parámetro inválido: CódigoCliente debe ser una cadena no vacía.');
            return false; // Manejo apropiado de error
        }
    
        // Construir la consulta SQL con query bindings para prevenir inyecciones SQL
        $sql = "
            UPDATE tbl_cliente
            SET ObservacionVinculacion = 1
            WHERE Cli_codigo = ?
        ";
    
        // Ejecutar la consulta de manera segura
        try {
            $this->db->query($sql, array($CodigoCliente));
    
            // Verificar si se afectaron filas
            if ($this->db->affected_rows() > 0) {
                return true; // Actualización exitosa
            } else {
                log_message('error', 'No se afectaron filas en la actualización para CódigoCliente: ' . $CodigoCliente);
                return false; // No se realizaron cambios
            }
        } catch (Exception $e) {
            // Registrar cualquier error de la base de datos
            log_message('error', 'Error en la consulta SQL: ' . $e->getMessage());
            return false;
        }
    }
    


    public function ConsultaCensoOFFLineBaseCensoPor_Pais($pais) {
        if (empty($pais)) {
            return array();
        }
    
        // Consulta según el país
        if ($pais == '1') {
            $sql = "
                SELECT 
                    Cc_Id,
                    Cc_Nombre_negocio,
                    Cc_direccion,
                    Cc_latitud,
                    Cc_longitud,
                     1 as pais 
                FROM 
                    tbl_clientes_censo
            ";
        } elseif ($pais == '2') {
            $sql = "
                SELECT 
                    Cc_Id,
                    Cc_Nombre_negocio,
                    Cc_direccion,
                    Cc_latitud,
                    Cc_longitud,
                     2 as pais 
                FROM 
                    tbl_Censo_GT
            ";
        } else {
            return array(); // País no válido
        }
    
        try {
            $query = $this->db->query($sql, array());
            $resultado = $query->result();
            return !empty($resultado) ? $resultado : array();
        } catch (Exception $e) {
            log_message('error', 'Error al ejecutar la consulta: ' . $e->getMessage());
            return array();
        }
    }


    public function ConsultaUsuarioLoginCensoController($Usuario) {
        // Validar que el parámetro no esté vacío
        if (empty($Usuario) || !is_string($Usuario)) {
            log_message('error', 'Parámetro inválido: usuario debe ser una cadena no vacía.');
            return false;
        }
    
        $sql = "
                SELECT 
            usu.Usu_Id,
            usu.Usu_nombre_usuario,
            usu.Usu_usuario,
            usu.Usu_contrasena,
            pais.P_Id,
            pais.P_nombre
        FROM tbl_usuario usu
        INNER JOIN tbl_rutas ruta ON usu.Usu_Ru_Id = ruta.Ru_Id
        INNER JOIN tbl_distrito distrito ON ruta.Ru_Dist_Id = distrito.Dist_Id
        INNER JOIN tbl_canal canal ON distrito.Dist_Ca_Id = canal.Ca_Id            
        INNER JOIN tbl_distribuidora dis ON canal.Ca_Dis_Id = dis.Dis_Id
        INNER JOIN tbl_division divis ON dis.Dis_Di_Id = divis.Di_Id
        INNER JOIN tbl_pais pais ON divis.Di_P_Id = pais.P_Id
        WHERE usu.Usu_usuario  = ?
        ";
    
        try {
            $query = $this->db->query($sql, array($Usuario));
    
            // Verificamos si hay resultados
            if ($query->num_rows() > 0) {
                return $query->row_array(); // Devuelve el primer resultado como array
            } else {
                log_message('error', 'No se encontró usuario con estos datos: ' . $Usuario);
                return false;
            }
        } catch (Exception $e) {
            log_message('error', 'Error en la consulta SQL: ' . $e->getMessage());
            return false;
        }
    }
    


}
?>