@extends('layouts.app')
@section('content')
    <div class="container">
        @foreach ($viewData['prensas'] as $prensa)        
            
            @include('App.Modulo.prensaElement', ['prensa' => $prensa])
            
            @if (!$loop->last)
                <hr/>
            @endif
        @endforeach
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