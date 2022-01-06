<?php

namespace App\Controllers;


class UbahFakultasNonKedokteran extends BaseController
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Fakultas Non Kedokteran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Setting Tanggal Tahap', 'Per Fakultas', 'Fakultas Non Kedokteran'],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'filter' => null,
            'startDate' => null,
            'endDate' => null,
            'tunggakan' => [],
            'icon' => 'https://assets10.lottiefiles.com/packages/lf20_s6bvy00o.json',
            'fakultas' => $this->getFakultas(),
            'listTermYear' => $this->getTermYear(),
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];
        // dd($data);

        return view('pages/ubahFakultasNonKedokteran', $data);
    }

    public function getFakultas()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getFakultas", [
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
            return redirect()->to('ubahFakultasNonKedokteran')->withInput();
        }

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));
        $startDate = trim($this->request->getPost('tahapTanggalAwal')) . ' 00:00:00.000';
        $endDate = trim($this->request->getPost('tahapTanggalAkhir')) . ' 23:59:00.000';

        // dd($filter);

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

        // dd(json_decode($response->getBody())->data);

        $data = [
            'title' => "Fakultas Non Kedokteran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Setting Tanggal Tahap', 'Per Fakultas', 'Fakultas Non Kedokteran'],
            'termYear' => $term_year_id,
            'entryYear' => $entry_year_id,
            'paymentOrder' => $payment_order,
            'filter' => $filter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataUbah' => json_decode($response->getBody())->data,
            'fakultas' => $this->getFakultas(),
            'listTermYear' => $this->getTermYear(),
            'icon' => (json_decode($response->getBody())->status) ? 'https://assets1.lottiefiles.com/packages/lf20_y2hxPc.json' : 'https://assets10.lottiefiles.com/packages/lf20_gO48yV.json',
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];

        session()->setFlashdata('success', 'Berhasil Mengubah Tanggal Tahap !');
        return view('pages/ubahFakultasNonKedokteran', $data);
    }
}
