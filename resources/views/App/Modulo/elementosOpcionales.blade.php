@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            <a class="btn btn-success float-end" href="{{route('modulo.elementos-opcionales.create', ['id' => $viewData['exposicion']['id']])}}">
                Nuevo Elemento Opcional
            </a>
            <h2 class="mb-3">
                <u>Modulo - Elementos Opcionales</u>
            </h2>

            @include('layouts.messages')

            @if (count($viewData['elementos']))
                <form method="POST" action="{{route('modulo.elementos-opcionales.update-cierre', ['id' => $viewData['exposicion']['id']])}}" class="p-2">
                    @csrf
                    Fecha de Cierre
                    <div class="input-group mb-3" style="width: fit-content;">                    
                        <input type="date" class="form-control" style="width: fit-content;" name="cierre" value="{{$viewData['elementos'][0]['cierre']}}"/>
                        <button class="btn btn-success" type="submit">Actualizar</button>
                    </div>
                </form>
            @endif

            <div class="d-flex">
            @if (count($viewData['elementos']))
                @foreach ($viewData['elementos'] as $elemento)
                    <div class="col shadow-sm border mx-2 p-1 position-relative">
                        @if (!$elemento['cierre'])
                        <h5 class="text-center"><b>SIN FECHA DE CIERRE (actualizar)</b></h5>
                        @endif
                        <h4 class="text-center">{{$elemento['titulo']}}</h4>
                        <div style="margin-bottom: 50px;">
                            <?php echo $elemento['descripcion'] ?>
                        </div>
                        <div class="d-flex justify-content-evenly position-absolute w-100" style="bottom: 10px;">
                            <a class="btn btn-danger" href="{{route('modulo.elementos-opcionales.delete', ['idElemento' => $elemento['id']])}}" onclick="return confirm('¿Seguro que desea eliminar este elemento opcional?')">Borrar</a>
                            <a class="btn btn-success" href="{{route('modulo.elementos-opcionales.create', ['id' => $viewData['exposicion']['id'], 'idElemento' => $elemento['id']])}}">Editar</a>
                        </div>
                    </div>
                @endforeach
            @endif
            </div>
        </div>
    </div>
@endsection
