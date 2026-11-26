@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <a class="btn btn-primary mb-2" href="{{route('expositor.panel-expositor')}}"><- Volver</a>
    <div class="bg-secondary text-white">
        <h5 class="text-center py-1 m-0 mb-2">Notificaciones</h5>
    </div>

    @foreach ($viewData['notificaciones'] as $notificacion)
        <div class="border p-1 mb-4 shadow-sm" style="background-color: white !important;">
            <b>{{ substr($notificacion['created_at'], 0, 10) }}</b>
            <?php echo $notificacion['descripcion']; ?>
        </div>
    @endforeach
</div>

@endsection