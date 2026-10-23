<!doctype html>
@extends('layouts.app')

@section('content')
    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    

    <div class="container">

        @include('App.ModuloExpositor.header', ['title' => 'Solicitud de Proveedores Optativos', 'requerimientos' => false])

        @include('layouts.messages')

        <div class="expositor-modulo-invitaciones p-3 position-relative mt-3 mb-5"
            style="min-height: calc(100vh - 55px);">

            <div class="mt-2">
                @include('App.Public.proveedoresOptativosComponent')
            </div>
        </div>
    </div>
@endsection
