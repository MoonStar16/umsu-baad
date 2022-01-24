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
            'title' => "Jumlah KRS Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan KRS Aktif', 'Jumlah KRS Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'dataFromApi' => $this->pendaftarModel->fromApi(),
        ];
        dd($data);

        return view('pages/krsAktif', $data);
    }
}
