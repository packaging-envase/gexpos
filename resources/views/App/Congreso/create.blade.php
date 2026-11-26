@extends('layouts.app')

@section('content')

    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        @include('layouts.messages')

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded border">

            @if ($viewData['congreso'])
                <a class="btn btn-warning float-end"
                    href="{{ route('congreso.view-excel', ['idCongreso' => $viewData['congreso']['id']]) }}"
                    target="_blank">
                    Preview
                </a>

                <a class="btn btn-primary float-end mx-2"
                    href="{{ route('congreso.generar-diplomas-view', ['idCongreso' => $viewData['congreso']['id']]) }}">
                    Ver Diplomas
                </a>
            @endif

            <h2 class="mb-3">
                @if ($viewData['congreso'])
                    <u>Editar Congreso</u>
                @else
                    <u>Nuevo Congreso</u>
                @endif
            </h2>

            <form method="POST" action="{{ route('congreso.create-post') }}">
                @csrf

                @if ($viewData['congreso'])
                    <input hidden name="idCongreso" value="{{ $viewData['congreso']['id'] }}" />
                @endif

                <div>
                    <label>* Nombre de Congreso</label>
                    <input name="nombre" class="form-control"
                        value="{{ $viewData['congreso'] ? $viewData['congreso']['nombre'] : '' }}" required />
                </div>
                <div>
                    <label>* Descripcion</label>
                    <textarea name="descripcion" class="form-control" required rows="4">{{ $viewData['congreso'] ? $viewData['congreso']['descripcion'] : '' }}</textarea>
                </div>
                <div>
                    <label>* Desde</label>
                    <input name="desde" type="date" class="form-control"
                        value="{{ $viewData['congreso'] ? $viewData['congreso']['desde'] : '' }}" required />
                </div>
                <div>
                    <label>* Hasta</label>
                    <input name="hasta" type="date" class="form-control"
                        value="{{ $viewData['congreso'] ? $viewData['congreso']['hasta'] : '' }}" required />
                </div>
                <div>
                    <label>Locación</label>
                    <input name="location" class="form-control"
                        value="{{ $viewData['congreso'] ? $viewData['congreso']['location'] : '' }}" />
                </div>

                <div class="mt-2">
                    <button class="btn btn-success float-end" type="submit">
                        Guardar
                    </button>
                    <div style="clear: both;"></div>
                </div>
            </form>

            <hr />

            @if ($viewData['congreso'])
                <h2 class="mb-3">
                    Locaciones
                </h2>

                <form method="POST"
                    action="{{ route('congreso.modulo-location-post', ['idCongreso' => $viewData['congreso']['id']]) }}">
                    @csrf

                    <input hidden name="idCongerso" value="{{ $viewData['congreso']['id'] }}" />

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="descripcion" placeholder="Nueva Locación">
                        <button class="btn btn-success" type="submit">Crear Locación</button>
                    </div>
                </form>

                @foreach ($viewData['locaciones'] as $item)
                    <div class="input-group mb-1">

                        {{-- Editar --}}
                        <form method="POST"
                            action="{{ route('congreso.modulo-location-post', [
                                'idCongreso' => $viewData['congreso']['id'],
                                'idModuloLocation' => $item['id'],
                            ]) }}"
                            class="d-flex flex-grow-1">

                            @csrf

                            <input type="text" class="form-control" name="descripcion" placeholder="Descripción Locación"
                                value="{{ $item['descripcion'] }}">

                            <button class="btn btn-warning" type="submit">
                                Editar
                            </button>
                        </form>

                        {{-- Borrar --}}
                        <form method="POST"
                            action="{{ route('congreso.modulo-location-post-delete', [
                                'idCongreso' => $viewData['congreso']['id'],
                                'idModuloLocation' => $item['id'],
                            ]) }}">
                            @csrf

                            <button class="btn btn-danger" type="submit">
                                Borrar
                            </button>
                        </form>

                    </div>
                @endforeach

                <hr />

                <a class="btn btn-success float-end me-2"
                    href="{{ route('congreso.modulo-create-view', ['idCongreso' => $viewData['congreso']['id']]) }}">
                    + Modulo
                </a>

                <a class="btn btn-primary float-end me-2"
                    href="{{ route('congreso.modulo-create-view', ['idCongreso' => $viewData['congreso']['id'], 'break' => true ]) }}">
                    + Break
                </a>

                <h2 class="mb-3">
                    Modulos
                </h2>

                @foreach ($viewData['congreso']['congresoModulo'] as $modulo)
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0">

                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">
                                    <a class="text-white text-decoration-none d-flex justify-content-between align-items-center"
                                        data-bs-toggle="collapse" href="#modulo{{ $modulo['id'] }}" role="button"
                                        aria-expanded="false">

                                        <span style="width: 50%;">{{ $modulo['nombre'] }}</span>
                                        <span style="width: 30%;">{{ $modulo['location_descripcion'] }}</span>
                                        <span style="width: 20%; text-align: right;">{{ $modulo['desde'] ? \Carbon\Carbon::parse($modulo['desde'])->format('d/m/Y H:i') : '' }} <br/> {{ $modulo['hasta'] ? \Carbon\Carbon::parse($modulo['hasta'])->format('d/m/Y H:i') : '' }}</span>
                                        <span>▼</span>
                                    </a>
                                </h5>
                            </div>

                            <div class="collapse" id="modulo{{ $modulo['id'] }}">
                                <div class="card-body">
                                    <?php 
                                        $break = $modulo['nombre'] == 'BREAK' ? 'true' : 'false'; 
                                    ?>
                                    <a class="btn btn-success float-end"
                                        href="{{ route('congreso.modulo-create-view', [
                                            'idCongreso' => $viewData['congreso']['id'],
                                            'idCongresoModulo' => $modulo['id'],
                                            'break' => $break
                                        ]) }}">
                                        Editar Modulo
                                    </a>

                                    <a class="btn btn-danger float-end me-1"
                                        href="{{ route('congreso.modulo-delete', [ 'idCongresoModulo' => $modulo['id'] ]) }}">
                                        Borrar Modulo
                                    </a>

                                    <label><b><u>Desde</u></b></label><br />
                                    {{$modulo['desde'] ? \Carbon\Carbon::parse($modulo['desde'])->format('d/m/Y H:i') : '' }}

                                    <br />

                                    <label><b><u>Hasta</u></b></label><br />
                                    {{$modulo['hasta'] ? \Carbon\Carbon::parse($modulo['hasta'])->format('d/m/Y H:i') : '' }}

                                    <br />

                                    <label><b><u>Locación</u></b></label><br />
                                    {{ $modulo['location_descripcion'] ?? 'No especificada' }}

                                    @if ($modulo['auspiciante'])
                                        <div>
                                            <label><b><u>Auspiciante</u></b></label>
                                            <p class="card-text mb-0">
                                                {{ $modulo['auspiciante'] }}
                                            </p>
                                        </div>
                                    @endif

                                    <div>
                                        <label><b><u>Descripción</u></b></label>
                                        <p class="card-text mb-0">
                                            {{ $modulo['descripcion'] }}
                                        </p>
                                    </div>

                                    <div>
                                        <label><b><u>Disertantes</u></b></label>

                                        @foreach ($modulo['disertantes'] as $disertante)
                                            <br />
                                            <a
                                                href="{{ route('congreso.modulo-disertante-create-view', [
                                                    'idCongreso' => $viewData['congreso']['id'],
                                                    'idModulo' => $modulo['id'],
                                                    'idDisertante' => $disertante['id'],
                                                ]) }}">
                                                {{ $disertante['apellido_nombre'] }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <hr />

                                    <a
                                        href="{{ route('congreso.modulo-disertante-create-view', [
                                            'idCongreso' => $viewData['congreso']['id'],
                                            'idCongresoModulo' => $modulo['id'],
                                        ]) }}">
                                        + Disertante
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endSection
