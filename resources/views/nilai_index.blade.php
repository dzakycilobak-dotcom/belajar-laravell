<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengujian & Error Handling Nilai Akademik</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 30px; }
        .container { max-width: 850px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h2 { color: #2c3e50; margin-top: 0; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #2c3e50; color: white; }
        .btn { padding: 6px 12px; background: #27ae60; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; margin-right: 5px; }
        .btn-debug { background: #8e44ad; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Panel Simulasi Error Handling & Debugging</h2>
        <p>Silakan pilih skenario pengujian pada daftar mahasiswa di bawah ini:</p>
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Total Mutu</th>
                    <th>Total SKS</th>
                    <th>Aksi Uji Coba</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mahasiswa as $nim => $item)
                <tr>
                    <td><b>{{ $nim }}</b></td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['total_mutu'] }}</td>
                    <td>{{ $item['total_sks'] }} SKS</td>
                    <td>
                        <a href="{{ url('/uji-nilai/hitung/' . $nim) }}" class="btn">Hitung IP</a>
                        <a href="{{ url('/uji-nilai/hitung/' . $nim . '?debug=1') }}" class="btn btn-debug">Debug dd()</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top: 20px; font-size: 14px;">
            <b>Uji Error 404:</b> <a href="{{ url('/uji-nilai/hitung/99999999') }}">Klik di sini untuk menguji NIM yang tidak terdaftar (99999999)</a>
        </p>
    </div>
</body>
</html>