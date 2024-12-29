<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAset extends Model
{
    protected $table = "dataaset";

    protected $fillable = [
        'NomorKK',
        'NamaKepalaKeluarga',
        'TabungGas',
        'LemariEs',
        'AC',
        'PemanasAir',
        'TeleponRumah',
        'TelevisiLayarDatar',
        'EmasPerhiasan',
        'KomputerLaptopTablet',
        'SepedaMotor',
        'Sepeda',
        'Mobil',
        'Perahu',
        'PerahuMotor',
        'Smartphone',
        'LahanLain',
        'RumahLain',
        'Sapi',
        'Kerbau',
        'Kuda',
        'Babi',
        'Kambing'
    ];

    public function nik()
    {
        return $this->belongsTo(dataKeluarga::class, 'NomorKK', 'NomorKK')->withDefault();
    }
}
