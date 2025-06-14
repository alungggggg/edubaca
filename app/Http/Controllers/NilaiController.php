<?php

namespace App\Http\Controllers;

use App\Models\NilaiModel;
use App\Models\ArtikelModel;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            // Ambil berdasarkan ID Nilai
            if ($request->id_nilai) {
                $nilai = NilaiModel::with(['artikel', 'user'])->find($request->id_nilai);
                if (!$nilai) {
                    return response()->json([
                        'message' => 'Nilai not found',
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $nilai
                ], 200);
            }

            // Ambil berdasarkan kombinasi id_artikel dan id_user
            if ($request->id_artikel && $request->id_user) {
                $nilai = NilaiModel::with(['artikel', 'user'])
                    ->where('id_artikel', $request->id_artikel)
                    ->where('id_user', $request->id_user)
                    ->first();

                if (!$nilai) {
                    return response()->json([
                        'message' => 'Nilai not found',
                    ], 404);
                }

                return response()->json([
                    'success' => true,
                    'data' => $nilai
                ], 200);
            }

            // Ambil berdasarkan ID Artikel
            if ($request->id_artikel) {
                $artikel = ArtikelModel::with('nilai.user')
                    ->where('id', $request->id_artikel)
                    ->first();

                if (!$artikel) {
                    return response()->json([
                        'message' => 'Artikel not found',
                    ], 404);
                }

                return response()->json([
                    'success' => true,
                    'data' => $artikel
                ], 200);
            }

            // Ambil semua nilai dengan artikel & user
            $artikel = ArtikelModel::with('nilai.user')->get();

            return response()->json([
                'success' => true,
                'data' => $artikel
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'id_user' => 'required|exists:users,id',
            'id_artikel' => 'required|exists:artikel,id',
        ]);

        try {
            $nilai = NilaiModel::create($validated);
            return response()->json([
                'success' => true,
                'data' => $nilai,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $nilai = NilaiModel::find($id);
            if (!$nilai) {
                return response()->json([
                    'message' => 'Nilai not found',
                ], 404);
            }
            $nilai->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $nilai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $nilai = NilaiModel::find($id);
            if (!$nilai) {
                return response()->json([
                    'message' => 'Nilai not found',
                ], 404);
            }
            $nilai->delete();
            return response()->json([
                'success' => true,
                'message' => 'Nilai deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
