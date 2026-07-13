<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kunjungan Tamu</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin-top: 0; font-size: 10px; color: #666; }
    </style>
</head>
<body>

    <h2>Laporan Kunjungan Tamu BKPSDM</h2>
    <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Nama Tamu</th>
                <th>Instansi</th>
                <th>Bidang/Tujuan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataTamu as $index => $tamu)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $tamu->nama_lengkap ?? $tamu->nama }}</td>
                <td>{{ $tamu->instansi }}</td>
                <td>{{ $tamu->tujuan ?? $tamu->bidang }}</td>
                <td>{{ $tamu->created_at ? $tamu->created_at->format('d-m-Y H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>