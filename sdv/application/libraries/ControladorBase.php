<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 
/**
 * Class : ControladorBase
 * Clase para controlar clases
 * @author : GR systems
 * @version : 1
 * @since : 28 Septiembre 2018
 */
class ControladorBase extends CI_Controller {
	protected $global = array ();
	protected $view_folder ='';
	protected $name_controller = '';
	protected $run_mode = '';
	public function __construct($modulo = '') {
		parent::__construct();
		$this->config->load('app',TRUE);
		$this->global["app_name"] = $this->config->item('app_name','app');
		$this->global["app_name_html"] = $this->config->item('app_name_html','app');
		$this->global["app_name_min"] = $this->config->item('app_name_min','app');
		$this->global["app_theme"] = $this->config->item('app_theme','app');
	}
	/**
     * Esta función se usa para cargar vistas
     * @param {string} $viewName : Este es el nombre de vista
     * @param {mixed} $headerInfo : Esta es una matriz de información de encabezado
     * @param {mixed} $pageInfo : Esta es una matriz de información de página
     * @param {mixed} $footerInfo : Esta es una matriz de información de pie de página
     * @return {null} $result : null
     */
    protected function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer', $footerInfo);
    }
    protected function loadViews_gerencia($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header_gerencia', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer_anl', $footerInfo);
    }
    protected function loadViews_login($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header_login', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer_login', $footerInfo);
    }

    protected function loadViews_login_reportes($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header_login_reportes', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer_login_reportes', $footerInfo);
    }
    protected function loadViews_login_ad($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header_login_admin', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer', $footerInfo);
    }

    protected function loadViews_admin($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header_admin', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer_anl', $footerInfo);
    }

    protected function loadViews_bodega($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header_bodega', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer_anl', $footerInfo);
    }

    protected function loadViews_admin_anl($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/header_anl', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer_anl', $footerInfo);
    }

    protected function loadViews_nada($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('layout/headernada', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('layout/footer', $footerInfo);
    }

	public function pageNotFound(){
        $this->global['pageTitle'] = $this->global['app_name'].' : 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}