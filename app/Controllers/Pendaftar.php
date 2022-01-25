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
            'title' => "Data Calon Pendaftar",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Pendaftar'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->pendaftarModel->getFakultas(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/pendaftar', $data);
    }

    public function proses()
    {

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapPendaftar = $this->pendaftarModel->getLapPendaftar($data);
        $data = [
            'title' => "Data Calon Pendaftar",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Pendaftar'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->pendaftarModel->getFakultas(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapPendaftar
        ];
        return view('pages/pendaftar', $data);
    }
}
