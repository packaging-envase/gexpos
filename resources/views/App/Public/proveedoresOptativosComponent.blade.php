<div class="">
    @foreach ($viewData['optativos'] as $optativo)
        <div class="" style="margin-bottom: 20px;">
            <strong style="color: #2D5C88;">{{ $optativo['nombre'] }}</strong>
            @if ($optativo['logo'])
                <br />
                <img src="{{ asset('storage/serviciosOptativos/logos/' . $optativo['logo']) }}" alt="Logo"
                    style="max-width: 200px;" />
                <br />
            @endif
            {!! $optativo['descripcion'] !!}
            @if (isset($optativo->moduloServiciosOptativosFile) && count($optativo->moduloServiciosOptativosFile))
                @foreach ($optativo->moduloServiciosOptativosFile as $file)
                    <a target="_blank"
                        href="{{ route('expositor.modulo.servicios-optativos.download-file', ['path' => $file->path]) }}">
                        {{ $file->name }}
                    </a>
                    <br />
                @endforeach
            @endif
            <hr />
        </div>
    @endforeach
</div>