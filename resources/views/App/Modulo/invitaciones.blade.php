@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script src="{{asset('js\tinymce\tinymce\tinymce.min.js')}}"></script>

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white">

            <div class="container">
                <h2 class="mb-3">
                    <u>Modulo - Invitaciones</u>
                </h2>
                
                @include('layouts.messages')

                <form method="POST" action="{{route('modulo.invitacion.update-store')}}">
                    @csrf
                    <input hidden name="idExposicion" value="{{$viewData['exposicion']['id']}}" />

                    <label>Fecha de Cierre</label>
                    <input class="form-control" type="date" value="{{$viewData['exposicion']['invitacion']['cierre']}}" name="cierre" style="width: fit-content;"/>

                    <label class="mt-2">Texto Descriptivo</label>
                    <textarea id="mytextarea" name="descripcion">
                        @if ($viewData['exposicion']['invitacion'])
                            {{$viewData['exposicion']['invitacion']['descripcion']}}
                        @else
                            <p>&nbsp;Las inivtaciones se retiran en Av. Jujuy 425, CABA, de lues a viernes de 10hs a 16hs, en d&iacute;as y horarios a coordinar a partir del 1 de agosto de 2023.</p>
                            <p>&nbsp;De acuerdo al reglamento, se asignan 6 invitaciones por m2 hasta un m&aacute;ximo de 600 invitaciones.</p>
                            <p><strong>&nbsp;Para mayor informacion escribir a admin.ventas@envase.org asunto "INVITACIONES".</strong></p>
                            <p>&nbsp;En breve proveeremos un link de descarga de invitaciones digitales a trav&eacute;s de este m&oacute;dulo, donde, adem&aacute;s de la invitaci&oacute;n digital, encontrar&aacute;n otras piezas para difusi&oacute;n. Invitations are withdrawn at Av. Jujuy 425, CABA, from Monday to Friday from 10 a.m. to 4 p.m., on days and times to be coordinated as of August, 1.</p>
                            <p>&nbsp;According to the regulations, 6 invitations are assigned per m2 up to a maximum of 600 invitations.</p>
                            <p>&nbsp;For more information write to admin.ventas@envase.org subject "INVITATIONS".</p>
                            <p>&nbsp;Soon we will provide a download link for digital invitations through this module, where, in addition to the digital invitation, you will find other pieces for diffusion in social networks and mailing</p>
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
    </div>
@endsection
