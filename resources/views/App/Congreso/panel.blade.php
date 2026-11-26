@extends('layouts.app')

@section('content')

    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        @include('layouts.messages')
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            @if (Auth::guard()->user()->grupo == 1)
                <a class="btn btn-primary float-end" href="{{ route('congreso.create-view') }}">
                    Nuevo Congreso
                </a>
            @endif

            <h2 class="mb-3">
                <u>Congresos</u>
            </h2>

            <div class="table-responsive">
                <table class="table table-striped border border-dark">
                    <thead>
                        <tr class="table-dark">
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['congresos'] as $item)
                            <tr>
                                <td>
                                    <a class="h3 text-primary" style="text-decoration: none;" href="{{ route('congreso.create-view', ['idCongreso' => $item['id']]) }}">
                                        {{ $item['nombre'] }}
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-danger float-end" style="text-decoration: none;" href="{{ route('congreso.delete-congreso', ['idCongreso' => $item['id']]) }}">
                                        Borrar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        
    </script>
@endsection
