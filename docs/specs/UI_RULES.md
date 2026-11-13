# UI_RULES.md

## Aturan Tampilan UI

Tujuan: minimalis tetapi tidak datar. Hierarki dan ritme datang dari tipografi, jarak, dan kontras, bukan dari kotak, garis, atau dekorasi.

Aturan ini terdiri dari tiga tingkat. Tingkat 1A tidak bisa ditawar. Tingkat 1B hanya bisa dikecualikan lewat pengecualian eksplisit dari pengguna (lihat bagian konflik di bawah). Tingkat 2 boleh dipakai bila lolos uji kelayakannya. Tingkat 3 adalah ruang bebas dengan arahan. Jika ada yang bertabrakan, tingkat yang lebih kecil menang.

### Jika permintaan pengguna bertentangan dengan aturan ini
- AI tidak berhenti dan tidak menolak seluruh tugas. Bagian yang bertentangan tidak dikerjakan, sisanya tetap dikerjakan.
- Menolak hanya bila bisa menunjuk aturan tertulis di file ini yang melarangnya. Tingkat 3 adalah arahan, bukan alasan menolak. Jangan menolak berdasarkan dugaan (misalnya mengira akan butuh overlay atau efek tertentu).
- Permintaan kabur atau berisi pilihan ("A atau B"): kerjakan opsi atau tafsir terdekat yang patuh, tolak hanya bagian yang bermasalah, dan sebut tafsirnya di laporan.
- Tingkat 1A (kegunaan dan aksesibilitas): tidak bisa diubah lewat instruksi biasa. Tolak bagian itu dan pakai alternatif terdekat yang patuh. Aturan ini hanya berubah bila file ini diedit.
- Tingkat 1B (larangan selera): hanya bisa dikecualikan bila instruksi/spesifikasi pengguna menyebut dengan jelas apa yang dikecualikan dan di elemen mana (misalnya "pakai icon pada tombol unduh"). Permintaan umum seperti "buat lebih menarik" atau "tambahkan ikon" tidak membatalkan larangan.
- Tingkat 2 dan 3: permintaan eksplisit dari pengguna dianggap sudah lolos uji kelayakan, tidak perlu alasan.
- Jika bagian permintaan saling bertentangan, ikuti yang lebih spesifik.
- Jika ada penyesuaian atau pengecualian aturan UI ini, tambahkan catatan satu baris di akhir jawaban (tanpa penjelasan panjang). Format: "Tafsiran: [yang dikerjakan]. Tidak diterapkan: [permintaan]. Alasan: [aturan]. Pengganti: [yang dipakai]." atau "Pengecualian dipakai: [apa]." Bagian yang tidak relevan dihilangkan. Jangan melanggar atau mengabaikan permintaan secara diam-diam.

### Tingkat 1A: Standar kegunaan dan aksesibilitas (acuan WCAG 2.2)
- Kontras teks minimal 4.5:1 (teks besar 3:1). Batas komponen interaktif (border input) dan icon yang membawa makna minimal 3:1 terhadap latar. Pemisah dekoratif seperti baris tabel dikecualikan.
- Fokus keyboard selalu terlihat: outline 2px warna aksen, offset 2px, tidak tertutup elemen lain.
- Status tidak hanya mengandalkan warna. Selalu sertakan kata label.
- Target sentuh: tinggi input dan tombol minimal 44px, jarak antar target minimal 8px. Link teks mencapai area sentuh lewat padding vertikal tanpa mengubah jarak antar elemen.
- Setiap field punya label permanen di atasnya. Placeholder tidak menggantikan label.
- Pesan error menyebut masalahnya dan cara memperbaikinya, ditaruh langsung di bawah field-nya (sebelum teks bantu atau link lain), dan dihubungkan dengan aria-describedby dan aria-invalid.
- Pesan yang muncul dinamis (error, berhasil, memproses) memakai role="alert" atau aria-live agar terbaca pembaca layar. Saat validasi gagal, fokus dipindah ke field pertama yang error.
- Semua fungsi bisa dipakai dengan keyboard. Gunakan HTML semantik: button untuk aksi, a untuk navigasi, satu h1, heading berurutan, lang="id".
- Tetap terpakai pada zoom 200% dan lebar 320px tanpa scroll horizontal. Ukuran font memakai rem.
- Hormati prefers-reduced-motion: transisi dimatikan.
- Form login: jangan blokir paste dan password manager (autocomplete="username" dan "current-password").

### Tingkat 1B: Larangan bawaan (selera)
- Emoji, di UI, teks, maupun kode.
- Dark mode. Gunakan light mode saja.
- Opacity, rgba, dan backdrop-blur pada elemen. State disabled memakai warna abu solid. Satu-satunya pengecualian: warna shadow elemen melayang, ditulis sekali sebagai variabel --shadow.
- Border tebal atau mencolok. Border selalu 1px, tanpa warna gelap yang menonjol. Satu-satunya pengecualian warna: border field yang tidak valid memakai --error, disertai teks error.
- Membungkus satu teks, pesan, atau kelompok kecil informasi dengan kotak berborder atau berbackground (callout, alert box, info box), termasuk garis aksen di sisi kotak. Pesan ditampilkan sebagai teks biasa berwarna sesuai status.
- Gradient.
- Palet merah-kuning-hijau untuk status. Merah hanya untuk error dan bukan merah generik. Berhasil: teal. Warning: oranye kecokelatan. Info: warna aksen.
- Menulis warna langsung (hex) di luar variabel. Semua warna lewat variabel CSS.

### Tingkat 2: Minimalkan, dengan uji kelayakan
Pakai hanya bila lolos ujinya. Jika tidak lolos, jangan dipakai.
- Icon. Uji: apakah icon dipahami lebih cepat daripada teks? Satu set icon bergaya garis yang sama, tidak dibungkus card atau lingkaran.
- Badge. Uji: tanpa badge, apakah status jadi tidak terbaca? Maksimal 1 per komponen.
- Card. Uji: apakah item-item sejenis perlu dipisah sebagai unit (bisa diklik, dipindah, dibandingkan)? Jangan pakai card hanya untuk merapikan.
- Garis pemisah. Uji: apakah dua bagian sudah terbedakan oleh jarak 48px? Jika ya, jangan pakai garis. Footer tidak diberi garis di atasnya. Pengecualian yang selalu lolos: baris tabel data dan penanda tab atau menu aktif (boleh setebal 2px).
- Shadow. Hanya untuk elemen melayang (modal, dropdown, tooltip).
- Animasi. Hanya transisi fungsional 120 sampai 160ms pada hover, fokus, dan perubahan state. Tidak ada animasi dekoratif.
- Pill. Bentuk bulat penuh hanya bila membedakan makna (misalnya tag filter), bukan untuk tombol atau input.
- Latar tint pada region layout. Uji: apakah area (header, sidebar, panel utama) perlu dibedakan dari konten? Pakai tint solid, bukan border. Region besar ini boleh berisi teks, tetapi tidak dipakai untuk membungkus satu pesan atau satu paragraf.
- Gambar dekoratif, ilustrasi, dan aksen visual. Uji: apakah diminta eksplisit oleh pengguna atau membawa informasi? Jika ya, boleh, asalkan teks tidak langsung di atas gambar (teks ditaruh di area solid), kontras Tingkat 1A tetap lolos, dan sumbernya hanya CSS, SVG inline, atau aset yang disediakan pengguna. Jangan mengarang atau menautkan URL gambar dari internet. Aksen dekoratif memakai warna dari variabel dan boleh ditaruh di atas region berlatar tint.

### Tingkat 3: Bebas, dengan arahan
AI bebas memilih komposisi selama mengikuti arahan berikut.
- Satu titik fokus per layar: judul dan tindakan utama. Elemen lain lebih kecil atau memakai warna teks sekunder. Idealnya satu tombol utama per layar, tombol lain berupa teks atau link (aksi destruktif seperti hapus memakai warna --error).
- Rancang untuk layar kecil dulu, lalu perlebar. Tata letak rata kiri dan lega. Wadah halaman utama (container) maksimal 1120px sampai 1200px, form maksimal 400px, teks bacaan maksimal 65ch.
- Tipografi satu font dengan fallback font sistem. Hierarki dari ukuran dan bobot. Acuan: 14, 16, 20, 28px dan bobot 400, 500, 600. Teks isi 16px dengan line-height 1.5, judul line-height 1.2 sampai 1.3 dengan letter-spacing sedikit rapat. Judul memakai text-wrap: balance dan paragraf memakai text-wrap: pretty agar tidak ada kata yatim di baris terakhir.
- Tabel dan angka: teks rata kiri, kolom angka atau harga rata kanan dengan font-variant-numeric: tabular-nums agar digit sejajar lurus dari atas ke bawah, tanpa garis pembatas vertikal.
- Jarak memakai skala 4, 8, 12, 16, 24, 32, 48, 64. Ritme form: label ke input 8px, antar field 24px, input terakhir ke tombol 32px, antar bagian 48px. Jarak label ke inputnya selalu lebih kecil daripada jarak antar field supaya pengelompokan jelas.
- Tiga warna utama: dasar, teks, aksen. Turunan netral (tint solid) boleh untuk border, teks sekunder, dan latar region. Aksen terutama untuk tombol utama, link, fokus, dan state aktif atau terpilih. Pemakaian lain (misalnya aksen dekoratif) hanya bila diminta, lihat Tingkat 2. Warna status (error, sukses, warning) hanya untuk pesan status dan tidak dihitung sebagai warna utama.
- Link berwarna aksen. Link aksi (Lupa kata sandi, Tampilkan sandi) tanpa garis bawah, bergaris bawah saat hover dan fokus. Link di dalam paragraf bergaris bawah. Link rata dengan tepi komponen di dekatnya, tanpa padding horizontal.
- Radius konsisten. Acuan: 6px untuk kontrol, 12px untuk elemen melayang.
- Setiap elemen interaktif punya state: default, hover, fokus, aktif, disabled. Komponen yang sama selalu tampil sama di seluruh halaman.
- Aksi yang butuh waktu menampilkan state loading (teks tombol berubah, tombol nonaktif sementara), dan hasilnya diberi tahu lewat teks.

### Implementasi
Semua warna didefinisikan sebagai variabel CSS di satu tempat. Nilai awal di bawah sudah dicek kontrasnya (boleh diganti per proyek, tetapi harus tetap lolos Tingkat 1A):

```css
:root {
  color-scheme: light;
  --base: #FFFFFF;
  --surface: #F6F7F9;
  --text: #14171F;                /* 17.9:1 pada base */
  --text-muted: #5B6270;          /* 6.1:1 pada base, 5.7:1 pada surface */
  --border: #E2E5EA;              /* dekoratif saja (baris tabel), bukan untuk input */
  --border-control: #858D9B;      /* border input, 3.3:1 pada base, 3.1:1 pada surface */
  --border-control-hover: #6B7280;
  --accent: #1F4E79;              /* 8.7:1 pada base */
  --accent-hover: #173A5C;
  --error: #B42318;               /* 6.6:1 pada base */
  --success: #0F766E;             /* 5.5:1 pada base */
  --warning: #B54708;             /* 5.4:1 pada base */
  --shadow: 0 4px 16px rgba(20, 23, 31, 0.12); /* satu-satunya rgba, hanya untuk elemen melayang */
}
```

Jika memakai Tailwind lewat CDN, atur tema warnanya ke variabel di atas dan jangan pakai kelas warna bawaan (seperti text-gray-500 atau bg-blue-600).

Jika ragu, pilih yang paling rapi dan konsisten. Jangan menghapus elemen fungsional (label, state fokus, pesan error, kontrol tampilkan sandi) hanya agar terlihat sederhana.

### Contoh
- Pesan error. Benar: teks warna --error 14px di bawah field, "Kata sandi minimal 8 karakter." Salah: kotak merah muda berborder dengan icon.
- Pemisah bagian. Benar: jarak 48px. Salah: hr atau garis di atas footer.
- Info tambahan. Benar: satu baris teks sekunder di bawah judul. Salah: teks dibungkus kotak abu berborder.
- Badge. Benar: "Menunggu" pada baris pesanan yang tidak punya keterangan status lain. Salah: badge "Baru" di samping judul halaman.
- Border input. Benar: 1px abu menengah (3:1). Salah: abu sangat pucat yang nyaris hilang di latar putih.
- Posisi error. Benar: error langsung di bawah field, lalu baris Tampilkan sandi dan Lupa kata sandi. Salah: error ditaruh di bawah baris link.
