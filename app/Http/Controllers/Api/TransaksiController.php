<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $dataTransaksi = [
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'jenis_zakat' => $request->jenis_zakat,
            'total_zakat' => $request->total_zakat,
            'id_user' => $request->id_user,
        ];

        
        $transaksi = Transaksi::create($dataTransaksi);
        
        if($request->jenis_zakat == 'beras'){
            $total_muzaki = $request->total_zakat /  3;
            $item = 3;
        }else{
            $total_muzaki = $request->total_zakat /  38000;
            $item = 38000;
        }

        for ($i=1; $i <= $total_muzaki; $i++) { 
            $muzaki = 'muzaki' . $i;
            $muzakinya = $request[$muzaki];
            $detailTransaksi = [
                'id_transaksi' => $transaksi->id,
                'nama_muzaki' => $muzakinya,
                'total' => $item,
            ];
            TransaksiDetail::create($detailTransaksi);
        }

        return response()->json([
            'status' => true,
            'message' => 'Zakat berhasil disimpan',
        ]);


    }
}