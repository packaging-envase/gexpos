function validateFile(input, elImg, maxSizeMB) {
    var file = input.files[0];
    if (file && file.type.split('/')[0] !== 'image') {
        alert("Por favor, selecciona solo archivos de imagen");
        input.value = "";
        return false;
    }

    var sizeInBytes = file.size;
    var maxSizeBytes = maxSizeMB * 1024 * 1024; // Convertir MB a Bytes
    if (sizeInBytes > maxSizeBytes) {
        var maxSizeMBWithDecimal = maxSizeMB.toFixed(1).replace('.', ','); // Cambiar el punto decimal a coma
        alert("Tamaño máximo de " + maxSizeMBWithDecimal + "MB superado.");
        input.value = "";
        return false;
    }
    var input = event.target;
    var reader = new FileReader();

    reader.onload = function() {
        var imgPreview = document.getElementById(elImg);
        imgPreview.src = reader.result;
    };

    reader.readAsDataURL(file);
    return true;
}