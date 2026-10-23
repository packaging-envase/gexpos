@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])    

    <div class="container pb-4">
        <div class="shadow p-3 mt-1 bg-white">

        <h2 class="mb-3">
            <u>Requerimientos - Planos de Stand</u>
        </h2>
        
        @include('layouts.messages')

        <div class="mt-2">
            <?php echo $viewData['exposicion']->planoStand['descripcion']; ?>
        </div>
    </div>
@endsection
