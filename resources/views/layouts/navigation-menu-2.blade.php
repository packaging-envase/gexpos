<style>
    .navbar-nav .nav-link {}

    .dropend .dropdown-toggle {}

    .dropdown-item:hover {}

    .dropdown .dropdown-menu {
        display: none;
    }

    .dropdown:hover>.dropdown-menu,
    .dropend:hover>.dropdown-menu {
        display: block;
        margin-top: .125em;
    }

    @media screen and (min-width:769px) {
        .dropend:hover>.dropdown-menu {
            position: absolute;
            top: 0;
            left: 100%;
        }

        .dropend .dropdown-toggle {}
    }
</style>
<div id="nav2" class="container py-4 bg-white">
    @if (isset($viewData['exposicion']))
    <h3 class="mx-auto m-0 border bg-white p-2 mb-2" style="width: fit-content">{{ $viewData['exposicion']['nombre'] }}</h3>        
    @endif
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow border">
        <div class="container-fluid">
            <!--<a class="navbar-brand" href="#">Navbar</a>-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if (Auth::guard()->user()->grupo == '3')
                        <li class="nav-item">
                            <a class="nav-link {{ $active == 'catalogo' ? 'active' : '' }}"
                                href="{{ route('categoria.panel', ['id' => isset($viewData['exposicion']) && $viewData['exposicion'] ? $viewData['exposicion']['id'] : 0]) }}">Rubros</a>
                        </li>
                    @endif
                    @if (Auth::guard()->user()->grupo == '3')
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('empresa.panel', ['id' => isset($viewData['exposicion']) ? $viewData['exposicion']['id'] : '0']) }}">
                                Empresas
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard()->user()->grupo == '3')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('congreso.panel') }}">
                                Congresos
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard()->user()->grupo == '1' ||
                            Auth::guard()->user()->grupo == '2' ||
                            Auth::guard()->user()->grupo == '4' ||
                            Auth::guard()->user()->grupo == '5')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $active == 'empresa' ? 'active' : '' }}"
                                href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Principal
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="margin-top: -5px;">

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('congreso.panel') }}">
                                        Congresos
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('exposicion.panel') }}">
                                        Exposiciones
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('stand-tipo.index') }}">
                                        Tipo Stand
                                    </a>
                                </li>

                                @if (Auth::guard()->user()->grupo == '1')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('empresa.panel') }}">
                                            Empresas
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $active == 'catalogo' ? 'active' : '' }}"
                                            href="{{ route('categoria.panel') }}">Rubros</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $active == 'catalogo' ? 'active' : '' }}"
                                            href="{{ route('credencial.panel') }}">Credenciales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $active == 'configuracion' ? 'active' : '' }}"
                                            href="{{ route('configuracion.index') }}">Configuración</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (isset($viewData['exposicion']) && $viewData['exposicion'])
                        @if (Auth::guard()->user()->grupo == '1')
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('exposicion.create', ['id' => $viewData['exposicion']['id']]) }}">Configuración</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('exposicion.create-2', ['id' => $viewData['exposicion']['id']]) }}">Planimetria</a>
                            </li>
                        @endif
                        @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '3' || Auth::guard()->user()->grupo == '4')
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('expositor.panel-administracion', ['id' => $viewData['exposicion']['id']]) }}">Expositores</a>
                            </li>
                        @endif
                        @if (Auth::guard()->user()->grupo == '1' ||
                                Auth::guard()->user()->grupo == '3' ||
                                Auth::guard()->user()->grupo == '2' ||
                                Auth::guard()->user()->grupo == '4')
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('exposicion.create-3', ['id' => $viewData['exposicion']['id']]) }}">
                                    Asignacion Stands
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard()->user()->grupo == '1')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Config. Modulos
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_catalogo'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.catalogo.create', ['id' => $viewData['exposicion']['id']]) }}">
                                                Catálogo
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_plano_stand'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.plano-stand.create', ['id' => $viewData['exposicion']['id']]) }}">
                                                Planos de stand
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_elementos_opcionales'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.elementos-opcionales.panel', ['id' => $viewData['exposicion']['id']]) }}">
                                                Elementos Opcionales
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_solicitar_energia'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.electricidad.create', ['id' => $viewData['exposicion']['id']]) }}">
                                                Energía extra
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_deposito_garantia'] == false ? 'd-none' : '' }}"
                                                href="{{route('modulo.deposito-garantia.create', ['id' => $viewData['exposicion']['id']])}}">
                                                Depósito en garantía
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_credenciales'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.credencial.panel', ['id' => $viewData['exposicion']['id']]) }}">
                                                Credenciales
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_invitaciones'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.invitacion.create', ['id' => $viewData['exposicion']['id']]) }}">
                                                Invitaciones
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <li>
                                            <a class="dropdown-item {{ $viewData['exposicion']['modulo_sala_conferencia'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.sala-conferencia.panel', ['id' => $viewData['exposicion']['id']]) }}">
                                                Sala de Conferencia
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <li>
                                            <a class="dropdown-item asd {{ $viewData['exposicion']['modulo_servicios_optativos'] == false ? 'd-none' : '' }}"
                                                href="{{ route('modulo.servicios-optativos.panel', ['id' => $viewData['exposicion']['id']]) }}">
                                                Proveedores Optativos
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Requer. Modulos
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_catalogo'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.catalogo.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Catálogo
                                        </a>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_elementos_opcionales'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.elementos-opcionales.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Elementos Opcionales
                                        </a>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_solicitar_energia'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.electricidad.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Energía Extra
                                        </a>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_deposito_garantia'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.deposito-garantia.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Depósito de Garantía
                                        </a>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_credenciales'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.credencial.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Credenciales
                                        </a>
                                        <!--
                                        <span class="dropdown-item" style="color: orange;">
                                            Credenciales
                                        </span>
                                        -->
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_invitaciones'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.invitacion.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Invitaciones
                                        </a>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_sala_conferencia'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.sala-conferencia.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Sala de Conferencia
                                        </a>
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
                                    {{--
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_plano_stand'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.plano-stand.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Planos de Stand
                                        </a>
                                    --}}
                                    @endif
                                    @if (Auth::guard()->user()->grupo == '2')
                                        <a class="dropdown-item {{ $viewData['exposicion']['modulo_plano_stand'] == false ? 'd-none' : '' }}"
                                            href="{{ route('modulo.plano-stand.panel-requerimientos', ['id' => $viewData['exposicion']['id']]) }}">
                                            Planos de Stand
                                        </a>
                                    @endif
                                    <a class="dropdown-item {{ $viewData['exposicion']['modulo_plano_stand'] == false ? 'd-none' : '' }}"
                                        href="{{ route('modulo.panel-control', ['id' => $viewData['exposicion']['id']]) }}">
                                        Panel de Control
                                    </a>
                                </ul>
                            </li>
                        @endif
                        @if (Auth::guard()->user()->grupo == '1')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Comunicacion
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('modulo.notificacion.panel', ['id' => $viewData['exposicion']['id']]) }}">
                                            Notificaciones
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('whatsapp.update-store-predefinido-view', ['id' => $viewData['exposicion']['id']]) }}">
                                            Whatsapp
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('email.update-store-predefinido-view', ['id' => $viewData['exposicion']['id']]) }}">
                                            Email
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '5')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Prensa
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('modulo.prensa.create', ['id' => $viewData['exposicion']['id']]) }}">
                                            Crear Nueva
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('modulo.prensa.listado', ['id' => $viewData['exposicion']['id']]) }}">
                                            Listado
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('modulo.prensa.acreditacion-panel', ['id' => $viewData['exposicion']['id']]) }}">
                                            Acreditaciones
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (Auth::guard()->user()->grupo == '1')
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('novedades.panel-empresa', ['id' => $viewData['exposicion']['id']]) }}">
                                    Novedades
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
                @if (isset($viewData['exposicion']) && $viewData['exposicion'])
                    {{-- <h4 class="float-end m-0"><b>{{ $viewData['exposicion']['nombre'] }}</b></h4> --}}
                @endif
            </div>
        </div>
    </nav>
</div>
