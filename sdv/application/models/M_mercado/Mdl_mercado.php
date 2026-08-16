<?php if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Mdl_mercado extends CI_Model
 {
    private $DB2;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database( 'database_sdv_2', TRUE );
    }

    function get_tareas( $id ) {
        $this->load->database();
        $this->DB2->select( '
          tbl_evaluacion_mercado.nombreEstablecimiento,
          tbl_evaluacion_mercado.direccion,
          tbl_evaluacion_mercado.oportunidad,
          tbl_tareas.fecha,
          tbl_tareas.asignado_a,
          tbl_tareas.estado,
          tbl_tareas.ruta_asignada,
          tbl_tareas.comentario,
          tbl_evaluacion_mercado.foto_uno,
          tbl_evaluacion_mercado.foto_dos,
          tbl_evaluacion_mercado.foto_tres,
          tbl_evaluacion_mercado.id_evaluacion,
          tbl_evaluacion_mercado.n_oportunidad,
          tbl_evaluacion_mercado.longitud,
          tbl_evaluacion_mercado.latitud,
          tbl_evaluacion_mercado.codigoCliente,
          tbl_tareas.id_evaluacion,
          tbl_oportunidades.nombre_oportunidad,
          tbl_tareas.tarea_id
      ' );
        $this->DB2->from( 'tbl_evaluacion_mercado' );
        $this->DB2->join( 'tbl_tareas', 'tbl_tareas.id_evaluacion = tbl_evaluacion_mercado.id_evaluacion', 'inner' );
        $this->DB2->join( 'tbl_oportunidades', 'tbl_oportunidades.id = tbl_evaluacion_mercado.n_oportunidad', 'inner' );
        $this->DB2->where( 'tbl_tareas.ruta_asignada', $id );
        $this->DB2->where( 'tbl_tareas.estado', '1' );
        $query = $this->DB2->get();

        // Para obtener los resultados como un array asociativo:
        $result = $query->result_array();

        return $result;

    }

    public function guardarFotos( $id, $data ) {
        $data[ 'estado' ] = 2;
        $this->DB2->where( 'tarea_id', $id );
        $this->DB2->or_where('hash', $id);
        $this->DB2->update( 'tbl_tareas', $data );
    }

    public function actuTareas($id, $data){
        $this->DB2->where( 'tarea_id', $id );
        $this->DB2->or_where('hash', $id);
        $this->DB2->update( 'tbl_tareas', $data );  
    }

    public function list_mercado($id_creado, $ruta) {

        $this->DB2->select('*');
        $this->DB2->from('tbl_evaluacion_mercado');
        $this->DB2->where('id_creado', $id_creado);
        $query = $this->DB2->get();
        $result = $query->result();

        if (empty($result)) {
            print_r("entro al if con ID:",$id_creado);
            // Si el resultado es nulo, ejecuta la segunda consulta
            $this->DB2->select('tbl_evaluacion_mercado.*');
            $this->DB2->from('tbl_tareas, tbl_evaluacion_mercado');
            $this->DB2->where('tbl_tareas.hash = tbl_evaluacion_mercado.hash');
            $this->DB2->where('tbl_tareas.asignado_a', $ruta);
            $query = $this->DB2->get();
            $result = $query->result();
        }

        return $result;
    }
    

    public function list_tareas() {
        $this->DB2->select( '*' );
        // Selecciona todas las columnas de la tabla
        $this->DB2->from( 'tbl_tareas' );
        // Selecciona la tabla 'tbl_tareas'
        $query = $this->DB2->get();
        // Ejecuta la consulta
        $result = $query->result();
        // Almacena los resultados en un array
        // Retorna los resultados para su uso en el controlador o vista
        return $result;
    }

    public function list_oportunidades() {
        $this->DB2->select( '*' );
        // Selecciona todas las columnas de la tabla
        $this->DB2->from( 'tbl_oportunidades' );
        // Selecciona la tabla 'tbl_tareas'
        $query = $this->DB2->get();
        // Ejecuta la consulta
        $result = $query->result();
        // Almacena los resultados en un array
        return $result;
    }
    public function insertar_formulario($data) {
        // Comprueba si el hash ya existe en la base de datos
        $this->DB2->where('hash', $data['hash']);
        $query = $this->DB2->get('tbl_evaluacion_mercado');
    
        // Si no hay resultados, procede con la inserción
        if ($query->num_rows() == 0) {
            $this->DB2->insert('tbl_evaluacion_mercado', $data);
    
            // Verifica si la operación afectó alguna fila
            if ($this->DB2->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            // Hash ya existe en la base de datos
            return false;
        }
    }
    
    
    public function insertar_tareas( $data ) {
        // Comprueba si el hash ya existe en la base de datos
        $this->DB2->where( 'hash', $data[ 'hash' ] );
        $query = $this->DB2->get( 'tbl_tareas' );

        // Si no hay resultados, procede con la inserción
        if ( $query->num_rows() == 0 ) {
            $this->DB2->insert( 'tbl_tareas', $data );
            return $this->DB2->insert_id();
        } else {
            // Retorna un valor o mensaje de error para indicar que el hash ya existe
            return 'Error: El hash ya existe';
        }

    }

    public function tareas( $id_usuario ) {

        print( $id_usuario );
        $this->DB2->select( '
          tbl_evaluacion_mercado.nombreEstablecimiento,
          tbl_evaluacion_mercado.direccion,
          tbl_evaluacion_mercado.oportunidad,
          tbl_tareas.fecha,
          tbl_tareas.asignado_a,
          tbl_tareas.estado,
          tbl_tareas.ruta_asignada,
          tbl_tareas.comentario,
          tbl_tareas.foto_u,
          tbl_tareas.foto_d,
          tbl_tareas.foto_t,
          tbl_evaluacion_mercado.foto_uno,
          tbl_evaluacion_mercado.foto_dos,
          tbl_evaluacion_mercado.foto_tres,
          tbl_evaluacion_mercado.id_evaluacion,
          tbl_evaluacion_mercado.n_oportunidad,
          tbl_evaluacion_mercado.longitud,
          tbl_evaluacion_mercado.latitud,
          tbl_evaluacion_mercado.codigoCliente,
          tbl_tareas.id_evaluacion,
          tbl_tareas.tarea_id,
          tbl_tareas.tipo_tarea
        ' );
        $this->DB2->from( 'tbl_evaluacion_mercado' );
        $this->DB2->join( 'tbl_tareas', 'tbl_tareas.id_evaluacion = tbl_evaluacion_mercado.id_evaluacion', 'inner' );
        $this->DB2->where( 'tbl_tareas.creado_por', 1101 );
        $query = $this->DB2->get();

        // Para obtener los resultados como un array asociativo:
        $result = $query->result_array();
        return $result;

    }

    public function asignarTarea($data) {
        // Define los datos a insertar
        $updateData = array(
            'comentario' => $data['comentario'],
            'ruta_asignada' => $data['ruta'],
            'asignado_a' => $data['asignado_a'],
            'estado'=> $data['estado']
        );
        $this->DB2->where('tarea_id', $data['id']);
        $this->DB2->or_where('hash', $data['id']);
        $this->DB2->update('tbl_tareas', $updateData);
        
        // Verifica si la operación afectó alguna fila
        if ($this->DB2->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    
}
?>