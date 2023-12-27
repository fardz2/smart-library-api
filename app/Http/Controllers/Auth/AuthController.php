<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BukuYangDipinjam;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // // Buat pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 3
        ]);

        return response()->json(['message' => 'berhasil registrasi']);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',],
        ], [
            'password.regex' => 'Password harus memiliki setidaknya satu huruf kapital, satu angka, dan satu simbol.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Jika email tidak terdaftar, berikan respons yang sesuai
            return response()->json(['status' => 404, 'message' => 'Email tidak terdaftar'], 404);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $user->role;

            return response()->json(['data' => $user, 'token' => $token]);
        } else {
            return response()->json(['status' => 401, 'message' => 'Password salah'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 200, 'message' => "berhasil logout"], 200);
    }

    public function getInfo(Request $request)
    {
        $user = $request->user();
        $buku = BukuYangDipinjam::where("user_id", $user->id);
        $buku_yang_telah_dipinjam = $buku->where("status", true)->count();
        $buku = BukuYangDipinjam::where("user_id", $user->id);
        $buku_yang_sedang_dipinjam = $buku->where("status", false)->count();
        $user_data = $user->toArray();
        $user_data['buku_yang_telah_dipinjam'] = $buku_yang_telah_dipinjam;
        $user_data['buku_yang_sedang_dipinjam'] = $buku_yang_sedang_dipinjam;
        return response()->json(['status' => 200, 'data' => $user_data], 200);
    }

    public function updateInfoUser(Request $request)
    {
        $user = $request->user();
        $user->update($request->all());

        return response()->json(["status" => 200, "message" => "Info user berhasil diupdate"]);
    }
}
