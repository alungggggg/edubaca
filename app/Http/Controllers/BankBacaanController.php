<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankBacaanModel;
use Illuminate\Support\Facades\File;

class BankBacaanController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->id) {
                $bankbacaan = BankBacaanModel::find($request->id);
                if (!$bankbacaan) {
                    return response()->json([
                        'message' => 'Materi not found',
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $bankbacaan
                ], 200);
            }

            $bankbacaan = BankBacaanModel::get();
            return response()->json([
                'success' => true,
                'data' => $bankbacaan
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
            $request->validate([
                'judul' => 'required|string|max:255',
                'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'pdf' => 'required|file|mimes:pdf|max:2048',
            ]);

            $cover = $request->file('cover');
            $pdf = $request->file('pdf');
            $destinationCoverPath = 'bank-bacaan/cover/';
            $destinationPdfPath = 'bank-bacaan/pdf/';
            $coverName = date('YmdHis') . "_cover." . $cover->getClientOriginalExtension();
            $pdfName = date('YmdHis') . "_pdf." . $pdf->getClientOriginalExtension();
            $cover->move($destinationCoverPath, $coverName);
            $pdf->move($destinationPdfPath, $pdfName);

            $bankbacaan = BankBacaanModel::create([
                'judul' => $request->judul,
                'cover' => $coverName,
                'pdf' => $pdfName,
            ]);

            return response()->json([
                'success' => true,
                'data' => $bankbacaan
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
            $bankbacaan = BankBacaanModel::findOrFail($id);

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $destinationPath = 'bank-bacaan/cover/';
                $coverName = date('YmdHis') . "_cover." . $cover->getClientOriginalExtension();
                $cover->move($destinationPath, $coverName);
                File::delete($destinationPath . $bankbacaan->cover);
                $bankbacaan->cover = $coverName;
            }

            if ($request->hasFile('pdf')) {
                $pdf = $request->file('pdf');
                $destinationPath = 'bank-bacaan/pdf/';
                $pdfName = date('YmdHis') . "_pdf." . $pdf->getClientOriginalExtension();
                $pdf->move($destinationPath, $pdfName);
                File::delete($destinationPath . $bankbacaan->pdf);
                $bankbacaan->pdf = $pdfName;
            }

            $bankbacaan->judul = $request->judul ?? $bankbacaan->judul;
            $bankbacaan->save();

            return response()->json([
                'success' => true,
                'data' => $bankbacaan
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
            $bankbacaan = BankBacaanModel::find($id);
            $pdf = $bankbacaan->pdf;
            $cover = $bankbacaan->cover;

            if (File::exists("bank-bacaan/cover/" . $cover)) {
                File::delete("bank-bacaan/cover/" . $cover);
            }
            if (File::exists("bank-bacaan/pdf/" . $pdf)) {
                File::delete("bank-bacaan/pdf/" . $pdf);
            }
            
            if (!$bankbacaan) {
                return response()->json([
                    'message' => 'Bank bacaan not found',
                ], 404);
            }

            $bankbacaan->delete();
            return response()->json([
                'success' => true,
                'message' => 'bank-bacaan deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
