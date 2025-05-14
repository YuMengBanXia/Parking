document.addEventListener('DOMContentLoaded', function() {
    // 1. Configuración inicial
    const ICONO_USUARIO = 'https://cdn-icons-png.flaticon.com/512/149/149060.png';
    const ICONO_PARKING = 'https://cdn-icons-png.flaticon.com/512/484/484167.png';
    const ESTILO_LINEA = {
        color: '#3498db',
        weight: 3,
        dashArray: '5, 5'
    };

    // 2. Función para calcular distancia (fórmula Haversine)
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

    // 3. Geocodificación usando Nominatim
    async function geocodificar(direccion) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(direccion)}&format=json`, {
                headers: {
                    'User-Agent': 'ParkingApp/1.0 (contacto@tudominio.com)'
                }
            });
            
            if (!response.ok) throw new Error('Error en la petición');
            const data = await response.json();
            
            if (data.length === 0) throw new Error('Dirección no encontrada');
            
            return {
                lat: parseFloat(data[0].lat),
                lon: parseFloat(data[0].lon)
            };
        } catch (error) {
            console.error('Error en geocodificación:', error);
            return null;
        }
    }

    // 4. Inicializar mini mapa con Leaflet
    function initMiniMapa(container, userCoords, parkingCoords) {
        // Configuración del mapa
        const mapa = L.map(container, {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            touchZoom: false,
            doubleClickZoom: false,
            boxZoom: false
        });

        // Capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(mapa);

        // Iconos personalizados
        const userIcon = L.icon({
            iconUrl: ICONO_USUARIO,
            iconSize: [25, 25],
            popupAnchor: [0, -10]
        });

        const parkingIcon = L.icon({
            iconUrl: ICONO_PARKING,
            iconSize: [25, 25],
            popupAnchor: [0, -10]
        });

        // Marcadores
        L.marker([userCoords.lat, userCoords.lon], {
            icon: userIcon
        }).addTo(mapa).bindPopup('Tu ubicación');

        L.marker([parkingCoords.lat, parkingCoords.lon], {
            icon: parkingIcon
        }).addTo(mapa).bindPopup('Parking');

        // Línea de conexión
        L.polyline([
            [userCoords.lat, userCoords.lon],
            [parkingCoords.lat, parkingCoords.lon]
        ], ESTILO_LINEA).addTo(mapa);

        // Ajustar vista para mostrar ambos puntos
        const bounds = L.latLngBounds([
            [userCoords.lat, userCoords.lon],
            [parkingCoords.lat, parkingCoords.lon]
        ]);
        mapa.fitBounds(bounds, { padding: [10, 10] });

        return mapa;
    }

    // 5. Función principal
    async function procesarParkings() {
        const filas = document.querySelectorAll('#tablaParkings tr[data-direccion]');
        if (filas.length === 0) return;

        try {
            // Obtener ubicación del usuario
            const posicion = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(
                    resolve, 
                    reject, 
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });

            const userCoords = {
                lat: posicion.coords.latitude,
                lon: posicion.coords.longitude
            };

            // Procesar cada parking
            for (const [index, fila] of Array.from(filas).entries()) {
                try {
                    // Retardo para cumplir con política de uso de Nominatim (1 solicitud/segundo)
                    if (index > 0) await new Promise(resolve => setTimeout(resolve, 1000));
                    
                    const direccion = fila.dataset.direccion;
                    const distanciaElement = fila.querySelector('.distancia');
                    const mapaContainer = fila.querySelector('.mini-mapa');
                    
                    distanciaElement.textContent = 'Calculando...';
                    mapaContainer.innerHTML = '<div class="loading-mapa">Cargando mapa...</div>';

                    const coords = await geocodificar(direccion);
                    
                    if (!coords) {
                        distanciaElement.textContent = 'Error dirección';
                        mapaContainer.innerHTML = '--';
                        continue;
                    }

                    // Calcular distancia
                    const distancia = calcularDistancia(
                        userCoords.lat, 
                        userCoords.lon,
                        coords.lat,
                        coords.lon
                    );
                    
                    // Actualizar UI
                    distanciaElement.textContent = `${distancia.toFixed(2)} km`;
                    mapaContainer.innerHTML = ''; // Limpiar loading
                    
                    // Inicializar mapa
                    initMiniMapa(mapaContainer, userCoords, coords);

                } catch (error) {
                    console.error(`Error procesando fila ${index}:`, error);
                    fila.querySelector('.distancia').textContent = 'Error';
                    fila.querySelector('.mini-mapa').innerHTML = '--';
                }
            }

        } catch (error) {
            console.error('Error de geolocalización:', error);
            document.querySelectorAll('.distancia').forEach(el => {
                el.textContent = 'Activa la geolocalización';
            });
            document.querySelectorAll('.mini-mapa').forEach(el => {
                el.innerHTML = '--';
            });
        }
    }

    // 6. Verificar compatibilidad y ejecutar
    if (!navigator.geolocation) {
        document.querySelectorAll('.distancia').forEach(el => {
            el.textContent = 'Geolocalización no soportada';
        });
        return;
    }

    // Iniciar el proceso
    procesarParkings();
});