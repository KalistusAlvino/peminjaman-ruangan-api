# Peminjangan-ruangan-api

## Base URL
```bash
  https://kalistusapi.my.id/
```
## End-point
Login
```bash
  https://kalistusapi.my.id/api/login
```
```bash
  {
    "success": true,
    "message": "Login successful",
    "data": {
        "role": "pengguna",
        "token": "random_token"
    }
}
```

### 🔐 **Authentication**

This endpoint **requires authentication** using a **Bearer Token**.

Include your token in the request header as shown below:

```bash
Authorization: Bearer <your_access_token>
```

Example:
```bash
curl -X GET "http://kalistusapi.my.id/api/rooms" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6..." \
  -H "Accept: application/json"
```

Get Rooms
```bash
    GET https://kalistusapi.my.id/api/rooms
```
```bash
    Parameters can be add on: room_name (string) (optional) | date (string) (optional) (format="YYYY-MM-DD")
```
```bash
    GET https://kalistusapi.my.id/api/rooms?room_name=meeting&date=2025-10-19
```
```bash
  {
    "success": true,
    "message": "Rooms data fetched successfully",
    "data": [
        {
            "id": 1,
            "room_name": "Ruang Meeting A",
            "location": "Gedung Utama, Lantai 2",
            "capacity": "40",
            "description": "TV, AC, Proyektor",
            "bookings": []
        },
        {
            "id": 2,
            "room_name": "GOR",
            "location": "Gedung Selatan",
            "capacity": "50",
            "description": "Gawang, Ring, Bola",
            "bookings": [
                {
                    "id": 7,
                    "user": "user",
                    "date": "2025-10-18",
                    "start_time": "19:00:00",
                    "end_time": "21:00:00",
                    "purpose": "Olahraga Divisi IT",
                    "status": "approved"
                },
                {
                    "id": 13,
                    "user": "user",
                    "date": "2025-10-18",
                    "start_time": "20:00:00",
                    "end_time": "22:00:00",
                    "purpose": "Olahraga Divisi Marketing",
                    "status": "rejected"
                }
            ]
        }
    ]
}
```
Post Bookings
```bash
    https://kalistusapi.my.id/api/bookings
```
```bash
    Required parameter & example JSON Body

    {
        "room_id": 1,
        "date": "2025-10-19",
        "start_time": "08:00",
        "end_time": "10:30",
        "purpose": "Rapat koordinasi tim"
    }
```
```bash
    {
        "success": true,
        "message": "Successfully booking!. But your room already booked by user from 10:00:00 to 12:00:00. Please wait until the admin approve your booking.",
        "data": []
    }
```
Approval and rejection
```bash
    https://kalistusapi.my.id/api/bookings/approve/{booking_id}

    https://kalistusapi.my.id/api/bookings/reject/{booking_id}
```

```bash
    {
        "success": true,
        "message": "Booking approved successfully",
        "data": []
    }
```


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

## Keys / Passport Auth Installation.
Laravel Passport requires encryption keys to be generated before the authentication system can be used.
```bash
  php artisan passport:keys  
```
```bash
  php artisan passport:client --personal
```


