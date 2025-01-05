<?php

namespace App\Http\Controllers;

use App\Models\dataKeluarga;
use App\Models\DataProgram;
use Illuminate\Http\Request;

class DataProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $search = request('search');
            $padukuhan = request('padukuhan');

            // Inisialisasi query dasar dengan relasi 'nik' dan 'program'
            $query = DataProgram::with(['nik']);

            // Filter berdasarkan search jika ada
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('NomorKK', 'like', '%' . $search . '%')
                        ->orWhere('NamaKepalaKeluarga', 'like', '%' . $search . '%')
                        ->orWhere('ProgramBantuanSembako', 'like', '%' . $search . '%')
                        ->orWhere('ProgramPKH', 'like', '%' . $search . '%')
                        ->orWhere('ProgramBLT', 'like', '%' . $search . '%')
                        ->orWhere('ProgramSubsidiListrik', 'like', '%' . $search . '%')
                        ->orWhere('ProgramBantuanPemda', 'like', '%' . $search . '%')
                        ->orWhere('ProgramSubsidiPupuk', 'like', '%' . $search . '%')
                        ->orWhere('ProgramSubsidiLPG', 'like', '%' . $search . '%');
                });
            }



            // Filter berdasarkan padukuhan jika ada
            if (!empty($padukuhan)) {
                // Pastikan bahwa 'keluarga' relasi sudah dimuat
                $query->whereHas('nik', callback: function ($q) use ($padukuhan) {
                    $q->where('Padukuhan', $padukuhan);
                });
            }
            // Ambil data dengan paginasi
            $program = $query->paginate(10);

            // Mengirimkan data ke view dengan menggunakan compact
            return view('dataProgram.index', compact('program',   'search', 'padukuhan'));
        } catch (\Exception $e) {
            // Menangani error dan mengirimkan pesan error ke view
            return redirect()->back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengambil data kependudukan: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $noKK = dataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();
        return view('dataProgram.create',[
            'noKK' => $noKK->toJson()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NomorKK' => 'required|digits:16',
            'NamaKepalaKeluarga' => 'required|string|max:100',
            'ProgramBantuanSembako' => 'required|in:Ya,Tidak',
            'PeriodeSembako' => 'nullable', // Pastikan format bulan-tahun valid
            'ProgramPKH' => 'required|in:Ya,Tidak',
            'PeriodePKH' => 'nullable',
            'ProgramBLT' => 'required|in:Ya,Tidak',
            'PeriodeBLT' => 'nullable',
            'ProgramSubsidiListrik' => 'required|in:Ya,Tidak',
            'PeriodeSubsidiListrik' => 'nullable',
            'ProgramBantuanPemda' => 'required|in:Ya,Tidak',
            'PeriodeBantuanPemda' => 'nullable',
            'ProgramSubsidiPupuk' => 'required|in:Ya,Tidak',
            'PeriodeSubsidiPupuk' => 'nullable',
            'ProgramSubsidiLPG' => 'required|in:Ya,Tidak',
            'PeriodeSubsidiLPG' => 'nullable',
        ]);

        // Simpan data ke database
        DataProgram::create($validatedData);

        // Redirect atau respon setelah menyimpan
        return redirect()->route('program.index')->with('success', 'Data program berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataProgram $dataProgram)
    {
        $noKK = DataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();

        // Ambil data lama untuk ditampilkan di form (jika ada)
        $previousNomorKK = $dataProgram->NomorKK ?? null; // Mengambil data Nomor KK terkait
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null; // Mengambil Nama Kepala Keluarga berdasarkan Nomor KK

        // Kirim data ke view
        return view('dataProgram.edit', [
            'dataProgram' => $dataProgram,
            'noKK' => $noKK->toJson(), // Kirim data Nomor KK ke view dalam bentuk JSON
            'previousNomorKK' => $previousNomorKK, // Data lama Nomor KK
            'previousNamaKepalaKeluarga' => $previousNamaKepalaKeluarga, // Data lama Nama Kepala Keluarga
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataProgram $dataProgram)
    {
        $validatedData = $request->validate([
            'NomorKK' => 'required|digits:16',
            'NamaKepalaKeluarga' => 'required|string|max:100',
            'ProgramBantuanSembako' => 'required|in:Ya,Tidak',
            'PeriodeSembako' => 'nullable', // Pastikan format bulan-tahun valid
            'ProgramPKH' => 'required|in:Ya,Tidak',
            'PeriodePKH' => 'nullable',
            'ProgramBLT' => 'required|in:Ya,Tidak',
            'PeriodeBLT' => 'nullable',
            'ProgramSubsidiListrik' => 'required|in:Ya,Tidak',
            'PeriodeSubsidiListrik' => 'nullable',
            'ProgramBantuanPemda' => 'required|in:Ya,Tidak',
            'PeriodeBantuanPemda' => 'nullable',
            'ProgramSubsidiPupuk' => 'required|in:Ya,Tidak',
            'PeriodeSubsidiPupuk' => 'nullable',
            'ProgramSubsidiLPG' => 'required|in:Ya,Tidak',
            'PeriodeSubsidiLPG' => 'nullable',
        ]);

        // Simpan data ke database
        $dataProgram->update($validatedData);

        // Redirect atau respon setelah menyimpan
        return redirect()->route('program.index')->with('success', 'Data program berhasil diubah.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataProgram $dataProgram)
    {
        $dataProgram->delete();

        // Redirect atau respon setelah menghapus
        return redirect()->route('program.index')->with('success', 'Data program berhasil dihapus.');
    }
}
