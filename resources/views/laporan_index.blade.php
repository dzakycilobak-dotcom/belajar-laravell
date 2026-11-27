<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kas Himpunan Mahasiswa</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 30px; color: #333; }
        .container { max-width: 960px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h2 { margin-top: 0; color: #2c3e50; border-bottom: 3px solid #2980b9; padding-bottom: 10px; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef; }
        select { padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc; }
        .btn { padding: 8px 14px; border-radius: 5px; text-decoration: none; color: white; font-size: 13px; font-weight: bold; border: none; cursor: pointer; display: inline-block; }
        .btn-filter { background: #34495e; }
        .btn-pdf { background: #e74c3c; }
        .btn-preview { background: #e67e22; }
        .btn-excel { background: #27ae60; }
        .btn-print { background: #3498db; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 11px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #2c3e50; color: white; }
        .text-right { text-align: right; }
        .badge { padding: 4px 9px; border-radius: 10px; color: white; font-size: 12px; font-weight: bold; }
        .bg-masuk { background: #27ae60; }
        .bg-keluar { background: #c0392b; }
        .summary-box { margin-top: 20px; padding: 15px; background: #eef6ff; border-left: 5px solid #2980b9; border-radius: 5px; line-height: 1.8; }

        /* Aturan Khusus Saat Cetak Langsung (Print Browser) */
        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; }
            .container { box-shadow: none; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Laporan Keuangan Kas Himpunan Mahasiswa (Kategori: {{ $kategori_aktif }})</h2>

    <!-- Area Filter dan Tombol Export (Disembunyikan saat Print) -->
    <div class="toolbar no-print">
        <form method="GET" action="{{ url('/laporan') }}">
            <label><b>Filter Kategori:</b></label>
            <select name="kategori">
                <option value="Semua" {{ $kategori_aktif == 'Semua' ? 'selected' : '' }}>Semua Transaksi</option>
                <option value="Pemasukan" {{ $kategori_aktif == 'Pemasukan' ? 'selected' : '' }}>Pemasukan Saja</option>
                <option value="Pengeluaran" {{ $kategori_aktif == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran Saja</option>
            </select>
            <button type="submit" class="btn btn-filter">Terapkan Filter</button>
        </form>

        <div>
            <a href="{{ url('/laporan/pdf?kategori=' . $kategori_aktif . '&mode=stream') }}" target="_blank" class="btn btn-preview">Preview PDF</a>
            <a href="{{ url('/laporan/pdf?kategori=' . $kategori_aktif . '&mode=download') }}" class="btn btn-pdf">Download PDF</a>
            <a href="{{ url('/laporan/excel?kategori=' . $kategori_aktif) }}" class="btn btn-excel">Export Excel (CSV)</a>
            <button onclick="window.print()" class="btn btn-print">Cetak Langsung</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Keterangan Transaksi</th>
                <th>Kategori</th>
                <th class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><b>{{ $item['kode'] }}</b></td>
                <td>{{ $item['tanggal'] }}</td>
                <td>{{ $item['keterangan'] }}</td>
                <td>
                    @if ($item['kategori'] === 'Pemasukan')
                        <span class="badge bg-masuk">Pemasukan</span>
                    @else
                        <span class="badge bg-keluar">Pengeluaran</span>
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <b>Total Pemasukan:</b> Rp {{ number_format($rekap['total_masuk'], 0, ',', '.') }} <br>
        <b>Total Pengeluaran:</b> Rp {{ number_format($rekap['total_keluar'], 0, ',', '.') }} <br>
        <b>Saldo Akhir Kas:</b> <span style="font-size: 16px; color: #2980b9;"><b>Rp {{ number_format($rekap['saldo_akhir'], 0, ',', '.') }}</b></span>
    </div>
</div>
</body>
</html>