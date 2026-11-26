<!doctype html>
@extends('layouts.app')

@section('content')
    <style>
        .file-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .file-item button {
            margin-left: 10px;
        }
    </style>
    </style>
    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    @if (!Auth::guard('expositor')->check())
        @include('layouts.navigation-menu-2', ['active' => 'exposicion'])
    @endif

    <div class="container">

        @include('App.ModuloExpositor.header', ['title' => 'Deposito en Garantia', 'requerimientos' => true])

        <div class="expositor-modulo-invitaciones shadow-sm mt-3 border p-3 bg-white mb-5"
            style="min-height: calc(100vh - 55px);">

            @include('layouts.messages')

            @if (\DateTime::createFromFormat('Y-m-d', $viewData['moduloDepositoGarantia']['fecha_cierre'])->format('Y-m-d') < date('Y-m-d'))
                <div class="alert alert-warning">
                    Este módulo llego a su fecha de cierre
                </div>
            @endif

            <div class="float-end">
                <b>Fecha de Cierre de Modulo: {{ \DateTime::createFromFormat('Y-m-d', $viewData['moduloDepositoGarantia']['fecha_cierre'])->format('d/m/Y') }}</b>
            </div>

            <div class="mt-5 mx-2">
                <?php echo $viewData['moduloDepositoGarantia']['descripcion']; ?>
            </div>

            {{--
            @if (isset($viewData['moduloDepositoGarantia']) && $viewData['moduloDepositoGarantia']['path'])
                <a href="{{ route('modulo.deposito-garantia.download-file', ['path' => $viewData['moduloDepositoGarantia']['path']]) }}" target="_blank" class="align-self-end mx-2">
                    Descargar Documento
                </a>
            @endif
            --}}

            @if (\DateTime::createFromFormat('Y-m-d', $viewData['moduloDepositoGarantia']['fecha_cierre'])->format('Y-m-d') >= date('Y-m-d'))
                <form method="POST" action="{{route('expositor.modulo.deposito-garantia.update-store')}}" class="mx-2">
                    @csrf
                    
                    @if ($viewData['expositorModuloDepositoGarantia'])
                        <input hidden name="id" value={{$viewData['expositorModuloDepositoGarantia']['id']}} />
                    @endif

                    @if ($viewData['montoUsd'])
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Monto U$/D</span>
                            <input type="text" class="form-control" disabled value="${{$viewData['montoUsd']}}">
                        </div>
                    @endif
                    @if ($viewData['montoArs'])
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Monto ARS</span>
                            <input type="text" class="form-control" disabled value="${{$viewData['montoArs']}}">
                        </div>
                    @endif


                    <label>* Apellido y Nombre</label>
                    <input class="form-control" name="apellido_nombre" value="{{$viewData['expositorModuloDepositoGarantia'] ? $viewData['expositorModuloDepositoGarantia']['apellido_nombre'] : ''}}"  required/>
                    <label>* Número Documento</label>
                    <input class="form-control" name="numero_documento" value="{{$viewData['expositorModuloDepositoGarantia'] ? $viewData['expositorModuloDepositoGarantia']['numero_documento'] : ''}}"  required/>
                    <label>* Dirección</label>
                    <input class="form-control" name="direccion" value="{{$viewData['expositorModuloDepositoGarantia'] ? $viewData['expositorModuloDepositoGarantia']['direccion'] : ''}}"  required/>
                    
                    {{--
                    <label>Documento</label>
                    <input class="form-control" type="file" name="responsable_path" /> 
                    --}}
                    <button class="btn btn-success float-end mt-2">Descargar Pagaré</button>
                    <div style="clear: both;"></div>
                </form>
            @endif
        </div>
    </div>
@endsection
