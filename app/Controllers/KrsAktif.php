<?php

namespace App\Controllers;

use App\Models\KrsAktifModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KrsAktif extends BaseController
{
    protected $krsAktifModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->krsAktifModel = new KrsAktifModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "KRS Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'KRS Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->krsAktifModel->getFakultas(),
            'listTermYear' => $this->krsAktifModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/krsAktif', $data);
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
            return redirect()->to('krsAktif')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapKrsAktif = $this->krsAktifModel->getLapKrsAktif($data);
        // dd($lapKrsAktif);
        $data = [
            'title' => "KRS Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'KRS Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->krsAktifModel->getFakultas(),
            'listTermYear' => $this->krsAktifModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapKrsAktif
        ];
        session()->setFlashdata('success', '<strong>' . count($lapKrsAktif) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/krsAktif', $data);
    }
}
