<!doctype html>
@extends('layouts.app')

@section('content')

    <div class="container">
        @include('App.ModuloExpositor.header', ['title' => 'Invitaciones', 'requerimientos' => false])

        <div class="mt-3 expositor-modulo-invitaciones shadow-sm p-3 bg-white border mb-5" style="min-height: calc(100vh - 55px);">
            @include('layouts.messages')

            <div class="float-end">
                <b>Fecha de Cierre de Modulo: {{ \DateTime::createFromFormat('Y-m-d', $viewData['invitacion']['cierre'])->format('d/m/Y') }}</b>
            </div>

            <div style="clear: both;"></div>

            <div class="mt-2">
                <?php echo $viewData['invitacion']['descripcion']; ?>
            </div>
            <form id="formInvitiacion" method="POST" action="{{ route('expositor.modulo.invitaciones.update-store') }}" onsubmit="validarCantidad(event);">
                @csrf
                <h5><b>Cantidad Máxima de solicitud: {{ $viewData['maxSolicitud'] }}</b></h5>
                
                <label>Invitaciones Solicitadas</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" style="width: 200px;"
                       value="{{ $viewData['expositorModuloInvitacion'] ? $viewData['expositorModuloInvitacion']['cantidad'] : 0 }}" />
            
                @if ($viewData['invitacion']['cierre'] >= date('Y-m-d'))
                    <button id="confirmarBtn" type="submit" class="btn btn-success mt-2">Guardar</button>
                @endif
            </form>            
        </div>
    </div>

    <script>
        const maxCantidad = {{ $viewData['maxSolicitud'] }};
        function validarCantidad(event) {
            const cantidad = parseInt(document.getElementById('cantidad').value);
            if (cantidad > maxCantidad) {
                alert('Estás solicitando más de la cantidad máxima permitida (' + maxCantidad + ')');
                event.preventDefault();
                return false;
            }

            // Marcar como enviado
            formularioYaEnviado = true;
            
            // Mostrar spinner en botón
            const btn = document.getElementById('confirmarBtn');
            console.log('btn',btn);
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="visually-hidden">Cargando...</span>
            `;
            btn.classList.add('disabled');
            return true;
        }

        $(document).ready(function() {
            var partes = "<?php echo $viewData['invitacion']['cierre']; ?>".split("-");
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
                $('#formInvitacion input, #formInvitacion button').prop('disabled', true);
            }
        });
    </script>

@endsection
