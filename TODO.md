# TODO: Implementasi Fitur Gallery Admin

## Status: Selesai

### 1. Update Model Gallery
- [x] Tambahkan field `description` ke model Gallery jika belum ada
- [x] Pastikan field `image` sudah ada untuk menyimpan path gambar

### 2. Buat Controller Gallery untuk Admin
- [x] Buat `GalleryController.php` di `app/Http/Controllers/Admin/`
- [x] Implementasikan method: index, create, store, show, edit, update, destroy
- [x] Tambahkan validasi untuk upload gambar
- [x] Implementasikan permission check untuk admin

### 3. Buat Routes untuk Gallery Admin
- [x] Tambahkan routes di `routes/web.php` dalam group admin
- [x] Routes: index, create, store, show, edit, update, destroy

### 4. Buat Views untuk Gallery Admin
- [x] Buat folder `resources/views/admin/galleries/`
- [x] Buat view `index.blade.php` - daftar gallery dengan tombol CRUD
- [x] Buat view `create.blade.php` - form tambah gallery
- [x] Buat view `edit.blade.php` - form edit gallery
- [x] Buat view `show.blade.php` - detail gallery

### 5. Update Tampilan Public Gallery
- [x] Modifikasi `resources/views/public/gallery.blade.php`
- [x] Ubah grid layout menjadi card-based dengan positioning:
  - 1 gambar: center
  - 2 gambar: left-right
  - 3 gambar: 1 center, 2 di samping
- [x] Pertahankan tampilan hero dan modal

### 6. Update Database Migration (jika perlu)
- [x] Periksa apakah perlu menambah field `description` atau `image`
- [x] Jalankan migration jika ada perubahan

### 7. Testing dan Verifikasi
- [ ] Test upload gambar
- [ ] Test CRUD operations
- [ ] Test tampilan public gallery
- [ ] Verifikasi permission admin

### 8. Update Sidebar Admin
- [x] Tambahkan menu "Galeri" di sidebar admin

### 9. Update Public Controller
- [x] Update method gallery() untuk mengirim data school profile ke view
- [x] Tambahkan orderBy untuk menampilkan gallery terbaru terlebih dahulu
