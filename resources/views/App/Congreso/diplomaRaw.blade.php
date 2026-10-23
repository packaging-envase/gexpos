
@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-primary float-end mx-2" href="{{ route('congreso.generar-diplomas-view', ['idCongreso' => $idCongreso]) }}">
                            <- Volver
                        </a>
                        <h3 class="mt-1">
                            {{ isset($diploma) && $diploma ? 'Editar configuración del diploma' : 'Configurar diploma' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('congreso.diploma-raw-post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="idCongreso" value="{{ $idCongreso }}">
                            <input type="hidden" name="diploma_posision_x" id="diplomaPosicionX" value="{{ isset($diploma) && $diploma ? $diploma['posicion_x'] : 0 }}">
                            <input type="hidden" name="diploma_posicion_y" id="diplomaPosicionY" value="{{ isset($diploma) && $diploma ? $diploma['posicion_y'] : 0 }}">

                            <div class="form-group">
                                <label for="diplomaRaw">Imagen del diploma</label>
                                <input type="file" class="form-control-file" id="diplomaRaw" name="diploma_raw" accept=".png,.jpg,.jpeg" {{ isset($diploma) && $diploma ? '' : 'required' }}>
                                @if (isset($diploma) && $diploma)
                                    <small class="form-text text-muted">Si seleccionás una nueva imagen, reemplazará la imagen actual.</small>
                                @endif
                            </div>

                            <div id="diplomaContainer" class="mt-4 {{ isset($diploma) && $diploma ? '' : 'd-none' }}">
                                <div class="alert alert-info">
                                    Arrastrá el nombre para colocarlo en la posición donde quieras que aparezca. La X/Y representan el centro del nombre.
                                </div>

                                <div id="diplomaPreviewWrapper">
                                    <img id="diplomaPreview" src="{{ isset($diploma) && $diploma ? $diploma['url'] : '' }}" alt="Diploma">
                                    <div id="nombrePreview">
                                        <span class="nombreTexto">NOMBRE DE EJEMPLO</span>
                                        <span class="centroVertical"></span>
                                        <span class="centroHorizontal"></span>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="posicionXInput">X</label>
                                            <input type="number" class="form-control" id="posicionXInput" value="{{ isset($diploma) && $diploma ? $diploma['posicion_x'] : 0 }}" step="1">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="posicionYInput">Y</label>
                                            <input type="number" class="form-control" id="posicionYInput" value="{{ isset($diploma) && $diploma ? $diploma['posicion_y'] : 0 }}" step="1">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fontSizeInput">Tamaño de fuente (px)</label>
                                            <input type="number" class="form-control" id="fontSizeInput" name="diploma_font_size" value="{{ isset($diploma) && $diploma ? ($diploma['font_size'] ?? 60) : 60 }}" min="1" max="500" step="1" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary" id="btnGuardar" {{ isset($diploma) && $diploma ? '' : 'disabled' }}>
                                    {{ isset($diploma) && $diploma ? 'Guardar cambios' : 'Guardar configuración' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @font-face {
            font-family: 'Great Vibes';
            src: url('{{ route('congreso.diploma-font') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        #diplomaPreviewWrapper {
            position: relative;
            display: inline-block;
            max-width: 100%;
            line-height: 0;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        #diplomaPreview {
            display: block;
            max-width: 100%;
            height: auto;
        }

        #nombrePreview {
            position: absolute;
            left: 0;
            top: 0;
            transform: translate(-50%, -50%);
            line-height: normal;
            cursor: move;
            user-select: none;
            white-space: nowrap;
            font-family: 'Great Vibes', cursive;
            font-size: 60px;
            font-weight: normal;
            padding: 0;
            color: #000;
        }

        #nombrePreview .nombreTexto {
            display: block;
            line-height: 1;
            background: rgba(255, 255, 255, 0.75);
            border: 1px dashed #000;
            padding: 5px 10px;
        }

        #nombrePreview .centroVertical,
        #nombrePreview .centroHorizontal {
            position: absolute;
            pointer-events: none;
        }

        #nombrePreview .centroVertical {
            left: 50%;
            top: 0;
            width: 1px;
            height: 100%;
            background: red;
            transform: translateX(-50%);
        }

        #nombrePreview .centroHorizontal {
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background: red;
            transform: translateY(-50%);
        }
    </style>

    <script>
        const posicionInicialX = {{ isset($diploma) && $diploma ? (float) $diploma['posicion_x'] : 0 }};
        const posicionInicialY = {{ isset($diploma) && $diploma ? (float) $diploma['posicion_y'] : 0 }};

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('diplomaRaw');
            const container = document.getElementById('diplomaContainer');
            const preview = document.getElementById('diplomaPreview');
            const nombre = document.getElementById('nombrePreview');
            const btnGuardar = document.getElementById('btnGuardar');
            const inputX = document.getElementById('diplomaPosicionX');
            const inputY = document.getElementById('diplomaPosicionY');
            const posicionXInput = document.getElementById('posicionXInput');
            const posicionYInput = document.getElementById('posicionYInput');
            const fontSizeInput = document.getElementById('fontSizeInput');

            let dragging = false;
            let offsetX = 0;
            let offsetY = 0;

            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.onload = function() {
                        container.classList.remove('d-none');
                        btnGuardar.disabled = false;
                        document.fonts.ready.then(function() {
                            actualizarEscalaFuente();
                            moverNombre(0, 0);
                        });
                    };
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });

            preview.addEventListener('load', function() {
                if (!preview.naturalWidth || !preview.naturalHeight) return;

                container.classList.remove('d-none');
                btnGuardar.disabled = false;

                document.fonts.ready.then(function() {
                    actualizarEscalaFuente();
                    moverNombre(posicionInicialX, posicionInicialY);
                });
            });

            nombre.addEventListener('mousedown', function(event) {
                dragging = true;
                const rect = nombre.getBoundingClientRect();
                offsetX = event.clientX - (rect.left + rect.width / 2);
                offsetY = event.clientY - (rect.top + rect.height / 2);
                event.preventDefault();
            });

            document.addEventListener('mousemove', function(event) {
                if (!dragging) return;

                const wrapperRect = document.getElementById('diplomaPreviewWrapper').getBoundingClientRect();
                const centerX = event.clientX - wrapperRect.left - offsetX;
                const centerY = event.clientY - wrapperRect.top - offsetY;
                const scaleX = preview.naturalWidth / preview.clientWidth;
                const scaleY = preview.naturalHeight / preview.clientHeight;

                let x = centerX * scaleX;
                let y = centerY * scaleY;

                x = Math.max(0, Math.min(x, preview.naturalWidth));
                y = Math.max(0, Math.min(y, preview.naturalHeight));

                moverNombre(x, y);
            });

            document.addEventListener('mouseup', function() {
                dragging = false;
            });

            posicionXInput.addEventListener('input', function() {
                const x = parseFloat(this.value) || 0;
                moverNombre(x, parseFloat(posicionYInput.value) || 0);
            });

            posicionYInput.addEventListener('input', function() {
                const y = parseFloat(this.value) || 0;
                moverNombre(parseFloat(posicionXInput.value) || 0, y);
            });

            fontSizeInput.addEventListener('input', function() {
                actualizarEscalaFuente();
            });

            window.addEventListener('resize', function() {
                if (!preview.naturalWidth || !preview.naturalHeight) return;
                actualizarEscalaFuente();
                moverNombre(parseFloat(inputX.value) || 0, parseFloat(inputY.value) || 0);
            });

            function actualizarEscalaFuente() {
                if (!preview.naturalWidth || !preview.clientWidth) return;

                const escala = preview.clientWidth / preview.naturalWidth;
                const fontSize = Math.max(1, parseFloat(fontSizeInput.value) || 60);

                nombre.style.fontSize = (fontSize * escala) + 'px';
            }

            function moverNombre(x, y) {
                if (!preview.naturalWidth || !preview.naturalHeight) return;

                x = Math.max(0, Math.min(x, preview.naturalWidth));
                y = Math.max(0, Math.min(y, preview.naturalHeight));

                const scaleX = preview.clientWidth / preview.naturalWidth;
                const scaleY = preview.clientHeight / preview.naturalHeight;

                nombre.style.left = (x * scaleX) + 'px';
                nombre.style.top = (y * scaleY) + 'px';

                inputX.value = x;
                inputY.value = y;
                posicionXInput.value = Math.round(x);
                posicionYInput.value = Math.round(y);
            }
        });
    </script>
@endsection