<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

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
        $nohp = $request->telp;
        if(!preg_match("/[^+0-9]/",trim($nohp))){
            if(substr(trim($nohp), 0, 2)=="62"){
                $hp    =trim($nohp);
            }
            else if(substr(trim($nohp), 0, 1)=="0"){
                $hp    ="62".substr(trim($nohp), 1);
            }
        }

        if($request->jenis_zakat == 'beras'){
            $total_zakat = $request->total_zakat . ' Kg';
        }else{
            $total_zakat = 'Rp. ' . number_format($request->total_zakat,2);
        }

        $message_wa = "*Zakat Berhasil Diterima Lazisnu* \n\n*Nama*   : {$request->nama} \n*Alamat* : {$request->alamat}\n\n";
        $message_wa .= "*List Muzaki* \n";
        for ($i=1; $i <= $total_muzaki; $i++) { 
            $muzaki = 'muzaki' . $i;
            $muzakinya = $request[$muzaki];
            $message_wa .= "*Nama* : {$muzakinya} \n";
        }
        $message_wa .= "\nTotal Zakat = * {$total_zakat}*";
        
          
        $jsonData = [
            'jid' => $hp.'@s.whatsapp.net',
            'type' => 'number',
            'message' => ['text' => $message_wa]
        ]; 


        $response = Http::withBody(
                    json_encode($jsonData), 'application/json'
                )->post('http://188.166.204.127/colabs/messages/send');

        Log::debug($response);

        return response()->json([
            'status' => true,
            'message' => 'Zakat berhasil disimpan',
            'print_text' => $message_wa,
        ]);


    }
}