<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(Request $request){

        $user = User::where('email', $request->email)->first();

        if($user){
            if (password_verify($request->password, $user->password)) {
                $dataUser = [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                    'telp' => $user->telp,
                ];

                return response()->json([
                    'status' => true,
                    'message' => 'Get data berhasil',
                    'data' => $dataUser
                ]);
            }
        }else{
            return Response::json([
                'status' => false,
            ], 401);
        }
        
    }

    public function doregis(Request $request){
        $dataUser = [
            'name' => $request->nama,
            'email' => $request->email,
            'telp' => $request->telp,
            'password' => Hash::make($request->password),
        ];

        User::create($dataUser);

        return response()->json([
            'status' => true,
            'message' => 'User berhasil didaftarkan'
        ]);
    }
}