<!doctype html>
@extends('layouts.app')

@section('content')
    <div class="container">
        @include('App.ModuloExpositor.header', [
            'title' => 'Solicitud de Energía extra',
            'requerimientos' => false,
        ])

        <div class="mt-3 expositor-modulo-invitaciones shadow-sm p-3 bg-white border mb-5"
            style="min-height: calc(100vh - 55px);">

            @include('layouts.messages')

            <form id="formElectricidad" method="POST" action="{{ route('expositor.modulo.electricidad.update-store') }}"
                onsubmit="return validarRadios()">
                @csrf

                @if ($viewData['expositorModuloElectricidad'])
                    <input hidden name="id" value="{{ $viewData['expositorModuloElectricidad']['id'] }}" />
                @endif

                <div class="">

                    <div class="positoin-relative">
                        <h4 class="position-absolute" style="transform: translateX(-50%); left: 50%;">
                            <u>Solicitud de Energía</u>
                        </h4>
                        <div class="float-end">
                            <b>Fecha de Cierre de Modulo:
                                {{ \DateTime::createFromFormat('Y-m-d', $viewData['electricidad']['cierre'])->format('d/m/Y') }}</b>
                        </div>
                    </div>

                    <br />

                    <div class="mt-2">
                        <?php echo $viewData['electricidad']['descripcion']; ?>
                    </div>

                    <div class="mt-2 p-2 w-100">
                        <label><b>Indique si presenta máquinas o equipos</b></label>
                        <span class="float-end">
                            <input id="tieneMaquinasSi" type="radio" name="tiene_maquinas" value="1"
                                {{ $viewData['expositorModuloElectricidad'] && $viewData['expositorModuloElectricidad']['tiene_maquinas'] ? 'checked=true' : '' }}
                                {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} /><label
                                for="tieneMaquinasSi">&#160;Si</label>
                            <input id="tieneMaquinasNo" type="radio" name="tiene_maquinas" value="0"
                                {{ $viewData['expositorModuloElectricidad'] && !$viewData['expositorModuloElectricidad']['tiene_maquinas'] ? 'checked=true' : '' }}
                                {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} /><label
                                for="tieneMaquinasNo">&#160;No</label>
                        </span>
                    </div>

                    <div id="enFuncionamiento" class="d-none">
                        <div class="mt-2 p-2 w-100">
                            <hr />
                            <label>¿Los pondría en funcionamiento?</label>
                            <span class="float-end">
                                <input id="funcionanSi" type="radio" name="funcionan" value="1"
                                    {{ $viewData['expositorModuloElectricidad'] && $viewData['expositorModuloElectricidad']['funcionan'] ? 'checked=true' : '' }}
                                    {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} /><label
                                    for="funcionanSi">&#160;Si</label>
                                <input id="funcionanNo" type="radio" name="funcionan" value="0"
                                    {{ $viewData['expositorModuloElectricidad'] && !$viewData['expositorModuloElectricidad']['funcionan'] ? 'checked=true' : '' }}
                                    {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} /><label
                                    for="funcionanNo">&#160;No</label>
                            </span>
                        </div>

                        <hr />

                        <div class="mt-2 p-2 w-100">
                            <label>Cantidad de máquinas/equipos a presentar</label>
                            <input id="cantidadMaquinas" class="form-control" name="cantidad" type="number"
                                value="{{ $viewData['expositorModuloElectricidad'] && $viewData['expositorModuloElectricidad']['cantidad'] ? $viewData['expositorModuloElectricidad']['cantidad'] : 0 }}"
                                {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                        </div>

                        <hr />

                        <div class="mt-2 p-2 w-100 ¡">
                            <label>Ingrese los pesos y medidas de sus equipos</label>
                            <textarea id="pesoMedida" name="peso_medida" class="form-control" style="resize: none;" rows='3'
                                {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }}>{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['peso_medida'] : '' }}</textarea>
                        </div>

                        <hr />
                    </div>
                    <div class="p-2 mt-2 w-100">
                        <div class="mt-1">
                            <div class="d-flex">
                                <span>Solicito&#160;</span>
                                <input name="monofasica" style="width: 50px;"
                                    value="{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['monofasica'] : '0' }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                    required {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                                <span>&#160;KW monofásica (220v)</span>
                            </div>
                            <div class="d-flex">
                                <span>Solicito&#160;</span>
                                <input name="trifasica" style="width: 50px;"
                                    value="{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['trifasica'] : '0' }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                    required {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                                <span>&#160;KW trifásica (380v)</span>
                            </div>
                            <small>En caso de no solicitar energía, indicar con 0</small>
                        </div>
                    </div>

                    <hr />

                    <div class="p-2 mt-2 w-100">
                        <h5 style="color: #665579;"><u>Personal responsable del pedido</u></h5>
                        <label>Apellido y Nombre <b title="Campor Requerido">*</b></label>
                        <input required name="apellido_nombre" class="form-control"
                            value="{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['apellido_nombre'] : '' }}"
                            {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                        <label>Cargo <b title="Campor Requerido">*</b></label>
                        <input required name="cargo" class="form-control"
                            value="{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['cargo'] : '' }}"
                            {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                        <label>Teléfono <b title="Campor Requerido">*</b></label>
                        <input required name="telefono" class="form-control"
                            value="{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['telefono'] : '' }}"
                            {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                        <label>Celular / Whatsapp <b title="Campor Requerido">*</b></label>
                        <input required name="celular" class="form-control"
                            value="{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['celular'] : '' }}"
                            {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                        <label>E-Mail <b title="Campor Requerido">*</b></label>
                        <input required name="email" class="form-control"
                            value="{{ $viewData['expositorModuloElectricidad'] ? $viewData['expositorModuloElectricidad']['email'] : '' }}"
                            {{ $viewData['expositorModuloElectricidad'] ? 'disabled=true' : '' }} />
                        <small>* Campo Obligatorio</small>
                    </div>
                    @if (!$viewData['expositorModuloElectricidad'] && $viewData['electricidad']['cierre'] >= date('Y-m-d'))
                        <button type="submit" id="btnGuardar" class="btn btn-success mt-2 float-end">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span class="texto-boton">Guardar</span>
                        </button>
                    @endif
                    <div style="clear: both;"></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formElectricidad');
            const btn = document.getElementById('btnGuardar');
            const spinner = btn.querySelector('.spinner-border');
            const texto = btn.querySelector('.texto-boton');

            form.addEventListener('submit', function() {
                btn.disabled = true;
                spinner.classList.remove('d-none');
                texto.textContent = 'Guardando...';
            });
        });
        $(document).ready(function() {
            $("input[name='tiene_maquinas']").change(function() {
                onChangeTieneMaquinas();
            });

            onChangeTieneMaquinas();

            var partes = "<?php echo $viewData['electricidad']['cierre']; ?>".split("-");
            var cierreDate = new Date(
            partes[0],
            partes[1] - 1,
            partes[2]
            );

            var today = new Date();
            console.log('cierreDate: ',cierreDate);
            console.log('today: ',today);
            // Comparar las fechas
            cierreDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);

            /* comparación solo por día (inclusivo) */
            if (today > cierreDate) {
                $('#formElectricidad input, #formElectricidad button').prop('disabled', true);
            }
        });

        function onChangeTieneMaquinas() {
            let selectedValue = $("input[name='tiene_maquinas']:checked").val();

            if (selectedValue == '1') {
                $('#enFuncionamiento').removeClass('d-none');
            } else {
                $('#enFuncionamiento').addClass('d-none');
            }
        }

        function validarRadios() {
            // Validar que "tiene_maquinas" está seleccionado
            var tieneMaquinasSi = document.getElementById('tieneMaquinasSi').checked;
            var tieneMaquinasNo = document.getElementById('tieneMaquinasNo').checked;

            // Validar que "funcionan" está seleccionado
            var funcionanSi = document.getElementById('funcionanSi').checked;
            var funcionanNo = document.getElementById('funcionanNo').checked;

            // Si no se seleccionó ninguno de los radios, mostrar un mensaje y cancelar el envío
            if (!(tieneMaquinasSi || tieneMaquinasNo)) {
                alert("Por favor, indique si presenta máquinas o equipos.");
                btn.disabled = false;
                spinner.classList.add('d-none');
                texto.textContent = 'guardar';
                return false;
            }

            if (tieneMaquinasSi && !(funcionanSi || funcionanNo)) {
                alert("Por favor, indique si los pondría en funcionamiento.");
                btn.disabled = false;
                spinner.classList.add('d-none');
                texto.textContent = 'guardar';
                return false;
            }

            if (tieneMaquinasSi && !document.getElementById('cantidadMaquinas').value) {
                alert("Debe indicar la cantidad de las máquinas/equipos");
                btn.disabled = false;
                spinner.classList.add('d-none');
                texto.textContent = 'guardar';
                return false;
            }

            if (tieneMaquinasSi && !document.getElementById('pesoMedida').value) {
                alert("Debe indicar el peso y las medidades de las máquinas/equipos");
                btn.disabled = false;
                spinner.classList.add('d-none');
                texto.textContent = 'guardar';
                return false;
            }

            // Si ambos grupos de radios están seleccionados, permitir el envío
            return true;
        }
    </script>
@endsection
