@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'catalogo'])

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white border">
            <h2 class="mb-3">
                <u>Rubros</u> <a class="btn btn-success" href="{{route('categoria.excel')}}">Excel</a>
            </h2>
            @include('layouts.messages')

            <h4>Nuevo Rubro Nivel 0</h4>
            <form method="POST" action="{{ route('categoria.update-store') }}">
                @csrf
                <input hidden name="nivel" value="0" />
                <input hidden name="id_padre" value="" />

                <label>Nombre Rubro</label>
                <input class="form-control" name="nombre" />

                <button type="submit" class="btn btn-success float-end mt-2">Guardar</button>
                <div style="clear: both;"></div>
            </form>

            <hr />

            <h4>Nuevo Rubro Nivel 1</h4>
            <form method="POST" action="{{ route('categoria.update-store') }}">
                @csrf
                <input hidden name="nivel" value="1" />

                <select id="selectCategoria0Nivel1" class="form-control" name="id_padre">
                    <option hidden value="">Nivel 0</option>
                    @foreach ($viewData['categorias0'] as $item)
                        <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                    @endforeach
                </select>

                <label>Nombre Rubro</label>
                <input id="nombreCategoriaNivel1" class="form-control" name="nombre" disabled />

                <button type="submit" class="btn btn-success float-end mt-2">Guardar</button>
                <div style="clear: both;"></div>
            </form>

            <hr />

            <h4>Nuevo Rubro Nivel 2</h4>
            <form method="POST" action="{{ route('categoria.update-store') }}">
                @csrf
                <input hidden name="nivel" value="2" />

                <select id="selectCategoria0Nivel2" class="form-control">
                    <option hidden value="">Nivel 0</option>
                    @foreach ($viewData['categorias0'] as $item)
                        <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                    @endforeach
                </select>

                <select id="selectCategoria1Nivel2" class="form-control mt-2" name="id_padre" disabled>
                    <option hidden value="">Nivel 1</option>
                </select>

                <label>Nombre Rubro</label>
                <input id="nombreCategoriaNivel2" class="form-control" name="nombre" disabled />

                <button type="submit" class="btn btn-success float-end mt-2">Guardar</button>
                <div style="clear: both;"></div>
            </form>

            @foreach ($viewData['categorias'] as $categoria0)
                <label>{{ $categoria0['nombre'] }}</label> <small>({{ count($categoria0['categoria1']) }}
                    items)</small><br />
                @foreach ($categoria0['categoria1'] as $categoria1)
                    <div class="d-flex">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="ms-1" style="border: none; background: none; padding: 0; margin: 0;" onclick="openModalEditCategoria('{{$categoria1['id']}}', '{{$categoria1['nombre']}}')">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                        <label style="cursor: pointer;" onclick="$('#listCategoria{{ $categoria1['id'] }}').toggle();"
                            class="ms-1">&#160;&#160;{{ $categoria1['nombre'] }}</label>
                        <small>({{ count($categoria1['categoria2']) }} items)</small><br />
                    </div>
                    <div id="listCategoria{{ $categoria1['id'] }}" style="display: none;">
                        @foreach ($categoria1['categoria2'] as $categoria2)
                            <div class="d-flex">
                                <form method="POST" action="{{ route('categoria.delete') }}"
                                    onsubmit="return confirm('Está por eliminar la categoría {{ $categoria2['nombre'] }}. Todas las empresas que tengan esta categoría se les eliminará.')">
                                    @csrf
                                    <input hidden name="id" value="{{ $categoria2['id'] }}" />

                                    <button type="submit" style="border: none; background: none; padding: 0; margin: 0;">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="ms-1" style="border: none; background: none; padding: 0; margin: 0;" onclick="openModalEditCategoria('{{$categoria2['id']}}', '{{$categoria2['nombre']}}')">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                                <label>&#160;&#160;&#160;&#160;{{ $categoria2['nombre'] }}</label><br />
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Rubro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarRubro" method="POST" action="{{route('categoria.update-store')}}">
                            @csrf
                            <input hidden id="inputIdEditarRubro" name="id" />
                            <label>Nombre del Rubro</label>
                            <input id="inputNombreEditarRubro" class="form-control" name="nombre" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" form="formEditarRubro" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let categorias1 = @json($viewData['categorias1']);

        $('#selectCategoria0Nivel1').on('change', function() {
            let value = $(this).val();

            $('#nombreCategoriaNivel1').prop('disabled', false);

        });

        $('#selectCategoria0Nivel2').on('change', function() {
            let value = $(this).val();
            console.log(value);

            $('#selectCategoria1Nivel2').prop('disabled', false);

            categorias1.forEach(categoria => {
                console.log(categoria);
                if (categoria.id_padre == value) {
                    $('#selectCategoria1Nivel2').append(`
                        <option value="` + categoria.id + `">` + categoria.nombre + `</option>
                    `);
                }
            });
            console.log(value);
        });

        $('#selectCategoria1Nivel2').on('change', function() {
            $('#nombreCategoriaNivel2').prop('disabled', false);
        });

        function openModalEditCategoria(id, nombre) {
            $('#inputIdEditarRubro').val(id);
            $('#inputNombreEditarRubro').val(nombre);
        }
    </script>
@endsection
