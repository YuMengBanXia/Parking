import ParkingController from './ParkingController.js';

document.addEventListener('DOMContentLoaded', () => {
    if (!navigator.geolocation) {
        document.querySelectorAll('.distancia').forEach(el => {
            el.textContent = 'Geolocalización no soportada';
        });
        return;
    }

    const app = new ParkingController();
    app.init();
});