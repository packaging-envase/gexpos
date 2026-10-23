<!doctype html>
@extends('layouts.app')

@section('content')
    <div class="container">

        @include('App.ModuloExpositor.header', ['title' => 'Documentos Importantes', 'requerimientos' => false])
        
        <div class="mt-3 expositor-modulo-invitaciones shadow-sm p-3 bg-white border mb-5">

            <h4 class=""><u>Documentos Importantes</u></h4>

            @include('layouts.messages')

            @if (isset($viewData['exposicion']) && $viewData['exposicion']->exposicionArchivo->isNotEmpty())
                
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                    @foreach ($viewData['exposicion']->exposicionArchivo as $archivo)
                        <div class="col">
                            <div class="card h-100 shadow-sm text-center border-0 {{ $archivo->importante ? 'border border-warning' : '' }}">
                                <div class="card-body d-flex flex-column justify-content-center position-relative">

                                    @if ($archivo->importante)
                                        <i class="fa fa-star position-absolute"
                                        style="top: 10px; right: 10px; color: #029a75;"
                                        aria-hidden="true"></i>
                                    @endif

                                    <div class="mb-2">
                                        <i class="fa fa-file-text-o fa-2x {{ $archivo->importante ? '' : 'text-primary' }}"
                                            style="{{$archivo->importante ? 'color: #029a75;' : ''}}"
                                        aria-hidden="true"></i>
                                    </div>

                                    <a href="{{ asset($archivo->path) }}"
                                    target="_blank"
                                    class="stretched-link text-decoration-none fw-semibold">
                                        {{ $archivo->tipo }}
                                    </a>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

            @else
                <p>No hay archivos disponibles.</p>
            @endif
        </div>
    </div>
@endsection