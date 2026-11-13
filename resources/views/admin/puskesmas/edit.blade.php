<x-layout title="Edit Puskesmas - Admin PuskesMap" body-class="min-h-dvh bg-[#f5f8f7] text-ink font-sans">
    <x-internal-nav />

    <main class="mx-auto max-w-4xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-extrabold text-ink">Edit Profil {{ $puskesmas->nama_puskesmas }}</h1>
            <a href="{{ route('admin.puskesmas.index') }}" class="text-xs font-bold text-primary hover:underline">&larr; Kembali ke Daftar</a>
        </div>

        <form method="POST" action="{{ route('admin.puskesmas.update', $puskesmas) }}" class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6">
            @csrf
            @method('PUT')
            @include('admin.puskesmas._form', ['puskesmas' => $puskesmas])
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                <a href="{{ route('admin.puskesmas.index') }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-ink">Batal</a>
                <button type="submit" class="rounded-xl bg-primary px-5 py-2.5 text-xs font-extrabold text-white hover:bg-primary-dark cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </main>
</x-layout>
