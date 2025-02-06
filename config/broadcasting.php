<?php

return [
  'default' => env('BROADCAST_DRIVER', 'pusher'),
  'connections' => [
    // Don't add your credentials here!
// config/broadcasting.php

  'pusher' => [
      'driver' => 'pusher',
      'key' => env('PUSHER_APP_KEY'),
      'secret' => env('PUSHER_APP_SECRET'),
      'app_id' => env('PUSHER_APP_ID'),
      'options' => [
          'cluster' => env('PUSHER_CLUSTER'),
          'encrypted' => true,
      ],
  ],

  ],
];

