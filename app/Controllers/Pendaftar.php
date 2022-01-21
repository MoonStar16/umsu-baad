<?php

namespace App\Controllers;

use App\Models\PendaftarModel;

class Pendaftar extends BaseController
{
    protected $pendaftarModel;

    function __construct()
    {
        $this->db = db_connect();
        $this->pendaftarModel = new PendaftarModel();
    }

    public function index()
    {
        $pendaftar = $this->pendaftarModel->getFromApi();
        $data = [
            'title' => "Home",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Dashboard'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'data' => $pendaftar->paginate(3, 'data')
        ];
        dd($data['data']);
        return view('pages/pendaftar', $data);
    }
}
