
# Rise Bowl (Laravel) — Install Guide

Panduan ini menjelaskan cara instalasi project Laravel ini, konfigurasi database MySQL, set `.env` custom, dan kredensial admin untuk login ke portal CMS.

## 1) Prasyarat

Pastikan sudah ter-install:

- PHP (disarankan 8.2+)
- Composer
- Node.js + npm
- MySQL Server

Opsional (membantu di Windows):

- Git

## 2) Setup project

### A. Clone repository

Jalankan:

`git clone https://github.com/MuhamadAfghan/food-delivery.git`

Masuk ke folder project:

`cd food-delivery`

Kalau kamu sudah punya folder project ini (mis. sudah di-download), cukup pastikan kamu ada di root project (yang ada file `artisan`).

### B. Install dependency & setup

Di root folder project:

1. Install dependency PHP:

	`composer install`

2. Install dependency frontend:

	`npm install`

3. Buat file environment:

	Copy `.env.example` menjadi `.env`.

4. Generate app key:

	`php artisan key:generate`

## 3) Konfigurasi Database MySQL

### A. Buat database

Buat database baru di MySQL, contoh:

- Database name: `risebowl`
- Collation (opsional): `utf8mb4_unicode_ci`

Contoh SQL:

```sql
CREATE DATABASE risebowl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### B. Atur `.env`

Edit `.env` dan set variabel DB berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=risebowl
DB_USERNAME=root
DB_PASSWORD=
```

Catatan:

- Sesuaikan `DB_USERNAME`/`DB_PASSWORD` dengan user MySQL kamu.
- Jika MySQL kamu pakai password, isi `DB_PASSWORD`.

## 4) Konfigurasi ENV Custom (Rise Bowl)

Project ini punya beberapa env custom untuk fitur WhatsApp checkout dan kontak di landing page.

Tambahkan/ubah ini di `.env`:

### A. Nomor WhatsApp untuk checkout

Gunakan format internasional TANPA tanda `+` (sesuai `wa.me`), contoh Indonesia:

```env
RISEBOWL_WHATSAPP_NUMBER="6281234567890"
```

### B. Nomor kontak landing page

Untuk memisahkan format tampilan dan format link `tel:`:

```env
RISEBOWL_CONTACT_PHONE_DISPLAY="+62 812-3456-7890"
RISEBOWL_CONTACT_PHONE_TEL="+6281234567890"
```

Catatan:

- `RISEBOWL_CONTACT_PHONE_TEL` sebaiknya hanya angka + awalan `+` (tanpa spasi) supaya `tel:` kompatibel.

## 5) Jalankan Migration + Seeder

Untuk membuat tabel dan mengisi data awal (admin + contoh produk):

`php artisan migrate --seed`

Seeder yang dijalankan akan membuat:

- User admin (untuk portal `/admin`)
- Produk awal untuk tampil di `/menu`

## 6) Jalankan aplikasi

### Opsi A: Jalankan dev server Laravel

`php artisan serve`

Lalu buka:

- Landing page: `http://127.0.0.1:8000/`
- Menu: `http://127.0.0.1:8000/menu`
- Cart: `http://127.0.0.1:8000/cart`
- Admin: `http://127.0.0.1:8000/admin` (butuh login)

### Opsi B: Build assets (kalau perlu)

Untuk build production:

`npm run build`

Untuk development (watch):

`npm run dev`

## 7) Kredensial Admin Login

Portal admin ada di `/admin`.

Default admin (dibuat lewat seeder):

- Email: `admin@risebowl.test`
- Password: `admin12345`

Jika ingin ganti kredensial default ini, ubah di seeder:

- `database/seeders/AdminUserSeeder.php`

Lalu jalankan ulang seeder:

`php artisan db:seed --class=AdminUserSeeder`

## 8) Upload Foto Produk

Di portal admin, saat upload foto produk, file akan disimpan ke:

- `public/images/products/`

Dan path-nya disimpan ke database sehingga foto bisa tampil di halaman menu.

## 9) Troubleshooting singkat

### A. Error koneksi database

- Pastikan MySQL service berjalan
- Cek `.env` bagian `DB_*`
- Pastikan database sudah dibuat

### B. Checkout WhatsApp tidak jalan

- Pastikan `RISEBOWL_WHATSAPP_NUMBER` sudah terisi
- Pastikan cart tidak kosong

