@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    @php
        $idsSinEmail = $viewData['expositorSinEmail']->pluck('id')->toArray();
    @endphp

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">
        <div id="edicionEmail" class="">
            <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

                <div class="container">
                    <h2 class="mb-3">
                        <u>Email - Mensajes Predefinidos</u>
                    </h2>
                    @include('layouts.messages')
                    <div class="p-4 rounded" style="background-color: #FFF3CD;">
                        <h5>Recomendacion:</h5>
                        <ul>
                            <li>No armar una nueva plantilla para cada email individual</li>
                            <li>Reutilizar las plantillas existentes</li>
                            <li>Crear una nueva plantilla solo en caso de que el tema no este ya creado</li>
                        </ul>
                    </div>

                    <form id="formWhatsappPredefinido" action="{{ route('email.update-store-predefinido') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input hidden name="idExposicion" value="{{ $viewData['exposicion']['id'] }}" />
                        <input id="idPredefinido" hidden name="idEmailPredefinido" value="" />
                        <div class="d-flex">
                            <div class="form-group mt-1 w-50 pe-1">
                                <label>Plantilla</label>
                                <select id="predefinidoSelect" class="form-control">
                                    <option value="" selected>Nuevo</option>
                                    @foreach ($viewData['predefinidos'] as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ $viewData['predefinido'] && $viewData['predefinido']['id'] == $item['id'] ? 'selected=true' : '' }}>
                                            {{ $item['descripcion'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-1 w-50 ps-1">
                                <label for="description">Editar Nombre Plantilla</label>
                                <input id="description" name="description" class="form-control" required></textarea>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label>Titulo del Email</label>
                            <input id="title" class="form-control" required name="title" />
                        </div>

                        <div class="form-group mt-1">
                            <label for="texto">Cuerpo del Email</label>
                            <textarea id="texto" name="texto" required rows="10">
                                
                            </textarea>
                            <script type="text/javascript">
                                tinymce.init({
                                    selector: '#texto',
                                    license_key: 'gpl'
                                });
                            </script>
                        </div>

                        <div class="form-group mt-1">
                            <label for="file">Nuevo Archivo</label>
                            <input id="file_path" type="file" name="file_path" class="form-control">
                        </div>
                        <div id="linkContainer" style="display: none;">
                            <label>Archivo Existente</label><br />
                            <a href="" id="link_file_path" style="display: none;" target="_blank"></a><br />
                            <input id="fileDeleted" name="fileDeleted" value="false" hidden />
                        </div>
                        <!-- Button trigger modal -->
                        <button type="submit" class="btn btn-success float-start mt-1">Guardar Plantilla</button>
                        <button type="button" class="btn btn-primary float-end mt-1" onclick="stepOne()">
                            Guardar Plantilla y Enviar
                        </button>
                        <div style="clear: both;"></div>
                    </form>
                </div>
            </div>
        </div>


        <div id="seleccionExpositores" class="d-none border shadow-sm mb-4 p-4">
            <h3><u>Seleccione los expositores a quienes se le enviará el mail</u></h3>
            <label><b>Plantilla Email: </b><span id="emailTitle"></span></label>
            <hr />
            <h5>Listado de Expositores:</h5>
            <select id="filtroTipo" class="form-control mb-2" style="width: fit-content;">
                <option value="todos">Todos (Envase + ETIF)</option>
                <option value="etif">ETIF</option>
                <option value="envase">Envase</option>
            </select>

            <hr />

            <h5>Herramientas de selección masiva de expositores:</h5>
            <input id="expositTodos" type="checkbox" value="todos" onchange="selectAllExpositores(this)"
                onclick="event.stopPropagation();" />
            <label for="expositTodos">Todos</label>
            <br />
            <label>
                <input type="checkbox" id="checkNoAceptaron">
                A los que no aceptaron términos y condiciones
            </label>
            <br />
            <label>
                <input type="checkbox" id="checkNoTieneEmail">
                A los que no se les mando ningun email
            </label>
            <br />
            <label>Fecha Alta de Sistema</label>
            <div class="d-flex">
                <div>
                    <label>Desde:</label>
                    <input type="date" id="fechaDesde" class="form-control" style="width: min-content;">
                </div>
                <div>
                    <label>Hasta:</label>
                    <input type="date" id="fechaHasta" class="form-control" style="width: min-content;">
                </div>
                <div>
                    <label style="color: white;">a</label><br />
                    <button type="button" id="btnSeleccionarFechas" class="btn btn-primary">
                        Seleccionar
                    </button>
                </div>
            </div>
            <div class="d-flex">
                <div>
                    <label>Stand desde:</label>
                    <input type="number" id="standDesde" class="form-control">
                </div>
                <div>
                    <label>Stand hasta:</label>
                    <input type="number" id="standHasta" class="form-control">
                </div>
                <div>
                    <label style="color: white;">a</label><br />
                    <button type="button" id="btnSeleccionarStands" class="btn btn-primary">
                        Seleccionar
                    </button>
                </div>
            </div>
            <table id="tableExpositores" class="table mx-auto">
                <thead>
                    <tr class="table-dark">
                        <th>Enviado</th>
                        <th>Razon Social</th>
                        <th>Nombre Usuario</th>
                        <th>Stands</th>
                        <th>Acepto Terminos y Cond.</th>
                        <th>Ultimo Envio</th>
                        <th>Alta Sistema</th>
                        <!--<th>Whatsapp</th>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['expositores'] as $expositor)
                        <tr onclick="checkExpositor({{ $expositor['id'] }}, '{{ $expositor['usuario'] }}')">
                            <div class='d-none'>{{ $expositor['email_count'] }}</div>
                            <td class="{{ $expositor['email_count'] != '0' ? 'bg-success text-white' : '' }}">
                                {{ $expositor['email_count'] }}
                            </td>
                            <td class="{{ $expositor['email_count'] != '0' ? 'bg-success text-white' : '' }}">
                                {{ $expositor['razon_social'] }}
                            </td>
                            <td class="{{ $expositor['email_count'] != '0' ? 'bg-success text-white' : '' }}">
                                <input id="expositor{{ $expositor['id'] }}" type="checkbox"
                                    value="{{ $expositor['id'] }}" name="idExpositores[]"
                                    data-usuario="{{ $expositor['usuario'] }}"
                                    data-empresa="{{ $expositor['razon_social'] }}"
                                    data-acepto="{{ $expositor['acepto_terminos_condiciones'] }}"
                                    data-sin-email="{{ in_array($expositor['id'], $idsSinEmail) ? '1' : '0' }}"
                                    data-created="{{ $expositor['created_at'] ? \Carbon\Carbon::parse($expositor['created_at'])->format('Y-m-d') : '' }}"
                                    data-stands="{{ $expositor['stands'] }}"
                                    data-etif="{{ $expositor['pertenece_etif'] }}" />
                                <label>{{ $expositor['usuario'] }}</label>
                            </td>
                            <td>
                                {{ $expositor['stands'] }}
                            </td>
                            <td>
                                <div class="d-none">{{ $expositor['acepto_terminos_condiciones'] }}</div>
                                @if ($expositor['acepto_terminos_condiciones'] == 1)
                                    <span class="btn btn-success disabled">SI</span>
                                @else
                                    <span class="btn btn-danger disabled">NO</span>
                                @endif
                            </td>
                            <td>
                                {{ $expositor['ultimo_email'] }}
                            <td>
                                {{ $expositor['created_at'] ? \Carbon\Carbon::parse($expositor['created_at'])->format('Y-m-d') : '' }}
                            </td>
                            <!--
                                                                            <td class="{{ $expositor['email_count'] != '0' ? 'bg-success text-white' : '' }}">
                                                                                {{ $expositor['whatsapp'] }}
                                                                            </td>
                                                                            -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-primary float-end m-2" onclick="agregarExpositoresYEnviarFormulario()">
                Enviar
            </button>
            <div style="clear: both;"></div>
        </div>

        <div id="seleccionExpositoresFinal" class="d-none border shadow-sm mb-4 p-4">
            <h5>Se enviará el email <b id="emailTitle2"></b> a los siguientes expositores:</h5>
            <div id="listExpositores">

            </div>

            <button type="button" class="btn btn-warning float-start" onclick="cancelar()">Cancelar</button>
            <button type="button" class="btn btn-success float-end"
                onclick="$('#formWhatsappPredefinido').submit()">Envio
                Definitivo</button>
            <div style="clear: both;"></div>
        </div>
    </div>


    <script type="text/javascript">
        let predefinidos = @json($viewData['predefinidos']);

        function stepOne() {
            $('#edicionEmail').addClass('d-none');
            $('#seleccionExpositores').removeClass('d-none');
            $('#emailTitle').html($('#description').val());
            $('#tableExpositores').DataTable({
                paging: false, // 🔥 desactiva paginación
                columnDefs: [{
                    targets: '_all',
                    className: 'text-start'
                }]
            });
        }

        function cancelar() {
            const form = $('#formWhatsappPredefinido');

            // Eliminar los checkboxes
            form.find('input[type="checkbox"][name="idExpositores[]"]').remove();

            // Mostrar y ocultar los elementos correspondientes
            $('#seleccionExpositores').removeClass('d-none');
            $('#seleccionExpositoresFinal').addClass('d-none');

            // Desmarcar todos los checkboxes seleccionados
            $('input[type="checkbox"][name="idExpositores[]"]:checked').prop('checked', false);
        }


        function selectAllExpositores(checkbox) {
            $('input[id^="expositor"]:visible').each(function() {
                if ($(this).data('usuario').includes('@')) {
                    $(this).prop('checked', checkbox.checked);
                }
            });
        }

        function agregarExpositoresYEnviarFormulario() {
            if (!$('#title').val()) {
                alert('Debe ingresar un titulo del email');
                return;
            }
            if (!$('#texto').val()) {
                alert('Debe ingresar un texto del email');
                return;
            }
            if (!$('#description').val()) {
                alert('Debe ingresar una descripcion del mensaje predefinido');
                return;
            }
            const form = $('#formWhatsappPredefinido');

            let seleccionados = $('input[type="checkbox"][name="idExpositores[]"]:checked');
            let listContainer = $('#listExpositores');

            listContainer.empty(); // Limpiar la lista antes de actualizarla

            if (seleccionados.length > 0) {
                seleccionados.each(function() {
                    let usuario = $(this).data('usuario');
                    let empresa = $(this).data('empresa');

                    console.log(usuario, empresa);

                    listContainer.append(`<div><strong>${empresa}</strong> - ${usuario}</div>`);
                });
            }

            $('input[type="checkbox"][name="idExpositores[]"]:checked').each(function() {
                const checkboxClone = $(this).clone();
                form.append(checkboxClone);
            });

            $('#seleccionExpositores').addClass('d-none');
            $('#seleccionExpositoresFinal').removeClass('d-none');
            $('#emailTitle2').html($('#description').val());
            //form.submit();
        }

        function checkExpositor(expositorId, usuario) {
            //console.log(expositorId, usuario);
            if (!usuario.includes('@')) {
                alert('El nombre de usuario del expositor no es un email');
            } else {
                // Obtiene el ID del expositor de la fila
                console.log('this', this)

                checkbox = $('#expositor' + expositorId);
                checkbox.prop('checked', !checkbox.prop('checked'));

                console.log('checkbox: ', checkbox.prop('checked'));
                console.log('checkbox: ', checkbox);
            }

            return;
        }

        function eliminarArchivo() {
            $('#fileDeleted').val('true');
            $('#link_file_path').hide();
            $('#fileGroup').show();
            $('#buttonEliminarArchivo').hide();
            $('#linkContainer').hide();
        }

        function getCheckboxesVisibles() {
            return Array.from(document.querySelectorAll('input[name="idExpositores[]"]'))
                .filter(cb => cb.closest('tr').style.display !== 'none');
        }

        $(document).ready(function() {

            document.getElementById('filtroTipo').addEventListener('change', function() {
                const tipo = this.value;

                // 🔹 LIMPIAR CHECKBOXES DEL PANEL
                document.getElementById('expositTodos').checked = false;
                document.getElementById('checkNoAceptaron').checked = false;
                document.getElementById('checkNoTieneEmail').checked = false;

                // 🔹 LIMPIAR INPUTS DE FECHA
                document.getElementById('fechaDesde').value = '';
                document.getElementById('fechaHasta').value = '';

                // 🔹 LIMPIAR INPUTS DE STAND
                document.getElementById('standDesde').value = '';
                document.getElementById('standHasta').value = '';

                // 🔹 LIMPIAR CHECKBOXES DE LA TABLA
                const rows = document.querySelectorAll('tr[onclick^="checkExpositor"]');

                rows.forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    if (!checkbox) return;

                    checkbox.checked = false;

                    const esEtif = checkbox.dataset.etif == "1";

                    if (tipo === 'todos') {
                        row.style.display = '';
                    } else if (tipo === 'etif') {
                        row.style.display = esEtif ? '' : 'none';
                    } else if (tipo === 'envase') {
                        row.style.display = !esEtif ? '' : 'none';
                    }
                });
            });

            document.getElementById('btnSeleccionarStands').addEventListener('click', function() {
                const desde = parseInt(document.getElementById('standDesde').value);
                const hasta = parseInt(document.getElementById('standHasta').value);

                if (isNaN(desde) || isNaN(hasta)) {
                    alert('Debés completar ambos valores de stand');
                    return;
                }

                getCheckboxesVisibles().forEach(function(checkbox) {
                    const standsRaw = checkbox.dataset.stands;
                    if (!standsRaw) return;

                    let partes = standsRaw.split('-').map(s => parseInt(s.trim()));
                    let standMin, standMax;

                    if (partes.length === 1) {
                        standMin = standMax = partes[0];
                    } else {
                        standMin = Math.min(...partes);
                        standMax = Math.max(...partes);
                    }

                    if (standMax >= desde && standMin <= hasta) {
                        checkbox.checked = true;
                        checkbox.dataset.autoStand = "1";
                    }
                });
            });

            document.getElementById('btnSeleccionarFechas').addEventListener('click', function() {
                const desde = document.getElementById('fechaDesde').value;
                const hasta = document.getElementById('fechaHasta').value;

                if (!desde || !hasta) {
                    alert('Debés completar ambas fechas');
                    return;
                }

                getCheckboxesVisibles().forEach(function(checkbox) {
                    const fecha = checkbox.dataset.created;
                    if (!fecha) return;

                    if (fecha >= desde && fecha <= hasta) {
                        checkbox.checked = true;
                        checkbox.dataset.autoFecha = "1";
                    }
                });
            });

            document.getElementById('checkNoTieneEmail').addEventListener('change', function() {
                const checked = this.checked;

                getCheckboxesVisibles().forEach(function(checkbox) {
                    const sinEmail = checkbox.dataset.sinEmail;

                    if (checked) {
                        if (sinEmail === "1") {
                            checkbox.checked = true;
                            checkbox.dataset.autoEmail = "1";
                        }
                    } else {
                        if (checkbox.dataset.autoEmail === "1") {
                            checkbox.checked = false;
                            checkbox.dataset.autoEmail = "0";
                        }
                    }
                });
            });

            document.getElementById('checkNoAceptaron').addEventListener('change', function() {
                const checked = this.checked;

                getCheckboxesVisibles().forEach(function(checkbox) {
                    const acepto = checkbox.dataset.acepto;

                    if (checked) {
                        if (acepto === "0") {
                            checkbox.checked = true;
                            checkbox.dataset.auto = "1";
                        }
                    } else {
                        if (checkbox.dataset.auto === "1") {
                            checkbox.checked = false;
                            checkbox.dataset.auto = "0";
                        }
                    }
                });
            });

            //$('tr[data-expositor-id]').click(checkExpositor);

            $('input[type="checkbox"][name="idExpositores[]"]').click(function(event) {
                event.target.checked = !event.target.checked;
            });

            let routeView =
                "{{ route('email.update-store-predefinido-view', ['id' => $viewData['exposicion']['id'], 'email' => 'p_email']) }}";


            $('#predefinidoSelect').change(function() {
                window.location = routeView.replace('p_email', $(this).val()).replace('amp;', '');
            });


            let predefinido = @json($viewData['predefinido']);
            if (predefinido) {
                let selectedId = $('#predefinidoSelect').val();

                // Limpiar los campos
                $('#description').val('');
                $('#texto').val('');
                $('#file_path').val('');
                $('#link_file_path').hide();
                $('#fileGroup').show();
                $('#idPredefinido').val();

                // Verificar si se seleccionó "Nuevo"
                if (selectedId === "") {
                    return; // Si es "Nuevo", salimos
                }

                // Buscar el predefinido seleccionado
                //const predefinido = predefinidos.find(item => item.id == selectedId);

                if (predefinido) {
                    $('#idPredefinido').val(predefinido.id);
                    // Rellenar los campos con los datos del predefinido
                    $('#description').val(predefinido.descripcion);
                    if (predefinido.descripcion == 'Envio de Usuario y Contraseña') {
                        $('#description').attr('readonly', true);
                    }

                    $('#texto').val(predefinido.texto);
                    $('#title').val(predefinido.title);

                    // Manejar el archivo
                    if (predefinido.file_path) {
                        // Mostrar el enlace del archivo
                        $('#link_file_path').attr('href', predefinido.file_path)
                            .text(predefinido.file_name)
                            .show();
                        $('#linkContainer').show();
                        // Ocultar el input de archivo
                        $('#fileGroup').hide();
                        $('#linkContainer').append(`
                            <button id="buttonEliminarArchivo" type="button" class="btn btn-danger" onclick="eliminarArchivo()">Eliminar Archivo</button>
                        `);
                        $('#fileDeleted').val('false');
                    } else {
                        // Si no hay archivo, mostrar el input
                        $('#link_file_path').hide();
                        $('#fileGroup').show();
                    }
                }
            }
        });
    </script>
@endsection
