<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'email' => 'Kolom :attribute harus berupa alamat email yang valid.',
    'min' => [
        'numeric' => 'Kolom :attribute harus minimal :min.',
        'string' => 'Kolom :attribute harus memiliki minimal :min karakter.',
    ],
    'max' => [
        'numeric' => 'Kolom :attribute tidak boleh lebih dari :max.',
        'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
    ],
    'custom' => [
        'NomorKK' => [
            'digits' => 'Kolom :attribute harus terdiri dari tepat 16 digit.',
        ],
        'IdLandmarkWilkerStat' => [
            'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka.',
            'size' => 'Kolom :attribute harus terdiri dari tepat 6 karakter.',
            'required' => 'Kolom :attribute wajib diisi.',
        ],
    ],

    'integer' => 'Kolom :attribute harus diisi dengan angka.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'unique' => ':Attribute sudah digunakan.',

    'status' => 'Tautan untuk mereset kata sandi telah dikirim ke email Anda. Silakan cek email Anda untuk melanjutkan.'
    // Tambahkan pesan lainnya jika diperlukan
];
