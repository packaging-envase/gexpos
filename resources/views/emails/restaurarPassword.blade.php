@component('mail::message')

**{{ $details['body'] }}**


<div style="text-align: center;">
    <a class="button button-primary" href="www.gexpos.com/reset-password/{{$details['token']}}">
        Restaurar Contraseña
    </a>
</div>

<div style="display: flex !important; space-between !important;">
    <img src="{{asset('storage/envase.png')}}" />
    <img src="{{asset('storage/etif.webp')}}" style="margin-left: 50px; margin-top: 20px; width: 100%; height: 100%;" />
</div>

@endcomponent