@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Requerimientos - Elementos Opcionales</u>
            </h2>

            @include('layouts.messages')

            <h5><b>Elementos Opcionales</b></h5>
            <div class="table-responsive">
                <table id="tableRequerimientosElementosRechazados" class="table"
                    style="white-space: nowrap; width: 100%;">
                    <thead>
                        <tr>
                            <th>Expositor - Razon Social</th>
                            <th>Stands</th>
                            <th>Elemento Solicitado</th>
                            <th>Observacion</th>
                            <th>Aceptado/Rechazado</th>
                            <th>Creado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['elementos'] as $expositor)
                            <tr>
                                <td>{{ $expositor['razon_social'] }}</td>
                                <td>{{ $expositor['numeros_stand'] }}</td>
                                <td>{{ $expositor['titulo'] }}</td>
                                <td>{{ $expositor['observacion'] }}</td>
                                <td>{{ $expositor['rechazado'] == 1 ? 'rechazado' : 'aceptado' }}</td>
                                <td>{{ \Carbon\Carbon::parse($expositor['created_at'])->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#tableRequerimientosElementosRechazados').DataTable({
                        columnDefs: [{
                            targets: '_all',
                            className: 'text-start'
                        }],
                        dom: 'Bfrtip', // Asegúrate de tener el dom correcto para los botones
                        buttons: [{
                            extend: 'excelHtml5',
                            title: "Elementos Opcionales Solicitados.xlsx" // Aquí cambias el nombre del archivo
                        }],
                        layout: {
                            topStart: {
                                buttons: ['excelHtml5']
                            }
                        }
                    });

                });
            </script>
        </div>
    </div>
    <style>
        .dt-search {
            float: right;
        }

        .dt-buttons {
            float: left;
        }
    </style>
@endsection
