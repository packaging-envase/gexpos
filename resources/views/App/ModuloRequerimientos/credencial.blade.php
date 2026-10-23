@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="">
        <div class="shadow p-3 mb-5 mt-1 bg-white">
            <h2 class="mb-3">
                <u>Requerimientos - Credenciales</u>
                <small>
                    (último día para actualizar la petición
                    <b>{{ $viewData['credencial_vencimiento'] ? date('d/m/Y', strtotime($viewData['credencial_vencimiento']['value_date'])) : '' }}</b>)
                </small>
            </h2>

            @include('layouts.messages')

            <h3>Credenciales Solicitadas</h3>
            <table class="table table-bordered">
                <thead>
                    <tr class="table-dark">
                        <th class="text-center">
                            <button class="btn btn-primary show-details" data-key="todos">Todos
                                ({{ $viewData['credencialesPedidasQuantity'] }})</button>
                        </th>
                        @foreach (array_keys($viewData['credencialesSolicitadas']) as $key)
                            <th class="text-center">
                                <button class="btn btn-primary show-details"
                                    data-key="{{ $key }}">{{ $key }}
                                    ({{ count($viewData['credencialesSolicitadas'][$key]) }})
                                </button>
                            </th>
                        @endforeach
                    </tr>
                </thead>
            </table>

            <div id="details" style="display: none;">
                <table class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Stands</th>
                            <th>Empresa</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Empresa</th>
                            <th>Cargo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Descripción</th> <!-- Nueva columna de Descripción -->
                            <th>Creado</th>
                        </tr>
                    </thead>
                    <tbody id="details-body">
                        <!-- Los detalles se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>

            <script>
                $(document).ready(function() {
                    // Función para formatear fecha a d/m/Y
                    function formatDate(dateString) {
                        var date = new Date(dateString);
                        var day = String(date.getDate()).padStart(2, '0');
                        var month = String(date.getMonth() + 1).padStart(2, '0');
                        var year = date.getFullYear();
                        return `${day}/${month}/${year}`;
                    }

                    // Inicializar DataTable con botón de exportación a Excel
                    var table = $('#details-body').closest('table').DataTable({
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'excelHtml5',
                            text: 'Exportar a Excel',
                            title: 'Credenciales Solicitadas'
                        }]
                    });

                    var lastClickedButton = null;

                    $(".show-details").click(function() {
                        var key = $(this).data("key");
                        var data = @json($viewData['credencialesSolicitadas']);

                        // Limpiar la tabla de detalles
                        table.clear();

                        // Mostrar todas las credenciales si se hace clic en "Todos"
                        if (key === "todos") {
                            var allData = Object.values(data).flat();

                            allData.forEach(function(item) {
                                table.row.add([
                                    item.stands,
                                    item.razon_social,
                                    item.nombre,
                                    item.apellido,
                                    item.empresa,
                                    item.cargo,
                                    item.email,
                                    item.telefono,
                                    item.descripcion,
                                    formatDate(item.created_at)
                                ]).draw();
                            });
                        } else {
                            if (data[key]) {
                                data[key].forEach(function(item) {
                                    table.row.add([
                                        item.stands,
                                        item.razon_social,
                                        item.nombre,
                                        item.apellido,
                                        item.empresa,
                                        item.cargo,
                                        item.email,
                                        item.telefono,
                                        item.descripcion,
                                        formatDate(item.created_at)
                                    ]).draw();
                                });
                            }
                        }

                        // Mostrar los detalles
                        $("#details").show();

                        // Cambiar el estado de los botones
                        if (lastClickedButton) {
                            lastClickedButton.removeClass('btn-success').addClass('btn-primary');
                        }

                        $(this).removeClass('btn-primary').addClass('btn-success');

                        lastClickedButton = $(this);
                    });
                });
            </script>
        </div>
    </div>
@endsection
