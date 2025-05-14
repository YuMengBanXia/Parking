class ParkingView {
    constructor() {
        this.DISTANCE_TEXT = {
            loading: 'Calculando...',
            error: 'Error',
            geolocationError: 'Activa la geolocalización'
        };
        this.initSortButton();
    }

     initSortButton() {
        const sortBtn = document.createElement('button');
        sortBtn.id = 'sortButton';
        sortBtn.className = 'btn-sort';
        sortBtn.textContent = 'Mostrar parking más cercano';
        sortBtn.addEventListener('click', () => this.triggerSortEvent());
        
        const tableContainer = document.querySelector('.tabla-responsive');
        tableContainer?.insertAdjacentElement('beforebegin', sortBtn);
    }

    triggerSortEvent() {
        const event = new CustomEvent('sortParkings', { 
            detail: { sortBy: 'distance' } 
        });
        document.dispatchEvent(event);
    }

    renderSortedRows(rows) {
        const tbody = document.querySelector('#tablaParkings tbody');
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    }
    
    getParkingRows() {
        return Array.from(document.querySelectorAll('#tablaParkings tr[data-direccion]'));
    }

    getParkingAddress(row) {
        return row.dataset.direccion;
    }

    showLoadingState(row) {
        row.querySelector('.distancia').textContent = this.DISTANCE_TEXT.loading;
        row.querySelector('.mini-mapa').innerHTML = '<div class="map-loading">Cargando...</div>';
    }

    updateDistance(row, distance) {
        row.querySelector('.distancia').textContent = `${distance.toFixed(2)} km`;
    }

    showGeocodingError(row) {
        row.querySelector('.distancia').textContent = this.DISTANCE_TEXT.error;
        row.querySelector('.mini-mapa').textContent = '--';
    }

    showProcessingError(row, error) {
        console.error('Processing error:', error);
        row.querySelector('.distancia').textContent = this.DISTANCE_TEXT.error;
        row.querySelector('.mini-mapa').textContent = '--';
    }

    showGeolocationError(error) {
        console.error('Geolocation error:', error);
        document.querySelectorAll('.distancia').forEach(el => {
            el.textContent = this.DISTANCE_TEXT.geolocationError;
        });
        document.querySelectorAll('.mini-mapa').forEach(el => {
            el.textContent = '--';
        });
    }
}

export default ParkingView;