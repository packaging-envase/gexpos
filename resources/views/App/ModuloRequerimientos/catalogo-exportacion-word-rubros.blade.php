<table width="100%">
    <tbody>
        @foreach ($rubros as $keyRubro => $rubro)
            <tr>
                <td colspan="3" style="font-size: 18px;"><b>{{ $rubro['nombre'] }}</b></td>
            </tr>
            @foreach ($rubros[$keyRubro]['expositores'] as $expositorR)
                <tr style="font-size: 14px;">
                    <td>{{ $expositorR['nombre'] }}</td>
                    <td>{{ $expositorR['stands'] }}</td>
                    <td>{{ $expositorR['pertenece_etif'] ? 'ETIF' : 'Envase' }}</td>
                </tr>
            @endforeach
            @foreach ($rubros[$keyRubro]['subRubros'] as $keySubRubro => $subRubro)
                <tr>
                    <td style="font-size: 13px;"><b>{{ $subRubro['nombre'] }}</b></td>
                    <td></td>
                </tr>
                @foreach ($rubros[$keyRubro]['subRubros'][$keySubRubro]['expositores'] as $keyExpositor => $expositor)
                    <tr style="font-size: 14px;">
                        <td>{{ $expositor['nombre'] }}</td>
                        <td>{{ $expositor['stands'] }}</td>
                        <td>{{ $expositor['pertenece_etif'] ? 'ETIF' : 'ENVASE' }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
