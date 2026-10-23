<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        CV - {{ $disertante->apellido_nombre }}
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6f8;
            margin: 0;
            padding-top: 75px;
        }

        /*
         * HEADER FIJO
         */
        .cv-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;

            height: 75px;

            background: #fff;

            display: flex;
            align-items: center;

            padding: 0 20px;

            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.10);

            z-index: 1000;
        }

        .btn-volver {
            border-radius: 8px;
        }

        /*
         * CONTENIDO
         */
        .cv-container {
            max-width: 900px;
            margin: 0 auto;

            padding: 40px 20px 60px;
        }

        .cv-card {
            background: #fff;

            border-radius: 16px;

            padding: 40px;

            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .cv-foto {
            width: 180px;
            height: 180px;

            object-fit: cover;

            border-radius: 50%;

            display: block;

            margin: 0 auto 25px;
        }

        .cv-sin-foto {
            width: 180px;
            height: 180px;

            border-radius: 50%;

            background: #e9ecef;
            color: #6c757d;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 70px;

            margin: 0 auto 25px;
        }

        .cv-nombre {
            font-size: 32px;
            font-weight: 700;

            text-align: center;

            color: #212529;

            margin-bottom: 10px;
        }

        .cv-titulo {
            font-size: 18px;

            text-align: center;

            color: #6c757d;

            margin-bottom: 35px;
        }

        .cv-label {
            font-size: 15px;
            font-weight: 700;

            color: #495057;

            margin-bottom: 5px;
        }

        .cv-value {
            font-size: 16px;

            color: #212529;

            margin-bottom: 25px;
        }

        .cv-descripcion {
            font-size: 16px;
            line-height: 1.7;

            color: #212529;
        }

        @media (max-width: 575px) {

            .cv-header {
                padding: 0 15px;
            }

            .cv-container {
                padding: 25px 15px 40px;
            }

            .cv-card {
                padding: 25px 20px;
            }

            .cv-nombre {
                font-size: 26px;
            }

            .cv-foto,
            .cv-sin-foto {
                width: 140px;
                height: 140px;
            }

        }
    </style>

</head>

<body>


    {{-- ========================================================= --}}
    {{-- HEADER FIJO --}}
    {{-- ========================================================= --}}

    <div class="cv-header">

        <a href="{{ url('/congreso/disertantes?idCongreso=' . $idCongreso) }}" class="btn btn-primary btn-volver">
            ← Volver
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- CV --}}
    {{-- ========================================================= --}}

    <div class="cv-container">

        <div class="cv-card">


            {{-- FOTO --}}
            @if ($disertante->path_foto && file_exists(public_path($disertante->path_foto)))
                <img src="{{ asset($disertante->path_foto) }}" alt="{{ $disertante->apellido_nombre }}" class="cv-foto">
            @else
                <div class="cv-sin-foto">
                    <span>👤</span>
                </div>
            @endif


            {{-- NOMBRE --}}
            <div class="cv-nombre">

                {{ $disertante->apellido_nombre }}

            </div>


            {{-- TÍTULO --}}
            @if ($disertante->titulo)
                <div class="cv-titulo">

                    {{ $disertante->titulo }}

                </div>
            @endif


            {{-- ESPECIALIDAD --}}
            @if ($disertante->especialidad)
                <div class="cv-label">
                    Especialidad
                </div>

                <div class="cv-value">
                    {{ $disertante->especialidad }}
                </div>
            @endif


            {{-- CARGO --}}
            @if ($disertante->cargo)
                <div class="cv-label">
                    Cargo
                </div>

                <div class="cv-value">
                    {{ $disertante->cargo }}
                </div>
            @endif


            {{-- EMPRESA --}}
            @if ($disertante->empresa)
                <div class="cv-label">
                    Empresa / Institución
                </div>

                <div class="cv-value">
                    {{ $disertante->empresa }}
                </div>
            @endif


            {{-- DESCRIPCIÓN --}}
            @if ($disertante->descripcion)
                <div class="cv-label">
                    CV
                </div>

                <div class="cv-descripcion">
                    {!! nl2br(e($disertante->descripcion)) !!}
                </div>
            @endif


        </div>

    </div>

</body>

</html>
