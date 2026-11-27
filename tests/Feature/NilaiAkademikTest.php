<?php

namespace Tests\Feature;

use Tests\TestCase;

class NilaiAkademikTest extends TestCase
{
    public function test_halaman_utama_uji_nilai_dapat_diakses(): void
    {
        $response = $this->get('/uji-nilai');

        $response->assertStatus(200);
        $response->assertSee('Panel Simulasi Error Handling & Debugging');
        $response->assertSee('Rizky Pratama');
    }

    public function test_perhitungan_ip_mahasiswa_normal_akurat(): void
    {
        $response = $this->get('/uji-nilai/hitung/2410114001');

        $response->assertStatus(200);
        $response->assertSee('3.80');
        $response->assertSee('24 SKS');
    }

    public function test_error_handling_saat_total_sks_bernilai_nol(): void
    {
        $response = $this->get('/uji-nilai/hitung/2410114003');

        $response->assertStatus(200);
        $response->assertSee('Total SKS bernilai 0');
    }

    public function test_nim_tidak_terdaftar_menampilkan_error_404(): void
    {
        $response = $this->get('/uji-nilai/hitung/99999999');

        $response->assertStatus(404);
    }
}