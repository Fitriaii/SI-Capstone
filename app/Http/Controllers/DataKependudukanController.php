<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use Illuminate\Http\Request;

class DataKependudukanController extends Controller
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
            $query = DataKeluarga::with(['user']);

            // Filter berdasarkan search jika ada
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('NomorKK', 'like', '%' . $search . '%')
                        ->orWhere('NamaKepalaKeluarga', 'like', '%' . $search . '%')
                        ->orWhere('Padukuhan', 'like', '%' . $search . '%')
                        ->orWhere('JumlahAnggotaKeluarga', 'like', '%' . $search . '%')
                        ->orWhere('Alamat', 'like', '%' . $search . '%');
                });
            }

            // Filter berdasarkan padukuhan jika ada
            if (!empty($padukuhan)) {
                $query->where('Padukuhan', $padukuhan);
            }

            // Ambil data dengan paginasi
            $keluarga = $query->paginate(10);

            // Mengirimkan data ke view dengan menggunakan compact
            return view('dataKependudukan.index', compact('keluarga', 'search', 'padukuhan'));
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
        return view('dataKependudukan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Provinsi' => 'required|string|max:255',
            'Kabupaten' => 'required|string|max:255',
            'Kecamatan' => 'required|string|max:255',
            'Kalurahan' => 'required|string|max:255',
            'Padukuhan' => 'required|string|max:255',
            'KodeSLS' => 'required|numeric',
            'KodeSubSLS' => 'required|numeric',
            'NamaSLSNonSLS' => 'required|string|max:255',
            'Alamat' => 'required|string|max:255',
            'NamaKepalaKeluarga' => 'required|string|max:255',
            'NomorUrutBangunanTempatTinggal' => 'required|integer',
            'NoUrutKeluargaHasilVerif' => 'required|integer',
            'StatusKeluarga' => 'required|integer',
            'JumlahAnggotaKeluarga' => 'required|numeric',
            'IdLandmarkWilkerStat' => 'required|max:255',
            'NomorKK' => 'required|numeric|digits:16',
            'KodeKartuKK' => 'required|string',
        ]);

        // Simpan data ke database
        DataKeluarga::create($validatedData);

        // Redirect dengan pesan sukses
        return redirect()->route('penduduk.index') // Ganti dengan route tujuan setelah penyimpanan
            ->with('success', 'Data keluarga berhasil ditambahkan.');
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
    public function edit(DataKeluarga $dataKeluarga)
    {
        // $dataKeluarga = DataKeluarga::find($dataKeluarga->ID);
        return view('dataKependudukan.edit', compact('dataKeluarga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataKeluarga $dataKeluarga)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'Provinsi' => 'required|string|max:255',
            'Kabupaten' => 'required|string|max:255',
            'Kecamatan' => 'required|string|max:255',
            'Kalurahan' => 'required|string|max:255',
            'Padukuhan' => 'required|string|max:255',
            'KodeSLS' => 'required|numeric',
            'KodeSubSLS' => 'required|numeric',
            'NamaSLSNonSLS' => 'required|string|max:255',
            'Alamat' => 'required|string|max:255',
            'NamaKepalaKeluarga' => 'required|string|max:255',
            'NomorUrutBangunanTempatTinggal' => 'required|integer',
            'NoUrutKeluargaHasilVerif' => 'required|integer',
            'StatusKeluarga' => 'required|integer',
            'JumlahAnggotaKeluarga' => 'required|numeric',
            'IdLandmarkWilkerStat' => 'required|max:255',
            'NomorKK' => 'required|numeric|digits:16',
            'KodeKartuKK' => 'required|string',
        ]);

        $dataKeluarga->update($validatedData);

        // Redirect dengan pesan sukses
        return redirect()->route('penduduk.index', compact('dataKeluarga')) // Ganti dengan route tujuan setelah pembaruan
            ->with('success', 'Data keluarga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataKeluarga $dataKeluarga)
    {
        $dataKeluarga->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('penduduk.index', compact('dataKeluarga')) // Ganti dengan route tujuan setelah penghapusan
            ->with('success', 'Data keluarga berhasil dihapus.');
    }



}
