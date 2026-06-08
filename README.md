<p align="center">
  <img src="https://img.icons8.com/fluent/96/000000/laundry.png" alt="Clean Time Logo" width="80" />
</p>

<h1 align="center">✨ Clean Time - Web Admin & API Backend ✨</h1>

<p align="center">
  <strong>Sistem Manajemen Laundry Eksklusif Modern Berbasis Cloud & Terintegrasi Mobile App</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind" />
  <img src="https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
</p>

<p align="center">
  <a href="#-fitur-unggulan">Fitur Utama</a> •
  <a href="#-arsitektur-teknologi">Tech Stack</a> •
  <a href="#-cara-instalasi">Instalasi</a> •
  <a href="#-endpoint-api">Dokumentasi API</a>
</p>

---

## 📖 Tentang Clean Time

**Clean Time** adalah ekosistem aplikasi laundry premium yang dirancang untuk menjembatani operasional internal toko dengan kemudahan akses pelanggan. 

Repositori ini menyimpan *source code* untuk **Web Dashboard Admin** (sisi manajemen internal) sekaligus **RESTful API Engine** yang mengamankan arus data untuk aplikasi mobile pelanggan berbasis Expo/React Native.

---

## 🚀 Fitur Unggulan

### 💻 Sisi Web Administrator (Back-Office)
* **Manajemen Pelanggan Pintar (CRUD):** Kelola profil, alamat penjemputan, dan kontak pelanggan dalam satu baris efisien.
* **Aksi Massal Kilat (Bulk Action Delete):** Efisiensi waktu admin dengan fitur hapus banyak data sekaligus menggunakan sistem check-all.
* **Sistem Proteksi Human-Error:** Integrasi penuh dengan **SweetAlert2** untuk pop-up konfirmasi yang elegan dan aman sebelum mengeksekusi penghapusan data.
* **Pencarian Real-Time Dinamis:** Filter data pelanggan secara instan lewat *keyword* nama, email, maupun nomor telepon tanpa merusak pagination data.

### 🔐 Sisi Backend & Integrasi Mobile
* **Database Transaksional (Atomicity):** Menggunakan `DB::beginTransaction()` untuk menjamin data tabel `users` dan `customers` selalu sinkron sempurna dan mencegah korup data.
* **RESTful Bridge:** Jalur distribusi API super cepat menggunakan enkripsi token untuk otentikasi login aplikasi mobile pelanggan.

---

## 🛠️ Arsitektur Teknologi

Aplikasi ini dibangun menggunakan kombinasi teknologi modern demi menghasilkan performa terbaik:

* **Core Framework:** Laravel 12 (Robust & Secure)
* **Frontend UI Engine:** Laravel Blade Templates & Tailwind CSS (Utility-First Framework)
* **Interaktivitas:** Vanilla JavaScript (ES6+) & SweetAlert2 Plugin
* **Database Driver:** MySQL / MariaDB dengan optimasi *Eloquent Relationship (Eager Loading)*

---

## ⚙️ Cara Instalasi

Ikuti 7 langkah mudah berikut untuk menjalankan proyek di perangkat lokal Anda:

### 1. Kloning Repositori
```bash
git clone [https://github.com/username/clean-time-backend.git](https://github.com/username/clean-time-backend.git)
cd clean-time-backend
