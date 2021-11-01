<?php

return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'My_ApiKey',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/pushcert.pem',
//      'certificate' => __DIR__ . '/iosCertificates/pushcert_live.pem',
//      'passPhrase' => '', //Optional
//      'passFile' => __DIR__ . '/iosCertificates/pushcert_live.pem', //Optional
      'dry_run' => false,
  ]
];