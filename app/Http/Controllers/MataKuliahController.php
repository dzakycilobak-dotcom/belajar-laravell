<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;

class MataKuliahController extends Controller
{
    public function index()
    {
        $matkul = MataKuliah::semuaData();

        $total_sks = 0;
        foreach ($matkul as $item) {
            $total_sks += $item['sks'];
        }

        return view('matakuliah_index', [
            'judul_halaman' => 'Katalog Mata Kuliah Teknik Informatika',
            'data_matkul'   => $matkul,
            'total_sks'     => $total_sks,
        ]);
    }

    public function detail(string $kode)
    {
        $matkul_pilihan = MataKuliah::cariKode($kode);

        if (!$matkul_pilihan) {
            abort(404, 'Data Mata Kuliah dengan kode tersebut tidak ditemukan.');
        }

        return view('matakuliah_detail', [
            'mk' => $matkul_pilihan,
        ]);
    }
}