<?php

namespace App\Controllers;


class UbahProdiKedokteran extends BaseController
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Prodi Kedokteran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Setting Tanggal Tahap', 'Per Prodi', 'Prodi Kedokteran'],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'filter' => null,
            'startDate' => null,
            'endDate' => null,
            'tunggakan' => [],
            'prodi' => $this->getProdi('Kedokteran'),
            'icon' => 'https://assets10.lottiefiles.com/packages/lf20_s6bvy00o.json',
            'listTermYear' => $this->getTermYear(),
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];
        // dd($data);

        return view('pages/ubahProdiKedokteran', $data);
    }

    public function getProdi($ket)
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getProdi?fakultas=" . $ket, [
            "headers" => [
                "Accept" => "application/json"
            ],

        ]);

        return json_decode($response->getBody())->data;
    }

    public function getTermYear()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getTermYear", [
            "headers" => [
                "Accept" => "application/json"
            ],

        ]);

        return json_decode($response->getBody())->data;
    }

    public function proses()
    {
        if (!$this->validate([
            'prodi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Prodi Harus Dipilih2 !',
                ]
            ],
            'tahap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pembayaran Tahap Harus Diisi !',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Diisi !',
                ]
            ],
            'tahapTanggalAwal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Awal Harus Diisi !',
                ]
            ],
            'tahapTanggalAkhir' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Akhir Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('ubahProdiKedokteran')->withInput();
        }

        // dd($_POST);
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $filter = trim($this->request->getPost('prodi'));
        $startDate = trim($this->request->getPost('tahapTanggalAwal')) . ' 00:00:00.000';
        $endDate = trim($this->request->getPost('tahapTanggalAkhir')) . ' 23:59:00.000';

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/updTanggalTahap", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "filter" => $filter,
                "startDate" => $startDate,
                "endDate" => $endDate
            ]
        ]);

        $data = [
            'title' => "Prodi Kedokteran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Setting Tanggal Tahap', 'Per Prodi', 'Prodi Kedokteran'],
            'termYear' => $term_year_id,
            'entryYear' => $entry_year_id,
            'paymentOrder' => $payment_order,
            'filter' => $filter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataUbah' => json_decode($response->getBody())->data,
            'listTermYear' => $this->getTermYear(),
            'prodi' => $this->getProdi('Kedokteran'),
            'icon' => (json_decode($response->getBody())->status) ? 'https://assets1.lottiefiles.com/packages/lf20_y2hxPc.json' : 'https://assets10.lottiefiles.com/packages/lf20_gO48yV.json',
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];

        session()->setFlashdata('success', 'Berhasil Mengubah Tanggal Tahap !');
        return view('pages/ubahProdiKedokteran', $data);
    }
}
