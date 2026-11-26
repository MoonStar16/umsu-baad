<?php

namespace App\Controllers;

use App\Models\UbahEmailModel;

class UbahEmail extends BaseController
{
    protected $ubahEmailModel;

    public function __construct()
    {
        $this->ubahEmailModel = new UbahEmailModel();
    }

    public function index()
    {
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Ubah Email",
            'appName' => "UMSU",
            'breadcrumb' => ['Setting', 'Ubah Email'],
            'dosen' => $this->ubahEmailModel->getDosen()->data,
            'validation' => \Config\Services::validation(),

        ];
        return view('pages/ubahEmail', $data);
    }

    public function getMahasiswa()
    {
        $angkatan = $this->request->getVar('angkatan');
        $data = $this->ubahEmailModel->getMahasiswa($angkatan);
        echo json_encode($data);
    }

    public function update()
    {
        $tipe = $this->request->getVar('tipe');

        if ($tipe == 'mahasiswa') {
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
                'emailMhs' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Email Harus Diisi!',
                    ]
                ],
            ])) {
                return redirect()->to('ubahEmail')->withInput();
            }

            $data = [
                'Email_General' => trim($this->request->getPost('emailMhs')),
                'Nim' => trim(explode('-', $this->request->getPost('npm'))[1]),
            ];

            $exe = $this->ubahEmailModel->updateEmailMhs($data);
            session()->setFlashdata((($exe->status) ? 'successMhs' : 'failedMhs'), $exe->message);
        } else {
            if (!$this->validate([
                'dosen' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Dosen Harus Dipilih!',
                    ]
                ],
                'emailDosen' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Email Harus Diisi!',
                    ]
                ],
            ])) {
                return redirect()->to('ubahEmail')->withInput();
            }

            $data = [
                'Employee_Id' => trim($this->request->getPost('dosen')),
                'Email_Corporate' => trim($this->request->getPost('emailDosen')),
            ];

            $exe = $this->ubahEmailModel->updateEmailDosen($data);
            session()->setFlashdata((($exe->status) ? 'successDosen' : 'failedDosen'), $exe->message);
        }
        return redirect()->to('ubahEmail');
    }
}
