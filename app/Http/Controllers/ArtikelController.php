<?php

namespace App\Http\Controllers;

use App\Models\ArtikelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ArtikelController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            if ($request->id) {
                $artikel = ArtikelModel::with('soal')->find($request->id);
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

            $artikel = ArtikelModel::with('soal')->get();
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

    public function create(Request $request)
    {
        try {
            $gambar = $request->file('gambar');
            $destinationPath = 'artikel/';
            $profileImage = date('YmdHis') . "." . $gambar->getClientOriginalExtension();
            $gambar->move($destinationPath, $profileImage);

            $artikel = ArtikelModel::create([
                'artikel_link' => $request->artikel_link,
                'judul' => $request->judul,
                'image' => $profileImage,
                'type' => $request->type,
                'deskripsi' => $request->deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'data' => $artikel
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
            $data = ArtikelModel::find($id);
            if (!$data) {
                return response()->json([
                    'message' => 'Artikel not found',
                ], 404);
            }
            $data->artikel_link = $request->artikel_link;
            $data->judul = $request->judul;
            $data->deskripsi = $request->deskripsi;
            $data->type = $request->type;
            if ($request->file("gambar")) {
                if (File::exists("artikel/" . $data['image'])) {
                    File::delete("artikel/" . $data['image']);
                }

                $gambar = $request->file('gambar');
                $destinationPath = 'artikel/';
                $filename = date('YmdHis') . "." . $gambar->getClientOriginalExtension();
                $gambar->move($destinationPath, $filename);

                $data->image = $filename;
            }
            $data->save();

            return response()->json([
                'success' => true,
                'data' => $data
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
            $data = ArtikelModel::find($id);

            if (!$data) {
                return response()->json([
                    'message' => 'Artikel tidak ditemukan.',
                ], 404);
            }

            // Cek relasi soal
            if ($data->soal()->count() > 0) {
                return response()->json([
                    'message' => 'Artikel masih memiliki soal terkait. Hapus soal terlebih dahulu.',
                ], 400);
            }

            // Cek relasi nilai
            if ($data->nilai()->count() > 0) {
                return response()->json([
                    'message' => 'Artikel masih memiliki nilai terkait. Hapus nilai terlebih dahulu.',
                ], 400);
            }

            // Hapus file jika ada
            if ($data->image && File::exists("artikel/" . $data->image)) {
                File::delete("artikel/" . $data->image);
            }

            // Hapus artikel
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Artikel berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server.',
            ], 500);
        }
    }
}
