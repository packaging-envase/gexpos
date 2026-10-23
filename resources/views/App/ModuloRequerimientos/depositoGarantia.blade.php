@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Requerimientos - Deposito Garantía</u>
            </h2>

            @include('layouts.messages')
        </div>
        <div>
            <table id="tableRequerimientosDepositoGarantia" class="table table-striped">
                <thead>
                    <tr>
                        <th style="position: sticky; top: 0;">Empresa</th>
                        <th style="position: sticky; top: 0;">Stands</th>
                        <th style="position: sticky; top: 0;" class="text-center">Documento Recibido</th>
                        <th style="position: sticky; top: 0;" class="text-center">Recibido Fecha</th>
                        <th style="position: sticky; top: 0;" class="text-center">Devuelto Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['expositores'] as $expositor)
                        <?php
                        $stands = '';
                        if ($expositor->stand) {
                            foreach ($expositor->stand as $key => $stand) {
                                $stands .= $stand->numero;
                                if ($key < count($expositor->stand) - 1) {
                                    $stands .= ' - ';
                                }
                            }
                        }
                        ?>
                        <tr>
                            <form method="POST" action="{{ route('modulo.deposito-garantia.update-entregado') }}"
                                id="formUpdate{{ $expositor->id }}">
                                <input hidden name="id_expositor" value="{{ $expositor->id }}"
                                    form="formUpdate{{ $expositor->id }}" />
                                <input hidden name="id_exposicion" value="{{ $viewData['exposicion']['id'] }}"
                                    form="formUpdate{{ $expositor->id }}" />
                                @csrf
                            </form>
                            <td>
                                <div class="d-none">{{ $expositor->empresa->razon_social }}</div>
                                @if ($expositor->empresa)
                                    @if ($expositor->depositoGarantia)
                                        <span class="link-a"
                                            onclick="openModalResponsable('{{ $expositor->depositoGarantia->apellido_nombre }}','{{ $expositor->depositoGarantia->numero_documento }}','{{ $expositor->depositoGarantia->direccion }}')">{{ $expositor->empresa->razon_social }}</span>
                                    @else
                                        {{ $expositor->empresa->razon_social }}
                                    @endif
                                @endif
                            </td>
                            <td>{{ $stands }}</td>
                            <td class="text-center">
                                <form id="formFile{{ $expositor['id'] }}" method="POST"
                                    action="{{ route('modulo.deposito-garantia.update-file') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input hidden name="id_expositor" value="{{ $expositor['id'] }}" />
                                    <input hidden name="id_exposicion" value="{{ $viewData['exposicion']['id'] }}" />

                                    <input hidden id="archivoExpositor{{ $expositor['id'] }}" class="form-control"
                                        type="file" name="archivo" required
                                        onchange="$('#formFile{{ $expositor['id'] }}').submit()" />
                                    <button type="button" class="btn btn-success"
                                        onclick="$('#archivoExpositor{{ $expositor['id'] }}').click();">Actualizar</button>

                                </form>
                                @if ($expositor->depositoGarantia && $expositor->depositoGarantia->path)
                                    <a
                                        href="{{ route('modulo.deposito-garantia.download-file', ['path' => $expositor->depositoGarantia->path]) }}">Descargar
                                        Actual</a>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-none">
                                    {{ $expositor->depositoGarantia ? $expositor->depositoGarantia->fecha_devuelto : '' }}
                                </div>
                                <div class="d-flex">
                                    <input class="form-control" style="width: fit-content;"
                                        id="fechaEntregado{{ $expositor->id }}" type="date" name="fecha_entregado"
                                        value="{{ $expositor->depositoGarantia ? $expositor->depositoGarantia->fecha_entregado : '' }}"
                                        form="formUpdate{{ $expositor->id }}" />
                                    <input id="checkboxEntregado{{ $expositor->id }}" type="checkbox" name="entregado"
                                        {{ $expositor->depositoGarantia && $expositor->depositoGarantia->entregado ? 'checked=true' : '' }}
                                        form="formUpdate{{ $expositor->id }}" />
                                </div>
                                <script>
                                    $('#fechaEntregado{{ $expositor->id }}').on('change', function() {
                                        $('#formUpdate{{ $expositor->id }}').submit();
                                    });
                                    $('#checkboxEntregado{{ $expositor->id }}').on('change', function() {
                                        $('#formUpdate{{ $expositor->id }}').submit();
                                    });
                                </script>
                            </td>
                            <td class="text-center">
                                <div class="d-flex">
                                    <input class="form-control" style="width: fit-content;"
                                        id="fechaDevuelto{{ $expositor->id }}" type="date" name="fecha_devuelto"
                                        value="{{ $expositor->depositoGarantia ? $expositor->depositoGarantia->fecha_devuelto : '' }}"
                                        form="formUpdate{{ $expositor->id }}" />
                                    <input id="checkboxDevuelto{{ $expositor->id }}" type="checkbox" name="devuelto"
                                        {{ $expositor->depositoGarantia && $expositor->depositoGarantia->devuelto ? 'checked=true' : '' }}
                                        form="formUpdate{{ $expositor->id }}" />
                                </div>
                                <script>
                                    $('#fechaDevuelto{{ $expositor->id }}').on('change', function() {
                                        $('#formUpdate{{ $expositor->id }}').submit();
                                    });
                                    $('#checkboxDevuelto{{ $expositor->id }}').on('change', function() {
                                        $('#formUpdate{{ $expositor->id }}').submit();
                                    });
                                </script>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Button trigger modal -->
        <button id="openResponsableModal" type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
            data-bs-target="#responsableModal">
            Launch demo modal
        </button>

        <!-- Modal -->
        <div class="modal fade" id="responsableModal" tabindex="-1" aria-labelledby="responsableModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="responsableModalLabel"><u>Responsable del Depísto en Garantia:</u>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label><b>Apellido y Nombre: </b>
                            <span id="responsableApellido"></span>
                        </label><br />
                        <label><b>Numero Documento: </b>
                            <span id="responsableDocumento"></span>
                        </label><br />
                        <label><b>Dirección: </b>
                            <span id="responsableDireccion"></span>
                        </label><br />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary d-none">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openModalResponsable(apellido_nombre, numero_documento, direccion) {
            $('#responsableApellido').html(apellido_nombre);
            $('#responsableDocumento').html(numero_documento);
            $('#responsableDireccion').html(direccion);

            $('#openResponsableModal').click();
        }

        $(document).ready(function() {
            $('#tableRequerimientosDepositoGarantia').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel',
                    title: 'Requerimientos Deposito Garantia',
                    exportOptions: {
                        columns: [0, 1, 3, 4] // ← excluye la 2
                    }
                }],
                columnDefs: [{
                    targets: '_all',
                    className: 'text-start'
                }]
            });
        });
    </script>
@endsection
