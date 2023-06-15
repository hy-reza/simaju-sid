<?php
// -------------------------------------------------------------------------
//
// Letakkan username, password dan database sebetulnya di file ini.
// File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
// -------------------------------------------------------------------------

// Data Konfigurasi MySQL yang disesuaikan

$db['default']['hostname'] = 'containers-us-west-31.railway.app';
$db['default']['username'] = 'root';
$db['default']['password'] = '8T4D9RGPZTHJGZQCBaL0';
$db['default']['port']     = 5465;
$db['default']['database'] = 'railway';

/*
| Untuk setting koneksi database 'Strict Mode'
| Sesuaikan dengan ketentuan hosting
*/
$db['default']['stricton'] = TRUE;