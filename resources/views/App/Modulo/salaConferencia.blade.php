@extends('layouts.app')

@section('content')
    <style>
        .dt-search {
            float: right;
        }

        .dt-buttons {
            float: left;
        }
    </style>
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

        <div class="container">
            <h2 class="mb-3">
                <u>Modulo - Sala de Conferencias</u>
            </h2>

            @include('layouts.messages')

            <div class="border shadow bg-white p-2 mt-2">
                <form method="POST" action="{{route('modulo.sala-conferencia.update-store-texto')}}">
                    @csrf
                    
                    <input hidden name="id_exposicion" value="{{$viewData['exposicion']['id']}}" />

                    <label>Fecha de Cierre</label>
                    <input type="date" class="form-control mb-2" style="width: fit-content;" name="cierre" value="{{$viewData['exposicion']['salaConferenciaTexto'] ? $viewData['exposicion']['salaConferenciaTexto']['cierre'] : ''}}"/>

                    <textarea id="textareaSalaConferencia" name="descripcion">
                        @if ($viewData['exposicion']['salaConferenciaTexto'])
                            {{$viewData['exposicion']['salaConferenciaTexto']['descripcion']}}
                        @endif
                    </textarea>
                    <script type="text/javascript">
                        tinymce.init({
                            selector: '#textareaSalaConferencia',
                            license_key: 'gpl'
                        });
                    </script>

                    <button class="btn btn-success float-end mt-2" type="submit">Guardar</button>
                    <div style="clear: both;"></div>
                </form>
            </div>

            <div class="border shadow bg-white p-2 mt-2">
                <h5 class="text-center"><u>
                        {{ $viewData['item'] ? 'Editar Item' : 'Nuevo Item' }}
                    </u>
                </h5>

                <form method="POST" action="{{ route('modulo.sala-conferencia.update-store') }}">
                    @csrf

                    <input hidden name="id_exposicion" value="{{ $viewData['exposicion']['id'] }}" />
                    @if ($viewData['item'])
                        <input hidden name="id" value="{{ $viewData['item']['id'] }}" />
                    @endif

                    <div class="row">
                        <div class="col-12 col-md-3">
                            <label class="mt-1">Sala</label>
                            <input name="sala" class="form-control"
                                value="{{ $viewData['item'] ? $viewData['item']['sala'] : '' }}" />
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="mt-1">Fecha</label>
                            <input name="fecha" type="datetime-local" class="form-control"
                                value="{{ $viewData['item'] ? $viewData['item']['fecha'] : '' }}" />
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="mt-1" >Duración (minutos)</label>
                            <input class="form-control" type="text" name="duracion" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="45">
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="d-flex">
                                <div style="padding-top: 35px;">
                                    <label for="etif">ETIF</label>
                                    <input id="etif" type="checkbox" name="etif" style="margin-top: 3px;" {{ $viewData['item'] && $viewData['item']['etif'] == '1' ? 'checked=true' : '' }}/>
                                </div>

                                <div class="mt-4">
                                    @if ($viewData['item'])
                                        <a class="btn btn-primary"
                                            href="{{ route('modulo.sala-conferencia.panel', ['id' => $viewData['exposicion']['id']]) }}">Cancelar</a>
                                    @endif
                                    <button class="btn btn-success ms-4" type="submit">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </form>
            </div>

            <div class="border shadow bg-white p-2 mt-2">
                <div class="table-responsive">
                    <table id="tableModuloSalaConferencia" class="table" style="white-space: nowrap; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sala</th>
                                <th>Fecha</th>
                                <th>Duracion</th>
                                <th>ETIF</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewData['exposicion']['salaConferencia'] as $item)
                                <tr>
                                    <td>{{ $item['sala'] }}</td>
                                    <td>{{ $item['fecha'] }}</td>
                                    <td>{{ $item['duracion'] }}</td>
                                    <td>{{ $item['etif'] == '1' ? 'Si' : 'No' }}</td>
                                    <td>
                                        <a class="btn btn-success"
                                            href="{{ route('modulo.sala-conferencia.panel', ['id' => $viewData['exposicion']['id'], 'itemId' => $item['id']]) }}">Editar</a>
                                        <a class="btn btn-danger"
                                            href="{{ route('modulo.sala-conferencia.delete', ['id' => $viewData['exposicion']['id'], 'itemId' => $item['id']]) }}">Eliminar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableModuloSalaConferencia').DataTable({
                columnDefs: [{
                    targets: '_all',
                    className: 'text-start'
                }],
                dom: 'Bfrtip', // Asegúrate de tener el dom correcto para los botones
                buttons: [{
                    extend: 'excelHtml5',
                    title: "{{ $viewData['exposicion']['nombre'] }} - Energia Extra" // Aquí cambias el nombre del archivo
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
