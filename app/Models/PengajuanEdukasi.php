<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanEdukasi extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_pengajuan',
        'jenis_instansi',
        'nama_instansi',
        'alamat_instansi',
        'nama_pic',
        'jabatan_pic',
        'email_pic',
        'no_telp_pic',
        'tema_kegiatan',
        'tujuan_kegiatan',
        'deskripsi_kegiatan',
        'jumlah_peserta',
        'tanggal_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
        'kota_kabupaten',
        'lokasi_kegiatan',
        'dokumen_proposal',
        'status',
        'catatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
