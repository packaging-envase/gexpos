@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Open Sans' !important;
        }

        .expo-card {
            transition: all .2s ease;
            text-decoration: none;
            color: inherit;
        }

        .expo-card:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }
    </style>

    <div class="expositor-home pt-3" style="min-height: calc(100vh - 55px);">
        <div class="container">

            @include('layouts.messages')

            <h3 class="mb-4">Mis exposiciones</h3>

            @if(count($viewData['exposiciones']) > 0)

                <div class="row g-3">
                    @foreach($viewData['exposiciones'] as $exposicion)
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ route('expositor.login', ['idExposicion' => $exposicion['id']]) }}" class="expo-card">
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
            @else
                <div class="alert alert-info">
                    Sin eventos disponibles
                </div>
            @endif
        </div>
    </div>
@endsection