    // Función para cerrar la alerta
    function closeAlert() {
        const alert = document.getElementById('alertBox');
        const backdrop = document.getElementById('alertBackdrop');
        const warning = document.getElementById('warningBox');


        if (alert && backdrop) {
            // Añadir animación de salida
            alert.classList.add('fade-out');
            backdrop.classList.add('backdrop-fade-out');

            // Eliminar los elementos después de que termine la animación
            setTimeout(() => {
                alert.style.display = 'none';
                backdrop.style.display = 'none';
            }, 300);
        }

        if (warning) {
            // Añadir animación de salida
            warning.classList.add('slide-out-right');

            // Eliminar el elemento después de que termine la animación
            setTimeout(() => {
                if (warning.parentNode) warning.parentNode.removeChild(warning);
            }, 300);
        }
    }

    // Función para iniciar el temporizador de cierre automático
    function startAlertTimer() {
        // Auto-cerrar después de 5 segundos
        setTimeout(function() {
            closeAlert();
        }, 5000);
    }


