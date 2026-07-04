<?php
return array (
  'site_name' => 'Maner Pvt ITI',
  'site_url' => '',
  'base_path' => '',
  'debug' => false,
  'timezone' => 'Asia/Kolkata',
  'db_host' => 'localhost',
  'db_name' => 'your_database',
  'db_user' => 'your_username',
  'db_pass' => 'your_password',
  'db_charset' => 'utf8mb4',
  'upload_dir' => '',
  'upload_legacy_dir' => '',
  'upload_max_mb' => 10,
  'allowed_upload_ext' =>
  array (
    0 => 'jpg',
    1 => 'jpeg',
    2 => 'png',
    3 => 'webp',
    4 => 'pdf',
  ),
  'upload_webp_quality' => 82,
  'upload_image_max_width' => 1600,
  'upload_image_max_height' => 1600,
  'session_name' => 'maner_iti_session',
  'installed_lock' => '',

  // Document storage: 'local' (server uploads/) or 'r2' (Cloudflare R2)
  'storage_driver' => 'local',
  'r2_account_id' => '',
  'r2_access_key' => '',
  'r2_secret_key' => '',
  'r2_bucket' => '',
  'r2_public_url' => '', // e.g. https://pub-xxxxx.r2.dev or https://files.yourdomain.com
  'r2_prefix' => 'uploads',
  'r2_region' => 'auto',
  'r2_delete_local' => '1', // delete from server after upload to R2
);
