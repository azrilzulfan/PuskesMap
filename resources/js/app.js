import './reveal.js';
import Alpine from 'alpinejs';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import Chart from 'chart.js/auto';
import { initLandingPage } from './landing/landing-page.js';
import { initPuskesMapSDSS } from './map/sdss-map.js';

window.L = L;

function initVisitTrendChart() {
    const canvas = document.getElementById('visitTrendChart');
    const dataEl = document.getElementById('visit-trend-data');
    if (!canvas || !dataEl) {
        return;
    }

    try {
        const series = JSON.parse(dataEl.textContent || '{}');
        const capacity = Number(series.capacity ?? 850);
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: series.labels || [],
                datasets: [
                    {
                        label: 'Kunjungan Pasien Bulanan',
                        data: series.values || [],
                        borderColor: '#0A6663',
                        backgroundColor: 'rgba(10, 102, 99, 0.12)',
                        borderWidth: 2.5,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 3.5,
                        pointBackgroundColor: '#0A6663',
                    },
                    {
                        label: `Kapasitas Acuan (${capacity}/bln)`,
                        data: (series.labels || []).map(() => capacity),
                        borderColor: '#B54708',
                        borderDash: [5, 5],
                        borderWidth: 1.5,
                        fill: false,
                        pointRadius: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });
    } catch (e) {
        console.warn('Gagal memuat grafik kunjungan 12 bulan:', e);
    }
}

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

    initVisitTrendChart();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootstrapPuskesMap);
} else {
    bootstrapPuskesMap();
}
