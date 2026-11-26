@extends('layouts.app')

@section('content')

    <div class="border shadow mx-auto mt-4" style="width: 500px;">
        <div class="bg-secondary text-white">
            <h5 class="text-center py-1">Notificaciones</h5>
        </div>

        <ul>
            @foreach ($viewData['notificaciones'] as $notificacion)
                <li>
                    <?php echo $notificacion->descripcion; ?>
                </li>
            @endforeach
        </ul>

        <button class="btn btn-success float-end m-2" onclick="window.location.reload()">Aceptar</button>
        <div style="clear: both;"></div>
    </div>
@endsection