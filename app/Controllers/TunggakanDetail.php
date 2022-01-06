<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class TunggakanDetail extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Detail Tunggakan",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Tunggakan', 'Detail Tunggakan'],
            'tunggakan' => [],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi' => [],
            'filter' => null,
            'fakultas' => $this->getFakultas(),
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];
        // dd($data);

        return view('pages/tunggakanDetail', $data);
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

    public function prosesTunggakanDetail()
    {
        dd($_POST);
        if (!$this->validate([
            'tahunAngkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Angkatan Harus Diisi !',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Diisi !',
                ]
            ],
            'tahap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tunggakan Tahap Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('tunggakanDetail')->withInput();
        }

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));
        // dd($term_year_id, $entry_year_id, $payment_order);


        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "filter" => $filter,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->NAMA_PRODI, $prodi)) {
                array_push($prodi, $k->NAMA_PRODI);
            }
        }

        $data = [
            'title' => "Detail Tunggakan",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Laporan Tunggakan', 'Detail Tunggakan'],
            'tunggakan' => json_decode($response->getBody())->data,
            'termYear' => $term_year_id,
            'entryYear' => $entry_year_id,
            'paymentOrder' => $payment_order,
            'filter' => $filter,
            'fakultas' => $this->getFakultas(),
            'listTermYear' => $this->getTermYear(),
            'prodi' => $prodi,
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Tunggakan, Klik Export Untuk Download !');
        return view('pages/tunggakanDetail', $data);
    }

    public function cetakTunggakanDetailProdi()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "filter" => $filter,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->NAMA_PRODI, $prodi)) {
                array_push($prodi, $k->NAMA_PRODI);
            }
        }


        $spreadsheet = new Spreadsheet();

        // $styleArray = [
        //     'borders' => [
        //         'outline' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
        //             'color' => ['argb' => 'FF000000'],
        //         ],
        //     ],
        // ];

        $default = 1;
        $konten = 0;
        foreach ($prodi as $prd) {
            $konten = $default + $konten;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, $prd)->mergeCells("A" . $konten . ":" . "H" . $konten)->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);
            $konten = $konten + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, 'No.')
                ->setCellValue('B' . $konten, 'No Register')
                ->setCellValue('C' . $konten, 'NPM')
                ->setCellValue('D' . $konten, 'Nama Lengkap')
                ->setCellValue('E' . $konten, 'Angkatan')
                ->setCellValue('F' . $konten, 'Nama Biaya')
                ->setCellValue('G' . $konten, 'Tahap')
                ->setCellValue('H' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);

            $konten = $konten + 1;
            $total = 0;
            $no = 1;
            foreach (json_decode($response->getBody())->data as $data) {
                if ($data->NOMINAL != 0) {
                    if ($prd == $data->NAMA_PRODI) {
                        $total = $total + $data->NOMINAL;
                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $konten, $no++)
                            ->setCellValue('B' . $konten, $data->NO_REGISTER)
                            ->setCellValue('C' . $konten, $data->Npm)
                            ->setCellValue('D' . $konten, $data->NAMA_LENGKAP)
                            ->setCellValue('E' . $konten, $data->ANGKATAN)
                            ->setCellValue('F' . $konten, $data->NAMA_BIAYA)
                            ->setCellValue('G' . $konten, $data->TAHAP)
                            ->setCellValue('H' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "H" . $konten);
                        $konten++;
                    }
                }
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "G" . $konten)->getStyle("A" . $konten . ":" . "G" . $konten)->getFont()->setBold(true);
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A" . $konten . ":" . "G" . $konten)->getAlignment()->setHorizontal('center');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $konten, number_to_currency($total, 'IDR'))->getStyle('H' . $konten)->getFont()->setBold(true);
            $konten = $konten + 1;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Detail Tunggakan Mahasiswa - Prodi';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        return $this->index('tunggakan');
    }

    public function cetakTunggakanDetailSeluruh()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "filter" => $filter,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->NAMA_PRODI, $prodi)) {
                array_push($prodi, $k->NAMA_PRODI);
            }
        }


        $spreadsheet = new Spreadsheet();

        // $styleArray = [
        //     'borders' => [
        //         'outline' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
        //             'color' => ['argb' => 'FF000000'],
        //         ],
        //     ],
        // ];

        $konten = 0;
        $konten = $konten + 1;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $konten, 'No.')
            ->setCellValue('B' . $konten, 'No Register')
            ->setCellValue('C' . $konten, 'NPM')
            ->setCellValue('D' . $konten, 'Nama Lengkap')
            ->setCellValue('E' . $konten, 'Prodi')
            ->setCellValue('F' . $konten, 'Angkatan')
            ->setCellValue('G' . $konten, 'Nama Biaya')
            ->setCellValue('H' . $konten, 'Tahap')
            ->setCellValue('I' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);

        $konten = $konten + 1;
        $total = 0;
        $no = 1;
        foreach (json_decode($response->getBody())->data as $data) {
            // if ($prd == $data->NAMA_PRODI) {
            $total = $total + $data->NOMINAL;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, $no++)
                ->setCellValue('B' . $konten, $data->NO_REGISTER)
                ->setCellValue('C' . $konten, $data->Npm)
                ->setCellValue('D' . $konten, $data->NAMA_LENGKAP)
                ->setCellValue('E' . $konten, $data->NAMA_PRODI)
                ->setCellValue('F' . $konten, $data->ANGKATAN)
                ->setCellValue('G' . $konten, $data->NAMA_BIAYA)
                ->setCellValue('H' . $konten, $data->TAHAP)
                ->setCellValue('I' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "H" . $konten);
            $konten++;
            // }
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "H" . $konten)->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A" . $konten . ":" . "H" . $konten)->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $konten, number_to_currency($total, 'IDR'))->getStyle('I' . $konten)->getFont()->setBold(true);
        $konten = $konten + 1;


        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Detail Tunggakan Mahasiswa - Seluruh';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        return $this->index('tunggakan');
    }
}
