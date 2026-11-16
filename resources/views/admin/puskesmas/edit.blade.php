<x-dashboard-shell
    title="Edit Puskesmas - Admin PuskesMap"
    :greeting="'Edit Profil '.$puskesmas->nama_puskesmas"
    :subtitle="$puskesmas->kode_puskesmas.' — Pembaruan profil & koordinat tervalidasi ST_Contains'"
>
    <x-slot:headerActions>
        <a href="{{ route('admin.puskesmas.index') }}"
           class="inline-flex h-11 items-center rounded-full bg-white px-5 text-xs font-extrabold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-surface-mint">
            &larr; Kembali ke Daftar
        </a>
    </x-slot:headerActions>

    <form method="POST" action="{{ route('admin.puskesmas.update', $puskesmas) }}">
        @csrf
        @method('PUT')
        @include('admin.puskesmas._form', ['puskesmas' => $puskesmas, 'submitLabel' => 'Simpan Perubahan', 'submitAccent' => false])
    </form>
</x-dashboard-shell>
