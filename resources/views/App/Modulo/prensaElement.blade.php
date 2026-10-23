<div style="margin-top: 100px;">
    <h3 class="text-center mb-5">
        <?php echo $prensa['titulo']; ?>
    </h3>

    <?php echo $prensa['texto']; ?>

    <div class="text-secondary">
        <?php echo $prensa['contacto']; ?>
    </div>

    <div class="image-container">
        @foreach ($prensa->moduloImagen as $item)
            <div class="image-item p-2 border">
                <img src="{{ asset('storage/prensa/imagen/' . $item['path']) }}" alt="Imagen">
            </div>
        @endforeach
    </div>

    @if ($prensa['moduloArchivo'])
        <a href="{{ asset('storage/prensa/archivo/' . $prensa['moduloArchivo']['path']) }}" target="_blank">PDF DESCARGABLE</a>
    @endif

    <script>
        $(document).ready(function() {
            function adjustImageSize() {
                var containerWidth = $('.image-container').width();
                var itemsPerRow = 5;
                var itemWidth = containerWidth / itemsPerRow;

                // Asigna el nuevo tamaño de cada item
                $('.image-item').css('flex', '1 0 ' + itemWidth + 'px');
            }

            // Ajustar el tamaño al cargar la página y cuando se cambia el tamaño de la ventana
            adjustImageSize();
            $(window).resize(function() {
                adjustImageSize();
            });
        });
    </script>

    <style>
        /* Contenedor de las imágenes */
        .image-container {
            display: flex;
            flex-wrap: wrap;
            /* Espaciado entre imágenes */
        }

        /* Estilo para cada imagen */
        .image-item {
            flex: 1 0 19%;
            /* Cada imagen tendrá un máximo de 5 imágenes por fila, 100% dividido entre 5 es 20%, y 1% de margen */
            box-sizing: border-box;
        }

        /* Aseguramos que las imágenes no se distorsionen */
        .image-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    </style>
</div>