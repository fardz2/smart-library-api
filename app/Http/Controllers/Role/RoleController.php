<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
class RoleController extends Controller
{
    public function index(){
        $role = Role::get();
        return response()->json(["status"=> 200, "data"=> $role]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'string'],
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $user = Role::create([
            'role' => $request->role,
           
        ]);
      
        return response()->json(['status'=> 200,'message' => 'role berhasil ditambah']);
        
    }

    public function destroy(string $id){
        $role = Role::find($id);
        if($role == null){
            return response()->json(["status"=> 404, "message"=>"role tidak ditemukan"]);
        }
        $role_destroy = $role->delete();
        return response()->json(["status"=> 200, "message"=>"role berhasil dihapus"]);
    }
}
