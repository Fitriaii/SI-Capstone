<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataLayanan extends Model
{
    protected $table = "ewallet_internet";

    protected $fillable = [
        'NomorKK',
        'NamaKepalaKeluarga',
        'JenisAksesInternet',
        'KepemilikanRekeningEWallet'
    ];

    public function nik()
    {
        return $this->belongsTo(dataKeluarga::class, 'NomorKK', 'NomorKK')->withDefault();
    }
}

