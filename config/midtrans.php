<?php
return [
  'is_production' => env('MIDTRANS_IS_PRODUCTION', true),
  'server_key'    => env('MIDTRANS_SERVER_KEY'), 
  'client_key'    => env('MIDTRANS_CLIENT_KEY'),
  // 'device_url'    => env('DEVICE_TRIGGER_URL'),
];
