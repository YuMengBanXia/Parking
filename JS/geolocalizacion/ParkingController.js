import GeoService from './GeoService.js';
import MapService from './MapService.js';
import ParkingView from './ParkingView.js';

class ParkingController {
    constructor() {
        this.view = new ParkingView();
        this.geoService = new GeoService();
        this.mapService = new MapService();
        this.parkingData = [];
        this.setupEventListeners();
    }

    setupEventListeners() {
        document.addEventListener('sortParkings', (e) => {
            if (e.detail.sortBy === 'distance') {
                this.sortParkingsByDistance();
            }
        });
    }

    async processParkings(rows, userCoords) {
        this.parkingData = [];
        
        for (const [index, row] of rows.entries()) {
            try {
                this.view.showLoadingState(row);
                
                const address = this.view.getParkingAddress(row);
                const parkingCoords = await this.geoService.geocode(address);
                
                if (!parkingCoords) {
                    this.view.showGeocodingError(row);
                    continue;
                }
                
                const distance = this.geoService.calculateDistance(userCoords, parkingCoords);
                this.view.updateDistance(row, distance);
                this.mapService.initMiniMap(row, userCoords, parkingCoords);
                
                // Almacenamos datos para ordenación
                this.parkingData.push({
                    element: row,
                    distance: distance
                });
                
            } catch (error) {
                this.view.showProcessingError(row, error);
            }
        }
    }

    sortParkingsByDistance() {
        if (this.parkingData.length === 0) return;
        
        const sortedData = [...this.parkingData].sort((a, b) => a.distance - b.distance);
        const sortedRows = sortedData.map(item => item.element.cloneNode(true));
        
        this.view.renderSortedRows(sortedRows);
        
        // Reaplicar eventos si es necesario
        sortedRows.forEach(row => {
            const radioBtn = row.querySelector('input[type="radio"]');
            if (radioBtn) {
                radioBtn.addEventListener('change', this.handleParkingSelection.bind(this));
            }
        });
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
}

export default ParkingController;