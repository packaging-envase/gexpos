<div id="navExp" class="d-flex mb-1">
    @if (Auth::guard()->user()->grupo == '1')
        <a class="btn btn-{{$active == 'datos-principales' ? 'success' :  'secondary'}}" href="{{route('exposicion.create', ['id' => $viewData['exposicion']['id']])}}">Configuración</a>
        <a class="btn btn-{{$active == 'expositores' ? 'success' :  'secondary'}} ms-2" href="{{route('expositor.panel-administracion', ['id' => $viewData['exposicion']['id']])}}">Expositores</a>
        <a class="btn btn-{{$active == 'asignacion-stand' ? 'success' :  'secondary'}} ms-2" href="{{route('exposicion.create-3', ['id' => $viewData['exposicion']['id']])}}">Asignacion Stands</a>
        <select class="text-white form-control ms-2 {{$active == 'modulos' ? 'bg-success text-white' : 'bg-secondary'}}" onchange="if (this.value) location = this.value;" style="width: fit-content;">
            <option hidden value="">Config. Modulos</option>
            <option 
                class="{{$viewData ['exposicion']['modulo_catalogo'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.catalogo.create', ['id' => $viewData['exposicion']['id']])}}" {{isset($modulo) && $modulo == 'catalogo' ? 'selected=true' : ''}}>
                Catálogo
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_plano_stand'] == false ? 'd-none' : ''}}" 
                value="{{route('modulo.plano-stand.create', ['id' => $viewData['exposicion']['id']])}}" {{isset($modulo) && $modulo == 'plano-stand' ? 'selected=true' : ''}}>
                Planos de stand
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_elementos_opcionales'] == false ? 'd-none' : ''}}"
                disabled style="color: orange;">
                Elementos opcionales
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_solicitar_energia'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.electricidad.create', ['id' => $viewData['exposicion']['id']])}}" {{isset($modulo) && $modulo == 'electricidad' ? 'selected=true' : ''}}>            
                Energía extra
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_deposito_garantia'] == false ? 'd-none' : ''}}"
                disabled style="color: orange;">
                Depósito en garantía
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_credenciales'] == false ? 'd-none' : ''}}"
                disabled style="color: orange;">
                Credenciales
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_invitaciones'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.invitacion.create', ['id' => $viewData['exposicion']['id']])}}" {{isset($modulo) && $modulo == 'invitaciones' ? 'selected=true' : ''}}>
                Invitaciones
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_sala_conferencia'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.sala-conferencia.panel' , ['id' => $viewData['exposicion']['id']])}}" {{isset($modulo) && $modulo == 'sala-conferencia' ? 'selected=true' : ''}}>
                Sala de Conferencia
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_servicios_optativos'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.servicios-optativos.panel' , ['id' => $viewData['exposicion']['id']])}}" {{isset($modulo) && $modulo == 'servicios-optativos' ? 'selected=true' : ''}}>
                Proveedores Optativos
            </option>
        </select>
        
    @endif
    <select class="text-white form-control ms-2 {{$active == 'requerimientos' ? 'bg-success text-white' : 'bg-secondary'}}" onchange="if (this.value) location = this.value;" style="width: fit-content;">
        <option hidden value="">Requer. Modulos</option>
        @if (Auth::guard()->user()->grupo == '1')
            <option 
                class="{{$viewData ['exposicion']['modulo_catalogo'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.catalogo.panel-requerimientos', ['id' => $viewData['exposicion']['id']])}}" {{isset($requerimiento) && $requerimiento == 'catalogo' ? 'selected=true' : ''}}>
                Catálogo
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_elementos_opcionales'] == false ? 'd-none' : ''}}"
                disabled style="color: orange;">
                Elementos opcionales
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_solicitar_energia'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.electricidad.panel-requerimientos', ['id' => $viewData['exposicion']['id']])}}" {{isset($requerimiento) && $requerimiento == 'electricidad' ? 'selected=true' : ''}}>
                Energía extra
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_deposito_garantia'] == false ? 'd-none' : ''}}"
                disabled style="color: orange;">
                Depósito en garantía
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_credenciales'] == false ? 'd-none' : ''}}"
                disabled style="color: orange;">
                Credenciales
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_invitaciones'] == false ? 'd-none' : ''}}"
                {{isset($requerimiento) && $requerimiento == 'invitaciones' ? 'selected=true' : ''}}
                disabled style="color: orange;">
                Invitaciones
            </option>
            <option 
                class="{{$viewData ['exposicion']['modulo_sala_conferencia'] == false ? 'd-none' : ''}}"
                value="{{route('modulo.sala-conferencia.panel-requerimientos', ['id' => $viewData['exposicion']['id']])}}" 
                {{isset($requerimiento) && $requerimiento == 'sala-conferencia' ? 'selected=true' : ''}}>
                Sala de Conferencia
            </option>

        @endif
        @if (Auth::guard()->user()->grupo == '1' || Auth::guard()->user()->grupo == '2')
            <option 
                class="{{$viewData ['exposicion']['modulo_plano_stand'] == false ? 'd-none' : ''}}" 
                value="{{route('modulo.plano-stand.panel-requerimientos', ['id' => $viewData['exposicion']['id']])}}" 
                {{isset($requerimiento) && $requerimiento == 'plano-stand' ? 'selected=true' : ''}}>
                Planos de stand
            </option>
        @endif
    </select>
    <!-- 
    @if (Auth::guard()->user()->grupo == '1')
        <a class="btn btn-primary ms-auto" href="{{route('public.catalogo', ['exposicion' => $viewData['exposicion']['id']])}}">Catalogo</a>
    @endif
    -->
</div>