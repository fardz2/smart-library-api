<?php

namespace App\Http\Controllers\Buku;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search != null) {
            // Menampilkan data pencarian yang tidak di-soft delete
            $buku = Buku::where('judul_buku', 'like', "%$search%")
                ->whereNull('deleted_at')
                ->paginate(10)
                ->withQueryString();

            if ($buku->isEmpty()) {
                return response()->json(["status" => 404, "message" => "Buku tidak ditemukan"], 200);
            }
        } else {
            $buku = Buku::whereNull('deleted_at')->paginate(10);
        }

        return response()->json(["status" => 200, "data" => $buku], 200);
    }
    public function recommended()
    {

        $buku = Buku::all()->take(5);

        return response()->json(["status" => 200, "data" => $buku], 200);
    }
    // public function getAll(Request $request)
    // {
    //     $search = $request->input('search');
    //     if ($search != null) {
    //         $buku = Buku::where('judul_buku', 'like', "%$search%")->get();
    //     } else {
    //         $buku = Buku::get();
    //     }

    //     return response()->json(["status" => 200, "data" => $buku], 200);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_buku' => ['required'],
            'penerbit' => ['required'],
            'pengarang' => ['required'],
            'sinopsis' => ['required'],
            'tahun_terbit' => ['required'],
            'jumlah_buku' => ['required'],
            'lokasi_rak_buku' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $jumlah_buku = (int)$request->jumlah_buku;
        if ($request->hasFile('cover') && $request->hasFile('pdf_buku')) {
            $cover = $request->file('cover');
            $pdf_buku = $request->file('pdf_buku');
            $path_cover = $cover->store('cover');
            $path_pdf_buku = $pdf_buku->store('buku');
            Buku::create([
                'cover' => asset('storage/' . $path_cover),
                'judul_buku' => Str::title($request->judul_buku),
                'penerbit' => $request->penerbit,
                'pengarang' => $request->pengarang,
                'sinopsis' => $request->sinopsis,
                'tahun_terbit' => $request->tahun_terbit,
                'jumlah_buku' =>  $jumlah_buku,
                'lokasi_rak_buku' => $request->lokasi_rak_buku,
                'pdf_buku' => asset('storage/' . $path_pdf_buku)
            ]);
        } else {
            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $path_cover = $cover->store('cover');
                Buku::create([
                    'cover' => asset('storage/' . $path_cover),
                    'judul_buku' => Str::title($request->judul_buku),
                    'penerbit' => $request->penerbit,
                    'pengarang' => $request->pengarang,
                    'sinopsis' => $request->sinopsis,
                    'tahun_terbit' => $request->tahun_terbit,
                    'jumlah_buku' =>  $jumlah_buku,
                    'lokasi_rak_buku' => $request->lokasi_rak_buku,
                ]);
            } else if ($request->hasFile('pdf_buku')) {
                $pdf_buku = $request->file('pdf_buku');
                $path_pdf_buku = $pdf_buku->store('buku');
                Buku::create([
                    'judul_buku' => Str::title($request->judul_buku),
                    'penerbit' => $request->penerbit,
                    'pengarang' => $request->pengarang,
                    'sinopsis' => $request->sinopsis,
                    'tahun_terbit' => $request->tahun_terbit,
                    'jumlah_buku' =>  $jumlah_buku,
                    'lokasi_rak_buku' => $request->lokasi_rak_buku,
                    'pdf_buku' => asset('storage/' . $path_pdf_buku)
                ]);
            } else {
                Buku::create([
                    'judul_buku' => Str::title($request->judul_buku),
                    'penerbit' => $request->penerbit,
                    'pengarang' => $request->pengarang,
                    'sinopsis' => $request->sinopsis,
                    'tahun_terbit' => $request->tahun_terbit,
                    'jumlah_buku' =>  $jumlah_buku,
                    'lokasi_rak_buku' => $request->lokasi_rak_buku,
                ]);
            }
        }
        return response()->json(["status" => 200, "message" => "buku berhasil ditambahkan"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::find($id);
        if ($buku == null) {
            return response()->json(["status" => 404, "message" => "buku tidak ditemukan"]);
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
            'judul_buku' => ['required'],
            'penerbit' => ['required'],
            'pengarang' => ['required'],
            'sinopsis' => ['required'],
            'tahun_terbit' => ['required'],
            'jumlah_buku' => ['required'],
            'lokasi_rak_buku' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $cover = Str::after($buku->cover, 'storage/');
        $pdf = Str::after($buku->pdf_buku, 'storage/');

        $dataBuku = [
            'judul_buku' => $request->judul_buku,
            'penerbit' => $request->penerbit,
            'pengarang' => $request->pengarang,
            'sinopsis' => $request->sinopsis,
            'tahun_terbit' => $request->tahun_terbit,
            'jumlah_buku' => $request->jumlah_buku,
            'lokasi_rak_buku' => $request->lokasi_rak_buku,
        ];
        if ($request->hasFile('cover') && $request->hasFile('pdf_buku')) {
            Storage::delete([$cover, $pdf]);
            $cover = $request->file('cover');
            $pdf_buku = $request->file('pdf_buku');
            $path_cover = $cover->store('cover');
            $path_pdf_buku = $pdf_buku->store('buku');
            $dataBuku['cover'] = asset('storage/' . $path_cover);
            $dataBuku['pdf_buku'] = asset('storage/' . $path_pdf_buku);
        } else if ($request->hasFile('cover')) {
            Storage::delete([$cover]);
            $cover = $request->file('cover');
            $path_cover = $cover->store('cover');
            $dataBuku['cover'] = asset('storage/' . $path_cover);
        } else if ($request->hasFile('pdf_buku')) {
            Storage::delete([$pdf]);
            $pdf_buku = $request->file('pdf_buku');
            $path_pdf_buku = $pdf_buku->store('buku');
            $dataBuku['pdf_buku'] = asset('storage/' . $path_pdf_buku);
        }

        $buku->update($dataBuku);
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

        // $cover = Str::after($buku->cover, 'storage/');
        // $pdf = Str::after($buku->pdf_buku, 'storage/');

        // if ($cover && $pdf) {
        //     Storage::delete([$cover, $pdf]);
        // }
        $buku->delete();
        return response()->json(["status" => 200, "message" => "buku berhasil dihapus"], 200);
    }
}
