@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'catalogo'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white border">
            <h2 class="m-0">
                <u>Novedades</u>
            </h2>
            <small>(creación de empresa/expositor desde ETIF)</small>
            @include('layouts.messages')

            <table id="tableNovedades" class="table table-stripped mt-3">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Creador</th>
                        <th>Descricpión</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['novedades'] as $novedad)
                        <tr>
                            <td>{{ substr($novedad['created_at'], 0, 10) }}</td>
                            <td>{{ $novedad['name'] }}</td>
                            <td>
                                @if (isset($novedad['id_expositor']) && $novedad['id_expositor'])
                                    <a href="{{ route('expositor.update-with-empresa', ['id' => $novedad['id_expositor']]) }}" target="blank_">
                                        {{ $novedad['descripcion'] }}
                                    </a>
                                @else
                                    <a href="{{ route('empresa.create', ['id' => $novedad['id_empresa'], 'idExposicion' => isset($viewData['exposicion']) ? $viewData['exposicion']['id'] : '0']) }}"
                                        target="_blank">
                                        {{ $novedad['descripcion'] }}
                                    </a>
                                @endif
                            </td>
                            <?php
                                $classStatus = '';
                                $flagStatus = null;
                                switch ($novedad['status']) {
                                    case 'aprobado':
                                        $flagStatus = 2;
                                        $classStatus = 'btn btn-success';
                                        break;
                                    case 'pendiente':
                                        $flagStatus = 0;
                                        $classStatus = 'btn btn-warning';
                                        break;
                                    case 'rechazado':
                                        $flagStatus = 1;
                                        $classStatus = 'btn btn-danger';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            ?>
                            <td>
                                <span class="d-none">{{$flagStatus}}</span>
                                <input disabled class="{{ $classStatus }}" value="{{ $novedad['status'] }}" />
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a class="btn btn-danger" href="{{route('novedades.resolve-expositor', ['id' => $novedad['id'], 'status' => 'false'])}}">Rechazar</a>
                                    <a class="btn btn-success ms-2" href="{{route('novedades.resolve-expositor', ['id' => $novedad['id'], 'status' => 'true'])}}">Aprobar</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableNovedades').DataTable({
                columnDefs: [
                    { targets: '_all', className: 'text-start' }
                ],
                layout: {
                    topStart: {
                        buttons: ['excelHtml5']
                    }
                }
            });
        });
    </script>
@endsection