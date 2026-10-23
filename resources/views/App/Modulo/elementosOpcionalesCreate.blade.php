@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            <a class="btn btn-primary float-end" href="{{route('modulo.elementos-opcionales.create', ['id' => $viewData['exposicion']['id']])}}">
                Listado
            </a>
            <h2 class="mb-3">
                <u>Modulo - Elementos Opcionales - Nuevo</u>
            </h2>

            @include('layouts.messages')

            <form method="POST" action="{{route('modulo.elementos-opcionales.update-store')}}">
                @csrf

                <input hidden value="{{$viewData['exposicion']['id']}}" name="id_exposicion" />

                @if ($viewData['elemento'])
                    <input hidden name="idElemento" value="{{$viewData['elemento']['id']}}" />
                @endif

                <label>Titulo</label>
                <input class="form-control" name="titulo" value="{{$viewData['elemento'] ? $viewData['elemento']['titulo'] : ''}}" required/>

                <label class="mt-2">Descripción</label>
                <textarea id="mytextarea" name="descripcion" required>
                    @if ($viewData['elemento'])
                        {{$viewData['elemento']['descripcion']}}
                    @else
                        
                    @endif
                </textarea>
                <script type="text/javascript">
                    tinymce.init({
                        selector: '#mytextarea',
                        license_key: 'gpl'
                    });
                </script>

                <button class="btn btn-success float-end mt-2" type="submit">Guardar</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
@endsection
