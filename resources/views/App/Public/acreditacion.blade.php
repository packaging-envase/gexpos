@extends('layouts.app')

@section('content')
<div class="container mt-4">

    @include('layouts.messages')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Formulario de Registro</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('public.acreditacion-store') }}" method="POST">
                @csrf
                
                <input hidden name="id_exposicion" value="{{$viewData['id_exposicion']}}"/>

                <div class="mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" class="form-control bg-white" id="dni" name="dni" required>
                </div>
                
                <div class="mb-3">
                    <label for="apellido_nombre" class="form-label">Apellido y Nombre</label>
                    <input type="text" class="form-control bg-white" id="apellido_nombre" name="apellido_nombre" required>
                </div>
                
                <div class="mb-3">
                    <label for="whatsapp" class="form-label">WhatsApp</label>
                    <input type="text" class="form-control bg-white" id="whatsapp" name="whatsapp" required>
                </div>
                
                <div class="mb-3">
                    <label for="medio" class="form-label">Medio al que pertenece</label>
                    <input type="text" class="form-control bg-white" id="medio" name="medio" required>
                </div>

                <div class="mb-3">
                    <label for="medio" class="form-label">Razón Social de la Empresa</label>
                    <input type="text" class="form-control bg-white" id="razon_social" name="razon_social" required>
                </div>
                
                <button type="submit" class="btn btn-success float-end">Enviar</button>
            </form>
        </div>
    </div>
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
