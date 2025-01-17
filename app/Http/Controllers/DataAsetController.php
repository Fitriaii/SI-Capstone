<?php

namespace App\Http\Controllers;

use App\Models\DataAset;
use App\Models\dataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

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

            $query->orderBy('created_at', 'desc');
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

        $previousNomorKK = session('previousNomorKK', null); // Bisa menggunakan session atau default null jika tidak ada
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null;
        // Kirim data ke view dalam bentuk JSON, termasuk previousNomorKK
        return view('dataAset.create', [
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
                'Sapi' => 'nullable|integer',
                'Kerbau' => 'nullable|integer',
                'Kuda' => 'nullable|integer',
                'Babi' => 'nullable|integer',
                'Kambing' => 'nullable|integer',
            ]);

            if (DataAset::where('NomorKK', $validatedData['NomorKK'])->exists()) {
                Alert::error('Gagal', 'Nomor KK sudah terdaftar.');
                return back()->withInput();
            }

            $dataAset = DataAset::create($validatedData);

            // Log data yang berhasil disimpan
            Log::info('Data aset berhasil disimpan:', $dataAset->toArray());

            // Redirect dengan notifikasi sukses
            Alert::success('Sukses', 'Data aset berhasil disimpan.');
            return redirect()->route('aset.index');
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
            return back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $th) {
            // Tangkap error tak terduga dan log detailnya
            Log::error('Error saat menyimpan data aset: ' . $th->getMessage(), [
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
        try {
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

            // Update data aset
            $dataAset->update($validatedData);

            // Redirect dengan notifikasi sukses
            Alert::success('Sukses', 'Data aset berhasil diperbarui.');
            return redirect()->route('aset.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi
            $errors = $e->errors();
            $errorMessage = 'Terjadi kesalahan pada data yang diinput:';

            foreach ($errors as $field => $messages) {
                $errorMessage .= "\n- " . ucfirst($field) . ": " . implode(', ', $messages);
            }

            // Tampilkan pesan error menggunakan SweetAlert
            Alert::error('Error', $errorMessage);
            return back()->withInput()->withErrors($errors);
        } catch (\Throwable $th) {
            // Tangkap error lain dan log detailnya
            Log::error('Error saat mengupdate data aset: ' . $th->getMessage());

            // Tampilkan notifikasi error
            Alert::error('Error', 'Terjadi kesalahan. Silakan coba lagi.');
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataAset $dataAset)
    {
        try {
            // Hapus data aset
            $dataAset->delete();

            // Redirect dengan notifikasi sukses
            Alert::success('Sukses', 'Data aset berhasil dihapus.');
            return redirect()->route('aset.index');
        } catch (\Throwable $th) {
            // Tangkap error dan log detailnya
            Log::error('Error saat menghapus data aset: ' . $th->getMessage());

            // Tampilkan notifikasi error
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
            return back();
        }
    }


}
