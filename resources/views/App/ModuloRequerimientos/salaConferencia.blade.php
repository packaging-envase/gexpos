@extends('layouts.app')

@section('content')
    <style>
        .tr-highlighted td {
            background-color: #6C757D !important;
            color: white !important;
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
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Requerimientos - Sala de Conferencia</u>
            </h2>

            @include('layouts.messages')

            <a class="btn btn-success"
                href="{{ route('modulo.sala-conferencia.exportar-excel', ['id' => $viewData['exposicion']['id']]) }}">Exportar
                Excel</a>
            <a class="btn btn-primary ms-2"
                href="{{ route('modulo.sala-conferencia.publicar-todo', ['idExposicion' => $viewData['exposicion']['id']]) }}">Publicar
                Todos los Asignados</a>
            <a class="btn btn-primary float-end" target="_blank"
                href="{{ route('public.sala-conferencia', ['idExposicion' => $viewData['exposicion']['id'], 'preview' => true]) }}">Ver
                Preview</a>
            <a class="btn btn-success float-end me-2"
                href="{{ route('modulo.sala-conferencia.publicar-web', ['id' => $viewData['exposicion']['id']]) }}">Publicar
                en Web</a>
            <div class="d-flex mt-2">
                <div class="w-50 table-responsive" style="height: calc(100vh - 360px);">
                    <table class="table">
                        <thead>
                            <tr class="table-dark">
                                <th style="position: sticky; top: 0;">Fecha</th>
                                <th style="position: sticky; top: 0;">Sala</th>
                                <th style="position: sticky; top: 0;">Duración</th>
                                <th style="position: sticky; top: 0;">Estado</th>
                                <th style="position: sticky; top: 0;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($viewData['moduloSalaConferencia'])) {
                                $day = substr($viewData['moduloSalaConferencia'][0]['fecha'], 0, 10);
                            }
                            ?>
                            @foreach ($viewData['moduloSalaConferencia'] as $key => $item)
                                <?php
                                $pendiente = false;
                                if ($item['status'] != 'asignado') {
                                    foreach ($item['expositorModuloSalaConferencia'] as $keyPedido => $pedido) {
                                        if ($pedido['status'] == 'pendiente') {
                                            $pendiente = true;
                                            break;
                                        }
                                    }
                                }
                                ?>

                                @if ($day != substr($item['fecha'], 0, 10))
                                    <?php $day = substr($item['fecha'], 0, 10); ?>
                                    <tr>
                                        <td colspan="5">
                                            <hr />
                                        </td>
                                    </tr>
                                @endif
                                <tr style="cursor: pointer;"
                                    onclick="highlighted(this); selectSala('{{ $key }}');">
                                    <td style="{{ $pendiente ? 'background-color: #FFA500;' : '' }}">
                                        {{ substr($item['fecha'], 0, 16) }}</td>
                                    <td style="{{ $pendiente ? 'background-color: #FFA500;' : '' }}">
                                        {{ $item['sala'] }}
                                    </td>
                                    <td style="{{ $pendiente ? 'background-color: #FFA500;' : '' }}">
                                        {{ $item['duracion'] }}
                                    </td>
                                    <td style="{{ $pendiente ? 'background-color: #FFA500;' : '' }}">
                                        {{ $item['status'] }}
                                    </td>
                                    <td style="{{ $pendiente ? 'background-color: #FFA500;' : '' }}">
                                        @if ($item['status'] == 'asignado')
                                            @if ($item['publicado'])
                                                <a class="btn btn-danger"
                                                    href="{{ route('modulo.sala-conferencia.switch-publicado', ['id' => $item['id']]) }}">No
                                                    publicar</a>
                                            @else
                                                <a class="btn btn-success"
                                                    href="{{ route('modulo.sala-conferencia.switch-publicado', ['id' => $item['id']]) }}">Publicar</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="w-50">
                    <table class="table">
                        <thead>
                            <tr class="table-dark">
                                <th>Empresa</th>
                                <th>Temario</th>
                                <th>Estado</th>
                                <th>Fecha de Pedido</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tbodyPedidos">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalTemario" tabindex="-1" aria-labelledby="modalTemarioLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTemarioLabel"></h5>
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

        <script>
            let moduloSalaConferencia = @json($viewData['moduloSalaConferencia']);
            let hrefAcepterSala = "{{ route('modulo.sala-conferencia.aceptar-sala') }}";
            let hrefCancelarSala = "{{ route('modulo.sala-conferencia.cancelar-sala') }}";
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let urlSalaConferenciaIndex = "{{ route('expositor.modulo.sala-conferencia.index') }}";

            function selectSala(key) {

                $('#tbodyPedidos').empty();
                moduloSalaConferencia[key]['expositor_modulo_sala_conferencia'].forEach((pedido, index) => {
                    let formAceptarSala = $(`<form method="POST" action="${hrefAcepterSala}"></form>`);
                    formAceptarSala.append('<input type="hidden" name="_token" value="' + csrfToken + '">');
                    formAceptarSala.append(`<input hidden name="id" value="${pedido['id']}" />`);
                    formAceptarSala.append('<button class="btn btn-success" type="submit">Aceptar</button>');

                    let formCancelarSala = $(`<form method="POST" action="${hrefCancelarSala}"></form>`);
                    formCancelarSala.append('<input type="hidden" name="_token" value="' + csrfToken + '">');
                    formCancelarSala.append(`<input hidden name="id" value="${pedido['id']}" />`);
                    formCancelarSala.append('<button class="btn btn-danger" type="submit">Cancelar</button>');

                    $('#tbodyPedidos').append(`
                    <tr>
                        <td>
                            ${ pedido['expositor']['empresa'] ? pedido['expositor']['empresa']['razon_social'] : ''}
                            ${ pedido['expositor']['id'] }
                        </td>
                        <td>
                            <button type="button" class="link-button" data-bs-toggle="modal" data-bs-target="#modalTemario" onclick="setTemario({{ $viewData['exposicion']['id'] }},${pedido['id_expositor']},${pedido['id']},${key},${index});">
                                Ver
                            </button>
                        </td>
                        <td>${pedido['status']}</td>
                        <td>${pedido['created_at'].replace('T', ' ').slice(0, 16)}</td>
                        <td class="${pedido['status'] == 'asignado' ? 'd-flex' : ''}">
                            ${pedido['status'] == 'pendiente' ? 
                                formAceptarSala.prop('outerHTML') : ''}
                            ${pedido['status'] != 'cancelado' ? 
                                formCancelarSala.prop('outerHTML') : ''}
                        </td>
                    </tr>
                `);

                });
            }

            function highlighted(row) {
                console.log(row);
                // Remueve la clase 'bg-secondary' de todas las filas
                const rows = document.querySelectorAll('tr');
                rows.forEach(r => r.classList.remove('tr-highlighted'));

                // Agrega la clase 'bg-secondary' a la fila seleccionada
                row.classList.add('tr-highlighted');

            }

            function setTemario(idExposicion, idExpositor, id, key, index) {
                $('#linkToIndex').attr('href', urlSalaConferenciaIndex + '?id=' + id + '&idExpositor=' + idExpositor +
                    '&idExposicion=' + idExposicion)
                let html = '<b><u>Temario ' +
                    moduloSalaConferencia[key]['expositor_modulo_sala_conferencia'][index]['expositor']['empresa'][
                        'razon_social'
                    ] + '</u></b>:' + '<br/>' +
                    moduloSalaConferencia[key]['expositor_modulo_sala_conferencia'][index]['temario'];
                $('#bodyModalTemario').empty();
                $('#bodyModalTemario').append(html);
                $('#modalTemarioLabel').html(
                    'Fecha: ' + moduloSalaConferencia[key]['fecha'] + '<br/>' +
                    'Duración: ' + moduloSalaConferencia[key]['duracion'] + ' minutos' + '<br/>' +
                    'Sala: ' + moduloSalaConferencia[key]['sala']
                );
            }
        </script>
    @endsection
