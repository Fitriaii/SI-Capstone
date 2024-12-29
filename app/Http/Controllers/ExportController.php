<?php

namespace App\Http\Controllers;

use App\Exports\DataKependudukanExport;
use App\Exports\DataKondisiRumahExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportDataKependudukan(Request $request)
    {
        $fileName = 'data_keluarga_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new DataKependudukanExport, $fileName);
    }

    public function exportDataKondisiRumah(Request $request)
    {
        $fileName = 'data_bangunan_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new DataKondisiRumahExport, $fileName);
    }
}
