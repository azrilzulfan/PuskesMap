<?php

use App\Enums\AuditAction;
use App\Enums\CrowdStatus;
use App\Enums\NakesCategory;
use App\Enums\ServiceCategory;
use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\Kecamatan;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('admin can create update and delete puskesmas with bogor polygon check and audit logs', function () {
    $admin = User::factory()->admin()->create();
    $kecamatan = Kecamatan::factory()->withPolygon()->create();

    $invalidResponse = $this->actingAs($admin)->post(route('admin.puskesmas.store'), [
        'kode_puskesmas' => 'P999999',
        'kecamatan_id' => $kecamatan->id,
        'nama_puskesmas' => 'Puskesmas Luar Bogor',
        'alamat' => 'Jakarta',
        'x_long' => 106.8166,
        'y_lat' => -6.2000,
        'kategori_layanan' => ServiceCategory::NonRawatInap->value,
        'jumlah_posyandu' => 10,
        'jumlah_sekolah' => 10,
    ]);

    $invalidResponse->assertSessionHasErrors('y_lat');
    $this->assertDatabaseCount('puskesmas', 0);

    $storeResponse = $this->actingAs($admin)->post(route('admin.puskesmas.store'), [
        'kode_puskesmas' => 'P32719901',
        'kecamatan_id' => $kecamatan->id,
        'nama_puskesmas' => 'Puskesmas Baru Bogor',
        'alamat' => 'Jl. Raya Pajajaran No. 99',
        'x_long' => 106.7950,
        'y_lat' => -6.5950,
        'website_url' => 'https://dinkes.kotabogor.go.id',
        'kategori_layanan' => ServiceCategory::RawatInap->value,
        'jumlah_posyandu' => 30,
        'jumlah_sekolah' => 25,
        'status_igd_24jam' => '1',
        'rawat_inap' => '1',
    ]);

    $storeResponse->assertRedirect(route('admin.puskesmas.index'));

    $created = Puskesmas::query()->where('kode_puskesmas', 'P32719901')->firstOrFail();
    expect($created->skor_psw)->not->toBeNull();
    expect($created->operasional?->status_igd_24jam)->toBeTrue();
    expect($created->tenagaMedis)->not->toBeNull();

    $geomText = DB::selectOne('SELECT ST_AsText(geom) AS wkt FROM puskesmas WHERE id = ?', [$created->id]);
    expect((string) $geomText?->wkt)->toStartWith('POINT(');

    $this->assertDatabaseHas('audit_log', [
        'user_id' => $admin->id,
        'aksi' => AuditAction::Insert->value,
        'tabel_target' => 'puskesmas',
        'target_id' => $created->id,
    ]);

    $updateResponse = $this->actingAs($admin)->put(route('admin.puskesmas.update', $created), [
        'kode_puskesmas' => 'P32719901',
        'kecamatan_id' => $kecamatan->id,
        'nama_puskesmas' => 'Puskesmas Baru Bogor Revisi',
        'alamat' => 'Jl. Raya Pajajaran No. 100',
        'x_long' => 106.7980,
        'y_lat' => -6.5980,
        'website_url' => 'https://dinkes.kotabogor.go.id',
        'kategori_layanan' => ServiceCategory::NonRawatInap->value,
        'jumlah_posyandu' => 35,
        'jumlah_sekolah' => 28,
    ]);

    $updateResponse->assertRedirect(route('admin.puskesmas.index'));
    expect($created->fresh()->nama_puskesmas)->toBe('Puskesmas Baru Bogor Revisi');
    $this->assertDatabaseHas('audit_log', [
        'aksi' => AuditAction::Update->value,
        'tabel_target' => 'puskesmas',
        'target_id' => $created->id,
    ]);

    $deleteResponse = $this->actingAs($admin)->delete(route('admin.puskesmas.destroy', $created));

    $deleteResponse->assertRedirect(route('admin.puskesmas.index'));
    $this->assertDatabaseMissing('puskesmas', ['id' => $created->id]);
    $this->assertDatabaseMissing('operasional', ['puskesmas_id' => $created->id]);
    $this->assertDatabaseMissing('tenaga_medis', ['puskesmas_id' => $created->id]);
    $this->assertDatabaseHas('audit_log', [
        'aksi' => AuditAction::Delete->value,
        'tabel_target' => 'puskesmas',
        'target_id' => $created->id,
    ]);
});

test('admin updates operational, nakes completeness, and patient visits with automatic calculations', function () {
    $admin = User::factory()->admin()->create();
    $kecamatan = Kecamatan::factory()->withPolygon()->create();
    $facility = Puskesmas::factory()->create(['kecamatan_id' => $kecamatan->id]);
    Operasional::factory()->create(['puskesmas_id' => $facility->id]);
    TenagaMedis::factory()->kurangLengkap()->create(['puskesmas_id' => $facility->id]);

    $this->actingAs($admin)
        ->get(route('admin.operasional.edit', $facility))
        ->assertOk()
        ->assertSee($facility->nama_puskesmas);

    $this->actingAs($admin)
        ->put(route('admin.operasional.update', $facility), [
            'status_igd_24jam' => '1',
            'jam_buka' => '00:00:00',
            'jam_tutup' => '23:59:59',
            'kontak_darurat' => '0251-8123456',
            'rawat_inap' => '1',
        ])
        ->assertRedirect(route('admin.operasional.edit', $facility));

    expect($facility->operasional()->firstOrFail()->status_igd_24jam)->toBeTrue();

    $this->actingAs($admin)
        ->put(route('admin.tenaga-medis.update', $facility), [
            'dokter_umum' => 3,
            'dokter_gigi' => 2,
            'bidan' => 8,
            'perawat' => 10,
            'farmasi' => 2,
            'gizi' => 1,
            'kesling' => 1,
            'kesmas' => 1,
            'atlm' => 0,
        ])
        ->assertRedirect(route('admin.operasional.edit', $facility));

    $nakes = $facility->tenagaMedis()->firstOrFail();
    expect($nakes->persentase_kelengkapan)->toBe(88.89);
    expect($nakes->kategori_jumlah_nakes)->toBe(NakesCategory::KurangLengkap);

    $this->actingAs($admin)
        ->put(route('admin.tenaga-medis.update', $facility), [
            'dokter_umum' => 3,
            'dokter_gigi' => 2,
            'bidan' => 8,
            'perawat' => 10,
            'farmasi' => 2,
            'gizi' => 1,
            'kesling' => 1,
            'kesmas' => 1,
            'atlm' => 2,
        ])
        ->assertRedirect(route('admin.operasional.edit', $facility));

    $nakes->refresh();
    expect($nakes->persentase_kelengkapan)->toBe(100.0);
    expect($nakes->kategori_jumlah_nakes)->toBe(NakesCategory::Lengkap);

    $this->actingAs($admin)
        ->post(route('admin.kunjungan.store', $facility), [
            'bulan' => 12,
            'tahun' => 2025,
            'total_pasien_bulanan' => 5000,
            'kapasitas_maksimal_bulanan' => 5603,
        ])
        ->assertRedirect(route('admin.kunjungan.index', $facility));

    $visit = KunjunganPasien::query()->where('puskesmas_id', $facility->id)->firstOrFail();
    expect($visit->persentase_keramaian)->toBe(89.24);
    expect($visit->status_kepadatan)->toBe(CrowdStatus::Padat);
    expect(AuditLog::query()->where('tabel_target', 'kunjungan_pasien')->count())->toBe(1);
});

test('admin can manage users, cannot deactivate self, and can inspect filtered audit logs', function () {
    $admin = User::factory()->admin()->create();
    $otherUser = User::factory()->pemerintah()->create(['name' => 'Staff Analitik']);

    $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Operator Baru',
        'email' => 'operator@puskesmap.local',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => UserRole::Pemerintah->value,
        'is_active' => '1',
    ])->assertRedirect(route('admin.users.index'));

    $createdUser = User::query()->where('email', 'operator@puskesmap.local')->firstOrFail();
    expect($createdUser->isPemerintah())->toBeTrue();

    $this->actingAs($admin)
        ->patch(route('admin.users.deactivate', $admin))
        ->assertSessionHasErrors('user');

    expect($admin->fresh()->is_active)->toBeTrue();

    $this->actingAs($admin)
        ->patch(route('admin.users.deactivate', $otherUser))
        ->assertRedirect(route('admin.users.index'));

    expect($otherUser->fresh()->is_active)->toBeFalse();

    $this->actingAs($admin)
        ->get(route('admin.audit-log.index', ['aksi' => AuditAction::Deactivate->value]))
        ->assertOk()
        ->assertSee('Staff Analitik');
});
