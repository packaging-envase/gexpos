<!doctype html>
@extends('layouts.app')

@section('content')
    <div class="container">
        @include('App.ModuloExpositor.header', [
            'title' => 'Material de Difusión',
            'requerimientos' => false,
        ])

        <div class="mt-3 expositor-modulo-invitaciones shadow-sm p-3 bg-white border mb-5"
            style="min-height: calc(100vh - 55px);">

            @include('layouts.messages')

            <table class="table">
                @foreach ($viewData['difusiones'] as $difusion)
                    <tr>
                        <td style="width: 15%;">
                            {{ $difusion['tipo'] }}
                        </td>
                        <td style="width: 70%;" class="text-center">
                            <img src="{{ asset($difusion->path_muestra) }}" style="max-width: 100%;"/>
                        </td>
                        <td style="width: 15%;">
                            <a href="{{ asset($difusion->path) }}" target="_blank" download>
                                Descargar
                            </a>
                        </td>
                    </tr>
                @endforeach                
            </table>
        </div>
    </div>
@endsection