@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{asset('js\tinymce\tinymce\tinymce.min.js')}}"></script>

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">

            <div class="container">
                <h2 class="mb-3">
                    <u>Modulo - Nueva Notificación</u>
                </h2>
                
                @include('layouts.messages')

                <form method="POST" action="{{route('modulo.notificacion.store')}}">
                    @csrf
                    <input hidden name="idExposicion" value="{{$viewData['exposicion']['id']}}" />
                    @if ($viewData['notificacion'])
                        <input hidden name="idNotificacion" value="{{$viewData['notificacion']['id']}}" />
                    @endif
                    <textarea id="mytextarea" name="descripcion" style="resize: none !important;">
                        {{$viewData['notificacion'] ? $viewData['notificacion']['descripcion'] : ''}}
                    </textarea>
                    <script type="text/javascript">
                        tinymce.init({
                            selector: '#mytextarea',
                            license_key: 'gpl',
                            resize: false,
                        });
                    </script>
                    <button class="btn btn-success float-end mt-2" type="submit">Enviar</button>
                    <div style="clear: both;"></div>
                </form>
            </div>
        </div>
    </div>
@endsection
