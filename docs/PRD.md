# PRODUCT REQUIREMENTS DOCUMENT (PRD)
**Project Name:** BI Siantar Mengajar System
**Platform:** Web Application (Frontend + CMS Backend)

## 1. Tech Stack & Architecture
Sistem ini menggunakan arsitektur Headless (Pemisahan ketat antara Frontend dan Backend API).

* **Backend:** Laravel (Fokus sebagai penyedia RESTful API). Autentikasi menggunakan Laravel Sanctum.
* **Frontend:** Next.js (App Router/Pages Router) untuk SEO dan performa rendering yang maksimal, dikombinasikan dengan Tailwind CSS untuk pengaturan tata letak.
* **Database:** MySQL (Relasional).
* **Deployment & CI/CD:** Menggunakan pipeline otomatis (misal: GitHub Actions) untuk memastikan rilis berjalan mulus ke server public tanpa downtime.

## 2. Security & High Availability (Keamanan & Anti-Downtime)
Untuk mencegah hacking, spam, dan server down, terapkan lapisan keamanan berikut di sisi Backend Laravel:

* **Login Throttling (Anti-Spam/Brute Force):** Fitur throttle Laravel wajib diaktifkan. Batasi percobaan login (misal: maksimal 5 kali gagal dalam 1 menit, IP akan diblokir sementara). Gunakan Rate Limiting pada endpoint publik (terutama form pengajuan) maksimal 10 request per menit per IP.
* **Anti-SQL Injection (SQLi):** Larang keras penggunaan Raw Queries. AI wajib menggunakan Laravel Eloquent ORM atau Query Builder di seluruh controller untuk memastikan semua input di-binding (PDO prepared statements) secara otomatis.
* **Cross-Site Scripting (XSS) & CSRF:** Gunakan komponen middleware CORS yang dikonfigurasi ketat (hanya menerima request dari domain Next.js). Frontend Next.js harus menggunakan mekanisme sanitasi saat me-render artikel HTML dari CMS.
* **Validation:** Wajib menggunakan Form Request Validation bawaan Laravel di sisi API untuk memfilter input yang tidak valid sebelum menyentuh controller.

## 3. Core Features & CMS Requirements (Admin Panel)
Sistem CMS dibangun secara penuh untuk administrator. Seluruh entitas di bawah ini wajib memiliki fitur CRUD (Create, Read, Update, Delete) melalui API.

### A. Modul Materi Edukasi
* **Field Tabel:** `id`, `judul`, `slug`, `kategori` (Kebanksentralan, QRIS, CBP Rupiah), `konten_html`, `thumbnail_url`, `status_publish`, `created_at`.
* **Fungsi Admin:** Menambah modul edukasi, upload gambar menggunakan disk storage, mengatur status draft/published.

### B. Modul Artikel / Berita
* **Field Tabel:** `id`, `judul`, `slug`, `penulis`, `konten_html`, `thumbnail_url`, `views_count`, `created_at`.
* **Fungsi Admin:** Editor teks kaya (Rich Text Editor) di frontend CMS untuk memformat paragraf berita, manajemen SEO meta (opsional).

### C. Modul Titik Temu (Uang Logam)
* **Field Tabel:** `id`, `nama_pengaju`, `kontak_wa`, `jenis_kebutuhan` (Mencari / Menawarkan), `nominal`, `rincian_pecahan`, `titik_lokasi`, `status` (Tersedia, Proses, Selesai).
* **Fungsi Admin:** Admin memiliki otoritas penuh untuk memantau transaksi, menghapus data spam, atau mengubah status pencocokan uang logam secara manual jika diperlukan.

### D. Modul Jadwal Kalender BI
* **Field Tabel:** `id`, `nama_kegiatan`, `deskripsi`, `lokasi`, `tipe_kegiatan` (BI Mengajar, School Visit, dll), `tanggal_mulai`, `tanggal_selesai`.
* **Fungsi Admin:** CRUD jadwal. Data dari API ini akan di-render oleh Next.js menjadi tampilan kalender interaktif atau widget Agenda di Landing Page.

### E. Modul Pengajuan Kunjungan
* **Field Tabel:** `id`, `nama_instansi`, `nama_pic`, `kontak_pic`, `email_pic`, `jenis_pengajuan` (Kunjungan ke BI / BI ke Sekolah), `jumlah_peserta`, `tanggal_pengajuan`, `surat_permohonan_url`, `status` (Pending, Disetujui, Ditolak).
* **Fungsi Admin:** Menerima notifikasi data baru, mengubah status pengajuan, dan mengunduh (download) lampiran surat permohonan.
