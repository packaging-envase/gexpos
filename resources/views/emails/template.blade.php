@component('mail::message')
# {{$details['title']}}

**{{ $details['body'] }}**

@component('mail::button', ['url' => 'https://gexpos.com/'])
Ingresar
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent