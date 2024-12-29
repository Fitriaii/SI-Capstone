<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBangunan extends Model
{
    protected $table = "databangunan";

    protected $fillable =[
        "NomorKK",
        "NamaKepalaKeluarga",
        "StatusKepemilikanBangunan",
        "BuktiKepemilikan",
        "LuasLantai",
        "JenisLantai",
        "JenisDindingTerluas",
        "JenisAtapTerluas",
        "SumberAirMinum",
        "JarakSumberAirMinum",
        "SumberPeneranganUtama",
        "Meteran1",
        "Meteran2",
        "Meteran3",
        "BahanBakarEnergiMemasak",
        "KepemilikanBAB",
        "JenisKloset",
        "TempatPembuanganAkhirTinja",
    ];

    public function nik()
    {
        return $this->belongsTo(dataKeluarga::class, 'NomorKK', 'NomorKK')->withDefault();
    }
}
