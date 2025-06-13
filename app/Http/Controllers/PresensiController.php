<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use Illuminate\Http\Request;
use App\Models\PresensiModel;

class PresensiController extends Controller
{
    //
    public function get(Request $request)
    {
        try {
            if ($request->id) {
                $presensi = PresensiModel::find($request->id);
                if (!$presensi) {
                    return response()->json([
                        'message' => 'Presensi not found',
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $presensi
                ], 200);
            }
            if ($request->kelas && $request->tanggal) {
                $presensi = KelasModel::with(['siswa.presensi' => function ($query) use ($request) {
                    $query->where('tanggal', $request->tanggal);
                }])->find($request->kelas);

                return response()->json([
                    'success' => true,
                    'data' => $presensi
                ], 200);
            }

            $presensi = PresensiModel::all();
            return response()->json([
                'success' => true,
                'data' => $presensi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'kelas_id' => 'required|integer|exists:kelas,id',
                'status' => 'required|string|in:HADIR,ALPA,IZIN,SAKIT',
                'tanggal' => 'required|date',
            ]);

            $presensi = PresensiModel::create([
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
                'status' => $request->status,
                'tanggal' => $request->tanggal,
            ]);

            return response()->json([
                'success' => true,
                'data' => $presensi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $presensi = PresensiModel::find($id);
            if (!$presensi) {
                return response()->json([
                    'message' => 'Presensi not found',
                ], 404);
            }

            $request->validate([
                'status' => 'required|string|in:HADIR,ALPA,IZIN,SAKIT',
                'tanggal' => 'required|date',
            ]);

            $presensi->update([
                'status' => $request->status,
                'tanggal' => $request->tanggal,
            ]);

            return response()->json([
                'success' => true,
                'data' => $presensi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function delete($id)
    {
        try {
            $presensi = PresensiModel::find($id);
            if (!$presensi) {
                return response()->json([
                    'message' => 'Presensi not found',
                ], 404);
            }

            $presensi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Presensi deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
