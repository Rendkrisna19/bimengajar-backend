# PRD - Peta Edukasi (Integrasi Data Sekolah)

## Tujuan

Menampilkan lokasi institusi pendidikan pada fitur **Peta Edukasi** menggunakan data resmi Kemendikdasmen yang disinkronkan ke database aplikasi. Untuk tahap pertama, cakupan wilayah dibatasi hanya **Pulau Sumatera**.

---

# Arsitektur

```text
                    Data Resmi Kemendikdasmen
                              │
                              │
                    (Import / Sync Service)
                              │
                              ▼
                     Laravel Backend
              (Validation, Mapping, Sync)
                              │
                              ▼
                 edukasi_locations Table
                              │
                     REST API Laravel
                              │
                              ▼
                 Next.js (Leaflet / Maps)
```

---

# Data Flow

### 1. Sinkronisasi Data

Backend bertugas mengambil data sekolah dari sumber resmi Kemendikdasmen.

Proses:

1. Request data sekolah berdasarkan provinsi di Pulau Sumatera.
2. Mapping data ke struktur `edukasi_locations`.
3. Melakukan `updateOrCreate()` agar data tidak duplikat.
4. Menyimpan hasil ke database lokal.

Frontend **tidak pernah** mengakses API Kemendikdasmen secara langsung.

---

### 2. Pengambilan Data

Frontend hanya mengambil data dari Laravel.

```http
GET /api/edukasi-locations
```

Response:

```json
[
  {
    "id": 1,
    "name": "SMA Negeri 1 Medan",
    "category": "SMA/SMK",
    "latitude": 3.595,
    "longitude": 98.672,
    "address": "Jl. ..."
  }
]
```

---

# Scope Wilayah

Tahap pertama hanya mengimpor sekolah dari:

* Aceh
* Sumatera Utara
* Sumatera Barat
* Riau
* Kepulauan Riau
* Jambi
* Bengkulu
* Sumatera Selatan
* Lampung
* Bangka Belitung

Data dari provinsi di luar Pulau Sumatera tidak diimpor ke database.

---

# Mapping Data

| Kemendikdasmen                | edukasi_locations |
| ----------------------------- | ----------------- |
| Nama Sekolah                  | name              |
| Jenjang                       | category          |
| Latitude                      | latitude          |
| Longitude                     | longitude         |
| Alamat                        | address           |
| Tahun Berdiri (jika tersedia) | year              |
| Informasi Tambahan            | description       |

---

# Sinkronisasi

Sinkronisasi dijalankan oleh Backend melalui:

* Artisan Command
* Scheduler (Cron Job)
* Manual Trigger dari Admin Panel

Contoh proses:

```
Kemendikdasmen
      │
      ▼
Import Service
      │
      ▼
updateOrCreate()
      │
      ▼
edukasi_locations
```

---

# Frontend Responsibility

* Memanggil REST API Laravel.
* Menampilkan marker pada peta.
* Menampilkan popup/detail lokasi.
* Tidak melakukan request ke API Kemendikdasmen.

---

# Backend Responsibility

* Mengakses sumber data resmi.
* Melakukan validasi dan mapping data.
* Mencegah duplikasi menggunakan `updateOrCreate()`.
* Menyediakan REST API untuk frontend.
* Menjalankan proses sinkronisasi secara berkala.

---

# Future Enhancement

* Filter berdasarkan provinsi, kabupaten, dan kecamatan.
* Filter berdasarkan kategori (SD, SMP, SMA/SMK, Perguruan Tinggi, Komunitas).
* Pencarian berdasarkan nama sekolah.
* Sinkronisasi otomatis terjadwal.
* Ekspansi cakupan ke seluruh Indonesia tanpa mengubah arsitektur aplikasi.


# Batasan Filtering Data untuk backend laravel

* Gunakan Eloquent Query Builder
* Database Index
* Pagination
* Lazy Loading jika diperlukan
* Jangan filter menggunakan Collection (->get()->filter())
* Tidak perlu package pihak ketiga
