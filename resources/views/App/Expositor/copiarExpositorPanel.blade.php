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
    @include('layouts.messages')

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">
            <h2 class="mb-3">
                <u>Copiar Expositor para: {{$viewData['exposicion']['nombre']}}</u>
            </h2>
            <h2>
                 Elegir Origen
            </h2>
            
            @if(count($viewData['exposiciones']) > 0)
                <div class="row g-3">
                    @foreach($viewData['exposiciones'] as $exposicion)
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('expositor.copiar-expositor-panel-2', ['idExposicion' => $exposicion['id'], 'id' => $viewData['exposicion']['id']]) }}" class="expo-card">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-0">
                                            {{ $exposicion['nombre'] }}
                                        </h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection