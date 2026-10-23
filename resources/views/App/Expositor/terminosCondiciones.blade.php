@extends('layouts.app')

@section('content')

<div class="container mt-4">
    
    <div class="shadow bg-white border p-4">
        <h5 class="text-center py-1 m-0 mb-2"><u>Términos y Condiciones</u></h5>

        @include('layouts.messages')
        
        <div class="d-flex">
            <a href="{{ asset($viewData['exposicion']['path_terminos_condiciones']) }}" target="_blank">
                Términos y Condiciones
            </a>
            <a href="{{ asset($viewData['exposicion']['path_terms_conditions']) }}" target="_blank" class="ms-2">
                Terms & Conditions
            </a>
        </div>
        <br/>
        <a class="btn btn-success" href="{{route('expositor.panel-expositor', ['acepta_terminos_condiciones' => 'true'])}}">Aceptar</a>
    </div>
</div>

@endsection