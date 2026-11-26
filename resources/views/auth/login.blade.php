@if(session('token_expired'))
    <script>
        window.location.reload();
    </script>
@endif

@extends('layouts.app')

@section('content')
    <style>
        .link-span {
            color: #007bff;
            /* Color azul similar a un enlace */
            text-decoration: underline;
            /* Subrayado para simular un enlace */
            cursor: pointer;
            /* Cursor de mano al pasar el mouse */
        }

        .link-span:hover {
            color: #0056b3;
            /* Color más oscuro al pasar el mouse */
            text-decoration: none;
            /* Quitar subrayado en hover */
        }
    </style>
    <div class="container mt-4">
        @include('layouts.messages')
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body bg-white">
                        <div class="d-flex justify-content-evenly">
                            <img src="{{ asset('storage/' . $viewData['logo_login']) }}" style="max-width: 100%;"
                            style="" />
                        </div>
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

                                <div class="col-md-6">
                                    <input id="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4 text-center">
                                    <span class="link-span" onclick="resetPassword()">Olvidé la contraseña</span>   
                                    <script>
                                        function resetPassword() {
                                            const email = $('#email').val();
                                            if (email) {

                                                $('#usuario').val(email);
                                                $('#formResetPassword').submit();
                                            } else {
                                                alert('Por favor, ingrese su correo electrónico.');
                                            }
                                        }
                                    </script>

                                    <div class="form-group text-center mt-2">
                                        <div style="display:inline-block">
                                            {!! NoCaptcha::display() !!}
                                        </div>

                                        @error('g-recaptcha-response')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {!! NoCaptcha::renderJs() !!}


                                    <!--
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    -->
                                </div>
                            </div>
                            <div class="row mb-3 mt-1">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-6 text-center">
                                    <b>
                                        <button type="submit" class="btn btn-primary" style="background-color: green;">
                                            {{ __('Login') }}
                                        </button>
                                    </b>
                                </div>
                            </div>
                            <!--
                                <div class="row mb-3">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            -->
                        </form>
                        <form id="formResetPassword" method="POST" action="{{ route('reset-password') }}">
                            @csrf
                            <input id="usuario" name="usuario" hidden/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
