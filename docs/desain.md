# Design System Guidelines & UI/UX Requirements
**Project Name:** BI Siantar Mengajar System

## 1. Typography
* **Font Family:** Wajib menggunakan font **Poppins** secara eksklusif untuk seluruh elemen teks (Heading maupun Body Text).

## 2. Color Palette
Sistem pewarnaan menggunakan pendekatan kontras yang profesional dan modern:
* **Primary:** Navy Blue (Biru dongker khas logo BI, misal: `#003366`).
* **Background/Base:** White (`#FFFFFF`) dan Soft Light Gray untuk latar belakang kanvas agar konten lebih menonjol.
* **Accent 1 (Warning/CTA):** Yellow & Orange (Digunakan untuk tombol aksi utama/CTA dan notifikasi).
* **Accent 2 (Highlight/Icons):** Purple (Untuk elemen visual, ornamen, atau highlight yang membutuhkan kontras modern).

## 3. Animation & Interactions
* **Animation Library (GSAP):** Gunakan GSAP untuk mengelola:
    * Transisi perpindahan halaman yang smooth.
    * Scroll trigger pada area Hero Section.
    * Kemunculan elemen seperti Bento Box/Card dengan efek *fade-up* & *stagger*.

## 4. Iconography
* **Icons:** Gunakan **Font Awesome** (kombinasikan versi Solid dan Regular sesuai konteks kebutuhan UI).

## 5. Alerts, Modals, & Notifications
* **Library:** Gunakan **SweetAlert2**.
* **Konfigurasi Penting:** SweetAlert2 **wajib di-custom secara global** di Next.js agar terintegrasi sempurna dengan tema utama aplikasi:
    * Sesuaikan `border-radius` agar serasi dengan elemen desain lain.
    * Sesuaikan warna tombol aksi (menggunakan warna *Navy* & *Yellow*).
    * Terapkan typography *Poppins*.
    * (Dilarang keras menggunakan gaya *default/bawaan* pabrik dari SweetAlert2).
