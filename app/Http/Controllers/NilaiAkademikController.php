<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NilaiAkademikController extends Controller
{
    private $data_mahasiswa = [
        '2410114001' => [
            'nama' => 'Rizky Pratama',
            'prodi' => 'Teknik Informatika',
            'total_mutu' => 76,
            'total_sks' => 20
        ],
        '2410114002' => [
            'nama' => 'Siti Nabila',
            'prodi' => 'Teknik Informatika',
            'total_mutu' => 78,
            'total_sks' => 20
        ],
        '2410114003' => [
            'nama' => 'Budi Hartono (Mahasiswa Baru)',
            'prodi' => 'Teknik Informatika',
            'total_mutu' => 0,
            'total_sks' => 0
        ]
    ];

    public function index()
    {
        return view('nilai_index', [
            'mahasiswa' => $this->data_mahasiswa
        ]);
    }

    public function hitungIp(Request $request, $nim)
    {
        if (!isset($this->data_mahasiswa[$nim])) {
            Log::warning("Percobaan akses NIM tidak terdaftar: " . $nim);
            abort(404, "Mahasiswa dengan NIM {$nim} tidak ditemukan di database akademik.");
        }

        $mhs = $this->data_mahasiswa[$nim];

        if ($request->query('debug') == '1') {
            dd([
                'INFO_DEBUG' => 'Memeriksa isi data mentah sebelum dihitung',
                'nim' => $nim,
                'data_mahasiswa' => $mhs
            ]);
        }

        try {
            if ($mhs['total_sks'] <= 0) {
                throw new \Exception("Total SKS bernilai 0. Tidak dapat menghitung IP Semester karena belum ada mata kuliah yang diambil.");
            }

            $ip_semester = $mhs['total_mutu'] / $mhs['total_sks'];
            $kuota_sks_depan = ($ip_semester >= 3.00) ? 24 : 20;

            Log::info("Berhasil menghitung IP untuk NIM: {$nim}", ['ip' => $ip_semester]);

            return view('nilai_hasil', [
                'nim' => $nim,
                'mhs' => $mhs,
                'ip' => number_format($ip_semester, 2),
                'kuota_sks' => $kuota_sks_depan,
                'pesan_error' => null
            ]);
        } catch (\Throwable $e) {
            Log::error("Error Perhitungan IP pada NIM {$nim}: " . $e->getMessage());

            return view('nilai_hasil', [
                'nim' => $nim,
                'mhs' => $mhs,
                'ip' => '0.00',
                'kuota_sks' => 0,
                'pesan_error' => $e->getMessage()
            ]);
        }
    }
}