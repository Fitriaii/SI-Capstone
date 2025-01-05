<?php

namespace App\Http\Controllers;

use App\Models\dataKeluarga;
use App\Models\DataLayanan;
use Illuminate\Http\Request;

class DataLayananController extends Controller
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
            $query = DataLayanan::with(['nik']);

            // Filter berdasarkan search jika ada
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('NomorKK', 'like', '%' . $search . '%')
                        ->orWhere('NamaKepalaKeluarga', 'like', '%' . $search . '%')
                        ->orWhere('JenisAksesInternet', 'like', '%' . $search . '%')
                        ->orWhere('KepemilikanRekeningEWallet',  'like', '%' . $search . '%');
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
            $layanan = $query->paginate(10);

            // Mengirimkan data ke view dengan menggunakan compact
            return view('dataLayanan.index', compact('layanan',    'search', 'padukuhan'));
        } catch (\Exception $e) {
            // Menangani error dan mengirimkan pesan error ke view
            return redirect()->back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengambil data layanan: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $noKK = dataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();
        return view('dataLayanan.create',[
            'noKK' => $noKK->toJson()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NomorKK' => 'required|numeric|digits:16',
            'NamaKepalaKeluarga' => 'required|string|max:100',
            'JenisAksesInternet' => 'required|string|in:Tidak Menggunakan,Internet dan TV Digital berlangganan,Wifi,Internet Handphone',
            'KepemilikanRekeningEWallet' => 'required|string',
        ]);

        // Simpan data ke database
        DataLayanan::create($validatedData);

        // Redirect atau respon setelah menyimpan
        return redirect()->route('layanan.index')->with('success', 'Data aset berhasil disimpan.');
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
    public function edit(DataLayanan $dataLayanan)
    {
        $noKK = DataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();

        // Ambil data lama untuk ditampilkan di form (jika ada)
        $previousNomorKK = $dataLayanan->NomorKK ?? null; // Mengambil data Nomor KK terkait
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null; // Mengambil Nama Kepala Keluarga berdasarkan Nomor KK

        // Kirim data ke view
        return view('dataLayanan.edit', [
            'dataLayanan' => $dataLayanan,
            'noKK' => $noKK->toJson(), // Kirim data Nomor KK ke view dalam bentuk JSON
            'previousNomorKK' => $previousNomorKK, // Data lama Nomor KK
            'previousNamaKepalaKeluarga' => $previousNamaKepalaKeluarga, // Data lama Nama Kepala Keluarga
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataLayanan $dataLayanan)
    {
        $validatedData = $request->validate([
            'NomorKK' => 'required|numeric|digits:16',
            'NamaKepalaKeluarga' => 'required|string|max:100',
            'JenisAksesInternet' => 'required|string|in:Tidak Menggunakan,Internet dan TV Digital berlangganan,Wifi,Internet Handphone',
            'KepemilikanRekeningEWallet' => 'required|string',
        ]);

        // Simpan data ke database
        $dataLayanan->update($validatedData);

        // Redirect atau respon setelah menyimpan
        return redirect()->route('layanan.index', compact('dataLayanan')
        )->with('success', 'Data aset berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataLayanan $dataLayanan)
    {
        $dataLayanan->delete();

        // Redirect atau respon setelah menghapus
        return redirect()->route('layanan.index')->with('success', 'Data aset berhasil dihapus.');
    }
}
