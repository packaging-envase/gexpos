<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
    <div>
        <div id="image-map-pro"></div>
    </div>

    <script src="{{ asset('ImageMapPro\client\main.js') }}"></script>
    <script>
        async function openModalAsignarStand() {};
        $(document).ready(function() {
            let jsonMapa = JSON.parse(@json($viewData['mapa']));
            ImageMapPro.init('#image-map-pro', jsonMapa);
        });
    </script>
</body>

</html>
