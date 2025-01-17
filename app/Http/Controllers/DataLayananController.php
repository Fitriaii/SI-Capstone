<?php

namespace App\Http\Controllers;

use App\Models\dataKeluarga;
use App\Models\DataLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

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

            $query->orderBy('created_at', 'desc');
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
        $noKK = DataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();

        // Jika ada data lama (misalnya, pada session sebelumnya), kita ambil Nomor KK yang dipilih
        $previousNomorKK = session('previousNomorKK', null); // Bisa menggunakan session atau default null jika tidak ada
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null;
        // Kirim data ke view dalam bentuk JSON, termasuk previousNomorKK
        return view('dataLayanan.create', [
            'noKK' => $noKK->toJson(), // Kirim data Nomor KK ke view dalam format JSON
            'previousNomorKK' => $previousNomorKK, // Mengirimkan Nomor KK sebelumnya jika ada
            'previousNamaKepalaKeluarga' => $previousNamaKepalaKeluarga
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
                'NamaKepalaKeluarga' => 'required|string|max:100',
                'JenisAksesInternet' => 'required|string|in:Tidak Menggunakan,Internet dan TV Digital berlangganan,Wifi,Internet Handphone',
                'KepemilikanRekeningEWallet' => 'required|string', // Validasi hanya menerima "Ya" atau "Tidak"
            ]);

            if (DataLayanan::where('NomorKK', $validatedData['NomorKK'])->exists()) {
                Alert::error('Gagal', 'Nomor KK sudah terdaftar.');
                return back()->withInput();
            }
            
            $dataLayanan = DataLayanan::create($validatedData);

            // Log data yang berhasil disimpan
            Log::info('Data layanan berhasil disimpan:', $dataLayanan->toArray());

            // Redirect dengan pesan sukses
            Alert::success('Sukses', 'Data layanan berhasil disimpan.');
            return redirect()->route('layanan.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan log pesan error
            Log::warning('Validasi gagal: ' . json_encode($e->errors()));

            // Buat pesan error untuk ditampilkan
            $errorMessage = 'Terjadi kesalahan pada data yang diinput:';
            foreach ($e->errors() as $field => $messages) {
                $errorMessage .= "\n- " . ucfirst($field) . ": " . implode(', ', $messages);
            }

            // Tampilkan pesan error menggunakan SweetAlert
            Alert::error('Error', $errorMessage);

            // Mengembalikan input yang sudah dimasukkan dan menampilkan error
            return back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $th) {
            // Tangkap error tak terduga dan log detailnya
            Log::error('Error saat menyimpan data layanan: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
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
        try {
            // Validasi data yang dikirim
            $validatedData = $request->validate([
                'NomorKK' => 'required|numeric|digits:16',
                'NamaKepalaKeluarga' => 'required|string|max:100',
                'JenisAksesInternet' => 'required|string|in:Tidak Menggunakan,Internet dan TV Digital berlangganan,Wifi,Internet Handphone',
                'KepemilikanRekeningEWallet' => 'required|string', // Menambahkan validasi nilai Ya/Tidak
            ]);

            // Perbarui data di database
            $dataLayanan->update($validatedData);

            // Redirect dengan notifikasi sukses
            Alert::success('Sukses', 'Data layanan berhasil diperbarui.');
            return redirect()->route('layanan.index');
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
            Log::error('Error saat memperbarui data layanan: ' . $th->getMessage());

            // Tampilkan notifikasi error
            Alert::error('Error', 'Terjadi kesalahan. Silakan coba lagi.');
            return back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataLayanan $dataLayanan)
    {
        try {
            // Hapus data dari database
            $dataLayanan->delete();

            // Redirect dengan notifikasi sukses
            Alert::success('Sukses', 'Data layanan berhasil dihapus.');
            return redirect()->route('layanan.index');
        } catch (\Throwable $th) {
            // Tangkap error dan log detailnya
            Log::error('Error saat menghapus data layanan: ' . $th->getMessage());

            // Tampilkan notifikasi error
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
            return back();
        }
    }

}
