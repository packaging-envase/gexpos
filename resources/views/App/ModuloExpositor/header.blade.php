<div class="shadow-sm container position-relative p-3 bg-white mt-3 border">            
    <div class="position-absolute" style="width: fit-content;">
        @if (Auth::guard('expositor')->check())
            <a 
                class="btn btn-primary" 
                style="background-color: #005978; border-color: black;"
                href="{{ route('expositor.panel-expositor') }}">
                Menú Principal
            </a>
        @else
            <button 
                class="btn btn-primary" 
                style="background-color: #005978; border-color: black;"
                onclick="history.back();"
                >
                <- Volver
            </button>
        @endif
    </div>
    <h2 class="text-center">
        <u>{{ $viewData['exposicion']['nombre'] }} {{$requerimientos && !Auth::guard('expositor')->check() ? '- Requerimientos' : ''}}- {{$title}}</u>
    </h2>
    <h5 class="text-center">
        <b>Expositor: {{ $viewData['empresa']['pertenece_etif'] ? 'ETIF 2025' : 'Envase 2025' }}</b>
    </h5>
</div>