<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
    integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
    integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Modal -->
<button id="buttonOpenModalAsignarStand" hidden type="button" data-bs-toggle="modal"
    data-bs-target="#asignarStandModal"></button>
<div class="modal fade" id="asignarStandModal" tabindex="-1" aria-labelledby="asignarStandModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asignarStandModalLabel">Asignación de Stand <span
                        id="modalLabelStand"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAsigacionStand" method="POST" action="{{ route('mapa.stand.update-store') }}">
                    @csrf

                    <input hidden name="id_exposicion" value="{{ $idExposicion }}" />
                    <input id="inputAsignacionStandModal_numero_stand" hidden name="numero_stand" value="" />

                    <div id="standBuscarEmpresa">
                        <label>Buscar Empresa</label><br />
                        <div class="input-group mb-3">
                            <input id="inputSearchEmpresa" type="text" class="form-control"
                                aria-describedby="buttonInputSearchEmpresa"
                                placeholder="CUIT - RAZON SOCIAL - NOMBRE FANTASIA">
                            <button class="btn btn-success" type="button" id="buttonInputSearchEmpresa"
                                onclick="buscarEmpresa()">Buscar</button>
                        </div>
                        @if (Auth::guard()->user()->grupo == 3)
                            <small>(Recuerde que si no encuentra la empresa, debe crear al expositor primero)</small>
                        @endif
                        <small>
                    </div>

                    <div id="containerEmpresas" class="d-none">
                        <label>Seleccionar Empresa</label><br />
                        <select id="selectEmpresa" name="empresa"></select>
                    </div>

                    <div id="usuarioExpositorContainer" class="my-2 d-none">
                        <label>Nombre Usuario Expositor <small>(mail)</small></label>
                        <input id="usuarioExpositor" class="form-control" name="usuarioExpositor" value="" />
                        <label>Whatsapp</label>
                        <input id="expositorWhatsapp" name="expositorWhatsapp" class="form-control" />
                    </div>

                    <div id="checkNombreExposicion" class="mt-2">
                        <label>Nombre en Exposicion</label>
                        <div id="radioEmpresaRazonSocial" class="form-check d-none">
                            <input class="form-check-input" type="radio" name="nombre_exposicion"
                                id="checkNombreExposicion1" value="razon_social">
                            <label class="form-check-label" for="checkNombreExposicion1">
                                Razon Social: <span id="empresaRazonSocial"></span>
                            </label>
                        </div>
                        <div class="form-check d-none" id="radioEmpresaFantasia">
                            <input class="form-check-input" type="radio" name="nombre_exposicion"
                                id="checkNombreExposicion2" value="nombre_fantasia">
                            <label class="form-check-label" for="checkNombreExposicion2">
                                Nombre Fantasia: <span id="empresaFantasia"></span>
                            </label>
                        </div>
                    </div>

                    <br />
                    <div class="d-flex mt-2">
                        <div class="w-50 pe-2">
                            <label>Area a facturar</label><br />
                            <div class="input-group">
                                <input id="standAreaFacturar" class="form-control bg-white" value=""
                                    name="metros_cuadrados" />
                                <span class="input-group-text">m<sup>2</sup></span>
                            </div>
                            <label>Valor (IVA incluido)</label><br />
                            <div class="input-group mb-3">
                                <span class="input-group-text" style="padding: 0;">
                                    <select id="standMoneda" name="moneda" class="form-select bg-white">
                                        <option value="1">U$D</option>
                                        <option value="2">ARS</option>
                                    </select>
                                </span>
                                <input id="standValor" type="number" class="form-control bg-white" name="valor"
                                    step="0.01">
                            </div>
                        </div>

                        <div class="w-50">
                            <label>Nuevo Estado del Stand</label><br />
                            <select id="standStatus" name="status" class="form-select bg-white">
                                <option value="solicitud_pendiente" selected>Solicitud Pendiente</option>
                                <option value="pagado">Pagado</option>
                                <option value="reservado">Reservado</option>
                            </select>
                            <label>Tipo de Stand</label><br />
                            <div class="input-group">
                                <select id="idStandTipo" name="id_stand_tipo" class="form-select bg-white">
                                    @foreach ($viewData['standTipo'] as $standTipo)
                                        <option value="{{ $standTipo['id'] }}">
                                            {{ $standTipo['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if (Auth::guard()->user()->grupo == 1)
                    <a id="btnEliminarAsignacionStand" type="submit" class="btn btn-danger d-none"
                        onclick="return confirm('¿Estás seguro de que deseas liberar el Stand?');">Eliminar</a>
                @endif
                <button id="btnSubmitFormAsigacionStand" form="formAsigacionStand" type="submit"
                    class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let empresas = [];
    let selectEmpresa;
    let selectizeInstance;
    let usuario;
    let grupo = "<?php echo Auth::guard()->user()->grupo; ?>";

    function createSelectize() {
        selectEmpresa = $("#selectEmpresa").selectize({
            plugins: ["restore_on_backspace", "clear_button"],
            persist: false,
            valueField: "id",
            render: {
                option: function(item, escape) {
                    // Construir la etiqueta para la opción
                    var optionLabel = (item.cuit ? item.cuit + ' - ' : '') +
                        item.razon_social +
                        (item.nombre_fantasia ? ' - ' + item.nombre_fantasia : '');

                    // Retornar el HTML personalizado para la opción
                    return '<div>' + escape(optionLabel) + '</div>';
                },
                item: function(item, escape) {
                    // Construir la etiqueta para la opción
                    var optionLabel = (item.cuit ? item.cuit + ' - ' : '') +
                        item.razon_social +
                        (item.nombre_fantasia ? ' - ' + item.nombre_fantasia : '');

                    // Retornar el HTML personalizado para la opción
                    return '<div>' + escape(optionLabel) + '</div>';
                }
            },
            searchField: ["cuit", "razon_social", "nombre_fantasia"],
            //options: response.data.items
        });

        // Guardar la instancia de Selectize
        selectizeInstance = selectEmpresa[0].selectize;
        selectizeInstance.clearOptions();
        $('#containerEmpresas').addClass('d-none');
        $('#selectEmpresa')[0].selectize.on('change', function(value) {
            if (empresas.length) {
                let empresaSelected = empresas.find(el => el.id == value);



                /*
                if (empresaSelected.nombre_fantasia) {
                    $('#checkNombreExposicion').removeClass('d-none');
                } else {
                    $('#checkNombreExposicion').addClass('d-none');
                }
                    */

                if (empresaSelected.usuario) {
                    $('#usuarioExpositor').val(empresaSelected.usuario);
                    $('#expositorWhatsapp').val(empresaSelected.whatsapp);
                    $('#usuarioExpositor').attr('readonly', true);
                    //console.log(empresaSelected);
                } else {
                    $('#usuarioExpositor').val('');
                    $('#usuarioExpositor').attr('readonly', false);
                }

                $('#usuarioExpositorContainer').removeClass('d-none');
            }
        });
    }

    function buscarEmpresa() {
        let urlEmpresaSearch = "{{ route('api.empresa.search') }}";
        axios.get(urlEmpresaSearch +
                `?search=${$('#inputSearchEmpresa').val()}&idExposicion={{ $viewData['exposicion']['id'] }}&asignacion=true`
                )
            .then(response => {
                empresas = response.data.items;

                createSelectize();
                // Agregar las nuevas opciones
                response.data.items.forEach(function(opcion) {
                    selectizeInstance.addOption(opcion);
                });
                // Refrescar las opciones
                selectizeInstance.refreshOptions(false);

                $('#containerEmpresas').removeClass('d-none');

                selectizeInstance.focus();
                selectizeInstance.open();

            })
            .catch(error => {
                console.log(error);
            });
    }

    async function openModalAsignarStand(labelStand) {

        empresas = [];
        $('#inputSearchEmpresa').val('');
        createSelectize();

        let match = labelStand.match(/(\d+[a-zA-Z]?)/) // Cambiamos la expresión regular
        let stand = null;
        //console.log('labelStand: ', labelStand);

        //console.log(match);
        if (match) {
            let numeroStand = match[1]; // Tomamos el primer grupo de captura
            stand = stands.find(el => el.numero == numeroStand);
        }
        //console.log('stand: ', stand)

        if (!stand) {
            alert("El Stand no tiene asignado correctamente su Nro. Especificar en planimetria");
        } else {
            console.log('grupo: ', grupo);
            //console.log(stand.numero.toString().startsWith('6'));

            if (grupo == 3 && !stand.numero.toString().startsWith('6')) {
                alert('No tiene los permisos necesarios para modificar este stand');
                return;
            }
            if (grupo == 2) {
                alert('No tiene los permisos necesarios para modificar este stand');
                return;
            }

            $('#inputAsignacionStandModal_numero_stand').val(stand.numero)
            $('#modalLabelStand').html(labelStand);
            if (stand.id_expositor) {
                let urlGetExpositorById = "{{ route('expositor.get-by-id') }}";
                urlGetExpositorById += '?idExpositor=' + stand.id_expositor;
                axios.get(urlGetExpositorById)
                    .then(response => {
                        // Desactiva la interacción del usuario con el selectize

                        selectizeInstance.addOption(response.data.empresa);
                        selectizeInstance.setValue(response.data.empresa.id);
                        selectizeInstance.lock();
                        //selectizeInstance.$control.find('.selectize-input').css('pointer-events', 'none');

                        if (response.data.empresa.razon_social) {
                            $('#empresaRazonSocial').html(response.data.empresa.razon_social);
                            $('#radioEmpresaRazonSocial').removeClass('d-none');
                        }

                        if (response.data.empresa.nombre_fantasia) {
                            $('#empresaFantasia').html(response.data.empresa.nombre_fantasia);
                            $('#radioEmpresaFantasia').removeClass('d-none');
                        }


                        $('#containerEmpresas').removeClass('d-none');

                        $('#standAreaFacturar').val(stand.metros_cuadrados);
                        $('#standValor').val(stand.valor);
                        $('#standStatus').val(stand.status);
                        $('#idStandTipo').val(stand.id_stand_tipo);
                        $('#standMoneda').val(stand.moneda);
                        $('#standBuscarEmpresa').addClass('d-none');
                        $('#containerEmpresas').addClass('d-none');

                        let routeDeleteStand = "{{ route('mapa.stand.delete') }}";
                        routeDeleteStand += '?idStand=' + stand.id;
                        $('#btnEliminarAsignacionStand').attr('href', routeDeleteStand);
                        $('#btnEliminarAsignacionStand').removeClass('d-none');

                        $('#usuarioExpositor').val(response.data.expositor.usuario);
                        $('#expositorWhatsapp').val(response.data.expositor.whatsapp);
                        $('#usuarioExpositor').attr('readonly', true);

                        $('#usuarioExpositorContainer').removeClass('d-none');

                        if (stand.nombre_exposicion == 'nombre_fantasia') {
                            $('#checkNombreExposicion2').prop('checked', true);
                        } else {
                            $('#checkNombreExposicion1').prop('checked', true);
                        }

                        console.log('response.data.expositor:', response.data.expositor)

                    })
                    .catch(error => {
                        console.log(error);
                    })
            } else {
                {{--
                @if (Auth::guard()->user()->grupo != 1)
                    alert('Debe solicitar al Administrador que cree un expositor para este stand');
                    return;
                @endif
                --}}

                let urlGet = "{{ route('mapa.stand.get-stand-by-id') }}";
                urlGet += '?id=' + stand.id;
                axios.get(urlGet)
                    .then(response => {
                        console.log('mapa.stand.get-stand-by-id: ',response);
                        $('#standAreaFacturar').val(response.data[0].metros_cuadrados);
                    });

                selectizeInstance.unlock();
                $('#standBuscarEmpresa').removeClass('d-none');
                $('#btnEliminarAsignacionStand').addClass('d-none');


            }

            $('#buttonOpenModalAsignarStand').click();
        }
    }

    document.getElementById('inputSearchEmpresa').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('buttonInputSearchEmpresa').click();
        }

        $('#formAsignacionStand').on('submit', function(e) {
            e.preventDefault(); // Evitar el envío por defecto

            // Verificar qué botón fue clicado
            if ($(document.activeElement).is('#btnSubmitFormAsigacionStand')) {
                // Aquí puedes ejecutar la lógica de envío
                alert('Formulario enviado!');
                // Por ejemplo, puedes enviar el formulario con AJAX o usar el método submit() si lo deseas
            } else if ($(document.activeElement).is('#btnEliminarAsignacionStand')) {
                // Aquí puedes ejecutar la lógica para eliminar
                alert('Formulario eliminado!');
            }
        });

        // Hacer que el botón de enviar simule un envío de formulario
        $('#btnSubmitFormAsigacionStand').on('click', function() {
            $('#formAsignacionStand').submit(); // Dispara el evento submit
        });

        $('#btnEliminarAsignacionStand').on('click', function() {
            $('#formAsignacionStand').submit(); // Dispara el evento submit
        });
    });
</script>
