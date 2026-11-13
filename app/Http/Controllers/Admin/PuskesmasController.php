<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AuditAction;
use App\Enums\NakesCategory;
use App\Enums\ServiceCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePuskesmasRequest;
use App\Http\Requests\Admin\UpdatePuskesmasRequest;
use App\Models\Kecamatan;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\WorkloadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuskesmasController extends Controller
{
    public function __construct(
        private readonly WorkloadService $workloadService,
        private readonly AuditLogService $auditLogService,
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $kecamatanId = $request->filled('kecamatan_id') ? (int) $request->input('kecamatan_id') : null;

        $puskesmas = Puskesmas::query()
            ->with(['kecamatan', 'operasional', 'tenagaMedis'])
            ->withLatestKunjungan()
            ->when($kecamatanId !== null, fn ($query) => $query->where('kecamatan_id', $kecamatanId))
            ->when($search !== '', function ($query) use ($search): void {
                $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';
                $query->where(function ($subQuery) use ($search, $operator): void {
                    $subQuery->where('nama_puskesmas', $operator, "%{$search}%")
                        ->orWhere('kode_puskesmas', $operator, "%{$search}%");
                });
            })
            ->orderBy('kode_puskesmas')
            ->paginate((int) config('sdss.pagination.per_page', 15))
            ->withQueryString();

        return view('admin.puskesmas.index', [
            'puskesmas' => $puskesmas,
            'districts' => Kecamatan::query()->orderBy('nama_kecamatan')->get(),
            'search' => $search,
            'selectedKecamatanId' => $kecamatanId,
        ]);
    }

    public function create(): View
    {
        return view('admin.puskesmas.create', [
            'districts' => Kecamatan::query()->orderBy('nama_kecamatan')->get(),
            'categories' => ServiceCategory::cases(),
        ]);
    }

    public function store(StorePuskesmasRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        /** @var User $actor */
        $actor = $request->user();

        $puskesmas = DB::transaction(function () use ($validated, $actor, $request): Puskesmas {
            $puskesmas = Puskesmas::create([
                'kecamatan_id' => $validated['kecamatan_id'],
                'kode_puskesmas' => $validated['kode_puskesmas'],
                'nama_puskesmas' => $validated['nama_puskesmas'],
                'alamat' => $validated['alamat'],
                'x_long' => $validated['x_long'],
                'y_lat' => $validated['y_lat'],
                'website_url' => $validated['website_url'] ?? null,
                'kategori_layanan' => $validated['kategori_layanan'],
                'jumlah_posyandu' => $validated['jumlah_posyandu'],
                'jumlah_sekolah' => $validated['jumlah_sekolah'],
            ]);

            $isIgd = (bool) ($validated['status_igd_24jam'] ?? false);

            Operasional::create([
                'puskesmas_id' => $puskesmas->id,
                'status_igd_24jam' => $isIgd,
                'jam_buka' => $validated['jam_buka'] ?? ($isIgd ? '00:00:00' : '07:30:00'),
                'jam_tutup' => $validated['jam_tutup'] ?? ($isIgd ? '23:59:59' : '15:00:00'),
                'kontak_darurat' => $validated['kontak_darurat'] ?? null,
                'rawat_inap' => (bool) ($validated['rawat_inap'] ?? false),
            ]);

            TenagaMedis::create([
                'puskesmas_id' => $puskesmas->id,
                'persentase_kelengkapan' => 0.0,
                'kategori_jumlah_nakes' => NakesCategory::KurangLengkap,
            ]);

            $this->auditLogService->record(
                $actor,
                AuditAction::Insert,
                'puskesmas',
                $puskesmas->id,
                "Menambahkan Puskesmas {$puskesmas->nama_puskesmas} ({$puskesmas->kode_puskesmas}).",
                $request->ip(),
            );

            $this->workloadService->recalculateAndPersistScores();

            return $puskesmas;
        });

        return redirect()
            ->route('admin.puskesmas.index')
            ->with('status', "Data Puskesmas {$puskesmas->nama_puskesmas} berhasil ditambahkan.");
    }

    public function edit(Puskesmas $puskesmas): View
    {
        $puskesmas->loadMissing(['kecamatan', 'operasional', 'tenagaMedis']);

        return view('admin.puskesmas.edit', [
            'puskesmas' => $puskesmas,
            'districts' => Kecamatan::query()->orderBy('nama_kecamatan')->get(),
            'categories' => ServiceCategory::cases(),
        ]);
    }

    public function update(UpdatePuskesmasRequest $request, Puskesmas $puskesmas): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $request->validated();

        DB::transaction(function () use ($puskesmas, $validated, $actor, $request): void {
            $puskesmas->update($validated);

            $this->auditLogService->record(
                $actor,
                AuditAction::Update,
                'puskesmas',
                $puskesmas->id,
                "Memperbarui profil dan koordinat Puskesmas {$puskesmas->nama_puskesmas} ({$puskesmas->kode_puskesmas}).",
                $request->ip(),
            );

            $this->workloadService->recalculateAndPersistScores();
        });

        return redirect()
            ->route('admin.puskesmas.index')
            ->with('status', "Data Puskesmas {$puskesmas->nama_puskesmas} berhasil diperbarui.");
    }

    public function destroy(Request $request, Puskesmas $puskesmas): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $id = $puskesmas->id;
        $name = $puskesmas->nama_puskesmas;
        $code = $puskesmas->kode_puskesmas;

        DB::transaction(function () use ($puskesmas, $actor, $id, $name, $code, $request): void {
            $this->auditLogService->record(
                $actor,
                AuditAction::Delete,
                'puskesmas',
                $id,
                "Menghapus Puskesmas {$name} ({$code}).",
                $request->ip(),
            );

            $puskesmas->delete();
            $this->workloadService->recalculateAndPersistScores();
        });

        return redirect()
            ->route('admin.puskesmas.index')
            ->with('status', "Data Puskesmas {$name} berhasil dihapus.");
    }
}
