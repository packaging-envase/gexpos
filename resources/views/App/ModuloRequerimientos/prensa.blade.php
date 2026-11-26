@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Requerimientos - Invitaciones</u>
            </h2>

            @include('layouts.messages')

            <div class="table-responsive">
                <table id="tableRequerimientosPrensa" class="table" style="white-space: nowrap; width: 100%;">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Apellido Nombre</th>
                            <th>Whatsapp</th>
                            <th>Tipo de Medio de Comunicación</th>
                            <th>Razón Social de la Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['acreditaciones'] as $acreditacion)
                            <tr>
                                <td>{{ $acreditacion->dni }}</td>
                                <td>{{ $acreditacion->apellido_nombre }}</td>
                                <td>{{ $acreditacion->whatsapp }}</td>
                                <td>{{ $acreditacion->medio }}</td>
                                <td>{{ $acreditacion->razon_social }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tableRequerimientosPrensa').DataTable({
                columnDefs: [{
                    targets: '_all',
                    className: 'text-start'
                }],
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: "{{ $viewData['exposicion']['nombre'] }} - Acreditaciones"
                }],
                layout: {
                    topStart: {
                        buttons: ['excelHtml5']
                    }
                }
            });

        });
    </script>
@endsection
