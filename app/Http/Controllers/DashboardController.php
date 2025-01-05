<?php

namespace App\Http\Controllers;

use App\Models\DataBangunan;
use App\Models\dataKeluarga;
use App\Models\DataProgram;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        //Data Jumlah KK
        $jumlahKartuKeluarga = DB::table('datakeluarga')->distinct('NomorKK')->count('NomorKK');

        //Data Jumlah Masyarakat
        $jumlahMasyarakat = DB::table('datakeluarga')->sum('JumlahAnggotaKeluarga');

        //Data Jumlah Penerima Bantuan Per KK
        $penerimaBantuanPerKK = DataProgram::select('NomorKK', DB::raw('count(*) as total'))
            ->groupBy('NomorKK')
            ->get();
        $totalPenerimaBantuan = $penerimaBantuanPerKK->sum('total');

        //Data Status Kepemilikan Rumah
        $statusRumah = DataBangunan::distinct()->pluck('StatusKepemilikanBangunan');
        $jumlahKeluargaPerStatus = DB::table('databangunan')
            ->select('StatusKepemilikanBangunan', DB::raw('count(*) as status_rumah'))
            ->groupBy('StatusKepemilikanBangunan')
            ->get();

        //Data Jumlah Keluarga per Padukuhan
        $allPadukuhan = DataKeluarga::distinct()->pluck('Padukuhan');
        $jumlahKeluargaPerPadukuhan = DB::table('datakeluarga')
            ->select('Padukuhan', DB::raw('count(*) as jumlah_keluarga'))
            ->groupBy('Padukuhan')
            ->get();

        //Data Program Bantuan
        $programs = [
            'ProgramBantuanSembako' => 'Program Bantuan Sosial Sembako/BPNT',
            'ProgramPKH' => 'Program Keluarga Harapan (PKH)',
            'ProgramBLT' => 'Program Bantuan Langsung Tunai (BLT) Desa',
            'ProgramSubsidiListrik' => 'Program Subsidi Listrik',
            'ProgramBantuanPemda' => 'Program Bantuan Pemerintah Daerah',
            'ProgramSubsidiPupuk' => 'Program Subsidi Pupuk',
            'ProgramSubsidiLPG' => 'Program Subsidi LPG',
        ];

        $jumlahPenerimaBantuan = [];
            foreach ($programs as $column => $name) {
                $jumlahPenerimaBantuan[$name] = DB::table('dataprogram')
                    ->where($column, 'Ya')
                    ->count();
            }

        return view('dashboard', compact(
            'jumlahKartuKeluarga',
            'jumlahMasyarakat',
            'allPadukuhan',
            'jumlahKeluargaPerPadukuhan',
            'jumlahPenerimaBantuan',
            'penerimaBantuanPerKK',
            'statusRumah',
            'jumlahKeluargaPerStatus',
            'totalPenerimaBantuan'
        ));
    }
}
