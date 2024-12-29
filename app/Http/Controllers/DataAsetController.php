<?php

namespace App\Http\Controllers;

use App\Models\DataAset;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
