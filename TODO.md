# TODO: Update Fitur Pengumuman

## Deskripsi
Memperbarui fitur pengumuman agar hanya admin yang bisa membuat pengumuman, sedangkan guru, siswa, orang tua, dan public hanya bisa melihat pengumuman yang dibuat oleh admin jika admin ingin mempublishnya.

## Langkah-langkah

### 1. Hapus Kemampuan Guru Membuat Pengumuman
- [x] Hapus routes teacher announcements di `routes/web.php`
- [x] Hapus methods announcements di `TeacherController.php`
- [x] Cek dan hapus view teacher announcements jika ada

### 2. Update Query Pengumuman di TeacherController
- [x] Update method `announcements()` di `TeacherController.php` untuk hanya melihat pengumuman dari admin yang published

### 3. Verifikasi Query di Controller Lain
- [ ] AdminController: sudah benar (bisa lihat semua)
- [ ] StudentController: sudah benar (hanya lihat yang published)
- [ ] ParentController: sudah benar (hanya lihat yang published)
- [ ] PublicController: sudah benar (hanya lihat yang published)

### 4. Testing
- [x] Test login sebagai admin: bisa buat, edit, hapus pengumuman
- [x] Test login sebagai guru: hanya bisa lihat pengumuman admin yang published
- [ ] Test login sebagai siswa: hanya bisa lihat pengumuman admin yang published
- [ ] Test login sebagai orang tua: hanya bisa lihat pengumuman admin yang published
- [ ] Test public: hanya bisa lihat pengumuman admin yang published
