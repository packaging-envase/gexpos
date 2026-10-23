<!doctype html>
@extends('layouts.app')

@section('content')

    <style>
        .expositor-modulo-catalogo .text-serif {
            font-family: "Times New Roman", Times, serif;
        }
    </style>

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="expositor-modulo-catalogo" style="min-height: calc(100vh - 55px);">

        @if (!Auth::guard()->check())
            <div class="container">
                @include('App.ModuloExpositor.header', [
                    'title' => 'Información para el Catálogo',
                    'requerimientos' => false,
                ])
            </div>
        @endif

        @if (Auth::guard()->check())
            <div class="container">
                <div class="border bg-white shadow-sm position-relative mt-3">
                    <h2 class="mb-3">
                        <u>Requerimientos - Catalogo</u>
                    </h2>

                    <a class="btn btn-primary mt-1 mb-2"
                        href="{{ route('expositor.panel-administracion', ['id' => $viewData['exposicion']['id']]) }}}}">
                        <- Volver </a>
                </div>
            </div>
        @endif

        <div class="container">
            <div class="border bg-white shadow-sm position-relative mt-3 mb-5">

                <div class="mt-2">
                    @include('layouts.messages')
                </div>

                <form id="formCatalogo" method="POST" action="{{ route('expositor.modulo.catalogo.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input hidden name="id"
                        value="{{ isset($viewData['empresa']) ? $viewData['empresa']['id'] : '' }}" />
                    <input hidden type="number" name="id_exposicion" value="{{ $viewData['exposicion']['id'] }}" />
                    <input hidden name="idExpositor" value="{{ $viewData['idExpositor'] }}" />

                    <div class="row no-gutters p-2 mt-2">
                        <div class="col-12">

                            <div class="float-end">
                                <b>Fecha de Cierre de Modulo: {{ \DateTime::createFromFormat('Y-m-d', $viewData['catalogo']['cierre'])->format('d/m/Y') }}</b>
                            </div>

                            <img id="empresaLogo"
                                src="{{ isset($viewData['empresa']) && $viewData['empresa']['logo'] ? asset('storage/empresa/logo/' . $viewData['empresa']['logo']['path']) : '' }}"
                                style="max-width: 250px; max-height: 250px;" />
                            <br />

                            <h5>
                                {{ isset($viewData['empresa']['stand']) &&
                                $viewData['empresa']['stand'] &&
                                $viewData['empresa']['stand']['nombre_exposicion']
                                    ? ($viewData['empresa']['stand']['nombre_exposicion'] == 'razon_social'
                                        ? $viewData['empresa']['razon_social']
                                        : $viewData['empresa']['nombre_fantasia'])
                                    : $viewData['empresa']['razon_social'] }}
                            </h5>
                            <p>
                                La imágen no debe superar los {{ $viewData['catalogo']['max_file_size'] }}MB
                                <br>
                                Preferentemente de fondo blanco / transparente
                                <br />
                                Resolución mínima sugerida de 250px x 250px
                            </p>
                            <button class="btn btn-success" type="button" onclick="$('#inputFileLogo').click()">
                                Actualizar Logo
                            </button>

                            <input hidden type="file" name="logo" id="inputFileLogo"
                                onchange="if (validateFile(this, 'empresaLogo', @json($viewData['catalogo']['max_file_size']))) {logoChange();}" /><br />
                            <small id="inputFileLogoDescription"></small>
                        </div>
                    </div>
                    <div class="row no-gutters p-2 mt-2">
                        <h4><u>Redes Sociales</u></h4>
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
                    </div>

                    <div class="row no-gutters p-2 mt-2">
                        <h4><u>Datos de Contacto Comercial</u></h4>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <!-- Falta hacer relacion tablas pais, provincia, localidad... toma los datos de la base vieja o los seteados por el expositor -->
                            <label for="">País</label>
                            <input class="form-control" name="pais"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['pais']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['pais']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Provincia</label>
                            <input class="form-control" name="provincia"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['provincia']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['provincia']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Localidad</label>
                            <input class="form-control" name="localidad"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['localidad']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['ciudad']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Código Postal</label>
                            <input class="form-control" name="codigo_postal"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['codigo_postal']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['codigo_postal']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Nombre</label>
                            <input class="form-control" name="catalgoNombre"
                                value="{{ isset($viewData['expositorModuloCatalogo']) && $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['nombre']
                                    : '' }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Direccion</label>
                            <input class="form-control" name="direccion"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['direccion']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['direccion']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Telefono</label>
                            <input class="form-control" name="telefono"
                                oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['telefono']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['telefono']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Whatsapp</label>
                            <input class="form-control" name="whatsapp"
                                oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.].*?)\..*/g, '$1');"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['whatsapp']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['whatsapp']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Email</label>
                            <input class="form-control" name="email"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['email']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['email']
                                        : '') }}" />
                        </div>
                        <div class="col-12 col-md-4 px-2 mt-2">
                            <label for="">Web</label>
                            <input class="form-control" name="web"
                                value="{{ $viewData['expositorModuloCatalogo']
                                    ? $viewData['expositorModuloCatalogo']['web']
                                    : (isset($viewData['empresa'])
                                        ? $viewData['empresa']['web']
                                        : '') }}" />
                        </div>
                    </div>

                    <div class="row no-gutters p-2 mt-2">
                        <div class="col-12">
                            <h2><u>Descripción (español)</u></h2>
                            <label>Caracteres Permitidos:
                                {{ $viewData['catalogo']['max_caracteres_empresa'] }}</label><br />
                            <label id="caracteresUtilizados">Caracteres Utilizados:
                                {{ isset($viewData['empresa']) ? strlen($viewData['empresa']['descripcion']) : '' }}</label>
                            <textarea id="mytextarea" name="descripcion" maxlength="{{ $viewData['catalogo']['max_caracteres_empresa'] }}">{{ isset($viewData['empresa']) ? $viewData['empresa']['descripcion'] : '' }}</textarea>
                            <script type="text/javascript">
                                tinymce.init({
                                    selector: '#mytextarea',
                                    license_key: 'gpl',
                                    setup: function(editor) {
                                        const maxLength = @json($viewData['catalogo']['max_caracteres_empresa']);

                                        editor.on('input', function() {
                                            const content = editor.getContent({
                                                format: 'text'
                                            });
                                            $('#caracteresUtilizados').html('Caracteres Utilizados: ' + content.length);
                                            if (content.length > maxLength) {
                                                editor.setContent(content.substring(0, maxLength));
                                                editor.selection.setCursorLocation(null, content.substring(0, maxLength)
                                                    .length); // Establecer el cursor al final
                                                alert('Has alcanzado el límite máximo de caracteres.');
                                            }
                                        });
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <div class="row no-gutters p-2 mt-2">
                        <div class="col-12">
                            <h2><u>Descripción (ingles)</u></h2>
                            <label>Caracteres Permitidos:
                                {{ $viewData['catalogo']['max_caracteres_empresa'] }}</label><br />
                            <label id="caracteresUtilizados">Caracteres Utilizados:
                                {{ isset($viewData['empresa']) ? strlen($viewData['empresa']['descripcion_ingles']) : '' }}</label>
                            <textarea id="mytextarea" name="descripcion_ingles" maxlength="{{ $viewData['catalogo']['max_caracteres_empresa'] }}">{{$viewData['empresa']['descripcion_ingles']}}</textarea>
                        </div>
                    </div>
                    <div class="row no-gutters p-2 mt-2">
                        <div class="col-6">
                            <label>Origen de la Empresa</label>
                            <input class="form-control" maxlength="255" name="origen"
                                value="{{ isset($viewData['empresa']) ? $viewData['empresa']['origen'] : '' }}" />
                        </div>
                        <div class="col-6">
                            <label>Fecha de fundacion</label>
                            <input class="form-control" type="date" maxlength="255" name="fecha_fundacion"
                                value="{{ isset($viewData['empresa']) ? $viewData['empresa']['fecha_fundacion'] : '' }}" />
                        </div>
                    </div>
                    <div class="row no-gutters p-2 mt-2">
                        <div class="col-12">
                            <h4><u>Paises a los que exporta</u></h4>
                            <textarea class="form-control" name="paises_exportar" style="resize: none;">{{ isset($viewData['empresa']) ? $viewData['empresa']['paises_exportar'] : '' }}</textarea>
                        </div>
                    </div>
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
                                <select id="selectCategoria1" class="form-control my-2 d-none"
                                    style="max-width: fit-content;" onchange="cargarSubcategorias2(this.value)">
                                    <!--<option hidden value="">Seleccionar Rubro Principal</option>-->
                                </select>
                                <button id="btnAgregarSubcategoria" class="btn btn-primary mx-2 d-none"
                                    onclick="agregarCategoria(1)" type="button">
                                    <!--<i class="fa-solid fa-plus"></i> Agregar Rubro Principal-->
                                </button>
                            </div>

                            <div class="d-flex align-items-center">
                                <select id="selectCategoria2" class="form-control my-2 d-none"
                                    style="max-width: fit-content;" onchange="changeSelectedRubro(this.value)">
                                    <!--<option hidden value="">Seleccionar Subrubro</option>-->
                                </select>
                                <button id="btnAgregarRubro" class="btn btn-success mx-2 d-none"
                                    onclick="agregarCategoria(2)" type="button">
                                    <!--<i class="fa-solid fa-plus"></i> Agregar Subrubro-->
                                </button>
                            </div>

                            <script>
                                const categorias = @json($viewData['categorias']);
                                let selectedCategoria = null;
                                let selectedSubcategoria = null;

                                function cargarSubcategorias1(idCategoria0) {
                                    let select1 = document.getElementById('selectCategoria1');
                                    let select2 = document.getElementById('selectCategoria2');
                                    let btnAgregarSub = document.getElementById('btnAgregarSubcategoria');
                                    let btnAgregarRub = document.getElementById('btnAgregarRubro');

                                    select1.innerHTML = '<option hidden value="">Seleccionar Rubro</option>';
                                    //select2.innerHTML = '<option hidden value="">Seleccionar Subrubro</option>';
                                    select1.classList.add('d-none');
                                    select2.classList.add('d-none');
                                    btnAgregarSub.classList.add('d-none');
                                    btnAgregarRub.classList.add('d-none');
                                    selectedCategoria = null;
                                    selectedSubcategoria = null;

                                    let categoria0 = categorias.find(cat => cat.id == idCategoria0);
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
                                    let categoria0 = categorias.find(cat => cat.categoria1.some(sub => sub.id == idCategoria1));
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
                                    let categoria0 = categorias.find(cat => cat.categoria1.some(sub => sub.categoria2.some(rub => rub.id ==
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



                    @if (isset($viewData['empresa']))
                        @include('App.ModuloExpositor.modulo-productos-servicios')
                        <hr />
                        @include('App.ModuloExpositor.modulo-empresa-representada')
                    @endif

                    @if ($viewData['catalogo']['cierre'] >= date('Y-m-d'))
                    <div class="row">
                        <div class="col-12 col-md-12 mt-2 mb-2">
                            <button class="btn btn-success float-end" type="submit">Guardar</button>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var partes = "<?php echo $viewData['catalogo']['cierre']; ?>".split("-");
            var cierreDate = new Date(
            partes[0],
            partes[1] - 1,
            partes[2]
            );

            var today = new Date();
            console.log('cierreDate: ',cierreDate);
            console.log('today: ',today);
            // Comparar las fechas
            cierreDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);

            /* comparación solo por día (inclusivo) */
            if (today > cierreDate) {
                $('#formCatalogo input, #formCatalogo button').prop('disabled', true);
            }
        });

        document.getElementById('formCatalogo').addEventListener('submit', function(e) {
            const form = e.target;
            if (!form.checkValidity()) {
                e.preventDefault(); // previene el envío
                alert('Por favor, complete todos los campos obligatorios.');
            }
        });

        /*
                    let categorias = @json($viewData['categoriasRaw']);        
                    let admin = @json(Auth::guard()->check());


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
            */
        /*
            $(document).ready(function() {
                if (admin) {
                    $('button').prop('disabled', true);
        
                    // Deshabilitar todos los inputs, textareas y selects
                    $('input, textarea, select').prop('disabled', true);
                }
            });
            */
    </script>
@endsection
