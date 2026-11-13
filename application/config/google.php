<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Konfigurasi Google OAuth 2.0
 */
$config['google_client_id']     = getenv('GOOGLE_CLIENT_ID') ?: '745678901234-dummyappschoolexample.apps.googleusercontent.com';
$config['google_client_secret'] = getenv('GOOGLE_CLIENT_SECRET') ?: 'GOCSPX-dummysecretschoolexample12345';
$config['google_redirect_uri']  = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http")
    . "://" . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost')
    . "/Simple_Akuntansi_Sekolah/Google_auth/callback";

