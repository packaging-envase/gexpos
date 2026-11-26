<!doctype html>
@extends('layouts.app')

@section('content')
    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">
        @include('App.ModuloExpositor.header', [
            'title' => 'Elementos Opcionales',
            'requerimientos' => false,
        ])

        <div class="mt-3 expositor-modulo-invitaciones mb-5" style="min-height: calc(100vh - 55px);">
            @include('layouts.messages')

            <div class="shadow-sm my-3 border p-3">
                <p>El expositor deberá solicitar o rechazar los siguientes elementos optativos:</p>
                <p><b>Pendientes:</b> {{ $viewData['pendientes'] }}</p>
                <p><b>Solicitados:</b> {{ $viewData['solicitados'] }}</p>
                <p><b>Rechazados:</b> {{ $viewData['rechazados'] }}</p>
            </div>
            @if (!$viewData['pendientes'] && !$viewData['elementos'][0]['confirmado'])
                @if (count($viewData['elementos']) && $viewData['elementos'][0]['cierre'] > date('Y-m-d'))
                    <a id="confirmarBtn" class="btn btn-success float-end my-2"
                        href="{{ route('expositor.modulo.elementos-opcionales.confirmar') }}">
                        Confirmar
                    </a>
                @endif

                <script>
                    document.getElementById('confirmarBtn').addEventListener('click', function(e) {
                        const btn = e.currentTarget;

                        // Evita múltiples clics
                        if (btn.dataset.clicked) {
                            e.preventDefault();
                            return false;
                        }

                        btn.dataset.clicked = "true"; // Marca como clickeado

                        // Cambia el contenido por el spinner
                        btn.innerHTML = `
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="visually-hidden">Cargando...</span>
                        `;

                        // Opcional: deshabilita interacción visual
                        btn.classList.add('disabled');
                    });
                </script>

                <div style="clear: both;"></div>
            @endif
            <table class="table table-striped">
                <thead>
                    <tr class=table-dark>
                        <th style="white-space: nowrap;">Titluo</th>
                        <th style="white-space: nowrap;">Descripción</th>
                        <th style="white-space: nowrap;"></th>
                        <th style="white-space: nowrap;">Comentario</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['elementos'] as $elemento)
                        <?php 
                            //dd(date('Y-m-d'), $elemento['cierre']); 
                        ?>
                        <form id="formElemento{{ $elemento['id'] }}" method="POST"
                            action="{{ route('expositor.modulo.elementos-opcionales.update-store') }}">
                            @csrf
                            @if ($elemento['idElementoExpositor'])
                                <input hidden name="idElementoExpositor" value="{{ $elemento['idElementoExpositor'] }}" />
                            @endif
                            <input hidden name="id_modulo_elem_op" value="{{ $elemento['id'] }}" />
                            <tr>
                                <td style="white-space: nowrap;">
                                    {{ $elemento['titulo'] }}<br />
                                    @if ($elemento['idElementoExpositor'] && !$elemento['rechazado'])
                                        <button disabled class="btn btn-success btn-sm">Solicitado</button>
                                    @endif
                                    @if ($elemento['rechazado'])
                                        <button disabled class="btn btn-danger btn-sm">Rechazado</button>
                                    @endif
                                    @if (!$elemento['idElementoExpositor'])
                                        <button disabled class="btn btn-warning btn-sm">Pendiente</button>
                                    @endif
                                </td>
                                <td><?php echo $elemento['descripcion']; ?></td>
                                <td style="white-space: nowrap;">
                                    @if (date('Y-m-d') <= $elemento['cierre'] )
                                        @if (!$elemento['confirmado'])
                                            @if (!$elemento['rechazado'])
                                                <button type="submit" class="btn btn-danger" name="action" value="rechazar">
                                                    Rechazar
                                                </button>
                                            @endif
                                            @if (!$elemento['idElementoExpositor'] || $elemento['rechazado'])
                                                <button type="submit" class="btn btn-success" name="action" value="solicitar">
                                                    Solicitar
                                                </button>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                                <td style="min-width: 300px;">
                                    <textarea name="observacion" class="w-100 text-start" rows="3" style="resize: none !important;"
                                        {{ $elemento['confirmado'] ? 'disabled=true' : '' }}>
@if ($elemento['observacion'])
{{ $elemento['observacion'] }}
@endif
</textarea>
                                </td>
                                <td>
                                    @if (date('Y-m-d') <= $elemento['cierre'] )
                                        @if (!$elemento['confirmado'])
                                            <button class="btn btn-success" style="white-space: nowrap;" name="action"
                                                value="enviar">
                                                Enviar
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </form>
                    @endforeach
                </tbody>
            </table>
            {{--
                    <div class="col-12">
                        <div class="shadow-sm border p-1 mt-3 position-relative">
                            <div class="float-end">
                                @if ($elemento['observacion'] && !$elemento['rechazado'])
                                    <button disabled class="btn btn-success btn-sm">Solicitado</button>
                                @endif
                                @if ($elemento['rechazado'])
                                    <button disabled class="btn btn-danger btn-sm">Rechazado</button>
                                @endif
                                @if (!$elemento['rechazado'] && !$elemento['observacion'])
                                    <button disabled class="btn btn-warning btn-sm">Pendiente</button>
                                @endif
                            </div>
                            <h4 class="text-center">{{ $elemento['titulo'] }}</h4>
                            <form method="POST"
                                action="{{ route('expositor.modulo.elementos-opcionales.update-store') }}">
                                @csrf

                                @if ($elemento['idElementoExpositor'])
                                    <input hidden name="idElementoExpositor" value="{{$elemento['idElementoExpositor']}}" />
                                @endif

                                <input hidden name="id_modulo_elem_op" value="{{$elemento['id']}}" />
                                <?php echo $elemento['descripcion']; ?>
                                <label>Observación:</label>
                                <textarea name="observacion" class="w-100 text-start" rows="4" style="resize: none !important;">@if ($elemento['observacion']) {{$elemento['observacion']}}@endif</textarea>
                                
                                <div class="float-start">
                                    @if (!$elemento['rechazado'])
                                        <button type="submit" class="btn btn-danger">
                                            Rechazar
                                        </button>
                                    @endif
                                </div>
                                <div class="float-end">
                                    @if ($elemento['observacion'])
                                        <button type="submit" class="btn btn-success">
                                            Actualizar
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success">
                                            Aceptar
                                        </button>
                                    @endif
                                </div>
                                <div style="clear: both;"></div>
                            </form>
                        </div>
                    </div>
                --}}
        </div>
    </div>
@endsection
