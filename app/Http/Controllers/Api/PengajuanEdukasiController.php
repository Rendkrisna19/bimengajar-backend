<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengajuanEdukasiRequest;
use App\Models\PengajuanEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengajuanStatusUpdated;

class PengajuanEdukasiController extends Controller
{
    /**
     * Display a listing of the submissions.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // If admin, see all. If user, see only theirs.
        if ($user->role === 'admin') {
            $pengajuan = PengajuanEdukasi::with('user')->latest()->get();
        } else {
            $pengajuan = PengajuanEdukasi::where('user_id', $user->id)->latest()->get();
        }

        return response()->json([
            'message' => 'Berhasil mengambil data pengajuan',
            'data' => $pengajuan
        ]);
    }

    /**
     * Store a newly created submission in storage.
     */
    public function store(StorePengajuanEdukasiRequest $request)
    {
        $data = $request->validated();
        
        // Handle file upload
        if ($request->hasFile('dokumen_proposal')) {
            $file = $request->file('dokumen_proposal');
            // Simpan di storage/app/public/proposal
            $path = $file->store('proposal', 'public');
            $data['dokumen_proposal'] = $path;
        }
        
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'pending'; // Default status

        $pengajuan = PengajuanEdukasi::create($data);

        return response()->json([
            'message' => 'Pengajuan kegiatan edukasi berhasil dikirim.',
            'data' => $pengajuan
        ], 201);
    }

    /**
     * Display the specified submission.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $pengajuan = PengajuanEdukasi::with('user')->findOrFail($id);

        // Security check: only admin or the owner can view it
        if ($user->role !== 'admin' && $pengajuan->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Detail pengajuan',
            'data' => $pengajuan
        ]);
    }

    /**
     * Update status (Admin only).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verifikasi,penjadwalan,konfirmasi,ditolak,selesai',
            'catatan_admin' => 'nullable|string'
        ]);

        $pengajuan = PengajuanEdukasi::findOrFail($id);
        
        $pengajuan->status = $request->status;
        if ($request->has('catatan_admin')) {
            $pengajuan->catatan_admin = $request->catatan_admin;
        }

        $pengajuan->save();

        try {
            Mail::to($pengajuan->email_pic)->send(new PengajuanStatusUpdated($pengajuan));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to queue status update email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Status pengajuan berhasil diperbarui.',
            'data' => $pengajuan
        ]);
    }
}
