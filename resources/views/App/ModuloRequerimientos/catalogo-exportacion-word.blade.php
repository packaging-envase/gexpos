@foreach ($expositores as $expositor)
    @if (count($expositor->stand))
        @foreach ($expositor->stand as $stand)
            <table width="100%">
                <tbody>
                    <tr>
                        <td style="font-size: 13px; border-top: 1px double black; border-bottom: 1px double black; padding-top: 15px; padding-bottom: 15px; border-right: none;">
                            <div><b>Stand</b></div>
                        </td>
                        <td style="text-align: right; font-size: 13px; border-top: 1px double black; border-bottom: 1px double black; padding-top: 15px; padding-bottom: 15px; border-left: none;">
                            <div><b>{{ $stand->numero }} | {{$expositor->pertenece_etif ? 'ETIF' : 'ENVASE'}}</b></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                            $nombreEmpresa = $stand['nombre_exposicion'] ? $stand['nombre_exposicion'] : 'razon_social';
                            $nombreEmpresa = $expositor->empresa[$nombreEmpresa];
                            $roles = [];
                            if ($expositor->empresa->fabricante) {
                                $roles[] = 'Fabricante';
                            }
                            if ($expositor->empresa->exportador) {
                                $roles[] = 'Exportador';
                            }
                            if ($expositor->empresa->importador) {
                                $roles[] = 'Importador';
                            }
                            if ($expositor->empresa->representante) {
                                $roles[] = 'Representante';
                            }
                            if ($expositor->empresa->distribuidor) {
                                $roles[] = 'Distribuidor';
                            }
                            ?>
                            <div style="font-size: 17px;"><b>{{ $nombreEmpresa }}</b></div>
                            <?php
                            $pais = null;
                            if ($expositor->expositorModuloCatalogo && $expositor->expositorModuloCatalogo->pais) {
                                $pais = $expositor->expositorModuloCatalogo->pais;
                            } elseif ($expositor->empresa->id_pais) {
                                $pais = \App\Models\Pais::find($expositor->empresa->id_pais)['name'];
                            } else {
                                $pais = $expositor->empresa->$pais;
                            }
                            ?>
                            <div style="font-size: 17px;"><b><?php echo trim($pais); ?></b></div>
                            <div style="font-size: 13px;">
                                {{ $expositor->expositorModuloCatalogo->provincia ?? '' }}</div>
                            <div style="font-size: 13px;">
                                {{ $expositor->expositorModuloCatalogo->localidad ?? '' }}</div>
                            <div style="font-size: 13px;">
                                {{ $expositor->expositorModuloCatalogo->direccion ?? '' }}</div>
                            <div style="font-size: 13px;">
                                {{ $expositor->expositorModuloCatalogo->codigo_postal ?? '' }}</div>
                            <div style="font-size: 13px;">{{ $expositor->empresa->email ?? '' }}</div>
                            <div style="font-size: 13px;">{{ $expositor->empresa->web ?? '' }}</div>
                            <div style="font-size: 13px;">
                                @if ($expositor->expositorModuloCatalogo)
                                    @if ($expositor->expositorModuloCatalogo->telefono && $expositor->expositorModuloCatalogo->whatsapp)
                                        Tel: {{ $expositor->expositorModuloCatalogo->telefono }} / Wsp: {{$expositor->expositorModuloCatalogo->whatsapp}}
                                    @else 
                                        {{ $expositor->expositorModuloCatalogo->telefono ? 'Tel: '.$expositor->expositorModuloCatalogo->telefono : '' }}
                                        {{ $expositor->expositorModuloCatalogo->whatsapp ? 'Wsp: '.$expositor->expositorModuloCatalogo->whatsapp : ''}}
                                    @endif
                                @endif
                            </div>
                            <br>
                            <div>{{ implode(' - ', $roles) }}</div>                            
                            <div style="font-size: 13px;">
                                {{ $expositor->empresa->descripcion ? strip_tags($expositor->empresa->descripcion) : '' }}
                            </div>
                            @if ($expositor->empresa->descripcion_ingles)
                                <div>----</div>
                                <div style="font-size: 13px;">
                                    {{ $expositor->empresa->descripcion_ingles ? strip_tags($expositor->empresa->descripcion_ingles) : '' }}
                                </div>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
        @endforeach
    @endif
@endforeach