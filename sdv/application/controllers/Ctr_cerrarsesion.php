<?php
header("Access-Control-Allow-Origin: *");
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
class Ctr_cerrarsesion extends ControladorBase
{
	public function __construct(){
        parent::__construct();
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));

    }

	// public function index(){
 //        //$this->global['pageTitle'] = 'Cerrar Sesion';
 //        //$this->loadViewsEncuestas('encuestas/V_entrega_exhibidores',$this->global);

 //        // $nombreusuario="";
 //        // $rolepks="";
                                 
 //        // $data = array(
 //        //     'codusuario' => '',
 //        //     'nombrecompleto' => '',
 //        //     'role_pks' => '',
 //        //     'nomruta' => '',
 //        //     'fechahoy' => ''
 //        // );
 //        // $this->session->set_userdata($data);
 //        // $this->session->unset_userdata($data);
 //        // session_destroy();
 //        header("Location: ../");

 //  	}

    public function cerrar_session_admin_pfn(){
        $data = array(
            'codusuario' => '',
            'nombrecompleto' => '',
            'nomruta' => '',
            'codRuta' => '',
            'fechahoy' => '',
            'idsupervisor' => '',    
            'pais' => '',
            'listdistribuidora' => '',
            'usuario' => '',
            'tipousuario' => '',
            'tipocuentaus' => '',
            'id_canal' => '',
            'id_privilegio' => '',
            'id_distribuidora' => ''
        );
        $this->session->set_userdata($data);
        $this->session->unset_userdata($data);
        if(session_destroy()){
            echo json_encode(array(
                'rs'    => TRUE,
                'info'  => 'SESION TERMINADA'
            ));
        }
        // header("Location: ../sdv/");
    }

}