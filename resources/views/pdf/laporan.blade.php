<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Serial Number</th>
                <th>Tanggal</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->product_name }}</td>
                    <td>{{ $row->sn ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') }}</td>
                    <td>{{ $row->price }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{ ucfirst($row->message) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
