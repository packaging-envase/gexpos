@extends('layouts.app')

@section('content')
    <style>
        input[type="color"] {
            width: 50px;
            height: 38px;
            padding: 3px;
            cursor: pointer;
        }

        .color-container {
            display: flex;
            align-items: center;
            margin-left: 8px;
        }

        .color-picker {
            width: 55px;
            min-width: 55px;
            height: 38px;
            padding: 3px;
            cursor: pointer;
        }

        .color-input {
            width: 90px;
            min-width: 90px;
            height: 38px;
            margin-left: 8px;
            font-family: monospace;
            text-transform: uppercase;
        }
    </style>

    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        @include('layouts.messages')

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded border">

            <h2 class="mb-3">
                <u>Nuevo Modulo para Congreso: {{ $viewData['congreso']['nombre'] }}</u>
            </h2>

            <form method="POST" action="{{ route('congreso.modulo-create-post') }}">
                @csrf

                <input name="idCongreso" hidden value="{{ $viewData['congreso']['id'] }}" />

                @if ($viewData['modulo'])
                    <input hidden name="idModulo" value="{{ $viewData['modulo']['id'] }}" />
                @endif

                @php
                    $colores = [
                        '#FFB3BA', // Rosa pastel
                        '#FFDFBA', // Durazno pastel
                        '#FFFFBA', // Amarillo pastel
                        '#BAFFC9', // Verde pastel
                        '#BAE1FF', // Celeste pastel
                        '#D8B4FE', // Violeta pastel
                        '#FF6B6B', // Rojo
                        '#FFA94D', // Naranja
                        '#51CF66', // Verde
                        '#339AF0', // Azul
                    ];
                @endphp

                @if ($viewData['break'] == 'false')
                    <div>

                        {{-- NOMBRE --}}
                        <div>
                            <label>* Nombre de Modulo</label>

                            <div class="d-flex align-items-center">

                                <input name="nombre" class="form-control"
                                    value="{{ $viewData['modulo'] ? $viewData['modulo']['nombre'] : '' }}" required />

                                <div class="color-container">

                                    <input type="color" name="nombre_color" class="form-control color-picker"
                                        value="{{ $viewData['modulo']['nombre_color'] ?? '#000000' }}" list="colores"
                                        title="Color del nombre">

                                    <input type="text" class="form-control color-input"
                                        value="{{ $viewData['modulo']['nombre_color'] ?? '#000000' }}" maxlength="7"
                                        placeholder="#000000" title="Color hexadecimal">

                                </div>

                            </div>
                        </div>

                        {{-- DESCRIPCION --}}
                        <div class="mt-2">

                            <label>* Descripcion</label>

                            <div class="d-flex align-items-center">

                                <input name="descripcion" class="form-control"
                                    value="{{ $viewData['modulo'] ? $viewData['modulo']['descripcion'] : '' }}" required />

                                <div class="color-container">

                                    <input type="color" name="descripcion_color" class="form-control color-picker"
                                        value="{{ $viewData['modulo']['descripcion_color'] ?? '#000000' }}" list="colores"
                                        title="Color de la descripción">

                                    <input type="text" class="form-control color-input"
                                        value="{{ $viewData['modulo']['descripcion_color'] ?? '#000000' }}" maxlength="7"
                                        placeholder="#000000" title="Color hexadecimal">

                                </div>

                            </div>

                        </div>

                        {{-- AUSPICIANTE --}}
                        <div class="mt-2">

                            <label>Auspiciante</label>

                            <div class="d-flex align-items-center">

                                <input name="auspiciante" class="form-control"
                                    value="{{ $viewData['modulo'] ? $viewData['modulo']['auspiciante'] : '' }}" />

                                <div class="color-container">

                                    <input type="color" name="auspiciante_color" class="form-control color-picker"
                                        value="{{ $viewData['modulo']['auspiciante_color'] ?? '#000000' }}" list="colores"
                                        title="Color del auspiciante">

                                    <input type="text" class="form-control color-input"
                                        value="{{ $viewData['modulo']['auspiciante_color'] ?? '#000000' }}" maxlength="7"
                                        placeholder="#000000" title="Color hexadecimal">

                                </div>

                            </div>

                        </div>

                        {{-- BACKGROUND --}}
                        <div class="mt-2">
                            <label>Background del módulo</label>
                            <div class="d-flex align-items-center">
                                <div class="color-container" style="margin-left: 0;">
                                    <input type="color" name="background" class="form-control color-picker"
                                        value="{{ $viewData['modulo']['background'] ?? '#ffffff' }}" list="colores"
                                        title="Color de fondo del módulo">
                                    <input type="text" class="form-control color-input"
                                        value="{{ $viewData['modulo']['background'] ?? '#ffffff' }}" maxlength="7"
                                        placeholder="#FFFFFF" title="Color hexadecimal">
                                </div>
                            </div>
                        </div>

                        {{-- PALETA PREDEFINIDA --}}
                        <datalist id="colores">
                            @foreach ($colores as $color)
                                <option value="{{ $color }}"></option>
                            @endforeach
                        </datalist>

                        {{-- DESDE / HASTA --}}
                        <div class="d-flex">
                            <div>
                                <label>Desde</label>
                                <input type="datetime-local" name="desde" class="form-control"
                                    value="{{ $viewData['modulo'] && $viewData['modulo']['desde'] ? substr($viewData['modulo']['desde'], 0, 16) : '' }}">
                            </div>
                            <div class="ms-2">
                                <label>Hasta</label>
                                <input type="datetime-local" name="hasta" class="form-control"
                                    value="{{ $viewData['modulo'] && $viewData['modulo']['hasta'] ? substr($viewData['modulo']['hasta'], 0, 16) : '' }}" />
                            </div>
                        </div>

                        {{-- UBICACION --}}
                        <div>
                            <label>Ubicación</label>
                            <select name="id_location" class="form-control">
                                <option value="">
                                    Seleccione una locación
                                </option>
                                @foreach ($viewData['locaciones'] as $locacion)
                                    <option value="{{ $locacion['id'] }}"
                                        {{ $viewData['modulo'] && $viewData['modulo']['id_location'] == $locacion['id'] ? 'selected' : '' }}>
                                        {{ $locacion['descripcion'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <div>
                        {{-- NOMBRE --}}
                        <div>
                            <label>* Nombre de Modulo</label>

                            <div class="d-flex align-items-center">

                                <input name="nombre" class="form-control" value="BREAK" required readonly />

                                <div class="color-container">

                                    <input type="color" name="nombre_color" class="form-control color-picker"
                                        value="{{ $viewData['modulo']['nombre_color'] ?? '#000000' }}" list="colores"
                                        title="Color del nombre">

                                    <input type="text" class="form-control color-input"
                                        value="{{ $viewData['modulo']['nombre_color'] ?? '#000000' }}" maxlength="7"
                                        placeholder="#000000" title="Color hexadecimal">

                                </div>

                            </div>
                        </div>

                        {{-- BACKGROUND --}}
                        <div class="mt-2">
                            <label>Background del módulo</label>
                            <div class="d-flex align-items-center">
                                <div class="color-container" style="margin-left: 0;">
                                    <input type="color" name="background" class="form-control color-picker"
                                        value="{{ $viewData['modulo']['background'] ?? '#ffffff' }}" list="colores"
                                        title="Color de fondo del módulo">
                                    <input type="text" class="form-control color-input"
                                        value="{{ $viewData['modulo']['background'] ?? '#ffffff' }}" maxlength="7"
                                        placeholder="#FFFFFF" title="Color hexadecimal">
                                </div>
                            </div>
                        </div>

                        {{-- PALETA PREDEFINIDA --}}
                        <datalist id="colores">
                            @foreach ($colores as $color)
                                <option value="{{ $color }}"></option>
                            @endforeach
                        </datalist>

                        {{-- DESDE / HASTA --}}
                        <div class="d-flex">
                            <div>
                                <label>Desde</label>
                                <input type="datetime-local" name="desde" class="form-control"
                                    value="{{ $viewData['modulo'] && $viewData['modulo']['desde'] ? substr($viewData['modulo']['desde'], 0, 16) : '' }}">
                            </div>
                            <div class="ms-2">
                                <label>Hasta</label>
                                <input type="datetime-local" name="hasta" class="form-control"
                                    value="{{ $viewData['modulo'] && $viewData['modulo']['hasta'] ? substr($viewData['modulo']['hasta'], 0, 16) : '' }}" />
                            </div>
                        </div>

                        {{-- UBICACION --}}
                        <div>
                            <label>Ubicación</label>
                            <select name="id_location" class="form-control">
                                <option value="">
                                    Seleccione una locación
                                </option>
                                @foreach ($viewData['locaciones'] as $locacion)
                                    <option value="{{ $locacion['id'] }}"
                                        {{ $viewData['modulo'] && $viewData['modulo']['id_location'] == $locacion['id'] ? 'selected' : '' }}>
                                        {{ $locacion['descripcion'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                {{-- GUARDAR --}}
                <div class="mt-2">
                    <button class="btn btn-success float-end" type="submit">
                        Guardar
                    </button>
                    <div style="clear: both;"></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.color-container').forEach(function(container) {
                const picker = container.querySelector('.color-picker');
                const input = container.querySelector('.color-input');

                // Picker -> Input
                picker.addEventListener('input', function() {
                    input.value = picker.value.toUpperCase();
                });

                // Input -> Picker
                input.addEventListener('input', function() {
                    let value = input.value.trim().toUpperCase();

                    // Agregar # automáticamente
                    if (value.length > 0 && !value.startsWith('#')) {
                        value = '#' + value;
                        input.value = value;
                    }

                    // Actualizar picker si es hexadecimal válido
                    if (/^#[0-9A-F]{6}$/.test(value)) {
                        picker.value = value;
                    }
                });

                // Si queda un valor inválido, volver al color anterior
                input.addEventListener('blur', function() {
                    const value = input.value.trim().toUpperCase();
                    if (!/^#[0-9A-F]{6}$/.test(value)) {
                        input.value = picker.value.toUpperCase();
                    }
                });
            });
        });
    </script>
@endsection
