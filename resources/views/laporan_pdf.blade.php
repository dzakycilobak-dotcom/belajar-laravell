<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Laporan Kas Resmi</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
        .kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop-surat h4 { margin: 4px 0; font-size: 13px; font-weight: normal; }
        .kop-surat p { margin: 2px 0; font-size: 10px; color: #555; }
        .meta-info { width: 100%; margin-bottom: 15px; }
        .meta-info td { padding: 3px 0; border: none; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #333; padding: 8px; text-align: left; }
        .data-table th { background-color: #2c3e50; color: #ffffff; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { background-color: #f2f2f2; font-weight: bold; }
        .ttd-table { width: 100%; margin-top: 40px; }
        .ttd-table td { border: none; text-align: center; width: 50%; vertical-align: top; }
        .ruang-ttd { height: 65px; }
    </style>
</head>
<body>
    <!-- KOP SURAT DOKUMEN -->
    <div class="kop-surat">
        <h2>Himpunan Mahasiswa Teknik Informatika</h2>
        <h4>Laporan Rekapitulasi Keuangan & Transaksi Kas</h4>
        <p>Jl. Pendidikan Raya No. 10, Gedung Fakultas Ilmu Komputer | Email: himatif@kampus.ac.id</p>
    </div>

    <!-- INFORMASI LAPORAN -->
    <table class="meta-info">
        <tr>
            <td style="width: 20%;"><b>Filter Kategori</b></td>
            <td style="width: 30%;">: {{ $kategori_aktif }}</td>
            <td style="width: 20%;"><b>Tanggal Cetak</b></td>
            <td style="width: 30%;">: {{ $tanggal_cetak }}</td>
        </tr>
        <tr>
            <td><b>Jumlah Data</b></td>
            <td>: {{ count($transaksi) }} Transaksi</td>
            <td><b>Status Dokumen</b></td>
            <td>: Resmi (Otomatis dari Sistem)</td>
        </tr>
    </table>

    <!-- TABEL DATA UTAMA -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 14%;">Kode</th>
                <th style="width: 14%;">Tanggal</th>
                <th style="width: 34%;">Keterangan</th>
                <th style="width: 14%;">Kategori</th>
                <th style="width: 18%;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $item['kode'] }}</td>
                <td class="text-center">{{ $item['tanggal'] }}</td>
                <td>{{ $item['keterangan'] }}</td>
                <td class="text-center">{{ $item['kategori'] }}</td>
                <td class="text-right">Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pemasukan</td>
                <td class="text-right">Rp {{ number_format($rekap['total_masuk'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pengeluaran</td>
                <td class="text-right">Rp {{ number_format($rekap['total_keluar'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row" style="background-color: #d9edf7;">
                <td colspan="5" class="text-right">SALDO AKHIR KAS</td>
                <td class="text-right">Rp {{ number_format($rekap['saldo_akhir'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- BLOK TANDA TANGAN PENGESAHAN -->
    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui, <br>
                <b>Ketua Himpunan</b>
                <div class="ruang-ttd"></div>
                <u><b>( ........................................ )</b></u>
            </td>
            <td>
                Tangerang Selatan, {{ $tanggal_cetak }}<br>
                <b>Bendahara Umum</b>
                <div class="ruang-ttd"></div>
                <u><b>( ........................................ )</b></u>
            </td>
        </tr>
    </table>
</body>
</html>