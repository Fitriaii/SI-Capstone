<?php

namespace App\Exports;

use App\Models\DataBangunan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataKondisiRumahExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataBangunan::select(
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
        )->get();
    }

    public function headings(): array
    {
        return [
            "Nomor KK",
            "Nama Kepala Keluarga",
            "Status Kepemilikan Bangunan",
            "Bukti Kepemilikan",
            "Luas Lantai (m²)",
            "Jenis Lantai",
            "Jenis Dinding Terluas",
            "Jenis Atap Terluas",
            "Sumber Air Minum",
            "Jarak ke Sumber Air Minum (m)",
            "Sumber Penerangan Utama",
            "Meteran 1",
            "Meteran 2",
            "Meteran 3",
            "Bahan Bakar Energi Memasak",
            "Kepemilikan BAB",
            "Jenis Kloset",
            "Tempat Pembuangan Akhir Tinja"
        ];
    }
}
