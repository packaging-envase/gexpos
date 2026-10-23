@extends('layouts.app')

@section('content')

    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        @include('layouts.messages')
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            <h2 class="mb-3">
                <u>Exposiciones</u>
            </h2>

            @if (Auth::guard()->user()->grupo == 1)
                <a class="btn btn-primary float-end" href="{{ route('exposicion.create', ['id' => 0]) }}">
                    Nueva Exposición
                </a>
            @endif

            <div class="mb-2">
                @if (Auth::guard()->user()->grupo == 1)
                    <a class="btn btn-{{ !$viewData['archivadas'] ? 'success' : 'secondary' }}"
                        href="{{ route('exposicion.panel') }}">
                        Principales
                    </a>
                    <a class="btn btn-{{ $viewData['archivadas'] ? 'success' : 'secondary' }}"
                        href="{{ route('exposicion.panel', ['archivadas' => true]) }}">
                        Archivadas
                    </a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-striped border border-dark">
                    <thead>
                        <tr class="table-dark">
                            @if ($viewData['archivadas'])
                                <th>Nombre</th>
                                <th></th>
                            @else
                                <th>Nombre</th>
                                <th>Catalogo de Envase en Wordpress</th>
                                <th>Catalogo de ETIF</th>
                                @if (Auth::guard()->user()->grupo == 1)
                                <th>¿Es ETIF?</th>
                                <th>Visible</th>
                                <th></th>
                                @endif
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($viewData['archivadas'])
                            @foreach ($viewData['exposiciones'] as $item)
                                @if ((Auth::guard()->user()->grupo == 3 && $item['etif'] == 1) || Auth::guard()->user()->grupo != 3)
                                <tr>
                                    <td>
                                        <a class="h3 text-primary" style="text-decoration: none;"
                                            href="{{ Auth::guard()->user()->grupo == '1'
                                                ? route('exposicion.create', ['id' => $item['id']])
                                                : (Auth::guard()->user()->grupo == '2'
                                                    ? route('modulo.plano-stand.panel-requerimientos', ['id' => $item['id']])
                                                    : route('logout')) }}">
                                            {{ $item['nombre'] }}
                                        </a>
                                    </td>
                                    <td>
                                        @if (Auth::guard()->user()->grupo == 1)
                                            <a class="btn btn-primary"
                                                href="{{ route('exposicion.switch-archivar', ['id' => $item['id']]) }}">Desarchivar</a>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if ($item['visible'])
                                            <a class="btn btn-danger" href="{{ route('exposicion.switch-visible', ['idExposicion' => $item['id']]) }}">Desactivar</a>
                                        @else
                                            <a class="btn btn-success" href="{{ route('exposicion.switch-visible', ['idExposicion' => $item['id']]) }}">Activar</a>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @else
                            @foreach ($viewData['exposiciones'] as $item)
                                
                                @if ((Auth::guard()->user()->grupo == 3 && $item['etif'] == 1) || Auth::guard()->user()->grupo != 3)
                                <?php
                                $grupo = Auth::guard()->user()->grupo;
                                $id = $item['id'];
                                $url = '';
                                
                                switch ($grupo) {
                                    case '1' || '3':
                                        $url = route('exposicion.create', ['id' => $id]);
                                        break;
                                    case '2':
                                        $url = route('modulo.plano-stand.panel-requerimientos', ['id' => $id]);
                                        break;                                    
                                    case '4':
                                        $url = route('exposicion.create-3', ['id' => $id]);
                                        break;
                                    case '5':
                                        $url = route('modulo.prensa.listado', ['id' => $id]);
                                        break;
                                    default:
                                        $url = route('dashboard');
                                        break;
                                }
                                ?>
                                <tr>
                                    <td>
                                        <a class="h3 text-primary" style="text-decoration: none;"
                                            href="{{ $url }}">
                                            {{ $item['nombre'] }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ isset($viewData['web']) &&
                                                $viewData['web'][0] &&
                                                $viewData['web'][0]->id_selected_expo == $item['id'] &&
                                                $viewData['web'][0]->envase
                                                    ? 'checked=true'
                                                    : '' }}
                                                onchange="handleCheckboxChange(event, @json($item['mapa']), {{ $item['id'] }},'envase')" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ isset($viewData['web']) &&
                                                $viewData['web'][0] &&
                                                $viewData['web'][0]->id_selected_expo == $item['id'] &&
                                                $viewData['web'][0]->etif
                                                    ? 'checked=true'
                                                    : '' }}
                                                onchange="handleCheckboxChange(event, @json($item['mapa']), {{ $item['id'] }},'etif')" />
                                        </div>
                                    </td>
                                    @if (Auth::guard()->user()->grupo == 1)
                                        <td>
                                            <input type="checkbox" {{ $item['etif'] ? 'checked' : '' }}
                                                onchange="switchEsEtif({{ $item['id'] }})" />
                                        </td>                                        
                                        <td>
                                            @if ($item['visible'])
                                                <a class="btn btn-danger" href="{{ route('exposicion.switch-visible', ['idExposicion' => $item['id']]) }}">Desactivar</a>
                                            @else
                                                <a class="btn btn-success" href="{{ route('exposicion.switch-visible', ['idExposicion' => $item['id']]) }}">Activar</a>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-primary"
                                                href="{{ route('exposicion.switch-archivar', ['id' => $item['id']]) }}">Archivar</a>
                                        </td>
                                    @endif                                    
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function handleCheckboxChange(event, hasMap, id, entidad) {
            if (hasMap) {
                window.location.href = '/exposicion/update-web?id=' + id + '&entidad=' + entidad;
            } else {
                // Prevent checking the checkbox and show an alert
                event.preventDefault();
                event.target.checked = false;
                alert('La exposición no tiene mapa');
            }
        }

        function switchEsEtif(id) {
            fetch("{{ route('api.exposicion.switch-etif') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        id: id
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la petición");
                    }
                    alert('Visualizacion para ETIF cambiada exitosamente');
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error(error);
                    alert("Ocurrió un error al actualizar.");
                });
        }
    </script>
@endsection
