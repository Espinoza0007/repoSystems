<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/ControladorBase.php';
date_default_timezone_set('America/El_Salvador');
ini_set('max_execution_time', 0);
class Ctr_marcaciones extends ControladorBase
{
	function __construct(){
        parent::__construct();
        $this->load->model('M_geocercas/Mdl_geocerca','Geo');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form','gcaptcha','security'));
        $this->load->config('gcaptcha');
    }
	function index(){
        $this->global['pageTitle'] = 'Reporte Impulso';
        $this->loadViews_gerencia('Geocercas/V_marcaciones',$this->global);
        // $this->Exportar_KML_X_Ruta();
  	}

    function L_CoordenadasMarcacion(){
        if($this->input->is_ajax_request()){

            // $ls_coordenadas = $this->Geo->arrg_coordenadas(1302);
            $ls_rutas = $this->Geo->arrg_coordenadas_rutas(1);
            
            $ag_coordenadas = array();
            $ag_poligonos = array();
            $k = 0;
            // foreach ($ls_coordenadas as $val){
            //     $ag_coordenadas[$k][0] = $val->Mar_longitud_ini;
            //     $ag_coordenadas[$k][1] = $val->Mar_latitud_ini;
            //     $k++;
            // }
            // $ls_paises = $this->Geo->ls_paises();
            // foreach ($ls_paises as $v_p){            
            // }
            $a = 0;$p = 0;
            foreach ($ls_rutas as $v_r){
                $ls_coordenadas = $this->Geo->arrg_coordenadas($v_r->Ru_Id);
                foreach ($ls_coordenadas as $v_c){
                    $ag_coordenadas[$v_r->Ru_Id][$a]['Latitud'] = $v_c->Mar_latitud_ini;
                    $ag_coordenadas[$v_r->Ru_Id][$a]['Longitud'] = $v_c->Mar_longitud_ini;
                    $ag_coordenadas[$v_r->Ru_Id][$a]['Ruta'] = $v_c->Ru_nombre;
                    $ag_coordenadas[$v_r->Ru_Id][$a]['Usuario'] = $v_c->Usu_nombre_usuario;
                    $ag_coordenadas[$v_r->Ru_Id][$a]['Token'] = $v_c->Mar_token;
                    $ag_coordenadas[$v_r->Ru_Id][$a]['Usu_usuario'] = $v_c->Usu_usuario;
                    $a++;
                }
                $ls_poligonos = $this->Geo->arrg_poligonos($v_r->Ru_Id);
                foreach ($ls_poligonos as $v_p){
                    $ag_poligonos[$v_r->Ru_Id][$p][1] = $v_p->Po_Latitud;
                    $ag_poligonos[$v_r->Ru_Id][$p][0] = $v_p->Po_Longitud;
                    $p++;
                }
                $a = 0;$p = 0;
                $k++;
            }
            echo json_encode(array(
                'rs'             => TRUE,
                'ls_coordenadas' => $ag_coordenadas,
                'ls_rutas' => $ls_rutas,
                'ls_poligonos' => $ag_poligonos
                // 'ls_paises' => $ls_paises
            ));
        }else{
            $resp = array(
                'rs'   => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }
    function Exportar_KML_X_Ruta(){
        if($this->input->is_ajax_request()){


            $Nombre_Carpeta = 'KML_'.cadena_aleatoria(17);
            $Carpeta_Temporal = '"../../../Uploads/Documentos/'.$Nombre_Carpeta;
            if (!file_exists($Carpeta_Temporal)) {
                mkdir($Carpeta_Temporal, 0777, true);
            }

            $ls_rutas = $this->Geo->arrg_coordenadas_rutas(1);
            $Encabezado_KML = '<?xml version="1.0" encoding="UTF-8"?><kml xmlns="http://www.opengis.net/kml/2.2"><Document>';
            $Final_KML = '</Document></kml>';
            $Contenido_KML = '';
            $Contenido_KML = json_decode($this->input->post('Json_KML'));
            foreach ($ls_rutas as $v_r){
                

                $Text_KML = '';
                $Text_KML = $Encabezado_KML.$Contenido_KML[$v_r->Ru_Id].$Final_KML;
                // $Ruta = $v_r->Ru_nombre;
                // $Color = 'ffd18802';
                // $PopupText = 'TEXTO';
                // $Lon_Lat_S = '-89.4530923,13.8447504';
                // $Text_KML = '<Placemark><name>'.$Ruta.'</name><Style><IconStyle><color>'.$Color.'</color><scale>1</scale><Icon><href>https://www.gstatic.com/mapspro/images/stock/503-wht-blank_maps.png</href></Icon><hotSpot x="32" xunits="pixels" y="64" yunits="insetPixels"/></IconStyle></Style><ExtendedData><Data name="tipo"><value>'.$PopupText.'</value></Data><Data name="name"><value>'.$Ruta.'</value></Data></ExtendedData><Point><coordinates>'.$Lon_Lat_S.'</coordinates></Point></Placemark>';
                header('Content-type: application/vnd.google-earth.kml+xml');
                $nombreArchivo = $v_r->Ru_nombre.".kml";
                // $carpeta = "/var/www/html/Uploads/img_server/clte_n/".$runanombre;
                file_put_contents($Carpeta_Temporal.'/'.$nombreArchivo, $Text_KML);
    

            }


            // Get real path for our folder
            $rootPath = realpath($Carpeta_Temporal);
            $fecha_descarga = date('d-m-Y_His');
            // Initialize archive object
            $zip = new ZipArchive();
            $zip->open('TemporalZip/COORDENADAS_'.$fecha_descarga.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }

            // Zip archive will be created only after closing object
            $zip->close();
            // unlink('../../Uploads/Documentos/'.$Nombre_Carpeta);


            echo json_encode(array(
                'rs'             => TRUE,
                // 'Arr_Json' => json_decode($this->input->post('Json_KML')),
                'Doc' => 'COORDENADAS_'.$fecha_descarga.'.zip'
            ));

        }else{
            $resp = array(
                'rs'   => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }
    function Importar_KML_X_Ruta(){
    }
    function Validar_Puntos_X_Poligono(){
        if($this->input->is_ajax_request()){

            $ls_rutas = $this->Geo->arrg_coordenadas_rutas(1);
            // foreach ($ls_rutas as $v_r){
            // }

            $y = 13.7660028;
            $x = -89.24980833333333;

            $arr = [

                [-89.37802536091297,13.89539267593116],
                [-89.41192477466586,13.7899015823351],
                [-89.45493298497294,13.760957073667],
                [-89.40774281142464,13.72981200120111],
                [-89.30463337536764,13.66559945019701],
                [-89.17907698450931,13.68511234324253],
                [-89.07125897746394,13.70583544676511],
                [-89.02329441613914,13.75262197479952],
                [-89.01569452864757,13.77718170916688],
                [-89.01128932481978,13.79424351752801],
                [-89.37802536091297,13.89539267593116]

            ];
            $R_OK = 0;
            $R_OK = $this->Punto_Fuera_Poligono($y,$x,$arr);
            echo json_encode(array(
                'rs'   => TRUE,
                'R_OK' => $R_OK
            ));


        }else{
            $resp = array(
                'rs'   => FALSE,
                'info' => 'Error desconocido...'
            );
            echo json_encode($resp);
            return;
        }
    }
    function Punto_Fuera_Poligono($lat,$lon,$poligono){
        $arr = $poligono;
        $y = $lat;
        $x = $lon;
        $count = count($arr);
        $n = 0; // El número de puntos cruzados por la línea
        $bool = 0; // fuera
        for ($i = 0, $j = $count - 1; $i < $count; $j = $i, $i++) {
            // Dos puntos y una línea Saque los puntos fijos de dos puntos de conexión
            $px1 = $arr[$i][0];
            $py1 = $arr[$i][1];
            $px2 = $arr[$j][0];
            $py2 = $arr[$j][1];
            // Dibuja un rayo en la posición horizontal de $ x
            if($x>=$px1 || $x>= $px2){
            // El área para determinar si $ y está en línea
                if(($y>=$py1 && $y<=$py2) || ($y>=$py2 && $y<= $py1)){
                    if (($y == $py1 && $x == $px1) || ($y == $py2 && $x == $px2)) {
                        #Si el valor de $ x es igual que la coordenada del punto
                        $bool = 2; // En el punto
                        return $bool;
                    }else{
                        $px = $px1+($y-$py1)/($py2-$py1)*($px2-$px1);
                        if($px ==$x){
                            $bool = 3; // En línea
                        }elseif($px< $x){
                            $n++;
                        }
                    }
                }
            }
        }
        if ($n%2 != 0) {
            $bool = 1;
        }
        echo $bool;
    }

}