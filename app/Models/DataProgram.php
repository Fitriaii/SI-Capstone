<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataProgram extends Model
{
    protected $table = "dataprogram";

    protected $fillable = [
        "NomorKK",
        "NamaKepalaKeluarga",
        "periode"
    ];

    protected function casts(): array
    {
        return [
            'periode' => 'date',
        ];
    }

    public function nik()
    {
        return $this->belongsTo(dataKeluarga::class, 'NomorKK', 'NomorKK')->withDefault();
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id',  'id')->withDefault();
    }
}
