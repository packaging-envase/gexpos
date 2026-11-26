@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'empresas'])

    <div class="container shadow mb-5 bg-white py-2">
        @include('layouts.messages')
        @include('App.Empresa.create-1')
    </div>
    
@endsection
