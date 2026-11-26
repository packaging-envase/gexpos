<style>

</style>

@if (!request()->is('login'))
    <div class="container bg-white">
        <nav id="navbarMain" class="navbar navbar-expand-md border shadow"
            style="background-color: white !important; color: white !important; height: 120px;">

            <!--
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            -->

            <?php
                $idExposicion = Auth::guard('expositor')->check() ? Auth::guard('expositor')->user()->id_exposicion : false;
                if ($idExposicion) {
                    $path = \App\Models\Exposicion::find($idExposicion)['logo_exposicion'];
                } else {
                    $path = \App\Models\Variables::where('descripcion','=','logo_login')->first()['value_string'];
                }
            ?>

            <div>
                @if (request()->routeIs('expositor.list-exposiciones'))
                <div>
                    <img src="{{ asset('storage/envase_navbar.png') }}" class="mx-auto"
                        style="height: 100px;
                        position: absolute;
                        top: 5px;
                        left: calc(50% - 250px);" />
                </div>
                @else
                    <img src="{{ asset('storage/' . $path) }}" class="mx-auto"
                        style="height: 100px;
                        position: absolute;
                        top: 5px;
                        left: calc(50% - 350px);" />
                @endif
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                <!-- Left Side Of Navbar
                    <ul class="navbar-nav me-auto">

                    </ul>
                    -->

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    <li class="nav-item {{ Auth::guard('expositor')->check() || Auth::check() ? 'dropdown' : '' }} d-flex px-2 me-2"
                        style="background-color: #009ca7; border-radius: 20px; max-height: 40px !important;">

                        @if (Auth::guard('expositor')->check() || Auth::check())
                            <span class="" style="margin-top: 5px;">
                                <svg width="20" height="20" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle fill="white" stroke="#000" stroke-width="1.1" cx="9.9"
                                        cy="6.4" r="4.4"></circle>
                                    <path fill="white" stroke="#000" stroke-width="1.1"
                                        d="M1.5,19 C2.3,14.5 5.8,11.2 10,11.2 C14.2,11.2 17.7,14.6 18.5,19.2"></path>
                                </svg>
                            </span>
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                v-pre>
                                {{ Auth::guard('expositor')->check() ? Auth::guard('expositor')->user()->usuario : Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    Salir
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @else
                            <a class="nav-link text-white" href="{{ route('login') }}">
                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                                <span class="ms-2">Iniciar Sesion</span>
                            </a>
                        @endif
                    </li>
                    {{--
                        <li class="nav-item">
                            <div class="d-flex">
                                <a target="blank" href="https://www.facebook.com/ETIFeventosytecnologia">
                                    <span class="icon-navbar">
                                        <i class="fa-brands fa-facebook"></i>
                                    </span>
                                </a>
                                <a target="blank" href="https://twitter.com/etif_pharma">
                                    <span class="icon-navbar">
                                        <i class="fa-brands fa-twitter"></i>
                                    </span>
                                </a>
                                <a target="blank" href="https://www.instagram.com/etif_pharma/">
                                    <span class="icon-navbar">
                                        <i class="fa-brands fa-instagram"></i>
                                    </span>
                                </a>
                                <a target="blank" href="https://www.linkedin.com/company/2670349?goback=">
                                    <span class="icon-navbar">
                                        <i class="fa-brands fa-linkedin"></i>
                                    </span>
                                </a>
                                <a target="blank" href="https://www.youtube.com/user/ETIFLatinoamerica">
                                    <span class="icon-navbar">
                                        <i class="fa-brands fa-youtube"></i>
                                    </span>
                                </a>
                            </div>
                        </li>
                        --}}
                </ul>
            </div>


        </nav>
    </div>
@endif
<style>
    .li-icon {
        width: fit-content;
    }

    .fa-brands {
        padding: 5px;
    }

    .fa-brands:hover {
        color: silver;
        box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        border-radius: 50%;
    }

    .icon-navbar {
        font-size: 26px;
        color: white;
        margin-left: 10px;

    }

    body {
        background-color: white !important;
    }
</style>
