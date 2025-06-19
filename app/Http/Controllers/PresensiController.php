<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use Illuminate\Http\Request;
use App\Models\PresensiModel;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{
    //
    public function index(Request $request)
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

            if($request->id_user && $request->id_kelas){
                $presensi = PresensiModel::where('user_id', $request->id_user)->get();

                return response()->json([
                    'success' => true,
                    'data' => $presensi,
                ], 200);
            }
            if ($request->kelas && $request->tanggal) {
                $presensi = KelasModel::with(['siswa.presensi' => function ($query) use ($request) {
                    $query->where('tanggal', $request->tanggal);
                }])->find($request->kelas);

                if ($presensi) {
                    $presensi->siswa = $presensi->siswa->map(function ($siswa) use ($request) {
                        // Assign ulang presensi dengan filter yang ketat
                        $filteredPresensi = $siswa->presensi->filter(function ($item) use ($request) {
                            return $item->tanggal === $request->tanggal;
                        })->values(); // reset index ke 0,1,2

                        $siswa->setRelation('presensi', $filteredPresensi); // ini penting
                        return $siswa;
                    });
                }

                return response()->json([
                    'success' => true,
                    'data' => $presensi
                ], 200);
            }


            if ($request->id_kelas) {
                $presensi = KelasModel::with(['siswa.presensi'])->find($request->id_kelas);
                if (!$presensi) {
                    return response()->json([
                        'message' => 'Kelas not found',
                    ], 404);
                }

                return response()->json([
                    'success' => true,
                    'data' => $presensi
                ], 200);
            }

            

            $presensi = KelasModel::with(['siswa.presensi'])->get();
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

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            if (!is_array($data)) {
                return response()->json([
                    'message' => 'Format data tidak valid. Harus berupa array.',
                ], 400);
            }

            foreach ($data as $item) {
                $validated = Validator::make($item, [
                    'user_id' => 'required|integer|exists:users,id',
                    'kelas_id' => 'required|integer|exists:kelas,id',
                    'status' => 'required|string|in:HADIR,ALPA,IZIN,SAKIT',
                    'tanggal' => 'required|date',
                ])->validate();

                PresensiModel::updateOrCreate(
                    [
                        'user_id' => $validated['user_id'],
                        'kelas_id' => $validated['kelas_id'],
                        'tanggal' => $validated['tanggal'],
                    ],
                    [
                        'status' => $validated['status'],
                    ]
                );
            }

            return response()->json(['message' => 'Presensi berhasil disimpan'], 201);
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
    public function destroy($id)
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
