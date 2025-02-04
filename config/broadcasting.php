<?php

return [
  'default' => env('BROADCAST_DRIVER', 'pusher'),
  'connections' => [
    'pusher' => [
      'cluster' => 'us3',
      'useTLS' => true
    ],
  ],
];

