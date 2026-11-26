@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'empresa'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white border">

            <a href="{{ route('stand-tipo.create') }}" class="btn btn-primary float-end">
                Nuevo Tipo de Stand
            </a>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                <u>Panel Tipo Stand</u>
            </h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tipos as $tipo)
                        <tr>
                            <td>{{ $tipo->nombre }}</td>
                            <td>
                                <a href="{{ route('stand-tipo.edit', $tipo->id) }}" class="btn btn-sm btn-warning">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
