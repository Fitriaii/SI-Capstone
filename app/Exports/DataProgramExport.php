<?php

namespace App\Exports;

use App\Models\DataProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataProgramExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataProgram::select(
            'NomorKK',
            'NamaKepalaKeluarga',
            'ProgramBantuanSembako',
            'PeriodeSembako',
            'ProgramPKH',
            'PeriodePKH',
            'ProgramBLT',
            'PeriodeBLT',
            'ProgramSubsidiListrik',
            'PeriodeSubsidiListrik',
            'ProgramBantuanPemda',
            'PeriodeBantuanPemda',
            'ProgramSubsidiPupuk',
            'PeriodeSubsidiPupuk',
            'ProgramSubsidiLPG',
            'PeriodeSubsidiLPG'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nomor KK',
            'Nama Kepala Keluarga',
            'Program Bantuan Sembako',
            'Periode Sembako',
            'Program PKH',
            'Periode PKH',
            'Program BLT',
            'Periode BLT',
            'Program Subsidi Listrik',
            'Periode Subsidi Listrik',
            'Program Bantuan Pemda',
            'Periode Bantuan Pemda',
            'Program Subsidi Pupuk',
            'Periode Subsidi Pupuk',
            'Program Subsidi LPG',
            'Periode Subsidi LPG'
        ];
    }
}
