@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            @if (isset($viewData['exposicion']))
                <h2 class="mb-3">
                    <u>Configuración</u>
                </h2>
            @else
                <h2 class="mb-3">
                    <u>Nueva Exposición - Configuración</u>
                </h2>
            @endif

            @include('layouts.messages')
            <form method="POST" action="{{ route('exposicion.store') }}" enctype="multipart/form-data"
                onsubmit="return validarFormulario()">
                @csrf
                @if (isset($viewData['exposicion']))
                    <input hidden name="id" value="{{ $viewData['exposicion']['id'] }}" />
                @endif

                <div class="row">
                    <div class="col-12 mt-2">
                        <h5><b>Nombre</b></h5>
                        <input class="form-control" style="width:250px;" name="nombre"
                            value="{{ isset($viewData['exposicion']) ? $viewData['exposicion']['nombre'] : '' }}" />
                        <small class="position-relative" style="bottom: 0;">Que verán los expositores cuando
                            ingresen.</small>
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="mb-3">
                        <label for="logo_exposicion" class="form-label">Logo encabezado exposición (imagen tipo banner horizontal proporción aproximada 5:1) transparente</label>

                        <input type="file" name="logo_exposicion" id="logo_exposicion" class="form-control"
                            accept="image/*">

                        @if (!empty($viewData['exposicion']['logo_exposicion']))
                            <div class="mt-2">
                                <p class="mb-1">Logo actual:</p>
                                <img src="{{ asset('storage/' . $viewData['exposicion']['logo_exposicion']) }}" 
                                    alt="Logo exposición"
                                    style="max-height: 120px;" />
                            </div>
                        @endif

                        <label>Descripción de contactos en los emails enviados:</label><br/>
                        <textarea name="contacto_emails" style="width: 100%; resize: none;">{{!empty($viewData['exposicion']['contacto_emails']) ? $viewData['exposicion']['contacto_emails'] : ''}}</textarea>
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <h5><b>Mapa Exposición</b></h5>
                        <input id="inputFileMapa" type="file" hidden name="fileMapa"
                            onchange="$('#labelMapaCargado').text($(this).val().split('\\').pop()).removeClass('d-none');" />
                        <button type="button" class="btn btn-success" onclick="$('#inputFileMapa').click()">Subir
                            Imagen</button>
                        <small id="labelMapaCargado" class="d-none"></small>
                        @if (isset($viewData['exposicion']) && $viewData['exposicion']['url_mapa'])
                            <br />
                            <img src="{{ $viewData['exposicion']['url_mapa'] }}" width="500" />
                        @endif
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <h5><b>Modulos a Incorporar</b></h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault1"
                                name="modulo_catalogo"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_catalogo'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault1">Catálogo</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault2"
                                name="modulo_plano_stand"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_plano_stand'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault2">Planos de stand</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault3"
                                name="modulo_elementos_opcionales"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_elementos_opcionales'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault3">Elementos opcionales</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault4"
                                name="modulo_deposito_garantia"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_deposito_garantia'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault4">Depósito en garantía</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault5"
                                name="modulo_credenciales"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_credenciales'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault5">Credenciales</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault6"
                                name="modulo_invitaciones"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_invitaciones'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault6">Invitaciones</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault7"
                                name="modulo_sala_conferencia"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_sala_conferencia'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault7">Sala de Conferencia</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault8"
                                name="modulo_solicitar_energia"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_solicitar_energia'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault8">Energía extra</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault9"
                                name="modulo_servicios_optativos"
                                {{ isset($viewData['exposicion']) && $viewData['exposicion']['modulo_servicios_optativos'] == true ? 'checked=true' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault9">Proveedores Optativos</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12 col-md-3">
                        <h5><b>Fecha Inicio</b></h5>
                        <input class="form-control" type="date" name="fecha_inicio"
                            value="{{ isset($viewData['exposicion']) ? $viewData['exposicion']['fecha_inicio'] : '' }}" />
                        <small>A partir de la cual los expositores podrán administrar sus datos.</small>
                    </div>
                    <div class="col-12 col-md-3">
                        <h5><b>Fecha Fin</b></h5>
                        <input class="form-control" type="date" name="fecha_fin"
                            value="{{ isset($viewData['exposicion']) ? $viewData['exposicion']['fecha_fin'] : '' }}" />
                        <small>A partir de la cual los expositores no podrán ingresar más a esta expo.</small>
                    </div>
                    <!--
                            <div class="col-12">
                                <hr/>
                            </div>
                            
                            <div class="col-12">
                                <h5><b>Email para Expositores</b></h5>
                                <textarea id="mytextarea" name="email_expositor">{{ isset($viewData['exposicion']) ? $viewData['exposicion']['email_expositor'] : '' }}</textarea>
                                <script type="text/javascript">
                                    tinymce.init({
                                        selector: '#mytextarea',
                                        license_key: 'gpl'
                                    });
                                </script>
                            </div>
                        -->
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <h5><b>E-mail administrador GExpo:</b></h5>
                        <input class="form-control" style="width:250px;" name="email_administrador_gexpo"
                            value="{{ isset($viewData['exposicion']) ? $viewData['exposicion']['email_administrador_gexpo'] : '' }}" />
                        <small>E-mail de la persona que recibirá la notificación cuando un stand sea asociado a una
                            empresa.</small>
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <h5><b>Documento de Términos y Condiciones</b></h5>
                        <input class="form-control" type="file" style="width:400px;" name="terminos_condiciones" />
                        @if ($viewData['exposicion']['path_terminos_condiciones'])
                            <div>
                                <a href="{{ asset($viewData['exposicion']['path_terminos_condiciones']) }}"
                                    target="_blank">
                                    Ver Términos y Condiciones
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        <h5><b>Documento de Terms & Conditions</b></h5>
                        <input class="form-control" type="file" style="width:400px;" name="terms_conditions" />
                        @if ($viewData['exposicion']['path_terms_conditions'])
                            <div>
                                <a href="{{ asset($viewData['exposicion']['path_terms_conditions']) }}" target="_blank">
                                    Ver Terms & Conditions
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <h5><b>Otros Documentos</b></h5>
                        <div class="d-flex">
                            <div class="mb-3" style="width: 250px;">
                                <label for="tipo" class="form-label">Nombre</label>
                                <input type="text" name="exposicion_archivo_tipo" id="tipo"
                                    class="form-control">
                            </div>
                            <div class="d-flex mb-3">
                                <div class="form-check ms-2" style="margin-top: 37px;">
                                    <input class="form-check-input" type="radio" name="exposicion_archivo_pertence"
                                        id="radioExposicionArchivo1" value="1" checked>
                                    <label class="form-check-label" for="radioExposicionArchivo1">
                                        Envase
                                    </label>
                                </div>
                                <div class="form-check ms-2" style="margin-top: 37px;">
                                    <input class="form-check-input" type="radio" name="exposicion_archivo_pertence"
                                        id="radioExposicionArchivo2" value="0">
                                    <label class="form-check-label" for="radioExposicionArchivo2">
                                        ETIF
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 ms-3" style="margin-top: 37px;">
                                <label for="archivo_importante">Importante</label>
                                <input type="checkbox" name="archivo_importante" id="archivo_importante" />
                            </div>
                            <div class="mb-3 ms-3">
                                <label for="archivo_nivel" class="form-label">Nivel</label>
                                <input type="number" name="archivo_nivel" id="archivo_nivel" class="form-control"
                                    style="width: 60px;" value="99" />
                            </div>
                            <div class="mb-3 ms-3">
                                <label for="archivo" class="form-label">Seleccionar archivo</label>
                                <input type="file" name="exposicion_archivo" id="archivo" class="form-control">
                            </div>
                        </div>
                        @if (isset($viewData['exposicion']) && $viewData['exposicion']->exposicionArchivo->isNotEmpty())
                            <ul>
                                @foreach ($viewData['exposicion']->exposicionArchivo as $archivo)
                                    @if ($archivo['clase'] == 'importante')
                                        <li>
                                            <a href="{{ route('exposicion.delete-documento-importante', ['id' => $archivo['id']]) }}"
                                                style="text-decoration: none;"
                                                onclick="return confirm('¿Seguro que desea eliminar este documento importante? Esta acción es irreversible')">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                                    viewBox="0 0 24 24" style="width: 25px;">
                                                    <path fill="red"
                                                        d="M 10 2 L 9 3 L 4 3 L 4 5 L 5 5 L 5 20 C 5 20.522222 5.1913289 21.05461 5.5683594 21.431641 C 5.9453899 21.808671 6.4777778 22 7 22 L 17 22 C 17.522222 22 18.05461 21.808671 18.431641 21.431641 C 18.808671 21.05461 19 20.522222 19 20 L 19 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 7 5 L 17 5 L 17 20 L 7 20 L 7 5 z M 9 7 L 9 18 L 11 18 L 11 7 L 9 7 z M 13 7 L 13 18 L 15 18 L 15 7 L 13 7 z">
                                                    </path>
                                                </svg>
                                            </a>
                                            ({{ $archivo->pertenece_envase ? 'envase' : 'ETIF' }})
                                            @if ($archivo['importante'])
                                                <i class="fa fa-star" style="color: #ffc107;" aria-hidden="true"></i>
                                            @endif
                                            {{ $archivo->nivel }}
                                            <a href="{{ asset($archivo->path) }}" target="_blank">
                                                {{ $archivo->tipo }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p>No hay archivos disponibles.</p>
                        @endif

                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <h5><b>Material de Difusion</b></h5>
                        <div class="d-flex">
                            <div class="mb-3" style="width: 250px;">
                                <label for="tipo" class="form-label">Nombre</label>
                                <input type="text" name="exposicion_archivo_tipo_muestra" id="tipo"
                                    class="form-control">
                            </div>
                            <div class="d-flex mb-3">
                                <div class="form-check ms-2" style="margin-top: 37px;">
                                    <input class="form-check-input" type="radio" name="difusion_pertence"
                                        id="flexRadioDefault1" value="1" checked>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Envase
                                    </label>
                                </div>
                                <div class="form-check ms-2" style="margin-top: 37px;">
                                    <input class="form-check-input" type="radio" name="difusion_pertence"
                                        id="flexRadioDefault2" value="0">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        ETIF
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 ms-3">
                                <label for="archivo" class="form-label">Seleccionar archivo difusión muestra</label>
                                <input type="file" name="exposicion_archivo_difusion_muestra" id="archivo"
                                    class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3 ms-3">
                                <label for="archivo" class="form-label">Seleccionar archivo difusión</label>
                                <input type="file" name="exposicion_archivo_difusion" id="archivo"
                                    class="form-control">
                            </div>
                        </div>
                        @if (isset($viewData['exposicion']) && $viewData['exposicion']->exposicionArchivo->isNotEmpty())
                            <ul>
                                @foreach ($viewData['exposicion']->exposicionArchivo as $archivo)
                                    @if ($archivo['clase'] == 'difusion')
                                        <li>
                                            <a href="{{ route('exposicion.delete-documento-importante', ['id' => $archivo['id']]) }}"
                                                style="text-decoration: none;"
                                                onclick="return confirm('¿Seguro que desea eliminar este documento importante? Esta acción es irreversible')">
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                                    viewBox="0 0 24 24" style="width: 25px;">
                                                    <path fill="red"
                                                        d="M 10 2 L 9 3 L 4 3 L 4 5 L 5 5 L 5 20 C 5 20.522222 5.1913289 21.05461 5.5683594 21.431641 C 5.9453899 21.808671 6.4777778 22 7 22 L 17 22 C 17.522222 22 18.05461 21.808671 18.431641 21.431641 C 18.808671 21.05461 19 20.522222 19 20 L 19 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 7 5 L 17 5 L 17 20 L 7 20 L 7 5 z M 9 7 L 9 18 L 11 18 L 11 7 L 9 7 z M 13 7 L 13 18 L 15 18 L 15 7 L 13 7 z">
                                                    </path>
                                                </svg>
                                            </a>
                                            ({{ $archivo->pertenece_envase ? 'envase' : 'ETIF' }})
                                            <a href="{{ asset($archivo->path) }}" target="_blank">
                                                {{ $archivo->tipo }}
                                            </a>
                                            <br />
                                            <img src="{{ asset($archivo->path_muestra) }}" style="max-width: 100%;" />
                                            <div style="margin-bottom: 30px; border-bottom: 1px black solid"></div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p>No hay archivos disponibles.</p>
                        @endif

                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <hr />
                    </div>
                    <!--
                                <div class="col-12">
                                    <label>Proveedores Optativos</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault7">
                                        <label class="form-check-label" for="flexSwitchCheckDefault7">TV - PANTALLAS</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault8">
                                        <label class="form-check-label" for="flexSwitchCheckDefault8">ALQUILER DE MUEBLES</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault9">
                                        <label class="form-check-label" for="flexSwitchCheckDefault9">ALQUILER DE MUEBLES</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault10">
                                        <label class="form-check-label" for="flexSwitchCheckDefault10">COMPRESORES</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault11">
                                        <label class="form-check-label" for="flexSwitchCheckDefault11">GRUAS</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault12">
                                        <label class="form-check-label" for="flexSwitchCheckDefault12">PLANTAS. ARREGLOS.</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault13">
                                        <label class="form-check-label" for="flexSwitchCheckDefault13">SERVICIO DE INTERNET - WIFI</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault14">
                                        <label class="form-check-label" for="flexSwitchCheckDefault14">SEGURIDAD</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault15">
                                        <label class="form-check-label" for="flexSwitchCheckDefault15">LECTORES OPTICOS</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault16">
                                        <label class="form-check-label" for="flexSwitchCheckDefault16">LECTOR QR / LEAD INFO</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault17">
                                        <label class="form-check-label" for="flexSwitchCheckDefault17">PROMOTORAS</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault18">
                                        <label class="form-check-label" for="flexSwitchCheckDefault18">SEGUROS</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault19">
                                        <label class="form-check-label" for="flexSwitchCheckDefault19">LIMPIEZA</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault20">
                                        <label class="form-check-label" for="flexSwitchCheckDefault20">GRAFICA</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault21">
                                        <label class="form-check-label" for="flexSwitchCheckDefault21">CATERING</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault22">
                                        <label class="form-check-label" for="flexSwitchCheckDefault22">DISPENSERS DE AGUA</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault23">
                                        <label class="form-check-label" for="flexSwitchCheckDefault23">Tessting Servicio</label>
                                    </div>
                                </div>
                                -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-success float-end">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function validarFormulario() {
            let tipo = document.getElementById("tipo");
            let archivo = document.getElementById("archivo");

            if (tipo.value.trim() !== "" && archivo.files.length === 0) {
                alert("Por favor, seleccione un archivo o elimine el nombre.");
                return false;
            }

            if (archivo.files.length > 0 && tipo.value.trim() === "") {
                tipo.value = archivo.files[0].name; // Asigna el nombre del archivo si el campo está vacío
            }

            return true;
        }
    </script>
@endsection
