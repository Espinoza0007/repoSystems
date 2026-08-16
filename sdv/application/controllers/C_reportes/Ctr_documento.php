<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
require_once 'dompdf/autoload.inc.php';
require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');
use Dompdf\Dompdf;
$dompdf = new Dompdf();
class Ctr_documento extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_clientes/Mdl_clientes','cl');
        $this->load->model('M_clientes/Mdl_listado','ls');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');


    }

	function index(){

        // $data['lista_ocupacion'] = $this->us->ocupaciones();
        // $data['lista_privilegio'] = $this->us->privilegios();
        $this->global['pageTitle'] = 'Generar Plantilla';
        $this->loadViews('Reportes/V_plantilla',$this->global);
       
  	}

    function documentook(){


if($this->input->is_ajax_request()){
    //<div style="page-break-after:always;"></div>

  // <div id="content">
  //   <p>the first page</p>
  //   <p style="page-break-before: always;">the second page</p>
  // </div>
        $codigogenerado=numero_aleatorio(12);
        $codigogenerados=numero_aleatorio(13);
        $codigoHTML="";
        $carpeta = "temporal99/"."temporal99".$codigogenerados.$codigogenerado;


        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }


        $codigoHTML = '<html>
<head>
  <style>

    @page { margin: 100px 50px; }
    #header { position: fixed; left: 0px; top: -100px; right: 0px; height: 125px;text-align: center; }
    .logo{
        opacity:0.6;
        height:120px;
    }

    #titulo{
   
        height:20px;
        margin-top:8px;
        color:#fff;
        font-weight:bold;
    }
    #tabla-documento{
        margin:0 auto;
        width:100%;
        margin-top:-5px;
        margin-left:-20px;
    }


#titulo-principal{
background-color:#83B3FF;
height:37px;
text-align:center;
position:absolute;
margin-top:50px;

}
  </style>


<body>

  <div id="header">
    <table id="tabla-documento">
        <tr>
            <td width="15%">
                <img src="dependencias/imagenes/formulario.jpg" class="logo">
            </td>
            <td width="85%">
                <div id="titulo-principal">
                    <div id="titulo">
                        SISTEMAS DE VENTAS / BOCADELI EL SALVADOR
                    </div>
                </div>
            </td>
        </tr>
    </table>
  </div>


 <div id="content">
    <p>

    </p>
    <p style="page-break-before: always;">
        Por las razón o razones que a continuación detallo: _____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
    </p>
  </div>

</body>
</html>';

        $dompdf = new Dompdf();
        $dompdf->set_paper('letter', 'portrait');
        //$dompdf->set_paper('letter', 'landscape');

        $dompdf->load_html($codigoHTML);
        $dompdf->render();
        file_put_contents($carpeta.'/doc.pdf', $dompdf->output());

        $csrftokename = $this->security->get_csrf_token_name();
        $csrfhash = $this->security->get_csrf_hash();

        echo json_encode(array(
            'rs' => TRUE,
            'info' => 'Documento Generado Sastifactoriamente.',
            'cla' => 'success grSuccess',
            'archivo' => $carpeta.'/doc.pdf',
            'csrftokename' => $csrftokename,
            'csrfhash' => $csrfhash
            )
        );
        return;  

    
        }

    }

    



}///CIERRE DE CLASE DE CODEIGNITER



  function eliminarDir($carpeta)
{
    foreach(glob($carpeta . "/*") as $archivos_carpeta)
    {
        echo $archivos_carpeta;
 
        if (is_dir($archivos_carpeta))
        {
            eliminarDir($archivos_carpeta);
        }
        else
        {
            unlink($archivos_carpeta);
        }
    }
 
    rmdir($carpeta);
}


class concat_pdf extends FPDI 
{

    var $files = array();

    function setFiles($files) 
    {
    $this->files = $files;
    }

    function concat() 
    {

        //if (is_array($this->files)) {
        foreach($this->files AS $file) 
        {
        $pagecount = $this->setSourceFile($file);
        for ($i = 1; $i <= $pagecount; $i++) 
        {
        $tplidx = $this->ImportPage($i);
        $s = $this->getTemplatesize($tplidx);
        $this->AddPage($s['h'] > $s['w'] ? 'P' : 'L');
        $this->useTemplate($tplidx);
        }
        }

        //}//fincomprobacion


    }
}

?>