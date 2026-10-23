@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Requerimientos - Invitaciones</u>
            </h2>

            @include('layouts.messages')

            <table id="tableInvitaciones" class="table table-striped">
                <thead>
                    <tr class="table-dark">
                        <th>Empresa</th>
                        <th>Dirección</th>
                        <th>Stands</th>
                        <th>Asignadas</th>
                        <th>Extra</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['invitaciones'] as $item)
                        <tr>
                            <td>
                                {{ $item['razon_social'] }} {{ $item['nombre_fantasia'] ? ' - ' . $item['nombre_fantasia'] : '' }}
                            </td>
                            <td>
                                {{ $item['direccion'] }}
                            </td>
                            <td>
                                {{$item['stands']}}
                            </td>
                            <td style="color: green;">
                                {{ $item['cantidad'] > $item['maxima'] ? $item['maxima'] : $item['cantidad'] }}
                            </td>
                            <td style="{{$item['cantidad'] > $item['maxima'] ? 'color: red;' : ''}}">
                                {{ $item['cantidad'] > $item['maxima'] ? $item['cantidad'] - $item['maxima'] : '0'}}
                            </td>
                            <td>{{ $item['cantidad'] }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#tableInvitaciones').DataTable({
                columnDefs: [{
                    targets: '_all',
                    className: 'text-start'
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