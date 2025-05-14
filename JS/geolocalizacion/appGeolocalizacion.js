import ParkingController from 'ParkingController.js';

document.addEventListener('DOMContentLoaded', () => {
    // Verificar compatibilidad
    if (!navigator.geolocation) {
        document.querySelectorAll('.distancia').forEach(el => {
            el.textContent = 'Geolocalización no soportada';
        });
        return;
    }

    // Inicializar aplicación
    const app = new ParkingController();
    app.init();
});