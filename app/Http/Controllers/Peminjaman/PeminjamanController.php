<?php

namespace App\Http\Controllers\Peminjaman;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\BukuYangDipinjam;



class PeminjamanController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->input('search');

        if ($user->role->role == "admin" || $user->role->role == "pustakawan") {
            $peminjaman = Peminjaman::with('peminjamanBuku', 'user')
                ->orderByDesc('created_at');
            if ($search) {
                $peminjaman->where('peminjaman_id', 'like', "%$search%");
            }
            $peminjaman = $peminjaman->paginate(5)->withQueryString();
            foreach ($peminjaman as $peminjam) {
                // Check if any buku_yang_dipinjam related to this peminjaman has status = true
                if ($peminjam->status == false) {
                    $statusBukuDipinjam = $peminjam->peminjamanBuku->contains(function ($buku) {
                        return $buku->pivot->status == true;
                    });
                    if ($statusBukuDipinjam) {
                        $peminjam->update(['status' => true]);
                    }
                    $peminjam->update(['denda' => $this->hitungDenda($peminjam->tanggal_pengembalian, $peminjam->status, $peminjam->denda)]);
                }
            }
            if ($peminjaman->isEmpty()) {
                return response()->json([
                    "status" => 404,
                    "message" => "Peminjaman tidak ditemukan"
                ], 200);
            }
        } else {
            $peminjaman = $user->peminjaman()->with('peminjamanBuku')
                ->orderByDesc('created_at');
            if ($search) {
                $peminjaman->where('peminjaman_id', 'like', "%$search%");
            }
            $peminjaman = $peminjaman->paginate(5)->withQueryString();
            foreach ($peminjaman as $peminjam) {
                // Check if any buku_yang_dipinjam related to this peminjaman has status = true
                // Check if any buku_yang_dipinjam related to this peminjaman has status = true
                if ($peminjam->status == false) {
                    $statusBukuDipinjam = $peminjam->peminjamanBuku->contains(function ($buku) {
                        return $buku->pivot->status == true;
                    });
                    if ($statusBukuDipinjam) {
                        $peminjam->update(['status' => true]);
                    }
                    $peminjam->update(['denda' => $this->hitungDenda($peminjam->tanggal_pengembalian, $peminjam->status, $peminjam->denda)]);
                }
            }
            if ($peminjaman->isEmpty()) {
                return response()->json([
                    "status" => 404,
                    "message" => "Peminjaman tidak ditemukan"
                ], 200);
            }
        }

        return response()->json([
            "status" => 200,
            "data" => $peminjaman
        ]);
    }
    public function store(Request $request)
    {
        $userId = $request->user()->id;
        $bukuStatusCheck = BukuYangDipinjam::where('user_id', $userId)
            ->where('status', false)
            ->count();

        if ($bukuStatusCheck >= 3) {
            return response()->json([
                "status" => 400,
                "message" => "Anda harus mengembalikan setidaknya satu buku sebelum melakukan peminjaman baru.",
            ], 200);
        }
        $tanggalPengembalian = Carbon::now()->addWeek();

        $smt = 'SMT';

        $randomPart = Str::random(8); // Menghasilkan string acak dengan panjang 8 karakter

        $peminjaman_id = $smt . $userId . '-' . $randomPart;
        // Simpan peminjaman
        $peminjaman = Peminjaman::create([
            'peminjaman_id' => $peminjaman_id,
            'user_id' => $userId,
            'tanggal_peminjaman' => Carbon::now(),
            'tanggal_pengembalian' => $tanggalPengembalian,
        ]);

        $peminjaman->peminjamanBuku()->attach($request->buku, ['user_id' => $userId]);

        return response()->json([
            "status" => 200,
            "message" => "Peminjaman berhasil",
        ], 200);
    }
    public function show(string $id)
    {
        $peminjaman = Peminjaman::find($id);
        if ($peminjaman == null) {
            return response()->json([
                "status" => 404,
                "message" => "tidak ditemukan"
            ], 200);
        }
        $peminjaman->peminjamanBuku;
        return response()->json([
            "status" => 200,
            "data" => $peminjaman
        ], 200);
    }

    public function edit(string $id)
    {
        $buku_yang_dipinjam = BukuYangDipinjam::find($id);
        if ($buku_yang_dipinjam == null) {
            return response()->json([
                "status" => 404,
                "message" => "tidak ditemukan"
            ], 200);
        }
        $buku_yang_dipinjam->update(["status" => true]);
        return response()->json([
            "status" => 200,
            "message" => "Buku berhasil dikembalikan"
        ], 200);
    }
    private function hitungDenda($tanggalPengembalian, $status, $denda)
    {
        $tanggalPengembalian = Carbon::parse($tanggalPengembalian);
        $sekarang = Carbon::now();

        // Jika tanggal pengembalian sudah lewat, hitung denda
        if ($sekarang->gt($tanggalPengembalian) && !$status) {
            $selisihHari = $sekarang->diffInDays($tanggalPengembalian);
            $dendaPerHari = 2000;
            $totalDenda = $selisihHari * $dendaPerHari;
            return $totalDenda;
        }

        return $denda; // Tidak ada denda
    }
}
