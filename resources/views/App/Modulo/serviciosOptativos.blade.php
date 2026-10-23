@extends('layouts.app')

@section('content')
    <style>
        .dt-search {
            float: right;
        }

        .dt-buttons {
            float: left;
        }
    </style>
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container mb-5 mt-1">

        <div class="shadow bg-white p-2">
            <h2 class="mb-3">
                <u>Modulo - Proveedores Optativos</u>
            </h2>

            @include('layouts.messages')

            <div class="mt-2 position-relative">
                <a class="btn btn-success position-absolute me-2" style="right: 0;" href="{{route('modulo.servicios-optativos.create', ['id' => $viewData['exposicion']['id']])}}">Nuevo Proveedor Optativo</a>
                <h3 class=""><u>Listado de Proveedores Optativos</u></h3>
                

                <div class="row">
                @foreach ($viewData['exposicion']->serviciosOptativos as $item)
                    <div class="col-12 col-md-6">
                        <div class="text-center border m-2 shadow-sm ">
                            <label><b><u>{{ $item['nombre'] }}</u></b></label><br/>
                            <?php echo $item['descripcion'] ?>
                            <br/>
                            
                            @if ($item['logo'])
                                <img src="{{ asset('storage/serviciosOptativos/logos/' . $item['logo']) }}" alt="Logo" style="max-width: 250px;"/>
                            @endif
                            <br/>
                            <div class="my-2">
                                <a class="btn btn-success"
                                    href="{{ route('modulo.servicios-optativos.create', ['id' => $viewData['exposicion']['id'], 'idItem' => $item['id']]) }}">Editar</a>
                                <button disabled class="btn btn-{{$item['active'] ? 'primary' : 'danger'}}">{{$item['active'] ? 'Activo' : 'Inactivo'}}</button>
                            </div>
                        </div>
                    </div>                    
                @endforeach
            </div>
        </div>
    </div>
@endsection
