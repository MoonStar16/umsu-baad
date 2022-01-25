<?php

namespace App\Controllers;

use App\Models\RegulangModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Regulang extends BaseController
{
    protected $regulangModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->regulangModel = new RegulangModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Data Registrasi Ulang",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Registrasi Ulang'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->regulangModel->getFakultas(),
            'listTermYear' => $this->regulangModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/regulang', $data);
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
            return redirect()->to('regulang')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapRegulang = $this->regulangModel->getLapRegulang($data);
        // dd($lapRegulang);
        $data = [
            'title' => "Data Registrasi Ulang",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Registrasi Ulang'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->regulangModel->getFakultas(),
            'listTermYear' => $this->regulangModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapRegulang
        ];
        session()->setFlashdata('success', '<strong>' . count($lapRegulang) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/regulang', $data);
    }
}
