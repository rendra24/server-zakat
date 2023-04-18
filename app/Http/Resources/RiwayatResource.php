<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RiwayatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
     
    public function toArray($request): array
    {
        if($this->jenis_zakat == 'beras'){
            $total = $this->total_zakat . ' Kg';
        }else{
            $total = number_format($this->total_zakat,2);
        }
        return [
                'id' => $this->id,
                'nama' => $this->nama,
                'total_zakat' => $total,
                'jenis_zakat' => $this->jenis_zakat,
                'created_at' =>  $this->created_at->format('d-m-Y H:i'),
        ];
    }
}