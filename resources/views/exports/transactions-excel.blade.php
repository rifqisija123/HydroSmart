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
                <td>{{ $item->paid_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
