<!doctype html>
@extends('layouts.app')

@section('content')
    <style>
        .file-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .file-item button {
            margin-left: 10px;
        }
    </style>
    </style>
    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    @if (!Auth::guard('expositor')->check())
        @include('layouts.navigation-menu-2', ['active' => 'exposicion'])
    @endif

    <div class="container">

        @include('App.ModuloExpositor.header', ['title' => 'Planos de Stand', 'requerimientos' => true])

        <div class="expositor-modulo-invitaciones shadow-sm mt-3 border p-3 bg-white mb-5"
            style="min-height: calc(100vh - 55px);">

            @include('layouts.messages')

            <div class="float-end">
                <b>Fecha de Cierre de Modulo: {{ \DateTime::createFromFormat('Y-m-d', $viewData['plano_stand']['cierre'])->format('d/m/Y') }}</b>
            </div>

            <div class="mt-2">
                <?php echo $viewData['plano_stand']['descripcion']; ?>
            </div>

            <hr />

            {{--
            <form id="formPlanoStand" class="container p-2" method="POST"
                action="{{ route('expositor.modulo.plano-stand.update-store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-12">
                        @if ($viewData['pedido'])
                            <?php
                            $statusColor = '';
                            switch ($viewData['pedido']['status']) {
                                case 'presentado':
                                    $statusColor = 'primary';
                                    break;
                                case 'pedido de correccion':
                                    $statusColor = 'danger';
                                    break;
                                case 'conforme a reglamento':
                                    $statusColor = 'success';
                                    break;
                                default:
                                    break;
                            }
                            ?>
                            <div class="btn btn-{{ $statusColor }} text-{{ $statusColor }} no-hover float-end"
                                style="background-color: whitesmoke;">
                                {{ $viewData['pedido']['status'] }}
                            </div>
                        @endif
                        <h4><u>Envío de Planos</u></h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label>Nombre</label>
                        <input class="form-control" name="nombre"
                            value="{{ $viewData['pedido'] ? $viewData['pedido']['nombre'] : '' }}"
                            {{ !Auth::guard('expositor')->check() ? 'disabled=true' : '' }} />
                    </div>
                    <div class="col-12 col-md-6">
                        <label>Apellido</label>
                        <input class="form-control" name="apellido"
                            value="{{ $viewData['pedido'] ? $viewData['pedido']['apellido'] : '' }}"
                            {{ !Auth::guard('expositor')->check() ? 'disabled=true' : '' }} />
                    </div>
                    <div class="col-12 col-md-6">
                        <label>Teléfono</label>
                        <input class="form-control" name="telefono"
                            value="{{ $viewData['pedido'] ? $viewData['pedido']['telefono'] : '' }}"
                            {{ !Auth::guard('expositor')->check() ? 'disabled=true' : '' }} />
                    </div>
                    <div class="col-12 col-md-6">
                        <label>Email</label>
                        <input class="form-control" name="email"
                            value="{{ $viewData['pedido'] ? $viewData['pedido']['email'] : '' }}"
                            {{ !Auth::guard('expositor')->check() ? 'disabled=true' : '' }} />
                    </div>

                    <?php
                    //dd($viewData['pedido']->moduloPlanoStandComentario);
                    ?>
                    <div class="col-12 col-md-6">
                        <label>Comentarios:</label>
                        <div id="box-comentarios" class="shadow-sm border p-2 position-relative"
                            style="height: 400px; overflow-y: auto;">
                            @if ($viewData['pedido'] && count($viewData['pedido']->moduloPlanoStandComentario))
                                @foreach ($viewData['pedido']->moduloPlanoStandComentario as $comentario)
                                    <div style="clear:both;"></div>
                                    <div class="border p-1 mb-2 {{ $comentario['id_expositor'] ? 'float-end' : '' }}"
                                        style="background-color: whitesmoke; width: fit-content;">
                                        {{ $comentario['id_expositor'] ? $viewData['pedido']['nombre'] : 'Arquitecto' }} -
                                        {{ substr($comentario['created_at'], 0, 11) }}<br />
                                        {{ $comentario['descripcion'] }}
                                    </div>
                                @endforeach
                            @endif
                            @if (Auth::guard('expositor')->check())
                                <div>
                                    <textarea placeholder="Agregar Comentario" class="form-control" name="comentario" style="resize: none;"></textarea>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mt-4">
                        @if ($viewData['pedido'] && count($viewData['pedido']->expositorModuloPlanoStandFile))
                            <?php $numeroEnvio = 0; ?>
                            @foreach ($viewData['pedido']->expositorModuloPlanoStandFile as $file)
                                @if ($numeroEnvio != $file->numero_envio)
                                    <?php $numeroEnvio = $file->numero_envio; ?>
                                    <u>Envío Expositor Nº {{ $numeroEnvio }}</u><br />
                                @endif
                                <a href="{{ route('expositor.modulo.plano-stand.download-file', ['path' => $file['path']]) }}"
                                    style="{{ !$file['aprobado'] ? 'color: red;' : '' }}">
                                    {{ substr($file['created_at'], 0, 11) }} ||
                                    {{ $file['aprobado'] ? 'Plano conforme a reglamento' : 'No Aprobado' }} ||
                                    {{ $file['name'] }}
                                </a>
                                <br />
                            @endforeach
                        @endif
                        <div id="fileList">
                            <!-- Los archivos seleccionados se agregarán aquí -->
                        </div>
                        @if (Auth::guard('expositor')->check())
                            <button type="button" id="addFiles" class="btn btn-success">Agregar Archivos</button>
                        @endif
                    </div>
                    @if (Auth::guard('expositor')->check())
                        <div class="col-12 mt-2 text-center">
                            @if (
                                $viewData['pedido'] &&
                                    ($viewData['pedido']['status'] == 'conforme a reglamento' ||
                                        $viewData['pedido']['status'] == 'conforme a reglamento'))
                                <button type="submit" class="btn btn-warning" style="font-size: 18px; width: 210px;"
                                    onclick="confirm('Está por enviar un nuevo requerimiento')">Nuevo Requerimiento</button>
                            @else
                                <button type="submit" class="btn btn-success"
                                    style="font-size: 18px; width: 150px;">Enviar</button>
                            @endif
                        </div>
                    @endif

                    <input hidden type="file" id="fileInput" multiple class="form-control mb-3" name="archivos[]"
                        accept=".jpg,.jpeg,.png,.pdf,.dwg,.dxf" />
                </div>
            </form>
            --}}
        </div>
    </div>

    <script type="text/javascript">
        let selectedFiles = [];

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

        $(document).ready(function() {

            let div = $('#box-comentarios');
            div.scrollTop(div[0].scrollHeight);
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
                    const fileItem = $('<div>', {
                        class: 'file-item'
                    });
                    const fileName = $('<span>').text(file.name);
                    const removeButton = $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-sm',
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

            $('#formPlanoStand').submit(function(event) {
                // Prevenir el envío automático del formulario
                event.preventDefault();

                // Verificar si hay archivos seleccionados
                if ($('#fileInput').get(0).files.length === 0) {
                    // Preguntar si desea continuar sin archivo
                    if (confirm("No agregó ningún archivo ¿Desea continuar?")) {
                        // Si acepta, enviar el formulario
                        this.submit();
                    }
                } else {
                    // Si hay archivos seleccionados, enviar el formulario
                    this.submit();
                }
            });
        });
    </script>
@endsection
