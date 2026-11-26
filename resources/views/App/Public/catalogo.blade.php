@extends('layouts.app')

@section('content')
    <style>
        .public-catalogo .catalogo-selected {
            background-color: #009CA7;
            color: white;
        }

        .public-catalogo .catalogo-selected rect {
            fill: white;
        }

        .public-catalogo .catalogo-selected:hover {
            background-color: #00BDCB;
        }

        .public-catalogo .catalogo-no-selected {
            background-color: whitesmoke;
            border: 1px solid lightgray;
        }

        .public-catalogo .catalogo-no-selected rect {
            fill: black;
        }

        .public-catalogo .categorias div {
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 500;
            color: gray;
        }

        .public-catalogo .cuadrada {
            /* O cualquier tamaño fijo o relativo que desees */
            /* aspect-ratio: 1 / 1; */
            object-fit: cover;
            /* Ajusta cómo se escala la imagen dentro del contenedor */
        }

        /* Para pantallas con un ancho mínimo de 900px */
        @media (min-width: 900px) {
            .flex-container {
                display: flex;
                justify-content: space-between;
                /* Justify content for wider screens */
            }
        }

        /* Para pantallas con un ancho máximo de 899px */
        @media (max-width: 899px) {
            .flex-container {
                display: flex;
                justify-content: center;
                /* Justify content for narrower screens */
                flex-wrap: wrap;
                /* Allow items to wrap */
            }
        }

        a {
            color: #008000;
            /* Verde estándar */
            text-decoration: none;
            /* Quita el subrayado */
        }

        a:hover {
            color: #006400;
            /* Verde más oscuro al pasar el mouse */
        }

        a:active {
            color: #00a000;
            /* Verde más claro al hacer clic */
        }

        a:visited {
            color: #007000;
            /* Verde ligeramente distinto para enlaces visitados */
        }
    </style>
    @if (Auth::guard()->check())
        @include('layouts.navigation-menu-2', ['active' => 'catalogo'])
        @include('layouts.messages')
    @endif
    <div id="publicCatalogo" class="container public-catalogo mb-4 {{ !Auth::guard()->check() ? 'mt-3' : '' }} pt-2">
        <!--<div class="bg-white shadow px-3 pt-1">-->
        <div class="bg-white">
            <!--<div class="mt-3 mb-4">-->
            <div class="mb-4 d-flex justify-content-between flex-wrap justify-content-center">

                {{--
                <div class="d-flex mt-2">
                    <a id="btnCatalogo" class="btn semi-circle-border catalogo-selected text-white"
                        style="padding-left: 30px; border-top-left-radius: 50px; border-bottom-left-radius: 50px; border-bottom-right-radius: 0; border-top-right-radius: 0;">
                        <span style="font-size: 12px;">CATALOGO</span>
                    </a>
                    <a id="btnEmpresas" class="btn semi-circle-border catalogo-no-selected"
                        style="padding-right: 30px; border-top-right-radius: 50px; border-bottom-right-radius: 50px; border-bottom-left-radius: 0; border-top-left-radius: 0;">
                        <span style="font-size: 12px;">EMP PARTICIPANTES</span>
                    </a>
                </div>
                --}}

                <?php
                $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                
                $params = [
                    'exposicion' => $viewData['exposicion']['id'],
                    'entidad' => $viewData['entidad'],
                ];
                
                if ($viewData['wp']) {
                    $params['wp'] = true;
                }
                ?>

                <div class="d-flex align-items-center">
                    <form action="{{ $baseUrl }}" method="get" class="d-flex">
                        <input class="form-control rounded-pill me-2" name="search" placeholder="Buscar..."
                            style="width: 250px;" value="{{ $viewData['search'] }}" />
                        <input type="hidden" name="exposicion" value="{{ $viewData['exposicion']['id'] }}">
                        <input type="hidden" name="entidad" value="{{ $viewData['entidad'] }}">
                        @if ($viewData['wp'])
                            <input type="hidden" name="wp" value="true" value="{{ $viewData['search'] }}">
                        @endif
                        <button class="btn btn-light rounded-pill" type="submit">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <div class="d-flex mt-2">
                    <a id="btnCatalogo" class="btn catalogo-selected semi-circle-border text-white"
                        style="padding-left: 30px; border-top-left-radius: 50px; border-bottom-left-radius: 50px; border-bottom-right-radius: 0; border-top-right-radius: 0;">
                        <span>
                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="2" width="3" height="3"></rect>
                                <rect x="8" y="2" width="3" height="3"></rect>
                                <rect x="14" y="2" width="3" height="3"></rect>
                                <rect x="2" y="8" width="3" height="3"></rect>
                                <rect x="8" y="8" width="3" height="3"></rect>
                                <rect x="14" y="8" width="3" height="3"></rect>
                                <rect x="2" y="14" width="3" height="3"></rect>
                                <rect x="8" y="14" width="3" height="3"></rect>
                                <rect x="14" y="14" width="3" height="3"></rect>
                            </svg>
                        </span>
                        <span style="font-size: 12px;">VER GRILLA</span>
                    </a>
                    <a id="btnEmpresas" class="btn catalogo-no-selected semi-circle-border"
                        style="padding-right: 30px; border-top-right-radius: 50px; border-bottom-right-radius: 50px; border-bottom-left-radius: 0; border-top-left-radius: 0;">
                        <span>
                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <rect x="6" y="4" width="12" height="1"></rect>
                                <rect x="6" y="9" width="12" height="1"></rect>
                                <rect x="6" y="14" width="12" height="1"></rect>
                                <rect x="2" y="4" width="2" height="1"></rect>
                                <rect x="2" y="9" width="2" height="1"></rect>
                                <rect x="2" y="14" width="2" height="1"></rect>
                            </svg>
                        </span>
                        <span style="font-size: 12px;">VER LISTADO</span>
                    </a>
                </div>

                <?php
                $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                
                $params = http_build_query([
                    'exposicion' => $viewData['exposicion']['id'],
                    'entidad' => $viewData['entidad'],
                ]);
                
                if ($viewData['wp']) {
                    $params .= '&wp=true';
                }
                
                $url = $baseUrl . '?' . $params;
                ?>
                <div class="d-flex mt-2">
                    <a href="{{ $url . '&orden=alfabetico' }}"
                        class="btn {{ $viewData['orden'] == 'alfabetico' || !$viewData['orden'] ? 'catalogo-selected text-white' : 'catalogo-no-selected text-dark' }} semi-circle-border"
                        style="padding-left: 30px; border-top-left-radius: 50px; border-bottom-left-radius: 50px; border-bottom-right-radius: 0; border-top-right-radius: 0;">
                        Alfabético
                    </a>
                    <a href="{{ $url . '&orden=stand' }}"
                        class="btn {{ $viewData['orden'] == 'stand' ? 'catalogo-selected text-white' : 'catalogo-no-selected text-dark' }} semi-circle-border"
                        style="padding-right: 30px; border-top-right-radius: 50px; border-bottom-right-radius: 50px; border-bottom-left-radius: 0; border-top-left-radius: 0;">
                        Nº Stand
                    </a>
                </div>

                @if (false)
                <a href="https://upersmedia.com/revistas/IAE/catalogo_general_2025/"
                    class="btn catalogo-selected text-white semi-circle-border mt-2 text-center"
                    style="padding-left: 20px; padding-right: 20px; border-radius: 50px;"
                    target="_blank">
                    Catálogo Digital
                </a>
                @endif

                
            </div>


            <div id="mainCatalogo" class="row">
                <h5 class="d-none">Catalogo</h5>
                <?php
                $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                
                $params = http_build_query([
                    'exposicion' => $viewData['exposicion']['id'],
                    'entidad' => $viewData['entidad'],
                ]);
                
                if ($viewData['wp']) {
                    $params .= '&wp=true';
                }
                
                $url = $baseUrl . '?' . $params;
                ?>
                @if ($viewData['entidad'] != 'etif')
                <div class="my-2" style="color: black;">
                    <a style="text-decoration: none;" href="{{ route('public.catalogo', $params) }}">
                        TODO
                    </a>
                </div>
                @endif
                <div class="col-md-3 categorias">
                    <?php
                    $soloEnvase = $params . '&filtro=envase';
                    ?>
                    @if ($viewData['entidad'] != 'etif')
                    <a style="text-decoration: none; color: black;" href="{{ route('public.catalogo', $soloEnvase) }}">
                        {{ isset($viewData['entidad']) && $viewData['entidad'] == 'envasealimentek' ? 'ENVASEALIMENTEK' : 'ENVASE' }}
                    </a>
                    @endif
                    
                    @foreach ($viewData['categorias'] as $item)
                        @if (!$item['etif'])
                            <?php
                            $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                            
                            $params = http_build_query([
                                'exposicion' => $viewData['exposicion']['id'],
                                'categoria' => $item['id'],
                                'entidad' => $viewData['entidad'],
                            ]);
                            
                            if ($viewData['wp']) {
                                $params .= '&wp=true';
                            }
                            
                            $url = $baseUrl . '?' . $params;
                            ?>
                            @if (
                                $item->nombre != 'CONTACTOS VIP' &&
                                    $item->nombre != 'ENVASE' &&
                                    $item->nombre != 'ALIMENTOS' &&
                                    $item->nombre != 'FARMACIA' &&
                                    $item->nombre != 'LABORATORIO' &&
                                    $item->nombre != 'ETIF')
                                <div class="my-2 ms-2">
                                    <a style="text-decoration: none;" href="{{ $url }}">
                                        {{ $item->nombre }}
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endforeach

                    <?php
                    $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                    
                    $params = http_build_query([
                        'exposicion' => $viewData['exposicion']['id'],
                        'entidad' => $viewData['entidad'],
                    ]);
                    
                    if ($viewData['wp']) {
                        $params .= '&wp=true';
                    }
                    
                    $url = $baseUrl . '?' . $params;
                    ?>
                    <?php
                    $soloEtif = $params . '&filtro=etif';
                    ?>
                    @if ($viewData['entidad'] != 'envasealimentek')
                        <a style="text-decoration: none; color: black;" href="{{ route('public.catalogo', $soloEtif) }}">
                            ETIF
                        </a>
                        @foreach ($viewData['categorias'] as $item)
                            @if ($item['etif'])
                                <?php
                                $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                                
                                $params = http_build_query([
                                    'exposicion' => $viewData['exposicion']['id'],
                                    'categoria' => $item['id'],
                                    'entidad' => $viewData['entidad'],
                                ]);
                                
                                if ($viewData['wp']) {
                                    $params .= '&wp=true';
                                }
                                
                                $url = $baseUrl . '?' . $params;
                                ?>
                                @if (
                                    $item->nombre != 'CONTACTOS VIP' &&
                                        $item->nombre != 'ENVASE' &&
                                        $item->nombre != 'ALIMENTOS' &&
                                        $item->nombre != 'FARMACIA' &&
                                        $item->nombre != 'LABORATORIO' &&
                                        $item->nombre != 'ETIF')
                                    <div class="my-2 ms-2">
                                        <a style="text-decoration: none;" href="{{ $url }}">
                                            {{ $item->nombre }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="col-md-9">
                    <select id="selectSubSubro" class="form-control mb-2"
                        style="width: fit-content; margin-left: -0.33rem;">
                        <option value="" selected>Todos los Subrubros</option>
                    </select>
                    <div class="row">
                        @foreach ($viewData['expositores'] as $item)
                            @if ($item['stand'])
                                <div class="col-md-4 px-2 mb-2">
                                    <div class="shadow-sm border bg-white position-relative" style="height: 420px;">
                                        <div class="text-center"
                                            style="position: absolute; top: calc(50% - 85px); left: 50%; transform: translate(-50%, -50%); width: 100%;">
                                            <img src="{{ $item['logo'] ? asset('storage/empresa/logo/' . $item['logo']->path) : asset('images/no-image.jpg') }}"
                                                class="cuadrada" style="max-width: 100%; max-height: 240px;" />
                                        </div>
                                        <div class="position-absolute w-100" style="bottom: 0;">
                                            <h5 class="text-center mt-4"
                                                style="text-transform: uppercase; color: #009CA7;">
                                                {{ $item['stand'] && $item['stand'][0]['nombre_exposicion'] ? $item['empresa'][$item['stand'][0]['nombre_exposicion']] : '' }}
                                            </h5>
                                            <div class="text-center" style="color: gray;">Nº STAND
                                                @foreach ($item['stand'] as $stand)
                                                    @if (!$loop->last)
                                                        {{ $stand['numero'] }} -
                                                    @else
                                                        {{ $stand['numero'] }}
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="mx-auto text-center catalogo-selected px-3 py-2 mb-4 mt-1"
                                                style="width: 80%; border-radius: 50px;">
                                                <a href="{{ route('public.catalogo-expositor', ['id' => $item['id'], 'entidad' => $viewData['entidad']]) }}"
                                                    style="font-weight: 600; text-decoration: none; color: white;">
                                                    Ver Expositor
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div id="mainEmpresas" class="d-none">
                <h5 class="d-none">Grilla</h5>
                <div id="mainCatalogo" class="row">

                    <?php
                    $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                    
                    $params = http_build_query([
                        'exposicion' => $viewData['exposicion']['id'],
                        'entidad' => $viewData['entidad'],
                    ]);
                    
                    if ($viewData['wp']) {
                        $params .= '&wp=true';
                    }
                    
                    $url = $baseUrl . '?' . $params;
                    ?>
                    <div class="my-2" style="color: black;">
                        <a style="text-decoration: none;" href="{{ route('public.catalogo', $params) }}">
                            TODO
                        </a>
                    </div>
                    <div class="col-md-3 categorias">
                        <?php
                        $soloEnvase = $params . '&filtro=envase';
                        ?>
                        <a style="text-decoration: none; color: black;"
                            href="{{ route('public.catalogo', $soloEnvase) }}">
                            {{ $viewData['entidad'] == 'envasealimentek' ? 'ENVASEALIMENTEK' : 'ENVASE' }}
                        </a>
                        @foreach ($viewData['categorias'] as $item)
                            @if (!$item['etif'])
                                <?php
                                $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                                
                                $params = http_build_query([
                                    'exposicion' => $viewData['exposicion']['id'],
                                    'categoria' => $item['id'],
                                    'entidad' => $viewData['entidad'],
                                ]);
                                
                                if ($viewData['wp']) {
                                    $params .= '&wp=true';
                                }
                                
                                $url = $baseUrl . '?' . $params;
                                ?>
                                @if (
                                    $item->nombre != 'CONTACTOS VIP' &&
                                        $item->nombre != 'ENVASE' &&
                                        $item->nombre != 'ALIMENTOS' &&
                                        $item->nombre != 'FARMACIA' &&
                                        $item->nombre != 'LABORATORIO')
                                    <div class="my-2 ms-2">
                                        <a style="text-decoration: none;" href="{{ $url }}">
                                            {{ $item->nombre }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach

                        <?php
                        $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                        
                        $params = http_build_query([
                            'exposicion' => $viewData['exposicion']['id'],
                            'entidad' => $viewData['entidad'],
                        ]);
                        
                        if ($viewData['wp']) {
                            $params .= '&wp=true';
                        }
                        
                        $url = $baseUrl . '?' . $params;
                        ?>
                        <?php
                        $soloEtif = $params . '&filtro=etif';
                        ?>
                        @if ($viewData['entidad'] != 'envasealimentek')
                        <a style="text-decoration: none; color: black;" href="{{ route('public.catalogo', $soloEtif) }}">
                            ETIF
                        </a>
                        @foreach ($viewData['categorias'] as $item)
                            @if ($item['etif'])
                                <?php
                                $baseUrl = route('public.catalogo'); // Obtener la URL base con route()
                                
                                $params = http_build_query([
                                    'exposicion' => $viewData['exposicion']['id'],
                                    'categoria' => $item['id'],
                                    'entidad' => $viewData['entidad'],
                                ]);
                                
                                if ($viewData['wp']) {
                                    $params .= '&wp=true';
                                }
                                
                                $url = $baseUrl . '?' . $params;
                                ?>
                                @if (
                                    $item->nombre != 'CONTACTOS VIP' &&
                                        $item->nombre != 'ENVASE' &&
                                        $item->nombre != 'ALIMENTOS' &&
                                        $item->nombre != 'FARMACIA' &&
                                        $item->nombre != 'LABORATORIO')
                                    <div class="my-2 ms-2">
                                        <a style="text-decoration: none;" href="{{ $url }}">
                                            {{ $item->nombre }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-striped" stlye="font-weight: 600;">
                            <tr class="table-dark">
                                <th>Expositor</th>
                                <th>Stand</th>
                                <th></th>
                            </tr>
                            @foreach ($viewData['expositores'] as $expositor)
                                @if ($expositor['stand'])
                                    <tr>
                                        <td>
                                            {{ $expositor['stand'] && $expositor['stand'][0]['nombre_exposicion'] ? $expositor['empresa'][$expositor['stand'][0]['nombre_exposicion']] : '' }}
                                        </td>
                                        <td>
                                            @foreach ($expositor['stand'] as $stand)
                                                @if (!$loop->last)
                                                    {{ $stand['numero'] }} -
                                                @else
                                                    {{ $stand['numero'] }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('public.catalogo-expositor', ['id' => $expositor['id'], 'entidad' => $viewData['entidad']]) }}">
                                                Ver Expositor
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        if (@json($viewData['wp'])) {
            $('body').attr('style', 'background-color: white !important;');
            $('#navbarMain').hide();
            $('#nav2').hide();
            $('#navExp').hide();
        }

        function enviarAltura() {
            let h = document.getElementById('publicCatalogo')?.scrollHeight;
            if (typeof h === 'number') {
                console.log("Nueva altura enviada:", h);
                window.parent.postMessage(h, '*');
            }
        }

        // Ejecutar al cargar la página
        window.onload = enviarAltura;

        // Detectar cambios en el DOM (por si hay navegación dentro del iframe)
        const observer = new MutationObserver(enviarAltura);
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });


        $(document).ready(function() {
            $('#btnCatalogo').on('click', function() {
                $('#mainCatalogo').removeClass('d-none');
                $('#btnCatalogo').addClass('catalogo-selected');
                $('#btnCatalogo').addClass('text-white');
                $('#btnCatalogo').removeClass('catalogo-no-selected');

                $('#mainEmpresas').addClass('d-none');
                $('#btnEmpresas').addClass('catalogo-no-selected');
                $('#btnEmpresas').removeClass('catalogo-selected');
                $('#btnEmpresas').removeClass('text-white');

            });
            $('#btnEmpresas').on('click', function() {
                $('#mainCatalogo').addClass('d-none');
                $('#btnCatalogo').removeClass('catalogo-selected');
                $('#btnCatalogo').removeClass('text-white');
                $('#btnCatalogo').addClass('catalogo-no-selected');

                $('#mainEmpresas').removeClass('d-none');
                $('#btnEmpresas').removeClass('catalogo-no-selected');
                $('#btnEmpresas').addClass('catalogo-selected');
                $('#btnEmpresas').addClass('text-white');

            });

            var subRubros = @json($viewData['subRubros']);
            var url = new URL(window.location.href);
            var subRubroParam = url.searchParams.get('subRubro');

            if (Array.isArray(subRubros)) {
                subRubros.forEach(function(item) {
                    $('#selectSubSubro').append(
                        $('<option>', {
                            value: item.id,
                            text: item.nombre,
                            selected: subRubroParam == item.id
                        })
                    );
                });
            } else {
                $('#selectSubSubro').append(
                    $('<option>', {
                        value: subRubros.id,
                        text: subRubros.nombre,
                        selected: subRubroParam == subRubros.id
                    })
                );
            }

            if (subRubroParam) {
                $('#selectSubSubro').val(subRubroParam);
            }

            $('#selectSubSubro').on('change', function() {
                var selectedValue = $(this).val();
                
                if (selectedValue) {
                    url.searchParams.set('subRubro', selectedValue);
                } else {
                    url.searchParams.delete('subRubro');
                }

                window.location.href = url.toString();
            });
        });

        //console.log('SUBRUBROS');
        //console.log(@json($viewData['subRubros']));
    </script>
@endsection
