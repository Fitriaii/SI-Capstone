<?php

namespace App\Exports;

use App\Models\DataAset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataAsetExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataAset::select(
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
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nomor KK',
            'Nama Kepala Keluarga',
            'Tabung Gas',
            'Lemari Es',
            'AC',
            'Pemanas Air',
            'Telepon Rumah',
            'Televisi Layar Datar',
            'Emas Perhiasan',
            'Komputer Laptop Tablet',
            'Sepeda Motor',
            'Sepeda',
            'Mobil',
            'Perahu',
            'Perahu Motor',
            'Smartphone',
            'Lahan Lain',
            'Rumah Lain',
            'Sapi',
            'Kerbau',
            'Kuda',
            'Babi',
            'Kambing'
        ];
    }
}
