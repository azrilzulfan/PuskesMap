# DESIGN_SPEC.md

File ini adalah cetak biru tampilan (UI) dan pengalaman pengguna (UX) yang menjelaskan **SEPERTI APA** bentuk visual produk (penggabungan ringkas dari Brand Guideline, Style Guide, Wireframe, dan UX Spec).
*(Untuk urusan fitur & aturan bisnis lihat [`PRODUCT_SPEC.md`](./PRODUCT_SPEC.md), dan untuk urusan teknis/database lihat [`TECH_SPEC.md`](./TECH_SPEC.md)).*

---

## Metadata

- Nama Produk: PuskesMap — Sistem Pendukung Keputusan Spasial (WebGIS) Puskesmas Kota Bogor
- Status Spec: Disetujui & Disinkronkan ke Rute Repo (`T-01` -> `T-02`)
- Terakhir diperbarui: 2026-11-13
- File Bahan / Referensi:
  - [`UI_RULES.md`](./UI_RULES.md) (Aturan Wajib Tingkat 1A, 1B, 2, dan 3)
  - [`Logo.jpeg`](./Logo.jpeg) (Identitas Visual Resmi PuskesMap)
  - `Activity_Rekomendasi_PuskesMap_v2.png` & `Activity_Analitik_PSW_PuskesMap_v2.png`
  - Referensi Pola UX WebGIS Pemerintahan: **OneMap Singapore (`https://www.onemap.gov.sg/`)**

---

## 1. Karakter Visual, Palet Warna ([`Logo.jpeg`](./Logo.jpeg)) & Pengecualian [`UI_RULES.md`](./UI_RULES.md)

- **Kesan Visual (Mood):** Modern, berwibawa sebagai sistem informasi kesehatan pemerintah daerah, bersih, dan mengutamakan kejelasan informasi peta (*Map-First Minimalist* ala OneMap Singapore). Hierarki dibangun dari tipografi, jarak, dan kontras tanpa dekorasi berlebihan.
- **Mode Tampilan:** **Hanya Mode Terang (*Light Mode*)** sesuai Tingkat 1B [`UI_RULES.md`](./UI_RULES.md#L34).
- **Sistem Variabel CSS (Diturunkan Langsung dari Piksel [`Logo.jpeg`](./Logo.jpeg) & Teruji WCAG 2.2):**
  Warna palang medis tengah pada [`Logo.jpeg`](./Logo.jpeg) (**`#0A6663` — *Deep Medical Teal***) ditetapkan sebagai warna aksen utama (`--accent`) karena memiliki rasio kontras **`6,79 : 1`** terhadap latar putih (`#FFFFFF`), sementara warna biru sayap kanan (`#067DCF`) dan hijau sayap kiri (`#157347` / `#16A34A`) dialokasikan untuk elemen semantik dan layer kanvas GIS:

```css
:root {
  color-scheme: light;

  /* 1. Palet Utama Antarmuka (Patuh Aturan 3 Warna Utama UI_RULES.md) */
  --base: #FFFFFF;
  --surface: #F4F7F6;                /* Abu-hijau sangat tipis senada logo untuk pembeda region */
  --text: #111C24;                   /* Kontras 17.28:1 pada base, 16.03:1 pada surface */
  --text-muted: #4E5D6C;             /* Kontras 6.76:1 pada base, 6.27:1 pada surface */
  --border: #E1E7EC;                 /* 1px solid khusus pemisah baris tabel data */
  --border-control: #7B8A97;         /* 1px solid border input/select (kontras 3.54:1 >= 3:1) */
  --border-control-hover: #5C6B78;   /* Kontras 5.48:1 saat hover */
  --accent: #0A6663;                 /* Deep Medical Teal dari palang tengah Logo.jpeg (6.79:1) */
  --accent-hover: #074E4C;           /* Kontras 9.53:1 saat tombol utama di-hover/aktif */
  --accent-tint: #EAF4F3;            /* Latar solid kartu/baris Puskesmas yang sedang terpilih */

  /* 2. Warna Status Semantik (Wajib Disertai Teks Label) */
  --error: #B42318;                  /* Kontras 6.57:1 — Pesan error, aksi hapus, & penanda UGD 24 Jam */
  --success: #157347;                /* Kontras 5.87:1 — Status Buka & SDM Lengkap (turunan sayap kiri) */
  --warning: #B54708;                /* Kontras 5.43:1 — Kepadatan Sedang & peringatan rasio */
  --shadow: 0 4px 16px rgba(20, 23, 31, 0.12); /* Satu-satunya rgba untuk panel/modal melayang */

  /* 3. Token Khusus Visualisasi Peta & Grafik GIS (Pengecualian Spesifik Tingkat 1B) */
  --gis-user-pin: #067DCF;           /* Ocean Blue dari sayap kanan Logo.jpeg untuk GPS & garis radius */
  --gis-psw-high: #DC2626;           /* Spektrum PSW Tinggi (>= 0.70) sesuai 05_hitung_psw.sql */
  --gis-psw-ideal: #16A34A;          /* Spektrum PSW Ideal (0.40 - <0.70) sesuai 05_hitung_psw.sql */
  --gis-psw-low: #2563EB;            /* Spektrum PSW Rendah (< 0.40) sesuai 05_hitung_psw.sql */
}
```

- **Daftar Pengecualian Eksplisit Tingkat 1B [`UI_RULES.md`](./UI_RULES.md#L14) (Khusus Elemen Geospasial & Identitas):**
  Sesuai prosedur resmi pada baris 14 [`UI_RULES.md`](./UI_RULES.md#L14), pengecualian Tingkat 1B diberikan secara terbatas **hanya** pada 5 elemen spesifik berikut (seluruh elemen tabel, form, navigasi, dan teks lainnya tetap tunduk 100% pada [`UI_RULES.md`](./UI_RULES.md)):
  1. **Transparansi Layer Peta (`fillOpacity` pada Kanvas Leaflet):** Diizinkan khusus untuk poligon `kecamatan`, `jangkauan_jalan`, dan lingkaran buffer `radius` (`fillOpacity: 0.20` s/d `0.45`) agar jaringan jalan dan label wilayah pada basemap di bawahnya tetap terbaca.
  2. **Palet Warna Tematik GIS (`--gis-*`):** Diizinkan khusus untuk mewarnai poligon spektrum PSW (`#DC2626` Tinggi, `#16A34A` Ideal, `#2563EB` Rendah sesuai [`05_hitung_psw.sql`](../sql/05_hitung_psw.sql#L19)), 5 kelas zona `jangkauan_jalan`, serta grafik analitik `Chart.js`, dan selalu didampingi label teks pada legenda/tabel.
  3. **Penanda Semantik Medis (`--error: #B42318` pada Pin/Badge `UGD 24 Jam` & `--success: #157347` pada Badge `Buka` / `SDM Lengkap`):** Diizinkan khusus pada pin peta Puskesmas UGD 24 Jam dan lencana (*badge*) status layanan/ketenagaan yang wajib selalu menyertakan label teks eksplisit.
  4. **Ikon Fungsional dari Pustaka Resmi `Lucide Icons` pada Kontrol Peta & Pin Faskes:** Diizinkan khusus pada *toolbar* peta kanan (`layers`, `locate-fixed`, `plus`, `minus`), tombol pencarian/reset, serta pin lokasi Puskesmas (`L.divIcon` berbasis SVG resmi Lucide).
  5. **Aset [`Logo.jpeg`](./Logo.jpeg):** Diizinkan menampilkan berkas logo asli proyek tanpa mengubah gradasi internal di dalam gambar logo tersebut.

---

## 2. Tipografi & Hierarki Teks

- **Jenis Huruf (Font):** Satu keluarga huruf *sans-serif* bersih **`Plus Jakarta Sans`** atau **`Inter`** dengan *fallback* font sistem (`system-ui, -apple-system, sans-serif`).
- **Skala Ukuran & Bobot (Menggunakan `rem` sesuai Tingkat 1A):**
  - Judul Utama (`h1` / Nama Halaman): `1.75rem` (`28px`), bobot `600`, `line-height: 1.25`, `text-wrap: balance`.
  - Subjudul Bagian / Judul Panel (`h2`): `1.25rem` (`20px`), bobot `600`, `line-height: 1.3`.
  - Teks Isi & Input Form: `1rem` (`16px`), bobot `400` (atau `500` untuk label), `line-height: 1.5`, `text-wrap: pretty`.
  - Teks Sekunder / Tabel / Keterangan: `0.875rem` (`14px`), bobot `400`, warna `--text-muted`.
- **Aturan Angka & Tabel Data:**
  - Seluruh kolom angka (jumlah penduduk, posyandu, sekolah, pasien bulanan, jarak meter/km, persentase kelengkapan Nakes, skor MAUT, dan skor PSW) **wajib rata kanan (`text-right`)** dan memakai **`font-variant-numeric: tabular-nums`** agar digit sejajar lurus dari atas ke bawah, tanpa garis pembatas vertikal.

---

## 3. Tata Letak (Adaptasi UX OneMap Singapore) & 5 Layar Utama

### Prinsip Tata Letak & Navigasi
- **Peta Utama (*Map-First Full-Viewport Canvas*):**
  - Kanvas peta menempati **100% tinggi dan lebar layar (`100dvh x 100vw`)** tanpa terpotong *header* atau *footer* besar.
  - **Satu Panel Apung Kiri Kontekstual (*Single Contextual Left Drawer* ala OneMap):** Berukuran lebar `380px–420px` di desktop (`border-radius: 12px`, `box-shadow: var(--shadow)`, latar `--base`) yang dapat disembunyikan/dibuka (*collapse/expand*). Di layar smartphone (`< 768px`), panel ini otomatis bertransformasi menjadi *bottom sheet* yang nyaman digeser dengan ibu jari.
  - **Responsivitas & Aksesibilitas Viewport (`NFR-02` & Tingkat 1A [`UI_RULES.md`](./UI_RULES.md#L28-L29)):** Tata letak wajib proporsional dan nyaman digunakan mulai dari lebar layar `320px`/`360px` hingga `1920px`, tetap terpakai pada perbesaran browser (*zoom*) `200%` tanpa *scroll* horizontal pada konten utama, serta wajib menghormati `@media (prefers-reduced-motion: reduce)`.
  - **Tombol Apung `Reset Filter / Bersihkan Pin`:** Muncul di sebelah kanan atas panel kiri saat filter atau radius aktif agar pengguna dapat mengembalikan peta ke kondisi awal dalam 1 klik.
  - ***Toolbar* Peta Kanan (Gaya OneMap):** Dikelompokkan secara vertikal di tepi kanan peta untuk: (a) Pemilih *Basemap* (**`OSM Standar`** vs **`Grey Lite / CartoDB Positron`**) & *Toggle Layer GIS*, (b) Tombol **Lokasi Saya (GPS)**, serta (c) Kontrol **Zoom `+` / `-`**.

### Daftar 5 Layar / Mode Utama (*Key Screens*)
1. **Layar 1 — Peta Eksplorasi & Filter Publik (`/peta` — Mode *Eksplorasi*):**
   - *(Catatan: Rute `/` dipakai untuk Landing Page pengantar; kanvas peta utama SDSS berada pada `/peta`.)*
   - Kotak pencarian nama Puskesmas, alamat, atau kecamatan (*debounce* `300 ms` — `FR-03`) menyatu di bagian atas *drawer* kiri, diikuti filter cepat (*Semua / Sedang Buka*, *UGD 24 Jam*, *SDM Lengkap*, pilihan Kecamatan — `FR-04`) dan daftar kartu ringkas 25 Puskesmas.
   - Mengklik salah satu kartu Puskesmas di daftar kiri langsung menggerakkan kamera peta (`map.flyTo()`) ke pin Puskesmas tersebut dan membuka *popup* ringkasnya.
2. **Layar 2 — Kalkulator Rekomendasi Radius & Top-3 MAUT (`/peta` — Mode *Rekomendasi Radius*):**
   - Di dalam *drawer* kiri, pengguna menentukan titik lokasi (tombol GPS atau klik peta — `FR-05`), memilih radius (`1 km`, `3 km`, atau `5 km` — `FR-06`), dan memilih skenario bobot (`Darurat`, `Reguler`, atau `Evaluasi Pemda` bagi akun login `pemerintah`/`admin` — `FR-07`).
   - Menampilkan lingkaran radius berwarna `--gis-user-pin` di peta, menampilkan 3 kartu peringkat teratas (`#1`, `#2`, `#3`) lengkap dengan jarak (`km`), skor akhir MAUT, status buka/UGD, dan rincian kontribusi 5 kriteria, serta **otomatis menggerakkan fokus peta (`flyTo`) dan menyorot penanda Puskesmas peringkat `#1` (`FR-08`)**.
3. **Layar 3 — Dasbor Analitik Kewilayahan & Beban Wilayah PSW (`/analitik` — Role `pemerintah` & `admin`):**
   - Menyediakan kontrol layer analitik Dinkes (Peta Choropleth Rasio Kecamatan `1:30.000` [`FR-10`], Spektrum PSW [`FR-24`], 37 Zona Jangkauan Jalan & 941 Ruas Jalan Utama [`FR-23`], serta Buffer Cakupan Layanan `1,5 km` atau `3,0 km` beserta luas *blank spot* dalam $\text{km}^2$ [`FR-09`]).
   - Menampilkan kartu ringkasan statistik fasilitas kota (`FR-11`), tabel peringkat 25 Puskesmas berdasarkan `skor_psw`, grafik perbandingan `Chart.js`, dan tombol aksi **"Unduh CSV"** serta **"Unduh PDF"** (`FR-21`).
4. **Layar 4 — Halaman / Panel Detail Profil Puskesmas (`/puskesmas/{kode_puskesmas}` — *Rencana Fase UI `T-02`*):**
   - Menampilkan informasi lengkap Puskesmas: status layanan saat ini (Buka/Tutup WIB & UGD 24 Jam), alamat, koordinat, nomor telepon/WhatsApp resmi, tautan eksternal aktif `https://dinkes.kotabogor.go.id/<slug>` (`FR-13`), beban binaan UKM (Posyandu & Sekolah 2025 — `FR-02`), matriks ketersediaan 9 profesi Nakes beserta persentase pemenuhannya (`FR-12`), serta indikator estimasi keramaian & grafik tren kunjungan pasien baru 12 bulan (`Chart.js` — `FR-22`).
5. **Layar 5 — Halaman Login & Dashboard Manajemen Data Master (`/login` & `/admin/*` — Role `admin`):**
   - Form login bersih (`max-width: 400px`, dukungan penuh *password manager* & tombol *Tampilkan Sandi* — `FR-14`, `FR-15`).
   - Tabel dan form CRUD terstruktur untuk Data Induk Puskesmas (`FR-16`, dilengkapi peta pemilih titik interaktif [`FR-17`] yang divalidasi `ST_Contains` [`FR-20`]), Jam Operasional, 9 Profesi Nakes, Kunjungan Bulanan (`FR-18`), Manajemen Akun Pengguna (*soft-deactivation* `is_active`), dan tabel Riwayat `audit_log` (`FR-19`). Seluruh tabel daftar di halaman administrasi menerapkan **paginasi sisi peladen `10` hingga `25` baris per halaman (`NFR-09`)**.

---

## 4. Standar Komponen & Pustaka Ikon Buatan Manusia (*Human-Crafted Libraries*)

- **Wajib Menggunakan Komponen & Ikon Buatan Manusia:**
  - **Dilarang** membuat gambar ilustrasi/stiker atau menggambar path SVG kustom karangan AI.
  - Seluruh ikon wajib diambil dari pustaka resmi **`Lucide Icons`** (`mallardduck/blade-lucide-icons` / `lucide`), dengan ukuran konsisten (`16px` atau `20px`) dan ketebalan garis seragam (`stroke-width: 1.75`).
  - Komponen interaktif dibangun dari pola komponen HTML/Tailwind teruji (**Preline UI / Flowbite** + **Alpine.js**) yang dirapikan ke dalam *Laravel Blade Components* di `resources/views/components/ui/` (`<x-ui.button>`, `<x-ui.input>`, `<x-ui.select>`, `<x-ui.badge>`, `<x-ui.drawer>`, `<x-ui.modal>`).
- **Ukuran Target Sentuh, Sudut, & Jarak (Sesuai [`UI_RULES.md`](./UI_RULES.md#L23-L63)):**
  - Tinggi minimal seluruh tombol, input, dan select adalah **`44px`** (`min-h-11`), dengan jarak antar target klik minimal **`8px`**.
  - *Border radius* konsisten: **`6px`** untuk kontrol (tombol, input, select, badge) dan **`12px`** untuk elemen melayang (*drawer*, modal, popup peta).
  - Ritme jarak form: label permanen ke input **`8px`**, jarak antar field **`24px`**, input terakhir ke tombol simpan **`32px`**, dan jarak antar bagian besar **`48px`** (tanpa garis `<hr>` dekoratif).

---

## 5. Respon Layar & Kondisi Khusus (UX States)

- **Saat Memuat Data (*Loading State*):**
  - Tombol aksi yang memicu proses (seperti *"Hitung Rekomendasi"*, *"Simpan Perubahan"*, atau *"Unduh PDF"*) menampilkan indikator pemrosesan, teks tombol berubah menjadi status aktif (misal: *"Menghitung..."*), dan tombol dinonaktifkan sementara (`disabled` menggunakan warna abu solid tanpa `opacity`).
- **Saat Data Kosong (*Empty State*):**
  - Jika pencarian atau filter radius tidak menemukan Puskesmas (misal 0 Puskesmas dalam radius `1 km`), tampilkan komponen *Empty State* bersih menggunakan 1 ikon resmi `lucide-map-pin-off` (`32px`, warna `--text-muted`), pesan jelas (*"Tidak ditemukan Puskesmas dalam radius 1 km dari titik Anda"*), dan tombol aksi langsung **"Perluas ke Radius 3 km"** atau **"Reset Filter"**.
- **Saat Berhasil / Gagal (*Feedback & Error State*):**
  - Pesan gagal validasi form ditampilkan sebagai teks `14px` berwarna `--error` (`#B42318`) **tepat di bawah field terkait** (dihubungkan dengan `aria-describedby` dan `aria-invalid="true"`, tanpa kotak *alert* berborder merah muda), dan fokus keyboard otomatis diarahkan ke field pertama yang bermasalah.
  - Notifikasi status dinamis menggunakan atribut `role="alert"` atau `aria-live="polite"`.
