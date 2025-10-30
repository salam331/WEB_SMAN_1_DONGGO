# TODO: Ubah Tampilan Notifikasi Admin

## Tugas Utama
- [x] Ubah tampilan notifikasi di halaman admin menjadi toast notification yang muncul di kanan atas (dekat logout) dan otomatis hilang setelah 1 detik.

## Langkah-langkah
1. [x] Analisis struktur notifikasi saat ini di `resources/views/layouts/app.blade.php`.
2. [x] Hapus alert lama (success dan error) dari layout.
3. [x] Tambahkan container toast notification dengan posisi fixed di kanan atas.
4. [x] Tambahkan CSS untuk styling toast (hijau untuk success, merah untuk error, animasi fade-in/out).
5. [x] Tambahkan JavaScript untuk menampilkan toast berdasarkan session dan auto-hide setelah 1 detik.
6. [x] Tambahkan komentar pada setiap baris kode untuk memudahkan penyesuaian (dalam Bahasa Indonesia).
7. [x] Test notifikasi di beberapa halaman admin (create/edit data) untuk memastikan berfungsi.

## Catatan
- Pastikan tidak menghapus data di database.
- Gunakan Bahasa Indonesia untuk komentar.
- Notifikasi harus otomatis hilang setelah 1 detik.
