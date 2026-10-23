@extends('layouts.app')

@section('content')
<style>
    .dt-buttons {
        display: flex;
    }
    .dt-input {
        margin-left: 0.5rem;
    }
</style>
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])
    @include('layouts.messages')

    <script src="{{ asset('js\tinymce\tinymce\tinymce.min.js') }}"></script>

    <div class="mx-2">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">
            @include('App.Expositor.modulo-create-empresa-expositor')
        </div>
    </div>
@endsection