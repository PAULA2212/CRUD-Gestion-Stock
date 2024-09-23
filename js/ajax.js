const enviar_formulario_ajax = (event) => {
    event.preventDefault(); // Previene el envío del formulario tradicional

    let confirmar = confirm('¿Deseas enviar el fichero?'); // Confirmación del usuario

    if (confirmar) {
        // Crear un objeto FormData con los datos del formulario
        let data = new FormData(event.target);

        // Obtener el método y la acción del formulario
        let method = event.target.getAttribute("method");
        let action = event.target.getAttribute("action");

        // Configuración de la solicitud fetch
        let config = {
            method: method,
            body: data,
            mode: 'cors', // o 'same-origin', según la configuración del servidor
            cache: 'no-cache'
        };

        // Realizar la solicitud fetch
        fetch(action, config)
            .then(respuesta => respuesta.text())
            .then(respuesta => {
                let contenedor = document.querySelector('.form-rest');
                contenedor.innerHTML = respuesta;
            })
            .catch(error => {
                console.error('Error:', error); // Manejo de errores
            });
    }
};

// Seleccionar todos los formularios con la clase "formularioAjax"
const formulario_ajax = document.querySelectorAll(".formularioAjax");

// Añadir el evento submit a cada formulario
formulario_ajax.forEach(formulario => {
    formulario.addEventListener("submit", enviar_formulario_ajax);
});