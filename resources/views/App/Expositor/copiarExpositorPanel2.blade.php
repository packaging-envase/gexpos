@extends('layouts.app')

@section('content')
    <style>
        .dt-buttons {
            display: flex;
        }

        .dt-input {
            margin-left: 0.5rem;
        }
    </style>
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">
            @include('layouts.messages')
            <h2 class="mb-3">
                <u>Copiar Expositor para: {{ $viewData['exposicion']['nombre'] }}</u>
            </h2>
            <h2>
                Origen: {{ $viewData['exposicionSelected']['nombre'] }}
            </h2>
            <div class="mb-3">
                <input type="text" id="buscarExpositor" class="form-control" placeholder="Buscar expositor...">
            </div>
            @if (count($viewData['expositores']) > 0)
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['expositores'] as $expositor)
                            <tr style="cursor: pointer;"
                                onclick="if(confirm('¿Confirmar copiar el expositor {{ addslashes($expositor['razon_social']) }} de {{ addslashes($viewData['exposicionSelected']['nombre']) }} a {{ addslashes($viewData['exposicion']['nombre']) }}?')) { 
                                    window.location='{{ route('expositor.copiar-expositor-panel-3', ['idExposicion' => $viewData['exposicionSelected']['id'], 'id' => $viewData['exposicion']['id'], 'idExpositor' => $expositor['id'], ]) }}'; 
                                }"
                                class="expositor-item">
                                <td>{{ $expositor['usuario'] }}</td>
                                <td>{{ $expositor['razon_social'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <script>
        document.getElementById('buscarExpositor').addEventListener('keyup', function() {
            const texto = this.value.toLowerCase();

            document.querySelectorAll('.expositor-item').forEach(function(item) {
                const contenido = item.textContent.toLowerCase();

                item.style.display = contenido.includes(texto) ? '' : 'none';
            });
        });
    </script>
@endsection
