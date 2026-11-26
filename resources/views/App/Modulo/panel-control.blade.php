@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">

            <div class="container">
                <h2 class="mb-3">
                    <u>Modulo - Panel de Control</u>
                </h2>

                @include('layouts.messages')

                {{--
                <a href="{{ '/modulo/panel-control?emailRecordatorio=true&id=' . $viewData['exposicion']['id'] }}"
                    class="btn btn-primary" id="btnEnviarRecordatorio">Enviar Recordatorio</a>
                --}}

                <table id="tableRequerimientosPanelControl" class="table table-striped">
                    <thead>
                        <tr class="table-dark">
                            <th style="position: sticky; top: 0; white-space: nowrap;">Razon Social</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Stand</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Catalogo</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Deposito en Garantia</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Electricidad Extra</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Elementos Opcionales</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Invitacion</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Sala Conferencia</th>
                            <th style="position: sticky; top: 0; white-space: nowrap;">Credenciales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['expositores'] as $expositor)
                            <tr>
                                <td>{{ $expositor['razon_social'] }}</td>
                                <td>{{ isset($expositor['stand']) ? $expositor['stand'] : '' }}</td>
                                <td>{{ $expositor['catalogo'] }}</td>
                                <td>{{ $expositor['deposito'] }}</td>
                                <td>{{ $expositor['electricidad'] }}</td>
                                <td>{{ $expositor['elementos'] }}</td>
                                <td>{{ $expositor['invitacion'] }}</td>
                                <td>{{ $expositor['conferencia'] }}</td>
                                <td>{{ $expositor['credencial'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tableRequerimientosPanelControl').DataTable({
                dom: 'Bfrtip', // muestra botones
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel',
                    title: 'Requerimientos Panel Control',
                    className: 'btn btn-success'
                }],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-start'
                }],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
                },
                initComplete: function() {
                    var api = this.api();
                    var container = $(api.buttons().container());

                    if (!$('#btnEnviarRecordatorio').length) {
                        container.prepend(`
                            <a href="/modulo/panel-control?emailRecordatorio=true&id={{ $viewData['exposicion']['id'] }}" 
                                class="btn btn-primary me-2" 
                                id="btnEnviarRecordatorio"
                                onclick="return confirm('¿Seguro que querés enviar el recordatorio?')">
                                Enviar Recordatorio
                            </a>
                        `);
                    }
                }
            });
        });
    </script>
    <style>
        /* Asegura que el botón de exportar esté alineado a la derecha */
        .dt-buttons {
            display: flex;
            margin-bottom: 10px;
        }
    </style>
@endsection
