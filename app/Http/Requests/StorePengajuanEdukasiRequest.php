<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanEdukasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_instansi' => 'required|string|max:100',
            'nama_instansi' => 'required|string|max:255',
            'alamat_instansi' => 'required|string',
            'nama_pic' => 'required|string|max:255',
            'jabatan_pic' => 'required|string|max:255',
            'email_pic' => 'required|email|max:255',
            'no_telp_pic' => 'required|string|max:20',
            'tema_kegiatan' => 'nullable|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'jumlah_peserta' => 'nullable|integer|min:1',
            'tanggal_kegiatan' => 'nullable|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'dokumen_proposal' => 'required|file|mimes:pdf|max:10240', // PDF max 10MB
        ];
    }
}
