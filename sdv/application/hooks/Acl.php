<?php
defined("BASEPATH") or die("Acceso prohibido");
 
class Acl
{
 
 /**
 * @desc - obtenemos la instancia de ci
 */
 public function __get($var)
    {
        return get_instance()->$var;
    }
 
    /**
 * @desc - obtenemos la instancia de ci sin tener que crearla
 */
    public function __construct()
    {   
        !$this->load->library('session') ? $this->load->library('session') : false;
        !$this->load->helper('url') ? $this->load->helper('url') : false;
        //!$this->load->model('M_usuarios/Mdl_login','lg') ? $this->load->model('M_usuarios/Mdl_login','lg') : false;

    }
 
    /**
 * @desc - devuelve un array con los roles y las zonas de acceso
 * @return array
 */
    private function roles_access()
    {
        return array(
 
        /*
            ----------------------------
            USUARIOS PRIVILEGIOS
            ----------------------------
        */
            '$vend0r01' => array(
                'login',
                'clientes',
                'carga-inicial',
                'salir',
                'agregar-cliente-ok',
                'carga-municipios',
                'carga-gironegocio',
                'sincronizacion',
                'comprobarconexion',
                'comprobarconec',
                'menu',
                'act-coordenadas',
                'lista-clientes-ruta',
                'lista-clientes-ruta_ac',
                'actu-cliente',
                'totales_actu_cuadro'
            ),
            '#supe1.$0' => array(
                'login-admin',
                'aprobacion-clientes',
                'lista-clientes-s',
                'modificar-clientes',
                'resolucion-cliente',
                'lista-duplicados-s',
                'editar-cliente-show',
                'mostrar-municipios',
                'mostrar-gironegocio',
                'zoom-lista-duplicados'
            ),
            '#ad$3#$$.' => array(
                'login-admin',
                'reportes',
                'salir',
                'generar-plantilla',
                'comprobarconec',
                't-clientes',
                'login-admin',
                'generar-plantilla-c',
                'mostrar-distribuidoras',
                'count-plantilla',
                'count-consulta',
                'total_clientes_plantillaco',
                'count-plantilla-actu',
                'lista-actualizados',
                'list-bitacora',
                'mostrar-rutas',
                'lista-duplicados-apr',
                'resolucion-cliente_anl',
                't-clientes_apr-anl',
                'actucontrasena',
                't-aprobados',
                'descarga-actualizados'
            ),
            'afuera' => array(
                'login',
                'salir',
                'comprobarconec',
                'clientes',
                'menu',
                'login-admin',
                'reportesd',
                'migrar',
                'migrartotal',
                'cargarmigrar',
                'actuplantilla',
                'editar',
                'migrartotaleditar',
                'cargarmigrareditar',
                'salvarexhibidores',
                'act-coordenadas'

            ),
            '' => array('login','salir','clientes','login-admin'),
        );
    }
 
    /**
    * @desc - por defecto, si no existe la sesión de usuario es afuera
    * @return - string - sesión por defecto
    */
    private function _defaultRole()
    {  
 
        if ( $this->session->userdata("role_pks") ){
             $this->session->userdata("role_pks");
        }else{
             $this->session->set_userdata("role_pks","afuera");
        }
        return $this->session->userdata("role_pks");
    }
 
    /**
 * @desc - comprobamos si el usuario tiene acceso a una zona,
 * si no lo tiene lo dejamos en la primera de su rol con un mensaje
 */
    public function auth()
    {
        $this->_defaultRole();
 
        foreach($this->roles_access() as $role => $areas)
        {
                 if($this->session->userdata("role_pks") == $role )
                 {
                 
                         if(!in_array($this->uri->segment(1),$areas))
                         {
                                
                                if(empty($this->uri->segment(1))){
                                    
                                }else{
                                    
                                    $nombreusuario="";
                                    $rolepks="";
                                 
                                    $data = array(
                                    'codusuario' => FALSE,
                                    'nombrecompleto' => $nombreusuario,
                                    'role_pks' => $rolepks
                                    ); 
                                    $this->session->set_userdata($data);
                                    $this->session->unset_userdata($data);
                                    $this->session->sess_destroy();
                                    $this->session->set_flashdata('cuentaok', array('info' => 'Acceso denegado,inicia sesi&oacute;n con tu cuenta.', 'cla' => 'danger grDanguer', 'ttmjs' => '¡Informaci&oacute;n importante!'));
                                    redirect('../',"refresh");
                                }
                                  
                               
                            
                         }
                              
                    
                 }
        }
    }
}
//Hooks