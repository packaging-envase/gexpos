@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'catalogo'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white border">
            <h2 class="mb-3">
                <u>Tipo de Credenciales</u>
            </h2>
            @include('layouts.messages')

            <form method="POST" action="{{route('credencial.update-store')}}">
                @csrf
                <div class="input-group mb-3" style="width: 500px;">
                    <input type="text" class="form-control" name="descripcion">
                    <button class="btn btn-success" type="submit">Crear Nuevo</button>
                </div>
            </form>

            <table class="table table-striped">
                <thead>
                    <tr class="table-dark">
                        <th>Descripción</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['credencial_tipo'] as $key => $item)
                        <form id="credencialUpdateStore{{$key}}" method="POST" action="{{route('credencial.update-store')}}">
                            @csrf
                            <input hidden name="id" value="{{$item['id']}}" />
                        </form>
                        <tr>
                            <td>
                                <input class="form-control" value="{{$item['descripcion']}}" name="descripcion" form="credencialUpdateStore{{$key}}" />
                            </td>
                            <td>
                                <button class="btn btn-success" type="submit" form="credencialUpdateStore{{$key}}">Actualizar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection