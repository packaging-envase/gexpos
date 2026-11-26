<div>
    <h2 class="text-center mb-2">
        <u>{{ isset($viewData['empresa']) && $viewData['empresa']['razon_social'] ? $viewData['empresa']['razon_social'] : 'Empresa' }}</u>
    </h2>
    <div class="row mb-2">
        <div class="col-12">
            <div class="text-center">
                <h5>Eventos en los que Participo</h5>
                <button class="btn btn-success">Exposicion 2023</button>
                <button class="btn btn-secondary">Exposicion 2021</button>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('empresa.update-store') }}" enctype="multipart/form-data">
        @csrf
        @if (isset($viewData['exposicion']) && $viewData['exposicion'])
            <input hidden name="idExposicion" value="{{ $viewData['exposicion']['id'] }}" />
        @endif

        <input hidden name="id" value="{{ isset($viewData['empresa']) ? $viewData['empresa']['id'] : '' }}" />
        <div class="row no-gutters border p-2">
            <div class="col-12">
                <h4><u>Datos Principales</u></h4>
            </div>
            <div class="col-12">

                <img id="empresaLogo"
                    src="{{ isset($viewData['empresa']) && $viewData['empresa']['logo'] ? asset('storage/empresa/logo/' . $viewData['empresa']['logo']['path']) : '' }}"
                    style="max-width: 250px; max-height: 250px;" />
                <br />

                <button class="btn btn-success mt-2" type="button" onclick="$('#inputFileLogo').click()">Actualizar
                    Logo</button>
                @if (isset($viewData['empresa']))
                    <a href="{{ route('empresa.delete', ['id' => $viewData['empresa']['id']]) }}"
                        class="btn btn-danger float-end"
                        onclick="return confirm('Está por eliminar ésta empresa')">Borrar</a>
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
                <input class="form-control" name="nombre_fantasia"
                    value="{{ isset($viewData['empresa']) ? $viewData['empresa']['nombre_fantasia'] : '' }}" />
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
                        <option value="{{ $item['id'] }}">{{ $item['descripcion'] }}
                            {{ isset($viewData['empresa']) && $viewData['empresa']['id_provincia'] == $item['id'] ? 'selected=true' : '' }}
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
            <div class="col-12">
                <div class="row no-gutters p-2 border my-2">
                    <div class="col-12 col-md-2 px-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" name="recibe_email"
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
                            <input class="form-check-input" type="checkbox" value="" id="exportadorCheckbox"
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
                            <input class="form-check-input" type="checkbox" value="" id="distribuidorChecked"
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

        <div class="row no-gutters border p-2 mt-2">
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
                                <button class="btn btn-danger" type="button"
                                    onclick="$('#contactoDeleted{{ $contacto['id'] }}').val('1'); $('#rowContactoExist{{ $contacto['id'] }}').addClass('d-none');">Eliminar</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row no-gutters border p-2 mt-2">
            <div class="col-12">
                <h4><u>Rubros</u></h4>
                <select id="selectRubros" class="form-control my-2" style="max-width: fit-content;"
                    onchange="changeSelectedRubro()">
                    <option hidden value="">Agregar Rubro</option>
                    @foreach ($viewData['categorias'] as $categoria0)
                        <option disabled value="{{ $categoria0['id'] }}">
                            {{ $categoria0['nombre'] }}
                        </option>
                        @foreach ($categoria0['categoria1'] as $categoria1)
                            <option value="{{ $categoria1['id'] }}" class="ms-2">
                                <span class="ms-1">&#160;&#160;{{ $categoria1['nombre'] }}</span>
                            </option>
                            @foreach ($categoria1['categoria2'] as $categoria2)
                                <option value="{{ $categoria2['id'] }}">
                                    <span class="ms-2">&#160;&#160;&#160;&#160;{{ $categoria2['nombre'] }}</span>
                                </option>
                            @endforeach
                        @endforeach
                    @endforeach
                </select>
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
        <!--
        <div id="productosServicios" class="row no-gutters border p-2 mt-2">
            <div class="col-12">
                <div class="d-flex">
                    <h4><u>Productos y Servicios</u></h4>
                    <button class="btn btn-success ms-2" type="button" onclick="addProductosServicios()">
                        + Agregar
                    </button>
                </div>
            </div>
            @if (isset($viewData['empresa']['productos']))
                @foreach ($viewData['empresa']['productos'] as $item)
<div class="col-12" id="colProductoServicio{{ $item['id'] }}">
                        <div class="row no-gutters">
                            <div class="col-12 col-md-3 px-2">
                                <label>Nombre Producto o Servicio</label>
                                <input required class="form-control" name="productoServicioNombre{{ $item['id'] }}"
                                    value="{{ $item['nombre'] }}" />
                            </div>
                            <div class="col-12 col-md-3 px-2">
                                <label>Descripcion</label>
                                <textarea class="form-control" style="resize: none;" name="productoServicioDescripcion{{ $item['id'] }}">{{ $item['descripcion'] }}</textarea>
                            </div>
                            <div class="col-12 col-md-2 px-2">
                                <input hidden id="inputProdServImagenExist{{ $item['id'] }}" type="file"
                                    name="prodServImagenExist{{ $item['id'] }}"
                                    onchange="if (validateFile(this, 'imageProductExist{{ $item['id'] }}')) 
                                {$('#prodServImagenExistSmall{{ $item['id'] }}')
                                    .html(document.getElementById('inputProdServImagenExist{{ $item['id'] }}').files.length+' archivo cargado'); 
                                }" />

                                <input hidden id="deleteProdServExist{{ $item['id'] }}"
                                    name="deleteProdServExist{{ $item['id'] }}" value="0" />

                                <button class="btn btn-success" type="button"
                                    onclick="$('#inputProdServImagenExist{{ $item['id'] }}').click();">Cargar
                                    Imagen</button><br />
                                <small id="prodServImagenExistSmall{{ $item['id'] }}"></small><br />
                                <button class="btn btn-danger mt-1" type="button"
                                    onclick="if (confirm('Esta a punto de eliminar este producto'))
                            {$('#colProductoServicio'+{{ $item['id'] }}).addClass('d-none');
                            $('#deleteProdServExist'+{{ $item['id'] }}).val('1');}">
                                    Eliminar
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <img id="imageProductExist{{ $item['id'] }}"
                                    src="{{ $item['path_logo'] ? asset('storage/empresa/productoServicioLogo/' . $item['path_logo']) : '' }}"
                                    style="max-width: 250px; max-height: 250px;" />
                                <br />
                            </div>
                        </div>
                        <hr />
                    </div>
@endforeach
            @endif
        </div>
        <div id="empresasRepresentadas" class="row no-gutters border p-2 mt-2">
            <div class="col-12">
                <div class="d-flex">
                    <h4 class="mt-1"><u>Empresas Representadas</u></h4>
                    <button type="button" class="btn btn-success ms-2" onclick="addEmpresaRepresentada()">
                        + Agregar
                    </button>
                </div>
                @if (isset($viewData['empresa']['empresaRepresentada']))
                    @foreach ($viewData['empresa']['empresaRepresentada'] as $item)
<div class="col-12" id="colEmpresaRepresentada{{ $item['id'] }}">
                            <div class="row no-gutters">
                                <div class="col-12 col-md-3 px-2">
                                    <label>Nombre Empresa Representada</label>
                                    <input required class="form-control"
                                        name="empresaRepresentadaNombre{{ $item['id'] }}"
                                        value="{{ $item['nombre'] }}" />
                                </div>
                                <div class="col-12 col-md-3 px-2">
                                    <label>Descripcion</label>
                                    <textarea class="form-control" style="resize: none;" name="empresaRepresentadaDescripcion{{ $item['id'] }}">{{ $item['descripcion'] }}</textarea>
                                </div>
                                <div class="col-12 col-md-2 px-2">
                                    <input hidden id="inputEmpresaRepresentadaImagenExist{{ $item['id'] }}"
                                        type="file" name="empresaRepresentadaImagenExist{{ $item['id'] }}"
                                        onchange="if (validateFile(this, 'imageEmpresaRepresentadaExist{{ $item['id'] }}')) 
                                {$('#empresaRepresentadaImagenExistSmall{{ $item['id'] }}')
                                    .html(document.getElementById('inputEmpresaRepresentadaImagenExist{{ $item['id'] }}').files.length+' archivo cargado'); 
                                }" />

                                    <input hidden id="deleteEmpresaRepresentadaExist{{ $item['id'] }}"
                                        name="deleteEmpresaRepresentadaExist{{ $item['id'] }}" value="0" />

                                    <button class="btn btn-success" type="button"
                                        onclick="$('#inputEmpresaRepresentadaImagenExist{{ $item['id'] }}').click();">Cargar
                                        Imagen</button><br />
                                    <small id="empresaRepresentadaImagenExistSmall{{ $item['id'] }}"></small><br />
                                    <button class="btn btn-danger mt-1" type="button"
                                        onclick="if (confirm('Esta a punto de eliminar esta empresa representada'))
                            {$('#colEmpresaRepresentada'+{{ $item['id'] }}).addClass('d-none');
                            $('#deleteEmpresaRepresentadaExist'+{{ $item['id'] }}).val('1');}">
                                        Eliminar
                                    </button>
                                </div>
                                <div class="col-12 col-md-4">
                                    <img id="imageEmpresaRepresentadaExist{{ $item['id'] }}"
                                        src="{{ $item['path_logo'] ? asset('storage/empresa/empresaRepresentadaLogo/' . $item['path_logo']) : '' }}"
                                        style="max-width: 250px; max-height: 250px;" />
                                    <br />
                                </div>
                            </div>
                            <hr />
                        </div>
@endforeach
                @endif
            </div>
        </div>
        -->
        <div class="col-12 col-md-12 mt-2">
            <button class="btn btn-success float-end" type="submit">Guardar</button>
            <div style="clear: both;"></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    let ciudades = @json($viewData['ciudades']);
    let categorias = @json($viewData['categoriasRaw']);

    $('#provincias').on('change', function() {
        let idProvincia = this.value;

        let ciudadesFiltradas = ciudades.filter(function(objeto) {
            return objeto.id_provincia == idProvincia;
        });

        $('#ciudades').empty();
        $('#ciudades').append(`<option value="">Seleccionar</option>`);

        let appendCiudad = '';
        ciudadesFiltradas.forEach(ciudad => {
            appendCiudad = appendCiudad + `<option value="` + ciudad.id + `">` + ciudad.descripcion +
                `</option>`;
        });
        $('#ciudades').append(appendCiudad);
    });

    $('#paises').on('change', function() {
        if (this.value == '11') {
            $('#divInputProvincia').addClass('d-none');
            $('#divInputCiudad').addClass('d-none');
            $('#divSelectProvincia').removeClass('d-none');
            $('#divSelectCiudad').removeClass('d-none');
        } else {
            $('#divInputProvincia').removeClass('d-none');
            $('#divInputCiudad').removeClass('d-none');
            $('#divSelectProvincia').addClass('d-none');
            $('#divSelectCiudad').addClass('d-none');
        }
    });

    let rowEmpresaRepresentada = 0;

    function addEmpresaRepresentada() {
        let div = $('#empresasRepresentadas');

        let empresaRepresentada = `
            <div id="rowEmpresaRepresentada` + rowEmpresaRepresentada + `" class="col-12">
                <div class="row no-gutters">
                    <div class="col-12 col-md-3 px-2">
                        <label>Nombre Empresa Representada</label>
                        <input required class="form-control" name="empresaRepresentadaNombre[]" />
                    </div>
                    <div class="col-12 col-md-3 px-2">
                        <label>Descripcion</label>
                        <textarea class="form-control" style="resize: none;" name="empresaRepresentadaDescripcion[]"></textarea>
                    </div>
                    <div class="col-12 col-md-2 px-2">
                        <input type="hidden" name="empresaRepresentadaImagen[]" value="">
                        <input hidden id="empresaRepresentadaImagen` + rowEmpresaRepresentada + `" 
                        type="file" 
                        name="empresaRepresentadaImagenes[]" 
                        onchange="if (validateFile(this,'imageEmpresaRepresentada` + rowEmpresaRepresentada + `', @json($viewData['catalogo']['max_file_size']))) 
                            {$('#empresaRepresentadaImagenSmall` + rowEmpresaRepresentada + `')
                                .html(document.getElementById('empresaRepresantadaImagen` +
            rowEmpresaRepresentada + `').files.length+' archivo cargado'); 
                            }" />
                        <button class="btn btn-success" type="button" onclick="$('#empresaRepresentadaImagen` +
            rowEmpresaRepresentada + `').click();">Cargar Imagen</button><br/>
                        <small id="empresaRepresentadaImagenSmall` + rowEmpresaRepresentada + `"></small><br/>
                        <button class="btn btn-danger mt-1" type="button" onclick="if (confirm('Esta a punto de eliminar esta empresa representada'))
                        {$('#rowEmpresaRepresentada` + rowEmpresaRepresentada + `').remove();}">Eliminar</button>
                    </div>
                    <div class="col-12 col-md-4">
                        <img id="imageEmpresaRepresentada` + rowEmpresaRepresentada + `"
                            style="max-width: 250px; max-height: 250px;" />
                        <br />
                    </div>
                </div>
                <hr/>
            </div>
        `;

        div.append(empresaRepresentada);
        rowEmpresaRepresentada += 1;
    }



    let rowProdServ = 0;

    function addProductosServicios() {
        let div = $('#productosServicios');

        let prodServ = `
            <div id="rowProdServ` + rowProdServ + `" class="col-12">
                <div class="row no-gutters">
                    <div class="col-12 col-md-3 px-2">
                        <label>Nombre Producto o Servicio</label>
                        <input required class="form-control" name="productoServicioNombre[]" />
                    </div>
                    <div class="col-12 col-md-3 px-2">
                        <label>Descripcion</label>
                        <textarea class="form-control" style="resize: none;" name="productoServicioDescripcion[]"></textarea>
                    </div>
                    <div class="col-12 col-md-2 px-2">
                        <input type="hidden" name="productosImagenes[]" value="">
                        <input hidden id="prodServImagen` + rowProdServ + `" 
                        type="file" 
                        name="productosImagenes[]" 
                        value="null"
                        onchange="if (validateFile(this,'imageProduct` + rowProdServ + `', @json($viewData['catalogo']['max_file_size']))) 
                            {$('#prodServImagenSmall` + rowProdServ + `')
                                .html(document.getElementById('prodServImagen` + rowProdServ + `').files.length+' archivo cargado'); 
                            }" />
                        <button class="btn btn-success" type="button" onclick="$('#prodServImagen` + rowProdServ + `').click();">Cargar Imagen</button><br/>
                        <small id="prodServImagenSmall` + rowProdServ + `"></small><br/>
                        <button class="btn btn-danger mt-1" type="button" onclick="if (confirm('Esta a punto de eliminar este producto'))
                        {$('#rowProdServ` + rowProdServ + `').remove();}">Eliminar</button>
                    </div>
                    <div class="col-12 col-md-4">
                        <img id="imageProduct` + rowProdServ + `"
                            style="max-width: 250px; max-height: 250px;" />
                        <br />
                    </div>
                </div>
                <hr/>
            </div>
        `;

        div.append(prodServ);
        rowProdServ += 1;
    }

    function changeSelectedRubro() {

        let categoria = categorias.find(cat => cat.id == this.event.target.value);
        let el = `
            <li id="liCategoria` + categoria.id + `">
                <input hidden name="categorias[]" value="` + categoria.id + `" />
                <button type="button" class="btn btn-danger my-1 mr-1" onclick="$('#liCategoria` + categoria.id + `').remove();">
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
                    <button class="btn btn-danger" type="button" onclick="$('#rowContacto` + rowContacto + `').remove()">Eliminar</button>
                </div>
            </div>
        `;

        rowContacto += 1;
        $('#listContactos').append(div);
    }
</script>
