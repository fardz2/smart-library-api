<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', ],
            'password' => ['required', 'string', 'min:8'],
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
       
        // // Buat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id'=> 1
        ]);
      
        
   
        // Anda juga dapat menambahkan logika lain di sini, seperti mengirim email verifikasi.
    
        // Kirim respons JSON yang sesuai
        return response()->json(['message' => $user]);
        

    }   

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', ],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $user->role;
        
            return response()->json(['user'=>$user,'token'=>$token]);
        }
        
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>"berhasil logout"]);

    }

    public function getInfo(Request $request){
        $user = $request->user();
        $user->buku;
       return  $user;
    }
}
