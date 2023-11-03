<?php

namespace App\Http\Controllers\Buku;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Buku;

use function Laravel\Prompts\search;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        if ($search != null) {
            $buku = Buku::where('judul_buku', 'like', "%$search%")->get();
        } else {
            $buku = Buku::get();
        }

        return response()->json(["status" => 200, "data" => $buku], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover' => ['required'],
            'judul_buku' => ['required'],
            'penerbit' => ['required'],
            'pengarang' => ['required'],
            'sinopsis' => ['required'],
            'tahun_terbit' => ['required'],
            'jumlah_buku' => ['required'],
            'lokasi_rak_buku' => ['required'],
            'pdf_buku' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // // Buat pengguna baru
        if ($request->hasFile('cover') && $request->hasFile('pdf_buku')) {
            $cover = $request->file('cover');
            $pdf_buku = $request->file('pdf_buku');
            $path_cover = $cover->store('cover');
            $path_pdf_buku = $pdf_buku->store('buku');
        }

        Buku::create([
            'cover' => asset('storage/' . $path_cover),
            'user_id' => $request->user()->id,
            'judul_buku' => $request->judul_buku,
            'penerbit' => $request->penerbit,
            'pengarang' => $request->pengarang,
            'sinopsis' => $request->sinopsis,
            'tahun_terbit' => $request->tahun_terbit,
            'jumlah_buku' => $request->jumlah_buku,
            'lokasi_rak_buku' => $request->lokasi_rak_buku,
            'pdf_buku' => asset('storage/' . $path_pdf_buku)
        ]);
        return response()->json(["status" => 200, "message" => "buku berhasil ditambahkan"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::find($id);
        if ($buku == null) {
            return response()->json(["status" => 404, "message" => "buku tidak ditemukan"], 404);
        }
        return response()->json(["status" => 200, "data" => $buku], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);
        if ($buku == null) {
            return response()->json(["status" => 404, "message" => "buku tidak ditemukan"], 404);
        }
        $validator = Validator::make($request->all(), [
            'cover' => ['required'],
            'judul_buku' => ['required'],
            'penerbit' => ['required'],
            'pengarang' => ['required'],
            'sinopsis' => ['required'],
            'tahun_terbit' => ['required'],
            'jumlah_buku' => ['required'],
            'lokasi_rak_buku' => ['required'],
            'pdf_buku' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // // Buat pengguna baru
        if ($request->hasFile('cover') && $request->hasFile('pdf_buku')) {
            $cover = $request->file('cover');
            $pdf_buku = $request->file('pdf_buku');
            $path_cover = $cover->store('cover');
            $path_pdf_buku = $pdf_buku->store('buku');
        }


        $buku->update([
            'cover' => asset('storage/' . $path_cover),
            'user_id' => $request->user()->id,
            'judul_buku' => $request->judul_buku,
            'penerbit' => $request->penerbit,
            'pengarang' => $request->pengarang,
            'sinopsis' => $request->sinopsis,
            'tahun_terbit' => $request->tahun_terbit,
            'jumlah_buku' => $request->jumlah_buku,
            'lokasi_rak_buku' => $request->lokasi_rak_buku,
            'pdf_buku' => asset('storage/' . $path_pdf_buku)
        ]);
        return response()->json(["status" => 200, "message" => "buku berhasil diedit"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);
        if ($buku == null) {
            return response()->json(["status" => 404, "message" => "buku tidak ditemukan"], 404);
        }
        $buku->delete();
        return response()->json(["status" => 200, "message" => "buku berhasil dihapus"], 200);
    }
}
