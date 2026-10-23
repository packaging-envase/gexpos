<div class="modal fade" id="empresaModal" tabindex="-1" aria-labelledby="empresaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <h2 class="text-center mb-2">
                        <u>{{ isset($viewData['empresa']) && $viewData['empresa']['razon_social'] ? $viewData['empresa']['razon_social'] : 'Nueva Empresa y Expositor' }}</u>
                    </h2>
                    @include('App.Expositor.modulo-create-empresa-expositor')
                </div>
                
            </div>
        </div>
    </div>
</div>
