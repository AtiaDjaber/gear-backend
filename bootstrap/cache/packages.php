<?php return array (
  'apility/laravel-fcm' => 
  array (
    'providers' => 
    array (
      0 => 'LaravelFCM\\FCMServiceProvider',
    ),
    'aliases' => 
    array (
      'FCM' => 'LaravelFCM\\Facades\\FCM',
      'FCMGroup' => 'LaravelFCM\\Facades\\FCMGroup',
    ),
  ),
  'facade/ignition' => 
  array (
    'providers' => 
    array (
      0 => 'Facade\\Ignition\\IgnitionServiceProvider',
    ),
    'aliases' => 
    array (
      'Flare' => 'Facade\\Ignition\\Facades\\Flare',
    ),
  ),
  'fideloper/proxy' => 
  array (
    'providers' => 
    array (
      0 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    ),
  ),
  'fruitcake/laravel-cors' => 
  array (
    'providers' => 
    array (
      0 => 'Fruitcake\\Cors\\CorsServiceProvider',
    ),
  ),
  'itsgoingd/clockwork' => 
  array (
    'providers' => 
    array (
      0 => 'Clockwork\\Support\\Laravel\\ClockworkServiceProvider',
    ),
    'aliases' => 
    array (
      'Clockwork' => 'Clockwork\\Support\\Laravel\\Facade',
    ),
  ),
  'kreait/laravel-firebase' => 
  array (
    'providers' => 
    array (
      0 => 'Kreait\\Laravel\\Firebase\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'FirebaseAuth' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseAuth',
      'FirebaseDatabase' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseDatabase',
      'FirebaseDynamicLinks' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseDynamicLinks',
      'FirebaseFirestore' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseFirestore',
      'FirebaseMessaging' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseMessaging',
      'FirebaseRemoteConfig' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseRemoteConfig',
      'FirebaseStorage' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseStorage',
    ),
  ),
  'laravel-notification-channels/fcm' => 
  array (
    'providers' => 
    array (
      0 => 'NotificationChannels\\Fcm\\FcmServiceProvider',
    ),
  ),
  'laravel/sanctum' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sanctum\\SanctumServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'spatie/laravel-backup' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Backup\\BackupServiceProvider',
    ),
  ),
  'swooletw/laravel-swoole' => 
  array (
    'providers' => 
    array (
      0 => 'SwooleTW\\Http\\LaravelServiceProvider',
    ),
    'aliases' => 
    array (
      'Server' => 'SwooleTW\\Http\\Server\\Facades\\Server',
      'Table' => 'SwooleTW\\Http\\Server\\Facades\\Table',
      'Room' => 'SwooleTW\\Http\\Websocket\\Facades\\Room',
      'Websocket' => 'SwooleTW\\Http\\Websocket\\Facades\\Websocket',
    ),
  ),
);