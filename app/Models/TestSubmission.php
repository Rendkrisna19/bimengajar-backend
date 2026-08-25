<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSubmission extends Model
{
    use HasFactory;

    protected $table = 'test_submissions';

    protected $fillable = [
        'test_id',
        'nama_peserta',
        'instansi',
        'email',
        'tanggal_bi_mengajar',
        'skor_total',
        'skor_maksimal',
        'detail_jawaban',
        'waktu_selesai',
    ];

    protected $casts = [
        'detail_jawaban' => 'array',
        'waktu_selesai' => 'datetime',
    ];

    public function test()
    {
        return $this->belongsTo(PrePostTest::class, 'test_id');
    }
}
