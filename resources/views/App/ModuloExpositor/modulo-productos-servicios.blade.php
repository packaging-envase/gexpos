<div id="productosServicios" class="row no-gutters p-2 mt-2">
    <div class="col-12">
        <div class="d-flex">
            <h4><u>Productos y Servicios (Catálogo)</u></h4>            
            <button class="btn btn-success ms-2" type="button" onclick="addProductosServicios()">
                + Agregar
            </button>
        </div>
        <small>Cargue imagenes de 300px x 300px mínimo recomendado, no más de {{$viewData['catalogo']['max_file_size']}} MB. Si no se carga una imagen o la imagen no tiene las dimensiones recomendadas, podría no verse correctamente.</small>
    </div>
    @if (isset($viewData['empresa']['productos']))
        @foreach ($viewData['empresa']['productos'] as $item)
            <div class="col-12">
                <div class="row no-gutters">
                    <div class="col-12 col-md-6 px-3">
                        <label><b>Nombre Producto o Servicio</b></label><br/>
                        <input required class="form-control" name="productoServicioNombre{{ $item['id'] }}"
                            value="{{ $item['nombre'] }}" maxlength="60"/>
                    
                        <label>Descripcion (<span id="psLength{{$item['id']}}">
                            {{strlen($item['descripcion'])}}
                        </span>/{{$viewData['catalogo']['max_caracteres_producto_descripcion']}})</label>
                        <textarea class="form-control" style="resize: none;" name="productoServicioDescripcion{{ $item['id'] }}"
                            maxlength="@json($viewData['catalogo']['max_caracteres_producto_descripcion'])"
                            rows="5" 
                            oninput="checkMaxLength(this, @json($viewData['catalogo']['max_caracteres_producto_descripcion']), 'psLength{{$item['id']}}')">{{ $item['descripcion'] }}</textarea>
                    </div>
                    <div class="col-12 col-md-2 px-2">
                        <input hidden id="inputProdServImagenExist{{ $item['id'] }}" type="file"
                            name="prodServImagenExist{{ $item['id'] }}"
                            onchange="if (validateFile(this, 'imageProductExist{{ $item['id'] }}' ,@json($viewData['catalogo']['max_file_size']))) 
                        {$('#prodServImagenExistSmall{{ $item['id'] }}')
                            .html(document.getElementById('inputProdServImagenExist{{ $item['id'] }}').files.length+' archivo cargado'); 
                        }" />

                        <input hidden id="deleteProdServExist{{ $item['id'] }}"
                            name="deleteProdServExist{{ $item['id'] }}" value="0" />

                        <button class="btn btn-success" type="button"
                            onclick="$('#inputProdServImagenExist{{ $item['id'] }}').click();">
                            Cargar Imagen
                        </button><br />
                        <small id="prodServImagenExistSmall{{ $item['id'] }}"></small><br />
                        <button id="btnDeleteProductoServicio{{ $item['id'] }}" class="btn btn-danger mt-1" type="button"
                            onclick="if (confirm('Esta a punto de eliminar este producto'))
                                {
                                    $('#deleteProdServExist'+{{ $item['id'] }}).val('1');
                                    $('#btnDeleteProductoServicio{{ $item['id'] }}').html('Eliminado')
                                }">
                            Eliminar
                        </button>
                    </div>
                    <div class="col-12 col-md-4">
                        <img id="imageProductExist{{ $item['id'] }}"
                            src="{{ $item['path_logo'] ? asset('storage/empresa/productoServicioLogo/' . $item['path_logo']) : '' }}"
                            style="max-width: 250px; max-height: 250px;" />
                        <br />
                    </div>
                </div>
                <hr />
            </div>
        @endforeach
    @endif
</div>
<script type="text/javascript">
    let rowProdServ = 0;
    let rowProdServTotal = @json(count($viewData['empresa']['productos']));
    let maxQtyProducts = @json($viewData['catalogo']['max_quantity_productos']);

    function checkMaxLength(textarea, maxLength, idLabel) {
        var contentLength = textarea.value.length;
        $('#'+idLabel).html(contentLength);
        if (contentLength > maxLength) {
            textarea.value = textarea.value.substring(0, maxLength); // Trunca el texto
            alert('Has alcanzado el límite máximo de caracteres.');
        }
    }

    function addProductosServicios() {
        if (rowProdServTotal < @json($viewData['catalogo']['max_quantity_productos'])) {
            let div = $('#productosServicios');
            let prodServ = `
                <div id="rowProdServ` + rowProdServ + `" class="col-12">
                    <div class="row no-gutters">
                        <div class="col-12 col-md-6 px-2">
                            <label><b>Nombre Producto o Servicio</b></label>
                            <input required class="form-control" name="productoServicioNombre[]" maxlength="60" required/>
                        
                            <label>Descripcion 
                                (<span id="npsLength`+rowProdServ+`">0</span>
                                /{{$viewData['catalogo']['max_caracteres_producto_descripcion']}})
                            </label>
                            <textarea class="form-control" style="resize: none;" 
                                rows="5"
                                name="productoServicioDescripcion[]" 
                                maxlength="@json($viewData['catalogo']['max_caracteres_producto_descripcion'])"
                                oninput="checkMaxLength(this, @json($viewData['catalogo']['max_caracteres_producto_descripcion']), 'npsLength`+rowProdServ+`')"></textarea>
                        </div>
                        <div class="col-12 col-md-2 px-2">
                            <input type="hidden" name="productosImagenes[]" value="">
                            <input hidden id="prodServImagen` + rowProdServ + `" 
                            type="file" 
                            name="productosImagenes[]" 
                            onchange="if (validateFile(this,'imageProduct` + rowProdServ + `', @json($viewData['catalogo']['max_file_size']))) 
                                {$('#prodServImagenSmall` + rowProdServ + `')
                                    .html(document.getElementById('prodServImagen` + rowProdServ + `').files.length+' archivo cargado'); 
                                }" />
                            <button class="btn btn-success" type="button" onclick="$('#prodServImagen` + rowProdServ + `').click();">Cargar Imagen</button><br/>
                            <small id="prodServImagenSmall` + rowProdServ + `"></small><br/>
                            <button class="btn btn-danger mt-1" type="button" onclick="if (confirm('Esta a punto de eliminar este producto'))
                            {$('#rowProdServ` + rowProdServ + `').remove(); rowProdServTotal -= 1;}">Eliminar</button>
                        </div>
                        <div class="col-12 col-md-4">
                            <img id="imageProduct` + rowProdServ + `"
                                style="max-width: 250px; max-height: 250px;" />
                            <br />
                        </div>
                    </div>
                    <hr/>
                </div>
            `;
            div.append(prodServ);
            rowProdServ += 1;
            rowProdServTotal += 1;
        } else {
            alert('Alcanzo la máxima cantidad de ' + @json($viewData['catalogo']['max_quantity_productos']) + ' productos/servicios')
        }
    }
</script>
