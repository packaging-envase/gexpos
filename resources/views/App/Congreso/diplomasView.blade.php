@extends('layouts.app')

@section('content')

    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        @include('layouts.messages')

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            <a class="btn btn-primary float-end mx-2"
                href="{{ route('congreso.diploma-raw', ['idCongreso' => $idCongreso]) }}">
                Configurar Diploma
            </a>

            <a class="btn btn-primary float-end" style="text-decoration: none;"
                href="{{ route('congreso.create-view', ['idCongreso' => $idCongreso]) }}">
                <- Volver
            </a>

            <h2 class="mb-3">
                <u>Congreso - Diplomas</u>
            </h2>

            <div class="mt-3">
                <div class="row">
                    <div class="col-md-12">

                        <div class="row">

                            {{-- Generar diploma individual --}}
                            <div class="col-6">
                                <div class="card" style="height: 187px;">
                                    <div class="card-header">
                                        Generar diploma individual
                                    </div>

                                    <div class="card-body">
                                        <form action="{{ route('congreso.generar-diplomas') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="idCongreso" value="{{ $idCongreso }}">

                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>

                                                <input type="text" class="form-control" id="nombre" name="nombre"
                                                    value="{{ old('nombre') }}" placeholder="Ingrese el nombre" required
                                                    autofocus>

                                                @error('nombre')
                                                    <div class="text-danger mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                                <button type="submit" class="btn btn-primary mt-3">
                                                    Generar diploma
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Generar diplomas desde CSV --}}
                            <div class="col-6">
                                <div class="card" style="height: 187px;">
                                    <div class="card-header">
                                        Generar diplomas desde CSV
                                    </div>

                                    <div class="card-body">
                                        <form action="{{ route('congreso.generar-diplomas') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <input type="hidden" name="idCongreso" value="{{ $idCongreso }}">

                                            <div class="form-group">
                                                <label for="csv">Archivo CSV</label>

                                                <input type="file" class="form-control-file" id="csv" name="csv"
                                                    accept=".csv,.txt" required>

                                                <small class="form-text text-muted">
                                                    El archivo debe tener una primera columna llamada
                                                    <strong>nombre</strong>.
                                                </small>

                                                @error('csv')
                                                    <div class="text-danger mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-primary mt-3">
                                                Generar diplomas
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Diplomas generados --}}
                        @if (count($diplomas))

                            <div class="row mt-4">

                                @foreach ($diplomas as $diploma)

                                    <div class="col-lg-4 col-md-6 mb-4">

                                        <div class="card h-100">

                                            <div class="card-body p-2">
                                                <a href="{{ $diploma['url'] }}" target="_blank">
                                                    <img src="{{ $diploma['url'] }}"
                                                        class="img-fluid w-100"
                                                        alt="{{ $diploma['nombre'] }}">
                                                </a>
                                            </div>

                                            <div class="card-footer text-center">

                                                <strong>{{ $diploma['nombre'] }}</strong>

                                                <br>

                                                <small class="text-muted">
                                                    {{ $diploma['filename'] }}
                                                </small>

                                                <div class="mt-2">

                                                    <form
                                                        action="{{ route('congreso.borrar-diploma', [
                                                            'idCongreso' => $idCongreso,
                                                            'filename' => $diploma['filename']
                                                        ]) }}"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('¿Está seguro de que desea borrar este diploma?');">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            Borrar
                                                        </button>

                                                    </form>

                                                    <a href="{{ $diploma['urlDownload'] }}"
                                                        class="btn btn-sm btn-primary"
                                                        target="_blank">
                                                        Descargar
                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="alert alert-info mt-4">
                                No hay diplomas generados.
                            </div>

                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection