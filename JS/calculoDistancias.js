// js/calculoDistancias.js
function calcularDistancia(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radio de la Tierra en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    return R * c;
}

async function geocodificar(direccion) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(direccion)}&format=json`);
        const data = await response.json();
        if(data.length > 0) {
            return {
                lat: parseFloat(data[0].lat),
                lon: parseFloat(data[0].lon)
            };
        }
        return null;
    } catch (error) {
        console.error('Error en geocodificación:', error);
        return null;
    }
}

async function actualizarDistancias(userLat, userLon) {
    const filas = document.querySelectorAll('#tablaParkings tr[data-direccion]');
    
    for(const fila of filas) {
        try {
            const direccion = fila.dataset.direccion;
            const coords = await geocodificar(direccion);
            
            if(coords) {
                const distancia = calcularDistancia(
                    userLat, userLon, 
                    coords.lat, coords.lon
                );
                fila.querySelector('.distancia').textContent = distancia.toFixed(2) + ' km';
            } else {
                fila.querySelector('.distancia').textContent = 'Dirección no encontrada';
            }
        } catch (error) {
            fila.querySelector('.distancia').textContent = 'Error calculando';
        }
    }
}

function initGeolocalizacion() {
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async position => {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;
                await actualizarDistancias(userLat, userLon);
            },
            error => {
                document.querySelectorAll('.distancia').forEach(td => {
                    td.textContent = 'Activa la geolocalización';
                });
            },
            { timeout: 10000 }
        );
    } else {
        document.querySelectorAll('.distancia').forEach(td => {
            td.textContent = 'Navegador no compatible';
        });
    }
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initGeolocalizacion);