@extends('layouts.app')

@section('content')
    
    <div class="">
        @include('App.Public.proveedoresOptativosComponent')
    </div>


    <script>
        $('#navbarMain').hide();
        $('#nav2').hide();
        $('#navExp').hide();
        window.onload = function() {
            // Obtener la altura del contenido
            var height = document.documentElement.scrollHeight;
            // Enviar la altura al parent (página principal)
            window.parent.postMessage(height, '*');
        };
    </script>
@endsection
