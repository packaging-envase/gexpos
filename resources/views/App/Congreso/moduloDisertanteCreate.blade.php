@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    @include('layouts.messages')

    <div class="container mb-3">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3>{{ $viewData['congreso']['nombre'] }}</h3>
                <h4 class="mb-0">
                    {{ isset($viewData['disertante']) ? 'Editar Disertante' : 'Nuevo Disertante' }}
                </h4>
            </div>

            <div class="card-body">

                <form action="{{ route('congreso.modulo-disertante-create-post') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <input hidden name="id_congreso_modulo" value="{{$viewData['idCongresoModulo']}}" />
                    @if (isset($viewData['disertante']))
                        <input hidden name="id_disertante" value="{{ $viewData['disertante']->id }}" />
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Apellido y Nombre</label>
                        <input type="text" name="apellido_nombre"
                            class="form-control @error('apellido_nombre') is-invalid @enderror bg-white"
                            value="{{ old('apellido_nombre', $viewData['disertante']->apellido_nombre ?? '') }}" required>

                        @error('apellido_nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror bg-white"
                            value="{{ old('titulo', $viewData['disertante']->titulo ?? '') }}">

                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Especialidad</label>
                        <input type="text" name="especialidad"
                            class="form-control @error('especialidad') is-invalid @enderror bg-white"
                            value="{{ old('especialidad', $viewData['disertante']->especialidad ?? '') }}">

                        @error('especialidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cargo</label>
                        <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror bg-white"
                            value="{{ old('cargo', $viewData['disertante']->cargo ?? '') }}">

                        @error('cargo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Empresa / Institución</label>
                        <input type="text" name="empresa" class="form-control @error('empresa') is-invalid @enderror bg-white"
                            value="{{ old('empresa', $viewData['disertante']->empresa ?? '') }}">

                        @error('empresa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto</label>

                        @if (!empty($viewData['disertante']->path_foto))
                            <div class="mb-2">
                                <img src="{{ asset($viewData['disertante']->path_foto) }}" class="img-thumbnail"
                                    style="max-height:200px;">
                            </div>
                        @endif

                        <input type="file" name="path_foto" class="form-control @error('path_foto') is-invalid @enderror bg-white"
                            accept="image/*">

                        @error('path_foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción / CV</label>

                        <textarea name="descripcion" rows="8" class="form-control @error('descripcion') is-invalid @enderror bg-white">{{ old('descripcion', $viewData['disertante']->descripcion ?? '') }}</textarea>

                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">

                        <button type="submit" class="btn btn-primary">
                            {{ isset($viewData['disertante']) ? 'Actualizar' : 'Guardar' }}
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
