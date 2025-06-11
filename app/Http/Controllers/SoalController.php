<?php

namespace App\Http\Controllers;

use App\Models\SoalModel;
use App\Models\ArtikelModel;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    //
    public function getSoal(Request $request)
    {
        try {
            $soal = [];
            if($request->id_artikel){
                $soal = ArtikelModel::with('soal')
                    ->where('id', $request->id_artikel)
                    ->get();
                if ($soal->isEmpty()) {
                    return response()->json([
                        'message' => 'Soal not found for the given article ID',
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $soal
                ], 200);
            }
            if($request->id){
                $soal = SoalModel::find($request->id);
                if (!$soal) {
                    return response()->json([
                        'message' => 'Soal not found',
                    ], 404);
                }
                
            }
            $soal = SoalModel::with('artikel')->get();

            return response()->json([
                'success' => true,
                'data' => $soal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createSoal(Request $request)
    {
        try {
            $soal = SoalModel::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $soal
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function updateSoal(Request $request, $id)
    {
        try {
            $soal = SoalModel::find($id);
            if (!$soal) {
                return response()->json([
                    'message' => 'Soal not found',
                ], 404);
            }
            $soal->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $soal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function deleteSoal($id)
    {
        try {
            $soal = SoalModel::find($id);
            if (!$soal) {
                return response()->json([
                    'message' => 'Soal not found',
                ], 404);
            }
            $soal->delete();
            return response()->json([
                'success' => true,
                'message' => 'Soal deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
