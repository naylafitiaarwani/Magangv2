# Magang 

Aplikasi web berbasis Laravel 11 untuk pengelolaan wisata, budaya, kuliner, UMKM, dan CMS.

## Persyaratan

- PHP 8.2 atau lebih baru, dengan ekstensi PHP Laravel termasuk `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, dan `zip`.
- Composer 2.
- Node.js dan npm.
- MySQL atau MariaDB yang berjalan lokal.
- Git.

Di Windows, Laragon dapat digunakan untuk menyediakan PHP, MySQL, dan terminal. Pastikan versi PHP yang aktif minimal 8.2.

## Setup lokal

1. Clone repository dan masuk ke folder project:

    ```bash
    git clone https://github.com/naylafitiaarwani/Magangv2.git
    cd Magangv2
    ```

2. Pasang dependency backend dan asset Laravel:

    ```bash
    composer install
    npm install
    ```

    `npm install` di folder ini hanya memasang Vite dan asset untuk Laravel, bukan aplikasi React.

3. Buat file konfigurasi lokal `.env` dengan menyalin template. Di PowerShell (Windows):

    ```bash
    cp .env.example .env
    ```

    Buka `.env`, lalu sesuaikan konfigurasi database dengan mesin masing-masing. Buat database kosong bernama `magang_contoh` di MySQL/MariaDB (misalnya melalui phpMyAdmin), lalu pastikan bagian ini cocok dengan nama database, username, dan password lokal:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=magang_contoh
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    Jangan commit `.env` atau membagikan `APP_KEY`, kredensial database, maupun API key. File `.env` bersifat lokal dan sudah diabaikan Git.

4. Buat application key, jalankan migration dan data awal, lalu buat symbolic link untuk file upload:

    ```bash
    php artisan key:generate
    php artisan migrate --seed
    php artisan storage:link
    ```

5. Jalankan backend Laravel dan Vite asset Laravel di dua terminal dari folder project backend:

    ```bash
    php artisan serve
    ```

    ```bash
    npm run dev
    ```

    Buka URL yang ditampilkan oleh `php artisan serve` (biasanya `http://127.0.0.1:8000`). Untuk membuat asset produksi Laravel, jalankan `npm run build`.

## Setup frontend React terpisah

Frontend React belum termasuk di repository backend ini. Jika tim menyimpan frontend di repository terpisah, setiap collaborator perlu clone kedua repository. Ganti `<URL-REPOSITORY-FRONTEND>` dengan URL repo React tim:

```bash
git clone <URL-REPOSITORY-FRONTEND> frontend
cd frontend
npm install
```

Buat file `.env.local` di folder frontend. Untuk Vite + React, isi alamat API backend lokal:

```dotenv
VITE_API_BASE_URL=http://127.0.0.1:8000/api
```

Pastikan kode React membaca variabel tersebut, misalnya `import.meta.env.VITE_API_BASE_URL`, dan gunakan sebagai dasar URL endpoint API. Jangan commit `.env.local`; konfigurasi lokal tiap collaborator dapat berbeda. Jika repository frontend menyediakan `.env.example`, salin file tersebut menjadi `.env.local` lalu isi nilainya.

Jalankan backend dari folder backend (`php artisan serve`) dan frontend dari folder React (`npm run dev`) pada terminal berbeda. Buka URL yang dicetak oleh Vite React, biasanya `http://localhost:5173`. Endpoint pemeriksaan backend dapat diuji di `http://127.0.0.1:8000/api/health-check`.

Backend saat ini mengizinkan CORS dari semua origin untuk development. Untuk production, batasi `allowed_origins` di `config/cors.php` ke domain frontend yang benar.

## Akun development

Seeder membuat akun lokal berikut:

- Email: `superadmin@local.com`
- Password: `super123`

Akun ini hanya untuk development lokal. Jangan gunakan kredensial tersebut di production; ganti password atau hapus akun setelah setup sesuai kebutuhan.

## Integrasi pembayaran

Fitur pembayaran Midtrans memerlukan `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` di `.env`. Untuk pekerjaan yang tidak menguji pembayaran, kedua nilai tersebut dapat dibiarkan kosong. Jangan pernah menaruh key asli di repository.

## Troubleshooting

- Jika koneksi database gagal, pastikan service MySQL aktif dan nilai `DB_*` di `.env` sesuai.
- Jika perubahan konfigurasi tidak terbaca, jalankan `php artisan config:clear`.
- Jika Artisan menyatakan tabel cache/session belum ada, pastikan `php artisan migrate --seed` sudah berhasil.
- Jika gambar upload tidak tampil, jalankan kembali `php artisan storage:link`.
