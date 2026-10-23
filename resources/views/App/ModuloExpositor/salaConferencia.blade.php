<!doctype html>
@extends('layouts.app')

<?php
//dd($viewData['item']);
//dd(Auth::guard('expositor')->user());
?>
@section('content')
    <?php
    //dd($viewData['sala_conferencia'])
    ?>
    <style>
        #ui-datepicker-div {
            z-index: 99 !important;
        }

        .container-1 {
            display: flex;
        }

        .div-1 {
            flex: 0 0 auto;
            /* Ocupa solo el espacio necesario */
            margin-right: 20px;
            /* Espacio entre los divs si es necesario */
        }

        .div-2 {
            flex: 1;
            /* Ocupa el resto del espacio disponible */
            overflow: auto;
            /* Agrega scroll si el contenido es demasiado grande */
        }

        .link-button {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
            font-size: inherit;
            font-family: inherit;
            padding: 0;
        }

        .link-button:hover {
            color: darkblue;
        }
    </style>

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">

        @if (Auth::guard()->check())
            @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

            <div class="container">
                <div class="shadow p-3 mb-1 mt-1 bg-white rounded">
                    <h2 class="mb-3">
                        <u>Requerimientos - Sala de Conferencia</u>
                    </h2>

                </div>
                <button class="btn btn-success" onclick="window.history.back();"><- Volver</button>
            </div>
        @else
            @include('App.ModuloExpositor.header', [
                'title' => 'Solicitud de Sala de Conferencia',
                'requerimientos' => false,
            ])
        @endif

        @include('layouts.messages')

        <div class="expositor-modulo-invitaciones shadow-sm p-3 bg-white border position-relative mt-3 mb-5"
            style="min-height: calc(100vh - 55px);">

            <div class="float-end">
                <b>Fecha de Cierre de Modulo:
                    {{ \DateTime::createFromFormat('Y-m-d', $viewData['sala_conferencia_texto']['cierre'])->format('d/m/Y') }}
                </b>
            </div>
            <div style="clear: both;"></div>

            <div>
                <?php echo $viewData['sala_conferencia_texto']['descripcion']; ?>
            </div>
            <hr />
            <form method="POST" action="{{ route('expositor.modulo.sala-conferencia.update-store') }}"
                onsubmit="deshabilitarBoton()">
                @csrf

                @if ($viewData['item'])
                    <input hidden name="idItem" value="{{ $viewData['item']['id'] }}" />
                    <input hidden name="idExposicion" value="{{ $viewData['exposicion']['id'] }}" />
                    <input hidden name="idExpositor" value="{{ $viewData['idExpositor'] }}" />
                @endif

                <h4><u>Datos del Responsable</u></h4>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label>Apellido y Nombre</label>
                        <input {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} class="form-control"
                            name="responsable_apellido_nombre"
                            value="{{ $viewData['item'] ? $viewData['item']['responsable_apellido_nombre'] : '' }}" />
                    </div>
                    <div class="col-12 col-md-3">
                        <label>Cargo</label>
                        <input {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} class="form-control"
                            name="responsable_cargo"
                            value="{{ $viewData['item'] ? $viewData['item']['responsable_cargo'] : '' }}" />
                    </div>
                    <div class="col-12 col-md-3">
                        <label>Email</label>
                        <input {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} class="form-control"
                            name="responsable_email" type="email"
                            value="{{ $viewData['item'] ? $viewData['item']['responsable_email'] : '' }}" />
                    </div>
                    <div class="col-12 col-md-3">
                        <label>Teléfono</label>
                        <input {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} class="form-control"
                            name="responsable_telefono"
                            value="{{ $viewData['item'] ? $viewData['item']['responsable_telefono'] : '' }}" />
                    </div>
                </div>

                <hr />

                <div class="">
                    <h4><u>Disertantes</u></h4>
                    @if (!$viewData['item'] || ($viewData['item'] && !Auth::guard('expositor')->user()))
                        <button type="button" id="add-disertante" class="btn btn-primary">Agregar Disertante</button>
                    @endif
                </div>

                <div id="disertante-container" class="mb-3 d-flex flex-wrap justify-content-around">
                    @if ($viewData['item'])
                        @foreach ($viewData['item']->expositorModuloSalaConferenciaDisertante as $key => $disertante)
                            <div class="p-2 border shadow-sm me-2 mt-2" id="disertante-{{ $key }}"
                                style="width: 300px;">
                                <label>Apellido y Nombre</label>
                                <input type="text" name="apellido_nombre[]" class="form-control"
                                    value="{{ $disertante['apellido_nombre'] }}"
                                    {{ Auth::guard('expositor')->user() ? 'disabled=true' : '' }}>
                                <label>Email</label>
                                <input type="email" name="email[]" class="form-control"
                                    value="{{ $disertante['email'] }}"
                                    {{ Auth::guard('expositor')->user() ? 'disabled=true' : '' }}>
                                <label>Telefono</label>
                                <input type="text" name="telefono[]" class="form-control"
                                    value="{{ $disertante['telefono'] }}"
                                    {{ Auth::guard('expositor')->user() ? 'disabled=true' : '' }}>
                                <label>Facebook</label>
                                <input type="text" name="facebook[]" class="form-control"
                                    value="{{ $disertante['facebook'] }}"
                                    {{ Auth::guard('expositor')->user() ? 'disabled=true' : '' }}>
                                <label>Instagram</label>
                                <input type="text" name="instagram[]" class="form-control"
                                    value="{{ $disertante['instagram'] }}"
                                    {{ Auth::guard('expositor')->user() ? 'disabled=true' : '' }}>
                                <label>Linkedin</label>
                                <input type="text" name="linkedin[]" class="form-control"
                                    value="{{ $disertante['linkedin'] }}"
                                    {{ Auth::guard('expositor')->user() ? 'disabled=true' : '' }}>
                                <label>Youtube</label>
                                <input type="text" name="youtube[]" class="form-control"
                                    value="{{ $disertante['youtube'] }}"
                                    {{ Auth::guard('expositor')->user() ? 'disabled=true' : '' }}>
                                @if (!$viewData['item'] || ($viewData['item'] && !Auth::guard('expositor')->user()))
                                    <div class="text-center mt-1">
                                        <button type="button" class="btn btn-danger"
                                            onclick="$('#disertante-{{ $key }}').remove();">Descartar</button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <hr />

                <h4><u>Datos de la Conferencia</u></h4>

                <div class="d-flex">
                    <div style="width: 30%;">
                        <label>Titulo de la Charla</label>
                        <input {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }}
                            class="form-control mb-2" name="titulo_charla" style="width: 100%;" required
                            value="{{ $viewData['item'] ? $viewData['item']['titulo_charla'] : '' }}" />
                    </div>
                    <div class="ms-2">
                        <label>Sala</label>
                        <select {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} required
                            id="sala" style="width: 200px;" class="form-control">
                            <option hidden value=""></option>
                            @foreach ($viewData['sala_conferencia'] as $key => $item)
                                <option value="{{ $key }}"
                                    {{ $viewData['item'] && $viewData['item']->moduloSalaConferencia && $viewData['item']->moduloSalaConferencia['sala'] == $key ? 'selected=true' : '' }}>
                                    {{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ms-2">
                        <label>Día</label>
                        <input {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} required type="text"
                            id="fecha" class="form-control" style="width: 200px;"
                            value="{{ $viewData['item'] ? substr($viewData['item']->moduloSalaConferencia['fecha'], 0, 10) : '' }}" />
                    </div>
                    <div class="ms-2">
                        <label>Horario</label>
                        <select {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} required
                            id="horario" name="id_sala" class="form-control" style="width: 200px;">
                            @if ($viewData['item'])
                                <option selected value="{{ $viewData['item']->moduloSalaConferencia['id'] }}">
                                    {{ substr($viewData['item']->moduloSalaConferencia['fecha'], 11, 5) }}
                                    ({{ $viewData['item']->moduloSalaConferencia['duracion'] }} minutos)
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <textarea {{ $viewData['item'] && !Auth::guard()->check() ? 'disabled' : '' }} id="mytextarea" name="temario"
                    style="resize: none !important;" placeholder="TEMARIO">
                    @if ($viewData['item'])
{{ $viewData['item']['temario'] }}
@endif
                </textarea>
                <script type="text/javascript">
                    let temarioReadonly = @json($viewData['item'] && !Auth::guard()->check() ? true : false);
                    tinymce.init({
                        selector: '#mytextarea',
                        license_key: 'gpl',
                        resize: false,
                        readonly: temarioReadonly
                    });
                </script>
                @if (!$viewData['item'] || ($viewData['item'] && !Auth::guard('expositor')->user()))
                    <div class="text-center">
                        <button id="subitSalaConferencia" type="submit" class="btn btn-success mt-2"
                            style="font-size: 18px">Enviar</button>
                    </div>
                    <script>
                        function deshabilitarBoton() {
                            const boton = document.getElementById('subitSalaConferencia');
                            boton.disabled = true;
                            boton.innerText = 'Enviando...';
                        }
                    </script>
                @endif
            </form>

            @if (count($viewData['sala_conferencia_pedidos']) && Auth::guard('expositor'))
                <hr />

                <h4><u>Pedidos realizados de Sala de Conferencia</u></h4>

                <table class="table">
                    <thead>
                        <tr class="table-dark">
                            <th>Sala</th>
                            <th>Día / Hora</th>
                            <th>Titulo</th>
                            <th>Temario</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($viewData['sala_conferencia_pedidos'] as $item)
                            @if ($item->moduloSalaConferencia)
                                <tr>
                                    <td>{{ $item->moduloSalaConferencia['sala'] }}</td>
                                    <td>{{ substr($item->moduloSalaConferencia['fecha'], 0, 16) }}</td>
                                    <td>{{ $item['titulo_charla'] }}</td>
                                    <!--<td style="width: 50%;"><?php echo $item['temario']; ?></td>-->
                                    <td>
                                        <button type="button" class="link-button" data-bs-toggle="modal"
                                            data-bs-target="#modalTemario"
                                            onclick="setTemario('{{ $item['id'] }}','{{ $item->moduloSalaConferencia['fecha'] }}', '{{ $item->moduloSalaConferencia['duracion'] }}', '{{ $item->moduloSalaConferencia['sala'] }}',`{{ $item['temario'] }}`);">
                                            Ver
                                        </button>
                                    </td>
                                    <td>{{ $item->moduloSalaConferencia['duracion'] }}</td>
                                    <td>
                                        <button disabled
                                            class="btn btn-{{ $item['status'] == 'pendiente' ? 'warning' : ($item['status'] == 'cancelado' ? 'danger' : 'success') }}">
                                            {{ $item['status'] == 'cancelado' ? 'cancelado, solicite nuevo turno' : $item['status'] }}
                                        </button>
                                    </td>
                                    <td>
                                        @if ($item['status'] != 'cancelado')
                                            <a class="btn btn-danger"
                                                href="{{ route('expositor.modulo.sala-conferencia.cancelar', ['id' => $item['id']]) }}"
                                                onclick="return confirm('¿Confirmá cencaler el turno de la sala de conferencia {{ $item->moduloSalaConferencia['sala'] }} de la fecha {{ substr($item->moduloSalaConferencia['fecha'], 0, 16) }}?')">Cancelar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalTemario" tabindex="-1" aria-labelledby="modalTemarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTemarioLabel"></h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div id="bodyModalTemario" class="modal-body">

                </div>
                <div class="modal-footer">
                    <a href="" id="linkToIndex" class="btn btn-success">+ Ver</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let salaConferenia = @json($viewData['sala_conferencia']);
        let salaConferenciaPedidos = @json($viewData['sala_conferencia_pedidos']);
        let disertanteIndex = {!! isset($viewData['item']) && $viewData['item']->expositorModuloSalaConferenciaDisertante
            ? count($viewData['item']->expositorModuloSalaConferenciaDisertante)
            : 0 !!};
        let urlSalaConferenciaIndex = "{{ route('expositor.modulo.sala-conferencia.index') }}";

        function setTemario(id, fecha, duracion, sala, temario) {
            $('#linkToIndex').attr('href', urlSalaConferenciaIndex + '?id=' + id)
            let html = '<b><u>Temario ' +
                temario;
            $('#bodyModalTemario').empty();
            $('#bodyModalTemario').append(temario);
            $('#modalTemarioLabel').html(
                'Fecha: ' + fecha + '<br/>' +
                'Duración: ' + duracion + ' minutos' + '<br/>' +
                'Sala: ' + sala
            );
        }

        $(document).ready(function() {

            $('#sala').on('change', function() {
                var salaSelected = $(this).val();


                $('#fecha').empty();
                $('#horario').empty();
                $('#horario').attr('disabled', true);

                let fechasPermitidas = [];

                salaConferenia[salaSelected].forEach(sala => {
                    fechasPermitidas.push(sala.fecha.substring(0, 10));
                });

                console.log(salaConferenia);

                $('#fecha').attr('disabled', false);

                const primeraFecha = fechasPermitidas[0];

                function isDateAllowed(date) {
                    const year = date.getFullYear();
                    const month = ('0' + (date.getMonth() + 1)).slice(-2);
                    const day = ('0' + date.getDate()).slice(-2);
                    const dateString = `${year}-${month}-${day}`;

                    return fechasPermitidas.includes(dateString);
                }

                $('#fecha').datepicker('option', 'beforeShowDay', function(date) {
                    return isDateAllowed(date) ? [true, "", ""] : [false, "", ""];
                });

                $('#fecha').datepicker('setDate', null); // Limpiar fecha seleccionada
                $('#fecha').datepicker('option', 'defaultDate',
                    primeraFecha); // Establecer nueva fecha predeterminada

                $('#fecha').datepicker({
                    dateFormat: 'yy-mm-dd',
                    defaultDate: primeraFecha,
                    beforeShowDay: function(date) {
                        if (isDateAllowed(date)) {
                            return [true, "", ""]; // Habilitar la fecha
                        } else {
                            return [false, "", ""]; // Deshabilitar la fecha
                        }
                    }
                });

                $('#fecha').on('change', function() {

                    var diaSelected = $(this).val();
                    let horarios = [];

                    $('#horario').empty();
                    $('#horario').append(`<option hidden value=""></option>`);



                    salaConferenia[salaSelected].forEach(sala => {

                        if (sala.fecha.includes(diaSelected)) {
                            $('#horario').append(
                                `<option value="${sala.id}" ${sala.status == 'ocupado' ? 'disabled' : ''}>${sala.fecha.substring(11, 16)} - (${sala.duracion} minutos) ${sala.status == 'ocupado' ? 'Cartomar S.A.' : ''}</option>`
                            );
                        }
                    });

                    $('#horario').attr('disabled', false);

                });
            });

            $('#add-disertante').click(function() {
                disertanteIndex++;
                const disertanteRow = `
                    <div class="p-2 border shadow-sm me-2 mt-2" id="disertante-${disertanteIndex}" style="width: 300px;">
                        <label>Apellido y Nombre</label>
                        <input type="text" name="apellido_nombre[]" class="form-control">
                        <label>Email</label>
                        <input type="email" name="email[]" class="form-control">
                        <label>Telefono</label>
                        <input type="text" name="telefono[]" class="form-control">
                        <label>Facebook</label>
                        <input type="text" name="facebook[]" class="form-control">
                        <label>Instagram</label>
                        <input type="text" name="instagram[]" class="form-control">
                        <label>Linkedin</label>
                        <input type="text" name="linkedin[]" class="form-control">
                        <label>Youtube</label>
                        <input type="text" name="youtube[]" class="form-control">
                        <div class="text-center mt-1">
                            <button type="button" class="btn btn-danger" onclick="$('#disertante-${disertanteIndex}').remove();">Descartar</button>
                        </div>
                    </div>
                `;
                $('#disertante-container').append(disertanteRow);
            });

            $(document).on('click', '.remove-btn', function() {
                const rowId = $(this).data('id');
                $(`#${rowId}`).remove();
            });


            @if ($viewData['item'] && !Auth::guard('expositor')->user())
                $('#sala').trigger('change');
                $('#fecha').trigger('change');

                let item = @json($viewData['item']);
                let labelHorario =
                    `${item.modulo_sala_conferencia.fecha.substring(11, 16)} - (${item.modulo_sala_conferencia.duracion} minutos)`;
                let valor = @json($viewData['item']['id_modulo_sala_conferencia']);

                // Buscamos si ya existe el option
                let option = $('#horario option[value="' + valor + '"]');

                if (option.length) {
                    option.text(labelHorario); // actualizamos label si existe
                    option.prop('selected', true);
                } else {
                    // Creamos un option oculto y seleccionado
                    $('#horario').append(
                        $('<option>', {
                            value: valor,
                            text: labelHorario,
                            selected: true,
                            hidden: true
                        })
                    );
                }
            @endif
        });
    </script>
@endsection
