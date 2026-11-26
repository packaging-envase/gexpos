@extends('layouts.app')

@section('content')

    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Requerimientos - Electricidad</u>
            </h2>

            @include('layouts.messages')

        <hr/>
        <div class="table-responsive">
            <table id="tableRequerimientosElectricidad" class="table" style="white-space: nowrap; width: 100%;">
                <thead>
                    <tr>
                        <th>Nº Stand</th>
                        <th>Empresa</th>
                        <th>Tiene Maquinas</th>
                        <th>Peso y Medidas</th>
                        <th>Pondrá en Funcionamiento</th>
                        <th>Monofásica KW</th>
                        <th>Trifásica KW</th>
                        <th>Nombre Encargado Pedido</th>
                        <th>Cargo</th>
                        <th>Teléfono</th>
                        <th>Celular</th>
                        <th>E-Mail</th>
                        <th>Creado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['requerimientosElectricidad'] as $item)
                        <tr>
                            <td>{{ $item['numero'] }}</td>
                            <td>{{ $item['razon_social'] }}{{ $item['nombre_fantasia'] ? ' - ' . $item['nombre_fantasia'] : '' }}
                            </td>
                            <td>{{ $item['tiene_maquinas'] ? 'Si' : 'No' }}</td>
                            @if ($item['tiene_maquinas'])
                                <td>{{ $item['peso_medida'] }}</td>
                                <td>{{ $item['funcionan'] ? 'Si' : 'No' }}</td>
                            @else
                                <td></td>
                                <td></td>
                            @endif
                            <td>{{ $item['monofasica'] }}</td>
                            <td>{{ $item['trifasica'] }}</td>
                            <td>{{ $item['apellido_nombre'] }}</td>
                            <td>{{ $item['cargo'] }}</td>
                            <td>{{ $item['telefono'] }}</td>
                            <td>{{ $item['celular'] }}</td>
                            <td>{{ $item['email'] }}</td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" action="{{route('modulo.electricidad.delete')}}">
                                    @csrf
                                    <input hidden value="{{$item['id']}}" name="id"/>
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableRequerimientosElectricidad').DataTable({
                columnDefs: [{
                    targets: '_all',
                    className: 'text-start'
                }],
                dom: 'Bfrtip', // Asegúrate de tener el dom correcto para los botones
                buttons: [{
                    extend: 'excelHtml5',
                    title: "{{$viewData['exposicion']['nombre']}} - Energia Extra" // Aquí cambias el nombre del archivo
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
