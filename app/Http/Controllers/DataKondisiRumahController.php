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
            'StatusKepemilikanBangunan' => 'required|in:Milik sendiri,Kontrak,Bebas Sewa,Dinas,Lainnya',
            'BuktiKepemilikan' => 'nullable|in:SHM atas nama anggota keluarga,SHM bukan anggota keluarga dengan perjanjian pemanfaatan tertulis,SHM bukan a.n anggota keluarga tanpa perjanjian pemanfaatan tertulis,Sertifikat selain SHM(SHGB, SHSRS, Surat lainnya),Tidak punya',
            'LuasLantai' => 'required|numeric',
            'JenisLantai' => 'required|in:Marmer/granit,Keramik,Parket/karpet,Ubin,Kayu,Semen,Bamboo,Tanah,Lainnya',
            'JenisDindingTerluas' => 'required|in:Tembok,Plesteran anyaman bambu/kawat,Kayu/papan/gypsum/GRC,Anyaman bambu,Batang kayu,Bambu,Lainnya',
            'JenisAtapTerluas' => 'required|in:Beton,Genteng,Seng,Asbes,Bambu,Kayu,Jerami/ijuk/daun,Lainnya',
            'SumberAirMinum' => 'required|in:Air kemasan bermerk,Air isi ulang,Ledeng,Sumur bor/pompa,Sumur terlindung,Sumur tak terlindung,Mata air terlindung,Mata air tak terlindung,Air permukaan(sungai/danau/waduk),Air hujan,Lainnya',
            'JarakSumberAirMinum' => 'nullable|in:<10 Meter,>10 Meter,Tidak Tahu',
            'SumberPeneranganUtama' => 'required|in:PLN,Non PLN,Lainnya',
            'Meteran1' => 'nullable|in:450 watt,900 watt,1.300 watt,2.200 watt,>2.200 watt',
            'Meteran2' => 'nullable|in:450 watt,900 watt,1.300 watt,2.200 watt,>2.200 watt',
            'Meteran3' => 'nullable|in:450 watt,900 watt,1.300 watt,2.200 watt,>2.200 watt',
            'BahanBakarEnergiMemasak' => 'required|in:Listrik,Gas Elpiji 5 kg/blue gas,Gas Elpiji 12 kg,Gas Elpiji 3 kg,Gas Meteran GPN,Biogas,Minyak Tanah,Breket,Arang,Kayu Bakar,Lainnya,Tidak Memasak dirumah',
            'KepemilikanBAB' => 'required|string',
            'JenisKloset' => 'nullable|in:Leher Angsa,Plesengan dengan tutup,Plengsengan tanpa tutup,Cemplung/Bubluk',
            'TempatPembuanganAkhirTinja' => 'required|in:Septik tank,IPAL,Kolam/Sungai,Lubang tanah,Tanah Lapang/Kebun,Lainnya',
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
            'StatusKepemilikanBangunan' => 'required|in:Milik sendiri,Kontrak,Bebas Sewa,Dinas,Lainnya',
            'BuktiKepemilikan' => 'nullable|in:SHM atas nama anggota keluarga,SHM bukan anggota keluarga dengan perjanjian pemanfaatan tertulis,SHM bukan a.n anggota keluarga tanpa perjanjian pemanfaatan tertulis,Sertifikat selain SHM(SHGB, SHSRS, Surat lainnya),Tidak punya',
            'LuasLantai' => 'required|numeric',
            'JenisLantai' => 'required|in:Marmer/granit,Keramik,Parket/karpet,Ubin,Kayu,Semen,Bamboo,Tanah,Lainnya',
            'JenisDindingTerluas' => 'required|in:Tembok,Plesteran anyaman bambu/kawat,Kayu/papan/gypsum/GRC,Anyaman bambu,Batang kayu,Bambu,Lainnya',
            'JenisAtapTerluas' => 'required|in:Beton,Genteng,Seng,Asbes,Bambu,Kayu,Jerami/ijuk/daun,Lainnya',
            'SumberAirMinum' => 'required|in:Air kemasan bermerk,Air isi ulang,Ledeng,Sumur bor/pompa,Sumur terlindung,Sumur tak terlindung,Mata air terlindung,Mata air tak terlindung,Air permukaan(sungai/danau/waduk),Air hujan,Lainnya',
            'JarakSumberAirMinum' => 'nullable|in:<10 Meter,>10 Meter,Tidak Tahu',
            'SumberPeneranganUtama' => 'required|in:PLN,Non PLN,Lainnya',
            'Meteran1' => 'nullable|in:450 watt,900 watt,1.300 watt,2.200 watt,>2.200 watt',
            'Meteran2' => 'nullable|in:450 watt,900 watt,1.300 watt,2.200 watt,>2.200 watt',
            'Meteran3' => 'nullable|in:450 watt,900 watt,1.300 watt,2.200 watt,>2.200 watt',
            'BahanBakarEnergiMemasak' => 'required|in:Listrik,Gas Elpiji 5 kg/blue gas,Gas Elpiji 12 kg,Gas Elpiji 3 kg,Gas Meteran GPN,Biogas,Minyak Tanah,Breket,Arang,Kayu Bakar,Lainnya,Tidak Memasak dirumah',
            'KepemilikanBAB' => 'required|string',
            'JenisKloset' => 'nullable|in:Leher Angsa,Plesengan dengan tutup,Plengsengan tanpa tutup,Cemplung/Bubluk',
            'TempatPembuanganAkhirTinja' => 'required|in:Septik tank,IPAL,Kolam/Sungai,Lubang tanah,Tanah Lapang/Kebun,Lainnya',
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
