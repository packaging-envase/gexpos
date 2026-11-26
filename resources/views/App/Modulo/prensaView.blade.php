@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            {{--
            <h2 class="mb-3">
                <u>Modulo - Prensa - Creacr Nueva</u>
            </h2>
            --}}

            @include('layouts.messages')

            @if ($viewData['prensa'])
                
                @if ($viewData['prensa']['aprobado'])
                    <a class="btn btn-danger float-end" href="{{route('modulo.prensa.desactivar', ['id' => $viewData['exposicion']['id'], 'idPrensa' => $viewData['prensa']['id']])}}">Desactivar</a>
                @else
                    <a class="btn btn-success float-end" href="{{route('modulo.prensa.activar', ['id' => $viewData['exposicion']['id'], 'idPrensa' => $viewData['prensa']['id']])}}">Activar</a>
                @endif

                <a class="btn btn-primary float-end me-3" href="{{route('modulo.prensa.create', ['id' => $viewData['exposicion']['id'], 'idPrensa' => $viewData['prensa']['id']])}}">Editar</a>
                <div style="clear: both;"></div>
            @endif

            <hr/>

            @include('App.Modulo.prensaElement', ['prensa' => $viewData['prensa']])

        </div>
    </div>
@endsection
