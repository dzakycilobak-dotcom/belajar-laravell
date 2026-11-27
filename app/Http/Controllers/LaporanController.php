<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // Data sumber simulasi transaksi kas himpunan mahasiswa
    private function ambilDataTransaksi($kategori = 'Semua')
    {
        $semua_data = [
            [
                'kode' => 'TRX-001',
                'tanggal' => '2026-11-01',
                'keterangan' => 'Iuran Kas Anggota Bulan November',
                'kategori' => 'Pemasukan',
                'nominal' => 1500000
            ],
            [
                'kode' => 'TRX-002',
                'tanggal' => '2026-11-05',
                'keterangan' => 'Pembelian Spanduk & ATK Workshop Laravel',
                'kategori' => 'Pengeluaran',
                'nominal' => 450000
            ],
            [
                'kode' => 'TRX-003',
                'tanggal' => '2026-11-10',
                'keterangan' => 'Dana Sponsorship Seminar Nasional IT',
                'kategori' => 'Pemasukan',
                'nominal' => 3000000
            ],
            [
                'kode' => 'TRX-004',
                'tanggal' => '2026-11-14',
                'keterangan' => 'Konsumsi Pemateri dan Panitia Workshop',
                'kategori' => 'Pengeluaran',
                'nominal' => 850000
            ],
            [
                'kode' => 'TRX-005',
                'tanggal' => '2026-11-18',
                'keterangan' => 'Sewa Peralatan Sound System & Dokumentasi',
                'kategori' => 'Pengeluaran',
                'nominal' => 600000
            ]
        ];

        // Jika filter bukan 'Semua', saring data sesuai kategori
        if ($kategori !== 'Semua') {
            return array_values(array_filter($semua_data, function ($item) use ($kategori) {
                return $item['kategori'] === $kategori;
            }));
        }

        return $semua_data;
    }

    // Fungsi pembantu untuk menghitung rekap pemasukan, pengeluaran, dan saldo
    private function hitungRekap($transaksi)
    {
        $total_masuk = 0;
        $total_keluar = 0;

        foreach ($transaksi as $item) {
            if ($item['kategori'] === 'Pemasukan') {
                $total_masuk += $item['nominal'];
            } else {
                $total_keluar += $item['nominal'];
            }
        }

        return [
            'total_masuk' => $total_masuk,
            'total_keluar' => $total_keluar,
            'saldo_akhir' => $total_masuk - $total_keluar
        ];
    }

    // 1. Halaman Utama Laporan di Web (/laporan)
    public function index(Request $request)
    {
        $kategori = $request->query('kategori', 'Semua');
        $transaksi = $this->ambilDataTransaksi($kategori);
        $rekap = $this->hitungRekap($transaksi);

        return view('laporan_index', [
            'transaksi' => $transaksi,
            'kategori_aktif' => $kategori,
            'rekap' => $rekap
        ]);
    }

    // 2. Export ke Format PDF menggunakan DomPDF (/laporan/pdf)
    public function exportPdf(Request $request)
    {
        $kategori = $request->query('kategori', 'Semua');
        $mode = $request->query('mode', 'download'); // 'download' atau 'stream'
        $transaksi = $this->ambilDataTransaksi($kategori);
        $rekap = $this->hitungRekap($transaksi);

        $pdf = Pdf::loadView('laporan_pdf', [
            'transaksi' => $transaksi,
            'kategori_aktif' => $kategori,
            'rekap' => $rekap,
            'tanggal_cetak' => date('d F Y')
        ])->setPaper('a4', 'portrait');

        $nama_file = 'Laporan-Kas-' . $kategori . '-' . date('Ymd') . '.pdf';

        // Jika mode=stream, tampilkan pratinjau di tab browser
        if ($mode === 'stream') {
            return $pdf->stream($nama_file);
        }

        // Default: langsung unduh file PDF
        return $pdf->download($nama_file);
    }

    // 3. Export ke Format Excel / CSV (/laporan/excel)
    public function exportExcel(Request $request)
    {
        $kategori = $request->query('kategori', 'Semua');
        $transaksi = $this->ambilDataTransaksi($kategori);
        $rekap = $this->hitungRekap($transaksi);
        $nama_file = 'Laporan-Kas-' . $kategori . '-' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$nama_file\"",
        ];

        return response()->streamDownload(function () use ($transaksi, $rekap) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM agar terbaca bersih di Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Baris Header Kolom (gunakan ';' untuk Excel setting Indonesia)
            fputcsv($handle, ['No', 'Kode Transaksi', 'Tanggal', 'Keterangan', 'Kategori', 'Nominal (Rp)'], ';');

            // Baris Isi Data
            $no = 1;
            foreach ($transaksi as $row) {
                fputcsv($handle, [
                    $no++,
                    $row['kode'],
                    $row['tanggal'],
                    $row['keterangan'],
                    $row['kategori'],
                    $row['nominal']
                ], ';');
            }

            // Baris Kosong & Rekapitulasi Total di Bawah Tabel
            fputcsv($handle, [], ';');
            fputcsv($handle, ['', '', '', 'TOTAL PEMASUKAN', '', $rekap['total_masuk']], ';');
            fputcsv($handle, ['', '', '', 'TOTAL PENGELUARAN', '', $rekap['total_keluar']], ';');
            fputcsv($handle, ['', '', '', 'SALDO AKHIR', '', $rekap['saldo_akhir']], ';');

            fclose($handle);
        }, $nama_file, $headers);
    }
}