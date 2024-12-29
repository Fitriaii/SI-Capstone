<?php

namespace App\Exports;

use App\Models\DataKeluarga;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataKependudukanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataKeluarga::select(
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
            'KodeKartuKK'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Kalurahan',
            'Padukuhan',
            'Kode SLS',
            'Kode Sub SLS',
            'Nama SLS/Non-SLS',
            'Alamat',
            'Nama Kepala Keluarga',
            'Nomor Urut Bangunan Tempat Tinggal',
            'Nomor Urut Keluarga Hasil Verifikasi',
            'Status Keluarga',
            'Jumlah Anggota Keluarga',
            'ID Landmark Wilayah Kerja Statistik',
            'Nomor KK',
            'Kode Kartu KK',
        ];
    }
}
