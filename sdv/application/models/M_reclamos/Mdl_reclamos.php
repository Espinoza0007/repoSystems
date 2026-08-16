<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_reclamos extends CI_Model
{
    private $DB2;

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->DB2 = $this->load->database('database_sdv_2', TRUE);
    }
      
    function listadoProductosReclamos($canal){
        $this->DB2->select('tbl_catalogo_productos.*, Um_nombre, Fa_nombre, Subf_nombre, Catx_estado,Subf_Fa_Id');
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos','Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->where('Catx_estado', 1);

        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    

    function listadoTipoDanosReclamos($area_tipo){
        $this->DB2->select('Tipd_Id, Tipd_descripcion,Tipd_Trec_Id Trec_Id');
        $this->DB2->from('tbl_tipo_danos');
        // $this->DB2->where('Tipd_area', $area_tipo);

        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    public function guardarReclamoNuevo($data_insertar){
       
        $this->DB2->insert('tbl_reclamo_pfn', $data_insertar);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }

    function verificar_producto_reclamo($data){
        $this->DB2->select('Rec_Id, Rec_token, Rec_Cli_Id, Cat_unidad_medida');
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_catalogo_productos', 'Cat_Id = Rec_Cat_Id');
        $this->DB2->where('Rec_Id', $data[0]);
        $this->DB2->where('Rec_Cat_Id_SKU', $data[1]);
        $this->DB2->where('Cat_unidad_medida', $data[3]);

        return $this->DB2->count_all_results() > 0 ? true : false;       

    }

    function filtro_familias($canal){
        $this->DB2->select('DISTINCT(Fa_nombre)');
        $this->DB2->from('tbl_catalogo_productos');
        $this->DB2->join('tbl_catalogopro_x_canal','Catx_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id','right');
        $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->group_by('Fa_nombre');

        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

   function filtro_familias1($canal){
        /*$this->DB2->select('DISTINCT(Fa_nombre), Fa_Id');
        $this->DB2->from('tbl_catalogo_productos');
        $this->DB2->join('tbl_catalogopro_x_canal','Catx_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->group_by('Fa_nombre');*/

        $this->DB2->select('DISTINCT(Fa_nombre), Fa_Id');
        $this->DB2->from('tbl_catalogo_productos');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id','right');
        $this->DB2->group_by('Fa_nombre');

        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

   /* function filtro_subfamilias($canal,$codigo_familia){
     

        $this->DB2->select('DISTINCT(Subf_nombre), Subf_Id');
        $this->DB2->from('tbl_catalogo_productos');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->where('Subf_Fa_Id =', $codigo_familia);
        $this->DB2->group_by('Subf_nombre');

        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }*/

    public function filtro_subfamilias($canal,$codigo_familia)
{
    $this->DB2->select('DISTINCT(Subf_nombre), Subf_Id');
    $this->DB2->from('tbl_subfamilia');
    $this->DB2->where('Subf_Fa_Id', $codigo_familia);
    $this->DB2->group_by(['Subf_nombre', 'Subf_Id']); // Agrupar ambos para evitar error
    $this->DB2->order_by('Subf_nombre', 'asc'); // Opcional

    $query = $this->DB2->get();
    return $query->result_array();
}


    function filtro_unidad_medida(){
        $this->DB2->select('*');
        $this->DB2->from('tbl_unidad_medida');

        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    // query para llenar lista de reclamos admin, bodega/mercadeo ---------------------------------------------

    function query_ls_reclamos($parametros){
        $str_current_date = date('Y_m_d');
        $this->DB2->select("
            distinct(Rec_Id),Tipd_Trec_Id, 
            Tipd_descripcion, trim(GROUP_CONCAT(' ',Cat_Id)) AS Cat_Id, 
            P_nombre, Di_nombre, Dis_nombre,
            Rec_fecha_servidor, Rec_estado, 
            CONCAT(Cli_codigo, ' - ', Cli_nombre) as Cli_codigo");
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_cliente','Cli_Id = Rec_Cli_Id');
        // $this->DB2->join('tbl_usuario','Usu_Id = Rec_Usu_Id');
        $this->DB2->join('tbl_rutas','Ru_Id = Cli_Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');

        if($parametros['estado'] != '')
            $this->DB2->where('Rec_estado',$parametros['estado']);
        else        
            $this->DB2->where('Rec_estado in (1,3,5)', null, false);

        if(!empty($parametros['codigo_pais']) || $parametros['codigo_pais'] != 0)
            $this->DB2->where('P_Id',$parametros['codigo_pais']);
        
        if(!empty($parametros['codigo_division']) || $parametros['codigo_division'] != 0)
            $this->DB2->where('Di_Id',$parametros['codigo_division']);
        
        if(!empty($parametros['codigo_dis']) || $parametros['codigo_dis'] != 0)
            $this->DB2->where('Dis_Id',$parametros['codigo_dis']);

        if(!empty($parametros['codigo_ca']) || $parametros['codigo_ca'] != 0)
            $this->DB2->where('Ca_Id',$parametros['codigo_ca']);

        if(!empty($parametros['codigo_grupo']) || $parametros['codigo_grupo'] != 0)
            $this->DB2->where('Dist_Id',$parametros['codigo_grupo']);
        
        if(!empty($parametros['codigo_ruta']) || $parametros['codigo_ruta'] != 0)
            $this->DB2->where('Ru_Id', $parametros['codigo_ruta']);

        if(!empty($parametros['fecha_inicial']) && empty($parametros['fecha_limite'])){
            $this->DB2->where('CAST(Rec_fecha_servidor as date) >=', $parametros['fecha_inicial']);
            $this->DB2->where('CAST(Rec_fecha_servidor as date) <=', $str_current_date);

        }else if(!empty($parametros['fecha_inicial']) && !empty($parametros['fecha_limite'])){
            $this->DB2->where('CAST(Rec_fecha_servidor as date) >=', $parametros['fecha_inicial']);
            $this->DB2->where('CAST(Rec_fecha_servidor as date) <=', $parametros['fecha_limite']);
        }
        if(!empty($_POST['search']['value'])){
            $bus_ = $_POST['search']['value'];
            $this->DB2->where("(Rec_Id like '%".$bus_."%' or Tipd_descripcion like '%".$bus_."%'
            or Cat_Id like '%".$bus_."%' or P_nombre like '%".$bus_."%'
            or Di_nombre like '%".$bus_."%' or Dis_nombre like '%".$bus_."%' 
            or Rec_fecha_servidor like '%".$bus_."%' or Tipd_area like '%".$bus_."%'
            or Cli_codigo like '%".$bus_."%' or Cli_nombre like '%".$bus_."%')", null, false);
        }
        $this->DB2->group_by('Rec_Id');
        $this->DB2->order_by('Rec_fecha_servidor, Rec_fecha_revision','DESC');
    }

/* $parametros: parametros para obtener datos.
 *  $query: indica la query que se construye para obtener los datos
 */

    function make_datatables($parametros, $query_){  
        if ($query_ == 'lista') {
            $this->query_ls_reclamos($parametros);

        }else if($query_ == 'catalogo'){
            $this->query_catalogo_bodega($parametros);
        }
        else if($query_ == 'catalogo_bo'){
            $this->query_ls_catalogo_productos_bo($parametros);
        }
        else if($query_ == 'registro_rec'){
            $this->query_registro_reclamo($parametros);
        }

        if($_POST["length"] != -1)  
        {  
            $this->DB2->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->DB2->get();  

        return $query->result();  
    }  
 
    function get_filtered_data($parametros, $query_){  
        if ($query_ == 'lista') {
            $this->query_ls_reclamos($parametros);

        }else if($query_ == 'catalogo'){
            $this->query_catalogo_bodega($parametros);
        }
        else if($query_ == 'catalogo_bo'){
            $this->query_ls_catalogo_productos_bo($parametros);
        }
        else if($query_ == 'registro_rec'){
            $this->query_registro_reclamo($parametros);
        }
        $query = $this->DB2->get();  
        return $query->num_rows();  
    }     

    function get_all_data($parametros, $query_)  
    {  
        if ($query_ == 'lista') {
            $this->query_ls_reclamos($parametros);
        }else if($query_ == 'catalogo'){
            $this->query_catalogo_bodega($parametros);
        }else if($query_ == 'catalogo_bo'){
            $this->query_ls_catalogo_productos_bo($parametros);
        }else if($query_ == 'registro_rec'){
            $this->query_registro_reclamo($parametros);
        }

        return $this->DB2->count_all_results();  
    }   

    // Obtiene productos correspondientes a un mismo reclamo por cliente -------------------------------------------------
    function obtener_registro_reclamo($rec_Id){
        $this->DB2->select("
            CONCAT(Rec_cantidad,' ', Rec_unidad_medida) as Rec_cantidad, 
            Rec_unidades_danadas, Rec_numero_lote, Rec_fecha_vencimiento,
            Rec_fecha_servidor, Rec_Id, Cli_nombre, 
            CONCAT(Cli_codigo, ' - ', Cli_nombre) as Cliente,
            Cli_Id, Cli_direccion, Cat_Id, Cat_img, Tipd_descripcion, 
            Rec_observacion_ventas, Cat_descripcion, Fa_nombre, Subf_nombre, 
            Cat_img, Rec_foto_fecha_lote, Rec_foto_producto, Emp_carnet, 
            Emp_nombre, Ru_nombre, Ca_nombre, Dist_nombre, Dis_nombre, 
            Di_nombre, Ru_Id, P_nombre, Usu_nombre_usuario, Sup_nombre,
                CASE
                    WHEN Rec_estado = 1 THEN 'ACTIVO'
                    WHEN Rec_estado = 2 THEN 'RECHAZADO'
                    WHEN Rec_estado = 3 THEN 'REVISADO'
                    WHEN Rec_estado = 4 THEN 'FINALIZADO'
                    ELSE 'AUTORIZADO'
                END AS Rec_estado,
            Rec_img_aprobado");

       /* $this->DB2->select("
            CONCAT(Rec_cantidad,' ', Rec_unidad_medida) as Rec_cantidad, 
            Rec_unidades_danadas, Rec_numero_lote, Rec_fecha_vencimiento,
            Rec_fecha_servidor, Rec_Id, Rec_estado, Cli_nombre, Cli_Id, 
            Cli_direccion, Cat_Id, Cat_img, Tipd_descripcion, 
            Rec_observacion_ventas, Cat_descripcion, Fa_nombre, Subf_nombre, 
            Cat_img, Rec_foto_fecha_lote, Rec_foto_producto, Emp_carnet, 
            Emp_nombre, Ru_nombre, Ca_nombre, Dist_nombre, Dis_nombre, 
            Di_nombre, Ru_Id, P_nombre, Usu_nombre_usuario, Sup_nombre,
            Rec_img_aprobado");*/
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_cliente','Rec_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_empleados','Cli_Ru_Id = Emp_Ru_Id', 'left');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');        
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');        
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_rutas','Cli_Ru_Id = Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_supervisores','Dist_Sup_Id = Sup_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        $this->DB2->join('tbl_usuario','Rec_Usu_Id = Usu_Id');
        $this->DB2->where('Rec_Id',$rec_Id);
        $this->DB2->group_by('Rec_Id');
        
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    // Obtiene productos correspondientes a un mismo reclamo por cliente -------------------------------------------------
    function query_registro_reclamo($rec_Id){
        $this->DB2->select('Rec_cantidad, Rec_numero_lote, Rec_unidad_medida, Rec_fecha_vencimiento, 
                            Rec_fecha_servidor, Rec_Id, Cli_nombre, Cli_Id, Cli_direccion, Cat_Id, Cat_img, 
                            Tipd_descripcion, Rec_observacion_ventas, Cat_descripcion, Fa_nombre, Subf_nombre, Cat_img,
                            Rec_foto_fecha_lote, Rec_foto_producto, Emp_carnet, Emp_nombre, Ru_nombre,
                            Ca_nombre, Dist_nombre, Dis_nombre, Di_nombre, Ru_Id, P_nombre, Usu_nombre_usuario');
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_cliente','Rec_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_empleados','Cli_Ru_Id = Emp_Ru_Id', 'left');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');        
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');        
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_rutas','Cli_Ru_Id = Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        $this->DB2->join('tbl_usuario','Rec_Usu_Id = Usu_Id');
        $this->DB2->where('Rec_Id',$rec_Id);
        
    }
    // ------------ lista de paises para filtro de administrador ------------------------------
    function lista_paises($n_pais){
        $this->DB2->select('P_Id, P_nombre');
        $this->DB2->from('tbl_pais');
        if ($n_pais != -1) {
            $this->DB2->where('P_nombre', $n_pais);
        }
                        
        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function lista_division($codigo_pais, $id_division){
        $this->DB2->select('Di_Id, Di_nombre');
        $this->DB2->from('tbl_division');
        $this->DB2->where('Di_P_Id', $codigo_pais);
        $this->DB2->where('Di_estado', 1);
        
        if ($id_division != 0 && $id_division != '')
            $this->DB2->where('Di_Id', $id_division);

        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
     // ---- Carga de distribuidoras para filtro ----------------------------------------------
    function lista_distribuidoras($codigo_division,$estado_p){
        $this->DB2->select('Dis_Id, Dis_nombre');
        $this->DB2->from('tbl_distribuidora');
        $this->DB2->where('Dis_Di_Id', $codigo_division);
        // if(!$estado_p){
        //     $this->DB2->where('Dis_Di_Id', $codigo_division);
        // }else{
        //     $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        //     $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        //     $this->DB2->where('P_Id', $this->session->userdata('id_pais'));
        // }
        $this->DB2->where('Dis_estado', 1);
        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    
    // lista de distribuidoras / clientes para usuarios de paises terceros ----------------------
    function lista_distribuidoras_pt($pais){
        $this->DB2->select('Cli_nombre, Cli_Id, Cli_Ru_Id');
        $this->DB2->from('erp_sdv_bocadeli.tbl_cliente');
        $this->DB2->join('tbl_rutas','Ru_Id = Cli_Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        $this->DB2->where('P_Id', $pais);
        $this->DB2->where('Dis_estado', 1);
        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    // -------------------------------------------------------------------------------------------
    function lista_canal($codigo_dist){
        $this->DB2->select('Ca_Id, Ca_nombre');
        $this->DB2->from('tbl_canal');
        $this->DB2->where('Ca_Dis_Id', $codigo_dist);
        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    
    function lista_grupo($codigo_ca){
        $this->DB2->select('Dist_Id, Dist_nombre');
        $this->DB2->from('tbl_distrito');        
        $this->DB2->where('Dist_Ca_Id', $codigo_ca); 
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    
    function lista_rutas($codigo_grupo){
        $this->DB2->select('Ru_Id, Ru_nombre');
        $this->DB2->from('tbl_rutas');
        $this->DB2->where('Ru_Dist_Id', $codigo_grupo);
        $this->DB2->where('Ru_estado', 1);
        //$this->DB2->where('Ru_Id !=', 3); 
        $query = $this->DB2->get();
        $resultado = $query->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function guardar_img_reclamo($codigo_reclamo, $img){
        $this->DB2->set('Rec_img_aprobado', $img);
        $this->DB2->set('Rec_estado', 0);
        $this->DB2->where('Rec_Id',$codigo_reclamo);
        $this->DB2->update('tbl_reclamo_pfn');
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }

    function get_rec_x_tipo(){
        
        $this->DB2->select('Tipd_descripcion, P_nombre, Di_nombre, Dis_nombre, count(*) as Cantidad');
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_usuario','Usu_Id = Rec_Usu_Id');
        $this->DB2->join('tbl_rutas','Ru_Id = Usu_Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        $this->DB2->where('Rec_estado', 1);
        $this->DB2->group_by('Rec_Id');
        $this->DB2->order_by('Tipd_descripcion, Dis_nombre');
        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    // query para llenar catalogo de productos para usuario bodega/mercado ---------------------------------------------
    function query_catalogo_bodega($canal){
        $this->DB2->select('tbl_catalogo_productos.*, Um_nombre, Fa_nombre, Subf_nombre, Catx_estado');
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos','Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $canal['canal']);
        // $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->where('Catx_estado', 1);
        $this->DB2->where('Fa_nombre !=', 'EXHIBIDOR');
        if(!empty($_POST['search']['value'])){
            $bus_ = $_POST['search']['value'];
            $this->DB2->where("(Cat_Id like '%".$bus_."%' or Cat_descripcion like '%".$bus_."%'
            or Um_nombre like '%".$bus_."%' or Fa_nombre like '%".$bus_."%'
            or Subf_nombre like '%".$bus_."%')", null, false);
        }
    }

    function get_cliente_bodega_mercadeo($ruta){
        $this->DB2->select('Cli_Id, Cli_codigo, Cli_nombre, Cli_Ru_Id, Di_nombre, Ru_nombre, Ru_Id');
        $this->DB2->from('tbl_cliente');
        $this->DB2->join('tbl_rutas','Cli_Ru_Id = Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        $this->DB2->where('Cli_Ru_Id', $ruta);
        $this->DB2->where('Cli_estado', 1);
        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    function get_reclamos_enviados($idRuta){
        $this->DB2->select('Rec_Id, Cli_codigo, Rec_Cat_Id, Tipd_descripcion, Rec_cantidad, Rec_unidad_medida, Cli_codigo');
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_cliente','Rec_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_usuario','Usu_Id = Rec_Usu_Id');
        $this->DB2->join('tbl_rutas','Ru_Id = Usu_Ru_Id');
        $this->DB2->where('Cli_Ru_Id', $idRuta);
        $this->DB2->where('Rec_estado', 1);
        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    public function verificar_reclamo($data)
    {
        $this->db->select('*');
        $this->db->from('tbl_reclamo_pfn');
        $this->db->where('Rec_token', $data);
        return $this->db->count_all_results() > 0 ? true : false;
    }

    function update_comentario_rec($parametros){
        $this->DB2->set('Rec_observacion_ventas', $parametros['txtComentario']);
        // $this->DB2->set('Rec_estado', 0);
        $this->DB2->where('Rec_Id', $parametros['rec_id']);
        $this->DB2->where('Rec_Cat_Id', $parametros['cat_id']);
        $this->DB2->update('tbl_reclamo_pfn');
        return $this->DB2->affected_rows() > 0 ? true : false;
        
    }
// QUERY PARA EXPORTAR DATOS DE RECLAMOS ----------------------------------------------------------------
    function get_all_reclamos_xlsx($parametros){
        $this->DB2->select("Rec_Id AS NUMERO_RECLAMO, 
            Tipd_descripcion AS TIPO_RECLAMO,
            Rec_Cat_Id AS CODIGO_PRODUCTO, 
            Cat_descripcion AS PRODUCTO,
            CONCAT(Rec_cantidad, ' ', Rec_unidad_medida) AS CANTIDAD_A_ENTREGAR,
            Fa_nombre AS FAMILIA_PRODUCTO,
            Subf_nombre AS SUBFAMILIA_PRODUCTO,
            Rec_fecha_vencimiento AS FECHA_VENCIMIENTO, 
            Rec_numero_lote AS NUMERO_LOTE, 
            Rec_unidades_danadas AS UNIDADES_DANADAS,
            Cli_codigo as CODIGO_CLIENTE,
            Cli_nombre as NOMBRE_CLIENTE,     
            CASE
                WHEN Tipd_area = 1 THEN 'VENTAS / MERCADEO'
                ELSE 'BODEGA / CALIDAD'
            END AS AREA, 
            Rec_fecha_servidor AS FECHA_RECLAMO, 
            Rec_token AS TOKEN, 
            Rec_observacion AS OBSERVACION, 
            Rec_observacion_ventas AS OBSERVACION_VENTAS, 
            CASE
                WHEN Rec_estado = 1 THEN 'ACTIVO'
                WHEN Rec_estado = 2 THEN 'RECHAZADO'
                WHEN Rec_estado = 3 THEN 'REVISADO'
                WHEN Rec_estado = 4 THEN 'FINALIZADO'
                ELSE 'PROCESADO'
            END AS ESTADO,
            P_nombre as PAIS, 
            Di_nombre as DIVISION, 
            Dis_nombre as DISTRIBUIDORA,
            Ru_nombre AS RUTA");
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_usuario','Usu_Id = Rec_Usu_Id');
        $this->DB2->join('tbl_rutas','Ru_Id = Usu_Ru_Id');
        $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
        $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
        $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
        $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
        $this->DB2->join('tbl_cliente','Rec_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        // $this->DB2->where('Rec_estado', 1);
        // $this->DB2->where('P_Id',$parametros['codigo_pais']);
        // $this->DB2->where('Di_Id',$parametros['codigo_division']);
        if($parametros['estado'] != '')
            $this->DB2->where('Rec_estado',$parametros['estado']);
        
        if(!empty($parametros['codigo_pais']) || $parametros['codigo_pais'] != 0)
            $this->DB2->where('P_Id',$parametros['codigo_pais']);

        if(!empty($parametros['codigo_division']) || $parametros['codigo_division'] != 0)
            $this->DB2->where('Di_Id',$parametros['codigo_division']);

        if(!empty($parametros['codigo_dis']) || $parametros['codigo_dis'] != 0)
            $this->DB2->where('Dis_Id',$parametros['codigo_dis']);

        if(!empty($parametros['codigo_ca']) || $parametros['codigo_ca'] != 0)
            $this->DB2->where('Ca_Id',$parametros['codigo_ca']);

        if(!empty($parametros['codigo_grupo']) || $parametros['codigo_grupo'] != 0)
            $this->DB2->where('Dist_Id',$parametros['codigo_grupo']);
        
        if(!empty($parametros['codigo_ruta']) || $parametros['codigo_ruta'] != 0)
            $this->DB2->where('Ru_Id', $parametros['codigo_ruta']);

        if(!empty($parametros['fecha_inicial']) && empty($parametros['fecha_limite'])){
            $this->DB2->where('CAST(Rec_fecha_servidor as date) >=', $parametros['fecha_inicial']);
            $this->DB2->where('CAST(Rec_fecha_servidor as date) <=', 'current_date()');
        }
        else if(!empty($parametros['fecha_inicial']) && !empty($parametros['fecha_limite'])){
            $this->DB2->where('CAST(Rec_fecha_servidor as date) >=', $parametros['fecha_inicial']);
            $this->DB2->where('CAST(Rec_fecha_servidor as date) <=', $parametros['fecha_limite']);
        }

        $this->DB2->group_by('Rec_Id');
        $this->DB2->order_by('Rec_fecha_servidor','DESC');
        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }




function get_reclamos_listado_inicial($credenciales = array())
{
    $fecha_inicial = date('Y-01-01');
    $fecha_limite  = date('Y-m-d');

    $privilegio = isset($credenciales['privilegio']) ? intval($credenciales['privilegio']) : 0;
    $us_cod     = isset($credenciales['us_cod']) ? intval($credenciales['us_cod']) : 0;

    $privilegiosPermitidos = array(1,8,10,11,12,13,14,15);

    $this->DB2->select("Rec_Id AS NUMERO_RECLAMO, 
        Tipd_descripcion AS TIPO_RECLAMO,
        Rec_Cat_Id AS CODIGO_PRODUCTO, 
        Cat_descripcion AS PRODUCTO,
        CONCAT(Rec_cantidad, ' ', Rec_unidad_medida) AS CANTIDAD_A_ENTREGAR,
        Fa_nombre AS FAMILIA_PRODUCTO,
        Subf_nombre AS SUBFAMILIA_PRODUCTO,
        Rec_fecha_vencimiento AS FECHA_VENCIMIENTO, 
        Rec_numero_lote AS NUMERO_LOTE, 
        Rec_unidades_danadas AS UNIDADES_DANADAS,
        Cli_codigo AS CODIGO_CLIENTE,
        Cli_nombre AS NOMBRE_CLIENTE,
        'BODEGA / CALIDAD' AS AREA,
        Rec_fecha_servidor AS FECHA_RECLAMO, 
        Rec_token AS TOKEN, 
        Rec_observacion AS OBSERVACION, 
        Rec_observacion_ventas AS OBSERVACION_VENTAS, 
        CASE
            WHEN Rec_estado = 1 THEN 'ACTIVO'
            WHEN Rec_estado = 2 THEN 'RECHAZADO'
            WHEN Rec_estado = 3 THEN 'EN REVISION'
            WHEN Rec_estado = 4 THEN 'FINALIZADO'
            ELSE 'PROCESADO'
        END AS ESTADO,
        P_nombre AS PAIS, 
        Di_nombre AS DIVISION, 
        Dis_nombre AS DISTRIBUIDORA,
        Ru_nombre AS RUTA");

    $this->DB2->from('tbl_reclamo_pfn');
    $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
    $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
    $this->DB2->join('tbl_usuario','Usu_Id = Rec_Usu_Id');
    $this->DB2->join('tbl_rutas','Ru_Id = Usu_Ru_Id');
    $this->DB2->join('tbl_distrito','Ru_Dist_Id = Dist_Id');
    $this->DB2->join('tbl_canal','Dist_Ca_Id = Ca_Id');
    $this->DB2->join('tbl_distribuidora','Ca_Dis_Id = Dis_Id');
    $this->DB2->join('tbl_division','Dis_Di_Id = Di_Id');
    $this->DB2->join('tbl_pais','Di_P_Id = P_Id');
    $this->DB2->join('tbl_cliente','Rec_Cli_Id = Cli_Id');
    $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
    $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');

    $this->DB2->where('DATE(Rec_fecha_servidor) >=', $fecha_inicial);
    $this->DB2->where('DATE(Rec_fecha_servidor) <=', $fecha_limite);

    if (!in_array($privilegio, $privilegiosPermitidos)) {
        $this->DB2->where('Rec_Usu_Id', $us_cod);
    }

    $this->DB2->group_by('Rec_Id');
    $this->DB2->order_by('Rec_fecha_servidor','DESC');

    $query = $this->DB2->get();

    return $query->result();
}


// ------------------------------------------------------------------------------------------------------

// CATALOGO PARA MANTENIMIENTO DE PRODUCTOS -------------------------------------------------------------
    /*function query_ls_catalogo_productos_bo($parametros){
        $this->DB2->select('tbl_catalogo_productos.*, Um_nombre, Um_Id,
            Fa_nombre, Fa_Id, Subf_Id, Subf_nombre, Catx_estado');
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos','Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $parametros['canal']);
        // $this->DB2->where('Catx_estado', 1);
        $this->DB2->where('Fa_nombre !=', 'EXHIBIDOR');
        
        if(!empty($parametros['codigo_producto']) || $parametros['codigo_producto'] != 0)
            $this->DB2->where('Cat_Id',$parametros['codigo_producto']);

        if(!empty($parametros['subfamilia']) || $parametros['subfamilia'] != 0)
            $this->DB2->where('Cat_Subf_Id',$parametros['subfamilia']);

        if(!empty($parametros['familia']) || $parametros['familia'] != 0)
            $this->DB2->where('Subf_Fa_Id',$parametros['subfamilia']);

        if(!empty($parametros['estado']) || $parametros['estado'] != 0)
            $this->DB2->where('Catx_estado',$parametros['estado']);

        if(!empty($_POST['search']['value'])){
            $bus_ = $_POST['search']['value'];
            $this->DB2->where("(Cat_Id like '%".$bus_."%' or Cat_descripcion like '%".$bus_."%'
            or Um_nombre like '%".$bus_."%' or Fa_nombre like '%".$bus_."%'
            or Subf_nombre like '%".$bus_."%')", null, false);
           
        }
       
    }*/

    function query_ls_catalogo_productos_bo($parametros){
        $this->DB2->select("*,
            @sq := (         
                select 
                    case count(*) 
                        WHEN 0 THEN 0
                        ELSE 1  
                    end as v
                FROM
                    tbl_catalogopro_x_canal AS d
                        INNER JOIN
                    tbl_canal ON Ca_Id = Catx_Ca_Id
                        LEFT JOIN
                    tbl_distribuidora ON Dis_Id = Ca_Dis_Id
                        LEFT JOIN
                    tbl_division ON Di_Id = Dis_Di_Id
                        LEFT JOIN
                    tbl_pais ON Di_P_Id = P_Id
                WHERE
                   P_Id = ".$parametros['pais']." AND d.Catx_Cat_Id = Cat_Id
            ) as Catx_estado");
        $this->DB2->from('erp_sdv_bocadeli.tbl_catalogo_productos');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id','left');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id','left');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id','left');
        $this->DB2->group_by('Cat_Id');  
        $this->DB2->order_by('Fa_Id', 'ASC');  
        $this->DB2->order_by('Catx_estado', 'DESC');  
        if(!empty($_POST['search']['value'])){
            $bus_ = $_POST['search']['value'];
            $this->DB2->where("(Cat_Id like '%".$bus_."%' or Cat_descripcion like '%".$bus_."%'
            or Um_nombre like '%".$bus_."%' or Fa_nombre like '%".$bus_."%'
            or Subf_nombre like '%".$bus_."%')", null, false);
        } 
    }
          
    
// ------------------------------------------------------------------------------------------------------

// VERIFICAR CODIGO DE PRODUCTO -------------------------------------------------------------------------
    function query_verificar_producto($parametros){
        $this->DB2->select('tbl_catalogo_productos.*, Um_nombre, Fa_nombre, Subf_nombre, Catx_estado');
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos','Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $parametros['canal']);
        $this->DB2->where('Fa_nombre !=', 'EXHIBIDOR');
        // $this->DB2->where('Cat_Id =', $parametros['codigo']);
        
        if(!empty($parametros['codigo']) || $parametros['codigo'] != 0){
            $this->DB2->where('Cat_Id =', $parametros['codigo']);
        }
        if(!empty($parametros['codigo_buscar']) || $parametros['codigo_buscar'] != 0){
            $bus_ = $parametros['codigo_buscar'];
            $this->DB2->where("(Cat_Id like '%".$bus_."%')", null, false);
        }

        return $this->DB2->count_all_results() > 0 ? true : false;      
    }

    function verificar_catalogo_producto_bo($canal, $codigo){
        $this->DB2->select('tbl_catalogo_productos.*, Um_nombre, Fa_nombre, Subf_nombre, Catx_estado');
        $this->DB2->from('tbl_catalogopro_x_canal');
        $this->DB2->join('tbl_catalogo_productos','Cat_Id = Catx_Cat_Id');
        $this->DB2->join('tbl_unidad_medida','Cat_Um_Id = Um_Id');
        $this->DB2->join('tbl_subfamilia','Cat_Subf_Id = Subf_Id');
        $this->DB2->join('tbl_familia','Subf_Fa_Id = Fa_Id');
        $this->DB2->where('Catx_Ca_Id', $canal);
        $this->DB2->where('Fa_nombre !=', 'EXHIBIDOR');
        $this->DB2->where('Cat_Id =', $codigo);

        return $this->DB2->count_all_results() > 0 ? true : false;
       
    }
// ------------------------------------------------------------------------------------------------------


    public function guardar_producto_catalogo_bo($data,$opcion){
        if($opcion == 1){
            $this->DB2->insert('tbl_catalogo_productos', $data);
        }else{
            $this->DB2->set('Cat_descripcion', $data['Cat_descripcion']);
            $this->DB2->set('Cat_Um_Id', $data['Cat_Um_Id']);
            $this->DB2->set('Cat_Subf_Id', $data['Cat_Subf_Id']);

            // if ($data['Cat_img'] != '' && $data['Cat_img'] != null) {
            //     $this->DB2->set('Cat_img', $data['Cat_img']);
            // }
            $this->DB2->where('Cat_Id',$data['Cat_Id']);
            $this->DB2->update('tbl_catalogo_productos');
        }

        if($this->DB2->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editar_producto_catalogo_bo($data){
        $this->DB2->set('Cat_descripcion', $data['Cat_descripcion']);
        $this->DB2->set('Cat_Um_Id', $data['Cat_Um_Id']);
        $this->DB2->set('Cat_Subf_Id', $data['Cat_Subf_Id']);

        if($data['Cat_img'] != ''){
            $this->DB2->set('Cat_img', $data['Cat_img']);
        }
        $this->DB2->where('Cat_Id',$data['Cat_Id']);
        $this->DB2->update('tbl_catalogo_productos');
          if($this->DB2->affected_rows() > 0 ){
            return true;
          }else{
            return false;
          }
            
    }

    public function asignar_canal_catalogo_bo($data,$opcion){
        if ($opcion == 1) {
            $this->DB2->insert('tbl_catalogopro_x_canal', $data);
        }else{
            $this->DB2->set('Catx_estado', $data['Catx_estado']);
            $this->DB2->where('Catx_Cat_Id',$data['Catx_Cat_Id']);
            $this->DB2->where('Catx_Ca_Id',$data['Catx_Ca_Id']);
            $this->DB2->update('tbl_catalogopro_x_canal');
        }

        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }

    function rechazar_reclamo($codigo_reclamo){
        $this->DB2->set('Rec_estado', 2);
        $this->DB2->where('Rec_Id',$codigo_reclamo);
        $this->DB2->update('tbl_reclamo_pfn');
          if($this->DB2->affected_rows() > 0 ){
            return true;
          }else{
            return false;
          }
    }

    // ------------------------------------------------------------------------------------------------------------
    public function verificar_regitro($data, $tabla)
    {
        $this->DB2->select('*');
        $this->DB2->from($tabla);
        $this->DB2->where($data);
        return $this->DB2->count_all_results() > 0 ? true : false;

    }
    // ------------------------------------------------------------------------------------------------------------
    // ------------------------------------------------------------------------------------------------------------
    public function editar_registro($data_set, $data_where, $tabla)
    {
        $this->DB2->set($data_set);
        $this->DB2->where($data_where);
        $this->DB2->update($tabla);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
        
    }
    // ------------------------------------------------------------------------------------------------------------
    // ------------------------------------------------------------------------------------------------------------
    public function agregar_registro($tabla, $data )
    {
        $this->DB2->insert($tabla, $data);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    // ------------------------------------------------------------------------------------------------------------

    // 29/09/2021 -------------------------------------------------------------------------------------------------
    function lista_canal_asignar($division, $codigo){
        $query_sql = "select Catx_estado AS Estado, Di_nombre, Ca_nombre, Ca_Id, Dis_nombre, Dis_Id 
            from tbl_canal
            inner join tbl_distribuidora on Ca_Dis_Id = Dis_Id
            inner join tbl_division on Dis_Di_Id = Di_Id
            inner join tbl_catalogopro_x_canal on Catx_Ca_Id = Ca_Id
            Where Di_Id = ".$division." and Catx_Cat_Id= '".$codigo."'             
            UNION ALL
            select '0' as Estado, Di_nombre, Ca_nombre, Ca_Id, Dis_nombre, Dis_Id 
            from tbl_canal
            inner join tbl_distribuidora on Ca_Dis_Id = Dis_Id
            inner join tbl_division on Dis_Di_Id = Di_Id
            Where Di_Id = ".$division." 
            and NOT EXISTS (Select Catx_Ca_Id from tbl_catalogopro_x_canal 
            WHERE tbl_canal.Ca_Id = tbl_catalogopro_x_canal.Catx_Ca_Id 
            and Catx_Cat_Id= '".$codigo."' );";
            $resultado = $this->DB2->query($query_sql);
            $resultado = $resultado->result();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }

    public function agregar_registros($tabla, $data )
    {
        $this->DB2->insert_batch($tabla, $data);
        if($this->DB2->affected_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }
    // FIN 29/09/2021 -----------------------------------------------------------------------------------

    // 01/10/2021 ---------------------------------------------------------------------------------------
    function ls_distribuidoras_canales($codigo_division){
        $this->DB2->select('Dis_Id, Dis_nombre, Ca_Id, Ca_nombre');
        $this->DB2->from('tbl_canal');
        $this->DB2->join('tbl_distribuidora', 'Ca_Dis_Id = Dis_Id');
        $this->DB2->join('tbl_division', 'Dis_Di_Id = Di_Id');
        $this->DB2->where('Dis_Di_Id', $codigo_division);     
        $this->DB2->where('Dis_estado', 1);
        $query = $this->DB2->get();
        $resultado = $query->result_array();
        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    // FIN 01/10/2021 -----------------------------------------------------------------------------------

    // -- 05/10/2021 ------------------------------------------------------------------------------------
    public function ls_reclamos_enproceso($ruta)
    {
        $this->DB2->select("
            Rec_Cat_Id as Id_Catalogo_Producto, 
            Rec_Cli_Id as Id_Cliente, 
            Cli_codigo as codigo_cliente,
            Rec_cantidad as cantidad, 
            Rec_Id as codigo_reclamo, 
            Cat_descripcion as descripcion_producto,
            Rec_estado as estado,
            Rec_fecha_telefono as fecha_telefono,
            Rec_fecha_vencimiento as fecha_vencimiento,
            Rec_foto_fecha_lote as fileFechaLote,
            Rec_foto_producto as fileProducto,
            Cli_nombre as nombre_cliente,
            Ru_nombre as nombre_ruta,
            Rec_numero_lote as numeroLote,
            'NO' as pendiente,
            Tipd_Id as tipo_reclamo, 
            Tipd_descripcion as tipo_reclamo_descripcion,
            Rec_tipo_usuario as tipo_usuario,
            Rec_token as token_reclamo,
            Rec_unidades_danadas as unidades_danadas,
            Rec_Usu_Id as usuario
        ");
        $this->DB2->from('tbl_reclamo_pfn');
        $this->DB2->join('tbl_tipo_danos','Rec_Tipd_Id = Tipd_Id');
        $this->DB2->join('tbl_catalogo_productos','Rec_Cat_Id = Cat_Id');
        $this->DB2->join('tbl_cliente','Rec_Cli_Id = Cli_Id');
        $this->DB2->join('tbl_usuario','Usu_Id = Rec_Usu_Id');
        $this->DB2->join('tbl_rutas','Ru_Id = Usu_Ru_Id');
        $this->DB2->where('Rec_estado', 1);        
        // $this->DB2->where('Rec_Usu_Id', $usuario);        
        $this->DB2->where('Cli_Ru_id', $ruta);        
        $this->DB2->group_by('Rec_Id');
        $this->DB2->order_by('Rec_fecha_servidor','DESC');
        $query = $this->DB2->get();
        $resultado = $query->result();

        if(!empty($resultado)){
            return $resultado;
        }else{
            return array();
        }
    }
    // --------------------------------------------------------------------------------------------------
    public function ListaProveedores(){
        $this->DB2->select('Pr_Id codbx,Pr_nombre valor');
        $this->DB2->from('tbl_proveedores');
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


