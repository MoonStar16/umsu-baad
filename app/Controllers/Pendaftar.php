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
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Pendaftar', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->pendaftarModel->getFakultas(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/pendaftar', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'fakultas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Fakultas Harus Dipilih!',
                ]
            ],
            'tahunAngkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Angkatan Harus Dipilih!',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('pembayaranDetail')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapPendaftar = $this->pendaftarModel->getLapPendaftar($data);
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Pendaftar', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->pendaftarModel->getFakultas(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapPendaftar
        ];
        session()->setFlashdata('success', '<strong>' . count($lapPendaftar) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/pendaftar', $data);
    }
}
