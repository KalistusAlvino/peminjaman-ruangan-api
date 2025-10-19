# Peminjangan-ruangan-api



## Installation

Setelah melakukan clone repository, buat file ".env" didalam project laravel. <br>

Copy semua code yang ada didalam ".env.example" kedalam file ".env", setelah itu sesuaikan koneksi untuk database yang akan digunakan. <br>

Jalankan beberapa perintah dibawah secara berurutan
```bash
  composer install
```

```bash
  php artisan key:generate
```
## Database Installation.

Setelah melakukan beberapa langkah diatas, dan sudah menyesuaikan koneksi untuk database pada file ".env" berikut perintah untuk melakukan installasi database.
```bash
  php artisan migrate
```
