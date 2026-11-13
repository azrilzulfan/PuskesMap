<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AuditAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKunjunganRequest;
use App\Http\Requests\Admin\UpdateOperationalRequest;
use App\Http\Requests\Admin\UpdateTenagaMedisRequest;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\WorkloadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OperationalMetricsController extends Controller
{
    public function __construct(
        private readonly WorkloadService $workloadService,
        private readonly AuditLogService $auditLogService,
    ) {}

    public function edit(Puskesmas $puskesmas): View
    {
        $puskesmas->loadMissing(['kecamatan', 'operasional', 'tenagaMedis']);

        return view('admin.operasional.edit', [
            'puskesmas' => $puskesmas,
        ]);
    }

    public function updateOperational(UpdateOperationalRequest $request, Puskesmas $puskesmas): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validated();

        DB::transaction(function () use ($puskesmas, $validated, $actor, $request): void {
            $operasional = Operasional::query()->updateOrCreate(
                ['puskesmas_id' => $puskesmas->id],
                $validated,
            );

            $igdStatus = $operasional->status_igd_24jam ? 'IGD 24 Jam' : 'Non-IGD';

            $this->auditLogService->record(
                $actor,
                AuditAction::Update,
                'operasional',
                $operasional->id,
                "Memperbarui status operasional {$puskesmas->nama_puskesmas} ({$igdStatus}, {$operasional->jam_buka}–{$operasional->jam_tutup}).",
                $request->ip(),
            );
        });

        return redirect()
            ->route('admin.operasional.edit', $puskesmas)
            ->with('status', "Status operasional {$puskesmas->nama_puskesmas} berhasil diperbarui.");
    }

    public function updateTenagaMedis(UpdateTenagaMedisRequest $request, Puskesmas $puskesmas): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validated();

        DB::transaction(function () use ($puskesmas, $validated, $actor, $request): void {
            $tenagaMedis = TenagaMedis::query()->firstOrNew(['puskesmas_id' => $puskesmas->id]);
            $tenagaMedis->fill($validated);
            $tenagaMedis->recomputeMetrics();
            $tenagaMedis->save();

            $this->auditLogService->record(
                $actor,
                AuditAction::Update,
                'tenaga_medis',
                $tenagaMedis->id,
                "Memperbarui tenaga medis {$puskesmas->nama_puskesmas} (Kelengkapan {$tenagaMedis->persentase_kelengkapan}% - {$tenagaMedis->kategori_jumlah_nakes->value}).",
                $request->ip(),
            );
        });

        return redirect()
            ->route('admin.operasional.edit', $puskesmas)
            ->with('status', "Data tenaga medis {$puskesmas->nama_puskesmas} berhasil diperbarui.");
    }

    public function kunjunganIndex(Puskesmas $puskesmas): View
    {
        $puskesmas->loadMissing(['kecamatan']);

        $records = KunjunganPasien::query()
            ->where('puskesmas_id', $puskesmas->id)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate((int) config('sdss.pagination.per_page', 15));

        return view('admin.operasional.kunjungan', [
            'puskesmas' => $puskesmas,
            'records' => $records,
            'defaultCapacity' => (int) config('sdss.capacity.default_monthly', 5603),
        ]);
    }

    public function storeKunjungan(StoreKunjunganRequest $request, Puskesmas $puskesmas): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validated();

        $kunjungan = DB::transaction(function () use ($puskesmas, $validated, $actor, $request): KunjunganPasien {
            $tahun = (int) $validated['tahun'];
            $bulan = (int) $validated['bulan'];

            $kunjungan = KunjunganPasien::query()->firstOrNew([
                'puskesmas_id' => $puskesmas->id,
                'tahun' => $tahun,
                'bulan' => $bulan,
            ]);

            $isNew = ! $kunjungan->exists;
            $kunjungan->total_pasien_bulanan = (int) $validated['total_pasien_bulanan'];
            $kunjungan->kapasitas_maksimal_bulanan = (int) ($validated['kapasitas_maksimal_bulanan']
                ?? ($kunjungan->kapasitas_maksimal_bulanan ?: config('sdss.capacity.default_monthly', 5603)));
            $kunjungan->recomputeMetrics();
            $kunjungan->save();

            $this->workloadService->recalculateAndPersistScores($tahun);

            $this->auditLogService->record(
                $actor,
                $isNew ? AuditAction::Insert : AuditAction::Update,
                'kunjungan_pasien',
                $kunjungan->id,
                "Mencatat kunjungan {$puskesmas->nama_puskesmas} periode {$bulan}/{$tahun}: {$kunjungan->total_pasien_bulanan} pasien ({$kunjungan->persentase_keramaian}% - {$kunjungan->status_kepadatan->value}).",
                $request->ip(),
            );

            return $kunjungan;
        });

        return redirect()
            ->route('admin.kunjungan.index', $puskesmas)
            ->with('status', "Data kunjungan periode {$kunjungan->bulan}/{$kunjungan->tahun} berhasil disimpan.");
    }
}
