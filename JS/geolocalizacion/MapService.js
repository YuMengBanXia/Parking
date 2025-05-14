class MapService {
    constructor() {
        this.USER_ICON = 'https://cdn-icons-png.flaticon.com/512/149/149060.png';
        this.PARKING_ICON = 'https://cdn-icons-png.flaticon.com/512/484/484167.png';
    }

    initMiniMap(rowElement, userCoords, parkingCoords) {
        const container = rowElement.querySelector('.mini-mapa');
        container.innerHTML = '';
        
        const map = L.map(container, {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Custom icons
        const userIcon = L.icon({
            iconUrl: this.USER_ICON,
            iconSize: [25, 25]
        });

        const parkingIcon = L.icon({
            iconUrl: this.PARKING_ICON,
            iconSize: [25, 25]
        });

        // Add markers
        L.marker([userCoords.lat, userCoords.lon], { icon: userIcon })
            .addTo(map)
            .bindPopup('Tu ubicación');

        L.marker([parkingCoords.lat, parkingCoords.lon], { icon: parkingIcon })
            .addTo(map)
            .bindPopup('Parking');

        // Add connection line
        L.polyline([
            [userCoords.lat, userCoords.lon],
            [parkingCoords.lat, parkingCoords.lon]
        ], { color: '#3498db', weight: 3 }).addTo(map);

        // Fit bounds
        map.fitBounds([
            [userCoords.lat, userCoords.lon],
            [parkingCoords.lat, parkingCoords.lon]
        ], { padding: [20, 20] });

        return map;
    }
}

export default MapService;