<div>
    <form id="formCatalogoExpositorEmpresa" method="POST" action="{{ route('expositor.store-expositor-with-empresa') }}" enctype="multipart/form-data">
        @csrf
        <input hidden name="idExposicion" value="{{ $viewData['exposicion']->id }}" />
        <input hidden name="idEmpresa" value="{{ isset($viewData['empresa']) ? $viewData['empresa']['id'] : '' }}" />

        <h4 class="text-center"><u>Configuración de Perfil</u></h4>

        <div class="d-flex justify-content-evenly">
            <!--
            <div class="col-4 d-none" id="checkNombreExposicionEmpresa">
                <div class="mt-2">
                    <label>Nombre en Exposicion</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                            name="nombre_exposicion" id="checkNombreExposicionEmpresa1"
                            value="razon_social" checked>
                        <label class="form-check-label" for="checkNombreExposicionEmpresa1">
                            Razon Social
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                            name="nombre_exposicion" id="checkNombreExposicionEmpresa2"
                            value="nombre_fantasia">
                        <label class="form-check-label" for="checkNombreExposicionEmpresa2">
                            Nombre Fantasia
                        </label>
                    </div>
                </div>
            </div>
            -->
            <div class="">
                <div class="d-flex">
                    <label>Nombre de Usuario</label><small class="ms-2">(Mail)</small>
                </div>
                <input hidden name="idExpositor"
                    value="{{ isset($viewData['expositor']) && $viewData['expositor'] ? $viewData['expositor']['id'] : '' }}" />
                <input required type="mail" name="usuarioExpositorEmpresa" class="form-control" style="width: 250px;"
                    value="{{ isset($viewData['expositor']) && $viewData['expositor'] ? $viewData['expositor']['usuario'] : '' }}" />
                <label>Contraseña</label>
                <input required name="passwordExpositorEmpresa" class="form-control" style="width: 250px;"
                    value="{{ isset($viewData['expositor']) && $viewData['expositor'] ? $viewData['expositor']['password'] : '' }}" />
                <label>Whatsapp</label>
                <input name="expositorWhatsapp" class="form-control" style="width: 250px;"
                    value="{{ isset($viewData['expositor']) && $viewData['expositor'] ? $viewData['expositor']['whatsapp'] : '' }}" />
                @if (isset($viewData['expositor']))
                    <a class="btn btn-danger float-end mt-2"
                        href="{{ route('expositor.delete', ['id' => $viewData['expositor']['id']]) }}">
                        Borrar Expositor
                    </a>
                @endif
            </div>
            <div class="">
                <label>Pertenece a:</label>
                <br />
                <input id="radioPerteneceEnvase" name="pertenece" value="envase" type="radio"
                    {{ isset($viewData['expositor']) && !$viewData['expositor']['pertenece_etif'] ? 'checked=true' : '' }} />
                <label for="radioPerteneceEnvase">Envase</label>
                <br />
                <input id="radioPerteneceEtif" name="pertenece" value="etif" type="radio"
                    {{ isset($viewData['expositor']) && $viewData['expositor']['pertenece_etif'] ? 'checked=true' : '' }} />
                <label for="radioPerteneceEtif">Etif</label>
            </div>
            <div class="">
                <label>Catalogo</label>
                <div>
                    <input type="radio" id="radioNingunoEmpresa" name="catalogo" value="ninguno"
                        {{ (!isset($viewData['expositor'])
                                ? 'checked=true'
                                : isset($viewData['expositor']) &&
                                    $viewData['expositor'] &&
                                    !$viewData['expositor']['catalogo'] &&
                                    !$viewData['expositor']['etif'])
                            ? 'checked=true'
                            : '' }} />
                    <label for="radioNingunoEmpresa">Ninguno</label><br />
                    @if (Auth::guard()->user()->grupo == 1)
                        <input type="radio" id="radioEnvaseEmpresa" name="catalogo" value="envase"
                            {{ isset($viewData['expositor']) && $viewData['expositor'] && $viewData['expositor']['catalogo'] && !$viewData['expositor']['etif'] ? 'checked=true' : '' }} />
                        <label for="radioEnvaseEmpresa">Envase</label><br />
                    @endif
                    <input type="radio" id="radioEnvaseEtif" name="catalogo" value="etif"
                        {{ isset($viewData['expositor']) && $viewData['expositor'] && !$viewData['expositor']['catalogo'] && $viewData['expositor']['etif'] ? 'checked=true' : '' }} />
                    <label for="radioEnvaseEtif">ETIF</label>
                </div>
            </div>
        </div>

        <div class="row no-gutters p-2">
            <div class="col-12">
                <h4 class=""><u>Datos de la Empresa</u></h4>
            </div>
            <div class="col-12">
                <img id="empresaLogo"
                    src="{{ isset($viewData['empresa']) && $viewData['empresa']['logo'] ? asset('storage/empresa/logo/' . $viewData['empresa']['logo']['path']) : '' }}"
                    style="max-width: 250px; max-height: 250px;" class="mb-2" />
                <br />

                <button class="btn btn-success" type="button" onclick="$('#inputFileLogo').click()">Actualizar
                    Logo</button>
                @if (isset($viewData['empresa']))
                    <!--
                    <a href="{{ route('empresa.delete', ['id' => $viewData['empresa']['id']]) }}"
                        class="btn btn-danger float-end"
                        onclick="return confirm('Está por eliminar ésta empresa')">Borrar</a>
                    -->
                @endif
                <input hidden type="file" name="logo" id="inputFileLogo"
                    onchange="if (validateFile(this, 'empresaLogo', @json($viewData['catalogo']['max_file_size']))) {logoChange();}" /><br />
                <small id="inputFileLogoDescription"></small>
            </div>
            <script type="text/javascript">
                function logoChange() {
                    console.log(document.getElementById("inputFileLogo").files.length);
                    if (document.getElementById("inputFileLogo").files.length != 0) {
                        console.log('asd');
                        $('#inputFileLogoDescription').html(document.getElementById("inputFileLogo").files.length +
                            ' archivo cargado');
                    }
                }
            </script>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">CUIT</label>
                <input minlength="11" maxlength="11" class="form-control" name="cuit"
                    oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['cuit'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Codigo IAE</label>
                <input minlength="11" maxlength="11" class="form-control" name="codigo_iae"
                    oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['codigo_iae'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Razon Social</label>
                <input required class="form-control" name="razon_social"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['razon_social'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Nombre Fantasia</label>
                <input class="form-control" id="nombre_fantasia" name="nombre_fantasia"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['nombre_fantasia'] : '' }}"
                    onchange="changeFantasia()" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Pais</label>
                <select name="id_pais" id="paises" class="form-control">
                    <option value="">Seleccionar</option>
                    @foreach ($viewData['paises'] as $item)
                        <option value="{{ $item['id'] }}" {{ $item['id'] == '11' ? 'selected=true' : '' }}
                            {{ isset($viewData['empresa']) && $viewData['empresa']['id_pais'] == $item['id'] ? 'selected=true' : '' }}>
                            {{ $item['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
            <div id="divSelectProvincia" class="col-12 col-md-4 px-2 mt-2">
                <label for="">Provincia</label>
                <select name="id_provincia" id="provincias" class="form-control">
                    <option value="">Seleccionar</option>
                    @foreach ($viewData['provincias'] as $item)
                        <option value="{{ $item['id'] }}" {{ isset($viewData['empresa']) && $viewData['empresa']['id_provincia'] == $item['id'] ? 'selected=true' : '' }}>
                            {{ $item['descripcion'] }}                            
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="divSelectCiudad" class="col-12 col-md-4 px-2 mt-2">
                <label for="">Ciudad</label>
                <select id="ciudades" name="id_ciudad" class="form-control">
                    <option value="">Seleccionar</option>
                </select>
            </div>
            <div id="divInputProvincia" class="col-12 col-md-4 px-2 mt-2 d-none">
                <label for="">Provincia</label>
                <input class="form-control" name="provincia" />
            </div>
            <div id="divInputCiudad" class="col-12 col-md-4 px-2 mt-2 d-none">
                <label for="">Ciudad</label>
                <input class="form-control" name="ciudad" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Codigo Postal</label>
                <input class="form-control" name="codigo_postal"
                    oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['codigo_postal'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Direccion</label>
                <input class="form-control" name="direccion"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['direccion'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Telefono</label>
                <input class="form-control" name="telefono"
                    oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['telefono'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Whatsapp</label>
                <input class="form-control" name="whatsapp"
                    oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['whatsapp'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Email</label>
                <input class="form-control" name="email"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['email'] : '' }}" />
            </div>
            <div class="col-12 col-md-4 px-2 mt-2">
                <label for="">Web</label>
                <input class="form-control" name="web"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['web'] : '' }}" />
            </div>
            <div class="col-12 col-md-2 px-2 mt-2">
                <label for="">Fecha Alta</label>
                <input required type="date" class="form-control" name="fecha_alta" value="{{ date('Y-m-d') }}"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['fecha_alta'] : '' }}" />
            </div>
            <div class="col-12 col-md-2 px-2 mt-2">
                <label for="">Fecha Baja</label>
                <input type="date" class="form-control" name="fecha_baja"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['fecha_baja'] : '' }}" />
            </div>

            <div class="col-12 px-2 mt-2">
                <label for="">Descripcion</label>
                <textarea id="mytextarea" name="descripcion">{{ isset($viewData['empresa']) ? $viewData['empresa']['descripcion'] : '' }}</textarea>
                <script type="text/javascript">
                    tinymce.init({
                        selector: '#mytextarea',
                        license_key: 'gpl'
                    });
                </script>
            </div>
            <div class="row no-gutters p-2 mt-2">
                <div class="col-6">
                    <label>Origen de la Empresa</label>
                    <input class="form-control" maxlength="255" name="origen" value="{{isset($viewData['empresa']) ? $viewData['empresa']['origen'] : ''}}" />
                </div>
                <div class="col-6">
                    <label>Fecha de fundacion</label>
                    <input class="form-control" type="date" maxlength="255" name="fecha_fundacion" value="{{isset($viewData['empresa']) ? $viewData['empresa']['fecha_fundacion'] : ''}}" />
                </div>
            </div>

            <hr class="mt-2" />
            <div class="row no-gutters p-2 mt-2">
                <h4><u>Datos de Contacto Comercial</u></h4>
                <div class="col-12 col-md-4 px-2 mt-2">
                    <label for="">Nombre</label>
                    <input class="form-control" name="catalgoNombre"
                        value="{{ isset($viewData['expositorModuloCatalogo']) && $viewData['expositorModuloCatalogo']
                            ? $viewData['expositorModuloCatalogo']['nombre']
                            : '' }}" />
                </div>
                <div class="col-12 col-md-4 px-2 mt-2">
                    <label for="">Direccion</label>
                    <input class="form-control" name="catalogoDireccion"
                        value="{{ isset($viewData['expositorModuloCatalogo']) && $viewData['expositorModuloCatalogo']
                            ? $viewData['expositorModuloCatalogo']['direccion']
                            : (isset($viewData['empresa'])
                                ? $viewData['empresa']['direccion']
                                : '') }}" />
                </div>
                <div class="col-12 col-md-4 px-2 mt-2">
                    <label for="">Telefono</label>
                    <input class="form-control" name="catalogoTelefono"
                        oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                        value="{{ isset($viewData['expositorModuloCatalogo']) && $viewData['expositorModuloCatalogo']
                            ? $viewData['expositorModuloCatalogo']['telefono']
                            : (isset($viewData['empresa'])
                                ? $viewData['empresa']['telefono']
                                : '') }}" />
                </div>
                <div class="col-12 col-md-4 px-2 mt-2">
                    <label for="">Whatsapp</label>
                    <input class="form-control" name="catalogoWhatsapp"
                        oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                        value="{{ isset($viewData['expositorModuloCatalogo']) && $viewData['expositorModuloCatalogo']
                            ? $viewData['expositorModuloCatalogo']['whatsapp']
                            : (isset($viewData['empresa'])
                                ? $viewData['empresa']['whatsapp']
                                : '') }}" />
                </div>
                <div class="col-12 col-md-4 px-2 mt-2">
                    <label for="">Email</label>
                    <input class="form-control" name="catalogoEmail"
                        value="{{ isset($viewData['expositorModuloCatalogo']) && $viewData['expositorModuloCatalogo']
                            ? $viewData['expositorModuloCatalogo']['email']
                            : (isset($viewData['empresa'])
                                ? $viewData['empresa']['email']
                                : '') }}" />
                </div>
                <div class="col-12 col-md-4 px-2 mt-2">
                    <label for="">Web</label>
                    <input class="form-control" name="catalogoWeb"
                        value="{{ isset($viewData['expositorModuloCatalogo']) && $viewData['expositorModuloCatalogo']
                            ? $viewData['expositorModuloCatalogo']['web']
                            : (isset($viewData['empresa'])
                                ? $viewData['empresa']['web']
                                : '') }}" />
                </div>
            </div>
            <hr class="mt-2" />

            <div class="col-12 p-2">
                <h4><u>Paises a los que exporta</u></h4>
                <textarea class="form-control" name="paises_exportar" style="resize: none;">{{ isset($viewData['empresa']) ? $viewData['empresa']['paises_exportar'] : '' }}</textarea>
            </div>

            <hr class="mt-2" />

            <div class="col-12 px-2 mt-2">
                <h4><u>Redes Sociales</u></h4>
            </div>
            <div class="col-12 col-md-3 px-2 mt-2">
                <label for="">Facebook</label>
                <input class="form-control" name="facebook"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['facebook'] : '' }}" />
            </div>
            <div class="col-12 col-md-3 px-2 mt-2">
                <label for="">Instagram</label>
                <input class="form-control" name="instagram"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['instagram'] : '' }}" />
            </div>
            <div class="col-12 col-md-3 px-2 mt-2">
                <label for="">YouTube</label>
                <input class="form-control" name="youtube"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['youtube'] : '' }}" />
            </div>
            <div class="col-12 col-md-3 px-2 mt-2">
                <label for="">Linkedin</label>
                <input class="form-control" name="linkedin"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['linkedin'] : '' }}" />
            </div>

            <hr class="mt-2" />

            <div class="col-12 px-2 mt-2">
                <h4><u>Información Comercial</u></h4>
            </div>
            <div class="col-12">
                <div class="row no-gutters p-2">
                    <div class="col-12 col-md-2 px-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recibe_email"
                                id="recibeEmail"
                                {{ isset($viewData['empresa']) && $viewData['empresa']['recibe_email'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="recibeEmail">
                                Recibe Email
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 px-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="importadorCheckbox"
                                name="importador"
                                {{ isset($viewData['empresa']) && $viewData['empresa']['importador'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="importadorCheckbox">
                                Importador
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 px-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="exportadorCheckbox"
                                name="exportador"
                                {{ isset($viewData['empresa']) && $viewData['empresa']['exportador'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="exportadorCheckbox">
                                Exportador
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 px-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="representanteCheckbox"
                                name="representante"
                                {{ isset($viewData['empresa']) && $viewData['empresa']['representante'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="representanteCheckbox">
                                Representante
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 px-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="distribuidorChecked"
                                name="distribuidor"
                                {{ isset($viewData['empresa']) && $viewData['empresa']['distribuidor'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="distribuidorChecked">
                                Distribuidor
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-2 px-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fabricanteChecked" name="fabricante"
                                {{ isset($viewData['empresa']) && $viewData['empresa']['fabricante'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="fabricanteChecked">
                                Fabricante
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr />

        <div class="row no-gutters p-2 mt-2">
            <div class="col-12">
                <div class="d-flex">
                    <h4><u>Contactos</u></h4>
                    <button class="btn btn-success ms-2" type="button" onclick="addContacto()">+
                        Agregar</button>
                </div>
            </div>

            <div id="listContactos" class="col-12">
                @if (isset($viewData['empresa']['contactos']))
                    @foreach ($viewData['empresa']['contactos'] as $contacto)
                        <div id="rowContactoExist{{ $contacto['id'] }}" class="row no-gutters">
                            <input hidden id="contactoDeleted{{ $contacto['id'] }}"
                                name="contactoDeleted{{ $contacto['id'] }}" value="0" />
                            <div class="col-12 col-md-2">
                                <label>Apellido</label>
                                <input required class="form-control" name="contactoApellido{{ $contacto['id'] }}"
                                    value="{{ $contacto['apellido'] }}" />
                            </div>
                            <div class="col-12 col-md-2">
                                <label>Nombre</label>
                                <input required class="form-control" name="contactoNombre{{ $contacto['id'] }}"
                                    value="{{ $contacto['nombre'] }}" />
                            </div>
                            <div class="col-12 col-md-2">
                                <label>WhatsApp</label>
                                <input class="form-control" name="contactoWhatsapp{{ $contacto['id'] }}"
                                    value="{{ $contacto['telefono'] }}" />
                            </div>
                            <div class="col-12 col-md-2">
                                <label>Cargo</label>
                                <input class="form-control" name="contactoCargo{{ $contacto['id'] }}"
                                    value="{{ $contacto['cargo'] }}" />
                            </div>
                            <div class="col-12 col-md-2">
                                <label>Email</label>
                                <input class="form-control" name="contactoEmail{{ $contacto['id'] }}"
                                    value="{{ $contacto['email'] }}" />
                            </div>
                            <div class="col-12 col-md-2">
                                <label>&#160;</label><br />
                                <button class="btn btn-danger" type="button" id="btnContacto{{$contacto['id']}}"
                                    onclick="$('#contactoDeleted{{ $contacto['id'] }}').val('1'); $('#btnContacto{{$contacto['id']}}').html('Eliminado');">Eliminar</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <hr />

        <div class="row no-gutters p-2 mt-2">
            <div class="col-12">
                <h4><u>Rubros</u></h4>
                <select id="selectCategoria0" class="form-control my-2" style="max-width: fit-content;"
                    onchange="cargarSubcategorias1(this.value)">
                    <option hidden value="">Seleccionar</option>
                    @foreach ($viewData['categorias'] as $categoria0)
                        <option value="{{ $categoria0['id'] }}">{{ $categoria0['nombre'] }}</option>
                    @endforeach
                </select>

                <div class="d-flex align-items-center">
                    <select id="selectCategoria1" class="form-control my-2 d-none" style="max-width: fit-content;"
                        onchange="cargarSubcategorias2(this.value)">
                        <!--<option hidden value="">Seleccionar Rubro</option>-->
                    </select>
                    <button id="btnAgregarSubcategoria" class="btn btn-primary mx-2 d-none"
                        onclick="agregarCategoria(1)" type="button">
                        <!--<i class="fa-solid fa-plus"></i> Agregar -->
                    </button>
                </div>

                <div class="d-flex align-items-center">
                    <select id="selectCategoria2" class="form-control my-2 d-none" style="max-width: fit-content;"
                        onchange="changeSelectedRubro(this.value)">
                        <option hidden value="" id="firstOptionSubRubro">Agregar Subrubro</option>
                    </select>
                    <button id="btnAgregarRubro" class="btn btn-success mx-2 d-none" onclick="agregarCategoria(2)"
                        type="button">
                        <i class="fa-solid fa-plus"></i> Agregar Sub Rubro
                    </button>
                </div>

                <script>
                    const categoriasRaw = @json($viewData['categorias']);
                    let selectedCategoria = null;
                    let selectedSubcategoria = null;

                    function cargarSubcategorias1(idCategoria0) {
                        let select1 = document.getElementById('selectCategoria1');
                        let select2 = document.getElementById('selectCategoria2');
                        let btnAgregarSub = document.getElementById('btnAgregarSubcategoria');
                        let btnAgregarRub = document.getElementById('btnAgregarRubro');

                        select1.innerHTML = '<option hidden value="">Seleccionar Rubro</option>';
                        //select2.innerHTML = '<option hidden value="">Agregar Subrubro</option>';
                        select1.classList.add('d-none');
                        select2.classList.add('d-none');
                        btnAgregarSub.classList.add('d-none');
                        btnAgregarRub.classList.add('d-none');
                        selectedCategoria = null;
                        selectedSubcategoria = null;

                        let categoria0 = categoriasRaw.find(cat => cat.id == idCategoria0);
                        if (categoria0 && categoria0.categoria1.length > 0) {
                            categoria0.categoria1.forEach(categoria1 => {
                                let option = document.createElement('option');
                                option.value = categoria1.id;
                                option.textContent = categoria1.nombre;
                                select1.appendChild(option);
                            });
                            select1.classList.remove('d-none');
                        }
                    }

                    function cargarSubcategorias2(idCategoria1) {
                        let categoria0 = categoriasRaw.find(cat => cat.categoria1.some(sub => sub.id == idCategoria1));
                        let categoria1 = categoria0 ? categoria0.categoria1.find(sub => sub.id == idCategoria1) : null;

                        let select2 = document.getElementById('selectCategoria2');
                        let btnAgregarSub = document.getElementById('btnAgregarSubcategoria');
                        let btnAgregarRub = document.getElementById('btnAgregarRubro');

                        select2.innerHTML = '<option hidden value=""><i class="fa-solid fa-plus"></i> Seleccionar subrubro de '+categoria1.nombre+'</option>';
                        select2.classList.add('d-none');
                        btnAgregarSub.classList.add('d-none');
                        btnAgregarRub.classList.add('d-none');
                        selectedCategoria = null;
                        selectedSubcategoria = null;

                        if (categoria1) {
                            selectedSubcategoria = categoria1;
                            btnAgregarSub.classList.remove('d-none');
                        }

                        if (categoria1 && categoria1.categoria2.length > 0) {
                            categoria1.categoria2.forEach(categoria2 => {
                                let option = document.createElement('option');
                                option.value = categoria2.id;
                                option.textContent = categoria2.nombre;
                                select2.appendChild(option);
                            });
                            select2.classList.remove('d-none');
                        }

                        $('#btnAgregarSubcategoria').html('<i class="fa-solid fa-plus"></i> Inlcuir en listado '+categoria1.nombre);
                        
                    }

                    function changeSelectedRubro(idCategoria2) {
                        let btnAgregarRub = document.getElementById('btnAgregarRubro');
                        let categoria0 = categoriasRaw.find(cat => cat.categoria1.some(sub => sub.categoria2.some(rub => rub.id ==
                            idCategoria2)));
                        let categoria1 = categoria0 ? categoria0.categoria1.find(sub => sub.categoria2.some(rub => rub.id ==
                            idCategoria2)) : null;
                        let categoria2 = categoria1 ? categoria1.categoria2.find(rub => rub.id == idCategoria2) : null;

                        if (categoria2) {
                            selectedCategoria = categoria2;
                            btnAgregarRub.classList.remove('d-none');
                        }
                    }

                    function agregarCategoria(tipo) {
                        let id, nombre, tipoTexto;
                        if (tipo === 1 && selectedSubcategoria) {
                            id = selectedSubcategoria.id;
                            nombre = selectedSubcategoria.nombre;
                            //tipoTexto = "Subcategoría";
                        } else if (tipo === 2 && selectedCategoria) {
                            id = selectedCategoria.id;
                            nombre = selectedCategoria.nombre;
                            //tipoTexto = "Rubro Final";
                        } else {
                            return;
                        }

                        let list = document.getElementById('listCategoriasEmpresa');

                        // Evitar duplicados
                        if (document.getElementById(`liCategoria${id}`)) {
                            alert(tipoTexto + ' ya fue agregada.');
                            return;
                        }

                        let li = document.createElement('li');
                        li.id = `liCategoria${id}`;
                        li.innerHTML = `
                                        <input hidden name="categorias[]" value="${id}" />
                                        <button type="button" class="btn btn-danger my-1 mr-1" onclick="document.getElementById('liCategoria${id}').remove();">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                        <b>${nombre}</b>
                                    `;
                        list.appendChild(li);

                        // Ocultar botón de agregar y resetear selección

                        document.getElementById('btnAgregarSubcategoria').classList.add('d-none');
                        document.getElementById('btnAgregarRubro').classList.add('d-none');

                        document.getElementById('selectCategoria0').value = "";
                        document.getElementById('selectCategoria1').classList.add('d-none');
                        document.getElementById('selectCategoria2').classList.add('d-none');
                        selectedCategoria = null;
                        selectedSubcategoria = null;
                    }
                </script>

                <ul id="listCategoriasEmpresa">
                    @if (isset($viewData['empresa']) && $viewData['empresa'])
                        @foreach ($viewData['empresa']['categorias'] as $categoria)
                            <li id="liCategoria{{ $categoria['id'] }}">
                                <input hidden id="categoria{{ $categoria['id'] }}"
                                    name="categoria{{ $categoria['id'] }}" value="0" />
                                <button type="button" class="btn btn-danger my-1 mr-1"
                                    onclick="$('#liCategoria{{ $categoria['id'] }}').addClass('d-none'); $('#categoria{{ $categoria['id'] }}').val('1');">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                                <b>{{ $categoria['nombre'] }}</b>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        <hr />

        @if (isset($viewData['empresa']))
            @include('App.ModuloExpositor.modulo-productos-servicios')
            <hr />
            @include('App.ModuloExpositor.modulo-empresa-representada')
        @endif

        <div class="row no-gutters p-2 mt-2">

            <div class="col-12 col-md-12 mt-2">
                <button class="btn btn-success float-end" type="submit">Guardar</button>
                <div style="clear: both;"></div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    let ciudades = @json($viewData['ciudades']);
    let categorias = @json($viewData['categoriasRaw']);
    let idProvincia = @json(isset($viewData['empresa']['id_provincia']) ?  $viewData['empresa']['id_provincia'] : null);
    let idCiudad = @json(isset($viewData['empresa']['id_ciudad']) ? $viewData['empresa']['id_ciudad'] : '');

    function changeProvincias() {
        let idProvincia = $('#provincias').val();
        console.log('idProvincia: ',idProvincia);
        console.log('idCiudad:', idCiudad);

        let ciudadesFiltradas = ciudades.filter(function(objeto) {
            return objeto.id_provincia == idProvincia;
        });

        $('#ciudades').empty();
        $('#ciudades').append(`<option value="">Seleccionar</option>`);

        let appendCiudad = '';
        ciudadesFiltradas.forEach(ciudad => {
            appendCiudad = appendCiudad + `<option value="` + ciudad.id + `" ` + 
                (idCiudad == ciudad.id ? 'selected=true' : '') + `>` + 
                ciudad.descripcion +
                `</option>`;
        });
        $('#ciudades').append(appendCiudad);
    }

    function changePaises() {
        if ($('#paises').val() == '11') {
            $('#divInputProvincia').addClass('d-none');
            $('#divInputCiudad').addClass('d-none');
            $('#divSelectProvincia').removeClass('d-none');
            $('#divSelectCiudad').removeClass('d-none');
            changeProvincias();
        } else {
            $('#divInputProvincia').removeClass('d-none');
            $('#divInputCiudad').removeClass('d-none');
            $('#divSelectProvincia').addClass('d-none');
            $('#divSelectCiudad').addClass('d-none');
        }
    }

    $('#provincias').on('change', function() {
        changeProvincias();
    });

    $('#paises').on('change', function() {
        changePaises();
    });

    function changeSelectedRubro() {

        let categoria = categorias.find(cat => cat.id == this.event.target.value);
        let el = `
          <li id="liCategoria` + categoria.id + `">
              <input hidden name="categorias[]" value="` + categoria.id + `" />
              <button type="button" class="btn btn-danger my-1 mr-1" onclick="$('#liCategoria` +
            categoria
            .id + `').remove();">
                  <i class="fa-regular fa-trash-can"></i>
              </button>
              <b>` + categoria.nombre + `</b>
          </li>
        `;

        $('#listCategoriasEmpresa').append(el);

    }

    let rowContacto = 0;

    function addContacto() {
        let div = `
          <div id="rowContacto` + rowContacto + `" class="row no-gutters">
              <div class="col-12 col-md-2">
                  <label>Apellido</label>
                  <input required class="form-control" name="contactoApellido[]" />
              </div>
              <div class="col-12 col-md-2">
                  <label>Nombre</label>
                  <input required class="form-control" name="contactoNombre[]" />
              </div>
              <div class="col-12 col-md-2">
                  <label>WhatsApp</label>
                  <input class="form-control" name="contactoWhatsapp[]" />
              </div>
              <div class="col-12 col-md-2">
                  <label>Cargo</label>
                  <input class="form-control" name="contactoCargo[]" />
              </div>
              <div class="col-12 col-md-2">
                  <label>Email</label>
                  <input class="form-control" name="contactoEmail[]" />
              </div>
              <div class="col-12 col-md-2">
                  <label>&#160;</label><br/>
                  <button class="btn btn-danger" type="button" onclick="$('#rowContacto` +
            rowContacto + `').remove()">Eliminar</button>
              </div>
          </div>
        `;

        rowContacto += 1;
        $('#listContactos').append(div);
    }

    function changeFantasia(event) {

        if ($('#nombre_fantasia').val()) {
            console.log('$(#nombre_fantasia).val(): ', $('#nombre_fantasia').val());
            $('#checkNombreExposicionEmpresa').removeClass('d-none');
        } else {
            $('#checkNombreExposicionEmpresa').addClass('d-none');
        }

    }

    document.getElementById('formCatalogoExpositorEmpresa').addEventListener('submit', function(e) {
        
        const form = e.target;
        if (!form.checkValidity()) {
            e.preventDefault(); // previene el envío
            alert('Por favor, complete todos los campos obligatorios.');
        }
    });

    changePaises();
</script>
