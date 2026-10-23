<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin-bottom: 25px;
        border: 1px solid black;
    }

    th,
    td {
        border: 1px solid black;
        padding: 6px;
        vertical-align: top;
    }

    thead th.titulo {
        background: #2f5f6f;
        color: #fff;
        font-size: 16px;
        text-align: center;
    }

    thead th.auditorio {
        background: rgb(208, 224, 227);
        text-align: center;
    }

    thead th.hora {
        background: #f2b84b;
        text-align: center;
        width: 90px;
    }

    td.hora {
        background: #fce4b8;
        font-weight: bold;
        text-align: center;
        white-space: nowrap;
    }

    .nombre-modulo {
        font-weight: bold;
    }

    .disertante {
        display: block;
        margin-top: 4px;
    }

    .auspiciante {
        font-weight: bold;
    }
</style>


@php

    /*
    |--------------------------------------------------------------------------
    | 1. AGRUPAR LAS FILAS POR MÓDULO
    |--------------------------------------------------------------------------
    */

    $modulos = $congreso
        ->groupBy('modulo_id')
        ->filter(function ($rows) {
            $first = $rows->first();

            // Excluir si ya tiene desde y hasta
            return $first->modulo_desde && $first->modulo_hasta;
        })
        ->map(function ($rows) {
            $first = $rows->first();

            return (object) [
                'id' => $first->modulo_id,

                'nombre' => $first->modulo_nombre,
                'nombre_color' => $first->modulo_nombre_color ?? '#000000',

                'descripcion' => $first->modulo_descripcion,
                'descripcion_color' => $first->modulo_descripcion_color ?? '#000000',

                'auspiciante' => $first->modulo_auspiciante,
                'auspiciante_color' => $first->modulo_auspiciante_color ?? '#000000',

                'background' => $first->modulo_background ?? '#ffffff',

                'desde' => $first->modulo_desde,
                'hasta' => $first->modulo_hasta,

                // Lugar / auditorio
                'auditorio' => $first->descripcion,

                'disertantes' => $rows->pluck('disertante_nombre')->filter()->unique()->values(),
                'disertantes_titulos' => $rows->pluck('disertante_titulo')->filter()->unique()->values(),
            ];
        })
        ->sortBy('desde')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | 2. AGRUPAR LOS MÓDULOS POR DÍA
    |--------------------------------------------------------------------------
    */

    $porFecha = $modulos->groupBy(function ($modulo) {
        return \Carbon\Carbon::parse($modulo->desde)->format('Y-m-d');
    });

    /*
    |--------------------------------------------------------------------------
    | 3. CADA FECHA ES UN BLOQUE / TABLA INDEPENDIENTE
    |--------------------------------------------------------------------------
    */

@endphp


@foreach ($porFecha as $fecha => $modulosDelDia)
    @php

        /*
        |--------------------------------------------------------------------------
        | LUGARES / AUDITORIOS DEL DÍA
        |--------------------------------------------------------------------------
        */

        $auditorios = $modulosDelDia->pluck('auditorio')->filter()->unique()->values();

        /*
        |--------------------------------------------------------------------------
        | HORARIOS DEL DÍA
        |--------------------------------------------------------------------------
        */

        $horarios = $modulosDelDia->sortBy('desde')->groupBy(function ($modulo) {
            return \Carbon\Carbon::parse($modulo->desde)->format('H:i') .
                ' a ' .
                \Carbon\Carbon::parse($modulo->hasta)->format('H:i');
        });

        /*
        |--------------------------------------------------------------------------
        | FECHA PARA MOSTRAR
        |--------------------------------------------------------------------------
        */

        \Carbon\Carbon::setLocale('es');

        $fechaMostrar = \Carbon\Carbon::parse($fecha)->translatedFormat('l d \d\e F');

    @endphp


    {{-- ================================================================
         BLOQUE DEL DÍA
    ================================================================= --}}

    <table class="" style="width: 100%; table-layout: fixed;">

        <thead>
            {{-- ========================================================
                 FECHA
            ========================================================= --}}
            <tr>
                <th style="width: 90px;"></th>
                <th class="titulo" colspan="{{ $auditorios->count() }}">
                    {{ ucfirst($fechaMostrar) }}
                </th>
            </tr>


            {{-- ========================================================
                 LUGARES
            ========================================================= --}}

            <tr>
                <th class="hora" style="border: 1px solid black;">
                    HORA
                </th>
                @foreach ($auditorios as $auditorio)
                    <th class="auditorio" style="border: 1px solid black;">
                        {{ $auditorio }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            {{-- ========================================================
                 FILAS POR HORARIO
            ========================================================= --}}
            @foreach ($horarios as $horario => $modulosDelHorario)
                <tr>
                    {{-- ==================================================
                         HORA
                    =================================================== --}}
                    <td class="hora" style="width: 100px; border: 1px solid black;">
                        {{ $horario }}
                    </td>

                    {{-- ==================================================
                         COLUMNAS POR LUGAR
                    =================================================== --}}
                    <?php 
                        //dd($horarios); 
                    ?>
                    @foreach ($auditorios as $auditorio)
                        @php
                            $modulo = $modulosDelHorario->firstWhere('auditorio', $auditorio);
                        @endphp

                        @if ($modulo)
                            @if (isset($modulo) && $modulo && $modulo->nombre == 'BREAK')
                                
                                <td
                                    colspan="4"
                                    style="
                                        text-align: center;
                                        background-color: {{ $modulo ? $modulo->background : '#ffffff' }};
                                    ">
                                    <span class="nombre-modulo"
                                        style="
                                            color: {{ $modulo->nombre_color }};
                                        ">
                                        {{ $modulo->nombre }}
                                    </span>
                                </td>
                                @continue
                            @else
                                <td
                                    style="
                                        text-align: center;
                                        width: calc((100% - 100px) / 4);
                                        background-color: {{ $modulo ? $modulo->background : '#ffffff' }};
                                        border: 1px solid black;
                                    ">                                    

                                    @if ($modulo)
                                        {{-- ==================================================
                                            NOMBRE
                                        =================================================== --}}
                                        <span class="nombre-modulo"
                                            style="
                                                color: {{ $modulo->nombre_color }};
                                            ">
                                            {{ $modulo->nombre }}
                                        </span>


                                        {{-- ==================================================
                                            DESCRIPCIÓN
                                        =================================================== --}}
                                        @if ($modulo->descripcion)
                                            <br>
                                            @if (strlen($modulo->descripcion) > 100)
                                                <span style="color: {{ $modulo->descripcion_color }}; cursor: pointer;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDescripcion{{ $modulo->id }}">

                                                    {{ Str::limit($modulo->descripcion, 100, '...') }}

                                                </span>
                                            @else
                                                <span style="color: {{ $modulo->descripcion_color }};">
                                                    {{ $modulo->descripcion }}
                                                </span>
                                            @endif
                                        @endif

                                        {{-- ==================================================
                                            DISERTANTES
                                        =================================================== --}}
                                        @foreach ($modulo->disertantes as $key => $disertante)
                                            <?php 
                                                //dd($modulo->disertantes_titulos[$key]); 
                                            ?>
                                            <span class="disertante">
                                                {{ isset($modulo->disertantes_titulos[$key]) && $modulo->disertantes_titulos[$key] ? $modulo->disertantes_titulos[$key] . ' ' : '' }}
                                                {{ $disertante }}
                                            </span>
                                        @endforeach


                                        {{-- ==================================================
                                            AUSPICIANTE
                                        =================================================== --}}
                                        @if ($modulo->auspiciante)
                                            <br>
                                            <span class="auspiciante"
                                                style="color: {{ $modulo->auspiciante_color }};">
                                                {{ $modulo->auspiciante }}
                                            </span>
                                        @endif
                                    @endif
                            @endif
                        @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

{{-- ================================================================
     MODALES
================================================================ --}}

@foreach ($modulos as $modulo)
    @if ($modulo->descripcion && strlen($modulo->descripcion) > 100)
        <div class="modal fade" id="modalDescripcion{{ $modulo->id }}" tabindex="-1"
            aria-labelledby="modalDescripcionLabel{{ $modulo->id }}" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="modalDescripcionLabel{{ $modulo->id }}">
                            {{ $modulo->nombre }}
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                        </button>

                    </div>

                    <div class="modal-body">

                        <p>
                            {{ $modulo->descripcion }}
                        </p>

                        @if ($modulo->auspiciante)
                            <hr>

                            <strong>Auspiciante:</strong>
                            {{ $modulo->auspiciante }}
                        @endif

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>

                    </div>

                </div>

            </div>

        </div>
    @endif
@endforeach
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
