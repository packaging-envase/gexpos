@extends('layouts.app')

@section('content')

    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <div class="container">
        @include('layouts.messages')

        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

            <h2 class="mb-3">
                <u>Configuración</u>
            </h2>

            <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="logo_login" class="form-label">
                        Logo login PNG transparente
                    </label>

                    <input 
                        type="file" 
                        name="logo_login" 
                        id="logo_login" 
                        class="form-control"
                        accept="image/*"
                    >

                    @if (!empty($viewData['logo_login']))
                        <div class="mt-2">
                            <p class="mb-1">Logo actual:</p>
                            <img 
                                src="{{ asset('storage/' . $viewData['logo_login']) }}" 
                                alt="Logo exposición"
                                style="max-height: 120px;" 
                            />
                        </div>
                    @endif
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Guardar configuración
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection