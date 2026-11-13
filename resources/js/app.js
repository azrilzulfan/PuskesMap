import './reveal.js';
import Alpine from 'alpinejs';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { initLandingPage } from './landing/landing-page.js';
import { initPuskesMapSDSS } from './map/sdss-map.js';

window.L = L;

function bootstrapPuskesMap() {
    initLandingPage();

    // Jalankan Alpine.js hasil bundle Vite hanya pada layout SDSS yang tidak memakai CDN
    if (document.body?.dataset?.alpine === 'bundled' && !window.Alpine) {
        window.Alpine = Alpine;
        Alpine.start();
    }

    const leafletContainer = document.getElementById('leaflet-map');
    if (leafletContainer) {
        initPuskesMapSDSS();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootstrapPuskesMap);
} else {
    bootstrapPuskesMap();
}
