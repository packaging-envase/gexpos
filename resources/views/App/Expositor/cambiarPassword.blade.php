@extends('layouts.app')

@section('content')

<div class="border shadow mx-auto mt-4" style="width: 500px;">
    <div class="bg-secondary text-white">
        <h5 class="text-center py-1">Cambio de Contraseña</h5>
    </div>
    
    
    <form id="passwordForm" method="POST" action="{{isset($viewData['token']) ? route('reset-password-confirm') : route('expositor.change-password')}}">
        @csrf
        <div class="mx-auto" style="width: fit-content;">
            @if (isset($viewData['token']))
                <input hidden value="{{$viewData['token']}}" name="token" />
            @endif
            <label>Nueva Contraseña</label>
            <input id="password" type="password" name="password" class="form-control" style="width:200px;" minlength="8"/>
        
            <label>Repetir Contraseña</label>
            <input id="repeat_password" type="password" class="form-control" style="width:200px;" minlength="8"/>
            <button type="submit" class="btn btn-success my-2 float-end">Guardar</button>
            <div style="clear: both;"></div>
        </div>
    </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#passwordForm').on('submit', function(e) {
            var password = $('#password').val();
            var repeatPassword = $('#repeat_password').val();

            if (password !== repeatPassword) {
                e.preventDefault(); // Detiene el envío del formulario
                alert('Passwords do not match. Please try again.');
            }
        });
    });
</script>
@endsection