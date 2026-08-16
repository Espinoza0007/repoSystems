<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
    <title>Detalle reclamo</title>
    <link rel="shortcut icon" type="image/ico" href="<?php echo base_url('dependencias/imagenes/bocadeli_logo.png')?>">
    <style>            
@page {
            margin: 0cm 0cm;
        }
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 300;
            src: local('Roboto Light'), local('Roboto-Light'), url(https://fonts.gstatic.com/s/roboto/v20/KFOlCnqEu92Fr1MmSU5vAw.ttf) format('truetype');
        }
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: local('Roboto'), local('Roboto-Regular'), url(https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Me5Q.ttf) format('truetype');
        }
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: local('Roboto Bold'), local('Roboto-Bold'), url(https://fonts.gstatic.com/s/roboto/v20/KFOlCnqEu92Fr1MmWUlvAw.ttf) format('truetype');
        }
        @import url('https://fonts.googleapis.com/css2?family=PT+Sans&display=swap');


        body {
            margin-top: 2.54cm;
            margin-left: 2.54cm;
            margin-right: 2.54cm;
            margin-bottom: 2.54cm;
        }

        body tr,td, th{
            border: solid 1px #000;
        } 

        td, th {
            font-family: 'PT Sans', sans-serif;
            font-size: 11px;
            padding: 2px;
        }
        table{
            border-collapse: collapse;
            margin: auto;
        }
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            background-color: #EAECEE;
            /* background: url('<?php echo base_url('dependencias/imagenes/bocadeli_logo.png')?>');     */
            /* height: 80px; width: 80px;         */
            font-family: 'PT Sans', sans-serif;
            color: #3D3D3D;
            line-height: 0cm;
            text-align: center;
            font-size: 25px !important;
        }       

        /* footer {
            position: fixed; 
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 2cm;
            background-color: #EAECEE;            
            color: white;
            text-align: center;
            line-height: 1.5cm;
        } */

        footer{
            position: fixed;
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 2cm;

            background-color: #EAECEE;
            font-family: 'PT Sans', sans-serif;
            color: #3D3D3D;
            line-height: 0cm;
            text-align: center;
            font-size: 25px !important;
        }

        .div_firmas {
            font-size:13px;
            position: fixed;
            bottom: 3cm;
            left: 2.54cm; 
            right: 2.54cm;
            border: none;
            
        }
        tfoot { page-break-after: always; }
        tfoot:last-child { page-break-after: never; }
    </style>
</head>
<body>
    <header id="header" class="">
        <table width="90%" style="border: none;">
            <tr  style="border: none;">
                <td width="35%" style="border: none;">
                    <img src="<?php echo base_url('dependencias/imagenes/bocadeli_logo.png')?>" style="height: 80px; width: 80px; margin:5px; opacity: 0.7;">
                </td>
                <td width="65%" style="border: none;">
                    Reclamo de calidad
                </td>
            </tr>
        </table>
                  
    </header>
    <footer>
        
    </footer>
    <main>
        <?php
            if(isset($reclamo_arr))
            {
                $cont = 0;
                $codigo1 = $reclamo_arr[0]->Rec_Id;
                $codigo = preg_split('/\d{1,30}/', $reclamo_arr[0]->Rec_Id);
                $codigo1 = str_replace($codigo, '', $codigo1);
        ?>
            <table width="100%" style="font-size:13px;">  
                <thead> 
                    <tr>
                        <th colspan="4">Datos generales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="20%">No.</td>
                        <td width="30%"><?php echo $codigo[0].' '.$codigo1; ?></td>
                        <td width="20%">Fecha de reclamo</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Rec_fecha_servidor; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">País</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->P_nombre; ?></td>
                        <td width="20%">Division</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Di_nombre; ?></td>                    
                    </tr>
                    <tr>
                        <td width="20%">Distribuidora:</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Dis_nombre; ?></td>
                        <td width="20%">Canal:</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Ca_nombre; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Nombre del cliente</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Cli_nombre; ?></td>
                        <td width="20%">Código del cliente</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Cli_Id; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Dirección:</td>
                        <td colspan="3"><?php echo $reclamo_arr[0]->Cli_direccion; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Vendedor:</td>
                        <td colspan="3"><?php echo $reclamo_arr[0]->Emp_nombre; ?></td>
                    </tr>
                    <tr>
                        <td width="20%">Carnet:</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Emp_carnet; ?></td>
                        <td width="20%">Ruta</td>
                        <td width="30%"><?php echo $reclamo_arr[0]->Ru_nombre; ?></td>
                    </tr>
                </tbody>
            </table>
            <br>
                    <?php 
                        foreach ($reclamo_arr as $key) {
                    ?>                        
                        <table width="100% " style="page-break-inside: avoid;">  
                            <thead> 
                                <tr>
                                    <th width="100%" colspan="4">Detalle de producto en malas codiciones</th>
                                </tr>                        
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%">Tipo de reclamo:</td>
                                    <td width="80%" colspan="3"><?php echo $key->Tipd_descripcion; ?></td>
                                </tr>
                                <tr>
                                    <td width="20%">Producto:</td>
                                    <td width="80%" colspan="3"><?php echo $key->Cat_descripcion; ?></td>
                                </tr>
                                <tr>
                                    <td width="20%">Codigo:</td>
                                    <td width="30%"><?php echo $key->Cat_Id; ?></td>
                                    <td width="20%"><b>Unidades dañadas (UN):</b></td>
                                    <td width="30%"><b><?php echo $key->Rec_unidades_danadas; ?></b></td>
                                </tr>
                                <tr>
                                    <td width="20%">Numero de lote:</td>
                                    <td width="30%"><?php echo $key->Rec_numero_lote == '' ? ' -- ' : $key->Rec_numero_lote; ?></td>
                                    <td width="20%">Fecha de vencimiento</td>
                                    <td width="30%"><?php echo $key->Rec_fecha_vencimiento == '' ?  '-- ' : $key->Rec_fecha_vencimiento; ?></td>
                                </tr>
                                <tr>
                                    <td width="20%">Familia</td>
                                    <td width="30%"><?php echo $key->Fa_nombre; ?></td>
                                    <td width="20%">Subfamilia</td>
                                    <td width="30%"><?php echo $key->Subf_nombre; ?></td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="2"><b>Cantidad a entregar: </b></td>
                                    <td colspan="2"><?php // echo $key->Rec_cantidad; ?></td>
                                </tr> -->
                                <tr>
                                    <td><b>Cantidad a entregar: </b></td>
                                    <td><?php echo $key->Rec_cantidad; ?></td>
                                    <td><b>Estado: </b></td>
                                    <td><?php echo $key->Rec_estado; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Observacion adicional:</b></td>
                                    <td colspan="3">
                                        <?php echo $key->Rec_observacion_ventas; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" colspan="2" style="text-align: center;">Foto producto dañado</td>
                                    <td width="50%" colspan="2" style="text-align: center;">Foto fecha y lote</td>
                                </tr>
                                <tr>
                                    <td width="50%" colspan="2">
                                        <img src="<?php echo 'https://bocadeli.info/'.str_replace("../","", $key->Rec_foto_fecha_lote); ?>" class="img_datatable" style="border: 1px solid black;max-height:140px;width:auto; max-width:120px; margin:5px;">
                                    </td>
                                    <td width="50%" colspan="2">
                                        <img src="<?php echo 'https://bocadeli.info/'.str_replace("../","", $key->Rec_foto_producto); ?>" class="img_datatable" style="border: 1px solid black;max-height:140px;width:auto; max-width:120px; margin:5px;">
                                    </td>             
                                </tr>
                            </tbody>
                            <?php  $cont++; if ($cont == 2) { ?>
                                <tfoot>
                                </tfoot>
                            <?php } else { ?>
                                <br>
                            <?php }  ?> 
                        </table>
                        <br>
                    <?php
                        }
                    ?>
        <?php
            }
        ?>

        
        <table width="100%" class="div_firmas">  
            <thead> 
                <tr style="border:none;">
                    <th colspan="4" style="border:none;">F.__________________________________________<br>Jefe de ventas <?php echo $reclamo_arr[0]->Di_nombre; ?></th>
                </tr>
            </thead>
            
        </table>

    </main>
    
    

</body>
</html>