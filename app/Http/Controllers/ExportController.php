<?php

namespace App\Http\Controllers;

use App\Exports\DataAsetExport;
use App\Exports\DataKependudukanExport;
use App\Exports\DataKondisiRumahExport;
use App\Exports\DataLayananExport;
use App\Exports\DataProgramExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportDataKependudukan(Request $request)
    {
        $fileName = 'data_keluarga_' . date('Ymd') . '.xlsx';

        return Excel::download(new DataKependudukanExport, $fileName);
    }

    public function exportDataKondisiRumah(Request $request)
    {
        $fileName = 'data_bangunan_' . date('Ymd') . '.xlsx';

        return Excel::download(new DataKondisiRumahExport, $fileName);
    }
    public function exportDataProgram(Request $request)
    {
        $fileName = 'data_program_' . date('Ymd') . '.xlsx';

        return Excel::download(new DataProgramExport, $fileName);
    }
    public function exportDataAset(Request $request)
    {
        $fileName = 'data_Aset_' . date('Ymd') . '.xlsx';

        return Excel::download(new DataAsetExport, $fileName);
    }
    public function exportDataLayanan(Request $request)
    {
        $fileName = 'data_layanan_' . date('Ymd') . '.xlsx';

        return Excel::download(new DataLayananExport, $fileName);
    }
}
