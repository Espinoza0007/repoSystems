<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
    }
    
</style>
<!-- PANEL CATALOGO PRODUCTOS BODEGA -->
    <div class="collapse" id="panel_mantinimiento">
        <div class="card card-body">
            <!-- <form id="frm_filtros_catalogo_no">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <div class="titulo"><span class=""></span> Familia</div>
                        <div id="fil_familia">
                            <select class="form-control custom-select select2_" data-width="100%">
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group col-md-3">
                        <div class="titulo"><span class=""></span> Estado</div>
                        <div id="fil_estado">
                            <select class="form-control custom-select select2_" data-width="100%">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>                                
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <div class="titulo"><span class=""></span> Subfamilia</div>
                        <div id="fil_subfamilia">
                            <select class="form-control custom-select select2_" data-width="100%">
                                <option>Seleccione una opción</option>
                            </select>
                        </div>                                
                    </div>
                    <div class="col-md-3 row m-0 text-center align-items-center justify-content-center">
                        <button type="button" class="btn btn-info align-bottom" id="btn_buscar_cat_bo">
                            <span class="fas fa-search"> Buscar</span> 
                        </button>
                    </div>
                </div>
            </form>     
            <hr>     -->

            <div class="form-group col-md-3">
                <button type="button" class="btn btn-outline-success btn-block" id="btn_agregar_cat" name="btn_agregar_cat"><i class=""></i> Agregar producto</button><br>
            </div>
            <div class="table-responsive">
                <table id="catalogoDbodega" class="table table-bordered" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">C&oacute;digo</th>
                            <th scope="col">Descripción producto</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">UM</th>
                            <th scope="col">Familia</th>
                            <th scope="col">Sub Familia</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                            <th scope="col" class="d-none"></th>
                            <th scope="col" class="d-none"></th>
                            <th scope="col" class="d-none"></th>
                        </tr>
                    </thead>
                    <tbody id="showDataCat">                    
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col">C&oacute;digo</th>
                            <th scope="col">Descripción producto</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">UM</th>
                            <th scope="col">Familia</th>
                            <th scope="col">Sub Familia</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                            <th scope="col" class="d-none"></th>
                            <th scope="col" class="d-none"></th>
                            <th scope="col" class="d-none"></th>
                        </tr>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<!-- FIN PANEL CATALOGO PRODUCTOS BODEGA -->    

<!-- MODAL MANTENIMIENTO PRODUCTOS -->
    <div class="modal" id="modal_catalogo_bodega" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width:100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLongTitle">Mantenimiento del catalogo de productos</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 4% 4% 0% 4%;">
                    <form id="frm_catalogo_bodega">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <div class="titulo"><span class=""></span>Código producto</div>
                                <input type="number" class="form-control" name="txtCodigoCat" id="txtCodigoCat">
                            </div>
                            <div class="form-group col-md-8">
                                <div class="titulo"><span class=""></span>Descripción del producto:</div>
                                <input type="text" class="form-control" name="txtDescripcionCat" id="txtDescripcionCat">
                            </div>                    
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 divrow">
                                <div class="titulo"><span class=""></span> Familia</div>
                                <div id="select_familia">
                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>
                            <div class="form-group col-md-4 divrow">
                                <div class="titulo"><span class=""></span> Subfamilia</div>
                                <div id="select_subfamilia">
                                    <select class="form-control custom-select select2_" data-width="100%">
                                        <option>Seleccione una opción</option>
                                    </select>
                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>
                            <div class="form-group col-md-4 divrow">
                                <div class="titulo"><span class=""></span> Unidad Medida</div>
                                <div id="select_UM">
                                </div>
                                <div class="valid-feedback">
                                    <strong></strong>
                                </div>
                                <div class="invalid-feedback" id="error-mjs-17">
                                    <strong>Por favor selecciona una opción de la lista!</strong>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="titulo"><span class="fa fa-camera"></span> Foto del producto</div>
                                    <div class="custom-file">
                                        <input id="file_cat_bo" name="file_cat_bo" class="custom-file-input" lang="es" type="file" accept="image/*" capture="camera">
                                        <label class="custom-file-label" data-browse="Tomar foto" for="file_cat_bo">Fotografía del producto</label>
                                        <div class="valid-feedback">
                                            <strong></strong>
                                        </div>
                                        <div class="invalid-feedback" id="error-mjs-7">
                                            <strong>Por favor tomar una foto!</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="display: flex; justify-content: center; max-height: 164px; min-height: 164px;">
                                    <img src="<?php echo base_url('dependencias/imagenes/file_3_icon-icons.com_68952.png');?>" id="canvas_cat" style="border: 1px solid black;width:auto;max-width:224px;height:164px;max-height:164px;" class="align-content-center zoom">
                                </div>                            
                            </div>
                            <div class="form-group col-md-4" style="display:none;">
                                <div class="titulo"><span class=""></span> Estado</div>
                                <div id="select_estado_cat">
                                    <select class="form-control custom-select" id="slc_estado_cat" name='slc_estado_cat' data-width="100%">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>                                 
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                            <!-- <div class="titulo"><span class=""></span> Asignar a canales</div> -->
                                <table class="table table-hover" id="panel_asignar">
                                    <thead>
                                        <tr class="thead-light" style="text-align: center;">
                                            <th scope="col" colspan="2">Asignar a canales</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Distribuidora</th>
                                            <th scope="col">Canal</th>
                                        </tr>
                                  </thead>
                                    <tbody id="div_ls_distribuidoras" style="display: none;">
                                        
                                    </tbody>
                                </table>                                           
                                
                            </div>
                        </div> 
                </div>            
                <div class="modal-footer">
                    <!-- <button id="btn_enviar_cat" type = "button" onclick="guardar_cambios();" class="btn btn-primary" ><span class="fas fa-paper-plane fa-lg" ></span> Guardar cambios</button> -->
                    <button id="btn_enviar_cat" class="btn btn-primary" ><span class="fas fa-paper-plane fa-lg" ></span> Guardar cambios</button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                    </form>                 
            </div>
        </div>
    </div>
<!-- FIN MODAL MANTENIMIENTO PRODUCTOS -->



<script type="text/javascript">
    document.getElementById('file_cat_bo').onchange = function(evt) {
        foto_catalogo_producto = '';
        if($(this).val() != ''){
            ImageTools.resize(this.files[0], {
                width: 823,
                height: 403
            }, function(blob, didItResize) {
                document.getElementById('canvas_cat').src = window.URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob); 
                reader.onloadend = function() {

                    if(blob === null || blob === "" || blob === undefined){
                        $("#file_cat_bo").val("");
                        $("#error_fotouno").empty().html('* LA FOTO ES OBLIGATORIA');
                        $("#canvas_cat").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
                        Swal.fire({
                            title: '<strong>Atención!</strong>',
                            type: 'warning',
                            html:`<div id="mjs_estilo">Por favor vuelve a tomar foto del exhibidor principal...</div>`,
                            confirmButtonText:'Ok'
                        });
                    }else{
                        var base64data = reader.result;
                        foto_catalogo_producto = base64data;
                    }
                      URL.revokeObjectURL(this.src); 
                }
            });
        }else{
            $("#canvas_cat").attr("src","../dependencias/imagenes/file_3_icon-icons.com_68952.png");
        }
    };
</script>

