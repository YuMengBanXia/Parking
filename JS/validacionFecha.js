document.addEventListener('DOMContentLoaded', function() {
    const inicio = document.getElementById('fecha_ini');
    const fin    = document.getElementById('fecha_fin');
  
    // 1) Al cambiar la fecha de inicio:
    inicio.addEventListener('change', () => {
        // a) Parsear fecha de inicio a objeto Date
        const startDate = new Date(inicio.value);
        // b) Calcular fecha mínima de fin = inicio + 2 horas
        const minEnd = new Date(startDate.getTime() + 2 * 60 * 60 * 1000);
        // c) Formatear a "YYYY-MM-DDThh:mm"
        fin.min = minEnd.toISOString().slice(0, 16);
        // d) Si el usuario ya seleccionó fin < min, lo limpiamos
        if (fin.value && new Date(fin.value) < minEnd) {
            fin.value = '';
        }
    });
  
    // 2) Al cambiar la fecha de fin, validaciones adicionales:
    fin.addEventListener('change', () => {
        const startDate = new Date(inicio.value);
        const endDate   = new Date(fin.value);

        // a) Fin debe ser posterior a inicio
        if (endDate < startDate) {
            alert('La fecha de fin debe ser posterior a la de inicio');
            fin.value = '';
            return;
        }

        // b) Duración mínima = 2 horas
        if ((endDate - startDate) < 2 * 60 * 60 * 1000) {
            alert('La reserva debe durar al menos 2 horas');
            fin.value = '';
        }
    });
});
  