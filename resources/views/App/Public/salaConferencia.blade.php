@extends('layouts.app')

@section('content')
    <style>
        .table {
            --bs-table-striped-bg: #d9e9ff;
            /* azul claro para filas impares */
            --bs-table-hover-bg: #a6d1ff;
            /* azul más intenso al pasar el mouse */
            --bs-table-active-bg: #c0dfff;
            /* azul para fila activa */
        }
    </style>    
    <div id="publicCatalogo" class="container public-catalogo mb-4 {{ !Auth::guard()->check() ? 'mt-3' : '' }} pt-2">
        <!--<div class="bg-white shadow px-3 pt-1">-->
        <div>
            <a href="https://envase.org/acreditacion/" target="_blank">Acredítese sin cargo y participe de las charlas</a><br/>
            La participación en las charlas es sin cargo. Requiere estar acreditado a la exposición. <br/>
            Dándole click a la charla de su interés, podrá acceder al temario e información sobre disertantes. Puede contactar al responsable de cada charla para más información.<br/>
        </div>
        <hr/>
        <div class="bg-white">
            <div class="table-responsive" style="height: calc(100vh - 150px);">
                <table id="tableSalaConferencia" class="table table-striped">
                    <thead>
                        <tr class="" style="background-color: #000078; color:white;">
                            <th style="background-color: #000078; color: white; position: sticky; top: 0;">Fecha</th>
                            <th style="background-color: #000078; color: white; position: sticky; top: 0;">Empresa</th>
                            <th style="background-color: #000078; color: white; position: sticky; top: 0;">Tema</th>
                            <th class="d-none" style="background-color: #000078; color: white; position: sticky; top: 0;">Resumen</th>
                            <th style="background-color: #000078; color: white; position: sticky; top: 0;">Sala</th>
                            <th class="d-none" style="background-color: #000078; color: white; position: sticky; top: 0;">
                                Duración</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($viewData['moduloSalaConferencia'])) {
                            //$day = substr($viewData['moduloSalaConferencia'][0]['fecha'], 0, 10);
                            $day = null;
                        }
                        ?>
                        @foreach ($viewData['moduloSalaConferencia'] as $key => $item)
                            <?php 
                                //dd($item); 
                            ?>
                            @if ($day != substr($item['fecha'], 0, 10))
                                <tr style="border: none; background-color: white; --bs-table-bg-type: white !important;">
                                    <td colspan="5"
                                        style="border: none; background-color: white; --bs-table-bg-type: white !important;">
                                    </td>
                                </tr>
                                <tr style="margin-top: 5px;">
                                    <td colspan="5" style="text-align: center; font-size: 18px; font-weight: bold;">
                                        {{ \DateTime::createFromFormat('Y-m-d', substr($item['fecha'], 0, 10))->format('d/m/Y') }}
                                    </td>
                                </tr>
                                <?php $day = substr($item['fecha'], 0, 10); ?>
                            @endif
                            <tr style="cursor: pointer;" onclick="selectSala('{{ $key }}');">
                                <td style="font-weight: bold;">
                                    {{ \DateTime::createFromFormat('Y-m-d H:i', substr($item['fecha'], 0, 16))->format('H:i') }}
                                </td>
                                <td>
                                    {{ $item['nombreEmpresa'] }}
                                </td>
                                <td>
                                    {{ $item['asignado']['titulo_charla'] }}
                                </td>
                                <td class="d-none">
                                    {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($item['asignado']['temario'])), 100, '...') }}
                                </td>
                                <td style="white-space: nowrap;">
                                    {{ $item['sala'] }}
                                </td>
                                <td class="d-none">
                                    {{ $item['duracion'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalTemario" tabindex="-1" aria-labelledby="modalTemarioLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px; margin: 1.75rem auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTemarioLabel"></h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div id="bodyModalTemario" class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <button id="openModaltemario" type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
        data-bs-target="#modalTemario">
        Modal Temario
    </button>

    <script>
        let moduloSalaConferencia = @json($viewData['moduloSalaConferencia']);
        let hrefAcepterSala = "{{ route('modulo.sala-conferencia.aceptar-sala') }}";
        let hrefCancelarSala = "{{ route('modulo.sala-conferencia.cancelar-sala') }}";
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        let urlSalaConferenciaIndex = "{{ route('expositor.modulo.sala-conferencia.index') }}";
        //href="{{-- route('public.catalogo-expositor', ['id' => $expositor['id'], 'entidad' => $viewData['entidad']]) --}}"
        let routeCatalogoExpositor =
        "{{ route('public.catalogo-expositor', ['id' => 'p_id', 'entidad' => 'p_entidad']) }}";

        function selectSala(key) {
            setTemario({{ $viewData['exposicion']['id'] }}, moduloSalaConferencia[key]);
        }

        function setTemario(idExposicion, modulo) {
            console.log(modulo['asignado']);

            let disertantes = modulo['asignado']['expositor_modulo_sala_conferencia_disertante'].length ? '<hr/>Disertantes:<ul>' : '';
            modulo['asignado']['expositor_modulo_sala_conferencia_disertante'].forEach(disertante => {
                disertantes += '<li><b>' + disertante['apellido_nombre'] + '</b></li>';
            });
            disertantes += '</ul>';

            let html = '<b><u>Responsable </u></b>:' + '<br/>' +
                '<b>' + modulo['asignado']['responsable_apellido_nombre'] + '</b><br/>' +
                '<i>' + modulo['asignado']['responsable_email'] + '</i><br/>' +
                '<hr/>' +
                '<b><u>Temario </u></b>:' + '<br/>' +
                modulo['asignado']['temario'] +
                disertantes;


            let url = routeCatalogoExpositor.replace('p_id', modulo['id_expositor']);
            url = url.replace('p_entidad', modulo.pertenece_etif == '1' ? 'etif' : 'envase');
            $('#bodyModalTemario').empty();
            $('#bodyModalTemario').append(html);
            $('#modalTemarioLabel').html(
                'Fecha: ' + modulo['fecha'] + '<br/>' +
                'Duración: ' + modulo['duracion'] + ' minutos' + '<br/>' +
                'Sala: ' + modulo['sala'] + '<br/>' +
                'Expositor: <a href="' + url + '">' + modulo['nombreEmpresa'] + '</a>'
            );
            $('#openModaltemario').click();
        }
    </script>
    <script>
        if (@json($viewData['wp'])) {
            $('body').attr('style', 'background-color: white !important;');
            $('#navbarMain').hide();
            $('#nav2').hide();
            $('#navExp').hide();
        }
    </script>
@endsection
