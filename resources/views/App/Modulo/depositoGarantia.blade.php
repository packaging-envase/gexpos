@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{asset('js\tinymce\tinymce\tinymce.min.js')}}"></script>

    <div class="container">

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">
                    
            <h2 class="mb-3">
                <u>Modulo - Deposito Garantia</u>
            </h2>
            
            @include('layouts.messages')

            <form method="POST" action="{{route('modulo.deposito-garantia.update-store')}}" enctype="multipart/form-data">
                @csrf
                <input hidden name="idExposicion" value="{{$viewData['exposicion']['id']}}" />

                <div class="d-flex">
                    <div>
                        <label>Fecha de Cierre</label>
                        <input class="form-control" type="date" value="{{isset($viewData['moduloDepositoGarantia']) ? $viewData['moduloDepositoGarantia']['fecha_cierre'] : ''}}" name="fecha_cierre" style="width: fit-content;" required/>
                    </div>

                    <div class="ms-2">
                        <label>Porcentaje del stand $</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" step="0.01" max="99.99" value="{{isset($viewData['moduloDepositoGarantia']) ? $viewData['moduloDepositoGarantia']['porcentaje'] : ''}}" name="porcentaje" style="width: 4rem; text-align: right;" required/>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon1">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="ms-2">
                        <label>Fecha</label>
                        <input class="form-control" type="date" value="{{isset($viewData['moduloDepositoGarantia']) ? 
                            $viewData['moduloDepositoGarantia']['fecha'] : ''}}" 
                            name="fecha" 
                            style="width: fit-content; text-align: right;" required />
                    </div>

                    {{--
                    <div class="ms-2">
                        <label>Documento Nuevo</label>
                        <div class="d-flex">
                            <input class="form-control" type="file" style="width:400px;" name="archivo" />
                            @if (isset($viewData['moduloDepositoGarantia']) && $viewData['moduloDepositoGarantia']['path'])
                                <a href="{{ route('modulo.deposito-garantia.download-file', ['path' => $viewData['moduloDepositoGarantia']['path']]) }}" target="_blank" class="align-self-end ms-2">
                                    Descargar Documento Actual
                                </a>
                            @endif
                        </div>
                    </div>
                    --}}
                </div>

                <label class="mt-2">Texto Descriptivo</label>
                <textarea id="mytextarea" name="descripcion" required>
                    @if ($viewData['moduloDepositoGarantia'])
                        {{$viewData['moduloDepositoGarantia']['descripcion']}}
                    @endif
                </textarea>
                <script type="text/javascript">
                    tinymce.init({
                        selector: '#mytextarea',
                        license_key: 'gpl'
                    });
                </script>

                <button type="submit" class="btn btn-success float-end mt-2">Guardar</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
@endsection
