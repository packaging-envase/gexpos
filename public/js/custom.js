import axios from 'axios';

// Función para la solicitud GET
module.exports.mapaUpdate = function (form) {
    return new Promise((resolve, reject) => {
        axios.get("{{route('api.mapa.update')", form)
            .then(response => {
                resolve(response.data);
            })
            .catch(error => {
                reject(error);
            });
    });
}