@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Catalogo -> Expositores Habilitados</u>
            </h2>

            @include('layouts.messages')

            <h4>Exportación Envase</h4>
            <div class="d-flex">
                <a class="btn btn-primary ms-2" href="{{route('modulo.catalogo.exportacion-word-detalle', ['id' => $viewData['exposicion']['id'], 'catalogo' => 'envase'])}}">Exportar Word Detalle</a>
                <a class="btn btn-primary ms-2" href="{{route('modulo.catalogo.exportacion-word-rubros', ['id' => $viewData['exposicion']['id'], 'catalogo' => 'envase' ])}}" >Exportar Word Rubros</a>
                <a class="btn btn-success ms-2" href="{{route('modulo.catalogo.exportacion-excel', ['id' => $viewData['exposicion']['id'], 'catalogo' => 'envase'])}}">Exportar Excel</a>
            </div>
            <hr/>
            <h4>Exportación ETIF</h4>
            <div class="d-flex">
                <a class="btn btn-primary ms-2" href="{{route('modulo.catalogo.exportacion-word-detalle', ['id' => $viewData['exposicion']['id'], 'catalogo' => 'etif'])}}">Exportar Word Detalle</a>
                <a class="btn btn-primary ms-2" href="{{route('modulo.catalogo.exportacion-word-rubros', ['id' => $viewData['exposicion']['id'], 'catalogo' => 'etif' ])}}" >Exportar Word Rubros</a>
                <a class="btn btn-success ms-2" href="{{route('modulo.catalogo.exportacion-excel', ['id' => $viewData['exposicion']['id'], 'catalogo' => 'etif'])}}">Exportar Excel</a>
            </div>
            <hr/>
            <h4>Exportación Envase + ETIF</h4>
            <div class="d-flex">
                <a class="btn btn-primary ms-2" href="{{route('modulo.catalogo.exportacion-word-detalle', ['id' => $viewData['exposicion']['id']])}}">Exportar Word Detalle</a>
                <a class="btn btn-primary ms-2" href="{{route('modulo.catalogo.exportacion-word-rubros', ['id' => $viewData['exposicion']['id']])}}" >Exportar Word Rubros</a>
                <a class="btn btn-success ms-2" href="{{route('modulo.catalogo.exportacion-excel', ['id' => $viewData['exposicion']['id']])}}">Exportar Excel</a>
            </div>

            <table class="table mt-2">
                <thead>
                    <tr class="table-dark">
                        <th>Empresa</th>
                        <th>Envase</th>
                        <th>ETIF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['expositores'] as $expositor)
                        <tr>
                            <td>
                                @if ($expositor->empresa)
                                    <a href="{{route('expositor.modulo.catalogo.panel', ['idExposicion' => $viewData['exposicion']['id'], 'idEmpresa' => $expositor->empresa->id])}}">
                                        {{ $expositor->empresa->razon_social }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <form id="formSwitchCatalogo{{$expositor->id}}" method="POST" action="{{route('modulo.catalogo.switch-expositor')}}">
                                        @csrf
                                        <input hidden name="id_exposicion" value="{{$viewData['exposicion']['id']}}" />
                                        <input hidden name="id_expositor" value="{{$expositor->id}}" />
                                        <input name="switch" class="form-check-input" type="checkbox"
                                            {{ $expositor->catalogo ? 'checked=true' : '' }} onclick="switchEnvase(event, '{{$expositor->etif}}', '{{$expositor->id}}')"/>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <form id="formSwitchCatalogoEtif{{$expositor->id}}" method="POST" action="{{route('modulo.catalogo.switch-expositor-etif')}}">
                                        @csrf
                                        <input hidden name="id_exposicion" value="{{$viewData['exposicion']['id']}}" />
                                        <input hidden name="id_expositor" value="{{$expositor->id}}" />
                                        <input name="switch" class="form-check-input" type="checkbox"
                                            {{ $expositor->etif ? 'checked=true' : '' }} onclick="switchEtif(event, '{{$expositor->catalogo}}', '{{$expositor->id}}')"/>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        function switchEnvase(event, enEtif, id) {
            if (enEtif == 1) {
                event.preventDefault();  // Evita el cambio de estado del checkbox
                alert('El Expositor ya está en el catalogo de ETIF');
                return;
            }
            $('#formSwitchCatalogo'+id).submit()
            //
            //$('#formSwitchCatalogoEtif{{$expositor->id}}').submit()
        }
        function switchEtif(event, enEnvase, id) {
            if (enEnvase == 1) {
                event.preventDefault();  // Evita el cambio de estado del checkbox
                alert('El Expositor ya está en el catalogo de Envase');
                return;
            }
            $('#formSwitchCatalogoEtif'+id).submit()
        }
    </script>
@endsection
