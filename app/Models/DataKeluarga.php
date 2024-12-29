<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dataKeluarga extends Model
{
    protected $table = "datakeluarga";

    protected $fillable = [
        'Provinsi',
        'Kabupaten',
        'Kecamatan',
        'Kalurahan',
        'Padukuhan',
        'KodeSLS',
        'KodeSubSLS',
        'NamaSLSNonSLS',
        'Alamat',
        'NamaKepalaKeluarga',
        'NomorUrutBangunanTempatTinggal',
        'NoUrutKeluargaHasilVerif',
        'StatusKeluarga',
        'JumlahAnggotaKeluarga',
        'IdLandmarkWilkerStat',
        'NomorKK',
        'KodeKartuKK',
    ];

    protected $casts = [
        'id' => 'integer',
        'KodeSLS' => 'integer',
        'KodeSubSLS' => 'integer',
        'NomorUrutBangunanTempatTinggal' => 'integer',
        'NoUrutKeluargaHasilVerif' => 'integer',
        'StatusKeluarga' => 'integer',
        'JumlahAnggotaKeluarga' => 'integer',
        'NomorKK' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
}
