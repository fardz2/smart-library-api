<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $role = Role::get();
            return response()->json(["status" => 200, "data" => $role], 200);
        } catch (\Exception $e) {
            // Tangani pengecualian di sini

            return response()->json(['error' => $e], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        Role::create([
            'role' => $request->role,

        ]);

        return response()->json(['status' => 200, 'message' => 'role berhasil ditambah'], 200);
    }

    public function destroy(string $id)
    {
        $role = Role::find($id);
        if ($role == null) {
            return response()->json(["status" => 404, "message" => "role tidak ditemukan"], 404);
        }
        $role->delete();
        return response()->json(["status" => 200, "message" => "role berhasil dihapus"], 200);
    }
}
