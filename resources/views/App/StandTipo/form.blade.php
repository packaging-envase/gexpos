@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'empresa'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white border">

            @include('layouts.messages')

            <div class="container">

                <h3>
                    {{ $standTipo->id ? 'Editar Tipo de Stand' : 'Nuevo Tipo de Stand' }}
                </h3>

                <form method="POST" action="{{ route('stand-tipo.update-store') }}">

                    @csrf

                    <input type="hidden" name="id" value="{{ $standTipo->id }}">

                    <div class="mb-3">

                        <label class="form-label">Nombre</label>

                        <input type="text" name="nombre" class="form-control"
                            value="{{ old('nombre', $standTipo->nombre) }}" required>

                    </div>

                    <button class="btn btn-success">
                        Guardar
                    </button>

                    <a href="{{ route('stand-tipo.index') }}" class="btn btn-secondary">
                        Volver
                    </a>

                </form>

            </div>
        </div>
    </div>
@endsection
