<?php

namespace App\Controllers;

use App\Models\HapusTagihanModel;

class HapusTagihan extends BaseController
{
    protected $hapusTagihanModel;

    public function __construct()
    {
        $this->hapusTagihanModel = new HapusTagihanModel();
    }

    public function index()
    {
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Hapus Tagihan Lain",
            'appName' => "UMSU",
            'breadcrumb' => ['Setting', 'Hapus Tagihan Lain'],
            'tahunAjaran' => $this->hapusTagihanModel->getTermYear(),
            'tagihanLain' => [
                'Biaya_Kelas_Malam' => 'Kelas Malam',
                'Biaya_Kompri' => 'Kompri',
                'Biaya_Praktikum' => 'Praktikum',
                'Biaya_Ujian_Susulan' => 'Ujian Susulan',
                'Biaya_Remedial' => 'Remedial',
                'Biaya_Seminar' => 'Seminar',
                'Biaya_Sidang' => 'Sidang',
                'Biaya_Wisuda' => 'Wisuda',
            ],
            'validation' => \Config\Services::validation(),
        ];
        return view('pages/hapusTagihan', $data);
    }

    public function delete()
    {
        if (!$this->validate([
            'angkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Angkatan Harus Dipilih!',
                ]
            ],
            'npm' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Mahasiswa Harus Dipilih!',
                ]
            ],
            'tahun' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Harus Dipilih!',
                ]
            ],
            'tagihan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tagihan Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('hapusTagihan')->withInput();
        }

        $data = [
            'Nim' => trim(explode('-', $this->request->getPost('npm'))[1]),
            'Term_Year_Id' => trim($this->request->getPost('tahun')),
            'Tagihan_Lain' => trim($this->request->getPost('tagihan')),
        ];

        $exe = $this->hapusTagihanModel->hapusTagihanLain($data);
        session()->setFlashdata((($exe->status) ? 'success' : 'failed'), $exe->message);

        return redirect()->to('hapusTagihan');
    }
}
