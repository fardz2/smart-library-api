<?php

namespace App\Http\Controllers\Peminjaman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;


class PeminjamanController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role->role == "admin" || $user->role->role == "pustakawan") {
            $peminjaman = Peminjaman::get();
        } else {
            $peminjaman = $user->peminjaman;
            if ($peminjaman == null) {
                return response()->json([
                    "status" => 404,
                    "message" => "peminjaman tidak ada"
                ], 404);
            }
        }
        return response()->json([
            "peminjaman" => $peminjaman
        ]);
    }
    public function store(Request $request)
    {
        $peminjaman = Peminjaman::create([
            'user_id' => $request->user()->id,
            'tanggal_peminjaman' => date('Y-m-d'),
            'tanggal_pengembalian' => $request->tanggal_pengembalian
        ]);
        $peminjaman->peminjamanBuku()->attach($request->buku);
        return response()->json([
            "status" => 200,
            "message" => "peminjaman berhasil"
        ], 200);
    }
    public function show(string $id)
    {
        $peminjaman = Peminjaman::find($id);
        $peminjaman->peminjamanBuku;
        return response()->json([
            "status", 200,
            "data" => $peminjaman
        ], 200);
    }
}
