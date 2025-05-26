const $buscarAlumno = document.getElementById('buscarAlumno');

function buscarAlumno(codigoAlumno) {
    // Validar que el campo no esté vacío
    if (!codigoAlumno) {
        alert('Por favor, ingrese un código de alumno.');
        return;
    }

    // Realizar la solicitud fetch
    fetch(`/asistencia/buscar-alumno?buscar=${codigoAlumno}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud.');
            }
            return response.json();
        })
        .then(data => {
            if (data.id_alumno) {
                // Llenar el campo oculto con el ID del alumno
                document.getElementById('fkalumno').value = data.id_alumno;

                // Mostrar el nombre completo del alumno
                document.getElementById('nombre_alumno').value = data.nombre_completo;

                // Hacer el campo de código de alumno de solo lectura
                document.getElementById('alum_codigo').readOnly = true;
            } else {
                // Limpiar los campos si no se encuentra el alumno
                document.getElementById('fkalumno').value = '';
                document.getElementById('nombre_alumno').value = '';
                document.getElementById('alum_codigo').readOnly = false;
                alert('Alumno no encontrado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // alert('Hubo un error al buscar el alumno. Por favor, inténtelo de nuevo.');
        });
}

if ($buscarAlumno) {
    $buscarAlumno.addEventListener('click', function () {
        const codigoAlumno = document.getElementById('alum_codigo').value;
        buscarAlumno(codigoAlumno);
    });
}

