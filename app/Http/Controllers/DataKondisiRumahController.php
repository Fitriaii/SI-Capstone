<?php

namespace App\Http\Controllers;

use App\Models\DataBangunan;
use App\Models\dataKeluarga;
use Illuminate\Http\Request;

class DataKondisiRumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $search = request('search');
            $padukuhan = request('padukuhan');

            // Inisialisasi query dasar dengan relasi 'user'
            $query = DataBangunan::with(['nik']);

            // Filter berdasarkan search jika ada
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('NomorKK', 'like', '%' . $search . '%')
                        ->orWhere('StatusKepemilikanBangunan', 'like', '%' . $search . '%')
                        ->orWhere('LuasLantai', 'like', '%' . $search . '%')
                        ->orWhere('SumberAirMinum', 'like', '%' . $search . '%')
                        ->orWhere('SumberPeneranganUtama', 'like', '%' . $search . '%');
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
            $bangunan = $query->paginate(10);

            // Mengirimkan data ke view dengan menggunakan compact
            return view('dataKondisiRumah.index', compact('bangunan',  'search', 'padukuhan'));
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
        $noKK = DataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();
        return view('dataKondisiRumah.create',[
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
            'StatusKepemilikanBangunan' => 'required',
            'BuktiKepemilikan' => 'nullable',
            'LuasLantai' => 'required|numeric',
            'JenisLantai' => 'required',
            'JenisDindingTerluas' => 'required',
            'JenisAtapTerluas' => 'required',
            'SumberAirMinum' => 'required',
            'JarakSumberAirMinum' => 'nullable',
            'SumberPeneranganUtama' => 'required',
            'Meteran1' => 'nullable',
            'Meteran2' => 'nullable',
            'Meteran3' => 'nullable',
            'BahanBakarEnergiMemasak' => 'required',
            'KepemilikanBAB' => 'required|string',
            'JenisKloset' => 'nullable',
            'TempatPembuanganAkhirTinja' => 'required',
        ]);

        // Simpan data ke dalam tabel 'databangunan'
        DataBangunan::create($validatedData);

        // Redirect dengan pesan sukses
        return redirect()->route('bangunan.index') // Ganti dengan route tujuan setelah penyimpanan
            ->with('success', 'Data bangunan berhasil ditambahkan.');
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
    public function edit(DataBangunan $dataBangunan)
    {
        // Ambil daftar Nomor KK dan Nama Kepala Keluarga
        $noKK = DataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();

        // Ambil data lama untuk ditampilkan di form (jika ada)
        $previousNomorKK = $dataBangunan->NomorKK ?? null; // Mengambil data Nomor KK terkait
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null; // Mengambil Nama Kepala Keluarga berdasarkan Nomor KK

        // Kirim data ke view
        return view('dataKondisiRumah.edit', [
            'dataBangunan' => $dataBangunan,
            'noKK' => $noKK->toJson(), // Kirim data Nomor KK ke view dalam bentuk JSON
            'previousNomorKK' => $previousNomorKK, // Data lama Nomor KK
            'previousNamaKepalaKeluarga' => $previousNamaKepalaKeluarga, // Data lama Nama Kepala Keluarga
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataBangunan $dataBangunan)
    {
        $validatedData = $request->validate([
            'NomorKK' => 'required|numeric|digits:16',
            'NamaKepalaKeluarga' => 'required|string|max:100',
            'StatusKepemilikanBangunan' => 'required',
            'BuktiKepemilikan' => 'nullable',
            'LuasLantai' => 'required|numeric',
            'JenisLantai' => 'required',
            'JenisDindingTerluas' => 'required',
            'JenisAtapTerluas' => 'required',
            'SumberAirMinum' => 'required',
            'JarakSumberAirMinum' => 'nullable',
            'SumberPeneranganUtama' => 'required',
            'Meteran1' => 'nullable',
            'Meteran2' => 'nullable',
            'Meteran3' => 'nullable',
            'BahanBakarEnergiMemasak' => 'required',
            'KepemilikanBAB' => 'required|string',
            'JenisKloset' => 'nullable',
            'TempatPembuanganAkhirTinja' => 'required',
        ]);

        // Update data ke dalam tabel 'databangunan'
        $dataBangunan->update($validatedData);

        return redirect()->route('bangunan.index', compact('dataBangunan')) // Ganti dengan route tujuan setelah pembaruan
            ->with('success', 'Data bangunan berhasil diperbarui.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataBangunan $dataBangunan)
    {
        $dataBangunan->delete();

        return redirect()->route('bangunan.index',compact('dataBangunan'))
            ->with('success', 'Data bangunan berhasil dihapus.');
    }
}
