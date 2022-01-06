<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PembayaranDetail extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Detail Pembayaran Pokok",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran Pokok', 'Detail Pembayaran Pokok'],
            'pembayaran' => [],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'bank' => null,
            'listTermYear' => $this->getTermYear(),
            'listBank' => $this->getBank(),
            'prodi' => [],
            'filter' => null,
            'fakultas' => $this->getFakultas(),
            'icon' => 'https://assets2.lottiefiles.com/packages/lf20_yzoqyyqf.json',
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];
        // dd($data);

        return view('pages/pembayaranDetail', $data);
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

    public function getBank()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getBank", [
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

    public function prosesPembayaranDetail()
    {
        if (!$this->validate([
            'tahap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pembayaran Tahap Harus Diisi !',
                ]
            ],
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
        ])) {
            return redirect()->to('pembayaranDetail')->withInput();
        }

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $bank = trim($this->request->getPost('bank'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));
        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "bank" => $bank,
                "filter" => $filter,
            ]
        ]);
        
        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->PRODI, $prodi)) {
                array_push($prodi, $k->PRODI);
            }
        }
        $pembayaran = json_decode($response->getBody())->data;

        $data = [
            'title' => "Detail Pembayaran Pokok",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Detail Pembayaran Pokok'],
            'termYear' => $term_year_id,
            'entryYear' => $entry_year_id,
            'paymentOrder' => $payment_order,
            'filter' => $filter,
            'fakultas' => $this->getFakultas(),
            'bank' => $bank,
            'pembayaran' => $pembayaran,
            'listTermYear' => $this->getTermYear(),
            'listBank' => $this->getBank(),
            'prodi' => $prodi,
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Pembayaran, Klik Export Untuk Download !');
        return view('pages/pembayaranDetail', $data);
    }

    public function cetakPembayaranDetailProdi()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $bank = trim($this->request->getPost('bank'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "bank" => $bank,
                "filter" => $filter,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->PRODI, $prodi)) {
                array_push($prodi, $k->PRODI);
            }
        }

        $spreadsheet = new Spreadsheet();

        $default = 1;
        $konten = 0;
        foreach ($prodi as $prd) {
            $konten = $default + $konten;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, $prd)->mergeCells("A" . $konten . ":" . "I" . $konten)->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);
            $konten = $konten + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, 'No.')
                ->setCellValue('B' . $konten, 'No Register')
                ->setCellValue('C' . $konten, 'NPM')
                ->setCellValue('D' . $konten, 'Nama Lengkap')
                ->setCellValue('E' . $konten, 'Angkatan')
                ->setCellValue('F' . $konten, 'Nama Biaya')
                ->setCellValue('G' . $konten, 'Bank')
                ->setCellValue('H' . $konten, 'Tahap')
                ->setCellValue('I' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);

            $konten = $konten + 1;
            $total = 0;
            $no = 1;
            foreach (json_decode($response->getBody())->data as $data) {
                if ($prd == $data->PRODI) {
                    $total = $total + $data->NOMINAL;
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $konten, $no++)
                        ->setCellValue('B' . $konten, $data->NO_REGISTER)
                        ->setCellValue('C' . $konten, $data->Npm)
                        ->setCellValue('D' . $konten, $data->NAMA_LENGKAP)
                        ->setCellValue('E' . $konten, $data->ANGKATAN)
                        ->setCellValue('F' . $konten, $data->NAMA_BIAYA)
                        ->setCellValue('G' . $konten, $data->BANK_NAMA)
                        ->setCellValue('H' . $konten, ($data->TAHAP == 0) ? "Lunas" : "Tahap " . $data->TAHAP)
                        ->setCellValue('I' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "I" . $konten);
                    $konten++;
                }
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "H" . $konten)->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);
            $spreadsheet->setActiveSheetIndex(0)->getStyle("A" . $konten . ":" . "H" . $konten)->getAlignment()->setHorizontal('center');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $konten, number_to_currency($total, 'IDR'))->getStyle('I' . $konten)->getFont()->setBold(true);
            $konten = $konten + 1;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Detail Pembayaran Pokok Mahasiswa - Prodi';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }

    public function cetakPembayaranDetailSeluruh()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $payment_order = trim($this->request->getPost('tahap'));
        $bank = trim($this->request->getPost('bank'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "bank" => $bank,
                "filter" => $filter,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->PRODI, $prodi)) {
                array_push($prodi, $k->PRODI);
            }
        }

        $spreadsheet = new Spreadsheet();

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
            ->setCellValue('H' . $konten, 'Bank')
            ->setCellValue('I' . $konten, 'Tahap')
            ->setCellValue('J' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "J" . $konten)->getFont()->setBold(true);

        $konten = $konten + 1;
        $total = 0;
        $no = 1;
        foreach (json_decode($response->getBody())->data as $data) {
            $total = $total + $data->NOMINAL;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, $no++)
                ->setCellValue('B' . $konten, $data->NO_REGISTER)
                ->setCellValue('C' . $konten, $data->Npm)
                ->setCellValue('D' . $konten, $data->NAMA_LENGKAP)
                ->setCellValue('E' . $konten, $data->PRODI)
                ->setCellValue('F' . $konten, $data->ANGKATAN)
                ->setCellValue('G' . $konten, $data->NAMA_BIAYA)
                ->setCellValue('H' . $konten, $data->BANK_NAMA)
                ->setCellValue('I' . $konten, ($data->TAHAP == 0) ? "Lunas" : "Tahap " . $data->TAHAP)
                ->setCellValue('J' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "J" . $konten);
            $konten++;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "I" . $konten)->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A" . $konten . ":" . "I" . $konten)->getAlignment()->setHorizontal('center');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $konten, number_to_currency($total, 'IDR'))->getStyle('J' . $konten)->getFont()->setBold(true);
        $konten = $konten + 1;

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Detail Pembayaran Pokok Mahasiswa - Seluruh';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
