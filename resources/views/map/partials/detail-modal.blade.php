    {{-- ================= MODAL DETAIL PUSKESMAS LENGKAP (FR-02 & FR-12) ================= --}}
    <div id="modal-faskes-detail" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-[560px] max-h-[90vh] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col">
            <div class="p-5 bg-surface-mint border-b border-primary/15 flex items-center justify-between">
                <div>
                    <span id="modal-code" class="text-[10px] font-extrabold uppercase tracking-wider text-primary">P32710101</span>
                    <h3 id="modal-name" class="text-lg font-extrabold text-primary-dark leading-tight">Puskesmas Tanah Sareal</h3>
                </div>
                <button type="button" id="btn-close-modal" aria-label="Tutup" class="size-8 rounded-full bg-white hover:bg-gray-100 text-ink flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 overflow-y-auto space-y-4 text-xs no-scrollbar">
                <div class="rounded-2xl bg-gray-50 p-3.5 space-y-1.5">
                    <div class="flex items-start gap-2">
                        <span class="text-muted shrink-0">Alamat:</span>
                        <span id="modal-address" class="font-bold text-ink">Jl. Kesehatan No. 1, Kota Bogor</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-muted shrink-0">Kontak:</span>
                        <span id="modal-phone" class="font-bold text-ink">(0251) 8332943</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-muted shrink-0">Jam Layanan:</span>
                        <span id="modal-hours" class="font-bold text-ink">Buka 08.00 – 16.00</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-extrabold text-ink text-[11px] uppercase tracking-wider mb-2">Kesiapan Fasilitas Layanan</h4>
                    <div id="modal-tags" class="flex flex-wrap gap-1.5"></div>
                </div>

                <div class="rounded-2xl border border-gray-200 p-3.5 sm:p-4 space-y-2 bg-white">
                    <div class="flex flex-wrap items-center justify-between gap-1.5">
                        <h4 class="font-extrabold text-ink text-xs">Kelengkapan 9 Profesi Nakes (Permenkes 43/2019)</h4>
                        <span id="modal-nakes-badge" class="rounded-full bg-surface-mint text-primary-dark px-2.5 py-0.5 text-[10px] font-extrabold">100% Terpenuhi</span>
                    </div>
                    <div id="modal-nakes-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1 text-[11px]"></div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 text-center">
                    <div class="p-3.5 rounded-2xl bg-surface-mint/60 border border-primary/15">
                        <span class="text-xs text-muted block">Posyandu Binaan</span>
                        <span id="modal-posyandu" class="text-lg font-extrabold text-primary-dark block mt-0.5">32</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-surface-mint/60 border border-primary/15">
                        <span class="text-xs text-muted block">Sekolah Binaan UKS</span>
                        <span id="modal-school" class="text-lg font-extrabold text-primary-dark block mt-0.5">24</span>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 grid grid-cols-2 sm:flex sm:items-center sm:justify-between gap-2.5 shrink-0">
                <a id="modal-official-link" href="https://kotabogor.go.id" target="_blank" rel="noopener noreferrer"
                   class="h-10 px-3 sm:px-4 rounded-full border border-gray-300 text-ink hover:bg-white text-[11px] sm:text-xs font-extrabold transition-colors inline-flex items-center justify-center gap-1.5">
                    <span>Portal Resmi</span>
                </a>
                <a id="modal-route-link" href="#" target="_blank" rel="noopener noreferrer"
                   class="h-10 px-3 sm:px-5 rounded-full bg-primary text-white hover:bg-primary-dark text-[11px] sm:text-xs font-extrabold transition-colors inline-flex items-center justify-center gap-1.5 shadow-sm">
                    <span>Rute Maps &rarr;</span>
                </a>
            </div>
        </div>
    </div>
