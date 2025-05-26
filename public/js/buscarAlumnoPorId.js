if (fkalumno && fkalumno.value) {
    // Realizar fetch por ID para rellenar campos automáticamente al cargar la página
    fetch(`/asistencia/buscar-alumno-id?id=${fkalumno.value}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al buscar alumno por ID.');
            }
            return response.json();
        })
        .then(data => {
            if (data.id_alumno) {
                document.getElementById('alum_codigo').value = data.alum_codigo;
                document.getElementById('nombre_alumno').value = data.nombre_completo;
                document.getElementById('alum_codigo').readOnly = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}