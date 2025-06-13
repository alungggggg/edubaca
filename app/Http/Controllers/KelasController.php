<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KelasModel;
use App\Models\SekolahModel;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    //
    public function index(Request $request)
    {
        try{
            if($request->sekolah && $request->kelas){
                $kelas = User::where('sekolah', $request->sekolah)->where('kelas', $request->kelas)->get();
                if ($kelas->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No classes found for the specified school and class.'
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $kelas
                ], 200);
            }


            if($request->sekolah){
                $kelas = SekolahModel::with('kelas.siswa.presensi')->find($request->sekolah);
                if (!$kelas) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No classes found for the specified school.'
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $kelas
                ], 200);
            }

            
            $kelas = SekolahModel::with('kelas.siswa.presensi')->get();

            return response()->json([
                'success' => true,
                'data' => $kelas
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching classes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'kelas' => 'required|string|max:255',
                'sekolah_id' => 'required|exists:sekolah,id',
            ]);
            $kelas = KelasModel::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $kelas
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the class: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kelas' => 'required|string|max:255',
                'sekolah_id' => 'integer|exists:sekolah,id',
            ]);
            $kelas = KelasModel::find($id);

            if($kelas){
                $kelas->kelas = $request->kelas ?? $kelas->kelas;
                $kelas->sekolah_id = $request->sekolah_id ?? $kelas->sekolah_id;
                $kelas->save();

                return response()->json([
                    'success' => true,
                    'data' => $kelas
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas Tidak Ditemukan!'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the class: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $kelas = KelasModel::find($id);
            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas Tidak Ditemukan!'
                ], 404);
            }
            $kelas->delete();
            return response()->json([
                'success' => true,
                'message' => 'Kelas Berhasil Dihapus!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the class: ' . $e->getMessage()
            ], 500);
        }
    }
}
