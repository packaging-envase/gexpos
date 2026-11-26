@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{asset('js\tinymce\tinymce\tinymce.min.js')}}"></script>

    <div class="container">

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

        
            <h2 class="mb-3 float-start">
                <u>Modulo - Prensa - Listado</u>
            </h2>

            <a class="btn btn-primary float-start ms-2" href="{{route('public.prensa', ['id' => $viewData['exposicion']['id']])}}" target="_blank">Previsualización</a>

            <div style="clear: both;"></div>

            @include('layouts.messages')

            <table class="table table-striped">
                <tbody>
                    @foreach ($viewData['prensas'] as $prensa)
                        <tr>
                            <td>{{ $prensa['created_at']->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{route('modulo.prensa.create', ['id' => $viewData['exposicion']['id'], 'idPrensa' => $prensa['id'], 'view' => 'true'])}}">
                                    {{ $prensa['titulo'] }}
                                </a>
                            </td>
                            <td>
                                @if ($prensa['aprobado'])
                                    <button disabled class="btn btn-success">Activa</button>
                                @else
                                    <button disabled class="btn btn-danger">Desactivada</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>


@endsection