<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
// $routes->get('/login', 'Login::index');
// $routes->post('/operator/auth', 'Operator::auth');
// $routes->post('/operator/register', 'Operator::register');
// $routes->get('/logout', 'Login::logout');

// Route Home
$routes->get('/home/(:any)', 'Home::index');

// Route Maintenance
$routes->get('/maintenance/(:any)', 'Maintenance::index');

// Route  Tunggakan PerMahasiswa
$routes->get('/tunggakanPerMahasiswa/(:any)', 'TunggakanPerMahasiswa::index');
$routes->post('/tunggakanPerMahasiswa/cetak', 'TunggakanPerMahasiswa::cetakTunggakanPerMahasiswa');
$routes->post('/tunggakanPerMahasiswa', 'TunggakanPerMahasiswa::prosesTunggakanPerMahasiswa');

// Route  Tunggakan Detail
$routes->get('/tunggakanDetail/(:any)', 'TunggakanDetail::index');
$routes->post('/tunggakanDetailProdi/cetak', 'TunggakanDetail::cetakTunggakanDetailProdi');
$routes->post('/tunggakanDetailSeluruh/cetak', 'TunggakanDetail::cetakTunggakanDetailSeluruh');
$routes->post('/tunggakanDetail', 'TunggakanDetail::prosesTunggakanDetail');

// Route  Tunggakan Total
$routes->get('/tunggakanTotal/(:any)', 'TunggakanTotal::index');
$routes->post('/tunggakanTotal/cetak', 'TunggakanTotal::cetakTunggakanTotal');
$routes->post('/tunggakanTotal', 'TunggakanTotal::prosesTunggakanTotal');

// Route Pembayaran Detail
$routes->get('/pembayaranDetail/(:any)', 'PembayaranDetail::index');
$routes->post('/pembayaranDetailProdi/cetak', 'PembayaranDetail::cetakPembayaranDetailProdi');
$routes->post('/pembayaranDetailSeluruh/cetak', 'PembayaranDetail::cetakPembayaranDetailSeluruh');
$routes->post('/pembayaranDetail', "PembayaranDetail::prosesPembayaranDetail");

// Route Pembayaran Total
$routes->get('/pembayaranTotal/(:any)', 'PembayaranTotal::index');
$routes->post('/pembayaranTotal/cetak', 'PembayaranTotal::cetakPembayaranTotal');
$routes->post('/pembayaranTotal', "PembayaranTotal::prosesPembayaranTotal");

// Route Pembayaran Lain-Lain
$routes->get('/pembayaranLain/(:any)', 'PembayaranLain::index');
$routes->post('/pembayaranLainProdi/cetak', 'PembayaranLain::cetakPembayaranLainProdi');
$routes->post('/pembayaranLainSeluruh/cetak', 'PembayaranLain::cetakPembayaranLainSeluruh');
$routes->post('/pembayaranLain', "PembayaranLain::prosesPembayaranLain");

// Route Ubah Tanggal Tahap Angkatan
$routes->get('/ubahAngkatan/(:any)', 'UbahAngkatan::index');
$routes->post('/ubahAngkatan', "UbahAngkatan::proses");

// Route Ubah Tanggal Tahap Fakultas Non  Kedokteran
$routes->get('/ubahFakultasNonKedokteran/(:any)', 'UbahFakultasNonKedokteran::index');
$routes->post('/ubahFakultasNonKedokteran', "UbahFakultasNonKedokteran::proses");

// Route Ubah Tanggal Tahap Fakultas Kedokteran
$routes->get('/ubahFakultasKedokteran/(:any)', 'UbahFakultasKedokteran::index');
$routes->post('/ubahFakultasKedokteran', "UbahFakultasKedokteran::proses");

// Route Ubah Tanggal Tahap Fakultas Pascasarjana
$routes->get('/ubahFakultasPascasarjana/(:any)', 'UbahFakultasPascasarjana::index');
$routes->post('/ubahFakultasPascasarjana', "UbahFakultasPascasarjana::proses");

// Route Ubah Tanggal Tahap Prodi Non Kedokteran
$routes->get('/ubahProdiNonKedokteran/(:any)', 'UbahProdiNonKedokteran::index');
$routes->post('/ubahProdiNonKedokteran', "UbahProdiNonKedokteran::proses");

// Route Ubah Tanggal Tahap Prodi Kedokteran
$routes->get('/ubahProdiKedokteran/(:any)', 'UbahProdiKedokteran::index');
$routes->post('/ubahProdiKedokteran', "UbahProdiKedokteran::proses");

// Route Ubah Tanggal Prodi Tahap Prodi Pascasarjana
$routes->get('/ubahProdiPascasarjana/(:any)', 'UbahProdiPascasarjana::index');
$routes->post('/ubahProdiPascasarjana', "UbahProdiPascasarjana::proses");

// Route KRS Aktif
$routes->get('/krsAktif/(:any)', 'KrsAktif::index');
$routes->post('/krsAktif/cetak', 'KrsAktif::cetakKrsAktif');
$routes->post('/krsAktif', 'KrsAktif::prosesKrsAktif');

// Route Mahasiswa KRS Aktif
$routes->get('/mahasiswaKrsAktif/(:any)', 'MahasiswaKrsAktif::index');
$routes->post('/mahasiswaKrsAktifProdi/cetak', 'MahasiswaKrsAktif::cetakKrsAktifProdi');
$routes->post('/mahasiswaKrsAktifSeluruh/cetak', 'MahasiswaKrsAktif::cetakKrsAktifSeluruh');
$routes->post('/mahasiswaKrsAktif', 'MahasiswaKrsAktif::prosesKrsAktif');

// Route Ubah Tanggal Tarif
$routes->get('/ubahTarif/(:any)', 'UbahTarif::index');
$routes->post('/ubahTarif', "UbahTarif::proses");



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
