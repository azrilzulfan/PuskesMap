import './reveal.js';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { initLandingPage } from './landing/landing-page.js';
import { initPuskesMapSDSS } from './map/sdss-map.js';

window.L = L;

function bootstrapPuskesMap() {
    initLandingPage();

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
