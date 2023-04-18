<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RiwayatResource;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(request $request){
        $transaksi = Transaksi::where('id_user', $request->id_user)->get();
        return RiwayatResource::collection($transaksi);
        
    }
}