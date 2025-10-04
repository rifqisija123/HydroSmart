<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bayar Rp1 (QRIS) → Nyalakan LED</title>
  <!-- Snap JS production -->
  <script src="https://app.midtrans.com/snap/snap.js"
          data-client-key="{{ config('midtrans.client_key') }}"></script>
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Arial;margin:40px}
    button{padding:12px 18px;border-radius:10px;border:1px solid #ddd;cursor:pointer}
  </style>
</head>
<body>
  <h2>Nyalakan Relay 5 Detik — Rp1 (QRIS)</h2>
  <p>Tekan tombol lalu scan QRIS.</p>
  <button id="payBtn">Bayar QRIS</button>

  <script>
    document.getElementById('payBtn').addEventListener('click', async () => {
      const csrf = document.querySelector('meta[name=csrf-token]').getAttribute('content');
      const res  = await fetch('{{ route('pay') }}', {
        method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrf}, body: '{}'
      });
      const { token } = await res.json();
      window.snap.pay(token, {
        onSuccess:   (r)=>console.log('success', r),
        onPending:   (r)=>console.log('pending', r),
        onError:     (r)=>console.error('error', r),
        onClose:     ()=>console.warn('closed')
      });
    });
  </script>
</body>
</html>
