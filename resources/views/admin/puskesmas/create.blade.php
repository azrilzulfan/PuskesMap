<x-dashboard-shell
    title="Tambah Puskesmas - Admin PuskesMap"
    greeting="Tambah Puskesmas Baru"
    subtitle="Isi profil fasilitas dan pilih titik koordinat pada peta Kota Bogor"
>
    <x-slot:headerActions>
        <a href="{{ route('admin.puskesmas.index') }}"
           class="inline-flex h-11 items-center rounded-full bg-white px-5 text-xs font-extrabold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-surface-mint">
            &larr; Kembali ke Daftar
        </a>
    </x-slot:headerActions>

    <form method="POST" action="{{ route('admin.puskesmas.store') }}">
        @csrf
        @include('admin.puskesmas._form', ['submitLabel' => 'Simpan Puskesmas', 'submitAccent' => true])
    </form>
</x-dashboard-shell>
