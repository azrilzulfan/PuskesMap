<?php

namespace App\Http\Controllers;

use App\Support\PuskesmasStaticDataset;
use Illuminate\Contracts\View\View;

class MapController extends Controller
{
    // Menampilkan halaman peta interaktif WebGIS Puskesmas Kota Bogor
    public function index(): View
    {
        $puskesmas = PuskesmasStaticDataset::puskesmas();
        $districtStats = PuskesmasStaticDataset::districtStats();

        $citySummary = [
            'total_puskesmas' => count($puskesmas),
            'total_igd' => count(array_filter($puskesmas, fn ($p) => $p['igd'])),
            'total_inap' => count(array_filter($puskesmas, fn ($p) => $p['inap'])),
            'avg_nakes_compliance' => round(array_sum(array_column($puskesmas, 'nakes_score')) / count($puskesmas), 1),
            'total_posyandu' => array_sum(array_column($puskesmas, 'posyandu_count')),
            'total_school' => array_sum(array_column($puskesmas, 'school_count')),
            'total_population' => array_sum(array_column($districtStats, 'population')),
        ];

        $districts = array_merge(['Semua'], array_column($districtStats, 'name'));

        return view('peta', [
            'puskesmas' => $puskesmas,
            'districtStats' => $districtStats,
            'citySummary' => $citySummary,
            'districts' => $districts,
        ]);
    }
}
