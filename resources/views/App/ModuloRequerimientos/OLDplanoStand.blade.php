@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    

    <div class="container">
        <div class="shadow p-3 mt-1 bg-white">

        <h2 class="mb-3">
            <u>Requerimientos - Planos de Stand</u>
        </h2>
        
        @include('layouts.messages')
    </div>

        @foreach ($viewData['pedidos'] as $indexPedido => $pedido)
            <div class="shadow-sm p-2 mb-2 mt-2 bg-white border">
                <?php
                $statusColor = '';
                switch ($pedido['status']) {
                    case 'presentado':
                        $statusColor = 'primary';
                        break;
                    case 'pedido de correccion':
                        $statusColor = 'warning';
                        break;
                    case 'conforme a reglamento':
                        $statusColor = 'success';
                        break;
                    case 'nuevo requerimiento':
                        $statusColor = 'primary';
                        break;
                    default:
                        break;
                }
                ?>

                <div class="d-flex">
                    <button type="button" class="me-2 btn btn-{{ $statusColor }}" data-bs-toggle="modal"
                        data-bs-target="#responderPedidoModal" onclick="openResponderPedidoModal('{{ $indexPedido }}')">
                        {{ $pedido['status'] }}
                    </button>
                    <h4>
                        <a
                            href="{{ route('expositor.modulo.plano-stand.index', ['idExpositor' => $pedido['id_expositor'], 'idExposicion' => $viewData['exposicion']['id']]) }}">
                            {{ isset($pedido->expositor) ? $pedido->expositor->empresa['razon_social'] : '' }}
                        </a>
                    </h4>
                </div>                
                Enviado: {{ substr($pedido['created_at'], 0, 11) }} Actualizacion: {{ substr($pedido['updated_at'], 0, 11) }}<br />
                <br />
                @if (isset($pedido->moduloPlanoStandComentario[0]))
                    @foreach ($pedido->moduloPlanoStandComentario as $comentario)
                        {{ substr($comentario['created_at'], 0, 11) }} |
                        {{ $comentario['id_expositor'] ? $pedido['nombre'] : 'Arquitecto ' }}:
                        {{ $comentario['descripcion'] }}
                        <br />
                    @endforeach
                @endif
                @if ($pedido && count($pedido->expositorModuloPlanoStandFile))
                    @foreach ($pedido->expositorModuloPlanoStandFile as $file)
                        <br />
                        <a href="{{ route('expositor.modulo.plano-stand.download-file', ['path' => $file['path']]) }}" style="{{!$file['aprobado'] ? 'color: red;' : ''}}">
                            {{ substr($file['created_at'], 0, 11) }} || {{ $file['aprobado'] ? 'Aprobado' : 'No Aprobado' }} || {{ $file['name'] }}
                        </a>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
    <!-- Modal -->
    <div class="modal fade" id="responderPedidoModal" tabindex="-1" aria-labelledby="responderPedidoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responderPedidoModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="responderPedidoModalBody" class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button form="responderPedidoModalForm" type="submit" class="btn btn-success">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let pedidos = @json($viewData['pedidos']);

        function openResponderPedidoModal(indexPedido) {
            console.log(pedidos[indexPedido]);

            $('#responderPedidoModalLabel').html(pedidos[indexPedido]['expositor']['empresa']['razon_social']);


            $('#responderPedidoModalBody').empty();
            $('#responderPedidoModalBody').append(`
                <form id="responderPedidoModalForm" method="POST" action="{{ route('expositor.modulo.plano-stand.update-store') }}">
                    @csrf
                    <input hidden name="idExpositor" value="${pedidos[indexPedido]['expositor']['id']}"/>
                    <input hidden name="idExposicion" value="{{ $viewData['exposicion']['id'] }}"/>
                    <select class="form-select" name="status" style="width: fit-content;" required>
                        <option value="" hidden>Estado</option>
                        <option value="presentado">Presentado</option>                            
                        <option value="pedido de correccion">Pedido de Correccion</option>
                        <option value="conforme a reglamento">Conforme a Reglamento</option>
                    </select>
                    <label>Comentario:</label>
                    <textarea style="resize:none;" class="form-control w-100" name="comentario"></textarea>
                </form>
            `);

            let numeroEnvio = 0;
            pedidos[indexPedido].expositor_modulo_plano_stand_file.forEach(file => {
                
                $('#responderPedidoModalForm').append(`
                    ${numeroEnvio != file.numero_envio ? 
                        '<u>Envío Expositor Nº '+file.numero_envio+'</u>'
                    : ''}
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault${file.id}" ${file.aprobado ? 'checked=true' : ''} onchange="
                            if (this.checked) {
                                $('#fileSwitchLabel${file.id}').html('(Plano conforme a reglamento) ');
                                $('#hiddenFileInput${file.id}').val('${file.id}:true');
                            } else {
                                $('#fileSwitchLabel${file.id}').html('(No Aprobado) ');
                                $('#hiddenFileInput${file.id}').val('${file.id}:false');
                            } 
                            $('#fileSwitchLabel${file.id}').append('${file.name}');
                        ">
                        <label class="form-check-label" for="flexSwitchCheckDefault${file.id}" id="fileSwitchLabel${file.id}">${file.aprobado ? '(Plano conforme a reglamento) '+file.name : '(No Aprobado) '+file.name}</label>
                        <input type="hidden" name="fileStatus[]" id="hiddenFileInput${file.id}" value="${file.id}:${file.aprobado}">
                    </div>
                `);
                numeroEnvio = file.numero_envio
            });
            
        }
    </script>
@endsection
