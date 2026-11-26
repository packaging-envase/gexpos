@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            <div class="container">
                <h2 class="mb-3">
                    <u>Whatsapp - Mensajes Predefinidos</u>
                </h2>
                @include('layouts.messages')

                <form id="formWhatsappPredefinido" action="{{ route('whatsapp.update-store-predefinido') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input hidden name="idExposicion" value="{{ $viewData['exposicion']['id'] }}" />
                    <input id="idPredefinido" hidden name="idWhatsappPredefinido" value="" />
                    <div class="d-flex">
                        <div class="form-group mt-1 w-50 pe-1">
                            <label>Tipo</label>
                            <select id="predefinidoSelect" class="form-control">
                                <option value="" selected>Nuevo</option>
                                @foreach ($viewData['predefinidos'] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['descripcion'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-1 w-50 ps-1">
                            <label for="description">Descripcion</label>
                            <input id="description" name="description" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="form-group mt-1">
                        <label for="texto">Texto de mensaje</label>
                        <textarea id="texto" name="texto" class="form-control" required></textarea>
                    </div>

                    <div class="form-group mt-1">
                        <label for="file">Nuevo Archivo</label>
                        <input id="file_path" type="file" name="file_path" class="form-control">
                    </div>
                    <div id="linkContainer" style="display: none;">
                        <label>Archivo Existente</label><br/>
                        <a href="" id="link_file_path" style="display: none;" target="_blank"></a><br/>
                        <input id="fileDeleted" name="fileDeleted" value="false" hidden />
                    </div>
                    <!-- Button trigger modal -->
                    <button type="submit" class="btn btn-success float-end mt-1 ms-2">Guardar</button>
                    <button type="button" class="btn btn-warning float-end mt-1" data-bs-toggle="modal"
                        data-bs-target="#enviarPredefinidioModal">
                        Guardar Enviar
                    </button>
                    <div style="clear: both;"></div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="enviarPredefinidioModal" tabindex="-1" aria-labelledby="enviarPredefinidioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enviarPredefinidioModalLabel">Seleccionar Expositores</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <table class="table mx-auto">
                        <tr>
                            <td colspan="2">
                                <input id="expositorTodos" type="checkbox" value="todos"
                                    onchange="selectAllExpositores(this)" onclick="event.stopPropagation();" />
                                <label for="expositorTodos">Todos</label>
                            </td>
                        </tr>
                        <tr class="table-dark">
                            <th>Nombre Usuario</th>
                            <th>Whatsapp</th>
                        </tr>
                        @foreach ($viewData['expositores'] as $expositor)
                            <tr onclick="checkExpositor({{ $expositor['id'] }}, {{ $expositor['whatsapp'] }})">
                                <td>
                                    <input id="expositor{{ $expositor['id'] }}" type="checkbox"
                                        value="{{ $expositor['id'] }}" name="idExpositores[]"
                                        data-whatsapp="{{ $expositor['whatsapp'] }}" />
                                    <label>{{ $expositor['usuario'] }}</label>
                                </td>
                                <td>{{ $expositor['whatsapp'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="agregarExpositoresYEnviarFormulario()">
                        Guardar y enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let predefinidos = @json($viewData['predefinidos']);

        function selectAllExpositores(checkbox) {
            $('input[id^="expositor"]').each(function() {
                if ($(this).data('whatsapp')) {
                    $(this).prop('checked', checkbox.checked);
                }
            });
        }

        function agregarExpositoresYEnviarFormulario() {
            if (!$('#texto').val()) {
                alert('Debe ingresar un texto del mensaje');
                return;
            } 
            if (!$('#description').val()) {
                alert('Debe ingresar una descripcion del mensaje predefinido');
                return;
            }
            const form = $('#formWhatsappPredefinido');

            $('input[type="checkbox"][name="idExpositores[]"]:checked').each(function() {
                const checkboxClone = $(this).clone();
                form.append(checkboxClone);
            });

            form.submit();
        }

        function checkExpositor(expositorId, whatsapp) {
            if (!whatsapp) {
                alert('El expositor no posee número de whatsapp');
            } else {
                // Obtiene el ID del expositor de la fila
                console.log('this', this)

                checkbox = $('#expositor' + expositorId);
                checkbox.prop('checked', !checkbox.prop('checked'));

                console.log('checkbox: ', checkbox.prop('checked'));
                console.log('checkbox: ', checkbox);
            }

            return;
        }

        function eliminarArchivo() {
            $('#fileDeleted').val('true');
            $('#link_file_path').hide();
            $('#fileGroup').show();
            $('#buttonEliminarArchivo').hide();
            $('#linkContainer').hide();
        }

        $(document).ready(function() {

            //$('tr[data-expositor-id]').click(checkExpositor);

            $('input[type="checkbox"][name="idExpositores[]"]').click(function(event) {
                event.target.checked = !event.target.checked;
            });

            $('#predefinidoSelect').change(function() {

                let selectedId = $(this).val();

                // Limpiar los campos
                $('#description').val('');
                $('#texto').val('');
                $('#file_path').val('');
                $('#link_file_path').hide();
                $('#fileGroup').show();
                $('#idPredefinido').val();

                // Verificar si se seleccionó "Nuevo"
                if (selectedId === "") {
                    return; // Si es "Nuevo", salimos
                }

                // Buscar el predefinido seleccionado
                const predefinido = predefinidos.find(item => item.id == selectedId);

                if (predefinido) {
                    $('#idPredefinido').val(predefinido.id);
                    // Rellenar los campos con los datos del predefinido
                    $('#description').val(predefinido.descripcion);
                    $('#texto').val(predefinido.texto);

                    // Manejar el archivo
                    if (predefinido.file_path) {
                        // Mostrar el enlace del archivo
                        $('#link_file_path').attr('href', predefinido.file_path)
                            .text(predefinido.file_name)
                            .show();
                        $('#linkContainer').show();
                        // Ocultar el input de archivo
                        $('#fileGroup').hide();
                        $('#linkContainer').append(`
                            <button id="buttonEliminarArchivo" type="button" class="btn btn-danger" onclick="eliminarArchivo()">Eliminar Archivo</button>
                        `);
                        $('#fileDeleted').val('false');
                    } else {
                        // Si no hay archivo, mostrar el input
                        $('#link_file_path').hide();
                        $('#fileGroup').show();
                    }
                }
            });
        });
    </script>
@endsection
