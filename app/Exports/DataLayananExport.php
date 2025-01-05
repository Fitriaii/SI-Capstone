<?php

namespace App\Exports;

use App\Models\DataLayanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataLayananExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataLayanan::select(
            'NomorKK',
            'NamaKepalaKeluarga',
            'JenisAksesInternet',
            'KepemilikanRekeningEWallet'
            )->get(
        );
    }

    public function headings(): array
    {
        return [
            'NomorKK',
            'Nama Kepala Keluarga',
            'Jenis Akses Internet',
            'Kepemilikan Rekening dan EWallet'
        ];
    }
}
