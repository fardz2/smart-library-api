<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = User::get();
        return response()->json(["status" => 200, "data" => $user], 200);
    }
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json(["status" => 404, "message" => "user tidak ditemukan"], 404);
        }
        return response()->json(["status" => 200, "data" => $user], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // // Buat pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ]);

        return response()->json(['message' => 'berhasil registrasi']);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json(["status" => 404, "message" => "user tidak ditemukan"], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ]);
        return response()->json(['message' => 'berhasil update user']);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json(["status" => 404, "message" => "user tidak ditemukan"], 404);
        }
        $user->delete();
        return response()->json(['message' => 'berhasil update user']);
    }
}
