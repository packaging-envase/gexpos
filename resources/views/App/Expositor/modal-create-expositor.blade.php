<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
    integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
    integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Modal -->
<button id="buttonOpenModalCreateExpositor" hidden type="button" data-bs-toggle="modal"
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
                <form id="formAsigacionStand" method="POST" action="{{ route('expositor.update-store') }}" onsubmit="if($('#selectEmpresa').val() === ''){ alert('Debe seleccionar una empresa'); return false; }">
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
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#empresaModal">
                        Nueva Empresa
                    </button>

                    <div id="containerEmpresas" class="d-none">
                        <label>Seleccionar Empresa</label><br />
                        <select id="selectEmpresa" name="empresa"></select>
                    </div>

                    <div class="d-flex">
                        <label>Nombre de Usuario</label><small class="ms-2">(Mail)</small>
                    </div>
                    <input required type="mail" name="usuarioExpositor" id="usuarioExpositorEmpresa"
                        class="form-control" style="width: 250px;" />
                    <div class="d-flex">
                        <div>
                            @if (Auth::guard()->user()->grupo == 1)
                                <label>Pertenece a</label>
                                <br/>
                                <input id="expositorPerteneceEnvase" type="radio" name="pertenece" value="envase" checked/>
                                <label for="expositorPerteneceEnvase">Envase</label>
                                <br/>
                                <input id="expositorPerteneceEtif" type="radio" name="pertenece" value="etif" />
                                <label for="expositorPerteneceEtif">ETIF</label>
                            @endif
                        </div>
                        <div class="ms-5">
                            <label>Catalogo</label>
                            <div>
                                <input type="radio" id="radioNingunoEmpresa" name="catalogo" value="ninguno" checked />
                                <label for="radioNingunoEmpresa">Ninguno</label><br />
                                @if (Auth::guard()->user()->grupo == 1)
                                    <input type="radio" id="radioEnvaseEmpresa" name="catalogo" value="envase" />
                                    <label for="radioEnvaseEmpresa">Envase</label><br />
                                @endif
                                <input type="radio" id="radioEnvaseEtif" name="catalogo" value="etif" />
                                <label for="radioEnvaseEtif">ETIF</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a id="btnEliminarAsignacionStand" type="submit" class="btn btn-danger d-none"
                    onclick="return confirm('¿Estás seguro de que deseas liberar el Stand?');">Eliminar</a>
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
                if (empresaSelected.nombre_fantasia) {
                    $('#checkNombreExposicion').removeClass('d-none');
                } else {
                    $('#checkNombreExposicion').addClass('d-none');
                }
            }
        });
    }

    function buscarEmpresa() {
        let urlEmpresaSearch = "{{ route('api.empresa.search') }}";
        axios.get(urlEmpresaSearch + "?search=" + $('#inputSearchEmpresa').val())
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

    async function openModalCreateExpositor() {

        empresas = [];
        $('#inputSearchEmpresa').val('');
        createSelectize();

        selectizeInstance.unlock();
        $('#standBuscarEmpresa').removeClass('d-none');
        $('#btnEliminarAsignacionStand').addClass('d-none');

        $('#buttonOpenModalCreateExpositor').click();
    }

    document.getElementById('inputSearchEmpresa').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('buttonInputSearchEmpresa').click();
        }
    });
</script>
