<div id="empresasRepresentadas" class="row no-gutters p-2 mt-2">
    <div class="col-12">
        <div class="d-flex">
            <h4 class="mt-1"><u>Empresas Representadas (Catálogo)</u></h4>
            <button type="button" class="btn btn-success ms-2" onclick="addEmpresaRepresentada()">
                + Agregar
            </button>
        </div>
        <small>Cargue imagenes cuadradas de 50x50 píxeles minimo recomendada, no más de {{$viewData['catalogo']['max_file_size']}} MB. Tener en cuenta que la imagen se transformará en un círculo, por lo que las esquinas serán recortadas.</small>
        @if (isset($viewData['empresa']['empresaRepresentada']))
            @foreach ($viewData['empresa']['empresaRepresentada'] as $item)
                <div class="col-12">
                    <div class="row no-gutters">
                        <div class="col-12 col-md-6 px-2">
                            <label>Nombre Empresa Representada</label>
                            <input required class="form-control" name="empresaRepresentadaNombre{{ $item['id'] }}"
                                value="{{ $item['nombre'] }}" />
                        
                            <label class="d-none">Descripcion</label>
                            <textarea class="form-control d-none" style="resize: none;" name="empresaRepresentadaDescripcion{{ $item['id'] }}">{{ $item['descripcion'] }}</textarea>
                        </div>
                        <div class="col-12 col-md-2 px-2">
                            <input hidden id="inputEmpresaRepresentadaImagenExist{{ $item['id'] }}" type="file"
                                name="empresaRepresentadaImagenExist{{ $item['id'] }}"
                                onchange="if (validateFile(this, 'imageEmpresaRepresentadaExist{{ $item['id'] }}', @json($viewData['catalogo']['max_file_size']))) 
                        {$('#empresaRepresentadaImagenExistSmall{{ $item['id'] }}')
                            .html(document.getElementById('inputEmpresaRepresentadaImagenExist{{ $item['id'] }}').files.length+' archivo cargado'); 
                        }" />

                            <input hidden id="deleteEmpresaRepresentadaExist{{ $item['id'] }}"
                                name="deleteEmpresaRepresentadaExist{{ $item['id'] }}" value="0" />

                            <button class="btn btn-success" type="button"
                                onclick="$('#inputEmpresaRepresentadaImagenExist{{ $item['id'] }}').click();">Cargar
                                Imagen</button><br />
                            <small id="empresaRepresentadaImagenExistSmall{{ $item['id'] }}"></small><br />
                            <button class="btn btn-danger mt-1" type="button"
                                onclick="if (confirm('Esta a punto de eliminar esta empresa representada'))
                                    {
                                        $('#deleteEmpresaRepresentadaExist'+{{ $item['id'] }}).val('1');
                                        $('#btnEliminarEmpresaRepresentada{{ $item['id'] }}').html('Eliminado')
                                    }">
                                Eliminar
                            </button>
                        </div>
                        <div class="col-12 col-md-4">
                            <img id="imageEmpresaRepresentadaExist{{ $item['id'] }}"
                                src="{{ $item['path_logo'] ? asset('storage/empresa/empresaRepresentadaLogo/' . $item['path_logo']) : '' }}"
                                style="max-width: 250px; max-height: 250px;" />
                            <br />
                        </div>
                    </div>
                    <hr />
                </div>
            @endforeach
        @endif
    </div>
</div>
<script type="text/javascript">
    let rowEmpresaRepresentada = 0;

    function addEmpresaRepresentada() {
        let div = $('#empresasRepresentadas');
        let empresaRepresentada = `
            <div id="rowEmpresaRepresentada` + rowEmpresaRepresentada + `" class="col-12">
                <div class="row no-gutters">
                    <div class="col-12 col-md-6 px-2">
                        <label>Nombre Empresa Representada</label>
                        <input required class="form-control" name="empresaRepresentadaNombre[]" />
                    
                        <label class="d-none">Descripcion</label>
                        <textarea class="form-control d-none" style="resize: none;" name="empresaRepresentadaDescripcion[]"></textarea>
                    </div>
                    <div class="col-12 col-md-2 px-2">
                        <input type="hidden" name="empresaRepresentadaImagenes[]" value="">
                        <input hidden id="empresaRepresentadaImagen` + rowEmpresaRepresentada + `" 
                        type="file" 
                        name="empresaRepresentadaImagenes[]" 
                        onchange="if (validateFile(this,'imageEmpresaRepresentada` + rowEmpresaRepresentada + `', @json($viewData['catalogo']['max_file_size']))) 
                            {$('#empresaRepresentadaImagenSmall` + rowEmpresaRepresentada + `')
                                .html(document.getElementById('empresaRepresentadaImagen` +
                rowEmpresaRepresentada + `').files.length+' archivo cargado'); 
                            }" />
                        <button class="btn btn-success" type="button" onclick="$('#empresaRepresentadaImagen` +
                rowEmpresaRepresentada + `').click();">Cargar Imagen</button><br/>
                        <small id="empresaRepresentadaImagenSmall` + rowEmpresaRepresentada + `"></small><br/>
                        <button class="btn btn-danger mt-1" type="button" onclick="if (confirm('Esta a punto de eliminar esta empresa representada'))
                        {$('#rowEmpresaRepresentada` + rowEmpresaRepresentada + `').remove();}">Eliminar</button>
                    </div>
                    <div class="col-12 col-md-4">
                        <img id="imageEmpresaRepresentada` + rowEmpresaRepresentada + `"
                            style="max-width: 250px; max-height: 250px;" />
                        <br />
                    </div>
                </div>
                <hr/>
            </div>
        `;
        div.append(empresaRepresentada);
        rowEmpresaRepresentada += 1;
    }
</script>
