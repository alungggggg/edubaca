<?php

namespace App\Http\Controllers;

use App\Models\SekolahModel;
use Illuminate\Http\Request;

class SekolahController extends Controller
{

    public function index(){
        try{
            $sekolah = SekolahModel::with("kelas")->paginate(15);
            return response()->json([
                'success' => true,
                'data' => $sekolah
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_sekolah' => 'required|string|max:255',
            ]);

            $sekolah = SekolahModel::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $sekolah
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
            $request->validate([
                'nama_sekolah' => 'required|string|max:255',
            ]);
            $sekolah = SekolahModel::findOrFail($id);
            if (!$sekolah) {
                return response()->json([
                    'message' => 'Sekolah not found',
                ], 404);
            }

            $sekolah->nama_sekolah = $request->nama_sekolah;
            $sekolah->save();

            return response()->json([
                'success' => true,
                'data' => $sekolah
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
            $sekolah = SekolahModel::findOrFail($id);
            $sekolah->delete();
            return response()->json([
                'success' => true,
                'message' => 'Sekolah deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
