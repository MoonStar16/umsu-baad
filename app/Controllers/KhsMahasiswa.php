<?php

namespace App\Controllers;

use App\Models\KhsMahasiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KhsMahasiswa extends BaseController
{
    protected $khsMahasiswaModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->khsMahasiswaModel = new KhsMahasiswaModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "KHS Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'KHS Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->khsMahasiswaModel->getFakultas(),
            'listTermYear' => $this->khsMahasiswaModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/khsMahasiswa', $data);
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
            return redirect()->to('khsMahasiswa')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapKhs = $this->khsMahasiswaModel->getLapKhsMahasiswa($data);
        // dd($lapKhs);
        $data = [
            'title' => "KHS Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'KHS Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->khsMahasiswaModel->getFakultas(),
            'listTermYear' => $this->khsMahasiswaModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapKhs
        ];
        session()->setFlashdata('success', '<strong>' . count($lapKhs) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/khsMahasiswa', $data);
    }
}
