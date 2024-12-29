<?php

namespace App\Http\Controllers;

use App\Models\dataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $jumlahKartuKeluarga = DB::table('datakeluarga')->distinct('NomorKK')->count('NomorKK');
        $jumlahMasyarakat = DB::table('datakeluarga')->sum('JumlahAnggotaKeluarga');

        $allPadukuhan = DataKeluarga::distinct()->pluck('Padukuhan');  // Ambil padukuhan unik

        // Ambil jumlah keluarga berdasarkan padukuhan
        $jumlahKeluargaPerPadukuhan = DB::table('datakeluarga')
            ->select('Padukuhan', DB::raw('count(*) as jumlah_keluarga'))
            ->groupBy('Padukuhan')
            ->get();

        // Gabungkan data padukuhan dengan jumlah keluarga (default 0 jika tidak ada data)
        $data = $allPadukuhan->map(function($padukuhan) use ($jumlahKeluargaPerPadukuhan) {
            // Cari jumlah keluarga per padukuhan
            $jumlahKeluarga = $jumlahKeluargaPerPadukuhan->firstWhere('Padukuhan', $padukuhan);
            // Jika tidak ada data, set jumlah keluarga menjadi 0
            return [
                'Padukuhan' => $padukuhan,
                'jumlah_keluarga' => $jumlahKeluarga ? $jumlahKeluarga->jumlah_keluarga : 0
            ];
        });
        return view('dashboard', compact('jumlahKartuKeluarga', 'jumlahMasyarakat','data', 'allPadukuhan'));
    }
}
