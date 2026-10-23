<!doctype html>
@extends('layouts.app')

<!-- Agregar las referencias a DataTables y su extensión Buttons para exportación CSV -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js">
</script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.2.0/jszip.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js">
</script>


@section('content')
    <?php
    $credencialActive = !$viewData['credencial_vencimiento'] || $viewData['credencial_vencimiento']['value_date'] >= date('Y-m-d');
    ?>

    <div class="container">
        @include('App.ModuloExpositor.header', [
            'title' => 'Solicitud de Credenciales',
            'requerimientos' => false,
        ])
    </div>

    <div class="container">
        <div class="mt-3 expositor-modulo-invitaciones shadow-sm p-3 bg-white border mb-5"
            style="min-height: calc(100vh - 55px);">
            @include('layouts.messages')

            @if ($viewData['credencialesPedidasQuantity'] && !(isset($viewData['credencial']) && $viewData['credencial']))
                <div class="positoin-relative">
                    <h4 class="position-absolute" style="transform: translateX(-50%); left: 50%;">
                        <u>Credenciales Solicitadas</u>
                    </h4>
                    <div class="float-end">
                        <b>Fecha de Cierre de Modulo:
                            {{ \DateTime::createFromFormat('Y-m-d', $viewData['credencial_vencimiento']['value_date'])->format('d/m/Y') }}</b>
                    </div>
                </div>

                <table class="table table-bordered mt-5">
                    <thead>
                        <tr class="table-dark">
                            <th class="text-center">
                                <button id="credencialesTodos" class="btn btn-primary show-details"
                                    data-key="todos">Credenciales Totales
                                    ({{ $viewData['credencialesPedidasQuantity'] }})</button>
                            </th>
                            @foreach (array_keys($viewData['credencialesSolicitadas']) as $key)
                                <th class="text-center">
                                    <button class="btn btn-primary show-details"
                                        data-key="{{ $key }}">{{ $key }}
                                        ({{ count($viewData['credencialesSolicitadas'][$key]) }})
                                    </button>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                </table>

                <div id="details" style="display: none;">
                    <table class="table table-striped w-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Empresa</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Descripción</th> <!-- Nueva columna de Descripción -->
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="details-body">

                            <!-- Los detalles se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <script>
                    $(document).ready(function() {
                        // Inicializar DataTable con botón de exportación a CSV
                        var table = $('#details-body').closest('table').DataTable({
                            dom: 'Bfrtip', // Definir la posición de los botones
                            buttons: [{
                                extend: 'csvHtml5', // Exportar a CSV
                                text: 'Exportar a CSV', // Texto del botón
                                title: 'Credenciales Solicitadas' // Título del archivo exportado
                            }]
                        });

                        var lastClickedButton = null;

                        $(".show-details").click(function() {
                            var key = $(this).data("key");
                            var data = @json($viewData['credencialesSolicitadas']);

                            // Limpiar la tabla de detalles
                            table.clear();

                            // Mostrar todas las credenciales si se hace clic en "Todos"
                            if (key === "todos") {
                                var allData = Object.values(data).flat();

                                allData.forEach(function(item) {
                                    var updateUrl =
                                        "{{ route('expositor.modulo.credencial.index', ['idCredencial' => '__id__']) }}"
                                        .replace('__id__', item.id);
                                    var deleteUrl =
                                        "{{ route('expositor.modulo.credencial.delete', ['idCredencial' => '__id__']) }}"
                                        .replace('__id__', item.id);

                                    let btnSuccess = @json($credencialActive) ?
                                        '<a class="btn btn-success" href="' + updateUrl + '">Editar</a>' : '';
                                    let btnDelete = @json($credencialActive) ?
                                        '<a class="btn btn-danger" href="' + deleteUrl + '">Eliminar</a>' : '';

                                    table.row.add([
                                        btnSuccess,
                                        item.nombre,
                                        item.apellido,
                                        item.empresa,
                                        item.cargo,
                                        item.email,
                                        item.telefono,
                                        item.descripcion,
                                        btnDelete,
                                    ]).draw();
                                });
                            } else {
                                if (data[key]) {
                                    data[key].forEach(function(item) {
                                        let updateUrl =
                                            "{{ route('expositor.modulo.credencial.index', ['idCredencial' => '__id__']) }}"
                                            .replace('__id__', item.id);
                                        let deleteUrl =
                                            "{{ route('expositor.modulo.credencial.delete', ['idCredencial' => '__id__']) }}"
                                            .replace('__id__', item.id);

                                        let btnSuccess = @json($credencialActive) ?
                                            '<a class="btn btn-success" href="' + updateUrl + '">Editar</a>' :
                                            '';
                                        let btnDelete = @json($credencialActive) ?
                                            '<a class="btn btn-danger" href="' + deleteUrl + '">Eliminar</a>' :
                                            '';

                                        table.row.add([
                                            btnSuccess,
                                            item.nombre,
                                            item.apellido,
                                            item.empresa,
                                            item.cargo,
                                            item.email,
                                            item.telefono,
                                            item.descripcion,
                                            btnDelete
                                        ]).draw();
                                    });
                                }
                            }

                            // Mostrar los detalles
                            $("#details").show();

                            // Cambiar el estado de los botones
                            if (lastClickedButton) {
                                lastClickedButton.removeClass('btn-success').addClass('btn-primary');
                            }

                            $(this).removeClass('btn-primary').addClass('btn-success');

                            lastClickedButton = $(this);
                        });
                    });
                </script>
            @endif

            <hr />

            @if ($credencialActive)
                <form id="formExpositorCredencial" method="POST"
                    action="{{ route('expositor.modulo.credencial.update-store') }}">
                    @if (isset($viewData['credencial']) && $viewData['credencial'])
                        <input hidden name="idCredencial" value="{{ $viewData['credencial']['id'] }}" />
                    @endif
                    @csrf
                    <small class="float-end text-secondary">
                        (último día para actualizar la petición
                        <b>{{ $viewData['credencial_vencimiento'] ? date('d/m/Y', strtotime($viewData['credencial_vencimiento']['value_date'])) : '' }}</b>)
                    </small>

                    <h4 class="">
                        @if (isset($viewData['credencial']) && $viewData['credencial'])
                            Editar Credencial
                        @else
                            Credencial a solicitar:
                        @endif
                    </h4>
                    <div class="d-flex">
                        <div>
                            <select class="form-control" name="id_credencial_tipo" onchange="selectCredencial(event)">
                                <option value="">Seleccionar Credencial</option>
                                @foreach ($viewData['credencial_tipo_exposicion'] as $keyCredencial => $item)
                                    <option value="{{ $item['id_credencial_tipo'] }}"
                                        {{ isset($viewData['credencial']) && $viewData['credencial'] && $viewData['credencial']['id_credencial_tipo'] == $item['id_credencial_tipo'] ? 'selected=true' : '' }}>
                                        {{ $item->credencialTipo['descripcion'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="divCredencial" class="d-none ms-2"
                            class="{{ isset($viewData['credencial']) && $viewData['credencial'] ? 'd-none' : '' }}">
                            <div class="d-flex" style="width: fit-content;">
                                <div class="d-flex justify-content-between">
                                    <input name="cantidad" class="form-control bg-white" style="max-width: 110px;"
                                        {{ !$credencialActive ? 'readonly=true' : '' }} placeholder="Cantidad" />
                                </div>
                                <button id="buttonSiguienteCredencial" class="btn btn-success ms-2" type="button"
                                    onclick="populateTableAddCredenciales()">
                                    Siguiente
                                </button>
                            </div>
                        </div>
                    </div>
                    <label>Máximo de credenciales: <span id="credencialMaximo"></span></label>
                    <small><b>Todos los campos son obligatorios</b></small>
                    <table id="tableAddCredenciales"
                        class="table mt-2 {{ isset($viewData['credencial']) && $viewData['credencial'] ? '' : 'd-none' }}">
                        <thead>
                            <tr class="table-dark">
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Empresa</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Whatsapp</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyCredenciales">
                            @if (isset($viewData['credencial']) && $viewData['credencial'])
                                <td>
                                    <input name="nombre" class="form-control bg-white"
                                        value="{{ $viewData['credencial']['nombre'] }}" />
                                </td>
                                <td>
                                    <input name="apellido" class="form-control bg-white"
                                        value="{{ $viewData['credencial']['apellido'] }}" />
                                </td>
                                <td>
                                    <input name="empresa" class="form-control bg-white"
                                        value="{{ $viewData['credencial']['empresa'] }}" />
                                </td>
                                <td>
                                    <input name="cargo" class="form-control bg-white"
                                        value="{{ $viewData['credencial']['cargo'] }}" />
                                </td>
                                <td>
                                    <input name="email" class="form-control bg-white"
                                        value="{{ $viewData['credencial']['email'] }}" />
                                </td>
                                <td>
                                    <input name="telefono" class="form-control bg-white"
                                        value="{{ $viewData['credencial']['telefono'] }}" />
                                </td>
                            @endif
                        </tbody>
                    </table>

                    @if (isset($viewData['credencial']) && $viewData['credencial'])
                        <button id="submitFormCredencial" type="submit" class="btn btn-success float-end">
                            Actualizar
                        </button>
                    @else
                        <button id="submitFormCredencial" type="submit" class="btn btn-success float-end d-none">
                            Enviar Solicitud
                        </button>
                    @endif
                    <div style="clear: both;"></div>
                </form>
            @endif
        </div>
    </div>

    <script>
        let credencialTipoExposicion = @json($viewData['credencial_tipo_exposicion']);
        let credencialesSolicitadas = @json($viewData['credencialesSolicitadas']);
        console.log(credencialesSolicitadas['Expositor']);
        let permitido = 0;

        function setMaximoCredencial() {
            // Obtener el value seleccionado del select
            let credencialId = divCredencial.value;

            // Buscar el objeto en el array credencialTipoExposicion
            let credencial = credencialTipoExposicion.find(item =>
                item.id_credencial_tipo == credencialId
            );

            console.log('credencial: ',credencial);

            permitido = credencial.permitido;

        }


        $('form').on('keydown', 'input, textarea', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevenir la acción por defecto (submit)
            }
        });

        function selectCredencial(e) {
            console.log($(e.target).val());

            if ($(e.target).val()) {
                // Obtener el value seleccionado del select
                let credencialId = $(e.target).val();

                // Buscar el objeto en el array credencialTipoExposicion
                let credencial = credencialTipoExposicion.find(item =>
                    item.id_credencial_tipo == credencialId
                );

                console.log('credencial: ',credencial.credencial_tipo.descripcion)

                permitido = credencial.permitido - credencialesSolicitadas[credencial.credencial_tipo.descripcion].length;

                console.log('asdasd: ', permitido)

                $('#credencialMaximo').html(credencial.permitido);

                $('#buttonSiguienteCredencial').removeClass('d-none');
                $('#tableAddCredenciales').addClass('d-none');
                $('#divCredencial').removeClass('d-none');
                $('input[name="cantidad"]').prop('readonly', false);
                $('input[name="cantidad"]').val(null);
                $('input[name="cantidad"]').removeClass('d-none');
            } else {
                $('input[name="cantidad"]').addClass('d-none');
                $('#buttonSiguienteCredencial').addClass('d-none');
                $('#divCredencial').addClass('d-none');
            }

            $('#tbodyCredenciales').empty();
            $('#submitFormCredencial').addClass('d-none');
        }

        function populateTableAddCredenciales() {

            if (!$('input[name="cantidad"]').val()) {
                alert('Debe ingresar una cantidad');
                return;
            }

            console.log('input[name="cantidad"]', $('input[name="cantidad"]').val());
            console.log('permitido: ',permitido);
            if ($('input[name="cantidad"]').val() > permitido) {
                alert('Cantidad máxima superada');
                return;
            }

            for (let i = 0; i < $('input[name="cantidad"]').val(); i++) {

                $('#tbodyCredenciales').append(`
                    <tr>
                        <td>
                            <input name="nombre[]" class="form-control bg-white" required/>
                        </td>
                        <td>
                            <input name="apellido[]" class="form-control bg-white" required/>
                        </td>
                        <td>
                            <input name="empresa[]" class="form-control bg-white" required/>
                        </td>
                        <td>
                            <input name="cargo[]" class="form-control bg-white" required/>
                        </td>
                        <td>
                            <input name="email[]" class="form-control bg-white" required/>
                        </td>
                        <td>
                            <input name="telefono[]" class="form-control bg-white" required/>
                        </td>
                    </tr>
                `);
            }

            $('#submitFormCredencial').removeClass('d-none');
            $('input[name="cantidad"]').prop('readonly', true);
            $('#buttonSiguienteCredencial').addClass('d-none');
            $('#tableAddCredenciales').removeClass('d-none');
        }

        //$('#').change(selectCredencial);

        $(document).ready(function() {
            $('#credencialesTodos').click();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formExpositorCredencial');
            const submitButton = document.getElementById('submitFormCredencial');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerText = 'Enviando...';
            });
        });

    </script>
@endsection
