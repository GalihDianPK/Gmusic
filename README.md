# 📻 GMusic - Web Streaming Musik (Spotify Clone)

GMusic adalah platform streaming musik berbasis web sederhana yang terinspirasi dari antarmuka Spotify. Aplikasi ini dibangun menggunakan **PHP Native** untuk sisi logika backend, **MySQL** untuk database, **Tailwind CSS** untuk desain antarmuka modern (dengan dukungan Dark Mode), serta **JavaScript** untuk kontrol pemutaran audio yang dinamis tanpa memuat ulang (*reload*) halaman.

## ✨ Fitur Utama

### 👤 Pengguna (User)
- **Autentikasi Aman:** Registrasi dan Login dengan sistem keamanan enkripsi password (*bcrypt*).
- **Beranda Dinamis:** Ucapan salam otomatis berdasarkan waktu (*Good Morning/Afternoon/Evening*) dan rekomendasi lagu secara acak (*Quick Picks*).
- **Pemutar Musik Interaktif:** Kontrol musik *real-time* (Play, Pause, Next, Previous, Volume, Seekbar).
- **Sistem Antrean (Queue):** Lagu diputar berurutan dari daftar yang sedang diakses.
- **Daftar Putar (Playlist):** Membuat playlist kustom dan menambahkan/menghapus lagu di dalamnya.
- **Lagu yang Disukai (Liked Songs):** Menandai lagu favorit dengan ikon hati (like/unlike) secara dinamis menggunakan API fetch.

### 👑 Administrator (Admin Panel)
- **Dashboard Admin:** Melihat statistik daftar lagu yang terdaftar di database.
- **Manajemen Lagu (CRUD):** 
  - Mengunggah lagu baru (mengisi judul, artis, genre, lirik, file audio, dan gambar sampul).
  - Mengedit informasi lagu, lirik, atau mengganti file musik/gambar.
  - Menghapus lagu (otomatis menghapus file fisik di server lokal dan relasi lagunya).

---

## 🛠️ Tech Stack

- **Backend:** PHP Native (versi 7.4 ke atas)
- **Database:** MySQL
- **Frontend / UI:** Tailwind CSS (via CDN) & Google Material Symbols Icons
- **Logic Player:** Vanilla JavaScript & HTML5 Audio API

---

## 📊 Skema Database

Database yang digunakan bernama **`music_stream`**. Berikut adalah tabel-tabel penyusunnya:
1. **`users`:** Menyimpan data kredensial pengguna (admin & user umum).
2. **`songs`:** Menyimpan informasi lagu (judul, artis, genre, lirik, path file audio, path file sampul).
3. **`playlists`:** Menyimpan daftar putar yang dibuat oleh pengguna.
4. **`playlist_songs`:** Tabel pivot (perantara) relasi *Many-to-Many* antara playlist dan lagu.
5. **`liked_songs`:** Tabel pivot relasi *Many-to-Many* untuk lagu yang disukai pengguna.

---

## 🚀 Cara Instalasi & Menjalankan di Lokal (XAMPP)

### 1. Persiapan Folder Proyek
1. Pastikan Anda sudah menginstal **XAMPP**.
2. Salin folder proyek ini ke direktori htdocs XAMPP Anda:
   ```bash
   C:\xampp\htdocs\web-programan
   ```

### 2. Impor Database
1. Jalankan **Apache** dan **MySQL** di XAMPP Control Panel.
2. Buka browser dan buka alamat: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
3. Buat database baru dengan nama `music_stream`.
4. Klik tab **SQL**, salin dan tempel isi dari file `database.sql` ke kolom SQL tersebut, lalu klik **Go**.

### 3. Migrasi Tabel Tambahan
Buka browser Anda dan jalankan file migrasi berikut untuk melengkapi tabel playlist dan likes:
- [http://localhost/web-programan/migrate.php](http://localhost/web-programan/migrate.php)
- [http://localhost/web-programan/migrate_likes.php](http://localhost/web-programan/migrate_likes.php)

### 4. Buka Aplikasi
Aplikasi sekarang siap digunakan melalui URL berikut:
- **Aplikasi (User & Landing):** [http://localhost/web-programan/](http://localhost/web-programan/)
- **Halaman Login:** [http://localhost/web-programan/login.php](http://localhost/web-programan/login.php)
- **Dashboard Admin:** [http://localhost/web-programan/admin/](http://localhost/web-programan/admin/)

---

## 🔑 Akun Demo (Uji Coba)

Anda dapat menggunakan akun uji coba di bawah ini untuk mencoba sistem:

| Peran (Role) | Username / Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `Admin` / `admin@musicstream.com` | `admin123` |
| **User Biasa** | `User Test` / `user@example.com` | `admin123` |

---

## Testing Hosting
(Ketik di CMD) 
ngrok config add-authtoken 3ERPYSRhje3P69Ha8ywptbUnYi1_59pGwySJQtkyimgGrSjVH

ngrok http 80

lalu buka link ini :
https://arrogance-gentile-busboy.ngrok-free.dev/web-programan/

---

## 📂 Struktur Direktori Proyek

```bash
web-programan/
│
├── admin/               # Halaman pengelolaan lagu oleh Admin (CRUD)
│   ├── index.php        # Halaman utama daftar lagu admin
│   ├── tambah.php       # Form unggah lagu baru
│   ├── edit.php         # Form edit lagu
│   └── hapus.php        # Script penghapusan lagu
│
├── uploads/             # Folder penyimpanan file unggahan
│   ├── covers/          # Sampul album / gambar lagu
│   └── songs/           # File audio (MP3) lagu
│
├── koneksi.php          # Konfigurasi koneksi PHP ke MySQL
├── database.sql         # Script SQL dasar untuk setup database
├── index.php            # Halaman utama aplikasi (Player & Dashboard)
├── login.php            # Form masuk akun
├── register.php         # Form pendaftaran akun baru
├── logout.php           # Proses keluar akun
├── playlist.php         # Halaman daftar playlist
├── liked.php            # Halaman lagu yang disukai
├── toggle_like.php      # API backend penanganan tombol like
├── migrate.php          # Skrip migrasi tabel playlist
├── migrate_likes.php    # Skrip migrasi tabel liked_songs
├── header.php           # Kerangka atas UI dan navigasi sidebar
└── footer.php           # Kerangka bawah UI dan Audio Player
```
