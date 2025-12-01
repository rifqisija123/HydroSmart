<html>
<head>
    <title>Print Transaksi — WADAH</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            padding: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }
        th, td { 
            padding: 8px; 
            border: 1px solid #333; 
            text-align: left;
        }
        th { 
            background: #eee; 
        }

        .print-btn {
            display: inline-block;
            margin-top: 5px;
            padding: 8px 14px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

<h2 style="text-align: center;">Laporan Transaksi WADAH</h2>

<button class="print-btn" onclick="window.print()">🖨️ Print</button>

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
