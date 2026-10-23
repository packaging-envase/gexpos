<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Disertantes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background: #f5f6f8;
        }

        .disertantes-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .titulo-seccion {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #212529;
        }

        .disertante-card {
            height: 100%;
            background: #fff;
            border: 0;
            border-radius: 16px;
            overflow: hidden;

            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);

            display: flex;
            flex-direction: column;

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .disertante-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .disertante-foto {
            width: 100%;
            height: 280px;
            object-fit: cover;
            display: block;
            background: #e9ecef;
        }

        .disertante-sin-foto {
            width: 100%;
            height: 280px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #e9ecef;
            color: #6c757d;

            font-size: 70px;
        }

        .disertante-body {
            padding: 22px;

            display: flex;
            flex-direction: column;

            flex: 1;
        }

        .disertante-titulo {
            font-size: 15px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .disertante-nombre {
            font-size: 22px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 8px;
        }

        .disertante-especialidad {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 18px;
        }

        .btn-cv {
            width: 100%;
            border-radius: 8px;
            margin-top: auto;
        }

        @media (max-width: 575px) {

            .disertantes-container {
                padding: 25px 15px;
            }

            .titulo-seccion {
                font-size: 26px;
            }

            .disertante-foto,
            .disertante-sin-foto {
                height: 240px;
            }

        }
    </style>

</head>

<body>

    <div class="disertantes-container">

        <h1 class="titulo-seccion">
            Disertantes
        </h1>

        @if ($disertantes->count())

            <div class="row g-4">

                @foreach ($disertantes as $disertante)
                    <div class="col-12 col-sm-6 col-lg-4">

                        <div class="disertante-card">

                            {{-- FOTO --}}
                            @if ($disertante->path_foto && file_exists(public_path($disertante->path_foto)))
                                <img src="{{ asset($disertante->path_foto) }}" alt="{{ $disertante->apellido_nombre }}"
                                    class="disertante-foto">
                            @else
                                <div class="disertante-sin-foto">
                                    <span>👤</span>
                                </div>
                            @endif


                            {{-- CONTENIDO --}}
                            <div class="disertante-body">

                                @if ($disertante->titulo)
                                    <div class="disertante-titulo">
                                        {{ $disertante->titulo }}
                                    </div>
                                @endif


                                <div class="disertante-nombre">
                                    {{ $disertante->apellido_nombre }}
                                </div>


                                @if ($disertante->especialidad)
                                    <div class="disertante-especialidad">
                                        {{ $disertante->especialidad }}
                                    </div>
                                @else
                                    <div class="disertante-especialidad">
                                        &nbsp;
                                    </div>
                                @endif


                                {{-- VER CV --}}
                                <a href="{{ url('/congreso/disertante-cv?id=' . $disertante->id) }}"
                                    class="btn btn-primary btn-cv">
                                    Ver CV
                                </a>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>
        @else
            <div class="alert alert-info">
                No hay disertantes registrados.
            </div>

        @endif

    </div>


    <script>
        function enviarAlturaIframe() {

            setTimeout(function() {

                const body = document.body;
                const html = document.documentElement;

                const altura = Math.max(
                    body.scrollHeight,
                    body.offsetHeight,
                    body.clientHeight,
                    html.scrollHeight,
                    html.offsetHeight,
                    html.clientHeight
                );

                window.parent.postMessage({
                        type: 'iframeHeight',
                        height: altura
                    },
                    '*'
                );

            }, 100);

        }


        // Cuando termina de cargar
        window.addEventListener('load', function() {

            enviarAlturaIframe();

            // Recalcular varias veces porque las imágenes pueden
            // terminar de acomodar el contenido después
            setTimeout(enviarAlturaIframe, 300);
            setTimeout(enviarAlturaIframe, 800);
            setTimeout(enviarAlturaIframe, 1500);

        });


        // Si cambia el ancho de pantalla
        window.addEventListener('resize', function() {
            enviarAlturaIframe();
        });


        // Detectar cambios de tamaño del contenido
        if (typeof ResizeObserver !== 'undefined') {

            const observer = new ResizeObserver(function() {
                enviarAlturaIframe();
            });

            observer.observe(document.body);
        }


        // Detectar cuando terminan de cargar las imágenes
        document.querySelectorAll('img').forEach(function(img) {

            if (!img.complete) {

                img.addEventListener('load', function() {
                    enviarAlturaIframe();
                });

            }

        });
    </script>

</body>

</html>
