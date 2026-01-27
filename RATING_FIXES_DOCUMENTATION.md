# Dokumentasi Perbaikan Rating System

## Masalah yang Diperbaiki:
1. ✅ **Rating/Ulasan tidak terkirim** - Field form tidak sesuai dengan validation
2. ✅ **Hanya satu buku** - Sekarang bisa rate multiple books sekaligus
3. ✅ **Tidak ada update** - Sekarang bisa edit rating/ulasan yang sudah ada

---

## File yang Diubah:

### 1. **app/Http/Controllers/RatingController.php**
- `show()`: Sekarang menampilkan SEMUA peminjaman (belum & sudah di-rating)
- `store()`: Fixed - menerima `peminjaman_id`, `buku_id`, `rating`, `ulasan`
- `update()`: Baru - untuk update rating yang sudah ada
- `destroy()`: Baru - untuk hapus rating

### 2. **routes/web.php**
- `GET /rating/show` → RatingController@show (tampilkan semua buku)
- `POST /rating/store` → RatingController@store (create/update)
- `PUT /rating/{id}` → RatingController@update (edit existing)
- `DELETE /rating/{id}` → RatingController@destroy (delete)

### 3. **resources/views/peminjaman/rating_list.blade.php** (NEW)
- Menampilkan 2 tab: Belum Diberi Rating | Sudah Diberi Rating
- Setiap buku punya modal rating sendiri
- Form dengan hidden input: `peminjaman_id`, `buku_id`, `rating`, `ulasan`
- Fitur edit & delete untuk rating yang sudah ada

### 4. **resources/views/peminjaman/rating.blade.php**
- Diubah menjadi redirect ke rating_list.blade.php

### 5. **resources/views/peminjaman/index.blade.php**
- Tambah button "Rating & Ulasan" di header untuk akses cepat

---

## Cara Test:

### Test 1: Buka Halaman Rating
```
1. Login ke dashboard
2. Click "Rating & Ulasan" button di halaman Peminjaman
3. Atau: https://yoursite/rating/show
```

### Test 2: Berikan Rating (Buku Belum Diberi Rating)
```
1. Buka tab "Belum Diberi Rating"
2. Click "Beri Rating" button pada buku
3. Pilih bintang (1-5)
4. (Opsional) Isi ulasan
5. Click "Kirim Rating"
```

### Test 3: Edit Rating (Buku Sudah Diberi Rating)
```
1. Buka tab "Sudah Diberi Rating"
2. Click "Edit Rating" button
3. Ubah bintang dan/atau ulasan
4. Click "Simpan Perubahan"
```

### Test 4: Hapus Rating
```
1. Buka tab "Sudah Diberi Rating"
2. Click tombol hapus (trash icon)
3. Konfirmasi delete
```

---

## Validasi yang Diimplementasikan:

✅ Rating wajib ada (1-5)
✅ Ulasan opsional (max 500 karakter)
✅ Tidak bisa rating 2x untuk buku yang sama
✅ Field peminjaman_id dan buku_id wajib ada
✅ Status peminjaman harus "Kembali"
✅ Ownership check - user hanya bisa edit/delete rating mereka sendiri

---

## Database Schema:

### ratings table
```php
- id
- user_id (FK users)
- buku_id (FK bukus)
- peminjaman_id (FK peminjamen)
- rating (tinyInteger 1-5)
- review (text)
- timestamps
- unique(user_id, buku_id)
```

---

## Important Notes:

⚠️ Field nama di form harus:
- `peminjaman_id` (hidden)
- `buku_id` (hidden)
- `rating` (radio input 1-5)
- `ulasan` (textarea)

⚠️ Route harus dalam middleware `auth` group
⚠️ Table structure sudah ada (migration: 2025_11_26_064625)

