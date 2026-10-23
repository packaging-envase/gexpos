@extends('layouts.app')
<style>
    @media (max-width: 900px) {
        .expositor-home .box-modulo {
            margin-left: auto;
            margin-right: auto;
            min-width: calc(100% - 40px);
            height: 200px;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 20px;
            border-top: 2px solid #009CA7;
        }
    }

    @media (min-width: 901px) {
        .expositor-home .box-modulo {
            min-width: calc(50% - 40px);
            height: 225px;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 20px;
            border-top: 2px solid #009CA7;
            max-width: 392px;
        }
    }
</style>
<script>
    let hrefCatalogo = "{{ route('expositor.modulo.catalogo.panel') }}";
    let hrefInvitaciones = "{{ route('expositor.modulo.invitaciones.index') }}";
    let hrefPlanoStand = "{{ route('expositor.modulo.plano-stand.index') }}";
    let hrefElectricidad = "{{ route('expositor.modulo.electricidad.index') }}";
    let hrefServiciosOptativos = "{{ route('expositor.modulo.servicios-optativos.index') }}";
    let hrefSalaConferencia = "{{ route('expositor.modulo.sala-conferencia.index') }}";
    let hrefCredencial = "{{ route('expositor.modulo.credencial.index') }}";
    let hrefElementosOpcionales = "{{ route('expositor.modulo.elementos-opcionales.index') }}";
    let hrefDocumentosImportantes = "{{ route('expositor.modulo.documentos-importantes') }}";
    let hrefNotificaciones = "{{ route('expositor.notificaciones') }}";
    let hrefDifusion = "{{ route('expositor.modulo.material-difusion.panel') }}";
    let hrefDepositoGarantia = "{{ route('expositor.modulo.deposito-garantia.index') }}";
</script>
@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Open Sans' !important;
        }
    </style>

    <div class="expositor-home pt-3" style='min-height: calc(100vh - 55px); font-family: "Times New Roman", Times, serif;'>
        {{-- <h1 class="text-center"><u>{{ $viewData['exposicion']['nombre'] }}</u></h1> --}}

        <div class="container position-relative">
            <!--<a class="btn btn-primary position-absolute me-4" style="right: 0;" href="">Notificaciones</a>-->

            <h5 class="text-center" style="font-size: 14px;">
                <b>Expositor: {{ $viewData['exposicion']['nombre'] }}</b>
            </h5>
            <h4 class="text-center" style="font-size: 14px;">
                {{ $viewData['empresa']['razon_social'] }}
                {{ $viewData['empresa']['nombre_fantasia'] ? ' - ' . $viewData['empresa']['razon_social'] : '' }}
            </h4>
            <div class="text-center">
                <a class="btn btn-primary" href="{{ route('expositor.list-exposiciones') }}">Listado des Exposiciones</a>
            </div>
        </div>

        <div class="p-3">

            @include('layouts.messages')

            <div style="margin-left: 25px; margin-bottom: 10px;"><b>Panel del Expositor // Exhibitor Panel</b></div>
            <!--<p class="" style="margin-left: 30px;">Complete los formularios para el expositor</p>-->
            <div class="row no-gutters">
                <div class="col-12 col-md-6 p-0">
                    <div style="margin-left: 20px;">
                        Información para el expositor // Important Information for the Exhibitors
                    </div>
                    <div class="d-flex justify-content-evenly" style="flex-wrap: wrap; border-right: 1px black solid;">
                        <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer;"
                            onclick="location.href = hrefNotificaciones">
                            <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                            <hr class="mx-4" />
                            <p class="m-0" style="font-size: 13px;"><b>Notificaciones<br /><span
                                        style="font-size: 13px;">Notifications</span></b></p>
                            <b>
                                <!--<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p>-->
                            </b>
                        </div>

                        <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                            onclick="location.href = hrefDocumentosImportantes">
                            <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                            <hr class="mx-4" />
                            <p class="m-0" style="font-size: 13px;"><b>Documentos Importantes<br /><span
                                        style="font-size: 13px;">Important Documents</span></b></b></p>
                            <!--<b><p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p></b>-->
                            <b>
                                <p>
                                    <!--<i class="fa fa-exclamation " aria-hidden="true"></i>
                                                        <span class="text-danger"> Vencimiento: 29 de Septiembre 2023</span>
                                                        -->
                                </p>
                            </b>
                        </div>

                        @if ($viewData['exposicion']['modulo_plano_stand'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer;"
                                onclick="location.href = hrefPlanoStand">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Planos del stand<br /><span
                                            style="font-size: 13px;">Booth plans</span></b></b></p>
                                <b>
                                    <!--<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p>-->
                                </b>
                            </div>
                        @endif

                        @if ($viewData['exposicion']['modulo_deposito_garantia'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                                onclick="location.href = hrefDepositoGarantia">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Depósito de garantía<br /><span
                                            style="font-size: 13px;">Security deposit</span></b></b></p>
                                <b>
                                    <!--<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p>-->
                                </b>
                            </div>
                        @endif

                        @if ($viewData['exposicion']['modulo_servicios_optativos'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                                onclick="location.href = hrefServiciosOptativos">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Proveedores Optativos<br /><span
                                            style="font-size: 13px;">Optional Suppliers</span></b></b></p>
                                <!--<b><p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p></b>-->
                                <b>
                                    <p>
                                        <!--<i class="fa fa-exclamation " aria-hidden="true"></i>
                                                        <span class="text-danger"> Vencimiento: 29 de Septiembre 2023</span>
                                                        -->
                                    </p>
                                </b>
                            </div>
                        @endif

                        <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                            onclick="location.href = hrefDifusion">
                            <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                            <hr class="mx-4" />
                            <p class="m-0" style="font-size: 13px;"><b>Material de Difusión<br /><span
                                        style="font-size: 13px;">Diffusion Material</span></b></b></p>
                            <!--<b><p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p></b>-->
                            <b>
                                <p>
                                    <!--<i class="fa fa-exclamation " aria-hidden="true"></i>
                                                        <span class="text-danger"> Vencimiento: 29 de Septiembre 2023</span>
                                                        -->
                                </p>
                            </b>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 p-0">
                    <div style="margin-left: 20px;">
                        Formularios para completar // Forms to Complete
                    </div>
                    <div class="d-flex justify-content-evenly" style="flex-wrap: wrap; border-left: 1px black solid;">
                        @if ($viewData['exposicion']['modulo_catalogo'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer;"
                                onclick="location.href = hrefCatalogo">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Catálogo del Expositor<br /><span
                                            style="font-size: 13px;">Exhibitor Catalogue</span></b></b></p>
                                @if ($viewData['vencimientos']['catalogo'])
                                    <div>
                                        <b>Cierre: {{ $viewData['vencimientos']['catalogo'] }}</b>
                                    </div>
                                @endif
                                <b>
                                    <!--<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p>-->
                                </b>
                            </div>
                        @endif

                        @if ($viewData['exposicion']['modulo_credenciales'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                                onclick="location.href = hrefCredencial">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Credenciales<br /><span
                                            style="font-size: 13px;">Credentials</span></b></b></p>
                                @if ($viewData['vencimientos']['credenciales'])
                                    <div>
                                        <b>Cierre: {{ $viewData['vencimientos']['credenciales'] }}</b>
                                    </div>
                                @endif
                                <b>
                                    <!--<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p>-->
                                </b>
                            </div>
                        @endif


                        @if ($viewData['exposicion']['modulo_elementos_opcionales'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                                onclick="location.href = hrefElementosOpcionales">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Elementos Opcionales<br /><span
                                            style="font-size: 13px;">Optional Elements</span></b></b></p>
                                @if ($viewData['vencimientos']['elementos'])
                                    <div>
                                        <b>Cierre: {{ $viewData['vencimientos']['elementos'] }}</b>
                                    </div>
                                @endif
                                <!--<b><p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p></b>-->
                                <b>
                                    <p>
                                        <!--<i class="fa fa-exclamation " aria-hidden="true"></i>
                                                        <span class="text-danger"> Vencimiento: 29 de Septiembre 2023</span>
                                                        -->
                                    </p>
                                </b>
                            </div>
                        @endif


                        @if ($viewData['exposicion']['modulo_solicitar_energia'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                                onclick="location.href = hrefElectricidad">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Solicitar electricidad<br /><span
                                            style="font-size: 13px;">Request electricity</span></b></b></p>
                                @if ($viewData['vencimientos']['electricidad'])
                                    <div>
                                        <b>Cierre: {{ $viewData['vencimientos']['electricidad'] }}</b>
                                    </div>
                                @endif
                                <b>
                                    <!--<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p>-->
                                </b>
                            </div>
                        @endif

                        @if ($viewData['exposicion']['modulo_sala_conferencia'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                                onclick="location.href = hrefSalaConferencia">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Solicitud de sala de conferencias<br /><span
                                            style="font-size: 13px;">Conference room request</span></b></b></p>
                                @if ($viewData['vencimientos']['electricidad'])
                                    <div>
                                        <b>Cierre: {{ $viewData['vencimientos']['salaConferencia'] }}</b>
                                    </div>
                                @endif
                                <!--<b><p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p></b>-->
                                <b>
                                    <p>
                                        <!--<i class="fa fa-exclamation " aria-hidden="true"></i>
                                                        <span class="text-danger"> Vencimiento: 29 de Septiembre 2023</span>-->
                                    </p>
                                </b>
                            </div>
                        @endif

                        @if ($viewData['exposicion']['modulo_invitaciones'] == 1)
                            <div class="col shadow p-3 bh-white box-modulo text-center" style="cursor: pointer"
                                onclick="location.href = hrefInvitaciones">
                                <img src="{{ asset('images/form.png') }}" style="width: 100px;" />
                                <hr class="mx-4" />
                                <p class="m-0" style="font-size: 13px;"><b>Invitaciones<br /><span
                                            style="font-size: 13px;">Invitations</span></b></b></p>
                                @if ($viewData['vencimientos']['invitaciones'])
                                    <div>
                                        <b>Cierre: {{ $viewData['vencimientos']['invitaciones'] }}</b>
                                    </div>
                                @endif
                                <b>
                                    <!--<p class="text-danger"><i class="fa fa-exclamation" aria-hidden="true"></i><span> Falta Completar</span></p>-->
                                </b>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
