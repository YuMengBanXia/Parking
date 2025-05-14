import GeoService from 'GeoService.js';
import MapService from 'MapService.js';
import ParkingView from 'ParkingView.js';

class ParkingController {
    constructor() {
        this.view = new ParkingView();
        this.geoService = new GeoService();
        this.mapService = new MapService();
    }

    async init() {
        try {
            // 1. Obtener ubicación del usuario
            const userCoords = await this.geoService.getUserLocation();
            
            // 2. Procesar cada parking
            const parkingRows = this.view.getParkingRows();
            await this.processParkings(parkingRows, userCoords);
            
        } catch (error) {
            this.view.showGeolocationError(error);
        }
    }

    async processParkings(rows, userCoords) {
        for (const [index, row] of rows.entries()) {
            try {
                this.view.showLoadingState(row);
                
                // 1. Geocodificar dirección
                const address = this.view.getParkingAddress(row);
                const parkingCoords = await this.geoService.geocode(address);
                
                if (!parkingCoords) {
                    this.view.showGeocodingError(row);
                    continue;
                }
                
                // 2. Calcular distancia
                const distance = this.geoService.calculateDistance(userCoords, parkingCoords);
                this.view.updateDistance(row, distance);
                
                // 3. Mostrar mapa
                this.mapService.initMiniMap(row, userCoords, parkingCoords);
                
            } catch (error) {
                this.view.showProcessingError(row, error);
            }
        }
    }
}

export default ParkingController;