<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Ctr_login';
/*---------------------------------------------------------------------------------------
	CONTROLADOR LOGIN
---------------------------------------------------------------------------------------*/
$route['login'] = 'Ctr_login';
$route['login/consultar'] = 'Ctr_login/iniciar_sesion';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MENU PRINCIPAL
---------------------------------------------------------------------------------------*/
$route['menu'] = 'C_menu/Ctr_menu';
$route['menu/sincronizar'] = 'C_menu/Ctr_menu/sincronizacion_completa';
$route['menu/contrasena_nueva'] = 'C_menu/Ctr_menu/Actualizar_Pass_Nuevo';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MENU CLIENTES NUEVOS
---------------------------------------------------------------------------------------*/
$route['clientes'] = 'C_clientes/Ctr_new_clientes';
$route['sincronizacion/inicial'] = 'C_clientes/Ctr_new_clientes/sincrok';
$route['clientes/agregar-ok'] = 'C_clientes/Ctr_new_clientes/nuevoclienteok';
$route['clientes/agregar-backup'] = 'C_clientes/Ctr_new_clientes/clientes_backup';
$route['clientes/actualizar-backup'] = 'C_clientes/Ctr_new_clientes/clientes_backup_AC';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MENU ACTUALIZACION DE EXHIBIDORES
---------------------------------------------------------------------------------------*/
$route['encuesta-exhibidores'] = 'C_clientes/Ctr_exhibidores';
$route['sincro/encuestaexh'] = 'C_clientes/Ctr_exhibidores/sincronizacion_inicial';
$route['guardar/exhibidores'] = 'C_clientes/Ctr_exhibidores/guardar_actulizacionExh';
$route['ver/encuesta-actualizados'] = 'C_clientes/Ctr_exhibidores/status_encuesta';
$route['exhibidores/guardar_cambios'] = 'C_clientes/Ctr_exhibidores/GuardarStatusExh';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MENU ACTUALIZACION DE CLIENTES
---------------------------------------------------------------------------------------*/
$route['actualizacion-clientes'] = 'C_clientes/Ctr_lat_log';
$route['actualizar_cleitnes/ok'] = 'C_clientes/Ctr_lat_log/actualizacion_cli';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MENU DE RECLAMO DE PRODUCTOS
---------------------------------------------------------------------------------------*/
$route['reclamos'] = 'C_reclamos/Ctr_ingreso_reclamos';
$route['reclamo_nuevo'] = 'C_reclamos/Ctr_ingreso_reclamos/ingresoNuevoReclamo';
$route['admin_reclamo'] = 'C_reclamos/Ctr_ingreso_reclamos/index_admin';
$route['data_reclamos_ls'] = 'C_reclamos/Ctr_ingreso_reclamos/obtener_lista_reclamos';
$route['data_reclamos_ls1'] = 'C_reclamos/Ctr_ingreso_reclamos/obtener_lista_reclamos1';
$route['data_reclamo_reg'] = 'C_reclamos/Ctr_ingreso_reclamos/obtener_reclamo';
$route['data_filtro_dist'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_distribuidora';
$route['data_filtro_canal'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_canal';
$route['data_filtro_grupo'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_grupo';
$route['data_filtro_ruta'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_ruta';
$route['img_reclamo'] = 'C_reclamos/Ctr_ingreso_reclamos/guardar_imagen_reclamo';
$route['imprimir_reclamo'] = 'C_reclamos/Ctr_ingreso_reclamos/generarReclamoPdf';
$route['exportar_reclamo'] = 'C_reclamos/Ctr_ingreso_reclamos/exportar_reclamo_xlsx';
$route['data_filtro_pais'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_pais';
$route['data_filtro_division'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_division';
$route['catalogo_bodega'] = 'C_reclamos/Ctr_ingreso_reclamos/ls_productos_bodega';
$route['filtro_familias'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_familas_catalago';
$route['filtro_subfamilias'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_subfamilas_catalago';
$route['filtro_um'] = 'C_reclamos/Ctr_ingreso_reclamos/filtro_um';
$route['data_tipo_reclamos'] = 'C_reclamos/Ctr_ingreso_reclamos/ls_tipo_reclamos_bodega';
$route['reclamo_nuevo1'] = 'C_reclamos/Ctr_ingreso_reclamos/ingresoReclamo_bodega';
$route['rec_enviados'] = 'C_reclamos/Ctr_ingreso_reclamos/get_reclamos_eviados';
$route['control_inventario'] = 'C_control_inventario/Ctr_control_inventario';
$route['rec_Listadoini'] = 'C_reclamos/Ctr_ingreso_reclamos/get_reclamos_listado_inicial';

/*---------------------------------------------------------------------------------------
	CONTROLADOR OTROS
---------------------------------------------------------------------------------------*/
$route['comprobar/conexion'] = 'C_clientes/Ctr_new_clientes/resultconexion';
$route['migrar'] = 'C_reportes/Ctr_migrar';
$route['migrartotal/(:any)'] = 'C_reportes/Ctr_migrar/totalcli';
$route['cargarmigrar/(:any)'] = 'C_reportes/Ctr_migrar/cargarregistro';
$route['editar_clientes'] = 'C_reportes/Ctr_migrar/actualizar_clientes';
$route['ingresar_clientes'] = 'C_reportes/Ctr_migrar/ingresar_clientes';
$route['ingresar_exhibidores'] = 'C_reportes/Ctr_migrar/ingresar_exhibidores';
$route['prueba_piloto'] = 'C_reportes/Ctr_migrar/info_prueba_piloto';
$route['editar'] = 'C_reportes/Ctr_editar';
$route['migrartotaleditar/(:any)'] = 'C_reportes/Ctr_editar/totalcli';
$route['cargarmigrareditar/(:any)'] = 'C_reportes/Ctr_editar/cargarregistro';
$route['comprobarconec/(:any)'] = 'Ctr_login/resultconexionlogin/$1';
$route['salir'] = 'Ctr_cerrarsesion';
/*---------------------------------------------------------------------------------------
	CONTROLADOR REPORTE DE EXHIBIDORES
---------------------------------------------------------------------------------------*/
// $route['r_exh'] = 'C_reportes/Ctr_reporte_exh';
// $route['ls_clteafiches/mostrar'] = 'C_reportes/Ctr_reporte_exh/Lista_TablaClteAfiches';
// $route['ls_clteafiches/xclienteAfiche'] = 'C_reportes/Ctr_reporte_exh/Expediente_ExhibidoresXcli';
// $route['ls_clteafiches/infoexhibidor'] = 'C_reportes/Ctr_reporte_exh/InfoExhSeleccionado';
// $route['ls_clteafiches/filtrosReportExh'] = 'C_reportes/Ctr_reporte_exh/FiltrosBusqueda';
/*---------------------------------------------------------------------------------------
	CONTROLADOR REPORTE DE GEOCERCAS IMPULSO
---------------------------------------------------------------------------------------*/
$route['mapa_impulso'] = 'C_geocercas/Ctr_marcaciones';
$route['ls_marcaciones/coordenadas'] = 'C_geocercas/Ctr_marcaciones/L_CoordenadasMarcacion';
$route['exportar_kml/coordenadas'] = 'C_geocercas/Ctr_marcaciones/Exportar_KML_X_Ruta';
$route['validar/coordenadas'] = 'C_geocercas/Ctr_marcaciones/Validar_Puntos_X_Poligono';
/*---------------------------------------------------------------------------------------
	CONTROLADOR DE PEDIDOS SUGERIDOS
---------------------------------------------------------------------------------------*/
$route['pedido_sugerido'] = 'C_pedidos/Ctr_pedido_sugerido';
$route['pedido_sugerido/solicitado'] = 'C_pedidos/Ctr_pedido_sugerido/GuardarPedidOptimo';
/*---------------------------------------------------------------------------------------
	CONTROLADOR DE GENERADOR DE ITINERARIO
---------------------------------------------------------------------------------------*/
$route['gen_iti_clientes'] = 'C_reportes/Ctr_migrar/Gen_Iti_PlusUltra';
// $route['gen_iti_clientes'] = 'C_reportes/Ctr_migrar/Gen_Iti_PlusUltra';
// $route['list_clientes'] = 'C_reportes/Ctr_migrar/Lista_clientes_XA';
$route['Calcular_distanciax'] = 'C_reportes/Ctr_migrar/DistaciaEntrePuntosGPS';
$route['Calcular_distanciaxK'] = 'C_reportes/Ctr_migrar/DistanciaEntrePuntosGPS_Kobo';
$route['Calcular_distanciaxKD'] = 'C_reportes/Ctr_migrar/EncontrarClientes_Kobo';
$route['Calcular_distanciaClteKobo'] = 'C_reportes/Ctr_migrar/EncontrarClientes_KoboTres';
/*---------------------------------------------------------------------------------------
	CONTROLADOR API XAMARIN
---------------------------------------------------------------------------------------*/
// $route['list_clientes'] = 'C_api_xamarin/Ctr_api_xamarin/';
$route['list_clientes'] = 'C_api_xamarin/Ctr_api_xamarin/Lista_clientes_XA';
$route['list_x_clientes'] = 'C_api_xamarin/Ctr_api_xamarin/Lista_clientes_X_cli';
$route['login_apk'] = 'C_api_xamarin/Ctr_api_xamarin/LoginUsApk';
$route['modificar_us'] = 'C_api_xamarin/Ctr_api_xamarin/ModificarUsuario';
$route['list_x_zona'] = 'C_api_xamarin/Ctr_api_xamarin/Lista_clientes_X_Zona';
$route['listusuariof'] = 'C_api_xamarin/Ctr_api_xamarin/Lista_usuariosOffline';
$route['modificar_usof'] = 'C_api_xamarin/Ctr_api_xamarin/Modificar_usuariosOffline';
$route['Lista_censoPorUbicacion'] = 'C_api_xamarin/Ctr_api_xamarin/Lista_censoPorUbicacion';
$route['Insertar_MatchCenso'] = 'C_api_xamarin/Ctr_api_xamarin/Insertar_MatchCenso';
$route['ValidacionClienteXamarin'] = 'C_api_xamarin/Ctr_api_xamarin/ValidacionClienteXamarin';
$route['ActualizarCensoMaestroClientes'] = 'C_api_xamarin/Ctr_api_xamarin/ActualizarCensoMaestroClientes';
$route['ConsultaClienteVinculoCensoview'] = 'C_api_xamarin/Ctr_api_xamarin/ConsultaClienteVinculoCensoview';
$route['ActualizaErrorCensoMaestroClientes'] = 'C_api_xamarin/Ctr_api_xamarin/ActualizaErrorCensoMaestroClientes';
$route['ConsultaClientesPorRutaOfflineCenso'] = 'C_api_xamarin/Ctr_api_xamarin/ConsultaClientesPorRutaOfflineCenso';
$route['ConsultaBaseCensoPorPais'] = 'C_api_xamarin/Ctr_api_xamarin/ConsultaBaseCensoPorPais';
$route['ConsultaUsuarioLoginCenso'] = 'C_api_xamarin/Ctr_api_xamarin/ConsultaUsuarioLoginCenso';
$route['vehiculo'] 			    = 'C_vehiculo/Ctr_vehiculo';
$route['historial'] 			= 'C_vehiculo/Ctr_historial';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MODULO DE RECEPCION DE VEHICULO
---------------------------------------------------------------------------------------*/
$route['vehiculo'] 			    = 'C_vehiculo/Ctr_vehiculo';
$route['historial'] 			= 'C_vehiculo/Ctr_historial';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MODULO EVALUACION DE MERCADO 
---------------------------------------------------------------------------------------*/
$route['mercado'] 			    = 'C_mercado/Ctr_mercado';
/*---------------------------------------------------------------------------------------
	CONTROLADOR MENU ACTUALIZACION DE CLIENTES - FACTURACION ELECTRONICA
---------------------------------------------------------------------------------------*/
$route['actualizar_datos'] = 'C_clientes/Ctr_actualizar';
$route['actualizar_datos/ok'] = 'C_clientes/Ctr_actualizar/actualizar';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

