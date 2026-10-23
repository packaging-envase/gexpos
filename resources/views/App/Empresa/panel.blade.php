@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'empresa'])
    
    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white border">

            @include('layouts.messages')
        
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                <u>Panel Empresas</u>
            </h2>
            <a class="btn btn-primary float-end" href="{{ route('empresa.create', ['id' => 0, 'idExposicion' => isset($viewData['exposicion']) && $viewData['exposicion'] ? $viewData['exposicion']['id'] : 0]) }}">
                Nueva Empresa
            </a>
            <div>
                <form id="formSearchEmpresa" method="GET" action="{{ route('empresa.panel') }}">
                    <div class="input-group mb-3" style="width: 400px;">
                        <input id="inputSearchEmpresa" name="search" class="form-control"
                            placeholder="Cuit - Razon Social" />
                        @if (isset($viewData['exposicion']) && $viewData['exposicion'])
                            <input hidden name="id" value="{{$viewData['exposicion']['id']}}" />
                        @endif
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit"><i class="fa fa-search"
                                    aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
                <script type="text/javascript">
                    $("#inputSearchEmpresa").on('keyup', function(e) {
                        if (e.key === 'Enter' || e.keyCode === 13) {
                            $('#formSearchEmpresa').submit();
                        }
                    });
                </script>
            </div>
            
            @if (count($viewData['empresas']))
                <form method="POST" action="{{route('empresa.delete-many')}}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped border border-dark">
                            <thead>
                                <tr class="table-dark">
                                    <th>Prospector</th>
                                    <th>Cuit</th>
                                    <th>Razon Social</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    @if (Auth::guard()->user()->grupo == 1)
                                        <th class="text-center">Pertenece ETIF</th>
                                    @endif
                                    <th class="text-center" style="width: 100px;">
                                        <button type="submit" class="btn btn-danger">Borrar</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($viewData['empresas'] as $item)
                                    <tr style="white-space: nowrap;">
                                        <td>{{ $item['name_prospector'] }}</td>
                                        <td class="p-0 ps-1">{{ $item['cuit'] }}</td>
                                        <td class="p-0">
                                            <b>
                                                <a href="{{ route('empresa.create', ['id' => $item['id'], 'idExposicion' => isset($viewData['exposicion']) ? $viewData['exposicion']['id'] : '0']) }}">
                                                    {{ $item['razon_social'] ? $item['razon_social'] : 'Sin Nombre' }}
                                                </a>
                                            </b>
                                        </td>
                                        <td class="p-0">{{ $item['telefono'] }}</td>
                                        <td class="p-0">{{ $item['email'] }}</td>
                                        @if (Auth::guard()->user()->grupo == 1)
                                            <td class="p-0">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" {{ $item['pertenece_etif'] ? 'checked=true' : '' }} onclick="changeEnvase('{{$item['id']}}')">
                                                </div>
                                            </td>
                                        @endif
                                        <td class="p-0 text-center" style="border-left: 1px solid black;">
                                            <input type="checkbox" name="checkboxBorrarEmpresas[]" value="{{ $item['id'] }}" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @endif
            {{ $viewData['empresas']->links() }}
        </div>
    </div>
    @if (Auth::guard()->user()->grupo == 1)
    <script type="text/javascript">
        let urlChangeEnvase = `{{route('api.empresa.change-etif')}}`
        function changeEnvase(id) {
            axios.get(urlChangeEnvase + "?id=" + id)
                .then(response => {
                    console.log(response);
                    if (response.status == 200) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        }
    </script>
    @endif
@endsection
