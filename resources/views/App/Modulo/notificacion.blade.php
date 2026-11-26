@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{asset('js\tinymce\tinymce\tinymce.min.js')}}"></script>

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">

            <div class="container">
                <a href="{{route('modulo.notificacion.create', ['id' => $viewData['exposicion']['id']])}}" class="btn btn-primary float-end">
                    Nueva Notificación
                </a>
                <h2 class="mb-3">
                    <u>Modulo - Notificación</u>
                </h2>
                
                @include('layouts.messages')

                <table class="table table-striped">
                    <thead>
                        <tr class="table-dark">
                            <th>Descripción</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewData['notificaciones'] as $notificacion)
                            <tr>
                                <td><?php echo $notificacion->descripcion; ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-danger" href="{{route('modulo.notificacion.delete', ['idNotificacion' => $notificacion['id']])}}">Borrar</a>
                                        <a href="{{route('modulo.notificacion.create', ['id' => $viewData['exposicion']['id'], 'idNotificacion' => $notificacion['id']])}}" class="btn btn-success ms-2">
                                            Editar
                                        </a>                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
