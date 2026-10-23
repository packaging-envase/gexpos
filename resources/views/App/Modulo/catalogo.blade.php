@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container">        

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            @include('layouts.messages')

            <h2 class="mb-3">
                <u>Modulo - Catálogo</u>
            </h2>

            <form method="POST" action="{{ route('modulo.catalogo.update-store') }}">
                @csrf
                <input hidden name="idExposicion" value="{{ $viewData['exposicion']['id'] }}" />
                <div class="row">
                    <div class="col-12 col-md-12 mb-2">
                        <label>Máximo en MB de imagenes</label>
                        <input class="form-control" style="width: 250px;" name="max_file_size"
                            value="{{ $viewData['exposicion']->catalogo->max_file_size }}" />
                    </div>
                    <div class="col-12 col-md-12 mb-2">
                        <label>Máxima cantidad de productos</label>
                        <input class="form-control" style="width: 250px;" name="max_quantity_productos"
                            value="{{ $viewData['exposicion']->catalogo->max_quantity_productos }}" />
                    </div>
                    <div class="col-12 col-md-12 mb-2">
                        <label>Máxima cantidad de caracteres en descripción de producto</label>
                        <input class="form-control" style="width: 250px;" name="max_caracteres_producto_descripcion"
                            value="{{ $viewData['exposicion']->catalogo->max_caracteres_producto_descripcion }}" />
                    </div>
                    <div class="col-12 col-md-12 mb-2">
                        <label>Máxima cantidad de caracteres en descripción de catalogo de la empresa</label>
                        <input class="form-control" style="width: 250px;" name="max_caracteres_empresa"
                            value="{{ $viewData['exposicion']->catalogo->max_caracteres_empresa }}" />
                    </div>
                    <div class="col-12 col-md-12">
                        <label>Fecha de Cierre</label>
                        <input class="form-control" style="width: 250px;" name="cierre" type="date"
                            value="{{ $viewData['exposicion']->catalogo->cierre }}" />
                    </div>
                </div>

                <button type="submit" class="btn btn-success float-end mt-2">Guardar</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
@endsection
