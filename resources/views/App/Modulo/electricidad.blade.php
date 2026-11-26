@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{asset('js\tinymce\tinymce\tinymce.min.js')}}"></script>

    <div class="container">

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">
        
            <h2 class="mb-3">
                <u>Modulo - Electricidad</u>
            </h2>
            
            @include('layouts.messages')

            <form method="POST" action="{{route('modulo.electricidad.update-store')}}">
                @csrf
                <input hidden name="idExposicion" value="{{$viewData['exposicion']['id']}}" />

                <label>Fecha de Cierre</label>
                <input class="form-control" type="date" value="{{$viewData['exposicion']['electricidad']['cierre']}}" name="cierre" style="width: fit-content;"/>

                <label class="mt-2">Texto Descriptivo</label>
                <textarea id="mytextarea" name="descripcion">
                    @if ($viewData['exposicion']['electricidad'])
                        {{$viewData['exposicion']['electricidad']['descripcion']}}
                    @else
                        
                    @endif
                </textarea>
                <script type="text/javascript">
                    tinymce.init({
                        selector: '#mytextarea',
                        license_key: 'gpl'
                    });
                </script>

                <button type="submit" class="btn btn-success float-end mt-2">Guardar</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
@endsection
