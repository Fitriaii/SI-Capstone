<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

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

            $query->orderBy('created_at', 'desc');
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
        try {
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
                'IdLandmarkWilkerStat' => 'required|string|max:255',
                'NomorKK' => 'required|numeric|digits:16',
                'KodeKartuKK' => 'required|string|max:255',
            ]);

            // Cek jika NomorKK sudah ada di database
            $isNomorKKExist = DataKeluarga::where('NomorKK', $validatedData['NomorKK'])->exists();

            if ($isNomorKKExist) {
                Alert::error('Gagal', 'Nomor KK sudah terdaftar.');
                return back()->withInput();
            }

            // Simpan data keluarga ke database
            $dataKeluarga = DataKeluarga::create($validatedData);

            // Log data yang berhasil disimpan (untuk debugging)
            Log::info('Data keluarga berhasil disimpan:', $dataKeluarga->toArray());

            // Tampilkan notifikasi sukses
            Alert::success('Sukses', 'Data keluarga berhasil ditambahkan.');

            return redirect()->route('penduduk.index'); // Sesuaikan dengan route tujuan
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan log pesan error
            Log::warning('Validasi gagal: ' . json_encode($e->errors()));

            // Buat pesan error untuk pengguna
            $errorMessage = 'Terjadi kesalahan pada data yang diinput:';
            foreach ($e->errors() as $field => $messages) {
                $errorMessage .= "\n- " . ucfirst($field) . ": " . implode(', ', $messages);
            }

            // Tampilkan pesan error menggunakan SweetAlert
            Alert::error('Error', $errorMessage);

            return back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $th) {
            // Tangkap error tak terduga dan log detailnya
            Log::error('Error saat menyimpan data keluarga: ' . $th->getMessage(), [
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
        try {
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

            // Perbarui data di database
            $dataKeluarga->update($validatedData);

            // Notifikasi sukses
            Alert::success('Sukses', 'Data keluarga berhasil diperbarui.');

            // Redirect dengan pesan sukses
            return redirect()->route('penduduk.index') // Sesuaikan dengan route tujuan
                ->with('success', 'Data keluarga berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan tampilkan pesan error
            $errors = $e->errors(); // Ambil semua pesan error

            // Buat pesan error untuk ditampilkan
            $errorMessage = 'Terjadi kesalahan pada data yang diinput:';

            foreach ($errors as $field => $messages) {
                // Tambahkan pesan kesalahan untuk setiap kolom yang gagal
                $errorMessage .= "\n- " . ucfirst($field) . ": " . implode(', ', $messages);
            }

            // Tampilkan pesan error menggunakan SweetAlert
            Alert::error('Error', $errorMessage);

            return back()->withInput()->withErrors($errors);  // Kembali ke halaman dengan input dan error
        } catch (\Throwable $th) {
            // Tangkap error lainnya dan log
            Log::error('Error saat menyimpan data keluarga: ' . $th->getMessage());

            // Tampilkan notifikasi error jika terjadi kesalahan lainnya
            Alert::error('Error', 'Terjadi kesalahan. Silakan coba lagi.');

            return back(); // Kembali ke halaman sebelumnya
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataKeluarga $dataKeluarga)
    {
        try {
            // Hapus data dari database
            $dataKeluarga->delete();

            // Notifikasi sukses
            Alert::success('Sukses', 'Data keluarga berhasil dihapus.');

            // Redirect ke halaman indeks
            return redirect()->route('penduduk.index') // Sesuaikan dengan route tujuan
                ->with('success', 'Data keluarga berhasil dihapus.');
        } catch (\Throwable $th) {
            // Tangkap error dan log
            Log::error('Error saat menghapus data keluarga: ' . $th->getMessage());

            // Notifikasi error
            Alert::error('Error', 'Terjadi kesalahan. Data keluarga tidak dapat dihapus.');

            return back();
        }
    }



}
