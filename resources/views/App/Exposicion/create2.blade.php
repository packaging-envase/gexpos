@extends('layouts.app')

@section('content')
    @include('layouts.navigation-menu-2', ['active' => 'exposicion'])

    <script type="text/javascript">
        var exportedMapjson = null;

        var mapaJson = @json($viewData['exposicion']['mapa']);
        var urlMapa = @json($viewData['exposicion']['url_mapa']);

        var mapaUpdate = (form) => {
            axios.post("{{ route('api.mapa.update', ['idExposicion' => $viewData['exposicion']['id']]) }}", form)
                .then(response => {
                    
                    if (response.status == 200) {
                        
                        alert('Mapa guardado con éxito!')
                        window.top.location.reload();
                    } else {
                        alert('Hubo un error al guardar el mapa!')
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        }

        var mapaGetSaved = () => {
            return new Promise((resolve, reject) => {
                axios.get("{{ route('api.mapa.get', ['idExposicion' => $viewData['exposicion']['id']]) }}")
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        }
    </script>
    <style>
        .iframe-container {
            width: 100%;
            height: 700px;
            border: 1px solid #ccc;
        }
    </style>

    <div class="container">
        <div class="shadow p-3 mb-5 mt-1 bg-white rounded">

        
            <h2 class="mb-3">
                <u>Carga de Planos y Stands</u>
            </h2>
            
            @include('layouts.messages')
            <img class="ms-4 shadow-sm p-4 mb-2 bg-white" src="{{ asset('ImageMapPro\explicacionHeading.png') }}"
                width="500" />


            <!--
                <button type="button" class="btn btn-primary my-2">Asignar Stand</button>
                -->
            <!--
                <button class="btn btn-success" onclick="$('#planoImagen').click()">Subit Plano Imagen</button>
                <input hidden type="file" id="planoImagen" />
                -->

            <div class="iframe-container">
                <iframe id="mapIframe" src="{{ asset('ImageMapPro\editor\index.html') }}" frameborder="0" width="100%"
                    height="100%"></iframe>
            </div>

            <a class="btn btn-success float-end mt-2"
                href="{{ route('exposicion.create-3', ['id' => $viewData['exposicion']['id']]) }}">Siguiente</a>
            <div style="clear: both;"></div>

        </div>

    </div>

    <script type="text/javascript">
        var iframe = document.getElementById('mapIframe');
        iframe.contentWindow.mapaUpdate = mapaUpdate;
        iframe.contentWindow.mapaGetSaved = mapaGetSaved;
        
        $(document).ready(function() {
            if (mapaJson) {
                mapaJson = JSON.parse(mapaJson);
            }
            
            if (!mapaJson || mapaJson.artboards[0].image_url != urlMapa) {
                console.log('asd');
                // Esperar a que el iframe esté completamente cargado
                $('#mapIframe').on('load', function() {
                    // Acceder al contenido del iframe
                    var iframeContent = $(this).contents();

                    // Seleccionar el div con las clases especificadas y simular un clic
                    iframeContent.find('div.fa-solid.fa-save.mb-1.text-base').click();
                });
            }
        });
        /*
                .then(response => {
                    //window.imageMapProConfig = JSON.parse(response.data);
                    //console.log('Guardando: ', response.data);
                    return response.data;
                    //$("#image-map-pro-editor").importSettings(JSON.parse(response.data));
                    
                    window.imageMapProConfig = response.data;
                    window.localStorage.imageMapProSaves = JSON.stringify([response.data])
                    window.localStorage.imageMapProLastSave = response.data.id
                    
                })
                .catch(error => {
                    console.log(error);
                });
        }
        */
        /*
            $(document).ready(function() {
                mapaSetSaved().then(data => console.log(JSON.parse(data)));

            });
            */
    </script>
@endsection
