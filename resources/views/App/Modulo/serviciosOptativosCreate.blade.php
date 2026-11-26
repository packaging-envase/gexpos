@extends('layouts.app')

@section('content')
    <style>
        .dt-search {
            float: right;
        }

        .dt-buttons {
            float: left;
        }
    </style>
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container mb-5 mt-1">

        <div class="bg-white shadow p-2">
            <h2 class="mb-3">
                <u>Modulo - Proveedores Optativos</u>
            </h2>

            @include('layouts.messages')

            <div class="mt-2">
                <form method="POST" action="{{ route('modulo.servicios-optativos.update-store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    @if ($viewData['item'])
                        <input hidden name="id" value="{{ $viewData['item']['id'] }}" />
                    @endif

                    <input hidden name="idExposicion" value="{{ $viewData['exposicion']['id'] }}" />

                    <hr />

                    <a class="btn btn-primary float-end"
                        href="{{ route('modulo.servicios-optativos.panel', ['id' => $viewData['exposicion']['id']]) }}">Listado</a>

                    <h2 class=""><u>{{ isset($viewData['item']) ? 'Editar' : 'Nuevo' }} Proveedor Optativo</u></h2>

                    <hr />                    

                    <div class="d-flex">
                        <div>
                            <h4><u>Logo</u></h4>
                            <input id="fileLogo" hidden type="file" class="form-control" style="width: 500px;"
                                name="logo">
                            @if ($viewData['item'] && $viewData['item']['logo'])
                                <img src="{{ asset('storage/serviciosOptativos/logos/' . $viewData['item']['logo']) }}"
                                    alt="Logo" style="max-width: 250px;" /><br />
                            @endif
                            <button type="button" class="btn btn-success mt-2" onclick="$('#fileLogo').click()">Cambiar
                                Logo</button><br />
                        </div>
                        <div>
                            <h4 class="m-0"><u>Nombre</u></h4>
                            <input class="form-control" name="nombre" style="min-width: 400px;"
                                value="{{ isset($viewData['item']) ? $viewData['item']['nombre'] : '' }}" />
                        </div>
                    </div>

                    <hr />

                    <div class="">
                        <h4 for="servicioOptativoActive"><u>Activo</u></h4>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="servicioOptativoActive"
                                name="active"
                                {{ isset($viewData['item']) && $viewData['item']['active'] ? 'checked=true' : '' }}>
                        </div>
                    </div>

                    <hr />

                    <h4><u>Archivos</u></h4>
                    <div>
                        <?php 
                            //dd($viewData['item']->moduloServiciosOptativosFile); 
                        ?> 
                        @if (isset($viewData['item']->moduloServiciosOptativosFile) && count($viewData['item']->moduloServiciosOptativosFile))
                            @foreach ($viewData['item']->moduloServiciosOptativosFile as $file)
                                <a target="_blank" href="{{route('expositor.modulo.servicios-optativos.download-file', ['path' => $file->path])}}">{{$file->name}}</a>
                                <a href="{{route('modulo.servicios-optativos.delete-file', ['id' => $file->id])}}" class="btn btn-danger btn-sm ms-2" onclick="return confirm('¿Seguro que desea eliminar el archivo {{$file->name}}?');">Eliminar</a>
                                <br/>
                            @endforeach
                        @endif
                    </div>
                    <div id="fileList">
                        <!-- Los archivos seleccionados se agregarán aquí -->
                    </div>
                    <button type="button" id="addFiles" class="btn btn-primary">Agregar Archivos</button>
                    <input hidden type="file" id="fileInput" multiple class="form-control mb-3" name="archivos[]" />

                    <hr />

                    <h4><u>Descripción</u></h4>
                    <textarea id="mytextarea" name="descripcion" style="resize: none !important;">{{ $viewData['item'] ? $viewData['item']['descripcion'] : '' }}</textarea>
                    <script type="text/javascript">
                        tinymce.init({
                            selector: '#mytextarea',
                            license_key: 'gpl',
                            resize: false,
                        });
                    </script>

                    <button class="btn btn-success float-end mt-2" type="submit">Guardar</button>
                    <div style="clear: both;"></div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let selectedFiles = [];

        $(document).ready(function() {
            $('#addFiles').on('click', function() {
                $('#fileInput').val('');
                $('#fileInput').click();
            });

            // Evento para manejar la selección de archivos
            $('#fileInput').on('change', function() {

                const files = Array.from($(this)[0].files);
                selectedFiles = selectedFiles.concat(files); // Agrega archivos a la variable global

                // Actualiza la lista de archivos en la interfaz de usuario
                $('#fileList').empty();

                selectedFiles.forEach((file, index) => {
                    const fileItem = $('<div class="mb-2">', {
                        class: 'file-item'
                    });
                    const fileName = $('<span>').text(file.name);
                    const removeButton = $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-sm ms-2',
                        text: 'Eliminar'
                    }).on('click', function() {
                        selectedFiles.splice(index,
                            1); // Elimina archivo de la variable global
                        fileItem.remove(); // Elimina archivo de la lista en la interfaz
                        popupateFiles();
                    });
                    fileItem.append(fileName).append(removeButton);
                    $('#fileList').append(fileItem);
                });

                popupateFiles();
            });
        });

        function popupateFiles() {
            $('#fileInput').val('');
            // Crear un nuevo DataTransfer para simular la selección de archivos
            let dataTransfer = new DataTransfer();
            // Añadir archivos al DataTransfer
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            // Asignar el DataTransfer al input de archivos
            $('#fileInput')[0].files = dataTransfer.files;

            console.log(selectedFiles);
        }
    </script>
@endsection
