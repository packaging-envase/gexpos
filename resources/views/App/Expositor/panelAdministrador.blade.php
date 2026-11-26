@extends('layouts.app')

@section('content')
    <style>
        .dt-buttons {
            display: flex;
        }

        .dt-input {
            margin-left: 0.5rem;
        }
    </style>
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])
    @include('layouts.messages')

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="mx-2">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">
            <butotn type="button" class="btn btn-success float-end" onclick="openModalCreateExpositor()">+ Nuevo Expositor
            </butotn>
            <a class="btn btn-success float-end me-2" href="{{route('expositor.copiar-expositor-panel', ['id' => $viewData['exposicion']['id']])}}">+ Copiar Expositor</a>
            <div class="d-flex">
                <h2 class="mb-3">
                    <u>Expositores</u>
                </h2>
                <div>
                    <button id="btnTabla1" class="btn btn-success ms-2" onclick="switchTable(1)">Tabla Configuración</button>
                    <button id="btnTabla2" class="btn btn-primary ms-2" onclick="switchTable(2)">Tabla Control</button>
                </div>
            </div>

            @if (Auth::guard()->user()->grupo == 1)
                <a class="btn btn-{{ !isset($viewData['filtro']) || $viewData['filtro'] == 1 ? 'success' : 'primary' }}"
                    href="{{ route('expositor.panel-administracion', ['id' => $viewData['exposicion']['id'], 'filtro' => 1]) }}">Todos
                    ({{ count($viewData['expositores']) }})</a>
                <a class="btn btn-{{ isset($viewData['filtro']) && $viewData['filtro'] == 2 ? 'success' : 'primary' }}"
                    href="{{ route('expositor.panel-administracion', ['id' => $viewData['exposicion']['id'], 'filtro' => 2]) }}">Expositores
                    Envase</a>
                <a class="btn btn-{{ isset($viewData['filtro']) && $viewData['filtro'] == 3 ? 'success' : 'primary' }}"
                    href="{{ route('expositor.panel-administracion', ['id' => $viewData['exposicion']['id'], 'filtro' => 3]) }}">Expositores
                    ETIF</a>
            @endif

            <!-- Modal de Exportación -->
            <div class="modal fade" id="modalExportacion" tabindex="-1" aria-labelledby="modalExportacionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('expositor.exportar') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalExportacionLabel">Exportar datos</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                @if (Auth::guard()->user()->grupo == 1)
                                    <div class="row">
                                        <label>
                                            <input type="radio" name="filtro" value="todo" checked>
                                            Todo
                                        </label>
                                        <label>
                                            <input type="radio" name="filtro" value="envase">
                                            Envase
                                        </label>
                                        <label>
                                            <input type="radio" name="filtro" value="etif">
                                            Etif
                                        </label>
                                    </div>
                                @endif
                                <div class="row">
                                    <!-- EMPRESA -->
                                    <div class="col-md-6">
                                        <h6>Empresa</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="cuit" value="1"
                                                id="cuit">
                                            <label class="form-check-label" for="cuit">CUIT</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="codigo_iae" value="1"
                                                id="codigo_iae">
                                            <label class="form-check-label" for="codigo_iae">Código IAE</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="razon_social"
                                                value="1" id="razon_social">
                                            <label class="form-check-label" for="razon_social">Razón Social</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nombre_fantasia"
                                                value="1" id="nombre_fantasia">
                                            <label class="form-check-label" for="nombre_fantasia">Nombre
                                                Fantasía</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="codigo_postal"
                                                value="1" id="codigo_postal_empresa">
                                            <label class="form-check-label" for="codigo_postal_empresa">Código Postal
                                                (Empresa)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="facebook" value="1"
                                                id="facebook">
                                            <label class="form-check-label" for="facebook">Facebook</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="instagram" value="1"
                                                id="instagram">
                                            <label class="form-check-label" for="instagram">Instagram</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="youtube"
                                                value="1" id="youtube">
                                            <label class="form-check-label" for="youtube">YouTube</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="linkedin"
                                                value="1" id="linkedin">
                                            <label class="form-check-label" for="linkedin">LinkedIn</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="descripcion"
                                                value="1" id="descripcion">
                                            <label class="form-check-label" for="descripcion">Descripcion</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="contactos"
                                                value="1" id="contactos">
                                            <label class="form-check-label" for="contactos">Contactos</label>
                                        </div>
                                    </div>

                                    <!-- EXPOSITOR MODULO CATALOGO -->
                                    <div class="col-md-6">
                                        <h6>Catálogo / Datos Comerciales</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nombre"
                                                value="1" id="nombre">
                                            <label class="form-check-label" for="nombre">Nombre</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="direccion"
                                                value="1" id="direccion">
                                            <label class="form-check-label" for="direccion">Dirección</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="telefono"
                                                value="1" id="telefono">
                                            <label class="form-check-label" for="telefono">Teléfono</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="whatsapp"
                                                value="1" id="whatsapp">
                                            <label class="form-check-label" for="whatsapp">WhatsApp</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="email"
                                                value="1" id="email">
                                            <label class="form-check-label" for="email">Email</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="web"
                                                value="1" id="web">
                                            <label class="form-check-label" for="web">Web</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="pais"
                                                value="1" id="pais">
                                            <label class="form-check-label" for="pais">País</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="provincia"
                                                value="1" id="provincia">
                                            <label class="form-check-label" for="provincia">Provincia</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="localidad"
                                                value="1" id="localidad">
                                            <label class="form-check-label" for="localidad">Localidad</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="codigo_postal"
                                                value="1" id="codigo_postal_catalogo">
                                            <label class="form-check-label" for="codigo_postal_catalogo">Código Postal
                                                (Catálogo)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="stands"
                                                value="1" id="stands">
                                            <label class="form-check-label" for="stands">Stands</label>
                                        </div>                                        
                                    </div>
                                </div>

                                <input type="hidden" name="idExposicion" value="{{ $viewData['exposicion']['id'] }}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Exportar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#modalExportacion">
                Exportar
            </button>


            <div id="containerTableExpositores" class="table-responsive">
                <table id="tableExpositores" class="table table-striped border border-dark">
                    <thead>
                        <tr class="table-dark">
                            <th style="white-space: nowrap;">Stand</th>
                            <th style="white-space: nowrap;">Vendedor</th>
                            <th style="white-space: nowrap;">Estado Administrativo</th>
                            <th style="white-space: nowrap;">Empresa</th>
                            <th style="white-space: nowrap;">Usuario</th>
                            <th style="white-space: nowrap;">Clave</th>
                            <th style="white-space: nowrap;" class="text-center">m<sup>2</sup> Asignado</th>
                            <!--
                                                <th style="white-space: nowrap;" class="text-center">Pass enviado</th>
                                                <th style="white-space: nowrap;" class="text-center">Acepto Term y Cond.</th>
                                                <th style="white-space: nowrap;" class="text-center">Estado</th>
                                                -->
                            @if (Auth::guard()->user()->grupo == 1)
                                <th class="text-center">Catalogo Envase</th>
                                <th class="text-center">Pertenece<br />ETIF</th>
                            @endif
                            <th class="text-center">Catalogo ETIF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['expositores'] as $item)
                            <?php
                            $column = '';
                            if (!$item->pertenece_etif) {
                                $column = 'columnEnvase';
                            } elseif ($item->pertenece_etif) {
                                $column = 'columnEtif';
                            }
                            //else $column = 'ninguno';
                            ?>

                            @php

                                $stands = count($item->stand) ? $item->stand : [null];
                                
                            @endphp
                            @foreach ($stands as $stand)
                                <?php 
                                    $user = null;
                                    if ($stand && $stand->id_user) {
                                        $user = \App\Models\User::find($stand->id_user);
                                    }
                                ?>
                                <tr class="{{ $column }}" style="white-space: nowrap;">
                                    <td class="p-0 ps-1">{{ $stand ? $stand->numero : '' }}</td>
                                    <td class="p-0 ps-1">{{ $user ? $user->name : '' }}</td>
                                    <td class="p-0 ps-1">{{ $stand ? $stand->status : '' }}</td>
                                    <td class="p-0 ps-1">
                                        {{-- <a href="{{route('expositor.modulo.catalogo.panel', ['idExposicion' => $viewData['exposicion']['id'], 'idEmpresa' => $item->empresa->id])}}"> --}}
                                        {{ $item && isset($item->empresa) ? $item->empresa->razon_social : '' }}
                                    </td>
                                    <td class="p-0 ps-1">
                                        <a href="{{ route('expositor.update-with-empresa', ['id' => $item['id']]) }}"
                                            class="btn btn-success btn-sm">
                                            Editar
                                        </a>
                                        <span
                                            class="{{ str_contains($item->usuario, '@') ? '' : 'bg-danger text-white p-1' }}">{{ $item->usuario }}</span>
                                    </td>
                                    <td class="p-0 ps-1">{{ $item->password }}</td>
                                    <td class="p-0 ps-1 text-center">{{ $stand ? $stand->metros_cuadrados : '' }}
                                    </td>
                                    <!--
                                                        <td class="p-0 ps-1 text-center">
                                                            @if ($item->email_count != '0')
                                                            <span class="d-none">1</span><span class="text-success"><b>Si</b></span>
                                                        @else
                                                            <span class="d-none">2</span><span class="text-danger"><b>No</b></span>
                                                            @endif
                                                            <a class="btn btn-success"
                                                                href="{{ route('api.email.send-user-pass', ['id' => $item['id']]) }}">
                                                                <i class="fa-solid fa-envelope"></i><i class="fa-solid fa-arrow-right"></i>
                                                            </a>
                                                        </td>
                                                        <td class="p-0 ps-1 text-center">
                                                            @if ($item->acepto_terminos_condiciones == 1)
                                                            <span class="text-success"><b>Si</b></span>
                                                        @else
                                                            <span class="text-danger"><b>No</b></span>
                                                            @endif
                                                        </td>
                                                        <td class="p-0 ps-1 text-center">
                                                            <span
                                                                class="{{ $item->status == 'activo' ? 'text-success' : '' }}"><b>{{ $item->status }}</b></span>
                                                        </td>
                                                        -->
                                    @if (Auth::guard()->user()->grupo == 1)
                                        <td class="text-center">
                                            <div class="form-check form-switch">
                                                <form id="formSwitchCatalogo{{ $item->id }}" method="POST"
                                                    action="{{ route('modulo.catalogo.switch-expositor') }}">
                                                    @csrf
                                                    <input hidden name="id_exposicion"
                                                        value="{{ $viewData['exposicion']['id'] }}" />
                                                    <input hidden name="id_expositor" value="{{ $item->id }}" />
                                                    <input name="switch" class="form-check-input mx-auto"
                                                        type="checkbox" {{ $item->catalogo ? 'checked=true' : '' }}
                                                        onclick="switchEnvase(event, '{{ $item->etif }}', '{{ $item->id }}')" />
                                                </form>
                                            </div>
                                        </td>
                                        <td class="p-0 ps-1 text-center">
                                            @if ($item->pertenece_etif)
                                                <span class="text-success"><b>SI</b></span>
                                            @else
                                                <span class="text-danger"><b>NO</b></span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <span hidden>{{ $item->etif ? '1' : '0' }}</span>
                                        <div class="form-check form-switch">
                                            <form id="formSwitchCatalogoEtif{{ $item->id }}" method="POST"
                                                action="{{ route('modulo.catalogo.switch-expositor-etif') }}">
                                                @csrf
                                                <input hidden name="id_exposicion"
                                                    value="{{ $viewData['exposicion']['id'] }}" />
                                                <input hidden name="id_expositor" value="{{ $item->id }}" />
                                                <input name="switch" class="form-check-input mx-auto" type="checkbox"
                                                    {{ $item->etif ? 'checked=true' : '' }}
                                                    onclick="switchEtif(event, '{{ $item->catalogo }}', '{{ $item->id }}')" />
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="containerTableExpositoresCompleto" class="table-responsive d-none">
                <table id="tableExpositoresCompleto" class="table table-striped border border-dark">
                    <thead>
                        <tr class="table-dark">
                            <th>Stands</th>
                            <th>Empresa</th>
                            <th style="white-space: nowrap;" class="">Pass enviado</th>
                            <th style="white-space: nowrap;" class="text-center">Acepto Term y Cond.</th>
                            <th style="white-space: nowrap;" class="text-center">Cambio Password</th>
                            <th style="white-space: nowrap;" class="text-center">Catálogo</th>
                            <th style="white-space: nowrap;" class="text-center">Energía Extra</th>
                            <th style="white-space: nowrap;" class="text-center">Pedidio de Auditorio</th>
                            <th style="white-space: nowrap;" class="text-center">Elementos Opcionales</th>
                            <th style="white-space: nowrap;" class="text-center">Invitaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['expositores'] as $item)
                            
                            <?php
                            $column = '';
                            if (!$item->pertenece_etif) {
                                $column = 'columnEnvase';
                            } elseif ($item->pertenece_etif) {
                                $column = 'columnEtif';
                            }
                            //else $column = 'ninguno';
                            
                            $expositorStands = '';
                            $stands = count($item->stand) ? $item->stand : [null];
                            if ($stands) {
                                $total = count($stands);
                                $index = 0;
                            
                                foreach ($stands as $stand) {
                                    if ($stand) {
                                        $user = null;

                                        $expositorStands .= $stand->numero;
                                        if (++$index < $total) {
                                            $expositorStands .= ' - ';
                                        }
                                    }
                                }
                            }
                            
                            ?>
                            <tr class="{{ $column }}" style="white-space: nowrap;">
                                <td class="p-0 ps-1">{{ $expositorStands }}</td>
                                <td class="p-0 ps-1">
                                    {{ $item && isset($item->empresa) ? $item->empresa->razon_social : '' }}
                                </td>
                                <td class="">
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('api.email.send-user-pass', ['id' => $item['id']]) }}">
                                        Enviar
                                    </a>
                                    @if ($item->email_count != '0')
                                        <span class="d-none">1</span><span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="d-none">2</span><span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->acepto_terminos_condiciones == 1)
                                        <span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->cambio_password)
                                        <span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->completoCatalogo)
                                        <span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->completoEnergia)
                                        <span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->completoConferencia)
                                        <span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->completoElementos)
                                        <span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->completoInvitacion)
                                        <span class="text-success"><b>Si</b></span>
                                    @else
                                        <span class="text-danger"><b>No</b></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @include('App.Expositor.modal-create-expositor', [
                'idExposicion' => $viewData['exposicion']['id'],
            ])
            @include('App.Expositor.modal-create-empresa')
        </div>
        <script type="text/javascript">
            let selectedTabla = '1';

            function switchTable(tabla) {
                if (tabla == 1) {
                    $('#btnTabla1').addClass('btn-success');
                    $('#btnTabla2').removeClass('btn-success');

                    $('#btnTabla1').removeClass('btn-primary');
                    $('#btnTabla2').addClass('btn-primary');

                    $('#containerTableExpositores').removeClass('d-none');
                    $('#containerTableExpositoresCompleto').addClass('d-none');
                }
                if (tabla == 2) {
                    $('#btnTabla1').removeClass('btn-success');
                    $('#btnTabla2').addClass('btn-success');

                    $('#btnTabla1').addClass('btn-primary');
                    $('#btnTabla2').removeClass('btn-primary');

                    $('#containerTableExpositores').addClass('d-none');
                    $('#containerTableExpositoresCompleto').removeClass('d-none');
                }
            }

            function switchEnvase(event, enEtif, id) {
                if (enEtif == 1) {
                    event.preventDefault();
                    alert('El Expositor ya está en el catalogo de ETIF');
                    return;
                }
                $('#formSwitchCatalogo' + id).submit()
            }

            function switchEtif(event, enEnvase, id) {
                if (enEnvase == 1) {
                    event.preventDefault();
                    alert('El Expositor ya está en el catalogo de Envase');
                    return;
                }
                $('#formSwitchCatalogoEtif' + id).submit()
            }

            function onChangeSelectEntida() {
                const selectedValue = document.getElementById('selectEntidad').value;

                // Obtener todos los elementos con las clases
                const columnEtif = document.querySelectorAll('.columnEtif');
                const columnEnvase = document.querySelectorAll('.columnEnvase');
                const columnNinguno = document.querySelectorAll('.ninguno');

                if (selectedValue === '1') {
                    // Si el valor es 1, mostrar ambos elementos
                    columnEtif.forEach(el => el.style.display = '');
                    columnEnvase.forEach(el => el.style.display = '');
                    columnNinguno.forEach(el => el.style.display = '');
                } else if (selectedValue === '2') {
                    // Si el valor es 2, ocultar los elementos con la clase 'columnEtif'
                    columnEtif.forEach(el => el.style.display = 'none');
                    columnNinguno.forEach(el => el.style.display = 'none');
                    columnEnvase.forEach(el => el.style.display = '');
                } else if (selectedValue === '3') {
                    // Si el valor es 3, ocultar los elementos con la clase 'columnEnvase'
                    columnEnvase.forEach(el => el.style.display = 'none');
                    columnNinguno.forEach(el => el.style.display = 'none');
                    columnEtif.forEach(el => el.style.display = '');
                } else if (selectedValue === '4') {
                    columnEtif.forEach(el => el.style.display = 'none');
                    columnEnvase.forEach(el => el.style.display = 'none');
                    columnNinguno.forEach(el => el.style.display = '');
                }
            }

            $(document).ready(function() {
                $('#tableExpositores').DataTable({
                    columnDefs: [{
                        targets: '_all',
                        className: 'text-start'
                    }],
                    layout: {
                        topStart: {
                            buttons: ['excelHtml5']
                        }
                    }
                });
                $('#tableExpositoresCompleto').DataTable({
                    columnDefs: [{
                        targets: '_all',
                        className: 'text-start'
                    }],
                    layout: {
                        topStart: {
                            buttons: [{
                                extend: 'excelHtml5',
                                text: 'Exportar a Excel',
                                exportOptions: {
                                    columns: ':visible',
                                    format: {
                                        body: function(data, row, column, node) {
                                            // Si hay un botón, buscar el texto que le sigue (el span "Si"/"No")
                                            if ($(node).find('a.btn').length) {
                                                return $(node).find(
                                                        'span.text-success, span.text-danger')
                                                    .text().trim();
                                            }
                                            return $(node).text().trim();;
                                        }
                                    }
                                }
                            }]
                        }
                    }
                });


                /*
                                            $('.dt-buttons').append(`
            <select title="CATALOGO" id="selectEntidad" class="form-select ms-2" onchange="onChangeSelectEntida()" style="width: fit-content;">
                <option value="1">Todos</option>
                <option value="2">Envase</option>
                <option value="3">ETIF</option>
                <!--<option value="4">Ninguno</option>-->
            </select>
        `);

                                            $('.dt-input').insertAfter('#selectEntidad');
                                            $('label[for="dt-search-0"]').remove();
                                        */
            });
        </script>
    @endsection
