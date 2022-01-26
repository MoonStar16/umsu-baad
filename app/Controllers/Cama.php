<?php

namespace App\Controllers;

use App\Models\CamaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cama extends BaseController
{
    protected $camaModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->camaModel = new CamaModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Mahasiswa', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->camaModel->getFakultas(),
            'listTermYear' => $this->camaModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/cama', $data);
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
            return redirect()->to('cama')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapCama = $this->camaModel->getLapCama($data);
        // dd($lapCama);
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Mahasiswa', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->camaModel->getFakultas(),
            'listTermYear' => $this->camaModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapCama
        ];
        session()->setFlashdata('success', '<strong>' . count($lapCama) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/cama', $data);
    }
}
