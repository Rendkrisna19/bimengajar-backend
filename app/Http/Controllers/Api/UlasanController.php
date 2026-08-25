<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UlasanController extends Controller
{
    /**
     * Safely ensure the ulasans table has status column.
     */
    private function ensureStatusColumnExists()
    {
        try {
            if (Schema::hasTable('ulasans') && !Schema::hasColumn('ulasans', 'status')) {
                Schema::table('ulasans', function (Blueprint $table) {
                    $table->string('status')->default('disetujui')->after('rating');
                });
            }
        } catch (\Exception $e) {
            // Ignore if schema modification is restricted
        }
    }

    public function index(Request $request)
    {
        $this->ensureStatusColumnExists();

        $query = Ulasan::query();

        // If request is from admin dashboard or asks for all records, return all items
        if ($request->boolean('all') || $request->boolean('admin') || $request->header('Authorization')) {
            $ulasan = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Public endpoint ONLY returns approved ('disetujui') ulasans
            if (Schema::hasColumn('ulasans', 'status')) {
                $ulasan = $query->where('status', 'disetujui')->orderBy('created_at', 'desc')->get();
            } else {
                $ulasan = $query->orderBy('created_at', 'desc')->get();
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $ulasan
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureStatusColumnExists();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'komentar' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            $validated['status'] = $request->input('status', 'disetujui');
            $ulasan = Ulasan::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Ulasan berhasil dikirim',
                'data' => $ulasan
            ], 201);
        } catch (\Exception $e) {
            // Fallback if status column is missing in DB
            unset($validated['status']);
            $ulasan = Ulasan::create($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Ulasan berhasil dikirim',
                'data' => $ulasan
            ], 201);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $this->ensureStatusColumnExists();

        $ulasan = Ulasan::findOrFail($id);
        $newStatus = $request->input('status');
        
        if (!$newStatus && $request->has('is_approved')) {
            $newStatus = $request->boolean('is_approved') ? 'disetujui' : 'pending';
        }

        $ulasan->status = $newStatus ?: 'disetujui';
        $ulasan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status ulasan berhasil diperbarui',
            'data' => $ulasan
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->ensureStatusColumnExists();

        $ulasan = Ulasan::findOrFail($id);
        $data = $request->all();
        if (isset($data['is_approved'])) {
            $data['status'] = $data['is_approved'] ? 'disetujui' : 'pending';
        }
        $ulasan->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan berhasil diperbarui',
            'data' => $ulasan
        ]);
    }

    public function destroy($id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan berhasil dihapus'
        ]);
    }
}
