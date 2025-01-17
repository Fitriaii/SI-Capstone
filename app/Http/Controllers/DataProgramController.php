<?php

namespace App\Http\Controllers;

use App\Models\dataKeluarga;
use App\Models\DataProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class DataProgramController extends Controller
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
            $query = DataProgram::with(['nik']);

            // Filter berdasarkan search jika ada
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('NomorKK', 'like', '%' . $search . '%')
                        ->orWhere('NamaKepalaKeluarga', 'like', '%' . $search . '%')
                        ->orWhere('ProgramBantuanSembako', 'like', '%' . $search . '%')
                        ->orWhere('ProgramPKH', 'like', '%' . $search . '%')
                        ->orWhere('ProgramBLT', 'like', '%' . $search . '%')
                        ->orWhere('ProgramSubsidiListrik', 'like', '%' . $search . '%')
                        ->orWhere('ProgramBantuanPemda', 'like', '%' . $search . '%')
                        ->orWhere('ProgramSubsidiPupuk', 'like', '%' . $search . '%')
                        ->orWhere('ProgramSubsidiLPG', 'like', '%' . $search . '%');
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
            $program = $query->paginate(10);

            // Mengirimkan data ke view dengan menggunakan compact
            return view('dataProgram.index', compact('program',   'search', 'padukuhan'));
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
        return view('dataProgram.create', [
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
            // Menetapkan nilai default "Tidak" untuk setiap program jika tidak ada input yang diberikan
            $request->merge([
                'ProgramBantuanSembako' => $request->input('ProgramBantuanSembako', 'Tidak'),
                'ProgramPKH' => $request->input('ProgramPKH', 'Tidak'),
                'ProgramBLT' => $request->input('ProgramBLT', 'Tidak'),
                'ProgramSubsidiListrik' => $request->input('ProgramSubsidiListrik', 'Tidak'),
                'ProgramBantuanPemda' => $request->input('ProgramBantuanPemda', 'Tidak'),
                'ProgramSubsidiPupuk' => $request->input('ProgramSubsidiPupuk', 'Tidak'),
                'ProgramSubsidiLPG' => $request->input('ProgramSubsidiLPG', 'Tidak')
            ]);

            // Validasi data input
            $validatedData = $request->validate([
                'NomorKK' => 'required|numeric|digits:16',
                'NamaKepalaKeluarga' => 'required|string|max:100',
                'ProgramBantuanSembako' => 'required|in:Ya,Tidak',
                'PeriodeSembako' => 'nullable', // Validasi format tanggal
                'ProgramPKH' => 'required|in:Ya,Tidak',
                'PeriodePKH' => 'nullable', // Validasi format tanggal
                'ProgramBLT' => 'required|in:Ya,Tidak',
                'PeriodeBLT' => 'nullable', // Validasi format tanggal
                'ProgramSubsidiListrik' => 'required|in:Ya,Tidak',
                'PeriodeSubsidiListrik' => 'nullable', // Validasi format tanggal
                'ProgramBantuanPemda' => 'required|in:Ya,Tidak',
                'PeriodeBantuanPemda' => 'nullable', // Validasi format tanggal
                'ProgramSubsidiPupuk' => 'required|in:Ya,Tidak',
                'PeriodeSubsidiPupuk' => 'nullable', // Validasi format tanggal
                'ProgramSubsidiLPG' => 'required|in:Ya,Tidak',
                'PeriodeSubsidiLPG' => 'nullable', // Validasi format tanggal
            ]);

            // Cek apakah Nomor KK sudah ada di database
            if (DataProgram::where('NomorKK', $validatedData['NomorKK'])->exists()) {
                Alert::error('Gagal', 'Nomor KK sudah terdaftar.');
                return back()->withInput();
            }

            // Simpan data ke dalam tabel 'dataprogram'
            $dataProgram = DataProgram::create($validatedData);

            // Log data yang berhasil disimpan
            Log::info('Data program berhasil disimpan:', $dataProgram->toArray());

            // Redirect dengan pesan sukses
            Alert::success('Sukses', 'Data program berhasil disimpan.');
            return redirect()->route('program.index'); // Pastikan route ini sesuai

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
            Log::error('Error saat menyimpan data program: ' . $th->getMessage(), [
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
    public function edit(DataProgram $dataProgram)
    {
        $noKK = DataKeluarga::select('NomorKK', 'NamaKepalaKeluarga')->get();

        // Ambil data lama untuk ditampilkan di form (jika ada)
        $previousNomorKK = $dataProgram->NomorKK ?? null; // Mengambil data Nomor KK terkait
        $previousNamaKepalaKeluarga = $previousNomorKK
            ? DataKeluarga::where('NomorKK', $previousNomorKK)->value('NamaKepalaKeluarga')
            : null; // Mengambil Nama Kepala Keluarga berdasarkan Nomor KK

        // Kirim data ke view
        return view('dataProgram.edit', [
            'dataProgram' => $dataProgram,
            'noKK' => $noKK->toJson(), // Kirim data Nomor KK ke view dalam bentuk JSON
            'previousNomorKK' => $previousNomorKK, // Data lama Nomor KK
            'previousNamaKepalaKeluarga' => $previousNamaKepalaKeluarga, // Data lama Nama Kepala Keluarga
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataProgram $dataProgram)
    {
        try {
            // Validasi data yang dikirim
            $validatedData = $request->validate([
                'NomorKK' => 'required|numeric|digits:16',
                'NamaKepalaKeluarga' => 'required|string|max:100',
                'ProgramBantuanSembako' => 'required|in:Ya,Tidak',
                'PeriodeSembako' => 'nullable',
                'ProgramPKH' => 'required|in:Ya,Tidak',
                'PeriodePKH' => 'nullable',
                'ProgramBLT' => 'required|in:Ya,Tidak',
                'PeriodeBLT' => 'nullable',
                'ProgramSubsidiListrik' => 'required|in:Ya,Tidak',
                'PeriodeSubsidiListrik' => 'nullable',
                'ProgramBantuanPemda' => 'required|in:Ya,Tidak',
                'PeriodeBantuanPemda' => 'nullable',
                'ProgramSubsidiPupuk' => 'required|in:Ya,Tidak',
                'PeriodeSubsidiPupuk' => 'nullable',
                'ProgramSubsidiLPG' => 'required|in:Ya,Tidak',
                'PeriodeSubsidiLPG' => 'nullable',
            ]);

            // Update data di database
            $dataProgram->update($validatedData);

            // Redirect dengan notifikasi sukses
            Alert::success('Sukses', 'Data program berhasil diperbarui.');
            return redirect()->route('program.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan tampilkan pesan error
            $errors = $e->errors();

            // Buat pesan error untuk notifikasi
            $errorMessage = 'Terjadi kesalahan pada data yang diinput:';
            foreach ($errors as $field => $messages) {
                $errorMessage .= "\n- " . ucfirst($field) . ": " . implode(', ', $messages);
            }

            // Tampilkan notifikasi error menggunakan SweetAlert
            Alert::error('Error', $errorMessage);
            return back()->withInput()->withErrors($errors);
        } catch (\Throwable $th) {
            // Tangkap error lainnya dan log detailnya
            Log::error('Error saat mengupdate data program: ' . $th->getMessage());

            // Tampilkan notifikasi error umum
            Alert::error('Error', 'Terjadi kesalahan. Silakan coba lagi.');
            return back();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataProgram $dataProgram)
    {
        try {
            // Hapus data program dari database
            $dataProgram->delete();

            // Redirect dengan notifikasi sukses
            Alert::success('Sukses', 'Data program berhasil dihapus.');
            return redirect()->route('program.index');
        } catch (\Throwable $th) {
            // Tangkap error dan log detailnya
            Log::error('Error saat menghapus data program: ' . $th->getMessage());

            // Tampilkan notifikasi error jika terjadi kesalahan
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data program.');
            return redirect()->route('program.index');
        }
    }

}
