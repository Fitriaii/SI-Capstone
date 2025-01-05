<?php

namespace App\Http\Controllers;

use App\Models\DataAset;
use App\Models\dataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataAsetController extends Controller
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
            $query = DataAset::with(['nik']);

            // Filter berdasarkan search jika ada
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('NomorKK', 'like', '%' . $search . '%')
                        ->orWhere('NamaKepalaKeluarga', 'like', '%' . $search . '%')
                        ->orWhere('TabungGas', 'like', '%' . $search . '%')
                        ->orWhere('LemariEs', 'like', '%' . $search . '%')
                        ->orWhere('AC', 'like', '%' . $search . '%')
                        ->orWhere('PemanasAir', 'like', '%' . $search . '%')
                        ->orWhere('TeleponRumah', 'like', '%' . $search . '%')
                        ->orWhere('TelevisiLayarDatar', 'like', '%' . $search . '%')
                        ->orWhere('EmasPerhiasan', 'like', '%' . $search . '%')
                        ->orWhere('KomputerLaptopTablet', 'like', '%' . $search . '%')
                        ->orWhere('SepedaMotor', 'like', '%' . $search . '%')
                        ->orWhere('Sepeda', 'like', '%' . $search . '%')
                        ->orWhere('Mobil', 'like', '%' . $search . '%')
                        ->orWhere('Perahu', 'like', '%' . $search . '%')
                        ->orWhere('PerahuMotor', 'like', '%' . $search . '%')
                        ->orWhere('Smartphone', 'like', '%' . $search . '%')
                        ->orWhere('LahanLain', 'like', '%' . $search . '%')
                        ->orWhere('RumahLain', 'like', '%' . $search . '%');
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
            $aset = $query->paginate(10);

            // Mengirimkan data ke view dengan menggunakan compact
            return view('dataAset.index', compact('aset',   'search', 'padukuhan'));
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
        return view('dataAset.create',[
            'noKK' => $noKK->toJson()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validatedData = $request->validate([
            'NomorKK' => 'required|numeric|digits:16',
            'NamaKepalaKeluarga' => 'required|string|max:100',
            'TabungGas' => 'nullable|string|in:Ya,Tidak',
            'LemariEs' => 'nullable|string|in:Ya,Tidak',
            'AC' => 'nullable|string|in:Ya,Tidak',
            'PemanasAir' => 'nullable|string|in:Ya,Tidak',
            'TeleponRumah' => 'nullable|string|in:Ya,Tidak',
            'TelevisiLayarDatar' => 'nullable|string|in:Ya,Tidak',
            'EmasPerhiasan' => 'nullable|string|in:Ya,Tidak',
            'KomputerLaptopTablet' => 'nullable|string|in:Ya,Tidak',
            'SepedaMotor' => 'nullable|string|in:Ya,Tidak',
            'Sepeda' => 'nullable|string|in:Ya,Tidak',
            'Mobil' => 'nullable|string|in:Ya,Tidak',
            'Perahu' => 'nullable|string|in:Ya,Tidak',
            'PerahuMotor' => 'nullable|string|in:Ya,Tidak',
            'Smartphone' => 'nullable|string|in:Ya,Tidak',
            'LahanLain' => 'nullable|string|in:Ya,Tidak',
            'RumahLain' => 'nullable|string|in:Ya,Tidak',
            'Sapi' => 'nullable|integer|min:0',
            'Kerbau' => 'nullable|integer|min:0',
            'Kuda' => 'nullable|integer|min:0',
            'Babi' => 'nullable|integer|min:0',
            'Kambing' => 'nullable|integer|min:0',
        ]);

        // Simpan data ke database
        DataAset::create($validatedData);

        // Redirect atau respon setelah menyimpan
        return redirect()->route('aset.index')->with('success', 'Data aset berhasil disimpan.');
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
    public function edit(DataAset $dataAset)
    {
        // Ambil daftar Nomor KK dan Nama Kepala Keluarga
        $noKK = DataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();

        // Ambil data lama untuk ditampilkan di form (jika ada)
        $previousNomorKK = $dataAset->NomorKK ?? null; // Mengambil data Nomor KK terkait
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null; // Mengambil Nama Kepala Keluarga berdasarkan Nomor KK

        // Kirim data ke view
        return view('dataAset.edit', [
            'dataAset' => $dataAset,
            'noKK' => $noKK->toJson(), // Kirim data Nomor KK ke view dalam bentuk JSON
            'previousNomorKK' => $previousNomorKK, // Data lama Nomor KK
            'previousNamaKepalaKeluarga' => $previousNamaKepalaKeluarga, // Data lama Nama Kepala Keluarga
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataAset $dataAset)
    {
        $validatedData = $request->validate([
            'NomorKK' => 'required|numeric|digits:16',
            'NamaKepalaKeluarga' => 'required|string|max:100',
            'TabungGas' => 'nullable|string|in:Ya,Tidak',
            'LemariEs' => 'nullable|string|in:Ya,Tidak',
            'AC' => 'nullable|string|in:Ya,Tidak',
            'PemanasAir' => 'nullable|string|in:Ya,Tidak',
            'TeleponRumah' => 'nullable|string|in:Ya,Tidak',
            'TelevisiLayarDatar' => 'nullable|string|in:Ya,Tidak',
            'EmasPerhiasan' => 'nullable|string|in:Ya,Tidak',
            'KomputerLaptopTablet' => 'nullable|string|in:Ya,Tidak',
            'SepedaMotor' => 'nullable|string|in:Ya,Tidak',
            'Sepeda' => 'nullable|string|in:Ya,Tidak',
            'Mobil' => 'nullable|string|in:Ya,Tidak',
            'Perahu' => 'nullable|string|in:Ya,Tidak',
            'PerahuMotor' => 'nullable|string|in:Ya,Tidak',
            'Smartphone' => 'nullable|string|in:Ya,Tidak',
            'LahanLain' => 'nullable|string|in:Ya,Tidak',
            'RumahLain' => 'nullable|string|in:Ya,Tidak',
            'Sapi' => 'nullable|integer|min:0',
            'Kerbau' => 'nullable|integer|min:0',
            'Kuda' => 'nullable|integer|min:0',
            'Babi' => 'nullable|integer|min:0',
            'Kambing' => 'nullable|integer|min:0',
        ]);

        // Update data aset
        $dataAset->update($validatedData);

        // Redirect atau respon setelah mengupdate
        return redirect()->route('aset.index', compact('dataAset')
        )->with('success', 'Data aset berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataAset $dataAset)
    {
        $dataAset->delete();

        // Redirect atau respon setelah menghapus
        return redirect()->route('aset.index')->with('success', 'Data aset berhasil dihapus.');
    }
    
}
