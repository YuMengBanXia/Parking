class GeoService {
    constructor() {
        this.GEOCODING_URL = 'https://nominatim.openstreetmap.org/search';
    }

    getUserLocation() {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                position => resolve({
                    lat: position.coords.latitude,
                    lon: position.coords.longitude
                }),
                error => reject(error),
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    }

    async geocode(address) {
        try {
            const response = await fetch(`${this.GEOCODING_URL}?q=${encodeURIComponent(address)}&format=json`, {
                headers: { 'User-Agent': 'ParkingApp/1.0 (contact@example.com)' }
            });
            
            const data = await response.json();
            return data.length ? { 
                lat: parseFloat(data[0].lat), 
                lon: parseFloat(data[0].lon) 
            } : null;
            
        } catch (error) {
            console.error('Geocoding error:', error);
            return null;
        }
    }

    calculateDistance(coord1, coord2) {
        const R = 6371; // Earth's radius in km
        const dLat = this.deg2rad(coord2.lat - coord1.lat);
        const dLon = this.deg2rad(coord2.lon - coord1.lon);
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(this.deg2rad(coord1.lat)) * Math.cos(this.deg2rad(coord2.lat)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    deg2rad(deg) {
        return deg * (Math.PI/180);
    }
}

export default GeoService;