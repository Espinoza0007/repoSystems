<!-- MODAL CATALOGO DE PRODUCTOS ------> 
    <div class="modal fullscreen-modal" id="modalCatalogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index: 1070;">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header d_arriba">
                <span class="modal-title" style="margin-top:-7px;">LISTA DE PRODUCTOS</span>
                <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
                </div>
                <div class="modal-body">
                <div class="row" style="margin-top: 7px;">
                    <div class="col-9" style="background-color:;margin:0 auto;" id="filtro_fam">     
                        <select id='familas-p' class='form-control' style="">
                        </select>
                    </div>
                </div>
                    <div class="table-responsive">
                        <table id="catalogoDtable" class="table table-bordered" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Unidad de medida</th>
                                    <th scope="col">Familia</th>
                                    <th scope="col">Sub Familia</th>
                                    <th scope="col">Estado</th>
                                    <!-- <th scope="col">Monto</th> -->
                                </tr>
                            </thead>
                            <tbody id="showDataSN">                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Unidad de medida</th>
                                    <th scope="col">Familia</th>
                                    <th scope="col">Sub Familia</th>
                                    <th scope="col">Estado</th>
                                    <!-- <th scope="col">Monto</th> -->
                                </tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- FIN MODAL CATALOGO DE PRODUCTOS -->   

<!-- MODAL LISTA DE CLIENTES RECLAMOS-->
    <div class="modal fullscreen-modal" id="modalClientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header d_arriba">
                <span class="modal-title" style="margin-top:-7px;">LISTA DE CLIENTES</span>
                <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
                </div>
                <div class="modal-body">
                <div class="row" style="margin-top: 7px;">            
                    
                    <div class="col-8" style="background-color:;">     
                        <select id='dias_busqueda' class='form-control' style="">
                            <option value=''>TODOS LOS DIAS</option>
                            <option value='LUNES'>LUNES</option>
                            <option value='MARTES'>MARTES</option>
                            <option value='MIERCOLES'>MIERCOLES</option>
                            <option value='JUEVES'>JUEVES</option>
                            <option value='VIERNES'>VIERNES</option>
                            <option value='SABADO'>SABADO</option>
                            <option value='DOMINGO'>DOMINGO</option>
                        </select>
                    </div>

                </div>
                    <div class="table-responsive">
                        <table id="clientesDtable" class="table table-bordered" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Ruta</th>
                                    <th scope="col">Días visita</th>
                                    <th scope="col">Division</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="showDataCli">                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Ruta</th>
                                    <th scope="col">Días visita</th>
                                    <th scope="col">Division</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer d_abajo">
                </div>
            </div>
        </div>
    </div>
<!-- FIN LISTA DE CLIENTES RECLAMOS --->

<!-- MODAL LISTA VENTA ------> 
    <div class="modal fullscreen-modal" id="modal_venta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index: 1070;">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header d_arriba">
                <span class="modal-title" style="margin-top:-7px;">Lista pedidos</span>
                <span id="XX" style="margin-top:-12px;margin-right:-12px;float:right;font-size: 40px;color:#FA2D52;" class="fa fa-window-close" data-dismiss="modal"></span>
                </div>
                <div class="modal-body">
                <div class="row" style="margin-top: 7px;">
            
                    <div class="col-9" style="background-color:;" id="filtro_fam">     
                        <select id='familas-p' class='form-control' style="">
                            
                        </select>
                    </div>
                </div>
                    <div class="table-responsive">
                        <table id="pedidoDtable" class="table table-bordered" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Unidad de medida</th>
                                    <th scope="col">Familia</th>
                                    <th scope="col">Sub Familia</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Monto</th>
                                </tr>
                            </thead>
                            <tbody id="showDataSN">                    
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">C&oacute;digo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Unidad de medida</th>
                                    <th scope="col">Familia</th>
                                    <th scope="col">Sub Familia</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Monto</th>
                                </tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- FIN MODAL LISTA VENTA -->   
