<?php

namespace App\Http\Controllers;

use App\Models\DataBangunan;
use App\Models\dataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

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
                        ->orWhere('NamaKepalaKeluarga', 'like', '%' . $search . '%')
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
            $query->orderBy('created_at', 'desc');
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

        $previousNomorKK = $dataBangunan->NomorKK ?? null; // Mengambil data Nomor KK terkait
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null; // Mengambil Nama Kepala Keluarga berdasarkan Nomor KK

        return view('dataKondisiRumah.create',[
            'noKK' => $noKK->toJson(),
            'previousNomorKK' => $previousNomorKK, // Data lama Nomor KK
            'previousNamaKepalaKeluarga' => $previousNamaKepalaKeluarga, // Data lama Nama Kepala Keluarga
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data input
            $validatedData = $request->validate([
                'NomorKK' => 'required|numeric|digits:16',
                'NamaKepalaKeluarga' => 'required|string',
                'StatusKepemilikanBangunan' => 'required|string',
                'BuktiKepemilikan' => 'nullable|string',
                'LuasLantai' => 'required|numeric',
                'JenisLantai' => 'required|string',
                'JenisDindingTerluas' => 'required|string',
                'JenisAtapTerluas' => 'required|string',
                'SumberAirMinum' => 'required|string',
                'JarakSumberAirMinum' => 'nullable',
                'SumberPeneranganUtama' => 'required|string',
                'Meteran1' => 'nullable|string',
                'Meteran2' => 'nullable|string',
                'Meteran3' => 'nullable|string',
                'BahanBakarEnergiMemasak' => 'required|string',
                'KepemilikanBAB' => 'required|string',
                'JenisKloset' => 'nullable|string',
                'TempatPembuanganAkhirTinja' => 'required|string',
            ]);

            if (DataBangunan::where('NomorKK', $validatedData['NomorKK'])->exists()) {
                Alert::error('Gagal', 'Nomor KK sudah terdaftar.');
                return back()->withInput();
            }
            // Simpan data bangunan ke dalam tabel 'databangunan'
            $dataBangunan = DataBangunan::create($validatedData);


            // Log data yang berhasil disimpan (untuk debugging)
            Log::info('Data bangunan berhasil disimpan:', $dataBangunan->toArray());

            // Redirect dengan pesan sukses
            Alert::success('Sukses', 'Data bangunan berhasil disimpan.');
            return redirect()->route('bangunan.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan log pesan error
            Log::warning('Validasi gagal: ' . json_encode($e->errors()));

            // Tampilkan pesan error ke pengguna
            $errorMessage = 'Terjadi kesalahan pada data yang diinput:';
            foreach ($e->errors() as $field => $messages) {
                $errorMessage .= "\n- " . ucfirst($field) . ": " . implode(', ', $messages);
            }

            // Tampilkan notifikasi error menggunakan SweetAlert
            Alert::error('Error', $errorMessage);

            return back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $th) {
            // Tangkap error tak terduga dan log detailnya
            Log::error('Error saat menyimpan data bangunan: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);

            // Tampilkan notifikasi error kepada pengguna
            Alert::error('Error', 'Terjadi kesalahan tak terduga. Silakan coba lagi.');

            return back();
        }
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
        try {
            // Validasi data input untuk data bangunan
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

            // Pastikan Anda memiliki ID untuk memperbarui data

            // Update data ke dalam tabel 'databangunan'
            $dataBangunan->update($validatedData);

            Alert::success('Sukses', 'Data bangunan berhasil diperbarui.');
            return redirect()->route('bangunan.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan tampilkan pesan error
            $errors = $e->errors(); // Mengambil semua pesan error

            // Buat pesan error untuk ditampilkan
            $errorMessage = 'Terjadi kesalahan pada data yang diinput:';
            foreach ($errors as $field => $messages) {
                $errorMessage .= "\n- " . ucfirst($field) . ": " . implode(', ', $messages);
            }

            // Tampilkan pesan error menggunakan SweetAlert
            Alert::error('Error', $errorMessage);
            return back()->withInput()->withErrors($errors);
        } catch (\Throwable $th) {
            // Tangkap error dan log detailnya
            Log::error('Error saat memperbarui data bangunan: ' . $th->getMessage());

            // Tampilkan notifikasi error
            Alert::error('Error', 'Terjadi kesalahan. Silakan coba lagi.');

            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataBangunan $dataBangunan)
    {
        try {
            // Hapus data bangunan
            $dataBangunan->delete();

            // Redirect dengan pesan sukses
            return redirect()->route('bangunan.index') // Ganti dengan route tujuan setelah penghapusan
                ->with('success', 'Data bangunan berhasil dihapus.');
        } catch (\Throwable $th) {
            // Tangkap error dan log detailnya
            Log::error('Error saat menghapus data bangunan: ' . $th->getMessage());

            // Tampilkan notifikasi error
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data bangunan.');

            return back();
        }
    }

}
