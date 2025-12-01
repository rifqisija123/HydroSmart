<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px;}
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>

<body>

<h2 style="text-align: center;">Laporan Transaksi WADAH</h2>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Minuman</th>
            <th>Volume</th>
            <th>Total Pembayaran</th>
            <th>Transaction Type</th>
            <th>Issuer</th>
            <th>Status</th>
            <th>Tanggal Waktu</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($records as $item)
        <tr>
            <td>{{ $item->order_id }}</td>
            <td>{{ ucfirst($item->drink) }}</td>
            <td>{{ $item->ml }} ml</td>
            <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
            <td>QRIS</td>
            <td>{{ $item->issuer ?? '-' }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td>
                {{ \Carbon\Carbon::parse($item->paid_at)->timezone(config('app.timezone'))->format('d M Y H:i:s') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
