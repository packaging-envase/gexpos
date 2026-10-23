@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">


            <h2 class="mb-3">
                <u>Modulo - Prensa - Crear Nueva</u>
            </h2>

            @include('layouts.messages')

            <form method="POST" action="{{ route('modulo.prensa.update-store') }}" enctype="multipart/form-data">
                @csrf

                <input hidden name="id_exposicion" value="{{ $viewData['exposicion']['id'] }}" />

                @if ($viewData['prensa'])
                    <input hidden name="id" value="{{$viewData['prensa']['id']}}" />
                @endif

                <label>Titulo</label>
                <input class="form-control" name="titulo" required
                    value="{{ $viewData['prensa'] ? $viewData['prensa']['titulo'] : '' }}" />

                <label class="mt-3">Texto</label>
                <textarea id="mytextarea" name="texto" style="resize: none !important;"></textarea>
                <script type="text/javascript">
                    const prensa = @json($viewData['prensa']);
                    tinymce.init({
                        selector: '#mytextarea',
                        license_key: 'gpl',
                        resize: false,
                        toolbar: 'undo redo | bold italic | link | bullist numlist', // Agregar el botón de link
                        plugins: 'link', // Asegúrate de incluir el plugin de link
                        setup: function(editor) {
                            editor.on('init', function() {
                                editor.setContent(prensa ? prensa.texto : '');
                            });
                        }
                    });
                </script>


                <label class="mt-3">Selecciona hasta 10 imágenes:</label>
                <input class="form-control" type="file" id="images" name="imagenes[]" accept="image/*" multiple
                    max="10">

                @if ($viewData['prensa'])
                    <div class="row">
                        @foreach ($viewData['prensa']['moduloImagen'] as $key => $imagen)
                            <div class="col-2">
                                <a href="{{ asset('storage/prensa/imagen/' . $imagen['path']) }}" target="_blank">
                                    Imagen {{ $imagen['id'] }}
                                </a>
                                <a class="text-danger ms-2"
                                    href="{{ route('modulo.prensa.delete-image', ['id' => $imagen['id']]) }}"
                                    onclick="return confirm('Esta acción será irreversible, ¿Continuar?');">
                                    Eliminar
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <label class="mt-3">Datos de Contacto</label>
                <textarea id="mytextarea2" name="contacto" style="resize: none !important;"></textarea>
                <script type="text/javascript">
                    tinymce.init({
                        selector: '#mytextarea2',
                        license_key: 'gpl',
                        resize: false,
                        toolbar: 'undo redo | bold italic | link | bullist numlist', // Agregar el botón de link
                        plugins: 'link', // Asegúrate de incluir el plugin de link
                        setup: function(editor) {
                            editor.on('init', function() {
                                editor.setContent(prensa ? prensa.contacto : '');
                            });
                        }
                    });
                </script>


                <label class="mt-3">Archivo PDF/Word descargable</label>
                <input class="form-control" type="file" name="archivo" />
                @if ($viewData['prensa'] && $viewData['prensa']['moduloArchivo'])
                    <a href="{{ asset('storage/prensa/archivo/' . $viewData['prensa']['moduloArchivo']['path']) }}"
                        target="_blank">
                        Ver Archivo
                    </a>
                    <a class="text-danger ms-2"
                        href="{{ route('modulo.prensa.delete-archivo', ['id' => $viewData['prensa']['moduloArchivo']]) }}"
                        onclick="return confirm('Esta acción será irreversible, ¿Continuar?');">
                        Eliminar
                    </a>
                @endif

                <button type="submit" class="btn btn-success float-end mt-2">Aceptar</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>


@endsection
