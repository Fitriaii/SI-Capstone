<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataProgram extends Model
{
    protected $table = "dataprogram";

    protected $fillable = [
        "NomorKK",
        "NamaKepalaKeluarga",
        "ProgramBantuanSembako",
        "PeriodeSembako",
        "ProgramPKH",
        "PeriodePKH",
        "ProgramBLT",
        "PeriodeBLT",
        "ProgramSubsidiListrik",
        "PeriodeSubsidiListrik",
        "ProgramBantuanPemda",
        "PeriodeBantuanPemda",
        "ProgramSubsidiPupuk",
        "PeriodeSubsidiPupuk",
        "ProgramSubsidiLPG",
        "PeriodeSubsidiLPG",
    ];


    protected function casts(): array
    {
        return [
            'PeriodeSembako' => 'date',
            'PeriodePKH' => 'date',
            'PeriodeBLT' => 'date',
            'PeriodeBantuanPemda' => 'date',
            'PeriodeSubsidiPupuk' => 'date',
            'PeriodeSubsidiLPG' => 'date',
            'PeriodeSubsidiListrik' => 'date',
        ];
    }

    public function nik()
    {
        return $this->belongsTo(dataKeluarga::class, 'NomorKK', 'NomorKK')->withDefault();
    }
}
