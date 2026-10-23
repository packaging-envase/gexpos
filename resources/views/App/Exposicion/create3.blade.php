<script>
    var stands = [];
</script>
@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])
    

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">
            @include('layouts.messages')
            <h2 class="mb-3">
                <u>Asignacion de Stand</u>
            </h2>            
            
            <span>Libres: </span><span>{{ $viewData['status']['libre'] }}</span>
            <span class="text-warning">Reservados: </span><span>{{ $viewData['status']['reservado'] }}</span>
            <span class="text-success">Solicitud Pendiente:
            </span><span>{{ $viewData['status']['solicitud_pendiente'] }}</span>
            <span class="text-primary"><b>Pagado:</b> </span><span>{{ $viewData['status']['pagado'] }}</span>
            <div class="mx-auto" style="width: fit-content;">
                <div id="spinnerLoading" class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="contentBody" class="d-none">
                @include('App.Exposicion.modal-asignacion-stand', [
                    'idExposicion' => $viewData['exposicion']['id'],
                ])
                <div id="image-map-pro"></div>
            </div>
            <hr/>
            <div class="d-flex justify-content-evenly">
                <button 
                    class="btn btn-secondary" 
                    type="button" 
                    onclick="openTable('containerTableTodos')">
                    Todos
                </button>
                <button 
                    class="btn btn-secondary" 
                    type="button" 
                    onclick="openTable('containerTableLibres')">
                    Libres
                </button>
                <button 
                    class="btn btn-warning" 
                    type="button"
                    onclick="openTable('containerTableReservados')">
                    Reservados
                </button>
                <button 
                    class="btn btn-success" 
                    type="button"
                    onclick="openTable('containerTableSolicitudPendiente')">
                    Solicitud Pendiente
                </button>
                <button 
                    class="btn btn-primary" 
                    type="button" 
                    onclick="openTable('containerTablePagados')">
                    Pagados
                </button>
            </div>
            <hr/>
            <div id="containerTableLibres" class="d-none">
                <h5 class="text-center"><b><u>Libres</u></b></h5>
                <table id="tableLibres" class="table" style="white-space: nowrap; width: 100%;">
                    <thead>
                        <tr>
                            <th>Pabellon</th>
                            <th>Nro Stand</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['list']['libre'] as $item)
                            <tr>
                                <td>{{ substr($item['numero'], 0, 1) }}</td>
                                <td>{{ $item['numero'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="containerTableReservados" class="d-none">
                <h5 class="text-center"><b><u>Reservados</u></b></h5>
                <table id="tableReservados" class="table" style="white-space: nowrap; width: 100%;">
                    <thead>
                        <tr>
                            <th>Pabellon</th>
                            <th>Nro Stand</th>
                            <th>Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['list']['reservado'] as $item)
                            <tr>
                                <td>{{ substr($item['numero'], 0, 1) }}</td>
                                <td>{{ $item['numero'] }}</td>
                                <td>{{ $item->expositor->empresa[$item['nombre_exposicion']] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="containerTableSolicitudPendiente" class="d-none">
                <h5 class="text-center"><b><u>Solicitud Pendiente</u></b></h5>
                <table id="tableSolicitudPendiente" class="table" style="white-space: nowrap; width: 100%;">
                    <thead>
                        <tr>
                            <th>Pabellon</th>
                            <th>Nro Stand</th>
                            <th>Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['list']['solicitud_pendiente'] as $item)
                            <tr>
                                <td>{{ substr($item['numero'], 0, 1) }}</td>
                                <td>{{ $item['numero'] }}</td>
                                <td>{{ $item->expositor->empresa[$item['nombre_exposicion']] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="containerTablePagados" class="d-none">
                <h5 class="text-center"><b><u>Pagados</u></b></h5>
                <table id="tablePagados" class="table" style="white-space: nowrap; width: 100%;">
                    <thead>
                        <tr>
                            <th>Pabellon</th>
                            <th>Nro Stand</th>
                            <th>Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['list']['pagado'] as $item)
                            <tr>
                                <td>{{ substr($item['numero'], 0, 1) }}</td>
                                <td>{{ $item['numero'] }}</td>
                                <td>{{ $item->expositor->empresa[$item['nombre_exposicion']] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="containerTableTodos" class="d-none">
                <h5 class="text-center"><b><u>Todos</u></b></h5>
                <div id="alertStand" class="alert alert-success d-none">

                </div>
                <table id="tableTodos" class="table" style="white-space: nowrap; width: 100%;">
                    <thead>
                        <tr>
                            <th>Pabellon</th>
                            <th>Nro Stand</th>
                            <th>Status</th>
                            <th id="thSumValue">Valor (Total: ${{ number_format($viewData['list']['sumValue'], 0, ',', '.') }})</th>
                            <th>Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['list']['todos'] as $item)
                            <tr>
                                <td>{{ substr($item['numero'], 0, 1) }}</td>
                                <td>{{ $item['numero'] }}</td>
                                <td>{{ $item['status'] }}</td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input id="inputStandValue{{$item['id']}}" type="number" step="0.01" value="{{ $item['valor'] }}" class="form-control bg-white" />
                                        @if (Auth::guard()->user()->grupo == '3')
                                            <button class="btn btn-success" type="button" onclick='updateStandValue("{{$item->id}}", $("#inputStandValue"+{{$item->id}}).val(), "{{$item->numero}}")'>Actualizar</button>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ isset($item->expositor) && $item->expositor ?  $item->expositor->empresa[$item['nombre_exposicion']] : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <script src="{{ asset('ImageMapPro\client\main.js') }}"></script>
        <script>
            function openTable(idContainerTable) {
                $('#containerTableLibres').addClass('d-none');
                $('#containerTableReservados').addClass('d-none');
                $('#containerTableSolicitudPendiente').addClass('d-none');
                $('#containerTablePagados').addClass('d-none');

                $('#' + idContainerTable).removeClass('d-none');
            }

            $(document).ready(function() {
                $('#tableLibres').DataTable({
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
                    ],
                    layout: {
                        topStart: {
                            buttons: ['excelHtml5']
                        }
                    }
                });

                $('#tableReservados').DataTable({
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
                    ],
                    layout: {
                        topStart: {
                            buttons: ['excelHtml5']
                        }
                    }
                });

                $('#tableSolicitudPendiente').DataTable({
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
                    ],
                    layout: {
                        topStart: {
                            buttons: ['excelHtml5']
                        }
                    }
                });

                $('#tablePagados').DataTable({
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
                    ],
                    layout: {
                        topStart: {
                            buttons: ['excelHtml5']
                        }
                    }
                });

                $('#tableTodos').DataTable({
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

            function getStands() {
                let urlEmpresaSearch = "{{ route('api.mapa.get-stands', ['idExposicion' => $viewData['exposicion']['id']]) }}";
                console.log(urlEmpresaSearch);
                axios.get(urlEmpresaSearch + "?search=" + $('#inputSearchEmpresa').val())
                    .then(response => {
                        stands = response.data;
                        $('#spinnerLoading').addClass("d-none");
                        $('#contentBody').removeClass("d-none");
                        let jsonMapa = JSON.parse(@json($viewData['exposicion']['mapa']));
                        ImageMapPro.init('#image-map-pro', jsonMapa);
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }

            function updateStandValue(id, value, numero) {
                let urlUpdateStandValue = "{{ route('api.update-stand-value') }}";
                console.log(urlUpdateStandValue);

                axios.get(urlUpdateStandValue + "?id=" + id + "&value=" + value)
                    .then(response => {
                        console.log(response);
                        if (response.status == 200) {
                            $('#alertStand').html('Stand ' + numero + ' actualizado con éxito');
                            $('#alertStand').removeClass('d-none');
                            $('#thSumValue').html('Valor (Total: $'+response.data.sumValue+')')
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
            getStands();
        </script>
    @endsection
