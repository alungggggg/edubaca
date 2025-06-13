<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerangkatMateriModel;
use Illuminate\Support\Facades\File;

class PerangkatMateriController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            if($request->id) {
                $perangkatMateri = PerangkatMateriModel::find($request->id);
                if (!$perangkatMateri) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Perangkat materi not found.'
                    ], 404);
                }

                return response()->json([
                    'success' => true,
                    'data' => $perangkatMateri
                ], 200);
            }
            $perangkatMateri = PerangkatMateriModel::get();
            return response()->json([
                'success' => true,
                'data' => $perangkatMateri
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching perangkat materi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'file' => 'required|file|mimes:pdf,docx|max:2048',
            ]);

            $cover = $request->file('cover');
            $file = $request->file('file');
            $destinationCoverPath = 'perangkat-materi/cover/';
            $destinationFilePath = 'perangkat-materi/file/';
            $coverName = date('YmdHis') . "_cover." . $cover->getClientOriginalExtension();
            $fileName = date('YmdHis') . "_file." . $file->getClientOriginalExtension();
            $cover->move($destinationCoverPath, $coverName);
            $file->move($destinationFilePath, $fileName);

            $materi = PerangkatMateriModel::create([
                'judul' => $request->judul,
                'cover' => $coverName,
                'file' => $fileName,
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
                'file' => 'nullable|file|mimes:pdf,docx|max:2048',
            ]);
            $materi = PerangkatMateriModel::findOrFail($id);

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $destinationPath = 'perangkat-materi/cover/';
                $coverName = date('YmdHis') . "_cover." . $cover->getClientOriginalExtension();
                $cover->move($destinationPath, $coverName);
                File::delete($destinationPath . $materi->cover);
                $materi->cover = $coverName;
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $destinationPath = 'perangkat-materi/file/';
                $fileName = date('YmdHis') . "_file." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                File::delete($destinationPath . $materi->file);
                $materi->file = $fileName;
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
            $materi = PerangkatMateriModel::find($id);
            $file = $materi->file;
            $cover = $materi->cover;

            if (File::exists("perangkat-materi/cover/" . $cover)) {
                File::delete("perangkat-materi/cover/" . $cover);
            }
            if (File::exists("perangkat-materi/file/" . $file)) {
                File::delete("perangkat-materi/file/" . $file);
            }
            if (!$materi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat materi not found.'
                ], 404);
            }
            $materi->delete();
            return response()->json([
                'success' => true,
                'message' => 'Perangkat materi deleted successfully'
            ], 200);
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
