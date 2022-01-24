<?php

namespace App\Controllers;

use App\Models\PendaftarModel;

class Pendaftar extends BaseController
{
    protected $pendaftarModel;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
    }

    public function index()
    {
        $data = [
            'title' => "Pendaftar",
            'appName' => "Rekap BAAD",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Pendaftar'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listKelompok' => $this->pendaftarModel->getListKelompok(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'termYear' => null,
            'entryYear' => null,
        ];
        // dd($data);

        return view('pages/pendaftar', $data);
    }
}
