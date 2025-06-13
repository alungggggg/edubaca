<?php

namespace App\Http\Controllers;

use App\Models\MateriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MateriController extends Controller
{
    
    //
    public function get(Request $request)
    {
        try {
            if ($request->id) {
                $materi = MateriModel::find($request->id);
                if (!$materi) {
                    return response()->json([
                        'message' => 'Materi not found',
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $materi
                ], 200);
            }

            $materi = MateriModel::get();
            return response()->json([
                'success' => true,
                'data' => $materi
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
                'judul' => 'required|string|max:255',
                'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'pdf' => 'required|file|mimes:pdf|max:2048',
            ]);

            $cover = $request->file('cover');
            $pdf = $request->file('pdf');
            $destinationCoverPath = 'materi/cover/';
            $destinationPdfPath = 'materi/pdf/';
            $coverName = date('YmdHis') . "_cover." . $cover->getClientOriginalExtension();
            $pdfName = date('YmdHis') . "_pdf." . $pdf->getClientOriginalExtension();
            $cover->move($destinationCoverPath, $coverName);
            $pdf->move($destinationPdfPath, $pdfName);

            $materi = MateriModel::create([
                'judul' => $request->judul,
                'cover' => $coverName,
                'pdf' => $pdfName,
            ]);

            return response()->json([
                'success' => true,
                'data' => $materi
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
                'judul' => 'nullable|string|max:255',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'pdf' => 'nullable|file|mimes:pdf|max:2048',
            ]);
            $materi = MateriModel::findOrFail($id);

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $destinationPath = 'materi/cover/';
                $coverName = date('YmdHis') . "_cover." . $cover->getClientOriginalExtension();
                $cover->move($destinationPath, $coverName);
                File::delete($destinationPath . $materi->cover);
                $materi->cover = $coverName;
            }

            if ($request->hasFile('pdf')) {
                $pdf = $request->file('pdf');
                $destinationPath = 'materi/pdf/';
                $pdfName = date('YmdHis') . "_pdf." . $pdf->getClientOriginalExtension();
                $pdf->move($destinationPath, $pdfName);
                File::delete($destinationPath . $materi->pdf);
                $materi->pdf = $pdfName;
            }

            $materi->judul = $request->judul ?? $materi->judul;
            $materi->save();

            return response()->json([
                'success' => true,
                'data' => $materi
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
            $materi = MateriModel::find($id);
            $pdf = $materi->pdf;
            $cover = $materi->cover;

            if (File::exists("materi/cover/" . $cover)) {
                File::delete("materi/cover/" . $cover);
            }
            if (File::exists("materi/pdf/" . $pdf)) {
                File::delete("materi/pdf/" . $pdf);
            }
            
            if (!$materi) {
                return response()->json([
                    'message' => 'Materi not found',
                ], 404);
            }

            $materi->delete();
            return response()->json([
                'success' => true,
                'message' => 'Materi deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
