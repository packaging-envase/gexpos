@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="container mb-5 mt-1">
        <div class="shadow p-3 bg-white rounded">

            <h2 class="mb-3">
                <u>Modulo - Credenciales</u>
            </h2>

            @include('layouts.messages')

            <form method="POST" action="{{ route('variable.update-store') }}">
                @csrf
                @if ($viewData['credencial_vencimiento'])
                    <input hidden name="idVariable"
                        value="{{ $viewData['credencial_vencimiento'] ? $viewData['credencial_vencimiento']['id'] : '' }}" />
                @endif
                <input hidden name="id_exposicion" value="{{ $viewData['exposicion']['id'] }}" />
                <input hidden name="descripcion" value="credencial_vencimiento" />

                <label>Ultimo día del expositor para cambiar la peticion de credenciales</label>
                <div class="input-group mb-3" style="width: fit-content;">
                    <input type="date" class="form-control" name="value_date"
                        value="{{ $viewData['credencial_vencimiento'] ? $viewData['credencial_vencimiento']['value_date'] : '' }}">
                    <button class="btn btn-success" type="submit">Actualizar</button>
                </div>
            </form>

            @foreach ($viewData['credencial_tipo_exposicion'] as $item)
                <form id="formCredencialTipo{{ $item['id'] }}" method="POST"
                    action="{{ route('modulo.credencial.update') }}">
                    @csrf
                    <input hidden name="id" value="{{ $viewData['exposicion']['id'] }}" />
                    <input hidden name="idCredencial" value="{{ $item['id'] }}" />
                </form>
                <form id="formCredencialMaximo{{ $item['id'] }}" method="POST" action="{{route('modulo.credencial.update-maximo')}}">
                    @csrf
                    <input hidden name="id" value="{{ $viewData['exposicion']['id'] }}" />
                    <input hidden name="idCredencial" value="{{ $item['id'] }}" />
                </form>
            @endforeach
            <table class="table table-striped" style="width: 750px;">
                <thead>
                    <tr class="table-dark">
                        <th>Descripción</th>
                        <th>Activada</th>
                        <th>1 credencial x metros cuadrados:</th>
                        <th>Máximo por Stand</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewData['credencial_tipo_exposicion'] as $item)
                        <tr>
                            <td style="white-space: nowrap;">
                                {{ $item->credencialTipo['descripcion'] }}
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="activa" class="form-check-input"
                                        form="formCredencialTipo{{ $item['id'] }}"
                                        onchange="$('#formCredencialTipo{{ $item['id'] }}').submit()"
                                        {{ $item['activa'] ? 'checked=true' : '' }} />
                                </div>
                            </td>
                            <td>
                                <div class="input-group mb-3">
                                    <input form="formCredencialMaximo{{ $item['id'] }}" type="number" class="form-control" name="metros" value="{{$item['metros'] ? $item['metros'] : 0}}">
                                    <button form="formCredencialMaximo{{ $item['id'] }}" class="btn btn-success" type="submit">Actualizar</button>
                                </div>
                            </td>
                            <td>
                                <div class="input-group mb-3">
                                    <input form="formCredencialMaximo{{ $item['id'] }}" type="number" class="form-control" name="maximo" value="{{$item['maximo'] ? $item['maximo'] : 0}}">
                                    <button form="formCredencialMaximo{{ $item['id'] }}" class="btn btn-success" type="submit">Actualizar</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
